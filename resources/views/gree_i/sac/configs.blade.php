@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Configurações do pós venda</h5>
                  <div class="breadcrumb-wrapper col-12">
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <form class="needs-validation" action="/sac/config_do" id="submitEdit" method="post" enctype="multipart/form-data">
            <section>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">DADOS DE CONFIGURAÇÃO</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sac_distance_km">KILOMETRAGEM PADRÃO</label>
                                    <input type="number" class="form-control" name="sac_distance_km" id="sac_distance_km" value="{{ getConfig("sac_distance_km") }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sac_visit_price">VALOR DA VISITA</label>
                                    <input type="text" class="form-control" name="sac_visit_price" id="sac_visit_price" value="{{ getConfig("sac_visit_price") }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sac_km_price">CUSTO POR KM</label>
                                    <input type="text" class="form-control" name="sac_km_price" id="sac_km_price" value="{{ getConfig("sac_km_price") }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sac_email_default">EMAIL DO SAC</label>
                                    <input type="email" class="form-control" name="sac_email_default" id="sac_email_default" value="{{ getConfig("sac_email_default") }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sac_number">NÚMERO DO SAC</label>
                                    <input type="text" class="form-control" name="sac_number" id="sac_number" value="{{ getConfig("sac_number") }}" required>
                                </fieldset>
                            </div>
							
							<div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sac_attemps_call_completed">NÚMERO DE TENTATIVAS PARA CONCLUIR O PROTOCOLO</label>
                                    <input type="text" class="form-control" name="sac_attemps_call_completed" id="sac_attemps_call_completed" value="{{ getConfig("sac_attemps_call_completed") }}" required>
                                </fieldset>
                            </div>
							
							<div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sac_number">PORCENTAGEM DE DESCONTO PARA REVENDA DE PEÇAS</label>
                                    <input type="text" class="form-control" name="sac_resale" id="sac_resale" value="{{ getConfig("sac_resale") }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sac_authorized_msg">INFORMATIVO PARA AUTORIZADA</label>
                                    <textarea id="js-ckeditor" name="sac_authorized_msg" rows="6" id="sac_authorized_msg">{{ getConfig("sac_authorized_msg") }}</textarea>
                                </fieldset>
                            </div>
							
							<div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sac_msg_interaction">PRIMEIRA INTERAÇÃO NO PROTOCOLO</label>
                                    <textarea class="form-control" name="sac_msg_interaction" rows="6" cols="50" id="sac_msg_interaction">{{ getConfig("sac_msg_interaction") }}</textarea>
                                </fieldset>
                            </div>
							
							<div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="os_msg_interaction">PRIMEIRA INTERAÇÃO NA OS</label>
                                    <textarea class="form-control" name="os_msg_interaction" rows="6" cols="50" id="os_msg_interaction">{{ getConfig("os_msg_interaction") }}</textarea>
                                </fieldset>
                            </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                
            </section>

            <button type="submit" style="width:100%" class="btn btn-primary">
                Atualizar configurações
            </button>

        </form>
        </div>
    </div>
    <script src="/ckeditor/ckeditor.js"></script>
    <script src="/ckeditor/init.js"></script>
    <script>
        $(document).ready(function () {
            ckeditorInit('#js-ckeditor');
			ckeditorInit('#js-ckeditor2');
			ckeditorInit('#js-ckeditor22');
            $("#submitEdit").submit(function (e) { 
                var form = $(".needs-validation");
                if (form[0].checkValidity() === false) {
                    e.preventDefault();
                } else {
                    block();
                }             
            });

            $('#sac_visit_price').mask('000.00', {reverse: true});
            $('#sac_km_price').mask('000.00', {reverse: true});
            $('#sac_km_price').mask('000.00', {reverse: true});

            setInterval(() => {
                $("#mAfterSales").addClass('sidebar-group-active active');
                $("#mSACConfing").addClass('active');
            }, 100);
        });
    </script>
@endsection