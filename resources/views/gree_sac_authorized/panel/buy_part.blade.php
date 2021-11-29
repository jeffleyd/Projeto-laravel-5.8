@extends('gree_sac_authorized.panel.layout')

@section('content')

<style>
    .select2-container .select2-selection--single {
        height: 33.3px;
        border: 1px solid #d4dae3;
    }
    .pre-obs {
        font-size: 15px;
        color: #646464;
    }
    .order-obs, .table-options {
        display: none;
    }
</style>    

<div class="col-md-12">
    <div class="block block-rounded block-themed">
        <div class="block-header bg-gd-primary">
                <h3 class="block-title">Solicitação de cotação de peças</h3>
        </div>
        <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="model">Modelo do equipamento</label>
                            <select class="select-model form-control" id="model" name="model" data-placeholder="Escolha o modelo"></select>
                        </div>
                    </div>
                    <div class="col-4">
                        <fieldset class="form-group">
                            <label for="part">Código/Nome da peça</label>
                            <select class="select-part form-control" id="part" name="part" data-placeholder="Escolha a peça"></select>
                        </fieldset>    
                    </div>
                    <div class="col-4">
                        <fieldset class="form-group">
                            <label for="quantity">Quantidade</label>
                            <input type="number" min="1" class="form-control" id="quantity" name="quantity" placeholder="" value="">
                        </fieldset>    
                    </div>
                    <!-- <div class="col-2">
                        <fieldset class="form-group">
                            <label for="price">Preço</label>
                            <input type="text" class="form-control" id="price" name="price" placeholder="" value="" disabled>
                        </fieldset>    
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 form-group">
                        <label for="picture">Imagem para identificação <small>Max. 5mb</small></label>
                        <br><input type="file" id="picture" name="picture" accept="image/png, image/jpeg, application/pdf">
                    </div>    
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label for="description">Descrição da peça</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="" value="">
                        </fieldset>
                    </div>    
                </div>
                <button type="button" id="btn_add_part" class="btn btn-sm btn-primary" style="">
                    <i class="fa fa-plus mr-5"></i>Adicionar peça
                </button>
        </div>
    </div>
    
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Peças adicionadas</h3>
            <div class="block-options">
                <button type="button" id="btn-modal-obs" class="btn btn-sm btn-alt-primary">
                    <i class="si si-note"></i> Adicionar observação
                </button>
            </div>
        </div>
        <div class="block-content">
            <div class="table-responsive push">
                <table class="table table-bordered table-hover" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;"></th>
                            <th>Peça</th>
                            <th class="text-center" style="width: 90px;">Quant</th>
                            <!--<th class="text-right" style="width: 120px;">Unid</th>
                            <th class="text-right" style="width: 120px;">Total</th>-->
                            <th class="table-options" style="width: 10px;"></th>
                        </tr>
                    </thead>
                    <tbody class="table_row">
                    </tbody>
                    <tr id="part_verif">
                        <td colspan="5" class="font-w600 text-center">Não há peças adicionadas!</td>
                    </tr>   
                    <!--<tr class="table-success" id="descont_order" style="display:none;">
                        <td colspan="4" class="font-w700 text-uppercase text-right">Desconto <span id="pct"></span></td>
                        <td class="font-w700 text-right" id="resale"></td>
                        <td class="table-options" style="width:10px;"></td>
                    </tr> 
                     <tr class="table-warning">
                        <td colspan="4" class="font-w700 text-uppercase text-right">Subtotal</td>
                        <td class="font-w700 text-right" id="subtotal"></td>
                        <td class="table-options" style="width:10px;"></td>
                    </tr> 
                    <tr class="order-obs">
                        <td colspan="6" class="font-w600 text-uppercase text-center">OBSERVAÇÃO ADICIONAL</td>
                    </tr>   --> 
                    <tr class="order-obs">
                        <td colspan="6"><pre id="tr_obs_order" class="pre-obs"></pre></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <form action="/autorizada/comprar/peca_do" id="submitForm" enctype="multipart/form-data" method="post">
        <input type="hidden" id="group" name="group" value="">
        <input type="hidden" id="optional" name="optional" value="">
        <button type="submit" class="btn btn-alt-primary">Realizar pedido de cotação</button>
    </form>
    <div class="modal fade" id="modal-part" tabindex="-1" role="dialog" aria-labelledby="modal-os" aria-hidden="true" style="z-index: 10000;">
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
                                Informe o modelo e a peça corretamente. <b>Campos obrigatórios</b> (Exceto imagem)
                            </p>
                        </div><br>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="model">Modelo do equipamento</label>
                                    <input type="text" class="form-control" id="modal_model" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="part">Código/Nome da peça</label>
                                    <input type="text" class="form-control" id="modal_part" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="quantity">Quantidade</label>
                                    <input type="text" class="form-control" id="modal_quantity" placeholder="" value="1" >
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-md-12 col-sm-12 form-group">
                                <label for="picture">Imagem para identificação <small>Max. 5mb</small></label>
                                <br><input type="file" id="modal_picture" accept="image/png, image/jpeg, application/pdf">
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Descrição da peça</label>
                                    <input type="text" class="form-control" id="modal_description" placeholder="" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_add_part_modal" class="btn btn-alt-primary">
                        Adicionar peça
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-order-obs" tabindex="-1" role="dialog" aria-labelledby="modal-os" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary">
                        <h3 class="block-title">OBSERVAÇÃO ADICIONAL</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="optional">Escreva a observação referente a solicitação desta cotação</label>
                                    <textarea name="modal_optional" rows="5" id="modal_optional" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_add_obs" class="btn btn-alt-primary">
                        Adicionar observação
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</div>
<script type="text/javascript">
    var amount_total = 0;
    var list_table = [];
    var elem_model, elem_part; 
    var sub_total = 0.00;
    var model_value = '';
    var part_exist = true;
    var resale = <?= $resale ?>;
    var type_author = <?= $type ?>;

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
                    $("#model_value").html('Modelo <b>'+model_value+'</b> não encontrado.');
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
                        $("#model_value").html('Código/Nome da peça <b>'+model_value+'</b> não encontrada.');
                        $("#modal_model").val(elem_model.text);
                        $("#modal_model").prop("disabled", true);
                        $('#modal-part').modal('show');
                        return 'Peça não foi encontrada!';
                    }
                }
            });
        });
        $('.select-part').on('select2:select', function (e) {
            $("#price").val(e.params.data.amount.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
            $("#quantity").val(1);
            amount_total = e.params.data.amount;
            elem_part = e.params.data;
        });  
        
        $("#quantity").keyup(function(e) {
            var qnt = $(this).val();
            if(qnt != '' && qnt != 0) {
                var sum_total = qnt*amount_total;
                $("#price").val(sum_total.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
            } else {
                $("#price").val(amount_total.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
            }
            if(qnt == 0 && qnt != '') {
                $("#quantity").val(1);
            }
        });

        $("#btn_add_part").click(function(e) {
            
            if(validateInput(1)) {

                if ($("#picture").val() != '') {
                    getBase64($("#picture"), 1);
                } else {
                    addElement(1, null);
                    reloadtable();
                    clearInput(1);
                }
            }    
        });

        $("#btn_add_part_modal").click(function() {

            if(validateInput(2)) {

                if ($("#modal_picture").val() != '') {
                    getBase64($("#modal_picture"), 2);
                } else {
                    addElement(2, null);
                    reloadtable();
                    clearInput(2);
                }
                $('#modal-part').modal('hide');
            }    
        });    

        $("#btn-modal-obs").click(function() {
            $('#modal-order-obs').modal('show');
        });

        $("#btn_add_obs"). click(function() {
            $("#optional").val($("#modal_optional").val());
            $("#tr_obs_order").html($("#modal_optional").val());
            $('#modal-order-obs').modal('hide');
            $(".order-obs").show();
        });

        $("#submitForm").submit(function (e) {
           
            if(list_table.length == 0) {
                e.preventDefault();
                return error('Necessário adicionar peças para realizar pedido de cotação!');
            } else {
                e.preventDefault();
                Swal.fire({
                title: 'Finalizar pedido',
                text: "Deseja enviar essa solicitação de pedido de peças!",
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
                        $("#submitForm").unbind().submit();
                        Codebase.loader('show', 'bg-gd-sea');
                    }
                });
            }
        }); 
    });  

    function reloadtable() {

        if(list_table.length == 0) {
            $("#part_verif").show();
        }

        $("#group").val(JSON.stringify(list_table));

        $(".table_row").html('');
        var price = 0;
        for (let index = 0; index < list_table.length; index++) {
            const arr = list_table[index];
            
            var total_row = index + 1;
            var amount =  arr.amount*arr.quantity;

            var tr_table = '';
            tr_table += '<tr class="tr_table">';
            tr_table += '<td class="text-center" style="vertical-align: middle;">'+total_row+'</td>';
            tr_table += '<td>';
            tr_table += '<p class="font-w600 mb-5">Modelo: '+arr.model_text+'</p>';
            tr_table += '<div class="text-muted">Peça: '+arr.part_text+'</div>';
            tr_table += '<div class="text-muted">Descrição: '+arr.description+'</div>';
            tr_table += '</td>';
            tr_table += '<td class="text-center" style="vertical-align: middle;">';
            tr_table += '<span class="badge badge-pill badge-primary">'+arr.quantity+'</span>';
            tr_table += '</td>';
            //tr_table += '<td class="text-right" style="vertical-align: middle;">'+arr.amount.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"})+'</td>';
            //tr_table += '<td class="text-right" style="vertical-align: middle;">'+amount.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"})+'</td>';
            tr_table += '<td style="width: 10px;vertical-align: middle;">';
            tr_table += '<a type="button" class="btn btn-sm btn-secondary" onclick="deletePart('+index+')">';
            tr_table += '<i class="fa fa-times"></i>';
            tr_table += '</a>';
            tr_table += '</td>';
            tr_table += '</tr>';
            $(".table_row").append(tr_table);

            price = amount + price;
        }
        
        var total_order;
        if(type_author == 3) {
            var descont = (price*resale)/100;
            total_order = price - descont;
            $("#descont_order").show();
			$('#pct').html('('+resale + '%)')
            $("#resale").text(descont.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
        } else {
            total_order = price;
        }
        $("#subtotal").text(total_order.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
    }

    function getBase64($this, type) {

        if ($this[0].files) {
            var FR = new FileReader();
            FR.addEventListener("load", function(e) {
                var elem = {};
                addElement(type, e.target.result);
                reloadtable();
                clearInput(type);
            });
            FR.readAsDataURL($this[0].files[0])
        }
    }

    function addElement(type, picture) {

        if(type == 1) {
            for (let index = 0; index < list_table.length; index++) {
                const arr = list_table[index];
                if (arr.part == elem_part.id && arr.model == elem_model.id && arr.exist == 1) {
                    arr.quantity = arr.quantity + 1;
                    arr.description = $("#description").val();
                    return;
                }
            }
        }

        var elem = {};
        if(part_exist) {
            elem['model'] = type == 1 ? elem_model.id: $("#modal_model").val();
        } else {
            elem['model'] = elem_model.id;
        }
        elem['model_text'] = type == 1 ? elem_model.text : $("#modal_model").val();
        elem['part'] = type == 1 ? elem_part.id : $("#modal_part").val();
        elem['part_text'] = type == 1 ? elem_part.text : $("#modal_part").val();
        elem['amount'] = type == 1 ? elem_part.amount : 0;
        var quant = parseInt($("#quantity").val());
        elem['quantity'] = type == 1 ? quant : $("#modal_quantity").val();
        elem['description'] = type == 1 ? $("#description").val() : $("#modal_description").val();
        elem['picture'] = picture;
        elem['exist'] = type == 1 ? 1: 0;
        list_table.push(elem);
        $("#part_verif").hide();
        $('.table-options').show();
    }

    function clearInput(type) {

        var prefix = type == 1 ? "": "modal_";
        $("#"+prefix+"model").val(null).trigger('change');
        $("#"+prefix+"part").val(null).trigger('change');
        $("#"+prefix+"quantity").val('');
        $("#price").val('');
        $("#"+prefix+"picture").val('');
        $("#"+prefix+"description").val('');
    }

    function validateInput(type) {
        var ret = true;
        var prefix = type == 1 ? "": "modal_";

        if($("#"+prefix+"description").val() == '') {
            error('É necessário escrever a descrição da peça!');
            ret = false;
        }
        if($("#"+prefix+"quantity").val() == '') {
            error('É necessário digitar a quantidade!');
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

    function deletePart(value) { 
        list_table.splice(value, 1);
        reloadtable();
    }
</script>
@endsection