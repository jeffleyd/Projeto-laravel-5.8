@extends('gree_i.layout')

<link href='/css/reservationMainFullCalendar.css' rel='stylesheet' type="text/css" />

<style>
    #calendar-container {
        top: 235;
        left: 0;
        right: 0;
        bottom: 0;
        border: solid;
    }

    .fc-header-toolbar {
        padding-top: 1em;
        padding-left: 1em;
        padding-right: 1em;
    }

    .adjustMainCalendar {
        padding-left: 35px;
        padding-right: 35px;
        text-transform: uppercase;
        margin-bottom: 200px;
    }

    .fc-list-event-title {
        cursor: pointer;
        color: black;
    }

    .btnRemove {
        justify-content: center;
    }

    .selectReason {
        text-transform: uppercase;
    }

    .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active {
        color: #fff;
        background-color: #0062cc;
        border-color: #005cbf;
    }

    .fc .fc-button-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

   
</style>

@section('content')
    <!-- BEGIN: Cabecalho -->
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-6 col-sm-12 col-lg-6">
                        <h5 class="content-header-title float-left pr-1 mb-0">Logística solicitações </h5>
                        <ul class="list-inline mb-0">
                            <li class="mr-50"> <i class="bullet bullet-xs bullet-info mr-50" style="background-color:#007bff;"></i>Entrada </li>
                            <li> <i class="bullet bullet-xs bullet-success mr-50" style="background-color:#ffff00;"></i>Saída </li>
                        </ul>
                    </div>
                    <div class="col-4 col-sm-12 col-lg-4">
                        <fieldset class="form-group float-right" style="margin-bottom: 0rem; margin-right: -50px; width: 100%;">
                            <select class="custom-select select-request" name="type_reason" id="type_reason" style="width: 100%;" multiple>
                                <option value="1">Entrega de compra</option>
                                <option value="2">Carregamento</option>
                                <option value="4">Importação</option>
                                <option value="5">Transferência</option>
                                <option value="6">Retirada de venda</option>
                                <option value="7">Coleta</option>
                                <option value="8">Entrega de avaria</option>
                                <option value="9">Manobra</option>
                                <option value="11">Outros</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-2 col-sm-12 col-lg-2">
                        <fieldset class="form-group float-right" style="margin-bottom: 0rem;">
                            <select class="form-control" id="gate" name="gate" style="position: relative; border-color: #3568df;color: #3568df;">
                                <option value="0">Todos</option>
                                @foreach ($entry_exit_gate as $key)
                                    <option value="{{ $key->id }}">{{ $key->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header row">
        </div>
    </div>
   
    <section>
        <div class="container-lg adjustMainCalendar">
            <div id='calendar-container' style="box-shadow: -8px 12px 18px 0 rgba(80, 90, 92, 0.13);">
                <form action="">
                    <div id='calendar'></div>
                </form>
            </div>
        </div>
    </section>

    <script src='/js/reservationMainFullCalendar.js'></script>    
    <script>

        $(document).ready(function() {
            $(".select-request").select2({
                placeholder: "Selecione a razão"
            });

            $('.select-request').on('select2:select', function (e) {
                reloadCalendar();
            }); 

            $('.select-request').on('select2:close', function (e) {
                reloadCalendar();
            });    
        });    

        function reloadCalendar() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'pt-br',
                dayMaxEventRows: true,
                moreLinkContent:function(args){
                    return '+'+args.num+' solicitações(s)';
                },
                dayHeaderContent: (args) => {
                    return nameWeek(args.dow);
                },
                buttonText: {today: "Hoje", month: "Mês", week: "Semana", day: "Dia", list: 'Lista'},
                allDayText: 'Horário', 
                height: '100%',
                expandRows: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
                },
                initialView: 'dayGridMonth',
                initialDate: functionCurrentDate(),
                navLinks: true,
                editable: true,
                selectable: true,
                eventSources: [{
                    url: '/logistics/request/visitor/cargo/monitor/ajax?gate='+$("#gate").val()+'&reason='+$('.select-request').val(),
                    method: 'GET',
                    failure: function() {
                        $error(
                            'Não foi possível pegar os eventos do calendário, verifique sua conexão com a internet.'
                        );
                    }
                }],
                eventClick: function(info) {
                    var code = info.event._def.extendedProps.code;
                    window.open('/logistics/request/cargo/transport/list?code='+code+'', '_blank');
                }
            });
            calendar.render();
        }

        document.addEventListener('DOMContentLoaded', function() {
            reloadCalendar();
        });

        function nameWeek(day) {
            return ["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado",][day];
        }

        function functionCurrentDate() {
            var dateCurrent = new Date();
            var day = dateCurrent.getDate();
            var month = dateCurrent.getMonth() + 1;
            var year = dateCurrent.getFullYear();
            var dateTimeCurrent = year + '-' + month + '-' + day;
            return Date.parse(dateTimeCurrent);
        }

        $('#gate').on('change',function(){
            reloadCalendar();
        });
   
        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExit").addClass('sidebar-group-active active');
            $("#mLogisticsMonitorScheduler").addClass('active');
        }, 100);
    </script>

@endsection
