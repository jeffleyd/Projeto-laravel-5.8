@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/order/list">Home</a></li>
    <li class="active">Produtos</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<style>
 .block-price {
    border: solid 1px;
    text-align: center;
 }

 .bfr {
    background: #d9e6f6;
 }

 .bqf {
    background: #f5f5f0;
 }

 .spaceColumn {
    margin: 0 20px 0px 20px;
 }

 .centerColumn {
    display: flex;
    justify-content: center;
 }

.select2-container--default .select2-selection--multiple {
       border-radius: 0px;
   }
</style>
<link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
<script src="/karma/js/plugins/strtr.js"></script>
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-default" onclick="editTablePrice()" href="#">
                    <i class="fa fa-floppy-o"></i>&nbsp; Salvar
                </a>
                <a class="btn btn-success" onclick="window.location.href = '/commercial/client/conditions/table/export/{{$id}}'" href="#">
                    <i class="fa fa-file" style="color:white;"></i>&nbsp; Exportar
                </a>
            </div>
        </div>
		<div class="pull-right">
			<div class="input-group">
				<form action="">
					<input style="float: left;width: 80px;" placeholder="Ano: 2021" class="form-control" value="{{Request::get('date')}}" name="date" type="text">
					<span style="width: 81px;float: right;" class="input-group-btn">
						<button class="btn btn-default" type="submit">Buscar</button>
					</span>
				</form>
			</div>
		</div>
    </div><!-- End .inner-padding -->
