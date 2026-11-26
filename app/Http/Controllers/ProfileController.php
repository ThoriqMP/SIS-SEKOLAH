<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $view = $user->role === 'student' ? 'student.profile' : 'profile.edit';

        if (!view()->exists($view)) {
            return redirect()->back()->with('info', 'Halaman profil belum tersedia.');
        }

        $student = $user->siswa ?? new \App\Models\Siswa([
            'nama_siswa' => $user->name,
            'nis' => '-',
            'class' => 'Umum',
        ]);

        $sessions = collect();
        try {
            if (config('session.driver') === 'database') {
                $sessions = \Illuminate\Support\Facades\DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->orderByDesc('last_activity')
                    ->get();
            }
        } catch (\Throwable $e) {
            $sessions = collect();
        }

        return view($view, compact('user', 'student', 'sessions'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function logoutDevice(Request $request)
    {
        Auth::logoutOtherDevices($request->input('password', ''));
        return back()->with('success', 'Sesi perangkat lain berhasil dikeluarkan.');
    }
}
