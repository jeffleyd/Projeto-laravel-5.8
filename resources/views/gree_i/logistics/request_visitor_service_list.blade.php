@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<style>
    .table th, .table td {
        padding: 1.10rem 0.20rem;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline {
        width: 100% !important;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline .select2-search__field {
        width: 100% !important;
    }

    .td-title {
        background-color: #f6f6f6;
        font-weight: normal;
    }
    .td-text-white {
        color: white;
        font-weight: normal;
    }
    .td-color-black {
        background-color: #000;
        font-weight: normal;
    }
    .td-color-red {
        background-color: rgb(145, 0, 0);
    }
    .td-bold {
        font-weight: 600 !important;
    }
    .td-text-center {
        text-align: center;
    }
    .td-font-11 {
        font-size: 6px;
        font-weight: normal;
    }
    .td-font-13 {
        font-size: 13px;
        font-weight: normal;
    }
    .td-font-14 {
        font-size: 13px;
        font-weight: normal;
    }
    .td-font-17 {
        font-size: 13px;
        font-weight: normal;
    }
</style>

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Logística</h5>
              <div class="breadcrumb-wrapper col-12">
                Entrada e saída
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="top d-flex flex-wrap">
                                    <div class="action-filters flex-grow-1">
                                        <div class="dataTables_filter mt-1">
                                            <h5>Solicitações Visitante & P.Serviço</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <a type="button" class="btn btn-primary shadow mr-1" href="/logistics/request/visitor/service/edit/0"><i class="bx bx-add-to-queue"></i> Nova solicitação</a>
                                        </div>
										@if (hasPermManager(26))
											<div class="dropdown invoice-filter-action">
												<button type="button" class="btn btn-dark shadow mr-1" id="btn_export_visitant"><i class="bx bx-file"></i> Exportar</button>
										@endif
                                        </div>
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-success shadow mr-1" id="btn_filter_visitant"><i class="bx bx-search-alt"></i> Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table" id="list-datatable" style="text-align: center;">
                                    <thead>
                                        <tr role="row">
                                            <th></th>
                                            <th>Código</th>
                                            <th>Solicitante</th>
                                            <th>Razão</th>
                                            <th>Visitante</th>
                                            <th>Empresa</th>
                                            <th>Motivo</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($entry_exit->count() > 0)
                                            @foreach ($entry_exit as $key)
                                                <tr role="row" class="cursor-pointer showDetails">
                                                    <td>
                                                        <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer" style="color: #3568df;"></i>
                                                    </td>
                                                    <td>{{ $key->code }}</td>
                                                    <td data-toggle="tooltip" data-placement="bottom" title="{{$key->request_user->full_name}}"><?= stringCut($key->request_user->full_name, 30) ?></td>
                                                    <td>
                                                        @if($key->type_reason == 3)
                                                            Visita
                                                        @elseif($key->type_reason == 9)
                                                            Prestador de serviço
                                                        @elseif($key->type_reason == 10)
                                                            Seleção p/ contratação
                                                        @endif
                                                    </td>
                                                    <td>{{ $key->logistics_entry_exit_visit ? $key->logistics_entry_exit_visit->name : '-' }}</td>
                                                    <td>{{ $key->logistics_entry_exit_visit ? $key->logistics_entry_exit_visit->company_name : '-' }}</td>										
                                                    <td data-toggle="tooltip" data-placement="bottom" title="{{ $key->reason }}"><?= stringCut($key->reason, 30) ?></td>
                                                    <td>
                                                        @if ($key->is_reprov)
                                                            <span class="badge badge-light-danger">Reprovado</span>
                                                        @elseif ($key->is_cancelled)
                                                            <span class="badge badge-light-danger">Cancelado</span>
                                                        @elseif ($key->has_analyze)
                                                            <span class="badge badge-light-warning">Em análise</span>
                                                        @elseif ($key->is_approv)
                                                            <span class="badge badge-light-success">Aprovado</span>
                                                        @else
                                                            <span class="badge badge-light-secondary">Rascunho</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropleft">
                                                            <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="/logistics/request/visitor/service/edit/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                                @if (($key->is_reprov == 0 || $key->is_reprov == 1) &&
                                                                    $key->is_cancelled == 0 && 
                                                                    $key->is_denied == 0 && 
                                                                    $key->is_liberate == 0 &&
                                                                    $key->has_analyze == 0 &&
                                                                    $key->is_approv == 0)
                                                                    <a class="dropdown-item send-approv" data-id="<?= $key->id ?>" href="javascript:void(0)"><i class="bx bx-check-double mr-1"></i> Enviar para aprovação</a>
                                                                @endif
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="reloadSingle(this)" json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>"><i class="bx bx-show mr-1"></i> Visualizar</a>
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="rtd_analyzes(<?=$key->id?>, 'App\\Model\\LogisticsEntryExitRequests')"><i class="bx bx-file mr-1"></i> histórico de aprovação</a>
                                                                <a class="dropdown-item" onclick="duplicateRequest(<?= $key->id ?>)" href="javascript:void(0)"><i class="bx bx-copy mr-1"></i> Duplicar Solicitação</a>
																@if (hasPermManager(26))
																	@if($key->has_analyze == 1)
																		<a class="dropdown-item" onclick="ApprovNow(<?= $key->id ?>)" href="javascript:void(0)"><i class="bx bx-task mr-1"></i> Aprovação imediata</a>
																	@endif    
																@endif
																@if($key->is_cancelled == 0 && $key->is_approv == 0 && $key->is_reprov == 0)
                                                                    <a class="dropdown-item" onclick="requestCancel(<?= $key->id ?>)" href="javascript:void(0)"><i class="bx bx-x-circle mr-1"></i>Cancelar Aprovação</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="group info_extra" style="display: none;">
                                                    <td colspan="10">
                                                        <div class="card" style="margin-bottom: 0rem;">
                                                            <div class="card-body">
                                                                <table class="table" style="text-align: center;">
                                                                    <thead>
                                                                        <tr role="row">
                                                                            <th>Tipo</th>
                                                                            <th>Data Liberação</th>
                                                                            <th>Restrição</th>
                                                                            <th>Encaminhamento</th>
                                                                            <th>Status</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($key->logistics_entry_exit_requests_schedule as $schedule)
                                                                        <tr>
                                                                            <td>
                                                                                @if($schedule->is_entry_exit == 1)
                                                                                    <span class="badge badge-light-primary">Entrada</span>
                                                                                @else    
                                                                                    <span class="badge badge-light-warning">Saída</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ date('d/m/Y H:i', strtotime($schedule->date_hour)) }}</td>
                                                                            <td>{{ $schedule->entry_restriction }}</td>
                                                                            <td>{{ $schedule->request_forwarding }}</td>
                                                                            <td>
                                                                                @if($schedule->is_liberate == 1)
                                                                                    <span class="badge badge-light-success">Liberado</span>
                                                                                @elseif($schedule->is_denied == 1)    
                                                                                    <span class="badge badge-light-danger">Negado</span>
																				@elseif($schedule->is_cancelled == 1)    
                                                                                    <span class="badge badge-light-danger">Cancelado</span>
                                                                                @else     
                                                                                    <span class="badge badge-light-warning">Aguard. Liberação</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if($schedule->is_liberate == 1)
                                                                                    <i class="bx bx-show-alt modal-details" data-json='<?= htmlspecialchars(json_encode($schedule), ENT_QUOTES, "UTF-8"); ?>' style="cursor: pointer; color:#3568df;" data-toggle="tooltip" data-placement="left" data-original-title="Detalhes da liberação"></i>
                                                                                @elseif($schedule->is_denied == 1)
                                                                                    <i class="bx bx-show-alt modal-details" data-json='<?= htmlspecialchars(json_encode($schedule), ENT_QUOTES, "UTF-8"); ?>' style="cursor: pointer; color:#3568df;" data-toggle="tooltip" data-placement="left" data-original-title="Detalhes da negação"></i>
                                                                                @endif
                                                                            </td>
                                                                        </tr>    
                                                                        @endforeach
                                                                    </tbody>    
                                                                </table>    
                                                            </div>    
                                                        </div>    
                                                    </td>    
                                                </tr>    
                                            @endforeach
                                        @else    
                                            <tr role="row">
                                                <td colspan="9">Não há solicitações de visitantes & P.Serviço</td>
                                            </tr>    
                                        @endif
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $entry_exit->appends(getSessionFilters()[2]->toArray()) ?>
                                    </ul>    
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title modal-filter-header">Filtrar Solicitações</span>
            </div>
            <div class="modal-body">
                <form method="get" action="{{Request::url()}}" id="form_request_filter">
                    <input type="hidden" name="export" id="export" value="0">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Código</label>
                                <input type="text" class="form-control" name="code">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Solicitante</label>
                                <select class="form-control select-request" name="request_r_code" id="request_r_code" style="width: 100%;" multiple></select>
                            </div>    
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Razão solicitacão</label>
                                <select class="custom-select" name="type_reason" id="type_reason">
                                    <option value="">Selecione</option>
                                    <option value="3">Visita</option>
                                    <option value="9">Prestador de serviço</option>
                                    <option value="10">Seleção para contratação</option>
                                </select>
                            </div>
                        </div>
						<div class="col-12">
                            <div class="form-group">
                                <label>Portão</label>
                                <select class="custom-select" name="gate" id="gate">
                                    <option value="">Selecione</option>
									@foreach($gates as $gate)
                                    <option value="{{$gate->id}}">{{$gate->name}}</option>
									@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="custom-select" name="is_entry_exit">
                                    <option value="">Selecione</option>
                                    <option value="1">Entrada</option>
                                    <option value="2">Saída</option>
                                </select>
                            </div>
                        </div>    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="custom-select" name="status">
                                    <option value="">Selecione</option>
                                    <option value="1">Reprovado</option>
                                    <option value="2">Cancelado</option>
                                    <option value="3">Negado</option>
                                    <option value="4">Liberado</option>
                                    <option value="5">Em análise</option>
                                    <option value="6">Aprovado</option>
                                    <option value="7">Rascunho</option> 
                                </select>
                            </div>
                        </div> 
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Inicial Liberação</label>
                                <input type="text" class="form-control date-mask" name="start_date" placeholder="00/00/0000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Final Liberação</label>
                                <input type="text" class="form-control date-mask" name="end_date" placeholder="00/00/0000">
                            </div>
                        </div>
                    </div>
                </form>    
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_request_filter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt modal-filter-btn">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<div class="modal fade text-left modal-borderless modal-view" id="requestPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-full">
        <div class="modal-content">
            <div class="card-body">
                <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab" aria-controls="home-just" aria-selected="true">Solicitação</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab-justified" data-toggle="tab" href="#profile-just" role="tab" aria-controls="profile-just" aria-selected="false">Itens Carregamento</a>
                    </li>
                </ul>
                <div class="tab-content pt-1">
                    <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
                        <div id="request"></div>
                    </div>
                    <div class="tab-pane" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">
                        <div id="charging"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="wdgt_printElem('request')">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Imprimir Solicitação</span>
                </button>
				<button type="button" class="btn btn-secondary" id="print_items_request">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Imprimir Itens Carregamento</span>
                </button>
                <button type="button" class="btn btn-light-primary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar visualização</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title header-details-status"></span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped details">
                            <tbody id="table_details">
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<form action="/logistics/request/visitant/service/approv/now" method="post" id="rtd_analyze_form">
    <input type="hidden" name="id" id="approv_now_id">
    <input type="hidden" name="description" id="approv_now_description">
    <input type="hidden" name="password">
