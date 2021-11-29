<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FinancyAlertDebtor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $lending_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lending_id)
    {
        $this->lending_id = $lending_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $lending = \App\Model\FinancyLending::find($this->lending_id);
        if ($lending) {

            $totalPago = $lending->getTotalPago(1);
            $totalAnalise = $lending->getTotalAnalise();
            $totalEmprestimo = $lending->getTotalEmprestimo(1);
            $data_pagamento = $lending->getDataPagamentoEmprestimo();
            
            $saldo_pagar = $totalEmprestimo - ($totalPago+$totalAnalise);

            //caso o tenha valor em analise ou saldo zerado, enviar Job para verificar novamente apos 5 dias
            if ( ($saldo_pagar==0 && $totalAnalise>0) || $totalAnalise>0 ) {
                // $this->dispatch($this->lending_id)->delay(now()->addSeconds(15));
                $this->dispatch($this->lending_id)->delay(now()->addDays(5));
                return;
            }

            //existe saldo a pagar, notifica o usuario, e enviar um job para realizar nova checagem apos 15 dias
            if ($saldo_pagar>0) {

                $user = $lending->user;
                
                $email_gestor_usuario = [];
                \App::setLocale("pt-br");
                $total_dias = $data_pagamento->diffForHumans();
                \App::setLocale($user->lang);

                $text_description = nl2br("Olá! Identificamos que você possui um saldo a pagar no valor de ".formatMoney(abs($saldo_pagar))." referente ao empréstimo(".$lending->code.")");

                $lending->count_notify=$lending->count_notify+1;
                $lending->save();

                if($lending->count_notify>0){
                    $gestor_usuario = \App\Model\UserImmediate::where('user_r_code',$lending->r_code)->get('immediate_r_code')->pluck('immediate_r_code');
                    $email_gestor_usuario = \App\Model\Users::isActive()->whereIn('r_code',$gestor_usuario)->get('email')->pluck('email');

                    $text_description .= " aberto ".$total_dias;
                    if($lending->count_notify==1){
                        $text_description .= nl2br("\n\n Você precisa regularizar seus debtos em até 15 dias, conforme as políticas do Financeiro
                        \n\n\n\n Desconsidere esta mensagem caso sua pendência tenha sido regularizada");
                    }
                }

                $pattern = array(
                    'title' => 'AVISO DE PRESTAÇÃO DE CONTAS',
                    'description' => $text_description,
                    'template' => 'misc.Default',
                    'copys'=>$email_gestor_usuario,
                    'subject' => 'Prestação de Contas Pendente (#'.$lending->code.')',
                );
                
                NotifyUser('Prestação de Contas Pendente (#'.$lending->code.')', $user->r_code, 'fa-exclamation', 'text-info', $text_description, env('URL') .'/financy/accountability/my');
                SendMailJob::dispatch($pattern, $user->email);

                // $this->dispatch($this->lending_id)->delay(now()->addSeconds(15));
                $this->dispatch($this->lending_id)->delay(now()->addDays(15));
                return;
            }


        }   
        
        return;
    }
}
