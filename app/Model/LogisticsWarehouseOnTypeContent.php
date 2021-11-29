<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticsWarehouseOnTypeContent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_warehouse_on_type_content';
    
    protected $fillable = ['logistics_warehouse_id', 'logistics_warehouse_type_content_id'];
}
