@extends('gree_commercial_external.layout')

@section('page-css')

    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
@endsection

@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Solicitações de verbas</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#add-contact"><i class="fa fa-filter"></i> Filtrar</button>
                <a class="btn btn-info d-none d-lg-block m-l-15" href="/comercial/operacao/verba-comercial/novo">
                    <i class="fa fa-plus-circle"></i> Novo solicitação
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="background-color: #03a9f3;color: #fff;">
                                <th>Código</th>
                                <th>Cliente</th>
								<th>Tipo de verba</th>
                                <th>Criado em</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($verbs as $key)
                                <tr>
                                    <td>{{$key->code}}</td>
                                    <td>{{$key->client_company_name}}</td>
									<td>{{$key->type_documents_name}}</td>
                                    <td>{{date('d/m/Y', strtotime($key->created_at))}}</td>
                                    <td>
                                        @if ($key->is_cancelled)
                                            <span class="label label-danger">Cancelado</span>
                                        @elseif ($key->is_reprov)
                                            <span class="label label-danger">Reprovado</span>
                                        @elseif ($key->is_approv)
                                            <span class="label label-success">Aprovado</span>
                                        @elseif ($key->has_analyze)
                                            <span class="label label-warning">Em análise</span>
                                        @elseif ($key->waiting_assign)
                                            <span class="label label-info">Aguard. Comprovação</span>
                                        @else
                                            <span class="label bg-dark">Aguard. Envio P/ Aprovação</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                            <option></option>
                                            @if ($key->waiting_assign and !$key->is_cancelled)
                                                <option value="1">Enviar Comprovação</option>
                                            @endif
                                            @if (!$key->has_analyze and !$key->waiting_assign and !$key->is_cancelled)
                                                <option value="4">Enviar p/ Aprovação</option>
                                            @endif
                                            @if (!$key->has_analyze and !$key->is_cancelled)
                                                <option value="5">Editar</option>
                                            @endif
                                            @if (!$key->is_cancelled and ($key->has_analyze or !$key->is_approv))
                                                <option value="6">Cancelar</option>
                                            @endif
                                            <option value="2">Hist. Análises</option>
											<option value="3">Imprimir</option>
                                            @if ($key->type_payment == 2)
											<option value="7">Imprimir Nota de crédito</option>
											@elseif ($key->type_payment == 3)
											<option value="8">Imprimir solic. pagamento</option>
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $verbs->appends(getSessionFilters()[0]->toArray())->links(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="receiverAssignModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enviar Comprovação</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="/comercial/operacao/verba-comercial/comprovacao/validar" method="POST" id="formSign">
                    <input type="hidden" name="budget_id" id="budget_id">
                    <div class="row">
                        <div class="col-sm-12">
                            <p>
                                Para prosseguir com sua solicitação, você precisa comprovar a solicitação, podendo
                                pegar assinatura do cliente, email do cliente confirmando, ordem de serviço do cliente...
                            </p>
                        </div>
                        <div class="col-sm-12 mb-4 mt-2 input-group">
                            <input type="file" name="budget_file" value="" class="form-control" />
                            <div class="input-group-append">
                                <button class="btn btn-info" type="button" onclick="sendFileOrder();">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Arquivo</th>
                                        <th>Visualizar</th>
                                        <th class="text-nowrap">Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody class="listfiles">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" onclick="sendAssign()" class="btn btn-success pull-right">Enviar</button>
            </div>
        </div>
    </div>
</div>

<div id="add-contact" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Filtrar solicitação</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="filterData">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="name">Código</label>
                            <input type="text" name="code" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="name">Razão Social / Nome</label>
                            <input type="text" name="name" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-12">
                            <label for="name">CNPJ / RG</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <select id="type_people" class="select-group" style="height: 38px;background-color: #eeeeee;border-color: #eeeeee;">
                                        <option value="1">CNPJ</option>
                                        <option value="2">RG</option>
                                    </select>
                                </span>
                                <input type="text" class="form-control" name="identity" id="identity" value="" placeholder="00.000.000/0000-00" required/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" id="filterNow" class="btn btn-success pull-right">Filtrar</button>
            </div>
        </div>
    </div>
</div>

<form action="/comercial/operacao/verba-comercial/cancelar" method="post" id="form_request_cancel">
    <input type="hidden" name="id" id="cancel_id">
    <input type="hidden" name="cancel_reason" id="cancel_reason">
</form>

@include('gree_commercial_external.components.analyze.history.view')
@endsection

@section('page-scripts')

    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>

    @include('gree_commercial_external.components.analyze.history.script')
    <script type="text/javascript">
        var budgetid;
        function action($this = '') {
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                $('input[name="budget_file"]').val('');
                $('#budget_id').val(json.id);
                $('#receiverAssignModal').modal();
                reloadFiles(json.budget_commercial_attach);
                budgetid = json.id;
            } else if ($($this).val() == 2) {
                rtd_analyzes(json.id, "App\\Model\\Commercial\\BudgetCommercial");
            } else if ($($this).val() == 3) {
				window.open('/comercial/operacao/verba-comercial/imprimir/'+json.id, '_blank');
			} else if ($($this).val() == 4) {
                Swal.fire({
                    title: 'Enviar para Aprovação',
                    text: "Deseja confirmar o envio para aprovação?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        block();
                        document.location.href = "/comercial/operacao/verba-comercial/enviar/analise/" + json.id;
                    }
                });
			} else if ($($this).val() == 5) {
                window.open('/comercial/operacao/verba-comercial/editar/'+json.id, '_self');
            } else if ($($this).val() == 6) {
                Swal.fire({
                    title: 'Cancelar Solicitação',
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    html: 'Deseja confirmar o cancelamento desta solicitação?'+
                        '<textarea id="swal_cancel_reason" class="swal2-textarea" style="height: 4.75em;" placeholder="Informe o motivo do cancelamento"></textarea>',
                    preConfirm: () => {
                        if($("#swal_cancel_reason").val() == "") {
                            swal.showValidationError(
                                'Obrigatório motivo do cancelamento'
                            );
                        }
                    }
                }).then(function (result) {
                    if (result.value) {
                        $("#cancel_id").val(json.id);
                        $("#cancel_reason").val($("#swal_cancel_reason").val());
                        block();
                        $("#form_request_cancel").submit();
                    }
                });
            } else if ($($this).val() == 7) {
                window.open('/comercial/operacao/verba-comercial/credit/imprimir/'+json.id, '_blank');
            } else if ($($this).val() == 8) {
                window.open('/comercial/operacao/verba-comercial/payment/imprimir/'+json.id, '_blank');
            }
            $($this).val('');
        }

        function sendAssign() {
            Swal.fire({
                title: 'Aviso importante',
                text: 'Sua solcitação entrará no processo de análise! Deseja continuar?',
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Pode continuar!',
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonText:
                    'Cancelar',
                showCancelButton: true,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                        if (value) {
                            resolve();
                        }
                    });
                }
            }).then(function (result) {
                if (result.value) {
                    block();
                    $('#formSign').submit();
                }
            });
        }

        function reloadFiles(arr) {
            var list = '';
            arr.forEach(function(item){
                list += '<tr>';
                list += '<td>'+item.name.substring(0,10)+'...</td>';
                list += '<td><a target="_blank" href="'+item.url+'">Clique aqui</a></td>';
                list += '<td><i class="ti ti-trash" style="cursor: pointer" onclick="removeFile('+item.id+')"></i></td>';
                list += '</tr>';
            });

            $('.listfiles').html(list);
        }

        function removeFile(id) {
            block();
            ajaxSend('/comercial/operacao/verba-comercial/comprovacao/remover', {budget_id: budgetid, attach_id:id}, 'POST', '10000').then(function(result){
                unblock();
                reloadFiles(result.data);
            }).catch(function(err){
                unblock();
                $error(err.message)
            })
        }

        function sendFileOrder() {
            if($('input[name="order_file"]').val() != '') {
                block();
                ajaxSend('/comercial/operacao/verba-comercial/comprovacao/adicionar', {budget_id: budgetid}, 'POST', '10000', $('#formSign')).then(function(result){
                    unblock();
                    reloadFiles(result.data);
                    $('input[name="order_file"]').val('');
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                })

            } else {
                $error('Você precisa informar um arquivo para continuar.');
            }
        }

        $(document).ready(function () {

            $('#identity').mask('00.000.000/0000-00', {reverse: false});
            $("#type_people").change(function () {
                var elem = $('#identity');
                if($(this).val() == 1) {
                    elem.mask('00.000.000/0000-00', {reverse: false});
                    elem.attr("placeholder", "00.000.000/0000-00");
                    elem.val('');
                } else {
                    elem.attr("placeholder", "Informe o RG");
                    elem.unmask();
                    elem.val('');
                }
            });

            $("#filterNow").click(function (e) {
                $("#filterModal").modal('toggle');
                block();
                $("#filterData").submit();
            });
        });

        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    </script>

@endsection
