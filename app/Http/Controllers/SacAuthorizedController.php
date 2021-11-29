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
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

use App\Model\SacProtocol;
use App\Model\SacModelProtocol;
use App\Model\SacPartProtocol;
use App\Model\SacAuthorized;
use App\Model\SacClient;
use App\Model\Parts;
use App\Model\SacMsgProtocol;
use App\Model\SacOsProtocol;
use App\Model\SacBuyPart;
use App\Model\SacBuyParts;
use App\Model\SacMsgOs;

use App\Model\UserOnPermissions;
use App\Model\Users;
use App\Model\ProductAir;
use App\Model\SacAuthorizedNotify;
use App\Model\Settings;
use App\Model\SacModelOs;
use App\Model\SacRemittancePart;
use App\Model\SacRemittanceParts;

use \App\Http\Controllers\Services\FileManipulationTrait;

class SacAuthorizedController extends Controller
{

    use FileManipulationTrait;

    public function login(Request $request) {


        return view('gree_sac_authorized.login');
    }

    public function verifyLogin(Request $request) {
        $user = SacAuthorized::where('identity', $request->identity)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->is_active == 1) {
                    $request->session()->put('sac_authorized_id', $user->id);
                    $request->session()->put('sac_authorized_name', $user->name);
                    $request->session()->put('sac_authorized_email', $user->email);
                    $request->session()->put('sac_authorized_identity', $user->identity);
					$request->session()->put('sac_authorized_is_remittance', $user->is_remittance);

                    if ($request->session()->get('url')) {

                        $url = $request->session()->get('url');
                        $request->session()->forget('url');
                        return redirect($url);
                    } else {
                        return redirect('/autorizada/painel');
                    }
                } else {
                    $request->session()->put('error', 'Seu acesso está bloqueado, entre em contato com o SAC.');
                    return redirect()->back();
                }
            } else {

                $request->session()->put('error', 'CNPJ ou senha incorreto.');
                return redirect()->back();
            }
        } else {

            $request->session()->put('error', 'CNPJ ou senha incorreto.');
            return redirect()->back();
        }
    }

    public function panel(Request $request) {

        $pending = SacOsProtocol::where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->where('is_paid', 0)
            ->where('is_cancelled', 0)
            ->count();

        $done = SacOsProtocol::where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->where('is_paid', 1)
            ->where('is_cancelled', 0)
            ->count();

        $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

        $multiply = 1.609344;
        $distance = getConfig("sac_distance_km");

        $latitude = $authorized->latitude;
        $longitude = $authorized->longitude;

        $query = "SELECT *, "
            . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ,8) as distance "
            . "from sac_protocol "
            . "where is_warranty = 1 and "
            . "authorized_id IS NULL and "
            . "is_cancelled = 0 and "
            . "is_completed = 0 and "
            . "is_refund = 0 and "
            . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ) ,8) <= $distance "
            . "order by distance ASC "
            . "LIMIT 10";

        $protocol = DB::select(DB::raw($query));

        $os_pending = SacOsProtocol::where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->where('expert_name', null)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0)
            ->first();

        $hasid = 0;
        if ($os_pending) {
            $hasid = $os_pending->sac_protocol_id;
        }

        return view('gree_sac_authorized.panel.main', [
            'pending' => $pending,
            'done' => $done,
            'authorized' => $authorized,
            'protocol' => $protocol,
            'hasid' => $hasid,
            'i' => 1,
        ]);
    }

    public function loadList(Request $request) {

        if ($request->session()->get('sac_authorized_id')) {

            $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

            if ($authorized->type >= 2) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            } else {

                $multiply = 1.609344;
                $distance = getConfig("sac_distance_km");

                $latitude = $authorized->latitude;
                $longitude = $authorized->longitude;

                $query = "SELECT *, "
                    . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ,8) as distance "
                    . "from sac_protocol "
                    . "where is_warranty = 1 and "
                    . "authorized_id IS NULL and "
                    . "is_cancelled = 0 and "
                    . "is_completed = 0 and "
                    . "is_refund = 0 and "
                    . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ) ,8) <= $distance "
                    . "order by distance ASC "
                    . "LIMIT 10";

                $protocol = DB::select(DB::raw($query));
                $sac_km_price = getConfig("sac_km_price");
                $sac_visit_price = getConfig("sac_visit_price");

                $data = array();
                foreach ($protocol as $key) {
                    $row = array();
                    $row['protocol'] = $key->code;
                    $row['address'] = $key->city.' - '.$key->state;
                    if ($key->not_address == 1) {
                        $row['price'] = 'R$ '. number_format($sac_visit_price, 2);
                    } else {
                        $row['price'] = 'R$ '. number_format(($key->distance * $sac_km_price) + $sac_visit_price, 2);
                        $max_total = $sac_visit_price + (number_format($sac_km_price * $key->distance,2));
                        if (number_format(($key->distance * $sac_km_price) + $sac_visit_price, 2) > $max_total) {
                            $row['price'] = 'R$ '. number_format($sac_visit_price, 2);
                        }
                    }

                    $row['id'] =  $key->id;

                    array_push($data, $row);
                }

                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function logout(Request $request) {
        $request->session()->forget('sac_authorized_id');
        return redirect('/autorizada');
    }

    public function profile(Request $request) {

        $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

        $latitude = $authorized->latitude;
        $longitude = $authorized->longitude;
        $name_contact = $authorized->name_contact;
        $address = $authorized->address;
        $zipcode = $authorized->zipcode;
        $city = $authorized->city;
        $state = $authorized->state;
        $complement = $authorized->complement;
        $email = $authorized->email;
        $email_copy = $authorized->email_copy;
        $phone_1 = $authorized->phone_1;
        $phone_2 = $authorized->phone_2;
        $is_active = $authorized->is_active;
        $account = $authorized->account;
        $agency = $authorized->agency;
        $bank = $authorized->bank;

        return view('gree_sac_authorized.panel.profile', [
            'account' => $account,
            'agency' => $agency,
            'bank' => $bank,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'name_contact' => $name_contact,
            'address' => $address,
            'zipcode' => $zipcode,
            'city' => $city,
            'state' => $state,
            'complement' => $complement,
            'email' => $email,
            'email_copy' => $email_copy,
            'phone_1' => $phone_1,
            'phone_2' => $phone_2,
            'is_active' => $is_active,
        ]);
    }

    public function profile_do(Request $request) {

        $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));
		
		$account_update = $this->compareAddressAuthorized($authorized, $request);

        $authorized->latitude = $request->latitude;
        $authorized->longitude = $request->longitude;
        $authorized->name_contact = $request->name_contact;
        $authorized->zipcode = $request->zipcode;
        $authorized->city = $request->city;
        $authorized->state = $request->state;
        $authorized->complement = $request->complement;
        $authorized->email = $request->email;
        $authorized->email_copy = $request->email_copy;
        $authorized->phone_1 = $request->phone_1;
        $authorized->phone_2 = $request->phone_2;
        $authorized->account = $request->account;
        $authorized->agency = $request->agency;
        $authorized->bank = $request->bank;

        if ($request->password) {
            $authorized->password = Hash::make($request->password);
        }
		
		if (count($account_update) > 0) {

            $fields_html = '(';
            foreach ($account_update as $index => $fields) {
                $fields_html .= $index.': '.$fields.', ';
            }    
            $fields_html .= ')';
			
			$sac = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                    ->select('users.*')
                    ->where('user_on_permissions.can_approv', 1)
                    ->where('user_on_permissions.perm_id', 6)
                    ->where('users.filter_line', 1)
                    ->get();
			
			foreach ($sac as $key) {
				
				NotifyUser(
                    'Credênciado: #'. $authorized->code, 
                    $key->r_code, 
                    'fa-exclamation', 'text-info', 
                    'Credênciado atualizou o perfil, com as seguintes informações: '.$fields_html.', necessário atualização no infor.', 
                    $request->root() .'/sac/authorized/edit/'. $authorized->id
                );	
			}
		}

		$authorized->address = $request->address;
        $authorized->save();

        $request->session()->put('success', 'Seu perfil foi atualizado com sucesso.');
        return redirect()->back();
    }
	
	public function compareAddressAuthorized($authorized, $request) {
        
        $source = [
            "Nome do responsável para contato" => $authorized->name_contact,
            "Endereço" => $authorized->address,
            "Complemento" => $authorized->complement,
            "CEP" => $authorized->zipcode,
            "Cidade" => $authorized->city,
            "Estado" => $authorized->state,
            "Telefone 1" => $authorized->phone_1,
            "Telefone 2" => $authorized->phone_2,
            "Email" => $authorized->email,
            "Email em cópia" => $authorized->email_copy,
            "Agência" => $authorized->agency,
            "Conta" => $authorized->account,
            "Banco" => $authorized->bank
        ];

        $filter = [
            "Nome do responsável para contato" => $request->name_contact,
            "Endereço" => $request->address,
            "Complemento" => $request->complement,
            "CEP" => $request->zipcode,
            "Cidade" => $request->city,
            "Estado" => $request->state,
            "Telefone 1" => $request->phone_1,
            "Telefone 2" => $request->phone_2,
            "Email" => $request->email,
            "Email em cópia" => $request->email_copy,
            "Agência" => $request->agency,
            "Conta" => $request->account,
            "Banco" => $request->bank
        ];

        $result = array_diff($filter, $source);
        return $result;
    }

    public function myOs(Request $request) {

        $os_pending = SacOsProtocol::where('authorized_id', $request->session()->get('sac_authorized_id'))
                                ->where('expert_name', null)
                                ->where('is_cancelled', 0)
                                ->where('is_paid', 0)
                                ->first();
        if ($os_pending) {

            return redirect('/autorizada/painel');
        }

        $os = SacOsProtocol::with('sacProtocol.clientProtocol')
            ->ShowAuthorizedOs($request->session()->get('sac_authorized_id'))
            ->orderBy('id', 'DESC');
		
		
        $array_input = collect([
            'os',
            'protocol',
        ]);

        // SAVE FILTERS
        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."os"){
                    $os->where('code', 'like', '%'. $valor_filtro .'%');
                }
				if($nome_filtro == $filtros_sessao[1]."protocol"){
                    $os->whereHas('sacProtocol', function($q) use ($valor_filtro) {
						$q->where('code', 'like', '%'. $valor_filtro .'%');
					});
                }
                
            }

        }    


        return view('gree_sac_authorized.panel.my_os', [
            'os' => $os->paginate(10),
        ]);

    }

    public function interactionOs(Request $request, $id) {

        $os = SacOsProtocol::where('id', $id)->where('authorized_id', $request->session()->get('sac_authorized_id'))->first();
        if ($os) {

            $messages = SacMsgOs::where('sac_os_protocol_id', $os->id)->where('message_visible', 1)->get();
            return view('gree_sac_authorized.panel.chat', [
                'id' => $id,
                'os' => $os,
                'messages' => $messages,
            ]);

        } else {
            return redirect('/autorizada/os');
        }
    }


    public function sendMsgOs(Request $request) {

        $os = SacOsProtocol::where('id', $request->id)->where('authorized_id', $request->session()->get('sac_authorized_id'))->first();
        if ($os) {

            if ($os->is_paid == 1 or $os->is_cancelled == 1) {
                $request->session()->put('success', "A ordem de serviço já foi finalizada.");
                return redirect()->back();
            }

            $message = new SacMsgOs;
            $message->message_visible = 1;
            $message->message = $request->msg == ''? '' : $request->msg;
            $message->sac_os_protocol_id = $request->id;

            if ($request->hasFile('attach')) {
                $response = $this->uploadS3(1, $request->attach, $request, 800);
                if ($response['success']) {
                    $message->file = $response['url'];
                } else {
                    $request->session()->put('error', 'Não foi possível realizar upload da imagem');
                    return redirect()->back();
                }
            }
            $message->save();

            if($request->os_code != "") {
                $code = $request->os_code;
            } else {
                $code = "Protocolo: ".$request->protocol_code;
            }

            $assist_tect = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                ->select('users.*')
				->where('users.is_active', 1)
                ->where('user_on_permissions.grade', 99)
                ->where('user_on_permissions.perm_id', 16)
                ->get();

            if (count($assist_tect) > 0) {

                foreach ($assist_tect as $asst) {

                    if ($asst->email) {

                        $pattern = array(
                            'title' => 'ATUALIZAÇÃO DA ORDEM DE SERVIÇO',
                            'description' => nl2br("Olá! Temos atualizações da ordem de serviço: (". $code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/os/interactive/". $os->id ."'>". $request->root() ."/sac/warranty/os/interactive/". $os->id ."</a>"),
                            'template' => 'misc.Default',
                            'subject' => 'Ordem de serviço: '. $code .' atualização!',
                        );

                        NotifyUser('Ordem de serviço: #'. $code, $asst->r_code, 'fa-exclamation', 'text-info', 'Temos uma nova atualização dessa ordem de serviço, clique aqui para visualizar.', $request->root() .'/sac/warranty/os/interactive/'. $os->id);
                        SendMailJob::dispatch($pattern, $asst->email);
                    }

                }
            }

            $request->session()->put('success', "Mensagem enviada com sucesso! Aguarde uma resposta.");

            return redirect()->back();
        } else {

            return redirect('/autorizada/os');
        }
    }

    public function technicalArea(Request $request) {

        $storage = Storage::disk('s3');
        $client = $storage->getAdapter()->getClient();

        $command = $client->getCommand('ListObjects');
        $command['Bucket'] = $storage->getAdapter()->getBucket();

        $s3_root_folder = 'public_gree/documentos_tecnicos/';
        $s3_root_level = 1; //representa o nivel de profundidade das pastas a partir do diretorio principal da aws
        $array_input = collect([
            'model',
            's3_prefix',
            's3_folder',
            's3_level',
        ]);

        // SAVE FILTERS
        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        //dd($filtros_sessao);die;
        $os_pending = SacOsProtocol::where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->where('expert_name', null)
            ->where('is_cancelled', 0)
            ->first();

        if ($os_pending) {

            return redirect('/autorizada/painel');
        }

        $product = ProductAir::orderBy('id', 'DESC');

        $s3_files = array(
            'back'=>"",
            'prefix'=>$s3_root_folder,
            'local_folder'=>"",
            'folder_level'=>$s3_root_level,
            'folders'=>collect([]),
            'files'=>[],
            'files_ignored'=>[],
        );

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."model"){
                    $product->where('model', 'like', '%'. $valor_filtro .'%');
                }
                if($nome_filtro == $filtros_sessao[1]."s3_prefix"){

                    $last_char = substr($valor_filtro, -1);
                    if($last_char=="/"){
                        $s3_files['prefix'] = $valor_filtro;
                    }else{
                        $s3_files['prefix'] = $valor_filtro."/";
                    }
                }
                if($nome_filtro == $filtros_sessao[1]."s3_level"){
                    $s3_files['folder_level'] = $valor_filtro;
                }
                if($nome_filtro == $filtros_sessao[1]."s3_folder"){
                    $s3_files['local_folder'] = $valor_filtro;
                }
            }

        }else{
            $s3_files['prefix'] = $s3_root_folder;
            $s3_files['folder_level'] = $s3_root_level;
            $s3_files['local_folder'] = "";
        }

        $nivel_atual = $s3_files['folder_level'];
        $level = $nivel_atual+1;


        $arr_old_path = explode('/', $s3_files['prefix']);
        $local_folder = $arr_old_path[$nivel_atual];

        $arr_old_path = array_slice($arr_old_path, 0, $nivel_atual);
        $old_path =  implode('/', $arr_old_path);

        $command['Prefix'] = $s3_files['prefix'].$s3_files['local_folder'];

        if($command['Prefix'] == $s3_root_folder){
            $s3_files['back'] = [];
        }else{
            $s3_files['back'] = ['level'=>$nivel_atual-1, 'name'=> $old_path, 'local_folder'=>$local_folder];
        }

        $result = $client->execute($command);
        $files = $result->getPath('Contents');

        $ignore_path = null;
        if( !empty($files) ){
            foreach($files as $file){
                $url_link = "https://s3.amazonaws.com/gree-app.com.br/".$file['Key'];

                $part_files = explode('/', $file['Key']);
                $size = count($part_files);
                $last_part = $part_files[$size-1];

                $info = pathinfo($last_part);
                $s3_files['pathinfo'][] = $info;

                $folders = array_slice($part_files, 0, $size-1);
                $folder =  implode('/', $folders);

                $current_folders = array_slice($part_files, 0, $level);
                $current_folder = implode('/', $current_folders);
                $s3_files['prefix'] = $current_folder;

                if(count($folders)>$level){
                    $ignore_path = $folders[$level];

                    $s3_files['folders'] = collect($s3_files['folders']);
                    $s3_files['folders']->push(collect(['level'=>$level, 'name'=> $folders[$level]]));
                    $s3_files['folders'] = $s3_files['folders']->unique();


                }

                if( isset($info['extension']) ){

                    $names=[];
                    if($s3_files['folders']->isNotEmpty()){
                        $names = $s3_files['folders']->pluck('name')->toArray();
                    }
                    if (Str::contains($file['Key'], $names ) ) {
                        $s3_files['files_ignored'][] = $url_link;
                    }else{
                        $s3_files['files'][] = [
                            'link'=>$url_link,
                            'basename'=>$info['basename'],
                            'extension'=>$info['extension'],
                        ];
                    }


                }
            }

        }


        return view('gree_sac_authorized.panel.technical_area', [
            'product' => $product->paginate(10),
            's3_files' => $s3_files,
        ]);

    }

    public function protocolAccept(Request $request) {

        if ($request->id) {

            $protocol = SacProtocol::where('id', $request->id)
                ->where('authorized_id', null)
                ->where('is_warranty', 1)
                ->where('is_cancelled', 0)
                ->where('is_completed', 0)
                ->first();

            if ($protocol) {

                $protocol->authorized_id = $request->session()->get('sac_authorized_id');
                $protocol->save();

                $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

                $multiply = 1.609344;
                $distance = getConfig("sac_distance_km");

                $latitude = $authorized->latitude;
                $longitude = $authorized->longitude;

                $query = "SELECT *, "
                    . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ,8) as distance "
                    . "from sac_protocol "
                    . "where id = $request->id and "
                    . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ) ,8) <= $distance "
                    . "order by distance ASC "
                    . "LIMIT 1";

                $data = DB::select(DB::raw($query));

                if (count($data) == 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Essa O.S não pode ser aceita por você, pois excede a quilometragem máxima de aceitamento.',
                    ]);
                }

                if (SacOsProtocol::where('sac_protocol_id', $protocol->id)->where('is_paid', 0)->where('is_cancelled', 0)->where('authorized_id', $request->session()->get('sac_authorized_id'))->count() == 0) {

                    $sac_visit_price = getConfig("sac_visit_price");
                    $sac_km_price = getConfig("sac_km_price");
                    $sac_distance_km = getConfig("sac_distance_km");

                    // Armazena o modelo único
                    $create_unique_os = array();

                    // Armazena os modelos em conjunto
                    $create_group_os = array();

                    $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                        ->where('sac_model_protocol.sac_protocol_id', $protocol->id)
                        ->select('product_air.model', 'sac_model_protocol.*')
                        ->get();

                    foreach ($models as $model) {
                        if (substr($model->model, -2) == '/O') {

                            $search = substr($model->model, 0, -2);
                            $create_group_os[$search][] = array(
                                $model->product_id,
                                $model->serial_number,
                                $model->model,
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                $model->id,
                            );

                        } else if (substr($model->model, -2) == '/I') {

                            $search = substr($model->model, 0, -2);
                            $create_group_os[$search][] = array(
                                $model->product_id,
                                $model->serial_number,
                                $model->model,
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                $model->id,
                            );

                        } else {

                            array_push($create_unique_os, array(
                                $model->product_id,
                                $model->serial_number,
                                $model->model,
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                $model->id,
                            ));
                        }
                    }

                    if (count($create_unique_os) > 0) {
                        foreach ($create_unique_os as $create_u_os) {
                            // criar os
                            $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                                ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                                ->where('sac_os_protocol.is_paid', 0)
                                ->where('sac_os_protocol.is_cancelled', 0)
                                ->where('sac_model_os.product_id', $create_u_os[0])
                                ->first();

                            if (!$os) {

                                $os = new SacOsProtocol;
                                $os->authorized_id = $request->session()->get('sac_authorized_id');
                                $os->sac_protocol_id = $protocol->id;
                                if (count($data) > 0) {
                                    if ($protocol->not_address == 1) {
                                        $total = $sac_visit_price;
                                    } else {
                                        $total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
                                    }

                                    $max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
                                    if (number_format($total, 2, '.', '') > $max_total) {
                                        $total = $sac_visit_price;
                                    }
                                } else {
                                    $total = $sac_visit_price;
                                }

                                $os->visit_total = number_format($total, 2);
                                $os->total = number_format($total, 2);
                                $os->save();

                            }
                            $add = new SacModelOs;
                            $add->product_id = $create_u_os[0];
                            $add->serial_number = array_key_exists(1, $create_u_os) == TRUE ? $create_u_os[1] : '';
                            $add->sac_os_protocol_id = $os->id;
                            $add->sac_protocol_id = $protocol->id;
                            $add->sac_model_protocol_id = $create_u_os[7];
                            $add->authorized_id = $request->session()->get('sac_authorized_id');
                            $add->save();
                        }
                    }
                    if (count($create_group_os) > 0) {
                        foreach ($create_group_os as $arr_key => $create_g_os) {
                            // criar os
                            $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                                ->leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                                ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                                ->where('sac_os_protocol.is_paid', 0)
                                ->where('sac_os_protocol.is_cancelled', 0)
                                ->where(function ($query) use ($arr_key) {
                                    $query->where('product_air.model', $arr_key .'/O')
                                        ->Orwhere('product_air.model', $arr_key .'/I');
                                })
                                ->first();

                            if (!$os) {

                                $os = new SacOsProtocol;
                                $os->authorized_id = $request->session()->get('sac_authorized_id');
                                $os->sac_protocol_id = $protocol->id;
                                if (count($data) > 0) {
                                    if ($protocol->not_address == 1) {
                                        $total = $sac_visit_price;
                                    } else {
                                        $total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
                                    }

                                    $max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
                                    if (number_format($total, 2, '.', '') > $max_total) {
                                        $total = $sac_visit_price;
                                    }
                                } else {
                                    $total = $sac_visit_price;
                                }

                                $os->visit_total = number_format($total, 2);
                                $os->total = number_format($total, 2);
                                $os->save();

                            }
                            foreach ($create_g_os as $arr_model) {
                                $add = new SacModelOs;
                                $add->product_id = $arr_model[0];
                                $add->serial_number = array_key_exists(1, $arr_model) == TRUE ? $arr_model[1] : '';
                                $add->authorized_id = $request->session()->get('sac_authorized_id');
                                $add->sac_os_protocol_id = $os->id;
                                $add->sac_protocol_id = $protocol->id;
                                $add->sac_model_protocol_id = $arr_model[7];
                                $add->save();
                            }
                        }
                    }

                    $parts = SacPartProtocol::where('sac_protocol_id', $protocol->id)
						->where('sac_os_protocol_id', $os->id)
                        ->get();

                    if (count($parts) > 0) {
                        foreach ($parts as $key) {
                            $upd_os_price = SacOsProtocol::leftjoin('sac_model_os', 'sac_os_protocol.id', '=', 'sac_model_os.sac_os_protocol_id')
                                ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                ->where('sac_os_protocol.authorized_id', $request->session()->get('sac_authorized_id'))
                                ->where('sac_model_os.product_id', $key->product_id)
                                ->select('sac_os_protocol.*')
                                ->first();

                            if ($key->is_approv == 1) {
                                $upd_os_price->total = number_format($upd_os_price->total + $key->total, 2);
                            }

                            if (is_null($upd_os_price->code)) {
                                //Add Code
                                $last_id = Settings::where('command', 'last_os_id')->first();
                                $seg = $last_id->value + 1;
                                $upd_os_price->created_at = date('Y-m-d H:i:s');
                                $upd_os_price->code = 'W'. $seg;

                                // update id
                                $last_id->value = $seg;
                                $last_id->save();
                            }
                            $upd_os_price->save();
                        }
                    }

                }

                $add_authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));
                if ($add_authorized) {

                    $client = SacClient::find($protocol->client_id);
                    $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                        ->select('product_air.model')
                        ->where('sac_protocol_id', $protocol->id)
                        ->get();

                    $pattern = array(
                        'a_name' => $add_authorized->name,
                        'c_name' => $client->name,
                        'c_phone' => $client->phone,
                        'c_phone_2' => $client->phone_2,
                        'p_address' => $protocol->address,
                        'p_model' => $models,
                        'p_tag_file' => $protocol->tag_file,
                        'p_description' => $protocol->description,
                        'title' => 'ATENDIMENTO ACEITO',
                        'description' => '',
                        'template' => 'sac.notifyAuthorized',
                        'subject' => 'Gree: Protocolo de atendimento: '. $protocol->code,
                    );

                    SendMailJob::dispatch($pattern, $add_authorized->email);
                }

                return response()->json([
                    'success' => true,
                    'id' => $protocol->id,
                ]);

            } else {

                return response()->json([
                    'success' => false,
                    'msg' => 'Essa O.S não precisa mais de atendimento, obrigado por sua manifestação.',
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'msg' => 'Você não enviou o código de aceitamento. Tente novamente.',
            ]);
        }

    }

    public function protocolGet(Request $request, $id) {

        $protocol = SacProtocol::leftjoin('sac_client', 'sac_client.id', '=', 'sac_protocol.client_id')
            ->select('sac_client.name', 'sac_client.phone', 'sac_client.phone_2', 'sac_protocol.address', 'sac_protocol.complement', 'sac_protocol.description', 'sac_protocol.id')
            ->where('sac_protocol.id', $id)
            ->first();

        if ($protocol) {


            $os_pending = SacOsProtocol::where('authorized_id', $request->session()->get('sac_authorized_id'))
                ->where('expert_name', null)
                ->where('is_cancelled', 0)
                ->where('is_paid', 0)
                ->first();



            $date = date('d-m-Y H:i', strtotime('+ 1 days'));
            if ($os_pending) {
                $date = date('d-m-Y H:i', strtotime($os_pending->created_at .'+ 1 days'));

                $model = SacModelOs::leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                    ->select('product_air.*', 'sac_model_os.serial_number as smp_serial_number')
                    ->where('sac_model_os.sac_os_protocol_id', $os_pending->id)
                    ->get();
            } else {

                $model = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                    ->select('product_air.model')
                    ->where('sac_protocol_id', $protocol->id)
                    ->get();
                Log::info('Verificar OS com data não encontrada: protocol id '. $protocol->id .' Autorizada: '. $request->session()->get('sac_authorized_id'));
            }

            $all_model = "";
            foreach ($model as $key) {
                $all_model .= $key->model .' ';
            }

            return response()->json([
                'success' => true,
                'name' => $protocol->name,
                'address' => $protocol->address,
                'complement' => $protocol->complement,
                'phones' => $protocol->phone .' / ' . $protocol->phone_2,
                'description' => $protocol->description,
                'model' => $all_model,
                'end_date' => $date,
                'id' => $protocol->id,
            ]);

        } else {

            return response()->json([
                'success' => false,
                'msg' => 'Infelizemente ocorreu um erro inesperado. Atualize a página.',
            ]);
        }

    }

    public function protocolOSGet(Request $request, $id) {

        $os = SacOsProtocol::where('id', $id)
            ->where('is_paid', 0)
            ->where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->first();

        if ($os) {

            $protocol = SacProtocol::leftjoin('sac_client', 'sac_client.id', '=', 'sac_protocol.client_id')
                ->select('sac_client.name', 'sac_client.phone', 'sac_client.phone_2', 'sac_protocol.address', 'sac_protocol.complement', 'sac_protocol.description', 'sac_protocol.id')
                ->where('sac_protocol.id', $os->sac_protocol_id)
                ->first();

            $model = SacModelOs::leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                ->select('product_air.*', 'sac_model_os.serial_number as smp_serial_number')
                ->where('sac_model_os.sac_os_protocol_id', $os->id)
                ->get();

            $options = '<option value=""></option>';
            $all_model = "";
            foreach ($model as $key) {
                $all_model .= $key->model .' ';
                $options .= '<option value="'. $key->id .'" data-serial="'. $key->smp_serial_number .'">'. $key->model .' ('. $key->smp_serial_number .')</option>';
            }

            $data_view_part = SacPartProtocol::leftjoin('product_air', 'sac_part_protocol.product_id', '=', 'product_air.id')
                ->leftjoin('parts', 'sac_part_protocol.part_id', '=', 'parts.id')
                ->select('sac_part_protocol.*', 'product_air.model', 'parts.description')
				->where('sac_os_protocol_id', $os->id)
                ->where('sac_protocol_id', $protocol->id)->get();

            $v_parts = array();
            foreach ($data_view_part as $key) {
                $row = array();
                $row['model'] = $key->model;
                $row['part'] = $key->description;
                $row['attach'] = $key->attach;
                $row['quantity'] = $key->quantity;
                if ($key->is_approv == 1) {
                    $row['status'] = 1;
                } else if ($key->is_repprov == 1) {
                    $row['status'] = 2;
                } else {
                    $row['status'] = 3;
                }
                $row['total'] = number_format($key->total, 2);

                array_push($v_parts, $row);
            }

            return response()->json([
                'success' => true,
                'name' => $protocol->name,
                'address' => $protocol->address,
                'complement' => $protocol->complement,
                'phones' => $protocol->phone .' / ' . $protocol->phone_2,
                'description' => $protocol->description,
                'model' => $all_model,
                'date' => date('Y-m-d', strtotime($os->visit_date)),
                'hour' => date('H:i', strtotime($os->visit_date)),
                'expert_name' => $os->expert_name,
                'expert_phone' => $os->expert_phone,
                'parts' => $options,
                'v_parts' => $v_parts,
                'id' => $os->id,
            ]);

        } else {

            return response()->json([
                'success' => false,
                'msg' => 'Infelizemente ocorreu um erro inesperado. Atualize a página.',
            ]);
        }

    }

    public function protocolsendPart(Request $request) {
        $os = SacOsProtocol::where('id', $request->id)
            ->where('is_paid', 0)
            ->where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->first();

        if ($os) {

            $txt = "";
            $group = $request->group;
            if ($group) {
                if (count($group) > 0) {
                    for ($i = 0; $i < count($group); $i++) {

                        $item = new SacPartProtocol;
                        if ($group[$i]['model'] and $group[$i]['part']) {
                            $item->sac_protocol_id = $os->sac_protocol_id;
							$item->sac_os_protocol_id = $os->id;
                            $item->product_id = $group[$i]['model'];
							$item->serial_number = $group[$i]['serial_number'];
                            $item->part_id = $group[$i]['part'];
                            $item->description = $group[$i]['description'];
                            $qty = 1;
                            if ($group[$i]['quantity']) {
                                $qty = $group[$i]['quantity'];
                                $item->quantity = $group[$i]['quantity'];
                            } else {
                                $item->quantity = 1;
                            }



                            $part = Parts::find($group[$i]['part']);
                            $txt .= nl2br("\n". $qty ."x ". $part->description);

                            if (isset($group[$i]['picture'])) {
                                $response = $this->uploadS3($i, $group[$i]['picture'], $request);
                                if ($response['success']) {
                                    $item->attach = $response['url'];
                                } else {
                                    return Redirect('/autorizada/os');
                                }
                            }

                            $item->save();

                        }

                        if ($request->hasFile('report')) {
                            $response = $this->uploadS3(99, $request->report, $request);
                            if ($response['success']) {
                                $os->diagnostic_test_part = $response['url'];
                            } else {
                                return Redirect('/autorizada/os');
                            }
                        }

                        $os->save();


                    }

                    if (is_null($os->code)) {
                        //Add Code
                        $last_id = Settings::where('command', 'last_os_id')->first();
                        $seg = $last_id->value + 1;
                        $os->created_at = date('Y-m-d H:i:s');
                        $os->code = 'W'. $seg;
                        $os->save();

                        // update id
                        $last_id->value = $seg;
                        $last_id->save();
                    }

                    $assist_tect = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
						->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.perm_id', 16)
                        ->get();

                    if (count($assist_tect) > 0) {

                        $protocol = SacProtocol::find($os->sac_protocol_id);

                        foreach($assist_tect as $key) {

                            $pattern = array(
                                'title' => 'PEDIDO DE PEÇAS [Protocolo: '. $protocol->code .'] [OS: '. $os->code .']',
                                'description' => nl2br("Olá! Foi realizado os seguintes pedido de peças: ". $txt ." \n\n Relatório técnico: ". $os->diagnostic_test_part ." \n\n veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/parts/". $os->id ."'>". $request->root() ."/sac/warranty/parts/". $os->id ."</a>"),
                                'template' => 'misc.Default',
                                'subject' => 'Protocolo: '. $protocol->code .' pedido de peças',
                            );

                            NotifyUser('Protocolo: #'. $protocol->code, $key->r_code, 'fa-exclamation', 'text-info', 'Foi realizado um novo pedido de peças, clique aqui para visualizar.', $request->root() .'/sac/warranty/parts/'. $os->id);
                            SendMailJob::dispatch($pattern, $key->email);

                        }
                    }

                    $request->session()->put('success', "Peça(s) enviada(s) para análise, aguarde o contato da Gree!");
                    return redirect()->back();

                } else {
                    $request->session()->put('error', "Você precisa adicionar uma peça para enviar para análise.");
                    return redirect()->back();
                }
            } else {

                $request->session()->put('error', "Você precisa adicionar uma peça para enviar para análise.");
                return redirect()->back();
            }


        } else {

            $request->session()->put('error', "Infelizemente ocorreu um erro inesperado. Atualize a página.");
            return redirect()->back();
        }

    }

    public function buyPartList(Request $request) {

        $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));
        $ob = SacBuyPart::where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->paginate(10);

        return view('gree_sac_authorized.panel.buy_part_list', [
            'ob' => $ob,
            'authorized' => $authorized
        ]);
    }

    public function buyPartPrint(Request $request, $id) {

        $ob = SacBuyPart::where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->where('id', $id)
            ->first();

        if ($ob) {

            $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

            $parts = SacBuyParts::where('sac_buy_part_id', $id)
                ->get();

            return view('gree_sac_authorized.panel.ob_print', [
                'ob' => $ob,
                'authorized' => $authorized,
                'parts' => $parts
            ]);

        } else {

            return redirect('/autorizada/painel');

        }
    }

    public function buyPart(Request $request) {

        $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

        $resale = 0;
        if($authorized->type == 3) {
            $resale = getConfig('sac_resale');
        }

        return view('gree_sac_authorized.panel.buy_part', [
            'resale' => $resale,
            'type' => $authorized->type
        ]);
    }

    public function buyPart_do(Request $request) {

        $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

        $txt = "";
        $txt .= nl2br(
            "<div style='text-align: left !important;>'> \n <b>CNPJ:</b> ". $authorized->identity
            ."<br> <b>Nome:</b> ". $authorized->name
            ."<br> <b>Email:</b> ". $authorized->email
            ."<br> <b>Telefone:</b> ". $authorized->phone_1
            ."<br> <b>Endereço:</b> ". $authorized->address
            ."<p> Lista de peças: </p>"
        );

        $group = json_decode($request->group);

        if ($group) {

            if (count($group) > 0) {
                $ob = new SacBuyPart;
                $ob->optional = $request->optional;
                $ob->authorized_id = $request->session()->get('sac_authorized_id');
                $ob->save();

                // Add code
                $add_code = SacBuyPart::find($ob->id);
                $add_code->code = 'G'. $ob->id;
                $add_code->save();

                for ($i = 0; $i < count($group); $i++) {

                    $part_group = (array) $group[$i];

                    if (isset($part_group['model']) and isset($part_group['part'])) {

                        $obp = new SacBuyParts;
                        $pic = "";

                        if (isset($part_group['picture'])) {
                            $response = $this->uploadS3Base64($i, $part_group['picture'], $request);
                            if ($response['success']) {
                                $pic = $response['url'];
                                $obp->image = $response['url'];
                            }
                        }

                        $obp->model = $part_group['model'];
                        if (isset($part_group['part'])) {
                            $obp->part = $part_group['part'];
                        }
                        $qty = 1;
                        if ($part_group['quantity']) {
                            $qty = $part_group['quantity'];
                            $obp->quantity = $part_group['quantity'];
                        } else {
                            $obp->quantity = 1;
                        }

                        $obp->description = $part_group['description'];
                        $obp->not_part = $part_group['exist'] == 1? 0: 1;
                        $obp->sac_buy_part_id = $ob->id;
                        $obp->save();


                        if ($part_group['exist'] != 1) {

                            $model_part = is_numeric($part_group['model']) ? ProductAir::find($part_group['model'])->model : $part_group['model'];
                            $name_part = $part_group['part'];
                        } else {
                            $product_air = ProductAir::find($part_group['model']);
                            $model_part = $product_air ? $product_air->model : ' - ';

                            $part = Parts::find($part_group['part']);
                            $name_part = $part ? $part->description. ' ('. $part->code . ')' : ' - ';
                        }

                        $txt .= nl2br(
                            " <b>Modelo do equipamento:</b> ". $model_part
                            ."<br> <b>Código/Nome da peça:</b> ". $name_part
                            ."<br> <b>Quantidade:</b> ". $qty
                            ."<br> <b>Descrição da peça:</b> ". $part_group['description']
                            ."<br> <b>Imagem para identificação:</b> <a href='". $pic ."'>". $pic ."</a>"
                        );


                        $txt .= nl2br(
                            "\n <hr> \n"
                        );
                    }
                }

                $txt .= nl2br("<p> Observação adicional: </p>". $request->optional);

                $txt .= nl2br("</div>");
                $assist_tect = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                    ->select('users.*')
					->where('users.is_active', 1)
                    ->where('user_on_permissions.grade', 99)
                    ->where('user_on_permissions.can_approv', 0)
                    ->where('user_on_permissions.perm_id', 6)
                    ->where('users.filter_line', 1)
                    ->get();

                if ($assist_tect) {

                    foreach($assist_tect as $key) {

                        $pattern = array(
                            'title' => 'PEDIDO DE COMPRA DE PEÇAS',
                            'description' => nl2br("Olá! Foi realizado os seguintes pedido de compra: ". $txt),
                            'template' => 'misc.Default',
                            'subject' => 'Novo pedido de compra de peças',
                        );

                        NotifyUser('Nova cotação de peças', $key->r_code, 'fa-exclamation', 'text-info', 'Foi realizado um novo pedido de compra de peças, clique aqui para visualizar.', $request->root() .'/sac/warranty/ob/');
                        // SendMailJob::dispatch($pattern, $key->email);

                    }
                }

                if ($authorized->email) {
                    $pattern = array(
                        'title' => 'PEDIDO DE COMPRA DE PEÇA(S)',
                        'description' => nl2br("Olá! Abaixo suas informações de compra da(s) peça(s): ". $txt),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Pedido enviado com sucesso!',
                    );

                    SendMailJob::dispatch($pattern, $authorized->email);
                }

                $request->session()->put('success', "Sua solicitação de peças, foi enviado por email para administração, você terá uma cópia em seu email.");
                return redirect('/autorizada/lista/ob');
            } else {

                $request->session()->put('error', "Você não adicionou nenhuma peça a sua solicitação.");
                return redirect('/autorizada/comprar/peca');

            }

        } else {

            $request->session()->put('error', "Você não adicionou nenhuma peça a sua solicitação.");
            return redirect('/autorizada/comprar/peca');
        }
    }

    public function comunicationList(Request $request) {

        $comunication = SacAuthorizedNotify::where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->orWhere(function ($query) {
                $query->where('authorized_id', null);
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('gree_sac_authorized.panel.notifications_list', [
            'comunication' => $comunication
        ]);
    }

    public function comunicationView(Request $request, $id) {

        $comunication = SacAuthorizedNotify::where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->where('id', $id)
            ->orWhere(function ($query) use ($id) {
                $query->where('authorized_id', null)
                    ->where('id', $id);
            })
            ->first();
        if ($comunication) {

            return view('gree_sac_authorized.panel.notification_view', [
                'comunication' => $comunication,
                'id' => $id
            ]);

        } else {

            return redirect('/autorizada/painel');
        }


    }

    public function newProtocol(Request $request) {

        $os_pending = SacOsProtocol::where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->where('expert_name', null)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0)
            ->first();

        if ($os_pending) {

            return redirect('/autorizada/painel');
        }

        $sac_problem_category = \App\Model\SacProblemCategory::All();

        return view('gree_sac_authorized.panel.new_protocol', [
            'sac_problem_category' => $sac_problem_category,
        ]);
    }

    public function newProtocol_do(Request $request) {

        $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

        if ($authorized->type == 3 and $authorized->identity != '03.519.135/0001-56') {
            $request->session()->put('error', "Você não tem permissão para abrir OS, apenas para compra de peças.");
            return Redirect('/autorizada/atendimento');
        }

        $client = SacClient::where('identity', $request->identity)->first();

        if (!$client) {
            $client = new SacClient;
            if (empty($request->c_name)) {

                $request->session()->put('error', 'Você não informou o nome do cliente.');
                return redirect()->back();
            } else if (empty($request->c_type_people)) {

                $request->session()->put('error', 'Você precisa dizer se é pessoa fisica ou juridica.');
                return redirect()->back();
            } else if (empty($request->identity)) {

                $request->session()->put('error', 'Você precisa informar um número de documento desse cliente.');
                return redirect()->back();
            } else if (empty($request->c_phone)) {

                $request->session()->put('error', 'Você precisa informar um número de telefone.');
                return redirect()->back();
            } else if (empty($request->c_email)) {

                $request->session()->put('error', 'Você precisa informar um email de contado do cliente.');
                return redirect()->back();
            }
            $client->name = $request->c_name;
            $client->type_people = $request->c_type_people;
            $client->identity = $request->identity;
            $client->phone = $request->c_phone;
            $client->phone_2 = $request->c_phone_2;
            $client->email = $request->c_email;
            $client->save();

        }



        $has_protocol = SacProtocol::where('client_id', $client->id)
            ->where('is_completed', 0)
            ->where('is_cancelled', 0)
            ->first();

        //if ($has_protocol) {

        //  $request->session()->put('error', "O cliente já tem um atendimento em aberto. Entre em contato com a Gree");
        //  return Redirect('/autorizada/atendimento');
        //}

        $protocol = new SacProtocol;

        $protocol->is_warranty = 0;
        $protocol->number_nf = $request->number_nf;
        $protocol->client_id = $client->id;
        $protocol->authorized_id = $request->session()->get('sac_authorized_id');
        $old_date = str_replace("/", "-", $request->buy_date);
        $buy_date = date('Y-m-d', strtotime($old_date));
        $protocol->buy_date = $buy_date;
        $protocol->shop = $request->shop;
        $protocol->type = 2;
        $protocol->origin = 5;
        $protocol->in_progress = 1;
        $protocol->description = $request->description;
        $protocol->address = $request->address;
        $protocol->complement = $request->complement;
        $protocol->sac_problem_category_id = $request->sac_problem_category;
        $protocol->installed_by = 2;
		$protocol->is_entry_manual = 1;
		
		 $total_actual = 0;
         /* $get_user = array();
         $users = DB::table('sac_protocol')
                 ->join('users', 'sac_protocol.r_code', '=', 'users.r_code')
                 ->select(DB::raw('count(sac_protocol.id) as total, users.first_name, users.r_code'))
                 ->where('sac_protocol.is_completed', 0)
                 ->where('sac_protocol.is_cancelled', 0)
			 	 ->whereNotIn('sac_protocol.r_code', [2692, 2571])
                 ->where('sac_protocol.r_code', '!=', null)
                 ->groupBy('sac_protocol.r_code')
                 ->orderBy('sac_protocol.r_code', 'DESC')
                 ->get();

         if ($users->count() > 0) {
             foreach ($users as $index => $user) {

                 if ($index == 0) {
                     $total_actual = $user->total;
                 }
                 $last_total = $user->total;
                 if ($last_total <= $total_actual) {
                     $total_actual = $last_total;
                     $get_user = array(
                         'r_code' => $user->r_code,
                         'total' => $user->total
                         );
                 }

             }
         }
        if(count($get_user) > 0) {
			if (isset($get_user['r_code'])) {
				$protocol->r_code = $get_user['r_code'];	 
			}
        } */


        if ($request->latitude and $request->longitude) {
            $protocol->latitude = $request->latitude;
            $protocol->longitude = $request->longitude;
        }

        if ($request->hasFile('nf_file')) {
            $response = $this->uploadS3(1, $request->nf_file, $request, 800);
            if ($response['success']) {
                $protocol->nf_file = $response['url'];
            } else {
                return Redirect('/autorizada/atendimento');
            }
        }
        if ($request->hasFile('tag_file')) {
            $response = $this->uploadS3(2, $request->tag_file, $request, 800);
            if ($response['success']) {
                $protocol->tag_file = $response['url'];
            } else {
                return Redirect('/autorizada/atendimento');
            }
        }
        if ($request->hasFile('c_install_file')) {
            $response = $this->uploadS3(3, $request->c_install_file, $request, 800);
            if ($response['success']) {
                $protocol->c_install_file = $response['url'];
            } else {
                return Redirect('/autorizada/atendimento');
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

        $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

        $multiply = 1.609344;
        $distance = getConfig("sac_distance_km");

        $latitude = $authorized->latitude;
        $longitude = $authorized->longitude;

        $query = "SELECT *, "
            . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ,8) as distance "
            . "from sac_protocol "
            . "where id = ". $protocol->id ." and "
            . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ) ,8) "
            . "order by distance ASC "
            . "LIMIT 1";

        $data = DB::select(DB::raw($query));

        // JSON
        $raw_payload = $request->input('json_data');
        $payload = json_decode($raw_payload, true);

        if (isset($payload)) {
            $sac_visit_price = getConfig("sac_visit_price");
            $sac_km_price = getConfig("sac_km_price");
            $sac_distance_km = getConfig("sac_distance_km");

            // Armazena o modelo único
            $create_unique_os = array();

            // Armazena os modelos em conjunto
            $create_group_os = array();

            foreach ($payload as $key) {
                $obj = $key;
                $model = SacModelProtocol::where('sac_protocol_id', $protocol->id)->where('product_id', $obj['product_id'])->first();
                if (substr($obj['product_text'], -2) == '/O') {

                    $search = substr($obj['product_text'], 0, -2);
                    $create_group_os[$search][] = array(
                        $obj['product_id'],
                        $obj['serial'],
                        $obj['product_text'],
                        $obj['date'],
                        $obj['hour'],
                        $obj['expert_name'],
                        $obj['expert_phone'],
                        $model->id,
                    );

                } else if (substr($obj['product_text'], -2) == '/I') {

                    $search = substr($obj['product_text'], 0, -2);
                    $create_group_os[$search][] = array(
                        $obj['product_id'],
                        $obj['serial'],
                        $obj['product_text'],
                        $obj['date'],
                        $obj['hour'],
                        $obj['expert_name'],
                        $obj['expert_phone'],
                        $model->id,
                    );

                } else {

                    array_push($create_unique_os, array(
                        $obj['product_id'],
                        $obj['serial'],
                        $obj['product_text'],
                        $obj['date'],
                        $obj['hour'],
                        $obj['expert_name'],
                        $obj['expert_phone'],
                        $model->id,
                    ));
                }

            }

            if (count($create_unique_os) > 0) {
                foreach ($create_unique_os as $create_u_os) {
                    // criar os
                    $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                        ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                        ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                        ->where('sac_os_protocol.is_paid', 0)
                        ->where('sac_os_protocol.is_cancelled', 0)
                        ->where('sac_model_os.product_id', $create_u_os[0])
                        ->first();

                    if (!$os) {

                        $os = new SacOsProtocol;
                        $os->authorized_id = $request->session()->get('sac_authorized_id');
                        $os->sac_protocol_id = $protocol->id;
                        if (count($data) > 0) {
                            if ($protocol->not_address == 1) {
                                $total = $sac_visit_price;
                            } else {
                                $total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
                            }

                            $max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
                            if (number_format($total, 2, '.', '') > $max_total) {
                                $total = $sac_visit_price;
                            }
                        } else {
                            $total = $sac_visit_price;
                        }

                        $os->visit_total = number_format($total, 2);
                        $os->total = number_format($total, 2);

                        $os->expert_name = $create_u_os[5];
                        $os->expert_phone = $create_u_os[6];
                        $hour = $create_u_os[4] == "" ? '12:00' : $create_u_os[4];
                        $os->visit_date = $create_u_os[3] .' '. $hour;
                        $os->save();

                    }
                    $add = new SacModelOs;
                    $add->product_id = $create_u_os[0];
                    $add->serial_number = array_key_exists(1, $create_u_os) == TRUE ? $create_u_os[1] : '';
                    $add->sac_os_protocol_id = $os->id;
                    $add->sac_protocol_id = $protocol->id;
                    $add->sac_model_protocol_id = $create_u_os[7];
                    $add->authorized_id = $protocol->authorized_id;
                    $add->save();
                }
            }
            if (count($create_group_os) > 0) {
                foreach ($create_group_os as $arr_key => $create_g_os) {
                    // criar os
                    $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                        ->leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                        ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                        ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                        ->where('sac_os_protocol.is_paid', 0)
                        ->where('sac_os_protocol.is_cancelled', 0)
                        ->where(function ($query) use ($arr_key) {
                            $query->where('product_air.model', $arr_key .'/O')
                                ->Orwhere('product_air.model', $arr_key .'/I');
                        })
                        ->first();

                    if (!$os) {

                        $os = new SacOsProtocol;
                        $os->authorized_id = $request->session()->get('sac_authorized_id');
                        $os->sac_protocol_id = $protocol->id;
                        if (count($data) > 0) {
                            if ($protocol->not_address == 1) {
                                $total = $sac_visit_price;
                            } else {
                                $total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
                            }

                            $max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
                            if (number_format($total, 2, '.', '') > $max_total) {
                                $total = $sac_visit_price;
                            }
                        } else {
                            $total = $sac_visit_price;
                        }

                        $os->visit_total = number_format($total, 2);
                        $os->total = number_format($total, 2);

                        $os->expert_name = $create_g_os[0][5];
                        $os->expert_phone = $create_g_os[0][6];
                        $hour = $create_g_os[0][4] == "" ? '12:00' : $create_g_os[0][4];
                        $os->visit_date = $create_g_os[0][3] .' '. $hour;
                        $os->save();


                    }
                    foreach ($create_g_os as $arr_model) {
                        $add = new SacModelOs;
                        $add->product_id = $arr_model[0];
                        $add->serial_number = array_key_exists(1, $arr_model) == TRUE ? $arr_model[1] : '';
                        $add->authorized_id = $protocol->authorized_id;
                        $add->sac_os_protocol_id = $os->id;
                        $add->sac_protocol_id = $protocol->id;
                        $add->sac_model_protocol_id = $arr_model[7];
                        $add->save();
                    }
                }
            }
        }

        // if ($client->email) {

        //     $authorized = SacAuthorized::find($protocol->authorized_id);

        //     $pattern = array(
        //         'c_name' => $client->name,
        //         'a_name' => $authorized->name,
        //         'e_name' => $request->expert_name,
        //         'e_phone' => $request->expert_phone,
        //         'c_date' => $request->date .' '. $hour .':00',
        //         'title' => 'ATENDIMENTO AGENDADO',
        //         'description' => '',
        //         'template' => 'sac.notifyClient',
        //         'subject' => 'Gree: Protocolo atualizado: '. $protocol->code,
        //     );

        //     SendMailJob::dispatch($pattern, $client->email);
        // }

        // VERIFY PARTS
        $txt = "";
        $group = $request->group;
        if (!empty($group)) {
            if (count($group) > 0) {
                for ($i = 0; $i < count($group); $i++) {

                    $item = new SacPartProtocol;
                    if (isset($group[$i]['p_model']) and isset($group[$i]['part'])) {
                        $item->sac_protocol_id = $protocol->id;
						$item->sac_os_protocol_id = $os->id;
                        $item->product_id = $group[$i]['p_model'];
						$item->serial_number = $group[$i]['serial_number'];
                        $item->part_id = $group[$i]['part'];
                        $item->description = $group[$i]['p_description'];
                        $qty = 1;
                        if ($group[$i]['quantity']) {
                            $qty = $group[$i]['quantity'];
                            $item->quantity = $group[$i]['quantity'];
                        } else {
                            $item->quantity = $qty;
                        }

                        $part = Parts::find($group[$i]['part']);
                        if ($part) {
                            $txt .= nl2br("\n". $qty ."x ". $part->description);
                        } else {
                            $request->session()->get('error', 'Ocorreu algum erro ao buscar a peça, realize novamente sua solicitação.');
                            return redirect()->back();
                        }

                        if (isset($group[$i]['picture'])) {
                            $response = uploadS3($i, $group[$i]['picture'], $request);
                            if ($response['success']) {
                                $item->attach = $response['url'];
                            } else {
                                return Redirect('/autorizada/os');
                            }
                        }

                        $item->save();

                        $os = SacOsProtocol::leftjoin('sac_model_os', 'sac_os_protocol.id', '=', 'sac_model_os.sac_os_protocol_id')
                            ->where('sac_model_os.authorized_id', $protocol->authorized_id)
                            ->where('sac_model_os.sac_protocol_id', $protocol->id)
                            ->where('sac_model_os.product_id', $group[$i]['p_model'])
                            ->select('sac_os_protocol.*')
                            ->first();

                        if ($os) {
                            if (is_null($os->code)) {
                                //Add Code
                                $last_id = Settings::where('command', 'last_os_id')->first();
                                $seg = $last_id->value + 1;
                                $os->created_at = date('Y-m-d H:i:s');
                                $os->code = 'W'. $seg;
                                $os->save();

                                // update id
                                $last_id->value = $seg;
                                $last_id->save();
                            }
                        }

                    }

                    if ($request->hasFile('report')) {
                        $response = uploadS3(99, $request->report, $request);
                        if ($response['success']) {
                            if (isset($group[$i]['p_model'])) {
                                $os = SacOsProtocol::leftjoin('sac_model_os', 'sac_os_protocol.id', '=', 'sac_model_os.sac_os_protocol_id')
                                    ->where('sac_model_os.authorized_id', $protocol->authorized_id)
                                    ->where('sac_model_os.sac_protocol_id', $protocol->id)
                                    ->where('sac_model_os.product_id', $group[$i]['p_model'])
                                    ->select('sac_os_protocol.*')
                                    ->first();

                                if ($os) {
                                    $os->diagnostic_test_part = $response['url'];
                                    $os->save();
                                }
                            }
                        } else {
                            return Redirect('/autorizada/os');
                        }
                    }
                }


                $assist_tect = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                    ->select('users.*')
					->where('users.is_active', 1)
                    ->where('user_on_permissions.grade', 99)
                    ->where('user_on_permissions.can_approv', 1)
                    ->where('user_on_permissions.perm_id', 16)
                    ->where('users.filter_line', 1)
                    ->get();

                if ($assist_tect) {

                    foreach($assist_tect as $key) {
                        $os = SacOsProtocol::where('authorized_id', $protocol->authorized_id)
                            ->where('sac_protocol_id', $protocol->id)
                            ->first();
                        if ($os) {
                            $pattern = array(
                                'title' => 'PEDIDO DE PEÇAS [Protocolo: '. $code .'] [OS: '. $os->code .']',
                                'description' => nl2br("Olá! Foi realizado os seguintes pedido de peças: ". $txt ." \n\n Relatório técnico: ". $os->diagnostic_test_part ." \n\n veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/parts/". $os->id ."'>". $request->root() ."/sac/warranty/parts/". $os->id ."</a>"),
                                'template' => 'misc.Default',
                                'subject' => 'Protocolo: '. $code .' pedido de peças',
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }

                        NotifyUser('Protocolo: #'. $code, $key->r_code, 'fa-exclamation', 'text-info', 'Foi realizado um novo pedido de peças, clique aqui para visualizar.', $request->root() .'/sac/warranty/parts/'. $os->id);

                    }
                }

            }
        }

        $request->session()->put('success', "Atendimento criado com sucesso! Olhe suas O.S");
        return redirect('/autorizada/painel');
    }

    public function OsPrint(Request $request, $id) {

        $os = SacOsProtocol::where('id', $id)->where('authorized_id', $request->session()->get('sac_authorized_id'))->first();

        if ($os) {

            $protocol = SacProtocol::find($os->sac_protocol_id);

            $authorized = SacAuthorized::find($os->authorized_id);
            $client = SacClient::find($protocol->client_id);

            $sac_models = SacModelOs::leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                ->select('product_air.*', 'sac_model_os.serial_number as smp_serial_number')
                ->where('sac_model_os.sac_os_protocol_id', $os->id)
                ->get();

            $parts = SacPartProtocol::leftjoin('parts', 'sac_part_protocol.part_id', '=', 'parts.id')
                ->select('sac_part_protocol.*', 'parts.description as parts_description', 'parts.code')
                ->where('sac_protocol_id', $os->sac_protocol_id)
				->where('sac_os_protocol_id', $os->id)
                ->where('sac_part_protocol.is_approv', 1)
                ->orWhere(function ($query) use ($os) {
                    $query->where('sac_protocol_id', $os->sac_protocol_id)
                        ->where('sac_part_protocol.is_approv', 0)
                        ->where('sac_part_protocol.is_repprov', 0);
                })
                ->get();

            return view('gree_sac_authorized.panel.os_print', [
                'parts' => $parts,
                'os' => $os,
                'protocol' => $protocol,
                'authorized' => $authorized,
                'client' => $client,
                'sac_models' => $sac_models,
            ]);

        } else {

            $request->session()->put('error', "Ordem de serviço não foi encontrado...");
            return redirect('/autorizada/painel');
        }

    }

    public function protocolAcceptLink(Request $request, $code) {

        $protocol = SacProtocol::where('accept_code', $code)->first();

        if ($protocol) {

            if ($protocol->authorized_id == $request->session()->get('sac_authorized_id')) {

                return redirect('/autorizada/painel');
            } else if ($protocol->authorized_id != NULL and $protocol->is_warranty == 1 and $protocol->is_cancelled == 0 and $protocol->is_completed == 0) {

                $request->session()->put('already_accept', 1);
                return redirect('/autorizada/painel');
            } else if ($protocol->is_warranty == 0) {

                $request->session()->put('already_accept', 1);
                return redirect('/autorizada/painel');
            } else {

                $protocol->authorized_id = $request->session()->get('sac_authorized_id');
                $protocol->save();

                $os = SacOsProtocol::where('sac_protocol_id', $protocol->id)
                    ->where('is_paid', 0)
                    ->where('is_cancelled', 0)
                    ->where('authorized_id', $request->session()->get('sac_authorized_id'))
                    ->first();

                if (!$os) {

                    $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

                    $multiply = 1.609344;
                    $distance = getConfig("sac_distance_km");

                    $latitude = $authorized->latitude;
                    $longitude = $authorized->longitude;

                    $query = "SELECT *, "
                        . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                        . "cos( radians(latitude) ) * "
                        . "cos( radians(longitude) - radians('$longitude') ) + "
                        . "sin( radians('$latitude') ) * "
                        . "sin( radians(latitude) ) ) ,8) as distance "
                        . "from sac_protocol "
                        . "where id = ". $protocol->id ." and "
                        . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                        . "cos( radians(latitude) ) * "
                        . "cos( radians(longitude) - radians('$longitude') ) + "
                        . "sin( radians('$latitude') ) * "
                        . "sin( radians(latitude) ) ) ) ,8) "
                        . "order by distance ASC "
                        . "LIMIT 1";

                    $data = DB::select(DB::raw($query));

                    if (count($data) == 0) {
                        return response()->json([
                            'success' => false,
                            'msg' => 'Essa O.S não pode ser aceita por você, pois excede a quilometragem máxima de aceitamento.',
                        ]);
                    }

                    $sac_visit_price = getConfig("sac_visit_price");
                    $sac_km_price = getConfig("sac_km_price");
                    $sac_distance_km = getConfig("sac_distance_km");

                    // Armazena o modelo único
                    $create_unique_os = array();

                    // Armazena os modelos em conjunto
                    $create_group_os = array();

                    $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                        ->where('sac_model_protocol.sac_protocol_id', $protocol->id)
                        ->select('product_air.model', 'sac_model_protocol.*')
                        ->get();

                    foreach ($models as $model) {
                        if (substr($model->model, -2) == '/O') {

                            $search = substr($model->model, 0, -2);
                            $create_group_os[$search][] = array(
                                $model->product_id,
                                $model->serial_number,
                                $model->model,
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                $model->id,
                            );

                        } else if (substr($model->model, -2) == '/I') {

                            $search = substr($model->model, 0, -2);
                            $create_group_os[$search][] = array(
                                $model->product_id,
                                $model->serial_number,
                                $model->model,
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                $model->id,
                            );

                        } else {

                            array_push($create_unique_os, array(
                                $model->product_id,
                                $model->serial_number,
                                $model->model,
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                $model->id,
                            ));
                        }
                    }

                    if (count($create_unique_os) > 0) {
                        foreach ($create_unique_os as $create_u_os) {
                            // criar os
                            $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                                ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                                ->where('sac_os_protocol.is_paid', 0)
                                ->where('sac_os_protocol.is_cancelled', 0)
                                ->where('sac_model_os.product_id', $create_u_os[0])
                                ->first();

                            if (!$os) {

                                $os = new SacOsProtocol;
                                $os->authorized_id = $request->session()->get('sac_authorized_id');
                                $os->sac_protocol_id = $protocol->id;
                                if (count($data) > 0) {
                                    if ($protocol->not_address == 1) {
                                        $total = $sac_visit_price;
                                    } else {
                                        $total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
                                    }

                                    $max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
                                    if (number_format($total, 2, '.', '') > $max_total) {
                                        $total = $sac_visit_price;
                                    }
                                } else {
                                    $total = $sac_visit_price;
                                }

                                $os->visit_total = number_format($total, 2);
                                $os->total = number_format($total, 2);

                                $os->save();

                            }
                            $add = new SacModelOs;
                            $add->product_id = $create_u_os[0];
                            $add->serial_number = array_key_exists(1, $create_u_os) == TRUE ? $create_u_os[1] : '';
                            $add->sac_os_protocol_id = $os->id;
                            $add->sac_protocol_id = $protocol->id;
                            $add->sac_model_protocol_id = $create_u_os[7];
                            $add->authorized_id = $request->session()->get('sac_authorized_id');
                            $add->save();
                        }
                    }
                    if (count($create_group_os) > 0) {
                        foreach ($create_group_os as $arr_key => $create_g_os) {
                            // criar os
                            $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                                ->leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                                ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                                ->where('sac_os_protocol.is_paid', 0)
                                ->where('sac_os_protocol.is_cancelled', 0)
                                ->where(function ($query) use ($arr_key) {
                                    $query->where('product_air.model', $arr_key .'/O')
                                        ->Orwhere('product_air.model', $arr_key .'/I');
                                })
                                ->first();

                            if (!$os) {

                                $os = new SacOsProtocol;
                                $os->authorized_id = $request->session()->get('sac_authorized_id');
                                $os->sac_protocol_id = $protocol->id;
                                if (count($data) > 0) {
                                    if ($protocol->not_address == 1) {
                                        $total = $sac_visit_price;
                                    } else {
                                        $total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
                                    }

                                    $max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
                                    if (number_format($total, 2, '.', '') > $max_total) {
                                        $total = $sac_visit_price;
                                    }
                                } else {
                                    $total = $sac_visit_price;
                                }

                                $os->visit_total = number_format($total, 2);
                                $os->total = number_format($total, 2);
                                $os->save();


                            }
                            foreach ($create_g_os as $arr_model) {
                                $add = new SacModelOs;
                                $add->product_id = $arr_model[0];
                                $add->serial_number = array_key_exists(1, $arr_model) == TRUE ? $arr_model[1] : '';
                                $add->authorized_id = $request->session()->get('sac_authorized_id');
                                $add->sac_os_protocol_id = $os->id;
                                $add->sac_protocol_id = $protocol->id;
                                $add->sac_model_protocol_id = $arr_model[7];
                                $add->save();
                            }
                        }
                    }

                    $parts = SacPartProtocol::where('sac_protocol_id', $protocol->id)
						->where('sac_os_protocol_id', $os->id)
                        ->get();

                    if (count($parts) > 0) {
                        foreach ($parts as $key) {
                            $upd_os_price = SacOsProtocol::leftjoin('sac_model_os', 'sac_os_protocol.id', '=', 'sac_model_os.sac_os_protocol_id')
                                ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                ->where('sac_os_protocol.authorized_id', $request->session()->get('sac_authorized_id'))
                                ->where('sac_model_os.product_id', $key->product_id)
                                ->select('sac_os_protocol.*')
                                ->first();

                            if ($key->is_approv == 1) {
                                $upd_os_price->total = number_format($upd_os_price->total + $key->total, 2);
                            }

                            if (is_null($upd_os_price->code)) {
                                //Add Code
                                $last_id = Settings::where('command', 'last_os_id')->first();
                                $seg = $last_id->value + 1;
                                $upd_os_price->created_at = date('Y-m-d H:i:s');
                                $upd_os_price->code = 'W'. $seg;

                                // update id
                                $last_id->value = $seg;
                                $last_id->save();
                            }
                            $upd_os_price->save();
                        }
                    }

                }

                $add_authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));
                if ($add_authorized) {

                    $client = SacClient::find($protocol->client_id);
                    $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                        ->select('product_air.model')
                        ->where('sac_protocol_id', $protocol->id)
                        ->get();

                    $pattern = array(
                        'a_name' => $add_authorized->name,
                        'c_name' => $client->name,
                        'c_phone' => $client->phone,
                        'c_phone_2' => $client->phone_2,
                        'p_address' => $protocol->address,
                        'p_model' => $models,
                        'p_tag_file' => $protocol->tag_file,
                        'p_description' => $protocol->description,
                        'title' => 'ATENDIMENTO ACEITO',
                        'description' => '',
                        'template' => 'sac.notifyAuthorized',
                        'subject' => 'Gree: Protocolo de atendimento: '. $protocol->code,
                    );

                    SendMailJob::dispatch($pattern, $add_authorized->email);
                }
                return redirect('/autorizada/painel');
            }

        } else {

            $request->session()->put('error', "Atendimento não foi encontrado...");
            return redirect('/autorizada/painel');
        }

    }

    public function protocolOsUpdate(Request $request) {
        $os = SacOsProtocol::where('id', $request->id)
            ->where('is_paid', 0)
            ->where('is_cancelled', 0)
            ->where('authorized_id', $request->session()->get('sac_authorized_id'))
            ->first();

        if ($os) {

            $authorized = SacAuthorized::find($os->authorized_id);

            $protocol = SacProtocol::find($os->sac_protocol_id);

            if ($request->has_completed == 1) {

                $protocol->pending_completed = 1;

                $client = SacClient::find($protocol->client_id);
                $source = array('(', ')', ' ', '-');
                $replace = array('', '', '', '');

                $phone = "";
                if ($client->phone) {
                    $phone = str_replace($source, $replace, $client->phone);

                } else {

                    $phone = str_replace($source, $replace, $client->phone_2);
                }

                $protocol->api_call_id = total_voice_call('55'.$phone);
                $protocol->save();

                if ($request->hasFile('diagnostic_test')) {
                    $response = $this->uploadS3(1, $request->diagnostic_test, $request);
                    if ($response['success']) {
                        $os->diagnostic_test = $response['url'];
                    } else {
                        return redirect()->back();
                    }
                }
                if ($request->hasFile('os_signature')) {
                    $response = $this->uploadS3(2, $request->os_signature, $request);
                    if ($response['success']) {
                        $os->os_signature = $response['url'];
                    } else {
                        return redirect()->back();
                    }
                }
                $os->description = $request->description_1;

                $message = new SacMsgProtocol;
                $message->message = nl2br($request->description_1);
                $message->authorized_id = $request->session()->get('sac_authorized_id');
                $message->sac_protocol_id = $os->sac_protocol_id;
                $message->save();

            } else {

                $hour = $request->hour == "" ? '12:00' : $request->hour;
                $os->expert_name = $request->expert_name;
                $os->expert_phone = $request->expert_phone;
                $os->visit_date = date('Y-m-d H:i:s', strtotime($request->date ." ". $hour));

                $models = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                    ->leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                    ->select('product_air.*', 'sac_model_os.serial_number as smp_serial_number')
                    ->where('sac_os_protocol.id', $os->id)
                    ->get();

                $message = new SacMsgProtocol;
                $message->message = nl2br($authorized->name ."\n<b>Atualizou o atendimento</b> \n\n <b>Técnico:</b> ". $request->expert_name ."\n <b>Telefones:</b> ". $authorized->phone_1 ." / ". $authorized->phone_2 ." \n <b>Modelo:</b> ". $models->implode('model', ', ') ." <br> <b>Data:</b> ".  date('d-m-Y H:i', strtotime($request->date ." ". $hour)));
                $message->is_system = 1;
                $message->sac_protocol_id = $os->sac_protocol_id;
                $message->save();
				
				$message = new SacMsgOs;
				$settings = App\Model\Settings::where('command', 'os_msg_interaction')->first();
                $message->message = $settings->value;
                $message->is_system = 0;
				$message->authorized_id = 1864;
                $message->sac_os_protocol_id = $os->id;
                $message->save();
            }

            $os->save();


            if ($protocol->r_code) {

                $user = Users::where('r_code', $protocol->r_code)->first();

                $pattern = array(
                    'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                    'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                    'template' => 'misc.Default',
                    'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                );

                NotifyUser('Protocolo: #'. $protocol->code, $user->r_code, 'fa-exclamation', 'text-info', 'Temos uma nova atualização desse protocolo, clique aqui para visualizar.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                SendMailJob::dispatch($pattern, $user->email);
            }

            $request->session()->put('success', "Você atualizou a ordem de serviço, o cliente e a Gree será notificados.");
            return redirect()->back();

        } else {

            $request->session()->put('error', "Infelizemente essa OS já foi cancelada ou já está paga.");
            return redirect()->back();
        }

    }

    public function protocolSchedule(Request $request) {

        $protocol = SacProtocol::where('sac_protocol.id', $request->id)
            ->where('sac_protocol.authorized_id', $request->session()->get('sac_authorized_id'))
            ->where('is_completed', 0)
            ->where('is_cancelled', 0)
            ->first();

        if ($protocol) {

            $os = SacOsProtocol::where('sac_protocol_id', $protocol->id)
                ->where('is_paid', 0)
                ->where('is_cancelled', 0)
                ->where('expert_name', null)
                ->where('authorized_id', $request->session()->get('sac_authorized_id'))
                ->first();

            if ($os) {

                $hour = $request->hour == "" ? '12:00' : $request->hour;
                $os->expert_name = $request->expert_name;
                $os->expert_phone = $request->expert_phone;
                $os->visit_date = date('Y-m-d H:i:s', strtotime($request->date ." ". $hour));
                $os->save();

                $authorized = SacAuthorized::find($protocol->authorized_id);

                $client = SacClient::find($protocol->client_id);

                $models = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                    ->leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                    ->select('product_air.*', 'sac_model_os.serial_number as smp_serial_number')
                    ->where('sac_os_protocol.id', $os->id)
                    ->get();

                if ($client->email) {

                    $pattern = array(
                        'c_name' => $client->name,
                        'a_name' => $authorized->name,
                        'e_name' => $request->expert_name,
                        'e_phone' => $request->expert_phone,
                        'models' => $models->implode('model', ', '),
                        'c_date' => date('d-m-Y H:i', strtotime($request->date ." ". $hour)),
                        'title' => 'ATENDIMENTO AGENDADO',
                        'description' => '',
                        'template' => 'sac.notifyClient',
                        'subject' => 'Gree: Protocolo atualizado: '. $protocol->code,
                    );

                    SendMailJob::dispatch($pattern, $client->email);
                }

                $message = new SacMsgProtocol;
                $message->message = nl2br($authorized->name ."\n <b>Agendou o atendimento</b> \n\n <b>Técnico:</b> ". $request->expert_name ."\n <b>Telefones:</b> ". $authorized->phone_1 ." / ". $authorized->phone_2 ."<br> <b>Modelo:</b> ". $models->implode('model', ', ') ." <br> <b>Data:</b> ".  date('d-m-Y H:i', strtotime($request->date ." ". $hour)));
                $message->is_system = 1;
                $message->sac_protocol_id = $protocol->id;
                $message->save();

                if ($protocol->r_code) {

                    $user = Users::where('r_code', $protocol->r_code)->first();

                    $pattern = array(
                        'title' => 'ATENDIMENTO AGENDADO',
                        'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                        'template' => 'misc.Default',
                        'subject' => 'Protocolo: '. $protocol->code .' foi agendado!',
                    );

                    NotifyUser('Protocolo: #'. $protocol->code, $user->r_code, 'fa-exclamation', 'text-info', 'Foi realizado o agendamento desse protocolo, clique aqui para visualizar.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                    SendMailJob::dispatch($pattern, $user->email);
                }

                $request->session()->put('success', 'Atendimento atualizado com sucesso! Veja as informações sobre em suas O.S');
                return redirect()->back();

            } else {

                $request->session()->put('error', 'O.S já foi concluída ou cancelada, Obrigado por se manifestar, qualquer dúvida entre em contato.');
                return redirect()->back();
            }

        } else {

            $os = SacOsProtocol::where('sac_protocol_id', $request->id)->where('is_paid', 0)->where('is_cancelled', 0)->where('authorized_id', $request->session()->get('sac_authorized_id'))->first();
            if ($os) {
                $os->is_cancelled = 1;
                $os->save();
            }
            $request->session()->put('error', 'Sua ordem de serviço foi encerrada, obrigado por prestar serviço para GREE.');
            return redirect()->back();
        }

    }


    public function forgotten(Request $request) {


        return view('gree_sac_authorized.forgottenPass');
    }

    public function forgotten_do(Request $request) {

        $authorized = SacAuthorized::where('identity', $request->identity)->first();

        if ($authorized) {

            if ($authorized->email) {
                $code = Str::random(10);
                $authorized->recovery = $code;
                $authorized->save();

                $pattern = array(
                    'title' => 'RECUPERAÇÃO DE SENHA',
                    'description' => nl2br("Para realizar a recuperação da sua senha, acessa o link abaixo e informe sua nova senha. \n \n <a href='". $request->root() ."/autorizada/recuperar/". $code ."'>". $request->root() ."/autorizada/recuperar/". $code ."</a>"),
                    'template' => 'misc.DefaultExternal',
                    'subject' => 'Gree - Recuperação de senha',
                );

                SendMailJob::dispatch($pattern, $authorized->email);

                $request->session()->put('success', 'Foi enviado um email para a recuperação da sua senha.');
                return redirect('/autorizada');
            } else {

                $request->session()->put('error', 'Você não tem um email cadastro, fale com o sac da gree.');
                return redirect('/autorizada');
            }

        } else {

            $request->session()->put('error', 'CNPJ não cadastrado em nossa base.');
            return redirect()->back();
        }


    }

    public function passwordRecovery(Request $request, $code) {

        $authorized = SacAuthorized::where('recovery', $code)->first();

        return view('gree_sac_authorized.passRecovery', [
            'authorized' => $authorized,
            'code' => $code,
        ]);
    }

    public function passwordRecovery_do(Request $request) {

        $authorized = SacAuthorized::where('recovery', $request->code)->where('recovery', '!=', null)->first();

        if ($authorized) {

            $authorized->password = Hash::make($request->password);
            $authorized->recovery = null;
            $authorized->save();

            $request->session()->put('success', 'Sua senha foi alterada com sucesso!');
            return redirect('/autorizada');

        } else {

            $request->session()->put('error', 'Ocorreu um erro inesperado.');
            return redirect()->back();
        }

    }
	
	public function support(Request $request){
        $faq_authorized = \App\Model\SacFaq::where('type', 1)->orderBy('id', 'DESC')->get();

        return view('gree_sac_authorized.panel.support', [
            'faq_authorized' => $faq_authorized,
        ]);

    }
	
	public function remittanceList(Request $request) {

        $authorized_id = $request->session()->get('sac_authorized_id');
        $authorized = SacAuthorized::find($authorized_id);

        $remittance = SacRemittancePart::where('authorized_id', $authorized_id)->orderBy('id', 'DESC');

        return view('gree_sac_authorized.panel.remittance_list', [
            'remittance' => $remittance->paginate(10),
            'authorized' => $authorized
        ]);
    }

    public function remittancePart(Request $request) {
        
        return view('gree_sac_authorized.panel.remittance_part');
    }

    public function remittancePart_do(Request $request) {

        $list_parts = json_decode($request->arr_list_parts, true);

        DB::beginTransaction();

        try {

            if($request->hasFile('remittance_note')) {

                $remittance = new SacRemittancePart;
				$last_id = Settings::where('command', 'last_os_id')->first();
				$seg = $last_id->value + 1;
				$remittance->code = 'W'. $seg;
				
				// update id
				$last_id->value = $seg;
				$last_id->save();
				
                $remittance_note = $this->uploadS3(1, $request->remittance_note, $request);
				$remittance_diagnostic = $this->uploadS3(2, $request->remittance_diagnostic, $request);
                $remittance->authorized_id = $request->session()->get('sac_authorized_id');

                if ($remittance_note['success']) {
                    $url_remittance_note = $remittance_note['url'];
                } else {
                    throw new \Exception('Não foi possível fazer upload da nota de remessa!'); 
                }
				
				if ($remittance_diagnostic['success']) {
                    $url_remittance_diagnostic = $remittance_diagnostic['url'];
                } else {
                    throw new \Exception('Não foi possível fazer upload do relatório técnico!'); 
                }

                if($request->hasFile('purchase_origin_note')) {

                    $purchase_origin_note = $this->uploadS3(1, $request->purchase_origin_note, $request);

                    if($purchase_origin_note['success']) {
                        $url_purchase_origin_note = $purchase_origin_note['url'];
                    } else {
                        throw new \Exception('Não foi possível fazer upload da Nota de origem de compra!'); 
                    }
                    $remittance->purchase_origin_note = $url_purchase_origin_note;
                }
				
				if($request->hasFile('photo_tag')) {

                    $photo_tag = $this->uploadS3(1, $request->photo_tag, $request);

                    if($photo_tag['success']) {
                        $url_photo_tag = $photo_tag['url'];
                    } else {
                        throw new \Exception('Não foi possível fazer upload da Nota de origem de compra!'); 
                    }
                    $remittance->photo_tag = $url_photo_tag;
                }

                $remittance->remittance_note = $url_remittance_note;
				$remittance->diagnostic_file = $url_remittance_diagnostic;
                $remittance->save();

                $authorized = $remittance->sac_authorized;

                $txt = "";
                $txt .= nl2br(
                    "<div style='text-align: left !important;>'> \n <b>CNPJ:</b> ". $authorized->identity 
                    ."<br> <b>Nome:</b> ". $authorized->name 
                    ."<br> <b>Email:</b> ". $authorized->email
                    ."<br> <b>Telefone:</b> ". $authorized->phone_1
                    ."<br> <b>Endereço:</b> ". $authorized->address
                    ."<p> Lista de peças: </p>"
                );

                if(count($list_parts) > 0) {
 
                    for ($i = 0; $i < count($list_parts); $i++) {

                        $part = new SacRemittanceParts;
                        $part->sac_remittance_part_id = $remittance->id;
                        $part->model = $list_parts[$i]['model'];
                        $part->part = $list_parts[$i]['part'];
                        $part->quantity = $list_parts[$i]['quantity'];
                        $part->description_order_part = $list_parts[$i]['description'];
                        $part->not_part = $list_parts[$i]['not_part'];
                        $part->save();

                        $txt .= nl2br(
                            " <b>Modelo do equipamento:</b> ". $list_parts[$i]['model_text']
                            ."<br> <b>Código/Nome da peça:</b> ". $list_parts[$i]['part_text']
                            ."<br> <b>Quantidade:</b> ". $list_parts[$i]['quantity']
                            ."<br> <b>Motivo de solicitação:</b> ". $list_parts[$i]['description']
                        );
    
                        $txt .= nl2br(
                            "\n <hr> \n"
                        );
                    }    

                } else {    
                    throw new \Exception('Você não adicionou nenhuma peça a sua solicitação!'); 
                }
            } else {
                throw new \Exception('Você não anexou a nota de remessa!'); 
            }

            DB::commit();

            $txt .= nl2br("</div>");

            $assist_tect = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                    ->select('users.*')
													->where('users.is_active', 1)
                                                    ->where('user_on_permissions.can_approv', 1)
                                                    ->where('user_on_permissions.perm_id', 16)
                                                    ->where('users.filter_line', 1)
                                                    ->get();
    
            if ($assist_tect) {

                foreach($assist_tect as $key) {

                    $pattern = array(
                        'title' => 'SOLICITAÇÃO DE REMESSA DE PEÇA(S)',
                        'description' => nl2br("Olá! Foi realizado os seguintes solicitações de remessa: ". $txt),
                        'template' => 'misc.Default',
                        'subject' => 'Nova solicitação de remessa de peça',
                    );
        
                    NotifyUser('Nova solicitação de remessa de peça', $key->r_code, 'fa-exclamation', 'text-info', 'Foi realizado uma nova solicitação de remessa de peça, clique aqui para visualizar.', $request->root() .'/sac/assistance/remittance/all');
                   // SendMailJob::dispatch($pattern, $key->email);
                }
            }
    
            if ($authorized->email) {
                $pattern = array(
                    'title' => 'SOLICITAÇÃO DE REMESSA DE PEÇA(S)',
                    'description' => nl2br("Olá! Abaixo suas informações de solicitações de remessa peça(s): ". $txt),
                    'template' => 'misc.DefaultExternal',
                    'subject' => 'Solicitação de remessa enviada com sucesso!',
                );
    
                SendMailJob::dispatch($pattern, $authorized->email);
            }

            $request->session()->put('success', 'Solicitação de remessa de peça realizada com sucesso!');
            return redirect('/autorizada/remessa/lista');

        } catch (\Exception $e) {

            DB::rollBack();
            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }    
    }    

    public function remittanceListPart(Request $request, $id) {

        $parts = SacRemittanceParts::with("product_air", "parts")->where('sac_remittance_part_id', $id);
        if(!$parts) {
            return response()->json([
                'success' => false,
                'message' => 'Peça não foi encontrado!'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'parts' => $parts->get(),
        ], 200);
    }

    public function remittancePrint(Request $request, $id) {

        $remittance = SacRemittancePart::with(['sac_authorized', 'sac_remittance_parts' => function($query) {
            $query->orderBy('is_approv', 'DESC');
        }])->find($id);

        return view('gree_sac_authorized.panel.remittance_print', [
            'id' => $id,
            'remittance' => $remittance,
            'remitance_parts' => $remittance->sac_remittance_parts,
            'authorized' => $remittance->sac_authorized
        ]);
    }
	
	public function comfirmRemittancePartFinish(Request $request) {

        try {
            $remittance = SacRemittancePart::find($request->id);
            $remittance->is_payment = 1;
            $remittance->is_payment_observation = $request->observation;
            $remittance->is_payment_authorized = 1;
            $remittance->save();
            return redirect()->back()->with('success', 'Finalização de solicitação realizada com sucesso');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}
