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
                <?= $backlog->subject ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="row">
      <div class="col-12">
    @if (hasPermManager(4) or hasPermApprov(4))
    
        <button type="button" data-toggle="modal" data-target="#add-register" class="btn btn-primary mb-1">
          <i class='bx bx-add-to-queue mr-50'></i> Novo registro de atividade
        </button>
      
    @endif
    <button type="button" data-toggle="modal" data-target="#description-project" class="btn btn-secondary mb-1">
      <i class='bx bx-task mr-50'></i> Detalhes da tarefa
    </button>
    </div>
  </div>
    <section>
      <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <b>Solicitante</b>
                                <br><a target="_blank" href="/user/view/<?= $backlog->request_r_code ?>"><?= getENameF($backlog->request_r_code); ?></a>
                              </div>
                              <div class="col-md-3">
                                <b>Responsável</b>
                                <br><a target="_blank" href="/user/view/<?= $backlog->ti_user_r_code ?>"><?= getENameF($backlog->ti_user_r_code); ?></a>
                              </div>
                              <div class="col-md-3">
                                  <b>Criado em</b>
                                  <br>{{ date('d-m-Y', strtotime($backlog->created_at))}}
                              </div>
                              <div class="col-md-3">
                                <b>Data de término</b>
                                <br>@if ($backlog->date_end != "0000-00-00 00:00:00")<?= date('d-m-Y', strtotime($backlog->date_end)) ?>@endif
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>
    <div class="content-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row justify-content-center mt-1">
                                <div class="col-md-6">
                                    <ul class="widget-timeline ps ps--active-y">
                                      @if (count($history) > 0)
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
                                        @else
                                        <h4 class="text-center">Sem dados de atividade</h4>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="add-register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="myModalLabel1">Registrar atividade</h3>
        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
          <i class="bx bx-x"></i>
        </button>
      </div>
      <form action="/ti/developer/send/history" id="sendHistory" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $id ?>">
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="status">Status</label>
                  <select id="status" class="form-control" name="status">
                    <option></option>
                    <option value="1" @if ($backlog->is_completed == 1) selected @endif>Concluir tarefa</option>
                    <option value="2" @if ($backlog->is_cancelled == 1) selected @endif>Cancelar</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="date_end">Data de término</label>
                  <input type="text" id="date_end" class="form-control" value="@if ($backlog->date_end != "0000-00-00 00:00:00")<?= date('d-m-Y', strtotime($backlog->date_end)) ?>@endif" name="date_end">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="responsible">Responsável</label>
              <select id="responsible" class="form-control" name="responsible" style="width:100%">
                <option></option>
                @foreach ($users as $key)
                <option value="{{$key->r_code}}" @if ($key->r_code == $backlog->ti_user_r_code) selected @endif>{{$key->first_name }} {{$key->last_name }} ({{$key->r_code}})</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" rows="5" class="form-control" name="description"></textarea>
                <div class="form-text text-muted">{{ __('project_i.ph_16') }}</div>
            </div>
            <div class="form-group">
                <label for="attach">Anexo</label>
                <input type="file" class="form-control" name="attach" id="attach">
                <div class="form-text text-muted">{{ __('project_i.ph_18') }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Fechar</span>
        </button>
        <button type="submit" class="btn btn-primary ml-1">
          <i class="bx bx-check d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Enviar registro</span>
        </button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="description-project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="myModalLabel1">Detalhes da tarefa</h3>
        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
          <i class="bx bx-x"></i>
        </button>
      </div>
      <div class="modal-body">
        <p>
          <?= $backlog->description ?>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Fechar</span>
        </button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>    
$(document).ready(function () {
  $('#date_end').mask('00/00/0000', {reverse: false});
  setInterval(() => {
      $("#mTI").addClass('sidebar-group-active active');
      $("#mTIDeveloper").addClass('sidebar-group-active active');
      $("#mTIDeveloperList").addClass('active');
  }, 100);
  //CKEDITOR.replace( 'description' );
  $("#sendHistory").submit(function (e) { 

    if ($("#description").val() == "") {

      error('Você precisa informar o seu registro de atividade antes de enviar.');
      e.preventDefault();
      return;
    }

    $("#add-register").modal('toggle');
    block();
    
  });
});
</script>
@endsection