@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<style>
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline {
        width: 100% !important;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline .select2-search__field {
        width: 100% !important;
    }
    .input-item {
        width: 100%;
        font-family: "IBM Plex Sans", Helvetica, Arial, serif;
        color: #475f7b;
        text-align: center;
        border: 0px;
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
                    Entrada & Saída
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="content-body">

        <section id="basic-tabs-components">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h4>Solicitação Visitante & Prestador Serviço</h4>
                    </div>
                </div>
                <form action="/logistics/request/visitor/service/edit_do" id="form_request_entry" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{$id}}">
                    <input type="hidden" name="arr_remove_items" id="arr_remove_items">
                    <input type="hidden" name="arr_schedule" id="arr_schedule">
                    <input type="hidden" name="arr_schedule_remove" id="arr_schedule_remove">                    
                    <div class="card-body" style="padding-top: 0px;">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-request" aria-controls="home" role="tab" aria-selected="true">
                                    <i class="bx bx-file align-middle"></i>
                                    <span class="align-middle">Solicitação</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-transporter" aria-controls="profile" role="tab" aria-selected="false">
                                    <i class="bx bx-user align-middle"></i>
                                    <span class="align-middle">Visitante / P.Serviço</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-schedule" role="tab" aria-selected="false">
                                    <i class="bx bx-calendar align-middle"></i>
                                    <span class="align-middle">Agendamentos</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-items" aria-controls="profile" role="tab" aria-selected="false">
                                    <i class="bx bx-cube align-middle"></i>
                                    <span class="align-middle">Itens Carregamento</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-request" role="tabpanel">  
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Razão</label>
                                            <select class="custom-select" name="type_reason" id="type_reason">
                                                <option value="" @if($type_reason == '') selected @endif>Selecione</option>
                                                <option value="3" @if($type_reason == 3) selected @endif>Visita</option>
                                                <option value="9" @if($type_reason == 9) selected @endif>Prestador de serviço</option>
                                                <option value="10" @if($type_reason == 10) selected @endif>Seleção para contratação</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Portaria</label>
											<select class="custom-select" name="entry_exit_gate_id" id="entry_exit_gate_id">    
												<option value=""></option>
                                                @foreach ($entry_exit_gate as $key)
                                                    <option value="{{ $key->id }}" @if($entry_exit_gate_id == $key->id) selected @endif>{{ $key->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Galpão</label>
											<select class="custom-select" name="warehouse_id" id="warehouse_id">    
												<option value=""></option>
                                                @foreach ($warehouse as $key)
                                                    <option value="{{ $key->id }}" @if($warehouse_id == $key->id) selected @endif>{{ $key->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Solicitante</label>
                                            <select class="form-control select-request" name="request_r_code" id="request_r_code" style="width: 100%;" multiple>
                                                @foreach ($users_request as $key)
                                                    <option value="{{ $key->r_code }}" @if($request_r_code == $key->r_code) selected @endif>{{ $key->first_name }} {{ $key->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>setor</label>
                                            <input type="text" class="form-control" id="request_sector" name="request_sector" value="{{$request_sector}}" placeholder="" readonly="readonly">
                                        </div>    
                                    </div>    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Telefone</label>
                                            <input type="text" class="form-control phone" id="request_phone" name="request_phone" value="{{$request_phone}}" placeholder="(00) 00000-0000">
                                        </div>    
                                    </div>    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Ramal</label>
                                            <input type="text" class="form-control" id="request_ramal" name="request_ramal" value="{{$request_ramal}}" placeholder="0000">
                                        </div>    
                                    </div>    
                                </div>
                                <div class="row">   
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Motivo de solicitacão</label>
                                            <textarea name="reason" id="reason" class="form-control" placeholder="Informe motivo ..."><?= $reason ?></textarea>
                                        </div>    
                                    </div>    
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-transporter" role="tabpanel">
                                <input type="hidden" name="visitor_id" value="{{$visitor_id}}">
                                <div class="row">                                       
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" class="form-control" id="visitor_name" name="visitor_name" value="{{$visitor_name}}" placeholder="Nome completo">
                                        </div>    
                                    </div>    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>RG</label>
                                            <input type="text" class="form-control" id="visitor_identity" name="visitor_identity" value="{{$visitor_identity}}"  placeholder="Informe o número do RG">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                       
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Telefone</label>
                                            <input type="text" class="form-control phone" id="visitor_phone" name="visitor_phone" value="{{$visitor_phone}}" placeholder="(00) 00000-0000">
                                        </div>    
                                    </div>    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sexo</label>
                                            <select class="custom-select" name="visitor_gender" id="visitor_gender">
                                                <option value="" @if ($visitor_gender == '') selected @endif>Selecione</option>
                                                <option value="1" @if ($visitor_gender == 1) selected @endif>Masculino</option>
                                                <option value="2" @if ($visitor_gender == 2) selected @endif>Feminino</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Empresa</label>
                                            <input type="text" class="form-control" id="visitor_company_name" name="visitor_company_name" value="{{$visitor_company_name}}"  placeholder="Informe o nome da empresa">
                                        </div>      
                                    </div>    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>CNPJ</label>
                                            <input type="text" class="form-control mask-cnpj-cpf" id="visitor_company_identity" name="visitor_company_identity" value="{{$visitor_company_identity}}" placeholder="00.000.000/0000-00">
                                        </div>      
                                    </div>    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Telefone</label>
                                            <input type="text" class="form-control phone" id="visitor_company_phone" name="visitor_company_phone"  value="{{$visitor_company_phone}}" placeholder="Telefone empresa">
                                        </div>      
                                    </div>    
                                </div>
                                <div class="row">   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Placa veículo</label>
                                            <input type="text" class="form-control" id="visitor_car_plate" name="visitor_car_plate" value="{{ $visitor_car_plate }}" placeholder="Informe a placa do veículo">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Modelo veículo</label>
                                            <input type="text" class="form-control" id="visitor_car_model" name="visitor_car_model" value="{{ $visitor_car_model }}" placeholder="Informe o modelo do veículo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-schedule" role="tabpanel">
								@if($has_analyze == 0)
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary shadow" data-toggle="modal" data-target="#modal_schedule"><i class="bx bx-plus"></i> Novo agendamento</button>
                                    </div>    
                                </div>
								@endif
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead style="text-align: center;">
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Data Liberação</th>
                                                        <th>Horário Liberação</th>
                                                        <th>Restrição</th>
                                                        <th>Encaminhamento</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table_schedule">
                                                    <tr id="tr_not_schedule">
                                                        <td colspan="6" style="text-align: center;">Não há itens adicionados</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>        
                            <div class="tab-pane" id="tab-items" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary shadow" data-toggle="modal" data-target="#modal_import_items"><i class="bx bx-import"></i> Importar itens</button>
                                    </div>    
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead style="text-align: center;">
                                                    <tr>
                                                        <th>DESCRIÇÃO</th>
                                                        <th>QUANTIDADE</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table_itens">
                                                    <tr id="tr_not_item">
                                                        <td colspan="3" style="text-align: center;">Não há itens adicionados</td>
                                                    </tr>    
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-outline-primary" id="btn_new_item" style="width:100%;">
                                                <i class="bx bx-plus"></i> Novo item
                                            </button>
                                        </div>
                                    </div>    
                                </div>    

                            </div>
                        </div>
                        @if($has_analyze == 0 && $is_approv == 0 && $is_cancelled == 0)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary" id="btn_save_request" style="width:100%;">
                                        @if($id == 0) Cadastrar @else Atualizar @endif solicitacão
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal_import_items" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Importar itens</span>
            </div>
            <div class="modal-body">
                <div class="alert border-primary alert-dismissible mb-2" role="alert">
                    <div class="d-flex align-items-center">
                        <span>
                            <a targe="_blank" href="/excell/model_import_items_visitant.xlsx" >Modelo de importação <i class="bx bxs-download"></i></a>
                        </span>
                    </div>
                  </div>
                <form method="post" action="#" id="form_import_items">
                    <div class="row">
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="price">Arquivo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file_items" id="file_items">
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
                <button type="button" class="btn btn-primary ml-1" id="btn_import_items">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Importar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>


<div class="modal fade" id="modal_schedule" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Novo agendamento</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Tipo</label>
                            <select class="custom-select" id="schedule_type">
                                <option value="">Selecione</option>
                                <option value="1">Entrada</option>
                                <option value="2">Saída</option>
                            </select>
                        </div>    
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Data liberação</label>
                            <input type="text" class="form-control date-mask" id="schedule_date" placeholder="00/00/0000">
                        </div>    
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Horário liberação</label>
                            <input type="text" class="form-control hour-work-mask" id="schedule_hour" placeholder="00:00">
                        </div>    
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Restrição</label>
                            <textarea class="form-control" id="schedule_restriction" placeholder="Informe restrição"></textarea>
                        </div>    
                    </div>        
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Encaminhamento</label>
                            <input type="text" class="form-control" id="schedule_forwarding" placeholder="Informe o local de encaminhamento">
                        </div>    
                    </div>    
                </div>    
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_add_schedule">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Adicionar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<script>

    var arr_sector =  {!! json_encode(config('gree.sector')) !!};
    var arr_items = {!! json_encode($arr_items) !!}
    var arr_remove_items = [];
    var arr_schedule = {!! json_encode($arr_schedule) !!}
    var arr_schedule_remove = [];

    $(document).ready(function () {

        if(arr_items.length > 0) {
            $("#tr_not_item").hide();
            $("#table_itens").append(reloadItems(arr_items, 2));
        }

        if(arr_schedule.length > 0) {
            $("#table_schedule").html(reloadSchedule(arr_schedule));
        }    
		
        $(".select-request").select2({
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
		
		$('.select-request').on('select2:select', function (e) {
            var data = e.params.data;
            $("#request_sector").val(arr_sector[data.sector]);
            $("#request_phone").val(data.phone);
        }); 
		
		$(".select-request").on('select2:unselect', function (e) {
            $("#request_sector, #request_phone").val('');
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
            }
        });

        $(".select-wharehouse").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione o galpão",
            language: {
                noResults: function () {
                    return 'Galpão não encontrado'; 
                }
            },
            ajax: {
                url: '/logistics/warehouse/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });


        $("#btn_new_item").click(function() {

            $("#tr_not_item").hide();
            var html = `<tr>
                            <td style="display:none;"><input class="input-item" name="items_id[]" value="0"></td>
                            <td style="padding: 5px;"><input class="input-item" name="items_description[]" placeholder="..."></td>
                            <td style="padding: 5px;"><input type="number" class="input-item" name="items_quantity[]" placeholder="0"></td>
                            <td style="padding: 5px; text-align: center;">
                                <a href="javascript:void(0);" class="remove-item" data-id="0"><i class="bx bx-trash"></i></a>
                            </td>
                        </tr>`;
            $("#table_itens").append(html);
        });

        $(document).on('click', '.remove-item', function (e){

            var id = $(this).attr('data-id');
            if(id != 0) {
                arr_remove_items.push(id);
            }   

            $(this).parent().parent().remove();
        });
    
        $("#btn_import_items").click(function() {

            if($("#file_items")[0].files.length == 0) {
                return $error('Selecione um arquivo!');
            } else {
                block();
                ajaxSend('/logistics/request/visitor/service/import/items', $("#form_import_items").serialize(), 'POST', '100000', $("#form_import_items")).then(function(result){

                    if(result.success) {
                        if(result.items.length > 0) {

                            console.log(result.items);

                            $("#tr_not_item").hide();
                            $("#table_itens").append(reloadItems(result.items, 1));
                            $("#file_items").val('');
                            $(".label-items").text('ESCOLHER ARQUIVO');
                            $("#modal_import_items").modal('hide');
                        } else {
                            return $error('O arquivo está sem dados!');
                        }
                    } 
                    unblock();

                }).catch(function(err){
                    unblock();
                    $error(err.message)
                });
            }
        });

        function reloadItems(items, type) {

            var html = '';
            items.forEach(element => {

                var description = type == 1 ? element[0] : element.description;
                var quantity = type == 1 ? element[1] : element.quantity;
                var id = type == 1 ? 0 : element.id;

                html += 
                    `<tr>
                        <td style="display:none;"><input class="input-item" name="items_id[]" value="`+id+`"></td>
                        <td style="padding: 5px;"><input class="input-item" name="items_description[]" value="`+description+`" placeholder="..."></td>
                        <td style="padding: 5px;"><input type="number" class="input-item" name="items_quantity[]" value="`+quantity+`" placeholder="0"></td>
                        <td style="padding: 5px; text-align: center;">
                            <a href="javascript:void(0);" class="remove-item" data-id="`+id+`"><i class="bx bx-trash"></i></a>
                        </td>
                    </tr>`;
            });
            return html;
        }

        $("#btn_add_schedule").click(function() {

            var type = $("#schedule_type").val();
            var date = $('#schedule_date').val();
            var hour = $('#schedule_hour').val();
            var restriction = $('#schedule_restriction').val();
            var forwarding = $('#schedule_forwarding').val();

            if(type == "") {
                return $error('Tipo de agendamento obrigatória');
            }
            else if (date == "") {
                return $error('Data de liberação obrigatória');
            }
            else if (hour == "") {
                return $error('Horário de liberação obrigatória');
            }
            else if (forwarding == "") {
                return $error('Encaminhamento obrigatória');
            }
            else {

                arr_schedule.push({
                    'id' : 0,
                    'type' : type,
                    'date' : date,
                    'hour' : hour,
                    'restriction' : restriction,
                    'forwarding' : forwarding
                });

                $("#table_schedule").html(reloadSchedule(arr_schedule));
                $("#modal_schedule").modal('hide');
                $("#schedule_type, #schedule_date, #schedule_hour, #schedule_restriction, #schedule_forwarding").val('');
            }
        });

        function reloadSchedule(arr) {

            var html = '';

            arr.forEach(function(element, index){

                var entry_exit = { 1 : 'Entrada', 2 : 'Saída'};
                var restriction = element.restriction != '' ? element.restriction : '-';

                html += 
                `<tr style="text-align: center;">
                    <td style="padding: 5px;">`+ entry_exit[element.type] +`</td>
                    <td style="padding: 5px;">`+ element.date +`</td>
                    <td style="padding: 5px;">`+ element.hour +`</td>
                    <td style="padding: 5px;">`+ restriction +`</td>
                    <td style="padding: 5px;">`+ element.forwarding +`</td>
                    <td style="padding: 5px; text-align: center;">
                        <a href="javascript:void(0);" class="remove-schedule" data-id="`+element.id+`" data-index="`+index+`"><i class="bx bx-trash"></i></a>
                    </td>
                </tr>`;
            });
            return html;
        }

        $(document).on('click', '.remove-schedule', function() {

            var index = $(this).attr('data-index');
            var id = $(this).attr('data-id');
            if(id != 0) {
                arr_schedule_remove.push(id);
                var type = 2;
            } else {
                var type = 1;
            }
            arr_schedule.splice(index, 1);
            $('#table_schedule').html(reloadSchedule(arr_schedule));
        });

        $("#btn_save_request").click(function() {

            if($("#type_reason").val() == "") {
                return $error('Tipo de solicitacão obrigatória');
            }
            else if ($('#entry_exit_gate_id').val() == "") {
                return $error('Portaria obrigatória');
            }
            else if ($('#request_r_code').val() == "") {
                return $error('Solicitante obrigatório');
            }
            else if ($('#request_phone').val() == "") {
                return $error('Telefone solicitante obrigatório');
            }
            else if ($('#reason').val() == "") {
                return $error('Motivo de solicitacão obrigatória');
            }
            else if ($('#visitor_name').val() == "") {
                return $error('Nome do visitante obrigatório');
            }
            else if ($('#visitor_identity').val() == "") {
                return $error('RG do vistante obrigatório');
            }
            else if ($('#visitor_phone').val() == "") {
                return $error('Telefone do visitante obrigatório');
            }
            else if ($('#visitor_gender').val() == "") {
                return $error('Sexo do vistante obrigatório');
            }
            else if (arr_schedule.length == 0) {
                return $error('Necessário adicionar ao menos um agendamento');
            }
            else {
                block();
                $("#arr_remove_items").val(JSON.stringify(arr_remove_items));
                $("#arr_schedule").val(JSON.stringify(arr_schedule));
                $("#arr_schedule_remove").val(JSON.stringify(arr_schedule_remove));
                $("#form_request_entry").submit();
            }
        });


        $('.hour-work-mask').mask('00:00', {reverse: false});
        $('.mask-cnpj-cpf').mask('00.000.000/0000-00');
        
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.phone').mask(SPMaskBehavior, spOptions);

        $(document).on("focus", ".money", function() {
            $(this).mask('000.000,0000', {reverse: true});
        });

        $('.date-mask').pickadate({
            editable: false,
            formatSubmit: 'yyyy-mm-dd',
            format: 'dd/mm/yyyy',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        });

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExit").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExitVisitorServiceList").addClass('active');
        }, 100);
    });
</script>
@endsection
