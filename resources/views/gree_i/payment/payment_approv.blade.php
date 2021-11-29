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
                Solicitações para aprovação
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/financy/payment/all" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="users-list-verified">Solicitante</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                <option></option>
                                <?php foreach ($userall as $key) { ?>
                                    <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                <?php } ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="nf">Pesquisar por nota fiscal</label>
                        <fieldset class="form-group">
                            <input type="number" class="form-control" id="nf" name="nf" value="<?= Session::get('paymentf_nf') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">{{ __('trip_i.tntp_id') }}</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" id="id" name="id" value="<?= Session::get('paymentf_id') ?>">
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
                                            <th>Conteúdo</th>
                                            <th>Solicitante</th>
                                            <th>Beneficiário</th>
                                            <th>Quantia</th>
                                            <th>Criado em</th>
                                            <th>Vencimento em</th>
                                            <th>Status</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($payment as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td>
                                                <span data-toggle="popover" data-content="<?= $key->description ?>"><?= strWordCut($key->description, 25, "...") ?></span>

                                            </td>
                                            <td>
                                                <?= $key->r_first_name ?> <?= $key->r_last_name ?>
                                            </td>
                                            <td>
                                                <?php if (empty($key->recipient_r_code)) { ?>
                                                    <?= $key->recipient ?>
                                                <?php } else { ?>
                                                    <?= $key->b_first_name ?> <?= $key->b_last_name ?>
                                                <?php } ?>
                                            </td>
                                            <td>R$ <?= number_format($key->amount_liquid, 2, ',', '.') ?></td>
                                            <td>
                                                <?= date('Y-m-d', strtotime($key->created_at)) ?>
                                            </td>
                                            <td>
                                                <?= date('Y-m-d', strtotime($key->due_date)) ?>
                                            </td>
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
                                            <td id="action">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="/financy/payment/request/approv/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-show-alt mr-1"></i> Análisar</a>
                                                        <a onclick="rtd_analyzes({{$key->id}}, 'App\\Model\\FinancyRPayment');" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $payment->appends(['r_code' => Session::get('paymentf_r_code')])->links(); ?>
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


    @include('gree_i.misc.components.analyze.history.view')
    <script>
    @include('gree_i.misc.components.analyze.history.script')
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('paymentf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('paymentf_r_code') ?>']).trigger('change');
        <?php } ?>
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
            $("#mFinancyPaymentApprov").addClass('active');
        }, 100);

    });
    </script>
@endsection
