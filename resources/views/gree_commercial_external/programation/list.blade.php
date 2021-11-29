@extends('gree_commercial_external.layout')

@section('page-css')
    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endsection
@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Lista de programações</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#filterModal"><i class="fa fa-filter"></i> Filtrar</button>
                <a class="btn btn-info d-none d-lg-block m-l-15" onclick="action()" href="#">
                    <i class="fa fa-plus-circle"></i> Nova programação
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <style>
        .alt-question {
            position: relative;
            top: 0px;
            left: 2px;
            font-size: 9px;
        }
    </style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="background-color: #03a9f3;color: #fff;">
                                <th>Código</th>
                                <th>Gestor</th>
                                <th>Cliente</th>
                                <th>Programado para</th>
                                <th>Versão</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programations as $key)
                                <tr>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-default btn-circle btn-xs" onclick="showOrders(this);" data-json='<?= htmlspecialchars(json_encode($key->programationMonth), ENT_QUOTES, "UTF-8") ?>' data-code='{{$key->code}}'
                                            data-toggle="tooltip" data-placement="left" data-original-title="Pedidos">
                                           <i class="fa fa-file-text-o" style="position: relative;font-size: 14px;top: 4px;left:8px"></i>
                                        </a>
                                        {{$key->code}}
                                    </td>
                                    <td>@if ($key->client->client_managers->count()) {{$key->client->client_managers[0]->salesman->short_name}} <br>{{$key->client->client_managers[0]->salesman->office}} @endif</td>
                                    <td>
										<a href="/comercial/operacao/cliente/todos?code={{$key->client->code}}" target="_blank" style="color: #428bca;">
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
                                            @if ($key->has_analyze == 0 and $key->is_cancelled == 0)
                                            <option value="1">Editar</option>
                                            @endif
                                            <option value="2">Visualizar</option>
											<option value="3">Hist. Análises</option>
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
            </div>
        </div>
    </div>
</div>

<div id="filterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Filtrar Programações</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="filterData">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="code">Código</label>
                            <input type="text" name="code" value="" class="form-control" />
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
                            <label for="client">Cliente</label>
                            <select name="client" class="form-control">
                                <option value=""></option>
                                @foreach ($clients as $key)
                                    <option value="{{$key->id}}">{{$key->company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="start_date">Data da programação</label>
                            <input type="text" name="start_date" value="" class="form-control myear" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" id="filterNow" class="btn btn-success pull-right">Filtrar</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_orders" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pedidos - programação (<span id="code_prog"></span>)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="load_orders"></div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('page-scripts')

	@include('gree_commercial_external.components.timeline_analyze', ['url' => '/comercial/operacao/programation/timeline/'])
    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">

        function action($this = '') {

            if ($this == '') {
                window.location.href = '/comercial/operacao/programation/new';
            }
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                window.location.href = '/comercial/operacao/programation/edit/'+json.id;
            } else if ($($this).val() == 2) {
                window.location.href = '/comercial/operacao/programation/view/'+json.id;
            } else if ($($this).val() == 3) {
                analyzeTimeline(json.id);
            }
            $($this).val('');
        }

        function acPicker() {
            $.fn.datepicker.dates['en'] = {
                days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
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
            
            html += '<ul class="nav nav-tabs" role="tablist">';

            for (var i = 0; i < object.length; i++) {

                var active = i == 0 ? 'active' : '';
                html += '    <li class="nav-item header-tab">';
                html += '        <a href="javascript:void(0)" class="nav-link btn-tab '+active+'" data-tab="tab'+i+'" data-toggle="tab">'+ getMonth(object[i].y_month) +'</a>';
                html += '    </li>';
            }

            html += '</ul>';
            html += '<div class="tab-content tabcontent-border">';

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
                        html += '    <td><a href="/comercial/operacao/order/all?code_order='+order.code+'" target="_blank" style="color:#428bca;">'+ order.code +'</a></td>';
                        html += '    <td><a href="/comercial/operacao/cliente/todos?code='+order.code_client+'" target="_blank" style="color:#428bca;">'+ order.client_shop +' ('+ order.code_client +')</a></td>';
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
			
			console.log(num_month);

            return arr_month[num_month] + ' - ' + split_month[0];
        }

        $(document).ready(function () {

            acPicker();
            $("#filterNow").click(function (e) {
                $("#filterModal").modal('toggle');
                block();
                $("#filterData").submit();
            });
        });
		
		$(document).on('click',".btn-tab",function() {
            $(".header-tab, .tab-pane").removeClass('active');
            $("#"+$(this).attr('data-tab')+"").addClass('active');
        });

        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    </script>

@endsection
