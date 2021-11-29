@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_lending') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                Controle de limite de colaboradores
              </div>
            </div>
          </div>
        </div>
      </div>

    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="<?= Request::url() ?>" id="searchTrip" method="GET">
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
                                            <th>Colaborador</th>
                                            <th>Observação</th>
                                            <th>Limite</th>
                                            <th>Usado</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($limit as $key) { ?>
                                        <tr>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                            <td><span data-toggle="popover" data-content="<?= $key->obs ?>"><?= strWordCut($key->obs, 25, "...") ?></span></td>
                                            <td>R$ <?= number_format($key->limit_credit, 2, ".", "") ?></td>
                                            <td>R$ <?= number_format($key->used_credit, 2, ".", "") ?></td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a onclick="editObs(this)" json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-error mr-1"></i> Edit. Observação</a>
                                                        <a onclick="editLimit(this)" json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-dollar-circle mr-1"></i> Edit. Limite</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $limit->appends(['r_code' => Session::get('lendingf_r_code')])->links(); ?>
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

<div class="modal fade text-left" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-bank" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Atualizar informação</h3>
          <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <form class="push" action="/financy/lending/limit_do" id="a_update_form" method="POST" enctype="multipart/form-data">
        <div class="modal-body text-center">
            <div class="row">
                <input type="hidden" id="id" name="id">
                <div class="col-sm-12 limit">
                    <label for="limit">LIMITE DISPONÍVEL</label>
                    <fieldset>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">R$</span>
                            </div>
                            <input type="text" class="form-control" name="limit" id="limit">
                        </div>
                    </fieldset>
                </div>
                <div class="col-sm-12 obs">
                    <label for="description">DESCRIÇÃO</label>
                    <fieldset class="form-group">
                        <textarea class="form-control" name="obs" id="obs" rows="3" placeholder="Se tiver alguma observação, informe aqui..."></textarea>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">FECHAR</span>
          </button>
            <button type="submit" class="btn btn-success ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">ATUALIZAR</span>
            </button>
        </div>
        </form>
      </div>
    </div>
  </div>

    <script>
    function editObs(elem) {

        $('.limit').hide();
        $('.obs').show();
        $('#obs').removeAttr('disabled');
        let json_row = JSON.parse($(elem).attr("json-data"));
        $('#id').val(json_row.id);
        $('#obs').val(json_row.obs);
        $('#limit').attr('disabled', 'disabled');

        $('#modal-edit').modal();
    }
    function editLimit(elem) {

        $('.limit').show();
        $('.obs').hide();
        $('#limit').removeAttr('disabled');
        let json_row = JSON.parse($(elem).attr("json-data"));
        $('#id').val(json_row.id);
        $('#limit').val(json_row.limit_credit);
        $('#obs').attr('disabled', 'disabled');

        $('#modal-edit').modal();
    }
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('lendingf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('lendingf_r_code') ?>']).trigger('change');
        <?php } ?>
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });



        $('#a_update_form').submit(function(e) {
            block();
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
        
        $('#limit').mask('00000.00', {reverse: true});

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mFinancyLending").addClass('sidebar-group-active active');
            $("#mFinancyLendingLimit").addClass('active');
        }, 100);

    });
    </script>
@endsection
