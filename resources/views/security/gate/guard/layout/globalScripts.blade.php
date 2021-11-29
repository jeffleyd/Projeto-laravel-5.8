<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->

    <script src="/elite/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="/elite/assets/node_modules/popper/popper.min.js"></script>
    <script src="/elite/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!--Wave Effects -->
    <script src="/elite/dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="/elite/dist/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="/elite/assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="/elite/assets/node_modules/sparkline/jquery.sparkline.min.js"></script>

    <!--Custom JavaScript -->
    <script src="/elite/dist/js/custom.min.js"></script>
    <script src="/elite/assets/node_modules/toast-master/js/jquery.toast.js"></script>

    <script>
        function block() {
            $(".preloader").show();
        }
        function unblock() {
            $(".preloader").hide();
        }
        function $success(msg) {
            $.toast({
                text: msg,
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 3500

            });
        }

        function $error(msg) {
            $.toast({
                text: msg,
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3500

            });
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
                    } else if (textStatus === 'parsererror') {
                        reject({'message': 'Erro ao tratar objeto JSON, comunique a equipe de desenvolvimento.'});
                    } else if (textStatus === 'timeout') {
                        reject({'message': 'Sua conexão demorou muito a responder, tente novamente!'});
                    } else if (textStatus === 'abort') {
                        reject({'message': 'Solicitação foi recusada, tente novamente!'});
                    } else {
                        if(jqXHR.responseJSON.message){
                            reject({'message': jqXHR.responseJSON.message, 'response':jqXHR});
                        }
                        if(jqXHR.responseJSON.msg){
                            reject({'message': jqXHR.responseJSON.msg, 'response':jqXHR});
                        }
                        reject({'message': 'Erro no processamento de sua solicitação, comunique a equipe de desenvolvimento.', 'response':jqXHR});
                    }
                }
                $.ajax($param);
            });

            return objeto;
        }



        $(document).ready(function () {
            <?php if (Session::has('success')) { ?>
                setTimeout(() => {
                    $success('<?= Session::get('success') ?>');
                }, 300);
            <?php } ?>
            <?php if (Session::has('error')) { ?>
                setTimeout(() => {
                    $error('<?= Session::get('error') ?>');
                }, 300);
            <?php } ?>

        });
    </script>
