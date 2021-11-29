<?php

namespace App\Helpers\Commercial;

use App\Http\Controllers\Services\CommercialTrait;
use App\Http\Controllers\Services\FileManipulationTrait;
use App\Model\Commercial\Client;
use App\Model\Commercial\ClientCommercialAnalyze;
use App\Model\Commercial\ClientDocuments;
use App\Model\Commercial\ClientFinancyAnalyze;
use App\Model\Commercial\ClientImdtAnalyze;
use App\Model\Commercial\ClientOnBalanceEquityDreFlow;
use App\Model\Commercial\ClientOnBalanceEquityDreFlow2Year;
use App\Model\Commercial\ClientOnBalanceEquityDreFlow3Year;
use App\Model\Commercial\ClientOnClient;
use App\Model\Commercial\ClientOnContractSocial;
use App\Model\Commercial\ClientOnProductSales;
use App\Model\Commercial\ClientPeoplesContact;
use App\Model\Commercial\ClientAccountBank;
use App\Model\Commercial\ClientMainSuppliers;
use App\Model\Commercial\ClientMainClients;
use App\Model\Commercial\ClientOwnerAndPartner;
use App\Model\Commercial\ClientOnGroup;
use App\Model\Commercial\ClientManagers;

use App\Model\Commercial\ClientVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Hash;
use App;
use Log;

class ManagerClient
{
    use FileManipulationTrait;
    use CommercialTrait;

    public $mng_model;
    public $mng_request;
    private $mng_id;

    public function __construct($request = null, $id = null)
    {
        $this->mng_model = null;
        $this->mng_id = $id;
        $this->mng_request = $request;

        if ($id)
        $this->mng_model = Client::with(['client_peoples_contact',
                                    'client_account_bank',
                                    'client_main_suppliers',
                                    'client_main_clients',
                                    'client_group',
                                    'client_on_group',
                                    'client_on_product_sales',
                                    'client_owner_and_partner',
                                    'client_version' => function($q) {
                                        $q->orderBy('id', 'DESC');
                                    },
                                    'salesman',
                                    'client_documents',
                                    'clientSubsidiary',
                                    'client_documents.contractSocial',
                                    'client_documents.balanceEquity',
									'client_documents.balanceEquity2Year',
								    'client_documents.balanceEquity3Year']
                                    )->where('id', $id)->first();

        if (!$this->mng_model)
        $this->mng_model = null;

    }

