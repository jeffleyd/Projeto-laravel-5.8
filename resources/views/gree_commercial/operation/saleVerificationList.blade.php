@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li class="active">Pedidos faturados</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
<style type="text/css">
    .swal2-popup {
        font-size: 1.4rem !important;
    }
</style>
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-primary" href="/commercial/operation/sale/verification">
                    <i class="fa fa-file" style="color: #ffffff;"></i>&nbsp; Nova apuração
                </a>
				<a class="btn btn-danger" href="https://anti-captcha.com/clients/entrance/login" target="_blank">
					<b>SALDO:</b> ${{round($balance,2)}}
                </a>
            </div>
        </div>
    </div><!-- End .inner-padding -->
</header>
<div class="window">
    <div class="inner-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="table-wrapper">
                    <header>
                        <h3>APURAÇÃO DE VENDAS</h3>
                    </header>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col" style="text-align: center;">id</th>
                            <th scope="col" style="text-align: center;">Solicitante</th>
                            <th scope="col" style="text-align: center;">Cliente</th>
                            <th scope="col" style="text-align: center;">Arquivo</th>
                            <th scope="col" style="text-align: center;">Progresso</th>
                            <th scope="col" style="text-align: center;">Criado em</th>
                            <th scope="col" style="text-align: center;">Atualizado em</th>
                            <th scope="col" style="text-align: center;">Status</th>
                            <th scope="col" style="text-align: center;">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales_verification as $key)
                                <tr style="text-align: center;">
                                    <td style="vertical-align: middle;">{{$key->id}}</td>
                                    <td style="vertical-align: middle;">{{$key->users->short_name}}</td>
                                    <td style="vertical-align: middle;"><?= $key->client ? stringCut($key->client->company_name, 20) : '-' ?></td>
                                    <td style="vertical-align: middle;">
                                        <a href="{{$key->url}}" target="_blank" style="color: #428bca;">
                                            Download
                                        </a>
                                    </td>
                                    <td style="vertical-align: middle;">{{$key->percent_completed}}%</td>
                                    <td style="vertical-align: middle;">{{ date('d/m/Y H:i', strtotime($key->created_at)) }}</td>
                                    <td style="vertical-align: middle;">{{ date('d/m/Y H:i', strtotime($key->updated_at)) }}</td>
                                    <td style="vertical-align: middle;">
                                        <div style="display: flex;justify-content: center;">
                                            @if($key->is_cancelled == 1)
                                                <span class="label label-danger">Cancelado</span>
											@elseif($key->is_ascertained == 1)
                                                <span class="label label-success">Finalizada</span>
                                            @else
                                                @if($key->is_completed == 0)
                                                    <span class="label label-warning">Em progresso</span>
                                                @elseif($key->is_completed == 1)
                                                    <span class="label label-info">Concluído</span>
                                                @else
                                                    <span class="label label-primary">Aguard. Reenvio</span>
                                                @endif    
                                            @endif    
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div class="btn-group" style="float: inherit;">
                                            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
                                                <i class="fa fa-bars"></i>
                                            </a>
                                            <ul role="menu" class="dropdown-menu pull-right rt-column-menu">
                                                <li><a href="/commercial/export/report/sale/client/response/errors/list/{{$key->id}}">Visualizar Erros</a></li>
                                                @if($key->is_completed == 2)
                                                    <li><a href="javascript:void(0)" class="btn_verification_sale" data-id="{{$key->id}}">Reenviar apuração</a></li>
                                                @endif
												@if($key->is_completed == 1 && $key->is_ascertained == 0)
                                                    <li><a href="javascript:void(0)" class="btn_finish" data-id="{{$key->id}}">Finalizar apuração</a></li>
                                                @endif
												@if($key->is_completed == 0 and $key->is_cancelled == 0)
                                                    <li><a href="javascript:void(0)" class="btn_cancel" data-id="{{$key->id}}">Cancelar apuração</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $sales_verification->appends(getSessionFilters()[2]->toArray()); ?>
                    </ul>
                </div>
                <div class="spacer-50"></div>  
            </div>
        </div>
    </div>
</div>
<form method="POST" action="#" id="form_resend_verification">
	<input type="hidden" name="id" id="sale_verify_id">
</form>	
<form method="POST" action="#" id="form_finish_verification">
	<input type="hidden" name="id" id="sale_finish_id">
</form>
<script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script>
    
    $(document).ready(function () {
        
        $(".btn_verification_sale").click(function() {

            var id = $(this).attr("data-id");
			$("#sale_verify_id").val(id);
			
            swal({
                title: "Reenviar apuração",   
                text: "Deseja confirmar o reenvio da apuração?",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",   
                cancelButtonText: "Cancelar",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }).then(function (result) {
				if(result.value) {
					block();
					ajaxSend('https://filemanager.gree.com.br/commercial/send/nfe/verification/sale/client', $("#form_resend_verification").serialize(), 'POST', '60000', $("#form_resend_verification")).then(function(result){
						unblock();
						$success('Geração de relatório em andamento');
						setTimeout(function () {
							window.location = "/commercial/export/report/sale/client/response/list";
						}, 2000);
					}).catch(function(err){
						unblock();
						$error(err.message);
					});
				}
            });
        });
		
		$(".btn_finish").click(function() {

            var id = $(this).attr("data-id");
            $("#sale_finish_id").val(id);
            swal({
                title: "Finalizar apuração",   
                text: "Deseja confirmar a finalização da apuração?",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",   
                cancelButtonText: "Cancelar",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }).then(function (result) {
                if(result.value) {
                    block();
                    ajaxSend('https://filemanager.gree.com.br/commercial/finish/verification/sale/client', $("#form_finish_verification").serialize(), 'POST', '60000', $("#form_finish_verification")).then(function(result){
                        if(result.success) {
                            $success(result.message);
                            setTimeout(function(){
                                window.location = "/commercial/export/report/sale/client/response/list";
                            }, 1000);
                        }
                        unblock();
                    }).catch(function(err){
                        unblock();
                        $error(err.message);
                    });
                }    
            });
        });
		
		$(".btn_cancel").click(function() {

            var id = $(this).attr("data-id");
            $("#sale_finish_id").val(id);
            swal({
                title: "Cancelar apuração",   
                text: "Deseja confirmar o cancelamento da apuração?",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",   
                cancelButtonText: "Cancelar",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }).then(function (result) {
                if(result.value) {
                    block();
                    ajaxSend('https://filemanager.gree.com.br/commercial/cancel/verification/sale/client', $("#form_finish_verification").serialize(), 'POST', '60000', $("#form_finish_verification")).then(function(result){
                        if(result.success) {
                            $success(result.message);
                            setTimeout(function(){
                                window.location = "/commercial/export/report/sale/client/response/list";
                            }, 1000);
                        }
                        unblock();
                    }).catch(function(err){
                        unblock();
                        $error(err.message);
                    });
                }    
            });
        });

        $("#operation").addClass('menu-open');
        $("#saleVerification").addClass('page-arrow active-page');
    });
</script>

@endsection
