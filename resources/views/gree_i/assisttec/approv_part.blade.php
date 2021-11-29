@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Assistência técnica</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de aprovação de peças
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/sac/warranty/approv" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Ordem de serviço</label>
                        <fieldset class="form-group">
                            <input type="text" name="code" value="{{ Session::get('sacf_code') }}" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="users-list-verified">Atendente</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" multiple>
                                <?php foreach ($userall as $key) { ?>
                                    <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                <?php } ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_status') == 1) selected @endif>Aguardando</option>
                                <option value="2" @if (Session::get('sacf_status') == 2) selected @endif>Em andamento</option>
                                <option value="3" @if (Session::get('sacf_status') == 3) selected @endif>Concluído</option>
                                <option value="4" @if (Session::get('sacf_status') == 4) selected @endif>Cancelado</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="type_people">Tipo de pesquisa (Cliente)</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="type_people" name="type_people" style="width: 100%;">
                                <option value="0" selected>Livre</option>
                                <option value="1">Física (CPF)</option>
                                <option value="2">Jurídica (CNPJ)</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-4">
                        <label for="users-list-verified">Cliente</label>
                        <fieldset class="form-group">
                            <select class="js-select21 form-control" id="client" name="client" style="width: 100%;" multiple>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-6">
                        <label for="users-list-verified">Autorizada/Credenciada</label>
                        <fieldset class="form-group">
                            <select class="js-select22 form-control" id="authorized" name="authorized" style="width: 100%;" multiple>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-12 d-flex align-items-center">
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
                                            <th>#ID</th>
                                            <th>Feito em</th>
                                            <th>Atendente</th>
                                            <th>Cliente</th>
                                            <th>Autorizado</th>
                                            <th>status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($protocol as $key) { ?>
                                        <tr>
                                            <td><?= $key->sac_os_protocol_code ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($key->created_at)) ?></td>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                            <td><a target="_blank" href="/sac/client/edit/<?= $key->sac_client_id ?>"><?= strWordCut($key->sac_client_name, 13) ?></a></td>
                                            <td><a target="_blank" href="/sac/authorized/edit/<?= $key->sac_authorized_id ?>"><?= strWordCut($key->sac_authorized_name, 13) ?></a></td>  
                                            <td>
                                                @if ($key->is_denied == 1)
                                                <span class="badge badge-light-danger">Finalização negada</span>
                                                @elseif ($key->is_cancelled == 1)
                                                <span class="badge badge-light-danger">Cancelado</span>
                                                @elseif ($key->pending_completed == 1)
                                                <span class="badge badge-light-warning">Pendente p/ completar</span>
                                                @elseif ($key->is_completed == 1)
                                                <span class="badge badge-light-success">Concluído</span>
                                                @elseif ($key->in_progress == 1)
                                                <span class="badge badge-light-warning">Em andamento</span>
                                                @elseif ($key->in_wait == 1)
                                                <span class="badge badge-light-info">Aguardando</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if ($key->is_completed == 0 and $key->is_cancelled == 0)
                                                        <a class="dropdown-item" href="/sac/warranty/parts/<?= $key->sac_os_protocol_id ?>"><i class="bx bxs-package mr-1"></i> Análisar solicitação</a>
                                                        @endif
                                                        <a class="dropdown-item" target="_blank" href="/sac/warranty/interactive/<?= $key->id ?>"><i class="bx bx-chat mr-1"></i> Ver interações</a>
                                                    </div>
                                                </div>
                                            </td>                                          
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $protocol->appends([
                                            'left_5' => Session::get('sacf_left_5'),
                                            'left_15' => Session::get('sacf_left_15'),
                                            'left_30' => Session::get('sacf_left_30'),
                                            'code' => Session::get('sacf_code'),
                                            'r_code' => Session::get('sacf_r_code'),
                                            'status' => Session::get('sacf_status'),
                                            'client' => Session::get('sacf_client'),
                                            'authorized' => Session::get('sacf_authorized'),
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


<div class="modal fade text-left" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="modal-map" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modal-map">Autorizadas próximas</h3>
          <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
            <div id="map" style="height: 500px; width:100%"></div>
        </div>
      </div>
    </div>
  </div>

    <script>
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });

        $("#type_people").change(function (e) { 
            if ($("#type_people").val() == 0) {
                $('.select2-search__field').unmask();
            } else if ($("#type_people").val() == 1) {

                $('.select2-search__field').mask('000.000.000-00', {reverse: false});

            } else if ($("#type_people").val() == 2) {

                $('.select2-search__field').mask('00.000.000/0000-00', {reverse: false});
            }
            
        });

        <?php if (!empty(Session::get('sacf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('userf_r_code') ?>']).trigger('change');
        <?php } ?>
        <?php if (!empty(Session::get('sacf_client'))) { ?>
        $('.js-select21').val(['<?= Session::get('sacf_client') ?>']).trigger('change');
        <?php } ?>
        <?php if (!empty(Session::get('sacf_authorized'))) { ?>
        $('.js-select22').val(['<?= Session::get('sacf_authorized') ?>']).trigger('change');
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

        $(".js-select21").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {

                        var url = "'/sac/client/edit/0'";
                        return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo cliente</button>');
                    }
                },
                ajax: {
                    url: '/misc/sac/client/',
                    data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }

                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                    }
                }
            });

            $(".js-select22").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {

                        var url = "'/sac/authorized/edit/0'";
                        return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Nova Autorizada</button>');
                    }
                },
                ajax: {
                    url: '/misc/sac/authorized/',
                    data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }

                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                    }
                }
            });

            setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mTAssist").addClass('sidebar-group-active active');
            $("#mTAssistOSPartApprov").addClass('active');
        }, 100);

    });
    </script>
@endsection