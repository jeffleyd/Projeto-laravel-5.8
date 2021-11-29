<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App;
use App\Model\Users;
use App\Model\SacRemittancePart;
use App\Model\SacRemittanceParts;
use App\Model\SacRemittanceAnalyze;
use App\Model\SacAuthorized;
use App\Model\SacOsProtocol;
use\App\Model\SacProtocol;
use\App\Model\SacPartProtocol;
use\App\Model\SacProtocolCosts;
use\App\Model\SacProtocolCostsHistoric;

use\App\Model\SacRemittancePartCosts;
use\App\Model\SacRemittancePartCostsHistoric;

use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Exports\DefaultExport;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Facades\Excel;


class TechnicalAssistanceController  extends Controller
{

    public function remittanceList(Request $request) {

        $remittance = SacRemittancePart::with(['sac_authorized', 'sac_remittance_parts' => function($query) {
            $query->where('is_approv', 0)
                  ->where('is_repprov', 0);
        }])->orderBy('id', 'DESC');

        $array_input = collect([
            'remittance_code',
            'authorized_id',
            'status',
            'start_date', 
            'end_date'
        ]);

        $array_input = putSession($request, $array_input, 'remitList_');
        $filter_session = getSessionFilters('remitList_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."remittance_code"){
                    $remittance->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."authorized_id"){

                    $remittance->where('authorized_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."status"){

                    if($value_filter == 99){
                        $remittance->where('is_cancelled', 1);
                    } else {
                        $remittance->where('status', $value_filter)->where('is_cancelled', 0);
                    }
                }
                if($name_filter == $filter_session[1]."start_date"){
                    $remittance->where('created_at', '>=', $value_filter);
                }
                if($name_filter == $filter_session[1]."end_date"){
                    $remittance->where('created_at', '<=', $value_filter);
                }
            }
        }

        if($request->export == 1) {

            $heading = array('Código', 'Autorizada', 'Nota Remessa', 'Nota Compra', 'Status', 'Rastreio', 'Custo Envio', 'Solicitado em', 'Atualizado em');
			$rows = array();

            foreach ($remittance->get() as $key) {
                $line = array();

                if ($key->is_cancelled == 0) {
                    if ($key->status == 1) {
                        $status = "Em análise";
                    }else if ($key->status == 2) {
                        $status = "Expedição";
                    } else if ($key->status == 3) {
                        $status ="Enviado";
                    } else if ($key->status == 4) {
                        $status = "Concluído";
                    }
                } else {
                    $status = 'Cancelado';
                }    

                $line[0] = $key->code;
                $line[1] = $key->sac_authorized->name;
                $line[2] = $key->remittance_note;
                $line[3] = $key->purchase_origin_note;
                $line[4] = $status;
                $line[5] = $key->track_code;
                $line[6] = 'R$ '.number_format($key->shipping_cost, 2,",",".");
                $line[7] = date('d/m/Y', strtotime($key->created_at));
                $line[8] = date('d/m/Y', strtotime($key->updated_at));
                
                array_push($rows, $line);
            }

            return Excel::download(new DefaultExport($heading, $rows), 'RemittanceListExport-'. date('Y-m-d') .'.xlsx');
        }

