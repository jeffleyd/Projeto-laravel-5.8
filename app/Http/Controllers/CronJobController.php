<?php

namespace App\Http\Controllers;

use App\Model\Users;
use App\Model\UserDebtors;
use App\Model\UserFinancy;
use App\Model\Trips;
use App\Model\TripPlan;
use App\Model\TripPeoples;
use App\Model\Task;
use App\Model\TaskResponsible;
use App\Model\TaskHistory;
use App\Model\TaskAnalyze;
use App\Model\TaskAnalyzeCompleted;
use App\Model\TaskCopyContact;
use App\Model\LogAccess;
use App\Model\Countries;
use App\Model\Regions;
use App\Model\TripFinality;
use App\Model\Survey;
use App\Model\SurveyNotifyUser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Model\TaskDay;
use Hash;
use App;
use Log;
use GuzzleHttp\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;

use App\Model\SacProtocol;
use App\Model\SacModelProtocol;
use App\Model\SacPartProtocol;
use App\Model\SacAuthorized;
use App\Model\SacClient;
use App\Model\SacMsgProtocol;
use App\Model\SacMsgOs;
use App\Model\SacOsProtocol;
use App\Model\SacExpeditionRequest;
use App\Model\SacBuyPart;
use App\Model\UserOnPermissions;
use App\Helpers\Commercial\OrderInvoiceEmail;

class CronJobController extends Controller
{

    public function verifyTaskLater(Request $request) {

        $late = Task::where('is_completed', 0)
            ->where('is_accept', 1)
            ->where('is_cancelled', 0)
            ->where('end_date', '<=', date('Y-m-d'))
            ->where('start_date', '<', date('Y-m-d'))
            ->get();

        if (count($late) > 0) {
            foreach ($late as $key) {

                $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                    ->where('task_id', $key->id)
                    ->select('users.*')
                    ->get();

                $emails_cc = TaskCopyContact::where('task_id', $key->id)->get();
                $pattern = array(
                    'id' => $key->id,
                    'responsable' => $responsable,
                    'copys' => $emails_cc,
                    'title' => 'TAREFA ATRASADA',
                    'description' => nl2br("A tarefa desse colaborador encontra-se atrasada desde o dia: ". date('Y-m-d', strtotime($key->end_date)) ."! Verifique nos detalhes mais informações. \n Responsável pela tarefa abaixo."),
                    'template' => 'task.RequestCJ_later',
                    'subject' => 'Tarefa atrasada: #'. $key->id .' "'. $key->title .'"',
                );

                $user = Users::where('r_code', $key->r_code)->first();
                SendMailCopyJob::dispatch($pattern, $user->email);
                $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                    ->where('task_id', $key->id)
                    ->select('users.*')
                    ->first();
                NotifyUser(__('layout_i.n_cron_001_title'), $user->r_code, 'fa-exclamation-triangle', 'text-danger', __('layout_i.n_cron_001', ['id' => $key->id, 'Name' => $responsable->first_name]), $request->root() .'/task/view/history/'. $key->id);

                $user = Users::where('r_code', $key->r_code)->get();
                $pattern = array(
                    'id' => $key->id,
                    'responsable' => $user,
                    'title' => 'TAREFA ATRASADA',
                    'description' => nl2br("Sua tarefa encontra-se atrasada desde o dia: ". date('Y-m-d', strtotime($key->end_date)) ."! Verifique nos detalhes mais informações. \n Criador da tarefa abaixo."),
                    'template' => 'task.RequestCJ_later',
                    'subject' => 'Tarefa atrasada: #'. $key->id .' "'. $key->title .'"',
                );

                SendMailJob::dispatch($pattern, $responsable->email);

            }
        }


        return;
    }

