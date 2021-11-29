@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Monitoramento do recebimento</h5>
              <div class="breadcrumb-wrapper col-12">
                Solicitações do dia
				  <button type="button" 
data-toggle="modal" data-target="#modal_filter" class="btn btn-primary" style="float:right">Filtrar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- users list start -->
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card" id="receivement">
                    <div class="card-content">
                        <ul class="list-inline mb-0" style="position: absolute; right: 10px; top: 10px;">
                            <li>
                                <a data-action="expand">
                                    <i class="bx bx-fullscreen"></i>
                                </a>
                            </li>
                            <li>
                                <a onclick="reloadList()">
                                    <i class="bx bx-revision"></i>
                                </a>
                            </li>
                        </ul>
                        <div class="card-body">
                            <hr>
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Código</th>
                                            <th>Razão</th>
                                            <th>Portão</th>
                                            <th>Solicitante</th>
                                            <th>Tipo</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ListRequests">
                                    </tbody>
                                </table>
                            </div>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar dados</span>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data</label>
                                <input type="text" class="form-control date-mask" name="date" id="date">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Portão</label>
                                <select class="form-control" name="gate" id="gate">
                                    <option value="" selected disabled>Selecione</option>
									@foreach($gates as $gate)
                                    <option value="{{$gate->id}}">{{$gate->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="button" onclick="reloadList()" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Filtrar</span>
                    </button>
                </div>
        </div>    
    </div>   
</div>
    <script>
        function dynamicSort(property) {
            var sortOrder = 1;
            if(property[0] === "-") {
                sortOrder = -1;
                property = property.substr(1);
            }
            return function (a,b) {
                /* next line works with strings and numbers,
                 * and you may want to customize it to your needs
                 */
                var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
                return result * sortOrder;
            }
        }

        $(document).on('click', '.marker-received', function(e) {
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Confirmar recebimento',
                text: "Deseja confirmar o recebimento da solicitação",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    document.location.href = "/receivement/confirm/received/" + id;
                }
            });
        });

        function rendRequests(requests) {
            var list = '';
            requests.forEach(function(val) {
                var type = '';
                var status = '';
                var link_url = val.is_visite ? '/logistics/request/visitor/service/list?code='+val.code : '/logistics/request/cargo/transport/list?code='+val.code;
                var confirmReceiver = val.is_liberate ? '<a class="dropdown-item marker-received" data-id="'+val.id+'" href="javascript:void(0)"><i class="bx bx-check-double mr-1"></i> Marcar como recebido</a>' : '';
                if (val.is_liberate) {
                    status = `<span class="badge badge-success">
                        Liberado
                        <br>${moment(val.request_action_time).format('DD/MM/Y HH:mm')}
                    </span>`;
                } else {
                    status = `<span class="badge badge-info">Aguard. Liberação</span>`;
                }
                if (val.is_entry_exit === 1)
                    type = '<span class="badge badge-primary">Entrada</span>';
                else
                    type = '<span class="badge badge-warning">Saída</span>';

                list += `<tr ${val.is_liberate ? 'style="background: #c4ffc0;"' : ''}>
                            <td>
                                <span class="badge badge-primary">
                                    <span style="font-weight: bold; font-size: 16px;">${moment(val.date_hour).format('DD/MM')}</span>
                                    <br><span style="font-size: 14px;">${moment(val.date_hour_initial).format('HH:mm')}-${moment(val.date_hour).format('HH:mm')}</span>
                                </span>
                            </td>
                            <td><b>${val.code}</b></td>
                            <td>${val.type_reason_name}</td>
                            <td>${val.gate}</td>
                            <td>${val.request_user}</td>
                            <td>${type}</td>
                    <td>${status}</td>
                    <td>
                        <div class="dropleft">
                            <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                            <div class="dropdown-menu dropdown-menu-right">
                                ${confirmReceiver}
                                <a class="dropdown-item" href="${link_url}" target="_blank"><i class="bx bx-show mr-1"></i> Visualizar</a>
                            </div>
                        </div>
                    </td>
                </tr>`
            });
            $('#ListRequests').html(list);
        }
        function reloadList() {
            // Block Element
            $("#receivement").find(".card-content").block({
                message:
                    '<div class="bx bx-sync icon-spin font-medium-2 text-primary"></div>',
                overlayCSS: {
                    backgroundColor: "#fff",
                    cursor: "wait"
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: "none"
                }
            });

            ajaxSend(
                '/receivement/monitor/ajax', {"date": $('#date').val(), "gate": $('#gate').val()}
            ).then(($result) => {
                $("#receivement").find(".card-content").unblock();
                $('#ListRequests').html('');
                rendRequests($result.sort(dynamicSort("date_hour")));
            }).catch((error) => {
                $("#receivement").find(".card-content").unblock();
                $error(error.message);
            });
        }
    $(document).ready(function () {
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
        reloadList();
    setInterval(() => {
        reloadList();
    }, 300000);

    setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#mEntryExit").addClass('sidebar-group-active active');
                $("#mEntryExiReceivementMonitor").addClass('active');
            }, 100);

    });
    </script>
@endsection
