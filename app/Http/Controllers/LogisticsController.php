<?php
namespace App\Http\Controllers;

use App;
use Hash;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Model\LogisticsTransporter;
use App\Model\LogisticsTransporterDriver;
use App\Model\LogisticsTransporterVehicle;
use App\Model\LogisticsTransporterCart;
use App\Model\LogisticsWarehouse;
use App\Model\LogisticsWarehouseTypeContent;
use App\Model\LogisticsWarehouseApprov;
use App\Model\LogisticsWarehouseObservers;
use App\Model\LogisticsEntryExitGate;
use App\Model\LogisticsWarehouseOnEntryExitGate;
use App\Model\LogisticsWarehouseOnTypeContent;
use App\Model\LogisticsContainer;
use App\Model\LogisticsEntryExitSecurityGuard;
use App\Model\LogisticsEntryExitRequests;
use App\Model\LogisticsEntryExitVisitant;
use App\Model\LogisticsEntryExitRequestsItems;
use App\Model\LogisticsEntryExitRequestsAttachs;
use App\Model\LogisticsEntryExitRequestsPeople;
use App\Model\LogisticsEntryExitRequestsSchedule;
use App\Model\LogisticsSupplier;
use App\Model\Countries;
use App\Model\Users;
use App\Model\WarehouseEntryExitItems;

use App\Services\Departaments\Logistics\CargoTransport;
use App\Services\Departaments\Logistics\VisitantService;
use App\Services\Departaments\ProcessAnalyze;
use App\Imports\DefaultImport;
use App\Exports\DefaultHtmlExport;
use App\Exports\DefaultExport;
use App\Jobs\SendMailJob;
use App\Http\Controllers\Services\FileManipulationTrait;

