<?php

namespace App\Http\Controllers;

use App\Exports\DefaultExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App;
use App\Model\TiMaintenance;
use App\Model\TiMaintenanceCategories;
use App\Model\TiMaintenanceNotes;
use App\Model\TiMaintenanceReplies;
use App\Model\TiMaintenanceAssigned;
use App\Model\TiMaintenanceTimer;
use App\Model\Users;

use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;

use Carbon\Carbon;

class TiMaintenanceController extends Controller
{

    public function MaintenanceList(Request $request) {

    $users = Users::all();
    $categories =  TiMaintenanceCategories::all();

    $maintenance = TiMaintenance::with('category', 'users');

    if (!hasPermManager(4)) {
        $maintenance->where('request_r_code', $request->session()->get('r_code'));
    }

    $array_input = collect([
        'track_id',
        'r_code',
        'category',
        'status',
		'start_date',
		'end_date',
    ]);

    $array_input = putSession($request, $array_input, 'maint_');
    $filtros_sessao = getSessionFilters('maint_');

    if($filtros_sessao[0]->isNotEmpty()){
        foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
            if($nome_filtro == $filtros_sessao[1]."track_id"){
                $maintenance->where('ti_maintenance.trackid', 'like', '%'. $valor_filtro .'%');
            }
            if($nome_filtro == $filtros_sessao[1]."r_code"){
                $maintenance->where('ti_maintenance.request_r_code', $valor_filtro);
            }
            if($nome_filtro == $filtros_sessao[1]."category"){
                $maintenance->where('ti_maintenance.category_id', $valor_filtro);
            }
            if($nome_filtro == $filtros_sessao[1]."status"){
                $maintenance->where('ti_maintenance.status', $valor_filtro);
            }
			if($nome_filtro == $filtros_sessao[1]."start_date") {
                $maintenance->whereDate('ti_maintenance.created_at', '>=', $valor_filtro);
            }
			if($nome_filtro == $filtros_sessao[1]."end_date") {
                $maintenance->whereDate('ti_maintenance.created_at', '<=', $valor_filtro);
            }
        }
    }

    $maintenance->orderBy('created_at', 'DESC');
		
	if ($request->export) {
		$heading = array(
			'Rastreio ID',
			'Assunto',
			'Categoria',
			'Solicitante',
			'Técnico',
			'Setor',
			'Prioridade',
			'Status',
			'Criado em',
			'Hora criação',
			'Última atualização',
		);
		
		$rows = array();
		foreach ($maintenance->get() as $val) {
			$line = array();
			
			$users = [];
			if ($val->assigns->count()) {
				foreach ($val->assigns as $key) {
					$users[] = $key->Users->full_name;
				}
			}				
			
			$line[0] = $val->trackid;
			$line[1] = $val->subject;
			$line[2] = $val->category ? $val->category->name : '';
			$line[3] = $val->users ? $val->users->full_name : '';
			$line[4] = implode(', ', $users);
			$line[5] = $val->sector_name;
			$line[6] = $val->priority_name;
			$line[7] = $val->status_name;
			$line[8] = date('d/m/Y', strtotime($val->created_at));
			$line[9] = date('H:i', strtotime($val->created_at));
			$line[10] = date('d/m/Y', strtotime($val->updated_at));
			
			array_push($rows, $line);
		}
	
		ob_clean();
		return Excel::download(new DefaultExport($heading, $rows), 'TISuppportExport-'. date('Y-m-d') .'.xlsx');
	}
    
