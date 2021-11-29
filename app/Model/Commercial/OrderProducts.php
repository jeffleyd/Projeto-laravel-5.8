<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_products';
    protected $connection = 'commercial';

    public function setProduct() {
        return $this->hasOne(SetProduct::class, 'id', 'set_product_id');
    }
}
