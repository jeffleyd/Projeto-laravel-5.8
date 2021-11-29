@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Perguntas frequentes</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de perguntas frequentes
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/sac/faq/all" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-10">
                        <label for="tyoe">Tipo de público</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="tyoe" name="type" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_type') == 1) selected @endif>Autorizada</option>
                                <option value="2" @if (Session::get('sacf_type') == 2) selected @endif>Consumidor</option>
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
                                            <th>Pergunta</th>
                                            <th>Tipo</th>
                                            <th>Atualizado em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($faq as $key) { ?>
                                        <tr>
                                            <td><?= $key->question ?></td>
                                            <td>
                                                @if ($key->type == 1)
                                                    Autorizada
                                                @else 
                                                    Consumidor
                                                @endif
                                            </td>
                                            <td><?= date('d-m-Y H:i', strtotime($key->updated_at)) ?></td>
                                            <td id="action">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="/sac/faq/edit/<?= $key->id ?>" class="dropdown-item"><i class="bx bx-edit-alt mr-1"></i> {{ __('layout_i.op_edit') }}</a>
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
                                        <?= $faq->appends([
                                            'type' => Session::get('sacf_type'),
                                            ])->links(); ?>
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
    <a href="/sac/faq/edit/0"><button type="button" class="btn btn-sm btn-secondary">Nova pergunta com resposta</button></a>
</div>

<script>
    function Delete(id) {
        Swal.fire({
            title: 'Deletar',
            text: "Você tem certeza que deseja fazer isso?",
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
                    window.location.href = "/sac/faq/delete/" + id;
                }
            })

    }
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
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSacComunication").addClass('sidebar-group-active active');
            $("#mSacComunicationFaq").addClass('active');
        }, 100);

    });
    </script>
@endsection