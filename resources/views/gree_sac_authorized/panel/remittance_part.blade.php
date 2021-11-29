@extends('gree_sac_authorized.panel.layout')

@section('content')

<style>
    .select2-container .select2-selection--single {
        height: 33.3px;
        border: 1px solid #d4dae3;
    }

    .alert-danger {
        color: #191919;
        background-color: #e8e87d;
        border-color: #e8e87d;
    }
</style>    

<div class="col-md-12">
    <form action="/autorizada/remessa/peca_do" id="submitForm" enctype="multipart/form-data" method="post">
        <input type="hidden" name="arr_list_parts" id="arr_list_parts">
        <div class="block block-rounded block-themed">
            <div class="block-header bg-gd-primary">
                <h3 class="block-title">Solicitação de remessa de peças</h3>
            </div>
            <ul class="nav nav-tabs nav-tabs-alt js-tabs-enabled" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#tab-attach" data-toggle="tab">Notas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab-parts" data-toggle="tab">Adicionar peças</a>
                </li>
            </ul>
            <div class="block-content tab-content">
                <div class="tab-pane active" id="tab-attach" role="tabpanel">
                    <h5 class="font-w400">Anexe os arquivos</h5>
                    <div class="form-group row">
                        <label class="col-12">Relatório técnico *</label>
                        <div class="col-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="remittance_diagnostic" name="remittance_diagnostic" data-toggle="custom-file-input">
                                <label class="custom-file-label">Escolha arquivo</label>
                            </div>
                        </div>
                    </div>
					<div class="form-group row">
                        <label class="col-12">Nota de remessa *</label>
                        <div class="col-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="remittance_note" name="remittance_note" data-toggle="custom-file-input">
                                <label class="custom-file-label">Escolha arquivo</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12">Nota de origem de compra</label>
                        <div class="col-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="purchase_origin_note" name="purchase_origin_note" data-toggle="custom-file-input">
                                <label class="custom-file-label">Escolha arquivo</label>
                            </div>
                        </div>
                    </div>
					<div class="form-group row">
                        <label class="col-12">Foto da etiqueta</label>
                        <div class="col-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="photo_tag" name="photo_tag" data-toggle="custom-file-input">
                                <label class="custom-file-label">Escolha arquivo</label>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="tab-pane" id="tab-parts" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="fa fa-fw fa-exclamation-triangle mr-10"></i>
                                <p class="mb-0">No máximo dois modelos poderão ser selecionados para adicionar as peças!</p>
                            </div>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                <label for="model">Modelo do equipamento</label>
                                <select class="select-model form-control" id="model" name="model" data-placeholder="Escolha o modelo" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-5">
                            <fieldset class="form-group">
                                <label for="part">Código/Nome da peça</label>
                                <select class="select-part form-control" id="part" name="part" data-placeholder="Escolha a peça" style="width: 100%;"></select>
                            </fieldset>    
                        </div>
                        <div class="col-2">
                            <fieldset class="form-group">
                                <label for="quantity">Quantidade</label>
                                <input type="number" min="1" class="form-control" id="quantity" name="quantity" placeholder="" value="">
                            </fieldset>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="description">Motivo de solicitação desta peça</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Informação obrigatória"></textarea>
                            </fieldset>
                        </div>    
                    </div>
                    <button type="button" id="btn_add_part" class="btn btn-sm btn-primary" style="">
                        <i class="fa fa-plus mr-5"></i>Adicionar peça
                    </button>
                    <br><hr><br>
                    <div class="block-header block-header-default" style="background-color: #e4e7ed;">
                        <h3 class="block-title">Peças Adicionadas</h3>
                    </div>
                    <div class="table-responsive push">
                        <table class="table table-bordered table-hover" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th>Modelo</th>
                                    <th>Peça</th>
                                    <th>Motivo</th>
                                    <th>Quant</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table_part" class="table_row"></tbody>
                            <tr id="part_verif">
                                <td colspan="5" class="font-w600 text-center">Não há peças adicionadas!</td>
                            </tr>
                        </table>
                    </div>
                        
                </div>
            </div>
        </div>
    </form>
    <button type="button" class="btn btn-alt-primary" id="btn_send_parts">Realizar solicitação de remessa</button>
