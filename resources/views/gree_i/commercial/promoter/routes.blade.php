@extends('gree_i.layout')

@section('content')
<style>
.pac-container {
    z-index: 1051 !important;
}
.fc-event {
    border: 1px solid #000000 !important;
}
.cardbox {
    box-shadow: -8px 12px 18px 0 rgba(25,42,70,.13);
    -webkit-transition: all .3s ease-in-out;
    transition: all .3s ease-in-out;
    min-height: 155px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    object-fit: cover;
}
</style>
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/calendars/fullcalendar.min.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/calendars/tui-calendar.min.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Comercial</h5>
              <div class="breadcrumb-wrapper col-12">
                Planejar rotas
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="{{Request::url()}}" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-4">
                        <label for="users-list-verified">Promotor</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="id_user" name="id_user" style="width: 100%;" data-placeholder="Pesquise..." multiple>
                                <option></option>
                                @foreach ($userall as $key)
                                <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-4">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="id_status" name="id_status" style="width: 100%;" data-placeholder="Escolha..." multiple>
                                <option></option>
                                <option value="1">CONCLUÍDO</option>
                                <option value="2">CANCELADO</option>
                                <option value="3">PENDENTE</option>
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

<div class="modal fade text-left w-100" id="modal-generic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Planejemento de rotas</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <form action="/commercial/promoter/request/item/edit_do" method="post" id="formDefault">
        <input type="hidden" value="0" id="id" name="id">
        <input type="hidden" value="0" id="latitude" name="latitude">
        <input type="hidden" value="0" id="longitude" name="longitude">
        <input type="hidden" value="" id="json_data" name="json_data">
        <div class="modal-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                <li class="nav-item current">
                <a class="nav-link active" id="info-tab-fill" data-toggle="tab" href="#info-fill" role="tab" aria-controls="home-fill" aria-selected="true">
                    Informações da rota
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" id="task-tab-fill" data-toggle="tab" href="#task-fill" role="tab" aria-controls="profile-fill" aria-selected="false">
                    Tarefas
                </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content pt-1">
                <div class="tab-pane active" id="info-fill" role="tabpanel" aria-labelledby="info-tab-fill">
                    <div class="row mb-1 text-center">
                        <div class="col-4">
                           <b>Criado em:</b> <span class="d_created">31-08-2020</span>
                        </div>
                        <div class="col-4">
                           <b>Promotor:</b> <span class="d_name">Carlos</span>
                        </div>
                        <div class="col-4">
                           <b>Status:</b> <span class="d_status"><span class="badge badge-light-warning">Pendente</span></span>
                        </div>
                    </div>
                    <fieldset class="form-group">
                        <label for="user">Usuário</label>
                        <select class="form-control js-select2" id="user" name="user" style="width: 100%;" multiple>
                            @foreach ($userall as $key)
                            <option value="{{$key->id}}">{{$key->name}}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="address">Endereço</label>
                        <div class="input-group">
                          <input type="text" name="address" id="address" class="form-control" placeholder="Ex. Av prefeito dulcidio..." aria-describedby="button-addon2">
                          <div class="input-group-append" id="button-addon2">
                            <button data-toggle="modal" data-target="#modal-maps" class="btn btn-primary" type="button"><i class="bx bxs-map-pin"></i></button>
                          </div>
                        </div>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="complement">Complemento</label>
                        <input type="text" class="form-control" id="complement" name="complement" />
                    </fieldset>
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label for="date_start">Data de inicio da rota</label>
                                <input type="text" class="form-control date-mask" id="date_start" name="date_start" />
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label for="date_end">Data de finalização da rota</label>
                                <input type="text" class="form-control date-mask" id="date_end" name="date_end" />
                            </fieldset>
                        </div>
                    </div>
                    <fieldset class="form-group">
                        <label for="description">Descrição da rota</label>
                        <textarea rows="5" class="form-control" id="description" name="description"></textarea>
                    </fieldset>
                </div>
                <div class="tab-pane" id="task-fill" role="tabpanel" aria-labelledby="task-tab-fill">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                          <thead>
                            <tr>
                              <th>TAREFA</th>
                              <th>COMPROVAÇÃO</th>
                              <th>FEITO EM</th>
                              <th>AÇÃO</th>
                            </tr>
                          </thead>
                          <tbody class="loadtask">
                          </tbody>
                        </table>
                        <div class="row mt-2 ml-15 mr-0">
                            <div class="col-md-11">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="taskadd" placeholder="fale sua tarefa, exemplo: Preciso que monte um stand na leroy merlin..." name="taskadd" />
                                </fieldset>
                            </div>
                            <div class="col-md-1">
                                <button type="button" onclick="addtask()" class="btn btn-block btn-primary">
                                    <i class="bx bx-plus text-bold-600"></i>
                                </button>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="modal-footer actiondetail">
            <button type="button" onclick="cancelroute();" class="btn btn-danger ml-1 btndeletemodal">
                <span class="d-sm-block">CANCELAR ROTA!</span>
            </button>
            <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                <span class="d-sm-block">FECHAR</span>
            </button>
            <button type="button" onclick="update();" class="btn btn-success ml-1 btnsavemodal">
                <span class="d-sm-block btnmodal">SALVAR</span>
            </button>
        </div>
        </form>
      </div>
    </div>
  </div>

