<?php

namespace App\Helpers\Commercial;

use App;
use App\Http\Controllers\Services\CommercialTrait;
use App\Jobs\SendMailJob;
use App\Model\Commercial\OrderCommercialAnalyze;
use App\Model\Commercial\OrderFinancyAnalyze;
use App\Model\Commercial\OrderImdtAnalyze;
use App\Model\Commercial\OrderSales;
use App\Model\Commercial\Settings;
use App\Model\UserOnPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Controllers\Services\FileManipulationTrait;

/**
 * Class AnalyzeProcessOrder
 * @package App\Helpers\Commercial
 */
class AnalyzeProcessOrder
{

    /**
     * @var Request
     */
    private $request;
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $template;
    /**
     * @var OrderSales|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public $model;
    /**
     * Usado para definir se é um pedido gerado internamente.
     * @var false|mixed
     */
    private $single_order;
    use CommercialTrait;
    use FileManipulationTrait;

    public function __construct(Request $request, $id, $single_order = false)
    {
        if (!$request)
            throw new \Exception("É necessário o envio do request para iniciar a class.");

        $this->request = $request;
        $this->single_order = $single_order;

        $this->model = OrderSales::with(
            'orderDelivery',
            'orderReceiver',
            'orderProducts.setProduct.productAirEvap',
            'programationMonth.programation.salesman',
            'salesman'
        )->where('id', $id)
            ->first();

        if (!$this->model)
            throw new \Exception("Não foi encontrar do pedido a partir do ID.");

        if ($this->model->is_programmed == 1) {
            $this->url = '/commercial/order/approv';
            $this->template = 'commercial.orderSales.requestApprovOrder';
        } else {
            $this->url = '/commercial/order/confirmed/approv';
            $this->template = 'commercial.orderSales.requestApprovOrderConfirmed';
        }

    }

    private function immediateStepAnalyze() {
        $analyzes = OrderImdtAnalyze::with('salesman')
            ->where('order_sales_id', $this->model->id)
            ->orderBy('id', 'DESC')
            ->first();

        if ($this->model->is_programmed == 1) {
            $url = '/comercial/operacao/order/approv';
            $template = 'commercial.orderSales.requestApprovOrder';
            $table = json_decode($this->model->programationMonth->json_table_price);
            $obj_table = commercialTablePriceConvertValue($table);
        } else {
            $url = '/comercial/operacao/order/confirmed/approv';
            $template = 'commercial.orderSales.requestApprovOrderConfirmed';
            $table = json_decode($this->model->json_table_price);
            $obj_table = commercialTablePriceConvertValue($table);
        }

        foreach ($analyzes->salesman->immediate_boss as $key) {

            if ($key->email) {
                $pattern = array(
                    'title' => 'APROVAÇÃO DO PEDIDO DE VENDAS',
                    'description' => nl2br(""),
                    'model' => $this->model,
                    'obj_table' => $obj_table,
                    'url' => $url,
                    'template' => $template,
                    'subject' => 'Comercial - Aprovação do pedido de vendas',
                );

                SendMailJob::dispatch($pattern, $key->email);
            }
        }
    }

    private function dirCommercialStepAnalyze() {
        $managers = UserOnPermissions::with('user')->where('perm_id', 20)
            ->where('grade', 9)
            ->where('can_approv', 1)
            ->get();

        if ($managers->count() == 0)
            throw new \Exception("Não há diretor comercial cadastrado para aprovar sua solicitação"); DB::rollBack();

        if ($this->model->is_programmed == 1) {
            $table = json_decode($this->model->programationMonth->json_table_price);
            $obj_table = commercialTablePriceConvertValue($table);
        } else {
            $table = json_decode($this->model->json_table_price);
            $obj_table = commercialTablePriceConvertValue($table);
        }

        foreach ($managers as $key) {

            if ($key->user->email) {
                $pattern = array(
                    'title' => 'APROVAÇÃO DO PEDIDO DE VENDAS',
                    'description' => nl2br(""),
                    'model' => $this->model,
                    'obj_table' => $obj_table,
                    'url' => $this->url,
                    'template' => $this->template,
                    'subject' => 'Comercial - Aprovação do pedido de vendas',
                );

                SendMailJob::dispatch($pattern, $key->user->email);
            }
        }
    }