    public function editClient($is_analyze = false) {

        $data = $this->mng_request;

        if ($data->id == 0) {
            $client_verify = Client::where('identity', $data->identity)->first();
            if($client_verify)
                throw new \Exception('Cliente já está cadastrado!');
            else
                $client = new Client;
			
        } else {
            $client = Client::find($data->id);
            if(!$client)
                throw new \Exception('Cliente não encontrado!');
        }

        $data['social_capital'] = str_replace(',', '.',str_replace('.', '', $data['social_capital']));

        DB::beginTransaction();

        $client->fill($data->all());
        $client->save();

        $contact_client = json_decode($data->arr_contact_client); // salvou sim! vamos verificar outra coisa

        if (count($contact_client) > 0) {

            for ($i = 0; $i < count($contact_client); $i++) {

                $obj_contact = (array) $contact_client[$i];

                $add_contact = ClientPeoplesContact::where('client_id', $client->id)->where('type_contact', $obj_contact['type_contact'])->first();
                if(!$add_contact) {
                    $add_contact = new ClientPeoplesContact;
                }

                $add_contact->client_id = $client->id;
                $add_contact->name = $obj_contact['name'];
                $add_contact->office = $obj_contact['office'];
                $add_contact->email = $obj_contact['email'];
                $add_contact->phone = $obj_contact['phone'];
                $add_contact->type_contact = $obj_contact['type_contact'];
                $add_contact->save();
            }
        }
        else {
            throw new \Exception('Não foi adicionado contatos de clientes!');
        }

        if($data->is_internal == 1) {

            $add_group = ClientOnGroup::where('client_id', $client->id)->first();
            if(!$add_group) {
                $add_group = new ClientOnGroup;
            }
            $add_group->client_id = $client->id;
            $add_group->client_group_id = $data->client_group;
            $add_group->save();
        }    

        if(!$this->clientEditRelation(new ClientOnProductSales, $data->arr_product_sale, $client->id, 'client_id', 'product_sales_id'))
            throw new \Exception('Ocorreu um erro ao salvar produtos vendidos!'); DB::rollBack();

        if(!$this->clientEditRelation(new ClientAccountBank, $data->arr_account_client, $client->id, 'client_id', 'id'))
            throw new \Exception('Ocorreu um erro ao salvar contas bancárias!'); DB::rollBack();

        if(!$this->clientEditRelation(new ClientMainSuppliers, $data->arr_supplier_client, $client->id, 'client_id', 'id'))
            throw new \Exception('Ocorreu um erro ao salvar principais fornecedores!'); DB::rollBack();

        if(!$this->clientEditRelation(new ClientMainClients, $data->arr_main_client, $client->id, 'client_id', 'id'))
            throw new \Exception('Ocorreu um erro ao salvar principais clientes!'); DB::rollBack();

        if(!$this->clientEditRelation(new ClientOwnerAndPartner, $data->arr_owner_partner, $client->id, 'client_id', 'id'))
            throw new \Exception('Ocorreu um erro ao salvar proprietários e sócios!'); DB::rollBack();

        if(!$this->clientEditRelation(new ClientOnClient, $data->arr_subsidiary_client, $client->id, 'matriz_id', 'filial_id'))
            throw new \Exception('Ocorreu um erro ao salvar filiais!'); DB::rollBack();
		
		if(!$this->clientEditRelation(new ClientManagers, $data->arr_managers_client, $client->id, 'client_id', 'salesman_id'))
            throw new \Exception('Ocorreu um erro ao salvar gestores!'); DB::rollBack();
		
		/*if($data->is_external == 1) {
            if(!$this->veirifyDocuments($data->arr_documents))
                throw new \Exception('Documentos obrigatórios!'); DB::rollBack();    
        }*/

        if($this->clientEditDocuments($data->arr_documents, $client->id))
            throw new \Exception('Ocorreu um erro ao salvar os documentos!'); DB::rollBack();

        DB::commit();

        if (!$is_analyze) {
            $last_version = ClientVersion::where('client_id', $client->id)->orderBy('id', 'DESC')->first();
            if ($last_version) {

                DB::beginTransaction();

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
                )->where('id', $client->id)->first();

                $last_version->view = $this->renderViewClient($new_model);
                $last_version->save();


                DB::commit();
            }
        }

        $resetAnalyze = ClientVersion::withTrashed()->where('client_id', $client->id)->where('deleted_at', '!=', null)->first();
        if ($resetAnalyze) {
            ClientImdtAnalyze::where('version', $resetAnalyze->version)->where('client_id', $client->id)->delete();
            ClientCommercialAnalyze::where('version', $resetAnalyze->version)->where('client_id', $client->id)->delete();
            ClientFinancyAnalyze::where('version', $resetAnalyze->version)->where('client_id', $client->id)->delete();
            DB::connection('commercial')
                ->table('client_version') 
                ->where('client_id', $client->id) 
                ->where('deleted_at', '!=', null) 
                ->delete();

            $client->salesman_imdt_approv = 0;
            $client->salesman_imdt_reprov = 0;
            $client->commercial_is_approv = 0;
            $client->commercial_is_reprov = 0;
            $client->financy_approv = 0;
            $client->financy_reprov = 0;
			$this->model->revision_is_approv = 0;
			$this->model->revision_is_reprov = 0;
			$this->model->judicial_is_approv = 0;
			$this->model->judicial_is_reprov = 0;
            $client->save();
        }

