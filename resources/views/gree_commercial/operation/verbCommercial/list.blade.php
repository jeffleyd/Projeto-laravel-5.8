@extends('gree_commercial.layout')

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/commercial/dashboard">Home</a></li>
        <li class="active">Todos verbas comerciais</li>
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
		.swal2-popup {
			font-size: 1.4rem !important;
		}
    </style>
    <link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
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
                            @foreach ($verb as $key)
                                <tr>
                                    <td>{{$key->code}}</td>
                                    <td>
                                        <a href="/commercial/salesman/list?code={{$key->salesman->code}}" target="_blank" style="color: #428bca;">
											<b>Representante</b><br>
											{{$key->salesman->short_name}}
										</a>	
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
										@elseif ($key->is_approv == 1)
											<span class="label label-success">Aprovado (Comercial)</span>
                                        @elseif ($key->is_reprov == 1)
                                            <span data-toggle="tooltip" title="" data-original-title="Reprovado" class="label label-danger">Reprovado <i class="ti ti-help-alt alt-question"></i></span>
                                        @elseif ($key->waiting_assign == 1)
                                            <span data-toggle="tooltip" title="" data-original-title="Você precisa comprovar a solicitação do pedido programado." class="label label-info">Aguardando Comprovação <i class="ti ti-help-alt alt-question"></i></span>
                                        @elseif ($key->has_analyze == 1)
                                            <span class="label label-warning">Em análise</span>
                                        @else
                                            <span class="label label-info">Não enviado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                            <option></option>
                                            <option value="2">Hist. Análises</option>
											<option value="3">Imprimir</option>
                                            @if ($key->type_payment == 2)
											<option value="7">Imprimir Nota de crédito</option>
											@elseif ($key->type_payment == 3)
											<option value="8">Imprimir solic. pagamento</option>
                                            @endif
											@if ($key->budget_commercial_report)
											<option value="9">Baixar Apuração</option>
											@endif
											@if (!$key->is_cancelled and ($key->has_analyze or !$key->is_approv))
                                                <option value="6">Cancelar</option>
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
                            <?= $verb->appends([
                                'code' => Request::get('code'),
                                'subordinates' => Request::get('subordinates'),
                                'client' => Request::get('client'),
                                'start_date' => Request::get('start_date'),
                                'end_date' => Request::get('end_date'),
								'status' => Request::get('status')
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
                    <form action="{{Request::url()}}" id="exportData">
						<input type="hidden" name="export" value="1">
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
                                    <option value="3">Aguardando Comprovação</option>
                                    <option value="4">Em análise</option>
                                </select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="salesman_id">Vendedores</label>
								<select name="salesman_id" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
                            </div>
							<div class="col-sm-12 form-group">
                                <label for="client">Cliente</label>
                                <select name="client" class="form-control select2-client" style="width: 100%;" multiple></select>
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
                                <label for="code">Código</label>
                                <input type="text" name="code" value="" class="form-control" />
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="subordinates">Vendedores</label>
                                <select name="subordinates" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="client">Cliente</label>
                                <select name="client" class="form-control select2-client" style="width: 100%;" multiple></select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="start_date">Data inicial</label>
                                <input type="text" name="start_date" value="" class="form-control myear" />
                            </div>
							<div class="col-sm-12 form-group">
                                <label for="end_date">Data final</label>
                                <input type="text" name="end_date" value="" class="form-control myear" />
                            </div>
							<div class="col-sm-12 form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option></option>
                                    <option value="1">Cancelado</option>
                                    <option value="2">Aprovado</option>
                                    <option value="3">Aguardando Comprovação</option>
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


	<form action="/commercial/sales/budget/cancel" method="post" id="form_request_cancel">
		<input type="hidden" name="id" id="cancel_id">
		<input type="hidden" name="cancel_reason" id="cancel_reason">
	</form>

	@include('gree_commercial.components.analyze.history.view')
    
	<script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
	@include('gree_commercial.components.analyze.history.script')
	<script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        var orderid;
        function action($this = '') {
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 2) {
                rtd_analyzes(json.id, "App\\Model\\Commercial\\BudgetCommercial");
            } else if ($($this).val() == 3) {
				window.open('/commercial/sales/budget/print/'+json.id, '_blank');
			} else if ($($this).val() == 6) {
                Swal.fire({
                    title: 'Cancelar Solicitação',
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success mb-1',
                    cancelButtonClass: 'btn btn-danger mb-1',
                    html: 'Deseja confirmar o cancelamento desta solicitação?'+
                        '<textarea id="swal_cancel_reason" class="swal2-textarea" style="height: 4.75em;" placeholder="Informe o motivo do cancelamento"></textarea>',
                    preConfirm: () => {
                        if($("#swal_cancel_reason").val() == "") {
                            swal.showValidationError(
                                'Obrigatório motivo do cancelamento'
                            );
                        }
                    }
                }).then(function (result) {
                    if (result.value) {
                        $("#cancel_id").val(json.id);
                        $("#cancel_reason").val($("#swal_cancel_reason").val());
                        block();
                        $("#form_request_cancel").submit();
                    }
                });
            } else if ($($this).val() == 7) {
                window.open('/commercial/sales/budget/credit/print/'+json.id, '_blank');
            } else if ($($this).val() == 8) {
                window.open('/commercial/sales/budget/payment/print/'+json.id, '_blank');
            } else if ($($this).val() == 9) {
                window.open(json.budget_commercial_report.report_file_url, '_blank');
            }
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
            format: "yyyy-mm-dd"
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

            $("#operation").addClass('menu-open');
			$("#reportInvoice").addClass('menu-open');
			$("#budgetCommercialAll").addClass('page-arrow active-page');
        });
    </script>

@endsection
