<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationVehicleAsset extends Model
{
    protected $table = 'registration_vehicle_assets';

    protected $fillable = [
        'student_registration_id',
        'jenis_kendaraan',
        'merk_tipe',
        'tahun',
    ];

    public function registration()
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }
}
