<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FinancyRPaymentRelationship extends model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financy_r_payment_relationship';
	protected $fillable = [
        'module_id',
        'module_type',
        'financy_r_payment_id'
    ];

}
