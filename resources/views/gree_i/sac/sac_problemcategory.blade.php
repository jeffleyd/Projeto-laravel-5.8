@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Categoria de Problemas</h5>
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
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list as $key) { ?>
                                            <tr>
                                                <td><?= $key->id ?></td>
                                                <td><?= $key->description ?></td>
                                                <td>
                                                    <div class="dropleft">
                                                        <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0)" json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="showEdit(this)"><i class="bx bxs-pencil mr-1"></i> Editar</a>
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="excluirCategoryProblem({{$key->id}})"><i class="bx bxs-trash mr-1"></i> Excluir</a>
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
    <button type="button" onclick="showEdit(this, 1)" class="btn btn-sm btn-secondary">Registrar Problema</button>
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
        <form class="push" id="a_update_form" method="POST" action="/sac/problemcategory/update">
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="id" name="id">
                <div class="col-sm-12">
                    <label for="type">Descrição</label>
                    <fieldset class="form-group">
                        <input type="text" class="form-control" name="description" id="description" value="" required>
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

        <input id="create" name="create" type="hidden">
        </form>
        </div>
    </div>
</div>

<script>

    function showEdit(elem, isNew) {

        if(isNew == 1){
            $(".agencyTitle").html("Registrar Descrição");
            $("#modal-update").find("#description").val("");
            $("#modal-update").find("#id").val("0");

            $("#modal-update").modal();
        }else{
            let json_row = JSON.parse($(elem).attr("json-data"));
            $(".agencyTitle").html("Alterar Descrição");
            $("#modal-update").find("#description").val(json_row.description);
            $("#modal-update").find("#id").val(json_row.id);


            $("#modal-update").modal();
        }
    }

    function excluirCategoryProblem(id){

        Swal.fire({
            title: 'Tem certeza disso?',
            text: "Você irá remover a categoria de mensagem!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar!',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                block();
                window.location.href = "/sac/problemcategory/excluir?id="+id;
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
