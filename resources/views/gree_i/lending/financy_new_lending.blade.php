@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_lending') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('layout_i.menu_lending_new') }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php if (isset($ac_bank)) { ?>
        <?php if ($ac_bank->used_credit > 0) { ?>
            <div class="alert alert-danger alert-dismissible mb-2 has-used" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error"></i>
                    <span>
                        <p class="mb-0">{{ __('lending_i.lrn_1') }}</p>
                    </span>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if (!isset($ac_bank)) { ?>
        <div class="alert alert-warning alert-dismissible mb-2 limit-default" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error"></i>
                <span>
                    <p class="mb-0">{{ __('lending_i.lrn_3') }}</p>
                </span>
            </div>
        </div>
    <?php } ?>
    <div class="alert alert-danger alert-dismissible mb-2 limit-exc" style="display:none" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error"></i>
            <span>
                <p class="mb-0">{{ __('lending_i.lrn_4') }}</p>
            </span>
        </div>
    </div>

    <div class="alert alert-warning alert-dismissible mb-2 money-get" style="display:none" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error"></i>
            <span>
                <p class="mb-0">{{ __('lending_i.lrn_51') }}</p>
            </span>
        </div>
    </div>

    @if ($already_loan)
    <div class="alert alert-danger alert-dismissible mb-2" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error"></i>
            <span>
                <p class="mb-0">Você já tem um empréstimo em aberto no valor de R$ <b>{{number_format($already_loan->amount, 2, ',', '.')}}</b>, criado em <b>{{$already_loan->created_at->format('d-m-Y')}}</b>, Código: <b>{{$already_loan->code}}</b>, veja o empréstimo: <a href="/financy/lending/my">Clique aqui</a>. Realmente precisa criar um novo?</p>
            </span>
        </div>
    </div>
    @endif

    <div class="content-header row">
    </div>
    <div class="content-body">
        <form action="/financy/lending/new_do" id="sendLending" method="post" enctype="multipart/form-data">
        <div class="row"> 
            <div class="col-md-9">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                                <div class="form-group">
                                    <label for="type_data">{{ __('lending_i.lrn_5') }}</label>
                                    <select class="form-control" id="type_data" name="type_data">
                                        <option value="">{{ __('lending_i.lrn_6') }}</option>
                                        <?php if (isset($trips)) { ?>
                                            <option value="1">{{ __('lending_i.lrn_7') }}</option>
                                        <?php } ?>
                                        <option value="99">{{ __('lending_i.lrn_8') }}</option>
                                    </select>
                                </div>

                                <div class="form-group trip" style="display: none">
                                    <label>{{ __('lending_i.lrn_9') }}</label>
                                    <select class="form-control" id="trip" name="trip">
                                        <option value="">{{ __('lending_i.lrn_6') }}</option>
                                        <?php if (isset($trips)) { ?>
                                            <?php foreach ($trips as $key) { ?>
                                                <option value="<?= $key->id ?>">{{ __('lending_i.lrn_10') }} #<?= $key->id ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group attach" style="display:none">
                                    <label>{{ __('lending_i.lrn_11') }}</label>
                                    <input type="file" name="file" id="file" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="p_method">{{ __('lending_i.lrn_50') }}</label>
                                    <select class="form-control" id="p_method" name="p_method">
                                        <option value="2" selected>{{ __('lending_i.lrn_48') }}</option>
                                        <option value="3">{{ __('lending_i.lrn_49') }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="total">{{ __('lending_i.lrn_12') }}</label>
                                    <input type="text" class="form-control" value="" id="total" name="total" placeholder="0.00">
                                </div>

                                <div class="form-group">
                                    <label for="description">{{ __('lending_i.lrn_13') }}</label>
                                        <textarea class="form-control" id="description" name="description" rows="6" placeholder="{{ __('lending_i.lrn_14') }}"></textarea>
                                </div>

                                <div class="table-responsive push mt-20">
                                    <table class="table table-bordered table-hover">
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="font-w600 text-right">{{ __('lending_i.lrn_15') }}</td>
                                                <?php if (isset($ac_bank)) { ?>
                                                <td class="text-right limit">R$ <?= number_format($ac_bank->limit_credit, 2, '.', '') ?></td>
                                                <?php } else { ?>
                                                    <td class="text-right limit">R$ 2000.00</td>
                                                <?php } ?>
                                            </tr>
                                            <tr <?php if (isset($ac_bank)) { ?><?php if ($ac_bank->used_credit > 0) { ?>style="display:none"<?php } else if ($ac_bank->used_credit < 0) { echo 'class="table-success"'; } else { echo ''; } } ?>>
                                                <td colspan="4" class="font-w600 text-right">{{ __('lending_i.lrn_16') }}</td>
                                                <td class="text-right pos">R$ 0.00</td>
                                            </tr>
                                            <?php if (isset($ac_bank)) { ?>
                                                <?php if ($ac_bank->used_credit > 0) { ?>
                                                    <tr class="table-danger">
                                                        <td colspan="4" class="font-w600 text-right">{{ __('lending_i.lrn_17') }}</td>
                                                        <td class="text-right empre_ant">R$ <?= number_format($ac_bank->used_credit, 2, '.', '') ?></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="4" class="font-w600 text-right">{{ __('lending_i.lrn_18') }}</td>
                                                <td class="text-right empre">R$ 0.00</td>
                                            </tr>
                                            <tr class="table-warning" id="status-total">
                                                <td colspan="4" class="font-w700 text-uppercase text-right">{{ __('lending_i.lrn_19') }}</td>
                                                <td class="font-w700 text-right total">R$ 0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <button type="button" id="sendLendingBtn" class="btn btn-square btn-primary" style="width: 100%;">{{ __('lending_i.lrn_20') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="font-w600 mb-1"><?= Session::get('first_name') ?> <?= Session::get('last_name') ?></div>
                                        <div id="noaccountbank" class="text-center mb-1" <?php if (isset($ac_bank)) { ?>style="display:none"<?php } ?>>
                                            {{ __('lending_i.lrn_21') }}
                                        </div>
                                        <div id="accountbank" class="text-center mb-1" <?php if (!isset($ac_bank)) { ?>style="display:none"<?php } ?>>
                                            <div class="font-size-sm text-muted agency"><b>{{ __('lending_i.lrn_22') }}</b> <?php if (isset($ac_bank)) { ?><?= $ac_bank->agency ?><?php } ?> </div>
                                            <div class="font-size-sm text-muted account"><b>{{ __('lending_i.lrn_23') }}</b> <?php if (isset($ac_bank)) { ?><?= $ac_bank->account ?><?php } ?> </div>
                                            <div class="font-size-sm text-muted bank"><b>{{ __('lending_i.lrn_24') }}</b> <?php if (isset($ac_bank)) { ?><?= $ac_bank->bank ?><?php } ?> </div>
                                            <div class="font-size-sm text-muted identity"><b>{{ __('lending_i.lrn_25') }}</b> <?php if (isset($ac_bank)) { ?><?= $ac_bank->identity ?><?php } ?> </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-account">{{ __('lending_i.lrn_26') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                                <!-- ValutaFX CURRENCY CONVERTER START -->
                                <div style='width:100%;*width:250px;max-width:250px;margin:0px auto;padding:0px;border:1px solid #01698C;background-color:#FFFFFF;'>
                                <div style='width:100%;*width:250px;max-width:250px;text-align:center;padding:10px 0px;background-color:#01698C;font-family:arial;font-size:16px;color:#FFFFFF;font-weight:bold;vertical-align:middle;'>Conversor de Moedas</div>
                                <div style='padding:10px;'>
                                <script type='text/javascript' charset='utf-8'>if(typeof vfxIdx==='undefined')vfxIdx=0;vfxIdx++;document.write("<div id='vfxWidget"+vfxIdx+"'><"+"/div>");document.write("<script async type='text/javascript' src='https://widgets.valutafx.com/ConverterWidgetLoader.aspx?sid=CC00BQ72G&idx="+vfxIdx+"' charset='utf-8'></" + "script>");</script>
                                <div style='overflow: hidden;'>
                                <div style='float:left; text-align: left;'><noindex><a title='Add this free widget to your website!' style='font-size:12px;color:#007EAA;text-decoration: none;' href='https://pt.valutafx.com/AddConverter.aspx?sid=CC00BQ72G' target='_blank' rel='nofollow'>Adicionar ao seu site</a></noindex></div>
                                <div style='float:right; text-align: right;'><a style='font-size:12px;color:#777777;text-decoration: none;opacity: 0.6;filter: alpha(opacity=60);' href='https://pt.valutafx.com/' target='_blank'>pt.valutafx.com</a></div></div>         
                                </div>
                                </div>
                                <!-- ValutaFX CURRENCY CONVERTER END -->
                        </div>

                    </div>
                </div>

            </div>
                        
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<div class="modal fade text-left" id="modal-account" tabindex="-1" role="dialog" aria-labelledby="modal-account" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modal-account">{{ __('lending_i.lrn_27') }}</h3>
          <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
            <form id="UpdateAccount" action="#" method="post">
                <div class="form-group">
                    <label for="agency">{{ __('lending_i.lrn_28') }}</label>
                    <input class="form-control" type="text" name="agency" id="agency" value="<?php if (isset($ac_bank)) { ?><?= $ac_bank->agency ?><?php } ?>">
                </div>
                <div class="form-group">
                    <label for="account">{{ __('lending_i.lrn_29') }}</label>
                    <input class="form-control" type="text" name="account" id="account" value="<?php if (isset($ac_bank)) { ?><?= $ac_bank->account ?><?php } ?>">
                </div>
                <div class="form-group">
                    <label for="bank">{{ __('lending_i.lrn_30') }}</label>
                    <input class="form-control" type="text" name="bank" id="bank" value="<?php if (isset($ac_bank)) { ?><?= $ac_bank->bank ?><?php } ?>">
                </div>
                <div class="form-group">
                    <label for="identity">{{ __('lending_i.lrn_31') }}</label>
                    <input class="form-control" type="text" name="identity" id="identity" value="<?php if (isset($ac_bank)) { ?><?= $ac_bank->identity ?><?php } ?>">
                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('lending_i.lrn_32') }}</span>
          </button>
          <button type="submit" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('lending_i.lrn_33') }}</span>
          </button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade text-left" id="modal-module" tabindex="-1" role="dialog" aria-labelledby="modal-module" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-full" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modal-module">{{ __('lending_i.lrn_34') }}</h3>
          <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body module-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('lending_i.lrn_35') }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>


<div class="modal fade text-left" id="termsModal" tabindex="-1" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Termos & Condições</h5>
            </div>
            <div class="modal-body">
                <p  style="text-transform:uppercase; text-align: justify">REQUISITOS DO EMPRESTIMO</p>
                <ol style="text-align:justify">
                    <li>Solicitação de empréstimo deve ser utilizado para cobrir pequenas despesas eventuais, compras em caráter de urgência e custas de viagens de negócio;</li>
                    <li>O prazo médio para verificação e aprovação financeira é de até 2 (dois) dias uteis contados da data de aprovação do gestor solicitante;</li>
                    <li>O prazo médio de pagamento é de até 2 (dois) dias úteis após a aprovação da diretoria;</li>
                    <li>Atender ao prazo para liquidação e prestação de contas de acordo com a política:
                      <ol>
                        <li>Viagens domésticas >> 45 dias;</li>
                        <li>Viagens exterior >> 60 dias;</li>
                        <li>Aquisição de material e equipamentos com NF GREE >> Dentro do mesmo mês ao da emissão da NF;</li>
						<li>Demais empréstimos >> 30 dias;</li>
                      </ol>
                    </li>        
                    <li>Quando do não cumprimento dos prazos de prestação de contas, os débitos serão enviados para tratativas de desconto em folha de pagamento;</li>
					<li>Não será concedido novo empréstimo se houver qualquer pendencia financeira.</li>
                  </ol>                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary ml-1" id="btn_confirm_term">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block " style="text-transform: uppercase"> estou de acordo</span>
                </button>
            </div>
            </div>
        </div>
    </div>    

    <script>
        var limit = <?php if (isset($ac_bank)) { ?><?= number_format($ac_bank->limit_credit, 2, '.', '') ?><?php } else { ?>2000.00<?php } ?>;
    var pos = <?php if (isset($ac_bank)) { ?><?php if ($ac_bank->used_credit < 0) { ?><?= number_format(abs($ac_bank->used_credit), 2, '.', '') ?><?php } else { ?>0.00<?php } ?><?php } else { ?>0.00<?php } ?>;
    var empre = 0.00;
    var empre_ant = <?php if (isset($ac_bank)) { ?><?php if ($ac_bank->used_credit > 0) { ?><?= number_format($ac_bank->used_credit, 2, '.', '') ?><?php } else { ?>0.00<?php } ?><?php } else { ?>0.00<?php } ?>;
    var total = 0.00;
    var fileName = "";
    var has_bank = <?php if (isset($ac_bank)) { ?>1<?php } else { ?>0<?php } ?>;

    $(document).ready(function () {
			
		var confirm_term =  localStorage.getItem("lending_confirm_term");
        if(JSON.parse(confirm_term)) {
            $("#termsModal").modal('hide');
        } else {
            $("#termsModal").modal('show');
        }
		
		$("#btn_confirm_term").click(function() {
            localStorage.setItem("lending_confirm_term", true);
            $("#termsModal").modal('hide');
        });
		
        $(".pos").html('R$ ' + pos.toFixed(2));
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        $('#total').mask('00000.00', {reverse: true});
        var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('#identity').mask(mask, options);
                }
            };
        $('#identity').mask('000.000.000-009', options);

        $("#total").on("input", function(){

            $(".empre").html('R$ ' + $("#total").val());
            empre = $("#total").val();
            total = (empre - pos) + empre_ant;
            if (pos > empre) {
                $("#status-total").removeClass().addClass('table-success');
            } else if (empre > pos) {
                $("#status-total").removeClass().addClass('table-danger');
            } else {
                $("#status-total").removeClass().addClass('table-warning');
            }

            if ($("#total").val() == "" || $("#total").val() == 0) {
                $("#total").val("");
                $(".empre").html('R$ 0.00');
            }

            $(".total").html('R$ ' + total.toFixed(2));
            

            if (total > limit) {
                $(".limit-exc").show();
            } else {
                $(".limit-exc").hide();
            }
            
        });
        $("#type_data").on("change", function () {
            if ($("#type_data").val() == 1) {
                $(".trip").show();
                $(".attach").hide();
            } else if ($("#type_data").val() == 99) {
                $(".attach").show();
                $(".trip").hide();
            } else {
                $(".attach").hide();
                $(".trip").hide();
            }
        });
        $("#p_method").on("change", function () {
            if ($("#p_method").val() == 3) {
                $(".money-get").show();
            } else {
                $(".money-get").hide();
            }
        });
        $("#trip").on("change", function () {
            if ($("#trip").val() != "" ) {

                $(".module-body").load("/module-view/" + $("#trip").val() +"/"+ $("#type_data").val(), function (response, status, request) {
                    if ( status == "error" ) {
                        var msg = "Sorry but there was an error: ";
                        $( ".module-body" ).html( msg + xhr.status + " " + xhr.statusText );
                    }
                    $("#modal-module").modal();
                });
            }
        });

        $("#sendLendingBtn").click(function (e) { 
            if ($("#type_data").val()) {
                if ($("#type_data").val() == 1 && $("#trip").val() == "") {
                    
                    error('<?= __('lending_i.lrn_36') ?>');
                    return e.preventDefault();
                } else if ($("#type_data").val() == 99 && $("#file").val() == "") {

                    error('<?= __('lending_i.lrn_37') ?>');
                    return e.preventDefault();
                }
            } 
            
            if ($("#total").val() == "" || $("#total").val() < 50.00) {

                error('<?= __('lending_i.lrn_38') ?>');
                return e.preventDefault();
            } else if (total > limit) {
				
                error('<?= __('lending_i.lrn_4') ?>');
                return e.preventDefault();
            } else if ($("#description").val() == "") {

                error('<?= __('lending_i.lrn_39') ?>');
                return e.preventDefault();
            } else if (has_bank == 0) {

                error('<?= __('lending_i.lrn_40') ?>');
                $('#modal-account').modal();
                return e.preventDefault();
            }

            e.preventDefault();
            Swal.fire({
                title: '<?= __('lending_i.lrn_41') ?>',
                text: "<?= __('lending_i.lrn_42') ?>",
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
                        $("#sendLending").submit();
                    }
                })
            
        });

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
                    data: {agency: $("#agency").val(), account: $("#account").val(), bank: $("#bank").val(), identity: $("#identity").val()},
                    success: function (response) {
                        success('<?= __('lending_i.lrn_47') ?>');

                        <?php if (!isset($ac_bank)) { ?>
                            $(".limit-default").hide();
                        <?php } ?>
                        $(".agency").html('<b><?= __('lending_i.lrn_28') ?></b> '+ $("#agency").val() +' ');
                        $(".account").html('<b><?= __('lending_i.lrn_29') ?></b> '+ $("#account").val() +' ');
                        $(".bank").html('<b><?= __('lending_i.lrn_30') ?></b> '+ $("#bank").val() +' ');
                        $(".identity").html('<b><?= __('lending_i.lrn_31') ?></b> '+ $("#identity").val() +' ');
                        
                        $('#modal-account').modal('toggle');
                        $("#noaccountbank").hide();
                        $("#accountbank").show();
                        limit = response.limit;
                        $(".limit").html('R$ ' + limit.toFixed(2));
                        if (total > limit) {
                            $(".limit-exc").show();
                        } else {
                            $(".limit-exc").hide();
                        }

                        has_bank = 1;
                    }
                });
                
                e.preventDefault();
            }
            
            
        });
        

            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#mFinancyLending").addClass('sidebar-group-active active');
                $("#mFinancyLendingNew").addClass('active');
            }, 100);
        });
    </script>
@endsection