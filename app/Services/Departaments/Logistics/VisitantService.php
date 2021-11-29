<?php

namespace App\Services\Departaments\Logistics;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMailJob;

use App\Services\Departaments\Interfaces\Analyze;
use App\Services\Departaments\Logistics\LogisticsTrait;
use App\Services\Departaments\Logistics\Rules\VisitantServiceRules;

class VisitantService implements Analyze
{
    use VisitantServiceRules;
    use LogisticsTrait;

    public $model;
    public $request;

    public function __construct($model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }   
    
    public function startAnalyze(): array
    {
        $this->execMethods(['rulesVerifyRegisterAnalyze']);

        $arr_approv = [];

        foreach($this->model->request_user->immediates as $immediate) {

            array_push($arr_approv,
                [
                    'version' => $this->model->rtd_status['last_version'] + 1, 
                    'r_code' => $immediate->r_code, 
                    'position' => 1
                ]
            );
        }    

        foreach($this->model->rtd_approvers() as $key) {

            array_push($arr_approv,
                [
                    'version' => $this->model->rtd_status['last_version'] + 1, 
                    'r_code' => $key->r_code, 
                    'position' => $key->position + 1
                ]
            );
        }

        $users_approv = $this->model->request_user->immediates;

        $schedule = $this->loadScheduleVisitorHtml($this->model->logistics_entry_exit_requests_schedule);
        $content = "Solicitante: ".$this->model->request_user->full_name."<br>". $schedule;

        $this->defaultEmail(
            $users_approv,
            'SOLICITAÇÃO DE APROVAÇÃO DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
            'REALIZAR APROVAÇÃO DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
            '/logistics/request/visitor/service/list/approv?code='.$this->model->code.'',
            'Realizar Aprovação',
            $content,
            true,
            true,
            2
        );

        return $arr_approv;
    }

    public function approvAnalyze() {

        if($this->model->rtd_status['status']['code'] == 3) {

            $users_approv = $this->model->rtd_status['status']['validation'];

            $schedule = $this->loadScheduleVisitorHtml($this->model->logistics_entry_exit_requests_schedule);
            $content = "Solicitante: ".$this->model->request_user->full_name."<br>". $schedule;

            $this->defaultEmail(
                $users_approv,
                'SOLICITAÇÃO DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
                'REALIZAR APROVAÇÃO DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
                '/logistics/request/visitor/service/list/approv?code='.$this->model->code.'',
                'Realizar Aprovação',
                $content,
                false,
                false,
                2
            );
        } 
        elseif($this->model->rtd_status['status']['code'] == 2) {
            $this->model->is_approv = 1;
            $this->model->has_analyze = 0;
            $this->model->save();

            $collect = collect([]);
            $collect->push($this->model->request_user);

            $schedule = $this->loadScheduleVisitorHtml($this->model->logistics_entry_exit_requests_schedule);
            $content = "Solicitante: ".$this->model->request_user->full_name."<br>". $schedule;

            $this->defaultEmail(
                $collect,
                'APROVADO SOLICITAÇÃO '.$this->model->code.' DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
                'APROVADO SOLICITAÇÃO DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
                '/logistics/request/visitor/service/list?code='.$this->model->code.'',
                'Visualizar solicitação aprovada', 
                $content,
                true,
                false,
                2
            );
        }

        return 'Solicitação aprovada com sucesso';
    }

    public function reprovAnalyze() {

        $this->model->is_reprov = 1;
        $this->model->has_analyze = 0;
        $this->model->save();
        
        $users = $this->model->rtd_status['status']['validation']->where('r_code', $this->request->session()->get('r_code'))->first()->users;
        $collect = collect([]);
        $collect->push($this->model->request_user);

        $schedule = $this->loadScheduleVisitorHtml($this->model->logistics_entry_exit_requests_schedule);
        $content = "Solicitante: ".$this->model->request_user->full_name;

        $this->defaultEmail(
            $collect,
            'REPROVADA SOLICITAÇÃO '.$this->model->code.' DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
            'REPROVADA SOLICITAÇÃO DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'', 
            '/logistics/request/visitor/service/list?code='.$this->model->code.'', 
            'Visualizar solicitação reprovada',
            ''.$content.'<br/><span style="color:#fb5151;">'.$users->full_name.' ('.$users->r_code.') REPROVOU A SOLICITAÇÃO</span> <br>Motivo: '.$this->request->description.'<br/>'.$schedule,
            true,
            false,
            2
        );               

        return 'Solicitação reprovada com sucesso';
    }

    public function suspendedAnalyze() {
        
        $users = $this->model->rtd_status['status']['validation']->where('r_code', $this->request->session()->get('r_code'))->first()->users;
        $collect = collect([]);
        $collect->push($this->model->request_user);

        $schedule = $this->loadScheduleVisitorHtml($this->model->logistics_entry_exit_requests_schedule);
        $content = "Solicitante: ".$this->model->request_user->full_name;

        $this->defaultEmail(
            $collect,
            'SOLICITAÇÃO '.$this->model->code.' SUSPENSA DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
            'SOLICITAÇÃO SUSPENSA DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'', 
            '/logistics/request/visitor/service/list?code='.$this->model->code.'', 
            'Visualizar solicitação suspensa',
            ''.$content.'<br/><span style="color:#fb5151;">'.$users->full_name.' ('.$users->r_code.') SUSPENDEU A SOLICITAÇÃO</span> <br>Motivo: '.$this->request->description.'<br/>'.$schedule,
            true,
            false,
            2
        );

        return 'Solicitação suspensa com sucesso';
    }

    public function revertAnalyze() {

        $users_approv = $this->model->rtd_analyze->where('position', $this->request->position);
        $users = $this->model->rtd_status['status']['validation']->where('r_code', $this->request->session()->get('r_code'))->first()->users;

        $schedule = $this->loadScheduleVisitorHtml($this->model->logistics_entry_exit_requests_schedule);
        $content = "Solicitante: ".$this->model->request_user->full_name;

        $this->defaultEmail(
            $users_approv,
            'SOLICITAÇÃO DE APROVAÇÃO DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
            'SOLICITAÇÃO REVERTIDA',
            '/logistics/request/visitor/service/list/approv?code='.$this->model->code.'', 
            'Realizar Aprovação',
            ''.$content.'<br/><span style="color:#fb5151;">'.$users->full_name.' ('.$users->r_code.') REVERTEU A SOLICITAÇÃO</span> <br>Motivo: '.$this->request->description.'<br/>'.$schedule,
            false,
            false,
            2
        );
        
        return 'Solicitação revertida com sucesso';
    }    

    public function approvNowAnalyze() {

        $collect = collect([]);
        $collect->push($this->model->request_user);

        $schedule = $this->loadScheduleVisitorHtml($this->model->logistics_entry_exit_requests_schedule);
        $content = "Solicitante: ".$this->model->request_user->full_name."<br>". $schedule;

        $this->defaultEmail(
            $collect,
            'APROVADO SOLICITAÇÃO '.$this->model->code.' DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
            'APROVADO SOLICITAÇÃO DE '.mb_strtoupper($this->model->type_reason_name, 'UTF-8').'',
            '/logistics/request/visitor/service/list?code='.$this->model->code.'',
            'Visualizar solicitação aprovada', 
            $content,
            true,
            false,
            2
        );

        return 'Solicitação aprovada com sucesso';
    }    
	
	public function cancelAnalyze() {        
        return;
    }
}   

