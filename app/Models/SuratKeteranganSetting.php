<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeteranganSetting extends Model
{
    protected $table = 'surat_keterangan_settings';

    protected $fillable = [
        'psikolog_nama',
        'psikolog_jabatan',
        'psikolog_alamat',
        'psikolog_no_himpsi',
        'logo_path',
        'signature_path',
    ];
}
