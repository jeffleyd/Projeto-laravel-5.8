<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class SaleVerificationErrors extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sale_verification_errors';
    protected $connection = 'commercial';
	
	public function sale_verification_client_completed() {
        return $this->belongsTo(SaleVerificationClientCompleted::class, 'sale_verification_client_completed_id', 'id');
    }
}
