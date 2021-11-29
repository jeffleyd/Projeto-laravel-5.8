@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Jurídico</h5>
              <div class="breadcrumb-wrapper col-12">
                Tipos de documentos
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
                        <form action="/juridical/type/documents/list" method="GET">
                            <div class="card-body">
                                <div class="top d-flex flex-wrap">
                                    <div class="action-filters flex-grow-1 mr-1 mt-1">
                                        <div class="dataTables_filter">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  name="description" id="filter_description" placeholder="Filtrar Documentos">
                                                    </div>
                                                </div>    
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <select class="form-control" name="status" id="filter_status">
                                                            <option value="" selected disabled>Filtrar Status</option>
                                                            <option value="1">Ativado</option>
                                                            <option value="opt_disabled">Desativado</option>
                                                        </select>
                                                    </div>    
                                                </div>    
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <button type="submit" class="btn btn-primary shadow mr-1">Filtrar</button>
                                        </div>
                                        <div class="dropdown invoice-options">
                                            <button type="button" class="btn btn-success shadow mr-1" onclick="typeEdit(0, 1, '', '')"><i class="bx bx-plus-circle"></i> Nova Documento</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </form>       
                    </div>   
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Descrição documento</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($type_documents as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td><?= $key->description ?></td>
                                            <td>
                                                @if ($key->status == 0)
                                                    <span class="badge badge-light-danger">Desativado</span>
                                                @else
                                                    <span class="badge badge-light-primary">Ativado</span>
                                                @endif
                                            </td>
                                            <td id="action">
                                                <a onclick="typeEdit(<?= $key->id ?>, 0, '<?= $key->description ?>', <?= $key->status ?>)" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $type_documents->appends([
                                                'description' => Session::get('type_doc_description'),
                                                'status' => Session::get('type_doc_status'),
                                            ])->links(); 
                                        ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade text-left" id="modal-update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <form id="modal_form_type" method="POST" action="/juridical/type/documents/edit_do">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="id" name="id">
                        <div class="col-sm-12">
                            <label>Descrição</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" name="description" id="description" placeholder="Informe nome ação">
                            </fieldset>
                        </div>
                        <div class="col-sm-12 div-modal-status">
                            <label>Status</label>
                            <fieldset class="form-group">
                                <select class="form-control" id="status" name="status">
                                    <option value="">Filtrar Status</option>
                                    <option value="1">Ativado</option>
                                    <option value="0">Desativado</option>
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
                    <button type="button" class="btn btn-primary ml-1" id="btn_submit_modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Salvar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
        
    $(document).ready(function () {

        $("#btn_submit_modal").click(function () {

            if($("#description").val() == "") {
                return $error('Descrição obrigatória!');
            } 
            else if($("#status").val() == "") {
                return $error('Status obrigatório!');
            } 

            $('#modal_form_type').submit();
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
            $("#mJuridical").addClass('sidebar-group-active active');
            $("#mJuridicalTypes").addClass('sidebar-group-active active');
            $("#mJuridicalTypeDocument").addClass('active');
        }, 100);
    });

    function typeEdit(id, is_new, description, status) {

        if (is_new == 1) {
            $(".modal-title").html("Novo tipo de documento");
            $("#description").val('');

            $('#status option[value="1"]').attr('selected','selected');
            $(".div-modal-status").hide();
            
        } else {
            $(".modal-title").html("Editando documento");
            $("#description").val(description);
            $("#status").val(status);
        }
            
        $("#id").val(id);
        $("#modal-update").modal();
    }

</script>
@endsection