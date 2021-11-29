
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- PWA CONFIGs -->
    <link rel="manifest" href="/elite/dist/js/entry_exit/gate/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="G-Portaria">
    <meta name="apple-mobile-web-app-title" content="G-Portaria">
    <meta name="theme-color" content="#5d5dfb">
    <meta name="msapplication-navbutton-color" content="#5d5dfb">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="/controle/portaria">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon icon -->
    <link rel="apple-touch-icon" href="/admin/app-assets/images/ico/favicon-192x192.png">
    <link rel="shortcut icon" type="image/x-icon" href="/admin/app-assets/images/ico/favicon.png">
    <title>Gree - Sistema da portaria</title>

    <!-- page css -->
    <link href="/elite/dist/css/pages/login-register-lock.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="/elite/assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/elite/dist/css/style.min.css" rel="stylesheet">
    <link href="/elite/dist/css/animate.min.css" rel="stylesheet">

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
        .bg-splash {
            background-image: url("/elite/assets/security/security_gate.png");
            background-repeat: no-repeat;
            background-position: right;
            position: absolute;
            top: 0;
            right: 0;
            height: 581px;
            width: 648px;
        }
        input::placeholder {
            color:white;
        }
        .input-security {
            border-radius: 7px;
            background: #a2a2fc;
            padding: 12px;
            color: white;
            width: 100%;
            border: none;
            opacity: 0.8;
        }
        .label-security {
            color:white;
        }
        .btn-security {
            background: #e57300;
            color: white;
            border-radius: 7px;
            height: 40px;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="horizontal-nav card-no-border" style="background-color: #5d5dfb">
<div class="bg-splash"></div>
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

    <div class="login-register" style="display: flex;justify-content: center;flex-direction: column;">
        <div class="row">
            <div class="col-12 d-flex justify-content-center text-center">
                <img class="animate__animated animate__zoomIn animate__delay-1s" src="/elite/assets/security/logo.png">
            </div>
        </div>
        <div class="login-box card mt-5 animate__animated animate__fadeIn animate__delay-1s" style="background-color: transparent">
            <div class="card-body">
                <form class="form-horizontal" id="loginform" method="POST" action="">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <label class="label-security">Documento de identificação</label>
                            <input class="input-security" type="text" name="identity" id="identity" placeholder="000.000.000-00"> </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label class="label-security">Senha</label>
                            <input class="input-security" type="password" name="password" id="password" placeholder="********"> </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <button class="btn btn-block btn-security" type="submit">Acessar</button>
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
        if ($('#identity').val() == "") {
            return $error('Preencha o seu CPF.');
        } else if ($('#password').val() == "") {
            return $error('Preencha sua senha.');
        }
        e.preventDefault();
        doLogin()
        block();
    });

    function ajaxSend(url, data = '', method = 'GET', timeout = 10000, form = '', enctype = 'multipart/form-data') {

        let $param = {
            type: method,
            timeout: timeout,
            url: url,
            data: data,
        };

        if (method == 'POST') {
            $param.enctype = enctype;
        }
        if (form != '') {
            var data = new FormData(form[0]);
            $param.enctype = enctype;
            $param.processData = false;
            $param.contentType = false;
            $param.data = data;
        }

        var objeto = new Promise(function(resolve, reject) {

            $param.success = function (response) {
                if(response.success==true){
                    resolve(response);
                }
                if(response.success==false){
                    let message = 'Erro no processamento de sua solicitação, comunique a equipe de desenvolvimento.';
                    if(response.message){
                        message = response.message;
                    }
                    if(response.msg){
                        message = response.msg;
                    }
                    response.message = message;
                    reject(response);
                }
                resolve(response);
            };
            $param.error =  function(jqXHR, textStatus, errorMessage){
                if (jqXHR.status === 0) {
                    reject({'message': 'Sem conexão, verifique sua conexão com a internet.'});
                } else if (jqXHR.status == 404) {
                    reject({'message': 'Página não foi encontrada, comunique a equipe de desenvolvimento.'});
                } else if (jqXHR.status == 500) {
                    reject({'message': 'Erro interno do servidor, comunique a equipe de desenvolvimento.'});
                } else if (textStatus === 'parsererror') {
                    reject({'message': 'Erro ao tratar objeto JSON, comunique a equipe de desenvolvimento.'});
                } else if (textStatus === 'timeout') {
                    reject({'message': 'Sua conexão demorou muito a responder, tente novamente!'});
                } else if (textStatus === 'abort') {
                    reject({'message': 'Solicitação foi recusada, tente novamente!'});
                } else {
                    if(jqXHR.responseJSON.message){
                        reject({'message': jqXHR.responseJSON.message, 'response':jqXHR});
                    }
                    if(jqXHR.responseJSON.msg){
                        reject({'message': jqXHR.responseJSON.msg, 'response':jqXHR});
                    }
                    reject({'message': 'Erro no processamento de sua solicitação, comunique a equipe de desenvolvimento.', 'response':jqXHR});
                }
            }
            $.ajax($param);
        });

        return objeto;
    }

    function doLogin() {
        block();
        ajaxSend(
            '/controle/portaria/validar',
            $('#loginform').serialize(),
            'POST'
        ).then(($result) => {
            localStorage.setItem('secret', $result.secret);
            window.location.href = '/controle/portaria/principal'
        }).catch((error) => {
            unblock();
            $error(error.message);
        });
    }
</script>

</body>

</html>
