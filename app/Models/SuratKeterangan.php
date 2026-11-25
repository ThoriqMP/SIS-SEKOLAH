<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeterangan extends Model
{
    protected $table = 'surat_keterangan';

    protected $fillable = [
        'jenis_surat',
        'psikolog_nama',
        'psikolog_jabatan',
        'psikolog_alamat',
        'psikolog_no_himpsi',
        'nama_anak',
        'tanggal_lahir',
        'nama_orang_tua',
        'alamat',
        'iq_score',
        'paragraf_isi',
        'tanggal_surat',
        'tempat_surat',
        'created_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_surat' => 'date',
    ];

    /**
     * User yang membuat surat keterangan ini.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Label jenis surat.
     */
    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis_surat) {
            'cibi' => 'SK CIBI',
            'abk' => 'SK ABK',
            default => strtoupper($this->jenis_surat),
        };
    }
}
