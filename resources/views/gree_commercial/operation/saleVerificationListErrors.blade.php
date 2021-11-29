@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li class="active">Pedidos faturados</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
    <header id="header-sec">
        <div class="inner-padding">
            <div class="pull-left">
                <div class="btn-group">
                    <a class="btn btn-primary" href="/commercial/operation/sale/verification">
                        <i class="fa fa-file" style="color: #ffffff;"></i>&nbsp; Nova apuração
                    </a>
                </div>
            </div>
        </div><!-- End .inner-padding -->
    </header>
<div class="window">
    <div class="inner-padding">
		@if($is_ascertained == 0)
        <div class="row">
            <div class="col-md-12">
                <h5>Selecione o erro para reenviar novamente as chaves da Nfe para apuração</h5>
            </div>    
        </div>    
        <div class="spacer-10"></div>
        <div class="row">
            <form action="#" method="post" id="form_send_keys">
                <input type="hidden" name="id" value="{{$id}}">
                <div class="col-sm-9">
                    <select name="type_error" id="type_error" class="form-control select2-type-errors" style="width: 100%;" multiple></select>
                </div>
                <div class="col-sm-3">
                    <button type="button" class="btn btn-primary" id="btn_send_keys" style="width: 100%;">
                        <i class="fa fa-upload" style="color: #ffffff;"></i>&nbsp; Enviar novamente
                    </button>
                </div>
            </form>    
        </div>    
		@endif
        <div class="spacer-20"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-wrapper">
                    <header>
                        <h3>ERROS DE APURAÇÃO DE VENDAS <span>({{$total_errors}})</span></h3>
                    </header>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col" style="text-align: center;">id</th>
                            <th scope="col" style="text-align: center;">Chave NFE</th>
                            <th scope="col" style="text-align: center;">Erro</th>
                            <th scope="col" style="text-align: center;">Criado em</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales_verification_errors as $key)
                                <tr style="text-align: center;">
                                    <td style="vertical-align: middle;">{{$key->sale_verification_client_completed_id}}</td>
                                    <td style="vertical-align: middle;overflow-wrap: break-word;">{{$key->key_nfe}}</td>
                                    <td style="vertical-align: middle;"><?= $key->msg_errors ?></td>
                                    <td style="vertical-align: middle;">{{ date('d/m/Y H:i', strtotime($key->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $sales_verification_errors->appends(getSessionFilters()[2]->toArray()); ?>
                    </ul>
                </div>
                <div class="spacer-50"></div>  
            </div>
        </div>
    </div>
</div>

<script>
	
	var id = {!! $id !!};
    
    $(document).ready(function () {

        $(".select2-type-errors").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return 'Tipo de erro não encontrado...';
                },
                maximumSelected: function (e) {
                    return 'você só pode selecionar 1 item';
                }
            },
            ajax: {
                url: '/commercial/sale/client/verification/errors/list/dropdown/'+id,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#btn_send_keys").click(function() {

            if($("#type_error").val() == null) {
                return $error('Selecione o tipo de erro para reenviar a apuração');
            } else {
					
				block();
                ajaxSend('https://filemanager.gree.com.br/commercial/sale/client/verification/errors/resend', $("#form_send_keys").serialize(), 'POST', '60000', $("#form_send_keys")).then(function(result){
					unblock();
                    $success('Preparado para ser enviado para apuração');
					setTimeout(function () {
                    	window.location = "/commercial/export/report/sale/client/response/list";
					}, 2000);	
                }).catch(function(err){
                    unblock();
                    $error(err.message);
                });
            }
        });
        
        $("#operation").addClass('menu-open');
        $("#saleVerification").addClass('page-arrow active-page');
    });
</script>

@endsection
