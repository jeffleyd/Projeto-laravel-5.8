<?php

namespace App\Services\Departaments\Administration\Refund;

use App;
use App\Jobs\SendMailJob;
use App\Model\FinancyRefundItem;
use App\Model\UserFinancy;
use App\Model\Users;
use App\Services\Departaments\Administration\Refund\Rules\RefundRules;
use Illuminate\Http\Request;
use App\Services\Departaments\Helpers;
use App\Helpers\Financy\Payment;

use App\Services\Departaments\Interfaces\Analyze;

class Refund implements Analyze
{
    use RefundRules;
    use RefundTrait;
    use Helpers;

    public $model;
    public $request;

    public function __construct($model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    public function startAnalyze(): array
    {

        $arr_approv = [];
        $approvers = $this->model->rtd_approvers();

        if (!$approvers->count())
            throw new \Exception('Não é possível iniciar aprovação dessa solicitação, pois não tem aprovadores configurados.');

        $arr_response = $this->bossToBoss(
            $arr_approv,
            $this->model->users->immediates,
            $this->model->rtd_status['last_version'] + 1
        );
		
        $arr_approv = $arr_response['arr_approv'];
        $last_pos = $arr_response['last_position'];

        foreach ($approvers as $key) {
            array_push($arr_approv,
                [
                    'version' => $this->model->rtd_status['last_version'] + 1,
                    'r_code' => $key->r_code,
                    'position' => $key->position + $last_pos,
                    'mark' => $key->mark
                ]
            );
        }

        foreach ($this->model->users->immediates as $user) {
            $this->sendEmailAnalyze($this->model, $user->email);
        }

        $this->sendEmailMySelf($this->model, $this->model->users->email);

        return $arr_approv;
    }

    public function approvAnalyze() {

        if ($this->model->rtd_status['status']['code'] == 3) {

            $users_approv = $this->model->rtd_status['status']['validation'];

            foreach ($users_approv as $user) {
                $this->sendEmailAnalyze($this->model, $user->email);
            }
        } else if ($this->model->rtd_status['status']['code'] == 2) {

            $a_bank = UserFinancy::where('r_code', $this->model->request_r_code)->first();
            if ($this->model->recipient_r_code) {
                $a_bank = UserFinancy::where('r_code', $this->model->recipient_r_code)->first();
            }

            if (!$a_bank)
                throw new \Exception("Não foi possível aprovar, o usuário não tem uma conta de banco.");

            $this->model->is_approv = 1;
            $this->model->has_analyze = 0;
            $this->model->save();

            $refund_itens = FinancyRefundItem::where('financy_refund_id', $this->model->id)->groupBy('type')->get();
            $arr1 = array();

            foreach ($refund_itens as $itns) {
                $arr1[] = refundType($itns->type);
            }

            if ($this->model->recipient_r_code) {
                $rec = Users::where('r_code', $this->model->recipient_r_code)->first();
            } else {
                $rec = Users::where('r_code', $this->model->request_r_code)->first();
            }

            if ($this->model->lending != 0.00) {
                if ($this->model->lending >= $this->model->total) {
                    $optional = nl2br($this->model->description ."Empréstimo ativo de: R$ ". number_format($this->model->lending, 2, ",", ".") ." \n sendo assim resta eu prestar conta de: R$ ". number_format(abs($this->model->lending - $this->model->total), 2, ",", "."));
                    $amount_liquid = 0.00;
                } else {
                    $optional = nl2br($this->model->description ." Empréstimo ativo de: R$ ". number_format($this->model->lending, 2, ",", ".") ." \n sendo assim resta a gree me pagar: R$ ". number_format(abs($this->model->lending - $this->model->total), 2, ",", "."));
                    $amount_liquid = number_format(abs($this->model->lending - $this->model->total), 2, ",", ".");
                }
            } else {
                $optional = $this->model->description;
                $amount_liquid = $this->model->total;
            }


            $newPayment = [
                'agency' => $a_bank->agency,
                'account' => $a_bank->account,
                'bank' => $a_bank->bank,
                'identity' => $a_bank->identity,
                'cnpj' => '',
                'description' => implode(", ", $arr1).'. '. $this->model->description,
                'request_r_code' => $this->model->request_r_code,
                'request_category' => 7,
                'nf_nmb' => 'CONTABILIZADO',
                'amount_gross' => $this->model->total,
                'amount_liquid' => $amount_liquid,
                'recipient' => $rec->full_name,
                'recipient_r_code' => $rec->r_code,
                'due_date' => date('Y-m-d', strtotime(date('Y-m-d'). ' + 10 day')),
                'p_method' => 2,
                'module' => [
                    'type' => 'App\Model\FinancyRefund',
                    'id' => $this->model->id
                ],
                'optional' => $optional,
            ];

            $payment = new Payment();
            $p_response = $payment->newPayment($newPayment);

            $this->sendEmailApproved($this->model, $p_response);
        }

        return 'Solicitação aprovado com sucesso';
    }

    public function reprovAnalyze() {

        $this->model->is_reprov = 1;
        $this->model->has_analyze = 0;
        $this->model->save();

        $this->sendEmailReproved($this->model);
        return 'Solicitação reprovada com sucesso';
    }

    public function suspendedAnalyze() {

        $this->sendEmailSuspended($this->model, $this->request);
        return 'Solicitação suspensa com sucesso';
    }

    public function revertAnalyze() {

        $users_approv = $this->model->rtd_analyze->where('position', $this->request->position);
        foreach ($users_approv as $key) {
            $this->sendEmailAnalyze($this->model, $key->users->email);
        }

        return 'Solicitação revertida com sucesso';
    }

    public function approvNowAnalyze() {
        return 'Solicitação aprovada com sucesso';
    }
}

