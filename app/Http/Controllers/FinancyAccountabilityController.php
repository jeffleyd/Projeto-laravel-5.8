<?php

namespace App\Http\Controllers;

use App\Model\UserOnPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManager;
use Log;
use Hash;
use App;

use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Jobs\SendMailAttachJob;

use App\Model\Users;
use App\Model\UserDebtors;
use App\Model\LogAccess;

use App\Model\UserFinancy;
use App\Model\FinancyLending;
use App\Model\FinancyLendingAttach;
use App\Model\FinancyLendingFnyAnalyze;
use App\Model\FinancyLendingMngAnalyze;
use App\Model\FinancyLendingPresAnalyze;
use App\Model\FinancyRPayment;
use App\Model\FinancyRPaymentNf;
use App\Model\FinancyRPaymentAttach;

use App\Model\FinancyAccountability;
use App\Model\FinancyAccountabilityItem;

use App\Model\FinancyUsersDebtors;
use App\Model\FinancyAccountabilityManualEntry;
use App\Model\FinancyAccountabilityAttach;
use App\Model\FinancyAccountabilityObservationHistory;
use App\Model\FinancyAccountabilityReceiverHistory;


use App\Model\FinancyRPaymentMngAnalyze;
use App\Model\FinancyRPaymentFnyAnalyze;
use App\Model\FinancyRPaymentPresAnalyze;

use App\Exports\PaymentExport;
use App\Exports\DefaultExport;

use App\Helpers\RulesFinancyLending;


class FinancyAccountabilityController extends Controller
{

    /** Ajax Functions */
    public function ajaxViewEmprestimosPendentes(Request $request) {

        $r_code = $request->r_code;
        if(empty($r_code)){
            $r_code = $request->session()->get('r_code');
        }

        $lending_pendings = FinancyLending::pendings($r_code)
            ->orderBy('id', 'DESC')->paginate(5);

        return view('gree_i.accountability.ajax.lending_pendings', [
            'lending_pendings' => $lending_pendings,
            'html_render' => '#ajax-table'
        ]);
    }

    public function ajaxViewHistoricoEmprestimo(Request $request, $id) {

        $show_actions=true;
        if($request->has('show_actions')){
            //converte a string em boleana
            $show_actions = filter_var($request->show_actions, FILTER_VALIDATE_BOOLEAN);
        }

        $accountability = FinancyLending::where('id',$id)
            ->with(['prestacao_conta.pagamento_prestacao_conta','prestacao_conta_manual.attach'])
            ->orderBy('id', 'DESC')->first();

        return view('gree_i.accountability.ajax.lending_history', [
            'lending' => $accountability,
            'show_actions' => $show_actions,
        ]);
    }

