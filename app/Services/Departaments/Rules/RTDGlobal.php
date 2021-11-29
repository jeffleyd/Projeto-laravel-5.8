<?php


namespace App\Services\Departaments\Rules;


use App\Model\Users;
use Illuminate\Support\Facades\Hash;

class RTDGlobal
{
    /**
     * Válida se a senha do colaborador está válida
     * @throws \Exception
     * @return void
     */
    protected function validPasswordUsers() {
        if (!$this->solicitation->request->has('password'))
            throw new \Exception("Você precisa passar sua senha");

        $user = Users::on('mysql')->where('r_code', $this->solicitation->request->session()->get('r_code'))->first();
        if (!$user)
            throw new \Exception("Não foi possível encontrar o usuário para validar sua senha.");

        if ($user) {
            if ($user->is_active) {
                if (!Hash::check($this->solicitation->request->password, $user->password)) {

                    $user->retry -= 1;
                    if (!$user->retry)
                        $user->is_active = 0;

                    $user->save();
                    throw new \Exception("Você informou sua senha incorreta, você será desativado se continuar errando.");
                }
            } else {
                throw new \Exception("Sua conta está desativada, fale com administração.");
            }
        }
    }

    /**
     * Verifica se a solicitação está aprovada, em análise ou suspensa
     * @throws \Exception
     * @return void
     */
    protected function rulesStatusStartAnalyze() {

        $arr = $this->solicitation->model->rtd_status['status'];
        if (in_array($arr['code'], [2, 3, 4], true)) {

            $status = [
                2 => 'Solicitação já está aprovada!',
                3 => 'Solicitação já está em análise!',
                4 => 'Solicitação está suspensa!'
            ];
            throw new \Exception($status[$arr['code']]);
        }
    }

    /**
     * Válida se usuário está no processo de aprovação e se o mesmo pode retornar estapas
     * @throws \Exception
     * @return void
     */
    protected function rulesVerifyValidationAnalyze() {

        $user = $this->solicitation->model->rtd_status['status']['validation']
                     ->where('r_code', $this->solicitation->request->session()->get('r_code'));

        if(!$user->count()) {
            throw new \Exception("Você não está no processo de análise");
        }

        if($this->solicitation->request->rtd_analyze_type == 4) {
            if($user->first()->position == 1) {
                throw new \Exception("Você não pode voltar etapa, estando na etapa 1");
            }
        }

        return $user->first();
    }

    /**
     * Verifica se a solicitação está em análise
     * @throws \Exception
     * @return void
     */
    protected function rulesVerifyHasAnalyze() {

        if ($this->solicitation->model->rtd_status['status']['code'] == 0) {
            throw new \Exception('Solicitação precisa ser enviada para análise');
        }
    }    
	
	/**
     * Verifica se usuário tem imediato
     * @throws \Exception
     * @return void
     */
    protected function rulesVerifyHasImmediate() {

        $user = Users::on('mysql')->with('immediates')->where('r_code', $this->solicitation->request->session()->get('r_code'))->first();
        if (count($user->immediates) == 0) {
            throw new \Exception('Para aprovação você precisa ao menos ter um chefe cadastrado');
        }
    }  
}
