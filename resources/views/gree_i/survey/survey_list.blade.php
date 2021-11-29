@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Pesquisas</h5>
              <div class="breadcrumb-wrapper col-12">
                Todas as pesquisas
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
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
                                            <th>#ID</th>
                                            <th>Nome</th>
                                            <th>Criado em</th>
                                            <th>Status</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($survey as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td>
                                                <?= strip_tags($key->name) ?>
                                            </td>
                                            <td><?= date('Y/m/d', strtotime($key->created_at)) ?></td>
                                            <td>
                                                <?php if ($key->is_active == 1) { ?>
                                                    <span class="badge badge-light-success">Ativo</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-danger">Desativado</span>
                                                <?php } ?>
                                            </td>
                                            <td id="action"><a href="/survey/edit/<?= $key->id ?>"><i class="bx bx-edit-alt"></i></a></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $survey->render(); ?>
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
            $("#msurvey").addClass('sidebar-group-active active');
            $("#msurveyAll").addClass('active');
        }, 100);

    });
    </script>
@endsection