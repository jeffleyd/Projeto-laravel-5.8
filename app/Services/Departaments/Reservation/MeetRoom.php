<?php

namespace App\Services\Departaments\Reservation;

use Illuminate\Http\Request;

use App\Services\Departaments\Interfaces\Analyze;
use App\Services\Departaments\Reservation\Rules\MeetRoomRules;
use App\Services\Departaments\Reservation\ReservationTrait;
use phpDocumentor\Reflection\Types\This;

class MeetRoom implements Analyze
{
    use MeetRoomRules;
    use ReservationTrait;

    public $model;
    public $request;

    public function __construct($model, Request $request)
    {
        
        $this->model = $model;
        $this->request = $request;
    }   
    
    public function startAnalyze(): array
    {
        $arr_approv = [];

        if($this->model->users->immediates->count() == 0)
            throw new \Exception("Solicitante não tem chefe imediato cadastrado");

        foreach($this->model->users->immediates as $immediate) {
            // NOVA FUNCAO DE EMAIL
            $this->sendEmailAnalyzeMeetRoom($this->model, $immediate);

            array_push($arr_approv,
                [
                    'version' => $this->model->rtd_status['last_version'] + 1, 
                    'r_code' => $immediate->r_code, 
                    'position' => 1
                ]
            );
        }

        return $arr_approv;
    }

    public function approvAnalyze(){
        
        if($this->model->rtd_status['status']['code'] == 3) {

            $users_approv = $this->model->rtd_status['status']['validation'];
            foreach($users_approv as $user) {
                $this->sendEmailAnalyzeMeetRoom($this->model, $user->users);
            }
        } 
        elseif($this->model->rtd_status['status']['code'] == 2) {
            $this->model->is_approv = 1;
            $this->model->has_analyze = 0;
            $this->model->save();

            // NOVA FUNCAO DE EMAIL
            $this->sendEmailApprovedMeetRoom($this->model, $this->request);
        }

        return 'Solicitação aprovada com sucesso';
    }

    public function reprovAnalyze() {

        $this->model->is_reprov = 1;
        $this->model->has_analyze = 0;
        $this->model->save();
        
        // NOVA FUNCAO DE EMAIL
        $this->sendEmailRepprovedMeetRoom($this->model, $this->request);       
        
        return 'Solicitação reprovada com sucesso';
    }

    public function suspendedAnalyze() {    
        // NOVA FUNCAO DE EMAIL
        $this->sendEmailSuspendedMeetRoom($this->model, $this->request); 

        return 'Solicitação suspensa com sucesso';
    }

    // Nao vao ser usados nessa solicitacao
    public function revertAnalyze() {
    }    

    public function approvNowAnalyze() {
    }
}