<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class SetProductAdjust extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'set_product_adjust';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];
}
