@extends('gree_i.layout')

@section('content')
<style>
.col-span {
    color: #e8ff26;
}
body.modal-open {
    overflow: auto !important;
}
body.modal-open[style] {
    padding-right: 0px !important;
}
.radio label::before {
    border: 1px solid #81c0ff;
}
.icon-edit:hover {
    color: #39DA8A;
    cursor: pointer;
}
.icon-edit {
    color:#68ab92;
}
.answer-edit:hover {
    color: #3568df;
}

</style>    

<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/editors/quill/quill.snow.css">

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-6 col-sm-12 col-lg-6">
                    <h5 class="content-header-title float-left pr-1 mb-0">Recrutamento</h5>
                    <div class="breadcrumb-wrapper col-12">
                        Questões de prova
                    </div>
                </div>

                @if($is_progress == 0)
                <div class="col-6 col-sm-12 col-lg-6">
                    <button type="button" class="btn btn-primary shadow float-right" id="btn_form_question">
                        <i class="bx bx-save"></i> Atualizar prova
                    </button>
                </div>    
                @endif
            </div>
        </div>
    </div>
    <div class="content-body">
        <form method="POST" action="/recruitment/question/edit_do" id="form_recruitment" class="form">
            <input type="hidden" name="recruitment_test_id" value="{{$id}}">
            <input type="hidden" name="arr_candidates" id="arr_candidates">
            <input type="hidden" name="arr_answers" id="arr_answers">
            <input type="hidden" name="question" id="question">

            <section id="basic-tabs-components">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="true">
                                    <i class="bx bx-file align-middle"></i>
                                    <span class="align-middle">Questões</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-candidates" role="tab" aria-selected="false">
                                    <i class="bx bxs-user-plus align-middle"></i>
                                    <span class="align-middle">Candidatos</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-instructions" role="tab" aria-selected="false">
                                    <i class="bx bx-notepad align-middle"></i>
                                    <span class="align-middle">Instruções prova</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-email" role="tab" aria-selected="false">
                                    <i class="bx bx-mail-send align-middle"></i>
                                    <span class="align-middle">Email</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="false">
                                    <i class="bx bxs-cog align-middle"></i>
                                    <span class="align-middle">Configurações</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="home" aria-labelledby="home-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-10">
                                        @if($is_progress == 0)
                                        <button type="button" class="btn btn-outline-primary shadow" data-toggle="modal" data-target="#new_question">
                                            <i class="bx bx-plus"></i> Nova questão
                                        </button>
                                        @endif
                                    </div>
                                    <div class="col-2">
                                        <div id="question_select"></div>                                        
                                    </div>
                                </div><hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div id="body_question"></div>
                                        @if($is_progress == 0)
                                        <button type="button" class="btn btn-outline-primary shadow mt-2" data-toggle="modal" data-target="#modal_answer_new">
                                            <i class="bx bx-plus"></i> Adicionar Resposta
                                        </button>
                                        @endif
                                    </div>    
                                </div>
                            </div>

                            <div class="tab-pane" id="tab-candidates" aria-labelledby="profile-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="description">Nome Candidato</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" class="form-control" id="name_candidate" placeholder="Nome completo">
                                                <div class="form-control-position">
                                                  <i class="bx bx-user"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="description">Email Candidato</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="email" class="form-control" id="email_candidate" placeholder="informe o email">
                                                <div class="form-control-position">
                                                  <i class="bx bx-mail-send"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="col-lg-2">
                                        <button type="button" class="btn btn-primary shadow mr-1 mt-2" id="btn_add_candidate">
                                            <i class="bx bx-plus"></i> Adicionar
                                        </button>
                                    </div>    
                                </div>  
                                <hr>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th>Email</th>
                                                        <th>Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table_candidates"></tbody>
                                            </table>
                                        </div>
                                    </div>    
                                </div>                                            
                            </div> 
                            <div class="tab-pane" id="tab-instructions" aria-labelledby="tab-instructions" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <div id="instructions" name="instructions"><?= $test->instructions ?></div>
                                        <textarea name="test_instructions" style="display: none;"><?= $test->instructions ?></textarea>
                                    </div>    
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-email" aria-labelledby="tab-email" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-error"></i>
                                                <span>
                                                    No conteúdo do email é necessário informar as seguintes variáveis:
                                                    <span>[{NOME}], [{EMAIL}], [{SENHA}], [{LINK}]</span>
                                                    <br><span class="col-span">SEM AS VARIÁVEIS ACIMA O CANDIDATO NÃO PODERÁ ACESSAR A PROVA!</span><br>
                                                </span>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">Assunto</label>
                                            <input type="text" class="form-control" name="email_subject" id="email_subject" placeholder="Digite assunto do email" value="<?= $test->email_subject ?>">
                                        </div>
                                    </div>    
                                </div>    
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">Título</label>
                                            <input type="text" class="form-control" name="email_content_title" id="email_content_title" placeholder="título vai no corpo do email" value="<?= $test->email_content_title ?>">
                                        </div>
                                    </div>    
                                </div>    
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="description">Conteúdo do email</label>
                                        <textarea class="form-control" name="email_content" id="email_content" rows="10" placeholder="Informe o conteúdo do email"><?= $test->email_content ?></textarea>
                                    </div>    
                                </div>
                            </div>
                            <div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                                <div class="form-group">
                                    <label for="name">Título da prova</label>
                                    <textarea class="form-control" name="title_test" id="title_test" placeholder="Digite o título"><?= $test->title ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descrição da prova</label>
                                    <textarea class="form-control" name="title_description" id="title_description" placeholder="Digite a descrição"><?= $test->description ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="description">Tempo de prova</label>
                                    <input type="text" class="form-control time_mask" name="test_time" id="test_time" placeholder="HH:MM:SS" value="<?= $test->test_time ?>">
                                </div>
                                <div class="form-group">
                                    <label for="description">Porcentagem de acertos para passar</label>
                                    <input type="number" class="form-control" name="test_percent" id="test_percent" placeholder="Ex: acertar 80% da prova" value="<?= $test->test_percent ?>">
                                </div>
                                <div class="form-group" @if ($test->is_send == 1) style="display:none;" @endif>
                                    <ul class="list-unstyled mb-0 border p-2">
                                        <li class="d-inline-block mr-2">
                                          <fieldset>
                                            <div class="custom-control custom-radio">
                                              <input type="radio" class="custom-control-input" value="1" name="is_send" id="active" @if ($test->is_send == 1) checked @endif>
                                              <label class="custom-control-label" for="active">Enviar</label>
                                            </div>
                                          </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2">
                                          <fieldset>
                                            <div class="custom-control custom-radio">
                                              <input type="radio" class="custom-control-input" value="0" name="is_send" id="desactive" @if ($test->is_send == 0) checked @endif>
                                              <label class="custom-control-label" for="desactive">Não enviar</label>
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
            <!--<button type="button" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1" id="btn_form_question" style="width: 100%;">Atualizar prova</button>-->
        </form>
    </div>
