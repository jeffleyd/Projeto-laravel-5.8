<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Services\FileManipulationTrait;
use App\Model\EntryExitEmployees;
use App\Model\EntryExitRentVehicle;
use App\Model\EntryExitVehicle;
use App\Model\LogisticsTransporterVehicle;
use App\Model\LogisticsTransporterCart;
use App\Model\LogisticsEntryExitRequests;
use App\Model\LogisticsEntryExitRequestsSchedule;
use App\Model\LogisticsEntryExitSecurityGuard;
use App\Model\LogisticsTransporter;
use App\Model\LogisticsTransporterDriver;
use App\Model\LogisticsSupplier;
use App\Model\Users;
use App\Model\UsersNotAccess;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Departaments\Warehouse\WarehouseTrait;

class SecurityGateController extends Controller
{
    use FileManipulationTrait;
	use WarehouseTrait;

    public function login() {

        return view('security.gate.guard.login');
    }

    public function logout(Request $request) {

        // session flush
        $request->session()->flush();
        return redirect('/controle/portaria');
    }

    public function loginVerify(Request $request) {

        $guard = LogisticsEntryExitSecurityGuard::with('logistics_entry_exit_gate')->where('identity', $request->identity)->first();
        if ($guard) {
            if ($guard->is_active) {
                if (Hash::check($request->password, $guard->password)) {

                    $last_session = \Session::getHandler()->read($guard->session_id); // retrive last session
                    if ($last_session) {
                        \Session::getHandler()->destroy($guard->session_id);
                    }

                    $secret = generateRandomNumber(12);
                    $guard->session_id = \Session::getId();
                    $guard->retry = 3;
                    $guard->secret = $secret;
                    $guard->save();

                    $request->session()->put('security_guard_data', $guard);
                    return response()->json([
                        'secret' => $secret
                    ]);
                } else {
                    $guard->retry -= 1;
                    if (!$guard->retry)
                        $guard->is_active = 0;

                    $guard->save();
                    return response()->json([
                        'msg' => 'Você informou sua senha incorreta, você será desativado se continuar errando.'
                    ], 400);
                }
            } else {
                Log::info('A conta do Segurança da portatia está desativada: ID '. $guard->id);
                return response()->json([
                    'msg' => 'Sua conta está desativada, fale com administração.'
                ], 400);
            }
        } else {
            return response()->json([
                'msg' => 'Segurança não está cadastrado, sua tentativa de acesso, foi registrada.'
            ], 400);
        }
    }

    public function main() {

        return view('security.gate.guard.main');
    }

    public function pagesTransportCharge() {

        return view('security.gate.guard.pages.transport_charge');
    }

    public function pagesVisite() {

        return view('security.gate.guard.pages.visite');
    }

    public function pagesEmployees() {

        $sectors = config('gree.sector');
        return view('security.gate.guard.pages.employees', ['sectors' => $sectors]);
    }

    public function pagesVehicle() {

        $sectors = config('gree.sector');
        return view('security.gate.guard.pages.vehicle', ['sectors' => $sectors]);
    }

