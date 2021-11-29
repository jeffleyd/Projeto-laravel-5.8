@extends('security.gate.guard.layout')

@section('page-css')
    <link href="/elite/assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
@endsection

@section('breadcrumbs')
    <div class="col-12">
        <div class="arrow_back" onclick="window.open('/controle/portaria', '_self'); block();">
            <i class="mdi mdi-arrow-left-bold"></i>
        </div>
        VISITANTE/P.SERVIÇO
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
    <!-- <div class="row ml-4 mr-4 mb-1 d-flex justify-content-center" style="margin-top: 20px">
        <div class="col-12" style="max-width: 600px;">
            <div class="input-group mb-3">
                <select id="sType" class="form-control">
                   <option value="1">Modo de pesquisa: livre</option>
                   <option value="2">Modo de pesquisa: CPF</option>
                </select>
            </div>
        </div>
    </div> -->
    <div class="row ml-4 mr-4 mb-3 d-flex justify-content-center" style="margin-top: 20px;">
        <div class="col-12" style="max-width: 600px;">
            <div class="input-group mb-3" style="box-shadow: 0px 2px 6px 0 rgb(25 42 70 / 13%);">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="background: white; border-right: none;" id="basic-addon1"><i class="ti-search"></i></span>
                </div>
                <input onkeyup="searchRequest(this)" id="searchText" style="border-left: none;" type="text" class="form-control" placeholder="Pesquise por placa, código, RG ou nome..." aria-label="Username" aria-describedby="basic-addon1">
				<button type="button" data-toggle="modal" data-target="#filter-modal" class="btn btn-default waves-effect">Pesquisa avançada</button>
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
				<div class="col-sm-2">
					<button type="button" onclick="$('#c_request').click(); wdgt_printElem('request')" class="btn waves-effect btn-block waves-light btn-primary mt-2">
						Imprimir Solicitação
					</button>
				</div>
				<div class="col-sm-2">
					<button type="button" onclick="$('#c_charge').click(); wdgt_printElem('charging')" class="btn waves-effect btn-block waves-light btn-primary mt-2">
						Imprimir Carregamento
					</button>
				</div>
			</div>
            <ul class="nav nav-tabs mt-2" role="tablist">
                <li class="nav-item"> <a class="nav-link active" id="c_request" data-toggle="tab" href="#request" role="tab" aria-selected="true">Solicitação</a> </li>
                <li class="nav-item"> <a class="nav-link" id="c_charge" data-toggle="tab" href="#charging" role="tab" aria-selected="false">Carregamento</a> </li>
            </ul>
            <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="request" role="tabpanel">
                </div>
                <div class="tab-pane p-20" id="charging" role="tabpanel">
                </div>
            </div>
            <div class="button-group btn-analyze" style="display: flex;justify-content: center;flex-direction: row;">
                <button type="button" onclick="approv()" style="height: 75px;width: 100%;" class="btn waves-effect waves-light btn-success">Liberar solicitação</button>
                <button type="button" onclick="reprov()" style="height: 75px;width: 100%;" class="btn waves-effect waves-light btn-danger">Negar solicitação</button>
            </div>
        </div>
    </div>

	<!-- sample modal content -->
    <div id="filter-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Filtrar avançado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Nome da empresa</label>
								<input type="text" class="form-control" id="logistics_transporter" name="logistics_transporter" placeholder="Nome da empresa">
							</div>    
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Data</label>
								<input type="text" class="form-control date-mask" id="date" name="date" placeholder="00/00/0000">
							</div>    
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" onclick="page = 1; $('.ListRequests').html(''); loadListView();" data-dismiss="modal" class="btn btn-success waves-effect waves-light">Filtrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
@endsection