    private function dirFinancyStepAnalyze() {

        $managers = UserOnPermissions::with('user')->where('perm_id', 18)
            ->where('grade', 8)
            ->where('can_approv', 1)
            ->get();

        if ($managers->count() == 0)
            throw new \Exception("Não há gerente financeiro cadastro para aprovar sua solicitação"); DB::rollBack();


        if ($this->model->is_programmed == 1) {
            $table = json_decode($this->model->programationMonth->json_table_price);
            $obj_table = commercialTablePriceConvertValue($table);
        } else {
            $table = json_decode($this->model->json_table_price);
            $obj_table = commercialTablePriceConvertValue($table);
        }

        foreach ($managers as $key) {

            if ($key->user->email) {
                $pattern = array(
                    'title' => 'APROVAÇÃO DO PEDIDO DE VENDAS',
                    'description' => nl2br(""),
                    'model' => $this->model,
                    'obj_table' => $obj_table,
                    'url' => $this->url,
                    'template' => $this->template,
                    'subject' => 'Comercial - Aprovação do pedido de vendas',
                );

                SendMailJob::dispatch($pattern, $key->user->email);
            }
        }
    }

    public function startAnalyze() {

        DB::beginTransaction();

        if ($this->single_order and !$this->model->manual_order_sales) {
            
            throw new \Exception("Peça para o representante realizar essa ação no painel dele."); DB::rollBack();

        } else {

            $this->model->salesman_imdt_approv = 0;

            if ($this->model->salesman->immediate_boss->count() == 0)
                throw new \Exception("Não foi possível encontrar o imediato chefe do representante"); DB::rollBack();

            if ($this->model->salesman->immediate_boss->where('is_direction', 2)->count() > 0) {
                $this->model->salesman_imdt_approv = 1;
                $this->dirCommercialStepAnalyze();
            } else {

                $this->model->salesman_imdt_approv = 0;
                if ($this->model->is_programmed == 1) {
                    $table = json_decode($this->model->programationMonth->json_table_price);
                    $obj_table = commercialTablePriceConvertValue($table);
                } else {
                    $table = json_decode($this->model->json_table_price);
                    $obj_table = commercialTablePriceConvertValue($table);
                }

                foreach ($this->model->salesman->immediate_boss as $key) {

                    if ($key->email) {
                        $pattern = array(
                            'title' => 'APROVAÇÃO DO PEDIDO DE VENDAS',
                            'description' => nl2br(""),
                            'model' => $this->model,
                            'obj_table' => $obj_table,
                            'url' => $this->url,
                            'template' => $this->template,
                            'subject' => 'Comercial - Aprovação do pedido de vendas',
                        );

                        SendMailJob::dispatch($pattern, $key->email);
                    }
                }

            }
        }

        $this->model->salesman_imdt_reprov = 0;
        $this->model->commercial_is_approv = 0;
        $this->model->commercial_is_reprov = 0;
        $this->model->has_analyze = 1;
        $this->model->financy_approv = 0;
        $this->model->financy_reprov = 0;
        $this->model->save();

        DB::commit();
        return true;
    }

