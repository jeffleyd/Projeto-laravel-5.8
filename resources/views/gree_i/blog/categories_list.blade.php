@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" href="/js/plugins/datatables/dataTables.bootstrap4.css">
    <h2 class="content-heading">Categorias <small>Cadastre ou edite</small></h2>
    <!-- Dynamic Table Full Pagination -->
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Lista</h3>
            <div class="block-options">
                <button type="button" onclick="Edit(0, '', '', 0, 1)" class="btn btn-sm btn-secondary">Adicionar nova</button>
            </div>
        </div>
        <div class="table-responsive">
        <div class="block-content block-content-full">
            
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome PT</th>
                            <th>NOME EN</th>
                            <th>PUBLICAÇÕES</th>
                            <th class="text-center">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $key) { ?>
                        <tr>
                            <td>
                                <?= $key->id ?>
                            </td>
                            <td>
                                <?= $key->name_pt ?>
                            </td>
                            <td>
                                <?= $key->name_en ?>
                            </td>
                            <td>
                                <?= $key->post_total ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" onclick="Edit(<?= $key->id ?>, '<?= $key->name_pt ?>', '<?= $key->name_en ?>', '<?= $key->is_visible ?>', 0)" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination float-right">
                                <?= $categories->render(); ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
    </div>

    <!-- email Modal -->
    <div class="modal fade" id="modal-update" tabindex="-1" role="dialog" aria-labelledby="modal-update" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popin" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title catTitle">Categoria</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <form class="push" id="a_update_form" method="POST" action="/blog/category/do">
                            <input type="hidden" id="id" name="id">
                            <div class="form-group">
                                <label class="col-12" for="name_pt">Nome em português</label>
                                <input type="text" class="form-control" id="name_pt" name="name_pt" placeholder="...">
                            </div>
                            <div class="form-group">
                                <label class="col-12" for="name_en">Nome em inglês</label>
                                <input type="text" class="form-control" id="name_en" name="name_en" placeholder="...">
                            </div>
                            <div class="form-group">
                                <label class="css-control css-control-info css-checkbox">
                                    <input type="checkbox" name="isvisible" id="isvisible" class="css-control-input" value="1" checked="">
                                    <span class="css-control-indicator"></span> Está visivel?
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">FECHAR</button>
                        <button type="submit" class="btn btn-alt-success">
                            <i class="fa fa-check"></i> ATUALIZAR
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END email Modal -->
    
    <script src="/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        
        function Edit(id, namept, nameen, check, isnew) {
            if (isnew == 1) {
                
                $(".catTitle").html("Nova categoria");
            } else {
                
                $(".catTitle").html("Editando categoria");
            }
            if (check == '0') {
                $("#isvisible").removeAttr('checked');
                
            } else {
                $("#isvisible").attr('checked', '');
            }
            $("#name_pt").val(namept);
            $("#name_en").val(nameen);
            $("#id").val(id);

            $("#modal-update").modal();

        }

        function errorToast(msg) {
            iziToast.error({
                title: 'Erro!',
                message: msg,
            });
        }

    $(document).ready(function () {
        <?php if (Session::has('success')) { ?>
            iziToast.success({
                title: 'Sucesso!',
                message: '<?= Session::get('success') ?>',
            });
        <?php } Session::forget('success'); ?>
        $('.js-dataTable-full-pagination').DataTable( {
            searching: true,
            paging: false,
            language: {
                search: "{{ __('layout_i.dtbl_search') }}",
                zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                info: "{{ __('layout_i.dtbl_info') }}",
                infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
                infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
            }
        });

        $("#a_update_form").submit(function (e) { 

            if ($("#name_pt").val() == "") {
            
                e.preventDefault();
                errorToast('Preencha o nome em português');
                return
            } else if ($("#name_en").val() == "") {
            
                e.preventDefault();
                errorToast('Preencha o nome em inglês');
                return
            }
            
            
        });

        $("#mBlog").addClass("open");
        $("#mBlogCategory").addClass("active");
    });
    </script>
@endsection