@extends('gree_i.layout')

@section('content')
<form action="/misc/suspended/request/1/" method="POST" id="form_request">
    <input type="hidden" name="r_val_3" id="r_val_3_analyze">
	<input type="hidden" name="description" id="description_analyze">
	<input type="hidden" name="people" id="people_analyze">
	<input type="hidden" name="step" id="step_analyze">
    <input type="hidden" name="password" id="password_analyze">
</form>

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_lending') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('layout_i.menu_lending_approv') }}
              </div>
            </div>
          </div>
        </div>
      </div>

    <div class="content-header row">
    </div>
    <div class="content-body">
        <section>
            <div class="row">
              <div class="col-md-12">
                  <div class="card">
                      <div class="card-content">
                          <div class="card-body">
                              <div class="row">
                                  <div class="col-md-4">
                                      <b>ID</b>
                                      <br>{{$lending->code}}
                                  </div>
                                  <div class="col-md-4">
                                      <b>Solicitante</b>
                                      <br><a target="_blank" href="/user/view/<?= $lending->r_code ?>"><?= getENameF($lending->r_code); ?></a>
                                    </div>
                                    <div class="col-md-4">
                                        <b>Criado em</b>
                                        <br>{{ date('Y-m-d H:i', strtotime($lending->created_at))}}
                                    </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
        </section>  

        <div class="row"> 
            <div class="col-md-9">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">

                                <div class="form-group">
                                    <label for="description">{{ __('lending_i.lrn_13') }}</label>
                                        <textarea class="form-control" id="description" name="description" rows="6" readonly placeholder="{{ __('lending_i.lrn_14') }}"><?= $lending->description ?></textarea>
                                </div>

                                <div class="table-responsive push mt-20">
                                    <table class="table table-bordered table-hover">
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="font-w600 text-right">{{ __('lending_i.lrn_15') }}</td>
                                                <?php if (isset($ac_bank)) { ?>
                                                <td class="text-right limit">R$ <?= number_format($ac_bank->limit_credit, 2, '.', '') ?></td>
                                                <?php } else { ?>
                                                    <td class="text-right limit">R$ 500.00</td>
                                                <?php } ?>
                                            </tr>
                                            <tr <?php if (isset($ac_bank)) { ?><?php if ($ac_bank->used_credit > 0) { ?>style="display:none"<?php } else if ($ac_bank->used_credit < 0) { echo 'class="table-success"'; } else { echo ''; } } ?>>
                                                <td colspan="4" class="font-w600 text-right">{{ __('lending_i.lrn_16') }}</td>
                                                <td class="text-right pos">R$ 0,00</td>
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
                                                <td class="text-right empre">R$ 0,00</td>
                                            </tr>
                                            <tr class="table-warning" id="status-total">
                                                <td colspan="4" class="font-w700 text-uppercase text-right">{{ __('lending_i.lrn_19') }}</td>
                                                <td class="font-w700 text-right total">R$ <?= number_format($lending->amount, 2, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                                        <div class="font-w600 mb-1"><?= $user->first_name ?> <?= $user->last_name ?></div>
                                        <div id="noaccountbank" class="text-center mb-1" <?php if (isset($ac_bank)) { ?>style="display:none"<?php } ?>>
                                            {{ __('lending_i.lrn_21') }}
                                        </div>
                                        <div id="accountbank" class="text-center mb-1" <?php if (!isset($ac_bank)) { ?>style="display:none"<?php } ?>>
                                            <div class="font-size-sm text-muted agency"><b>{{ __('lending_i.lrn_22') }}</b> <?php if (isset($ac_bank)) { ?><?= $ac_bank->agency ?><?php } ?> </div>
                                            <div class="font-size-sm text-muted account"><b>{{ __('lending_i.lrn_23') }}</b> <?php if (isset($ac_bank)) { ?><?= $ac_bank->account ?><?php } ?> </div>
                                            <div class="font-size-sm text-muted bank"><b>{{ __('lending_i.lrn_24') }}</b> <?php if (isset($ac_bank)) { ?><?= $ac_bank->bank ?><?php } ?> </div>
                                            <div class="font-size-sm text-muted identity"><b>{{ __('lending_i.lrn_25') }}</b> <?php if (isset($ac_bank)) { ?><?= $ac_bank->identity ?><?php } ?> </div>
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
            <input type="hidden" name="reason" id="reason">
            <input type="hidden" name="is_approv" id="is_approv" value="1">
            <div class="row">
                <div class="col-sm-12 d-flex justify-content-center">
                    <p></p>
                </div>
                <div class="col-sm-12 d-flex justify-content-center">
                    <button type="button" class="btn btn-success" onclick="analyze(<?= $lending->id ?>, <?= $lending->position_analyze ?>)">Realizar análise</button> 
                </div>
            </div>
        </div>
    </div>
</div>

<div class="customizer d-md-block text-center">
    <a onclick="rtd_analyzes(<?= $id ?>, 'App\\Model\\FinancyLending');" style="writing-mode: vertical-lr;height: 200px;font-weight: bold;top: 40%;" class="customizer-toggle btn-historic-approv" href="javascript:void(0);">
        Histórico de aprovação
    </a>
</div>
	
@include('gree_i.misc.components.analyze.history.view')
@include('gree_i.misc.components.analyze.do_analyze.inputs')
@include('gree_i.misc.components.analyze.do_analyze.script')

<script>

    @include('gree_i.misc.components.analyze.history.script')

    var limit = <?php if (isset($ac_bank)) { ?><?= number_format($ac_bank->limit_credit, 2, '.', '') ?><?php } else { ?>500.00<?php } ?>;
    var pos = <?php if (isset($ac_bank)) { ?><?php if ($ac_bank->used_credit < 0) { ?><?= number_format(abs($ac_bank->used_credit), 2, '.', '') ?><?php } else { ?>0.00<?php } ?><?php } else { ?>0.00<?php } ?>;
    var empre = 0.00;
    var empre_ant = <?php if (isset($ac_bank)) { ?><?php if ($ac_bank->used_credit > 0) { ?><?= number_format($ac_bank->used_credit, 2, '.', '') ?><?php } else { ?>0.00<?php } ?><?php } else { ?>0.00<?php } ?>;
    var total = 0.00;
    var fileName = "";
    var has_bank = <?php if (isset($ac_bank)) { ?>1<?php } else { ?>0<?php } ?>;

    function suspending() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else {
            block();
            $('#form_request').attr('action', '/misc/suspended/request/1/<?= $id ?>');
			$('#r_val_3_analyze').val($("#r_val_3").val());
			$('#people_analyze').val($("#people").val());
			$('#password_analyze').val($("#password").val());
			$('#form_request').submit();
        }
		
		
    }
    function retroc() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else if ($("#step").val() == "") {

            return error("Você precisa escolher a etapa de volta.");
        } else {
            block();			
			$('#form_request').attr('action', '/misc/retroc/request/2/<?= $id ?>');
			$('#description_analyze').val($("#r_val_4").val());
			$('#step_analyze').val($("#step").val());
			$('#password_analyze').val($("#password").val());
			$('#form_request').submit();
        }
    }
    function approvLending() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else {
            block();
			$('#form_request').attr('action', '/financy/lending/analyze/<?= $id ?>/1');
			$('#description_analyze').val($("#r_val_1").val());
			$('#password_analyze').val($("#password").val());
			$('#form_request').submit();
        }
    }

    function reprovLending() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else if ($("#r_val_2").val() == "") {

            return error("Por favor, digite o motivo da reprovação!");
        } else {
            block();
			$('#form_request').attr('action', '/financy/lending/analyze/<?= $id ?>/2');
			$('#description_analyze').val($("#r_val_2").val());
			$('#password_analyze').val($("#password").val());
			$('#form_request').submit();
            }
        }

    $(document).ready(function () {

        $(".btn-historic-approv").click(function() {
            $(".btn-historic-approv").hide();
        });

        $(".customizer-close").click(function() {
            $(".btn-historic-approv").show();
        });

        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });

        $("#HAnalyze").click(function (e) { 
            $("#Analyze").hide();
        
        });

        $("#showAnalyze").click(function (e) { 
            $("#Analyze").show();
            
        });

        $(".pos").html('R$ ' + pos.toFixed(2));
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        $('#total').mask('00000.00', {reverse: true});

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
                $("#total").val("0.00");
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
                } else if ($("#type_data").val() == 99 && fileName == "") {

                    error('<?= __('lending_i.lrn_37') ?>');
                    return e.preventDefault();
                }
            } 
            
            if ($("#total").val() == "" || $("#total").val() < 300.00) {

                error('<?= __('lending_i.lrn_38') ?>');
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