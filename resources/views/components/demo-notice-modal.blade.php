{{-- Live Demo Notice Modal --}}
<div id="sis-demo-notice-modal"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/45 backdrop-blur-[2px] p-4"
     role="dialog" aria-modal="true" aria-labelledby="demo-modal-title">
    <div class="w-full max-w-md rounded-2xl bg-white border border-slate-200 shadow-xl p-6 space-y-4">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-700 border border-sky-200/80 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-info text-sm"></i>
                </span>
                <div>
                    <h2 id="demo-modal-title" class="text-sm font-bold text-slate-900">Informasi Live Demo</h2>
                    <p class="text-[11px] text-slate-500">SIS SEKOLAH</p>
                </div>
            </div>
            <button type="button" onclick="closeSisDemoNotice()" aria-label="Tutup"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <div class="text-xs sm:text-[13px] text-slate-600 leading-relaxed space-y-2.5 border-t border-b border-slate-100 py-3.5">
            <p>
                Sistem ini merupakan <strong class="text-slate-900">demonstrasi langsung</strong>. Seluruh fitur dapat dicoba secara bebas dan data yang ditampilkan bersifat <strong class="text-slate-900">dummy</strong>.
            </p>
            <p class="text-slate-500">
                Untuk menjaga performa server tetap lancar, data yang Anda input dapat dihapus atau direset secara berkala.
            </p>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="https://wa.me/6285155238654?text=Halo%2C%20saya%20ingin%20konsultasi%20mengenai%20sistem%20SIS%20SEKOLAH."
               target="_blank" rel="noopener noreferrer"
               class="px-3.5 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Konsultasi
            </a>
            <button type="button" onclick="closeSisDemoNotice()"
                    class="px-4 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold transition cursor-pointer">
                Mengerti &amp; Lanjutkan
            </button>
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
