@extends('layouts.app')

@section('content')
<div class="py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-sky-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-sky-950/20 min-h-screen">
    
    <!-- Title Header -->
    <div class="mb-8 sm:mb-10">
        <span class="px-3 py-1 bg-sky-100 text-sky-700 dark:bg-sky-950/40 dark:text-sky-400 rounded-full text-[9px] font-black uppercase tracking-widest border border-sky-200/20 shadow-sm">
            {{ __('messages.financial_history') }}
        </span>
        <h2 class="text-2xl sm:text-4xl font-black text-gray-955 dark:text-white tracking-tight mt-2 leading-tight">{{ __('messages.tuition_payment') }}</h2>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-bold mt-1">{{ __('messages.tuition_payment_desc') }}</p>
    </div>

    <!-- Invoice Cards Feed -->
    <div class="space-y-6">
        @forelse($invoices as $invoice)
            <div class="clay-card overflow-hidden hover:shadow-xl hover:scale-[1.005] hover:border-sky-500/25 duration-300">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        
                        <div class="flex items-start gap-5">
                            <div class="h-14 w-14 bg-sky-50 dark:bg-sky-950/40 rounded-2xl flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0 shadow-inner border border-sky-100/20">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-2.5 mb-1.5">
                                    <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white tracking-tight font-display">Invoice #{{ $invoice->no_invoice }}</h3>
                                    <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest {{ $invoice->status === 'paid' ? 'bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-400 border border-sky-200/20 shadow-sm' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 shadow-sm' }}">
                                        {{ $invoice->status === 'paid' ? __('messages.paid') : __('messages.unpaid') }}
                                    </span>
                                    @if($invoice->is_auto_generated)
                                        <span class="px-2.5 py-1 rounded-full text-[8px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 shadow-sm" title="Invoice Otomatis">
                                            ⚡ Auto
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 font-bold">{{ __('messages.invoice_published_on') }} {{ $invoice->tanggal_invoice ? $invoice->tanggal_invoice->format('d M Y') : '-' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 items-center">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">{{ __('messages.invoice_amount') }}</p>
                                <p class="text-base sm:text-xl font-black text-gray-955 dark:text-white tracking-tight">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</p>
                            </div>
                            <div class="hidden lg:block">
                                <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">{{ __('messages.due_date') }}</p>
                                <p class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300">{{ $invoice->jatuh_tempo ? $invoice->jatuh_tempo->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="col-span-2 lg:col-span-1 flex gap-2 w-full justify-end">
                                <a href="{{ route('student.invoices.show', $invoice) }}" class="flex-1 lg:flex-none px-5 py-3 bg-slate-900 dark:bg-slate-800 text-white rounded-xl font-black text-xs text-center hover:bg-sky-600 transition duration-200 active:scale-95">
                                    {{ __('messages.action_detail') }}
                                </a>
                                <a href="{{ route('student.invoices.pdf', $invoice) }}" class="flex-1 lg:flex-none px-4 py-3 bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 rounded-xl hover:bg-sky-600 hover:text-white transition duration-200 active:scale-95 flex items-center justify-center gap-1.5 shadow-inner" title="Download PDF Invoice">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span class="lg:hidden text-[10px] font-black uppercase tracking-wider">PDF</span>
                                </a>
                                <a href="{{ route('student.invoices.kwitansi', $invoice) }}" class="flex-1 lg:flex-none px-4 py-3 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-xl hover:bg-amber-600 hover:text-white transition duration-200 active:scale-95 flex items-center justify-center gap-1.5 shadow-inner" title="Download Kwitansi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="lg:hidden text-[10px] font-black uppercase tracking-wider">Kwitansi</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <!-- Gorgeous Premium Empty State -->
            <div class="clay-card rounded-[32px] border border-white/40 dark:border-gray-800/40 p-10 sm:p-20 text-center shadow-lg">
                <div class="h-20 w-20 sm:h-24 sm:w-24 bg-gradient-to-br from-sky-50 to-sky-50 dark:from-sky-950/30 dark:to-sky-950/20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-sky-100/20">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-sky-600 dark:text-sky-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg sm:text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">{{ __('messages.no_invoices') }}</h3>
                <p class="text-xs sm:text-sm text-gray-400 dark:text-gray-500 max-w-sm mx-auto font-semibold leading-relaxed">{{ __('messages.no_invoices_desc') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
