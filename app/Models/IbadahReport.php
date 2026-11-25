<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbadahReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_report_id',
        'niat_subuh',
        'niat_dzuhur',
        'niat_ashar',
        'niat_maghrib',
        'niat_isya',
        'iftitah',
        'alfatihah',
        'surat_pendek',
        'rukuk',
        'itidal',
        'sujud',
        'duduk_antara_sujud',
        'tasyahud_awal',
        'tasyahud_akhir',
        'additional_grades'
    ];

    protected $casts = [
        'additional_grades' => 'array'
    ];

    public function studentReport()
    {
        return $this->belongsTo(StudentReport::class);
    }
}

