@extends('gree_commercial_external.layout')

@section('page-css')
    <style>
        .my-dialog .modal-header{
            display: block !important;
        }
        .bqf {
            background: #f5f5f0;
        }
        .bfr {
            background: #d9e6f6;
        }
        .block-price {
            padding: 10px;
            margin: 15px;
            border: solid 1px;
            text-align: center;
            max-width: 160px;
        }
        .padding-left-block {
            padding-left: 35px !important;
        }
        .list-group-item.active {
            border-color: rgba(0,0,0,.125) !important;
        }

        .btn-action-a {
            margin: 10px;
            font-weight: 500;
            border: solid 1px #eae6e6;
            padding: 5px;
            text-align: center;
        }

        @media (min-width: 320px) and (max-width: 480px) {
            .div_sel_template {
                margin-bottom: 10px;
            }
            .div_client_selected {
                margin-bottom: 15px;
            }
        }

        /* border: 1px solid rgba(0,0,0,.125); */
    </style>
    <link href="/elite/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/elite/dist/css/pages/ribbon-page.css" rel="stylesheet">
    <link href="/elite/dist/css/pages/stylish-tooltip.css" rel="stylesheet">
	<link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endsection

@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Condição comercial</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Condição comercial</li>
                </ol>

            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <!-- Column -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    {{-- <form action="/commercial/client/conditions/table/edit_do" method="POST" id="sendForm"> --}}
                    <form action="/comercial/operacao/tabela/preco_do" method="POST" id="formEditPrice">
                        <input type="hidden" name="salesman_id" id="salesman_id" value="@if (Session::has('salesman_data')) {{Session::get('salesman_data')->id}} @endif">
                        <input type="hidden" name="client_group_id" id="client_group_id" @if($id != 0) value="{{$table->client_group_id}}" @endif >
                        <input type="hidden" name="template_id" id="template_id">
                        <input type="hidden" name="template" id="template">
                        <input type="hidden" name="products" id="products">
                        <input type="hidden" id="id" name="id" value="{{$id}}">
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>
                                        NOME DA CONDIÇÂO <i class="ti ti-help-alt" data-toggle="tooltip" title="Nome para realizar uma busca mais fácil."></i>
                                    </label>
                                    <input required type="text" name="name" id="name" value="@if ($table){{$table->name}}@endif" class="form-control">
                                </div>
                                <div class="col-sm-12" style="margin-bottom: 10px;">
                                    <label>
                                        É PROGRAMADO? <i class="ti ti-help-alt" data-toggle="tooltip" title="Defini a condição para programada ou não."></i>
                                    </label>
                                    <select onchange="updatePrice()" data-id="32" name="is_programmed" id="is_programmed" class="form-control">
                                        <option @if ($table) @if ($table->is_programmed == 1) selected @endif @endif value="1">Sim</option>
                                        <option @if ($table) @if ($table->is_programmed == 0) selected @endif @endif value="0">Não</option>
                                    </select>
                                </div>
                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>
                                            TIPO DE CLIENTE <i class="ti ti-help-alt" data-toggle="tooltip" title="1- Classificação do Cliente"></i>
                                    </label>
                                    <select onchange="updatePrice()" name="type_client" id="type_client" class="form-control">
                                        <option value=""></option>
                                        @foreach ($fields->where('column_salesman_table_price', 'type_client') as $field)
                                            <option  @if ($table) @if ($table->type_client == $field->id) selected @endif @endif value="{{$field->id}}">{{$field->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px;">
                                    <label>
                                            É SUFRAMA? <i class="ti ti-help-alt" data-toggle="tooltip" title="2- Zona suframada"></i>
                                    </label>
                                    <select onchange="updatePrice()" data-id="23" name="is_suframa" id="is_suframa" class="form-control">
                                        <option @if ($table) @if ($table->is_suframa == 1) selected @endif @endif value="1">Não</option>
                                        <option @if ($table) @if ($table->is_suframa == 2) selected @endif @endif value="2">Sim</option>
                                    </select>
                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>
                                            DESCONTO EXTRA <i class="ti ti-help-alt" data-toggle="tooltip" title="3- Informar caso necessite desconto adicional ao cliente,."></i>
                                    </label>
                                    <input required type="text" onkeyup="updatePrice()" data-id="9" name="descont_extra" id="descont_extra" value="@if ($table){{number_format($table->descont_extra, 2, '.', '')}}@endif" class="form-control">

                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>CARGA COMPLETA
                                        <i class="ti ti-help-alt" data-toggle="tooltip" title="4- Informar se carga completa ou parcial."></i>
                                    </label>
                                    <select name="charge" onchange="updatePrice()" id="charge" class="form-control">
                                        <option value=""></option>
                                        @foreach ($fields->where('column_salesman_table_price', 'charge') as $field)
                                            <option @if ($table) @if ($table->charge == $field->id) selected @endif @endif value="{{$field->id}}">{{$field->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>CONTRATO / VPC
                                        <i class="ti ti-help-alt" data-toggle="tooltip" title="5- Informar % de VPC ou Contrato"></i>
                                    </label>
                                    <input type="text" data-id="13" onkeyup="updatePrice()" name="contract_vpc" id="contract_vpc" value="@if ($table){{number_format($table->contract_vpc,2,'.','')}}@endif" class="form-control">

                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>PRAZO MÉDIO
                                        <i class="ti ti-help-alt" data-toggle="tooltip" title="6- Prazo Médio de Pagamento DDF (Dias de faturamento)"></i>
                                    </label>
                                    <input type="text" data-id="14" onkeyup="updatePrice()" name="average_term" id="average_term" value="@if ($table){{$table->average_term}}@endif" class="form-control">
                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>PIS / CONFINS
                                        <i class="ti ti-help-alt" data-toggle="tooltip" title="7- PIS/Cofins (atentar para regime de apuração cliente, se for lucro real é 3,65%, se for presumido ou simples é 7,30%)"></i>
                                    </label>
                                    <select name="pis_confis" onchange="updatePrice()" id="pis_confis" class="form-control">
                                        <option value=""></option>
                                        @foreach ($fields->where('column_salesman_table_price', 'pis_confis') as $field)
                                            <option @if ($table) @if ($table->pis_confis == $field->id) selected @endif @endif value="{{$field->id}}">{{$field->name}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>TIPO DE ENTREGA
                                        <i class="ti ti-help-alt" data-toggle="tooltip" title="8- Escolha a região para realizar a dedução."></i>
                                    </label>
                                    <select name="sel_cif_fob" onchange="updatePrice()" id="sel_cif_fob" class="form-control">
                                        <option @if ($table) @if ($table->cif_fob == 0) selected @endif @endif value="1">CIF</option>
                                        <option @if ($table) @if ($table->cif_fob > 0) selected @endif @endif value="2">FOB</option>
                                    </select>

                                </div>

                                <div class="col-sm-12 cif_fob_m" style="margin-bottom: 10px; @if ($table) @if ($table->cif_fob == 0) display: none @endif @endif">
                                    <label>REGIÃO</label>
                                    <select name="cif_fob" onchange="updatePrice()" id="cif_fob" class="form-control">
                                        <option value=""></option>
                                        @foreach ($fields->where('column_salesman_table_price', 'cif_fob') as $field)
                                            <option @if ($table) @if ($table->cif_fob == $field->id) selected @endif @endif value="{{$field->id}}">{{$field->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>ICMS
                                        <i class="ti ti-help-alt" data-toggle="tooltip" title="9- ICMS"></i>
                                    </label>
                                    <select name="icms" onchange="updatePrice()" id="icms" class="form-control">
                                        <option value=""></option>
                                        @foreach ($fields->where('column_salesman_table_price', 'icms') as $field)
                                            <option @if ($table) @if ($table->icms == $field->id) selected @endif @endif value="{{$field->id}}">{{$field->name}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>AJUSTE COMERCIAL
                                        <i class="ti ti-help-alt" data-toggle="tooltip" title='10- "Gordura"  para negociação'></i>
                                    </label>
                                    <input type="text" data-id="22" onkeyup="updatePrice()" name="adjust_commercial" id="adjust_commercial" value="@if ($table){{number_format($table->adjust_commercial, 2, '.', '')}}@endif" class="form-control">
                                </div>
								
								<div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>DATA DA CONDIÇÃO</label>
                                    <input type="text" name="date_condition" id="date_condition" value="@if ($table && $table->date_condition != null) {{date('Y-m', strtotime($table->date_condition))}}  @endif" class="form-control myear" autocomplete="off">
                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <label>OBSERVAÇÃO DA CONDIÇÃO</label>
                                    <textarea rows="4" name="description_condition" id="description_condition" class="form-control">@if ($table)<?= $table->description_condition ?>@endif</textarea>
                                </div>
								
                                <div class="col-sm-12" style="margin-top: 10px">
                                    <button type="button" class="btn btn-success btn-block" onclick="editTablePrice()">Salvar condição</button>
                                    @if ($id != 0)
                                        <button type="button" onclick="window.location.href = '/comercial/operacao/tabela/exporta/{{$id}}'" class="btn btn-primary btn-block">Exportar dados</button>
                                    @endif
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-9 col-xlg-9 col-md-7">
            <div class="card">
                <div class="inner-padding">
                    <div class="row load_products" style="background: #edf1f5;">
                        @include('gree_commercial_external.salesman.load_products',['products'=>$products, 'months' => $months])
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <div id="filterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Filtrar dados</h4>
                </div>
                <div class="modal-body">
                    <form action="{{Request::url()}}" id="filterData">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="status">Status</label>
                                <select id="status" class="form-control" name="status">
                                    <option></option>
                                    <option value="1" @if (Session::get('filter_status') == 1) selected="selected" @endif>Ativo</option>
                                    <option value="2" @if (Session::get('filter_status') == 2) selected="selected" @endif>Desativado</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="clear"></div>
                </div>
                <div class="modal-footer" style="padding: 0;height: 76px;">
                    <div data-dismiss="modal" style="float: left;width: 170px;text-align: center;position: relative;top: 0px;font-weight: bold;color: #ff0000;height: 76px;cursor: pointer; font-size: 16px;">
                        <span style="position: relative;top: 25px;">Fechar</span>
                    </div>
                <div style="position: absolute;height: 76px;border-right: solid 1px #bbb;left: 170px;right: 0;width: 1px;"></div>
                    <div  id="filterNow" style="float: right;width: 178px;text-align: center;position: relative;height:76px;font-weight: bold;color: #18b202;cursor: pointer;font-size: 16px;">
                        <span style="position: relative;top: 25px;">Filtrar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="editModal" class="modal fade my-dialog" tabindex="-1" role="dialog" aria-hidden="true">
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
@endsection

@section('page-scripts')
    <script src="/karma/js/plugins/strtr.js"></script>
    <script src="/karma/bootstrap/bootboxjs/bootboxjs.min.js"></script>
    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/i18n/pt-BR.js" type="text/javascript"></script>
    <!--<script src="/elite/dist/js/custom.min.js"></script>-->
    <script src="/elite/dist/js/pages/validation.js"></script>
	<script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        $(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
    </script>
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
                    className: 'my-dialog',
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
                    className: 'my-dialog',
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
            $("#formEditPrice").attr('action', '/comercial/operacao/client/conditions/table/template/'+action);
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
					className: 'my-dialog',
					message: @if ($id == 0) "Você irá criar uma nova condição comercial, deseja confirmar essa ação?" @else "Você está atualizando sua condição comercial, deseja confirmar essa ação?" @endif,
					title: @if ($id == 0) "Criar condição" @else "Atualizar condição" @endif,
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
								$("#formEditPrice").attr('action', '/comercial/operacao/tabela/preco_do');
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

            // $(".load_products").html( $("#table_price_atual").html() );
            $("#sel_client_id").change(function (e) {

            });

            $("#editSave").click(function (e) {
                if($("#name").val() == "") {

                    return $error('Preencha o nome do seu novo template.');
                }
                $("#editModal").modal('toggle');
                $("#template").val($("#name").val());
                TemplateSubmit();
            });

            $("#sel_client_id").select2({
                language: "pt-BR",
                maximumSelectionLength: 1,
            });

            $('#sel_client_id').on('select2:select', function (e) {
                var data = e.params.data;
                $('#client_group_id').val(data.id);
            });

            $(".js-select21").select2({
                maximumSelectionLength: 1,
                language: "pt-BR",
                ajax: {
                    url: '/comercial/operacao/client/conditions/template/dropdown',
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

            $('#client_selected').on('select2:select', function (e) {
                @php
                    $version_table_price = \App\Model\Commercial\Settings::where('command', 'version_table_price')->first('value');
                @endphp

                let data = e.params.data;
                let version_table_price = {{$version_table_price->value}};

                // template_id = data.id;
                // template_object = data;
                // console.log(data);

                if(data.table_price != null){

                    $("#id").val(data.table_price.id);

                    if(version_table_price != data.table_price.version){
                        console.log("Versao diferente abrir modal");
                        console.log(data.table_price);


                        bootbox.dialog({
                            className: 'my-dialog',
                            message: `A condição comercial deste cliente esta desatualizada!
                            <br>
                            <br>
                            Clique em Confirmar para Atualizar a condição comercial
                            <br>
                            <br>
                            <br>
                            Obs: Após atualizar a condição , voce deve salva-la para confirmar as alterações
                            `,
                            title: "Atualizar condição comercial",
                            buttons: {
                                danger: {
                                    label: "Cancelar",
                                    className: "btn-default",
                                    callback: function(){
                                        $(".load_products").html(data.table_price_html);
                                    }
                                },
                                main: {
                                    label: "Confirmar",
                                    className: "btn-primary",
                                    callback: function() {
                                        // $("#template_id").val(template_id);
                                        $(".load_products").html( $("#table_price_atual").html() );
                                        // TemplateSubmit();
                                    }
                                }
                            }
                        });

                    }else{
                        $("#sel_template").val(null).trigger("change");
                        $("#type_client").val(data.table_price.type_client);
                        $("#descont_extra").val(data.table_price.descont_extra);
                        $("#charge").val(data.table_price.charge);
                        $("#contract_vpc").val(data.table_price.contract_vpc);
                        $("#average_term").val(data.table_price.average_term);
                        $("#pis_confis").val(data.table_price.pis_confis);
                        $("#cif_fob").val(data.table_price.cif_fob);
                        $("#icms").val(data.table_price.icms);
                        $("#adjust_commercial").val(data.table_price.adjust_commercial);
                        $("#is_suframa").val(data.table_price.is_suframa);
                        $("#is_programmed").val(data.table_price.is_programmed);

                        if (data.table_price.cif_fob != 0) {
                            $(".cif_fob").show();
                            $(".cif_fob_m").show();
                        } else {
                            $("#cif_fob").val('');
                            $(".cif_fob").hide();
                            $(".cif_fob_m").hide();
                        }

                        $(".load_products").html( $("#table_price_atual").html() );
                    }
                }else{
                    $("#id").val(0);
                    $(".load_products").html( $("#table_price_atual").html() );
                }

                updatePrice();
            });

            $('#descont_extra, #contract_vpc, #adjust_commercial').mask('000.00', {reverse: true});


            resetPrices();
            reloadPrices();
            updatePrice();
            $("#client").addClass('menu-open');
            $("#clientConditions").addClass('menu-open');
            $("#clientConditionsTable").addClass('page-arrow active-page');
        });
    </script>

@endsection
