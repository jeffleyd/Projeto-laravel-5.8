@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-6 col-sm-12 col-lg-6">
						<h5 class="content-header-title float-left pr-1 mb-0">Atendimento</h5>
						<div class="breadcrumb-wrapper col-12">
							Lista de ordem de serviços para pagamento
						</div>
					</div>
					<div class="col-6 col-sm-12 col-lg-6">
						<fieldset class="form-group float-right">
							<select class="form-control" id="type_payment" name="type_payment" style="position: relative; bottom:10px; border-color: #3568df;color: #3568df;">
								<option value="1" selected>Atendimento em garantia</option>
								<option value="2">Remessa de peça</option>
							</select>
						</fieldset>
					</div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="users-list-wrapper">
                <div class="users-list-table">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
								<div class="top d-flex flex-wrap">
                                <div class="action-filters flex-grow-1">
                                    <div class="dataTables_filter mt-1">
                                        <h5>Ordem de serviços para pagamento</h5>
                                    </div>
                                </div>
                                <div class="actions action-btns d-flex align-items-center">
                                    <div class="dropdown invoice-filter-action">
                                        <button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                    </div>
                                    <div class="dropdown invoice-options">
                                        <button type="button" class="btn btn-success shadow mr-0" data-toggle="modal" data-target="#modal_export">Exportar</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                                <!-- datatable start -->
                                <div class="table-responsive">
                                    <table id="list-datatable" style="margin-bottom: 125px !important;" class="table">
                                        <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>#PROTOCOLO</th>
                                            <th>Última atualização</th>
                                            <th>Autorizada</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Observação</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($os as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td><?= $key->sac_protocol_code ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($key->updated_at)) ?></td>
                                            <td><a target="_blank" href="/sac/authorized/edit/<?= $key->authorized_id ?>"><?= strWordCut($key->sac_authorized_name, 13) ?></a></td>
                                            <td><?= number_format($key->total, 2) ?></td>
                                            <td>
                                                @if ($key->payment_nf_request == 1)
                                                    <?php $status = 2; ?>
                                                    <span class="badge badge-light-warning">Solic. NF</span>
                                                @elseif ($key->request_tec_approv == 1)
                                                    <?php $status = 1; ?>
                                                    <span class="badge badge-light-success">Enviado p/ Assitência</span>
                                                @else
                                                    <?php $status = 0; ?>
                                                    <span class="badge badge-light-secondary">Aguardando análise</span>
                                                @endif

                                                @if ($key->has_pending_payment == 1)
                                                    <span class="badge badge-light-success">Finalizado pelo SAC</span>
                                                @endif
                                            </td>
                                            <td><span data-toggle="popover" data-content="<?= $key->paid_info ?>"><?= strWordCut($key->paid_info, 25, "...") ?></span><br></td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="javascript:void(0)" json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="viewPaid(this)"><i class="bx bx-dollar-circle mr-1"></i> Realizar pagamento</a>
                                                        <a class="dropdown-item" href="/sac/warranty/os/print/<?= $key->id ?>" target="_blank"><i class="bx bx-printer mr-1"></i> Impr. OS</a>
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="viewStatus(<?= $key->id ?>, <?= $status ?>)"><i class="bx bx-edit mr-1"></i> Atualizar status</a>
                                                        @if ($key->os_signature)
                                                            <a class="dropdown-item" href="<?= $key->os_signature ?>" target="_blank"><i class="bx bxs-pencil mr-1"></i> OS Assinada!</a>
                                                        @endif
                                                        @if ($key->diagnostic_test)
                                                            <a class="dropdown-item" href="<?= $key->diagnostic_test ?>" target="_blank"><i class="bx bxs-pencil mr-1"></i> Relatório técnico</a>
                                                        @endif
                                                        <a class="dropdown-item" target="_blank" href="/sac/warranty/interactive/<?= $key->sac_protocol_id ?>"><i class="bx bx-chat mr-1"></i> Ver interações</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-end">
                                            <?= $os->appends([
                                                'code' => Session::get('sacf_code'),
                                                'os' => Session::get('sacf_os'),
                                                'status' => Session::get('sacf_status'),
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
            <!-- users list ends -->
        </div>
    </div>

    <div class="modal fade text-left" id="modal-paid" tabindex="-1" role="dialog" aria-labelledby="modal-paid" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-paid">GERAR SOLICITAÇÃO DE PAGAMENTO</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/sac/warranty/os/payment" id="send_payment" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="id" id="p_id" value="0">
                        <fieldset class="form-group">
                            <label for="total">Valor total do serviço</label>
                            <input type="text" class="form-control" name="total" id="total">
                        </fieldset>
						<fieldset class="form-group">
                            <label for="total">Valor extra/diversos</label>
                            <input type="text" class="form-control" name="total_extra" id="total_extra">
                        </fieldset>
						<fieldset class="form-group">
                            <label for="total">Valor gás</label>
                            <input type="text" class="form-control money" name="total_gas" id="total_gas">
                        </fieldset>
                        <fieldset class="form-group">
                            <select id="is_payment_request" name="is_payment_request" class="form-control">
                                <option value="0">Sem Solicitação de Pagamento</option>
                                <option value="1" selected>Com Solicitação de Pagamento</option>
                            </select>
                        </fieldset>

                        <fieldset class="form-group payment_number_nf">
                            <label for="payment_number_nf">Número da nota fiscal</label>
                            <input type="text" id="payment_number_nf" name="payment_number_nf" class="form-control">
                        </fieldset>

                        <fieldset class="form-group payment_nf">
                            <label for="payment_nf">Anexar Nota fiscal de serviço</label>
                            <input type="file" id="payment_nf" name="payment_nf">
                        </fieldset>

                        <div class="row is_thyrd mb-1" style="display:none">
                            <div class="col-12">
                                <label for="">Nome completo</label>
                                <input type="text" class="form-control" name="full_name">
                            </div>
                            <div class="col-6">
                                <label for="">Agencia</label>
                                <input type="text" class="form-control" name="agency">
                            </div>
                            <div class="col-6">
                                <label for="">Conta</label>
                                <input type="text" class="form-control" name="account">
                            </div>
                            <div class="col-6">
                                <label for="">Banco</label>
                                <input type="text" class="form-control" name="bank">
                            </div>
                            <div class="col-6">
                                <label for="">CNPJ</label>
                                <input type="text" class="form-control" name="identity" id="identity">
                            </div>
                        </div>

                        <fieldset class="form-group">
                            <label for="option">Observação no pagamento</label>
                            <textarea name="option" readonly="readonly" class="form-control" id="option" id="" cols="30" rows="10"></textarea>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-sm-block d-none">Fechar</span>
                    </button>
                    <button type="button" onclick="confirmSend()" class="btn btn-primary ml-1 btn-sm">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-sm-block d-none">Continuar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="modal-status" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-status">ATUALIZAR STATUS</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/sac/warranty/os/payment/status" id="send_payment_status" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="id" id="p_id_status" value="0">
                        <fieldset class="form-group">
                            <select id="status" name="status" class="form-control">
                                <option value="0">Aguardando análise</option>
                                <option value="1">Enviado para assitência</option>
                                <option value="2">Solic. NF</option>
                            </select>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-sm-block d-none">Fechar</span>
                    </button>
                    <button type="button" onclick="confirmSendStatus()" class="btn btn-primary ml-1 btn-sm">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-sm-block d-none">Atualizar status</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

	<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar OS para pagamento</span>
            </div>
            <form action="/sac/warranty/os/paid" id="form_modal_filter" method="GET">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Protocolo de atendimento</label>
                                <input type="text" name="code" value="{{ Session::get('sacf_code') }}" class="form-control" placeholder="G202012300009">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Ordem de serviço</label>
                                <input type="text" name="os" value="{{ Session::get('sacf_os') }}" class="form-control" placeholder="W20095">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status">
                                    <option></option>
                                    <option value="1" @if (Session::get('sacf_status') == 1) selected @endif>1 - Aguardando análise</option>
                                    <option value="2" @if (Session::get('sacf_status') == 2) selected @endif>2 - Enviado p/ assistência</option>
                                    <option value="3" @if (Session::get('sacf_status') == 3) selected @endif>3 - Solic. NF</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Filtrar</span>
                    </button>
                </div>
            </form> 
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Exportar OS para pagamento</span>
            </div>
            <form action="/sac/warranty/os/paid/export" method="post">
                <input type="hidden" name="export" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label>Data inicial</label>
                                <input type="text" name="start_date" id="start_date" class="form-control date-pick" autocomplete="off">
                            </fieldset>
                        </div>    
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label>Data Final</label>
                                <input type="text" name="end_date" id="end_date" class="form-control date-pick" autocomplete="off">
                            </fieldset>
                        </div>    
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Protocolo de atendimento</label>
                                <input type="text" name="code" class="form-control" placeholder="G202012300009">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Ordem de serviço</label>
                                <input type="text" name="os" class="form-control" placeholder="W20095">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Status pagamento</label>
                                <select class="form-control" name="is_paid">
                                    <option></option>
                                    <option value="1">Pago</option>
                                    <option value="2">Não pago</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Exportar</span>
                    </button>
                </div>
            </form> 
        </div>    
    </div>   
</div>


    <script>
        function viewPaid(elem) {

            let json_row = JSON.parse($(elem).attr("json-data"));
            var nwstr ='';
            var nwstr2 = '';
            $("#p_id").val(json_row.id);
            $("#total").val((json_row.total).toFixed(2));
            if (json_row.paid_info) {
                nwstr = json_row.paid_info;
            }
            if (json_row.observation) {
                nwstr2 = json_row.observation;

            }

            $("#option").val( nwstr2+"\n"+nwstr);

            if (json_row.authorized_id == '1864') {
                $(".is_thyrd").show();
            } else {
                $(".is_thyrd").hide();
            }

            $("#modal-paid").modal();
        }
        function viewStatus(id, status) {
            $("#p_id_status").val(id);
            $("#status").val(status);

            $("#modal-status").modal();
        }
        function confirmSend() {
            $("#modal-paid").modal('toggle');
            Swal.fire({
                title: 'Gerar pagamento',
                text: "Ao confirmar essa etapa, será gerado uma solicitação de pagamento.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar!',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    $("#send_payment").submit();
                }
            })
        }
        function confirmSendStatus() {
            $("#modal-status").modal('toggle');
            block();
            $("#send_payment_status").submit();
        }
        $(document).ready(function () {
            $(".js-select2").select2({
                maximumSelectionLength: 1,
            });
			
			$('.date-pick').pickadate({
				formatSubmit: 'yyyy-mm-dd',
				format: 'yyyy-mm-dd',
				today: 'Hoje',
				clear: 'Limpar',
				close: 'Fechar',
				monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
				weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
			});    


            $('#identity').mask('00.000.000/0000-00', {reverse: false});
            $("#is_payment_request").change(function (e) {
                if($("#is_payment_request").val() == 1) {
                    $(".payment_number_nf").show();
                    $(".payment_nf").show();
                } else {
                    $(".payment_number_nf").hide();
                    $(".payment_nf").hide();
                }

            });

            $('#total').mask('000.00', {reverse: true});
			$('#total_extra').mask('000.00', {reverse: true});
			$('.money').mask('000.00', {reverse: true});
			
			$("#type_payment").change(function (e) {
				if($(this).val() == 2) {
					window.location.href = "/sac/assistance/remittance/list/payment";
				} else {
					window.location.href = "/sac/warranty/os/paid";
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

            $('[data-toggle="popover"]').popover({
                placement: 'right',
                trigger: 'hover',
            });

            setInterval(() => {
                $("#mAfterSales").addClass('sidebar-group-active active');
                $("#mTAssist").addClass('sidebar-group-active active');
                $("#mTAssistOsPaid").addClass('active');
            }, 100);

        });
    </script>
@endsection
