<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title>Gree do Brasil</title>
        <meta name="description" content="Serviço de atendimento ao cliente - SAC">
        <meta name="keywords" content="SAC Gree, gree sac, atendimento ao cliente gree">
        <meta name="robots" content="index, follow">
        <link rel="shortcut icon" href="/admin/app-assets/images/ico/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/admin/app-assets/images/ico/favicon-192x192.png>
        <link rel="apple-touch-icon" sizes="180x180" href="/admin/app-assets/images/ico/favicon-192x192.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
    <link rel="stylesheet" id="css-main" href="/css/codebase.min.css">
</head>
<body>
<div id="page-container" class="main-content-boxed">
                <main id="main-container">
<div class="bg-image" style="background-image: url('/media/gree.jpg');">
    <div class="row mx-0 bg-black-op">
        <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
            <div class="p-30 invisible" data-toggle="appear">
                <p class="font-italic text-white-op">
                    Copyright &copy; <?= date('Y') ?> Gree Electric Appliances do Brasil LTDA
                </p>
            </div>
        </div>
        <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible" data-toggle="appear" data-class="animated fadeInRight">
            <div class="content content-full">
                <div class="px-30 py-10 text-center">
                    <img class="img-fluid" src="/media/logo_gree.png" alt="" style="height:50px">
                </div>
                <div class="px-20 py-5 text-center">
                    <div class="spinner-border text-primary mr-20 loading" style="display: none; margin-top: 2px;" role="status">
                        <span class="sr-only">Carregando...</span>
                    </div>
                </div>
                <div class="px-20 py-5 text-left">
                    <h1 class="h4 mt-10 font-w700 mb-10 question" id="question"></h1>
                </div>
                <div>
                    <form class="px-30" action="#" method="post">
                        <!-- STEP 1 -->
                        <div class="form-group row step-1 animated fadeInRight">
                            <div class="col-12">
                                <div class="form-material floating">
                                    <select name="type" class="form-control" id="type">
                                        <option></option>
                                        <option value="99">Acompanhar atendimento</option>
                                        <option value="1">Reclamação</option>
                                        <option value="2">Assistência técnica (EM GARANTIA)</option>
                                        <option value="3">Assistência técnica (FORA DE GARANTIA)</option>
                                        <option value="4">Dúvida técnica</option>
                                        <option value="5">Seja uma autorizada</option>
                                        <option value="6">Seja um revendedor de peças</option>
                                    </select>
                                    <label for="type">Escolha uma opção</label>
                                </div>
                            </div>
                        </div>
                        <!-- STEP 2 -->
                        <div class="form-group row step-2-1 animated fadeInRight" style="display: none;">
                            <div class="col-5">
                                <div class="form-material floating">
                                    <select name="type_people" class="form-control" id="type_people">
                                        <option value="1">Física (CPF)</option>
                                        <option value="2">Jurídica (CNPJ)</option>
                                    </select>
                                    <label for="type_people">Tipo</label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="form-material floating">
                                    <input type="text" class="form-control" id="identity" placeholder="0000000000" name="identity">
                                </div>
                            </div>
                        </div>
                        <!-- STEP 3 PASSWORD -->
                        <div class="form-group row step-3 animated fadeInRight" style="display: none;">
                            <div class="col-12">
                                <span id="info_pass"></span>
                                <div class="form-material floating">
                                    <input type="text" class="form-control" maxlength="4" name="password_login" id="password_login">
                                    <label for="password_login">Senha</label>
                                </div>
                            </div>
                            <div class="col-12 fgtPass" style="padding: 15px; display: none;">
                                <a onclick="recovery();" href="javascript:void(0)">Esqueceu a senha?</a>
                            </div>
                        </div>
                        <!-- STEP 3 ACCESS CREATE -->
                        <div class="form-group row step-3-1 animated fadeInRight" style="display: none;">
                            <div class="col-12">
                                <div class="form-material floating">
                                    <input type="text" class="form-control" name="name" id="name">
                                    <label for="name">Nome completo/Nome da empresa</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-material floating">
                                    <input type="text" class="form-control" name="email" id="email">
                                    <label for="email">Email</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-material floating">
                                    <input type="text" class="form-control" name="phone" id="phone">
                                    <label for="phone">Telefone 1</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-material floating">
                                    <input type="text" class="form-control" name="phone_2" id="phone_2">
                                    <label for="phone_2">Telefone 2</label>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3 CREATE PASSWORD -->
                        <div class="form-group row step-3-2 animated fadeInRight" style="display: none;">
                            <div class="col-12">
                                <div class="form-material floating">
                                    <input type="text" class="form-control" maxlength="4" name="password_create" id="password_create">
                                    <label for="password_create">Senha</label>
                                </div>
                            </div>
                        </div>
                        <!-- STEP INFO -->
                        <div class="form-group row step-2-3 animated bounceIn" style="display: none;">
                            <div class="col-12 text-center">
                                <p>A nossa lista de autorizadas está no link abaixo:</p>
                                <p><a href="https://www.gree.com.br/onde-estamos/autorizadas/">https://www.gree.com.br/onde-estamos/autorizadas/</a></p>
                            </div>
                        </div>
                        <!-- STEP INFO -->
                        <div class="form-group row step-2-5 animated bounceIn" style="display: none;">
                            <div class="col-12 text-center">
                                <div class="col-12 text-center">
                                    <p>Para ser uma autorizada, você precisará enviar um email com os seguintes tópico preenchidos</p>
                                    <ul class="text-left">
                                        <li>CNPJ</li>
                                        <li>Inscrição estadual</li>
                                        <li>Nome de contato</li>
                                        <li>Telefone 1</li>
                                        <li>Telefone 2</li>
                                        <li>Endereço completo</li>
                                        <li>Foto da faixada</li>
                                        <li>Foto do interior</li>
                                        <li>Foto do ferramental</li>
                                    </ul>
                                    <p><a href="mailto:credenciado@gree-am.com.br">credenciado@gree-am.com.br</a> com o assunto "Quero ser uma autorizada"</p>
                                </div>
                            </div>
                        </div>
                        <!-- STEP INFO -->
                        <div class="form-group row step-2-6 animated bounceIn" style="display: none;">
                            <div class="col-12 text-center">
                                <p>Para ser uma revenda de peças, você precisará enviar um email para</p>
                                <p><a href="mailto:credenciado@gree-am.com.br">credenciado@gree-am.com.br</a> com o assunto "Revenda de peças"</p>
                            </div>
                        </div>

                        <div class="form-group float-right">
                            <button type="button" class="btn btn-sm btn-hero btn-alt-primary back" style="display: none;">
                                Voltar
                            </button>
                            <button type="button" class="btn btn-sm btn-hero btn-alt-primary continue">
                                Continuar
                            </button>
                            <button type="button" class="btn btn-sm btn-hero btn-alt-primary tryagain" style="display: none">
                                Tentar novamente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    </main>
    </div>
