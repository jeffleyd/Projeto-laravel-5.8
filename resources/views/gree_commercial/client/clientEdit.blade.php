<?php
$arr_product_sale = $client != null ? $client->client_on_product_sales: [];
$arr_account_client = $client != null ? $client->client_account_bank : [];
$arr_supplier_client = $client  != null ? $client->client_main_suppliers : [];
$arr_main_client = $client != null ? $client->client_main_clients : [];
$arr_owner_partner = $client != null ? $client->client_owner_and_partner : [];
$arr_contact_client = $client != null ? $client->client_peoples_contact : [];
$arr_subsidiary_client = $client != null ? $client->clientSubsidiary : [];
$arr_managers_client = $client != null ? $client->client_managers : [];
$arr_documents_client = $client ? $client->client_documents : null;

$arr_subsidiary = [];
if(count($arr_subsidiary_client) > 0) {

    foreach($arr_subsidiary_client as $key) {

        $obj_subsidiary = new stdClass;
        $obj_subsidiary->matriz_id = $key->pivot->matriz_id;
        $obj_subsidiary->filial_id = $key->pivot->filial_id;

        $arr_subsidiary[] = $obj_subsidiary;
    }
}

$arr_managers = [];
if(count($arr_managers_client) > 0) {

    foreach($arr_managers_client as $key) {

        $obj_managers = new stdClass;
        $obj_managers->client_id = $key->client_id;
        $obj_managers->salesman_id = $key->salesman_id;

        $arr_managers[] = $obj_managers;
    }
}

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

$obj = new stdClass;
if($arr_documents_client != null) {

    $obj->apresentation_commercial = $arr_documents_client ? $arr_documents_client->apresentation_commercial : '';
    $obj->balance_equity_dre_flow =  $arr_balance_equity ? $arr_balance_equity->last()->url : '';
    $obj->card_cnpj = $arr_documents_client ? $arr_documents_client->card_cnpj : '';
    $obj->card_ie = $arr_documents_client ? $arr_documents_client->card_ie : '';
    $obj->contract_social =  $arr_contract_social ? $arr_contract_social->last()->url : '';
    $obj->declaration_regime = $arr_documents_client ? $arr_documents_client->declaration_regime : '';
    $obj->proxy_representation_legal = $arr_documents_client ? $arr_documents_client->proxy_representation_legal : '';

    $obj->certificate_debt_negative_federal = $arr_documents_client ? $arr_documents_client->certificate_debt_negative_federal : '';
    $obj->certificate_debt_negative_sefaz = $arr_documents_client ? $arr_documents_client->certificate_debt_negative_sefaz : '';
    $obj->certificate_debt_negative_labor = $arr_documents_client ? $arr_documents_client->certificate_debt_negative_labor : '';

    $obj->balance_equity_dre_flow_2_year =  $arr_balance_equity_2_year ? $arr_balance_equity_2_year->last()->url : '';
    $obj->balance_equity_dre_flow_3_year =  $arr_balance_equity_3_year ? $arr_balance_equity_3_year->last()->url : '';
}
$arr_documents[] = $obj;

$group_id = $client && $client->group ? $client->group->id : 0;

$contact_client_purchase = null;
$contact_client_financial = null;
$contact_client_logistics = null;

foreach ($arr_contact_client as $key) {
    if($key->type_contact == 1) {
        $contact_client_purchase = $key;
    }
    if($key->type_contact == 2) {
        $contact_client_financial = $key;
    }
    if($key->type_contact == 3) {
        $contact_client_logistics = $key;
    }
}
?>

@extends('gree_commercial.layout')

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="/commercial/client/list">Cliente</a></li>
        <li class="active">Novo</li>
    </ul><!-- End .breadcrumb -->
@endsection

