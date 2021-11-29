<?php

namespace App\Http\Controllers;

use App\Model\Commercial\SetProductPriceFixed;
use App\Exports\DefaultExport;
use App\Exports\DefaultHtmlExport;
use App\Helpers\Commercial\AnalyzeProcessOrder;
use App\Helpers\Commercial\ApplyConditionPriceBase;
use App\Model\Commercial\ClientImdtAnalyze;
use App\Helpers\Commercial\AnalyzeProcessClient;
use App\Helpers\Commercial\ManagerClient;
use App\Http\Controllers\Controller;
use App\Events\EventSocket;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Jobs\SendMailAttachJob;

use App\Model\Commercial\OrderAvaibleMonth;
use App\Model\Commercial\OrderDelivery;
use App\Model\Commercial\OrderProducts;
use App\Model\Commercial\OrderReceiver;
use App\Model\Commercial\OrderSales;
use App\Model\Commercial\OrderSalesAttach;
use App\Model\Commercial\Programation;
use App\Model\Commercial\ProgramationMacro;
use App\Model\Commercial\ProgramationMonth;
use App\Model\UserOnPermissions;
use http\Env\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Hash;
use App;
use Log;

use \App\Model\Users;
use App\Model\Commercial\Salesman;
use App\Model\PromoterUserHistory;
use App\Model\PromoterRoute;
use App\Model\PromoterRouteHistory;
use App\Model\PromoterRouteHistoryImg;
use App\Model\PromoterRequestItem;
use App\Model\PromoterRequestItens;

use Carbon\Carbon;
use \App\Http\Controllers\Services\FileManipulationTrait;
use App\Http\Requests\FormRequestSaveSalesmanProfile;
use App\Http\Requests\FormRequestSaveClient;


use App\Model\Commercial\SetProductOnGroup;
use App\Model\Commercial\SetProductGroup;
use App\Model\Commercial\Settings;
use App\Model\Commercial\SetProduct;
use App\Model\Commercial\SetProductAdjust;
use App\Model\Commercial\SetProductSave;
use App\Model\Commercial\SalesmanTablePrice;
use App\Model\Commercial\SalesmanTablePriceTemplate;
use App\Model\Commercial\OrderFieldTablePrice;
use App\Model\Commercial\OrderTablePriceRules;
use App\Model\Commercial\Client;
use App\Model\Commercial\ClientVersion;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Services\CommercialTrait;
use App\Exports\ClientsExport;

class CommercialSalesmanController extends Controller
{
    use CommercialTrait;
    use FileManipulationTrait;

    public function salesmanLogin(Request $request) {

        return view('gree_commercial_external.login');
    }

    public function salesmanLogout(Request $request) {

        $request->session()->flush();
        return redirect('/comercial/operacao/login');
    }

    public function tabelaPrecoLista(Request $request) {

		if ($request->session()->get('salesman_data')->identity == '03.519.135/0001-56') {
			$salesman_table_price = SalesmanTablePrice::orderBy('id', 'DESC');
		} else {
			$salesman_table_price = SalesmanTablePrice::where('salesman_id', $request->session()->get('salesman_data')->id)
            ->orderBy('id', 'DESC');
		}
        
		
        $settings = Settings::where('command', 'version_table_price')->first();

        $array_input = collect([
            'status',
            'code',
            'name',
            'client_id',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1)
                        $salesman_table_price->where('version', '>=', $valor_filtro);
                    else
                        $salesman_table_price->where('version', '<', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."code"){
                    $salesman_table_price->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."name"){
                    $salesman_table_price->where('name', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."client_id"){
                    $salesman_table_price->where('client_id', $valor_filtro);
                }

            }
        }

        $clients = Client::where('request_salesman_id', $request->session()->get('salesman_data')->id)->orderBy('company_name', 'ASC')->get();

        return view('gree_commercial_external.salesman.tablePriceList', [
            'salesman_table_price' => $salesman_table_price->paginate(10),
            'version' => $settings->value,
            'clients' => $clients,
        ]);

    }

