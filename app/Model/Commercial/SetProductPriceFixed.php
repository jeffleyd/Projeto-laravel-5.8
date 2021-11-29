<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class SetProductPriceFixed extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'set_product_price_fixed';
    protected $connection = 'commercial';
}
