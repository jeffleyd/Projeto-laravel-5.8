@extends('gree_i.layout')

@section('content')
<?= $session_exped = Session::get('ses_type_expedition'); ?>
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-6 col-sm-12 col-lg-6">
                    <h5 class="content-header-title float-left pr-1 mb-0">Expedição</h5>
                    <div class="breadcrumb-wrapper">
                        Peças ainda não confirmadas para envio
                    </div>
                </div>
                <div class="col-6 col-sm-12 col-lg-6">
                    <form action="/sac/expedition/pending" id="form_expedition" method="GET">
                        <fieldset class="form-group float-right">
                            <select class="form-control" id="type_expedition" name="type_expedition" style="position: relative; bottom:10px; border-color: #3568df;color: #3568df;">
                                <option value="1" @if ($session_exped == 1) selected @endif>Ordem de serviço</option>
                                <option value="2" @if ($session_exped == 2) selected @endif>Ordem de compra</option>
                                <option value="3" @if ($session_exped == 3) selected @endif>Remessa de peça</option>
                            </select>
                        </fieldset>
                    </form>    
                </div>
            </div>
        </div>
    </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/sac/expedition/pending" id="searchTrip" method="GET">
                <input type="hidden" name="type_expedition" id="input_type_exped">
                <div class="row border rounded py-2 mb-2">
                    @if($session_exped == 1)
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="users-list-verified">Pesquisar por protocolo</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="protocol" value="<?= Session::get('filter_protocol') ?>" placeholder="Exem. G202012457">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="users-list-verified">Pesquisar por O.S</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="os" value="<?= Session::get('filter_os') ?>" placeholder="Exem. W20124">
                        </fieldset>
                    </div>
                    @elseif($session_exped == 2)
                    <div class="col-12 col-sm-12 col-lg-10">
                        <label for="users-list-verified">Pesquisar por Ordem de compra</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="order_purchase" value="<?= Session::get('filter_order_purchase') ?>" placeholder="Exem. G20124">
                        </fieldset>
                    </div>
                    @else
                    <div class="col-12 col-sm-12 col-lg-10">
                        <label for="users-list-verified">Pesquisar por código de remessa</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="code_remittance" value="<?= Session::get('filter_code_remittance') ?>" placeholder="Exem. R20124">
                        </fieldset>
                    </div>
                    @endif
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <form action="/sac/expedition/pending_do" id="sendForm" method="post">
        <input type="hidden" name="nf_number" id="nf_number">
        <input type="hidden" name="total" id="total" value="0.00">
        <input type="hidden" name="track" id="track" value="">
        <input type="hidden" name="transport" id="transport" value="">
        <input type="hidden" name="arrival" id="arrival" value="">
        <input type="hidden" name="is_expedition" id="is_expedition" value="">
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
                                                <th class="text-center">#</th>
                                                @if($session_exped == 1)
                                                <th>O.S</th>
                                                <th>Protocolo</th>
                                                <th>Código</th>
                                                <th>Descrição</th>
                                                @elseif($session_exped == 2)    
                                                <th>Ordem Compra</th>
                                                <th>Código</th>
                                                <th>Descrição</th>
                                                @else
                                                <th>Remessa</th>
                                                <th>Código</th>
                                                <th>Modelo</th>
                                                <th>Peça</th>
                                                @endif
                                                <th>Solicitado em</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; foreach ($expedition as $key) { ?>
                                            <tr>
                                                <td class="text-center">
                                                    <fieldset>
                                                        <div class="checkbox checkbox-shadow">
                                                            <input type="checkbox" id="checkitem<?= $i ?>" value="<?= $key->id ?>" name="checkitem[]">
                                                            <label for="checkitem<?= $i ?>"></label>
                                                        </div>
                                                    </fieldset>
                                                </td>

                                                @if($session_exped == 1)
                                                <td><?= $key->SacProtocol->firstsacosprotocol ?></td>
                                                <td><?= $key->SacProtocol->code ?></td>
                                                <td><?= $key->modelParts->code ?></td>
                                                <td><?= $key->modelParts->description ?></td>
                                                @elseif($session_exped == 2)
                                                <td><?= $key->sacBuyPartCode() ?></td>
                                                <td><?= $key->sacPart->code ?></td>
                                                <td><?= $key->sacPartCodeFilter($key->not_part, $key->part) ?></td>
                                                @else
                                                <td><?= $key->sac_remittance_part->code ?></td>
                                                <td>@if($key->parts['code'] != null) {{ $key->parts['code'] }} @else - @endif</td>
                                                <td>@if($key->product_air['model'] != null) {{ $key->product_air['model'] }} @else {{ $key->model }} @endif</td>
                                                <td>@if($key->parts['description'] != null) {{ $key->parts['description'] }} @else {{ $key->part }} @endif</td>
                                                @endif
                                                <td><?= date('d-m-Y H:i', strtotime($key->updated_at)) ?></td>
                                            </tr>
                                            <?php $i++; } ?>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-end">
                                            <?= $expedition->appends([
                                                'type_expedition' => Session::get('ses_type_expedition'),
                                            ])->links(); ?>
                                        </ul>
                                    </nav>
                                </div>
                                <!-- datatable ends -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>

