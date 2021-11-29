<?php


namespace App\Services\Departaments\Commercial;
use App\Services\Departaments\Interfaces\Analyze;
use Illuminate\Http\Request;

class RequestBudget implements Analyze
{
    use CommercialTrait;

    public $request;
    public $model;

    public function __construct($model, Request $request)
    {
        $this->request = $request;
        $this->model = $model;
    }

    public function startAnalyze(): array {

        $arr_approv = [];
        $approvers = $this->model->rtd_approvers();

        if (!$approvers->count())
            throw new \Exception('Não é possível iniciar aprovação dessa solicitação, pois não tem aprovadores configurados.');

        $arr_response = $this->bossToBoss(
            $arr_approv,
            $this->model->salesman->immediate_boss,
            $this->model->rtd_status['last_version'] + 1
        );

        $arr_approv = $arr_response['arr_approv'];
        $last_pos = $arr_response['last_position'];

        foreach ($approvers as $key) {
            array_push($arr_approv,
                [
                    'version' => $this->model->rtd_status['last_version'] + 1,
                    'r_code' => $key->r_code,
                    'position' => $key->position + $last_pos
                ]
            );
        }
		
		foreach($this->model->salesman->immediate_boss as $boss) {
			$this->sendEmailAnalyze($this->model, $boss);
		}

        return $arr_approv;
    }

    public function approvAnalyze() {

		$this->model->is_approv = 1;
		$this->model->has_analyze = 0;
		$this->model->save();
		
		$this->sendEmailApproved($this->model, $this->request);
		
		// TRANSFORMAR AS NF PARA PAGO.
		
        return 'Solicitação aprovada com sucesso';
    }

    public function reprovAnalyze() {

		$this->model->is_reprov = 1;
        $this->model->has_analyze = 0;
        $this->model->save();

        $this->sendEmailRepproved($this->model, $this->request);
		
        return 'Solicitação reprovada com sucesso';
    }

    public function suspendedAnalyze() {

		$this->sendEmailSuspended($this->model, $this->request);
        return 'Solicitação suspensa com sucesso';
    }
	
	public function approvNowAnalyze() {

        return 'Solicitação aprovada com sucesso';
    }

    public function RevertAnalyze() {

		$users_approv = $this->model->rtd_analyze->where('position', $this->request->position);
        foreach ($users_approv as $key) {
			$this->sendEmailAnalyze($this->model, $key->users);
        }
		
        return 'Solicitação revertida com sucesso';
    }
}
