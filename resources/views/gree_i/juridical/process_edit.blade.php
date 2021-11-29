@extends('gree_i.layout')

@section('content')

<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Jurídico</h5>
                    <div class="breadcrumb-wrapper col-12">Novo Processo</div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header row"></div>
    <div class="content-body">
        <form action="/juridical/process/edit_do" id="submitProcess" class="needs-validation" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="id" id="id" value="{{ $id }}">
            <section>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">INFORMAÇÕES INICIAIS DO PROCESSO</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset class="form-group">
                                        <label for="type_process">Seara</label>
                                        <select class="form-control" id="type_process" name="type_process" disabled>
                                            <option value="1" @if ($process->type_process == 1) selected @endif>Consumidor</option>
                                            <option value="2" @if ($process->type_process == 2) selected @endif>Trabalhista</option>
                                            <option value="3" @if ($process->type_process == 3) selected @endif>Cível</option>
                                            <option value="4" @if ($process->type_process == 4) selected @endif>Penal</option>
                                            <option value="5" @if ($process->type_process == 5) selected @endif>Tributário</option>
                                            <option value="6" @if ($process->type_process == 6) selected @endif>Administrativo</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shop">Número do processo</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="process_number" name="process_number" value="{{$process->process_number}}" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="btn-add-process" data-toggle="tooltip" data-placement="top" data-original-title="Adicionar processo vinculado" style="background-color: #5a8dee; cursor: pointer;">
                                                    <i class="bx bx-add-to-queue" style="color:#ffffff;"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shop">Data recebimento</label>
                                        <input type="text" class="form-control date-mask format_date" id="date_received" name="date_received" value="<?= date('d/m/Y', strtotime($process->date_received)) ?>" placeholder="__/__/____" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" id="row_number_execution" @if($process->process_number_execution != "") style="display:'';" @else style="display:none;" @endif>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <fieldset class="form-group">
                                            <label for="is_execution">Execução</label>
                                            <select class="form-control" id="is_execution" name="is_execution">
                                                <option value="1" @if ($process->is_execution == 1) selected @endif>Sim</option>
                                                <option value="0" @if ($process->is_execution == 0) selected @endif>Não</option>
                                            </select>
                                        </fieldset>
                                    </div>  
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="shop">Número do processo relacionado</label>
                                        <input type="text" class="form-control number_process" id="process_number_execution" name="process_number_execution" value="{{$process->process_number_execution}}" placeholder="0000000-00.0000.0.00.0000">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="lawyer_r_code">Advogado(a) responsável</label>
                                        <select class="form-control js-select23" id="lawyer_r_code" name="lawyer_r_code" multiple required>
                                            @foreach ($users as $key)
                                                <option value="{{ $key->r_code }}" @if($process->lawyer_r_code == $key->r_code) selected @endif>{{ $key->first_name }} {{ $key->last_name }} ({{ $key->r_code }})</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="law_firm_id">Escritório responsável</label>
                                        <select class="form-control js-select24" id="law_firm_id" name="law_firm_id" multiple required>
                                            <option value="{{ $process->law_firm_id }}" @if($process->juridical_law_firm->id == $process->law_firm_id) selected @endif>{{ $process->juridical_law_firm->name }}</option>
                                        </select>
                                    </fieldset>
                                </div>
                            </div>    
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="py-50">Partes do processo</h6>
                                </div>
                            </div>
                            <div class="row">
                                @if($process->costumer_id != 0)
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label for="law_firm_id">Requerente</label>
                                        <select class="form-control js-select2" id="costumer_id" name="costumer_id" multiple required>
                                            <option value="{{ $process->costumer_id }}" @if($process->sac_client->id == $process->costumer_id) selected @endif>{{ $process->sac_client->name }}</option>
                                        </select>
                                    </fieldset>
                                </div>  
                                @else
                                @if($process->type_process != 6) 
                                <div class="col-md-2 div-applicant" id="div_type_applicant">
                                    <fieldset class="form-group">
                                        <label for="type_applicant"><span id="span_type_applicant">Tipo {{$type_process_name[0]}}</span></label>
                                        <select class="form-control" id="type_applicant" name="type_applicant">
                                            <option value="1" @if ($process->type_applicant == 1) selected @endif>Física</option>
                                            <option value="2" @if ($process->type_applicant == 2) selected @endif>Jurídica</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4 div-applicant" id="div_identity_applicant">
                                    <fieldset class="form-group">
                                        <label><span id="span_costumer_cpf_cnpj">@if ($process->type_applicant == 1) CPF @else CNPJ @endif </span><span id="span_identity_applicant">{{$type_process_name[0]}}</span></label>
                                        <input type="text" class="form-control"  name="identity_applicant" id="identity_applicant" value="{{$process->identity_applicant}}" placeholder="000.000.000-00">
                                    </fieldset>
                                </div>
                                @endif
                                <div class="@if($process->type_process != 6) col-md-6 @else col-md-12 @endif div-applicant" id="div_name_applicant">
                                    <fieldset class="form-group">
                                        <label><span id="span_name_applicant">{{$type_process_name[0]}}</span></label>
                                        <input type="text" class="form-control"  name="name_applicant" id="name_applicant" value="{{$process->name_applicant}}" placeholder="Informe o nome">
                                    </fieldset>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-2" id="div_type_required">
                                    <fieldset class="form-group">
                                        <label for="span_type_required" id="span_type_required">Tipo {{$type_process_name[1]}}</label>
                                        <select class="form-control" id="type_required" name="type_required" required>
                                            <option value="1" @if ($process->type_required == 1) selected @endif>Física</option>
                                            <option value="2" @if ($process->type_required == 2) selected @endif>Jurídica</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4" id="div_identity_required">
                                    <fieldset class="form-group">
                                        <label><span id="span_identity_cpf_cnpj">@if ($process->type_required == 1) CPF @else CNPJ @endif </span><span id="span_identity_required"> {{$type_process_name[1]}}</span></label>
                                        <input type="text" class="form-control"  name="identity_required" id="identity_required" value="{{$process->identity_required}}" placeholder="000.000.000-00" required>
                                    </fieldset>
                                </div>
                                <div class="@if($process->type_process == 2) col-md-4 @else col-md-6 @endif" id="div_name_required">
                                    <fieldset class="form-group">
                                        <label><span id="span_name_required">{{$type_process_name[1]}}</span></label>
                                        <input type="text" class="form-control"  name="name_required" id="name_required" value="{{$process->name_required}}" placeholder="Informe o nome" required>
                                    </fieldset>
                                </div>
                                @if ($process->type_process == 2)
                                    <div class="col-md-2" id="div_worker_r_code">
                                        <fieldset class="form-group">
                                            <label><span id="span_worker_r_code">Matrícula GREE</span></label>
                                            <input type="text" class="form-control"  name="worker_r_code" id="worker_r_code" value="{{$process->worker_r_code}}" placeholder="0000">
                                        </fieldset>
                                    </div>
                                @endif
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label>Setor relacionado</label>
                                        <select class="form-control" id="sector_related" name="sector_related">
                                            <option value="">Sem relacionamento</option>
                                            <option value="1" @if ($process->sector_related == 1) selected @endif>SAC</option>
                                            <option value="2" @if ($process->sector_related == 2) selected @endif>Comercial</option>
                                        </select>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row" id="div_type_related" style="@if($process->sector_related == 0) display:none; @endif">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label>Tipo</label>
                                        <select class="form-control" name="type_sector_related" id="type_sector_related">
                                            <option value="" selected disabled>Selecione</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label>Código / Protocolo</label>
                                        <input type="text" class="form-control" name="code_sector_related" id="code_sector_related" value="{{$process->code_sector_related}}" placeholder="Informe o código / protocolo">
                                    </fieldset>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div> 
            </section>
            <section class="request-files">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">DADOS DO PROCESSO</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="type_action_id">Tipo de ação</label>
                                        <select class="form-control" id="type_action_id" name="type_action_id">
                                            <option value="">Selecione</option>
                                            @foreach ($type_action as $key)
                                                <option value="{{$key->id}}" @if($key->id == $process->juridical_type_action->id) selected @endif>{{$key->description}}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shop">Data do Ajuizamento</label>
                                        <input type="text" class="form-control date-mask format_date" id="date_judgment"  name="date_judgment" value="<?= date('d/m/Y', strtotime($process->date_judgment)) ?>" placeholder="__/__/____">
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="district_court">Vara</label>
                                        <input type="text" class="form-control" id="judicial_court" name="judicial_court" value="{{ $process->judicial_court }}" placeholder="Informe a vara judicial" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="district_court">Fórum</label>
                                        <input type="text" class="form-control" id="judicial_forum" name="judicial_forum" value="{{ $process->judicial_forum }}" placeholder="Informe o fórum" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="district_court">Comarca</label>
                                        <input type="text" class="form-control" id="district_court" name="district_court" value="{{ $process->district_court }}" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <fieldset class="form-group">
                                        <label for="state_court">Estado</label>
                                        <select class="form-control" id="state_court" name="state_court">
                                            <option value="">Selecione o estado</option>
                                            @foreach (config('gree.states') as $key => $value)
                                                <option value="{{ $key }}" @if ($key == $process->state_court) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="value_cause">Valor da causa</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">R$</span>
                                            </div>
                                            <input type="text" class="form-control money" id="value_cause" name="value_cause" value="{{ $process->value_cause }}" placeholder="0,00" required>
                                        </div>
                                    </div> 
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label for="measures_plea">Ementa / Pleitos</label>
                                        <textarea class="form-control" id="measures_plea" name="measures_plea" rows="4"><?= $process->measures_plea ?></textarea>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>          
            </section>
            <button type="submit" id="newProcess" class="btn btn-primary">Atualizar processo</button>
        </form>
    </div>
