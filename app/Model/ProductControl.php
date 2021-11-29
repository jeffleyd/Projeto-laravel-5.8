<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductControl extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_control';

    public function ProductAir()
    {
        return $this->hasOne(ProductAir::class, 'id', 'product_id');
    }

    public function scopeProductAirFilter($query, $type, $item) {

        if ($type == 1) {

            return $query->whereHas('ProductAir', function ($q) use ($item) {
                $q->where('model', 'like', '%'. $item .'%');
            });
        } else if ($type == 2) {
            return $query->whereHas('ProductAir', function ($q) use ($item) {
                $q->where('product_sub_level_1_id', $item);
            });
        } else if ($type == 3) {
            return $query->whereHas('ProductAir', function ($q) use ($item) {
                $q->where('product_sub_level_2_id', $item);
            });
        } else if ($type == 4) {
            return $query->whereHas('ProductAir', function ($q) use ($item) {
                $q->where('product_sub_level_3_id', $item);
            });
        }
        
    }
	
	public function product_parts() {
        return $this->belongsTo(ProductParts::class, 'id', 'product_control_id');
    }
}
