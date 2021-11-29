@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Engenharia</h5>
              <div class="breadcrumb-wrapper col-12">
                Tipo
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        @if (Session::get('filter_line') == 1)
        <div class="users-list-filter px-1">
            <form action="/engineering/type" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-10">
                        <label for="type">Tipo</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="type_line">
                                <option value="">Todos</option>
                                <option value="1" @if (Session::get('sacf_type_line') == '1') selected @endif>Residencial</option>
                                <option value="2" @if (Session::get('sacf_type_line') == '2') selected @endif>Comercial</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        @endif
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
                                            <th>Descrição</th>
                                            <th>Tipo de linha</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td><?= $key->name ?></td>
                                            <td>
                                                @if ($key->type_line == 1)
                                                    <span class="badge badge-primary" style="font-size: 9px;">Residencial</span>
                                                @else
                                                    <span class="badge badge-warning" style="font-size: 9px;">Comercial</span>
                                                @endif
                                            </td>
                                            <td id="action">
                                                <a onclick="Edit(<?= $key->id ?>, '<?= $key->name ?>', <?= $key->type_line ?> , 0)" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i></a>
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
    <button type="button" onclick="Edit(0, '', 0, 1)" class="btn btn-sm btn-secondary">Novo Tipo</button>
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
        <form class="push" id="a_update_form" method="POST" action="/engineering/type/update">
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="id" name="id">
                <div class="col-sm-12">
                    <label for="type">tipo</label>
                    <fieldset class="form-group">
                        <input type="text" class="form-control" name="type" id="type" value="" required>
                    </fieldset>
                </div>
                <div class="col-sm-12">
                    <label for="users-list-verified">Tipo de linha</label>
                    <fieldset class="form-group">
                        <select class="form-control" id="type_line" name="type_line" style="width: 100%;">
                            <option></option>
                            <option value="1">Residencial</option>
                            <option value="2">Comercial</option>
                        </select>
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
        function Edit(id, type, type_line, isnew) {
            if (isnew == 1) {
                $(".agencyTitle").html("Novo tipo");
                $("#type").val('');
                $("#type_line").val('');
            } else {
                $(".agencyTitle").html("Editando tipo");
                $("#type").val(type);
                $("#type_line").val(type_line);
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
                        window.location.href = "/engineering/type/delete/" + id;
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
                    $("#mIndustrial").addClass('sidebar-group-active active');
					$("#mEngineering").addClass('sidebar-group-active active');
					$("#mEngineeringAllTypes").addClass('active');
                }, 100);

    });
    </script>
@endsection