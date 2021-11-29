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
                Entrada & Saída
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
                        <div class="action-filters flex-grow-1 border-bottom">
                            <div class="dataTables_filter mt-1">
                                <h5>Visitante & Prestador de Serviço</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
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
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary shadow mr-1" id="btn_save_visitor" style="width: 100%;"><i class="bx bx-save"></i> Cadastrar</button>
                        </div>    
                    </div>    
                </div>
            </div>
        </section>
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

<form class="form" action="/logistics/request/visitor/service/approv/edit_do" method="post" id="form_visitor">
    <input type="hidden" name="arr_approv" id="arr_approv">
    <input type="hidden" name="arr_observers" id="arr_observers">
    <input type="hidden" name="arr_approv_delete" id="arr_approv_delete">
    <input type="hidden" name="arr_observers_delete" id="arr_observers_delete">
</form>    

<script>

    var arr_approv = {!! json_encode($arr_approv) !!};
    var arr_observers = {!! json_encode($arr_observers) !!};
    var arr_approv_verify = {!! json_encode($arr_approv_verify) !!};
    var arr_approv_delete = [];
    var arr_observers_delete = [];

    $(document).ready(function (state) {

        if(arr_approv.length > 0) {
            $('#list_approv').html(reloadApprov(arr_approv));
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

        $("#btn_save_visitor").click(function() {

            if(arr_approv.length == 0) {
                $error('Adicione ao menos um aprovador');
            }
            else {

                $("#arr_approv").val(JSON.stringify(arr_approv));
                $("#arr_observers").val(JSON.stringify(arr_observers));
                $("#arr_approv_delete").val(JSON.stringify(arr_approv_delete));
                $("#arr_observers_delete").val(JSON.stringify(arr_observers_delete));
                $('#form_visitor').submit();
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

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsWarehouse").addClass('sidebar-group-active active');
        }, 100);
    });

</script>
@endsection