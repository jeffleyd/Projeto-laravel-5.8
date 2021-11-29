@extends('gree_commercial_external.layout')

@section('page-css')
    <style>
    .cardbox {
        box-shadow: -8px 12px 18px 0 rgba(25,42,70,.13);
        -webkit-transition: all .3s ease-in-out;
        transition: all .3s ease-in-out;
        min-height: 155px;
        display: flex;
        justify-content: center;
        flex-direction: column;
        text-align: center;
        object-fit: cover;
    }
    .qtd-td {
        border: none;
        width: 40px;
        text-align: center;
        border: 1px solid #d2d2d2;
    }
	.select2-container .select2-selection__rendered > *:first-child.select2-search--inline {
        width: 100% !important;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline .select2-search__field {
        width: 100% !important;
    }
    </style>
    <link href="/elite/assets/node_modules/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="/elite/assets/node_modules/cropper/cropper.min.css" rel="stylesheet">
    <link href="/elite/assets/node_modules/css-chart/css-chart.css" rel="stylesheet">
    <link href="/elite/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <input type="file" accept="image/*" name="attach_trick" id="attach_trick" style="display:none">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Operação</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Operação</li>
                </ol>
                {{-- <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button> --}}
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- Column -->
        <div class="col-12 col-sm-2" style="cursor: pointer" onclick="window.open('/comercial/operacao/programation/all?is_open=1', '_blank')">
            <div class="card">
                <div class="box bg-danger text-center">
                    <h1 class="font-light text-white">{{$programation_open}}</h1>
                    <h6 class="text-white">Programações <br>em aberto</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-2" style="cursor: pointer" onclick="window.open('/comercial/operacao/cliente/todos?is_analyze=1', '_blank')">
            <div class="card">
                <div class="box bg-warning text-center" style="background-color:black !important;">
                    <h1 class="font-light text-white">{{$client_analyze}}</h1>
                    <h6 class="text-white">Clientes em <br>análise</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-2" style="cursor: pointer" onclick="window.open('/comercial/operacao/programation/all?is_analyze=1', '_blank')">
            <div class="card">
                <div class="box bg-megna text-center">
                    <h1 class="font-light text-white">{{$programation_analyze}}</h1>
                    <h6 class="text-white">Programações <br>em análise</h6>
                </div>
            </div>
        </div>
		
		<div class="col-12 col-sm-3" style="cursor: pointer" onclick="window.open('/comercial/operacao/order/all?is_analyze=1', '_blank')">
            <div class="card">
                <div class="box bg-success text-center">
                    <h1 class="font-light text-white">{{$order_analyze}}</h1>
                    <h6 class="text-white">Pedidos programados <br>em análise</h6>
                </div>
            </div>
        </div>
		<div class="col-12 col-sm-3" style="cursor: pointer" onclick="window.open('/comercial/operacao/order/confirmed/all?is_analyze=1', '_blank')">
            <div class="card">
                <div class="box bg-info text-center">
                    <h1 class="font-light text-white">{{ $order_not_prog_analyze }}</h1>
                    <h6 class="text-white">Pedidos não programados <br>em análise</h6>
                </div>
            </div>
        </div>
		
		<div class="col-12 col-sm-3" style="cursor: pointer" onclick="window.open('/comercial/operacao/programation/approv', '_blank')">
            <div class="card">
                <div class="box bg-warning text-center">
                    <h1 class="font-light text-white">{{$programation_analyze_mng}}</h1>
					<h6 class="text-white">Programações <br><b>Para você aprovar!</b></h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3" style="cursor: pointer" onclick="window.open('/comercial/operacao/order/approv', '_blank')">
            <div class="card">
                <div class="box bg-warning text-center">
                    <h1 class="font-light text-white">{{$order_analyze_mng}}</h1>
					<h6 class="text-white">Pedidos programados <br><b>Para você aprovar!</b></h6>
                </div>
            </div>
        </div>
		<div class="col-12 col-sm-3" style="cursor: pointer" onclick="window.open('/comercial/operacao/order/confirmed/approv', '_blank')">
            <div class="card">
                <div class="box bg-warning text-center">
                    <h1 class="font-light text-white">{{ $order_not_prog_analyze_mng }}</h1>
                    <h6 class="text-white">Pedidos não programados <br><b>Para você aprovar!</b></h6>
                </div>
            </div>
        </div>
		<div class="col-12 col-sm-3" style="cursor: pointer" onclick="window.open('/comercial/operacao/cliente/todos/analise', '_blank')">
            <div class="card">
                <div class="box bg-warning text-center">
                    <h1 class="font-light text-white">{{$client_analyze_mng}}</h1>
                    <h6 class="text-white">Clientes <br><b>Para você aprovar!</b></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="/comercial/operacao/dashboard/programation" id="filterProgramation" method="GET">
                    <div class="row">
                        <div class="col-12 col-sm-1">
                            <div class="form-group">
                                <label>Ano</label>
                                <select class="form-control" name="year" id="year">
                                    <option></option>
                                    @foreach ($year_range as $key)
                                        <option value="{{$key}}" @if (Request::get('year') == $key) selected @endif>{{$key}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 @if (Session::get('salesman_data')->is_direction >= 2) col-sm-4 @else col-sm-7 @endif">
                            <div class="form-group">
                                <label>Cliente</label>
                                <select class="form-control js-select2" name="client_id" id="client_id" multiple>
                                    <option></option>
                                    @foreach($clients_all as $cli)
                                        <option value="{{$cli->id}}" @if (Request::get('client_id') == $cli->id) selected @endif>{{$cli->company_name}} ({{$cli->identity}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if (Session::get('salesman_data')->is_direction >= 2)
                        <div class="col-12 col-sm-3">
                            <div class="form-group">
                                <label>Representante</label>
                                <select class="form-control js-select2" name="salesman_id" id="salesman_id" multiple>
                                    <option></option>
                                    @foreach($salesmans as $salesman)
                                    <option value="{{$salesman->id}}" @if (Request::get('salesman_id') == $salesman->id) selected @endif>{{$salesman->full_name}} ({{$salesman->identity}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="col-12 col-sm-2">
                            <div class="form-group">
                                <label>Quantidade</label>
                                <select class="form-control" name="is_total" id="is_total">
                                    <option value="1" @if (Request::get('is_total') == 1) selected @endif>Total</option>
                                    <option value="0" @if (!Request::get('is_total')) selected @endif>Saldo restante</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-1" style="margin-top: 27px;">
                            <button type="button" class="btn btn-info btn-block" id="btn_filter_programation">Filtrar</button>
                        </div>
                        <div class="col-12 col-sm-1" style="margin-top: 27px;">
                            <button type="button" onclick="exportProgramation()" class="btn btn-success btn-block">Exportar</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills">
                        <li class=" nav-item"> 
                            <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Programações</a> 
                        </li>
                        <li class="nav-item"> 
                            <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">Programações clientes</a> 
                        </li>
                    </ul>
                    <div class="card-actions" style="position: absolute; top:20px; right:10px;">
                        <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                    </div>
                </div>

                <div class="card-body" style="text-align: center">
                    <div class="tab-content">
                        <div id="navpills-1" class="tab-pane active">
                            <div class="loader loader-prog">
                                <div class="loader__figure" style="border:0 solid #fb9678;"></div>
                                <p class="loader__label" style="color:#fb9678;">Carregando programações</p>
                            </div>
                        </div> 
                        <div id="navpills-2" class="tab-pane">
                            <div class="row">
                                <div class="@if (Session::get('salesman_data')->is_direction >= 2) col-sm-3 @else col-sm-5 @endif">
                                    <input name="macro_year" id="macro_year" class="form-control" placeholder="Digite o ano" value="<?= date('Y'); ?>" autocomplete="off">
                                </div>
                                <div class="@if (Session::get('salesman_data')->is_direction >= 2) col-sm-4 @else col-sm-6 @endif">
                                    <select name="macro_month" id="macro_month" class="form-control">
                                        <option value="">Selecione o mês</option>
                                        @foreach (config('gree.months') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if (Session::get('salesman_data')->is_direction >= 2)
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <select class="form-control js-select23" name="salesman_id" id="macro_salesman_id" multiple style="width: 100%;">
                                            <option></option>
                                            @foreach($salesmans as $salesman)
                                            <option value="{{$salesman->id}}" @if (Request::get('salesman_id') == $salesman->id) selected @endif>{{$salesman->full_name}} ({{$salesman->identity}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-sm-1">
                                    <a class="btn btn-primary" href="javascript:void(0)" id="btn_macro_filter">
                                        <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                                    </a>
                                </div>
                            </div><br> 
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background-color: #f8f9fa;">
                                                <tr>
                                                    <td rowspan="2">Cliente</td>
                                                    <td colspan="2" style="padding: 2px;" id="month_name">Mês</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 2px; background-color: #ffece6;">Total</td>
                                                    <td style="padding: 2px; background-color: #ffece6;">Saldo</td>
                                                </tr>
                                            </thead>
                                            <tbody id="table_macro_clients">
                                                <tr>
                                                    <td colspan="3">Informe ano e mês para filtrar</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>       
                    </div>    
                </div>

                <br><br><br><br>
            </div>
        </div>
    </div>
	
	<div id="AuthModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"  data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Autenticação de 2 fatores</h4>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <p>Você precisará instalar um dos aplicativos abaixo da Play Store ou da Apple Store:</p>
                        <div>
                            <b><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=pt_BR&gl=US" target="_blank">Google Authenticator</a></b> ou 
                            <b><a href="https://play.google.com/store/apps/details?id=com.twofasapp&hl=pt_BR&gl=US" target="_blank">2fas</a></b>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 loadqrcode" style="text-align: center;"></div>

                    <div class="col-xs-12 col-sm-12 col-md-12 loadinput" style="display:none;">
                        <form action="/optauth/verify/dashboard" id="submitOptauth" method="post">
                            <input type="hidden" name="secret" id="secret">
                            <input type="text" class="form-control" name="code_auth" id="code_auth" placeholder="Informe o código">    
                            <small class="form-control-feedback">Código gerado no aplicativo Google Authenticator ou 2fas</small>
                        </form>
                    </div>
                    
                    <div class="loader load-qrcode" style="position:relative; margin-top: 40px;">
                        <div class="loader__figure"></div>
                        <p class="loader__label">Gerando QR Code</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_auth" class="btn btn-info waves-effect">Autenticar</button>
                </div>
            </div>
        </div>
    </div>
		
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->

@endsection

@section('page-scripts')
    <script src="/elite/assets/node_modules/sweetalert/sweetalert.min.js"></script>
    <script src="/elite/assets/node_modules/resize/canvasResize.js"></script>
    <script src="/elite/assets/node_modules/resize/jquery.canvasResize.js"></script>
    <script src="/elite/assets/node_modules/resize/binaryajax.js"></script>
    <script src="/elite/assets/node_modules/resize/exif.js"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/i18n/pt-BR.js" type="text/javascript"></script>
    <script>
        var _id;
        var lthis;
        var arryImg = new Array();
        var $inputImage = $('#attach_trick');
        var months = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

        function detail(elem) {
            lthis = elem;
            reloadtask(elem, true);
            $("#detailModal").modal();
        }

        function confirmEndTask(id) {
            _id = id;
            arryImg = new Array();
            $('#reporttext').val('');
            $inputImage.val('');
            $("#task_"+_id).find('#report').val('');
            $("#task_"+_id).find('#images').val('');
            reloadImg();
            $("#reportModal").modal({
                backdrop: 'static',
                keyboard: false
            });
        }

        function exportProgramation() {
            var old = $('#filterProgramation').attr('action');
            $('#filterProgramation').attr('action', '/comercial/operacao/dashboard/export');
            $('#filterProgramation').submit();
            $('#filterProgramation').attr('action', old);
        }

        function updateStatus($this, id, type) {
            swal({
                title: "Tem certeza disso?",
                text: type == 1 ? "Você confirma que acabou de chegar na rota?" : "Você confirma que está saindo da rota?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm){
                if (isConfirm) {
                    swal.close();
                    block();
                    ajaxSend('/promotor/route/update', {id:id, latitude:$('#latitude').val(), longitude:$('#longitude').val()}, 'POST', '10000').then(function(result){
                        unblock();
                        if (type == 1) {
                            $($this).html('Checkout');
                            $($this).removeAttr('onclick').attr('onclick', 'updateStatus(this, '+id+', 2)');
                            $($this).removeClass('btn-info').addClass('btn-success');
                            swal("Chegada confirmada", "Checkin realizado com sucesso!", "success");
                        } else {
                            swal("Saída confirmada", "Checkout realizado com sucesso!", "success");
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    }).catch(function(err){
                        unblock();
                        $error(err.message)
                    })
                } else {
                    swal.close();
                }
            });
        }

        function ConfirmFinalizedTask() {
            if ($("#reporttext").val() == '') {

                return $error('Relate sua tarefa para sabermos o que foi realizado.');
            } else if (arryImg.length == 0) {

                return $error('Você precisa enviar ao menos 1 imagem.');
            } else if (arryImg.length > 5) {

                return $error('Você pode enviar apenas 5 imagens.');
            }
            $("#task_"+_id).find('#report').val($("#reporttext").val());
            $("#task_"+_id).find('#images').val(JSON.stringify(arryImg));

            swal({
                title: "Tem certeza disso?",
                text: "Você irá finalizar sua tarefa.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm){
                if (isConfirm) {
                    swal.close();
                    $("#imageModal").modal('toggle');
                    block();
                    ajaxSend('/promotor/task/completed', $("#task_"+_id).serialize(), 'POST', '30000', $("#task_"+_id)).then(function(result){
                        unblock();
                        location.reload();
                    }).catch(function(err){
                        unblock();
                        $error(err.message)
                    })
                } else {
                    swal.close();
                }
            });
        }

        function reloadtask(object, isjson = false) {
            var arr;

            if (isjson)
            arr = JSON.parse($(object).attr("json-data"));
            else
            arr = object;

            var list = '';

            for (let index = 0; index < arr.length; index++) {
                const row = arr[index];

                if (!row.attach) {
                list += '<div class="card text-white bg-info">';
                } else {
                    list += '<div class="card text-white bg-success"> ';
                }

                list += '<div class="card-header">';
                list += '<h4 class="m-b-0 text-white">Tarefa #'+row.id+'</h4></div>';
                list += '<div class="card-body">';
                if (!row.attach) {
                    list += '<form action="#" id="task_'+row.id+'">';
                    list += '<input type="hidden" id="report" name="report" value="">';
                    list += '<input type="hidden" id="images" name="images" value="">';
                    list += '<input type="hidden" id="task_id" name="task_id" value="'+row.id+'">';
                    list += '</form>';
                    list += '<p class="card-text">'+row.description+'</p>';
                    list += '<a onclick="confirmEndTask('+row.id+')" href="javascript:void(0)" class="btn btn-block btn-dark">Concluir</a>';
                } else {
                    list += '<p class="card-text">'+row.description+'</p>';
                    list += '<br><b>Terminou em:</b> '+row.job_done+'';
                    list += '<br><b>Arquivo:</b> <a style="color: white;" target="_blank" href="'+row.attach+'">Clique aqui para ver</a>';
                }

                list += '</div>';
                list += '</div>';

            }

            $(".loadlist").html(list);

        }

        function arrdel(index) {
            swal({
                title: "Tem certeza disso?",
                text: "Você irá remover a imagem em anexo.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm){
                if (isConfirm) {
                    swal.close();
                    arryImg.splice(index, 1);
                    $success('Imagem excluída com sucesso!');
                    reloadImg();
                } else {
                    swal.close();
                }
            });

        }

        function reloadImg() {

            var list = '';
            for (let index = 0; index < arryImg.length; index++) {
                const img = arryImg[index];

                list += '<div class="col-md-4 cardbox cursor-pointer" onclick="arrdel('+index+')">';
                list += '<img id="image" height="100" class="img-fluid" src="'+img+'" alt="">';
                list += '</div>';

            }

            list += '<div class="col-md-4 cardbox cursor-pointer" onclick="trickImage()">';
            list += 'Adicionar imagem';
            list += '</div>';

            $(".loadimage").html(list);

        }

        function trickImage() {
            $inputImage.trigger('click');
        }

        function readURL(input) {
            var files = input.files;
            var file;

            if (files && files.length) {
                file = files[0];

                canvasResize(file, {
                    width: 0,
                    height: 800,
                    crop: false,
                    quality: 100,
                    //rotate: 90,
                    callback: function(data, width, height) {
                        arryImg.push(data);
                        reloadImg();
                    }
                });
            }

            $inputImage.val('');

        }
		
        $(document).ready(function () {

            $("#navpills-1").load( "/comercial/operacao/dashboard/programation", function(response, status, xhr) {
                if (status == 'success') {
                    $(".loader-prog").hide();
                } else {
                    $error("Erro ao carregar as programações: " + xhr.statusText);
                }
            });

            $("#btn_filter_programation").click(function() {
                
                var year = $("#year").val();
                var client_id = $("#client_id").val();
                var salesman_id = $("#salesman_id").val();
                var is_total = $("#is_total").val();

                loadSpinnerProg();
                $("#navpills-1").load( "/comercial/operacao/dashboard/programation?year="+year+"&client_id="+client_id+"&salesman_id="+salesman_id+"&is_total="+is_total+"", function(response, status, xhr) {
                    if (status == 'success') {
                        $(".loader-prog").hide();
                    } else {
                        $error("Erro ao carregar as programações: " + xhr.statusText);
                    }
                }); 
            });
	
			@if (!Session::get('sal_otpauth'))
                $("#AuthModal").modal('show');
                ajaxSend('/comercial/operacao/dashboard/2fa/update', {active_otp: 1}, 'GET', '5000').then(function(result){

                    $(".loadqrcode").html(result.html);
                    $(".load-qrcode").hide();
                    $(".loadinput").show();
                    $("#secret").val(result.otpauth);

                }).catch(function(err){
                    unblock();
                    $error(err.message);
                });
            @endif 
			
			$("#btn_auth").click(function() {

                if($("#code_auth").val() == "") {
                    return $error('Informe o código gerado');
                }
                else {
                    block();
                    $("#submitOptauth").submit();
                }
            });
			
            $(".js-select2").select2({
                language: "pt-BR",
                maximumSelectionLength: 1,
            });
			
			$(".js-select23").select2({
                language: "pt-BR",
                maximumSelectionLength: 1,
                placeholder: "Selecione o representante"
            });

            $inputImage.on('change', function () {
                readURL(this);
            });

            $("#btn_macro_filter").click(function() {

                var year = $("#macro_year").val();
                var month = $("#macro_month").val();
                
                if($("#macro_salesman_id").val() != undefined) {
                    var salesman_id = $("#macro_salesman_id").val().length == 0 ? 0 : $("#macro_salesman_id").val()[0];
                } else {
                    var salesman_id = 0;
                }

                if(year == "") {
                    $error('Informe o ano para filtrar');
                } else if(month == "") {
                    $error('Selecione o mês para filtrar');
                } else {
                    block();
                    ajaxSend('/comercial/operacao/programation/macro/clients/ajax', {year: parseInt(year), month: parseInt(month), salesman_id: salesman_id}, 'GET', '60000').then(function(result){

                        if(result.success) {

                            var index_month = month - 1;
                            
                            $("#table_macro_clients").html(loadMacroClients(result.macro_clients));
                            $("#month_name").html(months[index_month]);
                            unblock();
                        }
                    }).catch(function(err){
                        unblock();
                        $error(err.message)
                    });
                };
            });

            function loadMacroClients(object) {

                var html = '';
                if(object.length > 0) {
                    for (var i = 0; i < object.length; i++) {
                        var column = object[i];

                        html += '<tr>';
                        html += '    <td style="vertical-align: middle;"><a href="/comercial/operacao/cliente/todos?code='+column.code+'" style="color: #428bca;" target="_blank">'+column.company_name+'<br>('+column.identity+')</a></td>';
                        html += '    <td style="vertical-align: middle;">'+column.total+' Qtd</td>';
                        html += '    <td style="vertical-align: middle;">'+column.quantity+' Qtd</td>';
                        html += '</tr>';
                    }    
                } else {
                    html += '<tr>';
                    html += '    <td colspan="3" style="vertical-align: middle; background:#fff;">NÃO HÁ PROGRAMAÇÕES PARA O ANO E MÊS SELECIONADOS</td>';
                    html += '</tr>';
                }
                return html;
            }

            function loadSpinnerProg() {
                $("#navpills-1").html(`<div class="loader loader-prog">
                                            <div class="loader__figure" style="border:0 solid #fb9678;"></div>
                                            <p class="loader__label" style="color:#fb9678;">Carregando programações</p>
                                       </div>`);
            }
        });
    </script>
@endsection