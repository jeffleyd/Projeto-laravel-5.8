<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacProtocolCosts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_protocol_costs';

    public function sac_protocol_costs_historic()
    {
        return $this->hasMany(SacProtocolCostsHistoric::class, 'sac_protocol_costs_id', 'id');
    }
}
