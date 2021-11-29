@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="/commercial/client/list">Clientes</a></li>
    <li class="active">Todos</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
<style>
    .select-group {
        background-color: #eeeeee;
        border-color: #eeeeee;
        color: #555555;
        font-weight: 500;
    }
</style>

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
                        <h3>Clientes</h3>
                    </header>
                    <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                        <thead>
                            <tr>
                                <th scope="col" data-rt-column="Código">Código</th>
								<th scope="col" data-rt-column="Criado em">Cadastrado em</th>
                                <th scope="col" data-rt-column="Nome">Nome</th>
								<th scope="col" data-rt-column="Crédido aprovado">Crédido aprovado</th>
                                <th scope="col" data-rt-column="CNPJ/RG">CNPJ / RG</th>
                                <th scope="col" data-rt-column="Grupo">Grupo</th>
                                <th scope="col" data-rt-column="Status">Status</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($client as $key)
                            <tr>
                                <td>{{$key->code}}</td>
								<td>{{date('d/m/Y', strtotime($key->created_at))}}</td>
                                <td style="text-transform: uppercase;">{{$key->company_name}}</td>
								<td>R$ {{$key->financy_credit}}</td>
                                <td>{{$key->identity}}</td>
                                <td>{{$key->group->name}}</td>
                                <td>
                                    @if ($key->is_active == 0)
                                        <span class="label label-danger">Desativado</span>
                                    @elseif ($key->salesman_imdt_reprov == 1 or $key->revision_is_reprov == 1 or $key->judicial_is_reprov == 1 or $key->commercial_is_reprov == 1 or $key->financy_reprov == 1)
                                        <span class="label label-danger">Reprovado</span>
                                    @elseif ($key->salesman_imdt_approv == 1 and $key->revision_is_approv == 1 and $key->judicial_is_approv == 1 and $key->commercial_is_approv == 1 and $key->financy_approv == 1)
                                        <span class="label label-success">Aprovado</span>
										@if ($key->financy_status == 1)
											<br><span class="label label-danger">Reprovado pelo financeiro</span>
										@elseif ($key->financy_status == 2)
											<br><span class="label label-warning">Liberado antecipado</span>
										@elseif ($key->financy_status == 3)
											<br><span class="label label-success">Liberado antecipado & parcelado</span>
										@endif
                                    @elseif($key->has_analyze == 0)
                                        <span class="label label-info">Cadastrado</span>
										@if ($key->financy_status == 1)
											<br><span class="label label-danger">Reprovado pelo financeiro</span>
										@elseif ($key->financy_status == 2)
											<br><span class="label label-warning">Liberado antecipado</span>
										@elseif ($key->financy_status == 3)
											<br><span class="label label-success">Liberado antecipado & parcelado</span>
										@endif
                                    @else
                                        <span class="label label-warning">Em análise</span>
                                    @endif
                                </td>
                                <td>
                                    <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                        <option></option>
                                        <option value="1">Editar</option>
										<option value="2">Imprimir</option>
										<option value="3">Hist. Análise</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $client->appends([
                            'code' => Session::get('client_code'),
                            'name' => Session::get('client_name'),
                            'identity' => Session::get('client_identity'),
                            'status' => Session::get('client_status'),
                            'is_analyze' => Session::get('client_is_analyze'),
                            'region' => Session::get('client_region'),
                            'status_chart' => Session::get('client_status_chart'),
                        ])->links(); ?>
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
                        <div class="col-sm-12">
                            <label for="name">Código</label>
                            <input type="text" name="code" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="name">Nome fantasia</label>
                            <input type="text" name="name" value="" class="form-control" />
                        </div>
                    </div>
					<div class="row">
                        <div class="col-sm-12">
                            <label for="reason_social">Razão social</label>
                            <input type="text" name="reason_social" value="" class="form-control" />
                        </div>
                    </div>
					<div class="row">
						<div class="col-sm-12 form-group">
							<label for="salesman_id">Vendedores</label>
							<select name="salesman_id" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
						</div>
					</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>CNPJ / RG</label>
                            <div class="input-group input-group">
                                <span class="input-group-addon">
                                    <select id="type_people" class="select-group">
                                        <option value="1">CNPJ</option>
                                        <option value="2">RG</option>
                                    </select>
                                </span>
                                <input type="text" class="form-control" name="identity" id="identity" value="" placeholder="00.000.000/0000-00" required/>
                            </div>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('client_status') == 1) selected="selected" @endif>Ativo</option>
                                <option value="2" @if (Session::get('client_status') == 2) selected="selected" @endif>Desativado</option>
								<option value="3" @if (Session::get('client_status') == 3) selected="selected" @endif>Reprovado</option>
                                <option value="4" @if (Session::get('client_status') == 4) selected="selected" @endif>Aprovado / Reprovado pelo financeiro</option>
                                <option value="5" @if (Session::get('client_status') == 5) selected="selected" @endif>Aprovado / Liberado antecipado</option>
                                <option value="6" @if (Session::get('client_status') == 6) selected="selected" @endif>Aprovado / Liberado antecipado & parcelado</option>
                                <option value="7" @if (Session::get('client_status') == 7) selected="selected" @endif>Cadastrado / Reprovado pelo financeiro</option>
                                <option value="8" @if (Session::get('client_status') == 8) selected="selected" @endif>Cadastrado / Liberado antecipado</option>
                                <option value="9" @if (Session::get('client_status') == 9) selected="selected" @endif>Cadastrado / Liberado antecipado & parcelado</option>
                                <option value="10" @if (Session::get('client_status') == 10) selected="selected" @endif>Em análise</option>
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