</form>    

<form action="/logistics/request/cargo/visitant/cancel" method="post" id="form_request_cancel">
    <input type="hidden" name="id" id="cancel_id">
    <input type="hidden" name="cancel_reason" id="cancel_reason">
</form>


@include('gree_i.misc.components.analyze.history.view')
@include('gree_i.logistics.components.visitor_service')
@include('gree_i.misc.components.printElem.script')

<script>

    @include('gree_i.misc.components.analyze.history.script')

    function duplicateRequest(id) {
		
        Swal.fire({
            title: 'Tem certeza disso?',
            text: "Você irá duplicar a solicitação, essa solicitação não terá documentos em anexo.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar!',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {

            if (result.value) {
                block();
                window.location.href = "/logistics/request/visitant/service/duplicate/"+id;
            }
        });
    }

    function ApprovNow(id) {

        Swal.fire({
            title: 'Aprovar imediatamente?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar!',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            html: 'Deseja confirmar a aprovação imediata desta solicitação?'+
                '<textarea id="swal_observation" class="swal2-textarea" style="margin-bottom: 0px;height: 4.75em;" placeholder="Observação da aprovação imediata"></textarea>'+
                '<input type="password" id="swal_password" class="swal2-input" placeholder="Informe a senha">',
            preConfirm: () => {      
                if($("#swal_observation").val() == "") {
                    swal.showValidationError(
                        'Informe a observação da aprovação imediata'
                    );
                }
                else if($("#swal_password").val() == "") {
                    swal.showValidationError(
                        'Informe a senha para continuar'
                    );
                }
            }    
        }).then(function (result) {

            if (result.value) {
                $("#approv_now_id").val(id);
                $("#approv_now_description").val($("#swal_observation").val());
                $("input[name='password']").val($("#swal_password").val());
                block();
                $("#rtd_analyze_form").submit();
            }

        });
    }
	
	function requestCancel(id) {

        Swal.fire({
            title: 'Cancelar Solicitação',
            type: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar!',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
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
                $("#cancel_id").val(id);
                $("#cancel_reason").val($("#swal_cancel_reason").val());
                block();
                $("#form_request_cancel").submit();
            }
        });
    }

    $(document).ready(function () { 
		
		$("#print_items_request").click(function() {
            $('.nav-tabs a[href="#profile-just"]').tab('show');
            wdgt_printElem('charging');
        });

        $('.showDetails td').not('.no-click').click(function (e) {
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
        });

        $(".modal-details").click(function (e) {

            data = JSON.parse($(this).attr('data-json'));

            var guard = data.security_guard_liberate_denied ? data.security_guard_liberate_denied.name : '';
            if(data.is_denied == 1) {
                var html = `<tr><td class="td-label">Vigilante:</td><td>`+ guard +`</td></tr>
                            <tr><td class="td-label">Observação de negação:</td><td>`+ data.denied_reason +`</td></tr>
                            <tr><td class="td-label">Data da negação:</td><td>`+ moment(data.request_action_time).format('DD/MM/YYYY - HH:mm') +`</td></tr>`;

                $(".header-details-status").text('Detalhes da negação');
            }
            else if(data.is_liberate == 1) {
                var html = `<tr><td class="td-label">Vigilante:</td><td>`+ guard +`</td></tr>
                            <tr><td class="td-label">Data da liberação:</td><td>`+ moment(data.request_action_time).format('DD/MM/YYYY - HH:mm') +`</td></tr>`;

                $(".header-details-status").text('Detalhes da liberação');            
            }

            $('#table_details').html(html);
            $("#modal_details").modal('show');
        });

        $(document).on('click', '.send-approv', function(e) {
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Enviar para Aprovação',
                text: "Deseja confirmar o envio para aprovação?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    document.location.href = "/logistics/request/visitor/service/analyze/" + id;
                }
            });
        });    


        $('.date-mask').pickadate({
            selectYears: true,
            selectMonths: true,
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        });

        $(".select-request").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione",
            language: {
                noResults: function () {
                    return 'Usuário não existe ou está desativado...';
                }
            },
            ajax: {
                url: '/logistics/users/rcode/list',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });
        
        $("#btn_request_filter").click(function() {
            $("#form_request_filter").submit();
        });

        $("#btn_export_visitant").click(function() {

            $(".modal-filter-header").text('Exportar Solicitações');
            $(".modal-filter-btn").text('Exportar');
            $("#export").val(1);
            $("#modal_filter").modal('show');
        });

        $("#btn_filter_visitant").click(function() {

            $(".modal-filter-header").text('Filtrar Solicitações');
            $(".modal-filter-btn").text('Filtrar');
            $("#export").val(0);
            $("#modal_filter").modal('show');
        });

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExit").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExitVisitorServiceList").addClass('active');
        }, 100);
    });

</script>
@endsection