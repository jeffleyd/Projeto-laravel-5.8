@extends('gree_commercial_external.layout')

@section('page-css')
    <link href="/elite/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/elite/dist/css/pages/other-pages.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <!-- Column -->

    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-12 col-xlg-12 col-md-12">
        <div class="card">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist" id="myTab">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab"  data-position="1" href="#senha" role="tab" aria-selected="true">Senha</a> </li>
                <!--class="nav-item"> <a class="nav-link" data-toggle="tab"         data-position="2" href="#settings" role="tab" aria-selected="false">Segurança</a> </li>-->
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">

                <!--second tab-->
                <div class="tab-pane active" id="senha" role="tabpanel">
                    <div class="card ">
                        <div class="card-body">

                            <form method="post" action="/comercial/operacao/perfil/save" class="form-horizontal">
                                <input type="hidden" name="profile_type" value="3">
                                <div class="form-body m-t-40">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('current_password')) has-danger @endif">
                                                <label class="control-label col-md-3">Senha Atual</label>
                                                <div class="col-md-9">
                                                    <input type="password" name="current_password" class="form-control" placeholder="*******">
                                                    @if($errors->has('current_password'))
                                                        <small class="error-control">{{$errors->first('current_password')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('new_password')) has-danger @endif">
                                                <label class="control-label col-md-3">Nova Senha</label>
                                                <div class="col-md-9">
                                                    <input type="password" name="new_password" class="form-control" placeholder="*******">
                                                    @if($errors->has('new_password'))
                                                        <small class="error-control">{{$errors->first('new_password')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <hr>

                                <div class="form-actions" style="text-align: end;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-inverse">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Salvar</button>
                                        </div>
                                        <div class="col-md-6"> </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>


                <div class="tab-pane" id="settings" role="tabpanel">
                    <div class="card ">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <ul class="list-unstyled mb-0 border p-2">
                                        <li class="d-inline-block mr-2" style="margin-top: 10px;">
                                          <fieldset>
                                            <div class="custom-control custom-radio">
                                              <input type="radio" onclick="toFALoad(1)" class="custom-control-input" value="1" <?php if ($model->otpauth) { ?> checked=""<?php } else { ?><?php } ?> name="active_otp" id="activeotp">
                                              <label class="custom-control-label" for="activeotp">Ativar</label>
                                            </div>
                                          </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2" style="margin-top: 10px;">
                                          <fieldset>
                                            <div class="custom-control custom-radio">
                                              <input type="radio" onclick="toFALoad(0)" class="custom-control-input" value="0" <?php if ($model->otpauth == null) { ?> checked=""<?php } else { ?><?php } ?> name="active_otp" id="desactiveotp">
                                              <label class="custom-control-label" for="desactiveotp">Desativa</label>
                                            </div>
                                          </fieldset>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4">
                                    <p>Você precisará de um aplicativo para autenticar, pesquise na Play Store ou na Apple Store.</p>
                                    <div><b>Google Authenticator</b></div>
                                    <div>ou</div>
                                    <div><b>2fas</b></div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-8 loadqrcode" style="margin-top: 20px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>

@endsection

@section('page-scripts')

    <script src="/elite/dist/js/pages/validation.js"></script>
    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/i18n/pt-BR.js" type="text/javascript"></script>

    <script>
        ! function(window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);

        $(document).ready(function () {

            $(".nav-item").click(function(e){
                let url = new URL('{{Request::url()}}');
                url.searchParams.set("tab", $(this).find('a').data('position'));
                history.pushState('', '', url.href);

            });

            let url = new URL(window.location.href);
            let page = url.searchParams.get("tab");

            $('#myTab a[data-position="' +page+ '"]').tab('show');


            $(".select2").select2({
            language: "pt-BR",
            maximumSelectionLength: 1,
            });
            $(".zipcode").mask("99999-999");
            $('.phone').mask('(00) 00000-0000', {reverse: false});

            var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('#identity').mask(mask, options);
                }
            };
            $('#identity').mask('000.000.000-009', options);
        });

        function toFALoad(isactive) {
            block();
            ajaxSend('/comercial/operacao/2fa/update', {active_otp: isactive}, 'POST', '5000').then(function(result){
                
				if (isactive == 0)
					unblock();
				
				$(".loadqrcode").html(result.html);
				unblock();

            }).catch(function(err){
                unblock();
                $error(err.message)
            })
        }
    </script>


@endsection
