@extends('gree_commercial.layout')

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/commercial/settings">Home</a></li>
        <li class="active">Todas programações</li>
    </ul><!-- End .breadcrumb -->
@endsection

@section('content')

    <style>
        .select-group {
            background-color: #eeeeee;
            border-color: #eeeeee;
            color: #555555;
            font-weight: 500;
        }
		.th-order {
            text-align: center;
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
                </div>
            </div>
        </div><!-- End .inner-padding -->
    </header>
    <div class="window">
        <div class="inner-padding">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-wrapper">
                        <header>
                            <h3>Programações</h3>
                        </header>
                        <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                            <thead>
                            <tr>
                                <th scope="col" data-rt-column="Código">Código</th>
								<th scope="col" data-rt-column="Gestor">Gestor</th>
                                <th scope="col" data-rt-column="Solicitante">Solicitante</th>
                                <th scope="col" data-rt-column="Cliente">Cliente</th>
                                <th scope="col" data-rt-column="Programado para">Programado para</th>
                                <th scope="col" data-rt-column="Versão">Versão</th>
                                <th scope="col" data-rt-column="Status">Status</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($programations as $key)
                                <tr>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-default btn-circle btn-xs table-tooltip" onclick="showOrders(this);" data-json='<?= htmlspecialchars(json_encode($key->programationMonth), ENT_QUOTES, "UTF-8") ?>' data-code='{{$key->code}}' data-original-title="Pedidos">
                                            <i class="fa fa-file-text-o" style="position: relative;font-size:14px;top:6px;left:8px"></i>
                                        </a>
                                        <span style="position: relative; left:1px;">{{$key->code}}</span>
                                    </td>
                                    <td>
										@if ($key->client->client_managers->count())
										<a href="/commercial/salesman/list?code={{$key->client->client_managers[0]->salesman->code}}" target="_blank" style="color: #428bca;">
                                            {{$key->client->client_managers[0]->salesman->short_name}} <br>{{$key->client->client_managers[0]->salesman->office}}
                                        </a>
										@endif
									</td>
									<td>
										<a href="/commercial/salesman/list?code={{$key->salesman->code}}" target="_blank" style="color: #428bca;">
                                            {{$key->salesman->short_name}}
                                        </a>    
									</td>
                                    <td>
										<a href="/commercial/client/list?code={{$key->client->code}}" target="_blank" style="color: #428bca;">
                                            {{$key->client->company_name}}
                                        </a>
									</td>
                                    <td>{{$key->months}}</td>
                                    <td>{{number_format($key->programationVersion->version, 2)}}</td>
                                    <td>
                                        @if ($key->is_cancelled == 1)
                                            <span class="label label-danger">Cancelado</span>
                                        @elseif ($key->programationVersion->is_approv == 1)
                                            <span data-toggle="tooltip" title="" data-original-title="{{$key->programationVersion->description}}" class="label label-success">Aprovado <i class="ti ti-help-alt alt-question"></i></span>
                                        @elseif ($key->programationVersion->is_reprov == 1)
                                            <span data-toggle="tooltip" title="" data-original-title="{{$key->programationVersion->description}}" class="label label-danger">Reprovado <i class="ti ti-help-alt alt-question"></i></span>
                                        @elseif ($key->has_analyze == 1)
                                            <span class="label label-warning">Em análise</span>
                                        @else
                                            <span class="label label-secondary">Não enviado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                            <option></option>
                                            @if ($key->is_cancelled == 0)
                                                <option value="1">Cancelar</option>
                                            @endif
												<option value="3">Hist. Análises</option>
												<option value="2">Visualizar</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pull-right" style="margin-top: 20px;">
                        <ul class="pagination">
                            <?= $programations->appends(getSessionFilters()[2]->toArray())->links(); ?>
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
                                <label for="manager">Gestor</label>
                                <select name="manager" class="form-control">
                                    <option value=""></option>
                                    @foreach ($managers as $key)
                                        <option value="{{$key->id}}" @if(Session::get('filter_manager') == $key->id) selected @endif
                                        >{{$key->first_name}} {{$key->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="subordinates">Vendedores</label>
                                <select name="subordinates" class="form-control">
                                    <option value=""></option>
                                    @foreach ($subordinates as $key)
                                        <option value="{{$key->id}}">{{$key->short_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 form-group">
								<label for="client">Cliente</label>
								<select name="client" class="form-control select2-client" style="width: 100%;" multiple></select>
							</div>
                            <div class="col-sm-12 form-group">
                                <label for="start_date">Data da programação</label>
                                <input type="text" name="start_date" value="" class="form-control myear" />
                            </div>
							<div class="col-sm-12 form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value=""></option>
                                    <option value="1">Cancelado</option>
                                    <option value="2">Aprovado</option>
                                    <option value="3">Reprovado</option>
                                    <option value="4">Em análise</option>
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

	<div id="modal_orders" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 70%; margin: 50px auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Pedidos - programação (<span id="code_prog"></span>)</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="load_orders"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('gree_commercial.components.timeline_analyze', ['url' => '/commercial/programation/timeline/'])

    <script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        function action($this = '') {

            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                statusProgramation(json.id);
            } else if ($($this).val() == 2) {
                window.location.href = '/commercial/programation/view/'+json.id;
            } else if ($($this).val() == 3) {
                analyzeTimeline(json.id);
            }
            $($this).val('');
        }

        function statusProgramation(id) {
            bootbox.dialog({
                message: "Se você continuar, você irá alterar os status da programação. A programação será cancelada no sistema e todos os pedidos envolvidos em análise e esperando confirmação, serão cancelados. Os pedidos aprovados e faturados, precisarão ser cancelados manualmente. <br> <br> <input class='form-control input-lg' type='text' id='cancel_reason' placeholder='Motivo do cancelamento...'>",
                title: "Alterar status da programação",
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
							if ($('#cancel_reason').val() == '')
                                $error('É necessário informar o motivo do cancelamento.')
							
                            block();
                            window.location.href = '/commercial/programation/status/'+id+'?reason='+$('#cancel_reason').val();
                        }
                    }
                }
            });
        }
		
		function showOrders($this) {

            var json = JSON.parse($($this).attr('data-json'));
            var code = $($this).attr('data-code');

            $("#code_prog").html(code);
            $("#load_orders").html(loadOrders(json));            
            $("#modal_orders").modal('show');
        } 
		
		function loadOrders(object) {

            var html = '';
            html += '<ul class="ext-tabs">';

            for (var i = 0; i < object.length; i++) {

                var active = i == 0 ? 'active' : '';
                html += '    <li class="header-tab '+active+'">';
                html += '        <a href="javascript:void(0)" class="btn-tab" data-tab="tab'+i+'">'+ getMonth(object[i].y_month) +'</a>';
                html += '    </li>';
            }

            html += '</ul>';
            html += '<div class="tab-content">';

            for (var i = 0; i < object.length; i++) {
                
                var row = object[i];
                var active = i == 0 ? 'active' : '';

                html += '<div id="tab'+i+'" class="tab-pane '+active+'">';
                html += '<div class="spacer-20"></div>';
                html += '   <table class="table table-bordered table-striped table-condensed" style="text-align: center;">';
                html += '       <thead>';
                html += '           <tr>';
                html += '               <th class="th-order">Código</th>';
                html += '               <th class="th-order">Cliente</th>';
                html += '               <th class="th-order">Solicitante</th>';
                html += '               <th class="th-order">Status</th>';
                html += '               <th class="th-order">Criação</th>';
                html += '           </tr>';
                html += '       </thead>';
                html += '       <tbody>';

                if(row.order_sales.length > 0) {    
                    for (var j = 0; j < row.order_sales.length; j++) {
                        var order = row.order_sales[j];
                        html += '<tr>';
                        html += '    <td><a href="/commercial/order/all?code_order='+order.code+'" target="_blank" style="color:#428bca;">'+ order.code +'</a></td>';
                        html += '    <td><a href="/commercial/client/list?code='+order.code_client+'" target="_blank" style="color:#428bca;">'+ order.client_shop +' ('+ order.code_client +')</a></td>';
                        html += '    <td>'+ order.salesman.short_name +'</td>';
                        html += '    <td>'+ getStatus(order) +'</td>';
                        html += '    <td>'+ new Date(order.created_at).toLocaleString() +'</td>';
                        html += '</tr>';                        
                    }
                } else {
                    html += '<tr>';
                    html += '    <td colspan="5" style="text-align: center;">Não há pedidos neste mês</td>';
                    html += '</tr>';    
                }
                html += '       </tbody>';
                html += '   </table>';
                html += '</div>';
            }
            html += '</div>';

            return html;
        }
		
		function getStatus(order) {
            var status = '';

            if (order.is_cancelled == 1) {
                status += 'Cancelado';
            } else if (order.salesman_imdt_approv == 1 && order.commercial_is_approv == 1 && order.financy_approv == 1 && order.is_invoice == 0) {
                status += 'Aprovado';
            } else if (order.is_invoice == 1) {
                status += 'Faturado';
            } else if (order.salesman_imdt_reprov == 1) {
                status += 'Reprovado pelo gestor';
            } else if (order.commercial_is_reprov == 1) {
                status += 'Reprovado pelo diretor comercial';
            } else if (order.financy_reprov == 1) {
                status += 'Reprovado pelo diretor financeiro';
            } else if (order.waiting_assign == 1) {
                status += 'Aguardando Comprovação';
            } else if (order.has_analyze == 1) {
                status += 'Em análise';
            } else {
                status += 'Não enviado';
            }

            return status;
        }
		
		function getMonth(month) {

            var arr_month = [ 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
            split_month = month.split("-");
            num_month = parseInt(split_month[1]) - 1;

            return arr_month[num_month] + ' - ' + split_month[0];
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
			
            $("#filterNow").click(function (e) {
                $("#filterModal").modal('toggle');
                block();
                $("#filterData").submit();
            });

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
            $("#programation").addClass('menu-open');
            $("#programationAll").addClass('page-arrow active-page');
        });
		
		$(document).on('click',".btn-tab",function() {
            $(".header-tab, .tab-pane").removeClass('active');
            $("#"+$(this).attr('data-tab')+"").addClass('active');
        });
    </script>

@endsection