    public function verifyTripRest7(Request $request) {
        $trips = TripPlan::leftJoin('trips','trip_plan.trip_id','=','trips.id')
            ->leftJoin('users','trips.r_code','=','users.r_code')
            ->select('trip_plan.*', 'users.first_name', 'users.last_name', 'users.picture', 'users.r_code')
            ->where('trip_plan.has_analyze', 1)
            ->where('trip_plan.origin_date', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 day')))
            ->groupBy('trip_plan.id')
            ->get();

        if (count($trips) > 0) {
            foreach ($trips as $trip_plan) {

                $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();

                $pattern = array(
                    'name' => getNameFormated(),
                    'imd' => '0004',
                    'id' => $trip_plan->id,
                    'r_code' => $trip_plan->r_code,
                    'finality' => finalityName($trip_plan->finality),
                    'goal' => $trip_plan->goal,
                    'origin_country' => GetCountryName($trip_plan->origin_country),
                    'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                    'origin_city' => $trip_plan->origin_city,
                    'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                    'destiny_country' => GetCountryName($trip_plan->destiny_country),
                    'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                    'destiny_city' => $trip_plan->destiny_city,
                    'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                    'has_hotel' => $trip_plan->has_hotel,
                    'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                    'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                    'dispatch' => $trip_plan->dispatch,
                    'dispatch_reason' => $trip_plan->dispatch_reason,
                    'title' => 'PEDIDO REQUER APROVAÇÃO',
                    'description' => '',
                    'peoples' => $peoples,
                    'template' => 'trip.RequestApprov',
                    'subject' => 'Pedido de aprovação de viagem: #'. $trip_plan->id .' "'. getENameF($trip_plan->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                );

                $imdt = Users::where('r_code', '0004')->first();
                SendMailJob::dispatch($pattern, $imdt->email);
                // NotifyUser(__('layout_i.n_trip_006_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_006'), $request->root() .'/trip/review/'. $trip_plan->id);
            }
        }

        return count($trips);
    }

    public function sendReportTrip(Request $request) {
        $a_date = date('Y-m-d');
        $lastdate = date("Y-m-t", strtotime($a_date));

        if ($a_date == $lastdate) {

            $amount_actual = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                ->where('trip_plan.is_completed', 1)
                ->where('trip_plan.is_cancelled', 0)
                ->whereYear('trip_plan.origin_date', date('Y'))
                ->whereMonth('trip_plan.origin_date', date('m', strtotime($a_date)))
                ->sum('trip_agency_budget.total');

            if ($amount_actual != null and $amount_actual != 0) {

                $finality = collect([]);
                for ($fin=1; $fin <= 10; $fin++) {
                    $fin = $fin == 10 ? 99 : $fin;
                    $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                        ->leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                        ->where('trip_plan.is_completed', 1)
                        ->where('trip_plan.is_cancelled', 0)
                        ->where('trip_plan.finality', $fin)
                        ->whereYear('trip_plan.origin_date', date('Y'))
                        ->whereMonth('trip_plan.origin_date', date('m', strtotime($a_date)))
                        ->sum('trip_agency_budget.total');
                    $total = $total == null ? 0 : $total;
                    $finality->push(number_format($total, 2, '.', '.'));
                }

                $amount_actual = $amount_actual == null ? 0 : $amount_actual;
                $pattern = array(
                    'finality' => $finality,
                    'amount_actual' => number_format($amount_actual, 2, '.', '.'),
                    'date' => $a_date,
                    'title' => 'RELATÓRIO DE VIAGEM',
                    'description' => '',
                    'template' => 'report.ReportTrip',
                    'subject' => 'Relatório de viagem: Mês ('. $a_date .')',
                );

                $user = Users::where('r_code', '0004')->first();
                SendMailJob::dispatch($pattern, $user->email);

                $user = Users::where('r_code', '0005')->first();
                SendMailJob::dispatch($pattern, $user->email);

                $user = Users::where('r_code', '1842')->first();
                SendMailJob::dispatch($pattern, $user->email);

            }

        }

        return;
    }

    public function sendReportTaskDay(Request $request) {
        $a_date = date('Y-m-d');
        $lastdate = date("Y-m-t", strtotime($a_date));

        if ($a_date == $lastdate) {

            $taskday = TaskDay::leftJoin('users','task_day.r_code','=','users.r_code')
                ->select('users.first_name', 'users.last_name', 'task_day.*')
                ->where('task_day.attach', '!=', null)
                ->where('task_day.is_cancelled', 0)
                ->whereYear('task_day.start_date', date('Y'))
                ->whereMonth('task_day.start_date', date('m', strtotime($a_date)))
                ->groupBy('task_day.r_code')
                ->get();

            if (count($taskday) > 0) {

                $immediates = DB::table('user_on_permissions')
                    ->leftJoin('users','user_on_permissions.user_r_code','=','users.r_code')
                    ->select('users.r_code', 'users.email')
                    ->where('user_on_permissions.grade', '>=', 8)
                    ->where('user_on_permissions.grade', '<=', 10)
                    ->distinct('user_on_permissions.user_r_code')
                    ->get();

                if ($immediates) {

                    foreach ($immediates as $key) {
                        $pattern = array(
                            'taskday' => $taskday,
                            'title' => 'RELATÓRIO DO MÊS (HOME OFFICE)',
                            'description' => '',
                            'template' => 'report.ReportTaskDay',
                            'subject' => 'Relatório home office: Mês ('. $a_date .')',
                        );

                        SendMailJob::dispatch($pattern, $key->email);
                    }

                }

            }

        }

        return;
    }

    public function lendingOpen(Request $request) {

        $l_open = UserDebtors::where('is_active', 1)->where('time_block', '<', date('Y-m-d'))->get();

        if (count($l_open) > 0) {

            foreach ($l_open as $key) {

                $debit = UserDebtors::find($key->id);
                $debit->is_active = 0;
                $debit->payment_in_account = 1;
                $debit->save();

                $user = UserFinancy::where('r_code', $debit->r_code)->first();
                $rest = $user->used_credit - $debit->credit;
                $user->used_credit = $rest;
                if ($rest == 0.00) {
                    $user->has_credit_block = 0;
                }
                $user->save();
            }

        }
    }

    public function SurveyReportDaily(Request $request) {

        $survey = Survey::where('is_active', 1)->first();

        if ($survey) {

            $export = DB::table('user_survey')
                ->leftJoin('users','user_survey.user_r_code','=','users.r_code')
                ->select('user_survey.*', 'users.first_name', 'users.last_name')
                ->whereYear('user_survey.created_at', date('Y'))
                ->whereMonth('user_survey.created_at', date('m'))
                ->whereDay('user_survey.created_at', date('d'))
                ->where('user_survey.survey_id', $survey->id)
                ->get();

            if (count($export) > 0) {

                $pattern = array(
                    'date' => date('Y-m-d'),
                    'title' => "Relatório diário: ". strtoupper(strip_tags($survey->name)),
                    'description' => '',
                    'template' => 'report.ReportSurvey',
                    'subject' => "Relatório diário: ". ucfirst(strip_tags($survey->name)),
                );

                $notify = SurveyNotifyUser::where('survey_id', 1)->get();

                foreach ($notify as $key) {
                    $user = Users::where('r_code', $key->user_r_code)->first();
                    SendMailJob::dispatch($pattern, $user->email);
                }
            }

        }
    }

    public function sacAbsenceInteraction(Request $request) {

        DB::table('sac_protocol')
            ->where('is_warranty', 0)
            ->where('is_cancelled', 0)
            ->where('is_completed', 0)
            ->where('authorized_id', NULL)
            ->where('origin', '!=', 3)
            ->orderBy('id', 'DESC')
            ->chunk(20, function($protocol)
            {
                foreach ($protocol as $key)
                {
                    $last_intereaction = SacMsgProtocol::where('sac_protocol_id', $key->id)
                        ->where('is_system', 0)
                        ->where('message_visible', 1)
                        ->orderBy('id', 'DESC')
                        ->first();

                    if ($last_intereaction) {
                        if ($last_intereaction->r_code != null) {
                            if ($last_intereaction->created_at >= date('Y-m-d H:i:s', strtotime('- 7 days'))) {

                                $user = SacClient::find($key->client_id);

                                if ($user->email) {

                                    $pattern = array(
                                        'id' => $key->id,
                                        'name' => $user->name,
                                        'title' => 'PROTOCOLO: '. $key->code,
                                        'description' => '',
                                        'template' => 'sac.alertClient',
                                        'subject' => 'Gree - Protocolo: '. $key->code,
                                    );

                                    SendMailJob::dispatch($pattern, $user->email);
                                }

                            } else {

                                $update_protocol = SacProtocol::find($key->id);
                                $update_protocol->is_cancelled = 1;
                                $update_protocol->save();

                                $message = new SacMsgProtocol;
                                $message->message = nl2br("Atendimento foi cancelado\npor falta de interação.");
                                $message->is_system = 1;
                                $message->sac_protocol_id = $key->id;
                                $message->save();

                                $user = SacClient::find($key->client_id);

                                if ($user->email) {

                                    $pattern = array(
                                        'id' => $key->id,
                                        'name' => $user->name,
                                        'title' => 'PROTOCOLO: '. $key->code,
                                        'description' => '',
                                        'template' => 'sac.protocolCancel',
                                        'subject' => 'Gree - Protocolo: '. $key->code,
                                    );

                                    if ($update_protocol->r_code) {
                                        NotifyUser('Protocolo: #'. $update_protocol->code, $update_protocol->r_code, 'fa-exclamation', 'text-info', 'Foi realizado o cancelamento automático do protocolo por falta de interação do cliente no prazo de 7 dias, clique aqui para visualizar.', env('APP_URL') .'/sac/warranty/interactive/'. $update_protocol->id);
                                    }
                                    SendMailJob::dispatch($pattern, $user->email);
                                }

                            }
                        }
                    }

                }
            });

    }

    public function sacAuthorizedApm(Request $request) {
        $www = env('APP_URL');

        DB::table('sac_os_protocol')
            ->leftjoin('sac_protocol', 'sac_protocol.id', '=', 'sac_os_protocol.sac_protocol_id')
            ->where('sac_os_protocol.expert_name', NULL)
            ->where('sac_os_protocol.is_cancelled', 0)
            ->select('sac_os_protocol.created_at as sop_created_at', 'sac_os_protocol.has_send_sms', 'sac_os_protocol.code as sop_code', 'sac_os_protocol.id as sop_id', 'sac_protocol.*')
            ->orderBy('id', 'DESC')
            ->chunk(20, function($protocol)
            {
                foreach ($protocol as $key)
                {
                    // Verify passed on 24H without update visit_date
                    if ($key->sop_created_at < date('Y-m-d H:i', strtotime('- 1 days'))) {

                        $protocol = SacProtocol::find($key->id);
                        $protocol->authorized_id = null;
                        $protocol->save();

                        $os = SacOsProtocol::find($key->sop_id);
                        $os->is_cancelled = 1;
                        $os->save();

                        $authorized = SacAuthorized::find($key->authorized_id);
                        if ($authorized) {
                            if ($authorized->live > 0) {
                                $live = $authorized->live - 1;
                                $authorized->is_active = $live == 0 ? 0 : 1;
                                $authorized->live = $live;
                                $authorized->save();
                            }

                            if ($authorized->email) {

                                if ($authorized->email_copy)
                                    $copy = [$authorized->email_copy];
                                else
                                    $copy = [];

                                $pattern = array(
                                    'title' => 'OS CANCELADA #'. $key->sop_code,
                                    'description' => nl2br("Olá! Por conta que não houve o agendamento em 24H, sua ordem de serviço foi cancelada. Por conta disso, você perdeu classificação. \n\n veja mais informações no link abaixo: \n\n <a href='". env('APP_URL') ."/autorizada/os'>". env('APP_URL') ."/autorizada/os</a>"),
                                    'copys' => $copy,
                                    'template' => 'misc.DefaultExternal',
                                    'subject' => 'OS: '. $key->sop_code .' foi cancelada!',
                                );

                                SendMailJob::dispatch($pattern, $authorized->email);
                            }

                            if ($protocol->r_code) {

                                $user = Users::where('r_code', $protocol->r_code)->first();

                                if ($user) {

                                    $pattern = array(
                                        'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                                        'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". env('APP_URL') ."/sac/warranty/interactive/". $protocol->id ."'>". env('APP_URL') ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                                        'template' => 'misc.Default',
                                        'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                                    );

                                    NotifyUser('Protocolo: #'. $protocol->code, $protocol->r_code, 'fa-exclamation', 'text-info', 'Foi realizado o cancelamento da O.S da autorizada: '. $authorized->name .', por não agendar em 24h a solicitação, clique aqui para visualizar.', env('APP_URL') .'/sac/warranty/interactive/'. $protocol->id);
                                    SendMailJob::dispatch($pattern, $user->email);
                                }
                            }

                        }

                    } else if ($key->sop_created_at < date('Y-m-d H:i', strtotime('- 12 hour')) and $key->has_send_sms == 0) {
                        $os = SacOsProtocol::find($key->sop_id);
                        $os->has_send_sms = 1;
                        $os->save();

                        $authorized = SacAuthorized::find($key->authorized_id);
                        if ($authorized) {

                            if ($authorized->email) {

                                if ($authorized->email_copy)
                                    $copy = [$authorized->email_copy];
                                else
                                    $copy = [];

                                $pattern = array(
                                    'title' => 'REALIZE O AGENDAMENTO DA OS #'. $key->sop_code,
                                    'description' => nl2br("Olá! Acesse seu painel e realize o agendamento da sua ordem de serviço, caso você não o faça em 24 horas, você perderá classificação e sua OS será cancelada. \n\n veja mais informações no link abaixo: \n\n <a href='". env('APP_URL') ."/autorizada/painel'>". env('APP_URL') ."/autorizada/painel</a>"),
                                    'copys' => $copy,
                                    'template' => 'misc.DefaultExternal',
                                    'subject' => 'REALIZE O AGENDAMENTO DA OS: '. $key->sop_code,
                                );

                                SendMailJob::dispatch($pattern, $authorized->email);
                            }

                            // SEND SMS FOR CONSUMER
                            $source = array('(', ')', ' ', '-');
                            $replace = array('', '', '', '');

                            $phone = "";
                            if ($authorized->phone_1) {
                                $phone = '55'. str_replace($source, $replace, $authorized->phone_1);

                            } else {

                                $phone = '55'. str_replace($source, $replace, $authorized->phone_2);
                            }

                            total_voice_sms(trim($phone), 'Olá, autorizada! Voce tem OS pendente de agendamento, acesse seu painel, antes que ela seja cancelada.');

                        }



                    }
                }
            });

    }

    public function ProtocolReportMonth(Request $request) {

        $from = date('Y-m-t', strtotime("-1 months", strtotime("NOW")));
        $to = date('Y-m-01', strtotime("-2 months"));

        $actual_month_f = date('Y-m-01', strtotime("-1 months", strtotime("NOW")));
        $actual_month_l = date('Y-m-t', strtotime("-1 months", strtotime("NOW")));
        $prev_month_f = date('Y-m-01', strtotime("-2 months", strtotime("NOW")));
        $prev_month_l = date('Y-m-t', strtotime("-2 months", strtotime("NOW")));

        if(date('d') == '01') {
            $protocol = SacProtocol::whereBetween('created_at', [$to, $from])
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('sac_model_protocol')
                        ->whereRaw('sac_protocol.id = sac_model_protocol.sac_protocol_id');
                })->get();

            $total_protocols = $protocol->whereBetween('created_at', [$actual_month_f, $actual_month_l])->count();
            $total_protocols_prev_month = $protocol->whereBetween('created_at', [$prev_month_f, $prev_month_l])->count();

            $total_refund = $protocol->where('is_refund', 1)->whereBetween('created_at', [$actual_month_f, $actual_month_l])->count();
            $total_refund_prev_month = $protocol->where('is_refund', 1)->whereBetween('created_at', [$prev_month_f, $prev_month_l])->count();

            $total_completed = $protocol->where('is_completed', 1)->whereBetween('created_at', [$actual_month_f, $actual_month_l])->count();
            $total_completed_prev_month = $protocol->where('is_completed', 1)->whereBetween('created_at', [$prev_month_f, $prev_month_l])->count();

            $total_progress = $protocol->where('in_progress', 0)->where('is_cancelled', 0)->where('is_completed', 0)->where('is_refund', 0)->whereBetween('created_at', [$actual_month_f, $actual_month_l])->count();
            $total_progress = $total_progress + $protocol->where('in_progress', 1)->where('is_cancelled', 0)->where('is_completed', 0)->where('is_refund', 0)->whereBetween('created_at', [$actual_month_f, $actual_month_l])->count();

            $total_progress_prev_month = $protocol->where('in_progress', 0)->where('is_cancelled', 0)->where('is_completed', 0)->where('is_refund', 0)->whereBetween('created_at', [$prev_month_f, $prev_month_l])->count();
            $total_progress_prev_month = $total_progress_prev_month + $protocol->where('in_progress', 1)->where('is_cancelled', 0)->where('is_completed', 0)->where('is_refund', 0)->whereBetween('created_at', [$prev_month_f, $prev_month_l])->count();

            $os_protocol = SacOsProtocol::whereBetween('created_at', [$to, $from])->get();

            $total_os = $os_protocol->where('is_paid', 1)->whereBetween('created_at', [$actual_month_f, $actual_month_l])->sum('total');
            $total_os_prev_month = $os_protocol->where('is_paid', 1)->whereBetween('created_at', [$prev_month_f, $prev_month_l])->sum('total');

            $total_forecast = $os_protocol->where('is_paid', 0)->whereBetween('created_at', [$actual_month_f, $actual_month_l])->sum('total');
            $total_forecast = $total_forecast + $os_protocol->where('is_paid', 1)->whereBetween('created_at', [$actual_month_f, $actual_month_l])->sum('total');

            $total_forecast_prev_month = $os_protocol->where('is_paid', 0)->whereBetween('created_at', [$prev_month_f, $prev_month_l])->sum('total');
            $total_forecast_prev_month = $total_forecast_prev_month + $os_protocol->where('is_paid', 1)->whereBetween('created_at', [$prev_month_f, $prev_month_l])->sum('total');

            $not_paid_os = $os_protocol->where('is_paid', 0)->whereBetween('created_at', [$actual_month_f, $actual_month_l])->sum('total');
            $not_paid_os_prev_month = $os_protocol->where('is_paid', 0)->whereBetween('created_at', [$prev_month_f, $prev_month_l])->sum('total');

            $multiply = 1.609344;
            $distance = getConfig("sac_distance_km");

            $total_points = array(
                'total' => 0,
                'commercial' => 0,
                'residential' => 0,
            );

            $list_points = collect();

            $count_commercial = 0;
            $count_residential = 0;

            foreach ($protocol as $key) {

                $latitude = $key->latitude;
                $longitude = $key->longitude;

                $query = "SELECT *, "
                    . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ,8) as distance "
                    . "from sac_authorized "
                    . "where is_active = 1 and "
                    . "type != 3 and "
                    . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ) ,8) <= $distance "
                    . "order by distance ASC "
                    . "LIMIT 10";

                $data = DB::select(DB::raw($query));

                if (count($data) == 0) {

                    foreach ($key->sacModelProtocol()->get() as $model) {

                        $count_residential = $count_residential + $model->sacProductAir(1)->count();
                        $count_commercial = $count_commercial + $model->sacProductAir(2)->count();
                    }
                    $total_points['total'] = $total_points['total'] + 1;

                    $list_points->push([
                        'code' => $key->code,
                        'address' => $key->address
                    ]);

                }
            }

