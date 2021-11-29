<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticsWarehouseObservers extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_warehouse_observers';
    
    protected $fillable = ['warehouse_id', 'r_code'];

    public function users() {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }
}
