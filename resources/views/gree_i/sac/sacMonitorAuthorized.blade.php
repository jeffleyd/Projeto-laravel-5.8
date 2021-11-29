@extends('gree_i.layout')

@section('content')

    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section id="dashboard-analytics">
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-transform:uppercase">Total de Assist. Tec. Autorizadas Ativas:
                            <b>{{ $total }}</b>
                        </p>
                    </div>
                    <!-- Rank de bem-avaliados-->
                    <div class="col-md-9 col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom ">
                                <h4 class="card-title">Bem Avaliados ({{ $year }})</h4>
                                <!-- Selecionar a quantidade de bem avaliados-->
                                <div class="col-md-6 align-items-center">
                                    <form action="{{ Request::url() }}">
                                        <fieldset>
                                            <div class="input-group ">
                                                <input type="text" name="year" class="form-control"
                                                    placeholder="Informe o ano" value="{{ Request::get('year') }}"
                                                    aria-describedby="button-addon2" MAXLENGTH="4">
                                                <input type="text" name="quantity" class="form-control"
                                                    placeholder="Quantidade para exibir"
                                                    value="{{ Request::get('quantity') }}"
                                                    aria-describedby="button-addon2" MAXLENGTH="2">
                                                <div class="input-group-append" id="button-addon2">
                                                    <button class="btn btn-primary" type="submit"
                                                        onclick="block();">Ir</button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>


                            <div class="card-content">
                                <div class="card-body pb-1">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <div id="list-datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6"></div>
                                                <div class="col-sm-12 col-md-6"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="list-datatable" class="table dataTable no-footer" role="grid"
                                                        aria-describedby="list-datatable_info">
                                                        <thead>
                                                            <tr role="row">
                                                                <th class="sorting_disabled" rowspan="1" colspan="1"
                                                                    style="width: 52.0667px;">Posicao
                                                                </th>
                                                                <th class="sorting_disabled" rowspan="1" colspan="1"
                                                                    style="width: auto;">Nome
                                                                </th>
                                                                <th class="sorting_disabled" rowspan="1" colspan="1"
                                                                    style="width: 163.8px;">Avaliacao
                                                                </th>
                                                                <th class="sorting_disabled" rowspan="1" colspan="1"
                                                                    style="width: 80.0px; text-align: center">Quantidade
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($arrBemAvaliadas as $i => $key)
                                                                <tr role="row" class="odd">
                                                                    <td>
                                                                        <div class="avatar m-0 p-25"
                                                                            style=" background-color: rgb(0, 80, 165) ">
                                                                            <!-- style='-->
                                                                            <div class="avatar-content"
                                                                                style="color: white; font-weight: normal;font-size: 18px;">
                                                                                {{ $i + 1 }}
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="list-content">
                                                                            <span class="list-title text-bold-500">
                                                                                {{ $key[0] }}</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        @if ($key[1] > 0.0)
                                                                            <i class="bx bxs-star @if ($key[1]> 0) text-warning
                                                                            @elseif ($key[1] < 1) text-muted @endif"></i>
                                                                            <i class="bx bxs-star @if ($key[1]> 1) text-warning
                                                                            @elseif ($key[1] < 2) text-muted @endif"></i>
                                                                            <i class="bx bxs-star @if ($key[1]> 2) text-warning
                                                                            @elseif ($key[1] < 3) text-muted @endif"></i>
                                                                            <i class="bx bxs-star @if ($key[1]> 3) text-warning
                                                                            @elseif ($key[1] < 4) text-muted @endif"></i>
                                                                            <i class="bx bxs-star @if ($key[1]> 4) text-warning
                                                                            @elseif ($key[1] < 5) text-muted @endif"></i>
                                                                        @else
                                                                            --
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="list-content"
                                                                            style="text-align: center">
                                                                            <span class="list-title text-bold-500">
                                                                                {{ $key[2] }}</span>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-5">
                                                    <div class="dataTables_info" id="list-datatable_info" role="status"
                                                        aria-live="polite">
                                                        Visualizando 1 de 5 do total: 5</div>
                                                </div>
                                                <div class="col-sm-12 col-md-7"></div>
                                            </div>
                                        </div>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination justify-content-end">
                                            </ul>
                                        </nav>
                                    </div>
                                    <!-- datatable ends -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3" style="cursor: pointer">
                        <div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom"
                                data-original-title="Total de cadastros ativos na plataforma.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{ $total_active }}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-primary mr-50"></span>Ativos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
						<div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom"
                                data-original-title="Credenciadas que ao menos fizeram 1 trabalho para gree.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{ $total_base }}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-primary mr-50"></span>Base única</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
						<div class="card">
                            <div class="card-content" data-toggle="tooltip" data-placement="bottom"
                                data-original-title="Credenciadas que ao menos fizeram 1 trabalho para gree a partir dos últimos 3 meses.">
                                <div class="card-body donut-chart-wrapper">
                                    <div class="d-flex justify-content-center">
                                        <span style="font-size: 40px;font-family:'Rubik';">{{ $total_base_last_3_month }}</span>
                                    </div>
                                    <ul class="list-inline d-flex justify-content-around mb-0">
                                        <li><span class="bullet bullet-xs bullet-primary mr-50"></span>Base única a partir de três meses</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Representacao do total de trabalhados por mes-->
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-3">
                                <h4 class="card-title">Quantidade de Ass. Téc. Autorizadas que Realizaram O.S. no Mês</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body pb-1">
                                    <div id="graphicBarArrTotalAuthorizedByMonth"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Representacao do total de novos cadastros por mes-->
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-3">
                                <h4 class="card-title">Novas Assist. Tec. Autorizadas Cadastradas Ativas
                                    ({{ $year }})</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body pb-1">
                                    <div id="graphicBarNovosCadastros"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            
        </div>
    </div>


    <script>
        var analyticsBarChart;
        var donustChartStatistics;
        var typeStatistics;
        var originStatistics;
        var typeLine = "";

        $(window).on("load", function() {
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

            // Bar Chart 0
            // ---------
            graphicBarArrTotalAuthorizedByMonthOptions = {
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
                        @foreach ($arrTotalAuthorizedByMonth as $key)
                            "<?= $key ?>",
                        @endforeach

                    ]
                }],
                xaxis: {
                    categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov',
                        'Dez'
                    ],
                    axisBorder: {
                        show: false
                    },
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " Qntd. Assist. Téc. Autorizadas"
                        }
                    }
                }
            }
            graphicBarArrTotalAuthorizedByMonth = new ApexCharts(

                document.querySelector("#graphicBarArrTotalAuthorizedByMonth"),
                graphicBarArrTotalAuthorizedByMonthOptions
            );
            graphicBarArrTotalAuthorizedByMonth.render();



            // Bar Chart2
            // ---------
            graphicBarNovosCadastrosOptions = {
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
                        @foreach ($arrAllNewAuthorizedIsAtctive as $key)
                            "<?= $key ?>",
                        @endforeach
                    ]
                }],
                xaxis: {
                    categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov',
                        'Dez'
                    ],
                    axisBorder: {
                        show: false
                    },
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + "  Qntd Novas Ass. Téc. Autorizadas"
                        }
                    }
                }
            }
            graphicBarNovosCadastros = new ApexCharts(

                document.querySelector("#graphicBarNovosCadastros"),
                graphicBarNovosCadastrosOptions
            );
            graphicBarNovosCadastros.render();

            //analyticsBarChart.updateSeries([{
            //    data: [150, 95, 150, 210, 140, 230, 300, 280, 130, 0, 0, 0]
            //}])


            setInterval(() => {
                $("#mAfterSales").addClass('sidebar-group-active active');
                $("#mSac").addClass('sidebar-group-active active');
                $("#mSacAuthorizedMonitor").addClass('active');
            }, 100);

        });
    </script>

    <script>
        var interval;
        var intervalOff = false;
        var name_0, name_1, name_2 = [];
        var total_0, total_1, total_2 = [];
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
                timeout: 10000, //10 second timeout
                data: {
                    year: $("#year").val(),
                    month: $("#month").val(),
                    block: itsBlock,
                    type_line: $("#type_line").val()
                },
                success: function(response) {
                    intervalOff = response.has_filter;
                    if (itsBlock == 1) {
                        if (response.has_filter) {
                            // Block 1
                            analyticsBarChart.updateSeries([{
                                name: $("#year").val(),
                                data: response.total_protocol
                            }]);

                            analyticsBarChart.updateOptions({
                                xaxis: {
                                    categories: response.total_days
                                }
                            });
                        } else {

                            // Block 1
                            analyticsBarChart.updateSeries([{
                                name: '<?= date('Y') ?>',
                                data: response.total_protocol
                            }]);

                            console.log('padrão');
                            analyticsBarChart.updateOptions({
                                xaxis: {
                                    categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago',
                                        'Set', 'Out', 'Nov', 'Dez'
                                    ]
                                }
                            });
                        }
                    } else if (itsBlock == 2) {
                        name_0 = [];
                        total_0 = [];
                        for (let i = 0; i < response.protocol_w_u.length; i++) {
                            const obj = response.protocol_w_u[i];
                            name_0.push(obj.name);
                            total_0.push(obj.total);
                        }

                        donustChartStatistics.updateOptions({
                            xaxis: {
                                categories: name_0
                            }
                        });

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

                        typeStatistics.updateOptions({
                            xaxis: {
                                categories: name_1
                            }
                        });

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

                        originStatistics.updateOptions({
                            xaxis: {
                                categories: name_2
                            }
                        });

                        originStatistics.updateSeries([{
                            name: 'Total',
                            data: total_2
                        }]);

                    }


                },
                error: function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {

                        error(
                            'O servidor demorou para responder, os dados do monitoramento não foram atualizados.'
                        );
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
                timeout: 10000, //10 second timeout
                data: {
                    type_line: $("#type_line").val()
                },
                success: function(response) {

                    // Block 1
                    analyticsBarChart.updateSeries([{
                        name: '<?= date('y') ?>',
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

                    donustChartStatistics.updateOptions({
                        xaxis: {
                            categories: name_0
                        }
                    });

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

                    typeStatistics.updateOptions({
                        xaxis: {
                            categories: name_1
                        }
                    });

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

                    originStatistics.updateOptions({
                        xaxis: {
                            categories: name_2
                        }
                    });

                    originStatistics.updateSeries([{
                        name: 'Total',
                        data: total_2
                    }]);

                },
                error: function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {

                        error(
                            'O servidor demorou para responder, os dados do monitoramento não foram atualizados.'
                        );
                    } else {

                        error('Aconteceu um erro inesperado, os dados do monitoramento não foram atualizados.');
                    }
                }
            });
        }

        $("#type_line").change(function() {
            var type = $(this).val();
            if (type == 1) {
                typeLine = "residential";
            } else if (type == 2) {
                typeLine = "commercial";
            } else {
                typeLine = "";
            }
            loadData();
        });
    </script>
@endsection
