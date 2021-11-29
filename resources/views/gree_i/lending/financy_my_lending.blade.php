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
                {{ __('layout_i.menu_lending_my') }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php if (isset($ac_bank)) { ?>
        <?php if ($ac_bank->used_credit > 0) { ?>
            <div class="alert alert-danger alert-dismissible mb-2 has-used" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error"></i>
                    <span>
                        <p class="mb-0">{{ __('lending_i.lrn_2') }} <b><?= date('Y-m-d', strtotime($ac_bank->time_credit_block)) ?></b></p>
                    </span>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
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
                                            <th>{{ __('lending_i.lt_1') }}</th>
                                            <th>{{ __('lending_i.lt_2') }}</th>
                                            <th>{{ __('lending_i.lt_3') }}</th>
                                            <th>{{ __('lending_i.lt_4') }}</th>
                                            <th>{{ __('lending_i.lt_5') }}</th>
                                            <th>{{ __('trip_i.tmtp_actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lending as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td><span data-toggle="popover" data-content="<?= $key->description ?>"><?= strWordCut($key->description, 25, "...") ?></span></td>
                                            <td>R$ <?= number_format($key->amount, 2, ".", "") ?></td>
                                            <td><?= date('Y-m-d', strtotime($key->created_at)) ?></td>
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
                                            <td>
                                                <?php $attach = App\Model\FinancyLendingAttach::where('financy_lending_id', $key->id)->first(); ?>
                                                <?php if ($attach) { ?>
                                                    <?php if ($attach->is_module) { ?>
                                                        <a target="_blank" href="/module-view/<?= $attach->id_module ?>/1" href="javascript:void(0)"><i class="bx bxs-data mr-1"></i></a>
                                                    <?php } else { ?>
                                                        <a target="_blank" href="<?= $attach->url_file ?>" href="javascript:void(0)"><i class="bx bxs-file mr-1"></i></a>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <?php $user = App\Model\Users::where('r_code', $key->r_code)->first(); ?>
                                                        <a onclick="account('<?= $key->agency ?>', '<?= $key->account ?>', '<?= $key->bank ?>', '<?= $key->identity ?>', '<?= $user->first_name .' '. $user->last_name ?>');" class="dropdown-item" href="javascript:void(0)"><i class="bx bxs-bank mr-1"></i> {{ __('lending_i.lt_16') }}</a>
                                                        @php
														$payment = $key->relation_payment();
														@endphp
                                                        @if ($payment)
                                                            <a href="/financy/payment/request/print/<?= $payment->financy_r_payment_id ?>" class="dropdown-item" target="_blank" href="javascript:void(0)"><i class="bx bx-printer mr-1"></i>Impr. Solicitação</a>
                                                        @endif
                                                        <?php if ($key->receipt) { ?>
                                                            <a class="dropdown-item" target="_blank" href="<?= $key->receipt ?>"><i class="bx bx-receipt mr-1"></i> {{ __('lending_i.lt_17') }}</a>
                                                        <?php } ?>
                                                        <a onclick="rtd_analyzes(<?= $key->id ?>, 'App\\Model\\FinancyLending');" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $lending->render(); ?>
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

<div class="modal fade text-left" id="modal-bank" tabindex="-1" role="dialog" aria-labelledby="modal-bank" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modal-bank">{{ __('lending_i.lrn_27') }}</h3>
          <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body text-center">
            <div class="font-w600 mb-1"><span class="favo"></span></div>
            <div class="font-size-sm text-muted"><b>{{ __('lending_i.lrn_22') }}</b> <span class="agen"></span> </div>
            <div class="font-size-sm text-muted"><b>{{ __('lending_i.lrn_23') }}</b> <span class="acco"></span> </div>
            <div class="font-size-sm text-muted"><b>{{ __('lending_i.lrn_24') }}</b> <span class="bank"></span> </div>
            <div class="font-size-sm text-muted"><b>{{ __('lending_i.lrn_25') }}</b> <span class="cpf"></span> </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('lending_i.lrn_32') }}</span>
          </button>
        </div>
      </div>
    </div>
</div>

@include('gree_i.misc.components.analyze.history.view')

<script>
        @include('gree_i.misc.components.analyze.history.script')
        function account(agency, account, bank, identity, name) {
            $(".favo").html(name);
            $(".agen").html(agency);
            $(".acco").html(account);
            $(".bank").html(bank);
            $(".cpf").html(identity);
            $("#modal-bank").modal();
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
            $("#mFinancyLending").addClass('sidebar-group-active active');
            $("#mFinancyLendingMy").addClass('active');
        }, 100);

    });
    </script>
@endsection