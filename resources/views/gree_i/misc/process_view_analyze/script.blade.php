function seeAnalyzes(id, s_type = '') {
    block();
    $.ajax({
        type: "GET",
        @if (empty($type))
        url: "/misc/modeule/timeline/"+s_type+"/" + id,
        @else
        url: "/misc/modeule/timeline/{{$type}}/" + id,
        @endif
        success: function (response) {
            unblock();
            if (response.success) {

            if (response.history.length > 0) {
            $(".histId").html('Histórico ID# ' + id);
            var list = '';
            console.log(response.history);

            for (let i = 0; i < response.history.length; i++) {
            var obj = response.history[i];
            var status;
            if (obj.type == 1) {
                status = 'Aprovado';
            } else if (obj.type == 2) {
                status = 'Reprovado';
            } else if (obj.type == 3) {
                status = 'Suspenso';
            } else if (obj.type == 4) {
                status = 'Aguardando aprovação';
            }

            list += '<li class="timeline-items timeline-icon-'+ obj.status +' active">';
                if (obj.type != 4) {
                list += '<div class="timeline-time">'+ obj.created_at +'</div>';
                } else {
                list += '<div class="timeline-time">--</div>';
                }


                for (let index = 0; index < obj.users.length; index++) {
                var obj_users = obj.users[index];

                list += '<h6 class="timeline-title"><a target="_blank" href="/user/view/'+ obj_users.r_code +'">'+ obj_users.name +'</a></h6>';

                }

                list += '<p class="timeline-text">'+ obj.sector +': <b>'+ status +'</b></p>';
                if (obj.type != 4 && obj.message != null && obj.message != "") {
                list += '<div class="timeline-content">'+ obj.message +'</div>';
                }
                list += '</li>';

            }

                $(".listitens").html(list);
                $($(".customizer")).toggleClass('open');
                $('[data-toggle="tooltip"]').tooltip();

            } else {
                error('Ainda não foi enviado para análise.');
            }

            } else {
                error(response.msg);
            }
        }
    });
}