<div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Exportar dados</h4>
            </div>
            <div class="modal-body">
                <form action="/commercial/client/list" id="form_client_export">
                    <input type="hidden" name="export" value="1">
                    <div class="row">
						<div class="col-sm-12 form-group">
                            <label for="year">Data inicial</label>
                            <input name="start_date" class="form-control myear" autocomplete="off">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="year">Data final</label>
                            <input name="end_date" class="form-control myear" autocomplete="off">
                        </div>
                        <!--<div class="col-sm-12 form-group">
                            <label for="year">Ano</label>
                            <input name="year" placeholder="2021" value="2021" class="form-control">
                        </div>-->
                        <div class="col-sm-12 form-group">
                            <label for="subordinates">Vendedores</label>
                            <select name="subordinates" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="group_id">Grupos</label>
                            <select name="client_group" class="form-control client_group_dropdown" style="width: 100%;" multiple></select>
                        </div>
						<div class="col-sm-12 form-group">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1">Ativo</option>
                                <option value="2">Desativado</option>
								<option value="3">Reprovado</option>
                                <option value="4">Aprovado / Reprovado pelo financeiro</option>
                                <option value="5">Aprovado / Liberado antecipado</option>
                                <option value="6">Aprovado / Liberado antecipado & parcelado</option>
                                <option value="7">Cadastrado / Reprovado pelo financeiro</option>
                                <option value="8">Cadastrado / Liberado antecipado</option>
                                <option value="9">Cadastrado / Liberado antecipado & parcelado</option>
                                <option value="10">Em análise</option>
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

<script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>	
@include('gree_commercial.components.timeline_analyze', ['url' => '/commercial/client/timeline/'])
<script>
    function action($this = '') {

        if ($this == '') {
            window.location.href = '/commercial/client/edit/0';
        }
        var json = JSON.parse($($this).attr('json-data'));
        if ($($this).val() == 1) {
            window.location.href = '/commercial/client/edit/'+json.id;
        } else if ($($this).val() == 2) {
            window.open('/commercial/client/print/view/'+json.id, '_blank');
        } else if ($($this).val() == 3) {
			analyzeTimeline(json.id);
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

        $(".client_group_dropdown").select2({
            maximumSelectionLength: 1,
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
		
		$("#exportNow").click(function() {
            $("#form_client_export").submit();
        });

        $("#client").addClass('menu-open');
        $("#clientAll").addClass('page-arrow active-page');
    });
</script>

@endsection
