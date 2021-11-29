@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li class="active">Pedidos faturados</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
    <link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <header id="header-sec">
        <div class="inner-padding">
            <div class="pull-left">
                <div class="btn-group">
                    <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#filterModal">
                        <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                    </a>
                    <a class="btn btn-warning" href="#" data-toggle="modal" data-target="#exportModal">
                        <i class="fa fa-file" style="color: #ffffff;"></i>&nbsp; Exportar
                    </a>
                </div>
            </div>
        </div><!-- End .inner-padding -->
    </header>
<div class="window">
    <div class="inner-padding">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">
                            Pedido
                        </th>
                        <th scope="col">
                            Solicitante
                        </th>
                        <th scope="col">
                            Cliente
                        </th>
                        <th scope="col">
                            Criado em
                        </th>
                        <th scope="col">
                            Status
                        </th>
                        <th scope="col">
                            Ações
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr style="text-align: center;">
                            <td style="vertical-align: middle;"><a href="/commercial/order/confirmed/all?code_order=teste" target="_blank" style="color: #428bca;">AM-1000012</a></td>
                            <td style="vertical-align: middle;">
                                <a href="/commercial/salesman/list?code=RC013" target="_blank" style="color: #428bca;">
                                    <b>Representante</b><br>
                                    EDUARDO M
                                </a>
                            </td>
                            <td style="vertical-align: middle;">
                                <a href="/commercial/client/list?code=C05495" target="_blank" style="color: #428bca;">
                                    MIR IMPORTACAO E EXPORTACAO LTDA
                                </a>
                            </td>
                            <td style="vertical-align: middle;">04/05/2021</td>
                            <td style="vertical-align: middle; text-align: left;">
                                <div style="display: flex;justify-content: center;">
                                    <span class="label label-warning">Em faturamento</span>
                                    <div class="table-tooltip" title="" data-original-title="Todos os produtos do pedido, ainda não foram faturados.">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="td-btn" style="vertical-align: middle;">
                                <div style="display: flex;justify-content: center;">
                                    <a href="#" class="btn btn-default btn-sm table-toggle-tr">Detalhes</a>
                                </div>
                            </td>
                        </tr>
                        <tr class="table-collapsible">
                            <td colspan="6">
                                <div class="row">
                                    <div style="margin-top: 10px" class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr style="background: darkred;color: white;">
                                                <th scope="col" style="width: 100px;">
                                                    VPC: <span style="font-weight: lighter">2.00%</span>
                                                </th>
                                                <th scope="col" colspan="2">
                                                    ICMS: <span style="font-weight: lighter">R$ 345,00</span>
                                                </th>
                                                <th scope="col" colspan="2">
                                                    PIS: <span style="font-weight: lighter">R$ 487,14</span>
                                                </th>
                                                <th scope="col" colspan="2">
                                                    COFINS: <span style="font-weight: lighter">R$ 124,50</span>
                                                </th>
                                            </tr>
                                            <tr style="background: black;color: white;">
                                                <th scope="col">
                                                    NF
                                                </th>
                                                <th scope="col">
                                                    Valor bruto
                                                </th>
                                                <th scope="col">
                                                    Valor VPC
                                                </th>
                                                <th scope="col">
                                                    Tipo de pagamento
                                                </th>
                                                <th scope="col">
                                                    Status
                                                </th>
                                                <th scope="col">
                                                    Emitido em
                                                </th>
                                                <th scope="col">
                                                    Ações
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>43496</td>
                                                <td>R$ 227.460,00</td>
                                                <td>R$ 2.133,10</td>
                                                <td>Liquido</td>
                                                <td>
                                                    <span class="label label-warning">Não pago</span> <span style="margin-left: 2px" class="label label-danger">Tem devolução</span>
                                                </td>
                                                <td>04/05/2021</td>
                                                <td class="td-btn">
                                                    <a href="#" class="btn btn-default btn-sm table-toggle-tr">Produtos</a>
                                                    <div class="dropdown">
                                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                                                            Operações
                                                            <i class="fa fa-caret-down"></i>
                                                        </a>
                                                        <ul role="menu" class="dropdown-menu pull-right ext-dropdown-icons-right">
                                                            <li>
                                                                <a href="#"><i class="fa fa-download"></i> Baixar PDF</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fa fa-file-text-o"></i> Ver XML</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fa fa-share"></i>  Aplicar devolução</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fa fa-share"></i>  cancelar devolução</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fa fa-times"></i>  Cancelar</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="table-collapsible">
                                                <td colspan="7">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <ul class="ext-tabs">
                                                                <li class="active">
                                                                    <a href="#content-tab-7-a">Produtos faturados</a>
                                                                </li>
                                                                <li class="">
                                                                    <a href="#content-tab-7-b">Devoluções (10)</a>
                                                                </li>
                                                            </ul><!-- End .ext-tabs -->
                                                            <div class="tab-content ext-tabs-boxed">
                                                                <div id="content-tab-7-a" class="tab-pane active">
                                                                    <div class="inner-padding">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <table class="table table-bordered">
                                                                                    <thead>
                                                                                    <tr style="background: #3276b1;color: white;">
                                                                                        <th scope="col">
                                                                                            Cód. Prod.
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Descrição
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Quant.
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Preço Unit.
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Total
                                                                                        </th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td>CA434N20000</td>
                                                                                        <td>EVAP. GWC09QB-D3NNB4C/I DCR2021/00614-4</td>
                                                                                        <td>204</td>
                                                                                        <td>R$ 390,25</td>
                                                                                        <td>R$ 79.611,00</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>CA434W20000</td>
                                                                                        <td>COND. GWC09QB-D3NNB4C/O DCR2021/00614-4</td>
                                                                                        <td>204</td>
                                                                                        <td>R$ 724,75</td>
                                                                                        <td>R$ 147.849,00</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>CA434W20000</td>
                                                                                        <td>COND. GWC09QB-D3NNB4C/O DCR2021/00614-4</td>
                                                                                        <td>204</td>
                                                                                        <td>R$ 724,75</td>
                                                                                        <td>R$ 147.849,00</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>CA434W20000</td>
                                                                                        <td>COND. GWC09QB-D3NNB4C/O DCR2021/00614-4</td>
                                                                                        <td>204</td>
                                                                                        <td>R$ 724,75</td>
                                                                                        <td>R$ 147.849,00</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>CA434W20000</td>
                                                                                        <td>COND. GWC09QB-D3NNB4C/O DCR2021/00614-4</td>
                                                                                        <td>204</td>
                                                                                        <td>R$ 724,75</td>
                                                                                        <td>R$ 147.849,00</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>CA434W20000</td>
                                                                                        <td>COND. GWC09QB-D3NNB4C/O DCR2021/00614-4</td>
                                                                                        <td>204</td>
                                                                                        <td>R$ 724,75</td>
                                                                                        <td>R$ 147.849,00</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>CA434W20000</td>
                                                                                        <td>COND. GWC09QB-D3NNB4C/O DCR2021/00614-4</td>
                                                                                        <td>204</td>
                                                                                        <td>R$ 724,75</td>
                                                                                        <td>R$ 147.849,00</td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="content-tab-7-b" class="tab-pane">
                                                                    <div class="inner-padding">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <table class="table table-bordered">
                                                                                    <thead>
                                                                                    <tr style="background: #ddd; color: black;">
                                                                                        <th scope="col">
                                                                                            NF: <span style="font-weight: lighter">24574</span>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Valor bruto: <span style="font-weight: lighter">R$ 142.241,00</span>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Emitido em: <span style="font-weight: lighter">15/05/2021</span>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Baixar PDF: <span style="font-weight: lighter"><a href="#"><i class="fa fa-download"></i> Baixar PDF</a></span>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Ver XML: <span style="font-weight: lighter"><a href="#"><i class="fa fa-file-text-o"></i> Ver XML</a></span>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr style="background: #8900ab;color: white;">
                                                                                        <th scope="col">
                                                                                            Cód. Prod.
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Descrição
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Quant.
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Preço Unit.
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            Total
                                                                                        </th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td>CA434N20000</td>
                                                                                        <td>EVAP. GWC09QB-D3NNB4C/I DCR2021/00614-4</td>
                                                                                        <td>3</td>
                                                                                        <td>R$ 390,25</td>
                                                                                        <td>R$ 79.611,00</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>CA434W20000</td>
                                                                                        <td>COND. GWC09QB-D3NNB4C/O DCR2021/00614-4</td>
                                                                                        <td>10</td>
                                                                                        <td>R$ 724,75</td>
                                                                                        <td>R$ 147.849,00</td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- End .tab-content -->
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <div id="filterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Filtrar dados</h4>
                </div>
                <div class="modal-body">
                    <form action="{{Request::url()}}" id="filterData">
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="code">Código do pedido</label>
                                <input type="text" name="code_order" value="" class="form-control" />
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="code">Nota fiscal</label>
                                <input type="number" name="nf_code" value="" class="form-control" />
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="subordinates">Vendedores</label>
                                <select name="subordinates" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="client">Cliente</label>
                                <select name="client" class="form-control select2-client" style="width: 100%;" multiple></select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option></option>
                                    <option value="1">Em faturamento</option>
                                    <option value="2">Faturado</option>
                                </select>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="order_date_start">Data inicial do Ped.</label>
                                <input type="text" name="order_date_start" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="order_date_end">Data final do Ped.</label>
                                <input type="text" name="order_date_end" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="nf_date_start">Data inicial da NF</label>
                                <input type="text" name="nf_date_start" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="nf_date_end">Data final da NF</label>
                                <input type="text" name="nf_date_end" value="" class="form-control myear" />
                            </div>
                        </div>
                    </form>
                    <div class="clear"></div>
                </div>
                <div class="modal-footer" style="padding: 0;height: 76px;">
                    <div data-dismiss="modal" style="float: left;width: 170px;text-align: center;position: relative;top: 0px;font-weight: bold;color: #ff0000;height: 76px;cursor: pointer; font-size: 16px;">
                        <span style="position: relative;top: 25px;">Fechar</span>
                    </div>
                    <div style="position: absolute;height: 76px;border-right: solid 1px #bbb;left: 170px;right: 0;width: 1px;"></div>
                    <div  id="filterNow" style="float: right;width: 178px;text-align: center;position: relative;height:76px;font-weight: bold;color: #18b202;cursor: pointer;font-size: 16px;">
                        <span style="position: relative;top: 25px;">Filtrar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Exportar dados</h4>
                </div>
                <div class="modal-body">
                    <form action="/commercial/order/export" id="exportData">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="order_date_start">Data inicial do Ped.</label>
                                <input type="text" name="order_date_start" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="order_date_end">Data final do Ped.</label>
                                <input type="text" name="order_date_end" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="nf_date_start">Data inicial da NF</label>
                                <input type="text" name="nf_date_start" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="nf_date_end">Data final da NF</label>
                                <input type="text" name="nf_date_end" value="" class="form-control myear" />
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="client">Cliente</label>
                                <select name="client" class="form-control select2-client" style="width: 100%;" multiple></select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="subordinates">Vendedores</label>
                                <select name="subordinates" class="form-control select2-sallesman" style="width: 100%;" multiple></select>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="type_export">Tipo de exportação</label>
                                <select name="type_export" class="form-control">
                                    <option value="1">Relatório VPC - Apuração & Saldo</option>
                                    <option value="2">Relatório VPC - Lista de produtos</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="clear"></div>
                </div>
                <div class="modal-footer" style="padding: 0;height: 76px;">
                    <div data-dismiss="modal" style="float: left;width: 170px;text-align: center;position: relative;top: 0px;font-weight: bold;color: #ff0000;height: 76px;cursor: pointer; font-size: 16px;">
                        <span style="position: relative;top: 25px;">Fechar</span>
                    </div>
                    <div style="position: absolute;height: 76px;border-right: solid 1px #bbb;left: 170px;right: 0;width: 1px;"></div>
                    <div  id="exportNow" style="float: right;width: 178px;text-align: center;position: relative;height:76px;font-weight: bold;color: #18b202;cursor: pointer;font-size: 16px;">
                        <span style="position: relative;top: 25px;">Exportar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script>
    $.fn.datepicker.dates['en'] = {
        days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
        daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
        months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        today: "Hoje",
        clear: "Limpar",
        format: "mm/dd/yyyy",
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        weekStart: 0
    };
    $(".myear").datepicker( {
        format: "yyyy-mm-dd",
    });

    $(document).ready(function () {
        $(".select2-sallesman").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return 'Vendedor não existe...';
                },
                maximumSelected: function (e) {
                    return 'você só pode selecionar 1 item';
                }
            },
            ajax: {
                url: '/commercial/salesman/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".select2-client").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return 'Cliente não existe...';
                },
                maximumSelected: function (e) {
                    return 'você só pode selecionar 1 item';
                }
            },
            ajax: {
                url: '/commercial/client/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });
        $("#orderSale").addClass('menu-open');
        $("#orderInvoice").addClass('page-arrow active-page');
    });
</script>

@endsection
