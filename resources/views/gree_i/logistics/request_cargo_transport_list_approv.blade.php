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
                Solicitações de transporte de carga
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
                                        <h5>Transporte de carga para aprovação</h5>
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
                                            <th>Código</th>
                                            <th>Solicitante</th>
                                            <th>Razão</th>
                                            <th>Data Liberação</th>
                                            <th>Tipo</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($approv_list as $key)

                                            <tr role="row">
                                                <td>{{$key->code}}</td>
                                                <td>{{$key->request_user->full_name}}</td>
                                                <td class="text-primary">
                                                    @if($key->type_reason == 1)
                                                        Entrega de compra
                                                    @elseif($key->type_reason == 2)
                                                        Carregamento 
                                                    @elseif($key->type_reason == 4)
                                                        Importação 
                                                    @elseif($key->type_reason == 5)
                                                        Transferência 
                                                    @elseif($key->type_reason == 6)
                                                        Retirada de venda     
                                                    @elseif($key->type_reason == 7)
                                                        Coleta  
                                                    @else
                                                        Entrega de avaria
                                                    @endif
                                                </td>   
                                                <td>{{ date('d/m/Y H:i', strtotime($key->date_hour)) }}</td>
                                                <td>
                                                    @if($key->is_entry_exit == 1)
                                                        <span class="badge badge-light-primary">Entrada</span>
                                                    @else    
                                                        <span class="badge badge-light-warning">Saída</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($key->is_reprov)
                                                        <span class="badge badge-light-danger">Reprovado</span>
                                                    @elseif ($key->is_cancelled)
                                                        <span class="badge badge-light-danger">Cancelado</span>
                                                    @elseif ($key->is_denied)
                                                        <span class="badge badge-light-danger">Negado</span>
                                                    @elseif ($key->is_liberate)
                                                        <span class="badge badge-light-success">Liberado</span>
                                                    @elseif ($key->has_analyze)
                                                        <span class="badge badge-light-warning">Em análise</span>
                                                    @elseif ($key->is_approv)
                                                        <span class="badge badge-light-success">Aguard. Liberação</span>
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
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $approv_list->appends(getSessionFilters()[2]->toArray()); ?>
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
                    <li class="nav-item">
                        <a class="nav-link" id="messages-tab-justified" data-toggle="tab" href="#messages-just" role="tab" aria-controls="messages-just" aria-selected="false">Documentos</a>
                    </li>
                </ul>
                <div class="tab-content pt-1">
                    <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
                        <div id="request"></div>
                    </div>
                    <div class="tab-pane" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">
                        <div id="charging"></div>
                    </div>
                    <div class="tab-pane" id="messages-just" role="tabpanel" aria-labelledby="messages-tab-justified">
                        <div id="documents"></div>
                    </div>
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

<form action="/logistics/request/cargo/transport/analyze" method="post" id="rtd_analyze_form">
    <input type="hidden" name="id" id="rtd_analyze_id">
    <input type="hidden" name="type" id="rtd_analyze_type">
    <input type="hidden" name="description" id="rtd_analyze_observation">
    <input type="hidden" name="position" id="rtd_analyze_position">
    <input type="hidden" name="password">
</form>    

@include('gree_i.misc.components.analyze.history.view');
@include('gree_i.logistics.components.cargo_transporter')

<script>

    @include('gree_i.misc.components.analyze.history.script');

    var position = null;
    var reason = {
        1 : 'Aprovar',
        2 : 'Reprovar',
        3 : 'Suspender',
        4 : 'Voltar etapa'
    };

    function analyze() {

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
        
        $('#list-datatable').DataTable({
            searching: false,
            paging: false,
            ordering:false,
            lengthChange: false,
            language: {
                search: "{{ __('layout_i.dtbl_search') }}",
                zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                info: "{{ __('layout_i.dtbl_info') }}",
                infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
                infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
            }
        });

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExit").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExitApprovList").addClass('active');
        }, 100);
    });
    </script>
@endsection
