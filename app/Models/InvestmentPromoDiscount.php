<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPromoDiscount extends Model
{
    protected $table = 'investment_promo_discounts';
    protected $fillable = ['promo_id', 'investment_item_id', 'diskon_persen', 'keterangan'];

    public function promo()
    {
        return $this->belongsTo(InvestmentPromo::class, 'promo_id');
    }

    public function investmentItem()
    {
        return $this->belongsTo(InvestmentItem::class, 'investment_item_id');
    }
}
