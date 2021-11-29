@extends('gree_i.layout')

@section('content')
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-6 col-sm-12 col-lg-6">
                        <h5 class="content-header-title float-left pr-1 mb-0">Assistência técnica</h5>
                        <div class="breadcrumb-wrapper col-12">
                            Central de monitoramento de OS's
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section id="dashboard-analytics">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="card-title">TOTAL DE OS EM ABERTOS: <b>{{$os_total}}</b></h6>
                    </div>
                    <div class="col-md-3" onclick="window.open('/sac/warranty/all?not_response=1', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Aguardando análise de informações para responder ao SAC.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$without_response}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-warning mr-50"></span>SAC Aguardando resposta</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" onclick="window.open('/sac/warranty/os/all?status=2', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="OS com peça e aguardando ser análisada para dar andamento.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$analyze_part_pending}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-primary mr-50"></span>Análise de Peça pendente</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-3" onclick="window.open('/sac/warranty/os/all?status=10', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="OS está suspensa por conta de falta de informações para aprovar as peças envolvidas.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$part_suspense}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-primary mr-50"></span>Suspensos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" onclick="window.open('/sac/warranty/os/all?status=3', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Foi realizado análise da peça, mas ainda as peças não foram aprovadas.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$part_pending}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-danger mr-50"></span>OS Aguard. Aprov. de Peça</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" onclick="window.open('/sac/warranty/os/all?status=1', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Peças aprovadas, mas ainda não foram enviadas para separação.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$part_approv}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-success mr-50"></span>Peças Aprovadas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" onclick="window.open('/sac/warranty/os/all?status=4', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Falta ser realizado a PG/PN para enviar para a expedição e faturamento.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$split_pending}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-success mr-50"></span>Aguard. Envio P/ Separação</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-3" onclick="window.open('/sac/warranty/os/all?status=11', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Aguardando autorização para serguir com o pagamento.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$pending_payment}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-success mr-50"></span>Pend. de pagamento</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" onclick="window.open('/sac/warranty/os/all?status=12', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Visitas realizadas ou agendandas, mas que não tem pedido de peça e não foram concluídos ainda.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$services}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-success mr-50"></span>Serviço Prestados</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-3" onclick="window.open('/sac/warranty/os/all?gree_os=true', '_blank')" style="cursor: pointer">
                        <div class="card bg-warning text-white">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Atendimentos que estão com a GREE anexado e não tem uma credenciada cadastrada.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$gree_os}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>GREE no atendimento</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-3" onclick="window.open('/sac/warranty/os/all?warranty_extend=true', '_blank')" style="cursor: pointer">
                        <div class="card bg-warning text-white">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Atendimentos que estão em processo de garantia extendida para não sofrer reembolso.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$warranty_extend}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Garantia extendida</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-3" onclick="window.open('/sac/warranty/all?monitor_p_block_10=true', '_blank')" style="cursor: pointer">
                        <div class="card bg-warning text-white">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Atendimentos que foram autorizados a instalação.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$authorization_install}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Autorização de instalação</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-3" onclick="window.open('/sac/warranty/os/all?origin=3', '_blank')" style="cursor: pointer">
                        <div class="card" style="background: red; color:white">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Atendimento prioritários do reclame aqui.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$reclameaqui}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Reclame aqui</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-12" onclick="window.open('/sac/assistance/remittance/all?status=1', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Novas solicitações de remessa de peças">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$sac_remittance_news}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-warning mr-50"></span>OS Remessa de Peças</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Website Analytics Starts-->
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-3">
                                <h4 class="card-title">Atendimento em garantia</h4>
                                <div class="heading-elements">
                                    <button type="button" onclick="viewFilter(1)" class="btn btn-outline-primary mr-1 mb-1">Filtrar</button>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body pb-1">
                                    <div id="analytics-bar-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Website Analytics Starts-->
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-3">
                                <h4 class="card-title">Remessa de peça</h4>
                                <div class="heading-elements">
                                    <button type="button" onclick="viewFilter(2)" class="btn btn-outline-primary mr-1 mb-1">Filtrar</button>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body pb-1">
                                    <div id="analytics-bar-chart-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                                <h4 class="card-title">Técnicos envolvidos em Atend. Em Garantia</h4>
                                <div class="heading-elements">
                                    <button type="button" onclick="viewFilter(3)" class="btn btn-outline-primary mr-1 mb-1">Filtrar</button>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="pb-1 pt-3 d-flex justify-content-center" id="donut-chart-statistics"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                                <h4 class="card-title">Técnicos envolvidos em Remessa</h4>
                                <div class="heading-elements">
                                    <button type="button" onclick="viewFilter(4)" class="btn btn-outline-primary mr-1 mb-1">Filtrar</button>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="pb-1 pt-3 d-flex justify-content-center" id="donut-chart-statistics-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="modal fade" id="filterMonitor" tabindex="-1" role="dialog" aria-labelledby="filterMonitor" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filtrar dados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label for="year">Escolha o ano</label>
                                <select id="year" name="year" class="form-control">
                                    @foreach ($year_range as $key)
                                        <option value="{{$key}}">{{$key}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label for="month">Escolha o mês</label>
                                <select id="month" name="month" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="button" onclick="loadinfo()" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Filtrar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var analyticsBarChart;
        var analyticsBarChart2;
        var donustChartStatistics;
        var donustChartStatistics2;

        $(window).on("load", function () {
            var $primary = '#5A8DEE';
            var $success = '#39DA8A';
            var $danger = '#F44336';
            var $warning = '#FDAC41';
            var $info = '#00CFDD';
            var $label_color = '#475f7b';
            var $primary_light = '#E2ECFF';
            var $danger_light = '#ffeed9';
            var $gray_light = '#828D99';
            var $sub_label_color = "#596778";
            var $radial_bg = "#e7edf3";


            analyticsBarChart = new ApexCharts(
                document.querySelector("#analytics-bar-chart"),
                {
                    chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                position: 'top',
                            },
                        },
                    },
                    legend: {
                        horizontalAlign: 'right',
                    },
                    dataLabels: {
                        enabled: true
                    },
                    series: [{
                        name: 'OS Aberta',
                        data: [
                            @foreach ($os_graph_open_total as $key)
                            <?= $key ?>,
                            @endforeach
                        ]
                    }, {
                        name: 'OS Concluída',
                        data: [
                            @foreach ($os_graph_completed_total as $key)
                            <?= $key ?>,
                            @endforeach
                        ]
                    }],
                    xaxis: {
                        categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                        axisBorder: {
                            show: false
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " OS's"
                            }
                        }
                    }
                }
            );

            analyticsBarChart.render();

            analyticsBarChart2 = new ApexCharts(
                document.querySelector("#analytics-bar-chart-2"),
                {
                    chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                position: 'top',
                            },
                        },
                    },
                    legend: {
                        horizontalAlign: 'right',
                    },
                    dataLabels: {
                        enabled: true
                    },
                    series: [{
                        name: 'OS Aberta',
                        data: [
                            @foreach ($remitted_graph_open_total as $key)
                            <?= $key ?>,
                            @endforeach
                        ]
                    }, {
                        name: 'OS Concluída',
                        data: [
                            @foreach ($remitted_graph_completed_total as $key)
                            <?= $key ?>,
                            @endforeach
                        ]
                    }],
                    xaxis: {
                        categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                        axisBorder: {
                            show: false
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " OS's"
                            }
                        }
                    }
                }
            );

            analyticsBarChart2.render();

            donustChartStatistics = new ApexCharts(
                document.querySelector("#donut-chart-statistics"),
                {
                    series: [{
                        name: "OS's",
                        data: [
                            @foreach ($tec_with_analyze as $key)
                            <?= $key['total'] ?>,
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                position: 'top'
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                colors: ['#000000']
                            },
                            offsetX: 30
                        },
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: [
                            @foreach ($tec_with_analyze as $key)
                                '<?= $key['name'] ?>',
                            @endforeach
                        ],
                    }
                }
            );
            donustChartStatistics.render();

            donustChartStatistics2 = new ApexCharts(
                document.querySelector("#donut-chart-statistics-2"),
                {
                    series: [{
                        name: "OS's",
                        data: [
                            @foreach ($tec_with_analyze_remmitance as $key)
                            <?= $key['total'] ?>,
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                position: 'top'
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                colors: ['#000000']
                            },
                            offsetX: 30
                        },
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: [
                            @foreach ($tec_with_analyze_remmitance as $key)
                                '<?= $key['name'] ?>',
                            @endforeach
                        ],
                    }
                }
            );
            donustChartStatistics2.render();

            setInterval(() => {
                $("#mAfterSales").addClass('sidebar-group-active active');
                $("#mTAssist").addClass('sidebar-group-active active');
                $("#mSacOsDashboard").addClass('active');
            }, 100);

        });
    </script>
    <script>
        var interval;
        var intervalOff = false;
        var name_0,name_1,name_2 = [];
        var total_0,total_1,total_2 = [];
        var itsBlock = 1;

        function viewFilter(block) {
            itsBlock = block;
            $("#filterMonitor").modal();
        }
        function loadinfo() {
            $("#filterMonitor").modal('toggle');
            $.ajax({
                type: "GET",
                url: '/sac/warranty/os/monitor/filter',
                timeout:10000, //10 second timeout
                data: {year: $("#year").val(), month: $("#month").val(), block: itsBlock, type_line: $("#type_line").val()},
                success: function(response){
                    intervalOff = response.has_filter;
                    if (itsBlock == 1) {
                        // Block 1
                        analyticsBarChart.updateSeries([{
                            name: 'OS Aberta',
                            data: response.os_graph_open_total
                        }, {
                            name: 'OS Concluída',
                            data: response.os_graph_completed_total
                        }]);
                        if (response.has_filter) {
                            analyticsBarChart.updateOptions({ xaxis: { categories: response.total_days } });

                        } else {
                            analyticsBarChart.updateOptions({ xaxis: { categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'] } });
                        }
                    } else if (itsBlock == 2) {
                        // Block 1
                        analyticsBarChart2.updateSeries([{
                            name: 'OS Aberta',
                            data: response.remitted_graph_open_total
                        }, {
                            name: 'OS Concluída',
                            data: response.remitted_graph_completed_total
                        }]);
                        if (response.has_filter) {
                            analyticsBarChart2.updateOptions({ xaxis: { categories: response.total_days } });

                        } else {
                            analyticsBarChart2.updateOptions({ xaxis: { categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'] } });
                        }
                    } else if (itsBlock == 3) {
                        name_0 = [];
                        total_0 = [];
                        for (let i = 0; i < response.tec_with_analyze.length; i++) {
                            const obj = response.tec_with_analyze[i];
                            name_0.push(obj.name);
                            total_0.push(obj.total);
                        }

                        donustChartStatistics.updateOptions({ xaxis: { categories: name_0 } });

                        donustChartStatistics.updateSeries([{
                            name: "OS's",
                            data: total_0
                        }]);

                    } else if (itsBlock == 4) {
                        name_0 = [];
                        total_0 = [];
                        for (let i = 0; i < response.tec_with_analyze_remmitance.length; i++) {
                            const obj = response.tec_with_analyze_remmitance[i];
                            name_0.push(obj.name);
                            total_0.push(obj.total);
                        }

                        donustChartStatistics2.updateOptions({ xaxis: { categories: name_0 } });

                        donustChartStatistics2.updateSeries([{
                            name: "OS's",
                            data: total_0
                        }]);

                    }


                },
                error: function(jqXHR, textStatus){
                    if(textStatus === 'timeout') {

                        error('O servidor demorou para responder, os dados do monitoramento não foram atualizados.');
                    } else {

                        error('Aconteceu um erro inesperado, os dados do monitoramento não foram atualizados.');
                    }
                }
            });

            $("#year").val(2020);
            $("#month").val("");
        }
        function loadData() {
            $.ajax({
                type: "GET",
                url: '/sac/monitor/ajax',
                timeout:10000, //10 second timeout
                data: {type_line: $("#type_line").val()},
                success: function(response){

                    // Block 1
                    analyticsBarChart.updateSeries([{
                        name: '<?= date('Y') ?>',
                        data: response.total_protocol
                    }]);

                    // Block 2
                    name_0 = [];
                    total_0 = [];
                    for (let i = 0; i < response.protocol_w_u.length; i++) {
                        const obj = response.protocol_w_u[i];
                        name_0.push(obj.name);
                        total_0.push(obj.total);
                    }

                    donustChartStatistics.updateOptions({ xaxis: { categories: name_0 } });

                    donustChartStatistics.updateSeries([{
                        name: 'Atendimentos',
                        data: total_0
                    }]);

                    // Block 3
                    name_1 = [];
                    total_1 = [];
                    for (let i = 0; i < response.type_list.length; i++) {
                        const obj = response.type_list[i];
                        name_1.push(obj.name);
                        total_1.push(obj.total);
                    }

                    typeStatistics.updateOptions({xaxis: { categories: name_1 }});

                    typeStatistics.updateSeries([{
                        name: 'Total',
                        data: total_1
                    }]);

                    // Block 4
                    name_2 = [];
                    total_2 = [];
                    for (let i = 0; i < response.origin_list.length; i++) {
                        const obj = response.origin_list[i];
                        name_2.push(obj.name);
                        total_2.push(obj.total);
                    }

                    originStatistics.updateOptions({xaxis: { categories: name_2 }});

                    originStatistics.updateSeries([{
                        name: 'Total',
                        data: total_2
                    }]);

                },
                error: function(jqXHR, textStatus){
                    if(textStatus === 'timeout') {

                        error('O servidor demorou para responder, os dados do monitoramento não foram atualizados.');
                    } else {

                        error('Aconteceu um erro inesperado, os dados do monitoramento não foram atualizados.');
                    }
                }
            });
        }
    </script>
@endsection
