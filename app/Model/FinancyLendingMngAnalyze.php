<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FinancyLendingMngAnalyze extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financy_lending_mng_analyze';
	
	public function users() {
        return $this->hasOne(users::class, 'r_code', 'r_code');
    }
}
