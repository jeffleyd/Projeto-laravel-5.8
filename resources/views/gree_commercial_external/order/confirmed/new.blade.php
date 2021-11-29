@extends('gree_commercial_external.layout')

@section('page-css')
    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="/elite/assets/node_modules/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <link href="/elite/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <style>
        .isblock {
            opacity: 0.4;
        }
		.my-dialog .modal-header {
            display: block !important;
        }
    </style>
@endsection
@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Novo pedido</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <a class="btn btn-warning d-none d-lg-block m-l-15" onclick="selectProgramation()" href="#">
                    <i class="fa fa-pencil"></i> Alterar informações
                </a>
                <a class="btn btn-info d-none d-lg-block m-l-15" onclick="saveOrder()" href="#">
                    <i class="fa fa-check"></i> Salvar pedido
                </a>
            </div>
        </div>
    </div>
    <style>
        .qtd-td {
            border: none;
            width: 40px;
            text-align: center;
            border: 1px solid #d2d2d2;
        }
        ul.list-icons li {
            list-style: none;
            line-height: 30px;
            margin: 5px 0;
            transition: 0.2s ease-in;
        }
        ul.list-icons {
            margin: 0px;
            padding: 0px;
        }
    </style>
@endsection


@section('content')
<form action="/comercial/operacao/order/confirmed/save" id="sendOrder" method="post">
    <input type="hidden" name="client_id" id="client_id" @if (count($arr_month)>0) value="{{$client->id}}" @else value="0" @endif>
    <input type="hidden" name="monthyear" id="monthyear" @if (count($months)>0) value="{{date('Y-m-01', strtotime($months[0]->date))}}" @else value="0" @endif>
    <input type="hidden" name="table_id" id="table_id" @if (count($arr_month)>0) value="{{$table->id}}" @else value="0" @endif>
    <input type="hidden" name="json_order" id="json_order" value="[]">
@if (count($arr_month)>0)
<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <b>Dica:</b> Clique sobre o valor do conjunto para poder informar um valor personalizado.
        </div>
    </div>
    <div class="col-12 col-sm-3" style="line-height: 2.2;">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informações do pedido</h5>
                <div class="row">
                    <div class="col-12">
                        @php $dcarbon = new \Carbon\Carbon($month); @endphp
                        <b>Mês:</b> {{$dcarbon->locale('pt_BR')->isoFormat('MMMM')}} {{$dcarbon->locale('pt_BR')->isoFormat('YYYY')}}<br>
                        <b>Cliente:</b> {{$client->company_name}} @if($client->code) ({{$client->code}}) @endif<br>
                        <b>CNPJ/CPF:</b> {{$client->identity}}<br>
                        <hr style="margin-bottom:20px;">
                        <b>Condição comercial:</b> {{$table->code}}<br>
                        @php
							$obj_table = commercialTablePriceConvertValue($table);
						@endphp
                        <b>Tipo do cliente:</b> {{$obj_table->type_client}}<br>
                        <b>Regime de tributação:</b> {{$obj_table->pis_confis}}<br>
                        <b>Contrato/VPC:</b> {{$obj_table->contract_vpc}}<br>
                        <b>Tipo de entrega:</b> {{$obj_table->cif_fob}}<br>
                        <b>Prazo médio de pagamento:</b> {{$obj_table->average_term}}<br>
                    </div>
                </div>
            </div>
        </div>
		<div style="position: fixed;left:0;bottom:0;background: white;padding: 15px;font-weight: 400;margin: 25px;z-index: 10;box-shadow: 0px 0px 3px -1px;line-height: 1.6;text-align: center;"><b>Cubagem:</b> <span class="cub_total" style="font-size: 18px;">0.00</span>
    <div style="font-size: 10px;">
    Esse é o calculo total.
    </div>
