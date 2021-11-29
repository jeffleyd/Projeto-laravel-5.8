@extends('gree_i.layout')

@section('content')

<style>
    /*.table th, .table td {
        padding: 1.10rem 0.20rem;
    }*/
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
                                            <h5>Todas Carretas</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-primary shadow mr-1" id="btn_modal_cart"><i class="bx bx-add-to-queue"></i> Nova carreta</button>
                                        </div>
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-dark shadow mr-1" data-toggle="modal" data-target="#modal_filter"><i class="bx bx-search-alt"></i> Filtrar</button>
                                        </div>
                                        <!--<div class="dropdown invoice-options">
                                            <button type="button" class="btn btn-success shadow mr-0" data-toggle="modal" data-target="#modal_export"><i class="bx bx-import"></i> Exportar</button>
                                        </div>-->
                                    </div>
                                </div>
                                <hr>
                                <table class="table" id="list-datatable">
                                    <thead>
                                        <tr role="row">
                                            <th>Transportador/Fornecedor</th>
                                            <th>Carreta</th>
                                            <th>Placa</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart as $key)
                                            <tr role="row">
                                                <td>
                                                    <span data-toggle="tooltip" data-placement="top" 
                                                        title="@if($key->transporter_id) {{$key->logistics_transporter->name}} @elseif($key->supplier_id) {{$key->logistics_supplier->name}} @else Não cadastrado @endif" style="cursor: pointer;">
                                                        @if($key->transporter_id) {{$key->logistics_transporter->name}} @elseif($key->supplier_id) {{$key->logistics_supplier->name}} @else Não cadastrado @endif
                                                    </span>
                                                </td>
                                                <td>{{ config('gree.type_cart')[$key->type_cart] }}</td>
                                                <td>{{ $key->registration_plate }}</td>
                                                <td>
                                                    <div class="dropleft">
                                                        <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item edit-cart" data-json='<?= htmlspecialchars(json_encode($key), ENT_QUOTES, "UTF-8") ?>' href='javascript:void(0)'><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>    
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $cart->appends([
                                            'registration_plate' => Session::get('cart_registration_plate'),
                                            'transporter' => Session::get('cart_transporter'),
                                            'type_cart' => Session::get('cart_type_vehicle')
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

<div class="modal fade" id="modal_add_cart" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white modal-title-add">Cadastrar Carreta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="/logistics/transporter/cart/edit_do" id="form_register_cart">
                    <input type="hidden" name="cart_id" id="cart_id" value="0">
                    <div class="row">
                        <div class="col-12 col-md-12 col-sm-12">        
                            <div class="form-group">
                                <label>Veículo pertence *</label>
                                <select class="form-control" id="cart_is">
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
                        <div class="col-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Tipo de carreta *</label>
                                <select class="form-control" name="type_cart" id="type_cart">
                                    <option value="">Selecione a carreta</option>
                                    @foreach (config('gree.type_cart') as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-12 col-sm-12">        
                            <div class="form-group">
                                <label>Placa da carreta</label>
                                <input type="text" class="form-control" name="registration_plate" id="registration_plate" placeholder="Informe a placa da carreta">
                            </div>
                        </div>    
                    </div>  
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_save_cart">
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
                <span class="modal-title">Filtrar Carretas</span>
            </div>
            <div class="modal-body">
                <form method="get" action="{{Request::url()}}" id="form_cart_filter">
                    <div class="row">
                        <div class="col-12 col-md-12 col-sm-12">        
                            <div class="form-group">
                                <label>Transportadora</label>
                                <select class="form-control select-transporter-filter" name="transporter" style="width: 100%;" multiple></select>
                            </div>
                        </div>    
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Placa</label>
                                <input type="text" class="form-control" name="registration_plate" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Tipo de Carreta</label>
                                <select class="form-control" name="type_cart">
                                    <option value="">Selecione a carreta</option>
                                    @foreach (config('gree.type_cart') as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
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
                <button type="button" class="btn btn-primary ml-1" id="btn_cart_filter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<script>
    $(document).ready(function () {

        $("#cart_is").change(function() {
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

        $("#btn_save_cart").click(function() {

            var desc = "da carreta é obrigatório!";
            if($("#cart_is").val() == "") {
                return $error('Transportador / Fornecedor ' + desc);
            }
            else if($("#transporter").is(":visible") && $("#transporter").val() == "") {
                return $error('Transportador ' + desc);
            }
            else if($("#supplier").is(":visible") && $("#supplier").val() == "") {
                return $error('Fornecedor ' + desc);
            }
            else if($("#type_cart").val() == "") {
                return $error('Tipo ' + desc);
            }
            else if($("#registration_plate").val() == "") {
                return $error('Placa ' + desc);
            }    
            else {
                block();
                $('#form_register_cart').submit();
            }
        });

        $("#btn_cart_filter").click(function() {
            $('#form_cart_filter').submit();
        });

        $(".edit-cart").click(function() {

            obj = JSON.parse($(this).attr('data-json'));
            $("#cart_id").val(obj.id);
            $("#type_cart").val(obj.type_cart);
            $("#registration_plate").val(obj.registration_plate);
            $('#transporter').val(null).trigger('change');

            if(obj.transporter_id) {
                $("#transporter").html(new Option(obj.logistics_transporter.name, obj.transporter_id, true, true)).trigger('change');
                $("#cart_is").val(1);
                $(".div-transporter").show();
                $(".div-supplier").hide();

            } else {
                $("#supplier").html(new Option(obj.logistics_supplier.name, obj.supplier_id, true, true)).trigger('change');
                $("#cart_is").val(2);
                $(".div-transporter").hide();
                $(".div-supplier").show();
            }    
            $(".modal-title-add").text('Editar carreta');
            $("#modal_add_cart").modal('show');

        });

        $("#btn_modal_cart").click(function() {

            $("#cart_id").val(0);
            $("#type_cart, #registration_plate, #vehicle_is").val('');
            $(".select-transporter").val(0).trigger('change');
            $(".select-supplier").val(0).trigger('change');
            $(".div-transporter, .div-supplier").hide();
            $(".modal-title-add").text('Cadastrar carreta');
            $("#modal_add_cart").modal('show');
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
            $("#mLogisticsTransporter").addClass('sidebar-group-active active');
            $("#mLogisticsTransporterCarts").addClass('active');
        }, 100);
    });

</script>
@endsection