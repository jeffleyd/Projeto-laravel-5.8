@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Atendimento</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de autorizadas/credênciadas
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/sac/authorized/all" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="type_people">Tipo de pesquisa</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="type_people" name="type_people" style="width: 100%;">
                                <option value="0" selected>Livre</option>
                                <option value="1">Física (CPF)</option>
                                <option value="2">Jurídica (CNPJ)</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-8">
                        <label for="authorized">Autorizada/credênciada</label>
                        <fieldset class="form-group">
                            <select class="js-select21 form-control" id="authorized" name="authorized" style="width: 100%;" multiple>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_status') == 1) selected @endif>Ativo</option>
                                <option value="2" @if (Session::get('sacf_status') == 2) selected @endif>Desativado</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Indíce de manifestação</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="manifest" name="manifest" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_manifest') == 1) selected @endif>Mais ativos</option>
                                <option value="2" @if (Session::get('sacf_manifest') == 2) selected @endif>Menos ativos</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Avaliação</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="rate" name="rate" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_rate') == 1) selected @endif>Melhor avaliado</option>
                                <option value="2" @if (Session::get('sacf_rate') == 2) selected @endif>Pior avaliado</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Cidade</label>
                        <fieldset class="form-group">
                            <input type="text" name="city" value="{{ Session::get('sacf_city') }}" class="form-control">
                        </fieldset>
                    </div>            
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Estado</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="state" name="state" style="width: 100%;">
                                <option></option>      
                                @foreach (config('gree.states') as $key => $state)
                                    <option value="{{ $key }}" @if (Session::get('sacf_state') == $key) selected @endif>{{ $state }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
					<div class="col-md-2">
                        <fieldset class="form-group">
                            <label for="type">Tipo da empresa</label>
                            <select class="form-control" id="type" name="type">
                                <option></option>
                                <option value="1" @if (Session::get('sacf_type') == 1) selected @endif>Autorizada</option>
                                <option value="2" @if (Session::get('sacf_type') == 2) selected @endif>Tercerizado</option>
                                <option value="3" @if (Session::get('sacf_type') == 3) selected @endif>Revenda</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-md-2">
                        <fieldset class="form-group">
                            <label for="type">Tem remessa de peça</label>
                            <select class="form-control" id="remittance" name="remittance">
                                <option></option>
                                <option value="99" @if (Session::get('sacf_remittance') == 99) selected @endif>Não</option>
                                <option value="1" @if (Session::get('sacf_remittance') == 1) selected @endif>Sim</option>
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
                                            <th>Nome</th>
                                            <th>Manifestação</th>
                                            <th>Avaliação</th>
                                            <th>Telefone</th>
                                            <th>Status</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($authorized as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td><?= $key->name ?></td>
                                            <td><?= $key->live ?></td>
                                            <td>
                                                @if ($key->rate > 0.00)
                                                <i class="bx bxs-star @if ($key->rate > 0) text-warning @elseif ($key->rate < 1) text-muted @endif"></i>
                                                <i class="bx bxs-star @if ($key->rate > 1) text-warning @elseif ($key->rate < 2) text-muted @endif"></i>
                                                <i class="bx bxs-star @if ($key->rate > 2) text-warning @elseif ($key->rate < 3) text-muted @endif"></i>
                                                <i class="bx bxs-star @if ($key->rate > 3) text-warning @elseif ($key->rate < 4) text-muted @endif"></i>
                                                <i class="bx bxs-star @if ($key->rate > 4) text-warning @elseif ($key->rate < 5) text-muted @endif"></i>
                                                @else
                                                --
                                                @endif
                                            </td>
                                            <td><?= $key->phone_1 ?> / <?= $key->phone_2 ?></td>
                                            <td>
                                                @if ($key->is_active == 1)
                                                <span class="badge badge-light-success">Ativo</span>
                                                @else
                                                <span class="badge badge-light-danger">Desativado</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="/sac/authorized/edit/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i></a>
                                            </td>                                          
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $authorized->appends([
                                            'authorized' => Session::get('sacf_authorized'),
                                            'status' => Session::get('sacf_status'),
                                            'rate' => Session::get('sacf_rate'),
                                            'manifest' => Session::get('sacf_manifest'),
                                            'state' => Session::get('sacf_state'),
                                            'city' => Session::get('sacf_city'),
											'type' => Session::get('sacf_type'),
                                            'remittance' => Session::get('sacf_remittance')
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
        <?php if (!empty(Session::get('sacf_authorized'))) { ?>
        $('.js-select21').val(['<?= Session::get('sacf_authorized') ?>']).trigger('change');
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
            $("#mSac").addClass('sidebar-group-active active');
            $("#mSacAuthorizedAll").addClass('active');
        }, 100);

    });
    </script>
@endsection