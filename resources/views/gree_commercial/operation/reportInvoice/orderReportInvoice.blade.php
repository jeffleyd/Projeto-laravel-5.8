@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Operacional</a></li>
    <li class="active">Apuração de faturamento</li>
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
                    <a class="btn btn-success" href="#" data-toggle="modal" data-target="#createReportModal">
                        <i class="fa fa-calendar" style="color: #ffffff;"></i>&nbsp; Criar apuração
                    </a>
                </div>
            </div>
        </div><!-- End .inner-padding -->
    </header>
    <div class="window">
    <div class="inner-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="table-wrapper">
                    <header>
                        <h3>Solicitações de apurações</h3>
                    </header>
                    <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                        <thead>
                        <tr>
                            <th scope="col" data-rt-column="Código">Código</th>
                            <th scope="col" data-rt-column="Solicitação de verba">Solicitação de verba</th>
                            <th scope="col" data-rt-column="Colaborador">Colaborador</th>
                            <th scope="col" data-rt-column="Cliente">Cliente</th>
                            <th scope="col" data-rt-column="Tipo de verba">Tipo de verba</th>
                            <th scope="col" data-rt-column="Criado em">Criado em</th>
                            <th scope="col" data-rt-column="Status">Status</th>
                            <th scope="col" data-rt-column="Ações">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($reportInvoices as $key)
                            <tr>
                                <td>CAF-{{$key->id}}</td>
                                <td>
                                    @if ($key->BudgetCommercial)
                                        <a href="/commercial/operation/report/invoice/print/{{$key->BudgetCommercial->view}}" target="_blank" style="color: #428bca;">
                                            {{$key->BudgetCommercial->code}}
                                        </a>
                                    @else
                                        <a href="javascript:void(0)">
                                            #NA
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    {{$key->user->short_name}} ({{$key->user->r_code}})
                                </td>
                                <td>
                                    <a href="#" target="_blank" style="color: #428bca;">
                                        {{$key->client_group_name}}
                                    </a>
                                </td>
                                <td>
                                    @if ($key->type_report == 1)
                                        VPC
                                    @else
                                        REBATE
                                    @endif
                                </td>
                                <td>
                                    {{date('d/m/Y', strtotime($key->created_at))}}
                                </td>
                                <td>
                                    @if ($key->status == 1)
                                        <span class="label label-warning">Aguardando Liberação</span>
                                    @elseif ($key->status == 3)
                                        <span class="label label-info">Processando</span>
                                    @elseif ($key->status == 4)
                                        <span class="label label-danger">Cancelado</span>
                                    @else
                                        <span class="label label-success">Liberado</span>
                                    @endif
                                </td>
                                <td>
                                    <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                        <option></option>
                                        @if ($key->status < 3)
                                        <option value="1">Atualizar</option>
                                        <option value="2">Baixar relatório</option>
                                        <option value="3">Deletar</option>
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
                        <?= $reportInvoices->appends(getSessionFilters()[0]->toArray())->links(); ?>
                    </ul>
                </div>
                <div class="spacer-50"></div>
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
                                <label for="code_s_verba">Código Solic. Verba</label>
                                <input type="number" name="code_s_verba" value="" class="form-control" />
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="client">Cliente</label>
                                <select name="client" class="form-control select2-client" style="width: 100%;" multiple></select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="type_report">Tipo de verba</label>
                                <select name="type_report" class="form-control">
                                    <option></option>
                                    <option value="1">VPC</option>
                                    <option value="2">Rebate</option>
                                </select>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="start_date">Data inicial</label>
                                <input type="text" name="start_date" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="end_date">Data final</label>
                                <input type="text" name="end_date" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option></option>
                                    <option value="1">Aguardando liberação</option>
                                    <option value="2">Liberado</option>
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

    <div id="createReportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Criar apuração</h4>
                </div>
                <div class="modal-body">
                    <form action="/commercial/operation/report/invoice/new" method="POST" id="exportData">
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="salesman_id">Grupo dos clientes</label>
								<select name="client_group_id" class="form-control client_group_dropdown" style="width: 100%;" multiple></select>
                            </div>
							<div class="col-sm-12 form-group">
                                <label for="salesman_id">Vendedores</label>
								<select name="salesman_id" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="type_report">Tipo de apuração</label>
                                <select name="type_report" id="type_report" class="form-control">
                                    <option value="1">VPC</option>
                                    <option value="2">REBATE</option>
                                </select>
                            </div>
                            <div class="col-sm-12 form-group group" style="display: none">
                                <label for="group">Grupo</label>
                                <select id="group" class="form-control js-select2" name="group" style="width:100%" multiple>
                                </select>
                            </div>
                            <div class="col-sm-6 form-group tax" style="display: none">
                                <label for="tax_rebate">Taxa de rebate</label>
                                <input type="text" name="tax_rebate" value="" class="form-control" />
                            </div>
                            <div class="col-sm-6 form-group goal" style="display: none">
                                <label for="goal">Meta</label>
                                <input type="number" name="goal" value="" class="form-control" />
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="start_date">Data inicial</label>
                                <input type="text" name="start_date" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="end_date">Data final</label>
                                <input type="text" name="end_date" value="" class="form-control myear" />
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
                        <span style="position: relative;top: 25px;">Criar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title editTitle">Atualizar status</h4>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="/commercial/operation/report/invoice/update" enctype="multipart/form-data">
                        <input type="hidden" id="id" name="id" value="0">
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Código</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="code" name="code" readonly="readonly" value="" class="form-control" />
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Status</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="form-control" id="status" name="status">
                                    <option value="1">Aguardando liberação</option>
                                    <option value="2">Liberado</option>
                                </select>
                            </div>
                        </div>
                    </form>
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
        format: "yyyy-mm-dd",
    });

    $('#type_report').on('change', function () {
        if ($('#type_report').val() == 1) {
            $('.group').hide();
            $('.tax').hide();
            $('.goal').hide();
        } else {
            $('.group').show();
            $('.tax').show();
            $('.goal').show();
        }
    });

    $("#exportNow").click(function() {
        block();
        $("#exportData").submit();
    });

    $("#filterNow").click(function() {
        block();
        $("#filterData").submit();
    });

    $("#editSave").click(function() {
        block();
        $("#editForm").submit();
    });

    function action($this = '') {
        $('#editForm').each (function(){
            this.reset();
        });

        var json = JSON.parse($($this).attr('json-data'));

        if ($($this).val() == 1) {

            $("#id").val(json.id);
            $("#code").val('CAF-'+json.id);
            $("#status").val(json.status);

            $(".editTitle").html('Editando apuração: CAF-'+json.id);
            $("#editModal").modal();
        } else if ($($this).val() == 2) {

            window.open(json.report_file_url, '_blank');
        }else if ($($this).val() == 3) {

            bootbox.dialog({
                message: "Você realmente quer deletar apuração CAF-"+json.id+" ?",
                title: "Deletar grupo",
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
                            window.location.href = '/commercial/operation/report/invoice/delete/'+json.id;
                        }
                    }
                }
            });
        }

        $($this).val('');

    }

    $(document).ready(function () {

        $('input[name="tax_rebate"]').mask('00.00', {reverse: true});
        $(".js-select2").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {

                    return 'Grupo não existe...';
                }
            },
            ajax: {
                url: '/commercial/product/group/dropdown',
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
		
		$(".client_group_dropdown").select2({
                language: {
                    noResults: function () {
                        return 'Grupo do cliente não existe!';
                    }
                },
                ajax: {
                    url: '/commercial/client/group/dropdown',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;

                    },
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
        $("#operation").addClass('menu-open');
        $("#reportInvoice").addClass('menu-open');
        $("#reportInvoiceAll").addClass('page-arrow active-page');
    });
</script>

@endsection
