@extends('security.gate.guard.layout')

@section('page-css')
    <link href="/elite/assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="/elite/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
@endsection

@section('breadcrumbs')
    <div class="col-12">
        <div class="arrow_back" onclick="window.open('/controle/portaria', '_self'); block();">
            <i class="mdi mdi-arrow-left-bold"></i>
        </div>
        TRANSPORTE DE CARGA
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
	.bollhour {
		font-size:12px;	
	}
</style> 
    <div class="row ml-4 mr-4 mb-3" style="margin-top: 20px; display: flex; justify-content: center">
        <div class="col-12" style="max-width: 600px;">
            <div class="input-group mb-3" style="box-shadow: 0px 2px 6px 0 rgb(25 42 70 / 13%);">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="background: white; border-right: none;" id="basic-addon1"><i class="ti-search"></i></span>
                </div>
                <input onkeyup="searchRequest(this)" id="searchText" style="border-left: none;" type="text" class="form-control" placeholder="Pesquise por:  Código, Placa, CNPJ" aria-label="Username" aria-describedby="basic-addon1">
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
					<button type="button" onclick="$('#c_request').click(); wdgt_printElem('request')" class="btn waves-effect btn-block waves-light btn-success mt-2">
						Imprimir Solicitação
					</button>
				</div>
				<div class="col-sm-2">
					<button type="button" onclick="$('#c_charge').click(); wdgt_printElem('charging')" class="btn waves-effect btn-block waves-light btn-warning mt-2">
						Imprimir Carregamento
					</button>
				</div>
			</div>
            <ul class="nav nav-tabs mt-2" role="tablist">
                <li class="nav-item"> <a id="c_request" class="nav-link active" data-toggle="tab" href="#request" role="tab" aria-selected="true">Solicitação</a> </li>
                <li class="nav-item"> <a id="c_charge" class="nav-link" data-toggle="tab" href="#charging" role="tab" aria-selected="false">Carregamento</a> </li>
				<li class="nav-item"> <a id="c_charge" class="nav-link" data-toggle="tab" href="#documents" role="tab" aria-selected="false">Documentos</a> </li>
            </ul>
            <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="request" role="tabpanel">
                </div>
                <div class="tab-pane p-20" id="charging" role="tabpanel">
                </div>
				<div class="tab-pane p-20" id="documents" role="tabpanel">
                </div>
            </div>
            <div class="button-group btn-analyze" style="display: flex;justify-content: center;flex-direction: row;">
                <button type="button" onclick="approv()" style="height: 75px;width: 100%;" class="btn waves-effect waves-light btn-success">Liberar solicitação</button>
                <button type="button" onclick="reprov()" style="height: 75px;width: 100%;" class="btn waves-effect waves-light btn-danger">Negar solicitação</button>
            </div>
        </div>
    </div>
    <form action="/controle/portaria/paginas/transporte-de-carga/cadastrar/motorista" method="POST" enctype="multipart/form-data" id="submitDriver">
    <input type="hidden" name="id" id="d_id">
    <!-- sample modal content -->
    <div id="driver-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pendência de informações</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
					<ul class="nav nav-tabs mt-2" role="tablist">
						<li class="nav-item"> <a id="register_request" class="nav-link active" data-toggle="tab" href="#r_request" role="tab" aria-selected="true">Solicitação</a> </li>
						<li class="nav-item"> <a id="register_charge" class="nav-link" data-toggle="tab" href="#r_charging" role="tab" aria-selected="false">Carregamento</a> </li>
						<li class="nav-item"> <a id="c_charge" class="nav-link" data-toggle="tab" href="#r_documents" role="tab" aria-selected="false">Documentos</a> </li>
					</ul>
					<div class="tab-content tabcontent-border">
						<div class="tab-pane active" id="r_request" role="tabpanel">
							<div class="alert alert-info" id="request_people">
								<div style="width: 100%; font-weight: bold; text-align: center">SOLICITANTE</div>
								<div>
									<b>Nome:</b> 
									<br><b>Cargo:</b> 
									<br><b>Setor:</b> 
									<br><b>Telefone:</b> 
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Motorista/Pedestre</label>
								<select class="form-control select2-container" name="transporter_driver_id" id="transporter_driver_id" style="width: 100%;" multiple>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Veículo</label>
								<select class="form-control select2-container" name="transporter_vehicle_id" id="transporter_vehicle_id" style="width: 100%;" multiple>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Carreta</label>
								<select class="form-control select2-container" name="transporter_cart_id" id="transporter_cart_id" style="width: 100%;" multiple>
								</select>
							</div>
						</div>
						<div class="tab-pane p-20" id="r_charging" role="tabpanel">
						</div>
						<div class="tab-pane p-20" id="r_documents" role="tabpanel">
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" onclick="reprov()" class="btn btn-danger waves-effect waves-light">Negar solicitação</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light">Salvar dados</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    </form>

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
								<label>Fornecedor</label>
								<select class="custom-select select-suipplier" name="logistics_supplier" id="logistics_supplier" style="width: 100%;" multiple>
								</select>
							</div>    
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Transportador</label>
								<select class="custom-select select-transporter" name="logistics_transporter" id="logistics_transporter" style="width: 100%;" multiple>
								</select>
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
    <script src="/elite/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/i18n/pt-BR.js" type="text/javascript"></script>
    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
	<script src="/admin/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="/admin/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>

    <script>
        var page = 1;
        var hasLoad = false;
        var interval = null;
        var autoreload = null;
        var sel_id = 0;
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
                            'secret': localStorage.getItem('secret')
                        },
                        'POST'
                    ).then(($result) => {
                        unblock();
                        page = 1;
                        $('.ListRequests').html('');
                        loadListView();
                        $('.modal-view').hide();
                        Swal.fire(
                            'Liberação realizada',
                            'A solicitação foi atualizada.',
                            'success'
                        );
                    }).catch((error) => {
                        unblock();
                        $('.modal-view').hide();
                        page = 1;
                        $('.ListRequests').html('');
                        loadListView();
                        $error(error.message);
                    });

                }
            })
        }

        function reprov() {
            $('#driver-modal').modal('hide');
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
                            'description': result.value
                        },
                        'POST'
                    ).then(($result) => {
                        unblock();
                        page = 1;
                        $('.ListRequests').html('');
                        loadListView();
                        $('.modal-view').hide();
                        Swal.fire(
                            'Solicitação rejeitada',
                            'Caso não tenha comunicado, fale com o solicitante dessa requisição.',
                            'error'
                        );
                    }).catch((error) => {
                        unblock();
                        $('.modal-view').hide();
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
                if ($val.is_my_request) {
                    html += '<div class="row ml-2 mr-2 d-flex justify-content-center rowCard animate__animated animate__zoomIn" style="margin-bottom: -15px;" >';
                } else {
                    html += '<div class="row ml-2 mr-2 d-flex justify-content-center rowCard animate__animated animate__zoomIn" style="margin-bottom: -15px; opacity: 0.4;">';
                }
                if ($val.is_my_request) {
                    if (!$val.logistics_transporter_driver && !$val.is_denied) {
                        html += `<div class="col-12 itemlistColumn" onclick="registerDriver('${$val.id}', '${$val.request_user.full_name}', '${$val.request_user.office}', '${$val.request_sector}', '${$val.request_phone}', '${$val.request_ramal}')">`;
                    } else {
                        html += '<div class="col-12 itemlistColumn" onclick="loadSingleView('+$val.id+')">';
                    }
                } else {
                    html += '<div class="col-12 itemlistColumn">';
                }

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

				if ($val.warehouse_type_content_id == 11)
                	html += '<div class="card ripple" style="border: solid 6px #b71e00;">';
				else
					html += '<div class="card ripple">';
					
                html += '<div class="card-body">';
                if ($val.is_denied) {
                    html += '<div class="bg-danger bollList" style="margin-top: 10px;">';
                } else if ($val.is_cancelled) {
                    html += '<div class="bg-danger bollList" style="margin-top: 10px;">';
                } else if ($val.entry_restriction) {
                    html += '<div class="bg-warning bollList" style="margin-top: 10px;">';
                } else {
                    html += '<div class="bg-success bollList" style="margin-top: 10px;">';
                }
                html += '<div class="bollday">' + getDateFormat($val.date_hour) + '</div>';
                html += '<div class="bollhour">'+ getHourFormat($val.date_hour_initial) +'-'+ getHourFormat($val.date_hour) + '</div>';
                html += '</div>';
                html += '<div class="informations">';
				html += '<div><b>Empresa:</b> '+$val.who_business.substring(0, 30)+'...</div>';
                if ($val.logistics_transporter_driver) {
                    html += '<div><b>Motorista/Pedestre:</b> '+$val.logistics_transporter_driver.name+'</div>';
                } else {
                    html += '<div style="background: red;color: white;text-align: center;"><b>Motorista/Pedestre:</b> SEM CADASTRADO</div>';
                }
				if($val.logistics_transporter_vehicle) {
                	html += '<div><b>Veículo:</b> '+$val.logistics_transporter_vehicle.type_vehicle_name+'</div>';
				} else {
				    html += '<div><b>Veículo:</b> SEM CADASTRO</div>';
				}		 
				
				html += `<div><b>Placa:</b> ${$val.logistics_transporter_vehicle ? $val.logistics_transporter_vehicle.registration_plate : 'N/A'} <b style="margin-left: 25px;">Portaria:</b> ${$val.logistics_entry_exit_gate.name}</div>`;
					
				
                html += '</div>';
                html += '<div class="right-informations" style="position: relative;top: 10px;">';
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
		
		function loadSingleRegister(data) {
			var r_charge = '';
			var r_documents = '';
			$('#r_charging').html('');
			$('#r_documents').html('');

			var content = '';
			var items = '';
			if (data.is_content === 1) {
				content = '';
				if (data.logistics_warehouse_type_content) {
					content = data.logistics_warehouse_type_content.description;
				}
				if (data.logistics_entry_exit_requests_items.length) {
					data.logistics_entry_exit_requests_items.forEach(function ($var) {
						items += `<tr>
							<td class="td-font-14" colspan="3">
								${$var.code_model}
							</td>
							<td class="td-font-14" colspan="3">
								${$var.description}
							</td>
							<td class="td-font-14" colspan="3">
								${$var.quantity}
							</td>
							<td class="td-font-14" colspan="3">
								${$var.unit}
							</td>
						</tr>`
					});
				}
			}
			r_charge = `<table class="table table-bordered table-view">
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
											${data.is_content === 1 ? 'Sim' : 'Não'}
										</td>
										<td class="td-title td-font-14 td-bold" colspan="2">
											CONTEÚDO:
										</td>
										<td class="td-font-14" colspan="4">
											${content}
										</td>
									</tr>
									<tr>
										<td class="td-title td-font-14 td-bold" colspan="3">
											MODELO/CÓDIGO
										</td>
										<td class="td-title td-font-14 td-bold" colspan="3">
											DESCRIÇÃO
										</td>
										<td class="td-title td-font-14 td-bold" colspan="3">
											QUANTIDADE
										</td>
										<td class="td-title td-font-14 td-bold" colspan="3">
											UNID.
										</td>
									</tr>
									${items}
									</tbody>
								</table>
							`;

						$('#r_charging').html(r_charge);

						var items_doc = '';
						if (data.logistics_entry_exit_requests_attachs) {
								data.logistics_entry_exit_requests_attachs.forEach(function ($var) {
									items_doc += `<tr>
										<td class="td-font-14" colspan="6">
											${$var.name_attach}
										</td>
										<td class="td-font-14" colspan="6">
											<a href="${$var.url_attach}" target="_blank">Visualizar arquivo</a>
										</td>
									</tr>`
								});
							}
						r_documents = `<table class="table table-bordered table-view">
									<tbody>
									<tr>
										<td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
											DOCUMENTOS
										</td>
									</tr>
									<tr>
										<td class="td-font-14 td-text-center" colspan="3">
											<b>Nº SAÍDA / ENTRADA MATERIAL</b>
										</td>
										<td class="td-font-14 td-text-center" colspan="5">
											<b>${data.code_di ? data.code_di : '-'}</b>
										</td>
										<td class="td-font-14 td-text-center" colspan="5">
											<a href="${data.url_di ? data.url_di : 'javascript:void(0)'}" target="_blank">${data.url_di ? 'VISUALIZAR ARQUIVO' : '-'}</a>
										</td>
									</tr>
									<tr>
										<td class="td-font-14 td-text-center" colspan="3">
											<b>NOTA FISCAL</b>
										</td>
										<td class="td-font-14 td-text-center" colspan="5">
											<b>${data.nfe_number ? data.nfe_number : '-'}</b>
										</td>
										<td class="td-font-14 td-text-center" colspan="5">
											<a href="${data.nfe_url ? data.nfe_url : 'javascript:void(0)'}" target="_blank">${data.nfe_url ? 'VISUALIZAR ARQUIVO' : '-'}</a>
										</td>
									</tr>
									<tr>
										<td class="td-font-14 td-text-center" colspan="3">
											<b>FATURA</b>
										</td>
										<td class="td-font-14 td-text-center" colspan="5">
											<b>${data.invoice_number ? data.invoice_number : '-'}</b>
										</td>
										<td class="td-font-14 td-text-center" colspan="5">
											<a href="${data.invoice_url ? data.invoice_url : 'javascript:void(0)'}" target="_blank">${data.invoice_url ? 'VISUALIZAR ARQUIVO' : '-'}</a>
										</td>
									</tr>
									<tr>
										<td class="td-font-14 td-text-center" colspan="3">
											<b>GR</b>
										</td>
										<td class="td-font-14 td-text-center" colspan="5">
											<b>${data.code_gr ? data.code_gr : '-'}</b>
										</td>
										<td class="td-font-14 td-text-center" colspan="5">
											<a href="${data.gr_url ? data.gr_url : 'javascript:void(0)'}" target="_blank">${data.gr_url ? 'VISUALIZAR ARQUIVO' : '-'}</a>
										</td>
									</tr>
									${items_doc}
									</tbody>
								</table>
							`;

						$('#r_documents').html(r_documents);
			
		}

        function reloadSingle(data) {
			sel_id = data.id;
			var html = '';
			var charge = '';
			var documents = '';
			$('#request').html('');
			$('#charging').html('');
			$('#documents').html('');
			var restriction = '';
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

			var driver = '';
			if (data.logistics_transporter_driver) {
				var cnh = '';
				if (data.logistics_transporter_driver.cnh_url) {
					cnh = `<a href="javascript:void(0)" onclick="window.open('${data.logistics_transporter_driver.cnh_url}','page','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=400');">Ver arquivo</a>`;
				}

				driver = `<tr>
							<td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
								MOTORISTA/PEDESTRE
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								NOME COMPLETO:
							</td>
							<td class="td-font-14" colspan="9">
								${data.logistics_transporter_driver.name}
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								SEXO:
							</td>
							<td class="td-font-14" colspan="9">
								${data.logistics_transporter_driver.gender === 1 ? 'Masculino' : 'Feminino'}
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="2">
								RG:
							</td>
							<td class="td-font-14" colspan="4">
								${data.logistics_transporter_driver.identity}
							</td>
							<td class="td-title td-font-14 td-bold" colspan="2">
								TELEFONE:
							</td>
							<td class="td-font-14" colspan="4">
								${data.logistics_transporter_driver.phone}
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								CNH:
							</td>
							<td class="td-font-14" colspan="9">
								${cnh}
							</td>
						</tr>`;
			}

			var additional_people = '';
			if(data.logistics_entry_exit_requests_people) {

				additional_people +=
				`<tr>
					<td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
						PESSOAS ADICIONAIS
					</td>
				</tr>
				<tr style="text-align: center;">
					<td class="td-title td-font-14 td-bold" colspan="2">
						NOME COMPLETO
					</td>
					<td class="td-title td-font-14 td-bold" colspan="6">
						RG /CPF
					</td>
					<td class="td-title td-font-14 td-bold" colspan="4">
						MOTIVO
					</td>
				</tr>
				`;
				data.logistics_entry_exit_requests_people.forEach(function ($var) {
					additional_people += `<tr style="text-align: center;">
						<td class="td-font-14" colspan="2">
							${$var.name}
						</td>
						<td class="td-font-14" colspan="6">
							${$var.identity}
						</td>
						<td class="td-font-14" colspan="4">
							${$var.reason}
						</td>
					</tr>`;
				});
			}

			var cart = '';
			if (data.logistics_transporter_cart) {
				cart = `<tr>
							<td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
								CARRETA
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								PLACA:
							</td>
							<td class="td-font-14" colspan="9">
								${data.logistics_transporter_cart.registration_plate}
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								TIPO:
							</td>
							<td class="td-font-14" colspan="9">
								${data.logistics_transporter_cart.type_cart_name}
							</td>
						</tr>`;
					}
			var conteiner = '';
			if (data.logistics_container) {
				conteiner = `<tr>
					<td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
						CONTEINER
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="3">
						NÚMERO:
					</td>
					<td class="td-font-14" colspan="9">
						${data.logistics_container.number_container}
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="3">
						LACRE:
					</td>
					<td class="td-font-14" colspan="9">
						${data.logistics_container.number_fleet}
					</td>
				</tr>`;
			}

			var supplier = '';
			if(data.logistics_supplier) {
				supplier = 
				`<tr>
					<td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
						FORNECEDOR
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="3">
						NOME:
					</td>
					<td class="td-font-14" colspan="9">
						${data.logistics_supplier.name}
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="3">
						CNPJ:
					</td>
					<td class="td-font-14" colspan="9">
						${data.logistics_supplier.identity}
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="3">
						TELEFONE:
					</td>
					<td class="td-font-14" colspan="9">
						${data.logistics_supplier.phone}
					</td>
				</tr>
				`;
			}

			var business = '';
			if (data.logistics_transporter) {
				business = 
				`<tr>
					<td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
						TRANSPORTADOR
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="3">
						NOME:
					</td>
					<td class="td-font-14" colspan="9">
						${data.logistics_transporter.name}
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="3">
						CNPJ:
					</td>
					<td class="td-font-14" colspan="9">
						${data.logistics_transporter.identity}
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="3">
						ENDEREÇO:
					</td>
					<td class="td-font-14" colspan="9">
						${data.logistics_transporter.address}
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="2">
						ESTADO:
					</td>
					<td class="td-font-14" colspan="4">
						${data.logistics_transporter.state}
					</td>
					<td class="td-title td-font-14 td-bold" colspan="2">
						CIDADE:
					</td>
					<td class="td-font-14" colspan="4">
						${data.logistics_transporter.city}
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="2">
						RECEPCIONISTA:
					</td>
					<td class="td-font-14" colspan="4">
						${data.logistics_transporter.receptionist_name != '' ? data.logistics_transporter.receptionist_name : 'N/A'}
					</td>
					<td class="td-title td-font-14 td-bold" colspan="2">
						RAMAL:
					</td>
					<td class="td-font-14" colspan="4">
						${data.logistics_transporter.ramal != '' ? data.logistics_transporter.ramal : 'N/A'}
					</td>
				</tr>
				<tr>
					<td class="td-title td-font-14 td-bold" colspan="3">
						TELEFONE:
					</td>
					<td class="td-font-14" colspan="9">
						${data.logistics_transporter.phone}
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
			} else if (data.is_cancelled) {
				$('.btn-analyze').hide();
				situation = {
					status: 'Cancelado',
					reason: data.cancelled_reason,
					name: data.who_excute_action,
					time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
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
								<b>TIPO:</b> ${data.type_reason_name}
							</td>
						</tr>
						<tr>
							<td colspan="2" class="td-font-13">
								<b>DATA:</b> ${getDateFormat(data.date_hour, true)} &nbsp;&nbsp;&nbsp;&nbsp;<b>HORA INICIAL:</b> ${data.date_hour_initial ? getHourFormat(data.date_hour_initial) : '-'} &nbsp;&nbsp;&nbsp;&nbsp;<b>HORA FINAL:</b> ${getHourFormat(data.date_hour)}
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
						<tr>
							<td colspan="12" class="td-font-13 td-text-white td-text-center td-color-black td-bold">
								MOTIVO
							</td>
						</tr>
						<tr>
							<td class="td-title td-text-center td-font-14 td-bold" colspan="12">
								${data.reason}
							</td>
						</tr>
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
								${data.request_user.full_name}
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								CARGO:
							</td>
							<td class="td-font-14" colspan="9">
								${data.request_user.office}
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								SETOR:
							</td>
							<td class="td-font-14" colspan="9">
								${data.request_sector}
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="2">
								RAMAL:
							</td>
							<td class="td-font-14" colspan="4">
								${data.request_ramal ? data.request_ramal : 'N/A'}
							</td>
							<td class="td-title td-font-14 td-bold" colspan="2">
								TELEFONE:
							</td>
							<td class="td-font-14" colspan="4">
								${data.request_phone}
							</td>
						</tr>
						${driver}
						${additional_people}
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
								VEÍCULO
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								PLACA:
							</td>
							<td class="td-font-14" colspan="9">
								${data.logistics_transporter_vehicle ? data.logistics_transporter_vehicle.registration_plate : '-'}
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								TIPO:
							</td>
							<td class="td-font-14" colspan="9">
								${data.logistics_transporter_vehicle ? data.logistics_transporter_vehicle.type_vehicle_name : '-'}
							</td>
						</tr>
						${cart}
						${conteiner}
						<tr>
							<td colspan="3" class="td-title td-font-14 td-bold">
								LACRE
							</td>
							<td class="td-font-14" colspan="9">
								${data.code_seal ? data.code_seal : 'N/A'}
							</td>
						</tr>
						${supplier}
						${business}
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
								${data.logistics_warehouse.name}
							</td>
							<td class="td-title td-font-14 td-bold" colspan="2">
								PORTARIA:
							</td>
							<td class="td-font-14" colspan="4">
								${data.logistics_entry_exit_gate.name}
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
				if (data.logistics_warehouse_type_content) {
					content = data.logistics_warehouse_type_content.description;
				}
				if (data.logistics_entry_exit_requests_items.length) {
					data.logistics_entry_exit_requests_items.forEach(function ($var) {
						items += `<tr>
							<td class="td-font-14" colspan="3">
								${$var.code_model}
							</td>
							<td class="td-font-14" colspan="3">
								${$var.description}
							</td>
							<td class="td-font-14" colspan="3">
								${$var.quantity}
							</td>
							<td class="td-font-14" colspan="3">
								${$var.unit}
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
								${data.is_content === 1 ? 'Sim' : 'Não'}
							</td>
							<td class="td-title td-font-14 td-bold" colspan="2">
								CONTEÚDO:
							</td>
							<td class="td-font-14" colspan="4">
								${content}
							</td>
						</tr>
						<tr>
							<td class="td-title td-font-14 td-bold" colspan="3">
								MODELO/CÓDIGO
							</td>
							<td class="td-title td-font-14 td-bold" colspan="3">
								DESCRIÇÃO
							</td>
							<td class="td-title td-font-14 td-bold" colspan="3">
								QUANTIDADE
							</td>
							<td class="td-title td-font-14 td-bold" colspan="3">
								UNID.
							</td>
						</tr>
						${items}
						</tbody>
					</table>
				`;

			$('#charging').html(charge);

			var items_doc = '';
			if (data.logistics_entry_exit_requests_attachs) {
					data.logistics_entry_exit_requests_attachs.forEach(function ($var) {
						items_doc += `<tr>
							<td class="td-font-14" colspan="6">
								${$var.name_attach}
							</td>
							<td class="td-font-14" colspan="6">
								<a href="${$var.url_attach}" target="_blank">Visualizar arquivo</a>
							</td>
						</tr>`
					});
				}
			documents = `<table class="table table-bordered table-view">
						<tbody>
						<tr>
							<td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
								DOCUMENTOS
							</td>
						</tr>
						<tr>
							<td class="td-font-14 td-text-center" colspan="3">
								<b>Nº SAÍDA / ENTRADA MATERIAL</b>
							</td>
							<td class="td-font-14 td-text-center" colspan="5">
								<b>${data.code_di ? data.code_di : '-'}</b>
							</td>
							<td class="td-font-14 td-text-center" colspan="5">
								<a href="${data.url_di ? data.url_di : 'javascript:void(0)'}" target="_blank">${data.url_di ? 'VISUALIZAR ARQUIVO' : '-'}</a>
							</td>
						</tr>
						<tr>
							<td class="td-font-14 td-text-center" colspan="3">
								<b>NOTA FISCAL</b>
							</td>
							<td class="td-font-14 td-text-center" colspan="5">
								<b>${data.nfe_number ? data.nfe_number : '-'}</b>
							</td>
							<td class="td-font-14 td-text-center" colspan="5">
								<a href="${data.nfe_url ? data.nfe_url : 'javascript:void(0)'}" target="_blank">${data.nfe_url ? 'VISUALIZAR ARQUIVO' : '-'}</a>
							</td>
						</tr>
						<tr>
							<td class="td-font-14 td-text-center" colspan="3">
								<b>FATURA</b>
							</td>
							<td class="td-font-14 td-text-center" colspan="5">
								<b>${data.invoice_number ? data.invoice_number : '-'}</b>
							</td>
							<td class="td-font-14 td-text-center" colspan="5">
								<a href="${data.invoice_url ? data.invoice_url : 'javascript:void(0)'}" target="_blank">${data.invoice_url ? 'VISUALIZAR ARQUIVO' : '-'}</a>
							</td>
						</tr>
						<tr>
							<td class="td-font-14 td-text-center" colspan="3">
								<b>GR</b>
							</td>
							<td class="td-font-14 td-text-center" colspan="5">
								<b>${data.code_gr ? data.code_gr : '-'}</b>
							</td>
							<td class="td-font-14 td-text-center" colspan="5">
								<a href="${data.gr_url ? data.gr_url : 'javascript:void(0)'}" target="_blank">${data.gr_url ? 'VISUALIZAR ARQUIVO' : '-'}</a>
							</td>
						</tr>
						${items_doc}
						</tbody>
					</table>
				`;

			$('#documents').html(documents);
			$('.modal-view').toggle();
		}

        function registerDriver($id, $full_name, $office, $sector, $phone, $ramal) {
			loadSingleView($id, 2);
            var loadpeople = `
                        <div style="width: 100%; font-weight: bold; text-align: center">SOLICITANTE</div>
                        <div>
                            <b>Nome:</b> ${$full_name}
                            <br><b>Cargo:</b> ${$office}
                            <br><b>Setor:</b> ${$sector}
                            <br><b>Telefone:</b> ${$phone}
                            <br><b>Ramal:</b> ${$ramal !== 'null' ? $ramal : 'N/A'}
                        </div>`;
            $('#request_people').html(loadpeople);
            $('#d_id').val($id);
            sel_id = $id;
            $('#driver-modal').modal();
        }

        $('#submitDriver').submit(function(e) {
            if (!$('#transporter_id').val().length) {
                e.preventDefault();
                return $error('Você precisa selecionar a transportadora.');
            } else if (!$('#d_name').val()) {
                e.preventDefault();
                return $error('Você precisa informar o nome completo do motorista.');
            } else if (!$('#d_identity').val()) {
                e.preventDefault();
                return $error('Você precisa informar a identidade do motorista.');
            } else if (!$('#d_phone').val()) {
                e.preventDefault();
                return $error('Você precisa informar o número de celular do motorista.');
            } else if (!$('#d_cnh').val()) {
                e.preventDefault();
                return $error('Você precisa anexar a CNH do motorista.');
            }
            block();
        });

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

		// type = 1 para visualização da folha, 2 é para cadastro de motorista.
        function loadSingleView($id, type = 1) {
            block();
            ajaxSend(
                '/controle/portaria/paginas/transporte-de-carga/visualizar',
                {
                    'id': $id,
                    'secret': localStorage.getItem('secret')
                }
            ).then(($result) => {
                unblock();
				if (type == 1) {
					reloadSingle($result);
				} else {
					loadSingleRegister($result);
				}
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
                    '/controle/portaria/paginas/transporte-de-carga/listar',
                    {
                        'page': page,
                        'search': $('#searchText').val(),
						'logistics_transporter': $('#logistics_transporter').val(),
						'logistics_supplier': $('#logistics_supplier').val(),
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

        $(".select2").select2({
            placeholder: 'CNPJ...',
            maximumSelectionLength: 1,
            language: "pt-BR",
            ajax: {
                url: '/controle/portaria/paginas/transporte-de-carga/transportes',
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
		
		$("#transporter_driver_id").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione o motorista ou pedestre",
            language: {
                noResults: function () {
                    return 'Motorista ou pedestre não encontrado'
                }
            },
            ajax: {
                url: '/controle/portaria/misc/driver/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#transporter_vehicle_id").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione pela placa",
            language: {
                noResults: function () {
                    return 'Veículo não encontrado'
                }
            },
            ajax: {
                url: '/controle/portaria/misc/vehicle/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#transporter_cart_id").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione pela placa",
            language: {
                noResults: function () {
                    return 'Carreta não encontrada'
                }
            },
            ajax: {
                url: '/controle/portaria/misc/cart/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });
		
		$(".select-suipplier").select2({
			maximumSelectionLength: 1,
			placeholder: "Selecione o fornecedor",
			language: {
				noResults: function () {
					return 'Fornecedor não encontrado'; 
				}
			},
			ajax: {
				url: '/controle/portaria/misc/supplier/list/dropdown',
				data: function (params) {
					var query = {
						search: params.term,
						page: params.page || 1
					}
					return query;
				}
			}
		});

		$(".select-transporter").select2({
			maximumSelectionLength: 1,
			placeholder: "Selecione a transportadora",
			language: {
				noResults: function () {
					return 'Transportador não encontrado'; 
				}
			},
			ajax: {
				url: '/controle/portaria/misc/transporter/list/dropdown',
				data: function (params) {
					var query = {
						search: params.term,
						page: params.page || 1
					}
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
            $('#d_phone').mask('(00) 00000-0000', {reverse: false});

            loadListView();
            autoReload();
        })
    </script>
@endsection

