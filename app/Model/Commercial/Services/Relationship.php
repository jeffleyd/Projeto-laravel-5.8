<?php

namespace App\Model\Commercial\Services;

use App\Model\Commercial\Services\Analyze\ProcessAnalyze;
use App\Model\FinancyRPaymentRelationship;

/*
 * Essa class tem como intuito vincular as relações das solicitações.
 */
class Relationship extends ProcessAnalyze
{
    public function payment_relationship() {
        return $this->morphOne(FinancyRPaymentRelationship::class, 'module');
    }
}
