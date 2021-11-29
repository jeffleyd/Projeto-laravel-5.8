<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductSubLevel1 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_sub_level_1';

    public function productSubLevel2() {
    
        return $this->belongsTo(ProductSubLevel2::class, 'product_sub_level_1_id', 'id');
    }
}
