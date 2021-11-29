@extends('gree_i.layout')

@section('content')

<style>
    .badge-cost{
        padding: 0.5rem; 
        font-size: 0.6rem; 
        line-height: 0.6;
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
                    <div class="breadcrumb-wrapper col-12">Escritórios de advocacia</div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header row"></div>
    <div class="content-body">
        <section class="page-user-profile">
            <div class="row">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-content div-cost-process">
                            <div class="card-body py-1">
                                <div class="text-muted" style="font-size: 17px;">Honorários</div>
                                <h5 class="mb-0">R$ <?= number_format($honorarium, 2,",",".") ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-content div-cost-process">
                            <div class="card-body py-1">
                                <div class="text-muted line-ellipsis" style="font-size: 17px;">Despesas</div>
                                <h5 class="mb-0">R$ <?= number_format($expenses, 2,",",".") ?></h5>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">  
                                    <div class="top d-flex flex-wrap">
                                        <div class="action-filters flex-grow-1">
                                            <div class="dataTables_filter mt-1"> 
                                                <h5>Honorários e Despesas - <small>{{$month_year_actual}}</small></h5>
                                                <span>{{$law_firm->name}} ({{$law_firm->identity}})</span>
                                            </div>
                                        </div>
                                        <div class="actions action-btns d-flex align-items-center">
                                            <!--<button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_add_cost">Adicionar custo</button>-->
                                            <button type="button" class="btn btn-primary shadow mr-1" id="btn_modal_add_cost">Adicionar custo</button>
                                            <button type="button" class="btn btn-secondary shadow mr-1" data-toggle="modal" data-target="#modal_filter">Filtrar custos</button>
                                            <!--<button type="button" class="btn btn-success shadow mr-0">Exportar dados</button>-->
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="dataTables_wrapper">
                                        <table class="table">
                                            <thead>
                                                <tr role="row">
                                                    <th class="sorting">Código#</th>
                                                    <th class="sorting">Lançamento</th>
                                                    <th class="sorting">Tipo</th>
                                                    <th class="sorting">Detalhe</th>
                                                    <th class="sorting">Descrição</th>
                                                    <th class="sorting">Total</th>
                                                    <th class="sorting">Vencimento</th>
                                                    <th class="sorting">Status</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($law_cost->count() > 0)
                                                    @foreach ($law_cost as $cost)
                                                    <tr role="row">
                                                        <td><a href="#">{{ $cost->code }}</a></td>
                                                        <td><?= date('d/m/Y', strtotime($cost->date_release)) ?></td>
                                                        <td>
                                                            @if($cost->type_cost == 1)
                                                                <span class="badge badge-primary badge-pill badge-cost">Honorário</span>
                                                            @elseif($cost->type_cost == 2)
                                                                <span class="badge badge-warning badge-pill badge-cost">Despesa</span>
                                                            @endif
                                                        </td>
                                                        <td><span data-toggle="tooltip" data-placement="left" title="" data-original-title="<?= stringCut($cost->juridical_type_cost->description, 200) ?>" style="cursor: pointer;"><?= stringCut($cost->juridical_type_cost->description, 8) ?></span></td>
                                                        <td><span data-toggle="tooltip" data-placement="left" title="" data-original-title="<?= stringCut($cost->description, 200) ?>" style="cursor: pointer;"><?= stringCut($cost->description, 10) ?></span></td>
                                                        <td><?= number_format($cost->total, 2,",",".") ?></td>
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
                                                                        <a class="dropdown-item edit-law-cost" href="javascript:void(0)" law-cost-id="<?= $cost->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                                    @endif    
                                                                    @if($cost->total != 0)
                                                                        <a class="dropdown-item" onclick='viewCostAttach(<?= $cost->id ?>)' href='javascript:void(0)'><i class="bx bx-copy-alt mr-1"></i> Notas fiscais / faturamentos</a>
                                                                    @endif    
                                                                    @if($cost->is_paid == 0)
                                                                        <a class="dropdown-item add-receipt-payment" href='javascript:void(0)' data-cost-id="<?= $cost->id ?>"><i class="bx bx-receipt mr-1"></i> Comprovante de pagamento</a>
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
                                                        <td colspan="9" style="text-align: center;">Não há lançamentos cadastrados!</td>
                                                    </tr>    
                                                @endif
                                            </tbody>
                                        </table>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination justify-content-end">
                                                <?= $law_cost->appends(getSessionFilters('lawcost_')[0]->toArray())->links(); ?>
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

<div class="modal fade" id="modal_add_cost" tabindex="-1" role="dialog" aria-labelledby="modal_historic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title modal-title-cost">Adicionar Honorário / Despesa</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item current">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="true">
                            <span class="align-middle">Informações</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="false">
                            <span class="align-middle">Comprovantes</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="home" aria-labelledby="home-tab" role="tabpanel">
                        <form action="/juridical/law/firm/cost/edit_do" method="POST" id="register_cost">
                            <input type="hidden" name="law_firm_id" value="{{ $law_firm_id }}">
                            <input type="hidden" name="id" id="law_cost_id" value="0">
                            <input type="hidden" name="arr_documents_law" id="arr_documents_law">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label for="price">Custo</label>
                                        <select class="form-control" id="type_cost" name="type_cost">
                                            <option value="1">Honorários</option>
                                            <option value="2">Despesas</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label for="price">data de lançamento</label>
                                        <input type="text"class="form-control date-mask" id="cost_date_release" name="cost_date_release" placeholder="__/__/____">
                                    </fieldset>
                                </div>
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label for="serie">Tipo</label>
                                        <select class="form-control" id="type_id" name="type_id">
                                            <option value="" selected disabled>Selecione</option>
                                            @foreach ($type_cost as $key)
                                                <option value="{{$key->id}}">{{$key->description}}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label for="price">Descrição</label>
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
                        </form>
                    </div>
                    <div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" style="margin-bottom:20px;">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-outline-primary block" data-toggle="modal" data-target="#modal_attach_cost">Adicionar comprovante</button>
                                    </div>    
                                </div>
                                <div class="table-responsive">
                                    <table class="table" id="table_cost">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Descrição</th>
                                                <th>Arquivo</th>
                                                <th>Valor</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cost_table_attach">
                                            
                                        </tbody>    
                                    </table>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

<div class="modal fade text-left" id="modal_type" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Cadastrar Novo Tipo</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <form id="modal_form_type" method="POST" action="/juridical/type/cost/edit_do">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="id" name="id">
                        <div class="col-sm-12">
                            <label>Descrição</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" name="type_description" id="type_description" placeholder="Informe nome ação">
                            </fieldset>
                            <input type="hidden" id="type_status" name="type_status" value="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('news_i.lt_06') }}</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" id="btn_save_type">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Salvar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-secondary white">
                <span class="modal-title">Filtrar Honorários e Despesas</span>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="form_modal_filter">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="price">Mês / Ano</label>
                                <input type="text"class="form-control date_month_year" id="f_month_year" name="f_month_year" placeholder="00/0000">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Tipo</label>
                                <select class="form-control" id="f_cost_type" name="f_cost_type">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="1">Honorário</option>
                                    <option value="2">Despesa</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="serie">Detalhe</label>
                                <select class="form-control sel2-type" id="f_type_detail" name="f_type_detail" multiple style="width: 100%"></select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="filter_status" name="f_status">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="1">Pago</option>
                                    <option value="opt_0">Não pago</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </form>    
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-secondary ml-1" id="btn_filter_cost">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Filtrar</span>
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

<div class="modal fade" id="modal_attach_payment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Comprovante de Pagamento</span>
            </div>
            <div class="modal-body">
                <form action="/juridical/law/firm/payment/attach" method="POST" id="payment_attach_upload_form" enctype="multipart/form-data">
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

<div id="modal_receipt" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Notas fiscais / Faturamentos</h5>
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

<script>

    var arr_documents_law = [];
    var documents_law = localStorage.getItem("arr_documents_law");

    if (documents_law != null) {
        var arr_law = JSON.parse(documents_law);
        if(arr_law.length > 0) {
            $('#cost_table_attach').html(reloadAttachCost(arr_law));
        }
    }

    $(document).ready(function () {

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

        $('.date_month_year').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'mmmm-yyyy',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            selectYears: true,
            selectMonths: true,
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        });

        $(".sel2-type").select2({
            placeholder: "Selecione",
            language: {
                noResults: function () {
                    return $('<button type="submit" style="width: 100%" class="btn btn-primary" data-toggle="modal" data-target="#modal_type">Novo Tipo Honorário</button>');
                }
            },
            ajax: {
                url: '/juridical/law/firm/type/cost/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#btn_save_type").click(function() {

            block();
            ajaxSend('/juridical/type/cost/edit/ajax', {description: $("#type_description").val(), status: $("#type_status").val()}, 'POST', 3000).then(function(result) {
                $("#modal_type").modal('hide');
                unblock();
                $success(result.message); 
            }).catch(function(err){

                $("#modal_type").modal('hide');
                unblock();
                $error(err.message);
            });
            
        });

        $("#btn_filter_cost").click(function (e) {
            $("#modal_filter").modal('toggle');
            block();
            $("#form_modal_filter").submit();
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
                        'cost_id': $("#law_cost_id").val(),
                        'url': result.url,
                        'description': cost_attach_description,
                        'value' : value
                    };

                    var documents =  localStorage.getItem("arr_documents_law");

                    if (documents == null) {
                        arr_documents_law.push(obj_documents);
                        localStorage.setItem("arr_documents_law", JSON.stringify(arr_documents_law));

                        $('#cost_table_attach').html(reloadAttachCost(arr_documents_law));
                    } else {

                        var obj = JSON.parse(documents);
                        obj.push(obj_documents);
                        localStorage.setItem("arr_documents_law", JSON.stringify(obj));

                        $('#cost_table_attach').html(reloadAttachCost(obj));
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

        $(".edit-law-cost").click(function () {

            localStorage.removeItem('arr_documents_law');
            arr_documents_cost = [];

            var law_cost_id = $(this).attr('law-cost-id');
            $("#law_cost_id").val(law_cost_id);

            $(".modal-title-cost").text("Atualizar Honorário / Despesa");
            $(".span-add-cost").text("Atualizar");

            ajaxSend('/juridical/law/firm/cost/info/ajax', {law_cost_id: law_cost_id}, 'GET', 3000).then(function(result) {

                if(result.success) {

                    $("#type_cost").val(result.cost.type_cost);
                    $("#cost_date_release").val(result.date_release);
                    $("#description_cost").val(result.cost.description);
                    $("#cost_date_expiration").val(result.date_expiration); 
                    $("#type_id").val(result.cost.type_id);

                    var documents = result.cost.juridical_law_firm_cost_attach;

                    if(documents.lenght != 0) {

                        for (var i = 0; i < documents.length; i++) {
                            var obj = documents[i];

                            obj_documents = {
                                'id': obj.id,
                                'cost_id': obj.juridical_law_firm_cost_id,
                                'url': obj.url,
                                'description': obj.description,
                                'value' : obj.value
                            };

                            arr_documents_law.push(obj_documents);
                            localStorage.setItem("arr_documents_law", JSON.stringify(arr_documents_law));
                        }    
                        $('#cost_table_attach').html(reloadAttachCost(arr_documents_law, 2));
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

            $("#arr_documents_law").val(localStorage.getItem("arr_documents_law"));
            localStorage.removeItem('arr_documents_law');
            $('#register_cost').submit();
        });

        $(".add-receipt-payment").click(function () {

            var cost_id = $(this).attr('data-cost-id');
            $("#payment_cost_id").val(cost_id);
            $("#modal_attach_payment").modal('show');
        });

        $("#btn_attach_payment").click(function (e) {

            if($("#payment_attach_file")[0].files.length == 0) {

                e.preventDefault();
                return $error('Necessário adicionar comprovante!');
            } else {

                e.preventDefault();
                Swal.fire({
                    title: 'Comfirmar pagamento',
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

        $("#btn_modal_add_cost").click(function() {

            localStorage.removeItem('arr_documents_law');
            arr_documents_law = [];

            $("#cost_date_release, #type_cost, #description_cost, #cost_date_expiration, #cost_date_release").val('');
            $("#law_cost_id").val(0);
            $(".modal-title-cost").text('Adicionar Honorário / Despesa');
            $(".span-add-cost").text('Adicionar');
            $('#cost_table_attach').html('');
            $("#modal_add_cost").modal('show');
        });

        $('.money').mask('000.000.000.000.000,00', {reverse: true});
        
        setInterval(() => {
            $("#mJuridical").addClass('sidebar-group-active active');
            $("#mJuridicalLawFirm").addClass('sidebar-group-active active');
        }, 100);
    });

    function deleteTableDocument(el) {

        var index = $(el).attr('data-id');

        var documents =  localStorage.getItem("arr_documents_law");
        var obj = JSON.parse(documents);

        if(typeof obj[index] != 'undefined') {

            block();
            ajaxSend('/juridical/process/info/document/delete/ajax', {url: obj[index]['url'], type: 1}, 'POST', '60000', '').then(function(result){

                if(!result.success) {
                    return $error(result.message);
                } else {

                    obj.splice(index, 1);
                    arr_documents_law.splice(index, 1);
                    localStorage.setItem("arr_documents_law", JSON.stringify(obj));
                    $(el).parent().parent().remove();
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

    function reloadAttachCost(object) {
        var html = '';
      
        for (var i = 0; i < object.length; i++) {
            var column = object[i];

            html += '<tr>';
            html += '<td>'+ column.description.substring(0, 100)+'...</td>';
            html += '<td><a href="'+ column.url +'" target="_blank">ver arquivo</a></td>';
            html += '<td>'+ column.value.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}) +'</td>';
            html += "<td><a onclick='deleteTableDocument(this)' style='cursor: pointer;' data-id='"+ i +"' class='btn-less'><i class='badge-circle badge-circle-light-secondary bx bx-trash font-medium-1'></i></a></td>";
            html += '</tr>';
        }
        return html;
    }

    function viewCostAttach(cost_id) {

        block();
        $('#table_receipt').html('');
        ajaxSend('/juridical/law/firm/cost/receipt/ajax', {cost_id: cost_id}, 'GET', '60000', '').then(function(result){

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

        if(object.length != 0) {
            for (var i = 0; i < object.length; i++) {
                var column = object[i];

                html += '<tr>';
                html += '<td>'+ column.description.substring(0, 80)+'</td>';
                html += '<td><a href="'+ column.url +'" target="_blank">ver arquivo</a></td>';
                html += '<td>'+ column.value.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"})+'</td>';
                html += '</tr>';
            }
        }
        else {
            html += '<tr>';
            html += '<td colspan="3">Não há comprovantes anexados!</td>';
            html += '</tr>';    
        }
        return html;
    }
</script>    

@endsection