<?php

namespace App\Services\Departaments\Administration\Lending;

use App;
use Request;
use App\Jobs\SendMailJob;
use App\Model\Users;
use Illuminate\Support\Facades\Session;
use App\Services\Departaments\Helpers;

Trait LendingTrait
{
    use Helpers;

    public function sendEmailMySelf($lending, $email) {

        $pattern = array(
            'id' => $lending->id,
            'immediates' => $lending->user->immediates ?? [],
            'title' => 'PEDIDO FOI REALIZADO',
            'description' => '',
            'template' => 'lending.LendingSuccess',
            'subject' => 'Pedido de empréstimo: #'. $lending->id .' "Valor: R$ '. number_format($lending->amount, 2, ".", "."),
        );
        SendMailJob::dispatch($pattern, $email);
    }

    public function sendEmailAnalyze($lending, $immediate, $request) {

        $pattern = array(
            'id' => $lending->id,
            'immediates' => $lending->user->immediates ?? [],
            'has_debit' => $lending->user->financy->used_credit > 0 ? 1 : 0,
            'ac_bank' =>  $lending->user->financy,
            'lending' => $lending,
            'attach_type' => $request->type_data == 1 ? 1 : 2,
            'attach_url' => $request->attach_url,
            'user' => $lending->user,
            'title' => 'PEDIDO DE APROVAÇÃO',
            'description' => '',
            'template' => 'lending.LendingApprov',
            'subject' => 'Pedido de empréstimo: #'. $lending->id .' "Valor: R$ '. number_format($lending->amount, 2, ".", ".") . '" "Colaborador: '. $lending->user->first_name .' '. $lending->user->last_name .'"',
        );

        SendMailJob::dispatch($pattern, $immediate->email);
        App::setLocale($immediate->lang);
        NotifyUser(__('layout_i.n_lending_001_title'), $immediate->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_lending_001', ['amount' => '#'. number_format($lending->amount, 2, ".", "."), 'Name' => $lending->user->first_name]), $request->root() .'/financy/lending/approv');
        App::setLocale($request->session()->get('lang'));   
    }

    public function sendEmailApproved($lending, $payment, $ac_bank) {

        $attach = $lending->financy_lending_attach->first();
        if ($attach) {
            $attach_url = $attach->is_file == 1 ? $attach->url_file : $attach->id_module;
            $attach_type = $attach->module_type;
        } else {
            $attach_url = "";
            $attach_type = 0;
        }

        $pattern = array(
            'id' => $lending->id,
            'immediates' => [],
            'has_debit' => $ac_bank->used_credit > 0 ? 1 : 0,
            'ac_bank' => $ac_bank,
            'lending' => $lending,
            'attach_type' => $attach_type,
            'attach_url' => $attach_url,
            'payment' => $payment,
            'user' => $lending->user,
            'title' => 'PEDIDO APROVADO',
            'description' => '',
            'has_approv' => 1,
            'copys' => $this->approv_receivers,
            'template' => 'lending.LendingHasApprov',
            'subject' => 'Pedido de empréstimo aprovado: #'. $lending->id .' "Valor: R$ '. number_format($lending->amount, 2, ".", "."),
        );
    
        NotifyUser(__('layout_i.n_refund_002_title'), $lending->user->r_code, 'fa-check', 'text-success', __('layout_i.n_refund_002', ['id' => $lending->id]), Request::root() .'/financy/refund/my');
        SendMailJob::dispatch($pattern, $lending->user->email);
        LogSystem("Colaborador aprovou o empréstimo identificado por ". $lending->id, Session::get('r_code'));
    }

    public function sendEmailReproved($lending, $request, $ac_bank) {

        $attach = $lending->financy_lending_attach->first();
        if ($attach) {
            $attach_url = $attach->is_file == 1 ? $attach->url_file : $attach->id_module;
            $attach_type = $attach->module_type;
        } else {
            $attach_url = "";
            $attach_type = 0;
        }

        $user_approv = $lending->rtd_status['status']['validation']->where('r_code', $request->session()->get('r_code'))->first();
        if($user_approv) {
            if (in_array($user_approv->mark, array_keys($lending->configClass('arr_mark')))) { 
                $type_name = $lending->configClass('arr_mark')[$user_approv->mark];    
            } else {
                $type_name = "Gestor";
            }
        }

        $pattern = array(
            'id' => $lending->id,
            'has_debit' => $ac_bank->used_credit > 0 ? 1 : 0,
            'ac_bank' => $ac_bank,
            'lending' => $lending,
            'attach_type' => $attach_type,
            'attach_url' => $attach_url,
            'user' => $lending->user->first(),
            'title' => $user_approv ? strtoupper($type_name) . ' REPROVOU' : '' .' REPROVOU',
            'description' => $request->description,
            'has_approv' => 0,
            'template' => 'lending.LendingHasApprov',
            'subject' => 'Pedido de empréstimo reprovado: #'. $lending->id .' "Valor: R$ '. number_format($lending->amount, 2, ".", ".")
        );

        SendMailJob::dispatch($pattern, $lending->user->email);
        App::setLocale($lending->user->lang);
        NotifyUser(__('layout_i.n_lending_003_title'), $lending->user->r_code, 'fa-times', 'text-danger', __('layout_i.n_lending_003', ['amount' => '#'. number_format($lending->amount, 2, ".", "."), 'id' => $lending->id]), $request->root() .'/financy/lending/my');
        App::setLocale($request->session()->get('lang'));
        LogSystem("Colaborador reprovou o empréstimo identificado por ". $lending->id, $request->session()->get('r_code'));
    }

    public function sendEmailSuspended($lending, $request) {

        $pattern = array(
            'id' => $lending->id,
            'user' => $lending->user,
            'title' => 'EMPRÉSTIMO #'. $lending->code .' FOI SUSPENSO',
            'description' => nl2br("Olá! Seu pedido ficará suspenso pelo seguinte motivo: ". $request->description ." \n Link para ver mais detalhes: <a href'". $request->root() ."/financy/lending/all'>". $request->root() ."/financy/lending/all</a>"),
            'template' => 'misc.DefaultWithPeoples',
            'subject' => 'Atualização do empréstimo: #'. $lending->code,
        );

        NotifyUser(
            'Empréstimo foi suspendido: #'.$lending->code,
            $lending->user->r_code,
            'fa-exclamation',
            'text-primary',
            'Seu empréstimo foi suspendido, verifique sua lista e veja o histórico de análise.',
            Request::root() .'/financy/lending/my/'
        );

        SendMailJob::dispatch($pattern, $lending->user->email);
    }
}