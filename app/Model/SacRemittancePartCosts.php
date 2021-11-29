<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacRemittancePartCosts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_remittance_part_costs';

    public function sac_remittance_part_costs_historic()
    {
        return $this->hasMany(SacRemittancePartCostsHistoric::class, 'sac_remittance_part_costs_id', 'id');
    }
}
