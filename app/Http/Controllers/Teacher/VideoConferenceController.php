<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VideoConference;
use App\Models\Siswa;
use Illuminate\Support\Str;

class VideoConferenceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPimpinan = $user->isPimpinan() || $user->isSuperadmin();

        if ($isPimpinan) {
            // Pimpinan melihat seluruh kelas online yang dibuat oleh guru-guru
            $conferences = VideoConference::with('teacher')
                ->orderBy('scheduled_at', 'desc')
                ->get();
        } else {
            $conferences = VideoConference::with('teacher')
                ->where('teacher_id', $user->id)
                ->orWhereJsonContains('class', (int) $user->id)
                ->orWhereJsonContains('class', (string) $user->id)
                ->orderBy('scheduled_at', 'desc')
                ->get();
        }

        $students = Siswa::orderBy('nama_siswa', 'asc')->get();

        return view('teacher.video_conferences.index', compact('conferences', 'students', 'isPimpinan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'students' => 'nullable|array',
            'teachers' => 'nullable|array',
            'scheduled_at' => 'required|date',
        ]);

        $students = array_map('intval', (array) $request->input('students', []));
        $teachers = array_map('intval', (array) $request->input('teachers', []));
        $mergedParticipants = array_values(array_unique(array_filter(array_merge($students, $teachers))));

        if (empty($mergedParticipants)) {
            return back()->withErrors(['students' => 'Pilih setidaknya satu siswa atau guru untuk diundang.']);
        }

        VideoConference::create([
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'class' => $mergedParticipants,
            'room_name' => 'room-' . strtolower(Str::random(10)),
            'scheduled_at' => $request->scheduled_at,
            'is_active' => true,
        ]);

        return redirect()->route('teacher.video-conferences.index')->with('success', 'Kelas Online berhasil dijadwalkan!');
    }

    public function destroy(VideoConference $videoConference)
    {
        $user = Auth::user();
        if ($videoConference->teacher_id != $user->id && !$user->isSuperadmin()) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus jadwal ini.');
        }

        $videoConference->delete();

        return redirect()->route('teacher.video-conferences.index')->with('success', 'Jadwal Kelas Online berhasil dihapus.');
    }

    public function join($room)
    {
        $user = Auth::user();
        $conference = VideoConference::where('room_name', $room)->first();

        if (!$conference) {
            abort(404, 'Kelas Online tidak ditemukan.');
        }

        $isHost = ($conference->teacher_id == $user->id);
        $isInvited = is_array($conference->class) && (in_array($user->id, $conference->class) || in_array((string)$user->id, $conference->class));
        $isPimpinan = $user->isPimpinan() || $user->isSuperadmin();

        // Authorization: host teacher, invited participant, OR pimpinan can join
        if (!$isHost && !$isInvited && !$isPimpinan) {
            abort(403, 'Anda tidak memiliki hak untuk masuk ke Kelas Online ini.');
        }

        // Pass essential variables to view
        $userId = $user->id;
        $userName = $user->name;

        if ($isPimpinan && !$isHost) {
            $role = 'pimpinan';
            $userClass = 'Pimpinan Sekolah';
        } else {
            $role = $isHost ? 'teacher' : 'guest_teacher';
            $userClass = 'Guru';
        }

        // Jika guru host masuk kembali ke kelas yang sempat dinonaktifkan, aktifkan kembali
        if (!$conference->is_active && ($isHost || $isPimpinan)) {
            $conference->update(['is_active' => true]);
        }

        return view('teacher.video_conference', compact('room', 'userId', 'userName', 'role', 'conference', 'userClass'));
    }

    public function leaveRoom($room)
    {
        return redirect()->route('teacher.video-conferences.index')
            ->with('info', 'Anda telah keluar dari ruangan. Kelas tetap berjalan dan Anda dapat bergabung kembali kapan saja.');
    }

    public function endMeeting($room)
    {
        $user = Auth::user();
        $conference = VideoConference::where('room_name', $room)->first();

        if ($conference && ($conference->teacher_id == $user->id || $user->isPimpinan() || $user->isSuperadmin())) {
            $conference->update(['is_active' => false]);
        }

        return redirect()->route('teacher.video-conferences.index')->with('success', 'Pertemuan kelas online telah diakhiri untuk semua peserta.');
    }
}
