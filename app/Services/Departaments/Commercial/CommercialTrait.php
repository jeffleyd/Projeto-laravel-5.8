<?php


namespace App\Services\Departaments\Commercial;


Trait CommercialTrait
{

    public function bossToBoss($arr, $immediates, $version, $start_position = 1) {

        $arr = $this->recursiveFuncImdts($arr, $immediates, $start_position, $version);
        $pos = count($arr) ? $arr[count($arr)-1]['position'] : 0;

        return [
            'arr_approv' => $arr,
            'last_position' => $pos
        ];
    }

    private function recursiveFuncImdts($arr, $immediates, $pos, $version) {

        if ($pos != 1)
            if ($immediates->where('is_direction', '2')->count())
                return $arr;

        foreach ($immediates as $index => $immediate) {
            array_push($arr, [
                'version' => $version,
                'r_code' => $immediate->r_code,
                'position' => $pos
            ]);

            if ($immediates->count() == ($index+1)) {
                $get_bosses = $immediate->immediate_boss()->get();
                if ($get_bosses->count()) {
                    $pos++;
                    $arr = $this->recursiveFuncImdts($arr, $get_bosses, $pos, $version);
                }
            }
        }

        return $arr;
    }
	
	public function sendEmailAnalyze($model, $immediate)
    {
        $pattern = array(
            'title' => 'SOLICITAÇÃO DE VERBAS COMERCIAIS: EM ANÁLISE',
            'description' => nl2br(
                '<div style="text-align: justify">'.
                '<b>Colaborador:  </b>' . $model->salesman->full_name . '<br>' .
				'<b>Cliente:  </b>' . $model->client->company_name . '<br>' .
                '<b>Solicitação:  </b>' . $model->code . '<br>' .
                '<b>Tipo da solicitação:  </b>' . $model->type_budget_name . ' <br> ' .
                '<b>Tipo de pagamento:  </b>' . $model->type_payment_name .' <br> '.
                '<b>Motivo da Reserva:  </b>' . $model->reason->reason . ' <br> ' .
                '<b>Criado em:  </b>' . date('d/m/Y H:i', strtotime($key->created_at)) . ' <br> <br> '.
				'<b>Clique no link para aprovar:  </b><a href="' . env('APP_URL') . '/commercial/sales/budget/list/analyze">' . env('APP_URL') . '/commercial/sales/budget/list/analyze</a>'),
            'template' => 'misc.Default',
            'subject' => 'Solicitação de verbas comerciais em análise',
        );

        NotifyUser(
            'VERBAS COMERCIAIS',
            $immediate->r_code,
            'fa-exclamation',
            'text-info',
            'Solicitação de verbas em análise, precisa de sua aprovação.',
            env('APP_URL') . '/commercial/sales/budget/list/analyze'
        );

        SendMailJob::dispatch($pattern, $immediate->email);
    }

    public function sendEmailApproved($model, $request)
    {
		
		$pattern = array(
            'title' => 'SOLICITAÇÃO DE VERBAS COMERCIAIS: FOI APROVADO',
            'description' => nl2br(
                '<div style="text-align: justify">'.
                '<b>Colaborador:  </b>' . $model->salesman->full_name . '<br>' .
				'<b>Cliente:  </b>' . $model->client->company_name . '<br>' .
                '<b>Solicitação:  </b>' . $model->code . '<br>' .
                '<b>Tipo da solicitação:  </b>' . $model->type_budget_name . ' <br> ' .
                '<b>Tipo de pagamento:  </b>' . $model->type_payment_name .' <br> '.
                '<b>Motivo da Reserva:  </b>' . $model->reason->reason . ' <br> ' .
                '<b>Criado em:  </b>' . date('d/m/Y H:i', strtotime($key->created_at)) . ' <br> <br> '.
				'<span style="background: #e3e3e3;padding: 14px;font-weight: bold;"> Observação da Análise: ' . $request->description. '</span> </div>'.
				'<b>Para mais informações clique no link:  </b><a href="' . env('APP_URL') . '/comercial/operacao/verba-comercial/todos">' . env('APP_URL') . '/comercial/operacao/verba-comercial/todos</a>'),
            'template' => 'misc.Default',
            'subject' => 'Solicitação de verbas comerciais foi aprovado',
        );

        SendMailJob::dispatch($pattern, $model->salesman->email);
    }
    
    public function sendEmailRepproved($model, $request)
    {
       
        $pattern = array(
            'title' => 'SOLICITAÇÃO DE VERBAS COMERCIAIS: FOI REPROVADO',
            'description' => nl2br(
                '<div style="text-align: justify">'.
                '<b>Colaborador:  </b>' . $model->salesman->full_name . '<br>' .
				'<b>Cliente:  </b>' . $model->client->company_name . '<br>' .
                '<b>Solicitação:  </b>' . $model->code . '<br>' .
                '<b>Tipo da solicitação:  </b>' . $model->type_budget_name . ' <br> ' .
                '<b>Tipo de pagamento:  </b>' . $model->type_payment_name .' <br> '.
                '<b>Motivo da Reserva:  </b>' . $model->reason->reason . ' <br> ' .
                '<b>Criado em:  </b>' . date('d/m/Y H:i', strtotime($key->created_at)) . ' <br> <br> '.
				'<span style="background: #e3e3e3;padding: 14px;font-weight: bold;"> Observação da Análise: ' . $request->description. '</span> </div>'.
				'<b>Para mais informações clique no link:  </b><a href="' . env('APP_URL') . '/comercial/operacao/verba-comercial/todos">' . env('APP_URL') . '/comercial/operacao/verba-comercial/todos</a>'),
            'template' => 'misc.Default',
            'subject' => 'Solicitação de verbas comerciais foi reprovado',
        );

        SendMailJob::dispatch($pattern, $model->salesman->email);
        
    }

    public function sendEmailSuspended($model, $request)
    {
        $pattern = array(
            'title' => 'SOLICITAÇÃO DE VERBAS COMERCIAIS: FOI SUSPENDIDO',
            'description' => nl2br(
                '<div style="text-align: justify">'.
                '<b>Colaborador:  </b>' . $model->salesman->full_name . '<br>' .
				'<b>Cliente:  </b>' . $model->client->company_name . '<br>' .
                '<b>Solicitação:  </b>' . $model->code . '<br>' .
                '<b>Tipo da solicitação:  </b>' . $model->type_budget_name . ' <br> ' .
                '<b>Tipo de pagamento:  </b>' . $model->type_payment_name .' <br> '.
                '<b>Motivo da Reserva:  </b>' . $model->reason->reason . ' <br> ' .
                '<b>Criado em:  </b>' . date('d/m/Y H:i', strtotime($key->created_at)) . ' <br> <br> '.
				'<span style="background: #e3e3e3;padding: 14px;font-weight: bold;"> Observação da suspensão: ' . $request->description. '</span> </div>'.
				'<b>Para mais informações clique no link:  </b><a href="' . env('APP_URL') . '/comercial/operacao/verba-comercial/todos">' . env('APP_URL') . '/comercial/operacao/verba-comercial/todos</a>'),
            'template' => 'misc.Default',
            'subject' => 'Solicitação de verbas comerciais foi suspendido',
        );

        SendMailJob::dispatch($pattern, $model->salesman->email);
    }
}