    public function ajaxViewHistoricoEmprestimosUsuario(Request $request, $r_code) {

        $filter_all = true;


        $array_input = collect([
            'f_status_'.$r_code,
            'f_code_'.$r_code,
        ]);
        $array_input = putSession($request, $array_input,"ajax_filter_");

        $lendings = FinancyLending::where('r_code',$r_code)
            ->orderBy('id', 'DESC');

        $filtros_sessao = getSessionFilters("ajax_filter_");

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1].'f_status_'.$r_code){
                    $filter_all = false;
                    if($valor_filtro == 1){
                        $lendings->where('is_paid',1)
                            ->where('is_accountability_paid',0);
                    }
                    if($valor_filtro == 2){
                        $lendings->where('is_paid',1)
                            ->where('is_accountability_paid',1);
                    }
                    if($valor_filtro == 3){
                        $lendings->where('is_paid',1);
                    }
                }
                if($nome_filtro == $filtros_sessao[1]."f_code_".$r_code){
                    $lendings->where('code', 'like', '%'. $valor_filtro .'%');
                }
            }
        }

        if($filter_all){
            $lendings->where('is_paid',1)
                ->where('is_accountability_paid',0);
        }

        if ($request->input('f_export_'.$r_code) == 1) {

            $rows = array();

            $lendings = $lendings->get();
            $data_excel = $this->getRowsLendigsToExportExcel($lendings);
            $rows = $data_excel['rows'];
            $heading = $data_excel['heading'];

            return Excel::download(new DefaultExport($heading, $rows), 'UsersDebtorsExport_'.$r_code.'-'. date('Y-m-d') .'.xlsx');
        }else{
            $paginate = $lendings->paginate(5);
            $paginate->appends($request->except('page'));
            $paginate->setPath('/financy/accountability/ajax/lendings/history/user/'.$r_code);

            return view('gree_i.accountability.ajax.user_lendings_history', [
                'lendings' => $paginate,
                'html_render' => '#ajax-table-lendings_'.$r_code,
            ]);

        }

    }

    /** Show/Edit Functions */
    public function editPrestacaoContas(Request $request, $id) {

        $a_bank = UserFinancy::where('r_code', $request->session()->get('r_code'))->first();
        $show_actions=false;
        if ($id == 0) {

            $userall = "";
            $accountability = (object)[
                'description'=>'',
                'lending_request_id'=>0,
                'lending'=>null,
                'itens'=>null,
            ];

            $id = 0;
            $code = '';
            $itens = collect();
            $receiver = '';
            $total_item = 0;
            $total_lending = 0;
            $total_pending = 0;
            $total_amount = 0;
            $has_analyze = 0;
            $has_approv_or_repprov = 0;
            $is_financy = 0;
            $has_suspended = 0;
            $r_payment = '';

        } else {

            $accountability = FinancyAccountability::with(['lending.prestacao_conta'=>function($query){
                $query->notAnalyze();
            },'itens','itens.attach'])->find($id);

            if ($accountability) {
                $id = $accountability->id;
                $code = $accountability->code;
                $has_analyze = $accountability->has_analyze;
                $has_approv_or_repprov = ($accountability->status->id>=2 and $accountability->status->id<=4) ? 1 : 0;
                $has_suspended = 0;
                $r_payment = FinancyRPayment::find($accountability->payment_request_id);

                $is_financy = 0;
                $is_financy = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))
                        ->where('perm_id', 18)
                        ->first();
			
				if ($is_financy)
					$is_financy = 1;

            } else {
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }


        }

        $userall = Users::all();

        return view('gree_i.accountability.accountability_edit', [
            'a_bank' => $a_bank,
            'userall' => $userall,
            'r_payment' => $r_payment,
            'accountability' => $accountability,
            'is_financy' => $is_financy,
            'has_approv_or_repprov' => $has_approv_or_repprov,
            'has_analyze' => $has_analyze,
            'has_suspended' => $has_suspended,
            'id' => $id,
            'code' => $code,
            'show_actions' => $show_actions,
        ]);

    }
	
	private function quantationBRxUSD($currency, $date, $day = 0) {

        if ($currency == 'USD') {

            $get_cotattion = json_decode(file_get_contents('https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarDia(dataCotacao=@dataCotacao)?@dataCotacao=%27'.date('m-d-Y', strtotime('- '.$day.' days', strtotime($date))).'%27&$format=json'), TRUE);

            if (isset($get_cotattion['value'][0]))
                return ['result' => $get_cotattion['value'][0]['cotacaoCompra']];
            else
                $day++;

            do {

                $response = $this->quantationBRxUSD($currency, $date, $day);

                $day++;
            } while (!isset($response['result']));

            return ['result' => $response['result']];

        } else {

            $content = @file_get_contents("https://api.exchangeratesapi.io/v1/". date('Y-m-d', strtotime($date)) ."?access_key=6056e41532fdac37f01a77d8ac0a8d32&symbols=BRL&base=". $currency ."");

            if($content === FALSE) {
                return ['result' => 0];
            }
            $get_cotattion = json_decode($content, TRUE);

            return ['result' => $get_cotattion['rates']['BRL']];
        }
    }

    /** Save/Post Functions */
    public function savePrestacaoContas(Request $request) {
        $receipt = $request->file('receipt');
        $other = $request->file('other');

        // JSON RESPONSE FILES
        $receipt_url = "";
        $receipt_name = "";
        $other_url = "";
        $other_name = "";
        $is_financy = 0;
        $r_code = $request->session()->get('r_code');

        DB::beginTransaction(); //inicio da transação no SGBD

        if ($request->id == 0) {
            $accountability = new FinancyAccountability;
            $accountability->r_code = $r_code;
            $accountability->code = getCodeModule('accountability');
        } else {
            $accountability = FinancyAccountability::find($request->id);
            $old_accountability = $accountability->toJSON();
        }

        if ($request->item_id == 0) {
            $accountability_item = new FinancyAccountabilityItem;
        } else {
            $accountability_item = FinancyAccountabilityItem::find($request->item_id);
            $old_accountability_item = $accountability_item->toJSON();
        }

        $is_financy = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))
			->where('perm_id', 18)
			->first();

		if ($is_financy)
			$is_financy = 1;

        if ($request->has('lending_request_id')) {
            $accountability->lending_request_id = $request->lending_request_id;
        }

        $is_save = $accountability->save();
        if(!$is_save){
            DB::rollBack();
            $request->session()->put('error', "Erro ao Salvar no Dados no Banco de Dados");
            return redirect()->back();
        }

        if ($is_financy == 0) {
			
			if($request->date_submit == null) {
                $request->session()->put('error', "Selecione a data de consumo!");
            }
			
            $accountability_item->financy_accountability_id = $accountability->id;
            $accountability_item->currency = $request->currency;
            $accountability_item->type_entry = $request->type_entry;
            $accountability_item->type = $request->type;
            $accountability_item->description = $request->description;
            $accountability_item->peoples = $request->peoples;
            $accountability_item->city = $request->city;
            $date = $request->date_submit;
            $accountability_item->date = $request->date_submit;
        } else {
            $date = $accountability_item->date;
        }
        $source = array('.', ',');
        $replace = array('', '.');
        $total = str_replace($source, $replace, $request->total);

        $accountability_item->total = $total;


        $currency = $request->id != 0 ? $accountability_item->currency : $request->currency;
        if ($currency > 1) {
			
			$response = $this->quantationBRxUSD(currency($currency), $date);
            if ($response['result'] == 0)
                return redirect()->back()->with('error', 'Data informada não tem um valor de cotação!');

            $accountability_item->quotation = $response['result'];
        } else {
            $accountability_item->quotation = 0.00;
        }

        $is_save = $accountability_item->save();

        if(!$is_save){
            DB::rollBack();
            $request->session()->put('error', "Erro ao Salvar no Dados no Banco de Dados");
            return redirect()->back();
        }


        if ($request->hasFile('receipt')) {

            $attach = FinancyAccountabilityAttach::find($request->receipt_id);
            if ($attach === null) {
                $attach = new FinancyAccountabilityAttach;
            }else{
                removeS3($attach->url);
            }
            $attach->name = $receipt->getClientOriginalName();
            $attach->size = $receipt->getSize();
            $attach->financy_accountability_item_id = $accountability_item->id;

            $img_name = 1 .'-'. date('YmdHis') .'.'. $receipt->extension();
            $receipt->storeAs('/', $img_name, 's3');
            $url = Storage::disk('s3')->url($img_name);


            $attach->url = $url;
            $receipt_url = $url;
            $receipt_name = $receipt->getClientOriginalName();
            $is_save = $attach->save();
            if(!$is_save){
                removeS3($url);
                DB::rollBack();
                $request->session()->put('error', "Erro ao Salvar no Dados no Banco de Dados");
                return redirect()->back();
            }
        }

        if ($request->hasFile('other')) {

            $attach = FinancyAccountabilityAttach::find($request->other_id);
            if ($attach === null) {
                $attach = new FinancyAccountabilityAttach;
            }else{
                removeS3($attach->url);
            }

            $attach->name = $other->getClientOriginalName();
            $attach->size = $other->getSize();
            $attach->financy_accountability_item_id = $accountability_item->id;

            $img_name = 2 .'-'. date('YmdHis') .'.'. $other->extension();
            $other->storeAs('/', $img_name, 's3');
            $url = Storage::disk('s3')->url($img_name);
            $attach->url = $url;
            $other_url = $url;
            $other_name = $other->getClientOriginalName();
            $is_save = $attach->save();
            if(!$is_save){
                removeS3($url);
                DB::rollBack();
                $request->session()->put('error', "Erro ao Salvar no Dados no Banco de Dados");
                return redirect()->back();
            }
        }

        $accountability = FinancyAccountability::with(['lending.prestacao_conta'=>function($query){
            $query->notAnalyze();
        }])->find($accountability->id);

        $this->atualizaValorTotalItensPrestacaoContas($accountability, $request);
        if ($is_financy == 0) {
            $this->atualizaValorTotalPrestacaoContas($accountability, $request);
        }
        $this->atualizaSaldoDevedor($r_code);
        $this->atualizaPagamentoVinculado($accountability->id);

        $log_Observation=array(
            'financy_lending_id' => $accountability->lending_request_id,
            'model_class_origin'=>FinancyAccountability::class,
            'model_id'=>$accountability->id,
            'r_code'=>$request->session()->get('r_code'),
            'new_model_values'=>$accountability->toJSON(),
        );
        if ($request->id == 0) {
            $log_history = "Colaborador criou nova prestação de contas(#".$accountability->id.")";
        }else{
            $log_history = "Colaborador editou a prestação de contas(#".$request->id.")";
            $log_Observation['old_model_values'] = $old_accountability;
        }
        $log_Observation['description'] = $log_history;
        LogObservationHistory($log_Observation);
        LogSystem($log_history, $r_code);


        $log_Observation=array(
            'financy_lending_id' => $accountability->lending_request_id,
            'model_class_origin'=>FinancyAccountabilityItem::class,
            'model_id'=>$accountability_item->id,
            'r_code'=>$request->session()->get('r_code'),
            'new_model_values'=>$accountability_item->toJSON(),
        );
        if ($request->item_id == 0) {
            $log_history = "Colaborador adicionou o item (#".$accountability_item->id.") na prestação de contas(#".$request->id.").";

        }else{
            $log_history = "Colaborador editou o item (#".$accountability_item->id.") na prestação de contas(#".$request->id.") ";
            $log_Observation['old_model_values'] = $old_accountability_item;
        }
        $log_Observation['description'] = $log_history;

        LogObservationHistory($log_Observation);
        LogSystem($log_history, $r_code);


        DB::commit();

        return redirect('/financy/accountability/edit/'. $accountability->id);
    }

    public function savePrestacaoContasManual(Request $request) {
        $receipt = $request->file('receipt');
        $other = $request->file('other');

        // JSON RESPONSE FILES
        $receipt_url = "";
        $receipt_name = "";
        $other_url = "";
        $other_name = "";
        $is_financy = 0;
        $is_saved = true;

        DB::beginTransaction(); //inicio da transação no SGBD

        if ($request->financy_lending_id == 0) {
            abort(400,"Emprestimo não informado");
        }

        if ($request->entry_id == 0) {
            $manual_entry = new FinancyAccountabilityManualEntry;
            $lending = FinancyLending::find($request->financy_lending_id);
        } else {
            $manual_entry = FinancyAccountabilityManualEntry::find($request->entry_id);
            $old_manual_entry = $manual_entry->toJSON();
            $lending = $manual_entry->lending;
        }

        $source = array('.', ',');
        $replace = array('', '.');
        $total_sql_format = str_replace($source, $replace, $request->total);

        $request_date = str_replace("/", "-", $request->date);
        $date_sql = date('Y-m-d', strtotime($request_date));

        if($lending){
            if($request->type_entry == 2){

				
                $total_divida = $lending->getTotalPendente();
                $old_item_total = $manual_entry->total;

                $total_item = $total_sql_format - $old_item_total;

                if($total_divida>=0){
                    abort(400,"Não é possivel salvar esta prestação de contas, pois não existe saldo a Pagar");
                }else{

                    $total_item = number_format($total_item, 2, '.', '');
                    $total_divida = number_format(abs($total_divida), 2, '.', '');

                    //usuario Devendo
                    if($total_item> $total_divida ){
                        //impedir de lançar uma prestação de contas superior ao saldo da divida
                        abort(400,"Não é possivel salvar esta prestação de contas, pois o valor da Prestação é superior ao Saldo a Pagar");
                    } else {
						
						$user_financy = UserFinancy::where('r_code', $lending->r_code)->first();
						if ($user_financy) {
							if ($total_divida) {
								$new_total = $total_divida - floatval($total_sql_format);
								if ($new_total >= 0) {
									$user_financy->used_credit = $new_total;
									$user_financy->save();
								} else {
									$user_financy->used_credit = 0.00;	
								}
							}
						}
					}
                }
            }
        }

        $manual_entry->financy_lending_id = $request->financy_lending_id;
        $manual_entry->r_code = $request->user_r_code;
        $manual_entry->code = getCodeModule('accountability_manual');

        $manual_entry->type_entry = $request->type_entry;
        $manual_entry->p_method = $request->p_method;
        $manual_entry->description = $request->description;
        $manual_entry->total = $total_sql_format;

        $manual_entry->date = $date_sql;
        $is_saved = $manual_entry->save();

        $r_code = $request->session()->get('r_code');
        $log_Observation=array(
            'financy_lending_id' => $manual_entry->financy_lending_id,
            'model_class_origin'=>FinancyAccountabilityManualEntry::class,
            'model_id'=>$manual_entry->id,
            'r_code'=>$r_code,
            'new_model_values'=>$manual_entry->toJSON(),
        );
        if ($request->entry_id == 0) {
            $log_history = "Colaborador gerou uma prestação de contas manual(#".$manual_entry->id.") de acrécimo no valor de (".formatMoney($total_sql_format).") referente ao Emprestimo(#".$request->financy_lending_id.") ";
            if($request->type_entry==2){
                $log_history = "Colaborador gerou uma prestação de contas manual(#".$manual_entry->id.") no valor de (".formatMoney($total_sql_format).") referente ao Emprestimo(#".$request->financy_lending_id.") ";
            }
        }else{
            $log_history = "Colaborador editou prestação de contas manual(#".$manual_entry->id.").";
            $log_Observation['old_model_values']=$old_manual_entry;
        }
        $log_Observation['description']=$log_history;
        LogObservationHistory($log_Observation);
        LogSystem($log_history, $r_code);

        if(!$is_saved){
            DB::rollBack();
            abort(400,"Erro ao Salvar no Banco de Dados");
        }

        if ($request->hasFile('receipt')) {

            $attach = new FinancyAccountabilityAttach;
            $attach->name = $receipt->getClientOriginalName();
            $attach->size = $receipt->getSize();
            $attach->financy_accountability_manual_entry_id = $manual_entry->id;

            $img_name = 1 .'-'. date('YmdHis') .'.'. $receipt->extension();
            $receipt->storeAs('/', $img_name, 's3');
            $url = Storage::disk('s3')->url($img_name);


            $attach->url = $url;
            $receipt_url = $url;
            $receipt_name = $receipt->getClientOriginalName();
            $is_saved = $attach->save();
            if($is_saved == false){
                DB::rollBack();
                removeS3($url);
                abort(400,"Erro ao Salvar no Banco de Dados");
            }
        }

        if ($request->hasFile('other')) {
            $attach = new FinancyAccountabilityAttach;
            $attach->name = $other->getClientOriginalName();
            $attach->size = $other->getSize();
            $attach->financy_accountability_manual_entry_id = $manual_entry->id;

            $img_name = 2 .'-'. date('YmdHis') .'.'. $other->extension();
            $other->storeAs('/', $img_name, 's3');
            $url = Storage::disk('s3')->url($img_name);
            $attach->url = $url;
            $other_url = $url;
            $other_name = $other->getClientOriginalName();
            $is_saved = $attach->save();
            if($is_saved == false){
                DB::rollBack();
                removeS3($url);
                abort(400,"Erro ao Salvar no Banco de Dados");
            }
        }


        $this->atualizaSaldoDevedor($request->user_r_code);

        DB::commit();
		
		$user = Users::where('r_code', $request->user_r_code)->first();
        $typeEntry = $request->type_entry == 1 ? "Prestação de contas (+)" : "Prestação de contas (-)";
        $typePayment = $request->p_method == 2 ? "Transferência / D.Automático" : "Caixa";
        $pattern = array(
            'title' => 'PRESTAÇÃO DE CONTAS MANUAL',
            'description' => nl2br("
                <b>Colaborador: </b>". $user->full_name ."
                <br><b>Data da contabilização: </b>". $request->date ."
                <br><b>Descrição: </b>". $request->description ."
                <br><b>Tipo: </b>". $typeEntry ."
                <br><b>Forma de pagamento: </b>". $typePayment ."
                <br><b>Valor total: </b>". $request->total ."
                <br><b>Comprovante: </b> <a href='". $receipt_url ."'>".$receipt_name."</a>
                <br><b>Outro arquivo: </b> <a href='". $other_url ."'>".$other_name."</a>
            "),
            'template' => 'misc.Default',
            'copys' => ['joao.rocha@gree-am.com.br', 'simone@gree-am.com.br'],
            'subject' => 'Nova prestação de contas manual - '. date('d/m/Y H:i'),
        );

        SendMailJob::dispatch($pattern, $user->email);

        $redirect = redirect()->back()->getTargetUrl();

        return response()->json([
            'redirect' => $redirect,
        ]);
    }

    public function trocaEmprestimoPrestacaoContas(Request $request) {
        DB::beginTransaction(); //inicio da transação no SGBD
        $r_code = $request->session()->get('r_code');
        if ($request->id == 0) {


            //verifica se existe uma prestação de contas não enviada para analise
            $accountability = FinancyAccountability::notSendAnalyze($request->lending_request_id)->first();
            $is_edit = true;
            if(!$accountability){

                //verifica se existe uma prestação de contas em processo de analise
                $exists = FinancyAccountability::inAnalyze($request->lending_request_id)->first();
                if($exists){
                    abort(400,"Você não pode registrar uma nova prestação de contas para este emprestimo, pois existe uma prestação de contas em análise para este emprestimo");
                }
                $is_edit = false;
                $accountability = new FinancyAccountability;
                $accountability->code = getCodeModule('accountability');
                $accountability->r_code = $r_code;
            }
            $old_lending_request_id = $accountability->lending_request_id;
            $accountability->lending_request_id = $request->lending_request_id;
            $is_saved = $accountability->save();
            if($is_saved){
                DB::commit();
            }else{
                DB::rollBack();
            }

            if($is_edit){
                $log_history = "Colaborador editou a prestação de contas(#".$accountability->id.")";
            }else{
                $log_history = "Colaborador criou nova prestação de contas(#".$accountability->id.")";
            }

            $log_Observation=array(
                'financy_lending_id' => $accountability->lending_request_id,
                'model_class_origin'=>FinancyAccountability::class,
                'model_id'=>$accountability->id,
                'r_code'=>$request->session()->get('r_code'),
                'description'=>$log_history,
                'new_model_values'=>$accountability->toJSON(),
            );
            LogObservationHistory($log_Observation);

        } else {
            $accountability = FinancyAccountability::find($request->id);
            $old_model_values = $accountability->toJSON();

            $old_lending_request_id = $accountability->lending_request_id;
            $accountability->lending_request_id = $request->lending_request_id;
            $is_saved = $accountability->save();
            if($is_saved){
                DB::commit();
            }else{
                DB::rollBack();
            }

            $new_model_values = $accountability->toJSON();

            $log_history = "Colaborador trocou o emprestimo prestação de contas(#".$accountability->id."). Emprestimo Anterior(#".$old_lending_request_id."), Emprestimo Atual(#".$request->lending_request_id.")";
            $log_Observation=array(
                'model_class_origin'=>FinancyAccountability::class,
                'model_id'=>$accountability->id,
                'r_code'=>$request->session()->get('r_code'),
                'description'=>$log_history,
                'old_model_values'=>$old_model_values,
                'new_model_values'=>$new_model_values,
            );
            LogObservationHistory($log_Observation);

            //atualiza a relação do model
            $accountability = $accountability->fresh(['lending.prestacao_conta'=>function($query){
                $query->notAnalyze();
            }]);

        }

        if($old_lending_request_id != $request->lending_request_id){
            $this->atualizaValorTotalPrestacaoContas($accountability, $request);
            $this->atualizaSaldoDevedor($r_code);
        }


        return response()->json([
            'redirect' => '/financy/accountability/edit/'. $accountability->id,
        ]);
    }
	
	public function financyAccountabilitySendAnalyze_do(Request $request, $id) {

        try {
            $accountability = FinancyAccountability::find($id);
            $accountability_analyze = new App\Services\Departaments\Administration\Accountability\Accountability($accountability, $request);
            $do_analyze = new App\Services\Departaments\ProcessAnalyze($accountability_analyze);
            $do_analyze->eventStart();

            return redirect('/financy/accountability/my')->with('success', 'Prestação de contas enviado com sucesso!');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }    
    }
	
	public function financyAccountabilityAnalyze_do(Request $request) {

        try {

            $accountability = FinancyAccountability::with('rtd_analyze.users', 'user')->find($request->id);
            if(!$accountability)
                return redirect()->back()->with('error', 'Prestação de conta não encontrada!');

            $lending_analyze = new App\Services\Departaments\Administration\Accountability\Accountability($accountability, $request);
            $do_analyze = new App\Services\Departaments\ProcessAnalyze($lending_analyze);

            $actions = [
                1 => 'eventApprov',
                2 => 'eventReprov',
                3 => 'eventSuspended',
                4 => 'eventRevert'
            ];
            
            $method = $actions[$lending_analyze->request->type];
            $result = $do_analyze->$method();

            return redirect('/financy/accountability/approv')->with('success', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function financyAccountabilitySendAnalyze(Request $request, $id) {

        $accountability = FinancyAccountability::find($id);

        if ($accountability) {
            $r_code = $accountability->r_code;

            $user_financy = UserFinancy::where('r_code', $r_code)->first();
            if (!$user_financy) {
                $request->session()->put('error', 'Você precisa adicionar seus dados bancários.');
                return Redirect('/financy/accountability/edit/'. $id);
            }

            DB::beginTransaction(); //inicio da transação no SGBD
            if($accountability->total>0){


                $append_attach = true;
                if($accountability->payment_request_id !=0 ){
                    $r_payment = FinancyRPayment::find($accountability->payment_request_id);
                    $append_attach = false;
                }else{
                    $r_payment = new FinancyRPayment;
                    $r_payment->code = getCodeModule('payment');
                }


                if($user_financy){
                    $r_payment->agency = $user_financy->agency;
                    $r_payment->account = $user_financy->account;
                    $r_payment->bank = $user_financy->bank;
                    $r_payment->identity = $user_financy->identity;
                    $r_payment->cnpj = $user_financy->identity;
                }


                $r_payment->request_r_code = $r_code;
                $r_payment->request_category = 10;
                $r_payment->description = 'PAGAMENTO DE PRESTAÇÃO DE CONTAS';
                $r_payment->has_analyze = 1;

                $r_payment->nf_nmb = "CONTABILIZADO";
                // $r_payment->nf_nmb = "PRESTACAO_CONTAS";

                $r_payment->amount_gross = $accountability->total;
                if ($accountability->total_liquid < 0.00) {
                    $r_payment->amount_liquid = abs($accountability->total_liquid);
                } else {
                    $r_payment->amount_liquid = 0.00;
                }
                $r_payment->optional = $accountability->description;


                $r_payment->recipient = $accountability->user->full_name;
                $r_payment->recipient_r_code = $r_code;


                $r_payment->due_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + 8 day'));

                $r_payment->p_method = 2;
                $r_payment->module_id = $accountability->id;
                $r_payment->module_type = 4;

                $is_save = $r_payment->save();

                $log_history = "Colaborador enviou prestação de contas(#".$accountability->id.") para análise, gerando solicitação de Pagamento(#".$r_payment->id.") no valor de (".$r_payment->amount_liquid.")";
                $log_Observation=array(
                    'financy_lending_id' => $accountability->lending_request_id,
                    'model_class_origin'=>FinancyAccountability::class,
                    'model_id'=>$accountability->id,
                    'r_code'=>$request->session()->get('r_code'),
                    'description'=>$log_history,
                );
                LogObservationHistory($log_Observation);



                if(!$is_save){
                    DB::rollBack();
                    $request->session()->put('error', "Erro ao Salvar no Dados no Banco de Dados");
                    return redirect()->back();
                }

                // GEN CODE SEGMENT
                if($append_attach){
                    $attach = new FinancyRPaymentAttach;
                    $attach->name = 'prestacao_de_contas';
                    $attach->size = 500;
                    $attach->financy_r_payment_id = $r_payment->id;
                    $attach->url = $request->root() .'/financy/accountability/edit/'. $accountability->id;
                    $is_save = $attach->save();
                    if(!$is_save){
                        DB::rollBack();
                        $request->session()->put('error', "Erro ao Salvar no Dados no Banco de Dados");
                        return redirect()->back();
                    }
                }

                $accountability->payment_request_id = $r_payment->id;
                $accountability->has_analyze = 1;
                $is_save = $accountability->save();
                if(!$is_save){
                    DB::rollBack();
                    $request->session()->put('error', "Erro ao Salvar no Dados no Banco de Dados");
                    return redirect()->back();
                }

                DB::commit();
                //Envia Notificação para o Chefe Imediado do usuario
                $this->enviarNotificacaoPrestacaoContas($accountability,$request);


                return redirect('/financy/accountability/my');
            }else{
                $request->session()->put('error', 'Sua Prestação de Contas deve ser maior que zero.');
                return Redirect('/financy/accountability/edit/'. $id);
            }

        }


    }

    /** Delete Functions */
    public function financyAccountabilityItemDelete(Request $request) {

        DB::beginTransaction(); //inicio da transação no SGBD
        $accountability_item = FinancyAccountabilityItem::find($request->item_id);
        $old_accountability_item = $accountability_item->toJSON();


        if ($accountability_item) {

            $accountability_item->load('attach');

            if($accountability_item->attach){

                foreach ($accountability_item->attach as $item) {
                    $is_deleted = $item->delete();
                    if($is_deleted){
                        removeS3($item->url);
                    }else{
                        DB::rollBack();
                        $request->session()->put('error', "Erro ao Remover Item no Banco de Dados");
                        return redirect()->back();
                    }
                }
            }

            $is_deleted = FinancyAccountabilityItem::where('id', $request->item_id)->delete();

            $r_code = $request->session()->get('r_code');
            $log_history = "Colaborador removeu o item(#".$accountability_item->item_id.") da prestação de contas(#".$accountability_item->financy_accountability_id.").";
            $log_Observation=array(
                'model_class_origin'=>FinancyAccountabilityItem::class,
                'model_id'=>$accountability_item->id,
                'r_code'=>$r_code,
                'description'=>$log_history,
                'old_model_values'=>$old_accountability_item,
            );
            LogObservationHistory($log_Observation);
            LogSystem($log_history, $r_code);

            if(!$is_deleted){
                DB::rollBack();
                $request->session()->put('error', "Erro ao Remover Item no Banco de Dados");
                return redirect()->back();
            }

            $accountability = FinancyAccountability::find($accountability_item->financy_accountability_id);

            //atualiza a relação do model
            $accountability = $accountability->fresh(['lending.prestacao_conta'=>function($query){
                $query->notAnalyze();
            }]);

            $this->atualizaValorTotalItensPrestacaoContas($accountability, $request);

            if ($accountability->has_analyze == 0) {
                $this->atualizaValorTotalPrestacaoContas($accountability, $request);
            }

            $this->atualizaSaldoDevedor($accountability->r_code);
            $this->atualizaPagamentoVinculado($accountability->id);

            DB::commit();
            return redirect('/financy/accountability/edit/'. $accountability->id);

        } else {
            abort(400,"Item não existe na base de dados.");
        }

    }

    public function financyAccountabilityManualEntryItemDelete(Request $request) {

        DB::beginTransaction(); //inicio da transação no SGBD
        $manual_entry = FinancyAccountabilityManualEntry::find($request->item_id);


        if ($manual_entry) {
            $old_manual_entry = $manual_entry->toJSON();
            $user_r_code = $manual_entry->r_code;
            $manual_entry->load('attach');

            if($manual_entry->attach){

                foreach ($manual_entry->attach as $item) {
                    $is_deleted = $item->delete();
                    if($is_deleted){
                        removeS3($item->url);
                    }else{
                        DB::rollBack();
                        abort(400,"Erro ao Salvar no Banco de Dados");
                    }
                }
            }

            $is_deleted = FinancyAccountabilityManualEntry::where('id', $request->item_id)->delete();

            $r_code = $request->session()->get('r_code');
            $log_history = "Colaborador removeu o item(#".$request->item_id.") da prestação de contas manual do emprestimo(#".$manual_entry->financy_lending_id.")";
            $log_Observation=array(
                'financy_lending_id' => $manual_entry->financy_lending_id,
                'model_class_origin'=>FinancyAccountabilityManualEntry::class,
                'model_id'=>$manual_entry->id,
                'r_code'=>$r_code,
                'description'=>$log_history,
                'old_model_values'=>$old_manual_entry,
            );
            LogObservationHistory($log_Observation);
            LogSystem($log_history, $r_code);

            if(!$is_deleted){
                DB::rollBack();
                abort(400,"Erro ao Salvar no Banco de Dados");
            }

            //recalcular o valor total da tabela FinancyUsersDebtors;
            $this->atualizaSaldoDevedor($user_r_code);

            DB::commit();

            // $redirect = '/financy/list/debtors?r_code=2964';
            $redirect = redirect()->back()->getTargetUrl();

            return response()->json([
                'redirect' => $redirect,
            ]);

        } else {
            abort(400,"Item não existe na base de dados.");
        }

    }
    /** Aux Functions */
    private function atualizaValorTotalPrestacaoContas($accountability, $request){
        if($accountability){
            $total_pending = $accountability->lending->getTotalPendente();
            if($total_pending<0){
                $total_pending = abs($total_pending);
            }

            if($request->has('description_accountability')){
                $accountability->description = $request->description_accountability;
            }
            $accountability->total_lending = $accountability->lending->getTotalEmprestimo();
            $accountability->total_pending = $total_pending;
            $accountability->total_liquid = $total_pending - $accountability->total;

            $is_save = $accountability->save();

            if(!$is_save){
                DB::rollBack();
                abort(400,"Erro ao Atualizar o valor Total da Prestação de Contas");
            }

            return $accountability;
        }else{
            throw new \Exception('Model Not Found');
        }

    }

    private function atualizaPagamentoVinculado($id) {

        $fa = FinancyAccountability::find($id);
        if ($fa) {

            $r_payment = FinancyRPayment::find($fa->payment_request_id);
            if ($r_payment) {

                $r_payment->amount_gross = $fa->total;

                if ($fa->amount_liquid < 0)
                    $r_payment->amount_liquid = $fa->total_liquid;
                else
                    $r_payment->amount_liquid = 0.00;

                $r_payment->save();
            }
        }
    }

    private function atualizaValorTotalItensPrestacaoContas($accountability, $request){
        if($accountability){
            // $itens = accountabilityFinancyAccountabilityItem::where('financy_accountability_id', $accountability->id)->get();
            $old_value = $accountability->total;
            $old_accountability = $accountability->toJSON();
            $itens = $accountability->itens;
            $gen_total = 0.00;
            foreach ($itens as $item) {

                if ($item->currency > 1) {

					$response = $this->quantationBRxUSD(currency($item->currency), $item->date);
					if ($response['result'] == 0)
						return redirect()->back()->with('error', 'Data informada não tem um valor de cotação!');
					
                    $multiply = $response['result'];
                    $gen_total = $gen_total + round($multiply * $item->total, 2);

                } else {
                    $gen_total = $gen_total + $item->total;
                }
            }
            $accountability->total = $gen_total;
            $is_save = $accountability->save();

            if(!$is_save){
                DB::rollBack();
                $request->session()->put('error', "Erro ao Atualizar Dados no Banco de Dados");
                return redirect()->back();
            }

            $log_history = "Atualização no Valor Total da Prestação de contas(#".$request->id."), valor Anterior (".$old_value."), valor Atualizado (".$gen_total.")";
            $log_Observation=array(
                'financy_lending_id' => $accountability->lending_request_id,
                'model_class_origin'=>FinancyAccountability::class,
                'model_id'=>$accountability->id,

                'r_code'=>$request->session()->get('r_code'),
                'description'=>$log_history,
                'old_model_values'=>$old_accountability,
                'new_model_values'=>$accountability->toJSON(),
            );
            LogObservationHistory($log_Observation);

            return $accountability;
        }else{
            throw new \Exception('Model Not Found');
        }

    }

    private function enviarNotificacaoPrestacaoContas($accountability,$request){


        $immediate = \App\Model\UserImmediate::leftJoin('users', 'user_immediate.immediate_r_code', '=', 'users.r_code')
            ->select('users.*', 'user_immediate.*')
            ->where('user_r_code', $accountability->r_code)
            ->get();

        $r_user = $accountability->user;
        $itens = $accountability->itens;

        $pattern = array(
            'id' => $accountability->id,
            'immediates' => $immediate,
            'title' => 'Prestação de Contas: #'. $accountability->code." referente ao Emprestimo: #".$accountability->lending->code,
            'description' => '',
            'template' => 'accountability.Success',
            'subject' => 'Prestação de Contas: #'. $accountability->code,
        );

        SendMailJob::dispatch($pattern, $r_user->email);

        if (count($immediate) > 0) {

            $pattern = array(
                'id' => $accountability->id,
                'payment_request_id' => $accountability->payment_request_id,
                'sector_id' => $accountability->user->sector_id,
                'itens' => $itens,
                'r_code' => $accountability->r_code,
                'lending' => formatMoney($accountability->total_lending),
                'total_paid' => formatMoney(($accountability->total_lending - $accountability->total_pending) ),
                'total_des' =>  formatMoney($accountability->total),
                'total' => formatMoney(abs($accountability->total_liquid)),
                'created_at' => $accountability->created_at->format('d/m/Y'),
                'title' => 'APROVAÇÃO DE PRESTAÇÃO DE CONTAS',
                'description' => '',
                'template' => 'accountability.Analyze',
				'copys' => ['glauco.leao@gree-am.com.br', 'joao.rocha@gree-am.com.br', 'simone@gree-am.com.br'],
                'subject' => 'APROVAÇÃO DE PRESTAÇÃO DE CONTAS',
            );

            foreach ($immediate as $key) {

                $imdt = Users::where('r_code', $key->immediate_r_code)->first();

                if ($imdt->is_holiday == 1) {
                    $usrhd = \App\Model\UserHoliday::where('user_r_code', $imdt->r_code)->get();
                    foreach($usrhd as $usr) {

                        $imdt = Users::where('r_code', $usr->receiver_r_code)->first();

                        SendMailJob::dispatch($pattern, $imdt->email);
                        App::setLocale($imdt->lang);
                        NotifyUser(__('layout_i.n_accountability_001_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_accountability_001', ['Name' => $request->session()->get('first_name')]), $request->root() .'/financy/payment/request/approv/'. $accountability->payment_request_id);
                        App::setLocale($request->session()->get('lang'));
                    }
                } else {

                    SendMailJob::dispatch($pattern, $imdt->email);
                    App::setLocale($imdt->lang);
                    NotifyUser(__('layout_i.n_accountability_001_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_accountability_001', ['Name' => $request->session()->get('first_name')]), $request->root() .'/financy/payment/request/approv/'. $accountability->payment_request_id);
                    App::setLocale($request->session()->get('lang'));
                }

            }

        }

    }

    private function atualizaSaldoDevedor($r_code){
        $rules = new RulesFinancyLending();
        $rules->atualizaDividaUsuario($r_code);
    }

    private function getRowsLendigsToExportExcel($lendings){
        $heading = array('Usuário', 'Departamento' , 'ID Emprestimo', 'Data Emprestimo', 'Total Emprestimo', 'Total Pago', 'Saldo a Pagar');
        $rows = array();

        $line = array();
        if($lendings->isNotEmpty()){
            foreach ($lendings as $lending){
                $line[0] = mb_strtoupper($lending->user->full_name,'UTF-8')." (".$lending->user->r_code.")";
                $line[1] = mb_strtoupper(sectors($lending->user->sector_id),'UTF-8');
                $line[2] = $lending->code;

                $data_pgto = $lending->getDataPagamentoEmprestimo();
                if($data_pgto){
                    $line[3] = $data_pgto->format('d/m/Y');
                }else{
                    $line[3] = "";
                }

                $total_emprestimo = $lending->getTotalEmprestimo();
                $total_pago = $lending->getTotalPago(1);
                $saldo = $total_emprestimo - $total_pago;

                $line[4] = formatMoney($total_emprestimo);
                $line[5] = formatMoney($total_pago);
                $line[6] = formatMoney($saldo);
                array_push($rows, $line);
            }
        }

        return [
            'heading'=>$heading,
            'rows'=>$rows,
        ];
    }

    /** View/List Functions */
    public function financyAccountabilityMy(Request $request) {

        $a_bank = UserFinancy::where('r_code', $request->session()->get('r_code'))->first();

        $html_table = $this->ajaxViewHistoricoEmprestimosUsuario($request, $request->session()->get('r_code'));

        $filtros_sessao = getSessionFilters("ajax_filter_");

        return view('gree_i.accountability.accountability_my', [
            'html_table' => $html_table,
            'a_bank' => $a_bank,
            'r_code' => $request->session()->get('r_code'),
        ]);
    }

    public function financyAccountabilityAll(Request $request) {

        $array_input = collect([
            'id',
            'r_code',
        ]);
        $array_input = putSession($request, $array_input,"accountability_filter_");

        $accountability = FinancyAccountability::with('user')->orderByRaw('has_analyze DESC, id DESC');

        $filtros_sessao = getSessionFilters("accountability_filter_");
        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."id"){
                    $accountability->where('code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."r_code"){
                    $accountability->where('financy_accountability.r_code', 'like', '%'. $valor_filtro .'%');
                }
            }
        }

        $userall = Users::all();

        return view('gree_i.accountability.accountability_all', [
            'userall' => $userall,
            'accountability' => $accountability->paginate(10),
        ]);

    }

    public function financyAccountabilityApprov(Request $request) {

        $accountability = FinancyAccountability::where('has_analyze', 1)->ValidAnalyzeProccess($request->session()->get('r_code'))->orderBy('id', 'DESC');

        $array_input = collect([
            'id',
            'r_code',
        ]);
        $array_input = putSession($request, $array_input,"accountability_filter_");

        $filtros_sessao = getSessionFilters("accountability_filter_");
        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."id"){
                    $accountability->where('code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."r_code"){
                    $accountability->where('financy_accountability.r_code', 'like', '%'. $valor_filtro .'%');
                }
            }
        }

        $userall = Users::all();
        return view('gree_i.accountability.accountability_approv_list', [
            'userall' => $userall,
            'accountability' => $accountability->paginate(10),
        ]);
    }

    public function listAllDevedores(Request $request) {

        $array_input = collect([
            'f_status',
            'r_code',
        ]);
        $array_input = putSession($request, $array_input,"accountability_filter_");


        $users_debt = FinancyUsersDebtors::orderBy('total_lendings', 'DESC');


        $filtros_sessao = getSessionFilters("accountability_filter_");

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."r_code"){
                    $users_debt->where('r_code', 'like', '%'. $valor_filtro .'%');
                }

            }
        }

        if ($request->export == 1) {

            $rows = array();

            foreach ($users_debt->get() as $key) {
                $line = array();
                if($key->lendings->isNotEmpty()){

                    $data_excel = $this->getRowsLendigsToExportExcel($key->lendings);
                    $line = $data_excel['rows'];
                    $heading = $data_excel['heading'];
                    $rows = array_merge($rows, $line);
                }
            }

            return Excel::download(new DefaultExport($heading, $rows), 'UsersDebtorsExport-'. date('Y-m-d') .'.xlsx');
        } else {

            $userall = Users::all();
            return view('gree_i.accountability.financy_users_debt_all', [
                'userall' => $userall,
                'models' => $users_debt->paginate(10),
            ]);
        }


    }




}
