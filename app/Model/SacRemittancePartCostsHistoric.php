<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacRemittancePartCostsHistoric extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_remittance_part_costs_historic';

    protected $appends = [
        'user_historic'
    ];

    public function users()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }

    public function getUserHistoricAttribute() 
    {
        return $this->users->short_name;
    }
}
