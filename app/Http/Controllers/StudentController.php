<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nama_orang_tua', 'like', "%{$search}%")
                  ->orWhere('sekolah', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(10);

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_siswa' => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:255',
            'alamat_tagihan' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|min:6',
            'nis' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $userId = null;
            if ($request->filled('email')) {
                $user = User::create([
                    'name' => $request->nama_siswa,
                    'email' => $request->email,
                    'password' => bcrypt($request->password ?? 'password123'),
                    'role' => 'student',
                ]);
                $userId = $user->id;
            }

            Siswa::create([
                'nama_siswa' => $request->nama_siswa,
                'nama_orang_tua' => $request->nama_orang_tua,
                'alamat_tagihan' => $request->alamat_tagihan,
                'user_id' => $userId,
                'nis' => $request->nis,
                'class' => $request->class,
            ]);

            DB::commit();
            return redirect()->route('students.index')
                ->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $student)
    {
        $validator = Validator::make($request->all(), [
            'nama_siswa' => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:255',
            'alamat_tagihan' => 'required|string',
            'nis' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . ($student->user_id ?? 'NULL'),
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $userId = $student->user_id;

            if ($request->filled('email')) {
                if ($student->user) {
                    // Update existing user login details
                    $userData = [
                        'name' => $request->nama_siswa,
                        'email' => $request->email,
                    ];
                    if ($request->filled('password')) {
                        $userData['password'] = bcrypt($request->password);
                    }
                    $student->user->update($userData);
                } else {
                    // Create new user login if none existed
                    $newUser = User::create([
                        'name' => $request->nama_siswa,
                        'email' => $request->email,
                        'password' => bcrypt($request->password ?? 'password123'),
                        'role' => 'student',
                        'is_profile_completed' => false,
                    ]);
                    $userId = $newUser->id;
                }
            } elseif ($student->user) {
                // If student has a user but we want to sync the name if changed
                $student->user->update([
                    'name' => $request->nama_siswa,
                ]);
            }

            $student->update([
                'nama_siswa' => $request->nama_siswa,
                'nama_orang_tua' => $request->nama_orang_tua,
                'alamat_tagihan' => $request->alamat_tagihan,
                'nis' => $request->nis,
                'class' => $request->class,
                'user_id' => $userId,
            ]);

            DB::commit();
            return redirect()->route('students.index')
                ->with('success', 'Siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui siswa: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $student)
    {
        // Cek apakah siswa memiliki invoice
        if ($student->invoices()->count() > 0) {
            return redirect()->route('students.index')
                ->with('error', 'Siswa tidak dapat dihapus karena memiliki invoice.');
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }
}
