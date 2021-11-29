<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>GREE - System Internal</title>
        <meta name="robots" content="noindex, nofollow">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="/media/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/media/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Page JS Plugins CSS -->
        <link rel="stylesheet" href="/css/plugins/izitoast/iziToast.min.css">

        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="/css/codebase.css">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
    </head>
    <body>
        <div id="page-loader" class="show"></div>
        <style>
            .signature {
                border: 1px solid #d4dae3; 
                border-radius: .25rem;
                margin-top: 10px;
                margin-bottom: 10px;
                width: 280px;
            }
            </style>

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

                <!-- Page Content -->
                <div class="bg-body-dark">
                    <div class="row mx-0 justify-content-center">
                        <div class="hero-static col-lg-6 col-xl-4">
                            <div class="content content-full overflow-hidden">
                                <!-- Header -->
                                <div class="py-30 text-center">
                                    <img src="<?= Request::root() ?>/media/logo.png" class="img-fluid">
                                    <h1 class="h4 font-w700 mt-30 mb-10">Order approval</h1>
                                    <?php if ($is_analyze == 1) { ?>
                                    <h2 class="h5 font-w400 text-muted mb-0">To approve the order, you will need to sign and then press approve! To fail, you'll need to enter a note.</h2>
                                    <?php } else { ?>
                                        <h2 class="h5 font-w400 text-muted mb-0">You have successfully analyzed the order!</h2>
                                        <br><small>You can now close this page.</small>
                                        <canvas style="display:none"></canvas>
                                    <?php } ?>
                                </div>
                                <!-- END Header -->

                                
                                <?php if ($is_analyze == 1) { ?>
                                <form id="AnalyzeForm" action="/trip/analyze/update" method="post">
                                    <input type="hidden" name="reason" id="reason">
                                    <input type="hidden" name="signature" id="signature">
                                    <input type="hidden" name="is_approv" id="is_approv">
                                    <div class="block block-themed block-rounded block-shadow">
                                        <div class="block-header bg-gd-dusk">
                                            <h3 class="block-title">Secret Password</h3>
                                        </div>
                                        <div class="block-content">
                                            <div class="form-group">
                                                <label for="password" class="input-float">Password</label>
                                                <input type="password" class="form-control" id="password" name="password">
                                                <i id="showHiddenPass" class="fa fa-eye-slash"></i>
                                                <div class="form-text text-muted">It's necessary for approval or disapprov</div>
                                            </div>
                                        </div>
                                        <div class="block-content bg-body-light text-center">
                                            <button type="button" id="approv" class="btn btn-success min-width-125 mb-20">APPROVE</button> 
                                            <button type="button" data-toggle="modal" data-target="#modal-reprov" class="btn btn-danger min-width-125 mb-20">DISAPPROVE</button> 
                                        </div>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->

        <!-- Pop In Modal -->
    <div class="modal fade" id="modal-reprov" tabindex="-1" role="dialog" aria-labelledby="modal-reprov" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Order disapproval</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-12 text-center">
                                Fill in a reason for your disapproval.
                                <div class="form-group row goal">
                                    <label class="col-12" for="goal">REASON</label>
                                    <div class="col-12">
                                        <textarea class="form-control" id="r_val" name="r_val" rows="6" placeholder="..."></textarea>
                                    </div>
                                </div>
                            </did>
                        </did>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="reprov" class="btn btn-alt-danger" >DISAPPROVE!</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Pop In Modal -->

        <!--
            Codebase JS Core

            Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
            to handle those dependencies through webpack. Please check out assets/_es6/main/bootstrap.js for more info.

            If you like, you could also include them separately directly from the assets/js/core folder in the following
            order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

            assets/js/core/jquery.min.js
            assets/js/core/bootstrap.bundle.min.js
            assets/js/core/simplebar.min.js
            assets/js/core/jquery-scrollLock.min.js
            assets/js/core/jquery.appear.min.js
            assets/js/core/jquery.countTo.min.js
            assets/js/core/js.cookie.min.js
        -->
        <script src="/js/codebase.core.min.js"></script>

        <!--
            Codebase JS

            Custom functionality including Blocks/Layout API as well as other vital and optional helpers
            webpack is putting everything together at assets/_es6/main/app.js
        -->
        <script src="/js/codebase.app.min.js"></script>

        <!-- Page JS Plugins -->
        <script src="/js/plugins/izitoast/iziToast.min.js"></script>

        <!-- Page JS Code -->
        <script>
        var showPass = 0;
        
            $(document).ready(function () {
            <?php if (Session::has('error')) { ?>
                iziToast.error({
                    title: 'Opss',
                    message: '<?= Session::get('error') ?>',
                });
            <?php } Session::forget('error'); ?>
            $("#showHiddenPass").click(function () { 
                if (showPass == 0) {
                    $("#password").attr('type', 'text');
                    $(this).removeClass("fa fa-eye-slash").addClass("fa fa-eye");
                    showPass = 1;

                } else {
                    $("#password").attr('type', 'password');
                    $(this).removeClass("fa fa-eye").addClass("fa fa-eye-slash");
                    showPass = 0;

                }
            });
            $("#reprov").click(function (e) { 
                $("#reason").val($("#r_val").val());
                $("#is_approv").val(0);
                if ($("#reason").val() == "") {

                    iziToast.error({
                        title: 'Opss',
                        message: 'You must enter a reason for disapproval',
                    });

                    return;
                } else if ($("#password").val() == "") {
                    iziToast.error({
                        title: 'Opss',
                        message: 'fill in your secret password',
                    });

                    return;
                }

                $("#modal-reprov").modal('toggle');
                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: 'once',
                    id: 'question',
                    zindex: 999,
                    title: 'Disapprove',
                    message: 'You really want to do that?',
                    position: 'center',
                    buttons: [
                        ['<button><b>YES</b></button>', function (instance, toast) {
                
                            instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                            $('#page-loader').addClass('show');
                            $("#AnalyzeForm").submit();
                
                        }, true],
                        ['<button>NO</button>', function (instance, toast) {
                
                            instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                            
                
                        }],
                    ],
                });
            });
            $("#approv").click(function (e) {
                $("#is_approv").val(1);
                if ($("#password").val() == "") {
                    iziToast.error({
                        title: 'Opss',
                        message: 'fill in your secret password',
                    });

                    return;
                }
                
                $("#reason").val("");
                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: 'once',
                    id: 'question',
                    zindex: 999,
                    title: 'Approv',
                    message: 'You really want to do that?',
                    position: 'center',
                    buttons: [
                        ['<button><b>YES</b></button>', function (instance, toast) {
                
                            instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                            $('#page-loader').addClass('show');
                            $("#AnalyzeForm").submit();
                
                        }, true],
                        ['<button>NO</button>', function (instance, toast) {
                
                            instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                            
                
                        }],
                    ],
                });


                
            });
        });
        </script>

    </body>
</html>