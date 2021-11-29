<script>
    function analyze(id, position) {

    var observation = '';
    var option_position = position != 1 ? `<option value="4">Voltar etapa</option>` : ``;
    var reason = {
        1 : 'Aprovar',
        2 : 'Reprovar',
        3 : 'Suspender',
        4 : 'Voltar etapa'
    };

    Swal.fire({
        type: 'warning',
        title: 'Realizar análise',
        //target: document.getElementById('requestPrint'),
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Continue <i class="fa fa-arrow-right"></i>',
        html: `Selecione a análise e informe observação de aprovação
                   <select class="swal2-input" id="swal_type" style="width: 100%;margin-bottom: -5px;">
                        <option value="" selected disabled>Selecione o tipo de aprovação</option>
                        <option value="1">Aprovar</option>
                        <option value="2">Reprovar</option>
                        <option value="3">Suspender</option>`
            +option_position+
            `</select>
                   <textarea id="swal_observation" class="swal2-textarea" placeholder="Informe a observação desta análise"></textarea>`,
        preConfirm: () => {


            var type = $("#swal_type").val();
            observation = $("#swal_observation").val();

            if(type == null) {
                swal.showValidationError('Selecione o tipo de aprovação');
                return false;
            } else {
                if((type == 2 || type == 3 || type == 4) && observation == "") {
                    swal.showValidationError('Informe a observação da análise');
                    return false;
                }
            }
        }
    }).then((result) => {

        if(result.value) {

            var type = $("#swal_type").val();
            var select = '';

            if(type == 4) {
                if(position > 1) {
                    var input = '';
                    for(var i = 1; i < position; i++) {
                        input += '<option value="'+i+'">Etapa '+i+'</option>';
                    }
                    select += '<select class="swal2-input" id="type_revert" style="width: 100%; margin-bottom: -5px;">'+input+'</select>';
                }
            }

            Swal.fire({
                title: reason[type] + ' solicitação',
                //target: document.getElementById('requestPrint'),
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                html: 'Para dar andamento, informe sua senha e confirme'+
                    ''+select+''+
                    '<input type="password" id="swal_password" class="swal2-input" placeholder="Informe a senha">',
                preConfirm: () => {
                    if($("#swal_password").val() == "") {
                        swal.showValidationError(
                            'Informe a senha para continuar'
                        );
                    }
                }
            }).then(function (result) {

                if (result.value) {

					$("#rtd_analyze_id").val(id);
                    $("#rtd_analyze_type").val(type);
                    $("input[name='password']").val($("#swal_password").val());
                    $("#rtd_analyze_observation").val(observation);
                    $("#rtd_analyze_position").val($("#type_revert").val());

                    block();
                    $("#rtd_analyze_form").submit();
                }

            });
        }
    });
}
</script>