    public function doAnalyze($analyze_type, $user_type = 1, $description = null) {
        if ($this->model->salesman_imdt_reprov == 1)
            throw new \Exception("Essa análise do pedido já foi reprovada pelo imediato chefe.");
        else if ($this->model->commercial_is_reprov == 1)
            throw new \Exception("Essa análise do pedido já foi reprovada pelo diretor comercial.");
        else if ($this->model->financy_reprov == 1)
            throw new \Exception("Essa análise do pedido já foi reprovada pelo gerente financeiro.");
        else if ($this->model->salesman_imdt_approv == 1 and $this->model->commercial_is_approv == 1 and $this->model->financy_approv == 1)
            throw new \Exception("Análise do pedido já foi aprovada!");
        else if ($this->model->has_analyze == 0)
            throw new \Exception("Para realizar análise, o pedido precisa estar em análise.");
        else if (!$this->validProcess($user_type))
            throw new \Exception("Você não pertence a essa etapa do processo.");

        DB::beginTransaction();
        // continue
        if ($user_type == 1) {

            $dirSalesman = $this->request->session()->get('salesman_data');
            if ($dirSalesman)
                $is_direction = $dirSalesman->is_direction > 1 ? $dirSalesman->is_direction : 0;
            else
                $is_direction = 0;

            // É diretor comercial?
            if ($this->validPerm($this->request->session()->get('r_code'), 20, 9, 1) or $is_direction) {

                if ($analyze_type == 1) {
                    $this->model->commercial_is_approv = 1;
                    // provisorio
                    $this->model->financy_approv = 1;
                    $this->model->has_analyze = 0;
                    $this->model->is_approv = 1;
                } else {
                    $this->model->commercial_is_reprov = 1;
                    $this->model->has_analyze = 0;
                    $this->model->is_reprov = 1;
                }

                $this->registerAnalyze(2, $analyze_type, $description);

                // É gerente financeiro
            } else if ($this->validPerm($this->request->session()->get('r_code'), 18, 8, 1)) {

                if ($analyze_type == 1) {
                    $this->model->financy_approv = 1;
                    $this->model->has_analyze = 0;
                    $this->model->is_approv = 1;
                } else {
                    $this->model->financy_reprov = 1;
                    $this->model->has_analyze = 0;
                    $this->model->is_reprov = 1;

                }

                $this->registerAnalyze(3, $analyze_type, $description);

            } else {

                throw new \Exception("Você não tem permissão para aprovar essa solicitação.");
            }

        } else {

            $this->registerAnalyze(1, $analyze_type, $description);
        }

        return true;
    }