<?php if (count($expedition) > 0) { ?>
<div class="mb-2" style="width: 390px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
        <button type="button" onclick="confirmSend();" class="btn btn-success">
            Confirmar envio de peças
        </button>
</div>
<?php } ?>

<div class="modal fade text-left" id="track-modal" tabindex="-1" role="dialog" aria-labelledby="track-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">INFORMAÇÕES DE ENVIO DO PACOTE</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
            <fieldset class="form-group">
                <label for="input_nf_number">Número da nota fiscal</label>
                <input type="text" class="form-control" id="input_nf_number">
            </fieldset>
            <fieldset class="form-group">
                <label for="input_total">Total pago no envio</label>
                <input type="text" class="form-control" id="input_total" placeholder="0.00">
            </fieldset>
            <fieldset class="form-group">
                <label for="input_transport">Transportadora (Não é obrigatório)</label>
                <select class="form-control" id="input_transport">
                    <option value=""></option>
                    <option value="CORREIOS">CORREIOS</option>
                    <option value="AGILLOG">AGILLOG</option>
                    <option value="BRINGER">BRINGER</option>
                    <option value="AVIAT CARGO">AVIAT CARGO</option>
                    <option value="ACTUAL CARGO">ACTUAL CARGO</option>
                    <option value="TNT / FEDEX">TNT / FEDEX</option>
					<option value="PRONTO CARGO">PRONTO CARGO</option>
                    <option value="ACTUAL CARGO">ACTUAL CARGO</option>
                    <option value="CLIENTE RETIRA">CLIENTE RETIRA</option>
                </select>
            </fieldset>
            <fieldset class="form-group">
                <label for="input_track">Código de rastreio (Não é obrigatório)</label>
                <input type="text" style="text-transform: uppercase" class="form-control" id="input_track" placeholder="XHK1425752">
            </fieldset>
            <fieldset class="form-group">
                <label for="input_arrival">Previsão de chegada</label>
                <input type="text" class="form-control" id="input_arrival" placeholder="2020-06-18">
            </fieldset>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-sm-block d-none">Fechar</span>
          </button>
          <button type="button" onclick="sendInfo()" class="btn btn-primary ml-1 btn-sm">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-sm-block d-none">Concluir</span>
          </button>
        </div>
      </div>
    </div>
  </div>

<script>
    function confirmSend() {
        if ($(':checkbox[name="checkitem[]"]:checked').length > 0) {

            $("#track-modal").modal();

        } else {
            error('Selecione ao menos 1 peça para envio.');
        }
    }
    function sendInfo() {
        if ($("#input_nf_number").val() == "") {
            
            return error('Preecha o número da nota fiscal.');
        } else if ($("#input_total").val() == "") {
            
            return error('Digite o valor total do gasto para envio.');
        }

        $("#track-modal").modal('toggle');
        $("#nf_number").val($("#input_nf_number").val());
        $("#total").val($("#input_total").val());
        $("#track").val($("#input_track").val());
        $("#transport").val($("#input_transport").val());
        $("#arrival").val($("#input_arrival").val());
        $("#is_expedition").val($("#type_expedition").val());

        $("#sendForm").submit();
        block();
    }
    $(document).ready(function () {
        $('#input_total').mask('0000.00', {reverse: true});
        $('#input_arrival').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
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

        $("#input_type_exped").val($("#type_expedition").val());
        $("#type_expedition").change(function(){
            $("#form_expedition").submit();
        });

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSacExpedition").addClass('sidebar-group-active active');
            $("#mSacExpeditionPending").addClass('active');
        }, 100);

    });
</script>
@endsection