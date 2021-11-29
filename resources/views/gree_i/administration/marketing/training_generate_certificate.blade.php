<html  lang="en" >
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="robots" content="noindex, nofollow">

    <title>Gree - System Internal</title>
    <link rel="apple-touch-icon" href="/admin/app-assets/images/ico/favicon-192x192.png">
    <link rel="shortcut icon" type="image/x-icon" href="/admin/app-assets/images/ico/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700"
        rel="stylesheet">

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/components.css">
	<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/dark-layout.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/semi-dark-layout.min.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/core/menu/menu-types/horizontal-menu.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/extensions/toastr.min.css">
    <!-- END: Page CSS-->

	<script src="/admin/app-assets/js/jquery-3.4.1.min.js"></script>
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css">

    <!-- END: Custom CSS-->

</head>

<body
    class="horizontal-layout navbar-sticky 1-column footer-static blank-page blank-page pace-done menu-expanded horizontal-menu"
    data-open="hover" data-menu="horizontal-menu" data-col="1-column">
    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99"
            style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper" style="margin-top:0px !important">
            <div class="content-body">
                <!-- login page start -->
                <section id="auth-login" class="row flexbox-container">
                    <div class="col-xl-4 col-11">
                        <div class="card bg-authentication mb-0">
                            <div class="row m-0">
                                <!-- left section-login -->
                                <div class="col-md-12 col-12 px-0">
                                    <div
                                        class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                        <div class="card-header pb-1">
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="d-flex flex-md-row flex-column justify-content-around">
                                                    <img class="logo"
                                                        src="/admin/app-assets/images/logo/logo_gree_blue.png">
                                                </div>
                                                <div class="divider">
                                                </div>
                                                <form class="needs-validation"
                                                    action="/administration/marketing/training/certificate"
                                                    id="submitEdit" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>Treinamento</label>
                                                        <select class="form-control" id="training" name="training"
                                                            required>
                                                            <option value='0'></option>
                                                            @foreach ($training as $item)
                                                                <option value='{{ $item->id }}'>{{ $item->name }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cpf">CPF</label>
                                                        <input type="text" id="cpf" name="cpf" class="form-control"
                                                            placeholder="Informe o CPF" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="fullName">Nome</label>
                                                            <input type="text" id="fullName" name="fullName"
                                                                class="form-control" placeholder="Informe o nome"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="row imdts"></div>
                                            </div>
                                            <button type="submit"
                                                class="btn btn-primary glow w-100 position-relative">Gerar
                                                Certificado<i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
                                            </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right section image -->

                        </div>
                    </div>
            </div>
            </section>
            <!-- login page ends -->

        </div>
    </div>
    </div>
    <!-- END: Content-->

    <!-- BEGIN: Theme JS-->
    <script src="/admin/app-assets/js/scripts/configs/horizontal-menu.min.js"></script>
	<script src="/admin/app-assets/vendors/js/extensions/toastr.min.js"></script>
    <script src="/admin/app-assets/js/scripts/extensions/toastr.min.js"></script>
    <!-- END: Theme JS-->
    <script>
		
		function $error(msg) {
			toastr.error(msg, '', { "showDuration": 500, positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
			return;
		}
		
		function $success(msg) {
			toastr.success(msg, '', { "showDuration": 500, positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
			return;
		}
		
		$('document').ready(function() {
			<?php if (Session::has('success')) { ?>
				$success('<?= Session::get('success') ?>');
			<?php } ?>
			<?php if (Session::has('error')) { ?>
				$error('<?= Session::get('error') ?>');
			<?php } ?>
		});
		
    </script>

</body>

</html>
