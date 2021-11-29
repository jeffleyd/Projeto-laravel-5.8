<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderSalesAttach extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_sales_attach';
    protected $connection = 'commercial';
}
