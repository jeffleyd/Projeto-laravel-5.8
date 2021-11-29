@extends('gree_i.layout')

@section('content')
<div class="block block-transparent" style="padding:10px;">
    <h2 class="content-heading">Monitoramento <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button></h2>
    <div class="row gutters-tiny">
        <!-- Row #5 -->
        <div class="col-md-6 col-xl-4">
            <a class="block block-transparent" href="javascript:void(0)">
                <div class="block-content block-content-full bg-primary">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="si si-bell fa-4x text-white"></i>
                        </div>
                        <div class="font-size-h3 font-w600 text-white">56</div>
                        <div class="font-size-sm font-w600 text-uppercase text-white">Novos</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-4">
            <a class="block block-transparent" href="javascript:void(0)">
                <div class="block-content block-content-full bg-warning">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="si si-close fa-4x text-warning-light"></i>
                        </div>
                        <div class="font-size-h3 font-w600 text-white">10</div>
                        <div class="font-size-sm font-w600 text-uppercase text-white">Atrasados</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-4">
            <a class="block block-transparent" href="javascript:void(0)">
                <div class="block-content block-content-full bg-danger">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="si si-fire fa-4x text-info-light"></i>
                        </div>
                        <div class="font-size-h3 font-w600 text-white">2</div>
                        <div class="font-size-sm font-w600 text-uppercase text-info-light">Urgente</div>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- END Row #5 -->
    </div>

    <div class="content-heading">
        
        Protocolos (1047)
    </div>

    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th>Protocolo</th>
                        <th>Cliente</th>
                        <th>Local</th>
                        <th class="d-none d-sm-table-cell">Período</th>
                        <th class="d-none d-sm-table-cell">Orígem</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a class="font-w600" href="#">G202000019764</a>
                        </td>
                        <td>
                            Tainara Carbonaro
                        </td>
                        <td>
                            Dourados/MS
                        </td>
                        <td> 
                            07/02/2020 10:49
                        </td>
                        <td>
                            Site
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
</div>

    <script>
    $(document).ready(function () {

        $("#mSac").addClass("open");
        $("#mSacMonitor").addClass("active");
        
    });
    </script>
@endsection