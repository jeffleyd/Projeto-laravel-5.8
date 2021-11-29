@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<style>
    .table th, .table td {
        padding: 1.10rem 0.20rem;
    }
</style>  
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-6 col-sm-12 col-lg-6">
                    <h5 class="content-header-title float-left pr-1 mb-0">Assitência Técnica</h5>
                    <div class="breadcrumb-wrapper col-12">
                        Central de Custos
                    </div>
                </div>
                <div class="col-6 col-sm-12 col-lg-6">
                    <fieldset class="form-group float-right">
                        <select class="form-control" id="type_costs" name="type_costs" style="position: relative; bottom:10px; border-color: #3568df;color: #3568df;">
                            <option value="1">Custos Ordem de serviço </option>
                            <option value="2">Custos Remessa de peça </option>
                        </select>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header row"></div>
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">       
                        <div class="card-body">
                            <div class="top d-flex flex-wrap">
                                <div class="action-filters flex-grow-1">
                                    <div class="dataTables_filter mt-1">
                                        <h5 >Custos Ordem de Serviço / Protocolo</h5>
                                    </div>
                                </div>
                                <div class="actions action-btns d-flex align-items-center">
                                    <div class="dropdown invoice-filter-action">
                                        <button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_filter"><i class="bx bx-search-alt"></i> Filtrar</button>
                                    </div>
                                    <div class="dropdown invoice-options">
                                        <button type="button" class="btn btn-success shadow mr-0" data-toggle="modal" data-target="#modal_export"><i class="bx bx-import"></i> Exportar</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table id="list-datatable" class="table" style="text-align: center;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Protocolo</th>
                                            <th>Status</th>
                                            <th>Descrição</th>
                                            <th>Valor peças</th>
                                            <th>Valor frete</th>
                                            <th>Mão de obra</th>
                                            <th>Visita</th>
											<th>Serv. Prestado</th>
											<th>Valor Gás</th>
                                            <th>Total<i class="bx bx-info-circle cursor-pointer" 
                                                        style="color: #3568df; position: relative; bottom: 5px; font-size: 0.9rem;"
                                                        data-toggle="tooltip" data-placement="bottom" data-original-title="TOTAL = VALOR PEÇAS + VALOR FRETE + MÃO DE OBRA + VISITA"></i>
                                            </th>
                                            <th>Atualizado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($costs as $i => $key)
                                        
                                            <tr class="cursor-pointer showDetails">
                                                <td>
                                                    <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer" style="color: #3568df;"></i>
                                                </td>
                                                <td>{{ $key->code }}</td>
                                                <td><span class="badge  @if($key->is_completed == 1) badge-light-success @else badge-light-primary @endif">{{ $key->is_completed == 1 ? 'Concluído' : 'Andamento' }}</span></td>
                                                <td><?= config('gree.type_sac_protocol')[$key->type] ?></td>
                                                <td>R$ {{ number_format($key->value_part_total, 2, ',', '.') }}</td>
                                                <td>R$ {{ number_format($key->value_shipping_total, 2, ',', '.') }}</td>
                                                <td>R$ {{ number_format($key->value_labor_total, 2, ',', '.') }}</td>
                                                <td>R$ {{ number_format($key->value_visit_total, 2, ',', '.') }}</td>
												<td>R$ {{ number_format($key->value_total_extra, 2, ',', '.') }}</td>
												<td>R$ {{ number_format($key->value_total_gas, 2, ',', '.') }}</td>
                                                <td style="color: #4e4141;">R$ {{ number_format($key->cost_total, 2, ',', '.') }}</td>
                                                <td>{{  $key->date_updated ? date('d/m/Y', strtotime($key->date_updated)) : '00/00/0000' }}</td>
                                            </tr>

                                            <tr style="display: none;" class="group info_extra">
                                                <td colspan="12">
                                                    <div class="card" style="margin-bottom: 0rem;">
                                                        <div class="card-body">
                                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-toggle="tab" href="#tab-os-{{$i}}" role="tab" aria-controls="tab-os" aria-selected="true"><i class="bx bx-file" style="position: relative;top: 2px;right: -2px;"></i> Ordem de Serviço</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" data-toggle="tab" href="#tab-part-{{$i}}" role="tab" aria-controls="profile-fill" aria-selected="false"><i class="bx bx-cube-alt" style="position: relative;top: 2px;right: -2px;"></i> Peças Detalhadas</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-toggle="tab" href="#tab-model-{{$i}}" role="tab" aria-controls="profile-fill" aria-selected="false"><i class="bx bx-notepad" style="position: relative;top: 2px;right: -2px;"></i> Modelos</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane" id="tab-os-{{$i}}" role="tabpanel">
                                                                    <div class="table-responsive">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Código OS</th>
                                                                                    <th>Status OS</th>
                                                                                    <th>Data emissão</th>
                                                                                    <th>Visita</th>
                                                                                    <th>Mão de Obra</th>
                                                                                    <th>Total</th>
                                                                                </tr>    
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($key->sacosprotocol as $os)
                                                                                    <tr>
                                                                                        <td>{{ $os->code }}</td>
                                                                                        <td><?= $os->status_os ?></td>
                                                                                        <td>{{ date('d/m/Y', strtotime($os->created_at)) }}</td>
                                                                                        <td>R$ {{ number_format($os->visit_total, 2, ',', '.') }}</td>
                                                                                        <td>R$ {{ number_format(($os->value_labor), 2, ',', '.') }}</td>
                                                                                        <td>R$ {{ number_format(($os->total), 2, ',', '.') }}</td>
                                                                                    </tr>  
                                                                                @endforeach 
                                                                            </tbody>    
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane active" id="tab-part-{{$i}}" role="tabpanel" aria-labelledby="profile-tab-fill">
                                                                    <div class="table-responsive">
                                                                        <table class="table" style="text-align: center;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th></td>
                                                                                    <th style="width: 12%;">Peça</th>
                                                                                    <th>Qtd.</th>
                                                                                    <th>Fatur.</th>
                                                                                    <th>Nº Ordem</th>
                                                                                    <th>N° DI</th>
                                                                                    <th>NF</th>
                                                                                    <th>Emissão NF</th>
                                                                                    <th>Valor peça</th>
                                                                                    <th>frete</th>
																					<th>frete retor.</th>
                                                                                    <th>Mão de obra</th>
                                                                                    <th>Visita</th>
                                                                                    <th>total</th>
                                                                                    <th></th>
                                                                                </tr>    
                                                                            </thead>
                                                                            <tbody>

                                                                                @foreach ($key->sacpartprotocol as $part)
                                                                                    
                                                                                    <tr>
                                                                                        <td style="padding:0px;">
                                                                                            @if($part->protocol_cost_field()->observation != "")
                                                                                                <i class="bx bx-info-circle modal-details" data-observation="{{ $part->protocol_cost_field()->observation }}" style="cursor: pointer; color:#3568df;" data-toggle="tooltip" data-placement="left" data-original-title="Clique para visualizar a observação"></i>
                                                                                            @else 
                                                                                                <i class="bx bx-block" style="cursor: pointer; color:#3568df;" data-toggle="tooltip" data-placement="left" data-original-title="Não possui Observação"></i>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td><b>{{$part->code_os}}</b><br>{{ $part->description_part }} <br><b>({{$part->code_part}})</b>
																							@if($part->is_invoice == 1)
                                                                                                <span class="badge badge-light-success">Faturado</span>
                                                                                            @else    
                                                                                                <span class="badge badge-light-danger">Não Fatur.</span>
                                                                                            @endif    
																							<br>
																							@if($part->protocol_cost_field()->update_reason == 1) 
                                                                                                <span class="badge badge-light-primary" style="margin-top: 5px;">Envio Inicial</span>
                                                                                            @elseif ($part->protocol_cost_field()->update_reason == 2)    
                                                                                                <span class="badge badge-light-primary" style="margin-top: 5px;">Logística Reversa</span>
                                                                                            @elseif ($part->protocol_cost_field()->update_reason == 3)    
                                                                                                <span class="badge badge-light-primary" style="margin-top: 5px;">Reenvio</span>
                                                                                            @elseif ($part->protocol_cost_field()->update_reason == 4)   
                                                                                                <span class="badge badge-light-primary" style="margin-top: 5px;">Coleta</span>    
                                                                                            @endif
																						</td>
                                                                                        <td>{{ $part->quantity }}</td>
                                                                                        <td style="font-size: 13px;">
																						{{ $part->protocol_cost_field()->date_billing ? date('d/m/Y', strtotime($part->protocol_cost_field()->date_billing)) : '00/00/0000' }}<br>
                                                                                            ({{ $part->protocol_cost_field()->hour_billing ? $part->protocol_cost_field()->hour_billing : '00:00'}})
																						</td>
                                                                                        <td>{{ $part->protocol_cost_field()->number_order }}</td>
                                                                                        <td>{{ $part->protocol_cost_field()->number_di }}</td>
                                                                                        <td>{{ $part->protocol_cost_field()->number_nf }}</td>
                                                                                        <td style="font-size: 13px;">
																							{{ $part->protocol_cost_field()->date_emission_nf ? date('d/m/Y', strtotime($part->protocol_cost_field()->date_emission_nf)) : '00/00/0000' }}<br>
                                                                                            ({{ $part->protocol_cost_field()->hour_emission_nf ? $part->protocol_cost_field()->hour_emission_nf : '00:00' }})
																						</td>
                                                                                        <td>R$ {{ number_format($part->protocol_cost_field()->value_part, 2, ',', '.') }}</td>
                                                                                        <td>R$ {{ number_format($part->protocol_cost_field()->value_shipping, 2, ',', '.') }}</td>
																						<td>R$ {{ number_format($part->protocol_cost_field()->value_shipping_return, 2, ',', '.') }}</td>
                                                                                        <td>R$ {{ number_format($part->total, 2, ',', '.') }}</td>
                                                                                        <td>R$ {{ number_format($key->value_visit_total, 2, ',', '.')}}</td>

                                                                                        <td>R$ {{ number_format($part->part_sum_total, 2, ',', '.') }}</td>
                                                                                        <td style="padding:0px;">
                                                                                            <div class="dropleft">
                                                                                                <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="editCost(this);" data-json='<?= htmlspecialchars(json_encode($part), ENT_QUOTES, "UTF-8") ?>'><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="historicCost(this);" data-json='<?= htmlspecialchars(json_encode($part), ENT_QUOTES, "UTF-8") ?>'><i class="bx bx-file mr-1"></i> Histórico de atualização</a>
                                                                                                    @if($part->is_invoice == 0)
                                                                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="confirmInvoice(this);" data-id="<?= $part->id ?>"><i class="bx bx-check-circle mr-1"></i> Confirmar faturamento</a>
                                                                                                    @endif    
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>  
                                                                                @endforeach 
                                                                            </tbody>    
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="tab-model-{{$i}}" role="tabpanel" aria-labelledby="profile-tab-fill">
                                                                    <div class="table-responsive">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Código modelo</th>
                                                                                    <th>Número de série</th>
                                                                                </tr>    
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($key->sacModelProtocol as $model)
                                                                                    <tr>
                                                                                        <td>{{ $model->sacProductAir != null ? $model->sacProductAir->model : '' }}</td>
                                                                                        <td>{{ $model->serial_number }}</td>
                                                                                    </tr>  
                                                                                @endforeach 
                                                                            </tbody>    
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                          </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                                <nav>
                                    <ul class="pagination justify-content-end">
                                        <?= $costs->appends([
                                            'status' => Session::get('cost_status'),
                                            'begin_date_submit' => Session::get('cost_begin_date_submit'),
                                            'end_date_submit' => Session::get('cost_end_date_submit'),
                                            'begin_date_os_submit' => Session::get('cost_begin_date_os_submit'),
                                            'end_date_os_submit' => Session::get('cost_end_date_os_submit')
                                        ])->links(); ?>   
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<div class="modal fade" id="modal_cost" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="exampleModalScrollableTitle">ATUALIZAR CUSTO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="/sac/assistance/warranty/os/costs/edit_do" id="register_cost">
                    <input type="hidden" name="cost_id" id="cost_id" value="0">
                    <input type="hidden" name="protocol_id" id="protocol_id">
                    <input type="hidden" name="part_protocol_id" id="part_protocol_id">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Motivo atualização</label>
                                <select class="form-control" name="update_reason" id="update_reason">
                                    <option></option>
                                    <option value="1">Envio Inicial</option>
                                    <option value="2">Logística Reversa</option>
                                    <option value="3">Reenvio</option>
                                    <option value="4">Coleta</option>
                                </select>
                            </div>
                        </div>    
                        <div class="col-6">
                            <div class="form-group">
                                <label>Data envio faturamento</label>
                                <input type="text" class="form-control date-format" name="date_billing" id="date_billing" placeholder="00/00/0000">
                            </div>
                        </div>
						<div class="col-6">
                            <div class="form-group">
                                <label>Hora envio faturamento</label>
                                <input type="text" class="form-control hour-format" name="hour_billing" id="hour_billing" placeholder="00:00">
                            </div>
                        </div>
                        <div class="col-12">        
                            <div class="form-group">
                                <label for="number_order">Nº Ordem  (S61) – infor</label>
                                <input type="text" class="form-control input-cost" name="number_order" id="number_order" placeholder="S00000000">
                            </div>
                        </div>    
                        <div class="col-12">        
                            <div class="form-group">
                                <label for="track_code">N° DI</label>
                                <input type="text" class="form-control input-cost" name="number_di" id="number_di" placeholder="00/0000000-0" style="text-transform: uppercase">
                            </div>
                        </div>    
                        <div class="col-12">        
                            <div class="form-group">
                                <label for="track_code">NF (n° nota fiscal)</label>
                                <input type="text" class="form-control input-cost" name="number_nf" id="number_nf" placeholder="00000">
                            </div>
                        </div>    
                        <div class="col-6">        
                            <div class="form-group">
                                <label for="track_code">Data de emissão Nf</label>
                                <input type="text" class="form-control input-cost date-format" name="date_emission_nf" id="date_emission_nf" placeholder="00/00/0000">
                            </div>
                        </div>    
						<div class="col-6">        
                            <div class="form-group">
                                <label for="track_code">Hora de emissão Nf</label>
                                <input type="text" class="form-control input-cost hour-format" name="hour_emission_nf" id="hour_emission_nf" placeholder="00:00">
                            </div>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="track_code">Valor da peça (R$)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" class="form-control input-cost money" name="value_part" id="value_part" placeholder="0,00">
                                </div>
                            </fieldset>
                        </div>    
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="track_code">Valor do frete (R$)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" class="form-control input-cost money" name="value_shipping" id="value_shipping" placeholder="0,00">
                                </div>
                            </fieldset>
                        </div>    
						<div class="col-12 div-shipping-return" style="display: none;">
                            <fieldset class="form-group">
                                <label for="track_code">Valor do frete retorno (R$)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" class="form-control input-cost money" name="value_shipping_return" id="value_shipping_return" placeholder="0,00">
                                </div>
                            </fieldset>
                        </div>    
                        <div class="col-12">
                            <div class="form-group">
                                <label for="track_code">Observação</label>
                                <textarea class="form-control" id="observation" name="observation" rows="3" placeholder="Informe a observação..."></textarea>
                            </div>
                        </div>    
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_add_cost">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Atualizar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar Custos Ordem de Serviço</span>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="form_modal_filter">
                    <div class="row">

                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Inicial Emissão OS</label>
                                <input type="text" class="form-control date-format" name="begin_date_os" placeholder="00/00/0000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Final Emissão OS</label>
                                <input type="text" class="form-control date-format" name="end_date_os" placeholder="00/00/0000">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Protocolo</label>
                                <input type="text" class="form-control" name="code_protocol" placeholder="G0000000000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Ordem de serviço</label>
                                <input type="text" class="form-control" name="code_os" placeholder="W00000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Status</label>
                                <select class="form-control" name="status">
                                    <option></option>
                                    <option value="1">Concluído</option>
                                    <option value="99">Andamento</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Inicial de Atualização Custo</label>
                                <input type="text" class="form-control date-format" name="begin_date" placeholder="00/00/0000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Final de Atualização Custo</label>
                                <input type="text" class="form-control date-format" name="end_date" placeholder="00/00/0000">
                            </div>
                        </div>
                    </div>
                </form>
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_filter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Exportar Custos</span>
            </div>
            <form action="{{Request::url()}}" id="form_modal_filter">
                <input type="hidden" name="export" value="1">
                <div class="modal-body">
					
					<div class="alert border-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-error"></i>
                            <span>
                                Data Inicial e final para exportação é referente à atualização de custo
                            </span>
                        </div>
                    </div>
					
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Inicial</label>
                                <input type="text" class="form-control date-format" name="start_date" placeholder="00/00/0000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Final</label>
                                <input type="text" class="form-control date-format" name="end_date" placeholder="00/00/0000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Status</label>
                                <select class="form-control" name="status">
                                    <option></option>
                                    <option value="1">Concluído</option>
                                    <option value="99">Andamento</option>
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
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Exportar</span>
                    </button>
                </div>
            </form> 
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_historic" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Histórico de atualização</span>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Colaborador</th>
                                <th>Motivo atualização</th>
                                <th>Data de atualização</th>
                            </tr>    
                        </thead>
                        <tbody id="table-cost-historic"></tbody>
                    </table>    
                </div>    
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Observação</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <pre style="white-space: pre-wrap;color: #445567;font-size: 14px;background-color: #fff;" id="modal_observation"></pre>
                    </div>
                </div>
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>


