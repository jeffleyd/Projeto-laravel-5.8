<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacBuyParts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_buy_parts';

    public function SacBuyPart() {   
        return $this->belongsTo(SacBuyPart::class, 'sac_buy_part_id', 'id');
    }

    public function SacPart() {
        return $this->hasOne(Parts::class, 'id', 'part');
    }    

    public function SacProductAir() {
        return $this->hasOne(ProductAir::class, 'id', 'model');
    }

    public function scopeSacPartModelFilter($query, $part)
    {
        if($part == 0) {
            return $this->SacProductAir->model != null ? $this->SacProductAir->model: '';
        } else {
            return $this->model;
        }
    }

    public function scopeSacPartCodeFilter($query, $not_part, $part)
    {
        if($not_part == 0) {
            return $this->SacPart->description != null ? $this->SacPart->description: '-';
        } else {
            return $this->part;
        }
    }
    
    public function scopeSacBuyPartCode($query) {
        $q = $this->SacBuyPart;
        if($q) {
            return $q->code;
        } else {
            return ' - ';
        }
    }

    public function scopeSacPurchaseOrderFilter($query, $val_filter) {
        return $query->whereHas('SacBuyPart', function ($q) use ($val_filter) {
            $q->where('code', $val_filter);
        });
    }
}
