<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolHeroSetting;
use App\Models\SchoolPillar;
use App\Models\SchoolProgram;
use App\Models\CurriculumLoad;
use App\Models\CurriculumActivity;
use App\Models\InvestmentItem;
use App\Models\InvestmentPromo;
use App\Models\InvestmentPromoDiscount;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\LearningMethod;
use App\Models\LearningHighlight;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComproAdminController extends Controller
{
    /** Tampilkan CMS (Tab-based) */
    public function index()
    {
        $settings        = SchoolHeroSetting::getAllSettings();
        $pillars         = SchoolPillar::orderBy('urutan')->get();
        $programs        = SchoolProgram::orderBy('urutan')->get();
        $curriculumLoads = CurriculumLoad::with('activities')->orderBy('urutan')->get();
        $investmentItems = InvestmentItem::orderBy('urutan')->get();
        $investmentPromos = InvestmentPromo::with('discounts.investmentItem')->get();
        $galleryAlbums   = GalleryAlbum::with('photos')->orderBy('urutan')->get();
        $learningMethods = LearningMethod::orderBy('urutan')->get();
        $learningHighlights = LearningHighlight::orderBy('urutan')->get();
        $registrations   = StudentRegistration::orderByDesc('submitted_at')->limit(10)->get();
        $schoolProfile   = \App\Models\ProfilSekolah::first();
        $regCounts = [
            'pending'  => StudentRegistration::where('status', 'pending')->count(),
            'approved' => StudentRegistration::where('status', 'approved')->count(),
        ];

        return view('admin.compro.cms', compact(
            'settings', 'pillars', 'programs', 'curriculumLoads',
            'investmentItems', 'investmentPromos', 'galleryAlbums',
            'learningMethods', 'learningHighlights', 'registrations', 'regCounts', 'schoolProfile'
        ));
    }

    /** ─── SETTINGS ─────────────────────────────────────────────── */
    public function updateSettings(Request $request)
    {
        $whitelist = [
            'hero_badge', 'hero_title', 'hero_subtitle',
            'hero_image_title', 'hero_image_status',
            'hero_image_stat1_label', 'hero_image_stat1_val',
            'hero_image_stat2_label', 'hero_image_stat2_val',
            'hero_image_agenda_label', 'hero_image_agenda_title', 'hero_image_agenda_desc',
            'hero_image_progress_label', 'hero_image_progress_percent',
            'tentang_title', 'tentang_desc_1', 'tentang_desc_2',
            'visi', 'misi', 'metode_title', 'metode_desc',
            'pendaftaran_title', 'pendaftaran_desc',
            'pendaftaran_step1', 'pendaftaran_step1_desc',
            'pendaftaran_step2', 'pendaftaran_step2_desc',
            'pendaftaran_step3', 'pendaftaran_step3_desc',
            'whatsapp_number', 'whatsapp_text',
            'jadwal_wajib', 'jadwal_opsional', 'jam_masuk', 'jam_keluar', 'spp_termasuk',
            'kekhasan_desc',
            'footer_title', 'footer_desc', 'footer_office_address', 'footer_office_email', 'footer_office_phone',
            'nav_about_text', 'nav_program_text', 'nav_method_text', 'nav_contact_text',
            'header_cta_student_text', 'header_cta_teacher_text',
            'hero_cta_student_text', 'hero_cta_teacher_text',
            'footer_portal_title', 'footer_link_student_text', 'footer_link_teacher_text',
            'footer_link_admin_text', 'footer_copyright_suffix',
        ];

        foreach ($whitelist as $key) {
            if ($request->has($key)) {
                SchoolHeroSetting::setValue($key, $request->input($key));
            }
        }

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240']);
            $schoolProfile = \App\Models\ProfilSekolah::first() ?? new \App\Models\ProfilSekolah();
            if ($schoolProfile->logo_path) {
                Storage::disk('public')->delete($schoolProfile->logo_path);
            }
            $path = $this->compressAndStoreImage($request->file('logo'), 'logos', 800, 80);
            $schoolProfile->logo_path = $path;
            $schoolProfile->save();
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /** ─── PILLAR ─────────────────────────────────────────────────── */
    public function pillarStore(Request $request)
    {
        $request->validate(['icon' => 'required', 'judul' => 'required|string|max:255', 'deskripsi' => 'nullable|string', 'urutan' => 'nullable|integer']);
        SchoolPillar::create($request->only('icon', 'judul', 'deskripsi', 'urutan'));
        return redirect()->back()->with('success', 'Pilar berhasil ditambahkan.');
    }

    public function pillarUpdate(Request $request, SchoolPillar $pillar)
    {
        $request->validate(['icon' => 'required', 'judul' => 'required|string|max:255', 'deskripsi' => 'nullable|string', 'urutan' => 'nullable|integer']);
        $pillar->update($request->only('icon', 'judul', 'deskripsi', 'urutan'));
        return redirect()->back()->with('success', 'Pilar berhasil diperbarui.');
    }

    public function pillarDestroy(SchoolPillar $pillar)
    {
        $pillar->delete();
        return redirect()->back()->with('success', 'Pilar berhasil dihapus.');
    }

    /** ─── PROGRAM ────────────────────────────────────────────────── */
    public function programStore(Request $request)
    {
        $request->validate(['jenjang' => 'required|in:SD,SMP,SMA', 'judul' => 'required|string|max:255', 'deskripsi' => 'nullable|string', 'catatan_kaki' => 'nullable|string|max:255', 'urutan' => 'nullable|integer']);
        SchoolProgram::create($request->only('jenjang', 'judul', 'deskripsi', 'catatan_kaki', 'urutan'));
        return redirect()->back()->with('success', 'Program berhasil ditambahkan.');
    }

    public function programUpdate(Request $request, SchoolProgram $program)
    {
        $request->validate(['jenjang' => 'required|in:SD,SMP,SMA', 'judul' => 'required|string|max:255', 'deskripsi' => 'nullable|string', 'catatan_kaki' => 'nullable|string|max:255', 'urutan' => 'nullable|integer']);
        $program->update($request->only('jenjang', 'judul', 'deskripsi', 'catatan_kaki', 'urutan'));
        return redirect()->back()->with('success', 'Program berhasil diperbarui.');
    }

    public function programDestroy(SchoolProgram $program)
    {
        $program->delete();
        return redirect()->back()->with('success', 'Program berhasil dihapus.');
    }

    /** ─── CURRICULUM LOAD ────────────────────────────────────────── */
    public function curriculumLoadUpdate(Request $request, CurriculumLoad $load)
    {
        $request->validate(['nama' => 'required|string', 'persentase' => 'required|integer|min:1|max:100', 'deskripsi' => 'nullable|string', 'warna' => 'nullable|string', 'icon' => 'nullable|string']);
        $load->update($request->only('nama', 'persentase', 'deskripsi', 'warna', 'icon'));
        return redirect()->back()->with('success', 'Muatan kurikulum berhasil diperbarui.');
    }

    /** ─── CURRICULUM ACTIVITY ───────────────────────────────────── */
    public function activityStore(Request $request)
    {
        $request->validate(['curriculum_load_id' => 'required|exists:curriculum_loads,id', 'icon' => 'nullable|string', 'nama' => 'required|string|max:255', 'deskripsi' => 'nullable|string', 'urutan' => 'nullable|integer']);
        CurriculumActivity::create($request->only('curriculum_load_id', 'icon', 'nama', 'deskripsi', 'urutan'));
        return redirect()->back()->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function activityUpdate(Request $request, CurriculumActivity $activity)
    {
        $request->validate(['icon' => 'nullable|string', 'nama' => 'required|string|max:255', 'deskripsi' => 'nullable|string', 'urutan' => 'nullable|integer']);
        $activity->update($request->only('icon', 'nama', 'deskripsi', 'urutan'));
        return redirect()->back()->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function activityDestroy(CurriculumActivity $activity)
    {
        $activity->delete();
        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.');
    }

    /** ─── INVESTMENT ────────────────────────────────────────────── */
    public function investmentStore(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255', 'keterangan' => 'nullable|string|max:255', 'harga' => 'required|integer|min:0', 'urutan' => 'nullable|integer', 'aktif' => 'nullable|boolean']);
        InvestmentItem::create(['nama' => $request->nama, 'keterangan' => $request->keterangan, 'harga' => $request->harga, 'urutan' => $request->urutan ?? 0, 'aktif' => $request->boolean('aktif', true)]);
        return redirect()->back()->with('success', 'Item investasi berhasil ditambahkan.');
    }

    public function investmentUpdate(Request $request, InvestmentItem $item)
    {
        $request->validate(['nama' => 'required|string|max:255', 'keterangan' => 'nullable|string|max:255', 'harga' => 'required|integer|min:0', 'urutan' => 'nullable|integer']);
        $item->update(['nama' => $request->nama, 'keterangan' => $request->keterangan, 'harga' => $request->harga, 'urutan' => $request->urutan ?? 0, 'aktif' => $request->boolean('aktif', true)]);
        return redirect()->back()->with('success', 'Item investasi berhasil diperbarui.');
    }

    public function investmentDestroy(InvestmentItem $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Item investasi berhasil dihapus.');
    }

    /** ─── GALLERY ALBUM ─────────────────────────────────────────── */
    public function albumStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255', 
            'deskripsi' => 'nullable|string', 
            'tanggal' => 'nullable|date', 
            'urutan' => 'nullable|integer',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240'
        ]);
        $data = $request->only('judul', 'deskripsi', 'tanggal', 'urutan');
        $data['aktif'] = true;
        if ($request->hasFile('cover')) {
            $data['cover_path'] = 'storage/' . $this->compressAndStoreImage($request->file('cover'), 'gallery/covers', 1200, 75);
        }
        GalleryAlbum::create($data);
        return redirect()->back()->with('success', 'Album berhasil dibuat.');
    }

    public function albumUpdate(Request $request, GalleryAlbum $album)
    {
        $request->validate([
            'judul' => 'required|string|max:255', 
            'deskripsi' => 'nullable|string', 
            'tanggal' => 'nullable|date',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240'
        ]);
        $data = $request->only('judul', 'deskripsi', 'tanggal', 'urutan');
        $data['aktif'] = $request->boolean('aktif', true);
        if ($request->hasFile('cover')) {
            if ($album->cover_path) {
                Storage::disk('public')->delete(str_replace('storage/', '', $album->cover_path));
            }
            $data['cover_path'] = 'storage/' . $this->compressAndStoreImage($request->file('cover'), 'gallery/covers', 1200, 75);
        }
        $album->update($data);
        return redirect()->back()->with('success', 'Album berhasil diperbarui.');
    }

    public function albumDestroy(GalleryAlbum $album)
    {
        // Photos are cascade deleted by DB FK
        $album->delete();
        return redirect()->back()->with('success', 'Album dan semua fotonya berhasil dihapus.');
    }

    /** ─── GALLERY PHOTO ─────────────────────────────────────────── */
    public function photoStore(Request $request)
    {
        $request->validate([
            'album_id' => 'required|exists:gallery_albums,id', 
            'photos' => 'required|array', 
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:15360', 
            'keterangan' => 'nullable|string|max:255'
        ]);
        $urutan = GalleryPhoto::where('album_id', $request->album_id)->max('urutan') ?? 0;
        foreach ($request->file('photos') as $foto) {
            $path = $this->compressAndStoreImage($foto, 'gallery/photos', 1600, 75);
            GalleryPhoto::create([
                'album_id' => $request->album_id, 
                'foto_path' => 'storage/' . $path, 
                'keterangan' => $request->keterangan, 
                'urutan' => ++$urutan
            ]);
        }
        // Set cover if album has no cover
        $album = GalleryAlbum::find($request->album_id);
        if (!$album->cover_path && $album->photos()->exists()) {
            $album->update(['cover_path' => $album->photos()->first()->foto_path]);
        }
        return redirect()->back()->with('success', count($request->file('photos')) . ' foto berhasil diupload.');
    }

    public function photoDestroy(GalleryPhoto $photo)
    {
        Storage::disk('public')->delete(str_replace('storage/', '', $photo->foto_path));
        $photo->delete();
        return redirect()->back()->with('success', 'Foto berhasil dihapus.');
    }

    /** ─── LEARNING HIGHLIGHTS ────────────────────────────────────── */
    public function highlightStore(Request $request)
    {
        $request->validate(['teks' => 'required|string|max:255', 'urutan' => 'nullable|integer']);
        LearningHighlight::create($request->only('teks', 'urutan'));
        return redirect()->back()->with('success', 'Poin metode berhasil ditambahkan.');
    }

    public function highlightUpdate(Request $request, LearningHighlight $highlight)
    {
        $request->validate(['teks' => 'required|string|max:255', 'urutan' => 'nullable|integer']);
        $highlight->update($request->only('teks', 'urutan'));
        return redirect()->back()->with('success', 'Poin metode berhasil diperbarui.');
    }

    public function highlightDestroy(LearningHighlight $highlight)
    {
        $highlight->delete();
        return redirect()->back()->with('success', 'Poin metode berhasil dihapus.');
    }

    /** ─── PROMOS & DISCOUNTS ──────────────────────────────────────── */
    public function promoStore(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'aktif' => 'nullable|integer',
            'discounts' => 'nullable|array',
            'discounts.*' => 'nullable|integer|min:0|max:100',
        ]);

        $promo = InvestmentPromo::create([
            'label' => $request->label,
            'deskripsi' => $request->deskripsi,
            'aktif' => $request->has('aktif') || $request->input('aktif') == 1,
        ]);

        if ($promo->aktif) {
            InvestmentPromo::where('id', '!=', $promo->id)->update(['aktif' => false]);
        }

        if ($request->has('discounts')) {
            foreach ($request->discounts as $itemId => $percent) {
                if ($percent !== null && $percent !== '') {
                    InvestmentPromoDiscount::create([
                        'promo_id' => $promo->id,
                        'investment_item_id' => $itemId,
                        'diskon_persen' => $percent,
                        'keterangan' => 'Diskon ' . $percent . '% untuk ' . (InvestmentItem::find($itemId)->nama ?? 'item'),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Promo Gelombang berhasil dibuat.');
    }

    public function promoUpdate(Request $request, InvestmentPromo $promo)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'aktif' => 'nullable|integer',
            'discounts' => 'nullable|array',
            'discounts.*' => 'nullable|integer|min:0|max:100',
        ]);

        $promo->update([
            'label' => $request->label,
            'deskripsi' => $request->deskripsi,
            'aktif' => $request->has('aktif') || $request->input('aktif') == 1,
        ]);

        if ($promo->aktif) {
            InvestmentPromo::where('id', '!=', $promo->id)->update(['aktif' => false]);
        }

        $promo->discounts()->delete();
        if ($request->has('discounts')) {
            foreach ($request->discounts as $itemId => $percent) {
                if ($percent !== null && $percent !== '') {
                    InvestmentPromoDiscount::create([
                        'promo_id' => $promo->id,
                        'investment_item_id' => $itemId,
                        'diskon_persen' => $percent,
                        'keterangan' => 'Diskon ' . $percent . '% untuk ' . (InvestmentItem::find($itemId)->nama ?? 'item'),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Promo Gelombang berhasil diperbarui.');
    }

    public function promoDestroy(InvestmentPromo $promo)
    {
        $promo->discounts()->delete();
        $promo->delete();
        return redirect()->back()->with('success', 'Promo Gelombang berhasil dihapus.');
    }

    /**
     * Compress and save an uploaded image file using PHP GD.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory Directory inside 'public' disk
     * @param int $maxDimension Maximum width or height of the image (e.g. 1600)
     * @param int $quality Compression quality (0-100)
     * @return string Relational path inside 'public' disk
     */
    private function compressAndStoreImage($file, $directory, $maxDimension = 1600, $quality = 75)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = uniqid() . '.' . $extension;
        $tempPath = $file->getRealPath();

        // Check if GD is available
        if (!extension_loaded('gd')) {
            return $file->store($directory, 'public');
        }

        // Get image info
        $imageInfo = @getimagesize($tempPath);
        if (!$imageInfo) {
            return $file->store($directory, 'public');
        }

        list($width, $height, $type) = $imageInfo;

        // Load image based on type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = @imagecreatefromjpeg($tempPath);
                break;
            case IMAGETYPE_PNG:
                $image = @imagecreatefrompng($tempPath);
                break;
            case IMAGETYPE_GIF:
                $image = @imagecreatefromgif($tempPath);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    $image = @imagecreatefromwebp($tempPath);
                } else {
                    $image = false;
                }
                break;
            default:
                $image = false;
                break;
        }

        if (!$image) {
            return $file->store($directory, 'public');
        }

        // Resize image if it exceeds maxDimension
        if ($width > $maxDimension || $height > $maxDimension) {
            if ($width > $height) {
                $newWidth = $maxDimension;
                $newHeight = (int) ($height * ($maxDimension / $width));
            } else {
                $newHeight = $maxDimension;
                $newWidth = (int) ($width * ($maxDimension / $height));
            }

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency for PNG/GIF/WebP
            if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF || $type == IMAGETYPE_WEBP) {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resizedImage;
        }

        // Create directory if it doesn't exist
        $targetDir = Storage::disk('public')->path($directory);
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $outputPath = $targetDir . '/' . $filename;

        // Save and compress based on type
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $outputPath, $quality);
                break;
            case IMAGETYPE_PNG:
                // PNG compression: 0 (none) to 9 (max). Convert 0-100 quality to 0-9 compression scale.
                $pngQuality = (int) round((100 - $quality) / 10);
                $pngQuality = max(0, min(9, $pngQuality));
                imagepng($image, $outputPath, $pngQuality);
                break;
            case IMAGETYPE_GIF:
                imagegif($image, $outputPath);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagewebp')) {
                    imagewebp($image, $outputPath, $quality);
                } else {
                    imagejpeg($image, $outputPath, $quality); // fallback to jpg
                }
                break;
            default:
                imagejpeg($image, $outputPath, $quality);
                break;
        }

        imagedestroy($image);

        return $directory . '/' . $filename;
    }
}