</div>

<div class="modal" id="modal-part" tabindex="-1" role="dialog" aria-labelledby="modal-os" aria-hidden="true" style="z-index: 10000;">
    <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary">
                    <h3 class="block-title" id="title_modal"></h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="alert alert-warning alert-dismissable mb-0" role="alert">
                        <p class="mb-0" id="model_value"></p>    
                        <p class="mb-0">
                            Informe o modelo e a peça corretamente. <b>Campos obrigatórios</b>
                        </p>
                    </div><br>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="model">Modelo do equipamento</label>
                                <input type="text" class="form-control" id="modal_model" placeholder="Informe modelo">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="part">Código/Nome da peça</label>
                                <input type="text" class="form-control" id="modal_part" placeholder="Informe a peça">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="quantity">Quantidade</label>
                                <input type="number" class="form-control" id="modal_quantity" placeholder="">
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
                </div>
            </div><br>
            <div class="modal-footer">
                <button type="button" id="btn_add_part_modal" class="btn btn-alt-primary">
                    Adicionar peça
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var list_parts = [];
    var list_model = [];
    var elem_model, elem_part;
    var model_value = '';
    var part_exist = true;

    $(document).ready(function() {
        $('.select-model').select2({
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
                    $("#title_modal").text("MODELO NÃO ENCONTRADO");
                    $("#model_value").html('<b>Modelo '+model_value+' não encontrado.</b>');
                    $('#modal-part').modal('show');
                    $("#modal_model").prop("disabled", false);
                    $("#modal_model").val('');
                    return 'Nenhum resultado, foi encontrado.';
                }
            }
        });
        $('.select-part').select2();
        $('.select-model').on('select2:select', function (e) {
            var data = e.params.data;
            elem_model = data;
            $('.select-part').val(0).trigger('change');
            $('.select-part').select2({
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

                        part_exist = false;
                        $("#title_modal").text("PEÇA NÃO ENCONTRADA");
                        if(model_value == undefined) {
                            $("#model_value").html('<b>NÃO EXISTEM PEÇAS CADASTRADAS PARA ESTE MODELO!</b>');
                        } else {
                            $("#model_value").html('<b>Código/Nome da peça '+model_value+' não encontrada.</b>');
                        }
                        $("#modal_model").val(elem_model.text);
                        $("#modal_model").prop("disabled", true);
                        $('#modal-part').modal('show');
                        return 'Peça não foi encontrada!';
                    }
                }
            });
        });

        $('.select-part').on('select2:select', function (e) {
            elem_part = e.params.data;
        });

        $("#btn_add_part").click(function(e) {
            
            if(validateInput(1)) {
                addElement(1);
                clearInput(1);
                $("#table_part").html(reloadTable());
                $("#part_verif").css('display', 'none');
            }   
        });

        $("#btn_add_part_modal").click(function() {

            if(validateInput(2)) {
                addElement(2);
                clearInput(2);
                $("#table_part").html(reloadTable());
                $("#part_verif").css('display', 'none');
                $('#modal-part').modal('hide');
            }    
        });

        $("#btn_send_parts").click(function() {

            if($("#remittance_diagnostic")[0].files.length == 0) {

                return error('Anexe o relatório técnico!');
            } else if($("#remittance_note")[0].files.length == 0) {

                return error('Anexe a nota de remessa!');
            } 
            else if(list_parts.length == 0) {

                return error('Ao menos uma peça precisa ser adicionada!');
            } else {

                Swal.fire({
                title: 'Finalizar solicitação de remessa',
                text: "Verique se as peças estão corretas, se estiverem corretas confirme esta solicitação, caso contrário cancele!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        $("#arr_list_parts").val(JSON.stringify(list_parts));
                        $("#submitForm").unbind().submit();
                        Codebase.loader('show', 'bg-gd-sea');
                    }
                });
            }
        });
    });    

    /**
     * addElement() add part object to array
     * type = 1 -> part and model exists
     * type = 2 -> part and model not exist (modal)
     */
    function addElement(type) {

        var model_id = 0;
        if(part_exist) {
            model_id = type == 1 ? elem_model.id : $("#modal_model").val();
        } else {
            model_id = elem_model.id;
        }

        obj_part = {
            model: model_id,
            model_text: type == 1 ? elem_model.text : $("#modal_model").val(),
            part: type == 1 ? elem_part.id : $("#modal_part").val(),
            part_text: type == 1 ? elem_part.text : $("#modal_part").val(),
            quantity: type == 1 ? $("#quantity").val() : $("#modal_quantity").val(),
            description: type == 1 ? $("#description").val() : $("#modal_description").val(),
            not_part : type == 1 ? 0 : 1
        };

        if(type == 1) {
            if(verifyModel(elem_model.id, type)) {
                list_parts.push(obj_part);
            } else {
                error('Já existem dois modelos adicionados, só poderá adicionar novas peças a este modelos selecionados, caso contrário exclua!');
            }
        } else {
			
			if(elem_model != undefined) {
				if(verifyModel(elem_model.id, type)) {
					list_parts.push(obj_part);
				} else {
					error('Já existem dois modelos adicionados, só poderá adicionar novas peças a este modelos selecionados, caso contrário exclua!');
				}
			} else {
				list_parts.push(obj_part);
			}	
        }
        part_exist = true;
    }    

    function reloadTable() {
        var html = '';

        if(list_parts.length != 0) {
            list_parts.forEach(function (elem, i) {
                html += '<tr>';
                html += '<td>'+ elem.model_text+'</td>';
                html += '<td>'+ elem.part_text +'</td>';
                html += '<td>'+ elem.description +'</td>';
                html += '<td>'+ elem.quantity +'</td>';
                html += '<td><a onclick="deletePartTable(this)" style="cursor: pointer;color: #ad0808;" data-id="'+ i +'"><i class="si si-trash"></i></a></td>';
                html += '</tr>';
            });
            return html;    
        } else {
            $("#part_verif").css('display', '');
        }
    }

    function validateInput(type) {
        var ret = true;
        var prefix = type == 1 ? "": "modal_";

        if($("#"+prefix+"description").val() == '') {
            error('Motivo de solicitação de peça é obrigatório!');
            ret = false;
        }
        if($("#"+prefix+"quantity").val() == '') {
            error('Quantidade é obrigatório!');
            ret = false;
        }
        if($("#"+prefix+"part").val() == '' || $("#"+prefix+"part").val() == null) {
            error('código/nome da peça é obrigatório!');
            ret = false;
        }
        if($("#"+prefix+"model").val() == '' || $("#"+prefix+"model").val() == null) {
            error('Modelo do equipamento é obrigatório!');
            ret = false;
        }
        return ret;
    }

    function clearInput(type) {

        var prefix = type == 1 ? "": "modal_";
        $("#"+prefix+"model").val(null).trigger('change');
        $("#"+prefix+"part").val(null).trigger('change');
        $("#"+prefix+"quantity").val('');
        $("#"+prefix+"description").val('');
        $("#model").val(null).trigger('change');
    }

    function deletePartTable(el) {
        var index = $(el).attr('data-id');
        var number_model = list_parts[0].model;

        list_parts.splice(index, 1);
        $(el).parent().parent().remove();
        $("#table_part").html(reloadTable());

        var arr_part = [];
        list_parts.forEach(function (elem, i) {
            arr_part.push(elem.model);
        });    

        if(!list_model.every(r => arr_part.includes(r))){

            for (var i = list_model.length - 1; i >= 0; i--) {
                if (list_model[i] === number_model) {
                    list_model.splice(i, 1);
                }
            }
        }
    }

    function verifyModel(model_id, type) {

        if(list_parts.length == 0) {
            list_model.push(model_id);
        }

        if(list_parts.length != 0) {
            list_parts.forEach(function (elem, i) {
                if(elem.model != model_id && list_model.length < 2) {    
                    list_model.push(model_id);
                }
            }); 

            if(list_model.includes(model_id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

</script>
@endsection