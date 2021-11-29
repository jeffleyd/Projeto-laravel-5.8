<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WarehouseEntryExitItems extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'warehouse_entry_exit_items';

    protected $fillable = [
        'is_entry_exit', 'warehouse_id', 'type_request', 'request_id', 'code', 'description', 'quantity'
    ];

    public function logistics_entry_exit_requests() {
        return $this->belongsTo(LogisticsEntryExitRequests::class, 'request_id', 'id');
    }

    public function logistics_warehouse() {
        return $this->belongsTo(LogisticsWarehouse::class, 'warehouse_id', 'id');
    }
}