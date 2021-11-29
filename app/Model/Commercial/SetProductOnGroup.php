<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetProductOnGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'set_product_on_group';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];
}
