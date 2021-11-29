@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="/commercial/operation/dashboard/general">Painel geral</a></li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')

<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/charts/apexcharts.css">

<style type="text/css">

    .card {
        box-shadow: 1px 5px 9px 0px rgb(25 42 70 / 34%);
        padding: 13px;
    }
    .cad-header {
        text-align: center;
        padding: 5px;
    }

    .project-block {
        float: left;
        margin: 0 10px 20px 0;
        text-align: center;
        border-radius: 6px;
        color: #fff!important;
        box-shadow: 1px 5px 9px 0px rgb(25 42 70 / 34%);
        border: 0px;
        cursor: pointer;
    }

    .project-block:hover {
        box-shadow: 0px 5px 11px 0px rgb(25 42 70 / 63%);
    }

    .project-block > header {
        float: left;
        width: 100%;
        padding: 10px;
    }
    .block-p {
        font-size: 14px;
        margin-top: 5px;
    }

    @media (min-width: 1281px) {
        .row-block-1 {
            width: 19%;
        }
        .row-block-2 {
            width: 32.3%;
            background-color:#fec107;
        }
    }
    @media (min-width: 1025px) and (max-width: 1280px) {
        .row-block-1 {
            width: 19%;
        }
        .row-block-2 {
            width: 32.3%;
            background-color:#fec107;
        }
    }

    @media (min-width: 320px) and (max-width: 480px) {
        .row-block-1 {
            width: 100%;
        }
        .row-block-2 {
            width: 100%;
            background-color:#fec107;
        }
    }    
</style>

<div class="window">
    <div class="inner-padding">

        <div class="row"> 
            <div class="col-md-12">

                <div class="project-block row-block-1" style="background-color:#e46a76;" onclick="window.open('/commercial/programation/all?is_open=1', '_blank')">
                    <header>
                        <h2>{{$block1}}</h2>
                        <p class="block-p">Programações <br>em aberto</p>
                    </header>
                </div>
                <div class="project-block row-block-1" style="background-color:#000000;" onclick="window.open('/commercial/client/list?is_analyze=1', '_blank')">
                    <header>
                        <h2>{{$block2}}</h2>
                        <p class="block-p">Clientes em <br>análise</p>
                    </header>
                </div>
                <div class="project-block row-block-1" style="background-color:#3568df;" onclick="window.open('/commercial/programation/all?is_analyze=1', '_blank')">
                    <header>
                        <h2>{{$block3}}</h2>
                        <p class="block-p">Programações em <br>análise</p>
                    </header>
                </div>

                <div class="project-block row-block-1" style="background-color:#00c292;" onclick="window.open('/commercial/order/all?is_analyze=1', '_blank')">
                    <header>
                        <h2>{{$block4}}</h2>
                        <p class="block-p">Pedidos programados <br>em análise</p>
                    </header>
                </div>

                <div class="project-block row-block-1" style="background-color:#03a9f3;margin: 0px;" onclick="window.open('/commercial/order/confirmed/all?is_analyze=1', '_blank')">
                    <header>
                        <h2>{{$block5}}</h2>
                        <p class="block-p">Pedidos não programados <br>em análise</p>
                    </header>
                </div>
            </div>    
        </div>
        <div class="row"> 
            <div class="col-md-12">
                <div class="project-block row-block-2" onclick="window.open('/commercial/order/approv', '_blank')">
                    <header>
                        <h2>{{$order_approv}}</h2>
                        <p class="block-p">Pedidos programados <br>Para você aprovar!</p>
                    </header>
                </div>
                <div class="project-block row-block-2" onclick="window.open('/commercial/order/confirmed/approv', '_blank')">
                    <header>
                        <h2>{{$order_confirmed_approv}}</h2>
                        <p class="block-p">Pedidos não programados <br>Para você aprovar!</p>
                    </header>
                </div>
                <div class="project-block row-block-2" onclick="window.open('/commercial/client/list/analyze', '_blank')">
                    <header>
                        <h2>{{$client_approv}}</h2>
                        <p class="block-p">Clientes <br>Para você aprovar!</p>
                    </header>
                </div>
            </div>
        </div>
        <div class="spacer-20"></div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="cad-header">
                        <h4 class="cad-title">Totais pedidos programados e não programados</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart_order" class="chart-general"></div>
                    </div>    
                </div>   
            </div>
        </div>

        <div class="spacer-20"></div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="cad-header">
                        <h4 class="cad-title">Quantidade de pedidos por Região</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart_order_region" class="chart-general"></div>
                    </div>    
                </div>   
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="cad-header">
                        <h4 class="cad-title">Totais de clientes por região</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart_client_region" class="chart-general"></div>
                    </div>    
                </div>   
            </div>
        </div>

        <div class="spacer-20"></div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="cad-header">
                        <h4 class="cad-title">Totais de clientes por status</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart_client_status" class="chart-general"></div>
                    </div>    
                </div>   
            </div>
        </div>

        <div class="spacer-40"></div>
    </div>
</div>

