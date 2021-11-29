@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/dashboard">Home</a></li>
    <li><a href="/commercial/orderconfirmed//all">Pedido não programado</a></li>
    <li class="active">Solicitações de aprovação</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#filterModal">
                    <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                </a>
            </div>
        </div>
    </div><!-- End .inner-padding -->
</header>
<div class="window">
    <div class="alert alert-block alert-inline-top alert-dismissable">
        <h4>AVISO!</h4>
        Essa página é destinada apenas para aprovação da <b>DIREÇÃO COMERCIAL</b> & <b>DIREÇÃO FINANCEIRA</b>. Outras aprovações abaixo serão feitas no painel do representante.
    </div>
    <div class="inner-padding">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-wrapper">
                    <header>
                        <h3>SOLICITAÇÕES</h3>
                    </header>
                    <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                        <thead>
                            <tr>
                                <th scope="col" data-rt-column="Código">Código</th>
                                <th scope="col" data-rt-column="Solicitante">Solicitante</th>
                                <th scope="col" data-rt-column="Cliente">Cliente</th>
                                <th scope="col" data-rt-column="Criado em">Criado em</th>
                                <th scope="col" data-rt-column="Status">Status</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $key)
                            <tr>
                                <td>{{$key->code}}</td>
                                <td>
                                    @if ($key->manual_order_sales)
										<a href="/user/list?r_code={{$key->user->r_code}}" target="_blank" style="color: #428bca;">
                                        	<b>Colaborador</b><br>
                                        	{{$key->user->full_name}}
										</a>
                                    @else
										<a href="/commercial/salesman/list?code={{$key->salesman->code}}" target="_blank" style="color: #428bca;">
                                        	<b>Representante</b><br>
                                        	{{$key->salesman->short_name}}
										</a>	
                                    @endif
                                </td>
                                <td>
									<a href="/commercial/client/list?code={{$key->client->code}}" target="_blank" style="color: #428bca;">
                                    	{{$key->client->company_name}}
									</a>	
                                </td>
                                <td>{{date('d/m/Y', strtotime($key->created_at))}}</td>
                                <td>
                                    <span class="label label-warning">Em análise</span>
                                </td>
                                <td>
                                    <select json-data="{{$key->id}}" onchange="action(this)" class="simpleselect form-control">
                                        <option></option>
                                        <option value="1">Análisar</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $order->appends(getSessionFilters()[2]->toArray())->links(); ?>
                    </ul>
                </div>
                <div class="spacer-50"></div>
            </div>
        </div>

    </div>
    <!-- End .inner-padding -->
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
                        <div class="col-sm-12 form-group">
                            <label for="code">Código do pedido</label>
                            <input type="text" name="code_order" value="" class="form-control" />
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="subordinates">Vendedores</label>
                            <select name="subordinates" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="users">Colaboradores</label>
                            <select name="users" class="form-control select2-users" style="width: 100%;" multiple></select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="client">Cliente</label>
                            <select name="client" class="form-control select2-client" style="width: 100%;" multiple></select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="start_date">Data</label>
                            <input type="text" name="start_date" value="" class="form-control myear" />
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

<script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script>
    function searchChanger($bool) {

        if ($bool) {
            var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('input[name="cnpj_rg"]').mask(mask, options);
                }
            };

            $('input[name="cnpj_rg"]').attr('placeholder', 'Pesquisa por CNPJ/CPF...');
            $('input[name="cnpj_rg"]').mask('000.000.000-009', options);
        } else {
            $('input[name="cnpj_rg"]').attr('placeholder', 'Pesquisa por RG...');
            $('input[name="cnpj_rg"]').unmask();
        }

        // Close dropdown;
        $('input[name="cnpj_rg"]').click();
    }

    function action($this = '') {

        var id = $($this).attr('json-data');

        if ($($this).val() == 1)
            window.open('/commercial/order/confirmed/approv/view/'+id, '_self');

        $($this).val('');

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

        $(".select2-users").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return 'Colaborador não encontrado...';
                },
                maximumSelected: function (e) {
                    return 'você só pode selecionar 1 item';
                }
            },
            ajax: {
                url: '/commercial/user/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;

                    console.log(query);
                }
            }
        });
		
        $("#filterNow").click(function (e) {
            $("#filterModal").modal('toggle');
            block();
            $("#filterData").submit();

        });

        var options = {
            onKeyPress : function(cpfcnpj, e, field, options) {
                var masks = ['000.000.000-009', '00.000.000/0000-00'];
                var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                $('input[name="cnpj_rg"]').mask(mask, options);
            }
        };

        $('table').responsiveTables({
            columnManage: false,
            exclude: '.table-collapsible, .table-collapsible-open',
            menuIcon: '<i class="fa fa-bars"></i>',
            startBreakpoint: function(ui){
                //ui.item(element)
                ui.item.find('label').parents('.rt-responsive-row').hide();
            },
            endBreakpoint: function(ui){
                //ui.item(element)
                ui.item.find('label').parents('.rt-responsive-row').show();
            },
            onColumnManage: function(){}
        });

        $("#orderSale").addClass('menu-open');
        $("#orderConfirmed").addClass('menu-open');
        $("#orderConfirmedApprov").addClass('page-arrow active-page');
    });
</script>

@endsection