<script>
    $(document).ready(function () {
		
		$("#update_reason").change(function() {
            if($(this).val() == 2) { 
                $(".div-shipping-return").show();
            } else {
                $(".div-shipping-return").hide();
                $("#value_shipping_return").val('');
            }
        });

        $("#type_costs").val(1);

        $("#type_costs").change(function (e) {
            if($(this).val() == 2) {
                window.location.href = "/sac/assistance/warranty/remittance/costs/all";
            } else {
                window.location.href = "/sac/assistance/warranty/os/costs/all";
            }
        });

        $('.showDetails td').not('.no-click').click(function (e) {
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
        });    

        $("#btn_add_cost").click(function() {
            if($("#update_reason").val() == "") {
                return $error('Selecione o motivo de atualização');
            }
			else if($("#date_billing").val() == "") {
                return $error('Informe data envio de faturamento');
            }
            else if($("#hour_billing").val() == "") {
                return $error('Informe hora envio de faturamento');
            }
            else if($("#number_order").val() == "") {
                return $error('Informe o Nº Ordem  (S61) – infor');
            } 
            else if($("#number_di").val() == "") {
                return $error('Informe o N° DI');
            }
            else if($("#number_nf").val() == "") {
                return $error('Informe NF (n° nota fiscal)');
            }
            else if($("#date_emission_nf").val() == "") {
                return $error('Informe data de emissão Nf');
            }
			else if($("#hour_emission_nf").val() == "") {
                return $error('Informe hora de emissão Nf');
            }
            else if($("#value_part").val() == "") {
                return $error('Informe o valor da peça (R$)');
            }    
            else if($("#value_shipping").val() == "") {
                return $error('Informe o valor do frete (R$)');
            }
            else {
                block();
                $('#register_cost').submit();
            }
        });

        $("#form_modal_filter").submit(function(event) {
            block();
        });


        $("#btn_filter").click(function() {
            block();
            $('#form_modal_filter').submit();
        });

        $(".modal-details").click(function() {

            var observation= $(this).attr('data-observation');
            if(observation != "") {
                $("#modal_observation").html(observation);
                $("#modal_observation").css('text-align', 'left');
            } else {
                $("#modal_observation").css('text-align', 'center');
                $("#modal_observation").html("Não possui observações");
            }
            
            $("#modal_details").modal('show');
        });    

        $('.date-format').pickadate({
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

		$('.money').mask('#.##0,00', {reverse: true});
		$('.hour-format').mask('00:00', {reverse: true});

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mTAssist").addClass('sidebar-group-active active');
            $("#mTAssistCosts").addClass('active');
        }, 100);
    });
	
	function confirmInvoice($this) {

        var id = $($this).attr('data-id');

        Swal.fire({
            title: "Confirmar faturamento?",
            text: "Deseja confirmar o faturamento desta peça.",
            type: 'warning',
            showCancelButton: true,   
            confirmButtonColor: "#3085d6",   
            cancelButtonColor: '#d33',
            confirmButtonText: "Confirmar",   
            cancelButtonText: "Cancelar",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }).then(function (result) {
            if (result.value) {
                block();
                window.location.href = '/sac/assistance/warranty/os/part/invoice/' + id;
            }
        });
    }

    function editCost($this) {

        var json = $($this).attr('data-json');
        obj = JSON.parse(json);

        $('#protocol_id').val(obj.sac_protocol_id);
        $("#part_protocol_id").val(obj.id);

        $(".input-cost").val('');

        if(obj.sac_protocol_costs) {
            $("#number_order").val(obj.sac_protocol_costs.number_order);
            $("#number_di").val(obj.sac_protocol_costs.number_di);
            $("#number_nf").val(obj.sac_protocol_costs.number_nf);
            $("#date_emission_nf").val(obj.sac_protocol_costs.date_emission_nf);
			$("#hour_emission_nf").val(formatHour(obj.sac_protocol_costs.hour_emission_nf));
            $("#date_billing").val(obj.sac_protocol_costs.date_billing);
			$("#hour_billing").val(formatHour(obj.sac_protocol_costs.hour_billing));
            $("#value_part").val(numberToReal(obj.sac_protocol_costs.value_part));
            $("#value_shipping").val(numberToReal(obj.sac_protocol_costs.value_shipping));
            $("#cost_id").val(obj.sac_protocol_costs.id);
            $("#update_reason").val(obj.sac_protocol_costs.update_reason);
            $("#observation").val(obj.sac_protocol_costs.observation);
			$("#value_shipping_return").val(numberToReal(obj.sac_protocol_costs.value_shipping_return));
			
			if(obj.sac_protocol_costs.update_reason == 2) {
                $(".div-shipping-return").show();
            } else {
                $(".div-shipping-return").hide();
                $("#value_shipping_return").val('');
            }
        }
        $("#modal_cost").modal('show');
    }
	
	function numberToReal(numero) {
        var numerofloat = parseFloat(numero);
        return numerofloat.toLocaleString('pt-br', {minimumFractionDigits: 2});
    }
	
	function formatHour(hour) {
        if(hour) {
            arr_hour = hour.split(':');
            return arr_hour[0] + ':' + arr_hour[1];
        }
    }

    function historicCost($this) {

        var json = $($this).attr('data-json');
        obj = JSON.parse(json);

        var reason = {
            1 : 'Envio Inicial',
            2 : 'Logística Reversa',
            3 : 'Reenvio',
            4 : 'Coleta'
        };

        var html = '';
        if(obj.sac_protocol_costs) {

            object = obj.sac_protocol_costs.sac_protocol_costs_historic

            for (var i = 0; i < object.length; i++) {
                var column = object[i];

                html += '<tr>';
                html += '<td>'+ column.user_historic +' ('+ column.r_code +')</td>';
                html += '<td>'+ reason[column.update_reason] +'</td>';
                html += '<td>'+ new Date(column.updated_at).toLocaleString('pt-BR') +'</td>';
                html += '</tr>';
            }
        } else {
            html += '<tr>';
            html += '<td colspan="3" style="text-align:center;">Não há histórico de atualização!</td>';
            html += '</tr>';
        }
        $("#table-cost-historic").html(html);
        $("#modal_historic").modal('show');
    }    

</script>
@endsection