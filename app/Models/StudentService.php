<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentService extends Model
{
    protected $table = 'student_services';

    protected $fillable = [
        'id_siswa',
        'id_layanan',
        'nominal',
        'bulan',
        'tahun',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'bulan' => 'integer',
        'tahun' => 'integer',
    ];

    /**
     * Relationship to Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    /**
     * Relationship to Layanan (Service)
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }
}
