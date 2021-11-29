@extends('gree_commercial_external.layout')

@section('page-css')

    <link href="/elite/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/elite/dist/css/pages/ribbon-page.css" rel="stylesheet">
    <link href="/elite/dist/css/pages/stylish-tooltip.css" rel="stylesheet">
    <link href="/elite/assets/node_modules/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="/elite/dist/css/pages/tab-page.css" rel="stylesheet">

    <style>
        @media (min-width: 1025px) and (max-width: 1280px), (min-width: 1281px) {
            .vtabs {
                display: table;
                width: 100%;
            }
            .vtabs .tab-content {
                display: grid;
                padding: 20px;
                vertical-align: top;
            }
        }
        .has-danger .form-control {
            border-color: #e46a76;
        }

        .help-block {
            color: #bf3232;
        }
        .td-doc {
            width: 27%;
        }
        .btn-upload-delete {
            background-color: #03a9f3;
            color: #fff;
        }
    </style>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor">Cadastro do Cliente</h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <button type="button" class="btn btn-info d-none d-lg-block m-l-15" id="btn_modal_analyze"><i class="fa fa-save"></i>&nbsp; Salvar</button>
                                @if ($id != 0)
                                    <button type="button" onclick="window.open('/comercial/operacao/client/print/view/{{$id}}', '_blank')" class="btn btn-primary d-none d-lg-block m-l-15">
                                        <i class="fa fa-print" style="color:#fff;"></i>&nbsp; Imprimir
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" id="registration">
						<input type="hidden" name="is_external" value="1">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#content-tab-1" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Informações do cliente</span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#content-tab-2" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Documentos</span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#content-tab-3" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Versões do cliente</span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#content-tab-4" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Histórico de aprovações</span></a></li>
                        </ul>
                        <div class="tab-content tabcontent-border">
                            <div class="tab-pane active" id="content-tab-1" role="tabpanel">
                                <div class="inner-padding">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="hidden" name="arr_product_sale" id="arr_product_sale">
                                            <input type="hidden" name="arr_contact_client" id="arr_contact_client">
                                            <input type="hidden" name="arr_account_client" id="arr_account_client">
                                            <input type="hidden" name="arr_supplier_client" id="arr_supplier_client">
                                            <input type="hidden" name="arr_main_client" id="arr_main_client">
                                            <input type="hidden" name="arr_owner_partner" id="arr_owner_partner">
                                            <input type="hidden" name="arr_subsidiary_client" id="arr_subsidiary_client">
                                            <input type="hidden" name="arr_documents" id="arr_documents">
                                            <input type="hidden" name="name_file" id="name_file">
                                            <input type="hidden" name="is_internal" value="0">
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <div class="vtabs">
                                                <ul class="nav nav-tabs tabs-vertical ext-tabs-vertical" role="tablist" style="padding-top: 20px; width:193px;">
                                                    <li class="nav-item invalid-form-error-message"><a role="tab" data-toggle="tab" class="nav-link active" href="#content-tab-2-a">Dados principais</a></li>
                                                    <li class="nav-item invalid-form-error-message"><a role="tab" data-toggle="tab" class="nav-link" href="#content-tab-2-b">Endereços</a></li>
                                                    <li class="nav-item invalid-form-error-message"><a role="tab" data-toggle="tab" class="nav-link" href="#content-tab-2-d">Pessoa contato</a></li>
                                                    <li class="nav-item invalid-form-error-message"><a role="tab" data-toggle="tab" class="nav-link" href="#content-tab-2-e">Referências comercias</a></li>
                                                    <li class="nav-item invalid-form-error-message"><a role="tab" data-toggle="tab" class="nav-link" href="#content-tab-2-f">Proprietário / Sócios</a></li>
                                                    <li class="nav-item invalid-form-error-message"><a role="tab" data-toggle="tab" class="nav-link" href="#content-tab-2-g">informações Complementares</a></li>
													<li class="nav-item invalid-form-error-message"><a role="tab" data-toggle="tab" class="nav-link" href="#content-tab-2-h">Intenção de compra</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div id="content-tab-2-a" class="form-section tab-pane active">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset class="">
                                                                    <h5 class="card-title">Preencha os dados com atenção</h4>
                                                                        <div class="row">
                                                                            <div class="col-sm-6 form-group @if($errors->has('company_name')) has-danger @endif">
                                                                                <label>Razão Social / Nome <span class="text-danger">*</span></label>
                                                                                <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $client->company_name) }}" placeholder="Digite a razão social." required aria-required="true"/>
                                                                                @if($errors->has('company_name'))
                                                                                    <small class="form-control-feedback">{{$errors->first('company_name')}}</small>
                                                                                @endif
                                                                            </div>
                                                                            <div class="col-sm-6 form-group @if($errors->has('fantasy_name')) has-danger @endif">
                                                                                <label>Nome Fantasia</label>
                                                                                <input type="text" name="fantasy_name" id="fantasy_name" class="form-control" value="{{ old('fantasy_name', $client->fantasy_name) }}" placeholder="Digite o nome fantasia."/>
                                                                                @if($errors->has('fantasy_name'))
                                                                                    <small class="form-control-feedback">{{$errors->first('fantasy_name')}}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-6 form-group @if($errors->has('type_people')) has-danger @endif">
                                                                                <label>Tipo de Pessoa <span class="text-danger">*</span></label>
                                                                                <select name="type_people" id="type_people" class="form-control" required aria-required="true">
                                                                                    <option value="1" @if (old('type_people', $client->type_people) == 1) selected @endif>Jurídico</option>
                                                                                    <option value="2" @if (old('type_people', $client->type_people) == 2) selected @endif>Funcionário</option>
                                                                                    <option value="3" @if (old('type_people', $client->type_people) == 3) selected @endif>Pessoa Física</option>
                                                                                </select>

                                                                                @if($errors->has('type_people'))
                                                                                    <small class="form-control-feedback">{{$errors->first('type_people')}}</small>
                                                                                @endif
                                                                            </div>
                                                                            <div class="col-sm-6 form-group @if($errors->has('identity')) has-danger @endif">
                                                                                <label class="identity_label">CNPJ<span class="text-danger">*</span></label>
                                                                                <input type="text" name="identity" id="identity" class="form-control" value="{{ old('identity', $client->identity) }}" placeholder="00.000.000/0000-00" required aria-required="true"/>
                                                                                @if($errors->has('identity'))
                                                                                    <small class="form-control-feedback">{{$errors->first('identity')}}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-4 form-group @if($errors->has('state_registration')) has-danger @endif">
                                                                                <label>Inscrição Estadual <span class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control" name="state_registration" id="state_registration" value="{{ old('state_registration', $client->state_registration) }}" placeholder="Digite o número da IE." required aria-required="true"/>
                                                                                @if($errors->has('state_registration'))
                                                                                    <small class="form-control-feedback">{{$errors->first('state_registration')}}</small>
                                                                                @endif
                                                                            </div>

                                                                            <div class="col-sm-4 form-group @if($errors->has('municipal_registration')) has-danger @endif">
                                                                                <label>Inscrição Municipal</label>
                                                                                <input type="text" class="form-control" name="municipal_registration" id="municipal_registration" value="{{ old('municipal_registration', $client->municipal_registration) }}" placeholder="Digite a inscrição municipal."/>
                                                                                @if($errors->has('municipal_registration'))
                                                                                    <small class="form-control-feedback">{{$errors->first('municipal_registration')}}</small>
                                                                                @endif
                                                                            </div>
                                                                            <div class="col-sm-4 form-group @if($errors->has('is_matriz')) has-danger @endif">
                                                                                <label>Estabelecimento <span class="text-danger">*</span></label>
                                                                                <fieldset class="controls inline-labels" style="border: 1px solid #bbb; padding: 2.6px;">
                                                                                    <div class="custom-control" style="top:5px; padding-left:1rem;">
                                                                                        <label><input type="radio" name="is_matriz" value="1" <?php if(old('is_matriz', $client->is_matriz) == "1") { echo 'checked'; } ?> required/><span></span> Matriz</label>
                                                                                        <label style="margin-left:10px;"><input type="radio" name="is_matriz" value="0" <?php if(old('is_matriz', $client->is_matriz) == "0") { echo 'checked'; } ?> required/><span></span> Filial</label>
                                                                                    </div>
                                                                                </fieldset>
                                                                                @if($errors->has('is_matriz'))
                                                                                    <small class="form-control-feedback">{{$errors->first('is_matriz')}}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <hr style="margin-bottom: 30px;"/>
                                                                        <div class="row form-group @if($errors->has('code_description_ativity')) has-danger @endif">
                                                                            <div class="col-sm-3">
                                                                                <label>Atividade econômica principal</label>
                                                                            </div>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" id="code_description_ativity" name="code_description_ativity" value="{{ old('code_description_ativity', $client->code_description_ativity) }}" placeholder="CNAE - Informe o código e descrição" class="form-control"/>
                                                                                @if($errors->has('code_description_ativity'))
                                                                                    <small class="form-control-feedback">{{$errors->first('code_description_ativity')}}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="row form-group @if($errors->has('suframa_registration')) has-danger @endif">
                                                                            <div class="col-sm-3">
                                                                                <label>Inscrição SUFRAMA</label>
                                                                            </div>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" name="suframa_registration" id="suframa_registration" value="{{ old('suframa_registration', $client->suframa_registration) }}" class="form-control" placeholder="Digite o número inscrição suframa."/>
                                                                                @if($errors->has('suframa_registration'))
                                                                                    <small class="form-control-feedback">{{$errors->first('suframa_registration')}}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="row form-group @if($errors->has('especial_regime_icms_per_st')) has-danger @endif">
                                                                            <div class="col-sm-3">
                                                                                <label>Regime especial ou ICMS por ST</label>
                                                                            </div>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" name="especial_regime_icms_per_st" id="especial_regime_icms_per_st" value="{{ old('especial_regime_icms_per_st', $client->especial_regime_icms_per_st) }}" class="form-control"/>
                                                                                @if($errors->has('especial_regime_icms_per_st'))
                                                                                    <small class="form-control-feedback">{{$errors->first('especial_regime_icms_per_st')}}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="row form-group @if($errors->has('tax_regime')) has-danger @endif">
                                                                            <div class="col-sm-3">
                                                                                <label>Regime de Tributação <span class="text-danger">*</span></label>
                                                                            </div>
                                                                            <div class="col-sm-9">
                                                                                <fieldset class="controls inline-labels">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" name="tax_regime" id="radio_lucro_real" value="1" <?php if(old('tax_regime', $client->tax_regime) == "1") { echo 'checked'; } ?> required class="custom-control-input">
                                                                                        <label class="custom-control-label" for="radio_lucro_real">Lucro real</label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="2" name="tax_regime"  id="radio_lucro_presumido" <?php if(old('tax_regime', $client->tax_regime) == "2") { echo 'checked'; } ?> required class="custom-control-input">
                                                                                        <label class="custom-control-label" for="radio_lucro_presumido">Lucro Presumido</label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="3" name="tax_regime"  id="radio_simples" <?php if(old('tax_regime', $client->tax_regime) == "3") { echo 'checked'; } ?> required class="custom-control-input">
                                                                                        <label class="custom-control-label" for="radio_simples">Simples</label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="4" name="tax_regime"  id="radio_simples" <?php if(old('tax_regime', $client->tax_regime) == "4") { echo 'checked'; } ?> required class="custom-control-input">
                                                                                        <label class="custom-control-label" for="radio_simples">Pessoa física</label>
                                                                                    </div>
                                                                                </fieldset>
                                                                                @if($errors->has('tax_regime'))
                                                                                    <small class="form-control-feedback">{{$errors->first('tax_regime')}}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <hr style="margin-bottom:40px;"/>
                                                                        <div class="row form-group @if($errors->has('social_capital')) has-danger @endif">
                                                                            <div class="col-sm-3">
                                                                                <label>Capital Social</label>
                                                                            </div>
                                                                            <div class="col-sm-9">
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">R$</span>
                                                                                    </div>
                                                                                    <input type="text" class="form-control money" name="social_capital" id="social_capital" value="{{ old('social_capital', $client->social_capital) }}" placeholder="0,00"/>
                                                                                    @if($errors->has('social_capital'))
                                                                                        <small class="form-control-feedback">{{$errors->first('social_capital')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row form-group @if($errors->has('nire_number')) has-danger @endif">
                                                                            <div class="col-sm-3">
                                                                                <label>Junta Com. (NIRE)</label>
                                                                            </div>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" id="nire_number" name="nire_number" class="form-control" value="{{ old('nire_number', $client->nire_number) }}" placeholder="Informe o número" />
                                                                                @if($errors->has('nire_number'))
                                                                                    <small class="form-control-feedback">{{$errors->first('nire_number')}}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <hr style="margin-bottom:40px"/>
                                                                        <div class="row form-group @if($errors->has('type_client')) has-danger @endif">
                                                                            <div class="col-sm-3">
                                                                                <label>Tipo de Cliente</label>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="radio" name="type_client" value="1" <?php if(old('type_client', $client->type_client) == "1") { echo 'checked'; } ?> required/><span></span> Varejo Regional</label>
                                                                                    <label><input type="radio" name="type_client" value="2" <?php if(old('type_client', $client->type_client) == "2") { echo 'checked'; } ?> required/><span></span> Varejo Regional (Abertura)</label>
                                                                                    <label><input type="radio" name="type_client" value="7" <?php if(old('type_client', $client->type_client) == "7") { echo 'checked'; } ?> required/><span></span> E-commerce</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="radio" name="type_client" value="3" <?php if(old('type_client', $client->type_client) == "3") { echo 'checked'; } ?> required/><span></span> Especializado Regional</label>
                                                                                    <label><input type="radio" name="type_client" value="4" <?php if(old('type_client', $client->type_client) == "4") { echo 'checked'; } ?> required/><span></span> Especializado Nacional</label>
                                                                                    <br><label><input type="radio" name="type_client" value="8" <?php if(old('type_client', $client->type_client) == "8") { echo 'checked'; } ?> required/><span></span> VIP</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="radio" name="type_client" value="5" <?php if(old('type_client', $client->type_client) == "5") { echo 'checked'; } ?> required/><span></span> Refrigerista Nacional</label>
                                                                                    <label><input type="radio" name="type_client" value="6" <?php if(old('type_client', $client->type_client) == "6") { echo 'checked'; } ?> required/><span></span> Varejo Nacional</label>
                                                                                    <label><input type="radio" name="type_client" value="9" <?php if(old('type_client', $client->type_client) == "9") { echo 'checked'; } ?> required/><span></span> Colaborador / Parceiro</label>
                                                                                </div>
                                                                            </div>
                                                                            @if($errors->has('type_client'))
                                                                                <small class="form-control-feedback">{{$errors->first('type_client')}}</small>
                                                                            @endif
                                                                        </div>
                                                                        <hr style="margin-bottom: 40px;"/>
                                                                        <div class="row form-group @if($errors->has('product_sale[]')) has-danger @endif">
                                                                            <div class="col-sm-3">
                                                                                <label>Produtos vendidos</label>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="checkbox" name="product_sale[]" value="1" @if( is_array(old('product_sale', $client->product_sale)) && in_array(1, old('product_sale', $client->product_sale))) checked @endif required/><span></span> Ar condicionado (doméstico)</label>
                                                                                    <label><input type="checkbox" name="product_sale[]" value="2" @if( is_array(old('product_sale', $client->product_sale)) && in_array(2, old('product_sale', $client->product_sale))) checked @endif required/><span></span> Eletrodoméstico</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="checkbox" name="product_sale[]" value="3" @if( is_array(old('product_sale', $client->product_sale)) && in_array(3, old('product_sale', $client->product_sale))) checked @endif required/><span></span> Maquina Chiller</label>
                                                                                    <br><label><input type="checkbox" name="product_sale[]" value="4" @if( is_array(old('product_sale', $client->product_sale)) && in_array(4, old('product_sale', $client->product_sale))) checked @endif required/><span></span> Não é revenda</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <div class="stacked-labels">
                                                                                    <label><input type="checkbox" name="product_sale[]" value="5" @if( is_array(old('product_sale', $client->product_sale)) && in_array(5, old('product_sale', $client->product_sale))) checked @endif required/><span></span> VRF</label>
                                                                                    <br><label><input type="checkbox" name="product_sale[]" value="6" @if( is_array(old('product_sale', $client->product_sale)) && in_array(6, old('product_sale', $client->product_sale))) checked @endif required/><span></span> Outros</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                @if($errors->has('product_sales[]'))
                                                                                    <small class="form-control-feedback">{{$errors->first('product_sales[]')}}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="content-tab-2-b" class="tab-pane">
                                                        <ul class="nav nav-tabs customtab" role="tablist">
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link active" data-toggle="tab" href="#endereco_principal" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Endereço Principal</span></a> </li>
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link" data-toggle="tab" href="#endereco_faturamento" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Local de  Faturamento (Cobrança)</span></a> </li>
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link" data-toggle="tab" href="#endereco_entrega" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Local de Entrega dos Produtos(CD)</span></a> </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active form-section" id="endereco_principal" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset class="">
                                                                            <div class="row">
                                                                                <div class="col-sm-6 form-group @if($errors->has('address')) has-danger @endif">
                                                                                    <label>Endereço <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="address" id="address" value="{{ old('address', $client->address) }}" required aria-required="true" class="form-control" tab_parent="content-tab-2-b" placeholder="Digite o endereço completo"/>
                                                                                    @if($errors->has('address'))
                                                                                        <small class="form-control-feedback">{{$errors->first('address')}}</small>
                                                                                    @endif
                                                                                </div>

                                                                                <div class="col-sm-6 form-group @if($errors->has('address')) has-danger @endif">
                                                                                    <label>Bairro <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="district" id="district" value="{{ old('district', $client->district) }}" required aria-required="true" class="form-control" placeholder="Digite o bairro completo"/>
                                                                                    @if($errors->has('address'))
                                                                                        <small class="form-control-feedback">{{$errors->first('address')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-4 form-group @if($errors->has('state')) has-danger @endif">
                                                                                    <label>Estado <span class="text-danger">*</span></label>

                                                                                    <select class="form-control" name="state" id="state" required>
                                                                                        <option value="">Selecione o estado</option>
                                                                                        @foreach (config('gree.states') as $key => $value)
                                                                                            <option value="{{ $key }}" @if ($key == old('state', $client->state)) selected @endif>{{ $value }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    @if($errors->has('state'))
                                                                                        <small class="form-control-feedback">{{$errors->first('state')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-4 form-group @if($errors->has('city')) has-danger @endif">
                                                                                    <label>Cidade <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="city" id="city" value="{{ old('city', $client->city) }}" required aria-required="true" class="form-control" placeholder="Digite a cidade"/>
                                                                                    @if($errors->has('state'))
                                                                                        <small class="form-control-feedback">{{$errors->first('state')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-4 form-group @if($errors->has('zipcode')) has-danger @endif">
                                                                                    <label>Cep <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode', $client->zipcode) }}" required aria-required="true" class="form-control" placeholder="00000-000"/>
                                                                                    @if($errors->has('zipcode'))
                                                                                        <small class="form-control-feedback">{{$errors->first('zipcode')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane form-section" id="endereco_faturamento" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset class="">
                                                                            <div class="row">
                                                                                <div class="col-sm-5 form-group @if($errors->has('billing_location_identity')) has-danger @endif">
                                                                                    <label>CNPJ / RG<span class="text-danger">*</span></label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">
                                                                                            <select id="billing_location_type_people" name="billing_location_type_people" class="select-group" style="height: 38px;background-color: #eeeeee;border-color: #eeeeee;">
                                                                                                <option value="1" @if ($client->billing_location_type_people == 1) selected @endif>CNPJ</option>
                                                                                                <option value="2" @if ($client->billing_location_type_people == 2) selected @endif>RG</option>
                                                                                            </select>
                                                                                        </span>
                                                                                        <input type="text" class="form-control" name="billing_location_identity" id="billing_location_identity" value="{{ old('billing_location_identity', $client->billing_location_identity) }}" tab_parent="endereco_faturamento" placeholder="00.000.000/0000-00" required aria-required="true"/>
                                                                                    </div>
                                                                                    @if($errors->has('billing_location_identity'))
                                                                                        <small class="form-control-feedback">{{$errors->first('billing_location_identity')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-5 form-group @if($errors->has('billing_location_state_registration')) has-danger @endif">
                                                                                    <label>Inscrição Estadual <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="billing_location_state_registration" id="billing_location_state_registration" value="{{ old('billing_location_state_registration', $client->billing_location_state_registration) }}" required aria-required="true" class="form-control" placeholder="Digite a inscrição estadual"/>
                                                                                    @if($errors->has('billing_location_state_registration'))
                                                                                        <small class="form-control-feedback">{{$errors->first('billing_location_state_registration')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-2 form-group">
                                                                                    <button type="button" style="margin-top: 30px;" onclick="copiarEnderedo(1)"class="btn btn-info" >Copiar Endereço</button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-8 form-group @if($errors->has('billing_location_address')) has-danger @endif">
                                                                                    <label>Endereço <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="billing_location_address" id="billing_location_address" value="{{ old('billing_location_address', $client->billing_location_address) }}" required aria-required="true" class="form-control" placeholder="Digite o endereço completo"/>
                                                                                    @if($errors->has('billing_location_address'))
                                                                                        <small class="form-control-feedback">{{$errors->first('billing_location_address')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-4 form-group @if($errors->has('billing_location_city_state')) has-danger @endif">
                                                                                    <label>Cidade / UF <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="billing_location_city_state" id="billing_location_city_state" value="{{ old('billing_location_city_state', $client->billing_location_city_state) }}" required aria-required="true" class="form-control" placeholder="Digite a cidade e UF"/>
                                                                                    @if($errors->has('billing_location_city_state'))
                                                                                        <small class="form-control-feedback">{{$errors->first('billing_location_city_state')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane form-section" id="endereco_entrega" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset class="">
                                                                            <div class="row">
                                                                                <div class="col-sm-5 form-group @if($errors->has('delivery_location_identity')) has-danger @endif">
                                                                                    <label>CNPJ / RG<span class="text-danger">*</span></label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">
                                                                                            <select id="delivery_location_type_people" name="delivery_location_type_people" class="select-group" style="height: 38px;background-color: #eeeeee;border-color: #eeeeee;">
                                                                                                <option value="1" @if ($client->delivery_location_type_people == 1) selected @endif>CNPJ</option>
                                                                                                <option value="2" @if ($client->delivery_location_type_people == 2) selected @endif>RG</option>
                                                                                            </select>
                                                                                        </span>
                                                                                        <input type="text" name="delivery_location_identity" id="delivery_location_identity" value="{{ old('delivery_location_identity', $client->delivery_location_identity) }}" tab_parent="content-tab-2-b" required aria-required="true" class="form-control" placeholder="00.000.000/0000-00"/>
                                                                                    </div>
                                                                                    @if($errors->has('delivery_location_identity'))
                                                                                        <small class="form-control-feedback">{{$errors->first('delivery_location_identity')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-5 form-group @if($errors->has('delivery_location_state_registration')) has-danger @endif">
                                                                                    <label>Inscrição Estadual <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="delivery_location_state_registration" id="delivery_location_state_registration" value="{{ old('delivery_location_state_registration', $client->delivery_location_state_registration) }}" required aria-required="true" class="form-control" placeholder="Digite a inscrição estadual"/>
                                                                                    @if($errors->has('delivery_location_state_registration'))
                                                                                        <small class="form-control-feedback">{{$errors->first('delivery_location_state_registration')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-2 form-group">
                                                                                    <button type="button" style="margin-top: 30px;" onclick="copiarEnderedo(1)"class="btn btn-info" >Copiar Endereço</button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-8 form-group @if($errors->has('delivery_location_address')) has-danger @endif">
                                                                                    <label>Endereço <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="delivery_location_address" id="delivery_location_address" value="{{ old('delivery_location_address', $client->delivery_location_address) }}" required aria-required="true" class="form-control" placeholder="Digite o endereço completo de entrega"/>
                                                                                    @if($errors->has('delivery_location_address'))
                                                                                        <small class="form-control-feedback">{{$errors->first('delivery_location_address')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-4 form-group @if($errors->has('delivery_location_city_state')) has-danger @endif">
                                                                                    <label>Cidade / UF <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="delivery_location_city_state" id="delivery_location_city_state" value="{{ old('delivery_location_city_state', $client->delivery_location_city_state) }}" required aria-required="true" class="form-control" placeholder="Digite cidade e UF"/>
                                                                                    @if($errors->has('delivery_location_city_state'))
                                                                                        <small class="form-control-feedback">{{$errors->first('delivery_location_city_state')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="content-tab-2-d" class="tab-pane">
                                                        <ul class="nav nav-tabs customtab" role="tablist">
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link active" data-toggle="tab" href="#content-tab-3-a" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Compras</span></a> </li>
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link" data-toggle="tab" href="#content-tab-3-b" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Financeiro</span></a> </li>
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link" data-toggle="tab" href="#content-tab-3-c" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Logística</span></a> </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane form-section active" id="content-tab-3-a">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset class="">
                                                                            <div class="row">
                                                                                <div class="col-sm-6 form-group @if($errors->has('cp_name')) has-danger @endif">
                                                                                    <label>Nome<span class="text-danger">*</span></label>
                                                                                    <input type="text" name="cp_name" id="cp_name" value="{{ old('cp_name', $contact_purchase->name) }}" required aria-required="true" class="form-control" tab_parent="content-tab-2-d" placeholder="Nome completo"/>
                                                                                    @if($errors->has('cp_name'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cp_name')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-6 form-group @if($errors->has('cp_office')) has-danger @endif">
                                                                                    <label>Cargo<span class="text-danger">*</span></label>
                                                                                    <input type="text" name="cp_office" id="cp_office" value="{{ old('cp_office', $contact_purchase->office) }}" required aria-required="true" class="form-control" placeholder="Digite o cargo"/>
                                                                                    @if($errors->has('cp_office'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cp_office')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-6 form-group @if($errors->has('cp_email')) has-danger @endif">
                                                                                    <label>E-mail <span class="text-danger">*</span></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="ti-email"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="cp_email" id="cp_email" value="{{ old('cp_email', $contact_purchase->email) }}" required aria-required="true" class="form-control" placeholder="compras@dominio.com.br"/>
                                                                                    </div>
                                                                                    @if($errors->has('cp_email'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cp_email')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-6 form-group @if($errors->has('cp_phone')) has-danger @endif">
                                                                                    <label>Fone <span class="text-danger">*</span></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="mdi mdi-phone"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="cp_phone" id="cp_phone" value="{{ old('cp_phone', $contact_purchase->phone) }}" required aria-required="true" class="form-control phone" placeholder="(00) 00000-0000"/>
                                                                                    </div>
                                                                                    @if($errors->has('cp_phone'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cp_phone')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="content-tab-3-b" class="tab-pane form-section">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset class="">
                                                                            <div class="row">
                                                                                <div class="col-sm-6 form-group @if($errors->has('cf_name')) has-danger @endif">
                                                                                    <label>Nome<span class="text-danger">*</span></label>
                                                                                    <input type="text" name="cf_name" id="cf_name" value="{{ old('cf_name', $contact_financial->name) }}" required aria-required="true" class="form-control" placeholder="Nome completo"/>
                                                                                    @if($errors->has('cf_name'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cf_name')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-6 form-group @if($errors->has('cf_office')) has-danger @endif">
                                                                                    <label>Cargo<span class="text-danger">*</span></label>
                                                                                    <input type="text" name="cf_office" id="cf_office" value="{{ old('cf_office', $contact_financial->office) }}" required aria-required="true" class="form-control" placeholder="Digite o cargo"/>
                                                                                    @if($errors->has('cf_office'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cf_office')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-6 form-group @if($errors->has('cf_email')) has-danger @endif">
                                                                                    <label>E-mail <span class="text-danger">*</span></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="ti-email"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="cf_email" id="cf_email" value="{{ old('cf_email', $contact_financial->email) }}" required aria-required="true" class="form-control" placeholder="financeiro@dominio.com.br"/>
                                                                                    </div>
                                                                                    @if($errors->has('cf_email'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cf_email')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-6 form-group @if($errors->has('cf_phone')) has-danger @endif">
                                                                                    <label>Fone <span class="text-danger">*</span></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="mdi mdi-phone"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="cf_phone" id="cf_phone" value="{{ old('cf_phone', $contact_financial->phone) }}" required aria-required="true" class="form-control phone" placeholder="(00) 00000-0000"/>
                                                                                    </div>
                                                                                    @if($errors->has('cf_phone'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cf_phone')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="content-tab-3-c" class="tab-pane">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset class="">
                                                                            <div class="row">
                                                                                <div class="col-sm-6 form-group @if($errors->has('cl_name')) has-danger @endif">
                                                                                    <label>Nome<span class="text-danger">*</span></label>
                                                                                    <input type="text" name="cl_name" id="cl_name" value="{{ old('cl_name', $contact_logistics->name) }}" required aria-required="true" class="form-control" tab_parent="content-tab-2-d" placeholder="Nome completo"/>
                                                                                    @if($errors->has('cl_name'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cl_name')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-6 form-group @if($errors->has('cl_office')) has-danger @endif">
                                                                                    <label>Cargo<span class="text-danger">*</span></label>
                                                                                    <input type="text" name="cl_office" id="cl_office" value="{{ old('cl_office', $contact_logistics->office) }}" required aria-required="true" class="form-control" placeholder="Digite o cargo"/>
                                                                                    @if($errors->has('cl_office'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cl_office')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-6 form-group @if($errors->has('cl_email')) has-danger @endif">
                                                                                    <label>E-mail <span class="text-danger">*</span></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="ti-email"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="cl_email" id="cl_email" value="{{ old('cl_email', $contact_logistics->email) }}" required aria-required="true" class="form-control" placeholder="logistica@dominio.com.br"/>
                                                                                    </div>
                                                                                    @if($errors->has('cl_email'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cl_email')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-sm-6 form-group @if($errors->has('cl_email')) has-danger @endif">
                                                                                    <label>Fone <span class="text-danger">*</span></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="mdi mdi-phone"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="cl_phone" id="cl_phone" value="{{ old('cl_phone', $contact_logistics->phone) }}" required aria-required="true" class="form-control phone" placeholder="(00) 00000-0000"/>
                                                                                    </div>
                                                                                    @if($errors->has('cl_phone'))
                                                                                        <small class="form-control-feedback">{{$errors->first('cl_phone')}}</small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="content-tab-2-e" class="tab-pane">
                                                        <ul class="nav nav-tabs customtab" role="tablist">
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link active" data-toggle="tab" href="#content-tab-4-a" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Dados Bancários</span></a> </li>
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link" data-toggle="tab" href="#content-tab-4-b" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Titular</span></a> </li>
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link" data-toggle="tab" href="#content-tab-4-c" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Principais Fornecedores</span></a> </li>
                                                            <li class="nav-item invalid-form-error-message"> <a class="nav-link" data-toggle="tab" href="#content-tab-4-d" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Principais Clientes</span></a> </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div id="content-tab-4-a" class="tab-pane form-section active" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset>
                                                                            <h5 class="card-title">Adicione os dados Bancários</h5>
                                                                            <div class="row">
                                                                                <div class="col-sm-3">
                                                                                    <input type="text" name="account_bank" id="account_bank" placeholder="Nome do Banco" class="form-control"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <input type="text" name="account_agency" id="account_agency" placeholder="Número da Agência" class="form-control"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <input type="text" name="account_current" id="account_current" placeholder="Conta Corrente" class="form-control"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <button type="button" class="btn btn-info" id="btn_add_account" data-type="1"><i class="fa fa-plus"></i> Adicionar</button>
                                                                                </div>
                                                                            </div>
                                                                            <div style="margin-top:30px">
                                                                                <table class="table table-bordered table-striped" id="table_account">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Dados Bancários</b></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="col" data-rt-column="Nome">Banco</th>
                                                                                        <th scope="col" data-rt-column="Email">Agência</th>
                                                                                        <th scope="col" data-rt-column="Email">Conta</th>
                                                                                        <th scope="col" class="th-4-action-btn">Ação</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @if(count($arr_account_client) > 0)
                                                                                        @foreach ($arr_account_client as $index => $key)
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
                                                            <div id="content-tab-4-b" class="tab-pane form-section" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset>
                                                                            <h5 class="card-title">Informe os dados do titular</h5>
                                                                            <div class="row">
                                                                                <div class="col-sm-12 form-group">
                                                                                    <label>Titular / Nome / Razão Social</label>
                                                                                    <input type="text" class="form-control" name="title_name_reason_social" id="title_name_reason_social" value="{{ old('title_name_reason_social', $client->title_name_reason_social) }}"/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-12 form-group">
                                                                                    <label>CNPJ / CPF</label>
                                                                                    <input type="text" class="form-control mask-cnpj-cpf" id="title_name_reason_social_identity" name="title_name_reason_social_identity" value="{{ old('title_name_reason_social_identity', $client->title_name_reason_social_identity) }}" placeholder="00.000.000/0000-00"/>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="content-tab-4-c" class="tab-pane form-section">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset>
                                                                            <h5 class="card-title">Adicione os dados dos fornecedores</h5>
                                                                            <div class="row">
                                                                                <div class="col-sm-3">
                                                                                    <input type="text" id="supplier_name" name="supplier_name" placeholder="Nome do Fornecedor" class="form-control"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <input type="text" id="supplier_contact" name="supplier_contact" placeholder="Contato do Fornecedor" class="form-control"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <input type="text" id="supplier_phone" name="supplier_phone" placeholder="Telefone" class="form-control phone"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <button type="button" class="btn btn-info " id="btn_add_supplier" data-type="2">
                                                                                        <i class="fa fa-plus"></i>&nbsp; Adicionar
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div style="margin-top:30px">
                                                                                <table class="table table-bordered table-striped" id="table_supplier">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Fornecedores</b></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="col" data-rt-column="Nome">Fornecedor</th>
                                                                                        <th scope="col" data-rt-column="Email">Contato</th>
                                                                                        <th scope="col" data-rt-column="Email">Telefone</th>
                                                                                        <th scope="col" class="th-4-action-btn">Ação</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @if(count($arr_supplier_client) > 0)
                                                                                        @foreach ($arr_supplier_client as $index => $key)
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
                                                            <div id="content-tab-4-d" class="tab-pane form-section">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <fieldset>
                                                                            <h5 class="card-title">Adicione os dados dos clientes</h5>
                                                                            <div class="row">
                                                                                <div class="col-sm-3">
                                                                                    <input type="text" id="client_name" name="client_name" placeholder="Nome do Cliente" class="form-control"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <input type="text" id="client_contact" name="client_contact" placeholder="Contato do Cliente" class="form-control"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <input type="text" id="client_phone" name="client_phone" placeholder="Telefone do Cliente" class="form-control phone"/>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <button type="button" class="btn btn-info" id="btn_add_client" data-type="3">
                                                                                        <i class="fa fa-plus"></i>&nbsp; Adicionar
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div style="margin-top:30px">
                                                                                <table class="table table-bordered table-striped" id="table_client" data-rt-breakpoint="600">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Clientes</b></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="col" data-rt-column="Nome">Cliente</th>
                                                                                        <th scope="col" data-rt-column="Email">Contato</th>
                                                                                        <th scope="col" data-rt-column="Email">Telefone</th>
                                                                                        <th scope="col" class="th-4-action-btn">Ação</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @if(count($arr_main_client) > 0)
                                                                                        @foreach ($arr_main_client as $index => $key)
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
                                                                                        <tr>                                                                                            <tr>
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
                                                    <div id="content-tab-2-f" class="tab-pane">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <h5 class="card-title">Adicione os proprietários ou sócios</h5>
                                                                    <div class="row">
                                                                        <div class="col-sm-5">
                                                                            <input type="text" class="form-control" id="owner_partner" name="owner_partner" placeholder="Nome Completo"/>
                                                                        </div>
                                                                        <div class="col-sm-5">
                                                                            <input type="text" class="form-control mask-cnpj-cpf" id="owner_partner_identity" name="owner_partner_identity" placeholder="000.000.000-00"/>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <button type="button" class="btn btn-info" id="btn_add_owner">
                                                                                <i class="fa fa-plus"></i>&nbsp; Adicionar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div style="margin-top:30px">
                                                                        <table class="table table-bordered table-striped" id="table_owner_partner">
                                                                            <thead>
                                                                            <tr>
                                                                                <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Proprietários ou sócios</b></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="col">Nome</th>
                                                                                <th scope="col">CPF</th>
                                                                                <th scope="col">Ação</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @if(count($arr_owner_partner) > 0)
                                                                                @foreach ($arr_owner_partner as $index => $key)
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
                                                    <div id="content-tab-2-g" class="tab-pane">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <div class="row">
                                                                        <div class="col-sm-12 form-group @if($errors->has('quantity_filial_cds')) has-danger @endif">
                                                                            <label>Quantas filiais (loja e CD e sede) no Brasil? <span class="text-danger">*</span></label>
                                                                            <input type="text" name="quantity_filial_cds" value="{{ old('quantity_filial_cds', $client->quantity_filial_cds) }}" required aria-required="true" class="form-control"/>
                                                                            @if($errors->has('quantity_filial_cds'))
                                                                                <small class="form-control-feedback">{{$errors->first('quantity_filial_cds')}}</small>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12 form-group">
                                                                            <label>Qual faturamento geral nos últimos anos?</label>
                                                                            <input type="text" name="billing_last_years" value="{{ old('billing_last_years', $client->billing_last_years) }}" class="form-control"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row form-group @if($errors->has('units_air_sold_last_years')) has-danger @endif">
                                                                        <div class="col-sm-12">
                                                                            <label>Quantas unidades de ar.cond foram vendidas nos últimos anos? <span class="asterisk">*</span></label>
                                                                            <input type="text" name="units_air_sold_last_years" value="{{ old('units_air_sold_last_years', $client->units_air_sold_last_years) }}" class="form-control" required/>
                                                                            @if($errors->has('units_air_sold_last_years'))
                                                                                <small class="form-control-feedback">{{$errors->first('units_air_sold_last_years')}}</small>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="row form-group">
                                                                        <div class="col-sm-12">
                                                                            <label>Qual faturamento de ar.cond nos últimos anos?</label>
                                                                            <input type="text" name="billing_air_last_years" value="{{ old('billing_air_last_years', $client->billing_air_last_years) }}" class="form-control"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row form-group">
                                                                        <div class="col-sm-12">
                                                                            <label>Qual o volume de compra?</label>
                                                                            <input type="text" name="purchase_volume" value="{{ old('purchase_volume', $client->purchase_volume) }}" class="form-control"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="spacer-10"></div>
                                                                    <div class="row form-group @if($errors->has('works_import')) has-danger @endif">
                                                                        <div class="col-sm-12">
                                                                            <label>A empresa trabalha com IMPORTAÇÃO direta? <span class="text-danger">*</span></label>
                                                                            <fieldset class="controls inline-labels">
                                                                                <div class="custom-control custom-radio">
                                                                                    <input type="radio" value="1" name="works_import" <?php if(old('works_import', $client->works_import) == "1") { echo 'checked'; } ?> data-parsley-group="dados_complementares"  id="is_importacao_sim" class="custom-control-input">
                                                                                    <label class="custom-control-label" for="is_importacao_sim">Sim</label>
                                                                                </div>
                                                                                <div class="custom-control custom-radio">
                                                                                    <input type="radio" value="0" name="works_import" <?php if(old('works_import', $client->works_import) == "0") { echo 'checked'; } ?> data-parsley-group="dados_complementares" required id="is_importacao_nao" class="custom-control-input">
                                                                                    <label class="custom-control-label" for="is_importacao_nao">Não</label>
                                                                                </div>
                                                                            </fieldset>
                                                                            @if($errors->has('works_import'))
                                                                                <small class="form-control-feedback">{{$errors->first('works_import')}}</small>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
													<div id="content-tab-2-h" class="tab-pane">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <fieldset>
                                                                    <div class="row">
                                                                        <div class="col-sm-12 form-group">
                                                                            <label>Intenção de compra</label>
                                                                            <textarea class="form-control" name="buy_intention" rows="8" placeholder="Informe neste campo a intenção de compra"><?= $client->buy_intention ?></textarea>
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
                            <div class="tab-pane p-20" id="content-tab-2" role="tabpanel">
                                <div class="inner-padding">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <fieldset>
                                                <div class="spacer-30"></div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped" id="table_archive" data-rt-breakpoint="600">
                                                        <thead>
                                                        <tr>
                                                            <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Envie o(s) documento(s)</b></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="col" data-rt-column="Nome">Arquivo</th>
                                                            <th scope="col" data-rt-column="Nome">Descrição</th>
                                                            <th scope="col" data-rt-column="Nome">Status</th>
                                                            <th scope="col" data-rt-column="Nome">Tipo</th>
                                                            <th scope="col" data-rt-column="Nome">Ação</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="file" class="form-control" id="contract_social" name="contract_social"/>
                                                                    <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-delete" id="btn_del_contract_social" data_attr="contract_social" type="button" style="display: none;padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_contract_social" data_attr="contract_social" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                </div>
                                                            </td>
                                                            <td class="td-doc" style="vertical-align:middle;">Contrato Social e últimas alterações contratuais</td>
                                                            <td style="vertical-align:middle;">
                                                                @if($arr_contract_social != null && count($arr_contract_social) > 0)
                                                                    <span class="label label-success">Enviado</span>
                                                                @else
                                                                    <span class="label label-danger">Pendente</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <select class="simpleselect form-control doc_req" id="opt_contract_social" name="contract_social_is_exception" disabled style="-webkit-appearance: none;">
                                                                    <option value="0" @if ($arr_documents_client != null && $arr_documents_client->contract_social_is_exception == 0) selected @endif>Obrigatório</option>
                                                                    <option value="1" @if ($arr_documents_client != null && $arr_documents_client->contract_social_is_exception == 1) selected @endif>Exceção</option>
                                                                </select>
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                           @if($arr_contract_social != null && $arr_contract_social->last())
                                                                           href="<?= $arr_contract_social->last()->url ?>" target="_blank"
                                                                           @else
                                                                           href="javascript:void(0)"
                                                                            @endif>Visualizar
                                                                        </a>
                                                                        <a type="button" class="dropdown-item" id="btn_modal_contract_social" style="cursor: pointer;">Versões</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="file" class="form-control" id="card_cnpj" name="card_cnpj"/>
                                                                    <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_card_cnpj" data_attr="card_cnpj" type="button" style="display: none;padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_card_cnpj" data_attr="card_cnpj" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                </div>
                                                            </td>
                                                            <td class="td-doc" style="vertical-align:middle;">Cartão CNPJ (Receita Federal)</td>
                                                            <td style="vertical-align:middle;">
                                                                @if($arr_documents_client != null && $arr_documents_client->card_cnpj != '')
                                                                    <span class="label label-success">Enviado</span>
                                                                @else
                                                                    <span class="label label-danger">Pendente</span>
                                                                @endif
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <select class="simpleselect form-control" id="opt_card_cnpj" name="card_cnpj_is_exception" disabled style="-webkit-appearance: none;">
                                                                    <option value="0" @if ($arr_documents_client != null && $arr_documents_client->card_cnpj_is_exception == 0) selected @endif>Obrigatório</option>
                                                                    <option value="1" @if ($arr_documents_client != null && $arr_documents_client->card_cnpj_is_exception == 1) selected @endif>Exceção</option>
                                                                </select>
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                           @if($arr_documents_client != null && $arr_documents_client->card_cnpj != '')
                                                                           href="<?= $arr_documents_client->card_cnpj ?>" target="_blank"
                                                                           @else
                                                                           href="javascript:void(0)"
                                                                            @endif>Visualizar
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="file" class="form-control" id="card_ie" name="card_ie" style="padding: 4px 5px;"/>
                                                                    <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_card_ie" data_attr="card_ie" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_card_ie" data_attr="card_ie" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                </div>
                                                            </td>
                                                            <td class="td-doc" style="vertical-align:middle;">Cartão de Inscrição Estadual</td>
                                                            <td style="vertical-align:middle;">
                                                                @if($client != null && $arr_documents_client != null && $arr_documents_client->card_ie != '')
                                                                    <span class="label label-success">Enviado</span>
                                                                @else
                                                                    <span class="label label-danger">Pendente</span>
                                                                @endif
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <select class="simpleselect form-control" id="opt_card_ie" name="card_ie_is_exception" disabled style="-webkit-appearance: none;">
                                                                    <option value="0" @if ($arr_documents_client != null && $arr_documents_client->card_ie_is_exception == 0) selected @endif>Obrigatório</option>
                                                                    <option value="1" @if ($arr_documents_client != null && $arr_documents_client->card_ie_is_exception == 1) selected @endif>Exceção</option>
                                                                </select>
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                           @if($arr_documents_client != null && $arr_documents_client->card_ie != '')
                                                                           href="<?= $arr_documents_client->card_ie ?>" target="_blank"
                                                                           @else
                                                                           href="javascript:void(0)"
                                                                            @endif>Visualizar
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="file" class="form-control" id="balance_equity_dre_flow" name="balance_equity_dre_flow" style="padding: 4px 5px;"/>
                                                                    <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-delete" id="btn_del_balance_equity_dre_flow" data_attr="balance_equity_dre_flow" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_balance_equity_dre_flow" data_attr="balance_equity_dre_flow" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                </div>
                                                            </td>
                                                            <td class="td-doc" style="vertical-align:middle;">
                                                                <div data-toggle="tooltip" data-placement="top" title="" data-original-title="Balanço Patrimonial/DRE e Fluxo de Caixa(ano vigente e ano anterior obrigatorios), se tiver de outros anos também pode enviar">Balanço Patrimonial/DRE e Fluxo de Caixa <i class="fa fa-info-circle"></i></div>
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                @if($client != null && $arr_balance_equity != null && count($arr_balance_equity) > 0)
                                                                    <span class="label label-success">Enviado</span>
                                                                @else
                                                                    <span class="label label-danger">Pendente</span>
                                                                @endif
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <select class="simpleselect form-control" id="opt_balance_equity_dre_flow" name="balance_equity_dre_flow_is_exception" disabled style="-webkit-appearance: none;">
                                                                    <option value="0" @if ($arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_is_exception == 0) selected @endif>Obrigatório</option>
                                                                    <option value="1" @if ($arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_is_exception == 1) selected @endif>Exceção</option>
                                                                </select>
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                           @if($arr_balance_equity != null && $arr_balance_equity->last())
                                                                           href="<?= $arr_balance_equity->last()->url ?>" target="_blank"
                                                                           @else
                                                                           href="javascript:void(0)"
                                                                            @endif>Visualizar
                                                                        </a>
                                                                        <a type="button" id="btn_modal_balance_equity" class="dropdown-item" style="cursor: pointer;">Versões</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
														
														
														<tr>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="file" class="form-control" id="balance_equity_dre_flow_2_year" name="balance_equity_dre_flow_2_year" style="padding: 4px 5px;"/>
                                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-delete" id="btn_del_balance_equity_dre_flow_2_year" data_attr="balance_equity_dre_flow_2_year" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_balance_equity_dre_flow_2_year" data_attr="balance_equity_dre_flow_2_year" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="td-doc" style="vertical-align:middle;">
                                                                    <div data-toggle="tooltip" data-placement="top" title="" data-original-title="Balanço Patrimonial/DRE e Fluxo de Caixa do 2º ano anterior">Balanço Patrimonial/DRE e Fluxo de Caixa <b>2º ano anterior</b> <i class="fa fa-info-circle"></i></div>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    @if($client != null && $arr_balance_equity_2_year != null && count($arr_balance_equity_2_year) > 0)
                                                                        <span class="label label-success">Enviado</span>
                                                                    @else
                                                                        <span class="label label-danger">Pendente</span>
                                                                    @endif
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <select class="simpleselect form-control" id="opt_balance_equity_dre_flow_2_year" name="balance_equity_dre_flow_2_year_is_exception" disabled style="-webkit-appearance: none;">
                                                                        <option value="0" @if ($arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_2_year_is_exception == 0) selected @endif>Obrigatório</option>
                                                                        <option value="1" @if ($arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_2_year_is_exception == 1) selected @endif>Exceção</option>
                                                                    </select>
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item"
                                                                                @if($arr_balance_equity_2_year != null && $arr_balance_equity_2_year->last())
                                                                                    href="<?= $arr_balance_equity_2_year->last()->url ?>" target="_blank"
                                                                                @else
                                                                                    href="javascript:void(0)"
                                                                                @endif>Visualizar
                                                                            </a>
                                                                            <a type="button" id="btn_modal_balance_equity_2_year" class="dropdown-item" style="cursor: pointer;">Versões</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="file" class="form-control" id="balance_equity_dre_flow_3_year" name="balance_equity_dre_flow_3_year" style="padding: 4px 5px;"/>
                                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-delete" id="btn_del_balance_equity_dre_flow_3_year" data_attr="balance_equity_dre_flow_3_year" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_balance_equity_dre_flow_3_year" data_attr="balance_equity_dre_flow_3_year" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="td-doc" style="vertical-align:middle;">
                                                                    <div data-toggle="tooltip" data-placement="top" title="" data-original-title="Balanço Patrimonial/DRE e Fluxo de Caixa 3º ano anterior">Balanço Patrimonial/DRE e Fluxo de Caixa <b>3º ano anterior</b> <i class="fa fa-info-circle"></i></div>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    @if($client != null && $arr_balance_equity_3_year != null && count($arr_balance_equity_3_year) > 0)
                                                                        <span class="label label-success">Enviado</span>
                                                                    @else
                                                                        <span class="label label-danger">Pendente</span>
                                                                    @endif
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <select class="simpleselect form-control" id="opt_balance_equity_dre_flow_3_year" name="balance_equity_dre_flow_3_year_is_exception" disabled style="-webkit-appearance: none;">
                                                                        <option value="0" @if ($arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_3_year_is_exception == 0) selected @endif>Obrigatório</option>
                                                                        <option value="1" @if ($arr_documents_client != null && $arr_documents_client->balance_equity_dre_flow_3_year_is_exception == 1) selected @endif>Exceção</option>
                                                                    </select>
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item"
                                                                                @if($arr_balance_equity_3_year != null && $arr_balance_equity_3_year->last())
                                                                                    href="<?= $arr_balance_equity_3_year->last()->url ?>" target="_blank"
                                                                                @else
                                                                                    href="javascript:void(0)"
                                                                                @endif>Visualizar
                                                                            </a>
                                                                            <a type="button" id="btn_modal_balance_equity_3_year" class="dropdown-item" style="cursor: pointer;">Versões</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
														
															
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="file" class="form-control" id="proxy_representation_legal" name="proxy_representation_legal" style="padding: 4px 5px;"/>
                                                                    <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_proxy_representation_legal" data_attr="proxy_representation_legal" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_proxy_representation_legal" data_attr="proxy_representation_legal" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                </div>
                                                            </td>
                                                            <td class="td-doc">
                                                                <div data-toggle="tooltip" data-placement="top" title="" data-original-title="Procuração doa representantes legais e cópia dos documentos pessoais (caso a empresa seja administrada por pessoas fora do contrato social)">Procuração dos representantes legais e cópia dos documentos pessoais <i class="fa fa-info-circle"></i></div>
                                                            <td>
                                                                @if($arr_documents_client != null && $arr_documents_client->proxy_representation_legal != '')
                                                                    <span class="label label-success">Enviado</span>
                                                                @else
                                                                    <span class="label label-danger">Pendente</span>
                                                                @endif
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <select class="simpleselect form-control" id="opt_proxy_representation_legal" name="proxy_representation_legal_is_exception" disabled style="-webkit-appearance: none;">
                                                                    <option value="0" @if ($arr_documents_client != null && $arr_documents_client->proxy_representation_legal_is_exception == 0) selected @endif>Obrigatório</option>
                                                                    <option value="1" @if ($arr_documents_client != null && $arr_documents_client->proxy_representation_legal_is_exception == 1) selected @endif>Exceção</option>
                                                                </select>
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                           @if($arr_documents_client != null && $arr_documents_client->proxy_representation_legal != '')
                                                                           href="<?= $arr_documents_client->proxy_representation_legal ?>" target="_blank"
                                                                           @else
                                                                           href="javascript:void(0)"
                                                                            @endif>Visualizar
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="file" class="form-control" id="declaration_regime" name="declaration_regime" style="padding: 4px 5px;"/>
                                                                    <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_declaration_regime" data_attr="declaration_regime" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_declaration_regime" data_attr="declaration_regime" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                </div>
                                                            </td>
                                                            <td class="td-doc">
                                                                <div data-toggle="tooltip" data-placement="top" title="" data-original-title="Declaração de regime de tributação (LUCRO REAL-ANEXO I, LUCRO PRESUMIDO-ANEXO II E SIMPLES NACIONAL ANEXO III)">Declaração de regime de tributação <i class="fa fa-info-circle"></i></div>
                                                            </td>
                                                            <td>
                                                                @if($arr_documents_client != null && $arr_documents_client->declaration_regime != '')
                                                                    <span class="label label-success">Enviado</span>
                                                                @else
                                                                    <span class="label label-danger">Pendente</span>
                                                                @endif
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <select class="simpleselect form-control" id="opt_declaration_regime" name="declaration_regime_is_exception" disabled style="-webkit-appearance: none;">
                                                                    <option value="0" @if ($arr_documents_client != null && $arr_documents_client->declaration_regime_is_exception == 0) selected @endif>Obrigatório</option>
                                                                    <option value="1" @if ($arr_documents_client != null && $arr_documents_client->declaration_regime_is_exception == 1) selected @endif>Exceção</option>
                                                                </select>
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                           @if($arr_documents_client != null && $arr_documents_client->declaration_regime != '')
                                                                           href="<?= $arr_documents_client->declaration_regime ?>" target="_blank"
                                                                           @else
                                                                           href="javascript:void(0)"
                                                                            @endif>Visualizar
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="file" class="form-control" id="apresentation_commercial" name="apresentation_commercial" style="padding: 4px 5px;"/>
                                                                    <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_apresentation_commercial" data_attr="apresentation_commercial" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_apresentation_commercial" data_attr="apresentation_commercial" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                </div>
                                                            </td>
                                                            <td class="td-doc">
                                                                <div data-toggle="tooltip" data-placement="top" title="" data-original-title="Apresentação comercial ou portfólio próprio da empresa, Contendo imagens fotográficas da loja matriz, lista de pontos de vendas com nome fantasia, endereço, contato e gerente/reponsável da loja. É importante conter todas as informações comerciaos e história do cliente. Tipos de arquivos: PDF | PPT | WORD | IMAGENS">Apres. comercial ou portfólio próprio da empresa <i class="fa fa-info-circle"></i></div>
                                                            </td>
                                                            <td>
                                                                @if($arr_documents_client != null && $arr_documents_client->apresentation_commercial != '')
                                                                    <span class="label label-success">Enviado</span>
                                                                @else
                                                                    <span class="label label-danger">Pendente</span>
                                                                @endif
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <select class="simpleselect form-control" id="opt_apresentation_commercial" name="apresentation_commercial_is_exception" disabled style="-webkit-appearance: none;">
                                                                    <option value="0" @if ($arr_documents_client != null && $arr_documents_client->apresentation_commercial_is_exception == 0) selected @endif>Obrigatório</option>
                                                                    <option value="1" @if ($arr_documents_client != null && $arr_documents_client->apresentation_commercial_is_exception == 1) selected @endif>Exceção</option>
                                                                </select>
                                                            </td>
                                                            <td class="th-td-archive">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                           @if($arr_documents_client != null && $arr_documents_client->apresentation_commercial != '')
                                                                           href="<?= $arr_documents_client->apresentation_commercial ?>" target="_blank"
                                                                           @else
                                                                           href="javascript:void(0)"
                                                                            @endif>Visualizar
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
														
														<tr>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="file" class="form-control" id="certificate_debt_negative_federal" name="certificate_debt_negative_federal" style="padding: 4px 5px;"/>
                                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_certificate_debt_negative_federal" data_attr="certificate_debt_negative_federal" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_certificate_debt_negative_federal" data_attr="certificate_debt_negative_federal" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="td-doc">
                                                                    <div data-toggle="tooltip" data-placement="top" title="" data-original-title="Certidão negativa de debitos - Federal">Certidão negativa de debitos - Federal <i class="fa fa-info-circle"></i></div>
                                                                </td>
                                                                <td>
                                                                    @if($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_federal != '')
                                                                        <span class="label label-success">Enviado</span>
                                                                    @else
                                                                        <span class="label label-danger">Pendente</span>
                                                                    @endif
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <select class="simpleselect form-control" id="opt_certificate_debt_negative_federal" name="certificate_debt_negative_federal_is_exception" disabled style="-webkit-appearance: none;">
                                                                        <option value="0" @if ($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_federal_is_exception == 0) selected @endif>Obrigatório</option>
                                                                        <option value="1" @if ($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_federal_is_exception == 1) selected @endif>Exceção</option>
                                                                    </select>
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item"
                                                                                @if($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_federal != '')
                                                                                    href="<?= $arr_documents_client->certificate_debt_negative_federal ?>" target="_blank"
                                                                                @else
                                                                                    href="javascript:void(0)"
                                                                                @endif>Visualizar
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="file" class="form-control" id="certificate_debt_negative_sefaz" name="certificate_debt_negative_sefaz" style="padding: 4px 5px;"/>
                                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_certificate_debt_negative_sefaz" data_attr="certificate_debt_negative_sefaz" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_certificate_debt_negative_sefaz" data_attr="certificate_debt_negative_sefaz" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="td-doc">
                                                                    <div data-toggle="tooltip" data-placement="top" title="" data-original-title="Certidão negativa de debitos - Sefaz">Certidão negativa de debitos - Sefaz <i class="fa fa-info-circle"></i></div>
                                                                </td>
                                                                <td>
                                                                    @if($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_sefaz != '')
                                                                        <span class="label label-success">Enviado</span>
                                                                    @else
                                                                        <span class="label label-danger">Pendente</span>
                                                                    @endif
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <select class="simpleselect form-control" id="opt_certificate_debt_negative_sefaz" name="certificate_debt_negative_sefaz_is_exception" disabled style="-webkit-appearance: none;">
                                                                        <option value="0" @if ($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_sefaz_is_exception == 0) selected @endif>Obrigatório</option>
                                                                        <option value="1" @if ($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_sefaz_is_exception == 1) selected @endif>Exceção</option>
                                                                    </select>
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item"
                                                                                @if($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_sefaz != '')
                                                                                    href="<?= $arr_documents_client->certificate_debt_negative_sefaz ?>" target="_blank"
                                                                                @else
                                                                                    href="javascript:void(0)"
                                                                                @endif>Visualizar
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="file" class="form-control" id="certificate_debt_negative_labor" name="certificate_debt_negative_labor" style="padding: 4px 5px;"/>
                                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-default btn-upload-delete" id="btn_del_certificate_debt_negative_labor" data_attr="certificate_debt_negative_labor" type="button" style="display: none; padding: 8px; border-radius: 0px;">x</button>
                                                                            <button class="btn btn-default btn-upload" id="btn_certificate_debt_negative_labor" data_attr="certificate_debt_negative_labor" type="button" style="padding: 9px;">Enviar</button>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="td-doc">
                                                                    <div data-toggle="tooltip" data-placement="top" title="" data-original-title="Certidão negativa de debitos - Trabalhistas">Certidão negativa de debitos - Trabalhistas <i class="fa fa-info-circle"></i></div>
                                                                </td>
                                                                <td>
                                                                    @if($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_labor != '')
                                                                        <span class="label label-success">Enviado</span>
                                                                    @else
                                                                        <span class="label label-danger">Pendente</span>
                                                                    @endif
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <select class="simpleselect form-control" id="opt_certificate_debt_negative_labor" name="certificate_debt_negative_labor_is_exception" disabled style="-webkit-appearance: none;">
                                                                        <option value="0" @if ($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_labor_is_exception == 0) selected @endif>Obrigatório</option>
                                                                        <option value="1" @if ($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_labor_is_exception == 1) selected @endif>Exceção</option>
                                                                    </select>
                                                                </td>
                                                                <td class="th-td-archive">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item"
                                                                                @if($arr_documents_client != null && $arr_documents_client->certificate_debt_negative_labor != '')
                                                                                    href="<?= $arr_documents_client->certificate_debt_negative_labor ?>" target="_blank"
                                                                                @else
                                                                                    href="javascript:void(0)"
                                                                                @endif>Visualizar
                                                                            </a>
                                                                        </div>
                                                                    </div>
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
                            <div id="content-tab-3" class="tab-pane">
                                <div class="p-20">
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label>Escolha a versão que gostaria de consultar</label>
                                            <select id="version" class="form-control" style="width:100%">
                                                <option value=""></option>
                                                @foreach ($client->client_version()->withTrashed()->get() as $key)
                                                    <option value="{{$key->version}}">Versão: {{$key->version}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <!--<h5 class="card-title" id="version-name"></h5>-->
                                            <iframe id="version-src" style="width: 100%; height: 1600px; border: 0px;" src=""></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="content-tab-4" class="tab-pane">
                                <div class="inner-padding p-20">
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label>Escolha a versão para ver o histórico</label>
                                            <select id="version_hist" class="form-control" style="width:100%">
                                                <option value=""></option>
                                                @foreach ($client->client_version()->withTrashed()->get() as $key)
                                                    <option value="{{$key->version}}">Versão: {{$key->version}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-wrapper">
                                                <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                                    <thead>
                                                    <tr>
                                                        <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>ANÁLISES</b></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" data-rt-column="Tipo do usuário">Tipo de usuário</th>
                                                        <th scope="col" data-rt-column="Nome">Nome</th>
                                                        <th scope="col" data-rt-column="Cargo">Cargo</th>
                                                        <th scope="col" data-rt-column="Status">Status</th>
                                                        <th scope="col" data-rt-column="Observação">Observação</th>
                                                        <th scope="col" data-rt-column="Status">Versão</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="analyzes"></tbody>
                                                </table>
                                            </div>
                                            <div class="spacer-50"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_confirm" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar envio</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <strong><p>Deseja enviar para análise de aprovação?</p></strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-danger pull-right" onclick="save('/comercial/operacao/cliente/edit_analise')">Enviar para análise</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_contract_social" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Versões contrato social</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="table-modal-contract" data-rt-breakpoint="600">
                                    <thead>
                                    <tr>
                                        <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Contrato Social</b></td>
                                    </tr>
                                    <tr style="text-align:center;">
                                        <th scope="col" data-rt-column="Arquivo">Arquivo</th>
                                        <th scope="col" data-rt-column="Versão">Versão</th>
                                        <th scope="col" data-rt-column="Atualização">Atualização</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($arr_contract_social != null && count($arr_contract_social) > 0)
                                        @foreach ($arr_contract_social as $key)
                                            <tr style="text-align:center;">
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_balance_equity" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Versões do Balanço Patrimonial</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="table-modal-contract" data-rt-breakpoint="600">
                                    <thead>
                                    <tr>
                                        <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Versões do Balanço Patrimonial / DRE e Fluxo de Caixa</b></td>
                                    </tr>
                                    <tr style="text-align:center;">
                                        <th scope="col" data-rt-column="Arquivo">Arquivo</th>
                                        <th scope="col" data-rt-column="Versão">Versão</th>
                                        <th scope="col" data-rt-column="Atualização">Atualização</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($arr_balance_equity != null && count($arr_balance_equity) > 0)
                                        @foreach ($arr_balance_equity as $key)
                                            <tr style="text-align:center;">
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

	<div id="modal_balance_equity_2_year" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Versões do Balanço Patrimonial <b>2º ano anterior</b></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="table-modal-contract" data-rt-breakpoint="600">
                                    <thead>
                                        <tr>
                                            <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Versões do Balanço Patrimonial / DRE e Fluxo de Caixa</b></td>
                                        </tr>
                                        <tr style="text-align:center;">
                                            <th scope="col" data-rt-column="Arquivo">Arquivo</th>
                                            <th scope="col" data-rt-column="Versão">Versão</th>
                                            <th scope="col" data-rt-column="Atualização">Atualização</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($arr_balance_equity_2_year != null && count($arr_balance_equity_2_year) > 0)
                                            @foreach ($arr_balance_equity_2_year as $key)
                                            <tr style="text-align:center;">
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>


    <div id="modal_balance_equity_3_year" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Versões do Balanço Patrimonial <b>3º ano anterior</b></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="table-modal-contract" data-rt-breakpoint="600">
                                    <thead>
                                        <tr>
                                            <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Versões do Balanço Patrimonial / DRE e Fluxo de Caixa</b></td>
                                        </tr>
                                        <tr style="text-align:center;">
                                            <th scope="col" data-rt-column="Arquivo">Arquivo</th>
                                            <th scope="col" data-rt-column="Versão">Versão</th>
                                            <th scope="col" data-rt-column="Atualização">Atualização</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($arr_balance_equity_3_year != null && count($arr_balance_equity_3_year) > 0)
                                            @foreach ($arr_balance_equity_3_year as $key)
                                            <tr style="text-align:center;">
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-scripts')

    <script src="/js/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/elite/dist/js/custom.min.js"></script>
    <script src="/elite/assets/node_modules/sweetalert/sweetalert.min.js"></script>

    <script type="text/javascript">

        var arr_product_sale = {!! json_encode($arr_product_sale) !!},
            arr_account_client  = {!! json_encode($arr_account_client) !!},
            arr_supplier_client = {!! json_encode($arr_supplier_client) !!},
            arr_main_client = {!! json_encode($arr_main_client) !!},
            arr_owner_partner = {!! json_encode($arr_owner_partner) !!},
            arr_contact_client = {!! json_encode($arr_contact_client) !!},
            arr_documents = {!! json_encode($arr_documents) !!},
            arr_subsidiary_client = [];

        var client_id = {!! $id !!};

        var arr_doc = JSON.parse(arr_documents);
        if(arr_doc.length == 0) {
            arr_documents = [{}];
            localStorage.setItem("arr_documents", JSON.stringify(arr_documents));
        } else {
            localStorage.setItem("arr_documents", arr_documents);
        }

        $(document).ready(function () {

            $('#version').change(function () {
                if($('#version').val() != '') {
                    $('#version-name').html('<div class="label label-info">Versão '+$('#version').val()+'</div>');
                    $('#version-src').attr('src', '/comercial/operacao/cliente/print/versions/view/{{$id}}/'+$('#version').val());
                }
            });

            $('#version_hist').change(function () {
                if($('#version_hist').val() == '') {
                    $('#analyzes').html('');
                } else {
                    block();
                    ajaxSend('/comercial/operacao/cliente/analise/historico/aprov',{id: {{$id}}, version_hist: $('#version_hist').val()})
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

            $("#btn_modal_balance_equity").click(function () {
                $("#modal_balance_equity").modal('show');
            });
			
			$("#btn_modal_balance_equity_2_year").click(function () {
                $("#modal_balance_equity_2_year").modal('show');
            });

            $("#btn_modal_balance_equity_3_year").click(function () {
                $("#modal_balance_equity_3_year").modal('show');
            });

            $("#btn_modal_contract_social").click(function () {
                $("#modal_contract_social").modal('show');
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

                    if(value1 != "" && value2 != "" && value3 != "") {
                        arr_account_client.push(object);
                        $("#account_bank, #account_agency, #account_current").val('');
                    }
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

                    if(value1 != "" && value2 != "" && value3 != "") {
                        arr_supplier_client.push(object);
                        $("#supplier_name, #supplier_contact, #supplier_phone").val('');
                    }
                    $(".not-registered-supplier").hide();

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

                    if(value1 != "" && value2 != "" && value3 != "") {
                        arr_main_client.push(object);
                        $("#client_name, #client_contact, #client_phone").val('');
                    }
                    $(".not-registered-clients").hide();

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
                        "<td><a onclick='deleteRelTableClient(this)' data-id='"+ index +"' data-rel='"+name_rel+"' class='btn-less' style='cursor:pointer;'><i class='fa fa-trash-o'></i></a></td>"+
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

                    var index = arr_product_sale.findIndex(x => x.product_sales_id == $(this).val());
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
                ajaxSend('/comercial/operacao/cliente/documento/ajax', $("#registration").serialize(), 'POST', '60000', $("#registration")).then(function(result){

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

                    if(name != 'contract_social' || name != 'balance_equity_dre_flow') {

                        ajaxSend('/comercial/operacao/cliente/documento/delete/ajax', {url: obj[0][''+ name +''], name: name, client_id: client_id}, 'POST', '60000', '').then(function(result){

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

            var documents = localStorage.getItem("arr_documents");
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

                $(this).val('');
            });

            $("#btn_modal_analyze").click(function() {
                $("#modal_confirm").modal('show');
            });

            $('#identity').mask('00.000.000/0000-00', {reverse: false});

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
            $('.money').mask('000.000.000.000.000,00', {reverse: true});

            var behavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                options = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(behavior.apply({}, arguments), options);
                    }
                };
            $('.phone').mask(behavior, options);
        });

        function save(url) {

            $("#arr_product_sale").val(JSON.stringify(arr_product_sale));
            $("#arr_contact_client").val(JSON.stringify(arr_contact_client));
            $("#arr_account_client").val(JSON.stringify(arr_account_client));
            $("#arr_supplier_client").val(JSON.stringify(arr_supplier_client));
            $("#arr_main_client").val(JSON.stringify(arr_main_client));
            $("#arr_subsidiary_client").val(JSON.stringify(arr_subsidiary_client));

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
                            return $error('O balanço patrimonial/DRE/Fluxo de caixa 2º ano anterior é obrigatório no cadastro!');
                        }
                    }

                    if($("#opt_balance_equity_dre_flow_3_year").val() == 0) {
                        if(obj[0]["balance_equity_dre_flow_3_year"] === undefined) {
                            $('#modal_confirm').modal('hide');
                            return $error('O balanço patrimonial/DRE/Fluxo de caixa 3º ano anterior é obrigatório no cadastro!');
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
                            return $error('Certidão negativa de debitos - Trabalhistas é obrigatório no cadastro!');
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
            return path;
        }

        function deleteRelTableClient(el) {

            var index = $(el).attr('data-id'),
                name_arr = $(el).attr('data-rel'),
                arr_rel = window[name_arr];

            arr_rel.splice(index, 1);
            $(el).parent().parent().remove();
        }

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
                html += '<td><span class="label label-info">'+column.version+'</span></td>';
                html += '</tr>';

            }
            return html;
        }
        function realodAnalyzes(object) {

            var html = '';
            html += genHTML(object.imdt);
			html += genHTML(object.revision);
			html += genHTML(object.judicial);
            html += genHTML(object.commercial);
            //html += genHTML(object.financy);

            $('#analyzes').html(html);
        }
    </script>

@endsection
