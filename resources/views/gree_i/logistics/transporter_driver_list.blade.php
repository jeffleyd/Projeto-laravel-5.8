@extends('gree_i.layout')

@section('content')

<style>
    .table th, .table td {
        padding: 1.10rem 0.20rem;
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
                                            <h5>Lista de Motoristas</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-primary shadow mr-1" id="btn_modal_driver"><i class="bx bx-add-to-queue"></i> Novo motorista</button>
                                        </div>
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-success shadow mr-1" data-toggle="modal" data-target="#modal_filter"><i class="bx bx-search-alt"></i> Filtrar</button>
                                        </div>
                                        <div class="dropdown invoice-options">
                                            <button type="button" class="btn btn-dark shadow mr-0" data-toggle="modal" data-target="#modal_import_drivers"><i class="bx bx-import"></i> Importar</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table" id="list-datatable" style="text-align: center;">
                                    <thead>
                                        <tr role="row">
                                            <th>Transport / Fornecedor</th>
                                            <th>Nome</th>
                                            <th>RG</th>
                                            <th>Telefone</th>
                                            <th>Sexo</th>
                                            <th>CNH</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($drivers as $driver)
                                        <tr role="row">
                                            <td>
                                                @if($driver->transporter_id != 0 || $driver->supplier_id != 0)
                                                    <span data-toggle="tooltip" data-placement="top" title="<?= $driver->transporter_id ? $driver->logistics_transporter->name : $driver->logistics_supplier->name  ?>" style="cursor: pointer;">
                                                        <?= $driver->transporter_id ? stringCut($driver->logistics_transporter->name, 25) : stringCut($driver->logistics_supplier->name, 25)  ?>
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span data-toggle="tooltip" data-placement="top" title="{{ $driver->name }}" style="cursor: pointer;">
                                                    <?= stringCut($driver->name, 25) ?>
                                                </span>
                                            </td>
                                            <td>{{ $driver->identity }}</td>
                                            <td>{{ $driver->phone }}</td>
                                            <td>@if($driver->gender == 1) Masculino @else Feminino @endif</td>
                                            <td class="file-clear-{{$driver->id}}">@if($driver->cnh_url) <a href="{{$driver->cnh_url}}" target="_blank">visualizar</a> @else - @endif </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item edit-driver" data-json='<?= htmlspecialchars(json_encode($driver), ENT_QUOTES, "UTF-8") ?>' href='javascript:void(0)'><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>    
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $drivers->appends([
                                            'name' => Session::get('driver_name'),
                                            'identity' => Session::get('driver_identity'),
                                            'transporter' => Session::get('driver_transporter')
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

<div class="modal fade" id="modal_add_driver" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white modal-add-title">Cadastrar motorista / pedestre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/logistics/transporter/driver/edit_do" id="form_register_driver" enctype="multipart/form-data">
                    <input type="hidden" name="driver_id" id="driver_id" value="0">
                    <div class="row">
                        <div class="col-12 col-md-12 col-sm-12">        
                            <div class="form-group">
                                <label>Motorista / Pedestre Pertence *</label>
                                <select class="form-control" id="driver_is">
                                    <option value=""></option>
                                    <option value="1">Transportador</option>
                                    <option value="2">Fornecedor</option>
                                </select>
                            </div>
                        </div>    
                    </div>
                    <div class="row div-transporter" style="display: none;">
                        <div class="col-12 col-md-12 col-sm-12">        
                            <div class="form-group">
                                <label>Transportadora *</label>
                                <select class="form-control select-transporter" id="transporter" name="transporter" style="width: 100%;" multiple></select>
                            </div>
                        </div>    
                    </div>    
                    <div class="row div-supplier" style="display: none;">
                        <div class="col-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Fornecedor *</label>
                                <select class="form-control select-supplier" name="supplier" id="supplier" style="width: 100%;" multiple></select>
                            </div>    
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Nome *</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Informe nome fantasia">
                            </div>
                        </div>
                        <div class="col-6 col-md-6 col-sm-12">        
                            <div class="form-group">
                                <label>RG *</label>
                                <input type="text" class="form-control" name="identity" id="identity" placeholder="Informe o RG">
                            </div>
                        </div>    
                    </div>    
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-12">        
                            <div class="form-group">
                                <label>Telefone</label>
                                <input type="text" class="form-control phone" name="phone" id="phone" placeholder="(00) 00000-0000">
                            </div>
                        </div>    
                        <div class="col-6 col-md-6 col-sm-12">        
                            <div class="form-group">
                                <label>Sexo</label>
                                <select class="form-control" name="gender" id="gender">
                                    <option value="">Selecione o sexo</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Feminino</option>
                                </select>
                            </div>
                        </div>    
                    </div>  
                    <div class="row" id="not_file">
                        <div class="col-12 col-md-12 col-sm-12">        
                            <div class="form-group">
                                <label>CNH</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file_cnh" id="file_cnh">
                                    <label class="custom-file-label label-attach-file">Escolha a CNH para upload</label>
                                </div>
                            </div>
                        </div>      
                    </div>
                    <div class="row mt-1" id="has_file" style="display: none;">
                        <div class="col-12 col-md-12 col-sm-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb rounded-pill breadcrumb-divider" style="width: 100%;">
                                    <li class="breadcrumb-item" id="has_file_cnh">
                                      <a href="javascript:void(0);" style="color:#5A8DEE;"><i class="bx bx-file"></i> Visualizar CNH</a>
                                    </li>
                                    <li class="breadcrumb-item active" id="has_file_delete" style="position: sticky;left: 100%; cursor:pointer; width: 40px; padding-right: 0rem;">
                                        <span><i class="bx bx-trash"></i></span>
                                    </li>
                                </ol>
                            </nav>
                        </div>    
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_save_driver">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Salvar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar Motorista</span>
            </div>
            <div class="modal-body">
                <form method="get" action="{{Request::url()}}" id="form_driver_filter">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Nome do motorista</label>
                                <input type="text" class="form-control" name="name" placeholder="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">RG</label>
                                <input type="text" class="form-control" name="identity" placeholder="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Transportadora</label>
                                <select class="form-control select-transporter-filter" name="transporter" style="width: 100%;" multiple></select>
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
                <button type="button" class="btn btn-primary ml-1" id="btn_driver_filter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_import_drivers" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Importar Motoristas</span>
            </div>
            <div class="modal-body">
                <div class="alert border-primary alert-dismissible mb-2" role="alert">
                    <div class="d-flex align-items-center">
                        <span>
                            <a targe="_blank" href="/excell/model_import_drivers.xlsx" >Modelo de importação <i class="bx bxs-download"></i></a>
                        </span>
                    </div>
                </div>
                <form method="post" action="/logistics/driver/import" id="form_import_drivers" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="price">Arquivo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file_driver" id="file_driver">
                                    <label class="custom-file-label label-items">Escolher arquivo</label>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>    
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_import_drivers">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Importar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<script>
    $(document).ready(function () {

        $("#btn_import_drivers").click(function() {

            if($("#file_driver")[0].files.length == 0) {
                return $error('Adicione um arquivo para importar');
            } else {
                block();
                $('#form_import_drivers').submit();
            }
        });

        $("#driver_is").change(function() {
            if($(this).val() == 1) {
                $(".div-transporter").show();
                $(".div-supplier").hide();
            } else {
                $(".div-transporter").hide();
                $(".div-supplier").show();
            }
        });

        $(".select-transporter, .select-transporter-filter").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    var url = "'/logistics/transporter/list/#modal_add_transporter'";
                    return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo transportadora</button>');
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

        $(".select-supplier").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione o fornecedor",
            language: {
                noResults: function () {
                    return 'Fornecedor não encontrado'; 
                }
            },
            ajax: {
                url: '/logistics/supplier/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#btn_save_driver").click(function() {

            var desc = "do motorista é obrigatório!";

            if($("#driver_is").val() == "") {
                return $error('Transportador / Fornecedor ' + desc);
            }
            else if($("#transporter").is(":visible") && $("#transporter").val() == "") {
                return $error('Transportador ' + desc);
            }
            else if($("#supplier").is(":visible") && $("#supplier").val() == "") {
                return $error('Fornecedor ' + desc);
            }
            else if($("#name").val() == "") {
                return $error('Nome ' + desc);
            }
            else if($("#identity").val() == "") {
                return $error('RG ' + desc);
            }    
            else if($("#phone").val() == "") {
                return $error('Telefone ' + desc);
            }    
            else if($("#gender").val() == "") {
                return $error('Sexo ' + desc);
            }    
            else {
                block();
                $('#form_register_driver').submit();
            }
        });

        $("#btn_driver_filter").click(function() {
            $('#form_driver_filter').submit();
        });

        $(".edit-driver").click(function() {

            obj = JSON.parse($(this).attr('data-json'));
            $("#driver_id").val(obj.id);
            $("#name").val(obj.name);
            $("#identity").val(obj.identity);
            $("#phone").val(obj.phone);
            $("#gender").val(obj.gender);
            $('#transporter').val(null).trigger('change');

            if(obj.transporter_id != 0 || obj.supplier_id != 0) {
                if(obj.transporter_id) {
                    $("#transporter").html(new Option(obj.logistics_transporter.name, obj.transporter_id, true, true)).trigger('change');
                    $("#driver_is").val(1);
                    $(".div-transporter").show();
                    $(".div-supplier").hide();
                } else {
                    $("#supplier").html(new Option(obj.logistics_supplier.name, obj.supplier_id, true, true)).trigger('change');
                    $("#driver_is").val(2);
                    $(".div-transporter").hide();
                    $(".div-supplier").show();
                }
            } else {
                $("#driver_is").val('');
                $(".div-transporter, .div-supplier").hide();
            }    

            if(obj.cnh_url) {

                $("#has_file_cnh").html(
                    '<a href="'+obj.cnh_url+'" style="color:#5A8DEE;" target="_blank"><i class="bx bx-file"></i> Visualizar CNH</a>'
                );

                $("#has_file_delete").html(
                    '<span class="delete_cnh" data-id="'+obj.id+'" data-cnh="'+obj.cnh_url+'" style="position: relative; right: 14px; top: 2px;"><i class="bx bx-trash"></i></span>'
                );

                $("#not_file").hide();
                $("#has_file").show();
            } else {
                $("#not_file").show();
                $("#has_file").hide();
            }

            $(".modal-add-title").text('Editar Motorista / Pedestre');
            $("#modal_add_driver").modal('show');
        });

        $("#btn_modal_driver").click(function() {

            $("#name, #identity, #phone, #gender, #file_cnh, #driver_is").val('');
            $(".label-attach-file").text('Escolha a CNH para upload');
            $(".select-transporter").val(0).trigger('change');
            $(".select-supplier").val(0).trigger('change');
            $("#not_file").show();
            $("#has_file").hide();
            $(".div-transporter, .div-supplier").hide();
            $(".modal-add-title").text('Cadastrar Motorista / Pedestre');
            $("#modal_add_driver").modal('show');
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

        $(document).on('click',".delete_cnh",function(e) {

            var id = $(this).attr('data-id');
            var url = $(this).attr('data-cnh');

            Swal.fire({
                title: 'Excluir CNH',
                text: "Deseja confirmar a exclusão da CNH do motorista?",
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
                    ajaxSend('/logistics/transporter/driver/remove/file/ajax', {id: id, url: url}, 'GET', 3000).then(function(result) {

                        if(result.success) {
                            $("#not_file").show();
                            $("#has_file").hide();
                            $(".file-clear-"+id+"").html('-');

                            $success(result.message);
                        }
                        unblock();
                    }).catch(function(err){
                        unblock();
                        $error(err.message);
                    });   
                }
            });
        });

        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.phone').mask(SPMaskBehavior, spOptions);

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsTransporter").addClass('sidebar-group-active active');
            $("#mLogisticsTransporterDrivers").addClass('active');
        }, 100);
    });

</script>
@endsection