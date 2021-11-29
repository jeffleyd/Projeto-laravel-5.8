<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_delivery';
    protected $connection = 'commercial';
}