    /**
     * 	3 - Visita;
     *  9 - Prestação de serviços;
     *  10 - Seleção para contratação;
     * @return \Illuminate\Http\JsonResponse
     */
    public function pagesTransportChargeList(Request $request) {

        $load_requests = LogisticsEntryExitRequests::with(
            'logistics_entry_exit_gate',
            'logistics_transporter_vehicle',
            'logistics_transporter_driver',
			'logistics_entry_exit_requests_items',
			'logistics_entry_exit_requests_attachs',
            'request_user')
            ->where('date_hour', '>=', date('Y-m-d', strtotime('- 2 month')))
            ->whereNotIn('type_reason', [3,9,10])
            ->where('is_approv', 1)
			->orderByRaw('date_hour DESC, is_liberate, is_denied ASC');


        if ($request->search) {
            $load_requests->where(function($q) use ($request) {
                $q->where('code', 'LIKE', '%'.$request->search.'%')
                    ->orWhere(function($q2) use ($request) {
                        $q2->whereHas('logistics_transporter_vehicle', function ($q3) use ($request) {
                            $q3->where('registration_plate', $request->search);
                        });
                    })->orWhere(function($q2) use ($request) {
                        $q2->whereHas('logistics_transporter', function ($q3) use ($request) {
							$formatValue = formatCnpjCpf($request->search);
                            $q3->where('logistics_transporter.identity', $formatValue);
                        });
                    })->orWhere(function($q2) use ($request) {
                        $q2->whereHas('logistics_supplier', function ($q3) use ($request) {
							$formatValue = formatCnpjCpf($request->search);
                            $q3->where('logistics_supplier.identity', $formatValue);
                        });
                    });
            });
        }
		
		if ($request->date) {
			$load_requests->whereDate('date_hour', $request->date);
		}
		
		if ($request->logistics_supplier) {
			$load_requests->where('supplier_id', $request->logistics_supplier);
		}
		
		if ($request->logistics_transporter) {
			$load_requests->where('transporter_id', $request->logistics_transporter);
		}

        return response()->json($load_requests->paginate(3));
    }

    public function pagesTransportChargeSingle(Request $request) {

        $load_requests = LogisticsEntryExitRequests::with(
            'logistics_entry_exit_gate',
            'logistics_entry_exit_requests_items',
            'logistics_transporter',
            'logistics_transporter_driver',
            'logistics_transporter_vehicle',
            'logistics_transporter_cart',
            'logistics_container',
            'logistics_warehouse',
            'logistics_warehouse_type_content',
			'logistics_entry_exit_requests_attachs',
			'logistics_entry_exit_requests_people',
            'request_user')
            ->whereNotIn('type_reason', [3,9,10])
            ->where('is_approv', 1)
            ->where('id', $request->id)
            ->first();

        if (!$load_requests)
            return response()->json(['msg', 'Não foi possível encontrar a solicitação'], 400);

        return response()->json($load_requests);
    }

    public function pagesTransportChargeRegisterDriver(Request $request) {

        if (!$request->transporter_driver_id)
        	return redirect()->back()->with('error', 'É obrigatório escolher o Motorista/Pedestre!');
		
        $load_requests = LogisticsEntryExitRequests::find($request->id);
		if (!$request->id)
			return redirect()->back()->with('error', 'Solicitação não foi encontrada, ocorreu um erro inesperado.');
			
        $load_requests->transporter_driver_id = $request->transporter_driver_id;
		$load_requests->transporter_vehicle_id = $request->transporter_vehicle_id;
		$load_requests->transporter_cart_id = $request->transporter_cart_id ? $request->transporter_cart_id : 0;
        $load_requests->save();

        return redirect()->back()->with('success', 'Solicitação foi atualizada com sucesso! Pode dar andamento na solicitação');
    }

