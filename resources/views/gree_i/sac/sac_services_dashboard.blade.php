@extends('gree_i.layout')

@section('content')

<div class="block block-transparent" style="padding:10px;">
    <h2 class="content-heading">Visão geral <small>Veja info gráficos e dados atualizados dos atendimentos.</small></h2>
    <div class="row gutters-tiny mb-20">
        <!-- Row #4 -->
        <div class="col-md-6 col-xl-3">
            <a class="block block-transparent" href="javascript:void(0)">
                <div class="block-content block-content-full bg-info">
                    <div class="py-50 text-center bg-black-op-10">
                        <div class="font-size-h2 font-w700 mb-0 text-white">1450</div>
                        <div class="font-size-sm font-w600 text-uppercase text-white-op">Total</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-3">
            <a class="block block-transparent" href="javascript:void(0)">
                <div class="block-content block-content-full bg-primary">
                    <div class="py-50 text-center bg-black-op-10">
                        <div class="font-size-h2 font-w700 mb-0 text-white">850</div>
                        <div class="font-size-sm font-w600 text-uppercase text-white-op">Em andamento</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-3">
            <a class="block block-transparent" href="javascript:void(0)">
                <div class="block-content block-content-full bg-success">
                    <div class="py-50 text-center bg-black-op-10">
                        <div class="font-size-h2 font-w700 mb-0 text-white">450</div>
                        <div class="font-size-sm font-w600 text-uppercase text-white-op">Finalizados</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-3">
            <a class="block block-transparent" href="javascript:void(0)">
                <div class="block-content block-content-full bg-danger">
                    <div class="py-50 text-center bg-black-op-10">
                        <div class="font-size-h2 font-w700 mb-0 text-white">120</div>
                        <div class="font-size-sm font-w600 text-uppercase text-white-op">Cancelados</div>
                    </div>
                </div>
            </a>
        </div>
        <!-- END Row #4 -->
    </div>

    <div class="row">
        <div class="col-xl-12">
            <!-- Lines Chart -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Atendimentos finalizados</h3>
                </div>
                <div class="block-content block-content-full">
                    <!-- Lines Chart Container -->
                    <div style="height: 340px;">
                        {!! $requestCompleted->container() !!}
                    </div>
                </div>
            </div>
            <!-- END Lines Chart -->
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <!-- Lines Chart -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Atendimentos cancelados</h3>
                </div>
                <div class="block-content block-content-full">
                    <!-- Lines Chart Container -->
                    <div style="height: 340px;">
                        {!! $requestCancel->container() !!}
                    </div>
                </div>
            </div>
            <!-- END Lines Chart -->
        </div>
        <div class="col-xl-6">
            <!-- Lines Chart -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Total de atendimentos</h3>
                </div>
                <div class="block-content block-content-full">
                    <!-- Lines Chart Container -->
                    <div style="height: 340px;">
                        {!! $tripleStatus->container() !!}
                    </div>
                </div>
            </div>
            <!-- END Lines Chart -->
        </div>
    </div>
    
    
</div>

{{-- ChartScript --}}
@if($requestCompleted)
{!! $requestCompleted->script() !!}
@endif
@if($requestCancel)
{!! $requestCancel->script() !!}
@endif
@if($tripleStatus)
{!! $tripleStatus->script() !!}
@endif

    <script>
    $(document).ready(function () {

        $("#mSac").addClass("open");
        $("#mSacServices").addClass("open");
        $("#mSacServicesDashboard").addClass("active");
        
    });
    </script>
@endsection