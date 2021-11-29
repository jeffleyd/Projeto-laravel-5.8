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
use App\Model\SacFaq;
use App\Model\Users;
use App\Model\Representation;
use App\Model\SacShopParts;
use App\Model\SacShop;
use App\Model\SacProblemCategory;

class SacClientController extends Controller
{

    public function login(Request $request) {


        return view('gree_sac_client.login');
    }

    public function representation(Request $request) {

        $rep = Representation::where('is_active', 1)->get();

        return view('gree_sac_client.representation', [
            'rep' => $rep,
        ]);
    }

    public function shopParts(Request $request) {

        $shop = SacShopParts::where('is_active', 1)->get();

        return view('gree_sac_client.shop_parts', [
            'shop' => $shop,
        ]);
    }

    public function shops(Request $request) {

        $shop = SacShop::where('is_active', 1)->get();

        return view('gree_sac_client.shops', [
            'shop' => $shop,
        ]);
    }

    public function faq(Request $request) {

        $faq_authorized = SacFaq::where('type', 1)->orderBy('id', 'DESC')->get();
        $faq_client = SacFaq::where('type', 2)->orderBy('id', 'DESC')->get();
        $sac_problem_category = SacProblemCategory::All();

        return view('gree_sac_client.faq', [
            'faq_authorized' => $faq_authorized,
            'faq_client' => $faq_client,
            'sac_problem_category' => $sac_problem_category,
        ]);
    }

    public function verifyLoginData(Request $request) {

        $user = SacClient::where('identity', $request->identity)->first();

        if ($user) {

            return response()->json([
                'success' => true,
                'has_user' => true,
                'info' => $user->password == null ? 'Crie uma senha de 4 digitos para continuar seu acesso.' : 'Digite sua senha de 4 digites para acessar.'
            ]);
        } else {

            return response()->json([
                'success' => true,
                'has_user' => false,
            ]);
        }

    }

