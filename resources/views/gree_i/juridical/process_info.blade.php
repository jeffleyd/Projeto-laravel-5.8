@extends('gree_i.layout')

@section('content')

<style>
    .details-process-span {
        color: #475F7B;
        font-weight: 400;
        font-family: "Rubik", Helvetica, Arial, serif;
    }
    body.modal-open {
        overflow: auto !important;
    }
    body.modal-open[style] {
        padding-right: 0px !important;
    }
    .icon-edit {
        cursor:pointer; 
        color: #3568df;
    }
    .icon-edit:hover {
        color: #39da8a;
    }
    .badge-cost{
        padding: 0.5rem; 
        font-size: 0.7rem; 
        line-height: 0.6;
    }
    .table th, .table td {
        padding: 1.15rem 1rem;
    }

    .add-notification {
        color: #8a99b5;
    }
    .add-notification:hover {
        color: #39da8a;
    }
</style>

<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Jurídico</h5>
                    <div class="breadcrumb-wrapper col-12">Histórico e Custos</div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header row"></div>
    <div class="content-body">
        <section class="page-user-profile">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="col-lg-12">
                                <div class="card-body px-0">
                                    <ul class="nav user-profile-nav justify-content-center justify-content-md-start nav-tabs border-bottom-0 mb-0" role="tablist">
                                        <li class="nav-item pb-0">
                                            <a class="nav-link d-flex px-1 @if(!Session::has('tabName')) active @endif" id="activity-tab" data-toggle="tab" href="#activity" aria-controls="activity" role="tab" aria-selected="true"><i class="bx bx-file"></i><span class="d-none d-md-block">Processo</span></a>
                                        </li>
                                        <li class="nav-item pb-0">
                                            <a class="nav-link d-flex px-1 @if(Session::has('tabName')) active @endif" id="profile-tab" data-toggle="tab" href="#tab_cost" aria-controls="profile" role="tab" aria-selected="false"><i class="bx bx-dollar-circle"></i><span class="d-none d-md-block">Custos</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tab-content">
                                <div class="tab-pane @if(!Session::has('tabName')) active @endif" id="activity" aria-labelledby="activity-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body">
                                                        <div class="row" style="margin-bottom:20px;">
                                                            <div class="col-md-6">
                                                                <h6 class="mb-0 text-bold-500" style="position: relative; top: 10px;"><span class="text-bold-400">Processo n°:</span> {{ $process->process_number }}</h6>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <!--<button type="button" class="btn btn-success shadow float-right">Exportar</button>-->
                                                                <button type="button" class="btn btn-primary shadow float-right mr-1" id="btn_modal_historic">Adicionar Andamento</button>
                                                            </div>
                                                        </div>
                                                        @if ($process_historic->count() > 0)
                                                        <ul class="widget-timeline mb-0">
                                                            @foreach ($process_historic as $historic)
                                                                <li class="timeline-items timeline-icon-primary active">
                                                                    <div class="timeline-time historic-title"><?= date('d/m/Y', strtotime($historic->date_publication)) ?></div>
                                                                    <h6 class="timeline-title">{{ $historic->title }} 
                                                                        <i class="bx bx-edit icon-edit" data-icon-id="{{ $historic->id }}"></i> 
                                                                        @if($historic->is_notify == 0)
                                                                            <i class="bx bx-bell-off add-notification" style="cursor: pointer;" data-history-id="{{ $historic->id }}"></i>
                                                                        @else 
                                                                            <i class="bx bxs-bell-ring" style="cursor: pointer; color: #ef6969;" data-toggle="tooltip" data-placement="right" title="Notificação agendada: <?= date('d/m/Y', strtotime($historic->date_notify)) ?>"></i>
                                                                        @endif    
                                                                    </h6>
                                                                    <p class="timeline-text" style="ma'rgin-top:20px;"><?= nl2br($historic->description) ?></p><br>
                                                                    @foreach ($historic->juridical_process_documents as $item)
                                                                        <p><a href="{{$item->url}}" target="_blank">
                                                                            <img src="/admin/app-assets/images/icon/{{$item->file_ext}}.png" alt="document" height="23" width="19" class="mr-50">{{$item->juridical_type_document->description}}.{{$item->file_ext}}
                                                                        </a></p>
                                                                    @endforeach
                                                                </li>
                                                            @endforeach
                                                        </ul>    
                                                        <nav aria-label="Page navigation">
                                                            <ul class="pagination justify-content-end">
                                                                <?= $process_historic->appends(getSessionFilters()[0]->toArray())->links(); ?>
                                                            </ul>
                                                        </nav>
                                                        @else  
                                                        <div class="alert border-primary alert-dismissible mb-2" role="alert">
                                                            <div class="d-flex align-items-center">
                                                                <i class="bx bx-file"></i>
                                                                <span>
                                                                    Não há andamentos do processo cadastrados!
                                                                </span>
                                                            </div>
                                                        </div>                
                                                        @endif       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="card-title d-flex mb-25 mb-sm-0">Atualizar Processo</h6>
                                                    <div class="dropleft">
                                                        <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="/juridical/process/register/<?= $process_id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar processo</a>
                                                        </div>
                                                    </div>   
                                                </div>
                                                <div class="card-content">
                                                    <div class="card-body py-1">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <fieldset class="form-group">
                                                                    <label for="process_status">STATUS</label>
                                                                    <select class="form-control" id="process_status" name="process_status">
                                                                        <option value="0" @if($process->status == 0) selected @endif>Cadastrado</option>
                                                                        <option value="1" @if($process->status == 1) selected @endif>Em Andamento</option>
                                                                        <option value="2" @if($process->status == 2) selected @endif>Suspenso</option>
                                                                        <option value="3" @if($process->status == 3) selected @endif>Encerrado (Arquivo Definitivamente)</option>
                                                                        <option value="4" @if($process->status == 4) selected @endif>Cumprimento De Senteça</option>
                                                                    </select>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="shop">encerramento do processo</label>
                                                                    @if($process->date_finished != null)  
                                                                        <input type="text" class="form-control date-mask" id="date_finished" name="date_finished" value="<?= date('d/m/Y', strtotime($process->date_finished)) ?>" placeholder="__/__/____">
                                                                    @else
                                                                        <input type="text" class="form-control date-mask" id="date_finished" name="date_finished" placeholder="__/__/____">
                                                                    @endif
                                                                </div> 
                                                            </div>    
                                                        </div>    
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button type="button" id="btn_modal_update_info" class="btn btn-primary shadow btn-block mr-1">Atualizar</button>
                                                            </div>
                                                        </div>    
                                                    </div>    
                                                </div>     
                                            </div>    
                                            <div class="card widget-todo">
                                                <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="card-title d-flex mb-25 mb-sm-0">Detalhes do Processo</h6>
                                                </div>
                                                <div class="card-body px-0 py-1">
                                                    <ul class="widget-todo-list-wrapper" style="list-style-type: none;margin-left: -10px;">
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title "><span class="details-process-span">Seara:</span> {{ $type_process }}</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Recebimento:</span> <?= date('d/m/Y', strtotime($process->date_received)) ?></span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Requerente:</span>
                                                                        @if($process->costumer_id == 0)
                                                                            <span>{{ $process->name_applicant }} <?= $process->identity_applicant != '' ? '('.$process->identity_applicant.')' : '' ?></span>
                                                                        @else
                                                                            <a target="_blank" href="/sac/client/edit/<?= $process->sac_client->id ?>"><?= strWordCut($process->sac_client->name, 30) ?></a>
                                                                        @endif    
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Requerido:</span> {{$process->name_required}} ({{$process->identity_required}})</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Ação:</span> {{ $process->juridical_type_action->description }}</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Comarca:</span> <?= strWordCut($process->district_court, 33) ?></span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Estado:</span> <?= $process->state_court != '' ? config('gree.states')[$process->state_court] : '' ?></span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Data Ajuizamento:</span> <?= date('d/m/Y', strtotime($process->date_judgment)) ?></span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Valor da Causa: </span>R$ <?= number_format($process->value_cause, 2,",",".") ?></span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Responsável:</span> {{ $process->users->full_name }}</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-1">
                                                                <div class="widget-todo-title-area d-flex align-items-center">
                                                                    <span class="widget-todo-title"><span class="details-process-span">Escritório:</span> 
                                                                        <a target="_blank" href="/juridical/law/firm/register/<?= $process->juridical_law_firm->id ?>"><?= strWordCut($process->juridical_law_firm->name, 30) ?></a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="widget-todo-item">
                                                            <div class="widget-todo-title-area align-items-center">
                                                                <h6>Ementa / Pleitos</h6>
                                                                <span class="widget-todo-title ml-50">
                                                                    <a href="" data-toggle="modal" data-target="#modal_measures">
                                                                        <?= strWordCut($process->measures_plea, 50) ?>
                                                                        <i class="bx bx-link-external" style="top: 3px;position: relative;"></i>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                              </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="tab-pane @if(Session::has('tabName')) active @endif" id="tab_cost">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-content div-cost-process">
                                                    <div class="card-body py-1">
                                                        <div class="badge-circle badge-circle badge-circle-light-success mx-auto mb-50">
                                                            <i class="bx bx-dollar font-medium-5"></i>
                                                        </div>
                                                        <div class="text-muted">Custo total</div>
                                                        <h5 class="mb-0">R$ <?= number_format($total_cost, 2,",",".") ?></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-content div-cost-process">
                                                    <div class="card-body py-1">
                                                        <div class="badge-circle badge-circle badge-circle-light-success mx-auto mb-50">
                                                            <i class="bx bx-check font-medium-5"></i>
                                                        </div>
                                                        <div class="text-muted line-ellipsis">Total pago</div>
                                                        <h5 class="mb-0">R$ <?= number_format($total_paid, 2,",",".") ?></h5>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-content div-cost-process">
                                                    <div class="card-body py-1">
                                                        <div class="badge-circle badge-circle badge-circle-light-success mx-auto mb-50">
                                                        <i class="bx bx-no-entry font-medium-5"></i>
                                                        </div>
                                                        <div class="text-muted line-ellipsis">Total não pago</div>
                                                        <h5 class="mb-0">R$ <?= number_format($total_not_paid, 2,",",".") ?></h5>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <div class="top d-flex flex-wrap">
                                                        <div class="action-filters flex-grow-1">
                                                            <div class="dataTables_filter mt-1"> 
                                                                <h5 >Custos do processo</h5>
                                                            </div>
                                                        </div>
                                                        <div class="actions action-btns d-flex align-items-center">
                                                            <div class="dropdown invoice-filter-action">
                                                                <button type="button" class="btn btn-primary shadow mr-1" id="btn_modal_add_cost">Adicionar custo</button>
                                                            </div>
                                                            <div class="dropdown invoice-options">
                                                                <button type="button" class="btn btn-success shadow mr-0 " id="btn_modal_export">Exportar dados</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="dataTables_wrapper">
                                                        <table class="table" id="table_cost">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting">Código#</th>
                                                                    <th class="sorting">Lançamento</th>
                                                                    <th class="sorting">Tipo</th>
                                                                    <th class="sorting">Descrição</th>
                                                                    <th class="sorting">Total</th>
                                                                    <th class="sorting">Vencimento</th>
                                                                    <th class="sorting">Status</th>
                                                                    <th>Ação</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($process_cost->count() > 0)
                                                                    @foreach ($process_cost as $cost)
                                                                        <tr role="row">
                                                                            <td><a href="#">{{ $cost->code }}</a></td>
                                                                            <td><?= date('d/m/Y', strtotime($cost->date_release)) ?></td>
                                                                            <td><span data-toggle="tooltip" data-placement="left" title="" data-original-title="<?= strWordCut($cost->juridical_type_cost->description, 200) ?>" style="cursor: pointer;"><?= strWordCut($cost->juridical_type_cost->description, 20) ?></span></td>
                                                                            <td><span data-toggle="tooltip" data-placement="left" title="" data-original-title="<?= strWordCut($cost->description, 200) ?>" style="cursor: pointer;"><?= strWordCut($cost->description, 15) ?></span></td>
                                                                            <td>
                                                                                @if($cost->total != 0)
                                                                                    R$ <?= number_format($cost->total, 2,",",".") ?>
                                                                                @else
                                                                                    <span class="badge badge-warning badge-pill badge-cost" style="padding: 0.5rem; font-size: 0.6rem; line-height: 0.6;">Sem comprovante</span>
                                                                                @endif
                                                                            </td>
                                                                            <td><?= date('d/m/Y', strtotime($cost->date_expiration)) ?></td>
                                                                            <td>
                                                                                @if($cost->is_paid == 0)
                                                                                    <span class="badge badge-light-danger badge-pill badge-cost span-status-paid" data-payment-cost-id="<?= $cost->id ?>">Não pago</span>
                                                                                @elseif($cost->is_paid == 1)
                                                                                    <span class="badge badge-light-success badge-pill badge-cost">Pago</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <div class="dropleft">
                                                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        @if($cost->is_paid == 0)
                                                                                            <a class="dropdown-item edit-cost" href="javascript:void(0)" data-cost-id="<?= $cost->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                                                        @endif    
                                                                                        @if($cost->total != 0)
                                                                                            <a class="dropdown-item" onclick='viewCostAttach(<?= $cost->id ?>)' href='javascript:void(0)'><i class="bx bx-copy-alt mr-1"></i> Notas fiscais</a>
                                                                                        @endif    
                                                                                        @if($cost->is_paid == 0 && $cost->total != 0)
                                                                                            <a class="dropdown-item add-payment" href='javascript:void(0)' data-cost-id="<?= $cost->id ?>"><i class="bx bx-receipt mr-1"></i> Comprovante de pagamento</a>
                                                                                        @endif    
                                                                                        @if($cost->is_paid == 1)
                                                                                            <a class="dropdown-item" href='<?= $cost->receipt ?>' target="_blank"><i class="bx bx-receipt mr-1"></i> Comprovante de pagamento</a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>    
                                                                    @endforeach    
                                                                @else    
                                                                <tr role="row">
                                                                    <td colspan="8">Não há custos cadastrados!</td>
                                                                </tr>    
                                                                @endif 
                                                            </tbody>
                                                        </table>                 
                                                        <nav>
                                                            <ul class="pagination justify-content-end">
                                                                <?= $process_cost->appends(['tabName' => 'cost'])->links(); ?>
                                                            </ul>
                                                        </nav>
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
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal_add_cost" tabindex="-1" role="dialog" aria-labelledby="modal_historic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title modal-title-cost">Adicionar Custo</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body">
                <form action="/juridical/process/cost_do" method="POST" id="register_cost">
                    <input type="hidden" name="process_id" value="{{$process_id}}">
                    <input type="hidden" name="cost_id" id="cost_id" value="0">
                    <input type="hidden" name="arr_documents_cost" id="arr_documents_cost">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="price">data de lançamento</label>
                                <input type="text"class="form-control date-mask" id="cost_date_release" name="cost_date_release" placeholder="__/__/____">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="serie">Tipo de custo</label>
                                <select class="form-control" id="type_cost" name="type_cost">
                                    <option value="" selected disabled>Selecione</option>
                                    @foreach ($type_cost as $key)
                                        <option value="{{$key->id}}">{{$key->description}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="price">Descrição do custo</label>
                                <textarea class="form-control" id="description_cost" name="description_cost" rows="3"></textarea>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="price">data de vencimento</label>
                                <input type="text"class="form-control date-mask" id="cost_date_expiration" name="cost_date_expiration" placeholder="__/__/____">
                            </fieldset>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" style="margin-bottom:20px;">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-outline-primary block" data-toggle="modal" data-target="#modal_attach_cost">Adicionar comprovante fiscal</button>
                                </div>    
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Arquivo</th>
                                            <th>Valor</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cost_table_attach"></tbody>    
                                </table>    
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
                <button type="button" class="btn btn-primary ml-1" id="btn_add_cost">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block span-add-cost">Adicionar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_attach_cost" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Adicionar comprovante fiscal</span>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="cost_attach_upload_form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="serie">Descrição</label>
                                    <input type="text" class="form-control" name="cost_attach_description" id="cost_attach_description" placeholder="Informe a descrição do comprovante">
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="price">Valor do comprovante</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">R$</span>
                                        </div>
                                        <input type="text" class="form-control money" id="cost_attach_value" name="cost_attach_value" placeholder="0,00">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="price">Arquivo</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="cost_attach_file" id="cost_attach_file">
                                        <label class="custom-file-label">Escolher arquivo</label>
                                    </div>
                                </fieldset>
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
                <button type="button" class="btn btn-primary ml-1" id="btn_cost_attach">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Adicionar</span>
                </button>
            </div>
        </div>    
    </div> 
</div>   

<div class="modal fade" id="modal_historic" tabindex="-1" role="dialog" aria-labelledby="modal_historic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title modal-title-historic">Novo Andamento</span>
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>-->
            </div>
            <div class="modal-body">
                <form action="/juridical/process/info/historic_do" method="POST" id="register_historic">
                    <input type="hidden" name="process_id" value="{{$process_id}}">
                    <input type="hidden" name="historic_id" id="historic_id" value="0">
                    <input type="hidden" name="arr_documents" id="arr_documents">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="serie">Título</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="informe o título">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="price">Descrição</label>
                                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="price">data de publicação</label>
                                <input type="text"class="form-control date-mask" id="date_publication" name="date_publication" placeholder="__/__/____">
                            </fieldset>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" style="margin-bottom:20px;">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-outline-primary block" data-toggle="modal" data-target="#modal_document">Adicionar arquivos</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Arquivo</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_documents"></tbody>    
                                </table>    
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
                <button type="button" class="btn btn-primary ml-1" id="btn_add_historic">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block span-add-historic">Adicionar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_document" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Adicionar documento</span>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="register_document">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="serie">Descrição</label>
                                    <select class="form-control" id="type_document_id" name="type_document_id">
                                        <option value="" selected disabled>Selecione</option>
                                        @foreach ($type_documents as $key)
                                            <option value="{{$key->id}}">{{$key->description}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" class="form-control" name="description_document" id="description_document" placeholder="Informe a descrição do arquivo" style="display:none;">
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="price">Arquivo</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file_document" id="file_document">
                                        <label class="custom-file-label">Escolher arquivo</label>
                                    </div>
                                </fieldset>
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
                <button type="button" class="btn btn-primary ml-1" id="btn-upload">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Adicionar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>     

<div class="modal fade" id="modal_measures" tabindex="-1" role="dialog" aria-labelledby="modal_measures" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Ementa / Pleitos</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><?= nl2br($process->measures_plea) ?></p>
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

<div class="modal fade" id="modal_update_info" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Confirmar Atualização</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <p>Deseja confirmar atualização do status?</p>
                        </div>
                    </div>    
                </div>
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_update_info">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Confirmar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>     

<div id="modal_receipt" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Comprovantes de custo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-modal-contract" data-rt-breakpoint="600">
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Comprovante</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody id="table_receipt"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_attach_payment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Comprovante de Pagamento</span>
            </div>
            <div class="modal-body">
                <form action="/juridical/process/payment/attach" method="POST" id="payment_attach_upload_form" enctype="multipart/form-data">
                    <input type="hidden" id="payment_cost_id" name="payment_cost_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="price">Anexar comprovante</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="payment_attach_file" id="payment_attach_file">
                                        <label class="custom-file-label label-attach-file">Escolher arquivo</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="serie">Descrição</label>
                                    <textarea class="form-control" id="payment_description" name="payment_description" rows="4" placeholder="Informe uma descrição adicional sobre o pagamento"></textarea>
                                </fieldset>
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
                <button type="button" class="btn btn-primary ml-1" id="btn_attach_payment">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Adicionar</span>
                </button>
            </div>
        </div>    
    </div> 
</div>

<div class="modal fade" id="modal_export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Exportar Custos Processos</span>
            </div>
            <form action="/juridical/process/cost/list" id="form_modal_export">
                <input type="hidden" name="process_id" id="export_process_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Código custo</label>
                                <input type="text" class="form-control" id="code_cost" name="code_cost" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="price">data de lançamento</label>
                                <input type="text"class="form-control date-mask" id="cost_date_release" name="cost_date_release" placeholder="__/__/____">
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="serie">Tipo de custo</label>
                                <select class="form-control" id="type_cost" name="type_cost">
                                    <option value="" selected disabled>Selecione</option>
                                    @foreach ($type_cost as $key)
                                        <option value="{{$key->id}}">{{$key->description}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="price">data de vencimento</label>
                                <input type="text"class="form-control date-mask" id="cost_date_expiration" name="cost_date_expiration" placeholder="__/__/____">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">STATUS</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="1">Pago</option>
                                    <option value="not_paid">Não Pago</option>
                                </select>
                            </fieldset>
                        </div>
                        <input type="hidden" name="export" value="1">
                    </div>                    
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1" id="btn_export_process" >
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Exportar</span>
                    </button>
                </div>
            </form>
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_notification" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="/juridical/process/info/historic/notification" id="form_history_notification">
                <input type="hidden" name="notify_history_id" id="notify_history_id">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="myModalLabel160">Notificação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-error"></i>
                                    <span>Será enviado dois emails de notifição em relação a data escolhida, um email três dias antes e outro na data em questão.</span>
                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="price">data de notificação</label>
                                <input type="text"class="form-control date-format date-mask" id="date_notification" name="date_notification" placeholder="__/__/____">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="serie">Descrição</label>
                                <textarea class="form-control" id="description_notification" name="description_notification" rows="4" placeholder="Está descrição será incorporada ao email de notificação"></textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1" id="btn_send_notification" >
                        <span class="d-none d-sm-block actiontxt">Notificar</span>
                    </button>
                </div>
            </form>    
        </div>
    </div>
</div>

<script>
    var arr_documents = [];
    var arr_documents_cost = [];
    var process_id = {!! $process_id !!};
    
    $(document).ready(function () {

        var documents =  localStorage.getItem("arr_documents"),
            documents_cost = localStorage.getItem("arr_documents_cost");

        if (documents != null) {
            var arr = JSON.parse(documents);
            if(arr.length > 0) {
                $('#table_documents').html(reloadDocuments(arr, 1));
            }

        }  
        if (documents_cost != null) {
            var arr_cost = JSON.parse(documents_cost);
            if(arr_cost.length > 0) {
                $('#cost_table_attach').html(reloadAttachCost(arr_cost, 1));
            }
        }  

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

        $('.date-mask').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'dd/mm/yyyy',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        });

        $("#btn-upload").click(function () {

            var description = $("#description_document").val(),
                id_type_document = $("#type_document_id").val();

            if(description == '') {

                return $error('Informe a descrição do documento!');

            } else if($("#file_document")[0].files.length == 0) {

                return $error('Selecione um arquivo!');
            }

            $("#modal_document").modal('hide');
            block();
            ajaxSend('/juridical/process/info/document/ajax', $("#register_document").serialize(), 'POST', '60000', $("#register_document")).then(function(result){

                if(result.success) {

                    obj_documents = {
                        'id': 0,
                        'historic_id': $("#historic_id").val(),
                        'url': result.url,
                        'description': description,
                        'id_type_document': id_type_document
                    };

                    arr_documents.push(obj_documents);
                    localStorage.setItem("arr_documents", JSON.stringify(arr_documents));

                    $('#table_documents').html(reloadDocuments(arr_documents, 1));

                    $("#description_document, #file_document, #type_document_id").val('');
                    $(".custom-file-label").text('Escolher arquivo');

                    unblock();
                } else {
                    return $error(result.message);
                }
            }).catch(function(err){
                unblock();
                $error(err.message)
            });
        }); 

        $("#btn_add_historic").click(function () {

            if($("#title").val() == "") {
                return $error('Título obrigatório!');
            } 
            else if($("#description").val() == "") {
                return $error('Descrição obrigatória!');
            } 
            else if($("#date_publication").val() == "") {
                return $error('Data de publicação obrigatória!');
            }

            $("#arr_documents").val(localStorage.getItem("arr_documents"));
            localStorage.removeItem('arr_documents');
            $('#register_historic').submit();
        });

        $("#btn_cost_attach").click(function () {

            var cost_attach_description = $("#cost_attach_description").val(),
                cost_attach_value = $("#cost_attach_value").val();

            if(cost_attach_description == '') {

                return $error('Informe a descrição do comprovante!');

            } else if(cost_attach_value == '') {

                return $error('Informe o valor do comprovante!');
            
            } else if($("#cost_attach_file")[0].files.length == 0) {

                return $error('Selecione um arquivo!');
            }

            $("#modal_attach_cost").modal('hide');
            block();
            ajaxSend('/juridical/process/cost/attach/ajax', $("#cost_attach_upload_form").serialize(), 'POST', '60000', $("#cost_attach_upload_form")).then(function(result){

                if(result.success) {    

                    value = parseFloat(cost_attach_value.replace(".","").replace(",","."));

                    obj_documents = {
                        'id': 0,
                        'cost_id': $("#cost_id").val(),
                        'url': result.url,
                        'description': cost_attach_description,
                        'value' : value
                    };

                    var documents =  localStorage.getItem("arr_documents_cost");

                    if (documents == null) {
                        arr_documents_cost.push(obj_documents);
                        localStorage.setItem("arr_documents_cost", JSON.stringify(arr_documents_cost));

                        $('#cost_table_attach').html(reloadAttachCost(arr_documents_cost, 1));
                    } else {

                        var obj = JSON.parse(documents);
                        obj.push(obj_documents);
                        localStorage.setItem("arr_documents_cost", JSON.stringify(obj));

                        $('#cost_table_attach').html(reloadAttachCost(obj, 1));
                    }

                    $("#cost_attach_description, #cost_attach_value, #cost_attach_file").val('');
                    $(".custom-file-label").text('Escolher arquivo');

                    unblock();
                } else {
                    return $error(result.message);
                }
            }).catch(function(err){
                unblock();
                $error(err.message)
            });
        });

        $("#btn_add_cost").click(function () {

            if($("#cost_date_release").val() == "") {
                return $error('Data de lançamento obrigatória!');
            } 
            else if($("#type_cost").val() == "") {
                return $error('Tipo de custo obrigatória!');
            } 
            else if($("#description_cost").val() == "") {
                return $error('Descrição do custo obrigatória!');
            }
            else if($("#cost_date_expiration").val() == "") {
                return $error('Data de vencimento obrigatória!');
            }

            $("#arr_documents_cost").val(localStorage.getItem("arr_documents_cost"));
            localStorage.removeItem('arr_documents_cost');
            arr_documents_cost = [];
            $('#register_cost').submit();
        });

        $("#btn_modal_update_info").click(function(){
            $("#modal_update_info").modal('show');
        });

        $("#btn_update_info").click(function () {
            block();
            ajaxSend('/juridical/process/info/update/status/ajax', {status: $("#process_status").val(), date_finished: $("#date_finished").val(), process_id: process_id}, 'POST', 3000).then(function(result) {
                $("#modal_update_info").modal('hide');
                unblock();
                $success(result.message); 
            }).catch(function(err){

                $("#modal_update_info").modal('hide');
                unblock();
                $error(err.message);
            });
        });

        $("#type_document_id").change(function () {
            $("#description_document").val($("#type_document_id option:selected").text());
        });

        $(".icon-edit").click(function () {

            localStorage.removeItem('arr_documents');
            arr_documents = [];

            var historic_id = $(this).attr('data-icon-id');
            $("#historic_id").val(historic_id);

            $(".modal-title-historic").text('Atualizar Andamento');
            $(".span-add-historic").text('Atualizar');

            block();
            ajaxSend('/juridical/process/info/historic/ajax', {historic_id: historic_id, process_id: process_id}, 'GET', 3000).then(function(result) {

                if(result.success) {

                    $("#title").val(result.historic.title);
                    $("#description").val(result.historic.description);
                    $("#date_publication").val(result.date_publication);

                    var documents = result.historic.juridical_process_documents;

                    if(documents.lenght != 0) {

                        for (var i = 0; i < documents.length; i++) {
                            var obj = documents[i];

                            obj_documents = {
                                'id': obj.id,
                                'historic_id': result.historic.id,
                                'description': obj.juridical_type_document.description,
                                'id_type_document' : obj.juridical_type_document_id,
                                'url': obj.url
                            };

                            arr_documents.push(obj_documents);
                            localStorage.setItem("arr_documents", JSON.stringify(arr_documents));
                        }    
                        $('#table_documents').html(reloadDocuments(arr_documents, 2));
                    }
                    
                    $("#modal_historic").modal('show');
                    unblock();
                }
            }).catch(function(err){

                $("#modal_historic").modal('hide');
                unblock();
                $error(err.message);
            });

            $("#modal_historic").modal('show');
        });

        $(".edit-cost").click(function () {

            localStorage.removeItem('arr_documents_cost');
            arr_documents_cost = [];

            var cost_id = $(this).attr('data-cost-id');
            $("#cost_id").val(cost_id);

            $(".modal-title-cost").text("Atualizar Custo");
            $(".span-add-cost").text("Atualizar");

            block();
            ajaxSend('/juridical/process/info/cost/ajax', {cost_id: cost_id, process_id: process_id}, 'GET', 3000).then(function(result) {

                if(result.success) {

                    $("#cost_date_release").val(result.date_release);
                    $("#type_cost").val(result.cost.type_id);
                    $("#description_cost").val(result.cost.description);
                    $("#cost_date_expiration").val(result.date_expiration);

                    var documents = result.cost.juridical_process_cost_attach;

                    console.log(documents);

                    if(documents.lenght != 0) {

                        for (var i = 0; i < documents.length; i++) {
                            var obj = documents[i];

                            obj_documents = {
                                'id': obj.id,
                                'cost_id': obj.juridical_process_cost_id,
                                'url': obj.url,
                                'description': obj.description,
                                'value' : obj.value
                            };

                            arr_documents_cost.push(obj_documents);
                            localStorage.setItem("arr_documents_cost", JSON.stringify(arr_documents_cost));
                        }    
                        $('#cost_table_attach').html(reloadAttachCost(arr_documents_cost, 2));
                    }
                    
                    $("#modal_add_cost").modal('show');
                    unblock();
                }
            }).catch(function(err){

                $("#modal_add_cost").modal('hide');
                unblock();
                $error(err.message);
            });
        });

        $(".add-payment").click(function () {

            var cost_id = $(this).attr('data-cost-id');
            $("#payment_cost_id").val(cost_id);
            $("#modal_attach_payment").modal('show');
        });

        $("#btn_attach_payment").click(function (e) {

            if ($("#payment_attach_file")[0].files.length == 0) {

                e.preventDefault();
                return $error('Necessário adicionar comprovante!');
            } else {

                e.preventDefault();
                Swal.fire({
                    title: 'Confirmar pagamento',
                    text: "Deseja confirmar e anexar comprovante de pagamento!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        $("#payment_attach_upload_form").unbind().submit();
                        block();
                    }
                });
            }
        });    

        $("#btn_modal_export").click(function (e) {
            $("#modal_export").modal('show');
            $("#export_process_id").val(process_id);
        });

        $("#btn_export_process").click(function (e) {
            $("#modal_export").modal('toggle');
            $("#form_modal_export").submit();
        });

        $("#btn_modal_historic").click(function() {

            localStorage.removeItem('arr_documents');
            arr_documents = [];

            $("#title, #description, #date_publication").val('');
            $("#historic_id").val(0);
            $(".modal-title-historic").text("Novo Andamento");
            $(".span-add-historic").text("Adicionar");
            $('#table_documents').html('');
            $("#modal_historic").modal('show');
        });

        $("#btn_modal_add_cost").click(function() {

            localStorage.removeItem('arr_documents_cost');
            arr_documents_cost = [];

            $("#cost_date_release, #type_cost, #description_cost, #cost_date_expiration").val('');
            $("#cost_id").val(0);
            $(".modal-title-cost").text('Novo custo');
            $(".span-add-cost").text('Adicionar');
            $('#cost_table_attach').html('');
            $("#modal_add_cost").modal('show');
        });

        $(".add-notification").click(function() {

            var history_id = $(this).attr('data-history-id');
            $("#notify_history_id").val(history_id);
            $("#modal_notification").modal('show');
        });

        $("#btn_send_notification").click(function(e) {

            if ($("#date_notification").val() == '') {
                e.preventDefault();
                return $error('Informe uma data para notificação!');
            } else {
                e.preventDefault();
                Swal.fire({
                    title: 'Confirmar notificação',
                    text: "Deseja confirmar o agendamento desta notificação!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        $("#form_history_notification").unbind().submit();
                        block();
                    }
                });
            }
        });

        $('.money').mask('000.000.000.000.000,00', {reverse: true});
        $('.date-format').mask('00/00/0000', {reverse: true});

        setInterval(() => {
            $("#mJuridical").addClass('sidebar-group-active active');
            $("#mJuridicalProcess").addClass('sidebar-group-active active');
        }, 100);
    });

    function reloadDocuments(object, type = null) {
        var html = '';
        for (var i = 0; i < object.length; i++) {
            var column = object[i];
			
			if(column.description != undefined) {
                var description = column.description.substring(0, 100);
            } else {
                var description = "";
            }										  

            html += '<tr>';
            html += '<td>'+ description +'</td>';
            html += '<td><a href="'+ column.url +'" target="_blank">ver arquivo</a></td>';
            html += "<td><a onclick='deleteTableDocument(this)' style='cursor: pointer;' data-id='"+ i +"' data-type='"+ type +"' data-document-id='"+ column.id +"' data-historic-id='"+ column.historic_id +"' class='btn-less'><i class='badge-circle badge-circle-light-secondary bx bx-trash font-medium-1'></i></a></td>";
            html += '</tr>';
        }
        return html;
    }

    function deleteTableDocument(el) {

        var index = $(el).attr('data-id'),
            type =  $(el).attr('data-type'),
            document_id = $(el).attr('data-document-id');
            historic_id = $(el).attr('data-historic-id');

        var documents =  localStorage.getItem("arr_documents");
        var obj = JSON.parse(documents);

        if(typeof obj[index] != 'undefined') {

            block();
            ajaxSend('/juridical/process/info/document/delete/ajax', {url: obj[index]['url'], type: type, document_id: document_id, historic_id: historic_id}, 'POST', '60000', '').then(function(result){

                if(!result.success) {
                    return $error(result.message);
                } else {

                    obj.splice(index, 1);
                    arr_documents.splice(index, 1);
                    localStorage.setItem("arr_documents", JSON.stringify(obj));
                    $(el).parent().parent().remove();
                    $success(result.message); 
                }
                unblock();
            }).catch(function(err){
                unblock();
                $error(err.message)
            });
        }
        else {
            return $error('Documento não existe!');
        }
    }

    function deleteCostTable(el) {

        var index = $(el).attr('data-id'),
            type =  $(el).attr('data-type'),
            attach_id = $(el).attr('data-attach-id');
            cost_id = $(el).attr('data-cost-id');
        
        var documents =  localStorage.getItem("arr_documents_cost");
        var obj = JSON.parse(documents);

        if(typeof obj[index] != 'undefined') {

            block();
            ajaxSend('/juridical/process/cost/attach/delete/ajax', {url: obj[index]['url'], type: type, attach_id: attach_id, cost_id: cost_id}, 'POST', '60000', '').then(function(result){

                if(!result.success) {
                    return $error(result.message);
                } else {

                    obj.splice(index, 1);
                    arr_documents_cost.splice(index, 1);
                    localStorage.setItem("arr_documents_cost", JSON.stringify(obj));
                    $(el).parent().parent().remove();
                    $success(result.message); 
                }
                unblock();
            }).catch(function(err){
                unblock();
                $error(err.message)
            });
        }
        else {
            return $error('Documento não existe!');
        }
    }

    function viewCostAttach(cost_id) {

        block();
        ajaxSend('/juridical/process/cost/receipt/ajax', {cost_id: cost_id}, 'GET', '60000', '').then(function(result){

            if(!result.success) {
                return $error(result.message);
            } else {
                $('#table_receipt').html(reloadReceiptCost(result.arr_cost_attach));                
            }
            unblock();
        }).catch(function(err){
            unblock();
            $error(err.message)
        });

        $("#modal_receipt").modal();
    }

    function reloadReceiptCost(object) {
        var html = '';
        for (var i = 0; i < object.length; i++) {
            var column = object[i];
			
			if(column.description != undefined) {
                var description = column.description.substring(0, 80);
            } else {
                var description = "";
            }
										  
            html += '<tr>';
            html += '<td>'+ description +'</td>';
            html += '<td><a href="'+ column.url +'" target="_blank">ver arquivo</a></td>';
            html += '<td>'+ column.value.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"})+'</td>';
            html += '</tr>';
        }
        return html;
    }

    function reloadAttachCost(object, type = null) {
        var html = '';
        for (var i = 0; i < object.length; i++) {
            var column = object[i];
			
			if(column.description != undefined) {
                var description = column.description.substring(0, 100);
            } else {
                var description = "";
            }

            html += '<tr>';
            html += '<td>'+ description +'</td>';
            html += '<td><a href="'+ column.url +'" target="_blank">ver arquivo</a></td>';
            html += '<td>'+ column.value.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}) +'</td>';
            html += "<td><a onclick='deleteCostTable(this)' style='cursor: pointer;' data-id='"+ i +"' data-type='"+ type +"' data-attach-id='"+ column.id +"' data-cost-id='"+ column.cost_id +"' class='btn-less'><i class='badge-circle badge-circle-light-secondary bx bx-trash font-medium-1'></i></a></td>";
            html += '</tr>';
        }
        return html;
    }

</script>

@endsection