    return view('gree_i.ti.ti_maintenance_list', [
        'maintenance' => $maintenance->paginate(10),
        'users' => $users,
        'categories' => $categories
    ]);
}    

    public function MaintenanceEdit(Request $request, $id) {
    
        $users = Users::all();
        $categories =  TiMaintenanceCategories::all();
    
        if ($id == 0) {

            $request_r_code = $request->session()->get('r_code');
            $category = null;
            $priority = 1;
            $unit = 1;
            $sector = '';
            $status = 1;
            $ext_phone = "";
            $access_comp = "";
            $subject = "";
            $message = "";
            $attach = "";
            $printer_model = "";
            $toner_model = "";
            $start_reserve = "";
            $final_reserve = "";
        } else {

            $maintenance = TiMaintenance::find($id);
            if ($maintenance) {
                $request_r_code = $maintenance->request_r_code;
                $category = $maintenance->category_id;
                $priority = $maintenance->priority;
                $unit = $maintenance->unit;
                $sector = $maintenance->sector;
                $status = $maintenance->status;
                $ext_phone = $maintenance->ext_phone;
                $access_comp = $maintenance->access_comp;
                $subject = $maintenance->subject;
                $message = $maintenance->message;
                $attach = $maintenance->attach;
                $printer_model = $maintenance->printer_model;
                $toner_model = $maintenance->toner_model;
                $start_reserve = $maintenance->start_reserve;
                $final_reserve = $maintenance->final_reserve;
            } else {
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return redirect('/news');
            }
        }

        return view('gree_i.ti.ti_maintenance_edit', [
            'id' => $id,
            'category' => $category,
            'priority' => $priority,
            'unit' => $unit,
            'sector' => $sector,
            'status' => $status,
            'ext_phone' => $ext_phone,
            'access_comp' => $access_comp,
            'subject' => $subject,
            'message' => $message,
            'attach' => $attach,
            'request_r_code' => $request_r_code,
            'users' => $users,
            'categories' => $categories,
            'printer_model' => $printer_model,
            'toner_model' => $toner_model,
            'start_reserve' => $start_reserve,
            'final_reserve' => $final_reserve
        ]);
    }

    public function MaintenanceEdit_do(Request $request) {

        if ($request->id == 0) {

            $maintenance = new TiMaintenance;
        } else {
            $maintenance = TiMaintenance::find($request->id);
            if(!$maintenance) {
                $request->session()->put('error', 'Atendimento não foi encontrado!');
                return redirect()->back(); 
            }
        }

        $maintenance->request_r_code = $request->request_r_code;
        $maintenance->category_id = $request->category;
        $maintenance->priority = $request->priority;
        $maintenance->subject = $request->subject;
        $maintenance->message = $request->message;
        $maintenance->registred_r_code = $request->session()->get('r_code');
        $maintenance->status = $request->status;
        $maintenance->ext_phone = $request->ext_phone;
        $maintenance->access_comp = $request->access_comp;
        $maintenance->sector = $request->sector;
        $maintenance->unit = $request->unit;
        $maintenance->printer_model = $request->printer_model == '' ? 0 : $request->printer_model;
        $maintenance->toner_model = $request->toner_model == '' ? 0 : $request->toner_model;
        $maintenance->start_reserve = $request->start_reserve;
        $maintenance->final_reserve = $request->final_reserve;

        if ($request->hasFile('attach')) {
            $response = uploadS3(1, $request->attach, $request);
            if ($response['success']) {
                $maintenance->attach = $response['url'];
            } else {
                return redirect()->back();
            }
        }

        $maintenance->save();

		// GEN CODE TICKET
		$gen = strtoupper(substr(md5($maintenance->id), 0, 10));
		$maintenance->trackid = substr_replace($gen, '-', 5, 0);
        $maintenance->save();
        
        if($maintenance->id) {
            $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
                ->where('user_on_permissions.perm_id', 4)
                ->where('user_on_permissions.grade', 99)
                ->where('user_on_permissions.can_approv', 0)
                ->select('users.*')
                ->get();

            $first_name = $maintenance->users()->first()->first_name;
            $last_name = $maintenance->users()->first()->last_name;    
			
			if($request->priority == 1) {
                $priority = 'Prioridade Baixa: Até 72 horas úteis';
            } 
            elseif($request->priority == 2) {
                $priority = 'Prioridade Média: Até 48 horas úteis';
            }
            else {
                $priority = 'Prioridade Baixa: Até 24 horas úteis';
            }
			
			$pattern = array(
                'title' => ''.$maintenance->subject.'',
                'description' => nl2br("<span style='color:#ff0000;'><b>".$priority."</b></span>\n
                                        ID Rastreio: (". $maintenance->trackid .")\n 
                                        Assunto: ".$maintenance->subject."\n 
                                        Solicitante: ".$first_name." ".$last_name."\n
                                        Descrição: \n".$maintenance->message." \n
                                        Veja mais informações no link abaixo: \n 
                                        <a href='". $request->root() ."/ti/maintenance/info/". $maintenance->id ."'>Atendimento: ". $maintenance->trackid ."</a>\n\n
                                        <span style='color:#ff0000;'>
                                        <b>Observações:</b>\n
                                        * Fiquem atentos as atualizações dos chamados.
                                        * Solicitações relacionadas a estrutura física e predial, “Câmeras, cabeamento de rede e telefonia,” sujeito a análise.
                                        * Após a abertura do chamado Favor respeitar o tempo de atendimento.
                                        </span>"),
                                        
                'template' => 'misc.Default',
                'subject' => 'Novo atendimento: '. $maintenance->subject .'',
            );

            foreach ($users as $key) {
				
                NotifyUser('Suporte e Manutenção: Novo Atendimento ('. $maintenance->trackid.')', $key->r_code, 'fa-exclamation', 'text-info', 'Foi solicitado um novo atendimento, clique aqui para ver mais detalhes.', $request->root() .'/ti/maintenance/info/'. $maintenance->id);
                SendMailJob::dispatch($pattern, $key->email);
            }
			
			SendMailJob::dispatch($pattern, $request->session()->get('email'));
        }

        LogSystem("Colaborador criou novo atendimento para o setor de TI - Suporte e Manutenção: ID #". $maintenance->id, $request->session()->get('r_code'));
        $request->session()->put('success', 'Atendimento criado com sucesso!');
        return redirect('/ti/support');
    }    

    public function MaintenanceInfo(Request $request, $id) {

        $maintenance = TiMaintenance::with('users')->find($id);
        $categories =  TiMaintenanceCategories::all();
        
        $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
            ->where('user_on_permissions.perm_id', 4)
            ->select('users.*')
            ->get();
        
        if ($maintenance) {
            $trackid = $maintenance->trackid;
            $request_r_code = $maintenance->request_r_code;
            $category = $maintenance->category_id;
            $priority = $maintenance->priority;
            $unit = $maintenance->unit;
            $sector = $maintenance->sector;
            $status = $maintenance->status;
            $ext_phone = $maintenance->ext_phone;
            $access_comp = $maintenance->access_comp;
            $subject = $maintenance->subject;
            $message = $maintenance->message;
            $attach = $maintenance->attach;
            $created_at = $maintenance->created_at;
            $updated_at = $maintenance->updated_at;
            $first_name = $maintenance->users ? $maintenance->users->first_name : '';
            $last_name = $maintenance->users ? $maintenance->users->last_name : '';
            $printer_model = $maintenance->printer_model;
            $toner_model = $maintenance->toner_model;
            $start_reserve = $maintenance->start_reserve;
            $final_reserve = $maintenance->final_reserve;
            $start_time = $maintenance->start_time_job;
            $stop_time = $maintenance->pause_time_job;
            $end_time = $maintenance->final_time_job;
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return redirect('/news');
        }

        $actual_time = Carbon::now();
        $start_time = new Carbon($start_time);
		
		if ($stop_time != null)
		$start_time->diffInSeconds(new Carbon($stop_time));
		else
		$start_time->diffInSeconds(Carbon::now());
			
			
        return view('gree_i.ti.ti_maintenance_info', [
            'maintenance' => $maintenance,
            'trackid' => $trackid,
            'maintenance_id' => $id,
            'category' => $category,
            'priority' => $priority,
            'unit' => $unit,
            'sector' => $maintenance->sector_name,
            'status' => $status,
            'ext_phone' => $ext_phone,
            'access_comp' => $access_comp,
            'subject' => $subject,
            'message' => $message,
            'attach' => $attach,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'request_r_code' => $request_r_code,
            'categories' => $categories,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'users' => $users,
            'printer_model' => $maintenance->printer_model_name,
            'toner_model' => $maintenance->toner_model_name,
            'start_reserve' => $start_reserve,
            'final_reserve' => $final_reserve,
            'start_time' => $start_time->diffInSeconds($actual_time),
            'stop_time' => $start_time->diffInSeconds($stop_time),
            'end_time' => $start_time->diffInSeconds($end_time)
        ]);
    }

    public function MaintenanceNote_do(Request $request) {
        
        if ($request->id == 0) {

            $note = new TiMaintenanceNotes;
        } else {
            $note = TiMaintenanceNotes::find($request->id);
            if(!$note) {
                $request->session()->put('error', 'Nota não foi encontrada!');
                return redirect()->back(); 
            }
        }

        $note->maintenance_id = $request->maintenance_id;
        $note->r_code_edit = $request->session()->get('r_code');
        $note->message = $request->message;

        if ($request->hasFile('attach')) {
            $response = uploadS3(1, $request->attach, $request);
            if ($response['success']) {
                $note->attach = $response['url'];
            } else {
                return redirect()->back();
            }
        }

        $note->save();

        LogSystem("Colaborador criou nova nota de atendimento para o setor de TI - Suporte e Manutenção: ID #". $request->maintenance_id, $request->session()->get('r_code'));
        $request->session()->put('success', 'Nota criada com sucesso!');
        return redirect('/ti/maintenance/info/'.$request->maintenance_id.'');
    }    

    public function MaintenanceReplies_do(Request $request) {
        if ($request->id == 0) {

            $replie = new TiMaintenanceReplies;
        } else {
            $replie = TiMaintenanceReplies::find($request->id);
            if(!$replie) {
                $request->session()->put('error', 'Nota não foi encontrada!');
                return redirect()->back(); 
            }
        }

        $replie->maintenance_id = $request->maintenance_id;
        $replie->r_code_reply = $request->session()->get('r_code');
        $replie->message = $request->message;

        if ($request->hasFile('attach')) {
            $response = uploadS3(1, $request->attach, $request);
            if ($response['success']) {
                $replie->attach = $response['url'];
            } else {
                return redirect()->back();
            }
        }

        $replie->save();
        
        if ($replie->id) {

            $maintenance = TiMaintenance::with('users')->where('id', $replie->maintenance_id)->first();
			
			if($maintenance->priority == 1) {
                $priority = 'Prioridade Baixa: Até 72 horas úteis';
            } 
            elseif($maintenance->priority == 2) {
                $priority = 'Prioridade Média: Até 48 horas úteis';
            }
            else {
                $priority = 'Prioridade Baixa: Até 24 horas úteis';
            }

            if ($maintenance->request_r_code == $request->session()->get('r_code')) {    

                // EMAIL PARA EQUIPE
                $assigneds = TiMaintenanceAssigned::with('users')->where('maintenance_id', $replie->maintenance_id)->get();

                if(count($assigneds) > 0) {
                    foreach ($assigneds as $key) {

                        $pattern = array(
                            'title' => ''.$maintenance->subject.'',
                            'description' => nl2br("<span style='color:#ff0000;'><b>".$priority."</b></span>\n
                                                    Nova resposta do atendimento foi enviado: \n 
                                                    ID Rastreio: (". $maintenance->trackid .")\n 
                                                    Assunto: ".$maintenance->subject."\n 
                                                    Solicitante: ".$maintenance->Users->first_name." ".$maintenance->Users->last_name."\n\n <b>
                                                    Resposta enviada do solicitante</b>: \n".$replie->message." \n\n
                                                    Veja mais informações no link abaixo: \n 
                                                    <a href='". $request->root() ."/ti/maintenance/info/". $maintenance->id ."'>Atendimento: ". $maintenance->trackid ."</a>
                                                    <span style='color:#ff0000;'>
                                                    <b>Observações:</b>\n
                                                    * Fiquem atentos as atualizações dos chamados.
                                                    * Solicitações relacionadas a estrutura física e predial, “Câmeras, cabeamento de rede e telefonia,” sujeito a análise.
                                                    * Após a abertura do chamado Favor respeitar o tempo de atendimento.
                                                    </span>"),

                            'template' => 'misc.Default',
                            'subject' => 'Nova resposta do atendimento: '. $maintenance->subject .'',
                        );
						
                        NotifyUser('Suporte e Manutenção: Nova resposta do atendimento ('. $maintenance->trackid.')', $key->users->r_code, 'fa-exclamation', 'text-info', 'Foi solicitado um novo atendimento, clique aqui para ver mais detalhes.', $request->root() .'/ti/maintenance/info/'. $maintenance->id);
                        SendMailJob::dispatch($pattern, $key->users->email);
                    }
                } else {
                    $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
                            ->where('user_on_permissions.perm_id', 4)
                            ->select('users.*')
                            ->get();

                    foreach ($users as $key) {
						
                        $pattern = array(
                            'title' => ''.$maintenance->subject.'',
                            'description' => nl2br("<span style='color:#ff0000;'><b>".$priority."</b></span>\n
                                                    Nova resposta do atendimento foi enviado: \n\n 
                                                    ID Rastreio: (". $maintenance->trackid .")\n 
                                                    Assunto: ".$maintenance->subject."\n 
                                                    Solicitante: ".$maintenance->Users->first_name." ".$maintenance->Users->last_name."\n\n 
                                                    <b>Resposta enviada do solicitante</b>: \n".$replie->message." \n\n
                                                    Veja mais informações no link abaixo: \n 
                                                    <a href='". $request->root() ."/ti/maintenance/info/". $maintenance->id ."'>Atendimento: ". $maintenance->trackid ."</a>
                                                    <span style='color:#ff0000;'>
                                                    <b>Observações:</b>\n
                                                    * Fiquem atentos as atualizações dos chamados.
                                                    * Solicitações relacionadas a estrutura física e predial, “Câmeras, cabeamento de rede e telefonia,” sujeito a análise.
                                                    * Após a abertura do chamado Favor respeitar o tempo de atendimento.
                                                    </span>"),

                            'template' => 'misc.Default',
                            'subject' => 'Nova resposta do atendimento: '. $maintenance->subject .'',
                        );
						
                        NotifyUser('Suporte e Manutenção: Nova resposta do atendimento ('. $maintenance->trackid.')', $key->r_code, 'fa-exclamation', 'text-info', 'Foi solicitado um novo atendimento, clique aqui para ver mais detalhes.', $request->root() .'/ti/maintenance/info/'. $maintenance->id);
                        SendMailJob::dispatch($pattern, $key->email);
                    }        
                }
            } else {
                // EMAIL PARA REQUISITANTE
                $pattern = array(
                    'title' => ''.$maintenance->subject.'',
                    'description' => nl2br("<span style='color:#ff0000;'><b>".$priority."</b></span>\n
                                            ID Rastreio: (". $maintenance->trackid .")\n 
                                            Assunto: ".$maintenance->subject."\n 
                                            Solicitante: ".$maintenance->Users->first_name." ".$maintenance->Users->last_name."\n\n 
                                            <b>Resposta enviada</b>: \n".$replie->message." \n\n
                                            Veja mais informações no link abaixo: \n 
                                            <a href='". $request->root() ."/ti/maintenance/info/". $maintenance->id ."'>Atendimento: ". $maintenance->trackid ."</a>\n\n
                                            <span style='color:#ff0000;'>
                                            <b>Observações:</b>\n
                                            * Fiquem atentos as atualizações dos chamados.
                                            * Solicitações relacionadas a estrutura física e predial, “Câmeras, cabeamento de rede e telefonia,” sujeito a análise.
                                            * Após a abertura do chamado Favor respeitar o tempo de atendimento.
                                            </span>"),

                    'template' => 'misc.Default',
                    'subject' => 'Nova resposta do TI para atendimento: '. $maintenance->subject .'',
                );
				
                NotifyUser('Suporte e Manutenção: Nova resposta do atendimento ('. $maintenance->trackid.')', $maintenance->request_r_code, 'fa-exclamation', 'text-info', 'Foi solicitado um novo atendimento, clique aqui para ver mais detalhes.', $request->root() .'/ti/maintenance/info/'. $maintenance->id);
                SendMailJob::dispatch($pattern, $maintenance->Users->email);
            } 
        }

        $request->session()->put('success', 'Resposta enviada com sucesso!');
        return redirect('/ti/maintenance/info/'.$request->maintenance_id.'');
    }  
    
    public function MaintenanceInfo_ajax(Request $request) { 

        if($request->field == "assigned") {
            $maintenance = TiMaintenance::find($request->maintenance_id);

            $users_to_insert = collect($request->value); 

            $user_assigned = $maintenance->assigns()->where('maintenance_id', $request->maintenance_id);
            $create_time = $user_assigned->count() == 0 ? true : false;
            $users = $user_assigned->pluck('r_code');
         
            if($create_time) {
                $maintenance->start_time_job = Carbon::now();
                $maintenance->status = 4;
                $maintenance->save();
            }
            
            if (!empty($users)) {
                $users_to_delete = $users->diff($users_to_insert);
                $users_to_insert = $users_to_insert->diff(collect($users));
                $user_assigned->whereIn('r_code', $users_to_delete)->delete();
            }
                
            foreach ($users_to_insert as $r_code) {
                $user_assigned = new TiMaintenanceAssigned;
                $user_assigned->maintenance_id = $request->maintenance_id;
                $user_assigned->r_code = $r_code;
                $user_assigned->save();
            }
            
            $time = new Carbon($maintenance->start_time_job);
            if($maintenance->status == 5) {
                $time = $time->diffInSeconds($maintenance->pause_time_job);
            }
            elseif ($maintenance->status == 6)  {
                $time = $time->diffInSeconds($maintenance->final_time_job);
            } elseif ($maintenance->status == 4) {
                $time = $time->diffInSeconds(Carbon::now());
            } else {
                $time = 0;
            }

            return response()->json([
                'message' => 'Atualizado com sucesso!',
                'status' => $maintenance->status,
                'time' => $time
            ], 200);
        }
        else {
            $maintenance = TiMaintenance::find($request->maintenance_id);
        
            if ($maintenance) {

                if ($request->field == 'status') {

                    if ($maintenance->assigns()->count() > 0) {

                        $time = Carbon::now();

                        $maint_timer = new TiMaintenanceTimer;
                        $maint_timer->r_code = $request->session()->get('r_code');
                        $maint_timer->maintenance_id = $request->maintenance_id;
                        $maint_timer->date_time = Carbon::now();
                        $maint_timer->status = $request->value;
                        $maint_timer->save();

                        $field = $request->value;
                        if($field == 5 || $field == 7) {
                            $maintenance->pause_time_job = $time;
                            $time = new Carbon($maintenance->start_time_job);
                            $time = $time->diffInSeconds($maintenance->pause_time_job);
                        } elseif ($field == 6)  {
                            if ($maintenance->status == 5) {
                                $maintenance->final_time_job = $maintenance->pause_time_job;
                                $time = new Carbon($maintenance->start_time_job);
                                $time = $time->diffInSeconds($maintenance->pause_time_job);
                            } else {
                                $maintenance->final_time_job = $time;
                                $time = new Carbon($maintenance->start_time_job);
                                $time = $time->diffInSeconds($maintenance->final_time_job);
                            }
                        } else {
                            $field = 4;
                            $time = new Carbon($maintenance->start_time_job);
                            if ($maintenance->pause_time_job != null)
                            $time = $time->diffInSeconds(new Carbon($maintenance->pause_time_job));
                            else
                            $time = $time->diffInSeconds(Carbon::now());
                            
                        }

                        $maintenance->status = $request->value;
                        $maintenance->save();

                        return response()->json([
                            'message' => 'Atualizado com sucesso!',
                            'status' => $field,
                            'time' => $time
                        ], 200);

                    } else {

                        return response()->json([
                            'message' => 'Precisa ao menos uma pessoa atribuída ao atendimento!'
                        ], 400);
                    }
                } 
                else {
                    $field_name = $request->field;
                    $maintenance->$field_name = $request->value;
                    $maintenance->save();

                    return response()->json([
                        'message' => 'Atualizado com sucesso!',
                        'time' => null
                    ], 200);
                }

            } else {

                return response()->json([
                    'message' => 'Não atualizado, verifique!'
                ], 400);
            }
        }
    } 
    
    public function MaintenanceNoteDelete(Request $request, $id) { 
        $note = TiMaintenanceNotes::find($id);
        if ($note) {
            TiMaintenanceNotes::where('id', $id)->delete();
            $request->session()->put('success', 'Nota excluída com sucesso!');
            return redirect()->back();
        }    
        else {
            $request->session()->put('success', 'Nota não encontra!');
            return redirect()->back();
        }
    }   
}
