<?php

namespace App\Helpers;

use App\Model\UserFinancy;
use App\Model\Users;
use App\Model\LogAccess;
use App\Model\FinancyAccountability;
use App\Model\FinancyRPayment;
use App\Model\FinancyAccountabilityReceiverHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Hash;
use App;
use Log;

class RulesFinancyAccountability
{


    public function ModuleRulesfinancyPaymentAnalyze(Request $request, FinancyRPayment $r_payment, $type){

        if($r_payment){

            //Após Executar as Regras do Modulo FInanceiro, executa as Regras da Prestação de Contas
            $model = FinancyAccountability::find($r_payment->module_id);

            if($model){

                if ($type == 1) {//caso aprovado

                    if($r_payment->pres_approv == 1){ //altera somente se o presidente aprova

                        $is_paid = ($model->total_liquid >= 0.00) ? 1 : 0;

                        $model->has_analyze = 0;
                        $model->is_approv = 1;
                        $model->is_reprov = 0;
                        $model->is_paid = $is_paid;
                        $model->save();

                        $r_payment->is_paid = $is_paid;
						if (!$is_paid) {
							$r_payment->amount_liquid = abs($model->total_liquid);
						}
						$r_payment->due_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + 8 day'));
                        $r_payment->save();

                        $model->lending->count_notify=0;
                        $model->lending->save();

                        if ($is_paid == 1) {
                            $this->addCreditForLending($model);
                        }

                    }

                }elseif ($type == 2){//caso rerovado
                    $model->has_analyze = 0;
                    $model->is_approv = 0;
                    $model->is_reprov = 1;
                    $model->save();
                }

                //recalcular o valor total da tabela FinancyUsersDebtors;
                $rules = new RulesFinancyLending();
                $rules->atualizaDividaUsuario($model->r_code);

            }

        }

    }

    // Adição de $model parametro
    public function ModuleRulesFinancyPaymentTransfer(Request $request, FinancyRPayment $r_payment, $model){

        DB::beginTransaction(); //inicio da transação no SGBD

        $reembolso_prestacao_contas = new FinancyAccountabilityReceiverHistory;
        $reembolso_prestacao_contas->financy_accountability_id = $model->id;
        $reembolso_prestacao_contas->lending_request_id = $model->lending_request_id;
        $reembolso_prestacao_contas->request_payment_id = $model->payment_request_id;

        $reembolso_prestacao_contas->request_payment_file = $r_payment->receipt;
        $reembolso_prestacao_contas->total = $r_payment->amount_liquid;
        $reembolso_prestacao_contas->p_method = $r_payment->p_method;
        $reembolso_prestacao_contas->date = $r_payment->updated_at;

        $is_saved = $reembolso_prestacao_contas->save();
        if(!$is_saved){
            DB::rollBack();
            abort(400,"Erro ao Salvar no banco de dados");
        }
        $r_code = \Session::get('r_code');

        $log_history = "Pagamento de (".$r_payment->amount_liquid.") em Reembolso referente ao Prestação de Contas(#".$model->id.") do Emprestimo(#".$model->lending_request_id.")";
        $log_Observation=array(
            'financy_lending_id' => $model->lending_request_id,
            'model_class_origin'=>FinancyAccountabilityReceiverHistory::class,
            'model_id'=>$reembolso_prestacao_contas->id,
            'r_code'=> $r_code,
            'description'=>$log_history,
            'new_model_values'=>$reembolso_prestacao_contas->toJSON(),
        );
        LogObservationHistory($log_Observation);
        LogSystem($log_history, $r_code);
        $this->addCreditForLending($model);
        
        //recalcular o valor total da tabela FinancyUsersDebtors;
        $rules = new RulesFinancyLending();
        $rules->atualizaDividaUsuario($model->r_code);

        DB::commit();
    }
	
    private function addCreditForLending($model) {

        // Verificar a linha de crédito usada e faz o abatimento.
        $uFinancy = UserFinancy::where('r_code', $model->r_code)->first();
        if ($uFinancy) {
            if ($uFinancy->used_credit > 0.00) {
                $operation = $uFinancy->used_credit - $model->total;
                if ($operation > 0.00)
                    $uFinancy->used_credit = $operation;
                else
                    $uFinancy->used_credit = 0.00;

                $uFinancy->save();
            }
        }
    }


}
