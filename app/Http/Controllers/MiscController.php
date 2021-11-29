<?php

namespace App\Http\Controllers;

use App\Exports\DefaultExport;
use App\Imports\DefaultImport;
use App\Model\Commercial\ProgramationMacro;
use App\Model\Commercial\SetProductGroup;
use App\Model\Users;
use App\Model\UserOnPermissions;
use App\Model\ListTransmission;
use App\Model\Trips;
use App\Model\TripPlan;
use App\Model\TripPeoples;
use App\Model\Task;
use App\Model\TaskResponsible;
use App\Model\TaskHistory;
use App\Model\TaskAnalyze;
use App\Model\TaskAnalyzeCompleted;
use App\Model\TaskCopyContact;
use App\Model\LogAccess;
use App\Model\Countries;
use App\Model\Regions;
use App\Model\Sector2;
use App\Model\Sector3;
use App\Model\ProductSubLevel1;
use App\Model\ProductSubLevel2;
use App\Model\ProductSubLevel3;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use Hash;
use App;
use Log;
use App\Charts\SacChart;
use App\Model\Graphics;
use App\Classes\Extenso;
use App\Classes\FPDF;
use App\Model\UserFinancy;
use App\Model\SacClient;
use App\Model\SacAuthorized;
use App\Model\ProductAir;
use App\Model\ProductControl;
use App\Model\SacProtocol;
use App\Model\SacPartProtocol;
use App\Model\Parts;
use App\Model\ProductParts;
use App\Model\SacShop;
use App\Model\SacShopParts;
use App\Model\SacAuthorizedType;
use App\Model\SacOsProtocol;
use App\Model\SacModelOs;
use App\Model\SacModelProtocol;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Carbon\Carbon;

use App\Model\Representation;
use App\Model\FinancyAccountability;
use Maatwebsite\Excel\Facades\Excel;
use Str;
use App\Model\FinancyRPayment;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

use App\Model\Commercial\SetProductSave;
use App\Model\Commercial\SalesmanTablePriceTemplate;
use App\Model\Commercial\SalesmanTablePrice;
use App\Model\Commercial\Salesman;
use App\Model\Commercial\Client;
use App\Model\Commercial\ClientGroup;
use App\Model\JuridicalLawFirm;
use App\Model\JuridicalTypeCost;
use App\Model\JuridicalProcessCost;
use App\Model\JuridicalProcess;
use NcJoes\OfficeConverter\OfficeConverter;
use App\Http\Controllers\Services\CommercialTrait;

use \App\Http\Controllers\Services\FileManipulationTrait;

class MiscController extends Controller
{

    use FileManipulationTrait;
	use CommercialTrait;

    public function cleanEmails() {

        $users = Users::all();

        foreach ($users as $key) {
            ListTransmission::where('email', $key->email)->delete();
        }

        return 'ok';
    }

