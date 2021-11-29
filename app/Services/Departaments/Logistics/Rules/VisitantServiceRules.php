<?php

namespace App\Services\Departaments\Logistics\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Trait VisitantServiceRules
{
    public function rulesVerifyRegisterAnalyze() {

        if($this->model->request_user->immediates->count() == 0)
            throw new \Exception("Solicitante não tem chefe imediato cadastrado");
    }
}