@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/shepherd-theme-default.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/tour/tour.min.css">
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
          @if ($id == 0)
          <div class="alert alert-danger alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error"></i>
                <span>
                Ao criar a solicitação, você irá enviar para aprovação automáticamente, então se atente a todos os dados e informações. 
                <br>Caso queira editar mediante análise, entre em contato com o seu imediato para realizar alteração.
                </span>
            </div>
            </div>
            <div class="alert alert-info alert-dismissible mb-2" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error"></i>
                    <span>
                    Peça para seu gestor aprovar o quanto antes, pois para pedidos que restam 6 dias para o vencimento, será necessário aprovação
                    <br>do gerente financeiro.
                    </span>
                </div>
                </div>
          @endif
        <div class="content-header row">
        </div>
        <div class="content-body">
            <form action="/financy/payment/edit_do" id="submitEdit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <section class="request-payment">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">SOLICITAÇÃO DE CONTABILIZAÇÃO E PAGAMENTO</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="recipient">Categoria de despesas</label>
                                    <select class="form-control" id="request_category" name="request_category">
                                        @foreach (getFinancyRequestCategory() as $category)
                                            <option value="{{$category['value']}}" @if ($request_category == $category['value']) selected @endif>{{$category['desc']}}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="nf_nmb">Nota fiscal</label>
                                    <input type="text" id="nf_nmb" name="nf_nmb" style="text-transform: uppercase;" value="<?= $nf_nmb ?>" class="form-control" placeholder="CONTABILIZADO">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="desc_request">Conteúdo</label>
                                    <input type="text" id="desc_request" name="desc_request" style="text-transform: uppercase;" value="<?= $description ?>" class="form-control" placeholder="REEMBOLSO DE DESPESAS COMERCIAIS">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="recipient">Fornecedor/Beneficiário</label>
                                    <div id="changer_ben">
                                        <select class="js-select2 form-control" id="recipient" name="recipient" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                            <?php foreach ($userall as $key) { ?>
                                                <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                            <?php } ?>
                                            <option value="99">OUTRO BENFECIÁRIO</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control" id="recipient_other" name="recipient_other" style="text-transform: uppercase; display:none;" placeholder="digite aqui">
                                </fieldset>
                            </div>

                            @if ($id != 0 and $is_paid == 0 and $pres_approv == 1)
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="recipient">Recebebor</label>
                                    <select class="js-select21 form-control" id="receiver" name="receiver" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                        <?php foreach ($userall as $key) { ?>
                                            <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                        <?php } ?>
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="recipient">Verificador fiscal</label>
                                    <select class="js-select22 form-control" id="supervisor" name="supervisor" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                        <?php foreach ($userall as $key) { ?>
                                            <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                        <?php } ?>
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="recipient">Verificador contábil</label>
                                    <select class="js-select23 form-control" id="accounting" name="accounting" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                        <?php foreach ($userall as $key) { ?>
                                            <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                        <?php } ?>
                                    </select>
                                </fieldset>
                            </div>
                            @endif

                            <div class="col-md-12">
                                <label for="amount-total">Valor Bruto</label>
                                <fieldset class="form-label-group form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="amount-total" name="amount-total" value="<?php if ($total) { ?><?= number_format($total,2, ',', '.') ?> <?php } ?>" placeholder="0,00">
                                    <div class="form-control-position text-table" style="top: 1px;">
                                        R$
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <label for="amount-liquid">Valor Liquido</label>
                                <fieldset class="form-label-group form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" id="amount-liquid" name="amount-liquid" value="<?php if ($liquid) { ?><?= number_format($liquid,2, ',', '.') ?> <?php } ?>" placeholder="0,00">
                                    <div class="form-control-position text-table" style="top: 1px;">
                                        R$
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <label for="date_end">Data de vencimento</label>
                                <fieldset class="form-group">
                                    <input type="text" id="date_end" name="date_end" value="<?= $date_end ?>" class="form-control">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="optional">Informação adicional</label>
                                    <textarea name="optional" id="optional" class="form-control" cols="60" placeholder="Informação adicional..." rows="5"><?= $optional ?></textarea>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <ul class="list-unstyled mb-0 border p-2">
                                    <li class="d-inline-block mr-2">
                                      <fieldset>
                                        <div class="custom-control custom-radio">
                                          <input type="radio" class="custom-control-input" value="1" <?php if ($payment_method == 1) { ?> checked=""<?php } else { ?><?php } ?> name="payment_method" id="boleto">
                                          <label class="custom-control-label" for="boleto">Boleto</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2">
                                      <fieldset>
                                        <div class="custom-control custom-radio">
                                          <input type="radio" class="custom-control-input" value="2" <?php if ($payment_method == 2) { ?> checked=""<?php } else { ?><?php } ?> name="payment_method" id="tranfer" checked="">
                                          <label class="custom-control-label" for="tranfer">Transferência / D.Automático</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2">
                                        <fieldset>
                                          <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="3" <?php if ($payment_method == 3) { ?> checked=""<?php } else { ?><?php } ?> name="payment_method" id="caixa">
                                            <label class="custom-control-label" for="caixa">Caixa</label>
                                          </div>
                                        </fieldset>
                                      </li>
                                </ul>
                            </div>
                        </div>
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
                        <p class="card-text">Caso precise justificar a solicitação de pagamento ou simplesmente por um boleto, poderá anexar.</p>
                        <div class="form-group">
                            <label for="attach">Arquivo(s) anexado(s)</label>
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
                language: {
                    noResults: function () {
                        $("#recipient_other").val($(".select2-search__field").val());
                        $(".select2-search__field").val('');
                        $("#recipient_other").show();
                        $( "#recipient_other" ).focus();
                        $("#changer_ben").hide();
                        $('.js-select2').val(['99']).trigger('change');
                        return;
                    }
                }
            });
            $(".js-select21").select2({
                maximumSelectionLength: 1,
            });
            $(".js-select22").select2({
                maximumSelectionLength: 1,
            });
            $(".js-select23").select2({
                maximumSelectionLength: 1,
            });

            $('#amount-total').mask('#.###.##0,00', {reverse: true});
            $('#amount-liquid').mask('#.###.##0,00', {reverse: true});
            $('#date_end').mask("00/00/0000", {placeholder: "__/__/____"});

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

            <?php if (!empty($recipient_r_code) and $id != 0) { ?>
                $('.js-select2').val(['<?= $recipient_r_code ?>']).trigger('change');
            <?php } ?>
            @if ($id != 0 and $is_paid == 0 and $pres_approv == 1)
                $('.js-select21').val(['<?= $receiver ?>']).trigger('change');
                $('.js-select22').val(['<?= $supervisor ?>']).trigger('change');
                $('.js-select23').val(['<?= $accounting ?>']).trigger('change');
            @endif

            <?php if (empty($recipient_r_code) and $id != 0) { ?>
                $('.js-select2').val(['99']).trigger('change');
                $("#recipient_other").show();
                $("#changer_ben").hide();
                $("#recipient_other").val('<?= $recipient ?>');
            <?php } ?>

            $("#recipient_other").on('keyup', function () {
                if ($('#recipient_other').val() == "") {
                    $("#recipient_other").hide();
                    $("#changer_ben").show();
                    $(".select2-search__field").focus();
                    $('.js-select2').val(0).trigger("change");
                }
            });

            $("#changer_ben").click(function (e) { 
                $('.js-select2').val(0).trigger("change");
                
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

            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#mFinancyPayment").addClass('sidebar-group-active active');
                $("#mFinancyPaymentNew").addClass('active');
                
            }, 100);
        });
    </script>
@endsection