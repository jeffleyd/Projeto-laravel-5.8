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
</style>    

<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/editors/quill/quill.snow.css">

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Recrutamento</h5>
                    <div class="breadcrumb-wrapper col-12">
                        Questões de prova
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <form method="POST" action="/recruitment/question/edit_do" id="form_recruitment" class="form repeater-default">
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
                                        <button type="button" class="btn btn-outline-primary shadow" data-toggle="modal" data-target="#new_question">
                                            <i class="bx bx-plus"></i> Nova questão
                                        </button>
                                    </div>
                                    <div class="col-2">
                                        <div id="question_select"></div>                                        
                                    </div>
                                </div><hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div id="body_question">Não há questões cadastradas</div>
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
                                        <div id="instructions" name="instructions"></div>
                                        <textarea name="test_instructions" style="display: none;"></textarea>
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
                                            <input type="text" class="form-control" name="email_subject" id="email_subject" placeholder="Digite assunto do email" value="PROVA DE SELEÇÃO GREE BRASIL">
                                        </div>
                                    </div>    
                                </div>    
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">Título</label>
                                            <input type="text" class="form-control" name="email_content_title" id="email_content_title" placeholder="título vai no corpo do email" value="Segue abaixo as informações de acesso à prova">
                                        </div>
                                    </div>    
                                </div>    
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="description">Conteúdo do email</label>
                                        <textarea class="form-control" name="email_content" id="email_content" rows="10" placeholder="Informe o conteúdo do email"><?= $email_content ?></textarea>
                                    </div>    
                                </div>
                            </div>
                            <div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                                <div class="form-group">
                                    <label for="name">Título da prova</label>
                                    <textarea class="form-control" name="title_test" id="title_test" placeholder="Digite o título"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descrição da prova</label>
                                    <textarea class="form-control" name="title_description" id="title_description" placeholder="Digite a descrição"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="description">Tempo de prova</label>
                                    <input type="text" class="form-control time_mask" name="test_time" id="test_time" placeholder="HH:MM:SS">
                                </div>
                                <div class="form-group">
                                    <label for="description">Porcentagem de acertos para passar</label>
                                    <input type="number" class="form-control" name="test_percent" id="test_percent" placeholder="Ex: 80">
                                </div>
                                <div class="form-group">
                                    <ul class="list-unstyled mb-0 border p-2">
                                        <li class="d-inline-block mr-2">
                                          <fieldset>
                                            <div class="custom-control custom-radio">
                                              <input type="radio" class="custom-control-input" value="1" name="is_send" id="active">
                                              <label class="custom-control-label" for="active">Enviar</label>
                                            </div>
                                          </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2">
                                          <fieldset>
                                            <div class="custom-control custom-radio">
                                              <input type="radio" class="custom-control-input" value="0" name="is_send" id="desactive" checked>
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
            <button type="button" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1" id="btn_form_question" style="width: 100%;">Cadastrar prova</button>
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
                <button type="button" class="btn btn-light-secondary ml-1" id="btn_dismiss_question">
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

<script src="/admin/app-assets/vendors/js/editors/quill/quill.js"></script>

<script>

    var arr_candidates = [];
    var arr_answers = [];
    
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        ['image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],
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

        $("#btn_dismiss_question").click(function() {

            field_question.root.innerHTML = '';
            arr_answers = [];
            $('#table_answer').html(reloadAnswer([]));
            $("#new_question").modal('hide');
        });
        
        $("#btn_form_question").click(function() {

            if(field_question.getText().trim().length == 0) {
                $error('Adicione uma questão!');
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

        $('.time_mask').mask('00:00:00');

        setInterval(() => {
            $("#mRH").addClass('sidebar-group-active active');
            $("#mQuestion").addClass('sidebar-group-active active');
            $("#mQuestionNew").addClass('active');
        }, 100);
    });

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

    function deleteTableAnswer(el) {
        var index = $(el).attr('data-id');
        arr_answers.splice(index, 1);
        $(el).parent().parent().remove();
    }

    function deleteTableCandidate(el) {
        var index = $(el).attr('data-id');
        arr_candidates.splice(index, 1);
        $(el).parent().parent().remove();
    }
</script>
@endsection