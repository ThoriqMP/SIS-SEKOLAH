<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingSetting extends Model
{
    protected $table = 'billing_settings';

    protected $fillable = [
        'invoice_generation_day',
        'invoice_due_day',
    ];

    protected $casts = [
        'invoice_generation_day' => 'integer',
        'invoice_due_day' => 'integer',
    ];
}
