<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratKeterangan;
use App\Models\SuratKeteranganSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SuratKeteranganController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeterangan::with('creator')->latest();

        if ($request->filled('jenis')) {
            $query->where('jenis_surat', $request->jenis);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_anak', 'like', "%{$search}%")
                  ->orWhere('nama_orang_tua', 'like', "%{$search}%");
            });
        }

        $suratList = $query->paginate(12);

        return view('surat-keterangan.index', compact('suratList'));
    }

    public function create()
    {
        $setting = SuratKeteranganSetting::first() ?? new SuratKeteranganSetting([
            'psikolog_nama' => 'Anggia Chrisanti, M.Psi, Psikolog',
            'psikolog_jabatan' => 'Psikolog',
            'psikolog_alamat' => 'Windelrio Townhouse, Blok A No. 9, Jl. Brigjen Saptadji Hadiprawira, Semplak, Bogor Barat, 16112',
            'psikolog_no_himpsi' => '20250719',
        ]);

        return view('surat-keterangan.create', compact('setting'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_surat' => 'required|in:cibi,abk',
            'psikolog_nama' => 'required|string|max:255',
            'psikolog_jabatan' => 'required|string|max:255',
            'psikolog_alamat' => 'required|string',
            'psikolog_no_himpsi' => 'required|string|max:255',
            'nama_anak' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nama_orang_tua' => 'required|string|max:255',
            'alamat' => 'required|string',
            'iq_score' => 'nullable|string|max:50',
            'paragraf_isi' => 'nullable|string',
            'tanggal_surat' => 'required|date',
            'tempat_surat' => 'required|string|max:255',
        ]);

        $data['created_by'] = Auth::id();

        $surat = SuratKeterangan::create($data);

        return redirect()->route('surat-keterangan.show', $surat)
            ->with('success', 'Surat Keterangan berhasil dibuat.');
    }

    public function show(SuratKeterangan $suratKeterangan)
    {
        $setting = SuratKeteranganSetting::first();

        $logoProsekar = $setting && $setting->logo_path 
            ? $this->getBase64ImageFromDisk($setting->logo_path) 
            : $this->getBase64ImageFromDisk('logos/LogoProsekarWIthText-removebg-preview.png');

        $signatureAmbu = $setting && $setting->signature_path 
            ? $this->getBase64ImageFromDisk($setting->signature_path) 
            : $this->getBase64ImageFromDisk('logos/ttd_Ambu-removebg-preview.png');

        return view('surat-keterangan.show', compact('suratKeterangan', 'logoProsekar', 'signatureAmbu', 'setting'));
    }

    public function edit(SuratKeterangan $suratKeterangan)
    {
        return view('surat-keterangan.edit', compact('suratKeterangan'));
    }

    public function update(Request $request, SuratKeterangan $suratKeterangan)
    {
        $data = $request->validate([
            'jenis_surat' => 'required|in:cibi,abk',
            'psikolog_nama' => 'required|string|max:255',
            'psikolog_jabatan' => 'required|string|max:255',
            'psikolog_alamat' => 'required|string',
            'psikolog_no_himpsi' => 'required|string|max:255',
            'nama_anak' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nama_orang_tua' => 'required|string|max:255',
            'alamat' => 'required|string',
            'iq_score' => 'nullable|string|max:50',
            'paragraf_isi' => 'nullable|string',
            'tanggal_surat' => 'required|date',
            'tempat_surat' => 'required|string|max:255',
        ]);

        $suratKeterangan->update($data);

        return redirect()->route('surat-keterangan.show', $suratKeterangan)
            ->with('success', 'Surat Keterangan berhasil diperbarui.');
    }

    public function destroy(SuratKeterangan $suratKeterangan)
    {
        $suratKeterangan->delete();

        return redirect()->route('surat-keterangan.index')
            ->with('success', 'Surat Keterangan berhasil dihapus.');
    }

    public function pdf(SuratKeterangan $suratKeterangan)
    {
        ini_set('memory_limit', '256M');
        set_time_limit(120);

        $setting = SuratKeteranganSetting::first();

        $logoProsekar = $setting && $setting->logo_path 
            ? $this->getBase64ImageFromDisk($setting->logo_path) 
            : $this->getBase64ImageFromDisk('logos/LogoProsekarWIthText-removebg-preview.png');

        $signatureAmbu = $setting && $setting->signature_path 
            ? $this->getBase64ImageFromDisk($setting->signature_path) 
            : $this->getBase64ImageFromDisk('logos/ttd_Ambu-removebg-preview.png');

        $pdf = Pdf::loadView('surat-keterangan.pdf', [
            'surat' => $suratKeterangan,
            'logoProsekar' => $logoProsekar,
            'signature' => $signatureAmbu,
            'setting' => $setting,
        ]);

        $pdf->setPaper('a4', 'portrait');

        $jenis = strtoupper($suratKeterangan->jenis_surat);
        $namaAnak = $this->safeFilename($suratKeterangan->nama_anak);
        $tanggal = $suratKeterangan->tanggal_surat->format('dmY');
        $filename = "SK_{$jenis}_{$namaAnak}_{$tanggal}.pdf";

        if (request()->boolean('preview')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /* 
     * Halaman Settings & Update Settings 
     */
    public function settings()
    {
        $setting = SuratKeteranganSetting::first();
        if (!$setting) {
            $setting = SuratKeteranganSetting::create([
                'psikolog_nama' => 'Anggia Chrisanti, M.Psi, Psikolog',
                'psikolog_jabatan' => 'Psikolog',
                'psikolog_alamat' => 'Windelrio Townhouse, Blok A No. 9, Jl. Brigjen Saptadji Hadiprawira, Semplak, Bogor Barat, 16112',
                'psikolog_no_himpsi' => '20250719',
            ]);
        }

        return view('surat-keterangan.settings', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        $setting = SuratKeteranganSetting::first();
        if (!$setting) {
            $setting = new SuratKeteranganSetting();
        }

        $request->validate([
            'psikolog_nama' => 'required|string|max:255',
            'psikolog_jabatan' => 'required|string|max:255',
            'psikolog_alamat' => 'required|string',
            'psikolog_no_himpsi' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        $setting->psikolog_nama = $request->psikolog_nama;
        $setting->psikolog_jabatan = $request->psikolog_jabatan;
        $setting->psikolog_alamat = $request->psikolog_alamat;
        $setting->psikolog_no_himpsi = $request->psikolog_no_himpsi;

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $setting->logo_path = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('signature')) {
            if ($setting->signature_path) {
                Storage::disk('public')->delete($setting->signature_path);
            }
            $setting->signature_path = $request->file('signature')->store('signatures', 'public');
        }

        $setting->save();

        return redirect()->route('surat-keterangan.settings')
            ->with('success', 'Pengaturan Surat Keterangan berhasil diperbarui.');
    }

    private function getBase64ImageFromDisk($path)
    {
        if (!$path) {
            return null;
        }

        // Try direct storage retrieval in case it's in public storage folder
        if (Storage::disk('public')->exists($path)) {
            $data = Storage::disk('public')->get($path);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
            $data = File::get($fullPath);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        return null;
    }

    private function safeFilename($string)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $string));
    }
}
