<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-adsense-account" content="ca-pub-1189692810742391">
    
    @php
        $globalSchoolProfile = \App\Models\ProfilSekolah::first();
        $faviconUrl = ($globalSchoolProfile && $globalSchoolProfile->logo_path) 
            ? Storage::url($globalSchoolProfile->logo_path) 
            : asset('storage/logos/sayyidah-school-logo.png');
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}">

    <title>Pendaftaran Berhasil - Says Homeschooling</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --bg-base: #0f172a;
            --primary-gradient: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
        }

        body {
            background: radial-gradient(circle at top right, #1e1b4b, #0f172a);
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="min-h-screen text-slate-100 flex items-center justify-center p-4">

    <!-- Success Receipt Card -->
    <div class="max-w-md w-full glass-card rounded-[32px] p-8 sm:p-10 text-center relative overflow-hidden">
        <!-- Floating Light Effect -->
        <div class="absolute -top-16 -left-16 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-16 -right-16 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>

        <!-- Success Checkmark Illustration -->
        <div class="relative w-20 h-20 mx-auto mb-6 flex items-center justify-center">
            <div class="absolute inset-0 rounded-full bg-emerald-500/10 animate-ping duration-1000"></div>
            <div class="absolute inset-2 rounded-full bg-emerald-500/20 animate-pulse"></div>
            
            <div class="relative w-14 h-14 bg-gradient-to-tr from-emerald-500 to-teal-500 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/25">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black font-display tracking-tight bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent mb-2">
            Pendaftaran Dikirim!
        </h1>
        <p class="text-sm text-slate-400 font-medium mb-6">
            Formulir pendaftaran calon siswa berhasil disubmit ke sistem pendaftaran online.
        </p>

        <!-- Registration Number Container -->
        <div class="bg-slate-900/40 border border-slate-700/30 rounded-2xl p-5 mb-6">
            <div class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1.5">Nomor Registrasi Anda</div>
            <div class="flex items-center justify-center gap-3">
                <span class="text-xl sm:text-2xl font-black tracking-wider text-white select-all font-display">
                    {{ $registration->nomor_pendaftaran }}
                </span>
                <button onclick="copyToClipboard('{{ $registration->nomor_pendaftaran }}')" 
                        class="p-2 bg-slate-800 hover:bg-slate-700 active:scale-95 text-slate-300 hover:text-white rounded-xl transition border border-slate-700/50 flex items-center justify-center" 
                        title="Salin Nomor Pendaftaran">
                    <i id="copy-icon" class="fa-regular fa-copy text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Info Pengajuan Keringanan (Jika Ada) -->
        @if ($registration->mengajukan_keringanan)
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-2xl text-left text-xs text-amber-200 mb-6">
                📌 <strong>Pengajuan Keringanan (Potongan {{ $registration->persentase_keringanan_diajukan }}%):</strong><br>
                Persentase potongan final akan diinformasikan setelah verifikasi data oleh tim seleksi sekolah.
            </div>
        @endif

        <!-- Next Steps list -->
        <div class="text-left space-y-4 mb-8">
            <h4 class="text-xs font-black uppercase text-purple-400 tracking-wider">Langkah Selanjutnya:</h4>
            
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-[11px] font-bold text-purple-300 flex-shrink-0 mt-0.5">
                    1
                </div>
                <div class="text-xs text-slate-300 leading-relaxed font-medium">
                    <strong class="text-white block">Simpan Nomor Registrasi</strong>
                    Gunakan nomor di atas untuk memantau status persetujuan dari tim admin kami.
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-[11px] font-bold text-purple-300 flex-shrink-0 mt-0.5">
                    2
                </div>
                <div class="text-xs text-slate-300 leading-relaxed font-medium">
                    <strong class="text-white block">Pantau Status / Lacak</strong>
                    Gunakan tautan pelacakan status secara berkala dengan nomor registrasi Anda.
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-[11px] font-bold text-purple-300 flex-shrink-0 mt-0.5">
                    3
                </div>
                <div class="text-xs text-slate-300 leading-relaxed font-medium">
                    <strong class="text-white block">Aktivasi Akun</strong>
                    Jika status pendaftaran disetujui, akun Anda dapat diaktifkan untuk masuk ke Portal Pembelajaran Siswa.
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-3">
            <a href="{{ route('compro.index') }}" 
               class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 active:scale-[0.98] text-white text-sm font-bold uppercase tracking-wider rounded-2xl transition shadow-lg shadow-purple-950/20">
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- JS Clipboard utility -->
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                const icon = document.getElementById('copy-icon');
                icon.className = 'fa-solid fa-check text-emerald-400';
                
                setTimeout(function() {
                    icon.className = 'fa-regular fa-copy text-sm';
                }, 2000);
            }, function(err) {
                console.error('Gagal menyalin teks: ', err);
            });
        }
    </script>
</body>
</html>
