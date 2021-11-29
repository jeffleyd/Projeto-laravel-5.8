@extends('gree_i.layout')

@section('content')
<style>
    .btn_interactive {
        background-color: #f7f7f7;
    }
    .btn_interactive:hover {
        background-color: #3568df;
        color: #fff;
    }
    .expedition_bg_color {
        background: #f2f4f4;
        /*color: #121417;*/
    }
    .icon-model-os {
        position: relative;
        left: 10px;
        top: 2px;
    }
    .icon-model-os:hover {
        color: #eee20c;
    }

</style>
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Atendimento</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de ordem de serviços
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">

        <section class="users-list-wrapper">
            <div class="row py-2 mb-2">
                <div class="col-12 col-sm-12 col-lg-4 cursor-pointer" onclick="getDayLeft(1, this)">
                    <div class="card text-center" id="left_5">
                        <div class="card-content">
                            <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto my-1">
                                <i class="bx bxs-error-alt font-medium-5"></i>
                            </div>
                            <p class="text-muted mb-0 line-ellipsis">5 - 14 dias <small>(Sem conclusão)</small></p>
                            <h2 class="mb-0">{{ $total_5 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-4 cursor-pointer" onclick="getDayLeft(2, this)">
                    <div class="card text-center" id="left_15">
                        <div class="card-content">
                            <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto my-1">
                                <i class="bx bxs-error-alt font-medium-5"></i>
                            </div>
                            <p class="text-muted mb-0 line-ellipsis">15 - 29 dias <small>(Sem conclusão)</small></p>
                            <h2 class="mb-0">{{ $total_15 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-4 cursor-pointer" onclick="getDayLeft(3, this)">
                    <div class="card text-center" id="left_30">
                        <div class="card-content">
                            <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1">
                                <i class="bx bxs-error-alt font-medium-5"></i>
                            </div>
                            <p class="text-muted mb-0 line-ellipsis">30+ dias <small>(Sem conclusão)</small></p>
                            <h2 class="mb-0">{{ $total_30 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="users-list-filter px-1">
            <form action="/sac/warranty/os/all" id="searchTrip" method="GET">
                <input type="hidden" id="_left_5" name="left_5" value="">
                <input type="hidden" id="_left_15" name="left_15" value="">
                <input type="hidden" id="_left_30" name="left_30" value="">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="type_people">Tipo de pesquisa</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="type_people" name="type_people" style="width: 100%;">
                                <option value="0" selected>Livre</option>
                                <option value="1">Física (CPF)</option>
                                <option value="2">Jurídica (CNPJ)</option>
                            </select>
                        </fieldset>
                    </div>
                    <div @if (Session::get('filter_line') == 1) class="col-12 col-sm-12 col-lg-8" @else class="col-12 col-sm-12 col-lg-10" @endif>
                        <label for="users-list-verified">Autorizada/Credenciada</label>
                        <fieldset class="form-group">
                            <select class="js-select22 form-control" id="authorized" name="authorized" style="width: 100%;" multiple>
                            </select>
                        </fieldset>
                    </div>
                    @if (Session::get('filter_line') == 1)
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Tipo de Linha</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="type_line">
                                <option value="">Todos</option>
                                <option value="1" @if (Session::get('sacf_type_line') == '1') selected @endif>Residencial</option>
                                <option value="2" @if (Session::get('sacf_type_line') == '2') selected @endif>Comercial</option>
                            </select>
                        </fieldset>
                    </div>
                    @endif
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Protocolo de atendimento</label>
                        <fieldset class="form-group">
                            <input type="text" name="code" value="{{ Session::get('sacf_code') }}" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Ordem de serviço</label>
                        <fieldset class="form-group">
                            <input type="text" name="os" value="{{ Session::get('sacf_os') }}" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Mensagens não respondidas</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="all_msg">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_all_msg') == 1) selected @endif>Ver todas</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="status" id="sel_status">
                                <option></option>
                                <option value="9" @if (Session::get('sacf_status') == 9) selected @endif>0 - Protocolos (Sem análises)</option>
                                <option value="2" @if (Session::get('sacf_status') == 2) selected @endif>1 - Suspensos (Sem análises)</option>
								<option value="10" @if (Session::get('sacf_status') == 10) selected @endif>2 - Suspensos</option>
                                <option value="3" @if (Session::get('sacf_status') == 3) selected @endif>3 - Análisados (Falta aprovar)</option>
                                <option value="1" @if (Session::get('sacf_status') == 1) selected @endif>4 - Peças aprovadas</option>
                                <option value="4" @if (Session::get('sacf_status') == 4) selected @endif>5 - Aguardando envio P/ separação</option>
                                <option value="8" @if (Session::get('sacf_status') == 8) selected @endif>Separação & Faturamento</option>
								<option value="14" @if (Session::get('sacf_status') == 14) selected @endif>Peças Faturadas</option>
                                <option value="15" @if (Session::get('sacf_status') == 15) selected @endif>Peças não Faturadas</option>
                                <option value="5" @if (Session::get('sacf_status') == 5) selected @endif>Serviços prestados</option>
								<option value="11" @if (Session::get('sacf_status') == 11) selected @endif>Pend. de pagamento</option>
								<option value="12" @if (Session::get('sacf_status') == 12) selected @endif>OS's em aberto</option>
                                <option value="6" @if (Session::get('sacf_status') == 6) selected @endif>Canceladas</option>
                                <option value="7" @if (Session::get('sacf_status') == 7) selected @endif>Pago/Concluido</option>
								<option value="13" @if (Session::get('sacf_status') == 13) selected @endif>Reclame aqui</option>

                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Visualizar Ped. Peças</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="see_part">
                                <option>Ver todos</option>
                                <option value="1" @if (Session::get('sacf_see_part') == 1) selected @endif>Visualizar pedidos</option>
                            </select>
                        </fieldset>
                    </div>
					<div class="col-12 col-sm-12 col-lg-4">
                        <label for="start_date">Data de Criação (inicial)</label>
                        <fieldset class="form-group">
                            <input type="text" name="start_date" value="<?= Request::get('start_date') ?>" class="form-control date-mask js-flatpickr js-flatpickr-enabled flatpickr-input">
                        </fieldset>
                    </div>
                    
                    <div class="col-12 col-sm-12 col-lg-4">
                        <label for="end_date">Data de Criação (final)</label>
                        <fieldset class="form-group">
                            <input type="text" name="end_date" value="<?= Request::get('end_date') ?>" class="form-control date-mask js-flatpickr js-flatpickr-enabled flatpickr-input">
                        </fieldset>
                    </div>
					<div class="col-12 col-sm-12 col-lg-4">
                        <label for="origin">Origem de atd.</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="origin" name="origin" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_origin') == 1) selected @endif>Telefone</option>
                                <option value="2" @if (Session::get('sacf_origin') == 2) selected @endif>E-mail</option>
                                <option value="3" @if (Session::get('sacf_origin') == 3) selected @endif>Reclame aqui</option>
                                <option value="4" @if (Session::get('sacf_origin') == 4) selected @endif>Midia sociais</option>
                                <option value="5" @if (Session::get('sacf_origin') == 5) selected @endif>Site</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-12 d-flex align-items-center">
                        <button type="submit" value="0" name="export_external" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-12 d-flex align-items-center">
                        <button type="submit" value="1" name="export_external" class="btn btn-success btn-block glow users-list-clear mb-0">Exportar dados (Para gerência & CQ)</button>
                    </div>
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form action="/sac/warranty/os/split" id="sendSplit" method="post">
                            <div class="table-responsive">
                                <table id="list-datatable" style="margin-bottom: 240px !important;" class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="text-center">#</th>
                                            <th>ID</th>
                                            <th>PROTOCOLO</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($os as $key) { ?>
                                        <tr class="cursor-pointer showDetails" id="<?= $key->id ?>">
                                            <td style="width: 1%">
                                                <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                            </td>
                                            <td class="text-center">
                                                <div class="checkbox"><input type="checkbox" class="checkbox-input" id="check_<?= $key->id ?>" name="check[]" value="<?= $key->id ?>">
                                                    <label for="check_<?= $key->id ?>"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <?php $i = countIntection($key->osMsgs()->get()) ?>
                                                @if ($i > 0)
                                                <div class="badge badge-pill badge-glow badge-warning mr-1">{{$i}}</div>
                                                @endif
                                                <?= $key->code ?>
                                            </td>
                                            <td><a target="_blank" href="/sac/warranty/edit/<?= $key->sacProtocol->id ?>"><?= $key->sacProtocol->code ?></a></td>
                                            <td><?= number_format($key->total, 2) ?></td>
                                            <td>
                                                @if ($key->is_cancelled == 1)
                                                <span class="badge badge-light-danger">Cancelado</span>
                                                @elseif ($key->is_paid == 1 && $key->is_payment_request == 1 )
                                                <span class="badge badge-light-success">Pago</span>
                                                @elseif ($key->is_paid == 1 && $key->is_payment_request == 0 )
                                                <span class="badge badge-light-success">Concluido</span>
												@elseif ($key->has_pending_payment == 1 )
                                                <span class="badge badge-light-success">Pendente de pagamento</span>
                                                @elseif ($key->expedition_invoice == 1)
                                                <span class="badge badge-light-success">Separação & Faturamento</span>
                                                @elseif ($key->has_split == 1)
                                                <span class="badge badge-light-warning">Aguardando envio P/ separação</span>
                                                @elseif ($key->is_approv == 1 and $key->sacOsAnalyze->count() > 0)
                                                <span class="badge badge-light-info">Peças aprovadas</span>
                                                @elseif ($key->sacOsAnalyze->count() == 0)
                                                @if ($key->sacProtocol->sacpartprotocol->where('is_repprov', 0)->count() > 0)
                                                <span class="badge badge-light-secondary">Suspensos (Sem análises)</span>
                                                @else
                                                <span class="badge badge-light-warning">Em andamento</span>
                                                @endif
                                                @elseif ($key->sacOsAnalyze->count() > 0 and $key->has_analyze_part == 0 and $key->has_pending_payment = 0)
                                                <span class="badge badge-light-warning">Suspenso</span>
												@elseif ($key->sacOsAnalyze->count() > 0 and $key->has_analyze_part == 1 and $key->has_pending_payment = 0)
                                                <span class="badge badge-light-warning">Análisados (Falta aprovar)</span>
                                                @else
                                                <span class="badge badge-light-warning">Em andamento</span>
                                                @endif
												@if ($key->has_print == 1)
												<br><span class="badge badge-light-info">Imprimido</span>
												@endif
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                  <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                  <div class="dropdown-menu dropdown-menu-right">
                                                    @if ($key->is_paid == 0 and $key->is_cancelled == 0 and $key->is_completed == 0 and $key->sacProtocol->is_cancelled == 0 and $key->is_refund == 0)
                                                    <a class="dropdown-item" href="/sac/warranty/parts/<?= $key->id ?>"><i class="bx bxs-package mr-1"></i> Solicitar peças</a>
                                                    @endif
                                                    <a class='dropdown-item' onclick='viewAnalyze(this)' data-json='<?= htmlspecialchars(json_encode($key), ENT_QUOTES, "UTF-8") ?>' href='javascript:void(0)'><i class='bx bx-file mr-1'></i> Ver Análises</a>
                                                    <a class="dropdown-item" href="/sac/warranty/os/print/<?= $key->id ?>" target="_blank"><i class="bx bx-printer mr-1"></i> Impr. OS</a>
                                                    <a class="dropdown-item" onclick="anexoFiles(<?= $key->sacProtocol->id ?>)"> <i class="bx bx-file-blank mr-1"></i> Arquivos Anexados</a>
                                                    <a class="dropdown-item" href="/sac/warranty/os/model/<?= $key->id ?>" target="_blank"><i class="bx bx-edit-alt mr-1"></i> Editar Modelo</a>
                                                    <a class="dropdown-item" onclick='viewInteractive(<?= $key->id ?>, <?=$key->sacProtocol->id ?>)' href='javascript:void(0)'><i class="bx bx-chat mr-1"></i> Interagir</a>
                                                    @if ($key->is_paid == 1)
                                                    <a href="/financy/payment/request/print/<?= $key->payment_request_id ?>" class="dropdown-item" target="_blank" href="javascript:void(0)"><i class="bx bx-printer mr-1"></i> Impr. Solicitação Pag.</a>
                                                    @endif
                                                    @if ($key->payment_nf)
                                                    <a class="dropdown-item" href="<?= $key->payment_nf ?>" target="_blank"><i class="bx bx-receipt mr-1"></i> Nota Fiscal</a>
                                                    @endif
                                                    @if ($key->is_paid == 0)
                                                    @if ($key->is_cancelled == 1)
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="editStatusOS('/sac/warranty/os/status/1/<?= $key->id ?>', 2)"><i class="bx bx-check mr-1"></i> Reabrir</a>
                                                    @else
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="editStatusOS('/sac/warranty/os/status/1/<?= $key->id ?>', 1)"><i class="bx bx-x mr-1"></i> Cancelar</a>
                                                    @endif
                                                    @endif
                                                  </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="display: none;" class="group info_extra">
                                            <td colspan="10">
                                                <div class="card m-0">
                                                    <div class="card-content">
                                                        <div class="card-body">
                                                            <div class="alert alert-primary mb-2 expdition_motive"></div>
                                                            <div class="row">
                                                                <div class="col-xl-6 col-md-6 col-sm-12">
                                                                    <p>Autorizada: <a target="_blank" href="/sac/authorized/edit/<?= $key->authorizedOs->id ?>"><?= strWordCut($key->authorizedOs->name, 42) ?></a></p>
                                                                    <p>Técnico: <?= $key->expert_name ?> - <?= $key->expert_phone ?></p>
                                                                </div>
                                                                <div class="col-xl-6 col-md-6 col-sm-12">
                                                                    <p>Última atualização: <?= date('d/m/Y H:i', strtotime($key->updated_at)) ?></p>
                                                                    <p>Data de criação: <?= date('d/m/Y H:i', strtotime($key->created_at)) ?></p>
                                                                </div>
                                                            </div>
                                                            <ul class="nav nav-tabs list_info_tab"></ul>
                                                            <div class="tab-content list_info_parts"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $os->appends([
                                            'left_5' => Session::get('sacf_left_5'),
                                            'left_15' => Session::get('sacf_left_15'),
                                            'left_30' => Session::get('sacf_left_30'),
                                            'code' => Session::get('sacf_code'),
                                            'os' => Session::get('sacf_os'),
                                            'serial_number' => Session::get('sacf_serial_number'),
                                            'status' => Session::get('sacf_status'),
                                            'all_msg' => Session::get('sacf_all_msg'),
                                            'see_part' => Session::get('sacf_see_part'),
                                            'authorized' => Session::get('sacf_authorized'),
                                            'type_line' => Session::get('sacf_type_line'),
											'start_date' => Session::get('sacf_start_date'),
											'end_date' => Session::get('sacf_end_date'),
											'origin' => Session::get('sacf_origin'),
											'warranty_extend' => Session::get('sacf_warranty_extend'),
											'gree_os' => Session::get('sacf_gree_os'),
											'authorization_install' => Session::get('sacf_authorization_install'),
											'not_response' => Session::get('not_response'),
                                            ])->links(); ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="mb-2" style="width: 540px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
                                    <button type="submit" onclick="cSubmit(1)" class="btn btn-success">Aguardando Envio p/ Separação</button> <button type="submit" onclick="cSubmit(2)" class="btn btn-secondary">Enviar p/ Separação (Exportar)</button>
                            </div>
                            </form>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>
</div>


{{-- Interactive modal for attachedes files  --}}
<div class="modal fade text-left" id="modal-anexos" tabindex="-1" role="dialog" aria-labelledby="modal-anexos" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-anexos">Arquivos Anexados</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="card-body">
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                              <li class="nav-item active">
                                <a class="nav-link" id="tab-sac-os-msg" data-toggle="tab" data-ref="1" href="#sac-os-msg" aria-controls="sac-os-msg" role="tab" aria-selected="false" >
                                  SAC OS - Mensagens
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="tab-sac-protocol-msg" data-toggle="tab" data-ref="2" href="#sac-protocol-msg" aria-controls="sac-protocol-msg" role="tab" aria-selected="false">
                                    SAC Protocolos - Mensagens
                                </a>
                              </li>
                              <li class="nav-item current">
                                <a class="nav-link" id="tab-sac-protocol-os" data-toggle="tab" data-ref="3" href="#sac-protocol-os" aria-controls="sac-protocol-os" role="tab" aria-selected="true">
                                  SAC Protocolos - OS
                                </a>
                              </li>
                              <li class="nav-item current">
                                <a class="nav-link" id="tab-sac-protocol" data-toggle="tab" data-ref="4" href="#sac-protocol" aria-controls="sac-protocol" role="tab" aria-selected="true">
                                  SAC Protocolos
                                </a>
                              </li>
                            </ul>

                            <div class="tab-content">
                              <div class="tab-pane active" id="sac-os-msg" aria-labelledby="tab-sac-os-msg" role="tabpanel">
                                <h1>SAC OS - Mensagens</h1>
                                <div class="table-responsive">
                                    <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>ARQUIVO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataView1">

                                    </tbody>
                                    </table>
                                </div>
                              </div>
                              <div class="tab-pane" id="sac-protocol-msg" aria-labelledby="tab-sac-protocol-msg" role="tabpanel">
                                <h1>SAC Protocolos - Mensagens</h1>
                                <div class="table-responsive">
                                    <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>ARQUIVO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataView2">

                                    </tbody>
                                    </table>
                                </div>
                              </div>
                              <div class="tab-pane" id="sac-protocol-os" aria-labelledby="tab-sac-protocol-os" role="tabpanel">
                                <h1>SAC Protocolos - OS</h1>
                                <div class="table-responsive">
                                    <table class="table">
                                    <thead>
                                        <tr>
                                            <th>NOME</th>
                                            <th>ARQUIVO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataView3">

                                    </tbody>
                                    </table>
                                </div>
                              </div>
                              <div class="tab-pane" id="sac-protocol" aria-labelledby="tab-sac-protocol" role="tabpanel">
                                <h1>SAC Protocolos</h1>
                                <div class="table-responsive">
                                    <table class="table">
                                    <thead>
                                        <tr>
                                            <th>NOME</th>
                                            <th>ARQUIVO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataView4">

                                    </tbody>
                                    </table>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="modal-analyze" tabindex="-1" role="dialog" aria-labelledby="modal-analyze" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="modal-analyze">REALIZAR ANÁLISE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="card-body">
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                                <li class="nav-item active">
                                    <a class="nav-link" id="tab-historico" data-toggle="tab" data-ref="1" href="#historico" aria-controls="historico" role="tab" aria-selected="false" >
                                        Histórico de Análises
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="tab-entrada" data-toggle="tab" data-ref="2" href="#entrada" aria-controls="entrada" role="tab" aria-selected="false">
                                        Nova Análise Técnica
                                    </a>
                                </li>

                                <li class="nav-item current">
                                    <a class="nav-link" id="tab-config" data-toggle="tab" data-ref="3" href="#config" aria-controls="config" role="tab" aria-selected="true">
                                        Configuração
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="historico" aria-labelledby="tab-historico" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Descrição</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dataHistory">

                                        </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="entrada" aria-labelledby="tab-entrada" role="tabpanel">
                                    <form action="/sac/warranty/os/analyzeTechnique" method="post" id="formSendReport">
                                        <input type="hidden" value="0" id="id_" name="id_">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <fieldset class="form-group">
                                                        <label for="description_">Informe análise feita</label>
                                                        <textarea class="form-control" id="description_" name="description_" rows="6" placeholder="..."></textarea>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success ml-1" id="sendReport">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">ENVIAR RELATÓRIO</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane" id="config" aria-labelledby="tab-config" role="tabpanel">
                                    <div class="table-responsive">
                                        <form action="/sac/warranty/os/analyze" id="analyzepart" method="post">
                                            <input type="hidden" value="0" id="id" name="id">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <fieldset class="form-group">
                                                            <label for="description">Retroceder status</label>
                                                            <select class="form-control" name="status">
                                                                <option></option>
                                                                <option value="3">Suspenso</option>
																<option value="4">Análisado (Falta aprovar)</option>
																<option value="1">Aguardando envio P/ separação</option>
                                                                <option value="2">Separação & Faturamento (PG)</option>
                                                            </select>
                                                        </fieldset>
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                <div class="checkbox checkbox-shadow">
                                                                    <input type="checkbox" name="has_service" value="1" id="has_service">
                                                                    <label for="has_service">Marque essa opção, se caso é um serviço prestado.</label>
                                                                </div>
                                                                </fieldset>
                                                            </li>
															<li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                <div class="checkbox checkbox-shadow">
                                                                    <input type="checkbox" name="has_print" value="1" id="has_print">
                                                                    <label for="has_print">Marque essa opção para mostrar a tag imprimida nos status.</label>
                                                                </div>
                                                                </fieldset>
                                                            </li>
															@if (Session::get('r_code') == '2800' or Session::get('r_code') == '2290' or Session::get('r_code') == '4867')
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                <div class="checkbox checkbox-shadow">
                                                                    <input type="checkbox" name="has_pending_payment" value="1" id="has_pending_payment">
                                                                    <label for="has_pending_payment">Marque essa opção, se o serviço está pendente de pagamento.</label>
                                                                </div>
                                                                </fieldset>
                                                            </li>
															@endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success ml-1">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">ATUALIZAR CONFIGURAÇÃO</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade text-left" id="modal-interactive" tabindex="-1" role="dialog" aria-labelledby="modal-analyze" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white">INTERAGIR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <fieldset class="form-group">
                                <a class="dropdown-item a_interactive_client btn_interactive" target="_blank" href="#"><i class="bx bx-support mr-1"></i> INTERAGIR COM CLIENTE</a><br>
                                <a class="dropdown-item a_interactive_technical btn_interactive" target="_blank" href="#"><i class="bx bx-wrench mr-1"></i> INTERAGIR COM TÉCNICO</a>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-os" tabindex="-1" role="dialog" aria-labelledby="modal-analyze" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white">OS VINCULADAS AO N° DE SÉRIE: <span id="modal_nserie"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="list-datatable" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>PROTOCOLO</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="modal_os"></tbody>
                            </table>
                            <div id="modal_os_list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var id_protocol = '';
		
		function editStatusOS(url, type) {
			var response = type == 1? "cancelar":"reabrir";
			var text = "Tem certeza que deseja "+ response;
			var alertDialog = confirm(text);
			if (alertDialog == true) {
			  window.location.href=url;
			}
		}
        function anexoFiles(id){
            $('#modal-anexos').modal();

            $('#tab-sac-os-msg').click();

            actionModal($('#tab-sac-os-msg'), id);

            id_protocol = id;
        }

        $('#tab-sac-os-msg, #tab-sac-protocol-msg, #tab-sac-protocol-os, #tab-sac-protocol').on('click', function (){
            actionModal(this, id_protocol);
        })

        function actionModal($this, id){
            block();


            $('#dataView1').html('');
            $('#dataView2').html('');
            $('#dataView3').html('');
            $('#dataView4').html('');


            ajaxSend('/sac/warranty/attachmentAll',{id: id, tab: $($this).attr('data-ref')})

                .then((response) => {
                    unblock();
                    if (response.data.length != 0){
                        viewFile(response.data, $($this).attr('data-ref'));
                    }
                })
                .catch((error) => {
                    $error(error.message);

                    unblock();
                });
        }

        function viewAnalyze(element) {
            $('#tab-historico').addClass('active');
            $('#tab-entrada').removeClass('active');
            $('#tab-config').removeClass('active');

            // $(element).attr('data-json');
            var json = $(element).attr('data-json');
            obj = JSON.parse(json);

            var list_os = '';

            if(obj.sac_protocol.sacosprotocol.length > 0) {
                for (j = 0; j < obj.sac_os_analyze.length; j++) {
                    var row = obj.sac_os_analyze[j];

                    list_os += '<tr>';
                    list_os += '<td>'+row.sac_users.short_name+'</td>';
                    list_os += '<td>'+row.description+'</td>';
                    list_os += '<td>'+row.date_formmat+'</td>';
                    list_os += '</tr>';
                }
            } else {
                list_os += '<tr>';
                list_os += '<td>Não há itens relacionadas!</td>';
                list_os += '</tr>';
            }
            $('#dataHistory').html(list_os);

            $("#description").val(obj.sac_protocol.sacosprotocol.length > 0);
            if (obj.has_service == 1) {
                $("#has_service").attr('checked', '');
            } else {
                $("#has_service").removeAttr('checked');
            }
			if (obj.has_print == 1) {
                $("#has_print").attr('checked', '');
            } else {
                $("#has_print").removeAttr('checked');
            }
            if (obj.has_pending_payment == 1) {
                $("#has_pending_payment").attr('checked', '');
            } else {
                $("#has_pending_payment").removeAttr('checked');
            }

            $("#id").val(obj.id);
            $("#id_").val(obj.id);
            $("#modal-analyze").modal();
        }

        $('#sendReport').click(function(e){
            block();
            ajaxSend('/sac/warranty/os/analyzeTechnique', $('#formSendReport').serialize(), 'POST')
            .then((response) => {
                unblock();

                $('#description_').val('');

                var list_os = '';

                list_os += '<tr>';
                list_os += '<td>'+response.name+'</td>';
                list_os += '<td>'+response.description+'</td>';
                list_os += '<td>'+response.created_at+'</td>';
                list_os += '</tr>';

                $('#dataHistory').append(list_os);

                $('#tab-historico').tab('show');

                $success("O.S foi atualizada com a sua nova análise.");
            })
            .catch((error) => {
                $error(error.message);

                unblock();
            });
        });

        function viewFile(data, tab){

            console.log(data);

            var list = '';

            for(i=0; i < data.length; i++){
                list+= '<tr>';
                list+= '<td>'+data[i].name+'</td>';
                list+= '<td><a href="'+data[i].file+'" target="_blank">'+data[i].file+'</a></td>';
                list+= '</tr>';
            }

            $('#dataView'+tab).html(list);
        }

        var type;
        function cSubmit(t) {
            if (t == 1) {
                type = 1;
                $("#sendSplit").attr('action', '/sac/warranty/os/split');
            } else {
                type = 2;
                $("#sendSplit").attr('action', '/sac/warranty/os/export/split');
            }

        }

        function viewInteractive(id, protocol_id) {
            $(".a_interactive_client").attr("href", "/sac/warranty/interactive/"+protocol_id+"");
            $(".a_interactive_technical").attr("href", "/sac/warranty/os/interactive/"+id+"");
            $("#modal-interactive").modal();
        }

        function getDayLeft(nmb, $this) {
            if (nmb == 1) {
                if ($("#_left_5").val() == 1) {
                    $("#_left_5").val('');
                    $($this).find('.card').removeAttr('style');
                } else {
                    $("#_left_15").val('');
                    $("#_left_30").val('');
                    $("#_left_5").val(1);
                    $('#left_15').removeAttr('style');
                    $('#left_30').removeAttr('style');
                    $($this).find('.card').attr('style', 'border:solid');
                }
            } else if (nmb == 2) {
                if ($("#_left_15").val() == 1) {
                    $("#_left_15").val('');
                    $($this).find('.card').removeAttr('style');
                } else {
                    $("#_left_5").val('');
                    $("#_left_30").val('');
                    $("#_left_15").val(1);
                    $('#left_5').removeAttr('style');
                    $('#left_30').removeAttr('style');
                    $($this).find('.card').attr('style', 'border:solid');
                }
            } else if (nmb == 3) {
                if ($("#left_30").val() == 1) {
                    $("#left_30").val('');
                    $($this).find('.card').removeAttr('style');
                } else {
                    $("#_left_5").val('');
                    $("#_left_15").val('');
                    $("#_left_30").val(1);
                    $('#left_5').removeAttr('style');
                    $('#left_15').removeAttr('style');
                    $($this).find('.card').attr('style', 'border:solid');
                }
            }
        }

        var expeditionInfo = function(el){
            $('.row_expand_exp_'+el+'').toggleClass('bx-plus-circle');
            if($('.info_expedition_'+el+'').is(":visible")){
                $('.info_expedition_'+el+'').hide();
            }else{
                $('.info_expedition_'+el+'').show();
            }
        }

        function viewOSModal(serial_number) {
            $("#modal-os").modal();

            ajaxSend('/sac/warranty/os/all/model/ajax', {serial_number: serial_number}, 'GET', 3000).then(function(response) {

                $("#modal_nserie").html(serial_number +' ('+ response.count +')');

                listOSHtml(response);

            }).catch(function(err){
                $error(err.message);
            });
        }

        function listOSHtml(response) {
            var list_os = '';
            if(response.os.length > 0) {
                for (i = 0; i < response.os.length; i++) {
                    list_os += '<tr>';
                    list_os += '<td>'+response.os[i].os_code+'</td>';
                    list_os += '<td><a target="_blank" href="/sac/warranty/edit/'+response.os[i].protocol_id+'">'+response.os[i].protocol_code+'</a></td>';
                    list_os += '<td>'+response.os[i].status+'</td>';
                    list_os += '<td>';
                    list_os += '    <div class="dropleft">';
                    list_os += '      <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>';
                    list_os += '      <div class="dropdown-menu dropdown-menu-right">';
                    if (response.os[i].is_paid == 0 && response.os[i].is_cancelled == 0 && response.os[i].is_completed == 0 && response.os[i].sac_protocol_is_cancelled == 0 && response.os[i].is_refund == 0) {
                        list_os += '        <a class="dropdown-item" href="/sac/warranty/parts/'+response.os[i].os_id+'" target="_blank"><i class="bx bxs-package mr-1"></i> Solicitar peças</a>';
                    }
                    list_os += '        <a class="dropdown-item" href="/sac/warranty/os/print/'+response.os[i].os_id+'" target="_blank"><i class="bx bx-printer mr-1"></i> Impr. OS</a>';
                    list_os += '      </div>';
                    list_os += '    </div>';
                    list_os += '</td>';
                    list_os += '</tr>';
                }
            } else {
                list_os += '<tr>';
                list_os += '<td>Não há OS relacionadas!</td>';
                list_os += '</tr>';
            }
            $('#modal_os').html(list_os);
            $("#modal_os_list").html(response.paginate);
        }

        function ajaxPaginator(url_page, html_render, formdata) {
            block();
            let $params = {
                    type: "GET",
                    data: formdata,
                    url: url_page,
                };
            ajaxSend($params.url, $params.data, $params.type,$params.form)
            .then((response) => {

                    console.log(response);
                    unblock();
                    listOSHtml(response);
            })
            .catch((error) => {
                $error(error.message);
                unblock();
            });
        }
    </script>
    <script>

    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
		
		$('.date-mask').pickadate({
			formatSubmit: 'yyyy-mm-dd',
			format: 'yyyy-mm-dd',
			today: 'Hoje',
			clear: 'Limpar',
			close: 'Fechar',
			monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
			monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
			weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
			weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
		});

        $("#sendSplit").submit(function (e) {
            if ($(':checkbox[name="check[]"]:checked').length == 0) {

                e.preventDefault();

                return type == 1 ? error('Selecione ao menos 1 o.S para envio!') : error('Selecione ao menos 1 o.S para exportação!');
            }

            if (type == 1) {
                block();
            } else {
                Swal.fire({
                    type: "success",
                    title: 'Exportando...',
                    text: 'Aguarde nessa tela, enquanto estamos criando o arquivo para você. Quando concluir atualize a página.',
                    confirmButtonClass: 'btn btn-success',
                });
            }


        });
        $("#analyzepart").submit(function (e) {
            if ($("#description").val() == "") {
                e.preventDefault();
                return error('Você precisa descrever sua análise técnica realizado com o técnico.');
            }

            $("#modal-analyze").modal('toggle');
            block();


        });

		$("#sel_status").change(function (e) {
			if ($("#sel_status").val() == 9) {
				$("#sel_status").val('');
				window.open('/sac/warranty/all?not_response=1', '_blank');
			}
		});


        $("#type_people").change(function (e) {
            if ($("#type_people").val() == 0) {
                $('.select2-search__field').unmask();
            } else if ($("#type_people").val() == 1) {

                $('.select2-search__field').mask('000.000.000-00', {reverse: false});

            } else if ($("#type_people").val() == 2) {

                $('.select2-search__field').mask('00.000.000/0000-00', {reverse: false});
            }

        });

        $(".js-select22").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {

                    var url = "'/sac/authorized/edit/0'";
                    return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Nova Autorizada</button>');
                }
            },
            ajax: {
                url: '/misc/sac/authorized/',
                data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                }

                // Query parameters will be ?search=[term]&page=[page]
                return query;
                }
            }
        });

        $('.showDetails td').not('.no-click').click(function (e) {
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');

            if($(this).parent().next('.info_extra').is(':visible')) {

                var elem = $(this);
                ajaxSend('/sac/warranty/os/all/ajax', {id: $(this).parent().attr('id')}, 'GET', 3000).then(function(response) {

                    elem.parent().next().find('.expdition_motive').html('DEFEITO CONSTATADO: ' +response.motive_description);

                    var list_tab = '';
                    var list_parts = '';
                    var list_info_os = '';
                    var active = '';

                    if(response.model_os.length > 0) {
                        for (i = 0; i < response.model_os.length; i++) {
                            if(i == 0) {
                                active = 'active';
                            } else {
                                active = '';
                            }
                            var aspa = "'";
                            list_tab +='<li class="nav-item">';
                            list_tab +='    <a class="nav-link '+active+'" data-toggle="tab" href="#tab-'+i+'" role="tab" aria-controls="tab-'+i+'" aria-selected="true">';
                            list_tab +='      '+response.model_os[i].model+'';
                            list_tab +='       <i class="bx bx-file mr-1 icon-model-os" onclick="viewOSModal('+aspa+''+response.model_os[i].serial_number+''+aspa+')"></i>';
                            list_tab +='    </a>';
                            list_tab +='</li>';
                            list_parts += '<div class="tab-pane '+active+'" id="tab-'+i+'" role="tabpanel" aria-labelledby="tab-'+i+'">';
                            list_parts += '<div class="row">';
                            list_parts += '    <div class="col-xl-3 col-md-3 col-sm-12">';
                            list_parts += '        <p>Número de série: '+response.model_os[i].serial_number+'</p>';
                            list_parts += '    </div>';
                            list_parts += '    <div class="col-xl-6 col-md-6 col-sm-12">';
                            list_parts += '        <p>Segmento: '+response.model_os[i].segment+'</p>';
                            list_parts += '    </div>';
                            list_parts += '</div>';
                            list_parts += '<table class="table" id="table_parts" style="text-align: center;">';

                            if(response.model_os[i].parts.length > 0) {
                                list_parts += '<thead>';
                                list_parts += '<tr>';
                                list_parts += '<th></th>';
                                list_parts += '<th>código</th>';
                                list_parts += '<th>Peça</th>';
                                list_parts += '<th>Motivo</th>';
                                list_parts += '<th>Status</th>';
                                list_parts += '<th>Quantidade</th>';
								list_parts += '<th>Faturamento</th>';
                                list_parts += '</tr>';
                                list_parts += '</thead>';
                                list_parts += '<tbody>';
                                for (j = 0; j < response.model_os[i].parts.length; j++) {
                                    if(response.model_os[i].product_id == response.model_os[i].parts[j].product_id) {

                                        list_parts += '<tr class="cursor-pointer" onclick="javascript:expeditionInfo('+j+')">';
                                        list_parts += '<td style="width: 1%">';
                                        list_parts += '<i class="row_expand_exp_'+j+' bx bx-plus-circle bx-minus-circle cursor-pointer"></i>';
                                        list_parts += '</td>';
                                        list_parts += '<td>'+response.model_os[i].parts[j].code+'</td>';
                                        list_parts += '<td>'+response.model_os[i].parts[j].description+'</td>';
                                        list_parts += '<td>'+response.model_os[i].parts[j].motive+'</td>';
                                        list_parts += '<td>'+response.model_os[i].parts[j].status+'</td>';
                                        list_parts += '<td>'+response.model_os[i].parts[j].quantity+'</td>';
										
										if(response.model_os[i].parts[j].is_invoice == 1) {
                                            list_parts += '<td><span class="badge badge-light-success">Faturado</span></td>';
                                        } else {
                                            list_parts += '<td><span class="badge badge-light-danger">Não Faturado</span></td>';
                                        }
																  
                                        list_parts += '</tr>';
                                        list_parts += '<tr class="info_expedition_'+j+' expedition_bg_color" style="display: none;">';
                                        list_parts += '<td colspan="8" style="text-align:center;"><div class="alert alert-primary mb-2">EXPEDIÇÃO</div></td>';
                                        list_parts += '</tr>';
                                        list_parts += '<thead>';
                                        list_parts += '<tr class="info_expedition_'+j+' expedition_bg_color" style="display: none;">';
                                        list_parts += '<th>Nota Fiscal</th>';
                                        list_parts += '<th>Rastreio</td>';
                                        list_parts += '<th>Transporte</th>';
                                        list_parts += '<th>Previsão de chegada</th>';
                                        list_parts += '<th>Chegou em</th>';
                                        list_parts += '<th>Status</th>';
                                        list_parts += '<th>Total</th>';
                                        list_parts += '</tr>';
                                        list_parts += '</thead>';
                                        list_parts += '<tr class="info_expedition_'+j+' expedition_bg_color" style="display: none;">';

                                        for(var k = 0; k < response.expedition.length; k++) {
                                            if(response.expedition[k].id == response.model_os[i].parts[j].sac_expedition_request_id) {
                                                list_parts += '<td>'+response.expedition[k].nf_number+'</td>';
                                                list_parts += '<td>'+response.expedition[k].code_track+'</td>';
                                                list_parts += '<td>'+response.expedition[k].transport+'</td>';
                                                list_parts += '<td>'+response.expedition[k].arrival_forecast+'</td>';
                                                list_parts += '<td>'+response.expedition[k].arrived_at+'</td>';
                                                list_parts += '<td>'+response.expedition[k].status+'</td>';
                                                list_parts += '<td>'+response.expedition[k].total+'</td>';
                                            }
                                            else {
                                                list_parts += '<td colspan="7"><div class="badge badge-secondary mr-1 mb-1">Não há peça enviada para expedição.</div></td>';
                                            }
                                        }
                                        list_parts += '</tr>';
                                    }
                                }
                                list_parts += '</tbody>';
                            }
                            else {
                                list_parts += '<tr><td><div class="badge badge-secondary mr-1 mb-1">Não há peças vinculadas a esse modelo!</td></div></tr>';
                            }
                            list_parts += '</table>';
                            list_parts +='</div>';
                        }
                    } else {
                        list_tab +='<div class="badge badge-secondary mr-1 mb-1">Não há modelos vinculadas a essa OS.</div>';
                    }
                    elem.parent().next().find('.list_info_tab').html(list_tab);
                    elem.parent().next().find('.list_info_parts').html(list_parts);

                }).catch(function(err){
                    $error(err.message);
                });
            }
        });

        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mTAssist").addClass('sidebar-group-active active');
            $("#mTAssistOsAll").addClass('active');
        }, 100);

    });
    </script>
@endsection
