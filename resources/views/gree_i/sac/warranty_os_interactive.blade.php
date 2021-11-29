@extends('gree_i.layout')

@section('content')
<style>
  .chat_msg {
    max-width: 50%;
  }
  .hidden_msg {
    background-color: black;
    width: 100%;
    text-align: center;
    padding: 5px;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 10px;
    border-radius: 5px;
  }
  .alert-shadow {
    box-shadow: 0 3px 8px 0 rgb(130 140 158 / 58%);
  }
  .chat-print {
    display: none;
  }
  @media print {
    .chat-print {
      display: block;
    }
  }
</style>
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/pages/app-chat.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Atendimento</h5>
                    <div class="breadcrumb-wrapper col-12">
                        Interação O.S: 
                        @if ($os->code != "")
                            {{ $os->code }}
                        @else
                            (Protocolo {{ $os->sacProtocol()->first()->code }})
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header row"></div>
    <div class="content-body">
        <section class="invoice-edit-wrapper">
            <div class="row">
                <div class="col-xl-9 col-md-8 widget-chat-card">
                    <div class="widget-chat widget-chat-messages">
                        <div class="card">
                            <div class="card-header border-bottom p-0">
                                <div class="media m-75" style="height: 30px;">
                                    <div class="media-body">
                                        <h6 class="media-heading mb-0 pt-25"><a target="_blank" href="/sac/authorized/edit/{{ $os->authorizedOs->id }}">{{ $os->authorizedOs->name }} ({{ $os->authorizedOs->identity }})</a></h6>
                                    </div>
                                </div>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0" style="width: 40px;">
                                        <li>
                                            <button type="button" onclick="print()" class="btn btn-icon btn-outline-primary mr-1 mb-1" style="position: absolute;left: 0;top: -9px;"><i class="bx bx-printer"></i></button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body widget-chat-container widget-chat-scroll ps ps--active-y">
                                <div class="chat-content">
                                    
                                    <div class="alert mb-2 alert-shadow" role="alert">
                                        <div class="hidden_msg" style="color: white; background-color: #5a8dee;">MOTIVO ABERTURA DE PROTOCOLO</div>
                                        {{ $os->sacProtocol()->first()->description }}<br><br>
                                    </div>
                                    @if (count($messages) > 0)
                                        @foreach ($messages as $key)
                                            @if ($key->r_code != null or $key->authorized_id == 1864)
                                            <div class="chat">
                                                @if ($key->r_code == Session::get('r_code'))
                                                    <button type="button" onclick="editInteraction(this);" data-json='<?= htmlspecialchars(json_encode($key), ENT_QUOTES, "UTF-8") ?>' class="btn btn-icon rounded-circle btn-success glow" style="position: absolute;right: 9px;z-index: 9; padding: 0.2rem 0.4rem;margin-top: -11px;box-shadow: 0 2px 4px 0 rgb(23 47 35 / 36%) !important;"><i class="bx bx-pencil"></i></button>
                                                @endif
                                                <div class="chat-body">
                                                    <div class="chat-message chat_msg">
                                                        @if ($key->message_visible == 0)
                                                            <div class="hidden_msg">APENAS A GREE PODE VER ESSA MENSAGEM</div>
                                                        @endif
                                                        @if ($key->file)
                                                            <?php $path_info = pathinfo($key->file); ?>
                                                            @if (isset($path_info['extension']))
                                                                @if ($path_info['extension'] == "jpg" or $path_info['extension'] == "jpeg" or $path_info['extension'] == "png" or $path_info['extension'] == "gif")
                                                                    <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                                                @else
                                                                    <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer">
                                                                        <i class="bx bxs-file" style="font-size: 90px; color:white;"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                                            @endif
                                                            <br>
                                                        @endif
                                                        <?= ($key->message) ?>
                                                        <span class="chat-time"><a target="_blank" href="/user/view/{{ $key->r_code }}">{{ getENameF($key->r_code) }}</a> ({{ date('d/m/Y H:i', strtotime($key->created_at)) }})</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif ($key->authorized_id)
                                            <div class="chat">
                                                <div class="chat-body">
                                                    <div class="chat-message chat_msg" style="background: #E15517 !important;">
                                                        @if ($key->message_visible == 0)
                                                            <div class="hidden_msg">APENAS A GREE PODE VER ESSA MENSAGEM</div>
                                                        @endif
                                                        <?= ($key->message) ?>
                                                        <span class="chat-time"><a target="_blank" href="/sac/authorized/edit/{{ $key->authorized_id }}">{{ getNameAuthorizedFull($key->authorized_id) }}</a> ({{ date('d/m/Y H:i', strtotime($key->created_at)) }})</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif ($key->is_system == 1)
                                            <div class="badge badge-pill badge-light-secondary my-1">
                                                <span id="msg_system" class="text-center" style="position: relative; top: 9px;">
                                                    <p><?= ($key->message) ?></p>
                                                </span>
                                            </div>
                                            <div class="chat" style="visibility: hidden">
                                                <div class="chat-body" style="margin: 0px !important;">
                                                    <div class="chat-message chat_msg" style="padding: 0px !important;margin: 0px !important;">
                                                        <span class="chat-time"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="chat chat-left">
                                                <div class="chat-body">
                                                    <div class="chat-message chat_msg">
                                                        @if ($key->file)
                                                            <?php $path_info = pathinfo($key->file); ?>
                                                            @if (isset($path_info['extension']))
                                                                @if ($path_info['extension'] == "jpg" or $path_info['extension'] == "jpeg" or $path_info['extension'] == "png" or $path_info['extension'] == "gif")
                                                                    <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                                                @else
                                                                    <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><i class="bx bxs-file text-primary" style="font-size: 90px;"></i></a>
                                                                @endif
                                                            @else
                                                                <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                                            @endif
                                                            <br>
                                                        @endif
                                                        <?= ($key->message) ?>
                                                        <span class="chat-time" style="left: 0;"><a target="_blank" href="/sac/authorized/edit/{{ $key->authorized_id }}">{{ $os->authorizedOs->name }}</a> {{ date('d/m/Y H:i', strtotime($key->created_at)) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                            </div>
                            <div class="ps__rail-y" style="top: 0px; height: 420px; right: 0px;">
                                <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 156px;"></div>
                            </div>
                            @if ($os->is_paid == 1)
                            <div class="card-footer border-top p-1 text-white bg-success text-center">
                                A OS FOI ENCERRADO
                            </div>
                            @elseif ($os->is_cancelled == 1)
                                <div class="card-footer border-top p-1 text-white bg-danger text-center">
                                    A OS FOI CANCELADO
                                </div>
                            @else
                                <div class="card-footer border-top p-1 text-white">
                                    <form class="d-flex align-items-center" action="/sac/warranty/os/msg" id="sendMsg" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="send_type" id="send_type" value="1">
                                        <input type="hidden" name="authorized_id" id="authorized_id" value="{{ $os->authorized_id }}">
                                        <input type="hidden" name="os_code" id="os_code" value="{{ $os->code }}">
                                        <input type="hidden" name="protocol_code" id="protocol_code" value="{{ $os->sacProtocol()->first()->code }}">
                                        <input type="hidden" name="user_alert" id="user_alert" value="">
                                        <input type="file" name="file_msg" id="file_msg" style="display: none;">
                                        <textarea rows="1" name="msg" id="msg" class="form-control widget-chat-message mx-75" placeholder="{{ __('trip_i.trc_42') }}"></textarea>
                                        <button type="button" id="btnmsg" class="btn btn-primary glow"><i class="bx bx-paper-plane"></i></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-12">
                    <div class="card invoice-action-wrapper shadow-none border">
                        <div class="card-body">
                            <div class="invoice-action-btn mb-1">
                                <h6><b>Data da OS:</b> {{ date('d/m/Y', strtotime($os->created_at)) }}</h6>
								<h6><b>Data da reclamação:</b> {{ date('d/m/Y', strtotime($os->sacProtocol->created_at)) }}</h6>
                                <hr>
                                @if ($os->modelOs != null)
                                    @foreach ($os->modelOs as $model)
                                        @if ($model->sacProductAir)  
                                        <h6><b>Modelo:</b> {{ $model->sacProductAir->model }}</h6>
                                        <h6><b>N. Série:</b> {{ $model->serial_number }}</h6>
                                        <hr>
                                        @endif
                                    @endforeach
                                @endif
                                <h6>Autorizada/Credenciada</h6>
                                @if($os->authorizedOs->phone_1 != "")
                                    <h6><b>Telefone:</b> {{ $os->authorizedOs->phone_1 }}</h6>
                                @endif
                                @if($os->authorizedOs->phone_2 != "")
                                    <h6><b>Telefone:</b> {{ $os->authorizedOs->phone_2 }}</h6>
                                @endif
                                <h6><b>Cód. da Credenciada:</b> {{ $os->authorizedOs->code }}</h6>
                                <hr>
                                <h6>Análise técnica</h6>
                                <h6><b>Nome:</b> {{ $os->expert_name }}</h6>
                                <h6><b>Telefone:</b> {{ $os->expert_phone }}</h6>
                            </div>
                        </div>
                    </div>    
                    <div class="invoice-payment">
                        <div class="invoice-payment-option mb-2">
                            @if ($os->diagnostic_test_part != '')
                            <div class="invoice-action-btn mb-1">
                                <a href="{{ $os->diagnostic_test_part }}" target="_blank" rel="noopener noreferrer">
                                    <button class="btn btn-light-secondary btn-block">
                                        <span>Relatório técnico (Peças)</span>
                                    </button>
                                </a>
                            </div>
                            @endif
                            @if ($os->diagnostic_test != '')
                            <div class="invoice-action-btn mb-1">
                                <a href="{{ $os->diagnostic_test }}" target="_blank" rel="noopener noreferrer">
                                    <button class="btn btn-light-secondary btn-block">
                                        <span>Relatório técnico final</span>
                                    </button>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>    
</div>


<div class="chat-print">
    <style>
      .chat-content .chat-body .chat-message {
          position: relative !important;
          float: right !important;
          text-align: right !important;
          padding: 0.75rem 1rem !important;
          margin: 0.2rem 0.2rem 1.8rem 0 !important;
          max-width: calc(100% - 5rem) !important;
          clear: both !important;
          word-break: break-word !important;
          color: #FFFFFF !important;
          background: #5A8DEE !important;
          border-radius: 0.267rem !important;
          box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
      }
      .chat-content .chat-left .chat-message {
          text-align: left !important;
          float: left !important;
          margin: 0.2rem 0 1.8rem 0.2rem !important;
          color: #727E8C !important;
          background-color: #fafbfb !important;
          box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.3) !important;
      }
      .chat_msg {
        max-width: 50%;
      }
      .hidden_msg {
        background-color: black;
        width: 100%;
        text-align: center;
        padding: 5px;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 10px;
        border-radius: 5px;
      }
      .alert-shadow {
        box-shadow: 0 3px 8px 0 rgb(130 140 158 / 58%);
      }
    </style>

    <div class="chat-content">      
        <div class="alert mb-2 alert-shadow" role="alert">
            <div class="hidden_msg" style="color: white; background-color: #5a8dee;">MOTIVO ABERTURA DE PROTOCOLO</div>
            {{ $os->sacProtocol()->first()->description }}<br><br>
        </div>
        @if (count($messages) > 0)
            @foreach ($messages as $key)
                @if ($key->r_code != null)
                <div class="chat">
                    <div class="chat-body">
                        <div class="chat-message chat_msg">
                            @if ($key->message_visible == 0)
                                <div class="hidden_msg">APENAS A GREE PODE VER ESSA MENSAGEM</div>
                            @endif
                            @if ($key->file)
                                <?php $path_info = pathinfo($key->file); ?>
                                @if (isset($path_info['extension']))
                                    @if ($path_info['extension'] == "jpg" or $path_info['extension'] == "jpeg" or $path_info['extension'] == "png" or $path_info['extension'] == "gif")
                                        <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                    @else
                                        <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer">
                                            <i class="bx bxs-file" style="font-size: 90px; color:white;"></i>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                @endif
                                <br>
                            @endif
                            <?= ($key->message) ?>
                            <span class="chat-time"><a target="_blank" href="/user/view/{{ $key->r_code }}">{{ getENameF($key->r_code) }}</a> ({{ date('d/m/Y H:i', strtotime($key->created_at)) }})</span>
                        </div>
                    </div>
                </div>
                @elseif ($key->authorized_id)
                <div class="chat">
                    <div class="chat-body">
                        <div class="chat-message chat_msg" style="background: #E15517 !important;">
                            @if ($key->message_visible == 0)
                                <div class="hidden_msg">APENAS A GREE PODE VER ESSA MENSAGEM</div>
                            @endif
                            <?= ($key->message) ?>
                            <span class="chat-time"><a target="_blank" href="/sac/authorized/edit/{{ $key->authorized_id }}">{{ getNameAuthorizedFull($key->authorized_id) }}</a> ({{ date('d/m/Y H:i', strtotime($key->created_at)) }})</span>
                        </div>
                    </div>
                </div>
                @elseif ($key->is_system == 1)
                <div class="badge badge-pill badge-light-secondary my-1">
                    <span id="msg_system" class="text-center" style="position: relative; top: 9px;">
                        <p><?= ($key->message) ?></p>
                    </span>
                </div>
                <div class="chat" style="visibility: hidden">
                    <div class="chat-body" style="margin: 0px !important;">
                        <div class="chat-message chat_msg" style="padding: 0px !important;margin: 0px !important;">
                            <span class="chat-time"></span>
                        </div>
                    </div>
                </div>
                @else
                <div class="chat chat-left">
                    <div class="chat-body">
                        <div class="chat-message chat_msg">
                            @if ($key->file)
                                <?php $path_info = pathinfo($key->file); ?>
                                @if (isset($path_info['extension']))
                                    @if ($path_info['extension'] == "jpg" or $path_info['extension'] == "jpeg" or $path_info['extension'] == "png" or $path_info['extension'] == "gif")
                                        <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                    @else
                                        <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><i class="bx bxs-file text-primary" style="font-size: 90px;"></i></a>
                                    @endif
                                @else
                                    <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                @endif
                                <br>
                            @endif
                            <?= ($key->message) ?>
                            <span class="chat-time" style="left: 0;"><a target="_blank" href="/sac/authorized/edit/{{ $key->authorized_id }}">{{ $os->authorizedOs->name }}</a> {{ date('d/m/Y H:i', strtotime($key->created_at)) }}</span>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        @endif
    </div>
</div>

<div class="modal fade text-left" id="modal-select" tabindex="-1" role="dialog" aria-labelledby="modal-select" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-select">TIPO DE MENSAGEM</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <fieldset class="form-group">
                    <label for="users-list-verified">Avisar colaborador</label>
                    <select class="js-select2 form-control" id="r_code" name="r_code[]" style="width: 100%;" multiple>
                        <?php foreach ($userall as $key) { ?>
                            <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                        <?php } ?>
                    </select>
                </fieldset>
                <fieldset class="form-group">
                <label for="type_msg">Escolha uma opção</label>
                <select class="form-control" id="type_msg" name="type_msg">
                    <option value="1">Pública</option>
                    <option value="2">Oculta</option>
                </select>
                </fieldset>
                <ul class="list-unstyled mb-0">
                <li class="d-inline-block mr-2 mb-1">
                    <fieldset>
                    <div class="checkbox checkbox-shadow">
                        <input type="checkbox" name="check_file" id="check_file">
                        <label for="check_file">Marque essa opção para enviar anexo. <small>Caso não esteja marcado, não contém anexo.</small></label>
                    </div>
                    </fieldset>
                </li>
                </ul>
                <div class="img-preview text-center" style="display: none;">
                <img class="img-fluid" id="img-preview" src="" alt="" height="250" width="250">
                </div>
                <div class="file-preview text-center" style="display: none;">
                <span id="file-preview"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-sm-block d-none">Fechar</span>
                </button>
                <button type="button" onclick="confirmSend()" class="btn btn-primary ml-1 btn-sm">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-sm-block d-none">Continuar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modal-edit-msg" tabindex="-1" role="dialog" aria-labelledby="modal-select" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-select">EDITAR MESSAGEM</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="/sac/warranty/os/interactive/edit" id="formEditMessage" method="post">
                    <input type="hidden" name="message_id" id="message_id">
                    <fieldset class="form-group">
                        <label for="users-list-verified">Sua Ação</label>
                        <select class="form-control" id="type_action" name="type_action">
                            <option value="1">Atualizar</option>
                            <option value="2">Excluir</option>
                        </select>
                    </fieldset>
                    <fieldset class="form-group edit_field">
                        <label for="users-list-verified">Visibilidade</label>
                        <select class="form-control" id="message_visible" name="message_visible">
                            <option value=""></option>
                            <option value="1">Cliente pode ver</option>
                            <option value="2">Apenas a Gree</option>
                        </select>
                    </fieldset>
                    <fieldset class="form-group edit_field">
                        <label for="users-list-verified">Mensagem</label>
                        <textarea rows="10" class="form-control" id="message_txt" name="message_txt">
                        </textarea>
                    </fieldset>
                </form>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-sm-block d-none">Fechar</span>
                </button>
                <button type="button" onclick="confirmEditMessage()" class="btn btn-primary ml-1 btn-sm">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-sm-block d-none">Continuar</span>
                </button>
            </div>
        </div>
    </div>
</div>


<script>

    function editInteraction($this) {
    
        $($this).attr('data-json');
        var json = $($this).attr('data-json')
        obj = JSON.parse(json);
        $('#type_action').val(1);
        $('.edit_field').show();
        if (obj.message_visible == 1)
            $('#message_visible').val(1)
        else
            $('#message_visible').val(2)

        $('#message_id').val(obj.id);
        $('#message_txt').html(obj.message);
        $('#message_txt').html($('#message_txt').text());

        $('#modal-edit-msg').modal();
    }

    function confirmEditMessage() {
        Swal.fire({
            title: 'Aviso importante',
            text:  'Deseja continuar? Essa operação é irreversível.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Continuar!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                block();
                $('#modal-edit-msg').modal('toggle');
                $('#formEditMessage').submit();
            }
        });
    }

    function print() {
        $('.chat-print').printThis({
            importCSS: true,            // import parent page css
            importStyle: true,
            pageTitle: "Interação com cliente no protocolo #{{ $os->code }}",
        });
    }
    function cancelOS(id) {
        Swal.fire({
            title: 'Cancelamento da OS',
            text: 'Você está prestes a cancelar uma OS, ela não será paga se você realizar essa ação!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Confirmar!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
            if (result.value) {
              block();
              window.location.href = "/sac/warranty/os/status/" + id;
            }
        })
    }
    function confirmSend() {

        console.log('teste');

        $("#modal-select").modal('toggle');
        var type = $("#type_msg").val();
        $("#user_alert").val($("#r_code").val());
        $("#send_type").val(type);
        Swal.fire({
            title: type == 1 ? 'Envio público' : 'Envio oculto',
            text: type == 1 ? "Essa mensagem será vista por todo mundo, até mesmo o cliente." : "Você está enviando uma mensagem oculta, será vista apenas pela gree.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Confirmar!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
            if (result.value) {
                block();
                $("#sendMsg").submit();
            }
        })
    }
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                if ($('#file_msg')[0].files[0].type == "image/png" || $('#file_msg')[0].files[0].type == "image/jpg" || $('#file_msg')[0].files[0].type == "image/jpeg" || $('#file_msg')[0].files[0].type == "image/gif") {
                  $('#img-preview').attr('src', e.target.result);
                  $(".img-preview").show();
                } else {
                  $('#file-preview').html($('#file_msg')[0].files[0].name)
                  $(".file-preview").show();
                } 
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
        $(document).ready(function () {
          $(".js-select2").select2({
          });
          $("#btnmsg").click(function (e) { 
            e.preventDefault();
            $("#modal-select").modal();           
          });
          $("#check_file").click(function (e) { 
              if ($("#check_file").prop("checked")) {
                $('#file_msg').trigger('click');
              } else {
                $('#file_msg').val('');
                $(".img-preview").hide();
                $(".file-preview").hide();
              }
          });
          $("#file_msg").change(function(){
              readURL(this);
          });
          $("#w_upd").submit(function (e) { 
            block();
          });

             // Perfect Scrollbar
            //------------------
            // Widget - User Details -Perfect Scrollbar X
            if ($('.widget-user-details .table-responsive').length > 0) {
                var user_details = new PerfectScrollbar('.widget-user-details .table-responsive');
            }

            // Widget - Card Overlay - Perfect Scrollbar X - on initial level
            if ($('.widget-overlay-content .table-responsive').length > 0) {
                var card_overlay = new PerfectScrollbar('.widget-overlay-content .tab-pane.active .table-responsive');
            }

            // Widget - Card Overlay - Perfect Scrollbar X - on active tab-pane
            $('.widget-overlay-content a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var card_overlay = new PerfectScrollbar('.widget-overlay-content .tab-pane.active .table-responsive');
            })

            // Widget - timeline perfect scrollbar initialization
            if ($(".widget-timeline").length > 0) {
                var widget_chat_scroll = new PerfectScrollbar(".widget-timeline", { wheelPropagation: false });
            }
            // Widget - chat area perfect scrollbar initialization
            if ($(".widget-chat-scroll").length > 0) {
                var widget_chat_scroll = new PerfectScrollbar(".widget-chat-scroll", { wheelPropagation: false });
            }
            // Widget - earnings perfect scrollbar initialization
            if ($(".widget-earnings-scroll").length > 0) {
                var widget_earnings = new PerfectScrollbar(".widget-earnings-scroll",
                // horizontal scroll with mouse wheel
                {
                    suppressScrollY: true,
                    useBothWheelAxes: true
                });
            }
            // Widget - chat autoscroll to bottom of Chat area on page initialization
            $(".widget-chat-scroll").animate({ scrollTop: $(".widget-chat-scroll")[0].scrollHeight }, 800);

            setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSac").addClass('sidebar-group-active active');
            $("#mSacOSAll").addClass('active');
        }, 100);
        });
</script>
@endsection