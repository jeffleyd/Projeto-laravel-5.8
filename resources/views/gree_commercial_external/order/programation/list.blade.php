@extends('gree_commercial_external.layout')

@section('page-css')
    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endsection
@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Lista de pedidos programados</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#filterModal"><i class="fa fa-filter"></i> Filtrar</button>
                <a class="btn btn-info d-none d-lg-block m-l-15" onclick="action()" href="#">
                    <i class="fa fa-plus-circle"></i> Novo pedido programado
                </a>
				<a class="btn btn-success d-none d-lg-block m-l-15" href="#" data-toggle="modal" data-target="#exportModal"><i class="fa fa-download"></i> Exportar</a>
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
                                <th>Programação</th>
								<th>Mês do pedido</th>
                                <th>Cliente</th>
                                <th>Criado em</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $key)
                                <tr>
                                    <td>{{$key->code}}</td>
                                    <td>
                                        @if ($key->programationMonth)
                                        <b>Código:</b> {{$key->programationMonth->programation->code}}
                                        <br>{{$key->programationMonth->programation->months}}
                                        @endif
                                    </td>
									<td>
                                        @php $date = new \Carbon\Carbon($key->programationMonth->yearmonth); @endphp
                                        {{$date->locale('pt_BR')->isoFormat('MMMM')}} /
                                        {{$date->locale('pt_BR')->isoFormat('YYYY')}}
                                    </td>
                                    <td>
                                        @if ($key->programationMonth)
                                            <a href="/comercial/operacao/cliente/todos?code={{$key->programationMonth->programation->client->code}}" target="_blank" style="color: #428bca;">
                                                {{$key->programationMonth->programation->client->company_name}}
                                            </a>    
                                        @endif
                                    </td>
                                    <td>{{date('d/m/Y', strtotime($key->created_at))}}</td>
                                    <td>
                                        @if ($key->is_cancelled == 1)
                                            <span class="label label-danger">Cancelado</span>
										@elseif ($key->is_approv == 1 and $key->is_invoice == 0)
											<span class="label label-success">Aprovado (Comercial)</span>
										@elseif ($key->is_invoice == 1)
											<span class="label label-success">Faturado</span>
                                        @elseif ($key->salesman_imdt_reprov == 1)
                                            <span data-toggle="tooltip" title="" data-original-title="Reprovado pelo gestor" class="label label-danger">Reprovado <i class="ti ti-help-alt alt-question"></i></span>
                                        @elseif ($key->commercial_is_reprov == 1)
                                            <span data-toggle="tooltip" title="" data-original-title="Reprovado pelo diretor comercial" class="label label-danger">Reprovado <i class="ti ti-help-alt alt-question"></i></span>
                                        @elseif ($key->financy_reprov == 1)
                                            <span data-toggle="tooltip" title="" data-original-title="Reprovado pelo diretor financeiro" class="label label-danger">Reprovado <i class="ti ti-help-alt alt-question"></i></span>
                                        @elseif ($key->waiting_assign == 1)
                                            <span data-toggle="tooltip" title="" data-original-title="Você precisa comprovar a solicitação do pedido programado." class="label label-info">Aguardando Comprovação <i class="ti ti-help-alt alt-question"></i></span>
                                        @elseif ($key->has_analyze == 1)
                                            <span class="label label-warning">Em análise</span>
                                        @else
                                            <span class="label label-secondary">Não enviado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                            <option></option>
                                            @if ($key->waiting_assign == 1)
                                            <option value="1">Enviar Comprovação</option>
                                            @else
											<option value="6">Imprimir</option>
                                            @endif
                                            <option value="4">Imprimir P/ Cliente</option>
											@if ($key->waiting_assign == 0)
											<option value="2">Imprimir comprovações</option>
											@endif
                                            <option value="5">Hist. Análises</option>
											<option value="7">Condição comercial</option>
											@if ($key->is_invoice == 0 and $key->is_cancelled == 0 and $key->has_analyze == 1 or $key->waiting_assign == 1 and $key->is_cancelled == 0)
                                                <option value="3">Cancelar</option>
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
						<?= $order->appends([
                                'code_order' => Session::get('order_code_order'),
                                'code_programation' => Session::get('order_code_order'),
                                'client' => Session::get('order_client'),
                                'start_date' => Session::get('order_start_date'),
                                'is_analyze' => Session::get('order_is_analyze'),
                                'status' => Session::get('order_status')
                            ])->links(); 
                        ?>
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
                <h4 class="modal-title">Filtrar Pedido</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="filterData">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="code">Código do pedido</label>
                            <input type="text" name="code_order" value="" class="form-control" />
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="code">Código da programação</label>
                            <input type="text" name="code_programation" value="" class="form-control" />
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
                            <label for="start_date">Data</label>
                            <input type="text" name="start_date" value="" class="form-control myear" />
                        </div>
						<div class="col-sm-12 form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                <option></option>
                                <option value="1">Cancelado</option>
                                <option value="2">Aprovado</option>
                                <option value="3">Faturado</option>
                                <option value="4">Reprovado pelo gestor</option>
                                <option value="5">Reprovado pelo diretor comercial</option>
                                <option value="6">Reprovado pelo diretor financeiro</option>
                                <option value="7">Aguardando Comprovação</option>
                                <option value="8">Em análise</option>
                            </select>
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

