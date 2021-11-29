<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogisticsWarehouseTypeContent extends Model
{
	use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_warehouse_type_content';
	protected $dates = ['deleted_at'];
}