    public function tabelaPreco(Request $request, $id) {

        $months = $this->setSessionDatesAvaibles(date('Y-01-01'), true);

        $array_input = collect([
            'status',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1)
                        $salesman_table_price->where('is_active', 1);
                    else
                        $salesman_table_price->where('is_active', 0);

                }
            }
        }

        $table = '';
        if ($id != 0) {
			if ($request->session()->get('salesman_data')->identity == '03.519.135/0001-56') {
				$table = SalesmanTablePrice::where('id', $id)
                ->first();
			} else {
				$table = SalesmanTablePrice::where('id', $id)
                ->where('salesman_id', $request->session()->get('salesman_data')->id)
                ->first();
			}
            
        }

        $fields = OrderFieldTablePrice::all();

        $products = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $rules = OrderTablePriceRules::all()->toJson();

        $month = [1 => 'janeiro',2 => 'Fevereiro',3 => 'Março', 4 =>'Abril',5 =>'Maio', 6 =>'Junho', 7 =>'julho', 8 =>'Agosto', 9 =>'Setembro', 10 =>'Outubro', 11 =>'Novembro', 12 =>'Dezembro',];

        $category = Client::with(['client_group' => function($q) {
			$q->groupBy('id');	
		}])->ShowOnlyManager(\Session::get('salesman_data')->id)
			->whereHas('client_group')
			->orderBy('id', 'DESC')
            ->get();

        return view('gree_commercial_external.salesman.tablePrice', [
            'products' => $products,
            'fields' => $fields,
            'rules' => $rules,
            'months' => $months,
            'month' => $month,
            'table' => $table,
            'category' => $category,
            'id' => $id,
        ]);
    }

    public function tabelaPrecoDeletar(Request $request, $id) {

        $set_product = SalesmanTablePrice::where('id', $request->id)
            ->where('salesman_id', $request->session()->get('salesman_data')->id)
            ->first();

        if ($set_product) {

            SalesmanTablePrice::where('id', $id)->delete();

            $request->session()->put('success', 'Tabela de preço foi deletada com sucesso!');
            return redirect()->back();

        } else {

            $request->session()->put('error', 'Tabela de preço não foi encontrada no banco de dados.');
            return redirect()->back();
        }

    }

    public function tabelaPreco_do(Request $request) {

        if ($request->id == 0) {

            $table = new SalesmanTablePrice;

        } else {

            $table = SalesmanTablePrice::where('id', $request->id)
                ->where('salesman_id', $request->session()->get('salesman_data')->id)
                ->first();

            if (!$table) {
                return redirect()->back()->with('error', 'Tabela não existe no banco de dados.');
            }
        }

        $table->salesman_id = $request->session()->get('salesman_data')->id;
        $table->name = $request->name;

        // Gen code
        if ($request->id == 0) {

            $last_code = '';

            $code = SalesmanTablePrice::orderBy('id', 'DESC')->first();
            if ($code) {
                $last = $code->id + 1;
                $last_code = 'CTP-'. $last;
            } else {
                $last_code = 'CTP-1';
            }

            $table->code = $last_code;
        }

        if ($request->type_client == null) {
			$table->type_client = 0;
		} else {
			$table->type_client = $request->type_client;
		}
		
		if ($request->descont_extra == null) {
			$table->descont_extra = 0;
		} else {
			$table->descont_extra = $request->descont_extra;
		}
        	
        if ($request->charge == null) {
			$table->charge = 0;
		} else {
			$table->charge = $request->charge;
		}
		
		if ($request->contract_vpc == null) {
			$table->contract_vpc = 0;
		} else {
			$table->contract_vpc = $request->contract_vpc;
		}
		
		if ($request->average_term == null) {
			$table->average_term = 0;
		} else {
			$table->average_term = $request->average_term;
		}
		
		if ($request->pis_confis == null) {
			$table->pis_confis = 0;
		} else {
			$table->pis_confis = $request->pis_confis;
		}
		
		if ($request->cif_fob == null) {
			$table->cif_fob = 0;
		} else {
			$table->cif_fob = $request->cif_fob;
		}
		
		if ($request->icms == null) {
			$table->icms = 0;
		} else {
			$table->icms = $request->icms;
		}
		
		if ($request->adjust_commercial == null) {
			$table->adjust_commercial = 0;
		} else {
			$table->adjust_commercial = $request->adjust_commercial;
		}
		
		if ($request->is_suframa == null) {
			$table->is_suframa = 0;
		} else {
			$table->is_suframa = $request->is_suframa;
		}

        $table->is_programmed = $request->is_programmed ? 1 : 0;

        $settings = Settings::where('command', 'version_table_price')->first();
        $table->version = $settings->value;
		$table->date_condition = $request->date_condition.'-01';
        $table->description_condition = $request->description_condition;
        $table->save();

        if ($request->id == 0)
            return redirect()->back()->with('success', 'Tabela de preço, foi criada com sucesso.');
        else
            return redirect()->back()->with('success', 'Tabela de preço, foi atualizada com sucesso.');

    }

    public function clientConditionTablePriceExport(Request $request, $id) {

        // lipando os buffs do PHP
        if (ob_get_contents()){
            ob_end_clean();
        }
        $fields = OrderFieldTablePrice::all();
        $rules = OrderTablePriceRules::get();
        $table = SalesmanTablePrice::with('salesman', 'client')
            ->where('id', $id)
            ->where('salesman_id', $request->session()->get('salesman_data')->id)
            ->first();

        if (!$table) {

            return redirect()->back()->with('error', 'Não foi possível encontrar a tabela que deseja exportar.');
        }

        $applyPrice = new ApplyConditionPriceBase($fields, $rules, $table);

        $months = $this->setSessionDatesAvaibles();
        $products = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $pattern_second = [
            'view' => 'gree_commercial.exports.table_price_sheet_products',
            'sheet_title' => 'Proposta cliente',
            'months' => $months,
            'products' => $products,
            'table' => commercialTablePriceConvertValue($table),
            'applyPrice' => $applyPrice,
        ];

        $pattern = [
            'view' => 'gree_commercial.exports.table_price',
            'sheet_title' => 'Tabela de preço Gree',
            'months' => $months,
            'products' => $products,
            'table' => commercialTablePriceConvertValue($table),
            'applyPrice' => $applyPrice,
            'sheets' => [
                $pattern_second
            ]
        ];

        return Excel::download(new DefaultHtmlExport($pattern), 'CommercialTablePriceExport-'. date('Y-m-d') .'.xlsx');
    }

    private function programationEditFormatTable($programation, $json_prog) {

        $fields = collect(json_decode($programation->json_fields));
        $rules = collect(json_decode($programation->json_rules));

        $months = collect(json_decode($programation->json_months));

        $tables = collect(json_decode($programation->programationMonth()->get()));

        $products = collect(json_decode($programation->json_categories_products));

        $yearmonth = [];
        $response = [];
        foreach ($months as $d) {
            if (isset($json_prog[date('Y-m', strtotime($d->date))])) {
                $json = $json_prog[date('Y-m', strtotime($d->date))];
                foreach ($products as $key) {
                    $item = array();
                    $item['id'] = $key->id;
                    $item['is_hlcap'] = $key->is_conf_cap;
                    $arr_product = [];
                    foreach ($key->set_product_on_group as $set) {

                        $qtd = 0;
                        // Pegar a quantidade.
                        foreach ($json['category'] as $cat) {
                            if ($cat['id'] == $key->id) {
                                $prods = $cat['products'];
                                foreach ($prods as $prd) {
                                    if ($prd['id'] == $set->id) {
                                        $qtd = $prd['qtd'];
                                    }
                                }
                            }
                        }

                        $table = $tables->where('yearmonth', date('Y-m-01 00:00:00', strtotime($d->date)))->first();
                        $table = json_decode($table->json_table_price);
                        $applyPrice = new ApplyConditionPriceBase($fields, $rules, $table);

                        $prod = array();
                        $prod['id'] = $set->id;
                        $prod['qtd'] = $qtd;
                        $prod['price'] = $applyPrice->calcPrice($set->price_base, $set, $d->date);
                        $prod['hlcap'] = $set->capacity;

                        array_push($arr_product, $prod);
                    }

                    $item['products'] = $arr_product;

                    array_push($response, $item);
                }

                $yearmonth[date('Y-m', strtotime($d->date))] = [
                    'category' => $response,
                    'table' => $json['table'],
                    'contract_vpc' => number_format($json['contract_vpc'], 2, '.', ''),
                    'average_term' => $json['average_term'],
                    'cif_fob' => $json['cif_fob'],
                    'table_is_change' => 0,
                ];
                $response = [];
            }

        }

        return $yearmonth;

    }

    public function programationChangerTablePrice(Request $request) {

        $fields = OrderFieldTablePrice::all();
        $rules = OrderTablePriceRules::get();
        $table = SalesmanTablePrice::with('salesman', 'client')
            ->where('id', $request->id)
            ->where('salesman_id', $request->session()->get('salesman_data')->id)
            ->first();

        if (!$table) {

            return response()->json([
                'success' => false,
                'msg' => 'Não foi possível encontrar a tabela que deseja.'
            ], 400);
        }

        $applyPrice = new ApplyConditionPriceBase($fields, $rules, $table);

        $months = $this->setSessionDatesAvaibles();
        $products = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $response = [];
        foreach ($months as $d) {
            if (date('Y-m', strtotime($d->date)) == $request->date) {
                foreach ($products as $key) {
                    $item = array();
                    $item['id'] = $key->id;
                    $item['is_hlcap'] = $key->is_conf_cap;
                    $arr_product = [];
                    foreach ($key->setProductOnGroup as $set) {

                        $prod = array();
                        $prod['id'] = $set->id;
                        $prod['qtd'] = 0;
                        $prod['price'] = $applyPrice->calcPrice($set->price_base, $set, $d->date);
                        $prod['hlcap'] = $set->capacity;

                        array_push($arr_product, $prod);
                    }

                    $item['products'] = $arr_product;

                    array_push($response, $item);
                }
            }
        }

        if (count($response) != 0) {
            return response()->json([
                'success' => true,
                'code' => $table->code,
                'table_id' => $table->id,
                'contract_vpc' => number_format($table->contract_vpc, 2, '.', ''),
                'average_term' => round($table->average_term, 0),
                'cif_fob' => $table->cif_fob,
				'cif_fob_name' => $table->cif_fob_name,
                'result' => $response
            ], 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'Não há resultados para essa data em especifico.'
            ], 400);
        }
    }

    public function programationSaveNew(Request $request) {

		// Verificar se o cliente pode criar programação.
        $client = Client::with('client_managers.salesman')->find($request->client_id);

        if (!$client)
            return redirect()->back()->with('error', 'Não foi possível criar a programação, pois o cliente não existe ou não pertence a você.');

        if ($client->financy_status == 1)
            return redirect()->back()->with('error', 'Não foi possível criar a programação, pois o cliente encontra-se reprovado pelo financeiro.');
		
        $fields = OrderFieldTablePrice::all();
        $rules = OrderTablePriceRules::get();

        $months = $this->setSessionDatesAvaibles();
        $categories = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }, 'setProductOnGroup.productAirEvap'])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $programation = json_decode($request->json_programation, true);
        $response = [];
        $arr_cat = array();
        // Construção da arvore de preços e quantidades com base nos meses disponíveis.
        foreach ($months as $d) {
            if (isset($programation[date('Y-m', strtotime($d->date))])) {
                $yearmonth = $programation[date('Y-m', strtotime($d->date))];
                if ($yearmonth['table'] != 0) {
                    $table = SalesmanTablePrice::where('id', $yearmonth['table'])
                        ->where('salesman_id', $request->session()->get('salesman_data')->id)
                        ->first();

                    if ($table) {
                        $applyPrice = new ApplyConditionPriceBase($fields, $rules, $table);

                        $json_categories = $yearmonth['category'];
                        foreach ($categories as $key) {
                            foreach ($json_categories as $val) {
                                if ($val['id'] == $key->id) {
                                    $total_cat = 0;
                                    $item = array();
                                    $item['id'] = $key->id;
                                    $item['is_hlcap'] = $key->is_conf_cap;
                                    $arr_product = [];
                                    $json_products = $val['products'];
                                    foreach ($key->setProductOnGroup as $set) {
                                        foreach($json_products as $valp) {
                                            if ($valp['id'] == $set->id) {
                                                //if ($valp['qtd'] > 0) {
                                                    $total_cat = $total_cat + $valp['qtd'];
                                                    $prod = array();
                                                    $prod['id'] = $set->id;
                                                    $prod['qtd'] = $valp['qtd'];
                                                    $prod['price'] = $applyPrice->calcPrice($set->price_base, $set, $d->date);
                                                    $prod['hlcap'] = $set->capacity;

                                                    array_push($arr_product, $prod);
                                                //}
                                            }
                                        }
                                    }

                                    //if ($total_cat > 0) {
                                        $item['products'] = $arr_product;
                                        array_push($arr_cat, $item);
                                    //}
                                }
                            }
                        }

                        if (count($arr_cat) > 0) {
                            $response[date('Y-m', strtotime($d->date))] = [
                                'category' => $arr_cat,
                                'table' => $table->id,
                                'contract_vpc' => number_format($table->contract_vpc, 2, '.', ''),
                                'average_term' => round($table->average_term, 0),
                                'cif_fob' => $table->cif_fob,
                                'table_is_change' => 0,
                            ];
                        }
                        $arr_cat = array();
                    }
                }
            }
        }

        if (count($response) == 0)
            return redirect()->back()->with('error', 'Você não pode criar uma programação vazia.');

        // Salvar no banco de dados.
        DB::beginTransaction();
        $new_programation = new \App\Model\Commercial\Programation;
        $new_programation->code = getCodeModule('programation_commercial', '', 1);
        $new_programation->client_id = $request->client_id;
        $new_programation->request_salesman_id = \Session::get('salesman_data')->id;
        $new_programation->manager_id = $client->client_managers[0]->salesman_id;
        $new_programation->has_analyze = 1;
        $new_programation->description = $request->programation_desc;
        $new_programation->json_months = $months->toJson();
        $new_programation->json_categories_products = $categories->toJson();
        $new_programation->json_fields = $fields->toJson();
        $new_programation->json_rules = $rules->toJson();
        $new_programation->json_client = $client->toJson();
        $new_programation->save();

        foreach ($months as $d) {
            if (isset($response[date('Y-m', strtotime($d->date))])) {
                $yearmonth = $response[date('Y-m', strtotime($d->date))];
                $new_programation_month = new \App\Model\Commercial\ProgramationMonth;
                $new_programation_month->programation_id = $new_programation->id;
                $new_programation_month->yearmonth = date('Y-m-01 00:00:00', strtotime($d->date));
				$new_programation_month->adjust_month = OrderAvaibleMonth::where('date', date('Y-m-01 00:00:00', strtotime($d->date)))->get()->toJson();
                $new_programation_month->json_qtd_prices = json_encode($yearmonth['category']);
                $new_programation_month->json_table_price = SalesmanTablePrice::where('id', $yearmonth['table'])->where('salesman_id', $request->session()->get('salesman_data')->id)->first()->toJson();
                $new_programation_month->save();
            }
        }


        $new_programation_version = new \App\Model\Commercial\ProgramationVersion;
        $new_programation_version->json_programation = json_encode($response);
        $new_programation_version->programation_id = $new_programation->id;
        $new_programation_version->version = 1;
        $new_programation_version->save();

        DB::commit();

        $programation = Programation::with('client.client_managers.salesman')
            ->where('id', $new_programation->id)
            ->first();
        $manager = $programation->client->client_managers[0]->salesman;
		
        // Enviar email para o gestor
        $pattern = array(
            'title' => 'NOVA PROGRAMAÇÃO: '. $programation->code,
            'description' => nl2br("Olá! ". $manager->first_name ." ". $manager->last_name .",
            <br><p><b>Client:</b> ". $programation->client->company_name ."
			<br><b>Representante:</b> ". $request->session()->get('salesman_data')->full_name ."
			<br><b>Programado para:</b> ". $programation->months ." </p>
            <br>Foi realizado uma atualização nessa programação, será necessário você entrar no sistema para realizar análise.
            <br><a href='". $request->root() ."/comercial/operacao'>". $request->root() ."/comercial/operacao</a>"),
            'template' => 'misc.DefaultExternal',
            'subject' => 'GREE: Análise da nova programação: '. $programation->code,
        );
		
		SendMailJob::dispatch($pattern, $manager->email);

        return redirect('/comercial/operacao/programation/all')->with('success', 'Sua programação foi criada com sucesso!');


    }

    //lembrete :criar comparativo para listar listar somente os dados do usuario da sessão
    public function viewProfile(Request $request) {

        $salesman_session = $request->session()->get('salesman_data');

        $user = \App\Model\Commercial\Salesman::find($salesman_session->id);
        if ($user) {

            if(\Str::endsWith($request->path(), 'meu_perfil')){

                return view('gree_commercial_external.profile.profile_edit', [
                    'model' => $user,
                ]);
            }
            if(\Str::endsWith($request->path(), 'configuracoes')){
                $user->load('immediate_boss','subordinates');

                return view('gree_commercial_external.profile.account_settings', [
                    'model' => $user,
                ]);
            }
            if(\Str::endsWith($request->path(), 'equipe')){

                return view('gree_commercial_external.profile.team', [
                    'model' => $user,
                ]);
            }

        } else {
            return Redirect('/comercial/operacao/tabela/preco');
        }

    }

    public function clientList(Request $request) {

        $client = Client::with('client_group')
            ->orderBy('id', 'DESC');

		$client->ShowOnlyManager($request->session()->get('salesman_data')->id);

        $array_input = collect([
            'code',
            'name',
            'identity',
            'status',
            'is_analyze'
        ]);

        $array_input = putSession($request, $array_input, 'client_');
        $filter_session = getSessionFilters('client_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code"){
                    $client->where('code', $value_filter);
                }
                if($name_filter == $filter_session[1]."is_analyze"){
                    $client->where('has_analyze', 1);
                }
                if($name_filter == $filter_session[1]."name"){
                    $client->where('company_name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."identity"){
                    $client->where('identity', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status"){
						
					if ($value_filter == 1) {
                        $client->where('is_active', 1);
                    } elseif ($value_filter == 2) {
                        $client->where('is_active', 0);
                    } elseif ($value_filter == 3) {
                        $client->where('salesman_imdt_reprov', 1)
                               ->orWhere('revision_is_reprov', 1)
                               ->orWhere('judicial_is_reprov', 1)
                               ->orWhere('commercial_is_reprov', 1)
                               ->orWhere('financy_reprov', 1);
                    } elseif ($value_filter == 4 || $value_filter == 5 || $value_filter == 6) {
                        $client->where('salesman_imdt_approv', 1)
                               ->where('revision_is_approv', 1)
                               ->where('judicial_is_approv', 1)
                               ->where('commercial_is_approv', 1)
                               ->where('financy_approv', 1);

                        if($value_filter == 4)
                            $client->where('financy_status', 1);
                        elseif($value_filter == 5)    
                            $client->where('financy_status', 2);
                        elseif($value_filter == 6)        
                            $client->where('financy_status', 3);
                    }    
                    elseif($value_filter == 7 || $value_filter == 8 || $value_filter == 9) {

                        $client->where('has_analyze', 0);

                        if($value_filter == 7)
                            $client->where('financy_status', 1);
                        elseif($value_filter == 8)    
                            $client->where('financy_status', 2);
                        elseif($value_filter == 9)        
                            $client->where('financy_status', 3);
                    }
                    elseif($value_filter == 10) {
                        $client->where('has_analyze', 1);
                    }
                    
                }
            }
        }
			
		if ($request->export == 1) {
            ob_end_clean();
            return Excel::download(new ClientsExport($request, 1), 'ClientsExport'. date('Y-m-d H.s') .'.xlsx');
        }
		
        return view('gree_commercial_external.client.client_list', [
            'client' => $client->paginate(10)
        ]);
    }

    public function clientEdit(Request $request, $id) {

        if ($id == 0) {
            return view('gree_commercial_external.client.client_new', [
                'id' => $id
            ]);

        } else {

            $client = new ManagerClient($request, $id);
            $model = $client->mng_model;

            $arr_product_sale = $model->client_on_product_sales->isNotEmpty() ? $model->client_on_product_sales: [];
            $arr_account_client = $model->client_account_bank->isNotEmpty() ? $model->client_account_bank : [];
            $arr_supplier_client = $model->client_main_suppliers->isNotEmpty() ? $model->client_main_suppliers : [];
            $arr_main_client = $model->client_main_clients->isNotEmpty() ? $model->client_main_clients : [];
            $arr_owner_partner = $model->client_owner_and_partner->isNotEmpty() ? $model->client_owner_and_partner: [];
            $arr_contact_client = $model->client_peoples_contact->isNotEmpty() ? $model->client_peoples_contact: [];

            $contact_purchase = count($arr_contact_client) > 0 ? $arr_contact_client->where('type_contact', 1)->first() : null;
            $contact_financial = count($arr_contact_client) > 0 ? $arr_contact_client->where('type_contact', 2)->first() : null;
            $contact_logistics = count($arr_contact_client) > 0 ? $arr_contact_client->where('type_contact', 3)->first() : null;

            $arr_documents_client = $model->client_documents ? $model->client_documents: null;

            $arr_contract_social = null;
            if($arr_documents_client != null) {
                $arr_contract_social = $arr_documents_client->contractSocial->count() > 0  ? $arr_documents_client->contractSocial : null;
            }

            $arr_balance_equity = null;
            if($arr_documents_client != null) {
                $arr_balance_equity = $arr_documents_client->balanceEquity->count() > 0  ? $arr_documents_client->balanceEquity : null;
            }
			
			$arr_balance_equity_2_year = null;
            if($arr_documents_client != null) {
                $arr_balance_equity_2_year = $arr_documents_client->balanceEquity2Year->count() > 0  ? $arr_documents_client->balanceEquity2Year : null;
            }

            $arr_balance_equity_3_year = null;
            if($arr_documents_client != null) {
                $arr_balance_equity_3_year = $arr_documents_client->balanceEquity3Year->count() > 0  ? $arr_documents_client->balanceEquity3Year : null;
            }

            $arr_documents = [];
            if($arr_documents_client != null) {
                $arr_documents = array(
                    array(
                        'apresentation_commercial' => $arr_documents_client ? $arr_documents_client->apresentation_commercial : '',
                        'balance_equity_dre_flow' =>  $arr_balance_equity ? $arr_balance_equity->last()->url : '',
						'balance_equity_dre_flow_2_year' =>  $arr_balance_equity_2_year ? $arr_balance_equity_2_year->last()->url : '',
                        'balance_equity_dre_flow_3_year' =>  $arr_balance_equity_3_year ? $arr_balance_equity_3_year->last()->url : '',
						
                        'card_cnpj' => $arr_documents_client ? $arr_documents_client->card_cnpj : '',
                        'card_ie' => $arr_documents_client ? $arr_documents_client->card_ie : '',
                        'contract_social' =>  $arr_contract_social ? $arr_contract_social->last()->url : '',
                        'declaration_regime'=> $arr_documents_client ? $arr_documents_client->declaration_regime : '',
                        'proxy_representation_legal' => $arr_documents_client ? $arr_documents_client->proxy_representation_legal : '',
						
						'certificate_debt_negative_federal' => $arr_documents_client ? $arr_documents_client->certificate_debt_negative_federal : '',
                        'certificate_debt_negative_sefaz' => $arr_documents_client ? $arr_documents_client->certificate_debt_negative_sefaz : '',
                        'certificate_debt_negative_labor' => $arr_documents_client ? $arr_documents_client->certificate_debt_negative_labor : ''
                    )
                );
            }

            return view('gree_commercial_external.client.client_edit', [
                'client' => $client->mng_model,
                'arr_product_sale' => $arr_product_sale,
                'arr_account_client' => $arr_account_client,
                'arr_supplier_client' => $arr_supplier_client,
                'arr_main_client' => $arr_main_client,
                'arr_owner_partner' => $arr_owner_partner,
                'arr_contact_client' => $arr_contact_client,
                'contact_purchase' => $contact_purchase,
                'contact_financial' => $contact_financial,
                'contact_logistics' => $contact_logistics,
                'arr_documents_client' => $arr_documents_client,
                'arr_contract_social' => $arr_contract_social,
                'arr_balance_equity' => $arr_balance_equity,
				'arr_balance_equity_2_year' => $arr_balance_equity_2_year,
                'arr_balance_equity_3_year' => $arr_balance_equity_3_year,
                'arr_documents' => json_encode($arr_documents),
                'id' => $id
            ]);
        }
    }

    public function clientEdit_do(FormRequestSaveClient $request) {

        $client = new ManagerClient($request, $request->id);

        try {

            if ($client->mng_model != null)
                if ($client->mng_model->has_analyze == 1)
                    return redirect()->back()->with('error', 'Não é possível realizar alteração de um cadastro em análise.');

            $client->editClient();

            $request->session()->put('success', 'Cliente salvo com sucesso!');
            return redirect('/comercial/operacao/cliente/todos');

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back()->withInput();

        }
    }

    public function clientEditAnalyze(Request $request) {

        try {

            if ($request->id == 0) {
                $client = new ManagerClient($request, $request->id);
                $request->merge(['id' => $client->editClient()]);


                $settings = Settings::where('command', 'client_new_register')->first();

                if ($settings->value) {
                    $arr = explode(',', $settings->value);

                    foreach ($arr as $key) {

                        $pattern = array(
                            'title' => 'COMERCIAL - NOVO CLIENTE REGISTRADO',
                            'description' => nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre o novo cadastro.
                            <p>Cliente: ". $request->company_name ."<br>
                               Identidade: ". $request->identity ."<br>
							   Representante: ". $request->session()->get('salesman_data')->full_name ."<br>
                               URL: <a href='". $request->root() ."/commercial/client/list'>". $request->root() ."/commercial/client/list</a>
                            </p>"),
                            'template' => 'misc.DefaultExternal',
                            'subject' => 'Comercial - Novo cliente registrado',
                        );

                        SendMailJob::dispatch($pattern, $key);
                    }
                }

            } else {

                $settings = Settings::where('command', 'client_update_register')->first();

				
                if ($settings->value) {
                    $arr = explode(',', $settings->value);

                    foreach ($arr as $key) {

                        $pattern = array(
                            'title' => 'COMERCIAL - CLIENTE ATUALIZADO',
                            'description' => nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Cliente: ". $request->company_name ."<br>
                               Identidade: ". $request->identity ."<br>
							   Representante: ". $request->session()->get('salesman_data')->full_name ."<br>
                               URL: <a href='". $request->root() ."/commercial/client/list'>". $request->root() ."/commercial/client/list</a>
                            </p>"),
                            'template' => 'misc.DefaultExternal',
                            'subject' => 'Comercial - Cliente atualizado',
                        );

                        SendMailJob::dispatch($pattern, $key);
                    }
                }
            }

            $analyze_client =  new AnalyzeProcessClient($request);
            $analyze_client->startAnalyze();

            return redirect('/comercial/operacao/cliente/todos')->with('success', 'Dados do cliente enviados para análise!');

        } catch (\Exception $e) {
            $request->session()->put('error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function clientAnalyzeList(Request $request) {

        $analyze = Client::with(['client_group', 'client_version' => function ($q) {
            $q->orderBy('id', 'DESC')->withTrashed();

        }])->where('has_analyze', 1)
            ->where('salesman_imdt_approv', 0)
            ->where('salesman_imdt_reprov', 0)
            ->ValidProcessImdt($request)
            ->orderBy('id', 'DESC');

        $array_input = collect([
            'status',
            'name',
            'identity',
            'code'
        ]);

        $array_input = putSession($request, $array_input, 'client_');
        $filter_session = getSessionFilters('client_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."status"){
                    $analyze->ClientVersionStatus($value_filter);
                }
                if($name_filter == $filter_session[1]."code"){
                    $analyze->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."fantasy_name"){
                    $analyze->where('fantasy_name', $value_filter);
                }
                if($name_filter == $filter_session[1]."cnpj_rg"){
                    $analyze->where('identity', $value_filter);
                }
                if($name_filter == $filter_session[1]."group"){
                    $analyze->GroupFilter($value_filter);
                }
            }
        }

        return view('gree_commercial_external.client.listAnalyze', [
            'analyze' => $analyze->paginate(10),
        ]);
    }


    public function clientAnalyze_do(Request $request) {

        $user = Salesman::find($request->session()->get('salesman_data')->id);

        if (Hash::check($request->password, $user->password)) {

            try {
                $AnalyzeProcessClient = new AnalyzeProcessClient($request);
                $AnalyzeProcessClient->doAnalyze($request->type_analyze, 2, $request->description);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            return redirect('/comercial/operacao/cliente/todos/analise')->with('success', 'Análise realizada com sucesso!');
        } else {

            if ($user->retry > 0) {

                $user->retry = $user->retry - 1;
                $user->save();

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    // Write Log
                    LogSystem("Representante errou sua senha secreta para aprovar (Cliente do comercial) muitas vezes e foi bloqueado no sistema.", $user->id);
                    return redirect('/comercial/operacao/logout')->with('error', "You have often erred in your secret password and been blocked, talk to administration.");
                } else {

                    // Write Log
                    LogSystem("Representante errou sua senha secreta para aprovar (Cliente do comercial). Restou apenas ". $user->retry ." tentativa(s).", $user->id);
                    return redirect()->back()->with('error', 'You missed your secret password, only '. $user->retry .' attempt(s) left.');
                }
            } else {

                // Write Log
                LogSystem("Representante está tentando aprovar (Cliente do comercial) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->id);
                return redirect()->back();
            }
        }
    }

    public function clientPrintVersionView(Request $request, $id, $ver) {

        $version = ClientVersion::where('client_id', $id)->where('version', $ver)->orderBy('id', 'DESC')->withTrashed()->first();
        if ($version) {

            return $version->view;
        } else {
            return 'Não foi possível encontrar a versão...';
        }
    }

    public function clientAnalyzeHistoryApprov(Request $request) {

        $version = ClientVersion::with([
            'ClientImdtAnalyze' => function($q) use($request) {
                $q->where('version', $request->version_hist);
            },
            'ClientImdtAnalyze.salesman',
			'ClientRevisionAnalyze' => function($q) use($request) {
                $q->where('version', $request->version_hist);
            },
			'ClientRevisionAnalyze.user',
			'ClientJudicialAnalyze' => function($q) use($request) {
                $q->where('version', $request->version_hist);
            },
			'ClientJudicialAnalyze.user',
            'ClientCommercialAnalyze' => function($q) use($request) {
                $q->where('version', $request->version_hist);
            },
            'ClientCommercialAnalyze.user',
            'ClientFinancyAnalyze' => function($q) use($request) {
                $q->where('version', $request->version_hist);
            },
            'ClientFinancyAnalyze.user'

        ])->where('client_id', $request->id)
            ->where('version', $request->version_hist)->withTrashed()->first();

        if ($version) {
            $data = [
                'imdt' => $version->ClientImdtAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientImdtAnalyze)) : [],
				'revision' => $version->ClientRevisionAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientRevisionAnalyze, 2)): [],
				'judicial' => $version->ClientJudicialAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientJudicialAnalyze, 2)): [],
                'commercial' => $version->ClientCommercialAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientCommercialAnalyze, 2)): [],
                'financy' => $version->ClientFinancyAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientFinancyAnalyze, 2)) : [],
            ];
        } else {
            $data = [];
        }


        return response()->json([
            'data' => $data
        ], 200);
    }

    private function clientCreateArrayList($list, $type_user = 1) {

        $arr = [];
        if ($type_user == 1) {
            foreach ($list as $key) {
                $desc = $key->description ? $key->description : "";
                $row = array(
                    'type_user' => 'Representante',
                    'name' => $key->salesman->short_name,
                    'office' => $key->salesman->office,
                    'status' => $key->is_approv,
                    'description' => $desc,
                    'version' => $key->version,
                );

                array_push($arr, $row);
            }
        } else {
            foreach ($list as $key) {
                $desc = $key->description ? $key->description : "";
                $row = array(
                    'type_user' => 'Usuário interno',
                    'name' => $key->user->short_name,
                    'office' => $key->user->office,
                    'status' => $key->is_approv,
                    'description' => $desc,
                    'version' => $key->version,
                );

                array_push($arr, $row);
            }
        }

        return $arr;
    }

    public function clientDocumentsAjax(Request $request) {

        $client = new ManagerClient($request);

        try {

            $return = $client->uploadDocuments();

            return response()->json([
                'success' => true,
                'url' => $return
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }

    }

    public function clientDocumentDeleteAjax(Request $request) {

        $client = new ManagerClient($request);

        try {

            $client->deleteDocument();

            return response()->json([
                'success' => true,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function clientAnalyze(Request $request, $id) {

        $client = Client::with(['client_version' => function($query){
            $query->orderBy('id', 'DESC');
        }, 'salesman', 
        'client_documents.contractSocial', 
        'client_documents.balanceEquity',
        'client_documents.balanceEquity2Year',
        'client_documents.balanceEquity3Year'
        ])->where('id', $id)->first();

        if($client) {
            if ($client->client_version->count() == 0)
                return redirect('/comercial/operacao/cliente/todos/analise')->with('error', 'Cliente para realizar a análise, não foi encontrado.');

            return view('gree_commercial_external.client.analyze', [
                'id' => $id,
				'client' => $client,
                'versions' => $client->client_version,
                'documents' => $client->client_documents
            ]);
        } else {
            return redirect('/comercial/operacao/cliente/todos/analise')->with('error', 'Cliente para realizar a análise, não foi encontrado.');
        }
    }

    public function validSalesman($request, $client) {

        if ($client->salesman_imdt_approv == 0) {
            $analyzes = ClientImdtAnalyze::with('salesman')
                ->where('client_id', $client->id)
                ->where('version', $client->client_version->first()->version)
                ->orderBy('id', 'DESC')
                ->first();

            if ($analyzes) {

                $validNextAnalyze = $analyzes->salesman->immediate_boss->where('is_active', 1)
                    ->where('is_direction', 0)
                    ->where('id', $request->session()->get('salesman_data')->id)
                    ->first();

                if ($validNextAnalyze) {
                    return true;
                } else {
                    $validNextAnalyze = $analyzes->salesman->immediate_boss->where('is_active', 1)
                        ->where('is_direction', 2)
                        ->where('id', $request->session()->get('salesman_data')->id)
                        ->first();

                    if ($validNextAnalyze)
                        return true;
                    else
                        return false;
                }

            } else {

                $validNextAnalyze = $client->salesman->immediate_boss
                    ->where('id', $request->session()->get('salesman_data')->id)
                    ->first();

                if ($validNextAnalyze)
                    return true;
                else
                    return false;

            }
        } else {
            return false;
        }
    }

    public function clientPrintView(Request $request, $id) {

        $client = new ManagerClient(null, $id);

        if ($client->mng_model) {

            if ($client->mng_model->has_analyze == 1) {
                $version = ClientVersion::where('client_id', $id)->orderBy('id', 'DESC')->get();
                if (count($version) > 0) {

                    if ($client->mng_model->has_analyze == 1 and $version->count() > 1)
                        return $version->splice(1)->first()->view;
                    else
                        return $version->first()->view;
                }
            }

            $header = DB::connection('commercial')
                ->table('settings')
                ->where('type', 1)
                ->get();

        } else {

            return 'Não é possível visualizar a folha de impressão! Cliente não existe.';
            //return  redirect()->back()->with('error', 'Não é possível visualizar a folha de impressão! Cliente não existe.');
        }

        return view('gree_commercial_external.client.print', [
            'header' => $header,
            'client' => $client->mng_model,
        ]);
    }

    public function clientPrintAnalyze(Request $request, $id) {

        $client = new ManagerClient(null, $id);

        if ($client->mng_model) {

            $header = DB::connection('commercial')
                ->table('settings')
                ->where('type', 1)
                ->get();

        } else {

            return 'Não é possível visualizar a folha de impressão! Cliente não existe.';
            //return  redirect()->back()->with('error', 'Não é possível visualizar a folha de impressão! Cliente não existe.');
        }

        $version = DB::connection('commercial')->table('client_version')->where('client_id', $id)->orderBy('id', 'DESC')->get();

        if ($version->count() == 1) {

            return $version->first()->view;
        }

        $json = $version->first()->inputs;

        if ($version->first()->version == 2 or $version->first()->version == 1) {
            $data = Client::with(
                ['client_peoples_contact' => function($q) {
                    $q->withTrashed();
                },
                    'client_account_bank' => function($q) {
                        $q->withTrashed();
                    },
                    'client_main_clients' => function($q) {
                        $q->withTrashed();
                    },
                    'client_main_suppliers' => function($q) {
                        $q->withTrashed();
                    },
                    'client_on_group' => function($q) {
                        $q->withTrashed();
                    },
                    'client_on_product_sales' => function($q) {
                        $q->withTrashed();
                    },
                    'client_owner_and_partner' => function($q) {
                        $q->withTrashed();
                    },
                    'client_group',
                    'salesman',
                    'client_documents']
            )->where('id', $id)->first()->toJson();
        } else {
            $data = $version->splice(1)->first()->inputs;
        }

        // Diferença da tabela cliente
        $arr_client_diff = array_diff_assoc($this->splitArray($json, 1), $this->splitArray($data, 1));

        // Diferença das relações
        $arr_relation_diff = [];
        $path = json_decode($data, true);

        foreach ($this->splitArray($json, 2) as $key => $value) {

            foreach ($value as $index => $cvalue) {
                if ($path[$key][$index] == 1)
                    $isHasOne = true;
                else
                    $isHasOne = false;

                if (!$isHasOne)
                    $diff = array_diff_assoc($this->splitArray(json_encode($cvalue), 1), $this->splitArray(json_encode($path[$key][$index]), 1));
                else
                    $diff = array_diff_assoc($this->splitArray(json_encode($value), 1), $this->splitArray(json_encode($path[$key]), 1));

                if (count($diff) > 0)
                    if (!in_array($key, $arr_relation_diff))
                        $arr_relation_diff[] = $key;

            }
        }

        $client = collect(json_decode($json, true));
        $header = collect(json_decode($header, true));

        return view('gree_commercial.client.printApprov', [
            'header' => $header,
            'client' => $client,
            'arr_client_diff' => $arr_client_diff,
            'arr_relation_diff' => $arr_relation_diff
        ]);
    }

    public function programationNew(Request $request) {

        $salesman_table_price = SalesmanTablePrice::where('salesman_id', $request->session()->get('salesman_data')->id)
            ->where('is_programmed', 1)
            ->orderBy('id', 'DESC')->get();

        $alert = App\Model\Commercial\Settings::where('command', 'programation_alert')->first();
        $months = $this->setSessionDatesAvaibles();

        $category = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $programation = array();
        $arr_cat = array();
        foreach ($months as $d) {
            foreach ($category as $key) {
                $item = array();
                $item['id'] = $key->id;
                $item['is_hlcap'] = $key->is_conf_cap;
                $arr_product = [];
                foreach ($key->setProductOnGroup as $set) {

                    $prod = array();
                    $prod['id'] = $set->id;
                    $prod['qtd'] = 0;
                    $prod['price'] = 0;
                    $prod['hlcap'] = $set->capacity;

                    array_push($arr_product, $prod);
                }

                $item['products'] = $arr_product;

                array_push($arr_cat, $item);
            }
            $programation[date('Y-m', strtotime($d->date))] = [
                'category' => $arr_cat,
                'table' => 0,
                'contract_vpc' => 0,
                'average_term' => 0,
                'cif_fob' => 0,
                'table_is_change' => 0,
            ];
            $arr_cat = array();
        }

        $clients = Client::with(['client_managers.salesman', 'client_group' => function($q) {
			$q->groupBy('id');	
		}])
            ->whereHas('client_managers')
			->whereHas('client_group')
            ->where('is_active', 1)
			->where('has_analyze', 0)
            ->where(function ($q) {
                $q->where('financy_approv', 1)
                    ->orWhere(function ($q1) {
                        $q1->where('salesman_imdt_approv', 0)
                        ->where('salesman_imdt_reprov', 0)
                        ->where('commercial_is_approv', 0)
                        ->where('commercial_is_reprov', 0)
                        ->where('financy_reprov', 0)
                        ->where('financy_approv', 0);
                    });
            })
            ->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->orderBy('id', 'DESC')
            ->get();

        if ($clients->count() == 0)
            return redirect()->back()->with('error', 'Você tem que ter ao menos 1 cliente aprovado e ativo.');


        $settings = Settings::where('command', 'version_table_price')->first();
        return view('gree_commercial_external.programation.new', [
            'clients' => $clients,
            'salesman_table_price' => $salesman_table_price,
            'version' => $settings->value,
            'months' => $months,
            'category' => $category,
            'programation' => $programation,
            'alert' => $alert->value
        ]);
    }

    public function programationList(Request $request) {

        $array_input = collect([
            'code',
            'manager',
            'client',
            'start_date',
            'end_date',
            'is_open',
            'is_analyze',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();
		
		$clients = Client::ShowOnlyManager($request->session()->get('salesman_data')->id)->where('is_active', 1)->get();

        $managers = DB::connection('commercial')
            ->table('salesman')->join('salesman_on_state', 'salesman_on_state.salesman_id', '=', 'salesman.id')
            ->select('salesman.*')
            ->whereExists(function($q) {
                $q->select(DB::raw(1))
                    ->from('client')
                    ->whereRaw('client.state = salesman_on_state.state');
            })->groupBy('salesman_on_state.salesman_id')
            ->get();
	
		$programations = Programation::with('client.client_managers.salesman', 'programationVersion', 'programationMonth.orderSales.salesman');
		
		$programations->ShowOnlyManager(\Session::get('salesman_data')->id);

        $programations->orderBy('id', 'DESC');

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code"){
                    $programations->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $programations->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $programations->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."is_open"){
                    $programations->whereHas('programationMacro', function ($q) {
                        $q->where('quantity', '>', 0);
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."is_analyze"){
                    $programations->where('has_analyze', 1);
                }

            }
        }

        return view('gree_commercial_external.programation.list', [
            'programations' => $programations->paginate(10),
            'managers' => $managers,
            'clients' => $clients,
        ]);
    }
	
	public function programationMacroClientsAjax(Request $request) {

        $id_salesman = $request->session()->get('salesman_data')->id;
        $is_direction = $request->session()->get('salesman_data')->is_direction;
        
        try {

            if((int)$request->year && (int)$request->month) {

                $query = "SELECT client.company_name, client.identity, client.code, SUM(programation_macro.total) as total, SUM(programation_macro.quantity) as quantity
				FROM `client` 
					INNER JOIN programation ON programation.client_id = client.id AND programation.is_cancelled = 0
					INNER JOIN programation_macro ON programation.id = programation_macro.programation_id
					WHERE financy_status = 3
					AND YEAR(programation_macro.yearmonth) = $request->year 
					AND MONTH(programation_macro.yearmonth) = $request->month   
					AND EXISTS (
						SELECT 1 FROM programation 
						WHERE programation.client_id = client.id
						AND EXISTS (SELECT 1 FROM programation_macro 
									WHERE programation_macro.programation_id = programation.id)
					)";
					

                    if($request->salesman_id != 0) {           
                        
                        $query .= " AND client.request_salesman_id = $request->salesman_id";

                    } 
                    if($request->salesman_id == 0) {

                        if($is_direction == 2 || $is_direction == 3) {
                            $query .= " AND EXISTS (SELECT 1
                                            FROM   client_managers
                                            WHERE  client_managers.salesman_id = $id_salesman
                                                AND programation.client_id = client_managers.client_id)";
                        } elseif($is_direction == 0) {
                            $query .= " AND client.request_salesman_id = $id_salesman";
                        }
                    }    

                $query .= " AND Year(programation_macro.yearmonth) = $request->year
                           AND Month(programation_macro.yearmonth) = $request->month
                           GROUP BY client.identity";
			

                $macro_clients = DB::connection('commercial')->select(DB::raw($query));

                return response()->json([
                    'success' => true,
                    'macro_clients' => $macro_clients
                ], 200);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Mês / Ano não foram passados corretamente'
                ], 400);
            }
        
        } catch (\Exception $e) {    

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }    
    }

    public function programationView(Request $request, $id) {
	
		$programation = Programation::with('client.client_managers.salesman', 'programationVersionAll', 'programationVersion')
            ->where('id', $id)
            ->first();
		
		// Fábio
		if ($programation->request_salesman_id != \Session::get('salesman_data')->id and $request->session()->get('salesman_data')->id != 10) {
			$programation = Programation::with('client.manager_region', 'programationVersionAll', 'programationVersion')
                ->whereHas('client', function ($sb1) use ($request) {
                    $sb1->ShowOnlyManager(\Session::get('salesman_data')->id);
                }) 
				->where('id', $id)
				->first();
		} else if ($programation->request_salesman_id != \Session::get('salesman_data')->id and $request->order_id) {
			$order = OrderSales::with(
				'programationMonth.programation.client',
				'orderSalesAttach',
				'programationMonth.programation.salesman',
				'orderImdAnalyze',
				'orderCommercialAnalyze',
				'orderFinancyAnalyze')
				->ValidProcessImdt($request)
				->where('has_analyze', 1)
				->where('salesman_imdt_approv', 0)
				->where('salesman_imdt_reprov', 0)
				->where('id', $request->order_id)
				->first();

			if (!$order)
				return response()->json(['success' => false,'msg' => 'Seu pedido não foi encontrado na base de dados.',]);

			$programation = $order->programationMonth->programation;
		}
		
		if (!$programation)
			return response()->json(['success' => false,'msg' => 'Você não tem permissão para visualizar essa programação.']);

        if ($request->version)
            if ($request->version > $programation->programationVersion()->first()->version)
                return redirect()->back()->with('error', 'A versão que deseja visualizar, não está disponível');

        $client = json_decode($programation->json_client);

        $alert = App\Model\Commercial\Settings::where('command', 'programation_alert')->first();
        $months = json_decode($programation->json_months, true);
        $category = collect(json_decode($programation->json_categories_products, true));
        if ($request->version) {
            $version = $programation->programationVersion($request->version)->first();
            $tables = $programation->programationMonth($request->version)->get();
        } else {
            $version = $programation->programationVersion()->first();
            $tables = $programation->programationMonth($version->version)->orderBy('id', 'DESC')->get();
        }

        $version_arr = json_decode($version->json_programation, true);

        $months_arr = [];
        foreach ($version_arr as $idx => $vsion_val) {
            $months_arr[] = $idx;
        }

        $in_analyze = 0;
		if ($request->version) {
			if ($request->version == $programation->programationVersion->version and $programation->programationVersion->is_approv == 0 and $programation->programationVersion->is_reprov == 0 and $programation->has_analyze == 1)
				if ($programation->request_salesman_id != \Session::get('salesman_data')->id)
					$in_analyze = 1;
		} else {
			if ($programation->programationVersion->is_approv == 0 and $programation->programationVersion->is_reprov == 0 and $programation->has_analyze == 1)
				if ($programation->request_salesman_id != \Session::get('salesman_data')->id)
					$in_analyze = 1;
		}


        return view('gree_commercial_external.programation.view', [
            'in_analyze' => $in_analyze,
            'client' => $client,
            'months' => $months,
            'months_arr' => $months_arr,
            'category' => $category,
            'version_arr' => $version_arr,
            'version' => $version,
            'tables' => $tables,
            'programation' => $programation,
            'alert' => $alert->value
        ]);
    }

    public function programationEdit(Request $request, $id) {

		$programation = Programation::with('client.manager_region', 'programationVersionAll')
				->ShowOnlyManager(\Session::get('salesman_data')->id)
				->where('id', $id)
				->where('is_cancelled', 0)
				->first();

        if (!$programation)
            return redirect()->back()->with('error', 'A programação que está tentando acessar, não está disponível!');

        $client = json_decode($programation->json_client);

        $alert = App\Model\Commercial\Settings::where('command', 'programation_alert')->first();
        $months = json_decode($programation->json_months, true);
        $category = collect(json_decode($programation->json_categories_products, true));
        $version = $programation->programationVersion()->first();
        $version_arr = $this->programationEditFormatTable($programation, json_decode($version->json_programation, true));
        $tables = $programation->programationMonth($version->version)->orderBy('id', 'DESC')->get();

        $months_arr = [];
        foreach ($version_arr as $idx => $vsion_val) {
            $months_arr[] = $idx;
        }

		$salesman_table_price = SalesmanTablePrice::where('salesman_id', $request->session()->get('salesman_data')->id)
			->where('is_programmed', 1)
			->orderBy('id', 'DESC')->get();

		$clients = Client::with('manager_region')
			->whereHas('manager_region')
			->where('is_active', 1)
			->where('request_salesman_id', $request->session()->get('salesman_data')->id)
			->orderBy('id', 'DESC')
			->get();
        

        $version_actual = $settings = Settings::where('command', 'version_table_price')->first();
        $limit = App\Model\Commercial\Settings::where('command', 'programation_last_day')->first();
        return view('gree_commercial_external.programation.edit', [
            'salesman_table_price' => $salesman_table_price,
            'clients' => $clients,
            'client' => $client,
            'months' => $months,
            'months_arr' => $months_arr,
            'category' => $category,
            'version_arr' => $version_arr,
            'version' => $version,
            'version_actual' => $version_actual->value,
            'limit' => $limit,
            'tables' => $tables,
            'programation' => $programation,
            'alert' => $alert->value
        ]);
    }

    public function programationSaveEdit(Request $request) {
		
        $programation = Programation::with('client.client_managers', 'programationVersionAll')
            ->where('request_salesman_id', \Session::get('salesman_data')->id)
            //->where('has_analyze', 0)
            ->where('is_cancelled', 0)
            ->where('id', $request->programation_id)
            ->first();

        if (!$programation)
            return redirect()->back()->with('error', 'A programação que está tentando atualizar, não está disponível!');


        $fields = collect(json_decode($programation->json_fields));
        $rules = collect(json_decode($programation->json_rules));
        $tables = collect(json_decode($programation->programationMonth()->get()));

        $months = $this->setSessionDatesAvaibles();

        $categories = collect(json_decode($programation->json_categories_products));

        $pro_json = json_decode($request->json_programation, true);

        $limit = App\Model\Commercial\Settings::where('command', 'programation_last_day')->first();
        $response = [];

        $arr_cat = array();
        // Construção da arvore de preços e quantidades com base nos meses disponíveis.
        foreach ($months as $d) {
            if (isset($pro_json[date('Y-m', strtotime($d->date))])) {

                $yearmonth = $pro_json[date('Y-m', strtotime($d->date))];

                if ($yearmonth['table_is_change'] == 0) {
                    $table = $tables->where('yearmonth', date('Y-m-01 00:00:00', strtotime($d->date)))->first();
                    $table = json_decode($table->json_table_price);
                } else {
                    $table = SalesmanTablePrice::where('id', $yearmonth['table'])
                        ->where('salesman_id', $request->session()->get('salesman_data')->id)
                        ->first();
                }

                $applyPrice = new ApplyConditionPriceBase($fields, $rules, $table);

                $json_categories = $yearmonth['category'];
                foreach ($categories as $key) {
                    foreach ($json_categories as $val) {
                        if ($val['id'] == $key->id) {
                            $total_cat = 0;
                            $item = array();
                            $item['id'] = $key->id;
                            $item['is_hlcap'] = $key->is_conf_cap;
                            $arr_product = [];
                            $json_products = $val['products'];
                            foreach ($key->set_product_on_group as $set) {
                                foreach($json_products as $valp) {
                                    if ($valp['id'] == $set->id) {
                                        //if ($valp['qtd'] > 0) {
                                            $total_cat = $total_cat + $valp['qtd'];
                                            $prod = array();
                                            if (date('Y-m', strtotime($d->date)) == date('Y-m') and date('d') >= $limit->value or date('Y-m', strtotime($d->date)) < date('Y-m')) {
                                                $prod['qtd'] = $valp['qtd'];
                                                $prod['price'] = $valp['price'];
                                            } else {
                                                $prod['qtd'] = $valp['qtd'];
                                                $prod['price'] = $applyPrice->calcPrice($set->price_base, $set, $d->date);
                                            }
                                            $prod['id'] = $set->id;
                                            $prod['hlcap'] = $set->capacity;

                                            array_push($arr_product, $prod);

                                       // }
                                    }
                                }
                            }

                            //if ($total_cat > 0) {
                                $item['products'] = $arr_product;
                                array_push($arr_cat, $item);
                           // }
                        }
                    }
                }

                if (count($arr_cat) > 0) {
                    $response[date('Y-m', strtotime($d->date))] = [
                        'category' => $arr_cat,
                        'table' => $table->id,
                        'contract_vpc' => number_format($table->contract_vpc, 2, '.', ''),
                        'average_term' => round($table->average_term, 0),
                        'cif_fob' => $table->cif_fob,
                        'table_is_change' => $yearmonth['table_is_change'],
                    ];
                }
                $arr_cat = array();
            }
        }

        if (count($response) == 0)
            return redirect()->back()->with('error', 'Você não pode atualizar uma programação sem nenhum dado.');

		
        // Salvar no banco de dados.
        DB::beginTransaction();
        $programation->has_analyze = 1;
		$programation->coordinator_has_analyze = 0;
		$programation->manager_has_analyze = 0;
        $programation->description = $request->programation_desc;
        $programation->save();

		$las_v_line = $programation->programationVersion()->first();
        $last_v = $las_v_line->version;
		
		if ($las_v_line->is_reprov) {
			$last_v -= 1;
			DB::connection('commercial')->table('programation_month')
				->where('programation_id', $programation->id)
				->where('version', $las_v_line->version)->delete();
			
			DB::connection('commercial')->table('programation_version')
				->where('programation_id', $programation->id)
				->where('version', $las_v_line->version)->delete();
		}

        foreach ($months as $d) {
            if (isset($response[date('Y-m', strtotime($d->date))])) {
                $yearmonth = $response[date('Y-m', strtotime($d->date))];
                $new_programation_month = new \App\Model\Commercial\ProgramationMonth;
                $new_programation_month->programation_id = $programation->id;
                $new_programation_month->version = $last_v + 1;
                $new_programation_month->yearmonth = date('Y-m-01 00:00:00', strtotime($d->date));
                $new_programation_month->json_qtd_prices = json_encode($yearmonth['category']);
                if ($yearmonth['table_is_change'] == 0){
                    $table = $tables->where('yearmonth', date('Y-m-01 00:00:00', strtotime($d->date)))->first();
                    $new_programation_month->json_table_price = $table->json_table_price;
                } else {
                    $new_programation_month->json_table_price = SalesmanTablePrice::where('id', $yearmonth['table'])->where('salesman_id', $request->session()->get('salesman_data')->id)->first()->toJson();
                }
                $new_programation_month->save();
            }
        }

        $new_programation_version = new \App\Model\Commercial\ProgramationVersion;
        $new_programation_version->json_programation = json_encode($response);
        $new_programation_version->programation_id = $programation->id;
        $new_programation_version->version = $last_v + 1;
        $new_programation_version->save();

        DB::commit();
        $manager = $programation->client->client_managers[0]->salesman_id;
		$salesman = Salesman::find($manager);
        // Enviar email para o gestor
        $pattern = array(
            'title' => 'ATUALIZAÇÃO DA PROGRAMAÇÃO: '. $programation->code,
            'description' => nl2br("Olá! ". $salesman->first_name ." ". $salesman->last_name .",
                <br><p><b>Client:</b> ". $programation->client->company_name ."
                <br><b>Programado para:</b> ". $programation->months ." </p>
                <br>Foi realizado uma atualização nessa programação, será necessário você entrar no sistema para realizar análise.
                <br><a href='". $request->root() ."/comercial/operacao'>". $request->root() ."/comercial/operacao</a>"),
            'template' => 'misc.DefaultExternal',
            'subject' => 'GREE: Análise da atualização da programação: '. $programation->code,
        );

        SendMailJob::dispatch($pattern, $salesman->email);

        return redirect('/comercial/operacao/programation/all')->with('success', 'Sua programação foi criada com sucesso!');


    }

    public function programationApprovList(Request $request) {

        $array_input = collect([
            'code',
            'manager',
            'client',
            'start_date',
            'end_date',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();
        $clients = DB::connection('commercial')
            ->table('client')
            ->join('salesman_on_state', 'salesman_on_state.state', '=', 'client.state')
            ->select('client.*')
            ->where('salesman_on_state.salesman_id', \Session::get('salesman_data')->id)
            ->where('is_active', 1)
            ->get();

        $programations = Programation::with('client.manager_region', 'programationVersion')
            ->where('has_analyze', 1)
			->where('is_cancelled', 0)
			->whereHas('client', function ($q) {
				$q->whereHas('manager_region')
					->whereRaw('(SELECT client_managers.salesman_id 
										FROM client_managers 
										WHERE client.id = client_managers.client_id 
										LIMIT 1) = '.\Session::get('salesman_data')->id.'
								');
            })
            ->orderBy('id', 'DESC');
		
		// Fábio
		if ($request->session()->get('salesman_data')->id != 10) {
			$programations->where('coordinator_has_analyze' , 0);
		} else {
			$programations->orWhere(function($q) {
				$q->where('coordinator_has_analyze' , 1)
					->where('has_analyze', 1)
					->where('is_cancelled', 0);
			});
		}

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code"){
                    $programations->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $programations->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $programations->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                    });
                }

            }
        }

        return view('gree_commercial_external.programation.approv', [
            'programations' => $programations->paginate(10),
            'clients' => $clients
        ]);
    }

    public function programationApprov_do(Request $request) {

        $programation = Programation::with('client.client_managers.salesman', 'programationVersion', 'salesman')
            ->where('id', $request->programation_id)
            ->where('has_analyze', 1)
            ->first();

        if ($programation) {

            $salesman = Salesman::find($request->session()->get('salesman_data')->id);
            if (Hash::check($request->password, $request->session()->get('salesman_data')->password)) {

                if ($salesman->is_active == 0) {

                    LogSystem("Representante bloqueado pela administração, está tentando acessar o sistema.", $salesman->code);
                    return redirect('/comercial/operacao/logout')
                        ->with('error', 'Você foi bloqueado, entre em contato com administrador.');

                } else {
					
					// FÁBIO
					if (\Session::get('salesman_data')->id == 10 or \Session::get('salesman_data')->id == 6) {
						
						$programation->has_analyze = 0;
						$programation->coordinator_has_analyze = 1;
						$programation->manager_has_analyze = 1;
						$programation->save();
						$programation->programationVersion->description = $request->description;

						if ($request->type_analyze == 1)
							$programation->programationVersion->is_approv = 1;
						else
							$programation->programationVersion->is_reprov = 1;

						$programation->programationVersion->save();

						if ($request->type_analyze == 1) {

							// Construção da programação macro
							ProgramationMacro::where('programation_id', $programation->id)->delete();

							$version = $programation->programationVersion->version;
							$months = $programation->programationMonth($version)->get();
							foreach ($months as $d) {
								$categories = json_decode($d->json_qtd_prices, true);
								foreach ($categories as $cat) {
									foreach($cat['products'] as $prod) {

										$new_programation_macro = new ProgramationMacro;
										$new_programation_macro->programation_id = $programation->id;
										$new_programation_macro->salesman_id = $programation->request_salesman_id;
										$new_programation_macro->yearmonth = $d->yearmonth;
										$new_programation_macro->set_product_id = $prod['id'];
										$new_programation_macro->category_id = $cat['id'];
										$new_programation_macro->price = $prod['price'];
										$new_programation_macro->quantity = $prod['qtd'];
										$new_programation_macro->total = $prod['qtd'];
										$new_programation_macro->save();
									}

								}
							}
						}

						// Enviar email
						$pattern = array(
							'title' => $request->type_analyze == 1 ? 'A PROGRAMAÇÃO: '. $programation->code .' FOI APROVADO' : 'A PROGRAMAÇÃO: '. $programation->code .' FOI REPROVADO',
							'description' => nl2br("Olá! ". $salesman->first_name ." ". $salesman->last_name .",
							<br><p><b>Client:</b> ". $programation->client->company_name ."
							<br><b>Programado para:</b> ". $programation->months ." </p>
							<br>Foi realizado uma atualização nessa programação, será necessário você entrar no sistema para realizar análise.
							<br><a href='". $request->root() ."/comercial/operacao'>". $request->root() ."/comercial/operacao</a>"),
							'template' => 'misc.DefaultExternal',
							'subject' => 'GREE: Atualização da sua programação',
						);

						SendMailJob::dispatch($pattern, $salesman->email);

						$settings = Settings::where('command', 'programation_approval')->first();
						if ($settings->value) {
							$arr = explode(',', $settings->value);

							foreach ($arr as $key) {

								// Enviar email
								$pattern = array(
									'title' => $request->type_analyze == 1 ? 'A PROGRAMAÇÃO: '. $programation->code .' FOI APROVADO' : 'A PROGRAMAÇÃO: '. $programation->code .' FOI REPROVADO',
									'description' => nl2br("Olá! ". $salesman->first_name ." ". $salesman->last_name .",
							<br><p><b>Client:</b> ". $programation->client->company_name ."
							<br><b>Programado para:</b> ". $programation->months ." </p>
							<br>Foi realizado uma atualização nessa programação, será necessário você entrar no sistema para realizar análise.
							<br><a href='". $request->root() ."/comercial/operacao'>". $request->root() ."/comercial/operacao</a>"),
									'template' => 'misc.DefaultExternal',
									'subject' => 'GREE: Atualização da sua programação',
								);

								SendMailJob::dispatch($pattern, $key);
							}
						}	
					} else {

						if ($request->type_analyze == 1) {
							$programation->has_analyze = 1;
						} else {
							$programation->has_analyze = 0;
						}
						
						$programation->coordinator_has_analyze = 1;
						$programation->save();
						
						$programation->programationVersion->description = $request->description;
						if ($request->type_analyze == 2)
							$programation->programationVersion->is_reprov = 1;

						$programation->programationVersion->save();
						
						
						// FÁBIO
						$manager = Salesman::find(10);

						// Enviar email para o gestor
						$pattern = array(
							'title' => 'NOVA PROGRAMAÇÃO: '. $programation->code,
							'description' => nl2br("Olá! ". $manager->first_name ." ". $manager->last_name .",
							<br><p><b>Client:</b> ". $programation->client->company_name ."
							<br><b>Representante:</b> ". $programation->salesman->full_name ."
							<br><b>Programado para:</b> ". $programation->months ." </p>
							<br>Foi realizado uma atualização nessa programação, será necessário você entrar no sistema para realizar análise.
							<br><a href='". $request->root() ."/comercial/operacao'>". $request->root() ."/comercial/operacao</a>"),
							'template' => 'misc.DefaultExternal',
							'subject' => 'GREE: Análise da nova programação: '. $programation->code,
						);

						SendMailJob::dispatch($pattern, $manager->email);
					}

                    return redirect('/comercial/operacao/programation/approv')
                        ->with('success', 'Você realizou análise com sucesso!');
                }
            } else {

                if ($salesman->retry > 0) {
                    $salesman->retry = $salesman->retry - 1;

                    if ($salesman->retry == 0) {

                        $salesman->retry_time = date('Y-m-d H:i:s');
                        $salesman->is_active = 0;
                        $salesman->save();

                        // Write Log
                        LogSystem("Representante foi bloqueado no sistema por usar todas as suas tentativas.", $salesman->code);
                        return redirect('/comercial/operacao/logout')
                            ->with('error', 'Você foi bloqueado, entre em contato com administrador.');

                    } else {

                        $salesman->retry_time = date('Y-m-d H:i:s');
                        $salesman->save();

                        // Write Log
                        LogSystem("Representante tentou acessar e errou sua senha. Restou apenas ". $salesman->retry ." tentativa(s).", $salesman->code);
                        return redirect()->back()
                            ->with('error', 'Login ou senha incorreta. Você tem '. $salesman->retry .' tentativa(s)');
                    }
                } else {

                    // Write Log
                    LogSystem("Colaborador está tentando acesso, mesmo já tendo sido bloqueado!", $salesman->code);
                    return redirect('/comercial/operacao/logout')
                        ->with('error', 'Você foi bloqueado, entre em contato com administrador.');
                }
            }
        } else {
            return redirect()->back()->with('error', 'A programação que está tentando análisar, não está disponível!');

        }

        return view('gree_commercial_external.programation.approv', [
            'programations' => $programations,
        ]);
    }

    public function orderNew(Request $request) {

        if ($request->programation_id) {

            $month = App\Model\Commercial\ProgramationMonth::with('programation.programationVersionLast', 'orderSales.OrderProducts')
                ->whereHas('programation', function($q) use ($request) {
                    $q->where('request_salesman_id', $request->session()->get('salesman_data')->id)
                        ->where('has_analyze', 0)
                        ->where('is_cancelled', 0);
                })->where('id', $request->programation_id)
                ->first();

            if (!$month)
                return redirect()->back()->with('error', 'O mês da programação escolhida, não pertence a você!');
			
			$programation_macro = ProgramationMacro::where('programation_id', $month->programation->id)->where('yearmonth', $month->yearmonth)->get();

            if (!$programation_macro)
                return redirect()->back()->with('error', 'Não foi possível encontrar o macro da programação!');

            $products = json_decode($month->json_qtd_prices, true);
            $table = json_decode($month->json_table_price);
            $client = json_decode($month->programation->json_client);
            $categories = collect(json_decode($month->programation->json_categories_products));
			
			$collect_adjust = collect(json_decode($month->adjust_month));
			$total_adjust = $collect_adjust ? $collect_adjust->sum('factor') : false;
            $arr_month = [];
            $arr_cat_id = [];
            foreach ($products as $key) {
                $cat = [];
                $cat['id'] = $key['id'];
                $arr_cat_id[] = $key['id'];
                $arr_prod = [];
                foreach ($key['products'] as $prod) {
                    $prods = [];
                    $prods['id'] = $prod['id'];
                    $prods['price'] = $prod['price'];
                    $prods['qtd'] = $prod['qtd'];
					$calc_max = $this->calcProgramationMacroQtd($month, $programation_macro, $key['id'], $prod['id']);
                    $prods['max'] = $calc_max['total'];
                    $prods['is_custom'] = 0;
					
					$setproduct = SetProduct::find($prod['id']);
					$prods['calc_cubage'] = isset($setproduct) ? $setproduct->calc_cubage : 0.0;
                    $prods['descoint'] = 0;

                    array_push($arr_prod, $prods);
                }

                $cat['products'] = $arr_prod;
                array_push($arr_month, $cat);
            }

            $all_cat_prod = $categories;

        } else {
            $month = [];
            $arr_month = [];
            $table = "";
            $all_cat_prod = [];
            $client = [];
			$total_adjust = 1;
        }

        return view('gree_commercial_external.order.programation.new', [
            'month' => $month,
            'arr_month' => $arr_month,
            'all_cat_prod' => $all_cat_prod,
            'table' => $table,
            'client' => $client,
			'total_adjust' => $total_adjust
        ]);
    }

    public function orderSaveNew(Request $request) {

        $programation = Programation::with('client.client_peoples_contact', 'programationVersion', 'programationMonth.orderSales')
            ->where('request_salesman_id',$request->session()->get('salesman_data')->id)
            ->where('id', $request->programation_id)
            ->where('has_analyze', 0)
            ->first();

        if (!$programation)
            return redirect()->back()->with('error', 'Aconteceu algo inesperado, por favor, atualize a página e revise os dados.');
		
		// Verificar se já existe algum pedido com solicitação de alteração na programação.
		$p_month = $programation->programationMonth;
		
		foreach ($p_month as $mth) {
			$r_order = $mth->orderSales->where('is_request_edit_programation', 1)->where('has_analyze', 1)->where('is_cancelled', 0)->first();
			if ($r_order) {
				return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois você tem um pedido solicitando alteração de programação. Código do pedido: '. $r_order->code);
			}
		}

        $order = new OrderSales;
        if ($request->other_client) {
            $client_delivery = Client::find($request->other_client);
        } else {
            $client_delivery = $programation->client;
        }

        $client = $programation->client;
		
		// Verificar se o cliente pode criar pedido.
        if (!$client)
            return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois o cliente não existe ou não pertence a você.');

        if ($client->financy_status == 1)
            return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois o cliente encontra-se reprovado pelo financeiro.');
        elseif ($client->financy_status == 2 and $request->date_payment != '0')
            return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois o cliente só está habilitado para pagamento à vista! Informe na data de pagamento o valor "0".');
		
		$order->code = getCodeModule('order_commercial', '', 1);
        $order->control_client = $request->control_client;
        $order->observation = $request->observation;
        $order->code_client = $client->code;
		$order->client_vpc = $client->vpc;
        $month = ProgramationMonth::with(['programation' => function($q) use ($request) {
            $q->where('id', $request->programation_id);
        }, 'OrderSales.OrderProducts'])->where('id', $request->programation_month_id)->first();

        if (!$month)
            return redirect()->back()->with('error', 'Aconteceu algo inesperado, por favor, atualize a página e revise os dados.');

        $table = json_decode($month->json_table_price);
		$order->type_client = $table->type_client;
        $order->contract_vpc = $table->contract_vpc;
        $order->average_term = $table->average_term;
        $order->cif_fob = $table->cif_fob;

        $order->type_payment = $request->type_payment;
        $order->form_payment = $request->form_payment;
        $order->name_transport = $request->transport_name;
		$order->date_payment = $request->date_payment;
        $order->commission = $request->commission;
		if ($request->vpc_view) {
			$order->vpc_view = $request->vpc_view;
		}

        $old_date = str_replace("/", "-", $request->date_invoice);
        $date_end = date('Y-m-d', strtotime($old_date));
        $order->date_invoice = $date_end;

        $order->client_company_name = $client->company_name;
        $order->client_shop = $client->fantasy_name;
        $order->client_address = $client->address;
        $order->client_city = $client->city;
        $order->client_district = $client->district;
        $order->client_phone = $request->client_phone;
        $order->client_state = $client->state;
        $order->client_especial_regime_icms_per_st = $client->state;
        $order->client_identity = $client->identity;
        $order->client_state_registration = $client->state_registration;
        $order->client_suframa_registration = $client->suframa_registration;

        if ($client->client_peoples_contact->count()) {
            if ($client->client_peoples_contact->where('type_contact', 1)->first()) {
                $buyer = $client->client_peoples_contact->where('type_contact', 1)->first();
                $order->client_phone = $buyer->phone;
                $order->client_peoples_contact_buyer_phone = $buyer->phone;
                $order->client_peoples_contact_buyer_contact = $buyer->email;
            }
            if ($client->client_peoples_contact->where('type_contact', 2)->first()) {
                $financy = $client->client_peoples_contact->where('type_contact', 2)->first();
                $order->client_email_financy_nfe = $financy->email;
            }

        }


        $order->client_id = $client->id;

        $order->programation_month_id = $request->programation_month_id;

        $order->has_analyze = 0;
        $order->waiting_assign = 1;
        $order->request_salesman_id = $request->session()->get('salesman_data')->id;
        $order->programation_version = $programation->programationVersion->version;
		
		if ($request->has_apply_discount) {
			$order->has_apply_discount = $request->has_apply_discount;
			$order->state_invoice = $request->state_invoice;
		}

        $header = DB::connection('commercial')
            ->table('settings')
            ->where('type', 2)
            ->get()->toJson();

        $order->json_header = $header;
        $order->save();

        if ($table->cif_fob == 0) {
            $order_receiver = new OrderReceiver;
            $order_receiver->type_receiver = $request->receiver;
            $order_receiver->type_day_receiver = $request->day_receiver;
            if ($request->day_receiver == 2) {
                $order_receiver->monday_friday_hour_start = $request->hour_start_mon_fri.':00';
                $order_receiver->monday_friday_hour_end = $request->hour_end_mon_fri.':00';
                $order_receiver->saturday_hour_start = $request->hour_start_sat.':00';
                $order_receiver->saturday_hour_end = $request->hour_end_sat.':00';
            } else if ($request->day_receiver == 1) {
                $order_receiver->monday_friday_hour_start = $request->hour_start_mon_fri.':00';
                $order_receiver->monday_friday_hour_end = $request->hour_end_mon_fri.':00';
            }
            $order_receiver->apm_name = $request->apm_name;
            $order_receiver->apm_phone = $request->apm_phone;
            $order_receiver->apm_email = $request->apm_email;
            $order_receiver->transport = $request->discharge;
            if ($request->discharge == 2)
                $order_receiver->total = $request->price_charge;

            $order_receiver->order_sales_id = $order->id;
            $order_receiver->save();
        }

        if ($request->other_client) {
            $order_delivery = new OrderDelivery;
            $order_delivery->order_sales_id = $order->id;
            $order_delivery->address = $client_delivery->address;
            $order_delivery->city = $client_delivery->city;
            $order_delivery->district = $client_delivery->district;
            $order_delivery->state = $client_delivery->state;
            $order_delivery->zipcode = $client_delivery->zipcode;
            $order_delivery->phone = $request->client_phone;
            $order_delivery->state_registration = $client_delivery->state_registration;
            $order_delivery->identity = $client_delivery->identity;
            $order_delivery->save();
        }

        $json = json_decode($request->json_order, true);
        $macro = ProgramationMacro::where('programation_id', $request->programation_id)->where('yearmonth', $month->yearmonth)->get();
        if (!$macro) {
            return redirect()->back()->with('error', 'Aconteceu algo inesperado, o macro da programação não pode ser encontrado.');
        }

		$new_programation = false;
        foreach ($json as $key) {
            foreach ($key['products'] as $prod) {
                if ($prod['qtd'] > 0) {
                    $item = new OrderProducts;
                    $item->order_sales_id = $order->id;
                    $item->set_product_id = $prod['id'];
                    $item->category_id = $key['id'];
                    $item->price_unit = $prod['price'];
                    $item->quantity = $prod['qtd'];
                    $item->descoint = $prod['descoint'];
                    $item->total = $prod['price'];
                    $item->is_price_custom = $prod['is_custom'];
					$result = $this->calcProgramationMacroQtd($month, $macro, $key['id'], $prod['id'], $prod['qtd']);
					if ($result['is_negative']) {
						$item->is_qtd_diff_programation = 1;
						$new_programation = true;
					}
					if ($result['new_product']) {
						$item->is_prod_diff_programation = 1;
						$new_programation = true;
					}					
                    $item->save();
                }
            }
        }
		
		if ($new_programation) {
            $order->observation = $order->observation ." \nATENÇÃO: informações do pedido está diferente da programação!";
			$order->is_request_edit_programation = 1;
        	$order->save();
		}

		// Salvar o layout renderizado.
        $order->view = $this->renderViewOrder($order->id);
        $order->save();
		
		$settings = Settings::where('command', 'order_new_register')->first();
		if ($settings->value) {
			$arr = explode(',', $settings->value);

			foreach ($arr as $key) {

				$pattern = array(
					'title' => 'COMERCIAL - NOVO PEDIDO: '. $order->code,
                                'description' => nl2br("Olá! Foi realizado a criação de um novo pedido de vendas na plataforma da GREE
                        <br><p><b>Client:</b> ". $client->company_name ."
						<br><b>Programação:</b> ". $programation->code ."
						<br>Representante: ". $request->session()->get('salesman_data')->full_name ."
                        <br><a href='". $request->root() ."/commercial/order/all'>". $request->root() ."/commercial/order/all</a></p>"),
					'template' => 'misc.DefaultExternal',
                    'subject' => 'Comercial - novo pedido',
				);

				SendMailJob::dispatch($pattern, $key);
			}
		}

        return redirect('/comercial/operacao/order/all')->with('success', 'Seu pedido foi criado com sucesso! Faça a comprovação do pedido.');

    }

    public function orderList(Request $request) {

        $array_input = collect([
            'code_order',
            'code_programation',
            'client',
            'start_date',
            'is_analyze',
			'status'
        ]);

        $array_input = putSession($request, $array_input, 'order_');

        $filtros_sessao = getSessionFilters('order_');
		$clients = Client::ShowOnlyManager($request->session()->get('salesman_data')->id)->where('is_active', 1)->get();

		$order = OrderSales::with(['programationMonth.programation.client', 'orderSalesAttach'])
			->whereHas('programationMonth', function ($sub1) use ($request) {
				$sub1->whereHas('programation', function($sub) use ($request) {
					$sub->ShowOnlyManager($request->session()->get('salesman_data')->id);
				});
			})->where('is_programmed', 1)
			->orderBy('id', 'DESC');

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code_order"){
                    $order->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."code_programation"){
                    $order->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->whereHas('programation', function($q1) use ($valor_filtro) {
                            $q1->where('code', 'like', '%'.$valor_filtro.'%');
                        });
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."is_analyze"){
                    $order->where('has_analyze', 1);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $order->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                    });
                }
				if($nome_filtro == $filtros_sessao[1]."status") {

                    if($valor_filtro == 1) {  
                        $order->where('is_cancelled', 1);
                    } 
                    elseif($valor_filtro == 2) { 
                        $order->where('is_approv', 1)->where('is_invoice', 0)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 3) { 
                        $order->where('is_invoice', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 4) {
                        $order->where('salesman_imdt_reprov', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 5) {
                        $order->where('commercial_is_reprov', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 6) {
                        $order->where('financy_reprov', 1)->where('is_cancelled', 0);
                    }    
                    elseif($valor_filtro == 7) {
                        $order->where('waiting_assign', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 8) {
                        $order->where('has_analyze', 1);
                    }  
                }
            }
        }

        return view('gree_commercial_external.order.programation.list', [
            'order' => $order->paginate(10),
            'clients' => $clients,
        ]);
    }

    public function orderApprovList(Request $request) {

        $array_input = collect([
            'code_order',
            'code_programation',
            'subordinates',
            'client',
            'start_date',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        $order = OrderSales::with(
            'programationMonth.programation.client',
            'orderSalesAttach',
            'programationMonth.programation.salesman')
            ->ValidProcessImdt($request)
            ->where('has_analyze', 1)
            ->where('is_programmed', 1)
            ->where('waiting_assign', 0)
            ->where('salesman_imdt_approv', 0)
            ->where('salesman_imdt_reprov', 0)
            ->orderBy('id', 'DESC');

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code_order"){
                    $order->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."code_programation"){
                    $order->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->whereHas('programation', function($q1) use ($valor_filtro) {
                            $q1->where('code', 'like', '%'.$valor_filtro.'%');
                        });
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $order->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                    });
                }

            }
        }

        $subordinates = Salesman::ValidProcessImdt($request)->get();
        $clients = DB::connection('commercial')->table('client')->where('request_salesman_id', \Session::get('salesman_data')->id)->where('is_active', 1)->get();

        return view('gree_commercial_external.order.programation.approv', [
            'order' => $order->paginate(10),
            'clients' => $clients,
            'subordinates' => $subordinates
        ]);
    }

    public function orderAnalyze(Request $request, $id) {

        $order = OrderSales::with(
            'programationMonth.programation.client',
            'orderSalesAttach',
            'programationMonth.programation.salesman',
            'orderImdAnalyze',
            'orderCommercialAnalyze',
            'orderFinancyAnalyze',
			'programationMonth',
			'orderProducts')
            ->ValidProcessImdt($request)
            ->where('is_programmed', 1)
            ->where('has_analyze', 1)
            ->where('salesman_imdt_approv', 0)
            ->where('salesman_imdt_reprov', 0)
            ->where('id', $id)
            ->first();

        if (!$order)
            return redirect()->back()->with('error', 'Não foi possível encontrar o pedido.');

        $salesman_id = $order->programationMonth->programation->request_salesman_id;
        $salesman = '';
        $arr_imdt = [];
        // Montar array de gestores
        do {
            $data = Salesman::with('immediate_boss')->find($salesman_id);
            if ($data->immediate_boss->first()) {
                $salesman = $data->immediate_boss->first();
                $salesman_id = $salesman->id;
                if ($salesman->is_direction != 2)
                    $arr_imdt[] = $salesman;
            }
        } while ($salesman->is_direction != 2);

        $dir_commecrial = UserOnPermissions::with('user')->where('perm_id', 20)
            ->where('grade', 9)
            ->where('can_approv', 1)
            ->first();

        $dir_financy = UserOnPermissions::with('user')->where('perm_id', 18)
            ->where('grade', 8)
            ->where('can_approv', 1)
            ->first();

        if (!$order)
            return response()->json(['success' => false,'msg' => 'Seu pedido não foi encontrado na base de dados.',]);

        return view('gree_commercial_external.order.programation.analyze', [
            'order' => $order,
            'arr_imdt' => $arr_imdt,
            'dir_commecrial' => $dir_commecrial,
            'dir_financy' => $dir_financy,
        ]);
    }

    public function orderPrintView(Request $request, $id) {

        $order = OrderSales::with(['programationMonth.programation.client', 'orderSalesAttach'])
            ->where(function ($q) use ($request) {
                $q->whereHas('programationMonth', function ($sub1) use ($request) {
                    $sub1->whereHas('programation', function($sub) use ($request) {
                            $sub->ShowOnlyManager($request->session()->get('salesman_data')->id);
                    });
                })->orWhere(function($query) use ($request) {
                    $query->ValidProcessImdt($request);
                });
            })
            ->where('id', $id)
            ->where('is_programmed', 1)
            ->first();


        if (!$order)
            return redirect()->back()->with('error', 'Você não está autorizado para ver essa solicitação!');

        return $order->view;
    }

    public function orderConfirmedNew(Request $request) {

        if ($request->client_id and $request->monthyear and $request->table_id) {

            $month = $request->monthyear;
            $table = json_decode(SalesmanTablePrice::with('set_product_price_fixed')->find($request->table_id)->toJson());
            $client = json_decode(Client::find($request->client_id)->toJson());
            $categories = SetProductGroup::with(['setProductOnGroup'=>function($q){
                $q->orderBy('position', 'ASC');
            }, 'setProductOnGroup.productAirEvap'])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

            $fields = OrderFieldTablePrice::all();
            $rules = OrderTablePriceRules::get();

            $applyPrice = new ApplyConditionPriceBase($fields, $rules, $table);

            $arr_month = [];
            $arr_cat_id = [];
            foreach ($categories as $key) {
                $cat = [];
                $cat['id'] = $key->id;
                $arr_cat_id[] = $key->id;
                $arr_prod = [];
                foreach ($key->setProductOnGroup as $prod) {
                    $prods = [];
                    $prods['id'] = $prod->id;
					if ($table->is_fixed_price == 1) {
						$collect_price_fixed = collect($table->set_product_price_fixed);
						$fixed_price = $collect_price_fixed
							->where('salesman_table_price_id', $table->id)
							->where('set_product_id', $prod->id)
							->first();
						if ($fixed_price)
							$prods['price'] = $fixed_price->price;
						else
							$prods['price'] = 0.00;
					} else {
						$prods['price'] = $applyPrice->calcPrice($prod->price_base, $prod, $request->monthyear, FALSE);
					}
                    
                    $prods['qtd'] = 0;
                    $prods['max'] = 0;
                    $prods['is_custom'] = 0;
					$prods['calc_cubage'] = $prod->calc_cubage;
                    $prods['descoint'] = 0;

                    array_push($arr_prod, $prods);
                }

                $cat['products'] = $arr_prod;
                array_push($arr_month, $cat);
            }

            $all_cat_prod = $categories->whereIn('id', $arr_cat_id);

            $clients = DB::connection('commercial')->table('client')->where('request_salesman_id', \Session::get('salesman_data')->id)->where('is_active', 1)->get();
            $tables = DB::connection('commercial')->table('salesman_table_price')->where('salesman_id', \Session::get('salesman_data')->id)->where('is_programmed', 0)->get();
            $months = $this->setSessionDatesAvaibles();
        } else {
            $month = [];
            $arr_month = [];
            $table = "";
            $all_cat_prod = [];
            $client = [];

            $clients = DB::connection('commercial')->table('client')->where('request_salesman_id', \Session::get('salesman_data')->id)->where('is_active', 1)->get();
            $tables = DB::connection('commercial')->table('salesman_table_price')->where('salesman_id', \Session::get('salesman_data')->id)->where('is_programmed', 0)->get();
            $months = $this->setSessionDatesAvaibles();

        }
		
		if (!$months->count())
			return redirect()->back()->with('error', 'Não há meses disponíveis para criação do pedido, entre em contato com administração para aplicação do reajuste.');

        return view('gree_commercial_external.order.confirmed.new', [
            'month' => $month,
            'arr_month' => $arr_month,
            'all_cat_prod' => $all_cat_prod,
            'table' => $table,
            'client' => $client,
            'clients' => $clients,
            'tables' => $tables,
            'months' => $months,
        ]);
    }

    public function orderConfirmedSaveNew(Request $request) {

        $client = Client::with('client_peoples_contact')
            ->where('request_salesman_id',$request->session()->get('salesman_data')->id)
            ->where('id', $request->client_id)
            ->where('has_analyze', 0)
            ->first();

        if (!$client)
            return redirect()->back()->with('error', 'Cliente não foi encontrado na base de dados.');

        $table = SalesmanTablePrice::where('salesman_id',$request->session()->get('salesman_data')->id)
            ->where('id', $request->table_id)
            ->where('is_programmed', 0)
            ->first();

        if (!$table)
            return redirect()->back()->with('error', 'Condição comercial não foi encontrada na base de dados.');

        $order = new OrderSales;
        if ($request->other_client) {
            $client_delivery = Client::find($request->other_client);
        } else {
            $client_delivery = $client;
        }
		
		// Verificar se o cliente pode criar pedido.
        if (!$client)
            return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois o cliente não existe.');

        if ($client->financy_status == 1)
            return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois o cliente encontra-se reprovado pelo financeiro.');
        elseif ($client->financy_status == 2 and $request->date_payment != '0')
            return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois o cliente só está habilitado para pagamento à vista! Informe na data de pagamento o valor "0".');

		if ($request->type_order == 1)
            $order->code = getCodeModule('order_commercial', '', 1);
        elseif ($request->type_order == 2)
            $order->code = getCodeModule('order_commercial_vpc', '', 1);
        elseif ($request->type_order == 3)
            $order->code = getCodeModule('order_commercial_vdc', '', 1);
        else
            $order->code = getCodeModule('order_commercial_esp', '', 1);

		$order->type_order = $request->type_order;
        $order->control_client = $request->control_client;
        $order->observation = $request->observation;
        $order->code_client = $client->code;
        $order->type_client = $table->type_client;

        $order->contract_vpc = $table->contract_vpc;
        $order->average_term = $table->average_term;
        $order->cif_fob = $table->cif_fob;

        $order->type_payment = $request->type_payment;
        $order->form_payment = $request->form_payment;
        $order->name_transport = $request->transport_name;
		$order->date_payment = $request->date_payment;
        $order->commission = $request->commission;
		if ($request->vpc_view) {
			$order->vpc_view = $request->vpc_view;
		}

        $old_date = str_replace("/", "-", $request->date_invoice);
        $date_end = date('Y-m-d', strtotime($old_date));
        $order->date_invoice = $date_end;

        $order->client_company_name = $client->company_name;
        $order->client_shop = $client->fantasy_name;
        $order->client_address = $client->address;
        $order->client_city = $client->city;
        $order->client_district = $client->district;
        $order->client_phone = $request->client_phone;
        $order->client_state = $client->state;
        $order->client_especial_regime_icms_per_st = $client->state;
        $order->client_identity = $client->identity;
        $order->client_state_registration = $client->state_registration;
        $order->client_suframa_registration = $client->suframa_registration;

        if ($client->client_peoples_contact->count()) {
            if ($client->client_peoples_contact->where('type_contact', 1)->first()) {
                $buyer = $client->client_peoples_contact->where('type_contact', 1)->first();
                $order->client_peoples_contact_buyer_phone = $buyer->phone;
                $order->client_peoples_contact_buyer_contact = $buyer->email;
            }
            if ($client->client_peoples_contact->where('type_contact', 2)->first()) {
                $financy = $client->client_peoples_contact->where('type_contact', 2)->first();
                $order->client_email_financy_nfe = $financy->email;
            }

        }


        $order->client_id = $client->id;

        $categories = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }, 'setProductOnGroup.productAirEvap'])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $order->programation_month_id = 0;
        $order->json_table_price = $table->toJson();
        $order->json_categories_products = $categories->toJson();
        $order->is_programmed = 0;
        $order->yearmonth = $request->monthyear;
        $order->has_analyze = 0;
        $order->waiting_assign = 1;
        $order->request_salesman_id = $request->session()->get('salesman_data')->id;
        $order->programation_version = 1;
		$order->adjust_month = OrderAvaibleMonth::where('date', date('Y-m-01 00:00:00'))->get()->toJson();
		
		if ($request->has_apply_discount) {
			$order->has_apply_discount = $request->has_apply_discount;
			$order->state_invoice = $request->state_invoice;
		}

        $header = DB::connection('commercial')
            ->table('settings')
            ->where('type', 2)
            ->get()->toJson();

        $order->json_header = $header;
        $order->save();

        if ($table->cif_fob == 0) {
            $order_receiver = new OrderReceiver;
            $order_receiver->type_receiver = $request->receiver;
            $order_receiver->type_day_receiver = $request->day_receiver;
            if ($request->day_receiver == 2) {
                $order_receiver->monday_friday_hour_start = $request->hour_start_mon_fri.':00';
                $order_receiver->monday_friday_hour_end = $request->hour_end_mon_fri.':00';
                $order_receiver->saturday_hour_start = $request->hour_start_sat.':00';
                $order_receiver->saturday_hour_end = $request->hour_end_sat.':00';
            } else if ($request->day_receiver == 1) {
                $order_receiver->monday_friday_hour_start = $request->hour_start_mon_fri.':00';
                $order_receiver->monday_friday_hour_end = $request->hour_end_mon_fri.':00';
            }
            $order_receiver->apm_name = $request->apm_name;
            $order_receiver->apm_phone = $request->apm_phone;
            $order_receiver->apm_email = $request->apm_email;
            $order_receiver->transport = $request->discharge;
            if ($request->discharge == 2)
                $order_receiver->total = $request->price_charge;

            $order_receiver->order_sales_id = $order->id;
            $order_receiver->save();
        }

        if ($request->other_client) {
            $order_delivery = new OrderDelivery;
            $order_delivery->order_sales_id = $order->id;
            $order_delivery->address = $client_delivery->address;
            $order_delivery->city = $client_delivery->city;
            $order_delivery->district = $client_delivery->district;
            $order_delivery->state = $client_delivery->state;
            $order_delivery->zipcode = $client_delivery->zipcode;
            $order_delivery->phone = $request->client_phone;
            $order_delivery->state_registration = $client_delivery->state_registration;
            $order_delivery->identity = $client_delivery->identity;
            $order_delivery->save();
        }

        $json = json_decode($request->json_order, 2);

        foreach ($json as $key) {
            foreach ($key['products'] as $prod) {
                if ($prod['qtd'] > 0) {
                    $item = new OrderProducts;
                    $item->order_sales_id = $order->id;
                    $item->set_product_id = $prod['id'];
                    $item->category_id = $key['id'];
                    $item->price_unit = $prod['price'];
                    $item->quantity = $prod['qtd'];
                    $item->descoint = $prod['descoint'];
                    $item->total = $prod['price'];
                    $item->is_price_custom = $prod['is_custom'];
                    $item->save();
                }
            }
        }

        $order->view = $this->renderViewOrderConfirmed($order->id);
        $order->save();

		$settings = Settings::where('command', 'order_new_register')->first();
		if ($settings->value) {
			$arr = explode(',', $settings->value);

			foreach ($arr as $key) {

				$pattern = array(
					'title' => 'COMERCIAL - NOVO PEDIDO: '. $order->code,
                                'description' => nl2br("Olá! Foi realizado a criação de um novo pedido de vendas na plataforma da GREE
                        <br><p><b>Client:</b> ". $client->company_name ." </p>
						<br>Representante: ". $request->session()->get('salesman_data')->full_name ."
                        <br><a href='". $request->root() ."/commercial/order/confirmed/all'>". $request->root() ."/commercial/order/confirmed/all</a></p>"),
					'template' => 'misc.DefaultExternal',
                    'subject' => 'Comercial - novo pedido',
				);

				SendMailJob::dispatch($pattern, $key);
			}
		}

        return redirect('/comercial/operacao/order/confirmed/all')->with('success', 'Seu pedido foi criado com sucesso! Faça a comprovação do pedido.');

    }

    public function orderConfirmedList(Request $request) {

        $array_input = collect([
            'code_order',
            'client',
            'start_date',
            'is_analyze',
			'status'
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();
		
		$clients = Client::ShowOnlyManager($request->session()->get('salesman_data')->id)->where('is_active', 1)->get();

        $order = OrderSales::with(['client', 'orderSalesAttach'])
            ->whereHas('client', function($sub) use ($request) {
                    $sub->ShowOnlyManager($request->session()->get('salesman_data')->id);
            })
            ->where('is_programmed', 0)
            ->orderBy('id', 'DESC');        

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code_order"){
                    $order->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."is_analyze"){
                    $order->where('has_analyze', 1);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $order->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                }
				
				if($nome_filtro == $filtros_sessao[1]."status") {

                    if($valor_filtro == 1) {  
                        $order->where('is_cancelled', 1);
                    } 
                    elseif($valor_filtro == 2) { 
                        $order->where('is_approv', 1)->where('is_invoice', 0)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 3) { 
                        $order->where('is_invoice', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 4) {
                        $order->where('salesman_imdt_reprov', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 5) {
                        $order->where('commercial_is_reprov', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 6) {
                        $order->where('financy_reprov', 1)->where('is_cancelled', 0);
                    }    
                    elseif($valor_filtro == 7) {
                        $order->where('waiting_assign', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 8) {
                        $order->where('has_analyze', 1);
                    }  
                }
            }
        }

        return view('gree_commercial_external.order.confirmed.list', [
            'order' => $order->paginate(10),
            'clients' => $clients,
        ]);
    }
	
	public function programationExport(Request $request) {

        $order = OrderSales::with('client.manager_region', 'client.salesman', 'orderProducts.setProduct.productAirEvap', 'programationMonth')
            ->whereHas('client', function($sub) use ($request) {
                $sub->ShowOnlyManager($request->session()->get('salesman_data')->id);
            })
            ->where('is_programmed', 1)
            ->orderBy('id', 'DESC');

        if ($request->salesman_id) {
            $order->where('request_salesman_id', $request->salesman_id);
        }

        if ($request->start_date) {
            $date = $request->start_date;
            $order->whereHas('programationMonth', function($q) use ($date) {
                $q->whereYear('yearmonth', '>=', date('Y', strtotime($date)))
                  ->whereMonth('yearmonth', '>=', date('m', strtotime($date)));
            });
        }

        if ($request->end_date) {
            $date = $request->end_date;
            $order->whereHas('programationMonth', function($q) use ($date) {
                $q->whereYear('yearmonth', '<=', date('Y', strtotime($date)))
                  ->whereMonth('yearmonth', '<=', date('m', strtotime($date)));
            });
        } 

        if($request->status) {
            $status = $request->status;

            if($status == 1) {  
                $order->where('is_cancelled', 1);
            } 
            elseif($status == 2) { 
                $order->where('salesman_imdt_approv', 1)
                    ->where('commercial_is_approv', 1)
                    ->where('financy_approv', 1)
                    ->where('is_invoice', 0);
            }
            elseif($status == 3) { 
                $order->where('is_invoice', 1);
            }
            elseif($status == 4) {
                $order->where('salesman_imdt_reprov', 1);
            }
            elseif($status == 5) {
                $order->where('commercial_is_reprov', 1);
            }
            elseif($status == 6) {
                $order->where('financy_reprov', 1);
            }    
            elseif($status == 7) {
                $order->where('waiting_assign', 1);
            }
            elseif($status == 8) {
                $order->where('has_analyze', 1);
            }  
        }

        $order = $order->get();

        ob_end_clean();
        return Excel::download(new App\Exports\ProgramationExport($order), 'ProgramationExport-'. date('Y-m-d') .'.xlsx');
    }
	
	public function orderExport(Request $request) {

        $order = OrderSales::with('client.manager_region', 'client.salesman', 'orderProducts.setProduct.productAirEvap')
			->whereHas('client', function($sub) use ($request) {
                $sub->ShowOnlyManager($request->session()->get('salesman_data')->id);
            })
            ->where('is_programmed', 0)
            ->orderBy('id', 'DESC');

        if ($request->start_date) {
            $order->whereYear('yearmonth', '>=', date('Y', strtotime($request->start_date)))
                    ->whereMonth('yearmonth', '>=', date('m', strtotime($request->start_date)));
        } 

        if ($request->end_date) {
            $order->whereYear('yearmonth', '<=', date('Y', strtotime($request->end_date)))
                    ->whereMonth('yearmonth', '<=', date('m', strtotime($request->end_date)));
        } 
    
        if($request->status) {
            $status = $request->status;

            if($status == 1) {  
                $order->where('is_cancelled', 1);
            } 
            elseif($status == 2) { 
                $order->where('salesman_imdt_approv', 1)
                    ->where('commercial_is_approv', 1)
                    ->where('financy_approv', 1)
                    ->where('is_invoice', 0);
            }
            elseif($status == 3) { 
                $order->where('is_invoice', 1);
            }
            elseif($status == 4) {
                $order->where('salesman_imdt_reprov', 1);
            }
            elseif($status == 5) {
                $order->where('commercial_is_reprov', 1);
            }
            elseif($status == 6) {
                $order->where('financy_reprov', 1);
            }    
            elseif($status == 7) {
                $order->where('waiting_assign', 1);
            }
            elseif($status == 8) {
                $order->where('has_analyze', 1);
            }  
        }    

        $order = $order->get();

        ob_end_clean();
        return Excel::download(new App\Exports\OrderExport($order), 'OrderExport-'. date('Y-m-d') .'.xlsx');
    }
	
    public function orderConfirmedApprovList(Request $request) {

        $array_input = collect([
            'code_order',
            'subordinates',
            'client',
            'start_date',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        $order = OrderSales::with(
            'client',
            'orderSalesAttach',
            'salesman')
            ->ValidProcessImdt($request, 0)
            ->where('is_programmed', 0)
            ->where('waiting_assign', 0)
            ->where('has_analyze', 1)
            ->where('salesman_imdt_approv', 0)
            ->where('salesman_imdt_reprov', 0)
            ->orderBy('id', 'DESC');

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code_order"){
                    $order->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $order->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                }

            }
        }

        $subordinates = Salesman::ValidProcessImdt($request, 0)->get();
        $clients = DB::connection('commercial')->table('client')->where('request_salesman_id', \Session::get('salesman_data')->id)->where('is_active', 1)->get();

        return view('gree_commercial_external.order.confirmed.approv', [
            'order' => $order->paginate(10),
            'clients' => $clients,
            'subordinates' => $subordinates
        ]);
    }

    public function orderConfirmedAnalyze(Request $request, $id) {

        $order = OrderSales::with(
            'client',
            'salesman',
            'orderSalesAttach',
            'orderImdAnalyze',
            'orderCommercialAnalyze',
            'orderFinancyAnalyze',
			'orderProducts')
            ->ValidProcessImdt($request, 0)
            ->where('is_programmed', 0)
            ->where('has_analyze', 1)
            ->where('salesman_imdt_approv', 0)
            ->where('salesman_imdt_reprov', 0)
            ->where('id', $id)
            ->first();

        if (!$order)
            return redirect()->back()->with('error', 'Não foi possível encontrar o pedido.');

        $salesman_id = $order->request_salesman_id;
        $salesman = '';
        $arr_imdt = [];
        // Montar array de gestores
        do {
            $data = Salesman::with('immediate_boss')->find($salesman_id);
            if ($data->immediate_boss->first()) {
                $salesman = $data->immediate_boss->first();
                $salesman_id = $salesman->id;
                if ($salesman->is_direction != 2)
                    $arr_imdt[] = $salesman;
            }
        } while ($salesman->is_direction != 2);

        $dir_commecrial = UserOnPermissions::with('user')->where('perm_id', 20)
            ->where('grade', 9)
            ->where('can_approv', 1)
            ->first();

        $dir_financy = UserOnPermissions::with('user')->where('perm_id', 18)
            ->where('grade', 8)
            ->where('can_approv', 1)
            ->first();

        if (!$order)
            return response()->json(['success' => false,'msg' => 'Seu pedido não foi encontrado na base de dados.',]);

        return view('gree_commercial_external.order.confirmed.analyze', [
            'order' => $order,
            'arr_imdt' => $arr_imdt,
            'dir_commecrial' => $dir_commecrial,
            'dir_financy' => $dir_financy,
        ]);
    }

    public function orderConfirmedPrintView(Request $request, $id) {

        $order = OrderSales::with(['client', 'orderSalesAttach'])
            ->where(function($q) use ($request) {
                $q->whereHas('client', function($sub) use ($request) {
                        $sub->ShowOnlyManager($request->session()->get('salesman_data')->id);
                })->orWhere(function($query) use ($request) {
                    $query->ValidProcessImdt($request, 0);
                });
            })
            ->where('id', $id)
            ->where('is_programmed', 0)

            ->first();


        if (!$order)
            return redirect()->back()->with('error', 'Você não está autorizado para ver essa solicitação!');

        return $order->view;
    }

    public function orderAnalyze_do(Request $request) {

        $user = Salesman::find($request->session()->get('salesman_data')->id);

        if (Hash::check($request->password, $user->password)) {

            try {
                $AnalyzeProcessOrder = new AnalyzeProcessOrder($request, $request->id);
                $AnalyzeProcessOrder->doAnalyze($request->type_analyze, 2, $request->description);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            if ($request->is_programmed == 1)
                return redirect('/comercial/operacao/order/approv')->with('success', 'Análise realizada com sucesso!');
            else
                return redirect('/comercial/operacao/order/confirmed/approv')->with('success', 'Análise realizada com sucesso!');
        } else {

            if ($user->retry > 0) {

                $user->retry = $user->retry - 1;
                $user->save();

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    // Write Log
                    LogSystem("Representante errou sua senha secreta para aprovar (comercial) muitas vezes e foi bloqueado no sistema.", $user->id);
                    return redirect('/comercial/operacao/logout')->with('error', "You have often erred in your secret password and been blocked, talk to administration.");
                } else {

                    // Write Log
                    LogSystem("Representante errou sua senha secreta para aprovar (comercial). Restou apenas ". $user->retry ." tentativa(s).", $user->id);
                    return redirect()->back()->with('error', 'You missed your secret password, only '. $user->retry .' attempt(s) left.');
                }
            } else {

                // Write Log
                LogSystem("Representante está tentando aprovar (comercial) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->id);
                return redirect()->back();
            }
        }
    }

    public function orderProofUpload(Request $request) {

        $order = OrderSales::with(['orderSalesAttach'])
            ->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $request->order_id)->first();

        if (!$order)
            return response()->json(['success' => false,'msg' => 'Seu pedido não foi encontrado na base de dados.',]);


        $orderSalesAttach = new OrderSalesAttach;
        $response = $this->uploadS3(1, $request->order_file, $request);
        if ($response['success']) {
            $orderSalesAttach->url = $response['url'];
        } else {
            return redirect()->back()->with('error', 'Não foi possível fazer upload do arquivo!');
        }

        $orderSalesAttach->size = $request->file('order_file')->getSize();
        $orderSalesAttach->name = $request->file('order_file')->getClientOriginalName();
        $orderSalesAttach->order_sales_id = $request->order_id;
        $orderSalesAttach->save();

        $order->load('orderSalesAttach');

        return response()->json([
           'success' => true,
           'data' => $order->orderSalesAttach,
        ]);

    }

    public function orderProofRemove(Request $request) {

        $order = OrderSales::with(['orderSalesAttach'])
            ->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $request->order_id)->first();

        if (!$order)
            return response()->json(['success' => false,'msg' => 'Seu pedido não foi encontrado na base de dados.',]);


        $file = $order->orderSalesAttach->where('id', $request->attach_id)->first();

        if (!$file)
            return response()->json(['success' => false,'msg' => 'Arquivo do pedido não foi encontrado.',]);

        $this->removeS3($file->url);
        $file->delete();

        $order->load('orderSalesAttach');

        return response()->json([
            'success' => true,
            'data' => $order->orderSalesAttach,
        ]);

    }

    public function orderProof(Request $request) {

        $order = OrderSales::with(['orderSalesAttach'])
            ->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $request->order_id)->first();

        if (!$order)
            return redirect()->back()->with('error', 'Seu pedido não foi encontrado na base de dados.');

		if ($order->orderSalesAttach->count() == 0)
			return redirect()->back()->with('error', 'Você precisa anexar ao menos 1 arquivo para poder validar a comprovação.');
			
		$response = $this->fileManagerSVR([
            'order_id' => $order->id
        ], 'api/v1/commercial/order/files/to/pdf');

        if (!$response->success)
            return redirect()->back()->with('error', $response->msg);
		
        $order->waiting_assign = 0;
		$order->url_view_proof = $response->url;
        $order->save();

        try {
            $orderAnalyze = new AnalyzeProcessOrder($request, $request->order_id);
            $orderAnalyze->startAnalyze();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Seu pedido foi enviado para análise com sucesso!.');

    }

    public function orderCancel(Request $request, $id) {

        $order = OrderSales::with(
			'orderSalesAttach', 
			'client', 
			'salesman', 
			'orderDelivery',
            'orderReceiver',
            'orderProducts.setProduct.productAirEvap',
            'programationMonth.programation.salesman')
            ->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $id)->first();

        if (!$order)
            return redirect()->back()->with('error', 'Seu pedido não foi encontrado na base de dados.');

        $order->is_cancelled = 1;
		$order->cancel_reason = $request->reason;
        $order->cancel_salesman_id = $request->session()->get('salesman_data')->id;
        $order->has_analyze = 0;
        $order->save();
		
		if ($order->is_programmed)
            $order->view = $this->renderViewOrder($order->id);
        else
            $order->view = $this->renderViewOrderConfirmed($order->id);
		
		$order->save();
		
		$settings = Settings::where('command', 'order_update_register')->first();
		if ($settings->value) {
			$arr = explode(',', $settings->value);

			foreach ($arr as $key) {

				if ($order->is_programmed) {
					$pattern = array(
						'title' => 'COMERCIAL - PEDIDO PROGRAMADO FOI CANCELADO: '. $order->code,
						'description' => nl2br("Olá! Foi realizado o cancelamento do pedido de vendas na plataforma da GREE
							<br><p><b>Client:</b> ". $order->client->company_name ."
							<br>Representante: ". $request->session()->get('salesman_data')->full_name ."
							<br><a href='". $request->root() ."/commercial/order/all'>". $request->root() ."/commercial/order/all</a></p>"),
						'template' => 'misc.DefaultExternal',
						'subject' => 'Comercial - pedido programado cancelado',

					);
				} else {
					$pattern = array(
						'title' => 'COMERCIAL - PEDIDO NÃO PROGRAMADO FOI CANCELADO: '. $order->code,
						'description' => nl2br("Olá! Foi realizado o cancelamento do pedido de vendas na plataforma da GREE
							<br><p><b>Client:</b> ". $order->client->company_name ."
							<br>Representante: ". $request->session()->get('salesman_data')->full_name ."
							<br><a href='". $request->root() ."/commercial/order/confirmed/all'>". $request->root() ."/commercial/order/confirmed/all</a></p>"),
						'template' => 'misc.DefaultExternal',
						'subject' => 'Comercial - pedido não programado cancelado',

					);
				}

				SendMailJob::dispatch($pattern, $key);
			}
		}

        return redirect()->back()->with('success', 'Seu pedido foi cancelado com sucesso!.');
    }

    public function listDropDownProgramations(Request $request) {
        $name = $request->search;

        $data = Programation::with('client')->whereExists(function ($sub){
            $sub->select(DB::raw(1))
                ->from('programation_version')
                ->whereRaw('programation.id = programation_version.programation_id')
                ->whereRaw('programation_version.is_approv = 1')
                ->orderBy('programation_version.id', 'DESC');
        })->whereExists(function ($sub){
            $sub->select(DB::raw(1))
                ->from('programation_macro')
                ->whereRaw('programation.id = programation_macro.programation_id')
				->whereRaw('programation_macro.quantity > 0');
        })->where(function($query) use ($name) {
            $query->where('code', 'like', '%'. $name .'%')
                ->orWhere(function ($q2) use ($name) {
                    $q2->whereHas('client', function($q3) use ($name) {
                       $q3->where('client.code', 'like', '%'. $name .'%')
                           ->orWhere('identity', 'like', '%'. $name .'%')
                           ->orWhere('company_name', 'like', '%'. $name .'%');
                    });
                });
        })->where('request_salesman_id',$request->session()->get('salesman_data')->id)
            ->where('has_analyze', 0)
            ->where('is_cancelled', 0)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->code .' ('. $key->months .') '. $key->client->company_name .' ('.$key->client->code.') '.$key->client->identity;

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

    public function listProgramationMonth(Request $request) {

        $data = App\Model\Commercial\ProgramationMonth::whereHas('programation', function($q) use ($request) {
            $q->where('request_salesman_id', $request->session()->get('salesman_data')->id)
                ->where('has_analyze', 0)
                ->where('is_cancelled', 0);
        })->whereRaw('(SELECT MAX(programation_macro.quantity) 
			FROM programation_macro 
			WHERE programation_month.programation_id = programation_macro.programation_id 
			AND programation_month.yearmonth = programation_macro.yearmonth) > 0')
			->where('programation_id', $request->id)
			->where('version', function ($sub) {
				$sub->select(DB::raw('max(version)'))
					->from('programation_version')
					->whereRaw('programation_month.programation_id = programation_version.programation_id')
					->whereRaw('programation_version.is_approv = 1');
			})
			->paginate(10);

        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = date('Y-m', strtotime($key->yearmonth));

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

    public function listClientSameGroup(Request $request) {

        $client = Client::find($request->id);

        $data = Client::whereHas('client_group', function($q) use ($client) {
            $q->where('client_group.id', $client->group->id);
        })->where('id', '!=', $client->id)
            ->where(function($sub) use ($request){
                $sub->where('code', 'LIKE', '%'. $request->search .'%')
                    ->orWhere('identity', 'LIKE', '%'. $request->search .'%')
                    ->orWhere('fantasy_name', 'LIKE', '%'. $request->search .'%');
            })
            ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->fantasy_name .' ('. $key->identity .')';
                $row['address'] = $key->address;
                $row['state'] = $key->state;
                $row['city'] = $key->city;
                $row['district'] = $key->district;
                $row['zipcode'] = $key->zipcode;

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

    //lembrete :criar comparativo para listar listar somente os dados do usuario da sessão
    public function saveProfile(FormRequestSaveSalesmanProfile $request) {

        $profile_type = $request->get('profile_type');

        if($request->has('tab')){
            $request->session()->flash('tab', $request->tab);
        }

        $salesman_session = $request->session()->get('salesman_data');

        $salesman = \App\Model\Commercial\Salesman::find($salesman_session->id);
        if ($salesman) {


            if($profile_type == 3 && !empty($request->current_password)) {
                if (Hash::check($request->current_password, $salesman->password)) {
					$pass = Hash::make($request->new_password);
                    $salesman->password = $pass;
					if ($salesman->r_code) {
						$user = DB::connection('mysql')->table('users')->where('r_code', $salesman->r_code)->first();
						if ($user) {
							$user->password = $pass;
							$user->save();
						}
					}
                } else {
                    return redirect()->back()->withErrors(['current_password' => 'A senha informada não confere com sua senha atual'])->withInput();
                }
            }

            $salesman->fill($request->except('identity','password','otpauth'));

            $salesman->save();
            return redirect()->back()->with('success', 'Dados atualizados com sucesso!');

        } else {
            return redirect('/comercial/operacao/tabela/preco')->with('error', 'Usuário não encontrado!');
        }

    }

    public function active2FAUser(Request $request) {

        $user = Salesman::find($request->session()->get('salesman_data')->id);

        if ($user) {

            if ($request->active_otp == 1) {
                $code = $user->id .''. rand(1000, 9999);

                // get first letter of each word
                $words = explode(" ", $user->last_name);
                $lastname = "";

                $i = 0;
                $len = count($words);
                foreach ($words as $w) {

                    if (strlen($w) > 2) {
                        if ($i == $len - 1) {
                            $lastname .= $w[0];
                        } else {
                            $lastname .= $w[0] .".";
                        }
                    } else {
                        $i++;
                        continue;
                    }
                    $i++;
                }

                $source = array('(', ')', ' ');
                $replace = array('', '', '');
                $fname = str_replace($source, $replace, $user->first_name);
                $name = remove_accents($fname ."". strtoupper($lastname));
                $result = optAuthGoogleAuthentication($code, $name, 'gree.representante');

                return response()->json([
                    "success" => true,
                    "otpauth" => $result->secret,
                    "html" => $result->qr,
                ], 200);

            } else {

                $user->otpauth = null;
                $user->save();

                return response()->json([
                    'success' => true,
                    'html' => ""
                ], 200);
            }
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Não foi possí­vel encontrar o representante.'
            ], 400);
        }

    }


    public function verifyOtpAuth(Request $request) {

        if ($request->pin) {

            $result = optAuthGoogleAuthenticationVerify($request->session()->get('sal_otpauth'), $request->pin);

            if ($result == 'true') {

                $salesman = Salesman::where('code', $request->session()->get('sal_code'))->first();

                LogSystem("Representante conseguiu realizar o acesso com autenticador.", $salesman->code);
                $request->session()->put('salesman_data', $salesman);

                $salesman->retry = 3;
                $salesman->save();

                if ($request->session()->get('url')) {
                    $url = $request->session()->get('url');
                    $request->session()->put('url', '');

                    return response()->json([
                        'success' => true,
                        'url' => $url,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => true,
                        'url' => '/comercial/operacao/dashboard',
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Seu código de autenticação é inválido!'
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Campo "Código de autenticação" é obrigatório!'
            ], 200);
        }
    }
	
	public function verifyOtpAuthDashboard(Request $request) {

        if ($request->code_auth) {

            $result = optAuthGoogleAuthenticationVerify($request->secret, $request->code_auth);
            if ($result == 'true') {

                $salesman = Salesman::find($request->session()->get('salesman_data')->id);
                $salesman->otpauth = $request->secret;
                $salesman->save();

                $request->session()->put('sal_otpauth', $request->secret);
                LogSystem("Representante conseguiu realizar o acesso com autenticador.", $salesman->code);
                
                return redirect()->back()->with('success', 'Autenticado com sucesso!');
            }
            else {
                return redirect()->back()->with('error', 'Seu código de autenticação é inválido!');
            }
        }    
    }   

    public function salesmanLoginVerify(Request $request) {

        $salesman = Salesman::where('identity', $request->identity)->first();
		
		/*if($salesman->type_people == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Está desativado temporariamente o acesso'
            ], 200);
        }*/ 

        if ($salesman) {

            if (Hash::check($request->password, $salesman->password)) {

                if ($salesman->is_active == 0) {

                    LogSystem("Representante bloqueado pela administração, está tentando acessar o sistema.", $salesman->code);
                    return response()->json([
                        'success' => false,
                        'message' => 'Você foi bloqueado, entre em contato com administrador.'
                    ], 200);

                } else {

                    if ($salesman->otpauth == null) {

                        LogSystem("Colaborador conseguiu realizar o acesso.", $salesman->code);
                        $request->session()->put('salesman_data', $salesman);

                        $salesman->retry = 3;
                        $salesman->save();

                        if ($request->session()->get('url')) {
                            $url = $request->session()->get('url');
                            $request->session()->put('url', '');

                            return response()->json([
                                'success' => true,
                                'url' => $url,
                            ], 200);
                        } else {
                            return response()->json([
                                'success' => true,
                                'url' => '/comercial/operacao/dashboard',
                            ], 200);
                        }
                    } else {

                        $request->session()->put('sal_otpauth', $salesman->otpauth);
                        $request->session()->put('sal_code', $salesman->code);

                        return response()->json([
                            'success' => true,
                            'url' => ''
                        ], 200);
                    }
                }
            } else {

                if ($salesman->retry > 0) {
                    $salesman->retry = $salesman->retry - 1;

                    if ($salesman->retry == 0) {

                        $salesman->retry_time = date('Y-m-d H:i:s');
                        $salesman->is_active = 0;
                        $salesman->save();

                        // Write Log
                        LogSystem("Representante foi bloqueado no sistema por usar todas as suas tentativas.", $salesman->code);
                        return response()->json([
                            'success' => false,
                            'message' => 'Você foi bloqueado, entre em contato com administrador.'
                        ], 200);
                    } else {

                        $salesman->retry_time = date('Y-m-d H:i:s');
                        $salesman->save();

                        // Write Log
                        LogSystem("Representante tentou acessar e errou sua senha. Restou apenas ". $salesman->retry ." tentativa(s).", $salesman->code);
                        return response()->json([
                            'success' => false,
                            'message' => 'Login ou senha incorreta. Você tem '. $salesman->retry .' tentativa(s)'
                        ], 200);
                    }
                } else {

                    // Write Log
                    LogSystem("Colaborador está tentando acesso, mesmo já tendo sido bloqueado!", $salesman->code);
                    return response()->json([
                        'success' => false,
                        'message' => 'Você foi bloqueado, entre em contato com administrador.'
                    ], 200);
                }
            }
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Representante não foi encontrado no sistema.'
            ], 200);
        }
    }

    public function salesmanLoginForgotten(Request $request) {

        $salesman = Salesman::where('identity', $request->identity_f)->first();

        if ($salesman) {

            $new_pass = rand(1000, 9999);
            $salesman->password = Hash::make($new_pass);
            $salesman->save();

            $pattern = array(
                'title' => 'RECUPERAÇÃO DE CONTA: REPRESENTANTE',
                'description' => nl2br("Olá, <b>". $salesman->full_name ."</b><p>Seus dados de acesso constam abaixo: <br><br>Login: <b>". $salesman->identity ."</b><br>Senha: <b>". $new_pass ."</b><br>Caso preciso de ajuda, não deixe de entrar em contato.</p></br><hr><p>Para realizar acesso ao painel, click abaixo:</p><p><a target='_blank' href='". $request->root()."/comercial/operacao/login'>". $request->root()."/comercial/operacao/login</a></p>"),
                'template' => 'misc.DefaultExternal',
                'subject' => 'Gree - recuperação de conta',
            );

            SendMailJob::dispatch($pattern, $salesman->email);

            return response()->json([
                'success' => true,
                'message' => 'Foi enviado para o seu email os dados da sua conta.'
            ], 200);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível encontrar o representante desse CNPJ ou CPF.'
            ], 200);
        }
    }

    public function clientTimelineAnalyze(Request $request, $id) {
        $client = Client::with([
            'client_imdt_analyze',
            'client_revision_analyze',
            'client_judicial_analyze',
            'client_commercial_analyze',
            'client_financy_analyze',
            'client_version' => function($q) {
                $q->orderBy('id', 'DESC');
            }])
            ->where('id', $id)
            ->first();

        if (!$client)
            return Response()->json([ 'success' => false, 'msg' => 'Não foi possível encontrar o cliente.'], 200);

        $arr_imdt = $this->generatorClientAnalyze($client);

        return Response()->json([
            'imds' => $arr_imdt,
            'dir_commercial' => $this->generatorClientAnalyze($client, 0, 'Diretor Comercial', 'client_commercial_analyze', 20, 9),
            'dir_financy' => [],
            //'dir_judicial' => $this->generatorClientAnalyze($client, 0, 'Direção Juridica', 'client_judicial_analyze', 23),
			'dir_judicial' => [],
            'dir_revision' => $this->generatorClientAnalyze($client, 0, 'Revisão interna', 'client_revision_analyze', 20, 4),
			'who_cancel' => []
        ], 200);
    }

    public function orderTimelineAnalyze(Request $request, $id) {
        $order = OrderSales::with(
            'salesman',
            'orderImdAnalyze',
            'orderCommercialAnalyze',
            'orderFinancyAnalyze')
            ->where('id', $id)
            ->first();

        if (!$order)
            return Response()->json([ 'success' => false, 'msg' => 'Não foi possível encontrar o pedido.'], 200);

        $arr_imdt = $this->generatorOrderAnalyze($order)?? [];
		$arr_cancel = [];
        $who_cancel = $order->whoCancel();
        if ($who_cancel) {
            $arr_cancel = array(
                'user' => $who_cancel,
                'when' => date('d/m/y', strtotime($order->updated_at)),
                'description' => $order->cancel_reason
            );
        }

        return Response()->json([
            'imds' => $arr_imdt,
            'dir_commercial' => $this->generatorOrderAnalyze($order, 0, 'Diretor Comercial', 'orderCommercialAnalyze', 20, 9)?? [],
            'dir_financy' => [],
            'dir_revision' => [],
            'dir_judicial' => [],
			'who_cancel' => $arr_cancel
        ], 200);
    }
	
	public function programationTimelineAnalyze(Request $request, $id) {
        $programation = Programation::with('programationVersion')->where('id', $id)
            ->first();

        if (!$programation)
            return Response()->json([ 'success' => false, 'msg' => 'Não foi possível encontrar a programação.'], 200);

		$arr_cancel = [];
		$arr_mng = [];
		$arr_dir = [];
		if ($programation->is_cancelled) {
			$arr_cancel = array(
                'user' => Users::where('r_code', $programation->cancel_r_code)->first(),
                'when' => date('d/m/y', strtotime($programation->updated_at)),
                'description' => $programation->cancel_reason
            );
		} else {
			
			$is_reprov = false;
			$client_mng = DB::connection('commercial')->table('client_managers')->where('client_id', $programation->client_id)->first();
			$manager = Salesman::where('id', $client_mng->salesman_id)->first();

			$analyze_status = 1;
			$when = "";
			$office = $manager->office;
			if ($programation->coordinator_has_analyze) {
				if ($programation->programationVersion->is_reprov) {
					$analyze_status = 3;
					$when = date('d/m/Y', strtotime($programation->programationVersion->updated_at));
					$is_reprov = true;
				} else {
					$analyze_status = 2;
				}
					
			}
			$arr_mng = array(
                'user' => $manager,
                'analyze' => $analyze_status,
                'description' => $programation->programationVersion->description,
                'when' => $when,
                'office' => $office
            );
		
			
			if (!$is_reprov) {
				$direction = Salesman::where('id', '10')->first();
				
				$analyze_status = 1;
				$when = "";
				$office = $direction->office;
				if ($programation->manager_has_analyze) {
					if ($programation->programationVersion->is_reprov) {
						$analyze_status = 3;
						$is_reprov = true;
						$when = date('d/m/Y', strtotime($programation->programationVersion->updated_at));
					} else {
						$analyze_status = 2;
					}

				}

				$arr_dir = array(
					'user' => $direction,
					'analyze' => $analyze_status,
					'description' => $programation->programationVersion->description,
					'when' => $when,
					'office' => $office
				);
			}
			
		}

        return Response()->json([
            'imds' => [],
            'dir_commercial' => $arr_dir,
            'dir_financy' => [],
            'dir_revision' => $arr_mng,
            'dir_judicial' => [],
			'who_cancel' => $arr_cancel
        ], 200);
    }

    public function salesmanDashboard(Request $request) {
		
		$programation_open = ProgramationMacro::whereHas('programation', function($q) use ($request) {
			$q->ShowOnlyManager($request->session()->get('salesman_data')->id)
				->where('is_cancelled', 0);
		})->where('quantity', '>', 0)->groupBy('programation_id')->count();

        $client_analyze = Client::where('has_analyze', 1)->ShowOnlyManager($request->session()->get('salesman_data')->id)->count();

        $programation_analyze = Programation::where('has_analyze', 1)->where('is_cancelled', 0);
        if ($request->session()->get('salesman_data')->id != 10) {
			$programation_analyze->where('coordinator_has_analyze' , 0);
		} else {
			$programation_analyze->orWhere(function($q) {
				$q->where('coordinator_has_analyze' , 1)->where('has_analyze', 1)->where('is_cancelled', 0);
			});
		}
        $programation_analyze->ShowOnlyManager($request->session()->get('salesman_data')->id);

        $order_analyze = OrderSales::where('has_analyze', 1)->whereHas('client', function($sub) use ($request) {
			$sub->ShowOnlyManager($request->session()->get('salesman_data')->id);
		})->where('is_programmed', 1)->count();

        $order_not_prog_analyze = OrderSales::where('has_analyze', 1)->whereHas('client', function($sub) use ($request) {
			$sub->ShowOnlyManager($request->session()->get('salesman_data')->id);
		})->where('is_programmed', 0)->count();

        if ($request->session()->get('salesman_data')->id != 10) {
			$programation_analyze_mng = Programation::whereHas('client', function ($q) {
					$q->whereHas('manager_region')
						->whereRaw('(SELECT client_managers.salesman_id 
											FROM client_managers 
											WHERE client.id = client_managers.client_id 
											LIMIT 1) = '.\Session::get('salesman_data')->id.'
									');
				})  
            ->where('has_analyze', 1)
			->where('coordinator_has_analyze' , 0)
            ->where('is_cancelled', 0)->count();
			
		} else {
			$programation_analyze_mng = Programation::where('has_analyze', 1)
			->where('coordinator_has_analyze' , 1)
            ->where('is_cancelled', 0)->count();
		}

        $order_analyze_mng = OrderSales::where('has_analyze', 1)->where('is_programmed', 1)->ValidProcessImdt($request)
                        ->where('is_cancelled', 0)
						->where('salesman_imdt_approv', 0)
						->where('salesman_imdt_reprov', 0)->count();

        $order_not_prog_analyze_mng = OrderSales::where('has_analyze', 1)->where('is_programmed', 0)->ValidProcessImdt($request, 0)
                        ->where('is_cancelled', 0)
						->where('salesman_imdt_approv', 0)
						->where('salesman_imdt_reprov', 0)->count();               
                        
        $client_analyze_mng = Client::where('has_analyze', 1)->ValidProcessImdt($request)
                        ->where('is_active', 1)
						->where('salesman_imdt_approv', 0)
						->where('salesman_imdt_reprov', 0)->count();                

        if ($request->session()->get('salesman_data')->is_direction <= 1) {                
            $salesmans = [];
            $clients_all = Client::where('request_salesman_id', \Session::get('salesman_data')->id)->get();
        } else {
            $salesmans = Salesman::where('id', $request->session()->get('salesman_data')->id)->first()->subordinates()->get();
            $clients_all = Client::ShowOnlyManager($request->session()->get('salesman_data')->id)->get();
        }    

        return view('gree_commercial_external.dashboard', [
            'programation_open' => $programation_open,
            'client_analyze' => $client_analyze,
            'programation_analyze' => $programation_analyze->count(),
            'order_analyze' => $order_analyze,
            'order_not_prog_analyze' => $order_not_prog_analyze,
            'programation_analyze_mng' => $programation_analyze_mng,
            'order_analyze_mng' => $order_analyze_mng,
            'order_not_prog_analyze_mng' => $order_not_prog_analyze_mng,
            'client_analyze_mng' => $client_analyze_mng,
            'year_range' => range(2021, date('Y')),
            'clients_all' => $clients_all,
            'salesmans' => $salesmans
        ]); 
    }
	
	public function salesmanDashboardProgramation(Request $request) {

        try {

            if ($request->client_id) {
                $programation_macro = ProgramationMacro::with('programation')->whereHas('programation', function($q) use ($request) {
                    $q->where('client_id', $request->client_id)->where(function($query) use ($request) {
                        $query->ShowOnlyManager($request->session()->get('salesman_data')->id)
                            ->where('is_cancelled', 0);
                    });
                });
                
            } else {
                $pmacro_c = ProgramationMacro::with('programation.client')
                    ->whereHas('programation', function($q) use ($request) {
                        $q->ShowOnlyManager($request->session()->get('salesman_data')->id)
                            ->where('is_cancelled', 0);
                    })->first();
                if ($pmacro_c)
                    $client_id = $pmacro_c->programation->client->id;
                else
                    $client_id = 0;
                $programation_macro = ProgramationMacro::with('programation')->whereHas('programation', function($q) use ($request) {
                    $q->ShowOnlyManager($request->session()->get('salesman_data')->id)
                        ->where('is_cancelled', 0);
                });
            }
            
            if ($request->salesman_id) {
                $programation_macro->where('salesman_id', $request->salesman_id);
            }

            if ($request->year) {
                $programation_macro->whereRaw("YEAR(yearmonth) = '".$request->year."'");
            } else {
                $programation_macro->whereRaw("YEAR(yearmonth) = '".date('Y')."'");
            }

            $pmacro = $programation_macro->get();

            $months = $pmacro->unique('yearmonth')->pluck('yearmonth');

            $programation_uniq = $pmacro->unique('programation_id');

            $client_id = [];

            foreach ($programation_uniq as $val) {
                if (!in_array($val->programation->client_id, $client_id))
                    $client_id[] = $val->programation->client_id;
            }

            $category = SetProductGroup::with(['setProductOnGroup'=>function($q){
                $q->orderBy('position', 'ASC');
            }, 'setProductOnGroup.productAirEvap'])->withTrashed()->orderBy('position', 'ASC')->get();

            if ($request->session()->get('salesman_data')->is_direction <= 1) {
                $clients = Client::whereIn('id', $client_id)->get();
            } else {
                $clients = Client::ShowOnlyManager($request->session()->get('salesman_data')->id)->get();
            }

            if (count($months) > 0) {
                if ($request->client_id or $request->session()->get('salesman_data')->is_direction < 2)
                    return view('gree_commercial_external.dashboard.tableMacroClient', ['months' => $months, 'clients' => $clients,'cat_uniq' => $category,'category' => $category->toArray()]);
                else
                    return view('gree_commercial_external.dashboard.tableMacro', ['months' => $months, 'clients' => $clients,'cat_uniq' => $category,'category' => $category->toArray()]);
            } else {
                return "<h4>Não existe programação.</h4>";
            }

        } catch(\Exception $e) {
            return $e->getMessage();
        }    
    }

    public function salesmanDashboardExport(Request $request) {

		if ($request->client_id) {
			$programation_macro = ProgramationMacro::with('programation')->whereHas('programation', function($q) use ($request) {
				$q->where('client_id', $request->client_id)->where(function($query) use ($request) {
					$query->ShowOnlyManager($request->session()->get('salesman_data')->id)
                    	->where('is_cancelled', 0);
				});
			});
            
        } else {
			 $pmacro_c = ProgramationMacro::with('programation.client')
				 ->whereHas('programation', function($q) use ($request) {
					 $q->ShowOnlyManager($request->session()->get('salesman_data')->id)
						 ->where('is_cancelled', 0);
				 })->first();
			if ($pmacro_c)
				$client_id = $pmacro_c->programation->client->id;
			else
				$client_id = 0;
            $programation_macro = ProgramationMacro::with('programation')->whereHas('programation', function($q) use ($request) {
                $q->ShowOnlyManager($request->session()->get('salesman_data')->id)
                    ->where('is_cancelled', 0);
            });
        }

        if ($request->year) {
            $programation_macro->whereRaw("YEAR(yearmonth) = '".$request->year."'");
        } else {
            $programation_macro->whereRaw("YEAR(yearmonth) = '".date('Y')."'");
        }

        $pmacro = $programation_macro->get();
        $months = $pmacro->unique('yearmonth')->pluck('yearmonth');

        $programation_uniq = $pmacro->unique('programation_id');

        $client_id = [];

        foreach ($programation_uniq as $val) {
            if (!in_array($val->programation->client_id, $client_id))
                $client_id[] = $val->programation->client_id;
        }

        $category = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }, 'setProductOnGroup.productAirEvap'])->withTrashed()->orderBy('position', 'ASC')->get();

        if ($request->session()->get('salesman_data')->is_direction <= 1) {
            $clients = Client::whereIn('id', $client_id)->get();
        } else {
            $clients = Client::ShowOnlyManager($request->session()->get('salesman_data')->id)->get();
        }

        if ($request->client_id) {
            $view = 'gree_commercial.programation.tableMacroClient';
        } else {
            $view = 'gree_commercial.programation.tableMacro';
        }

        $pattern = [
            'view' => $view,
            'sheet_title' => 'Proposta cliente',
            'months' => $months,
            'clients' => $clients,
            'cat_uniq' => $category,
            'category' => $category->toArray()
        ];

        ob_end_clean();
        if ($months->count() > 0) {
            return Excel::download(new DefaultHtmlExport($pattern), 'ProgramationMacroExport-'. date('Y-m-d') .'.xlsx');
        } else {
            return redirect()->back()->with('error', 'NÃO EXISTE PROGRAMAÇÃO');
        }            
		
    }
	
			
	public function commercialBudgetList(Request $request) {

        $verbs = App\Model\Commercial\BudgetCommercial::with([
                'salesman',
				'client',
				'budget_commercial_report',
				'budget_commercial_attach' => function($q) {
					$q->where('type_document', 2);
				}])
            ->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'name',
            'identity',
        ]);

        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code"){
                    $verbs->where('code', $value_filter);
                }
                if($name_filter == $filter_session[1]."name"){
                    $verbs->whereHas('client', function($q) use ($value_filter) {
                       $q->where('company_name', 'like', '%'.$value_filter.'%');
                    });
                }
                if($name_filter == $filter_session[1]."identity"){
                    $verbs->whereHas('client', function($q) use ($value_filter) {
                        $q->where('identity', 'like', '%'.$value_filter.'%');
                    });
                }
            }
        }

        return view('gree_commercial_external.commercial_budget.list', [
            'verbs' => $verbs->paginate(10)
        ]);
    }

    public function commercialBudgetListApprov(Request $request) {

        $r_code = $request->session()->get('salesman_data')->r_code?? '0';

        $verbs = App\Model\Commercial\BudgetCommercial::with([
                'salesman',
				'client',
				'budget_commercial_report',
				'budget_commercial_attach' => function($q) {
					$q->where('type_document', 2);
				}])->ValidAnalyzeProccess($r_code)->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'name',
            'identity',
        ]);

        putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code"){
                    $verbs->where('code', $value_filter);
                }
                if($name_filter == $filter_session[1]."name"){
                    $verbs->whereHas('client', function($q) use ($value_filter) {
                        $q->where('company_name', 'like', '%'.$value_filter.'%');
                    });
                }
                if($name_filter == $filter_session[1]."identity"){
                    $verbs->whereHas('client', function($q) use ($value_filter) {
                        $q->where('identity', 'like', '%'.$value_filter.'%');
                    });
                }
            }
        }

        return view('gree_commercial_external.commercial_budget.approv', [
            'verbs' => $verbs->paginate(10)
        ]);
    }

    public function commercialBudgetAnalyze(Request $request, $id) {

        $r_code = $request->session()->get('salesman_data')->r_code?? '0';

        $budget = App\Model\Commercial\BudgetCommercial::with(
            'salesman',
			'client',
            'budget_commercial_duplicates',
            'budget_commercial_itens',
            'budget_commercial_report'
        )->ValidAnalyzeProccess($r_code)
            ->where('id', $id)
            ->first();

        if (!$budget)
            return redirect()->back()->with('error', 'Você não tem permissão para visualizar essa solicitação.');

        return view('gree_commercial_external.commercial_budget.analyze', [
            'budget' => $budget
        ]);
    }

    public function commercialBudgetNew(Request $request) {

        $reportInvoices = App\Model\Commercial\BudgetCommercialReport::where('request_salesman_id', $request->session()->get('salesman_data')->id)
			->where('status', 2)
            ->get();

        $reportVPC = $reportInvoices->where('type_report', 1);
        $reportRebate = $reportInvoices->where('type_report', 2);

        $clients = Client::where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->orderBy('company_name', 'ASC')->get();

        return view('gree_commercial_external.commercial_budget.new', [
            'reportVPC' => $reportVPC,
            'reportRebate' => $reportRebate,
            'clients' => $clients,
        ]);
    }

    public function commercialBudgetEdit(Request $request, $id) {

        $budget = App\Model\Commercial\BudgetCommercial::with(
            'budget_commercial_attach',
            'budget_commercial_duplicates',
            'budget_commercial_itens',
            'budget_commercial_report',
            'client'
        )->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $id)->first();

        if (!$budget)
            return redirect()->back()->with('error', 'Sua solicitação não foi encontrado na base de dados.');

        $reportInvoices = App\Model\Commercial\BudgetCommercialReport::where('request_salesman_id', $request->session()->get('salesman_data')->id)
			->where('status', 2)
            ->get();

        $reportVPC = $reportInvoices->where('type_report', 1);
        $reportRebate = $reportInvoices->where('type_report', 2);

        $clients = Client::where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->orderBy('company_name', 'ASC')->get();

        return view('gree_commercial_external.commercial_budget.edit', [
            'reportVPC' => $reportVPC,
            'reportRebate' => $reportRebate,
            'clients' => $clients,
            'budget' => $budget,
            'id' => $id,
        ]);
    }

    public function commercialBudgetProofUpload(Request $request) {
		
        $budget = App\Model\Commercial\BudgetCommercial::with(['budget_commercial_attach' => function ($q) {
            $q->where('type_document', 1);
        }])->where('id', $request->budget_id)->first();

        if (!$budget)
            return response()->json(['success' => false,'msg' => 'Sua solicitação não foi encontrado na base de dados.',]);

        $Attach = new App\Model\Commercial\BudgetCommercialAttach;
        $response = $this->uploadS3(1, $request->budget_file, $request);
        if ($response['success']) {
            $Attach->url = $response['url'];
        } else {
            return redirect()->back()->with('error', 'Não foi possível fazer upload do arquivo!');
        }

        $Attach->size = $request->file('budget_file')->getSize();
        $Attach->name = $request->file('budget_file')->getClientOriginalName();
        $Attach->budget_commercial_id = $request->budget_id;
        $Attach->type_document = 1;
        $Attach->save();

        $budget->load(['budget_commercial_attach' => function ($q) {
            $q->where('type_document', 1);
        }]);

        return response()->json([
            'success' => true,
            'data' => $budget->budget_commercial_attach,
        ]);

    }

    public function commercialBudgetProofRemove(Request $request) {

        $budget = App\Model\Commercial\BudgetCommercial::with(['budget_commercial_attach' => function ($q) {
            $q->where('type_document', 1);
        }])
            ->where('id', $request->budget_id)->first();

        if (!$budget)
            return response()->json(['success' => false,'msg' => 'Sua solicitação não foi encontrado na base de dados.',]);


        $file = $budget->budget_commercial_attach->where('id', $request->attach_id)->first();

        if (!$file)
            return response()->json(['success' => false,'msg' => 'Arquivo não foi encontrado.',]);

        $this->removeS3($file->url);
        $file->delete();

        $budget->load(['budget_commercial_attach' => function ($q) {
            $q->where('type_document', 1);
        }]);

        return response()->json([
            'success' => true,
            'data' => $budget->budget_commercial_attach,
        ]);

    }

    public function commmercialBudgetProofConfirm(Request $request) {
        $budget = App\Model\Commercial\BudgetCommercial::with('salesman.immediate_boss', 'budget_commercial_attach')
            ->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $request->budget_id)
            ->where('waiting_assign', 1)
            ->where('is_cancelled', 0)
            ->first();

        if (!$budget)
            return redirect()->back()->with('error', 'Sua solicitação de verbas não foi encontrado na base de dados.');

        if ($budget->budget_commercial_attach->where('type_document', 1)->count() == 0)
            return redirect()->back()->with('error', 'Você precisa anexar ao menos 1 arquivo para poder validar a comprovação.');

        $response = $this->fileManagerSVR([
            'budget_id' => $budget->id
        ], 'api/v1/commercial/budget/files/to/pdf');

        if (!$response->success)
            return redirect()->back()->with('error', $response->msg);

        $budget->waiting_assign = 0;
        $budget->url_view_proof = $response->url;
        $budget->save();

        return redirect()->back()->with('success', 'A solicitação foi comprovada, agora é possível enviar para análise.');
    }

    public function commercialBudgetSendAnalyze(Request $request, $id) {

        $budget = App\Model\Commercial\BudgetCommercial::with('salesman.immediate_boss')
            ->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $id)
            ->where('waiting_assign', 0)
            ->where('is_cancelled', 0)
            ->first();

        if (!$budget)
            return redirect()->back()->with('error', 'Sua solicitação de verbas não foi encontrado na base de dados.');

        try {

            $solicitation = new App\Services\Departaments\Commercial\RequestBudget($budget, $request);
            $do_analyze = new App\Services\Departaments\ProcessAnalyze($solicitation);
            $do_analyze->eventStart();

            return redirect()->back()->with('success', 'Sua solicitação de verbas foi enviado para análise com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function commercialBudgetDoAnalyze(Request $request) {

        $r_code = $request->session()->get('salesman_data')->r_code?? '0';

        $budget = App\Model\Commercial\BudgetCommercial::with('salesman.immediate_boss')
            ->ValidAnalyzeProccess($r_code)
            ->where('id', $request->id)
            ->where('waiting_assign', 0)
            ->where('is_cancelled', 0)
            ->first();

        if (!$budget)
            return redirect()->back()->with('error', 'Sua solicitação de verbas não foi encontrado na base de dados.');

        try {

            $solicitation = new App\Services\Departaments\Commercial\RequestBudget($budget, $request);
            $do_analyze = new App\Services\Departaments\ProcessAnalyze($solicitation);

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

    public function commercialBudgetCancel(Request $request) {

        $budget = App\Model\Commercial\BudgetCommercial::with('salesman.immediate_boss')
            ->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $request->id)
            ->where('is_cancelled', 0)
            ->first();

        if (!$budget)
            return redirect()->back()->with('error', 'Sua solicitação de verbas não foi encontrado na base de dados.');

        $budget->is_cancelled = 1;
        $budget->cancel_reason = $request->cancel_reason;
        $budget->cancel_salesman_id = $request->session()->get('salesman_data')->id;
        $budget->has_analyze = 0;
        $budget->save();

        return redirect()->back()->with('success', 'Sua solicitação foi cancelada com sucesso!');
    }

    public function commercialBudgetSaveNew(Request $request) {

        $report = null;
        if ($request->type_budget == 1) {
            $report = App\Model\Commercial\BudgetCommercialReport::find($request->reportVPC);
            if (!$report)
                return redirect()->back()->with('error', 'Apuração não existe na base de dados');

        } else if ($request->type_budget == 2) {
            $report = App\Model\Commercial\BudgetCommercialReport::find($request->reportRebate);
            if (!$report)
                return redirect()->back()->with('error', 'Apuração não existe na base de dados');
        }

        $client = Client::with('salesman', 'client_peoples_contact')->find($request->client_id);

        if (!$client)
            return redirect()->back()->with('error', 'O cliente não existe na base de dados');

        $verb = new App\Model\Commercial\BudgetCommercial;
        if ($request->type_request == 3)
            $verb->code = getCodeModule('order_commercial_vdc', '', 1);
        else
            $verb->code = getCodeModule('order_commercial_vpc', '', 1);

        $verb->request_salesman_id = $request->session()->get('salesman_data')->id;
        $verb->client_code = $client->code;
        $verb->client_id = $client->id;
        $verb->client_fantasy_name = $client->fantasy_name;
        $verb->client_company_name = $client->company_name;
        $verb->client_identity = $client->identity;
        $verb->client_state_registration = $client->state_registration;
        $verb->client_peoples_contact_phone = $client->peoples_contact_phone;
        if ($client->client_peoples_contact->count()) {
            if ($client->client_peoples_contact->where('type_contact', 2)->first()) {
                $financy = $client->client_peoples_contact->where('type_contact', 2)->first();
                $verb->client_peoples_contact_phone = $financy->phone;
            }
        }
        $verb->client_address = $client->address;
        $verb->client_district = $client->district;
        $verb->client_zipcode = $client->zipcode;
        $verb->client_city = $client->city;
        $verb->client_state = $client->state;

        $verb->type_documents = $request->type_document;
        $verb->type_payment = $request->type_request;
        $verb->type_budget = $request->type_budget;

        if ($request->type_request == 3) {
            $verb->transf_nf = $request->nf;
            $verb->transf_bank = $request->bank;
            $verb->transf_agency = $request->agency;
            $verb->transf_account = $request->account;
            $verb->transf_people_name = $request->people_name;
            $verb->transf_identity = $request->identity;
            $source = array('.', ',');
            $replace = array('', '.');
            $gross = str_replace($source, $replace, $request->total_gross_payment);
            $verb->total_gross_payment = $gross;
            $liquid = str_replace($source, $replace, $request->total_liquid_payment);
            $verb->total_liquid_payment = $liquid;
        }

        $verb->observation = $request->observation;
        $verb->waiting_assign = 1;
        $verb->save();

        if ($report) {
            $report->budget_commercial_id = $verb->id;
            $report->save();
        }

        if ($request->type_request == 2) {
            if (count($request->duplicate_col_1)) {
                foreach ($request->duplicate_col_1 as $index => $val) {
                    if ($val) {
                        $add = new App\Model\Commercial\BudgetCommercialDuplicates;
                        $add->budget_commercial_id = $verb->id;
                        $add->nf_number = $val;
                        $add->nf_serie = $request->duplicate_col_2[$index] ?? 2;
                        $add->parcel_number = $request->duplicate_col_3[$index] ?? null;
                        if (isset($request->duplicate_col_4[$index])) {
                            $date = str_replace('/', '-', $request->duplicate_col_4[$index]);
                            $add->due_date = date('Y-m-d', strtotime($date));
                        }
                        if (isset($request->duplicate_col_5[$index]))
                            $add->parcel_price = $this->convertRealToFloat($request->duplicate_col_5[$index]);

                        if (isset($request->duplicate_col_6[$index]))
                            $add->price_descoint = $this->convertRealToFloat($request->duplicate_col_6[$index]);

                        $add->save();
                    }
                }
            }
        }

        if (count($request->request_col_1)) {
            foreach ($request->request_col_1 as $index => $val) {
                if ($val) {
                    $add = new App\Model\Commercial\BudgetCommercialItens;
                    $add->budget_commercial_id = $verb->id;
                    $add->description = $val;
                    $add->quantity = $request->request_col_2[$index] ?? null;
                    $add->unity = $request->request_col_3[$index] ?? null;
                    if (isset($request->request_col_4[$index]))
                        $add->price_unit = $this->convertRealToFloat($request->request_col_4[$index]);

                    if (isset($request->request_col_5[$index]))
                        $add->sub_total = $this->convertRealToFloat($request->request_col_5[$index]);

                    $add->save();
                }
            }
        }

        if (count($request->type_document_upload)) {
            foreach ($request->type_document_upload as $index => $val) {
                $add = new App\Model\Commercial\BudgetCommercialAttach;
                $add->budget_commercial_id = $verb->id;
                $add->type_document = 2;
                $add->name = $val->getClientOriginalName();
                $add->size = $val->getSize();
                $response = $this->uploadS3($index, $val, $request);
                if ($response['success']) {
                    $add->url = $response['url'];
                } else {
                    return redirect()->back()->with('error', 'Não foi possível fazer upload do arquivo!');
                }
                $add->save();
            }
        }

        return redirect('/comercial/operacao/verba-comercial/todos')->with('success', 'Solicitação de verba criada com sucesso!');

    }

    public function commercialBudgetSaveEdit(Request $request) {

        $report = null;
        if ($request->type_budget == 1) {
            $report = App\Model\Commercial\BudgetCommercialReport::find($request->reportVPC);
            if (!$report)
                return redirect()->back()->with('error', 'Apuração não existe na base de dados');

        } else if ($request->type_budget == 2) {
            $report = App\Model\Commercial\BudgetCommercialReport::find($request->reportRebate);
            if (!$report)
                return redirect()->back()->with('error', 'Apuração não existe na base de dados');
        }

        if ($report)
            $client = Client::with('salesman', 'client_peoples_contact')->find($report->client_id);
        else
            $client = Client::with('salesman', 'client_peoples_contact')->find($request->client_id);

        if (!$client)
            return redirect()->back()->with('error', 'O cliente não existe na base de dados');

        $verb = App\Model\Commercial\BudgetCommercial::where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $request->id)
            ->first();

        if (!$verb)
            return redirect()->back()->with('error', 'Sua solicitação não foi encontrado na base de dados.');

        $verb->client_code = $client->code;
        $verb->client_id = $client->id;
        $verb->client_fantasy_name = $client->fantasy_name;
        $verb->client_company_name = $client->company_name;
        $verb->client_identity = $client->identity;
        $verb->client_state_registration = $client->state_registration;
        $verb->client_peoples_contact_phone = $client->peoples_contact_phone;
        if ($client->client_peoples_contact->count()) {
            if ($client->client_peoples_contact->where('type_contact', 2)->first()) {
                $financy = $client->client_peoples_contact->where('type_contact', 2)->first();
                $verb->client_peoples_contact_phone = $financy->phone;
            }
        }
        $verb->client_address = $client->address;
        $verb->client_district = $client->district;
        $verb->client_zipcode = $client->zipcode;
        $verb->client_city = $client->city;
        $verb->client_state = $client->state;

        $verb->type_documents = $request->type_document;
        $verb->type_payment = $request->type_request;
        $verb->type_budget = $request->type_budget;

        if ($request->type_request == 3) {
            $verb->transf_nf = $request->nf;
            $verb->transf_bank = $request->bank;
            $verb->transf_agency = $request->agency;
            $verb->transf_account = $request->account;
            $verb->transf_people_name = $request->people_name;
            $verb->transf_identity = $request->identity;
            $verb->total_gross_payment = $request->total_gross_payment;
            $verb->total_liquid_payment = $request->total_liquid_payment;
        }

        $verb->observation = $request->observation;
        $verb->save();

        if ($report) {
            $report->budget_commercial_id = $verb->id;
            $report->save();
        }

        if ($request->type_request == 2) {
            if (count($request->duplicate_col_1)) {
                App\Model\Commercial\BudgetCommercialDuplicates::where('budget_commercial_id', $verb->id)->delete();
                foreach ($request->duplicate_col_1 as $index => $val) {
                    if ($val) {
                        $add = new App\Model\Commercial\BudgetCommercialDuplicates;
                        $add->budget_commercial_id = $verb->id;
                        $add->nf_number = $val;
                        $add->nf_serie = $request->duplicate_col_2[$index] ?? 2;
                        $add->parcel_number = $request->duplicate_col_3[$index] ?? null;
                        if (isset($request->duplicate_col_4[$index])) {
                            $date = str_replace('/', '-', $request->duplicate_col_4[$index]);
                            $add->due_date = date('Y-m-d', strtotime($date));
                        }
                        if (isset($request->duplicate_col_5[$index]))
                            $add->parcel_price = $this->convertRealToFloat($request->duplicate_col_5[$index]);

                        if (isset($request->duplicate_col_6[$index]))
                            $add->price_descoint = $this->convertRealToFloat($request->duplicate_col_6[$index]);

                        $add->save();
                    }
                }
            }
        }

        if (count($request->request_col_1)) {
            App\Model\Commercial\BudgetCommercialItens::where('budget_commercial_id', $verb->id)->delete();
            foreach ($request->request_col_1 as $index => $val) {
                if ($val) {
                    $add = new App\Model\Commercial\BudgetCommercialItens;
                    $add->budget_commercial_id = $verb->id;
                    $add->description = $val;
                    $add->quantity = $request->request_col_2[$index] ?? null;
                    $add->unity = $request->request_col_3[$index] ?? null;
                    if (isset($request->request_col_4[$index]))
                        $add->price_unit = $this->convertRealToFloat($request->request_col_4[$index]);

                    if (isset($request->request_col_5[$index]))
                        $add->sub_total = $this->convertRealToFloat($request->request_col_5[$index]);

                    $add->save();
                }
            }
        }

        if ($request->hasFile('type_document_upload')) {
            if (count($request->type_document_upload)) {
                App\Model\Commercial\BudgetCommercialAttach::where('budget_commercial_id', $verb->id)->delete();
                foreach ($request->type_document_upload as $index => $val) {
                    $add = new App\Model\Commercial\BudgetCommercialAttach;
                    $add->budget_commercial_id = $verb->id;
                    $add->type_document = 2;
                    $add->name = $val->getClientOriginalName();
                    $add->size = $val->getSize();
                    $response = $this->uploadS3($index, $val, $request);
                    if ($response['success']) {
                        $add->url = $response['url'];
                    } else {
                        return redirect()->back()->with('error', 'Não foi possível fazer upload do arquivo!');
                    }
                    $add->save();
                }
            }
        }

        return redirect('/comercial/operacao/verba-comercial/todos')->with('success', 'Solicitação de verba atualizada com sucesso!');

    }

    public function commercialBudgetPrint(Request $request, $id) {

        $budget = App\Model\Commercial\BudgetCommercial::with(
            'salesman',
			'client',
            'budget_commercial_duplicates',
            'budget_commercial_itens',
            'budget_commercial_report'
        )->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $id)
            ->first();

        if (!$budget) {
            $r_code = $request->session()->get('salesman_data')->r_code?? '0';

            $budget = App\Model\Commercial\BudgetCommercial::with(
                'salesman',
				'client',
                'budget_commercial_duplicates',
                'budget_commercial_itens',
                'budget_commercial_report'
            )->ValidAnalyzeProccess($r_code)
                ->where('id', $id)
                ->first();

            if (!$budget)
                return redirect()->back()->with('error', 'Você não tem permissão para visualizar essa solicitação.');
        }

        return view('gree_commercial.operation.reportInvoice.printRequestVerbaCommercial', [
            'budget' => $budget,
        ]);
    }

    public function commercialBudgetCreditPrint(Request $request, $id) {

        $budget = App\Model\Commercial\BudgetCommercial::with(
            'salesman',
			'client',
            'budget_commercial_duplicates',
            'budget_commercial_itens',
            'budget_commercial_report',
            'rtd_analyze.users'
        )->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $id)
            ->first();

        if (!$budget) {
            $r_code = $request->session()->get('salesman_data')->r_code?? '0';

            $budget = App\Model\Commercial\BudgetCommercial::with(
                'salesman',
                'budget_commercial_duplicates',
                'budget_commercial_itens',
                'budget_commercial_report',
                'client',
                'rtd_analyze.users'
            )->ValidAnalyzeProccess($r_code)
                ->where('id', $id)
                ->first();

            if (!$budget)
                return redirect()->back()->with('error', 'Você não tem permissão para visualizar essa solicitação.');
        }

        return view('gree_commercial.operation.reportInvoice.printRequestVerbaCommercialCredit', [
            'budget' => $budget,
        ]);
    }

    public function commercialBudgetPaymentPrint(Request $request, $id) {

        $budget = App\Model\Commercial\BudgetCommercial::with(
            'salesman',
			'client',
            'budget_commercial_duplicates',
            'budget_commercial_itens',
            'budget_commercial_report',
            'rtd_analyze.users'
        )->where('request_salesman_id', $request->session()->get('salesman_data')->id)
            ->where('id', $id)
            ->first();

        if (!$budget) {
            $r_code = $request->session()->get('salesman_data')->r_code?? '0';

            $budget = App\Model\Commercial\BudgetCommercial::with(
                'salesman',
                'budget_commercial_duplicates',
                'budget_commercial_itens',
                'budget_commercial_report',
                'client',
                'rtd_analyze.users'
            )->ValidAnalyzeProccess($r_code)
                ->where('id', $id)
                ->first();

            if (!$budget)
                return redirect()->back()->with('error', 'Você não tem permissão para visualizar essa solicitação.');
        }

        return view('gree_commercial.operation.reportInvoice.printRequestVerbaCommercialPayment', [
            'budget' => $budget,
        ]);
    }

}
