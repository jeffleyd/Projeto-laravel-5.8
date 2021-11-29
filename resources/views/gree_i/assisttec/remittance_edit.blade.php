@extends('gree_i.layout')

@section('content')
<style>
    .table th, .table td {
        padding: 1.10rem 0.8rem;
    }
    .div-cost-process:hover {
        background-color: #dfe3e7;
        cursor: pointer;
    }
</style>  
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Assitência Técnica</h5>
              <div class="breadcrumb-wrapper col-12">
                Editar peças
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
                            
                            <div class="top d-flex flex-wrap">
                                <div class="action-filters flex-grow-1">
                                    <div class="mt-1">
                                        <b>EDIÇÃO DE REMESSA {{$remittance->code}}</b> - <span style="font-weight: 400;"><a href="/sac/authorized/edit/<?= $remittance->authorized_id ?>" target="_blank"><?= $remittance->sac_authorized->name ?></a></span>
                                    </div>
                                </div>
                                <div class="actions action-btns d-flex align-items-center">
                                    <div class="dropdown invoice-filter-action">
                                        <button type="button" class="btn btn-primary shadow" data-toggle="modal" data-target="#modal_add_part">
                                            <i class="bx bx-plus"></i>
                                            Adicionar peça
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Modelo</th>
                                            <th>Peça</th>
                                            <th>Código</th>
                                            <th>Quantidade</th>
                                            <th>Motivo de Solicitação</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($remitance_parts as $key)
                                        <tr>
                                            <td>@if(isset($key->product_air['model'])) {{ $key->product_air['model'] }} @else {{ $key->model }} @endif</td>
                                            <td>@if(isset($key->parts['description'])) {{ $key->parts['description'] }} @else {{ $key->part }} @endif</td>
                                            <td>@if(isset($key->parts['code'])) {{ $key->parts['code'] }} @else - @endif</td>
                                            <td class="text-center">{{ $key->quantity }}</td>
                                            <td>
                                                <span data-toggle="tooltip" data-placement="right" title="{{ $key->description_order_part }}" style="cursor: pointer;">
                                                    <?= stringCut($key->description_order_part, 25) ?>
                                                    <i class="bx bx-info-circle" style="color: #3568df;"></i>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-outline-primary edit-part" data-id="{{$key->id}}"><i class="bx bx-edit-alt"></i></button>
                                            <td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal_add_part" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Adicionar peça</span>
            </div>
            <div class="modal-body">
                <form action="/sac/assistance/remittance/edit_do" method="POST" id="form_add_part" enctype="multipart/form-data">
                    <input type="hidden" id="parts_id" name="parts_id" value="0">
                    <input type="hidden" id="remittance_id" name="remittance_id" value="{{ $remittance->id }}">
                    <input type="hidden" name="type" value="1">

                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <span>Caso o modelo ou a peça não for encontrado, será aberto uma janela para digitar manualmente!</span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="type_action">Modelo equipamento</label>
                                <select class="form-control select-model" id="model" name="model" data-placeholder="Escolha o modelo" style="width: 100%;" multiple></select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="part">Código/Nome da peça</label>
                                <select class="select-part form-control" id="part" name="part" data-placeholder="Escolha a peça" style="width: 100%;" multiple></select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="quantity">Quantidade</label>
                                <input type="number" min="1" class="form-control" id="quantity" name="quantity" placeholder="">
                            </fieldset>  
                        </div>    
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="description">Motivo de solicitação desta peça</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Informação obrigatória"></textarea>
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
                <button type="button" class="btn btn-primary ml-1" id="btn_add_part">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Adicionar</span>
                </button>
            </div>
        </div>    
    </div> 
</div>

