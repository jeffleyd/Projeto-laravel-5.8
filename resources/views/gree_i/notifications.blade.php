@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Notificações</h5>
              <div class="breadcrumb-wrapper col-12">
                Todas as notificações
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
                        <label for="code">Pesquisa por texto</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" id="text" value="{{Session::get('flt_text')}}" name="text">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="status">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('flt_status') == 1) selected @endif>Lido</option>
                                <option value="2" @if (Session::get('flt_status') == 2) selected @endif>Não lido</option>
                                <option value="3" @if (Session::get('flt_status') == 3) selected @endif>Importante</option>
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
                                            <th>Descrição</th>
                                            <th>Status</th>
                                            <th>Criado em</th>
                                            <th>Link</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($notify as $key) { ?>
                                        <tr 
                                            @if($key->has_read == 0)
                                                style="color: #454545; background-color: #ffffff;"
                                            @elseif($key->has_read == 1)
                                                style="color: #454545; background-color: #f2f4f4;"
                                            @else    
                                                style="color: #ffffff; background-color: #e65c5c;"
                                            @endif
                                        >
                                            <td>
                                                <?= $key->title ?>
                                                <br>
                                                <?= $key->code ?>
                                            </td>
                                            <td>
                                                @if ($key->has_read == 0)
                                                    <span class="badge badge-warning" style="font-size: 9px;">Não lido</span>
                                                @elseif($key->has_read == 1)
                                                    <span class="badge badge-primary" style="font-size: 9px;">lido</span> 
                                                @else
                                                    <span class="badge badge-danger" style="font-size: 9px;">importante</span> 
                                                @endif
                                            </td>
                                            <td><?= date('Y-m-d H:i', strtotime($key->created_at)) ?></td>
                                            <td><a href="<?= $key->url ?>"><i class="bx bx-link-external"></i></a></td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/notifications/change/status/{{$key->id}}/1"><i class="bx bx-check-double mr-1"></i>Lido</a>
                                                        <a class="dropdown-item" href="/notifications/change/status/{{$key->id}}/0"><i class="bx bx-x mr-1"></i>Não Lido</a>
                                                        <a class="dropdown-item" href="/notifications/change/status/{{$key->id}}/2"><i class="bx bxs-error-circle mr-1"></i>Importante</a>
                                                    </div>
                                                </div>
                                            </td>  
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
										<?= $notify->appends(['text'=> Session::get('flt_text'), 'status'=> Session::get('flt_status') ])->links(); ?>
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

    });
    </script>
@endsection