    private function registerAnalyze($type, $analyze_type, $description = null) {
        // Representante
        if ($type == 1) {

            $analyze = new OrderImdtAnalyze;
            $analyze->order_sales_id = $this->model->id;
            $analyze->salesman_id = $this->request->session()->get('salesman_data')->id;
            $analyze->office = $this->request->session()->get('salesman_data')->office;
            $analyze->description = $description;
            $analyze->is_approv = $analyze_type == 1 ? 1 : 0;
            $analyze->is_reprov = $analyze_type == 2 ? 1 : 0;
            $analyze->save();

            if ($analyze_type == 1) {
                $analyzes = OrderImdtAnalyze::with('salesman')
                    ->where('order_sales_id', $this->model->id)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($analyze) {
                    if ($analyzes->salesman->immediate_boss->where('is_direction', 2)->first()) {
                        $this->dirCommercialStepAnalyze();
                        $this->model->salesman_imdt_approv = 1;
                    } else {
                        $this->immediateStepAnalyze();
                    }
                } else {
                    DB::rollBack();
                    throw new \Exception("Ocorreu algum erro inesperado ao continuar as etapas. Fale com administrador!");
                }
            } else {
                $this->model->salesman_imdt_reprov = 1;
                $this->model->has_analyze = 0;
                $this->model->is_reprov = 1;
            }

            // Comercial
        } else if ($type == 2) {

            $analyze = new OrderCommercialAnalyze;
            $analyze->order_sales_id = $this->model->id;
            $analyze->r_code = $this->request->session()->get('r_code');
            $analyze->description = $description;
            $analyze->is_approv = $analyze_type == 1 ? 1 : 0;
            $analyze->is_reprov = $analyze_type == 2 ? 1 : 0;
            $analyze->save();


            // Provisorio
            if ($this->model->is_programmed) {

                $this->createNewVersionProgramation();
                $this->downProgramationMacro();

                $salesman = App\Model\Commercial\Salesman::find($this->model->request_salesman_id);

                $description = nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Código: ". $this->model->code ."<br>
                               Programação: ". $this->model->programationMonth->programation->code ."<br>
                               Mês(es) da programação: ". $this->model->programationMonth->programation->months ."<br>
                               Cliente: ". $this->model->client_company_name ."<br>
                               Representante: ". $salesman->full_name ."<br>
                               URL: <a href='". $this->request->root() ."/commercial/order/list'>". $this->request->root() ."/commercial/order/list</a>
                            </p>");
            } else {
                $date = new \Carbon\Carbon($this->model->yearmonth);
                $month = ucfirst($date->locale('pt_BR')->isoFormat('MMMM')) .' '. ucfirst($date->locale('pt_BR')->isoFormat('YYYY'));
                $salesman = App\Model\Commercial\Salesman::find($this->model->request_salesman_id);

                $description = nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Código: ". $this->model->code ."<br>
                               Mês: ". $month ."<br>
                               Cliente: ". $this->model->client_company_name ."<br>
                               Representante: ". $salesman->full_name ."<br>
                               URL: <a href='". $this->request->root() ."/commercial/order/confirmed/list'>". $this->request->root() ."/commercial/order/confirmed/list</a>
                            </p>");
            }

            // Avisar o representante
            $pattern = array(
                'title' => 'COMERCIAL - PEDIDO APROVADO',
                'description' => $description,
                'template' => 'misc.DefaultExternal',
                'subject' => 'Comercial - Pedido aprovado',
            );

            SendMailJob::dispatch($pattern, $this->model->salesman->email);

            $settings = Settings::where('command', 'order_approval')->first();
            if ($settings->value) {
                $arr = explode(',', $settings->value);

                foreach ($arr as $key) {

                    $pattern = array(
                        'title' => 'COMERCIAL - PEDIDO APROVADO',
                        'description' => $description,
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Comercial - Pedido aprovado',
                    );

                    SendMailJob::dispatch($pattern, $key);
                }
            }

            // Financeiro
        } else if ($type == 3) {

            $analyze = new OrderFinancyAnalyze;
            $analyze->order_sales_id = $this->model->id;
            $analyze->r_code = $this->request->session()->get('r_code');
            $analyze->description = $description;
            $analyze->is_approv = $analyze_type == 1 ? 1 : 0;
            $analyze->is_reprov = $analyze_type == 2 ? 1 : 0;
            $analyze->save();

            if ($this->model->is_programmed) {

                $this->createNewVersionProgramation();
                $this->downProgramationMacro();

                $salesman = App\Model\Commercial\Salesman::find($this->model->request_salesman_id);

                $description = nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Código: ". $this->model->code ."<br>
                               Programação: ". $this->model->programationMonth->programation->code ."<br>
                               Mês(es) da programação: ". $this->model->programationMonth->programation->months ."<br>
                               Cliente: ". $this->model->client_company_name ."<br>
                               Representante: ". $salesman->full_name ."<br>
                               URL: <a href='". $this->request->root() ."/commercial/order/list'>". $this->request->root() ."/commercial/order/list</a>
                            </p>");
            } else {
                $date = new \Carbon\Carbon($this->model->yearmonth);
                $month = ucfirst($date->locale('pt_BR')->isoFormat('MMMM')) .' '. ucfirst($date->locale('pt_BR')->isoFormat('YYYY'));
                $salesman = App\Model\Commercial\Salesman::find($this->model->request_salesman_id);

                $description = nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Código: ". $this->model->code ."<br>
                               Mês: ". $month ."<br>
                               Cliente: ". $this->model->client_company_name ."<br>
                               Representante: ". $salesman->full_name ."<br>
                               URL: <a href='". $this->request->root() ."/commercial/order/confirmed/list'>". $this->request->root() ."/commercial/order/confirmed/list</a>
                            </p>");
            }

            // Avisar o representante
            $pattern = array(
                'title' => 'COMERCIAL - PEDIDO APROVADO',
                'description' => $description,
                'template' => 'misc.DefaultExternal',
                'subject' => 'Comercial - Pedido aprovado',
            );

            SendMailJob::dispatch($pattern, $this->model->salesman->email);

            $settings = Settings::where('command', 'order_approval')->first();
            if ($settings->value) {
                $arr = explode(',', $settings->value);

                foreach ($arr as $key) {

                    $pattern = array(
                        'title' => 'COMERCIAL - PEDIDO APROVADO',
                        'description' => $description,
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Comercial - Pedido aprovado',
                    );

                    SendMailJob::dispatch($pattern, $key);
                }
            }

        } else {
            DB::rollBack();
            throw new \Exception("Tipo de registro de aprovação, inexistente, contate o administrador!");
        }

		
        $this->model->save();
        DB::commit();
		$this->updatePaper();

        return;
    }

