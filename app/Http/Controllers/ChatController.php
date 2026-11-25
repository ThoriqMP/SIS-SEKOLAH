<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    // Get list of contacts (other users)
    public function getContacts(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Direct browser navigation guard: Never dump raw JSON to browser page visits
        if (! $request->expectsJson() && ! $request->ajax()) {
            return redirect()->route($currentUser?->role === 'student' ? 'student.dashboard' : 'dashboard');
        }
        
        // Update user online status
        Cache::put('user_online_' . $currentUser->id, true, now()->addMinutes(1));

        $query = User::where('id', '!=', $currentUser->id);

        // Filter: Student only see admins/pimpinan/guru
        if ($currentUser->role === 'student') {
            $query->whereIn('role', ['admin', 'pimpinan', 'guru']);
        }

        $users = $query->select('id', 'name', 'role')
            ->get()
            ->map(function ($user) use ($currentUser) {
                // Count unread messages from this specific user to me
                $unreadCount = Message::where('user_id', $user->id)
                    ->where('receiver_id', $currentUser->id)
                    ->where('is_read', false)
                    ->count();
                
                $user->unread_count = $unreadCount;
                $user->is_online = Cache::has('user_online_' . $user->id);
                return $user;
            });

        // Total unread for badge
        $totalUnread = Message::where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'contacts' => $users,
            'total_unread' => $totalUnread
        ]);
    }

    // Get messages between current user and specific user
    public function getMessages(Request $request, $userId)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Direct browser navigation guard: Never dump raw JSON to browser page visits
        if (! $request->expectsJson() && ! $request->ajax()) {
            return redirect()->route($currentUser?->role === 'student' ? 'student.dashboard' : 'dashboard');
        }

        // Security check for students: can only chat with admin/pimpinan/guru
        if ($currentUser->role === 'student') {
            $otherUser = User::find($userId);
            if (!$otherUser || !in_array($otherUser->role, ['admin', 'pimpinan', 'guru'])) {
                return response()->json(['messages' => [], 'is_typing' => false], 403);
            }
        }

        $currentUserId = $currentUser->id;

        $messages = Message::where(function($q) use ($currentUserId, $userId) {
                $q->where('user_id', $currentUserId)->where('receiver_id', $userId);
            })
            ->orWhere(function($q) use ($currentUserId, $userId) {
                $q->where('user_id', $userId)->where('receiver_id', $currentUserId);
            })
            ->with('user:id,name,role')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        // Check if other user is typing (specific to this chat)
        $isTyping = Cache::has('user_is_typing_' . $userId);

        return response()->json([
            'messages' => $messages,
            'is_typing' => $isTyping
        ]);
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id'
        ]);

        // Security check for students: can only chat with admin/pimpinan/guru
        if ($currentUser->role === 'student') {
            $receiver = User::find($request->receiver_id);
            if (!$receiver || !in_array($receiver->role, ['admin', 'pimpinan', 'guru'])) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }

        $message = Message::create([
            'user_id' => $currentUser->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json($message->load('user:id,name,role'));
    }

    public function markAsRead(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        $request->validate([
            'sender_id' => 'required|exists:users,id'
        ]);

        // Security check for students: can only mark messages from admin/pimpinan/guru as read
        if ($currentUser->role === 'student') {
            $sender = User::find($request->sender_id);
            if (!$sender || !in_array($sender->role, ['admin', 'pimpinan', 'guru'])) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }

        Message::where('user_id', $request->sender_id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }

    public function typing()
    {
        Cache::put('user_is_typing_' . Auth::id(), true, now()->addSeconds(3));
        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $message = Message::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $message->delete(); // Soft delete

        return response()->json(['status' => 'success', 'id' => $id]);
    }
}
