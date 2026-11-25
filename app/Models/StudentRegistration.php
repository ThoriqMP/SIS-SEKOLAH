<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    protected $table = 'student_registrations';

    protected $fillable = [
        'nomor_pendaftaran', 'email', 'tahun_pelajaran', 'status', 'user_id',
        'jenjang', 'jenis_pendaftar', 'nama_sekolah_sebelumnya',
        'skema_belajar', 'biaya_uang_gedung', 'biaya_spp', 'biaya_kegiatan_semester',
        'mengajukan_keringanan', 'status_keringanan',
        'persentase_keringanan_diajukan', 'persentase_keringanan_disetujui', 'catatan_keringanan_admin',
        'password_hash',
        // A
        'nama_lengkap', 'nama_panggilan', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'anak_ke', 'agama', 'golongan_darah',
        'berat_badan', 'tinggi_badan', 'no_telpon_wa', 'alamat_rumah', 'tinggal_dengan',
        // B
        'asal_sekolah', 'alamat_sekolah', 'nama_kepala_sekolah', 'nisn',
        'status_sekolah', 'sistem_pendidikan', 'lama_belajar',
        // C Ayah
        'nama_ayah', 'ttl_ayah', 'pekerjaan_ayah', 'pendidikan_ayah',
        'agama_ayah', 'hp_ayah', 'alamat_ayah',
        // C Ibu
        'nama_ibu', 'ttl_ibu', 'pekerjaan_ibu', 'pendidikan_ibu',
        'agama_ibu', 'hp_ibu', 'alamat_ibu',
        // Meta
        'submitted_at', 'reviewed_at', 'reviewed_by', 'catatan_admin',
    ];

    protected $casts = [
        'tanggal_lahir'        => 'date',
        'submitted_at'         => 'datetime',
        'reviewed_at'          => 'datetime',
        'mengajukan_keringanan' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function financialData()
    {
        return $this->hasOne(RegistrationFinancialData::class, 'student_registration_id');
    }

    public function vehicleAssets()
    {
        return $this->hasMany(RegistrationVehicleAsset::class, 'student_registration_id');
    }

    /**
     * Hitung total estimasi biaya bersih setelah potongan disetujui (atau diajukan jika belum disetujui).
     */
    public function calculateFinalFees(): array
    {
        $discountPct = $this->persentase_keringanan_disetujui ?? ($this->mengajukan_keringanan ? $this->persentase_keringanan_diajukan : 0);
        if ($this->status_keringanan === 'rejected') {
            $discountPct = 0;
        }

        $uangGedungFinal = $this->biaya_uang_gedung * (1 - ($discountPct / 100));
        $sppFinal = $this->biaya_spp * (1 - ($discountPct / 100));
        $kegiatanFinal = $this->biaya_kegiatan_semester * (1 - ($discountPct / 100));

        return [
            'discount_percent'    => $discountPct,
            'uang_gedung'         => $uangGedungFinal,
            'spp_bulanan'         => $sppFinal,
            'kegiatan_semester'   => $kegiatanFinal,
            'total_investasi_awal' => $uangGedungFinal + $sppFinal + $kegiatanFinal,
        ];
    }

    /**
     * Generate nomor pendaftaran otomatis: REG-YYYY-NNNN
     */
    public static function generateNomor(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)->lockForUpdate()->count();
        return 'REG-' . $year . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu Review',
            'reviewed' => 'Sedang Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'yellow',
            'reviewed' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            default    => 'gray',
        };
    }
}
