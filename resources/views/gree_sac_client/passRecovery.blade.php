<html lang="en" class="no-focus">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gree do Brasil</title>
    <meta name="description" content="Painel de acesso da autorizada gree">
    <meta name="robots" content="index, follow">
    <link rel="shortcut icon" href="/admin/app-assets/images/ico/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/admin/app-assets/images/ico/favicon-192x192.png>
    <link rel="apple-touch-icon" sizes="180x180" href="/admin/app-assets/images/ico/favicon-192x192.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
<link rel="stylesheet" id="css-main" href="/css/codebase.min.css">
</head>
<body>
<div id="page-container" class="main-content-boxed side-trans-enabled">
<main id="main-container" style="min-height: 754px;">
<div class="bg-gd-sea">
<div class="hero-static content content-full bg-white js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="py-30 px-5 text-center">
        <img class="img-fluid" src="/media/logo_gree.png" alt="" style="height:50px">
        @if ($client)
        <h1 class="h2 font-w700 mt-50 mb-10">Quase lá :)</h1>
        <h2 class="h4 font-w400 text-muted mb-0">Informe sua nova senha abaixo</h2>
        @else
        <h1 class="h2 font-w700 mt-50 mb-10">Opss.. </h1>
        <h2 class="h4 font-w400 text-muted mb-0">Aconteceu algo</h2>
        @endif
    </div>
    <div class="row justify-content-center px-5">
        <div class="col-sm-8 col-md-6 col-xl-4">
            @if ($client)
            <form action="/suporte/recuperar_do" id="formSend" method="post">
            <input type="hidden" name="code" value="{{ $code }}">
                <div class="form-group row">
                    <div class="col-12">
                        <div class="form-material floating">
                            <input type="text" maxlength="4" class="form-control" id="password" name="password">
                            <label for="password">Nova senha</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row gutters-tiny">
                    <div class="col-12 mb-10">
                        <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-primary">
                            Enviar</button>
                    </div>
                </div>
            </form>
            @else
            <div class="text-center">
                Por favor, peça para recuperar sua senha novamente.

                <div class="col-sm-12 mb-5 mt-10">
                    <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="/suporte">
                        Voltar</a>
                </div>
            </div>
            @endif
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
<script>
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
</script>
<script>
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

        $("#formSend").submit(function (e) {
            if ($("#password").val() == "") {

                e.preventDefault();
                return error('É necessário informar a nova senha de acesso.');
            }


            
        });
    });
</script>

</body></html>