            $total_points['residential'] = $count_residential;
            $total_points['commercial'] = $count_commercial;

            $pattern = array(
                'title' => 'RELATÓRIO PÓS VENDA: '. date('m/Y', strtotime("-1 months", strtotime("NOW"))),
                'total_protocols' => $total_protocols,
                'total_protocols_prev_month' => $total_protocols_prev_month,
                'total_refund' => $total_refund,
                'total_refund_prev_month' => $total_refund_prev_month,
                'total_completed' => $total_completed,
                'total_completed_prev_month' => $total_completed_prev_month,
                'total_progress' => $total_progress,
                'total_progress_prev_month' => $total_progress_prev_month,
                'total_os' => number_format($total_os, 2, ',', '.'),
                'total_os_prev_month' => number_format($total_os_prev_month, 2, ',', '.'),
                'total_forecast' =>  number_format($total_forecast, 2, ',', '.'),
                'total_forecast_prev_month' => number_format($total_forecast_prev_month, 2, ',', '.'),
                'not_paid_os' => number_format($not_paid_os, 2, ',', '.'),
                'not_paid_os_prev_month' => number_format($not_paid_os_prev_month, 2, ',', '.'),
                'total_points' =>  $total_points['total'],
                'total_commercial' => $total_points['commercial'],
                'total_residential' => $total_points['residential'],
                'list_points' => $list_points,
                'distance' => $distance,
                'template' => 'report.ReportProtocolMonth',
                'subject' => 'RELATÓRIO PÓS VENDA: '. date('m/Y', strtotime("-1 months", strtotime("NOW"))),
            );

