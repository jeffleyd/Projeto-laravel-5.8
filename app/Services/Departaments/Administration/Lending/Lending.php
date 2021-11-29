<?php 

namespace App\Services\Departaments\Administration\Lending;

use App;
use App\Services\Departaments\Helpers;
use App\Services\Departaments\Interfaces\Analyze;
use App\Services\Departaments\Administration\Lending\Rules\LendingRules;
use App\Services\Departaments\Administration\Lending\LendingTrait;
use App\Helpers\Financy\Payment;
use App\Model\UserFinancy;

use Illuminate\Http\Request;

class Lending implements Analyze
{
    use LendingRules;
    use LendingTrait;
    use Helpers;

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
            $this->model->user->immediates,
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
        foreach ($this->model->user->immediates as $immediate) {
            $this->sendEmailAnalyze($this->model, $immediate, $this->request);
        }

        $this->sendEmailMySelf($this->model, $this->model->user->email);
        return $arr_approv;
    }

    public function approvAnalyze() {

        if ($this->model->rtd_status['status']['code'] == 3) {

            $users_approv = $this->model->rtd_status['status']['validation'];
            foreach ($users_approv as $approv) {
                $this->sendEmailAnalyze($this->model, $approv->users, $this->request);
            }
            
        } else if ($this->model->rtd_status['status']['code'] == 2) {

            $a_bank = UserFinancy::where('r_code', $this->model->r_code)->first();
            if (!$a_bank)
                throw new \Exception("Não foi possível aprovar, o usuário não tem uma conta de banco.");

            $this->model->is_approv = 1;
            $this->model->has_analyze = 0;
            $this->model->save();

            $newPayment = [
                'agency' => $a_bank->agency,
                'account' => $a_bank->account,
                'bank' => $a_bank->bank,
                'identity' => $a_bank->identity,
                'cnpj' => '',
                'description' => $this->model->description,
                'request_r_code' => $this->model->r_code,
                'request_category' => 7,    
                'nf_nmb' => 'CONTABILIZADO',
                'amount_gross' => $this->model->amount,
                'amount_liquid' => $this->model->amount,
                'recipient' => $this->model->user->full_name,
                'recipient_r_code' => $this->model->user->r_code,
                'due_date' => date('Y-m-d', strtotime(date('Y-m-d'). ' + 10 day')),
                'p_method' => $this->model->p_method,
                'module' => [
                    'type' => 'App\Model\FinancyLending',
                    'id' => $this->model->id
                ],
                'optional' => null,
            ];

            $payment = new Payment();
            $p_response = $payment->newPayment($newPayment);

            $this->sendEmailApproved($this->model, $p_response, $a_bank);
        }

        return 'Empréstimo aprovado com sucesso';
    }

    public function reprovAnalyze() {

        $a_bank = UserFinancy::where('r_code', $this->model->r_code)->first();
            
        $this->model->is_reprov = 1;
        $this->model->has_analyze = 0;
        $this->model->save();

        $this->sendEmailReproved($this->model, $this->request, $a_bank);
        return 'Empréstimo reprovado com sucesso';
    }   
    
    public function suspendedAnalyze() {

        $this->model->has_suspended = 1;
        $this->model->save();

        $this->sendEmailSuspended($this->model, $this->request);
        return 'Empréstimo suspenso com sucesso';
    }   
    
    public function revertAnalyze() {

        $users_approv = $this->model->rtd_analyze->where('position', $this->request->position);
        foreach ($users_approv as $key) {
            $this->sendEmailAnalyze($this->model, $key->users, $this->request);
        }
        return 'Solicitação revertida com sucesso';
    }

    public function approvNowAnalyze() {

    }    
}    