</div>
    </div>
    <div class="col-12 col-sm-9">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#client" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Dados do cliente</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#delivery" role="tab"><span class="hidden-sm-up"><i class="ti-shopping-cart"></i></span> <span class="hidden-xs-down">Endereço pra entrega</span></a> </li>
                    @if($table->cif_fob == 0)
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#receiver" role="tab"><span class="hidden-sm-up"><i class="ti-truck"></i></span> <span class="hidden-xs-down">Receb. de mercadoria</span></a> </li>
                    @endif
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#products" role="tab"><span class="hidden-sm-up"><i class="ti-package"></i></span> <span class="hidden-xs-down">Produtos</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#observation" role="tab"><span class="hidden-sm-up"><i class="ti-help"></i></span> <span class="hidden-xs-down">Observação</span></a> </li>
                </ul>
                <div class="tab-content tabcontent-border" style="border: 1px solid #ddd;border-top: 0px;">
                    <div class="tab-pane active" id="client" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12 p-4">
                                <div class="row">
                                    <div class="col-sm-6 form-group ">
                                        <label class="type_order">Tipo de pedido</label>
                                        <select name="type_order" id="type_order" class="form-control">
                                            <option value="1">(AM) Vendas correntes</option>
                                            <option value="4">Outros</option>
                                        </select>
                                    </div>
									<div class="col-sm-6 form-group ">
                                        <label class="state_invoice">Estado para faturamento</label>
                                        <select name="state_invoice" id="state_invoice" class="form-control">
                                            <option value="">---</option>
                                            <option value="AC">Acre</option>
											<option value="AL">Alagoas</option>
											<option value="AP">Amapá</option>
											<option value="AM">Amazonas</option>
											<option value="BA">Bahia</option>
											<option value="CE">Ceará</option>
											<option value="DF">Distrito Federal</option>
											<option value="ES">Espírito Santo</option>
											<option value="GO">Goiás</option>
											<option value="MA">Maranhão</option>
											<option value="MT">Mato Grosso</option>
											<option value="MS">Mato Grosso do Sul</option>
											<option value="MG">Minas Gerais</option>
											<option value="PA">Pará</option>
											<option value="PB">Paraíba</option>
											<option value="PR">Paraná</option>
											<option value="PE">Pernambuco</option>
											<option value="PI">Piauí</option>
											<option value="RJ">Rio de Janeiro</option>
											<option value="RN">Rio Grande do Norte</option>
											<option value="RS">Rio Grande do Sul</option>
											<option value="RO">Rondônia</option>
											<option value="RR">Roraima</option>
											<option value="SC">Santa Catarina</option>
											<option value="SP">São Paulo</option>
											<option value="SE">Sergipe</option>
											<option value="TO">Tocantins</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group ">
                                        <label>Tipo de pagamento <span class="text-danger">*</span></label>
                                        <fieldset class="controls inline-labels" style="border: 1px solid #bbb; padding: 2.6px;">
                                            <div class="custom-control" style="top:5px; padding-left:1rem;">
                                                <label><input type="radio" name="type_payment" value="1" checked><span></span> DDF</label>
                                                <label style="margin-left:10px;"><input type="radio" name="type_payment" value="2"><span></span> DDE</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-6 form-group ">
                                        <label>Forma de pagamento <span class="text-danger">*</span></label>
                                        <fieldset class="controls inline-labels" style="border: 1px solid #bbb; padding: 2.6px;">
                                            <div class="custom-control" style="top:5px; padding-left:1rem;">
                                                <label><input type="radio" name="form_payment" value="1" checked><span></span> BOL</label>
                                                <label style="margin-left:10px;"><input type="radio" name="form_payment" value="2"><span></span> DEP</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 form-group ">
                                        <label class="identity_label">Nome do transportador</label>
                                        <input type="text" name="transport_name" id="transport_name" class="form-control" value="" placeholder="Digite o nome do transportador">
                                    </div>
									<div class="col-sm-3 form-group ">
                                        <label>Condição de pagamento <span class="text-danger">*</span></label>
                                        <input type="text" name="date_payment" id="date_payment" class="form-control" value="" placeholder="Ex: 10/10/10 para 3x">
                                    </div>
                                    <div class="col-sm-3 form-group ">
                                        <label>Comissão <span class="text-danger">*</span></label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <input type="text" class="form-control" name="commission" id="commission" placeholder="1.50" value="{{number_format($client->commission, 2, '.', '')}}">
                                            <span class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">%</span></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Data de faturamento <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control date" name="date_invoice" id="date_invoice" value="" placeholder="00/00/0000">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>Ordem de compra do cliente</label>
                                        <input type="text" class="form-control" name="control_client" id="control_client" value="">
                                    </div>
									<div class="col-sm-6 form-group">
                                        <label>VPC para visualização do cliente</label>
                                        <input type="text" class="form-control" name="vpc_view" id="vpc_view">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane  p-20" id="delivery" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Escolha apenas se for outro endereço de entrega</label>
                            </div>
                            <div class="col-sm-3 form-group ">
                                <select id="sel_type" class="form-control">
                                    <option value="">Livre</option>
                                    <option value="1">Física (CPF)</option>
                                    <option value="2">Jurídica (CNPJ)</option>
                                </select>
                            </div>
                            <div class="col-sm-9 form-group ">
                                <select name="other_client" id="other_client" style="width:100%" class="form-control js-select23" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="list-icons">
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i> <b>Endereço:</b> <span id="c_address" data-address="{{$client->address}}">{{$client->address}}</span></a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i> <b>Estado:</b> <span id="c_state" data-state="{{$client->state}}">{{$client->state}}</span></a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i> <b>Cidade:</b> <span id="c_city" data-city="{{$client->city}}">{{$client->city}}</span></a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i> <b>Bairro:</b> <span id="c_district" data-district="{{$client->district}}">{{$client->district}}</span></a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i> <b>CEP:</b> <span id="c_zipcode" data-zipcode="{{$client->zipcode}}">{{$client->zipcode}}</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @if($table->cif_fob == 0)
                    <div class="tab-pane p-20" id="receiver" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12 p-4">
                                <div class="row">
                                    <div class="col-sm-6 form-group ">
                                        <label>Recebimento <span class="text-danger">*</span></label>
                                        <fieldset class="controls inline-labels" style="border: 1px solid #bbb; padding: 2.6px;">
                                            <div class="custom-control" style="top:5px; padding-left:1rem;">
                                                <label><input type="radio" name="receiver" value="1" checked required=""><span></span> ORDEM DE CHEGADA</label>
                                                <label style="margin-left:10px;"><input type="radio" name="receiver" value="2" required=""><span></span> AGENDADO</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-6 form-group ">
                                        <label>Dias de recebimento <span class="text-danger">*</span></label>
                                        <fieldset class="controls inline-labels" style="border: 1px solid #bbb; padding: 2.6px;">
                                            <div class="custom-control" style="top:5px; padding-left:1rem;">
                                                <label><input type="radio" name="day_receiver" value="1" checked required=""><span></span> SEG À SEX</label>
                                                <label style="margin-left:10px;"><input type="radio" name="day_receiver" value="2" required=""><span></span> SEG À SÁB</label>
                                                <label style="margin-left:10px;"><input type="radio" name="day_receiver" value="3" required=""><span></span> 24 HORAS</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row hour_times_1">
                                    <div class="col-sm-6 form-group ">
                                        <label>Hora inicial Seg - Sex.<span class="text-danger">*</span></label>
                                        <input type="text" name="hour_start_mon_fri" id="hour_start_mon_fri" class="form-control hour" value="" placeholder="00:00">
                                    </div>
                                    <div class="col-sm-6 form-group ">
                                        <label>Hora final Seg - Sex <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control hour" name="hour_end_mon_fri" id="hour_end_mon_fri" value="" placeholder="00:00">
                                    </div>
                                </div>
                                <div class="row hour_times_2" style="display:none">
                                    <div class="col-sm-6 form-group ">
                                        <label>Hora inicial Sábado <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control hour" name="hour_start_sat" id="hour_start_sat" value="" placeholder="00:00">
                                    </div>
                                    <div class="col-sm-6 form-group ">
                                        <label>Hora final Sábado <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control hour" name="hour_end_sat" id="hour_end_sat" value="" placeholder="00:00">
                                    </div>
                                </div>
                                <fieldset class="controls inline-labels p-4 appointment" style="border: 1px solid #bbb; display:none;">
                                    <h6><b>Agendar com</b></h6>
                                    <div class="row">
                                        <div class="col-sm-4 form-group ">
                                            <label>Pessoa <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="apm_name" id="apm_name" value="" placeholder="Nome">
                                        </div>
                                        <div class="col-sm-4 form-group ">
                                            <label>Telefone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="apm_phone" id="apm_phone" value="" placeholder="00 0 0000-0000">
                                        </div>
                                        <div class="col-sm-4 form-group ">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="apm_email" id="apm_email" value="" placeholder="pessoa@email.com.br">
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="row mt-4">
                                    <div class="col-sm-8 form-group ">
                                        <label>Descarga <span class="text-danger">*</span></label>
                                        <fieldset class="controls inline-labels" style="border: 1px solid #bbb; padding: 2.6px;">
                                            <div class="custom-control" style="top:5px; padding-left:1rem;">
                                                <label><input type="radio" name="discharge" value="1" checked><span></span> TRANSPORTE DA GREE</label>
                                                <label style="margin-left:10px;"><input type="radio" name="discharge" value="2"><span></span> EQUIPE DO CLIENTE</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-4 form-group discharge_total" style="display:none">
                                        <label>Custo por carga <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="price_charge" id="price_charge" value="" placeholder="0.000,00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="tab-pane p-20" id="products" role="tabpanel">
						<div class="row">
							<div class="col-sm-12 form-group has_apply_discount_elem">
								<label class="has_apply_discount"></label>
								<select name="has_apply_discount" id="has_apply_discount" class="form-control">
									<option value="">Escolha uma opção</option>
									<option value="1">SIM</option>
									<option value="2">NÃO</option>
								</select>
							</div>
						</div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody><tr class="table-active">
                                    <td colspan="2" rowspan="2" style="background: white;border: none; text-align: center">
                                    </td>
                                </tr>
                                <tr class="table-active">
                                    <td style="text-align: center">Qty</td>
                                    <td style="text-align: center">Preço</td>
                                    <td style="text-align: center">Valor total</td>
                                </tr>
                                @foreach($all_cat_prod as $key)

                                    @foreach($arr_month as $am)
                                        @if ($am['id'] == $key->id)
                                            <tr class="table-primary">
                                                <td colspan="6" style="text-align: center">{{$key->name}}</td>
                                            </tr>
                                            @foreach($key->setProductOnGroup as $prod)
                                                @foreach($am['products'] as $prd)
                                                    @if ($prd['id'] == $prod->id)
														@if ($prod->is_visible)
                                                        <tr>
                                                            <td style="text-align: center">{{$prod->resume}} @if ($prod->capacity == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
                                                            <td style="text-align: center"> @if ($prod->productAirEvap) @if (substr($prod->productAirEvap->model, -2) == '/I' or substr($prod->productAirEvap->model, -2) == '/O') {{substr($prod->productAirEvap->model, 0, -2)}} @else {{$prod->productAirEvap->model}} @endif @endif</td>
                                                            <td style="text-align: center" id="cat-{{$key->id}}-product-{{$prod->id}}-qtd"><input data-cat="{{$key->id}}" data-product="{{$prod->id}}" onkeyup="insertValue(this, 1)" class="qtd-td" type="text" value="0" maxlength="4"></td>
                                                            <td style="text-align: center; cursor: pointer;" id="cat-{{$key->id}}-product-{{$prod->id}}-price" data-cat="{{$key->id}}" data-product="{{$prod->id}}" data-cub="{{$prd['calc_cubage']}}" data-amount="{{number_format($prd['price'], 2, '.', '')}}" onclick="insertCustomPrice(this)">R$ {{number_format($prd['price'], 2, ',', '.')}}</td>
                                                            <td style="text-align: center" id="cat-{{$key->id}}-product-{{$prod->id}}-total">R$ 0,00</td>
                                                        </tr>
														@endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td colspan="6" style="text-align: right"><b>TOTAL DO PEDIDO:</b> <span id="total">R$&nbsp;0,00</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane  p-20" id="observation" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12 form-group ">
                                <label>Informe sua observação</label>
                                <textarea name="observation" id="observation" class="form-control" rows="5" placeholder="Caso tenha alguma coisa a dizer, digite aqui..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
</form>
<div id="editprice" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="vcenter">Valor da unidade</h4>
            </div>
            <div class="modal-body">
                <input type="text" placeholder="0.00" id="customprice" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary waves-effect">Fechar</button>
                <button type="button" onclick="updateValue()" class="btn btn-success waves-effect">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal-programation" class="modal" tabindex="1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 920px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body wizard-content">
                                <h4 class="card-title">Configurações do pedido não programado</h4>
                                <h6 class="card-subtitle">Informações necessária para dar andamento ao pedido</h6>
                                <form action="#" class="tab-wizard wizard-circle">
                                    <!-- Step 1 -->
                                    <section class="mt-5">
                                        <div class="row text-center">
                                            <div class="col-md-6">
                                                <button type="button" @if (count($arr_month)>0) class="btn btn-success" @else class="btn btn-secondary" @endif style="height: 190px;width: 190px;" id="SelClient">
                                                    <i class="ti-user" style="font-size: 45px;"></i>
                                                    <div style="position: relative;top: 50px;">Escolha o cliente</div>
                                                </button>
                                                <i class="ti-arrow-right icon_choose" style="position: relative;font-size: 20px;left: 105px;font-weight: bold;"></i>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" @if (count($arr_month)>0) class="btn btn-success" @else class="btn btn-secondary isblock" @endif style="height: 190px;width: 190px;" id="SelCondition">
                                                    <i class="ti-money" style="font-size: 45px;"></i>
                                                    <div style="position: relative;top: 50px;">Condição comercial</div>
                                                </button>
                                            </div>
                                        </div>
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="/comercial/operacao/programation/all"><button type="button" class="btn btn-dark"> Sair</button></a>
                <button type="button" onclick="choiceProgramation()" class="btn btn-success"> <i class="fa fa-check"></i> Concluir</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal chooseClient" tabindex="2" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none; background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-sm modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Escolha o cliente</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12 form-group">
                    <label for="client">Cliente</label>
                    <select name="client" id="modal_sel_client" class="form-control select2-client" style="width: 100%;" multiple></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="recuseOption(1)" class="btn btn-dark"> Cancelar</button>
                <button type="button" onclick="choiceOption(1)" class="btn btn-success"> Escolher</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal chooseCondition" tabindex="0" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none; background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-sm modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Escolha a condição</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12 form-group">
                    <label for="client">Condição</label>
                    <select id="modal_sel_condition" class="form-control">
                        <option value=""></option>
                        @foreach ($tables as $key)
                            <option class="client_id_condition-{{$key->client_group_id}} op_condition" @if (count($arr_month)>0) @if($table->id == $key->id) selected @endif @endif value="{{$key->id}}">{{$key->name}} ({{$key->code}})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="recuseOption(2)" class="btn btn-dark"> Cancelar</button>
                <button type="button" onclick="choiceOption(2)" class="btn btn-success"> Escolher</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal chooseMonth" tabindex="0" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none; background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-sm modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Escolha o mês</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12 form-group">
                    <label for="client">Mês</label>
                    <select id="modal_sel_month" class="form-control">
                        <option value=""></option>
                        @foreach ($months as $key)
                            @php $date = new \Carbon\Carbon($key->date); @endphp
                            <option value="{{date('Y-m-01', strtotime($key->date))}}" @if (count($arr_month)>0) @if($month == date('Y-m-01', strtotime($key->date))) selected @endif @endif>{{ucfirst($date->locale('pt_BR')->isoFormat('MMMM'))}} de {{$date->locale('pt_BR')->isoFormat('YYYY')}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="recuseOption(3)" class="btn btn-dark"> Cancelar</button>
                <button type="button" onclick="choiceOption(3)" class="btn btn-success"> Escolher</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection

@section('page-scripts')

    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/elite/assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
	<script src="/karma/bootstrap/bootboxjs/bootboxjs.min.js"></script>
    <script type="text/javascript">

        var products = {!! json_encode($arr_month) !!};
        var oldvalue;
        var globalthis;
        var prog_id = 0;
        var step = @if (count($arr_month)>0) 3; @else 1; @endif
		var areas_discount = {
			'RR': 'O desconto de área de incentivo', 
			'AC': 'O desconto de área de incentivo', 
			'RO': 'O desconto de área de incentivo', 
			'AP': 'O desconto de área de incentivo', 
			'AM': 'O ICMS-ST'
		};
		
		$('#state_invoice').change(function() {
			if (areas_discount[$(this).val()]) {
				$('.has_apply_discount_elem').show();
				$('.has_apply_discount').html(areas_discount[$(this).val()]+' foi incluido no preço unitário?');
				$('#has_apply_discount').val('');
			} else {
				$('.has_apply_discount_elem').hide();	
				$('#has_apply_discount').val('');	
			}
		});

        $('#SelClient').click(function () {
            if (step >= 1)
                $('.chooseClient').modal();
        });

        $('#SelCondition').click(function () {
            if (step >= 2) {
                //$('.op_condition').hide();
                //$('.client_id_condition-'+$('#modal_sel_client').val()).show();
                $('.chooseCondition').modal();
            }

        });

        $('#SelMonth').click(function () {
            if (step == 3)
                $('.chooseMonth').modal();
        });

        function choiceOption(op) {
            if (op == 1) {
                if ($('#modal_sel_client').val() == "") {
                    return $error('Você precisa selecionar o cliente.');
                }
                step = 2;
                $('#client_id').val($('#modal_sel_client').val());
                $('#SelClient').removeClass('btn-secondary').addClass('btn-success');
                $('#SelCondition').removeClass('isblock');
                $('.chooseClient').modal('toggle');

                // Reset
                $('#table_id').val('');
                $('#modal_sel_condition').val('');
                //$('#monthyear').val('');
                $('#modal_sel_month').val('');
                $('#SelClient').removeClass('btn-secondary').addClass('btn-success');
                $('#SelCondition').removeClass('btn-success').addClass('btn-secondary');
                $('#SelMonth').removeClass('btn-success').addClass('btn-secondary');
                $('#SelMonth').addClass('isblock');
            } else if (op == 2) {
                if ($('#modal_sel_condition').val() == "") {
                    return $error('Você precisa selecionar a condição.');
                }
                step = 3;
                $('#table_id').val($('#modal_sel_condition').val());
                $('#SelCondition').removeClass('btn-secondary').addClass('btn-success');
                $('#SelMonth').removeClass('isblock');
                $('.chooseCondition').modal('toggle');

                // Reset
                //$('#monthyear').val('');
                $('#modal_sel_month').val('');
                $('#SelClient').removeClass('btn-secondary').addClass('btn-success');
                $('#SelCondition').removeClass('btn-secondary').addClass('btn-success');
                $('#SelMonth').removeClass('btn-success').addClass('btn-secondary');
            } else if (op == 3) {
                if ($('#modal_sel_month').val() == "") {
                    return $error('Você precisa selecionar o mês do pedido.');
                }
                //$('#monthyear').val($('#modal_sel_month').val());
                $('#SelMonth').removeClass('btn-secondary').addClass('btn-success');
                $('.chooseMonth').modal('toggle');
            }
        }

        function recuseOption(op) {
            if (op == 1) {
                step = 1;
                $('#client_id').val('');
                $('#modal_sel_client').val('');
                $('#SelClient').removeClass('btn-success').addClass('btn-secondary');
                $('#SelCondition').removeClass('btn-success').addClass('btn-secondary');
                $('#SelMonth').removeClass('btn-success').addClass('btn-secondary');
                $('#SelCondition').addClass('isblock');
                $('#SelMonth').addClass('isblock');
                $('.chooseClient').modal('toggle');
            } else if (op == 2) {
                step = 2;
                $('#table_id').val('');
                $('#modal_sel_condition').val('');
                $('#SelClient').removeClass('btn-secondary').addClass('btn-success');
                $('#SelCondition').removeClass('btn-success').addClass('btn-secondary');
                $('#SelMonth').removeClass('btn-success').addClass('btn-secondary');
                $('#SelMonth').addClass('isblock');
                $('.chooseCondition').modal('toggle');
            } else if (op == 3) {
                step == 3;
                $('#monthyear').val('');
                $('#modal_sel_month').val('');
                $('#SelClient').removeClass('btn-secondary').addClass('btn-success');
                $('#SelCondition').removeClass('btn-secondary').addClass('btn-success');
                $('#SelMonth').removeClass('btn-success').addClass('btn-secondary');
                $('.chooseMonth').modal('toggle');
            }
        }

        function insertCustomPrice($this) {
            globalthis = $this;
            oldvalue = $($this).attr('data-amount');
            $('#customprice').val($($this).attr('data-amount'));
            $('#editprice').modal();
        }

        function updateValue() {
            $('#editprice').modal('toggle');
            products.forEach(function (cat){
               if (cat.id == $(globalthis).attr('data-cat')) {
                   cat.products.forEach(function (prod) {
                      if (prod.id == $(globalthis).attr('data-product')) {
                        prod.price = $('#customprice').val();
                        if ($('#customprice').val() == oldvalue)
                            prod.is_custom = 0;
                        else
                            prod.is_custom = 1;

                        var val = parseFloat($('#customprice').val());
                        $('#cat-'+cat.id+'-product-'+prod.id+'-price').html(val.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
                      }
                   });
               }
            });
            $('#customprice').val('');
            reCalcColumn();
        }
		
		function reCalcCubage() {
			var cub_total = 0.0;
			products.forEach(function (cat){
                cat.products.forEach(function (prod) {
					if (prod.qtd > 0) {
						var ctotal = prod.calc_cubage * prod.qtd;
						cub_total = cub_total + ctotal;
					}
				});
            });
			$('.cub_total').html(cub_total.toFixed(2));
		}

        // Type = 1 is value qtd and Type = 2 is porcent of the descoint
        function insertValue($this, type) {
            products.forEach(function (cat){
                if (cat.id == $($this).attr('data-cat')) {
                    cat.products.forEach(function (prod) {
                        if (prod.id == $($this).attr('data-product')) {
                            if (type == 1) {
                                prod.qtd = $($this).val();
                            } else {
                                prod.descoint = $($this).val();
                            }
                        }
                    });
                }
            });

            reCalcColumn();
        }

        function reCalcColumn() {
            var total = 0.00;
            products.forEach(function (cat){
                cat.products.forEach(function (prod) {
                    var trow = prod.qtd * prod.price;
                    if (typeof prod.descoint != 'undefined')
                        trow = trow * (1-(prod.descoint/100));

                    total = total + trow;
                    $('#cat-'+cat.id+'-product-'+prod.id+'-total').html(trow.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}))
                });
            });

            $('#total').html(total.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));

            $('#json_order').val(JSON.stringify(products));
			reCalcCubage();
        }

        $('.pct').keyup(function() {
            if ($('.pct').val() >= 100) {
                $('.pct').val(100);
            }
        });

        function selectProgramation() {
            $('.select2-search__field').unmask();
            $('#modal-programation').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }

        function selectMonthBuilder() {

            $(".js-select22").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {
                        return "Sem resultados...";
                    },
                    searching: function () {
                        return "Buscando resultados...";
                    },
                    loadingMore: function () {
                        return 'Carregando mais resultados...';
                    },
                    maximumSelected: function (args) {
                        return 'Você já selecionou o mês da programação';
                    },
                },
                ajax: {
                    url: '/comercial/operacao/order/select/months?id='+prog_id,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
        }

        function choiceProgramation() {
            if ($('#client_id').val() == 0)
                return $error('Você precisa escolher o cliente!');
            else if ($('#table_id').val() == 0)
                return $error('Você precisa escolher a condição comercial!');
            else if ($('#monthyear').val() == 0)
                return $error('Você precisa escolher o mês do pedido!');
            else
                window.location.href = '/comercial/operacao/order/confirmed/new?monthyear='+$('#monthyear').val()+'&client_id='+$('#client_id').val()+'&table_id='+$('#table_id').val();
        }

        function saveOrder() {
            if ($('#state_invoice').val() == '') {
                return $error('Você precisa informar o estado de faturamento.');
            } else if ($('#date_payment').val() == '') {
                return $error('Você precisa informar a condição de pagamento!');
            } else if ($('#commission').val() == '') {
                return $error('Você precisa informar um número para comissão!');
            } else if ($('#date_invoice').val() == '') {
                return $error('Informe a data de faturamento!');
            }
            @if (count($arr_month)>0)
                @if ($table->cif_fob == 0)
            else if ($('input[name="day_receiver"]:checked').val() == 2) {
                if ($('#hour_start_mon_fri').val() == '') {
                    return $error('Informe a Hora inicial Seg - Sex!');
                } else if ($('#hour_end_mon_fri').val() == '') {
                    return $error('Informe a Hora final Seg - Sex!');
                } else if ($('#hour_start_sat').val() == '') {
                    return $error('Informe a Hora inicial Sábado!');
                } else if ($('#hour_end_sat').val() == '') {
                    return $error('Informe a Hora final Sábado!');
                }
            }

            if ($('input[name="day_receiver"]:checked').val() == 1) {
                if ($('#hour_start_mon_fri').val() == '') {
                    return $error('Informe a Hora inicial Seg - Sex!');
                } else if ($('#hour_end_mon_fri').val() == '') {
                    return $error('Informe a Hora final Seg - Sex!');
                }
            }

            if ($('input[name="receiver"]:checked').val() == 2) {
                if ($('#apm_name').val() == '') {
                    return $error('Informe o nome da pessoa do agendamento!');
                } else if ($('#apm_phone').val() == '') {
                    return $error('Informe o telefone da pessoa do agendamento!');
                } else if ($('#apm_email').val() == '') {
                    return $error('Informe o email da pessoa do agendamento!');
                }
            }

            if ($('input[name="discharge"]:checked').val() == 2) {
                if ($('#price_charge').val() == '') {
                    return $error('Informe o valor por carga!');
                }
            }
            @endif
                @endif
			
			if (areas_discount[$("#state_invoice").val()]) {
				if ($('#has_apply_discount').val() == '') {
					return $error('Escolha uma opção acima dos produtos.');
				}
			}
            if ($('#total').html() == 'R$&nbsp;0,00') {
                return $error('Você precisa informar ao menos 1 quantidade de um produto em específico para dar continuidade!');
            }

            bootbox.dialog({
				className: 'my-dialog',
                message: "Você irá gerar um pedido de vendas, mas para dar continuidade, terá que imprimir o pedido e pedir assinatura do cliente, deseja continuar?",
                title: "Aviso importante",
                buttons: {
                    danger: {
                        label: "Cancelar",
                        className: "btn-default",
                        callback: function(){}
                    },
                    main: {
                        label: "Confirmar",
                        className: "btn-primary",
                        callback: function() {
                            block();
                            $('#sendOrder').submit();
                        }
                    }
                }
            });

        }

        $('input[name="day_receiver"]').change(function() {
           if ($('input[name="day_receiver"]:checked').val() == 3) {
                $('.hour_times_1').hide();
                $('.hour_times_2').hide();
           } else if ($('input[name="day_receiver"]:checked').val() == 2) {
                $('.hour_times_1').show();
                $('.hour_times_2').show();
           } else if ($('input[name="day_receiver"]:checked').val() == 1) {
                $('.hour_times_1').show();
                $('.hour_times_2').hide();
            }
        });

        $('input[name="receiver"]').change(function() {
            if ($('input[name="receiver"]:checked').val() == 1) {
                $('.appointment').hide();
            } else {
                $('.appointment').show();
            }
        });

        $('input[name="discharge"]').change(function() {
           if ($('input[name="discharge"]:checked').val() == 2) {
                $('.discharge_total').show();
           } else {
                $('.discharge_total').hide();
            }
        });

        $(document).ready(function () {

            @if (count($arr_month) == 0)
                selectProgramation();
            @endif

			$(".select2-client").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {
                        return 'Cliente não existe...';
                    },
                    maximumSelected: function (e) {
                        return 'você só pode selecionar 1 item';
                    }
                },
                ajax: {
                    url: '/comercial/operacao/client/dropdown',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;
                    }
                }
            });
			
            $("#sel_type").change(function (e) {
                if ($("#sel_type").val() == 0) {
                    $('.select2-search__field').unmask();
                } else if ($("#sel_type").val() == 1) {

                    $('.select2-search__field').mask('000.000.000-00', {reverse: false});

                } else if ($("#sel_type").val() == 2) {

                    $('.select2-search__field').mask('00.000.000/0000-00', {reverse: false});
                }

            });

            $(".js-select21").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {
                        return "Sem resultados...";
                    },
                    searching: function () {
                        return "Buscando resultados...";
                    },
                    loadingMore: function () {
                        return 'Carregando mais resultados...';
                    },
                    maximumSelected: function (args) {
                        return 'Você já selecionou sua programação';
                    },
                },
                ajax: {
                    url: '/comercial/operacao/order/select/programations',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });

            $('.js-select21').on('select2:select', function (e) {
                var data = e.params.data;
                prog_id = data.id;
                $('#programation_id').val(data.id);
                $('.js-select22').select2('destroy');
                selectMonthBuilder();
                $('.chs_month').show();

            });

            $('.js-select21').on('select2:unselect', function (e) {
                $('.chs_month').hide();
                $('.js-select22').val(null).trigger("change");
                $('#programation_id').val(0);
            });

            $('.js-select22').on('select2:select', function (e) {
                var data = e.params.data;
                $('#programation_month_id').val(data.id);
            });

            $('.js-select22').on('select2:unselect', function (e) {
                $('#programation_month_id').val(0);
            });

            @if (count($arr_month) > 0)
                $(".js-select23").select2({
                    maximumSelectionLength: 1,
                    language: {
                        noResults: function () {
                            return "Sem resultados...";
                        },
                        searching: function () {
                            return "Buscando resultados...";
                        },
                        loadingMore: function () {
                            return 'Carregando mais resultados...';
                        },
                        maximumSelected: function (args) {
                            return 'Você já selecionou o cliente';
                        },
                    },
                    ajax: {
                        url: '/comercial/operacao/select/group/client?id={{Request::get('client_id')}}',
                        data: function (params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }
                            // Query parameters will be ?search=[term]&page=[page]
                            return query;
                        }
                    }
                });

            $('.js-select23').on('select2:select', function (e) {
                var data = e.params.data;
                $('#c_address').html(data.address);
                $('#c_state').html(data.state);
                $('#c_city').html(data.city);
                $('#c_district').html(data.district);
                $('#c_zipcode').html(data.zipcode);
            });

            $('.js-select23').on('select2:unselect', function (e) {
                $('#c_address').html($('#c_address').attr('data-address'));
                $('#c_state').html($('#c_state').attr('data-state'));
                $('#c_city').html($('#c_city').attr('data-city'));
                $('#c_district').html($('#c_district').attr('data-district'));
                $('#c_zipcode').html($('#c_zipcode').attr('data-zipcode'));
            });
            @endif

            selectMonthBuilder();
            $('#client_phone, #apm_phone').mask('(00) 0000-00009');
            $('#client_phone, #apm_phone').blur(function(event) {
                if($(this).val().length == 15){ // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
                    $('#fone').mask('(00) 00000-0009');
                } else {
                    $('#fone').mask('(00) 0000-00009');
                }
            });
            $('#customprice').mask('0000.00', {reverse: true});
            $('#price_charge').mask('0000.00', {reverse: true});
            $('.pct').mask('000', {reverse: true});
			$('#vpc_view').mask('00.00', {reverse: true});
		
            $('#commission').mask('00.00', {reverse: true});

            $.fn.datepicker.dates['en'] = {
                days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
                daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
                months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                today: "Hoje",
                clear: "Limpar",
                format: "mm/dd/yyyy",
                titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
                weekStart: 0
            };
            $(".date").datepicker( {
                format: "dd/mm/yyyy",
            });
            $('.hour').clockpicker({
                autoclose: true,
            });

        });

        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    </script>

@endsection
