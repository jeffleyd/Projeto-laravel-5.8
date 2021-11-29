<html class="loaded" lang="en" data-textdirection="ltr"><!-- BEGIN: Head--><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<meta name="robots" content="noindex, nofollow">
	
    <title>Gree - System Internal</title>
    <link rel="apple-touch-icon" href="admin/app-assets/images/ico/favicon-192x192.png">
    <link rel="shortcut icon" type="image/x-icon" href="admin/app-assets/images/ico/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="admin/app-assets/vendors/css/vendors.min.css">
	<link rel="stylesheet" type="text/css" href="admin/app-assets/vendors/css/extensions/toastr.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/themes/dark-layout.min.css">
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/themes/semi-dark-layout.min.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/core/menu/menu-types/horizontal-menu.min.css">
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/plugins/extensions/toastr.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="admin/assets/css/style.css">
    <!-- END: Custom CSS-->
    <!-- Google Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  
  ga('create', 'UA-144619391-11', 'auto');
  ga('send', 'pageview');
  </script>
  <!-- End Google Analytics -->

  </head>
  <!-- END: Head-->

  <!-- BEGIN: Body-->
  <body class="horizontal-layout navbar-sticky 1-column footer-static blank-page blank-page pace-done menu-expanded horizontal-menu" data-open="hover" data-menu="horizontal-menu" data-col="1-column"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="content-wrapper" style="margin-top:0px !important">
        <div class="content-body"><!-- login page start -->
