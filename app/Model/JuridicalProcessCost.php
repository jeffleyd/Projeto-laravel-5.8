<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JuridicalProcessCost extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juridical_process_cost';

    public function juridical_process_cost_attach() {
        return $this->hasMany(JuridicalProcessCostAttach::class, 'juridical_process_cost_id', 'id');
    }

    public function juridical_type_cost() {
        return $this->hasOne(JuridicalTypeCost::class, 'id', 'type_id');
    }

    public function juridical_process() {
        return $this->belongsTo(JuridicalProcess::class, 'juridical_process_id', 'id');
    }        
}