</header>
<div class="window">
    <div class="actionbar">
        <div class="pull-left">
            <ul class="ext-tabs">
                <li class="active">
                    <a href="#content-tab-1">Condições comerciais</a>
                </li>
                <li>
                    <a href="#content-tab-2">Tabela de preços</a>
                </li>
				<li>
                    <a href="#content-tab-3">Preços fixos</a>
                </li>
            </ul><!-- End .ext-tabs -->
        </div>
    </div>
    <form action="" id="formEditPrice" method="POST">
    <input type="hidden" name="id" id="id" value="{{$id}}">
    <input type="hidden" name="template_id" id="template_id">
    <input type="hidden" name="template" id="template">
    <div class="tab-content">
        <div id="content-tab-1" class="tab-pane active">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12" style="text-align:center; margin-bottom:15px">
                        <label for="salesman_id">Representante</label>
                        <select id="salesman_id" class="form-control js-select22" name="salesman_id" style="width:100%" multiple>
                            @if ($table)
                                @if ($table->salesman)
                                <option value="{{$table->salesman->id}}" selected>{{$table->salesman->first_name}} {{$table->salesman->last_name}}</option>
                                @endif
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-12">
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>NOME DA TABELA</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="@if ($table) {{$table->name}} @endif" class="form-control">
                                        <p style="font-size: 10px;">Nome da tabela para encontrar mais fácil</p>
                                    </div>
                                </div>
								<div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>TEM PREÇO FIXO?</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="is_fixed_price" id="is_fixed_price" class="form-control">
											<option value="0" @if ($table) @if ($table->is_fixed_price == 0) selected @endif @endif>Não</option>
                                            <option value="1" @if ($table) @if ($table->is_fixed_price == 1) selected @endif @endif>Sim</option>
                                        </select>
                                        <p style="font-size: 10px;">As condições informadas e também os preços simulados não irão influênciar, você precisará digita o preço de cada conjunto.</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>É INTERNA?</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="manual_table_price" id="manual_table_price" class="form-control">
											<option value="0" @if ($table) @if ($table->manual_table_price == 0) selected @endif @endif>Não</option>
                                            <option value="1" @if ($table) @if ($table->manual_table_price == 1) selected @endif @endif>Sim</option>
                                        </select>
                                        <p style="font-size: 10px;">Caso a tabela de preço seja definida como interna, você poderá usa-la para gerar pedidos internamente.</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>É PROGRAMADO?</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select onchange="updatePrice()" data-id="32" name="is_programmed" id="is_programmed" class="form-control">
											<option value="0" @if ($table) @if ($table->is_programmed == 0) selected @endif @endif>Não</option>
                                            <option value="1" @if ($table) @if ($table->is_programmed == 1) selected @endif @endif>Sim</option>
                                        </select>
                                        <p style="font-size: 10px;">Defini a condição para programada ou não.</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>TIPO DE CLIENTE</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select onchange="updatePrice()" name="type_client" id="type_client" class="form-control">
                                            <option value=""></option>
                                            @foreach ($fields->where('column_salesman_table_price', 'type_client') as $field)
                                                <option value="{{$field->id}}" @if ($table) @if ($table->type_client == $field->id) selected @endif @endif>{{$field->name}}</option>
                                            @endforeach
                                        </select>
                                        <p style="font-size: 10px;">1- Classificação do Cliente</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>É SUFRAMA?</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select onchange="updatePrice()" data-id="23" name="is_suframa" id="is_suframa" class="form-control">
                                            <option value="1" @if ($table) @if ($table->is_suframa == 1) selected @endif @endif>Não</option>
                                            <option value="2" @if ($table) @if ($table->is_suframa == 2) selected @endif @endif>Sim</option>
                                        </select>
                                        <p style="font-size: 10px;">2- Zona suframada</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>DESCONTO EXTRA</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" onkeyup="updatePrice()" data-id="9" name="descont_extra" id="descont_extra" value="@if ($table) {{number_format($table->descont_extra, 2, '.', '')}} @endif" class="form-control">
                                        <p style="font-size: 10px;">3- Informar caso necessite desconto adicional ao cliente.</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>CARGA COMPLETA</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="charge" onchange="updatePrice()" id="charge" class="form-control">
                                            <option value=""></option>
                                            @foreach ($fields->where('column_salesman_table_price', 'charge') as $field)
                                                <option value="{{$field->id}}" @if ($table) @if ($table->charge == $field->id) selected @endif @endif>{{$field->name}}</option>
                                            @endforeach
                                        </select>
                                        <p style="font-size: 10px;">4- Informar se carga completa ou parcial.</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>CONTRATO / VPC</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" data-id="13" onkeyup="updatePrice()" name="contract_vpc" id="contract_vpc" value="@if ($table) {{number_format($table->contract_vpc, 2, '.', '')}} @endif" class="form-control">
                                        <p style="font-size: 10px;">5- Informar % de VPC ou Contrato</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>PRAZO MÉDIO</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" data-id="14" onkeyup="updatePrice()" name="average_term" id="average_term" value="@if ($table) {{$table->average_term}} @endif" class="form-control">
                                        <p style="font-size: 10px;">6- Prazo Médio de Pagamento DDF (Dias de faturamento)</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>PIS / CONFINS</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="pis_confis" onchange="updatePrice()" id="pis_confis" class="form-control">
                                            <option value=""></option>
                                            @foreach ($fields->where('column_salesman_table_price', 'pis_confis') as $field)
                                                <option value="{{$field->id}}" @if ($table) @if ($table->pis_confis == $field->id) selected @endif @endif>{{$field->name}}</option>
                                            @endforeach
                                        </select>
                                        <p style="font-size: 10px;">7- PIS/Cofins (atentar para regime de apuração cliente, se for lucro real é 3,65%, se for presumido ou simples é 7,30%)</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>TIPO DE ENTREGA</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="sel_cif_fob" onchange="updatePrice()" id="sel_cif_fob" class="form-control">
                                            <option @if ($table) @if ($table->cif_fob == 0) selected @endif @endif value="1">CIF</option>
                                            <option @if ($table) @if ($table->cif_fob > 0) selected @endif @endif value="2">FOB</option>
                                        </select>
                                        <p style="font-size: 10px;">8- Escolha a região para realizar a dedução.</p>
                                    </div>
                                </div>
                                <div class="spacer-10 cif_fob_m" @if($table) @if ($table->cif_fob == 0) style="display: none" @endif @endif></div>
                                <div class="row cif_fob" @if ($table) @if ($table->cif_fob == 0) style="display: none" @endif @endif>
                                    <div class="col-sm-3">
                                        <label>REGIÃO</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="cif_fob" onchange="updatePrice()" id="cif_fob" class="form-control">
                                            <option value=""></option>
                                            @foreach ($fields->where('column_salesman_table_price', 'cif_fob') as $field)
                                                <option value="{{$field->id}}" @if ($table) @if ($table->cif_fob == $field->id) selected @endif @endif>{{$field->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>ICMS</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="icms" onchange="updatePrice()" id="icms" class="form-control">
                                            <option value=""></option>
                                            @foreach ($fields->where('column_salesman_table_price', 'icms') as $field)
                                                <option value="{{$field->id}}" @if ($table) @if ($table->icms == $field->id) selected @endif @endif>{{$field->name}}</option>
                                            @endforeach
                                        </select>
                                        <p style="font-size: 10px;">9- ICMS</p>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>AJUSTE COMERCIAL</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" data-id="22" onkeyup="updatePrice()" name="adjust_commercial" id="adjust_commercial" value="@if ($table) {{number_format($table->adjust_commercial, 2, '.', '')}} @endif" class="form-control">
                                        <p style="font-size: 10px;">10- "Gordura"  para negociação</p>
                                    </div>
                                </div>
								
								<div class="spacer-10"></div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>DATA DA CONDIÇÃO</label>
                                </div>
                                <div class="col-sm-9">
                                   <input type="text" name="date_condition" id="date_condition" value="@if ($table && $table->date_condition != null) {{date('Y-m', strtotime($table->date_condition))}}  @endif" class="form-control myear">
                                </div>
                            </div>
                            <div class="spacer-10"></div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>OBSERVAÇÃO DA CONDIÇÃO</label>
                                </div>
                                <div class="col-sm-9">
                                    <textarea name="description_condition" id="description_condition" class="form-control">@if ($table)<?= $table->description_condition ?>@endif</textarea>
                                </div>
                            </div>
                            </fieldset>
                    </div>
                </div>
            </div>
            <!-- End .inner-padding -->
            <div class="spacer-20"></div>
        </div>
        <div id="content-tab-2" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ext-tabs-vertical-wrapper ext-tabs-highlighted">
							<ul class="ext-tabs-vertical">
								@foreach ($months as $indx => $key)
								<li @if (date('m-Y', strtotime($key->date)) == date('m-Y')) class="active" @endif>
									<a href="#month-tab-{{$indx}}">{{$month[date('n', strtotime($key->date))]}} ({{date('Y', strtotime($key->date))}})</a>
								</li>
								@endforeach
							</ul><!-- End .ext-tabs -->
							<div class="tab-content">
								@foreach ($months as $indx => $key)
								<div id="month-tab-{{$indx}}" class="tab-pane @if ($indx == 0) active @endif">
									<div class="inner-padding">
										<div class="row">
											<div class="col-sm-12">
												<fieldset>
													<div class="row">
														@foreach ($products as $item)
															<div class="col-sm-6">
																<fieldset style="text-align: center;padding: 5px;margin: 15px 0px;background: #f6ebd5;">
																	<b>{{$item->name}}</b>
																</fieldset>
																<div class="row centerColumn">
																	<div class="col-sm-6 spaceColumn">
																		<div class="row">
																			@foreach ($item->setProductOnGroup->where('is_qf', 0) as $index => $set)
																				<div class="col-sm-12 padding-left-block block-price bfr" @if ($index != array_key_last($item->setProductOnGroup->where('is_qf', 0)->toArray())) style="border-bottom:none;" @endif)>
																					<div class="title">
																						<b>{{$set->resume}}</b>
																					</div>
																					<div data-price="{{$set->price_base}}" data-has-type-client="{{$set->has_type_client}}" data-adjust="{{implode(',', $set->condition_in_month[date('Y-n-01', strtotime($key->date))]['factors'])}}" data-date="{{date('Y-n', strtotime($key->date))}}" class="price_fr">
																						R$ {{$set->price_base}}
																					</div>
																				</div>
																			@endforeach
																		</div>
																	</div>

																	<div class="col-sm-6 spaceColumn">
																		<div class="row">
																			@foreach ($item->setProductOnGroup->where('is_qf', 1) as $index => $set)
																				<div class="col-sm-12 block-price bqf" @if ($index != array_key_last($item->setProductOnGroup->where('is_qf', 1)->toArray())) style="border-bottom:none;" @endif>
																					<div class="title">
																						<b>{{$set->resume}}</b>
																					</div>
																					<div data-price="{{$set->price_base}}" data-has-type-client="{{$set->has_type_client}}" data-adjust="{{implode(',', $set->condition_in_month[date('Y-n-01', strtotime($key->date))]['factors'])}}" data-date="{{date('Y-n', strtotime($key->date))}}" class="price_qf">
																						R$ {{$set->price_base}}
																					</div>
																				</div>
																			@endforeach
																		</div>
																	</div>
																</div>
															</div>
														@endforeach
													</div>
												</fieldset>
											</div>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
                    </div>
                    </div>
                </div>
            </div>
			<div id="content-tab-3" class="tab-pane">
            <div class="inner-padding">
				<div class="table-wrapper">
					<header>
						<h3 style="font-size: 17px;font-weight: 100;">Produtos disponíveis</h3>
					</header>
					<table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
						<thead>
						</thead>
						<tbody>
							@foreach ($products as $item)
							<tr style="background-color: black !important;color: white;"><td style="background-color: black !important;" colspan="3">{{$item->name}}</td></tr>
							@foreach ($item->setProductOnGroup as $key)
							<tr>
								<td>{{$key->resume}}</td>
								<td>@if ($key->is_qf == 1)
									<span class="label label-warning">Quente/Frio</span>
									@else
									<span class="label label-info">Frio</span>
									@endif
									@if ($key->capacity == 1)
									<span class="label label-danger">Alta</span>
									@else
									<span class="label label-info">Baixa</span>
									@endif
								</td>
								<td><input type="text" name="products[{{$key->id}}]" @if($table)
										   @php
										   	$r_price = $table->set_product_price_fixed->where('set_product_id', $key->id)->first();
										   @endphp
										   @if ($r_price) value="{{number_format($r_price->price,'2', '.', '')}}" @endif
										   @endif class="form-control price_dig"/></td>
							</tr>
							@endforeach
							@endforeach
						</tbody>
					</table>
				</div>
            </div>
            <!-- End .inner-padding -->
            <div class="spacer-20"></div>
        </div>
            <!-- End .inner-padding -->
            <div class="spacer-10"></div>
        </div>
        <div class="spacer-50"></div>
    </div>
    </form>
</div>

<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title editTitle">Novo template</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label>Nome</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" name="name" id="name" class="form-control" />
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button class="btn btn-primary pull-right" id="editSave">Salvar</button>
            </div>
        </div>
    </div>
 </div>

<script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script>
    var price_fr = [{<?php foreach ($months as $idx => $d) { ?> '{{date('Y-n', strtotime($d->date))}}': [], <?php } ?>}];
    var price_qf = [{<?php foreach ($months as $idx => $d) { ?> '{{date('Y-n', strtotime($d->date))}}': [], <?php } ?>}];
    var rules = <?= $rules ?>;
    var action;
    var template_id = 0;
    var template_object;
    var last_date = '';
    function resetPrices() {
        // reset prices
        price_fr = [{<?php foreach ($months as $idx => $d) { ?> '{{date('Y-n', strtotime($d->date))}}': [], <?php } ?>}];
        price_qf = [{<?php foreach ($months as $idx => $d) { ?> '{{date('Y-n', strtotime($d->date))}}': [], <?php } ?>}];

        $('.price_fr').each (function() {

            price_fr[0][$(this).attr('data-date').toString()].push({
                'price': $(this).attr('data-price'),
                'adjusts': $(this).attr('data-adjust'),
                'has_type_client': $(this).attr('data-has-type-client'),
            });
            $(this).html(parseFloat($(this).attr('data-price')).toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
        });
        $('.price_qf').each (function() {
            price_qf[0][$(this).attr('data-date').toString()].push({
                'price': $(this).attr('data-price'),
                'adjusts': $(this).attr('data-adjust'),
                'has_type_client': $(this).attr('data-has-type-client'),
            });
            $(this).html(parseFloat($(this).attr('data-price')).toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
        });
    }

    function getLastDate(date) {
        if (date != last_date) {
            last_date = date;
            return false;
        } else {
            return true;
        }
    }

    function reloadPrices() {
        // reload prices
        var i_fr = 0;
        var i_qf = 0;
        var order_total = 0.00;
        $('.price_fr').each (function() {
            if (!getLastDate($(this).attr('data-date')))
                i_fr = 0;

            $(this).html(parseFloat(price_fr[0][$(this).attr('data-date').toString()][i_fr]['price']).toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
            order_total = order_total + parseFloat(price_fr[0][$(this).attr('data-date').toString()][i_fr]['price']);
            ++i_fr;

        });
        last_date = '';
        $('.price_qf').each (function() {
            if (!getLastDate($(this).attr('data-date')))
                i_qf = 0;

            $(this).html(parseFloat(price_qf[0][$(this).attr('data-date').toString()][i_qf]['price']).toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
            order_total = order_total + parseFloat(price_qf[0][$(this).attr('data-date').toString()][i_qf]['price']);
            ++i_qf;
        });

        $(".order_total").html(order_total).toLocaleString("pt-BR", { style: "currency" , currency:"BRL"});
    }
    function updatePrice() {

        // reset prices
        resetPrices();

        // SNOW
        <?php foreach ($months as $idx => $d) { ?>
        for (let i = 0; i < price_fr[0]['{{date('Y-n', strtotime($d->date))}}'].length; i++) {
            var frelem = price_fr[0]['{{date('Y-n', strtotime($d->date))}}'][i];
            price_fr[0]['{{date('Y-n', strtotime($d->date))}}'][i]['price'] = getValuesSelecteds(frelem['price'], frelem['adjusts'], frelem['has_type_client']);
        }

        // SNOW AND HOT
        for (let i = 0; i < price_qf[0]['{{date('Y-n', strtotime($d->date))}}'].length; i++) {
            var qfelem = price_qf[0]['{{date('Y-n', strtotime($d->date))}}'][i];
            price_qf[0]['{{date('Y-n', strtotime($d->date))}}'][i]['price'] = getValuesSelecteds(qfelem['price'], qfelem['adjusts'], qfelem['has_type_client']);
        }
        <?php } ?>

        // reload prices
        reloadPrices();

    }

    function getValuesSelecteds($total, $adjusts, $hasTypeClient) {

        var total = $total;
        var adjust_commercial = calcPercent('#adjust_commercial', $("#adjust_commercial").val(), true);
        var average_term = calcPercent('#average_term', $("#average_term").val(), true);
        var contract_vpc = calcPercent('#contract_vpc', $("#contract_vpc").val(), true);
        var pis_confis = calcPercent('#pis_confis', 1, false);
        var cif_fob = 0;

        if ($("#sel_cif_fob").val() == 2) {
            $(".cif_fob").show();
            $(".cif_fob_m").show();
            cif_fob = calcPercent('#cif_fob', 1, false);
        } else {
            $("#cif_fob").val('');
            $(".cif_fob").hide();
            $(".cif_fob_m").hide();
        }

        var suframa = 0.00;
        if ($("#is_suframa").val() == 2)
            suframa = calcPercent('#is_suframa', 1, true);

        var is_programmed = 0.00;
        if ($("#is_programmed").val() == 0)
            is_programmed = calcPercent('#is_programmed', 1, true);

        var icms = calcPercent('#icms', 1, false);
        var type_client = calcPercent('#type_client', 1, false);

        // Validar se o produto pode aplicar a regra
        if ($hasTypeClient == 0)
            type_client = 0;

        var descont_extra = calcPercent('#descont_extra', $("#descont_extra").val(), true);
        var charge = calcPercent('#charge', 1, false);

        var rule1 = total*((1+(adjust_commercial/100))*(1+(is_programmed/100))*(1+(average_term/100))*(1+(contract_vpc/100)))/(1-(pis_confis/100))/(1-(icms/100));

        var adj = $adjusts.split(',');
        if (adj.length > 0) {
            var calc = rule1*(1-(type_client/100))*(1-(descont_extra/100))*(1-(suframa/100))*(1-(cif_fob/100))*(1+(charge/100));
            for (let index = 0; index < adj.length; index++) {
                var value = adj[index];

                calc = calc *(1+(value/100));
            }

            total = Math.ceil(calc);
        } else {

            total = Math.ceil(rule1*(1-(type_client/100))*(1-(descont_extra/100))*(1-(suframa/100))*(1-(cif_fob/100))*(1+(charge/100)));
        }

        return total;
    }

    function calcPercent($selector, vzs = 1, IsAttr = false) {

        if ($($selector).val()) {
            if (IsAttr) {
                return getValuesJson($($selector).attr('data-id'), vzs);
            } else {
                return getValuesJson($($selector).val(), vzs);
            }
        } else {
            return 0;
        }
    }

    function getValuesJson($id, vzs = 1) {

        for (let index = 0; index < rules.length; index++) {
            var obj = rules[index];

            if (obj.field_id == $id) {

                if (obj.is_static) {

                    // Pega lógica estática convertida em javascript.
                    var result = obj.logic_static.strtr({
                        "{factor}" : obj.logic,
                        "{input}" : vzs
                    });

                    return eval(result);
                } else {

                    var new_factor = 1 + (parseFloat(vzs * (parseFloat((parseFloat(obj.logic) - 1) * 100).toFixed(2))).toFixed(2)/100);
                    return parseFloat((new_factor - 1) * 100).toFixed(2);
                }

            }

        }

        return 0;
    }

    function save() {
        if ($('select[name="group"]').val() == null) {

            return $error('Escolha um grupo para o conjunto.');
        } else if ($('input[name="resume"]').val() == "") {

            return $error('Informe um nome resumido do conjunto.');
        } else if ($('select[name="evap"]').val() == null) {

            return $error('Escolha a unidade evaporadora.');
        } else if ($('input[name="evap_price"]').val() == "") {

            return $error('Informe o preço da unidade evaporadora.');
        } else if ($('select[name="cond"]').val() == null) {

            return $error('Escolha a unidade condensadora.');
        } else if ($('input[name="cond_price"]').val() == "") {

            return $error('Informe o preço da unidade condensadora.');
        }

        block();
        $("#sendForm").submit();
    }

    function TemplateAction($action) {
        action = $action;
        if (action == 1) {

            $("#name").val('');
            $("#template").val('');
            $("#editModal").modal();
        } else if (action == 2) {

            if (template_id == 0) {

                return $error('Selecione o template que deseja atualizar.');
            }

            bootbox.dialog({
                message: "Você realmente quer atualizar o template: '"+template_object.text+"'?",
                title: "Atualizar template",
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
                            $("#template_id").val(template_id);
                            TemplateSubmit();
                        }
                    }
                }
            });

        } else if (action == 3) {

            if (template_id == 0) {

                return $error('Selecione o template que deseja deletar.');
            }

            bootbox.dialog({
                message: "Você realmente quer deletar o template: '"+template_object.text+"'?",
                title: "Deletar template",
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
                            $("#template_id").val(template_id);
                            TemplateSubmit();
                        }
                    }
                }
            });
        }
    }

    function TemplateSubmit() {

        block();
        $("#formEditPrice").attr('action', '/commercial/client/conditions/table/template/'+action);
        $("#formEditPrice").submit();
    }

    function editTablePrice() {
		
		if($("#date_condition").val() == "") {
            return $error('Data da condição é obrigatória');
        } 
        else if($("#description_condition").val() == "") {
            return $error('Observação da condição é obrigatória');
        } 
        else {
		
			bootbox.dialog({
				message: @if ($id == 0) "Você irá criar uma nova tabela de preço, deseja confirmar essa ação?" @else "Você está atualizando sua tabela de preço, deseja confirmar essa ação?" @endif,
				title: @if ($id == 0) "Criar tabela" @else "Atualizar tabela" @endif,
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
							$("#formEditPrice").attr('action', '/commercial/client/conditions/table/edit_do');
							$("#formEditPrice").submit();
						}
					}
				}
			});
		}
    }
		
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

    $(".myear").datepicker( {
        format: "yyyy-mm",
        viewMode: "months",
        minViewMode: "months"
    });
	
    $(document).ready(function () {

        $("#editSave").click(function (e) {
            if($("#name").val() == "") {

                return $error('Preencha o nome do seu novo template.');
            }
            $("#editModal").modal('toggle');
            $("#template").val($("#name").val());
            TemplateSubmit();
        });
        $(".js-select22").select2({
            maximumSelectionLength: 1,
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

                // Query parameters will be ?search=[term]&page=[page]
                return query;
                }
            }
        });
        $(".js-select23").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {

                    return 'Client não existe...';
                }
            },
            ajax: {
                url: '/commercial/client/dropdown',
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

        $(".js-select21").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {

                    return 'Template não existe...';
                }
            },
            ajax: {
                url: '/commercial/client/conditions/template/dropdown',
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

        $('#sel_template').on('select2:select', function (e) {
            var data = e.params.data;
            template_id = data.id;
            template_object = data;

            $("#template").val(data.text);
            $("#type_client").val(data.type_client);
            $("#descont_extra").val(data.descont_extra);
            $("#charge").val(data.charge);
            $("#contract_vpc").val(data.contract_vpc);
            $("#average_term").val(data.average_term);
            $("#pis_confis").val(data.pis_confis);
            $("#cif_fob").val(data.cif_fob);
            $("#icms").val(data.icms);
            $("#adjust_commercial").val(data.adjust_commercial);
            $("#is_suframa").val(data.is_suframa);
            $("#is_programmed").val(data.is_programmed);

            if (data.cif_fob != 0) {
                $(".cif_fob").show();
                $(".cif_fob_m").show();
            } else {
                $("#cif_fob").val('');
                $(".cif_fob").hide();
                $(".cif_fob_m").hide();
            }

            updatePrice();
        });
        $('#sel_template').on('select2:unselect', function (e) {
            template_id = 0;
            $("#icms").removeAttr('readonly');
            $("#cif_fob").val('');
            $(".cif_fob").hide();
            $(".cif_fob_m").hide();
            $('#formEditPrice').each (function(){
                this.reset();
            });
            resetPrices();
            reloadPrices();
        });

        $('#descont_extra, #contract_vpc, #adjust_commercial').mask('000.00', {reverse: true});
		$('.price_dig').mask('000000.00', {reverse: true});
		
        $('#average_term').mask('000', {reverse: true});

        resetPrices();
        reloadPrices();
        updatePrice();
        $("#client").addClass('menu-open');
        $("#clientConditions").addClass('menu-open');
        $("#clientConditionsTable").addClass('page-arrow active-page');
    });
</script>

@endsection
