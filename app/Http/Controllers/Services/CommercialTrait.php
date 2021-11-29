<?php

namespace App\Http\Controllers\Services;

use App\Model\Commercial\Client;
use App\Model\Commercial\ClientVersion;
use App\Model\Commercial\OrderAvaibleMonth;
use App\Model\Commercial\OrderSales;
use App\Model\Commercial\ProgramationMacro;
use App\Model\Commercial\ProgramationMonth;
use App\Model\Commercial\Salesman;
use App\Model\UserOnPermissions;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Trait CommercialTrait
{
    public function renderViewClient(Model $client) {

        $header = DB::connection('commercial')
            ->table('settings')
            ->where('type', 1)
            ->get();

        return view('gree_commercial.client.print', [
            'client' => $client,
            'header' => $header,
        ])->render();
    }

    public function updateFieldsClient(Model $client) {

        $json = DB::connection('commercial')->table('client_version')->where('client_id', $client->id)->orderBy('id', 'DESC')->first();

        if ($json->version != 1) {
            DB::connection('commercial')->table('client')->where('id', $client->id)->update($this->splitArray($json->inputs, 1));

            foreach ($this->splitArray($json->inputs, 2) as $key => $value) {

                if ($key != 'salesman' and $key != 'client_group' and $key != 'group') {
                    DB::connection('commercial')->table($key)->where('client_id', $client->id)->delete();
                    DB::connection('commercial')->table($key)->insert($value);
                }

            }
        }

        $version = ClientVersion::where('client_id', $client->id)->orderBy('id', 'DESC')->first();
        if ($version) {

            $print = Client::with(['client_peoples_contact',
                    'client_account_bank',
                    'client_main_suppliers',
                    'client_main_clients',
                    'client_group',
                    'client_on_group',
                    'client_on_product_sales',
                    'client_owner_and_partner',
                    'salesman',
                    'client_documents']
            )->where('id', $this->model->id)->first();

            $version->view = $this->renderViewClient($print);
            $version->save();
        }

        return true;
    }

    public function renderViewOrder($id) {

        $order = OrderSales::with(
            'orderImdAnalyze.salesman',
            'orderCommercialAnalyze.user',
            'orderFinancyAnalyze.user',
            'orderDelivery',
            'salesman',
            'user',
            'client.client_managers.salesman',
            'orderReceiver',
            'orderProducts.setProduct.productAirEvap',
            'programationMonth.programation'
        )->where('id', $id)->first();
        $header = collect(json_decode($order->json_header));
        $table = json_decode($order->programationMonth->json_table_price);

        return view('gree_commercial.orderSale.print', [
            'order' => $order,
            'header' => $header,
            'table' => $table
        ])->render();
    }

    public function renderViewOrderConfirmed($id) {

        $order = OrderSales::with(
            'orderImdAnalyze.salesman',
            'orderCommercialAnalyze.user',
            'orderFinancyAnalyze.user',
            'orderDelivery',
            'salesman',
            'user',
            'client.client_managers.salesman',
            'orderReceiver',
            'orderProducts.setProduct.productAirEvap'
        )->where('id', $id)->first();
        $header = collect(json_decode($order->json_header));
        $table = json_decode($order->json_table_price);

        return view('gree_commercial.orderSale.printConfirmed', [
            'order' => $order,
            'header' => $header,
            'table' => $table
        ])->render();
    }

    private function splitArray($json, $type = 1) {

        $json = json_decode($json, true);

        if (is_array($json)) {
            $client_fields = $json;
            $relations_fields = $json;

            if ($type == 1) {
                foreach ($client_fields as $index => $item) {

                    if (is_array($item))
                        unset($client_fields[$index]);

                }

                return $client_fields;
            } else {
                foreach ($relations_fields as $index => $item) {

                    if (!is_array($item))
                        unset($relations_fields[$index]);

                }

                return $relations_fields;
            }
        } else {
            return [];
        }

    }


    /**
     * Colocar na sessão os fatores disponiveis no mês para aplicar ao preço base dos conjuntos
     * Retorna os meses disponiveis para exibição
     * @param $date [Y-m-d]
     * @return Model
     */
    public function setSessionDatesAvaibles($date = null, $rule_free = false) {

        $settings = App\Model\Commercial\Settings::where('command', 'programation_last_day')->first();
        if (!$rule_free) {
            $date = $date == null ? date('Y-m-d') : $date;
            $query = OrderAvaibleMonth::where('date', '>=', date('Y-m-01', strtotime($date)));
            $months_all = $query->get();

            $months = $query->whereRaw('IF(DAY(CURDATE()) >= '. $settings->value .'
                        AND MONTH(date) = MONTH(CURDATE())
                        AND YEAR(date) = YEAR(CURDATE()), TRUE, FALSE) = 0')
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();
        } else {
            $date = $date == null ? date('Y-m-d') : $date;
            $query = OrderAvaibleMonth::whereYear('date', '=', date('Y', strtotime($date)));
            $months_all = $query->get();

            $months = $query->where('date', '>=', $date)
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();
        }


        $conditions = [];
        foreach ($months_all->pluck('date') as $key) {
			if (!$rule_free) {
				if (date('Y-m', strtotime($key)) == date('Y-m') and date('d') >= $settings->value)
					continue;
				else
					$conditions[date('Y-n-01', strtotime($key))] = [];
			} else {
				$conditions[date('Y-n-01', strtotime($key))] = [];
			}
        }

        foreach ($conditions as $d => $value) {

            $apply = OrderAvaibleMonth::where('date', '>=', date('Y-01-01', strtotime($date)))
                ->where('date', '<=', $d)
                ->get();

            $conditions[$d]['model'] = $apply;
            $conditions[$d]['factors'] = [];

        }

        \Session::put('commercial_months', $conditions);

        return $months;
    }

    /**
     * @param ProgramationMonth $programation_month [Precisa ter relação OrderSales.OrderProducts]
     * @param Collection $programation_macro
     * @param $category
     * @param $product
     * @param int $total_in_order
     * @return array
     */
    public function calcProgramationMacroQtd(ProgramationMonth $programation_month, Collection $programation_macro, $category, $product, int $total_in_order = 0) {

        foreach ($programation_month->OrderSales->where('is_approv', 0)->where('is_reprov', 0)->where('is_cancelled', 0) as $order) {
            $total_in_order += $order->OrderProducts->where('category_id', $category)->where('set_product_id', $product)->sum('quantity');
        }

        $p_in_macro = $programation_macro->where('set_product_id', $product)->where('category_id', $category)->first();
        $has_new_product = false;
        if ($p_in_macro) {
            $rest_total = $p_in_macro->quantity - $total_in_order;
            if ($p_in_macro->total == 0) {
                $has_new_product = true;
            }
        } else {
            $rest_total = 0;
        }

        return [
            'total' => $rest_total >= 0 ? $rest_total : 0,
            'is_negative' => $rest_total >= 0 ? false : true,
            'new_product' => $has_new_product
        ];
    }
	
	public function scopeClientCountAnalyze() {
		return Client::with(['client_group', 'client_version' => function ($q) {
            $q->orderBy('id', 'DESC')->withTrashed();
        }])->where('has_analyze', 1)
            ->where('salesman_imdt_approv', 1)
            ->where('salesman_imdt_reprov', 0)
            ->orderBy('id', 'DESC');
	}
	
	public function generatorOrderAnalyze(
	    OrderSales $order,
        $is_imd = 1,
        $office = null,
        $relation = null,
        $perm = null,
        $grade = null
    ) {

        $result = [];
        if ($is_imd) {
			$salesman_id = $order->request_salesman_id;
			$salesman = '';

			if(!$order->manual_order_sales) {
				// Montar array de gestores
				do {
					$data = Salesman::with('immediate_boss')->find($salesman_id);
					if ($data->immediate_boss->first()) {
						$salesman = $data->immediate_boss->first();
						if (!$salesman)
							return false;
						
						$salesman_id = $salesman->id;
						if ($salesman->is_direction != 2) {
							$analyze = null;
							if ($order->orderImdAnalyze->count() > 0)
								$analyze = $order->orderImdAnalyze->where('salesman_id', $salesman_id)->first();
							// 1 = Em análise, 2 = Aprovado, 3 = Reprovado.
							$analyze_status = 1;
							$when = '';
							$analyze_desc = '';
							$office = $salesman->office;
							if ($analyze) {
								$office = $analyze->office;
								$analyze_desc = $analyze->description ? $analyze->description : '';
								$when = date('d/m/Y H:i', strtotime($analyze->created_at));
								if ($analyze->is_approv == 1)
									$analyze_status = 2;
								else
									$analyze_status = 3;
							}
							$result[] = array(
								'user' => $salesman,
								'analyze' => $analyze_status,
								'when' => $when,
								'description' => $analyze_desc,
								'office' => $office
							);
						}

					}
				} while ($salesman->is_direction != 2);

				return $result;
			} else {
				return $result;
			}
        } else {

            $user_perm = UserOnPermissions::with('user')->where('perm_id', $perm)
                ->where('grade', $grade)
                ->where('can_approv', 1)
                ->first();

            $analyze = null;
            if ($order->$relation and $user_perm)
                $analyze = $order->$relation;
            // 1 = Em análise, 2 = Aprovado, 3 = Reprovado.
            $analyze_status = 1;
            $analyze_desc = '';
            $when = "";
            if ($analyze) {
				$user_perm = $analyze->load('user');
                $analyze_desc = $analyze->description ? $analyze->description : '';
                $when = date('d/m/Y H:i', strtotime($analyze->created_at));
                if ($analyze->is_approv == 1)
                    $analyze_status = 2;
                else
                    $analyze_status = 3;
            }

            return array(
                'user' => $user_perm ? $user_perm->user : '',
                'analyze' => $analyze_status,
                'description' => $analyze_desc,
                'when' => $when,
                'office' => $office
            );
        }
    }

    public function generatorClientAnalyze(
        Client $client,
        $is_imd = 1,
        $office = null,
        $relation = null,
        $perm = null,
        $grade = null
    ) {

        $version = $client->client_version->first();
        if ($version)
            $version = $version->version;
        else
            $version = 1;

        $result = [];
        if ($is_imd) {

            $salesman_id = $client->request_salesman_id;
            $salesman = '';

            // Montar array de gestores
            do {
                $data = Salesman::with('immediate_boss')->find($salesman_id);
				if($data) {
					if ($data->immediate_boss->first()) {
						$salesman = $data->immediate_boss->first();
						if (!$salesman) {
							return response()->json([
								'success' => false, 
								'msg' => 'Há um imediato na solicitação que não tem chefe imediato.'
							], 400);	
						}
							
						$salesman_id = $salesman->id;
						if ($salesman->is_direction != 2) {
							$analyze = null;
							if ($client->client_imdt_analyze->count() > 0)
								$analyze = $client->client_imdt_analyze->where('salesman_id', $salesman_id)->where('version', $version)->first();
							// 1 = Em análise, 2 = Aprovado, 3 = Reprovado.
							$analyze_status = 1;
							$analyze_desc = '';
							$when = "";
							$office = $salesman->office;
							if ($analyze) {
								$office = $analyze->office;
								$analyze_desc = $analyze->description ? $analyze->description : '';
								$when = date('d/m/Y H:i', strtotime($analyze->created_at));
								if ($analyze->is_approv == 1)
									$analyze_status = 2;
								else
									$analyze_status = 3;
							}
							$result[] = array(
								'user' => $salesman,
								'analyze' => $analyze_status,
								'when' => $when,
								'description' => $analyze_desc,
								'office' => $office
							);
						}

					}
				} else {

                    return response()->json([
                        'success' => false, 
                        'msg' => 'Você não tem um chefe imediato para continuar com a análise!'
                    ], 400);
                } 
            } while ($salesman->is_direction != 2);

            return $result;
        } else {

            $user_perm = UserOnPermissions::with('user')->where('perm_id', $perm);

            if ($grade)
                $user_perm->where('grade', $grade);

            $user = $user_perm->where('can_approv', 1)
                ->first();
			
            $analyze = null;
            if ($client->$relation and $user)
                $analyze = $client->$relation->where('client_id', $client->id)->where('version', $version)->first();
            // 1 = Em análise, 2 = Aprovado, 3 = Reprovado.
            $analyze_status = 1;
            $analyze_desc = '';
            $when = "";
            if ($analyze) {
				$user = $analyze->load('user');
                $analyze_desc = $analyze->description ? $analyze->description : '';
                $when = date('d/m/Y H:i', strtotime($analyze->created_at));
                if ($analyze->is_approv == 1)
                    $analyze_status = 2;
                else
                    $analyze_status = 3;
            }

            return array(
                'user' => $user ? $user->user : '',
                'analyze' => $analyze_status,
                'description' => $analyze_desc,
                'when' => $when,
                'office' => $office
            );
        }
    }
	
	/**
     * Cálculo VPC
     * 
     * 1 - líquido:
     *     Valor dedução = Valor total nfe - Valor ICMS - Valor PIS - Valor CONFINS
     *     Valor VPC = (Valor dedução * Contrato VPC(%)) / 100
     * 
     * 2 - Bruto: 
     *     Valor VPC = (Valor total nfe * Contrato VPC(%)) / 100
     */
    public function calculateVPC(
        $type_vpc, 
        $contract_vpc,
        $total_nfe,
        $total_icms = null, 
        $total_pis = null, 
        $total_confins = null
    ) {

        $total_vpc = 0;
        if($type_vpc == 1) {
            $total = $total_nfe - $total_icms - $total_pis - $total_confins;
            $total_vpc = ($total * $contract_vpc) / 100;
        }
        else {
            $total = $total_nfe;
            $total_vpc = ($total_nfe * $contract_vpc) / 100;
        }

        return [
            'total_vpc' => $total_vpc,
            'total' => $total
        ];
    }
	
	public function convertRealToFloat($price) {
        $source = array('.', ',');
        $replace = array('', '.');
        $total = str_replace($source, $replace, $price);
        return $total;
    }

}
