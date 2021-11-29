
@extends('gree_i.layout')

@section('content')

<style>
    .add-effect-tr, .tr-user:hover {
        transform: scale(1);
        -webkit-transform: scale(1);
        -moz-transform: scale(1);
        box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
        -webkit-box-shadow: 1px 2px 8px 1px rgb(0 0 0 / 30%);
        -moz-box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
        cursor: pointer;
        background-color: #f2f4f4;
    }

    .block-msg {
        padding:10px; 
        margin-bottom: 0px;
        box-shadow: -1px 4px 8px 0px rgb(55 70 95 / 28%);
    }
    .format-pre {
        background-color: #ffffff;
        color: #26282a;
        font-size: 15px;
        white-space: pre-wrap;
    }

    .table td:hover {
        border-bottom: 0px solid #DFE3E7;
    }
    .pagination {
        display: inline-flex;
        font-size: 13px;
    }
</style>    

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0" style="border-right: 1px solid #f2f4f4;">Comunicados e notificações</h5>
                    <!--<div class="breadcrumb-wrapper col-12">
                        Permissões
                    </div>-->
                </div>
            </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <section id="table-transactions-statistics">
            <div class="row ">
                <div id="table-transactions-history" class="col-lg-4 col-md-12">
                    <div class="card" style="height: 394px;">
                        <div class="card-header border-bottom">
                            <h5 class="card-title">Colaboradores</h5>
                            <div class="heading-elements">
                                <div class="dropdown invoice-filter-action">
                                    <button type="button" class="btn btn-primary btn-sm shadow" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive collaborator-table" style="overflow-y: auto;">
                            <table class="table mb-0">
                                <tbody id="tbody_collaborator">
                                    @if($users_notify->count() > 0) 
                                        @foreach ($users_notify as $index => $key)
                                        <tr class="tr-user" data-id="<?= $key->id ?>">
                                            <td style="padding: 0.9rem 2rem;">
                                                <div class="flex-content">
                                                    <span style="color:#53677d;"><?= $key->name ?></span><br>
                                                    <span class="text-muted font-small-2"><?= $key->sector? config('gree.sector')[$key->sector] : '' ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class=" d-flex justify-content-end align-items-end flex-column">
                                                    <button type="button" class="btn btn-icon rounded-circle btn-primary" style="box-shadow:0 2px 4px 0 rgb(0 0 0 / 66%) !important" onclick="loadModalNotif(this)" data-id="<?= $key->id ?>">
                                                        <i class="bx bx-plus "></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td style="text-align: center;">Não há colaboradores cadastrados!</td>
                                        </tr>    
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="table-statistics" class="col-lg-8 col-md-12">
                    <div class="card" style="height: 394px;">
                        <div class="card-header border-bottom">
                            <h5 class="card-title">Notificações: <span style="font-size: 16px; color: #3568df;"><?= $first_collaborator ? $first_collaborator : '' ?></span></h5>
                            <div class="heading-elements">
                                <ul class="list-inline" id="msg_paginate">

                                </ul>
                            </div>
                        </div>
                        <div class="table-responsive msg-table">
                            <table class="table table-borderless mb-0">
                                <tbody id="tbody_msg">
                                    @if($msg_last_user->count() > 0)
                                        @foreach ($msg_last_user as $key => $msg)
                                        <tr>
                                            <td>
                                                <blockquote class="blockquote border-left-3 block-msg
                                                    @if($msg->priority == 1)
                                                        border-left-primary 
                                                    @elseif($msg->priority == 2)    
                                                        border-left-warning 
                                                    @else    
                                                        border-left-danger
                                                    @endif
                                                ">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <p>{{$msg->title}} - <small><?= date('d/m/Y', strtotime($msg->created_at)) ?></small></p>
                                                            <pre class="format-pre"><?= $msg->message ?></pre>
                                                        </div>
                                                        <div class="col-md-3" style="text-align: end;">
                                                            <small style="position: relative;bottom: 5px;">{{$msg->collaborator_name}} ({{$msg->r_code}})</small>
                                                            <span class="bx bx-dots-vertical-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="deleteNotification(this)" data-id="<?= $msg->id ?>"><i class="bx bx-trash mr-1"></i> Excluir</a>
                                                            </div>
                                                        </div>
                                                    </div>    
                                                </blockquote>
                                            </td>
                                        </tr>  
                                        @endforeach
                                    @else
                                        <tr>
                                            <td style="text-align: center;">Não há notificações!</td>
                                        </tr>    
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal_add_msg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Enviar notificação</span>
            </div>
            <div class="modal-body">
                <input type="hidden" id="collaborator_id">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="status">Prioridade</label>
                            <select class="form-control" id="notify_priority">
                                <option></option>
                                <option value="1">Baixa</option>
                                <option value="2">Média</option>
                                <option value="3">Alta</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="message">Título de notificação</label>
                            <input type="text" id="title_message" class="form-control" placeholder="Informe um título">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="message">Mensagem de notificação</label>
                            <textarea id="notify_message" rows="4" class="form-control" required="Descreva a mensagem"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_send_notification">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Enviar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar Colaborador</span>
            </div>
            <form action="{{Request::url()}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Matrícula</label>
                                <input type="text" class="form-control" name="r_code" placeholder="Infomer a matrícula">
                            </div>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="type_action">Nome</label>
                                <input type="text" class="form-control" name="name" placeholder="Informe o nome">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Setor</label>
                                <select class="form-control" name="sector">
                                    <option></option>
                                    @foreach (config('gree.sector') as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Filtrar</span>
                    </button>
                </div>
            </form> 
        </div>    
    </div>   
</div>

<script>

    var next_msg = 1;
    var next_collaborator = 1;
    var data_id = {{ $collaborator_id }};
    
    $(document).ready(function () {

        $("#btn_send_notification").click(function() {

            if($("#notify_priority").val() == "") {
                $error('Selecione a prioridade');
            }
            else if($("#title_message").val() == "") {
                $error('Informe um título para mensagem');
            } 
            else if($("#notify_message").val() == "") {
                $error('Escreva uma mensagem');  
            } 
            else {
                block();
                ajaxSend('/notify/collaborator/notify/send', 
                { 
                    priority: $("#notify_priority").val(), 
                    title_notify: $("#title_message").val(), 
                    msg_notify: $("#notify_message").val(), 
                    collaborator_id: $("#collaborator_id").val()

                }, 'POST', 10000).then(function(result) {

                    if(result.success) {
                        $("#modal_add_msg").modal('hide');
                        $("#tbody_msg").prepend(loadMsg([result.user]));
                        $success(result.message); 
                    }
                    $("#notify_priority, #title_message, #notify_message").val('');
                    unblock();
                }).catch(function(err){
                    $error(err.message);
                });
            }
        });

        $(".msg-table").scroll(function(){
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

                if(next_msg == 1) {
                    next_msg = 2;
                }
                ajaxSend('/notify/collaborator/msg/ajax?page='+next_msg, {data_id: data_id}, 'GET', 10000).then(function(result) {

                    if(result.success) {
                        if(result.msg.data != 0) {
                            $("#tbody_msg").append(loadMsg(result.msg.data));
                        } else {
                            $error('Não há mais mensagens!');
                        }
                    }
                    next_msg++;
                }).catch(function(err){
                    $error(err.message);
                }); 
            } 
        });

        $(".collaborator-table").scroll(function() {
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                
                if(next_collaborator == 1) {
                    next_collaborator = 2;
                }
                ajaxSend('/notify/collaborator/list/ajax?page='+next_collaborator, '', 'GET', 10000).then(function(result) {

                    if(result.success) {
                        if(result.users_notify.data != 0) {
                            $("#tbody_collaborator").append(loadCollaborator(result.users_notify.data));
                        } else {
                            $error('Não há mais colaboradores!');
                        }
                    }
                    next_collaborator++;
                }).catch(function(err){
                    $error(err.message);
                });
            }    
        });
       
        setInterval(() => {
            $("#mRH").addClass('sidebar-group-active active');
            $("#mNotifyCollaborator").addClass('sidebar-group-active active');
            $("#mNewNotifyCollaborator").addClass('active');
        }, 100);
    });

    $(document).on('click',".tr-user",function() {

        var collab_id = $(this).attr("data-id");
        data_id = collab_id;

        block();
        ajaxSend('/notify/collaborator/msg/ajax', {data_id: collab_id}, 'GET', 10000).then(function(result) {

            if(result.success) {
                $("#tbody_msg").html(loadMsg(result.msg.data));
            }
            unblock();
        }).catch(function(err){
            $error(err.message);
        });
    });    

    function loadModalNotif($this) {
        $("#collaborator_id").val($($this).attr("data-id"));
        $("#modal_add_msg").modal('show');
    }

    function loadMsg(object) {

        var msg = '';

        if(object.length != 0)  {

            for (var i = 0; i < object.length; i++) {
                var column = object[i];
             
                var priority = '';
                if(column.priority == 1) {
                    priority = 'border-left-primary'; 
                } else if(column.priority == 2) {
                    priority = 'border-left-warning';
                } else { 
                    priority = 'border-left-danger';
                }

                var date ='';

                msg += '<tr>';
                msg += '    <td>';
                msg += '        <blockquote class="blockquote border-left-3 block-msg '+ priority +'">';
                msg += '            <div class="row">';
                msg += '                <div class="col-md-9">';
                msg += '                    <p>'+ column.title +' - <small>'+column.created_at.replace(/(\d*)-(\d*)-(\d*).*/, '$3/$2/$1')+'</small></p>';
                msg += '                    <pre class="format-pre">'+ column.message +'</pre>';
                msg += '                </div>';
                msg += '                <div class="col-md-3" style="text-align: end;">';
                msg += '                    <small style="position: relative;bottom: 5px;">'+ column.collaborator_name +' ('+ column.r_code +')</small>';
                msg += '                    <span class="bx bx-dots-vertical-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>';
                msg += '                    <div class="dropdown-menu dropdown-menu-right">';
                msg += '                        <a class="dropdown-item" href="javascript:void(0)" onclick="deleteNotification(this)" data-id="'+column.id+'"><i class="bx bx-trash mr-1"></i> Excluir</a>';
                msg += '                    </div>';
                msg += '                </div>';
                msg += '            </div>';
                msg += '        </blockquote>';
                msg += '    </td>';
                msg += '</tr>';
            }
        } else {
            msg += '<div style="margin-top: 30px; text-align: center;">';
            msg += '    <tr style="text-align: center;">';
            msg += '        <td>';   
            msg += '            <p>Não há notificações!</p>';       
            msg += '        </td>';
            msg += '    </tr>';        
            msg += '</div>';
        }

        return msg;
    }

    function loadCollaborator(data) {
        
        var html = '';

        for (var i = 0; i < data.length; i++) {
            
            var column = data[i];
            html += '<tr class="tr-user" data-id="'+ column.id +'">';
            html += '    <td style="padding: 0.9rem 2rem;">';
            html += '        <div class="flex-content">';
            html += '            <span style="color:#53677d;">'+column.name+'</span><br>';
            html += '            <span class="text-muted font-small-2">'+sector[column.sector]+'</span>';
            html += '        </div>';
            html += '    </td>';
            html += '    <td>';
            html += '        <div class=" d-flex justify-content-end align-items-end flex-column">';
            html += '            <button type="button" class="btn btn-icon rounded-circle btn-primary" style="box-shadow:0 2px 4px 0 rgb(0 0 0 / 66%) !important" onclick="loadModalNotif(this)" data-id="'+ column.id +'">';
            html += '                <i class="bx bx-plus "></i>';
            html += '            </button>';
            html += '        </div>';
            html += '    </td>';
            html += '</tr>';
        }
        return html;
    }

    function deleteNotification(el) {

        var notif_id =  $(el).attr('data-id');

        Swal.fire({
            title: 'Excluir Notificação',
            text: 'Deseja excluir essa notificação?',
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
                ajaxSend('/notify/collaborator/msg/delete', {notif_id: notif_id}, 'POST', 3000).then(function(result) {

                    if(result.success) {
                        $(el).closest('tr').remove();
                        $success(result.message);
                    }
                    unblock();
                }).catch(function(err){
                    $error(err.message);
                });
            }
        });
    }

    var sector = {
        1 : 'Comercial (CRAC)', 2 : 'Industrial', 3 : 'Financeiro', 4 : 'Expedição &amp; Recebimento', 5 : 'Importação &amp; Exportação',
        6 : 'Administração', 7 : 'Recursos humanos', 8 : 'Compras', 9 : 'TI', 10 : 'Manutenção', 99 : 'Geral', 100 : 'Marketing Interno',
        101 : 'Recepção', 102 : 'Comercial (CAC)', 103 : 'Comercial Internacional', 104 : 'Produção', 105 : 'Engenharia', 106 : 'Pós venda',
        107 : 'Assistência técnica', 108 : 'SAC', 109 : 'P&amp;D', 110 : 'Certificação', 111 : 'Treinamento', 112 : 'Jurídico', 113 : 'Qualidade',
        114 : 'Logistica', 115 : 'Trade'
    }
   
</script>
@endsection