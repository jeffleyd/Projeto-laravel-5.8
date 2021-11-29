@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_trip_all') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('layout_i.menu_trip_all_subtitle') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/trip/all" id="searchTrip" method="GET">
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
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label for="start_date">{{ __('trip_i.tntp_date_begin') }}</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="start_date" id="start_date" value="<?= Session::get('tripf_start_date') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label for="users-list-verified">{{ __('trip_i.tntp_date_end') }}</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="end_date" id="end_date" value="<?= Session::get('tripf_end_date') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('trip_i.tntp_search_filter') }}</button>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-2 d-flex align-items-center">
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
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#{{ __('trip_i.tmtp_id') }}</th>
                                            <th>{{ __('trip_i.tmtp_collaborator') }}</th>
                                            <th>{{ __('trip_i.tmtp_route') }}</th>
                                            <th>{{ __('trip_i.tmtp_dispatch') }}</th>
                                            <th>{{ __('trip_i.tmtp_startin') }}</th>
                                            <th>{{ __('trip_i.tmtp_endin') }}</th>
                                            <th>{{ __('trip_i.tmtp_status') }}</th>
                                            <th>{{ __('trip_i.tmtp_actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trips as $key) { ?>
                                        <tr>
                                            <td class="text-center"><b><?= $key->id ?></b></td>
                                            <td>
                                                <a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a>
                                            </td>
                                            <td>
                                                <?= $key->routes ?>
                                            </td>
                                            <td>
                                                <?= $key->dispatch ?>
                                            </td>
                                            <td>
                                                <?php $startin = App\Model\TripPlan::where('trip_id', $key->id)->orderBy('id', 'ASC')->first(); ?>
                                                @if ($startin)
                                                <?= date('Y-m-d', strtotime($startin->origin_date)) ?>
                                                @endif
                                            </td>
                                            <td>
                                                <?php $endin = App\Model\TripPlan::where('trip_id', $key->id)->orderBy('id', 'DESC')->first(); ?>
                                                @if ($endin)
                                                <?= date('Y-m-d', strtotime($endin->destiny_date)) ?>
                                                @endif
                                            </td>
                                            <td>
                                                <?php if (App\Model\TripPlan::where('trip_id', $key->id)->where('is_completed', 0)->count() > 0) { ?>
                                                    <span class="badge badge-light-info">{{ __('trip_i.tmtp_status_pending') }}</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-success">{{ __('trip_i.tmtp_status_complete') }}</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="/trip/detail/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-map-pin mr-1"></i> {{ __('layout_i.op_see_routes') }}</a>
                                                        <a href="/trip/edit/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i> {{ __('layout_i.op_edit') }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $trips->appends(['rcode' => Session::get('tripf_r_code'), 'sdate' => Session::get('tripf_start_date'), 'edate' => Session::get('tripf_end_date')])->links(); ?>
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

    <script>
        function resetForm() {
            $('#start_date').val('');
            $('#end_date').val('');
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
            $("#mTripAll").addClass('active');
        }, 100);

    });
    </script>
@endsection