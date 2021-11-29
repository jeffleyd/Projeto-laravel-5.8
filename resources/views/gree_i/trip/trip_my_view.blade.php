@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_trip_my') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('layout_i.menu_trip_my_subtitle') }}
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
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#{{ __('trip_i.tmtp_id') }}</th>
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
                                            <td id="action">
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
                                        <?= $trips->render(); ?>
                                        
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
    
    <script src="/js/uxTour.js"></script>
    <script>
    // STEPS
    var uxTour = new uxTour({
        buttonText: '<?= __('training_i.continue') ?> <i class="bx bx-right-arrow-alt" style="position: relative;top: 3px;left: 4px;"></i>',
        clickClose: 'true',
    });
    var tour = {
        steps: [
            {element: 'action', text: '<?= __('training_i.options') ?>'},
        ]   
    };
    $(document).ready(function () {
        $("#ActiveTraine").click(function (e) { 
            uxTour.start(tour);
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
            $("#mTrip").addClass('sidebar-group-active active');
            $("#mTripMy").addClass('active');
        }, 100);

    });
    </script>
@endsection