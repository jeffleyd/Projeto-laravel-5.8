@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">

<style>
    /*.table th, .table td {
        padding: 1.10rem 0.50rem;
    }*/
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
                Transportadoras
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
                                            <h5>Todos Galpões</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <a type="button" class="btn btn-primary shadow mr-1" href="/logistics/warehouse/edit/0"><i class="bx bx-add-to-queue"></i> Novo galpão</a>
                                        </div>
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-success shadow mr-1" data-toggle="modal" data-target="#modal_filter"><i class="bx bx-search-alt"></i> Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table" id="list-datatable">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Galpão</th>
                                                <th>Endereço</th>
                                                <th>Cidade / Estado</th>
                                                <th>CEP</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($warehouse) > 0)
                                                @foreach ($warehouse as $i => $key)
                                                    <tr class="cursor-pointer showDetails">
                                                        <td>
                                                            <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer" style="color: #3568df;"></i>
                                                        </td>
                                                        <td>{{ $key->name }}</td>
                                                        <td>{{ $key->address }}</td>
                                                        <td>{{ $key->city }} - {{ $key->state }}</td>
                                                        <td>{{ $key->zipcode }}</td>
                                                        <td>
                                                            <div class="dropleft">
                                                                <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a class="dropdown-item" href="/logistics/warehouse/edit/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar galpão</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="group info_extra" style="display: none;">
                                                        <td colspan="10">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                        <li class="nav-item">
                                                                            <a class="nav-link active" data-toggle="tab" href="#tab-approv-{{$i}}" role="tab" aria-selected="true"><i class="bx bx-group" style="position: relative;top: 2px;right: -2px;"></i> Aprovadores</a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link" data-toggle="tab" href="#tab-gate-{{$i}}" role="tab" aria-selected="false"><i class="bx bx-shield-alt" style="position: relative;top: 2px;right: -2px;"></i> Portarias</a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link" data-toggle="tab" href="#tab-content-{{$i}}" role="tab" aria-selected="false"><i class="bx bx-cube" style="position: relative;top: 2px;right: -2px;"></i> Tipo de conteúdo</a>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="tab-content">
                                                                        <div class="tab-pane active" id="tab-approv-{{$i}}" role="tabpanel">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="py-75" style="padding-left: 0rem;padding-right: 0rem;">
                                                                                        <h6>APROVADORES</h6>
                                                                                    </div>
                                                                                    <ul class="list-group list-group-flush" >
                                                                                        @php $arr_approv = $key->analyze_approv->sortBy('position')->groupBy('position'); @endphp
                                                                                        @foreach ($arr_approv as $approv)
                                                                                            @if($approv->count() == 1)
                                                                                                <li class="list-group-item">
                                                                                                    <div class="list-left d-flex">
                                                                                                        <div class="list-icon mr-1">
                                                                                                            <div class="avatar bg-rgba-primary m-0">
                                                                                                                <img class="" src="<?= $approv->first()->users->picture != "" ? $approv->first()->users->picture : "/media/avatars/avatar10.jpg" ?>" alt="img placeholder" height="38" width="38">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="list-content">
                                                                                                            <span class="list-title text-bold-400">{{$approv->first()->users->full_name}}</span>
                                                                                                            <small class="text-muted d-block">{{$approv->first()->position}} ° Aprovador</small>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </li>
                                                                                            @endif
                                                                                            @if($approv->count() > 1)
                                                                                                <li class="list-group-item" style="padding-top: 0px;">
                                                                                                    @foreach ($approv as $appr)
                                                                                                        <div class="list-left d-flex mt-1">
                                                                                                            <div class="list-icon mr-1">
                                                                                                                <div class="avatar bg-rgba-primary m-0">
                                                                                                                    <img class="" src="<?= $appr->users->picture != "" ? $appr->users->picture : "/media/avatars/avatar10.jpg" ?>" alt="img placeholder" height="38" width="38">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="list-content">
                                                                                                                <span class="list-title text-bold-400">{{$appr->users->full_name}}</span>
                                                                                                                <small class="text-muted d-block">{{$appr->position}} ° Aprovador</small>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </li>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </ul>    
                                                                                </div>  
                                                                                <div class="col-lg-6">
                                                                                    <div class="py-75" style="padding-left: 0rem;padding-right: 0rem;">
                                                                                        <h6>OBSERVADORES</h6>
                                                                                    </div>
                                                                                    <ul class="list-group list-group-flush" id="list_approv">
                                                                                        @foreach ($key->analyze_observ as $observ)
                                                                                            <li class="list-group-item">
                                                                                                <div class="list-left d-flex">
                                                                                                    <div class="list-icon mr-1">
                                                                                                        <div class="avatar bg-rgba-primary m-0">
                                                                                                            <img class="" src="<?= $observ->users->picture != "" ? $observ->users->picture : "/media/avatars/avatar10.jpg" ?>" alt="img placeholder" height="38" width="38">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="list-content">
                                                                                                        <span class="list-title text-bold-400">{{$observ->users->full_name}}</span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </li>    
                                                                                        @endforeach    
                                                                                    </ul>    
                                                                                </div> 
                                                                            </div>
                                                                        </div>    
                                                                        <div class="tab-pane" id="tab-gate-{{$i}}" role="tabpanel">
                                                                            <div class="table-responsive">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Portaria</th>
                                                                                            <th>Telefone</th>
                                                                                            <th>Ramal</th>
                                                                                        </tr>    
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach ($key->logistics_entry_exit_gate as $gate)
                                                                                            <tr style="border-bottom: 1px solid #DFE3E7;">
                                                                                                <td>{{ $gate->name }}</td>
                                                                                                <td>{{ $gate->phone }}</td>
                                                                                                <td>{{ $gate->ramal }}</td>
                                                                                            </tr>  
                                                                                        @endforeach 
                                                                                    </tbody>    
                                                                                </table>
                                                                            </div>    
                                                                        </div>    
                                                                        <div class="tab-pane" id="tab-content-{{$i}}" role="tabpanel">
                                                                            <div class="table-responsive">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Tipo de conteúdo</th>
                                                                                        </tr>    
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach ($key->logistics_warehouse_type_content as $content)
                                                                                            <tr style="border-bottom: 1px solid #DFE3E7;">
                                                                                                <td>{{ $content->description }}</td>
                                                                                            </tr>  
                                                                                        @endforeach 
                                                                                    </tbody>    
                                                                                </table>
                                                                            </div>   
                                                                        </div>    
                                                                    </div>    
                                                                </div>    
                                                            </div>    
                                                        </td>    
                                                    </tr>
                                                @endforeach
                                            @else    
                                                <tr>
                                                    <td colspan="6" style="text-align: center;">Não há galpões cadastrados</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-end">
                                            <?= $warehouse->appends([
                                                'warehouse' => Session::get('ware_warehouse'),
                                                'approv' => Session::get('ware_approv'),
                                                'gate' => Session::get('ware_gate'),
                                                'content' => Session::get('ware_content')
                                            ])->links(); ?>   
                                        </ul>
                                    </nav>
                                </div>    
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
                <span class="modal-title">Filtrar Galpão</span>
            </div>
            <div class="modal-body">
                <form method="get" action="{{Request::url()}}" id="form_warehouse_filter">
                    <div class="row">
                        <div class="col-12 col-md-12 col-sm-12">        
                            <div class="form-group">
                                <label>Galpão</label>
                                <input type="text" class="form-control" name="warehouse" placeholder="">
                            </div>
                        </div>    
                        <div class="col-12 col-md-12 col-sm-12">      
                            <div class="form-group">
                                <label>Aprovador</label>
                                <select class="form-control select-approv" name="approv" style="width: 100%;" multiple></select>
                            </div>
                        </div>    
                        <div class="col-12 col-md-12 col-sm-12">        
                            <div class="form-group">
                                <label>Portaria</label>
                                <select class="form-control select-gate" name="gate" style="width: 100%;" multiple></select>
                            </div>
                        </div>    
                        <div class="col-12 col-md-12 col-sm-12">        
                            <div class="form-group">
                                <label>Tipo de conteúdo</label>
                                <select class="form-control select-type-content" name="content" style="width: 100%;" multiple></select>
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
                <button type="button" class="btn btn-primary ml-1" id="btn_warehouse_filter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<script>
    $(document).ready(function () {

        $('.showDetails td').not('.no-click').click(function (e) {
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
        });

        $(".select-approv").select2({
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

        $(".select-gate").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione",
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

        $(".select-type-content").select2({
            placeholder: "Selecione",
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return 'tipo de conteúdo não encontrado';
                }
            },
            ajax: {
                url: '/logistics/warehouse/type/content/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            },
        });

        $("#btn_warehouse_filter").click(function() {
            $("#form_warehouse_filter").submit();
        });

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsWarehouse").addClass('sidebar-group-active active');
        }, 100);
    });

</script>
@endsection