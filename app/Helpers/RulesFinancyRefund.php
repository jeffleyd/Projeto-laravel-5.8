<?php

namespace App\Helpers;

use App\Model\Users;
use App\Model\LogAccess;
use App\Model\Countries;
use App\Model\FinancyRPayment;
use App\Model\FinancyRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Hash;
use App;
use Log;

class RulesFinancyRefund
{

  
    public function ModuleRulesfinancyPaymentAnalyze(Request $request, FinancyRPayment $r_payment, $type){
        Log::info('RulesFinancyRefund@ModuleRulesfinancyPaymentAnalyze not implemented');
    }

    public function ModuleRulesFinancyPaymentTransfer(Request $request, FinancyRPayment $r_payment, $model){
        
        if($r_payment){

            if ($request->amount != '0.00' or !empty($request->amount)) {
                
                $refund = FinancyRefund::find($r_payment->module_id);
                if ($refund) {

                    if ($refund->lending >= $r_payment->amount_liquid) {
                        $refund->lending = $request->amount;
                        $refund->save();

                        $r_payment->amount_liquid = $r_payment->amount_liquid - $request->amount;
                        $r_payment->save();
                    }
                }
            }
            
            
        }

        

    }

}