<script src="/js/codebase.core.min.js"></script>
<script src="/js/codebase.app.min.js"></script>
<script src="/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="/js/plugins/es6-promise/es6-promise.auto.min.js"></script>
<script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="/js/plugins/mask/jquery.mask.min.js"></script>
<script>jQuery(function(){ Codebase.helpers('notify'); });</script>
    <script>
        var step = 1;
        var typer;
        var HasUser = 0;
        function writing(str) {
            clearInterval(typer);
            var div = document.getElementById('question');
            var char = str.split('').reverse();
            typer = setInterval(function() {
                if (!char.length) return clearInterval(typer);
                var next = char.pop();
                div.innerHTML += next;
            }, 50);

            return true;
        }
        function recovery() {
            $.ajax({
                type: "POST",
                url: "/suporte/esqueci",
                success: function (response) {
                    if (response.success) {
                        success('Foi enviado um email com as instruções para recuperar a senha.');
                    } else {
                        error(response.msg);
                    }
                    
                }
            });
        }
        function steps(sq) {
            if (sq == 1) {
                $(".back").hide();
                $(".continue").show();
                $(".step-2-1").hide();
                $(".step-2-3").hide();
                $(".step-2-5").hide();
                $(".step-2-6").hide();
                $(".step-1").show();
                $("#question").html('');
                var string = 'Como podemos ajudar você?';
                writing(string);
            } else if (sq == 2) {
                $(".tryagain").hide();
                $(".step-3").hide();
                $(".step-3-1").hide();
                $(".step-3-2").hide();
                $(".continue").show();
                if ($("#type").val() != "") {
                    $(".back").show();
                    $(".step-1").hide();

                    if ($("#type").val() == 1 || $("#type").val() == 2 || $("#type").val() == 4 || $("#type").val() == 99) {
                        $("#question").html('');
                        var string = 'Precisamos do seu CPF/CNPJ.';
                        writing(string);
                        $(".step-2-1").show();
                    } else if ($("#type").val() == 3) {
                        $("#question").html('');
                        $(".continue").hide();
                        var string = 'isso pode te ajudar.';
                        writing(string);
                        $(".step-2-3").show();
                    } else if ($("#type").val() == 5) {
                        $("#question").html('');
                        $(".continue").hide();
                        var string = 'isso pode te ajudar.';
                        writing(string);
                        $(".step-2-5").show();
                    } else if ($("#type").val() == 6) {
                        $("#question").html('');
                        $(".continue").hide();
                        var string = 'isso pode te ajudar.';
                        writing(string);
                        $(".step-2-6").show();
                    }
                } else {
                    error('Escolha o assunto antes de continuar.');
                    return step--;
                }
            } else if (sq == 3) {
                if ($("#type").val() == 1 || $("#type").val() == 2 || $("#type").val() == 4 || $("#type").val() == 99) {
                    if ($("#identity").val() != "") {
                        $("#question").html('');
                        var string = 'Aguarde, por favor...';
                        writing(string);
                        $(".step-2-1").hide();
                        $(".step-3-2").hide();
                        $(".step-3-1").hide();
                        $(".step-3").hide();
                        $(".continue").hide();
                        $(".back").hide();
                        $(".tryagain").hide();
                        $(".loading").show();

                        $.ajax({
                            type: "POST",
                            url: "/support/verify/client",
                            data: {
                                type: $("#type").val(), 
                                type_people: $("#type_people").val(), 
                                identity: $("#identity").val()
                                },
                            success: function (response) {
                                $(".loading").hide();
                                if (response.success) {
                                    $(".continue").show();
                                    $(".back").show();
                                    if (response.has_user) {
                                        HasUser = 1;
                                        $("#question").html('');
                                        var string = 'Perfeito, você já tem um acesso!';
                                        writing(string);
                                        $("#info_pass").html(response.info);
                                        $(".step-3").show();
                                    } else {
                                        HasUser = 0;
                                        $("#question").html('');
                                        var string = 'Precisamos criar um acesso.';
                                        writing(string);
                                        $(".step-3-1").show();
                                    }

                                } else {
                                    $("#question").html('');
                                    var string = 'Opss! Aconteceu um erro :(';
                                    writing(string);
                                    error(response.msg);
                                    $(".back").show();
                                    $(".tryagain").show();
                                }
                            }
                        });
                    } else {
                        error('Digite um documento de identificação.');
                        return step--;
                    }
                }
            } else if (sq == 4) {
                if (HasUser == 1) {

                    if ($("#password_login").val() == "" || $("#password_login").val().length < 4) {

                        error('Digite sua senha de 4 digitos.');
                        return step--;
                    } else {
                        $(".step-3").hide();
                        $("#question").html('');
                        var string = 'Aguarde, por favor...';
                        writing(string);
                        $(".loading").show();

                        $.ajax({
                            type: "POST",
                            url: "/support/verify/login",
                            data: {
                                identity: $("#identity").val(),
                                password_login: $("#password_login").val(),
                                type: $("#type").val()
                            },
                            success: function (response) {
                                $(".loading").hide();
                                if (response.success) {
                                    window.location.href = '/suporte/painel'; 
                                } else {
                                    $(".fgtPass").show();
                                    error(response.msg);
                                    $("#question").html('');
                                    var string = 'Opss! Aconteceu um erro :(';
                                    writing(string);
                                    $(".back").show();
                                }
                               
                            }
                        });
                    }
                } else {
                    if ($("#name").val() == "") {

                        error('Você precisa informar seu nome completo ou da sua empresa.');
                        return step--; 
                    } else if ($("#phone").val() == "" && $("#phone_2").val() == "") {

                        error('Você precisa por ao menos 1 telefone para contato.');
                        return step--;
                    } else {

                        $("#question").html('');
                        var string = 'Informe uma senha de 4 digitos.';
                        writing(string);
                        $(".step-3-1").hide();
                        $(".step-3-2").show();
                    }
                }
            } else if (sq == 5) {
                if ($("#password_create").val() == "" || $("#password_create").val().length < 4) {

                    error('Digite sua senha de 4 digitos.');
                    return step--;
                } else {
                    $(".step-3-2").hide();
                    $("#question").html('');
                    var string = 'Aguarde, por favor...';
                    writing(string);
                    $(".loading").show();

                    $.ajax({
                        type: "POST",
                        url: "/support/verify/login",
                        data: {
                            identity: $("#identity").val(),
                            password_create: $("#password_create").val(),
                            type: $("#type").val(),
                            type_people: $("#type_people").val(), 
                            name: $("#name").val(),
                            email: $("#email").val(),
                            phone: $("#phone").val(),
                            phone_2: $("#phone_2").val(),
                        },
                        success: function (response) {
                            $(".loading").hide();
                            if (response.success) {
                                window.location.href = '/suporte/painel'; 
                            } else {

                                error(response.msg);
                                $("#question").html('');
                                var string = 'Opss! Aconteceu um erro :(';
                                writing(string);
                                $(".back").show();
                            }
                            
                        }
                    });
                }   
            
            }
        }

        function error(str) {

            Codebase.helpers('notify', {
                align: 'right',             // 'right', 'left', 'center'
                from: 'top',                // 'top', 'bottom'
                type: 'danger',               // 'info', 'success', 'warning', 'danger'
                icon: 'fa fa-times mr-5',    // Icon class
                message: str
            });
        }

        function success(str) {

            Codebase.helpers('notify', {
                align: 'right',             // 'right', 'left', 'center'
                from: 'top',                // 'top', 'bottom'
                type: 'success',               // 'info', 'success', 'warning', 'danger'
                icon: 'fa fa-check mr-5',    // Icon class
                message: str
            });
        }

        $(document).ready(function () {
            <?php if (Session::has('success')) { ?>
                setTimeout(() => {
                    success('<?= Session::get('success') ?>');
                }, 300);
            <?php } Session::forget('success'); ?>
            <?php if (Session::has('error')) { ?>
                setTimeout(() => {
                    error('<?= Session::get('error') ?>');
                }, 300);
            <?php } Session::forget('error'); ?>

            setTimeout(() => {
                steps(1);
            }, 1000);

            $(".tryagain").click(function (e) { 
                steps(step);
                
            });

            $(".continue").click(function (e) { 
                step++;
                steps(step);
                
            });

            $(".back").click(function (e) { 
                step--;
                steps(step);
                
            });

            $('#identity').mask('000.000.000-00', {reverse: false});
            $('#phone').mask('(00) 00000-0000', {reverse: false});
            $('#phone_2').mask('(00) 00000-0000', {reverse: false});
            $("#type_people").change(function (e) { 
                if($("#type_people").val() == 1) {

                    $('#identity').mask('000.000.000-00', {reverse: false});
                } else {
                    $('#identity').mask('00.000.000/0000-00', {reverse: false});
                }
                
            });
            
        });
    </script>
    </body>
</html>