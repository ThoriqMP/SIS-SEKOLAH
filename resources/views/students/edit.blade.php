@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Siswa</h2>
                    <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        Kembali
                    </a>
                </div>

                @if ($errors->any())
        <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 dark:text-red-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Terjadi kesalahan</h3>
                    <div class="mt-1 text-sm text-red-700 dark:text-red-200">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

                <form method="POST" action="{{ route('students.update', $student) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="nama_siswa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Siswa</label>
                            <input type="text" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $student->nama_siswa) }}" required
                                   placeholder="Masukkan nama lengkap siswa"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400">
                        </div>
                        
                        <div>
                            <label for="nama_orang_tua" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Orang Tua</label>
                            <input type="text" id="nama_orang_tua" name="nama_orang_tua" value="{{ old('nama_orang_tua', $student->nama_orang_tua) }}" required
                                   placeholder="Masukkan nama orang tua"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400">
                        </div>

                        <div>
                            <label for="nis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIS</label>
                            <input type="text" id="nis" name="nis" value="{{ old('nis', $student->nis) }}"
                                   placeholder="Nomor Induk Siswa"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400">
                        </div>

                        @php
                            $studentEducationLevel = '';
                            $studentClassNum = '';
                            $currentClass = old('class', $student->class);
                            if ($currentClass) {
                                $parts = explode(' ', $currentClass);
                                if (count($parts) === 2) {
                                    $studentClassNum = $parts[0];
                                    $studentEducationLevel = $parts[1];
                                }
                            }
                        @endphp
                        
                        <div x-data="{ 
                            educationLevel: '{{ old('education_level', $studentEducationLevel) }}',
                            classNum: '{{ old('class_num', $studentClassNum) }}'
                        }" class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="hidden" name="class" :value="classNum && educationLevel ? classNum + ' ' + educationLevel : ''">
                            
                            <div>
                                <label for="education_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenjang Pendidikan</label>
                                <select id="education_level" x-model="educationLevel" @change="classNum = ''"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base cursor-pointer">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="SD">Sekolah Dasar (SD)</option>
                                    <option value="SMP">Sekolah Menengah Pertama (SMP)</option>
                                    <option value="SMA">Sekolah Menengah Atas (SMA)</option>
                                </select>
                            </div>

                            <div>
                                <label for="class_num" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tingkat Kelas</label>
                                <select id="class_num" x-model="classNum" :disabled="!educationLevel"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                    <option value="">-- Pilih Kelas --</option>
                                    
                                    <!-- SD Options -->
                                    <option value="1" x-show="educationLevel === 'SD'">Kelas 1</option>
                                    <option value="2" x-show="educationLevel === 'SD'">Kelas 2</option>
                                    <option value="3" x-show="educationLevel === 'SD'">Kelas 3</option>
                                    <option value="4" x-show="educationLevel === 'SD'">Kelas 4</option>
                                    <option value="5" x-show="educationLevel === 'SD'">Kelas 5</option>
                                    <option value="6" x-show="educationLevel === 'SD'">Kelas 6</option>
                                    
                                    <!-- SMP Options -->
                                    <option value="7" x-show="educationLevel === 'SMP'">Kelas 7</option>
                                    <option value="8" x-show="educationLevel === 'SMP'">Kelas 8</option>
                                    <option value="9" x-show="educationLevel === 'SMP'">Kelas 9</option>
                                    
                                    <!-- SMA Options -->
                                    <option value="10" x-show="educationLevel === 'SMA'">Kelas 10</option>
                                    <option value="11" x-show="educationLevel === 'SMA'">Kelas 11</option>
                                    <option value="12" x-show="educationLevel === 'SMA'">Kelas 12</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Siswa</label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base">
                            <option value="aktif" {{ old('status', $student->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ old('status', $student->status) === 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="pindah" {{ old('status', $student->status) === 'pindah' ? 'selected' : '' }}>Pindah Sekolah</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            Catatan: Jika status diubah ke Lulus atau Pindah Sekolah, siswa tidak akan lagi menerima tagihan invoice baru, dan akun login siswa akan otomatis dihapus secara permanen dalam waktu 6 bulan. Data diri dan rapot siswa akan tetap aman di arsip sekolah.
                        </p>
                    </div>

                    <div class="mb-8">
                        <label for="alamat_tagihan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Tagihan</label>
                        <textarea id="alamat_tagihan" name="alamat_tagihan" rows="3" required
                                  placeholder="Masukkan alamat lengkap tagihan"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400">{{ old('alamat_tagihan', $student->alamat_tagihan) }}</textarea>
                    </div>

                    <div class="mb-8 border-t border-gray-100 dark:border-gray-700 pt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Akun Login Siswa</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $student->user->email ?? '') }}"
                                       placeholder="example@email.com"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400">
                                <p class="mt-1 text-xs text-gray-500">Isi email untuk membuat atau memperbarui akun login siswa.</p>
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                                <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400">
                                <p class="mt-1 text-xs text-gray-500">Minimal 6 karakter. Jika membuat akun baru, default password adalah 'password123'.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection