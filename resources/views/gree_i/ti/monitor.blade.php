@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">TI</h5>
                  <div class="breadcrumb-wrapper col-12">
                    Central de monitoramento
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

                  <div class="col-sm-4">
                    <div class="card">
                      <div class="card-content">
                        <div class="card-body p-0">
                          <div class="d-lg-flex justify-content-between">
                            <div class="widget-card-details d-flex flex-column justify-content-between p-2">
                              <div>
                                <h5 class="font-medium-2 font-weight-normal">Memória</h5>
                                <p class="text-muted">Representa o uso constante da memória.</p>
                              </div>
                            </div>
                            <div id="radial-chart-primary"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="card">
                      <div class="card-content">
                        <div class="card-body p-0">
                          <div class="d-lg-flex justify-content-between">
                            <div class="widget-card-details d-flex flex-column justify-content-between p-2">
                              <div>
                                <h5 class="font-medium-2 font-weight-normal">Processador</h5>
                                <p class="text-muted">Mostra o pico de processamento do servidor.</p>
                              </div>
                            </div>
                            <div id="radial-chart-success"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="card">
                      <div class="card-content">
                        <div class="card-body p-0">
                          <div class="d-lg-flex justify-content-between">
                            <div class="widget-card-details d-flex flex-column justify-content-between p-2">
                              <div>
                                <h5 class="font-medium-2 font-weight-normal">Tarefas realizadas</h5>
                                <p class="text-muted">Mostra uma porcentagem do total de tarefas feitas.</p>
                              </div>
                            </div>
                            <div id="radial-chart-danger"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <iframe id="iframe" src="https://gree-app.com.br/ti/developer/logs" frameborder="0" style="width: 1500px;height: 440px;"></iframe>
                  </div>

                </div>
            </section>
        </div>
    </div>

    <script>
      var radialPrimaryChart, radialSuccessChart, radialDangerChart;
    $(window).on("load", function () {
        var $primary = '#5A8DEE';
        var $success = '#FF5B5C';
        var $danger = '#39DA8A';
        var $warning = '#FDAC41';
        var $info = '#00CFDD';
        var $label_color = '#304156';
        var $danger_light = '#FFDEDE';
        var $gray_light = '#828D99';
        var $bg_light = "#f2f4f4";

        // Radial Followers Chart - Primary
        // --------------------------------
        var radialPrimaryoptions = {
          chart: {
            height: 250,
            type: "radialBar"
          },
          series: [0],
          plotOptions: {
            radialBar: {
              offsetY: -10,
              size: 70,
              hollow: {
                size: "70%"
              },
              dataLabels: {
                showOn: "always",
                name: {
                  show: false
                },
                value: {
                  colors: [$label_color],
                  fontSize: "20px",
                  show: true,
                  offsetY: 8,
                  fontFamily: "Rubik"
                }
              }
            }
          },
          stroke: {
            lineCap: "round",
          }
        };
        radialPrimaryChart = new ApexCharts(
          document.querySelector("#radial-chart-primary"),
          radialPrimaryoptions
        );

        radialPrimaryChart.render();


        // Radial Users Chart - Success
        // ----------------------------
        var radialSuccessoptions = {
          chart: {
            height: 250,
            type: "radialBar"
          },
          series: [0],
          colors: [$success],
          plotOptions: {
            radialBar: {
              offsetY: -10,
              size: 70,
              hollow: {
                size: "70%"
              },

              dataLabels: {
                showOn: "always",
                name: {
                  show: false
                },
                value: {
                  colors: [$label_color],
                  fontSize: "20px",
                  show: true,
                  offsetY: 8,
                  fontFamily: "Rubik"
                }
              }
            }
          },
          stroke: {
            lineCap: "round",
          }
        };
        radialSuccessChart = new ApexCharts(
          document.querySelector("#radial-chart-success"),
          radialSuccessoptions
        );

        radialSuccessChart.render();


        // Radial Registrations Chart - Danger
        // -----------------------------------
        var radialDangeroptions = {
          chart: {
            height: 250,
            type: "radialBar"
          },
          series: [<?= $pct_done ?>],
          colors: [$danger],
          plotOptions: {
            radialBar: {
              offsetY: -10,
              size: 70,
              hollow: {
                size: "70%"
              },

              dataLabels: {
                showOn: "always",
                name: {
                  show: false
                },
                value: {
                  colors: [$label_color],
                  fontSize: "20px",
                  show: true,
                  offsetY: 8,
                  fontFamily: "Rubik"
                }
              }
            }
          },
          stroke: {
            lineCap: "round",
          }
        };
        radialDangerChart = new ApexCharts(
          document.querySelector("#radial-chart-danger"),
          radialDangeroptions
        );
        radialDangerChart.render();



        setInterval(() => {
            $("#mTI").addClass('sidebar-group-active active');
            $("#mTIDeveloper").addClass('sidebar-group-active active');
            $("#mTIDeveloperMonitor").addClass('active');
        }, 100);
        
    });

    $(document).ready(function () {
      socket.on('cpu usage', function(data){
        if (radialSuccessChart)
        radialSuccessChart.updateSeries([data.cpu]);
      });
      socket.on('memory usage', function(data){
        if (radialPrimaryChart)
        radialPrimaryChart.updateSeries([data.memomy]);
      });
      setInterval(() => {
        var iframe = document.getElementById('iframe');
        iframe.src = iframe.src;
      }, 300000);
    });
    </script>
@endsection