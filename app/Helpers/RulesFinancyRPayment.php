<?php

namespace App\Helpers;

use App\Model\Users;
use App\Model\LogAccess;
use App\Model\Countries;
use App\Model\FinancyRPayment;
use App\Model\FinancyRPaymentFnyAnalyze;
use App\Model\FinancyRPaymentMngAnalyze;
use App\Model\FinancyRPaymentPresAnalyze;
use App\Model\FinancyRPaymentNf;
use App\Model\UserOnPermissions;
use App\Model\UserFinancy;
use App\Model\FinancyAccountability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Jobs\SendMailJob;
use Hash;
use App;
use Log;

class RulesFinancyRPayment
{


    public function RulesFinancyPaymentAnalyze(Request $request, FinancyRPayment $r_payment, $type){

        $permission = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))->where('perm_id', 11)->first();

        if($r_payment){
            $id = $r_payment->id;

            if ($type == 1) {//caso aprovado

                //Processo de Aprovação do Presidente
                if ($permission->grade == 10 and $r_payment->financy_approv == 1 or validHoliday(11, 10, null)) {
                    $f_analyze = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)->first();
                    if (!$f_analyze) {
                        $request->session()->put('error', "Financeiro ainda não realizou análise desse pedido.");
                        return redirect('/financy/payment/approv');
                    } else if ($r_payment->pres_approv == 1) {
                        $request->session()->put('error', "Solicitação de pagamento, já foi aprovada!");
                        return redirect('/financy/payment/approv');
                    }
                    $r_payment->pres_approv = 1; //Aprovação do Presidente
                    $r_payment->financy_approv = 1; //Aprovação do Gerente Financeiro
                    $r_payment->mng_approv = 1;  //Aprovação do Gestor
                    $r_payment->has_analyze = 0;

                    $analyze = new FinancyRPaymentPresAnalyze;
                    $analyze->financy_payment_id = $id;
                    $analyze->r_code = $request->session()->get('r_code');
                    $analyze->is_approv = 1;
                    $analyze->description = $request->description;
                    $analyze->save();

                    $r_user = Users::where('r_code', $r_payment->request_r_code)->first();

                    $pattern = array(
                        'id' => $r_payment->id,
                        'r_p_id' => $r_payment->id,
                        'payment' => $r_payment,
                        'user' => $r_user,
                        'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                        'title' => 'PEDIDO APROVADO',
                        'description' => '',
                        'template' => 'payment.HasApprov',
                        'subject' => 'Pedido aprovado: #'. $r_payment->id,
                    );
                    $notify_title = __('layout_i.n_payment_002_title');
                    $notify_code = __('layout_i.n_payment_002', ['id' => $r_payment->id, 'amount' => $r_payment->amout_liquid,]);
                    $notify_url = '/financy/payment/my';

                    //Caso a Origem do Pagamento Seja uma prestação de Contas
                    if($r_payment->module_type == 4){
                        $pattern['id']=$r_payment->module_id;
                        $pattern['title']='PRESTAÇÃO DE CONTAS APROVADA';
                        $pattern['template']='accountability.HasApprov';
                        $pattern['subject']='Prestação de Contas aprovada: #'. $r_payment->id;

                        $notify_title = __('layout_i.n_accountability_002_title');
                        $notify_code = __('layout_i.n_accountability_002', ['id' => $r_payment->id, 'amount' => $r_payment->amount_gross,]);
                        $notify_url = '/financy/accountability/my';
                    }

                    SendMailJob::dispatch($pattern, $r_user->email);
                    $this->sendNotifyUser($request, $r_user, $notify_title, $notify_code, $notify_url);


                } else if ($permission->grade == 99 and $r_payment->mng_approv == 1 or $permission->grade == 11 and $r_payment->mng_approv == 1 or $permission->grade == 12 and $r_payment->mng_approv == 1 or validHoliday(11, 99, null) and $r_payment->mng_approv == 1 or validHoliday(11, 11, null) and $r_payment->mng_approv == 1 or validHoliday(11, 12, null) and $r_payment->mng_approv == 1) {
                    if (FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)->count() == 3) {
                        $request->session()->put('error', "Essa solicitação já foi aprovado pelo financeiro.");
                        return redirect('/financy/payment/approv');
                    }
                    if (FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)->where('r_code', $request->session()->get('r_code'))->count() > 0) {
                        $request->session()->put('error', "Você ja aprovou esta solicitação.");
                        return redirect('/financy/payment/approv');
                    }

                    if (!$r_payment->financy_supervisor and $permission->grade == 12 and $r_payment->due_date >= date('Y-m-d', strtotime($r_payment->created_at .'+ 7 days')) or !$r_payment->financy_supervisor and validHoliday(11, 12, null) and $r_payment->due_date >= date('Y-m-d', strtotime($r_payment->created_at .'+ 7 days'))) {
                        $r_payment->financy_supervisor = $request->session()->get('r_code');

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                            ->select('users.*')
                                            ->where('user_on_permissions.can_approv', 1)
                                            ->where('user_on_permissions.grade', 11)
                                            ->where('user_on_permissions.perm_id', 11)
                                            ->get();

                    } else if (!$r_payment->financy_accounting and $r_payment->financy_supervisor != null and $permission->grade == 11 and $r_payment->due_date >= date('Y-m-d', strtotime($r_payment->created_at .'+ 7 days')) or !$r_payment->financy_accounting and $r_payment->financy_supervisor != null and validHoliday(11, 11, null) and $r_payment->due_date >= date('Y-m-d', strtotime($r_payment->created_at .'+ 7 days'))) {
                        $r_payment->financy_accounting = $request->session()->get('r_code');

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                            ->select('users.*')
                                            ->where('user_on_permissions.can_approv', 1)
                                            ->where('user_on_permissions.grade', 99)
                                            ->where('user_on_permissions.perm_id', 11)
                                            ->get();

                    } else {
                        if ($permission->grade != 99 and $permission->can_approv == 1) {
                            $request->session()->put('error', "Apenas o gerente financeiro pode liberar essa solicitação.");
                            return redirect('/financy/payment/approv');
                        }

                        $r_payment->financy_approv = 1;
                        $r_payment->mng_approv = 1;

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                            ->select('users.*')
                                            ->where('user_on_permissions.can_approv', 1)
                                            ->where('user_on_permissions.grade', 10)
                                            ->where('user_on_permissions.perm_id', 11)
                                            ->get();

                    }

                    if ($request->receiver != "" and $request->receiver != "undefined") {
                        $r_payment->financy_receiver = $request->receiver;
                    }
                    $analyze = new FinancyRPaymentFnyAnalyze;
                    $analyze->financy_payment_id = $id;
                    $analyze->r_code = $request->session()->get('r_code');
                    $analyze->is_approv = 1;
                    $analyze->description = $request->description;
                    $analyze->save();

                    $r_user = Users::where('r_code', $r_payment->request_r_code)->first();

                    $pattern = array(
                        'id' => $r_payment->id,
                        'sector_id' => $r_user->sector_id,
                        'r_code' => $r_user->r_code,
                        'content' => $r_payment->description,
                        'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                        'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                        'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                        'method' => $r_payment->p_method,
                        'optional' => $r_payment->optional,
                        'title' => 'APROVAÇÃO DE PAGAMENTO',
                        'description' => '',
                        'template' => 'payment.Analyze',
                        'subject' => 'APROVAÇÃO DE PAGAMENTO',
                    );
                    $notify_title = __('layout_i.n_payment_001_title');
                    $notify_code = __('layout_i.n_payment_001', ['amount' => $r_payment->amount_liquid, 'Name' => $r_user->first_name]);
                    $notify_url = '/financy/payment/request/approv/'. $r_payment->id;

                        //Caso a Origem do Pagamento Seja uma prestação de Contas
                        if($r_payment->module_type == 4){

                            $accountability = FinancyAccountability::find($r_payment->module_id);
                            if ($accountability) {

                                $pattern = array(
                                    'id' => $accountability->id,
                                    'payment_request_id' => $accountability->payment_request_id,
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
                                $notify_code = __('layout_i.n_accountability_001', ['amount' => $r_payment->amount_liquid, 'Name' => $r_user->first_name]);
                                $notify_url = '/financy/payment/request/approv/'. $r_payment->id;
                            }

                        }

                    foreach ($immediate as $key) {

                        $imdt = Users::where('r_code', $key->r_code)->first();

                        if ($key->is_holiday == 1) {
                            $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                            foreach($usrhd as $usr) {

                                $imdt = Users::where('r_code', $usr->receiver_r_code)->first();

                                // send email
                                SendMailJob::dispatch($pattern, $imdt->email);
                                $this->sendNotifyUser($request, $imdt, $notify_title, $notify_code, $notify_url,'fa-exclamation', 'text-info');
                            }
                        } else {

                            // send email
                            SendMailJob::dispatch($pattern, $imdt->email);
                            $this->sendNotifyUser($request, $imdt, $notify_title, $notify_code, $notify_url,'fa-exclamation', 'text-info');
                        }

                    }
                } else {
                    if (FinancyRPaymentMngAnalyze::where('financy_payment_id', $id)->count() > 0) {
                        $request->session()->put('error', "Essa solicitação já foi aprovado pelo gestor.");
                        return redirect('/financy/payment/approv');
                    }
                    $r_payment->mng_approv = 1;

                    $analyze = new FinancyRPaymentMngAnalyze;
                    $analyze->financy_payment_id = $id;
                    $analyze->r_code = $request->session()->get('r_code');
                    $analyze->is_approv = 1;
                    $analyze->description = $request->description;
                    $analyze->save();

                    $r_user = Users::where('r_code', $r_payment->request_r_code)->first();

                    // VERIFY IF HAS NF PENDING
                    $nf = FinancyRPaymentNf::where('nf_number', $r_payment->nf_nmb)->where('financy_r_payment_id', 0)->first();


                    if ($r_payment->due_date >= date('Y-m-d', strtotime($r_payment->created_at .'+ 7 days'))) {

                        if ($nf) {

                            if ($nf->is_approv == 1) {
                                $r_payment->financy_supervisor = $nf->r_code;

                                $analyze = new FinancyRPaymentFnyAnalyze;
                                $analyze->financy_payment_id = $id;
                                $analyze->r_code = $nf->r_code;
                                $analyze->is_approv = 1;
                                $analyze->description = $nf->description;
                                $analyze->save();

                                $nf->financy_r_payment_id = $r_payment->id; //antecipação fiscal
                                $nf->save();

                                $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                    ->select('users.*')
                                                    ->where('user_on_permissions.can_approv', 1)
                                                    ->where('user_on_permissions.grade', 11)
                                                    ->where('user_on_permissions.perm_id', 11)
                                                    ->get();
                            } else {
                                $n_r_payment = FinancyRPayment::find($r_payment->id);
                                $n_r_payment->financy_reprov = 1;

                                $analyze = new FinancyRPaymentFnyAnalyze;
                                $analyze->financy_payment_id = $id;
                                $analyze->r_code = $nf->r_code;
                                $analyze->is_reprov = 1;
                                $analyze->description = $nf->description;
                                $analyze->save();

                                $n_r_payment->has_analyze = 0;
                                $n_r_payment->has_suspended = 0;
                                $n_r_payment->save();

                                $nf->financy_r_payment_id = $r_payment->id; //antecipação fiscal
                                $nf->save();

                                $immediate = [];

                                $r_user = Users::where('r_code', $r_payment->request_r_code)->first();
                                $pattern = array(
                                    'id' => $r_payment->id,
                                    'sector_id' => $r_user->sector_id,
                                    'r_code' => $r_user->r_code,
                                    'content' => $r_payment->description,
                                    'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                                    'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                                    'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                                    'method' => $r_payment->p_method,
                                    'optional' => $r_payment->optional,
                                    'title' => 'PAGAMENTO REPROVADO',
                                    'description' => '',
                                    'template' => 'payment.HasReprov',
                                    'subject' => 'PAGAMENTO REPROVADO',
                                );

                                SendMailJob::dispatch($pattern, $r_user->email);

                                $notify_title = __('layout_i.n_payment_003_title');
                                $notify_code = __('layout_i.n_payment_003', ['amount' => $r_payment->amout_liquid, 'id' => $r_payment->id]);
                                $notify_url = '/financy/payment/my/';
                                $this->sendNotifyUser($request, $r_user, $notify_title, $notify_code, $notify_url,'fa-times', 'text-danger');

                            }
                        } else {

                            $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                            ->select('users.*')
                                            ->where('user_on_permissions.can_approv', 1)
                                            ->where('user_on_permissions.grade', 12)
                                            ->where('user_on_permissions.perm_id', 11)
                                            ->get();

                        }

                    } else {
                        if ($nf) {

                            if ($nf->is_repprov == 1) {

                                $n_r_payment = FinancyRPayment::find($r_payment->id);
                                $n_r_payment->financy_reprov = 1;

                                $analyze = new FinancyRPaymentFnyAnalyze;
                                $analyze->financy_payment_id = $id;
                                $analyze->r_code = $nf->r_code;
                                $analyze->is_reprov = 1;
                                $analyze->description = $nf->description;
                                $analyze->save();

                                $n_r_payment->has_analyze = 0;
                                $n_r_payment->has_suspended = 0;
                                $n_r_payment->save();

                                $nf->financy_r_payment_id = $r_payment->id;
                                $nf->save();

                                $immediate = [];

                                $r_user = Users::where('r_code', $r_payment->request_r_code)->first();
                                $pattern = array(
                                    'id' => $r_payment->id,
                                    'sector_id' => $r_user->sector_id,
                                    'r_code' => $r_user->r_code,
                                    'content' => $r_payment->description,
                                    'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                                    'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                                    'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                                    'method' => $r_payment->p_method,
                                    'optional' => $r_payment->optional,
                                    'title' => 'PAGAMENTO REPROVADO',
                                    'description' => '',
                                    'template' => 'payment.HasReprov',
                                    'subject' => 'PAGAMENTO REPROVADO',
                                );

                                SendMailJob::dispatch($pattern, $r_user->email);

                                $notify_title = __('layout_i.n_payment_003_title');
                                $notify_code = __('layout_i.n_payment_003', ['amount' => $r_payment->amout_liquid, 'id' => $r_payment->id]);
                                $notify_url = '/financy/payment/my/';
                                $this->sendNotifyUser($request, $r_user, $notify_title, $notify_code, $notify_url,'fa-times', 'text-danger');


                            } else {


                                $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                            ->select('users.*')
                                            ->where('user_on_permissions.can_approv', 1)
                                            ->where('user_on_permissions.grade', 99)
                                            ->where('user_on_permissions.perm_id', 11)
                                            ->get();

                            }


                        } else {

                            $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                            ->select('users.*')
                                            ->where('user_on_permissions.can_approv', 1)
                                            ->where('user_on_permissions.grade', 99)
                                            ->where('user_on_permissions.perm_id', 11)
                                            ->get();

                        }

                    }

                    $pattern = array(
                        'id' => $r_payment->id,
                        'sector_id' => $r_user->sector_id,
                        'r_code' => $r_user->r_code,
                        'content' => $r_payment->description,
                        'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                        'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                        'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                        'method' => $r_payment->p_method,
                        'optional' => $r_payment->optional,
                        'title' => 'APROVAÇÃO DE PAGAMENTO',
                        'description' => '',
                        'template' => 'payment.Analyze',
                        'subject' => 'APROVAÇÃO DE PAGAMENTO',
                    );
                    $notify_title = __('layout_i.n_payment_001_title');
                    $notify_code = __('layout_i.n_payment_001', ['amount' => $r_payment->amount_liquid, 'Name' => $r_user->first_name]);
                    $notify_url = '/financy/payment/request/approv/'. $r_payment->id;

                    //Caso a Origem do Pagamento Seja uma prestação de Contas
                    if($r_payment->module_type == 4){

                        $accountability = FinancyAccountability::find($r_payment->module_id);
                        if ($accountability) {

                            $pattern = array(
                                'id' => $accountability->id,
                                'payment_request_id' => $accountability->payment_request_id,
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
                            $notify_code = __('layout_i.n_accountability_001', ['amount' => $r_payment->amount_liquid, 'Name' => $r_user->first_name]);
                            $notify_url = '/financy/payment/request/approv/'. $r_payment->id;
                        }

                    }
                    if (count($immediate) > 0) {
                        foreach ($immediate as $key) {

                            $imdt = Users::where('r_code', $key->r_code)->first();

                            if ($key->is_holiday == 1) {
                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $imdt = Users::where('r_code', $usr->receiver_r_code)->first();

                                    // send email
                                    SendMailJob::dispatch($pattern, $imdt->email);
                                    $this->sendNotifyUser($request, $imdt, $notify_title, $notify_code, $notify_url,'fa-exclamation', 'text-info');
                                }
                            } else {

                                // send email
                                SendMailJob::dispatch($pattern, $imdt->email);
                                $this->sendNotifyUser($request, $imdt, $notify_title, $notify_code, $notify_url,'fa-exclamation', 'text-info');
                            }

                        }
                    }
                }
                $r_payment->has_suspended = 0;
                $r_payment->save();

                LogSystem("Colaborador aprovou a solicitação de pagamento identificado por ". $r_payment->id, $request->session()->get('r_code'));
            } else if ($type == 2) { //caso reprovado
                $type_name = "";
                if ($permission->grade == 10 or validHoliday(11, 10, null)) {
                    $r_payment->pres_reprov = 1;
                    $analyze = new FinancyRPaymentPresAnalyze;
                    $type_name = "PRESIDENTE";

                } else if ($permission->grade == 99 or $permission->grade == 11 or $permission->grade == 12 or validHoliday(11, 99, null) or validHoliday(11, 11, null) or validHoliday(11, 12, null)) {
                    $r_payment->financy_reprov = 1;
                    $analyze = new FinancyRPaymentFnyAnalyze;
                    $type_name = "FINANCEIRO";

                } else {
                    $r_payment->mng_reprov = 1;
                    $analyze = new FinancyRpaymentMngAnalyze;
                    $type_name = "GESTOR";
                }

                    $analyze->financy_payment_id = $id;
                    $analyze->r_code = $request->session()->get('r_code');
                    $analyze->is_reprov = 1;
                    $analyze->description = $request->description;
                    $analyze->save();

                $r_payment->has_analyze = 0;
                $r_payment->has_suspended = 0;
                $r_payment->save();

                $r_user = Users::where('r_code', $r_payment->request_r_code)->first();
                $pattern = array(
                    'id' => $r_payment->id,
                    'sector_id' => $r_user->sector_id,
                    'r_code' => $r_user->r_code,
                    'content' => $r_payment->description,
                    'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                    'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                    'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                    'method' => $r_payment->p_method,
                    'optional' => $r_payment->optional,
                    'title' => 'PAGAMENTO REPROVADO',
                    'description' => '',
                    'template' => 'payment.HasReprov',
                    'subject' => 'PAGAMENTO REPROVADO',
                );

                SendMailJob::dispatch($pattern, $r_user->email);

                $notify_title = __('layout_i.n_payment_003_title');
                $notify_code = __('layout_i.n_payment_003', ['amount' => $r_payment->amout_liquid, 'id' => $r_payment->id]);
                $notify_url = '/financy/payment/my/';
                $this->sendNotifyUser($request, $r_user, $notify_title, $notify_code, $notify_url,'fa-times', 'text-danger');

                LogSystem("Colaborador reprovou a solicitação de pagamento identificado por ". $r_payment->id, $request->session()->get('r_code'));
            }

        }

    }

    public function ModuleRulesFinancyPaymentTransfer(Request $request, FinancyRPayment $r_payment, $model){
        Log::info('RulesFinancyRPayment@ModuleRulesFinancyPaymentTransfer not implemented');
    }

    private function sendNotifyUser($request, $r_user, $notify_title, $notify_code, $notify_url, $notify_icon='fa-check', $notify_type='text-success'){
        App::setLocale($r_user->lang);
        NotifyUser($notify_title, $r_user->r_code, $notify_icon, $notify_type, $notify_code, $request->root() .$notify_url);
        App::setLocale($request->session()->get('lang'));
    }


}
