@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/order/list">Home</a></li>
    <li><a href="#">Clientes</a></li>
    <li><a href="#">Condições comerciais</a></li>
    <li class="active">Regra de preço</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<style>
 .block-price {
    padding: 10px;
    margin: 15px;
    border: solid 1px;
    text-align: center;
    max-width: 160px;
 }

 .bfr {
    background: #d9e6f6;
 }

 .bqf {
    background: #f5f5f0;
 }

 .padding-left-block {
    padding: 0px;
    padding-left: 35px;
 }

 .padding-right-block {
    padding: 0px;
    padding-right: 35px;
 }

 @media only screen and (max-width: 600px) {
    .padding-left-block {
        padding-right: 35px;
    }

    .padding-right-block {
        padding-left: 35px;
    }

    .block-price {
        max-width: none;
    }
}

.select2-container--default .select2-selection--multiple {
       border-radius: 0px;
   }
</style>
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
				<!--<a class="btn btn-default" onclick="newRule()" href="#">
                    <i class="fa fa-plus"></i>&nbsp; Nova regra
                </a>-->
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#filterModal">
                    <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                </a>
            </div>
        </div>
    </div><!-- End .inner-padding -->
</header>
<div class="window">
    <div class="actionbar">
        <div class="pull-left">
            <ul class="ext-tabs">
                <li class="active">
                    <a href="#content-tab-1">Regras personalizadas</a>
                </li>
                <li>
                    <a href="#content-tab-2">Regras estáticas</a>
                </li>
            </ul><!-- End .ext-tabs -->
        </div>
    </div>
    <div class="tab-content">
        <div id="content-tab-1" class="tab-pane active">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-wrapper">
                                        <header>
                                            <h3>REGRAS PERNOALIZADAS</h3>
                                        </header>
                                        <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                                            <thead>
                                                <tr>
                                                    <th scope="col" data-rt-column="Nome">Nome</th>
                                                    <th scope="col" data-rt-column="Multiplicador">Multiplicador</th>
                                                    <th scope="col" data-rt-column="Relação">Relação</th>
                                                    <th scope="col" data-rt-column="Operador">Operador</th>
                                                    <th scope="col" data-rt-column="Ações">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rules_custom as $key)
                                                <tr>
                                                    <td>
                                                        {{$key->name}}
                                                    </td>
                                                    <td>
                                                        {{($key->logic - 1) * 100}}
                                                    </td>
                                                    <td>
														@if ($key->OrderFieldTablePrice)
                                                        {{$key->OrderFieldTablePrice->name}}
														
                                                        <div class="table-tooltip" title="" data-original-title="Campo: {{$key->OrderFieldTablePrice->column_salesman_name}}">
                                                            <i class="fa fa-info-circle"></i>
                                                        </div>
														@endif
                                                    </td>
                                                    <td>
                                                        @if ($key->is_sum)
                                                        <span class="label label-success">Acrescentar</span>
                                                        @else
                                                        <span class="label label-danger">Deduzir</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                                            <option></option>
                                                            <option value="1">Editar</option>
                                                            <option value="2">Deletar</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pull-right" style="margin-top: 20px;">
                                        <ul class="pagination">
                                            <?= $rules_custom->appends(getSessionFilters()[0]->toArray())->links(); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="spacer-10"></div>
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
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-wrapper">
                                        <header>
                                            <h3>REGRAS ESTÁTICAS</h3>
                                        </header>
                                        <table class="table table-bordered table-striped" id="tb2" data-rt-breakpoint="600">
                                            <thead>
                                                <tr>
                                                    <th scope="col" data-rt-column="Nome">Nome</th>
                                                    <th scope="col" data-rt-column="Multiplicador">Multiplicador</th>
                                                    <th scope="col" data-rt-column="Relação">Relação</th>
                                                    <th scope="col" data-rt-column="Operador">Operador</th>
                                                    <th scope="col" data-rt-column="Ações">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rules_static as $key)
                                                <tr>
                                                    <td>
                                                        {{$key->name}}
                                                    </td>
                                                    <td>
                                                        {{($key->logic - 1) * 100}}
                                                    </td>
                                                    <td>
														@if ($key->OrderFieldTablePrice)
                                                        {{$key->OrderFieldTablePrice->name}}
                                                        <div class="table-tooltip" title="" data-original-title="Lógica: ARRENDONDA((POTÊNCIA(FATOR, (PRAZO MÉDIA/30))-1) * 100 ">
                                                            <i class="fa fa-info-circle"></i>
                                                        </div>
														@endif
                                                    </td>
                                                    <td>
                                                        @if ($key->is_sum)
                                                        <span class="label label-success">Acrescentar</span>
                                                        @else
                                                        <span class="label label-danger">Deduzir</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                                            <option></option>
                                                            <option value="1">Editar</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="spacer-50"></div>
                                </div>
                            </div>
                            <div class="spacer-10"></div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <!-- End .inner-padding -->
            <div class="spacer-10"></div>
        </div>
    </div>
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
                            <label for="field">Campo</label>
                            <select id="field" class="form-control" name="field">
                                <option></option>
                                <option value="type_client" @if (Request::get('field') == 'type_client') selected="selected" @endif>Tipo de Cliente</option>
                                <option value="descont_extra" @if (Request::get('field') == 'descont_extra') selected="selected" @endif>Desconto Extra</option>
                                <option value="charge" @if (Request::get('field') == 'charge') selected="selected" @endif>Carga</option>
                                <option value="contract_vpc" @if (Request::get('field') == 'contract_vpc') selected="selected" @endif>CONTRATO / VPC</option>
                                <option value="average_term" @if (Request::get('field') == 'average_term') selected="selected" @endif>Prazo médio</option>
                                <option value="pis_confis" @if (Request::get('field') == 'pis_confis') selected="selected" @endif>PIS / CONFIS</option>
                                <option value="icms" @if (Request::get('field') == 'icms') selected="selected" @endif>ICMS</option>
                                <option value="adjust_commercial" @if (Request::get('field') == 'adjust_commercial') selected="selected" @endif>Ajuste comercial</option>
                                <option value="is_suframa" @if (Request::get('field') == 'is_suframa') selected="selected" @endif>É Suframa</option>
                                <option value="cif_fob" @if (Request::get('field') == 'cif_fob') selected="selected" @endif>CIF / FOB</option>
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

