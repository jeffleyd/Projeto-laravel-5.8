@extends('gree_commercial.layout')

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/commercial/dashboard">Home</a></li>
        <li class="active">Todos pedidos</li>
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
    </style>
    <link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <header id="header-sec">
        <div class="inner-padding">
            <div class="pull-left">
                <div class="btn-group">
                    <a class="btn btn-default" onclick="action()" href="#">
                        <i class="fa fa-plus"></i>&nbsp; Criar novo
                    </a>
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
        <div class="inner-padding">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-wrapper">
                        <header>
                            <h3>Pedidos</h3>
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
											@if ($key->waiting_assign == 1 and $key->manual_order_sales)
												<option value="1">Enviar comprovação</option>
                                            @endif
                                            @if ($key->waiting_assign == 0)
												<option value="6">Imprimir</option>
                                            @endif
                                            <option value="4">Imprimir P/ Cliente</option>
											@if ($key->waiting_assign == 0)
											<option value="2">Imprimir comprovações</option>
											@endif
                                            <option value="5">Hist. Análises</option>
											<option value="7">Condição comercial</option>
											@if ($key->is_invoice == 0 and $key->is_cancelled == 0)
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
                                'code_order' => Session::get('confirmed_code_order'),
                                'subordinates' => Session::get('confirmed_subordinates'),
                                'users' => Session::get('confirmed_users'),
                                'client' => Session::get('confirmed_client'),
                                'start_date' => Session::get('confirmed_start_date'),
                                'is_analyze' => Session::get('confirmed_is_analyze'),
                                'region' => Session::get('confirmed_region'),
								'chart_start_date' => Session::get('confirmed_chart_start_date'),
								'status' => Session::get('confirmed_status')
                            ])->links(); ?>
                        </ul>
                    </div>
                    <div class="spacer-50"></div>
                </div>
            </div>

        </div>
        <!-- End .inner-padding -->
    </div>

    <div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Exportar dados</h4>
                </div>
                <div class="modal-body">
                    <form action="/commercial/order/export" id="exportData">
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
                            <div class="col-sm-12 form-group">
                                <label for="salesman_id">Vendedores</label>
                                <select name="salesman_id" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach ($subordinates as $key)
                                        <option value="{{$key->id}}">{{$key->short_name}}</option>
                                    @endforeach
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
                    <div  id="exportNow" style="float: right;width: 178px;text-align: center;position: relative;height:76px;font-weight: bold;color: #18b202;cursor: pointer;font-size: 16px;">
                        <span style="position: relative;top: 25px;">Exportar</span>
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

    <div id="receiverAssignModal" class="modal" tabindex="1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Enviar Compravação</h4>
                </div>
                <form action="/commercial/order/proof" style="background: white;" method="POST" id="formSign">
                    <input type="hidden" name="order_id" id="order_id">
                <div class="modal-body">
                    Para prosseguir com o seu pedido, você precisa comprovar a solicitação, podendo
                    pegar assinatura do cliente, email com cliente confirmando, ordem de serviço do cliente...
                    <div class="row">
                        <div class="col-sm-12" style="margin-top: 25px;margin-bottom: 25px;">
                            <div class="input-group">
                                <input class="form-control" type="file" name="order_file">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" onclick="sendFileOrder();" type="button"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-12" style="margin-bottom: 30px;">
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
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary"> Sair</button>
                    <button type="button" onclick="sendAssign()" class="btn btn-success"> <i class="fa fa-check"></i> Concluir</button>
                </div>
                </form>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

	<div id="ModalTablePrice" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">CONDIÇÃO COMERCIAL</h4>
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
					<div class="clear"></div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	 </div>

    @include('gree_commercial.components.timeline_analyze', ['url' => '/commercial/order/timeline/'])
    <script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="/commercial/salesmanTablePrice.js"></script>
    <script>
        var orderid;
        function action($this = '') {

            if ($this == '') {
                window.location.href = '/commercial/order/confirmed/new';
            }
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 2) {
				window.open(json.url_view_proof, '_blank');
            } else if ($($this).val() == 6) {
				window.open('/commercial/order/confirmed/print/view/'+json.id, '_blank');	   
			} else if ($($this).val() == 1) {
                $('input[name="order_file"]').val('');
                $('#order_id').val(json.id);
                $('#receiverAssignModal').modal();
                reloadFiles(json.order_sales_attach);
                orderid = json.id;
            } else if ($($this).val() == 3) {
                cancelOrder(json.id);
            } else if ($($this).val() == 4) {
                window.open('/commercial/order/confirmed/print/view/'+json.id+'?chide=true', '_blank');
            } else if ($($this).val() == 5) {
                analyzeTimeline(json.id);
            } else if ($($this).val() == 7) {
				$('#loadadjusts').html('');
                if (json.adjust_month) {
                    var arr_months = JSON.parse(json.adjust_month);
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
				var table = commercialTablePriceConvertValue(JSON.parse(json.json_table_price));
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
                list += '<td><i class="fa fa-trash-o" style="cursor: pointer" onclick="removeFile('+item.id+')"></i></td>';
                list += '</tr>';
            });

            $('.listfiles').html(list);
        }

        function removeFile(id) {
            block();
            ajaxSend('/commercial/order/proof/remove', {order_id: orderid, attach_id:id}, 'POST', '10000').then(function(result){
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
                ajaxSend('/commercial/order/proof/upload', {order_id: orderid}, 'POST', '10000', $('#formSign')).then(function(result){
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

        function sendAssign() {
            bootbox.dialog({
                message: "Seu pedido entrará no processo de análise! Deseja continuar?",
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
                            $('#formSign').submit();
                        }
                    }
                }
            });
        }

        function cancelOrder(id) {
            bootbox.dialog({
                message: "Para dar continuidade no cancelamento, por favor, informe o motivo do cancelamento. <br> <br> <input class='form-control input-lg' type='text' id='cancel_reason' placeholder='Motivo do cancelamento...'>",
                title: "Cancelar pedido",
                buttons: {
                    danger: {
                        label: "Cancelar",
                        className: "btn-default",
                        callback: function(){}
                    },
                    main: {
                        label: "Confirmar",
                        className: "btn-primary",
                        callback: function(result) {
                            if ($('#cancel_reason').val() == '')
                                $error('É necessário informar o motivo do cancelamento.')

                            block();
                            window.location.href = '/commercial/order/cancel/'+id+'?reason='+$('#cancel_reason').val();
                        }
                    }
                }
            });
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

            $('#identity').mask('00.000.000/0000-00', {reverse: false});
            $("#type_people").change(function () {
                var elem = $('#identity');
                if($(this).val() == 1) {
                    elem.mask('00.000.000/0000-00', {reverse: false});
                    elem.attr("placeholder", "00.000.000/0000-00");
                    elem.val('');
                } else {
                    elem.attr("placeholder", "Informe o RG");
                    elem.unmask();
                    elem.val('');
                }
            });

            $("#filterNow").click(function (e) {
                $("#filterModal").modal('toggle');
                block();
                $("#filterData").submit();
            });

            $("#exportNow").click(function (e) {
                $("#exportModal").modal('toggle');
                $("#exportData").submit();
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
            $("#orderConfirmed").addClass('menu-open');
            $("#orderConfirmedAll").addClass('page-arrow active-page');
        });
    </script>

@endsection
