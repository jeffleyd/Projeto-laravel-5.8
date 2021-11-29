@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Administração</h5>
              <div class="breadcrumb-wrapper col-12">
                Abaixo a lista de solicitações que você está marcado como observador
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="{{Request::url()}}" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="code">Código</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" id="code" value="{{Session::get('filter_code')}}" name="code">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="status">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('filter_status') == 1) selected @endif>Em andamento</option>
                                <option value="2" @if (Session::get('filter_status') == 2) selected @endif>Aprovado</option>
                                <option value="3" @if (Session::get('filter_status') == 3) selected @endif>Reprovado</option>
                                <option value="4" @if (Session::get('filter_status') == 4) selected @endif>Cancelado</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">Filtrar</button>
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
                                            <th>Código</th>
											<th>Criado em</th>
                                            <th>Status</th>
                                            <th>Solicitação</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests as $key)
                                        <tr>
                                            <td>{{$key->AdmRequests->code}}</td>
											<td>{{date('d/m/Y H:i', strtotime($key->AdmRequests->created_at))}}</td>
                                            <td>
                                                @if ($key->AdmRequests->is_approv)
                                                <span class="badge badge-light-success">Aprovado</span>
                                                @elseif ($key->AdmRequests->is_reprov)
                                                <span class="badge badge-light-danger">Reprovado</span>
                                                @elseif ($key->AdmRequests->is_cancelled)
                                                <span class="badge badge-light-danger">Cancelado</span>
                                                @else
                                                <span class="badge badge-light-warning">Em andamento</span>
                                                @endif
                                            </td>
                                            <td><span data-toggle="popover" data-content="<?= $key->AdmRequests->description ?>"><?= \Str::limit($key->AdmRequests->description, 35); ?></span></td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/administration/generic/request/view?s={{$key->AdmRequests->hash_code}}"><i class="bx bx-show mr-1"></i> Visualizar</a>
                                                        <a class="dropdown-item" target="_blank" href="{{$key->AdmRequests->AdmRequestFiles->url}}"><i class="bx bx-printer mr-1"></i> Imprim. Solic.</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $requests->appends(getSessionFilters()[0]->toArray())->links(); ?>
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

        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mrequests").addClass('sidebar-group-active active');
            $("#mrequestsApprov").addClass('active');
        }, 100);

    });
    </script>
@endsection