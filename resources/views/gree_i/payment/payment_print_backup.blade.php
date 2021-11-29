@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/shepherd-theme-default.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/tour/tour.min.css">
<link rel="stylesheet" type="text/css" href="/admin/assets/css/table_custom.css">
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Pagamento</h5>
                  <div class="breadcrumb-wrapper col-12">
                    @if ($id == 0)
                    Nova solicitação 
                    @else
                    Atualizar solicitação
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <form action="/financy/payment/edit_do" id="submitEdit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="payment_method" id="payment_method">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <section class="request-payment">
                <div class="card">
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                              <thead>
                              </thead>
                              <tbody>
                                <tr>
                                  <td colspan="7">
                                      <img class="img-fluid" src="/admin/app-assets/images/logo/logo_gree_blue.png" alt="">
                                      <br><h4 class="ml-4 font-weight-bold">SOLICITAÇÃO DE CONTABILIZAÇÃO E PAGAMENTO</h4>
                                  </td>
                                  <td>
                                    Data: <b><?= $created_at ?></b>
                                  </td>
                                </tr>
                                <tr>
                                    <td>Depto Solicitante:</td>
                                    <td class="text-bold-500"><?= $sector_name ?></td>
                                    <td>Solicitante</td>
                                    <td colspan="2" class="text-bold-500"><?= getENameF($request_r_code) ?></td>
                                    <td>NF</td>
                                    <td colspan="2" class="text-bold-500">
                                        <input type="text" class="input-table" name="nf_nmb" id="nf_nmb" style="text-transform: uppercase;" value="<?= $nf_nmb ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Conteúdo:</td>
                                    <td colspan="7" class="text-bold-500">
                                        <input type="text" class="input-table" name="desc_request" id="desc_request" value="<?= $description ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Fornecedor/Beneficiário</td>
                                    <td colspan="7" class="text-bold-500" >
                                            <div id="changer_ben">
                                                <select class="js-select2" id="recipient" name="recipient" style="width: 100%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                                    <?php foreach ($userall as $key) { ?>
                                                        <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?></option>
                                                    <?php } ?>
                                                    <option value="99">OUTRO BENFECIÁRIO</option>
                                                </select>
                                            </div>
                                            <input type="text" class="input-table" id="recipient_other" name="recipient_other" style="display:none;" placeholder="digite aqui">
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">Valor total da Nota Fiscal</td>
                                    <td>Valor Bruto (R$)</td>
                                    <td colspan="7" class="text-bold-500 text-right">
                                        <fieldset class="form-label-group form-group position-relative has-icon-left m-0 float-right" style="width: 160px;">
                                            <input type="text" class="input-table form-control" id="amount-total" name="amount-total" value="<?php if ($total) { ?><?= number_format($total,2, ',', '.') ?> <?php } ?>" placeholder="0,00">
                                            <div class="form-control-position text-table" style="top: 1px;">
                                                R$
                                            </div>
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-bold-500" id="amount-total-word">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Solicitante</td>
                                    <td colspan="2" class="text-bold-500">
                                        <?= getENameF($request_r_code) ?> (<?= $request_r_code ?>)
                                    </td>
                                    <td>Gerente Dpt</td>
                                    <td colspan="2" class="text-bold-500">
                                        
                                    </td>
                                    <td>Recebedor</td>
                                    <td colspan="1" class="text-bold-500">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Vencimento</td>
                                    <td colspan="7" class="text-bold-500 text-center">
                                         <input type="text" class="input-table text-center" name="date_end" value="<?= $date_end ?>" id="date_end">
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">Valor total para pagamento</td>
                                    <td>Valor Liquido (R$)</td>
                                    <td colspan="7" class="text-bold-500 text-right">
                                        <fieldset class="form-label-group form-group position-relative has-icon-left m-0 float-right" style="width: 160px;">
                                            <input type="text" class="input-table form-control" id="amount-liquid" name="amount-liquid" value="<?php if ($liquid) { ?><?= number_format($liquid,2, ',', '.') ?> <?php } ?>" placeholder="0,00">
                                            <div class="form-control-position text-table" style="top: 1px;">
                                                R$
                                            </div>
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-bold-500" id="amount-liquid-word">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Verificador Fiscal</td>
                                    <td class="text-bold-500">
                                        
                                    </td>
                                    <td>Verificador Contábil</td>
                                    <td class="text-bold-500">
                                        
                                    </td>
                                    <td>Gerente financeiro</td>
                                    <td class="text-bold-500">
                                        
                                    </td>
                                    <td>Diretor</td>
                                    <td class="text-bold-500">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2" colspan="6" class="text-center">
                                        <textarea name="optional" id="optional" class="area-table" cols="60" placeholder="Informação adicional..." rows="5"><?= $optional ?></textarea>
                                    </td>
                                    <td rowspan="2" colspan="2" id="selectm">
                                        <p><span id="payment_1" class="cursor-pointer">( )</span> Boleto</p> 
                                        <p><span id="payment_2" class="cursor-pointer">( )</span> Transferência / D.Automático</p>
                                        <p><span id="payment_3" class="cursor-pointer">( )</span> Caixa</p> 
                                    </td>
                                </tr>
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="request-files">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Anexar multiplos arquivos</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <p class="card-text">Caso precise justificar a solicitação de pagamento, poderá anexar. Para pedidos de reembolso não há necessidade de anexo, apenas crie uma nova solicitação de reembolso no módulo e vincule essa solicitação de pagamento. (max 10mb)</p>
                        <div class="form-group">
                            <label for="attach">Arquivo de justificativa</label>
                            <input type="file" class="form-control" id="files[]" name="files[]" multiple="">
                            <p>
                                <small class="text-muted">Segure o Ctrl e clique sobre os arquivos que irá enviar. Max de (20mb)</small>
                            </p>
                        </div>
                        <div class="form-group">
                            <ul class="list-inline">
                                @if ($files)
                                @foreach ($files as $key)
                                <a href="<?= $key->url ?>"><li class="list-inline-item"><?= $key->id ?>. <?= $key->name ?> (<?= readableBytes($key->size) ?>)</li></a>   
                                @endforeach  
                                @endif
                            </ul>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                
            </section>

            <section class="request-bank">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Dados bancários</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="agency">Agência</label>
                                    <input type="text" id="agency" name="agency" value="<?= $agency ?>" class="form-control" placeholder="0000">
                                </fieldset>
                            </div>

                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="account">Conta</label>
                                    <input type="text" id="account" name="account" value="<?= $account ?>" class="form-control" placeholder="000000-0">
                                </fieldset>
                            </div>

                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="bank">Banco</label>
                                    <input type="text" id="bank" name="bank" value="<?= $bank ?>" class="form-control" placeholder="Bradesco">
                                </fieldset>
                            </div>

                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="identity">CPF/IDENTIDADE</label>
                                    <input type="text" id="identity" name="identity" value="<?= $identity ?>" class="form-control" placeholder="00000000000">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="cnpj">CNPJ (Apenas para empresa)</label>
                                    <input type="text" id="cnpj" name="cnpj" value="<?= $cnpj ?>" class="form-control" placeholder="00000000000">
                                </fieldset>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                
            </section>

            <button type="submit" id="NewRequestPayment" class="btn btn-primary">@if ($id == 0)
                Criar solicitação de pagamento  
                @else
                Atualizar solicitação
                @endif</button>

        </form>
        </div>
    </div>
    <div class="customizer d-none d-md-block" id="ActiveTraine">
        <a class="customizer-toggle" href="#"><i class="bx bx-question-mark white"></i></a>
    </div>
    <script src="/admin/app-assets/vendors/js/extensions/shepherd.min.js"></script>
    <script src="/js/StepsTour.js"></script>
    <script>
        // tour initialize
        var tour = new Shepherd.Tour({
            classes: 'shadow-md bg-purple-dark',
            scrollTo: true
        });

        AddSteps(1, 'Digite o número da nota, se caso for reembolso deixe como CONTABILIZADO.', '#nf_nmb bottom');
        AddSteps(2, 'Essa descrição é para reembolso, mas se caso for algo diferente, digita sua própria descrição.', '#desc_request bottom');
        AddSteps(3, 'Escolha a pessoa cadastrada no sistema ou se quiser digitar, escolha a opção OUTRO. Caso queira voltar para opção de escolha é só apagar todo conteúdo escrito.', '#changer_ben bottom');
        AddSteps(4, 'Digite o valor bruto total.', '#amount-total bottom');
        AddSteps(5, 'Digite a data de vencimento no formato brasileiro.', '#date_end bottom');
        AddSteps(6, 'Digite o valor liquido com base nas taxas se houver.', '#amount-liquid bottom');
        AddSteps(7, 'Caso tenha alguma informação adicional é só informar, não precisa adicionar sua conta bancária, pode adicionar sua conta na criação de um novo empréstimo.', '#optional bottom');
        AddSteps(8, 'Escolha uma opção de pagamento.', '#selectm bottom');
        AddSteps(9, 'Para justificar sua solicitação para casos que não seja reembolso é ideal enviar os arquivos. Não ultrapasse 10mb de arquivos!', '.request-files top');
        AddSteps(0, 'Preencha os dados bancários para o recebimento do pagamento após aprovação.', '.request-bank top');

        function getWords(obj, amout) {
            $.ajax({
                type: "GET",
                timeout: 10000,
                url: "/misc/currency-to-words?amount=" + amout,
                success: function (response) {
                    if (response.success) {
                        obj.html(response.words);
                    }
                }
            });
        }
        $(document).ready(function () {
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
            $(".js-select2").select2({
                maximumSelectionLength: 1,
            });

            $('#amount-total').mask('#.###.##0,00', {reverse: true});
            $('#amount-liquid').mask('#.###.##0,00', {reverse: true});
            $('#date_end').mask("00/00/0000", {placeholder: "__/__/____"});

            <?php if ($total) {?>
                getWords($("#amount-total-word"), '<?= number_format($total,2, ',', '.') ?>');
            <?php } ?>
            $("#amount-total").on('blur', function () {
                getWords($("#amount-total-word"), $("#amount-total").val());
            });
            
            <?php if ($liquid) {?>
                getWords($("#amount-liquid-word"), '<?= number_format($liquid,2, ',', '.') ?>');
            <?php } ?>
            $("#amount-liquid").on('blur', function () {
                getWords($("#amount-liquid-word"), $("#amount-liquid").val());
            });

            $('.js-select2').on('select2:select', function (e) {
                if (e.params.data.id == "99") {
                    $("#recipient_other").show();
                    $("#changer_ben").hide();
                    $("#agency").val("");
                    $("#account").val("");
                    $("#bank").val("");
                    $("#identity").val("");
                } else {
                    $.ajax({
                    type: "GET",
                    timeout: 10000,
                    url: "/misc/user/bank?rcode=" + e.params.data.id,
                    success: function (response) {
                        if (response.success) {
                            $("#agency").val(response.agency);
                            $("#account").val(response.account);
                            $("#bank").val(response.bank);
                            $("#identity").val(response.identity);
                            success('Dados da conta incluidos automaticamente. Se necessário pode alterar.')
                        } else {
                            $("#agency").val("");
                            $("#account").val("");
                            $("#bank").val("");
                            $("#identity").val("");
                            error('Esse usuário ainda não tem uma conta cadastrada. Insira os dados manualmente abaixo.')
                        }
                    }
                });
                }
            });

            <?php if (!empty($recipient_r_code)) { ?>
                $('.js-select2').val(['<?= $recipient_r_code ?>']).trigger('change');
            <?php } ?>

            <?php if (empty($recipient_r_code)) { ?>
                $('.js-select2').val(['99']).trigger('change');
                $("#recipient_other").show();
                $("#changer_ben").hide();
                $("#recipient_other").val('<?= $recipient ?>');
            <?php } ?>

            $("#recipient_other").on('blur', function () {
                if ($('#recipient_other').val() == "") {
                    $("#recipient_other").hide();
                    $("#changer_ben").show();
                }
            });

            $("#changer_ben").click(function (e) { 
                $('.js-select2').val(0).trigger("change");
                
            });

            <?php if ($payment_method) { ?>
                <?php if ($payment_method == 1) { ?>
                    $("#payment_method").val(1);
                    $("#payment_1").html("(X)");
                <?php } else if ($payment_method == 2) { ?>
                    $("#payment_method").val(2);
                    $("#payment_2").html("(X)");
                <?php } else if ($payment_method == 3) { ?>
                    $("#payment_method").val(3);
                    $("#payment_3").html("(X)");
                <?php } ?>
            <?php } ?>

            $("#payment_1").click(function (e) { 
                $("#payment_1").html("(X)");
                $("#payment_2").html("( )");
                $("#payment_3").html("( )");
                $("#payment_method").val(1);
            });
            $("#payment_2").click(function (e) { 
                $("#payment_1").html("( )");
                $("#payment_2").html("(X)");
                $("#payment_3").html("( )");
                $("#payment_method").val(2);
            });
            $("#payment_3").click(function (e) { 
                $("#payment_1").html("( )");
                $("#payment_2").html("( )");
                $("#payment_3").html("(X)");
                $("#payment_method").val(3);
            });

            $("#NewRequestPayment").click(function (e) {
                
                if ($("#nf_nmb").val() == "") {
                    e.preventDefault();
                    return error('Digite o número da nota fiscal ou informe "CONTABILIZADO" para reembolso.');
                } else if ($("#desc_request").val() == "") {
                    e.preventDefault();
                    return error('Informe a descrição da solicitação na parte de conteúdo.');
                } else if ($('.js-select2').val()[0] == undefined) {
                    e.preventDefault();
                    return error('Você deve informar o beneficiário ou fornecedor.');
                } else if ($('.js-select2').val()[0] == "99") {
                    if ($("#recipient_other").val() == "") {
                        e.preventDefault();
                        return error('Você deve informar o beneficiário ou fornecedor.');
                    }
                }
                
                if ($("#amount-total").val() == "") {
                    e.preventDefault();
                    return error('Digite o valor total bruto da solicitação.');
                } else if ($("#date_end").val() == "") {
                    e.preventDefault();
                    return error('Inclua uma data de vencimento de no minimo 7 dias.');
                } else if ($("#amount-liquid").val() == "") {
                    e.preventDefault();
                    return error('Digite o valor liquido da solicitação.');
                } else if ($("#payment_method").val() == "") {
                    e.preventDefault();
                    return error('Você precisa escolher um metódo de pagamento.');
                } else if ($("#agency").val() == "") {
                    e.preventDefault();
                    return error('Informe sua agência bancária.');
                } else if ($("#account").val() == "") {
                    e.preventDefault();
                    return error('Informe a conta da sua agência.');
                } else if ($("#bank").val() == "") {
                    e.preventDefault();
                    return error('Informe o banco da sua conta.');
                } else if ($("#identity").val() == "") {
                    e.preventDefault();
                    return error('Informe um documento do beneficiário/fornecedor.');
                }

                $("#submitEdit").submit(function (e) { 
                    block();
                });
                
            });
        });
    </script>
@endsection