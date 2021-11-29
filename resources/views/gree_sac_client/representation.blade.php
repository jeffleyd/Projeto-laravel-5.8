<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Gree do Brasil</title>
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
        <link rel="stylesheet" href="/js/plugins/datatables/dataTables.bootstrap4.css">
        <link rel="stylesheet" id="css-main" href="/css/codebase.min.css">
        <script src="/admin/app-assets/js/jquery-3.4.1.min.js"></script>

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
    </head>
    <body style="background-color: white !important">

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

                <div class="table-responsive" style="padding: 40px;background-color: white;">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefones</th>
                                <th>Endereço</th>
                                <th>Estado</th>
                                <th>Cidade</th>
                                <th>CEP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rep as $key) { ?>
                            <tr>
                                <td><?= $key->name ?></td>
                                <td><?= $key->email ?></td>
                                <td><?= $key->phone_1 ?> <?= $key->phone_2 ?></td>
                                <td><?= $key->address ?></td>
                                <td><?= $key->state ?></td>
                                <td><?= $key->city ?></td>
                                <td><?= $key->zipcode ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </main>
            <!-- END Main Container -->
        </div>
        <script>
        
            $(document).ready(function () {
                $('.js-dataTable-full-pagination').DataTable( {
                    searching: true,
                    paging: true,
                    ordering:true,
                    lengthChange: false,
                    pageLength: 10,
                    language: {
                        search: "{{ __('layout_i.dtbl_search') }}",
						zeroRecords: "Infelizmente não temos representante na sua região. Mas você pode entrar em contato diretamente com o nosso time comercial através do <br>número: (92) 2123 - 6904 ou do<br> e-mail: comercialinterno@gree-am.com.br",
                        //zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                        info: "{{ __('layout_i.dtbl_info') }}",
                        infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
                        infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
                        paginate: {
                            previous: "Anterior",
                            next: "Próxima"
                        }
                    }
                });
        
            });
            </script>
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
        <script src="/js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    </body>
</html>