    public function CurrencyToWords(Request $request) {

        if ($request->amount) {

            return response()->json([
                'success' => true,
                'words' => Extenso::converte($request->amount, true, false),
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }

    }
	
	public function sacProductListProtocol(Request $request) {
        $name = $request->search;

        $data = ProductAir::where('model', 'like', '%'. $name .'%')
                            ->where('is_active', 1)
                            ->orWhere(function ($query) use ($name) {
                                $query->where('code_unity', 'like', '%'. $name .'%')
                                    ->where('is_active', 1);
                            })
                            ->orderBy('id', 'DESC')
                            ->paginate(10);
                                            
        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->model;

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

    public function authorizedCertifiedGen(Request $request) {

        $authorized = SacAuthorized::find($request->session()->get('sac_authorized_id'));

        $pdf = new FPDF('L','mm','A4');
        $pdf->AddPage();
        $pdf->AddFont('calibri-bold','','calibri-bold.php');
        $pdf->AddFont('calibri-light','','calibri-light.php');

        $pdf->Image($request->root() .'/media/authorized_certified_bg.png',0,0,300);

        $pdf->SetFont('calibri-bold','',18);
        $pdf->SetY(88); // abscissa of Horizontal position 
        $pdf->SetX(20); // abscissa of Horizontal position 
        $pdf->MultiCell(270,9,utf8_decode($authorized->name),0, 'L', false);

        $pdf->SetFont('calibri-light','',18);
        $pdf->SetY(110); // abscissa of Horizontal position 
        $pdf->SetX(35); // abscissa of Horizontal position 
        $pdf->MultiCell(235,7,utf8_decode('Inscrito no CNPJ sob n.º '. $authorized->identity .', como prestadora de serviço de Assistência Técnica para os produtos CONDICIONADORES DE AR  residenciais, bem como, os produtos similares como aquecedores, ventiladores, portátil e outros fabricados e/ou distribuídos pela GREE do Brasil.'),0, 'C', false);

        return $pdf->Output();

    }

    public function userBank(Request $request) {

        $user = UserFinancy::where('r_code', $request->rcode)->first();

        if ($user) {

            return response()->json([
                'success' => true,
                'agency' => $user->agency,
                'account' => $user->account,
                'bank' => $user->bank,
                'identity' => $user->identity,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }

    }

    public function userUpdatePermissions(Request $request) {

        DB::table('users')->where('is_active', 1)->orderBy('id', 'DESC')->chunk(100, function($users)
        {
            foreach ($users as $user)
            {

                $permission_1 = UserOnPermissions::where('user_r_code', $user->r_code)->where('perm_id', 9)->first();
                if (!$permission_1) {
                    $perm_new = new UserOnPermissions;
                    $perm_new->user_r_code = $user->r_code;
                    $perm_new->perm_id = 9;
                    $perm_new->save();
                }
                $permission_2 = UserOnPermissions::where('user_r_code', $user->r_code)->where('perm_id', 11)->first();
                if (!$permission_2) {
                    $perm_new = new UserOnPermissions;
                    $perm_new->user_r_code = $user->r_code;
                    $perm_new->perm_id = 11;
                    $perm_new->save();
                }
                $permission_3 = UserOnPermissions::where('user_r_code', $user->r_code)->where('perm_id', 12)->first();
                if (!$permission_3) {
                    $perm_new = new UserOnPermissions;
                    $perm_new->user_r_code = $user->r_code;
                    $perm_new->perm_id = 12;
                    $perm_new->save();
                }
            }
        });

    }

    public function getSubSectors(Request $request, $nivel, $id) {

        $list = "";
        if ($nivel == 2) {

            $data = Sector2::where('sector_id', $id)->get();

            $list .= '<option value="0"></option>';
            foreach ($data as $key) {

                $list .= '<option value="'. $key->id .'">'. __('layout_i.'. $key->name .'') .'</option>';
            }
        } else if ($nivel == 3) {

            $data = Sector3::where('sector_id_2', $id)->get();

            $list .= '<option value="0"></option>';
            foreach ($data as $key) {

                $list .= '<option value="'. $key->id .'">'. __('layout_i.'. $key->name .'') .'</option>';
            }
        }


        return response()->json([
            'success' => true,
            'list' => $list,
        ]);

    }

    public function getSubProducts(Request $request, $nivel, $id) {

        $list = "";
        if ($nivel == 1) {

            $data = ProductSubLevel1::where('product_category_id', $id)->orderBy('name', 'DESC')->get();

            $list .= '<option value="0"></option>';
            foreach ($data as $key) {

                $list .= '<option value="'. $key->id .'">' . $key->name .'</option>';
            }
        } else if ($nivel == 2) {

            $data = ProductSubLevel2::where('product_sub_level_1_id', $id)->orderBy('name', 'DESC')->get();

            $list .= '<option value="0"></option>';
            foreach ($data as $key) {

                $list .= '<option value="'. $key->id .'">'. $key->name .'</option>';
            }
        } else if ($nivel == 3) {

            $data = ProductSubLevel3::where('product_sub_level_2_id', $id)->orderBy('name', 'DESC')->get();

            $list .= '<option value="0"></option>';
            foreach ($data as $key) {

                $list .= '<option value="'. $key->id .'">'. $key->name .'</option>';
            }
        }


        return $list;

    }

    public function getPartProducts(Request $request, $id) {

        $list = "";

        if ($id == 2) {
            $data = DB::table('product_control')
                ->leftJoin('product_air', 'product_control.product_id', '=', 'product_air.id')
                ->leftJoin('voltages', 'product_control.voltage_id', '=', 'voltages.id')
                ->select('product_control.*', 'voltages.name as voltages_name', 'product_air.model')
                ->where('product_control.product_category_id', $id)
                ->get();

            $list .= '<option value="0"></option>';
            foreach ($data as $key) {

                $list .= '<option value="'. $key->id .'">' . $key->model .' ('. $key->voltages_name .')</option>';
            }
        }


        return $list;

    }

    public function getPartsList(Request $request, $id) {

        $list = "";


        $data = DB::table('product_parts')
            ->leftJoin('product_control', 'product_parts.product_control_id', '=', 'product_control.id')
            ->leftJoin('product_air', 'product_control.product_id', '=', 'product_air.id')
            ->leftJoin('parts', 'product_parts.part_id', '=', 'parts.id')
            ->select('parts.*')
			->where('parts.is_active', 1)
            ->where('product_air.id', $id)
            ->get();

        if (count($data) > 0) {

            $list .= '<option value="0"></option>';
            foreach ($data as $key) {

                $list .= '<option value="'. $key->id .'">' . $key->description .' ('. $key->code .')</option>';
            }
        }


        return $list;

    }

    public function chartjsBarTrip(Request $request, $month) {

        $borderColors = [
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",
            "rgba(205,220,57, 1.0)"
        ];
        $fillColors = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)"

        ];

        $fay_actual = collect([]);
        for ($fin=1; $fin <= 10; $fin++) {

            $fin = $fin == 10 ? 99 : $fin;
            $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                ->leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                ->where('trip_plan.is_completed', 1)
                ->where('trip_plan.finality', $fin)
                ->whereYear('trip_plan.origin_date', date('Y', strtotime($month)))
                ->whereMonth('trip_plan.origin_date', date('m', strtotime($month)))
                ->sum('trip_agency_budget.total');

            $total = $total == null ? 0 : $total;
            $fay_actual->push(number_format($total, 2, '.', ''));
        }


        $fay_last = collect([]);
        $last_m = date('m', strtotime($month)) - 1;
        for ($fin=1; $fin <= 10; $fin++) {
            $fin = $fin == 10 ? 99 : $fin;
            $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                ->where('trip_plan.is_completed', 1)
                ->where('trip_plan.finality', $fin)
                ->whereYear('trip_plan.origin_date', date('Y', strtotime($month)))
                ->whereMonth('trip_plan.origin_date', $last_m)
                ->sum('trip_agency_budget.total');
            $total = $total == null ? 0 : $total;
            $fay_last->push(number_format($total, 2, '.', ''));
        }

        $finalityAmountMonth = new SacChart;
        $finalityAmountMonth->displayLegend(false);
        $finalityAmountMonth->labels([__('trip_i.finality_1'), __('trip_i.finality_2'), __('trip_i.finality_3'), __('trip_i.finality_4'), __('trip_i.finality_5'), __('trip_i.finality_6'), __('trip_i.finality_7'), __('trip_i.finality_8'), __('trip_i.finality_9'), __('trip_i.finality_99')]);
        $finalityAmountMonth->dataset("". date('F', strtotime($month ."-1 month")) ."", 'bar', $fay_last)
            ->color($borderColors)
            ->backgroundcolor($fillColors);
        $finalityAmountMonth->dataset("". date('F', strtotime($month)) ."", 'bar', $fay_actual)
            ->color($borderColors)
            ->backgroundcolor($fillColors);

        $fillColors = collect([
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)"

        ]);

        $borderColors = collect([
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",
            "rgba(205,220,57, 1.0)"
        ]);
        $label = collect([__('trip_i.finality_1'), __('trip_i.finality_2'), __('trip_i.finality_3'), __('trip_i.finality_4'), __('trip_i.finality_5'), __('trip_i.finality_6'), __('trip_i.finality_7'), __('trip_i.finality_8'), __('trip_i.finality_9'), __('trip_i.finality_99')]);

        $json = "{type:'bar', data:{labels: ". $label .", datasets:[{data:". $fay_last .", borderWidth: 2, backgroundColor: ". $fillColors .", borderColor: ". $borderColors ." },{data:". $fay_actual .", borderWidth: 2, backgroundColor: ". $fillColors .", borderColor: ". $borderColors ." }]}, options: {legend: { display: false }}}";

        return redirect("https://quickchart.io/chart?width=500&height=300&c=". $json);

    }

    public function sacRegisterList(Request $request) {
        $name = $request->search;

        if ($request->type == 3) {
            $data = Representation::where('name', 'like', '%'. $name .'%')
                ->orWhere(function ($query) use ($name) {
                    $query->where('identity', 'like', '%'. $name .'%');
                })
                ->orderBy('id', 'DESC')
                ->paginate(10);
        } else if ($request->type == 2) {
            $data = SacShopParts::where('name', 'like', '%'. $name .'%')
                ->orderBy('id', 'DESC')
                ->paginate(10);

        } else if ($request->type == 1) {
            $data = SacShop::where('name', 'like', '%'. $name .'%')
                ->orderBy('id', 'DESC')
                ->paginate(10);
        }

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name .' ('. $key->state.')';

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

    public function sacClientList(Request $request) {
        $name = $request->search;

        $data = SacClient::where('name', 'like', '%'. $name .'%')
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
                $row['text'] = $key->name ." (". $key->identity .")";

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

    public function sacAuthorizedList(Request $request) {
        $name = $request->search;

        $data = SacAuthorized::where('name', 'like', '%'. $name .'%')
            ->orWhere(function ($query) use ($name) {
                $query->where('identity', 'like', '%'. $name .'%');
            })
            ->orWhere(function ($query) use ($name) {
                $query->where('code', 'like', '%'. $name .'%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name ." (". $key->identity .")";

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

    public function sacProtcolList(Request $request) {
        $name = $request->search;

        $data = SacProtocol::where('code', 'like', '%'. $name .'%')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->code;

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

    public function sacProductList(Request $request) {
        $name = $request->search;

        $data = ProductAir::where('model', 'like', '%'. $name .'%')
            ->where('is_active', 1)
            ->orWhere(function ($query) use ($name) {
                $query->where('code_unity', 'like', '%'. $name .'%')
                    ->where('is_active', 1);
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = '('.$key->sales_code.') - '.$key->model;

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

    public function commercialProductGroupList(Request $request) {
        $name = $request->search;

        $data = SetProductGroup::where('name', 'like', '%'. $name .'%')
            ->where('is_active', 1)
            ->orWhere(function ($query) use ($name) {
                $query->where('code', 'like', '%'. $name .'%')
                    ->where('is_active', 1);
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name .' ('. $key->code.')';

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
	
	public function commercialTablePriceList(Request $request) {
        $name = $request->search;

        $data = SalesmanTablePrice::where('name', 'like', '%'. $name .'%')
            ->orWhere('code', $name)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = '('.$key->code.') '. $key->name;

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


    public function commercialProductSaveList(Request $request) {
        $name = $request->search;

        $id = $request->session()->has('salesman_data') ? $request->session()->get('salesman_data')->id :0;
        $data = SalesmanTablePriceTemplate::where('name', 'like', '%'. $name .'%')
            ->where('salesman_id',$id)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name;
                $row['type_client'] = $key->type_client;
                $row['descont_extra'] = $key->descont_extra;
                $row['charge'] = $key->charge;
                $row['contract_vpc'] = $key->contract_vpc;
                $row['average_term'] = $key->average_term;
                $row['pis_confis'] = $key->pis_confis;
                $row['icms'] = $key->icms;
                $row['adjust_commercial'] = $key->adjust_commercial;
                $row['is_suframa'] = $key->is_suframa;

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

    public function commercialClientGroupList(Request $request) {
        $name = $request->search;

        $data = ClientGroup::where('code', 'like', '%'. $name .'%')
                            ->where('is_active', 1)
                            ->orWhere(function ($query) use ($name) {
                                $query->where('name', 'like', '%'. $name .'%')
                                      ->where('is_active', 1);
                            })
                            ->orderBy('id', 'DESC')
                            ->paginate(10);
        $results = array();
                            
        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->name .' ('. $key->code.')';

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
	
	public function commercialSalesmanClientGroupList(Request $request) {
        $name = $request->search;

		$data = Client::with(['client_group' => function($q) use ($name) {
			$q->groupBy('id')
				->where('name', 'like', '%'. $name .'%')->take(1);	
		}])->ShowOnlyManager(\Session::get('salesman_data')->id)
			->whereHas('client_group')
			->orderBy('id', 'DESC')
            ->paginate(10);
                  
        $results = array();
                            
        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->client_group[0]->id;
                $row['text'] = $key->client_group[0]->name;

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


    public function commercialSalesmanList(Request $request) {
        $name = $request->search;

        $data = Salesman::whereRaw("CONCAT_WS(' ', `first_name`, `last_name`) like '%$name%'")
            ->orWhere(function ($query) use ($name) {
                $query->where('identity', 'like', '%'. $name .'%');
            })
            ->orWhere(function ($query) use ($name) {
                $query->where('code', 'like', '%'. $name .'%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = "". $key->first_name ." ". $key->last_name ." (". $key->identity .")";
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

    public function commercialClientList(Request $request) {
        $name = $request->search;

        $data = Client::where('fantasy_name', 'like', '%'. $name .'%')
            ->where('is_active', 1)
			->where('has_analyze', 0)
            ->orWhere(function ($query) use ($name) {
                $query->where('identity', 'like', '%'. $name .'%')
                    ->where('is_active', 1)
					->where('has_analyze', 0);
            })
			->orWhere(function ($query) use ($name) {
                $query->where('code', 'like', '%'. $name .'%')
                    ->where('is_active', 1)
					->where('has_analyze', 0);
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->fantasy_name .' ('. $key->code.') '.$key->identity;
				$row['company_name'] = $key->company_name;
				$row['identity'] = $key->identity;
				$row['group'] = $key->group->name ?? '';

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
	
	public function commercialSalesmanClientList(Request $request) {
        $name = $request->search;

        $data = Client::ShowOnlyManager(\Session::get('salesman_data')->id)
			->where(function($q) use ($name) {
				$q->where('fantasy_name', 'like', '%'. $name .'%')
					->orWhere(function ($query) use ($name) {
						$query->where('identity', 'like', '%'. $name .'%');
					})
					->orWhere(function ($query) use ($name) {
						$query->where('code', 'like', '%'. $name .'%');
					});
			})
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->fantasy_name .' ('. $key->code.') '.$key->identity;

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

    public function usersList(Request $request) {
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
                $row['id'] = $key->id;
                $row['picture'] = $key->picture;
                $row['text'] = $key->first_name .' '. $key->last_name;
                $row['r_code'] = $key->r_code;

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
	
	public function comercialUsersList(Request $request) {
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

    public function commercialClientTemplateList(Request $request) {
        $name = $request->search;

        $data = SetProductSave::where('name', 'like', '%'. $name .'%')
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

    public function sacPartsList(Request $request) {
        $name = $request->search;

        $data = Parts::leftjoin('product_parts', 'parts.id', '=', 'product_parts.part_id')
            ->leftjoin('product_control', 'product_parts.product_control_id', '=', 'product_control.id')
            ->where('parts.code', 'like', '%'. $name .'%')
            ->where('parts.is_active', 1)
            ->where('product_control.product_id', $request->p)
            ->orWhere(function ($query) use ($name, $request) {
                $query->where('parts.description', 'like', '%'. $name .'%')
                    ->where('parts.is_active', 1)
                    ->where('product_control.product_id', $request->p);
            })
            ->select('parts.id', 'parts.description', 'parts.code', 'parts.amount')
            ->orderBy('parts.id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->description .'('. $key->code .')';
                $row['amount'] = $key->amount;
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

    public function shopImport(Request $request) {
        $filename = $request->csv;

        $key_google = getConfig("google_key_web");

        set_time_limit(360);
        $file = fopen($filename, "r");
        $line = 0;
        while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
        {
            $line++;

            $insert = new SacShop;
            $insert->name = utf8_encode($getData[0]);
            $insert->email = $getData[1];
            $insert->phone = $getData[2];
            $insert->phone_2 = $getData[3];
            $number = $getData[5] != "S/N" ? $getData[5] : "";
            $address = $getData[4] .", ". $number ." ". $getData[6] .", ". $getData[7];
            $real_ad = utf8_encode($address);
            $insert->address = $real_ad;
            $convert = linkConstructGoogle($real_ad);
            $prepAddr = str_replace(' ','+', $convert);
            $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false&key='. $key_google);
            $output= json_decode($geocode);

            if (isset($output->results[0]->geometry->location->lat) and isset($output->results[0]->geometry->location->lng)) {

                $insert->latitude = $output->results[0]->geometry->location->lat;
                $insert->longitude = $output->results[0]->geometry->location->lng;
            } else {
                $insert->is_active = 0;
            }
            $insert->complement = $getData[8];
            $insert->site = $getData[9];
            $insert->save();

        }

        fclose($file);

        return redirect()->back();
    }

    public function shopPartsImport(Request $request) {
        $filename = $request->csv;

        set_time_limit(800);
        $file = fopen($filename, "r");
        $line = 0;
        while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
        {
            $line++;

            $insert = Parts::where('code', $getData[0])->first();
            if ($insert) {
                $insert->amount = number_format($getData[1], '2', '.', '');
                $insert->save();
            }


        }

        fclose($file);

        return redirect()->back();
    }

    public function authorizedImport(Request $request) {

        $payment = FinancyRPayment::where('module_id', 0)->where('module_type', 0)->get();

        foreach($payment as $pay) {

            $ar = findAttachPayment($pay->id);

            if ($ar['type'] != 0) {

                $upd = FinancyRPayment::find($pay->id);
                $upd->module_id = $ar['id'];
                $upd->module_type = $ar['type'];
                $upd->save();
            }



        }
    }

    public function clientImport(Request $request) {
        $filename = $request->csv;

        set_time_limit(600);
        $file = fopen($filename, "r");
        $line = 0;
        while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
        {
            $line++;

            $insert = Parts::where('code', $getData[0])->first();
            if ($insert) {

                if ($getData[1]) {
                    $source = array(',');
                    $replace = array('.');
                    $total = str_replace($source, $replace, $getData[1]);
                    $insert->amount = $total;
                    $insert->save();
                }
            }

        }

        fclose($file);

        return redirect()->back();
    }

    public function ProductImport(Request $request) {
        $filename = $request->csv;

        set_time_limit(600);
        $file = fopen($filename, "r");
        $line = 0;
        while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
        {
            $line++;

            $insert = new ProductAir;

            $insert->id = $getData[0];
            $insert->import = 1;
            $insert->model = $getData[1];
            if ($getData[2]) {
                $insert->exploded_view = $request->root() .'/'. $getData[2];
            }

            if ($getData[3]) {
                $insert->electric_circuit = $request->root() .'/'. $getData[3];
            }

            if ($getData[4]) {
                $insert->manual = $request->root() .'/'. $getData[4];
            }

            if ($getData[5]) {
                $insert->datasheet = $request->root() .'/'. $getData[5];
            }

            $insert->save();

            $insert2 = new ProductControl;
            $insert2->product_id = $getData[0];
            $insert2->product_category_id = 2;
            $insert2->voltage_id = 2;
            $insert2->save();

        }

        fclose($file);

        return redirect()->back();
    }

    private function GetInfoAddres($get_address, $type)
    {
        $info = "";
        foreach ($get_address["results"] as $result) {
            foreach ($result["address_components"] as $address) {
                // Repeat the following for each desired type
                if (in_array($type, $address["types"])) {
                    $info = $address["short_name"];
                }
            }
        }

        return $info;


    }

    public function unLinkAWS(Request $request) {

        $protocol = SacProtocol::find($request->id);

        if ($protocol) {

            $column = $request->column;
            if (!empty($protocol->$column)) {
                if ($this->removeS3($protocol->$column)) {

                    $protocol->$column = '';
                    $protocol->save();

                    LogSystem("Colaborador realizou a exclusão do arquivo na AWS", $request->session()->get('r_code'));
                    return redirect()->back()->with('success', 'Arquivo foi removido com sucesso!');
                } else {
                    return redirect()->back()->with('error', 'Não foi possível encontrar o arquivo para remoção.');
                }
            } else {

                return redirect()->back()->with('error', 'Não há arquivo para ser deletado.');
            }

        } else {
            return redirect()->back()->with('error', 'Ocorreu um erro desconhecido!');
        }

    }

    public function changeLocale($locale) {

        \Session::put('lang', $locale);
        $user = App\Model\Users::where('r_code', \Session::get('r_code'))->first();
        $user->lang = $locale;
        $user->save();
        return redirect()->back();
    }

    public function hourExtraExcelReport(Request $request) {
        ob_end_clean();

        $heading = array('Colaborador', 'Matricula', 'Iniciou em', 'Concluiu em', 'Horas trabalhadas', 'Aprovado por', 'Observação do gestor');
        $rows = array();

        $start = date('Y-m-21', strtotime($request->d. ' - 1 month'));
        $end = date('Y-m-20', strtotime($request->d));

        $hm = App\Model\RhHourExtra::with('user', 'manager')
            ->where('start_date', '>=', $start)
            ->where('start_date', '<=', $end)
            ->where('is_approv', 1)
            ->orderBy('r_code', 'DESC')
            ->get();

        foreach ($hm as $key) {
            $line = array();

            $start_date = new DateTime($key->start_date);
            $since_start = $start_date->diff(new DateTime($key->end_date));

            $manager = '';
            if ($key->manager)
                $manager = $key->manager->first_name .' '. $key->manager->last_name;

            $line[0] = $key->user->first_name .' '. $key->user->last_name;
            $line[1] = $key->r_code;
            $line[2] = $key->start_date;
            $line[3] = $key->end_date;
            $line[4] = $since_start->h .':'. $since_start->i .':'. $since_start->s .'';
            $line[5] = $manager;
            $line[6] = $key->mng_obs;

            array_push($rows, $line);
        }

        return Excel::download(new DefaultExport($heading, $rows), 'HorasExtrasGree|'. $start .'|'. $end .'.xlsx');
    }
	
	/**
     * @param Request $request
     * namespace = 'App\\Model\\FinancyRPayment'.
     * id = Solicitação que está em análise.
     * @return \Illuminate\Http\JsonResponse
     */
    public function processAnalyzeDepartaments(Request $request) {
        $class = $request->namespace;
		$on = $request->connection?? 'mysql';
        if (!class_exists($class))
            return response()->json(['msg' => 'Módulo informado não existe.'], 400);

        $module = $class::on($on)->find($request->id);
        if (!$module)
            return response()->json(['msg' => 'Módulo não foi encontrado.'], 400);

        if (!method_exists($module, 'rtd_analyze'))
            return response()->json(['msg' => 'Módulo não é uma solicitação de aprovação.'], 400);
		
		$request_code = '';
        if (method_exists($module, 'relationship')) {
            $load_relationship = $module->relationship();
            if ($load_relationship) {
                $request_code = $module->code;
                $module = $load_relationship;
            }
        }

        $module->load('rtd_analyze.users');
        return response()->json([
            'module' => $module,
			'request_code' => $request_code,
            'rtd_status' => $module->rtd_status,
        ]);
    }
	
	public function migrateRefundApprov() {
        $refund = App\Model\FinancyRefund::where('pres_approv', 1)->get();

        foreach ($refund as $key) {
            $key->is_approv = 1;
            $key->has_analyze = 0;
            $key->version = 1;
            $key->save();

            if ($key->payment_request_id) {
                $r_payment = new App\Model\FinancyRPaymentRelationship;
                $r_payment->financy_r_payment_id = $key->payment_request_id;
                $r_payment->module_id = $key->id;
                $r_payment->module_type = get_class($key);
                $r_payment->save();
            }

            // GESTOR
            if (!$key->dir_r_code) {
                $mng = App\Model\FinancyRefundMngAnalyze::where('financy_refund_id', $key->id)->orderBy('id', 'ASC')->first();

                if ($mng) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $mng->r_code;
                    $add->is_approv = 1;
                    $add->description = $mng->description;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyRefund';
                    $add->updated_at = $mng->updated_at;
                    $add->save();
                }
            } else {
                $mng = App\Model\FinancyRefundDirAnalyze::where('financy_refund_id', $key->id)->orderBy('id', 'ASC')->first();
                if ($mng) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $mng->r_code;
                    $add->is_approv = 1;
                    $add->description = $mng->description;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyRefund';
                    $add->updated_at = $mng->updated_at;
                    $add->save();
                }
            }

            // FINANCEIRO
            $financy = App\Model\FinancyRefundFnyAnalyze::where('financy_refund_id', $key->id)
                ->orderBy('id', 'ASC')
                ->limit(2)
                ->get();

            if ($financy->count()) {
                foreach ($financy as $index => $fin) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->is_approv = 1;
                    $add->r_code = $fin->r_code;
                    $add->description = $fin->description;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyRefund';
                    if ($financy->count() == 1) {
                        $add->mark = 5;
                        $add->position = 2;
                    } else {
                        $add->mark = $index == 0 ? 2 : 5;
                        $add->position = $index == 0 ? 2 : 3;
                    }

                    $add->save();
                }
            }

            $dir = App\Model\FinancyRefundPresAnalyze::where('financy_refund_id', $key->id)->orderBy('id', 'ASC')->first();
            if ($dir) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = $dir->r_code;
                $add->is_approv = 1;
                $add->description = $dir->description;
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyRefund';
                $add->updated_at = $dir->updated_at;
                $add->position = $financy->count() == 1 ? 3 : 4;
                $add->mark = 6;
                $add->save();
            }
        }

        return "Migração realizada das aprovados reembolso";
    }

    public function migrateRefundAnalyze() {
        $refund = App\Model\FinancyRefund::where('has_analyze', 1)->where('pres_approv', 0)->get();

        foreach ($refund as $key) {
            $key->version = 1;
            $key->save();

            // GESTOR
            if (!$key->dir_r_code) {
                $mng = App\Model\FinancyRefundMngAnalyze::where('financy_refund_id', $key->id)->orderBy('id', 'ASC')->first();
                if ($mng) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $mng->r_code;
                    $add->is_approv = 1;
                    $add->description = $mng->description;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyRefund';
                    $add->updated_at = $mng->updated_at;
                    $add->save();
                } else {
                    $user = Users::with('immediates')->where('r_code', $key->request_r_code)->first();
                    $boss = $user->immediates->first();
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $boss->r_code;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyRefund';
                    $add->save();
                }
            } else {
                if ($key->dir_approv) {
                    $mng = App\Model\FinancyRefundDirAnalyze::where('financy_refund_id', $key->id)->orderBy('id', 'ASC')->first();
                    if ($mng) {
                        $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                        $add->r_code = $mng->r_code;
                        $add->is_approv = 1;
                        $add->description = $mng->description;
                        $add->analyze_id = $key->id;
                        $add->analyze_type = 'App\Model\FinancyRefund';
                        $add->updated_at = $mng->updated_at;
                        $add->save();
                    }
                } else {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $key->dir_r_code;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyRefund';
                    $add->save();
                }
            }

            // FINANCEIRO
            $financy = App\Model\FinancyRefundFnyAnalyze::where('financy_refund_id', $key->id)
                ->orderBy('id', 'ASC')
                ->limit(2)
                ->get();

            if ($financy->count()) {
                foreach ($financy as $index => $fin) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->description = $fin->description;
                    $add->is_approv = 1;
                    $add->r_code = $fin->r_code;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyRefund';
                    if ($financy->count() == 1) {
                        $add->mark = 5;
                        $add->position = 2;
                    } else {
                        $add->mark = $index == 0 ? 2 : 5;
                        $add->position = $index == 0 ? 2 : 3;
                    }

                    $add->save();
                }

                if ($key->financy_approv == 0) {
                    if ($financy->count() == 1) {
                        $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                        $add->r_code = '2777';
                        $add->analyze_id = $key->id;
                        $add->analyze_type = 'App\Model\FinancyRefund';
                        $add->mark = 5;
                        $add->position = 3;
                        $add->save();
                    }
                }
            } else {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '1996';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyRefund';
                $add->mark = 2;
                $add->position = 2;
                $add->save();

                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '4411';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyRefund';
                $add->mark = 2;
                $add->position = 2;
                $add->save();

                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '2777';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyRefund';
                $add->mark = 5;
                $add->position = 3;
                $add->save();

            }

            $dir = App\Model\FinancyRefundPresAnalyze::where('financy_refund_id', $key->id)->orderBy('id', 'ASC')->first();
            if (!$dir) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '0004';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyRefund';
                $add->mark = 6;
                $add->position = $financy->count() == 1 ? 3 : 4;
                $add->save();
            }


        }

        return "Migração realizada das análise reembolso";
    }

    public function migrateLendingApprov() {
        $lending = App\Model\FinancyLending::where('pres_approv', 1)->get();

        foreach ($lending as $key) {
            $key->is_approv = 1;
            $key->has_analyze = 0;
            $key->version = 1;
            $key->save();

            if ($key->payment_request_id) {
                $r_payment = new App\Model\FinancyRPaymentRelationship;
                $r_payment->financy_r_payment_id = $key->payment_request_id;
                $r_payment->module_id = $key->id;
                $r_payment->module_type = get_class($key);
                $r_payment->save();
            }

            // GESTOR
            if (!$key->dir_r_code) {
                $mng = App\Model\FinancyLendingMngAnalyze::where('financy_lending_id', $key->id)->orderBy('id', 'ASC')->first();

                if ($mng) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $mng->r_code;
                    $add->is_approv = 1;
                    $add->description = $mng->description;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyLending';
                    $add->updated_at = $mng->updated_at;
                    $add->save();
                }
            } else {
                $mng = App\Model\FinancyLendingDirAnalyze::where('financy_lending_id', $key->id)->orderBy('id', 'ASC')->first();
                if ($mng) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $mng->r_code;
                    $add->is_approv = 1;
                    $add->description = $mng->description;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyLending';
                    $add->updated_at = $mng->updated_at;
                    $add->save();
                }
            }

            // FINANCEIRO
            $financy = App\Model\FinancyLendingFnyAnalyze::where('financy_lending_id', $key->id)
                ->orderBy('id', 'ASC')
                ->first();

            if ($financy) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->is_approv = 1;
                $add->r_code = $financy->r_code;
                $add->description = $financy->description;
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyLending';
                $add->mark = 5;
                $add->position = 2;

                $add->save();
            }

            $dir = App\Model\FinancyLendingPresAnalyze::where('financy_lending_id', $key->id)->orderBy('id', 'ASC')->first();
            if ($dir) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = $dir->r_code;
                $add->is_approv = 1;
                $add->description = $dir->description;
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyLending';
                $add->updated_at = $dir->updated_at;
                $add->position = $financy->count() == 1 ? 3 : 4;
                $add->mark = 6;
                $add->save();
            }
        }

        return "Migração realizada das aprovados empréstimo";
    }

    public function migrateLendingAnalyze() {
        $lending = App\Model\FinancyLending::where('has_analyze', 1)->where('pres_approv', 0)->get();

        foreach ($lending as $key) {
            $key->version = 1;
            $key->save();

            // GESTOR
            if (!$key->dir_r_code) {
                $mng = App\Model\FinancyLendingMngAnalyze::where('financy_lending_id', $key->id)->orderBy('id', 'DESC')->first();
                if ($mng) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $mng->r_code;
                    $add->is_approv = 1;
                    $add->description = $mng->description;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyLending';
                    $add->updated_at = $mng->updated_at;
                    $add->save();
                } else {
                    $user = Users::with('immediates')->where('r_code', $key->r_code)->first();
                    $boss = $user->immediates->first();
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $boss->r_code;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyLending';
                    $add->save();
                }
            } else {
                if ($key->dir_approv) {
                    $mng = App\Model\FinancyLendingDirAnalyze::where('financy_lending_id', $key->id)->orderBy('id', 'DESC')->first();
                    if ($mng) {
                        $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                        $add->r_code = $mng->r_code;
                        $add->is_approv = 1;
                        $add->description = $mng->description;
                        $add->analyze_id = $key->id;
                        $add->analyze_type = 'App\Model\FinancyLending';
                        $add->updated_at = $mng->updated_at;
                        $add->save();
                    }
                } else {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = $key->dir_r_code;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyLending';
                    $add->save();
                }
            }

            // FINANCEIRO
            $financy = App\Model\FinancyLendingFnyAnalyze::where('financy_lending_id', $key->id)
                ->orderBy('id', 'ASC')
                ->first();

            if ($financy) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->description = $financy->description;
                $add->is_approv = 1;
                $add->r_code = $financy->r_code;
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyLending';
                $add->mark = 5;
                $add->position = 2;
                $add->save();

            } else {

                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '2777';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyLending';
                $add->mark = 5;
                $add->position = 2;
                $add->save();

            }

            $dir = App\Model\FinancyLendingPresAnalyze::where('financy_lending_id', $key->id)->orderBy('id', 'ASC')->first();
            if (!$dir) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '0004';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyLending';
                $add->mark = 6;
                $add->position = 3;
                $add->save();
            }


        }

        return "Migração realizada das análise empréstimo";
    }
	
	public function migrateAtualizaca() {
		$analyze = App\Model\Services\Analyze\Model\RequestAnalyze::where('analyze_type', 'App\Model\FinancyLending')
			//->where('r_code', ['4411'])
			//->where('r_code', ['1996'])
			//->where('r_code', ['2743'])
			//->where('r_code', ['4499'])
			->where('r_code', '2777')
			->where('is_approv', 0)
			->where('is_reprov', 0)
			->get();
		
		foreach($analyze as $index => $key) {
			
			$f_analyze = new App\Model\Services\Analyze\Model\RequestAnalyze;
			$f_analyze->analyze_type = 'App\Model\FinancyLending';
			$f_analyze->analyze_id = $key->analyze_id;
			$f_analyze->r_code = '8888';
			$f_analyze->mark = $key->mark;
			$f_analyze->position = $key->position;
			$f_analyze->version = $key->version;
			$f_analyze->save();
			
		}
		
		return 'Concluido: '. date('H:i');
	}

    public function migrateAccountbilityApprov() {
        $accountability = App\Model\FinancyAccountability::where('is_approv', 1)->get();
		
		dd(App\Model\Services\Analyze\Model\RequestAnalyze::where('analyze_type', 'App\Model\FinancyAccountability')->whereIn('analyze_id', $accountability->pluck('id'))->get());

        foreach ($accountability as $key) {
            /*$r_payment = App\Model\FinancyRPayment::find($key->payment_request_id);

            $key->is_approv = 1;
            $key->has_analyze = 0;
            $key->version = 1;
            $key->receipt = $r_payment->receipt;
            $key->save();

            $r_payment = App\Model\FinancyRPayment::find($key->payment_request_id);
            $r_payment->is_approv = 1;
            $r_payment->has_analyze = 0;
            $r_payment->save();

			if ($key->payment_request_id) {
                $r_payment = new App\Model\FinancyRPaymentRelationship;
                $r_payment->financy_r_payment_id = $key->payment_request_id;
                $r_payment->module_id = $key->id;
                $r_payment->module_type = get_class($key);
                $r_payment->save();
            }*/

            // GESTOR
            $mng = App\Model\FinancyRPaymentMngAnalyze::where('financy_payment_id', $key->payment_request_id)->orderBy('id', 'DESC')->first();
            if ($mng) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = $mng->r_code;
                $add->is_approv = 1;
                $add->description = $mng->description;
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyAccountability';
                $add->updated_at = $mng->updated_at;
                $add->save();
            }

            // FINANCEIRO
            $financy = App\Model\FinancyRPaymentFnyAnalyze::where('financy_payment_id', $key->payment_request_id)
                ->orderBy('id', 'ASC')
                ->limit(3)
                ->get();

            if ($financy->count()) {
                foreach ($financy as $index => $fin) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->is_approv = 1;
                    $add->r_code = $fin->r_code;
                    $add->description = $fin->description;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyAccountability';
                    if ($financy->count() == 1) {
                        $add->mark = 5;
                        $add->position = 2;
                    } else if ($financy->count() == 2) {
                        if ($index == 0) {
                            $add->mark = 3;
                            $add->position = 2;
                        } else {
                            $add->mark = 5;
                            $add->position = 3;
                        }
                    } else {
                        if ($index == 0) {
                            $add->mark = 3;
                            $add->position = 2;
                        } else if ($index == 1) {
                            $add->mark = 4;
                            $add->position = 3;
                        } else {
                            $add->mark = 5;
                            $add->position = 4;
                        }
                    }

                    $add->save();
                }
            }

            $dir = App\Model\FinancyRPaymentPresAnalyze::where('financy_payment_id', $key->payment_request_id)->orderBy('id', 'ASC')->first();
            if ($dir) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = $dir->r_code;
                $add->is_approv = 1;
                $add->description = $dir->description;
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyAccountability';
                $add->updated_at = $dir->updated_at;
                if ($financy->count() == 1)
                    $add->position = 3;
                elseif ($financy->count() == 2)
                    $add->position = 4;
                else
                    $add->position = 5;

                $add->mark = 6;
                $add->save();
            }
        }

        return "Migração realizada das aprovados prestação de contas";
    }

    public function migrateAccountbilityAnalyze() {
        $accountability = App\Model\FinancyAccountability::where('has_analyze', 1)->get();

        foreach ($accountability as $key) {
            $key->version = 1;
            $key->save();

            // GESTOR
            $mng = App\Model\FinancyRPaymentMngAnalyze::where('financy_payment_id', $key->payment_request_id)->orderBy('id', 'DESC')->first();

            if ($mng) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = $mng->r_code;
                $add->is_approv = 1;
                $add->description = $mng->description;
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyAccountability';
                $add->updated_at = $mng->updated_at;
                $add->save();
            } else {
                $user = Users::with('immediates')->where('r_code', $key->r_code)->first();
                $boss = $user->immediates->first();
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = $boss->r_code;
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyAccountability';
                $add->save();
            }

            // FINANCEIRO
            $financy = App\Model\FinancyRPaymentFnyAnalyze::where('financy_payment_id', $key->payment_request_id)
                ->orderBy('id', 'ASC')
                ->limit(3)
                ->get();

            if ($financy->count()) {
                foreach ($financy as $index => $fin) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->description = $fin->description;
                    $add->is_approv = 1;
                    $add->r_code = $fin->r_code;
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyAccountability';
                    if ($financy->count() == 1) {
                        $add->mark = 3;
                        $add->position = 2;
                    } elseif ($financy->count() == 2) {
                        if ($index == 0) {
                            $add->mark = 3;
                            $add->position = 2;
                        } else {
                            $add->mark = 4;
                            $add->position = 3;
                        }
                    } elseif ($financy->count() == 3) {
                        if ($index == 0) {
                            $add->mark = 3;
                            $add->position = 2;
                        } elseif ($index == 1) {
                            $add->mark = 4;
                            $add->position = 3;
                        } else {
                            $add->mark = 5;
                            $add->position = 4;
                        }
                    }

                    $add->save();
                }

                if ($financy->count() == 1) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = '1996';
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyAccountability';
                    $add->mark = 4;
                    $add->position = 3;
                    $add->save();

                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = '4411';
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyAccountability';
                    $add->mark = 4;
                    $add->position = 3;
                    $add->save();

                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = '2777';
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyAccountability';
                    $add->mark = 5;
                    $add->position = 4;
                    $add->save();
                } elseif ($financy->count() == 2) {
                    $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                    $add->r_code = '2777';
                    $add->analyze_id = $key->id;
                    $add->analyze_type = 'App\Model\FinancyAccountability';
                    $add->mark = 5;
                    $add->position = 4;
                    $add->save();
                }
            } else {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '2743';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyAccountability';
                $add->mark = 3;
                $add->position = 2;
                $add->save();

                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '1996';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyAccountability';
                $add->mark = 4;
                $add->position = 3;
                $add->save();

                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '4411';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyAccountability';
                $add->mark = 4;
                $add->position = 3;
                $add->save();

                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '2777';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyAccountability';
                $add->mark = 5;
                $add->position = 4;
                $add->save();

            }

            $dir = App\Model\FinancyRPaymentPresAnalyze::where('financy_payment_id', $key->payment_request_id)->orderBy('id', 'ASC')->first();
            if (!$dir) {
                $add = new App\Model\Services\Analyze\Model\RequestAnalyze;
                $add->r_code = '0004';
                $add->analyze_id = $key->id;
                $add->analyze_type = 'App\Model\FinancyAccountability';
                $add->mark = 6;
                $add->position = 5;
                $add->save();
            }

        }

        return "Migração realizada das análise prestação de contas";
    }
	
	public function wdgt_createUsers(Request $request) {

        $arr = [];

        if ($request->hasFile('csv')) {

            try {
                Excel::import(
                    new DefaultImport(
                        new Users,
                        ['r_code', 'first_name', 'last_name', 'office'],
                        function($column_excel, $column_db, $row_excel, $add) use (&$arr) {

                            if($column_excel == '')
                                return ['result' => false];

                            if($column_db == 'r_code') {
                                $verify = Users::where('r_code', $column_excel)->first();
                                if($verify) {
                                    return ['result' => false];
                                }
                                array_push($arr, $column_excel);
                            }

                            $add->$column_db = $column_excel;
                            $add->sector_id = 4;
                            $add->is_active = 1;
                            $add->email = 'recebimento04@gree-am.com.br';
                            $add->password = '$2y$10$58oUl1uEYXPYm8ma/7TWB.uwVt4hfiyGlamHPrSevbAVZjJ5cqigK';

                            return [
                                'collect' => $add,
                                'result' => true
                            ];
                        }
                    ),
                    $request->file('csv')
                );

                $perms = [1,3,9,11,12,26];
                foreach ($arr as $usr) {
                    foreach ($perms as $perm) {
                        $dbperm = new UserOnPermissions;
                        $dbperm->perm_id   = $perm;
                        $dbperm->user_r_code  = $usr;
                        $dbperm->save();
                    }
                }

                return redirect()->back()->with('success', "Usuários importados com sucesso!");
            }
            catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }
	
	public function expeditionViewOrders(Request $request) {
		ob_end_clean();

        if ($request->logout) {
            $request->session()->forget('comercialpedidos');
            return redirect($request->url());
        }
		
        $username = "comercialpedidos";
        $password = "gree&*11425(***";

        $style_content = 'width: 100%;height: 100%;display: flex;justify-content: center;align-items: center;flex-direction: column;';
        $img_loading = "<img src='/loading.gif'>";
        $script_load = '<script>function loading() {var elem = document.getElementById("loading");elem.innerHTML = "'.$img_loading.'";}</script>';

        if ($request->post()) {
            if ($request->username == $username and $request->password == $password) {
                $request->session()->put('comercialpedidos', 'true');
            }
        }

        if (!$request->session()->has('comercialpedidos')) {
            $html = '<div style="'.$style_content.'"><img style="margin-bottom: 35px;" src="https://gree.com.br/wp-content/uploads/2020/01/gree-brasil-logo-main-regular-249x75px.png"><form method="post" action="'.$request->url().'">';
            $html .= '<fieldset><legend>Pedidos comercial:</legend><label for="username">Usuário</label><input id="username" type="text" name="username" placeholder="....">';
            $html .= '<br><br><label for="password">Senha</label><input id="password" type="password" name="password" placeholder="*****">';
            $html .= '<br><br><button onclick="loading()" type="submit">Realizar acesso</button></fieldset>';
            $html .= '</form><div id="loading"></div></div>';

            if ($request->post()) {
                $html .= '<script>alert("Usuário ou senha incorreto.")</script>';
            }
            $html .= $script_load;
            return $html;
        }

        $files = App\Model\Commercial\OrderSales::where('is_approv', 1)->where('is_cancelled', 0);

        if ($request->search) {
            $files->where('code', 'LIKE', '%'.$request->search.'%');
        }

        $html = '';
        $style_td_h = 'border: solid 1px;padding: 5px;background: black;color: white;';
        $style_content = 'width: 100%;height: 100%;display: flex;justify-content: center;align-items: center;flex-direction: column;';

        $search = '<form method="GET" action="'.$request->url().'">';
        $search .= '<div><label>Pesquisar arquivos</label><br><input type="text" value="'.$request->search.'" name="search" placeholder="Pesquise aqui...">';
        $search .= '<button onclick="loading()" type="submit">Buscar</button></div>';
        $search .= '<div style="text-align: center; width: 100%; margin-top: 10px"><a href="?logout=true">Sair da conta</a></div></form>';

        $html .= '<div class="content" style="'.$style_content.'"><img style="margin-bottom: 35px;" src="https://gree.com.br/wp-content/uploads/2020/01/gree-brasil-logo-main-regular-249x75px.png">'.$search.'<table id="loading">';
        $html .= '<tr><td style="'.$style_td_h.'width: 415px;">Nome do arquivo</td><td style="'.$style_td_h.'width: 100px;">Ações</td></tr>';

        foreach ($files->paginate(10) as $file) {
			$complement = !$file->is_programmed?'confirmed/':'';
            $html .= '<tr><td>'.$file->code.'</td><td><a target="_blank" href="/commercial/order/'.$complement.'print/view/'. $file->id .'">Visualizar</a></td></tr>';

        }

        $html .= '</table></div>';
        $html .= $script_load;

        return $html;
		
	}

    public function teste(Request $request) {
	
		$req = App\Model\FinancyRefund::find(37);
		dd($req->relation_payment());
    }

    public function projectlist(Request $request) {

        $request->merge([
            'id' => 14
        ]);

        $request->session()->put('r_code', '4447');

        try {
            $project = new App\Services\Dynamic\Project($request);
            $mproject = App\Model\Dynamic\Projects::with('projects_columns', 'projects_users_columns_configs')->find($request->id);
            if (!$mproject)
                throw new \Exception('Projeto não foi encontrado.');

            $project->validateOwnerProject($mproject);
            dd($project->showColumns($mproject));
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
	
	// EXEMPLO NAVEGAR NO S3 DA AWS
	public function teste2(Request $request) {
        ob_end_clean();

        if ($request->logout) {
            $request->session()->forget('comercialpedidos');
            return redirect($request->url());
        }
        $username = "comercialpedidos";
        $password = "123456";

        $style_content = 'width: 100%;height: 100%;display: flex;justify-content: center;align-items: center;flex-direction: column;';
        $img_loading = "<img src='/loading.gif'>";
        $script_load = '<script>function loading() {var elem = document.getElementById("loading");elem.innerHTML = "'.$img_loading.'";}</script>';

        if ($request->post()) {
            if ($request->username == $username and $request->password == $password) {
                $request->session()->put('comercialpedidos', 'true');
            }
        }

        if (!$request->session()->has('comercialpedidos')) {
            $html = '<div style="'.$style_content.'"><img style="margin-bottom: 35px;" src="https://gree.com.br/wp-content/uploads/2020/01/gree-brasil-logo-main-regular-249x75px.png"><form method="post" action="'.$request->url().'">';
            $html .= '<fieldset><legend>Pedidos comercial:</legend><label for="username">Usuário</label><input id="username" type="text" name="username" placeholder="....">';
            $html .= '<br><br><label for="password">Senha</label><input id="password" type="password" name="password" placeholder="*****">';
            $html .= '<br><br><button onclick="loading()" type="submit">Realizar acesso</button></fieldset>';
            $html .= '</form><div id="loading"></div></div>';

            if ($request->post()) {
                $html .= '<script>alert("Usuário ou senha incorreto.")</script>';
            }
            $html .= $script_load;
            return $html;
        }

        $sfile = $request->get('search')?? $request->file_name;

        $storage = Storage::disk('gree');
        $client = $storage->getAdapter()->getClient();

        $command = $client->getCommand('ListObjects');
        $command['Bucket'] = $storage->getAdapter()->getBucket();

        $main_path = 'comercial/pedidos/';
        $command['Prefix'] = $main_path.$sfile;
        $command['MaxKeys'] = 10; // Max por linha

        $result = $client->execute($command);
        $files = $result->getPath('Contents');


        if ($sfile) {
            if ($files and !$request->get('search')) {
                $path_file = $files[0]['Key'];
                $name_file = str_replace($main_path, '', $path_file);

                $contentFile = Storage::disk('gree')->get($path_file);
                $mime = Storage::disk('gree')->mimetype($path_file);
                //Storage::disk('public')->put($name_file, $contentFile);

                return response($contentFile, 200, ['Content-type' => $mime]);
            }
        }

        $html = '';
        $style_td_h = 'border: solid 1px;padding: 5px;background: black;color: white;';
        $style_content = 'width: 100%;height: 100%;display: flex;justify-content: center;align-items: center;flex-direction: column;';

        $search = '<form method="GET" action="'.$request->url().'">';
        $search .= '<div><label>Pesquisar arquivos</label><br><input type="text" value="'.$request->search.'" name="search" placeholder="Pesquise aqui...">';
        $search .= '<button onclick="loading()" type="submit">Buscar</button></div>';
        $search .= '<div style="text-align: center; width: 100%; margin-top: 10px"><a href="?logout=true">Sair da conta</a></div></form>';

        $html .= '<div class="content" style="'.$style_content.'"><img style="margin-bottom: 35px;" src="https://gree.com.br/wp-content/uploads/2020/01/gree-brasil-logo-main-regular-249x75px.png">'.$search.'<table id="loading">';
        $html .= '<tr><td style="'.$style_td_h.'width: 415px;">Nome do arquivo</td><td style="'.$style_td_h.'width: 100px;">Ações</td></tr>';
        if ($files) {

            foreach ($files as $file) {
                $nfile = str_replace($main_path, '', $file['Key']);
                if ($nfile) {
                    $html .= '<tr><td>'.$nfile.'</td><td><a target="_blank" href="?file_name='.$nfile.'">Visualizar</a></td></tr>';
                }
            }
        }
        $html .= '</table></div>';
        $html .= $script_load;

        return $html;

    }
	
	private function createSheets(array $pattern, array $data) {
		if (!count($pattern['sheets'])) {
			$pattern['sheets'] = $data;
			return $pattern;
		} else {
			$pattern = $this->createSheets($pattern['sheets'], $data);
		}
       	
            
    }
    
    
	public function juridicalProcessList(Request $request) {
        $name = $request->search;

        $data = JuridicalProcess::where('process_number', 'like', '%'. $name .'%')
                    ->orderBy('id', 'DESC')
                    ->paginate(10);
        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->process_number;

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

    public function juridicalLawFirmList(Request $request) {
        $name = $request->search;

        $data = JuridicalLawFirm::where('name', 'like', '%'. $name .'%')
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
                $row['text'] = $key->name ." (". $key->identity .")";

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

    public function juridicalTypeCostList(Request $request) {
        $name = $request->search;

        $data = JuridicalTypeCost::where('description', 'like', '%'. $name .'%')
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
	
	public function componentsAnalyzeCreateApprovers(Request $request) {

        $class = $request->namespace;
        if (!class_exists($class))
            return redirect()->back()->with('error', 'Módulo informado não existe');
        
        $class_dynamic = new $class();
        if (!method_exists($class_dynamic, 'rtd_analyze'))
            return redirect()->back()->with('error', 'Módulo não é uma solicitação de aprovação.');

        $arr_approvers = $class_dynamic->rtd_approvers()->sortBy('position')->groupBy('position');
        $arr_observers_old = $class_dynamic->rtd_observers();

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
                        $new_arr['mark'] = $key['mark'];
                    } else {
                        
                        $sub_arr['name'] = $key['users']['full_name'];
                        $sub_arr['picture'] = $key['users']['picture'];
                        $sub_arr['r_code'] = $key['r_code'];
                        $sub_arr['id'] = $key['id'];
                        $sub_arr['mark'] = $key['mark'];

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
                $new_arr['mark'] = $approv->first()->mark;

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

        if(!method_exists($class_dynamic, 'configClass')) 
            return redirect()->back()->with('error', 'Método de configuração não implementado');

        return view('gree_i.misc.components.analyze.create_approvers', [
            'arr_approv' => $arr_approv,
            'arr_observers' => $arr_observers,
            'arr_approv_verify' => $arr_approv_verify,
            'namespace' => $class,
            'arr_mark' => $class_dynamic->configClass('arr_mark'),
            'name' => $class_dynamic->configClass('name'),
            'activemenu' => $class_dynamic->configClass('activemenu')
        ]);
    }
	
	public function componentsAnalyzeCreateApprovers_do(Request $request) {

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

            $class = $request->namespace;
            $request_analyze = new $class();

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
                $approv->mark = $key['mark'];
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
}
