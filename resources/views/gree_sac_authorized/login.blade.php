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
        <h1 class="h2 font-w700 mt-50 mb-10">Olá autorizada!</h1>
        <h2 class="h4 font-w400 text-muted mb-0">Use suas credências abaixo</h2>
    </div>
    <div class="row justify-content-center px-5">
        <div class="col-sm-8 col-md-6 col-xl-4">
            <form id="formSend" action="/autorizada/acessar" method="post">
                <div class="form-group row">
                    <div class="col-12">
                        <div class="form-material floating">
                            <input type="identity" class="form-control" id="identity" name="identity">
                            <label for="identity">CNPJ</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <div class="form-material floating">
                            <input type="password" class="form-control" id="password" name="password">
                            <label for="password">Senha</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row gutters-tiny">
                    <div class="col-12 mb-10">
                        <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-primary">
                            <i class="si si-login mr-10"></i>Acessar</button>
                    </div>
                    
                    <div class="col-sm-12 mb-5">
                        <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="/autorizada/esqueci">
                            <i class="fa fa-warning text-muted mr-5"></i>Esqueci minha senha</a>
                    </div>
                </div>
            </form>
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
<script src="/js/plugins/mask/jquery.mask.min.js"></script>
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

        $('#identity').mask('00.000.000/0000-00', {reverse: false});
        $("#formSend").submit(function (e) {
            if ($("#identity").val() == "") {

                e.preventDefault();
                return error('Preencha seu cnpj de cadastro.');
            } else if ($("#password").val() == "") {

                e.preventDefault();
                return error('Preencha a sua senha de cadastro.');
            }


            
        });
    });
</script>

</body></html>