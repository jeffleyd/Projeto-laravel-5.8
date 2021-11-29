@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Comunicação</h5>
              <div class="breadcrumb-wrapper col-12">
                @if ($id == 0)
                Nova notificação
                @else
                Atualizando notificação
                @endif
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
                            <form class="push" id="a_update_form" method="POST" action="/sac/comunication/authorized/update" enctype="multipart/form-data">
                            <input type="hidden" id="id" name="id" value="<?= $id ?>">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="users-list-verified">Deixe em branco para enviar para todas</label>
                                    <fieldset class="form-group">
                                        <select class="js-select2 form-control" id="authorized_id" name="authorized_id" style="width: 100%;" data-placeholder="Escolha autorizada" multiple>
                                            <option></option>
                                            <?php foreach ($userall as $key) { ?>
                                                <option value="<?= $key->id ?>" @if ($authorized_id == $key->id) selected @endif><?= $key->name ?> (#ID <?= $key->id ?>)</option>
                                            <?php } ?>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-sm-12">
                                    <label for="subject">Prioridade</label>
                                    <fieldset class="form-group">
                                        <select name="priority" class="form-control" id="priority">
                                            <option value="1" @if ($priority == 1) selected @endif>Baixa</option>
                                            <option value="2" @if ($priority == 2) selected @endif>Média</option>
                                            <option value="3" @if ($priority == 3) selected @endif>Alta</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-sm-12">
                                    <label for="subject">Assunto</label>
                                    <fieldset class="form-group">
                                    <input type="text" name="subject" id="subject" value="{{ $subject }}" class="form-control" maxlength="61">
                                    </fieldset>
                                </div>

                                <div class="col-sm-12">
                                    <label for="picture">Imagem mostrada no corpo do email</label>
                                    <fieldset class="form-group">
                                        <input type="file" accept="image/x-png,image/gif,image/jpeg" class="form-control" id="picture" name="picture">
                                        <?php if ($picture) { ?><div style="margin-top:5px;"><img height="100" src="<?= $picture ?>" alt=""></div><?php } ?>
                                    </fieldset>
                                </div>

                                <div class="col-sm-12">
                                    <label for="subject">Link da imagem</label>
                                    <fieldset class="form-group">
                                        <input type="text" name="link_picture" id="link_picture" class="form-control" value="{{ $link_picture }}" placeholder="Link usado ao clicar na imagem">
                                    </fieldset>
                                </div>

                                <div class="col-sm-12">
                                    <label for="description">Descrição (Não preencha se por o link externo)</label>
                                    <fieldset class="form-group">
                                        <textarea name="description" id="description" class="form-control" rows="3">{{ $description }}</textarea>
                                    </fieldset>
                                </div>

                                <div class="col-sm-12">
                                    <label for="attach_1">Anexo 1</label>
                                    <fieldset class="form-group">
                                        <input type="file" name="attach_1" id="attach_1">
                                    </fieldset>
                                    @if ($attach_1)
                                    <p>
                                        <a href="{{ $attach_1 }}" target="_blank">Ver anexo</a>
                                    </p>
                                    @endif
                                </div>
                                <div class="col-sm-12">
                                    <label for="attach_2">Anexo 2</label>
                                    <fieldset class="form-group">
                                        <input type="file" name="attach_2" id="attach_2">
                                    </fieldset>
                                    @if ($attach_2)
                                    <p>
                                        <a href="{{ $attach_2 }}" target="_blank">Ver anexo</a>
                                    </p>
                                    @endif
                                </div>
                                <div class="col-sm-12">
                                    <label for="attach_3">Anexo 3</label>
                                    <fieldset class="form-group">
                                        <input type="file" name="attach_3" id="attach_3">
                                    </fieldset>
                                    @if ($attach_3)
                                    <p>
                                        <a href="{{ $attach_3 }}" target="_blank">Ver anexo</a>
                                    </p>
                                    @endif
                                </div>
                                <div class="col-sm-12">
                                    <label for="link_external">Link externo (Caso for algo externo)</label>
                                    <fieldset class="form-group">
                                        <input type="text" name="link_external" value="{{ $link_external }}" id="link_external" class="form-control">
                                    </fieldset>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                @if ($id == 0)
                                <span class="d-none d-sm-block">Criar notificação</span>
                                @else
                                <span class="d-none d-sm-block">Atualizar notificação</span>
                                @endif
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>
</div>
<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckeditor/init.js"></script>
<script>
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        ckeditorInit('#description');
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
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSacComunication").addClass('sidebar-group-active active');
            $("#mSacComunicationAuthorized").addClass('active');
        }, 100);

    });
    </script>
@endsection