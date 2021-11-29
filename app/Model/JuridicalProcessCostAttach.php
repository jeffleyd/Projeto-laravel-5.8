<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JuridicalProcessCostAttach extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juridical_process_cost_attach';

    public function juridical_process_cost() {
        return $this->belongsTo(JuridicalProcessCost::class, 'juridical_process_cost_id', 'id');
    }
}
