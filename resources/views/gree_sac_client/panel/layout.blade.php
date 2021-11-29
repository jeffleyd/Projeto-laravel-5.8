<html lang="en" class="no-focus"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gree do Brasil - Suporte ao cliente</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="/admin/app-assets/images/ico/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/admin/app-assets/images/ico/favicon-192x192.png>
    <link rel="apple-touch-icon" sizes="180x180" href="/admin/app-assets/images/ico/favicon-192x192.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&amp;display=swap">
<link rel="stylesheet" id="css-main" href="/css/codebase.min.css">
<link rel="stylesheet" href="/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
<script src="/admin/app-assets/js/jquery-3.4.1.min.js"></script>

    <link rel="stylesheet" id="css-theme" href="/css/themes/corporate.min.css">
</head>
<body>
<div id="page-loader"></div>
<div id="page-container" class="sidebar-inverse side-scroll page-header-fixed page-header-glass page-header-inverse main-content-boxed side-trans-enabled">
    <nav id="sidebar" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">
<div class="sidebar-content">
    <div class="content-header content-header-fullrow bg-black-op-10">
        <div class="content-header-section text-center align-parent">
            <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                <i class="fa fa-times text-danger"></i>
            </button>
            <div class="content-header-item">
                <img class="img-fluid" style="height: 38px; margin-top: 8px;" src="/admin/app-assets/images/logo/logo_gree.png" alt="gree">
                <p class="text-center text-white mt-5 mb-5"><a href="/suporte" class="text-white">Sair</a></p>
            </div>
        </div>
    </div>
</div>
</div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 221px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar simplebar-visible" style="height: 0px; display: none;"></div></div></nav>
<header id="page-header" style="height: 90px;">
<div class="content-header" style="
justify-content: center;
">
    <div class="content-header-section" style="height: 63px;">
        <div class="content-header-item mr-5">
            <img class="img-fluid" style="height: 38px; margin-top: 8px;" src="/admin/app-assets/images/logo/logo_gree.png" alt="gree">
            <p class="text-center text-white mt-5 mb-5"><a href="/suporte" class="text-white">Sair</a></p>
        </div>
        
    </div>
    
</div>

<div id="page-header-loader" class="overlay-header bg-primary">
    <div class="content-header content-header-fullrow text-center">
        <div class="content-header-item">
            <i class="fa fa-sun-o fa-spin text-white"></i>
        </div>
    </div>
</div>
</header>
<main id="main-container" style="min-height: 694.8px;">
<div class="bg-primary-dark">
<div class="content content-top">
    
</div>
</div>
<div class="bg-white">

<div class="content">
    @yield('content')
</div>
</div>
</main>
<footer id="page-footer" class="bg-white opacity-0" style="opacity: 1;">
<div class="content py-20 font-size-sm clearfix">
    <div class="float-left">
        © Copyright - <?= date('Y') ?> Gree Electric Appliances do Brasil. Todos os direitos reservados.
    </div>
</div>
</footer>
</div>
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
        });
</script>
<script src="/js/codebase.core.min.js"></script>
<script src="/js/codebase.app.min.js"></script>
<script src="/js/plugins/select2/js/select2.full.min.js"></script>
<script src="/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="/js/plugins/es6-promise/es6-promise.auto.min.js"></script>
<script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="/js/plugins/jquery-raty/jquery.raty.js"></script>
<script src="/js/plugins/mask/jquery.mask.min.js"></script>
<script>jQuery(function(){ Codebase.helpers(['datepicker', 'select2', 'notify']); });</script>


</body></html>