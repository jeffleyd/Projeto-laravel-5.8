@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Entrada & Saída</h5>
              <div class="breadcrumb-wrapper col-12">
                Veículos alugados
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
                            <div class="top d-flex flex-wrap">
                                <div class="action-filters flex-grow-1">
                                    <div class="dataTables_filter mt-1">
                                        <h5>Lista de veículos</h5>
                                    </div>
                                </div>
                                <div class="actions action-btns d-flex align-items-center">
                                    <div class="dropdown invoice-filter-action">
                                        <button type="button" onclick="showEdit('')" class="btn btn-secondary shadow mr-1">Novo veículo</button>
                                        <button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Placa</th>
                                            <th>Cor</th>
                                            <th>KM</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($vehicles as $key) { ?>
                                        <tr>
                                            <td><?= $key->registration_plate ?></td>
                                            <td>{{$key->color}}</td>
                                            <td>{{$key->km}}</td>
                                            <td>
                                                <?php if ($key->is_active) { ?>
                                                    <span class="badge badge-light-success">Ativo</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-danger">Desativado</span>
                                                <?php } ?>
                                            </td>
                                            <td id="action">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="showEdit(this)" href="javascript:void(0)" class="dropdown-item"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                        <a onclick="" target="_blank" href="/adm/entry-exit/vehicles/list?entry_exit_rent_vehicle_id={{$key->id}}" class="dropdown-item"><i class="bx bx-paperclip mr-1"></i> Solicitações</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $vehicles->render(); ?>
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

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar dados</span>
            </div>
            <form action="{{Request::url()}}" id="form_modal_filter">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Placa</label>
                                <input type="text" class="form-control" name="registration_plate" value="{{Request::get('registration_plate')}}" placeholder="Exem. R18739">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="submit" onclick="block();" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Filtrar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modal-update" tabindex="-1" role="dialog" aria-labelledby="modal-update" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark white">
          <span class="modal-title itemTitle" id="modal-update"></span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <form class="push" id="a_update_form" method="POST" action="/adm/entry-exit/rent/vehicles/edit">
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="id" name="id">
                <div class="col-sm-12">
                    <label for="users-list-verified">Placa</label>
                    <fieldset class="form-group">
                        <input type="text" name="registration_plate" class="form-control" placeholder="LPH-2221" required>
                    </fieldset>
                </div>
                <div class="col-sm-12">
                    <label for="users-list-verified">Cor</label>
                    <fieldset class="form-group">
                        <input type="text" name="color" class="form-control" placeholder="Vermelho" required>
                    </fieldset>
                </div>
                <div class="col-sm-12">
                    <label for="users-list-verified">KM</label>
                    <fieldset class="form-group">
                        <input type="number" name="km" class="form-control" placeholder="0" required>
                    </fieldset>
                </div>
                <div class="col-sm-12">
                    <label for="users-list-verified">Status</label>
                    <fieldset class="form-group">
                        <select name="is_active" class="form-control">
                            <option value="2">Desativado</option>
                            <option value="1">Ativo</option>
                        </select>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Fechar</span>
            </button>
          <button type="submit" class="btn btn-dark ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Enviar</span>
          </button>
        </div>
        </form>
      </div>
    </div>
  </div>

<script>
    function showEdit(elem) {

            if (elem) {
                let json_row = JSON.parse($(elem).attr("json-data"));
                $("#id").val(json_row.id);
                $('input[name="registration_plate"]').val(json_row.registration_plate);
                $('input[name="color"]').val(json_row.color);
                $('input[name="km"]').val(json_row.km);
                if (json_row.is_active == 1)
                    $('select[name="is_active"]').val(1);
                else
                    $('select[name="is_active"]').val(2);

                $('.itemTitle').html('Editando veículo');
            } else {
                $("#id").val(0);
                $('input[name="registration_plate"]').val('');
                $('input[name="color"]').val('');
                $('input[name="km"]').val(0);
                $('select[name="is_active"]').val(1);
                $('.itemTitle').html('Novo veículo');
            }

            $("#modal-update").modal();

        }
        function Delete(id) {
            Swal.fire({
                title: '<?= __('news_i.la_11') ?>',
                text: "<?= __('news_i.la_12') ?>",
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
                        window.location.href = "/blog/author/delete/" + id;
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
                    $("#mAdmin").addClass('sidebar-group-active active');
                    $("#mEntryExit").addClass('sidebar-group-active active');
                    $("#mEntryExitRentVehicles").addClass('active');
                }, 100);

    });
    </script>
@endsection