            $user = Users::where('r_code', '0004')->first();
            SendMailJob::dispatch($pattern, $user->email);

            $user = Users::where('r_code', '0005')->first();
            SendMailJob::dispatch($pattern, $user->email);

            $user = Users::where('r_code', '1842')->first();
            SendMailJob::dispatch($pattern, $user->email);

            $user = Users::where('r_code', '2749')->first();
            SendMailJob::dispatch($pattern, $user->email);

            $user = Users::where('r_code', '2571')->first();
            SendMailJob::dispatch($pattern, $user->email);
			
			$user = Users::where('r_code', '2750')->first();
            SendMailJob::dispatch($pattern, $user->email);

            $user = Users::where('r_code', '2290')->first();
            SendMailJob::dispatch($pattern, $user->email);

            $user = Users::where('r_code', '4033')->first();
            SendMailJob::dispatch($pattern, $user->email);
			
			$user = Users::where('r_code', '653')->first();
            SendMailJob::dispatch($pattern, $user->email);
			
			$user = Users::where('r_code', '2290')->first();
            SendMailJob::dispatch($pattern, $user->email);

        }
    }


    private function sacViewAndSaveTrack($type, $action, $positions = '', $codes = '') {

        // Atualizar rastreio peças das OS
        if ($action == 1) {

            if ($type == 'view') {

                $codes = SacExpeditionRequest::where('transport', 'like', '%'. 'CORREIOS' .'%')
                    ->where('is_completed', 0)
                    ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('- 30 days')))
                    ->orderBy('id', 'ASC')
                    ->get();

                return $codes;

            } else if ($type == 'save') {

                foreach ($codes as $index => $code) {
                    if (isset($positions[$index]['status'])) {

                        if ($positions[$index]['status'] == 'Objeto entregue ao destinatário') {

                            $code->is_completed = 1;
                            $code->updated_at = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $positions[$index]['data'] .' '. $positions[$index]['hora']);
                            $code->save();

                            $sac_part_protocol = SacPartProtocol::where('sac_expedition_request_id', $code->id)->first();

                            if($sac_part_protocol) {

                                $protocol = SacProtocol::find($sac_part_protocol->sac_protocol_id);

                                $message = new SacMsgProtocol;
                                $message->message = nl2br("Peças chegaram no destinatário, aguardando realização do serviço. <br> Confirmado em <br>". date('d-m-Y', strtotime($code->updated_at)));
                                $message->is_system = 1;
                                $message->message_visible = 1;
                                $message->sac_protocol_id = $protocol->id;
                                $message->save();

                                $os = SacOsProtocol::where('sac_protocol_id', $sac_part_protocol->sac_protocol_id)->where('is_cancelled', 0)->where('is_paid', 0)->get();

                                foreach($os as $os_item) {
                                    $msg_os = new SacMsgOs;
                                    $msg_os->message = nl2br("Peças chegaram no destinatário, aguardando realização do serviço. <br> Confirmado em <br>". date('d-m-Y', strtotime($code->updated_at)));
                                    $msg_os->is_system = 1;
                                    $msg_os->message_visible = 1;
                                    $msg_os->sac_os_protocol_id = $os_item->id;
                                    $msg_os->save();
                                }

                                if ($protocol->r_code) {

                                    $user = Users::where('r_code', $protocol->r_code)->first();

                                    $pattern = array(
                                        'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                                        'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='https://gree-app.com.br/sac/warranty/interactive/". $protocol->id ."'>https://gree-app.com.br/sac/warranty/interactive/". $protocol->id ."</a>"),
                                        'template' => 'misc.Default',
                                        'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                                    );

                                    NotifyUser('Protocolo: #'. $protocol->code, $user->r_code, 'fa-exclamation', 'text-info', 'Peças envolvidas em seu protocolo, chegaram no destino, clique aqui para visualizar.', 'https://gree-app.com.br/sac/warranty/interactive/'. $protocol->id);
                                    SendMailJob::dispatch($pattern, $user->email);
                                }

                                $user = SacClient::find($protocol->client_id);

                                if ($user->email) {

                                    $pattern = array(
                                        'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                                        'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='https://gree-app.com.br/suporte/interacao/atendimento/". $protocol->id ."'>https://gree-app.com.br/suporte/interacao/atendimento/". $protocol->id ."</a>"),
                                        'template' => 'misc.DefaultExternal',
                                        'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                                    );

                                    SendMailJob::dispatch($pattern, $user->email);
                                }

                                $authorized = SacAuthorized::find($protocol->authorized_id);

                                if($authorized) {

                                    if ($authorized->email) {

                                        $os = SacOsProtocol::where('sac_protocol_id', $protocol->id)->where('authorized_id', $protocol->authorized_id)->first();
                                        if ($authorized->email_copy)
                                            $copy = [$authorized->email_copy];
                                        else
                                            $copy = [];

                                        $pattern = array(
                                            'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                                            'description' => nl2br("Olá! Temos atualizações da sua ordem de serviço, confirmação de chegada do seu pedido de peças no destinatário: (". $os->code .") <p>Confirmado em : ". date('d-m-Y', strtotime($user->updated_at)) ."</p></a>"),
                                            'copys' => $copy,
                                            'template' => 'misc.DefaultExternal',
                                            'subject' => 'O.S: '. $os->code .' atualização!',
                                        );

                                        SendMailJob::dispatch($pattern, $authorized->email);
                                    }
                                }
                            }

                        }
                    }
                }

            }
            // Atualizar rastreio loja de peças
        } else if ($action == 2) {
            if ($type == 'view') {

                $codes = SacBuyPart::where('track_code', '!=', NULL)
                    ->where('status', 3)
                    ->where('transport', 'like', '%'. 'CORREIOS' .'%')
                    ->where('is_cancelled', 0)
                    ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('- 30 days')))
                    ->orderBy('id', 'ASC')
                    ->get();

                return $codes;

            } else if ($type == 'save') {

                $assist_tect = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                    ->select('users.*')
                    ->where('user_on_permissions.grade', 99)
                    ->where('user_on_permissions.perm_id', 6)
                    ->where('users.filter_line', 1)
                    ->get();

                foreach ($codes as $index => $code) {
                    if (isset($positions[$index]['status'])) {

                        if ($positions[$index]['status'] == 'Objeto entregue ao destinatário') {

                            $code->status = 4;
                            $code->save();

                            if ($assist_tect) {

								$i = 1;
                                foreach($assist_tect as $key) {

                                    $pattern = array(
                                        'title' => 'ATUALIZAÇÃO DO PEDIDO DE COMPRA DE PEÇAS',
                                        'description' => nl2br("O correios confirmou a chegada da peça(s) no destinatário, clique abaixo para visualizar.: \n <a href='https://gree-app.com.br/sac/warranty/ob/'>https://gree-app.com.br/sac/warranty/ob/</a>"),
                                        'template' => 'misc.Default',
                                        'subject' => 'Atualização do pedido de compra de peças: '. $code->code,
                                    );

                                    NotifyUser('Atualização do pedido de compra de peças', $key->r_code, 'fa-exclamation', 'text-info', 'O correios confirmou a chegada da peça(s) no destinatário, clique aqui para visualizar.', 'https://gree-app.com.br/sac/warranty/ob/');
                                    SendMailJob::dispatch($pattern, $key->email)->delay(now()->addMinutes($i));
									$i++;

                                }
                            }

                        }


                    }

                }

            }
        }
    }

    public function sacTrackCorreios(Request $request, $action) {

        $codes = $this->sacViewAndSaveTrack('view', $action);

        if ($action == 1)
            $str_codes = implode(",", $codes->pluck('code_track')->toArray());
        else
            $str_codes = implode(",", $codes->pluck('track_code')->toArray());

        if ($codes) {
            $client = new GuzzleClient();
            try {
                $response = $client->request('GET', 'https://gree-app.com.br:3000/correios/rastreamento?option=1&codes='. $str_codes, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept'     => 'application/json'
                    ],

                ]);
                $positions = json_decode($response->getBody(), true);
                return $this->sacViewAndSaveTrack('save', $action, $positions, $codes);
            } catch (ClientErrorResponseException $exception) {
                // $responseBody = $exception->getResponse()->getBody(true);
                abort(600, 'Ocorreu um erro inesperado!');
            }
        } else {

            return 'Código não pode ser vazio...';
        }

    }

    public function resetCountFirstDay(Request $request) {

        if (date('d') == 01) {

            $counts = App\Model\ModuleCounters::where('is_reset', 1)->get();
            foreach ($counts as $key) {
                $key->value = 1;
                $key->save();
            }
        }
    }

    public function hourExtraReportRH(Request $request)
    {
        $a_date = date('Y-m-d');
        $lastdate = date("Y-m-21", strtotime($a_date));

        if ($a_date == $lastdate) {

            $user = Users::where('r_code', 4494)->first();

            if ($user) {

                $pattern = array(
                    'title' => 'LISTA DE COLABORADORES DE HORA EXTRA',
                    'description' => nl2br("<div style='text-align: left;'><p><b>Excel gerado com todos os colaboradores de home office, abaixo:</b></p></div><div style='text-align: center;'><p><b>Link do excel: </b><a href='". $request->root() ."/misc/hourextra/report/users?d=". date('Y-m-d') ."'>". $request->root() ."/misc/hourextra/report/users?d=". date('Y-m-d') ."</a></p></div>"),
                    'template' => 'misc.Default',
                    'subject' => 'Hora extra lista do dia: '. date('Y-m-d', strtotime('- 1 month')) .' à '. date('Y-m-d', strtotime('- 1 days')),
                );

                NotifyUser('Hora extra lista do dia: '. date('Y-m-d', strtotime('- 1 month')) .' à '. date('Y-m-d', strtotime('- 1 days')), $user->r_code, 'fa-exclamation', 'text-info', 'Baixe a lista de colaboradores desse mês para atualização da folha de ponto.', $request->root() .'/misc/hourextra/report/users?d='. date('Y-m-d'));
                SendMailJob::dispatch($pattern, $user->email);
            }
        }

    }

    public function adjustYearPriceCommercial() {

        if (date('Y-m-d') == date('Y-01-01')) {

            // Salvamento dos preços base do ano anterior antes da atualização
            $products = App\Model\Commercial\SetProductGroup::with('setProductOnGroup')->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();
            $table = new App\Model\Commercial\SetProductSave;
            $table->name = 'Preços de: '. date('01-Y', strtotime('- 1 year')) .' à '. date('12-Y', strtotime('- 1 year'));
            $table->collect = $products->toJson();
            $table->save();

            $this->setSessionDatesAvaibles();
            $uproducts = App\Model\Commercial\SetProduct::with('setProductOnGroup')
                ->where('is_active', 1)
                ->orderBy('position', 'ASC')
                ->get();

            foreach ($uproducts as $key) {

                // Busca todos os fatores do conjunto com base de dezembro a janeiro.
                $factors = $key->condition_in_month[date('Y-n-01', strtotime('- 1 year'))]['factors'];

                $total = $key->price_base;
                // Multiplica o valor com base em cada fator.
                foreach ($factors as $factor) {
                    $total = $total * (1+($factor/100));
                }

                // Salva o conjunto com valor atualizado.
                $key->evap_product_price = (ceil($total) * 35) / 100;
                $key->cond_product_price = (ceil($total) * 65) / 100;
                $key->save();

            }

            // Atualizar versão da tabela de preço
            $settings = App\Model\Commercial\Settings::where('command', 'version_table_price')->first();
            $med = ($settings->value - number_format($settings->value, 0)) * 100;
            if ($med >= 50 or $med <= 99)
                $settings->value = round($settings->value + 0.01, 2);
            else
                $settings->value = round($settings->value + 0.50);

            $settings->save();

        }
    }

    public function hasHolidayActive() {

        $usersHd = Users::where('is_holiday', 1)->get();

        foreach ($usersHd as $key) {

            if (date('Y-m-d') >= date('Y-m-d', strtotime($key->holiday_date_end))) {

                \App\Model\UserHoliday::where('user_r_code', $key->r_code)->delete();

                $key->is_holiday = 0;
                $key->holiday_date_end = null;
                $key->save();
            }
        }
    }
	
	public function JuridicalReportMonth(Request $request) {
		
		$year = date('Y', strtotime("-1 months"));
		$month = date('m', strtotime("-1 months"));
		
        $process = DB::table('juridical_process')
                ->select(DB::raw('juridical_process.*, count(juridical_process.id) as total_type, (Select sum(total) from juridical_process_cost where is_paid = 1) as total'))
                //->whereYear('juridical_process.date_received', date('Y'))
                ->groupBy('juridical_process.type_process')
                ->get();

		$law_cost = \App\Model\JuridicalLawFirmCost::with('juridical_law_firm', 'juridical_type_cost')
					->where('is_paid', 1)
                    ->whereYear('date_release', $year)
                    ->whereMonth('date_release', $month)
                    ->get();

		$process_cost = \App\Model\JuridicalProcessCost::with('juridical_type_cost', 'juridical_process')
						->where('is_paid', 1)
                        ->whereYear('date_release', $year)
                        ->get();


        $totals_process = \App\Model\JuridicalProcess::all();

		$type_process = array(
			['total' => 0], 
			['total' => 0],
			['total' => 0],
			['total' => 0],
			['total' => 0],
			['total' => 0],
		);

		foreach($process as $key) {
			$index = $key->type_process - 1;
			$type_process[$index]['total'] = $key->total_type;
		}  

		$pattern = array(
			'title' => 'RELATÓRIO JURÍDICO',
			'total_progress' => $totals_process->where('status', 1)->count(),
			'total_suspended' => $totals_process->where('status', 2)->count(),
			'total_closed' => $totals_process->where('status', 3)->count(),
			'total_sentence' => $totals_process->where('status', 4)->count(),
			'law_cost' => $law_cost,
			'process_cost' => $process_cost,
			'type_process' => $type_process,
			'template' => 'report.ReportJuridicalMonth',
			'subject' => 'RELATÓRIO JURÍDICO'
		);

		$users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')->where('user_on_permissions.perm_id', 23)->select('users.*')->get();
		foreach ($users as $key) {
			SendMailJob::dispatch($pattern, $key->email);
		}
	}
	
	public function entryExitEmployeesDailyStatus() {
        $load_requests = App\Model\EntryExitEmployees::where('is_liberate', 0)
            ->where('is_denied', 0)
            ->where('is_cancelled', 0)
            ->where('is_approv', 1)
            ->whereRaw("date(date_hour) < date('".date('Y-m-d')."')")
            ->get();

        foreach ($load_requests as $key) {
            $key->is_cancelled = 1;
            // Gree do brasil
            $key->cancelled_r_code = '00001';
            $key->cancelled_reason = 'Expirou o prazo da solicitação!';
            $key->request_action_time = date('Y-m-d H:i:s');
            $key->save();
        }
    }

    public function entryExitVisiteDailyStatus() {

        $load_requests = App\Model\LogisticsEntryExitRequestsSchedule::where('is_liberate', 0)
            ->where('is_denied', 0)
            ->where('is_cancelled', 0)
            ->whereHas('logistics_entry_exit_requests', function ($q) {
                $q->where('is_approv', 1)
                ->whereIn('type_reason', [3,9,10]);
            })
            ->whereRaw("DATEDIFF(date('".date('Y-m-d')."'), date(date_hour)) > 2")
            ->get();

		dd($load_requests);
        foreach ($load_requests as $key) {
            $key->is_cancelled = 1;
            // Gree do brasil
            $key->cancelled_r_code = '00001';
            $key->cancelled_reason = 'Expirou o prazo da solicitação!';
            $key->request_action_time = date('Y-m-d H:i:s');
            $key->save();
        }
    }

    public function entryExitTransportDailyStatus() {

        $load_requests = App\Model\LogisticsEntryExitRequests::where('is_liberate', 0)
            ->where('is_denied', 0)
            ->where('is_cancelled', 0)
            ->where('is_approv', 1)
            ->whereNotIn('type_reason', [3,9,10])
            ->whereRaw("DATEDIFF(date('".date('Y-m-d')."'), date(date_hour)) > 2")
            ->get();

        foreach ($load_requests as $key) {
            $key->is_cancelled = 1;
            // Gree do brasil
            $key->cancelled_r_code = '00001';
            $key->cancelled_reason = 'Expirou o prazo da solicitação!';
            $key->request_action_time = date('Y-m-d H:i:s');
            $key->save();
        }
    }
	
	public function operationalOrderInvoiceEmail(Request $request) {

        try {
            $refund = new OrderInvoiceEmail('gree-app.com.br','nfs@gree-app.com.br','2t&0gn6J');
            $refund->startTaskInvoice();
        }
        catch(\Exception $e) {
            return Log::error($e->getMessage());
        }
    }  
}
