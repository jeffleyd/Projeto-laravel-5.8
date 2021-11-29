@extends('gree_i.layout')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/calendars/fullcalendar.min.css">
@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Comercial</h5>
              <div class="breadcrumb-wrapper col-12">
                Calendário de promotores
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="#" id="searchNow" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-6 col-sm-6 col-lg-8">
                        <label for="users-list-verified">Usuário</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="user" name="user">
                                <option value="">Todos</option>
                                @foreach ($userall as $key)
                                <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-4 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>



<script>
var calendar;
var $event, $date;
$(document).ready(function() {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var min_date = '2020-09-08';

    
    $("#user").change(function (e) { 
        $("#ship").load("/ships/get", {user: $("#user").val()}, function (response, status, request) {
            this;
            
        });
        
    });

    $("#new_user").change(function (e) { 
        $("#new_ship").load("/ships/get", {user: $("#new_user").val()}, function (response, status, request) {
            this;
            
        });
        
    });

    
    $("#new_is_other_people").change(function (e) { 
        if($("#new_is_other_people").val() == 0) {
            $(".new_other_people").hide();
        } else {
            $(".new_other_people").show();
        }
        
    });

    $("#is_other_people").change(function (e) { 
        if($("#is_other_people").val() == 0) {
            $(".other_people").hide();
        } else {
            $(".other_people").show();
        }
        
    });

    /*  className colors

        className: default(transparent), important(red), chill(pink), success(green), info(blue)

        */


    /* initialize the external events
        -----------------------------------------------------------------*/

    $('#external-events div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });


    /* initialize the calendar
        -----------------------------------------------------------------*/

    calendar =  $('#calendar').fullCalendar({
        locale: 'pt-br',
        header: {
            left: 'title',
            center: 'month',
            right: 'prev,next today'
        },
        editable: false,
        firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
        selectable: true,
        defaultView: 'month',

        axisFormat: 'h:mm',
        columnFormat: {
            month: 'ddd',    // Mon
            week: 'ddd d', // Mon 7
            day: 'dddd M/d',  // Monday 9/7
            agendaDay: 'dddd d'
        },
        titleFormat: {
            month: 'MMMM YYYY', // September 2009
            week: "MMMM YYYY", // September 2009
            day: 'MMMM YYYY'   // Tuesday, Sep 8, 2009
        },
        allDaySlot: false,
        dayClick: function(date, allDay, jsEvent, view) {

            if (date.format() >= min_date) {
                $date = date;
                $("#new_is_other_people").val(0);
                $(".new_other_people").hide();
                $(".add_event").modal();
            }
        },
        eventClick: function(event, element) {

            $("#request_mari").val(event.extendedProps.is_request_mari);
            $event = event;
            $(".event_title").html('<b>' + event.title + '</b>');
            $("#ship").val(event.extendedProps.ship_id);
            $(".edit_event").modal();
            $("#used_hour").val(event.extendedProps.used_hour);
                        $("#is_other_people").val(event.extendedProps.is_other_people);
            $("#other_people_name").val(event.extendedProps.other_people_name);
            if (event.extendedProps.is_other_people == 1) {
                $(".other_people").show();
            } else {
                $(".other_people").hide();
            }
            if (event.extendedProps.other_people_qualification) {
                $("#other_people_qualification_file").show();
                $("#other_people_qualification_file").attr('href', event.extendedProps.other_people_qualification);
            } else {
                $("#other_people_qualification_file").hide();
                $("#other_people_qualification_file").removeAttr('href');
            }
            


        },
        eventAfterRender: function(event, element, view) { 
            var el = element.html();
            element.html("<div class='block-e'><div class='fc-content'><span class='fc-title'>"+ event.title +"</span><br><span class='text-white'><b>"+ event.extendedProps.ship +"</b></span></div></div>");

        },
        eventSources: [

            // your event source
            {
                url: '/appointment/get',
                type: 'GET',
                success: function(response) {
                    for (let index = 0; index < response.events.length; index++) {
                        const element = response.events[index];
                        var newEvent = new Object();
                        var extendedProps = new Object();
                        newEvent.title = element.title;
                        newEvent.start = element.start;
                        newEvent.allDay = true;
                        newEvent.extendedProps = { 
                            user_id: element.extendedProps.user_id, 
                            ship: element.extendedProps.ship, 
                            id: element.extendedProps.id, 
                            ship_id: element.extendedProps.ship_id, 
                            used_hour: element.extendedProps.used_hour,
                            is_other_people: element.extendedProps.is_other_people,
                            other_people_name: element.extendedProps.other_people_name,
                            other_people_qualification: element.extendedProps.other_people_qualification,
                            is_request_mari: element.extendedProps.is_request_mari,
                            };
                        $('#calendar').fullCalendar('renderEvent', newEvent);
                        
                    }
                    return;
                },
                error: function() {
                    error('Erro ao carregar os agendamentos, atualize a página!');
                },
            }

        ]
    });
});
</script>
<script>
    function updateEvent() {

        block();
        $("#start_date").val($event._start.format());
        $("#id").val($event.extendedProps.id);
        var form = $('#form')[0];
        var data = new FormData(form);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false, // impedir que o jQuery tranforma a "data" em querystring
            contentType: false, // desabilitar o cabeçalho "Content-Type"
            url: "/appointment/update",
            data: data,
            success: function (response) {
                unblock();

                if (response.success) {
                    //$('#calendar').fullCalendar( 'refetchEvents' );
                    
                    location.reload();
                } else {
                    error(response.msg); 
                }
            }, error: function (e) {
                unblock();
                error(e);
            }
        });
    }
    function deleteEvent() {
        
        block();
        $.ajax({
            type: "POST",
            url: "/appointment/delete",
            data: {
                id: $event.extendedProps.id
            },
            success: function (response) {
                unblock();

                if (response.success) {
                    //$('#calendar').fullCalendar( 'refetchEvents' );
                    
                    location.reload();
                } else {
                    error(response.msg); 
                }
            }, error: function (e) {
                unblock();
                error(e.responseJSON.msg);
            }
        });
    }
    function addEvent() {

        block();
        $("#new_start_date").val($date.format());
        var form = $('#new_form')[0];
        var data = new FormData(form);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false, // impedir que o jQuery tranforma a "data" em querystring
            contentType: false, // desabilitar o cabeçalho "Content-Type"
            url: "/appointment/update",
            data: data,
            success: function (response) {
                unblock();

                if (response.success) {
                    //$('#calendar').fullCalendar( 'refetchEvents' );
                    
                    location.reload();
                } else {
                    error(response.msg); 
                }
            }, error: function (e) {
                unblock();
                console.log(e);
                error(e.responseJSON.msg);
            }
        });

    }
</script>
<script>
$(document).ready(function () {
    $(".js-select2").select2({
        maximumSelectionLength: 1,
    });

    //createMarker();

    $("#searchNow").submit(function (e) { 
        e.preventDefault();

        //createMarker();
        
    });

        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });

        $('.date-mask').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        });

        $('.hour-mask').mask('00:00', { placeholder: "HH:ss" });

        setInterval(() => {
            $("#mCommercial").addClass('sidebar-group-active active');
            $("#mCommercialPromoter").addClass('sidebar-group-active active');
            $("#mCommercialPromoterCalendar").addClass('active');
    }, 100);

});
</script>
@endsection