@extends('layouts.app')

@section('content')
@php
    $activeSchool = $invoice->profilSekolah ?? $schoolProfile;
@endphp
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen" x-data="{ showUploadModal: false, step: 1, preview: null, checkedItems: [@foreach($invoice->invoiceDetails as $item) '{{ $item->id }}', @endforeach], selectAll: true }">
    <!-- Header Section -->
    <div class="mb-8 sm:mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4 md:gap-6">
        <div>
            <div class="flex items-center gap-3.5 mb-4">
                <a href="{{ auth()->user()->role === 'student' ? route('student.invoices') : route('invoices.index') }}" class="h-10 w-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-gray-500 shadow-md border border-gray-100 dark:border-gray-700/80 hover:bg-purple-600 hover:text-white transition-all active:scale-95 group">
                    <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <span class="px-3.5 py-1.5 bg-purple-100 text-purple-750 dark:bg-purple-950/40 dark:text-purple-450 rounded-full text-[9px] font-black uppercase tracking-widest border border-purple-200/20 shadow-sm">{{ __('messages.invoice_details') }}</span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-black text-gray-955 dark:text-white tracking-tight mb-2 font-display">Invoice #{{ $invoice->no_invoice }}</h2>
            <p class="text-gray-400 dark:text-gray-500 text-xs sm:text-sm font-bold">{{ __('messages.invoice_published_on') }} {{ $invoice->tanggal_invoice ? $invoice->tanggal_invoice->format('d M Y') : '-' }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('student.invoices.pdf', $invoice) }}" class="flex items-center justify-center flex-1 md:flex-none gap-2 px-6 py-3.5 bg-slate-900 dark:bg-slate-800 text-white rounded-2xl text-xs sm:text-sm font-black shadow-lg hover:bg-purple-650 transition-all active:scale-95">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                {{ __('messages.download_invoice') }}
            </a>
            <a href="{{ route('student.invoices.kwitansi', $invoice) }}" class="flex items-center justify-center flex-1 md:flex-none gap-2 px-6 py-3.5 bg-amber-600 text-white rounded-2xl text-xs sm:text-sm font-black shadow-lg hover:bg-amber-700 transition-all active:scale-95">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download Kwitansi
            </a>
            @if($invoice->status !== 'paid')
                <button @click="showUploadModal = true" class="flex items-center justify-center flex-1 md:flex-none gap-2 px-6 py-3.5 bg-purple-650 text-white rounded-2xl text-xs sm:text-sm font-black shadow-lg shadow-purple-600/20 hover:bg-purple-700 transition-all active:scale-95">
                    {{ __('messages.pay_now') }}
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-5 bg-purple-50 dark:bg-purple-950/30 border-l-4 border-purple-500 rounded-2xl text-purple-800 dark:text-purple-300 font-bold flex items-center gap-3 shadow-sm border border-purple-200/20">
            <svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-xs sm:text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-5 bg-rose-50 dark:bg-rose-950/30 border-l-4 border-rose-500 rounded-2xl text-rose-800 dark:text-rose-300 font-bold flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-xs sm:text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Left Side - Services & Proofs) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Items Table Card -->
            <div class="clay-card shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/60 dark:bg-slate-900/40 border-b border-gray-100 dark:border-gray-800">
                                <th class="px-6 py-4.5 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.table_service_item') }}</th>
                                <th class="px-6 py-4.5 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">{{ __('messages.table_unit_price') }}</th>
                                <th class="px-6 py-4.5 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">{{ __('messages.table_quantity') }}</th>
                                <th class="px-6 py-4.5 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">{{ __('messages.table_total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                            @foreach($invoice->invoiceDetails as $item)
                                <tr class="hover:bg-purple-50/20 dark:hover:bg-purple-900/10 transition-colors">
                                    <td class="px-6 py-5 sm:py-6 flex items-center gap-3">
                                        <div class="h-10 w-10 bg-purple-50 dark:bg-purple-950/40 text-purple-650 dark:text-purple-400 rounded-xl flex items-center justify-center font-black text-sm shrink-0 shadow-inner border border-purple-100/10">
                                            {{ substr($item->layanan->nama_layanan ?? 'L', 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-sm sm:text-base font-black text-gray-955 dark:text-white block truncate">{{ $item->layanan->nama_layanan ?? 'Layanan' }}</span>
                                            @if($item->deskripsi_tambahan)
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500 block truncate mt-0.5">{{ $item->deskripsi_tambahan }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 sm:py-6 text-right text-gray-500 dark:text-gray-400 font-bold text-xs sm:text-sm">
                                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-5 sm:py-6 text-center text-gray-900 dark:text-white font-black text-xs sm:text-sm">
                                        {{ $item->kuantitas }}
                                    </td>
                                    <td class="px-6 py-5 sm:py-6 text-right text-gray-955 dark:text-white font-black text-xs sm:text-base">
                                        Rp {{ number_format($item->total_biaya, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-900 dark:bg-slate-900/80 text-white border-t border-purple-900/20">
                                <td colspan="3" class="px-6 py-5 text-right font-black uppercase tracking-widest text-gray-400 dark:text-gray-300 text-[10px] sm:text-xs">{{ __('messages.table_total_payment') }}</td>
                                <td class="px-6 py-5 text-right font-black text-sm sm:text-xl text-purple-300">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment Proof Display (Polaroid style) -->
            @if($invoice->payment_proof)
                <div class="clay-card shadow-md p-6 sm:p-8">
                    <h4 class="text-base sm:text-lg font-black text-gray-900 dark:text-white mb-6 uppercase tracking-wider">{{ __('messages.uploaded_payment_proof') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-slate-50 dark:bg-gray-900 border-2 border-dashed border-gray-200 dark:border-gray-800 group cursor-pointer" @click="window.open('{{ asset('storage/' . $invoice->payment_proof) }}', '_blank')">
                            <img src="{{ asset('storage/' . $invoice->payment_proof) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Bukti Transfer">
                        </div>
                        <div class="space-y-4">
                            <div class="p-5 bg-purple-50/50 dark:bg-purple-950/20 rounded-2xl border border-purple-100/50 dark:border-purple-900/30">
                                <p class="text-[9px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-1">{{ __('messages.upload_date') }}</p>
                                <p class="text-sm sm:text-base font-black text-gray-955 dark:text-white">{{ $invoice->payment_date->format('d M Y H:i') }} WIB</p>
                            </div>
                            @if($invoice->payment_notes)
                                <div class="p-5 bg-purple-50/50 dark:bg-purple-950/20 rounded-2xl border border-purple-100/50 dark:border-purple-900/30">
                                    <p class="text-[9px] font-black text-purple-750 dark:text-purple-450 uppercase tracking-widest mb-1">{{ __('messages.marked_for_service') }}</p>
                                    <p class="text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200 leading-relaxed">{{ $invoice->payment_notes }}</p>
                                </div>
                            @endif
                            <div class="p-4 bg-slate-100/50 dark:bg-slate-900/40 rounded-2xl text-xs text-gray-400 dark:text-gray-500 font-semibold italic border border-slate-200/10">
                                {{ __('messages.transfer_proof_audit_desc') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Previous Payment History (Lunas) -->
            @php
                $paidInvoices = \App\Models\Invoice::where('id_siswa', $invoice->id_siswa)
                    ->where('status', 'paid')
                    ->where('id', '!=', $invoice->id)
                    ->latest()
                    ->get();
            @endphp
            @if($paidInvoices->isNotEmpty())
                <div class="clay-card shadow-md p-6 sm:p-8">
                    <h4 class="text-base sm:text-lg font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider">{{ __('messages.previous_payment_history') }}</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-slate-900/30 border-b border-gray-150 dark:border-gray-850">
                                    <th class="px-4 py-3 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">No. Invoice</th>
                                    <th class="px-4 py-3 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.payment_date') }}</th>
                                    <th class="px-4 py-3 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">{{ __('messages.amount') }}</th>
                                    <th class="px-4 py-3 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">{{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($paidInvoices as $paidInvoice)
                                    <tr class="hover:bg-purple-50/20 dark:hover:bg-purple-900/10 transition-all">
                                        <td class="px-4 py-4 font-black text-gray-900 dark:text-white text-xs sm:text-sm">
                                            #{{ $paidInvoice->no_invoice }}
                                        </td>
                                        <td class="px-4 py-4 text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-bold">
                                            {{ $paidInvoice->payment_date ? $paidInvoice->payment_date->format('d M Y') : $paidInvoice->updated_at->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-4 text-right font-black text-gray-900 dark:text-white text-xs sm:text-sm">
                                            Rp {{ number_format($paidInvoice->grand_total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <a href="{{ route('student.invoices.show', $paidInvoice) }}" class="px-3.5 py-1.5 bg-slate-100 dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg font-black text-[10px] hover:bg-purple-650 hover:text-white transition active:scale-95">
                                                {{ __('messages.action_detail') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Content (Right Side - Status & Bank VA Card) -->
        <div class="space-y-8">
            <!-- Payment Status Card -->
            <div class="{{ $invoice->status === 'paid' ? 'bg-gradient-to-br from-purple-600 to-indigo-700 shadow-xl shadow-purple-900/20' : ($invoice->payment_proof ? 'bg-gradient-to-br from-amber-500 to-orange-600 shadow-xl shadow-orange-950/20' : 'bg-gradient-to-br from-rose-600 to-red-700 shadow-xl shadow-rose-900/20') }} rounded-[28px] p-6 sm:p-8 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-3.5 mb-6">
                        <div class="h-10 w-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                            <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider">{{ __('messages.invoice_status') }}</h4>
                    </div>
                    <div class="text-3xl sm:text-4xl font-black mb-1.5 uppercase tracking-tighter">
                        @if($invoice->status === 'paid')
                            {{ __('messages.paid') }}
                        @elseif($invoice->payment_proof)
                            {{ __('messages.verifying') }}
                        @else
                            {{ __('messages.unpaid') }}
                        @endif
                    </div>
                    <p class="text-white/80 font-bold text-xs sm:text-sm">{{ __('messages.due_date_limit') }}: {{ $invoice->jatuh_tempo ? $invoice->jatuh_tempo->format('d M Y') : '-' }}</p>
                </div>
                <!-- Ambient circular background graphics -->
                <div class="absolute -top-16 -right-16 w-36 h-36 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
                <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-black/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
            </div>

            <!-- Virtual Bank VA Card (E-Wallet Mockup - Purple theme) -->
            <div class="clay-card p-6 sm:p-8 shadow-md">
                <h4 class="text-sm sm:text-base font-black text-gray-900 dark:text-white mb-5">{{ __('messages.payment_method') }}</h4>
                @if($activeSchool && $activeSchool->no_rekening)
                    <div class="p-5 bg-gradient-to-br from-slate-900 to-purple-955 text-white rounded-2xl border border-slate-950 flex flex-col gap-4 shadow-lg group relative overflow-hidden">
                        <div class="flex items-center justify-between z-10">
                            <span class="px-2.5 py-1 bg-white/15 backdrop-blur-md rounded-lg text-[10px] font-black uppercase tracking-wider text-purple-200">{{ $activeSchool->bank_nama ?? 'REKENING' }}</span>
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div class="z-10 mt-1">
                            <p class="text-[8px] font-black text-purple-300 uppercase tracking-widest">{{ __('messages.target_account_number') }}</p>
                            <p class="text-xl sm:text-2xl font-black tracking-wider mt-1 text-purple-50">{{ $activeSchool->no_rekening }}</p>
                        </div>
                        <div class="pt-3 border-t border-white/10 flex justify-between items-center text-xs z-10 mt-2">
                            <span class="font-bold text-purple-300 text-[10px] uppercase">{{ __('messages.account_name') }}</span>
                            <span class="font-black text-purple-50 uppercase tracking-wide truncate max-w-[120px]">{{ $activeSchool->atas_nama }}</span>
                        </div>
                        <!-- Ambient backdrop graphic -->
                        <div class="absolute -bottom-16 -right-16 w-32 h-32 bg-purple-500/20 rounded-full blur-2xl group-hover:scale-115 transition-transform duration-700"></div>
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">{{ __('messages.va_info_unavailable') }}</p>
                @endif
                <p class="mt-6 text-xs text-gray-400 dark:text-gray-500 font-bold italic leading-relaxed">
                    {{ __('messages.transfer_instruction_desc') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Upload Proof Modal (Fixed Glassmorphic Layout) -->
    <div x-show="showUploadModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="clay-card w-full max-w-lg shadow-2xl overflow-hidden transform transition-all m-4 border border-white/40 dark:border-gray-800/40"
             @click.away="showUploadModal = false">
            
            <div class="p-6 sm:p-10">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl sm:text-2xl font-black text-gray-955 dark:text-white uppercase tracking-tight font-display">{{ __('messages.send_payment_proof') }}</h3>
                    <button @click="showUploadModal = false" class="text-gray-400 hover:text-rose-500 transition-colors bg-slate-100 dark:bg-gray-700 p-2 rounded-full shadow-inner active:scale-90">
                        <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- VA Account Copier in Modal -->
                @if($activeSchool && $activeSchool->no_rekening)
                    <div class="mb-6 p-4 bg-purple-50/60 dark:bg-purple-950/20 rounded-2xl border border-purple-150 dark:border-purple-900/30 flex flex-col gap-2.5">
                        <div class="flex justify-between items-center">
                            <span class="px-2.5 py-0.5 bg-purple-600 text-white rounded-md text-[8px] font-black uppercase tracking-wider">{{ $activeSchool->bank_nama ?? 'TRANSFER' }}</span>
                            <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500">{{ __('messages.target_account_number') }}</span>
                        </div>
                        <div class="flex justify-between items-center gap-3">
                            <p class="text-lg font-black text-purple-600 dark:text-purple-400 tracking-wider truncate" id="modal-bank-acc">{{ $activeSchool->no_rekening }}</p>
                            <button type="button" @click="navigator.clipboard.writeText('{{ $activeSchool->no_rekening }}'); alert('{{ __('messages.copied_rekening') }}')" class="h-8 px-3.5 bg-white dark:bg-gray-800 text-[10px] font-black text-gray-700 dark:text-gray-300 rounded-lg border border-slate-200 dark:border-gray-750 hover:bg-purple-600 hover:text-white transition active:scale-95 flex items-center justify-center gap-1 shrink-0 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                {{ __('messages.copy') }}
                            </button>
                        </div>
                        <div class="pt-2 border-t border-purple-100/50 dark:border-purple-900/10 flex justify-between items-center text-[10px] font-bold text-gray-500 dark:text-purple-300 uppercase">
                            <span>{{ __('messages.account_name') }}</span>
                            <span class="font-black text-gray-900 dark:text-white">{{ $activeSchool->atas_nama }}</span>
                        </div>
                    </div>
                @endif

                <form action="{{ route('student.invoices.upload-payment-proof', $invoice) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- STEP 1: CHOOSE TRANSFER PROOF IMAGE -->
                    <div x-show="step === 1" class="space-y-6" x-transition>
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">{{ __('messages.select_transfer_proof') }}</label>
                            <div class="relative group aspect-[4/3] rounded-3xl overflow-hidden bg-slate-50 dark:bg-gray-900 border-2 border-dashed border-slate-200 dark:border-gray-700 flex flex-col items-center justify-center transition-all hover:border-purple-500 hover:bg-purple-50/20 shadow-inner">
                                <template x-if="!preview">
                                    <div class="text-center p-6">
                                        <div class="h-16 w-16 bg-purple-100/60 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center text-purple-650 dark:text-purple-400 mx-auto mb-4 group-hover:scale-105 transition-all border border-purple-200/10">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest group-hover:text-purple-600 transition-colors">{{ __('messages.choose_file_drag') }}</p>
                                    </div>
                                </template>
                                <template x-if="preview">
                                    <img :src="preview" class="w-full h-full object-cover">
                                </template>
                                <input type="file" name="payment_proof" :required="step === 1" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer"
                                       @change="const file = $event.target.files[0]; if(file) { preview = URL.createObjectURL(file); }">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" @click="showUploadModal = false" class="flex-1 px-5 py-3.5 bg-slate-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-2xl font-black uppercase text-xs tracking-wider hover:bg-slate-200 transition">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="button" 
                                    @click="if (preview) { step = 2 } else { alert('{{ __('messages.select_file_warning') }}') }" 
                                     class="flex-1 px-5 py-3.5 bg-purple-650 text-white rounded-2xl font-black uppercase text-xs tracking-wider shadow-lg shadow-purple-600/20 hover:bg-purple-700 transition active:scale-95">
                                {{ __('messages.next') }}
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: CHECK ITEMS BEING PAID -->
                    <div x-show="step === 2" class="space-y-6" x-transition style="display: none;">
                        <div class="p-4 bg-amber-50 dark:bg-amber-950/20 rounded-xl border border-amber-100 dark:border-amber-900/20 flex gap-3 text-xs text-amber-800 dark:text-amber-300 font-semibold mb-4 leading-relaxed">
                            <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span>{{ __('messages.mark_items_paid_instruction') }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-2 border-b border-slate-100 dark:border-gray-700">
                            <span class="text-[9px] font-black uppercase tracking-wider text-gray-400 dark:text-gray-500">{{ __('messages.service_invoice_list') }}</span>
                            <button type="button" 
                                    @click="selectAll = !selectAll; checkedItems = selectAll ? [@foreach($invoice->invoiceDetails as $item) '{{ $item->id }}', @endforeach] : []" 
                                    class="text-[9px] font-black text-purple-650 dark:text-purple-400 uppercase tracking-widest hover:underline">
                                <span x-text="selectAll ? '{{ __('messages.clear_all_checks') }}' : '{{ __('messages.check_all_items') }}'"></span>
                            </button>
                        </div>

                        <div class="max-h-56 overflow-y-auto space-y-2.5 pr-1.5 scrollbar-hide">
                            @foreach($invoice->invoiceDetails as $item)
                                <label class="flex items-start gap-3.5 p-4 bg-slate-50 dark:bg-gray-900/50 rounded-2xl border border-slate-100 dark:border-gray-800 hover:border-purple-500 dark:hover:border-purple-500 cursor-pointer transition-all">
                                    <input type="checkbox" name="checked_items[]" value="{{ $item->id }}" x-model="checkedItems"
                                           class="mt-1.5 h-4.5 w-4.5 rounded-md border-gray-300 dark:border-gray-750 text-purple-650 focus:ring-purple-500 dark:focus:ring-purple-650 cursor-pointer">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs sm:text-sm font-black text-gray-900 dark:text-white truncate">
                                            {{ $item->layanan->nama_layanan ?? 'Layanan' }}
                                        </p>
                                        @if($item->deskripsi_tambahan)
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">{{ $item->deskripsi_tambahan }}</p>
                                        @endif
                                        <p class="text-xs font-black text-purple-650 dark:text-purple-400 mt-1">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex gap-4 pt-2">
                            <button type="button" @click="step = 1" class="flex-1 px-5 py-3.5 bg-slate-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-2xl font-black uppercase text-xs tracking-wider hover:bg-slate-200 transition">
                                {{ __('messages.back') }}
                            </button>
                            <button type="submit" 
                                    :disabled="checkedItems.length === 0"
                                    class="flex-1 px-5 py-3.5 bg-purple-650 text-white rounded-2xl font-black uppercase text-xs tracking-wider shadow-lg shadow-purple-600/20 hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition active:scale-95">
                                {{ __('messages.send_proof') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
