@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">TI - Criação de software</h5>
                <div class="breadcrumb-wrapper col-12">
                    Criação de uma nova tarefa
                </div>
                </div>
            </div>
            </div>
        </div>

        <div class="alert alert-info alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error"></i>
                <span>
                    Sua tarefa será avaliada pelo responsável da área e em seguida aprovada ou não.
                    <br> Quando tivermos a situação da sua avaliação, iremos enviar um email para você.
                </span>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form action="/ti/developer/update" id="sendProject" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="type">Motivo</label>
                                        <select class="form-control" id="type" value="<?= $type ?>" name="type">
                                            <option value="1" @if ($type == 1) selected @endif>Nova implementação</option>
                                            <option value="2" @if ($type == 2) selected @endif>Correção de erro</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="subject">Título</label>
                                        <input type="text" class="form-control" id="subject" value="<?= $subject ?>" name="subject" placeholder="...">
                                        <div class="form-text text-muted">Fale o titulo da tarefa</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Descrição completa da tarefa</label>
                                        <textarea id="description" rows="10" class="form-control" name="description"><?= $description ?></textarea>
                                        <div class="form-text text-muted">Seja o mais detalhado possível para que possa ser avaliado.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="attach">Anexo</label>
                                        <input type="file" class="form-control" name="attach" id="attach">
                                        <div class="form-text text-muted">Caso precise anexar um arquivo.</div>
                                    </div>
                                    <?php if (!empty($attach)) { ?>
                                    <div class="form-group">
                                        <a href="<?= $attach ?>" target="_blank" class="text-primary font-weight-bold">Anexo</a>
                                    </div>
                                    <?php } ?>
                                </div>
                                @if ($has_analyze == 0)
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" style="width:100%;"><?php if ($id == 0) { ?>Criar tarefa<?php } else { ?>Atualizar projeto<?php } ?></button>
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


   
@if ($has_analyze == 1)
<div class="mb-2 cursor-pointer" id="showAnalyze" style="position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
    <i class="bx bx-up-arrow-alt"></i>
    <br>Mostrar análise
</div>

<div class="card text-center" id="Analyze" style="width: 395px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; opacity: 0.9;z-index: 99;">
    <div class="card-content">
        <button type="button" id="HAnalyze" class="close HideAnalyze" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>
        <div class="card-body">
        <form id="AnalyzeForm" action="/trip/analyze-in/update" method="post">
        <input type="hidden" name="reason" id="reason">
        <input type="hidden" name="is_approv" id="is_approv" value="1">

        <div class="row">

            <div class="col-sm-12 d-flex justify-content-center">
                <div class="form-group">
                    <label for="password" class="input-float float-left">{{ __('trip_i.trc_5') }}</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <div class="form-text text-muted">{{ __('trip_i.trc_6') }}</div>
                </div>
            </div>

            <div class="col-sm-12 d-flex justify-content-center">
                <button type="button" data-toggle="modal" data-target="#modal-approv" class="btn btn-success min-width-125 mr-1">{{ __('trip_i.trc_7') }}</button> 
                <button type="button" data-toggle="modal" data-target="#modal-reprov" class="btn btn-danger min-width-125 mb-20">{{ __('trip_i.trc_8') }}</button> 
            </div>

            @if ($has_suspended == 0)
            <div class="col-sm-12 d-flex justify-content-center">
                <button type="button" data-toggle="modal" data-target="#modal-suspending" class="btn btn-primary mt-1">SUSPENDER</button>
            </div>
            @endif
        </div>
    
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modal-approv" tabindex="-1" role="dialog" aria-labelledby="modal-approv" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header bg-success">
        <h5 class="modal-title white" id="modal-approv">APROVAR REEMBOLSO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>
        </div>
        <div class="modal-body">
            <div class="row">

                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="r_val_1">observação</label>
                        <textarea class="form-control" id="r_val_1" name="r_val_1" rows="6" placeholder="..."></textarea>
                    </fieldset>
                </div>

            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-success ml-1" data-dismiss="modal">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block" onclick="approv();">APROVAR</span>
        </button>
        </div>
    </div>
    </div>
</div>

<div class="modal fade text-left" id="modal-reprov" tabindex="-1" role="dialog" aria-labelledby="modal-reprov" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header bg-danger">
        <h5 class="modal-title white" id="modal-reprov">REPROVAR REEMBOLSO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>
        </div>
        <div class="modal-body">
            <div class="row">

                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="r_val_2">Motivo</label>
                        <textarea class="form-control" id="r_val_2" name="r_val_2" rows="6" placeholder="..."></textarea>
                    </fieldset>
                </div>

            </div>
        </div>
        <div class="modal-footer">
        <button type="button" id="reprov" class="btn btn-danger ml-1" data-dismiss="modal">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block" onclick="reprov();">REPROVAR</span>
        </button>
        </div>
    </div>
    </div>
</div>

<div class="modal fade text-left" id="modal-suspending" tabindex="-1" role="dialog" aria-labelledby="modal-suspending" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary">
        <h5 class="modal-title white" id="modal-suspending">SUSPENDER SOLICITAÇÃO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>
        </div>
        <div class="modal-body">
            <div class="row">

                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="people">Falar com</label>
                        <select class="js-select2 form-control" id="people" name="people" style="width:100%" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                            <option></option>
                            @if ($userall)
                            <?php foreach ($userall as $key) { ?>
                                <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                            <?php } ?>
                            @endif
                        </select>
                    </fieldset>
                </div>
                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="r_val_1">Mensagem</label>
                        <textarea class="form-control" id="r_val_3" name="r_val_3" rows="6" placeholder="..."></textarea>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block" onclick="suspending();">SUSPENDER</span>
        </button>
        </div>
    </div>
    </div>
</div>
@endif

    <script src="/ckeditor/ckeditor.js"></script>
    <script src="/ckeditor/init.js"></script>
    <script>
        function suspending() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else {
            block();
            window.location.href = "/misc/suspended/request/4/<?= $id ?>?r_val_3=" + $("#r_val_3").val() + "&password=" + $("#password").val() + "&people=" + $("#people").val();
        }
    }
    function approv() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else {
            block();
            window.location.href = "/ti/developer/analyze/<?= $id ?>/1?description=" + $("#r_val_1").val() + "&password=" + $("#password").val();
        }
    }

    function reprov() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else if ($("#r_val_2").val() == "") {

            return error("Por favor, digite o motivo da reprovação!");
        } else {
            block();
            window.location.href = "/ti/developer/analyze/<?= $id ?>/2?description=" + $("#r_val_2").val() + "&password=" + $("#password").val();
            }
    }

        var editor_pt, editor_en;
        $(document).ready(function () {
            $(".js-select2").select2({
                maximumSelectionLength: 1,
            });
            $("#HAnalyze").click(function (e) { 
                $("#Analyze").hide();
            
            });

            $("#showAnalyze").click(function (e) { 
                $("#Analyze").show();
                
            });
            $("#sendProject").submit(function (e) { 
                if ($("#subject").val() == "") {
    
                    error('É necessário o preenchimento do titulo.');
                    e.preventDefault();
                    return;
                } else if ($("#description").val() == "") {
    
                    error('Digite a descrição completa da sua tarefa.');
                    e.preventDefault();
                    return;
                }

                block();
                
            });
    
            setInterval(() => {
                $("#mTI").addClass('sidebar-group-active active');
                $("#mTIDeveloper").addClass('sidebar-group-active active');
                $("#mTIDeveloperNew").addClass('active');
            }, 100);
            
        });
        </script>
@endsection