<div id="receiverAssignModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enviar Comprovação</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="/comercial/operacao/order/proof" id="formSign" method="post">
                    <input type="hidden" name="order_id" id="order_id">
                    <div class="row">
                        <div class="col-sm-12">
                            <p>
                                Para prosseguir com o seu pedido programado, você precisa comprovar a solicitação, podendo
                                pegar assinatura do cliente, email do cliente confirmando, ordem de serviço do cliente...
                            </p>
                        </div>
                        <div class="col-sm-12 mb-4 mt-2 input-group">
                            <input type="file" name="order_file" value="" class="form-control" />
                            <div class="input-group-append">
                                <button class="btn btn-info" type="button" onclick="sendFileOrder();">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Arquivo</th>
                                        <th>Visualizar</th>
                                        <th class="text-nowrap">Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody class="listfiles">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" onclick="sendAssign()" class="btn btn-success pull-right">Enviar</button>
            </div>
        </div>
    </div>
</div>

<div id="ModalTablePrice" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">CONDIÇÃO COMERCIAL</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
				<ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#conditions" role="tab" aria-selected="true">Condições aplicadas</a></li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#adjusts" role="tab" aria-selected="false">Reajustes mensal</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="conditions" role="tabpanel">
                        <div class="table-wrapper">
                            <header>
                            </header>
                            <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                <thead>
                                <tr>
                                    <th scope="col" data-rt-column="Nome">Nome</th>
                                    <th scope="col" data-rt-column="Valor">Valor</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        É programado?
                                    </td>
                                    <td id="t_is_programmed">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Tipo de cliente
                                    </td>
                                    <td id="t_type_client">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        É suframa?
                                    </td>
                                    <td id="t_is_suframa">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Desconto Extra
                                    </td>
                                    <td id="t_descont_extra">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Carga completo
                                    </td>
                                    <td id="t_charge">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Contrato / VPC
                                    </td>
                                    <td id="t_contract_vpc">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Prazo médio
                                    </td>
                                    <td id="t_average_term">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        PIS / Confins
                                    </td>
                                    <td id="t_pis_confis">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Tipo de entrega
                                    </td>
                                    <td id="t_cif_fob">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ICMS
                                    </td>
                                    <td id="t_icms">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Ajuste comercial
                                    </td>
                                    <td id="t_adjust_commercial">
                                    </td>
                                </tr>
							<tr>
                                <td>
                                    Data da condição
                                </td>
                                <td id="t_date_condition">

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Observação da condição
                                </td>
                                <td id="t_description_condition">
                                </td>
                            </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane p-20" id="adjusts" role="tabpanel">
                        <div class="table-wrapper">
                            <header>
                            </header>
                            <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                <thead>
                                <tr>
                                    <th scope="col" data-rt-column="Tipo de aplicação">Tipo de aplicação</th>
                                    <th scope="col" data-rt-column="Porcentagem">Porcentagem</th>
                                </tr>
                                </thead>
                                <tbody id="loadadjusts">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Exportar pedidos programados</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="/comercial/operacao/programation/export" id="exportData">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="start_date">Data Inicial</label>
                            <input type="text" name="start_date" value="" class="form-control myear" autocomplete="off"/>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="start_date">Data final</label>
                            <input type="text" name="end_date" value="" class="form-control myear" autocomplete="off"/>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                <option></option>
                                <option value="1">Cancelado</option>
                                <option value="2">Aprovado</option>
                                <option value="3">Faturado</option>
                                <option value="4">Reprovado pelo gestor</option>
                                <option value="5">Reprovado pelo diretor comercial</option>
                                <option value="6">Reprovado pelo diretor financeiro</option>
                                <option value="7">Aguardando Comprovação</option>
                                <option value="8">Em análise</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" id="exportNow" class="btn btn-success pull-right">Exportar</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('page-scripts')

    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

    @include('gree_commercial_external.components.timeline_analyze', ['url' => '/comercial/operacao/order/timeline/'])

	<script src="/commercial/salesmanTablePrice.js"></script>
    <script type="text/javascript">
    var orderid;
        function action($this = '') {

            if ($this == '') {
                window.location.href = '/comercial/operacao/order/new';
            }
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 2) {
				window.open(json.url_view_proof, '_blank');
            } else if ($($this).val() == 6) {
                window.open('/comercial/operacao/order/print/view/'+json.id, '_blank');
            } else if ($($this).val() == 1) {
                $('input[name="order_file"]').val('');
                $('#order_id').val(json.id);
                $('#receiverAssignModal').modal();
                reloadFiles(json.order_sales_attach);
                orderid = json.id;
            } else if ($($this).val() == 3) {
                cancelOrder(json.id);
            } else if ($($this).val() == 4) {
                window.open('/comercial/operacao/order/print/view/'+json.id+'?chide=true', '_blank');
            } else if ($($this).val() == 5) {
                analyzeTimeline(json.id);
            } else if ($($this).val() == 7) {
				$('#loadadjusts').html('');
                if (json.programation_month.adjust_month) {
                    var arr_months = JSON.parse(json.programation_month.adjust_month);
                    var list = '';
                    arr_months.forEach(function ($val) {
                        list += `
                            <tr>
                                <td>${typeAdjuste($val.type_apply)}</td>
                                <td>${$val.factor}%</td>
                            </tr>
                        `;
                    });
                    $('#loadadjusts').html(list);
                }
				var table = commercialTablePriceConvertValue(JSON.parse(json.programation_month.json_table_price));
				$('#t_adjust_commercial').html(table.adjust_commercial);
				$('#t_average_term').html(table.average_term);
				$('#t_charge').html(table.charge);
				$('#t_cif_fob').html(table.cif_fob);
				$('#t_contract_vpc').html(table.contract_vpc);
				$('#t_descont_extra').html(table.descont_extra);
				$('#t_icms').html(table.icms);
				$('#t_is_programmed').html(table.is_programmed);
				$('#t_is_suframa').html(table.is_suframa);
				$('#t_pis_confis').html(table.pis_confis);
				$('#t_type_client').html(table.type_client);
				$('#t_date_condition').html(table.date_condition.replaceAll('-01', ''));
                $('#t_description_condition').html(table.description_condition);
				
				$('#ModalTablePrice').modal();
			}
            $($this).val('');
        }

        function reloadFiles(arr) {
            var list = '';
            arr.forEach(function(item){
                list += '<tr>';
                list += '<td>'+item.name.substring(0,10)+'...</td>';
                list += '<td><a target="_blank" href="'+item.url+'">Clique aqui</a></td>';
                list += '<td><i class="ti ti-trash" style="cursor: pointer" onclick="removeFile('+item.id+')"></i></td>';
                list += '</tr>';
            });

            $('.listfiles').html(list);
        }

        function removeFile(id) {
            block();
            ajaxSend('/comercial/operacao/order/proof/remove', {order_id: orderid, attach_id:id}, 'POST', '10000').then(function(result){
                unblock();
                reloadFiles(result.data);
            }).catch(function(err){
                unblock();
                $error(err.message)
            })
        }

        function sendFileOrder() {
            if($('input[name="order_file"]').val() != '') {
                block();
                ajaxSend('/comercial/operacao/order/proof/upload', {order_id: orderid}, 'POST', '10000', $('#formSign')).then(function(result){
                    unblock();
                    reloadFiles(result.data);
                    $('input[name="order_file"]').val('');
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                })

            } else {
                $error('Você precisa informar um arquivo para continuar.');
            }
        }
        function cancelOrder(id) {
            Swal.fire({
                title: 'Aviso importante',
                text: 'Se você continuar, você irá cancelar seu pedido programado. Seu pedido programado sairá do processo de análise e não poderá mais dar continuidade.',
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Pode continuar!',
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonText:
                    'Cancelar',
                showCancelButton: true,
				input: 'text',
            	inputPlaceholder: 'Digite o motivo...',
            }).then(function (result) {
                if ($('.swal2-input').val() != "") {
					block();
                    window.location.href = '/comercial/operacao/order/cancel/'+id+'?reason='+$('.swal2-input').val();
				} else {
					$error('Você precisa dizer o motivo do cancelamento.');
				}
            });
        }

        function sendAssign() {
            Swal.fire({
                title: 'Aviso importante',
                text: 'Seu pedido programado entrará no processo de análise! Deseja continuar?',
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Pode continuar!',
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonText:
                    'Cancelar',
                showCancelButton: true,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                        if (value) {
                            resolve();
                        }
                    });
                }
            }).then(function (result) {
                if (result.value) {
                    block();
                    $('#formSign').submit();
                }
            });
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

        $(document).ready(function () {
			
			$("#exportNow").click(function (e) {
                $("#exportModal").modal('toggle');
                $("#exportData").submit();
            });

            acPicker();
            $("#filterNow").click(function (e) {
                $("#filterModal").modal('toggle');
                block();
                $("#filterData").submit();
            });
        });

        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    </script>

@endsection