<div class="modal fade" id="modal-part" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1052;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Adicionar peça</span>
            </div>
            <div class="modal-body">
                <form action="/sac/assistance/remittance/edit_do" method="POST" id="form_modal_add_part" enctype="multipart/form-data">
                    <input type="hidden" id="parts_id" name="parts_id" value="0">
                    <input type="hidden" id="remittance_id" name="remittance_id" value="{{ $remittance->id }}">
                    <input type="hidden" id="model_id" name="model_id">
                    <input type="hidden" name="type" value="2">
                    <div class="alert alert-danger  mb-1" role="alert">
                        <p class="mb-0" id="model_value"></p>    
                        <p class="mb-0">
                            Informe o modelo e a peça corretamente. <b>Campos obrigatórios</b>
                        </p>
                    </div><br>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="model">Modelo do equipamento</label>
                                <input type="text" class="form-control" id="modal_model" name="modal_model" placeholder="Informe modelo">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="part">Código/Nome da peça</label>
                                <input type="text" class="form-control" id="modal_part" name="modal_part" placeholder="Informe a peça">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="quantity">Quantidade</label>
                                <input type="text" class="form-control" id="modal_quantity" name="modal_quantity" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Motivo de solicitação desta peça</label>
                                <textarea class="form-control" id="modal_description" name="modal_description" rows="3" placeholder="Informação obrigatória"></textarea>
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
                <button type="button" class="btn btn-primary ml-1" id="btn_modal_add_part">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Adicionar</span>
                </button>
            </div>
        </div>    
    </div> 
</div>

<div class="modal fade" id="edit_modal_part" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Atualizar peça</span>
            </div>
            <div class="modal-body">
                <form action="/sac/assistance/remittance/part/edit" method="POST" id="form_edit_part" enctype="multipart/form-data">
                    <input type="hidden" id="edit_parts_id" name="parts_id">
                    <input type="hidden" id="upd_not_part" name="not_part">

                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <span>Caso o modelo ou a peça não for encontrado, e seja necessário digitar manualmente clique em <i class="bx bx-edit-alt"></i></span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <div class="custom-control custom-switch custom-control-inline" style="margin-bottom: 5px">
                                    <span class="mr-1">Modelo equipamento</span>
                                    <input type="checkbox" class="custom-control-input" id="checkbox_model">
                                    <label class="custom-control-label" for="checkbox_model">
                                        <span class="switch-icon-left"><i class="bx bx-edit-alt"></i></span>
                                        <span class="switch-icon-right"><i class="bx bx-edit-alt"></i></span>
                                    </label>
                                </div>
                                <select class="form-control select-model" id="edit_model" name="model" data-placeholder="Escolha o modelo" style="width: 100%;" multiple></select>
                                <input type="text" class="form-control" id="edit_not_model" name="model" placeholder="Digite o modelo">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <div class="custom-control custom-switch custom-control-inline" style="margin-bottom: 5px">
                                    <span class="mr-1">Código/Nome da peça</span>
                                    <input type="checkbox" class="custom-control-input" id="checkbox_part">
                                    <label class="custom-control-label" for="checkbox_part" id="label_checkbox_part">
                                        <span class="switch-icon-left"><i class="bx bx-edit-alt"></i></span>
                                        <span class="switch-icon-right"><i class="bx bx-edit-alt"></i></span>
                                    </label>
                                </div>
                                <select class="select-part form-control" id="edit_part" name="part" data-placeholder="Escolha a peça" style="width: 100%;" multiple></select>
                                <input type="text" class="form-control" id="edit_not_part" name="part" placeholder="Digite o código ou nome">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="quantity">Quantidade</label>
                                <input type="number" min="1" class="form-control" id="edit_quantity" name="quantity" placeholder="">
                            </fieldset>  
                        </div>    
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="description">Motivo de solicitação desta peça</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Informação obrigatória"></textarea>
                            </fieldset>
                        </div>    
                    </div>
                </form>
            </div>    
            <div class="modal-footer">
                <button type="button" id="btn_close_modal_edit" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_edit_model_part">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Atualizar</span>
                </button>
            </div>
        </div>    
    </div> 
</div>

