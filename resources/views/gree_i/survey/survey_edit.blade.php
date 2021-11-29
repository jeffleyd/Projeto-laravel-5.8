@extends('gree_i.layout')

@section('content')
<style>
.ck-editor__editable_inline {
    min-height: 50px;
}
</style>
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Pesquisas</h5>
              <div class="breadcrumb-wrapper col-12">
                Atualizando pesquisa
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>

    <div class="alert alert-info alert-dismissible mb-2" role="alert">
    <div class="d-flex align-items-center">
        <i class="bx bx-error"></i>
        <span>
            Deixe para realizar a edição de uma pergunta no final do dia 19:00, pois o sistema envia o relatório completo todos os dias
            <br> às 18:30 para os emails dos responsáveis.
        </span>
    </div>
    </div>

    <div class="alert alert-warning alert-dismissible mb-2" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error"></i>
            <span>
                Deixe apenas uma pergunta ativa por vez e não esqueça de desativar sua pergunta quando não estiver mais usando.
            </span>
        </div>
        </div>
    <div class="content-body">
        <form method="POST" action="/survey/edit_do" class="form repeater-default">
        <input type="hidden" name="survey_id" value="<?= $id ?>">

        <section>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Dados da pesquisa
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Título da pesquisa</label>
                                    <textarea id="js-ckeditor-title" name="name" id="name"><?= $name ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descrição da pesquisa</label>
                                    <textarea id="js-ckeditor-desc" name="description" id="description"><?= $description ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <fieldset class="form-group">
                                        <label for="r_code">Usuarios que Recebem Resposta da Pesquisa</label>
                                        <select class="js-select2 form-control" id="r_codes" name="r_codes[]" style="width: 100%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                            <option></option>
                                            @if ($userall)
                                            <?php foreach ($userall as $key) { ?>
                                                <option value="<?= $key->r_code ?>"  
                                                @if (in_array($key->r_code, $r_codes))
                                                    selected
                                                @endif
                                                ><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)
                                                </option>
                                            <?php } ?>
                                            @endif
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3 col-12 form-group">
                                        <label for="is_notify">Notificar Imediatamente apos responder pesquisa?</label>
                                        <select id="is_notify" name="is_notify" class="form-control">
                                            <option value="0" @if ($is_notify == 0) selected @endif>Não</option>
                                            <option value="1" @if ($is_notify == 1) selected @endif>Sim</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="form-group col-xl-3 col-md-8 col-12">
                                        <label for="survey_init">Onde abrir a Pesquisa</label>
                                        <select id="survey_init" name="survey_init" class="form-control">
                                            <option value="0" @if ($survey_init == 0) selected @endif>Ao iniciar o sistema</option>
                                            <option value="1" @if ($survey_init == 1) selected @endif>No Menu do Sistema</option>
                                            <option value="2" @if ($survey_init == 2) selected @endif>Programada</option>                                            
                                            
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-xl-2 col-md-4 col-12 frequency_time" style="display:none">
                                        <label for="frequency_time">Horario Programado</label>
                                        <input type="text" id="frequency_time" name="frequency_time" class="form-control" value="<?= $frequency_time ?>">
                                    </div>

                                    <div class="form-group col-xl-3 col-md-4 col-12 survey_frequency" style="display:none">
                                        <label for="survey_frequency">Qual a frequencia</label>
                                        <select id="survey_frequency" name="survey_frequency" class="form-control">
                                            <option value="0" @if ($survey_frequency == 0) selected @endif>Diária</option>
                                            <option value="1" @if ($survey_frequency == 1) selected @endif>Semanal</option>
                                            <option value="2" @if ($survey_frequency == 2) selected @endif>Mensal</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-xl-4 col-md-8 col-12 frequency_week" style="display:none">
                                        <label for="frequency_week">Dias da Semana</label>
                                        <select class="js-select2 form-control" id="frequency_week" name="frequency_week[]" style="width: 100%;" data-placeholder="Selecione os dias da Semana" multiple>
                                            
                                            @foreach ($frequency_week_select as $option)
                                                <option value="{{$option->value}}" @if ($option->selected) selected @endif >{{$option->text}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-4 col-md-8 col-12 frequency_month" style="display:none">
                                        <label for="frequency_month">Dias do Mês</label>
                                        <select class="js-select2 form-control" id="frequency_month" name="frequency_month[]" style="width: 100%;" data-placeholder="Selecione os dias do Mês" multiple>
                                            
                                            @foreach ($frequency_month_select as $option)
                                                <option value="{{$option->value}}" @if ($option->selected) selected @endif >{{$option->text}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                
                                @if ($survey_init != 0 && $id!=0 )
                                    <div class="form-group row">
                                        <div class="col-md-3 col-12 form-group">
                                            <label>Link para responder pesquisa anonimamente</label>
                                            <br>
                                            <span>{{route('pesquisa.anonima', $id)}}</span>
                                        </div>
                                    </div>
                                @endif



                                <ul class="list-unstyled mb-0 border p-2">
                                    <li class="d-inline-block mr-2">
                                      <fieldset>
                                        <div class="custom-control custom-radio">
                                          <input type="radio" class="custom-control-input" value="1" name="is_active" id="active" @if ($is_active == 1) checked="" @endif>
                                          <label class="custom-control-label" for="active">Ativo</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2">
                                      <fieldset>
                                        <div class="custom-control custom-radio">
                                          <input type="radio" class="custom-control-input" value="0" name="is_active" id="desactive" @if ($is_active == 0) checked="" @endif>
                                          <label class="custom-control-label" for="desactive">Desativado</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="form-repeater-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Lista de perguntas
                            </h4>
                        </div>
                        <div class="card-content">

                            <div class="card-body pt-50">
                                <div class="survey-rows">
                                    <div data-repeater-list="group">
                                        
                                        @foreach ($questions as $item_key => $item)

                                            <div data-repeater-item="survey-row" class="survey-row">
                                                <input type="hidden" name="item_id" value="<?= $item->id ?>">
                                                <div class="d-flex border rounded mb-1">
                                                
                                                    <div class="row justify-content-between flex-fill pt-1 px-1">
                                                        <div class="col-md-3 col-12 form-group">
                                                            <label for="title">Pergunta </label>
                                                            <input type="text" name="title" class="form-control" placeholder="Digite a pergunta..." value="<?= $item->title ?>">
                                                        </div>

                                                        <div class="col-md-3 col-12 form-group" style="display:none">
                                                            <label for="is_notify">É importante?</label>
                                                            <select name="is_notify" class="form-control">
                                                                <option value="0" @if ($item->is_notify == 0) selected @endif>Não</option>
                                                                <option value="1" @if ($item->is_notify == 1) selected @endif>Sim</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-3 col-12 form-group" >
                                                            <label for="is_required">É Obrigatório?</label>
                                                            <select name="is_required" class="form-control">
                                                                <option value="0" @if ($item->is_required == 0) selected @endif>Não</option>
                                                                <option value="1" @if ($item->is_required == 1) selected @endif>Sim</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 col-12 form-group" >
                                                            <label for="show_obs">Permite Observações?</label>
                                                            <select name="show_obs" class="form-control">
                                                                <option value="0" @if ($item->show_obs == 0) selected @endif>Não</option>
                                                                <option value="1" @if ($item->show_obs == 1) selected @endif>Sim</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3 col-12 form-group">
                                                            <label for="answer_type">Tipo Resposta</label>
                                                            <select name="answer_type" class="select-answer_type form-control">

                                                                <option value="0" @if ($item->answer_type == 0) selected @endif>Texto</option>
                                                                <option value="1" @if ($item->answer_type == 1) selected @endif>Sim/Não</option>
                                                                <option value="2" @if ($item->answer_type == 2) selected @endif>Caixa de Seleção</option>
                                                                <option value="3" @if ($item->answer_type == 3) selected @endif>Multipla Escolha</option>
                                                                <option value="4" @if ($item->answer_type == 4) selected @endif>Lista Suspensa</option>

                                                            </select>
                                                        </div>


                                                            
                                                            @if($item->json_answer)
                                                            <div class="col-md-6 col-12 form-group answers-inner-repeater" style="{{ ( $item->answer_type == 0 || $item->answer_type == 1 ) ? 'display: none' : 'display: initial' }};">

                                                                <div class="card-content">
                                                                    <div class="card-body pt-50">
                                                                            <div class="d-flex border rounded mb-1">
                                                                                <!-- innner repeater -->
                                                                                <div class="inner-repeater row justify-content-between flex-fill pt-1 px-1">

                                                                                    <div class="col-md-12 col-12 form-group">
                                                                                        <div data-repeater-list="json_answer">
                                                                                            <label for="answers">Respostas</label>
                                                                                                
                                                                                                @foreach ($item->json_answer as $key => $json_item)
                                                                                                <div data-repeater-item="answer-row" class="answer-row">
                                                                                                    <div class="row justify-content-between pt-1 px-1">
                                                                                                        <div class="col-md-11 col-10 form-group">
                                                                                                            <input type="text" name="title" class="form-control" placeholder="Digite a resposta..." value="<?= $json_item['title'] ?>">
                                                                                                        </div>
                                                                                                        <div class="col p-0">
                                                                                                            <button class="btn btn-light-danger btn-sm" data-repeater-delete="" type="button">
                                                                                                            <i class="bx bxs-trash-alt"></i>
                                                                                                            </button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                @endforeach
                                                                                                
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-12 col-12 form-group">
                                                                                        <div class="col p-0">
                                                                                            <button class="btn btn-light-primary btn-sm" data-repeater-create="" type="button">
                                                                                            <i class="bx bx-plus"></i>
                                                                                            <span>Nova Resposta</span>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                </div>

                                                                            </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            
                                                            @else
                                                            
                                                            <div class="col-md-6 col-12 form-group answers-inner-repeater" style="{{ ( $item->answer_type == 0 || $item->answer_type == 1 ) ? 'display: none' : 'display: initial' }};">

                                                                <div class="card-content">
                                                                    <div class="card-body pt-50">
                                                                            <div class="d-flex border rounded mb-1">
                                                                                <!-- innner repeater -->
                                                                                <div class="inner-repeater row justify-content-between flex-fill pt-1 px-1">

                                                                                    <div class="col-md-12 col-12 form-group">
                                                                                        <div data-repeater-list="json_answer">
                                                                                            <label for="answers">Respostas</label>
                                                                                            
                                                                                            <div data-repeater-item="answer-row" class="answer-row">
                                                                                                <div class="row justify-content-between pt-1 px-1">
                                                                                                    <div class="col-md-11 col-10 form-group">
                                                                                                        <input type="text" name="title" class="form-control" placeholder="Digite a resposta...">
                                                                                                    </div>
                                                                                                    <div class="col p-0">
                                                                                                        <button class="btn btn-light-danger btn-sm" data-repeater-delete="" type="button">
                                                                                                        <i class="bx bxs-trash-alt"></i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-12 col-12 form-group">
                                                                                        <div class="col p-0">
                                                                                            <button class="btn btn-light-primary btn-sm" data-repeater-create="" type="button">
                                                                                            <i class="bx bx-plus"></i>
                                                                                            <span>Nova Resposta</span>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                </div>

                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                            @endif

                                                            
                                                        

                                                    </div>

                                                    <div class="d-flex  justify-content-between border-left p-25">
                                                        <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button"> <i class="bx bx-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach
                                        
                                    </div>
                                    <div class="form-group">
                                        <div class="col p-0">
                                            <button class="new-question btn btn-light-primary btn-sm" data-repeater-create="" type="button">
                                            <i class="bx bx-plus"></i>
                                            <span>Nova pergunta</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1" style="width: 100%;"><?php if ($id == 0) { ?>Nova pesquisa<?php } else { ?>Atualizar pesquisa<?php } ?></button>
    </form>
    </div>
</div>

    <script src="/ckeditor/ckeditor.js"></script>
    <script src="/ckeditor/init.js"></script>
    <script>
    var item_id;
    $(document).ready(function () {
        ckeditorInit('#js-ckeditor-title');
        ckeditorInit('#js-ckeditor-desc');
        $('#list-datatable').DataTable( {
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

        // form repeater jquery
        $('.file-repeater, .contact-repeater, .repeater-default').repeater({
            repeaters: [{
                selector: '.inner-repeater'
            }],
            defaultValues: {
                'title': '',
                'is_notify': 0,
                'item_id': 0,
                'answer_type': 0,
                'is_required': 1,
                'show_obs': 0,

                'json_answer': {
                    'title': '',
                }

            },
            show: function () {
            $(this).slideDown();
            },
            hide: function (deleteElement) {
                item_id = $(this)[0].children[0].defaultValue;
                if (item_id == "0") {
                    $(this).slideUp(deleteElement); 
                } else {
                    Swal.fire({
                    title: 'Tem certeza disso?',
                    text: "Ao deletar essa pergunta, você também irá remover todas as respostas dos usuários envolvidos nessa pergunta.",
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

                        $.ajax({
                            type: "POST",
                            url: "/survey/question/delete/" + item_id,
                            success: function (response) {
                                if (response.success) {
                                    $(this).slideUp(deleteElement);

                                    Swal.fire({
                                        type: "success",
                                        title: 'Removido',
                                        text: 'Pergunta removida da lista.',
                                        confirmButtonClass: 'btn btn-success',
                                    })
                                } else {
                                    error('Pergunta não foi encontrada!');
                                }
                            }
                        });
                    }
                    });
                }
            }
        });

        $(".js-select2").select2({});

        // $('.timepicker').pickatime();
        
        <?php if (!empty(Session::get('userf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('userf_r_code') ?>']).trigger('change');
        <?php } ?>

        let survey_init = $("#survey_init").val();
        
        let survey_frequency = $("#survey_frequency").val();

        if( survey_init == 0 || survey_init == 1 ){
            $("#survey_frequency option[value='" + 0 + "']").show();
            $("#survey_frequency").val(0).trigger('change');
            $(".survey_frequency").hide();
            
            $('#frequency_time').val(null);
            $(".frequency_time").hide();
            
            

            $('#frequency_week').val(null).trigger('change');
            $(".frequency_week").hide();

            $('#frequency_month').val(null).trigger('change');
            $(".frequency_month").hide();
            
        }else{
            $("#survey_frequency option[value='" + 0 + "']").hide();
            $(".survey_frequency").show();
            
            $(".frequency_time").show();

            if( survey_frequency == 1 ){
                $('#frequency_month').val(null).trigger('change');
                $(".frequency_month").hide();

                $(".frequency_week").show();
            }else{
                $('#frequency_week').val(null).trigger('change');
                $(".frequency_week").hide();

                $(".frequency_month").show();
            }
            
        }


        $(document).on('change',"#survey_init",function() {
            if( $(this).val() == 0 || $(this).val() == 1 ){
                $("#survey_frequency option[value='" + 0 + "']").show();
                $("#survey_frequency").val(0).trigger('change');
                $(".survey_frequency").hide();
                
                $('#frequency_time').val(null);
                $(".frequency_time").hide();

                $('#frequency_week').val(null).trigger('change');
                $(".frequency_week").hide();

                $('#frequency_month').val(null).trigger('change');
                $(".frequency_month").hide();
            }else{
                $("#survey_frequency option[value='" + 0 + "']").hide();
                $("#survey_frequency").val(1).trigger('change');
                $(".survey_frequency").show();
                $(".frequency_time").show();
                $(".frequency_week").show();
            }

        })
        
        $(document).on('change',"#survey_frequency",function() {
            if( $(this).val() == 1 ){
                
                $('#frequency_month').val(null).trigger('change');
                $(".frequency_month").hide();

                $(".frequency_week").show();
            }else if( $(this).val() == 2 ){
                $('#frequency_week').val(null).trigger('change');
                $(".frequency_week").hide();

                $(".frequency_month").show();
            }

        })
        
        $(document).on('change',".select-answer_type",function() {
            
            if( $(this).val() == 0 || $(this).val() == 1 ){
                $(this).parent().parent().find("[data-repeater-item]").remove();
                $(this).parent().parent().find(".answers-inner-repeater").css("display", "none");
                
            }else{
                $(this).parent().parent().find(".answers-inner-repeater").css("display", "initial");
            }
        })

       


        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#msurvey").addClass('sidebar-group-active active');
            $("#msurveyNew").addClass('active');
        }, 100);

    });
    </script>
@endsection