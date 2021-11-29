@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_trip_credit') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('layout_i.menu_trip_credit_subtitle') }}
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
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Agência</th>
                                            <th>Viagem</th>
                                            <th>Crédito</th>
                                            <th>Status</th>
                                            <th>Creditado em</th>
                                            <th>Última atualização</th>
                                            <th class="text-center">Crédito usado?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($credit as $key) { ?>
                                        <tr>
                                            <td>
                                                <?= $key->id ?>
                                            </td>
                                            <td>
                                                <?= $key->name ?>
                                            </td>
                                            <td>
                                                <a href="/trip/review/<?= $key->trip_plan_id ?>">Ver viagem</a>
                                            </td>
                                            <td>
                                                <?= number_format($key->total, 2, '.', '') ?>
                                            </td>
                                            <td>
                                                <?php if ($key->has_used == 1) { ?>
                                                    <span class="badge badge-success">Usado</span></td>
                                                <?php } else { ?>
                                                    <span class="badge badge-info">Disponível</span></td>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?= date('Y-m-d H:i', strtotime($key->created_at)) ?>
                                            </td>
                                            <td>
                                                <?= date('Y-m-d H:i', strtotime($key->updated_at)) ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($key->has_used == 0) { ?> 
                                                <a onclick="user(<?= $key->id ?>)" href="javascript:void(0)"><i class="bx bx-check-circle mr-1"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $credit->render(); ?>
                                        
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
        function user(index) {
            Swal.fire({
                title: 'Confirme',
                text: "Você confirmar que usou o crédito dessa viagem?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
                cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        block();
                        window.location.href = "/trip/credits_do/" + index;
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
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mTrip").addClass('sidebar-group-active active');
            $("#mTripCredits").addClass('active');
        }, 100);

    });
    </script>
@endsection