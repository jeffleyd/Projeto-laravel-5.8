
        <script>
            var socket = io.connect('https://gree-app.com.br:3000');
            socket.emit('user', {
                id: '<?= Session::get('r_code') ?>',
                picture: '@if (empty(Session::get('picture'))) /media/avatars/avatar10.jpg @else {{ Session::get('picture') }} @endif',
                name: '<?= getENameF(Session::get('r_code')); ?>',
                sector: '<?= sectorName(Session::get('sector')) ?>',
                version: 2,
            });
			@if (Request::path() != "chat/main")
			socket.on('chat message', function(data){
				if (data.receiver == '<?= Session::get('r_code') ?>') {
					toastr.options.onclick = function() { window.location.href = "/chat/main"; }
					toastr.info(data.msg, data.name, { positionClass: 'toast-top-right', containerId: 'toast-top-right', "showDuration": "300", hideDuration: "1000", timeOut: "30000", extendedTimeOut: "1000", });
					return;
				}
			});
			@endif
			function admSearchCode() {
                if ($("#admrequestcode").val() == "") {

                    return $error('Você precisa preencher o código para realizar a busca!');
                }
                block();
                $('#admRequestSearch').modal('toggle');
                window.location.href = '/administration/generic/request/view?s='+$("#admrequestcode").val();

            }
			function admEntryExitApprov() {
                if ($("#modal_is_entry_exit").val() == 3)
                    window.location.href = '/adm/entry-exit/approv/employees/list';
                else if ($("#modal_is_entry_exit").val() == 2)
                    window.location.href = '/logistics/request/cargo/transport/approv/list';
                else if ($("#modal_is_entry_exit").val() == 1)
                    window.location.href = '/logistics/request/visitor/service/list/approv';

                $('#admRequestEntryExit').modal('toggle');

            }
            function error(msg) {
                toastr.error(msg, '', { "showDuration": 500, positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
                return;
            }
            function $error(msg) {
                toastr.error(msg, '', { "showDuration": 500, positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
                return;
            }
            function success(msg) {
                toastr.success(msg, '', { "showDuration": 500, positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
                return;
            }
            function $success(msg) {
                toastr.success(msg, '', { "showDuration": 500, positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
                return;
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
			function ajaxSend(url, data = '', method = 'GET', timeout = 10000, form = '', enctype = 'multipart/form-data') {
                
                let $param = {
                    type: method,
                    timeout: timeout,
                    url: url,
                    data: data,
                };
                
                if (method == 'POST') {
                    $param.enctype = enctype;
                }
                if (form != '') {
                    var data = new FormData(form[0]);
                    $param.enctype = enctype;
                    $param.processData = false;
                    $param.contentType = false;
					$param.data = data;
                }

				var objeto = new Promise(function(resolve, reject) {

                    $param.success = function (response) {
                            if(response.success==true){
                                resolve(response);
                            }
                            if(response.success==false){
                                let message = 'Erro no processamento de sua solicitação, comunique a equipe de desenvolvimento.';
                                if(response.message){
                                    message = response.message;
                                }
                                if(response.msg){
                                    message = response.msg;
                                }
                                response.message = message;
                                reject(response);
                            }
                            resolve(response);
                        };
                    $param.error =  function(jqXHR, textStatus, errorMessage){
                        if (jqXHR.status === 0) {
                            reject({'message': 'Sem conexão, verifique sua conexão com a internet.'});
                        } else if (jqXHR.status == 404) {
                            reject({'message': 'Página não foi encontrada, comunique a equipe de desenvolvimento.'});
                        } else if (jqXHR.status == 500) {
                            reject({'message': 'Erro interno do servidor, comunique a equipe de desenvolvimento.'});
                        } else if (jqXHR.status == 502) {
                            reject({'message': 'Bad Gateway, comunique a equipe de desenvolvimento.'});
                        } else if (textStatus === 'parsererror') {
                            reject({'message': 'Erro ao tratar objeto JSON, comunique a equipe de desenvolvimento.'});
                        } else if (textStatus === 'timeout') {
                            reject({'message': 'Sua conexão demorou muito a responder, tente novamente!'});
                        } else if (textStatus === 'abort') {
                            reject({'message': 'Solicitação foi recusada, tente novamente!'});
                        } else {
                            if(jqXHR.responseJSON){
                                if(jqXHR.responseJSON.message){
                                    reject({'message': jqXHR.responseJSON.message, 'response':jqXHR});
                                }
                                if(jqXHR.responseJSON.msg){
                                    reject({'message': jqXHR.responseJSON.msg, 'response':jqXHR});
                                }
                            }
                            
                            reject({'message': 'Erro no processamento de sua solicitação, comunique a equipe de desenvolvimento.', 'response':jqXHR});
                        }
                    }
                    $.ajax($param);
				});
				
				return objeto;
			}
            function Notify() {
                $.ajax({
                    type: "GET",
                    timeout: 10000,
                    url: "/notifications/get",
                    success: function (response) {
                        var list = "";
                        for (var i = 0; i < response.inc; i++) {
                            var has_read = response.notify[i][5] == 1 ? "read-notification" : "";
                            list += '<a href="javascript:void(0);" id="notify'+response.notify[i][7]+'" onclick="readNotifyOnly(\''+response.notify[i][7]+'\',\''+response.notify[i][3]+'\', \''+response.notify_count+'\');">';
                            list += '<div class="d-flex justify-content-between '+ has_read +' cursor-pointer">';
                            list += '<div class="media d-flex align-items-center">';
                            list += '<div class="media-left pr-0" style="margin-right:20px">';
                            list += '<i class="fa '+ response.notify[i][0] +' '+ response.notify[i][1] +'"></i>';
                            list += '</div>';
                            list += '<div class="media-body">';
                            list += '<h6 class="media-heading"><b>'+ response.notify[i][6] +'</b><br>'+ response.notify[i][2] +'</h6>';
                            list += '</div>';
                            list += '</div>';
                            list += '</div>';
                            list += '</a>';
                        }
                        
                        $(".badge-up").html(response.notify_count);
                        if (response.notify_count > 0) {
                            $(".notifyTotal").html(response.notify_count + ' <?= __('layout_i.srt_new_notify') ?>');
                            $(".badge-up").show();
                            $(".bx-bell").addClass("bx-tada bx-flip-horizontal");
                        } else {
                            $(".notifyTotal").html('<?= __('layout_i.srt_empty_notify') ?>');
                            $(".badge-up").hide();
                            $(".bx-bell").removeClass("bx-tada bx-flip-horizontal");
                        }
                        $(".notify").html(list);
                    }
                });
            }

            function readNotifyOnly(id, link, count) {
                $.ajax({
                    type: "POST",
                    url: "/notifications/read/only",
                    data: {id: id},
                    success: function (response) {
                        $("#notify"+id+"").hide();
                        $(".badge-up").html(count-1);
                        window.open(link);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        error('Erro ao mudar status para lido!');
                    }
                });
            }

            function sendAswer() {
                block();
                $.ajax({
                    type: "POST",
                    url: "/misc/survey/user",
                    data: $("#formQuestion").serialize(),
                    success: function (response) {
                        unblock();
                        $('#covid19').modal('toggle');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        unblock();
                        alert('Não foi possível enviar, tente novamente!');
                    }
                });
            }

            $(document).ready(function () {
                @if (!Session::get('picture'))
                    $('#modal-pic').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    $("#picsend").submit(function (e) { 
                        if ($('#picture').val() == "") {
                            error('É necessário anexar uma foto de perfil, antes de enviar.')
                            e.preventDefault();
                            return;
                        }
                        block();
                        $('#modal-pic').modal('toggle');
                    });
                @endif
                <?php if (!Session::has('s_report')) { ?>
                    $('#covid19').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                <?php } else if (Session::get('s_report') == 0) { ?>
                    $('#covid19').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                <?php } ?>

                <?php if (Session::has('success')) { ?>
                    success("<?= Session::get('success') ?>");
                <?php } Session::forget('success'); ?>
                <?php if (Session::has('error')) { ?>
                    error("<?= Session::get('error') ?>");
                <?php } Session::forget('error'); ?>
                requestPermission();
                Notify();
                $(".readNotify").click(function (e) {
                    $.ajax({
                        type: "POST",
                        timeout: 10000,
                        url: "/notifications/read",
                        success: function (response) {
                            Notify();
                        }
                    });   
                });

                <?php if (Session::get('user_version') != getConfig("version_number")) { ?>
                <?php updateUser(Session::get('r_code'), getConfig("version_number"));?>
                    $('#modal-version').modal();
                <?php } ?>

            });
    </script>