    public function listDropDownLogisticsTransport(Request $request) {
        $name = $request->search;

        $data = LogisticsTransporter::where('identity', 'like', '%'. $name .'%')
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name .' ('. $key->identity .')';

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

    public function pagesVisitList(Request $request) {

        $load_requests = LogisticsEntryExitRequestsSchedule::with(
            'logistics_entry_exit_requests.logistics_entry_exit_gate',
            'logistics_entry_exit_requests.logistics_entry_exit_visit',
            'logistics_entry_exit_requests.request_user')
            ->where('date_hour', '>=', date('Y-m-d', strtotime('- 2 month')))
            ->orderByRaw('date_hour DESC, is_liberate, is_denied ASC');


        if ($request->search) {
            $load_requests->whereHas('logistics_entry_exit_requests', function ($q) use ($request) {
                $q->whereIn('type_reason', [3,9,10])
                    ->where('is_approv', 1)
                    ->where(function($q1) use ($request) {
                        $q1->where('code', 'LIKE', '%'.$request->search.'%')
                            ->orWhere(function($q2) use ($request) {
                                $q2->whereHas('logistics_entry_exit_visit', function ($q3) use ($request) {
                                    $q3->where('car_plate', $request->search)
                                        ->orWhere('name', 'LIKE', '%'.$request->search.'%')
                                        ->orWhere('identity', 'LIKE', '%'.$request->search.'%');
                                });
                            });
                    });
            });
        } else if ($request->logistics_transporter) {
			$load_requests->whereHas('logistics_entry_exit_requests', function ($q) use ($request) {
                $q->whereIn('type_reason', [3,9,10])
                    ->where('is_approv', 1)
                    ->where(function($q1) use ($request) {
                        $q1->where(function($q2) use ($request) {
							$q2->whereHas('logistics_entry_exit_visit', function ($q3) use ($request) {
								$q3->where('company_name', 'LIKE', '%'.$request->logistics_transporter.'%');
							});
						});
                    });
            });
		} else if ($request->date) {
			$load_requests->whereHas('logistics_entry_exit_requests', function ($q) use ($request) {
                $q->whereIn('type_reason', [3,9,10])
                    ->where('is_approv', 1);
            })->whereDate('date_hour', $request->date);
		} else {
            $load_requests->whereHas('logistics_entry_exit_requests', function ($q) {
                $q->whereIn('type_reason', [3,9,10])
                    ->where('is_approv', 1);
            });
        }

        return response()->json($load_requests->paginate(3));
    }

    public function pagesVisitSingle(Request $request) {

        $load_requests = LogisticsEntryExitRequestsSchedule::with(
            'logistics_entry_exit_requests.logistics_entry_exit_gate',
            'logistics_entry_exit_requests.logistics_entry_exit_visit',
            'logistics_entry_exit_requests.logistics_warehouse',
            'logistics_entry_exit_requests.logistics_warehouse_type_content',
            'logistics_entry_exit_requests.logistics_entry_exit_requests_items',
            'logistics_entry_exit_requests.request_user')
            ->whereHas('logistics_entry_exit_requests', function ($q) {
                $q->whereIn('type_reason', [3,9,10])
                    ->where('is_approv', 1);

            })
            ->where('id', $request->id)
            ->first();

        if (!$load_requests)
            return response()->json(['msg', 'Não foi possível encontrar a solicitação'], 400);

        return response()->json($load_requests);
    }

    public function pagesRequestsEntryExitApprov(Request $request) {

        if ($request->is_schedule) {
            $requests = LogisticsEntryExitRequestsSchedule::whereHas('logistics_entry_exit_requests', function ($q) {
                $q->where('is_cancelled', 0)
                    ->where('is_approv', 1);

            }) ->where('is_denied', 0)
                ->where('is_liberate', 0)
                ->where('id', $request->id)
                ->first();
        } else {
            $requests = LogisticsEntryExitRequests::with('logistics_entry_exit_requests_items')->where('is_approv', 1)
                ->where('is_denied', 0)
                ->where('is_cancelled', 0)
                ->where('is_liberate', 0)
                ->where('id', $request->id)
                ->first();
        }

        if (!$requests)
            return response()->json(['msg' => 'A solicitação não foi encontrada ou já foi análisada.'], 400);

        $requests->is_liberate = 1;
        $requests->entry_exit_security_guard_id = $request->session()->get('security_guard_data')->id;
        $requests->request_action_time = date('Y-m-d H:i:s');
        $requests->save();
		
		if (!$request->is_schedule) {
			
			if($requests->logistics_entry_exit_requests_items->count() > 0) {

				$arr_keys = [
					'warehouse_id' => $requests->warehouse_id,
					'is_entry_exit' => $requests->is_entry_exit,
					'type_request' => 2,
					'request_id' => $requests->id,
					'pair_keys' => [
						'code_model' => 'code', 
						'description' => 'description',
						'quantity' => 'quantity',
					]
				];

				$this->saveEntryExitItems($requests->logistics_entry_exit_requests_items, $arr_keys);

        	}
		}	

        return response()->json([]);
    }

    public function pagesRequestsEntryExitDenied(Request $request) {

        if ($request->is_schedule) {
            $requests = LogisticsEntryExitRequestsSchedule::whereHas('logistics_entry_exit_requests', function ($q) {
                $q->where('is_cancelled', 0)
                    ->where('is_approv', 1);

            }) ->where('is_denied', 0)
                ->where('is_liberate', 0)
                ->where('id', $request->id)
                ->first();
        } else {
            $requests = LogisticsEntryExitRequests::where('is_approv', 1)
                ->where('is_denied', 0)
                ->where('is_cancelled', 0)
                ->where('is_liberate', 0)
                ->where('id', $request->id)
                ->first();
        }

        if (!$requests)
            return response()->json(['msg' => 'A solicitação não foi encontrada ou já foi análisada.'], 400);

        $requests->is_denied = 1;
        $requests->denied_reason = $request->description;
        $requests->entry_exit_security_guard_id = $request->session()->get('security_guard_data')->id;
        $requests->request_action_time = date('Y-m-d H:i:s');
        $requests->save();

        return response()->json([]);
    }

    public function pagesEmployeesList(Request $request) {

        $load_requests = EntryExitEmployees::with(
            'logistics_entry_exit_gate',
            'who_analyze')
            ->where('date_hour', '>=', date('Y-m-d', strtotime('- 2 month')))
            ->where('is_approv', 1)
			->orderByRaw('date_hour DESC, is_liberate, is_denied ASC');

        if ($request->search) {
            $load_requests->where(function($q) use ($request) {
                $q->where('code', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('request_r_code', 'LIKE', '%'.$request->search.'%');
            });
        }

        return response()->json($load_requests->paginate(3));
    }

    public function pagesEmployeesSingle(Request $request) {

        $load_requests = EntryExitEmployees::with(
            'logistics_entry_exit_gate',
            'who_analyze',
			'entry_exit_employees_items',
            'logistics_warehouse'
        )
            ->where('is_approv', 1)
            ->where('id', $request->id)
            ->first();

        if (!$load_requests)
            return response()->json(['msg', 'Não foi possível encontrar a solicitação'], 400);

        return response()->json($load_requests);
    }

    public function pagesEmployeesApprovOrReprov(Request $request) {

        $requests = EntryExitEmployees::where('is_approv', 1)
            ->where('is_denied', 0)
            ->where('is_cancelled', 0)
            ->where('is_liberate', 0)
            ->where('id', $request->id)
            ->first();

        if (!$requests)
            return response()->json(['msg' => 'A solicitação não foi encontrada ou já foi análisada.'], 400);

        $requests->is_liberate = $request->status == 1 ? 1 : 0;
        $requests->is_denied = $request->status == 2 ? 1 : 0;
        $requests->denied_reason = $request->description ? $request->description : null;
        $requests->logistics_entry_exit_security_guard_id = $request->session()->get('security_guard_data')->id;
        $requests->logistics_entry_exit_gate_id = $request->session()->get('security_guard_data')->logistics_entry_exit_gate->id;
        $requests->request_action_time = date('Y-m-d H:i:s');
		$requests->date_hour = date('Y-m-d H:i:s');
        $requests->save();
		
		if($requests->entry_exit_employees_items->count() > 0) {

			$arr_keys = [
				'warehouse_id' => $requests->warehouse_id,
				'is_entry_exit' => $requests->is_entry_exit,
				'type_request' => 1,
				'request_id' => $requests->id,
				'pair_keys' => [
					'description' => 'description',
					'quantity' => 'quantity',
				]
			];

			$this->saveEntryExitItems($requests->entry_exit_employees_items, $arr_keys);

		}

        return response()->json([]);
    }

    public function pagesEmployeesDeleteEntry(Request $request) {
        $entry = EntryExitEmployees::find($request->id);
        $entry->del_logistics_entry_exit_security_guard_id = $request->session()->get('security_guard_data')->id;
        $entry->del_description = $request->description;
        $entry->save();

        if (!$entry)
            return response()->json(['msg' => 'Não foi possível encontrar a solicitação.'], 400);

        $entry->delete();
        return response()->json();
    }

    public function pagesEmployeesCreateEntry(Request $request) {

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
        $entry->request_r_code = $user->r_code;
        $entry->create_r_code = $request->who_analyze_r_code;
        $entry->request_sector = $user->sector_id;
        $entry->request_office = $user->office;
        $entry->date_hour = date('Y-m-d H:i:s');
        $entry->who_analyze_r_code = $request->who_analyze_r_code;
        $entry->is_approv = 1;

        $entry->justify = $request->justify;
        $entry->reason = $request->reason;
        $entry->is_liberate = 1;
        $entry->logistics_entry_exit_security_guard_id = $request->session()->get('security_guard_data')->id;
        $entry->logistics_entry_exit_gate_id = $request->session()->get('security_guard_data')->logistics_entry_exit_gate->id;
        $entry->request_action_time = date('Y-m-d H:i:s');
        $entry->save();

        DB::commit();

        return redirect()->back()->with('success', 'Entrada foi criada com sucesso!');
    }

    public function pagesVehicleList(Request $request) {

        $load_requests = EntryExitVehicle::with(
            'logistics_entry_exit_gate',
            'entry_exit_rent_vehicle',
            'who_analyze'
        )
            ->where('date_hour', '>=', date('Y-m-d', strtotime('- 2 month')))
            ->where('is_approv', 1)
			->orderByRaw('date_hour DESC, is_liberate, is_denied ASC');


        if ($request->search) {
            $load_requests->where(function ($q) use ($request) {
                $q->where('code', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('request_r_code', 'LIKE', '%'.$request->search.'%');
            });
        }

        return response()->json($load_requests->paginate(3));
    }

    public function pagesVehicleSingle(Request $request) {

        $load_requests = EntryExitVehicle::with(
            'logistics_entry_exit_gate',
            'entry_exit_rent_vehicle',
            'who_analyze'
        )
            ->where('is_approv', 1)
            ->where('id', $request->id)
            ->first();

        if (!$load_requests)
            return response()->json(['msg', 'Não foi possível encontrar a solicitação'], 400);

        return response()->json($load_requests);
    }

    public function pagesVehicleApprovOrReprov(Request $request) {

        $requests = EntryExitVehicle::where('is_approv', 1)
            ->where('is_denied', 0)
            ->where('is_cancelled', 0)
            ->where('is_liberate', 0)
            ->where('id', $request->id)
            ->first();

        if (!$requests)
            return response()->json(['msg' => 'A solicitação não foi encontrada ou já foi análisada.'], 400);

        $requests->is_liberate = $request->status == 1 ? 1 : 0;
        $requests->is_denied = $request->status == 2 ? 1 : 0;
        $requests->denied_reason = $request->description ? $request->description : null;
        $requests->logistics_entry_exit_security_guard_id = $request->session()->get('security_guard_data')->id;
        $requests->logistics_entry_exit_gate_id = $request->session()->get('security_guard_data')->logistics_entry_exit_gate->id;
        $requests->request_action_time = date('Y-m-d H:i:s');
        $requests->save();

        return response()->json([]);
    }

    public function pagesVehicleDeleteEntry(Request $request) {
        $entry = EntryExitVehicle::find($request->id);
        $entry->del_logistics_entry_exit_security_guard_id = $request->session()->get('security_guard_data')->id;
        $entry->del_description = $request->description;
        $entry->save();

        if (!$entry)
            return response()->json(['msg' => 'Não foi possível encontrar a solicitação.'], 400);

        $entry->delete();
        return response()->json();
    }

    public function pagesVehicleCreateEntry(Request $request) {

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

        $entry = new EntryExitVehicle;

        $entry->code = $this->codeGenerator('entry_exit_employees', $user->sector_id);
        $entry->request_user_type = $type_user;
        $entry->request_r_code = $user->r_code;
        $entry->create_r_code = $request->who_analyze_r_code;
        $entry->entry_exit_rent_vehicle_id = $request->entry_exit_rent_vehicle_id;
        $entry->request_sector = $user->sector_id;
        $entry->request_office = $user->office;
        $entry->date_hour = date('Y-m-d H:i:s');
        $entry->who_analyze_r_code = $request->who_analyze_r_code;
        $entry->is_approv = 1;

        $entry->justify = $request->justify;
        $entry->is_liberate = 1;
        $entry->km = $request->km;
        $entry->is_entry_exit = $request->is_entry_exit;
        $entry->logistics_entry_exit_security_guard_id = $request->session()->get('security_guard_data')->id;
        $entry->logistics_entry_exit_gate_id = $request->session()->get('security_guard_data')->logistics_entry_exit_gate->id;
        $entry->request_action_time = date('Y-m-d H:i:s');
        $entry->save();

        $vehicle = EntryExitRentVehicle::find($request->entry_exit_rent_vehicle_id);
        $vehicle->km = $request->km;
        $vehicle->save();

        DB::commit();

        return redirect()->back()->with('success', 'Solicitação foi criada com sucesso!');
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
                $row['id'] = $key->id;
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
	
	public function transporterDriverListDropdown(Request $request) {

        $name = $request->search;

		$cpf = formatCnpjCpf($name);
		
        $data = LogisticsTransporterDriver::with('logistics_transporter')->where('name', 'like', '%'. $name .'%')
                ->orWhere('identity', 'like', '%'. $name .'%')
				->orWhere('identity', 'like', '%'. $cpf .'%')
                ->orderBy('id', 'DESC')
                ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
				$transport = $key->logistics_transporter? ' ('.$key->logistics_transporter->name.')' : '';
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name.' - '. $key->identity.$transport;
                
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

    public function transporterVehicleListDropdown(Request $request) {

        $name = $request->search;
        $data = LogisticsTransporterVehicle::with('logistics_transporter')->where('registration_plate', 'like', '%'. $name .'%')
                ->orderBy('id', 'DESC')
                ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
				$transport = $key->logistics_transporter? $key->logistics_transporter->name : '';
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->registration_plate.' - '. ' ('.stringCut($transport, 28).')';
                
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

    public function transporterCartListDropdown(Request $request) {

        $name = $request->search;
        $data = LogisticsTransporterCart::with('logistics_transporter')->where('registration_plate', 'like', '%'. $name .'%')
                ->orderBy('id', 'DESC')
                ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->registration_plate;
                $row['type'] = $key->type_cart;
                
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
	
	public function transporterListDropdown(Request $request) {
        
        $name = $request->search;

        $data = LogisticsTransporter::where('name', 'like', '%'. $name .'%')->where('is_active', 1)
            ->orWhere(function ($query) use ($name) {
				$formatValue = formatCnpjCpf($name);
				$query->where('identity', 'like', '%'. $name .'%')->where('is_active', 1);
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name ." (". $key->identity .")";
                $row['name'] = $key->name;
                $row['identity'] = $key->identity;
                $row['address'] = $key->address;
                $row['city'] = $key->city;
                $row['state'] = $key->state;
                $row['phone'] = $key->phone;
                $row['email'] = $key->email;

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
	
	public function supplierListDropdown(Request $request) {

        $name = $request->search;

        $data = LogisticsSupplier::where('name', 'like', '%'. $name .'%')
			->orWhere(function($q) use ($name) {
				$formatValue = formatCnpjCpf($name);
				$q->where('identity', 'like', '%'. $name .'%');
			})
			->orderBy('id', 'DESC')
			->paginate(10);
        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name .'('.$key->identity.')';
                
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
