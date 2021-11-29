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
                            Interação com: {{ $protocol->code }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header row">
        </div>
        <div class="alert alert-danger alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error"></i>
                <span>
              Caso tenha O.S anexada ao protocolo, por favor, verifique se há a necessidade de pagá-la, caso não hajá, cancele a O.S, antes de concluir ou cancelar o protocolo.
              </span>
            </div>
        </div>
        <div class="content-body">

            <section class="invoice-edit-wrapper">
                <div class="row">
                    <!-- invoice view page -->
                    <div class="@if (!$os) col-xl-9 col-md-8 @endif col-12 widget-chat-card">
                        <div class="widget-chat widget-chat-messages">
                            <div class="card">
                                <div class="card-header border-bottom p-0">
                                    <div class="media m-75" style="height: 30px;">
                                        <div class="media-body">
                                            <h6 class="media-heading mb-0 pt-25"><a target="_blank" href="/sac/client/edit/{{ $protocol->client_id }}">{{ $client->name }} ({{ $client->identity }})</a>
                                            </h6>
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
                                            {{ $protocol->description }}<br><br>
                                        </div>
                                        @if (count($messages) > 0)
                                            @foreach ($messages as $key)
                                                @if ($key->r_code != null or $key->authorized_id == 1864)
                                                    <div class="chat">
                                                        @if ($key->r_code == Session::get('r_code'))
                                                        <button type="button" onclick="editInteraction(this);" data-json='<?= htmlspecialchars(json_encode($key), ENT_QUOTES, "UTF-8") ?>' class="btn btn-icon rounded-circle btn-success glow" style="position: absolute;right: 4px;z-index: 9;"><i class="bx bx-pencil"></i></button>
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
                                                                            <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer">
                                                                                <i class="bx bxs-file text-primary" style="font-size: 90px;"></i>
                                                                            </a>
                                                                        @endif
                                                                    @else
                                                                        <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                                                    @endif
                                                                    <br>
                                                                @endif
                                                                <?= ($key->message) ?>
                                                                <span class="chat-time" style="left: 0;"><a target="_blank" href="/sac/client/edit/{{ $protocol->client_id }}">{{ $client->name }} ({{ $client->identity }})</a> {{ date('d/m/Y H:i', strtotime($key->created_at)) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 420px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 156px;"></div></div></div>
                                @if ($protocol->is_refund == 1)
                                    <div class="card-footer border-top p-1 text-white bg-danger text-center">
                                        O ATENDIMENTO FOI REEMBOLSADO
                                    </div>
                                @elseif ($protocol->is_completed == 1)
                                    <div class="card-footer border-top p-1 text-white bg-success text-center">
                                        O ATENDIMENTO FOI ENCERRADO
                                    </div>
                                @elseif ($protocol->is_cancelled == 1)
                                    <div class="card-footer border-top p-1 text-white bg-danger text-center">
                                        O ATENDIMENTO FOI CANCELADO
                                    </div>
                                @else
                                    <div class="card-footer border-top p-1 text-white">
                                        <form class="d-flex align-items-center" action="/sac/warranty/msg" id="sendMsg" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <input type="hidden" name="send_type" id="send_type" value="1">
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
                    @if (!$os)
                        <div class="col-xl-3 col-md-4 col-12">
                            <div class="card invoice-action-wrapper shadow-none border">
                                @if ($protocol->pending_completed == 1)
                                    <div class="invoice-action-btn mb-1 bg-warning text-center text-white" style="padding: 5px; width: 100%;">
                                        PENDENTE DE FINALIZAÇÃO
                                    </div>
                                @endif
                                @if ($protocol->is_denied == 1)
                                    <div class="invoice-action-btn mb-1 bg-danger text-center text-white" style="padding: 5px; width: 100%;">
                                        FINALIZAÇÃO NEGADA
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="invoice-action-btn mb-1">
                                        @if (count($sp_model) > 0)
                                            @foreach ($sp_model as $item)
                                                <h6><b>Modelo:</b> {{ $item->model }}</h6>
                                                @if ($item->serial_number)
                                                    <h6><b>N. Série:</b> {{ $item->serial_number }}</h6>
                                                @else
                                                    <h6><b>N. Série:</b> Não informado</h6>
                                                @endif
                                                <br>
                                            @endforeach
                                        @else
                                            <h6><b>Modelo:</b> Não informado <br><small>Você precisa informar um modelo na edição para poder transformar o protocolo em atendimento em garantia.</small></h6>
                                        @endif
                                        <hr>
                                        <h6><b>Data de abertura:</b> {{ date('d/m/Y', strtotime($protocol->created_at)) }}</h6>
                                        <h6><b>Data da compra:</b> @if ($protocol->buy_date == '0000-00-00 00:00:00') Não informado  @else {{ date('d/m/Y', strtotime($protocol->buy_date)) }} @endif</h6>
                                        <h6><b>Telefone:</b> {{ $client->phone }}</h6>
                                        <h6><b>Telefone:</b> {{ $client->phone_2 }}</h6>
                                        <h6><b>Atend. em garantia:</b> @if ($protocol->is_warranty == 1) Sim @else Não @endif</h6>
                                        <h6><b>Tipo:</b> @if ($protocol->type == 1) Reclamação @elseif ($protocol->type == 2) Atend. em garantia @elseif ($protocol->type == 3) Dúvida técnica @elseif ($protocol->type == 6) Outros @endif</h6>
                                        <h6><b>Instalado por:</b> @if ($protocol->installed_by == 1) Empresa particular @elseif ($protocol->installed_by == 2) Posto autorizado Gree @endif</h6>
                                        @if ($protocol->number_nf)<h6><b>Nota fiscal:</b> {{ $protocol->number_nf }}</h6>@endif
                                        <h6><b>Endereço:</b> {{ $protocol->address }}</h6>
                                        <h6><b>Complemento:</b> {{ $protocol->complement }}</h6>
                                    </div>
                                    <hr>
                                    <form action="/sac/warranty/update/{{ $id }}" id="w_upd" method="post">
                                        @if (count($sp_model) > 0)
                                            <div class="invoice-action-btn mb-1">
                                                <fieldset class="form-group">
                                                    <label for="alertRequest">Notificar autorizadas</label>
                                                    <select class="form-control" id="alertRequest" name="alertRequest">
                                                        <option value="0" selected>Não</option>
                                                        <option value="1">Sim</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="invoice-action-btn mb-1">
                                                <fieldset class="form-group">
                                                    <label for="is_warranty">É atend. em garantia?</label>
                                                    <select class="form-control" id="is_warranty" name="is_warranty">
                                                        <option value="0" @if ($protocol->is_warranty == 0) selected @endif>Não</option>
                                                        <option value="1" @if ($protocol->is_warranty == 1) selected @endif>Sim</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                        @endif
										
                                        <div class="invoice-action-btn mb-1">
                                            <fieldset class="form-group">
                                                <select class="form-control" id="warranty_extend" name="warranty_extend">
                                                    <option value="" ></option>
													<option value="1" @if ($protocol->warranty_extend == 1) selected @endif>Em processo de garantia estendida.</option>
													<option value="2" @if ($protocol->warranty_extend == 2) selected @endif>Garantia estendida concluida.</option>
                                                </select>
												<p>Caso esse protocolo tenha envolvimento com garantia estendida, por favor, informar.</p>
                                            </fieldset>
                                        </div>
										
                                        <div class="invoice-action-btn mb-1">
                                            <fieldset class="form-group">
                                                <select class="form-control" id="status" name="status">
                                                    <option value="1" @if ($protocol->in_wait == 1 and $protocol->in_progress == 0 and $protocol->is_completed == 0 and $protocol->is_cancelled == 0) selected @endif>Verificando Atend.</option>
													 <option value="6" @if ($protocol->in_wait == 1 and $protocol->in_wait_documents == 1 and $protocol->in_progress == 0 and $protocol->is_completed == 0 and $protocol->is_cancelled == 0) selected @endif>Aguard. Documentos</option>
                                                    <option value="2" @if ($protocol->in_wait == 1 and $protocol->in_progress == 1 and $protocol->is_completed == 0 and $protocol->is_cancelled == 0) selected @endif>Em andamento</option>
                                                    <option value="3" @if ($protocol->is_completed == 1 and $protocol->is_cancelled == 0) selected @endif>Concluído</option>
                                                    <option value="4" @if ($protocol->is_cancelled == 1 and $protocol->is_refund == 0) selected @endif>Cancelado</option>
                                                    <option value="5" @if ($protocol->is_refund == 1 and $protocol->is_cancelled == 1) selected @endif>Rembolso Pago</option>
													<option value="7" @if ($protocol->is_refund_pending == 1 and $protocol->is_cancelled == 1) selected @endif>Rembolso Pendente</option>
                                                </select>
                                            </fieldset>
                                        </div>

                                        <div class="invoice-action-btn mb-1">
                                            <fieldset class="form-group">
                                                <label for="type">Tipo de atendimento</label>
                                                <select class="form-control" id="type" name="type">
                                                    <option value=""></option>
                                                    <option value="1" @if ($protocol->type == 1) selected @endif>Reclamação</option>
                                                    <option value="2" @if ($protocol->type == 2) selected @endif>Atend. em garantia</option>
                                                    <option value="3" @if ($protocol->type == 3) selected @endif>Dúvida técnica</option>
                                                    <option value="4" @if ($protocol->type == 4) selected @endif>Revenda</option>
                                                    <option value="5" @if ($protocol->type == 5) selected @endif>Credenciamento</option>
                                                    <option value="7" @if ($protocol->type == 7) selected @endif>Atendimento fora de garantia</option>
                                                    <option value="8" @if ($protocol->type == 8) selected @endif>Atendimento negado (erro de inst.)</option>
                                                    <option value="9" @if ($protocol->type == 9) selected @endif>Autorização de instalação</option>
                                                    <option value="10" @if ($protocol->type == 10) selected @endif>Atendimento tercerizado</option>
													<option value="11" @if ($protocol->type == 11) selected @endif>Atendimento em cortesia</option>
                                                    <option value="6" @if ($protocol->type == 6) selected @endif>Outros</option>
                                                </select>
                                            </fieldset>
                                        </div>

                                        <div class="invoice-action-btn mb-1">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <span>Atualizar interação</span>
                                            </button>
                                        </div>
                                        @if (count($all_os) > 0)
                                            <div class="invoice-action-btn mb-1">
                                                <button data-toggle="modal" data-target="#full-scrn" type="button" class="btn btn-danger btn-block">
                                                    <span>Visualizar OS(s)</span>
                                                </button>
                                            </div>
                                        @endif
                                        @if ($protocol->has_notify_assist == 0 or $protocol->has_notify_assist == 2)
                                            <div class="invoice-action-btn mb-1">
                                                <button type="button" onclick="notifyAssit(1, <?= $protocol->id ?>)" class="btn btn-success btn-block">
                                                    <span>Notificar assistência</span>
                                                </button>
                                            </div>
                                        @elseif ($protocol->has_notify_assist == 1)
                                            <div class="invoice-action-btn mb-1">
                                                <button type="button" onclick="notifyAssit(2, <?= $protocol->id ?>)" class="btn btn-warning btn-block">
                                                    <span>Assistência Respondeu</span>
                                                </button>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            <div class="invoice-payment">
                                <div class="invoice-payment-option mb-2">
                                    @if ($protocol->nf_file)
                                        <div class="invoice-action-btn mb-1">
                                            <a href="{{ $protocol->nf_file }}" target="_blank" rel="noopener noreferrer">
                                                <button class="btn btn-light-primary btn-block">
                                                    <span>Nota fiscal</span>
                                                </button>
                                            </a>
                                        </div>
                                    @endif
                                    @if ($protocol->c_install_file)
                                        <div class="invoice-action-btn mb-1">
                                            <a href="{{ $protocol->c_install_file }}" target="_blank" rel="noopener noreferrer">
                                                <button class="btn btn-light-primary btn-block">
                                                    <span>Comprovante de instalação</span>
                                                </button>
                                            </a>
                                        </div>
                                    @endif
                                    @if ($protocol->tag_file)
                                        <div class="invoice-action-btn mb-1">
                                            <a href="{{ $protocol->tag_file }}" target="_blank" rel="noopener noreferrer">
                                                <button class="btn btn-light-primary btn-block">
                                                    <span>Etiqueta do produto</span>
                                                </button>
                                            </a>
                                        </div>
                                    @endif
                                    @if ($report_os)
                                        @if ($report_os->diagnostic_test_part)
                                            <div class="invoice-action-btn mb-1">
                                                <a href="{{ $report_os->diagnostic_test_part }}" target="_blank" rel="noopener noreferrer">
                                                    <button class="btn btn-light-secondary btn-block">
                                                        <span>Relatório técnico (Peças)</span>
                                                    </button>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>
            @endif
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
        <div class="chat-content" style="margin: 0.67rem 0 0 0 !important;">

            <div class="alert mb-2 alert-shadow" style="margin-bottom: 1.5rem !important; box-shadow: 0 3px 8px 0 rgb(130 140 158 / 58%)" role="alert">
                <div class="hidden_msg" style="color: white; background-color: #5a8dee;">MOTIVO ABERTURA DE PROTOCOLO</div>
                {{ $protocol->description }}<br><br>
            </div>
            @if (count($messages) > 0)
                @foreach ($messages as $key)
                    @if ($key->r_code != null)
                        <div class="chat">
                            <div class="chat-body" style="margin: 0.67rem 0 0 0 !important;">
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
                            <div class="chat-body" style="margin: 0.67rem 0 0 0 !important;">
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
                        <div class="chat">
                            <div class="chat-body" style="margin: 0px !important;">
                                <div class="chat-message" style="padding: 0px !important;margin: 0px !important;">
                                    <div class="badge badge-pill badge-light-secondary my-1">
                  <span id="msg_system" class="text-center" style="position: relative; top: 9px;">
                    <p><?= ($key->message) ?></p>
                  </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="chat chat-left">
                            <div class="chat-body" style="margin: 0.67rem 0 0 0 !important;">
                                <div class="chat-message chat_msg">
                                    @if ($key->file)
                                        <?php $path_info = pathinfo($key->file); ?>
                                        @if (isset($path_info['extension']))
                                            @if ($path_info['extension'] == "jpg" or $path_info['extension'] == "jpeg" or $path_info['extension'] == "png" or $path_info['extension'] == "gif")
                                                <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                            @else
                                                <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer">
                                                    <i class="bx bxs-file text-primary" style="font-size: 90px;"></i>
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer"><img class="img-fluid" src="{{ $key->file }}" alt="" height="200" width="260"></a>
                                        @endif
                                        <br>
                                    @endif
                                    <?= ($key->message) ?>
                                    <span class="chat-time" style="left: 0;"><a target="_blank" href="/sac/client/edit/{{ $protocol->client_id }}">{{ $client->name }} ({{ $client->identity }})</a> {{ date('d/m/Y H:i', strtotime($key->created_at)) }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>

    <div class="modal fade text-left w-100" id="full-scrn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel20" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel20">Lista de OS(s) no protocolo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                            <tr>
                                <th>OS</th>
                                <th>AUTORIZADA</th>
                                <th>AÇÃO</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($all_os) > 0)
                                @foreach ($all_os as $key)
                                    <tr>
                                        <td class="text-bold-500">{{ $key->code }}</td>
                                        <td>{{ $key->name }}</td>
                                        <td>
                                            <div class="dropleft">
                                                <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" onclick="cancelOS(<?= $key->id ?>)" href="javascript:void(0)"><i class="bx bx-x mr-1"></i> Cancelar OS</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                </div>
            </div>
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
                    <form action="/sac/warranty/interactive/edit" id="formEditMessage" method="post">
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
        function print() {
            $('.chat-print').printThis({
                importCSS: true,            // import parent page css
                importStyle: true,
                pageTitle: "Interação com cliente no protocolo #{{ $protocol->code }}",
            });
        }

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
            })
        }

        $('#type_action').change(function (){
           if ($('#type_action').val() == 1) {
               $('.edit_field').show();
           }  else {
               $('.edit_field').hide();
           }
        });

        function notifyAssit(typ, id) {
            Swal.fire({
                title: typ == 1 ? 'Notificar assistência' : 'Assistência Respondeu',
                text:  typ == 1 ? 'Você realmente já tem todas as informações necessárias no protocolo pra realizar essa ação?' : 'Você irá dizer que assistência técnica da gree já respondeu a esse protocolo.',
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
                    window.location.href = "/sac/warranty/notify/assist/" + id;
                }
            })
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
                    window.location.href = "/sac/warranty/os/status/1/" + id;
                }
            })
        }
        function confirmaOS(id) {
            Swal.fire({
                title: 'Alteração de OS para pago',
                text: 'Você está prestes a alterar o status da OS para pago!',
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
                    window.location.href = "/sac/warranty/os/status/2/" + id;
                }
            })
        }
        function confirmSend() {
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
                $("#mSacAll").addClass('active');
            }, 100);
        });
    </script>
@endsection
