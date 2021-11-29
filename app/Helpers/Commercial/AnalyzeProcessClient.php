<?php

namespace App\Helpers\Commercial;

use App;
use App\Http\Controllers\Services\CommercialTrait;
use App\Jobs\SendMailJob;
use App\Model\Commercial\Client;
use App\Model\Commercial\ClientCommercialAnalyze;
use App\Model\Commercial\ClientFinancyAnalyze;
use App\Model\Commercial\ClientImdtAnalyze;
use App\Model\Commercial\ClientJudicialAnalyze;
use App\Model\Commercial\ClientRevisionAnalyze;
use App\Model\Commercial\ClientVersion;
use App\Model\Commercial\Settings;
use App\Model\UserOnPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyzeProcessClient extends ManagerClient
{

    private $request;
    private $version;
    public $model;
    use CommercialTrait;

    public function __construct(Request $request)
    {
        if (!$request)
            throw new \Exception("É necessário o envio do request para iniciar a class.");

        $this->request = $request;

        $this->model = Client::with('client_peoples_contact',
            'client_account_bank',
            'client_main_suppliers',
            'client_main_clients',
            'client_group',
            'client_on_group',
            'client_on_product_sales',
            'client_owner_and_partner',
            'client_version',
            'salesman',
            'client_documents'
        )->where('id', $request->id)->first();

        if (!$this->model)
            throw new \Exception("Não foi encontrar o cliente a partir do ID.");
    }

    private function verifyVersion() {

        $resetAnalyze = ClientVersion::withTrashed()->where('client_id', $this->model->id)->where('deleted_at', '!=', null)->first();
        if ($resetAnalyze) {
            ClientImdtAnalyze::where('version', $resetAnalyze->version)->where('client_id', $this->model->id)->delete();
            ClientRevisionAnalyze::where('version', $resetAnalyze->version)->where('client_id', $this->model->id)->delete();
            ClientJudicialAnalyze::where('version', $resetAnalyze->version)->where('client_id', $this->model->id)->delete();
            ClientCommercialAnalyze::where('version', $resetAnalyze->version)->where('client_id', $this->model->id)->delete();
            ClientFinancyAnalyze::where('version', $resetAnalyze->version)->where('client_id', $this->model->id)->delete();
            DB::connection('commercial')
                ->table('client_version')
                ->where('client_id', $this->model->id)
                ->where('deleted_at', '!=', null)
                ->delete();
        }

        $this->mng_request = $this->request;

        $this->editClient(true); // problema a cria novo e enviar para analise

        $new_model = Client::with(['client_peoples_contact',
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

        // Verify version if first version
        if ($this->model->client_version()->count() == 0) {

            // Setup first version
            $version = new ClientVersion;
            $version->view = "";
            $version->inputs = json_encode($this->model);
            $version->version = 1;
            $version->client_id = $this->model->id;
            $version->save();

            // PRINT
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


        } else {

            $new_model = $new_model->toJson();

            // retroceder valores no banco de dados
            DB::connection('commercial')->table('client')->where('id', $this->model->id)->update($this->splitArray($this->model->toJson(), 1));
            foreach ($this->splitArray($this->model->toJson(), 2) as $key => $value) {

                if ($key != 'salesman' and $key != 'client_group' and $key != 'group') {
                    DB::connection('commercial')->table($key)->where('client_id', $this->model->id)->delete();
                    DB::connection('commercial')->table($key)->insert($value);
                }
            }

            $header = DB::connection('commercial')
                ->table('settings')
                ->where('type', 1)
                ->get()->toJson();


            $last = $this->model->client_version()->orderBy('id', 'DESC')->first();

            // Update version
            $version = new clientVersion;
            $version->view = $this->renderViewClient($this->model);
            $version->header = $header;
            $version->inputs = $new_model;
            $version->version = $last->version + 1;
            $version->client_id = $this->model->id;
            $version->save();


        }

    }

    private function immediateStepAnalyze() {
        $analyzes = ClientImdtAnalyze::with('salesman')
            ->where('client_id', $this->model->id)
            ->where('version', $this->version)
            ->orderBy('id', 'DESC')
            ->first();

        foreach ($analyzes->salesman->immediate_boss as $key) {

            if ($key->email) {
                $pattern = array(
                    'title' => 'APROVAÇÃO DE CLIENTE',
                    'description' => nl2br("Este cliente se encontra em processo de aprovação, para aprovar e analisar<br>suas informações  e documentos entre no painel."),
                    'salesman_request' => $analyzes->salesman->full_name,
                    'date_request' => date('Y-m-d H:i:s'),
                    'email' => $analyzes->salesman->email,
                    'office' => $analyzes->salesman->office,
                    'social_reason' => $this->model->company_name,
                    'type' => $this->model->client_version->count() == 0 || $this->model->client_version->last()->version == 1 ? 'Novo cliente' : 'Atualização',
                    'identity_client' => $this->model->identity,
                    'is_matriz' => $this->model->is_matriz == 1 ? 'Matriz' : 'Filial',
                    'client_analyze_id' => $this->model->id,
                    'url' => '/comercial/operacao/cliente/analise/',
                    'template' => 'commercial.client.requestApprov',
                    'subject' => 'Comercial - Aprovação de cliente',
                );

                SendMailJob::dispatch($pattern, $key->email);
            }
        }
    }

    private function dirCommercialStepAnalyze($salesman) {
        $managers = UserOnPermissions::with('user')->where('perm_id', 20)
            ->where('grade', 9)
            ->where('can_approv', 1)
            ->get();

        if ($managers->count() == 0)
            throw new \Exception("Não há diretor comercial cadastrado para aprovar sua solicitação"); DB::rollBack();

        foreach ($managers as $key) {

            $pattern = array(
                'title' => 'APROVAÇÃO DE CLIENTE',
                'description' => nl2br("Este cliente se encontra em processo de aprovação, para aprovar e analisar<br>suas informações  e documentos entre no painel."),
                'salesman_request' => $salesman->full_name,
                'email' => $salesman->email,
                'office' => $salesman->office,
                'date_request' => date('Y-m-d H:i:s'),
                'social_reason' => $this->model->company_name,
                'type' => $this->model->client_version->count() == 0 || $this->model->client_version->last()->version == 1 ? 'Novo cliente' : 'Atualização',
                'identity_client' => $this->model->identity,
                'is_matriz' => $this->model->is_matriz == 1 ? 'Matriz' : 'Filial',
                'client_analyze_id' => $this->model->id,
                'url' => '/commercial/client/analyze/',
                'template' => 'commercial.client.requestApprov',
                'subject' => 'Comercial - Aprovação de cliente',
            );

            SendMailJob::dispatch($pattern, $key->user->email);
        }
    }

    private function dirJudicialStepAnalyze($salesman) {
        $managers = UserOnPermissions::with('user')->where('perm_id', 23)
            ->where('can_approv', 1)
            ->get();

        if ($managers->count() == 0)
            throw new \Exception("Não há cadastro com permissão no juridico para aprovar sua solicitação"); DB::rollBack();

        foreach ($managers as $key) {

            $pattern = array(
                'title' => 'APROVAÇÃO DE CLIENTE',
                'description' => nl2br("Este cliente se encontra em processo de aprovação, para aprovar e analisar<br>suas informações  e documentos entre no painel."),
                'salesman_request' => $salesman->full_name,
                'email' => $salesman->email,
                'office' => $salesman->office,
                'date_request' => date('Y-m-d H:i:s'),
                'social_reason' => $this->model->company_name,
                'type' => $this->model->client_version->count() == 0 || $this->model->client_version->last()->version == 1 ? 'Novo cliente' : 'Atualização',
                'identity_client' => $this->model->identity,
                'is_matriz' => $this->model->is_matriz == 1 ? 'Matriz' : 'Filial',
                'client_analyze_id' => $this->model->id,
                'url' => '/commercial/client/analyze/',
                'template' => 'commercial.client.requestApprov',
                'subject' => 'Comercial - Aprovação de cliente',
            );

            SendMailJob::dispatch($pattern, $key->user->email);
        }
    }

    private function dirRevisionStepAnalyze($salesman) {
        $managers = UserOnPermissions::with('user')->where('perm_id', 20)
            ->where('grade', 4)
            ->where('can_approv', 1)
            ->get();

        if ($managers->count() == 0)
            throw new \Exception("Não há cadastro com permissão no juridico para aprovar sua solicitação"); DB::rollBack();

        foreach ($managers as $key) {

            $pattern = array(
                'title' => 'APROVAÇÃO DE CLIENTE',
                'description' => nl2br("Este cliente se encontra em processo de aprovação, para aprovar e analisar<br>suas informações  e documentos entre no painel."),
                'salesman_request' => $salesman->full_name,
                'email' => $salesman->email,
                'office' => $salesman->office,
                'date_request' => date('Y-m-d H:i:s'),
                'social_reason' => $this->model->company_name,
                'type' => $this->model->client_version->count() == 0 || $this->model->client_version->last()->version == 1 ? 'Novo cliente' : 'Atualização',
                'identity_client' => $this->model->identity,
                'is_matriz' => $this->model->is_matriz == 1 ? 'Matriz' : 'Filial',
                'client_analyze_id' => $this->model->id,
                'url' => '/commercial/client/analyze/',
                'template' => 'commercial.client.requestApprov',
                'subject' => 'Comercial - Aprovação de cliente',
            );

            SendMailJob::dispatch($pattern, $key->user->email);
        }
    }

    private function dirFinancyStepAnalyze($salesman) {

        $managers = UserOnPermissions::with('user')->where('perm_id', 18)
            ->where('grade', 8)
            ->where('can_approv', 1)
            ->get();

        if ($managers->count() == 0)
            throw new \Exception("Não há gerente financeiro cadastro para aprovar sua solicitação"); DB::rollBack();

        foreach ($managers as $key) {

            if ($key->user->email) {
                $pattern = array(
                    'title' => 'APROVAÇÃO DE CLIENTE',
                    'description' => nl2br("Este cliente se encontra em processo de aprovação, para aprovar e analisar<br>suas informações  e documentos entre no painel."),
                    'salesman_request' => $salesman->full_name,
                    'email' => $salesman->email,
                    'office' => $salesman->office,
                    'date_request' => date('Y-m-d H:i:s'),
                    'social_reason' => $this->model->company_name,
                    'type' => $this->model->client_version->count() == 0 || $this->model->client_version->last()->version == 1 ? 'Novo cliente' : 'Atualização',
                    'identity_client' => $this->model->identity,
                    'is_matriz' => $this->model->is_matriz == 1 ? 'Matriz' : 'Filial',
                    'client_analyze_id' => $this->model->id,
                    'url' => '/commercial/client/analyze/',
                    'template' => 'commercial.client.requestApprov',
                    'subject' => 'Comercial - Aprovação de cliente',
                );

                SendMailJob::dispatch($pattern, $key->user->email);
            }
        }
    }

    public function startAnalyze() {

        if ($this->model->has_analyze == 1)
            throw new \Exception("Cliente já se encontra em análise, realize a reprovação ou aprovação.");
        else if ($this->model->is_active == 0)
            throw new \Exception("Cliente está desativado, não é possível enviar para aprovação.");

        DB::beginTransaction();
        $this->verifyVersion();

        if (!$this->model->request_salesman_id)
            throw new \Exception("Não foi possível encontrar o representante vinculado ao cliente"); DB::rollBack();

        $this->model->salesman_imdt_approv = 0;

		if ($this->model->salesman->immediate_boss->count() == 0)
			throw new \Exception("Não foi possível encontrar o imediato chefe do representante"); DB::rollBack();

		if ($this->model->salesman->immediate_boss->where('is_direction', 2)->count() > 0) {
			$this->model->salesman_imdt_approv = 1;
			$this->dirRevisionStepAnalyze($this->model->salesman);
		} else {

			$this->model->salesman_imdt_approv = 0;
			foreach ($this->model->salesman->immediate_boss as $key) {

				if ($key->email) {
					$pattern = array(
						'title' => 'APROVAÇÃO DE CLIENTE',
						'description' => nl2br("Este cliente se encontra em processo de aprovação, para aprovar e analisar<br>suas informações  e documentos entre no painel."),
						'salesman_request' => $this->model->salesman->full_name,
						'email' => $this->model->salesman->email,
						'office' => $this->model->salesman->office,
						'date_request' => date('Y-m-d H:i:s'),
						'social_reason' => $this->model->company_name,
						'type' => $this->model->client_version->count() == 0 || $this->model->client_version->last()->version == 1 ? 'Novo cliente' : 'Atualização',
						'identity_client' => $this->model->identity,
						'is_matriz' => $this->model->is_matriz == 1 ? 'Matriz' : 'Filial',
						'client_analyze_id' => $this->model->id,
						'url' => '/comercial/operacao/cliente/analise/',
						'template' => 'commercial.client.requestApprov',
						'subject' => 'Comercial - Aprovação de cliente',
					);

					SendMailJob::dispatch($pattern, $key->email);
				}
			}

		}

        $this->model->salesman_imdt_reprov = 0;
        $this->model->commercial_is_approv = 0;
        $this->model->commercial_is_reprov = 0;
        $this->model->has_analyze = 1;
        $this->model->financy_approv = 0;
        $this->model->financy_reprov = 0;
		$this->model->revision_is_approv = 0;
        $this->model->revision_is_reprov = 0;
		$this->model->judicial_is_approv = 0;
        $this->model->judicial_is_reprov = 0;
        $this->model->save();

        DB::commit();
        return true;
    }

    public function doAnalyze($analyze_type, $user_type = 1, $description = null) {
        $this->version = $this->model->client_version()->orderBy('id', 'DESC')->first()->version;

        if ($this->model->salesman_imdt_reprov == 1)
            throw new \Exception("Essa análise do cliente já foi reprovada pelo imediato chefe.");
        else if ($this->model->revision_is_reprov == 1)
            throw new \Exception("Essa análise do cliente já foi reprovada pela revisão interna.");
        else if ($this->model->judicial_is_reprov == 1)
            throw new \Exception("Essa análise do cliente já foi reprovada pela direção juridica.");
        else if ($this->model->commercial_is_reprov == 1)
            throw new \Exception("Essa análise do cliente já foi reprovada pelo diretor comercial.");
        else if ($this->model->commercial_is_reprov == 1)
            throw new \Exception("Essa análise do cliente já foi reprovada pelo diretor comercial.");
        else if ($this->model->financy_reprov == 1)
            throw new \Exception("Essa análise do cliente já foi reprovada pelo gerente financeiro.");
        else if ($this->model->salesman_imdt_approv == 1 and $this->model->revision_is_approv == 1 and $this->model->judicial_is_approv == 1 and $this->model->commercial_is_approv == 1 and $this->model->financy_approv == 1)
            throw new \Exception("Análise do cliente já foi aprovada!");
        else if ($this->model->has_analyze == 0)
            throw new \Exception("Para realizar análise, o cliente precisa estar em análise.");
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

            // Revisão interna
            if ($this->validPerm($this->request->session()->get('r_code'), 20, 4, 1)) {

                if ($analyze_type == 1) {
                    $this->model->revision_is_approv = 1;
					$this->model->judicial_is_approv = 1;
                } else {
                    $this->model->revision_is_reprov = 1;
                    $this->model->has_analyze = 0;
                    clientVersion::where('client_id', $this->model->id)->orderBy('id', 'DESC')->delete();
                }

                $this->registerAnalyze(5, $analyze_type, $description);

                // É Juridico
            } else if ($this->validPerm($this->request->session()->get('r_code'), 23, null, 1)) {

                if ($analyze_type == 1) {
                    $this->model->judicial_is_approv = 1;
                } else {
                    $this->model->judicial_is_reprov = 1;
                    $this->model->has_analyze = 0;
                    clientVersion::where('client_id', $this->model->id)->orderBy('id', 'DESC')->delete();
                }

                $this->registerAnalyze(4, $analyze_type, $description);

                // É diretor comercial?
            } else if ($this->validPerm($this->request->session()->get('r_code'), 20, 9, 1) or $is_direction) {

                if ($analyze_type == 1) {
                    $this->model->commercial_is_approv = 1;
                    // Provisorio
                    $this->model->financy_approv = 1;
                    $this->model->has_analyze = 0;
                } else {
                    $this->model->commercial_is_reprov = 1;
                    $this->model->has_analyze = 0;
                    clientVersion::where('client_id', $this->model->id)->orderBy('id', 'DESC')->delete();
                }

                $this->registerAnalyze(2, $analyze_type, $description);

                // É gerente financeiro
            } else if ($this->validPerm($this->request->session()->get('r_code'), 18, 8, 1)) {

                if ($analyze_type == 1) {
                    $this->model->financy_approv = 1;
                    $this->model->has_analyze = 0;
                } else {
                    $this->model->financy_reprov = 1;
                    $this->model->has_analyze = 0;

                    clientVersion::where('client_id', $this->model->id)->orderBy('id', 'DESC')->delete();
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

            $analyze = new ClientImdtAnalyze;
            $analyze->client_id = $this->model->id;
            $analyze->salesman_id = $this->request->session()->get('salesman_data')->id;
            $analyze->office = $this->request->session()->get('salesman_data')->office;
            $analyze->description = $description;
            $analyze->is_approv = $analyze_type == 1 ? 1 : 0;
            $analyze->is_reprov = $analyze_type == 2 ? 1 : 0;
            $analyze->version = $this->version;
            $analyze->save();

            if ($analyze_type == 1) {
                $analyzes = ClientImdtAnalyze::with('salesman')
                    ->where('client_id', $this->model->id)
                    ->where('version', $this->version)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($analyze) {
                    if ($analyzes->salesman->immediate_boss->where('is_direction', 2)->first()) {
                        $this->dirRevisionStepAnalyze($this->model->salesman);
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
                clientVersion::where('client_id', $this->model->id)->orderBy('id', 'DESC')->delete();
            }

            // Comercial
        } else if ($type == 2) {

            $analyze = new ClientCommercialAnalyze;
            $analyze->client_id = $this->model->id;
            $analyze->r_code = $this->request->session()->get('r_code');
            $analyze->description = $description;
            $analyze->is_approv = $analyze_type == 1 ? 1 : 0;
            $analyze->is_reprov = $analyze_type == 2 ? 1 : 0;
            $analyze->version = $this->version;
            $analyze->save();
            // $this->dirFinancyStepAnalyze($this->model->salesman);

            // provisoriamente
            /*$analyze = new ClientFinancyAnalyze;
            $analyze->client_id = $this->model->id;
            $analyze->r_code = $this->request->session()->get('r_code');
            $analyze->description = $description;
            $analyze->is_approv = $analyze_type == 1 ? 1 : 0;
            $analyze->is_reprov = $analyze_type == 2 ? 1 : 0;
            $analyze->version = $this->version;
            $analyze->save();*/

            $pattern = array(
                'title' => 'COMERCIAL - CLIENTE APROVADO',
                'description' => nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Cliente: ". $this->model->company_name ."<br>
                               Identidade: ". $this->model->identity ."<br>
                               URL: <a href='". $this->request->root() ."/comercial/operacao/cliente/todos'>". $this->request->root() ."/comercial/operacao/cliente/todos</a>
                            </p>"),
                'template' => 'misc.DefaultExternal',
                'subject' => 'Comercial - Cliente aprovado',
            );

            SendMailJob::dispatch($pattern, $this->model->salesman->email);

            $settings = Settings::where('command', 'client_approval')->first();
            if ($settings->value) {
                $arr = explode(',', $settings->value);

                foreach ($arr as $key) {

                    $pattern = array(
                        'title' => 'COMERCIAL - CLIENTE APROVADO',
                        'description' => nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Cliente: ". $this->model->company_name ."<br>
                               Identidade: ". $this->model->identity ."<br>
                               URL: <a href='". $this->request->root() ."/commercial/client/list'>". $this->request->root() ."/commercial/client/list</a>
                            </p>"),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Comercial - Cliente aprovado',
                    );

                    SendMailJob::dispatch($pattern, $key);
                }
            }


            // Financeiro
        } else if ($type == 3) {

            $analyze = new ClientFinancyAnalyze;
            $analyze->client_id = $this->model->id;
            $analyze->r_code = $this->request->session()->get('r_code');
            $analyze->description = $description;
            $analyze->is_approv = $analyze_type == 1 ? 1 : 0;
            $analyze->is_reprov = $analyze_type == 2 ? 1 : 0;
            $analyze->version = $this->version;
            $analyze->save();

            $pattern = array(
                'title' => 'COMERCIAL - CLIENTE APROVADO',
                'description' => nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Cliente: ". $this->model->company_name ."<br>
                               Identidade: ". $this->model->identity ."<br>
                               URL: <a href='". $this->request->root() ."/comercial/operacao/cliente/todos'>". $this->request->root() ."/comercial/operacao/cliente/todos</a>
                            </p>"),
                'template' => 'misc.DefaultExternal',
                'subject' => 'Comercial - Cliente aprovado',
            );

            SendMailJob::dispatch($pattern, $this->model->salesman->email);

            $settings = Settings::where('command', 'client_approval')->first();
            if ($settings->value) {
                $arr = explode(',', $settings->value);

                foreach ($arr as $key) {

                    $pattern = array(
                        'title' => 'COMERCIAL - CLIENTE APROVADO',
                        'description' => nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Cliente: ". $this->model->company_name ."<br>
                               Identidade: ". $this->model->identity ."<br>
                               URL: <a href='". $this->request->root() ."/commercial/client/list'>". $this->request->root() ."/commercial/client/list</a>
                            </p>"),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Comercial - Cliente aprovado',
                    );

                    SendMailJob::dispatch($pattern, $key);
                }
            }

        } else if ($type == 4) {

            $analyze = new ClientJudicialAnalyze;
            $analyze->client_id = $this->model->id;
            $analyze->r_code = $this->request->session()->get('r_code');
            $analyze->description = $description;
            $analyze->is_approv = $analyze_type == 1 ? 1 : 0;
            $analyze->is_reprov = $analyze_type == 2 ? 1 : 0;
            $analyze->version = $this->version;
            $analyze->save();
            $this->dirCommercialStepAnalyze($this->model->salesman);

            // Revisão interna
        } else if ($type == 5) {

            $analyze = new ClientRevisionAnalyze;
            $analyze->client_id = $this->model->id;
            $analyze->r_code = $this->request->session()->get('r_code');
            $analyze->description = $description;
            $analyze->is_approv = $analyze_type == 1 ? 1 : 0;
            $analyze->is_reprov = $analyze_type == 2 ? 1 : 0;
            $analyze->version = $this->version;
            $analyze->save();
			$this->dirCommercialStepAnalyze($this->model->salesman);
            //$this->dirJudicialStepAnalyze($this->model->salesman);

        } else {
            DB::rollBack();
            throw new \Exception("Tipo de registro de aprovação, inexistente, contate o administrador!");
        }

        $this->model->save();
        DB::commit();

        if ($analyze_type == 1 and $type == 3)
            $this->updateFieldsClient($this->model);
    }

    private function validPerm($user_r_code, $perm_id, $grade, $can_approv) {

        if ($grade) {
            $result = $this->request->session()->get('permissoes_usuario')
                ->where('user_r_code', $user_r_code)
                ->where('perm_id', $perm_id)
                ->where('grade', $grade)
                ->where('can_approv', $can_approv)
                ->first();

        } else {
            $result = $this->request->session()->get('permissoes_usuario')
                ->where('user_r_code', $user_r_code)
                ->where('perm_id', $perm_id)
                ->where('can_approv', $can_approv)
                ->first();
        }

        return $result;
    }

    private function validProcess($type) {
        if ($type == 1) {
            // Nesse processo apenas o diretor pode aprovar, pois o usuário aqui informado é "INTERNO".
            if ($this->model->salesman_imdt_approv == 0) {
                $analyzes = ClientImdtAnalyze::with('salesman')
                    ->where('client_id', $this->model->id)
                    ->where('version', $this->version)
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

            } else if ($this->model->revision_is_approv == 0) {
                if ($this->validPerm($this->request->session()->get('r_code'), 20, 4, 1))
                    return true;
                else
                    return false;
            } else if ($this->model->judicial_is_approv == 0) {
                if ($this->validPerm($this->request->session()->get('r_code'), 23, null, 1))
                    return true;
                else
                    return false;
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
                $analyzes = ClientImdtAnalyze::with('salesman')
                    ->where('client_id', $this->model->id)
                    ->where('version', $this->version)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($analyzes) {

                    $validNextAnalyze = $analyzes->salesman->immediate_boss->where('is_active', 1)
                        ->where('is_direction', '!=',  2)
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
                        ->where('is_direction', '!=',  2)
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
