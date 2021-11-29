@extends('security.gate.guard.layout')

@section('page-css')
    <link href="/elite/assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="/elite/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumbs')
    <div class="col-12">
        <div class="arrow_back" onclick="window.open('/controle/portaria', '_self'); block();">
            <i class="mdi mdi-arrow-left-bold"></i>
        </div>
        FUNCIONÁRIOS
    </div>
@endsection
@section('content')
<style>
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline {
        width: 100% !important;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline .select2-search__field {
        width: 100% !important;
    }
</style> 
    <div class="row ml-4 mr-4 mb-3" style="margin-top: 20px; display: flex; justify-content: center">
        <div class="col-12" style="max-width: 600px;">
            <div class="input-group mb-3" style="box-shadow: 0px 2px 6px 0 rgb(25 42 70 / 13%);">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="background: white; border-right: none;" id="basic-addon1"><i class="ti-search"></i></span>
                </div>
                <input onkeyup="searchRequest(this)" id="searchText" style="border-left: none;" type="text" class="form-control" placeholder="Pesquise por matricula ou código..." aria-label="Username" aria-describedby="basic-addon1">
            </div>
        </div>
    </div>

    <div class="ListRequests" style="margin-bottom: 80px">
    </div>
    <div id="loading" class="text-center" style="display: none; position: absolute; bottom: -50px; width: 100%;">
        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
        <span id="ltext">Por favor, aguarde...</span>
    </div>
    <div id="notResults" class="text-center" style="display: none; position: absolute; bottom: -50px; width: 100%;">
        <span id="nrtext">Não há dados...</span>
    </div>

    <div class="row modal-view" style="display: none">
        <div class="col-sm-12">
			<div class="row">
				<div class="col-sm-8">
					<button type="button" onclick="$('.modal-view').hide()" class="btn waves-effect btn-block waves-light btn-cyan mt-2">Fechar visualização</button>
				</div>
				<div class="col-sm-4">
					<button type="button" onclick="wdgt_printElem('request')" class="btn waves-effect btn-block waves-light btn-primary mt-2">
						Imprimir Solicitação
					</button>
				</div>
			</div>
			<ul class="nav nav-tabs mt-2" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#request" role="tab" aria-selected="true">Solicitação</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#charge" role="tab" aria-selected="false">Carregamento</a> </li>
            </ul>
			<div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="request" role="tabpanel">
                </div>
                <div class="tab-pane p-20" id="charge" role="tabpanel">
                </div>
            </div>
            <div class="button-group btn-analyze" style="display: flex;justify-content: center;flex-direction: row;">
                <button type="button" onclick="approv()" style="height: 75px;width: 100%;" class="btn waves-effect waves-light btn-success">Liberar solicitação</button>
                <button type="button" onclick="reprov()" style="height: 75px;width: 100%;" class="btn waves-effect waves-light btn-danger">Negar solicitação</button>
            </div>
            <div class="button-group btn-del" style="display: flex;justify-content: center;flex-direction: row;">
                <button type="button" onclick="deleteReq()" style="height: 75px;width: 100%;" class="btn waves-effect waves-light btn-danger">Deletar solicitação</button>
            </div>
        </div>
    </div>

    <form action="/controle/portaria/paginas/funcionarios/criar" method="POST" enctype="multipart/form-data" id="submitEntry">
        <!-- sample modal content -->
        <div id="create-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">CRIAR SOLICITAÇÃO DE ENTRADA</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sel_r_code" class="control-label">Colaborador:</label>
                            <select name="sel_r_code" id="sel_r_code" style="width:100%" class="form-control select2 select2-container" multiple>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="who_analyze_r_code" class="control-label">Imediato chefe:</label>
                            <select name="who_analyze_r_code" id="who_analyze_r_code" style="width:100%" class="form-control select22 select2-container" multiple>
                            </select>
                        </div>
                        <div class="form-group collaborate">
                            <label for="r_code" class="control-label">Matricula:</label>
                            <input type="text" class="form-control" name="r_code" id="r_code">
                        </div>
                        <div class="form-group collaborate">
                            <label for="first_name" class="control-label">Primeiro nome:</label>
                            <input type="text" class="form-control" name="first_name" id="first_name">
                        </div>
                        <div class="form-group collaborate">
                            <label for="last_name" class="control-label">Sobrenome:</label>
                            <input type="text" class="form-control" name="last_name" id="last_name">
                        </div>
                        <div class="form-group collaborate">
                            <label for="office" class="control-label">Cargo:</label>
                            <input type="text" class="form-control" name="office" id="office">
                        </div>
                        <div class="form-group collaborate">
                            <label for="sector_id" class="control-label">Setor:</label>
                            <select class="form-control" name="sector_id" id="sector_id">
                                <option value=""></option>
                                @foreach ($sectors as $index => $key)
                                    <option value="{{$index}}">{{$key}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reason" class="control-label">Motivo:</label>
                            <select class="form-control" name="reason" id="reason">
                                <option value="2" selected>Particular</option>
                                <option value="1">Serviço</option>
                                <option value="3">Almoço</option>
                                <option value="4">Esqueceu o crachá</option>
								<option value="5">Saúde</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="justify" class="control-label">Justifique:</label>
                            <textarea cols="5" class="form-control" name="justify" id="justify"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">Criar solicitação</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal -->
    </form>

    <div class="bg-primary plusAdd ripple" onclick="$('#create-modal').modal()">
        <i class="ti-plus"></i>
    </div>
@endsection

@section('page-scripts')
	@include('gree_i.misc.components.printElem.script')
    <script src="/elite/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/i18n/pt-BR.js" type="text/javascript"></script>
    <script>
        var page = 1;
        var hasLoad = false;
        var interval = null;
        var autoreload = null;
        var sel_id = 0;

        $('#submitEntry').submit(function(e) {
            if (!$('#who_analyze_r_code').val().length) {
                e.preventDefault();
                return $error('Você precisa selecionar o imediato chefe.');
            } else if (!$('#r_code').val()) {
                e.preventDefault();
                return $error('Você precisa informar a matricula.');
            } else if (!$('#first_name').val()) {
                e.preventDefault();
                return $error('Você precisa informar o primeiro nome.');
            } else if (!$('#last_name').val()) {
                e.preventDefault();
                return $error('Você precisa informar o sobrenome.');
            } else if (!$('#office').val()) {
                e.preventDefault();
                return $error('Você precisa informar o cargo.');
            } else if (!$('#sector_id').val()) {
                e.preventDefault();
                return $error('Você precisa informar o setor.');
            } else if (!$('#justify').val()) {
                e.preventDefault();
                return $error('Você precisa informar a justificativa.');
            }
            block();
        });

        function approv() {
            Swal.fire({
                title: 'Você confirma a liberação?',
                showCancelButton: true,
                confirmButtonText: 'Sim, pode liberar',
                cancelButtonText: 'Fechar'
            }).then((result) => {
                if (result.value) {
                    block();
                    ajaxSend(
                        '/controle/portaria/paginas/funcionarios/analisar',
                        {
                            'id': sel_id,
                            'secret': localStorage.getItem('secret'),
                            'status': 1
                        },
                        'POST'
                    ).then(($result) => {
                        unblock();
                        page = 1;
                        $('.ListRequests').html('');
                        loadListView();
                        $('.modal-view').toggle();
                        Swal.fire(
                            'Liberação realizada',
                            'A solicitação foi atualizada.',
                            'success'
                        );
                    }).catch((error) => {
                        unblock();
                        $('.modal-view').toggle();
                        page = 1;
                        $('.ListRequests').html('');
                        loadListView();
                        $error(error.message);
                    });

                }
            })
        }

        function reprov() {
            Swal.fire({
                title: 'Informe o motivo da rejeição',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Continuar',
                cancelButtonText: 'Fechar'
            }).then((result) => {
                if (result.value) {
                    block();
                    ajaxSend(
                        '/controle/portaria/paginas/funcionarios/analisar',
                        {
                            'id': sel_id,
                            'secret': localStorage.getItem('secret'),
                            'description': result.value,
                            'status': 2
                        },
                        'POST'
                    ).then(($result) => {
                        unblock();
                        page = 1;
                        $('.ListRequests').html('');
                        loadListView();
                        $('.modal-view').toggle();
                        Swal.fire(
                            'Solicitação rejeitada',
                            'Caso não tenha comunicado, fale com o solicitante dessa requisição.',
                            'error'
                        );
                    }).catch((error) => {
                        unblock();
                        $('.modal-view').toggle();
                        page = 1;
                        $('.ListRequests').html('');
                        loadListView();
                        $error(error.message);
                    });
                }
            })
        }

        function deleteReq() {
            Swal.fire({
                title: 'Informe o motivo da exclusão',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Continuar',
                cancelButtonText: 'Fechar'
            }).then((result) => {
                if (result.value) {
                    block();
                    ajaxSend(
                        '/controle/portaria/paginas/funcionarios/deletar',
                        {
                            'id': sel_id,
                            'secret': localStorage.getItem('secret'),
                            'description': result.value,
                        },
                        'POST'
                    ).then(($result) => {
                        unblock();
                        page = 1;
                        $('.ListRequests').html('');
                        loadListView();
                        $('.modal-view').toggle();
                        Swal.fire(
                            'Solicitação excluida',
                            'Sua exclusão irá se registrada e poderá ser usada para futura consulta.',
                            'error'
                        );
                    }).catch((error) => {
                        unblock();
                        $('.modal-view').toggle();
                        page = 1;
                        $('.ListRequests').html('');
                        loadListView();
                        $error(error.message);
                    });
                }
            })
        }

        function reloadData(data) {

            var html = '';

            data.forEach(function ($val) {
                html += '<div class="row ml-2 mr-2 d-flex justify-content-center rowCard animate__animated animate__zoomIn" style="margin-bottom: -15px;">';
                html += '<div class="col-12 itemlistColumn" onclick="loadSingleView('+$val.id+')">';
                html += '<div class="statusRequest">';
                html += '<div style="display: flex;justify-content: center;flex-direction: column;">';
                if ($val.is_liberate) {
                    html += '<img style="width: auto;margin: auto;" src="/elite/assets/security/security_gate_is_liberator.png">';
                } else if ($val.is_denied) {
                    html += '<img style="width: auto;margin: auto;" src="/elite/assets/security/security_gate_is_reject.png">';
                } else if ($val.is_cancelled) {
                    html += '<img style="width: auto;margin: auto;" src="/elite/assets/security/security_gate_is_cancelled.png">';
                }
                html += '</div>';
                html += '</div>';
                html += '<div class="card ripple">';
                html += '<div class="card-body">';
                if ($val.is_denied) {
                    html += '<div class="bg-danger bollList">';
                } else if ($val.is_cancelled) {
                    html += '<div class="bg-danger bollList">';
                } else if ($val.entry_restriction) {
                    html += '<div class="bg-warning bollList">';
                } else {
                    html += '<div class="bg-success bollList">';
                }
                html += '<div class="bollday">' + getDateFormat($val.date_hour) + '</div>';
                html += '<div class="bollhour">' + getHourFormat($val.date_hour) + '</div>';
                html += '</div>';
                html += '<div class="informations">';
                html += '<div><b>Nome:</b> '+$val.request_user.full_name+'</div>';
                html += `<div><b>Cargo:</b> ${$val.request_office}</div>`;
                html += `<div><b>Motivo:</b> ${$val.reason_name} <b style="margin-left: 25px;">Retorna:</b> ${$val.return_same_day ? 'Sim' : 'Não'}</div>`;
                html += '</div>';
                html += '<div class="right-informations">';
                if ($val.is_entry_exit == 1) {
                    html += '<div class="type_enter" style="background: #046fb7;"><b>ENTRADA</b></div>';
                } else if ($val.is_entry_exit == 2) {
                    html += '<div class="type_enter" style="background: #ced300;"><b>SAÍDA</b></div>';
                }
                html += '<div class="code"><b>'+$val.code+'</b></div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            });

            $('.ListRequests').append(html);
        }

        function reloadSingle(data) {
            sel_id = data.id;
            var html = '';
            var charge = '';
            $('#request').html('');
			$('#charge').html('');
            var restriction = '';
            if (data.entry_restriction) {
                restriction = `<tr>
                            <td colspan="12" class="td-font-13 td-text-white td-text-center td-color-red td-bold">
                                RENSTRIÇÃO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-text-center td-font-14 td-bold" colspan="12">
                                ${data.entry_restriction}
                            </td>
                        </tr>`;
            }
            var reason = '';
            if (data.justify) {
                reason = `<tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                JUSTIFICATIVA
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-text-center td-font-14" colspan="12">
                                ${data.justify}
                            </td>
                        </tr>`;
            }
            var situation = {
                status: 'Aguardando liberação',
                reason: '',
                name: '',
                time: '',
            };
            $('.btn-analyze').show();
            $('.btn-del').hide();
            if (data.is_liberate) {
                $('.btn-analyze').hide();
                @if (Session::get('security_guard_data')->is_supervisor)
                if (data.is_entry_exit === 1) {
                    $('.btn-del').show();
                }
                @endif
                    situation = {
                    status: 'Liberado',
                    reason: '',
                    name: data.who_excute_action,
                    time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
                };
            } else if (data.is_denied) {
                $('.btn-analyze').hide();
                situation = {
                    status: 'Negado',
                    reason: data.denied_reason,
                    name: data.who_excute_action,
                    time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
                };
            } else if (data.is_cancelled) {
                $('.btn-analyze').hide();
                situation = {
                    status: 'Cancelado',
                    reason: data.cancelled_reason,
                    name: data.who_excute_action,
                    time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
                };
            }
            var return_hour = 'N/A';
            if (data.return_hour) {
                return_hour = `<b>DATA:</b> ${getDateFormat(data.return_hour, true)} <b>HORA:</b> ${getHourFormat(data.return_hour)}`
            }
            html += `<table class="table table-bordered table-view">
                        <tbody>
                        <tr>
                            <td rowspan="4" style="text-align: center;">
                                <img src="https://gree-app.com.br/media/logo.png" height="30" alt="" style="padding: 2px;">
                            </td>
                            <td colspan="8" rowspan="3" class="td-text-center td-font-17 td-bold">
                                CONTROLE DE ${data.is_entry_exit === 1 ? 'ENTRADA' : 'SAÍDA'}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-font-13">
                                <b>TIPO:</b> FUNCIONÁRIOS
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-font-13">
                                <b>DATA:</b> ${getDateFormat(data.date_hour, true)} <b>HORA:</b> ${getHourFormat(data.date_hour)}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9" class="td-font-13 td-text-white td-text-center td-color-red td-bold">

                            </td>
                            <td class="td-font-13">
                                <b>CÓDIGO:</b> ${data.code}
                            </td>
                        </tr>
                        ${restriction}
                        ${reason}
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                SOLICITANTE
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                NOME COMPLETO:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.request_user.full_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                MATRICULA:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.request_user.r_code}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                CARGO:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.request_office}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                SETOR:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.sector_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                MOTIVO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.reason_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                Retorna no mesmo dia?:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${data.return_same_day ? 'Sim' : 'Não'}
                            </td>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                Horário de retorno:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${return_hour}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                APROVADOR
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                NOME COMPLETO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze.full_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                MATRICULA:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze.r_code}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                CARGO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze.office}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                SETOR:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze.sector_name}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                SITUAÇÃO DA SOLICITAÇÃO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                STATUS:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.status}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                PORTARIA:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.logistics_entry_exit_gate ? data.logistics_entry_exit_gate.name : 'N/A'}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                VIGILANTE/COLABORADOR:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                DATA E HORÁRIO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.time}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                MOTIVO DA AÇÃO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.reason}
                            </td>
                        </tr>
                        </tbody>
                    </table>`;
            $('#request').html(html);
			
			var has_doc = '';
            if (data.file_ref)
                has_doc = `<a target="_blank" href="${data.file_ref}">Clique para visualizar</a>`;

            var itens = '';
            if (data.entry_exit_employees_items.length > 0) {
                data.entry_exit_employees_items.forEach(function($val) {
                    itens += `<tr>
                                <td class="td-font-14" colspan="6">
                                    ${$val.description}
                                </td>
                                <td class="td-font-14" colspan="6">
                                    ${$val.quantity}
                                </td>
                            </tr>`;
                });
            }
            charge += `<table class="table table-bordered table-view">
                        <tbody>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                DETALHES
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                Número de referência:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.number_ref ? data.number_ref : 'N/A'}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                GALPÃO:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.logistics_warehouse ? data.logistics_warehouse.name : 'N/A'}
                            </td>
                        </tr
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                Documento de referência:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${has_doc}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                ITENS
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="6">
                                DESCRIÇÃO
                            </td>
                            <td class="td-title td-font-14 td-bold" colspan="6">
                                QUANTIDADE
                            </td>
                        </tr>
                        ${itens}
                        </tbody>
                    </table>`;
            $('#charge').html(charge);
            $('.modal-view').toggle();
        }

        function getDateFormat(value, has_year = false){
            var date = new Date(value);
            var day = date.getDate().toString();
            var dayF = (day.length == 1) ? '0'+day : day;
            var month = (date.getMonth()+1).toString();
            var monthF = (month.length == 1) ? '0'+month : month;

            if (has_year)
                return dayF+'/'+monthF+'/'+date.getFullYear();
            else
                return dayF+'/'+monthF;
        }

        function getHourFormat(value) {
            var date = new Date(value);
            var hour = date.getHours();
            var hourF = hour < 10 ? '0'+hour : hour;
            var minutes = date.getMinutes();
            var minutesF = minutes < 10 ? '0'+minutes : minutes;
            return  hourF+':'+minutesF;
        }

        function searchRequest($this) {
            clearInterval(interval);
            interval = setTimeout(() => {
                $('.ListRequests').html('');
                page = 1;
                loadListView($($this).val(), true);
            }, 1000);
        }

        function loadSingleView($id) {
            block();
            ajaxSend(
                '/controle/portaria/paginas/funcionarios/visualizar',
                {
                    'id': $id,
                    'secret': localStorage.getItem('secret')
                }
            ).then(($result) => {
                unblock();
                reloadSingle($result);
            }).catch((error) => {
                unblock();
                $error(error.message);
            });
        }

        function loadListView(search = null, is_reset = false) {
            if (!hasLoad) {
                hasLoad = true;
                $('#loading').show();
                $('#notResults').hide();
                ajaxSend(
                    '/controle/portaria/paginas/funcionarios/listar',
                    {
                        'page': page,
                        'search': $('#searchText').val(),
                        'secret': localStorage.getItem('secret')
                    }
                ).then(($result) => {
                    page++;
                    hasLoad = false;
                    $('#loading').hide();
                    if ($result.data.length) {
                        reloadData($result.data, is_reset);
                    } else {
                        $('#notResults').show();
                        setTimeout(() => {
                            $('#notResults').hide();
                        }, 2000);
                    }
                }).catch((error) => {
                    hasLoad = false;
                    $error(error.message);
                });
            }

            return null
        }

        $(".select2").select2({
            placeholder: 'Nome ou matricula...',
            maximumSelectionLength: 1,
            language: "pt-BR",
            ajax: {
                url: '/controle/portaria/misc/users/general',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                }
            }
        });

        $(".select2").on('select2:select', function (e) {
            var data = e.params.data;
            $('#r_code').val(data.r_code);
            $('#first_name').val(data.first_name);
            $('#last_name').val(data.last_name);
            $('#office').val(data.office);
            $('#sector_id').val(data.sector_id);
            $('.collaborate').hide();
        });

        $(".select2").on('select2:unselect', function (e) {
            var data = e.params.data;
            $('#r_code').val('');
            $('#first_name').val('');
            $('#last_name').val('');
            $('#office').val('');
            $('#sector_id').val('');
            $('.collaborate').show();
        });

        $(".select22").select2({
            placeholder: 'Noome ou matricula...',
            maximumSelectionLength: 1,
            language: "pt-BR",
            ajax: {
                url: '/controle/portaria/misc/users',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                }
            }
        });
        function autoReload() {
            clearInterval(autoreload);
            autoreload = setInterval(loadListView(), 1800000);
        }
        $(window).on("scroll", function() {
            var scrollHeight = $(document).height();
            var scrollPosition = $(window).height() + $(window).scrollTop();
            if (Math.round(scrollPosition) >= Math.round(scrollHeight)) {
                loadListView();
            }
        });
        $(document).ready(function() {
            loadListView();
            autoReload();
        })
    </script>
@endsection

