@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_homeoffice') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('homeoffice_i.ws_01') }}
              </div>
            </div>
          </div>
        </div>
      </div>

<div class="alert alert-warning alert-dismissible mb-2" role="alert">
<div class="d-flex align-items-center">
    <i class="bx bx-error"></i>
    <span>
        {{ __('homeoffice_i.ws_02') }}
    <br>{{ __('homeoffice_i.ws_03') }}
    </span>
</div>
</div>

<div class="alert alert-danger alert-dismissible mb-2" role="alert">
<div class="d-flex align-items-center">
    <i class="bx bx-error"></i>
    <span>
        {{ __('homeoffice_i.ws_04') }}
    </span>
</div>
</div>

</div>

    <div class="card text-center" style="width: 395px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; opacity: 0.9;z-index: 99;">
        <div class="card-header">
          <h4 class="card-title">{{ __('homeoffice_i.ws_01') }}</h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <div style="width: 100%;height: 60px;">
                        <div id="start_job_div" class="float-left ml-30">{{ __('homeoffice_i.ws_05') }}<h4 id="start_job_time">00:00:00</h4></div>
                        <div id="end_job_div" class="float-right mr-30" style="display: none">{{ __('homeoffice_i.ws_06') }}<h4 id="end_job_time">00:00:00</h4></div>
                    </div>
                    <div class="spinner-grow loading-task" style="display: none" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button id="start_job" type="button" class="btn btn-rounded btn-noborder btn-success">{{ __('homeoffice_i.ws_07') }}</button>
                    <button id="end_job" type="button" class="btn btn-rounded btn-noborder btn-danger" style="display: none;">{{ __('homeoffice_i.ws_08') }}</button>

          </div>
        </div>
      </div>

<div class="modal fade text-left" id="modal-end" tabindex="-1" role="dialog" aria-labelledby="modal-end" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
<div class="modal-content">
    <form action="/misc/task/report" id="sendReportTask" method="POST" enctype="multipart/form-data">
