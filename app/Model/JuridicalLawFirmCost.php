<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JuridicalLawFirmCost extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juridical_law_firm_cost';

    public function juridical_type_cost() {
        return $this->hasOne(JuridicalTypeCost::class, 'id', 'type_id');
    }

    public function juridical_law_firm() {
        return $this->belongsTo(JuridicalLawFirm::class, 'juridical_law_firm_id', 'id');
    }    

    public function juridical_law_firm_cost_attach() {
        return $this->hasMany(JuridicalLawFirmCostAttach::class, 'juridical_law_firm_cost_id', 'id');
    }
}
