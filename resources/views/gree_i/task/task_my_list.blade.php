@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/pages/widgets.min.css">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_project') }}</h5>
                  <div class="breadcrumb-wrapper col-12">
                    {{ __('layout_i.menu_project_my') }}
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
            <div class="col-xl-3 col-md-4 col-sm-6 cursor-pointer">
              <div class="card text-center">
                <div class="card-content">
                  <div class="card-body">
                    <p class="text-muted mb-0 line-ellipsis">{{ __('project_i.block_3') }}</p>
                    <h2 class="mb-0"><?= $total ?></h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 cursor-pointer">
              <div class="card text-center">
                <div class="card-content">
                  <div class="card-body">
                    <p class="text-muted mb-0 line-ellipsis">{{ __('project_i.block_1') }}</p>
                    <h2 class="mb-0"><?= $completed ?></h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 cursor-pointer">
              <div class="card text-center">
                <div class="card-content">
                  <div class="card-body">
                    <p class="text-muted mb-0 line-ellipsis">{{ __('project_i.block_4') }}</p>
                    <h2 class="mb-0"><?= $late ?></h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 cursor-pointer">
              <div class="card text-center">
                <div class="card-content">
                  <div class="card-body">
                    <p class="text-muted mb-0 line-ellipsis">{{ __('project_i.block_2') }}</p>
                    <h2 class="mb-0"><?= $progress ?></h2>
                  </div>
                </div>
              </div>
            </div>
        </section>
        <div class="users-list-filter px-1">
            <form action="/task/view/my" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-3 col-lg-3">
                        <label for="users-list-verified">{{ __('project_i.mp_26') }}</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="sector" name="sector">
                                <option value=""></option>
                                <?php foreach ($sector as $key) { ?>
                                <option value="<?= $key->id ?>" <?php if ($key->id == Session::get('taskf_sector')){ echo "selected"; } ?> ><?= __('layout_i.'. $key->name .'') ?></option>
                                <?php } ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-3 col-lg-3">
                        <label for="users-list-verified">{{ __('project_i.mp_25') }}</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="rcodes" name="rcodes">
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-3 col-lg-3">
                        <label for="users-list-verified">{{ __('project_i.mp_10') }}</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status">
                                <option value=""></option>
                                <option value="1" <?php if (1 == Session::get('taskf_status')){ echo "selected"; } ?>>{{ __('project_i.ee_11') }}</option>
                                <option value="2" <?php if (2 == Session::get('taskf_status')){ echo "selected"; } ?>>{{ __('project_i.ee_14') }}</option>
                                <option value="3" <?php if (3 == Session::get('taskf_status')){ echo "selected"; } ?>>{{ __('project_i.ee_13') }}</option>
                                <option value="4" <?php if (4 == Session::get('taskf_status')){ echo "selected"; } ?>>{{ __('project_i.ee_15') }}</option>
                                <option value="5" <?php if (5 == Session::get('taskf_status')){ echo "selected"; } ?>>{{ __('project_i.ee_16') }}</option>
                                <option value="6" <?php if (6 == Session::get('taskf_status')){ echo "selected"; } ?>>{{ __('project_i.ee_18') }}</option>
                                <option value="7" <?php if (7 == Session::get('taskf_status')){ echo "selected"; } ?>>{{ __('project_i.ee_17') }}</option>
                                <option value="8" <?php if (8 == Session::get('taskf_status')){ echo "selected"; } ?>>{{ __('project_i.ee_12') }}</option>
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-3 col-lg-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('project_i.ep_3') }}</button>
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
                                <table id="list-datatable" class="table table-transparent">
                                    <thead>
                                        <tr>
                                            <th>{{ __('project_i.mp_6') }}</th>
                                            <th>{{ __('project_i.mp_24') }}</th>
                                            <th>{{ __('project_i.mp_25') }}</th>
                                            <th>{{ __('project_i.mp_7') }}</th>
                                            <th>{{ __('project_i.mp_8') }}</th>
                                            <th>{{ __('project_i.mp_9') }}</th>
                                            <th>{{ __('project_i.mp_10') }}</th>
                                            <th>{{ __('project_i.mp_12') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($task as $key) { ?>
                                        <tr>
                                            <td><?= $key->title ?></td>
                                            <td>
                                                <?php $boss = App\Model\Users::where('r_code', $key->r_code)->first(); ?>
                                                <a target="_blank" href="/user/view/<?= $boss->r_code ?>"><?= getENameF($key->r_code); ?></a>
                                            </td>
                                            <td>
                                                <a target="_blank" href="/user/view/<?= $key->users_r_code ?>"><?= getENameF($key->users_r_code); ?></a>
                                            </td>
                                            <td>
                                                <?= date('Y-m-d', strtotime($key->start_date)) ?>
                                            </td>
                                            <td>
                                                <?= date('Y-m-d', strtotime($key->end_date)) ?>
                                            </td>
                                            <td><?= __('layout_i.'. $key->sector_name .'') ?></td>
                                            <td>
                                                <?php if ($key->is_cancelled == 1) { ?>
                                                    <span class="badge badge-light-danger">{{ __('project_i.ee_18') }}</span>
                                                <?php } else if ($key->is_completed == 1) { ?>
                                                    <span class="badge badge-light-success">{{ __('project_i.ee_11') }}</span>
                                                <?php } else if ($key->has_analyze == 1 and $key->is_accept == 1 and $key->is_completed == 0) { ?>
                                                    <span class="badge badge-light-warning">{{ __('project_i.ee_12') }}</span>
                                                <?php } else if ($key->is_accept == 1 and date('Y-m-d') >= date('Y-m-d', strtotime($key->end_date))) { ?>
                                                    <span class="badge badge-light-secondary">{{ __('project_i.ee_13') }}</span>
                                                <?php } else if ($key->is_accept == 1 and date('Y-m-d') >= date('Y-m-d', strtotime($key->start_date))) { ?>
                                                    <span class="badge badge-light-primary">{{ __('project_i.ee_14') }}</span>
                                                <?php } else if ($key->is_accept == 1) { ?>
                                                    <span class="badge badge-light-success">{{ __('project_i.ee_15') }}</span>
                                                <?php } else if ($key->is_recuse == 1) { ?>
                                                    <span class="badge badge-light-danger">{{ __('project_i.ee_16') }}</span>
                                                <?php } else if ($key->has_analyze == 1) { ?>
                                                    <span class="badge badge-light-warning">{{ __('project_i.ee_17') }}</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="/task/view/history/<?= $key->id ?>" class="dropdown-item"><i class="bx bx-detail mr-1"></i> <?= __('layout_i.op_history') ?></a>
                                                        <?php if ($key->has_analyze == 0 and $key->r_code == Session::get('r_code')) { ?>
                                                            <a href="/task/<?= $key->id ?>" class="dropdown-item"><i class="bx bx-edit-alt mr-1"></i> <?= __('layout_i.op_edit') ?></a>
                                                            <?php if ($key->is_completed == 0) { ?>
                                                                <?php if ($key->is_cancelled == 0) { ?>
                                                                    <a href="javascript:void(0)" onclick="status(<?= $key->id ?>)" class="dropdown-item"><i class="bx bx-lock-alt mr-1"></i> <?= __('layout_i.op_inactive') ?></a>
                                                                <?php } else { ?>
                                                                    <a href="javascript:void(0)" onclick="status(<?= $key->id ?>)" class="dropdown-item"><i class="bx bx-lock-open-alt mr-1"></i> <?= __('layout_i.op_active') ?></a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>                                                
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $task->appends(['sector' => Session::get('taskf_sector'), 'rcodes' => Session::get('taskf_rcodes'), 'status' => Session::get('taskf_status')])->links(); ?>
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
        function status(index) {
            Swal.fire({
                title: '<?= __('project_i.msg_13') ?>',
                text: "<?= __('project_i.msg_14') ?>",
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
                        window.location.href = "/task/change/"+ index;
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

        <?php if (!empty(Session::get('taskf_sector'))) {?>
            $('#rcodes').load('/filter/task/users?sector='+ <?= Session::get('taskf_sector') ?>, function( response, status, xhr ) {
                    if ( status == "error" ) {

                        ErrorToast('<?= __('trip_i.etn_select_network_error') ?>');

                    } else {

                    }
                });
        <?php } ?>

        $('#sector').change(function(){
            val = $('#rcodes').load('/filter/task/users?sector='+$('#sector').val(), function( response, status, xhr ) {
                    if ( status == "error" ) {

                        ErrorToast('<?= __('trip_i.etn_select_network_error') ?>');

                    } else {

                    }
                });
        });

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mTask").addClass('sidebar-group-active active');
            $("#mTaskMy").addClass('active');
        }, 100);
        
});
</script>
@endsection