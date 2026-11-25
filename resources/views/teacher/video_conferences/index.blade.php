@extends('layouts.app')

@section('title', 'Penjadwalan Kelas Online')

@section('content')
<div class="py-6 sm:py-12 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white/40 dark:bg-gray-900/40 backdrop-blur-md p-6 rounded-3xl border border-white/20 dark:border-gray-800/40 shadow-sm">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">Kelas Online</h1>
                    @if(!empty($isPimpinan))
                        <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 font-extrabold text-xs rounded-full flex items-center gap-1.5 shadow-sm">
                            👑 Mode Supervisi Pimpinan
                        </span>
                    @endif
                </div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1">
                    {{ !empty($isPimpinan) ? 'Pantau dan ikuti sesi Kelas Online aktif dari seluruh guru untuk supervisi pembelajaran.' : 'Kelola dan jadwalkan sesi Kelas Online WebRTC untuk masing-masing siswa.' }}
                </p>
            </div>
            @if(empty($isPimpinan) || Auth::user()->isSuperadmin())
            <button onclick="showCreateModal()" class="px-6 py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-2xl text-sm shadow-lg shadow-purple-500/20 active:scale-95 transition-all whitespace-nowrap">
                + Jadwalkan Kelas Baru
            </button>
            @endif
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl text-sm font-bold shadow-sm animate-pulse">
                {{ session('success') }}
            </div>
        @endif

        <div>
            <!-- List Jadwal Kelas Online -->
            <div class="space-y-6">
                <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">
                    {{ !empty($isPimpinan) ? 'Daftar Kelas Online Seluruh Guru' : 'Daftar Sesi Terjadwal' }}
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($conferences as $conf)
                        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 border border-gray-150/40 dark:border-gray-800/40 shadow-md flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex flex-wrap gap-1.5 max-w-[75%]">
                                        @if($conf->teacher)
                                            <span class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-indigo-150/20 flex items-center gap-1">
                                                👨‍🏫 {{ $conf->teacher->name }}
                                            </span>
                                        @endif
                                        <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-purple-150/20 truncate">
                                            Peserta: {{ is_array($conf->class) ? (empty($conf->target_students) ? implode(', ', $conf->class) : implode(', ', $conf->target_students)) : $conf->class }}
                                        </span>
                                    </div>
                                    <span class="px-2 py-1 {{ $conf->is_active ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border-emerald-200/30' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200/20' }} text-[9px] font-black uppercase tracking-widest rounded-md border flex-shrink-0">
                                        {{ $conf->is_active ? '🟢 Aktif' : ($conf->scheduled_at->isPast() ? 'Selesai' : 'Akan Datang') }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight line-clamp-1 mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                    {{ $conf->title }}
                                </h3>
                                
                                <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-4">
                                    Room ID: <code class="text-indigo-500 dark:text-indigo-400">{{ $conf->room_name }}</code>
                                </div>

                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6 font-semibold">
                                    📅 Jadwal Mulai: {{ $conf->scheduled_at->format('d M Y H:i') }}
                                </p>
                            </div>

                            @php
                                $isMyClass = ($conf->teacher_id == Auth::id());
                                $btnText = $isMyClass ? 'Mulai Kelas' : (!empty($isPimpinan) ? '👑 Supervisi Kelas' : 'Masuk Kelas');
                                $canDelete = $isMyClass || Auth::user()->isSuperadmin();
                            @endphp
                            <div class="grid {{ $canDelete ? 'grid-cols-2' : 'grid-cols-1' }} gap-2 pt-4 border-t border-gray-100 dark:border-gray-800/60">
                                <a href="{{ route('teacher.video-conference.join', $conf->room_name) }}" class="text-center py-2.5 {{ $isMyClass ? 'bg-purple-600 hover:bg-purple-700 text-white' : 'bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black' }} rounded-xl font-extrabold text-xs shadow-md transition duration-200 flex items-center justify-center gap-1.5">
                                    <span>{{ $btnText }}</span>
                                </a>
                                
                                @if($canDelete)
                                <form action="{{ route('teacher.video-conferences.destroy', $conf) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal kelas online ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center py-2.5 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 rounded-xl font-bold text-xs hover:bg-rose-100 dark:hover:bg-rose-900/20 transition duration-200">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white/40 dark:bg-gray-900/40 rounded-3xl border-2 border-dashed border-purple-200/50 dark:border-purple-800/40 text-gray-400 dark:text-gray-500 p-8 shadow-sm">
                            <p class="text-sm font-bold">Belum ada sesi kelas online yang dijadwalkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Form Jadwal Kelas -->
<div id="createModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-150/40 dark:border-gray-800/40 shadow-2xl p-6 w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0" id="createModalContent">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Jadwalkan Kelas Baru</h3>
            <button onclick="hideCreateModal()" class="text-gray-400 hover:text-rose-500 transition-colors p-2 rounded-full hover:bg-rose-50 dark:hover:bg-rose-950/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('teacher.video-conferences.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="title" class="block text-xs font-black uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Judul Sesi / Mata Pelajaran</label>
                <input type="text" name="title" id="title" required placeholder="Contoh: Matematika - Aljabar"
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/40 transition">
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Pilih Siswa</label>

                <div class="flex justify-between items-center mb-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-purple-500 hover:text-purple-600 transition-colors">
                        <input type="checkbox" id="selectAllStudents" class="w-3.5 h-3.5 rounded text-purple-600 border-slate-350 focus:ring-purple-500 bg-white dark:bg-slate-700">
                        Pilih Semua
                    </label>
                </div>
                
                <div class="mb-3">
                    <input type="text" id="searchStudent" placeholder="🔍 Cari nama siswa..." 
                           class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/40 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-48 overflow-y-auto p-3 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-750 rounded-xl" id="studentListContainer">
                    @foreach($students as $student)
                        <label class="student-item flex items-center gap-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 cursor-pointer hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            <input type="checkbox" name="students[]" value="{{ $student->user_id ?? $student->id }}" class="student-checkbox w-4 h-4 rounded text-purple-600 border-slate-350 focus:ring-purple-500 bg-white dark:bg-slate-700">
                            <span class="truncate student-name">{{ $student->nama_siswa }} <small class="text-gray-400">({{ $student->class }})</small></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="scheduled_at" class="block text-xs font-black uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Waktu Mulai</label>
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" required
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500/40 transition">
            </div>

            <div class="pt-2 flex gap-3">
                <button type="button" onclick="hideCreateModal()" class="w-full py-3.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl text-sm transition-all text-center">
                    Batal
                </button>
                <button type="submit" class="w-full py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-2xl text-sm shadow-lg shadow-purple-500/20 active:scale-95 transition-all text-center">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showCreateModal() {
        const modal = document.getElementById('createModal');
        const content = document.getElementById('createModalContent');
        modal.classList.remove('hidden');
        // trigger reflow
        void modal.offsetWidth;
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }

    function hideCreateModal() {
        const modal = document.getElementById('createModal');
        const content = document.getElementById('createModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.getElementById('selectAllStudents').addEventListener('change', function() {
        const isChecked = this.checked;
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => {
            // Only toggle visible checkboxes
            if(cb.closest('.student-item').style.display !== 'none') {
                cb.checked = isChecked;
            }
        });
    });

    document.getElementById('searchStudent').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const items = document.querySelectorAll('.student-item');
        
        items.forEach(item => {
            const name = item.querySelector('.student-name').textContent.toLowerCase();
            if (name.includes(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endsection
