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
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab"  data-position="1" href="#dados_pessoais" role="tab" aria-selected="true">Dados Pessoais</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab"         data-position="2" href="#endereco" role="tab" aria-selected="false">Endereço</a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">

                <!--second tab-->
                <div class="tab-pane active" id="dados_pessoais" role="tabpanel">
                    <div class="card ">
                        <div class="card-body">

                            <form method="post" action="/comercial/operacao/perfil/save" class="form-horizontal">
                                <input type="hidden" name="profile_type" value="1">
                                <div class="form-body m-t-40">


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('district')) has-danger @endif">
                                                <label class="control-label col-md-3">Região</label>
                                                <div class="col-md-9">
                                                    <select name="distric" id="district" class="form-control" required>
                                                        @foreach (config('gree.region') as $key => $value)
                                                            <option value="{{ $key }}" @if ($key == 1) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('identity')) has-danger @endif">
                                                <label class="control-label col-md-3">Identificação</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="identity" value="{{ old('identity',$model->identity) }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group row @if($errors->has('company_name')) has-danger @endif">
                                                <label class="control-label col-md-3">Nome da Empresa</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="company_name" value="{{ old('company_name',$model->company_name) }}">

                                                    @if($errors->has('company_name'))
                                                        <small class="form-control-feedback">{{$errors->first('company_name')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group row @if($errors->has('email')) has-danger @endif">
                                                <label class="control-label col-md-3">E-Mail</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="email" class="form-control" value="{{ old('email',$model->email) }}">
                                                    @if($errors->has('email'))
                                                        <small class="form-control-feedback">{{$errors->first('email')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('phone_1')) has-danger @endif">
                                                <label class="control-label col-md-3">Telefone 1</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control phone" name="phone_1" value="{{ old('phone_1',$model->phone_1) }}">
                                                    @if($errors->has('phone_1'))
                                                        <small class="form-control-feedback">{{$errors->first('phone_1')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('phone_2')) has-danger @endif">
                                                <label class="control-label col-md-3">Telefone 2</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control phone" name="phone_2" value="{{ old('phone_2',$model->phone_2) }}">
                                                    @if($errors->has('phone_2'))
                                                        <small class="form-control-feedback">{{$errors->first('phone_2')}}</small>
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
                <div class="tab-pane" id="endereco" role="tabpanel">
                    <div class="card ">

                        <div class="card-body">
                            <form method="post" action="/comercial/operacao/perfil/save" class="form-horizontal">
                                <input type="hidden" name="profile_type" value="2">
                                <div class="form-body m-t-40">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('address')) has-danger @endif">
                                                <label class="control-label col-md-3">Endereço</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="address" value="{{ old('address',$model->address) }}">
                                                    @if($errors->has('address'))
                                                        <small class="form-control-feedback">{{$errors->first('address')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('zipcode')) has-danger @endif">
                                                <label class="control-label col-md-3">Cep</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control zipcode" name="zipcode" value="{{ old('zipcode',$model->zipcode) }}">
                                                    @if($errors->has('zipcode'))
                                                        <small class="form-control-feedback">{{$errors->first('zipcode')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('state')) has-danger @endif">
                                                <label class="control-label col-md-3">Estado</label>
                                                <div class="col-md-9">

                                                    <select class="form-control select2 m-b-10 select2-multiple" name="state" style="width: 100%" multiple="multiple" data-placeholder="Selecione o Estado">
                                                        @foreach (config('gree.states') as $key => $value)
                                                            <option value="{{ $key }}" @if ($key == old('state',$model->state)) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('state'))
                                                        <small class="form-control-feedback">{{$errors->first('state')}}</small>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row @if($errors->has('city')) has-danger @endif">
                                                <label class="control-label col-md-3">Cidade</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="city" value="{{$model->city}}">
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
                unblock();
                $(".loadqrcode").html(result.html);
            }).catch(function(err){
                unblock();
                $error(err.message)
            })
        }
    </script>


@endsection
