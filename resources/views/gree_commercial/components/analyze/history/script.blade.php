<script>
	$('.right-side-toggle').click(function() {
        $('.right-sidebar').css("right", "-340px");
    });
var rtd_json = null;
var rtd_status = ['reprovado', 'Aprovado', 'Suspenso', 'Em análise'];

// Passe o ID da solicitação e 'namespace' ex: rtd_analyzes(16, "App\\Model\\FinancyRPayment");
function rtd_analyzes(id, namespace) {
    block();
    ajaxSend(
        '/misc/components/analyze/',
        {
            'id': id,
            'namespace': namespace,
            'connection': 'commercial'
        }
    ).then((result) => {
        unblock();
        rtd_json = result['rtd_status'];
        $(".rpanel-title").html('Histórico ' + rtd_json.code +' <span onclick="closePanel()"><i class="fa fa-times right-side-toggle" style="cursor: pointer; color:white"></span>');
        if (rtd_json.status['code']) {
            $(".select-version").show();
            rtd_analyzesVersion(rtd_json.last_version);
            rtd_selectBuilder();
            $('.right-sidebar').css("right", "0px");
        } else {
            if (rtd_json.status['situation'] == 'A solitação foi cancelada!') {
                var list = '';
                var user = result['module']['who_cancel'];
                $(".select-version").hide();
                $(".steamline").html('');
                $('#rtd_version').html('');
                if (user.picture) {
                    list += '<div class="sl-left"> <img class="img-circle" alt="user" src="'+user.picture+'"> </div>';
				} else {
                    list += '<div class="sl-left bg-secondary"><i class="ti-user"></i> </div>';
				}

                list += '<div class="sl-right" style="padding-left: 35px !important;">';
                list += '<div class="font-medium" style="font-size: 12px;">'+user.name;
                list += '<span class="label label-danger" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 0px;left: 5px;float:right;">Cancelado <span style="font-size: 9px;color: #ffeb5b;">'+rtd_getDateFormat(user.updated_at, true)+'</span> </span></div>';
                list += '<div style="font-size:10px">'+user.office+' </div>';
                list += '<div class="desc" style="background: #fff1e9;padding: 6px;border: solid 2px #d4c3b9;font-size: 12px;margin: 13px 0px;">'+user.description+'</div>';
                list += '</div>';
                list += '</div>';
                $(".steamline").html(list);

                $('.right-sidebar').css("right", "0px");
            } else {
                $error(rtd_json.status['situation']);
            }
        }
    }).catch((error) => {
        unblock();
        $error(error.message);
    });
}

$('#rtd_version').change(function() {
    rtd_analyzesVersion($(this).val());
});

function closePanel() {
    $('.right-sidebar').css("right", "-340px");
}

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
    $(".steamline").html('');
    var group_peoples = Object.values(rtd_json.versions[version].collect);

    var position = 1;
    group_peoples.forEach(function ($val) {
        $val.forEach(function ($user) {
            if (rtd_findAnalyzeGroup($val).status) {
                if (rtd_findAnalyzeGroup($val).r_code == $user.r_code) {
                    list += '<div class="sl-item">';

                    if ($user.users.picture){
                        list += '<div class="sl-left"><span class="badge rounded-pill bg-cyan" style="padding: 3px 0px;background-color: #3071a9;position: absolute;height: 14px;width: 14px;font-size: 9px;line-height: 1;">'+position+'</span> <img class="img-circle" alt="user" src="'+$user.users.picture+'"> </div>';
					} else {
                        list += '<div class="sl-left bg-secondary"><span class="badge rounded-pill bg-cyan" style="padding: 3px 0px;background-color: #3071a9;position: absolute;height: 14px;width: 14px;font-size: 9px;line-height: 1;">'+position+'</span> <i class="ti-user"></i> </div>';
					}

                    list += '<div class="sl-right" style="padding-left: 35px !important;">';
                    if ($user.is_holiday) {
                        list += '<div style="font-size: 10px;background-color: red;color: white;text-align: center;border-radius: 3px;margin-bottom: 7px;"><b>Aprovador provisório</b></div>';
                    }

                    list += '<div class="font-medium" style="font-size: 12px;">'+$user.users.short_name+'</div>';
                    if ($user.is_reprov) {
                        list += '<span class="label label-danger" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 15px;left: 5px;float: right;">Reprovado <span style="font-size: 9px;color: #ffeb5b;">'+rtd_getDateFormat($user.updated_at, true)+'</span> </span>';
					} else if ($user.is_approv) {
                        list += '<span class="label label-success" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 15px;left: 5px;float: right;">Aprovado <span style="font-size: 9px;color: #ffeb5b;">'+rtd_getDateFormat($user.updated_at, true)+'</span> </span>';
					} else {
                        list += '<span class="label label-warning" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 15px;left: 5px;float: right;">Em análise</span>';
					}

                    var description = $user.description != null ? '<div class="desc" style="background: #fff1e9;padding: 6px;border: solid 2px #d4c3b9;font-size: 12px;margin: 13px 0px;">'+$user.description+'</div>' : '<div class="desc"></div>';
                    list += '<div style="font-size:10px">'+$user.users.office+' </div>';
                    list += description;
                    list += '</div>';
                    list += '</div>';

                }
            } else {
                list += '<div class="sl-item">';

                if ($user.users.picture) {
                    list += '<div class="sl-left"> <span class="badge rounded-pill bg-cyan" style="padding: 3px 0px;background-color: #3071a9;position: absolute;height: 14px;width: 14px;font-size: 9px;line-height: 1;">'+position+'</span> <img class="img-circle" alt="user" src="'+$user.users.picture+'"> </div>';
				} else {
                    list += '<div class="sl-left bg-secondary"> <span class="badge rounded-pill bg-cyan" style="padding: 3px 0px;background-color: #3071a9;position: absolute;height: 14px;width: 14px;font-size: 9px;line-height: 1;">'+position+'</span> <i class="ti-user"></i> </div>';
				}

                list += '<div class="sl-right" style="padding-left: 35px !important;">';
                if ($user.is_holiday) {
                    list += '<div style="font-size: 10px;background-color: red;color: white;text-align: center;border-radius: 3px;margin-bottom: 7px;"><b>Aprovador provisório</b></div>';
                }
                list += '<div class="font-medium" style="font-size: 12px;">'+$user.users.short_name+'</div>';
                list += '<span class="label label-warning" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 15px;left: 5px;float: right">Em análise</span>';

                var description = $user.description != null ? '<div class="desc" style="background: #fff1e9;padding: 6px;border: solid 2px #d4c3b9;font-size: 12px;margin: 13px 0px;">'+$user.description+'</div>' : '<div class="desc"></div>';
                list += '<div style="font-size:10px">'+$user.users.office+'</div>';
                list += description;
                list += '</div>';
                list += '</div>';
            }
        });
        position++;
    });

    $(".steamline").html(list);
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

</script>
