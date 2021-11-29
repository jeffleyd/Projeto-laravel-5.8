@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_news') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('news_i.lt_01') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/blog/transmission" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-10">
                        <label for="email">{{ __('news_i.lt_02') }}</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="email" value="<?= Session::get('blogf_email') ?>" placeholder="exemplo@gree-am.com.br">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
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
                                            <th>#</th>
                                            <th>{{ __('news_i.lt_02') }}</th>
                                            <th>{{ __('news_i.lt_04') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td><?= $key->email ?></td>
                                            <td id="action">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a onclick="Edit(<?= $key->id ?>, '<?= $key->email ?>', 0)" href="javascript:void(0)" class="dropdown-item"><i class="bx bx-edit-alt mr-1"></i> {{ __('layout_i.op_edit') }}</a>
                                                        <a  onclick="Delete(<?= $key->id ?>)" class="dropdown-item"><i class="bx bx-x mr-1"></i> {{ __('layout_i.op_delete') }}</a>
                                                        
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $list->render(); ?>
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

<div class="mb-2" style="width: 390px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
    <button type="button" onclick="Edit(0, '', 1)" class="btn btn-sm btn-secondary">{{ __('news_i.lt_05') }}</button>
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
        <form class="push" id="a_update_form" method="POST" action="/blog/transmission/update">
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="id" name="id">
                <div class="col-sm-12">
                    <label for="email">{{ __('news_i.lt_02') }}</label>
                    <fieldset class="form-group">
                        <input type="email" class="form-control" name="email" id="email" value="" required>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">{{ __('news_i.lt_06') }}</span>
            </button>
          <button type="submit" class="btn btn-dark ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('news_i.lt_07') }}</span>
          </button>
        </div>
        </form>
      </div>
    </div>
  </div>

<script>
        function Edit(id, email, isnew) {
            if (isnew == 1) {
                $(".agencyTitle").html("<?= __('news_i.lt_05') ?>");
                $("#email").val('');
            } else {
                $(".agencyTitle").html("<?= __('news_i.lt_08') ?>");
                $("#email").val(email);
            }
            
            $("#id").val(id);
            $("#modal-update").modal();

        }
        function Delete(id) {
            Swal.fire({
                title: '<?= __('news_i.lt_09') ?>',
                text: "<?= __('news_i.lt_10') ?>",
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
                        window.location.href = "/blog/transmission/delete/" + id;
                    }
                })

        }
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
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
                    $("#mNews").addClass('sidebar-group-active active');
                    $("#mListTransmission").addClass('active');
                }, 100);

    });
    </script>
@endsection