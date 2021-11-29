<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetProductSave extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'set_product_save';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];
    protected $casts = ['collect' => 'array'];
}
