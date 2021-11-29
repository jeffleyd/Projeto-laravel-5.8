<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Parts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parts';

    public function ProductParts1()
    {
        return $this->hasOne(ProductParts::class, 'part_id', 'id');
    }

    public function ProductControl()
    {
        return $this->belongsToMany(ProductControl::class, 'product_parts', 'part_id', 'product_control_id');
    }

    public function scopeProductControlFilter($query, $type, $item) {

        return $query->whereHas('ProductControl', function ($q) use ($type, $item) {
            $q->ProductAirFilter($type, $item);
        });
    }

}
