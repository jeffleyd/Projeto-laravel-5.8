<?php

namespace App\Services\Departaments\Administration\Trip\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\Users;

Trait TripPlanRules
{
    /**
     * Verifica se a data atual é menor que a data de origem menos 7 dias
     */
    public function rulesVerifyDateOrigin() {
        if(!(date('Y-m-d') < date('Y-m-d', strtotime($this->model->origin_date. ' - 7 day')))) {
            return Users::where('is_pres', 1)->get();
        }
        return null;
    }    

    /**
     * Caso a data atual seja maior que a data de origin lança uma exceção
     */
    public function rulesVerifyDateOriginPass() {
        if(date('Y-m-d') > date('Y-m-d', strtotime($this->model->origin_date))) {
            throw new \Exception('Você não pode enviar para aprovação, a data atual é maior que a data de origem');
        }
    }
}