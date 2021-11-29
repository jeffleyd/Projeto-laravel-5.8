@extends('gree_i.layout')

@section('content')

<style>
    .badge-cost{
        padding: 0.5rem; 
        font-size: 0.6rem; 
        line-height: 0.6;
    }
    .table th, .table td {
        padding: 1.15rem 1rem;
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
              <div class="breadcrumb-wrapper col-12">
                Escritórios
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="top d-flex flex-wrap">
                                    <div class="action-filters flex-grow-1">
                                        <div class="dataTables_filter mt-1">
                                            <h5 >Custos de escritórios</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                        </div>
                                        <div class="dropdown invoice-options">
                                            <button type="button" class="btn btn-success shadow mr-0" data-toggle="modal" data-target="#modal_export">Exportar dados</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting">Código#</th>
                                            <th class="sorting">Escritório</th>
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
                                                <td><a data-toggle="tooltip" data-placement="left" data-original-title="<?= $cost->juridical_law_firm->name ?>" href="/juridical/law/firm/cost/<?= $cost->juridical_law_firm->id ?>"><?= stringCut($cost->juridical_law_firm->name, 20) ?></a></td>
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
                                                <td>R$ <?= number_format($cost->total, 2,",",".") ?></td>
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
                                                            <a class="dropdown-item" onclick='viewCostAttach(<?= $cost->id ?>)' href='javascript:void(0)'><i class="bx bx-copy-alt mr-1"></i> Notas fiscais</a>
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
        </section>
    </div>
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar Honorários e Despesas</span>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="form_modal_filter">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Código</label>
                                <input type="text" class="form-control" id="code_cost" name="code_cost" placeholder="">
                            </div>
                        </div>
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
                                <select class="form-control" id="f_type_detail" name="f_type_detail">
                                    <option value="" selected disabled>Selecione</option>
                                    @foreach ($type_cost as $key)
                                        <option value="{{$key->id}}">{{$key->description}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="filter_status" name="f_status">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="1">Pago</option>
                                    <option value="not_paid">Não pago</option>
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
                <button type="button" class="btn btn-primary ml-1" id="btn_filter_cost">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>


<div class="modal fade" id="modal_export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Exportar Processos</span>
            </div>
            <form action="{{Request::url()}}" id="form_modal_export">
                <div class="modal-body">             
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Código</label>
                                <input type="text" class="form-control" id="code_cost" name="code_cost" placeholder="">
                            </div>
                        </div>
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
                                <select class="form-control" id="f_type_detail" name="f_type_detail">
                                    <option value="" selected disabled>Selecione</option>
                                    @foreach ($type_cost as $key)
                                        <option value="{{$key->id}}">{{$key->description}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="filter_status" name="f_status">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="1">Pago</option>
                                    <option value="not_paid">Não pago</option>
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

<script>
    $(document).ready(function () {

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

        $("#btn_filter_cost").click(function (e) {
            $("#modal_filter").modal('toggle');
            block();
            $("#form_modal_filter").submit();
        });

        $("#btn_export_process").click(function (e) {
            $("#modal_export").modal('toggle');
            $("#form_modal_export").submit();
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

        setInterval(() => {
            $("#mJuridical").addClass('sidebar-group-active active');
            $("#mJuridicalLawFirm").addClass('sidebar-group-active active');
            $("#mJuridicalLawFirmCost").addClass('active');
        }, 100);
    });

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