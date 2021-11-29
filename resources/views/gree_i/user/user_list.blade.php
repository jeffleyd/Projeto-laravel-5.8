@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Colaboradores</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de usuários
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/user/list" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-10">
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
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
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
                                            <th>Nome</th>
                                            <th>Cargo</th>
                                            <th>Setor</th>
                                            <th>2FA</th>
                                            <th>status</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $key) { ?>
                                        <tr>
                                            <td><?= $key->r_code ?></td>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                            <td><?= $key->office ?></td>
                                            <td><?= __('layout_i.'. $key->name .'') ?></td>
                                            <td>
                                                <?php if ($key->otpauth) { ?>
                                                    <span class="badge badge-light-success">Ativo</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-warning">Desativado</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($key->is_active == 1 and $key->is_holiday == 1) { ?>
                                                    <span class="badge badge-light-infor">Férias</span>
                                                <?php } else if ($key->is_active == 1) { ?>
                                                    <span class="badge badge-light-success">Ativo</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-danger">Desativado</span>
                                                <?php } ?>
                                            </td>
                                            <td id="action"><a href="/user/edit/<?= $key->r_code ?>"><i class="bx bx-edit-alt"></i></a></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $users->render(); ?>
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

<script>
        
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('userf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('userf_r_code') ?>']).trigger('change');
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
            $("#mUser").addClass('sidebar-group-active active');
            $("#mUserList").addClass('active');
        }, 100);

    });
    </script>
@endsection