<?php

namespace App\Helpers;

use App\Model\Users;
use App\Model\LogAccess;
use App\Model\Countries;
use App\Model\Regions;
use App\Model\FinancyLending;
use App\Model\FinancyRefund;
use App\Model\SacOsProtocol;
use App\Model\FinancyAccountability;
use App\Model\FinancyRPayment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Hash;
use App;
use Log;

class ModuleRules
{

    protected $instance;
    private $modules;
    private $type;

    public function __construct($type)
    {
        $this->instance = null;
        $this->type = $type;

        $this->modules = [
            'App\Model\FinancyLending' => ['rule_class'=>new RulesFinancyLending(), 'model_class'=>FinancyLending::class],
            'App\Model\FinancyRefund' => ['rule_class'=>new RulesFinancyRefund(), 'model_class'=>FinancyRefund::class],
            'App\Model\FinancyAccountability' => ['rule_class'=>new RulesFinancyAccountability(), 'model_class'=>FinancyAccountability::class],
        
        ];

        if(array_key_exists($type, $this->modules)){
            $this->instance = $this->modules[$type];
        }
    }

    public function getModelClass(){
        return $this->instance['model_class'];
    }

    public function getRuleClass(){
        return $this->instance['rule_class'];
    }
    
    /**
     * ModuleRulesfinancyPaymentAnalyze function
     *
     * @param Request $request
     * @param FinancyRPayment $r_payment
     * @param [type] $type
     * @return void
     * 
     * Esta função executa uma regra de acordo com o modulo que originou o Pagamento
     */
    public function ModuleRulesfinancyPaymentAnalyze(Request $request, FinancyRPayment $r_payment, $type){
        if($this->instance != null){
            return $this->instance['rule_class']->ModuleRulesfinancyPaymentAnalyze($request, $r_payment, $type);
        }
    }

    // Adição de parâmetro $model
    public function ModuleRulesFinancyPaymentTransfer(Request $request, FinancyRPayment $r_payment, $model){
        if($this->instance != null){
            return $this->instance['rule_class']->ModuleRulesFinancyPaymentTransfer($request, $r_payment, $model);
        }
    }


}