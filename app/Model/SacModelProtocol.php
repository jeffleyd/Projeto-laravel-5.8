<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacModelProtocol extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_model_protocol';

    public function sacProductAir($type_line = 0)
    {

        if ($type_line == 1) {
            return $this->belongsTo(ProductAir::class, 'product_id', 'id')->where('residential', 1);
        }
        else if($type_line == 2){
            return $this->belongsTo(ProductAir::class, 'product_id', 'id')->where('commercial', 1);
        }
        else {
            return $this->belongsTo(ProductAir::class, 'product_id', 'id');
        }
    }

    public function scopeSacProductAirFilter($query, $type) {

        if ($type == 1) {

            return $query->whereHas('sacProductAir', function($q) {
                $q->where('residential', 1);
            });
        } else {

            return $query->whereHas('sacProductAir', function($q) {
                $q->where('commercial', 1);
            });

        }
    }
}