        return $client->id;
    }

    private function clientEditRelation($model, $req, $id_client, $id_client_name, $id_verify) {

        $request_decode =  json_decode($req);

        $request = collect($request_decode);
        $request_pluck = $request->pluck($id_verify);

        $query = $model::where(''.$id_client_name.'', $id_client)->pluck($id_verify);

        $delete = $query->diff($request_pluck);
        $request_pluck = $request_pluck->diff($query);

        $model::whereIn($id_verify, $delete)->where(''.$id_client_name.'', $id_client)->delete();

        $arr = array();

        foreach ($request_pluck as $index => $val) {

            $req_values = (array) $request[$index];
            $req_values[''.$id_client_name.''] = $id_client;

            array_push($arr, $req_values);
        }

        if(!$model->insert($arr)) {
            return false;
        }
        return true;
    }

    private function clientEditDocuments($data, $id_client) {

        $req_decode = json_decode($data);
        $arr_req = (array)$req_decode[0];
		
		$contract_social_version = 0;
        $balance_equity_dre_flow_version = 0;
		$balance_equity_dre_flow_version_2 = 0;
        $balance_equity_dre_flow_version_3 = 0;

		foreach ($arr_req as $key => $val) {

            if($key == 'contract_social' && !empty($val)) {

                $client_version = ClientOnContractSocial::where('client_id', $id_client)->orderBy('id', 'DESC')->first();

                if($client_version == null) {
                    $contract = new ClientOnContractSocial;
                    $contract->client_id = $id_client;
                    $contract->url = $val;
                    $contract->version = 1;
                    $contract->save();

                    $contract_social_version = 1;
                } else {

                    if($client_version->url != $val) {

                        $contract_social_version = $client_version->version + 1;

                        $contract = new ClientOnContractSocial;
                        $contract->client_id = $id_client;
                        $contract->url = $val;
                        $contract->version = $contract_social_version;
                        $contract->save();
                    } else {
                        $contract_social_version = $client_version->version;
                    }
                }
            }

            if($key == 'balance_equity_dre_flow' && !empty($val)) {

                $balance_version = ClientOnBalanceEquityDreFlow::where('client_id', $id_client)->orderBy('id', 'DESC')->first();

                if($balance_version == null) {
                    $balance = new ClientOnBalanceEquityDreFlow;
                    $balance->client_id = $id_client;
                    $balance->url = $val;
                    $balance->version = 1;
                    $balance->save();    

                    $balance_equity_dre_flow_version = 1;

                } else {
                    
                    if($balance_version->url != $val) {

                        $balance_equity_dre_flow_version = $balance_version->version + 1;

                        $balance = new ClientOnBalanceEquityDreFlow;
                        $balance->client_id = $id_client;
                        $balance->url = $val;
                        $balance->version = $balance_equity_dre_flow_version;
                        $balance->save();
                    } else {
                        $balance_equity_dre_flow_version = $balance_version->version;
                    }
                }
            }

            if($key == 'balance_equity_dre_flow_2_year' && !empty($val)) {

                $balance_version_2 = ClientOnBalanceEquityDreFlow2Year::where('client_id', $id_client)->orderBy('id', 'DESC')->first();

                if($balance_version_2 == null) {
                    $balance_2 = new ClientOnBalanceEquityDreFlow2Year;
                    $balance_2->client_id = $id_client;
                    $balance_2->url = $val;
                    $balance_2->version = 1;
                    $balance_2->save();    

                    $balance_equity_dre_flow_version_2 = 1;

                } else {
                    
                    if($balance_version_2->url != $val) {

                        $balance_equity_dre_flow_version_2 = $balance_version_2->version + 1;

                        $balance = new ClientOnBalanceEquityDreFlow2Year;
                        $balance->client_id = $id_client;
                        $balance->url = $val;
                        $balance->version = $balance_equity_dre_flow_version_2;
                        $balance->save();
                    } else {
                        $balance_equity_dre_flow_version_2 = $balance_version_2->version;
                    }
                }
            }

            if($key == 'balance_equity_dre_flow_3_year' && !empty($val)) {

                $balance_version_3 = ClientOnBalanceEquityDreFlow3Year::where('client_id', $id_client)->orderBy('id', 'DESC')->first();

                if($balance_version_3 == null) {
                    $balance_3 = new ClientOnBalanceEquityDreFlow3Year;
                    $balance_3->client_id = $id_client;
                    $balance_3->url = $val;
                    $balance_3->version = 1;
                    $balance_3->save();    

                    $balance_equity_dre_flow_version_3 = 1;

                } else {
                    
                    if($balance_version_3->url != $val) {

                        $balance_equity_dre_flow_version_3 = $balance_version_3->version + 1;

                        $balance = new ClientOnBalanceEquityDreFlow3Year;
                        $balance->client_id = $id_client;
                        $balance->url = $val;
                        $balance->version = $balance_equity_dre_flow_version_3;
                        $balance->save();
                    } else {
                        $balance_equity_dre_flow_version_3 = $balance_version_3->version;
                    }
                }
            }
        }
        
        $arr_req['client_id'] = $id_client;
        $arr_req['contract_social_is_exception'] = $this->mng_request->contract_social_is_exception;
        $arr_req['balance_equity_dre_flow_is_exception'] = $this->mng_request->balance_equity_dre_flow_is_exception;
        $arr_req['declaration_regime_is_exception'] = $this->mng_request->declaration_regime_is_exception;
        $arr_req['card_cnpj_is_exception'] = $this->mng_request->card_cnpj_is_exception;
        $arr_req['card_ie_is_exception'] = $this->mng_request->card_ie_is_exception;
        $arr_req['apresentation_commercial_is_exception'] = $this->mng_request->apresentation_commercial_is_exception;
        $arr_req['proxy_representation_legal_is_exception'] = $this->mng_request->proxy_representation_legal_is_exception;
		$arr_req['certificate_debt_negative_federal_is_exception'] = $this->mng_request->certificate_debt_negative_federal_is_exception;
        $arr_req['certificate_debt_negative_sefaz_is_exception'] = $this->mng_request->certificate_debt_negative_sefaz_is_exception;
        $arr_req['certificate_debt_negative_labor_is_exception'] = $this->mng_request->certificate_debt_negative_labor_is_exception;
		
		$arr_req['contract_social'] = $contract_social_version;
        $arr_req['balance_equity_dre_flow'] = $balance_equity_dre_flow_version;
		
		$arr_req['balance_equity_dre_flow_2_year'] = $balance_equity_dre_flow_version_2;
        $arr_req['balance_equity_dre_flow_2_year_is_exception'] = $this->mng_request->balance_equity_dre_flow_2_year_is_exception;

        $arr_req['balance_equity_dre_flow_3_year'] = $balance_equity_dre_flow_version_3;
        $arr_req['balance_equity_dre_flow_3_year_is_exception'] = $this->mng_request->balance_equity_dre_flow_3_year_is_exception;

        $documents = ClientDocuments::where('client_id', $id_client)->first();
        if(!$documents) {
            $documents = new ClientDocuments;
            $documents->insert($arr_req);
        } else {
            $documents->update($arr_req);
        }
    }

    public function uploadDocuments() {

        $name = $this->mng_request->name_file;

        if ($this->mng_request->hasFile(''.$name.'')) {
            $response = $this->uploadS3(1, $this->mng_request->$name, $this->mng_request);

            if ($response['success']) {

                return $response['url'];
            } else {
                throw new \Exception('Não foi possível fazer upload do arquivo!');
            }
        }  else {
            throw new \Exception('Arquivo não adicionado para upload!');
        }
    }

    public function deleteDocument() {

        $name = $this->mng_request->name;

        $documents = ClientDocuments::where('client_id', $this->mng_request->client_id)->first();
        if($documents) {
            $documents->$name = '';
            $documents->save();

            if($name != 'contract_social' || $name != 'balance_equity_dre_flow') {
                removeS3($this->mng_request->url);
            }

        } else {
            throw new \Exception('Documento não encontrado!');
        }
    }
	
	private function veirifyDocuments($docs) {

        $arr = json_decode($docs, true)[0];

        foreach ($arr as $name => $doc) {

            if($name != "opt_balance_equity_dre_flow" || $name != "balance_equity_dre_flow_2_year" || 
               $name != "balance_equity_dre_flow_3_year" || $name != "proxy_representation_legal") {
                if($doc == "") {
                    return false;
                }
            }
        }
        return true;
    }
}
