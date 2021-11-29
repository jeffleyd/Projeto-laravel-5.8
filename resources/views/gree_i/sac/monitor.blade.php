@extends('gree_i.layout')

@section('content')
	<style>
	.fullscreen {
		z-index: 9999;
		position: fixed;
		width: 100%!important;
		height: 100%!important;
		top: 0;
		right: 0;
		left: 0;
		bottom: 0;
		overflow: hidden;
		background: #efefef;
		margin: 0;	
	}
	</style>
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-6 col-sm-12 col-lg-6">
                        <h5 class="content-header-title float-left pr-1 mb-0">SAC</h5>
                        <div class="breadcrumb-wrapper col-12">
                            Central de monitoramento
                        </div>
                    </div>
                    @if (Session::get('filter_line') == 1)
                        <div class="col-6 col-sm-12 col-lg-6">
                            <fieldset class="form-group float-right">
                                <select class="form-control" id="type_line" name="type_line" style="width: 106%;">
                                    <option value="">Todos</option>
                                    <option value="1" @if (Session::get('sacf_type_line') == '1') selected @endif>Residencial</option>
                                    <option value="2" @if (Session::get('sacf_type_line') == '2') selected @endif>Comercial</option>
                                </select>
                            </fieldset>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section id="dashboard-analytics">
                <div class="row" id="viewFullScreen">
					<div class="col-md-9 mt-2">
                        <h6 class="card-title">TOTAL DE PROTOCOLOS ABERTOS: <b>{{$protocol_total}}</b></h6>
                    </div>
					<div class="col-md-3">
						<button type="button" onclick="activeFullScreen()" class="btn btn-primary mt-1 float-right">Tela cheia</button>
					</div>
					<div class="col-12 col-sm-12 col-lg-4 cursor-pointer" onclick="getDayLeft(1, this)">
						<div class="card text-center" id="left_5">
							<div class="card-content">
								<div class="card-body">
								<div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto my-1">
									<i class="bx bxs-error-alt font-medium-5"></i>
								</div>
								<p class="text-muted mb-0 line-ellipsis">5 - 14 dias <small>(Sem conclusão)</small></p>
								<h2 class="mb-0">{{ $total_5 }}</h2>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-lg-4 cursor-pointer" onclick="getDayLeft(2, this)">
						<div class="card text-center" id="left_15">
							<div class="card-content">
								<div class="card-body">
								<div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto my-1">
									<i class="bx bxs-error-alt font-medium-5"></i>
								</div>
								<p class="text-muted mb-0 line-ellipsis">15 - 29 dias <small>(Sem conclusão)</small></p>
								<h2 class="mb-0">{{ $total_15 }}</h2>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-lg-4 cursor-pointer" onclick="getDayLeft(3, this)">
						<div class="card text-center" id="left_30">
							<div class="card-content">
								<div class="card-body">
								<div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1">
									<i class="bx bxs-error-alt font-medium-5"></i>
								</div>
								<p class="text-muted mb-0 line-ellipsis">30+ dias <small>(Sem conclusão)</small></p>
								<h2 class="mb-0">{{ $total_30 }}</h2>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3" onclick="window.open('/sac/warranty/all?monitor_p_block_1=true', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Protocolo foi aberto e não há nenhum atendente cuidando.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$block_1}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Recebimento de Atend.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-3" onclick="window.open('/sac/warranty/all?monitor_p_block_2=true', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Há um atendente verificando se é um atendimento em garantia ou apenas uma dúvida.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$block_2}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Verificando atend.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-3" onclick="window.open('/sac/warranty/all?monitor_p_block_3=true', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Está aguardando documentação para validar o atendimento em garantia.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$block_3}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Aguard. Documentos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-3" onclick="window.open('/sac/warranty/all?monitor_p_block_4=true', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="É um atendimento em garantia, mas ainda não tem uma credenciada/autorizada no atendimento.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$block_4}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Aguard. data de Agend.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-3" onclick="window.open('/sac/warranty/all?monitor_p_block_5=true', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="O atendimento foi agendado e está aguardando o dia da realização da visita.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$block_5}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Atend. Agendado</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-3" onclick="window.open('/sac/warranty/all?monitor_p_block_6=true', '_blank')" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Atendimentos que já foram realizados a visita, mas não há pedido de peça.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$block_6}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Aguard. Análise da credenciada</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
                    <div class="col-md-3" onclick="window.open('/sac/warranty/all?status=9', '_blank')" style="cursor: pointer">
                        <div class="card bg-warning text-white">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Cliente fez uma interação e nenhum operador respondeu ainda.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$without_response}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Aguard. Resposta do SAC</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-3" onclick="window.open('/sac/warranty/all?monitor_block_4=true', '_blank')" style="cursor: pointer">
                        <div class="card bg-warning text-white">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Já foi realizado o serviço, colaborador da GREE precisar entrar em contato com CLIENTE ou TÉCNICO.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$pending_completed}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Atend. Pendentes do SAC</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-4" onclick="window.open('/sac/warranty/all?monitor_p_block_9=true', '_blank')" style="cursor: pointer">
                        <div class="card bg-danger text-white">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Atendimentos tercerizados.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$block_9}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Atendimentos tercerizados</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-4" onclick="window.open('/sac/warranty/all?monitor_p_block_7=true', '_blank')" style="cursor: pointer">
                        <div class="card bg-danger text-white">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Atendimentos que estão no processo de serem reembolsados.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$block_7}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Reembolso pendente</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-4" onclick="window.open('/sac/warranty/all?monitor_p_block_8=true', '_blank')" style="cursor: pointer">
                        <div class="card bg-danger text-white">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Atendimentos do reclame aqui.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{$block_8}}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li>Reclame aqui</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
					
                    <!-- Website Analytics Starts-->
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-3">
                                <h4 class="card-title">Análise de operação</h4>
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
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                                <h4 class="card-title">Tipo de atendimento</h4>
                                <div class="heading-elements">
                                    <button type="button" onclick="viewFilter(3)" class="btn btn-outline-primary mr-1 mb-1">Filtrar</button>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="pb-1 pt-3 d-flex justify-content-center" id="type-chart-statistics"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                                <h4 class="card-title">Atendentes com protocolo em aberto</h4>
                                <div class="heading-elements">
                                    <button type="button" onclick="viewFilter(2)" class="btn btn-outline-primary mr-1 mb-1">Filtrar</button>
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
                                <h4 class="card-title">Origem de atendimento</h4>
                                <div class="heading-elements">
                                    <button type="button" onclick="viewFilter(4)" class="btn btn-outline-primary mr-1 mb-1">Filtrar</button>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="pb-1 pt-3 d-flex justify-content-center" id="origin-chart-statistics"></div>
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
        var donustChartStatistics;
        var typeStatistics;
        var originStatistics;
        var typeLine = "";
		var hasFullScreen = @if (Request::has('has_fullscreen')) {{Request::get('has_fullscreen')}} @else false @endif;
		
		function activeFullScreen() {
			if (!hasFullScreen) {
				hasFullScreen = true;
				$('#viewFullScreen').addClass('fullscreen');
			} else {
				hasFullScreen = false;
				$('#viewFullScreen').removeClass('fullscreen');	
			}
				
		}

		@if (Request::get('has_fullscreen') == 'true')
		$('#viewFullScreen').addClass('fullscreen');
		@endif
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

            // Bar Chart
            // ---------
            analyticsBarChartOptions = {
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
                    name: '<?= date('Y') ?>',
                    data: [
                        @foreach ($total_protocol as $key)
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
                            return val + " Atendimentos abertos"
                        }
                    }
                }
            }

            analyticsBarChart = new ApexCharts(
                document.querySelector("#analytics-bar-chart"),
                analyticsBarChartOptions
            );

            analyticsBarChart.render();

            //analyticsBarChart.updateSeries([{
            //    data: [150, 95, 150, 210, 140, 230, 300, 280, 130, 0, 0, 0]
            //}])


            // Donut Chart Statistics
            // -----------------------

            donustChartStatistics = {
                series: [{
                    name: 'Atendimentos',
                    data: [
                        @foreach ($protocol_w_u as $key)
                        <?= $key['total'] ?>,
                        @endforeach
                    ]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            var name = config.w.config.xaxis.categories[config.dataPointIndex]
                            var r_code = name.replace( /^\D+/g, '');
                            window.open('/sac/warranty/all?code=&r_code=' + r_code, '_blank');
                        },
                        mounted: function(chartContext, config) {
                            setInterval(() => {
                                if (donustChartStatistics.toolbar.elMenuItems[0].parentElement != null) {
                                    donustChartStatistics.toolbar.elMenuItems[0].parentElement.innerHTML = '<div class="apexcharts-menu-item"><a style="color: #727E8C !important;" target="_blank" href="/sac/warranty/all?code=&export=1">Download XLSX</a></div><div class="apexcharts-menu-item exportSVG" title="Download SVG">Download SVG</div><div class="apexcharts-menu-item exportPNG" title="Download PNG">Download PNG</div>';
                                }
                            }, 300);
                        }
                    }
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
                        @foreach ($protocol_w_u as $key)
                            '<?= $key['name'] ?>',
                        @endforeach
                    ],
                }
            };

            donustChartStatistics = new ApexCharts(
                document.querySelector("#donut-chart-statistics"),
                donustChartStatistics
            );
            donustChartStatistics.render();

            // donustChartStatistics.updateOptions([xaxis: { categories: ['Jéssica ', 'Fábio', 'Ketlen', 'Blenda', 'Jefferson'] }]);

            //donustChartStatistics.updateSeries([{
            //  name: 'Atendimentos',
            //  data: [22, 15, 8, 3, 4]
            //}])

            var arr_atend = [
                @foreach ($type_list as $key)
                    '<?= $key['name'] ?>',
                @endforeach
            ];
            typeStatistics = {
                series: [{
                    name: 'Total',
                    data: [
                        @foreach ($type_list as $key)
                        <?= $key['total'] ?>,
                        @endforeach
                    ]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    events: {
                        dataPointSelection: function(event, chartContext, config) {

                            if (config.dataPointIndex == 0)
                                window.open('/sac/warranty/all?type=10&type_line='+typeLine+'', '_blank');
                            else if (config.dataPointIndex == 1)
                                window.open('/sac/warranty/all?type=9&type_line='+typeLine+'', '_blank');
                            else if (config.dataPointIndex == 2)
                                window.open('/sac/warranty/all?type=8&type_line='+typeLine+'', '_blank');
                            else if (config.dataPointIndex == 3)
                                window.open('/sac/warranty/all?type=7&type_line='+typeLine+'', '_blank');
                            else if (config.dataPointIndex == 4)
                                window.open('/sac/warranty/all?type=6&type_line='+typeLine+'', '_blank');
                            else if (config.dataPointIndex == 5)
                                window.open('/sac/warranty/all?type=5&type_line='+typeLine+'', '_blank');
                            else if (config.dataPointIndex == 6)
                                window.open('/sac/warranty/all?type=4&type_line='+typeLine+'', '_blank');
                            else if (config.dataPointIndex == 7)
                                window.open('/sac/warranty/all?type=3&type_line='+typeLine+'', '_blank');
                            else if (config.dataPointIndex == 8)
                                window.open('/sac/warranty/all?type=2&type_line='+typeLine+'', '_blank');
                            else if (config.dataPointIndex == 9)
                                window.open('/sac/warranty/all?type=1&type_line='+typeLine+'', '_blank');
                        }
                    }
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
                        @foreach ($type_list as $key)
                            '<?= $key['name'] ?>',
                        @endforeach
                    ],
                }
            };

            typeStatistics = new ApexCharts(
                document.querySelector("#type-chart-statistics"),
                typeStatistics
            );
            typeStatistics.render();


            originStatistics = {
                series: [{
                    name: 'Total',
                    data: [
                        @foreach ($origin_list as $key)
                        <?= $key['total'] ?>,
                        @endforeach
                    ]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            if (config.dataPointIndex == 0)
                                window.open('/sac/warranty/all?origin=5', '_blank');
                            else if (config.dataPointIndex == 1)
                                window.open('/sac/warranty/all?origin=3', '_blank');
                            else if (config.dataPointIndex == 2)
                                window.open('/sac/warranty/all?origin=2', '_blank');
                            else if (config.dataPointIndex == 3)
                                window.open('/sac/warranty/all?origin=1', '_blank');
                        }
                    }
                },
                fill: {
                    colors: [$danger]
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
                        @foreach ($origin_list as $key)
                            '<?= $key['name'] ?>',
                        @endforeach
                    ],
                }
            };

            originStatistics = new ApexCharts(
                document.querySelector("#origin-chart-statistics"),
                originStatistics
            );
            originStatistics.render();


            setInterval(() => {
                $("#mAfterSales").addClass('sidebar-group-active active');
                $("#mSac").addClass('sidebar-group-active active');
                $("#mSacDashboard").addClass('active');
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
                url: '/sac/monitor/filter/ajax',
                timeout:10000, //10 second timeout
                data: {year: $("#year").val(), month: $("#month").val(), block: itsBlock, type_line: $("#type_line").val()},
                success: function(response){
                    intervalOff = response.has_filter;
                    if (itsBlock == 1) {
                        if (response.has_filter) {
                            // Block 1
                            analyticsBarChart.updateSeries([{
                                name: $("#year").val(),
                                data: response.total_protocol
                            }]);

                            analyticsBarChart.updateOptions({ xaxis: { categories: response.total_days } });

                        } else {

                            // Block 1
                            analyticsBarChart.updateSeries([{
                                name: '<?= date('Y') ?>',
                                data: response.total_protocol
                            }]);

                            console.log('padrão');
                            analyticsBarChart.updateOptions({ xaxis: { categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'] } });
                        }
                    } else if (itsBlock == 2) {
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

                    } else if (itsBlock == 3) {
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

                    } else if (itsBlock == 4) {
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
        $(document).ready(function () {
            interval = setInterval(() => {
				if (hasFullScreen) {
					window.location.href = "/sac/monitor?has_fullscreen=true";
				} else {
					window.location.href = "/sac/monitor?has_fullscreen=false";
				}
            }, 120000);
        });
        $("#type_line").change(function(){
            var type = $(this).val();
            if (type == 1) {
                typeLine = "residential";
            } else if(type == 2){
                typeLine = "commercial";
            } else {
                typeLine = "";
            }
            loadData();
        });
    </script>
@endsection
