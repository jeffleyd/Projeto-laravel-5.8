<?php 

namespace App\Services\Departaments\Administration\Trip;

use Illuminate\Http\Request;

use App;
use App\Services\Departaments\Helpers;
use App\Services\Departaments\Interfaces\Analyze;
use App\Services\Departaments\Administration\Trip\Rules\TripPlanRules;
use App\Services\Departaments\Administration\Trip\TripPlanTrait;

class TripPlan implements Analyze
{

    use Helpers;
    use TripPlanRules;
    use TripPlanTrait;

    public function __construct($model, Request $request) {
        $this->model = $model;
        $this->request = $request;
    }

    public function startAnalyze(): array 
    {
        $this->rulesVerifyDateOriginPass();

        $arr_approv = [];
        $approvers = $this->model->rtd_approvers();

        $arr_response = $this->bossToBoss(
            $arr_approv,
            $this->model->trips->user->immediates,
            $this->model->rtd_status['last_version'] + 1
        );
		
        $arr_approv = $arr_response['arr_approv'];
        $last_pos = $arr_response['last_position'];

        
		foreach ($approvers as $key) {
            array_push($arr_approv,
                [
                    'version' => $this->model->rtd_status['last_version'] + 1,
                    'r_code' => $key->r_code,
                    'position' => $key->position + $last_pos,
                    'mark' => $key->mark
                ]
            );
        }
        
        
        $dateOrigin = $this->rulesVerifyDateOrigin();
        $last_position = count($arr_approv) ? $arr_approv[count($arr_approv)-1]['position'] : 0;
        if($dateOrigin) {
            foreach ($dateOrigin as $key) {
                $last_position++;
                array_push($arr_approv, [
                    'version' => $this->model->rtd_status['last_version'] + 1,
                    'r_code' => $key->r_code,
                    'position' => $last_position
                ]);
            }
        }

        foreach ($this->model->trips->user->immediates as $immediate) {
            $this->sendEmailAnalyze($this->model, $immediate, $this->request);
        }

        $this->sendEmailMySelf($this->model, $this->model->trips->user->immediates);
        return $arr_approv;
    }   
    
    public function approvAnalyze() {

        $approvers = $this->model->rtd_status['collect'];
        $dateOrigin = $this->rulesVerifyDateOrigin();
        if($dateOrigin) {
            if(!$approvers->where('r_code', $dateOrigin->first()->r_code)->first()) {

                $this->model->rtd_analyze()->createMany([[
                    'version' => $this->model->rtd_status['last_version'],
                    'r_code' => $dateOrigin->first()->r_code,
                    'position' => $this->model->rtd_status['collect']->max('position') + 1,
                    'is_holiday' => 0,
                    'mark' => 1
                ]]);
            }
        }
        
        if($this->model->rtd_status['status']['code'] == 3) {
            $users_approv = $this->model->rtd_status['status']['validation'];
            foreach ($users_approv as $approv) {
                $this->sendEmailAnalyze($this->model, $approv->users, $this->request);
            }
        }   
        elseif($this->model->rtd_status['status']['code'] == 2) {

            $status = $this->model->refresh();
            if($status->rtd_status['status']['code'] == 3) {
                $users_approv = $status->rtd_status['status']['validation'];
                foreach ($users_approv as $approv) {
                    $this->sendEmailAnalyze($this->model, $approv->users, $this->request);
                }
            } else {
                $this->model->is_approv = 1;
                $this->model->has_analyze = 0;
                $this->model->save();
                $this->sendEmailApproved($this->model, $this->request);
            }
        }    
        return 'solicitação aprovada com sucesso';
    }    

    public function reprovAnalyze() {

        $this->model->is_reprov = 1;
        $this->model->has_analyze = 0;
        $this->model->save();

        $this->sendEmailReproved($this->model, $this->request);
        return 'Solicitação reprovada com sucesso';
    }

    public function suspendedAnalyze() {

        $this->model->has_suspended = 1;
        $this->model->save();

        $this->sendEmailSuspended($this->model, $this->request);
        return 'Empréstimo suspenso com sucesso';
    }

    public function revertAnalyze() {

        $this->model->has_suspended = 0;
        $this->model->save();

        $users_approv = $this->model->rtd_analyze->where('position', $this->request->position);
        foreach ($users_approv as $approv) {
            $this->sendEmailRevert($this->model, $approv->users, $this->request);
        }
        return 'Solicitação revertida com sucesso';
    }

    public function approvNowAnalyze() {}
}    