<form action="/commercial/client/conditions/table/rules_do" method="post" id="formSend">
<input type="hidden" name="field_selected" id="field_selected" value="">
<input type="hidden" name="id" id="id" value="0">
<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title editTitle">Novo regra</h4>
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
                <div class="spacer-10"></div>
                <div class="row fields">
                    <div class="col-sm-4">
                        <label>Relação</label>
                    </div>
                    <div class="col-sm-8">
                        <select name="fields" onchange="loadChildren(this)" id="fields" class="form-control">
                            <option value=""></option>
                            <option value="type_client">Tipo de Cliente</option>
                            <option value="descont_extra">Desconto Extra</option>
                            <option value="charge">Carga</option>
                            <option value="contract_vpc">CONTRATO / VPC</option>
                            <option value="average_term">Prazo médio</option>
                            <option value="pis_confis">PIS / CONFIS</option>
                            <option value="icms">ICMS</option>
                            <option value="adjust_commercial">Ajuste comercial</option>
                            <option value="is_suframa">É Suframa</option>
                            <option value="cif_fob">CIF / FOB</option>
                        </select>
                    </div>
                </div>
                <div class="spacer-10 fields"></div>
                <div class="row subselect" style="display: none">
                    <div class="col-sm-4">
                        <label></label>
                    </div>
                    <div class="col-sm-8">
                        <select id="subselect" onchange="getValueSublevel()" class="form-control">
                        </select>
                    </div>
                </div>
                <div class="spacer-10 subselect" style="display: none"></div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Multiplicador</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" name="multiplay" id="multiplay" class="form-control" />
                    </div>
                </div>
                <div class="spacer-10"></div>
                <div class="row is_sum">
                    <div class="col-sm-4">
                        <label>Operador</label>
                    </div>
                    <div class="col-sm-8">
                        <select id="is_sum" name="is_sum" disabled="" class="form-control">
                            <option value="1">Mais</option>
                            <option value="2">Menos</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button class="btn btn-primary pull-right" id="editSave">Salvar</button>
            </div>

        </div>
    </div>
 </div>
