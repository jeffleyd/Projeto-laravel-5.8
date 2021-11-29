@extends('gree_i.layout')
{{-- <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/pages/page-knowledge-base.min.css"> --}}
<link href='/css/reservationMainFullCalendar.css' rel='stylesheet' type="text/css" />
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">

<style>
    html,
    body {
        /*  overflow: hidden; */
        /* don't do scrollbars */
        /* font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 14px; */
    }

    #calendar-container {
        top: 235;
        left: 0;
        right: 0;
        bottom: 0;
        border: solid;
    }

    .fc-header-toolbar {
        /*
    the calendar will be butting up against the edges,
    but let's scoot in the header's buttons
    */
        padding-top: 1em;
        padding-left: 1em;
        padding-right: 1em;
    }

    .adjustMainCalendar {
        padding-left: 60px;
        padding-right: 60px;
        text-transform: uppercase;

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

</style>

@section('content')
    <!-- BEGIN: Cabecalho -->
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-9">
                        <h5 class="content-header-title float-left pr-1 mb-0">Quadro de Horário</h5>
                        <div class="breadcrumb-wrapper col-12">
                            Reservar Sala de Reunião
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div style="text-align: center" class="breadcrumb-wrapper col-12">
                    Para reservar sala de reuinão clique em uma data vigente do mês.
                </div>
            </div>
        </div>
        <div class="content-header row">
        </div>
    </div>
    <!-- END: Cabecalho -->

    <!-- BEGIN: FullCalendar - calendario de agendamento -->
    <section>
        <div class="container-lg adjustMainCalendar">
            <div id='calendar-container' style="box-shadow: -8px 12px 18px 0 rgba(80, 90, 92, 0.13);">
                <form action="">
                    <div id='calendar'></div>
                </form>
            </div>
        </div>
    </section>
    <!-- END: FullCalendar - calendario de agendamento -->

    <!-- BEGIN: Modal para inclusao de reserva de sala de reuniao.-->
    <section>
        <div class="modal " id="reservationModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h5 class="modal-title ">Reservar Sala de Reunião</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/administration/reservation/meetroom/insert" id="reservationMeet" method="post">
                            <input id="validate_r_code" name="validate_r_code" type="hidden" value="">
                            <input id="reservation_id" name="reservation_id" type="hidden" value="0">
                            <input id="responsible_user_id" name="responsible_user_id" type="hidden" value="">
                            <input id="sector_id" name="sector_id" type="hidden" value="">

                            <div class="content-header row">
                                <div class="content-body">

                                    <div class="card-content">

                                        <div class="row">

                                            <div class="col-md-12">
                                                <h6 style="text-transform: uppercase">Responsável pela Reunião</h6>
                                                <div class="row align-items-center">
                                                    <div class="form-group col-3">
                                                        <div>
                                                            <label for="responsibleRCode">Matrícula</label>
                                                            <input type="text" onblur="validaRCode(this)"
                                                                class="form-control" value="" id="responsibleRCode"
                                                                name="responsibleRCode">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-5">
                                                        <div>
                                                            <label for="responsibleName">Nome</label>
                                                            <input type="text" readonly class="form-control" value=""
                                                                id="responsibleName" name="responsibleName">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-4">
                                                        <div>
                                                            <label for="responsibleSector">Setor</label>
                                                            <input type="text" readonly class="form-control" value=""
                                                                id="responsibleSector" name="responsibleSector">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--BEGIN: Selecionar Sala: 1. Sala 1;  2.Sala 2 ...-->
                                            <div class="col-md-12">
                                                <h6 style="text-transform: uppercase">Sala </h6>
                                                <fieldset class="form-group">
                                                    <select class="form-control"id="selectMeetRoom"name="selectMeetRoom">                                                        
                                                        <option value="1">SHOWROOM</option>
                                                        <option value="2">SALA DE TREINAMENTO G2</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <!--END: Selecionar Sala: 1. Sala 1;  2.Sala 2 ...-->

                                            <!--BEGIN: Motivo da reserva de sala: 1. Reunião, 2. Outros-->
                                            <div class="col-md-12">
                                                <h6 style="text-transform: uppercase">Motivo </h6>
                                                <fieldset class="form-group" name="fieldsetMotivoMeeting">
                                                    <select class="form-control" id="selectReason" name="selectReason">
                                                        <option value="1">OUTROS</option>
                                                        <option value="2">ELETROS</option>
                                                        <option value="3">REUNIAO PERIODICA</option>
                                                        <option value="4">REUNIAO COMERCIAL</option>
                                                        <option value="5">TREINAMENTO</option>
                                                        <option value="6">ATLANTIC</option>
                                                        <option value="7">CIPA</option>
                                                        <option value="8">SELECAO</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <!--END: Motivo da reserva de sala: 1. Reunião, 2. Outros-->

                                            <h6 class="col-12" style="text-transform: uppercase">Descrição /
                                                Observação</h6>
                                            <div class="col-12">
                                                <fieldset class="form-group">
                                                    <textarea value="" class="form-control" id="textareaDescription"
                                                        name="textareaDescription" rows="3"
                                                        placeholder="Descrição"></textarea>
                                                </fieldset>
                                            </div>

                                            <!-- BEGIN: Data -->
                                            <div class="container-md col-12">
                                                <h6 style="text-transform: uppercase">Data</h6>
                                                <h6 id="alertErroData" style="color: red; display:none;">Data
                                                    retroativa
                                                    ou maior que 7 dias da vigente.</h6>
                                                <div class="row align-items-center">
                                                    <fieldset class="form-group col">
                                                        <input type="text" readonly="readonly" class="form-control"
                                                            id="dateSelected" name="dateSelected">
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <!-- END: Date-->


                                            <!-- BEGIN: Selecionar Horário -->
                                            <div class="col-md-12">
                                                <h6 style="text-transform: uppercase">Horário</h6>
                                                <div class="row align-items-center">

                                                    <fieldset class="form-group col-6">
                                                        <label for="selectStartHour">Início</label>
                                                        <select class="form-control" id="selectStartHour"
                                                            name="selectStartHour" required>
                                                            <option></option>
                                                            <option value="07:00">07:00</option>
                                                            <option value="08:00">08:00</option>
                                                            <option value="09:00">09:00</option>
                                                            <option value="10:00">10:00</option>
                                                            <option value="11:00">11:00</option>
                                                            <option value="12:00">12:00</option>
                                                            <option value="13:00">13:00</option>
                                                            <option value="14:00">14:00</option>
                                                            <option value="15:00">15:00</option>
                                                            <option value="16:00">16:00</option>
                                                            <option value="17:00">17:00</option>
                                                            <option value="18:00">18:00</option>
                                                            <option value="19:00">19:00</option>
                                                        </select>
                                                    </fieldset>

                                                    <fieldset class="form-group col-6">
                                                        <label for="selectEndHour">Fim</label>
                                                        <select class="form-control" id="selectEndHour"
                                                            name="selectEndHour" required>
                                                            <option></option>
                                                            <option value="07:59">08:00</option>
                                                            <option value="08:59">09:00</option>
                                                            <option value="09:59">10:00</option>
                                                            <option value="10:59">11:00</option>
                                                            <option value="11:59">12:00</option>
                                                            <option value="12:59">13:00</option>
                                                            <option value="13:59">14:00</option>
                                                            <option value="14:59">15:00</option>
                                                            <option value="15:59">16:00</option>
                                                            <option value="16:59">17:00</option>
                                                            <option value="17:59">18:00</option>
                                                            <option value="18:59">19:00</option>
                                                            <option value="19:59">20:00</option>
                                                        </select>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <!-- END: Selecionar Horário -->
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer container btnRemove" style="justify-content: center;">
                                <button id="removeReservationMeet" onclick="confirmationBtnRemove(this)" type="button"
                                    class="actionBtn btn btn-danger row justify-content-start">Remover</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button id="sendReservationMeet" class="actionBtn btn btn-primary"
                                    type="submit">Reservar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Modal para inclusao de reserva de sala de reuniao.-->


    <!-- BEGIN: Modal para cadastrar motivo de reserva de sala de reuniao.-->
    <section>
        <div class="modal " id="insertReasonModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h5 class="modal-title">Motivo de Reserva de Sala de Reunião</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/administration/reservation/meetroom/reason/insert" id="reasonInsert" method="post">
                            <div class="content-header row">
                                <div class="content-body">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row align-items-center">
                                                            <div class="form-group col-12">
                                                                <div>
                                                                    <h6 style="text-transform: uppercase">Motivo</h6>
                                                                    <input type="text" class="form-control" value=""
                                                                        id="reservationReason" name="reservationReason">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h6 class="col-12" style="text-transform: uppercase">Descrição /
                                                        Observação</h6>
                                                    <div class="col-12">
                                                        <fieldset class="form-group">
                                                            <textarea value="" class="form-control"
                                                                id="textAreaDescriptionReason"
                                                                name="textAreaDescriptionReason" rows="3"
                                                                placeholder="Descrição"></textarea>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer container" style="justify-content: center;">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button id="btnInsertReservationReason"
                                    class="actionBtninsertReservationReason btn btn-primary" type="submit">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Modal para cadastrar motivo de reserva de sala de reuniao.-->

    <!-- BEGIN: Modal para orirentaçao de uso de sala de reunião. Abre assim que carrega o quadro de horário-->
    <div class="modal fade text-left" id="modalOrientation" tabindex="-1" aria-labelledby="myModalLabel160"style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered role=" document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Termos & Condições</h5>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-12">
                        <h4 class="card-title">Comunicado</h4>
                        <h6 class="card-subtitle">Siga as orientações para o uso da sala de reunião.</h6>
                        <div id="carousel-example-card" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-example-card" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example-card" data-slide-to="1"></li>
                                <li data-target="#carousel-example-card" data-slide-to="2"></li>
                                <li data-target="#carousel-example-card" data-slide-to="3"></li>
                                <li data-target="#carousel-example-card" data-slide-to="4"></li>
                                <li data-target="#carousel-example-card" data-slide-to="5"></li>
                            </ol>
                            <div class="carousel-inner rounded-0" role="listbox">
                                <div class="carousel-item active">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="/media/sentarCorretoIncorreto.jpg"
                                                class="rounded mx-auto d-block w-75" style="height: 162px"
                                                alt="First slide">
                                        </div>
                                        <p class="card-text" style="text-align: justify" >
                                            [1]. Sentando de maneira inadequada na cadeira. Irá ocasionando um forçamento
                                            na estrutura, podendo sofrer um acidente.
                                        </p>
                                        <p class="card-text" style="text-align: justify" >
                                            Sentando de forma correta, não irá forçar a estrutura da cadeira, assim
                                            evitará um possivel acidente.
                                        </p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="/media/cadeiras.jpg"
                                                class="rounded mx-auto d-block w-75" style="height: 162px"
                                                alt="Second slide">
                                        </div>
                                        <p class="card-text" style="text-align: justify" >
                                            [2]. Cadeiras desarrumadas após o término de reunião, entrevistas e visitas.
                                        </p>
                                        <p class="card-text" style="text-align: justify" >
                                            Arrumar as cadeiras após o término de reunião, entrevista e visita.
                                            Assim manterá o ambiente arrumado para a proxima reunião.
                                        </p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="/media/equipamentosLigados.jpg"
                                                class="rounded mx-auto d-block w-75" style="height: 162px"
                                                alt="Third slide">
                                        </div>
                                        <p class="card-text" style="text-align: justify" >
                                            [3]. Equipamentos ligados após o término da reunião.
                                        </p>
                                        <p class="card-text" style="text-align: justify" >
                                            Desligar todos os equipamento após o término da reunião.
                                        </p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="/media/transitando.jpg"
                                                class="rounded mx-auto d-block w-75" style="height: 162px"
                                                alt="Fourth slide">
                                        </div>
                                        <p class="card-text" style="text-align: justify" >
                                            [4]. Transitando no caminho determinado de forma inadequada pegando atalhos
                                            pisando no carpete original.
                                        </p>
                                        <p class="card-text" style="text-align: justify" >
                                            Transitar no caminho determinado de forma correta sem pegar atalhos,
                                            evitando pisar no carpete original.
                                        </p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="/media/transitando1.jpg"
                                                class="rounded mx-auto d-block w-75" style="height: 162px"
                                                alt="Fifth slide">
                                        </div>

                                        <p class="card-text" style="text-align: justify" >
                                            [5]. Transitando no caminho determinado de forma inadequada pisando no carpete
                                            original.
                                        </p>
                                        <p class="card-text" style="text-align: justify" >
                                            Transitar no caminho determinado de forma correta sem pisar no carpete
                                            original.
                                        </p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="/media/sentarCorretoIncorreto2.jpg"
                                                class="rounded mx-auto d-block w-75" style="height: 162px"
                                                alt="Third slide">
                                        </div>
                                        <p class="card-text" style="text-align: justify" >
                                            [6]. Sentando de forma inadequada e encostando o calcanhar na estrutura, Dessa
                                            forma a base do sofá fica toda suja.
                                        </p>
                                        <p class="card-text" style="text-align: justify" >
                                            Sentando de forma correta sem encostar o calcanhar, Evita que a base do sofá
                                            fique suja.
                                        </p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="/media/maosnosaparelhos.jpg"
                                                class="rounded mx-auto d-block w-75" style="height: 162px"
                                                alt="Third slide">
                                        </div>

                                        <p class="card-text" style="text-align: justify" >
                                            [7]. Sentando de forma inadequada e encostando o calcanhar na estrutura, Dessa
                                            forma a base do sofá fica toda suja.
                                        </p>
                                        <p class="card-text" style="text-align: justify" >
                                            Sentando de forma correta sem encostar o calcanhar, Evita que a base do sofá
                                            fique suja.
                                        </p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="/media/inadequado.jpg"
                                                class="rounded mx-auto d-block w-75" style="height: 162px"
                                                alt="Third slide">
                                        </div>

                                        <p class="card-text" style="text-align: justify" >
                                            [8]. Sentando de forma inadequada e encostando o calcanhar na estrutura, Dessa
                                            forma a base do sofá fica toda suja.
                                        </p>
                                        <p class="card-text" style="text-align: justify" >
                                            Sentando de forma correta sem encostar o calcanhar, Evita que a base do sofá
                                            fique suja.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <a class="carousel-control-prev" href="#carousel-example-card" role="button" data-slide="prev">
                            <span class="bx bx-chevron-left icon-prev"
                                style="font-size: 40.2px;border: groove;color: cornflowerblue;" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-example-card" role="button" data-slide="next">
                            <span class="bx bx-chevron-right icon-next "
                                style="font-size: 40.2px;border: groove;color: cornflowerblue;" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnModalOrientation" type="button" class="btn btn-primary ml-1" data-dismiss="modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block " style="text-transform: uppercase"> estou de acordo</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Modal para cadastrar motivo de reserva de sala de reuniao.-->



    <script src='/js/reservationMainFullCalendar.js'></script>
    <!-- Begin: Calendar -->
    <script>
        var arr_reservation = [];

        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl = document.getElementById('calendar');

            function functionCurrentDate() {
                var dateCurrent = new Date();
                var dia = dateCurrent.getDate();
                var mes = dateCurrent.getMonth() + 1;
                var ano = dateCurrent.getFullYear();
                var dateTimeCurrent = ano + '-' + mes + '-' + dia;

                return Date.parse(dateTimeCurrent);
            }

            function nomeDaSemana(numSemana) {
                var arrayDia = new Array(7);
                arrayDia[0] = "Domingo";
                arrayDia[1] = "Segunda";
                arrayDia[2] = "Terça";
                arrayDia[3] = "Quarta";
                arrayDia[4] = "Quinta";
                arrayDia[5] = "Sexta";
                arrayDia[6] = "Sábado";
                return arrayDia[numSemana];
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'pt-br',
                dayMaxEventRows: true,
                moreLinkContent: function(args) {
                    return '+' + args.num + ' Agendamento(s)';
                },
                // altera o cabeçalho da coluna do dia da semana 
                dayHeaderContent: (args) => {
                    return nomeDaSemana(args.dow);
                },

                buttonText: {
                    today: "Hoje",
                    month: "Mês",
                    week: "Semana",
                    day: "Dia",
                    list: 'Lista'
                },
                allDayText: 'Dia Todo',
                height: '100%',
                expandRows: true,
                slotMinTime: '08:00',
                slotMaxTime: '20:00',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
                },
                //A name of any of the available views, such as 'dayGridWeek', 'timeGridDay', 'listWeek', listMonth .            
                initialView: 'dayGridMonth',
                initialDate: functionCurrentDate(),
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                selectable: true,
                nowIndicator: true,
                eventSources: [{
                    url: '/getReservationMeetRoom',
                    method: 'GET',
                    failure: function() {
                        $error(
                            'Não foi possível pegar os eventos do calendário, verifique sua conexão com a internet.'
                        );
                    },
                    color: 'orange', // a non-ajax option
                    textColor: 'black' // a non-ajax option
                }],
                eventClick: function(event) {
                        
                    if (moment(event.event._def.extendedProps.dateSelected).format(
                            'YYYY-MM-DD') >=
                        '{{ date('Y-m-d') }}' && event.event._def.extendedProps.r_code ==
                        '{{ Session::get('r_code') }}') {

                        $('input').each(function() {
                            $(this).removeAttr('readonly', 'readonly')
                        });

                        $('select').each(function() {
                            $(this).removeAttr('readonly', 'readonly')
                        });

                        $('textarea').each(function() {
                            $(this).removeAttr('readonly', 'readonly')
                        });

                        $('#responsibleName, #responsibleSector, #dateSelected').attr(
                            'readonly',
                            'readonly');


                        $("#reservationMeet").attr("action",
                            "/administration/reservation/meetroom/edit");
                        $("#sendReservationMeet").html("Atualizar");
                        $(".modal-title").html("Atualizando reserva");
                        $("#reservation_id").val(event.event._def.extendedProps.reservation_id);
                        $("#responsible_user_id").val(event.event._def.extendedProps
                            .responsible_user_id);
                        $("#sector_id").val(event.event._def.extendedProps.sector_id);
                        $("#selectMeetRoom").val(event.event._def.extendedProps.meet_room_id);
                        
                        $("#responsibleRCode").val(event.event._def.extendedProps.r_code);
                        $("#responsibleName").val(event.event._def.extendedProps.first_name);
                        $("#responsibleSector").val(event.event._def.extendedProps.sector_name);
                        $("#textareaDescription").val(event.event._def.extendedProps
                            .description);

                        $("#selectReason").val(event.event._def.extendedProps.reason);
                        var dateSelected = (moment(event.event._def.extendedProps.dateSelected)
                            .format(
                                'YYYY-MM-DD'));
                        $("#dateSelected").val(dateSelected);

                        //var start = selectStartHour.getHours();                
                        $("#selectStartHour").val(event.event._def.extendedProps
                            .selectStartHour);
                        $("#selectEndHour").val(event.event._def.extendedProps.selectEndHour);

                        $("#reservationModal").modal('show');
                        $(".actionBtn").show();
                        //console.log(event.event._def.extendedProps.r_code);
                    } else {

                        // Apenas visualizacao
                        $("#selectMeetRoom").val(event.event._def.extendedProps.meet_room_id);
                        $("#reservationMeet").attr("action", "#");
                        $("#sendReservationMeet").html("Atualizar");
                        $(".modal-title").html("Visualizando reserva");
                        $("#reservation_id").val(event.event._def.extendedProps.reservation_id);
                        $("#responsible_user_id").val(event.event._def.extendedProps
                            .responsible_user_id);
                        $("#sector_id").val(event.event._def.extendedProps.sector_id);
                        $("#responsibleRCode").val(event.event._def.extendedProps.r_code);
                        $("#responsibleName").val(event.event._def.extendedProps.first_name);
                        $("#responsibleSector").val(event.event._def.extendedProps.sector_name);
                        $("#textareaDescription").val(event.event._def.extendedProps
                            .description);

                        $("#selectReason").val(event.event._def.extendedProps.reason);
                        var dateSelected = (moment(event.event._def.extendedProps.dateSelected)
                            .format(
                                'YYYY-MM-DD'));
                        $("#dateSelected").val(dateSelected);

                        //var start = selectStartHour.getHours();                
                        $("#selectStartHour").val(event.event._def.extendedProps
                            .selectStartHour);
                        $("#selectEndHour").val(event.event._def.extendedProps.selectEndHour);

                        $(".actionBtn").hide();
                        $('input').each(function() {
                            $(this).attr('readonly', 'readonly')
                        });

                        $('select').each(function() {
                            $(this).attr('readonly', 'readonly')
                        });

                        $('textarea').each(function() {
                            $(this).attr('readonly', 'readonly')
                        });

                        $("#reservationModal").modal('show');
                        //console.log(event.event._def.extendedProps.r_code);
                    }
                }
            });
            calendar.render();

            function functionClear() {
                $('#responsibleRCode').val('');
                $('#textareaDescription').val('');
                $('#selectStartHour').val('');
                $('#selectEndHour').val('');
                $('#responsibleName').val('');
                $('#responsibleSector').val('');
            }
            //Aciona tela de cadastro ao clicar na data do calendario
            //Evento para Inserir Reserva de Sala de Reunião
            calendar.on('dateClick', function(event) {
                console.log(event);
                var date = event.date;
                var start = moment(moment(date).format('YYYY-MM-DD'), "YYYY-MM-DD");
                var end = moment("{{ date('Y-m-d') }}", "YYYY-MM-DD");
                var totaDias = moment.duration(start.diff(end)).asDays();

                $('input').each(function() {
                    $(this).removeAttr('readonly', 'readonly')
                });

                $('select').each(function() {
                    $(this).removeAttr('readonly', 'readonly')
                });

                $('textarea').each(function() {
                    $(this).removeAttr('readonly', 'readonly')
                });
                $('#responsibleName, #responsibleSector').attr('readonly', 'readonly');
                $('#responsibleName, #responsibleSector, #dateSelected').attr(
                    'readonly', 'readonly');
                $("#reservationMeet").attr("action",
                    "/administration/reservation/meetroom/insert");
                $("#sendReservationMeet").html("Reservar");
                // $(".modal-title").html("Reservar Sala de Reunião");
                $("#reservation_id").val(0);
                $(".actionBtn").show();
                $("#removeReservationMeet").hide();

                functionClear();

                if ((totaDias < 0) || (totaDias > 7)) {
                    $error(
                        "Para reservar sala de reunião: clique em uma data atual ou em uma data menor que 7 dias da vigente."
                    );

                } else {
                    $('#alertErroData').hide();
                    $('#responsibleRCode').removeAttr('disabled');
                    $('#sendReservationMeet').removeAttr('disabled');
                    $('#dateSelected').val(moment(date).format('YYYY-MM-DD'));
                    $("#reservationModal").modal('show');
                    functionClear();                    
                }
            });

            var confirm_term = localStorage.getItem("lending_confirm_term");
          
            $("#modalOrientation").modal('show');

            $("#btnModalOrientation").click(function() {
                localStorage.setItem("lending_confirm_term", true);
                $("#modalOrientation").modal('hide');
            });
        });        
    </script>

    <!--Valida r_code se existe -->
    <script>
        function validaRCode($this) {
            var r_code = $($this).val();

            ajaxSend(
                '/administration/reservation/meetroom/validateRCode', {
                    r_code: r_code
                },
                'POST',
                '10000'
            ).then(function(result) {
                $("#responsible_user_id").val(result.responsible_user_id);
                $("#responsibleName").val(result.responsibleName);
                $("#r_code").val(result.r_code);
                $("#sector_id").val(result.sector_id);
                $("#responsibleSector").val(result.responsibleSector);

            }).catch(function(err) {
                $("#responsibleName").val("");
                $error(err.message)
            })
        }
    </script>

    <!--Valida hora inicial se maior que hora final. -->
    <script>
        $('#reservationMeet').submit(function(e) {

            var dateSelected = $('#dateSelected').val();
            var selectStartHour = $('#selectStartHour').val();
            var selectEndHour = $('#selectEndHour').val();
            var responsibleRCode = $('#responsibleRCode').val();
            var selectMeetRoom = $('#selectMeetRoom').val();
            var selectReason = $('#selectReason').val();

            var timeInMs = Date.now();
            var selectedDateTime = dateSelected + ' ' + selectStartHour + ':00'; //date("Y-m-d H:i:s");
            var selectedMs = Date.parse(selectedDateTime);

            //Valida reserva com 60 min de antecedência
            if (((((selectedMs - timeInMs) / 1000) / 60)) < 60) {
                e.preventDefault();
                return $error('ERRO! Deve-se reservar ou atualizar reserva com 1 hora de antecedência.');
            }

            if (selectStartHour > selectEndHour) {
                e.preventDefault();
                return $error('ERRO! A hora inicial é maior ou igual a hora final.');
            }

            if (responsibleRCode == "") {
                e.preventDefault();
                return $error('ERRO! Informe a matrícula.');
            }

            if (selectMeetRoom == "") {
                e.preventDefault();
                return $error('ERRO! Informe a sala.');
            }

            if (selectReason == "") {
                e.preventDefault();
                return $error('ERRO! Informe o motivo.');
            }
            block();
        });
    </script>

    <!--Exibe tela de confirmação remover -->
    <script>
        function confirmationBtnRemove(event) {

            Swal.fire({
                title: 'Tem certeza disso?',
                text: "Você irá remover a sua reserva!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    block();
                    $("#reservationMeet").attr("action", "/administration/reservation/meetroom/remove");
                    $("#reservationMeet").submit();
                }
            })
        }
    </script>

    <script>
        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mReservate").addClass('sidebar-group-active active');
            $("#mReservateView").addClass('active');
        }, 100);
    </script>



@endsection
