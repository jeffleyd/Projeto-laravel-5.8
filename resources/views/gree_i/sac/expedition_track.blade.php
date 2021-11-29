@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Expedição</h5>
              <div class="breadcrumb-wrapper col-12">
                Acompanhamento de peças
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/sac/expedition/track" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Ordem de compra</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="order_purchase" value="<?= Session::get('filter_order_purchase') ?>" placeholder="Exem. G19272">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Pesquisar por O.S</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="os" value="<?= Session::get('filter_os') ?>" placeholder="Exem. W21254">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Remessa de peça</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="remittance_code" value="<?= Session::get('filter_remittance_code') ?>" placeholder="Exem. R18739">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">número da NF</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="nf_number" value="<?= Session::get('filter_nf_number') ?>" placeholder="Exem. 6668574457">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="start_date">Data inicial para exportação</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="start_date" id="start_date">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="end_date">Data final para exportação</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="end_date" id="end_date">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="status">Status para exportação</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="status" id="status">
                                <option value=""></option>
                                <option value="1">A caminho</option>
                                <option value="2">Concluído</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3 d-flex align-items-center">
                        <button type="submit" name="export" value="1" class="btn btn-success btn-block glow users-list-clear mb-0">Exportar dados</button>
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
                                            <th>Nota fiscal</th>
                                            <th>Rastreio</th>
                                            <th>Transporte</th>
                                            <th>Previsão de chegada</th>
                                            <th>Chegou em</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ser as $key) { ?>
                                        <tr>
                                            <td>
                                                @if ($key->is_expedition == 1)
                                                <?= $key->sac_os_protocol_code ?>
                                                @elseif($key->is_expedition == 2)
                                                <?= $key->buy_part_code ?>
                                                @else
                                                <?= $key->remittance_part_code ?>
                                                @endif
                                            </td>
                                            <td><?= $key->nf_number ?></td>
                                            <td><?= $key->code_track ?></td>
                                            <td><?= $key->transport ?></td>
                                            <td><?= date('d-m-Y', strtotime($key->arrival_forecast)) ?></td>
                                            <td>@if ($key->is_completed == 1) <?= date('d-m-Y', strtotime($key->updated_at)) ?> @else -- @endif</td>
                                            <td>
                                                <?php if ($key->is_completed == 1) { ?>
                                                    <span class="badge badge-light-success">Concluído</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-info">A caminho</span>
                                                <?php } ?>
                                            </td>
                                            <td>R$ <?= number_format($key->total, 2, ',', '.') ?></td>
                                            <td id="action">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if ($key->is_completed == 0)
                                                        <a href="javascript:void(0)" class="dropdown-item" onclick="completed(<?= $key->id ?>)"><i class="bx bx-check mr-1"></i> Concluir</a>
                                                        @endif
                                                        <a class="dropdown-item" target="_blank" href="/sac/expedition/track_part/<?= $key->id ?>/<?= $key->is_expedition ?>"><i class="bx bxs-wrench mr-1"></i> Ver peças</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $ser->appends([
                                            'nf_number' => Session::get('filter_nf_number'),
                                            'os' => Session::get('filter_os')
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
    </div>
</div>

    <script>
    function completed(id) {
        Swal.fire({
                    title: 'Finalizar acompanhamento',
                    text: "Você confirma que a(s) peça(s) cheragam no destinatário!?",
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
                        window.location.href = "/sac/expedition/track_do?id=" + id; 
                    }
                    })
    }
    $(document).ready(function () {
        $('#start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('#end_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
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
        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSacExpedition").addClass('sidebar-group-active active');
            $("#mSacExpeditionTrack").addClass('active');
        }, 100);
    });
    </script>
@endsection