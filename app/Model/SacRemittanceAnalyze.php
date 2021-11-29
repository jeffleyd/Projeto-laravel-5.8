<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacRemittanceAnalyze extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_remittance_analyze';
   
    public function users() {   
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }
}
