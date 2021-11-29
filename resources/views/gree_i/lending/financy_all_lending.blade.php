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
                {{ __('layout_i.menu_lending_all') }}
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
                        <p class="mb-0">{{ __('lending_i.lt_1') }}</p>
                        <p class="mb-0">{{ __('lending_i.lt_2') }} <b><?= date('Y-m-d', strtotime($ac_bank->time_credit_block)) ?></b></p>
                    </span>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/financy/lending/all" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-8">
                        <label for="users-list-verified">{{ __('trip_i.tntp_collaborator') }}</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                <option></option>
                                <?php foreach ($userall as $key) { ?>
                                    <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                <?php } ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">{{ __('trip_i.tntp_id') }}</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" id="id" name="id" value="<?= Session::get('lendingf_id') ?>">
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
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>Solicitante</th>
                                            <th>{{ __('lending_i.lt_1') }}</th>
                                            <th>{{ __('lending_i.lt_2') }}</th>
                                            <th>{{ __('lending_i.lt_3') }}</th>
                                            <th>{{ __('lending_i.lt_4') }}</th>
                                            <th>{{ __('lending_i.lt_5') }}</th>
                                            <th>{{ __('lending_i.lt_6') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lending as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
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
                                                            <a class="dropdown-item" href="<?= $key->receipt ?>" target="_blank"><i class="bx bx-receipt mr-1"></i> {{ __('lending_i.lt_17') }}</a>
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
                                        <?= $lending->appends(['r_code' => Session::get('lendingf_r_code'), 'id' => Session::get('lendingf_id')])->links(); ?>
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
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('lendingf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('lendingf_r_code') ?>']).trigger('change');
        <?php } ?>
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
            $("#mFinancyLendingAll").addClass('active');
        }, 100);

    });
    </script>
@endsection