</div>

<div class="modal fade" id="addmodel" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo modelo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="model">Modelo do equipamento</label>
                        <select class="form-control js-select22" style="width: 100%" id="model" name="model[]" multiple>
                        </select>
                    </fieldset>
                </div>
                <div class="col-md-12">
                    <fieldset>
                        <label for="serie">Número de série</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="serie" name="serie">
                            <div class="input-group-append">
                            <a href="/sac/warranty/os/all?serial_number=" target="_blank" id="nmb_series_link">
                                <button class="btn btn-primary nmb_series">0</button>
                            </a>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-12 mt-1">
                    <fieldset class="form-group">
                        <label for="price">Preço da unidade</label>
                        <input type="text"class="form-control" style="width: 100%" id="price" name="price">
                        <p>Veja essa informação na nota fiscal. Informe o valor da unidade.</p>
                    </fieldset>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1 actionclick" onclick="addModel_do()">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Adicionar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row no-gutters justify-content-center">
    <div class="alert alert-primary alert-dismissible mb-2 alert-custom" role="alert" id="alert_note" style="display:none;">
        <button type="button" class="close" aria-label="Close" onclick="$('#alert_note').hide()">
            <span aria-hidden="true">×</span>
        </button>
        <div class="d-flex align-items-center">
            <span>
                <i class="bx bx-error"></i> <span id="text-header"></span><br>
                <p class="mb-0" id="text-alert" style="white-space: pre;"></p>
                <span class="mb-0 float-right" id="text-link"><a target="_blank" href="" style="color:#ffffff;"><i class="bx bx-link-external" style="top: 3px;position: relative;"></i>Veja Mais</a></span>
            </span>
        </div>
    </div>
