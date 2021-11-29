@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li class="active">Pedidos faturados</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
<link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
<style>
    .btn-delete-refund {
        font-size: 16px; 
        position: relative; 
        color: #ff0000;
        cursor: pointer;
        left: 5px; 
    }
    .swal2-popup {
        font-size: 1.4rem !important;
    }
    
</style>
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#filterModal">
                    <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                </a>
                <!--<a class="btn btn-warning" href="#" data-toggle="modal" data-target="#exportModal">
                    <i class="fa fa-file" style="color: #ffffff;"></i>&nbsp; Exportar
                </a>-->
            </div>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#errorsModal">
                <span class="indicator-dot @if(count($errors_invoice) > 0) chat-blink-icon animated flash @endif">{{count($errors_invoice)}}</span>
                Erros NFEs recebidas
            </a>
        </div>    
    </div><!-- End .inner-padding -->
</header>
<div class="window">
    <div class="inner-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="table-wrapper">
                    <header>
                        <h3>PEDIDOS FATURADOS</h3>
                    </header>
                    <table class="table table-bordered" data-rt-breakpoint="600">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align: center;">
                                    Pedido
                                </th>
                                <th scope="col" style="text-align: center;">
                                    Solicitante
                                </th>
                                <th scope="col" style="text-align: center;">
                                    Cliente
                                </th>
                                <th scope="col" style="text-align: center; width: 70px;">
                                    Total
                                </th>
                                <th scope="col" style="text-align: center; width: 80px;">
                                    Faturado
                                </th>
                                <th scope="col" style="text-align: center; width: 80px;">
                                    Devolução
                                </th>
                                <th scope="col" style="text-align: center;">
                                    Criado em
                                </th>
                                <th scope="col" style="text-align: center;">
                                    Status
                                </th>
                                <th scope="col" style="text-align: center;">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($order_invoice) > 0)
                                @foreach($order_invoice as $i => $key)
                                    <tr style="text-align: center;">
                                        <td style="vertical-align: middle;"><a href="/commercial/order/confirmed/all?code_order={{$key->code}}" target="_blank" style="color: #428bca;">{{$key->code}}</a></td>
                                        <td style="vertical-align: middle;">
                                            <a href="/commercial/salesman/list?code={{$key->salesman->code}}" target="_blank" style="color: #428bca;">
                                                <b>Representante</b><br>
                                                {{$key->salesman->short_name}}
                                            </a>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <a href="/commercial/client/list?code={{$key->client->code}}" target="_blank" style="color: #428bca;">
                                                {{$key->client->company_name}}
                                            </a>
                                        </td>
                                        <td style="vertical-align: middle;">{{$key->total_order}}</td>
                                        <td style="vertical-align: middle;">{{$key->total_invoice}}</td>
                                        <td style="vertical-align: middle;">{{$key->total_refund}}</td>
                                        <td style="vertical-align: middle;">{{date('d/m/Y', strtotime($key->created_at))}}</td>
                                        <td style="vertical-align: middle; text-align: left;">
                                            <div style="display: flex;justify-content: center;">
                                                @if ($key->is_invoice == 1)
                                                    <span class="label label-success">Faturado</span>
                                                @elseif($key->is_invoice == 0)
                                                    <span class="label label-warning">Em faturamento</span>
                                                @endif    
                                                <div class="table-tooltip" title="" data-original-title="@if($key->is_invoice == 1) Todos os produtos do pedido foram faturados @else Todos os produtos do pedido, ainda não foram faturados. @endif">
                                                    <i class="fa fa-info-circle"></i>
                                                </div>
                                            </div>
                                        </td>   
                                        <td class="td-btn" style="vertical-align: middle;">
                                            <div style="justify-content: center;">
                                                <a href="#" class="btn btn-default btn-sm table-toggle-tr">
                                                    @if(count($key->orderInvoice) > 0)
                                                        Detalhes
                                                    @else
                                                        Não há NFe faturadas
                                                    @endif
                                                </a>
                                                @if($key->is_invoice == 0)
                                                <div class="dropdown">
                                                    <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                                                        Ações
                                                        <i class="fa fa-caret-down"></i>
                                                    </a>
                                                    <ul role="menu" class="dropdown-menu pull-right ext-dropdown-icons-right">
                                                        <li>
                                                            <a href="javascript:void(0)" class="btn_confirm_invoice" data-id="{{$key->id}}"><i class="fa fa-check"></i> Confirmar faturamento</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="table-collapsible">
                                        <td colspan="9">
                                            @foreach ($key->orderInvoice as $j => $invoice)
                                            <div class="row">
                                                <div style="margin-top: 10px" class="col-md-12">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr style="background: darkred;color: white;">
                                                            <th scope="col" style="width: 100px;">
                                                                VPC: <span style="font-weight: lighter">{{$invoice->contract_vpc}}%</span>
                                                            </th>
                                                            <th scope="col" colspan="2">
                                                                ICMS: <span style="font-weight: lighter">R$ {{number_format($invoice->nf_icms_total, 2,",",".")}}</span>
                                                            </th>
                                                            <th scope="col" colspan="2">
                                                                PIS: <span style="font-weight: lighter">R$ {{number_format($invoice->nf_pis_total, 2,",",".")}}</span>
                                                            </th>
                                                            <th scope="col" colspan="2">
                                                                COFINS: <span style="font-weight: lighter">R$ {{number_format($invoice->nf_cofins_total, 2,",",".")}}</span>
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
                                                            <td>{{$invoice->nf_number}}</td>
                                                            <td>R$ {{number_format($invoice->nf_total, 2,",",".")}}</td>
                                                            <td>R$ {{number_format($invoice->vpc_total_paid, 2,",",".")}}</td>
                                                            <td>
                                                                @if($invoice->type_payment_vpc == 1)
                                                                    Líquido
                                                                @elseif($invoice->type_payment_vpc == 2)    
                                                                    Bruto
                                                                @endif    
                                                            </td>
                                                            <td>
                                                                @if($invoice->is_paid_vpc == 0)
                                                                    <span class="label label-warning" style="position: relative;left: 30%;">Não pago</span> 
                                                                @else
                                                                    <span class="label label-success" style="position: relative;left: 35%;">Pago</span> 
                                                                @endif    
                                                                @if($invoice->is_refund == 1)
                                                                    <span class="label label-danger" style="position: relative;left: 20%;">Tem devolução</span>
                                                                @endif
                                                            </td>
                                                            <td>{{date('d/m/Y', strtotime($invoice->date_emission))}}</td>
                                                            <td class="td-btn">
                                                                <a href="#" class="btn btn-default btn-sm table-toggle-tr">Produtos</a>
                                                                <div class="dropdown">
                                                                    <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                                                                        Operações
                                                                        <i class="fa fa-caret-down"></i>
                                                                    </a>
                                                                    <ul role="menu" class="dropdown-menu pull-right ext-dropdown-icons-right">
                                                                        <li>
                                                                            <a href="{{$invoice->nf_pdf_url}}" target="_blank"><i class="fa fa-download"></i> Ver PDF</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{$invoice->nf_xml_url}}" target="_blank"><i class="fa fa-file-text-o"></i> Ver XML</a>
                                                                        </li>
                                                                        @if ($key->is_invoice == 0)
                                                                        <li>
                                                                            <a href="javascript:void(0)" class="btn_refund_modal" data-json='<?= htmlspecialchars(json_encode($key->orderProducts), ENT_QUOTES, "UTF-8") ?>' data-nfe="{{$invoice->nf_number}}" data-invoice-id="{{$invoice->id}}"><i class="fa fa-share"></i>  Aplicar devolução</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="javascript:void(0)" class="btn_delete_nfe_invoice" data-order-id="{{$invoice->order_sales_id}}" data-nfe="{{$invoice->nf_number}}"><i class="fa fa-times"></i> Excluir NFe faturada</a>
                                                                        </li>
                                                                        @endif
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
                                                                                <a href="#content-tab-7-a-{{$i}}-{{$j}}">Produtos faturados</a>
                                                                            </li>
                                                                            @if(count($invoice->orderInvoiceRefund) > 0)
                                                                                <li class="">
                                                                                    <a href="#content-tab-7-b-{{$i}}-{{$j}}">Devoluções ({{count($invoice->orderInvoiceRefund)}})</a>
                                                                                </li>
                                                                            @endif
                                                                        </ul><!-- End .ext-tabs -->
                                                                        <div class="tab-content ext-tabs-boxed">
                                                                            <div id="content-tab-7-a-{{$i}}-{{$j}}" class="tab-pane active">
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
                                                                                                    @foreach ($invoice->orderInvoiceProducts as $product)
                                                                                                        <tr>
                                                                                                            <td style="overflow-wrap: break-word;"><?= $product->productAir ? $product->productAir->sales_code : '-' ?></td>
                                                                                                            <td>{{$product->description_product}}</td>
                                                                                                            <td>{{$product->quantity}}</td>
                                                                                                            <td>R$ {{number_format($product->price_unit, 2,",",".")}}</td>
                                                                                                            <td>R$ {{number_format($product->price_total, 2,",",".")}}</td>
                                                                                                        </tr>
                                                                                                    @endforeach    
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div id="content-tab-7-b-{{$i}}-{{$j}}" class="tab-pane">
                                                                                @foreach ($invoice->orderInvoiceRefund as $refund)
                                                                                <div class="inner-padding">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <table class="table table-bordered">
                                                                                                <thead>
                                                                                                    <tr style="background: #ddd; color: black; font-size: 12px;">
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            NF: <span style="font-weight: lighter">{{$refund->nf_number}}</span>
                                                                                                            <i class="fa fa-trash-o btn-delete-refund" data-id="{{$refund->id}}" data-nfe="{{$refund->nf_number}}"></i>
                                                                                                        </th>
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            Valor bruto: <span style="font-weight: lighter">R$ {{number_format($refund->nf_total, 2,",",".")}}</span>
                                                                                                        </th>
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            Emitido em: <span style="font-weight: lighter">{{date('d/m/Y', strtotime($refund->date_emission))}}</span>
                                                                                                        </th>
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            Baixar PDF: <span style="font-weight: lighter"><a href="{{$refund->nf_pdf_url}}" target="_blank"><i class="fa fa-download"></i> Ver PDF</a></span>
                                                                                                        </th>
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            Ver XML: <span style="font-weight: lighter"><a href="{{$refund->nf_xml_url}}" target="_blank"><i class="fa fa-file-text-o"></i> Ver XML</a></span>
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                    <tr style="background: #8900ab;color: white;text-align: center;">
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            Cód. Prod.
                                                                                                        </th>
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            Descrição
                                                                                                        </th>
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            Quant.
                                                                                                        </th>
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            Preço Unit.
                                                                                                        </th>
                                                                                                        <th scope="col" style="text-align: center;">
                                                                                                            Total
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    @foreach ($refund->orderInvoiceRefundProducts as $refundProducts)
                                                                                                    <tr style="text-align: center;">
                                                                                                        <td>{{$refundProducts->code_product}}</td>
                                                                                                        <td>{{$refundProducts->description_product}}</td>
                                                                                                        <td>{{$refundProducts->quantity}}</td>
                                                                                                        <td>R$ {{number_format($refundProducts->price_unit, 2,",",".")}}</td>
                                                                                                        <td>R$ {{number_format($refundProducts->price_total, 2,",",".")}}</td>
                                                                                                    </tr>
                                                                                                    @endforeach
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @endforeach
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
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            @else 
                                <tr style="text-align: center;">
                                    <td colspan="9">Não há pedidos faturados</td>
                                </tr>    
                            @endif
                        </tbody>
                    </table>
                </div>    
            </div>
        </div>
        <div class="spacer-50"></div>
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
                                <option value="99">Em faturamento</option>
                                <option value="1">Faturado</option>
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


