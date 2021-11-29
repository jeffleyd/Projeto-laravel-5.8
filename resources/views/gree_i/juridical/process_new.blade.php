@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<style>
    .was-validated .valid-select2:invalid + .select2 .select2-selection{
        border-color: #dc3545!important;
    }
    .was-validated .valid-select2:valid + .select2 .select2-selection{
        border-color: #39da8a!important;
    }
    *:focus{
        outline:0px;
    }
    
</style>
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
                                        <select class="form-control" id="type_process" name="type_process">
                                            <option value="1">Consumidor</option>
                                            <option value="2">Trabalhista</option>
                                            <option value="3">Cível</option>
                                            <option value="4">Penal</option>
                                            <option value="5">Tributário</option>
                                            <option value="6">Adminstrativo</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shop">Número do processo</label>
                                        <input type="text" class="form-control number_process" id="process_number" name="process_number" placeholder="0000000-00.0000.0.00.0000" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shop">Data recebimento</label>
                                        <input type="text" class="form-control date-mask format_date" id="date_received" name="date_received" placeholder="__/__/____" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="row_number_execution" style="display:none;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <fieldset class="form-group">
                                            <label for="is_execution">Execução</label>
                                            <select class="form-control" id="is_execution" name="is_execution">
                                                <option value="1">Sim</option>
                                                <option value="0">Não</option>
                                            </select>
                                        </fieldset>
                                    </div>  
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="shop">Número do processo relacionado</label>
                                        <input type="text" class="form-control number_process" id="process_number_execution" name="process_number_execution" placeholder="0000000-00.0000.0.00.0000">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="lawyer_r_code">Advogado(a) responsável</label>
                                        <select class="form-control js-select23 valid-select2" id="lawyer_r_code" name="lawyer_r_code" multiple required>
                                            @foreach ($users as $key)
                                                <option value="{{ $key->r_code }}">{{ $key->first_name }} {{ $key->last_name }} ({{ $key->r_code }})</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="law_firm_id">Escritório responsável</label>
                                        <select class="form-control js-select24 valid-select2" id="law_firm_id" name="law_firm_id" multiple required></select>
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
                                <div class="col-md-2 div-applicant" style="display:none;" id="div_type_applicant">
                                    <fieldset class="form-group">
                                        <label for="type_applicant"><span id="span_type_applicant">Tipo Requerente</span></label>
                                        <select class="form-control" id="type_applicant" name="type_applicant">
                                            <option value="1">Física</option>
                                            <option value="2">Jurídica</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-12 div-costumer">
                                    <fieldset class="form-group">
                                        <label><span id="span_costumer">Requerente</span></label>
                                        <select class="form-control js-select2 valid-select2" id="costumer_id" name="costumer_id" multiple required></select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4 div-applicant" style="display:none;" id="div_identity_applicant">
                                    <fieldset class="form-group">
                                        <label><span id="span_costumer_cpf_cnpj">CPF </span><span id="span_identity_applicant">Requerente</span></label>
                                        <input type="text" class="form-control mask-cnpj-cpf"  name="identity_applicant" id="identity_applicant" placeholder="000.000.000-00">
                                    </fieldset>
                                </div>
                                <div class="col-md-6 div-applicant" style="display:none;" id="div_name_applicant">
                                    <fieldset class="form-group">
                                        <label><span id="span_name_applicant">Requerente</span></label>
                                        <input type="text" class="form-control"  name="name_applicant" id="name_applicant" placeholder="Informe o nome">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2" id="div_type_required">
                                    <fieldset class="form-group">
                                        <label for="span_type_required" id="span_type_required">Tipo Requerido</label>
                                        <select class="form-control" id="type_required" name="type_required" required>
                                            <option value="1">Física</option>
                                            <option value="2">Jurídica</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4" id="div_identity_required">
                                    <fieldset class="form-group">
                                        <label><span id="span_identity_cpf_cnpj">CPF </span><span id="span_identity_required"> Requerido</span></label>
                                        <input type="text" class="form-control mask-cnpj-cpf"  name="identity_required" id="identity_required" placeholder="000.000.000-00" required>
                                    </fieldset>
                                </div>
                                <div class="col-md-6" id="div_name_required">
                                    <fieldset class="form-group">
                                        <label><span id="span_name_required">Requerido</span></label>
                                        <input type="text" class="form-control"  name="name_required" id="name_required" placeholder="Informe o nome" required>
                                    </fieldset>
                                </div>
                                <div class="col-md-2" id="div_worker_r_code" style="display:none;">
                                    <fieldset class="form-group">
                                        <label><span id="span_worker_r_code">Matrícula GREE</span></label>
                                        <input type="text" class="form-control"  name="worker_r_code" id="worker_r_code" placeholder="0000">
                                    </fieldset>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label>Setor relacionado</label>
                                        <select class="form-control" id="sector_related" name="sector_related">
                                            <option value="">Sem relacionamento</option>
                                            <option value="1">SAC</option>
                                            <option value="2">Comercial</option>
                                        </select>
                                    </fieldset>
                                </div>    
                            </div> 
                            <div class="row" id="div_type_related" style="display:none;">
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
                                        <input type="text" class="form-control" name="code_sector_related" id="code_sector_related" placeholder="Informe o código / protocolo">
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
                                        <select class="form-control" id="type_action_id" name="type_action_id" required>
                                            <option value="">Selecione</option>
                                            @foreach ($type_action as $key)
                                                <option value="{{$key->id}}">{{$key->description}}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shop">Data do Ajuizamento</label>
                                        <input type="text" class="form-control format_date date-mask" id="date_judgment" name="date_judgment" placeholder="__/__/____">
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="district_court">Vara</label>
                                        <input type="text" class="form-control" id="judicial_court" name="judicial_court" placeholder="Informe a vara judicial" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="district_court">Fórum</label>
                                        <input type="text" class="form-control" id="judicial_forum" name="judicial_forum" placeholder="Informe o fórum" required>
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="district_court">Comarca</label>
                                        <input type="text" class="form-control" id="district_court" name="district_court" placeholder="Informe a comarca" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <fieldset class="form-group">
                                        <label for="state_court">Estado</label>
                                        <select class="form-control" id="state_court" name="state_court" required>
                                            <option value="">Selecione o estado</option>
                                            @foreach (config('gree.states') as $key => $value)
                                                <option value="{{ $key }}" @if ($key == old('state')) selected @endif>{{ $value }}</option>
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
                                            <input type="text" class="form-control money" id="value_cause" name="value_cause" placeholder="0,00" required>
                                        </div>
                                    </div>    
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="form-group" required>
                                        <label for="measures_plea">Ementa / Pleitos</label>
                                        <textarea class="form-control" id="measures_plea" name="measures_plea" rows="3" placeholder="Descreva a Ementa" required></textarea>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>          
            </section>
            <button type="submit" class="btn btn-primary">Criar processo</button>
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
                <button type="submit" class="btn btn-primary ml-1 actionclick" onclick="addModel_do()">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Adicionar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left show" id="modal_execution" tabindex="-1" role="dialog" aria-modal="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="card-title"><span id="span_title_">Execução trabalhista?</span></h6>
                        <ul class="list-unstyled mb-0">
                            <li class="d-inline-block mr-2 mb-1">
                              <fieldset>
                                    <div class="radio">
                                        <input type="radio" name="radio_execution" id="radio1" value="1">
                                        <label for="radio1">Sim</label>
                                    </div>
                              </fieldset>
                            </li>
                            <li class="d-inline-block mr-2 mb-1">
                              <fieldset>
                                    <div class="radio">
                                        <input type="radio" name="radio_execution" id="radio2" value="0">
                                        <label for="radio2">Não</label>
                                    </div>
                              </fieldset>
                            </li>
                          </ul>
                    </div>    
                </div> 
                <div class="row" id="modal_row_number_execution" style="display:none;">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="serie">NÚMERO DO PROCESSO VINCULADO</label>
                            <select class="form-control number_process_linked" id="process_number_execution" name="process_number_execution" multiple style="width: 100%"></select>
                        </fieldset>
                    </div>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary ml-1 btn-sm" id="btn_confirm_execution">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-sm-block d-none">Confirmar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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

        $('input[type=radio][name=radio_execution]').change(function() {
            if (this.value == 1) {
                $("#modal_row_number_execution").show();
                $('.modal-dialog').removeClass('modal-sm');
            } else {
                $("#modal_row_number_execution").hide();
                $('.modal-dialog').addClass('modal-sm');
            }
        });

        $("#is_execution").val(0);
        $("#btn_confirm_execution").click(function () {

            var radio = $("input[type=radio][name=radio_execution]:checked");

            if(radio.length == 0) {
                return $error('Selecione para confirmar!');
            }

            if(radio.val() == 1) {
                $("#row_number_execution").show();
                $("#is_execution").val(1);

                $("#span_type_applicant").text('TIPO EXEQUENTE');
                $("#span_identity_applicant").text('EXEQUENTE');
                $("#span_name_applicant").text('EXEQUENTE');

                $("#span_type_required").text('TIPO EXECUTADO');
                $("#span_identity_required").text('EXECUTADO');
                $("#span_name_required").text('EXECUTADO');
                
            } else {
                $("#row_number_execution").hide();
                $("#is_execution").val(0);
                $("#process_number_execution").val("");
            }

            var process_number_execution = $("#process_number_execution").select2('data');
            if(process_number_execution.length != 0) {
                $("#process_number_execution").val($("#process_number_execution").select2('data')[0].text);
            }

            $("#modal_execution").modal('hide');
            $("#process_number_modal").val("");

        });

        $('#identity_required').mask('000.000.000-00', {reverse: false});
        $('#identity_applicant').mask('000.000.000-00', {reverse: false});

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
        });

        $(".js-select24").select2({
            placeholder: "Selecione",
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

        $(".number_process_linked").select2({
            placeholder: "Selecione",
            ajax: {
                url: '/juridical/process/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
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
        });

        $("#type_process").change(function () {

            if($(this).val() == 1) {
                
                $(".div-costumer").show();
                $(".div-applicant").hide();

                $("#span_type_required").text('TIPO REQUERIDO');
                $("#span_identity_required").text('REQUERIDO');
                $("#span_name_required").text('REQUERIDO');

                $("#div_name_applicant").hide();
                $("#div_identity_required").attr('class', 'col-md-4');
                $("#div_name_required").attr('class', 'col-md-6');
                $("#div_worker_r_code").hide();

                $("#div_type_required").show();
                $("#div_identity_required").show();

                $("#row_number_execution").hide();
                $("#is_execution").val(0);

                $("#costumer_id").prop('required',true);  
                $("#identity_applicant").prop('required',false);
                $("#name_applicant").prop('required',false);              


            } else if($(this).val() == 2) {

                $(".div-costumer").hide();
                $(".div-applicant").show();

                $("#span_type_applicant").text('TIPO RECLAMANTE');
                $("#span_identity_applicant").text('RECLAMANTE');
                $("#span_name_applicant").text('RECLAMANTE');

                $("#span_type_required").text('TIPO RECLAMADO');
                $("#span_identity_required").text('RECLAMADO');
                $("#span_name_required").text('RECLAMADO');

                $("#div_name_applicant").attr('class', 'col-md-6');
                $("#div_identity_required").attr('class', 'col-md-3');
                $("#div_name_required").attr('class', 'col-md-5');

                $("#div_worker_r_code").show();
                $("#div_name_applicant").show();
                $("#div_type_required").show();
                $("#div_identity_required").show();
                $("#modal_execution").modal('show');

                $("#identity_applicant").prop('required',true);
                $("#name_applicant").prop('required',true);
                $("#costumer_id").prop('required',false);
                
            } else if($(this).val() == 3 || $(this).val() == 5) {

                $(".div-costumer").hide();
                $(".div-applicant").show();

                $("#span_type_applicant").text('TIPO REQUERENTE');
                $("#span_identity_applicant").text('REQUERENTE');
                $("#span_name_applicant").text('REQUERENTE');

                $("#span_type_required").text('TIPO REQUERIDO');
                $("#span_identity_required").text('REQUERIDO');
                $("#span_name_required").text('REQUERIDO');

                $("#div_name_applicant").attr('class', 'col-md-6');
                $("#div_identity_required").attr('class', 'col-md-4');
                $("#div_name_required").attr('class', 'col-md-6');
                $("#div_worker_r_code").hide(); 

                $("#div_name_applicant").show();
                $("#div_type_required").show();
                $("#div_identity_required").show();

                if($(this).val() == 3) {
                    $("#modal_execution").modal('show');
                }
                
                $("#row_number_execution").hide();
                $("#is_execution").val(0);

                $("#identity_applicant").prop('required',true);
                $("#name_applicant").prop('required',true);
                $("#costumer_id").prop('required',false);

            } else if($(this).val() == 4) {
                
                $(".div-costumer").hide();
                $(".div-applicant").show();

                $("#span_type_applicant").text('TIPO AUTOR');
                $("#span_identity_applicant").text('AUTOR');
                $("#span_name_applicant").text('NOME AUTOR');

                $("#span_type_required").text('TIPO RÉU');
                $("#span_identity_required").text('RÉU');
                $("#span_name_required").text('RÉU');

                $("#div_name_applicant").attr('class', 'col-md-6');
                $("#div_identity_required").attr('class', 'col-md-4');
                $("#div_name_required").attr('class', 'col-md-6');
                $("#div_worker_r_code").hide();

                $("#div_name_applicant").show();
                $("#div_type_required").show();
                $("#div_identity_required").show();

                $("#row_number_execution").hide();
                $("#is_execution").val(0);

                $("#identity_applicant").prop('required',true);
                $("#name_applicant").prop('required',true);
                $("#costumer_id").prop('required',false);

            } else if($(this).val() == 6) {

                $(".div-costumer").hide();
                $(".div-applicant").show();

                $("#div_name_applicant").show();
                $("#div_type_applicant").hide();
                $("#div_identity_applicant").hide();
                $("#div_name_applicant").attr('class', 'col-md-12');
                $("#span_name_applicant").text('ORGÃO / ESTADO');
                
                $("#name_applicant").prop('required',true);
                $("#costumer_id").prop('required',false);
            }
        });

        $('.format_date').mask('00/00/0000', {reverse: false});
        $('.money').mask('000.000.000.000.000,00', {reverse: true});

        //$('.number_process').mask('0000000-00.0000.0.00.0000', {reverse: false});
        //$('.number_process').mask('ZZZZZZZ-00.0000.0.00.0000',{translation:  {'Z': {pattern: /[a-zA-Z0-9_.-]/, recursive: true}}});


        var CpfCnpjMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
        },
        cpfCnpjpOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.mask-cnpj-cpf').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);

        setInterval(() => {
            $("#mJuridical").addClass('sidebar-group-active active');
            $("#mJuridicalProcess").addClass('sidebar-group-active active');
            $("#mJuridicalProcessNew").addClass('active');
        }, 100);
    });
</script>

@endsection