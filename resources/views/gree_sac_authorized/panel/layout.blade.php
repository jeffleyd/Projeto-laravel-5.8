<?php 

$authorized = App\Model\SacAuthorized::where('id', Session::get('sac_authorized_id'))->first();

$news = App\Model\SacAuthorizedNotify::where('authorized_id', Session::get('sac_authorized_id'))
                            ->orWhere(function ($query) {
                                $query->where('authorized_id', null);
                            })
                            ->orderBy('id', 'DESC')
                            ->paginate(5);

$news_count = App\Model\SacAuthorizedNotify::where('authorized_id', Session::get('sac_authorized_id'))
                            ->whereDay('created_at', date('d'))
                            ->whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))
                            ->orWhere(function ($query) {
                                $query->where('authorized_id', null)
                                    ->whereDay('created_at', date('d'))
                                    ->whereMonth('created_at', date('m'))
                                    ->whereYear('created_at', date('Y'));
                            })
                            ->count();

?>

<html lang="en" class="no-focus"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gree do Brasil - Painel da autorizada</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="/admin/app-assets/images/ico/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/admin/app-assets/images/ico/favicon-192x192.png>
    <link rel="apple-touch-icon" sizes="180x180" href="/admin/app-assets/images/ico/favicon-192x192.png">
<link rel="stylesheet" href="/js/plugins/slick/slick.css">
<link rel="stylesheet" href="/js/plugins/slick/slick-theme.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&amp;display=swap">
<link rel="stylesheet" id="css-main" href="/css/codebase.min.css">
<link rel="stylesheet" href="/js/plugins/datatables/dataTables.bootstrap4.css">
<link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
<script src="/admin/app-assets/js/jquery-3.4.1.min.js"></script>
<style>
    [data-notify="container"] {
        z-index: 99999 !important;
    }
</style>
</head>
<body class="" style="">
<div id="page-container" class="enable-page-overlay side-scroll page-header-modern main-content-boxed side-trans-enabled sidebar-o"><div id="page-overlay"></div>
<aside id="side-overlay" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px;">
</div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 1772px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 320px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></aside>

<nav id="sidebar" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">
<div class="sidebar-content">
    <div class="content-header content-header-fullrow px-15">
        <div class="content-header-section sidebar-mini-visible-b">
            <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
            </span>
        </div>
        <div class="content-header-section text-center align-parent sidebar-mini-hidden">
            <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                <i class="fa fa-times text-danger"></i>
            </button>
            <div class="content-header-item">
                <img class="img-fluid" src="/media/logo_gree.png" style="height: 29px;margin: 12px;">
            </div>
        </div>
    </div>
    
    <div class="content-side content-side-full">
        <ul class="nav-main">
            <li class="nav-main-heading"><span class="sidebar-mini-visible">MN</span><span class="sidebar-mini-hidden">MENU</span></li><li>
            <a id="navDashboard" href="/autorizada/painel"><i class="si si-bar-chart"></i><span class="sidebar-mini-hide">Painel geral</span></a>
            </li>
            <li>
            <a id="navBuyPart" href="/autorizada/lista/ob"><i class="si si-basket"></i><span class="sidebar-mini-hide">Comprar peça</span></a>
            </li>
            <li>
            <a id="navMyOs" href="/autorizada/os"><i class="si si-doc"></i><span class="sidebar-mini-hide">Ordem de serviço</span></a>
            </li>
			@if ($authorized->is_remittance == 1)																						  
			<li>
            <a id="navRemittanceNote" href="/autorizada/remessa/lista"><i class="fa fa-send-o"></i><span class="sidebar-mini-hide">Remessa de peça</span></a>
            </li>
			@endif
																																  
            <li>
            <a id="navTec" href="/autorizada/area-tecnica"><i class="si si-wrench"></i><span class="sidebar-mini-hide">Área Técnica</span></a>
            </li>
			<li>
            <a id="navComunic" href="/autorizada/comunicado/todos"><i class="si si-flag"></i><span class="sidebar-mini-hide">Comunicados</span></a>
            </li>																										  
            <li>
            <a id="navFaq" href="/autorizada/suporte"><i class="si si-question"></i><span class="sidebar-mini-hide">Suporte</span></a>
            </li>
        </ul>
    </div>
</div>
</div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 237px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; transform: translate3d(0px, 0px, 0px); display: none;"></div></div></nav>
<header id="page-header">
<div class="content-header">
    <div class="content-header-section">
        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
            <i class="fa fa-navicon"></i>
        </button>
    </div>
    <div class="content-header-section">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-user d-sm-none"></i>
                <span class="d-none d-sm-inline-block"><?= Session::get('sac_authorized_name'); ?></span>
                <i class="fa fa-angle-down ml-5"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown" style="">
                <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">Usuário</h5>
                <a class="dropdown-item" href="/autorizada/perfil">
                    <i class="si si-user mr-5"></i> Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/autorizada/gerar/certificado" target="_blank">
                    <i class="si si-badge mr-5"></i> Certificado
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/autorizada/sair">
                    <i class="si si-logout mr-5"></i> Sair
                </a>
            </div>
        </div>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-flag"></i>
                @if ($news_count > 0)
                <span class="badge badge-primary badge-pill">{{ $news_count }}</span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-right min-width-300" aria-labelledby="page-header-notifications" style="">
                <h5 class="h6 text-center py-10 mb-0 border-b text-uppercase">Notificações</h5>
                <ul class="list-unstyled my-20">
                    @foreach ($news as $item)
                    <li>
                        <a class="text-body-color-dark media mb-15 " @if ($item->link_external) href="<?= $item->link_external ?>" target="_blank" @else href="/autorizada/comunicado/ver/<?= $item->id ?>" @endif>
                            <div class="ml-5 mr-15 mt-20">
                                @if ($item->priority == 1)
                                <i class="fa fa-fw fa-bell-o text-primary"></i>
                                @elseif ($item->priority == 2)
                                <i class="fa fa-fw fa-exclamation-circle text-warning"></i>
                                @elseif ($item->priority == 3)
                                <i class="fa fa-fw fa-exclamation-triangle text-danger"></i>
                                @endif
                            </div>
                            <div class="media-body pr-10">
                                <p class="mb-0">{{ $item->subject }}</p>
                                <div class="text-muted font-size-sm">{{ date('d-m-Y', strtotime($item->created_at)) }}</div>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center mb-0" href="/autorizada/comunicado/todos">
                    <i class="fa fa-flag mr-5"></i> Ver todas
                </a>
            </div>
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
<main id="main-container" style="min-height: 626.8px;">
<div class="content">
    @yield('content')
</div>
</main>
<footer id="page-footer" class="opacity-0" style="opacity: 1;">
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
<script src="/js/plugins/flatpickr/flatpickr.min.js"></script>
<script src="/js/plugins/flatpickr/l10n/pt.js"></script>
<script src="/js/plugins/mask/jquery.mask.min.js"></script>
<script src="/admin/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script>
    jQuery(function(){ Codebase.helpers(['datepicker', 'select2', 'notify', 'flatpickr']); });
</script>
</body></html>