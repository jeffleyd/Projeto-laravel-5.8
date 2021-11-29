@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('trip_i.tmp_title') }}: #<?= $planid ?></h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('trip_i.tmp_subtitle') }}
              </div>
            </div>
          </div>
        </div>
      </div>

<div class="alert alert-danger alert-dismissible mb-2" role="alert">
<div class="d-flex align-items-center">
    <i class="bx bx-error"></i>
    <span>
    {{ __('trip_i.tmp_ps_desc_1') }}
    <br>{{ __('trip_i.tmp_ps_desc_2') }}
    </span>
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
                                            <th class="text-center"></th>
                                            <th>{{ __('trip_i.tmptp_reason') }}</th>
                                            <th>{{ __('trip_i.tmptp_orin') }}</th>
                                            <th>{{ __('trip_i.tmptp_destiny') }}</th>
                                            <th>{{ __('trip_i.tmptp_hotel') }}</th>
                                            <th>{{ __('trip_i.tmptp_dispatch') }}</th>
                                            <th>{{ __('trip_i.tmptp_peoples') }}</th>
                                            <th>{{ __('trip_i.tmptp_situation') }}</th>
                                            <th>{{ __('trip_i.tmptp_actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trips as $key) { ?>
                                        <tr>
                                            <td class="text-center"><i class="bx bx-down-arrow-alt"></i></td>
                                            <td><?= $key->goal ?></td>
                                            <td>
                                                <small><i><?= date('Y-m-d', strtotime($key->origin_date)) ?></i></small>
                                                <br><span class="font-w600">UF: <?= GetStateName($key->origin_country, $key->origin_state) ?></span>
                                                <div class="text-muted"><?= $key->origin_city ?></div>
                                            </td>
                                            <td>
                                                <small><i><?= date('Y-m-d', strtotime($key->destiny_date)) ?></i></small>
                                                <br><span class="font-w600">UF: <?= GetStateName($key->destiny_country, $key->destiny_state) ?></span>
                                                <div class="text-muted"><?= $key->destiny_city ?></div>
                                            </td>
                                            <td><?php if ($key->has_hotel == 1) { echo __('trip_i.tmp_yes'); } else { echo __('trip_i.tmp_no'); } ?></td>
                                            <td><?= $key->dispatch ?></td>
                                            <td><?= $key->peoples + 1 ?></td>
                                            <td>
                                                <?php if ($key->is_completed == 1) { ?>
                                                    <span class="badge badge-light-info">{{ __('trip_i.tmptp_status_0') }}</span></td>
                                                <?php } else if ($key->is_cancelled == 1) { ?>
                                                    <span class="badge badge-light-danger">{{ __('trip_i.tntp_status_6') }}</span></td>
                                                <?php } else if ($key->is_approv == 1) { ?>
                                                    <span class="badge badge-light-success">{{ __('trip_i.tmptp_status_1') }}</span></td>
                                                <?php } else if ($key->is_reprov == 1) { ?>
                                                    <span class="badge badge-light-danger">{{ __('trip_i.tmptp_status_2') }}</span></td>
                                                <?php } else if ($key->has_analyze == 1 && $key->has_suspended == 0) { ?>
                                                    <span class="badge badge-light-warning">{{ __('trip_i.tmptp_status_3') }}</span></td>
                                                <?php } else if ($key->has_analyze == 1 && $key->has_suspended == 1) { ?>    
                                                    <span class="badge badge-light-warning">{{ __('trip_i.tmptp_status_5') }}</span></td>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-secondary">{{ __('trip_i.tmptp_status_4') }}</span></td>
                                                <?php } ?>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <?php if ($key->is_approv == 0 and $key->has_analyze == 0) { ?>
                                                        <a onclick="Approv(<?= $planid ?>,<?= $key->id ?>)" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-show-alt mr-1"></i> {{ __('trip_i.action_send_approve') }}</a>
                                                        <?php } ?>
                                                        <a href="/trip/review/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-detail mr-1"></i> {{ __('trip_i.action_details') }}</a>
                                                        <a onclick="rtd_analyzes(<?= $key->id ?>, 'App\\Model\\TripPlan');" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
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

@include('gree_i.misc.components.analyze.history.view')

<script>
    @include('gree_i.misc.components.analyze.history.script')

    function Approv(idt, index) {
        Swal.fire({
            title: '<?= __('trip_i.tmp_approv') ?>',
            text: "<?= __('trip_i.tmp_confirm') ?>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
            cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    window.location.href = "/trip/request/approv/" + idt +"/"+ index;
                }
            })
    }
    $(document).ready(function () {
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
        }, 100);

    });
    </script>
@endsection