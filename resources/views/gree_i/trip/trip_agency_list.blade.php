@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_trip_agency') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('layout_i.menu_trip_agency_subtitle') }}
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
                                            <th>#</th>
                                            <th>{{ __('trip_i.tav_name') }}</th>
                                            <th>{{ __('trip_i.tav_email') }}</th>
                                            <th>{{ __('trip_i.tav_createdat') }}</th>
                                            <th>{{ __('trip_i.tav_last_activity') }}</th>
                                            <th class="text-center">{{ __('trip_i.tav_action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($agency as $key) { ?>
                                        <tr>
                                            <td>
                                                <?= $key->id ?>
                                            </td>
                                            <td>
                                                <?= $key->name ?>
                                            </td>
                                            <td>
                                                <?= $key->email ?>
                                            </td>
                                            <td>
                                                <?= date('Y-m-d H:i', strtotime($key->created_at)) ?>
                                            </td>
                                            <td>
                                                <?php $log = App\Model\TripAgencyBudget::where('agency_id', $key->id)->orderBy('created_at', 'DESC')->first(); ?>
                                                <?php if ($log) { ?>
                                                <?= date('Y-m-d H:i', strtotime($log->created_at)) ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a onclick="Edit(<?= $key->id ?>, '<?= $key->name ?>', '<?= $key->email ?>', 0)" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i> {{ __('layout_i.op_edit') }}</a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $agency->render(); ?>
                                        
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

    <div class="mb-2" style="width: 390px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
        <button type="button" onclick="Edit(0, '', '', 1)" class="btn btn-sm btn-secondary">{{ __('trip_i.tav_add') }}</button>
    </div>
</div>

<div class="modal fade text-left" id="modal-update" tabindex="-1" role="dialog" aria-labelledby="modal-update" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark white">
          <span class="modal-title agencyTitle" id="modal-update"></span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <form class="push" id="a_update_form" method="POST" action="/trip/agency/update">
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="id" name="id">
                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="name">{{ __('trip_i.tav_enter_name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="...">
                    </fieldset>
                </div>
                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="email">{{ __('trip_i.tav_enter_email') }}</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="...">
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">{{ __('trip_i.tav_close') }}</span>
            </button>
          <button type="submit" class="btn btn-dark ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('trip_i.tav_update_agency') }}</span>
          </button>
        </div>
        </form>
      </div>
    </div>
  </div>

    <script>
        function Edit(id, name, email, isnew) {
            if (isnew == 1) {
                $(".agencyTitle").html("<?= __('trip_i.tav_add_agency') ?>");
            } else {
                $(".agencyTitle").html("<?= __('trip_i.tav_edit_agency') ?>");
            }
            $("#name").val(name);
            $("#email").val(email);
            $("#id").val(id);

            $("#modal-update").modal();

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

        $("#a_update_form").submit(function (e) { 

        if ($("#name").val() == "") {

            e.preventDefault();
            error('<?= __('trip_i.tav_error_name') ?>');
            return
        } else if ($("#email").val() == "") {

            e.preventDefault();
            error('<?= __('trip_i.tav_error_email') ?>');
            return
        }
        block();


        });

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mTrip").addClass('sidebar-group-active active');
            $("#mTripAgency").addClass('active');
        }, 100);

    });
    </script>
@endsection