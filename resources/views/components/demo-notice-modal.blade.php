{{-- Live Demo Notice Modal --}}
<div id="sis-demo-notice-modal"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
     role="dialog" aria-modal="true" aria-labelledby="demo-modal-title">
    <div class="relative w-full max-w-md rounded-3xl bg-white border border-slate-200 shadow-2xl overflow-hidden animate-[fadeIn_0.2s_ease-out]">
        {{-- Top Accent Header --}}
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-teal-900 px-6 py-5 text-white flex items-start justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-500/20 border border-teal-400/30 flex items-center justify-center text-teal-300 shrink-0">
                    <i class="fa-solid fa-flask text-lg"></i>
                </div>
                <div>
                    <span class="inline-block text-[10px] font-extrabold uppercase tracking-widest text-teal-300">Live Demo Environment</span>
                    <h2 id="demo-modal-title" class="text-base font-extrabold tracking-tight text-white">Pemberitahuan Sistem Demo</h2>
                </div>
            </div>
            <button type="button" onclick="closeSisDemoNotice()" aria-label="Tutup"
                    class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white flex items-center justify-center transition shrink-0">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 space-y-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
            <p class="text-slate-700 font-medium">
                Selamat datang di <strong class="text-slate-900">SIS SEKOLAH</strong>. Aplikasi ini disediakan khusus untuk keperluan <strong class="text-teal-700">demonstrasi langsung (live demo)</strong>:
            </p>

            <ul class="space-y-2.5">
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-circle-check text-[11px]"></i>
                    </span>
                    <span>Seluruh fitur sistem dapat dicoba secara bebas oleh pengunjung.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-database text-[11px]"></i>
                    </span>
                    <span>Seluruh data yang tampil bersifat <strong>dummy / simulasi</strong>, bukan data sekolah sebenarnya.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-rotate text-[11px]"></i>
                    </span>
                    <span>Data yang Anda input dapat <strong>dihapus atau direset secara berkala</strong> untuk menjaga server tetap lancar.</span>
                </li>
            </ul>

            <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <button type="button" onclick="closeSisDemoNotice()"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-extrabold shadow-md shadow-teal-600/20 transition cursor-pointer">
                    <i class="fa-solid fa-check"></i>
                    <span>Mengerti &amp; Lanjutkan</span>
                </button>
                <a href="https://wa.me/6285155238654?text=Halo%2C%20saya%20ingin%20konsultasi%20mengenai%20sistem%20SIS%20SEKOLAH."
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 text-xs font-bold transition">
                    <i class="fa-brands fa-whatsapp text-emerald-600 text-sm"></i>
                    <span>Konsultasi</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const storageKey = 'sis_demo_notice_ack_v1';
        const modal = document.getElementById('sis-demo-notice-modal');
        if (!modal) return;

        window.closeSisDemoNotice = function () {
            try { sessionStorage.setItem(storageKey, '1'); } catch (e) {}
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

        try {
            if (!sessionStorage.getItem(storageKey)) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        } catch (e) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    })();
</script>
