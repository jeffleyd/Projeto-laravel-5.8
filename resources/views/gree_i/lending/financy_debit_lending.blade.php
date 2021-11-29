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
                A receber
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
                                            <th>#ID</th>
                                            <th>Colaborador</th>
                                            <th>Quantia</th>
                                            <th>Status</th>
                                            <th>Bloqueado até</th>
                                            <th>Criado em</th>
                                            <th>Última atualização</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($debit as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                            <td>R$ <?= number_format($key->credit, 2, ".", "") ?></td>
                                            <td>
                                                <?php if ($key->is_active == 1) { ?>
                                                    <span class="badge badge-light-warning">No prazo</span>
                                                <?php } else if ($key->payment_in_account == 1) { ?>
                                                    <span class="badge badge-light-danger">Folha de pag</span>
                                                <?php } else if ($key->has_transfer == 1) { ?>
                                                    <span class="badge badge-light-primary">Transferido</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-success">Pago</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= date('Y-m-d', strtotime($key->time_block)) ?></td>
                                            <td><?= date('Y-m-d', strtotime($key->created_at)) ?></td>
                                            <td><?= date('Y-m-d H:i', strtotime($key->updated_at)) ?></td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/financy/lending/all?id=<?= $key->financy_lending_id ?>" target="_blank"><i class="bx bxs-bank mr-1"></i> Ver empréstimo</a>
                                                        <?php if ($key->has_transfer == 0 and $key->is_active == 1) { ?>
                                                        <a onclick="receipt(<?= $key->id ?>);" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-check-circle mr-1"></i> Transferência</a>
                                                        <?php } else if ($key->has_transfer == 1 and $key->is_active == 0) { ?>
                                                        <a class="dropdown-item" href="<?= $key->receipt ?>" target="_blank"><i class="bx bx-check-circle mr-1"></i> Comprovante</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $debit->render(); ?>
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

<div class="modal fade text-left" id="modal-attach" tabindex="-1" role="dialog" aria-labelledby="modal-attach" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title white" id="modal-attach">Debitar pagamento</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>
        <div class="modal-body">
            <form action="/financy/lending/receiver_do" method="post" id="submitTransfer" enctype="multipart/form-data">
                <input type="hidden" id="lending_id" name="lending_id" value="">
                <div class="form-group row">
                    <label class="col-12" for="receipt">Comprovante</label>
                    <div class="col-12">
                        <input type="file" id="receipt" name="receipt">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12" for="description">Observação</label>
                    <div class="col-12">
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Fale qualquer observação sobre esse pagamento..."></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12" for="total">Valor pago</label>
                    <div class="col-12">
                        <input type="text" id="total" name="total" placeholder="0.00">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Fechar</span>
            </button>
            <button type="button" onclick="Transfer();" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Confirmar</span>
            </button>
        </div>
        </div>
    </div>
    </div>

    <script>
        function account(agency, account, bank, identity, name) {
            $(".favo").html(name);
            $(".agen").html(agency);
            $(".acco").html(account);
            $(".bank").html(bank);
            $(".cpf").html(identity);
            $("#modal-bank").modal();
        }
        function receipt(index) {
            $("#lending_id").val(index);
            $("#attach").val("");
            $("#total").val("");
            $('#modal-attach').modal();
        }
        function Transfer() {
            if ($("#total").val() == "" || $("#total").val() == 0.00) {

                error('Você precisa por o valor do comprovante.');
            } else if ($("#attach").val() != "") {
                $('#modal-attach').modal('toggle');
                Swal.fire({
                    title: 'Transferir',
                    text: "Você confirmar a transferência feita?",
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
                            $("#submitTransfer").submit();
                        }
                    })
            } else {
                error('Você precisa anexar o comprovante antes de continuar.');
            }
        }
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });

        $('#total').mask('####0.00', {reverse: true});

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
            $("#mFinancy").addClass('sidebar-group-active active');
            $("#m_FinancyLending").addClass('sidebar-group-active active');
            $("#mFinancyLendingReceiver").addClass('active');
        }, 100);

    });
    </script>
@endsection