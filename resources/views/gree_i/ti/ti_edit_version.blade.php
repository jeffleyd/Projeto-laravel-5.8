@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Versão</h5>
              <div class="breadcrumb-wrapper col-12">
                <?= getConfig("version_name") ?>
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
                        <form action="/ti/version/edit_do" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="version_name">Nome da versão</label>
                                <input type="text" class="form-control" id="version_name" value="<?= $vname ?>" name="version_name" placeholder="...">
                            </div>
                            <div class="form-group">
                                <label for="description">Texto em português</label>
                                <textarea id="js-ckeditor-pt" name="text_pt" id="text_pt"><?= $txt_pt ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="description">Texto em english</label>
                                <textarea id="js-ckeditor-en" name="text_en" id="text_en"><?= $txt_en ?></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="sendVersion" class="btn btn-primary" style="width:100%;"><?php if ($id == 0) { ?>NOVA VERSÃO<?php } else { ?>ATUALIZAR VERSÃO<?php } ?></button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
                                            <th>Versão</th>
                                            <th>Nome da versão</th>
                                            <th>Enviando em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($versions as $key) { ?>
                                            <tr>
                                                <td><?= $key->version ?></td>
                                                <td>
                                                    <?= $key->version_name ?>
                                                </td>
                                                <td>
                                                    <?= date('Y-m-d', strtotime($key->created_at)) ?>
                                                </td>
                                                <td id="action"><a href="/ti/version/edit/<?= $key->id ?>"><i class="bx bx-edit-alt"></i></a></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $versions->render(); ?>
                                        
                                    </ul>
                                </nav>
                            </div>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
    <script>
    $(document).ready(function () {
        CKEDITOR.replace("js-ckeditor-pt");
        CKEDITOR.replace("js-ckeditor-en");
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

        $("#sendVersion").click(function (e) { 
            if ($("#version_name").val() == "") {

                error('Digite o nome da versão');
                e.preventDefault();
                return;
            } else if ($("#text_pt").val() == "") {

                error('Fale sobre atualizações em português');
                e.preventDefault();
                return;
            } else if ($("#text_en").val() == "") {

                error('Fale sobre atualizações em inglês');
                e.preventDefault();
                return;
            } else if ($("#title").val() == "") {

                error('<?= __('project_i.pd_39') ?>');
                e.preventDefault();
                return;
            } else if ($("#description").val() == "") {

                error('<?= __('project_i.pd_40') ?>');
                e.preventDefault();
                return;
            } else if (ArrayRes == "") {

                error('<?= __('project_i.pd_41') ?>');
                e.preventDefault();
                return;
            }


            
        });

    });
    </script>
@endsection