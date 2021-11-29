@extends('gree_i.layout')

@section('content')

<style>
    .table th, .table td {
        padding: 1.15rem 1rem;
    }
</style>  

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Jurídico</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de escritórios
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/juridical/law/firm/list" id="searchTrip" method="GET">
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
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="users-list-verified">Escritório</label>
                        <fieldset class="form-group">
                            <select class="js-select21 form-control" id="law_firm" name="law_firm" style="width: 100%;" multiple>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status" style="width: 100%;">
                                <option></option>
                                <option value="1" @if (Session::get('lawfirm_status') == 1) selected @endif>Ativo</option>
                                <option value="2" @if (Session::get('lawfirm_status') == 2) selected @endif>Desativado</option>
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
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Razão social / Nome</th>
                                            <th>CNPJ / CPF</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>Status</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($law_firm as $key) { ?>
                                        <tr>
                                            <td><a href="/juridical/law/firm/cost/<?= $key->id ?>"><?= $key->name ?></a></td>
                                            <td><?= $key->identity ?></td>
                                            <td><?= count($key->juridical_law_firm_contacts) ? $key->juridical_law_firm_contacts->first()->email : '-' ?></td>
                                            <td><?= count($key->juridical_law_firm_contacts) ? $key->juridical_law_firm_contacts->first()->phone_1 : '-' ?></td>
                                            <td>
                                                @if ($key->is_active == 1)
                                                <span class="badge badge-light-success">Ativo</span>
                                                @else
                                                <span class="badge badge-light-danger">Desativado</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/juridical/law/firm/cost/<?= $key->id ?>"><i class="bx bx-file mr-1"></i> Acompanhar Custos</a>
                                                        <a class="dropdown-item" href="/juridical/law/firm/register/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar escritório</a>
                                                    </div>
                                                </div>    
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $law_firm->appends(getSessionFilters('lawfirm_')[0]->toArray())->links(); ?>
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
        $(".js-select21").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    var url = "'/juridical/law/firm/register/0'";
                    return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo escritório</button>');
                }
            },
            ajax: {
                url: '/juridical/law/firm/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });
        setInterval(() => {
            $("#mJuridical").addClass('sidebar-group-active active');
            $("#mJuridicalLawFirm").addClass('sidebar-group-active active');
            $("#mJuridicalLawFirmList").addClass('active');
        }, 100);
    });
</script>
@endsection