<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Model\Services\Analyze\Model\RequestAnalyzeApprovers;
use App\Model\Services\Analyze\Model\RequestAnalyzeObservers;


class LogisticsWarehouse extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_warehouse';
	
	public function analyze_approv() {
        return $this->morphMany(RequestAnalyzeApprovers::class, 'analyze');
    }

    public function analyze_observ() {
        return $this->morphMany(RequestAnalyzeObservers::class, 'analyze');
    }

    public function logistics_warehouse_approv()
    {
        return $this->hasMany(LogisticsWarehouseApprov::class, 'warehouse_id', 'id');
    }

    public function logistics_entry_exit_gate() 
    {
        return $this->belongsToMany(LogisticsEntryExitGate::class, 'logistics_warehouse_on_entry_exit_gate', 'logistics_warehouse_id', 'logistics_entry_exit_gate_id');
    }

    public function logistics_warehouse_type_content() 
    {
        return $this->belongsToMany(LogisticsWarehouseTypeContent::class, 'logistics_warehouse_on_type_content', 'logistics_warehouse_id', 'logistics_warehouse_type_content_id');
    }

    public function logistics_warehouse_observers()
    {
        return $this->hasMany(LogisticsWarehouseObservers::class, 'warehouse_id', 'id');
    }
}