</form>
<script>
    var children = <?= $fields ?>;
    function newRule() {
        $(".editTitle").html('Nova regra');
        $("#id").val(0);
        $("#editModal").modal();
    }

    function action($this) {

        var json = JSON.parse($($this).attr('json-data'));

        if ($($this).val() == 1) {

            $("#id").val(json.id);
            $("#name").val(json.name);

            if (json.is_static == 1) {
                $(".fields").each(function() {
                    $(this).hide();
                });
                $(".subselect").each(function() {
                    $(this).hide();
                });
                $(".is_sum").hide();
            } else {
                $(".fields").each(function() {
                    $(this).show();
                });
                $(".subselect").each(function() {
                    $(this).show();
                });
                $(".is_sum").show();
                $("#fields").val(json.order_field_table_price.column_salesman_table_price);
                loadChildren($('#fields'));
                $("#field_selected").val(json.order_field_table_price.id);
                $("#subselect").val(json.order_field_table_price.id);
            }

            $("#multiplay").val(Math.round((json.logic - 1) * 100));
            if (json.is_sum == 1)
            $("#is_sum").val(1);
            else
            $("#is_sum").val(2);

            $(".editTitle").html('Editando regra: '+json.name);
            $("#editModal").modal();
        } else if ($($this).val() == 2) {

            bootbox.dialog({
                message: "Você realmente quer deletar a regra '"+json.name+"'?",
                title: "Deletar regra",
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
                            window.location.href = '/commercial/client/conditions/table/rules/delete/'+json.id;
                        }
                    }
                }
            });
        }

        $($this).val('');

    }
    function getValueSublevel() {
        $("#field_selected").val($("#subselect").val());
    }
    function loadChildren($this) {

        var count = 0;
        var last = 0;
        var html = '<option value=""></option>';
        for (let index = 0; index < children.length; index++) {
            const elem = children[index];
            if (elem.column_salesman_table_price == $($this).val()) {
                ++count;
                last = elem.id;
                html += '<option value="'+elem.id+'">'+elem.name+'</option>';
            }
        }

        if (count == 1) {

            $("#field_selected").val(last);
            $(".subselect").each(function() {
                $(this).hide();
            });
        } else {

            $(".subselect").each(function() {
                $(this).show();
            });
            $("#field_selected").val('');
            $("#subselect").html(html);
        }

    }
    $(document).ready(function () {

        $("#filterNow").click(function (e) {
            $("#filterModal").modal('toggle');
            block();
            $("#filterData").submit();

        });

        $("#editSave").click(function (e) {

            if ($("#name").val() == "") {

                return $error('Informe um nome para sua regra!');
            } else if ($("#fields").val() == "") {

                return $error('Escolhe uma relação para a regra.');
            } else if ($("#field_selected").val() == "") {

                return $error('Você precisa definir a relação para a regra.');
            } else if ($("#multiplay").val() == "" || $("#multiplay").val() == 0) {

                return $error('Digite a porcentagem para a regra.');
            }

            block();
            $("#editModal").modal('toggle');
            $("#formSend").submit();

        });

        $('#multiplay').mask('000.00', {reverse: true});

        $("#client").addClass('menu-open');
        $("#clientConditions").addClass('menu-open');
        $("#clientConditionsRule").addClass('page-arrow active-page');
    });
</script>

@endsection
