<?php

namespace App\Http\Controllers;

use App\Exports\DefaultExport;
use App\Http\Controllers\Services\FileManipulationTrait;
use App\Jobs\SendMailJob;
use App\Model\EntryExitEmployees;
use App\Model\EntryExitEmployeesItems;
use App\Model\EntryExitRentVehicle;
use App\Model\EntryExitVehicle;
use App\Model\LogisticsEntryExitRequests;
use App\Model\LogisticsEntryExitRequestsSchedule;
use App\Model\LogisticsEntryExitGate;
use App\Model\Users;
use App\Model\UsersNotAccess;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdministrationController extends Controller
{
    use FileManipulationTrait;

    public function rentVehicles(Request $request) {

        $vehicles = EntryExitRentVehicle::orderBy('is_active', 'DESC');
        if ($request->registration_plate) {
            $vehicles->where('registration_plate', 'LIKE', '%'.$request->registration_plate.'%');
        }

        return view('gree_i.administration.entry_exit.rent_vehicles', ['vehicles' => $vehicles->paginate(10)]);
    }

    public function rentVehiclesEdit(Request $request) {

        if ($request->id) {
            $vehicles = EntryExitRentVehicle::find($request->id);
        } else {
            $vehicles = new EntryExitRentVehicle;
            if (EntryExitRentVehicle::where('registration_plate', $request->registration_plate)->count())
                return redirect()->back()->with('error', 'Veículo já contém registro na base de dados!');
        }

        $vehicles->registration_plate = $request->registration_plate;
        $vehicles->color = $request->color;
        $vehicles->km = $request->km;
        $vehicles->is_active = $request->is_active == 1 ? 1:0;
        $vehicles->save();

        LogSystem("Colaborador atualizou a lista de veículos com o veículo: ". $request->registration_plate, $request->session()->get('r_code'));
        return redirect()->back()->with('success', $request->id ? 'Veículo foi atualizado com sucesso!' : 'Novo veículo cadastrado com sucesso!');
    }

    public function requestVehicles(Request $request) {

        $load_requests = EntryExitVehicle::withTrashed()->with(
            'logistics_entry_exit_gate',
            'entry_exit_rent_vehicle',
            'who_analyze'
        )->orderBy('date_hour', 'DESC');
		
		if (!hasPermManager(27)) {
            $load_requests->where(function($q) use ($request) {
                $q->where('create_r_code', $request->session()->get('r_code'))
                    ->orWhere('request_r_code', $request->session()->get('r_code'));
            });
        }

        $array_input = collect([
            'r_code',
            'code',
            'entry_exit_rent_vehicle_id',
            'start_date',
            'end_date',
        ]);

        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."r_code"){
                    $load_requests->where('request_r_code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."code"){
                    $load_requests->where('code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."entry_exit_rent_vehicle_id"){
                    $load_requests->where('entry_exit_rent_vehicle_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $load_requests->where('date_hour', '>=', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $load_requests->where('date_hour', '<=', $valor_filtro.' 23:59:59');
                }
            }
        }

        if ($request->export) {
            $heading = array(
                'Código',
                'Placa',
                'KM',
                'Solicitante',
                'Matricula',
                'Nome do gestor',
                'Matricula do gestor',
                'Feito em',
                'Tipo',
                'Status',
            );
            $rows = array();

            foreach ($load_requests->get() as $key) {
                $line = array();
                $line[] = $key->code;
                $line[] = $key->entry_exit_rent_vehicle->registration_plate;
                $line[] = $key->km;
                $line[] = $key->request_user->short_name;
                $line[] = $key->request_user->r_code;
                $line[] = $key->who_analyze->short_name;
                $line[] = $key->who_analyze->r_code;
                $line[] = date('d/m/Y H:i', strtotime($key->date_hour));
                $line[] = $key->is_entry_exit == 1 ? 'ENTRADA' : 'SAÍDA';
                $line[] = $key->status;

                array_push($rows, $line);
            }

			ob_clean();
            return Excel::download(new DefaultExport($heading, $rows), 'RequestVehicles-'. date('Y-m-d') .'.xlsx');

        }

        return view('gree_i.administration.entry_exit.request_vehicles',[
                'load_requests' => $load_requests->paginate(10)
        ]);
    }

    public function requestEmployees(Request $request) {

        $load_requests = EntryExitEmployees::withTrashed()->with(
            'logistics_entry_exit_gate',
            'who_analyze',
			'entry_exit_employees_items',
            'logistics_warehouse'
        )->orderBy('date_hour', 'DESC');
		
		if (!hasPermManager(24)) {
            $load_requests->where(function($q) use ($request) {
                $q->where('create_r_code', $request->session()->get('r_code'))
                    ->orWhere('request_r_code', $request->session()->get('r_code'))
					->orWhere('who_analyze_r_code', $request->session()->get('r_code'));
            });
        }

        $array_input = collect([
            'r_code',
			'number_ref',
            'code',
            'start_date',
            'end_date',
			'reason'
        ]);

        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."r_code"){
                    $load_requests->where('request_r_code', $valor_filtro);
                }
				if($nome_filtro == $filtros_sessao[1]."number_ref"){
                    $load_requests->where('number_ref', 'LIKE', '%'.$valor_filtro.'%');
                }
				if($nome_filtro == $filtros_sessao[1]."reason"){
                    $load_requests->where('reason', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."code"){
                    $load_requests->where('code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $load_requests->where('date_hour', '>=', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $load_requests->where('date_hour', '<=', $valor_filtro.' 23:59:59');
                }
            }
        }

        if ($request->export) {
            $heading = array(
                'Código',
                'Solicitante',
                'Matricula',
                'Nome do gestor',
                'Matricula do gestor',
                'Motivo',
                'Justificativa',
                'Portaria Liberou em',
                'Tipo',
                'Status',
            );
            $rows = array();

            foreach ($load_requests->get() as $key) {
                $line = array();
                $line[] = $key->code;
                $line[] = $key->request_user->short_name;
                $line[] = $key->request_user->r_code;
                $line[] = $key->who_analyze ? $key->who_analyze->short_name : '';
                $line[] = $key->who_analyze ? $key->who_analyze->r_code : '';
                $line[] = $key->reason_name;
                $line[] = $key->justify;
                $line[] = $key->is_liberate == 1 ? date('d/m/Y H:i', strtotime($key->request_action_time)) : '';
                $line[] = $key->is_entry_exit == 1 ? 'ENTRADA' : 'SAÍDA';
                $line[] = $key->status;

                array_push($rows, $line);
            }

			ob_clean();
            return Excel::download(new DefaultExport($heading, $rows), 'RequestEmployees-'. date('Y-m-d') .'.xlsx');

        }

        $sectors = config('gree.sector');
        return view('gree_i.administration.entry_exit.request_employees',[
            'load_requests' => $load_requests->paginate(10),
            'sectors' => $sectors
        ]);
    }

    public function requestEmployeesApprov(Request $request) {

        $load_requests = EntryExitEmployees::with(
            'logistics_entry_exit_gate',
            'who_analyze',
			'entry_exit_employees_items',
            'logistics_warehouse'
        )->where('has_analyze', 1)
            ->whereRaw("FIND_IN_SET('".$request->session()->get('r_code')."', immediates)")
            ->orderBy('date_hour', 'DESC');

        $array_input = collect([
            'r_code',
            'code',
            'start_date',
            'end_date',
        ]);

        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."r_code"){
                    $load_requests->where('request_r_code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."code"){
                    $load_requests->where('code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $load_requests->where('date_hour', '>=', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $load_requests->where('date_hour', '<=', $valor_filtro.' 23:59:59');
                }
            }
        }

        return view('gree_i.administration.entry_exit.request_employeesApprov',[
            'load_requests' => $load_requests->paginate(10)
        ]);
    }

    public function requestEmployeesApprov_do(Request $request) {
        $ids = explode(',', $request->id);
        $requests = EntryExitEmployees::whereIn('id', $ids)->get();

        if (!$requests->count())
            return response()->json(['msg' => 'Selecione ao menos 1 solicitação.'], 400);

        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if (Hash::check($request->password, $user->password)) {

            foreach($requests as $req) {
                $req->is_approv = $request->type == 1 ? 1 : 0;
                $req->is_reprov = $request->type == 2 ? 1 : 0;
                $req->has_analyze = 0;
                $req->who_analyze_r_code = $request->session()->get('r_code');
                $req->save();
            }

        } else {

            if ($user->retry > 0) {
                $user->retry = $user->retry - 1;

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    // Write Log
                    LogSystem("Colaborador foi bloqueado tentando aprovar uma solicitação de entrada/saida de funcionário.", $user->r_code);
                    return response()->json(['msg' => 'Você foi bloqueado, entre em contato com administrador.'], 400);

                } else {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->save();

                    // Write Log
                    LogSystem("Colaborador tentou aprovar e errou sua senha. Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                    return response()->json(['msg' => 'Senha incorreta. Você tem '. $user->retry .' tentativa(s)'], 400);
                }
            } else {

                // Write Log
                LogSystem("Colaborador está tentando aprovar, mesmo já tendo sido bloqueado!", $user->code);
                return response()->json(['msg' => 'Você foi bloqueado, entre em contato com administrador.'], 400);
            }
        }

        return response()->json(['msg' => 'Você análisou a solicitação com sucesso!']);
    }

    public function requestEmployeesNew(Request $request) {

        DB::beginTransaction();
        $type_user = 1;
        $user = Users::where('r_code', $request->r_code)->first();
        if (!$user) {
            $type_user = 2;
            $user = UsersNotAccess::where('r_code', $request->r_code)->first();
            if (!$user) {
                $user = new UsersNotAccess;
                $user->r_code = $request->r_code;
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->office = $request->office;
                $user->sector_id = $request->sector_id;
                $user->save();
            }
        }

        $entry = new EntryExitEmployees;

        $entry->code = $this->codeGenerator('entry_exit_employees', $user->sector_id);
        $entry->request_user_type = $type_user;
        $entry->request_r_code = $request->r_code;
        $entry->create_r_code = $request->session()->get('r_code');
        $entry->request_sector = $user->sector_id;
        $entry->logistics_entry_exit_gate_id = $request->logistics_entry_exit_gate_id;
        $entry->request_office = $user->office;
        $entry->is_entry_exit = $request->is_entry_exit;
        $entry->return_same_day = $request->return_same_day == 1 ? 1 : 0;
        if ($request->return_same_day == 1) {
            $date_return = str_replace('/', '-', $request->rsd_day);
            $entry->return_hour = date('Y-m-d', strtotime($date_return)).' '. $request->rsd_hour;
        }
        $date = str_replace('/', '-', $request->date);
        $entry->date_hour = date('Y-m-d', strtotime($date)).' '. $request->hour;
        $entry->immediates = implode(',', $request->who_analyze_r_code);

        foreach ($request->who_analyze_r_code as $val) {
            if ($val == $request->session()->get('r_code')) {
                $entry->is_approv = 1;
                $entry->who_analyze_r_code = $val;
                break;
            }
        }
        if (!$entry->is_approv)
            $entry->has_analyze = 1;

        $entry->justify = $request->justify;
        $entry->reason = $request->reason;
		
		$entry->number_ref = $request->number_ref;
        $entry->warehouse_id = $request->warehouse_id?? 0;
        if ($request->hasFile('file_ref')) {
            $response = $this->uploadS3(1, $request->file_ref, $request);
            if ($response['success']) {
                $entry->file_ref = $response['url'];
            } else {
                return Redirect()->back()->with('error', 'Não foi possivel fazer upload do arquivo.');
                DB::rollBack();
            }
        }
        $entry->save();

		if (isset($request->items_quantity)) {
            foreach ($request->items_quantity as $index=> $item) {
                if (
                    $request->items_description[$index] and
                    $request->items_quantity[$index]
                ) {
                    $add = new EntryExitEmployeesItems;
                    $add->description = $request->items_description[$index];
                    $add->quantity = $request->items_quantity[$index];
                    $add->entry_exit_employees_id = $entry->id;
                    $add->save();
                }
            }
        }
		
        DB::commit();
		
		if ($request->return_same_day == 1) {
			
			$date_return = str_replace('/', '-', $request->rsd_day);
			
			$return = $entry->replicate();
			
			$return->code = $this->codeGenerator('entry_exit_employees', $user->sector_id);
			$return->date_hour = date('Y-m-d', strtotime($date_return)).' '. $request->rsd_hour;
			$return->return_same_day = 0;
			$return->return_hour = NULL;
			$return->is_entry_exit = $request->is_entry_exit == 1 ? 2 : 1;
			$return->save();
			
			if (isset($request->items_quantity)) {
				foreach ($request->items_quantity as $index=> $item) {
					if (
						$request->items_description[$index] and
						$request->items_quantity[$index]
					) {
						$add = new EntryExitEmployeesItems;
						$add->description = $request->items_description[$index];
						$add->quantity = $request->items_quantity[$index];
						$add->entry_exit_employees_id = $return->id;
						$add->save();
					}
				}
			}
		}

        if (!$entry->is_approv) {

            foreach ($request->who_analyze_r_code as $val) {
                $imd = Users::where('r_code', $val)->first();
                if ($user and $imd) {

					$subject = $request->is_entry_exit == 1 ? 'entrada' : 'saída';
					$title = $request->is_entry_exit == 1 ? 'ENTRADA' : 'SAÍDA';
					
					$itens_request = $entry->entry_exit_employees_items()->get();
					$list = '';
					$url_ref = $entry->file_ref ? "<a href='". $entry->file_ref ."'>Clique aqui</a>" : '';
					$warehouse_name = '';
					if ($itens_request->count()) {
						$warehouse = $entry->logistics_warehouse()->first();
						$warehouse_name = $warehouse ? $warehouse->name : '';
						
						$list = '<p><table style="text-align: center;margin: auto;"><thead><tr role="row"><th style="border: 1px solid #ddd;">Descrição</th><th style="border: 1px solid #ddd;">Quantidade</th></tr></thead><tbody>';
						
						foreach ($itens_request as $item) {
							$list .= '<tr><td style="border: 1px solid #ddd;padding-left: 7px;padding-right: 7px;">'.$item->description.'</td><td style="border: 1px solid #ddd;padding-left: 7px;padding-right: 7px;">'.$item->quantity.'</td></tr>';
						}
						$list .= '</tbody></table></p>';
					}
					
                    $pattern = array(
                        'title' => 'SOLICITAÇÃO DE '. $title,
                        'description' => nl2br("Olá! ". $imd->first_name ." ". $imd->last_name .",
                <br><p><b>Solicitante:</b> ". $user->full_name ."
                <br><b>Solicitado para:</b> ". $request->date." ". $request->hour ."
				<br><b>Documento:</b> ". $url_ref ."
				<br><b>Número de referência:</b> ". $entry->number_ref ."
				<br><b>Galpão:</b> ". $warehouse_name ." </p>
				". str_replace("\r\n", "",$list) ."
                <br>Foi realizado uma nova solicitação vinculado para você, será necessário você entrar no sistema para realizar análise.
                <br><a href='". $request->root() ."/adm/entry-exit/approv/employees/list?code=".$entry->code."'>". $request->root() ."/adm/entry-exit/approv/employees/list?code=".$entry->code."</a>"),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Solicitação de '. $subject .' para aprovar.',
                    );

                    SendMailJob::dispatch($pattern, $imd->email);
                    NotifyUser('Entrada & Saída de funcionários: #'. $entry->code, $imd->r_code, 'fa-exclamation', 'text-info', 'Foi realizado um pedido de Entrada & Saída de funcionários, precisa de sua aprovação.', $request->root() .'/adm/entry-exit/approv/employees/list');
                }
            }
        }

        return redirect()->back()->with('success', 'Solicitação foi criada com sucesso!');
    }

    public function requestEmployeesCancel(Request $request) {
        $requests = EntryExitEmployees::find($request->id);
        if (!$requests)
            return redirect()->back()->with('error', 'Não foi possível encontrar a solicitação');

        $requests->has_analyze = 0;
        $requests->is_cancelled = 1;
        $requests->cancelled_r_code = $request->session()->get('r_code');
        $requests->cancelled_reason = $request->description;
        $requests->request_action_time = date('Y-m-d H:i');
        $requests->save();

        return redirect()->back()->with('success', 'Você cancelou a solicitação com sucesso!');
    }
	
	public function receivementMonitor(Request $request) {
		
		$gates = LogisticsEntryExitGate::all();

        return view('gree_i.administration.entry_exit.receivementMonitor', ['gates' => $gates]);
    }

    public function receivementMonitorAjax(Request $request) {
        $solicitations = collect([]);

        $data_transport = LogisticsEntryExitRequests::with('request_user', 'logistics_entry_exit_gate')
            ->whereHas('logistics_entry_exit_gate', function ($q) {
                $q->where('is_receivement', 1);
            })->where('is_cancelled', 0)
			->where('is_receivement_check', 0)
            ->where('is_content', 1)
            ->where('is_approv', 1)
            ->whereNotIn('type_reason', [3,9,10])
            ->where('is_denied', 0);
		
		if ($request->date) {
			$data_transport->whereDate('date_hour', $request->date);	
		} else {
			$data_transport->whereDate('date_hour', '>=', date('Y-m-d', strtotime('- 1 days')))
			->whereDate('date_hour', '<=', date('Y-m-d', strtotime('+ 1 days')));	
		}
		
		if ($request->gate) {
			$data_transport->where('entry_exit_gate_id', $request->gate);
		}
		
		$trasnport = $data_transport->get();

        $solicitations = $this->createUniqueCollect($trasnport, $solicitations);

        $data_visite = LogisticsEntryExitRequestsSchedule::with('logistics_entry_exit_requests.request_user', 'logistics_entry_exit_requests.logistics_entry_exit_gate')
            ->whereHas('logistics_entry_exit_requests', function ($q) {
                $q->where('is_cancelled', 0)
                    ->where('is_approv', 1)
					->where('is_receivement_check', 0)
                    ->whereIn('type_reason', [3,9,10])
                    ->where('is_content', 1)
                    ->whereHas('logistics_entry_exit_gate', function ($q1) {
                        $q1->where('is_receivement', 1);
                    });
            })
            ->where('is_denied', 0);
		
		if ($request->date) {
			$data_visite->whereDate('date_hour', $request->date);	
		} else {
			$data_visite->whereDate('date_hour', '>=', date('Y-m-d', strtotime('- 1 days')))
			->whereDate('date_hour', '<=', date('Y-m-d', strtotime('+ 1 days')));	
		}
		
		if ($request->gate) {
			$data_visite->whereHas('logistics_entry_exit_requests', function ($q) use ($request) {
				$q->where('entry_exit_gate_id', $request->gate);
			});
		}
		
		$visite = $data_visite->get();

        $solicitations = $this->createUniqueCollect($visite, $solicitations, true);

        return response()->json($solicitations);
    }
	
	public function receivementMonitorConfirm(Request $request, $id) {
        $solicitations = collect([]);

        $requests = LogisticsEntryExitRequests::where('is_receivement_check', 0)->where('id', $id)->first();
        if (!$requests)
            return redirect()->back()->with('error', 'A solicitação já foi recebida ou não existe');

        $requests->is_receivement_check = 1;
        $requests->date_receivement_check = date('Y-m-d H:i:s');
        $requests->save();

        LogSystem('O colaborador confirmou o recebimento da solicitação. ID: #'. $requests->id, $request->session()->get('r_code'));

        return redirect()->back()->with('success', 'A solicitação foi atualizada com sucesso!');
    }

    private function createUniqueCollect($collect, $solicitations, $use_relation = false) {

            foreach ($collect as $val) {
                $request = $use_relation ? $val->logistics_entry_exit_requests : $val;
                $solicitations->push([
					'is_visite' => $use_relation,
                    'id' => $request->id,
                    'code' => $request->code,
					'date_hour_initial' => $use_relation ? $val->date_hour : $request->date_hour_initial,
                    'date_hour' => $use_relation ? $val->date_hour : $request->date_hour,
                    'type_reason_name' => $request->type_reason_name,
                    'request_forwarding' => $use_relation ? $val->request_forwarding : $request->request_forwarding,
                    'request_user' => $request->request_user->short_name .' ('.$request->request_user->r_code.')',
                    'request_action_time' => $use_relation ? $val->request_action_time : $request->request_action_time,
                    'is_liberate' => $use_relation ? $val->is_liberate : $request->is_liberate,
                    'gate' => $request->logistics_entry_exit_gate->name,
                    'is_entry_exit' => $use_relation ? $val->is_entry_exit : $request->is_entry_exit,
                ]);
            }

            return $solicitations;

    }

    public function listDropDownUsersGeneral(Request $request) {
        $name = $request->search;

        $data = Users::where(function ($q) use ($name) {
                $q->where('r_code', 'like', '%'. $name .'%')
                    ->orWhereRaw("CONCAT_WS(' ', `first_name`, `last_name`) like '%$name%'");
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);

        if (!count($data)) {
            $data = UsersNotAccess::where('r_code', 'like', '%'. $name .'%')
                ->orderBy('id', 'DESC')
                ->paginate(10);
        }

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->r_code;
                $row['r_code'] = $key->r_code;
                $row['first_name'] = $key->first_name;
                $row['last_name'] = $key->last_name;
                $row['office'] = $key->office;
                $row['sector_id'] = $key->sector_id;
                $row['text'] = $key->first_name.' '. $key->last_name .' ('. $key->r_code .')';

                array_push($results, $row);
            }

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => true
                ],
            ]);
        } else {
            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => false
                ],
            ]);
        }
    }

    public function listDropDownUsers(Request $request) {
        $name = $request->search;

        $data = Users::where('is_active', 1)
            ->where(function ($q) use ($name) {
                $q->where('r_code', 'like', '%'. $name .'%')
                    ->orWhereRaw("CONCAT_WS(' ', `first_name`, `last_name`) like '%$name%'");
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->r_code;
                $row['text'] = $key->first_name.' '. $key->last_name .' ('. $key->r_code .')';

                array_push($results, $row);
            }

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => true
                ],
            ]);
        } else {
            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => false
                ],
            ]);
        }
    }

    public function listDropDownGates(Request $request) {
        $name = $request->search;

        $data = LogisticsEntryExitGate::where('name', 'LIKE', '%'.$name.'%')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name;

                array_push($results, $row);
            }

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => true
                ],
            ]);
        } else {
            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => false
                ],
            ]);
        }
    }

    public function listDropDownRentVehicles(Request $request) {
        $name = $request->search;

        $data = EntryExitRentVehicle::where('registration_plate', 'like', '%'. $name .'%')
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->registration_plate.' ('. $key->color .')';

                array_push($results, $row);
            }

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => true
                ],
            ]);
        } else {
            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => false
                ],
            ]);
        }
    }
	
	public function reservationMeetRoomShow(Request $request)
    {
		$meets = \App\Model\Administration\MeetRoom::all();
		$reasons = \App\Model\Administration\Reason::all();
		
        return view('gree_i.administration.reservation_meet_room.reservation_main', [
			'meets' => $meets,
			'reasons' => $reasons,
		]);
    }


    //Para alimentar o FullCalendar.
    public function getReservationMeetRoom(Request $request)
    {
        $reservation = \App\Model\Administration\ReservationMeetRoom::with('users', 'reason')
            ->whereDate('start_time', '>=', date('Y-m-d', strtotime($request->start)))
            ->whereDate('end_time', '<=', date('Y-m-d', strtotime($request->end)))
            ->where('is_reprov', 0)
            ->get();
        
        $array = array();

        foreach ($reservation as $i => $key) {
                        
            $has_analyze = [
                1 => ' >> '.$key->rtd_status['status']['situation'],
                0 => ''
            ];



            $array[] = [
                'reservation_id' => $key->id,
                'title' => $key->reason->reason . ' - ' . $key->users->first_name . ' (' . $key->users->sector_name . ').'.$has_analyze[$key->has_analyze],
                'start' => $key->start_time,
                'end' => $key->end_time,                            
                'extendedProps' => array(
                    'reservation_id' => $key->id,
                    'responsible_user_id' => $key->users_id,
                    'r_code' => $key->r_code,
                    'description' => $key->description,
                    'reason' => $key->reason_id,
                    'dateSelected' => $key->start_time,
                    'selectStartHour' => date('H:i', strtotime($key->start_time)),
                    'selectEndHour' => date('H:i', strtotime($key->end_time)),
                    'first_name' => $key->users->first_name,
                    'sector_id' => $key->users->sector_id,
                    'sector_name' => $key->users->sector_name,
                    'meet_room_id' => $key->meet_room_id
                )
            ];
        }

        return response()->json($array);
    }

    //Método pusado para validar se RCode existe.
    public function reservationMeetRoomValidateRCode(Request $request)
    {

        $user = \App\Model\Users::where('r_code', $request->r_code)->first();

        if (!$user) {
            return response()->json([
                'msg' => "Usuário não encontrado"
            ], 400);
        }
        return response()->json([
            'responsible_user_id' => $user->id,
            'responsibleName' => $user->first_name,
            'r_code' => $user->r_code,
            'sector_id' => $user->sector_id,
            'responsibleSector' => config('gree.sector')[$user->sector_id] ?? ''
        ]);
    }

    //Função para validar conflito de horas. Nao utilizado no momento
    /* private function reservationHourAppointment($start, $end)
    {
        $range_hours = [];

        $h = 0;
        for ($i = intval(date('H', strtotime($start))); $i < intval(date('H', strtotime($end)) + 1); $i++) {
            $range_hours[] = date('H:s', strtotime($start . '+ ' . $h . ' hour'));
            $h++;
        }

        $range_hours[] = date('H:59', strtotime($start . '+ ' . --$h . ' hour'));
        return $range_hours;
    } */

    private function reservationMeetRoomValidate($request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make(
            [
                'responsible_user_id' => $request->responsible_user_id,
                'reservation_id' => $request->reservation_id,
                'sector_id' => $request->sector_id,
                'responsibleRCode' => $request->responsibleRCode,
                'selectMeetRoom' => $request->selectMeetRoom,
                'selectReason' => $request->selectReason,
                'dateSelected' => $request->dateSelected,
                'selectStartHour' => $request->selectStartHour,
                'selectEndHour' => $request->selectEndHour
            ],
            [
                'responsible_user_id' => 'required',
                'reservation_id' => 'required',
                'sector_id' => 'required',
                'responsibleRCode' => 'required',
                'selectMeetRoom' => 'required',
                'selectReason' => 'required',
                'dateSelected' => 'required',
                'selectStartHour' => 'required',
                'selectEndHour' => 'required'
            ]
        );


        if ($validator->fails()) {
            return Redirect()->back()->with('error', "Você não preencheu todos os campos corretamente.");
        }

        $start_time = date("Y-m-d H:i:s", strtotime($request->dateSelected . ' ' . $request->selectStartHour));
        $end_time = date("Y-m-d H:i:s", strtotime($request->dateSelected . ' ' . $request->selectEndHour));

        // 1 - Valida data inicial não pode ser maior que a data final.
        if ($start_time > $end_time)
            return Redirect()->back()->with('error', "ERRO de reserva. A data inicial não pode ser maior que a data final.");

        // 2 -Validar conflitos de horários. //date("Y-m-d H:i:s")
        $valid_hour = \App\Model\Administration\ReservationMeetRoom::where(function ($qprimary) use ($start_time, $end_time) {
            $qprimary->where(function ($q) use ($start_time) {
                $q->where('start_time', '<=', $start_time)->where('end_time', '>=', $start_time);
            })->orWhere(function ($q) use ($end_time) {
                $q->where('start_time', '<=', $end_time)->where('end_time', '>=', $end_time);
            })->orWhere(function ($q) use ($start_time, $end_time) {
                $q->where('start_time', '>=', $start_time)->where('end_time', '<=', $end_time);
            });
        })->where(function ($qsecondary) {
            $qsecondary->where('is_approv', 1)
                ->orWhere('has_analyze', 1);
        })
		->where('meet_room_id', $request->selectMeetRoom)
		->first();

        if ($valid_hour)
            return Redirect()->back()->with('error', "ERRO de reserva. Já existe uma reserva para esse horário.");

        // 2 -Validar conflitos de horários. //date("Y-m-d H:i:s")
        /* $valid_hour = \App\Model\Administration\ReservationMeetRoom::whereDate('start_time', date('Y-m-d', strtotime($start_time)))->get();
        foreach ($valid_hour as $apms) {
            $db_apm = $this->reservationHourAppointment($apms->start_time, $apms->end_time);
            $request_apm = $this->reservationHourAppointment($start_time, $end_time);
            $result = array_intersect($db_apm, $request_apm);
            if (count($result))
                return Redirect()->back()->with('error', "ERRO de reserva. Já existe uma reserva para esse horário.");
        } */

        //Valida limite agendamento por setor.
        $totalReserveBySector = \App\Model\Administration\ReservationMeetRoom::join('users', 'reservation_meet_room.users_id', '=', 'users.id')
            ->where('users.sector_id', '=', $request->sector_id)
            ->where('reservation_meet_room.id', '!=', $request->reservation_id)
            ->whereMonth('reservation_meet_room.start_time', date('m', strtotime($start_time)))
            ->whereYear('reservation_meet_room.start_time', date('Y', strtotime($start_time)))
            ->count();

        if ($totalReserveBySector > 15)
            return Redirect()->back()->with('error', "ERRO de reserva. Este setor atingiu o limite de 15 reservas.");

        return true;
    }



    public function reservationMeetRoomInsert(Request $request)
    {
        $users_id = $request->responsible_user_id;
        $sector_id = $request->sector_id;
        $r_code = $request->responsibleRCode;
        $meet_room_id = $request->selectMeetRoom; //Referente ao id da sala de reunião. ID: 1.Sala 1  2.Sala 2 ... Não há tabela no banco MeetRoom.        

        //Referente ao id motivo da reserva da sala de reunião. ID: 1.Reuniao  2.Outros. Não há tabela no banco Motivo.
        $reason_id = $request->selectReason;
        $description = $request->textareaDescription;
        $start_time = date("Y-m-d H:i:s", strtotime($request->dateSelected . ' ' . $request->selectStartHour));
        $end_time = date("Y-m-d H:i:s", strtotime($request->dateSelected . ' ' . $request->selectEndHour));

        if ($this->reservationMeetRoomValidate($request) === TRUE) {
            //Insere nova reserva de sala.
            $ReservationMeetRoom = new \App\Model\Administration\ReservationMeetRoom;
            $ReservationMeetRoom->users_id = $users_id;
            $ReservationMeetRoom->r_code = $r_code;
            $ReservationMeetRoom->meet_room_id = $meet_room_id;
            $ReservationMeetRoom->reason_id = $reason_id;

            $ReservationMeetRoom->description = $description;
            $ReservationMeetRoom->start_time = $start_time;
            $ReservationMeetRoom->end_time = $end_time;
            $ReservationMeetRoom->save();

            $dStart = new \Carbon\Carbon($start_time);
            $dEnd = new \Carbon\Carbon($end_time);
            $hLimit = 8;
            if (($dEnd->diffInHours($dStart) + 1) >= $hLimit) {

                try {

                    $solicitation = new \App\Services\Departaments\Reservation\MeetRoom($ReservationMeetRoom, $request);
                    $do_analyze = new \App\Services\Departaments\ProcessAnalyze($solicitation);
                    $do_analyze->eventStart();

                    return redirect()->back()->with('success', 'A solicitação está em um período maior que ' . $hLimit . ' horas, por isso ficará em processo de análise.');
                } catch (\Exception $e) {
                    \App\Model\Administration\ReservationMeetRoom::where('id', $ReservationMeetRoom->id)->delete();
                    return redirect()->back()->with('error', $e->getMessage());
                }
            } else {
                $ReservationMeetRoom->is_approv = 1;
                $ReservationMeetRoom->save();
            }

            return Redirect()->back()->with('success', "Reserva de sala de reunião concluída com sucesso!");
        } else {
            return Redirect()->back();
        }
    }

    // Atualiza reserva
    public function reservationMeetRoomEdit(Request $request)
    {
        if ($this->reservationMeetRoomValidate($request) === TRUE) {

            $start_time = date("Y-m-d H:i:s", strtotime($request->dateSelected . ' ' . $request->selectStartHour));
            $end_time = date("Y-m-d H:i:s", strtotime($request->dateSelected . ' ' . $request->selectEndHour));

            $reservationMeetRoom = \App\Model\Administration\ReservationMeetRoom::find($request->reservation_id);
            $reservationMeetRoom->description = $request->textareaDescription;
            $reservationMeetRoom->start_time = $start_time;
            $reservationMeetRoom->end_time = $end_time;
            $reservationMeetRoom->users_id = $request->responsible_user_id;
            $reservationMeetRoom->r_code = $request->responsibleRCode;
            $reservationMeetRoom->meet_room_id = $request->selectMeetRoom;
            $reservationMeetRoom->reason_id = $request->selectReason;

            $reservationMeetRoom->save();

            return Redirect()->back()->with('success', "Reserva de sala de reunião atualizada com sucesso!");
        } else {
            return Redirect()->back();
        }
    }


    public function reservationMeetRoomRemove(Request $request)
    {

        $reservation = \App\Model\Administration\ReservationMeetRoom::where('r_code', $request->session()->get('r_code'))
            ->where('id', $request->reservation_id)
            ->first();

        if (!$reservation)
            return redirect()->back()->with('error', 'Sessão não definida.');

        \App\Model\Administration\ReservationMeetRoom::find($request->reservation_id)->delete();


        return Redirect()->back()->with('success', "Reserva de sala de reunião atualizada com sucesso!");
    }

    public function reservationMeetRoomAnalyze(Request $request)
    {   
        if (!( $request->session()->get('r_code'))) {
            return Redirect()->back()->with('success', "Erro de Sessao!");
        }           

        
        //Retona apenas as reservas de sala de reuniao que precisam de analise.
        $reservationMeetRoomHasAnalyzes = \App\Model\Administration\ReservationMeetRoom::        
            with('users', 'meet_room', 'reason')       
            ->where('start_time', '>=', date("Y-m"))        
            ->where('has_analyze', 1)
            ->ValidAnalyzeProccess($request->session()->get('r_code'))
            ->orderBy('start_time', 'DESC')->paginate(10);
        
        return view('gree_i.administration.reservation_meet_room.reservation_meet_room_analyze', [
            'reservationMeetRoomHasAnalyze' => $reservationMeetRoomHasAnalyzes
        ]);
    }

    public function reservationMeetRoomAnalyze_do(Request $request) {
        

        $ReservationMeetRoom = \App\Model\Administration\ReservationMeetRoom::with('users', 'meet_room', 'reason')
            ->where('id', $request->id)
            ->first();

        if (!$ReservationMeetRoom)
            return redirect()->back()->with('error', 'A solicitacao nao existe');

        try {
            
            $solicitation = new \App\Services\Departaments\Reservation\MeetRoom($ReservationMeetRoom, $request);
            $do_analyze = new \App\Services\Departaments\ProcessAnalyze($solicitation);
            
            $actions = [
                1 => 'eventApprov',
                2 => 'eventReprov',
                3 => 'eventSuspended',
                4 => 'eventRevert'
            ];

            $method = $actions[$solicitation->request->type];            
            $result = $do_analyze->$method();
            
            return redirect()->back()->with('success', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
    public function reservationMeetRoomReasonIsert(Request $request)
    {
        try {
            $Reason = new \App\Model\Administration\Reason;
            $Reason->reason = $request->reservationReason;
            $Reason->description = $request->textAreaDescriptionReason;           
            $Reason->save();

            return redirect()->back()->with('success', 'Cadastro concluído com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function codeGenerator($table, $sector_id) {
        $sector = config('gree.sector');
        // Busca nome do setor e pega as duas primeiras letras.
        $sector_name = isset($sector[$sector_id]) ? substr(remove_accents($sector[$sector_id]), 0, 2) : '';

        do {
           $code = strtoupper($sector_name).date('ym').'-'.strtoupper(generateRandomNumber(6));
        } while (DB::table($table)->where('code', $code)->first());

        return $code;
    }
}
