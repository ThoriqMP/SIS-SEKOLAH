@extends('layouts.app')

@section('title', 'Manajemen Ruang Belajar')

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen" x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') === 'diskusi' ? 'DISKUSI' : 'SD' }">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-purple-700 via-purple-800 to-indigo-800 rounded-[32px] p-6 sm:p-10 text-white shadow-xl shadow-purple-950/25 relative overflow-hidden group">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <span class="px-3.5 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[10px] sm:text-xs font-black uppercase tracking-widest text-purple-200">
                        Staff Portal
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-black mt-4 mb-2 tracking-tight">
                        Manajemen Ruang Belajar
                    </h1>
                    <p class="text-purple-100 text-xs sm:text-sm opacity-90 font-medium leading-relaxed">
                        Kelola kursus, unggah materi Google Drive, dan konfigurasikan lencana kelulusan otomatis untuk setiap jenjang.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('teacher.courses.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white text-purple-900 hover:bg-purple-50 rounded-2xl font-black text-sm shadow-lg shadow-purple-900/10 active:scale-95 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Kursus Baru
                    </a>
                </div>
            </div>
            <div class="absolute -top-32 -right-32 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl group-hover:scale-110 duration-1000"></div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/25 rounded-full blur-3xl group-hover:scale-110 duration-1000"></div>
        </div>

        <!-- Alert Success / Error -->
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tab Controls -->
        <div class="flex border-b border-gray-200 dark:border-gray-800 overflow-x-auto gap-2">
            @foreach(['SD' => 'Sekolah Dasar (SD)', 'SMP' => 'Sekolah Menengah Pertama (SMP)', 'SMA' => 'Sekolah Menengah Atas (SMA)'] as $key => $label)
                <button @click="activeTab = '{{ $key }}'" 
                        :class="activeTab === '{{ $key }}' ? 'border-purple-650 text-purple-650 dark:border-purple-400 dark:text-purple-400 font-black' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-bold'" 
                        class="py-4 px-6 border-b-2 text-sm uppercase tracking-wider transition-colors duration-200 whitespace-nowrap">
                    {{ $label }}
                </button>
            @endforeach
            @php
                $unreadCommentsCount = \App\Models\LessonComment::hasUnreadForUser(Auth::user());
            @endphp
            <button @click="activeTab = 'DISKUSI'" 
                    :class="activeTab === 'DISKUSI' ? 'border-purple-650 text-purple-650 dark:border-purple-400 dark:text-purple-400 font-black' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-bold'" 
                    class="py-4 px-6 border-b-2 text-sm uppercase tracking-wider transition-colors duration-200 whitespace-nowrap flex items-center gap-1.5 relative">
                💬 Forum Diskusi
                @if($unreadCommentsCount)
                    <span class="w-2 h-2 bg-rose-500 rounded-full animate-pulse shadow-md shadow-rose-500/25"></span>
                @endif
            </button>
        </div>

        <!-- Tab Content -->
        @foreach(['SD', 'SMP', 'SMA'] as $level)
            <div x-show="activeTab === '{{ $level }}'" x-transition:enter="transition ease-out duration-200" class="space-y-8">
                @php
                    $courses = $coursesGrouped->get($level, collect());
                @endphp

                @if($courses->isEmpty())
                    <div class="py-16 text-center bg-white dark:bg-gray-900 border border-dashed border-gray-300 dark:border-gray-800 rounded-[32px] p-8 shadow-sm">
                        <span class="text-5xl block mb-4">📚</span>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Belum ada kursus untuk jenjang {{ $level }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                            Buat kursus pertama untuk mengelompokkan materi pembelajaran siswa.
                        </p>
                        <a href="{{ route('teacher.courses.create', ['education_level' => $level]) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-purple-600 hover:bg-purple-755 text-white rounded-2xl font-bold text-sm transition-all shadow-md shadow-purple-600/10">
                            Tambah Kursus Baru
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach($courses as $course)
                            @php
                                $badge = $course->badges->first();
                            @endphp
                            <div class="bg-white dark:bg-gray-900 rounded-[32px] border border-gray-200 dark:border-gray-800 shadow-md p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                                <div>
                                    <!-- Course Info Header -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <span class="px-3 py-1 bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-purple-100/10">
                                                Kelas {{ $course->grade }} ({{ $course->education_level }})
                                            </span>
                                            <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight mt-2.5">
                                                {{ $course->title }}
                                            </h3>
                                        </div>
                                        @if($badge)
                                            <div class="flex items-center gap-1.5 bg-amber-50 dark:bg-amber-955/20 border border-amber-200/50 dark:border-amber-900/30 px-3 py-1.5 rounded-2xl">
                                                <span class="text-xl">{{ $badge->badge_image_url ?: '🏆' }}</span>
                                                <span class="text-[9px] font-black text-amber-800 dark:text-amber-400 uppercase tracking-wider max-w-[80px] truncate" title="{{ $badge->badge_name }}">
                                                    {{ $badge->badge_name }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <hr class="border-gray-100 dark:border-gray-800 my-4" />

                                    <!-- Lessons List -->
                                    <div class="space-y-3 mb-6">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                                Daftar Materi ({{ $course->lessons->count() }})
                                            </h4>
                                            <a href="{{ route('teacher.lessons.create', $course) }}" class="text-xs font-black text-purple-600 dark:text-purple-450 hover:underline">
                                                + Tambah Materi
                                            </a>
                                        </div>

                                        @if($course->lessons->isEmpty())
                                            <p class="text-xs text-gray-400 dark:text-gray-500 italic py-2">Belum ada materi pembelajaran.</p>
                                        @else
                                            <div class="max-h-[220px] overflow-y-auto pr-1 space-y-2">
                                                @foreach($course->lessons as $lesson)
                                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-950/50 border border-gray-150 dark:border-gray-850 rounded-2xl text-xs hover:bg-gray-100 dark:hover:bg-gray-850 transition">
                                                        <div class="flex items-center gap-2.5 min-w-0">
                                                            <span class="flex-shrink-0 text-sm">
                                                                @if($lesson->type === 'video')
                                                                    🎥
                                                                @elseif($lesson->type === 'pdf')
                                                                    📄
                                                                @else
                                                                    📝
                                                                @endif
                                                            </span>
                                                            <span class="font-bold text-gray-400 dark:text-gray-500">{{ $lesson->order_position }}.</span>
                                                            <span class="font-semibold text-gray-800 dark:text-gray-200 truncate" title="{{ $lesson->title }}">
                                                                {{ $lesson->title }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-1.5 flex-shrink-0 ml-3">
                                                            @if($lesson->id)
                                                                <a href="{{ route('teacher.lessons.show', $lesson->id) }}" class="p-1.5 text-gray-500 hover:text-purple-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-lg transition" title="Preview">
                                                                    👁
                                                                </a>
                                                                <a href="{{ route('teacher.lessons.edit', [$course->id, $lesson->id]) }}" class="p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-lg transition" title="Edit">
                                                                    ✏️
                                                                </a>
                                                                <form action="{{ route('teacher.lessons.destroy', [$course->id, $lesson->id]) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-lg transition" title="Hapus">
                                                                        🗑
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Course Actions -->
                                <div class="flex gap-2 pt-4 border-t border-gray-100 dark:border-gray-800">
                                    @if(!$course->lessons->isEmpty())
                                        <a href="{{ route('teacher.lessons.show', $course->lessons->first()->id) }}" class="flex-1 text-center py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-2xl font-black text-xs transition">
                                            Preview Imersif
                                        </a>
                                    @else
                                        <button disabled class="flex-1 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-2xl font-black text-xs cursor-not-allowed">
                                            Preview (No Lessons)
                                        </button>
                                    @endif
                                    <a href="{{ route('teacher.courses.edit', $course->id) }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-250 rounded-2xl font-black text-xs transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('teacher.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Hapus kursus ini beserta seluruh materi dan lencananya? Tindakan ini tidak bisa dibatalkan.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2.5 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl font-black text-xs transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Tab Content Forum Diskusi -->
        <div x-show="activeTab === 'DISKUSI'" x-transition:enter="transition ease-out duration-200" class="space-y-6">
            @if($lessonsWithDiscussions->isEmpty())
                <div class="py-16 text-center bg-white dark:bg-gray-900 border border-dashed border-gray-300 dark:border-gray-800 rounded-[32px] p-8 shadow-sm">
                    <span class="text-5xl block mb-4">💬</span>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Belum ada diskusi aktif</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        Forum diskusi akan terisi secara otomatis ketika ada siswa atau guru yang mengirim pesan/komentar pada materi pelajaran.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($lessonsWithDiscussions as $item)
                        @php
                            $latestComment = $item->comments->first();
                            $hasUnread = $item->hasUnreadCommentsForUser(Auth::user());
                        @endphp
                        <div class="bg-white dark:bg-gray-900 rounded-[32px] border border-gray-200 dark:border-gray-800 shadow-md p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                            <div>
                                <!-- Header Info -->
                                <div class="flex justify-between items-start gap-4 mb-4">
                                    <div>
                                        <span class="px-3 py-1 bg-purple-50 dark:bg-purple-950/40 text-purple-750 dark:text-purple-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-purple-100/10">
                                            {{ $item->course->title }} &bull; Kelas {{ $item->course->grade }} ({{ $item->course->education_level }})
                                        </span>
                                        <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight mt-2.5 flex items-center gap-1.5">
                                            📚 {{ $item->title }}
                                            @if($hasUnread)
                                                <span class="w-2 h-2 bg-rose-500 rounded-full animate-ping" title="Ada Diskusi Baru"></span>
                                            @endif
                                        </h3>
                                    </div>
                                    <span class="text-[10px] font-black px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-650 dark:text-slate-350 rounded-full shrink-0">
                                        {{ $item->comments_count }} Pesan
                                    </span>
                                </div>

                                <hr class="border-gray-150 dark:border-gray-850 my-4" />

                                <!-- Latest Comment Snippet -->
                                @if($latestComment)
                                    <div class="p-4 bg-slate-50 dark:bg-gray-950/40 rounded-2xl border border-slate-100 dark:border-gray-850">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-black text-white shrink-0 bg-gradient-to-br {{ $latestComment->user->role === 'student' ? 'from-amber-400 to-orange-500' : 'from-indigo-500 to-purple-650' }}">
                                                {{ strtoupper(substr($latestComment->user->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1 flex items-center justify-between">
                                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">{{ $latestComment->user->name }}</span>
                                                <span class="text-[9px] text-gray-400 dark:text-gray-500 font-semibold">{{ $latestComment->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium line-clamp-2">
                                            {{ $latestComment->content }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 font-display">
                                <a href="{{ route('teacher.lessons.show', $item->id) }}?tab=diskusi" class="flex-1 text-center py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl font-black text-xs transition active:scale-95 duration-150 shadow-md shadow-purple-600/10">
                                    Balas & Lihat Diskusi
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
