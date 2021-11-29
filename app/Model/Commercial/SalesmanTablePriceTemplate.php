<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class SalesmanTablePriceTemplate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'salesman_table_price_template';
    protected $connection = 'commercial';

}
