@extends('layouts.app')

@section('title', __('messages.online_class_list'))

@section('content')
<div class="py-6 sm:py-12 bg-gradient-to-br from-slate-50 via-slate-50 to-sky-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-sky-950/20 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white/40 dark:bg-gray-900/40 backdrop-blur-md p-6 rounded-3xl border border-white/20 dark:border-gray-800/40 shadow-sm">
            <div>
                <h1 class="text-2xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ __('messages.online_class_list') }}</h1>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('messages.video_conf_intro') }}
                </p>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-lg font-black text-gray-905 dark:text-white tracking-tight">{{ __('messages.active_sessions') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($conferences as $conf)
                    @php
                        // Check if it's currently live (within 15 minutes before or active)
                        $isLive = $conf->is_active && ($conf->scheduled_at->isPast() || $conf->scheduled_at->diffInMinutes(now()) <= 30);
                    @endphp
                    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 border border-gray-150/40 dark:border-gray-800/40 shadow-md flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <div>
                             <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-sky-150/20 max-w-[200px] truncate">
                                    Siswa: {{ is_array($conf->class) ? (empty($conf->target_students) ? implode(', ', $conf->class) : implode(', ', $conf->target_students)) : $conf->class }}
                                </span>
                                @if($isLive)
                                    <span class="px-2.5 py-1 bg-red-500/10 border border-red-500/20 text-red-500 text-[9px] font-black uppercase tracking-widest rounded-md animate-pulse">
                                        {{ __('messages.live_now') }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-slate-200/20">
                                        {{ __('messages.upcoming') }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight line-clamp-1 mb-2 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
                                {{ $conf->title }}
                            </h3>
                            
                            <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-4">
                                {{ __('messages.teacher') }}: <span class="text-slate-600 dark:text-slate-300 font-extrabold">{{ $conf->teacher->name ?? 'Guru' }}</span>
                            </div>

                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6 font-semibold">
                                📅 {{ __('messages.schedule') }}: {{ $conf->scheduled_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-gray-800/60">
                            @if($isLive)
                                <a href="{{ route('student.video-conference.join', $conf->room_name) }}" class="block text-center py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-extrabold text-xs shadow-md shadow-red-500/25 transition duration-200 animate-bounce">
                                    {{ __('messages.join_class_live') }}
                                </a>
                            @else
                                <a href="{{ route('student.video-conference.join', $conf->room_name) }}" class="block text-center py-3 bg-sky-600 hover:bg-sky-700 text-white rounded-xl font-extrabold text-xs shadow-md transition duration-200">
                                    {{ __('messages.join_class') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white/40 dark:bg-gray-900/40 rounded-3xl border-2 border-dashed border-sky-200/50 dark:border-sky-800/40 text-gray-400 dark:text-gray-500 p-8 shadow-sm">
                        <p class="text-sm font-bold">{{ __('messages.no_online_class_scheduled') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
