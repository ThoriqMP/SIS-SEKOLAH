<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\VideoConference;

class VideoConferenceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $conferences = VideoConference::where(function($query) use ($user) {
                $query->whereJsonContains('class', (string)$user->id)
                      ->orWhereJsonContains('class', $user->id)
                      ->orWhere('class', 'like', '%"' . $user->id . '"%');
            })
            ->where('is_active', true)
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return view('student.video_conferences.index', compact('conferences'));
    }

    public function join($room)
    {
        $user = Auth::user();
        $conference = VideoConference::where('room_name', $room)->first();
        
        if (!$conference) {
            abort(404, 'Kelas Online tidak ditemukan.');
        }

        if (!$conference->is_active) {
            return redirect()->route('video-conferences.index')->with('info', 'Kelas Online ini telah selesai.');
        }

        // Authorization check: Make sure student is targeted
        $isTargeted = false;
        if (is_array($conference->class)) {
            $isTargeted = in_array((string)$user->id, $conference->class) || in_array($user->id, $conference->class);
        } else {
            $isTargeted = $conference->class == $user->id;
        }

        if (!$isTargeted) {
            abort(403, 'Anda tidak terdaftar dalam Kelas Online ini.');
        }
        
        // Pass essential variables to view
        $userId = $user->id;
        $userName = $user->name;
        $role = 'student';
        $userClass = $user->siswa->class ?? 'Siswa';

        return view('student.video_conference', compact('room', 'userId', 'userName', 'role', 'conference', 'userClass'));
    }
}
