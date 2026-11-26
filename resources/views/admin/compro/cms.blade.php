<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ ($schoolProfile && $schoolProfile->logo_path) ? Storage::url($schoolProfile->logo_path) : asset('storage/logos/sayyidah-school-logo.png') }}">
    
    <title>SAYS Homeschooling CMS - Panel Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        (() => {
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initialDark = localStorage.theme === 'dark' || (!('theme' in localStorage) && prefersDark);
            document.documentElement.classList.toggle('dark', initialDark);
        })();
    </script>

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-200 antialiased"
      x-data="{ 
          tab: 'pengaturan',
          
          // Modals state
          addPillarModal: false,
          editPillarModal: false,
          editPillarData: { id: '', icon: '', judul: '', deskripsi: '', urutan: 0 },
          
          addProgramModal: false,
          editProgramModal: false,
          editProgramData: { id: '', jenjang: 'SD', judul: '', deskripsi: '', catatan_kaki: '', urutan: 0 },

          editLoadModal: false,
          editLoadData: { id: '', nama: '', persentase: 0, deskripsi: '', warna: 'purple', icon: '📚' },

          addActivityModal: false,
          editActivityModal: false,
          editActivityData: { id: '', curriculum_load_id: '', icon: '▪️', nama: '', deskripsi: '', urutan: 0 },

          addInvestmentModal: false,
          editInvestmentModal: false,
          editInvestmentData: { id: '', nama: '', harga: 0, keterangan: '', urutan: 0 },

          addHighlightModal: false,
          editHighlightModal: false,
          editHighlightData: { id: '', teks: '', urutan: 0 },

          addAlbumModal: false,
          editAlbumModal: false,
          editAlbumData: { id: '', judul: '', deskripsi: '', tanggal: '', urutan: 0 },

          managePhotosModal: false,
          managePhotosData: { id: '', judul: '', photos: [] },
          selectedPhotosPreviews: [],
          
          addPromoModal: false,
          editPromoModal: false,
          editPromoData: { id: '', label: '', deskripsi: '', aktif: false, discounts: {} }
      }">

    <div class="h-full flex flex-col md:flex-row overflow-hidden">
        
        <!-- SIDEBAR NAVIGATION -->
        <aside class="w-full md:w-80 bg-slate-900 text-white flex flex-col justify-between shrink-0 border-r border-slate-800">
            <div class="p-6">
                <!-- Branding -->
                <div class="flex items-center gap-3 mb-8">
                    <img src="{{ ($schoolProfile && $schoolProfile->logo_path) ? Storage::url($schoolProfile->logo_path) : asset('storage/logos/sayyidah-school-logo.png') }}" 
                         alt="Sayyidah School" 
                         class="h-10 w-auto object-contain bg-white/10 p-1.5 rounded-xl">
                    <div>
                        <h1 class="font-black text-sm tracking-tight text-white leading-none">SAYS Homeschooling</h1>
                        <span class="text-[10px] text-purple-400 font-bold uppercase tracking-wider block mt-1">CMS Control Panel</span>
                    </div>
                </div>

                <!-- Navigation List -->
                <nav class="space-y-1">
                    <button @click="tab = 'pengaturan'" :class="tab === 'pengaturan' ? 'bg-purple-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition">
                        <span>🌐</span> Pengaturan Umum
                    </button>
                    <button @click="tab = 'pillars'" :class="tab === 'pillars' ? 'bg-purple-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition">
                        <span>📌</span> Pilar Sekolah
                    </button>
                    <button @click="tab = 'programs'" :class="tab === 'programs' ? 'bg-purple-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition">
                        <span>🎓</span> Program Pendidikan
                    </button>
                    <button @click="tab = 'curriculum'" :class="tab === 'curriculum' ? 'bg-purple-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition">
                        <span>📐</span> Kurikulum & Kegiatan
                    </button>
                    <button @click="tab = 'investasi'" :class="tab === 'investasi' ? 'bg-purple-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition">
                        <span>💳</span> Rincian Investasi/Biaya
                    </button>
                    <button @click="tab = 'highlights'" :class="tab === 'highlights' ? 'bg-purple-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition">
                        <span>📝</span> Highlights Metode
                    </button>
                    <button @click="tab = 'gallery'" :class="tab === 'gallery' ? 'bg-purple-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition">
                        <span>🖼️</span> Galeri Kegiatan
                    </button>
                </nav>
            </div>

            <!-- Footer Sidebar info -->
            <div class="p-6 border-t border-slate-800 bg-slate-950/40 text-[10px] text-slate-500 font-semibold space-y-3">
                <div>
                    <span class="block">Masuk Sebagai Admin</span>
                    <span class="text-slate-350 block mt-0.5">{{ Auth::user()->name ?? 'Administrator' }}</span>
                </div>
                <a href="{{ route('dashboard') }}" class="block w-full py-2 bg-slate-800 hover:bg-slate-750 text-white rounded-xl text-center text-[10px] font-bold uppercase transition">
                    Kembali Ke Dashboard
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT CONTAINER -->
        <main class="flex-1 flex flex-col overflow-hidden bg-slate-50 dark:bg-slate-950">
            
            <!-- TOP NOTIFICATION BAR & DARK MODE -->
            <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-8 py-4 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <h2 class="text-lg font-black tracking-tight dark:text-white uppercase" x-text="'Kelola: ' + tab.replace('_', ' ')"></h2>
                    
                    @if (session('success'))
                        <div class="py-1 px-3 bg-green-500 text-white font-bold text-xs rounded-xl animate-bounce">
                            ✓ {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="py-2 px-4 bg-rose-500 text-white font-bold text-xs rounded-xl space-y-1">
                            <div class="flex items-center gap-1">
                                <span>⚠️ Terjadi kesalahan:</span>
                            </div>
                            <ul class="list-disc list-inside font-medium pl-1 text-[11px]">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button @click="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light'" class="p-2 bg-slate-100 dark:bg-slate-800 rounded-xl hover:scale-105 transition text-xs font-bold">
                        🌓 Dark/Light Mode
                    </button>
                </div>
            </header>

            <!-- TABS ROUTER WRAPPER -->
            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                
                <!-- TAB 1: PENGATURAN UMUM -->
                <div x-show="tab === 'pengaturan'" class="space-y-6">
                    <form action="{{ route('admin.compro-cms.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Header & Logo Section -->
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-sm font-black uppercase text-purple-650 tracking-wider">Logo Website & Navigasi</h3>
                            <div class="grid md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-500 block uppercase">Logo Sekolah</label>
                                    <div class="mb-2">
                                        <img src="{{ ($schoolProfile && $schoolProfile->logo_path) ? Storage::url($schoolProfile->logo_path) : asset('storage/logos/sayyidah-school-logo.png') }}" 
                                             alt="Logo Sekolah" 
                                             class="h-12 w-auto object-contain bg-slate-100 dark:bg-slate-800 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700">
                                    </div>
                                    <input type="file" name="logo" class="w-full text-xs">
                                    <p class="text-[10px] text-slate-400">Pilih berkas PNG/JPEG transparan untuk mengganti logo atas.</p>
                                </div>
                                <div class="space-y-2 col-span-2 grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 block uppercase">Link Nav 1</label>
                                        <input type="text" name="nav_about_text" value="{{ $settings['nav_about_text'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 block uppercase">Link Nav 2</label>
                                        <input type="text" name="nav_program_text" value="{{ $settings['nav_program_text'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 block uppercase">Link Nav 3</label>
                                        <input type="text" name="nav_method_text" value="{{ $settings['nav_method_text'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 block uppercase">Link Nav 4</label>
                                        <input type="text" name="nav_contact_text" value="{{ $settings['nav_contact_text'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Banner Section -->
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-sm font-black uppercase text-purple-650 tracking-wider">Konten Hero Landing Page</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Badge Atas</label>
                                    <input type="text" name="hero_badge" value="{{ $settings['hero_badge'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Judul Utama (Title)</label>
                                        <textarea name="hero_title" rows="3" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">{{ $settings['hero_title'] ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Sub-judul (Subtitle)</label>
                                        <textarea name="hero_subtitle" rows="3" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Tombol CTA Siswa</label>
                                        <input type="text" name="hero_cta_student_text" value="{{ $settings['hero_cta_student_text'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Tombol CTA Guru</label>
                                        <input type="text" name="hero_cta_teacher_text" value="{{ $settings['hero_cta_teacher_text'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Sidebar Stats/Info Widget -->
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-sm font-black uppercase text-purple-650 tracking-wider">Widget Samping Gambar Hero</h3>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Judul Widget</label>
                                    <input type="text" name="hero_image_title" value="{{ $settings['hero_image_title'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Status Badge</label>
                                    <input type="text" name="hero_image_status" value="{{ $settings['hero_image_status'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Kemajuan Label & %</label>
                                    <div class="grid grid-cols-2 gap-1.5">
                                        <input type="text" name="hero_image_progress_label" value="{{ $settings['hero_image_progress_label'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700" placeholder="Label">
                                        <input type="text" name="hero_image_progress_percent" value="{{ $settings['hero_image_progress_percent'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700" placeholder="Percent">
                                    </div>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4 pt-2">
                                <div class="grid grid-cols-2 gap-3 border p-3.5 rounded-xl dark:border-slate-800">
                                    <div class="col-span-2 text-[10px] font-black uppercase text-slate-400">Statistik Kiri</div>
                                    <div>
                                        <label class="text-[9px] text-slate-400 uppercase font-black">Label</label>
                                        <input type="text" name="hero_image_stat1_label" value="{{ $settings['hero_image_stat1_label'] ?? '' }}" class="border rounded-xl p-1.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-[9px] text-slate-400 uppercase font-black">Nilai</label>
                                        <input type="text" name="hero_image_stat1_val" value="{{ $settings['hero_image_stat1_val'] ?? '' }}" class="border rounded-xl p-1.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 border p-3.5 rounded-xl dark:border-slate-800">
                                    <div class="col-span-2 text-[10px] font-black uppercase text-slate-400">Statistik Kanan</div>
                                    <div>
                                        <label class="text-[9px] text-slate-400 uppercase font-black">Label</label>
                                        <input type="text" name="hero_image_stat2_label" value="{{ $settings['hero_image_stat2_label'] ?? '' }}" class="border rounded-xl p-1.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-[9px] text-slate-400 uppercase font-black">Nilai</label>
                                        <input type="text" name="hero_image_stat2_val" value="{{ $settings['hero_image_stat2_val'] ?? '' }}" class="border rounded-xl p-1.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                </div>
                            </div>
                            <div class="border p-3.5 rounded-xl grid grid-cols-3 gap-3 dark:border-slate-800">
                                <div class="col-span-3 text-[10px] font-black uppercase text-slate-400">Agenda / Pengumuman</div>
                                <div>
                                    <label class="text-[9px] text-slate-400 uppercase font-black">Kategori/Label</label>
                                    <input type="text" name="hero_image_agenda_label" value="{{ $settings['hero_image_agenda_label'] ?? '' }}" class="border rounded-xl p-1.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                </div>
                                <div>
                                    <label class="text-[9px] text-slate-400 uppercase font-black">Agenda Utama</label>
                                    <input type="text" name="hero_image_agenda_title" value="{{ $settings['hero_image_agenda_title'] ?? '' }}" class="border rounded-xl p-1.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                </div>
                                <div>
                                    <label class="text-[9px] text-slate-400 uppercase font-black">Keterangan Singkat</label>
                                    <input type="text" name="hero_image_agenda_desc" value="{{ $settings['hero_image_agenda_desc'] ?? '' }}" class="border rounded-xl p-1.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                </div>
                            </div>
                        </div>

                        <!-- Tentang & Visi Misi -->
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-sm font-black uppercase text-purple-650 tracking-wider">Section Profil & Visi Misi</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Judul Profil (Tentang Kami)</label>
                                    <input type="text" name="tentang_title" value="{{ $settings['tentang_title'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Deskripsi Profil Paragraf 1</label>
                                        <textarea name="tentang_desc_1" rows="4" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 leading-relaxed font-medium">{{ $settings['tentang_desc_1'] ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Deskripsi Profil Paragraf 2</label>
                                        <textarea name="tentang_desc_2" rows="4" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 leading-relaxed">{{ $settings['tentang_desc_2'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4 pt-2">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Visi Sekolah</label>
                                        <textarea name="visi" rows="5" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 italic font-semibold">{{ $settings['visi'] ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Misi Sekolah (Poin-poin)</label>
                                        <textarea name="misi" rows="5" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium leading-relaxed">{{ $settings['misi'] ?? '' }}</textarea>
                                        <p class="text-[9px] text-slate-400">Pisahkan setiap poin dengan baris baru.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pendaftaran / Timeline Section -->
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-sm font-black uppercase text-purple-650 tracking-wider">Alur & Berkas Pendaftaran</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Judul Section Pendaftaran</label>
                                    <input type="text" name="pendaftaran_title" value="{{ $settings['pendaftaran_title'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Keterangan Singkat</label>
                                    <input type="text" name="pendaftaran_desc" value="{{ $settings['pendaftaran_desc'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                </div>
                            </div>
                            <div class="grid md:grid-cols-3 gap-4 border-t pt-4 dark:border-slate-800">
                                <div class="space-y-2">
                                    <span class="text-[10px] font-black text-slate-400 block uppercase">Langkah 1</span>
                                    <input type="text" name="pendaftaran_step1" value="{{ $settings['pendaftaran_step1'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold" placeholder="Judul">
                                    <textarea name="pendaftaran_step1_desc" rows="2" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium" placeholder="Deskripsi"></textarea>
                                </div>
                                <div class="space-y-2">
                                    <span class="text-[10px] font-black text-slate-400 block uppercase">Langkah 2</span>
                                    <input type="text" name="pendaftaran_step2" value="{{ $settings['pendaftaran_step2'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold" placeholder="Judul">
                                    <textarea name="pendaftaran_step2_desc" rows="2" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium" placeholder="Deskripsi"></textarea>
                                </div>
                                <div class="space-y-2">
                                    <span class="text-[10px] font-black text-slate-400 block uppercase">Langkah 3</span>
                                    <input type="text" name="pendaftaran_step3" value="{{ $settings['pendaftaran_step3'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold" placeholder="Judul">
                                    <textarea name="pendaftaran_step3_desc" rows="2" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium" placeholder="Deskripsi"></textarea>
                                </div>
                            </div>
                            <div class="border-t pt-4 dark:border-slate-800 space-y-2">
                                <label class="text-xs font-bold text-slate-500 block uppercase">Formulir Pendaftaran (PDF)</label>
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                                    <div class="text-[10px] text-slate-400 flex-1">
                                        Berkas saat ini: <br>
                                        <span class="font-mono text-purple-600 dark:text-purple-400 font-bold truncate block">{{ $settings['pdf_formulir_path'] ?? 'Belum ada berkas formulir PDF yang diupload' }}</span>
                                    </div>
                                    <input type="file" name="registration_form_pdf" class="text-xs cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <!-- Whatsapp CTA & Contact Details -->
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-sm font-black uppercase text-purple-650 tracking-wider">Tombol Whatsapp & Kontak Kantor</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Nomor Whatsapp Admin (Format: 62xxxx)</label>
                                        <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Pesan Whatsapp Prefilled</label>
                                        <textarea name="whatsapp_text" rows="3" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium">{{ $settings['whatsapp_text'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Alamat Kantor</label>
                                        <input type="text" name="footer_office_address" value="{{ $settings['footer_office_address'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Email Kontak</label>
                                        <input type="text" name="footer_office_email" value="{{ $settings['footer_office_email'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Telepon / Whatsapp Utama</label>
                                        <input type="text" name="footer_office_phone" value="{{ $settings['footer_office_phone'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Branding & Copyright -->
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-sm font-black uppercase text-purple-650 tracking-wider">Pengaturan Bagian Footer</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Judul Footer (Brand Name)</label>
                                        <input type="text" name="footer_title" value="{{ $settings['footer_title'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Deskripsi Singkat Footer</label>
                                        <textarea name="footer_desc" rows="3" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium">{{ $settings['footer_desc'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Judul Kolom Portal Link</label>
                                        <input type="text" name="footer_portal_title" value="{{ $settings['footer_portal_title'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <label class="text-[9px] text-slate-400 uppercase font-black">Link Siswa</label>
                                            <input type="text" name="footer_link_student_text" value="{{ $settings['footer_link_student_text'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                        </div>
                                        <div>
                                            <label class="text-[9px] text-slate-400 uppercase font-black">Link Guru</label>
                                            <input type="text" name="footer_link_teacher_text" value="{{ $settings['footer_link_teacher_text'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                        </div>
                                        <div>
                                            <label class="text-[9px] text-slate-400 uppercase font-black">Link Admin</label>
                                            <input type="text" name="footer_link_admin_text" value="{{ $settings['footer_link_admin_text'] ?? '' }}" class="border rounded-xl p-2 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Copyright Suffix (Info Tambahan)</label>
                                        <input type="text" name="footer_copyright_suffix" value="{{ $settings['footer_copyright_suffix'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Titles of section for editing -->
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-sm font-black uppercase text-purple-650 tracking-wider">Judul Section Tambahan</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Judul Section Program</label>
                                        <input type="text" name="program_title" value="{{ $settings['program_title'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Deskripsi Section Program</label>
                                        <input type="text" name="program_desc" value="{{ $settings['program_desc'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Judul Section Metode</label>
                                        <input type="text" name="metode_title" value="{{ $settings['metode_title'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Judul Section Investasi/Biaya</label>
                                        <input type="text" name="biaya_title" value="{{ $settings['biaya_title'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Deskripsi Section Investasi/Biaya</label>
                                        <input type="text" name="biaya_desc" value="{{ $settings['biaya_desc'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block uppercase mb-1">Deskripsi Section Metode</label>
                                        <input type="text" name="metode_desc" value="{{ $settings['metode_desc'] ?? '' }}" class="border rounded-xl p-2.5 text-xs w-full text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sticky Submit Bar -->
                        <div class="sticky bottom-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur border border-slate-200 dark:border-slate-800 p-4 rounded-2xl flex items-center justify-end z-20">
                            <button type="submit" class="px-8 py-3 bg-purple-650 hover:bg-purple-700 text-white font-black text-sm uppercase tracking-wider rounded-xl transition shadow shadow-purple-500/25">
                                Simpan Semua Pengaturan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: PILAR SEKOLAH -->
                <div x-show="tab === 'pillars'" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-black uppercase tracking-wider dark:text-white">Daftar Pilar Homeschooling</h3>
                        <button @click="addPillarModal = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow">
                            ➕ Tambah Pilar Baru
                        </button>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800">
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-20">Icon</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-1/4">Nama Pilar</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-24">Urutan</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider text-right w-36">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($pillars as $pillar)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                    <td class="px-6 py-4 text-2xl font-bold">{{ $pillar->icon }}</td>
                                    <td class="px-6 py-4 font-black text-slate-950 dark:text-white">{{ $pillar->judul }}</td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-medium">{{ $pillar->deskripsi }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-400">{{ $pillar->urutan }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button @click="
                                            editPillarData = { id: '{{ $pillar->id }}', icon: '{{ $pillar->icon }}', judul: '{{ $pillar->judul }}', deskripsi: '{{ $pillar->deskripsi }}', urutan: {{ $pillar->urutan }} };
                                            editPillarModal = true;
                                        " class="text-xs text-amber-500 hover:underline font-bold">Edit</button>
                                        <form action="{{ route('admin.compro-cms.pillars.destroy', $pillar->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pilar ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-500 hover:underline font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-medium">Belum ada data pilar sekolah.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: PROGRAM PENDIDIKAN -->
                <div x-show="tab === 'programs'" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-black uppercase tracking-wider dark:text-white">Daftar Program Pendidikan</h3>
                        <button @click="addProgramModal = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow">
                            ➕ Tambah Program Baru
                        </button>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800">
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-24">Jenjang</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-1/4">Nama Program</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-36">Catatan Kaki</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-20">Urutan</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider text-right w-36">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($programs as $program)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                    <td class="px-6 py-4 font-black">
                                        <span class="px-2 py-1 bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 rounded-lg text-xs">
                                            {{ $program->jenjang }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-black text-slate-950 dark:text-white">{{ $program->judul }}</td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-medium">{{ $program->deskripsi }}</td>
                                    <td class="px-6 py-4 text-slate-400 font-semibold text-xs">{{ $program->catatan_kaki }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-400">{{ $program->urutan }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button @click="
                                            editProgramData = { id: '{{ $program->id }}', jenjang: '{{ $program->jenjang }}', judul: '{{ $program->judul }}', deskripsi: '{{ $program->deskripsi }}', catatan_kaki: '{{ $program->catatan_kaki }}', urutan: {{ $program->urutan }} };
                                            editProgramModal = true;
                                        " class="text-xs text-amber-500 hover:underline font-bold">Edit</button>
                                        <form action="{{ route('admin.compro-cms.programs.destroy', $program->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus program ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-500 hover:underline font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-400 font-medium">Belum ada data program sekolah.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 4: KURIKULUM & KEGIATAN -->
                <div x-show="tab === 'curriculum'" class="space-y-8">
                    <!-- Muatan Kurikulum Utama -->
                    <div class="space-y-4">
                        <h3 class="text-base font-black uppercase tracking-wider dark:text-white">Muatan Kurikulum Utama (3 Laporan Perkembangan)</h3>
                        <div class="grid md:grid-cols-3 gap-6">
                            @foreach($curriculumLoads as $load)
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">{{ $load->icon }}</span>
                                        <div>
                                            <span class="text-xs text-slate-400 font-black uppercase">{{ $load->kode }}</span>
                                            <h4 class="font-black text-base dark:text-white">{{ $load->nama }}</h4>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 text-sm bg-purple-50 dark:bg-purple-950 text-purple-650 dark:text-purple-400 rounded-xl font-black">{{ $load->persentase }}%</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">{{ $load->deskripsi }}</p>
                                <button @click="
                                    editLoadData = { id: '{{ $load->id }}', nama: '{{ $load->nama }}', persentase: {{ $load->persentase }}, deskripsi: '{{ $load->deskripsi }}', warna: '{{ $load->warna }}', icon: '{{ $load->icon }}' };
                                    editLoadModal = true;
                                " class="w-full py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-350 rounded-xl text-xs font-bold transition">
                                    Edit Detail Muatan
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Daftar Kegiatan Detail -->
                    <div class="space-y-4 pt-4 border-t dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-black uppercase tracking-wider dark:text-white">Daftar Kegiatan Detail Kurikulum</h3>
                            <button @click="addActivityModal = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow">
                                ➕ Tambah Kegiatan Baru
                            </button>
                        </div>

                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-40">Muatan</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-20">Icon</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-1/4">Nama Kegiatan</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Keterangan</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-20">Urutan</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider text-right w-36">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                    @foreach($curriculumLoads as $load)
                                        @forelse($load->activities as $act)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                            <td class="px-6 py-4 font-black">
                                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-350 rounded-lg text-xs uppercase tracking-wider">
                                                    {{ $load->nama }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-lg">{{ $act->icon }}</td>
                                            <td class="px-6 py-4 font-black text-slate-950 dark:text-white">{{ $act->nama }}</td>
                                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-medium">{{ $act->deskripsi }}</td>
                                            <td class="px-6 py-4 font-bold text-slate-400">{{ $act->urutan }}</td>
                                            <td class="px-6 py-4 text-right space-x-2">
                                                <button @click="
                                                    editActivityData = { id: '{{ $act->id }}', curriculum_load_id: '{{ $act->curriculum_load_id }}', icon: '{{ $act->icon }}', nama: '{{ $act->nama }}', deskripsi: '{{ $act->deskripsi }}', urutan: {{ $act->urutan }} };
                                                    editActivityModal = true;
                                                " class="text-xs text-amber-500 hover:underline font-bold">Edit</button>
                                                <form action="{{ route('admin.compro-cms.activities.destroy', $act->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kegiatan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-rose-500 hover:underline font-bold">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <!-- No activities under this load -->
                                        @endforelse
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 5: RINCIAN BIAYA (INVESTASI) -->
                <div x-show="tab === 'investasi'" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-black uppercase tracking-wider dark:text-white">Daftar Item Investasi & Biaya Pendidikan</h3>
                        <button @click="addInvestmentModal = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow">
                            ➕ Tambah Item Baru
                        </button>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800">
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-1/4">Nama Item Investasi</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-40">Harga / Biaya</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Keterangan Tambahan</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-20">Urutan</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider text-right w-36">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($investmentItems as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                    <td class="px-6 py-4 font-black text-slate-950 dark:text-white">{{ $item->nama }}</td>
                                    <td class="px-6 py-4 font-black text-purple-650 dark:text-purple-400">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-medium">{{ $item->keterangan }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-400">{{ $item->urutan }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button @click="
                                            editInvestmentData = { id: '{{ $item->id }}', nama: '{{ $item->nama }}', harga: {{ $item->harga }}, keterangan: '{{ $item->keterangan }}', urutan: {{ $item->urutan }} };
                                            editInvestmentModal = true;
                                        " class="text-xs text-amber-500 hover:underline font-bold">Edit</button>
                                        <form action="{{ route('admin.compro-cms.investments.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item investasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-500 hover:underline font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-medium">Belum ada data biaya / investasi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Promo & Diskon Section -->
                    <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-800 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-black uppercase tracking-wider dark:text-white">Promo Gelombang & Diskon Biaya</h3>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">Atur potongan harga/investasi khusus berdasarkan gelombang pendaftaran.</p>
                            </div>
                            <button @click="addPromoModal = true" class="px-4 py-2 bg-purple-650 hover:bg-purple-750 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow">
                                ➕ Tambah Promo Baru
                            </button>
                        </div>

                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-1/4">Nama Promo / Gelombang</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Keterangan</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-64">Potongan Diskon</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-28">Status</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider text-right w-36">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                    @forelse($investmentPromos as $promo)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                        <td class="px-6 py-4 font-black text-slate-950 dark:text-white">
                                            {{ $promo->label }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                            {{ $promo->deskripsi }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                @forelse($promo->discounts as $disc)
                                                    @if($disc->investmentItem)
                                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-650 dark:text-purple-400">
                                                        🎯 {{ $disc->investmentItem->nama }}: <span class="font-bold text-slate-900 dark:text-white">{{ $disc->diskon_persen }}%</span>
                                                    </span>
                                                    @endif
                                                @empty
                                                    <span class="text-xs text-slate-400">Tidak ada potongan</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($promo->aktif)
                                                <span class="px-2.5 py-1 bg-green-500/10 text-green-600 dark:text-green-400 text-[10px] font-black tracking-wider uppercase rounded-lg">Aktif</span>
                                            @else
                                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-400 text-[10px] font-bold tracking-wider uppercase rounded-lg">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <button @click="
                                                editPromoData = { 
                                                    id: '{{ $promo->id }}', 
                                                    label: '{{ $promo->label }}', 
                                                    deskripsi: '{{ $promo->deskripsi }}', 
                                                    aktif: {{ $promo->aktif ? 1 : 0 }}, 
                                                    discounts: {
                                                        @foreach($promo->discounts as $d)
                                                            '{{ $d->investment_item_id }}': '{{ $d->diskon_persen }}',
                                                        @endforeach
                                                    } 
                                                };
                                                editPromoModal = true;
                                            " class="text-xs text-amber-500 hover:underline font-bold">Edit</button>
                                            <form action="{{ route('admin.compro-cms.promos.destroy', $promo->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus program promo ini beserta semua diskon di dalamnya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-rose-500 hover:underline font-bold">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-medium">Belum ada promo gelombang dibuat.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 6: HIGHLIGHTS METODE -->
                <div x-show="tab === 'highlights'" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-black uppercase tracking-wider dark:text-white">Poin Highlight Metode Pembelajaran</h3>
                        <button @click="addHighlightModal = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow">
                            ➕ Tambah Highlight Baru
                        </button>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800">
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Teks Poin Highlight</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-32">Urutan</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider text-right w-36">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($learningHighlights as $point)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                    <td class="px-6 py-4 font-bold text-slate-950 dark:text-white">{{ $point->teks }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-400">{{ $point->urutan }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button @click="
                                            editHighlightData = { id: '{{ $point->id }}', teks: '{{ $point->teks }}', urutan: {{ $point->urutan }} };
                                            editHighlightModal = true;
                                        " class="text-xs text-amber-500 hover:underline font-bold">Edit</button>
                                        <form action="{{ route('admin.compro-cms.highlights.destroy', $point->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus poin highlight ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-500 hover:underline font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400 font-medium">Belum ada data poin highlight.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 7: GALERI KEGIATAN (ALBUMS) -->
                <div x-show="tab === 'gallery'" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-black uppercase tracking-wider dark:text-white">Daftar Album Kegiatan</h3>
                        <button @click="addAlbumModal = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow">
                            ➕ Buat Album Baru
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($galleryAlbums as $album)
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between p-6 space-y-4">
                            <div class="flex gap-4">
                                <div class="w-24 h-24 rounded-xl overflow-hidden shrink-0 bg-slate-100 dark:bg-slate-800 border dark:border-slate-800 flex items-center justify-center">
                                    @if($album->cover_path)
                                    <img src="{{ asset($album->cover_path) }}" alt="{{ $album->judul }}" class="w-full h-full object-cover">
                                    @else
                                    <span class="text-xs text-slate-400">No Cover</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="px-2 py-0.5 bg-purple-50 dark:bg-purple-950 text-purple-650 dark:text-purple-400 rounded-lg text-[10px] font-black tracking-wider uppercase">
                                        {{ $album->photos->count() }} Foto
                                    </span>
                                    <h4 class="font-black text-base dark:text-white truncate mt-1">{{ $album->judul }}</h4>
                                    @if($album->tanggal)
                                    <span class="text-[10px] text-slate-400 block font-bold mt-0.5">{{ \Carbon\Carbon::parse($album->tanggal)->format('d M Y') }}</span>
                                    @endif
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-2 font-medium leading-relaxed">{{ $album->deskripsi }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 pt-2 border-t dark:border-slate-850">
                                <button @click="
                                    editAlbumData = { id: '{{ $album->id }}', judul: '{{ $album->judul }}', deskripsi: '{{ $album->deskripsi }}', tanggal: '{{ $album->tanggal }}', urutan: {{ $album->urutan }} };
                                    editAlbumModal = true;
                                " class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-350 rounded-xl text-xs font-bold transition">
                                    Edit Detail
                                </button>
                                <button @click="
                                    managePhotosData = { id: '{{ $album->id }}', judul: '{{ $album->judul }}', photos: [
                                        @foreach($album->photos as $ph)
                                            { id: '{{ $ph->id }}', path: '{{ asset($ph->foto_path) }}' },
                                        @endforeach
                                    ] };
                                    selectedPhotosPreviews = [];
                                    managePhotosModal = true;
                                " class="flex-1 py-2 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/40 text-purple-650 dark:text-purple-400 rounded-xl text-xs font-bold transition">
                                    Kelola Foto
                                </button>
                                <form action="{{ route('admin.compro-cms.albums.destroy', $album->id) }}" method="POST" onsubmit="return confirm('Hapus album ini dan semua foto di dalamnya?')" class="inline shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-2 px-3.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 text-rose-600 rounded-xl text-xs font-bold transition">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 py-12 text-center text-slate-400 font-medium bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-2xl">Belum ada album galeri. Silakan buat album baru terlebih dahulu.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL: TAMBAH PILAR -->
    <div x-show="addPillarModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="addPillarModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Tambah Pilar Baru</h4>
                    <button @click="addPillarModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form action="{{ route('admin.compro-cms.pillars.store') }}" method="POST" class="space-y-4 text-slate-900">
                    @csrf
                    <div class="grid grid-cols-4 gap-3">
                        <div class="col-span-1">
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Icon (Emoji)</label>
                            <input type="text" name="icon" required placeholder="🌟" class="w-full border rounded-xl p-2 text-xs text-center font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                        <div class="col-span-3">
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Pilar</label>
                            <input type="text" name="judul" required placeholder="Cth: Akademik Mandiri" class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="3" required placeholder="Tulis deskripsi pilar di sini..." class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" value="0" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="addPillarModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT PILAR -->
    <div x-show="editPillarModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="editPillarModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Edit Pilar Sekolah</h4>
                    <button @click="editPillarModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form :action="'/admin/compro-cms/pillars/' + editPillarData.id" method="POST" class="space-y-4 text-slate-900">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-4 gap-3">
                        <div class="col-span-1">
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Icon (Emoji)</label>
                            <input type="text" name="icon" x-model="editPillarData.icon" required class="w-full border rounded-xl p-2 text-xs text-center font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                        <div class="col-span-3">
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Pilar</label>
                            <input type="text" name="judul" x-model="editPillarData.judul" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Deskripsi Singkat</label>
                        <textarea name="deskripsi" x-model="editPillarData.deskripsi" rows="3" required class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" x-model="editPillarData.urutan" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="editPillarModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH PROGRAM -->
    <div x-show="addProgramModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="addProgramModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Tambah Program Baru</h4>
                    <button @click="addProgramModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form action="{{ route('admin.compro-cms.programs.store') }}" method="POST" class="space-y-4 text-slate-900">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Jenjang Tingkat</label>
                        <select name="jenjang" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Program</label>
                        <input type="text" name="judul" required placeholder="Cth: PKBM Kesetaraan SD" class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Program</label>
                        <textarea name="deskripsi" rows="3" required placeholder="Tulis detail materi program di sini..." class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Catatan Kaki (Info Tambahan)</label>
                        <input type="text" name="catatan_kaki" placeholder="Cth: Raport & Ijazah Setara Diknas" class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" value="0" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="addProgramModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT PROGRAM -->
    <div x-show="editProgramModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="editProgramModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Edit Program Pendidikan</h4>
                    <button @click="editProgramModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form :action="'/admin/compro-cms/programs/' + editProgramData.id" method="POST" class="space-y-4 text-slate-900">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Jenjang Tingkat</label>
                        <select name="jenjang" x-model="editProgramData.jenjang" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Program</label>
                        <input type="text" name="judul" x-model="editProgramData.judul" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Program</label>
                        <textarea name="deskripsi" x-model="editProgramData.deskripsi" rows="3" required class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Catatan Kaki (Info Tambahan)</label>
                        <input type="text" name="catatan_kaki" x-model="editProgramData.catatan_kaki" class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" x-model="editProgramData.urutan" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="editProgramModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT LOAD KURIKULUM -->
    <div x-show="editLoadModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="editLoadModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Edit Muatan Kurikulum</h4>
                    <button @click="editLoadModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form :action="'/admin/compro-cms/curriculum-loads/' + editLoadData.id" method="POST" class="space-y-4 text-slate-900 font-semibold text-xs">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Icon</label>
                            <input type="text" name="icon" x-model="editLoadData.icon" required class="w-full border rounded-xl p-2 text-xs font-bold text-center dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                        <div class="col-span-2">
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Muatan</label>
                            <input type="text" name="nama" x-model="editLoadData.nama" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Persentase (%)</label>
                            <input type="number" name="persentase" x-model="editLoadData.persentase" min="1" max="100" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Warna Badge</label>
                            <select name="warna" x-model="editLoadData.warna" required class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                                <option value="purple">Ungu (Purple)</option>
                                <option value="green">Hijau (Green)</option>
                                <option value="orange">Oranye (Orange)</option>
                                <option value="blue">Biru (Blue)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Singkat</label>
                        <textarea name="deskripsi" x-model="editLoadData.deskripsi" rows="3" required class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="editLoadModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH KEGIATAN KURIKULUM -->
    <div x-show="addActivityModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="addActivityModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Tambah Kegiatan Kurikulum</h4>
                    <button @click="addActivityModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form action="{{ route('admin.compro-cms.activities.store') }}" method="POST" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Pilih Muatan Kurikulum</label>
                        <select name="curriculum_load_id" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                            @foreach($curriculumLoads as $load)
                                <option value="{{ $load->id }}">{{ $load->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-4 gap-3">
                        <div class="col-span-1">
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Icon</label>
                            <input type="text" name="icon" value="▪️" required class="w-full border rounded-xl p-2 text-xs text-center font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                        <div class="col-span-3">
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Kegiatan</label>
                            <input type="text" name="nama" required placeholder="Cth: Shalat Dhuha Mandiri" class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Singkat</label>
                        <textarea name="deskripsi" rows="3" placeholder="Tulis detail/deskripsi kegiatan kurikulum..." class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" value="0" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="addActivityModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT KEGIATAN KURIKULUM -->
    <div x-show="editActivityModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="editActivityModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Edit Kegiatan Kurikulum</h4>
                    <button @click="editActivityModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form :action="'/admin/compro-cms/activities/' + editActivityData.id" method="POST" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Pilih Muatan Kurikulum</label>
                        <select name="curriculum_load_id" x-model="editActivityData.curriculum_load_id" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700" disabled>
                            @foreach($curriculumLoads as $load)
                                <option value="{{ $load->id }}">{{ $load->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-4 gap-3">
                        <div class="col-span-1">
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Icon</label>
                            <input type="text" name="icon" x-model="editActivityData.icon" required class="w-full border rounded-xl p-2 text-xs text-center font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                        <div class="col-span-3">
                            <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Kegiatan</label>
                            <input type="text" name="nama" x-model="editActivityData.nama" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Singkat</label>
                        <textarea name="deskripsi" x-model="editActivityData.deskripsi" rows="3" class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" x-model="editActivityData.urutan" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="editActivityModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH RINCIAN BIAYA -->
    <div x-show="addInvestmentModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="addInvestmentModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Tambah Biaya Baru</h4>
                    <button @click="addInvestmentModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form action="{{ route('admin.compro-cms.investments.store') }}" method="POST" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Item Investasi</label>
                        <input type="text" name="nama" required placeholder="Cth: Biaya Formulir, SPP Bulanan" class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Harga / Nominal (Rupiah)</label>
                        <input type="number" name="harga" required placeholder="Cth: 1500000" class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Singkat</label>
                        <textarea name="keterangan" rows="2" placeholder="Cth: Termasuk psikotes, dibayar sekali" class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" value="0" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="addInvestmentModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT RINCIAN BIAYA -->
    <div x-show="editInvestmentModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="editInvestmentModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Edit Rincian Biaya</h4>
                    <button @click="editInvestmentModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form :action="'/admin/compro-cms/investments/' + editInvestmentData.id" method="POST" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Item Investasi</label>
                        <input type="text" name="nama" x-model="editInvestmentData.nama" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Harga / Nominal (Rupiah)</label>
                        <input type="number" name="harga" x-model="editInvestmentData.harga" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Singkat</label>
                        <textarea name="keterangan" x-model="editInvestmentData.keterangan" rows="2" class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" x-model="editInvestmentData.urutan" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="editInvestmentModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH HIGHLIGHT -->
    <div x-show="addHighlightModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="addHighlightModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Tambah Highlight Baru</h4>
                    <button @click="addHighlightModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form action="{{ route('admin.compro-cms.highlights.store') }}" method="POST" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Teks Poin Highlight</label>
                        <input type="text" name="teks" required placeholder="Cth: Mengacu & menginduk kepada PKBM" class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" value="0" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="addHighlightModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT HIGHLIGHT -->
    <div x-show="editHighlightModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="editHighlightModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Edit Highlight</h4>
                    <button @click="editHighlightModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form :action="'/admin/compro-cms/highlights/' + editHighlightData.id" method="POST" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Teks Poin Highlight</label>
                        <input type="text" name="teks" x-model="editHighlightData.teks" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" x-model="editHighlightData.urutan" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="editHighlightModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH ALBUM GALERI -->
    <div x-show="addAlbumModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="addAlbumModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Buat Album Kegiatan Baru</h4>
                    <button @click="addAlbumModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form action="{{ route('admin.compro-cms.albums.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Judul Album</label>
                        <input type="text" name="judul" required placeholder="Cth: Field Trip Kebun Raya Bogor" class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold text-slate-500">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Singkat</label>
                        <textarea name="deskripsi" rows="3" placeholder="Tulis rincian kegiatan album ini..." class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Foto Sampul (Cover Album) - Wajib</label>
                        <input type="file" name="cover" required class="w-full text-xs">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" value="0" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="addAlbumModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Buat Album</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT DETAIL ALBUM GALERI -->
    <div x-show="editAlbumModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="editAlbumModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Edit Detail Album</h4>
                    <button @click="editAlbumModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form :action="'/admin/compro-cms/albums/' + editAlbumData.id" method="POST" enctype="multipart/form-data" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Judul Album</label>
                        <input type="text" name="judul" x-model="editAlbumData.judul" required class="w-full border rounded-xl p-2 text-xs font-bold dark:bg-slate-800 dark:text-white dark:border-slate-700">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" x-model="editAlbumData.tanggal" class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold text-slate-500">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Singkat</label>
                        <textarea name="deskripsi" x-model="editAlbumData.deskripsi" rows="3" class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Ganti Foto Sampul (Cover Album)</label>
                        <input type="file" name="cover" class="w-full text-xs">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" x-model="editAlbumData.urutan" class="w-24 border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="editAlbumModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: KELOLA FOTO ALBUM GALERI -->
    <div x-show="managePhotosModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="managePhotosModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <div>
                        <h4 class="font-black text-sm uppercase dark:text-white">Kelola Foto Album</h4>
                        <span class="text-[10px] text-slate-400 font-black block mt-0.5" x-text="managePhotosData.judul"></span>
                    </div>
                    <button @click="managePhotosModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                
                <!-- Photos collection grid inside modal -->
                <div class="space-y-2">
                    <span class="text-[9px] font-black text-slate-400 block uppercase">Foto Terunggah</span>
                    <div class="grid grid-cols-4 gap-3 max-h-60 overflow-y-auto p-2 bg-slate-50 dark:bg-slate-950 rounded-2xl border dark:border-slate-800">
                        <template x-for="photo in managePhotosData.photos" :key="photo.id">
                            <div class="aspect-square relative group rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-850 border dark:border-slate-800 shadow-sm">
                                <img :src="photo.path" class="w-full h-full object-cover">
                                <form :action="'/admin/compro-cms/photos/' + photo.id" method="POST" onsubmit="return confirm('Hapus foto ini dari album?')" class="absolute inset-0 flex items-center justify-center bg-slate-950/70 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-650 hover:bg-rose-700 text-white rounded-full text-xs font-bold shadow-lg transition">
                                        ❌ Hapus Foto
                                    </button>
                                </form>
                            </div>
                        </template>
                        <div x-show="managePhotosData.photos.length === 0" class="col-span-4 py-12 text-center text-xs text-slate-400 font-medium">Belum ada foto dalam album ini. Unggah di bawah!</div>
                    </div>
                </div>

                <!-- Add Photos Form inside modal -->
                <form action="{{ route('admin.compro-cms.photos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 border-t pt-4 dark:border-slate-800 text-slate-900 text-xs">
                    @csrf
                    <input type="hidden" name="album_id" :value="managePhotosData.id">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Unggah Foto Baru (Pilih 1 atau lebih)</label>
                        <input type="file" name="photos[]" multiple required class="w-full text-xs"
                               @change="
                                   selectedPhotosPreviews = [];
                                   const files = $event.target.files;
                                   for(let i=0; i < files.length; i++) {
                                       selectedPhotosPreviews.push(URL.createObjectURL(files[i]));
                                   }
                               ">
                    </div>

                    <!-- Selected Photos Previews -->
                    <div x-show="selectedPhotosPreviews.length > 0" class="space-y-2">
                        <label class="text-[9px] font-black text-purple-600 dark:text-purple-400 block uppercase">Pratinjau Foto Pilihan (Belum Disimpan)</label>
                        <div class="grid grid-cols-4 gap-3 bg-purple-50/30 dark:bg-slate-950 p-2.5 rounded-2xl border border-purple-100 dark:border-slate-800">
                            <template x-for="url in selectedPhotosPreviews">
                                <div class="aspect-square relative rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-800 border dark:border-slate-750 shadow-sm">
                                    <img :src="url" class="w-full h-full object-cover">
                                </div>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan Foto / Caption (Opsional)</label>
                        <input type="text" name="keterangan" placeholder="Cth: Suasana belajar kelompok" class="w-full border rounded-xl p-2 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="managePhotosModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Tutup</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Unggah Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH PROMO GELOMBANG BARU -->
    <div x-show="addPromoModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="addPromoModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Tambah Promo Gelombang</h4>
                    <button @click="addPromoModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form action="{{ route('admin.compro-cms.promos.store') }}" method="POST" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Promo / Gelombang</label>
                        <input type="text" name="label" placeholder="Cth: Gelombang 1" required class="w-full border rounded-xl p-2.5 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan / Deskripsi</label>
                        <textarea name="deskripsi" placeholder="Cth: Potongan harga spesial pendaftaran di awal tahun ajaran" rows="3" class="w-full border rounded-xl p-2.5 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="aktif" value="1" id="add_promo_aktif" class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                        <label for="add_promo_aktif" class="text-[10px] font-bold text-slate-500 uppercase cursor-pointer">Aktifkan Promo Ini (Akan menonaktifkan promo lain)</label>
                    </div>

                    <div class="border-t pt-4 dark:border-slate-800 space-y-3">
                        <span class="text-[9px] font-black text-slate-400 block uppercase">Pengaturan Potongan Diskon (%)</span>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($investmentItems as $item)
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase">{{ $item->nama }}</label>
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="number" name="discounts[{{ $item->id }}]" placeholder="0" min="0" max="100" class="border rounded-xl p-2 text-xs w-24 text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    <span class="text-xs font-bold text-slate-400">%</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="addPromoModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Simpan Promo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT PROMO GELOMBANG -->
    <div x-show="editPromoModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="editPromoModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl border dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b pb-3 dark:border-slate-800">
                    <h4 class="font-black text-sm uppercase dark:text-white">Edit Promo Gelombang</h4>
                    <button @click="editPromoModal = false" class="text-slate-400 hover:text-slate-650">✕</button>
                </div>
                <form :action="'/admin/compro-cms/promos/' + editPromoData.id" method="POST" class="space-y-4 text-slate-900 text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Nama Promo / Gelombang</label>
                        <input type="text" name="label" x-model="editPromoData.label" required class="w-full border rounded-xl p-2.5 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-bold">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 block uppercase mb-1">Keterangan / Deskripsi</label>
                        <textarea name="deskripsi" x-model="editPromoData.deskripsi" rows="3" class="w-full border rounded-xl p-2.5 text-xs dark:bg-slate-800 dark:text-white dark:border-slate-700 font-medium"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="aktif" value="1" id="edit_promo_aktif" :checked="editPromoData.aktif" @change="editPromoData.aktif = $event.target.checked ? 1 : 0" class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                        <label for="edit_promo_aktif" class="text-[10px] font-bold text-slate-500 uppercase cursor-pointer">Aktifkan Promo Ini (Akan menonaktifkan promo lain)</label>
                    </div>

                    <div class="border-t pt-4 dark:border-slate-800 space-y-3">
                        <span class="text-[9px] font-black text-slate-400 block uppercase">Pengaturan Potongan Diskon (%)</span>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($investmentItems as $item)
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase">{{ $item->nama }}</label>
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="number" 
                                           :name="'discounts[' + {{ $item->id }} + ']'" 
                                           x-model="editPromoData.discounts[{{ $item->id }}]"
                                           placeholder="0" 
                                           min="0" 
                                           max="100" 
                                           class="border rounded-xl p-2 text-xs w-24 text-slate-900 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                                    <span class="text-xs font-bold text-slate-400">%</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-800">
                        <button type="button" @click="editPromoModal = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-650 hover:bg-purple-700 text-white rounded-xl text-xs font-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
