<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Jobs\SendMailAttachJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Hash;
use App;
use Illuminate\Support\Facades\Validator;
use Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

use App\Model\SacProtocol;
use App\Model\SacModelProtocol;
use App\Model\SacPartProtocol;
use App\Model\SacAuthorized;
use App\Model\SacClient;
use App\Model\SacMsgProtocol;
use App\Model\SacOsProtocol;
use App\Model\Users;
use App\Model\BlogPost;
use App\Model\EmailsProblem;
use App\Model\UserNotificationExternal;

class ApiController extends Controller
{
	
	public function awsResponseSNS(Request $request) {
		$data = json_decode($request->getContent(), true);
		if (is_array($data)) {
			foreach($data['bounce']['bouncedRecipients'] as $key) {
				$model = new EmailsProblem;
				$model->type = 'Bounche: '. $data['bounce']['bounceType'];
				$model->email = $key['emailAddress'];
				$model->json = $request->getContent();
				$model->save();
			}
			
			$namespace = '';
            $code = '';
            $subject = '';
            foreach($data['mail']['headers'] as $hearder) {
                if ($hearder['name'] == 'h_namespace')
                    $namespace = $hearder['value'];
                if ($hearder['name'] == 'h_code')
                    $code = $hearder['value'];
                if ($hearder['name'] == 'Subject')
                    $subject = $hearder['value'];
            }

            if ($namespace and $code) {
                $class = $namespace;
                if (!class_exists($class))
                    Log::info('Módulo informado não existe. Namespace: '.$namespace);

                $module = $class::where('code', $code)->first();
                if (!$module)
                    Log::info('Módulo não foi encontrado. Código: '.$code.' Namespace: '.$namespace);

                $r_code = $module->r_code ? $module->r_code : $module->request_r_code;
                if ($r_code) {
                    NotifyUser($subject.' (RETORNOU)',
                        $r_code,
                        'fa-times',
                        'text-danger',
                        'O email: ('.implode(', ', $data['mail']['destination']).') está errado ou está inacessível. Por favor verifique o email dessa solcitação.',
                        '#');
                }
            }
		}
	}
	public function endCall(Request $request) {
        $data = json_decode($request->getContent(), true);
        $protocol = \App\Model\SacProtocol::where('api_call_id', $data['id'])->first();
        if ($protocol) {
            if ($data['origem']['status'] != 'atendida') {

                $message = new \App\Model\SacMsgProtocol;
                $message->message = nl2br("Cliente não atendeu a ligação, status da ligação foi: ".$data['origem']['status']." \n". date('d/m/Y H:i'));
                $message->is_system = 1;
                $message->sac_protocol_id = $protocol->id;
                $message->save();

                $settings = App\Model\Settings::where('command', 'sac_attemps_call_completed')->first();
                
                if ($protocol->attemps_call < $settings->value) {
					App\Jobs\SacTryCompletedProtocol::dispatch($protocol)->delay(now()->addDays(1));
				} else {
					$protocol->attemps_call_has_limit = 1;
					$protocol->save();
				}
					

            } else {

                $message = new \App\Model\SacMsgProtocol;
                $message->message = nl2br("Cliente atendeu a ligação:  \n". date('d/m/Y H:i'));
                $message->is_system = 1;
                $message->sac_protocol_id = $protocol->id;
                $message->save();
            }
        }
    }

