@extends('gree_commercial.layout')
@section('breadcrumb')
    <a href="#" class="btn" data-toggle-sidebar="left" id="menu">
        <i class="fa fa-list-ul"></i>
    </a>
    <ul class="breadcrumb">
        <li><a href="/commercial/settings">Home</a></li>
        <li class="active">Todas programações</li>
        <li class="active">Visualizar</li>
    </ul><!-- End .breadcrumb -->
@endsection
@section('content')

<style>
    .cardbox {
        box-shadow: -8px 12px 18px 0 rgba(25,42,70,.13);
        -webkit-transition: all .3s ease-in-out;
        transition: all .3s ease-in-out;
        min-height: 155px;
        display: flex;
        justify-content: center;
        flex-direction: column;
        text-align: center;
        object-fit: cover;
    }
    .qtd-td {
        border: none;
        width: 40px;
        text-align: center;
        border: 1px solid #d2d2d2;
    }
    .portlet-placeholder, .table tbody tr:hover td, .planning-timeline-timeframe > span.current-date {
        background: #0000000f;
    }
</style>
<link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#filterModal">
                    <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                </a>
				<a class="btn btn-warning" href="#" data-toggle="modal" data-target="#exportModal">
                    <i class="fa fa-file" style="color: #ffffff;"></i>&nbsp; Exportar
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
                    <a href="#content-tab-1">Macro</a>
                </li>
                <li>
                    <a href="#content-tab-2">Clientes</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content">
        <div id="content-tab-1" class="tab-pane active">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <h4 class="text-themecolor">Programação Macro</h4>
                    </div>
                </div><br>    
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions">
                                    <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                                </div>
                            </div>
                            <div class="card-body" style="text-align: center">
                                @if ($months->count() > 0)
                                    @if (Request::get('client_id'))
                                        @include('gree_commercial.programation.tableMacroClient', ['months' => $months, 'cat_uniq' => $cat_uniq,'category' => $category])
                                    @else
                                        @include('gree_commercial.programation.tableMacro', ['months' => $months, 'cat_uniq' => $cat_uniq,'category' => $category])
                                    @endif
                                @else
                                <h4>Não existe programação.</h4>
                                @endif
                                <div class="spacer-50"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <div id="content-tab-2" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <h4 class="text-themecolor">Programação Macro Clientes</h4>
                    </div>

                </div><br>    
                <div class="row">
                    <div class="col-sm-4">
                        <input name="macro_year" id="macro_year" class="form-control" placeholder="Digite o ano" value="<?= date('Y'); ?>" autocomplete="off">
                    </div>
                    <div class="col-sm-4">
                        <select name="macro_month" id="macro_month" class="form-control">
                            <option value="">Selecione o mês</option>
                            @foreach (config('gree.months') as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <a class="btn btn-primary" href="javascript:void(0)" id="btn_macro_filter">
                            <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                        </a>
                    </div>
                </div>
                <br> 
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card">
                            <div class="card-body" style="text-align: center;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-active">
                                            <td rowspan="2" style="background: white;border: none; text-align:center;vertical-align: middle;">
                                                Cliente
                                            </td>
                                            <td style="text-align: center; background: #e2fdfd; padding: 3px;" colspan="2" id="month_name">Mês</td>
                                        </tr>
                                        <tr class="table-active">
                                            <td colspan="1" style="text-align: center; padding: 3px; background-color: #fbfbef;">Total</td>
                                            <td colspan="1" style="text-align: center; padding: 3px; background-color: #fbfbef;">Saldo</td>
                                        </tr>
                                    </thead>    
                                    <tbody id="table_macro_clients">
                                        <tr>
                                            <td colspan="3" style="vertical-align: middle; background:#fff;">Informe ano e mês para filtrar</td>
                                        </tr>
                                    </tbody>    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>    
    </div>
</div>

<div id="filterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Filtrar</h4>
            </div>
            <div class="modal-body">
                <form action="/commercial/programation/macro" id="programation_filter">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="year">Data inicial</label>
                            <input name="start_date" class="form-control myear" autocomplete="off">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="year">Data final</label>
                            <input name="end_date" class="form-control myear" autocomplete="off">
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="salesman_id">Vendedores</label>
                            <select name="salesman_id" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="client_id">Cliente</label>
                            <select name="client_id" class="form-control select2-client" style="width: 100%;" multiple></select>
                        </div>
						<div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label>Quantidade</label>
                                <select class="form-control" name="is_total">
                                    <option value="1" @if (Request::get('is_total') == 1) selected @endif>Total</option>
                                    <option value="0" @if (!Request::get('is_total')) selected @endif>Saldo restante</option>
                                </select>
                            </div>
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

<div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Exportar programação macro</h4>
            </div>
            <div class="modal-body">
                <form action="/commercial/programation/macro" id="programation_export">
                    <input type="hidden" name="export" value="1">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="year">Data inicial</label>
                            <input name="start_date" id="year_begin" class="form-control myear" autocomplete="off">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="year">Data final</label>
                            <input name="end_date" id="year_final" class="form-control myear" autocomplete="off">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="salesman_id">Vendedores</label>
                            <select name="salesman_id" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="client_id">Cliente</label>
                            <select name="client_id" class="form-control select2-client" style="width: 100%;" multiple></select>
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
                <div  id="exportNow" style="float: right;width: 178px;text-align: center;position: relative;height:76px;font-weight: bold;color: #18b202;cursor: pointer;font-size: 16px;">
                    <span style="position: relative;top: 25px;">Exportar</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">

   var months = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

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

        $(".select2-sallesman").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return 'Vendedor não existe...';
                },
                maximumSelected: function (e) {
                    return 'você só pode selecionar 1 item';
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
                url: '/commercial/client/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#filterNow").click(function() {
            $("#programation_filter").submit();
        });
		
		$("#exportNow").click(function() {

            if($("#year_begin").val() == "") {
                $error('Selecione a data inicial');
            } else if($("#year_final").val() == ""){
                $error('Selecione a data final');
            } else {
                $("#programation_export").submit();
            }
        });

        $("#btn_macro_filter").click(function() {

            var year = $("#macro_year").val();
            var month = $("#macro_month").val();

            if(year == "") {
                $error('Informe o ano para filtrar');
            } else if(month == "") {
                $error('Selecione o mês para filtrar');
            } else {
                block();
                ajaxSend('/commercial/programation/macro/clients/ajax', {year: parseInt(year), month: parseInt(month)}, 'GET', '60000').then(function(result){

                    if(result.success) {

                        var index_month = month - 1;
                        
                        $("#table_macro_clients").html(loadMacroClients(result.macro_clients));
                        $("#month_name").html(months[index_month]);
                        unblock();
                    }
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                });
            }
        });

        function loadMacroClients(object) {

            var html = '';
            if(object.length > 0) {
                for (var i = 0; i < object.length; i++) {
                    var column = object[i];

                    html += '<tr>';
                    html += '    <td style="vertical-align: middle;"><a href="/commercial/client/list?code='+column.code+'" style="color: #428bca;" target="_blank">'+column.company_name+'<br>('+column.identity+')</a></td>';
                    html += '    <td style="vertical-align: middle;">'+column.total+' Qtd</td>';
                    html += '    <td style="vertical-align: middle;">'+column.quantity+' Qtd</td>';
                    html += '</tr>';
                }    
            } else {
                html += '<tr>';
                html += '    <td colspan="3" style="vertical-align: middle; background:#fff;">NÃO HÁ PROGRAMAÇÕES PARA O ANO E MÊS SELECIONADOS</td>';
                html += '</tr>';
            }
            return html;
        }

        $("#orderSale").addClass('menu-open');
        $("#programation").addClass('menu-open');
        $("#programationMacro").addClass('page-arrow active-page');
    });

</script>

@endsection
