@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">QR Code</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de Solicitações para aprovar
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        
        <div class="users-list-filter px-1">
            <form action="{{ Request::url() }} " id="searchTrip" method="GET">
                
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="code">Code</label>
                        <fieldset class="form-group">
                            <input type="text" name="code" value="<?= Session::get('filter_code') ?>" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="identity">CPF/CNPJ</label>
                        <fieldset class="form-group">
                            <input type="text" name="identity" id="identity" value="<?= Session::get('filter_identity') ?>" class="form-control identity">
                        </fieldset>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="start_date">Data de Criação (inicial)</label>
                        <fieldset class="form-group">
                            <input type="text" name="start_date" value="<?= Session::get('filter_start_date') ?>" class="form-control date-mask js-flatpickr js-flatpickr-enabled flatpickr-input">

                        </fieldset>
                    </div>
                    
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="end_date">Data de Criação (final)</label>
                        <fieldset class="form-group">
                            <input type="text" name="end_date" value="<?= Session::get('filter_end_date') ?>" class="form-control date-mask js-flatpickr js-flatpickr-enabled flatpickr-input">
                        </fieldset>
                    </div>
                    
                    
                   
                    <div class="col-12 col-sm-12 col-lg-12 d-flex align-items-center">
                        <button type="submit" value="0" name="export" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                   
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>#</th>
                                            <th>Nome do Cliente</th>
                                            <th>CPF/CNPJ</th>
                                            <th>Telefones</th>
                                            <th>e-mail</th>
                                            <th></th>
                                            <th></th>
                                            
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($qrcode as  $index => $item)

                                            <tr class="cursor-pointer showDetails">
                                                
                                                <td style="width: 1%">
                                                    <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                                </td>
                                                <td>{{$item->code}}</td>
                                                <td>{{$item->full_name}}</td>
                                                <td>{{$item->identity}}</td>
                                                <td>{{$item->phone_1}} <br> {{$item->phone_2}}</td>
                                                
                                                <td colspan="3">{{$item->email}}</td>
                                                

                                                <td class="no-click">
                                                    <div class="dropleft">
                                                        <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            
                                                            <a class="dropdown-item" href="javascript:void(0)" json-data="<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>" onclick="showEdit(this)"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                            <a class="dropdown-item" onclick="viewAnalyze('', <?= $item->id ?>)" href="javascript:void(0)"><i class="bx bx-file-blank mr-1"></i> Fazer Análise</a>
                                                            <a onclick="seeAnalyzes(<?= $item->id ?>);" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style="display:none" class="seq_{{$index+1}} group">
                                                <td colspan="9">
                                                    <table class="table table-striped mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 14%"><b>Campanha:</b></td>
                                                                <td>{{$item->name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 14%"><b>Data de Compra:</b></td>
                                                                    @if($item->buy_date != "0000-00-00 00:00:00")
                                                                        <td>{{date('d/m/Y H:i', strtotime($item->buy_date))}}</td>
                                                                    @else
                                                                        <td></td>
                                                                    @endif
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 14%"><b>Nota Fiscal:</b></td>
                                                                <td>
                                                                    <a target="_self" data-toggle="popover" data-content="Nota Fiscal" onclick="windowOpen('{{$item->nf_file}}')" href="#" data-original-title="" title=""><i class="bx bxs-file-image mr-1"></i></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 14%"><b>Numero Nota:</b></td>
                                                                <td>{{$item->nf_number}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 14%"><b>e-mail:</b></td>
                                                                <td>{{$item->email}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 14%"><b>Valor da compra:</b></td>
                                                                <td>R$ {{number_format($item->total, 2, ',', '.')}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $qrcode->appends(getSessionFilters()[0]->toArray())->links(); ?>
                                    </ul>
                                </nav>
                            </div>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>
</div>


    <div class="modal fade text-left" id="modal-analyze" tabindex="-1" role="dialog" aria-labelledby="modal-analyze" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            <h5 class="modal-title white" id="modal-analyze">REALIZAR ANÁLISE</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
            </button>
            </div>
            <form action="/qr_code/analyze" id="analyzepart" method="post">
                <input type="hidden" value="0" id="id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <fieldset class="form-group">
                                <label for="description">Informe análise feita</label>
                                <textarea class="form-control" id="description" name="description" rows="6" placeholder="..."></textarea>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="description">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option></option>
                                    <option value="1">Aprovado</option>
                                    <option value="2">Reprovado</option>
                                </select>
                            </fieldset>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-success ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">ENVIAR</span>
                </button>
                </div>
            </form>
        </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-update" tabindex="-1" role="dialog" aria-labelledby="modal-update" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary white">
            <span class="modal-title title-item" id="modal-update">
                <h5 class="modal-title white" id="modal-analyze">Dados do Cliente</h5>
            </span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
            </button>
            </div>
            
            <form action="/qr_code/edit_do" class="needs-validation" id="a_update_form" method="post" enctype="multipart/form-data">

                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="item_id" name="item_id" value="0">
                        <input type="hidden" id="id" name="id" value="0">
                        <input type="hidden" name="json_data" id="json_data" value="">
                        
                        <div class="col-sm-12">
                            <fieldset class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </fieldset>
                        </div>
                        

                        <div class="col-sm-12">
                            <fieldset class="form-group">
                                <label for="identity">CPF/CNPJ</label>
                                <input type="text" class="form-control identity" id="identity" name="identity" required>
                            </fieldset>
                        </div>

                        <div class="col-sm-6">
                            <fieldset class="form-group">
                                <label for="phone">Telefone</label>
                                <input type="text" class="form-control" id="phone_1" name="phone_1" required>
                            </fieldset>
                        </div>

                        <div class="col-sm-6">
                            <fieldset class="form-group">
                                <label for="phone_2">Telefone</label>
                                <input type="text" class="form-control" id="phone_2" name="phone_2">
                            </fieldset>
                        </div>

                        <div class="col-sm-12">
                            <fieldset class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </fieldset>
                        </div>

                        <div class="col-sm-12">
                            <fieldset class="form-group">
                                <label for="total">Valor total da compra</label>
                                <input type="total" class="form-control" id="total" name="total">
                            </fieldset>
                        </div>
                        
                        <div class="col-sm-6">
                            <label for="date">DATA DA COMPRA</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control date-mask" name="buy_date" id="buy_date" required>
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
                            <label for="nf_number">NUMERO DA NOTA</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="nf_number" name="nf_number" required>
                            </fieldset>
                        </div>

                        <div class="col-sm-12">
                            <label for="nf_file">NOTA FISCAL</label>
                            <fieldset class="form-group">
                                <input type="file" class="form-control" name="nf_file" id="nf_file">
                                <small>Esse arquivo é obrigatório.</small>
                                <br><a href="#" id="nf_file_url" style="display:none"></a>
                                
                            </fieldset>
                            
                            
                        </div>
                        
                        
                        

                        <div class="col-md-12">
                            <div class="row listmodels" style="margin-left: 2px; margin-right: 2px; margin-bottom: 20px;margin-top: 15px;">
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <label for="protocol_code">PROTOCOLO</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" name="protocol_code" id="protocol_code">
                            </fieldset>
                        </div>
                        
                        
                
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="resetFields();" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">FECHAR</span>
                    </button>
                    <button type="button" id="updoradditem" class="btn btn-success ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">ATUALIZAR</span>
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>

    <div class="modal fade" id="addmodel" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
          <div class="modal-content">
            
            <div class="modal-header bg-secondary">

              <h5 class="modal-title white">Novo modelo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="authorized">Modelo do equipamento</label>
                        <select class="form-control js-select22" style="width: 100%" id="model" name="model[]" multiple>
                        </select>
                    </fieldset>
                </div>
                <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="authorized">Número de série</label>
                        <input type="text"class="form-control" style="width: 100%" id="serie" name="serie">
                    </fieldset>
                </div>

                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="address">Endereço</label>
                        <input type="text" class="form-control" id="address" name="address">
                    </fieldset>
                </div>
                
                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="address">Complemento</label>
                        <input type="text" class="form-control" id="complement" name="complement">
                    </fieldset>
                </div>
                
              </div>
            </div>
            <div class="modal-footer">
              
              <button type="button" id="delete" index="" class="btn btn-danger ml-1" onclick="deleteModel(this)">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Remover</span>
              </button>

              <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Fechar</span>
              </button>
              
              <button type="button" id="new" class="btn btn-primary ml-1" onclick="addModel_do()">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Adicionar</span>
              </button>
              <button type="button" id="update" index="" class="btn btn-primary ml-1" onclick="updateModel(this)">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Atualizar</span>
              </button>

            </div>
          </div>
        </div>
    </div>

    <div class="customizer d-md-block text-center"><a class="customizer-close" href="#"><i class="bx bx-x"></i></a><div class="customizer-content p-2 ps ps--active-y">
        <h4 class="text-uppercase mb-0 histId"></h4>
        <small>Veja todo histórico de análises</small>
        <hr>
        <div class="theme-layouts">
        <div class="d-flex justify-content-start text-left p-1">
                <ul class="widget-timeline listitens" style="width: 100%;">
                </ul>
        </div>
        </div>
        
        <!-- Hide Scroll To Top Ends-->
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 754px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 590px;"></div></div></div>
    </div>



    <script>
        function seeAnalyzes(id) {
            block();
            $.ajax({
                type: "GET",
                url: "/misc/modeule/timeline/5/" + id,
                success: function (response) {
                    unblock();
                    if (response.success) {

                        if (response.history.length > 0) {
                            $(".histId").html(response.code);
                            var list = '';
                            
                            for (let i = 0; i < response.history.length; i++) {
                                var obj = response.history[i];
                                var status;
                                if (obj.type == 1) {
                                    status = 'Aprovado';
                                } else if (obj.type == 2) {
                                    status = 'Reprovado';
                                } else if (obj.type == 3) {
                                    status = 'Suspenso';
                                } else if (obj.type == 4) {
                                    status = 'Aguardando aprovação';
                                }

                                list += '<li class="timeline-items timeline-icon-'+ obj.status +' active">';
                                if (obj.type != 4) {
                                    list += '<div class="timeline-time">'+ obj.created_at +'</div>';
                                } else {
                                    list += '<div class="timeline-time">--</div>';
                                }
                                

                                for (let index = 0; index < obj.users.length; index++) {
                                    var obj_users = obj.users[index];

                                    list += '<h6 class="timeline-title"><a target="_blank" href="/user/view/'+ obj_users.r_code +'">'+ obj_users.name +'</a></h6>';
                                    
                                }
                                
                                list += '<p class="timeline-text">'+ obj.sector +': <b>'+ status +'</b></p>';
                                if (obj.type != 4 && obj.message != null && obj.message != "") {
                                    list += '<div class="timeline-content">'+ obj.message +'</div>';
                                }
                                list += '</li>';
                                
                            }

                            $(".listitens").html(list);
                            $($(".customizer")).toggleClass('open');

                        } else {
                            error('Ainda não foi enviado para análise.');
                        }

                    } else {
                        error(response.msg);
                    }
                }
            });
        }

        function viewAnalyze(desc = '', id = 0) {
            $("#description").val('');
            if (desc != '') {
                var nwstr = desc.replace(/\+/g,' ');
                $("#description").val(nwstr);
            }
            
            
            $("#modal-analyze").find("#id").val(id);
            $("#modal-analyze").modal();
        }

        function addModel() {
            $("#addmodel").modal();

            $('#model').val(0).trigger('change');
            $("#addmodel").find("#serie").val("");
            $("#addmodel").find("#address").val("");
            $("#addmodel").find("#complement").val("");
            
            $("#addmodel").find("#delete").hide();
            $("#addmodel").find("#update").hide();
            
            $("#addmodel").find("#new").show();

        }

        function addModel_do() {

            var _model = $('#model').select2('data');
            if (_model[0] == undefined) {

                return error('Você precisa escolher o modelo');
            } else if ($('#serie').val() == "") {

                return error('Você precisa informar o número de série');

            } else if ($('#address').val() == "") {

                return error('Você precisa informar o endereço');
            }
            for(let i = 0; i < models.length; i++) {
                let arrayObj = models[i];
                if (arrayObj.serial_number == $('#serie').val()) {

                    error('Você já adicionou esse número de série com esse modelo.');
                    $('#serie').val('');
                    return;
                }
            }

            models.push({
                "id" : 0,
                "product_id" : _model[0].id,
                "model_name" : _model[0].text,
                "serial_number" : $('#serie').val(),
                "address" : $('#address').val(),
                "complement" : $('#complement').val(),
            });
            $('#model').val(0).trigger('change');
            $('#serie').val('');
            reloadModels();
            $("#addmodel").modal('toggle');

        }

        function addModelEdit(index) {
            var json_item = models[index];

            $("#addmodel").find("#model").select2('destroy');
            $("#addmodel").find("#model").html('<option value="'+json_item.product_id+'" selected>'+json_item.model_name+'</option>');
            
            $(".js-select22").select2({
                maximumSelectionLength: 1,
                ajax: {
                    url: '/misc/sac/product/',
                    data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                    }
                }
            });

            $("#addmodel").find("#serie").val(json_item.serial_number);
            $("#addmodel").find("#address").val(json_item.address);
            $("#addmodel").find("#complement").val(json_item.complement);
            
            let btDelete = $("#addmodel").find("#delete");
            btDelete.attr('index', index);
            btDelete.show();
            
            let btUpdate = $("#addmodel").find("#update");
            btUpdate.attr('index', index);
            btUpdate.show();

            $("#addmodel").find("#new").hide();
            

            
            
            $("#addmodel").modal();
        }

        function deleteModel(elem) {

            let index = $(elem).attr("index");
            
            Swal.fire({
                    title: 'Tem certeza disso?',
                    text: "Você irá remover o modelo em anexo!",
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
                        models.splice(index, 1);
                        $("#addmodel").modal('toggle');
                        reloadModels();
                        Swal.fire(
                        {
                            type: "success",
                            title: 'Removido',
                            text: 'Modelo foi removido, para as alterações serem efetivadas, precisa atualizar atendimento.',
                            confirmButtonClass: 'btn btn-success',
                        }
                        )
                    }
            })
            

        }

        function updateModel(elem) {
            
            let index = $(elem).attr("index");

            let valid_objs = models[index];

            var _model = $('#model').select2('data');
            if (_model[0] == undefined) {

                return error('Você precisa escolher o modelo');
            } else if ($('#serie').val() == "") {

                return error('Você precisa informar o número de série');

            } else if ($('#address').val() == "") {

                return error('Você precisa informar o endereço');
            }

            if( valid_objs.product_id != _model[0].id || 
                valid_objs.model_name != _model[0].text || 
                valid_objs.serial_number != $('#serie').val() || 
                valid_objs.address != $('#address').val() || 
                valid_objs.complement != $('#complement').val() ){
                

                Swal.fire({
                    title: 'Tem certeza disso?',
                    text: "Você irá atualizar os dados deste modelo!",
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


                        for(let i = 0; i < models.length; i++) {
                            let arrayObj = models[i];
                            if (arrayObj.serial_number == $('#serie').val() && i!=index ) {
                                error('Você já adicionou esse número de série com esse modelo.');
                                return;
                            }
                        }

                        models[index].product_id = _model[0].id;
                        models[index].model_name = _model[0].text;
                        models[index].serial_number = $('#serie').val();
                        models[index].address = $('#address').val();
                        models[index].complement = $('#complement').val();

                        $('#model').val(0).trigger('change');
                        $('#serie').val('');
                        reloadModels();
                        
                        Swal.fire(
                        {
                            type: "success",
                            title: 'Atualizado',
                            text: 'Modelo foi atualizado, para as alterações serem efetivadas, precisa atualizar atendimento.',
                            confirmButtonClass: 'btn btn-success',
                        });
                        
                    }
                })

                
                //$("#json_data").val(JSON.stringify(models));
            }

            $("#addmodel").modal('toggle');

        }

        function reloadModels() {
            let list = "";
            for(let i = 0; i < models.length; i++) {
                let arrayObj = models[i];
                
                list += '<div class="col-12 mt-1 bg-primary bg-lighten-1 text-white cursor-pointer" style="padding: 15px;margin-right: 25px;border-radius: 10px; height: auto;" onclick="addModelEdit('+ i +');">';

                list += '<i class="bx bx-edit-alt" style="position: absolute;right: 10px;top: 7px;"></i>';
                list += '<p style="margin: 0;"><b>Modelo:</b> '+ arrayObj.model_name +'';
                list += '<br><b>N Série:</b> '+ arrayObj.serial_number +' </p>';
                list += '<br><b>Endereço:</b> '+ arrayObj.address +'';
                list += '<br><b>Complemento:</b> '+ arrayObj.complement +'';
                list += '</div>';
            }

            list += '<div class="col-12 mt-1 bg-secondary bg-lighten-1 text-center text-white cursor-pointer" style="display: flex;justify-content: center;flex-direction: column; border-radius: 10px; height: auto;" onclick="addModel();">';
            list += '<p style="margin: 0;">Novo modelo';
            list += '<br><i style="font-size: 18px" class="bx bx-plus-circle"></i>';
            list += '</div>';

            $(".listmodels").html(list);
            $("#json_data").val(JSON.stringify(models));
        }

        function windowOpen(url, name) {
            var win =  window.open(url, name, 'width=650,height=600');
        }

        function resetFields() {
            $("#a_update_form").find("#full_name").val("");
            $("#a_update_form").find("#identity").val("");
            $("#a_update_form").find("#phone").val("");
            $("#a_update_form").find("#phone_2").val("");
            $("#a_update_form").find("#email").val("");
<<<<<<< HEAD
=======
            $("#a_update_form").find("#total").val("");
            $("#a_update_form").find("#address").val("");
>>>>>>> master
            $("#a_update_form").find("#buy_date").val(null);
            $("#a_update_form").find("#nf_number").val("");
            $("#a_update_form").find("#nf_file").val("");
            $("#a_update_form").find("#protocol_code").val("");

            
            $("#addmodel").find("#address").val("");
            $("#addmodel").find("#complement").val("");

            models = new Array();
            
        }

        function showEdit(elem) {
            
            let json_row = JSON.parse($(elem).attr("json-data"));

            $("#a_update_form").find("#id").val(json_row.id );
            $("#a_update_form").find("#full_name").val(json_row.full_name );
            $("#a_update_form").find("#identity").val(json_row.identity);
            $("#a_update_form").find("#phone_1").val(json_row.phone_1);
            $("#a_update_form").find("#phone_2").val(json_row.phone_2);
            $("#a_update_form").find("#email").val(json_row.email);

            $("#a_update_form").find("#total").val(json_row.total);
            $("#a_update_form").find("#address").val(json_row.address);
            
            if(json_row.buy_date != "0000-00-00 00:00:00"){
                $("#a_update_form").find("#buy_date").val(moment(json_row.buy_date).format('YYYY-MM-DD'));
            }else{
                 $("#a_update_form").find("#buy_date").val(null);
            }
            

            $("#a_update_form").find("#nf_number").val(json_row.nf_number);
            
            $("#a_update_form").find("#protocol_code").val(json_row.protocol_code);
            
            if (json_row.nf_file != null) {
                $("#nf_file_url").show();
                $("#nf_file_url").attr('href', json_row.nf_file);
                $("#nf_file_url").html("Nota Fiscal");
            } else {
                $("#nf_file_url").hide();
            }
            
            models = json_row.products;
            reloadModels()
            $("#modal-update").modal();


        }

    </script>

    <script>
        var models = new Array();
        
        $(document).ready(function () {
            var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('.identity').mask(mask, options);
                }
            };

            $('#total').mask('00000.00', {reverse: true});
            $('.identity').mask('000.000.000-009', options);

            $('#phone_1').mask('(00) 0000-00009');
            $('#phone_1').blur(function(event) {
                if($(this).val().length == 15){
                    $('#phone_1').mask('(00) 00000-0009');
                } else {
                    $('#phone_1').mask('(00) 0000-00009');
                }
            });

            $('#phone_2').mask('(00) 0000-00009');
            $('#phone_2').blur(function(event) {
                if($(this).val().length == 15){
                    $('#phone_2').mask('(00) 00000-0009');
                } else {
                    $('#phone_2').mask('(00) 0000-00009');
                }
            });

            $('.date-mask').pickadate({
                formatSubmit: 'yyyy-mm-dd',
                format: 'yyyy-mm-dd',
                today: 'Hoje',
                clear: 'Limpar',
                close: 'Fechar',
                monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
                weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            });
            
            $('[data-toggle="popover"]').popover({
                placement: 'right',
                trigger: 'hover',
            });

            $(".js-select2").select2({
                maximumSelectionLength: 1,
            });
            

            $("#analyzepart").submit(function (e) { 
                
                if ( $("#analyzepart").find("#status").val() == "") {
                    e.preventDefault();
                    return error('Você precisa informar um status');
                }
                if ( $("#analyzepart").find("#description").val() == "" && $("#analyzepart").find("#status").val() == 2) {
                    e.preventDefault();
                    return error('Você precisa descrever o motivo do status ser reprovado');
                }

                $("#modal-analyze").modal('toggle');
                block();
                
            });
            
            $("#updoradditem").click(function (e) { 
                
                var form = $(".needs-validation");
                if (form[0].checkValidity() === false) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.addClass('was-validated');
                    
                }else{

                    if ($("#a_update_form").find("#buy_date").val() == 0) {
                        e.preventDefault();
                        return error("Você precisa informar a data da compra");
                    }

                    if (models.length == 0) {
                        e.preventDefault();
                        return error("Você precisa ao menos adicionar 1 endereço de instalação.");
                    }
                    $("#modal-update").modal('toggle');
                    block();

                   $("#a_update_form").submit();
                }
            });


            $(".js-select22").select2({
                maximumSelectionLength: 1,
                ajax: {
                    url: '/misc/sac/product/',
                    data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }

                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                    }
                }
            });

            $('#addmodel').on('hidden.bs.modal', function (e) {

                $('body').addClass('modal-open');

            });

        });

        $('.showDetails td').not('.no-click').click(function (e) { 
            e.preventDefault();
            $(this).parent().next().toggle();

            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
            
        });

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mQRCode").addClass('sidebar-group-active active');
            $("#mQRCodeListApprov").addClass('active');
        }, 100);

    </script>

@endsection