</div>

<div class="modal fade" id="new_question" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Nova Questão</span>
            </div>
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-12 form-group">
                        <div class="form-group">
                            <div id="field_question" name="field_question"></div>
                        </div>    
                    </div>    
                    <div class="col-12">    
                        <button type="button" class="btn btn-outline-primary shadow btn-block" data-toggle="modal" data-target="#modal_answer">
                            <i class="bx bx-plus" style="position: relative;top: 3px;"></i> Adicionar resposta
                        </button>
                    </div>    
                    <hr>
                </div>    

                <div class="row">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Respostas</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table_answer"></tbody>
                        </table>
                    </div>
                </div>
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_save_question">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Salvar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_answer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Adicionar resposta</span>
            </div>
            <form action="{{Request::url()}}" id="form_modal_filter">
                <input type="hidden" name="export" value="1">
                <div class="modal-body">
					
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Resposta</label>
                                <textarea class="form-control" rows="4" id="field_answer"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Resposta correta?</label>
                                <select class="form-control" id="answer_status">
                                    <option></option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÃO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" id="btn_add_answer">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Adicionar</span>
                    </button>
                </div>
            </form> 
        </div>    
    </div>
</div>

<div class="modal fade" id="modal_answer_edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Atualizar resposta</span>
            </div>
                <input type="hidden" id="answer_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Resposta</label>
                                <textarea class="form-control" rows="4" id="field_answer_edit"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Resposta correta?</label>
                                <select class="form-control" id="answer_status_edit">
                                    <option></option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÃO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" id="btn_add_answer_edit">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Atualizar</span>
                    </button>
                </div>
            
        </div>    
    </div>
