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
                    Entrada & Saída Itens
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
                                            <h5>Entrada e Saída de itens</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-primary shadow mr-1" id="btn_filter_item"><i class="bx bx-search-alt"></i> Filtrar</button>
                                        </div>
                                        <div class="dropdown invoice-options">
                                            <button type="button" class="btn btn-success shadow mr-0" id="btn_export_item"><i class="bx bx-import"></i> Exportar</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table" id="list-datatable" style="text-align: center;">
                                    <thead>
                                        <tr role="row">
                                            <th>Solicitação</th>
                                            <th>Tipo</th>
                                            <th>Galpão</th>
                                            <th>Código</th>
                                            <th>Descrição</th>
                                            <th>Quantidade</th>
                                            <th>Data</th>
											<th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($itens as $key)
                                            <tr role="row">
                                                <td>
                                                    @if($key->type_request == 1)
                                                        <a href="#" target="_blank">{{$key->logistics_entry_exit_requests->code ?? ''}}</a>
                                                    @elseif($key->type_request == 2)    
                                                        <a href="/logistics/request/cargo/transport/list?code={{$key->logistics_entry_exit_requests->code}}" target="_blank">{{$key->logistics_entry_exit_requests->code}}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($key->is_entry_exit == 1)
                                                        <span class="badge badge-light-primary">Entrada</span>
                                                    @else    
                                                        <span class="badge badge-light-warning">Saída</span>
                                                    @endif
                                                </td>
                                                <td>{{$key->logistics_warehouse->name}}</td>    
                                                <td>{{$key->code ? $key->code : '-'}}</td>
                                                <td>{{$key->description ? $key->description : '-'}}</td>
                                                <td>{{$key->quantity}}</td>
                                                <td>{{ date('d/m/Y H:i', strtotime($key->created_at)) }}</td>
												<td>
                                                    <div class="dropleft">
                                                        <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="reloadSingle(this)" json-data="<?= htmlspecialchars(json_encode($key->logistics_entry_exit_requests), ENT_QUOTES, 'UTF-8') ?>"><i class="bx bx-show mr-1"></i>Visualizar</a>
                                                        </div>
                                                    </div>    
                                                </td>
                                            </tr>    
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $itens->appends(getSessionFilters()[2]->toArray()); ?>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title modal-filter-header">Filtrar entrada e saída itens</span>
            </div>
            <div class="modal-body">
                <form method="get" action="{{Request::url()}}" id="form_item_filter">
                    <input type="hidden" name="export" id="export" value="0">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Tipo</label>
                                <select class="form-control" name="is_entry_exit">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="1">Entrada</option>
                                    <option value="2">Saída</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Código solicitação</label>
                                <input type="text" class="form-control" name="code_request">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Tipo de solicitação</label>
                                <select class="form-control" name="type_request">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="1">Funcionário</option>
                                    <option value="2">Transporte de carga</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Galpão</label>
                                <select class="custom-select select-wharehouse" name="warehouse_id" id="warehouse_id" style="width: 100%;" multiple></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Código item</label>
                                <input type="text" class="form-control" name="code_item" placeholder="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Descrição do item</label>
                                <input type="text" class="form-control" name="description" placeholder="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Inicial</label>
                                <input type="text" class="form-control date-mask" name="start_date" placeholder="00/00/0000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Final</label>
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
                <button type="button" class="btn btn-primary ml-1" id="btn_submit_filter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block modal-filter-btn">Filtrar</span>
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
                <button type="button" class="btn btn-secondary" id="print_request">
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

@include('gree_i.logistics.components.cargo_transporter')
@include('gree_i.misc.components.printElem.script')

<script>
    $(document).ready(function () {
		
		$("#print_request").click(function() {
            $('.nav-tabs a[href="#home-just"]').tab('show');
            wdgt_printElem('request');
        });

        $("#print_items_request").click(function() {
        	$('.nav-tabs a[href="#profile-just"]').tab('show');
            wdgt_printElem('charging');
        });

        $(".select-wharehouse").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione o galpão",
            language: {
                noResults: function () {
                    return 'Galpão não encontrado'; 
                }
            },
            ajax: {
                url: '/logistics/warehouse/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#btn_export_item").click(function() {
            $(".modal-filter-header").text('Exportar entrada e saída itens');
            $(".modal-filter-btn").text('Exportar');
            $("#export").val(1);
            $("#modal_filter").modal('show');
        });

        $("#btn_filter_item").click(function() {
            $(".modal-filter-header").text('Filtrar entrada e saída itens');
            $(".modal-filter-btn").text('Filtrar');
            $("#export").val(0);
            $("#modal_filter").modal('show');
        });

        $("#btn_submit_filter").click(function() {
            $('#form_item_filter').submit();
        });

        $('#list-datatable').DataTable( {
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
        
        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
			$("#mLogisticsWarehouseItens").addClass('sidebar-group-active active');
        }, 100);
    });

</script>
@endsection