    public function sacEndProtocol(Request $request) {

        $payload = json_decode(file_get_contents('php://input'));
        $id = $payload->id != null ? $payload->id : $request->id;
        $protocol = SacProtocol::where('api_call_id', $id)->first();

        if ($protocol) {
            if ($request->answer == 1) {
                $protocol->pending_completed = 0;
                $protocol->is_completed = 1;
                $protocol->save();

                $message = new SacMsgProtocol;
                $message->message = nl2br("Cliente finalizou o atendimento por telefone \n". date('d/m/Y H:i'));
                $message->is_system = 1;
                $message->sac_protocol_id = $protocol->id;
                $message->save();

                if ($protocol->authorized_id) {

                    $authorized = SacAuthorized::find($protocol->authorized_id);
    
                    if ($authorized->live < 10) {
                        $authorized->live = $authorized->live + 1;
                        $authorized->save();
                    }
                }

                if ($protocol->r_code) {

                    $user = Users::where('r_code', $protocol->r_code)->first();
                    
                    $pattern = array(
                        'title' => 'PROTOCOLO FINALIZADO',
                        'description' => nl2br("Olá! foi realizado a finalizadção por telefone do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                        'template' => 'misc.Default',
                        'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                    );
                    
                    NotifyUser('Protocolo: #'. $protocol->code, $user->r_code, 'fa-exclamation', 'text-info', 'Foi realizado a finalizadção por telefone do seu protocolo, clique aqui para ver mais detalhes.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                    SendMailJob::dispatch($pattern, $user->email);
                }

            } else if ($request->answer == 2) {
                $protocol->is_denied = 1;
                $protocol->pending_completed = 0;
                $protocol->save();

                $message = new SacMsgProtocol;
                $message->message = nl2br("\nCliente recusou a finalização \n do atendimento por telefone \n\n Data: ".  date('d/m/Y H:i'));
                $message->is_system = 1;
                $message->sac_protocol_id = $protocol->id;
                $message->save();

                if ($protocol->r_code) {

                    $user = Users::where('r_code', $protocol->r_code)->first();
                    
                    $pattern = array(
                        'title' => 'FINALIZAÇÃO DO PROTOCOLO RECUSADO',
                        'description' => nl2br("Olá! seu protocolo: (". $protocol->code .") teve a finalização recusada pelo cliente, entre em contato por telefone para saber o motivo. Veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                        'template' => 'misc.Default',
                        'subject' => 'Protocolo: '. $protocol->code .' finalização recusado!',
                    );
                    
                    NotifyUser('Protocolo: #'. $protocol->code, $user->r_code, 'fa-exclamation', 'text-info', 'Foi recusado a finalizadção seu protocolo, clique aqui para ver mais detalhes.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                    SendMailJob::dispatch($pattern, $user->email);
                }
            } else {
                $message = new SacMsgProtocol;
                $message->message = nl2br("\nCliente não conseguiu finalizar \n o atendimento por telefone \n\n Data: ".  date('d/m/Y H:i'));
                $message->is_system = 1;
                $message->sac_protocol_id = $protocol->id;
                $message->save();

                if ($protocol->r_code) {

                    $user = Users::where('r_code', $protocol->r_code)->first();
                    
                    $pattern = array(
                        'title' => 'FINALIZAÇÃO DO PROTOCOLO RECUSADO',
                        'description' => nl2br("Olá! seu protocolo: (". $protocol->code .") cliente teve dificuldade na finalização, entre em contato por telefone para saber o motivo. Veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                        'template' => 'misc.Default',
                        'subject' => 'Protocolo: '. $protocol->code .' finalização recusado!',
                    );
                    
                    NotifyUser('Protocolo: #'. $protocol->code, $user->r_code, 'fa-exclamation', 'text-info', 'Cliente teve dificuldade na finalização do seu protocolo, clique aqui para ver mais detalhes.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                    SendMailJob::dispatch($pattern, $user->email);
                }
            }
        } else {

            Log::info('Verificar esse api_call_id : '. $id .' não conseguiu encontrar o protocolo.');
        }
        
    }

    public function sacRateProtocol(Request $request) {

        $payload = json_decode(file_get_contents('php://input'));
        $id = $payload->id != null ? $payload->id : $request->id;

        $protocol = SacProtocol::where('api_call_id', $id)->first();

        if ($protocol->is_completed == 1) {

            $rating = number_format($request->rate, 2);

            $authorized = SacAuthorized::find($protocol->authorized_id);
            
            if ($authorized) {
                $new_count = $authorized->rate_count + 1;
                if ($authorized->rate_count == 0) {
                    $authorized->rate = $rating;
                } else {
                    $new_rate = (($authorized->rate * $authorized->rate_count) + $rating) / $new_count;
                    $authorized->rate = $new_rate;
                }
                $authorized->rate_count = $authorized->rate_count + 1;
                $authorized->save();

            }

            $protocol->rate = $rating;
            $protocol->save();

        } else {
            Log::info('Verificar esse id desse protocolo: '. $protocol->id .' está tentando votar sem estar concluido. Pulando processo da URA.');
        }

    }

    public function jsonURADinamic(Request $request) {
        
        $consulta = $request->consulta;
        $menu = $request->menu;
        $tries = 0;
        if($request->has('tries') ){
            $tries = $request->tries;
        }

        
        $dados = array();
        
        $php_input = file_get_contents("php://input");
        $dados_recebidos = json_decode($php_input);
        
        // $cnpj = '00.138.409/0001-79';
        // $cnpj = str_replace(['-','.','/'], ['','',''], $cnpj);
        // $num_os = '2020706598';

        date_default_timezone_set('America/Manaus');

        if ($consulta == 'consultaHorarioAtendimento') {
            

            if( date('w') > 0 && date('w') < 6 && date('H:i') >= date('07:00') && date('H:i') <= date('17:30') ){
                $dados_tts['acao'] = 'tts';
                $dados_tts['acao_dados']['mensagem'] = 'Bem vindo à Gree do Brasil';
                $dados_tts['timeout'] = '1';
            
                // Aqui criamos o retorno para o próximo passo
                $dados_dinamico['acao'] = 'dinamico';
                $dados_dinamico['acao_dados']['url'] = url('/').'/api/v1/sac/ura?menu=menu_principal';
            
                $dados = array($dados_tts, $dados_dinamico);
            }else{

                $dados_tts['acao'] = 'tts';
                $dados_tts['acao_dados']['mensagem'] = 'não foi possível continuar esta ligação, pois nosso horário de atendimento é de segunda à sexta de sete as dezessete e trinta (horário de Manaus). A Gree agradece sua ligação!';
                $dados_tts['timeout'] = '1';
            
                $dados = array($dados_tts);
            }
            
        }else if ($consulta == 'consultaCPF') {

            $dados_tts['acao'] = 'tts';
            $dados_tts['acao_dados']['mensagem'] = 'Por favor, digite seu CPF';
            // Vou coletar 11 digitos (CPF) em um tempo de 20 segundos
            $dados_tts['coletar_dtmf'] = '11';
            $dados_tts['timeout'] = '20';
        
            // Aqui criamos o retorno para o próximo passo
            $dados_dinamico['acao'] = 'dinamico';
            $dados_dinamico['acao_dados']['url'] = url('/').'/api/v1/sac/ura?menu='.$menu;
        
            $dados = array($dados_tts, $dados_dinamico);
        }else if ($consulta == 'consultaAutorizada') {

            $dados_tts['acao'] = 'tts';
            $dados_tts['acao_dados']['mensagem'] = 'Por favor, digite somente os numeros do CNPJ da Autorizada';
            // Vou coletar 11 digitos (CPF) em um tempo de 20 segundos
            $dados_tts['coletar_dtmf'] = '14';
            $dados_tts['timeout'] = '20';
        
            // Aqui criamos o retorno para o próximo passo
            $dados_dinamico['acao'] = 'dinamico';
            $dados_dinamico['acao_dados']['url'] = url('/').'/api/v1/sac/ura?consulta=getAutorizada&tries='.$tries;
        
            $dados = array($dados_tts, $dados_dinamico);
            
        }else if ($consulta == 'getAutorizada') {

            $cnpj_autorizada = $dados_recebidos->ultimo_dtmf;
            // $cnpj_autorizada = $cnpj;
            
            $findAutorizada = SacAuthorized::
            whereRaw("TRIM(REPLACE(REPLACE(REPLACE(identity, '.', ''), '/', ''), '-', '')  )  = '".$cnpj_autorizada."'" );

            $dados_tts = [];
            
            // echo $findAutorizada->toSql();
            $findAutorizada = $findAutorizada->first();

            if($findAutorizada){

                $dados_dinamico['acao'] = 'dinamico';
                $dados_dinamico['acao_dados']['url'] = url('/').'/api/v1/sac/ura?autorizada='.$findAutorizada->id.'&consulta=consultaProtocoloOS';

            }else{
                $tries++;
                $dados_tts['acao'] = 'tts';
                $dados_tts['acao_dados']['mensagem'] = 'não conseguimos localizar sua autorizada. Tente novamente';

                // Aqui criamos um loop para retornar ao menu de busca do numero da Autorizada
                $dados_dinamico['acao'] = 'dinamico';
                $dados_dinamico['acao_dados']['url'] = url('/').'/api/v1/sac/ura?consulta=consultaAutorizada&tries='.$tries;

                if($tries==3){ //em caso nao encontre em 3 tentativas, direcionar para o SAC
                    $dados_tts['acao_dados']['mensagem'] = 'não conseguimos localizar sua Autorizada. estamos transferindo sua chamada para o SAC';
        
                    $dados_dinamico = [];
                    $dados_dinamico['acao'] = 'fila';
                    $dados_dinamico['acao_dados']['fila_id'] = 'id_fila_sac';
        
                }

            }
            
            if($dados_tts){
                array_push($dados, $dados_tts);
            }
            array_push($dados, $dados_dinamico);
            
        }else if ($consulta == 'getProtocolo') {

            $protocolo_num_os = $dados_recebidos->ultimo_dtmf;
            // $protocolo_num_os = $num_os;
            $id_autorizada = $request->autorizada;

            $dados_dinamico = [];
            $dados_tts['acao'] = 'tts';
            
            $findOS = DB::table('sac_os_protocol')
                ->leftJoin('sac_protocol','sac_os_protocol.sac_protocol_id','=','sac_protocol.id')
                
                ->select('sac_os_protocol.*', 'sac_protocol.code as sac_protocol_code' ,'sac_protocol.is_completed')
                
                ->where('sac_os_protocol.authorized_id',$id_autorizada)
                ->where('sac_os_protocol.is_cancelled', 0)
                ->where(function($q) use($protocolo_num_os) {
                    $q->where('sac_os_protocol.code', "W".$protocolo_num_os)
                    ->orWhere('sac_protocol.code', "G".$protocolo_num_os);
                });
                
            $findOS = $findOS->first();
            
            if($findOS){

                if( $findOS->is_completed == 1 ){

                    if( $findOS->is_paid == 1 && $findOS->is_payment_request == 1 ){
                        $dados_tts['acao_dados']['mensagem'] = 'O pagamento de sua OS esta sendo processado. Em até 10 dias úteis será realizado o pagamento'; 
                    }else if( $findOS->is_paid == 1 && $findOS->is_payment_request == 0 ){
                        $dados_tts['acao_dados']['mensagem'] = 'Sua OS ja foi finalizada em nosso sistema. Em caso de duvidas entre em contato com o SAC';
                    }else if( empty($findOS->os_signature) ){
                        $dados_tts['acao_dados']['mensagem'] = 'sua OS esta pendente em nosso sistema, não foi realizado o enviou da OS assinada ou Relatorio Tecnico';
                    }else if( $findOS->payment_nf_request == 1 ){
                        $dados_tts['acao_dados']['mensagem'] = 'a Gree ja enviou para seu email uma solicitação de nota fiscal referente a OS realizada, caso ja tenha nos enviado a nota fiscal, aguarde em até 10 dias para pagamento da mesma.';
                    }else{
                        $dados_tts['acao_dados']['mensagem'] = 'sua OS esta em analise para pagamento';
                    }

                }else{
                    $dados_tts['acao_dados']['mensagem'] = 'esta OS esta em andamento';
                }
                

            }else{


                $findOSCancelled = DB::table('sac_os_protocol')
                ->leftJoin('sac_protocol','sac_os_protocol.sac_protocol_id','=','sac_protocol.id')
                
                ->select('sac_os_protocol.*', 'sac_protocol.code as sac_protocol_code' ,'sac_protocol.is_completed')
                
                ->where('sac_os_protocol.authorized_id',$id_autorizada)
                ->where('sac_os_protocol.is_cancelled', 1)
                ->where(function($q) use($protocolo_num_os) {
                    $q->where('sac_os_protocol.code', "W".$protocolo_num_os)
                    ->orWhere('sac_protocol.code', "G".$protocolo_num_os);
                });
                $findOSCancelled = $findOSCancelled->first();

                if($findOSCancelled){
                    $dados_tts['acao_dados']['mensagem'] = 'Esta OS foi cancelada. Em caso de duvidas entre em contato com o SAC';
                }else{
                    $tries++; //contador de tentativas
                    $dados_tts['acao_dados']['mensagem'] = 'não conseguimos localizar sua OS. Tente novamente';
                    
                    $dados_dinamico['acao'] = 'dinamico';
                    $dados_dinamico['acao_dados']['url'] = url('/').'/api/v1/sac/ura?autorizada='.$id_autorizada.'&consulta=consultaProtocoloOS&tries='.$tries;

                    if($tries==3){ //em caso nao encontre em 3 tentativas, direcionar para o SAC
                        $dados_tts['acao_dados']['mensagem'] = 'não conseguimos localizar sua OS. estamos transferindo sua chamada para o SAC';
            
                        $dados_dinamico = [];
                        $dados_dinamico['acao'] = 'fila';
                        $dados_dinamico['acao_dados']['fila_id'] = 'id_fila_sac';
                    }
                    
                }

            }
            
            $dados = array($dados_tts);
            if($dados_dinamico){
                array_push($dados, $dados_dinamico);
            }
            
        }else if ($consulta == 'consultaProtocoloOS') {
            $id_autorizada = $request->autorizada;

            $dados_tts['acao'] = 'tts';
            $dados_tts['acao_dados']['mensagem'] = 'Por favor, digite somente os numeros do Protocolo ou o numero da OS';
            // Vou coletar 11 digitos (CPF) em um tempo de 20 segundos
            $dados_tts['coletar_dtmf'] = '20';
            $dados_tts['timeout'] = '20';
        
            // Aqui criamos o retorno para o próximo passo
            $dados_dinamico['acao'] = 'dinamico';
            $dados_dinamico['acao_dados']['url'] = url('/').'/api/v1/sac/ura?autorizada='.$id_autorizada.'&consulta=getProtocolo&tries='.$tries;
        
            $dados = array($dados_tts, $dados_dinamico);

        }else if ($menu == 'menu_principal') {

            // Fala um texto de transferência
            $dados_tts['acao'] = 'tts';
            $dados_tts['acao_dados']['mensagem'] = 'Para Atendimento em Garantia, tecle 1, para falar com Assistencia Técnica, tecle 2, Para Reclamação, tecle 3, Para falar com um atendente, tecle 9';

            // Adiciona duas opções de menus que vão para suas respectivas filas
            // Ao escolher opção 1, transfere a ligação para o SAC (Garantia)
            $dados_fila_garantia['opcao'] = '1';
            $dados_fila_garantia['acao'] = 'dinamico';
            $dados_fila_garantia['acao_dados']['url'] = url('/').'/api/v1/sac/ura?consulta=consultaCPF&menu=menu_garantia';

            // Ao escolher opção 1, transfere a ligação para a Assistencia Tecnica
            $dados_fila_ass_tecnica['opcao'] = '2';
            $dados_fila_ass_tecnica['acao'] = 'dinamico';
            $dados_fila_ass_tecnica['acao_dados']['url'] = url('/').'/api/v1/sac/ura?menu=menu_assistencia_tecnica';
            
            // Ao escolher opção 1, transfere a ligação para o SAC (Reclamação)
            $dados_fila_reclamacao['opcao'] = '3';
            $dados_fila_reclamacao['acao'] = 'dinamico';
            $dados_fila_reclamacao['acao_dados']['url'] = url('/').'/api/v1/sac/ura?consulta=consultaCPF&menu=menu_reclamacao';
            
            // Ao escolher opção 1, transfere a ligação para o SAC (Atentende)
            $dados_fila_atendimento['opcao'] = '9';
            $dados_fila_atendimento['acao'] = 'dinamico';
            $dados_fila_atendimento['acao_dados']['url'] = url('/').'/api/v1/sac/ura?menu=menu_atendente';

            $dados = array($dados_tts, $dados_fila_garantia, $dados_fila_ass_tecnica, $dados_fila_reclamacao, $dados_fila_atendimento );

        }else if ($menu == 'menu_garantia') {

            $cpf = $dados_recebidos->ultimo_dtmf;
            // $cpf = "12345678900";

            $dados_tts['acao'] = 'tts';
            $dados_tts['acao_dados']['mensagem'] = 'estamos transferindo sua chamada para o SAC';

            // Transfere a chamada para uma fila de ramais
            $dados_fila['acao'] = 'fila';
            $dados_fila['acao_dados']['fila_id'] = 5; //ID da Fila do SAC (Atendimento em Garantia)

            $dados = array($dados_tts, $dados_fila);

        }else if ($menu == 'menu_assistencia_tecnica') {

            $dados_tts['acao'] = 'tts';
            $dados_tts['acao_dados']['mensagem'] = 'Para Compra de Peça, tecle 1, para saber sobre pagamento da OS, tecle 2, Para falar com um atendente, tecle 9';

            // Adiciona duas opções de menus que vão para suas respectivas filas
            // Ao escolher opção 1, transfere a ligação para o SAC (Garantia)
            $dados_fila_compra_peca['opcao'] = '1';
            $dados_fila_compra_peca['acao'] = 'fila';
            $dados_fila_compra_peca['acao_dados']['fila_id'] = 7; //ID da Fila de Compra de Peça

            // Ao escolher opção 1, transfere a ligação para a Assistencia Tecnica
            
            //criar submenu auxliar
            $dados_fila_pagamento_os['opcao'] = '2';
            $dados_fila_pagamento_os['acao'] = 'dinamico';
            $dados_fila_pagamento_os['acao_dados']['url'] = url('/').'/api/v1/sac/ura?consulta=consultaAutorizada&tries='.$tries;

            // Ao escolher opção 1, transfere a ligação para o SAC (Atentende)
            $dados_fila_atendimento['opcao'] = '9';
            $dados_fila_atendimento['acao'] = 'fila';
            $dados_fila_atendimento['acao_dados']['fila_id'] = 7; //ID da Fila de Atendimento da Assistencia Tecnica

            $dados = array($dados_tts, $dados_fila_compra_peca, $dados_fila_pagamento_os, $dados_fila_atendimento );

        }else if ($menu == 'menu_reclamacao') {

            $cpf = $dados_recebidos->ultimo_dtmf;
            // $cpf = "12345678900";

            $dados_tts['acao'] = 'tts';
            $dados_tts['acao_dados']['mensagem'] = 'estamos transferindo sua chamada para o SAC';

            // Transfere a chamada para uma fila de ramais
            $dados_fila['acao'] = 'fila';
            $dados_fila['acao_dados']['fila_id'] = 5; //ID da Fila do SAC (Atendimento em Garantia)

            $dados = array($dados_tts, $dados_fila);

        }else if ($menu == 'menu_atendente') {

            $dados_tts['acao'] = 'tts';
            $dados_tts['acao_dados']['mensagem'] = 'estamos transferindo sua chamada para o SAC';

            // Transfere a chamada para uma fila de ramais
            $dados_fila['acao'] = 'fila';
            $dados_fila['acao_dados']['fila_id'] = 5; //ID da Fila do SAC (Atendimento em Garantia)

            $dados = array($dados_tts, $dados_fila);
        }
        
        
        // Monto a array de saída
        $menu_resposta = array(
            'nome' => 'Menu Principal',
            'dados' => $dados
        );
        
        // Respondo o JSON para a Ura
        header('Content-Type: application/json');
        die (json_encode($menu_resposta));

    }
	
	public function newsPosts(Request $request) {

        $post = BlogPost::where('is_publish', 1)->orderBy('id', 'DESC');

        if($request->search != '') {
            $post->where('title_pt', 'like', '%'.$request->search.'%');
        }

        if ($request->sector == 1) {
            $post->where('category_id', 7)
            ->orWhere(function ($query) {
                $query->where('is_publish', 1)
                        ->where('category_id', 6);
            });

        } else if ($request->sector == 2) {
            $post->where('category_id', 100);

        } else if ($request->sector == 3) {
            $post->where('category_id', 2);

        } else if ($request->sector == 4) {
            $post->where('category_id', 1);

        } else if ($request->sector == 5) {
            $post->where('category_id', 3);

        } 
        else if ($request->sector == 6) {
            $post->where('category_id', 99);
        } 

        return response()->json([
            'success' => true,
            'posts' => $post->paginate(3),
        ], 200); 
    }

    public function newsPostSingle(Request $request) {

        $post = BlogPost::with('blog_post_attach')->find($request->id);

        return response()->json([
            'success' => true,
            'post' => $post,
        ], 200);
    }

    public function newsNotificationToken(Request $request) {

        try {
            $user = UserNotificationExternal::where('r_code', $request->code_reg)->first();
            if ($user) {
                $user->token = $request->token;
                $user->save();
            } else {
                $new_user = new UserNotificationExternal;
                $new_user->r_code = $request->code_reg;
                $new_user->token = $request->token;
                $new_user->save();
            }

            return response()->json([
                'success' => true,
            ], 200); 

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

}