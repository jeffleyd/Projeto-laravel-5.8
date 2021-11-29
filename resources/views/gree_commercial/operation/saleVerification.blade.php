@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Operacional</a></li>
    <li class="active">Importar NF em aberto</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')

<style>
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline {
        width: 100% !important;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline .select2-search__field {
        width: 100% !important;
    }
</style>    
<link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
<div class="window">
    <div class="inner-padding">

        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>Para importar, baixe o modelo ao lado - <a targe="_blank" href="/excell/modelo_vendas_importacao.xlsx" style="color:#9b9c3a;">Modelo de importação apuração <i class="fa fa-download"></i></a>
            <br>
            <span style="margin-left: 22px;">Preencha todas as colunas da planilha.</span>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <form action="#" method="post" id="form_sale_verification" enctype="multipart/form-data">
					<input type="hidden" value="{{Session::get('r_code')}}" name="r_code">
					<input type="hidden" value="{{Session::get('email')}}" name="email">
                <!--<form action="/commercial/export/report/sale/verification/client" method="post" id="form_sale_verification" enctype="multipart/form-data">-->
                    <fieldset>
                        <legend>Apuração de venda cliente</legend>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Cliente</label>
                            </div>
                            <div class="col-sm-10">
                                <select name="client_id" id="client_id" class="form-control select2-client" style="width: 100%;" multiple></select>
                            </div>
                        </div>
                        <div class="spacer-20"></div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Data inicial e final</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="start_date" id="start_date" class="form-control date-format" placeholder="Data inicial" autocomplete="off">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="final_date" id="final_date" class="form-control date-format" placeholder="Data final" autocomplete="off">
                            </div>
                        </div>
                        <div class="spacer-20"></div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Modelo Frio</label>
                            </div>
                            <div class="col-sm-10">
                                <select name="model_f[]" id="model_f" class="form-control model-select-f" style="width: 100%;" multiple></select>
                            </div>
                        </div>
                        <div class="spacer-20"></div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Modelo Quente/Frio</label>
                            </div>
                            <div class="col-sm-10">
                                <select name="model_q_f[]" id="model_q_f" class="form-control model-select-q-f" style="width: 100%;" multiple></select>
                            </div>
                        </div>
                        <div class="spacer-20"></div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>BTU</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" name="btu" id="btu" value="" class="form-control" placeholder="Informe o formato do BTU: Ex. 00K">
                            </div>
                        </div>

						<div class="spacer-20"></div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Condições adicionais</label>
                            </div>
                            <div class="col-sm-10">
                                <select name="conditions[]" id="conditions" class="form-control model-select-conditions" style="width: 100%;" multiple></select>
                            </div>
                        </div>
						<div class="spacer-20"></div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Tipo de condicões adicionais</label>
                            </div>
                            <div class="col-sm-10">
                                <select name="type_conditions" id="type_conditions" class="form-control">
                                    <option value="1">Ter todas as condições</option>
                                    <option value="2">Ter ao menos 1 condição</option>
                                </select>
                            </div>
                        </div>
                        <div class="spacer-20"></div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Tipo</label>
                            </div>
                            <div class="col-sm-10">
                                <select name="type_part_air" id="type_part_air" class="form-control">
                                    <option value="1">Condensadora</option>
                                    <option value="2">Evaporadora</option>
                                </select>
                            </div>
                        </div>
                        <div class="spacer-20"></div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Arquivo (xlsx)</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="file" name="attach" id="attach" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <div class="spacer-20"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-group" style="width: 100%;">
                                <button type="button" class="btn btn-primary" id="btn_import" style="width: 100%;">
                                    <i class="fa fa-upload" style="color: #ffffff;"></i>&nbsp; Iniciar apuração
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End .inner-padding -->
</div>

<script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script>

    $.fn.datepicker.dates['en'] = {
        days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
        daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
        months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        today: "Hoje",
        clear: "Limpar",
        format: "mm/dd/yyyy",
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        weekStart: 0
    };

    $(".date-format").datepicker( {
        format: "yyyy-mm-dd",
        
    });

    $(document).ready(function () {

        $(".select2-client").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return 'Cliente não existe...';
                },
                maximumSelected: function (e) {
                    return 'você só pode selecionar 1 item';
                }
            },
            ajax: {
                url: '/commercial/client/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".model-select-q").select2({
            tags: true,
            placeholder: "Formato do modelo frio: Ex. Q",
            tokenSeparators: [',', ' ']
        });

        $(".model-select-f").select2({
            tags: true,
            placeholder: "Formato do modelo frio: Ex. F",
            tokenSeparators: [',', ' ']
        });

        $(".model-select-q-f").select2({
            tags: true,
            placeholder: "Formato do modelo frio: Ex. Q/F",
            tokenSeparators: [',', ' ']
        });
		
		$(".model-select-conditions").select2({
            tags: true,
            placeholder: "Informe as condições",
            tokenSeparators: [',', ' ']
        });

        $("#btn_import").click(function() {

            if($("#client_id").val() == null) {
                return $error('Selecione o cliente');
            }
            else if($("#start_date").val() != "" && $("#final_date").val() == "") {
                return $error('Selecione a data final');
            } 
            else if($("#final_date").val() != "" && $("#start_date").val() == "") {
                return $error('Selecione a data inicial');
            } 
            else if($("#model_f").val() == null) {
                return $error('Informe Modelo Frio');
            }
            else if($("#model_q_f").val() == null) {
                return $error('Informe Modelo Quente/Frio');
            }
            else if($("#btu").val() == '') {
                return $error('Informe BTU');
            }
            else if($("#attach")[0].files.length == 0) {
                return $error('Selecione um arquivo!');
            } 
            else {
                
                block();
                ajaxSend('https://filemanager.gree.com.br/commercial/export/report/sale/verification/client', $("#form_sale_verification").serialize(), 'POST', '60000', $("#form_sale_verification")).then(function(result){
                    $success('Geração de relatório em andamento');
                    window.location = "/commercial/export/report/sale/client/response/list";
                }).catch(function(err){
                    unblock();
                    $error(err.message);
                });
            }    
        });

        $("#operation").addClass('menu-open');
        $("#saleVerification").addClass('page-arrow active-page');
    });
</script>

@endsection