    private function createNewVersionProgramation() {
        $orderProducts = $this->model->orderProducts;
        $programationMacro = $this->model->programationMonth->programation->programationMacro()
            ->where('yearmonth', date('Y-m-01', strtotime($this->model->programationMonth->yearmonth)))
            ->get();

        if ($orderProducts->where('is_qtd_diff_programation', 1)->first()
            or
            $orderProducts->where('is_prod_diff_programation', 1)->first()) {

            // Cria uma nova versão da programação
            // Nova versão do mês
            $last_version = $this->model->programationMonth->programation->programationVersion()->where('is_approv', 1)->first();

            if (!$last_version)
                throw new \Exception("Não foi encontrado a última versão da programação, fale com administrador!");  DB::rollBack();

            $p_months_replicate = \App\Model\Commercial\ProgramationMonth::where('programation_id', $this->model->programationMonth->programation_id)
                ->where('version', $this->model->programationMonth->version)
                ->whereNotIn('id', [$this->model->programationMonth->id])->get();

            foreach ($p_months_replicate as $r_month) {
                $new_o_month = $r_month->replicate();
                $new_o_month->version = $last_version->version + 1;
                $new_o_month->save();
            }

            $new_p_month = $this->model->programationMonth->replicate();
            $new_p_month->version = $last_version->version + 1;

            $old_json_qtd_prices = json_decode($new_p_month->json_qtd_prices, true);
            $new_json_qtd_prices = json_decode($new_p_month->json_qtd_prices, true);

            foreach ($old_json_qtd_prices as $index_cat => $old_json) {
                foreach ($old_json['products'] as $index_prod => $products_cat) {

                    $is_prod = $orderProducts->where('category_id', $old_json['id'])
                        ->where('set_product_id', $products_cat['id'])
                        ->where('is_prod_diff_programation', 1)->first();

                    $new_prod = $orderProducts->where('category_id', $old_json['id'])
                        ->where('set_product_id', $products_cat['id'])
                        ->where('is_qtd_diff_programation', 1)->first();

                    $product_macro = $programationMacro->where('category_id', $old_json['id'])
                        ->where('set_product_id', $products_cat['id'])
                        ->first();

                    if ($new_prod) {

                        $new_json_qtd_prices[$index_cat]['products'][$index_prod]['qtd'] = $new_prod->quantity;
                        $product_macro->quantity = $new_prod->quantity;
                        $product_macro->total = $new_prod->quantity;
                        $product_macro->save();

                    } else if ($is_prod) {

                        $new_json_qtd_prices[$index_cat]['products'][$index_prod]['qtd'] += $is_prod->quantity - $product_macro->quantity;
                        $rest_total = $is_prod->quantity - $product_macro->quantity;
                        $product_macro->total += $rest_total;
                        $product_macro->quantity += $rest_total;
                        $product_macro->save();
                    }

                }
            }

            $new_p_month->json_qtd_prices = json_encode($new_json_qtd_prices);
            $new_p_month->save();

            // Atualizar versão da programação
            $p_version = $last_version->replicate();
            $json_programmation = json_decode($p_version->json_programation, true);

            $json_programmation[date('Y-m', strtotime($this->model->programationMonth->yearmonth))]['category'] = $new_json_qtd_prices;

            $new_json_programmation = $json_programmation;
            $p_version->json_programation = json_encode($new_json_programmation);
            $p_version->version += 1;
            $p_version->description = 'Programação atualizada com base na aprovação do pedido com novo acréscimo de quantidade/produto.';
            $p_version->save();

        }
    }

