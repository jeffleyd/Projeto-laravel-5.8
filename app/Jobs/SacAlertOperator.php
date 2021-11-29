<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SacAlertOperator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $protocol_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($protocol_id)
    {
        $this->protocol_id = $protocol_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $protocol = \App\Model\SacProtocol::find($this->protocol_id);
        if ($protocol) {
            if ($protocol->authorized_id == null) {

                $user = \App\Model\Users::where('r_code', $protocol->r_code)->first();
                
                $pattern = array(
                    'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                    'description' => nl2br("Olá! Infelizmente nenhuma autorizada aceitou a solicitação no prazo de 48 horas do protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". env('URL') ."/sac/warranty/interactive/". $protocol->id ."'>". env('URL') ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                    'template' => 'misc.Default',
                    'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                );
                
                NotifyUser('Protocolo: #'. $protocol->code, $protocol->r_code, 'fa-exclamation', 'text-info', 'Infelizmente nenhuma autorizada aceitou a solicitação no prazo de 48 horas do protocolo, clique aqui para visualizar.', env('URL') .'/sac/warranty/interactive/'. $protocol->id);
                SendMailJob::dispatch($pattern, $user->email);
            }
        }   
        
        return;
    }
}