@section('page-scripts')
	@include('gree_i.misc.components.printElem.script')
    <script src="/elite/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
	<script src="/admin/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="/admin/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script>
        var page = 1;
        var hasLoad = false;
        var interval = null;
        var autoreload = null;
        var sel_id = 0;

        $('#sType').change(function () {
            if ($(this).val() == 2) {
                $('#searchText').mask('000.000.000-00', {reverse: false});
            } else {
                $('#searchText').unmask();
            }
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
                        '/controle/portaria/transporte-de-carga/visita/aprovar',
                        {
                            'id': sel_id,
                            'secret': localStorage.getItem('secret'),
                            'is_schedule': 1,
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
                        '/controle/portaria/transporte-de-carga/visita/negar',
                        {
                            'id': sel_id,
                            'secret': localStorage.getItem('secret'),
                            'description': result.value,
                            'is_schedule': 1,
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

        function reloadData(data) {

            var html = '';

            data.forEach(function ($val) {
                if ($val.logistics_entry_exit_requests.is_my_request) {
                    html += '<div class="row ml-2 mr-2 d-flex justify-content-center rowCard animate__animated animate__zoomIn" style="margin-bottom: -15px;">';
                } else {
                    html += '<div class="row ml-2 mr-2 d-flex justify-content-center rowCard animate__animated animate__zoomIn" style="margin-bottom: -15px; opacity: 0.4">';
                }

                if ($val.logistics_entry_exit_requests.is_my_request) {
                    html += '<div class="col-12 itemlistColumn" onclick="loadSingleView('+$val.id+')">';
                } else {
                    html += '<div class="col-12 itemlistColumn">';
                }

                html += '<div class="statusRequest">';
                html += '<div style="display: flex;justify-content: center;flex-direction: column;">';
                if ($val.is_liberate) {
                    html += '<img style="width: auto;margin: auto;" src="/elite/assets/security/security_gate_is_liberator.png">';
                } else if ($val.is_denied) {
                    html += '<img style="width: auto;margin: auto;" src="/elite/assets/security/security_gate_is_reject.png">';
                } else if ($val.logistics_entry_exit_requests.is_cancelled) {
                    html += '<img style="width: auto;margin: auto;" src="/elite/assets/security/security_gate_is_cancelled.png">';
                }
                html += '</div>';
                html += '</div>';
                html += '<div class="card ripple">';
                html += '<div class="card-body">';
                if ($val.is_denied) {
                    html += '<div class="bg-danger bollList">';
                } else if ($val.logistics_entry_exit_requests.is_cancelled) {
                    html += '<div class="bg-danger bollList">';
                } else if ($val.logistics_entry_exit_requests.entry_restriction) {
                    html += '<div class="bg-warning bollList">';
                } else {
                    html += '<div class="bg-success bollList">';
                }
                html += '<div class="bollday">' + getDateFormat($val.date_hour) + '</div>';
                html += '<div class="bollhour">' + getHourFormat($val.date_hour) + '</div>';
                html += '</div>';
                html += '<div class="informations">';
				var bname = '';
				if ($val.logistics_entry_exit_requests.logistics_entry_exit_visit.company_name) {
					bname = $val.logistics_entry_exit_requests.logistics_entry_exit_visit.company_name.substring(0, 30)+'...';
				}
				html += '<div><b>Empresa:</b> '+bname+'</div>';
                html += '<div><b>Nome:</b> '+$val.logistics_entry_exit_requests.logistics_entry_exit_visit.name+'</div>';
                html += `<div><b>Veículo:</b> ${$val.logistics_entry_exit_requests.logistics_entry_exit_visit.car_model ? $val.logistics_entry_exit_requests.logistics_entry_exit_visit.car_model : "N/A"}</div>`;
                html += `<div><b>Placa:</b> ${$val.logistics_entry_exit_requests.logistics_entry_exit_visit.car_plate ? $val.logistics_entry_exit_requests.logistics_entry_exit_visit.car_plate : "N/A"} <b style="margin-left: 25px;">Portaria:</b> ${$val.logistics_entry_exit_requests.logistics_entry_exit_gate.name}</div>`;
                html += '</div>';
                html += '<div class="right-informations">';
                if ($val.is_entry_exit == 1) {
                    html += '<div class="type_enter" style="background: #046fb7;"><b>ENTRADA</b></div>';
                } else if ($val.is_entry_exit == 2) {
                    html += '<div class="type_enter" style="background: #ced300;"><b>SAÍDA</b></div>';
                }
                html += '<div class="code"><b>'+$val.logistics_entry_exit_requests.code+'</b></div>';
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
            var restriction = '';
            var requestsData = data.logistics_entry_exit_requests;
            if (data.entry_restriction) {
                restriction = `<tr>
                            <td colspan="12" class="td-font-13 td-text-white td-text-center td-color-red td-bold">
                                RENSTRIÇÃO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-text-center td-font-14 td-bold" colspan="12" style="background: yellow;">
                                ${data.entry_restriction}
                            </td>
                        </tr>`;
            }
            var reason = '';
            if (requestsData.reason) {
                reason = `<tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                MOTIVO DA SOLICITAÇÃO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-text-center td-font-14" colspan="12">
                                ${requestsData.reason}
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
            if (data.is_liberate) {
                $('.btn-analyze').hide();
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
            } else if (requestsData.is_cancelled) {
                $('.btn-analyze').hide();
                situation = {
                    status: 'Cancelado',
                    reason: requestsData.cancelled_reason,
                    name: requestsData.who_excute_action,
                    time: getDateFormat(requestsData.request_action_time, true) +' '+getHourFormat(requestsData.request_action_time),
                };
            }
            html += `<table class="table table-bordered table-view">
                        <tbody>
                        <tr>
                            <td rowspan="4" style="text-align: center;">
                                <img src="https://gree-app.com.br/media/logo.png" height="30" alt="" style="padding: 2px;">
                            </td>
                            <td colspan="8" rowspan="3" class="td-text-center td-font-17 td-bold">
                                SOLICITAÇÃO DE ${data.is_entry_exit === 1 ? 'ENTRADA' : 'SAÍDA'}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-font-13">
                                <b>TIPO:</b> ${requestsData.type_reason_name}
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
                                <b>CÓDIGO:</b> ${requestsData.code}
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
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                NOME COMPLETO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${requestsData.request_user.full_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                CARGO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${requestsData.request_user.office}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                SETOR:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${requestsData.request_sector}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                RAMAL:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${requestsData.request_ramal ? requestsData.request_ramal : 'N/A'}
                            </td>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                TELEFONE:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${requestsData.request_phone}
                            </td>
                        </tr>
                        <tr>
                        <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                        VISITANTE
                        </td>
                        </tr>
                        <tr>
                        <td class="td-title td-font-14 td-bold" colspan="3">
                        NOME COMPLETO:
                        </td>
                        <td class="td-font-14" colspan="9">
                        ${requestsData.logistics_entry_exit_visit.name}
                        </td>
                        </tr>
                        <tr>
                        <td class="td-title td-font-14 td-bold" colspan="3">
                        SEXO:
                        </td>
                        <td class="td-font-14" colspan="9">
                        ${requestsData.logistics_entry_exit_visit.gender === 1 ? 'Masculino' : 'Feminino'}
                        </td>
                        </tr>
                        <tr>
                        <td class="td-title td-font-14 td-bold" colspan="2">
                        RG:
                        </td>
                        <td class="td-font-14" colspan="4">
                        ${requestsData.logistics_entry_exit_visit.identity}
                        </td>
                        <td class="td-title td-font-14 td-bold" colspan="2">
                        TELEFONE:
                        </td>
                        <td class="td-font-14" colspan="4">
                        ${requestsData.logistics_entry_exit_visit.phone}
                        </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold td-text-white td-color-red" colspan="3">
                                Encaminhamento:
                            </td>
                            <td class="td-font-14 td-text-white td-color-red" colspan="9">
                                ${data.request_forwarding}
                            </td>
                        </tr>
                        <tr>
                        <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                        EMPRESA
                        </td>
                        </tr>
                        <tr>
                        <td class="td-title td-font-14 td-bold" colspan="3">
                        NOME:
                        </td>
                        <td class="td-font-14" colspan="9">
                        ${requestsData.logistics_entry_exit_visit.company_name ? requestsData.logistics_entry_exit_visit.company_name : 'N/A'}
                        </td>
                        </tr>
                        <tr>
                        <td class="td-title td-font-14 td-bold" colspan="2">
                        CNPJ:
                        </td>
                        <td class="td-font-14" colspan="4">
                        ${requestsData.logistics_entry_exit_visit.company_identity ? requestsData.logistics_entry_exit_visit.company_identity : 'N/A'}
                        </td>
                        <td class="td-title td-font-14 td-bold" colspan="2">
                        TELEFONE:
                        </td>
                        <td class="td-font-14" colspan="4">
                        ${requestsData.logistics_entry_exit_visit.company_phone ? requestsData.logistics_entry_exit_visit.company_phone : 'N/A'}
                        </td>
                        </tr>
                        <tr>
                        <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                        VEÍCULO
                        </td>
                        </tr>
                        <tr>
                        <td class="td-title td-font-14 td-bold" colspan="2">
                        PLACA:
                        </td>
                        <td class="td-font-14" colspan="4">
                        ${requestsData.logistics_entry_exit_visit.car_plate ? requestsData.logistics_entry_exit_visit.car_plate : 'N/A'}
                        </td>
                        <td class="td-title td-font-14 td-bold" colspan="2">
                        MODELO:
                        </td>
                        <td class="td-font-14" colspan="4">
                        ${requestsData.logistics_entry_exit_visit.car_model ? requestsData.logistics_entry_exit_visit.car_plate : 'N/A'}
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
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                GALPÃO:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${requestsData.logistics_warehouse ? requestsData.logistics_warehouse.name : ''}
                            </td>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                PORTARIA:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${requestsData.logistics_entry_exit_gate.name}
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

            var content = '';
            var items = '';
            if (data.is_content === 1) {
                content = requestsData.logistics_warehouse_type_content.description;
                if (requestsData.logistics_entry_exit_requests_items.length) {
                    requestsData.logistics_entry_exit_requests_items.forEach(function ($var) {
                        items = `<tr>
                            <td class="td-font-14" colspan="6">
                                ${$var.description}
                            </td>
                            <td class="td-font-14" colspan="6">
                                ${$var.quantity}
                            </td>
                        </tr>`
                    });
                }
            }
            charge = `<table class="table table-bordered table-view">
                        <tbody>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                CARREGAMENTO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                CARREGADO?:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${requestsData.is_content === 1 ? 'Sim' : 'Não'}
                            </td>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                CONTEÚDO:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${content}
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
                        ${items}
                        </tbody>
                    </table>
                `;

            $('#charging').html(charge);
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
            }, 2000);
        }

        function loadSingleView($id) {
            block();
            ajaxSend(
                '/controle/portaria/paginas/visita/visualizar',
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
		
		$('.date-mask').pickadate({
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

        function loadListView(search = null, is_reset = false) {
            if (!hasLoad) {
                hasLoad = true;
                $('#loading').show();
                $('#notResults').hide();
                ajaxSend(
                    '/controle/portaria/paginas/visita/listar',
                    {
                        'page': page,
                        'search': search,
						'logistics_transporter': $('#logistics_transporter').val(),
						'date': $('#date').val(),
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
        }
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

