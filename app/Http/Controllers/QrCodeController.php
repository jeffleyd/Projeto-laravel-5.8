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

use App\Model\Users;
use App\Model\Qrcode;
use App\Model\QrcodeProducts;
use App\Model\QrcodeAnalistAnalyze;
use App\Model\QrcodeMngAnalyze;
use App\Model\UserOnPermissions;


class QrCodeController extends Controller
{

    public function QrCodeNewRequest(Request $request) {

        $validator = Validator::make(
                    [
                    'full_name' => $request->full_name,
                    'identity' => $request->identity,
                    'phone_1' => $request->phone_1,
                    'email' => $request->email,
                    'nf_number' => $request->nf_number,
                    'nf_file' => $request->nf_file,
                    'models' => $request->json_data,
                    ],
                    [
                    'full_name' => 'required',
                    'identity' => 'required',
                    'phone_1' => 'required',
                    'email' => 'required',
                    'nf_number' => 'required',
                    'nf_file' => 'required',
                    'models' => 'required',
                    ]
        );

        if ($validator->fails()) { 

            return response()->json([
                'success' => false,
                'msg' => 'Preencha todos os campos corretamente! '
            ]);
        }

        $qrcode = new Qrcode;
        $qrcode->full_name = $request->full_name;
        $qrcode->name = 'Gree Exclusiva';
        $qrcode->identity = $request->identity;
        $qrcode->phone_1 = $request->phone_1;
        $qrcode->phone_2 = $request->phone_2;
        $qrcode->email = $request->email;
        $qrcode->nf_number = $request->nf_number;

        if ($request->hasFile('nf_file')) {
            $response = uploadS3(1, $request->nf_file, $request);
            if ($response['success']) {
                $qrcode->nf_file = $response['url'];
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'Não foi possível realizar upload da imagem. Tamanho do arquivo deve ser menor que 5mb. '
                ]);
            }
        }

        $qrcode->save();

        // SEND EMAIL FOR MANAGER
        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                ->select('users.*')
                ->where('user_on_permissions.can_approv', 1)
                ->where('user_on_permissions.grade', '!=', 99)
                ->where('user_on_permissions.perm_id', 19)
                ->get();

        foreach ($immediate as $key) {

            $pattern = array(
            'title' => 'PEDIDO DE APROVAÇÃO QRCODE: '. $qrcode->name,
            'description' => nl2br('Para realizar aprovação da solicitação realizada do QRCODE, acesse o link abaixo: <p><center><a href="'. $request->root() .'/qr_code/list/approv">'. $request->root() .'/qr_code/list/approv</a></center></p>'),
            'template' => 'misc.Default',
            'subject' => 'PEDIDO DE APROVAÇÃO QRCODE: '. $qrcode->name,
            );

            NotifyUser('PEDIDO DE APROVAÇÃO QRCODE', $key->r_code, 'fa-exclamation', 'text-info', 'Foi criado uma solicitação de aprovação de QRCODE, acesse essa página para aprovar ou reprovar a solicitação: ', $request->root() .'/qr_code/list/approv');
            SendMailJob::dispatch($pattern, $key->email);
        }

        // code
        $addcode = Qrcode::find($qrcode->id);
        $addcode->code = "QR".$qrcode->id;
        $addcode->save();

        // JSON
        $raw_payload = $request->input('json_data');
        $payload = json_decode($raw_payload, true);

        if (isset($payload)) {
            foreach ($payload as $key) {

                $add = new QrcodeProducts;
                $add->product_id = $key['product_id'];
                $add->serial_number = $key['serial'];
                $add->address = $key['address'];
                $add->complement = $key['complement'];
                $add->qrcode_id = $qrcode->id;
                $add->save();

            }
        }

        return response()->json([
            'success' => true
        ]);


    }

    public function QrCodeEdit_do(Request $request) {

        $id=$request->id;

        $qrcode = Qrcode::find($id);

        if($qrcode){
            $qrcode->full_name = $request->full_name;
            $qrcode->name = "Gree Exclusivo";
            $qrcode->identity = $request->identity;
            $qrcode->phone_1 = $request->phone_1;
            $qrcode->phone_2 = $request->phone_2;
            $qrcode->buy_date = $request->buy_date;            
            $qrcode->email = $request->email;
            $qrcode->total = $request->total;
            $qrcode->nf_number = $request->nf_number;

            if ($request->hasFile('nf_file')) {
                $response = uploadS3(1, $request->nf_file, $request);
                if ($response['success']) {
                    $qrcode->nf_file = $response['url'];
                } else {
                    return redirect()->back();
                }
            }

            $qrcode->protocol_code = $request->protocol_code;

            $qrcode->save();
        }else{
            $request->session()->put('error', 'QR Code não encontrado');
        }


        // JSON
        $raw_payload = $request->input('json_data');
        $payload = json_decode($raw_payload, true);
        
        $products_to_insert = collect($payload)->pluck('id');

        if (!empty($products_to_insert)) {

            $qr_products = QrcodeProducts::where('qrcode_id', $id);
            $products = $qr_products->pluck('id');
            
            //Verifica se existe usuarios para serem notificados
            if (!empty($products)) {

                $prods_to_delete = $products->diff($products_to_insert);
                $products_to_insert = $products_to_insert->diff(collect($products));

                $qr_products->whereIn('id', $prods_to_delete)->delete();
            }

            if (isset($payload)) {
                foreach ($payload as $key) {
                    
                    if( $key['id'] ==0 ){
                        $add = new QrcodeProducts;
                    }else{
                        $add = QrcodeProducts::where('id',$key['id'])->first();
                    }

                    $add->product_id = $key['product_id'];
                    $add->serial_number = $key['serial_number'];
                    $add->address = $key['address'];
                    $add->complement = $key['complement'];
                    $add->qrcode_id = $qrcode->id;
                    $add->save();


                }
            }

        } 
        /* */

        
        return redirect()->back();


    } 

    public function QrCodeAnalyze(Request $request) {

        $qrcode = Qrcode::find($request->id);
        $permission = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))->where('perm_id', 19)->first();
        
        if ($qrcode) {

            if ($request->status == 1) {
                if ($permission->can_approv == 1 and $permission->grade == 99) {
                    if (QrcodeAnalistAnalyze::where('qrcode_id', $request->id)->count() == 0) {
                        $request->session()->put('error', "O analista ainda não realizou análise desse pedido.");
                        return redirect()->back();
                    }

                    $qrcode->mng_approv = 1;
                    $qrcode->date_approv = date('Y-m-d H:i:s');
                    $qrcode->save();

                    $analyzer = new QrcodeMngAnalyze;
                    $analyzer->qrcode_id = $request->id;
                    $analyzer->r_code = $request->session()->get('r_code');
                    $analyzer->is_approv = 1;
                    $analyzer->description = $request->description;
                    $analyzer->save();

                    // SEND SMS FOR CONSUMER
                    $source = array('(', ')', ' ', '-');
                    $replace = array('', '', '', '');

                    $phone = "";
                    if ($qrcode->phone_1) {
                            $phone = '55'. str_replace($source, $replace, $qrcode->phone_1); 

                    } else {
                        
                            $phone = '55'. str_replace($source, $replace, $qrcode->phone_2);
                    }

                    total_voice_sms(trim($phone), 'Olá, '. $qrcode->full_name .'. Seu cadastro na campanha '. $qrcode->name .' foi aprovado, voce recebera mais informacoes no email.');


                    // SEND EMAIL FOR CONSUMER
                    if ($qrcode->email) {

                        $pattern = array(
                            'title' => 'GREE EXCLUSIVA: APROVADO',
                            'description' => '',
                            'qrcode' => $qrcode,
                            'model' => 'GCC300ASND',
                            'template' => 'qrcode.ge03092020',
                            'subject' => 'GREE EXCLUSIVA: APROVADO',
                        );
            
                        SendMailJob::dispatch($pattern, $qrcode->email);
                    }

                } else {

                    $qrcode->analist_approv = 1;
                    $qrcode->save();

                    $analyzer = new QrcodeAnalistAnalyze;
                    $analyzer->qrcode_id = $request->id;
                    $analyzer->r_code = $request->session()->get('r_code');
                    $analyzer->is_approv = 1;
                    $analyzer->description = $request->description;
                    $analyzer->save();

                    // SEND EMAIL FOR MANAGER
                    $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                ->select('users.*')
                                                ->where('user_on_permissions.can_approv', 1)
                                                ->where('user_on_permissions.grade', 99)
                                                ->where('user_on_permissions.perm_id', 19)
                                                ->get();

                    foreach ($immediate as $key) {
                        
                        $pattern = array(
                            'title' => 'PEDIDO DE APROVAÇÃO QRCODE: '. $qrcode->name,
                            'description' => nl2br('Para realizar aprovação da solicitação realizada do QRCODE, acesse o link abaixo: <p><center><a href="'. $request->root() .'/qr_code/list/approv">'. $request->root() .'/qr_code/list/approv</a></center></p>'),
                            'template' => 'misc.Default',
                            'subject' => 'PEDIDO DE APROVAÇÃO QRCODE: '. $qrcode->name,
                        );
            
                        NotifyUser('PEDIDO DE APROVAÇÃO QRCODE', $key->r_code, 'fa-exclamation', 'text-info', 'Foi criado uma solicitação de aprovação de QRCODE, acesse essa página para aprovar ou reprovar a solicitação: ', $request->root() .'/qr_code/list/approv');
                        SendMailJob::dispatch($pattern, $key->email);
                    }

                }

                $request->session()->put('success', 'Foi aprovado com sucesso!');
                return redirect()->back();
            } else {
                if ($permission->can_approv == 1 and $permission->grade == 99) {
                    if (QrcodeAnalistAnalyze::where('qrcode_id', $request->id)->count() == 0) {
                        $request->session()->put('error', "O analista ainda não realizou análise desse pedido.");
                        return redirect()->back();
                    }

                    $qrcode->mng_reprov = 1;
                    $qrcode->save();

                    $analyzer = new QrcodeMngAnalyze;
                    $analyzer->qrcode_id = $request->id;
                    $analyzer->r_code = $request->session()->get('r_code');
                    $analyzer->is_reprov = 1;
                    $analyzer->description = $request->description;
                    $analyzer->save();

                    // SEND SMS FOR CONSUMER
                    $source = array('(', ')', ' ', '-');
                    $replace = array('', '', '', '');

                    $phone = "";
                    if ($qrcode->phone_1) {
                            $phone = '55'. str_replace($source, $replace, $qrcode->phone_1); 

                    } else {
                        
                            $phone = '55'. str_replace($source, $replace, $qrcode->phone_2);
                    }

                    total_voice_sms(trim($phone), 'Olá, '. $qrcode->full_name .'. Seu cadastro na campanha '. $qrcode->name .' foi reprovada, entre em contato conosco: '. getConfig("sac_number"));

                } else {

                    $qrcode->analist_reprov = 1;
                    $qrcode->save();

                    $analyzer = new QrcodeAnalistAnalyze;
                    $analyzer->qrcode_id = $request->id;
                    $analyzer->r_code = $request->session()->get('r_code');
                    $analyzer->is_reprov = 1;
                    $analyzer->description = $request->description;
                    $analyzer->save();

                    // SEND SMS FOR CONSUMER
                    $source = array('(', ')', ' ', '-');
                    $replace = array('', '', '', '');

                    $phone = "";
                    if ($qrcode->phone_1) {
                            $phone = '55'. str_replace($source, $replace, $qrcode->phone_1); 

                    } else {
                        
                            $phone = '55'. str_replace($source, $replace, $qrcode->phone_2);
                    }

                    total_voice_sms(trim($phone), 'Olá, '. $qrcode->full_name .'. Seu cadastro na campanha '. $qrcode->name .' foi reprovada, entre em contato conosco: '. getConfig("sac_number"));
                    
                }

                $request->session()->put('success', 'Foi reprovado com sucesso!');
                return redirect()->back();

            }

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }


    }

    public function QrCodeAll(Request $request) { 

        $qrcode = Qrcode::leftjoin('sac_protocol', 'qrcode.protocol_code', '=', 'sac_protocol.code')
                    ->select('qrcode.*', 'sac_protocol.id as sac_protocol_id');

        // Solicitações aprovadas e sem agendamento
        $approved_not_apm = Qrcode::where('analist_approv', 1)->where('mng_approv', 1)->where('protocol_code', NULL)->count();

        // Solicitações que faltam aprovar
        $not_approved = Qrcode::where('mng_approv', 0)
                                ->where('mng_reprov', 0)
                                ->orWhere(function ($query) {
                                    $query->where('analist_reprov', 0)
                                                ->where('analist_approv', 0);
                                })
                                ->count();

        // Solicitações com agendamento, mas sem técnico
        $request_without_expert = Qrcode::leftjoin('sac_protocol', 'qrcode.protocol_code', '=', 'sac_protocol.code')
                            ->where('qrcode.protocol_code', '!=', NULL)
                            ->where('sac_protocol.authorized_id', NULL)
                            ->where('sac_protocol.is_cancelled', 0)
                            ->where('sac_protocol.is_completed', 0)
                            ->where('sac_protocol.is_refund', 0)
                            ->count();

        // Solicitações com agendamento, mas sem técnico restando 5 dias a partir da coluna date_approv (20 dias).
        $request_without_expert_left_5 = Qrcode::leftjoin('sac_protocol', 'qrcode.protocol_code', '=', 'sac_protocol.code')
                            ->where('qrcode.protocol_code', '!=', NULL)
                            ->where('qrcode.date_approv', '<=', date('Y-m-d', strtotime(' - 15 day')))
                            ->where('sac_protocol.authorized_id', NULL)
                            ->where('sac_protocol.is_cancelled', 0)
                            ->where('sac_protocol.is_completed', 0)
                            ->where('sac_protocol.is_refund', 0)
                            ->count();

        $array_input = collect([
            'approved_not_apm',
            'not_approved',
            'request_without_expert',
            'request_without_expert_left_5',
            'code',
            'identity',
            'start_date',
            'end_date',
            'status',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();
        
        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
            
                if($nome_filtro == $filtros_sessao[1]."approved_not_apm"){
                    $qrcode->where('analist_approv', 1)
                            ->where('mng_approv', 1)
                            ->where('protocol_code', NULL);

                }

                if($nome_filtro == $filtros_sessao[1]."not_approved"){
                    $qrcode->where('mng_approv', 0)
                            ->where('mng_reprov', 0)
                            ->orWhere(function ($query) {
                                $query->where('analist_reprov', 0)
                                            ->where('analist_approv', 0);
                            });

                }

                if($nome_filtro == $filtros_sessao[1]."request_without_expert"){
                    $qrcode->where('qrcode.protocol_code', '!=', NULL)
                            ->where('sac_protocol.authorized_id', NULL)
                            ->where('sac_protocol.is_cancelled', 0)
                            ->where('sac_protocol.is_completed', 0)
                            ->where('sac_protocol.is_refund', 0);

                }

                if($nome_filtro == $filtros_sessao[1]."request_without_expert_left_5"){
                    $qrcode->where('qrcode.protocol_code', '!=', NULL)
                            ->where('qrcode.date_approv', '<=', date('Y-m-d', strtotime(' - 15 day')))
                            ->where('sac_protocol.authorized_id', NULL)
                            ->where('sac_protocol.is_cancelled', 0)
                            ->where('sac_protocol.is_completed', 0)
                            ->where('sac_protocol.is_refund', 0);

                }

                if($nome_filtro == $filtros_sessao[1]."code"){
                    $qrcode->where('qrcode.code', 'like', '%'. $valor_filtro .'%');
                }

                if($nome_filtro == $filtros_sessao[1]."identity"){
                    $qrcode->where('qrcode.identity', 'like', '%'. $valor_filtro .'%');
                }
                
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $qrcode->where('qrcode.created_at', '>=', "$valor_filtro");
                }

                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $qrcode->where('qrcode.created_at', '<=', "$valor_filtro");
                }

                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1)
                    $qrcode->where('qrcode.analist_approv', 0)
                            ->where('qrcode.analist_reprov', 0)
                            ->where('qrcode.mng_approv', 0)
                            ->where('qrcode.mng_reprov', 0);
                    else if ($valor_filtro == 2)
                    $qrcode->where('qrcode.analist_approv', 1)
                            ->where('qrcode.analist_reprov', 0)
                            ->where('qrcode.mng_approv', 1)
                            ->where('qrcode.mng_reprov', 0);
                    else if ($valor_filtro == 3)
                    $qrcode->where('qrcode.analist_reprov', 1)
                            ->orWhere(function ($query) {
                                $query->where('qrcode.mng_reprov', 1);
                            });
                }

            }
        }

        $qrcode = $qrcode->with('products')->orderBy('created_at', 'DESC');

        return view('gree_i.qrcode.qr_code_all', [
            'approved_not_apm' => $approved_not_apm,
            'not_approved' => $not_approved,
            'request_without_expert' => $request_without_expert,
            'request_without_expert_left_5' => $request_without_expert_left_5,
            'qrcode' => $qrcode->paginate(10),

        ]);

    }

    public function QrCodeApprov(Request $request) {

        $permission = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))->where('perm_id', 19)->first();

        if ($permission->grade == 99 and $permission->can_approv == 1) {
            $qrcode = Qrcode::where('analist_approv', 1)
                                    ->where('analist_reprov', 0)
                                    ->where('mng_approv', 0)
                                    ->where('mng_reprov', 0)
                                    ->orderBy('id', 'DESC');

        } else {
            $qrcode = Qrcode::where('analist_approv', 0)
                                    ->where('analist_reprov', 0)
                                    ->where('mng_approv', 0)
                                    ->where('mng_reprov', 0)
                                    ->orderBy('id', 'DESC');
        }

        $array_input = collect([
            'code',
            'identity',
            'start_date',
            'end_date',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
            
                if($nome_filtro == $filtros_sessao[1]."approved_not_apm"){
                    $qrcode->where('analist_approv', 1)
                            ->where('mng_approv', 1)
                            ->where('protocol_code', NULL);

                }

                if($nome_filtro == $filtros_sessao[1]."not_approved"){
                    $qrcode->where('mng_approv', 0)
                            ->where('mng_reprov', 0)
                            ->orWhere(function ($query) {
                                $query->where('analist_reprov', 0)
                                            ->where('analist_approv', 0);
                            });

                }

                if($nome_filtro == $filtros_sessao[1]."request_without_expert"){
                    $qrcode->leftjoin('sac_protocol', 'qrcode.protocol_code', '=', 'sac_protocol.code')
                            ->where('qrcode.protocol_code', '!=', NULL)
                            ->where('sac_protocol.authorized_id', NULL)
                            ->where('sac_protocol.is_cancelled', 0)
                            ->where('sac_protocol.is_completed', 0)
                            ->where('sac_protocol.is_refund', 0);

                }

                if($nome_filtro == $filtros_sessao[1]."request_without_expert_left_5"){
                    $qrcode->leftjoin('sac_protocol', 'qrcode.protocol_code', '=', 'sac_protocol.code')
                            ->where('qrcode.protocol_code', '!=', NULL)
                            ->where('qrcode.date_approv', '<=', date('Y-m-d', strtotime(' - 15 day')))
                            ->where('sac_protocol.authorized_id', NULL)
                            ->where('sac_protocol.is_cancelled', 0)
                            ->where('sac_protocol.is_completed', 0)
                            ->where('sac_protocol.is_refund', 0);

                }

                if($nome_filtro == $filtros_sessao[1]."code"){
                    $qrcode->where('qrcode.code', 'like', '%'. $valor_filtro .'%');
                }

                if($nome_filtro == $filtros_sessao[1]."identity"){
                    $qrcode->where('qrcode.identity', 'like', '%'. $valor_filtro .'%');
                }
                
                if($nome_filtro == $filtros_sessao[1]."start_date"){

                    $old_date = str_replace("/", "-", $valor_filtro);
                    $date = date('Y-m-d', strtotime($old_date));
                    
                    $qrcode->where('qrcode.created_at', '>=', "$valor_filtro");
                }

                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $old_date = str_replace("/", "-", $valor_filtro);
                    $date = date('Y-m-d', strtotime($old_date));

                    $qrcode->where('qrcode.created_at', '<=', "$valor_filtro");
                }

            }
        }

        $qrcode = $qrcode->with('products')->orderBy('created_at', 'DESC');

        return view('gree_i.qrcode.qr_code_approv', [
            'qrcode' => $qrcode->paginate(10),
        ]);

    }

    public function listarSolicitacoes(Request $request) {

        $qrcode = Qrcode::
        where('analist_approv', 0)
        ->where('analist_reprov', 0)
        ->where('mng_approv', 0)
        ->where('mng_reprov', 0);
        

     
        
        $array_input = collect([
            'qr_code',
            '​full_name',
            'identity',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
            
                if($nome_filtro == $filtros_sessao[1]."qr_code"){
                    $qrcode->where('code', $valor_filtro);
                }

                if($nome_filtro == $filtros_sessao[1]."​full_name"){
                    $qrcode->where('full_name', 'like', '%'. $valor_filtro .'%');
                }
                
                if($nome_filtro == $filtros_sessao[1]."identity"){
                    $qrcode->where('identity', 'like', "$valor_filtro");
                }

            }
        }

        $qrcode = $qrcode->with('products')->orderBy('created_at', 'DESC');

        
        
        return view('gree_i.qr_code.qr_code_list', [
            'qrcode' => $qrcode->paginate(10),
        ]);
    }
    
    public function aprovarSolicitacao(Request $request, $id) {
        return view('gree_i.survey.survey_edit');
    }
    public function reprovarSolicitacao(Request $request, $id) {
        return view('gree_i.survey.survey_edit');
    }

    public function aprovarSolicitacoes(Request $request, $id) {
    
        return view('gree_i.survey.survey_edit');
    }

    public function mapaGeral(Request $request, $id) {
        return view('gree_i.survey.survey_edit');
    }

}