<div id="refundModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Importar devolução</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i>Para importar, selecione arquivo xml de devolução referente a nota fiscal (<span id="info_refund"></span>)</a>
                </div>
                <form method="post" action="#" id="form_import_refund" enctype="multipart/form-data">
                    <input type="hidden" name="invoice_id" id="invoice_id">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="order_date_start">XML de devolução</label>
                            <input type="file" name="xml_refund" id="xml_refund" style="width:100%;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="order_date_start">PDF de devolução</label>
                            <input type="file" name="pdf_refund" id="pdf_refund" style="width:100%;">
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                <button class="btn btn-primary pull-right" id="btn_import_refund">Importar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="errorsModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg" style="width: 70%; margin: 50px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Erros de recebimento das NFEs</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom:-20px; margin-top:-20px;">
                    <div class="inner-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align:center; width:110px;">Número NFe</th>
                                    <th scope="col">Chave Nfe</th>
                                    <th scope="col">Mensagem erro</th>
                                    <th scope="col">Arquivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($errors_invoice) > 0)
                                <form method="post" action="/commercial/operation/order/invoice/resend/xml" id="form_resend_nfe_xml" enctype="multipart/form-data">
                                    @foreach ($errors_invoice as $errors)
                                    <tr>
                                        <td style="text-align: center;">{{$errors->number_nfe}}</td>
                                        <td style="overflow-wrap: break-word;">
                                            {{$errors->key_nfe}}
                                            <input type="hidden" name="key_nfe[]" value="{{$errors->key_nfe}}">
                                        </td>
                                        <td>
                                            <span><?= stringCut($errors->message, 20) ?></span>
                                            <a href="#" class="btn btn-default btn-sm table-toggle-tr" style="float: right;">Detalhes</a>
                                        </td>
                                        <td>
                                            <input class="file_error" type="file" name="xml_refund[]" style="width:100%;">
                                        </td>
                                    </tr>
                                    <tr class="table-collapsible" style="display: none;">
                                        <td colspan="4">
                                            <p>{{$errors->message}}</p>                                                                                     
                                        </td>
                                    </tr>
                                    @endforeach
                                </form>
                                @else    
                                    <tr>
                                        <td style="text-align: center;" colspan="4">Não há erros de notas fiscais recebidas!</td>
                                    </tr>    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                @if(count($errors_invoice) > 0)
                    <button class="btn btn-primary pull-right" id="btn_resend_nfe_xml">Reenviar notas</button>
                @endif    
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="refundModalImport" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg" style="width: 70%; margin: 50px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Vinculação de produtos da devolução</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom:-20px; margin-top:-20px;">
                    <div class="inner-padding">

                        <div class="alert alert-danger">
                            <span>Vincule os produtos devolvidos com seu respectivo modelo no pedido</span>
                        </div>

                        <form method="POST" action="/commercial/operation/order/invoice/import/refund" id="form_import_refund_confirm" enctype="multipart/form-data">
                            <input type="hidden" name="invoice_id" id="invoice_id_confirm">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Descrição</th>
                                        <th scope="col">Modelo</th>
                                    </tr>
                                </thead>
                                <tbody id="table_refund_import"></tbody>
                            </table>
                        </form>    
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                <button class="btn btn-primary pull-right" id="btn_import_refund_confirm">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script>

    var order_products = [];
    var arr_model = [];

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

        $("#filterNow").click(function (){
            $("#filterData").submit();
        });

        $(".btn_refund_modal").click(function() {
            var number_nfe = $(this).attr("data-nfe");
            var invoice_id = $(this).attr("data-invoice-id");
            order_products = JSON.parse($(this).attr("data-json"));

            $("#invoice_id").val(invoice_id);
            $("#info_refund").text(number_nfe);
            $("#refundModal").modal("show");
        });

        $("#btn_import_refund").click(function() {
            if($("#xml_refund")[0].files.length == 0) {
                return $error('Selecione o arquivo XML!');
            } else if($("#pdf_refund")[0].files.length == 0) {
                return $error('Selecione o arquivo PDF!');
            } else {    

                $("#invoice_id_confirm").val($("#invoice_id").val());
                $("#xml_refund").clone().appendTo("#form_import_refund_confirm").css('display','none');
                $("#pdf_refund").clone().appendTo("#form_import_refund_confirm").css('display','none');

                var file = $("#xml_refund")[0].files[0];
                var reader = new FileReader();
                reader.readAsText(file);
                reader.onloadend = function(event) {
                    var text = event.target.result;
                    var parser = new DOMParser(),
                    xml = parser.parseFromString(text, "application/xml");
                    var det = xml.getElementsByTagName("det");
                    $("#table_refund_import").html(loadDetXmlModal(det));
                    $("#refundModal").modal('hide');
                    $("#refundModalImport").modal('show');
                }
            }
        });

        function loadDetXmlModal(det) {

            arr_model = [];
            order_products.forEach(function(item, index){

                if(item.set_product.product_air_cond != null && item.set_product.product_air_evap != null) {
                    arr_model.push(
                        {
                            product_id: item.set_product.product_air_evap.id,
                            product_description: item.set_product.product_air_evap.model,
                            set_product_id: item.set_product.id,
                            set_product_btus: item.set_product.btus,
                            set_product_group_id: item.set_product.set_product_on_group[0].id
                        },
                        {
                            product_id: item.set_product.product_air_cond.id,
                            product_description: item.set_product.product_air_cond.model,
                            set_product_id: item.set_product.id,
                            set_product_btus: item.set_product.btus,
                            set_product_group_id: item.set_product.set_product_on_group[0].id
                        },
                    );
                }    
            });

            var option = '';
            arr_model.forEach(function(item, index){
                option += `<option value="`+index+`">`+item.product_description+`</option>`;
            });

            var html = '';
            for ( i = 0; i < det.length; i++) {
                var desc_prod = det[i].getElementsByTagName('xProd')[0].innerHTML;
                var code_pord = det[i].getElementsByTagName('cProd')[0].innerHTML;
                html += `<tr>
                            <td>`+desc_prod+`</td>
                            <td><select class="form-control model_refund" data-index="`+i+`"><option value=""></option>`+option+`</select></td>
                            <td style="display: none;"><input type="hidden" name="refund_cprod[]" value="`+code_pord+`"></td>
                            <td style="display: none;"><input type="hidden" name="refund_xprod[]" value="`+desc_prod+`"></td>
                            <td style="display: none;"><input type="hidden" name="refund_data[]" id="refund_data_`+i+`"></td>
                        </tr>`;
            }
            return html;
        }

        $(document).on('change', '.model_refund', function(e){
            var index = $(this).attr("data-index");
            var model_index = $(this).val();
            $("#refund_data_"+index+"").val(JSON.stringify(arr_model[model_index]));
        });

        $("#btn_import_refund_confirm").click(function() {
            count_empty = $('.model_refund').filter(function(){return $(this).val() == ''}).length;
            if(count_empty != 0) {
                return $error('Vincule todos os modelos!');
            } else {
                block();
                $("#form_import_refund_confirm").submit();
            }
        });

        $("#btn_resend_nfe_xml").click(function() {
            
            count_empty = $('.file_error').filter(function(){return $(this)[0].files.length != 0}).length;
            if(count_empty == 0) {
                return $error('Para reenviar, necessário escolher ao menos um arquivo!');
            } else {
                swal({
                    title: "Verificação de erros",   
                    target: document.getElementById('errorsModal'),
                    text: "Deseja confirmar que os erros foram corrigidos?",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#3085d6",   
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Confirmar",   
                    cancelButtonText: "Cancelar",   
                    closeOnConfirm: false,   
                    closeOnCancel: false,
                    position: 'center'
                }).then(function (result) {
                    if(result.value) {
                        block();
                        $("#form_resend_nfe_xml").submit();
                    }
                });
            }
        });

        $(".btn-delete-refund").click(function() {

            var refund_id = $(this).attr("data-id");
            var data_nfe = $(this).attr("data-nfe");
            
            swal({
                title: "Remover devolução",   
                text: "Deseja confirmar a remoção da NFe de devolução de número "+data_nfe+"?",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",   
                cancelButtonText: "Cancelar",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }).then(function (result) {
                if(result.value) {
                    block();
                    window.location = "/commercial/operation/order/invoice/refund/delete/"+refund_id;
                }    
            });
        });

        $(".btn_confirm_invoice").click(function() {

            var invoice_id = $(this).attr("data-id");
            swal({
                title: "Comfirmar faturamento",   
                text: "Deseja confirmar que este pedido foi faturado?",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",   
                cancelButtonText: "Cancelar",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }).then(function (result) {
                if(result.value) {
                    block();
                    window.location = "/commercial/operation/order/invoice/confirm/"+invoice_id;
                }    
            });
        });

        $(".btn_delete_nfe_invoice").click(function() {

            var order_id = $(this).attr("data-order-id");
            var code_nfe = $(this).attr("data-nfe");

            swal({
                title: "Confirmar exclusão de nota faturada",   
                text: "Deseja confirmar a exclusão da nota "+code_nfe+"?",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",   
                cancelButtonText: "Cancelar",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }).then(function (result) {
                if(result.value) {
                    block();
                    window.location = "/commercial/operation/order/invoice/nfe/delete/"+order_id+"/"+code_nfe;
                }    
            });
        });

        $("#operation").addClass('menu-open');
        $("#orderInvoice").addClass('page-arrow active-page');
    });
</script>

@endsection
