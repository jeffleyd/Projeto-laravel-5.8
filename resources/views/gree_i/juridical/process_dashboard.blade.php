@extends('gree_i.layout')

@section('content')

<style>
  body {
    color: #475f7b;
  }
  #chart1 {
    width: 300px;
    margin: 0 auto;
  }    
  .div-cost-process:hover {
    background-color: #dfe3e7;
    cursor: pointer;
  }
  .number-style{
    font-size: '40px';
    font-family: 'Rubik';
    color: '#475f7b'
  }
</style>

<div class="content-overlay"></div>
  <div class="content-wrapper">
      <div class="content-header row">
          <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
              <div class="col-6 col-sm-12 col-lg-6">
                <h5 class="content-header-title float-left pr-1 mb-0">Jurídico</h5>
                <div class="breadcrumb-wrapper col-12">
                  Central de monitoramento
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="content-header row"></div>
      <div class="content-body">
          <section id="dashboard-analytics"> 
              <div class="row">
                  <div class="col-md-12">
                      <h6 class="card-title">Totais de processos - <?= date('Y') ?></h6>
                  </div>
                  <div class="col-md-3">
                      <div class="card">
                          <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Processo tramitando. Curso comum">
                              <div class="card-body donut-chart-wrapper">
                                  <div class="d-flex justify-content-center">
                                    <span style="font-size: 40px;font-family:'Rubik';">{{ $total_progress }}</span>
                                  </div>
                                  <ul class="list-inline d-flex justify-content-around mb-0">
                                    <li><span class="bullet bullet-xs bullet-warning mr-50"></span>Andamentos</li>
                                  </ul>
                              </div>
                          </div>    
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="card">
                          <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Aguardando decisão judicial para continuidade">
                              <div class="card-body donut-chart-wrapper">
                                  <div class="d-flex justify-content-center">
                                    <span style="font-size: 40px;font-family:'Rubik';">{{ $total_suspended }}</span>
                                  </div>
                                  <ul class="list-inline d-flex justify-content-around mb-0">
                                      <li><span class="bullet bullet-xs bullet-primary mr-50"></span>Suspensos</li>
                                  </ul>
                              </div>
                          </div>    
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="card">
                          <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Arquivado definitivamente. Processo finalizado">
                              <div class="card-body donut-chart-wrapper">
                                  <div class="d-flex justify-content-center">
                                    <span style="font-size: 40px;font-family:'Rubik';">{{ $total_closed }}</span>
                                  </div>
                                  <ul class="list-inline d-flex justify-content-around mb-0">
                                    <li><span class="bullet bullet-xs bullet-danger mr-50"></span>Encerrados</li>
                                  </ul>
                              </div>
                          </div>    
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="card">
                          <div class="card-content" data-toggle="tooltip" data-placement="bottom" data-original-title="Transitou em julgado, porém ainda não houve quitação. Fase para quitar o que foi decidido">
                              <div class="card-body donut-chart-wrapper">
                                  <div class="d-flex justify-content-center">
                                    <span style="font-size: 40px;font-family:'Rubik';">{{ $total_sentence }}</span>
                                  </div>
                                  <ul class="list-inline d-flex justify-content-around mb-0">
                                    <li><span class="bullet bullet-xs bullet-success mr-50"></span>Cumprimento de Sentença</li>
                                  </ul>
                              </div>
                          </div>    
                      </div>
                  </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                    <div class="card text-center">
                      <a href="/juridical/process/cost/list">
                        <div class="card-content div-cost-process">
                          <div class="card-body py-1">
                              <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                  <i class="bx bx-file font-medium-5"></i>
                              </div>
                              <div class="text-muted line-ellipsis">Custos de processos</div>
                              <h3 class="mb-0">R$ <?= number_format($process_cost, 2,",",".") ?></h3>
                          </div>
                        </div>
                      </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center">
                      <a href="/juridical/law/cost/list">
                        <div class="card-content div-cost-process">
                          <div class="card-body py-1">
                              <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                              <i class="bx bx-buildings font-medium-5"></i>
                              </div>
                              <div class="text-muted line-ellipsis">Custos de escritórios</div>
                              <h3 class="mb-0">R$ <?= number_format($law_cost, 2,",",".") ?></h3>
                          </div>
                        </div>
                      </a>  
                    </div>    
                </div>
              </div>   
              <div class="row">
                <div class="col-lg-6 col-md-6">
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                          <h4 class="card-title">Processo por seara</h4>
                          <div class="heading-elements">
                            <button type="button" onclick="viewFilter(1)" class="btn btn-outline-primary mr-1 mb-1">Filtrar</button>
                          </div>
                      </div>
                      <div class="card-content">
                          <div class="card-body">
                              <div class="pb-1 pt-3 d-flex justify-content-center" id="chart-seara"></div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                            <h4 class="card-title">Processos por escritório</h4>
                            <div class="heading-elements">
                              <button type="button" onclick="viewFilter(2)" class="btn btn-outline-primary mr-1 mb-1">Filtrar</button>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="pb-1 pt-3 d-flex justify-content-center" id="chart_law_process"></div>
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
                    @foreach($range_year as $year)
                      <option value="{{$year}}" @if($year == date('Y')) selected @endif>{{$year}}</option>
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
  var typeStatistics;
  var law_process_config;
  var arr_type_process = {!! json_encode($type_process) !!}
  var arr_law_process = {!! json_encode($law_process) !!}
  var name_0,name_1, total_0, total_1 = [];
  var itsBlock = null;

  var type_process = [],
      type_process_name = [], 
      law_process = [], 
      law_process_name = [];
    
  for (var i = 0; i < arr_type_process.length; i++) {
    type_process[i] = arr_type_process[i].total;
    type_process_name[i] = arr_type_process[i].name;
  }
  
  for (var i = 0; i < arr_law_process.length; i++) {
    law_process[i] = arr_law_process[i].total;
    law_process_name[i] = arr_law_process[i].name;
  }

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

    // ------------------- Chart type process--------------------------
    typeStatistics = {
      series: [{
        name: 'Total',
        data: type_process
      }],
      chart: {
        type: 'bar',
        height: 350,
        events: {
          dataPointSelection: function(event, chartContext, config) {

            if (config.dataPointIndex == 0)
              window.open('/juridical/process/list?type_process=1', '_blank');
            else if (config.dataPointIndex == 1)
              window.open('/juridical/process/list?type_process=2', '_blank');
            else if (config.dataPointIndex == 2)
              window.open('/juridical/process/list?type_process=3', '_blank');
            else if (config.dataPointIndex == 3)
              window.open('/juridical/process/list?type_process=4', '_blank');
            else if (config.dataPointIndex == 4)
              window.open('/juridical/process/list?type_process=5', '_blank');
            else if (config.dataPointIndex == 5)
            window.open('/juridical/process/list?type_process=6', '_blank');
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
        categories: type_process_name
      }
    };
    typeStatistics = new ApexCharts(
        document.querySelector("#chart-seara"),
        typeStatistics
    );
    typeStatistics.render();

    // ------------------- Chart law process--------------------------
    law_process_config = {
      series: [{
        name: 'Total',
        data: law_process
      }],
      chart: {
        type: 'bar',
        height: 350,
        events: {
          dataPointSelection: function(event, chartContext, config) {
            for (var i = 0; i < arr_law_process.length; i++) {
              if (config.dataPointIndex == i) {
                window.open('/juridical/process/list?law_firm_id='+arr_law_process[i].id+'', '_blank');  
              }
            }
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
        categories: law_process_name
      }
    };

    law_process_config = new ApexCharts(
      document.querySelector("#chart_law_process"),
      law_process_config
    );
    law_process_config.render();
  });

  function viewFilter(block) {
    itsBlock = block;
    $("#filterMonitor").modal();
  }
        
  function loadinfo() {
    $("#filterMonitor").modal('toggle');
    $.ajax({
      type: "GET",
      url: '/juridical/process/monitor/filter/ajax',
      timeout:10000,
      data: {
        year: $("#year").val(), 
        month: $("#month").val(), 
        block: itsBlock
      },
      success: function(response){
        if (itsBlock == 1) {
          name_0 = [];
          total_0 = [];

          for (let i = 0; i < response.type_process.length; i++) {
            const obj = response.type_process[i];
            name_0.push(obj.name);
            total_0.push(obj.total);
          }

          typeStatistics.updateOptions({ xaxis: { categories: name_0 } });
          typeStatistics.updateSeries([{
            name: 'Atendimentos',
            data: total_0
          }]);

        } else if (itsBlock == 2) {
          name_1 = [];
          total_1 = [];

          for (let i = 0; i < response.law_process.length; i++) {
            const obj = response.law_process[i];
            name_1.push(obj.name);
            total_1.push(obj.total);
          }
          law_process_config.updateOptions({xaxis: { categories: name_1 }});
          law_process_config.updateSeries([{
            name: 'Total',
            data: total_1
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
</script>
@endsection