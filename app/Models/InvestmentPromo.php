<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPromo extends Model
{
    protected $table = 'investment_promos';
    protected $fillable = ['label', 'deskripsi', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];

    public function discounts()
    {
        return $this->hasMany(InvestmentPromoDiscount::class, 'promo_id');
    }

    public function discountItems()
    {
        return $this->discounts()->with('investmentItem');
    }
}
