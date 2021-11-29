@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Reembolso</h5>
              <div class="breadcrumb-wrapper col-12">
                Meus reembolsos
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="alert alert-primary alert-dismissible mb-2" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bxs-info-circle"></i>
            <span>
            Atualize seus dados da sua conta bancária para poder enviar para análise.

            </span>
            <div style="width:100%">
            <button type="button" class="btn btn-sm btn-secondary float-right" data-toggle="modal" data-target="#modal-account">{{ __('lending_i.lrn_26') }}</button>
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
                                            <th>Total</th>
                                            <th>Empréstimo</th>
                                            <th>Status</th>
                                            <th>Criado em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($refund as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td>R$ <?= number_format($key->total, 2, ".", "") ?></td>
                                            <td>R$ <?= number_format($key->lending, 2, ".", "") ?></td>
                                            <td>
                                                @if ($key->is_reprov == 1)
                                                    <span class="badge badge-light-danger">REPROVADO</span>
                                                @elseif ($key->is_paid == 1)
                                                    <span class="badge badge-light-success">PAGO</span>
                                                @elseif ($key->is_approv == 1)
                                                    <span class="badge badge-light-success">APROVADO</span>
                                                @elseif ($key->has_analyze == 1)
                                                    <span class="badge badge-light-info">EM ANÁLISE</span>
                                                @else
                                                    <span class="badge badge-light-secondary">NÃO ENVIADO</span>
                                                @endif
                                            </td>
                                            <td><?= date('Y-m-d', strtotime($key->created_at)) ?></td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="/financy/refund/edit/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                        @php
														$payment = $key->relation_payment();
														@endphp
                                                        @if ($payment)
                                                            <a href="/financy/payment/request/print/<?= $payment->financy_r_payment_id ?>" class="dropdown-item" target="_blank" href="javascript:void(0)"><i class="bx bx-printer mr-1"></i>Impr. Solicitação</a>
                                                        @endif
                                                        <?php if ($key->receipt) { ?>
                                                            <a class="dropdown-item" target="_blank" href="<?= $key->receipt ?>"><i class="bx bx-receipt mr-1"></i> {{ __('lending_i.lt_17') }}</a>
                                                        <?php } ?>
                                                        <a onclick="rtd_analyzes({{$key->id}}, 'App\\Model\\FinancyRefund');" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $refund->render(); ?>
                                    </ul>
                                </nav>
                            </div>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="card border-info text-center bg-transparent">
                <div class="card-content">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12 col-sm-12 d-flex justify-content-center">
                        <img src="/admin/app-assets/images/backgrounds/process_approv.png" alt="element 04" class="float-left mt-1 img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </section>
    </div>
</div>


<div class="modal fade text-left" id="modal-account" tabindex="-1" role="dialog" aria-labelledby="modal-account" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modal-account">{{ __('lending_i.lrn_27') }}</h3>
          <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
            <form id="UpdateAccount" action="#" method="post">
                <div class="form-group">
                    <label for="agency">{{ __('lending_i.lrn_28') }}</label>
                    <input class="form-control" type="text" name="agency" id="agency" value="<?php if (isset($a_bank)) { ?><?= $a_bank->agency ?><?php } ?>">
                </div>
                <div class="form-group">
                    <label for="account">{{ __('lending_i.lrn_29') }}</label>
                    <input class="form-control" type="text" name="account" id="account" value="<?php if (isset($a_bank)) { ?><?= $a_bank->account ?><?php } ?>">
                </div>
                <div class="form-group">
                    <label for="bank">{{ __('lending_i.lrn_30') }}</label>
                    <input class="form-control" type="text" name="bank" id="bank" value="<?php if (isset($a_bank)) { ?><?= $a_bank->bank ?><?php } ?>">
                </div>
                <div class="form-group">
                    <label for="identity">{{ __('lending_i.lrn_31') }}</label>
                    <input class="form-control" type="text" name="identity" id="identity" value="<?php if (isset($a_bank)) { ?><?= $a_bank->identity ?><?php } ?>">
                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('lending_i.lrn_32') }}</span>
          </button>
          <button type="submit" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('lending_i.lrn_33') }}</span>
          </button>
        </div>
        </form>
      </div>
    </div>
  </div>

    @include('gree_i.misc.components.analyze.history.view')
    <script>
    @include('gree_i.misc.components.analyze.history.script')
    function Approv(index) {
        Swal.fire({
            title: 'Aprovação',
            text: "Confirma o envio da solicitação de reembolso para aprovação?",
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
                    window.location.href = "/financy/refund/send/analyze/"+ index;
                }
            })
    }

    $(document).ready(function () {
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
            $("#mFinancyRefund").addClass('sidebar-group-active active');
            $("#mFinancyRefundMy").addClass('active');
        }, 100);

        var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('#identity').mask(mask, options);
                }
            };
        $('#identity').mask('000.000.000-009', options);

        $("#UpdateAccount").submit(function (e) {
            if ($("#agency").val() == "") {

                error('<?= __('lending_i.lrn_43') ?>');
                e.preventDefault();
            } else if ($("#account").val() == "") {

                error('<?= __('lending_i.lrn_44') ?>');
                e.preventDefault();
            } else if ($("#bank").val() == "") {

                error('<?= __('lending_i.lrn_45') ?>');
                e.preventDefault();
            } else if ($("#identity").val() == "") {

                error('<?= __('lending_i.lrn_46') ?>');
                e.preventDefault();
            } else {

                $.ajax({
                    type: "POST",
                    url: "/financy/lending/bank_upd",
                    data: {agency: $("#agency").val(), account: $("#account").val(), bank: $("#bank").val(), identity: $("#identity").val()},
                    success: function (response) {
                        success('<?= __('lending_i.lrn_47') ?>');
                        $("#modal-account").modal('toggle');
                    }
                });

                e.preventDefault();
            }


        });

    });
    </script>
@endsection
