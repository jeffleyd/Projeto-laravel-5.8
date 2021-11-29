@extends('gree_i.layout')

@section('content')

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
                                            <h5>Lista de Vigilantes</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <a type="button" class="btn btn-primary shadow mr-1" href="/logistics/security/guard/edit/0"><i class="bx bx-add-to-queue"></i> Novo vigilante</a>
                                        </div>
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-success shadow mr-1" data-toggle="modal" data-target="#modal_filter"><i class="bx bx-search-alt"></i> Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table" id="list-datatable" style="text-align: center;">
                                    <thead>
                                        <tr role="row">
                                            <th>Portaria</th>
                                            <th>Nome</th>
                                            <th>Telefone</th>
                                            <th>expediente</th>
                                            <th>Turno</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($guard as $key)
                                            <tr role="row">
                                                <td>{{ $key->logistics_entry_exit_gate ? $key->logistics_entry_exit_gate->name : '-' }}</td>
                                                <td>{{ $key->name }}</td>
                                                <td>{{ $key->phone_1 }} / {{ $key->phone_2 }}</td>
                                                <td>{{ date('H:i', strtotime($key->begin_hour_work)) }} - {{ date('H:i', strtotime($key->final_hour_work)) }}</td>
                                                <td>
                                                    @if($key->working_turn == 1) 
                                                        1° Turno
                                                    @elseif ($key->working_turn == 2)
                                                        2° Turno
                                                    @else
                                                        3° Turno
                                                    @endif    
                                                </td>
                                                <td>
                                                    @if($key->is_active == 1)
                                                        <span class="badge badge-light-success">Ativo</span>
                                                    @else
                                                        <span class="badge badge-light-danger">Desativado</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropleft">
                                                        <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="/logistics/security/guard/edit/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>    
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $guard->appends([
                                            'gate' => Session::get('guard_gate'),
                                            'name' => Session::get('guard_name'),
                                            'working_turn' => Session::get('guard_working_turn')
                                        ])->links(); ?>  
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
                <span class="modal-title">Filtrar Vigilantes</span>
            </div>
            <div class="modal-body">
                <form method="get" action="{{Request::url()}}" id="form_gate_filter">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Portaria</label>
                                <select class="custom-select select-gate" name="gate"style="width: 100%;" multiple></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Vigilante</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Turno de trabalho</label>
                                <select class="custom-select" name="working_turn" id="working_turn">
                                    <option value="">Selecione</option>
                                    <option value="1">1° turno</option>
                                    <option value="2">2° turno</option>
                                    <option value="3">3° turno</option>
                                </select>
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
                <button type="button" class="btn btn-primary ml-1" id="btn_gate_filter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<script>
    $(document).ready(function () {

        $(".select-gate").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione a portaria",
            language: {
                noResults: function () {
                    return 'Portaria não encontrada'; 
                }
            },
            ajax: {
                url: '/logistics/gate/dropdown/list',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#btn_gate_filter").click(function() {
            $("#form_gate_filter").submit();
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
        
        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExitGuards").addClass('active');
        }, 100);
    });

</script>
@endsection