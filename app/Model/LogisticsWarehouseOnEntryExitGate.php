<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticsWarehouseOnEntryExitGate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_warehouse_on_entry_exit_gate';
    
    protected $fillable = ['logistics_warehouse_id', 'logistics_entry_exit_gate_id'];
}
