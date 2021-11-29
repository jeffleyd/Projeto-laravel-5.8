
<!DOCTYPE html>
<html lang="pt-br">

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
    <title>Gree - Sistema de Representante</title>
    <!-- Custom CSS -->
    @yield('page-css')
    <link href="/elite/dist/css/style.min.css" rel="stylesheet">
    <link href="/elite/dist/css/styleSecurity.css" rel="stylesheet">
    <link href="/elite/dist/css/animate.min.css" rel="stylesheet">
    <style>
        .issue .help-block {
            color: #c09853;;
        }
        .issue .form-control {
            border-color: #c09853;;
        }
    </style>
    <!-- toast CSS -->
    <link href="/elite/assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body class="horizontal-nav skin-megna fixed-layout" style="background-color: #ffffff;">
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
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper" style="background-color: #ffffff; padding: 0px !important;">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="navbar-header" style="width: 100%; display: flex;justify-content: center;background: #3263d4;height: 55px;">
                <a class="navbar-brand" href="javascript:void(0)" onclick="location.reload()">
                    <img src="/admin/app-assets/images/logo/logo_gree.png" style="height: 40px; margin-top: 5px;" class="light-logo" alt="homepage"> </a>
            </div>
            <div class="row" style="background: #3263d4;color: white;padding: 9px;height: 70px;">
                <div class="col-12 text-left mt-1">
                    <b>Nome:</b> {{\Session::get('security_guard_data')->name}}
                    <br><b>Portaria:</b>
                    @if (\Session::get('security_guard_data')->logistics_entry_exit_gate)
                        {{\Session::get('security_guard_data')->logistics_entry_exit_gate->name}}
                    @endif
                </div>
                <div onclick="window.open('/controle/portaria/sair', '_self')" class="text-right" style="position: absolute; font-size: 40px;right: 10px;top: 60px;">
                    <i class="ti-power-off"></i>
                </div>
            </div>
            <div class="row" style="background: #b70030;color: white;padding: 9px;height: 45px; text-align: center; text-transform: uppercase; font-weight: normal; margin-bottom: 30px">
                @yield('breadcrumbs')
            </div>
            @yield('content')
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    @include('security.gate.guard.layout.globalScripts')
    @yield('page-scripts')
</body>

</html>