<script>
    
    var elem_model, elem_part;
    var modal_edit = true;
    
    $(document).ready(function () {

        $('.select-model').select2({
            maximumSelectionLength: 1,
            ajax: {
                url: '/suporte/products',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    model_value = params.term;
                    return query;
                }
            },
            language: {
                noResults: function (e) {

                    if(modal_edit == true) {
                        $("#title_modal").text("MODELO NÃO ENCONTRADO");
                        $("#model_value").html('<b>Modelo '+model_value+' não encontrado.</b>');
                        $('#modal-part').modal('show');
                        $('#modal_add_part').modal('hide');
                        $("#modal_model").prop("disabled", false);
                        $("#modal_model").val('');
                    } else {
                        return 'Nenhum resultado, foi encontrado.';
                    }    
                }
            }
        });
        $('.select-part').select2();
        $('.select-model').on('select2:select', function (e) {

            $("#edit_part").next(".select2-container").show();
            $("#edit_part").prop('disabled', false);
            $("#edit_not_part").css("display", "none").prop('disabled', true);

            var data = e.params.data;
            elem_model = data;
            $('.select-part').val(0).trigger('change');
            $('.select-part').select2({
                maximumSelectionLength: 1,
                ajax: {
                    url: '/suporte/parts?p=' + data.id,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        model_value = params.term;
                        return query;
                    }
                },
                language: {
                    noResults: function () {
                        if(modal_edit != false) {
                            $("#title_modal").text("PEÇA NÃO ENCONTRADA");

                            if(model_value == undefined) {
                                $("#model_value").html('<b>NÃO EXISTEM PEÇAS CADASTRADAS PARA ESTE MODELO!</b>');
                            } else {
                                $("#model_value").html('<b>Código/Nome da peça '+model_value+' não encontrada.</b>');
                            }
                            $("#modal_model").val(elem_model.text);
                            $("#modal_model").prop("disabled", true);
                            $('#modal-part').modal('show');
                            $('#modal_add_part').modal('hide');
                            $("#model_id").val(elem_model.id);
                        } else {
                            return 'Peça não foi encontrada!';
                        }    
                    }
                }
            });
        });

        $('.select-part').on('select2:select', function (e) {
            elem_part = e.params.data;
        });

        $("#btn_add_part").click(function() {

            if($("#model").val() == '' || $("#model").val() == null) {
                $error('Modelo do equipamento é obrigatório!');
            }
            else if($("#part").val() == '' || $("#part").val() == null) {
                $error('código/nome da peça é obrigatório!');
            }
            else if($("#quantity").val() == '') {
                $error('Quantidade é obrigatório!');
            }
            else if($("#description").val() == '') {
                $error('Motivo de solicitação de peça é obrigatório!');
            }
            else {

                Swal.fire({
                    title: 'Confirmar cadastro de peça',
                    text: 'Esta peça não poderá ser excluída, apenas poderá ser cancelada em "aprovar remessa"!',
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
                        //$('#modal-part').modal('hide');
                        $("#form_add_part").unbind().submit();
                    }
                });
            }
        });

        $("#btn_modal_add_part").click(function() {

            if($("#modal_model").val() == '' || $("#model").val() == null) {
                $error('Modelo do equipamento é obrigatório!');
            }
            else if($("#modal_part").val() == '' || $("#part").val() == null) {
                $error('código/nome da peça é obrigatório!');
            }
            else if($("#modal_quantity").val() == '') {
                $error('Quantidade é obrigatório!');
            }
            else if($("#modal_description").val() == '') {
                $error('Motivo de solicitação de peça é obrigatório!');
            }
            else {

                Swal.fire({
                    title: 'Confirmar cadastro de peça',
                    text: 'Esta peça não poderá ser excluída, apenas poderá ser cancelada em "aprovar remessa"!',
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
                        $('#modal-part').modal('hide');
                        $("#form_modal_add_part").unbind().submit();
                    }
                });
            }
        });

        $(".edit-part").click(function() {

            var data_id = $(this).attr('data-id');
            modal_edit = false;

            $("#edit_parts_id").val(data_id);

            block();
            ajaxSend('/sac/assistance/remittance/part/ajax', {data_id: data_id}, 'GET', 3000).then(function(result) {

                $('#edit_model').val(null).trigger('change');
                $('#edit_part').val(null).trigger('change');
                $("#edit_not_model").val('');
                $("#edit_not_part").val('');
                $("#label_checkbox_part").css("display", "");
				
				console.log(result.part);
              
                if(result.part.not_part == 0 && result.part.product_air != null) {
		
                    $("#edit_model").append(new Option(result.part.product_air.model, result.part.model, true, true)).trigger('change');
                    $("#edit_part").append(new Option(result.part.parts.description, result.part.part, true, true)).trigger('change');

                    $('#edit_model, #edit_part').next(".select2-container").show();
                    $('#edit_model, #edit_part').prop('disabled', false);

                    $("#edit_not_model, #edit_not_part").css("display", "none").prop('disabled', true);
                    $("#upd_not_part").val(0);

                    $("#checkbox_model").prop('checked', false);
                    $("#checkbox_part").prop('checked', false);
                }
                else if(result.part.not_part == 1 && result.part.product_air != null) {    

                    $("#edit_model").append(new Option(result.part.product_air.model, result.part.model, true, true)).trigger('change');
                    $("#edit_not_part").val(result.part.part);


                    $('#edit_model').next(".select2-container").show();
                    $('#edit_model').prop('disabled', false);

                    $("#edit_part").next(".select2-container").hide();
                    $("#edit_part").prop('disabled', true);

                    $("#edit_not_part").css("display", "").prop('disabled', false);
                    $("#edit_not_model").css("display", "none").prop('disabled', true);
                    $("#upd_not_part").val(1);

                    $("#checkbox_part").prop('checked', true);
                    $("#checkbox_model").prop('checked', false);
                } 
                else {

                    $("#edit_not_model").val(result.part.model);
                    $("#edit_not_part").val(result.part.part);

                    $("#edit_not_model, #edit_not_part").css("display", "").prop('disabled', false);
                    $('#edit_model, #edit_part').next(".select2-container").hide();
                    $('#edit_model, #edit_part').prop('disabled', true);
                    $("#upd_not_part").val(1);

                    $("#checkbox_part").prop('checked', true);
                    $("#checkbox_model").prop('checked', true);
                }

                $("#edit_quantity").val(result.part.quantity);
                $("#edit_description").val(result.part.description_order_part);
                $("#edit_modal_part").modal("show");
                unblock();

            }).catch(function(err){

                $("#edit_modal_part").modal('hide');
                unblock();
                $error(err.message);
            });             
        });

        $("#btn_edit_model_part").click(function() {

            if($("#edit_model").next(".select2-container").is(":visible") && $("#edit_model").val() == '') {
                $error('Modelo do equipamento é obrigatório!');
            }
            else if($("#edit_part").next(".select2-container").is(":visible") && $("#edit_part").val() == '') {
                $error('Código/nome da peça é obrigatório!');
            }
            else if($("#edit_not_model").is(":visible") && $("#edit_not_model").val() == '') {
                $error('Obrigatório informar o modelo!');
            }
            else if($("#edit_not_part").is(":visible") && $("#edit_not_part").val() == '') {
                $error('Obrigatório informar o código/ nome da peça!');
            }
            else if($("#edit_quantity").val() == '') {
                $error('Quantidade é obrigatório!');
            }
            else if($("#edit_description").val() == '') {
                $error('Motivo de solicitação de peça é obrigatório!');
            }
            else {

                Swal.fire({
                    title: 'Confirmar atualização da peça',
                    text: 'Deseja confirmar está atualização?',
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
                        $('#edit_modal_part').modal('hide');
                        $("#form_edit_part").unbind().submit();
                    }
                });
            }
        });

        $("#btn_close_modal_edit").click(function() {
            modal_edit = true;
            $("#edit_modal_part").modal("hide");
        });

        $("#checkbox_model").click(function() {
            
            if($(this).is(':checked')) {

                $(this).attr('checked', 'checked');
                $("#edit_not_model, #edit_not_part").css("display", "").prop('disabled', false);
                $('#edit_model, #edit_part').next(".select2-container").hide();
                $('#edit_model, #edit_part').prop('disabled', true);
                $("#label_checkbox_part").css("display", "none");
                $("#checkbox_part").prop('checked', false);
                $("#upd_not_part").val(1);
            } else {

                $(this).removeAttr('checked');
                $("#edit_not_model, #edit_not_part").css("display", "none").prop('disabled', true);
                $('#edit_model, #edit_part').next(".select2-container").show();
                $('#edit_model, #edit_part').prop('disabled', false);
                $("#label_checkbox_part").css("display", "");
                $("#upd_not_part").val(0);
            }
        });

        $("#checkbox_part").click(function() {
            if($(this).is(':checked')) {

                $(this).attr('checked', 'checked');
                $("#edit_part").next(".select2-container").hide();
                $("#edit_part").prop('disabled', true);
                $("#edit_not_part").css("display", "").prop('disabled', false);
                $("#upd_not_part").val(1);
            } else {

                $(this).removeAttr('checked');
                $("#edit_part").next(".select2-container").show();
                $("#edit_part").prop('disabled', false);
                $("#edit_not_part").css("display", "none").prop('disabled', true);
                $("#upd_not_part").val(0);
            }
        });

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mTAssist").addClass('sidebar-group-active active');
            $("#mTAssistRemittance").addClass('active');
        }, 100);

    });
    </script>
@endsection