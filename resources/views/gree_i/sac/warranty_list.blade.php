@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<style>
.pac-container {
    z-index: 1051 !important;
}
</style>
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-6 col-sm-12 col-lg-6">
              <div class="breadcrumb-wrapper col-12">
                Lista de protocolos
              </div>
            </div>
            @if (Session::get('filter_line') == 1)
            <div class="col-6 col-sm-12 col-lg-6">
                <fieldset class="form-group float-right">
                    <select class="form-control" id="sel_typeline" name="sel_typeline" style="width: 106%;">
                        <option value="">Tipo de linha</option>
                        <option value="residential" @if (Session::get('sacf_segment') == 'residential') selected @endif>Residencial</option>
                        <option value="commercial" @if (Session::get('sacf_segment') == 'commercial') selected @endif>Comercial</option>
                    </select>
                </fieldset>
            </div>
            @endif
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
            <form action="/sac/warranty/all" id="searchTrip" method="GET">
                <input type="hidden" id="_left_5" name="left_5" value="">
                <input type="hidden" id="_left_15" name="left_15" value="">
                <input type="hidden" id="_left_30" name="left_30" value="">
				<input type="hidden" name="monitor_block_1" value="{{Request::get('monitor_block_1')}}">
				<input type="hidden" name="monitor_block_2" value="{{Request::get('monitor_block_2')}}">
				<input type="hidden" name="monitor_block_3" value="{{Request::get('monitor_block_3')}}">
				<input type="hidden" name="monitor_block_4" value="{{Request::get('monitor_block_4')}}">
				<input type="hidden" name="monitor_p_block_1" value="{{Request::get('monitor_p_block_1')}}">
				<input type="hidden" name="monitor_p_block_2" value="{{Request::get('monitor_p_block_2')}}">
				<input type="hidden" name="monitor_p_block_3" value="{{Request::get('monitor_p_block_3')}}">
				<input type="hidden" name="monitor_p_block_4" value="{{Request::get('monitor_p_block_4')}}">
				<input type="hidden" name="monitor_p_block_5" value="{{Request::get('monitor_p_block_5')}}">
				<input type="hidden" name="monitor_p_block_6" value="{{Request::get('monitor_p_block_6')}}">
				<input type="hidden" name="monitor_p_block_7" value="{{Request::get('monitor_p_block_7')}}">
				<input type="hidden" name="monitor_p_block_8" value="{{Request::get('monitor_p_block_8')}}">
				<input type="hidden" name="monitor_p_block_9" value="{{Request::get('monitor_p_block_9')}}">
				<input type="hidden" name="monitor_p_block_10" value="{{Request::get('monitor_p_block_10')}}">
                <input type="hidden" id="segment" name="segment" value="">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Protocolo de atendimento</label>
                        <fieldset class="form-group">
                            <input type="text" name="code" value="{{ Session::get('sacf_code') }}" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Atendente</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" multiple>
                                <?php foreach ($userall as $key) { ?>
                                    <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                <?php } ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="is_warranty">É garantia</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="is_warranty" name="is_warranty" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_is_warranty') == 1) selected @endif>SIM</option>
                                <option value="2" @if (Session::get('sacf_is_warranty') == 2) selected @endif>NÃO</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="origin">Origem de atd.</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="origin" name="origin" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_origin') == 1) selected @endif>Telefone</option>
                                <option value="2" @if (Session::get('sacf_origin') == 2) selected @endif>E-mail</option>
                                <option value="3" @if (Session::get('sacf_origin') == 3) selected @endif>Reclame aqui</option>
                                <option value="4" @if (Session::get('sacf_origin') == 4) selected @endif>Midia sociais</option>
                                <option value="5" @if (Session::get('sacf_origin') == 5) selected @endif>Site</option>
								<option value="6" @if (Session::get('sacf_origin') == 6) selected @endif>Consumidor GOV</option>
								<option value="7" @if (Session::get('sacf_origin') == 7) selected @endif>Procon</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="type_attendance">Tipo de atendimento</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="type_attendance" name="type_attendance" style="width: 100%;">
                                <option value=""></option>
                                <option value="1" @if (Session::get('sacf_type_attendance') == 1) selected @endif>Reclamação</option>
                                <option value="2" @if (Session::get('sacf_type_attendance') == 2) selected @endif>Atend. em garantia</option>
                                <option value="3" @if (Session::get('sacf_type_attendance') == 3) selected @endif>Dúvida técnica</option>
                                <option value="4" @if (Session::get('sacf_type_attendance') == 4) selected @endif>Revenda</option>
                                <option value="5" @if (Session::get('sacf_type_attendance') == 5) selected @endif>Credenciamento</option>
                                <option value="7" @if (Session::get('sacf_type_attendance') == 7) selected @endif>Atendimento fora de garantia</option>
                                <option value="8" @if (Session::get('sacf_type_attendance') == 8) selected @endif>Atendimento negado (erro de inst.)</option>
                                <option value="9" @if (Session::get('sacf_type_attendance') == 9) selected @endif>Autorização de instalação</option>
                                <option value="10" @if (Session::get('sacf_type_attendance') == 10) selected @endif>Atendimento tercerizado</option>
								<option value="11" @if (Session::get('sacf_type_attendance') == 11) selected @endif>Atendimento em cortesia</option>
                                <option value="6" @if (Session::get('sacf_type_attendance') == 6) selected @endif>Outros</option>
                            </select>
                        </fieldset>
                    </div>
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
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Cliente</label>
                        <fieldset class="form-group">
                            <select class="js-select21 form-control" id="client" name="client" style="width: 100%;" multiple>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Cidade</label>
                        <fieldset class="form-group">
                            <input type="text" name="city" value="{{ Session::get('sacf_city') }}" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Estado</label>
                        <fieldset class="form-group">
                            <input type="text" name="state" value="{{ Session::get('sacf_state') }}" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <fieldset class="form-group">
                            <label for="model">Modelo do equipamento</label>
                            <select class="form-control js-select222" style="width: 100%" id="model" name="model" multiple>
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Data inicial</label>
                        <fieldset class="form-group">
                            <input type="text" name="start_date" id="start_date" class="form-control date-mask"  value="{{ Session::get('sacf_start_date') }}">
                        </fieldset>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Data final</label>
                        <fieldset class="form-group">
                            <input type="text" name="end_date" id="end_date" class="form-control date-mask" value="{{ Session::get('sacf_end_date') }}">
                        </fieldset>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Autorizada/Credenciada</label>
                        <fieldset class="form-group">
                            <select class="js-select22 form-control" id="authorized" name="authorized" style="width: 100%;" multiple>
                            </select>
                        </fieldset>
                    </div>
					<div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_status') == 1) selected @endif>Verificando Atend.</option>
                                <option value="2" @if (Session::get('sacf_status') == 2) selected @endif>Em andamento</option>
                                <option value="3" @if (Session::get('sacf_status') == 3) selected @endif>Concluído</option>
                                <option value="4" @if (Session::get('sacf_status') == 4) selected @endif>Cancelado</option>
                                <option value="5" @if (Session::get('sacf_status') == 5) selected @endif>Atend. Pendente</option>
                                <option value="6" @if (Session::get('sacf_status') == 6) selected @endif>Reembolso pago</option>
                                <option value="7" @if (Session::get('sacf_status') == 7) selected @endif>Finalização negada</option>
                                <option value="8" @if (Session::get('sacf_status') == 8) selected @endif>Sem atendente</option>
								<option value="9" @if (Session::get('sacf_status') == 9) selected @endif>Sem resposta do operador</option>
                            </select>
                        </fieldset>
                    </div>
					<div class="col-md-3 problem_category">
                        <fieldset class="form-group">
                            <label for="problem_category">Categoria de Problema</label>
                            <select class="form-control" id="problem_category" name="problem_category">
                                <option value=""></option>
                                @foreach ($problem_category as $key)
                                    <option value="{{ $key->id }}">{{ $key->description }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-12 d-flex align-items-center">
                        <button type="submit" value="0" id="btn_filter" name="export_external" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-12 d-flex align-items-center">
                        <button type="submit" value="1" name="export_external" id="exportQueue" class="btn btn-success btn-block glow users-list-clear mb-0">Exportar dados</button>
                    </div>
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" style="margin-bottom: 240px !important;" class="table">
                                    <thead>
                                        <tr>
                                            <th>Protocolo</th>
                                            <th>Tipo</th>
                                            <th>Origem</th>
                                            <th>Feito em</th>
                                            <th>Atendente</th>
                                            <th>Cliente</th>
                                            <th>Autorizado</th>
                                            <th>status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($protocol as $key) { ?>
                                        <tr>
                                            <td>
                                                <?php $i = countIntection($key->sacMsgs()->get()) ?>
                                                @if ($i > 0)
                                                <div class="badge badge-pill badge-glow badge-warning mr-1">{{$i}}</div>
                                                @endif
                                                <?= $key->code ?>
                                            </td>
                                            <td>
                                                @if ($key->type == 1)
                                                Reclamação
                                                @elseif ($key->type == 2)
                                                Atend. Garantia
                                                @elseif ($key->type == 3)
                                                Dúvida técnica
                                                @elseif ($key->type == 4)
                                                Revenda
                                                @elseif ($key->type == 5)
                                                Credenciamento
                                                @elseif ($key->type == 7)
                                                Atend. fora de garantia
                                                @elseif ($key->type == 8)
                                                Atend. negado
                                                @elseif ($key->type == 9)
                                                Autorização instalação
                                                @elseif ($key->type == 10)
                                                Atend. Tercerizado
                                                @else
                                                Outros
                                                @endif
                                            </td>
                                            <td>
                                                @if ($key->origin == 1)
                                                Telefone
                                                @elseif ($key->origin == 2)
                                                E-mail
                                                @elseif ($key->origin == 3)
                                                Reclame aqui
                                                @elseif ($key->origin == 4)
                                                Midias sociais
                                                @elseif ($key->origin == 5)
                                                Site
												@elseif ($key->origin == 6)
                                                Consumidor GOV
												@elseif ($key->origin == 7)
                                                Procon
                                                @endif
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($key->created_at)) ?></td>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                            <td>@if(isset($key->clientProtocol['id'])) <a target="_blank" href="/sac/client/edit/<?= $key->clientProtocol['id'] ?>"><?= strWordCut($key->clientProtocol['name'], 13) ?></a> @endif</td>
                                            <td>@if(isset($key->authorizedProtocol['id'])) <a target="_blank" href="/sac/authorized/edit/<?= $key->authorizedProtocol['id'] ?>"><?= strWordCut($key->authorizedProtocol['name'], 13) ?></a> @endif</td>
                                            <td>
                                                @if ($key->is_denied == 1)
                                                <span class="badge badge-light-danger">Finalização negada</span>
                                                @elseif ($key->is_refund == 1)
                                                <span class="badge badge-light-danger">Reembolso pago</span>
												@elseif ($key->is_refund_pending == 1)
                                                <span class="badge badge-light-danger">Reembolso pendente</span>
                                                @elseif ($key->is_cancelled == 1)
                                                <span class="badge badge-light-danger">Cancelado</span>
                                                @elseif ($key->pending_completed == 1)
                                                <span class="badge badge-light-warning">Pendente p/ completar</span>
                                                @elseif ($key->is_completed == 1)
                                                <span class="badge badge-light-success">Concluído</span>
                                                @elseif ($key->in_progress == 1)
                                                <span class="badge badge-light-warning">Em andamento</span>
												@elseif ($key->in_wait_documents == 1)
                                                <span class="badge badge-light-info">Aguard. documentos</span>
                                                @elseif ($key->in_wait == 1)
                                                <span class="badge badge-light-info">Verificando Atend.</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if ($key->is_completed == 0 and $key->is_cancelled == 0)
                                                        <a class="dropdown-item" href="/sac/warranty/edit/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                        @endif
                                                        @if ($key->is_completed == 0 and $key->is_cancelled == 0 and $key->authorized_id == null)
                                                        <a class="dropdown-item" onclick="createMarker(<?= $key->latitude ?>, <?= $key->longitude ?>)" href="javaScript:void(0)"><i class="bx bx-map mr-1"></i> Buscar Autorizadas</a>
                                                        @endif
                                                        <a class="dropdown-item" href="/sac/warranty/interactive/<?= $key->id ?>"><i class="bx bx-chat mr-1"></i> Interagir</a>
                                                        <a class="dropdown-item" onclick="anexoFiles(<?= $key->id ?>)"> <i class="bx bx-file-blank mr-1"></i> Arquivos Anexados</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
										<?= $protocol->appends([
                                            'left_5' => Request::get('left_5'),
                                            'left_15' => Request::get('left_15'),
                                            'left_30' => Request::get('left_30'),
                                            'code' => Request::get('code'),
                                            'r_code' => Request::get('r_code'),
                                            'status' => Request::get('status'),
                                            'client' => Request::get('client'),
                                            'authorized' => Request::get('authorized'),
                                            'is_warranty' => Request::get('is_warranty'),
                                            'origin' => Request::get('origin'),
                                            'type' => Request::get('type'),
                                            'segment' => Request::get('segment'),
											'not_response' => Request::get('not_response'),
											'state' => Request::get('state'),
											'city' => Request::get('city'),
											'model' => Request::get('model'),
											'start_date' => Request::get('start_date'),
											'end_date' => Request::get('end_date'),
											'authorization_install' => Request::get('authorization_install'),
											'type_attendance' => Request::get('type_attendance'),
											'monitor_block_1' => Request::get('monitor_block_1'),
											'monitor_block_2' => Request::get('monitor_block_2'),
											'monitor_block_3' => Request::get('monitor_block_3'),
											'monitor_block_4' => Request::get('monitor_block_4'),
											'monitor_p_block_1' => Request::get('monitor_p_block_1'),
											'monitor_p_block_2' => Request::get('monitor_p_block_2'),
											'monitor_p_block_3' => Request::get('monitor_p_block_3'),
											'monitor_p_block_4' => Request::get('monitor_p_block_4'),
											'monitor_p_block_5' => Request::get('monitor_p_block_5'),
											'monitor_p_block_6' => Request::get('monitor_p_block_6'),
											'monitor_p_block_7' => Request::get('monitor_p_block_7'),
											'monitor_p_block_8' => Request::get('monitor_p_block_8'),	
											'monitor_p_block_9' => Request::get('monitor_p_block_9'),
											'monitor_p_block_10' => Request::get('monitor_p_block_10'),
											'problem_category' => Request::get('problem_category')
                                            ])->links(); ?>
                                    </ul>
                                </nav>
                            </div>
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

{{-- Interactive modal for Map  --}}
<div class="modal fade text-left" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="modal-map" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-map">Autorizadas próximas</h3>
            <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <fieldset class="form-group">
                        <label for="address">Informe cep, estado, endereço..</label>
                        <input type="text" class="form-control" name="address" placeholder="Rua exemplo 775" id="address">
                    </fieldset>
                </div>

                <div class="col-md-4 col-sm-12">
                    <label for="users-list-verified">Tipo de linha</label>
                    <fieldset class="form-group">
                        <select class="form-control" id="type_line1" name="type_line1" style="width: 100%;">
                            <option value="1">Residencial</option>
                            <option value="2">Comercial</option>
                        </select>
                    </fieldset>
                </div>

                <div class="col-md-4 col-sm-12 mt-1 loadath" style="height: 450px; overflow-y: scroll;">

                </div>
                <div class="col-md-8 col-sm-12 mt-1">
                    <div id="map" style="height: 100%; width:100%"></div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

    <script>
        var id_protocol = '';

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

        $(document).ready(function () {
            $(".js-select2").select2({
                maximumSelectionLength: 1,
            });

            $("#sel_typeline").change(function () {
                $("#segment").val($(this).val());
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


            $(".js-select222").select2({
                maximumSelectionLength: 1,
                ajax: {
                    url: '/misc/sac/product/',
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



            <?php if (!empty(Session::get('sacf_r_code'))) { ?>
            $('.js-select2').val(['<?= Session::get('sacf_r_code') ?>']).trigger('change');
            <?php } ?>
            <?php if (!empty(Session::get('sacf_client'))) { ?>
            $('.js-select21').val(['<?= Session::get('sacf_client') ?>']).trigger('change');
            <?php } ?>
            <?php if (!empty(Session::get('sacf_authorized'))) { ?>
            $('.js-select22').val(['<?= Session::get('sacf_authorized') ?>']).trigger('change');
            <?php } ?>
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

            $(".js-select21").select2({
                    maximumSelectionLength: 1,
                    language: {
                        noResults: function () {

                            var url = "'/sac/client/edit/0'";
                            return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo cliente</button>');
                        }
                    },
                    ajax: {
                        url: '/misc/sac/client/',
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

                setInterval(() => {
                $("#mAfterSales").addClass('sidebar-group-active active');
                $("#mSac").addClass('sidebar-group-active active');
                $("#mSacAll").addClass('active');
            }, 100);

        });
    </script>

    <script>
        // Drag Event
        var gmarkers = [];
        var markers = [];
        var markern = [];
        var pin_client = '/media/pin.png';
        var pin_authorized = '/media/pin_ath.png'
        var map;
        var infoWindow;

        $("#type_line1").change(function (e) {
            if ($("#address").val() != "") {

                var geocoder = new google.maps.Geocoder()
                var end = $("#address").val();
                var endereco = end;


                geocoder.geocode( { 'address': endereco}, function(resultado, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                var lat1 = resultado[0].geometry.location.lat();
                var long1 = resultado[0].geometry.location.lng();
                var type_line = $("#type_line1").val();

                createMarker(lat1, long1, type_line);

                } else {
                alert('Erro ao converter endereço: ' + status);
                }
            });

            }

        });

        $( "#address" ).blur(function() {
            if ($("#address").val() != "") {

                var geocoder = new google.maps.Geocoder()
                var end = $("#address").val();
                var endereco = end;


                geocoder.geocode( { 'address': endereco}, function(resultado, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                var lat1 = resultado[0].geometry.location.lat();
                var long1 = resultado[0].geometry.location.lng();
                var type_line = $("#type_line1").val();

                createMarker(lat1, long1, type_line);

                } else {
                alert('Erro ao converter endereço: ' + status);
                }
            });

            }

        });

    function initAutocomplete() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -23.005171, lng: -43.348923},
          zoom: 10,
          mapTypeId: 'roadmap'
        });

		var input = document.getElementById('address');
        var searchBox = new google.maps.places.SearchBox(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

		infoWindow = new google.maps.InfoWindow;
      }
      // Sets the map on all markers in the array.
		function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setMapOnAll(null);
    }
    function createMarker(lat, long, type_line = '') {
        clearMarkers();
        block();
        // Create a marker for each place.
        $.ajax({
            type: "GET",
            url: "/sac/warranty/get/authorizeds",
            data: {latitude: lat, longitude: long, type_line: type_line},
            success: function (response) {
                unblock();
                if (response.success) {
                    console.log(response);
                    var marker = new google.maps.Marker({
                        map: map,
                        icon: pin_client,
                        title: 'Local do serviço',
                        zIndex: 2,
                        animation: google.maps.Animation.DROP,
                        position: {lat: lat, lng: long},
                        draggable: false
                    });

                    markers.push(marker);
                    if (response.authorizeds.length == 0) {
                        $(".loadath").html('');
                    }
                    var list = '';
                    for (i = 0; i < response.authorizeds.length; i++) {
                        var marker_ll = new google.maps.LatLng(response.authorizeds[i]['latitude'], response.authorizeds[i]['longitude']);
                        var result = new google.maps.Marker({
                            position: marker_ll,
                            map: map,
                            zIndex: 1,
                            icon: pin_authorized,
                            animation: google.maps.Animation.DROP,
                            title: response.authorizeds[i]['name'],
                        });
                        markers.push(result);
                        html = '<h6>Informações de contato</h6><p><small class="text-muted"><b>Nome fantasia:</b> '+ response.authorizeds[i]['name'] +'</small><br><small class="text-muted"><b>Nome do contato:</b> '+ response.authorizeds[i]['name_contact'] +'</small><br><small class="text-muted"><b>CNPJ:</b> '+ response.authorizeds[i]['identity'] +'</small><br><small class="text-muted"><b>Telefone:</b> '+ response.authorizeds[i]['phone_1'] +'</small><br><small class="text-muted"><b>Telefone:</b> '+ response.authorizeds[i]['phone_2'] +'</small><br><small class="text-muted"><b>Email:</b> '+ response.authorizeds[i]['email'] +'</small></p>';
                        bindInfoWindow(result, map, infoWindow, html);

                        list += '<div class="card" style="box-shadow: -8px 12px 18px 0 rgba(25,42,70,.13) !important">';
                        list += '<div class="card-content">';
                        list += '<div class="card-body">';
                        list += '<h4 class="card-title">'+ response.authorizeds[i]['name'] +'</h4>';
                        list += '<p class="card-text">';
                        list += '<div style="margin-top: 5px;"><b>Nome do contato:</b> '+response.authorizeds[i]['name_contact']+'</div>';
                        list += '<div style="margin-top: 5px;"><b>CNPJ:</b> '+response.authorizeds[i]['identity']+'</div>';
                        list += '<div style="margin-top: 5px;"><b>Telefone:</b> '+response.authorizeds[i]['phone_1']+' / '+response.authorizeds[i]['phone_2']+'</div>';
                        list += '<div style="margin-top: 5px;"><b>Email:</b> '+response.authorizeds[i]['email']+'</div>';
                        list += '</p>';
                        list += '</div>';
                        list += '</div>';
                        list += '</div>';
                    }

                    $(".loadath").html(list);
                }
            }
        });

        var bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(lat, long));
        google.maps.event.addListener(map, 'zoom_changed', function() {
            zoomChangeBoundsListener =
                google.maps.event.addListener(map, 'bounds_changed', function(event) {
                    if (this.getZoom() > 13 && this.initialZoom == true) {
                        // Change max/min zoom here
                        this.setZoom(13);
                        this.initialZoom = false;
                    }
                google.maps.event.removeListener(zoomChangeBoundsListener);
            });
        });
        map.initialZoom = true;
        map.fitBounds(bounds);
        $('#modal-map').modal();
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ getConfig("google_key_web") }}&libraries=places&callback=initAutocomplete"></script>

@endsection
