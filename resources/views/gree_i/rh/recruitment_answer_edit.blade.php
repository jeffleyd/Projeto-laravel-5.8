
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="robots" content="noindex, nofollow">
    
    <title>Gree - System Internal</title>
    <link rel="apple-touch-icon" href="/admin/app-assets/images/ico/favicon-192x192.png">
    <link rel="shortcut icon" type="image/x-icon" href="/admin/app-assets/images/ico/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/semi-dark-layout.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/all.css">
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/extensions/toastr.min.css">
    <script src="/admin/app-assets/js/jquery-3.4.1.min.js"></script>

    <style>
        .app-content {
            -webkit-filter: blur(8px);
            filter: blur(8px);
        }
        .div_time {
            position: absolute;
            right: 0;
            top: 0px;
        }
        .radio label::before {
            border: 1px solid #81c0ff;
        }
    </style>
</head>
<body class="horizontal-layout horizontal-menu 2-columns" data-open="hover" data-menu="horizontal-menu" data-col="2-columns">
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="card">
                <div class="user-profile-images pt-2 px-3" style="background-color: #5A8DEE!important;">
                    <div class="brand-logo text-center">
                        <img class="logo" src="/admin/app-assets/images/logo/logo_gree.png">
                    </div>
                    <div class="float-sm-right text-center text-white p-1" id="time_countdown">01:00:00</div>
                    <div class="d-block">
                        <h4 class="title text-center text-white">{{$title}}</h4>
                        <p class="mb-1 text-center text-white">{{$description}}</p>
                    </div>    
                </div>

                <div class="container">
                    <div class="card-content" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="card-header"></div>

                        <div class="progress progress-bar-primary mb-1 progress-sm">
                            <div class="progress-bar progress-test" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div>
                        </div>

                        <div class="card-body" id="body_question"></div>
                        <button type="button" id="btn_prox_question" class="btn btn-primary shadow ml-2 mb-2">Próxima questão</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="edit_modal_instructions" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="termsandconditions">INSTRUÇÕES PARA A REALIZAÇÃO DA PROVA</h5>
                    <div class="d-flex flex-md-row flex-column justify-content-around">
                        <img class="logo" src="/admin/app-assets/images/logo/logo_gree.png">
                    </div>
                </div>
                <div class="modal-body" style="text-align: justify;">
                    <?= nl2br($instructions) ?>                    
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ml-1">
                        <span class="d-sm-block" id="btn_begin_test">Iniciar prova</span>
                    </button>
                </div>
            </div>    
        </div> 
    </div>

    <div class="modal fade show" id="modal_approved" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="row m-0">
                    <div class="col-md-12 col-12 px-0">
                        <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                            <div class="card-body text-center">
                                <i class="bx bx-check-circle font-large-4" id="icon_approv" style="margin-bottom: 20px;color: #39da8a;"></i>
                                <i class="bx bx-x-circle font-large-4" id="icon_repprov" style="margin-bottom: 20px;color: #ff0f10; display:none;"></i>
                                <h5 class="text-center" id="title_concluded">Você foi aprovado!</h5>
                                <p class="text-center text-muted" id="description_concluded">Em breve entraremos em contato, obrigado!</p>
                                <div class="col-sm-12 col-md-12 text-center">
                                    <form method="post" action="/recruitment/logout">
                                        <button type="submit" class="btn btn-danger" id="btn_concluded">
                                            <span class="d-sm-block">Sair</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <!-- BEGIN: Vendor JS-->
    <script src="/admin/app-assets/vendors/js/vendors.min.js"></script>
    <script src="/admin/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.min.js"></script>
    <script src="/admin/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.min.js"></script>
    <script src="/admin/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="/admin/app-assets/vendors/js/ui/jquery.sticky.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/swiper.min.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/toastr.min.js"></script>
    <script src="/admin/app-assets/js/scripts/extensions/toastr.min.js"></script>
    <!-- END: Page Vendor JS-->

    <script>

        history.pushState(null, null, window.location.href);
        history.back();
        window.onpopstate = () => history.forward();

        var arr_questions = {!! json_encode($questions_id) !!};
        var candidate_id = {{ $candidate_id }};
        var test_id = {{ $test_id }};
        var is_concluded = {{ $is_concluded }};
        var is_approved = {{ $is_approved }};
        var next_question = 0;
        var arr_response = [];
        var timecount = "{{ $time }}";

        $(document).ready(function () {

            if(is_concluded == 1 && is_approved == 1) {
                $("#modal_approved").modal('show');

                $("#icon_approv").css('display', '');
                $("#icon_repprov").css('display', 'none');
                $("#title_concluded").text('Você foi aprovado!');
                $("#description_concluded").text('Em breve entraremos em contato, obrigado!');
                $('#btn_concluded').addClass('btn-success').removeClass('btn-danger');

            } 
            else if(is_concluded == 1 && is_approved == 0) {
                $("#modal_approved").modal('show');

                $("#icon_repprov").css('display', '');
                $("#icon_approv").css('display', 'none');
                $("#title_concluded").text('Você foi reprovado!');
                $("#description_concluded").text('Obrigado por sua participação!');
                $('#btn_concluded').addClass('btn-danger').removeClass('btn-success');
            }
            else {
                $('#edit_modal_instructions').modal('show');
            }
            
            $("#btn_begin_test").click(function() {

                block();
                $.ajax({
                    type: "GET",
                    timeout: 1000,
                    url: "/recruitment/question/ajax",
                    data: {question_id: arr_questions[next_question]},
                    success: function (response) {

                        $("#body_question").html(reloadQuestions(response.question));
                        unblock();
                        setBlur(0);
                        $('#edit_modal_instructions').modal('hide');

                        $("#body_question").html(reloadQuestions(response.question));
                        next_question = next_question + 1;

                        var perc = (next_question * 100)/arr_questions.length;
                        $('.progress-test').css('width', perc+'%');

                        countdownTimeStart(timecount);

                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        unblock();
                        errorsAjax(xhr, ajaxOptions, thrownError);
                    }
                }); 
            });

            $("#btn_prox_question").click(function(e) {

                name_question = $('.radio_val').attr('name');
                if($("input[name="+name_question+"]").is(':checked')) {

                    var question = $("input[name="+name_question+"]:checked").attr('data-question');
                    var option = $("input[name="+name_question+"]:checked").val();

                    obj_answer = {
                        'question_id': question,
                        'option_id': option,
                    };

                    arr_response.push(obj_answer);

                    if(arr_questions.length != next_question) {

                        block();
                        $.ajax({
                            type: "GET",
                            timeout: 1000,
                            url: "/recruitment/question/ajax",
                            data: {question_id: arr_questions[next_question]},
                            success: function (response) {

                                $("#body_question").html(reloadQuestions(response.question));
                                unblock();
                                next_question = next_question + 1;

                                var perc = (next_question * 100)/arr_questions.length;
                                $('.progress-test').css('width', perc+'%');
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                unblock();
                                errorsAjax(xhr, ajaxOptions, thrownError);
                            }
                        }); 
                    } else {

                        block();
                        $.ajax({
                            type: "POST",
                            timeout: 1000,
                            url: "/recruitment/question/response",
                            data: {
                                candidate_id: candidate_id,
                                test_id: test_id,
                                arr_response: arr_response
                            },
                            success: function (response) {  
                                
                                setBlur(8);
                                if(response.is_approv) {
                                    $("#icon_approv").css('display', '');
                                    $("#icon_repprov").css('display', 'none');
                                    $("#title_concluded").text('Você foi aprovado!');
                                    $("#description_concluded").text('Em breve entraremos em contato, obrigado!');
                                    $('#btn_concluded').addClass('btn-success').removeClass('btn-danger');
                                } else {
                                    $("#icon_repprov").css('display', '');
                                    $("#icon_approv").css('display', 'none');
                                    $("#title_concluded").text('Você foi reprovado!');
                                    $("#description_concluded").text('Obrigado por sua participação!');
                                    $('#btn_concluded').addClass('btn-danger').removeClass('btn-success');
                                }

                                $('#modal_approved').modal('show');
                                unblock();
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                unblock();
                                errorsAjax(xhr, ajaxOptions, thrownError);
                            }
                        });
                    }

                } else {
                    return $error('Selecione uma resposta para passar para próxima questão!');
                }
            });

            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#msurvey").addClass('active');
                
            }, 100);
        });

        function setBlur(val) {
            var filterVal = 'blur('+val+'px)';
            $('.app-content')
                .css('filter',filterVal)
                .css('webkitFilter',filterVal);
        }

        function reloadQuestions(object) {

            var question = next_question + 1;

            var html = '';
            html += '<div class="row control-group">';
            html += '    <div class="col-12">';    
            html += '       <p class="text-left" style="color:#5a8dee;"><b>Questão '+ question +'</b></p>';
            html += '    </div>';
            html += '    <div class="col-12">';
            html += '        <div class="form-group row">';
            html += '            <div class="col-12">';
            html += '               <p class="text-left">';
            html += '                   '+ object.title +'';
            html += '               </p>';
            html += '            </div>';
            html += '        </div>';

            for (var i = 0; i < object.recruitment_test_questions_answer.length; i++) {

                var column = object.recruitment_test_questions_answer[i];

                html += '<div class="form-group row" style="margin-bottom: -2rem;">';
                html += '    <div class="form-group col-md-6 col-12" style="padding-left: 10px;padding-right: 10px;">';
                html += '        <fieldset>';
                html += '            <div class="radio radio-primary radio-glow">';
                html += '                <input type="radio" class="radio_val" value="'+column.id+'" data-question="'+column.recruitment_test_questions_id+'" required="" id="question_'+column.recruitment_test_questions_id+'_'+column.id+'" name="question_'+column.recruitment_test_questions_id+'">';
                html += '                <label for="question_'+column.recruitment_test_questions_id+'_'+column.id+'" style="width: fit-content;"><pre style="margin-top: revert;font-weight: 400;padding: 10px; background-color: #fafaff;">'+htmlEntities(column.description)+'</pre></label>';
                html += '            </div>';
                html += '        </fieldset>';
                html += '    </div>';
                html += '</div>';
            }

            html += '    </div>';
            html += '</div>';
            return html;
        }

        function htmlEntities(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function errorsAjax(jqXHR, textStatus, errorMessage) {

            if (jqXHR.status === 0) {
                return $error('Sem conexão, verifique sua conexão com a internet.');
			} else if (jqXHR.status == 404) {
				return $error('Página não foi encontrada, comunique a equipe de desenvolvimento.');
			} else if (jqXHR.status == 500) {
				return $error('Erro interno do servidor, comunique a equipe de desenvolvimento.');
			} else if (jqXHR.status == 502) {
				return $error('Bad Gateway, comunique a equipe de desenvolvimento.');
			} else if (textStatus === 'parsererror') {
				return $error('Erro ao tratar objeto JSON, comunique a equipe de desenvolvimento.');
			} else if (textStatus === 'timeout') {
				return $error('Sua conexão demorou muito a responder, tente novamente!');
			} else if (textStatus === 'abort') {
				return $error('Solicitação foi recusada, tente novamente!');
			} else {
				if(jqXHR.responseJSON){
					if(jqXHR.responseJSON.message){

                        return $error('message:' + jqXHR.responseJSON.message + 'response' + jqXHR);
					}
					if(jqXHR.responseJSON.msg){

                        return $error('message:' + jqXHR.responseJSON.msg + 'response' + jqXHR);
					}
				}
				return $error('Erro no processamento de sua solicitação, comunique a equipe de desenvolvimento. response: '+jqXHR);
			}    
        }

        function countdownTimeStart(time){

            var countDownDate = toDateWithOutTimeZone(time).getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

                // Get todays date and time
                var now = new Date().getTime();
                
                // Find the distance between now an the count down date
                var distance = countDownDate - now;
                
                // Time calculations for days, hours, minutes and seconds
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Output the result in an element with id="demo"
                document.getElementById("time_countdown").innerHTML = hours + "h "
                + minutes + "m " + seconds + "s ";
                
                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);

                    block();
                    $.ajax({
                        type: "POST",
                        timeout: 1000,
                        url: "/recruitment/question/response/timeout",
                        data: {
                            candidate_id: candidate_id,
                            test_id: test_id
                        },
                        success: function (response) {  
                            
                            setBlur(8);
                            if(response.is_approv == false) {
                                $("#icon_repprov").css('display', '');
                                $("#icon_approv").css('display', 'none');
                                $("#title_concluded").text('Você foi reprovado!');
                                $("#description_concluded").text('Obrigado por sua participação!');
                                $('#btn_concluded').addClass('btn-danger').removeClass('btn-success');
                            }

                            $('#modal_approved').modal('show');
                            unblock();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            unblock();
                            errorsAjax(xhr, ajaxOptions, thrownError);
                        }
                    });

                    document.getElementById("time_countdown").innerHTML = "Tempo encerrado";
                }
            }, 1000);
        }

        function toDateWithOutTimeZone(time) {
            
            var tempTime = time.split(":");

            var old_date = new Date();
            var new_date = new Date(old_date);
            new_date.setHours(new_date.getHours() + parseInt(tempTime[0]));
            new_date.setMinutes(new_date.getMinutes() + parseInt(tempTime[1]));
            new_date.setSeconds(new_date.getSeconds() + parseInt(tempTime[2]));

            return new_date;
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
    </script>
</body>
</html>
