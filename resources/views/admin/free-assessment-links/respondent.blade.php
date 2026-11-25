@extends('mbi.layouts.app')

@section('title', 'Hasil Responden — ' . $respondent->nama)

@section('content')
<div class="py-10 bg-gradient-to-br from-gray-50 via-slate-50 to-purple-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-slate-900 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Top Nav Bar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.free-links.show', $link->id) }}" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Responden "{{ $link->title }}"
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('free-assessment.result-pdf', ['slug' => $link->slug, 'sessionToken' => $session->session_token, 'download' => 1]) }}"
                   class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-md shadow-purple-900/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> Unduh PDF Laporan
                </a>
            </div>
        </div>

        <!-- Embedded Public Result Component -->
        @php
            $personalityData = $config['personality'][$result->role_category] ?? reset($config['personality']);
            $vakData = $config['vak'][$result->learning_style] ?? reset($config['vak']);
            $miDoingArr = (array) ($result->mi_doing ?? []);
            $miRecArr = (array) ($result->mi_receiving ?? []);
            $llDoingArr = (array) ($result->love_language_doing ?? []);
            $llRecArr = (array) ($result->love_language_receiving ?? []);
        @endphp

        <!-- Header Card -->
        <div class="p-6 sm:p-8 rounded-3xl bg-slate-900 border border-slate-800 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-widest text-purple-400">Hasil Free Assessment</span>
                <h1 class="text-2xl font-black">{{ $respondent->nama }}</h1>
                <div class="text-xs text-slate-400 flex flex-wrap items-center gap-3 pt-1">
                    <span><i class="fa-solid fa-calendar mr-1"></i>{{ $result->computed_at ? $result->computed_at->format('d M Y H:i') : '-' }}</span>
                    @if($respondent->email) <span>&bull;</span> <span><i class="fa-solid fa-envelope mr-1"></i>{{ $respondent->email }}</span> @endif
                    @if($respondent->no_hp) <span>&bull;</span> <span><i class="fa-brands fa-whatsapp text-emerald-400 mr-1"></i>{{ $respondent->no_hp }}</span> @endif
                    @if($respondent->asal_sekolah) <span>&bull;</span> <span><i class="fa-solid fa-school text-blue-400 mr-1"></i>{{ $respondent->asal_sekolah }}</span> @endif
                </div>
            </div>

            @if($result->needs_followup_flag)
                <div class="px-4 py-2.5 rounded-2xl bg-rose-950/80 border border-rose-800 text-rose-200 text-xs font-bold flex items-center gap-2">
                    <i class="fa-solid fa-flag text-rose-500 text-base"></i>
                    <span>Perlu Pendampingan Lanjutan</span>
                </div>
            @endif
        </div>

        <!-- 2-Column: Personality & VAK -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personality -->
            <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase text-purple-400"><i class="fa-solid fa-masks-theater mr-1"></i> Karakter & Peran</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $personalityData['badge'] }}">{{ $personalityData['type'] }}</span>
                </div>
                <h3 class="text-lg font-black text-white">{{ $personalityData['title'] }}</h3>
                <p class="text-xs text-slate-300 leading-relaxed">{{ $personalityData['desc'] }}</p>
                <div class="pt-2 border-t border-slate-800 text-xs space-y-1">
                    <div><strong class="text-purple-300">Kekuatan:</strong> <span class="text-slate-400">{{ $personalityData['strengths'] }}</span></div>
                    <div><strong class="text-amber-300">Saran:</strong> <span class="text-slate-400">{{ $personalityData['advice'] }}</span></div>
                </div>
            </div>

            <!-- VAK -->
            <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase text-cyan-400"><i class="fa-solid fa-brain mr-1"></i> Gaya Belajar</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $vakData['badge'] }}">{{ strtoupper($result->learning_style) }}</span>
                </div>
                <h3 class="text-lg font-black text-white">{{ $vakData['title'] }}</h3>
                <p class="text-xs text-slate-300 leading-relaxed">{{ $vakData['desc'] }}</p>
                <div class="pt-2 border-t border-slate-800 text-xs">
                    <strong class="text-cyan-300">Tips Belajar:</strong> <span class="text-slate-400">{{ $vakData['tips'] }}</span>
                </div>
            </div>
        </div>

        <!-- DASS-21 Result Summary -->
        <div class="p-6 sm:p-8 rounded-3xl bg-slate-900/90 border border-slate-800 space-y-6">
            <div class="border-b border-slate-800 pb-3">
                <span class="text-xs font-extrabold uppercase text-amber-400">Skrining Emosional DASS-21</span>
                <h2 class="text-lg font-black text-white">Evaluasi Tingkat Depresi, Kecemasan, dan Stres</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-2">
                    <span class="text-xs font-bold text-slate-400 uppercase">Depresi</span>
                    <div class="text-3xl font-black text-white">{{ $result->dass_depression_raw }}</div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase bg-purple-950 text-purple-300 border border-purple-800">
                        {{ $result->dass_depression_level }}
                    </span>
                </div>

                <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-2">
                    <span class="text-xs font-bold text-slate-400 uppercase">Kecemasan</span>
                    <div class="text-3xl font-black text-white">{{ $result->dass_anxiety_raw }}</div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase bg-blue-950 text-blue-300 border border-blue-800">
                        {{ $result->dass_anxiety_level }}
                    </span>
                </div>

                <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-2">
                    <span class="text-xs font-bold text-slate-400 uppercase">Stres</span>
                    <div class="text-3xl font-black text-white">{{ $result->dass_stress_raw }}</div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-950 text-amber-300 border border-amber-800">
                        {{ $result->dass_stress_level }}
                    </span>
                </div>
            </div>
        </div>

        <!-- MI & Love Language Highlights -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- MI -->
            <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 space-y-3">
                <h3 class="text-sm font-black text-white uppercase text-purple-400">Aktivitas Kecerdasan Majemuk:</h3>
                <div class="space-y-2">
                    @foreach($config['multiple_intelligence'] as $miKey => $miItem)
                        @php
                            $isDoing = in_array($miKey, $miDoingArr);
                            $isRec = in_array($miKey, $miRecArr);
                        @endphp
                        @if($isDoing || $isRec)
                            <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-between text-xs">
                                <span class="font-bold text-white">{{ $miItem['title'] }}</span>
                                <div class="flex gap-1 text-[10px]">
                                    @if($isDoing) <span class="px-2 py-0.5 rounded bg-emerald-950 text-emerald-300">Melakukan</span> @endif
                                    @if($isRec) <span class="px-2 py-0.5 rounded bg-blue-950 text-blue-300">Menerima</span> @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Love Language -->
            <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 space-y-3">
                <h3 class="text-sm font-black text-white uppercase text-rose-400">Bahasa Kasih Utama:</h3>
                <div class="space-y-2">
                    <div class="text-xs font-bold text-slate-400">Diberikan:</div>
                    @foreach($llDoingArr as $k)
                        @if(isset($config['love_language'][$k]))
                            <div class="p-2 rounded-xl bg-slate-950 border border-rose-900/40 text-xs font-semibold text-rose-200">
                                &bull; {{ $config['love_language'][$k]['title'] }}
                            </div>
                        @endif
                    @endforeach

                    <div class="text-xs font-bold text-slate-400 pt-2">Diterima:</div>
                    @foreach($llRecArr as $k)
                        @if(isset($config['love_language'][$k]))
                            <div class="p-2 rounded-xl bg-slate-950 border border-pink-900/40 text-xs font-semibold text-pink-200">
                                &bull; {{ $config['love_language'][$k]['title'] }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