<div class="modal-header bg-primary">
    <h5 class="modal-title white" id="modal-end">{{ __('homeoffice_i.ws_09') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <i class="bx bx-x"></i>
    </button>
</div>
<div class="modal-body">
    <input type="hidden" name="task_id" id="task_id">
    <input type="hidden" name="end_date" id="end_date">
    <input type="hidden" name="tasks" id="tasks" value="[]">
    <div class="form-group row">
        <label class="col-12" for="description">{{ __('homeoffice_i.ws_10') }}</label>
        <div class="col-12">
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="{{ __('homeoffice_i.ws_11') }}"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12" for="attach">{{ __('homeoffice_i.ws_12') }}</label>
        <div class="col-12">
            <ul class="list-unstyled mb-0 border p-2">
                <li class="d-inline-block mr-2">
                  <fieldset>
                    <div class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" value="1" name="extrah" id="extra_yes">
                      <label class="custom-control-label" for="extra_yes">{{ __('homeoffice_i.ws_13') }}</label>
                    </div>
                  </fieldset>
                </li>
                <li class="d-inline-block mr-2">
                  <fieldset>
                    <div class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" checked="" value="0" name="extrah" id="extra_no">
                      <label class="custom-control-label" for="extra_no">{{ __('homeoffice_i.ws_14') }}</label>
                    </div>
                  </fieldset>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-9">
            <div class="row">
                <label class="col-12" for="attach">Tarefas realizadas</label>
                <div class="col-3">
                    <input type="text" class="form-control" id="subject" placeholder="Assunto">
                </div>
                <div class="col-9">
                    <input type="text" class="form-control" id="task" placeholder="Processo">
                </div>
                <div class="col-12 mt-1">
                    <input type="text" class="form-control" id="result" placeholder="resultado">
                </div>
            </div>
        </div>
        <div class="col-3">
            <button onclick="additem()" type="button" style="height: 95px;margin-top: 19px;" class="btn btn-primary btn-block" id="addtask">Adicionar tarefa</button>
        </div>
    </div>
    <div class="row mt-1 mb-1">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                    <tr>
                        <th>ASSUNTO</th>
                        <th>PROCESSO</th>
                        <th>RESULTADO</th>
                        <th>ACÕES</th>
                    </tr>
                    </thead>
                    <tbody class="row-tasks">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12" for="attach">Arquivo adicional</label>
        <div class="col-12">
            <input type="file" class="form-control" id="attach" name="attach">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
    <i class="bx bx-x d-block d-sm-none"></i>
    <span class="d-none d-sm-block">{{ __('homeoffice_i.ws_17') }}</span>
    </button>
    <button type="submit" class="btn btn-primary ml-1">
    <i class="bx bx-check d-block d-sm-none"></i>
    <span class="d-none d-sm-block">{{ __('homeoffice_i.ws_18') }}</span>
    </button>
</div>
</form>
</div>
</div>
</div>

    <script>
    var arr_tasks = [];
    var time_start, time_end, task_id = 0;
    var start_time = setInterval(start, 1000);
    function start() {
        time_start = new Date();
        document.getElementById("start_job_time").innerHTML = time_start.toLocaleTimeString();
    }
    var end_time = setInterval(end, 1000);
    function end() {
        time_end = new Date();
        document.getElementById("end_job_time").innerHTML = time_end.toLocaleTimeString();
        $("#end_date").val(time_end.toLocaleTimeString());
    }

    function reloadtasks() {

        $('.row-tasks').html('');

        var list = '';
        for (let i = 0; i < arr_tasks.length; i++) {
            const row = arr_tasks[i];

            list += '<tr>';
            list += '<td class="text-bold-500">'+row.subject+'</td>';
            list += '<td>'+row.task+'</td>';
            list += '<td>'+row.result+'</td>';
            list += '<td><a onclick="deleteitem('+i+')" href="javascript:void(0)"><i class="badge-circle badge-circle-light-danger bx bx-x font-medium-1"></i></a></td>';
            list += '</tr>';

        }

        $('.row-tasks').html(list);
        $('#tasks').val(JSON.stringify(arr_tasks));

    }
    function deleteitem(index) {
        arr_tasks.splice(index, 1);
        reloadtasks();
    }
    function additem() {
        if ($('#subject').val() == '') {
            return $error('Você precisa preencher o assunto.');
        } else if ($('#task').val() == '') {
            return $error('Você precisa preencher o processo realizado');
        }

        arr_tasks.push({
            'subject': $('#subject').val(),
            'task': $('#task').val(),
            'result': $('#result').val(),
        });

        $('#subject').val('');
        $('#task').val('');
        $('#result').val('');
        reloadtasks();
    }
    $(document).ready(function () {
        <?php if ($task_id == 0) { ?>

                start();

                $("#end_job_div").hide();
                $("#end_job").hide();
            <?php } else { ?>

                clearInterval(start_time);
                task_id = <?= $task_id ?>;
                end();
                $("#start_job").hide();
                $("#end_job_div").show();
                $("#end_job").show();
                time_start = new Date('<?= $task_start ?>');
                document.getElementById("start_job_time").innerHTML = time_start.toLocaleTimeString();

            <?php } ?>

            $("#start_job").click(function (e) {
                $(".loading-task").show();
                $("#start_job").hide();
                clearInterval(start_time);
                $.ajax({
                    type: "post",
                    url: "/misc/task/start",
                    data: {start_date: time_start.toLocaleTimeString(), task_id: task_id},
                    success: function (response) {

                        if (response.success) {
                            if (response.task_id > 0) {

                                $(".loading-task").hide();
                                end();
                                $("#start_job").hide();
                                $("#end_job_div").show();
                                $("#end_job").show();
                                task_id = response.task_id;
                                time_start = new Date(response.task_start);
                                document.getElementById("start_job_time").innerHTML = time_start.toLocaleTimeString();

                            } else {
                                $(".loading-task").hide();
                                end();
                                $("#start_job").hide();
                                $("#end_job_div").show();
                                $("#end_job").show();
                                task_id = response.task_id;
                            }
                        } else {
                            $(".loading-task").hide();
                            $("#start_job").show();
                            error('<?= __('homeoffice_i.ws_21') ?>');
                        }

                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $(".loading-task").hide();
                        $("#start_job").show();
                        error('<?= __('homeoffice_i.ws_19') ?>');
                    }
                });

            });

            $("#sendReportTask").submit(function (e) {
                if (arr_tasks.length > 0) {
                    clearInterval(end_time);
                    block();
                } else {
                    e.preventDefault();
                    $error('É obrigatório informar suas tarefas.');
                }

            });

            $("#end_job").click(function (e) {
                $("#modal-end").modal();
                $("#task_id").val(task_id);

            });
        $('#list-datatable').DataTable( {
            searching: false,
            paging: false,
            ordering:false,
            lengthChange: false,
            language: {
                search: "{{ __('layout_i.dtbl_search') }}",
                zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                info: "{{ __('layout_i.dtbl_info') }}",
                infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
                infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
            }
        });

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mHomeOffice").addClass('sidebar-group-active active');
            $("#mHomeOfficeNew").addClass('active');
        }, 100);
		
		setInterval(() => {
            window.location.reload();
        }, 600000);

    });
    </script>
@endsection
