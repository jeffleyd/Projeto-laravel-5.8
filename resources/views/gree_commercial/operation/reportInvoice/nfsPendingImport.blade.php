@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Operacional</a></li>
    <li class="active">Importar NF em aberto</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')

<div class="window">
    <div class="inner-padding">

        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>Para importar, baixe o modelo ao lado - <a targe="_blank" href="/excell/nfs_pendings_payments_clients.xlsx" style="color:#9b9c3a;">Modelo de importação <i class="fa fa-download"></i></a>
            <br>
            <span style="margin-left: 22px;">Preencha todas as colunas da planilha, todas são obrigatórias.</span>
            <br>
            @php
            $last_nf = $nfs->first();
            @endphp
            @if ($last_nf)
            <span style="margin-left: 22px;">Última atualização em: {{date('d/m/Y H:i:s', strtotime($last_nf->created_at))}} com total de {{$nfs->count()}} NFs</span>
            @endif
        </div>
        <div class="row">
            <div class="col-sm-12">
                <form action="/commercial/operation/nfs/pendings/import_do" id="sendForm" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Importar NFs</legend>
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

    $('#btn_import').click(function () {
        block();
    })
    $(document).ready(function () {

        $("#operation").addClass('menu-open');
        $("#reportInvoice").addClass('menu-open');
        $("#NFsPendingImport").addClass('page-arrow active-page');
    });
</script>

@endsection
