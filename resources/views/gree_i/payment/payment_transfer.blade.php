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
                            Transferir pagamento
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="users-list-filter px-1">
                <form action="{{Request::url()}}" id="search" method="GET">
                    <div class="row border rounded py-2 mb-2">
                        <div class="col-12 col-sm-12 col-lg-5">
                            <label for="users-list-verified">Solicitante</label>
                            <fieldset class="form-group">
                                <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                    <option></option>
                                    <?php foreach ($userall as $key) { ?>
                                    <option value="<?= $key->r_code ?>"
                                            @if ($key->r_code == Session::get('paymentf_r_code') ) selected @endif
                                    ><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                    <?php } ?>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-3">
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
                                            <th>Conteúdo</th>
                                            <th>Solicitante</th>
                                            <th>Beneficiário</th>
                                            <th>Quantia</th>
                                            <th>Criado em</th>
                                            <th>Vencimento em</th>
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
                                            <td>{{formatMoney($key->amount_liquid)}}</td>
                                            <td>
                                                @if(Session::get('lang') == 'en')
                                                    {{$key->created_at->format('Y-m-d')}}
                                                @else
                                                    {{$key->created_at->format('d/m/Y')}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(Session::get('lang') == 'en')
                                                    {{$key->due_date->format('Y-m-d')}}
                                                @else
                                                    {{$key->due_date->format('d/m/Y')}}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a onclick="account('<?= $key->agency ?>', '<?= $key->account ?>', '<?= $key->bank ?>', '<?= $key->identity ?>', '<?= $key->b_first_name .' '. $key->b_last_name ?>');" class="dropdown-item" href="javascript:void(0)"><i class="bx bxs-bank mr-1"></i> {{ __('lending_i.lt_16') }}</a>
                                                        @if ($relation)
                                                            @if (get_class($relation) == 'App\Model\FinancyRefund')
                                                                <a onclick="Receipt(<?= $key->id ?>, 1);" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-check-circle mr-1"></i> {{ __('lending_i.lt_28') }}</a>
                                                            @else
                                                                <a onclick="Receipt(<?= $key->id ?>, 0);" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-check-circle mr-1"></i> {{ __('lending_i.lt_28') }}</a>
                                                            @endif
                                                        @else
                                                            <a onclick="Receipt(<?= $key->id ?>, 0);" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-check-circle mr-1"></i> {{ __('lending_i.lt_28') }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-end">
                                            <?= $payment->appends(['r_code' => Session::get('paymentf_r_code'), 'id' => Session::get('paymentf_id')])->links(); ?>
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
                    <h5 class="modal-title white" id="modal-attach">Comprovante de pagamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/financy/payment/transfer_do" method="post" id="submitTransfer" enctype="multipart/form-data">
                        <input type="hidden" id="payment_id" name="payment_id" value="">
                        <div class="form-group row">
                            <label class="col-12" for="p_method">Metódo de pagamento</label>
                            <div class="col-12">
                                <select id="p_method" class="form-control" name="p_method">
                                    <option value="2" selected>Trasferência/Deb. Automático</option>
                                    <option value="3">Caixa</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row amount">
                            <label class="col-12" for="amount">Valor do devedor (Reembolso)</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group row attach">
                            <label class="col-12" for="attach">Anexar comprovante</label>
                            <div class="col-12">
                                <input type="file" id="attach" name="attach">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="description">Observação</label>
                            <div class="col-12">
                                <textarea class="form-control" id="description" name="description" rows="6">Foi realizado a sua transferência, demorará um prazo de até 2 dias úteis para contas que não são do banco Bradesco</textarea>
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
                        <span class="d-none d-sm-block">Comprovar</span>
                    </button>
                </div>
            </div>
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

    <script>
        function account(agency, account, bank, identity, name) {
            $(".favo").html(name);
            $(".agen").html(agency);
            $(".acco").html(account);
            $(".bank").html(bank);
            $(".cpf").html(identity);
            $("#modal-bank").modal();
        }
        function Receipt(index, vis) {
            // if (vis == 1) {
            //     $(".amount").show();
            // } else {
            //     $(".amount").hide();
            // }
            $(".amount").hide();
            $("#payment_id").val(index);
            $("#attach").val("");
            $(".attach").show();
            $('#modal-attach').modal();
            $("#amount").val("");
            $("#description").val("Foi realizado a sua transferência, demorará um prazo de até 2 dias úteis para contas que não são do banco Bradesco.");
            $("#p_method").val(2);
        }
        function Transfer() {

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
            });
        }
        $(document).ready(function () {
            $(".js-select2").select2({
                maximumSelectionLength: 1,
            });
            $('[data-toggle="popover"]').popover({
                placement: 'right',
                trigger: 'hover',
            });

            $('#amount').mask('00000.00', {reverse: true});

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
                $("#mFinancyPaymentTransfer").addClass('active');
            }, 100);

        });
    </script>
@endsection
