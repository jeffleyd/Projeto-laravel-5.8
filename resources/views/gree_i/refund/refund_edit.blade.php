@extends('gree_i.layout')

@section('content')
    <style>
        @media (max-width: 600px) {
            .sumary {
                position: inherit !important;
            }
        }
    </style>
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/shepherd-theme-default.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/tour/tour.min.css">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Reembolso</h5>
                        <div class="breadcrumb-wrapper col-12">
                            @if ($id == 0)
                                Novo pedido
                            @else
                                Atualizando pedido
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!$refund)
            <div class="alert alert-primary alert-dismissible mb-2" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bx bxs-info-circle"></i>
                    <span>
            Atualize seus dados da sua conta bancária para poder enviar para análise.
            </span>
                    <div style="width:100%">
                        <button type="button" class="btn btn-sm btn-secondary float-right" id="btnAccount" data-toggle="modal" data-target="#modal-account">{{ __('lending_i.lrn_26') }}</button>
                    </div>
                </div>
            </div>
        @else
            <section>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <b>ID</b>
                                            <br>{{$refund->code}}
                                        </div>
                                        <div class="col-md-4">
                                            <b>Solicitante</b>
                                            <br><a target="_blank" href="/user/view/<?= $refund->request_r_code ?>"><?= getENameF($refund->request_r_code); ?></a>
                                        </div>
                                        <div class="col-md-4">
                                            <b>Criado em</b>
                                            <br>{{ date('Y-m-d H:i', strtotime($refund->created_at))}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <section>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body" style="padding: 0px;margin: 5px 30px;">
                                <div class="row">
                                    <div class="col-12">
                                        <fieldset class="form-group mt-1">
                                            <p>Caso a pessoa que vá receber o dinheiro, seja outra, informe abaixo.</p>
                                            <select class="recipient form-control" id="recipient_r_code" name="recipient_r_code" @if($id != 0) @if ($refund->has_analyze == 1 or $refund->is_approv == 1) disabled @endif @endif style="width:100%" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                                <option></option>
                                                @if ($userall)
                                                    <?php foreach ($userall as $key) { ?>
                                                    <option value="<?= $key->r_code ?>" @if($id != 0) @if ($key->r_code == $refund->recipient_r_code) selected @endif @endif><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                                    <?php } ?>
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body" style="padding: 0px;margin: 5px 30px;">
                                <div class="row">
                                    <div class="col-12">
                                        <fieldset class="form-group mt-1">
                                            <p>Caso tenha que informar algo importante, digite abaixo.</p>
                                            <textarea class="form-control" id="question" name="question" rows="3" @if ($id != 0) @if ($refund->has_analyze == 1) readonly @endif @endif>@if ($id != 0) {{$refund->description}} @endif</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="content-body">
            <div class="row">
                <div class="col-12 col-md-9">
                    <div class="card list">
                        <div class="card-content">
                            <!-- table head dark -->
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>TIPO</th>
                                        <th>DESCRIÇÃO</th>
                                        <th>PESSOAS (QTD)</th>
                                        <th>CIDADE</th>
                                        <th>MOEDA</th>
                                        <th>TOTAL</th>
                                        <th>DATA</th>
                                        <th>ANEXO</th>
                                        <th>AÇÕES</th>
                                    </tr>
                                    </thead>
                                    <tbody id="ListItens">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card sumary">
                        <div class="card-header">Sumário de reembolso</div>
                        <div class="card-content">
                            <div class="float-right p-1">
                                <table class="table" style="font-size: 12px !important;">
                                    <tbody>
                                    <tr>
                                    <tr>
                                        <td class="text-right p-1">TOTAL DE DESPESAS:</td>
                                        <td class="text-right total_des p-1" style="width: 120px;">R$ <?= $total_item ?></td>
                                    </tr>
                                    <tr class="cursor-pointer lending_click">
                                        <td class="text-right p-1"><i class="bx bxs-help-circle" style="position: relative;top: 3px; left: 0px;" data-toggle="tooltip" data-placement="top" data-original-title="Clique em cima para mudar o valor"></i> ADIANTAMENTO:</td>
                                        <td class="text-right lending p-1" style="width: 120px;">R$ <?= $lending ?></td>
                                    </tr>
                                    <tr id="status-total">
                                        <td class="text-uppercase text-right p-1">SALDO A PAGAR (RECEBER):</td>
                                        <td class="text-right total_amount p-1" style="width: 120px;">R$ <?= $total_amount ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-center table-dark cursor-pointer" data-toggle="modal" data-target="#modal-details">
                            <h6 class="mb-1 mt-1" style="color:white">VER DETALHES</h6>
                        </div>
                        @if ($id > 0)
                            <div class="text-center table-danger cursor-pointer" onclick="location.href = '/financy/refund/edit/<?= $id ?>?submit=export&refund_id=<?= $id ?>';">
                                <h6 class="mb-1 mt-1" style="color:white">EXPORTAR</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-details" tabindex="-1" role="dialog" aria-labelledby="modal-details" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-details">DETALHES COMPLETO DO REEMBOLSO</h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless" style="font-size: 12px !important;">
                        <tbody class="detail_cons">
                        <div class="list-consum">

                        </div>
                        <div class="list-consum-inter" style="display:none;">
                            <div class="list-consum-inter-item">

                            </div>
                        </div>
                        </tbody>
                    </table>
                    <table class="table table-borderless" style="font-size: 12px !important;">
                        <tbody>
                        <tr>
                            <td class="text-left p-1" colspan="3"><b>SUB TOTAL</b></td>
                        </tr>
                        <tr>
                            <td class="text-left p-1">EMPRÉSTIMO:</td>
                            <td class="text-left lending_detail p-1">R$ <?= $lending ?></td>
                        </tr>
                        <tr>
                            <td class="text-left p-1">TOTAL:</td>
                            <td class="text-left total_detail p-1">R$ <?= $total_amount ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('lending_i.lrn_32') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-update" tabindex="-1" role="dialog" aria-labelledby="modal-update" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <span class="modal-title title-item" id="modal-update"></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form class="push" id="a_update_form" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="item_id" name="item_id" value="0">
                            <input type="hidden" id="squestion" name="squestion" value="@if($id != 0) {{$refund->description}} @endif">
                            <input type="hidden" id="srecipient_r_code" name="srecipient_r_code" value="@if($id != 0) {{$refund->recipient_r_code}} @endif">
                            <input type="hidden" id="id" name="id" value="<?= $id ?>">
                            <div class="col-sm-12">
                                <label for="sector">TIPO DE CONSUMO</label>
                                <fieldset class="form-group">
                                    <select class="form-control" name="type" id="type">
                                        <option value="1">COMBUSTÍVEL</option>
                                        <option value="2">TAXI</option>
                                        <option value="3">UBER/99</option>
                                        <option value="4">PASSAGEM AÉREA</option>
                                        <option value="5">HOSPEDAGEM</option>
                                        <option value="6">ALIMENTAÇÃO</option>
                                        <option value="7">OUTRO</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">DESCRIÇÃO</label>
                                <fieldset class="form-group">
                                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Justifique se necessário..."></textarea>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <label for="peoples">QUANTIDADE DE PESSOAS</label>
                                <fieldset class="form-group">
                                    <input type="number" class="form-control" name="peoples" id="peoples" min="1" value="1">
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <label for="city">CIDADE</label>
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" name="city" id="city" placeholder="Digite a cidade...">
                                </fieldset>
                            </div>
                            <div class="col-sm-6">
                                <label for="currency">MOEDA</label>
                                <fieldset class="form-group">
                                    <select class="form-control" name="currency" id="currency">
                                        <option value="1">REAL (BRL)</option>
                                        <option value="2">DOLLAR (USD)</option>
                                        <option value="3">RENMIBI (CNY)</option>
                                        <option value="4">HONG KONG DOLLAR (HKD)</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-sm-6">
                                <label for="total">TOTAL</label>
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" name="total" id="total" placeholder="0,00">
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <label for="date">DATA DE CONSUMO</label>
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" name="date" id="date" placeholder="__/__/____">
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <label for="receipt">COMPROVANTE</label>
                                <fieldset class="form-group">
                                    <input type="file" class="form-control" name="receipt" id="receipt">
                                    <small>Esse arquivo é obrigatório.</small>
                                    <br><a href="#" id="receipt_url" style="display:none"></a>
                                </fieldset>


                                <label for="other">OUTRO ARQUIVO</label>
                                <fieldset class="form-group">
                                    <input type="file" class="form-control" name="other" id="other">
                                    <small>Esse arquivo não é obrigatório.</small>
                                    <br><a href="#" id="other_url" style="display:none"></a>
                                </fieldset>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="resetFields();" class="btn btn-light-secondary" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">FECHAR</span>
                        </button>
                        <button type="button" id="updoradditem" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">ATUALIZAR LISTA</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($id != 0)
        @if ($refund->has_analyze == 0 and $refund->is_approv == 0)
            <div class="mb-2" style="text-align: center; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99;">
                <button type="button" class="btn btn-primary mb-1 mr-1" data-toggle="modal" id="newItem" data-target="#modal-update">Novo item</button>
                <button type="button" onclick="Approv();" class="btn btn-success mb-1 sendapprov" style="display:none">Enviar</button>
            </div>
        @endif
    @else
    <div class="mb-2" style="text-align: center; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99;">
        <button type="button" class="btn btn-primary mb-1 mr-1" data-toggle="modal" id="newItem" data-target="#modal-update">Novo item</button>
    </div>
    @endif

    <div class="modal fade text-left" id="modal-account" tabindex="-1" role="dialog" aria-labelledby="modal-account" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-account">{{ __('lending_i.lrn_27') }}</h3>
                </div>
                <div class="modal-body">
                    <form id="UpdateAccount" action="#" method="post">
                        <input type="hidden" id="r_code" name="r_code">
                        <div class="form-group">
                            <label for="agency">{{ __('lending_i.lrn_28') }}</label>
                            <input class="form-control" type="text" name="agency" id="agency" value="<?php if (isset($a_bank)) { ?><?= $a_bank->agency ?><?php } ?>">
                        </div>
                        <div class="form-group">
                            <label for="account">{{ __('lending_i.lrn_29') }}</label>
                            <input class="form-control" type="text" name="account" id="account" value="<?php if (isset($a_bank)) { ?><?= $a_bank->account ?><?php } ?>">
                        </div>
                        <div class="form-group">
                            <label for="bank">{{ __('lending_i.lrn_30') }}</label>
                            <input class="form-control" type="text" name="bank" id="bank" value="<?php if (isset($a_bank)) { ?><?= $a_bank->bank ?><?php } ?>">
                        </div>
                        <div class="form-group">
                            <label for="identity">{{ __('lending_i.lrn_31') }}</label>
                            <input class="form-control" type="text" name="identity" id="identity" value="<?php if (isset($a_bank)) { ?><?= $a_bank->identity ?><?php } ?>">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('lending_i.lrn_33') }}</span>
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    @if ($id != 0)
        <div class="customizer d-none d-md-block">
            <a class="customizer-toggle" onclick="rtd_analyzes({{$id}}, 'App\\Model\\FinancyRefund');" href="#" style="writing-mode: vertical-lr;height: 115px;">
                <b>Hist. Análise</b>
            </a>
        </div>
    @endif

    @if ($has_analyze == 1)
        <div class="mb-2 cursor-pointer" id="showAnalyze" style="position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
            <i class="bx bx-up-arrow-alt"></i>
            <br>Mostrar análise
        </div>


        <div class="card text-center" id="Analyze" style="width: 395px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; opacity: 0.9;z-index: 99;">
            <div class="card-content">
                <button type="button" id="HAnalyze" class="close HideAnalyze" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
                <div class="card-body">
                    <form id="AnalyzeForm" action="#" method="post">
                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-center">
                                <button type="button" onclick="analyze({{$id}}, {{$refund->rtd_position}})" class="btn btn-success min-width-125">REALIZAR ANÁLISE</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @include('gree_i.misc.components.analyze.history.view')
    @include('gree_i.misc.components.analyze.do_analyze.inputs', ['url' => '/financy/refund/analyze/'.$id])
    @include('gree_i.misc.components.analyze.do_analyze.script')
    <script>
        var is_edit = 0;
        var arrayItens = new Array();
        var arrayDetails = new Array();
        var index_edit = 0;
        @if ($a_bank)
        var request_r_code_account = {agency: '{{$a_bank->agency}}', account: '{{$a_bank->account}}', bank: '{{$a_bank->bank}}', identity: '{{$a_bank->identity}}'}
        @else
        var request_r_code_account ={};
        @endif
        var id_refund = <?= $id ?>;

        @include('gree_i.misc.components.analyze.history.script')

        function Approv() {
            Swal.fire({
                title: 'Aprovação',
                text: "Confirma o envio da solicitação de reembolso para aprovação?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
                cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    window.location.href = "/financy/refund/send/analyze/" + id_refund + "?desc="+ $('#question').val() +"&srecipient_r_code="+$('#srecipient_r_code').val();
                }
            })
        }

        function type(nmb) {
            switch (nmb) {
                case 1:

                    return 'COMBUSTÍVEL';
                    break;
                case 2:

                    return 'TAXI';
                    break;
                case 3:

                    return 'UBER/99';
                    break;
                case 4:

                    return 'PASSAGEM AÉREA';
                    break;
                case 5:

                    return 'HOSPEDAGEM';
                    break;
                case 6:

                    return 'ALIMENTAÇÃO';
                    break;
                case 7:

                    return 'OUTROS';
                    break;
            }
        }
        function currency(nmb) {
            switch (nmb) {
                case 1:

                    return 'BRL';
                    break;
                case 2:

                    return 'USD';
                    break;
                case 3:

                    return 'CNY';
                    break;
                case 4:

                    return 'HKD';
                    break;
            }
        }
        function ConverteQuat(theform) {
            var num = theform, rounded = theform.rounded;
            var with2Decimals = num.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0];
            return rounded = with2Decimals;
        }

        function windowOpen(url, name) {
            var win =  window.open(url, name, 'width=650,height=600');
        }
        function reloadTable() {

            var asp = "'";
            var list = "";
            var inter = "";
            inter += '<tr>';
            inter += '<td class="text-left p-1" colspan="3"><b>GASTOS INTERNACIONAIS</b></td>';
            inter += '</tr>';
            var correct = 0;
            var is_inter_visible = 0;
            var position = 1;
            for(var i = 0; i < arrayItens.length; i++) {
                var arrayObj = arrayItens[i];
                if (arrayObj.old_total != "0,00") {
                    correct = 1;
                    list += '<tr class="table-danger">';
                } else {
                    list += '<tr>';
                }
                list += '<td class="text-bold-500">'+ position +'</td>';
                list += '<td class="text-bold-500">'+ type(arrayObj.type) +'</td>';
                list += '<td>';
                if (arrayObj.desc_request) {
                    list += '<span>'+ arrayObj.desc_request +'</span>';
                }
                list += '</td>';
                list += '<td>'+ arrayObj.peoples +'</td>';
                list += '<td><small>'+ arrayObj.city +'</small></td>';
                list += '<td>'+ currency(arrayObj.currency) +'</td>';
                list += '<td class="text-bold-500">';
                if (arrayObj.old_total != "0,00") {
                    list += '<s>'+ arrayObj.old_total +'</s><br>'+ arrayObj.item_total +'';
                } else {
                    list += ''+ arrayObj.item_total +'';
                }
                list += '</td>';
                list += '<td>'+ arrayObj.date +'</td>';
                list += '<td>';
                list += '<a target="_blank" data-toggle="popover" data-content="'+ arrayObj.receipt_name +'" href="'+ arrayObj.receipt_url +'"><i class="bx bxs-file-image mr-1"></i></a>';
                if (arrayObj.other_url != "") {
                    list += '<a target="_blank" data-toggle="popover" data-content="'+ arrayObj.other_name +'" href="'+ arrayObj.other_url +'"><i class="bx bxs-file-image mr-1"></i></a>';
                }
                list += '</td>';
                list += '<td id="action">';
                @if ($id != 0)
                    @if ($refund->has_analyze == 1 and $is_financy == 1 or $refund->has_analyze == 0 and $refund->is_approv == 0)
                    list += '<div class="dropleft">';
                list += '<span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>';
                list += '<div class="dropdown-menu dropdown-menu-right">';
                list += '<a class="dropdown-item" href="javascript:edit('+ i +');"><i class="bx bx-edit-alt mr-1"></i> Editar</a>';
                list += '<a class="dropdown-item" href="javascript:deletes('+ i +');"><i class="bx bx-trash-alt mr-1"></i> Excluir</a>';
                list += '</div>';
                list += '</div>';
                    @endif
                @endif
                list += '</td>';
                list += '</tr>';

                if (arrayObj.currency > 1) {
                    is_inter_visible = 1;
                    var i_total = arrayObj.item_total.replace(/\,/g, '.');
                    var str_currency = (parseFloat(i_total) * arrayObj.quotation).toFixed(2).replace(".", ",");
                    inter += '<tr>';
                    inter += '<td class="text-left p-1">'+ type(arrayObj.type) +':</td>';
                    inter += '<td class="text-left p-1">'+ currency(arrayObj.currency) +' '+ arrayObj.item_total +' * <small>'+ ConverteQuat(arrayObj.quotation) +'</small> : BRL '+ str_currency +'</td>';
                    inter += '<td class="text-left p-1">'+ arrayObj.date +'</td>';
                    inter += '</tr>';

                }

                position++;
            }

            if (correct == 1) {
                $(".correction").show();
            } else {
                $(".correction").hide();
            }
            if (is_inter_visible == 1) {
                $(".list-consum-inter").show();
            } else {
                $(".list-consum-inter").hide();
            }
            $("#ListItens").html(list);
            $(".list-consum-inter-item").html(inter);

            $('[data-toggle="popover"]').popover({
                placement: 'right',
                trigger: 'hover',
            });
        }
        function reloadDetails() {

            var list = "";
            var totaln = "0,00";
            arrayDetails = new Array();
            for(var i = 0; i < arrayItens.length; i++) {
                var arrayObj = arrayItens[i];
                if (arrayDetails.length > 0) {

                    var objDetail = arrayDetails.find(x => x.id == arrayObj.type);
                    if (objDetail) {

                        var a_total = objDetail.total.replace(/\,/g, '.');

                        var i_total = arrayObj.item_total.replace(/\,/g, '.');

                        if (arrayObj.currency > 1) {
                            objDetail.total = (parseFloat(a_total) + (parseFloat(i_total) * arrayObj.quotation)).toFixed(2).replace(".", ",");
                        } else {

                            objDetail.total = (parseFloat(a_total) + parseFloat(i_total)).toFixed(2).replace(".", ",");
                        }

                    } else {

                        if (arrayObj.currency > 1) {

                            arrayDetails.push({
                                "id" : arrayObj.type,
                                "total" : (parseFloat(arrayObj.item_total) * arrayObj.quotation).toFixed(2).replace(".", ","),
                            });

                        } else {

                            arrayDetails.push({
                                "id" : arrayObj.type,
                                "total" : arrayObj.item_total,
                            });
                        }

                    }
                } else {
                    if (arrayObj.currency > 1) {

                        arrayDetails.push({
                            "id" : arrayObj.type,
                            "total" : (parseFloat(arrayObj.item_total) * arrayObj.quotation).toFixed(2).replace(".", ","),
                        });

                    } else {

                        arrayDetails.push({
                            "id" : arrayObj.type,
                            "total" : arrayObj.item_total,
                        });
                    }
                }
            }

            var group_item = "";
            for(var i = 0; i < arrayDetails.length; i++) {
                var arrayObj = arrayDetails[i];
                group_item += '<tr>';
                group_item += '<td class="text-left p-1">'+ type(arrayObj.id) +':</td>';
                group_item += '<td class="text-left p-1">BRL '+ arrayObj.total +'</td>';
                group_item += '</tr>';
            }

            $(".list-consum").html(group_item);

            return;
        }

        function resetFields() {
            $("#item_id").val(0);
            $("#type").val(1).change();
            $("#description").val('');
            $("#peoples").val(1);
            $("#currency").val(1).change();
            $("#total").val('');
            $("#date").val('');
            $("#city").val('');
            $("#receipt").val('');
            $("#other").val('');
            $("#receipt_url").html('');
            $("#other_url").html('');
        }

        $('#question').blur(function() {
            $('#squestion').val($(this).val());
        })
        function edit(index) {
            $(".title-item").html('EDITANDO ITEM');

            is_edit = 1;
            index_edit = index;
            var arrayObj = arrayItens[index];
            $("#item_id").val(arrayObj.refund_item_id);
            $("#type").val(arrayObj.type).change();
            $("#description").val(arrayObj.desc_request);
            $("#peoples").val(arrayObj.peoples);
            $("#city").val(arrayObj.city);
            $("#currency").val(arrayObj.currency);
            $("#total").val(arrayObj.item_total);
            $("#date").val(arrayObj.date);
            if (arrayObj.receipt_url != "") {
                $("#receipt_url").show();
                $("#receipt_url").attr('href', arrayObj.receipt_url);
                $("#receipt_url").html(arrayObj.receipt_name);
            } else {
                $("#receipt_url").hide();
            }
            if (arrayObj.other_url != "") {
                $("#other_url").show();
                $("#other_url").attr('href', arrayObj.other_url);
                $("#other_url").html(arrayObj.other_name);
            } else {
                $("#other_url").hide();
            }

            $('#modal-update').modal({
                backdrop: 'static',
                keyboard: false
            });

        }
        function deletes(index) {

            var arrayObj = arrayItens[index];

            Swal.fire({
                title: 'Deletar item',
                text: "Você tem certeza dessa ação?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {

                    var block_ele = $('.list')
                    // Block Element
                    block_ele.block({
                        message:
                            '<div class="bx bx-sync icon-spin font-medium-2 text-primary"></div>',
                        timeout: 2000, //unblock after 2 seconds
                        overlayCSS: {
                            backgroundColor: "#fff",
                            cursor: "wait"
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: "none"
                        }
                    })

                    $.ajax({
                        type: "post",
                        url: "/financy/refund/item/delete",
                        data: {item_id: arrayObj.refund_item_id},
                        success: function (response) {
                            $('.list').unblock();
                            if (response.success) {
                                arrayItens.splice(index, 1);
                                reloadTable();
                                reloadDetails();
                                $(".total_des").html('R$ ' + response.total_des);
                                $(".lending").html('R$ ' + response.lending);
                                $(".total_amount").html('R$ ' + response.total);

                                $(".lending_detail").html('R$ ' + response.lending);
                                $(".total_detail").html('R$ ' + response.total);

                                if (arrayItens.length == 0) {
                                    $(".sendapprov").hide();
                                }
                            } else {
                                alert(response.msg);
                            }
                        },
                        error: function (request, status, error) {
                            $('.list').unblock();
                            alert(request.responseText);
                        }
                    });
                }
            })

        }

        $(document).ready(function () {

            var confirm_term =  localStorage.getItem("refund_confirm_term");
            if(JSON.parse(confirm_term)) {
                $("#termsModal").modal('hide');
            } else {
                $("#termsModal").modal('show');
            }

            $("#btn_confirm_term").click(function() {
                localStorage.setItem("refund_confirm_term", true);
                $("#termsModal").modal('hide');
            });

            /*$("#termsModal").modal({
                keyboard: false,
                backdrop: "static"
            });*/
            $(".js-select2").select2({
                maximumSelectionLength: 1,
            });
            $(".recipient").select2({
                maximumSelectionLength: 1,
            });
            $('.recipient').on('select2:select', function (e) {
                var data = e.params.data;
                $('#r_code').val(data.id);
                $('#agency').val('');
                $('#account').val('');
                $('#bank').val('');
                $('#identity').val('');
                $("#modal-account").modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#srecipient_r_code').val(data.id);
            });
            $('.recipient').on('select2:unselect', function (e) {
                if (Object.keys(request_r_code_account).length > 0) {
                    $('#agency').val(request_r_code_account.agency);
                    $('#account').val(request_r_code_account.account);
                    $('#bank').val(request_r_code_account.bank);
                    $('#identity').val(request_r_code_account.identity);
                }
                $('#r_code').val('');
                $('#srecipient_r_code').val('');
            });
            // function to remove tour on small screen
            window.resizeEvt;
            if ($(window).width() > 576) {
                $('#ActiveTraine').on("click", function () {
                    clearTimeout(window.resizeEvt);
                    tour.start();
                })
            }
            else {
                $('#ActiveTraine').on("click", function () {
                    clearTimeout(window.resizeEvt);
                    tour.cancel()
                    window.resizeEvt = setTimeout(function () {
                        alert("Tour only works for large screens!");
                    }, 250);
                })
            }
            $('[data-toggle="popover"]').popover({
                placement: 'right',
                trigger: 'hover',
            });
            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#mFinancyRefund").addClass('sidebar-group-active active');
                $("#mFinancyRefundNew").addClass('active');
            }, 100);

            $('#total').mask('##.##0,00', {reverse: true});
            $('#date').mask("00/00/0000", {placeholder: "__/__/____"});

            var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('#identity').mask(mask, options);
                }
            };
            $('#identity').mask('000.000.000-009', options);

            $("#clickexport").click(function (e) {
                $("#exportSubmit").submit();

            });

            <?php if ($id != 0) { ?>
            <?php if (isset($itens)) { ?>
            <?php foreach($itens as $item) { ?>

            arrayItens.push({
                "refund_item_id" : <?= $item->id ?>,
                "currency" : <?= $item->currency ?>,
                "quotation" : <?= $item->quotation ?>,
                "type" : <?= $item->type ?>,
                "desc_request" : '<?= preg_replace( "/\r|\n/", " ", $item->description); ?>',
                "peoples" : '<?= $item->peoples ?>',
                "city" : '<?= $item->city ?>',
                "date" : '<?= date('d/m/Y', strtotime($item->date)) ?>',
                "item_total" : '<?= number_format($item->total, 2, ',', '.') ?>',
                "old_total" : '<?= number_format($item->old_total, 2, ',', '.') ?>',
                <?php $attach = App\Model\FinancyRefundItemAttach::where('financy_refund_item_id', $item->id)->get(); ?>
                "receipt_url" : '<?= $attach[0]->url ?>',
                "receipt_name" : '<?= $attach[0]->name ?>',
                <?php if (isset($attach[1])) { ?>
                "other_url" : '<?= $attach[1]->url ?>',
                "other_name" : '<?= $attach[1]->name ?>',
                <?php } else { ?>
                "other_url" : '',
                "other_name" : '',
                <?php } ?>
            });


            <?php } ?>

            if (arrayItens.length > 0) {
                $(".sendapprov").show();
            }
            reloadTable();
            reloadDetails();
            <?php } ?>
            <?php } ?>

            @if ($has_analyze == 0 and $has_approv_or_repprov == 0 or $is_financy == 1)
            $(".lending_click").click(function (e) {
                Swal.fire({
                    title: 'Empréstimo',
                    input: 'text',
                    inputPlaceholder: '0,00',
                    text: "Digite o valor do empréstismo.",
                    type: 'warning',
                    onOpen: function(el) {
                        $('.swal2-input').mask('##.##0,00', {reverse: true});
                    },
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
                    cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        block();
                        $.ajax({
                            type: "POST",
                            data: {id: $("#id").val(), total:result.value},
                            url: "/financy/refund/lending_do",
                            success: function (response) {

                                $("#id").val(response.refund_id);

                                $(".total_des").html('R$ ' + response.total_des);
                                $(".lending").html('R$ ' + response.lending);
                                $(".total_amount").html('R$ ' + response.total);

                                $(".lending_detail").html('R$ ' + response.lending);
                                $(".total_detail").html('R$ ' + response.total);

                                unblock();


                            },
                            error: function (request, status, error) {
                                unblock();
                                alert(request.responseText);
                            }
                        });
                    }
                })




            });
            @endif
            $("#updoradditem").click(function (e) {
                if ($("#peoples").val() == "" || $("#peoples").val() < 1) {

                    return error('Informe ao menos 1 pessoa.');
                } else if ($("#total").val() == "" || $("#total").val() == "0,00") {

                    return error('Preecha o valor total.');
                } else if ($("#date").val() == "") {

                    return error('Você precisa informar a data de consumo');
                } else if ($("#city").val() == "") {

                    return error('Você precisa informar a cidade de consumo');
                } else if ($("#receipt").val() == "" && is_edit == 0) {

                    return error('É necessário adicionar o comprovante.');
                }

                block();
                $('#a_update_form').attr('action', '/financy/refund/edit_do');
                $('#a_update_form').submit();
            });


            // RESPONSIVE FIELDS
            $(window).resize(function() {
                var width = $(window).width();
                if (width <= 380){
                    $("*#mobile-c9-c12").each(function (index, element) {
                        $(element).removeClass().addClass('col-12');

                    });
                    $("*#mobile-c3-c12").each(function (index, element) {
                        $(element).removeClass().addClass('col-12');
                        $(element).removeAttr('style');

                    });
                } else {
                    $("*#mobile-c9-c12").each(function (index, element) {
                        $(element).removeClass().addClass('col-9');

                    });
                    $("*#mobile-c3-c12").each(function (index, element) {
                        $(element).removeClass().addClass('col-3');
                        $(element).attr('style', 'position: fixed; right: 0');

                    });
                }
            });


            if ($(window).width() <= 380)
            {
                $("*#mobile-c9-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-12');

                });
                $("*#mobile-c3-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-12');
                    $(element).removeAttr('style');

                });
            } else {
                $("*#mobile-c9-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-9');

                });
                $("*#mobile-c3-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-3');
                    $(element).attr('style', 'position: fixed; right: 0');

                });
            }

            $("#UpdateAccount").submit(function (e) {
                if ($("#agency").val() == "") {
                    error('<?= __('lending_i.lrn_43') ?>');
                    e.preventDefault();
                } else if ($("#account").val() == "") {

                    error('<?= __('lending_i.lrn_44') ?>');
                    e.preventDefault();
                } else if ($("#bank").val() == "") {

                    error('<?= __('lending_i.lrn_45') ?>');
                    e.preventDefault();
                } else if ($("#identity").val() == "") {

                    error('<?= __('lending_i.lrn_46') ?>');
                    e.preventDefault();
                } else {

                    $.ajax({
                        type: "POST",
                        url: "/financy/lending/bank_upd",
                        data: {
                            agency: $("#agency").val(),
                            account: $("#account").val(),
                            bank: $("#bank").val(),
                            identity: $("#identity").val(),
                            r_code: $("#r_code").val(),
                        },
                        success: function (response) {
                            success('<?= __('lending_i.lrn_47') ?>');
                            $("#modal-account").modal('toggle');
                        }
                    });

                    e.preventDefault();
                }


            });

            $("#HAnalyze").click(function (e) {
                $("#Analyze").hide();

            });

            $("#showAnalyze").click(function (e) {
                $("#Analyze").show();

            });

            $("#newItem").click(function (e) {
                $(".title-item").html('NOVO ITEM');

            });

        });
    </script>
@endsection