<div class="modal fade text-left w-100" id="modal-maps" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Arraste ou escreva o endereço próximo</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="address_autocomplete">Buscar por endereço</label>
                        <input type="text" class="form-control" id="address_autocomplete" name="address_autocomplete" />
                    </fieldset>
                </div>
                <div class="col-md-12">
                    <div id="map" style="height: 300px; width:100%"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">FECHAR</span>
            </button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade text-left" id="modal-taskedit" tabindex="-1" role="dialog" aria-labelledby="modal-generic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary">
        <h5 class="modal-title white">EDITANDO TAREFA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>
        </div>
        <form action="#" method="post" id="formTask">
        <input type="hidden" value="0" id="task_index" name="task_index">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="taskname">Tarefa</label>
                        <input type="text" class="form-control" id="taskname" name="taskname" />
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">FECHAR</span>
            </button>
            <button type="button" onclick="taskeditview_do();" class="btn btn-success ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">SALVAR</span>
            </button>
        </div>
        </form>
    </div>
    </div>
</div>

<div id="reportModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="reportModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
                <div class="modal-title">RELATÓRIO DE FINALIZAÇÃO</div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group border p-1">
                            <span id="reporttext" class="form-control-static"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row loadimage m-0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
var calendar;
var $event, $date;
$(document).ready(function() {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var min_date = '<?= date('Y-m-d') ?>';


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
                edit('', date.format());
            }
        },
        eventClick: function(event, element) {

            json = event.extendedProps.all;
            edit(event.extendedProps.all)

        },
        eventAfterRender: function(event, element, view) { 
            var el = element.html();
            var left_do = 0;
            var arrhistory = event.extendedProps.routeHistory;
            for (let ind = 0; ind < arrhistory.length; ind++) {
                const elh = arrhistory[ind];
                if (elh.attach != null)
                left_do++;
            }
            var popover = '<b>Tarefas:</b> '+left_do+'/'+ event.extendedProps.routeHistory.length +' <br>'+event.extendedProps.all.description;
            if (event.extendedProps.all.is_cancelled == 1)
            element.html('<span data-html="true" data-toggle="popover" data-content="'+popover+'" class="tui-full-calendar-weekday-schedule-title" style="background-color: #f31717 !important; color: white;padding: 3px;" data-title="'+event.title+'" title="'+event.title+'"><span class="bx bxs-user font-size-small align-middle"></span> '+event.title+'</span>');
            else if (event.extendedProps.all.is_completed == 1)
            element.html('<span data-html="true" data-toggle="popover" data-content="'+popover+'" class="tui-full-calendar-weekday-schedule-title" style="background-color: #10d304 !important; color: white;padding: 3px;" data-title="'+event.title+'" title="'+event.title+'"><span class="bx bxs-user font-size-small align-middle"></span> '+event.title+'</span>');
            else
            element.html('<span data-html="true" data-toggle="popover" data-content="'+popover+'" class="tui-full-calendar-weekday-schedule-title" style="background-color: #ecb203 !important; color: white;padding: 3px;" data-title="'+event.title+'" title="'+event.title+'"><span class="bx bxs-user font-size-small align-middle"></span> '+event.title+'</span>');
        },
        eventSources: [

            // your event source
            {
                url: '/commercial/promoter/calendar?id_user=<?= Session::get('filter_id_user') ?>&id_status=<?= Session::get('filter_id_status') ?>',
                type: 'GET',
                success: function(response) {
                    for (let index = 0; index < response.events.length; index++) {
                        const element = response.events[index];
                        var newEvent = new Object();
                        var extendedProps = new Object();
                        newEvent.title = element.title;
                        newEvent.start = element.start;
                        newEvent.end = element.end;
                        newEvent.allDay = element.allDay;
                        newEvent.extendedProps = { 
                            id: element.extendedProps.id, 
                            user: element.extendedProps.user, 
                            routeHistory: element.extendedProps.routeHistory,
                            all: element.extendedProps.all,
                        };
                        $('#calendar').fullCalendar('renderEvent', newEvent);
                        
                    }
                    
                    setTimeout(() => {
                        $('[data-toggle="popover"]').popover({
                            placement: 'top',
                            trigger: 'hover',
                        });
                    }, 500);
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
    var hasNew = false;
    var tasks = new Array();
    var id_;
    function edit(elem = '', dateformat = '') {
        $('#formDefault').each (function(){
            this.reset();
        });
        hasNew = elem == '' ? true : false;
        if (elem != '') {
            $(".btnmodal").html('SALVAR');
            $(".btnsavemodal").show();
            $(".btndeletemodal").show();
            let json_row = elem;
            $("#id").val(json_row.id);
            id_ = json_row.id;
            $("#address").val(json_row.address);
            $("#user").val(json_row.promoter_user_id);
            $("#user").trigger('change');
            $("#complement").val(json_row.complement);
            $("#date_start").val(json_row.date_start);
            $("#date_end").val(json_row.date_end);
            $("#description").val(json_row.description);
            $("#latitude").val(json_row.latitude);
            $("#longitude").val(json_row.longitude);
            if (json_row.is_cancelled == 1) {
                $(".btndeletemodal").hide();
                $(".btnsavemodal").hide();
                $(".d_status").html('<span class="badge badge-light-danger">Cancelado</span>');
            } else if (json_row.is_completed == 1) {
                $(".d_status").html('<span class="badge badge-light-success">Concluído</span>');
            } else {
                $(".d_status").html('<span class="badge badge-light-warning">Pendente</span>');
            }

            $(".d_created").html(json_row.created_at);
            $(".d_name").html(json_row.user.name);

            tasks = new Array();
            tasks.push(json_row.route_history);
            console.log(tasks);
            reloadTasks();
        } else {
            $("#date_start").val(dateformat);
            $(".d_status").html('-');
            $(".d_created").html('-');
            $(".d_name").html('-');
            $("#id").val(0);
            $("#user").val(0).trigger('change');
            $(".loadtask").html('');
            tasks = new Array();
            $(".btndeletemodal").hide();
            $(".btnmodal").html('CRIAR NOVO');
        }

        
        $("#modal-generic").modal();
    }
    function cancelroute() {
        Swal.fire({
            title: 'Tem certeza disso?',
            text: "Você irá cancelar a rota permanentemente, deseja continuar?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim!',
            cancelButtonText: 'Não quero',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    ajaxSend('/commercial/promoter/routes/cancel', {id:id_}, 'POST').then(function(result){                   
                        location.reload();
                    }).catch(function(err){
                        unblock();
                        $error(err.message)
                    })
                }
            })
    }
    function update() {
        if ($("#user").val().length == 0) {
            
            return $error('É necessário ao menos ter 1 promotor cadastro nessa rota.');
        } else if ($("#address").val() == '') {

            return $error('Preencha o endereço');
        } else if ($("#complement").val() == '') {

            return $error('Preencha o complemento');
        } else if ($("#date_start").val() == '') {

            return $error('Preencha uma data de inicio para realização da rota.');
        } else if ($("#date_end").val() == '') {

            return $error('Preencha uma data de finalização da rota.');
        } else if ($("#description").val() == '') {

            return $error('Preencha a descrição da rota');
        } else if (tasks.length == 0) {

            return $error('Ao menos adicione uma tarefa para poder continuar.');
        }
        $("#modal-generic").modal('toggle');
        block();
        ajaxSend('/commercial/promoter/routes/edit_do', $("#formDefault").serialize(), 'POST').then(function(result){                   
            location.reload();
        }).catch(function(err){
            unblock();
            $error(err.message)
        })
    }

    function taskeditview(index, text) {
        $("#task_index").val(index);
        $("#taskname").val(text);
        $("#modal-taskedit").modal();
    }
    function taskeditview_do() {
        tasks[0][$("#task_index").val()].description = $("#taskname").val();
        $("#taskname").val('');
        reloadTasks();
        $("#modal-taskedit").modal('toggle');
    }
    function taskdelete(index) {
        Swal.fire({
            title: 'Tem certeza disso?',
            text: "Você irá remover a tarefa, mas precisará salvar, para gravar as alterações.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar!',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    tasks[0].splice(index, 1);
                    reloadTasks();
                }
            })
    }
    function addtask() {
        if ($("#taskadd").val() == "") {

            return $error('Você precisa adicionar uma descrição para tarefa');
        }
        if (tasks.length == 0) {
            tasks.push([{
                id: 0,
                description: $("#taskadd").val()
            }]);
        } else {
            tasks[0].push({
                id: 0,
                description: $("#taskadd").val()
            });
        }
        
        reloadTasks()
        $("#taskadd").val('');
    }

    function reloadImg(index) {

        $("#reporttext").html(tasks[0][index].report);

        var list = '';
        for (let i = 0; i < tasks[0][index].images.length; i++) {
            const img = tasks[0][index].images[i];

            list += '<div class="col-md-4 cardbox cursor-pointer">';
            list += '<a target="_blank" href="'+img.image+'"><img id="image" height="100" class="img-fluid" src="'+img.image+'" alt=""></a>';
            list += '</div>';
            
        }

        $(".loadimage").html(list);
        $("#reportModal").modal();

    }

    function reloadTasks() {
        
        var list = '';
        for (let index = 0; index < tasks[0].length; index++) {
            const elem = tasks[0][index];
            
            list += '';
            list += '<tr>';
            list += `<td class="text-bold-500">${elem.description}</td>`;
            
            if (elem.attach_date) {
                list += `<td><a onclick='reloadImg("${index}")' href='javascript:void(0)'>Ver relatório</a></td>`;
                list += `<td>${elem.attach_date}</td>`;
                list += `<td></td>`;
            } else {
                list += `<td></td>`;
                list += `<td></td>`;
                list += '<td> ';
                list += `<a onclick="taskeditview(${index}, '${elem.description}')" href="javascript:void(0)" class="mr-1"><i class="badge-circle badge-circle-light-primary bx bx-edit font-medium-1"></i></a>`;
                list += `<a onclick="taskdelete(${index})" href="javascript:void(0)" href="#"><i class="badge-circle badge-circle-light-danger bx bx-x-circle font-medium-1"></i></a>`;
                list += '</td>';
            }

            list += '</tr>';

        }

        if (tasks.length == 0) 
        tasks = new Array();

        $(".loadtask").html(list);
        $("#json_data").val(JSON.stringify(tasks[0]));
    }

    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('filter_id_user'))) { ?>
        $('#id_user').val(['<?= Session::get('filter_id_user') ?>']).trigger('change');
        <?php } ?>
        <?php if (!empty(Session::get('filter_id_status'))) { ?>
        $('#id_status').val(['<?= Session::get('filter_id_status') ?>']).trigger('change');
        <?php } ?>

        $('.showDetails td').not('.no-click').click(function (e) { 
            e.preventDefault();
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
            
        });

        $("#formTask").submit(function (e) { 
            e.preventDefault();
            
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
        
        setInterval(() => {
            $("#mCommercial").addClass('sidebar-group-active active');
            $("#mCommercialPromoter").addClass('sidebar-group-active active');
            $("#mCommercialPromoterRoutes").addClass('active');
        }, 100);

    });
    </script>
    <script>
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: -13.7025048, lng: -69.6903341},
              zoom: 3,
              draggable: true,
              mapTypeId: 'roadmap'
            });
            
            // Drag Event
            var gmarkers = [];
            var markers = [];
            var markern = [];
            var marker_cliente = '/media/pin.png';
            var marker;
    
            // Create the search box and link it to the UI element.
            var input = document.getElementById('address');
            var searchBox = new google.maps.places.Autocomplete(input);

            searchBox.addListener("place_changed", () => {

                const place = searchBox.getPlace();

                if (!place.geometry) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17); // Why 17? Because it looks good.
                }
                marker.setPosition(place.geometry.location);
                $("#address").val(place.formatted_address);
                $("#address_autocomplete").val(place.formatted_address);
            });

            var input2 = document.getElementById('address_autocomplete');
            var searchBox2 = new google.maps.places.Autocomplete(input2);

            searchBox2.addListener("place_changed", () => {

                const place = searchBox2.getPlace();

                if (!place.geometry) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17); // Why 17? Because it looks good.
                }
                marker.setPosition(place.geometry.location);
                $("#address").val(place.formatted_address);
                $("#address_autocomplete").val(place.formatted_address);
            });
            
            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
              searchBox.setBounds(map.getBounds());
            });
    
            $( "#address" ).blur(function() {
                if ($("#address").val() != "") {
    
                    clearMarkers();

                    var geocoder = new google.maps.Geocoder()
                    var end = $("#address").val();
                    var endereco = end;
                    
                    
                    geocoder.geocode( { 'address': endereco}, function(resultado, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        
                    var lat1 = resultado[0].geometry.location.lat();
                    var long1 = resultado[0].geometry.location.lng();	

                    marker.setPosition(resultado[0].geometry.location);
                    
                    console.log( "Endereço: " + endereco + " - Latitude: " + lat1 + " Longitude: " + long1);
                    document.getElementById("latitude").value = lat1;
                    document.getElementById("longitude").value = long1;
    
                    } else {
                        confirm("Endereço não foi encontrado, deseja continuar assim mesmo?");
                    }
                });
    
                }
                
            });
    
            // Sets the map on all markers in the array.
            function setMapOnAll(map) {
                    for (var i = 0; i < markers.length; i++) {
                      markers[i].setMap(map);
                    }
            }
            
            function geocodeR(latitude, longitude) {
                var pos = {
                lat: latitude,
                lng: longitude
                };
    
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: pos }, function(results, status) {
                    if (status === "OK") {
                    if (results[0]) {
                        $("#address").val(results[0].formatted_address);
                        $("#address_autocomplete").val(results[0].formatted_address);
                    } else {
                        window.alert("Não foi encontrado o endereço.");
                    }
                    } else {
                    window.alert("Geocoder failed due to: " + status);
                    }
                });
            }
    
            // Removes the markers from the map, but keeps them in the array.
            function clearMarkers() {
                setMapOnAll(null);
            }
            
            
            // Create a marker for each place.
            marker = new google.maps.Marker({
                map: map,
                icon: marker_cliente,
                title: 'Local do serviço',
                zIndex: 2,
                animation: google.maps.Animation.DROP,
                position: {lat: -13.7025048, lng: -69.6903341},
                draggable: true,
            });
                
                
            google.maps.event.addListener(marker, 'drag', function() {
                console.log('posição Lat: ' + marker.position.lat());
                console.log('posição lng: ' + marker.position.lng());
                document.getElementById('latitude').value = marker.position.lat();
                document.getElementById('longitude').value = marker.position.lng();
            });

            google.maps.event.addListener(marker, 'dragend', function() {
                geocodeR(marker.position.lat(), marker.position.lng());
            });            
            
            
          }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ getConfig("google_key_web") }}&libraries=places&callback=initAutocomplete"></script>
@endsection