</div>

<div class="modal fade" id="modal_question_edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Atualizar pergunta</span>
            </div>
            <input type="hidden" id="question_id">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="first-name-vertical">Pergunta</label>
                            <div id="field_question_2"></div>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_update_question_edit">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Atualizar</span>
                </button>
            </div>
        </div>    
    </div>
</div>

<div class="modal fade" id="modal_answer_new" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Adicionar nova resposta</span>
            </div>
            <form action="/recruitment/question/edit/new" id="form_modal_new_answer">
                <input type="hidden" name="recruitment_test_questions_id" id="recruitment_test_questions_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Resposta</label>
                                <textarea class="form-control" rows="4" id="question_new_description" name="description"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Resposta correta?</label>
                                <select class="form-control" id="question_new_is_correct" name="is_correct">
                                    <option></option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÃO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" id="btn_add_new_answer">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Adicionar</span>
                    </button>
                </div>
            </form> 
        </div>    
    </div>
</div>

<script src="/admin/app-assets/vendors/js/editors/quill/quill.js"></script>

<script>
    var arr_candidates = {!! json_encode($test->recruitment_test_candidates_all) !!};
    var arr_question = {!! json_encode($question) !!};
    var arr_questions_id = {!! json_encode($questions_id) !!};
    var arr_answers = [];

    var is_progress = {{$is_progress}};
    
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        ['image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
        [{ 'direction': 'rtl' }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'font': [] }],
        [{ 'align': [] }],
    ];

    const instructions = new Quill('#instructions', {
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    image: function() {
                        imageHandler(instructions);
                    }
                }
            }
        },
        placeholder: 'Informe as instruções...',
        theme: 'snow'
    });
    instructions.on('text-change', function(delta, oldDelta, source) {
        $('textarea[name="test_instructions"]').val(instructions.container.firstChild.innerHTML);
    });

    const field_question = new Quill('#field_question', {
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    image: function() {
                        imageHandler(field_question);
                    }
                }
            }
        },
        placeholder: 'Informe o enunciado da questão ...',
        theme: 'snow'
    });
    field_question.on('text-change', function(delta, oldDelta, source) {
        $('#field_question').val(field_question.container.firstChild.innerHTML);
    });

    const field_question_2 = new Quill('#field_question_2', {
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    image: function() {
                        imageHandler(field_question_2);
                    }
                }
            }
        },
        placeholder: 'Informe o enunciado da questão ...',
        theme: 'snow'
    });
    field_question_2.on('text-change', function(delta, oldDelta, source) {
        $('#field_question_2').val(field_question_2.container.firstChild.innerHTML);
    });

    if(arr_candidates.length > 0) {
        $('#table_candidates').html(reloadCandidates(arr_candidates));
    }

    $(document).ready(function () {

        $('.modal').on('show.bs.modal', function () {
            var $modal = $(this);
            var baseZIndex = 1050;
            var modalZIndex = baseZIndex + ($('.modal.show').length * 20);
            var backdropZIndex = modalZIndex - 10;
            $modal.css('z-index', modalZIndex).css('overflow', 'auto');
            $('.modal-backdrop.show:last').css('z-index', backdropZIndex);
        });

        $('.modal').on('shown.bs.modal', function () {
            var baseBackdropZIndex = 1040;
            $('.modal-backdrop.show').each(function (i) {
                $(this).css('z-index', baseBackdropZIndex + (i * 20));
            });
        });

        $('.modal').on('hide.bs.modal', function () {
            var $modal = $(this);
            $modal.css('z-index', '');
        });

        $("#btn_save_question").click(function() {

            if(field_question.getText().trim().length == 0) {
                $error('Digite a pergunta!');
            }
            else if(arr_answers.length == 0) {
                $error('Informe as repostas!');
            } 
            else {
                $("#question").val(field_question.root.innerHTML);
                $("#arr_answers").val(JSON.stringify(arr_answers));
                $("#form_recruitment").unbind().submit();
            }
        });

        $("#btn_add_answer").click(function() {

            if($("#field_answer").val() == '') {
                $error('Digite a resposta!');
            }
            else if($("#answer_status").val() == '') {
                $error('Selecione a resposta correta');
            }
            else {
                obj = {
                    'answer': $("#field_answer").val(),
                    'is_correct': $("#answer_status").val()
                };
                arr_answers.push(obj);
                $('#table_answer').html(reloadAnswer(arr_answers));
                $("#field_answer, #answer_status").val('');
                $("#modal_answer").modal('hide');
            }
        });

        $("#body_question").html(reloadQuestions(arr_question));
        $("#question_select").html(loadSelect());


        $("#btn_add_candidate").click(function() {

            if($("#name_candidate").val() == '') {
                $error('Nome do candidato é obrigatório!');  
            }
            else if($("#email_candidate").val() == '') {
                $error('email do candidato é obrigatório!');
            }
            else {
                obj = {
                    'name': $("#name_candidate").val(),
                    'email': $("#email_candidate").val()
                };

                arr_candidates.push(obj);
                $('#table_candidates').html(reloadCandidates(arr_candidates));
                $("#name_candidate, #email_candidate").val('');
            }
        });

        $("#load_select").change(function() {

            block();
            ajaxSend('/recruitment/question/ajax/edit', {question_id: $(this).val()}, 'GET', 6000).then((response) => {
                $("#body_question").html(reloadQuestions(response.question));
                unblock();

            })
            .catch((error) => {
                $error(error.message);
                unblock();
            });
        });

        $("#btn_add_answer_edit").click(function() {

            var id_answer = $("#answer_id").val();

            if($("#field_answer_edit").val() == '') {
                $error('Informe a resposta para atualizar!');  
            }
            else if($("#answer_status_edit").val() == '') {
                $error('Selecione se a resposta é correta!');  
            }
            else {
                block();
                ajaxSend('/recruitment/answer/update/ajax', 
                {
                    answer_id: $("#answer_id").val(),
                    field_answer: $("#field_answer_edit").val(),
                    answer_status: $("#answer_status_edit").val()
                },  
                'POST', 6000, '').then(function(result){

                    if(result.success) {
                        
                        $("#answer_"+id_answer+"").html($("#field_answer_edit").val());

                        if($("#answer_status_edit").val() == 1) {

                            $("#answer_icon_"+id_answer+"").addClass('bx-check-circle').removeClass('bx-x-circle');
                            $("#answer_icon_"+id_answer+"").css('color', '#39DA8A');
                        }
                        else {
                            $("#answer_icon_"+id_answer+"").addClass('bx-x-circle').removeClass('bx-check-circle');
                            $("#answer_icon_"+id_answer+"").css('color', '#f10a0a');
                        }

                        $("#modal_answer_edit").modal('hide');
                        $success(result.message);
                    }
                    unblock();
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                });
            }    
        });

        $("#btn_update_question_edit").click(function() {

            var question_id = $("#question_id").val();

            if(field_question_2.getText().trim().length == 0) {
                $error('Informe a pergunta!');
            } else {

                block();
                ajaxSend('/recruitment/question/update/ajax', 
                {
                    question_id: question_id,
                    question_content: field_question_2.root.innerHTML
                },  
                'POST', 6000, '').then(function(result){

                    if(result.success) {
                        
                        $("#question_"+question_id+"").html('');
                        $("#question_"+question_id+"").html(field_question_2.root.innerHTML);
                        $("#modal_question_edit").modal('hide');
                        $success(result.message);
                    }
                    unblock();
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                });
            }
        });

        $("#btn_add_new_answer").click(function() {

            if($("#question_new_description").val() == '') {
                $error('Adicione uma resposta!');  
            }
            else if($("#question_new_is_correct").val() == '') {
                $error('Selecione se a resposta é correta!');  
            }
            else {  

                block();
                ajaxSend('/recruitment/answer/edit/new/ajax', 
                {
                    question_id: $("#load_select").val(),
                    description: $("#question_new_description").val(),
                    is_correct: $("#question_new_is_correct").val()
                },  
                'GET', 6000, '').then(function(result){

                    if(result.success) {
                        
                        $("#question_new_description, #question_new_is_correct").val('');
                        $("#modal_answer_new").modal('hide');
                        $("#body_question").append(loadSingleAnswer(result.answer));
                        $success(result.message);
                    }
                    unblock();
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                });
            }  
        });

        $("#btn_form_question").click(function() {

            if(arr_candidates.length == 0) {
                $error('Adicione os candidatos, obrigatório!');
            }
            else if($("textarea[name='test_instructions']").val() == '') {
                $error('Obrigatório instruções da prova!');
            }
            else if($("#email_subject").val() == '') {
                $error('Assunto do email é obrigatório!');
            }
            else if($("#email_content_title").val() == '') {
                $error('Título do corpo do email é obrigatório!');
            }
            else if($("#email_content").val() == '') {
                $error('Conteúdo do email é obrigatório!');
            }
            else if($("#title_test").val() == '') {
                $error('Título da prova é obrigatório!');
            }
            else if($("#title_description").val() == '') {
                $error('descrição da prova é obrigatório!');
            }
            else if($("#test_time").val() == '') {
                $error('Tempo de prova é obrigatório!');
            }
            else if($("#test_percent").val() == '') {
                $error('Porcentagem de acertos é obrigatório!');
            }
            else if($('input[name="is_send"]:checked').val() == 1){

                Swal.fire({
                    title: 'Confirmar envio de prova',
                    text: 'Verifique se todas informações e emails dos candidatos estão corretos!',
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
                        $("#arr_candidates").val(JSON.stringify(arr_candidates));
                        $("#form_recruitment").unbind().submit();
                    }
                });
            }
            else {
                block();
                $("#arr_candidates").val(JSON.stringify(arr_candidates));
                $("#form_recruitment").unbind().submit();
            }

        });

        $('.time_mask').mask('00:00:00');

        setInterval(() => {
            $("#mRH").addClass('sidebar-group-active active');
            $("#mQuestion").addClass('sidebar-group-active active');
            $("#mQuestionNew").addClass('active');
        }, 100);
    });

    function reloadQuestions(object) {

        var html = '';
        if(object != null) {

            var question = arr_questions_id.indexOf(object.id) + 1;

            html += '<div class="row control-group">';
            html += '    <div class="col-12">';    
            html += '       <p class="text-left" style="color:#5a8dee;"><b>Questão '+ question +'</b>';
            if(is_progress == 0) {
                html += '      <i onclick="editQuestion(this)" class="bx bx-edit" data-icon-id="'+object.id+'" style="position:relative;margin-left: 7px;top: 2px;cursor:pointer;"></i><i class="bx bx-trash" style="position: relative;top:1px;color:#f56e6e;left: 4px;cursor:pointer;" onclick="deleteAllQuestion(this)" data-question-id="'+object.id+'"></i>';
            }
            html += '       </p>';
            html += '    </div>';
            html += '    <div class="col-12">';
            html += '        <div class="form-group row">';
            html += '            <div class="col-12" id="question_'+object.id+'">';
            html += '               <p class="text-left">';
            html += '                   '+object.title+'';
            html += '               </p>';
            html += '            </div>';
            html += '        </div>';

            for (var i = 0; i < object.recruitment_test_questions_answer.length; i++) {

                var column = object.recruitment_test_questions_answer[i];

                html += '<div class="form-group row" style="margin-bottom: -2rem;">';
                html += '    <div class="form-group col-md-12 col-12" style="padding-left: 10px;padding-right: 10px;">';
                html += '        <fieldset>';
                html += '            <div class="radio radio-primary">';
                html += '                <label  style="width: fit-content;"><pre style="margin-top: revert;font-weight: 400;padding: 10px;background-color: #fafaff;" id="answer_'+column.id+'">'+htmlEntities(column.description)+'</pre>';
                if(column.is_correct == 1) {
                    html += '               <i class="bx bx-check-circle mr-2" style="color:#39DA8A;font-size: 1rem;" id="answer_icon_'+column.id+'"></i>';
                } else {
                    html += '               <i class="bx bx-x-circle mr-2" style="color:#f10a0a;font-size: 1rem;" id="answer_icon_'+column.id+'"></i>';
                }

                if(is_progress == 0) {
                    html += '                   <a onclick="editSingleAnswer(this)" style="cursor: pointer;" data-id="'+ column.id +'" data-answer-status="'+ column.is_correct +'" ><i class="bx bx-edit-alt answer-edit" style="color:#3568df;"></i></a>';
                    html += '                   <a onclick="deleteSingleAnswer(this)" style="cursor: pointer;position:relative;left:3px;" data-id="'+ column.id +'" class="btn-less"><i class="bx bx-trash" style="position: relative;top:1px;color:#f56e6e;"></i></a>';
                }
                html += '                </label>';
                html += '            </div>';
                html += '        </fieldset>';
                html += '    </div>';
                html += '</div>';
            }

            html += '    </div>';
            html += '</div>';

        } else {
            html += '<div class="row control-group">';
            html += '    <div class="col-12">';    
            html += '       <p class="text-left" style="color:#5a8dee;">Não há questões cadastradas!</p>';
            html += '    </div>';
            html += '</div>';
        }    

        return html;
    }

    function loadSingleAnswer(answer) {

        var html = '';
        html += '<div class="form-group row" style="margin-bottom: -2rem;">';
        html += '    <div class="form-group col-md-12 col-12" style="padding-left: 10px;padding-right: 10px;">';
        html += '        <fieldset>';
        html += '            <div class="radio radio-primary">';
        html += '                <label  style="width: fit-content;"><pre style="margin-top: revert;font-weight: 400;padding: 10px;background-color: #fafaff;" id="answer_'+answer.id+'">'+htmlEntities(answer.description)+'</pre>';
        if(answer.is_correct == 1) {
            html += '               <i class="bx bx-check-circle mr-2" style="color:#39DA8A;font-size: 1rem;" id="answer_icon_'+answer.id+'"></i>';
        } else {
            html += '               <i class="bx bx-x-circle mr-2" style="color:#f10a0a;font-size: 1rem;" id="answer_icon_'+answer.id+'"></i>';
        }
        html += '                   <a onclick="editSingleAnswer(this)" style="cursor: pointer;" data-id="'+ answer.id +'" data-answer-status="'+ answer.is_correct +'" ><i class="bx bx-edit-alt answer-edit" style="color:#3568df;"></i></a>';
        html += '                   <a onclick="deleteSingleAnswer(this)" style="cursor: pointer;position:relative;left:3px;" data-id="'+ answer.id +'" class="btn-less"><i class="bx bx-trash" style="position: relative;top:1px;color:#f56e6e;"></i></a>';
        html += '                </label>';
        html += '            </div>';
        html += '        </fieldset>';
        html += '    </div>';
        html += '</div>';

        return html;
    }

    function loadSelect() {

        var select = '';
        select += '<select class="form-control" style="border-color:#3567DF;color: #3568df;" id="load_select">';
        for (var i = 0; i < arr_questions_id.length; i++) {
            var quest = i + 1;
            select += '<option value="'+arr_questions_id[i]+'">Questão '+quest+'</option>';
        }
        select += '</select>';
        return select;
    }

    function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function imageHandler(quill) {

        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.click();

        input.onchange = () => {
            const file = input.files[0];

            if (/^image\//.test(file.type)) {

                var form_data = new FormData();                  
                form_data.append('file', file);

                block();
                $.ajax({
                    url: '/recruitment/question/image/upload',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                    success: function(response){

                        if(response.success) {
                            const range = quill.getSelection();
                            quill.insertEmbed(range.index, 'image', response.url);

                            unblock();
                        } else {
                            $error(response.message);    
                        }
                    }, 
                    error: function(err){
                        $error(err);
                    }
                });
            } else {
                $error('Você só pode fazer uploads de imagens!');
            }
        };
    }

    function reloadAnswer(object) {

        var html = '';
        for (var i = 0; i < object.length; i++) {
            var column = object[i];

            html += '<tr>';
            html += '<td class="text-bold-400">'+ column.answer +'</td>';
            html += '<td style="text-align:right;white-space: nowrap;">'
            if(column.is_correct == 1) {
                html += '<i class="bx bx-check-circle mr-2" style="color:#39DA8A;"></i>';
            } else {
                html += '<i class="bx bx-x-circle mr-2" style="color:#f10a0a;"></i>';
            }
            html += "<a onclick='deleteTableAnswer(this)' style='cursor: pointer;' data-id='"+ i +"' class='btn-less'><i class='badge-circle badge-circle-light-secondary bx bx-trash font-medium-1'></i></a>";
            html += '</td>'
            html += '</tr>';
        }
        return html;
    }

    function reloadCandidates(object) {
        var html = '';
        for (var i = 0; i < object.length; i++) {
            var column = object[i];

            html += '<tr>';
            html += '<td>'+ column.name +'</td>';
            html += '<td>'+ column.email +'</td>';
            html += "<td><a onclick='deleteTableCandidate(this)' style='cursor: pointer;' data-id='"+ i +"' class='btn-less'><i class='badge-circle badge-circle-light-secondary bx bx-trash font-medium-1'></i></a></td>";
            html += '</tr>';
        }
        return html;
    }

    function deleteSingleAnswer(el) {

        var answer_id = $(el).attr('data-id');
        Swal.fire({
            title: 'Excluir pergunta?',
            text: "Deseja confirmar a exclusão da questão!",
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
                ajaxSend('/recruitment/answer/delete/ajax', {answer_id: answer_id}, 'GET', 6000, '').then(function(result){
                    if(result.success) {
                        $success(result.message); 
                        $(el).parent().remove();
                    }
                    unblock();
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                });
            } 
        });
    }

    function editSingleAnswer(el) {

        var id_answer = $(el).attr('data-id');

        block();
        ajaxSend('/recruitment/answer/edit/ajax', {id_answer: id_answer}, 'GET', 6000).then((response) => {

            if(response.success) {
                $("#field_answer_edit").val(response.answer.description);
                $("#answer_status_edit").val(response.answer.is_correct);
                $("#answer_id").val(response.answer.id);
                $("#modal_answer_edit").modal('show');
            }
            unblock();
        })
        .catch((error) => {
            $error(error.message);
            unblock();
        });
    }

    function editQuestion(el) {

        var data_id = $(el).attr('data-icon-id');

        block();
        ajaxSend('/recruitment/question/return/ajax', {id_question: data_id}, 'GET', 6000).then((response) => {

            if(response.success) {   
                field_question_2.root.innerHTML = response.question.title;
                $("#question_id").val(data_id);
                $("#modal_question_edit").modal('show');
            }
            unblock();
        })
        .catch((error) => {
            $error(error.message);
            unblock();
        });        

    }

    function deleteAllQuestion (el) {

        var question_id = $(el).attr('data-question-id');

        Swal.fire({
            title: 'Excluir Questão!',
            text: "Deseja confirmar a exclusão da questão e de suas respostas?",
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
                window.location.href = "/recruitment/question/delete/ajax/" + question_id;
            } 
        });
    }
    
    function deleteTableCandidate(el) {
        var index = $(el).attr('data-id');
        arr_candidates.splice(index, 1);
        $(el).parent().parent().remove();
    }
</script>
@endsection