</div>

<script>

    var type_applicant = {{ $type_applicant }};
    var sector_related = {{ $sector_related }};
    var type_sector_related = {{ $type_sector_related }};
    var options = '';

    var opt = [];
    if(sector_related == 1) {
        opt = ["Selecione","Cliente", "Assistência Técnica", "Compra de Peça"];
    } else if(sector_related == 2) {
        opt = ["Selecione", "Distribuidor"];
    }

    for (var i = 0; i < opt.length; i++) {
        var selected = type_sector_related == i ? 'selected' : '';
        options += '<option value="' + i+ '" '+ selected +'>' + opt[i] + '</option>';
    }
    $("#type_sector_related").html(options);

    $(document).ready(function () {

        $('.date-mask').pickadate({
			max: true,
            selectYears: true,
            selectMonths: true,
            editable: true,
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
 
        //$('#identity_required').mask('000.000.000-00', {reverse: false});
        //$('#identity_applicant').mask('000.000.000-00', {reverse: false});

        $("#type_required").change(function() {
            $('#identity_required').val("");
            if($(this).val() == 1) {
                $('#identity_required').mask('000.000.000-00', {reverse: false});
                $('#identity_required').attr("placeholder", "000.000.000-00");
                $("#span_identity_cpf_cnpj").text('CPF ');
            } else {
                $('#identity_required').mask('00.000.000/0000-00', {reverse: false});
                $('#identity_required').attr("placeholder", "00.000.000/0000-00");
                $("#span_identity_cpf_cnpj").text('CNPJ ');
            }    
        });

        $("#type_applicant").change(function() {
            $('#identity_applicant').val("");
            if($(this).val() == 1) {
                $('#identity_applicant').mask('000.000.000-00', {reverse: false});
                $('#identity_applicant').attr("placeholder", "000.000.000-00");
                $("#span_costumer_cpf_cnpj").text('CPF ');
            } else {
                $('#identity_applicant').mask('00.000.000/0000-00', {reverse: false});
                $('#identity_applicant').attr("placeholder", "00.000.000/0000-00");
                $("#span_costumer_cpf_cnpj").text('CNPJ ');
            }    
        });

        $(".js-select2").select2({

            placeholder: "Selecione",
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    var url = "'/sac/client/edit/0'";
                    return $('<button type="button" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo cliente</button>');
                }
            },
            ajax: {
                url: '/misc/sac/client/',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".js-select23").select2({
            placeholder: "Selecione",
            maximumSelectionLength: 1,
        });

        $(".js-select24").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    var url = "'/juridical/law/firm/register/0'";
                    return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo escritório</button>');
                }
            },
            ajax: {
                url: '/juridical/law/firm/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#sector_related").change(function() {

            var val = $(this).val();
            var options = '';
            var opt = [];

            if(val != '') {

                if(val == 1) {
                    opt = ["Selecione","Cliente", "Assistência Técnica", "Compra de Peça"]; 
                } else if (val == 2) {
                    opt = ["Selecione", "Distribuidor"]; 
                }

                for (var i = 0; i < opt.length; i++) {
                    options += '<option value="' + i+ '">' + opt[i] + '</option>';
                }

                $("#div_type_related").show();
                $("#type_sector_related").html(options);

            } else {
                $("#div_type_related").hide();
                $("#type_sector_related").html('<option value=""></option>');
            }
            $("#code_sector_related").val('');
        });

        $("#submitProcess").submit(function (e) {
            var form = $(".needs-validation");
            if (form[0].checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                form.addClass('was-validated');

            } else {
                block();
            }
        });

        $("#btn-add-process").click(function() {

            if($("#row_number_execution").is(':visible')) {
                $('#process_number_execution').prop('required', false);    
            } else {
                $('#process_number_execution').prop('required', true);    
            }
            $("#row_number_execution").toggle();

            $("#process_number_execution").val("");
            $("#is_execution").val(0);
        });

        $("#btn-add-process").hover(
            function() {
                $("#btn-add-process").css('background-color', '#3164c3');
            }, function() {
                $("#btn-add-process").css('background-color', '#5a8dee');
            }
        );
	
		$('.format_date').mask('00/00/0000', {reverse: false});
        $('.money').mask('000.000.000.000.000,00', {reverse: true});
        //$('.number_process').mask('0000000-00.0000.0.00.0000', {reverse: false});

        setInterval(() => {
            $("#mJuridical").addClass('sidebar-group-active active');
            $("#mJuridicalProcess").addClass('sidebar-group-active active');
            $("#mJuridicalProcessNew").addClass('active');
        }, 100);
    });
</script>

@endsection