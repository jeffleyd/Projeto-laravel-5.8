<?php

namespace App\Services\Departaments\Reservation;

use App;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ReservationTrait
{

    public function sendEmailAnalyzeMeetRoom($model, $immediate)
    {
        $dateTime = date('d-m-Y', strtotime($model->start_time)) . ' das ' .
            date('H:i', strtotime($model->start_time)) . ' as ' .
            date('H:i', strtotime($model->end_time));
        $pattern = array(
            'title' => 'SOLICITAÇÃO DE SALA DE REUNIÃO: EM ANÁLISE',
            'description' => nl2br(
                '<div style="text-align: justify">'.
                '<b>Colaborador:  </b>' . $model->users->full_name . '<br>' .
                '<b>Solicitação:  </b>' . $model->id . '<br>' .
                '<b>Sala Solicitada:  </b>' . $model->meet_room->sala . ' <br> ' .
                '<b>Data Reserva:  </b>' . $dateTime .' <br> '.
                '<b>Motivo da Reserva:  </b>' . $model->reason->reason . ' <br> ' .
                '<b>Descrição da Reserva:  </b>' . $model->description . ' <br> <br> '.
                '<span style="background: #e3e3e3;padding: 14px;font-weight: bold;"> Observação da Análise: ' . '  -----.' . '</span> </div>'),
            'template' => 'misc.Default',
            'subject' => 'Solicitação de sala de reuniao em análise',
        );

        NotifyUser(
            'SOLICITAÇÃO RESERVA DE SALA DE REUNIAO: Em análise!',
            $immediate->r_code,
            'fa-check',
            'text-success',
            'Sua solicitação foi para análise.',
            env('APP_URL') . '/administration/reservation/meetroom/analyze'
        );

        SendMailJob::dispatch($pattern, $immediate->email);
    }

    public function sendEmailApprovedMeetRoom($model, $request)
    {
        $dateTime = date('d-m-Y', strtotime($model->start_time)) . ' das ' .
            date('H:i', strtotime($model->start_time)) . ' as ' .
            date('H:i', strtotime($model->end_time));

        $pattern = array(
            'title' => 'SOLICITAÇÃO DE SALA DE REUNIÃO: APROVADA.',
            'description' => nl2br(
                '<div style="text-align: justify">'.
                '<b>Colaborador:  </b>' . $model->users->full_name . '<br>' .
                '<b>Solicitação:  </b>' . $model->id . '<br>' .
                '<b>Sala Solicitada:  </b>' . $model->meet_room->sala . ' <br> ' .
                '<b>Data Reserva:  </b>' . $dateTime .' <br> '.
                '<b>Motivo da Reserva:  </b>' . $model->reason->reason . ' <br> ' .
                '<b>Descrição da Reserva:  </b>' . $model->description . ' <br> <br> '.
                '<span style="background: #e3e3e3;padding: 14px;font-weight: bold;"> Observação da Análise: ' . $request->description. '</span> </div>'),
            'template' => 'misc.Default',
            'subject' => 'Solicitação de sala de reunião aprovada.'
        );

        NotifyUser(
            'SOLICITAÇÃO DE SALA DE REUNIÃO: APROVADA.',
            $model->users->r_code,
            'fa-check',
            'text-success',
            'Sua solicitação de sala de reunião foi aprovada.',
            env('APP_URL') . '/administration/reservation/meetroom/analyze'
        );

        SendMailJob::dispatch($pattern, $model->users->email);
    }
    
    public function sendEmailRepprovedMeetRoom($model, $request)
    {
       
        $dateTime = date('d-m-Y', strtotime($model->start_time)) . ' das ' .
            date('H:i', strtotime($model->start_time)) . ' as ' .
            date('H:i', strtotime($model->end_time));
            
        $pattern = array(
            'title' => 'SOLICITAÇÃO DE SALA DE REUNIÃO: REPROVADA.',
            'description' => nl2br(
                '<div style="text-align: justify">'.
                '<b>Colaborador:  </b>' . $model->users->full_name . '<br>' .
                '<b>Solicitação:  </b>' . $model->id . '<br>' .
                '<b>Sala Solicitada:  </b>' . $model->meet_room->sala . ' <br> ' .
                '<b>Data Reserva:  </b>' . $dateTime .' <br> '.
                '<b>Motivo da Reserva:  </b>' . $model->reason->reason . ' <br> ' .
                '<b>Descrição da Reserva:  </b>' . $model->description . ' <br> <br> '.
                '<span style="background: #e3e3e3;padding: 14px;font-weight: bold;"> Observação da Análise: ' . $request->description. '</span> </div>'),
            'template' => 'misc.Default',
            'subject' => 'Solicitação de sala de reunião reprovada.',
        );
        
        NotifyUser(
            'SOLICITAÇÃO DE SALA DE REUNIÃO: REPROVADA.',
            $model->users->r_code,
            'fa-check',
            'text-success',
            'Sua solicitação de sala de reunião foi aprovada.',
            env('APP_URL') . '/administration/reservation/meetroom/analyze'
        );
        
        SendMailJob::dispatch($pattern, $model->users->email);
        
    }

    public function sendEmailSuspendedMeetRoom($model, $request)
    {
        $dateTime = date('d-m-Y', strtotime($model->start_time)) . ' das ' .
            date('H:i', strtotime($model->start_time)) . ' as ' .
            date('H:i', strtotime($model->end_time));

        $pattern = array(
            'title' => 'SOLICITAÇÃO DE SALA DE REUNIÃO: SUSPENSA.',
            'description' => nl2br(
                '<div style="text-align: justify">'.
                '<b>Colaborador:  </b>' . $model->users->full_name . '<br>' .
                '<b>Solicitação:  </b>' . $model->id . '<br>' .
                '<b>Sala Solicitada:  </b>' . $model->meet_room->sala . ' <br> ' .
                '<b>Data Reserva:  </b>' . $dateTime .' <br> '.
                '<b>Motivo da Reserva:  </b>' . $model->reason->reason . ' <br> ' .
                '<b>Descrição da Reserva:  </b>' . $model->description . ' <br> <br> '.
                '<span style="background: #e3e3e3;padding: 14px;font-weight: bold;"> Observação da Análise: ' . $request->description. '</span> </div>'),
            'template' => 'misc.Default',
            'subject' => 'Solicitação de sala de reunião suspensa.',
        );

        NotifyUser(
            'SOLICITAÇÃO DE SALA DE REUNIÃO: SUSPENSA.',
            $model->users->r_code,
            'fa-check',
            'text-success',
            'Sua solicitação de sala de reunião encontra-se suspensa.',
            env('APP_URL') . '/administration/reservation/meetroom/analyze'
        );

        SendMailJob::dispatch($pattern, $model->users->email);
    }
}