    private function downProgramationMacro() {
        // Dar baixa na programação macro relacionada.
        $orderProducts = $this->model->orderProducts;
        $programationMacro = $this->model->programationMonth->programation->programationMacro()
            ->where('yearmonth', date('Y-m-01', strtotime($this->model->programationMonth->yearmonth)))
            ->get();

        foreach ($programationMacro as $item) {
            $order_prod = $orderProducts->where('set_product_id', $item->set_product_id)->where('category_id', $item->category_id)->first();
            if ($order_prod) {
                $item->quantity = $item->quantity - $order_prod->quantity;
                $item->save();
            }
        }
    }

    private function updatePaper() {

        if ($this->model->is_programmed)
            $this->model->view = $this->renderViewOrder($this->model->id);
        else
            $this->model->view = $this->renderViewOrderConfirmed($this->model->id);

		$this->model->save();
        return;
    }

    private function validPerm($user_r_code, $perm_id, $grade, $can_approv) {
        $imDir = $this->request->session()->get('permissoes_usuario')
            ->where('user_r_code', $user_r_code)
            ->where('perm_id', $perm_id)
            ->where('grade', $grade)
            ->where('can_approv', $can_approv)
            ->first();

        return $imDir;
    }

    private function validProcess($type) {
        if ($type == 1) {
            // Nesse processo apenas o diretor pode aprovar, pois o usuário aqui informado é "INTERNO".
            if ($this->model->salesman_imdt_approv == 0) {
                $analyzes = OrderImdtAnalyze::with('salesman')
                    ->where('order_sales_id', $this->model->id)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($analyzes) {
                    if ($analyzes->salesman->immediate_boss->where('is_active', 1)->where('is_direction', 2)->first() or $this->validPerm($this->request->session()->get('r_code'), 20, 9, 1))
                        return true;
                    else
                        return false;
                } else {

                    if ($this->model->salesman->immediate_boss->where('is_active', 1)->where('is_direction', 2)->first() or $this->validPerm($this->request->session()->get('r_code'), 20, 9, 1))
                        return true;
                    else
                        return false;
                }

            } else if ($this->model->commercial_is_approv == 0) {
                if ($this->validPerm($this->request->session()->get('r_code'), 20, 9, 1))
                    return true;
                else
                    return false;
            } else if ($this->model->financy_approv == 0) {
                if ($this->validPerm($this->request->session()->get('r_code'), 18, 8, 1))
                    return true;
                else
                    return false;
            }

        } else {

            if ($this->model->salesman_imdt_approv == 0) {
                $analyzes = OrderImdtAnalyze::with('salesman')
                    ->where('order_sales_id', $this->model->id)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($analyzes) {

                    $validNextAnalyze = $analyzes->salesman->immediate_boss->where('is_active', 1)
                        ->where('is_direction', '!=', 2)
                        ->where('id', $this->request->session()->get('salesman_data')->id)
                        ->first();

                    if ($validNextAnalyze) {
                        return true;
                    } else {
                        $validNextAnalyze = $analyzes->salesman->immediate_boss->where('is_active', 1)
                            ->where('is_direction', 2)
                            ->where('id', $this->request->session()->get('salesman_data')->id)
                            ->first();

                        if ($validNextAnalyze)
                            return true;
                        else
                            return false;
                    }

                } else {

                    $validNextAnalyze = $this->model->salesman->immediate_boss
                        ->where('is_direction', '!=', 2)
                        ->where('id', $this->request->session()->get('salesman_data')->id)
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

    }

}
