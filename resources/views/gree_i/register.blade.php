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
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/themes/dark-layout.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/themes/semi-dark-layout.min.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/core/menu/menu-types/horizontal-menu.min.css">
    <link rel="stylesheet" type="text/css" href="admin/app-assets/css/plugins/extensions/toastr.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="admin/assets/css/style.css">
    <!-- END: Custom CSS-->
    <script src="https://www.google.com/recaptcha/api.js?render=6LeKg8wZAAAAAC_zzJxDMJo3Oc7A7aYPBFS0q2E6"></script>
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
                                <form class="needs-validation" action="/register/create" id="submitEdit" method="post" enctype="multipart/form-data">
                                  <input type="hidden" name="data_input" id="data_input">
                                    <div class="media mb-2">
                                      <a class="mr-2" href="#">
                                          <img src="/media/avatars/avatar10.jpg" alt="users avatar" id="avatar" class="users-avatar-shadow rounded-circle" height="64" width="64">
                                      </a>
                                      <div class="media-body">
                                          <h6 class="text-bold-600">FOTO DE PERFIL</h6>
                                          <input style="display: none" type="file" name="picture" id="picture" accept="image/x-png,image/gif,image/jpeg">
                                          <div class="col-12 px-0 d-flex">
                                              <a href="#" class="btn btn-sm btn-primary mr-25 changePic">Adicionar</a>
                                              <a href="#" class="btn btn-sm btn-light-secondary resetPic">Apagar</a>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="office">Cargo</label>
                                    <input type="text" id="office" name="office" class="form-control" placeholder="Gerente internacional" required>
                                  </div>
                                  <div class="form-group">
                                      <label>Setor de atuação</label>
                                      <select class="form-control" id="sector" name="sector" required>
                                          <option value=""></option>
                                          <?php foreach ($sector as $key) { ?>
                                          <option value="<?= $key->id ?>"><?= __('layout_i.'. $key->name .'') ?></option>
                                          <?php } ?>
                                      </select>
                                    </div>
                                    <div class="form-group">
                                      <label>Sede de operação</label>
                                      <select class="form-control" id="gree" name="gree" required>
                                          <option value=""></option>
                                          <option value="1">Gree China (zhuhai)</option>
                                          <option value="2">Gree Brazil (Manaus)</option>
                                          <option value="3">Gree Brazil (Sao Paulo)</option>
                                      </select>
                                    </div>
                                    <div class="form-group">
                                      <div class="controls">
                                          <label for="registration">Matricula</label>
                                          <input type="number" name="registration" class="form-control" placeholder="0000" required>
                                      </div>
                                  </div>
                                    <div class="form-group">
                                      <div class="controls">
                                          <label for="first_name">Primeiro nome</label>
                                          <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Jhon" required>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label>Sobrenome</label>
                                      <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Doe" required>
                                    </div>
                                    <div class="form-group">
                                      <div class="controls">
                                          <label for="birthday">Data de anivesário</label>
                                          <input type="text" id="birthday" name="birthday" class="form-control" required>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="controls">
                                          <label for="reg_email">E-mail</label>
                                          <input type="email" id="reg_email" name="reg_email" class="form-control" placeholder="jhon.doe@gree-am.com.br" required>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="controls">
                                          <label for="phone">Telefone</label>
                                          <input type="number" id="phone" name="phone" class="form-control" placeholder="9291434215" required>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Senha</label>
                                        <input type="text" class="form-control" id="password" name="password" placeholder="*******" required>
                                    </div>
                                    <div class="row">
                                      <div class="col-9">
                                          <div class="form-group">
                                              <label></label>
                                              <select class="js-select2 form-control" id="c_rcode" name="c_rcode" style="width: 100%;" data-placeholder="Seu imediato chefe" multiple>
                                                  <option></option>
                                                  <?php foreach ($usersall as $key) { ?>
                                                      <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                                  <?php } ?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-2">
                                          <a href="#" style="position: relative;left: -9px;top: 2px;" class="btn btn-info" id="addboss">Add</a>
                                      </div>
                                    </div>
                                    <div class="row imdts"></div>
                                  </div>
                                    <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                        <div class="text-left">
                                            
                                        </div>
                                        <div class="text-right"></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary glow w-100 position-relative">Criar uma conta<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                </form>
                                <div class="text-center mb-1 mt-2"><a href="/login" class="card-link"><small>Voltar para o login</small></a></div>
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
  <script src="/js/plugins/select2/js/select2.full.min.js"></script>
  <script src="/admin/app-assets/vendors/js/pickers/daterange/moment.min.js"></script>
  <script src="/admin/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>
  <script src="admin/app-assets/js/scripts/extensions/toastr.min.js"></script>
  <script src="/admin/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="/admin/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#avatar').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
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
    function error(msg) {
        toastr.error(msg, '', { "showDuration": 500, positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
        return;
    }
    function success(msg) {
        toastr.success(msg, '', { "showDuration": 500, positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
        return;
    }
		$(document).ready(function () {
			<?php if (Session::has('error')) { ?>
				error('<?= Session::get('error') ?>');
      <?php } Session::forget('error'); ?>

      <?php if (Session::has('success')) { ?>
				success('<?= Session::get('success') ?>');
      <?php } Session::forget('success'); ?>
      
      $(".js-select2").select2({
          maximumSelectionLength: 1,
      });
      $('#birthday').daterangepicker({
          singleDatePicker: true,
          showDropdowns: true,
          locale: {
              format: 'YYYY-MM-DD'
          },
          minYear: 1901,
          maxYear: parseInt(moment().format('YYYY'),10)
      });

      $("#submitEdit").submit(function (e) { 
          var form = $(".needs-validation");
          if (form[0].checkValidity() === false) {
              e.preventDefault();
          } else if (ArrayImmediate.length == 0) {
            error('Você precisa adicionar seu chefe imediato!');
            e.preventDefault();
          } else {
            e.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute('6LeKg8wZAAAAAC_zzJxDMJo3Oc7A7aYPBFS0q2E6', {action: 'gree_internal_create_user'}).then(function(token) {
                    $('#submitEdit').prepend('<input type="hidden" name="token" value="' + token + '">');
                    $('#submitEdit').prepend('<input type="hidden" name="action" value="gree_internal_create_user">');
                    $('#submitEdit').unbind('submit').submit();
                });
            });
              block();
          }                
          
      });

      $(".changePic").click(function (e) { 
          $('#picture').trigger('click');
      });

      $(".resetPic").click(function (e) { 
          $('#picture').val('');
          $('#avatar').attr('src', '<?php if (!empty($picture)) { echo $picture; } else { ?>/media/avatars/avatar10.jpg<?php } ?>');
      });
      
      $("#picture").change(function(){
          readURL(this);
      });
		});
  </script>
  <script src="/admin/app-assets/js/scripts/forms/form-tooltip-valid.min.js"></script>
    <script>
        var ArrayImmediate = new Array();
    
        function deleteImd(index) {
                Swal.fire({
                    title: 'Tem certeza disso?',
                    text: "Você irá remover o imediato chefe!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                    }).then(function (result) {
                    if (result.value) {
                        ArrayImmediate.splice(index, 1);
                        ReloadImmediate();
                        Swal.fire(
                        {
                            type: "success",
                            title: 'Removido',
                            text: 'Imediado foi removido.',
                            confirmButtonClass: 'btn btn-success',
                        }
                        )
                    }
                    })
        }
    
        function ReloadImmediate() {
            var list = "";
            for(var i = 0; i < ArrayImmediate.length; i++) {
                var arrayObj = ArrayImmediate[i];
                list += '<div class="col-12 mt-1" onclick="deleteImd('+ i +');">';
                list += '<a href="javascript: void(0);">';
                list += '<div class="media">';
                list += '<img src="'+ arrayObj.picture +'" class="rounded mr-75" alt="profile image" height="64" width="64">';
                list += '<div class="media-body mt-25">';
                list += '<div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">';
                list += '<h6>'+ arrayObj.name +'</h6>';
                list += '</div>';
                list += '<p class="text-muted"><small>'+ arrayObj.r_code +'</small></p>';
                list += '</div>';
                list += '</div>';
                list += '</a>';
                list += '</div>';
            }
            $(".imdts").html(list);
            $("#data_input").val(JSON.stringify(ArrayImmediate));
        }
        $(document).ready(function () {  
    
            $("#addboss").click(function (e) { 
                if ($("#c_rcode").val() == "") {
                    error('Preencha o número de matricula do imediato.');
                } else {
                    var rcode = $(".js-select2").select2('data')[0]['id'];
                    $('.js-select2').val(0).trigger("change");
                    block();
                    $.ajax({
                        type: "POST",
                        timeout: 5000,
                        url: "/user/ajax/immediate",
                        data: {c_rcode:rcode, registration:$("#registration").val()},
                        success: function (response) {
                            unblock();
                            if (response.success) {
                                for(var i = 0; i < ArrayImmediate.length; i++) {
                                    var arrayObj = ArrayImmediate[i];
                                    if (arrayObj.r_code == rcode) {
    
                                        error('Você já adicionou esse imediato a sua lista.');
                                        return;
                                    }
                                }

                                success('Imediato disponível para anexar ao cadastro.');
    
                                if (ArrayImmediate.length == 0) {
                                  ArrayImmediate.push({
                                      "r_code" : response.r_code, 
                                      "name" : response.name,
                                      "picture" : response.picture,
                                  });
                                  ReloadImmediate();
                                } else {
                                  error('Você só pode ter um imediato chefe. Exclua clicando em clicando em cima para adicionar outro.');
                                }
                            } else {
                                error(response.error);
                            }
                            
                        },
                        error: function(jqXHR, textStatus){
                            if(textStatus == 'timeout')
                            {     
                                
                                error('Ocorreu um erro na sua conexão, tente novamente!');
                            }
                        }
                    });  
                }
                
            });
    
        });
        </script>
    <!-- END: Page JS-->

  
  <!-- END: Body-->
</body></html>