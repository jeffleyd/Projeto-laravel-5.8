<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Gree - System Internal</title>
        <meta name="robots" content="noindex, nofollow">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="/media/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/media/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->

        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="/css/codebase.css">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
    </head>
    <body>

        <!-- Page Container -->
        <!--
            Available classes for #page-container:

        GENERIC

            'enable-cookies'                            Remembers active color theme between pages (when set through color theme helper Template._uiHandleTheme())

        SIDEBAR & SIDE OVERLAY

            'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
            'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
            'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
            'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
            'sidebar-inverse'                           Dark themed sidebar

            'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
            'side-overlay-o'                            Visible Side Overlay by default

            'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

            'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

        HEADER

            ''                                          Static Header if no class is added
            'page-header-fixed'                         Fixed Header

        HEADER STYLE

            ''                                          Classic Header style if no class is added
            'page-header-modern'                        Modern Header style
            'page-header-inverse'                       Dark themed Header (works only with classic Header style)
            'page-header-glass'                         Light themed Header with transparency by default
                                                        (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
            'page-header-glass page-header-inverse'     Dark themed Header with transparency by default
                                                        (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

        MAIN CONTENT LAYOUT

            ''                                          Full width Main Content if no class is added
            'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
            'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
        -->
        <div id="page-container" class="main-content-boxed">

            <!-- Main Container -->
            <main id="main-container">

                <div class="bg-body-light bg-pattern">
                    <div class="row no-gutters justify-content-center">
                        <div class="hero-static col-lg-7">
                            <div class="content content-full overflow-hidden">
                                <!-- Header -->
                                <div class="py-50 text-center">
                                    <img src="/media/logo.png" style="height: 50px;" class="img-fluid" />
                                    <h1 class="h4 font-w700 mt-30 mb-10">Status Service</h1>
                                    <h2 class="h5 font-w400 text-muted mb-0">Check out the current status of our services</h2>
                                </div>
                                <!-- END Header -->

                                <!-- Status -->
                                <div class="row no-gutters d-flex justify-content-center">
                                    <div class="col-md-10 col-xl-7">
                                        <div class="d-flex justify-content-between">
                                            <a class="btn btn-hero btn-alt-secondary" href="/">
                                                <i class="fa fa-arrow-left mr-5"></i> Back to Login
                                            </a>
                                        </div>
                                        <hr>
                                        <div class="alert alert-warning d-flex align-items-center justify-content-between mb-15" role="alert">
                                            <div class="flex-fill mr-10">
                                                <p class="mb-0">System is currently under maintenance. Please stand by for a while as we are working on it.</p>
                                            </div>
                                            <div class="flex-00-auto">
                                                <i class="fa fa-fw fa-2x fa-exclamation-triangle"></i>
                                            </div>
                                        </div>
                                        <div class="alert alert-danger d-flex align-items-center justify-content-between mb-15" role="alert">
                                            <div class="flex-fill mr-10">
                                                <p class="mb-0">API & Payments, these services have not yet been developed.</p>
                                            </div>
                                            <div class="flex-00-auto">
                                                <i class="fa fa-fw fa-2x fa-bug"></i>
                                            </div>
                                        </div>
                                        <ul class="list-group push">
                                            <li class="list-group-item d-flex justify-content-between align-items-center font-w600">
                                                Site
                                                <span class="badge badge-pill badge-success">Operational</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center font-w600">
                                                System
                                                <span class="badge badge-pill badge-warning">Maintenance</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center font-w600">
                                                Files
                                                <span class="badge badge-pill badge-success">Operational</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center font-w600">
                                                API
                                                <span class="badge badge-pill badge-danger">Down</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center font-w600">
                                                Payments
                                                <span class="badge badge-pill badge-danger">Down</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- END Status -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->

        <!--
            Codebase JS Core

            Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
            to handle those dependencies through webpack. Please check out /_es6/main/bootstrap.js for more info.

            If you like, you could also include them separately directly from the /js/core folder in the following
            order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

            /js/core/jquery.min.js
            /js/core/bootstrap.bundle.min.js
            /js/core/simplebar.min.js
            /js/core/jquery-scrollLock.min.js
            /js/core/jquery.appear.min.js
            /js/core/jquery.countTo.min.js
            /js/core/js.cookie.min.js
        -->
        <script src="/js/codebase.core.min.js"></script>

        <!--
            Codebase JS

            Custom functionality including Blocks/Layout API as well as other vital and optional helpers
            webpack is putting everything together at /_es6/main/app.js
        -->
        <script src="/js/codebase.app.min.js"></script>
    </body>
</html>