<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentItem extends Model
{
    protected $table = 'investment_items';
    protected $fillable = ['nama', 'keterangan', 'harga', 'urutan', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function promoDiscounts()
    {
        return $this->hasMany(InvestmentPromoDiscount::class, 'investment_item_id');
    }

    /**
     * Format harga sebagai string rupiah: Rp 1.500.000
     */
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