    public function faq_email_do(Request $request) {

        if ($request->c_name and $request->c_identity and $request->c_phone and $request->c_email) {
            $email = "";
            $subject = "";
            if ($request->type == 4) {
                $subject = "Email do suporte para: Marketing";
                $email = 'filipe.gomes@gree-am.com.br';
            } else if ($request->type == 5) {
                $subject = "Email do suporte para: Comercial";
                $email = 'taiane.franca@gree-am.com.br';
            } else {

                $client = SacClient::where('identity', $request->c_identity)->first();

                if (!$client) {

                    $client = new SacClient;
                    $client->name = $request->c_name;
                    $client->type_people = 1;
                    $client->identity = $request->c_identity;
                    $client->phone = $request->c_phone;
                    $client->email = $request->c_email;
                    $client->save();

                }


                $has_protocol = SacProtocol::where('client_id', $client->id)
                    ->where('is_completed', 0)
                    ->where('is_cancelled', 0)
                    ->first();

                if ($has_protocol) {

                    $request->session()->put('error', "Você já tem um atendimento aberto. Finalize antes de abrir um novo.");
                    return Redirect('/suporte');
                }

                $protocol = new SacProtocol;

                $protocol->is_warranty = 0;
                $protocol->client_id = $client->id;
                $protocol->type = 6;
                $protocol->origin = 5;
                $protocol->description = $request->c_message;

                //$total_actual = 0;
                //$get_user = array();
                //$users = DB::table('sac_protocol')
                //        ->leftjoin('users', 'sac_protocol.r_code', '=', 'users.r_code')
                //        ->select(DB::raw('count(sac_protocol.id) as total, users.first_name, users.r_code'))
                //        ->where('sac_protocol.is_completed', 0)
                //        ->where('sac_protocol.is_cancelled', 0)
                //        ->where('sac_protocol.r_code', '!=', null)
                //        ->groupBy('sac_protocol.r_code')
                //        ->orderBy('sac_protocol.r_code', 'DESC')
                //        ->get();

                //if ($users->count() > 0) {
                //    foreach ($users as $index => $user) {

                //      if ($index == 0) {
                //        $total_actual = $user->total;
                //  }
                // $last_total = $user->total;
                // if ($last_total <= $total_actual) {
                //   $total_actual = $last_total;
                // $get_user = array(
                //   'r_code' => $user->r_code,
                //  'total' => $user->total
                //);
                //}

                //}
                //}
                //if(count($get_user) > 0) {
                //  $protocol->r_code = $get_user['r_code'];
                //}

                $protocol->save();

                // CREATE PROTOCOL
                $code = sacCreateProtocol($protocol->id);

				$message = new SacMsgProtocol;
				
				$settings = App\Model\Settings::where('command', 'sac_msg_interaction')->first();
                $message->message = $settings->value;
                $message->is_system = 0;
				$message->authorized_id = 1864;
                $message->sac_protocol_id = $protocol->id;
                $message->save();

				$request->session()->put('sac_client_id', $protocol->client_id);
                $request->session()->put('success', 'Foi gerado um procotolo para sua solicitação, em breve entraremos em contato.');
                return view('gree_sac_client.create_protocol_success', [
                    'protocol' => $code,
					'protocol_id' => $protocol->id
                ]);
            }

            $pattern = array(
                'title' => $subject,
                'description' => nl2br("
                    <b>Nome:</b> ". $request->c_name ."
                    <br><b>Email:</b> ". $request->c_email ."
                    <br><b>CPF:</b> ". $request->c_identity ."
                    <br><b>Telefone:</b> ". $request->c_phone ."
                    <br><b>Cidade:</b> ". $request->c_city ."
                    <br><b>Estado:</b> ". $request->c_state ."
                    <br><b>Mensagem:</b> ". $request->c_message ."
                    <br><br>Esse email foi enviado do site https://gree.com.br/suporte/
                "),
                'template' => 'misc.Default',
                'subject' => $subject,
            );


            SendMailJob::dispatch($pattern, $email);

            $request->session()->put('success', 'Sua mensagem foi enviada com sucesso! Em breve entramos em contato.');
            return redirect('https://gree.com.br/suporte');

        } else {

            $request->session()->put('error', "Você não enviou todos os dados corretamente, re-envie novamente.");
            return Redirect('/suporte');
        }

    }

    public function faq_do(Request $request) {

        if (!googleRecaptchaV3($request->token, $request->action)) {

            $request->session()->put('error', 'Não foi possível realizar o cadastro, pois o google detectou que você é um robo.');
            return redirect()->back();
        }

        $client = SacClient::where('identity', $request->identity)->first();

        if (!$client) {

            $client = new SacClient;
            if (empty($request->name)) {

                $request->session()->put('error', 'Você não informou o nome do cliente.');
                return redirect()->back();
            } else if (empty($request->type_people)) {

                $request->session()->put('error', 'Você precisa dizer se é pessoa fisica ou juridica.');
                return redirect()->back();
            } else if (empty($request->identity)) {

                $request->session()->put('error', 'Você precisa informar um número de documento desse cliente.');
                return redirect()->back();
            } else if (empty($request->phone)) {

                $request->session()->put('error', 'Você precisa informar um número de telefone.');
                return redirect()->back();
            } else if (empty($request->email)) {

                $request->session()->put('error', 'Você precisa informar um email de contado do cliente.');
                return redirect()->back();
            }
            $client->name = $request->name;
            $client->type_people = $request->type_people;
            $client->identity = $request->identity;
            $client->address = $request->address;
            $client->complement = $request->complement;
            $client->latitude = $request->latitude;
            $client->longitude = $request->longitude;
            $client->phone = $request->phone;
            $client->phone_2 = $request->phone_2;
            $client->email = $request->email;
            $client->email = $request->email;

            $client->save();

        }


        $has_protocol = SacProtocol::where('client_id', $client->id)
            ->where('is_completed', 0)
            ->where('is_cancelled', 0)
            ->first();

        if ($has_protocol) {

            $request->session()->put('error', "Você já tem um atendimento aberto. Finalize antes de abrir um novo.");
            return Redirect('/suporte');
        }

        $protocol = new SacProtocol;

        $protocol->is_warranty = 0;
        $protocol->number_nf = $request->number_nf;
        $protocol->client_id = $client->id;
        $protocol->type = $request->type;
        $old_date = str_replace("/", "-", $request->buy_date);
        $buy_date = date('Y-m-d', strtotime($old_date));
        $protocol->buy_date = $buy_date;
        $protocol->shop = $request->shop;
        $protocol->origin = 5;
        $protocol->description = $request->description;
        $protocol->address = $request->address;
		
		if ($request->sac_problem_category)
        	$protocol->sac_problem_category_id = $request->sac_problem_category;
		
		if ($request->installed_by)
        	$protocol->installed_by = $request->installed_by;

        $protocol->complement = $request->complement;
        if ($request->latitude and $request->longitude) {
            $protocol->latitude = $request->latitude;
            $protocol->longitude = $request->longitude;
        }

        if ($request->hasFile('nf_file')) {
            $response = uploadS3(1, $request->nf_file, $request);
            if ($response['success']) {
                $protocol->nf_file = $response['url'];
            } else {
                return Redirect('/suporte');
            }
        }
        if ($request->hasFile('tag_file')) {
            $response = uploadS3(2, $request->tag_file, $request);
            if ($response['success']) {
                $protocol->tag_file = $response['url'];
            } else {
                return Redirect('/suporte');
            }
        }
        if ($request->hasFile('c_install_file')) {
            $response = uploadS3(3, $request->c_install_file, $request);
            if ($response['success']) {
                $protocol->c_install_file = $response['url'];
            } else {
                return Redirect('/suporte');
            }
        }

        // $total_actual = 0;
        // $get_user = array();
        // $users = DB::table('sac_protocol')
        //         ->leftjoin('users', 'sac_protocol.r_code', '=', 'users.r_code')
        //         ->select(DB::raw('count(sac_protocol.id) as total, users.first_name, users.r_code'))
        //         ->where('sac_protocol.is_completed', 0)
        //         ->where('sac_protocol.is_cancelled', 0)
        //         ->where('sac_protocol.r_code', '!=', null)
        //         ->groupBy('sac_protocol.r_code')
        //         ->orderBy('sac_protocol.r_code', 'DESC')
        //         ->get();

        // if ($users->count() > 0) {
        //     foreach ($users as $index => $user) {

        //         if ($index == 0) {
        //             $total_actual = $user->total;
        //         }
        //         $last_total = $user->total;
        //         if ($last_total <= $total_actual) {
        //             $total_actual = $last_total;
        //             $get_user = array(
        //                 'r_code' => $user->r_code,
        //                 'total' => $user->total
        //                 );
        //         }

        //     }
        // }
        // if(count($get_user) > 0) {
        //     $protocol->r_code = $get_user['r_code'];
        // }

        $protocol->save();

        // JSON
        $raw_payload = $request->input('json_data');
        $payload = json_decode($raw_payload, true);

        if (isset($payload)) {
            SacModelProtocol::where('sac_protocol_id', $protocol->id)->delete();
            foreach ($payload as $key) {

                $add = new SacModelProtocol;
                $add->product_id = $key['product_id'];
                $add->serial_number = $key['serial'];
                $add->sac_protocol_id = $protocol->id;
                $add->save();

            }
        }

        // CREATE PROTOCOL
        $code = sacCreateProtocol($protocol->id);
		
		$message = new SacMsgProtocol;
				
		$settings = App\Model\Settings::where('command', 'sac_msg_interaction')->first();
		$message->message = $settings->value;
		$message->is_system = 0;
		$message->authorized_id = 1864;
		$message->sac_protocol_id = $protocol->id;
		$message->save();

        $multiply = 1.609344;
        $distance = getConfig("sac_distance_km");

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $query = "SELECT *, "
            . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ,8) as distance "
            . "from sac_authorized "
            . "where is_active = 1 and "
            . "type != 3 and "
            . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ) ,8) <= $distance "
            . "order by distance ASC "
            . "LIMIT 10";

        $data = DB::select(DB::raw($query));

        if (count($data) == 0 and $request->type != 3) {

            $p_id = $code;
            $pattern = array(
                'title' => 'SEM AUTORIZADA PRÓXIMA',
                'description' => nl2br("Foi realizado um possível atendimento em garantia: \n \n Protocolo: ". $p_id ." \n Endereço: ". $request->address ." \n \n Em um raio de:". $distance ."KM."),
                'template' => 'misc.DefaultExternal',
                'subject' => 'Gree - sem autorizada próxima',
            );

            SendMailJob::dispatch($pattern, getConfig("sac_email_default"));
        }

        return view('gree_sac_client.create_protocol_success', [
            'protocol' => $code,
			'protocol_id' => $protocol->id
        ]);
    }

    public function findProtocol(Request $request) {

        $protocol = SacProtocol::where('code', $request->protocol)->first();

        if ($protocol) {

            $request->session()->put('sac_client_id', $protocol->client_id);
            return response()->json([
                'success' => true,
            ]);

        } else {

            return response()->json([
                'success' => false,
                'msg' => 'Protocolo de atendimento não foi encontrado.'
            ]);
        }

    }

    public function forgotten(Request $request) {

        if ($request->session()->get('sac_client_email')) {

            $user = SacClient::where('email', $request->session()->get('sac_client_email'))->first();

            if ($user) {

                if ($user->recovery == null) {
                    $code = Str::random(10);
                    $user->recovery = $code;
                    $user->save();

                    $pattern = array(
                        'title' => 'RECUPERAÇÃO DE SENHA',
                        'description' => nl2br("Para realizar a recuperação da sua senha, acessa o link abaixo e informe sua nova senha. \n \n <a href='". $request->root() ."/suporte/recuperar/". $code ."'>". $request->root() ."/suporte/recuperar/". $code ."</a>"),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Gree - Recuperação de senha',
                    );

                    SendMailJob::dispatch($pattern, $user->email);

                    return response()->json([
                        'success' => true,
                    ]);
                } else {

                    return response()->json([
                        'success' => false,
                        'msg' => 'A recuperação da senha já foi enviada para seu email!',
                    ]);
                }

            } else {

                return response()->json([
                    'success' => false,
                    'msg' => 'Não encontramos suas informações, ligue para: '. getConfig("sac_number"),
                ]);
            }

        } else {

            return response()->json([
                'success' => false,
                'msg' => 'Você não tem um email cadastrado, ligue para: '. getConfig("sac_number"),
            ]);

        }

    }

    public function passwordRecovery(Request $request, $code) {

        $client = SacClient::where('recovery', $code)->first();

        return view('gree_sac_client.passRecovery', [
            'client' => $client,
            'code' => $code,
        ]);
    }

    public function passwordRecovery_do(Request $request) {

        $client = SacClient::where('recovery', $request->code)->where('recovery', '!=', null)->first();

        if ($client) {

            $client->password = Hash::make($request->password);
            $client->recovery = null;
            $client->save();

            $request->session()->put('success', 'Sua senha foi alterada com sucesso!');
            return redirect('/suporte');

        } else {

            $request->session()->put('error', 'Ocorreu um erro inesperado.');
            return redirect()->back();
        }

    }

    public function verifyLogin(Request $request) {

        $user = SacClient::where('identity', $request->identity)->first();

        if ($user) {

            $request->session()->put('sac_client_email', $user->email);

            if ($user->password == null) {

                $user->password = Hash::make($request->password_login);
                $user->save();

                if ($user->email) {
                    $pattern = array(
                        'title' => 'ACOMPANHAR ATENDIMENTO',
                        'description' => nl2br("Use nossa central de suporte para abrir atendimento e acompanhar. Para acessar use os seguintes dados: \n \n CPF/CNPJ: ". $user->identity ." \n Senha: ". $request->password_login ." \n Link: <a href='". $request->root() ."/suporte'>". $request->root() ."/suporte</a>"),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Gree - Acompanhar atendimento',
                    );

                    SendMailJob::dispatch($pattern, $user->email);
                }

                $request->session()->put('sac_client_type', $request->type);
                $request->session()->put('sac_client_id', $user->id);
                return response()->json([
                    'success' => true,
                ]);

            } else if (Hash::check($request->password_login, $user->password)) {

                $request->session()->put('sac_client_id', $user->id);
                $request->session()->put('sac_client_type', $request->type);

                return response()->json([
                    'success' => true,
                ]);

            } else {

                return response()->json([
                    'success' => false,
                    'msg' => 'A senha que informou é incorreta.',
                ]);
            }

        } else {

            $user = new SacClient;
            $user->name = $request->name;
            $user->type_people = $request->type_people;
            $user->password = Hash::make($request->password_create);
            $user->identity = $request->identity;
            $user->phone = $request->phone;
            $user->phone_2 = $request->phone_2;
            $user->email = $request->email;

            $user->save();

            if ($request->email) {
                $pattern = array(
                    'title' => 'ACOMPANHAR ATENDIMENTO',
                    'description' => nl2br("Use nossa central de suporte para abrir atendimento e acompanhar. Para acessar use os seguintes dados: \n \n CPF/CNPJ: ". $request->identity ." \n Senha: ". $request->password_create ." \n Link: <a href='". $request->root() ."/suporte'>". $request->root() ."/suporte</a>"),
                    'template' => 'misc.DefaultExternal',
                    'subject' => 'Gree - Acompanhar atendimento',
                );

                SendMailJob::dispatch($pattern, $request->email);
            }

            $request->session()->put('sac_client_type', $request->type);
            $request->session()->put('sac_client_id', $user->id);

            return response()->json([
                'success' => true,
            ]);
        }

    }

    public function panel(Request $request) {

        if ($request->session()->get('url')) {

            $url = $request->session()->get('url');
            $request->session()->forget('url');
            return redirect($url);

        } else {

            $protocol = SacProtocol::where('client_id', $request->session()->get('sac_client_id'))->paginate(10);

            return view('gree_sac_client.panel.main', [
                'protocol' => $protocol
            ]);
        }
    }

    public function interactionProtocol(Request $request, $id) {

        $protocol = SacProtocol::where('id', $id)->where('client_id', $request->session()->get('sac_client_id'))->first();

        if ($protocol) {

            $messages = SacMsgProtocol::where('sac_protocol_id', $protocol->id)->where('message_visible', 1)->get();

            return view('gree_sac_client.panel.chat', [
                'protocol' => $protocol,
                'messages' => $messages,
            ]);

        } else {

            return redirect('/suporte/painel');
        }
    }

    public function ratingProtocol(Request $request) {

        $protocol = SacProtocol::where('id', $request->id)->where('client_id', $request->session()->get('sac_client_id'))->first();

        if ($protocol) {

            if ($protocol->rate > 0) {

                return response()->json([
                    'success' => false,
                    'msg' => 'Atendimento já foi avaliado.'
                ]);
            } else {
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

                return response()->json([
                    'success' => true,
                ]);
            }

        } else {

            return response()->json([
                'success' => false,
                'msg' => 'Atendimento não foi encontrado.'
            ]);
        }

    }

    public function sendMsgProtocol(Request $request) {

        $protocol = SacProtocol::where('id', $request->id)->where('client_id', $request->session()->get('sac_client_id'))->first();

        if ($protocol) {

            if ($protocol->is_completed == 1 or $protocol->is_cancelled == 1) {

                $request->session()->put('success', "O atendimento já foi finalizado.");
                return redirect()->back();
            }

            $message = new SacMsgProtocol;
            $message->message = $request->msg;
            $message->sac_protocol_id = $request->id;
            if ($request->hasFile('attach')) {
                $response = uploadS3(1, $request->attach, $request);
                if ($response['success']) {
                    $message->file = $response['url'];
                } else {
                    return redirect()->back();
                }
            }
            $message->save();

            if ($protocol->r_code) {

                $user = Users::where('r_code', $protocol->r_code)->first();

                $pattern = array(
                    'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                    'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                    'template' => 'misc.Default',
                    'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                );

                NotifyUser('Protocolo: #'. $protocol->code, $protocol->r_code, 'fa-exclamation', 'text-info', 'Temos uma nova atualização desse protocolo, clique aqui para visualizar.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                SendMailJob::dispatch($pattern, $user->email);
            }


            $request->session()->put('success', "Mensagem enviada com sucesso! Aguarde uma resposta.");
            return redirect()->back();
        } else {

            return redirect('/suporte/painel');
        }

    }

    public function newProtocol(Request $request) {


        return view('gree_sac_client.panel.new_protocol');
    }

    public function endProtocol(Request $request) {

        $protocol = SacProtocol::where('id', $request->id)->where('client_id', $request->session()->get('sac_client_id'))->first();

        if ($protocol) {

            if ($protocol->is_completed == 1 or $protocol->is_cancelled == 1) {

                $request->session()->put('success', "O atendimento já foi finalizado.");
                return redirect()->back();
            }

            $protocol->is_completed = 1;
            $protocol->pending_completed = 0;
            $protocol->save();

            $message = new SacMsgProtocol;
            $message->message = nl2br("<b>Cliente finalizou o atendimento</b> \n". date('d-m-Y H:i') ." \n\n <b>Com a seguinte justificativa:</b> \n". $request->description);
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
                NotifyUser('Protocolo: #'. $protocol->code, $protocol->r_code, 'fa-exclamation', 'text-info', 'Protocolo foi finalizado pelo cliente, clique aqui para ver mais detalhes.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
            }
            $request->session()->put('success', "Seu atendimento foi finalizado com sucesso!");
            return redirect()->back();
        } else {

            return redirect('/suporte/painel');
        }

    }

    public function newProtocol_do(Request $request) {

        $has_protocol = SacProtocol::where('client_id', $request->session()->get('sac_client_id'))
            ->where('is_completed', 0)
            ->where('is_cancelled', 0)
            ->first();

        if ($has_protocol) {

            $request->session()->put('error', "Você já tem um atendimento aberto. Finalize antes de abrir um novo.");
            return Redirect('/suporte/novo/atendimento');
        }

        $protocol = new SacProtocol;

        $protocol->is_warranty = 0;
        $protocol->number_nf = $request->number_nf;
        $protocol->client_id = $request->session()->get('sac_client_id');
        $protocol->type = $request->type;
        $old_date = str_replace("/", "-", $request->buy_date);
        $buy_date = date('Y-m-d', strtotime($old_date));
        $protocol->buy_date = $buy_date;
        $protocol->shop = $request->shop;
        $protocol->origin = 5;
        $protocol->description = $request->description;
        $protocol->address = $request->address;
        $protocol->complement = $request->complement;
        $protocol->latitude = $request->latitude;
        $protocol->longitude = $request->longitude;

        if ($request->hasFile('nf_file')) {
            $response = uploadS3(1, $request->nf_file, $request);
            if ($response['success']) {
                $protocol->nf_file = $response['url'];
            } else {
                return Redirect('/suporte/novo/atendimento');
            }
        }
        if ($request->hasFile('tag_file')) {
            $response = uploadS3(2, $request->tag_file, $request);
            if ($response['success']) {
                $protocol->tag_file = $response['url'];
            } else {
                return Redirect('/suporte/novo/atendimento');
            }
        }
        if ($request->hasFile('c_install_file')) {
            $response = uploadS3(3, $request->c_install_file, $request);
            if ($response['success']) {
                $protocol->c_install_file = $response['url'];
            } else {
                return Redirect('/suporte/novo/atendimento');
            }
        }

        $protocol->save();

        // JSON
        $raw_payload = $request->input('json_data');
        $payload = json_decode($raw_payload, true);

        if (isset($payload)) {
            SacModelProtocol::where('sac_protocol_id', $protocol->id)->delete();
            foreach ($payload as $key) {

                $add = new SacModelProtocol;
                $add->product_id = $key['product_id'];
                $add->serial_number = $key['serial'];
                $add->sac_protocol_id = $protocol->id;
                $add->save();

            }
        }

        // CREATE PROTOCOL
        $code = sacCreateProtocol($protocol->id);

        $multiply = 1.609344;
        $distance = getConfig("sac_distance_km");

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $query = "SELECT *, "
            . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ,8) as distance "
            . "from sac_authorized "
            . "where is_active = 1 and "
            . "type != 3 and "
            . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ) ,8) <= $distance "
            . "order by distance ASC "
            . "LIMIT 10";

        $data = DB::select(DB::raw($query));

        if (count($data) == 0 and $request->type != 3) {

            $p_id = $code;
            $pattern = array(
                'title' => 'SEM AUTORIZADA PRÓXIMA',
                'description' => nl2br("Foi realizado um possível atendimento em garantia: \n \n Protocolo: ". $p_id ." \n Endereço: ". $request->address ." \n \n Em um raio de:". $distance ."KM."),
                'template' => 'misc.DefaultExternal',
                'subject' => 'Gree - sem autorizada próxima',
            );

            SendMailJob::dispatch($pattern, getConfig("sac_email_default"));
        }

        $request->session()->put('success', "Atendimento criado com sucesso!");
        return redirect('/suporte/painel');
    }

}