<section id="auth-login" class="row flexbox-container">
    <div class="col-xl-4 col-11">
        <div class="card bg-authentication mb-0">
            <div class="row m-0">
                <!-- left section-login -->
                <div class="col-md-12 col-12 px-0">
                    <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                        <div class="card-header pb-1">
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="d-flex flex-md-row flex-column justify-content-around">
                                    <img class="logo" src="admin/app-assets/images/logo/logo_gree_blue.png">
                                </div>
                                <div class="divider">
                                </div>
                                <form action="/login/verify" id="submitLogin" method="post">
                                    <div class="form-group mb-50">
                                        <label class="text-bold-600" for="r_code">Matricula</label>
                                        <input type="number" class="form-control" name="r_code" id="r_code" placeholder="0000"></div>
                                    <div class="form-group">
                                        <label class="text-bold-600" for="password">Senha</label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="********">
                                    </div>
                                    <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                        <div class="text-left">
                                            
                                        </div>
                                        <div class="text-right"></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary glow w-100 position-relative">Acessar<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                </form>

                                <div class="text-center mb-1 mt-1"><a href="/register" class="card-link"><small>Criar uma conta</small></a></div>
                                
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

	  <!--info theme Modal -->
	  <div class="modal fade text-left" id="termsandconditions" tabindex="-1" role="dialog" aria-labelledby="termsandconditions" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		  <div class="modal-content">
			<div class="modal-header bg-info">
			  <h5 class="modal-title white" id="termsandconditions">Termos & Condições</h5>
			</div>
			<div class="modal-body">
			  <p><b>1º</b> Deixe anotado sua senha e sua matricula, caso houver a perca, terá que solicitar ao administrativo atualização da mesma. </p>

					<p><b>2º</b> Todos os dados dentro do painel da GREE são confidenciais, sendo assim, não poderá compartilhar, enviar ou imprimir qualquer dados do painel.</p>

					<p><b>3º</b> Todas as ações de gravar, atualizar ou deletar informações, são armazenadas em nosso servidor em forma de log para futuras consultas.</p>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-info ml-1" data-dismiss="modal">
				<i class="bx bx-check d-block d-sm-none"></i>
				<span class="d-none d-sm-block" id="acceptTerms">ACEITO</span>
			  </button>
			</div>
		  </div>
		</div>
	  </div>
	</div>
    <!-- BEGIN: Vendor JS-->
    <script src="admin/app-assets/vendors/js/vendors.min.js"></script>
    <script src="admin/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.min.js"></script>
    <script src="admin/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.min.js"></script>
    <script src="admin/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="admin/app-assets/vendors/js/ui/jquery.sticky.js"></script>
	<script src="admin/app-assets/vendors/js/extensions/toastr.min.js"></script>
  <script src="admin/app-assets/js/scripts/extensions/toastr.min.js"></script>
  <script src="/admin/app-assets/vendors/js/ui/blockUI.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="admin/app-assets/js/scripts/configs/horizontal-menu.min.js"></script>
    <script src="admin/app-assets/js/core/app-menu.min.js"></script>
    <script src="admin/app-assets/js/core/app.js"></script>
    <script src="admin/app-assets/js/scripts/components.js"></script>
    <script src="admin/app-assets/js/scripts/footer.min.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
	<script>
    function block() { 
        $.blockUI({
            message: '<span class="spinner-border text-primary spinner-border-sm" role="status" aria-hidden="true"></span> <span style="position: relative;top: 1px;left: 5px;">Por favor, aguarde...</span>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }
    function unblock() {
        $('body').unblock();
    }
	function ChangeUser() {
      localStorage.removeItem("rcode");
      localStorage.removeItem("picture");

      window.location.href = "/login"; 
	  }
		$(document).ready(function () {
			<?php if (Session::has('error')) { ?>
				toastr.error('<?= Session::get('error') ?>', '', { positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
      <?php } Session::forget('error'); ?>
      <?php if (Session::has('success')) { ?>
				toastr.success('<?= Session::get('success') ?>', '', { positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
			<?php } Session::forget('success'); ?>
			if (localStorage.terms == null) {
				$('#termsandconditions').modal({
					backdrop: 'static',
					keyboard: false,
				});
			}
			if (localStorage.rcode != null) {
				var screen = "";
					screen += '<div class="d-flex flex-md-row flex-column justify-content-around">';
					screen +=		  '<img class="logo" src="admin/app-assets/images/logo/logo_gree_blue.png">';
                    screen +=            '</div>';
                    screen +=            '<div class="d-flex flex-md-row flex-column justify-content-around">';
                    screen +=              ' <div class="avatar mr-1 avatar-xl" style="display: flex; align-self: center;">';
					screen +=			'	  <img src="'+ localStorage.picture +'" style="height: 100px;width: 100px; object-fit: cover;" alt="avtar img holder">';
					screen +=			'	</div>';
                    screen +=            '</div>';
                    screen +=            '<div class="divider">';
                    screen +=            '</div>';
                    screen +=            '<form action="/login/verify" id="submitLogin" method="post">';
					screen += '<input type="hidden" value="'+ localStorage.rcode +'" class="form-control" id="r_code" name="r_code">';
                    screen +=            '    <div class="form-group">';
                    screen +=            '        <label class="text-bold-600" for="password">Senha</label>';
                    screen +=            '        <input type="password" class="form-control" name="password" id="password" placeholder="********">';
                    screen +=            '    </div>';
                    screen +=            '    <div class="text-center mb-1"><a href="#" onclick="ChangeUser();" class="card-link"><small>Essa conta não é sua ?</small></a></div>';
                    screen +=            '    <button type="submit" class="btn btn-primary glow w-100 position-relative">Acessar<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>';
                    screen +=            '</form>';

				$(".card-body").html(screen);
			}
			$("#acceptTerms").click(function (e) {
				localStorage.terms = 1;
				$('#termsandconditions').modal('toggle');
			});

      $("#submitLogin").submit(function (e) { 
        if ($("#r_code").val() == "") {
          toastr.error('Field "Matricula" is required!', '', { positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
          e.preventDefault();
        } else if ($("#password").val() == "") {
          toastr.error('Field "Senha" is required!', '', { positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
          e.preventDefault();
        }

        e.preventDefault();
        block();
        $.ajax({
          type: "post",
          url: "/login/verify",
          data: $("#submitLogin").serialize(),
          success: function (response) {
            unblock();
            if (response.success) {

              if (response.url == "") {

                    var screen = "";
                              screen +=            '<div class="d-flex flex-md-row flex-column justify-content-around">';
                              screen +=              ' <div class="avatar mr-1 avatar-xl" style="display: flex; align-self: center;">';
                    screen +=			'	  <i class="bx bxs-lock" style="font-size: 80px;"></i>';
                    screen +=			'	</div>';
                              screen +=            '</div>';
                              screen +=            '<div class="divider">';
                              screen +=            '</div>';
                              screen +=            '<form action="/optauth/verify" id="submitOptauth" method="post">';
                    screen += '<input type="hidden" value="'+ localStorage.rcode +'" class="form-control" id="r_code" name="r_code">';
                              screen +=            '    <div class="form-group">';
                              screen +=            '        <label class="text-bold-600" for="password">Código de autenticação</label>';
                              screen +=            '        <input type="text" class="form-control" autocomplete="false" name="pin" id="pin" placeholder="00000">';
                              screen +=            '    </div>';
                              screen +=            '    <button type="submit" class="btn btn-primary glow w-100 position-relative">AUTENTICAR</button>';
                              screen +=            '</form>';

                  $(".card-body").html(screen);

                  $("#submitOptauth").submit(function (e) { 
                      e.preventDefault();
					  if ($('#pin').val() != '') {
						block();
						  $.ajax({
							type: "POST",
							url: "/optauth/verify",
							data: $("#submitOptauth").serialize(),
							success: function (response) {
							  unblock();
							  if (response.success) {

								window.location.href = response.url;
							  } else {
								$('#pin').val('');
								toastr.error(response.msg, '', { positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
							  }
							}
						  });  
					  }
                    });
              } else {

                window.location.href = response.url;
              }
            } else {

              toastr.error(response.msg, '', { positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
            }
          }
        });

      });
		});
	</script>
    <!-- END: Page JS-->

  
  <!-- END: Body-->
</body></html>