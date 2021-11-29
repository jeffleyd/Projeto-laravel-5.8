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
                            Minhas solicitações
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
                                            <th>#ID</th>
                                            <th>Conteúdo</th>
                                            <th>Solicitante</th>
                                            <th>Beneficiário</th>
                                            <th>Quantia</th>
                                            <th>Criado em</th>
                                            <th>Vencimento em</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($payment as $key) { ?>
                                        @php
                                            $relation = $key->relationship();
                                        @endphp
                                        <tr>
                                            <td>
                                                <span style="font-size:14px"><b><?= $key->code ?></b></span>
                                                @if ($relation)
                                                    <br><span style="font-size:10px">Origem: {{$relation->code}}</span>
                                                @endif
                                            </td>
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
                                                        <a href="/financy/payment/request/print/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-printer mr-1"></i> Imprimir</a>
                                                        <?php if ($key->receipt) { ?>
                                                        <a class="dropdown-item" target="_blank" href="<?= $key->receipt ?>"><i class="bx bx-receipt mr-1"></i> {{ __('lending_i.lt_17') }}</a>
                                                        <?php } ?>
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
                                            <?= $payment->render(); ?>

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
                $("#mFinancyPaymentMy").addClass('active');
            }, 100);

        });
    </script>
@endsection
