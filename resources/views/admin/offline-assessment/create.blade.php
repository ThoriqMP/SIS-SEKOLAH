@extends('mbi.layouts.app')

@section('title', 'Input Asesmen Offline & Penautan Slot')

@section('content')
<div class="py-10 bg-gradient-to-br from-gray-50 via-slate-50 to-purple-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-slate-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-600 via-purple-700 to-indigo-700 text-white shadow-xl mb-8 p-6 sm:p-8">
            <div class="relative z-10 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-md mb-2">
                        Asesmen Konvensional / Anak < 7 Tahun
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Input Asesmen Offline & Penautan Slot</h1>
                    <p class="text-amber-100 text-sm mt-1">Masukkan hasil asesmen manual offline dan tautkan langsung ke slot grup Family Mapping.</p>
                </div>
                <a href="{{ route('assessment-groups.index') }}" 
                   class="inline-flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-xl text-sm font-medium transition backdrop-blur-md">
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-md rounded-2xl border border-gray-200/80 dark:border-gray-800 shadow-xl p-6 sm:p-8">
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl p-4 text-red-700 dark:text-red-300 text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.offline-assessment.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section 1: Biodata Anak / Subjek -->
                <div class="border-b border-gray-200 dark:border-gray-800 pb-6 space-y-4">
                    <h2 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300 flex items-center justify-center text-xs font-bold">1</span>
                        Biodata Anak / Subjek Tes Offline
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap Anak <span class="text-red-500">*</span></label>
                            <input type="text" name="new_subject_name" value="{{ old('new_subject_name') }}" required
                                   class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500"
                                   placeholder="Contoh: Ananda Kevin">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="new_subject_gender" required
                                    class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500">
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir</label>
                            <input type="date" name="new_subject_dob" value="{{ old('new_subject_dob') }}"
                                   class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Tanggal Tes <span class="text-red-500">*</span></label>
                            <input type="date" name="test_date" value="{{ old('test_date', date('Y-m-d')) }}" required
                                   class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Nama Psikolog / Asesor <span class="text-red-500">*</span></label>
                        <input type="text" name="psychologist_name" value="{{ old('psychologist_name', auth()->user()->name) }}" required
                               class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <!-- Section 2: Penautan Slot Family Mapping -->
                <div class="border-b border-gray-200 dark:border-gray-800 pb-6 space-y-4">
                    <h2 class="text-base font-bold text-purple-900 dark:text-purple-200 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xs font-bold">2</span>
                        Penautan ke Slot Family Mapping (Opsional)
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-purple-50/60 dark:bg-purple-950/30 p-4 rounded-xl border border-purple-200 dark:border-purple-800/60">
                        <div>
                            <label class="block text-xs font-bold text-purple-900 dark:text-purple-200 mb-1">Pilih Target Slot Offline Grup</label>
                            <select name="target_slot_id"
                                    class="w-full px-3.5 py-2 bg-white dark:bg-gray-800 border border-purple-300 dark:border-purple-700 rounded-xl text-xs text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500">
                                <option value="">-- Tidak Ditautkan (Asesmen Mandiri Standalone) --</option>
                                @foreach($availableGroups as $grp)
                                    @foreach($grp->assessmentLinks as $sl)
                                        <option value="{{ $sl->id }}">
                                            {{ $grp->group_name }} — Slot {{ $sl->slot_number }} [OFFLINE]
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-purple-900 dark:text-purple-200 mb-1">Peran dalam Keluarga</label>
                            <input type="text" name="member_role" value="Anak (Offline)"
                                   class="w-full px-3.5 py-2 bg-white dark:bg-gray-800 border border-purple-300 dark:border-purple-700 rounded-xl text-xs text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500"
                                   placeholder="Contoh: Anak 1 / Anak 2">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Ringkasan Skor Asesmen Offline -->
                <div class="space-y-4">
                    <h2 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-xs font-bold">3</span>
                        Input Skor & Aspek Dominan Asesmen Offline
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Dominan Personality (DISC)</label>
                            <select name="personality[dominance]" class="w-full px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 rounded-lg text-xs">
                                <option value="D">Dominance (D)</option>
                                <option value="I">Influence (I)</option>
                                <option value="S">Steadiness (S)</option>
                                <option value="C">Compliance (C)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Dominan Gaya Belajar (VAK)</label>
                            <select name="personality[learning_style]" class="w-full px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 rounded-lg text-xs">
                                <option value="Visual">Visual</option>
                                <option value="Auditori">Auditori</option>
                                <option value="Kinestetik">Kinestetik</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Dominan Bahasa Kasih</label>
                            <select name="love_language[primary]" class="w-full px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 rounded-lg text-xs">
                                <option value="Words of Affirmation">Kata-kata Pujian</option>
                                <option value="Quality Time">Waktu Berkualitas</option>
                                <option value="Receiving Gifts">Hadiah Fisik</option>
                                <option value="Acts of Service">Pelayanan Nyata</option>
                                <option value="Physical Touch">Sentuhan Fisik</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('assessment-groups.index') }}" class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-xl text-sm font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-bold transition shadow-lg">
                        Simpan & Tautkan Asesmen Offline
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