        return view('gree_i.assisttec.remittance_list', [
            'remittance' => $remittance->paginate(10)
        ]);
    }

    public function remittanceApprov(Request $request, $id) {

        $remittance = SacRemittancePart::with('sac_remittance_parts', 'sac_authorized')->find($id);

        return view('gree_i.assisttec.remittance_approv', [
            'id' => $id,
            'remittance' => $remittance,
            'remitance_parts' => $remittance->sac_remittance_parts
        ]);
    }    

    public function remittanceApprov_do(Request $request) {
	
		if($request->is_paid_info == 1) {
			$remittance_id = $request->remittance_id;
			$remittance = SacRemittancePart::find($remittance_id);
			$remittance->payment_info = $request->paid_info;
			$remittance->save();
		
			return redirect()->back()->with('success', 'Informações adicionais atualizadas com sucesso!');
		}

        try {
            if (count($request->check) > 0) {
					
				$remittance = SacRemittancePart::find($request->remittance_id);
			    $remittance->payment_info = $request->paid_info;
			    $remittance->save();
				
                foreach ($request->check as $index => $key) {

                    $item = SacRemittanceParts::find($key);
                    if($item) {

                        $item->is_approv = $request->is_approv == 1 ? 1 : 0;
                        $item->is_repprov = $request->is_approv == 2 ? 1 : 0; 
                        $item->rcode_is_approv = $request->session()->get('r_code'); 
                        $item->service_value = $request->service[$index];
                        $item->save();

                        $log_msg = $request->is_approv == 1 ? "APROVOU" : "REPROVOU";
                        LogSystem("Colaborador ". $log_msg ." solicitação de remessa P". $item->sac_remittance_part_id ." da peça ID: ". $item->id, $request->session()->get('r_code'));

                    } else {
                        throw new \Exception('solicitação de peça não encontrada, verifique!');
                    }
                }

                if($this->verifyApprovAll($request->remittance_id)) {

                    $request->session()->put('success', "Aprovação realizada com sucesso, peças enviadas para expedição!");
                    $request->session()->forget('partAlert'.$request->remittance_id.'');

                    return redirect('/sac/assistance/remittance/all');
                } else {

                    $ret_msg = $request->is_approv == 1 ? "APROVADAS" : "REPROVADAS";
                    $request->session()->put('success', "Peças foram ". $ret_msg ." com sucesso!");

                    $request->session()->put('partAlert'.$request->remittance_id.'', true);
                    return redirect()->back();
                }
                
            } else {
                throw new \Exception('Você precisa selecionar ao menos uma peça para realizar essa ação.');
            }  

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }   

    public function remittanceEdit(Request $request, $id) {

        $remittance = SacRemittancePart::with('sac_remittance_parts', 'sac_authorized')->find($id);

        return view('gree_i.assisttec.remittance_edit', [
            'id' => $id,
            'remittance' => $remittance,
            'remitance_parts' => $remittance->sac_remittance_parts
        ]);
    }

    public function remittanceEdit_do(Request $request) {

        $model = $request->model_id == null ? $request->modal_model : $request->model_id; 

        try {

            $part = new SacRemittanceParts;
            $part->sac_remittance_part_id = $request->remittance_id;
            $part->model = $request->type == 1 ? $request->model : $model;
            $part->part = $request->type == 1 ? $request->part : $request->modal_part;
            $part->quantity = $request->type == 1 ? $request->quantity : $request->modal_quantity;
            $part->description_order_part = $request->type == 1 ? $request->description : $request->modal_description;
            $part->not_part = $request->type == 1 ? 0 : 1;
            $part->save();

            $request->session()->put('success', 'Peça adicionada com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function remittancePartAjax(Request $request) {

        $part = SacRemittanceParts::with("product_air", "parts")->find($request->data_id);
        if(!$part) {
            return response()->json([
                'success' => false,
                'message' => 'Peça não foi encontrado!'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'part' => $part,
        ], 200);
    }

    public function remittancePartEdit(Request $request) {

        $part = SacRemittanceParts::find($request->parts_id);
        if(!$part) {
            $request->session()->put('error', 'Não foi possível atualizar, peça não encontrada!');
            return redirect()->back();
        }

        try {
            $part->model = $request->model;
            $part->part = $request->part;
            $part->quantity = $request->quantity;
            $part->description_order_part = $request->description;
            $part->not_part = $request->not_part;
            $part->save();

            $request->session()->put('success', 'Peça atualizada com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        } 
    }

    public function remittanceUpdate(Request $request) {

        $remittance = SacRemittancePart::find($request->id);
        if(!$remittance) {
            $request->session()->put('error', 'Remessa não encontrada!');
            return redirect()->back();
        }

        try {

            if($request->status == 99) {
                $remittance->is_cancelled = 1;    
            } else {
                $remittance->status = $request->status;
                $remittance->is_cancelled = 0;    
            }
            $remittance->track_code = $request->track_code;
            $remittance->shipping_cost = $request->shipping_cost;  
            $remittance->save();

            if($request->status == 99) {
                $authorized = SacAuthorized::find($remittance->authorized_id);
                if ($authorized->email) {
                    $pattern = array(
                        'title' => 'ATUALIZAÇÃO DE SOLICITAÇÃO DE REMESSA',
                        'description' => nl2br("<b>Solicitação de remessa de código (". $remittance->code .") foi CANCELADA</b>, 
                                                <br>Para mais detalhes entre em contato com assistência técnica:
                                                <br>Fone: 0800 055 6188 / Email: sac@gree-am.com.br
                                                <br>Cancelado em: ". date('d-m-Y', strtotime($remittance->updated_at)) ."</a>"),

                        'template' => 'misc.DefaultExternal',
                        'subject' => 'REMESSA DE PEÇA(S): '. $remittance->code .' CANCELADA!',
                    );
                    SendMailJob::dispatch($pattern, $authorized->email);
                }

                $msg_log = "CANCELOU";
            } else {
                $msg_log = "ATUALIZOU";
            }   

            LogSystem("Colaborador ".$msg_log." a solicitação de remessa: ". $remittance->code, $request->session()->get('r_code'));

            $request->session()->put('success', 'Remessa atualizada com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function remittancePrint(Request $request, $id) {

        $remittance = SacRemittancePart::with(['sac_authorized', 'sac_remittance_parts' => function($query) {
            $query->orderBy('is_approv', 'DESC');
        }])->find($id);

        return view('gree_i.assisttec.remittance_print', [
            'id' => $id,
            'remittance' => $remittance,
            'remitance_parts' => $remittance->sac_remittance_parts,
            'authorized' => $remittance->sac_authorized
        ]);
    }

    public function remittanceRequestPayment(Request $request, $id) {

        $remittance = SacRemittancePart::find($id);
        if(!$remittance) {
            $request->session()->put('error', 'Remessa não encontrada!');
            return redirect()->back();
        }

        try {
            $remittance->is_payment = 1;
            $remittance->save();

            $request->session()->put('success', 'Solicitação de pagamento realizado com sucesso!');
            return redirect()->back();
        
        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function remittanceListPayment(Request $request) {

        $remittance = SacRemittancePart::with(['sac_authorized', 'sac_remittance_parts'])
                        ->where('is_payment', 1)
                        ->where('is_paid', 0)
                        ->orderBy('updated_at', 'ASC');

        $array_input = collect([
            'code_remittance',
            'status'
        ]);
        
        $array_input = putSession($request, $array_input, 'remitt_');
        $filter_session = getSessionFilters('remitt_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code_remittance"){
                    $remittance->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status"){

                    if($value_filter == 2) {
                        $remittance->where('request_tec_approv', 1);
                    } else if($value_filter == 3) {
                        $remittance->where('payment_nf_request', 1);
                    } else {
                        $remittance->where('request_tec_approv', 0)->where('payment_nf_request', 0);
                    }
                }
            }
        }     
        
        return view('gree_i.assisttec.remittance_payment', [
            'remittance' => $remittance->paginate(10),
        ]);
    }

    public function remittanceListPaymentExport(Request $request) {

        $remittance = SacRemittancePart::with(['sac_authorized', 
                                                'sac_remittance_parts', 
                                                'sac_remittance_analyze.users', 
                                                'financy_r_payment',
                                                'parts'])->where('is_payment', 1)->orderBy('updated_at', 'DESC');

        $array_input = collect([
            'start_date', 
            'end_date',
            'is_paid',
            'code_remittance',
            'status'
        ]);

        $array_input = putSession($request, $array_input, 'remitList_');
        $filter_session = getSessionFilters('remitList_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."start_date"){
                    $remittance->where('created_at', '>=', $value_filter);
                }
                if($name_filter == $filter_session[1]."end_date"){
                    $remittance->where('created_at', '<=', $value_filter);
                }
                if($name_filter == $filter_session[1]."is_paid"){

                    $value = $value_filter == 1 ? 1 : 0;
                    $remittance->where('is_paid', $value);
                }
                if($name_filter == $filter_session[1]."code_remittance"){
                    $remittance->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status"){

                    if($value_filter == 2) {
                        $remittance->where('request_tec_approv', 1);
                    } else if($value_filter == 3) {
                        $remittance->where('payment_nf_request', 1);
                    } else {
                        $remittance->where('request_tec_approv', 0)->where('payment_nf_request', 0);
                    }
                }
            }
        }

        if($request->export == 1) {

            $heading = array('Status', 'Ordem de Serviço', 'Cod. Cred', 'Credenciado', 'Peça', 'Descrição', 'Valor', 'Data PGT', 'Banco', 'AG.', 'C/C');
            $rows = array();
            
            foreach ($remittance->get() as $key) {
                $line = array();
 
                $arr_desc = $key->parts->pluck('description')->toArray();

                $analyze = $key->sac_remittance_analyze->map(function ($item, $key) {
                    return ' "'. $item->users->short_name .' ('. $item->users->r_code .') - '. $item->description .'" ';
                });

                $line[0] = $key->is_paid == 1 ? 'PAGO' : 'NÃO PAGO';
                $line[1] = $key->code;
                $line[2] = $key->sac_authorized->code;
                $line[3] = $key->sac_authorized->name;
                $line[4] = implode(', ', $arr_desc);
                $line[5] = implode('| ', $analyze->toArray());
                $line[6] = "R$ ".number_format($key->sac_remittance_parts->sum('service_value'), 2,",",".");
                $line[7] = date('d/m/Y', strtotime($key->updated_at));
                $line[8] = $key->financy_r_payment ? $key->financy_r_payment->bank : $key->sac_authorized->bank;
                $line[9] = $key->financy_r_payment ? $key->financy_r_payment->agency : $key->sac_authorized->agency;
                $line[10] = $key->financy_r_payment ? $key->financy_r_payment->account : $key->sac_authorized->account;
                
                array_push($rows, $line);
            }
            return Excel::download(new DefaultExport($heading, $rows), 'RemittanceListExport-'. date('Y-m-d') .'.xlsx');
        }
    }

    public function sacWarrantyOsPaidExport(Request $request) {


        $os = SacOsProtocol::with('authorizedOs', 'sacProtocol.sacpartprotocol', 'sacProtocol.parts', 'sac_os_analyze.users', 'financy_r_payment')
                ->whereHas('sacProtocol', function($q) {
                    $q->where('is_completed', 1)
                      ->orWhere('is_refund', 1);
                })
                ->where('is_cancelled', 0)
                ->orderBy('sac_os_protocol.updated_at', 'ASC');

        $array_input = collect([
            'start_date',
            'end_date',
            'code',
            'os',
            'is_paid'
        ]);

        
        $array_input = putSession($request, $array_input, 'expos_');
        $filter_session = getSessionFilters('expos_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."start_date"){
                    $os->where('updated_at', '>=', $value_filter);
                }
                if($name_filter == $filter_session[1]."end_date"){
                    $os->where('updated_at', '<=', $value_filter.' 23:59:59');
                }
                if($name_filter == $filter_session[1]."code"){

                    $os->whereHas('sacProtocol', function($q) use ($value_filter){
                        $q->where('code', 'like', '%'.$value_filter.'%');
                    });
                }
                if($name_filter == $filter_session[1]."os"){
                    $os->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."is_paid"){

                    $value = $value_filter == 1 ? 1 : 0;
                    $os->where('is_paid', $value);
                }
            }
        }

        if($request->export == 1) {

            $heading = array('Status', 'Ordem de Serviço', 'Protocolo', 'Cod. Cred', 'Credenciado', 'Peça', 'Descrição', 'Valor', 'Data PGT', 'Banco', 'AG.', 'C/C', 'Criação protocolo');
            $rows = array();
            
            foreach ($os->get() as $key) {
                $line = array();

                $analyze = $key->sac_os_analyze->map(function ($item, $key) {
                    return ' "'. $item->users->short_name .' ('. $item->users->r_code .') - '. $item->description .'" ';
                });

                $line[0] = $key->is_paid == 1 ? 'PAGO' : 'NÃO PAGO';
                $line[1] = $key->code ? $key->code : '-';
                $line[2] = $key->sacProtocol->code;
                $line[3] = $key->authorizedOs->code;
                $line[4] = $key->authorizedOs->name;

                if($key->sacProtocol->sacpartprotocol->count()) {
                    $arr_desc = $key->sacProtocol->parts->pluck('description')->toArray();
                    $line[5] = implode(', ', $arr_desc);
                } else {
                    $line[5] = '-';
                }

                $line[6] = implode('| ', $analyze->toArray());
					
				$sum_total = $key->total + $key->total_extra;
                $line[7] = "R$ ".number_format($sum_total, 2,",",".");
				
                if($key->is_paid == 1) {
                    $line[8] = date('d/m/Y', strtotime($key->updated_at));
                } else {
                    $line[8] = '-';
                }
                
                $line[9] = $key->financy_r_payment ? $key->financy_r_payment->bank : $key->authorizedOs->bank;
                $line[10] = $key->financy_r_payment ? $key->financy_r_payment->agency : $key->authorizedOs->agency;
                $line[11] = $key->financy_r_payment ? $key->financy_r_payment->account : $key->authorizedOs->account;
                $line[12] = date('d/m/Y', strtotime($key->sacProtocol->created_at));
                
                array_push($rows, $line);
            }
            return Excel::download(new DefaultExport($heading, $rows), 'OSPaidExport-'. date('Y-m-d') .'.xlsx');
        }
    }

    public function remittancePayment(Request $request) {
        
        $remittance = SacRemittancePart::with('sac_authorized')->find($request->id);
        if($remittance) {

            if ($remittance->sac_authorized->agency 
                && $remittance->sac_authorized->account 
                && $remittance->sac_authorized->bank) {
                
                if($request->is_payment_request == 1) {

                    $nf_url = "";
                    if ($request->hasFile('payment_nf')) {
                        $response = uploadS3(1, $request->payment_nf, $request);
                        if ($response['success']) {
                            $nf_url = $response['url'];
                        } else {
                            return redirect()->back();
                        }
                    }

                    $remittance->total_payment = $request->total;
                    $remittance->is_payment_request = $request->is_payment_request;
                    $remittance->payment_number_nf = $request->payment_number_nf;
                    $remittance->payment_nf = $nf_url;
                    $remittance->payment_observation = $request->option;
                    $remittance->is_paid = 1;
                    $remittance->save();

                } else {

                    $remittance->total_payment = $request->total;
                    $remittance->is_payment_request = $request->is_payment_request;
                    $remittance->payment_observation = $request->option;
                    $remittance->is_paid = 1;
                    $remittance->save();
                }   

                $request->session()->put('success', 'Nova solicitação de pagamento realizada e enviada para análise.');
                return redirect()->back();

            } else {

                $request->session()->put('error', 'Autorizada não cadastrou dados bancários');
                return redirect()->back();
            } 

        } else {
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function remittancePaymentStatus(Request $request) {
        
        $remittance = SacRemittancePart::find($request->id);

        if ($remittance) {

            if ($request->status == 0) {

                $remittance->payment_nf_request = 0;
                $remittance->request_tec_approv = 0;
                $remittance->payment_info = $request->description;
                $remittance->save();

            } else if ($request->status == 1) {

                $remittance->payment_nf_request = 0;
                $remittance->request_tec_approv = 1;
                $remittance->payment_info = $request->description;
                $remittance->save();
            
            } else if ($request->status == 2) {

                $remittance->payment_nf_request = 1;
                $remittance->request_tec_approv = 1;
                $remittance->payment_info = $request->description;
                $remittance->save();
            }

            LogSystem("Colaborador atualizou o status da solicitação de pagamento de remessa identificado por #". $remittance->code, $request->session()->get('r_code'));
            $request->session()->put('success', 'Status atualizado com sucesso!');
            return redirect()->back();

        } else {
            $request->session()->put('error', 'Remessa não encontrada para reazalizar esta ação, verifique!');
            return redirect()->back();
        }
    }

    public function remittanceAnalyze(Request $request) {

        try {

            $analyze = new SacRemittanceAnalyze;
            $analyze->sac_remittance_part_id = $request->remittance_id;
            $analyze->r_code = $request->session()->get('r_code');
            $analyze->description = $request->description;
            $analyze->save();

            $request->session()->put('success', 'Análise feita com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function remittanceAnalyzeList(Request $request) {

        $analyze = SacRemittanceAnalyze::with('users')->where('sac_remittance_part_id', $request->remittance_id)->orderBy('id', 'DESC')->get(); 

        if(!$analyze) {
            return response()->json([
                'success' => false,
                'message' => 'Remessa não foi encontrada!'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'analyze' => $analyze,
        ], 200); 
    }    

    private function verifyApprovAll($id) {

        $parts = SacRemittanceParts::where('sac_remittance_part_id', $id)
                 ->where('is_approv', 0)
                 ->where('is_repprov', 0)
                 ->get();

        if(count($parts) == 0) {

            $remittance = SacRemittancePart::find($id);
            $remittance->status = 2;
            $remittance->save();

            return true;
        } else {
            return false;
        }
    }
	
	public function warrantyOsCostsAll(Request $request) {
		
		$costs = SacProtocol::with([
        'sacosprotocol' => function($query) {
            $query->where('is_cancelled', 0);
        } , 
        'sacpartprotocol' => function($query) {
            $query->whereHas('SacOsProtocol', function($q) {
                $q->where('is_cancelled', 0);
            })->where('is_repprov', 0)
              ->whereHas('modelParts');
        } , 
        'sacpartprotocol.sac_protocol_costs.sac_protocol_costs_historic', 
        'sacModelProtocol.sacProductAir']);

        $array_input = collect([
            'code_protocol',
            'code_os',
            'status',
            'start_date_submit',
            'end_date_submit',
			'start_date_os_submit',
            'end_date_os_submit'
        ]);
    
        $array_input = putSession($request, $array_input, 'cost_');
        $filter_session = getSessionFilters('cost_');

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code_protocol") {
                    $costs->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."code_os"){

                    $costs->whereHas('sacosprotocol', function($q) use ($value_filter) {
                        $q->where('code', 'like', '%'.$value_filter.'%');
                    });
                }
                if($name_filter == $filter_session[1]."status") {

                    $value = $value_filter == 99 ? 0 : 1;
                    $costs->where('is_completed', $value);
                }
				if($name_filter == $filter_session[1]."start_date_submit"){
                    $costs->WhereDoesntHave('sacpartprotocol.sac_protocol_costs', function($q) use ($value_filter) {
                          $q->whereDate('updated_at', '<', $value_filter);
                    })->whereHas('sacpartprotocol.sac_protocol_costs.sac_protocol_costs_historic');
                }
                if($name_filter == $filter_session[1]."end_date_submit"){
                    $costs->WhereDoesntHave('sacpartprotocol.sac_protocol_costs', function($q) use ($value_filter) {
                          $q->whereDate('updated_at', '>', $value_filter);
                    })->whereHas('sacpartprotocol.sac_protocol_costs.sac_protocol_costs_historic');
                }
				if($name_filter == $filter_session[1]."start_date_os_submit"){

					$costs->whereHas('sacosprotocol', function($q) use ($value_filter) {
						$q->where('created_at', '>=', $value_filter);
					});
				}
				if($name_filter == $filter_session[1]."end_date_os_submit"){

					$costs->whereHas('sacosprotocol', function($q) use ($value_filter) {
						$q->where('created_at', '<=', $value_filter);
					});
				}
            }
        }

        if($request->export == 1) {

           $heading = array('Status', 'Ordem de Serviço', 'Protocolo', 'Descrição', 'Descrição peça', 'Código da peça', 'Quantidade', 'Data envio Faturamento', 'Hora envio faturamento', 'Nº Ordem', 'Nº DI', 'NF', 'Data emissão NF', 'Hora emissão NF', 'Valor da Peça', 'Valor do Frete', 'Valor Frete Retorno', 'Mão de obra', 'Visita', 'Serviço Prestado', 'Valor Gás', 'TOTAL', 'Atualizado em', 'Faturamento');
            $rows = array();

            $costs->chunk(100, function($data) use (&$rows) {

                foreach ($data as $key) {
                    $line = array();    

                    $line[0] = $key->is_completed == 1 ? 'Concluído' : 'Andamento';
                    $line[2] = $key->code;
                    $line[3] = config('gree.type_sac_protocol')[$key->type];

                    foreach ($key->sacosprotocol as $os) {

                        $line[1] = $os->code;

                        if($key->sacpartprotocol->count() > 0) {

                            foreach ($key->sacpartprotocol as $part) {

                                $line[4] = $part->description_part;
                                $line[5] = $part->code_part;
                                $line[6] = $part->quantity;
                                $line[7] = $part->protocol_cost_field()->date_billing ? date('d/m/Y', strtotime($part->protocol_cost_field()->date_billing)) : '00/00/0000';
                                $line[8] = $part->protocol_cost_field()->hour_billing ? $part->protocol_cost_field()->hour_billing : '00:00';
                                $line[9] = $part->protocol_cost_field()->number_order;
                                $line[10] = $part->protocol_cost_field()->number_di;
                                $line[11] = $part->protocol_cost_field()->number_nf;
                                $line[12] = $part->protocol_cost_field()->date_emission_nf ? date('d/m/Y', strtotime($part->protocol_cost_field()->date_emission_nf)) : '00/00/0000';
                                $line[13] = $part->protocol_cost_field()->hour_emission_nf ? $part->protocol_cost_field()->hour_emission_nf : '00:00';
                                $line[14] = 'R$ '.number_format($part->protocol_cost_field()->value_part, 2, ',', '.');
                                $line[15] = 'R$ '.number_format($part->protocol_cost_field()->value_shipping, 2, ',', '.');
                                $line[16] = 'R$ '.number_format($part->protocol_cost_field()->value_shipping_return, 2, ',', '.');
                                $line[17] = 'R$ '.number_format($part->total, 2, ',', '.');
                                $line[18] = 'R$ '.number_format($key->value_visit_total, 2, ',', '.');
								$line[19] = 'R$ '.number_format($key->value_total_extra, 2, ',', '.');
								$line[20] = 'R$ '.number_format($key->value_total_gas, 2, ',', '.');

                                $sum_total = $part->protocol_cost_field()->value_part + $part->protocol_cost_field()->value_shipping + $part->total + $key->value_visit_total;
                                $line[21] = 'R$ '.number_format($sum_total, 2, ',', '.');
                                $line[22] = $key->date_updated ? date('d/m/Y', strtotime($key->date_updated)) : '00/00/0000';
                                $line[23] = $key->is_invoice == 1 ? 'Faturado' : 'Não Faturado';

                                array_push($rows, $line);
                            }   
                        }    
                        else {

                            $line[4] = '';
                            $line[5] = '';
                            $line[6] = '';
                            $line[7] = '00/00/0000';
                            $line[8] = '00:00';
                            $line[9] = '';
                            $line[10] = '';
                            $line[11] = '';
                            $line[12] = '00/00/0000';
                            $line[13] = '00:00';
                            $line[14] = 'R$ 0,00';
                            $line[15] = 'R$ 0,00';
                            $line[16] = 'R$ 0,00';
                            $line[17] = 'R$ 0,00';
                            $line[18] = 'R$ 0,00';
                            $line[19] = 'R$ 0,00';
							$line[20] = 'R$ 0,00';
							$line[21] = 'R$ 0,00';
                            $line[22] = '00/00/0000';
                            $line[23] = '';
                            
                            array_push($rows, $line);
                        }
                    }
                }
            });  

            return Excel::download(new DefaultExport($heading, $rows), 'OSExportCost-'. date('Y-m-d') .'.xlsx');
        }

        return view('gree_i.assisttec.warranty_os_costs', [
            'costs' => $costs->paginate(10)
        ]);
    }

	public function warrantyOsCostsEdit_do(Request $request) {

        try {

            if($request->cost_id == 0) {
                $protocol_cost = new SacProtocolCosts;
            } else {
                $protocol_cost = SacProtocolCosts::find($request->cost_id);
            }

            $protocol_cost->sac_protocol_id = $request->protocol_id;
            $protocol_cost->sac_part_protocol_id = $request->part_protocol_id;
            $protocol_cost->number_order = $request->number_order;
            $protocol_cost->number_di = $request->number_di;
            $protocol_cost->number_nf = $request->number_nf;

			$protocol_cost->date_emission_nf = $request->date_emission_nf;
            $protocol_cost->hour_emission_nf = $request->hour_emission_nf;
            $protocol_cost->date_billing = $request->date_billing;
            $protocol_cost->hour_billing = $request->hour_billing;

			$protocol_cost->value_part = str_replace(',','.', str_replace('.','', $request->value_part));
            $protocol_cost->value_shipping = str_replace(',','.', str_replace('.','', $request->value_shipping));
			$protocol_cost->value_shipping_return = str_replace(',','.', str_replace('.','', $request->value_shipping_return));			
            $protocol_cost->update_reason = $request->update_reason;
			$protocol_cost->observation = $request->observation;
			
            if($protocol_cost->save()) {
                $cost_historic = new SacProtocolCostsHistoric;
                $cost_historic->sac_protocol_costs_id = $protocol_cost->id;
                $cost_historic->r_code = $request->session()->get('r_code');
                $cost_historic->update_reason = $request->update_reason;
                $cost_historic->save();
            }
            

            $request->session()->put('success', 'Custo atualizado com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function warrantyRemittanceCostsAll(Request $request) {

        $costs = SacRemittancePart::with(['sac_authorized', 'sac_remittance_parts' => function($query) {
            $query->where('is_approv', 1)
                  ->where('is_repprov', 0);
        }, 'sac_remittance_parts.sac_remittance_part_costs.sac_remittance_part_costs_historic', 'sac_remittance_parts.parts'])
        ->where('is_cancelled', 0)
        ->orderBy('id', 'DESC');

        $array_input = collect([
            'code_remittance',
            'status',
            'start_date_submit',
            'end_date_submit',
			'begin_date_remit_submit',
            'end_date_remit_submit'
        ]);
            

        $array_input = putSession($request, $array_input, 'cost_');
        $filter_session = getSessionFilters('cost_');

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code_remittance") {
                    $costs->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status") {

                    if($value_filter == 5) {
                        $costs->where('is_paid', 1)
                              ->where('is_payment_request', 1);
                    }
                    else if($value_filter == 6) {
                        $costs->where('is_paid', 1)
                              ->where('is_payment_request', 0);
                    }
                    else {
                        $costs->where('status', $value_filter);
                    }

                }
                /*if($name_filter == $filter_session[1]."start_date_remit_submit"){

					$costs->where('created_at', '>=', $value_filter);
				}
				if($name_filter == $filter_session[1]."end_date_remit_submit"){
					$costs->where('created_at', '<=', $value_filter);
				}*/

                if($name_filter == $filter_session[1]."start_date_submit"){

                    $costs->whereHas('sac_remittance_parts')->whereExists(function($query) use ($value_filter)
                    {
                        $query->select(DB::raw(1))
                            ->from('sac_remittance_part_costs')
                            ->whereRaw('sac_remittance_part_costs.sac_remittance_part_id = sac_remittance_part.id')
                            ->whereRaw("date(updated_at) >= '".$value_filter."'")
                            ->OrderByRaw('updated_at desc');

                    });
                }
                if($name_filter == $filter_session[1]."end_date_submit"){

                    $costs->whereHas('sac_remittance_parts')->whereExists(function($query) use ($value_filter)
                    {
                        $query->select(DB::raw(1))
                            ->from('sac_remittance_part_costs')
                            ->whereRaw('sac_remittance_part_costs.sac_remittance_part_id = sac_remittance_part.id')
                            ->whereRaw("date(updated_at) <= '".$value_filter."'")
                            ->OrderByRaw('updated_at desc');
                    });
                }
            }
        }

        if($request->export == 1) {

            $heading = array('Status', 'Remessa', 'Descrição peça', 'Código da peça', 'Quantidade', 'Envio Faturamento', 'Nº Ordem', 'Nº DI', 'NF', 'Emissão NF', 'Valor da Peça', 'Valor do Frete', 'Valor do Serviço', 'TOTAL', 'Pagamento Serviço', 'Atualizado em', 'Faturamento');
            $rows = array();

            $costs->chunk(100, function($data) use (&$rows) {

                foreach ($data as $key) {
                    $line = array();    

                    if($key->is_paid == 1) {
                        if($key->is_payment_request == 1) {
                            $status = 'Pago';
                        } else {
                            $status = 'Concluído';
                        }
                    } else {
                        if ($key->is_cancelled == 0) {
                            if ($key->status == 1) {
                                $status = 'Em análise';
                            } elseif ($key->status == 2) {
                                $status = 'Expedição';
                            } elseif ($key->status == 3) {
                                $status = 'Enviado';
                            } elseif ($key->status == 4) {
                                $status = 'Concluído';
                            }
                        } else {
                            $status = 'Cancelado';
                        }
                    }

                    $line[0] = $status;
                    $line[1] = $key->code;
                    
                    foreach ($key->sac_remittance_parts as $part) {

                        $line[2] = $part->parts['description'] != null ? $part->parts['description'] : $part->part;
                        $line[3] = $part->parts['description'] != null ? (string)$part->parts['code'] : 'Solic. Manualmente';

                        $line[4] = $part->quantity;

                        if($part->sac_remittance_part_costs != null) {

                            $line[5] = $part->remittance_cost_field()->date_billing ? date('d/m/Y', strtotime($part->remittance_cost_field()->date_billing)) : '00/00/0000';
                            $line[6] = $part->remittance_cost_field()->number_order;
                            $line[7] = $part->remittance_cost_field()->number_di;
                            $line[8] = $part->remittance_cost_field()->number_nf;
                            $line[9] = $part->remittance_cost_field()->date_emission_nf ? date('d/m/Y', strtotime($part->remittance_cost_field()->date_emission_nf)) : '00/00/0000';

                            $line[10] = 'R$ '.number_format($part->remittance_cost_field()->value_part, 2, ',', '.');
                            $line[11] = 'R$ '.number_format($part->remittance_cost_field()->value_shipping, 2, ',', '.');
                            $line[12] = 'R$ '.number_format($part->service_value, 2, ',', '.');
                            $line[13] = 'R$ '.number_format($part->part_sum_total, 2, ',', '.');
							$line[14] = 'R$ '.number_format($key->total_payment, 2, ',', '.');
                            $line[15] = $key->date_updated ? date('d/m/Y', strtotime($key->date_updated)) : '00/00/0000';
							$line[16] = $key->is_invoice == 1 ? 'Faturado' : 'Não Faturado';

                            array_push($rows, $line);
                        } 
                        else {

                            $line[5] = '00/00/0000';
                            $line[6] = '';
                            $line[7] = '';
                            $line[8] = '';
                            $line[9] = '00/00/0000';
                            $line[10] = 'R$ 0,00';
                            $line[11] = 'R$ 0,00';
                            $line[12] = 'R$ 0,00';
                            $line[13] = 'R$ 0,00';
							$line[14] = 'R$ 0,00';
                            $line[15] = '00/00/0000';
							$line[16] = '';
                            
                            array_push($rows, $line);
                        }
                    }
                }
            });  

            return Excel::download(new DefaultExport($heading, $rows), 'OSExportCost-'. date('Y-m-d') .'.xlsx');
        }

        return view('gree_i.assisttec.warranty_remittance_costs', [
            'costs' => $costs->paginate(10)
        ]);
    }

    public function warrantyRemittanceCostsEdit_do(Request $request) {

        try {
            
            if($request->cost_id == 0) {
                $remittance_cost = new SacRemittancePartCosts;
            } else {
                $remittance_cost = SacRemittancePartCosts::find($request->cost_id);
            }

            $remittance_cost->sac_remittance_part_id = $request->remittance_id;
            $remittance_cost->sac_remittance_parts_id = $request->part_remittance_id;

            $remittance_cost->number_order = $request->number_order;
            $remittance_cost->number_di = $request->number_di;
            $remittance_cost->number_nf = $request->number_nf;
            
            $remittance_cost->date_emission_nf = $request->date_emission_nf;
			$remittance_cost->hour_emission_nf = $request->hour_emission_nf;
			$remittance_cost->date_billing = $request->date_billing;
			$remittance_cost->hour_billing = $request->hour_billing;
			
			$remittance_cost->value_part = str_replace(',','.', str_replace('.','', $request->value_part));
            $remittance_cost->value_shipping = str_replace(',','.', str_replace('.','', $request->value_shipping));
			$remittance_cost->value_shipping_return = str_replace(',','.', str_replace('.','', $request->value_shipping_return));
			
            $remittance_cost->update_reason = $request->update_reason;
			$remittance_cost->observation = $request->observation;
			
            if($remittance_cost->save()) {
                $cost_historic = new SacRemittancePartCostsHistoric;
                $cost_historic->sac_remittance_part_costs_id = $remittance_cost->id;
                $cost_historic->r_code = $request->session()->get('r_code');
                $cost_historic->update_reason = $request->update_reason;
                $cost_historic->save();
            }
            

            $request->session()->put('success', 'Custo de remessa atualizado com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }
	
	public function warrantyOsPartInvoice(Request $request) {

        try {

            $part = SacPartProtocol::find($request->id);
            if(!$part) 
                return redirect()->back()->with('error', 'Peça não encontrada');

            $part->is_invoice = 1;
            $part->save();

            return redirect()->back()->with('success', 'Faturamento confirmado com sucesso');
        } 
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function warrantyRemittancePartInvoice(Request $request) {

        try {

            $part = SacRemittanceParts::find($request->id);
            if(!$part) 
                return redirect()->back()->with('error', 'Peça não encontrada');

            $part->is_invoice = 1;
            $part->save();

            return redirect()->back()->with('success', 'Faturamento confirmado com sucesso');
        } 
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
	
	public function remittanceChangeStatus(Request $request, $id, $status) {
		
        try {
            $remittance = SacRemittancePart::find($id);
            $remittance->status = $status;
            $remittance->save();

            return redirect()->back()->with('success', 'Status atualizado com sucesso');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }  
    }
}
