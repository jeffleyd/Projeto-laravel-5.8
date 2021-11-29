@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<style>
    .table th, .table td {
        padding: 1.10rem 0.20rem;
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
        font-size: 12px;
        font-weight: normal;
    }
    .td-font-14 {
        font-size: 12px;
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
              <h5 class="content-header-title float-left pr-1 mb-0">Entrada & Saída</h5>
              <div class="breadcrumb-wrapper col-12">
                Solicitações de Visitante & Prestador de Serviço
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- users list start -->
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="top d-flex flex-wrap">
                                <div class="action-filters flex-grow-1">
                                    <div class="dataTables_filter mt-1">
                                        <h5>Visitante & P. Serviço para aprovação</h5>
                                    </div>
                                </div>
                                <div class="actions action-btns d-flex align-items-center">
                                    <div class="dropdown invoice-filter-action">
                                        <button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
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
                                                    <td>{{ $key->logistics_entry_exit_visit->name }}</td>
                                                    <td>{{ $key->logistics_entry_exit_visit->company_name }}</td>
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
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="reloadSingle(this)" 
                                                                    json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" 
                                                                    data-id="<?= $key->id ?>" 
                                                                    data-position="<?= $key->rtd_status['status']['validation']->first()->position ?>"><i class="bx bx-check-circle mr-1"></i> Análisar</a>
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="rtd_analyzes(<?=$key->id?>, 'App\\Model\\LogisticsEntryExitRequests')"><i class="bx bx-list-check mr-1"></i> hist. de aprovações</a>
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
                                                                                @else     
                                                                                    <span class="badge badge-light-warning">Aguard. Liberação</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if($schedule->is_denied == 1 || $schedule->is_liberate == 1)
                                                                                    <i class="bx bx-show-alt modal-details" data-json='<?= htmlspecialchars(json_encode($schedule), ENT_QUOTES, "UTF-8") ?>' style="cursor: pointer; color:#3568df;" data-toggle="tooltip" data-placement="left" data-original-title="Detalhes da negação"></i>
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
                                            <tr role="row" style="text-align: center;">
                                                <td colspan="9">Não há solicitações para aprovar</td>
                                            </tr>    
                                        @endif    
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $entry_exit->appends(getSessionFilters()[2]->toArray()); ?>
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar dados</span>
            </div>
            <form action="{{Request::url()}}" id="form_modal_filter">
                <div class="modal-body">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="submit" onclick="block();" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Filtrar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left modal-borderless modal-view" id="requestPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full">
        <div class="modal-content">
            <div class="modal-body">
                <div id="request">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="analyze()">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Realizar análise</span>
                </button>
                <button type="button" class="btn btn-light-primary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar visualização</span>
                </button>
            </div>
        </div>
    </div>
</div>

<form action="/logistics/request/visitor/service/analyze" method="post" id="rtd_analyze_form">
    <input type="hidden" name="id" id="rtd_analyze_id">
    <input type="hidden" name="type" id="rtd_analyze_type">
    <input type="hidden" name="description" id="rtd_analyze_observation">
    <input type="hidden" name="position" id="rtd_analyze_position">
    <input type="hidden" name="password">
</form>    

@include('gree_i.misc.components.analyze.history.view')
@include('gree_i.logistics.components.visitor_service')

<script>

    @include('gree_i.misc.components.analyze.history.script')

    var position = null;
    var reason = {
        1 : 'Aprovar',
        2 : 'Reprovar',
        3 : 'Suspender',
        4 : 'Voltar etapa'
    };

    function analyze(id) {

        var observation = '';
        var option_position = position != 1 ? `<option value="4">Voltar etapa</option>` : ``;

        Swal.fire({
            type: 'warning',
            title: 'Realizar análise',
            target: document.getElementById('requestPrint'),
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continue <i class="fa fa-arrow-right"></i>',
            html: `Selecione a análise e informe observação de aprovação 
                   <select class="swal2-input" id="swal_type" style="width: 100%;margin-bottom: -5px;">
                        <option value="" selected disabled>Selecione o tipo de aprovação</option>
                        <option value="1">Aprovar</option>
                        <option value="2">Reprovar</option>
                        <option value="3">Suspender</option>`
                        +option_position+    
                   `</select>
                   <textarea id="swal_observation" class="swal2-textarea" placeholder="Informe a observação desta análise"></textarea>`,
            preConfirm: () => {
                

                var type = $("#swal_type").val();
                observation = $("#swal_observation").val();

                if(type == null) {
                    swal.showValidationError('Selecione o tipo de aprovação');
                    return false;
                } else {
                    if((type == 2 || type == 3 || type == 4) && observation == "") {
                        swal.showValidationError('Informe a observação da análise');
                        return false;
                    }
                }
            }
        }).then((result) => {

            if(result.value) {

                var type = $("#swal_type").val();
                var select = '';
                
                if(type == 4) {
                    if(position > 1) {
                        var input = '';
                        for(var i = 1; i < position; i++) {
                            input += '<option value="'+i+'">Etapa '+i+'</option>';
                        }
                        select += '<select class="swal2-input" id="type_revert" style="width: 100%; margin-bottom: -5px;">'+input+'</select>';
                    }
                }
                
                Swal.fire({
                    title: reason[type] + ' solicitação',
                    target: document.getElementById('requestPrint'),
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    html: 'Para dar andamento, informe sua senha e confirme'+
                          ''+select+''+
                          '<input type="password" id="swal_password" class="swal2-input" placeholder="Informe a senha">',
                    preConfirm: () => {      
                        if($("#swal_password").val() == "") {
                            swal.showValidationError(
                                'Informe a senha para continuar'
                            );
                        }
                    }    
                }).then(function (result) {

                    if (result.value) {

                        $("#rtd_analyze_type").val(type);
                        $("input[name='password']").val($("#swal_password").val());
                        $("#rtd_analyze_observation").val(observation);
                        $("#rtd_analyze_position").val($("#type_revert").val());

                        block();
                        $("#rtd_analyze_form").submit();
                    }

                });
            }
        });
    }

    $(document).ready(function () {

        $('.showDetails td').not('.no-click').click(function (e) {
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
        });

        $(".modal-details").click(function (e) {

            data = JSON.parse($(this).attr('data-json'));

            var guard = data.security_guard_liberate_deneid ? data.security_guard_liberate_deneid.name : '';
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

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExit").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExitVisitorServiceApprovList").addClass('active');
        }, 100);
    });
    </script>
@endsection
