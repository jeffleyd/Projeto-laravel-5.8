<?php

namespace App\Helpers;

use App\Model\Users;
use App\Model\LogAccess;
use App\Model\Countries;
use App\Model\FinancyRPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Hash;
use App;
use Log;

class RulesSacOsProtocol
{
    
    public function ModuleRulesfinancyPaymentAnalyze(Request $request, FinancyRPayment $r_payment, $type){
        Log::info('RulesSacOsProtocol@ModuleRulesfinancyPaymentAnalyze not implemented');
    }

    public function ModuleRulesFinancyPaymentTransfer(Request $request, FinancyRPayment $r_payment, $model){
        Log::info('RulesSacOsProtocol@ModuleRulesFinancyPaymentTransfer not implemented');
    }


}