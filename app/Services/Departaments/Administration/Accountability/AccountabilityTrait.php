<?php

namespace App\Services\Departaments\Administration\Accountability;

use App;
use App\Model\Users;
use App\Model\FinancyAccountability;
use App\Jobs\SendMailJob;
use Illuminate\Support\Facades\Session;
use App\Services\Departaments\Helpers;

Trait AccountabilityTrait
{
    use Helpers;

    public function accountabilitySend($accountability, $request) {

        $log_history = "Colaborador enviou prestação de contas(#".$accountability->id.") para análise.";
        $log_Observation=array(
            'financy_lending_id' => $accountability->lending_request_id,
            'model_class_origin'=>FinancyAccountability::class,
            'model_id'=>$accountability->id,
            'r_code'=>$request->session()->get('r_code'),
            'description'=>$log_history,
        );
        LogObservationHistory($log_Observation);
    }

    public function sendEmailAnalyze($accountability, $imdt, $request) {

        $pattern = array(
            'id' => $accountability->id,
            'accountability_id' => $accountability->id,
            'sector_id' => $accountability->user->sector_id,
            'itens' => $accountability->itens,
            'r_code' => $accountability->r_code,
            'lending' => formatMoney($accountability->total_lending),
            'total_paid' => formatMoney(($accountability->total_lending - $accountability->total_pending) ),
            'total_des' =>  formatMoney($accountability->total),
            'total' => formatMoney(abs($accountability->total_liquid)),
            'created_at' => $accountability->created_at->format('d/m/Y'),
            'title' => 'APROVAÇÃO DE PRESTAÇÃO DE CONTAS',
            'description' => '',
            'template' => 'accountability.Analyze',
            'subject' => 'APROVAÇÃO DE PRESTAÇÃO DE CONTAS',
        );

        $notify_title = __('layout_i.n_accountability_001_title');
        $notify_code = __('layout_i.n_accountability_001', ['amount' => $accountability->total_liquid, 'Name' => $accountability->user->first_name]);
        $notify_url = '/financy/accountability/edit/'. $accountability->id;

        SendMailJob::dispatch($pattern, $imdt->email);
        $this->sendNotifyUser($request, $imdt, $notify_title, $notify_code, $notify_url,'fa-exclamation', 'text-info');
    }    

    public function sendEmailApproved($accountability, $r_payment, $request) {

        $pattern = array(
            'id' => $r_payment->id,
            'r_p_id' => $r_payment->id,
            'payment' => $r_payment,
            'user' => $accountability->user,
            'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
            'title' => 'PRESTAÇÃO DE CONTAS APROVADA',
            'description' => '',
            'copys' => $this->approv_receivers,
            'template' => 'accountability.HasApprov',
            'subject' => 'Prestação de Contas aprovada: #'. $r_payment->id
        );

        $notify_title = __('layout_i.n_accountability_002_title');
        $notify_code = __('layout_i.n_accountability_002', ['id' => $r_payment->id, 'amount' => $r_payment->amount_gross,]);
        $notify_url = '/financy/accountability/my';

        SendMailJob::dispatch($pattern, $accountability->user->email);
        $this->sendNotifyUser($request, $accountability->user, $notify_title, $notify_code, $notify_url);
    }   
    
    public function sendEmailReproved($accountability, $ac_bank, $request) {
        
        $pattern = array(
            'id' => $accountability->id,
            'accountability_id' => $accountability->id,
            'sector_id' => $accountability->user->sector_id,
            'itens' => $accountability->itens,
            'r_code' => $accountability->r_code,
            'lending' => formatMoney($accountability->total_lending),
            'total_paid' => formatMoney(($accountability->total_lending - $accountability->total_pending) ),
            'total_des' =>  formatMoney($accountability->total),
            'total' => formatMoney(abs($accountability->total_liquid)),
            'created_at' => $accountability->created_at->format('d/m/Y'),
            'title' => 'PRESTAÇÃO DE CONTAS REPROVADA',
            'description' => $request->description,
            'is_reprov' => true,
            'template' => 'accountability.Analyze',
            'subject' => 'PRESTAÇÃO DE CONTAS REPROVADA',
        );

        $notify_title = __('layout_i.n_accountability_001_title');
        $notify_code = __('layout_i.n_accountability_001', ['amount' => $accountability->total_liquid, 'Name' => $accountability->user->first_name]);
        $notify_url = '/financy/accountability/edit/'. $accountability->id;

        SendMailJob::dispatch($pattern, $accountability->user->email);
        $this->sendNotifyUser($request, $accountability->user, $notify_title, $notify_code, $notify_url,'fa-exclamation', 'text-info');

    }    

    public function sendEmailSuspended($accountability, $request) {

        $pattern = array(
            'id' => $accountability->id,
            'accountability_id' => $accountability->id,
            'sector_id' => $accountability->user->sector_id,
            'itens' => $accountability->itens,
            'r_code' => $accountability->r_code,
            'lending' => formatMoney($accountability->total_lending),
            'total_paid' => formatMoney(($accountability->total_lending - $accountability->total_pending) ),
            'total_des' =>  formatMoney($accountability->total),
            'total' => formatMoney(abs($accountability->total_liquid)),
            'created_at' => $accountability->created_at->format('d/m/Y'),
            'title' => 'PRESTAÇÃO DE CONTAS SUSPENSA',
            'description' => $request->description,
            'is_reprov' => true,
            'template' => 'accountability.Analyze',
            'subject' => 'PRESTAÇÃO DE CONTAS SUSPENSA',
        );

        $notify_title = __('layout_i.n_accountability_001_title');
        $notify_code = __('layout_i.n_accountability_001', ['amount' => $accountability->total_liquid, 'Name' => $accountability->user->first_name]);
        $notify_url = '/financy/accountability/edit/'. $accountability->id;

        SendMailJob::dispatch($pattern, $accountability->user->email);
        $this->sendNotifyUser($request, $accountability->user, $notify_title, $notify_code, $notify_url,'fa-exclamation', 'text-info');
    }    

    public function LogObservationHistory($columns=array('model_class_origin'=>null,
                                                'model_id'=>null,
                                                'r_code'=>null,
                                                'description'=>null,
                                                )) {
    
        $observationHistory = new \App\Model\FinancyAccountabilityObservationHistory;
        foreach ($columns as $key => $column) {
            $observationHistory->{$key} = $column;
        }
        $observationHistory->save();
        return;
    }

    private function sendNotifyUser($request, $r_user, $notify_title, $notify_code, $notify_url, $notify_icon='fa-check', $notify_type='text-success'){
        App::setLocale($r_user->lang);
        NotifyUser($notify_title, $r_user->r_code, $notify_icon, $notify_type, $notify_code, $request->root() .$notify_url);
        App::setLocale($request->session()->get('lang'));
    }
}