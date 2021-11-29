
    var rtd_json = null;
    var rtd_status = ['reprovado', 'Aprovado', 'Suspenso', 'Em análise'];

    // Passe o ID da solicitação e 'namespace' ex: rtd_analyzes(16, "App\\Model\\FinancyRPayment");
    function rtd_analyzes(id, namespace) {
        block();
        ajaxSend(
            '/misc/components/analyze/',
            {
                'id': id,
                'namespace': namespace
            }
        ).then((result) => {
            unblock();
            rtd_json = result['rtd_status'];
            if (rtd_json.status['code']) {
                if (result['request_code']) {
 					$(".histId").html('Histórico <br>'+result['request_code']+' <br><small style="font-size: 15px;">origem: '+(rtd_json.code ? rtd_json.code : '')+'</small>');
                } else {
					$(".histId").html('Histórico <br>' + (rtd_json.code ? rtd_json.code : ''));
                }
                rtd_analyzesVersion(rtd_json.last_version);
                rtd_selectBuilder();
                $($(".customizer")).toggleClass('open');
                $('[data-toggle="tooltip"]').tooltip();
            } else {
                $error(rtd_json.status['situation']);
            }
        }).catch((error) => {
            unblock();
            $error(error.message);
        });
    }

    $('#rtd_version').change(function() {
        rtd_analyzesVersion($(this).val());
    });

    function rtd_selectBuilder() {
        $('#rtd_version').html('');
        for(i = 1; i <= rtd_json.last_version; i++) {
            if (i == rtd_json.last_version)
                $('#rtd_version').append('<option selected value="'+i+'">Versão: '+i+'</option>');
            else
                $('#rtd_version').append('<option value="'+i+'">Versão: '+i+'</option>');
        }
    }

    function rtd_analyzesVersion(version) {
        var list = '';
        $(".listitens").html('');
        var group_peoples = Object.values(rtd_json.versions[version].collect);

        group_peoples.forEach(function ($val) {
            var has_print = false;
            $val.forEach(function ($user) {
                if (rtd_findAnalyzeGroup($val).status) {
                    if (rtd_findAnalyzeGroup($val).r_code == $user.r_code) {
                        if ($user.is_reprov)
                            list += '<li class="timeline-items timeline-icon-danger active">';
                        else if ($user.is_approv)
                            list += '<li class="timeline-items timeline-icon-success active">';
                        else
                            list += '<li class="timeline-items timeline-icon-warning active">';

                        list += '<div class="timeline-time">'+ rtd_getDateFormat($user.updated_at, true) +'</div>';
                        list += '<h6 class="timeline-title"><a target="_blank" href="/user/view/'+ $user.r_code +'">'+ $user.users.short_name +'</a>';
                        if ($user.is_holiday) {
                            list += '<i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Este colaborador está provisóriamente na aprovação, enquanto a pessoa que deveria aprovar, está de férias."></i>';
                        }
                        list += '</h6>';

                        var status = 'Em análise';
                        if ($user.is_reprov)
                            status = 'Reprovado';
                        else if ($user.is_approv)
                            status = 'Aprovado';
                        else if ($user.is_suspended)
                            status = 'Suspenso';

                        var description = $user.description ? $user.description : '';
                        list += '<p class="timeline-text">'+ $user.users.sector_name +': <b>'+status+'</b></p>';
                        description ? list += '<div class="timeline-content">'+ $user.description +'</div>' : description;
                    }
                } else {
                    if (!has_print) {
                        list += '<li class="timeline-items timeline-icon-warning active">';
                        list += '<div class="timeline-time">--</div>';
                        has_print = true;
                    }
                    list += '<h6 class="timeline-title"><a target="_blank" href="/user/view/'+ $user.r_code +'">'+ $user.users.short_name +'</a>';
                    if ($user.is_holiday) {
                        list += '<i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Este colaborador está provisóriamente na aprovação, enquanto a pessoa que deveria aprovar, está de férias."></i>';
                    }
                    list += '</h6>';
                }
            });

            list += '</li>';
        });

        $(".listitens").html(list);
    }

    function rtd_findAnalyzeGroup($val) {
        var hasAnalyze = {'status': false, 'r_code': '0'};
        var loop = false;
        $val.forEach(function ($user) {
            if (!loop) {
                if ($user.is_reprov || $user.is_suspended || $user.is_approv) {
                    hasAnalyze = {'status': true, 'r_code': $user.r_code};
                    loop = true;
                }
            }
        });
        return hasAnalyze;
    }

    function rtd_getDateFormat(value, has_year = false){
        var date = new Date(value);
        var day = date.getDate().toString();
        var dayF = (day.length == 1) ? '0'+day : day;
        var month = (date.getMonth()+1).toString();
        var monthF = (month.length == 1) ? '0'+month : month;

        if (has_year)
            return dayF+'/'+monthF+'/'+date.getFullYear();
        else
            return dayF+'/'+monthF;
    }

    function rtd_getHourFormat(value) {
        var date = new Date(value);
        var hour = date.getHours();
        var hourF = hour < 10 ? '0'+hour : hour;
        var minutes = date.getMinutes();
        var minutesF = minutes < 10 ? '0'+minutes : minutes;
        return  hourF+':'+minutesF;
    }
