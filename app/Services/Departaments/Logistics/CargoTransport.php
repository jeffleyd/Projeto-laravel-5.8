<?php

namespace App\Services\Departaments\Logistics;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMailJob;

use App\Services\Departaments\Interfaces\Analyze;
use App\Services\Departaments\Logistics\Rules\CargoTransportRules;
use App\Services\Departaments\Logistics\LogisticsTrait;

class CargoTransport implements Analyze
{
    use CargoTransportRules;
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

        $this->execMethods(['rulesVerifyRegisterAnalyze', 'rulesVerifyReleaseHourRequest']);

        $arr_approv = [];

        /*if($this->model->request_user->immediates->count() == 0)
            throw new \Exception("Solicitante não tem chefe imediato cadastrado");*/

        /*foreach($this->model->request_user->immediates as $immediate) {

            array_push($arr_approv,
                [
                    'version' => $this->model->rtd_status['last_version'] + 1, 
                    'r_code' => $immediate->r_code, 
                    'position' => 1
                ]
            );
        } */   

        foreach($this->model->logistics_warehouse->analyze_approv as $key) {

            array_push($arr_approv,
                [
                    'version' => $this->model->rtd_status['last_version'] + 1, 
                    'r_code' => $key->r_code, 
                    'position' => $key->position
                ]
            );
        }

        $users_approv = $this->model->logistics_warehouse->analyze_approv->pluck('users');
        $this->defaultEmail(
            $users_approv,
            'SOLICITAÇÃO DE APROVAÇÃO DE TRANSPORTE DE CARGA',
            'REALIZAR APROVAÇÃO DE TRANSPORTE DE CARGA',
            '/logistics/request/cargo/transport/approv/list?code='.$this->model->code.'',
            'Realizar Aprovação',
            'Solicitante: '.$this->model->request_user->full_name.'',
            true,
            false,
            1
        );

        return $arr_approv;
    }

    public function approvAnalyze() {

        if($this->model->rtd_status['status']['code'] == 3) {

            $users_approv = $this->model->rtd_status['status']['validation'];
            $this->defaultEmail(
                $users_approv,
                'SOLICITAÇÃO DE APROVAÇÃO DE TRANSPORTE DE CARGA',
                'REALIZAR APROVAÇÃO DE TRANSPORTE DE CARGA',
                '/logistics/request/cargo/transport/approv/list?code='.$this->model->code.'',
                'Realizar Aprovação',
                '',
                false,
                false,
                1
            );
        } 
        elseif($this->model->rtd_status['status']['code'] == 2) {
            $this->model->is_approv = 1;
            $this->model->has_analyze = 0;
            $this->model->save();

            $collect = collect([]);
            $collect->push($this->model->request_user);
            $this->defaultEmail(
                $collect,
                'APROVADO SOLICITAÇÃO '.$this->model->code.' DE TRANSPORTE DE CARGA',
                'APROVADO SOLICITAÇÃO DE TRANSPORTE DE CARGA',
                '/logistics/request/cargo/transport/list?code='.$this->model->code.'',
                'Visualizar solicitação aprovada', 
                '',
                true,
                false,
                1
            );
        }

        return 'Solicitação aprovado com sucesso';
    }

    public function reprovAnalyze() {

        $this->model->is_reprov = 1;
        $this->model->has_analyze = 0;
        $this->model->save();
        
        $users = $this->model->rtd_status['status']['validation']->where('r_code', $this->request->session()->get('r_code'))->first()->users;
        $collect = collect([]);
        $collect->push($this->model->request_user);

        $this->defaultEmail(
            $collect,
            'REPROVADA SOLICITAÇÃO '.$this->model->code.' DE TRANSPORTE DE CARGA',
            'REPROVADA SOLICITAÇÃO DE TRANSPORTE DE CARGA', 
            '/logistics/request/cargo/transport/list?code='.$this->model->code.'', 
            'Visualizar solicitação reprovada',
            '<span style="color:#fb5151;">'.$users->full_name.' ('.$users->r_code.') REPROVOU A SOLICITAÇÃO</span> <br>Motivo: '.$this->request->description.'',
            true,
            false,
            1
        );               

        return 'Solicitação reprovada com sucesso';
    }

    public function suspendedAnalyze() {
        
        $users = $this->model->rtd_status['status']['validation']->where('r_code', $this->request->session()->get('r_code'))->first()->users;
        $collect = collect([]);
        $collect->push($this->model->request_user);

        $this->defaultEmail(
            $collect,
            'SOLICITAÇÃO '.$this->model->code.' SUSPENSA DE TRANSPORTE DE CARGA',
            'SOLICITAÇÃO SUSPENSA DE TRANSPORTE DE CARGA', 
            '/logistics/request/cargo/transport/list?code='.$this->model->code.'', 
            'Visualizar solicitação suspensa',
            '<span style="color:#fb5151;">'.$users->full_name.' ('.$users->r_code.') SUSPENDEU A SOLICITAÇÃO</span> <br>Motivo: '.$this->request->description.'',
            true,
            false,
            1
        );

        return 'Solicitação suspensa com sucesso';
    }

    public function revertAnalyze() {

        $users_approv = $this->model->rtd_analyze->where('position', $this->request->position);
        $users = $this->model->rtd_status['status']['validation']->where('r_code', $this->request->session()->get('r_code'))->first()->users;

        $this->defaultEmail(
            $users_approv,
            'SOLICITAÇÃO DE APROVAÇÃO DE TRANSPORTE DE CARGA',
            'SOLICITAÇÃO REVERTIDA',
            '/logistics/request/cargo/transport/approv/list?code='.$this->model->code.'', 
            'Realizar Aprovação',
            '<span style="color:#fb5151;">'.$users->full_name.' ('.$users->r_code.') REVERTEU A SOLICITAÇÃO</span> <br>Motivo: '.$this->request->description.'',
            false,
            false,
            1
        );
        
        return 'Solicitação revertida com sucesso';
    }    

    public function approvNowAnalyze() {

        $collect = collect([]);
        $collect->push($this->model->request_user);
        $this->defaultEmail(
            $collect,
            'APROVADO SOLICITAÇÃO '.$this->model->code.' DE TRANSPORTE DE CARGA',
            'APROVADO SOLICITAÇÃO DE TRANSPORTE DE CARGA',
            '/logistics/request/cargo/transport/list?code='.$this->model->code.'',
            'Visualizar solicitação aprovada', 
            '',
            true,
            false,
            1
        );
        
        return 'Solicitação aprovada com sucesso';
    }
	
	public function cancelAnalyze() {        
        return;
    }
}