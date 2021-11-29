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

<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Logística</h5>
              <div class="breadcrumb-wrapper col-12">
                Containers
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
                                            <h5>Containers</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <a type="button" class="btn btn-primary shadow mr-1" href="/logistics/container/edit/0"><i class="bx bx-add-to-queue"></i> Novo container</a>
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
                                            <th>N° Container</th>
                                            <th>Empresa</th>
                                            <th>Telefone</th>
                                            <th>Email</th>
                                            <th>Cidade / País</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($container as $key)
                                            <tr role="row">
                                                <td>{{ $key->number_container }}</td>

                                                @if($key->logistics_transporter)
                                                    <td>{{ $key->logistics_transporter->name }}</td>
                                                    <td>{{ $key->logistics_transporter->phone }}</td>
                                                    <td>{{ $key->logistics_transporter->email }}</td>
                                                    <td>{{ $key->logistics_transporter->city }} / Brasil</td>
                                                @else     
                                                    <td>{{ $key->name }}</td>
                                                    <td>{{ $key->phone }}</td>
                                                    <td>{{ $key->email }}</td>
                                                    <td>{{ $key->city }} / {{ $country->find($key->country)->name }}</td>
                                                @endif    

                                                <td>
                                                    <div class="dropleft">
                                                        <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="/logistics/container/edit/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>    
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $container->appends([
                                            'number_container' => Session::get('container_number_container'),
                                            'transporter' => Session::get('container_transporter'),
                                            'company_name' => Session::get('container_company_name')
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
                <span class="modal-title">Filtrar Container</span>
            </div>
            <div class="modal-body">
                <form method="get" action="{{Request::url()}}" id="form_container_filter">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Número do container</label>
                                <input type="text" class="form-control" name="number_container" placeholder="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Transportadora</label>
                                <select class="form-control select-transporter" id="transporter" name="transporter" style="width: 100%;" multiple></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Nome da empresa</label>
                                <input type="text" class="form-control" name="company_name" placeholder="">
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
                <button type="button" class="btn btn-primary ml-1" id="btn_container_filter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<script>
    $(document).ready(function () {

        $(".select-transporter").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione",
            language: {
                noResults: function () {
                    return 'Tranportadora não encontrada!';
                }
            },
            ajax: {
                url: '/logistics/transporter/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#btn_container_filter").click(function() {
            $("#form_container_filter").submit();
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
            $("#mLogisticsContainer").addClass('sidebar-group-active active');
        }, 100);
    });

</script>
@endsection