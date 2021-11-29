@extends('gree_i.layout')

@section('content')
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">RH - Minhas horas extras</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de horas extras
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="<?= Request::url() ?>" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-4">
                        <label for="users-list-verified">Data inicial</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control date-mask js-flatpickr js-flatpickr-enabled flatpickr-input" placeholder="0000-00-00" value="{{Session::get('filter_start_date')}}" name="start_date" id="start_date">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-4">
                        <label for="users-list-verified">Data final</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control date-mask js-flatpickr js-flatpickr-enabled flatpickr-input" placeholder="0000-00-00" value="{{Session::get('filter_end_date')}}" name="end_date" id="end_date">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
					<div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" name="export" value="1" class="btn btn-success btn-block glow users-list-clear mb-0">Exportar</button>
                    </div>
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Data</th>
                                            <th>Hora inicial</th>
                                            <th>Hora final</th>
                                            <th>Total</th>
                                            <th>status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($itens as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td>{{date('d/m/Y', strtotime($key->start_date))}}</td>
                                            <td>{{date('H:i', strtotime($key->start_date))}}</td>
                                            <td>{{date('H:i', strtotime($key->end_date))}}</td>
                                            @php ($start_date = new \DateTime($key->start_date)) @endphp
                                            @php ($since_start = $start_date->diff(new \DateTime($key->end_date))) @endphp
                                            <td>{{$since_start->h}}:{{$since_start->i}}</td>
                                            <td>
                                                <?php if ($key->is_approv == 1) { ?>
                                                    <span class="badge badge-light-success">Aprovado</span>
                                                <?php } else if ($key->is_reprov == 1) { ?>
                                                    <span class="badge badge-light-danger">Reprovado</span>
                                                <?php } else if ($key->is_cancelled == 1) { ?>
                                                    <span class="badge badge-light-danger">Cancelado</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-warning">Em análise</span>
                                                <?php }  ?>
                                            </td>
                                            <td id="action">
                                                @if ($key->is_cancelled == 0)
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <?php if ($key->is_cancelled == 0 and $key->is_approv == 0 and $key->is_reprov == 0) { ?>
                                                        <a class="dropdown-item" onclick="cancelrq({{$key->id}})" href="javascript:void(0)"><i class="bx bx-x mr-1"></i> Cancelar</a>
                                                        <?php } ?>
                                                        <a onclick="seeAnalyzes(this);" json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovação</a>
                                                        @if ($key->attach)
                                                        <a class="dropdown-item" href="{{$key->attach}}"><i class="bx bx-file mr-1"></i> Ver arquivo</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $itens->appends([
                                            'start_date' => Session::get('filter_start_date'),
                                            'end_date' => Session::get('filter_end_date')
                                        ])->links(); ?>
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

<div class="customizer d-none d-md-block" id="ActiveTraine">
<a class="customizer-toggle" href="#"><i class="bx bx-question-mark white"></i></a>
</div>

<div class="modal fade" id="modal-hist" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Histórico de aprovação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body body-ap">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function seeAnalyzes(elem) {

        let json_row = JSON.parse($(elem).attr("json-data"));

        var html = '<p>'+json_row.mng_obs+'</p>'
        if (json_row.is_approv == 1)
            html += '<p><b>'+json_row.manager.short_name+': </b> Aprovou a solicitação</p>';
        else if (json_row.is_reprov == 1)
            html += '<p><b>'+json_row.manager.short_name+': </b> Aprovou a solicitação</p>';
        else
            html += '<p>Ainda não foi análisada...</p>';

        $('.body-ap').html(html);

        $('#modal-hist').modal();
    }
    function cancelrq(id) {

        var r = confirm("Você está prestes a cancelar sua solicitação, deseja continuar?");
        if (r == true) {
            window.location.href = '/hour-extra/cancel/'+id;
        }
    }
    $(document).ready(function () {

        $('.date-mask').pickadate({
            //editable: true,
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
            $("#mRH").addClass('sidebar-group-active active');
            $("#mHourExtra").addClass('sidebar-group-active active');
            $("#mHourExtraMy").addClass('active');
        }, 100);

    });
    </script>
@endsection
