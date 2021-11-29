@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Pedidos de vendas</a></li>
    <li class="active">Importar Pedidos</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')

<div class="window">
    <div class="inner-padding">

        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>Para importar, baixe o modelo ao lado - <a targe="_blank" href="/excell/model_import_orders.xlsx" style="color:#9b9c3a;">Modelo de importação <i class="fa fa-download"></i></a>
            <br>
            <span style="margin-left: 22px;">Preencha todas as colunas da planilha, todas são obrigatórias.</span>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form action="/commercial/order/import" id="sendForm" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Importar Pedidos</legend>
                        
						<div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Condição comercial</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="table" id="table" class="form-control js-select22" multiple>
								</select>
                            </div>
                        </div>
						<div class="spacer-10"></div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Anexe o arquivo (xlsx)</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="file" name="attach" id="attach" class="form-control">
                            </div>
                        </div>
						<div class="spacer-10"></div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                            </div>
                            <div class="col-sm-9">
                                <div class="btn-group">
                                    <button type="submit" name="import" value="1" class="btn btn-default" id="btn_import">
                                        <i class="fa fa-upload"></i>&nbsp; Importar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <!-- End .inner-padding -->
</div>

<script>
    
    $(document).ready(function () {

		$(".js-select22").select2({
			maximumSelectionLength: 1,
			language: {
				noResults: function () {
					return "Sem resultados...";
				},
				searching: function () {
					return "Buscando resultados...";
				},
				loadingMore: function () {
					return 'Carregando mais resultados...';
				},
				maximumSelected: function (args) {
					return 'Você já selecionou a tabela';
				},
			},
			ajax: {
				url: '/commercial/tableprice/dropdown',
				data: function (params) {
					var query = {
						search: params.term,
						page: params.page || 1
					}
					// Query parameters will be ?search=[term]&page=[page]
					return query;
				}
			}
		});
		
        $("#orderSale").addClass('menu-open');
        $("#orderImport").addClass('page-arrow active-page');
    });
</script>

@endsection
