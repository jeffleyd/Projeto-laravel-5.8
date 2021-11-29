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

                <li class="nav-item"> <a class="nav-link active" data-toggle="tab"         data-position="1" href="#superior_imediato" role="tab" aria-selected="false">Superior Imediato</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab"         data-position="2" href="#subordinado" role="tab" aria-selected="false">Subordinados</a> </li>

            </ul>
            <!-- Tab panes -->
            <div class="tab-content">


                <div class="tab-pane active" id="superior_imediato" role="tabpanel">
                    <div class="card-body">

                        <div class="tab-pane fade show" id="immediate" aria-labelledby="immediate-tab" role="tabpanel">
                                <!-- users edit Info form start -->
                                <div class="row">
                                    <div class="col-12">
                                        Essa pessoa(s) abaixo responde por todos as suas solicitações de aprovação!
                                    </div>

                                    @foreach ($model->immediate_boss as $item)
                                        <div class="col-lg-4 col-xlg-3 col-md-5">
                                            <div class="card">
                                                <div class="card-body media justify-content-center" >
                                                    <center class="m-t-30"> <img src="@if ($item->picture) {{$item->picture}} @else /media/avatars/avatar10.jpg @endif" style="object-fit: cover;" class="img-circle" width="150" height="150">
                                                        <h4 class="card-title m-t-10">{{$item->full_name}}</h4>
                                                        <h6 class="card-subtitle">{{$item->company_name}}</h6>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                        </div>

                    </div>
                </div>

                <div class="tab-pane" id="subordinado" role="tabpanel">
                    <div class="card-body">

                        <div class="tab-pane fade show" id="immediate" aria-labelledby="immediate-tab" role="tabpanel">
                                <div class="row">
                                    @foreach ($model->subordinates as $item)
                                        <div class="col-lg-4 col-xlg-3 col-md-5">
                                            <div class="card">
                                                <div class="card-body media justify-content-center" >
                                                    <center class="m-t-30"> <img src="@if ($item->picture) {{$item->picture}} @else /media/avatars/avatar10.jpg @endif" style="object-fit: cover;" class="img-circle" width="150" height="150">
                                                        <h4 class="card-title m-t-10">{{$item->full_name}}</h4>
                                                        <h6 class="card-subtitle">{{$item->company_name}}</h6>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
                unblock();
                $(".loadqrcode").html(result.html);
            }).catch(function(err){
                unblock();
                $error(err.message)
            })
        }
    </script>


@endsection
