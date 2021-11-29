@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Prestação de Contas</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de Aprovações de Prestação de Contas
              </div>
            </div>
          </div>
        </div>
      </div>      
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/financy/accountability/all" id="searchTrip" method="GET">
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
                            <input type="text" class="form-control" id="id" name="id" value="<?= Session::get('accountability_filter_id') ?>">
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
                                            <th>Total</th>
                                            <th>Empréstimo</th>
                                            <th>Status</th>
                                            <th>Criado em</th>
                                            <th>Atualizado em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($accountability as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>">{{$key->user->short_name}}</a></td>
                                            <td>{{$key->total_money}}</td>
                                            <td>
                                                @if ($key->lending)
                                                {{$key->lending->amount_money}}
                                                @endif
                                            </td>
                                            <td>
                                                {!! $key->status->html!!}
                                            </td>
                                            <td><?= date('Y-m-d', strtotime($key->created_at)) ?></td>
                                            <td><?= date('Y-m-d', strtotime($key->updated_at)) ?></td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
														@if($key->has_analyze == 1)
                                                            <a href="/financy/accountability/edit/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i> Analisar</a>
														@endif
                                                        <?php if ($key->payment_request_id) { ?>
                                                            <a href="/financy/payment/request/print/<?= $key->payment_request_id ?>" class="dropdown-item" target="_blank" href="javascript:void(0)"><i class="bx bx-printer mr-1"></i>Impr. Solicitação Pag.</a>
                                                        <?php } ?>
                                                        <?php if ($key->receipt) { ?>
                                                            <a class="dropdown-item" target="_blank" href="<?= $key->receipt ?>"><i class="bx bx-receipt mr-1"></i> {{ __('lending_i.lt_17') }}</a>
                                                        <?php } ?>
                                                        
                                                        @if ($key->status->id >0)
                                                            <a onclick="rtd_analyzes(<?= $key->id ?>, 'App\\Model\\FinancyAccountability');" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
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
                                        <?= $accountability->appends(getSessionFilters()[0]->toArray())->links(); ?>
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
    
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('accountability_filter_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('accountability_filter_r_code') ?>']).trigger('change');
        <?php } ?>
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });

        var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('#identity').mask(mask, options);
                }
            };
        $('#identity').mask('000.000.000-009', options);

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
            $("#mFinancyAccountability").addClass('sidebar-group-active active');
            $("#mFinancyaccountabilityAll").addClass('active');
        }, 100);

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