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
        <script src="/js/codebase.core.min.js"></script>
    </head>
    <body>
    <style>
    .msg_gree {
        padding: 10px;
        border-radius: 20px;
        margin: 10px 10px;
        text-align: left;
        width: 80%;
    }
    .msg_agency {
        padding: 10px;
        border-radius: 20px;
        margin: 10px 10px;
        text-align: right;
    }
    </style>
    <div id="page-loader" class="show"></div>

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
                        <div class="hero-static col-lg-6 col-xl-8">
                            <div class="content content-full overflow-hidden">

                            <div class="py-30 text-center">
                                    <img src="<?= Request::root() ?>/media/logo.png" class="img-fluid">
                                </div>

                    <link rel="stylesheet" href="/js/plugins/datatables/dataTables.bootstrap4.css">
                        <h2 class="content-heading">Planejamento de Voo #<?= $planid ?></h2>
                        <?php if ($has_request == 1) { ?>
                            <?php if ($trip->is_completed == 1) { ?>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <h2>Cotação foi concluida!</h2>
                                        <br><h5>Obrigado por usar o sistema da GREE, nos vemos em breve!</h5>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-12">
                                        <?php if ($other_agency_approv == 1) { ?>
                                            <p class="p-10 bg-danger text-center text-white font-w600 font-size-md">
                                            Encerrado
                                            </p>
                                        <?php } else if ($has_approv == 1) { ?>
                                            <p class="p-10 bg-success text-center text-white font-w600 font-size-md">
                                            Aprovado
                                            </p>
                                            <div class="block block-rounded">
                                                <div class="block-header" style="padding: 0px 20px;">
                                                    <h3 class="block-title mt-15">Atenção!</h3>
                                                </div>
                                                <div class="block-content" style="padding: 0px 20px 1px;">
                                                    <p>Para situações que precisar enviar mais de 1 PDF, use esse site para mesclar os PDFs <a target="_blank" href="https://www.ilovepdf.com/pt/juntar_pdf">https://www.ilovepdf.com/pt/juntar_pdf</a></p>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <p class="p-10 bg-info text-center text-white font-w600 font-size-md">
                                            Aguardando: <span class="text-white font-w300">Envie o PDF com a cotação e se quiser, poderá enviar uma descrição breve!</span>
                                            </p>
                                            <div class="block block-rounded">
                                                <div class="block-header" style="padding: 0px 20px;">
                                                    <h3 class="block-title mt-15">Atenção!</h3>
                                                </div>
                                                <div class="block-content" style="padding: 0px 20px 1px;">
                                                    <p>Para situações que precisar enviar mais de 1 PDF, use esse site para mesclar os PDFs <a target="_blank" href="https://www.ilovepdf.com/pt/juntar_pdf">https://www.ilovepdf.com/pt/juntar_pdf</a></p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php if ($other_agency_approv == 1) { ?>
                                <div class="row">
                                    <div class="col-12 text-center mb-20">
                                        <h2>Cotação foi encerrada!</h2>
                                        <h5>Infelizmente não há mais como enviar cotação. Obrigado! <i class="si si-like"></i></h5>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <?php if ($has_approv == 1) { ?>
                                    <div class="col-md-12" style="padding:30px">
                                        <div class="block block-rounded">
                                            <div class="block-header">
                                                <h3 class="block-title">Histórico de conversa</h3>
                                            </div>

                                                <!-- Messages (demonstration messages are added with JS code at the bottom of this page) -->
                                                <div class="block-content chat block-content-full text-wrap-break-word overflow-y-auto" style="height: 300px;">
                                                    <?php foreach ($msgs as $key) { ?>
                                                        <?php if ($key->type == 1) { ?>
                                                            <div class="msg_gree">
                                                                <div class="text-primary font-w600">Gree</div>
                                                                <?= $key->message ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="msg_agency">
                                                                <div class="text-primary font-w600">Você</div>
                                                                <?= $key->message ?>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>

                                                <!-- Chat Input -->
                                                <div class="js-chat-form block-content block-content-full block-content-sm bg-body-light">
                                                    <form action="/trip/agency/msg" id="sendMsg" method="post">
                                                    <input type="hidden" name="type" value="2">
                                                    <input type="hidden" name="trip_id" value="<?= $planid ?>">
                                                    <input type="hidden" name="gen" value="<?= $gen ?>">
                                                    <div class="input-group">
                                                        <input class="js-ckeditor-inline form-control" name="msg" type="text" placeholder="Digite sua mensagem e aperte ENTER no teclado..."><div class="input-group-append">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-send"></i>
                                                        </button>
                                                    </div>
                                                    </div>
                                                    </form>
                                                </div>
                                                <!-- END Chat Input -->
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-<?php if ($trip->has_hotel == 1) { ?>6<?php } else { ?>12<?php } ?>">
                                        <div class="block block-rounded block-bordered">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">{{ __('trip_i.trc_flight') }}</h3>
                                            </div>
                                            <div class="block-content">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="p-20 text-left">
                                                            <i class="fa fa-2x fa-long-arrow-right text-danger"></i>
                                                        </div>
                                                        <div class="ml-5 text-left">
                                                            <p class="font-size-lg font-w600 mb-0">
                                                            {{ __('trip_i.trc_orign') }}
                                                            </p>
                                                            <p class="font-size-sm text-uppercase font-w600 text-muted mb-0">
                                                                <div><b>{{ __('trip_i.trc_origin_country') }}</b> <?= GetCountryName($trip->origin_country) ?></div>
                                                                <div><b>{{ __('trip_i.trc_origin_state') }}</b> <?= GetStateName($trip->origin_country, $trip->origin_state) ?></div>
                                                                <div><b>{{ __('trip_i.trc_origin_city') }}</b> <?= $trip->origin_city ?></div>
                                                                <div><b>{{ __('trip_i.trc_origin_period') }}</b> <?= periodName($trip->origin_period) ?></div>
                                                                <div><b>{{ __('trip_i.trc_origin_date') }}</b> <?= date('Y-m-d', strtotime($trip->origin_date)) ?></div>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-20 text-right">
                                                            <i class="fa fa-2x fa-flag text-danger"></i>
                                                        </div>
                                                        <div class="ml-5 text-right">
                                                            <p class="font-size-lg font-w600 mb-0">
                                                            {{ __('trip_i.trc_destiny') }}
                                                            </p>
                                                            <p class="font-size-sm text-uppercase font-w600 text-muted mb-0">
                                                                <div><b>{{ __('trip_i.trc_destiny_country') }}</b> <?= GetCountryName($trip->destiny_country) ?></div>
                                                                    <div><b>{{ __('trip_i.trc_destiny_state') }}</b> <?= GetStateName($trip->destiny_country, $trip->destiny_state) ?></div>
                                                                    <div><b>{{ __('trip_i.trc_destiny_city') }}</b> <?= $trip->destiny_city ?></div>
                                                                    <div><b>{{ __('trip_i.trc_destiny_period') }}</b> <?= periodName($trip->destiny_period) ?></div>
                                                                    <div><b>{{ __('trip_i.trc_destiny_date') }}</b> <?= date('Y-m-d', strtotime($trip->destiny_date)) ?></div>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mb-20">
                                                            <h5 class="mb-10">{{ __('trip_i.trc_destiny_dispatch') }} <?= $trip->dispatch ?></h5>
                                                            <?php if ($trip->dispatch > 1) { ?>
                                                                <spam class="text-muted"><?= $trip->dispatch_reason ?></spam>
                                                            <?php } ?>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($trip->has_hotel == 1) { ?>
                                    <div class="col-md-6">
                                        <div class="block block-rounded block-bordered">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">{{ __('trip_i.trc_hotel') }}</h3>
                                            </div>
                                            <div class="block-content">
                                                <div class="row mb-20">
                                                    <div class="col-6">
                                                        <div class="p-20 text-left">
                                                            <i class="fa fa-2x fa-long-arrow-right text-danger"></i>
                                                        </div>
                                                        <div class="ml-5 text-left">
                                                            <p class="font-size-lg font-w600 mb-0">
                                                            {{ __('trip_i.trc_hotel_enter') }}
                                                            </p>
                                                            <p class="font-size-sm text-uppercase font-w600 text-muted mb-0">
                                                                <div><b>{{ __('trip_i.trc_hotel_country') }}</b> <?= GetCountryName($trip->hotel_country) ?></div>
                                                                        <div><b>{{ __('trip_i.trc_hotel_state') }}</b> <?= GetStateName($trip->hotel_country, $trip->hotel_state) ?></div>
                                                                        <div><b>{{ __('trip_i.trc_hotel_city') }}</b> <?= $trip->hotel_city ?></div>
                                                                        <div><b>{{ __('trip_i.trc_hotel_checkout') }}</b> 
                                                                    <?php if ($trip->hotel_checkout == 1) { ?>
                                                                        {{ __('trip_i.trc_hotel_normal') }}
                                                                    <?php } else if ($trip->hotel_checkout == 2) { ?>
                                                                        {{ __('trip_i.trc_hotel_later') }}
                                                                    <?php } ?>
                                                                    </div>
                                                                        <div><b>{{ __('trip_i.trc_hotel_date') }}</b> <?= date('Y-m-d', strtotime($trip->hotel_date)) ?></div>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-20 text-right">
                                                            <i class="fa fa-2x fa-long-arrow-left text-danger"></i>
                                                        </div>
                                                        <div class="ml-5 text-right">
                                                            <p class="font-size-lg font-w600 mb-0">
                                                            {{ __('trip_i.trc_hotel_exit') }}
                                                            </p>
                                                            <p class="font-size-sm text-uppercase font-w600 text-muted mb-0">
                                                                <div><b>{{ __('trip_i.trc_hotel_exit_date') }}</b> <?= date('Y-m-d', strtotime($trip->hotel_exit)) ?></div>
                                                                
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div>
                                                            <h5 class="mb-10">{{ __('trip_i.trc_hotel_address') }}</h5>
                                                            <spam class="text-muted"><?= $trip->hotel_address ?></spam>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if (count($peoples) > 0) { ?>
                                    <div class="col-md-12">
                                        <div class="block block-rounded block-bordered">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">Pessoas adicionais</h3>
                                            </div>
                                            <div class="block-content mb-20">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nome</th>
                                                                    <?php if ($has_approv == 1) { ?>
                                                                    <th>Bilhete</th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($peoples as $key) { ?>
                                                                    <tr>
                                                                        <td><?= $key->name ?></td>
                                                                        <form action="/trip/agency/people/do/<?= $gen ?>/<?= $planid ?>" method="post" enctype="multipart/form-data">
                                                                        <?php if ($has_approv == 1) { ?>
                                                                        <td>
                                                                            <?php if (!empty($key->ticket_url)) { ?>
                                                                                <a target="_blank" href="<?= $key->ticket_url ?>">
                                                                                    <button type="button" class="btn btn-sm btn-outline-primary mb-10">Ver arquivo</button>
                                                                                </a>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td>
                                                                            <input type="hidden" name="identity" value="<?= $key->identity ?>">
                                                                            <input type="file" id="people" name="people">
                                                                        </td>
                                                                        <td>
                                                                            <button type="submit" class="btn btn-sm btn-success mb-10">Atualizar</button>
                                                                        </td>
                                                                        <?php } ?>
                                                                        </form>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if ($other_agency_approv == 0) { ?>
                                    <form action="/trip/agency/budget/do/<?= $gen ?>/<?= $planid ?>" id="AgencyForm" method="post" enctype="multipart/form-data" style="width: 100%;">
                                    <?php } ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?php if ($other_agency_approv == 0) { ?>
                                            <label for="budget">Anexar orçamento de voo (pdf, jpg, png, gif)</label>
                                            <div class="custom-file">
                                                <input type="file" id="budget" name="budget">
                                            </div>
                                            <?php } ?>
                                            <?php if (!empty($trip_budget->budget_url)) { ?>
                                                <a target="_blank" href="<?= $trip_budget->budget_url ?>">
                                                    <button type="button" class="btn btn-sm btn-outline-info mb-10">Ver arquivo enviado</button>
                                                </a>
                                            <?php } ?>
                                            <?php if ($other_agency_approv == 0) { ?>
                                            <div class="form-group">
                                            <label for="hotel">Anexar orçamento de hotel (pdf, jpg, png, gif)</label>
                                            <div class="custom-file">
                                                <input type="file" id="hotel" name="hotel">
                                            </div>
                                            <?php } ?>
                                            <?php if (!empty($trip_budget->budget_hotel)) { ?>
                                                <a target="_blank" href="<?= $trip_budget->budget_hotel ?>">
                                                    <button type="button" class="btn btn-sm btn-outline-info mb-10">Ver arquivo enviado</button>
                                                </a>
                                            <?php } ?>
                                        </div>
                                        <?php if ($has_approv == 1) { ?>
                                            <div class="form-group">
                                                <label for="ticket">Anexar bilhete da passagem</label>
                                                <div class="custom-file">
                                                    <input type="file" id="ticket" name="ticket">
                                                    
                                                </div>
                                                <?php if (!empty($trip_budget->ticket_url)) { ?>
                                                    <a target="_blank" href="<?= $trip_budget->ticket_url ?>">
                                                        <button type="button" class="btn btn-sm btn-outline-info mb-10">Ver bilhete de passagem</button>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="ticket_hotel">Anexar bilhete do hotel</label>
                                                <div class="custom-file">
                                                    <input type="file" id="ticket_hotel" name="ticket_hotel">
                                                    
                                                </div>
                                                <?php if (!empty($trip_budget->ticket_hotel)) { ?>
                                                    <a target="_blank" href="<?= $trip_budget->ticket_hotel ?>">
                                                        <button type="button" class="btn btn-sm btn-outline-info mb-10">Ver bilhete de hotel</button>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <label for="description">Observação adicional</label>
                                                <textarea class="form-control" <?php if ($has_approv == 1 or $other_agency_approv == 1) { ?>disabled=""<?php } else { ?>id="description" name="description"<?php } ?> rows="6" placeholder="..."><?= $trip_budget->description ?></textarea>
                                        </div>

                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                        <div class="row">
                            <div class="col-12 text-center">
                                <h2>Cotação não foi encontrada!</h2>
                                <br><h5>Verifique se caso realmente esse é o link!</h5>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if ($other_agency_approv == 0 and $trip->is_completed == 0) { ?>
                        <div class="row">
                            <div class="col-12 text-left">
                                <button type="submit" class="btn btn-square btn-primary">Atualizar informações</button>
                            </div>
                        </div>
                        </form>
                        <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script>

    
    $(document).ready(function () {
        $(".chat").animate({ scrollTop: $(document).height() }, 1000);
        <?php if (Session::has('success')) { ?>
            iziToast.success({
                title: 'Sucesso!',
                message: '<?= Session::get('success') ?>',
            });
        <?php } Session::forget('success'); ?>
        <?php if (Session::has('error')) { ?>
            iziToast.success({
                title: 'Opss...',
                message: '<?= Session::get('error') ?>',
            });
        <?php } Session::forget('error'); ?>
        $("#AgencyForm").submit(function (e) { 
            $('#page-loader').addClass('show');
            
        });

        $("#sendMsg").submit(function (e) { 
            
            if ($("input[name='msg']").val() == "") {
                e.preventDefault();
            }
            
        });
        
        $('.js-dataTable-full-pagination').DataTable( {
            ordering: false,
            searching: true,
            paging: false,
            language: {
                search: "{{ __('layout_i.dtbl_search') }}",
                zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                info: "{{ __('layout_i.dtbl_info') }}",
                infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
                infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
            }
        });

    });
    </script>

                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->

        <!--
            Codebase JS

            Custom functionality including Blocks/Layout API as well as other vital and optional helpers
            webpack is putting everything together at assets/_es6/main/app.js
        -->
        <script src="/js/codebase.app.min.js"></script>
        <script src="/js/plugins/izitoast/iziToast.min.js"></script>

        <!-- Page JS Plugins -->
        <script src="/js/plugins/ckeditor/ckeditor.js"></script>

        <!-- Page JS Helpers (Summernote + CKEditor + SimpleMDE plugins) -->
        <script>jQuery(function(){ Codebase.helpers(['ckeditor']); });</script>

        </body>
</html>