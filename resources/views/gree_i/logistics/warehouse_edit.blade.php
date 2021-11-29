@extends('gree_i.layout')

@section('content')

<style>
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline {
        width: 100% !important;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline .select2-search__field {
        width: 100% !important;
    }
    .bx-user-plus:hover {
        color:#5a8dee;
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
                Galpões
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row"></div>
    <div class="content-body">
        <section id="basic-tabs-components">
            <div class="card">
                <div class="card-body">
                    <div class="top d-flex flex-wrap">
                        <div class="action-filters flex-grow-1">
                            <div class="dataTables_filter mt-1">
                                <h5>Cadastrar Galpão</h5>
                            </div>
                        </div>
                        <div class="actions action-btns d-flex align-items-center">
                            <div class="dropdown invoice-filter-action">
                                <button type="button" class="btn btn-primary shadow mr-1" id="btn_save_warehouse"><i class="bx bx-save"></i> Cadastrar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab_wharehouse" aria-controls="home" role="tab" aria-selected="true">
                                <i class="bx bxs-factory align-middle"></i>
                                <span class="align-middle">Galpão</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="about-tab" data-toggle="tab" href="#tab_approvers" aria-controls="about" role="tab" aria-selected="false">
                                <i class="bx bx-group align-middle"></i>
                                <span class="align-middle">Aprovadores</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="about-tab" data-toggle="tab" href="#tab_gate" aria-controls="about" role="tab" aria-selected="false">
                                <i class="bx bx-shield-alt align-middle"></i>
                                <span class="align-middle">Portarias</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab_content" aria-controls="profile" role="tab" aria-selected="false">
                                <i class="bx bx-cube align-middle"></i>
                                <span class="align-middle">Tipo de conteúdo</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_wharehouse" role="tabpanel">
                            <p> Informe os dados do galpão</p>
                            <form class="form" action="/logistics/warehouse/edit_do" method="post" id="form_warehouse">
                                <input type="hidden" name="id" id="id" value="{{$id}}">
                                <input type="hidden" name="arr_approv" id="arr_approv">
                                <input type="hidden" name="arr_gate" id="arr_gate">
                                <input type="hidden" name="arr_content" id="arr_content">
                                <input type="hidden" name="arr_observers" id="arr_observers">
                                <input type="hidden" name="arr_approv_delete" id="arr_approv_delete">
                                <input type="hidden" name="arr_observers_delete" id="arr_observers_delete">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label>Galpão</label>
                                                <input type="text" class="form-control" name="name" id="ware_name" value="{{$ware_name}}" placeholder="Informe o nome do galpão">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Endereço</label>
                                                <input type="text" class="form-control" name="address" id="ware_address" value="{{$ware_address}}" placeholder="Informe o endereço">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Cidade</label>
                                                <input type="text" class="form-control" name="city" id="ware_city" value="{{$ware_city}}" placeholder="Informe a cidade">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="company-column">Estado</label>
                                                <select class="form-control" name="state" id="ware_state">
                                                    <option value="">Selecione o estado</option>
                                                    @foreach (config('gree.states') as $key => $value)
                                                        <option value="{{ $key }}" @if($ware_state == $key) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>CEP</label>
                                                <input type="email" class="form-control" name="zipcode" id="ware_zipcode" value="{{$ware_zipcode}}" placeholder="Informe o CEP">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>        
                        </div>
                        <div class="tab-pane" id="tab_approvers" role="tabpanel">
                            <div class="row">
                                
                                <div class="col-lg-6">
                                    <div class="card widget-notification">
                                        <div class="card-header border-bottom py-75" style="padding-left: 0rem;padding-right: 0rem;">
                                            <h6>APROVADORES</h6>
                                            <div class="task-header d-flex justify-content-between align-items-center w-100">
                                                <select class="select-approv form-control" style="width: 100%;" id="r_code" name="r_code" multiple></select>
                                                <span class="dropdown ml-md-2">
                                                    <button type="button" class="btn btn-icon rounded-circle btn-light-primary" id="btn_add_approv">
                                                        <i class="bx bx-plus"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <ul class="list-group list-group-flush" id="list_approv">

                                                <li class="list-group-item list-group-item-action handle" style="padding-left: 0.5rem">    
                                                    <div class="list-left d-flex">
                                                        <div class="list-content">
                                                            <span class="list-title">Não há aprovadores adicionados!</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card widget-notification">
                                        <div class="card-header border-bottom py-75" style="padding-left: 0rem;padding-right: 0rem;">
                                            <h6>OBSERVADORES</h6>
                                            <div class="task-header d-flex justify-content-between align-items-center w-100">
                                                <select class="select-observers form-control" style="width: 100%;" id="observers_r_code" name="observers_r_code" multiple></select>
                                                <span class="dropdown ml-md-2">
                                                    <button type="button" class="btn btn-icon rounded-circle btn-light-primary" id="btn_add_observers">
                                                        <i class="bx bx-plus"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <ul class="list-group list-group-flush" id="list_observers">
                                                <li class="list-group-item list-group-item-action" style="padding-left: 0.5rem">
                                                    <div class="list-left d-flex">
                                                        <div class="list-content">
                                                            <span class="list-title">Não há observadores adicionados!</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_gate" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <p>Adicione ou cadastre a portaria</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-12">
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <select class="custom-select select-gate" style="width: 95%;" multiple></select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-icon btn-light-primary" id="btn_add_gate_table">
                                                    <i class="bx bx-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-2 col-12">
                                    <button type="button" class="btn btn-outline-primary shadow mb-1" id="btn_add_gate"><i class="bx bx-add-to-queue"></i> Nova portaria</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Portaria</th>
                                                    <th>Telefone</th>
                                                    <th>Ramal</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_gate">
                                                <tr><td colspan="4" style="text-align: center;">Não há portarias adicionadas!</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>    
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_content" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <p>Adicione os tipos de conteúdos deste galpão</p>
                                </div>    
                            </div>    
                            <div class="row">
                                <div class="col-md-12 col-12">

                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <select class="custom-select select-type-content" style="width: 95%;" multiple></select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-icon btn-light-primary" id="btn_add_arr_content">
                                                    <i class="bx bx-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <!--<div class="col-md-3 col-12">
                                    <button type="button" class="btn btn-outline-primary shadow mb-1" id="btn_add_content"><i class="bx bx-add-to-queue"></i> Novo conteúdo</button>
                                </div> -->
                            </div>    
                            <div class="row">
                                <div class="col-12 col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tipo conteúdo</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_type_content">
                                                <tr><td colspan="2" style="text-align: center;">Não há tipos de conteúdos adicionados</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal_add_content" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white modal-title-add">Cadastrar tipo de conteúdo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-sm-12">        
                        <input type="hidden" id="type_content_id" value="0">
                        <div class="form-group">
                            <label>Descrição do conteúdo</label>
                            <input type="text" class="form-control" id="type_content_description" placeholder="Informe o conteúdo">
                        </div>
                    </div>    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_save_type_content">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Salvar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add_approv" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white modal_add_approv_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-sm-12">        
                        <input type="hidden" id="user_index">
                        <div class="form-group">
                            <label>Aprovador</label>
                            <select class="select-approv-add form-control" style="width: 100%;" id="r_code_approv"  multiple></select>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_add_approv_position">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Adicionar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add_gate" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white modal-title-add">Cadastrar portaria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="gate_id" value="0">
                    <div class="col-12 col-md-12 col-sm-12">        
                        <div class="form-group">
                            <label>Nome portaria</label>
                            <input type="text" class="form-control" id="gate_name" placeholder="Informe o nome">
                        </div>
                    </div>    
                    <div class="col-12 col-md-12 col-sm-12">        
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" class="form-control phone" id="gate_phone" placeholder="(00) 00000-0000">
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-sm-12">        
                        <div class="form-group">
                            <label>ramal</label>
                            <input type="text" class="form-control" id="gate_ramal" placeholder="Informe o ramal">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_save_gate">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Salvar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>

    var arr_approv = {!! json_encode($arr_approv) !!};
    var arr_gate = {!! json_encode($arr_gate) !!};
    var arr_type_content = {!! json_encode($arr_type_content) !!};
    var arr_observers = {!! json_encode($arr_observers) !!};
    var arr_approv_verify = {!! json_encode($arr_approv_verify) !!};

    var arr_approv_delete = [];
    var arr_observers_delete = [];

    $(document).ready(function (state) {

        if(arr_approv.length > 0) {
            $('#list_approv').html(reloadApprov(arr_approv));
        }

        if(arr_gate.length > 0) {
            $("#table_gate").html(reloadGate(arr_gate));
        }    

        if(arr_type_content.length > 0) {
            $("#table_type_content").html(relodTypeContent(arr_type_content));
        }    
        if(arr_observers.length > 0) {
            $('#list_observers').html(reloadObservers(arr_observers));
        }    

        $(".select-approv, .select-approv-add, .select-observers").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione",
            language: {
                noResults: function () {
                    return 'Usuário não existe ou está desativado...';
                }
            },
            ajax: {
                url: '/users/dropdown',
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
            },
            templateSelection: formatGate
        });

        function formatGate (gate) {

            var json = encodeURIComponent(JSON.stringify(gate));
            
            $state = $(
                '<i class="bx bx-edit edit-gate-select2" style="cursor: pointer;" data-json="'+json+'"></i>'+
                '<i class="bx bx-trash delete-gate-select2" style="cursor: pointer;" data-id="'+gate.id+'"></i>'+
                '<span>'+gate.text+'<span>'
            );
            return $state;
        };

        $(".select-type-content").select2({
            placeholder: "Selecione tipo de conteúdo",
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
            templateSelection: formatState
        });

        function formatState (state) {
            $state = $(
                '<i class="bx bx-edit edit-select2" style="cursor: pointer;" data-id="'+state.id+'" data-description="'+state.text+'"></i>'+
                //'<i class="bx bx-trash delete-select2" style="cursor: pointer;" data-id="'+state.id+'"></i>'+
                '<span>'+state.text+'<span>'
            );
            return $state;
        };

        $("#btn_add_approv").click(function() {

            var value = $(".select-approv").select2('data');
            if(value.length != 0) {

                if(verifyRCodeArray(arr_approv_verify, value[0].r_code)) {
                    obj_approv = {
                        'id' : 0,
                        'r_code' : value[0].r_code,
                        'name' : value[0].text,
                        'picture' : value[0].picture,
                        'arr_approvers': null
                    };
                    arr_approv.push(obj_approv);
                    arr_approv_verify.push(value[0].r_code);
                    $('#list_approv').html(reloadApprov(arr_approv));
                }    
                $('.select-approv').val(0).trigger('change');

            } else {
                $error('Para adicionar, selecione um aprovador!');
            }
        });

        $(document).on('click', '.add-approv-position', function (e) {
            var index = parseInt($(this).attr("data-index"));
            $("#user_index").val(index);
            $(".modal_add_approv_title").text('Adicionar como ' + (index+1) + '° aprovador');
            $("#modal_add_approv").modal('show');
        });

        $("#btn_add_approv_position").click(function() {

            var value = $(".select-approv-add").select2('data');
            var index = $("#user_index").val();

            if(value.length != 0) {
                if(verifyRCodeArray(arr_approv_verify, value[0].r_code)) {
                    var obj_approv = {
                        'id' : 0,
                        'r_code' : value[0].r_code,
                        'name' : value[0].text,
                        'picture' : value[0].picture,
                    };

                    if(arr_approv[index].arr_approvers == null) {
                        arr_approv[index].arr_approvers = [];
                    }
                    
                    arr_approv[index].arr_approvers.push(obj_approv);
                    arr_approv_verify.push(value[0].r_code);
                    $('#list_approv').html(reloadApprov(arr_approv));
                } 

                $('.select-approv-add').val(0).trigger('change');
                $("#modal_add_approv").modal('hide');
            } else {
                $error('Para adicionar, selecione um aprovador!');
            }    
        });

        var drake = dragula([document.getElementById("list_approv")],{
            moves:function(el, container, handler){
                return handler.classList.contains("handle");
            }
        });
        drake.on('drop', function (el, target) {
            
            const index_old = $(el).attr("data-index");
            const index_new = [].slice.call(el.parentNode.childNodes).findIndex((item) => el === item);

            if(arr_approv.length > 0) {

                i_old = parseInt(index_old);
                i_new = parseInt(index_new);

                arr_change_position(arr_approv, i_old, i_new);
                $('#list_approv').html(reloadApprov(arr_approv));
            }
        });

        $(document).on('click', '.delete-approv', function() {
            var index = $(this).attr('data-index');
            var r_code = arr_approv[index].r_code;

            if(arr_approv[index].arr_approvers != null) {

                if(arr_approv[index].arr_approvers.length == 1) {
                    arr_approv[index] = arr_approv[index].arr_approvers[0]
                } else {
                    var new_obj = arr_approv[index].arr_approvers[0];
                    var sub_approv = arr_approv[index].arr_approvers;
                    sub_approv.splice(0, 1);
                    new_obj['arr_approvers'] = sub_approv;
                    arr_approv[index] = new_obj;
                }
            } else {
                arr_approv.splice(index, 1);
            }

            arr_approv_delete.push(r_code);

            const index_verify = arr_approv_verify.indexOf(r_code);
            if (index_verify > -1) {
                arr_approv_verify.splice(index_verify, 1);
            }
            $('#list_approv').html(reloadApprov(arr_approv));
        });

        $(document).on('click', '.delete-sub-approv', function(e) {

            var index = $(this).attr('data-index');
            var sub_index = $(this).attr('data-sub-index');
            var r_code = arr_approv[index].arr_approvers[sub_index].r_code;

            if(arr_approv[index].arr_approvers.length == 1) {
                arr_approv[index].arr_approvers = null;
            } else {
                arr_sub_approv = arr_approv[index].arr_approvers;
                arr_sub_approv.splice(sub_index, 1);
            }

            arr_approv_delete.push(r_code);

            const index_verify = arr_approv_verify.indexOf(r_code);
            if (index_verify > -1) {
                arr_approv_verify.splice(index_verify, 1);
            }
            $('#list_approv').html(reloadApprov(arr_approv));
        });

        $("#btn_add_observers").click(function() {

            var value = $(".select-observers").select2('data');
            if(value.length != 0) {

                if(verifyRCodeArray(arr_approv_verify, value[0].r_code)) {
                    obj_observers = {
                        'id' : 0,
                        'r_code' : value[0].r_code,
                        'name' : value[0].text,
                        'picture' : value[0].picture
                    };
                    arr_observers.push(obj_observers);
                    arr_approv_verify.push(value[0].r_code);
                    $('#list_observers').html(reloadObservers(arr_observers));
                }    
                $('.select-observers').val(0).trigger('change');

            } else {
                $error('Para adicionar, selecione um observador!');
            }
        });

        $(document).on('click', '.delete-observers', function() {

            var index = $(this).attr('data-index');
            var r_code = arr_observers[index].r_code;

            arr_observers_delete.push(r_code);
            arr_observers.splice(index, 1);

            $('#list_observers').html(reloadObservers(arr_observers));
        });    

        $("#btn_add_gate").click(function() {

            $("#gate_id").val(0);
            $("#gate_name, #gate_phone, #gate_ramal").val('');
            $('.select-gate').val(0).trigger('change');
            $("#modal_add_gate").modal('show');
        });

        $("#btn_save_gate").click(function() {

            var gate_id = $("#gate_id").val();
            var gate_name = $("#gate_name").val();
            var gate_phone = $("#gate_phone").val();
            var gate_ramal = $("#gate_ramal").val();

            if(gate_name == "") {
                return $error('Informe o nome da portaria');
            } 
            else if(gate_phone == "") {
                return $error('Informe o telefone da portaria');
            }
            else if(gate_ramal == "") {
                return $error('Informe o ramal da portaria');
            }
            else {
                block();
                ajaxSend(
                    '/logistics/gate/edit/ajax', 
                    {
                        id: gate_id,
                        name: gate_name,
                        phone: gate_phone,
                        ramal: gate_ramal
                    }, 
                    'POST', 3000).then(function(result) {
                        if(result.success) {

                            $("#gate_id").val(0);
                            $("#gate_name, #gate_phone, #gate_ramal").val('');
                            $("#modal_add_gate").modal('hide');

                            var data = {
                                id: gate_id,
                                text: gate_name,
                                phone: gate_phone,
                                ramal: gate_ramal
                            };

                            var option = new Option(data, true, true);
                            $('.select-gate').html(option).trigger('change');
                            $success(result.message);
                        }
                        unblock();
                    }
                ).catch(function(err){
                    unblock();
                    $error(err.message);
                });
            }    
        });

        $(document).on('click', '.edit-gate-select2', function(e){
        
            var json = JSON.parse(decodeURIComponent($(this).attr("data-json")));

            $("#gate_id").val(json.id);
            $("#gate_name").val(json.text);
            $("#gate_phone").val(json.phone);
            $("#gate_ramal").val(json.ramal);
            $('.select2-dropdown').css('position', 'initial');
            $("#modal_add_gate").modal('show');
        });

        $(document).on('click', '.delete-gate-select2', function(e){

            var gate_id = $(this).attr('data-id');
            Swal.fire({
                title: 'Excluir portaria',
                text: "Deseja confirmar a exclusão da portaria?",
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
                    ajaxSend('/logistics/gate/delete', {id: gate_id}, 'GET', 3000).then(function(result) {
                        if(result.success) {
                            $('.select-gate').val(0).trigger('change');
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

        $("#btn_add_gate_table").click(function() {

            var value = $(".select-gate").select2('data');
            var obj = {
                'id' : value[0].id,
                'name' : value[0].text,
                'phone': value[0].phone,
                'ramal': value[0].ramal
            };

            arr_gate.push(obj);
            $('.select-gate').val(0).trigger('change');
            $("#table_gate").html(reloadGate(arr_gate));
        });

        $(document).on('click', '.remove-gate-arr', function(e) {
            index = $(this).attr('data-index');
            arr_gate.splice(index, 1);
            $("#table_gate").html(reloadGate(arr_gate));
        });

        $("#btn_add_arr_content").click(function() {

            var value = $(".select-type-content").select2('data');
            var obj = {
                'id' : value[0].id,
                'description' : value[0].text
            }
            arr_type_content.push(obj);
            $('.select-type-content').val(0).trigger('change');
            $("#table_type_content").html(relodTypeContent(arr_type_content));
        });

        $("#btn_save_type_content").click(function() {

            var id = $("#type_content_id").val();
            var description = $("#type_content_description").val();

            if(description == "") {
                return $error('Informe o tipo de conteúdo!');
            } else {
                block();
                ajaxSend('/logistics/warehouse/type/content/edit/ajax', {id: id, description: description}, 'POST', 3000).then(function(result) {
                    if(result.success) {
                        
                        $("#type_content_id").val(0);
                        $("#type_content_description").val('');
                        $("#modal_add_content").modal('hide');
                        $success(result.message);

                        if(id != 0) {
                            var option = new Option(description, id, true, true);
                            $('.select-type-content').val(0).trigger('change');
                            $('.select-type-content').html(option).trigger('change');                        
                        }
                    }
                    unblock();
                }).catch(function(err){
                    unblock();
                    $error(err.message);
                });   
            }
        });

        $("#btn_add_content").click(function() {

            $("#type_content_id").val(0);
            $("#type_content_description").val("");
            $(".modal-title-add").text('Cadastrar tipo de conteúdo');
            $("#modal_add_content").modal('show');
        });

        $(document).on('click', '.edit-select2', function() {

            $("#type_content_id").val($(this).attr('data-id'));
            $("#type_content_description").val($(this).attr('data-description'));
            $(".modal-title-add").text('Editar tipo de conteúdo');
            $('.select2-dropdown').css('position', 'initial');
            $("#modal_add_content").modal('show');
        }); 

        $(document).on('click', '.delete-select2', function() {

            var id = $(this).attr('data-id');

            Swal.fire({
                title: 'Excluir tipo de conteúdo',
                text: "Deseja confirmar a exclusão do tipo de conteúdo?",
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
                    ajaxSend('/logistics/warehouse/type/content/delete', {id: id}, 'GET', 3000).then(function(result) {

                        if(result.success) {
                            $('.select-type-content').val(0).trigger('change');
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


        $(document).on('click', '.table-delete-type', function() {
            index = $(this).attr('data-index');
            arr_type_content.splice(index, 1);
            $("#table_type_content").html(relodTypeContent(arr_type_content));
        });
        
        $("#btn_save_warehouse").click(function() {

            if($("#ware_name").val() == "") {
                $error('Informe o nome do galpão');
            } 
            else if($("#ware_address").val() == "") {
                $error('Informe o endereço do galpão');
            } 
            else if($("#ware_city").val() == "") {
                $error('Informe a cidade do galpão');
            } 
            else if($("#ware_state").val() == "") {
                $error('Informe o estado do galpão');
            } 
            else if(arr_approv.length == 0) {
                $error('Adicione ao menos um aprovador');
            }
            else if(arr_gate.length == 0) {
                $error('Adicione ao menos uma portaria');
            }
            else if(arr_type_content.length == 0) {
                $error('Adicione ao menos um tipo de conteúdo');
            }
            else {

                $("#arr_approv").val(JSON.stringify(arr_approv));
                $("#arr_observers").val(JSON.stringify(arr_observers));
                $("#arr_gate").val(JSON.stringify(arr_gate));
                $("#arr_content").val(JSON.stringify(arr_type_content));
                $("#arr_approv_delete").val(JSON.stringify(arr_approv_delete));
                $("#arr_observers_delete").val(JSON.stringify(arr_observers_delete));

                $('#form_warehouse').submit();
            }
        });

        function arr_change_position(arr, old_index, new_index) {
            if(new_index < arr.length) {
                arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
            }
        };

        function verifyRCodeArray(arr, r_code) {

            var ret = true;
            arr.forEach(function(item, index){
                if(item == r_code) {
                    ret = false;
                    $error('Já adicionado na lista!');
                }
            });
            return ret;
        }

        function reloadApprov(object) {

            var html = '';

            if(object.length > 0) {

                for (var i = 0; i < object.length; i++) {
                    var row = object[i];

                    var picture = row.picture != "" ? row.picture : '/media/avatars/avatar10.jpg';
                    var position = i + 1;
                    
                    html += '<li class="list-group-item list-group-item-action handle" data-index="'+i+'">';
                    html += '    <div class="list-left d-flex">';
                    html += '        <i class="bx bx-grid-vertical cursor-move handle " style="margin-top: 10px; margin-right: 10px; margin-left: -10px;"></i>';
                    html += '        <div class="list-icon mr-1">';
                    html += '            <div class="avatar bg-rgba-primary m-0">';
                    html += '                <img class="" src="'+ picture +'" alt="img placeholder" height="38" width="38">';
                    html += '            </div>';
                    html += '        </div>';
                    html += '        <div class="list-content">';
                    html += '            <span class="list-title text-bold-500">'+ row.name +'</span>';
                    html += '            <small class="text-muted d-block">'+ position +'° Aprovador</small>';
                    html += '        </div>';
                    html += '        <div style="right: 35px; position: absolute; top: 23px;">';
                    html += '            <i class="bx bx-user-plus font-medium-1 add-approv-position" style="font-size: 1.40rem !important; cursor:pointer;" data-index="'+i+'"></i>';
                    html += '        </div>';
                    html += '        <div style="right: 10px; position: absolute; top: 25px;">';
                    html += '            <i class="bx bx-trash delete-approv font-medium-1" style="color:#ff6060; cursor:pointer;" data-index="'+i+'"></i>';
                    html += '        </div>';
                    html += '    </div>';
                    
                    if(row.arr_approvers != null) {
                        for (var j = 0; j < row.arr_approvers.length; j++) {

                            var row_sub = row.arr_approvers[j];
                            var picture2 = row_sub.picture != "" ? row_sub.picture : '/media/avatars/avatar10.jpg';

                            html += '<div class="list-left d-flex mt-1 ml-2">';
                            html += '    <div class="list-icon mr-1">';
                            html += '        <div class="avatar bg-rgba-info m-0">';
                            html += '            <img class="" src="'+ picture2 +'" alt="img placeholder" height="38" width="38">';
                            html += '        </div>';
                            html += '    </div>';
                            html += '    <div class="list-content">';
                            html += '        <span class="list-title text-bold-500">'+ row_sub.name +'</span>';
                            html += '        <small class="text-muted d-block">'+ position +'° aprovador</small>';
                            html += '    </div>';
                            html += '    <div style="position: absolute; right: 10px; margin-top: 5px;">';
                            html += '        <i class="bx bx-trash delete-sub-approv font-medium-1" style="color:#ff6060; cursor:pointer;" data-index="'+ i +'" data-sub-index="'+ j +'"></i>';
                            html += '    </div>';
                            html += '</div>';
                        }
                    }
                    html += '</li>';
                }
            } else {

                html += '<li class="list-group-item list-group-item-action handle" style="padding-left: 0.5rem">';
                html += '    <div class="list-left d-flex">';
                html += '        <div class="list-content">';
                html += '            <span class="list-title">Não há aprovadores adicionados!</span>';
                html += '        </div>';
                html += '    </div>';
                html += '</li>';
            }
            return html;
        }


        function reloadObservers(object) {

            var html = '';
            if(object.length > 0) {
                for (var i = 0; i < object.length; i++) {
                    var row = object[i];

                    var picture = row.picture != "" ? row.picture : '/media/avatars/avatar10.jpg';
                    
                    html += '<li class="list-group-item" data-index="'+i+'">';
                    html += '    <div class="list-left d-flex">';
                    html += '        <div class="list-icon mr-1">';
                    html += '            <div class="avatar bg-rgba-primary m-0">';
                    html += '                <img class="" src="'+ picture +'" alt="img placeholder" height="38" width="38">';
                    html += '            </div>';
                    html += '        </div>';
                    html += '        <div class="list-content" style="margin-top: 10px;">';
                    html += '            <span class="list-title text-bold-500">'+ row.name +'</span>';
                    html += '        </div>';
                    html += '        <div style="right: 35px; position: absolute; top: 23px;">';
                    html += '            <i class="bx bx-trash delete-observers font-medium-1" style="color:#ff6060; cursor:pointer;" data-index="'+i+'"></i>';
                    html += '        </div>';
                    html += '    </div>';
                    html += '</li>';
                }    
            } else {
                html += '<li class="list-group-item list-group-item-action handle" style="padding-left: 0.5rem">';
                html += '    <div class="list-left d-flex">';
                html += '        <div class="list-content">';
                html += '            <span class="list-title">Não há aprovadores adicionados!</span>';
                html += '        </div>';
                html += '    </div>';
                html += '</li>';
            }
            return html;
        }    

        function reloadGate(arr) {

            var html = '';
            
            if(arr.length > 0) {
                arr.forEach(function(item, index){
                    html += 
                        `<tr>
                            <td>`+item.name +`</td>
                            <td>`+item.phone +`</td>
                            <td>`+item.ramal +`</td>
                            <td><i class="bx bx-trash remove-gate-arr" data-index="`+index+`" style="color:#ff6060; cursor:pointer;"></i></td>
                        </tr>`;
                });    
            } else {
                html += `<tr><td colspan="4" style="text-align: center;">Não há portarias adicionadas!</td></tr>`;
            }    
            return html;
        }

        function relodTypeContent(arr) {

            var html = '';
            if(arr.length > 0) {
                arr.forEach(function(item, index){
                    html += 
                        `<tr>
                            <td>`+item.description+`</td>
                            <td><i class="bx bx-trash table-delete-type" data-index="`+index+`" style="color:#ff6060; cursor:pointer;"></i></td>
                        </tr>`;
                });
            } else {
                html += `<tr><td colspan="2" style="text-align: center;">Não há tipos de conteúdos adicionados!</td></tr>`;
            }    
            return html;
        }   

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
            $("#mLogisticsWarehouse").addClass('sidebar-group-active active');
        }, 100);
    });

</script>
@endsection