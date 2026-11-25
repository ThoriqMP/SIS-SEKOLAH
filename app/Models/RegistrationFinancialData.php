<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationFinancialData extends Model
{
    protected $table = 'registration_financial_data';

    protected $fillable = [
        'student_registration_id',
        'ayah_tempat_kerja',
        'ayah_pekerjaan',
        'ibu_tempat_kerja',
        'ibu_pekerjaan',
        'penghasilan_gabungan',
        'jumlah_tanggungan',
        'daya_listrik_watt',
    ];

    public function registration()
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }
}
