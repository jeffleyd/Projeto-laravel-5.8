@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="/commercial/client/list">Clientes</a></li>
    <li class="active">Importar Clientes</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')

<div class="window">
    <div class="inner-padding">

        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>Para importar, baixe o modelo ao lado - <a targe="_blank" href="/excell/model_import_clients.xlsx" style="color:#9b9c3a;">Modelo de importação <i class="fa fa-download"></i></a>
            <br>
            <span style="margin-left: 22px;">Preencha todas as colunas da planilha, todas são obrigatórias.</span>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form action="/commercial/client/import_do" id="sendForm" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Importar Clientes</legend>
                        
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Anexe o arquivo (xlsx)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="file" name="attach" id="attach" class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-default" id="btn_import">
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

        $("#client").addClass('menu-open');
        $("#clientImport").addClass('page-arrow active-page');
    });
</script>

@endsection
