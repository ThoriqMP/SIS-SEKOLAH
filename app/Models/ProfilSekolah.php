<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'logo_path',
        'bank_nama',
        'no_rekening',
        'atas_nama',
        'pimpinan_nama',
        'signature_path',
        'kepala_sekolah_nama',
        'kepala_sekolah_signature_path',
        'semester_ganjil_start_month',
        'semester_genap_start_month',
    ];

    /**
     * Relasi ke invoice
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'id_sekolah');
    }
}
