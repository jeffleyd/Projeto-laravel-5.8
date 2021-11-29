@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Pagamento</h5>
              <div class="breadcrumb-wrapper col-12">
                Antecipar análise fiscal
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
                                            <th>Número da NF</th>
                                            <th>Anexo da NF</th>
                                            <th>Solic. de pagamento</th>
                                            <th>Verificador</th>
                                            <th>Status</th>
                                            <th>Informação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($nfs as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td><?= $key->nf_number ?></td>
                                            <td>@if ($key->nf_attach)<a href="<?= $key->nf_attach ?>" target="_blank">Ver imagem</a>@else -- @endif</td>
                                            <td>
                                                @if ($key->financy_r_payment_id > 0)
                                                <a href="/financy/payment/request/print/<?= $key->financy_r_payment_id ?>" target="_blank"> Ver mais</a>
                                                @endif
                                            </td>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></td>
                                            <td>
                                                @if ($key->is_approv == 1)
                                                    <span class="badge badge-light-success">Aprovado</span>
                                                @elseif ($key->is_repprov == 1)
                                                    <span class="badge badge-light-danger">Reprovado</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span data-toggle="popover" data-content="<?= $key->description ?>"><?= strWordCut($key->description, 25, "...") ?></span>
                                            </td>
                                            <td id="action">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="/financy/payment/supervisor/edit/<?= $key->id ?>" class="dropdown-item"><i class="bx bx-edit-alt mr-1"></i> {{ __('layout_i.op_edit') }}</a>
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
                                        <?= $nfs->render(); ?>
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
    <a href="/financy/payment/supervisor/edit/0"><button type="button" class="btn btn-sm btn-secondary">Nova análise antecipada</button></a>
</div>

<script>
    function Delete(id) {
        Swal.fire({
            title: '<?= __('news_i.la_11') ?>',
            text: "<?= __('news_i.la_12') ?>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= __('trip_i.tn_fly_toast_yes') ?>',
            cancelButtonText: '<?= __('trip_i.tn_fly_toast_no') ?>',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    window.location.href = "/financy/payment/supervisor/delete?id=" + id;
                }
            })

    }
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
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
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mFinancyPayment").addClass('sidebar-group-active active');
            $("#mFinancyPaymentSupervisor").addClass('active');
        }, 100);

    });
    </script>
@endsection