@section('content')

    <style>
        #table_archive>tbody>tr>td, #table_archive>thead>tr>th {
            /*text-align: center;*/
            vertical-align: middle;
        }
        #table-modal-contract>tbody>tr>td, #table-modal-contract>thead>tr>th {
            text-align: center;
        }
        form .error {
            color: #ff0000;
        }

        input:required:valid, textarea:required:valid, select:required:valid,
        .has-success .form-control, .has-success .form-control:focus {
            border-color: #bbbbbb;
        }

        .select-group {
            background-color: #eeeeee;
            border-color: #eeeeee;
            color: #555555;
            font-weight: 500;
        }

        .help-block {
            color: #bf3232;
        }
    </style>

    <header id="header-sec">
        <div class="inner-padding">
            <div class="pull-left">
                <div class="btn-group">
                    <button type="button" class="btn btn-default" id="btn_modal_analyze">
                        <i class="fa fa-floppy-o"></i>&nbsp; Salvar
                    </button>
                    @if ($id != 0)
                        <button type="button" onclick="window.open('/commercial/client/print/view/{{$id}}', '_blank')" class="btn btn-primary">
                            <i class="fa fa-print" style="color:#fff;"></i>&nbsp; Imprimir
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </header>
    <div class="window">
        <div class="actionbar">
            <div class="pull-left">
                <a href="#" class="btn small-toggle-btn" data-toggle-sidebar="left"></a>
                <ul class="ext-tabs">
                    <li class="active">
                        <a href="#content-tab-1">Informações do cliente</a>
                    </li>
                    <li>
                        <a href="#content-tab-2" id="tab-filial" @if ($client != null && $client->is_matriz == 0) style="display:none;" @endif>Filias</a>
                    </li>
                    <li>
                        <a href="#content-tab-3">Documentos</a>
                    </li>
                    @if ($id != 0)
                        <li>
                            <a href="#content-tab-4">Versões do cliente</a>
                        </li>
                        <li>
                            <a href="#content-tab-5">Histórico de aprovações</a>
                        </li>
                    @endif
                    <li>
                        <a href="#content-tab-6">Gestores</a>
                    </li>
                </ul>
            </div>
        </div>
        <form action="#" method="post" id="registration">
            <input type="hidden" name="arr_product_sale" id="arr_product_sale">
            <input type="hidden" name="arr_contact_client" id="arr_contact_client">
            <input type="hidden" name="arr_account_client" id="arr_account_client">
            <input type="hidden" name="arr_supplier_client" id="arr_supplier_client">
            <input type="hidden" name="arr_main_client" id="arr_main_client">
            <input type="hidden" name="arr_owner_partner" id="arr_owner_partner">
            <input type="hidden" name="arr_subsidiary_client" id="arr_subsidiary_client">
            <input type="hidden" name="arr_managers_client" id="arr_managers_client">
            <input type="hidden" name="name_file" id="name_file">
            <input type="hidden" name="arr_documents" id="arr_documents">
            <input type="hidden" name="is_internal" id="is_internal" value="1">
            <input type="hidden" name="id" value="{{$id}}">
			<input type="hidden" name="is_external" value="0">
            <div class="tab-content">
                <div id="content-tab-1" class="tab-pane active">
                    <div class="inner-padding">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget">
                                    <header>
                                        <h2>CADASTRO DO CLIENTE</h2>
                                    </header>
                                    <div>
                                        <div class="ext-tabs-vertical-wrapper ext-tabs-highlighted">
                                            <ul class="ext-tabs-vertical">
                                                <li class="active">
                                                    <a href="#content-tab-2-a">Dados Principais</a>
                                                </li>
                                                <li class="">
                                                    <a href="#content-tab-2-b">Local de Faturamento</a>
                                                </li>
                                                <li class="">
                                                    <a href="#content-tab-2-c">Local de Entrega</a>
                                                </li>
                                                <li>
                                                    <a href="#content-tab-2-d">Pessoa Contato</a>
                                                </li>
                                                <li>
                                                    <a href="#content-tab-2-e">Referências Comerciais</a>
                                                </li>
                                                <li>
                                                    <a href="#content-tab-2-f">Proprietário / Sócios</a>
                                                </li>
                                                <li>
                                                    <a href="#content-tab-2-g">Informações básicas</a>
                                                </li>
                                                <li>
                                                    <a href="#content-tab-2-h">Pós Aprovação</a>
                                                </li>
												<li>
                                                    <a href="#content-tab-2-i">Intenção de compra</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="content-tab-2-a" class="tab-pane active">
                                                    <div class="inner-padding">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <legend>Preencha os dados com atenção</legend>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Tipo <span class="asterisk">*</span></label>
                                                                            <select name="type_people" id="type_people" class="form-control" required>
                                                                                <option value="1" @if ($client != null && $client->type_people == 1) selected @endif>Jurídico</option>
                                                                                <option value="2" @if ($client != null && $client->type_people == 2) selected @endif>Funcionário</option>
                                                                                <option value="3" @if ($client != null && $client->type_people == 3) selected @endif>Pessoa Física</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label class="identity_label">@if($id != 0) @if($client->type_people == 1) CNPJ @else RG @endif @else CNPJ @endif <span class="asterisk">*</span></label>
                                                                            <input type="text" class="form-control" name="identity" id="identity" value="<?= $client ? $client->identity : '' ?>" placeholder="@if($id != 0) @if($client->type_people == 1) 00.000.000/0000-00 @else Informe o RG @endif @else 00.000.000/0000-00 @endif" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Razão Social / Nome <span class="asterisk">*</span></label>
                                                                            <input type="text" name="company_name" id="company_name" value="<?= $client ? $client->company_name : '' ?>" class="form-control" placeholder="Digite a razão social." required/>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label class="fantasy_name">Nome Fantasia @if($id != 0) @if($client->type_people == 1) <span class="asterisk">*</span> @endif @else <span class="asterisk">*</span> @endif</label>
                                                                            <input type="text" name="fantasy_name" id="fantasy_name" value="<?= $client ? $client->fantasy_name : '' ?>" class="form-control" placeholder="Digite o nome fantasia." @if($id != 0) @if($client->type_people == 1) required @endif @else required @endif/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Inscrição Estadual <span class="asterisk">*</span></label>
                                                                            <input type="text" name="state_registration" id="state_registration" value="<?= $client ? $client->state_registration : '' ?>" class="form-control" placeholder="Digite o número da IE." required/>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Inscrição Municipal</label>
                                                                            <input type="text" name="municipal_registration" id="municipal_registration" value="<?= $client ? $client->municipal_registration : '' ?>" class="form-control" placeholder="Digite a inscrição municipal."/>
                                                                        </div>
                                                                    </div>

                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-3">
                                                                            <label>Estabelecimento*</label>
                                                                        </div>
                                                                        <div class="col-sm-9">
                                                                            <div class="inline-labels">
                                                                                <label><input type="radio" name="is_matriz" value="1" @if ($client != null && $client->is_matriz == 1) checked @endif required/><span></span> Matriz</label>
                                                                                <label><input type="radio" name="is_matriz" value="0" @if ($client != null && $client->is_matriz == 0) checked @endif required/><span></span> Filial</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-20"></div>
                                                                    <hr/>
                                                                    <div class="spacer-20"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-8">
                                                                            <label>Endereço <span class="asterisk">*</span></label>
                                                                            <input type="text" name="address" id="address" value="<?= $client ? $client->address : '' ?>" class="form-control" placeholder="Digite o endereço e número." required/>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label>Bairro <span class="asterisk">*</span></label>
                                                                            <input type="text" name="district" id="district" value="<?= $client ? $client->district : '' ?>" class="form-control" placeholder="Digite o bairro." required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <label>Cidade <span class="asterisk">*</span></label>
                                                                            <input type="text" name="city" id="city" value="<?= $client ? $client->city : '' ?>"class="form-control" placeholder="Digite a cidade." required/>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label>Estado <span class="asterisk">*</span></label>
                                                                            <select name="state" id="state" class="form-control" required>
                                                                                <option value="">Selecione o estado</option>
                                                                                @foreach (config('gree.states') as $key => $value)
                                                                                    <option value="{{ $key }}" @if ($client != null && $key == $client->state) selected @endif>{{ $value }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label>CEP <span class="asterisk">*</span></label>
                                                                            <input type="text" name="zipcode" id="zipcode" value="<?= $client ? $client->zipcode : '' ?>" class="form-control" placeholder="00000-000" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-20"></div>
                                                                    <hr/>
                                                                    <div class="spacer-20"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Atividade econômica principal</label>
                                                                            <input type="text" name="code_description_ativity" id="code_description_ativity" value="<?= $client ? $client->code_description_ativity : '' ?>" class="form-control" placeholder="CNAE - Informe o código e descrição"/>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Inscrição SUFRAMA</label>
                                                                            <input type="text" name="suframa_registration" id="suframa_registration" value="<?= $client ? $client->suframa_registration : '' ?>" class="form-control" placeholder="Digite o número inscrição suframa."/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Regime especial ou ICMS por ST</label>
                                                                            <input type="text" name="especial_regime_icms_per_st" id="especial_regime_icms_per_st" value="<?= $client ? $client->especial_regime_icms_per_st : '' ?>" class="form-control" placeholder=""/>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Regime de Tributação <span class="asterisk">*</span></label>
                                                                            <fieldset style="padding: 0px 0px 0px 10px;">
                                                                                <div class="inline-labels">
                                                                                    <label><input type="radio" name="tax_regime" value="1" @if ($client != null && $client->tax_regime == 1) checked @endif required/><span></span> Lucro real</label>
                                                                                    <label><input type="radio" name="tax_regime" value="2" @if ($client != null && $client->tax_regime == 2) checked @endif required/><span></span> Presumido</label>
                                                                                    <label><input type="radio" name="tax_regime" value="3" @if ($client != null && $client->tax_regime == 3) checked @endif required/><span></span> Simples</label>
                                                                                    <label><input type="radio" name="tax_regime" value="4" @if ($client != null && $client->tax_regime == 4) checked @endif required/><span></span> Pessoa física</label>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-30"></div>
                                                                    <hr/>
                                                                    <div class="spacer-20"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Capital Social</label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">R$</span>
                                                                                <input type="text" name="social_capital" id="social_capital" value="<?= $client ? number_format($client->social_capital,2,',', '.') : '' ?>"class="form-control money" placeholder="0,00"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Junta Com. (NIRE) </label>
                                                                            <input type="text" name="nire_number" id="nire_number" value="<?= $client ? $client->nire_number : '' ?>" class="form-control" placeholder="Informe o número"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-20"></div>
                                                                    <fieldset>
                                                                        <legend>Tipo de Cliente  <span class="asterisk">*</span></legend>
                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="radio" name="type_client" value="1" @if ($client != null && $client->type_client == 1) checked @endif required/><span></span> Varejo Regional</label>
                                                                                    <label><input type="radio" name="type_client" value="2" @if ($client != null && $client->type_client == 2) checked @endif required/><span></span> Varejo Regional (Abertura)</label>
                                                                                    <label><input type="radio" name="type_client" value="9" @if ($client != null && $client->type_client == 9) checked @endif required/><span></span> Colaborador / Parceiro</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="radio" name="type_client" value="3" @if ($client != null && $client->type_client == 3) checked @endif required/><span></span> Especializado Regional</label>
                                                                                    <label><input type="radio" name="type_client" value="4" @if ($client != null && $client->type_client == 4) checked @endif required/><span></span> Especializado Nacional</label>
                                                                                    <label><input type="radio" name="type_client" value="7" @if ($client != null && $client->type_client == 7) checked @endif required/><span></span> E-commerce</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="radio" name="type_client" value="5" @if ($client != null && $client->type_client == 5) checked @endif required/><span></span> Refrigerista Nacional</label>
                                                                                    <label><input type="radio" name="type_client" value="6" @if ($client != null && $client->type_client == 6) checked @endif required/><span></span> Varejo Nacional</label>
                                                                                    <label><input type="radio" name="type_client" value="8" @if ($client != null && $client->type_client == 8) checked @endif required/><span></span> VIP</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                    <div class="spacer-20"></div>
                                                                    <fieldset>
                                                                        <legend>Produtos vendidos  <span class="asterisk">*</span></legend>
                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="checkbox" name="product_sale[]" value="1" data-model="clientOnProductSales" required/><span></span> Ar condicionado (doméstico)</label>
                                                                                    <label><input type="checkbox" name="product_sale[]" value="2" data-model="clientOnProductSales" required/><span></span> Eletrodoméstico</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="checkbox" name="product_sale[]" value="3" data-model="clientOnProductSales" required/><span></span> Maquina Chiller</label>
                                                                                    <label><input type="checkbox" name="product_sale[]" value="4" data-model="clientOnProductSales" required/><span></span> Não é revenda</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="checkbox" name="product_sale[]" value="5" data-model="clientOnProductSales" required/><span></span> VRF</label>
                                                                                    <label><input type="checkbox" name="product_sale[]" value="6" data-model="clientOnProductSales" required/><span></span> Outro</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="content-tab-2-b" class="tab-pane">
                                                    <div class="inner-padding">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <legend>Preencha os dados do local de cobrança</legend>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>CNPJ / RG</label>
                                                                            <div class="input-group input-group">
                                                                            <span class="input-group-addon">
                                                                                <select id="billing_location_type_people" name="billing_location_type_people" class="select-group">
                                                                                    <option value="1" @if ($client != null && $client->billing_location_type_people == 1) selected @endif>CNPJ</option>
                                                                                    <option value="2" @if ($client != null && $client->billing_location_type_people == 2) selected @endif>RG</option>
                                                                                </select>
                                                                            </span>
                                                                                <input type="text" class="form-control" name="billing_location_identity" id="billing_location_identity" value="<?= $client ? $client->billing_location_identity : '' ?>" placeholder="00.000.000/0000-00" required/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Inscrição Estadual</label>
                                                                            <input type="text"  name="billing_location_state_registration" value="<?= $client ? $client->billing_location_state_registration : '' ?>" class="form-control" placeholder="Digite a inscrição estadual" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Endereço</label>
                                                                            <input type="text"  name="billing_location_address" value="<?= $client ? $client->billing_location_address : '' ?>" class="form-control" placeholder="Digite o endereço e número." required/>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Cidade / UF</label>
                                                                            <input type="text"  name="billing_location_city_state" value="<?= $client ? $client->billing_location_city_state : '' ?>" class="form-control" placeholder="Digite a cidade e UF" required/>
                                                                        </div>
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="content-tab-2-c" class="tab-pane">
                                                    <div class="inner-padding">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <legend>Preencha os dados do local de entrega dos produtos (CD)</legend>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>CNPJ / RG</label>

                                                                            <div class="input-group input-group">
                                                                            <span class="input-group-addon">
                                                                                <select id="delivery_location_type_people" name="delivery_location_type_people" class="select-group">
                                                                                    <option value="1" @if ($client != null && $client->delivery_location_type_people == 1) selected @endif>CNPJ</option>
                                                                                    <option value="2" @if ($client != null && $client->delivery_location_type_people == 2) selected @endif>RG</option>
                                                                                </select>
                                                                            </span>
                                                                                <input type="text" class="form-control" name="delivery_location_identity" id="delivery_location_identity" value="<?= $client ? $client->delivery_location_identity : '' ?>" placeholder="00.000.000/0000-00" required/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Inscrição Estadual</label>
                                                                            <input type="text"  name="delivery_location_state_registration" value="<?= $client ? $client->delivery_location_state_registration : '' ?>" class="form-control" placeholder="Digite a inscrição estadual." required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Endereço</label>
                                                                            <input type="text"  name="delivery_location_address" value="<?= $client ? $client->delivery_location_address : '' ?>" class="form-control" placeholder="Digite o endereço e número." required/>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Cidade / UF</label>
                                                                            <input type="text"  name="delivery_location_city_state" value="<?= $client ? $client->delivery_location_city_state : '' ?>" class="form-control" placeholder="Digite cidade e UF" required/>
                                                                        </div>
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="content-tab-2-d" class="tab-pane">
                                                    <ul class="ext-tabs">
                                                        <li class="active">
                                                            <a href="#content-tab-3-a">Compras</a>
                                                        </li>
                                                        <li>
                                                            <a href="#content-tab-3-b">Financeiro</a>
                                                        </li>
                                                        <li>
                                                            <a href="#content-tab-3-c">Logística</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content clearfix">
                                                        <div id="content-tab-3-a" class="tab-pane active">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="inner-padding">
                                                                        <fieldset>
                                                                            <legend>Informe os dados do contato do setor compras</legend>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <label>Nome</label>
                                                                                    <input type="text" id="cp_name" name="cp_name" class="form-control" value="<?= $contact_client_purchase ? $contact_client_purchase->name : '' ?>" tab_parent="content-tab-2-d" placeholder="Nome completo" required/>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label>Cargo</label>
                                                                                    <input type="text"  id="cp_office" name="cp_office" class="form-control" value="<?= $contact_client_purchase ? $contact_client_purchase->office : '' ?>" placeholder="Digite o cargo." required/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="spacer-10"></div>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <label>E-mail</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                                                                        <input class="form-control" id="cp_email" name="cp_email" type="text" value="<?= $contact_client_purchase ? $contact_client_purchase->email : '' ?>" placeholder="compras@dominio.com.br" required/>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label>Telefone</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                                                        <input class="form-control phone" id="cp_phone" name_type="cp_phone" value="<?= $contact_client_purchase ? $contact_client_purchase->phone : '' ?>" type="text" placeholder="(00) 00000-0000" required/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="content-tab-3-b" class="tab-pane">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="inner-padding">
                                                                        <fieldset>
                                                                            <legend>Preencha os dados do contato do setor financeiro</legend>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <label>Nome</label>
                                                                                    <input type="text" id="cf_name" name="cf_name" class="form-control" value="<?= $contact_client_financial ? $contact_client_financial->name : '' ?>" placeholder="Nome completo" required/>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label>Cargo</label>
                                                                                    <input type="text"  id="cf_office" name="cf_office"class="form-control" value="<?= $contact_client_financial ? $contact_client_financial->office : '' ?>" required/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="spacer-10"></div>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <label>E-mail</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                                                                        <input class="form-control" id="cf_email" name="cf_email" type="text" value="<?= $contact_client_financial ? $contact_client_financial->email : '' ?>" placeholder="financeiro@dominio.com.br" required/>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label>Telefone</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                                                        <input class="form-control phone" id="cf_phone" name="cf_phone" type="text" value="<?= $contact_client_financial ? $contact_client_financial->phone : '' ?>" placeholder="(00) 00000-0000" required/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="content-tab-3-c" class="tab-pane">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="inner-padding">
                                                                        <fieldset>
                                                                            <legend>Preencha os dados do contato do setor de logística</legend>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <label>Nome</label>
                                                                                    <input type="text" id="cl_name" name="cl_name" value="<?= $contact_client_logistics ? $contact_client_logistics->name : '' ?>" class="form-control" placeholder="Nome completo"required/>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label>Cargo</label>
                                                                                    <input type="text"  id="cl_office" name="cl_office" value="<?= $contact_client_logistics ? $contact_client_logistics->office : '' ?>" class="form-control" required/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="spacer-10"></div>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <label>E-mail</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                                                                        <input class="form-control" id="cl_email" name="cl_email" type="text" value="<?= $contact_client_logistics ? $contact_client_logistics->email : '' ?>" placeholder="logistica@dominio.com.br" required/>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label>Telefone</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                                                        <input class="form-control phone" id="cl_phone" name="cl_phone" type="text" value="<?= $contact_client_logistics ? $contact_client_logistics->phone : '' ?>" placeholder="(00) 00000-0000" required/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="content-tab-2-e" class="tab-pane">
                                                    <ul class="ext-tabs">
                                                        <li class="active">
                                                            <a href="#content-tab-4-a">Dados Bancários</a>
                                                        </li>
                                                        <li>
                                                            <a href="#content-tab-4-b">Titular</a>
                                                        </li>
                                                        <li>
                                                            <a href="#content-tab-4-c">Principais Fornecedores</a>
                                                        </li>
                                                        <li>
                                                            <a href="#content-tab-4-d">Principais Clientes</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content clearfix">
                                                        <div id="content-tab-4-a" class="tab-pane active">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="inner-padding">
                                                                        <fieldset>
                                                                            <legend>Adicione os dados bancários</legend>
                                                                            <div class="row">
                                                                                <div class="col-sm-3">
                                                                                    <label>Banco</label>
                                                                                    <input type="text"  name="account_bank" id="account_bank" class="form-control" placeholder="Nome do Banco"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <label>Agência</label>
                                                                                    <input type="text"  name="account_agency" id="account_agency" class="form-control" placeholder="Número da Agência"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <label>Conta</label>
                                                                                    <input type="text"  name="account_current" id="account_current" class="form-control" placeholder="Conta corrente"/>
                                                                                </div>
                                                                                <div class="col-sm-3" style="top: 29px;">
                                                                                    <button type="button" class="btn btn-default" id="btn_add_account" data-type="1">
                                                                                        <i class="fa fa-plus"></i>&nbsp; Adicionar
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="spacer-30"></div>
                                                                            <div class="table-wrapper">
                                                                                <header>
                                                                                    <h3>Dados Bancários</h3>
                                                                                </header>
                                                                                <table class="table table-bordered table-striped" id="table_account" data-rt-breakpoint="600">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th scope="col" >Banco</th>
                                                                                        <th scope="col" >Agência</th>
                                                                                        <th scope="col" >Conta</th>
                                                                                        <th scope="col" class="th-4-action-btn">Action</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @if($client != null && !$client->client_account_bank->isEmpty())
                                                                                        @foreach ($client->client_account_bank as $index => $key)
                                                                                            <tr>
                                                                                                <td>{{ $key->bank }}</td>
                                                                                                <td>{{ $key->agency }}</td>
                                                                                                <td>{{ $key->account }}</td>
                                                                                                <td><a onclick='deleteRelTableClient(this)' data-id='<?= $index ?>' data-rel='arr_account_client' class='btn-less'><i class='fa fa-trash-o'></i></a></td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <tr class="not-registered-account">
                                                                                            <td colspan="4">Não há contas bancárias adiociondas.</td>
                                                                                        <tr>
                                                                                    @endif
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="content-tab-4-b" class="tab-pane">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="inner-padding">
                                                                        <fieldset>
                                                                            <legend>Informe os dados do titular</legend>
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <label>Titular / Nome / Razão Social</label>
                                                                                    <input type="text" name="title_name_reason_social" value="<?= $client ? $client->title_name_reason_social : '' ?>" class="form-control"/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <label>CNPJ / CPF</label>
                                                                                    <input type="text" class="form-control mask-cnpj-cpf" id="title_name_reason_social_identity" name="title_name_reason_social_identity" value="<?= $client ? $client->title_name_reason_social_identity : '' ?>"  placeholder="00.000.000/0000-00"/>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="content-tab-4-c" class="tab-pane">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="inner-padding">
                                                                        <fieldset>
                                                                            <legend>Adicione os dados do(s) fornecedore(s)</legend>
                                                                            <div class="row">
                                                                                <div class="col-sm-3">
                                                                                    <label>Fornecedor</label>
                                                                                    <input type="text"  name="supplier_name" id="supplier_name" class="form-control" placeholder="Nome do Fornecedor"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <label>Contato</label>
                                                                                    <input type="text"  name="supplier_contact" id="supplier_contact" class="form-control" placeholder="Contato"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <label>Telefone</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                                                        <input class="form-control phone" name="supplier_phone" id="supplier_phone" type="text" placeholder="(00) 00000-0000">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-3" style="top:29px;">
                                                                                    <button type="button" class="btn btn-default " id="btn_add_supplier" data-type="2">
                                                                                        <i class="fa fa-plus"></i>&nbsp; Adicionar
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="spacer-30"></div>
                                                                            <div class="table-wrapper">
                                                                                <header>
                                                                                    <h3>Fornecedores</h3>
                                                                                </header>
                                                                                <table class="table table-bordered table-striped" id="table_supplier" data-rt-breakpoint="600">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th scope="col" data-rt-column="Fornecedor">Fornecedor</th>
                                                                                        <th scope="col" data-rt-column="Contato">Contato</th>
                                                                                        <th scope="col" data-rt-column="Telefone">Telefone</th>
                                                                                        <th scope="col" class="th-4-action-btn">Ação</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @if($client != null && !$client->client_main_suppliers->isEmpty())
                                                                                        @foreach ($client->client_main_suppliers as $index => $key)
                                                                                            <tr>
                                                                                                <td>{{ $key->supplier_name }}</td>
                                                                                                <td>{{ $key->contact }}</td>
                                                                                                <td>{{ $key->phone }}</td>
                                                                                                <td><a onclick='deleteRelTableClient(this)' data-id='<?= $index ?>' data-rel='arr_supplier_client' class='btn-less'><i class='fa fa-trash-o'></i></a></td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <tr class="not-registered-supplier">
                                                                                            <td colspan="4">Não há fornecedores cadastrados.</td>
                                                                                        <tr>
                                                                                    @endif
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="content-tab-4-d" class="tab-pane">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="inner-padding">
                                                                        <fieldset>
                                                                            <legend>Adicione os dados do cliente</legend>
                                                                            <div class="row">
                                                                                <div class="col-sm-3">
                                                                                    <label>Cliente</label>
                                                                                    <input type="text" name="client_name" id="client_name" class="form-control" placeholder="Nome do Cliente"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <label>Contato</label>
                                                                                    <input type="text"  name="client_contact" id="client_contact" class="form-control" placeholder="Contato"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <label>Telefone</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                                                        <input class="form-control phone" name="client_phone" id="client_phone" type="text" placeholder="(00) 00000-0000"/>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-3" style="top: 29px;">
                                                                                    <button type="button" class="btn btn-default " id="btn_add_client" data-type="3">
                                                                                        <i class="fa fa-plus"></i>&nbsp; Adicionar
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="spacer-30"></div>
                                                                            <div class="table-wrapper">
                                                                                <header>
                                                                                    <h3>Clientes</h3>
                                                                                </header>
                                                                                <table class="table table-bordered table-striped" id="table_client" data-rt-breakpoint="600">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th scope="col" data-rt-column="Cliente">Cliente</th>
                                                                                        <th scope="col" data-rt-column="Contato2">Contato</th>
                                                                                        <th scope="col" data-rt-column="Telefone2">Telefone</th>
                                                                                        <th scope="col" class="th-4-action-btn">Ação</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @if($client != null && !$client->client_main_clients->isEmpty())
                                                                                        @foreach ($client->client_main_clients as $index => $key)
                                                                                            <tr>
                                                                                                <td>{{ $key->client_name }}</td>
                                                                                                <td>{{ $key->contact }}</td>
                                                                                                <td>{{ $key->phone }}</td>
                                                                                                <td><a onclick='deleteRelTableClient(this)' data-id='<?= $index ?>' data-rel='arr_main_client' class='btn-less'><i class='fa fa-trash-o'></i></a></td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <tr class="not-registered-clients">
                                                                                            <td colspan="4">Não há clientes cadastrados.</td>
                                                                                        <tr>
                                                                                    @endif
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="content-tab-2-f" class="tab-pane">
                                                    <div class="inner-padding">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <legend>Adicione os proprietários ou sócios</legend>
                                                                    <div class="row">
                                                                        <div class="col-sm-5">
                                                                            <label>Proprietário / Sócio</label>
                                                                            <input type="text" name="owner_partner" id="owner_partner"class="form-control" placeholder="Nome completo"/>
                                                                        </div>
                                                                        <div class="col-sm-5">
                                                                            <label>CPF / CNPJ</label>
                                                                            <input type="text" class="form-control mask-cnpj-cpf" name="owner_partner_identity" id="owner_partner_identity" placeholder="000.000.000-00"/>
                                                                        </div>
                                                                        <div class="col-sm-2" style="top:29px;">
                                                                            <button type="button" class="btn btn-default " id="btn_add_owner">
                                                                                <i class="fa fa-plus"></i>&nbsp; Adicionar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-30"></div>
                                                                    <div class="table-wrapper">
                                                                        <header>
                                                                            <h3>Proprietários ou sócios</h3>
                                                                        </header>
                                                                        <table class="table table-bordered table-striped" id="table_owner_partner" data-rt-breakpoint="600">
                                                                            <thead>
                                                                            <tr>
                                                                                <th scope="col" data-rt-column="Nome_prop">Nome</th>
                                                                                <th scope="col" data-rt-column="CPF">CPF</th>
                                                                                <th scope="col" class="th-4-action-btn">Ação</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @if($client != null && !$client->client_owner_and_partner->isEmpty())
                                                                                @foreach ($client->client_owner_and_partner as $index => $key)
                                                                                    <tr>
                                                                                        <td>{{ $key->name }}</td>
                                                                                        <td>{{ $key->identity }}</td>
                                                                                        <td><a onclick='deleteRelTableClient(this)' data-id='<?= $index ?>' data-rel='arr_owner_partner' class='btn-less'><i class='fa fa-trash-o'></i></a></td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            @else
                                                                                <tr class="not-registered-owner">
                                                                                    <td colspan="3">Não há proprietários / sócios cadastrados.</td>
                                                                                <tr>
                                                                            @endif
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="content-tab-2-g" class="tab-pane">
                                                    <div class="inner-padding">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <legend>Responda as perguntas</legend>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <label>Quantas filiais (loja e CD e sede) no Brasil? <span class="asterisk">*</span></label>
                                                                            <input type="text" name="quantity_filial_cds" value="<?= $client ? $client->quantity_filial_cds : '' ?>" class="form-control" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <label>Qual faturamento geral nos últimos anos?</label>
                                                                            <input type="text" name="billing_last_years" value="<?= $client ? $client->billing_last_years : '' ?>" class="form-control"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <label>Quantas unidades de ar.cond foram vendidas nos últimos anos? <span class="asterisk">*</span></label>
                                                                            <input type="text" name="units_air_sold_last_years" value="<?= $client ? $client->units_air_sold_last_years : '' ?>" class="form-control" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <label>Qual faturamento de ar.cond nos últimos anos?</label>
                                                                            <input type="text" name="billing_air_last_years" value="<?= $client ? $client->billing_air_last_years : '' ?>"class="form-control"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <label>Qual o volume de compra?</label>
                                                                            <input type="text" name="purchase_volume" value="<?= $client ? $client->purchase_volume : '' ?>" class="form-control"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <label>A empresa trabalha com IMPORTAÇÃO direta? <span class="asterisk">*</span></label>
                                                                            <div class="inline-labels">
                                                                                <label><input type="radio" name="works_import" value="1" @if ($client != null && $client->works_import == 1) checked @endif required/><span></span> Sim</label>
                                                                                <label><input type="radio" name="works_import" value="0" @if ($client != null && $client->works_import == 0) checked @endif required/><span></span> Não</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="content-tab-2-h" class="tab-pane">
                                                    <div class="inner-padding">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <legend>Pós aprovação</legend>
                                                                    <div class="row">
                                                                        <div class="col-sm-3">
                                                                            <label>Pagamento VPC <span class="asterisk"></span>
                                                                                <div class="table-tooltip" title="" data-original-title="Caso selecione líquido será descontado do valor total da nota os tributos ICMS, PIS, COFINS E FIT, o resultado sera a dedução X% do VPC, caso contrário o Bruto será dedução X% do VPC">
                                                                                    <i class="fa fa-info-circle"></i>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-sm-9">
                                                                            <div class="inline-labels">
                                                                                <label><input type="radio" name="vpc" value="1" @if ($client != null && $client->vpc == 1) checked @endif/><span></span> Líquido</label>
                                                                                <label><input type="radio" name="vpc" value="2" @if ($client != null && $client->vpc == 2) checked @endif/><span></span> Bruto</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Código do cliente</label>
                                                                            <div class="input-group input-group">
                                                                                <span class="input-group-addon">#</span>
                                                                                <input type="text" name="code" id="code" value="<?= $client ? $client->code : '' ?>" class="form-control"/>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-6">
                                                                            <label>Grupo <span class="asterisk"></span></label>
                                                                            <select name="client_group" id="client_group" value="1"  class="form-control client_group_dropdown" style="width: 100%;" data-model="clientOnGroup" required>
                                                                                <option value="<?= $client && $client->group ? $client->group->id : '' ?>"><?= $client && $client->group ? ''.$client->group->name.' (' .$client->group->code.')' : '' ?></option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Status do financeiro <span class="asterisk"></span></label>
                                                                            <select name="financy_status" id="financy_status" class="form-control">
                                                                                <option value="1" @if ($client != null && $client->financy_status == 1) selected @endif>Reprovado pelo financeiro</option>
                                                                                <option value="2" @if ($client != null && $client->financy_status == 2) selected @endif>Liberado antecipado</option>
                                                                                <option value="3" @if ($client != null && $client->financy_status == 3) selected @endif>Liberado antecipado e parcelado</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Crédito aprovado</label>
                                                                            <input type="text" name="financy_credit" id="financy_credit" value="<?= $client ? $client->financy_credit : '0' ?>" class="form-control money">
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Representante <span class="asterisk"></span></label>
                                                                            <select name="request_salesman_id" id="request_salesman_id" class="form-control" style="width: 100%;">
                                                                                <option value="<?= $client && $client->salesman ? $client->salesman->id : '' ?>"><?= $client && $client->salesman ? ''.$client->salesman->first_name.' '.$client->salesman->last_name.'  (' .$client->salesman->identity.')' : '' ?></option>
                                                                            </select>
                                                                        </div>
																		<div class="col-sm-6">
                                                                            <label>Comissão contratual</label>
                                                                            <input type="text" name="commission" id="commission" value="<?= $client ? number_format($client->commission, 2, '.', '') : '0.00' ?>" class="form-control pct">
                                                                        </div>
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
													
												<div id="content-tab-2-i" class="tab-pane">
                                                    <div class="inner-padding">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <legend>Intenção de compra</legend>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <textarea class="form-control" name="buy_intention" rows="14" placeholder="Informe neste campo a intenção de compra"><?= $client ? $client->buy_intention : '' ?></textarea>
                                                                        </div>
                                                                    </div>    
                                                                </fieldset>    
                                                            </div>    
                                                        </div>    
                                                    </div>    
                                                </div>  
												
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="content-tab-2" class="tab-pane">
                    <div class="inner-padding">
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Selecione a(s) filiai(s)</legend>
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <select class="select-subsidiary form-control" id="subsidiary" name="subsidiary" data-placeholder="Escolha a filial" style="width: 100%;"></select>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-default " id="btn_add_subsidiary">
                                                <i class="fa fa-plus"></i>&nbsp; Adicionar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="spacer-30"></div>
                                    <div class="table-wrapper">
                                        <header>
                                            <h3>Filias</h3>
                                        </header>
                                        <table class="table table-bordered table-striped" id="table_subsidiary" data-rt-breakpoint="600">
                                            <thead>
                                            <tr>
                                                <th scope="col" data-rt-column="Razão Social / Nome">Razão Social / Nome</th>
                                                <th scope="col" data-rt-column="CNPJ / RG">CNPJ / RG</th>
                                                <th scope="col" data-rt-column="Grupo">Grupo</th>
                                                <th scope="col" class="th-4-action-btn">Ação</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($client != null && count($arr_subsidiary_client) > 0)
                                                @foreach ($arr_subsidiary_client as $index => $key)
                                                    <tr>
                                                        <td>{{ $key->company_name }}</td>
                                                        <td>{{ $key->identity }}</td>
                                                        <td>{{ $key->group->name }}</td>
                                                        <td><a onclick='deleteRelTableClient(this)' data-id='<?= $index ?>' data-rel='arr_subsidiary_client' class='btn-less'><i class='fa fa-trash-o'></i></a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="not-registered-subsidiary">
                                                    <td colspan="4">Não há filias cadastradas.</td>
                                                <tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="content-tab-3" class="tab-pane">
                    <div class="inner-padding">
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Envie o(s) documento(s)</legend>
                                    <div class="spacer-30"></div>
                                    <div class="table-wrapper">
                                        <header>
                                            <h3>Documentos</h3>
                                        </header>
                                        <table class="table table-bordered table-striped" id="table_archive" data-rt-breakpoint="600">
                                            <thead>
                                            <tr>
                                                <th colspan="2" scope="col" data-rt-column="Arquivo">@if($client != null && $arr_documents_client) Atualizar arquivo  @else Arquivo @endif</th>
                                                <th scope="col" data-rt-column="Descrição">Descrição</th>
                                                <th scope="col" data-rt-column="Descrição">Status</th>
                                                <th scope="col" data-rt-column="Status">Tipo</th>
                                                <th scope="col" data-rt-column="Ação">Ação</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="contract_social" name="contract_social" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-delete" id="btn_del_contract_social" data_attr="contract_social" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_contract_social" data_attr="contract_social" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Contrato Social e últimas alterações contratuais</td>
                                                <td>
                                                    @if($client != null && $arr_contract_social != null && count($arr_contract_social) > 0)
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select class="simpleselect form-control doc_req" id="opt_contract_social" name="contract_social_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->contract_social_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->contract_social_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client !=null && $arr_contract_social != null && $arr_contract_social->last() ? $arr_contract_social->last()->url : ''?>" data-opt="contract_social" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                        <option value="2">Versões</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="card_cnpj" name="card_cnpj" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_card_cnpj" data_attr="card_cnpj" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_card_cnpj" data_attr="card_cnpj" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Cartão CNPJ (Receita Federal)</td>
                                                <td>
                                                    @if($client != null && $arr_documents_client != null && $arr_documents_client->card_cnpj != '')
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_card_cnpj" name="card_cnpj_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->card_cnpj_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->card_cnpj_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_documents_client != null && $arr_documents_client->card_cnpj != '' ? $arr_documents_client->card_cnpj : ''?>" data-opt="card_cnpj" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="card_ie" name="card_ie" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_card_ie" data_attr="card_ie" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_card_ie" data_attr="card_ie" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Cartão de Inscrição Estadual</td>
                                                <td>
                                                    @if($client != null && $arr_documents_client != null && $arr_documents_client->card_ie != '')
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_card_ie" name="card_ie_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->card_ie_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->card_ie_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_documents_client != null && $arr_documents_client->card_ie != '' ? $arr_documents_client->card_ie : ''?>" data-opt="card_ie" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="balance_equity_dre_flow" name="balance_equity_dre_flow" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-delete" id="btn_del_balance_equity_dre_flow" data_attr="balance_equity_dre_flow" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_balance_equity_dre_flow" data_attr="balance_equity_dre_flow" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Balanço Patrimonial/DRE e Fluxo de Caixa <b>Atual</b>
                                                    <div class="table-tooltip" title="Balanço Patrimonial/DRE e Fluxo de Caixa(ano vigente e ano anterior obrigatorios), se tiver de outros anos também pode enviar">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($client != null && $arr_balance_equity != null && count($arr_balance_equity) > 0)
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_balance_equity_dre_flow" name="balance_equity_dre_flow_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_balance_equity != null && $arr_balance_equity->last() ? $arr_balance_equity->last()->url : ''?>" data-opt="balance_equity_dre_flow" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                        <option value="2">Versões</option>
                                                    </select>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="balance_equity_dre_flow_2_year" name="balance_equity_dre_flow_2_year" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-delete" id="btn_del_balance_equity_dre_flow_2_year" data_attr="balance_equity_dre_flow_2_year" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_balance_equity_dre_flow_2_year" data_attr="balance_equity_dre_flow_2_year" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Balanço Patrimonial/DRE e Fluxo de Caixa <b>2º ano</b>
                                                    <div class="table-tooltip" title="Balanço Patrimonial/DRE e Fluxo de Caixa(ano vigente e ano anterior obrigatorios), se tiver de outros anos também pode enviar">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($client != null && $arr_balance_equity_2_year != null && count($arr_balance_equity_2_year) > 0)
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_balance_equity_dre_flow_2_year" name="balance_equity_dre_flow_2_year_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_2_year_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_2_year_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_balance_equity_2_year != null && $arr_balance_equity_2_year->last() ? $arr_balance_equity_2_year->last()->url : ''?>" data-opt="balance_equity_dre_flow_2_year" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                        <option value="2">Versões</option>
                                                    </select>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="balance_equity_dre_flow_3_year" name="balance_equity_dre_flow_3_year" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-delete" id="btn_del_balance_equity_dre_flow_3_year" data_attr="balance_equity_dre_flow_3_year" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_balance_equity_dre_flow_3_year" data_attr="balance_equity_dre_flow_3_year" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Balanço Patrimonial/DRE e Fluxo de Caixa <b>3º ano</b>
                                                    <div class="table-tooltip" title="Balanço Patrimonial/DRE e Fluxo de Caixa(ano vigente e ano anterior obrigatorios), se tiver de outros anos também pode enviar">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($client != null && $arr_balance_equity_3_year != null && count($arr_balance_equity_3_year) > 0)
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_balance_equity_dre_flow_3_year" name="balance_equity_dre_flow_3_year_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_3_year_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_3_year_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_balance_equity_3_year != null && $arr_balance_equity_3_year->last() ? $arr_balance_equity_3_year->last()->url : ''?>" data-opt="balance_equity_dre_flow_3_year" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                        <option value="2">Versões</option>
                                                    </select>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="proxy_representation_legal" name="proxy_representation_legal" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_proxy_representation_legal" data_attr="proxy_representation_legal" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_proxy_representation_legal" data_attr="proxy_representation_legal" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Procuração dos representantes legais e cópia dos documentos pessoais
                                                    <div class="table-tooltip" title="Procuração doa representantes legais e cópia dos documentos pessoais (caso a empresa seja administrada por pessoas fora do contrato social)">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($client != null && $arr_documents_client != null && $arr_documents_client->proxy_representation_legal != '')
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_proxy_representation_legal" name="proxy_representation_legal_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->proxy_representation_legal_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->proxy_representation_legal_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_documents_client != null && $arr_documents_client->proxy_representation_legal != '' ? $arr_documents_client->proxy_representation_legal : ''?>" data-opt="proxy_representation_legal" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="declaration_regime" name="declaration_regime" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_declaration_regime" data_attr="declaration_regime" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_declaration_regime" data_attr="declaration_regime" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Declaração de regime de tributação
                                                    <div class="table-tooltip" title="Declaração de regime de tributação (LUCRO REAL-ANEXO I, LUCRO PRESUMIDO-ANEXO II E SIMPLES NACIONAL ANEXO III)">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($client != null && $arr_documents_client != null && $arr_documents_client->declaration_regime != '')
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_declaration_regime" name="declaration_regime_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->declaration_regime_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->declaration_regime_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_documents_client != null && $arr_documents_client->declaration_regime != '' ? $arr_documents_client->declaration_regime : ''?>" data-opt="declaration_regime" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="apresentation_commercial" name="apresentation_commercial" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_apresentation_commercial" data_attr="apresentation_commercial" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_apresentation_commercial" data_attr="apresentation_commercial" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Apresentação comercial ou portfólio próprio da empresa
                                                    <div class="table-tooltip" title="Apresentação comercial ou portfólio próprio da empresa, Contendo imagens fotográficas da loja matriz, lista de pontos de vendas com nome fantasia, endereço, contato e gerente/reponsável da loja. É importante conter todas as informações comerciaos e história do cliente. Tipos de arquivos: PDF | PPT | WORD | IMAGENS">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($client != null && $arr_documents_client != null && $arr_documents_client->apresentation_commercial != '')
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_apresentation_commercial" name="apresentation_commercial_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->apresentation_commercial_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->apresentation_commercial_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_documents_client != null && $arr_documents_client->apresentation_commercial != '' ? $arr_documents_client->apresentation_commercial : ''?>" data-opt="apresentation_commercial" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="certificate_debt_negative_federal" name="certificate_debt_negative_federal" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_certificate_debt_negative_federal" data_attr="certificate_debt_negative_federal" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_certificate_debt_negative_federal" data_attr="certificate_debt_negative_federal" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Certidão negativa de debitos - Federal
                                                    <div class="table-tooltip" title="Certidão negativa de debitos - Federal">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_federal != '')
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_certificate_debt_negative_federal" name="certificate_debt_negative_federal_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_federal_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_federal_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_federal != '' ? $arr_documents_client->certificate_debt_negative_federal : ''?>" data-opt="certificate_debt_negative_federal" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="certificate_debt_negative_sefaz" name="certificate_debt_negative_sefaz" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_certificate_debt_negative_sefaz" data_attr="certificate_debt_negative_sefaz" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_certificate_debt_negative_sefaz" data_attr="certificate_debt_negative_sefaz" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Certidão negativa de debitos - Sefaz
                                                    <div class="table-tooltip" title="Certidão negativa de debitos - Sefaz">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_sefaz != '')
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_certificate_debt_negative_sefaz" name="certificate_debt_negative_sefaz_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_sefaz_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_sefaz_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_sefaz != '' ? $arr_documents_client->certificate_debt_negative_sefaz : ''?>" data-opt="certificate_debt_negative_sefaz" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="file" class="form-control" id="certificate_debt_negative_labor" name="certificate_debt_negative_labor" style="padding: 4px 5px;"/>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_certificate_debt_negative_labor" data_attr="certificate_debt_negative_labor" type="button" style="display: none;">x</button>
                                                            <button class="btn btn-default btn-upload" id="btn_certificate_debt_negative_labor" data_attr="certificate_debt_negative_labor" type="button">Enviar</button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>Certidão negativa de debitos - Trabalhistas
                                                    <div class="table-tooltip" title="Certidão negativa de debitos - Trabalhistas">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_labor != '')
                                                        <span class="label label-success">Arquivo enviado</span>
                                                    @else
                                                        <span class="label label-danger">Não enviado</span>
                                                    @endif
                                                </td>
                                                <td class="th-td-archive">
                                                    <select class="simpleselect form-control" id="opt_certificate_debt_negative_labor" name="certificate_debt_negative_labor_is_exception">
                                                        <option value="0" @if ($client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_labor_is_exception == 0) selected @endif>Obrigatório</option>
                                                        <option value="1" @if ($client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_labor_is_exception == 1) selected @endif>Exceção</option>
                                                    </select>
                                                </td>
                                                <td class="th-td-archive">
                                                    <select data-url="<?= $client != null && $arr_documents_client != null && $arr_documents_client->certificate_debt_negative_labor != '' ? $arr_documents_client->certificate_debt_negative_labor : ''?>" data-opt="certificate_debt_negative_labor" class="form-control" name="options-doc">
                                                        <option></option>
                                                        <option value="1">Visualizar</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($id != 0)
                    <div id="content-tab-4" class="tab-pane">
                        <div class="inner-padding">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Escolha a versão que gostaria de consultar</label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="version" class="form-control" style="width:100%">
                                        <option value=""></option>
                                        @foreach ($client->client_version()->withTrashed()->get() as $key)
                                            <option value="{{$key->version}}">Versão: {{$key->version}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <fieldset>
                                        <legend id="version-name"></legend>
                                        <iframe id="version-src" style="width: 100%; height: 1600px;" src=""></iframe>
                                    </fieldset>
                                    <div class="spacer-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="content-tab-5" class="tab-pane">
                        <div class="inner-padding">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Escolha a versão para ver o histórico</label>
                                    <select id="version_hist" class="form-control" style="width:100%">
                                        <option value=""></option>
                                        @foreach ($client->client_version()->withTrashed()->get() as $key)
                                            <option value="{{$key->version}}">Versão: {{$key->version}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="col-sm-12">
                                    <div class="table-wrapper">
                                        <header>
                                            <h3>ANÁLISES</h3>
                                        </header>
                                        <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                            <thead>
                                            <tr>
                                                <th scope="col" data-rt-column="Tipo do usuário">Tipo de usuário</th>
                                                <th scope="col" data-rt-column="Nome">Nome</th>
                                                <th scope="col" data-rt-column="Cargo">Cargo</th>
                                                <th scope="col" data-rt-column="Status">Status</th>
                                                <th scope="col" data-rt-column="Observação">Observação</th>
                                                <th scope="col" data-rt-column="Status">Versão</th>
                                            </tr>
                                            </thead>
                                            <tbody id="analyzes">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="spacer-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div id="content-tab-6" class="tab-pane">
                    <div class="inner-padding">
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Selecione o(s) gestores(s)</legend>
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <select class="select-managers form-control" id="managers" name="managers" data-placeholder="Escolha o gestor" style="width: 100%;"></select>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-default " id="btn_add_managers">
                                                <i class="fa fa-plus"></i>&nbsp; Adicionar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="spacer-30"></div>
                                    <div class="table-wrapper">
                                        <header>
                                            <h3>Gestores</h3>
                                        </header>
                                        <table class="table table-bordered table-striped" id="table_managers" data-rt-breakpoint="600">
                                            <thead>
                                            <tr>
                                                <th scope="col" data-rt-column="Nome">Nome</th>
                                                <th scope="col" data-rt-column="Email">Email</th>
                                                <th scope="col" class="th-4-action-btn">Ação</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($client != null && count($arr_managers_client) > 0)
                                                @foreach ($arr_managers_client as $index => $key)
                                                    <tr>
                                                        <td>{{ $key->salesman->full_name }} ({{ $key->salesman->identity }})</td>
                                                        <td>{{ $key->salesman->email }}</td>
                                                        <td><a onclick='deleteRelTableClient(this)' data-id='<?= $index ?>' data-rel='arr_managers_client' class='btn-less'><i class='fa fa-trash-o'></i></a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="not-registered-managers">
                                                    <td colspan="4">Não há gestores cadastradas.</td>
                                                <tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="spacer-30"></div>
    </div>

    <div class="modal fade" id="modal_contract" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Versões contrato social</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-bottom:-20px">
                        <div class="col-sm-12">

                            <div class="table-wrapper">
                                <header>
                                    <h3>Contrato Social</h3>
                                </header>
                                <table class="table table-bordered table-striped" id="table-modal-contract" data-rt-breakpoint="600">
                                    <thead>
                                    <tr>
                                        <th scope="col" data-rt-column="Arquivo">Arquivo</th>
                                        <th scope="col" data-rt-column="Arquivo">Versão</th>
                                        <th scope="col" data-rt-column="Criação">Criação</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($client != null && $arr_contract_social != null && count($arr_contract_social) > 0)
                                        @foreach ($arr_contract_social as $key)
                                            <tr>
                                                <td><a href="{{ $key->url }}" target="_blank" style="color: #8d23ef;">visualizar</a></td>
                                                <td>{{ $key->version }}</td>
                                                <td>{{ $key->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="not-registered-subsidiary">
                                            <td colspan="3">Não há versões do contrato social.</td>
                                        <tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="spacer-40"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_balance" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Versões do Balanço Patrimonial / DRE e Fluxo de Caixa</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-bottom:-20px">
                        <div class="col-sm-12">

                            <div class="table-wrapper">
                                <header>
                                    <h3>Balanço Patrimonial</h3>
                                </header>
                                <table class="table table-bordered table-striped" id="table-modal-contract" data-rt-breakpoint="600">
                                    <thead>
                                    <tr>
                                        <th scope="col" data-rt-column="Arquivo">Arquivo</th>
                                        <th scope="col" data-rt-column="Versão">Versão</th>
                                        <th scope="col" data-rt-column="Atualização">Atualização</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($client != null && $arr_balance_equity != null && count($arr_balance_equity) > 0)
                                        @foreach ($arr_balance_equity as $key)
                                            <tr>
                                                <td><a href="{{ $key->url }}" target="_blank" style="color: #8d23ef;">visualizar</a></td>
                                                <td>{{ $key->version }}</td>
                                                <td>{{ $key->updated_at }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="not-registered-subsidiary">
                                            <td colspan="3">Não há versões do Balanço Patrimonial / DRE e Fluxo de Caixa.</td>
                                        <tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="spacer-40"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_balance_2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Versões do Balanço Patrimonial / DRE e Fluxo de Caixa <b>2º ano</b></h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-bottom:-20px">
                        <div class="col-sm-12">

                            <div class="table-wrapper">
                                <header>
                                    <h3>Balanço Patrimonial</h3>
                                </header>
                                <table class="table table-bordered table-striped" id="table-modal-contract" data-rt-breakpoint="600">
                                    <thead>
                                    <tr>
                                        <th scope="col" data-rt-column="Arquivo">Arquivo</th>
                                        <th scope="col" data-rt-column="Versão">Versão</th>
                                        <th scope="col" data-rt-column="Atualização">Atualização</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($client != null && $arr_balance_equity_2_year != null && count($arr_balance_equity_2_year) > 0)
                                        @foreach ($arr_balance_equity_2_year as $key)
                                            <tr>
                                                <td><a href="{{ $key->url }}" target="_blank" style="color: #8d23ef;">visualizar</a></td>
                                                <td>{{ $key->version }}</td>
                                                <td>{{ $key->updated_at }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="not-registered-subsidiary">
                                            <td colspan="3">Não há versões do Balanço Patrimonial / DRE e Fluxo de Caixa <b>2º ano</b>.</td>
                                        <tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="spacer-40"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_balance_3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Versões do Balanço Patrimonial / DRE e Fluxo de Caixa <b>3º ano</b></h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-bottom:-20px">
                        <div class="col-sm-12">

                            <div class="table-wrapper">
                                <header>
                                    <h3>Balanço Patrimonial</h3>
                                </header>
                                <table class="table table-bordered table-striped" id="table-modal-contract" data-rt-breakpoint="600">
                                    <thead>
                                    <tr>
                                        <th scope="col" data-rt-column="Arquivo">Arquivo</th>
                                        <th scope="col" data-rt-column="Versão">Versão</th>
                                        <th scope="col" data-rt-column="Atualização">Atualização</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($client != null && $arr_balance_equity_3_year != null && count($arr_balance_equity_3_year) > 0)
                                        @foreach ($arr_balance_equity_3_year as $key)
                                            <tr>
                                                <td><a href="{{ $key->url }}" target="_blank" style="color: #8d23ef;">visualizar</a></td>
                                                <td>{{ $key->version }}</td>
                                                <td>{{ $key->updated_at }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="not-registered-subsidiary">
                                            <td colspan="3">Não há versões do Balanço Patrimonial / DRE e Fluxo de Caixa <b>3º ano</b>.</td>
                                        <tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="spacer-40"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Confirmar envio</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-bottom:-20px">
                        <div class="col-sm-12">
                            <strong><p>Deseja enviar para análise de aprovação?</p></strong>
                            <div class="spacer-40"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" id="btn_analyze">Fechar</button>
                    <button class="btn btn-primary pull-right" id="btn_not_analyse" onclick="save('/commercial/client/edit_do')">Não enviar para análise</button>
                    <!-- <button class="btn btn-danger pull-right" onclick="save('/commercial/client/edit_analyze')">Enviar para análise</button>-->
                </div>
            </div>
        </div>
    </div>

    <script>
        var arr_product_sale = {!! json_encode($arr_product_sale) !!},
            arr_account_client  = {!! json_encode($arr_account_client) !!},
            arr_supplier_client = {!! json_encode($arr_supplier_client) !!},
            arr_main_client = {!! json_encode($arr_main_client) !!},
            arr_owner_partner = {!! json_encode($arr_owner_partner) !!},
            arr_contact_client = {!! json_encode($arr_contact_client) !!},
            arr_subsidiary_client = {!! json_encode($arr_subsidiary) !!},
            arr_managers_client = {!! json_encode($arr_managers) !!},
            arr_documents = {!! json_encode($arr_documents) !!};
        localStorage.setItem("arr_documents", JSON.stringify(arr_documents));

        var group_id = {!! $group_id !!},
            client_id = {!! $id !!};

        function genHTML(object) {
            var html = '';
            for (let index = 0; index < object.length; index++) {
                const column = object[index];

                html += '<tr>';
                html += '<td>'+column.type_user+'</td>';
                html += '<td>'+column.name+'</td>';
                html += '<td>'+column.office+'</td>';
                html += '<td>';

                if (column.status == 1)
                    html += '<span class="label label-success">Aprovado</span>';
                else
                    html += '<span class="label label-danger">Reprovado</span>';

                html += '</td>';
                html += '<td>'+column.description+'</td>';
                html += '<td>'+column.version+'</td>';
                html += '</tr>';

            }

            return html;
        }
        function realodAnalyzes(object) {

            var html = '';
            //html += genHTML(object.financy);
            html += genHTML(object.commercial);
            html += genHTML(object.judicial);
            html += genHTML(object.revision);
            html += genHTML(object.imdt);

            $('#analyzes').html(html);
        }
        $(document).ready(function () {
			
            $('#version').change(function () {
                if($('#version').val() != '') {
                    $('#version-name').html('Versão: '+$('#version').val());
                    $('#version-src').attr('src', '/commercial/client/print/versions/view/{{$id}}/'+$('#version').val());
                }
            });

            $('#version_hist').change(function () {
                if($('#version_hist').val() == '') {
                    $('#analyzes').html('');
                } else {
                    block();
                    ajaxSend(`/commercial/client/analyze/history/approv`,{id: {{$id}}, version_hist: $('#version_hist').val()})
                        .then((response) => {
                            unblock();
                            if (response.data.length != 0)
                                realodAnalyzes(response.data);
                            else
                                $('#analyzes').html('');
                        })
                        .catch((error) => {
                            $error(error.message);
                            unblock();
                        });
                }
            });

            $.extend($.validator.messages, {
                required: "Este campo é obrigatório.",
                email: "Por favor insira um endereço de e-mail válido."
            });

            $("#registration").validate({
                rules: {},
                ignore:"ui-tabs-hide",
                messages: {
                    group_client: 'Necessário selecionar o grupo do cliente',
                    contact_purchase_mail: "Por favor insira um endereço de e-mail válido."
                },
                errorElement: "span",
                errorClass: "help-block",
                highlight: function (element, errorClass, validClass) {
                    $(element).removeClass(errorClass); //.removeClass(errorClass);
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).addClass(validClass); //.addClass(validClass);
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                errorPlacement: function (error, element) {
                    if (element.hasClass("select2-hidden-accessible")) {
                        error.insertAfter(element.next('span.select2'));
                    }
                    else if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    }
                    else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                        error.insertAfter(element.parent().parent());
                    }
                    else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                        error.appendTo(element.parent().parent());
                    }

                    else {
                        error.insertAfter(element);
                    }
                },
                invalidHandler: function(e, validator){
                    if(validator.errorList.length) {

                        $('.ext-tabs-vertical a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show');
                        $('.ext-tabs-vertical a[href="#' + $(validator.errorList[0].element).attr('tab_parent') + '"]').tab('show');
                        $('.ext-tabs a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show');
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            $("#btn_add_subsidiary").click(function () {

                var data = $('.select-subsidiary').select2('data');

                var object = {
                    id:0,
                    matriz_id: client_id,
                    filial_id: parseInt(data[0].id)
                };

                arr_subsidiary_client.push(object);

                var index = arr_subsidiary_client.length - 1;

                $("#table_subsidiary tbody").append("<tr class='tr-rem'>"+
                    "<td>" + data[0].company_name + "</td>"+
                    "<td>" + data[0].identity + "</td>"+
                    "<td>" + data[0].group + "</td>"+
                    "<td><a onclick='deleteRelTableClient(this)' data-id='"+index+"' data-rel='arr_subsidiary_client' class='btn-less'><i class='fa fa-trash-o'></i></a></td>"+
                    "</tr>");

                $('.select-subsidiary').val(null).trigger('change');
                $(".not-registered-subsidiary").hide();
            });

            $("#btn_add_managers").click(function () {

                var data = $('.select-managers').select2('data');

                var object = {
                    id:0,
                    client_id: client_id,
                    salesman_id: parseInt(data[0].id)
                };

                arr_managers_client.push(object);

                var index = arr_managers_client.length - 1;

                $("#table_managers tbody").append("<tr class='tr-rem'>"+
                    "<td>" + data[0].text + "</td>"+
                    "<td>" + data[0].email + "</td>"+
                    "<td><a onclick='deleteRelTableClient(this)' data-id='"+index+"' data-rel='arr_managers_client' class='btn-less'><i class='fa fa-trash-o'></i></a></td>"+
                    "</tr>");

                $('.select-managers').val(null).trigger('change');
                $(".not-registered-managers").hide();
            });

            $(".select-managers").select2({
                language: {
                    noResults: function () {
                        return 'Não existe gestor!';
                    }
                },
                ajax: {
                    url: '/commercial/salesman/dropdown',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;

                    },
                }
            });

            if(client_id != 0 ) {
                $(".select-subsidiary").select2({
                    language: {
                        noResults: function () {
                            return 'Não existem filiais relacionadas ao mesmo grupo!';
                        }
                    },
                    ajax: {
                        url: '/commercial/client/dropdown?group_id=' + group_id,
                        data: function (params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }
                            return query;

                        },
                    }
                });
            }

            $(".select-subsidiary").click(function(e) {

                var data = $('.client_group_dropdown').select2('data');
                if(data[0].id == "") {
                    return $error('Selecione o Grupo!');
                }
            });

            $(".client_group_dropdown").select2({
                language: {
                    noResults: function () {
                        return 'Grupo do cliente não existe!';
                    }
                },
                ajax: {
                    url: '/commercial/client/group/dropdown',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;

                    },
                }
            });

            $('.client_group_dropdown').on('select2:select', function (e) {

                group_id = e.params.data.id;

                $(".select-subsidiary").select2({
                    language: {
                        noResults: function () {
                            return 'Não existem filiais relacionadas ao mesmo grupo!';
                        }
                    },
                    ajax: {
                        url: '/commercial/client/dropdown?group_id=' + group_id,
                        data: function (params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }
                            return query;

                        },
                    }
                });
            });

            $("#request_salesman_id").select2({
                placeholder: "Selecione o representante",
                allowClear: true,
                language: {
                    noResults: function () {

                        return 'Representante não existe...';
                    }
                },
                ajax: {
                    url: '/commercial/salesman/dropdown',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;
                    }
                }
            });

            $("#btn_add_account, #btn_add_supplier, #btn_add_client").click(function () {

                var name_type = '';
                var type = $(this).attr('data-type');
                var value1, value2, value3;

                if(type == 1) {
                    value1 = $("#account_bank").val();
                    value2 = $("#account_agency").val();
                    value3 = $("#account_current").val();

                    name_type = 'account';
                    var object = {
                        id:0,
                        client_id: 0,
                        bank: value1,
                        agency: value2,
                        account: value3
                    };

                    var index = arr_account_client.length;
                    arr_account_client.push(object);

                    $("#account_bank, #account_agency, #account_current").val('');
                    $(".not-registered-account").hide();

                } else if(type == 2) {

                    value1 = $("#supplier_name").val();
                    value2 = $("#supplier_contact").val();
                    value3 = $("#supplier_phone").val();

                    name_type = 'supplier';
                    var object = {
                        id:0,
                        client_id: 0,
                        supplier_name: value1,
                        contact: value2,
                        phone: value3
                    };

                    var index = arr_supplier_client.length;
                    arr_supplier_client.push(object);

                    $(".not-registered-supplier").hide();
                    $("#supplier_name, #supplier_contact, #supplier_phone").val('');

                } else {

                    value1 = $("#client_name").val();
                    value2 = $("#client_contact").val(),
                        value3 = $("#client_phone").val();

                    name_type = 'client';
                    var object = {
                        id:0,
                        client_id: 0,
                        client_name: value1,
                        contact: value2,
                        phone: value3
                    };

                    var index = arr_main_client.length;
                    arr_main_client.push(object);

                    $(".not-registered-clients").hide();
                    $("#client_name, #client_contact, #client_phone").val('');
                }

                if(value1 == "" && type == 1) {
                    return $error('Informe o banco.');

                } else if (value2 == "" && type == 1) {
                    return $error('Informe o número da agência.');

                } else if (value3 == "" && type == 1) {
                    return $error('Informe o número da conta corrente.');

                } else if (value1 == "" && type == 2) {
                    return $error('Informe o fornecedor.');

                }  else if (value1 == "" && type == 3) {
                    return $error('Informe o nome do cliente.');

                } else if (value2 == "" && type == 3 || value2 == "" && type == 2) {
                    return $error('Informe o contato.');

                } else if (value3 == "" && type == 3 || value3 == "" && type == 2) {
                    return $error('Informe o telefone.');

                } else {

                    var name_rel = '';

                    if(name_type == 'account') {
                        name_rel = 'arr_account_client';
                    } else if(name_type == 'supplier') {
                        name_rel = 'arr_supplier_client';
                    } else if(name_type == 'client') {
                        name_rel = 'arr_main_client';
                    }

                    $("#table_"+ name_type +" tbody").append("<tr class='tr-rem'>"+
                        "<td>" + value1 + "</td>"+
                        "<td>" + value2 + "</td>"+
                        "<td>" + value3 + "</td>"+
                        "<td><a onclick='deleteRelTableClient(this)' data-id='"+ index +"' data-rel='"+name_rel+"' class='btn-less'><i class='fa fa-trash-o'></i></a></td>"+
                        "</tr>");
                }
            });

            $("#btn_add_owner").click(function () {

                var value1 = $("#owner_partner").val(),
                    value2 = $("#owner_partner_identity").val();

                if(value1 == "") {

                    return $error('Informe nome do proprietário ou sócio.');
                } else if (value2 == "") {

                    return $error('Informe o número do CPF / CNPJ');
                } else {

                    arr_owner_partner.push({
                        id:0,
                        client_id: 0,
                        name: value1,
                        identity: value2
                    });

                    var index = arr_owner_partner.length - 1;

                    $("#table_owner_partner tbody").append("<tr class='tr-rem'>"+
                        "<td>" + value1 + "</td>"+
                        "<td>" + value2 + "</td>"+
                        "<td><a onclick='deleteRelTableClient(this)' data-id='"+ index +"' data-rel='arr_owner_partner' class='btn-less'><i class='fa fa-trash-o'></i></a></td>"+
                        "</tr>");
                }
                $(".not-registered-owner").hide();
                $("#owner_partner, #owner_partner_identity").val('');
            });

            $('input[name="product_sale[]"]').click(function() {
                if ($(this).is(':checked')) {
                    arr_product_sale.push(
                        {product_sales_id: parseInt($(this).val())}
                    );
                } else {
                    var index = arr_product_sale.findIndex(x => x == $(this).val());
                    arr_product_sale.splice(index, 1);
                }
            });

            $('input[name="product_sale[]"]').each(function (i) {

                if(arr_product_sale != 0) {
                    if(arr_product_sale[i]) {
                        $('input[name="product_sale[]"][value="'+arr_product_sale[i].product_sales_id+'"]').prop('checked', true);
                    }
                }
            });

            $("#cp_name, #cp_office, #cp_email, #cp_phone").blur(function() {

                var obj_contact = {
                    type_contact: 1,
                    name: $("#cp_name").val(),
                    office: $("#cp_office").val(),
                    email: $("#cp_email").val(),
                    phone: $("#cp_phone").val()
                };
                arr_contact_client[0] = obj_contact;
            });

            $("#cf_name, #cf_office, #cf_email, #cf_phone").blur(function() {

                var obj_contact = {
                    type_contact: 2,
                    name: $("#cf_name").val(),
                    office: $("#cf_office").val(),
                    email: $("#cf_email").val(),
                    phone: $("#cf_phone").val()
                };
                arr_contact_client[1] = obj_contact;
            });

            $("#cl_name, #cl_office, #cl_email, #cl_phone").blur(function() {

                var obj_contact = {
                    id:0,
                    type_contact: 3,
                    name: $("#cl_name").val(),
                    office: $("#cl_office").val(),
                    email: $("#cl_email").val(),
                    phone: $("#cl_phone").val()
                };
                arr_contact_client[2] = obj_contact;
            });

            $(".btn-upload").click(function () {

                var name = $(this).attr('data_attr');

                if (($("#"+name+""))[0].files.length > 0) {
                    $("#name_file").val(name);
                } else {
                    return $error('Selecione um arquivo!');
                }

                block();
                ajaxSend('/commercial/client/documents/ajax', $("#registration").serialize(), 'POST', '60000', $("#registration")).then(function(result){

                    obj_documents = {};
                    if(result.success) {

                        var documents =  localStorage.getItem("arr_documents");
                        if (documents == null) {

                            obj_documents[''+ name +''] = result.url;
                            arr_documents.push(obj_documents);
                            localStorage.setItem("arr_documents", JSON.stringify(arr_documents));

                            $("#btn_"+name+"").css('display', 'none');
                            $("#btn_del_"+name+"").css('display', '');
                            $("#"+name+"").prop('disabled', true);

                            $("#"+name+"").prop('type','text');
                            $("#"+name+"").val(filename(obj[0][name]));

                        } else {

                            var obj = JSON.parse(documents);
                            obj[0][''+ name +''] = result.url;
                            localStorage.setItem("arr_documents", JSON.stringify(obj));

                            $("#btn_"+name+"").css('display', 'none');
                            $("#btn_del_"+name+"").css('display', '');
                            $("#"+name+"").prop('disabled', true);

                            $("#"+name+"").prop('type','text');
                            $("#"+name+"").val(filename(obj[0][name]));

                        }
                        unblock();
                    } else {
                        return $error(result.message);
                    }
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                });
            });

            $(".btn-delete").click(function () {

                var name = $(this).attr('data_attr');

                $("#btn_"+name+"").css('display', '');
                $("#btn_del_"+name+"").css('display', 'none');
                $("#"+name+"").prop('disabled', false);
                $("#"+name+"").prop('type','file');
            });

            $(".btn-upload-delete").click(function () {

                var name = $(this).attr('data_attr');

                $("#btn_"+name+"").css('display', '');
                $("#btn_del_"+name+"").css('display', 'none');
                $("#"+name+"").prop('disabled', false);
                $("#"+name+"").prop('type','file');

                var documents =  localStorage.getItem("arr_documents");
                var obj = JSON.parse(documents);

                if(client_id != 0) {

                    if(name != 'contract_social' || name!= 'balance_equity_dre_flow') {

                        ajaxSend('/commercial/client/document/delete/ajax', {url: obj[0][''+ name +''], name: name, client_id: client_id}, 'POST', '60000', '').then(function(result){

                            if(!result.success) {
                                return $error(result.message);
                            }
                        }).catch(function(err){
                            unblock();
                            $error(err.message)
                        });
                    }
                }

                delete obj[0][''+ name +''];
                localStorage.setItem("arr_documents", JSON.stringify(obj));
                $("#"+name+"").val('');
            });

            var documents =  localStorage.getItem("arr_documents");
            if (documents !== null) {

                var obj = JSON.parse(documents);

                Object.keys(obj[0]).forEach(function(key) {

                    if (obj[0][key] != null && obj[0][key] != "") {

                        $("#btn_"+key+"").css('display', 'none');
                        $("#btn_del_"+key+"").css('display', '');
                        $("#"+key+"").prop('disabled', true);

                        $("#"+key+"").prop('type','text');

                        $("#"+key+"").val(filename(obj[0][key]));
                    }
                });
            }

            $("#btn_modal_analyze").click(function() {
                $("#modal_confirm").modal('show');
            });

            $("select[name='options-doc']").change(function(e) {

                var url = $(this).attr("data-url"),
                    type = $(this).attr("data-opt");

                if($(this).val() == 1) {
                    window.open(''+url+'','_blank');
                }
                else if($(this).val() == 2 && type == 'contract_social') {
                    $("#modal_contract").modal('show');
                }
                else if($(this).val() == 2 && type == 'balance_equity_dre_flow') {
                    $("#modal_balance").modal('show');
                }
                else if($(this).val() == 2 && type == 'balance_equity_dre_flow_2_year') {
                    $("#modal_balance_2").modal('show');
                }
                else if($(this).val() == 2 && type == 'balance_equity_dre_flow_3_year') {
                    $("#modal_balance_3").modal('show');
                }

                $(this).val('');
            });

            @if ($id != 0)
            $("input[name=is_matriz]:radio").change(function () {
                if($(this).val() == 0) {
                    $("#tab-filial").hide();
                } else {
                    $("#tab-filial").show();
                }
            });
            @else
            $("#tab-filial").hide();
            @endif

            $('#billing_location_identity').mask('00.000.000/0000-00', {reverse: false});
            $('#delivery_location_identity').mask('00.000.000/0000-00', {reverse: false});
            @if($id != 0)
            @if($client->type_people == 1)
            $('#identity').mask('00.000.000/0000-00', {reverse: false});
            @endif
            @else
            $('#identity').mask('00.000.000/0000-00', {reverse: false});
            @endif

            $("#billing_location_type_people, #delivery_location_type_people, #type_people").change(function (e) {

                var selector = $(this).attr('name'), elem = null;

                if(selector == 'billing_location_type_people') {
                    elem = $('#billing_location_identity');
                } else if (selector == 'delivery_location_type_people') {
                    elem = $('#delivery_location_identity');
                } else if (selector == 'type_people') {
                    elem = $('#identity');
                }

                if($(this).val() == 1) {
                    if(selector == 'type_people') {
                        $('.identity_label').html('CNPJ <span class="asterisk">*</span>');
                    }
                    elem.mask('00.000.000/0000-00', {reverse: false});
                    elem.attr("placeholder", "00.000.000/0000-00");
                    elem.val('');
                    $('#fantasy_name').attr('required');
                    $('.fantasy_name').html('Nome Fantasia <span class="asterisk">*</span>');
                } else {
                    if(selector == 'type_people') {
                        $('.identity_label').html('RG <span class="asterisk">*</span>');
                    }
                    $('#fantasy_name').removeAttr('required');
                    $('.fantasy_name').html('Nome Fantasia');
                    elem.attr("placeholder", "Informe o RG");
                    elem.unmask();
                    elem.val('');
                }
            });

            var CpfCnpjMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
                },
                cpfCnpjpOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
                    }
                };
            $('.mask-cnpj-cpf').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
            $('#zipcode').mask('00000-000', {reverse: false});
            $('.phone').mask('(00) 00000-0000', {reverse: false});
            $('.money').mask('000.000.000.000.000,00', {reverse: true});
			$('.pct').mask('00.00', {reverse: true});

            var behavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                options = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(behavior.apply({}, arguments), options);
                    }
                };
            $('.phone').mask(behavior, options);

            $("#client").addClass('menu-open');
            $("#clientEdit").addClass('page-arrow active-page');
        });

        function save(url) {

            $("#arr_product_sale").val(JSON.stringify(arr_product_sale));
            $("#arr_contact_client").val(JSON.stringify(arr_contact_client));
            $("#arr_account_client").val(JSON.stringify(arr_account_client));
            $("#arr_supplier_client").val(JSON.stringify(arr_supplier_client));
            $("#arr_main_client").val(JSON.stringify(arr_main_client));
            $("#arr_subsidiary_client").val(JSON.stringify(arr_subsidiary_client));
            $("#arr_managers_client").val(JSON.stringify(arr_managers_client));

            if ($('#registration').valid()) {

                if(arr_owner_partner.length == 0) {
                    $('#modal_confirm').modal('hide');
                    return $error('Adicione os proprietários ou sócios!');
                } else {
                    $("#arr_owner_partner").val(JSON.stringify(arr_owner_partner));
                }

                var documents =  localStorage.getItem("arr_documents");
                if(documents == null) {
                    $('#modal_confirm').modal('hide');
                    return $error('Selecione os documentos!');
                } else {

                    var obj = JSON.parse(documents);

                    if($("#opt_contract_social").val() == 0) {
                        if(obj[0]["contract_social"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('O contrato social é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_card_cnpj").val() == 0) {
                        if(obj[0]["card_cnpj"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('O cartão CNPJ é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_card_ie").val() == 0) {
                        if(obj[0]["card_ie"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('Inscrição estadual é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_balance_equity_dre_flow").val() == 0) {
                        if(obj[0]["balance_equity_dre_flow"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('O balanço patrimonial/DRE/Fluxo de caixa é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_balance_equity_dre_flow_2_year").val() == 0) {
                        if(obj[0]["balance_equity_dre_flow_2_year"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('O balanço patrimonial/DRE/Fluxo de caixa 2º ano é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_balance_equity_dre_flow_3_year").val() == 0) {
                        if(obj[0]["balance_equity_dre_flow_3_year"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('O balanço patrimonial/DRE/Fluxo de caixa 3º ano é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_proxy_representation_legal").val() == 0) {
                        if(obj[0]["proxy_representation_legal"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('A procuração dos representantes legais é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_declaration_regime").val() == 0) {
                        if(obj[0]["declaration_regime"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('A declaração de regime é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_apresentation_commercial").val() == 0) {
                        if(obj[0]["apresentation_commercial"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('A apresentação comercial é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_certificate_debt_negative_federal").val() == 0) {
                        if(obj[0]["certificate_debt_negative_federal"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('Certidão negativa de debitos - Federal é obrigatório no cadastro!');
                        }
                    }
                    if($("#opt_certificate_debt_negative_sefaz").val() == 0) {
                        if(obj[0]["certificate_debt_negative_sefaz"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('Certidão negativa de debitos - Sefaz é obrigatório no cadastro!');
                        }
                    }

                    if($("#opt_certificate_debt_negative_labor").val() == 0) {
                        if(obj[0]["certificate_debt_negative_labor"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('Certidão negativa de debitos - Trabalhista é obrigatório no cadastro!');
                        }
                    }
                }

                block();
                $("#arr_documents").val(localStorage.getItem("arr_documents"));
                localStorage.removeItem('arr_documents');
                $('#registration').attr('action', url).submit();
            }

            $('#modal_confirm').modal('hide');
        }

        function filename(path){
            path = path.substring(path.lastIndexOf("/")+ 1);
            return (path.match(/[^.]+(\.[^?#]+)?/) || [])[0];
        }

        function deleteRelTableClient(el) {

            var index = $(el).attr('data-id'),
                name_arr = $(el).attr('data-rel'),
                arr_rel = window[name_arr];

            arr_rel.splice(index, 1);
            $(el).parent().parent().remove();
        }
    </script>

@endsection
