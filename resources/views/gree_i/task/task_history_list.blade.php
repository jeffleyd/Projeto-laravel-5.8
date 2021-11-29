@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Tarefa:</h5>
              <div class="breadcrumb-wrapper col-12">
                <?= $task->title ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-md-12 <?php if ($task->r_code == Session::get('r_code')) { ?>col-xl-10<?php } ?>">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                                        <li class="nav-item current">
                                          <a class="nav-link active" id="home-tab-fill" data-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="true">
                                            Detalhes
                                          </a>
                                        </li>
                                        <?php if ($task->is_completed == 0 and $task->is_accept == 1 and $task->has_analyze == 0 and isset($me_resp)) { ?>
                                        <li class="nav-item">
                                          <a class="nav-link" id="profile-tab-fill" data-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">
                                            Nova atualização
                                          </a>
                                        </li>
                                        <?php } ?>
                                        <li class="nav-item">
                                          <a class="nav-link" id="messages-tab-fill" data-toggle="tab" href="#messages-fill" role="tab" aria-controls="messages-fill" aria-selected="false">
                                            Linha de atualizações
                                          </a>
                                        </li>
                                        <li class="nav-item">
                                          <a class="nav-link" id="settings-tab-fill" data-toggle="tab" href="#settings-fill" role="tab" aria-controls="settings-fill" aria-selected="false">
                                            Sub tarefas
                                          </a>
                                        </li>
                                      </ul>
                                      <div class="tab-content pt-1">
                                        <div class="tab-pane active" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">
                                            <p>
                                            <b>Gestor:</b> <?= $mng_task->first_name ?> <?= $mng_task->last_name ?>
                                            <br>
                                            <b>{{ __('project_i.ph_35') }}</b> <?= __('layout_i.'. $sector->name .'') ?>
                                            <br>
                                            <b>Começa em:</b> <?= date('Y-m-d', strtotime($task->start_date)) ?>
                                            <br>
                                            <b>Previsão de término:</b> <?= date('Y-m-d', strtotime($task->end_date)) ?>
                                            </p>
                                            <p class="text-center">
                                                <br>
                                                <b>Descrição:</b>
                                                <br><?= $task->description ?>
                                            </p>
                                        </div>
                                        <?php if ($task->is_completed == 0 and $task->is_accept == 1 and $task->has_analyze == 0 and isset($me_resp)) { ?>
                                        <div class="tab-pane" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                                            <form action="/task/update/history/<?= $task->id ?>" id="sendHistory" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="type" id="type">
                                
                                                <div class="form-group">
                                                    <fieldset class="text-center">
                                                        <div class="checkbox">
                                                            <input type="checkbox" name="iscompleted" class="checkbox-input" id="iscompleted" value="1">
                                                            <label for="iscompleted">{{ __('project_i.ph_12') }}</label>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">{{ __('project_i.ph_15') }}</label>
                                                    <textarea id="description" name="description"></textarea>
                                                    <div class="form-text text-muted">{{ __('project_i.ph_16') }}</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="attach">{{ __('project_i.ph_17') }}</label>
                                                    <input type="file" class="form-control" name="attach" id="attach">
                                                    <div class="form-text text-muted">{{ __('project_i.ph_18') }}</div>
                                                </div>
                                
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary" style="width:100%;">{{ __('project_i.ph_19') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                        <?php } ?>
                                        <div class="tab-pane" id="messages-fill" role="tabpanel" aria-labelledby="messages-tab-fill">
                                            <div class="row justify-content-center">
                                                <div class="col-md-6">
                                                    <ul class="widget-timeline ps ps--active-y">
                                                        <?php foreach ($history as $key) { ?>
                                                        <li class="timeline-items timeline-icon-<?= $key->color ?> active">
                                                          <div class="timeline-time"><?= date('Y-m-d', strtotime($key->date)) ?></div>
                                                          <h6 class="timeline-title"><?= $key->title ?></h6>
                                                          <div class="timeline-content" style="display:block">
                                                            <p><?= $key->description ?></p>
                                                            <?php if (!empty($key->attach)) { ?>
                                                                <?php if ($key->is_file == 0) { ?>
                                                                <br><a target="_bank" href="<?= $key->attach ?>">
                                                                        <img class="img-fluid" width="350" src="<?= $key->attach ?>" alt="">
                                                                    </a>
                                                                <?php } else { ?>
                                                                    <a target="_bank" href="<?= $key->attach ?>">
                                                                        <i class="bx bxs-file" style="float: left; height: 34px; font-size: 38px;"></i>
                                                                        {{ __('project_i.ph_25') }}
                                                                        <div class="font-w400 font-size-xs text-muted">{{ __('project_i.ph_26') }}</div>
                                                                    </a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                          </div>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="settings-fill" role="tabpanel" aria-labelledby="settings-tab-fill">
                                            <div class="table-responsive">
                                            <table id="list-datatable" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th>Setor</th>
                                                        <th>Tarefa</th>
                                                        <th>Começa em</th>
                                                        <th>Termina em</th>
                                                        <th>Status</th>
                                                        <?php if ($task->is_accept == 1 and isset($me_resp)) { ?>
                                                        <th>Ações</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($task_sub as $key) { ?>
                                                    <tr>
                                                        <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code) ?></a></td>
                                                        <td><?= __('layout_i.'. $key->name .'') ?></td>
                                                        <td><?= $key->title ?></td>
                                                        <td><?= date('Y-m-d', strtotime($key->start_date)) ?></td>
                                                        <td><?= date('Y-m-d', strtotime($key->end_date)) ?></td>
                                                        <td>
                                                        <?php if ($key->is_completed == 1) { ?>
                                                            <span class="badge badge-light-success">Concluído</span>
                                                        <?php } else if (date('Y-m-d') >= date('Y-m-d', strtotime($key->end_date))) { ?>
                                                            <span class="badge badge-light-danger">Atrasado</span></td>
                                                        <?php } else if (date('Y-m-d') >= date('Y-m-d', strtotime($key->start_date))) { ?>
                                                            <span class="badge badge-light-info">Em andamento</span></td>
                                                        <?php } else { ?>
                                                            <span class="badge badge-light-warning">Ainda não começou</span></td>
                                                        <?php } ?>
                                                        </td>
                                                        <?php if ($key->resp_r_code == Session::get('r_code')) { ?>
                                                        <td>
                                                            <div class="dropleft">
                                                                <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a onclick="subTaskEdit(0, [{'id': '<?= $key->id ?>', 'rcodes': '<?= $key->r_code ?>', 'title': '<?= $key->title ?>', 'description': '<?= $key->description ?>', 'start_date': '<?= date('Y-m-d', strtotime($key->start_date)) ?>', 'end_date': '<?= date('Y-m-d', strtotime($key->end_date)) ?>', 'attach': '<?= $key->attach ?>', 'iscompleted': '<?= $key->is_completed ?>'}]);" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i> {{ __('lending_i.lt_16') }}</a>
                                                                    <a onclick="subTaskDelete('<?= $key->id ?>');" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-trash mr-1"></i> {{ __('lending_i.lt_17') }}</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <?php if ($task->is_accept == 1 and isset($me_resp)) { ?>
                                                <button type="button" class="btn btn-outline-primary mb-20" onclick="subTaskEdit(1, []);" style="width: 100%">NOVA SUB TAREFA</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($task->r_code == Session::get('r_code')) { ?>
            <div class="col-md-12 col-xl-2">
                <?php foreach ($resp as $key) { ?>
                <div class="card">
                    <div class="card-header mx-auto pt-3">
                      <div class="avatar bg-rgba-primary p-50">
                        <img class="img-fluid" src="<?php if (empty($key->picture)) { ?>/media/avatars/avatar10.jpg<?php } else { ?><?= $key->picture ?><?php } ?>" alt="img placeholder" height="85" width="85">
                      </div>
                    </div>
                    <div class="card-content">
                      <div class="card-body text-center">
                        <h6><?= getENameF($key->r_code) ?></h6>
                        <p>Pessoa responsável</p>
                        <!-- <p class="px-1">Jelly beans halvah cake chocolate gummies.</p> -->
                        <div class="d-flex justify-content-around mb-1">
                            <a target="_blank" href="/user/view/<?= $key->r_code ?>">
                          <div class="card-icons d-flex flex-column" >
                            <i class="bx bx-camera font-medium-5 font-weight-bold"></i>
                          </div>
                            </a>
                            <a href="mailto:<?= $key->email ?>?subject=Sobre a tarefa #<?= $task->id ?>&amp;body=Para saber mais detalhes acesse esse link: <?= Request::root() ?>/task/view/history/<?= $task->id ?>">
                          <div class="card-icons d-flex flex-column" >
                            <i class="bx bx-user font-medium-5 font-weight-bold"></i>
                          </div>
                            </a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        
    </div>
</div>

    <div class="mb-2" style="text-align: center; width: 390px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99;">
        
        <?php if ($task->is_accept == 0 and $task->has_analyze == 1 and isset($me_resp)) { ?>
        <button type="button" onclick="acceptTask(1);" class="btn btn-sm btn-success">
            <i class="bx bx-check mr-1"></i>Eu aceito a tarefa
        </button>
        <button type="button" onclick="reasonEdit();" class="btn btn-sm btn-danger">
            <i class="bx bx-x mr-1"></i>Não aceito
        </button>
        <?php } ?>
        <?php if ($task->is_recuse == 1 and $task->has_analyze == 0 and $task->r_code == Session::get('r_code')) { ?>
        <button type="button" onclick="updateInfo();" class="btn btn-sm btn-info">
            Atualizar informações
        </button>
        <button type="button" onclick="reSend();" class="btn btn-sm btn-success">
            Re-enviar para colaborador(es)
        </button>
        <?php } ?>
        <?php if ($task->is_accept == 1 and $task->has_analyze == 1 and $task->r_code == Session::get('r_code')) { ?>
        <button type="button" onclick="FinishTask(1);" class="btn btn-sm btn-success">
            <i class="bx bx-check mr-1"></i>Finalizar tarefa
        </button>
        <button type="button" onclick="FinishTask(2);" class="btn btn-sm btn-danger">
            <i class="bx bx-x mr-1"></i>Recusar finalização
        </button>
        <?php } ?>  
    </div>

    <div class="modal fade text-left" id="modal-finish" tabindex="-1" role="dialog" aria-labelledby="modal-finish" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title" id="modal-finish">Decisão</h3>
              <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-sm-12">
                        <fieldset class="form-group">
                            <label for="r_val">Preencha a observação da escolha se houver</label>
                            <textarea class="form-control" id="r_val" name="r_val" rows="6" placeholder="..."></textarea>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="password">Senha secreta</label>
                            <input type="password" class="form-control" id="password" name="password" />
                        </fieldset>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="AnalyzeFinish" class="btn btn-primary ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Enviar Análise!</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade text-left" id="modal-reason" tabindex="-1" role="dialog" aria-labelledby="modal-reason" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title" id="modal-reason">Motivo da recusação</h3>
              <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-sm-12">
                        <fieldset class="form-group">
                            <label for="r_val">Preencha em baixo o seu motivo.</label>
                            <textarea class="form-control" id="r_val" name="r_val" rows="6" placeholder="..."></textarea>
                        </fieldset>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
              <button type="button" onclick="acceptTask(2);" class="btn btn-primary ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">RECUSAR!</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade text-left" id="modal-sub-task" tabindex="-1" role="dialog" aria-labelledby="modal-sub-task" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title title-st" id="modal-sub-task"></h3>
              <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
              </button>
            </div>
            <div class="modal-body">
                <form action="/task/update/subtask" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_subtask" id="id_subtask">
                <div class="row">
                    <div class="col-12 text-left">
                        <div class="form-group subiscompleted">
                            <fieldset>
                                <div class="checkbox">
                                    <input type="checkbox" name="subiscompleted" class="checkbox-input" id="subiscompleted" value="1">
                                    <label for="subiscompleted">Essa tarefa está concluída?</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <label for="rcodes">Responsável</label>
                            <select class="js-select2 form-control" id="rcodes" name="rcodes" style="width: 100%;" data-placeholder="Pesquise o nome ou matricula..." multiple>
                                <option></option>
                                <?php foreach ($usersall as $key) { ?>
                                    <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Assunto</label>
                            <input type="text" class="form-control" id="title" name="title" />
                        </div>
                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="6" placeholder="..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Começa em</label>
                            <input type="text" class="form-control" id="start_date" name="start_date">
                            <div class="form-text text-muted">Insira uma data inicial</div>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Termina em</label>
                            <input type="text" class="form-control" id="end_date" name="end_date">
                            <div class="form-text text-muted">Insira uma data de término</div>
                        </div>
                        <div class="form-group">
                            <label for="subattach">Anexo PDF, JPG ou PNG</label>
                            <input type="file" class="form-control" name="subattach" id="subattach">
                            <div class="form-text text-muted">Máximo de envio é de 10mb</div>
                        </div>
                        <div class="form-group hasattach">
                            <a href="#" id="hasattach" target="_blank" class="text-primary font-weight-bold">Ver anexo</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Atualizar tarefa</span>
              </button>
            </div>
          </div>
        </form>
        </div>
      </div>

<script>
    var type = 0;
    function subTaskDelete(id) {
        Swal.fire({
            title: 'Deletar',
            text: "Você tem certeza que deseja deletar?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
            cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    window.location.href = "/task/delete/subtask/" + id;
                }
            })
        
    }
    function subTaskEdit(isNew, object) {
        if (isNew == 1) {
            $(".title-st").html('Nova sub tarefa');
            $("#id_subtask").val(0);
            $("#title").val('');
            $("#description").val('');
            $("#start_date").val('');
            $("#end_date").val('');
            $(".hasattach").hide();
            $(".iscompleted").hide();
            $("#subiscompleted").val(0);
            $("#subiscompleted").removeAttr("checked");
            $("#subattach").attr('href', '#');
            $('.js-select2').val(0).trigger("change");
        } else {
            $('.js-select2').val(0).trigger("change");
            $("#subiscompleted").val(1);
            $(".title-st").html('Editando sub tarefa');
            $("#id_subtask").val(object[0]['id']);
            $('.js-select2').val([object[0]['rcodes']]).trigger('change');
            $("#title").val(object[0]['title']);
            $("#description").val(object[0]['description']);
            $("#start_date").val(object[0]['start_date']);
            $("#end_date").val(object[0]['end_date']);
            if (object[0]['attach'] != "") {
                $(".hasattach").show();
                $("#hasattach").attr('href', object[0]['attach']);
            } else {
                $(".hasattach").hide();
                $("#hasattach").attr('href', '#');
            }
            if (object[0]['iscompleted'] == '1') {
                $("#subiscompleted").attr("checked", "");
            } else {
                $("#subiscompleted").removeAttr("checked");
            }
        }
        $("#modal-sub-task").modal();
    }
        
    function acceptTask(type) {
        $("#modal-reason").modal('hide');
        Swal.fire({
            title: type == 1 ? 'Aceitar' : 'Recusar',
            text: type == 1 ? 'Você concorda com todas informações da tarefa?' : 'Tem certeza que deseja recusar?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
            cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    window.location.href = "/task/accept/<?= $task->id ?>?description=" + $("#r_val").val() + "&type="+ type;
                }
            })
    }
    
    function updateInfo() {
        window.location.href = "/task/<?= $task->id ?>";
    }
    function reSend() {
        block();
        window.location.href = "/task/resend/<?= $task->id ?>";
    }
    
    function reasonEdit() {
        $("#modal-reason").modal();
    }
    
    function FinishTask(choose) {
        type = choose;
        $("#modal-finish").modal();
    }
    
    
$(document).ready(function () {
    $(".js-select2").select2({
        maximumSelectionLength: 1,
    });
    $('#start_date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD'
        },
    });
    
    $('#end_date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD'
        },
    });
    <?php if ($task->is_completed == 0 and $task->is_accept == 1 and $task->has_analyze == 0 and isset($me_resp)) { ?>
    CKEDITOR.replace( 'description' );
    <?php } ?>
    $("#AnalyzeFinish").click(function (e) { 
        if ($("#r_val").val() == "" && type == 2) {
            
            return error('Preencha o motivo da sua reprovação!');
        } else if ($("#password").val() == "") {

            return error('Preencha a sua senha secreta para análise!');
        } else {

            $("#modal-finish").modal('toggle');
            Swal.fire({
                title: type == 1 ? 'Aceitar' : 'Recusar',
                text: type == 1 ? 'Tem certeza que deseja aceitar?' : 'Tem certeza que deseja recusar?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
                cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        block();
                        window.location.href = "/task/completed/<?= $task->id ?>/" + type + "?description=" + $("#r_val").val() +"&password="+ $("#password").val();
                    }
                })
        }
        
    });
    $("#sendHistory").submit(function (e) { 
        if (CKEDITOR.instances.description.getData() == "") {

            error('<?= __('project_i.ph_34') ?>');
            e.preventDefault();
            return;
        }
        
    });

    setInterval(() => {
        $("#mAdmin").addClass('sidebar-group-active active');
        $("#mTask").addClass('sidebar-group-active active');
    }, 100);
    
});
</script>
@endsection