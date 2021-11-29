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
                Peças da solicitação #<?= $id ?>
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
                                <!-- datatable start -->
                                <div class="table-responsive">
                                    <table id="list-datatable" class="table">
                                        <thead>
                                            <tr>
                                                @if($is_expedition == 1)
                                                <th>O.S</th>
                                                <th>protocolo</th>
                                                @elseif($is_expedition == 1)
                                                <th>Ordem Compra</th>
                                                <th>modelo</th>
                                                @else
                                                <th>Remessa de peça</th>
                                                <th>modelo</th>
                                                @endif
                                                <th>Código</th>
                                                <th>Descrição</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($parts as $key) { ?>
                                            <tr>
                                                @if($is_expedition == 1)
                                                <td><?= $key->sac_os_protocol_code ?></td>
                                                <td><?= $key->sac_protocol_code ?></td>
                                                <td><?= $key->parts_code ?></td>
                                                <td><?= $key->parts_description ?></td>
                                                @elseif($is_expedition == 2)
                                                <td><?= $key->sacBuyPartCode() ?></td>
                                                <td><?= $key->sacPartModelFilter($key->not_part) ?></td>
                                                <td><?= $key->sacPartCodeFilter($key->not_part, $key->part) ?></td>
                                                <td><?= $key->description ?></td>
                                                @else
                                                <td><?= $key->sac_remittance_part->code ?></td>
                                                <td>@if($key->product_air['model'] != null) {{ $key->product_air['model'] }} @else {{ $key->model }} @endif</td>
                                                <td>@if($key->parts['code'] != null) {{ $key->parts['code'] }} @else - @endif</td>
                                                <td>@if($key->parts['description'] != null) {{ $key->parts['description'] }} @else {{ $key->part }} @endif</td>
                                                @endif
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-end">
                                            <?= $parts->appends([])->links(); ?>
                                        </ul>
                                    </nav>
                                </div>
                                <!-- datatable ends -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
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
        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSacExpedition").addClass('sidebar-group-active active');
            $("#mSacExpeditionTrack").addClass('active');
        }, 100);
    });
    </script>
@endsection
