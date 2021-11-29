<?php

namespace App\Services\Departaments\Administration\Refund;

use App\Jobs\SendMailJob;
use App\Model\Users;
use Illuminate\Support\Facades\Session;

Trait RefundTrait
{

    private function observersEmails() {

        $observers = $this->model->rtd_observers()->map(function ($item, $key) {
            return $item->users->email;
        });

        return $observers->toArray();
    }

    public function sendEmailMySelf($refund, $email) {
        $pattern = array(
            'id' => $refund->id,
            'immediates' => $refund->users->immediates,
            'title' => 'PEDIDO FOI REALIZADO',
            'description' => '',
            'template' => 'refund.Success',
            'subject' => 'Pedido de reembolso: #'. $refund->id,
        );

        SendMailJob::dispatch($pattern, $email);
    }

    public function sendEmailAnalyze($refund, $email) {
        $pattern = array(
            'id' => $refund->id,
            'sector_id' => $refund->users->sector_id,
            'itens' => $refund->financy_refund_item,
            'r_code' => $refund->users->r_code,
            'lending' => number_format($refund->lending, 2, ',', '.'),
            'total_des' =>  number_format($refund->total, 2, ',', '.'),
            'total' => number_format(abs($refund->lending - $refund->total), 2, ',', '.'),
            'created_at' => date('d/m/Y', strtotime($refund->created_at)),
            'title' => 'APROVAÇÃO DE REEMBOLSO',
            'copys' => $this->observersEmails(),
            'description' => '',
            'template' => 'refund.Analyze',
            'subject' => 'APROVAÇÃO DE REEMBOLSO',
        );

        NotifyUser(__('layout_i.n_refund_001_title'), $refund->users->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_refund_001', ['Name' => $refund->users->first_name]), env('APP_URL') .'/financy/refund/approv');
        SendMailJob::dispatch($pattern, $email);
    }

    public function sendEmailApproved($refund, $payment) {

        $pattern = array(
            'id' => $refund->id,
            'r_p_id' => $payment->id,
            'payment' => $payment,
            'user' => $refund->users,
            'created_at' => date('d/m/Y', strtotime($refund->created_at)),
            'title' => 'PEDIDO APROVADO',
            'copys' => $this->observersEmails(),
            'description' => '',
            'template' => 'refund.HasApprov',
            'subject' => 'Pedido de reembolso aprovado: #'. $refund->id,
        );

        NotifyUser(__('layout_i.n_refund_002_title'), $refund->users->r_code, 'fa-check', 'text-success', __('layout_i.n_refund_002', ['id' => $refund->id]), env('APP_URL') .'/financy/refund/my');
        SendMailJob::dispatch($pattern, $refund->users->email);
        LogSystem("Colaborador aprovou o reembolso identificado por ". $refund->id, Session::get('r_code'));
    }

    public function sendEmailReproved($refund) {

        $pattern = array(
            'id' => $refund->id,
            'sector_id' => $refund->users->sector_id,
            'itens' => $refund->financy_refund_item,
            'r_code' => $refund->users->r_code,
            'lending' => number_format($refund->lending, 2, ',', '.'),
            'total_des' =>  number_format($refund->total, 2, ',', '.'),
            'total' => number_format(abs($refund->lending - $refund->total), 2, ',', '.'),
            'created_at' => date('d/m/Y', strtotime($refund->created_at)),
            'title' => 'REEMBOLSO FOI REPROVADO',
            'copys' => $this->observersEmails(),
            'description' => '',
            'template' => 'refund.HasReprov',
            'subject' => 'Pedido de reembolso reprovado: #'. $refund->id,
        );

        NotifyUser(__('layout_i.n_refund_003_title'), $refund->users->r_code, 'fa-times', 'text-danger', __('layout_i.n_refund_003', ['id' => $refund->id]), env('APP_URL') .'/financy/refund/my/');
        SendMailJob::dispatch($pattern, $refund->users->email);

        LogSystem("Colaborador reprovou o reembolso identificado por ". $refund->id, Session::get('r_code'));
    }

    function sendEmailSuspended($refund, $request) {

        $user = Users::where('r_code', Session::get('r_code'))->first();

        $pattern = array(
            'id' => $refund->id,
            'user' => $user,
            'title' => 'REEMBOLSO #'. $refund->code .' FOI SUSPENSO',
            'description' => nl2br("Olá! Seu pedido ficará suspenso pelo seguinte motivo: ". $request->description ." \n Link para ver mais detalhes: <a href='". env('APP_URL') ."/financy/refund/all'>". env('APP_URL') ."/financy/refund/all</a>"),
            'template' => 'misc.DefaultWithPeoples',
            'copys' => $this->observersEmails(),
            'subject' => 'Atualização do reembolso: #'. $refund->code,
        );

        NotifyUser(
            'Reembolso foi suspendido: #'.$refund->code,
            $refund->users->r_code,
            'fa-exclamation',
            'text-primary',
            'Seu reembolso foi suspendido, verifique sua lista e veja o histórico de análise.',
            env('APP_URL') .'/financy/refund/my/');
        SendMailJob::dispatch($pattern, $refund->users->email);

        LogSystem("Colaborador suspendeu o reembolso identificado por ". $refund->id, Session::get('r_code'));
    }
}