use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class LogisticsController extends Controller
{
    use FileManipulationTrait;

    public function transporterList(Request $request) {

        $transporter = LogisticsTransporter::orderBy('id', 'DESC');

        $array_input = collect([
            'name',
            'identity',
            'status',
        ]);

        $array_input = putSession($request, $array_input, 'trans_');
        $filter_session = getSessionFilters('trans_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {



                if($name_filter == $filter_session[1]."name"){
                    $transporter->where('name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."identity"){
                    $transporter->where('identity', $value_filter);
                }
                if($name_filter == $filter_session[1]."status"){

                    $value = $value_filter == 'not' ? 0 : 1;
                    $transporter->where('is_active', $value_filter);    
                }
            }
        }

        return view('gree_i.logistics.transporter_list', [
            'transporter' => $transporter->paginate(10)
        ]);
    }    

    public function transporterEdit_do(Request $request) {

        try {

            if($request->transporter_id == 0) {

                $verify = LogisticsTransporter::where('identity', $request->identity)->first();
                if($verify)
                    return redirect()->back()->with('error', 'Transportador já cadastrado');

                $transporter = new LogisticsTransporter;
            } else {
                $transporter = LogisticsTransporter::find($request->transporter_id);
                if(!$transporter) {
                    return redirect()->back()->with('error', 'Transportador não encontrado!');
                }
            }

            $transporter->name = $request->name;
            $transporter->identity = $request->identity;
            $transporter->address = $request->address;
            $transporter->city = $request->city;
            $transporter->state = $request->state;
            $transporter->receptionist_name = $request->receptionist_name;
            $transporter->ramal = $request->receptionist_ramal;
            $transporter->phone = $request->phone;
            $transporter->email = $request->email;
            $transporter->is_active = 1;
            $transporter->save();

            return redirect()->back()->with('success', 'Transportador cadastrado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }    
    }

    public function transporterChangeStatus(Request $request, $id, $status) {

        try {

            $transporter = LogisticsTransporter::find($id);
            if(!$transporter) {
                return redirect()->back()->with('error', 'Transportador não encontrado!');
            }

            $transporter->is_active = $status;
            $transporter->save();

            return redirect()->back()->with('success', 'Status atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }        
    }

    public function transporterListDropdown(Request $request) {
        
        $name = $request->search;

        $data = LogisticsTransporter::where('name', 'like', '%'. $name .'%')->where('is_active', 1)
            ->orWhere(function ($query) use ($name) {
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

    public function transporterDriverList(Request $request) {

        $drivers = LogisticsTransporterDriver::with('logistics_transporter', 'logistics_supplier')->orderBy('id', 'DESC');

        $array_input = collect([
            'name',
            'identity',
            'transporter',
        ]);

        $array_input = putSession($request, $array_input, 'driver_');
        $filter_session = getSessionFilters('driver_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."name"){

                    $drivers->where('name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."identity"){
                    $drivers->where('identity', $value_filter);
                }
                if($name_filter == $filter_session[1]."transporter"){
                    $drivers->whereHas('logistics_transporter', function($q) use ($value_filter) {
                        $q->where('id', $value_filter);
                    });
                }
            }
        }

        return view('gree_i.logistics.transporter_driver_list', [
            'drivers' => $drivers->paginate(10)
        ]);
    }    

    public function transporterDriverEdit_do(Request $request) {

        try {

            if($request->driver_id == 0) {

                $verify = LogisticsTransporterDriver::where('identity', $request->identity)->first();
                if($verify)
                    return redirect()->back()->with('error', 'Motorista Já cadastrado');

                $driver = new LogisticsTransporterDriver;

            } else {
                $driver = LogisticsTransporterDriver::find($request->driver_id);
                if(!$driver)
                    return redirect()->back()->with('error', 'Motorista não encontrado!');
            }

            $driver->transporter_id = $request->transporter;
            $driver->supplier_id = $request->supplier;
            $driver->name = $request->name;
            $driver->identity = $request->identity;
            $driver->gender = $request->gender;
            $driver->phone = $request->phone;
            $driver->cnh_url = $request->hasFile('file_cnh') ? $this->uploadFile($request->file_cnh, $request) : $driver->cnh_url;
            $driver->save();

            return redirect()->back()->with('success', 'Motorista cadastrado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }    
    }

    public function transporterDriverRemoveFileAjax(Request $request) {
        
        try {

            $driver = LogisticsTransporterDriver::find($request->id);
            if(!$driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'Motorista não encontrado!'
                ], 400);
            } else {

                $driver->cnh_url = null;
                if($driver->save())
                    removeS3($request->url);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Documento excluído!',
                ], 200);
            }

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }    
    }

    public function transporterVehicleList(Request $request) {

        $vehicle = LogisticsTransporterVehicle::with('logistics_transporter', 'logistics_supplier')->orderBy('id', 'DESC');

        $array_input = collect([
            'registration_plate',
            'transporter',
            'type_vehicle',
        ]);

        $array_input = putSession($request, $array_input, 'vehicle_');
        $filter_session = getSessionFilters('vehicle_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."registration_plate"){
                    $vehicle->where('registration_plate', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."type_vehicle"){
                    $vehicle->where('type_vehicle', $value_filter);
                }
                if($name_filter == $filter_session[1]."transporter"){
                    $vehicle->whereHas('logistics_transporter', function($q) use ($value_filter) {
                        $q->where('id', $value_filter);
                    });
                }
            }
        }

        return view('gree_i.logistics.transporter_vehicle_list', [
            'vehicle' => $vehicle->paginate(10)
        ]);
    }

    public function transporterVehicleEdit_do(Request $request) {

        try {

            if($request->vehicle_id == 0) {

                $verify = LogisticsTransporterVehicle::where('registration_plate', $request->registration_plate)->first();
                if($verify)
                    return redirect()->back()->with('error', 'Veículo já cadastrado');

                $vehicle = new LogisticsTransporterVehicle;
            } else {
                $vehicle = LogisticsTransporterVehicle::find($request->vehicle_id);
                if(!$vehicle) {
                    return redirect()->back()->with('error', 'Motorista não encontrado!');
                }
            }

            $vehicle->transporter_id = $request->transporter;
            $vehicle->supplier_id = $request->supplier;
             $vehicle->registration_plate = str_replace('-', '', $request->registration_plate);
            $vehicle->type_vehicle = $request->type_vehicle;
            $vehicle->is_articulated = $request->is_articulated;
            $vehicle->save();

            return redirect()->back()->with('success', 'Veículo salvo com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }    
    }

    public function transporterCartList(Request $request) {
        $cart = LogisticsTransporterCart::with('logistics_transporter', 'logistics_supplier')->orderBy('id', 'DESC');

        $array_input = collect([
            'transporter',
            'type_cart',
            'registration_plate'
        ]);

        $array_input = putSession($request, $array_input, 'cart_');
        $filter_session = getSessionFilters('cart_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."registration_plate"){
                    $cart->where('registration_plate', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."type_cart"){
                    $cart->where('type_cart', $value_filter);
                }
                if($name_filter == $filter_session[1]."transporter"){
                    $cart->whereHas('logistics_transporter', function($q) use ($value_filter) {
                        $q->where('id', $value_filter);
                    });
                }
            }
        }

        return view('gree_i.logistics.transporter_cart_list', [
            'cart' => $cart->paginate(10)
        ]);
    }    

    public function transporterCartEdit_do(Request $request) {

        try {

            if($request->cart_id == 0) {

                $verify = LogisticsTransporterCart::where('registration_plate', $request->registration_plate)->first();
                if($verify) 
                    return redirect()->back()->with('error', 'Carreta já está cadastrada!');

                $cart = new LogisticsTransporterCart;
            } else {
                $cart = LogisticsTransporterCart::find($request->cart_id);
                if(!$cart) {
                    return redirect()->back()->with('error', 'Carreta não encontrada!');
                }
            }

            $cart->transporter_id = $request->transporter;
            $cart->supplier_id = $request->supplier;
            $cart->registration_plate = str_replace('-', '', $request->registration_plate);
            $cart->type_cart = $request->type_cart;
            $cart->save();

            return redirect()->back()->with('success', 'Carreta salva com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }    
    }

    public function warehouseList(Request $request) {

        $warehouse = LogisticsWarehouse::with(
                                    'logistics_entry_exit_gate',
                                    'logistics_warehouse_type_content',
                                    'analyze_approv.users',
                                    'analyze_observ.users')->orderBy('id', 'DESC'); 

        $array_input = collect([
            'warehouse',
            'approv',
            'gate',
            'content'
        ]);

        $array_input = putSession($request, $array_input, 'ware_');
        $filter_session = getSessionFilters('ware_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."warehouse"){
                    $warehouse->where('name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."approv"){
                    $warehouse->whereHas('logistics_warehouse_approv', function($q) use ($value_filter) {
                        $q->where('r_code', $value_filter);
                    });
                }
                if($name_filter == $filter_session[1]."gate") {

                    $warehouse->whereHas('logistics_entry_exit_gate', function($q) use ($value_filter) {
                        $q->where('logistics_entry_exit_gate_id', $value_filter);
                    });
                }
                if($name_filter == $filter_session[1]."content") {
                    $warehouse->whereHas('logistics_warehouse_type_content', function($q) use ($value_filter) {
                        $q->where('logistics_warehouse_type_content_id', $value_filter);
                    });
                }
            }
        }

        return view('gree_i.logistics.warehouse_list', [
            'warehouse' => $warehouse->paginate(10)
        ]);
    }    

    public function warehouseEdit(Request $request, $id) {

        if($id == 0) {

            $ware_name = '';
            $ware_address = '';
            $ware_city = '';
            $ware_state = '';
            $ware_zipcode = '';
            $arr_approv = [];
            $arr_gate = [];
            $arr_type_content = [];
            $arr_observers = [];
            $arr_approv_verify = [];

        } else {

            $warehouse = LogisticsWarehouse::with('logistics_warehouse_approv.users', 
                                              'logistics_warehouse_observers.users',
                                              'logistics_entry_exit_gate',
                                              'logistics_warehouse_type_content',
                                              'analyze_approv.users',
                                              'analyze_observ.users')->find($id);

            if(!$warehouse) {
                return redirect()->back()->with('error', 'Galpão não foi encontrado!');
            } 

            $ware_name = $warehouse->name;
            $ware_address = $warehouse->address;
            $ware_city = $warehouse->city;
            $ware_state = $warehouse->state;
            $ware_zipcode = $warehouse->zipcode;
            $arr_approv_verify = [];

            $arr_approv = [];
            
            $ware_approv = $warehouse->analyze_approv->sortBy('position')->groupBy('position');

            foreach($ware_approv as $approv) {

                if($approv->count() > 1) {
                    $new_arr = [];
                    $new_arr_sub = [];
                    $sub_arr = [];
                    
                    foreach($approv->toArray() as $i => $key) {

                        if($i == 0) {
                            $new_arr['arr_approvers'] = null;
                            $new_arr['name'] = $key['users']['full_name'];
                            $new_arr['picture'] = $key['users']['picture'];
                            $new_arr['r_code'] = $key['r_code'];
                            $new_arr['id'] = $key['id'];
                        } else {
                            
                            $sub_arr['name'] = $key['users']['full_name'];
                            $sub_arr['picture'] = $key['users']['picture'];
                            $sub_arr['r_code'] = $key['r_code'];
                            $sub_arr['id'] = $key['id'];

                            array_push($new_arr_sub, $sub_arr);
                            $new_arr['arr_approvers'] = $new_arr_sub;
                        }

                        array_push($arr_approv_verify, $key['r_code']);
                    }
                    array_push($arr_approv, $new_arr);
                }
                if($approv->count() == 1) {

                    $new_arr = [];
                    $new_arr['arr_approvers'] = null;
                    $new_arr['name'] = $approv->first()->users->full_name;
                    $new_arr['picture'] = $approv->first()->users->picture;
                    $new_arr['r_code'] = $approv->first()->r_code;
                    $new_arr['id'] = $approv->first()->id;

                    array_push($arr_approv, $new_arr);
                    array_push($arr_approv_verify, $approv->first()->r_code);
                }    
            }

            $arr_gate_old = $warehouse->logistics_entry_exit_gate->toArray();
            $arr_gate = [];
            foreach ($arr_gate_old as $gate) {

                $arr_gate_new = [];
                $arr_gate_new['id'] = $gate['id'];
                $arr_gate_new['name'] = $gate['name'];
                $arr_gate_new['phone'] = $gate['phone'];
                $arr_gate_new['ramal'] = $gate['ramal'];
                array_push($arr_gate, $arr_gate_new);
            }


            $arr_type_content_old = $warehouse->logistics_warehouse_type_content->toArray();
            $arr_type_content = [];
            foreach ($arr_type_content_old as $content) {
                $arr_content_new = [];
                $arr_content_new['description'] = $content['description'];
                $arr_content_new['id'] = $content['id'];
                array_push($arr_type_content, $arr_content_new);
            }    

            $arr_observers = [];
            $arr_observers_old = $warehouse->analyze_observ;
            foreach ($arr_observers_old as $observer) {
                $arr_observers_new = [];
                $arr_observers_new['id'] = $observer->id;
                $arr_observers_new['name'] = $observer->users->full_name;
                $arr_observers_new['picture'] = $observer->users->picture;
                $arr_observers_new['r_code'] = $observer->users->r_code;
                array_push($arr_observers, $arr_observers_new);
                array_push($arr_approv_verify, $observer->users->r_code);
            }
        }

        return view('gree_i.logistics.warehouse_edit', [
            'id' => $id,
            'ware_name' => $ware_name,
            'ware_address' => $ware_address,
            'ware_city' => $ware_city,
            'ware_state' => $ware_state,
            'ware_zipcode' => $ware_zipcode,
            'arr_approv' => $arr_approv,
            'arr_gate' => $arr_gate,
            'arr_type_content' => $arr_type_content,
            'arr_observers' => $arr_observers,
            'arr_approv_verify' => $arr_approv_verify
        ]);
    }    

    public function warehouseEdit_do(Request $request) {

        DB::beginTransaction();

        try {

            if($request->id == 0) {
                $warehouse = new LogisticsWarehouse;
            } else {
                $warehouse = LogisticsWarehouse::find($request->id);
                if(!$warehouse)
                    return redirect()->back()->with('error', 'Galpão não encontrado');
            }

            $warehouse->name = $request->name;
            $warehouse->address = $request->address;
            $warehouse->city = $request->city;
            $warehouse->state = $request->state;
            $warehouse->zipcode = $request->zipcode;
            
            if($warehouse->save()) {    

                $arr_approv = json_decode($request->arr_approv, true);
                $arr_observers = json_decode($request->arr_observers, true);
                $arr_gate = json_decode($request->arr_gate, true);
                $arr_content = json_decode($request->arr_content, true);

                if(count($arr_approv) > 0) {

                    foreach($arr_approv as $index => $approv) {
                        $arr_approv[$index]['position'] = $index + 1;
        
                        if($approv['arr_approvers'] != null) {
        
                            foreach($approv['arr_approvers'] as $sub_index => $sub_approv) {
                                $arr_approv[$index]['arr_approvers'][$sub_index]['position'] = $index + 1;
                                array_push($arr_approv, $arr_approv[$index]['arr_approvers'][$sub_index]);
                            }
                        }
                    }

                    $arr_new = [];
                    $arr_update = [];
                    foreach($arr_approv as $key) {
                        if($key['id'] == 0) {
                            $arr = array_diff_key($key, ["arr_approvers" => 1, "name" => 1, "picture" => 1, "id" => 1]);
                            array_push($arr_new, $arr);
                        } else {
                            array_push($arr_update, $key);
                        }
                    }
                    
                    $this->saveMorphApprovers($warehouse, $arr_new, $arr_update, $request->arr_approv_delete);
                }

                $observers_new = [];
                foreach($arr_observers as $key) {   
                    if($key['id'] == 0) {
                        $arr = array_diff_key($key, ["name" => 1, "picture" => 1]);
                        array_push($observers_new, $arr);
                    }    
                }
                $this->saveMorphObservers($warehouse, $observers_new, $request->arr_observers_delete);
                

                if(count($arr_gate) > 0) {
                    $fields_remove = ["name" => 1, "phone" => 1, "ramal" => 1];
                    $this->saveRelations(new LogisticsWarehouseOnEntryExitGate, $arr_gate, $fields_remove, $warehouse->id, "logistics_warehouse_id", "logistics_entry_exit_gate_id", "id");
                }

                if(count($arr_content) > 0) {
                    $fields_remove = ["description" => 1];
                    $this->saveRelations(new LogisticsWarehouseOnTypeContent, $arr_content, $fields_remove, $warehouse->id, "logistics_warehouse_id", "logistics_warehouse_type_content_id", "id");
                }
            }
    
            DB::commit();
            return redirect('/logistics/warehouse/list')->with('success', 'Galpão salvo com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveMorphApprovers($model, $arr_new, $arr_update, $arr_delete, $type = false) {

        $arr_delete_rcode = json_decode($arr_delete, true);
        if(count($arr_delete_rcode) > 0) {
            if(!$type) {
                $model->analyze_approv()->whereIn('r_code', $arr_delete_rcode)->delete();
            } else {
                foreach($model->rtd_approvers()->whereIn('r_code', $arr_delete_rcode) as $key) {
                    $key->delete();
                }
            }   
        }

        if(count($arr_new) > 0)
            $model->analyze_approv()->createMany($arr_new);

        if(count($arr_update) > 0) {

            foreach($arr_update as $key) {

                if(!$type) {
                    $row = $model->analyze_approv();
                } else {
                    $row = $model->rtd_approvers();
                }
                
                $approv = $row->where('r_code', $key['r_code'])->first();
                $approv->position = $key['position'];
                $approv->save();
            }
        }       
    }

    private function saveMorphObservers($model, $arr_new, $arr_delete, $type = false) {

        $arr_delete_decode = json_decode($arr_delete, true);

        if(count($arr_delete_decode) > 0) {
            if(!$type) {
                $model->analyze_observ()->whereIn('r_code', $arr_delete_decode)->delete();
            } else {
                foreach($model->rtd_observers()->whereIn('r_code', $arr_delete_decode) as $key) {
                    $key->delete();
                }
            }   
        }

        if(count($arr_new) > 0)
            $model->analyze_observ()->createMany($arr_new);
    }

    private function saveRelations($model, $arr_request, $fields_remove, $parent_id, $parent_name, $col_verify, $request_verify = null, $table_name = null) {

        try {

            $request = collect($arr_request);

            if($request_verify) {
                $request_pluck = $request->pluck($request_verify);
            } else {
                $request_pluck = $request->pluck($col_verify);
            }
            
            $query = $model::where($parent_name, $parent_id)->pluck($col_verify);
            $delete = $query->diff($request_pluck);
            
            $model::whereIn($col_verify, $delete)->where($parent_name, $parent_id)->delete();
            $request_pluck = $request_pluck->diff($query);

            $arr = array();
            foreach ($request_pluck as $index => $val) {

                $req_values = (array) $request[$index];
                if($request_verify) {
                    $arr_diff = array_diff_key($req_values, [$request_verify => $val]);
                    $arr_diff[$col_verify] = $val;
                    $new_arr = array_diff_key($arr_diff, $fields_remove);
                } else {
                    $new_arr = array_diff_key($req_values, $fields_remove);
                }

                $new_arr[''.$parent_name.''] = $parent_id;
                array_push($arr, $new_arr);
            }

            $model->insert($arr);
        
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function warehouseTypeContentEditAjax(Request $request) {

        try {

            if($request->id == 0) {
                $type = new LogisticsWarehouseTypeContent;
            } else {
                $type = LogisticsWarehouseTypeContent::find($request->id);
                if(!$type) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipo de conteúdo já cadastrado!'
                    ], 400);
                }
            }

            $type->description = $request->description;
            $type->save();

            return response()->json([
                'success' => true,
                'message' => 'Tipo de conteúdo salvo com sucesso!'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }     

    }

    public function warehouseTypeContentDropdown(Request $request) {

        $name = $request->search;

        $data = LogisticsWarehouseTypeContent::where('description', 'like', '%'. $name .'%')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->description;
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

    public function warehouseTypeContentDelete(Request $request) {

        try {

            $content = LogisticsWarehouseTypeContent::find($request->id);
            if($content)
                $content->delete();
            else
                throw new \Exception('Tipo de conteúdo não encontrado!');
            
            return response()->json([
                'success' => true,
                'message' => 'Tipo de conteúdo excluído com sucesso!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } 
    }    

    public function gateEditAjax(Request $request) {
        try {

            if($request->id == 0) {
                $gate = new LogisticsEntryExitGate;
            } else {
                $gate = LogisticsEntryExitGate::find($request->id);
                if(!$gate) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Portaria não foi encontrada!'
                    ], 400);
                }
            }

            $gate->name = $request->name;
            $gate->phone = $request->phone;
            $gate->ramal = $request->ramal;
            $gate->save();

            return response()->json([
                'success' => true,
                'message' => 'Portaria salva com sucesso!'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }     
    }

    public function gateDropdownList(Request $request) {
        $name = $request->search;
        $data = LogisticsEntryExitGate::where('name', 'like', '%'. $name .'%')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name;
                $row['phone'] = $key->phone;
                $row['ramal'] = $key->ramal;
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

    public function gateDelete(Request $request) {

        try {

            $gate = LogisticsEntryExitGate::find($request->id);
            if($gate)
                $gate->delete();
            else
                throw new \Exception('Portaria não foi encontrada!');
            
            return response()->json([
                'success' => true,
                'message' => 'Portaria excluída com sucesso!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } 
    }

    public function containerEdit(Request $request, $id) {

        if($id == 0) {
            $transporter = 0;
            $description = '';
            $number_container = '';
            $number_fleet = '';
            $company_name = '';
            $company_identity = '';
            $company_address = '';
            $company_city = '';
            $company_state = '';
            $company_country = 0;
            $company_phone = '';
            $company_ramal = '';
            $company_email = '';
        } else {

            $container = LogisticsContainer::with('logistics_transporter')->find($id);

            $transporter = $container->transporter_id;
            $description = $container->description;
            $number_container = $container->number_container;
            $number_fleet = $container->number_fleet;

            if($container->logistics_transporter) {

                $company_name = $container->logistics_transporter->name;
                $company_identity = $container->logistics_transporter->identity;
                $company_address = $container->logistics_transporter->address;
                $company_city = $container->logistics_transporter->city;
                $company_country = 0;
                $company_state = $container->logistics_transporter->state;
                $company_phone = $container->logistics_transporter->phone;
                $company_ramal = $container->logistics_transporter->ramal;
                $company_email = $container->logistics_transporter->email;
            } else {
                $company_name = $container->name;
                $company_identity = $container->identity;
                $company_address = $container->address;
                $company_city = $container->city;
                $company_country = $container->country;
                $company_state = $container->state;
                $company_phone = $container->phone;
                $company_ramal = $container->ramal;
                $company_email = $container->email;
            }
        }

        $country = Countries::orderBy('name')->get();

        return view('gree_i.logistics.container_edit', [
            'id' => $id,
            'transporter' => $transporter,
            'description' => $description,
            'number_container' => $number_container,
            'number_fleet' => $number_fleet,
            'company_name' => $company_name,
            'company_identity' => $company_identity,
            'company_address' => $company_address,
            'company_city' => $company_city,
            'company_country' => $company_country,
            'company_state' => $company_state,
            'company_phone' => $company_phone,
            'company_ramal' => $company_ramal,
            'company_email' => $company_email,
            'country' => $country
        ]);
    }

    public function containerEdit_do(Request $request) {

        try {

            if($request->id == 0) {
                $container = new LogisticsContainer;
            } else {
                $container = LogisticsContainer::find($request->id);
                if(!$container) {
                    return redirect()->back()->with('error', 'Container não encontrado!');
                }
            }   
            
            if(!isset($request->transporter)) {
                $container->transporter_id = 0;
                $container->name = $request->company_name;
                $container->identity = $request->company_identity;
                $container->address = $request->company_address;
                $container->city = $request->company_city;
                $container->state = $request->company_state;
                $container->country = $request->company_country;
                $container->phone = $request->company_phone;
                $container->email = $request->company_email;
                $container->ramal = $request->company_ramal;
            } else {
                $container->transporter_id = $request->transporter;
                $container->name = null;
                $container->identity = null;
                $container->address = null;
                $container->city = null;
                $container->state = null;
                $container->country = null;
                $container->phone = null;
                $container->email = null;
                $container->ramal = null;
            }

            $container->description = $request->description;
            $container->number_container = $request->number_container;
            $container->number_fleet = $request->number_fleet;
            $container->save();

            return redirect('/logistics/container/list')->with('success', 'Container salvo com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }    
    }

    public function containerList(Request $request) {

        $container = LogisticsContainer::with('logistics_transporter')->orderBy('id', 'DESC');

        $array_input = collect([
            'number_container',
            'transporter',
            'company_name'
        ]);

        $array_input = putSession($request, $array_input, 'container_');
        $filter_session = getSessionFilters('container_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."number_container") {
                    $container->where('number_container', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."transporter") {
                    $container->whereHas('logistics_transporter', function($q) use ($value_filter) {
                        $q->where('id', $value_filter);
                    });
                }
                if($name_filter == $filter_session[1]."company_name") {
                    $container->where('name', 'like', '%'.$value_filter.'%');
                }
            }
        }

        $country = Countries::orderBy('name')->get();

        return view('gree_i.logistics.container_list', [
            'container' => $container->paginate(10),
            'country' => $country
        ]);
    }

    public function securityGuardEdit(Request $request, $id) {

        if($id == 0) {

            $entry_exit_gate = 0;
            $name = '';
            $identity = '';
            $phone_1 = '';
            $phone_2 = '';
            $picture = '';
            $password = '';
            $company_name = '';
            $begin_hour_work = '';
            $final_hour_work = '';
            $working_turn = '';
            $is_supervisor = 0;
            $is_active = 1;
            $entry_exit_gate_name = '';
        } else {

            $container = LogisticsEntryExitSecurityGuard::with('logistics_entry_exit_gate')->find($id);

            $entry_exit_gate = $container->entry_exit_gate_id;
            $entry_exit_gate_name = $container->logistics_entry_exit_gate->name;
            $name = $container->name;
            $identity = $container->identity;
            $phone_1 = $container->phone_1;
            $phone_2 = $container->phone_2;
            $picture = $container->picture;
            $company_name = $container->company_name;
            $begin_hour_work = $container->begin_hour_work;
            $final_hour_work = $container->final_hour_work;
            $working_turn = $container->working_turn;
            $is_supervisor = $container->is_supervisor;
            $is_active = $container->is_active;
        }

        return view('gree_i.logistics.security_guard_edit', [
            'id' => $id,
            'is_supervisor' => $is_supervisor,
            'entry_exit_gate' => $entry_exit_gate,
            'entry_exit_gate_name' => $entry_exit_gate_name,
            'name' => $name,
            'identity' => $identity,
            'phone_1' => $phone_1,
            'phone_2' => $phone_2,
            'picture' => $picture,
            'company_name' => $company_name,
            'begin_hour_work' => $begin_hour_work,
            'final_hour_work' => $final_hour_work,
            'working_turn' => $working_turn,
            'is_active' => $is_active
        ]);
    }

    public function securityGuardEdit_do(Request $request) {

        try {

            if($request->id == 0) {

                $verify = LogisticsEntryExitSecurityGuard::where('identity', $request->identity)->first();
                if($verify) 
                    return redirect()->back()->with('error', 'Vigilante já cadastrado');

                $guard = new LogisticsEntryExitSecurityGuard;
            } else {
                $guard = LogisticsEntryExitSecurityGuard::find($request->id);
                if(!$guard) {
                    return redirect()->back()->with('error', 'Vigilante não encontrado!');
                }
            }   

            $guard->entry_exit_gate_id = $request->entry_exit_gate;
            $guard->name = $request->name;
            $guard->identity = $request->identity;
            $guard->phone_1 = $request->phone_1;
            $guard->phone_2 = $request->phone_2;
            $guard->is_supervisor = $request->is_supervisor;
            $guard->company_name = $request->company_name;
            $guard->begin_hour_work = $request->begin_hour_work;
            $guard->final_hour_work = $request->final_hour_work;
            $guard->working_turn = $request->working_turn;
            
            if($request->password)
                $guard->password = Hash::make($request->password);

            $guard->is_active = $request->is_active;

            if ($request->hasFile('picture')) {
                $guard->picture = $this->uploadFile($request->picture, $request);
            }
            $guard->save();
            return redirect('/logistics/security/guard/list')->with('success', 'Vigilante salvo com sucesso!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }       
        
    }    

    public function securityGuardList(Request $request) {

        $guard = LogisticsEntryExitSecurityGuard::with('logistics_entry_exit_gate')->orderBy('id', 'DESC');

        $array_input = collect([
            'gate',
            'name',
            'working_turn'
        ]);

        $array_input = putSession($request, $array_input, 'guard_');
        $filter_session = getSessionFilters('guard_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."gate") {
                    $guard->whereHas('logistics_entry_exit_gate', function($q) use ($value_filter) {
                        $q->where('id', $value_filter);
                    });
                }
                if($name_filter == $filter_session[1]."name") {
                    $guard->where('name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."working_turn") {
                    $guard->where('working_turn', $value_filter);
                }
            }
        }
        
        return view('gree_i.logistics.security_guard_list', [
            'guard' => $guard->paginate(10)
        ]);
    }

    public function requestCargoTransportList(Request $request) {

        $entry_exit = LogisticsEntryExitRequests::with(
                            'logistics_entry_exit_gate',
                            'logistics_entry_exit_requests_items',
                            'logistics_transporter',
                            'logistics_transporter_driver',
                            'logistics_transporter_vehicle',
                            'logistics_transporter_cart',
                            'logistics_container',
                            'logistics_warehouse',
                            'logistics_supplier',
                            'logistics_warehouse_type_content',
                            'logistics_entry_exit_requests_attachs',
                            'logistics_entry_exit_requests_people',
                            'request_user')->whereNotIn('type_reason', [3, 9, 10])->orderBy('id', 'DESC');

        if(!$request->code && $entry_exit->count() > 0) {
            if (!hasPermManager(26)) {
                $entry_exit->where('request_r_code', $request->session()->get('r_code'))
                            ->orWhere('create_r_code', $request->session()->get('r_code'));
                            
            }                   
        }       

        $array_input = collect([
            'code',
            'type_reason',
            'is_entry_exit',
            'request_r_code',
            'status', 
            'start_date',
            'end_date',
			'gate',
			'supplier_id'
        ]);

        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code") {
                    $entry_exit->where('code', $value_filter);
                }
				if($name_filter == $filter_session[1]."gate") {
                    $entry_exit->where('entry_exit_gate_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."type_reason") {
                    $entry_exit->where('type_reason', $value_filter);
                }
                if($name_filter == $filter_session[1]."is_entry_exit") {
                    $entry_exit->where('is_entry_exit', $value_filter);
                }
                if($name_filter == $filter_session[1]."request_r_code") {
                    $entry_exit->where('request_r_code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status") {

                    if($value_filter == 1) {
                        $entry_exit->where('is_reprov', 1);
                    } elseif ($value_filter == 2) {
                        $entry_exit->where('is_cancelled', 1);
                    } elseif ($value_filter == 3) {
                        $entry_exit->where('is_denied', 1);
                    } elseif ($value_filter == 4) {
                        $entry_exit->where('is_liberate', 1);
                    } elseif ($value_filter == 5) {
                        $entry_exit->where('has_analyze', 1);
                    } elseif ($value_filter == 6) {
                        $entry_exit->where('is_approv', 1);
                    } else {
                        $entry_exit->where('is_reprov', 0)
                                   ->where('is_cancelled', 0)
                                   ->where('is_denied', 0)
                                   ->where('is_liberate', 0)
                                   ->where('has_analyze', 0)
                                   ->where('is_approv', 0);
                    }
                }
                if($name_filter == $filter_session[1]."start_date") {
                    $entry_exit->whereDate('date_hour', '>=', $value_filter);
                }
                if($name_filter == $filter_session[1]."end_date") {
                    $entry_exit->whereDate('date_hour', '<=', $value_filter);
                }
				if($name_filter == $filter_session[1]."supplier_id") {
                    $entry_exit->where('supplier_id', $value_filter);
                }
            }
        }

        if($request->export == 1) {

            $pattern = [
                'view' => 'gree_i.logistics.reports.report_request_cargo_transport',
                'entry_exit' => $entry_exit->get()
            ];
            return Excel::download(new DefaultHtmlExport($pattern), 'CargoTransportExport-'. date('Y-m-d') .'.xlsx');
        }
		
		$gates = App\Model\LogisticsEntryExitGate::all();

        return view('gree_i.logistics.request_cargo_transport_list', [
            'entry_exit' => $entry_exit->paginate(10),
			'gates' => $gates,
        ]);
    }
    
    public function requestCargoTransportEdit(request $request, $id) {        

        if($id != 0) {
            $entry_exit = LogisticsEntryExitRequests::with('logistics_entry_exit_requests_items', 
                                                           'logistics_entry_exit_requests_attachs',
                                                           'logistics_entry_exit_requests_people')->find($id);

            if(!$entry_exit) {
                return redirect()->back()->with('error', 'Requisição não encontrada');
            }
        }    
        
        $type_reason = $id ? $entry_exit->type_reason : '';
        $is_entry_exit = $id ? $entry_exit->is_entry_exit : 1;
        $release_date = $id ? date('d/m/Y', strtotime($entry_exit->date_hour)) : '';
        $release_hour = $id ? date('H:i', strtotime($entry_exit->date_hour)) : '';
		$release_hour_initial = $id && $entry_exit->date_hour_initial ? date('H:i', strtotime($entry_exit->date_hour_initial)) : '';
        $request_r_code = $id ? $entry_exit->request_r_code : '';
        $request_sector = $id ? $entry_exit->request_sector : '';
        $request_phone = $id ? $entry_exit->request_phone : '';
        $request_ramal = $id ? $entry_exit->request_ramal : '';
        $entry_exit_gate_id = $id ? $entry_exit->entry_exit_gate_id : 0;
        $warehouse_id = $id ? $entry_exit->warehouse_id :0; 
        $supplier_id = $id ? $entry_exit->supplier_id :0; 
        $reason = $id ? $entry_exit->reason : '';
        $entry_restriction = $id ? $entry_exit->entry_restriction : '';
        $request_forwarding = $id ? $entry_exit->request_forwarding : '';
        $transporter_id = $id ? $entry_exit->transporter_id : 0;
        $is_content = $id ? $entry_exit->is_content : 0;
        $code_seal = $id ? $entry_exit->code_seal : '';
        $transporter_driver_id = $id ? $entry_exit->transporter_driver_id : 0;
        $transporter_vehicle_id = $id ? $entry_exit->transporter_vehicle_id : 0;
        $transporter_cart_id = $id ? $entry_exit->transporter_cart_id : 0;
        $transporter_container_id = $id ? $entry_exit->transporter_container_id : 0;
        $is_transhipment = $id ? $entry_exit->is_transhipment : 0;
        $transhipment_container_id = $id ? $entry_exit->transhipment_container_id : 0;
        $warehouse_type_content_id = $id ? $entry_exit->warehouse_type_content_id : 0;
        $nfe_number = $id ? $entry_exit->nfe_number : '';
        $invoice_number = $id ? $entry_exit->invoice_number : '';
        $nfe_url = $id ? $entry_exit->nfe_url : '';
        $invoice_url = $id ? $entry_exit->invoice_url : '';
        $code_gr = $id ? $entry_exit->code_gr : '';
        $gr_url = $id ? $entry_exit->gr_url : '';
		$code_di = $id ? $entry_exit->code_di : '';
        $url_di = $id ? $entry_exit->url_di : '';

        $is_approv = $id ? $entry_exit->is_approv : 0;
        $is_reprov = $id ? $entry_exit->is_reprov : 0;
        $has_analyze = $id ? $entry_exit->has_analyze : 0;
        $is_cancelled = $id ? $entry_exit->is_cancelled : 0;
        
        $users_request = Users::all();
        $entry_exit_gate = LogisticsEntryExitGate::all();
        $warehouse = LogisticsWarehouse::all();
        $supplier = LogisticsSupplier::all();
        $transporter = LogisticsTransporter::all();
        $drivers = LogisticsTransporterDriver::all();
        $vehicles = LogisticsTransporterVehicle::all();
        $carts = LogisticsTransporterCart::all();
        $containers = LogisticsContainer::all();
        $type_content = LogisticsWarehouseTypeContent::all();
        $arr_items = $id ? $entry_exit->logistics_entry_exit_requests_items : []; 
        $arr_attachs = $id ? $entry_exit->logistics_entry_exit_requests_attachs : [];
        $arr_people = $id ? $entry_exit->logistics_entry_exit_requests_people : [];

        return view('gree_i.logistics.request_cargo_transport', [
            'id' => $id,
			'list_hours' => $this->getFreeHoursToReceivement(),
            'type_reason' => $type_reason,
            'is_entry_exit' => $is_entry_exit,
            'release_date' => $release_date,
            'release_hour' => $release_hour,
			'release_hour_initial' => $release_hour_initial,
            'request_r_code' => $request_r_code,
            'request_sector' => $request_sector,
            'request_phone' => $request_phone,
            'request_ramal' => $request_ramal,
            'entry_exit_gate_id' => $entry_exit_gate_id,
            'warehouse_id' => $warehouse_id,
            'supplier_id' => $supplier_id,
            'reason' => $reason,
            'entry_restriction' => $entry_restriction,
            'request_forwarding' => $request_forwarding,
            'transporter_id' => $transporter_id,
            'is_content' => $is_content,
            'code_seal' => $code_seal,
            'transporter_driver_id' => $transporter_driver_id,
            'transporter_vehicle_id' => $transporter_vehicle_id,
            'transporter_cart_id' => $transporter_cart_id,
            'transporter_container_id' => $transporter_container_id,
            'is_transhipment' => $is_transhipment,
            'transhipment_container_id' => $transhipment_container_id,
            'warehouse_type_content_id' => $warehouse_type_content_id,
            'nfe_number' => $nfe_number,
            'invoice_number' => $invoice_number,
            'nfe_url' => $nfe_url,
            'invoice_url' => $invoice_url,
            'code_gr' => $code_gr,
            'gr_url' => $gr_url,
			'code_di' => $code_di,
            'url_di' => $url_di,

            'is_approv' => $is_approv,
            'is_reprov' => $is_reprov,
            'has_analyze' => $has_analyze,
            'is_cancelled' => $is_cancelled,

            'users_request' => $users_request,
            'entry_exit_gate' => $entry_exit_gate,
            'warehouse' => $warehouse,
            'supplier' => $supplier,
            'transporter' => $transporter,
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'carts' => $carts,
            'containers' => $containers,
            'type_content' => $type_content,
            'arr_items' => $arr_items,
            'arr_attachs' => $arr_attachs,
            'arr_people' => $arr_people,
			'arr_range_time' => arrayRangeTime('00:00', '00:00', 30)
        ]);
    }
	
	public function verifyHoursReceivement(Request $request) {

        if (!$request->receivement_date)
            return response()->json(['msg' => 'A data é obrigatório!'], 400);

        $date = str_replace('/', '-', $request->receivement_date);
        $date = date('Y-m-d', strtotime($date));

        return response()->json($this->getFreeHoursToReceivement($date));
    }

    private function getFreeHoursToReceivement($date = null) {

        $date = $date?? date('Y-m-d');
        $hours = ['07:00','08:00','09:00','10:00','11:00','12:00',
            '13:00','14:00','15:00','18:00','19:00','20:00','21:00',
            '22:00','23:00','00:00','01:00',
        ];

        $requests_load = LogisticsEntryExitRequests::whereDate('date_hour', $date)
            ->where('is_liberate', 0)
            ->where('is_denied', 0)
            ->where(function($query) {
                $query->where('has_analyze', 1)->orWhere('is_approv', 1);
            })->get();

        $arr_result = [];
        foreach ($hours as $hour) {
            $arr_result[] = [
                'hour' =>  $hour,
                'quantity' => $requests_load->where('date_hour', $date.' '.$hour.':00')->count(),
            ];
        }

        return $arr_result;
    }

    public function requestCargoTransportEdit_do(Request $request) {

        try {

            if($request->id == 0) {
                $entry_exit = new LogisticsEntryExitRequests;
            } else {
                $entry_exit = LogisticsEntryExitRequests::find($request->id);
                if(!$entry_exit) {
                    return redirect()->back()->with('error', 'Requisição não encontrada!');
                }
            }
            
			$entry_exit->code = $request->id == 0 ? $this->codeGenerator('logistics_entry_exit_requests', 114) : $entry_exit->code;
            $entry_exit->type_reason = $request->type_reason;
            $entry_exit->reason = $request->reason;
            $entry_exit->entry_restriction = $request->entry_restriction;
            $entry_exit->request_r_code = $request->request_r_code;
            $entry_exit->request_sector = $request->request_sector;
            $entry_exit->request_phone = $request->request_phone;
            $entry_exit->request_ramal = $request->request_ramal;
            $entry_exit->request_forwarding = $request->request_forwarding; 
            $entry_exit->create_r_code = $request->session()->get('r_code');
            $entry_exit->transporter_id = $request->transporter_id;
            $entry_exit->transporter_vehicle_id = $request->transporter_vehicle_id;
            $entry_exit->transporter_cart_id = isset($request->transporter_cart_id) ? $request->transporter_cart_id : 0;
            $entry_exit->transporter_container_id = isset($request->transporter_container_id) ? $request->transporter_container_id : 0;
            $entry_exit->transporter_driver_id = $request->transporter_driver_id;
            $entry_exit->is_content = $request->is_content;
            $entry_exit->is_transhipment = $request->is_transhipment;
            $entry_exit->transhipment_container_id = isset($request->transhipment_container_id) ? $request->transhipment_container_id : 0;
            $entry_exit->warehouse_type_content_id = isset($request->warehouse_type_content_id) ? $request->warehouse_type_content_id : 0;
            $entry_exit->is_entry_exit = $request->is_entry_exit;
            $entry_exit->warehouse_id = $request->warehouse_id;

            $entry_exit->supplier_id = $request->supplier_id;

            $entry_exit->entry_exit_gate_id = $request->entry_exit_gate_id;
            $entry_exit->date_hour = implode('-', array_reverse(explode('/', $request->release_date))) .' '.$request->release_hour;
			$entry_exit->date_hour_initial = implode('-', array_reverse(explode('/', $request->release_date))) .' '.$request->release_hour_initial;
            $entry_exit->nfe_number = $request->nfe_number;
            $entry_exit->invoice_number = $request->invoice_number;
            $entry_exit->code_gr = $request->code_gr;
            $entry_exit->nfe_url = $request->hasFile('nfe_file') ? $this->uploadFile($request->nfe_file, $request) : $entry_exit->nfe_url;
            $entry_exit->invoice_url = $request->hasFile('invoice_file') ? $this->uploadFile($request->invoice_file, $request) : $entry_exit->invoice_url;
            $entry_exit->gr_url = $request->hasFile('gr_file') ? $this->uploadFile($request->gr_file, $request) : $entry_exit->gr_url;
            $entry_exit->code_seal = $request->code_seal;
			$entry_exit->code_di = $request->code_di;
            $entry_exit->url_di = $request->hasFile('url_di') ? $this->uploadFile($request->url_di, $request) : $entry_exit->url_di;

            if($entry_exit->save()) {

                $arr_remove_items = json_decode($request->arr_remove_items, true);
                if(count($arr_remove_items) > 0) {
                    LogisticsEntryExitRequestsItems::whereIn('id', $arr_remove_items)->where('entry_exit_requests_id', $entry_exit->id)->delete();
                }
                
                if(isset($request->items_model) && isset($request->items_description) && 
                   isset($request->items_quantity) && isset($request->items_total) && isset($request->items_unit)) {

                    foreach($request->items_id as $index => $id) {

                        if($id == 0) {

                            if($request->items_model[$index] &&
                               $request->items_description[$index] && 
                               $request->items_quantity[$index] &&
                               $request->items_unit[$index]) {

                                $item = new LogisticsEntryExitRequestsItems;
                                $item->entry_exit_requests_id = $entry_exit->id;
                                $item->code_model = $request->items_model[$index];
                                $item->description = $request->items_description[$index];
                                $item->quantity = $request->items_quantity[$index];
                                $item->total = $request->items_total[$index] ? str_replace(',', '.',str_replace('.', '', $request->items_total[$index])) : 0;
                                $item->unit = $request->items_unit[$index];
                                $item->save();
                            }    
                        }
                    }
                }

                $arr_archives = json_decode($request->arr_archives, true);
                if(count($arr_archives) > 0) {
                    foreach($arr_archives as $key) {
                        
                        if(!isset($key['id'])) {
                            $archive = new LogisticsEntryExitRequestsAttachs;
                            $archive->entry_exit_requests_id = $entry_exit->id;
                            $archive->url_attach = $key['url_attach'];
                            $archive->name_attach = $key['name_attach'];
                            $archive->save();
                        }
                    }
                }

                $arr_remove_people = json_decode($request->arr_remove_people, true);
                if(count($arr_remove_people) > 0) {
                    LogisticsEntryExitRequestsPeople::whereIn('id', $arr_remove_people)->where('entry_exit_requests_id', $entry_exit->id)->delete();
                }

                if(isset($request->people_id) && isset($request->people_name) && 
                   isset($request->people_identity) && isset($request->people_reason)) {

                    foreach($request->people_id as $index => $id) {

                        if($id == 0) {

                            if($request->people_name[$index] &&
                               $request->people_identity[$index] && 
                               $request->people_reason[$index]) {

                                $item = new LogisticsEntryExitRequestsPeople;
                                $item->entry_exit_requests_id = $entry_exit->id;
                                $item->name = $request->people_name[$index];
                                $item->identity = $request->people_identity[$index];
                                $item->reason = $request->people_reason[$index];
                                $item->save();
                            }    
                        }
                    }
                }
            }
			
			if($request->send_approv == 1) {
                $this->requestCargoTransportStartAnalyze($request, $request->id);
            }

            return redirect('/logistics/request/cargo/transport/list')->with('success', 'Solicitação salvo com sucesso!');
        } 
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function requestVisitorServiceList(Request $request) {

        $entry_exit = LogisticsEntryExitRequests::with(
            'logistics_entry_exit_requests_items',
            'logistics_entry_exit_gate',
            'logistics_warehouse',
            'logistics_warehouse_type_content',
            'logistics_entry_exit_visit',
            'logistics_entry_exit_requests_schedule.SecurityGuardLiberateDenied',
            'request_user')->whereIn('type_reason', [3, 9, 10])->orderBy('id', 'DESC');

        if(!$request->code && $entry_exit->count() > 0) {
            if (!hasPermManager(26)) {
                $entry_exit->where('request_r_code', $request->session()->get('r_code'))
                            ->orWhere('create_r_code', $request->session()->get('r_code'));
            }    
        }    

        $array_input = collect([
            'code',
			'gate',
            'type_reason',
            'is_entry_exit',
            'request_r_code',
            'status', 
            'start_date',
            'end_date'
        ]);

        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code") {
                    $entry_exit->where('code', 'like', '%'.$value_filter.'%');
                }
				if($name_filter == $filter_session[1]."gate") {
                    $entry_exit->where('entry_exit_gate_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."type_reason") {
                    $entry_exit->where('type_reason', $value_filter);
                }
                if($name_filter == $filter_session[1]."is_entry_exit") {
                    $entry_exit->whereHas('logistics_entry_exit_requests_schedule', function($q) use ($value_filter){
                        $q->where('is_entry_exit', $value_filter);
                    });
                }
                if($name_filter == $filter_session[1]."request_r_code") {
                    $entry_exit->where('request_r_code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status") {

                    if($value_filter == 1) {
                        $entry_exit->where('is_reprov', 1);
                    } elseif ($value_filter == 2) {
                        $entry_exit->where('is_cancelled', 1);
                    } elseif ($value_filter == 3) {
                        $entry_exit->where('is_denied', 1);
                    } elseif ($value_filter == 4) {
                        $entry_exit->where('is_liberate', 1);
                    } elseif ($value_filter == 5) {
                        $entry_exit->where('has_analyze', 1);
                    } elseif ($value_filter == 6) {
                        $entry_exit->where('is_approv', 1);
                    } else {
                        $entry_exit->where('is_reprov', 0)
                                ->where('is_cancelled', 0)
                                ->where('is_denied', 0)
                                ->where('is_liberate', 0)
                                ->where('has_analyze', 0)
                                ->where('is_approv', 0);
                    }
                }
                if($name_filter == $filter_session[1]."start_date") {

                    $entry_exit->whereHas('logistics_entry_exit_requests_schedule', function($q) use ($value_filter){
                        $q->whereDate('date_hour', '>=', $value_filter);
                    });
                }
                if($name_filter == $filter_session[1]."end_date") {
                    $entry_exit->whereHas('logistics_entry_exit_requests_schedule', function($q) use ($value_filter){
                        $q->whereDate('date_hour', '<=', $value_filter);
                    });
                }
            }
        }

        if($request->export == 1) {

            $pattern = [
                'view' => 'gree_i.logistics.reports.report_request_visitor_service',
                'entry_exit' => $entry_exit->get()
            ];
            return Excel::download(new DefaultHtmlExport($pattern), 'VisitorServiceExport-'. date('Y-m-d') .'.xlsx');
        }
		
		$gates = App\Model\LogisticsEntryExitGate::all();

        return view('gree_i.logistics.request_visitor_service_list', [
            'entry_exit' => $entry_exit->paginate(10),
			'gates' => $gates,
        ]);
    }

    public function exportTeste() {

        $entry_exit = LogisticsEntryExitRequests::with(
            'logistics_entry_exit_requests_items',
            'logistics_entry_exit_gate',
            'logistics_warehouse',
            'logistics_warehouse_type_content',
            'logistics_entry_exit_visit',
            'logistics_entry_exit_requests_schedule.SecurityGuardLiberateDenied',
            'request_user')->whereIn('type_reason', [3, 9, 10])->orderBy('id', 'DESC');

        return view('gree_i.logistics.reports.report_request_visitor_service', [
            'entry_exit' => $entry_exit->get()
        ]);
    }

    public function requestVisitorServiceEdit(request $request, $id) {        

        if($id != 0) {
            $entry_exit = LogisticsEntryExitRequests::with('logistics_entry_exit_requests_items', 
                                                           'logistics_entry_exit_visit',
                                                           'logistics_entry_exit_requests_schedule')->find($id);
            if(!$entry_exit) {
                return redirect()->back()->with('error', 'Solicitação não encontrada');
            }
        }
        
        $visitor_id = $id ? $entry_exit->entry_exit_visitant_id : 0;
        $type_reason = $id ? $entry_exit->type_reason : '';
        $entry_exit_gate_id = $id ? $entry_exit->entry_exit_gate_id : 0;
        $warehouse_id = $id ? $entry_exit->warehouse_id :0; 
        $request_r_code = $id ? $entry_exit->request_r_code : '';
        $request_sector = $id ? $entry_exit->request_sector : '';
        $request_phone = $id ? $entry_exit->request_phone : '';
        $request_ramal = $id ? $entry_exit->request_ramal : '';
        $reason = $id ? $entry_exit->reason : '';
		if($id) {
			$visitor_name = $entry_exit->logistics_entry_exit_visit ? $entry_exit->logistics_entry_exit_visit->name : '';	
		} else {
			$visitor_name = '';	
		}
        
        $visitor_identity = $id ? $entry_exit->logistics_entry_exit_visit->identity ?? '' : '';
        $visitor_phone = $id ? $entry_exit->logistics_entry_exit_visit->phone ?? '' : '';
        $visitor_gender = $id ? $entry_exit->logistics_entry_exit_visit->gender ?? '' : '';
        $visitor_company_name = $id ? $entry_exit->logistics_entry_exit_visit->company_name ?? '' : '';
        $visitor_company_phone = $id ? $entry_exit->logistics_entry_exit_visit->company_phone ?? '' : '';
        $visitor_company_identity = $id ? $entry_exit->logistics_entry_exit_visit->company_identity ?? '' : '';
        $visitor_car_plate = $id ? $entry_exit->logistics_entry_exit_visit->car_plate ?? '' : '';
        $visitor_car_model = $id ? $entry_exit->logistics_entry_exit_visit->car_model ?? '' : '';
        $is_approv = $id ? $entry_exit->is_approv : 0;
        $is_reprov = $id ? $entry_exit->is_reprov : 0;
        $has_analyze = $id ? $entry_exit->has_analyze : 0;
        $is_cancelled = $id ? $entry_exit->is_cancelled : 0;        
        $users_request = Users::all();
        $entry_exit_gate = LogisticsEntryExitGate::all();
        $warehouse = LogisticsWarehouse::all();

        $arr_items = $id ? $entry_exit->logistics_entry_exit_requests_items : []; 

        $arr_schedule = [];
        if($id != 0) {
            foreach($entry_exit->logistics_entry_exit_requests_schedule as $key) {

                $new_arr = [];
                $new_arr['id'] = $key->id;
                $new_arr['type'] = $key->is_entry_exit;
                $new_arr['date'] = date('d/m/Y', strtotime($key->date_hour));
                $new_arr['hour'] = date('H:i', strtotime($key->date_hour));
                $new_arr['restriction'] = $key->entry_restriction;
                $new_arr['forwarding'] = $key->request_forwarding;
                array_push($arr_schedule, $new_arr);
            }
        }

        return view('gree_i.logistics.request_visitor_service', [
            'id' => $id,
            'visitor_id' => $visitor_id,
            'type_reason' => $type_reason,
            'entry_exit_gate_id' => $entry_exit_gate_id,
            'warehouse_id' => $warehouse_id,
            'request_r_code' => $request_r_code,
            'request_sector' => $request_sector,
            'request_phone' => $request_phone,
            'request_ramal' => $request_ramal,
            'reason' => $reason,
            'visitor_name' => $visitor_name,
            'visitor_identity' => $visitor_identity,
            'visitor_phone' => $visitor_phone,
            'visitor_gender' => $visitor_gender,
            'visitor_company_name' => $visitor_company_name,
            'visitor_company_phone' => $visitor_company_phone,
            'visitor_company_identity' => $visitor_company_identity,
            'visitor_car_plate' => $visitor_car_plate,
            'visitor_car_model' => $visitor_car_model,
            'users_request' => $users_request,
            'entry_exit_gate' => $entry_exit_gate,
            'warehouse' => $warehouse,
            'arr_items' => $arr_items,
            'is_approv' => $is_approv,
            'is_reprov' => $is_reprov,
            'has_analyze' => $has_analyze,
            'is_cancelled' => $is_cancelled,
            'arr_schedule' => $arr_schedule
        ]);
    }

    public function requestVisitorServiceEdit_do(Request $request) {

        DB::beginTransaction();

        try {

            if($request->id == 0) {
                $entry_exit = new LogisticsEntryExitRequests;
                $visitor = new LogisticsEntryExitVisitant;
            } else {

                $entry_exit = LogisticsEntryExitRequests::find($request->id);
                $visitor = LogisticsEntryExitVisitant::find($request->visitor_id);

                if(!$entry_exit)
                    return redirect()->back()->with('error', 'Solicitação não encontrada!');

                if(!$visitor)
                    return redirect()->back()->with('error', 'Visitante não encontrado!');    
            }

            $visitor->name = $request->visitor_name;
            $visitor->identity = $request->visitor_identity;
            $visitor->phone = $request->visitor_phone;
            $visitor->gender = $request->visitor_gender;
            $visitor->company_name = $request->visitor_company_name;
            $visitor->company_identity = $request->visitor_company_identity;
            $visitor->company_phone = $request->visitor_company_phone;
            $visitor->car_model = $request->visitor_car_model;
            $visitor->car_plate = $request->visitor_car_plate;

            if($visitor->save()) {

                $entry_exit->code = $this->codeGenerator('logistics_entry_exit_requests', 114);
                $entry_exit->type_reason = $request->type_reason;
                $entry_exit->reason = $request->reason;
                $entry_exit->request_r_code = $request->request_r_code;
                $entry_exit->request_sector = $request->request_sector;
                $entry_exit->request_phone = $request->request_phone;
                $entry_exit->request_ramal = $request->request_ramal;
                $entry_exit->create_r_code = $request->session()->get('r_code');
                $entry_exit->entry_exit_gate_id = $request->entry_exit_gate_id;
                $entry_exit->warehouse_id = $request->warehouse_id;
                $entry_exit->entry_exit_visitant_id = $visitor->id;

                if($entry_exit->save()) {

                    $arr_remove_items = json_decode($request->arr_remove_items, true);
                    if(count($arr_remove_items) > 0) {
                        LogisticsEntryExitRequestsItems::whereIn('id', $arr_remove_items)->where('entry_exit_requests_id', $entry_exit->id)->delete();
                    }
                    
                    if(isset($request->items_description) && isset($request->items_quantity)) {

                        foreach($request->items_id as $index => $id) {
                            
                            if($id == 0) {

                                if($request->items_description[$index] && 
                                   $request->items_quantity[$index]) {

                                    $item = new LogisticsEntryExitRequestsItems;
                                    $item->entry_exit_requests_id = $entry_exit->id;
                                    $item->description = $request->items_description[$index];
                                    $item->quantity = $request->items_quantity[$index];
                                    $item->save();
                                }
                            }
                        }
                    }

                    $arr_schedule_remove = json_decode($request->arr_schedule_remove, true);
                    if(count($arr_schedule_remove) > 0) {
                        LogisticsEntryExitRequestsSchedule::whereIn('id', $arr_schedule_remove)->where('entry_exit_requests_id', $entry_exit->id)->delete();
                    }

                    $arr_schedule = json_decode($request->arr_schedule, true);
                    foreach($arr_schedule as $index => $key) {
                        if($key['id'] == 0) {
                            $schedule = new LogisticsEntryExitRequestsSchedule;
                            $schedule->entry_exit_requests_id = $entry_exit->id;
                            $schedule->entry_restriction = $key['restriction'];
                            $schedule->request_forwarding = $key['forwarding'];
                            $schedule->is_entry_exit = $key['type'];
                            $schedule->date_hour = implode('-', array_reverse(explode('/', $key['date']))) .' '.$key['hour'];
                            $schedule->save();
                        }
                    }
                }   
            }

            DB::commit();
            return redirect('/logistics/request/visitor/service/list')->with('success', 'Solicitação cadastrada com sucesso');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function requestVisitorServiceApprovers(Request $request) {

        if (!hasPermManager(27)) {
            return redirect()->back()->with('error', 'Você não tem permissão para acessar está página');
        }
 
        $entry_exit = new LogisticsEntryExitRequests;    
        $arr_approvers = $entry_exit->rtd_approvers()->sortBy('position')->groupBy('position');
        $arr_observers_old = $entry_exit->rtd_observers();

        $arr_approv_verify = [];
        $arr_approv = [];

        foreach($arr_approvers as $approv) {

            if($approv->count() > 1) {
                $new_arr = [];
                $new_arr_sub = [];
                $sub_arr = [];
                
                foreach($approv->toArray() as $i => $key) {

                    if($i == 0) {
                        $new_arr['arr_approvers'] = null;
                        $new_arr['name'] = $key['users']['full_name'];
                        $new_arr['picture'] = $key['users']['picture'];
                        $new_arr['r_code'] = $key['r_code'];
                        $new_arr['id'] = $key['id'];
                    } else {
                        
                        $sub_arr['name'] = $key['users']['full_name'];
                        $sub_arr['picture'] = $key['users']['picture'];
                        $sub_arr['r_code'] = $key['r_code'];
                        $sub_arr['id'] = $key['id'];

                        array_push($new_arr_sub, $sub_arr);
                        $new_arr['arr_approvers'] = $new_arr_sub;
                    }

                    array_push($arr_approv_verify, $key['r_code']);
                }
                array_push($arr_approv, $new_arr);
            }
            if($approv->count() == 1) {

                $new_arr = [];
                $new_arr['arr_approvers'] = null;
                $new_arr['name'] = $approv->first()->users->full_name;
                $new_arr['picture'] = $approv->first()->users->picture;
                $new_arr['r_code'] = $approv->first()->r_code;
                $new_arr['id'] = $approv->first()->id;

                array_push($arr_approv, $new_arr);
                array_push($arr_approv_verify, $approv->first()->r_code);
            }
        }

        $arr_observers = [];
        foreach ($arr_observers_old as $observer) {
            $arr_observers_new = [];
            $arr_observers_new['id'] = $observer->id;
            $arr_observers_new['name'] = $observer->users->full_name;
            $arr_observers_new['picture'] = $observer->users->picture;
            $arr_observers_new['r_code'] = $observer->users->r_code;
            array_push($arr_observers, $arr_observers_new);
            array_push($arr_approv_verify, $observer->users->r_code);
        }

        
        return view('gree_i.logistics.request_visitor_service_approvers', [
            'arr_approv' => $arr_approv,
            'arr_observers' => $arr_observers,
            'arr_approv_verify' => $arr_approv_verify
        ]);
    }

    public function requestVisitorServiceApprovEdit_do(Request $request) {


        $arr_approv = json_decode($request->arr_approv, true);
        $arr_observers = json_decode($request->arr_observers, true);

        if(count($arr_approv) > 0) {

            foreach($arr_approv as $index => $approv) {
                $arr_approv[$index]['position'] = $index + 1;

                if($approv['arr_approvers'] != null) {

                    foreach($approv['arr_approvers'] as $sub_index => $sub_approv) {
                        $arr_approv[$index]['arr_approvers'][$sub_index]['position'] = $index + 1;
                        array_push($arr_approv, $arr_approv[$index]['arr_approvers'][$sub_index]);
                    }
                }
            }   

            $request_analyze = new LogisticsEntryExitRequests;

            $arr_new = [];
            $arr_update = [];
            foreach($arr_approv as $key) {
                if($key['id'] == 0) {
                    $arr = array_diff_key($key, ["arr_approvers" => 1, "name" => 1, "picture" => 1, "id" => 1]);
                    array_push($arr_new, $arr);
                } else {
                    array_push($arr_update, $key);
                }
            }
            
            $this->saveMorphApprovers($request_analyze, $arr_new, $arr_update, $request->arr_approv_delete, true);
        }

        $observers_new = [];
        foreach($arr_observers as $key) {   
            if($key['id'] == 0) {
                $arr = array_diff_key($key, ["name" => 1, "picture" => 1]);
                array_push($observers_new, $arr);
            }    
        }
        $this->saveMorphObservers($request_analyze, $observers_new, $request->arr_observers_delete, true);

        return redirect()->back()->with('success', 'Aprovadores cadastrados com sucesso');
    }
    
    public function requestVisitorServiceListApprov(Request $request) {

        $entry_exit = LogisticsEntryExitRequests::with(
            'logistics_entry_exit_gate',
            'logistics_warehouse',
            'logistics_entry_exit_requests_items',
            'logistics_warehouse_type_content',
            'logistics_entry_exit_visit',
            'logistics_entry_exit_requests_schedule.SecurityGuardLiberateDenied',
            'request_user')->whereIn('type_reason', [3, 9, 10])->where('has_analyze', 1)->ValidAnalyzeProccess($request->session()->get('r_code'))->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'is_entry_exit',
            'request_r_code'
        ]);
    
        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code") {
                    $entry_exit->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."is_entry_exit") {
                    $entry_exit->where('is_entry_exit', $value_filter);
                }
                if($name_filter == $filter_session[1]."request_r_code") {
                    $entry_exit->where('request_r_code', 'like', '%'.$value_filter.'%');
                }
            }
        }

        return view('gree_i.logistics.request_visitor_service_list_approv', [
            'entry_exit' => $entry_exit->paginate(10)
        ]);
    }

    public function requestVisitorServiceStartAnalyze(Request $request, $id) {

        try {

            $entry_exit = LogisticsEntryExitRequests::with('request_user.immediates', 
                                                           'logistics_entry_exit_requests_schedule')->find($id);

            if(!$entry_exit)
				return redirect()->back()->with('error', 'Não foi possível encontrar a solicitação enviada para aprovação.'); 

                $solicitation = new VisitantService($entry_exit, $request);
                $do_analyze = new ProcessAnalyze($solicitation);
                $do_analyze->eventStart(['rulesVerifyHasImmediate']);
                return redirect()->back()->with('success', 'Solicitação enviada para aprovação');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function requestVisitorServiceAnalyze_do(Request $request) {

        try {

            $entry_exit = LogisticsEntryExitRequests::with('rtd_analyze.users', 
                                                           'logistics_entry_exit_requests_schedule')->find($request->id);

            if(!$entry_exit)
                return redirect()->back()->with('error', 'Solicitação não encontrada!');

            $solicitation = new VisitantService($entry_exit, $request);
            $do_analyze = new ProcessAnalyze($solicitation);

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

    public function requestCargoTransportStartAnalyze(Request $request, $id) {

        try {

            $entry_exit = LogisticsEntryExitRequests::with('logistics_warehouse.analyze_approv', 'logistics_warehouse.analyze_observ.users','request_user.immediates')->find($id);

            if($entry_exit) {
                $solicitation = new CargoTransport($entry_exit, $request);
                $do_analyze = new ProcessAnalyze($solicitation);
                $do_analyze->eventStart(['rulesStatusStartAnalyze', 'rulesVerifyHasImmediate']);
                return redirect()->back()->with('success', 'Solicitação enviada para aprovação');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function requestCargoTransportApprovList(Request $request) {

        $approv_list = LogisticsEntryExitRequests::with(
                            'logistics_entry_exit_gate',
                            'logistics_entry_exit_requests_items',
							'logistics_entry_exit_requests_attachs',
                            'logistics_transporter',
                            'logistics_transporter_driver',
                            'logistics_transporter_vehicle',
                            'logistics_transporter_cart',
                            'logistics_container',
                            'logistics_warehouse',
                            'logistics_warehouse_type_content',
                            'request_user')->whereNotIn('type_reason', [3, 9, 10])->where('has_analyze', 1)->ValidAnalyzeProccess($request->session()->get('r_code'))->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'is_entry_exit',
            'request_r_code'
        ]);
                    
        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code") {
                    $approv_list->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."is_entry_exit") {
                    $approv_list->where('is_entry_exit', $value_filter);
                }
                if($name_filter == $filter_session[1]."request_r_code") {
                    $approv_list->where('request_r_code', 'like', '%'.$value_filter.'%');
                }
            }
        }
        
        return view('gree_i.logistics.request_cargo_transport_list_approv', [
            'approv_list' => $approv_list->paginate(10)
        ]);
    }

    public function requestCargoTransportAnalyze_do(Request $request) {

        try {

            $entry_exit = LogisticsEntryExitRequests::with('rtd_analyze.users')->find($request->id);
            if(!$entry_exit)
                return redirect()->back()->with('error', 'Solicitação não encontrada!');

            $solicitation = new CargoTransport($entry_exit, $request);
            $do_analyze = new ProcessAnalyze($solicitation);

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

    public function requestCargoTransportApprovNow(Request $request) {
        
        try {

            $entry_exit = LogisticsEntryExitRequests::with('rtd_analyze.users')->find($request->id);
            if(!$entry_exit)
                return redirect()->back()->with('error', 'Solicitação não encontrada!');
            
            $solicitation = new CargoTransport($entry_exit, $request);
            $do_analyze = new ProcessAnalyze($solicitation);
            $result = $do_analyze->eventApprovNow();
            return redirect()->back()->with('success', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }    

    public function requestVisitantServiceApprovNow(Request $request) {
        
        try {

            $entry_exit = LogisticsEntryExitRequests::with('rtd_analyze.users')->find($request->id);
            if(!$entry_exit)
                return redirect()->back()->with('error', 'Solicitação não encontrada!');
            
            $solicitation = new VisitantService($entry_exit, $request);
            $do_analyze = new ProcessAnalyze($solicitation);
            $result = $do_analyze->eventApprovNow();
            return redirect()->back()->with('success', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }    

    public function requestCargoVisitantCancel(Request $request) {

        try {

            $entry_exit = LogisticsEntryExitRequests::find($request->id);
            if(!$entry_exit)
                return redirect()->back()->with('error', 'Solicitação não encontrada!');

            $validation = $entry_exit->rtd_status['status']['validation']->first();
            if($validation) {
                if($validation->position  > 1)
                    return redirect()->back()->with('error', 'Está solicitação não pode ser cancelada, já se encontra em processo de aprovação');
            }
			    
            $entry_exit->is_cancelled = 1;
            $entry_exit->has_analyze = 0;
            $entry_exit->cancelled_r_code = $request->session()->get('r_code');
            $entry_exit->cancelled_reason = $request->cancel_reason;
            $entry_exit->save();

            return redirect()->back()->with('success', 'Solicitação foi cancelada com sucesso!');
        
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function requestCargoTransportImportItems(Request $request) {

        set_time_limit(360);
        if ($request->hasFile('file_items')) {

            $extension = $request->file_items->extension();
        
            $validator = Validator::make(
                ['file' => $request->file_items, 'extension' => strtolower($request->file_items->getClientOriginalExtension())],
                ['file' => 'required|max:20480', 'extension' => 'required|in:csv,xlsx,xls']
            );
            
            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => "Tamanho do arquivo não pode exceder 20MB!"
                ], 400);

            } else {
        
                try {

                    $rows = Excel::toArray([], $request->file('file_items'));
                    
                    $arr_items = [];
                    foreach ($rows[0] as $index => $row) {
                        if($index != 0) {
                            
                            $arr = [
                                $row[0],
                                $row[1],
                                $row[2],
                                number_format($row[3], 4,",","."),
                                $row[4]
                            ];
                            array_push($arr_items, $arr);
                        }    
                    }

                    return response()->json([
                        'success' => true,
                        'items' => $arr_items
                    ], 200);
                    
                } catch (\Exception $e) {

                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ], 400);
                }
            } 
        }
    }

    public function requestVisitorServiceImportItems(Request $request) {

        set_time_limit(360);
        if ($request->hasFile('file_items')) {

            $extension = $request->file_items->extension();
        
            $validator = Validator::make(
                ['file' => $request->file_items, 'extension' => strtolower($request->file_items->getClientOriginalExtension())],
                ['file' => 'required|max:20480', 'extension' => 'required|in:csv,xlsx,xls']
            );
            
            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => "Tamanho do arquivo não pode exceder 20MB!"
                ], 400);

            } else {
        
                try {

                    $rows = Excel::toArray([], $request->file('file_items'));
                    
                    $arr_items = [];
                    foreach ($rows[0] as $index => $row) {
                        if($index != 0) {
                            
                            $arr = [
                                $row[0],
                                $row[1]
                            ];
                            array_push($arr_items, $arr);
                        }    
                    }

                    return response()->json([
                        'success' => true,
                        'items' => $arr_items
                    ], 200);
                    
                } catch (\Exception $e) {

                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ], 400);
                }
            } 
        }
    }

    public function requestCargoTransportUploadArchive(Request $request) {

        try {

            if ($request->hasFile('file_archive')) {

                return response()->json([
                    'success' => true,
                    'url' => $this->uploadFile($request->file_archive, $request)
                ], 200);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Não foi possível fazer upload do arquivo!'
                ], 400);
            }

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }  
    }

    public function requestCargoTransportDeleteArchive(Request $request) {

        try {

            if($request->url != '') {

                if($request->id != 0 && $request->request_id != 0) {
                    LogisticsEntryExitRequestsAttachs::where('id', $request->id)->where('entry_exit_requests_id', $request->request_id)->delete();
                } 

                removeS3($request->url);

                return response()->json([
                    'success' => true,
                    'message' => 'Arquivo removido!',
                ], 200);
            } else {
                
                return response()->json([
                    'success' => false,
                    'message' => 'URL vazia, não é possível remover!'
                ], 400);
            }

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
	
	public function requestCargoTransportDeleteFixArchive(Request $request) {

        try {

            if ($request->url != '' && $request->id != 0) {

                $remove = LogisticsEntryExitRequests::find($request->id);

                if ($request->type == 1) {
                    $remove->nfe_url = null;
                    $remove->nfe_number = null;
                }
                elseif ($request->type == 2) {
                    $remove->invoice_url = null;
                    $remove->invoice_number = null;
                }
                elseif ($request->type == 3) {
                    $remove->gr_url = null;
                    $remove->code_gr = null;
                }
				elseif ($request->type == 4) {
                    $remove->url_di = null;
                    $remove->code_di = null;
                }

                if($remove->save()) {
                    removeS3($request->url);
                }
                return redirect()->back()->with('success', 'Arquivo removido com sucesso!');

            } else {
                return redirect()->back()->with('error', 'URL vazia, não é possível remover!');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function requestCargoTransportDuplicate(Request $request, $id) {

        $request = LogisticsEntryExitRequests::with('logistics_entry_exit_requests_items')->find($id);
        if(!$request)
            return redirect()->back()->with('error', 'Não foi possível encontrar a solicitação para duplicação');

        $new_request = $request->replicate();
        $new_request->code = $this->codeGenerator('logistics_entry_exit_requests', 114);
        $new_request->is_approv = 0;
        $new_request->is_reprov = 0;
        $new_request->is_liberate = 0;
        $new_request->is_denied = 0;
        $new_request->is_cancelled = 0;
        $new_request->has_analyze = 0;
        $new_request->cancelled_r_code = '';
        $new_request->cancelled_reason = '';
        $new_request->denied_reason = '';
        $new_request->request_action_time = '0000-00-00 00:00:00';
        $new_request->version = 0;
		$new_request->nfe_url = null;
        $new_request->nfe_number = null;
        $new_request->invoice_url = null;
        $new_request->invoice_number = null;
        $new_request->gr_url = null;
        $new_request->code_gr = null;
        $new_request->save();

        foreach ($request->logistics_entry_exit_requests_items as $key) {
            $new_item = $key->replicate();
            $new_item->entry_exit_requests_id = $new_request->id;
            $new_item->save();
        }    

        return redirect()->back()->with('success', 'Solicitação foi duplicada com sucesso!');
    }

    public function requestVisitantServiceDuplicate(Request $request, $id) {

        $request = LogisticsEntryExitRequests::with('logistics_entry_exit_visit', 
                                                    'logistics_entry_exit_requests_schedule',
                                                    'logistics_entry_exit_requests_items')->find($id);

        if(!$request)
            return redirect()->back()->with('error', 'Não foi possível encontrar a solicitação para duplicação');

        $new_request = $request->replicate();
        $new_request->code = $this->codeGenerator('logistics_entry_exit_requests', 114);
        $new_request->is_approv = 0;
        $new_request->is_reprov = 0;
        $new_request->is_liberate = 0;
        $new_request->is_denied = 0;
        $new_request->is_cancelled = 0;
        $new_request->has_analyze = 0;
        $new_request->cancelled_r_code = '';
        $new_request->cancelled_reason = '';
        $new_request->denied_reason = '';
        $new_request->request_action_time = '0000-00-00 00:00:00';
        $new_request->version = 0;

        if($new_request->save()) {

            $visitant = LogisticsEntryExitVisitant::find($new_request->entry_exit_visitant_id);
            $new_visitant = $visitant->replicate();
            if($new_visitant->save()) {
                $new_request->entry_exit_visitant_id = $new_visitant->id;
                $new_request->save();
            }
        }

        foreach ($request->logistics_entry_exit_requests_schedule as $key) {
            $schedule = $key->replicate();
            $schedule->entry_exit_requests_id = $new_request->id;
            $schedule->save();
        }

        foreach ($request->logistics_entry_exit_requests_items as $key) {
            $new_item = $key->replicate();
            $new_item->entry_exit_requests_id = $new_request->id;
            $new_item->save();
        }

        return redirect()->back()->with('success', 'Solicitação foi duplicada com sucesso!');
    }    

    public function supplierList(Request $request) {

        $supplier = LogisticsSupplier::orderBy('id', 'DESC');

        $array_input = collect([
            'name',
            'identity',
            'status',
        ]);

        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."name"){
                    $supplier->where('name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."identity"){
                    $supplier->where('identity', $value_filter);
                }
                if($name_filter == $filter_session[1]."status"){
                    $value = $value_filter == 'not' ? 0 : 1;
                    $supplier->where('is_active', $value_filter);
                }
            }
        }

        return view('gree_i.logistics.supplier_list', [
            'suppliers' => $supplier->paginate(10)
        ]);
    }

    public function supplierEdit_do(Request $request) {

        try {

            if($request->id == 0) {

                $verify = LogisticsSupplier::where('identity', $request->identity)->first();
                if($verify)
                    return redirect()->back()->with('error', 'Fornecedor já cadastrado');

                $supplier = new LogisticsSupplier;
            } else {
                $supplier = LogisticsSupplier::find($request->id);
                if(!$supplier) {
                    return redirect()->back()->with('error', 'Fornecedor não encontrado!');
                }
            }

            $supplier->name = $request->name;
            $supplier->identity = $request->identity;
            $supplier->address = $request->address;
            $supplier->city = $request->city;
            $supplier->state = $request->state;
            $supplier->phone = $request->phone;
            $supplier->email = $request->email;
            $supplier->is_active = 1;
            $supplier->save();

            return redirect()->back()->with('success', 'Fornecedor cadastrado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }    
    }

    public function transportDriverImport(Request $request) {

        if ($request->hasFile('file_driver')) {

			try {
				Excel::import(
					new DefaultImport(
						new LogisticsTransporterDriver,
                        ['name', 'identity', 'phone'],
                        function($column_excel, $column_db, $row_excel, $add) {

                            if($column_excel == '')
                                return ['result' => false];

                            if($column_db == 'identity') {

                                $verify = LogisticsTransporterDriver::where('identity', $column_excel)->first();
                                if($verify) {
                                    return ['result' => false];
                                } else {   
                                    $add->$column_db = $column_excel;
                                }    
                            } else {
                                $add->$column_db = $column_excel;
                            }

                            return [
                                'collect' => $add, 
                                'result' => true
                            ];
                        }    
                    ),
					$request->file('file_driver')
				);
				return redirect()->back()->with('success', "Motoristas importados com sucesso!");
			}
			catch (\Exception $e) {
				return redirect()->back()->with('error', $e->getMessage());
			}
		}
    }

    public function transportVehicleImport(Request $request) {

        if ($request->hasFile('file_vehicle')) {

			try {
				Excel::import(
					new DefaultImport(
						new LogisticsTransporterVehicle,
                        ['type_vehicle', 'registration_plate'],

                        function($column_excel, $column_db, $row_excel, $add) {
                            if($column_excel == '')
                                return ['result' => false];

                            if($column_db == 'registration_plate') {

                                $verify = LogisticsTransporterVehicle::where('registration_plate', $column_excel)->first();
                                if($verify) {
                                    return ['result' => false];
                                } else {   
                                    $add->$column_db = $column_excel;
                                }    
                            } else {
                                $add->$column_db = $column_excel;
                            }    

                            if($column_db == 'type_vehicle') {

                                $vehicle = array_search($column_excel, config('gree.type_vehicle'));
                                if ($vehicle !== false) {
                                    $val_vehicle = $vehicle;
                                } else {
                                    $val_vehicle = 0;
                                }
                                $add->$column_db = $val_vehicle;
                            } else {
                                $add->$column_db = $column_excel;
                            }

                            return [
                                'collect' => $add, 
                                'result' => true
                            ];
                        }
                    ),
					$request->file('file_vehicle')
				);
				return redirect()->back()->with('success', "Motoristas importados com sucesso!");
			}
			catch (\Exception $e) {
				return redirect()->back()->with('error', $e->getMessage());
			}
		}
    }


    public function supplierImport(Request $request) {

        if ($request->hasFile('file_supplier')) {

			try {
				Excel::import(
					new DefaultImport(
						new LogisticsSupplier,
                        ['name', 'identity', 'address', 'city', 'state', 'phone', 'email']
                    ),
					$request->file('file_supplier')
				);
				return redirect()->back()->with('success', "Fornecedores importados com sucesso!");
			}
			catch (\Exception $e) {
				return redirect()->back()->with('error', $e->getMessage());
			}
		}
    }
	
	public function warehouseEntryExitItemsList(Request $request) {

        $itens = WarehouseEntryExitItems::with(
            'logistics_entry_exit_requests.logistics_entry_exit_gate',
            'logistics_entry_exit_requests.logistics_entry_exit_requests_items', 
            'logistics_entry_exit_requests.logistics_transporter', 
            'logistics_entry_exit_requests.logistics_transporter_driver', 
            'logistics_entry_exit_requests.logistics_transporter_vehicle', 
            'logistics_entry_exit_requests.logistics_transporter_cart', 
            'logistics_entry_exit_requests.logistics_container', 
            'logistics_entry_exit_requests.logistics_warehouse',
            'logistics_entry_exit_requests.logistics_supplier',
            'logistics_entry_exit_requests.logistics_warehouse_type_content',
            'logistics_entry_exit_requests.logistics_entry_exit_requests_attachs',
            'logistics_entry_exit_requests.logistics_entry_exit_requests_people',
            'logistics_entry_exit_requests.request_user',
            'logistics_warehouse'
        )->orderBy('id', 'DESC');

        $array_input = collect([
            'is_entry_exit',
            'code_request',
            'type_request',
            'warehouse_id',
            'code_item',
            'description',
            'start_date',
            'end_date'
        ]);

        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."is_entry_exit"){
                    $itens->where('is_entry_exit', $value_filter);
                }
                if($name_filter == $filter_session[1]."code_request"){
                    $itens->whereHas('logistics_entry_exit_requests', function($q) use ($value_filter){
                        $q->where('code', $value_filter);
                    });
                }
                if($name_filter == $filter_session[1]."type_request"){
                    $itens->where('type_request', $value_filter);
                }
                if($name_filter == $filter_session[1]."warehouse_id"){
                    $itens->whereHas('logistics_warehouse', function($q) use ($value_filter){
                        $q->where('id', $value_filter);
                    });
                }
                if($name_filter == $filter_session[1]."code_item"){
                    $itens->where('code', $value_filter);
                }
                if($name_filter == $filter_session[1]."description"){
                    $itens->where('description', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."start_date") {
                    $itens->whereDate('created_at', '>=', $value_filter);
                }
                if($name_filter == $filter_session[1]."end_date") {
                    $itens->whereDate('created_at', '<=', $value_filter);
                }
            }
        }

        if($request->export == 1) {

            $heading = array('Solicitação', 'Tipo', 'Galpão', 'Código item', 'Descrição item', 'Quantidade', 'Data', 'Horário');
            $rows = array();
            
            foreach ($itens->get() as $key) {
                $line = array();

                $line[0] = $key->logistics_entry_exit_requests->code ?? '';
                $line[1] = $key->is_entry_exit == 1 ? 'Entrada' : 'Saída';
                $line[2] = $key->logistics_warehouse->name;
                $line[3] = $key->code != '' ? $key->code : '-';
                $line[4] = $key->description;
                $line[5] = $key->quantity;
                $line[6] = date('d/m/Y', strtotime($key->created_at));
                $line[7] = date('H:i', strtotime($key->created_at));

                array_push($rows, $line);
            }
            return Excel::download(new DefaultExport($heading, $rows), 'EntryExitItemsExport-'. date('Y-m-d') .'.xlsx');
        }

        return view('gree_i.logistics.warehouse_entry_exit_items_list', [
            'itens' => $itens->paginate(10)
        ]);
    }

    public function usersRcodeList(Request $request) {
        $name = $request->search;

        $data = Users::where('first_name', 'like', '%'. $name .'%')
            ->where('is_active', 1)
            ->orWhere(function ($query) use ($name) {
                $query->where('r_code', 'like', '%'. $name .'%')
                    ->where('is_active', 1);
            })
            ->orWhere(function ($query) use ($name) {
                $query->where('last_name', 'like', '%'. $name .'%')
                    ->where('is_active', 1);
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->r_code;
                $row['text'] = $key->first_name .' '. $key->last_name;
                $row['sector'] = $key->sector_id;
                $row['phone'] = $key->phone;
                
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

    public function warehouseListDropdown(Request $request) {

        $name = $request->search;

        $data = LogisticsWarehouse::where('name', 'like', '%'. $name .'%')->orderBy('id', 'DESC')->paginate(10);
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

    public function supplierListDropdown(Request $request) {

        $name = $request->search;

        $data = LogisticsSupplier::where('name', 'like', '%'. $name .'%')->orderBy('id', 'DESC')->paginate(10);
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

    public function transporterDriverListDropdown(Request $request) {

        $name = $request->search;

        $data = LogisticsTransporterDriver::with('logistics_transporter')->where('name', 'like', '%'. $name .'%')
                ->orWhere(function ($query) use ($name) {
                    $query->where('identity', 'like', '%'. $name .'%');
                })
                ->orderBy('id', 'DESC')
                ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;

                if($key->transporter_id != 0 || $key->supplier_id != 0) {
                    $name_comp = $key->transporter_id ? $key->logistics_transporter->name : $key->logistics_supplier->name;
                    $row['text'] = $key->name.' - '. $key->identity .' ('.$name_comp.')';
                } else {
                    $row['text'] = $key->name.' - '. $key->identity .' (Não vinculado)';
                }
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
        $data = LogisticsTransporterVehicle::with('logistics_transporter', 'logistics_supplier')->where('registration_plate', 'like', '%'. $name .'%')
                ->orderBy('id', 'DESC')
                ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;

                if($key->transporter_id != 0 || $key->supplier_id != 0) {
                    $name_comp = $key->transporter_id ? $key->logistics_transporter->name : $key->logistics_supplier->name;
                    $row['text'] = $key->registration_plate.' - '. ' ('.stringCut($name_comp, 28).')';
                } else {
                    $row['text'] = $key->registration_plate. ' (Não vinculado)';    
                }

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

                if($key->transporter_id != 0 || $key->supplier_id != 0) {
                    $name_comp = $key->transporter_id ? $key->logistics_transporter->name : $key->logistics_supplier->name;
                    $row['text'] = $key->registration_plate.' - '. ' ('.stringCut($name_comp, 28).')';
                } else {
                    $row['text'] = $key->registration_plate. ' (Não vinculado)';    
                }

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

    public function containerListDropdown(Request $request) {

        $name = $request->search;

        $data = LogisticsContainer::with('logistics_transporter')->where('number_container', 'like', '%'. $name .'%')
                ->orderBy('id', 'DESC')
                ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;

                if($key->logistics_transporter)
                    $row['text'] = $key->number_container.' - '. ' ('.stringCut($key->logistics_transporter->name, 20).')';
                else
                    $row['text'] = $key->number_container.' - '. ' ('.stringCut($key->name, 20).')';
                
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

    private function uploadFile($file, $request) {
    
        $response = $this->uploadS3(1, $file, $request);

        if ($response['success'])
            return $response['url'];
        else
            throw new \Exception('Não foi possível fazer upload do arquivo!');
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
	
	public function RequestVisitorCargoMonitor(Request $request) {

        $entry_exit_gate = LogisticsEntryExitGate::all();
        return view('gree_i.logistics.request_visitor_cargo_monitor',[
            'entry_exit_gate' => $entry_exit_gate
        ]);
    }
	
	public function RequestVisitorCargoMonitorAjax(Request $request) {
        
		$solicitations = collect([]);
        $type_reason = $request->reason ? array_map('intval', explode(',', $request->reason)) : [];
        
        $data_transport = LogisticsEntryExitRequests::with('request_user', 'logistics_entry_exit_gate')
            ->whereHas('logistics_entry_exit_gate', function ($q) {
                $q->where('is_receivement', 1);
            })->where('is_cancelled', 0)
			->where('is_receivement_check', 0)
            ->where('is_content', 1)
            ->where('is_approv', 1)
            ->whereNotIn('type_reason', [3,9,10])
            ->where(function ($q) use ($type_reason) {
                if(count($type_reason) > 0)
                    $q->whereIn('type_reason', $type_reason);
            })
            ->where('is_denied', 0)
            ->where('is_liberate', 0)
            ->whereDate('date_hour_initial', '>=', date('Y-m-d', strtotime($request->start)))
			->whereDate('date_hour', '<=', date('Y-m-d', strtotime($request->end)));
		
		if ($request->gate) {
			$data_transport->where('entry_exit_gate_id', $request->gate);
		}
		
		$trasnport = $data_transport->get();

        $solicitations = $this->createUniqueCollect($trasnport, $solicitations);

        $data_visite = LogisticsEntryExitRequestsSchedule::with('logistics_entry_exit_requests.request_user', 'logistics_entry_exit_requests.logistics_entry_exit_gate')
            ->whereHas('logistics_entry_exit_requests', function ($q) use ($type_reason) {
                $q->where('is_cancelled', 0)
                    ->where('is_approv', 1)
					->where('is_receivement_check', 0)
                    ->where(function ($query) use ($type_reason) {
                        if(count($type_reason) > 0) {
                            $query->whereIn('type_reason', $type_reason);
                        } else {
                            $query->whereIn('type_reason', [3,9,10]);
                        }
                    })
                    ->where('is_content', 1)
                    ->whereHas('logistics_entry_exit_gate', function ($q1) {
                        $q1->where('is_receivement', 1);
                    });
            })
            ->where('is_denied', 0)
            ->where('is_liberate', 0)
            ->whereDate('date_hour', '>=', date('Y-m-d', strtotime($request->start)))
			->whereDate('date_hour', '<=', date('Y-m-d', strtotime($request->end)));
	
		if ($request->gate) {
			$data_visite->whereHas('logistics_entry_exit_requests', function ($q) use ($request) {
				$q->where('entry_exit_gate_id', $request->gate);
			});
		}
		
		$visite = $data_visite->get();

        $solicitations = $this->createUniqueCollect($visite, $solicitations, true);

        return response()->json($solicitations);
    }  

    private function createUniqueCollect($collect, $solicitations, $use_relation = false) {

        foreach ($collect as $val) {
            $request = $use_relation ? $val->logistics_entry_exit_requests : $val;
            $date_initial = $request->date_hour_initial ?? $val->date_hour;
            $time_start = date('H:i', strtotime($date_initial));
            $time_end = date('H:i', strtotime($request->date_hour));

            $is_entry_exit = $use_relation ? $val->is_entry_exit : $request->is_entry_exit;

            $solicitations->push([
                'id' => $request->id,
                'title' => $time_start.'-'.$time_end.' - '.$request->type_reason_name . ' - '.$request->code,
                'start' => $use_relation ? $date_initial : $date_initial,
                'end' => $use_relation ? $val->date_hour : $request->date_hour,
                'gate' => $request->logistics_entry_exit_gate->id,
                'display' => 'block',
                'color' => $is_entry_exit == 1 ? '#007bff' : '#ffff00',
                'textColor' => $is_entry_exit == 1 ? '#ffffff' : 'black',
                'extendedProps' => array(
                    'is_visite' => $use_relation,
                    'code' => $request->code,
                    'request_forwarding' => $use_relation ? $val->request_forwarding : $request->request_forwarding,
                    'request_user' => $request->request_user->short_name ?? '',
                    'request_action_time' => $use_relation ? $val->request_action_time : $request->request_action_time,
                    'is_liberate' => $use_relation ? $val->is_liberate : $request->is_liberate,
                    'is_entry_exit' => $is_entry_exit
                ),
                'allDay' => true
            ]);
        }

        return $solicitations;
    }
}    
