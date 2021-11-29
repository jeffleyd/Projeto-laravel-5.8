<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App;
use App\Model\Users;
use App\Model\JuridicalProcess;
use App\Model\JuridicalLawFirm;
use App\Model\JuridicalLawFirmCost;
use App\Model\JuridicalLawFirmCostAttach;
use App\Model\JuridicalLawFirmContacts;
use App\Model\JuridicalLawFirmAccount;
use App\Model\JuridicalTypeAction;
use App\Model\JuridicalTypeDocument;
use App\Model\JuridicalTypeCost;
use App\Model\JuridicalProcessHistoric;
use App\Model\JuridicalProcessDocuments;
use App\Model\JuridicalProcessCost;
use App\Model\JuridicalProcessCostAttach;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Imports\ProcessImport;
use App\Exports\DefaultExport;
use App\Http\Controllers\Services\FileManipulationTrait;

use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class JuridicalController extends Controller
{

    use FileManipulationTrait;

    public function processEdit(Request $request, $id) {

        $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
                        ->where('user_on_permissions.perm_id', 23)
						->where('user_on_permissions.grade', 99)
                        ->select('users.*')
                        ->get();          

        $typeAction = JuridicalTypeAction::where('status', 1)->get();
        
        if ($id == 0) {
            
            return view('gree_i.juridical.process_new', [
                'id' => $id,
                'type_action' => $typeAction,
                'users' => $users
            ]);
        } else {

            $process = JuridicalProcess::with(['sac_client', 'juridical_type_action', 'juridical_law_firm'])->where('id', $id)->first();

            return view('gree_i.juridical.process_edit', [
                'id' => $id,
                'users' => $users,
                'type_action' => $typeAction,
                'type_applicant' => $process->type_applicant,
                'process' => $process,
                'sector_related' => $process->sector_related,
                'type_sector_related' => $process->type_sector_related,
                'type_process_name' => $this->getTypeProcessName($process->type_process)
            ]);
        }
    } 
	
	public function processDetails(Request $request) {


        $process = JuridicalProcess::with('users', 'juridical_law_firm', 'juridical_type_action', 'sac_client')->find($request->process_id);

        if($process) {
            if($process->costumer_id == 0) {
                $name_applicant = $process->name_applicant . ' ('.$process->identity_applicant. ')';
                $sac_client_id = 0;
            } else {
                $sac_client_id = $process->sac_client->id;
                $name_applicant = $process->sac_client->name;
            }

            $details = array(
                'type_process' => $this->getTypeProcess($process->type_process),
                'date_received' => date('d/m/Y', strtotime($process->date_received)),
                'sac_client_id' => $sac_client_id,
                'costumer_id' => $process->costumer_id,
                'name_applicant' => $name_applicant,
                'name_required' => $process->name_required. '('.$process->identity_required.')',
                'type_action' => $process->juridical_type_action->description,
                'district_court' => $process->district_court,
                'state_court' => $process->state_court != '' ? config('gree.states')[$process->state_court] : '',
                'date_judgment' => date('d/m/Y', strtotime($process->date_judgment)),
                'value_cause' => number_format($process->value_cause, 2,",","."),
                'law_name_resp' => $process->users->full_name,
                'law_firm_id' => $process->juridical_law_firm->id,
                'law_firm_name' => $process->juridical_law_firm->name,
                'measures_plea' => $process->measures_plea,
                'last_historic' => $process->juridical_process_historic()->orderBy('id', 'desc')->first()
            );

            return response()->json([
                'success' => true,
                'details' => $details,
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Detalhes do processo não foram encontrados!'
            ], 400);
        }        
    }

    public function processEdit_do(Request $request) {

        if ($request->id == 0) {

            $process = new JuridicalProcess;
            $msg_log = 'criou novo';
            $msg_email = 'NOVO PROCESSO';

        } else {
            $process = JuridicalProcess::find($request->id);
            if(!$process) {
                $request->session()->put('error', 'Processo não foi encontrado!');
                return redirect()->back(); 
            }
            $msg_log = 'atualizou';
            $msg_email = 'ATUALIZADO PROCESSO';
        }

        $request->merge([
            'value_cause' => str_replace(',', '.',str_replace('.', '', $request->value_cause)),
            'date_received' => date('Y-m-d', strtotime(str_replace("/", "-", $request->date_received))),
            'date_judgment' => date('Y-m-d', strtotime(str_replace("/", "-", $request->date_judgment))),
            'costumer_id' => $request->costumer_id != null ? $request->costumer_id : 0,
            'process_number_execution' => $request->process_number_execution != '' ? $request->process_number_execution : '',
            'type_applicant' => $request->type_process == 6 || $request->costumer_id != 0 ? 0 : $request->type_applicant,
            'identity_applicant' => $request->identity_applicant != '' ? $request->identity_applicant : '',
            'name_applicant' => $request->name_applicant != '' ? $request->name_applicant : '',
            'worker_r_code' => $request->worker_r_code != '' ? $request->worker_r_code : '',
            'sector_related' => $request->sector_related != null ? $request->sector_related : '',
            'type_sector_related' => $request->type_sector_related != null ? $request->type_sector_related : 0,
            'code_sector_related' => $request->code_sector_related != null ? $request->code_sector_related : '',
        ]);

        $process->fill($request->all());

        DB::beginTransaction();
        
        try {

            $process->save();
            DB::commit();

            $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
                            ->where('user_on_permissions.perm_id', 23)
							->where('user_on_permissions.grade', 99)
                            ->select('users.*')
                            ->get();

            foreach ($users as $key) {
                $pattern = array(
                    'title' => 'PROCESSO JURÍDICO: '.$process->process_number.'',
                    'description' => nl2br("Número do processo: ". $process->process_number ."\n Seara: ". $this->getTypeProcess($process->type_process) ."\n Vara: ".$process->judicial_court." \n Fórum: ".$process->judicial_forum." \n\nVeja mais informações no link abaixo: \n <a href='". $request->root() ."/juridical/process/info/". $process->id ."'>".$request->root()."/juridical/process/info/". $process->id ."</a>"),
                    'template' => 'misc.Default',
                    'subject' => ''.$msg_email.' JURÍDICO: '. $process->process_number .'',
                );
                NotifyUser(''.$msg_email.' JURÍDICO: ('. $process->process_number.')', $key->r_code, 'fa-exclamation', 'text-info', 'Colaborador '.$msg_log.' processo, clique aqui para ver mais detalhes.', $request->root() .'/juridical/process/info/'. $process->id);
                SendMailJob::dispatch($pattern, $key->email);
            }

            LogSystem("Colaborador ".$msg_log." processo jurídico: ". $process->id, $request->session()->get('r_code'));
            $request->session()->put('success', 'Processo salvo com sucesso!');
            return redirect('/juridical/process/list');

        } catch (\Exception $e) {

            DB::rollBack();
            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function processList(Request $request) {
        
        $process = JuridicalProcess::with('sac_client', 'juridical_type_action', 'juridical_law_firm')->orderBy('id', 'DESC');
        $typeAction = JuridicalTypeAction::all();

        $array_input = collect([
            'process_number',
            'type_process',
            'type_action',
            'costumer_id',
            'identity_applicant',
            'name_applicant',
            'law_firm_id',
            'status',
			'start_date',
            'end_date'
        ]);
        
        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."process_number"){
                    $process->where('process_number', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."type_process"){
                    $process->where('type_process', $value_filter);
                }
                if($name_filter == $filter_session[1]."type_action"){
                    $process->where('type_action_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."costumer_id"){
                    $process->where('costumer_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."identity_applicant"){
                    $process->where('identity_applicant', $value_filter);
                }
                if($name_filter == $filter_session[1]."name_applicant"){
                    $process->where('name_applicant', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."law_firm_id"){
                    $process->where('law_firm_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."status"){
					$value = $value_filter == 99 ? 0 : $value_filter;
                    $process->where('status', $value);
                }
				if($name_filter == $filter_session[1]."start_date"){
                    $process->whereDate('created_at', '>=', $value_filter);
                }
                if($name_filter == $filter_session[1]."end_date"){
                    $process->whereDate('created_at', '<=', $value_filter);
                }
            }
        }

        return view('gree_i.juridical.process_list', [
            'process' => $process->paginate(10),
            'type_action' => $typeAction
        ]);
    }

    public function processInfo(Request $request, $id) {
        
        $process = JuridicalProcess::with('users', 'juridical_law_firm', 'juridical_type_action', 'sac_client')->find($id);
		
		if (!$process)
			return redirect()->back()->with('error', 'O processo em questão, não existe mais na base de dados.');

        $type_documents = JuridicalTypeDocument::where('status', 1)->get();
        $type_cost = JuridicalTypeCost::where('status', 1)->get();
        $process_cost = $process->juridical_process_cost();

        if($request->has('tabName'))
            $request->session()->put('tabName', 'cost');
        
        return view('gree_i.juridical.process_info', [
            'process_id' => $id,
            'process' => $process,
            'process_historic' => $process->juridical_process_historic()->orderBy('id', 'desc')->paginate(5, ['*'], 'historic'),
            'process_cost' => $process_cost->orderBy('id', 'DESC')->paginate(5, ['*'], 'cost'),
            'type_process' => $this->getTypeProcess($process->type_process),
            'type_documents' => $type_documents,
            'type_cost' => $type_cost,
            'total_cost' => $process->juridical_process_cost()->sum('total'),
            'total_paid' => $process->juridical_process_cost()->where('is_paid', 1)->sum('total'),
            'total_not_paid' => $process->juridical_process_cost()->where('is_paid', 0)->sum('total')
        ]);
    }

    public function processHistoric_do(Request $request) {

        if ($request->historic_id == 0) {

            $historic = new JuridicalProcessHistoric;
            $msg_log = 'criou novo';
            $msg_email = 'NOVO ANDAMENTO';

        } else {
            $historic = JuridicalProcessHistoric::find($request->historic_id);
            if(!$historic) {
                $request->session()->put('error', 'Andamento não foi encontrado!');
                return redirect()->back();
            }

            $msg_log = 'atualizou';
            $msg_email = 'ATUALIZADO ANDAMENTO';
        }

        $historic->juridical_process_id = $request->process_id;
        $historic->title = $request->title;
        $historic->description = $request->description;
        $historic->date_publication = implode('-', array_reverse(explode('/', $request->date_publication)));
        
        DB::beginTransaction();
        
        try {

            $historic->save();

            if($request->arr_documents != null) {
                $this->processDocuments($request->arr_documents, $historic->id);
            }

            DB::commit();

            $process_number = $historic->juridical_process()->first()->process_number;

            $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
                            ->where('user_on_permissions.perm_id', 23)
							->where('user_on_permissions.grade', 99)
                            ->select('users.*')->get();

            foreach ($users as $key) {
                $pattern = array(
                    'title' => 'ANDAMENTO DE PROCESSO JURÍDICO: '.$process_number.'',
                    'description' => nl2br("Número do processo: ".$process_number."\n Título: ". $historic->title ."\n Descrição: \n".stringCut($historic->description, 100)." \n Data de publicação: ".date('d/m/Y', strtotime($historic->date_publication))." \n\nVeja mais informações no link abaixo: \n <a href='". $request->root() ."/juridical/process/info/". $request->process_id ."'>".$request->root()."/juridical/process/info/". $request->process_id ."</a>"),
                    'template' => 'misc.Default',
                    'subject' => ''.$msg_email.' JURÍDICO: '. $process_number .'',
                );
                NotifyUser(''.$msg_email.' JURÍDICO: ('. $process_number.')', $key->r_code, 'fa-exclamation', 'text-info', 'Colaborador '.$msg_log.' andamento, clique aqui para ver mais detalhes.', $request->root() .'/juridical/process/info/'. $request->process_id);
                SendMailJob::dispatch($pattern, $key->email);
            }

            LogSystem("Colaborador ".$msg_log." andamento jurídico: ". $request->process_id, $request->session()->get('r_code'));
            $request->session()->put('success', 'Andamento salvo com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            DB::rollBack();
            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function processHistoricAjax(Request $request) {

        $historic = JuridicalProcessHistoric::with('juridical_process_documents.juridical_type_document')->find($request->historic_id);
        if(!$historic) {
            return response()->json([
                'success' => false,
                'message' => 'Andamento não foi encontrado!'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'historic' => $historic,
            'date_publication' => date('d/m/Y', strtotime($historic->date_publication))
        ], 200);
    }

    public function processCostAjax(Request $request) {

        $cost = JuridicalProcessCost::with('juridical_process_cost_attach')->find($request->cost_id);
        if(!$cost) {
            return response()->json([
                'success' => false,
                'message' => 'Custo do processo não encontrado!'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'cost' => $cost,
            'date_release' => date('d/m/Y', strtotime($cost->date_release)),
            'date_expiration' => date('d/m/Y', strtotime($cost->date_expiration))
        ], 200);
    }

    public function lawFirmCostInfoAjax(Request $request) {

        $cost = JuridicalLawFirmCost::with('juridical_law_firm_cost_attach')->find($request->law_cost_id);
        
        if(!$cost) {
            return response()->json([
                'success' => false,
                'message' => 'Custo do escritório não encontrado!'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'cost' => $cost,
            'date_release' => date('d/m/Y', strtotime($cost->date_release)),
            'date_expiration' => date('d/m/Y', strtotime($cost->date_expiration))
        ], 200);
    }

    public function processDocumentsAjax(Request $request) {

        if ($request->hasFile('file_document')) {
            
            $response = $this->uploadS3(1, $request->file_document, $request);

            if ($response['success']) {

                return response()->json([
                    'success' => true,
                    'url' => $response['url']
                ], 200);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Não foi possível fazer upload do arquivo!'
                ], 400);
            }
        }  else {

            return response()->json([
                'success' => false,
                'message' => 'Arquivo não adicionado para upload!'
            ], 400);
        }
    }

    public function processDocumentsDeleteAjax(Request $request) {

        try {

            if($request->type == 1 && $request->url != '') {

                removeS3($request->url);
                
            } else if($request->type == 2) {

                //$document = JuridicalProcessDocuments::where('id', $request->document_id)->where('juridical_process_historic_id', $request->historic_id)->first();
                $document = JuridicalProcessDocuments::find($request->document_id);

                if($document) {
                    
                    if($document->delete()) {
                        removeS3($request->url);
                    }
    
                } else {
                    throw new \Exception('Documento não encontrado!');
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Documento excluído!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function processInfoUpdateStatusAjax(Request $request) {

        $info_status = JuridicalProcess::find($request->process_id);

        if(!$info_status) {

            return response()->json([
                'success' => false,
                'message' => 'Processo não foi encontrado!'
            ], 400);
        }

        try {

            $info_status->status = $request->status;
            $info_status->date_finished = $request->date_finished ? date('Y-m-d', strtotime(str_replace('/', '-', $request->date_finished))) : null;
            $info_status->save();

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso!'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }    
    }    

    public function processCostAttchAjax(Request $request) {
        
        if ($request->hasFile('cost_attach_file')) {
            
            $response = $this->uploadS3(1, $request->cost_attach_file, $request);

            if ($response['success']) {

                return response()->json([
                    'success' => true,
                    'url' => $response['url']
                ], 200);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Não foi possível fazer upload do comprovante!'
                ], 400);
            }
        }  else {

            return response()->json([
                'success' => false,
                'message' => 'Comprovante não adicionado para upload!'
            ], 400);
        }
    }

    public function processCostAttchDeleteAjax(Request $request) {

        try {

            if($request->type == 1 && $request->url != '') {

                removeS3($request->url);
                
            } else if($request->type == 2) {

                $attach = JuridicalProcessCostAttach::where('id', $request->attach_id)->where('juridical_process_cost_id', $request->cost_id)->first();
                
                if($attach) {

                    $process_cost = JuridicalProcessCost::find($request->cost_id);
                    $process_cost->total = $process_cost->total - $attach->value;
                    $process_cost->save();
                    
                    if($attach->delete()) {
                        removeS3($request->url);
                    }
    
                } else {
                    throw new \Exception('Documento não encontrado!');
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Documento excluído!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function processCost_do(Request $request) {

        if ($request->cost_id == 0) {

            $process_cost = new JuridicalProcessCost;
            $process_cost->code = getCodeModule('juridical_process');

            $msg_log = 'criou novo';
            $msg_email = 'NOVO CUSTO';

        } else {
            $process_cost = JuridicalProcessCost::find($request->cost_id);
            if(!$process_cost) {
                $request->session()->put('error', 'Custo não foi encontrado!');
                return redirect()->back();
            }
            $msg_log = 'atualizou';
            $msg_email = 'ATUALIZADO CUSTO';
        }

        $process_cost->juridical_process_id = $request->process_id;
        $process_cost->r_code = $request->session()->get('r_code');
        $process_cost->type_id = $request->type_cost;
        $process_cost->description = $request->description_cost;
        $process_cost->date_release = implode('-', array_reverse(explode('/', $request->cost_date_release)));
        $process_cost->date_expiration = implode('-', array_reverse(explode('/', $request->cost_date_expiration)));

        DB::beginTransaction();
        
        try {

            $process_cost->save();

            if($request->arr_documents_cost != null) {
                $process_cost->total = $this->processCostAttach($request->arr_documents_cost, $process_cost->id, $process_cost->total, 1);
                $process_cost->save();
            }

            DB::commit();

            $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
                            ->where('user_on_permissions.perm_id', 23)
							->where('user_on_permissions.grade', 99)
                            ->select('users.*')
                            ->get();

            foreach ($users as $key) {
                $pattern = array(
                    'title' => 'CUSTO JURÍDICO: '.$process_cost->code.'',
                    'description' => nl2br(
                            "Número do processo: ". $process_cost->juridical_process()->first()->process_number 
                            ."\n Custo: ".$process_cost->juridical_type_cost()->first()->description 
                            ."\n Descrição: ".$process_cost->description
                            ."\n Total: R$ ".number_format($process_cost->total, 2,",",".") 
                            ."\n\nVeja mais informações no link abaixo: \n <a href='".$request->root() ."/juridical/process/info/". $request->process_id ."?tabName=cost'>".$request->root()."/juridical/process/info/".$request->process_id ."?tabName=cost</a>"),

                    'template' => 'misc.Default',
                    'subject' => ''.$msg_email.' JURÍDICO: '. $process_cost->code .'',
                );

                LogSystem("Colaborador ".$msg_log." custo jurídico: ". $process_cost->id, $request->session()->get('r_code'));
                NotifyUser(''.$msg_email.' JURÍDICO: ('. $process_cost->code.')', $key->r_code, 'fa-exclamation', 'text-info', 'Colaborador '.$msg_log.' processo, clique aqui para ver mais detalhes.', $request->root() .'/juridical/process/info/'. $request->process_id . '?tabName=cost');
                SendMailJob::dispatch($pattern, $key->email);
            }

            $request->session()->put('success', 'Custo salvo com sucesso!');
            return redirect()->back()->with('tabName', 'cost');

        } catch (\Exception $e) {

            DB::rollBack();
            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function processCostReceiptAjax(Request $request) {
        
        $arr_attach = JuridicalProcessCostAttach::where('juridical_process_cost_id', $request->cost_id)->get();
        if(!$arr_attach) {
            
            return response()->json([
                'success' => false,
                'message' => 'Comprovante não foi encontrado!'
            ], 400);
        } else {

            return response()->json([
                'success' => true,
                'arr_cost_attach' => $arr_attach
            ], 200);
        }
    }

    public function processExport(Request $request) {

        $process = JuridicalProcess::with('sac_client', 'juridical_type_action', 'juridical_law_firm', 'juridical_process_historic', 'juridical_process_cost')->orderBy('id', 'DESC');

        $array_input = collect([
            'exp_type_process',
            'exp_type_action',
            'exp_costumer_id',
            'exp_identity_applicant',
            'exp_name_applicant',
            'exp_law_firm_id',
            'exp_status'
        ]);
        
        $array_input = putSession($request, $array_input, 'export_');
        $filter_session = getSessionFilters('export_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."exp_type_process"){
                    $process->where('type_process', $value_filter);
                }
                if($name_filter == $filter_session[1]."exp_type_action"){
                    $process->where('type_action_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."exp_costumer_id"){
                    $process->where('costumer_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."exp_identity_applicant"){
                    $process->where('identity_applicant', $value_filter);
                }
                if($name_filter == $filter_session[1]."exp_name_applicant"){
                    $process->where('name_applicant', $value_filter);
                }
                if($name_filter == $filter_session[1]."exp_law_firm_id"){
                    $process->where('law_firm_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."exp_status"){
                    $process->where('status', $value_filter);
                }
            }
        }

        if($request->export == 1) {

            $heading = array('Processo', 'lançamento', 'Requerente', 'Seara', 'Tipo de ação', 'Ementa/Pleito', 'Último Andamento', 'Escritório', 'Total Custos','Ajuizamento', 'Status');
			$rows = array();

            foreach ($process->get() as $key) {
                $line = array();

                $line[0] = $key->process_number;
                $line[1] = date('d/m/Y', strtotime($key->date_received));
                $line[2] = $key->costumer_id != 0 ? $key->sac_client->name : $key->name_applicant;
                $line[3] = $this->getTypeProcess($key->type_process);
                $line[4] = $key->juridical_type_action->description;
                $line[5] = $key->measures_plea;
                $line[6] = $key->juridical_process_historic->count() ? $key->juridical_process_historic->last()->description: '-';
                $line[7] = $key->juridical_law_firm->name;
                $line[8] = $key->juridical_process_cost->count() ? $key->juridical_process_cost->sum('total'): '-';

                $line[9] = date('d/m/Y', strtotime($key->date_judgment));
                $line[10] = $this->getStatus($key->status);
    
                array_push($rows, $line);
            }

            return Excel::download(new DefaultExport($heading, $rows), 'ProcessExport-'. date('Y-m-d') .'.xlsx');
        }
    }

    public function lawFirmEdit(Request $request, $id) {

        if($id == 0) {
            return view('gree_i.juridical.law_firm_new', [
                'id' =>$id
            ]);    
        } else {

            $lawFirm = JuridicalLawFirm::with(['juridical_law_firm_contacts', 'juridical_law_firm_account'])->where('id', $id)->first();

            if(!$lawFirm) {
                $request->session()->put('error', 'Escritório não foi encontrado!');
                return redirect()->back(); 
            }

            $arr_contacts = $lawFirm->juridical_law_firm_contacts ? $lawFirm->juridical_law_firm_contacts: [];
            $arr_bank = $lawFirm->juridical_law_firm_account ? $lawFirm->juridical_law_firm_account: [];

            return view('gree_i.juridical.law_firm_edit', [
                'id' =>$id,
                'law_firm' => $lawFirm, 
                'arr_contacts' => $arr_contacts,
                'arr_bank' => $arr_bank
            ]);    
        }
    }    

    public function lawFirmEdit_do(Request $request) {

        if ($request->id == 0) {

            $lawFirm = new JuridicalLawFirm;
        } else {
            $lawFirm = JuridicalLawFirm::find($request->id);
            if(!$lawFirm) {
                $request->session()->put('error', 'Escritório não foi encontrado!');
                return redirect()->back(); 
            }
        }

        $lawFirm->name = $request->name;
        $lawFirm->type_people = $request->type_people;
        $lawFirm->identity = $request->identity;
        $lawFirm->address = $request->address;
        $lawFirm->city = $request->city;
        $lawFirm->state = $request->state;
        $lawFirm->complement = $request->complement;

        DB::beginTransaction();

        try {

            $lawFirm->save();

            if(!$this->juridicalEditRelation(new JuridicalLawFirmContacts, $request->arr_contacts, $lawFirm->id, 'juridical_law_firm_id', 'id', 1)) {
                throw new \Exception('Ocorreu um erro ao salvar os contatos!'); 
            }

            if(!$this->juridicalEditRelation(new JuridicalLawFirmAccount, $request->arr_bank, $lawFirm->id, 'juridical_law_firm_id', 'id', 2)) {
                throw new \Exception('Ocorreu um erro ao salvar os dados bancários!'); 
            }

            DB::commit();

            $request->session()->put('success', 'Escritório salvo com sucesso!');
            return redirect('/juridical/law/firm/list');

        } catch (\Exception $e) {

            DB::rollBack();
            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function lawFirmList(Request $request) {
          
        $law_firm = JuridicalLawFirm::with('juridical_law_firm_contacts')->orderBy('id', 'DESC');

        $array_input = collect([
            'law_firm',
            'status'
        ]);

        $array_input = putSession($request, $array_input, 'lawfirm_');
        $filter_session = getSessionFilters('lawfirm_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."law_firm"){
                    $law_firm->where('id', $value_filter);
                }
                if($name_filter == $filter_session[1]."status"){

                    $law_firm->where('is_active', $value_filter == 2? 0 : 1);
                }
            }
        }
        return view('gree_i.juridical.law_firm_list', [
            'law_firm' => $law_firm->paginate(10),
        ]);
    }

  
    private function juridicalEditRelation($model, $req, $id_parent, $id_parent_name, $id_verify, $type) {

        $request_decode =  json_decode($req);

        $request = collect($request_decode);
        $request_pluck = $request->pluck($id_verify);

        $query = $model::where(''.$id_parent_name.'', $id_parent)->pluck($id_verify);

        $delete = $query->diff($request_pluck);
        $request_pluck = $request_pluck->diff($query);

        $model::whereIn($id_verify, $delete)->where(''.$id_parent_name.'', $id_parent)->delete();

        $arr = array();

        foreach ($request_pluck as $index => $val) {

            $req_values = (array) $request[$index];
            $req_values[''.$id_parent_name.''] = $id_parent;

            array_push($arr, $req_values);
        }

        if($type == 2) {
            if($request->where('is_master', 1)->count() > 0) {

                $data = $request->where('is_master', 1)->first();

                $qr = $model::where(''.$id_parent_name.'', $id_parent)->get();
                foreach ($qr as $key) {

                    if($data->id != $key->id) {
                        $key->is_master = 0;
                        $key->save();
                    }
                }
            }
        }

        if($model->insert($arr)) {
            return true;
        } else {
            return false;
        }
    }

    public function processTypeActionList(Request $request) {

        $type_action = JuridicalTypeAction::orderBy('id', 'DESC');

        $array_input = collect([
            'description',
            'status'
        ]);
        
        $array_input = putSession($request, $array_input, 'type_action_');
        $filter_session = getSessionFilters('type_action_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."description"){
                    $type_action->where('description', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status"){
                    $type_action->where('status', $value_filter != 1 ? 0 : 1);
                }
            }
        }

        return view('gree_i.juridical.process_type_action', [
            'type_action' => $type_action->paginate(10),
        ]);
    }   

    public function processTypeActionEdit_do(Request $request) {

        if ($request->id == 0) {

            $type_action = new JuridicalTypeAction;
        } else {
            $type_action = JuridicalTypeAction::find($request->id);
            if(!$type_action) {
                $request->session()->put('error', 'Ação não foi encontrada!');
                return redirect()->back(); 
            }
        }

        $type_action->description = $request->description;
        $type_action->status = $request->status;
        
        try {

            $type_action->save();

            $request->session()->put('success', 'Tipo de ação salvo com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }

    }

    public function processTypeDocumentsList(Request $request) {
        
        $type_documents = JuridicalTypeDocument::orderBy('id', 'DESC');

        $array_input = collect([
            'description',
            'status'
        ]);
        
        $array_input = putSession($request, $array_input, 'type_doc_');
        $filter_session = getSessionFilters('type_doc_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."description"){
                    $type_documents->where('description', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status"){
                    $type_documents->where('status', $value_filter != 1 ? 0 : 1);
                }
            }
        }
        
        return view('gree_i.juridical.process_type_documents', [
            'type_documents' => $type_documents->paginate(10),
        ]);
    }    

    public function processTypeDocumentsEdit_do(Request $request) {

        if ($request->id == 0) {

            $type_documents = new JuridicalTypeDocument;
        } else {
            $type_documents = JuridicalTypeDocument::find($request->id);
            if(!$type_documents) {
                $request->session()->put('error', 'Tipo de documento não foi encontrado!');
                return redirect()->back(); 
            }
        }

        $type_documents->description = $request->description;
        $type_documents->status = $request->status;
        
        try {

            $type_documents->save();

            $request->session()->put('success', 'Tipo de documento cadastrado com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }

    }

    public function processTypeCostList(Request $request) {
        
        $type_cost = JuridicalTypeCost::orderBy('id', 'DESC');

        $array_input = collect([
            'description',
            'status'
        ]);
        
        $array_input = putSession($request, $array_input, 'type_cost_');
        $filter_session = getSessionFilters('type_cost_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."description"){
                    $type_cost->where('description', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status"){
                    $type_cost->where('status', $value_filter != 1 ? 0 : 1);
                }
            }
        }
        
        return view('gree_i.juridical.process_type_cost', [
            'type_cost' => $type_cost->paginate(10),
        ]);
    } 

    public function processTypeCostEdit_do(Request $request) {

        if ($request->id == 0) {

            $type_cost = new JuridicalTypeCost;
        } else {
            $type_cost = JuridicalTypeCost::find($request->id);
            if(!$type_cost) {
                $request->session()->put('error', 'Tipo de custo não foi encontrado!');
                return redirect()->back();
            }
        }

        $type_cost->description = $request->description;
        $type_cost->status = $request->status;
        
        try {

            $type_cost->save();

            $request->session()->put('success', 'Tipo de custo cadastrado com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function processTypeCostEditAjax(Request $request) {

        $type_cost = new JuridicalTypeCost;
        $type_cost->description = $request->description;
        $type_cost->status = $request->status;

        try {

            $type_cost->save();

            return response()->json([
                'success' => true,
                'message' => 'Tipo cadastrado com sucesso!'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function lawFirmCostList(Request $request, $id) {

        $law_cost = JuridicalLawFirmCost::where('juridical_law_firm_id', $id)->orderBy('id', 'DESC');
        $law_firm = JuridicalLawFirm::find($id);
        $type_cost = JuridicalTypeCost::where('status', 1)->get();

        $date_month = date('m');
        $date_year = date('Y');

        $array_input = collect([
            'f_month_year_submit',
            'f_cost_type',
            'f_type_detail',
            'f_status'
        ]);

        $array_input = putSession($request, $array_input, 'lawcost_');
        $filter_session = getSessionFilters('lawcost_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."f_month_year_submit"){

                    $date = Carbon::createFromFormat('Y-m-d', $value_filter);
                    $date_month = $date->month;
                    $date_year = $date->year;  
                } 
                if($name_filter == $filter_session[1]."f_cost_type"){

                    $law_cost->where('type_cost', $value_filter);
                }
                if($name_filter == $filter_session[1]."f_type_detail"){

                    $law_cost->where('type_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."f_status"){

                    $law_cost->where('status', $value_filter != 'opt_0' ? $value_filter : 0 );
                }
            }
        }

        $law_cost->whereYear('date_release', $date_year);
        $law_cost->whereMonth('date_release', $date_month);

        return view('gree_i.juridical.law_firm_cost', [
            'law_firm_id' => $id,
            'law_cost' => $law_cost->paginate(10),
            'type_cost' => $type_cost,
            'honorarium' => $law_cost->get()->where('type_cost', 1)->sum('total'),
            'expenses' => $law_cost->get()->where('type_cost', 2)->sum('total'),
            'law_firm' => $law_firm,
            'month_year_actual' => $date_month .'/'. $date_year
        ]);
    }

    public function lawFirmCostEdit_do(Request $request) {

        if ($request->id == 0) {

            $law_cost = new JuridicalLawFirmCost;
            $law_cost->code = getCodeModule('juridical_law');
        } else {
            
            $law_cost = JuridicalLawFirmCost::find($request->id);
            if(!$law_cost) {
                $request->session()->put('error', 'Custo do escritório não foi encontrado!');
                return redirect()->back();
            }
        }

        $law_cost->juridical_law_firm_id = $request->law_firm_id;
        $law_cost->r_code = $request->session()->get('r_code');
        $law_cost->type_cost = $request->type_cost;
        $law_cost->type_id = $request->type_id;
        $law_cost->description = $request->description_cost;
        $law_cost->date_release = implode('-', array_reverse(explode('/', $request->cost_date_release)));
        $law_cost->date_expiration = implode('-', array_reverse(explode('/', $request->cost_date_expiration)));
  
        DB::beginTransaction();
        
        try {

            $law_cost->save();

            if($request->arr_documents_law != null) {
                $law_cost->total = $this->processCostAttach($request->arr_documents_law, $law_cost->id, $law_cost->total, 2);
                $law_cost->save();
            }

            DB::commit();
            
            $request->session()->put('success', 'Custo de escritório salvo com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            DB::rollBack();
            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    
	public function processMonitor() {

        $process = DB::table('juridical_process')
                ->select(DB::raw('juridical_process.*, count(juridical_process.id) as total_type, (Select sum(total) from juridical_process_cost where is_paid = 1) as total'))
                //->whereYear('juridical_process.date_received', date('Y'))
                ->groupBy('juridical_process.type_process')
                ->get();

        $law = JuridicalLawFirm::with(['juridical_process' => function($query) {
            $query->whereYear('date_received', date('Y'));
        }])->get();        

        $law_total = JuridicalLawFirmCost::where('is_paid', 1)->sum('total');
        //$totals_process = JuridicalProcess::whereYear('date_received', date('Y'))->get();
		$totals_process = JuridicalProcess::all();
		
        $type_process = array(
            ['type' => 1,'name' => 'Consumidor','total' => 0], 
            ['type' => 2,'name' => 'Trabalhista','total' => 0],
            ['type' => 3,'name' => 'Cível','total' => 0],
            ['type' => 4,'name' => 'Penal','total' => 0],
            ['type' => 5,'name' => 'Tributário','total' => 0],
            ['type' => 6,'name' => 'Administrativo','total' => 0],
        );

        foreach($process as $key) {
            $index = $key->type_process - 1;
            $type_process[$index]['total'] = $key->total_type;
        }

        $law_process = [];
        foreach($law as $item) {
            $arr =  array (
                'id' => $item->id,
                'name' => $item->name,
                'total' => $item->juridical_process->count()
            );
            $law_process[] = $arr;
        }    

        return view('gree_i.juridical.process_dashboard', [
            'total_progress' => $totals_process->where('status', 1)->count(),
            'total_suspended' => $totals_process->where('status', 2)->count(),
            'total_closed' => $totals_process->where('status', 3)->count(),
            'total_sentence' => $totals_process->where('status', 4)->count(),
            'process_cost' => $process->count() > 0 ? $process[0]->total : 0,
            'law_cost' => $law_total,
            'type_process' => $type_process,
            'law_process' => $law_process,
            'range_year' => range(2020, date('Y'))
        ]);
    }	

    public function processCostList(Request $request) {

        if($request->process_id == 0) {
            $process_cost = JuridicalProcessCost::with('juridical_type_cost', 'juridical_process')->orderBy('id', 'DESC');
        } else {
            $process_cost = JuridicalProcessCost::with('juridical_type_cost', 'juridical_process')->where('juridical_process_id', $request->process_id)->orderBy('id', 'DESC');
        }

        $type_cost = JuridicalTypeCost::all();

        $array_input = collect([
            'code_cost',
            'process_number',
            'cost_date_release_submit',
            'type_cost',
            'cost_date_expiration_submit',
            'status'
        ]);
        
        $array_input = putSession($request, $array_input, 'cost_');
        $filter_session = getSessionFilters('cost_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code_cost"){
                    $process_cost->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."process_number"){

                    $teste = $process_cost->whereHas('juridical_process', function ($query) use ($value_filter) {
                        $query->where('process_number', $value_filter);
                    });
                }
                if($name_filter == $filter_session[1]."cost_date_release_submit"){
                    $process_cost->whereDate('date_release', $value_filter);
                }
                if($name_filter == $filter_session[1]."type_cost"){
                    $process_cost->where('type_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."cost_date_expiration_submit"){
                    $process_cost->whereDate('date_expiration', $value_filter);
                }
                if($name_filter == $filter_session[1]."status"){
                    $process_cost->where('is_paid', $value_filter == 'not_paid' ? 0 : $value_filter);
                }
            }
        }

        if($request->export == 1) {

            $heading = array('Código', 'lançamento', 'Tipo Custo', 'Descrição', 'Total', 'Vencimento', 'Status');
			$rows = array();

            foreach ($process_cost->get() as $key) {
                $line = array();

                $line[0] = $key->code;
                $line[1] = date('d/m/Y', strtotime($key->date_release));
                $line[2] = $key->juridical_type_cost->description;
                $line[3] = $key->description;
                $line[4] = 'R$ '.number_format($key->total, 2,",",".");
                $line[5] = date('d/m/Y', strtotime($key->date_expiration));
                $line[6] = $key->is_paid == 1 ? 'Pago' : 'Não Pago';
                
                array_push($rows, $line);
            }

            return Excel::download(new DefaultExport($heading, $rows), 'ProcessCostExport-'. date('Y-m-d') .'.xlsx');
        }
        
        return view('gree_i.juridical.process_cost_list', [
            'process_cost' => $process_cost->paginate(10),
            'type_cost' => $type_cost
        ]);
    }

    public function lawCostList(Request $request) {

        $law_cost = JuridicalLawFirmCost::with('juridical_law_firm', 'juridical_type_cost')->orderBy('id', 'DESC');
        $type_cost = JuridicalTypeCost::all();

        $array_input = collect([
            'code_cost',
            'f_month_year_submit',
            'f_cost_type',
            'f_type_detail',
            'f_status'
        ]);

        $array_input = putSession($request, $array_input, 'lawcost_');
        $filter_session = getSessionFilters('lawcost_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code_cost"){
                    $law_cost->where('code', $value_filter);
                }
                if($name_filter == $filter_session[1]."f_month_year_submit"){

                    $date = Carbon::createFromFormat('Y-m-d', $value_filter);
                    $date_month = $date->month;
                    $date_year = $date->year;  

                    $law_cost->whereMonth('date_release', $date_month);
                    $law_cost->whereYear('date_release', $date_year);
                } 
                if($name_filter == $filter_session[1]."f_cost_type"){

                    $law_cost->where('type_cost', $value_filter);
                }
                if($name_filter == $filter_session[1]."f_type_detail"){

                    $law_cost->where('type_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."f_status"){

                    $law_cost->where('is_paid', $value_filter != 'not_paid' ? $value_filter : 0 );
                }
            }
        }

        if($request->export == 1) {

            $heading = array('Código', 'Escritório', 'Lançamento', 'Tipo', 'Detalhe', 'Descrição', 'Total', 'vencimento', 'status');
			$rows = array();

            foreach ($law_cost->get() as $key) {
                $line = array();

                $line[0] = $key->code;
                $line[1] = $key->juridical_law_firm->name;
                $line[2] = date('d/m/Y', strtotime($key->date_release));
                $line[3] = $key->type_cost == 1 ? 'Honorário' : 'Despesa';
                $line[4] = $key->juridical_type_cost->description;
                $line[5] = $key->description;
                $line[6] = 'R$ '.number_format($key->total, 2,",",".");
                $line[7] = date('d/m/Y', strtotime($key->date_expiration));
                $line[8] = $key->is_paid == 1 ? 'Pago' : 'Não Pago';
                
                array_push($rows, $line);
            }

            return Excel::download(new DefaultExport($heading, $rows), 'CostLawExport-'. date('Y-m-d') .'.xlsx');
        }

        return view('gree_i.juridical.law_cost_list', [
            'law_cost' => $law_cost->paginate(10),
            'type_cost' => $type_cost
        ]);
    }    

    public function lawFirmCostReceiptAjax(Request $request) {
        
        $arr_attach = JuridicalLawFirmCostAttach::where('juridical_law_firm_cost_id', $request->cost_id)->get();

        if(!$arr_attach) {
            
            return response()->json([
                'success' => false,
                'message' => 'Comprovante não foi encontrado!'
            ], 400);
        } else {

            return response()->json([
                'success' => true,
                'arr_cost_attach' => $arr_attach
            ], 200);
        }
    }

    public function processMonitorFilterAjax(Request $request) {

        $month = $request->month;
        $year = $request->year;

        if ($request->block == 1) {

            $process = DB::table('juridical_process')
                ->select(DB::raw('juridical_process.*, count(juridical_process.id) as total_type, (Select sum(total) from juridical_process_cost where is_paid = 0) as total'))
                ->where(function ($query) use ($month) {
                    if($month) {
                        $query->whereMonth('juridical_process.date_received', $month);
                    }
                })
                ->whereYear('juridical_process.date_received', $request->year)
                ->groupBy('juridical_process.type_process')
                ->get();
             
            $type_process = array(
                ['type' => 1,'name' => 'Consumidor','total' => 0], 
                ['type' => 2,'name' => 'Trabalhista','total' => 0],
                ['type' => 3,'name' => 'Cível','total' => 0],
                ['type' => 4,'name' => 'Penal','total' => 0],
                ['type' => 5,'name' => 'Tributário','total' => 0],
                ['type' => 6,'name' => 'Administrativo','total' => 0],
            );    
        
            foreach($process as $key) {
                $index = $key->type_process - 1;
                $type_process[$index]['total'] = $key->total_type;
            }        

            return response()->json([
                'type_process' => $type_process
            ], 200);

        } else if ($request->block == 2) {

            $law = JuridicalLawFirm::with(['juridical_process' => function($query) use ($month, $year) {
                $query->where(function ($qr) use ($month) {
                    if($month) {
                        $qr->whereMonth('date_received', $month);
                    }
                })
                ->whereYear('date_received', $year);
            }])->get();

            $law_process = [];
            foreach($law as $item) {
                $arr =  array (
                    'id' => $item->id,
                    'name' => $item->name,
                    'total' => $item->juridical_process->count()
                );
                $law_process[] = $arr;
            }  

            return response()->json([
                'law_process' => $law_process
            ], 200);
        } 
    }

    public function processPaymentAttach(Request $request) {

        if ($request->hasFile('payment_attach_file')) {
            
            $response = $this->uploadS3(1, $request->payment_attach_file, $request);

            if ($response['success']) {

                $payment = JuridicalProcessCost::find($request->payment_cost_id);

                if(!$payment) {

                    $request->session()->put('success', 'Custo não encontrado!');
                    return redirect()->back()->with('tabName', 'cost');
                }

                $payment->receipt = $response['url'];
                $payment->payment_description = $request->payment_description;
                $payment->is_paid = 1;
                $payment->save();

                $request->session()->put('success', 'Comprovante adicionado com sucesso!');
                return redirect()->back()->with('tabName', 'cost');

            } else {

                $request->session()->put('success', 'Não foi possível fazer upload do arquivo!');
                return redirect()->back()->with('tabName', 'cost');
            }
        }  else {

            $request->session()->put('success', 'Arquivo não adicionado para upload!');
            return redirect()->back()->with('tabName', 'cost');
        }
    }

    public function lawFirmPaymentAttach(Request $request) {

        if ($request->hasFile('payment_attach_file')) {
            
            $response = $this->uploadS3(1, $request->payment_attach_file, $request);
            if ($response['success']) {

                $payment = JuridicalLawFirmCost::find($request->payment_cost_id);

                if(!$payment) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Custo não encontrado!'
                    ], 400);
                }

                $payment->receipt = $response['url'];
                $payment->payment_description = $request->payment_description;
                $payment->is_paid = 1;
                $payment->save();

                $request->session()->put('success', 'Comprovante adicionado com sucesso!');
                return redirect()->back();

            } else {

                $request->session()->put('success', 'Não foi possível fazer upload do arquivo!');
                return redirect()->back();
            }
        }  else {

            $request->session()->put('success', 'Arquivo não adicionado para upload!');
            return redirect()->back();
        }
    }

    public function processHistoricNotification(Request $request) {

        $date_actual = new Carbon();
        $date_notify = new Carbon(Carbon::createFromFormat('d/m/Y', $request->date_notification)->format('Y-m-d'));
        $date_before = (new Carbon($date_notify))->subDays(3);

        $seconds_notify_before = $date_actual->diffInSeconds($date_before);
        $seconds_notify = $date_actual->diffInSeconds($date_notify);
        
        $historic = JuridicalProcessHistoric::find($request->notify_history_id);
        if(!$historic) {
            $request->session()->put('error', 'Andamento não foi encontrado!');
            return redirect()->back();
        }

        $process = $historic->juridical_process()->first();             
        
        try {                    

            $historic->date_notify = $date_notify;
            $historic->description_notify = $request->description_notification;
            $historic->is_notify = 1;
            $historic->save();

            $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
                            ->where('user_on_permissions.perm_id', 23)
							->where('user_on_permissions.grade', 99)
                            ->select('users.*')->get();

            foreach ($users as $key) {
                $pattern = array(
                    'title' => 'ANDAMENTO JURÍDICO: '.$historic->title.'',
                    'description' => nl2br("Número do processo: ".$process->process_number.
                                            "\n Título andamento: ".$historic->title.
                                            "\n Descrição notificação: \n".$request->description_notification.
                                            "\n Data de notificação: ".$request->date_notification.
                                            "\n\nVeja mais informações no link abaixo: \n <a href='".$request->root()."/juridical/process/info/".$process->id."'>".$request->root()."/juridical/process/info/".$process->id."</a>"),

                    'template' => 'misc.Default',
                    'subject' => 'NOTIFICAÇÃO DE ANDAMENTO JURÍDICO: '.$historic->title.'',
                );
                
                SendMailJob::dispatch($pattern, $key->email)->delay($seconds_notify_before);
                SendMailJob::dispatch($pattern, $key->email)->delay($seconds_notify);
            }

            $request->session()->put('success', 'Notificação agendada com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('success', $e->getMessage());
            return redirect()->back();
        }    
    }

    public function  processImport(Request $request) {

        $law_firm = JuridicalLawFirm::all();

        return view('gree_i.juridical.process_import', [
            'law_firm' => $law_firm
        ]);
    }

    public function processImport_do(Request $request) {

        set_time_limit(360);
        if ($request->hasFile('attach')) {
            $extension = $request->attach->extension();
        
            $validator = Validator::make(
                [
                    'file'      => $request->attach,
                    'extension' => strtolower($request->attach->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required|max:1024',
                    'extension'      => 'required|in:csv,xlsx,xls',
                ]
            );
            
            if ($validator->fails()) {
                
                $request->session()->put('error', "Tamanho do arquivo não pode exceder 1MB!");
                return redirect()->back();
            } else {
        
                try {
                    Excel::import(new ProcessImport($request), $request->file('attach'));
        
                    LogSystem("Colaborador importou processos", $request->session()->get('r_code'));
                    $request->session()->put('success', "Processos importados com sucesso!");

                    return Redirect('/juridical/process/list');
                }
                catch (\Exception $e) {
                    $request->session()->put('error', $e->getMessage());
                    return redirect()->back();
                }
            } 
        }
    }

    private function processDocuments($data, $process_id) {

        $arr_req = json_decode($data);

        foreach ($arr_req as $key) {
            
            if($key->id == 0) {

                $documents = new JuridicalProcessDocuments;

                $documents->juridical_process_historic_id = $process_id;
                $documents->url = $key->url;
                $documents->juridical_type_document_id = $key->id_type_document;
                $documents->save();    
            }
        }    
    }

    private function processCostAttach($data, $process_cost_id, $total, $type) {

        $arr_req = json_decode($data, true);
        
        foreach ($arr_req as $indx => $key) {            

            if($key['id'] == 0) {

                if($type == 1) {

                    $documents = new JuridicalProcessCostAttach;
                    $documents->juridical_process_cost_id = $process_cost_id;
                } else if($type == 2) {

                    $documents = new JuridicalLawFirmCostAttach;
                    $documents->juridical_law_firm_cost_id = $process_cost_id;
                }

                $documents->description = $key['description'];
                $documents->value = $key['value'];
                $documents->url = $key['url'];
                $documents->save();

                $total = $total + $documents->value;
            }    
        }
        return $total;
    }

    private function getTypeProcess($type) {
        $type_process = array(
            1 => 'Consumidor',
            2 => 'Trabalhista',
            3 => 'Cível',
            4 => 'Penal',
            5 => 'Tributário',
            6 => 'Adminstrativo'
        );
        return $type_process[$type];
    }    

    private function getStatus($status) {
        $arr_status = array(
            0 => 'Cadastrado',
            1 => 'Em andamento',
            2 => 'Suspenso',
            3 => 'Arquivado',
            4 => 'Senteciado',
        );
        return $arr_status[$status];
    }
    
    private function getTypeProcessName($type) {

        $arr = array(
            1 => ['REQUERENTE', 'REQUERIDO'],
            2 => ['RECLAMANTE', 'RECLAMADO'],
            3 => ['REQUERENTE', 'REQUERIDO'],
            4 => ['AUTOR', 'RÉU'],
            5 => ['REQUERENTE', 'REQUERIDO'],
            6 => ['ORGÃO / ESTADO', 'REQUERIDO'],
        );
        return $arr[$type];
    }

    private function style_border($count) {

        $arr = [];
        $row1 = 1;
        $row2 = 1;
        
        for ($i=0; $i < $count; $i++) { 

            $row1 = $row2 + 1;
            $row2 = $row2 + 2;

            $borders = [
                'A'.$row1.':H'.$row2.'' => [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFF0000'],
                        ],
                    ]
                ]    
            ];

            $arr = array_merge($arr, $borders);
        }   
        return $arr;
    }
}