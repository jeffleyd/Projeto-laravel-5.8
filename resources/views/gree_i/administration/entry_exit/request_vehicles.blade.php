@extends('gree_i.layout')

@section('content')
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <style>
        .td-title {
            background-color: #f6f6f6;
            font-weight: normal;
        }
        .td-text-white {
            color: white;
            font-weight: normal;
        }
        .td-color-black {
            background-color: #000;
            font-weight: normal;
        }
        .td-color-red {
            background-color: rgb(145, 0, 0);
        }
        .td-bold {
            font-weight: 600 !important;
        }
        .td-text-center {
            text-align: center;
        }
        .td-font-11 {
            font-size: 6px;
            font-weight: normal;
        }
        .td-font-13 {
            font-size: 8px;
            font-weight: normal;
        }
        .td-font-14 {
            font-size: 12px;
            font-weight: normal;
        }
        .td-font-17 {
            font-size: 13px;
            font-weight: normal;
        }
    </style>
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Entrada & Saída</h5>
              <div class="breadcrumb-wrapper col-12">
                Solicitações de veículos
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
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="top d-flex flex-wrap">
                                <div class="action-filters flex-grow-1">
                                    <div class="dataTables_filter mt-1">
                                        <h5>Lista de solicitações</h5>
                                    </div>
                                </div>
                                <div class="actions action-btns d-flex align-items-center">
                                    <div class="dropdown invoice-filter-action">
                                        <button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                        <button type="button" onclick="@if(Request::get('start_date') and Request::get('end_date')) window.open('{{Request::fullUrl()}}&export=1', '_self') @else $error('Você precisa filtrar e informar a data inicial e final.'); @endif" class="btn btn-success shadow mr-1">Exportar</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Solicitante</th>
                                            <th>Feito em</th>
                                            <th>Placa</th>
                                            <th>KM</th>
                                            <th>Tipo</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($load_requests as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td>{{$key->request_user->short_name}} ({{$key->request_user->r_code}})</td>
                                            <td>{{date('d/m/Y H:i', strtotime($key->date_hour))}}</td>
                                            <td>{{$key->entry_exit_rent_vehicle->registration_plate}}</td>
                                            <td>{{$key->km}}</td>
                                            <td>
                                                @if($key->is_entry_exit == 1)
                                                    <span class="badge badge-light-primary">Entrada</span>
                                                @else
                                                    <span class="badge badge-light-warning">Saída</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($key->deleted_at)
                                                    <span class="badge badge-light-danger">Deletado</span>
                                                @elseif ($key->is_reprov)
                                                    <span class="badge badge-light-danger">Reprovado</span>
                                                @elseif ($key->is_cancelled)
                                                    <span class="badge badge-light-danger">Cancelado</span>
                                                @elseif ($key->is_denied)
                                                    <span class="badge badge-light-danger">Negado</span>
                                                @elseif ($key->is_liberate)
                                                    <span class="badge badge-light-success">Liberado</span>
                                                @elseif ($key->has_analyze)
                                                    <span class="badge badge-light-success">Em análise</span>
                                                @else
                                                    <span class="badge badge-light-warning">Aguard. Liberação</span>
                                                @endif
                                            </td>
                                            <td id="action">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="reloadSingle(this)" href="javascript:void(0)" class="dropdown-item"><i class="bx bx-show mr-1"></i> Visualizar</a>
                                                        @if ($key->has_analyze and $key->is_cancelled)
                                                        <a onclick="" href="javascript:void(0)" class="dropdown-item"><i class="bx bx-x-circle mr-1"></i> Cancelar</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $load_requests->appends(getSessionFilters()[2]->toArray()); ?>
                                    </ul>
                                </nav>
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
            <form action="{{Request::url()}}" id="form_modal_filter">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Código</label>
                                <input type="text" class="form-control" name="code">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="first-name-vertical">Data inicial</label>
                                <input name="start_date" class="form-control date-mask">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="first-name-vertical">Data final</label>
                                <input name="end_date" class="form-control date-mask">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Condutor</label>
                                <select name="r_code" style="width:100%" class="form-control select2" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Veículo</label>
                                <select name="entry_exit_rent_vehicle_id" style="width:100%" class="form-control select23" multiple>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="submit" onclick="block();" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Filtrar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left modal-borderless modal-view" id="requestPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full">
        <div class="modal-content">
            <div class="modal-body">
                <div id="request">
                </div>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="wdgt_printElem('request')">
					<span>Imprimir</span>
				</button>
                <button type="button" class="btn btn-light-primary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar visualização</span>
                </button>
            </div>
        </div>
    </div>
</div>
@include('gree_i.misc.components.printElem.script')
<script>
    function reloadSingle(elem) {

        let data = JSON.parse($(elem).attr("json-data"));

        var html = '';
        $('#request').html('');
        var restriction = '';
        if (data.entry_restriction) {
            restriction = `<tr>
                            <td colspan="12" class="td-font-13 td-text-white td-text-center td-color-red td-bold">
                                RENSTRIÇÃO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-text-center td-font-14 td-bold" colspan="12">
                                ${data.entry_restriction}
                            </td>
                        </tr>`;
        }
        var reason = '';
        if (data.justify) {
            reason = `<tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                DESTINO DA SAÍDA
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-text-center td-font-14" colspan="12">
                                ${data.justify}
                            </td>
                        </tr>`;
        }
        var situation = {
            status: 'Aguardando liberação',
            reason: '',
            name: '',
            time: '',
        };
        if (data.deleted_at) {
            $('.btn-analyze').hide();
            situation = {
                status: 'Deletado',
                reason: data.del_description,
                name: data.who_excute_action,
                time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
            };
        } else if (data.is_liberate) {
                situation = {
                status: 'Liberado',
                reason: '',
                name: data.who_excute_action,
                time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
            };
        } else if (data.is_denied) {
            $('.btn-analyze').hide();
            situation = {
                status: 'Negado',
                reason: data.denied_reason,
                name: data.who_excute_action,
                time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
            };
        } else if (data.is_cancelled) {
            $('.btn-analyze').hide();
            situation = {
                status: 'Cancelado',
                reason: data.cancelled_reason,
                name: data.who_excute_action,
                time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
            };
        }
        html += `<table class="table table-bordered table-view">
                        <tbody>
                        <tr>
                            <td rowspan="4" style="text-align: center;">
                                <img src="/media/logo.png" height="50" alt="" style="padding: 2px;">
                            </td>
                            <td colspan="8" rowspan="3" class="td-text-center td-font-17 td-bold">
                                CONTROLE DE ${data.is_entry_exit === 1 ? 'ENTRADA' : 'SAÍDA'}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-font-14">
                                <b>TIPO:</b> VEÍCULOS
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-font-14">
                                <b>DATA:</b> ${getDateFormat(data.date_hour, true)} <b>HORA:</b> ${getHourFormat(data.date_hour)}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9" class="td-font-14 td-text-white td-text-center td-color-red td-bold">

                            </td>
                            <td class="td-font-14">
                                <b>CÓDIGO:</b> ${data.code}
                            </td>
                        </tr>
                        ${restriction}
                        ${reason}
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                CONDUTOR
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                NOME COMPLETO:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.request_user.full_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                MATRICULA:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.request_user.r_code}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                CARGO:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.request_office}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                SETOR:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.sector_name}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                VEÍCULO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                PLACA:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${data.entry_exit_rent_vehicle.registration_plate}
                            </td>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                COR:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${data.entry_exit_rent_vehicle.color}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                KM REGISTRADO:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.km}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                APROVADOR
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                NOME COMPLETO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze.full_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                MATRICULA:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze.r_code}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                CARGO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze.office}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                SETOR:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze.sector_name}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                SITUAÇÃO DA SOLICITAÇÃO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                STATUS:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.status}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                PORTARIA:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.logistics_entry_exit_gate ? data.logistics_entry_exit_gate.name : 'N/A'}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                VIGILANTE/COLABORADOR:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                DATA E HORÁRIO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.time}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                MOTIVO DA AÇÃO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.reason}
                            </td>
                        </tr>
                        </tbody>
                    </table>`;
        $('#request').html(html);
        $('.modal-view').modal();
    }

    function getDateFormat(value, has_year = false){
        var date = new Date(value);
        var day = date.getDate().toString();
        var dayF = (day.length == 1) ? '0'+day : day;
        var month = (date.getMonth()+1).toString();
        var monthF = (month.length == 1) ? '0'+month : month;

        if (has_year)
            return dayF+'/'+monthF+'/'+date.getFullYear();
        else
            return dayF+'/'+monthF;
    }

    function getHourFormat(value) {
        var date = new Date(value);
        var hour = date.getHours();
        var hourF = hour < 10 ? '0'+hour : hour;
        var minutes = date.getMinutes();
        var minutesF = minutes < 10 ? '0'+minutes : minutes;
        return  hourF+':'+minutesF;
    }

    function showEdit(elem) {

            if (elem) {
                let json_row = JSON.parse($(elem).attr("json-data"));
                $("#id").val(json_row.id);
                $('input[name="registration_plate"]').val(json_row.registration_plate);
                $('input[name="color"]').val(json_row.color);
                $('input[name="km"]').val(json_row.km);
                if (json_row.is_active == 1)
                    $('select[name="is_active"]').val(1);
                else
                    $('select[name="is_active"]').val(2);

                $('.itemTitle').html('Editando veículo');
            } else {
                $("#id").val(0);
                $('input[name="registration_plate"]').val('');
                $('input[name="color"]').val('');
                $('input[name="km"]').val(0);
                $('select[name="is_active"]').val(1);
                $('.itemTitle').html('Novo veículo');
            }

            $("#modal-update").modal();

        }
        function Delete(id) {
            Swal.fire({
                title: '<?= __('news_i.la_11') ?>',
                text: "<?= __('news_i.la_12') ?>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= __('trip_i.tn_fly_toast_yes') ?>',
                cancelButtonText: '<?= __('trip_i.tn_fly_toast_no') ?>',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        block();
                        window.location.href = "/blog/author/delete/" + id;
                    }
                })

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
        $(".select2").select2({
            placeholder: 'Nome ou matricula...',
            maximumSelectionLength: 1,
            language: "pt-BR",
            ajax: {
                url: '/adm/entry-exit/misc/users/general',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                }
            }
        });
        $(".select23").select2({
            placeholder: 'Placa...',
            maximumSelectionLength: 1,
            language: "pt-BR",
            ajax: {
                url: '/adm/entry-exit/misc/rent/vehicles',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                }
            }
        });
        $(".select22").select2({
            placeholder: 'Nome ou matricula...',
            maximumSelectionLength: 1,
            language: "pt-BR",
            ajax: {
                url: '/adm/entry-exit/misc/users',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                }
            }
        });
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
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

        $("#a_update_form").submit(function (e) {
            block();

        });

        setInterval(() => {
                    $("#mAdmin").addClass('sidebar-group-active active');
                    $("#mEntryExit").addClass('sidebar-group-active active');
                    $("#mEntryExitVehicles").addClass('active');
                }, 100);

    });
    </script>
@endsection
