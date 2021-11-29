@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/pages/widgets.min.css">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_trip_view') }}</h5>
                  <div class="breadcrumb-wrapper col-12">
                    {{ __('layout_i.menu_trip_view_subtitle') }}
                  </div>
                </div>
              </div>
            </div>
          </div>
    
    <div class="content-header row">
    </div>
    <div class="content-body">
        <section id="widgets-Statistics">
        <div class="row">
            <div class="col-xl-3 col-md-4 col-sm-6 cursor-pointer" onclick="location.href = '/trip/view/all'">
              <div class="card text-center">
                <div class="card-content">
                  <div class="card-body">
                    <p class="text-muted mb-0 line-ellipsis">{{ __('trip_i.tntp_block_total') }}</p>
                    <h2 class="mb-0"><?= $total ?></h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 cursor-pointer" onclick="location.href = '/trip/view/all?end_date=<?= date('Y-m-d', strtotime(date('Y-m-d'). ' + 3 day')) ?>&status=2'">
              <div class="card text-center">
                <div class="card-content">
                  <div class="card-body">
                    <p class="text-muted mb-0 line-ellipsis">{{ __('trip_i.tntp_block_rest_3') }}</p>
                    <h2 class="mb-0"><?= $left3 ?></h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 cursor-pointer" onclick="location.href = '/trip/view/all?end_date=<?= date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 day')) ?>&status=2'">
              <div class="card text-center">
                <div class="card-content">
                  <div class="card-body">
                    <p class="text-muted mb-0 line-ellipsis">{{ __('trip_i.tntp_block_rest_7') }}</p>
                    <h2 class="mb-0"><?= $left7 ?></h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 cursor-pointer" onclick="location.href = '/trip/view/all?end_date=<?= date('Y-m-d', strtotime(date('Y-m-d'). ' + 8 day')) ?>&status=2'">
              <div class="card text-center">
                <div class="card-content">
                  <div class="card-body">
                    <p class="text-muted mb-0 line-ellipsis">{{ __('trip_i.tntp_block_news') }}</p>
                    <h2 class="mb-0"><?= $news ?></h2>
                  </div>
                </div>
              </div>
            </div>
        </section>
        <div class="users-list-filter px-1">
            <form action="/trip/view/all" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-6 col-lg-3">
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
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="start_date">{{ __('trip_i.tntp_date_begin') }}</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="start_date" id="start_date" value="<?= Session::get('tripf_start_date') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="users-list-verified">{{ __('trip_i.tntp_date_end') }}</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="end_date" id="end_date" value="<?= Session::get('tripf_end_date') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="users-list-verified">{{ __('trip_i.tntp_status') }}</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status">
                                <option value=""></option>
                                <option value="1" <?php if (1 == Session::get('tripf_status')){ echo "selected"; } ?>>{{ __('trip_i.tntp_status_0') }}</option>
                                <option value="2" <?php if (2 == Session::get('tripf_status')){ echo "selected"; } ?>>{{ __('trip_i.tntp_status_1') }}</option>
                                <option value="3" <?php if (3 == Session::get('tripf_status')){ echo "selected"; } ?>>{{ __('trip_i.tntp_status_2') }}</option>
                                <option value="4" <?php if (4 == Session::get('tripf_status')){ echo "selected"; } ?>>{{ __('trip_i.tntp_status_3') }}</option>
                                <option value="5" <?php if (5 == Session::get('tripf_status')){ echo "selected"; } ?>>{{ __('trip_i.tntp_status_4') }}</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('trip_i.tntp_search_filter') }}</button>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6 d-flex align-items-center">
                        <button type="button" onclick="resetForm();" class="btn btn-danger btn-block glow users-list-clear mb-0">{{ __('trip_i.tntp_clean_filter') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- users list start -->
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <form action="/trip/analyze/update" id="approvaltrip" method="post">
                            <div class="table-responsive">
                                <table id="list-datatable" class="table table-transparent">
                                    <thead>
                                        <tr>
                                            <th>{{ __('trip_i.tntp_id') }}</th>
                                            <th>{{ __('trip_i.tntp_colaborate') }}</th>
                                            <th>{{ __('trip_i.tntp_origin') }}</th>
                                            <th>{{ __('trip_i.tntp_destiny') }}</th>
                                            <th>{{ __('trip_i.tntp_situation') }}</th>
                                            <th>{{ __('trip_i.tmtp_actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trips as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td>
                                                <a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a>
                                            </td>
                                            <td>
                                                <b><?= GetCountryName($key->origin_country) ?>:</b> <?= GetStateName($key->origin_country, $key->origin_state) ?>
                                                <br><b>{{ __('trip_i.tntp_going') }}</b> <?= date('Y-m-d', strtotime($key->origin_date)) ?>
                                            </td>
                                            <td>
                                                <b><?= GetCountryName($key->destiny_country) ?>:</b> <?= GetStateName($key->destiny_country, $key->destiny_state) ?>
                                                <br><b>{{ __('trip_i.tntp_arrived') }}</b> <?= date('Y-m-d', strtotime($key->destiny_date)) ?>
                                            </td>
                                            <td>
                                                <?php if ($key->is_cancelled == 1) { ?>
                                                    <span class="badge badge-light-danger">Cancelado</span>
                                                <?php } else if ($key->is_completed == 1) { ?>
                                                    <span class="badge badge-light-primary">{{ __('trip_i.tntp_status_0') }}</span>
                                                <?php } else if ($key->is_approv == 1) { ?>
                                                    <span class="badge badge-light-success">{{ __('trip_i.tntp_status_1') }}</span>
                                                <?php } else if ($key->is_reprov == 1) { ?>
                                                    <span class="badge badge-light-danger">{{ __('trip_i.tntp_status_2') }}</span>
                                                <?php } else if ($key->has_analyze == 1) { ?>
                                                    <span class="badge badge-light-warning">{{ __('trip_i.tntp_status_3') }}</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-info">{{ __('trip_i.tntp_status_4') }}</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <?php if ($key->is_completed == 1) { ?>
                                                        <?php $ticket = App\Model\TripAgencyBudget::where('trip_plan_id', $key->id)->where('is_approv', 1)->first(); ?>
                                                        <?php if (isset($ticket)) { ?>
                                                        <a href="<?= $ticket->budget_url ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bxs-coupon mr-1"></i> {{ __('layout_i.op_ticket') }}</a>
                                                        <a href="<?= $ticket->budget_url ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-building-house mr-1"></i> {{ __('layout_i.op_hotel') }}</a>
                                                        <?php } } ?>
                                                        <?php if ($key->is_approv == 1 and hasPermManager(1)) { ?>
                                                        <a href="/trip/edit/route/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i> {{ __('layout_i.op_edit') }}</a>
                                                        <?php } ?>
                                                        <?php if ($key->is_completed == 0 and hasPermManager(1)) { ?>
                                                            <a href="/trip/cancel/route/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-transfer-alt mr-1"></i> @if($key->is_cancelled == 1) Ativar rota @else Cancelar @endif</a>
                                                        <?php } ?>
                                                        <a href="/trip/review/<?= $key->id ?>" class="dropdown-item"><i class="bx bx-detail mr-1"></i> {{ __('trip_i.action_details') }}</a>
                                                    </div>
                                                </div>
                                                
                                                
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $trips->appends(['rcode' => Session::get('tripf_r_code'), 'sdate' => Session::get('tripf_start_date'), 'edate' => Session::get('tripf_end_date'), 'status' => Session::get('tripf_status')])->links(); ?>
                                    </ul>
                                </nav>
                            </div>
                            </form>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>
</div>

    <script>
        function resetForm() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#status').val('');
            $('.js-select2').val(0).trigger("change");
          }
$(document).ready(function () {
    $(".js-select2").select2({
        maximumSelectionLength: 1,
    });
    <?php if (!empty(Session::get('tripf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('tripf_r_code') ?>']).trigger('change');
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
    <?php if (empty(Session::get('tripf_start_date'))) { ?>
        $('#start_date').val('');
    <?php } ?>
    <?php if (empty(Session::get('tripf_end_date'))) { ?>
        $('#end_date').val('');
    <?php } ?>
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
            $("#mTrip").addClass('sidebar-group-active active');
            $("#mTripViewApprov").addClass('active');
        }, 100);
        
});
</script>
@endsection