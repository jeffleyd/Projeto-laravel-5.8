<?php

namespace App\Services\Departaments\Administration\Trip;

use App;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Model\TripPeoples;
use Illuminate\Http\Request;

Trait TripPlanTrait
{

    public function sendEmailMySelf($trip_plan, $immediates) {

        $pattern = array(
            'id' => $trip_plan->id,
            'immediates' => $immediates,
            'title' => 'PEDIDO FOI REALIZADO',
            'description' => '',
            'template' => 'trip.RequestSuccess',
            'subject' => 'Pedido da viagem: #'. $trip_plan->id .' "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
        );
        SendMailJob::dispatch($pattern, $trip_plan->trips->user->email);
    }    

    public function sendEmailAnalyze($trip_plan, $immediate, $request) {

        $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();
        $pattern = array(
            'name' => $trip_plan->trips->user->full_name,
            'imd' => $immediate->r_code,
            'id' => $trip_plan->id,
            'r_code' => $trip_plan->trips->user->r_code,
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
            'subject' => 'Pedido de aprovação de viagem: #'. $trip_plan->id .' "'. getENameF($trip_plan->trips->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
        );

        LogSystem("Colaborador enviou planejamento para análise de aprovação", $request->session()->get('r_code'));
        SendMailJob::dispatch($pattern, $immediate->email);
        App::setLocale($immediate->lang);
        NotifyUser(__('layout_i.n_trip_006_title'), $immediate->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_006'), $request->root() .'/trip/review/'. $trip_plan->id);
        App::setLocale($request->session()->get('lang'));
    }    

    public function sendEmailApproved($trip_plan, $request) {

        $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();
        $pattern = array(
            'name' => getENameFull($trip_plan->trips->r_code),
            'id' => $trip_plan->id,
            'r_code' => $trip_plan->trips->r_code,
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
            'peoples' => $peoples,
            'title' => 'PEDIDO FOI APROVADO',
            'description' => 'Parabéns! Sua viagem foi aprovada, agora aguarde a cotação da viagem, assim que tivermos o bilhete no sistema, iremos te avisar.',
            'template' => 'trip.RequestHasApprov',
            'subject' => 'Sua viagem foi aprovada: #'. $trip_plan->id .' "'. getENameF($trip_plan->trips->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
        );

        SendMailJob::dispatch($pattern, $trip_plan->trips->user->email);
        App::setLocale($trip_plan->trips->user->lang);
        NotifyUser(__('layout_i.n_trip_001_title'), $trip_plan->trips->user->r_code, 'fa-check', 'text-success', __('layout_i.n_trip_001'), $request->root() .'/trip/review/'. $trip_plan->id);
        App::setLocale($request->session()->get('lang'));
    }

    public function sendEmailReproved($trip_plan, $request) {

        $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();
        $pattern = array(
            'name' => getENameFull($trip_plan->trips->r_code),
            'id' => $trip_plan->id,
            'r_code' => $trip_plan->trips->r_code,
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
            'peoples' => $peoples,
            'title' => 'PEDIDO REPROVADO',
            'description' => 'Infelizmente sua viagem foi reprovada, acesse o sistema para ver o motivo da reprovação.',
            'template' => 'trip.RequestHasApprov',
            'subject' => 'Sua viagem foi reprovada: #'. $trip_plan->id .' "'. getENameF($trip_plan->trips->user->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
        );

        SendMailJob::dispatch($pattern, $trip_plan->trips->user->email);        
        App::setLocale($trip_plan->trips->user->lang);
        NotifyUser(__('layout_i.n_trip_002_title'), $trip_plan->trips->user->r_code, 'fa-times', 'text-danger', __('layout_i.n_trip_002'), $request->root() .'/trip/review/'. $trip_plan->id);
        App::setLocale($request->session()->get('lang'));
    }

    public function sendEmailSuspended($trip_plan, $request) {

        $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();
        $pattern = array(
            'name' => getENameFull($trip_plan->trips->r_code),
            'id' => $trip_plan->id,
            'r_code' => $trip_plan->trips->r_code,
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
            'peoples' => $peoples,
            'title' => 'PEDIDO SUSPENSO',
            'description' => 'Sua viagem foi suspensa, acesse o sistema para ver o motivo da suspensão.',
            'template' => 'trip.RequestHasApprov',
            'subject' => 'Sua viagem foi suspensa: #'. $trip_plan->id .' "'. getENameF($trip_plan->trips->user->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
        );

        SendMailJob::dispatch($pattern, $trip_plan->trips->user->email);        
        App::setLocale($trip_plan->trips->user->lang);
        NotifyUser(__('layout_i.n_trip_002_title'), $trip_plan->trips->user->r_code, 'fa-times', 'text-danger', __('layout_i.n_trip_002'), $request->root() .'/trip/review/'. $trip_plan->id);
        App::setLocale($request->session()->get('lang'));
    }

    public function sendEmailRevert($trip_plan, $immediate, $request) {

        $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();
        $pattern = array(
            'name' => $trip_plan->trips->user->full_name,
            'imd' => $immediate->r_code,
            'id' => $trip_plan->id,
            'r_code' => $trip_plan->trips->user->r_code,
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
            'title' => 'PEDIDO RETROCEDIDO',
            'description' => 'Solicitação retrocedida<br>Motivo: '.$request->description,
            'peoples' => $peoples,
            'template' => 'trip.RequestApprov',
            'subject' => 'Pedido de aprovação de viagem: #'. $trip_plan->id .' "'. getENameF($trip_plan->trips->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
        );

        LogSystem("Colaborador enviou planejamento para análise de aprovação", $request->session()->get('r_code'));
        SendMailJob::dispatch($pattern, $immediate->email);
        App::setLocale($immediate->lang);
        NotifyUser(__('layout_i.n_trip_006_title'), $immediate->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_006'), $request->root() .'/trip/review/'. $trip_plan->id);
        App::setLocale($request->session()->get('lang'));
    }
}    
