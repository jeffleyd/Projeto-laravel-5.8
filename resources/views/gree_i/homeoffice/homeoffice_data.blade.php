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
                {{ __('homeoffice_i.ld_01') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/home-office/data" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-4 col-lg-4">
                        <label for="users-list-verified">{{ __('trip_i.tntp_collaborator') }}</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                <option></option>
                                <?php foreach ($userall as $key) { ?>
                                    <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                <?php } ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-2 col-lg-2">
                        <label for="start_date">{{ __('trip_i.tntp_date_begin') }}</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="start_date" id="start_date" value="<?= Session::get('Homeofficef_start_date') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-2 col-lg-2">
                        <label for="users-list-verified">{{ __('trip_i.tntp_date_end') }}</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="end_date" id="end_date" value="<?= Session::get('Homeofficef_end_date') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-2 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">Filtrar</button>
                    </div>
                    <div class="col-12 col-sm-2 col-lg-2 d-flex align-items-center">
                        <button type="button" onclick="resetForm();" class="btn btn-danger btn-block glow users-list-clear mb-0">{{ __('trip_i.tntp_clean_filter') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body p-0">
                            <div class="d-lg-flex justify-content-between">
                                <div class="widget-card-details d-flex flex-column justify-content-between p-2">
                                    <div>
                                        <h5 class="font-medium-2 font-weight-normal"><?= $active ?> {{ __('homeoffice_i.ld_02') }} <?= $active_total ?></h5>
                                        <p class="text-muted">{{ __('homeoffice_i.ld_03') }}</p>
                                    </div>
                                </div>
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
                                        <h5 class="font-medium-2 font-weight-normal"><?= $cancel ?> ({{ __('homeoffice_i.ld_04') }})</h5>
                                        <p class="text-muted">{{ __('homeoffice_i.ld_05') }}</p>
                                    </div>
                                </div>
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
                                        <h5 class="font-medium-2 font-weight-normal"><?= round($hour[0]->result,2) ?> {{ __('homeoffice_i.ld_06') }}</h5>
                                        <p class="text-muted">{{ __('homeoffice_i.ld_07') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable1" class="table">
                                    <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>{{ __('homeoffice_i.ld_08') }}</th>
                                            <th>{{ __('homeoffice_i.ld_09') }}</th>
                                            <th>{{ __('homeoffice_i.ld_10') }}</th>
                                            <th>{{ __('homeoffice_i.ld_11') }}</th>
                                            <th>{{ __('homeoffice_i.ld_12') }}</th>
                                            <th>Anexo</th>
                                            <th>{{ __('homeoffice_i.ld_14') }}</th>
											<th>Relatório</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($task as $key) { ?>
                                            <tr>
                                                <td><?= $key->id ?></td>
                                                <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                                <td>
                                                    <?= date('Y-m-d H:i', strtotime($key->start_date)) ?>
                                                </td>
                                                <td>
                                                    <?php if ($key->end_date != '0000-00-00 00:00:00') { ?>
                                                    <?= date('Y-m-d H:i', strtotime($key->end_date)) ?>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?= date('Y-m-d H:i', strtotime($key->updated_at)) ?>
                                                </td>
                                                <td>
                                                    <?php if ($key->hour_extra == 1) { ?>
                                                        {{ __('homeoffice_i.ld_15') }}
                                                    <?php } else { ?>
                                                        {{ __('homeoffice_i.ld_16') }}
                                                    <?php } ?>
                                                </td>
                                                <td>
													@if ($key->attach)
                                                    <a target="_blank" href="<?= $key->attach ?>">{{ __('homeoffice_i.ld_17') }}</a>
													@endif
                                                </td>
                                                <td>
                                                    <?php if ($key->is_cancelled == 1) { ?>
                                                        <span class="badge badge-light-danger">{{ __('homeoffice_i.ld_18') }}</span>
                                                    <?php } else if ($key->end_date == '0000-00-00 00:00:00') { ?>
                                                        <span class="badge badge-light-info">{{ __('homeoffice_i.ld_19') }}</span>    
                                                    <?php } else { ?>
                                                        <span class="badge badge-light-success">{{ __('homeoffice_i.ld_20') }}</span>
                                                    <?php } ?>
                                                </td>
												<td>
												@if ($key->is_cancelled == 0 and $key->end_date != '0000-00-00 00:00:00')
                                                <button type="button" json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="showEdit(this)" class="btn btn-sm btn-dark round mr-1 mb-1">Ver mais</button>
												@endif
                                            </td>
                                            </tr>
                                            <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $task->appends(['r_code' => Session::get('Homeofficef_r_code'), 'start_date' => Session::get('Homeofficef_start_date'), 'end_date' => Session::get('Homeofficef_end_date')])->links(); ?>
                                        
                                    </ul>
                                </nav>
                            </div>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="modal fade text-left modal-borderless" id="hmmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Relatório Home Office</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body hmbody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
            </div>
        </div>
    </div>
</div>
    <script>
        function resetForm() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#status').val('');
            $('.js-select2').val(0).trigger("change");
          }
		function showEdit(elem) {

            $('.hmbody').html('');
            let json_row = JSON.parse($(elem).attr("json-data"));

            var body = '<p>'+json_row.description+'</p>';

            if (json_row.itens.length > 0) {

                body += '<div class="row">';
                body += '<div class="col-12 col-sm-12 col-md-4 ">';
                body += '<div class="list-group" role="tablist">';

                for (let i = 0; i < json_row.itens.length; i++) {
                    const row = json_row.itens[i];

                    var active = '';
                    if (i == 0)
                        active = 'active';

                    var position = i+1;
                    body += '<a class="list-group-item list-group-item-action '+active+'" id="list-'+position+'-list" data-toggle="list" href="#list-'+position+'" role="tab">'+row.subject+'</a>';

                }

                body += '</div>';
                body += '</div>';
                body += '<div class="col-12 col-sm-12 col-md-8 mt-1">';
                body += '<div class="tab-content text-justify" id="nav-tabContent">';

                for (let i = 0; i < json_row.itens.length; i++) {
                    const row = json_row.itens[i];

                    var active = '';
                    if (i == 0)
                        active = 'show active';

                    var position = i+1;
                    body += '<div class="tab-pane '+active+'" id="list-'+position+'" role="tabpanel" aria-labelledby="list-'+position+'-list">';
                    body += row.task;

                    if (row.result)
                        body += '<div class="bg-success" style="padding:5px; margin-top: 5px; color:white"><b>Resultado: </b>'+row.result+'</div>';

                    body += '</div>';

                }

                body += '</div>';

            }

            $('.hmbody').html(body);
            $('#hmmodal').modal();

        }
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('Homeofficef_r_code'))) { ?>
            $('.js-select2').val(['<?= Session::get('Homeofficef_r_code') ?>']).trigger('change');
        <?php } ?>
        $('#start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        $('#end_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        <?php if (empty(Session::get('Homeofficef_start_date'))) { ?>
            $('#start_date').val('');
        <?php } ?>
        <?php if (empty(Session::get('Homeofficef_end_date'))) { ?>
            $('#end_date').val('');
        <?php } ?>

        $('#list-datatable1').DataTable( {
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
            $("#mHomeOfficeData").addClass('active');
        }, 100);

    });
    </script>
@endsection