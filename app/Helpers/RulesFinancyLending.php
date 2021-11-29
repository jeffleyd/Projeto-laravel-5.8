<?php

namespace App\Helpers;

use App\Model\Users;
use App\Model\LogAccess;
use App\Model\Countries;
use App\Model\FinancyRPayment;
use App\Model\FinancyLending;
use App\Model\FinancyUsersDebtors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Hash;
use App;
use Log;

class RulesFinancyLending
{


    public function ModuleRulesfinancyPaymentAnalyze(Request $request, FinancyRPayment $lending, $type){
        Log::info('RulesFinancyLending@ModuleRulesfinancyPaymentAnalyze not implemented');
    }

    public function ModuleRulesFinancyPaymentTransfer(Request $request, FinancyRPayment $r_payment, $model){

        if($r_payment){
            $this->atualizaDividaUsuario($r_payment->recipient_r_code);
            \App\Jobs\FinancyAlertDebtor::dispatch($r_payment->module_id)->delay(now()->addDays(30));
        }

    }

    public function atualizaDividaUsuario($user_r_code){
        DB::beginTransaction(); //inicio da transação no SGBD

        $users_debt = FinancyUsersDebtors::where('r_code', $user_r_code)->first();
        if(!$users_debt){
            $users_debt = new FinancyUsersDebtors;
            $users_debt->r_code = $user_r_code;

            $is_saved = $users_debt->save();

            $r_code = \Session::get('r_code');
            $log_history = "Sistema criou um cadastro de divida(#".$users_debt->id.") para o usuario(#".$user_r_code.")";
            $log_Observation=array(
                'model_class_origin'=>FinancyUsersDebtors::class,
                'model_id'=>$users_debt->id,
                'r_code'=> $r_code,
                'description'=>$log_history,
                'new_model_values'=>$users_debt->toJSON(),
            );
            LogObservationHistory($log_Observation);
            LogSystem($log_history, $r_code);

            if(!$is_saved){
                DB::rollBack();
                abort(400,"Erro ao Salvar no banco de dados");
            }
        }
        
        $old_users_debt = $users_debt->toJSON();
        $old_value = $users_debt->balance_due;

        $lendings = $users_debt->lendings;
        foreach ($lendings as $lending){

            if( !$lending->isPending() ){
                $lending->is_accountability_paid = 1;
                $is_saved = $lending->save();
                if(!$is_saved){
                    DB::rollBack();
                    abort(400,"Erro ao Salvar no banco de dados");
                }

                $r_code = \Session::get('r_code');
                $log_history = "Colaborador finalizou sua prestação de contas do Emprestimo (".$lending->id.")";
                $log_Observation=array(
                    'financy_lending_id' => $lending->id,
                    'model_class_origin'=>FinancyLending::class,
                    'model_id'=>$lending->id,
                    'r_code'=> $r_code,
                    'description'=>$log_history,
                    'new_model_values'=>$lending->toJSON(),
                );
                LogObservationHistory($log_Observation);
                LogSystem($log_history, $r_code);
            }
            
        }
        
        $total_emprestimos = $users_debt->getTotalEmprestimo();
        $total_pago =  $users_debt->getTotalPago(1);
        $total_analise =  $users_debt->getTotalPago(2);
        $total_devedor =  $users_debt->getTotalPendente();

        if($total_emprestimos == $total_pago){
            $users_debt->total_lendings = 0;
            $users_debt->total_paid = 0;
        }else{
            $users_debt->total_lendings = $total_emprestimos;
            $users_debt->total_paid = $total_pago;
        }
        $users_debt->total_analyze = $total_analise;
        $users_debt->balance_due = $total_devedor;


        $is_saved = $users_debt->save();
        
        $log_history = "Atualização cadastro de divida(#".$users_debt->id.") do usuario(#".$user_r_code."). Total a Pagar Anterior (".$old_value."), Total a Pagar Atualizado (".$total_devedor.")";
        $log_Observation=array(
            'model_class_origin'=>FinancyUsersDebtors::class,
            'model_id'=>$users_debt->id,
            'r_code'=> \Session::get('r_code'),
            'description'=>$log_history,
            'old_model_values'=>$old_users_debt,
            'new_model_values'=>$users_debt->toJSON(),
        );
        LogObservationHistory($log_Observation);
        
        if(!$is_saved){
            DB::rollBack();
            abort(400,"Erro ao Salvar no banco de dados");
        }

        DB::commit();
        return $users_debt;
    }


}