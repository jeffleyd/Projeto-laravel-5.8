
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="apple-touch-icon" href="/admin/app-assets/images/ico/favicon-192x192.png">
    <link rel="shortcut icon" type="image/x-icon" href="/admin/app-assets/images/ico/favicon.png">
    <title>Gree - Sistema de Clientes</title>

    <!-- page css -->
    <link href="/elite/dist/css/pages/login-register-lock.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="/elite/assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/elite/dist/css/style.min.css" rel="stylesheet">

    <style type="text/css">
        .avatar {
            background-color: #c3c3c3;
            border-radius: 50%;
            color: #FFFFFF;
            display: inline-flex;
            width: 80px;
            height: 80px;
            margin-bottom: 30px;
            margin-top: 20px;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="horizontal-nav skin-megna card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Gree do Brasil</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <div class="login-register">
            <div class="login-box card">
                <div class="card-body">
                    <form class="form-horizontal form-material" id="loginform" action="#">
                        <h3 class="box-title m-b-20">Área Restrita</h3>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" name="identity" id="identity" placeholder="CNPJ / CPF"> </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" name="password" id="password" placeholder="Senha"> </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Esqueceu a senha?</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit">Acessar</button>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" id="recoverform" action="#">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>Recuperar senha</h3>
                                <p class="text-muted">Digite seu CNPJ ou CPF e você terá as instruções de alteração de senha no email! </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" name="identity_f" id="identity_f" placeholder="000.000.000-00"> </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12 text-center">
                                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" style="margin-bottom: 20px;" type="submit">Resetar senha</button>
                                <a href="javascript:void(0)" id="to-login" class="text-dark text-center">Voltar para o login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="/elite/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="/elite/assets/node_modules/popper/popper.min.js"></script>
    <script src="/elite/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/elite/assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <script src="/js/plugins/mask/jquery.mask.min.js"></script>

    <!--Custom JavaScript -->
    <script type="text/javascript">
        function block() {
            $(".preloader").show();
        }
        function unblock() {
            $(".preloader").hide();
        }
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });

        function $success(msg) {
            $.toast({
                text: msg,
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 3500

            });
        }

        function $error(msg) {
            $.toast({
                text: msg,
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3500

            });
        }
        // ==============================================================
        // Login and Recover Password
        // ==============================================================
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
        $('#to-login').on("click", function() {
            $("#recoverform").slideUp();
            $("#loginform").fadeIn();
        });

        var options = {
            onKeyPress: function (cpf, ev, el, op) {
                var masks = ['000.000.000-000', '00.000.000/0000-00'];
                $('#identity, #identity_f').mask((cpf.length > 14) ? masks[1] : masks[0], op);
            }
        }

        $('#identity, #identity_f').length > 11 ? $('#identity, #identity_f').mask('00.000.000/0000-00', options) : $('#identity, #identity_f').mask('000.000.000-00#', options);

        $('#loginform').on('submit', function (e) {
            e.preventDefault();
            if ($('#identity').val() == "") {
                return $error('Preencha o seu cnpj.');
            } else if ($('#password').val() == "") {
                return $error('Preencha sua senha.');
            }
            block();
            $.ajax({
                type: "post",
                url: "/comercial/operacao/login/verify",
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {

                        console.log(response.url);

                        if (response.url == "") {
                            unblock();
                            var screen = "";
                                screen += '<div class="d-flex flex-md-row flex-column justify-content-around">';
                                screen += '<div class="avatar mr-1 avatar-xl" style="display: flex; align-self: center;">';
                                screen += '	  <i class="icon-lock" style="font-size: 60px; position: relative; left: 10px; top: 7px;"></i>';
                                screen += '	</div>';
                                screen += '</div>';
                                screen += '<div class="divider">';
                                screen += '</div>';
                                screen += '<form action="/optauth/verify" id="submitOptauth" method="post">';
                                screen += '    <input type="hidden" value="'+ response.code +'" class="form-control" id="code" name="code">';
                                screen += '    <div class="form-group">';
                                screen += '        <label class="text-bold-600" for="password">Código de autenticação</label>';
                                screen += '        <input type="text" class="form-control" autocomplete="false" name="pin" id="pin" placeholder="00000">';
                                screen += '    </div>';
                                screen += '    <button type="submit" class="btn btn-info glow w-100 position-relative">AUTENTICAR</button>';
                                screen += '</form>';

                            $(".card-body").html(screen);

                            $("#submitOptauth").submit(function (e) {
                                e.preventDefault();

                                block();
                                $.ajax({
                                    type: "POST",
                                    url: "/comercial/operacao/login/optauth/verify",
                                    data: $("#submitOptauth").serialize(),

                                    success: function (response) {
                                        if (response.success) {

                                            window.location.href = response.url;
                                        } else {
                                            unblock();
                                            $error(response.message);
                                        }
                                    }
                                });
                            });
                        } else {
                            window.location.href = response.url;
                        }
                    } else {
                        unblock();
                        $error(response.message);
                    }
                }
            });

        });

        $('#recoverform').on('submit', function (e) {
            e.preventDefault();
            if ($('#identity_f').val() == "") {
                return $error('Preencha o CNPJ ou CPF, antes de enviar a solicitação.')
            }

            $.ajax({
                type: "post",
                url: "/comercial/operacao/forgotten/password",
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {

                        $("#recoverform").slideUp();
                        $("#loginform").fadeIn();
                        $('#identity_f').val("");
                        $success(response.message);
                    } else {
                        $error(response.message);
                    }
                }
            });

            $('#email_f').val('');
        });
    </script>

</body>

</html>
