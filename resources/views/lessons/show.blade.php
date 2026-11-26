@php
    // Adaptive Theme Engine mapping
    $isSD = $educationLevel === 'SD';
    $isSMP = $educationLevel === 'SMP';
    $isSMA = $educationLevel === 'SMA';

    $theme = [
        'bg' => $isSD ? 'bg-amber-50/50' : ($isSMP ? 'bg-indigo-50/30' : 'bg-purple-50/30'),
        'sidebar_bg' => $isSD 
            ? 'bg-white border-amber-200 shadow-xl' 
            : ($isSMP 
                ? 'bg-white text-slate-800 border-indigo-100 shadow-xl' 
                : 'bg-white text-slate-800 border-purple-100 shadow-xl'),
        'sidebar_header_border' => $isSD ? 'border-amber-100' : ($isSMP ? 'border-indigo-100' : 'border-purple-100'),
        'sidebar_text_header' => $isSD ? 'text-amber-600 font-extrabold' : ($isSMP ? 'text-indigo-600 font-black' : 'text-purple-600 font-black'),
        'sidebar_item_hover' => $isSD 
            ? 'hover:bg-amber-100 text-amber-900 border-amber-300' 
            : ($isSMP 
                ? 'hover:bg-indigo-50 text-indigo-900 border-indigo-200' 
                : 'hover:bg-purple-50 text-purple-900 border-purple-200'),
        'sidebar_item_active' => $isSD 
            ? 'bg-amber-200 text-amber-950 border-amber-500 font-bold' 
            : ($isSMP 
                ? 'bg-indigo-600 text-white border-indigo-600 font-bold' 
                : 'bg-purple-600 text-white border-purple-600 font-bold'),
        'sidebar_item_bg_inactive' => $isSD 
            ? 'bg-amber-50 border-amber-100' 
            : ($isSMP 
                ? 'bg-indigo-50/30 border-indigo-100' 
                : 'bg-purple-50/30 border-purple-100'),
        'rounded_lg' => $isSD ? 'rounded-3xl' : ($isSMP ? 'rounded-md' : 'rounded-none'),
        'rounded_sm' => $isSD ? 'rounded-2xl' : ($isSMP ? 'rounded' : 'rounded-none'),
        'font' => $isSD ? 'font-sans font-medium text-lg' : 'font-sans tracking-tight text-sm',
        'heading_font' => $isSD 
            ? 'font-black text-2xl text-amber-900' 
            : ($isSMP 
                ? 'font-black text-lg text-indigo-950' 
                : 'font-black text-lg text-purple-950'),
        'primary_btn' => $isSD 
            ? 'bg-teal-500 hover:bg-teal-600 text-white font-black shadow-lg shadow-teal-500/30' 
            : ($isSMP 
                ? 'bg-indigo-600 hover:bg-indigo-700 text-white font-black shadow-lg shadow-indigo-600/30' 
                : 'bg-purple-600 hover:bg-purple-700 text-white font-black shadow-lg shadow-purple-600/30'),
        'accent_color' => $isSD ? 'text-amber-500' : ($isSMP ? 'text-indigo-600' : 'text-purple-600'),
        'dock_bg' => $isSD 
            ? 'bg-white/95 border-amber-200 shadow-2xl' 
            : ($isSMP 
                ? 'bg-white/95 border-indigo-150 shadow-2xl' 
                : 'bg-white/95 border-purple-150 shadow-2xl'),
        'badge_bg' => $isSD 
            ? 'bg-teal-100 text-teal-800 border-teal-200' 
            : ($isSMP 
                ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' 
                : 'bg-purple-100 text-purple-800 border border-purple-200'),
        'skeleton_bg' => $isSD ? 'bg-amber-200/50' : ($isSMP ? 'bg-indigo-150' : 'bg-purple-150'),
        'body_text' => $isSD ? 'text-amber-950' : ($isSMP ? 'text-slate-800' : 'text-slate-800'),
        'nav_btn' => $isSD 
            ? 'bg-amber-100 hover:bg-amber-200 text-amber-900 border-amber-200 font-bold' 
            : ($isSMP 
                ? 'bg-indigo-50 hover:bg-indigo-100 text-indigo-900 border-indigo-150 font-bold' 
                : 'bg-purple-50 hover:bg-purple-100 text-purple-900 border-purple-150 font-bold'),
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ruang Belajar - {{ $course->title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900&display=swap" rel="stylesheet" />

    <!-- Tailwind & Assets (Vite if built, CDN fallback) -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom Confetti Animation Styles */
        .confetti-particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f00;
            opacity: 0.8;
            top: -10px;
            z-index: 999;
            animation: fall linear forwards;
        }

        @keyframes fall {
            to {
                transform: translateY(105vh) rotate(360deg);
            }
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-indigo-500 selection:text-white overflow-hidden">

    <!-- Confetti Container -->
    <div id="confetti-container" class="fixed inset-0 pointer-events-none z-50 overflow-hidden"></div>

    <!-- Main Wrapper -->
    <div class="h-screen h-[100dvh] w-screen flex flex-col relative overflow-hidden {{ $theme['bg'] }}" style="height: 100vh; height: 100dvh;">
        
        <!-- Header Minimalist -->
        <header class="h-16 flex items-center justify-between px-6 border-b z-20 {{ $isSD ? 'bg-amber-100/90 border-amber-200' : ($isSMP ? 'bg-white/90 border-indigo-100 backdrop-blur-md' : 'bg-white/90 border-purple-100 backdrop-blur-md') }}">
            <div class="flex items-center gap-3">
                <!-- Hamburger Collapsible Menu Toggle -->
                <button id="sidebar-toggle" class="p-2.5 transition-all duration-200 focus:outline-none flex items-center justify-center {{ $theme['rounded_sm'] }} {{ $isSD ? 'hover:bg-amber-200 text-amber-900 bg-amber-100' : ($isSMP ? 'hover:bg-indigo-100 text-indigo-700 bg-indigo-50' : 'hover:bg-purple-100 text-purple-700 bg-purple-50') }}" title="Toggle Sidebar [=]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex flex-col min-w-0">
                    <span class="text-[10px] font-black uppercase tracking-wider {{ $isSD ? 'text-amber-700/80' : ($isSMP ? 'text-indigo-500/80' : 'text-purple-500/80') }}">{{ __('messages.study_room') }} {{ $educationLevel }}</span>
                    <h1 class="{{ $theme['heading_font'] }} truncate max-w-[200px] sm:max-w-md">{{ $course->title }}</h1>
                </div>
            </div>

            <!-- Right Controls: Progress & Exit -->
            <div class="flex items-center gap-4 sm:gap-6">
                <!-- Progress Indicator -->
                <div class="flex items-center gap-3">
                    <span class="text-xs font-black uppercase tracking-wider {{ $isSD ? 'text-amber-800' : ($isSMP ? 'text-indigo-700/80' : 'text-purple-700/80') }} hidden sm:inline">{{ __('messages.course_progress') }}:</span>
                    <div class="w-24 sm:w-36 rounded-full h-3 overflow-hidden p-0.5 {{ $isSD ? 'bg-amber-200 border border-amber-300' : ($isSMP ? 'bg-indigo-100 border border-indigo-200' : 'bg-purple-100 border border-purple-200') }}">
                        <div id="progress-bar-fill" class="h-full transition-all duration-700 ease-out {{ $isSD ? 'bg-teal-500 rounded-full' : ($isSMP ? 'bg-indigo-600 rounded-full' : 'bg-purple-600 rounded-full') }}" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <span id="progress-text" class="text-xs sm:text-sm font-black {{ $isSD ? 'text-teal-600' : ($isSMP ? 'text-indigo-600' : 'text-purple-600') }}">{{ round($progressPercentage) }}%</span>
                </div>

                <!-- Back to Portal -->
                <a href="{{ Auth::user()->role === 'student' ? route('student.lessons.index') : route('teacher.lessons.index') }}" class="py-2 px-3 text-xs flex items-center gap-1.5 transition-all duration-200 hover:-translate-x-0.5 {{ $theme['rounded_sm'] }} {{ $theme['nav_btn'] }} border">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l-7-7m-7 7h18"></path>
                    </svg>
                    <span class="hidden sm:inline">{{ __('messages.back') }}</span>
                </a>
            </div>
        </header>

        <!-- Sidebar + Canvas Content -->
        <div class="flex-1 flex relative overflow-hidden" style="flex: 1 1 0%; display: flex; position: relative; overflow: hidden; min-height: 0;">
            
            <!-- Floating Collapsible Navigator -->
            <aside id="sidebar-nav" class="absolute lg:relative left-0 top-0 bottom-0 lg:bottom-auto h-full w-80 z-30 transition-all duration-300 ease-in-out border-r flex flex-col overflow-hidden transform {{ $theme['sidebar_bg'] }}" style="bottom: 0px; height: 100%; display: flex; flex-direction: column; overflow: hidden;">
                <!-- Tabs Selector -->
                <div class="flex border-b {{ $theme['sidebar_header_border'] }} shrink-0">
                    <button id="tab-modules" class="flex-1 py-3 text-xs font-black uppercase tracking-wider border-b-2 text-center focus:outline-none transition-all duration-200 {{ $isSD ? 'border-teal-500 text-teal-600' : ($isSMP ? 'border-indigo-650 text-indigo-600' : 'border-purple-600 text-purple-600') }}">
                        📚 {{ __('messages.modules') }}
                    </button>
                    <button id="tab-forum" class="flex-1 py-3 text-xs font-black uppercase tracking-wider border-b-2 text-center focus:outline-none transition-all duration-200 border-transparent text-slate-400 hover:text-slate-650 hover:border-slate-200 relative flex items-center justify-center gap-1">
                        💬 {{ __('messages.discussion') }}
                        <span id="tab-forum-red-dot" class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse shadow-sm {{ $hasUnreadCommentsForThisLesson ?? false ? '' : 'hidden' }}"></span>
                    </button>
                </div>

                <!-- Tab 1: Modul / Materi List -->
                <div class="flex-1 flex flex-col min-h-0" id="lessons-list-container">
                    <div class="p-4 sm:p-5 border-b flex items-center justify-between {{ $theme['sidebar_header_border'] }} shrink-0">
                        <h2 class="text-xs uppercase tracking-widest {{ $theme['sidebar_text_header'] }}">{{ __('messages.lessons_list') }}</h2>
                        <span class="text-[10px] font-black px-2.5 py-1 rounded-full {{ $theme['badge_bg'] }}">
                            {{ $lessons->count() }} {{ __('messages.modules') }}
                        </span>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-3" id="lessons-list">
                        @foreach($lessons as $item)
                            @php
                                $isCurrent = $item->id === $lesson->id;
                                $isCompleted = in_array($item->id, $progressList);
                                
                                // Type details
                                $icon = '📄';
                                $typeLabel = __('messages.document');
                                if ($item->type === 'video') {
                                    $icon = '🎥';
                                    $typeLabel = __('messages.video');
                                } elseif ($item->type === 'pdf') {
                                    $icon = '📕';
                                    $typeLabel = __('messages.pdf_label') ?? 'PDF';
                                } elseif ($item->type === 'docx') {
                                    $icon = '📝';
                                    $typeLabel = __('messages.word_document') ?? 'Word';
                                }
                            @endphp
                            <a href="{{ route('student.lessons.show', $item->id) }}" 
                               class="flex items-center justify-between p-3.5 border transition-all duration-200 group lesson-item {{ $theme['rounded_sm'] }} {{ $isCurrent ? $theme['sidebar_item_active'] : $theme['sidebar_item_hover'] . ' ' . $theme['sidebar_item_bg_inactive'] }}"
                               data-lesson-id="{{ $item->id }}">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <span class="text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">{{ $icon }}</span>
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-xs font-semibold uppercase tracking-wider {{ $isCurrent ? ($isSD ? 'text-amber-800' : 'text-white/80') : ($isSD ? 'text-amber-700/80 group-hover:text-amber-900' : ($isSMP ? 'text-indigo-500/70 group-hover:text-indigo-600' : 'text-purple-500/70 group-hover:text-purple-600')) }}">{{ $typeLabel }}</span>
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <span class="text-sm font-bold truncate pr-2 {{ $isCurrent ? ($isSD ? 'text-amber-950' : 'text-white') : ($isSD ? 'text-amber-950' : ($isSMP ? 'text-slate-800 group-hover:text-indigo-950' : 'text-slate-800 group-hover:text-purple-950')) }}">{{ $item->title }}</span>
                                            @if($item->hasUnreadCommentsForUser(Auth::user()))
                                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse shadow-sm shrink-0"></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- Status Progress Checkbox -->
                                <div class="flex-shrink-0">
                                    <span class="progress-checkbox w-6 h-6 rounded-full flex items-center justify-center border transition-all duration-300 {{ $isCompleted ? 'bg-emerald-500 border-emerald-400 text-white' : ($isSD ? 'border-amber-300 bg-amber-50/50' : ($isSMP ? 'border-indigo-200 bg-indigo-50/30' : 'border-purple-200 bg-purple-50/30')) }}" data-completed="{{ $isCompleted ? 'true' : 'false' }}">
                                        <svg class="w-4 h-4 check-icon {{ $isCompleted ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Tab 2: Forum Diskusi -->
                <div class="flex-1 flex flex-col min-h-0 hidden" id="forum-container">
                    <div class="p-4 sm:p-5 border-b flex items-center justify-between {{ $theme['sidebar_header_border'] }} shrink-0">
                        <h2 class="text-xs uppercase tracking-widest {{ $theme['sidebar_text_header'] }}">{{ __('messages.discussion_forum') }}</h2>
                        <span id="comments-count-badge" class="text-[10px] font-black px-2.5 py-1 rounded-full {{ $theme['badge_bg'] }}">
                            {{ __('messages.discussions_count', ['count' => $comments->count()]) }}
                        </span>
                    </div>

                    <!-- Comments List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4 font-sans text-xs" id="comments-list" style="overflow-y: auto; flex: 1 1 0%; min-height: 0;">
                        @forelse($comments as $comment)
                            @php
                                $roleLabel = __('messages.student');
                                $badgeClass = 'bg-slate-100 text-slate-700';
                                if ($comment->user->role === 'guru') {
                                    $roleLabel = __('messages.teacher');
                                    $badgeClass = 'bg-indigo-100 text-indigo-800 border border-indigo-200';
                                } elseif ($comment->user->role === 'admin') {
                                    $roleLabel = __('messages.admin');
                                    $badgeClass = 'bg-rose-100 text-rose-800 border border-rose-200';
                                } elseif ($comment->user->role === 'pimpinan') {
                                    $roleLabel = __('messages.principal');
                                    $badgeClass = 'bg-purple-100 text-purple-800 border border-purple-200';
                                }

                                $isOwnerOrStaff = $comment->user_id === Auth::id() || in_array(Auth::user()->role, ['guru', 'admin', 'pimpinan']);
                            @endphp
                            <div class="space-y-2 root-comment-container" data-comment-id="{{ $comment->id }}">
                                <!-- Parent/Root Comment -->
                                <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-150 relative group comment-item" data-comment-id="{{ $comment->id }}">
                                    <!-- Initial Avatar -->
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black text-white shrink-0 bg-gradient-to-br {{ $comment->user->role === 'student' ? 'from-amber-400 to-orange-500' : 'from-indigo-500 to-purple-650' }}">
                                        {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <span class="text-xs font-bold text-slate-800 truncate max-w-[120px]">{{ $comment->user->name }}</span>
                                            <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded scale-90 {{ $badgeClass }}">
                                                {{ $roleLabel }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-700 font-medium leading-relaxed mt-1 break-words">
                                            {!! nl2br(e($comment->content)) !!}
                                        </p>
                                        <div class="flex items-center gap-3 mt-1.5">
                                            <span class="text-[9px] text-slate-400 font-semibold">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </span>
                                            <button type="button" class="reply-comment-btn text-[9px] text-purple-650 dark:text-purple-400 font-black hover:underline focus:outline-none" data-comment-id="{{ $comment->id }}" data-username="{{ $comment->user->name }}">
                                                {{ __('messages.reply') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if($isOwnerOrStaff)
                                        <button class="delete-comment-btn absolute top-2 right-2 text-slate-300 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition-all focus:opacity-100" data-comment-id="{{ $comment->id }}" title="{{ __('messages.delete_discussion') }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <!-- Replies List (Indented) -->
                                <div class="replies-list pl-8 space-y-2">
                                    @foreach($comment->replies as $reply)
                                        @php
                                            $replyRoleLabel = __('messages.student');
                                            $replyBadgeClass = 'bg-slate-100 text-slate-700';
                                            if ($reply->user->role === 'guru') {
                                                $replyRoleLabel = __('messages.teacher');
                                                $replyBadgeClass = 'bg-indigo-100 text-indigo-800 border border-indigo-200';
                                            } elseif ($reply->user->role === 'admin') {
                                                $replyRoleLabel = __('messages.admin');
                                                $replyBadgeClass = 'bg-rose-100 text-rose-800 border border-rose-200';
                                            } elseif ($reply->user->role === 'pimpinan') {
                                                $replyRoleLabel = __('messages.principal');
                                                $replyBadgeClass = 'bg-purple-100 text-purple-800 border border-purple-200';
                                            }

                                            $replyIsOwnerOrStaff = $reply->user_id === Auth::id() || in_array(Auth::user()->role, ['guru', 'admin', 'pimpinan']);
                                        @endphp
                                        <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-100/50 border border-slate-150/40 relative group comment-item reply-item" data-comment-id="{{ $reply->id }}">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black text-white shrink-0 bg-gradient-to-br {{ $reply->user->role === 'student' ? 'from-amber-400 to-orange-500' : 'from-indigo-500 to-purple-650' }}">
                                                {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-1.5">
                                                    <span class="text-[11px] font-bold text-slate-800 truncate max-w-[100px]">{{ $reply->user->name }}</span>
                                                    <span class="text-[8px] font-black uppercase tracking-wider px-1 py-0.5 rounded scale-90 {{ $replyBadgeClass }}">
                                                        {{ $replyRoleLabel }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-700 font-medium leading-relaxed mt-0.5 break-words">
                                                    {!! nl2br(e($reply->content)) !!}
                                                </p>
                                                <span class="text-[8px] text-slate-400 font-semibold mt-0.5 block">
                                                    {{ $reply->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            @if($replyIsOwnerOrStaff)
                                                <button class="delete-comment-btn absolute top-2 right-2 text-slate-300 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition-all focus:opacity-100" data-comment-id="{{ $reply->id }}" title="{{ __('messages.delete_discussion') }}">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 px-4" id="comments-empty-state">
                                <span class="text-4xl">💬</span>
                                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mt-3">{{ __('messages.no_discussions') }}</h3>
                                <p class="text-[11px] text-slate-400 mt-1 font-medium">{{ __('messages.comment_box_guideline') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Comment Post Form -->
                    <form id="comment-post-form" class="p-4 border-t {{ $theme['sidebar_header_border'] }} bg-slate-50/50 shrink-0">
                        @csrf
                        <input type="hidden" id="parent-comment-id" value="">
                        
                        <!-- Reply Info Bar -->
                        <div id="reply-info-bar" class="hidden mb-2 px-3 py-1.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 rounded-xl flex items-center justify-between gap-2 text-[10px] font-bold text-slate-650 dark:text-slate-350">
                            <span class="truncate">{{ __('messages.replying_to') }} <span id="reply-target-username" class="text-purple-600">@User</span></span>
                            <button type="button" id="btn-cancel-reply" class="text-rose-500 hover:text-rose-600 text-xs font-black focus:outline-none">&times;</button>
                        </div>

                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <textarea id="comment-input" rows="1" required 
                                          class="w-full text-xs font-medium rounded-xl border-slate-200 bg-white placeholder-slate-400 text-slate-800 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 transition-all resize-none py-2 px-3" 
                                          placeholder="{{ __('messages.comment_box_placeholder') }}"
                                          style="min-height: 36px; max-height: 120px; box-sizing: border-box;"></textarea>
                            </div>
                            <button type="submit" id="btn-submit-comment"
                                    class="h-[36px] w-[36px] flex items-center justify-center shrink-0 rounded-xl text-white transition-all {{ $isSD ? 'bg-teal-500 hover:bg-teal-600' : ($isSMP ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-purple-650 hover:bg-purple-700') }} active:scale-95 disabled:opacity-50"
                                    title="{{ __('messages.send_discussion') }}">
                                <svg class="w-4 h-4 rotate-90" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Theater Canvas (Main Viewport) -->
            <main id="theater-canvas" class="flex-1 flex flex-col relative overflow-y-auto {{ $theme['bg'] }} transition-all duration-300 z-10">
                @php
                    $moduleData = is_array($lesson->module_data) ? $lesson->module_data : null;
                    $useInteractive = $lesson->type === 'interactive' || !empty($moduleData) || empty($lesson->gdrive_url);
                    if ($useInteractive && empty($moduleData)) {
                        $moduleData = [
                            'mindmap' => [
                                'center' => $lesson->title,
                                'branches' => [
                                    ['title' => 'Konsep Dasar', 'color' => 'purple', 'items' => ['Definisi & ruang lingkup ' . $lesson->title, 'Pemahaman awal konsep']],
                                    ['title' => 'Poin & Kaidah Inti', 'color' => 'indigo', 'items' => ['Aturan utama materi', 'Langkah penyelesaian runtut']],
                                    ['title' => 'Penerapan Nyata', 'color' => 'emerald', 'items' => ['Studi kasus sehari-hari', 'Latihan kontekstual']],
                                    ['title' => 'Ringkasan', 'color' => 'amber', 'items' => ['Poin kunci yang wajib diingat', 'Persiapan evaluasi']],
                                ],
                            ],
                            'explanation' => [
                                'summary' => $lesson->content ?: ($lesson->title . ' adalah materi pembelajaran terstruktur yang dirancang untuk membantu siswa memahami konsep inti secara mendalam dan aplikatif.'),
                                'key_points' => [
                                    'Pahami konsep dasar ' . $lesson->title . ' terlebih dahulu.',
                                    'Hubungkan materi dengan contoh nyata agar mudah diingat.',
                                    'Kerjakan contoh soal dan cek pembahasannya di tab Contoh Soal.',
                                ],
                                'formula_or_rule' => 'Pahami Konsep -> Analisis Contoh -> Latihan Mandiri',
                            ],
                            'examples' => [
                                [
                                    'question' => 'Jelaskan inti pembelajaran dari materi "' . $lesson->title . '"!',
                                    'answer' => 'Menguasai konsep dasar serta mampu menerapkannya dalam pemecahan masalah.',
                                    'explanation' => 'Mulailah dengan memetakan cabang Mind Map lalu pelajari poin inti di tab Penjelasan.',
                                ],
                            ],
                        ];
                    }
                @endphp

                @if ($useInteractive)
                    <div x-data="{ activeTab: 'mindmap' }" class="flex-1 p-4 sm:p-6 lg:p-8 max-w-5xl w-full mx-auto pb-28 space-y-6">
                        {{-- Interactive Mode Tabs --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 bg-white/90 backdrop-blur-md p-2 rounded-2xl border border-slate-200/80 shadow-xs">
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" @click="activeTab = 'mindmap'"
                                    :class="activeTab === 'mindmap' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20' : 'text-slate-600 hover:bg-slate-100'"
                                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition flex items-center gap-2">
                                    <i class="fa-solid fa-diagram-project"></i> Mind Map
                                </button>
                                <button type="button" @click="activeTab = 'explanation'"
                                    :class="activeTab === 'explanation' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:bg-slate-100'"
                                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition flex items-center gap-2">
                                    <i class="fa-solid fa-book-open"></i> Penjelasan Materi
                                </button>
                                <button type="button" @click="activeTab = 'examples'"
                                    :class="activeTab === 'examples' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:bg-slate-100'"
                                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition flex items-center gap-2">
                                    <i class="fa-solid fa-list-check"></i> Contoh Soal ({{ count($moduleData['examples'] ?? []) }})
                                </button>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-[10px] font-extrabold uppercase tracking-wider">
                                Modul Interaktif
                            </span>
                        </div>

                        {{-- TAB 1: MIND MAP --}}
                        <div x-show="activeTab === 'mindmap'" class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-8">
                            <div class="text-center">
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-purple-600">Peta Konsep Visual</span>
                                <div class="mt-3 inline-flex items-center justify-center px-8 py-4 rounded-3xl bg-gradient-to-r from-[#6d28d9] to-[#4f46e5] text-white font-extrabold text-base sm:text-xl shadow-xl shadow-purple-600/25">
                                    {{ $moduleData['mindmap']['center'] ?? $lesson->title }}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative">
                                @foreach (($moduleData['mindmap']['branches'] ?? []) as $branch)
                                    @php
                                        $color = $branch['color'] ?? 'purple';
                                        $palette = match($color) {
                                            'indigo' => ['bg' => 'bg-indigo-50/70', 'border' => 'border-indigo-200', 'badge' => 'bg-indigo-600', 'dot' => 'bg-indigo-500'],
                                            'emerald' => ['bg' => 'bg-emerald-50/70', 'border' => 'border-emerald-200', 'badge' => 'bg-emerald-600', 'dot' => 'bg-emerald-500'],
                                            'amber' => ['bg' => 'bg-amber-50/70', 'border' => 'border-amber-200', 'badge' => 'bg-amber-500', 'dot' => 'bg-amber-500'],
                                            default => ['bg' => 'bg-purple-50/70', 'border' => 'border-purple-200', 'badge' => 'bg-purple-600', 'dot' => 'bg-purple-500'],
                                        };
                                    @endphp
                                    <div class="rounded-2xl p-5 border {{ $palette['bg'] }} {{ $palette['border'] }} hover:shadow-md transition">
                                        <div class="flex items-center gap-2.5 mb-3">
                                            <span class="w-3 h-3 rounded-full {{ $palette['badge'] }}"></span>
                                            <h3 class="text-sm font-extrabold text-slate-900">{{ $branch['title'] ?? 'Cabang' }}</h3>
                                        </div>
                                        <ul class="space-y-2">
                                            @foreach (($branch['items'] ?? []) as $item)
                                                @if (trim((string) $item) !== '')
                                                    <li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 bg-white/90 px-3.5 py-2 rounded-xl border border-white shadow-2xs">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $palette['dot'] }} mt-1.5 shrink-0"></span>
                                                        <span>{{ $item }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- TAB 2: PENJELASAN MATERI --}}
                        <div x-show="activeTab === 'explanation'" x-cloak class="space-y-5">
                            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                                <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-extrabold uppercase tracking-wider">Penjelasan Utama</span>
                                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ $lesson->title }}</h2>
                                <p class="text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line">
                                    {{ $moduleData['explanation']['summary'] ?? '' }}
                                </p>
                            </div>

                            @if (!empty($moduleData['explanation']['key_points']))
                                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-4">Poin-Poin Penting</h3>
                                    <div class="space-y-3">
                                        @foreach ($moduleData['explanation']['key_points'] as $idx => $pt)
                                            @if (trim((string) $pt) !== '')
                                                <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-extrabold flex items-center justify-center shrink-0">{{ $idx + 1 }}</span>
                                                    <p class="text-xs sm:text-sm text-slate-700 font-medium pt-0.5">{{ $pt }}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!empty($moduleData['explanation']['formula_or_rule']))
                                <div class="bg-gradient-to-r from-purple-700 to-indigo-700 rounded-3xl p-6 text-white shadow-lg">
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-purple-200">Rumus / Kaidah Kunci</span>
                                    <p class="text-base sm:text-lg font-extrabold mt-1 font-mono">{{ $moduleData['explanation']['formula_or_rule'] }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- TAB 3: CONTOH SOAL & PEMBAHASAN --}}
                        <div x-show="activeTab === 'examples'" x-cloak class="space-y-4">
                            @foreach (($moduleData['examples'] ?? []) as $eIdx => $ex)
                                <div x-data="{ showAnswer: false }" class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-extrabold">Contoh Soal #{{ $eIdx + 1 }}</span>
                                        <button type="button" @click="showAnswer = !showAnswer"
                                            class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                                            <span x-text="showAnswer ? 'Sembunyikan Pembahasan' : 'Lihat Kunci & Pembahasan'"></span>
                                        </button>
                                    </div>
                                    <p class="text-sm sm:text-base font-bold text-slate-900 leading-relaxed">{{ $ex['question'] ?? '' }}</p>

                                    <div x-show="showAnswer" x-cloak class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 space-y-2">
                                        <p class="text-xs font-extrabold text-emerald-800">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Jawaban: {{ $ex['answer'] ?? '-' }}
                                        </p>
                                        @if (!empty($ex['explanation']))
                                            <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">{{ $ex['explanation'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Skeleton Loader overlay -->
                    <div id="skeleton-screen" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/90 transition-opacity duration-550 backdrop-blur-md">
                        <div class="w-16 h-16 border-4 border-t-transparent rounded-full animate-spin {{ $isSD ? 'border-amber-500' : ($isSMP ? 'border-indigo-600' : 'border-purple-600') }} mb-4"></div>
                        <p class="text-xs font-black uppercase tracking-widest {{ $isSD ? 'text-amber-800' : ($isSMP ? 'text-indigo-600' : 'text-purple-600') }} animate-pulse">{{ __('messages.connecting_to_drive') }}</p>
                    </div>

                    <!-- External Media Iframe Container -->
                    <div class="flex-1 w-full h-full relative">
                        <iframe id="lesson-iframe" src="{{ $lesson->embed_url }}" class="absolute inset-0 w-full h-full border-0 bg-slate-100" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                @endif

                <!-- Floating Action Dock -->
                <footer class="h-20 flex items-center justify-between px-6 border-t backdrop-blur-md z-20 {{ $theme['dock_bg'] }}">
                    <!-- Previous Button -->
                    <div>
                        @if($prevLesson)
                            <a href="{{ route('student.lessons.show', $prevLesson->id) }}" class="py-2.5 px-4 text-xs font-bold flex items-center gap-1.5 transition-all hover:-translate-x-0.5 {{ $theme['rounded_sm'] }} {{ $theme['nav_btn'] }} border shadow-sm">
                                <span>←</span> <span class="hidden sm:inline">{{ __('messages.prev_lesson') }}</span>
                            </a>
                        @else
                            <button disabled class="py-2.5 px-4 text-xs font-bold text-slate-400 border border-slate-100 bg-slate-100/50 cursor-not-allowed {{ $theme['rounded_sm'] }}">
                                <span>←</span> <span class="hidden sm:inline">{{ __('messages.prev_lesson') }}</span>
                            </button>
                        @endif
                    </div>

                    <!-- Main Progress Toggle Button -->
                    <div id="action-btn-container" class="flex items-center gap-3">
                        @php
                            $currentCompleted = in_array($lesson->id, $progressList);
                        @endphp
                        @if($currentCompleted)
                            <!-- Completed State: Green Button with Undo action -->
                            <button id="btn-toggle-complete" data-completed="true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 {{ $theme['rounded_sm'] }} transition-all duration-300 flex items-center gap-2 shadow-lg shadow-emerald-600/30">
                                <svg class="w-5 h-5 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm font-black tracking-wide">{{ __('messages.lesson_completed_undo') }}</span>
                            </button>
                        @else
                            <!-- Not Completed State -->
                            <button id="btn-toggle-complete" data-completed="false" class="{{ $theme['primary_btn'] }} font-black py-3 px-6 {{ $theme['rounded_sm'] }} transition-all duration-300 flex items-center gap-2">
                                <span class="text-sm tracking-wide">{{ __('messages.mark_completed') }}</span>
                            </button>
                        @endif
                    </div>

                    <!-- Next Button -->
                    <div>
                        @if($nextLesson)
                            <a href="{{ route('student.lessons.show', $nextLesson->id) }}" class="py-2.5 px-4 text-xs font-bold flex items-center gap-1.5 transition-all hover:translate-x-0.5 {{ $theme['rounded_sm'] }} {{ $theme['nav_btn'] }} border shadow-sm">
                                <span class="hidden sm:inline">{{ __('messages.next_lesson') }}</span> <span>→</span>
                            </a>
                        @else
                            <button disabled class="py-2.5 px-4 text-xs font-bold text-slate-400 border border-slate-100 bg-slate-100/50 cursor-not-allowed {{ $theme['rounded_sm'] }}">
                                <span class="hidden sm:inline">{{ __('messages.next_lesson') }}</span> <span>→</span>
                            </button>
                        @endif
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Badge Celebration Modal Popup -->
    <div id="badge-celebration-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md hidden transition-all duration-500">
        <div class="max-w-md w-full p-8 text-center border relative overflow-hidden {{ $theme['rounded_lg'] }} {{ $isSD ? 'bg-amber-50 border-amber-200 shadow-2xl' : ($isSMP ? 'bg-white border-indigo-200 shadow-2xl' : 'bg-white border-purple-200 shadow-2xl') }}">
            
            <!-- Shine/Light effect -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl animate-pulse"></div>

            <!-- Celebration Text -->
            <span class="text-3xl animate-bounce inline-block mb-2 font-black {{ $isSD ? 'text-amber-850' : ($isSMP ? 'text-indigo-600' : 'text-purple-600') }}">{{ __('messages.hore_class_completed') }}</span>
            
            <h3 class="text-2xl font-black mt-2 {{ $isSD ? 'text-amber-950' : ($isSMP ? 'text-indigo-950' : 'text-purple-950') }} tracking-tight">{{ __('messages.congrats_great_job') }}</h3>
            <p class="text-xs mt-2 {{ $isSD ? 'text-amber-800' : ($isSMP ? 'text-slate-650' : 'text-slate-650') }} font-semibold">
                {{ __('messages.celebration_desc') }}
            </p>

            <!-- Stylized Badge Image / Icon container -->
            <div class="my-8 flex justify-center">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 p-1.5 shadow-2xl animate-spin-slow">
                    <div class="w-full h-full rounded-full flex flex-col items-center justify-center relative bg-slate-950 text-white overflow-hidden p-2">
                        <!-- Holographic shine -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/10 to-white/0 transform -skew-x-12 translate-x-full animate-shine"></div>
                        <span id="earned-badge-image" class="text-6xl mb-1 filter drop-shadow">🏆</span>
                        <span id="earned-badge-name" class="text-[9px] font-black tracking-widest text-center uppercase {{ $isSD ? 'text-teal-300' : ($isSMP ? 'text-indigo-300' : 'text-purple-300') }} truncate max-w-full">{{ __('messages.honor_badge') }}</span>
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="space-y-3">
                <button id="btn-close-celebration" class="w-full py-3.5 {{ $theme['primary_btn'] }} font-black text-sm {{ $theme['rounded_sm'] }} active:scale-95 transition-all shadow-md">
                    {{ __('messages.continue_learning_celebration') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts section -->
    <script>
        const trans = {
            discussions: "{{ __('messages.discussion') }}",
            reply: "{{ __('messages.reply') }}",
            delete_discussion: "{{ __('messages.delete_discussion') }}",
            no_discussions: "{{ __('messages.no_discussions') }}",
            comment_box_guideline: "{{ __('messages.comment_box_guideline') }}"
        };

        const roleLabels = {
            student: "{{ __('messages.student') }}",
            guru: "{{ __('messages.teacher') }}",
            admin: "{{ __('messages.admin') }}",
            pimpinan: "{{ __('messages.principal') }}"
        };

        document.addEventListener('DOMContentLoaded', () => {
            
            // 1. Sidebar Collapse / Theater Mode Toggle
            const sidebar = document.getElementById('sidebar-nav');
            const toggleBtn = document.getElementById('sidebar-toggle');
            
            // Simpan status toggle sidebar
            let isSidebarCollapsed = false;

            toggleBtn.addEventListener('click', () => {
                isSidebarCollapsed = !isSidebarCollapsed;
                if (isSidebarCollapsed) {
                    sidebar.style.transform = 'translateX(-100%)';
                    // Sembunyikan sidebar di screen lebar
                    sidebar.classList.add('lg:hidden');
                } else {
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.classList.remove('lg:hidden');
                }
            });

            // 2. Skeleton screen loader handler for iframe
            const iframe = document.getElementById('lesson-iframe');
            const skeletonScreen = document.getElementById('skeleton-screen');

            iframe.onload = () => {
                skeletonScreen.classList.add('opacity-0');
                setTimeout(() => {
                    skeletonScreen.classList.add('hidden');
                }, 550);
            };

            // 3. Progress AJAX Logic (Zero page refresh with Forgiving Design)
            const toggleCompleteBtn = document.getElementById('btn-toggle-complete');
            const btnContainer = document.getElementById('action-btn-container');
            const progressBarFill = document.getElementById('progress-bar-fill');
            const progressText = document.getElementById('progress-text');
            const celebrationModal = document.getElementById('badge-celebration-modal');

            // Hook click event
            btnContainer.addEventListener('click', async (e) => {
                const targetBtn = e.target.closest('#btn-toggle-complete');
                if (!targetBtn) return;

                const currentStatus = targetBtn.getAttribute('data-completed') === 'true';
                const nextStatus = !currentStatus;

                // Nonaktifkan tombol sementara agar tidak double klik
                targetBtn.disabled = true;
                targetBtn.style.opacity = '0.7';

                try {
                    const response = await fetch('{{ route("student.lessons.progress", $lesson->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            is_completed: nextStatus
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memperbarui progres materi.');
                    }

                    const data = await response.json();

                    if (data.success) {
                        // 1. Transform Button (Visual Feedback)
                        if (data.is_completed) {
                            btnContainer.innerHTML = `
                                <button id="btn-toggle-complete" data-completed="true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 {{ $theme['rounded_sm'] }} transition-all duration-300 flex items-center gap-2 shadow-lg shadow-emerald-600/30">
                                    <svg class="w-5 h-5 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm font-black tracking-wide">Materi Selesai (Batal?)</span>
                                </button>
                            `;
                        } else {
                            btnContainer.innerHTML = `
                                <button id="btn-toggle-complete" data-completed="false" class="{{ $theme['primary_btn'] }} font-black py-3 px-6 {{ $theme['rounded_sm'] }} transition-all duration-300 flex items-center gap-2">
                                    <span class="text-sm tracking-wide">Tandai Selesai Membaca/Menonton</span>
                                </button>
                            `;
                        }

                        // 2. Update Sidebar Checkbox status
                        const currentLessonId = '{{ $lesson->id }}';
                        const sidebarCheckbox = document.querySelector(`.lesson-item[data-lesson-id="${currentLessonId}"] .progress-checkbox`);
                        if (sidebarCheckbox) {
                            if (data.is_completed) {
                                sidebarCheckbox.className = 'progress-checkbox w-6 h-6 rounded-full flex items-center justify-center border transition-all duration-300 bg-emerald-500 border-emerald-400 text-white';
                                sidebarCheckbox.querySelector('.check-icon').classList.remove('hidden');
                                sidebarCheckbox.setAttribute('data-completed', 'true');
                            } else {
                                sidebarCheckbox.className = 'progress-checkbox w-6 h-6 rounded-full flex items-center justify-center border transition-all duration-300 {{ $isSD ? "border-amber-300 bg-amber-50/50" : ($isSMP ? "border-indigo-200 bg-indigo-50/30" : "border-purple-200 bg-purple-50/30") }}';
                                sidebarCheckbox.querySelector('.check-icon').classList.add('hidden');
                                sidebarCheckbox.setAttribute('data-completed', 'false');
                            }
                        }

                        // 3. Update Progress Bar & Text
                        progressBarFill.style.width = `${data.progress_percentage}%`;
                        progressText.textContent = `${Math.round(data.progress_percentage)}%`;

                        // 4. Trigger Badge Celebration Popup if earned
                        if (data.earned_badge) {
                            document.getElementById('earned-badge-name').textContent = data.badge_name || 'Lencana Kehormatan';
                            if (data.badge_image) {
                                document.getElementById('earned-badge-image').textContent = data.badge_image;
                            }
                            
                            // Trigger Confetti
                            triggerCelebrationConfetti();
                            
                            // Show modal
                            celebrationModal.classList.remove('hidden');
                            celebrationModal.classList.add('flex');
                        }
                    }
                } catch (error) {
                    alert("{{ __('messages.network_error') }}: " + error.message);
                }
            });

            // 4. Celebration Close Button
            const btnCloseCelebration = document.getElementById('btn-close-celebration');
            if (btnCloseCelebration) {
                btnCloseCelebration.addEventListener('click', () => {
                    celebrationModal.classList.add('hidden');
                    celebrationModal.classList.remove('flex');
                });
            }

            // 5. Lightweight Confetti Particle Generator
            function triggerCelebrationConfetti() {
                const container = document.getElementById('confetti-container');
                const colors = ['#f43f5e', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'];
                
                for (let i = 0; i < 100; i++) {
                    const particle = document.createElement('div');
                    particle.classList.add('confetti-particle');
                    
                    // Random styles
                    particle.style.left = `${Math.random() * 100}vw`;
                    particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    particle.style.width = `${Math.random() * 12 + 6}px`;
                    particle.style.height = `${Math.random() * 12 + 6}px`;
                    
                    const duration = Math.random() * 3 + 2; // 2s - 5s
                    const delay = Math.random() * 2; // 0s - 2s
                    particle.style.animationDuration = `${duration}s`;
                    particle.style.animationDelay = `${delay}s`;
                    
                    // Different shapes
                    if (Math.random() > 0.5) {
                        particle.style.borderRadius = '50%';
                    }
                    
                    container.appendChild(particle);

                    // Auto clean up
                    setTimeout(() => {
                        particle.remove();
                    }, (duration + delay) * 1000);
                }
            }

            // 6. Discussion Forum Tab & AJAX Logic
            const tabModules = document.getElementById('tab-modules');
            const tabForum = document.getElementById('tab-forum');
            const modulesContainer = document.getElementById('lessons-list-container');
            const forumContainer = document.getElementById('forum-container');

            const activeTabClasses = '{{ $isSD ? "border-teal-500 text-teal-600" : ($isSMP ? "border-indigo-600 text-indigo-600" : "border-purple-650 text-purple-650") }}'.split(' ');
            const inactiveTabClasses = 'border-transparent text-slate-400 hover:text-slate-650 hover:border-slate-200'.split(' ');

            let pollingInterval = null;

            function startPolling() {
                if (pollingInterval) clearInterval(pollingInterval);
                // Polling komentar setiap 12 detik saat tab aktif (hemat bandwidth dari sebelumnya 6s)
                pollingInterval = setInterval(() => {
                    if (document.hidden) return;
                    fetchComments();
                }, 12000);
            }

            function stopPolling() {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            // Hentikan request ke server jika tab diminimalkan atau beralih ke aplikasi lain
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    stopPolling();
                } else if (forumContainer && !forumContainer.classList.contains('hidden')) {
                    fetchComments();
                    startPolling();
                }
            });

            tabModules.addEventListener('click', () => {
                tabModules.classList.add(...activeTabClasses);
                tabModules.classList.remove(...inactiveTabClasses);
                tabForum.classList.remove(...activeTabClasses);
                tabForum.classList.add(...inactiveTabClasses);
                modulesContainer.classList.remove('hidden');
                forumContainer.classList.add('hidden');
                stopPolling();
            });

            tabForum.addEventListener('click', () => {
                tabForum.classList.add(...activeTabClasses);
                tabForum.classList.remove(...inactiveTabClasses);
                tabModules.classList.remove(...activeTabClasses);
                tabModules.classList.add(...inactiveTabClasses);
                forumContainer.classList.remove('hidden');
                modulesContainer.classList.add('hidden');

                // Mark discussions as read
                const readUrl = '{{ Auth::user()->role === "student" ? route("student.lessons.read", $lesson->id) : route("teacher.lessons.read", $lesson->id) }}';
                fetch(readUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(() => {
                    const redDot = document.getElementById('tab-forum-red-dot');
                    if (redDot) redDot.classList.add('hidden');
                });

                // Fetch comments and start polling
                fetchComments().then(() => {
                    const commentsList = document.getElementById('comments-list');
                    commentsList.scrollTop = commentsList.scrollHeight;
                });
                startPolling();
            });

            // Post Comment AJAX
            const commentForm = document.getElementById('comment-post-form');
            const commentInput = document.getElementById('comment-input');
            const btnSubmitComment = document.getElementById('btn-submit-comment');
            const commentsList = document.getElementById('comments-list');
            const commentsCountBadge = document.getElementById('comments-count-badge');

            // Reply elements
            const parentCommentInput = document.getElementById('parent-comment-id');
            const replyInfoBar = document.getElementById('reply-info-bar');
            const replyTargetUsername = document.getElementById('reply-target-username');
            const btnCancelReply = document.getElementById('btn-cancel-reply');

            // Auto resize textarea height dynamically
            commentInput.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Handle reply button click
            commentsList.addEventListener('click', (e) => {
                const replyBtn = e.target.closest('.reply-comment-btn');
                if (replyBtn) {
                    const commentId = replyBtn.getAttribute('data-comment-id');
                    const username = replyBtn.getAttribute('data-username');
                    
                    parentCommentInput.value = commentId;
                    replyTargetUsername.textContent = '@' + username;
                    replyInfoBar.classList.remove('hidden');
                    commentInput.focus();
                }
            });

            // Handle cancel reply click
            if (btnCancelReply) {
                btnCancelReply.addEventListener('click', () => {
                    parentCommentInput.value = '';
                    replyInfoBar.classList.add('hidden');
                });
            }

            commentForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const contentVal = commentInput.value.trim();
                if (!contentVal) return;

                btnSubmitComment.disabled = true;
                commentInput.disabled = true;

                const parentIdVal = parentCommentInput.value || null;

                try {
                    const response = await fetch('{{ Auth::user()->role === "student" ? route("student.lessons.comments.store", $lesson->id) : route("teacher.lessons.comments.store", $lesson->id) }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                         },
                         body: JSON.stringify({
                             content: contentVal,
                             parent_id: parentIdVal
                         })
                    });

                    if (!response.ok) {
                        throw new Error('Gagal mengirim komentar.');
                    }

                    const data = await response.json();

                    if (data.success) {
                        commentInput.value = '';
                        commentInput.style.height = 'auto';
                        parentCommentInput.value = '';
                        replyInfoBar.classList.add('hidden');
                        
                        // Refetch comments to update UI in order
                        await fetchComments();
                        commentsList.scrollTop = commentsList.scrollHeight;
                    }
                } catch (error) {
                    alert("{{ __('messages.send_comment_error') }}: " + error.message);
                } finally {
                    btnSubmitComment.disabled = false;
                    commentInput.disabled = false;
                    commentInput.focus();
                }
            });

            // Delete Comment AJAX (Event Delegation)
            commentsList.addEventListener('click', async (e) => {
                const deleteBtn = e.target.closest('.delete-comment-btn');
                if (!deleteBtn) return;

                const commentId = deleteBtn.getAttribute('data-comment-id');
                if (!commentId) return;

                if (!confirm('Apakah Anda yakin ingin menghapus diskusi ini?')) return;

                deleteBtn.disabled = true;
                deleteBtn.style.opacity = '0.5';

                const deleteBaseUrl = '{{ Auth::user()->role === "student" ? url("/ruang-belajar/comments") : url("/ruang-belajar/preview/comments") }}';

                try {
                    const response = await fetch(`${deleteBaseUrl}/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (!response.ok) {
                        throw new Error("{{ __('messages.delete_comment_error') }}.");
                    }

                    const data = await response.json();

                    if (data.success) {
                        await fetchComments();
                    }
                } catch (error) {
                    alert("{{ __('messages.delete_comment_error') }}: " + error.message);
                    deleteBtn.disabled = false;
                    deleteBtn.style.opacity = '1';
                }
            });

            // Polling fetchComments function
            async function fetchComments() {
                try {
                    const pollUrl = '{{ Auth::user()->role === "student" ? route("student.lessons.comments.index", $lesson->id) : route("teacher.lessons.comments.index", $lesson->id) }}';
                    const response = await fetch(pollUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) return;
                    const data = await response.json();
                    if (data.success) {
                        renderComments(data.comments);
                    }
                } catch (err) {
                    console.error('Polling error:', err);
                }
            }

            // HTML Renderer for Comments
            function renderComments(comments) {
                if (comments.length === 0) {
                    commentsCountBadge.textContent = '0 ' + trans.discussions;
                    commentsList.innerHTML = `
                        <div class="text-center py-10 px-4" id="comments-empty-state">
                            <span class="text-4xl">💬</span>
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mt-3">${trans.no_discussions}</h3>
                            <p class="text-[11px] text-slate-400 mt-1 font-medium">${trans.comment_box_guideline}</p>
                        </div>
                    `;
                    return;
                }

                let html = '';
                comments.forEach(comment => {
                    const isStudent = comment.user_role === 'student';
                    const gradClass = isStudent ? 'from-amber-400 to-orange-500' : 'from-indigo-500 to-purple-650';
                    let roleLabel = roleLabels[comment.user_role] || comment.user_role;
                    let badgeClass = 'bg-slate-100 text-slate-700';
                    if (comment.user_role === 'guru') {
                        badgeClass = 'bg-indigo-100 text-indigo-800 border border-indigo-200';
                    } else if (comment.user_role === 'admin') {
                        badgeClass = 'bg-rose-100 text-rose-800 border border-rose-200';
                    } else if (comment.user_role === 'pimpinan') {
                        badgeClass = 'bg-purple-100 text-purple-800 border border-purple-200';
                    }

                    let repliesHtml = '';
                    comment.replies.forEach(reply => {
                        const rStudent = reply.user_role === 'student';
                        const rGradClass = rStudent ? 'from-amber-400 to-orange-500' : 'from-indigo-500 to-purple-650';
                        let rRoleLabel = roleLabels[reply.user_role] || reply.user_role;
                        let rBadgeClass = 'bg-slate-100 text-slate-700';
                        if (reply.user_role === 'guru') {
                            rBadgeClass = 'bg-indigo-100 text-indigo-800 border border-indigo-200';
                        } else if (reply.user_role === 'admin') {
                            rBadgeClass = 'bg-rose-100 text-rose-800 border border-rose-200';
                        } else if (reply.user_role === 'pimpinan') {
                            rBadgeClass = 'bg-purple-100 text-purple-800 border border-purple-200';
                        }

                        let rDeleteBtn = '';
                        if (reply.is_owner_or_staff) {
                            rDeleteBtn = `
                                <button class="delete-comment-btn absolute top-2 right-2 text-slate-300 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition-all focus:opacity-100" data-comment-id="${reply.id}" title="${trans.delete_discussion}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            `;
                        }

                        repliesHtml += `
                            <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-100/50 border border-slate-150/40 relative group comment-item reply-item" data-comment-id="${reply.id}">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black text-white shrink-0 bg-gradient-to-br ${rGradClass}">
                                    ${reply.user_name.substring(0, 2).toUpperCase()}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-[11px] font-bold text-slate-800 truncate max-w-[100px]">${reply.user_name}</span>
                                        <span class="text-[8px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded scale-90 ${rBadgeClass}">
                                            ${rRoleLabel}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-700 font-medium leading-relaxed mt-0.5 break-words font-sans">
                                        ${reply.content}
                                    </p>
                                    <span class="text-[8px] text-slate-400 font-semibold mt-0.5 block">
                                        ${reply.created_at_formatted}
                                    </span>
                                </div>
                                ${rDeleteBtn}
                            </div>
                        `;
                    });

                    let deleteBtn = '';
                    if (comment.is_owner_or_staff) {
                        deleteBtn = `
                            <button class="delete-comment-btn absolute top-2 right-2 text-slate-300 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition-all focus:opacity-100" data-comment-id="${comment.id}" title="${trans.delete_discussion}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        `;
                    }

                    html += `
                        <div class="space-y-2 root-comment-container" data-comment-id="${comment.id}">
                            <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-150 relative group comment-item" data-comment-id="${comment.id}">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black text-white shrink-0 bg-gradient-to-br ${gradClass}">
                                    ${comment.user_name.substring(0, 2).toUpperCase()}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-xs font-bold text-slate-800 truncate max-w-[120px]">${comment.user_name}</span>
                                        <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded scale-90 ${badgeClass}">
                                            ${roleLabel}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-700 font-medium leading-relaxed mt-1 break-words font-sans">
                                        ${comment.content}
                                    </p>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <span class="text-[9px] text-slate-400 font-semibold">
                                            ${comment.created_at_formatted}
                                        </span>
                                        <button type="button" class="reply-comment-btn text-[9px] text-purple-650 dark:text-purple-400 font-black hover:underline focus:outline-none" data-comment-id="${comment.id}" data-username="${comment.user_name}">
                                            ${trans.reply}
                                        </button>
                                    </div>
                                </div>
                                ${deleteBtn}
                            </div>
                            <div class="replies-list pl-8 space-y-2">
                                ${repliesHtml}
                            </div>
                        </div>
                    `;
                });

                let totalCount = comments.length;
                comments.forEach(c => totalCount += c.replies.length);
                commentsCountBadge.textContent = `${totalCount} ` + trans.discussions;

                const wasAtBottom = commentsList.scrollHeight - commentsList.scrollTop - commentsList.clientHeight < 60;
                commentsList.innerHTML = html;
                if (wasAtBottom) {
                    commentsList.scrollTop = commentsList.scrollHeight;
                }
            }

            // Click tab if URL parameter specifies diskusi
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tab') === 'diskusi') {
                setTimeout(() => {
                    tabForum.click();
                }, 300);
            }
        });
    </script>
</body>
</html>
