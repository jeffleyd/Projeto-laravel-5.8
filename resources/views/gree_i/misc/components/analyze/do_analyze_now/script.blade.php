<script>
    function ApprovNow(id) {

        Swal.fire({
            title: 'Aprovar imediatamente?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar!',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            html: 'Deseja confirmar a aprovação imediata desta solicitação?'+
                '<textarea id="swal_observation" class="swal2-textarea" style="margin-bottom: 0px;height: 4.75em;" placeholder="Observação da aprovação imediata"></textarea>'+
                '<input type="password" id="swal_password" class="swal2-input" placeholder="Informe a senha">',
            preConfirm: () => {
                if($("#swal_observation").val() == "") {
                    swal.showValidationError(
                        'Informe a observação da aprovação imediata'
                    );
                }
                else if($("#swal_password").val() == "") {
                    swal.showValidationError(
                        'Informe a senha para continuar'
                    );
                }
            }
        }).then(function (result) {

            if (result.value) {
                $("#approv_now_id").val(id);
                $("#approv_now_description").val($("#swal_observation").val());
                $("input[name='password']").val($("#swal_password").val());
                block();
                $("#rtd_analyze_form").submit();
            }

        });
    }
</script>