<script src="/admin/app-assets/vendors/js/charts/apexcharts.min.js"></script>
<script>

    var chart_arr_order = {!! json_encode($chart_arr_order) !!};
    var chart_arr_order_confirmed = {!! json_encode($chart_arr_order_confirmed) !!};
    var arr_order_region_programmed = {!! json_encode($arr_order_region_programmed) !!};
    var arr_order_region_not_programmed = {!! json_encode($arr_order_region_not_programmed) !!};
    var arr_client_region = {!! json_encode($arr_client_region) !!};
    var arr_client_status = {!! json_encode($arr_client_status) !!};
    var arr_month = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    var year = new Date().getFullYear();
    
    $(document).ready(function () {

        var options_order = {
            series: [
                {name: "Programados", data: chart_arr_order, is_programmed: 1},
                {name: "Não programados", data: chart_arr_order_confirmed, is_programmed: 2},
            ],
            chart: { 
                type: 'bar', 
                height: 320,
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        var is_programmed = config.w.config.series[config.seriesIndex].is_programmed;
                        var month = arr_month.indexOf(config.w.config.xaxis.categories[config.dataPointIndex]) + 1;
                        var date = ''+year+'-'+month+'';
                        
                        if(is_programmed == 1) {
                            window.open('/commercial/order/all?chart_start_date=' + date, '_blank');
                        } else {
                            window.open('/commercial/order/confirmed/all?chart_start_date=' + date, '_blank');
                        }
                    }
                }    
            },
            plotOptions: { bar: { horizontal: false, columnWidth: "80%", dataLabels: { position: 'top'} }},
            dataLabels: { enabled: true },
            stroke: { show: true, width: 1, colors: ["transparent"] },
            xaxis: { categories: arr_month },
            yaxis: { title: { text: "Totais de pedidos" } },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " pedidos";
                    }
                }
            }
        };

        var options_order_region = {
            series: [
                {name: "Programados", data: arr_order_region_programmed, is_programmed: 1},
                {name: "Não programados", data: arr_order_region_not_programmed, is_programmed: 2},
            ],
            chart: { 
                type: 'bar', 
                height: 320,
                events: {
                    dataPointSelection: function(event, chartContext, config) {

                        var is_programmed = config.w.config.series[config.seriesIndex].is_programmed;
                        var region = config.w.config.xaxis.categories[config.dataPointIndex];

                        if(is_programmed == 1) {
                            window.open('/commercial/order/all?region=' + region, '_blank');
                        } else {
                            window.open('/commercial/order/confirmed/all?region=' + region, '_blank');
                        }
                    }    
                }    
            },
            plotOptions: { bar: { horizontal: false, columnWidth: "80%", dataLabels: { position: 'top'} }},
            dataLabels: { enabled: true },
            stroke: { show: true, width: 1, colors: ["transparent"] },
            xaxis: { categories: ["Sul", "Sudeste", "Centro-Oeste", "Norte", "Nordeste"] },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " pedidos";
                    }
                }
            }
        };

        var options_client_region = {
            series: [
                {name: "Total", data: arr_client_region },
            ],
            chart: { 
                type: 'bar', 
                height: 320,
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        var region = config.w.config.xaxis.categories[config.dataPointIndex];
                        window.open('/commercial/client/list?region=' + region, '_blank');
                    } 
                }       
            },
            plotOptions: { bar: { horizontal: false, columnWidth: "70%", dataLabels: { position: 'top'} }},
            dataLabels: { enabled: true },
            stroke: { show: true, width: 1, colors: ["transparent"] },
            xaxis: { categories: ["Sul", "Sudeste", "Centro-Oeste", "Norte", "Nordeste"] },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " clientes";
                    }
                }
            }
        };

        var options_client_status = {
            series: [
                {name: "Total", data: arr_client_status },
            ],
            chart: { 
                type: 'bar', 
                height: 320,
                events: {
                    dataPointSelection: function(event, chartContext, config) {

                        var status = config.w.config.xaxis.categories[config.dataPointIndex];
                        window.open('/commercial/client/list?status_chart=' + status, '_blank');
                    } 
                }
            },
            plotOptions: { bar: { horizontal: false, columnWidth: "55%", dataLabels: { position: 'top'} }},
            dataLabels: { enabled: true },
            stroke: { show: true, width: 1, colors: ["transparent"] },
            xaxis: { 
                categories: ["Desativado", "Liberado antecipado", "Liberado antecipado e parcelado", "Ativo"],
                labels: {
                    show: true,
                    rotate: -15,
                    trim: false,
                    maxHeight: 70,
                    style: {
                        fontSize: '12px',
                    },
                }                  
            },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " clientes";
                    }
                }
            }
        };

        new ApexCharts(document.querySelector("#chart_order"), options_order).render();
        new ApexCharts(document.querySelector("#chart_order_region"), options_order_region).render();
        new ApexCharts(document.querySelector("#chart_client_region"), options_client_region).render();
        new ApexCharts(document.querySelector("#chart_client_status"), options_client_status).render();
        
        $("#operation").addClass('menu-open');
        $("#dashboard_general").addClass('page-arrow active-page');
    });
</script>

@endsection
