@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8" x-data="{ 
    showPreview: false, 
    previewUrl: '', 
    previewLoaded: false,
    selectedTahunAjaran: '{{ request('tahun_ajaran', 'Semua') }}',
    showFinalizeModal: false,
    finalizeUrl: '',
    finalizeReportName: '',
    finalizePeriod: '',
    triggerFinalize(url, name, period) {
        this.finalizeUrl = url;
        this.finalizeReportName = name;
        this.finalizePeriod = period;
        this.showFinalizeModal = true;
    },
    submitFinalize() {
        fetch(this.finalizeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Terjadi kesalahan.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan koneksi.');
        });
    }
}">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            @php
                $backPeriod = request('tahun_ajaran') && request('tahun_ajaran') !== 'Semua' 
                    ? request('tahun_ajaran') 
                    : \App\Helpers\ReportHelper::getCurrentPeriod();
            @endphp
            <a href="{{ route('admin.reports.index', ['period' => $backPeriod]) }}" 
               class="h-12 w-12 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-gray-500 shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-blue-600 hover:text-white transition-all active:scale-90 group">
                <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">Daftar Raport</h2>
                <p class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-xs">
                    {{ $siswa->nama_siswa }} • NIS: {{ $siswa->nis ?? '-' }}
                    @if(request('tahun_ajaran') && request('tahun_ajaran') !== 'Semua')
                        • Periode: {{ request('tahun_ajaran') }}
                    @endif
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-green-50 dark:bg-green-900/30 border border-green-100 dark:border-green-800 p-6 rounded-[24px] flex items-center gap-4 text-green-700 dark:text-green-400">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Reports Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($reports as $report)
                <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="p-8 flex-1">
                        <div class="flex justify-between items-start mb-6">
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/40 rounded-2xl text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <span class="px-4 py-1.5 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $report->created_at->format('d M Y') }}</span>
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <h3 class="text-xl font-black text-gray-900 dark:text-white leading-none">{{ $report->category->name }}</h3>
                                @if($report->is_draft)
                                    <span class="px-2.5 py-0.5 bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-400 text-[10px] font-black rounded-lg border border-amber-200/30 uppercase tracking-widest leading-none">
                                        Draft
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 italic">"{{ $report->period }}"</p>
                            @if($report->academic_type)
                                <span class="inline-block mt-2 px-3 py-1 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $report->academic_type }}
                                </span>
                            @endif
                        </div>

                        <!-- Teacher Info -->
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <div class="h-8 w-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 font-black text-xs">
                                {{ substr($report->teacher->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Fasilitator</p>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-gray-300 truncate">{{ $report->teacher->name ?? 'Admin Sekolah' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-0 space-y-3">
                        <div class="flex gap-2">
                            <a :href="'{{ route('admin.reports.show', $report) }}?tahun_ajaran=' + selectedTahunAjaran" class="flex-1 py-3.5 bg-gray-900 dark:bg-gray-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest text-center hover:bg-blue-600 transition-colors active:scale-95">
                                Detail
                            </a>
                            <button @click="previewUrl = '{{ route('admin.reports.preview', $report) }}'; showPreview = true; previewLoaded = false" class="flex-1 py-3.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-650 dark:text-indigo-400 rounded-2xl font-black text-[10px] uppercase tracking-widest text-center hover:bg-indigo-100 transition-colors active:scale-95">
                                Pratinjau
                            </button>
                            <a :href="'{{ route('admin.reports.edit', $report) }}?tahun_ajaran=' + selectedTahunAjaran" class="p-3.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-2xl hover:bg-amber-100 transition-colors active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form :action="'{{ route('admin.reports.destroy', $report) }}?tahun_ajaran=' + selectedTahunAjaran" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus raport ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl hover:bg-red-100 transition-colors active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                        @if($report->is_draft)
                            <button @click="triggerFinalize('{{ route('admin.reports.finalize', $report) }}', '{{ $report->category->name }}', '{{ $report->period }}')" class="flex items-center justify-center gap-2 w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all duration-300 shadow-lg shadow-emerald-600/10 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Finalkan Rapor
                            </button>
                        @else
                            <button disabled class="flex items-center justify-center gap-2 w-full py-3 bg-gray-100 dark:bg-gray-700/50 text-gray-400 dark:text-gray-550 rounded-2xl font-black text-[10px] uppercase tracking-widest cursor-not-allowed border border-gray-200 dark:border-gray-700">
                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Rapor Telah Final (Terkunci)
                            </button>
                        @endif
                        <a href="{{ route('admin.reports.pdf', $report) }}" class="flex items-center justify-center gap-2 w-full py-3.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-100 transition-colors active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download PDF
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-[40px] border-2 border-dashed border-gray-200 dark:border-gray-700 p-20 text-center">
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Belum Ada Raport</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto font-medium">Siswa ini belum memiliki data raport yang tersimpan.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="showPreview" 
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white dark:bg-gray-900 rounded-[40px] w-full max-w-5xl h-[90vh] shadow-2xl overflow-hidden flex flex-col transform transition-all"
             @click.away="showPreview = false; previewUrl = ''; previewLoaded = false">
            
            <div class="px-8 py-6 flex items-center justify-between border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-wider">Pratinjau Raport</h3>
                </div>
                <button @click="showPreview = false; previewUrl = ''; previewLoaded = false" class="h-10 w-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 bg-gray-100 dark:bg-black/20 p-4 relative">
                <!-- Loading State -->
                <div x-show="!previewLoaded" class="absolute inset-0 flex flex-col items-center justify-center gap-4">
                    <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-sm font-bold text-gray-500 animate-pulse uppercase tracking-widest">Menyiapkan Dokumen...</p>
                </div>

                <template x-if="showPreview">
                    <iframe :src="previewUrl" 
                            @load="previewLoaded = true"
                            class="w-full h-full rounded-2xl border-none shadow-inner bg-white"
                            type="application/pdf">
                    </iframe>
                </template>
            </div>
            
            <div class="px-8 py-6 bg-gray-50 dark:bg-gray-900/50 border-t dark:border-gray-800 flex justify-end">
                <button @click="showPreview = false; previewUrl = ''; previewLoaded = false" class="px-8 py-3 bg-gray-900 dark:bg-gray-700 text-white rounded-xl font-black uppercase tracking-widest text-xs hover:bg-blue-600 transition-all">Tutup</button>
            </div>
        </div>
    </div>
    <!-- Finalize Confirmation Modal (Immersive Design) -->
    <div x-show="showFinalizeModal" 
         class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-xl"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-[40px] w-full max-w-lg shadow-2xl overflow-hidden transform transition-all flex flex-col p-8 md:p-10 relative"
             @click.away="showFinalizeModal = false">
            
            <!-- Warning Badge Decorator -->
            <div class="mx-auto w-16 h-16 bg-amber-50 dark:bg-amber-950/30 rounded-3xl flex items-center justify-center text-amber-500 dark:text-amber-400 mb-6 border border-amber-100 dark:border-amber-900/30 relative">
                <span class="absolute inset-0 rounded-3xl bg-amber-400 animate-ping opacity-25"></span>
                <svg class="w-8 h-8 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <div class="text-center mb-8">
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-wide uppercase">Finalkan Rapor</h3>
                <p class="text-sm font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest leading-none mb-4" x-text="finalizeReportName + ' • ' + finalizePeriod"></p>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-relaxed">
                    Apakah Anda yakin ingin memfinalkan rapor ini? Aksi ini akan memicu hal-hal berikut:
                </p>
            </div>

            <!-- Immersive Trigger Checkpoints -->
            <div class="space-y-4 mb-8 bg-gray-50 dark:bg-gray-950/50 p-6 rounded-[28px] border border-gray-150 dark:border-gray-850">
                <div class="flex items-start gap-4">
                    <div class="h-8 w-8 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-wider">Rilis ke Student Portal</h4>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed mt-0.5">Nilai & perkembangan siswa akan langsung dipublikasikan dan dapat diakses oleh murid & orang tua.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="h-8 w-8 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-450 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-wider">Kunci Perubahan Nilai</h4>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed mt-0.5">Rapor akan dikunci dan **tidak dapat diedit kembali** oleh guru (kecuali dengan persetujuan pimpinan).</p>
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-end">
                <button @click="showFinalizeModal = false" 
                        class="w-full sm:w-auto px-6 py-4 text-center text-xs font-black text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 uppercase tracking-widest transition">
                    Batal
                </button>
                <button @click="submitFinalize()" 
                        class="w-full sm:w-auto px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 shadow-lg shadow-emerald-600/10 active:scale-95">
                    Ya, Finalkan & Kunci Rapor
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
