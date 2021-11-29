@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<style>
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline {
        width: 100% !important;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline .select2-search__field {
        width: 100% !important;
    }
    .input-item {
        width: 100%;
        font-family: "IBM Plex Sans", Helvetica, Arial, serif;
        color: #475f7b;
        text-align: center;
        border: 0px;
    }
</style>    

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">Logística</h5>
                <div class="breadcrumb-wrapper col-12">
                    Entrada & Saída
                </div>
            </div>
        </div>
        </div>
    </div>
	<div class="alert alert-danger mb-2" role="alert">
        Necessário informar o número e anexar o documento de entrada / saída de material na aba documentos, caso exista.
    </div>
    <div class="content-body">
        <section id="basic-tabs-components">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h4>Solicitação Transporte De Carga</h4>
                    </div>
                </div>
                <form action="/logistics/request/cargo/transport/edit_do" id="form_request_entry" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{$id}}">
                    <input type="hidden" name="arr_archives" id="arr_archives">
                    <input type="hidden" name="arr_remove_items" id="arr_remove_items">
                    <input type="hidden" name="arr_remove_people" id="arr_remove_people">
					<input type="hidden" name="send_approv" id="send_approv" value="0">
                    <div class="card-body" style="padding-top: 0px;">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-request" aria-controls="home" role="tab" aria-selected="true">
                                    <i class="bx bx-file align-middle"></i>
                                    <span class="align-middle">Solicitação</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-transporter" aria-controls="profile" role="tab" aria-selected="false">
                                    <i class="bx bxs-truck align-middle"></i>
                                    <span class="align-middle">Dados do transporte</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-items" aria-controls="profile" role="tab" aria-selected="false">
                                    <i class="bx bx-cube align-middle"></i>
                                    <span class="align-middle">Itens carregamento</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-documents" aria-controls="profile" role="tab" aria-selected="false">
                                    <i class="bx bxs-file-pdf align-middle"></i>
                                    <span class="align-middle">Documentos</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-people-additional" aria-controls="profile" role="tab" aria-selected="false">
                                    <i class="bx bx-group align-middle"></i>
                                    <span class="align-middle">Pessoas Adicionais</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-request" role="tabpanel">  
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Razão</label>
                                            <select class="custom-select" name="type_reason" id="type_reason">
                                                <option value="" @if($type_reason == '') selected @endif>Selecione</option>
                                                <option value="1" @if($type_reason == 1) selected @endif>Entrega de compra</option>
                                                <option value="2" @if($type_reason == 2) selected @endif>Carregamento</option>
                                                <option value="4" @if($type_reason == 4) selected @endif>Importação</option>
                                                <option value="5" @if($type_reason == 5) selected @endif>Transferência</option>
                                                <option value="6" @if($type_reason == 6) selected @endif>Retirada de venda</option>
                                                <option value="7" @if($type_reason == 7) selected @endif>Coleta</option>
                                                <option value="8" @if($type_reason == 8) selected @endif>Entrega de avaria</option>
												<option value="12" @if($type_reason == 12) selected @endif>Manobra</option>
												<option value="11" @if($type_reason == 11) selected @endif>Outros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tipo solicitacão</label>
                                            <select class="custom-select" name="is_entry_exit" id="is_entry_exit">
                                                <option value="1" @if($is_entry_exit == 1) selected @endif>Entrada</option>
                                                <option value="2" @if($is_entry_exit == 2) selected @endif>Saída</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Data liberação</label>
                                            <input type="text" class="form-control date-mask" id="release_date" name="release_date" value="{{$release_date}}" placeholder="00/00/0000">
                                        </div>    
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Hora inicial liberação
                                                <i class="bx bx-info-circle cursor-pointer" style="color: #3568df; position: relative; top: 1px; font-size: 0.9rem; left:1px;"data-toggle="tooltip" data-placement="bottom" data-original-title="Horário que a portaria pode começar a liberar está solicitação"></i>
                                            </label>
											<select class="form-control" id="release_hour_initial" name="release_hour_initial">
                                                @foreach ($arr_range_time as $time)
                                                    <option value="{{$time}}" @if($release_hour_initial == $time) selected @endif>{{$time}}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Hora Final liberação
                                                <i class="bx bx-info-circle cursor-pointer" style="color: #3568df; position: relative; top: 1px; font-size: 0.9rem; left:1px;"data-toggle="tooltip" data-placement="bottom" data-original-title="Após este horário portaria não poderá liberar, apenas mediante consenso do solicitante e recebedor."></i>
                                            </label>
											<select class="form-control" id="release_hour" name="release_hour">
                                                @foreach ($arr_range_time as $time)
                                                    <option value="{{$time}}" @if($release_hour == $time) selected @endif>{{$time}}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Solicitante</label>
                                            <select class="form-control select-request" name="request_r_code" id="request_r_code" style="width: 100%;" multiple>
                                                @foreach ($users_request as $key)
                                                    <option value="{{ $key->r_code }}" @if($request_r_code == $key->r_code) selected @endif>{{ $key->first_name }} {{ $key->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>setor</label>
                                            <input type="text" class="form-control" id="request_sector" name="request_sector" value="{{$request_sector}}"placeholder="" readonly="readonly">
                                        </div>    
                                    </div>    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Telefone</label>
                                            <input type="text" class="form-control phone" id="request_phone" name="request_phone" value="{{$request_phone}}"placeholder="(00) 00000-0000">
                                        </div>    
                                    </div>    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Ramal</label>
                                            <input type="text" class="form-control" id="request_ramal" name="request_ramal" value="{{$request_ramal}}" placeholder="0000">
                                        </div>    
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fornecedor</label>
                                            <select class="custom-select select-suipplier" name="supplier_id" id="supplier_id" style="width: 100%;" multiple>
                                                @foreach ($supplier as $key)
                                                    <option value="{{ $key->id }}" @if($supplier_id == $key->id) selected @endif>{{ $key->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Portaria</label>
                                            <select class="custom-select select-gate" name="entry_exit_gate_id" id="entry_exit_gate_id" style="width: 100%;" multiple>
                                                @foreach ($entry_exit_gate as $key)
                                                    <option value="{{ $key->id }}" @if($entry_exit_gate_id == $key->id) selected @endif>{{ $key->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Galpão</label>
                                            <select class="custom-select select-wharehouse" name="warehouse_id" id="warehouse_id" style="width: 100%;" multiple>
                                                @foreach ($warehouse as $key)
                                                    <option value="{{ $key->id }}" @if($warehouse_id == $key->id) selected @endif>{{ $key->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>     
                                </div>
                                <div class="row">   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Motivo de solicitacão</label>
                                            <textarea name="reason" id="reason" class="form-control" placeholder="Informe motivo ..."><?= $reason ?></textarea>
                                        </div>    
                                    </div>    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Restrição</label>
                                            <textarea name="entry_restriction" id="entry_restriction" class="form-control" placeholder="Informe restrição"><?= $entry_restriction ?></textarea>
                                        </div>    
                                    </div>    
                                </div>
                                <div class="row">   
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Encaminhamento</label>
                                            <input type="text" class="form-control" id="request_forwarding" name="request_forwarding" value="{{$request_forwarding}}" placeholder="Informe o local de encaminhamento">
                                        </div>    
                                    </div>  
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-transporter" role="tabpanel">
                                <div class="row">                                       
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Transportadora</label>
                                            <select class="custom-select select-transporter" name="transporter_id" id="transporter_id" style="width: 100%;" multiple>
                                                @foreach ($transporter as $key)
                                                    <option value="{{ $key->id }}" @if($transporter_id == $key->id) selected @endif>{{ $key->name }} ({{ $key->identity }})</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Carregado</label>
                                            <select class="custom-select" name="is_content" id="is_content">
                                                <option value="0" @if($is_content == 0) selected @endif>Não</option>
                                                <option value="1" @if($is_content == 1) selected @endif>Sim</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>lacre</label>
                                            <input type="text" class="form-control" id="code_seal" name="code_seal" value="{{$code_seal}}" placeholder="Informe o lacre">
                                        </div>    
                                    </div>   
                                </div>
                                <div class="row">   
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Motorista / Pedestre</label>
                                            <select class="custom-select select-driver" name="transporter_driver_id" id="transporter_driver_id" style="width: 100%;" multiple>
                                                @foreach ($drivers as $key)
                                                    <option value="{{ $key->id }}" @if($transporter_driver_id == $key->id) selected @endif>{{ $key->name }} - {{ $key->identity }} ({{$key->transporter_name}})</option>
                                                @endforeach
                                            </select>
                                        </div>      
                                    </div>    
                                </div>    
                                <div class="row">   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Veículo</label>
                                            <select class="custom-select select-vehicle" name="transporter_vehicle_id" id="transporter_vehicle_id" style="width: 100%;" multiple>
                                                @foreach ($vehicles as $key)
                                                    <option value="{{ $key->id }}" @if($transporter_vehicle_id == $key->id) selected @endif>{{ $key->registration_plate }} - ({{stringCut($key->transporter_name, 25)}})</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Carreta</label>
                                            <select class="custom-select select-cart" name="transporter_cart_id" id="transporter_cart_id" style="width: 100%;" multiple>
                                                @foreach ($carts as $key)
                                                    <option value="{{ $key->id }}" @if($transporter_cart_id == $key->id) selected @endif>{{ $key->registration_plate }} - ({{stringCut($key->transporter_name, 25)}})</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Container</label>
                                            <select class="custom-select select-container" name="transporter_container_id" id="transporter_container_id" style="width: 100%;" multiple>
                                                @foreach ($containers as $key)
                                                    <option value="{{ $key->id }}" @if($transporter_container_id == $key->id) selected @endif>{{ $key->number_container }} - ({{stringCut($key->company_name, 15)}})</option>
                                                @endforeach
                                            </select>
                                        </div>  
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Houve transbordo?</label>
                                            <select class="custom-select" name="is_transhipment" id="is_transhipment">
                                                <option value="0" @if($is_transhipment == 0) selected @endif>NÃO</option>
                                                <option value="1" @if($is_transhipment == 1) selected @endif>SIM</option>
                                            </select>
                                        </div>    
                                    </div>
                                </div>    
                                <div class="row div-container-transhipment" style="@if($is_transhipment == 1) display:block; @else display:none; @endif">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Container</label>
                                            <select class="custom-select select-container-transhipment" name="transhipment_container_id" id="transhipment_container_id" style="width: 100%;" multiple>
                                                @foreach ($containers as $key)
                                                    <option value="{{ $key->id }}" @if($transhipment_container_id == $key->id) selected @endif>{{ $key->number_container }} - ({{stringCut($key->company_name, 15)}})</option>
                                                @endforeach
                                            </select>
                                        </div>  
                                    </div> 
                                </div>    
                            </div>    
                            <div class="tab-pane" id="tab-items" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>Tipo de conteúdo</label>
                                            <select class="custom-select select-type-content" name="warehouse_type_content_id" id="warehouse_type_content_id" style="width: 100%;" multiple>
                                                @foreach ($type_content as $key)
                                                    <option value="{{ $key->id }}" @if($warehouse_type_content_id == $key->id) selected @endif>{{ $key->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>  
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-secondary shadow mt-2" style="width: 100%;" data-toggle="modal" data-target="#modal_import_items"><i class="bx bx-import"></i> Importar itens</button>
                                    </div>    
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead style="text-align: center;">
                                            <tr>
                                                <th>MODELO/CÓDIGO</th>
                                                <th>DESCRIÇÃO</th>
                                                <th>QUANTIDADE</th>
                                                <th>VALOR</th>
                                                <th>UNID</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_itens">
                                            <tr id="tr_not_item">
                                                <td colspan="5" style="text-align: center;">Não há itens adicionados</td>
                                            </tr>    
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-outline-primary" id="btn_new_item" style="width:100%;">
                                        <i class="bx bx-plus"></i> Novo item
                                    </button>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-documents" role="tabpanel">
								<div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nº Saída / Entrada Material</label>
                                            <input type="text" class="form-control" id="code_di" name="code_di" value="{{ $code_di }}"placeholder="Informe o número">
                                        </div>    
                                    </div>    
                                    <div class="col-md-6">
                                        <fieldset class="form-group">
                                            <label for="price">Arquivo Saída / Entrada Material</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend" style="@if($url_di == '') display:none; @endif width: 100%;">
                                                    <span class="input-group-text" style="width: 100%;">
                                                        <a href="{{$url_di}}" target="_blank">
                                                            <i class="bx bx-link-external" style="top: 3px; margin-right:2px; position:relative;"></i>
                                                            Click para visualizar o arquivo
                                                        </a>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <a class="remove-url" href="javascript:void(0)" data-type="4" data-url="{{$url_di}}" data-id="{{$id}}">
                                                            <i class="bx bx-trash" style="top: 3px;position:relative;color: #e04747;"></i>
                                                        </a>    
                                                    </span>
                                                </div>
                                                <div class="custom-file" @if($url_di != '') style="display:none" @endif>
                                                    <input type="file" class="custom-file-input" name="url_di" id="url_di" style="cursor:pointer;">
                                                    <label class="custom-file-label">@if($url_di != '') Atualizar @else Escolher @endif arquivo</label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Número da Nota Fiscal (NFE)</label>
                                            <input type="text" class="form-control" id="nfe_number" name="nfe_number" value="{{ $nfe_number }}"placeholder="Informe o número">
                                        </div>    
                                    </div>    
                                    <div class="col-md-6">
                                        <fieldset class="form-group">
                                            <label for="price">Arquivo Nota Fiscal (NFE)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend" style="@if($nfe_url == '') display:none; @endif width: 100%;">
                                                    <span class="input-group-text" style="width: 100%;">
                                                        <a href="{{$nfe_url}}" target="_blank">
                                                            <i class="bx bx-link-external" style="top: 3px; margin-right:2px; position:relative;"></i>
                                                            Click para visualizar o arquivo
                                                        </a>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <a class="remove-url" href="javascript:void(0)" data-type="1" data-url="{{$nfe_url}}" data-id="{{$id}}">
                                                            <i class="bx bx-trash" style="top: 3px;position:relative;color: #e04747;"></i>
                                                        </a>    
                                                    </span>
                                                </div>
                                                <div class="custom-file" @if($nfe_url != '') style="display:none" @endif>
                                                    <input type="file" class="custom-file-input" name="nfe_file" id="nfe_file" style="cursor:pointer;">
                                                    <label class="custom-file-label">@if($nfe_url != '') Atualizar @else Escolher @endif arquivo</label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Código da fatura</label>
                                            <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ $invoice_number }}" placeholder="Informe a fatura">
                                        </div>    
                                    </div>    
                                    <div class="col-md-6">
                                        <fieldset class="form-group">
                                            <label for="price">comprovante da fatura</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend" style="@if($invoice_url == '') display:none; @endif width: 100%;">
                                                    <span class="input-group-text" style="width: 100%;">
                                                        <a href="{{$invoice_url}}" target="_blank">
                                                            <i class="bx bx-link-external" style="top: 3px; margin-right:2px; position:relative;"></i>
                                                            Click para visualizar o arquivo
                                                        </a>    
                                                    </span>
                                                    <span class="input-group-text">
                                                        <a class="remove-url" href="javascript:void(0)" data-type="2" data-url="{{$invoice_url}}" data-id="{{$id}}">
                                                            <i class="bx bx-trash" style="top: 3px;position:relative;color: #e04747;"></i>
                                                        </a>    
                                                    </span>
                                                </div>
                                                <div class="custom-file" @if($invoice_url != '') style="display:none" @endif>
                                                    <input type="file" class="custom-file-input" name="invoice_file" id="invoice_file" style="cursor:pointer;">
                                                    <label class="custom-file-label">@if($invoice_url != '') Atualizar @else Escolher @endif arquivo</label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Código GR</label>
                                            <input type="text" class="form-control" id="code_gr" name="code_gr" value="{{ $code_gr }}" placeholder="Informe código GR">
                                        </div>    
                                    </div>    
                                    <div class="col-md-6">
                                        <fieldset class="form-group">
                                            <label for="price">Arquivo GR</label>
                                            <div class="input-group">
                                                @if($gr_url != '')
                                                    <div class="input-group-prepend" style="@if($gr_url == '') display:none; @endif width: 100%;">
                                                        <span class="input-group-text" style="width: 100%;">
                                                            <a href="{{$gr_url}}" target="_blank">
                                                                <i class="bx bx-link-external" style="top: 3px; margin-right:2px; position:relative;"></i>
                                                                Click para visualizar o arquivo
                                                            </a>    
                                                        </span>
                                                        <span class="input-group-text">
                                                            <a class="remove-url" href="javascript:void(0)" data-type="3" data-url="{{$gr_url}}" data-id="{{$id}}">
                                                                <i class="bx bx-trash" style="top: 3px;position:relative;color: #e04747;"></i>
                                                            </a>    
                                                        </span>
                                                    </div>
                                                @endif
                                                <div class="custom-file" @if($gr_url != '') style="display:none" @endif>
                                                    <input type="file" class="custom-file-input" name="gr_file" id="gr_file" style="cursor:pointer;">
                                                    <label class="custom-file-label">@if($gr_url != '') Atualizar @else Escolher @endif arquivo</label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead style="text-align: center;">
                                            <tr>
                                                <th>DESCRIÇÃO</th>
                                                <th>ARQUIVO</th>
                                                <th>AÇÃO</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_archives">
                                            <tr id="tr_not_archive">
                                                <td colspan="5" style="text-align: center;">Não há arquivos adicionados</td>
                                            </tr>    
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-outline-primary" style="width:100%;" data-toggle="modal" data-target="#modal_add_archive">
                                        <i class="bx bx-plus"></i> Adicionar arquivo
                                    </button>
                                </div>
                            </div>    
                            <div class="tab-pane" id="tab-people-additional" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead style="text-align: center;">
                                            <tr>
                                                <th>NOME COMPLETO</th>
                                                <th>RG / CPF</th>
                                                <th>MOTIVO</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_peoples">
                                            <tr id="tr_not_people">
                                                <td colspan="5" style="text-align: center;">Não há pessoas adicionadas</td>
                                            </tr>    
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-outline-primary" id="btn_new_people" style="width:100%;">
                                        <i class="bx bx-plus"></i> Nova pessoa
                                    </button>
                                </div>
                            </div>    
                        </div>
                        @if($has_analyze == 0 && $is_cancelled == 0)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    @if($is_approv == 1)
                                        <button type="button" class="btn btn-primary" id="btn_save_approv" style="width:100%;">
                                            Atualizar e enviar para aprovação
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-primary" id="btn_save_request" style="width:100%;">
                                            @if($id == 0) Cadastrar @else Atualizar @endif solicitacão
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal_import_items" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Importar itens</span>
            </div>
            <div class="modal-body">
                <div class="alert border-primary alert-dismissible mb-2" role="alert">
                    <div class="d-flex align-items-center">
                        <span>
                            <a targe="_blank" href="/excell/model_import_items.xlsx" >Modelo de importação <i class="bx bxs-download"></i></a>
                        </span>
                    </div>
                  </div>
                <form method="post" action="#" id="form_import_items">
                    <div class="row">
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="price">Arquivo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file_items" id="file_items">
                                    <label class="custom-file-label label-items">Escolher arquivo</label>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>    
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_import_items">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Importar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_add_archive" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Adicionar Arquivo</span>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="form_add_archive">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="serie">Descrição</label>
                                    <input type="text" class="form-control" name="name_attach" id="name_attach" placeholder="Informe a descrição do arquivo">
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="price">Arquivo</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file_archive" id="file_archive">
                                        <label class="custom-file-label label-archives">Escolher arquivo</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div> 
                    </div>
                </form> 
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_upload_archive">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Adicionar</span>
                </button>
            </div>
        </div>
    </div>
</div>   

<div class="customizer d-md-block text-center">
    <a style="writing-mode: vertical-lr;height: 140px;font-weight: bold;" class="customizer-toggle" href="javascript:void(0);">
        Recebimento
    </a>
    <a class="customizer-close" href="#"><i class="bx bx-x"></i></a>
    <div class="customizer-content p-2 ps ps--active-y">
        <h4 class="text-uppercase mb-0 histId">Disponibilidade</h4>
        <div style="background-color: red;color: white;padding: 6px;border-radius: 4px;margin: 15px;">Veja a grade de horários para conteúdos que não sejam produtivos</div>
        <hr>
        <div class="theme-layouts">
            <fieldset>
                <div class="input-group">
                    <input type="text" class="form-control" id="receivement_date" value="{{date('d/m/Y')}}" placeholder="DD/MM/YYYY" aria-describedby="button-addon2">
                    <div class="input-group-append" id="button-addon2">
                        <button class="btn btn-primary" onclick="receivementReload()" type="button">Buscar</button>
                    </div>
                </div>
            </fieldset>
            <div class="d-flex justify-content-start text-left p-1">
                <ul class="widget-timeline listitens p-0" style="width: 100%;">
                    @foreach($list_hours as $list)
                    <li>
                        <div class="d-flex flex-row">
                            <div class="bg-primary text-white p-2 mr-1 text-center" style="border-radius: 5px;">
                                {{$list['hour']}}
                            </div>
                            @for ($i=0; $i < $list['quantity']; $i++)
                                <div class="bg-danger text-white p-2 mr-1 text-center" style="border-radius: 5px; min-width: 106px">
                                    Ocupado
                                </div>
                            @endfor
                            @php
                            $free = 2 - $list['quantity'];
                            @endphp
                            @for ($i=0; $i < $free; $i++)
                                <div class="bg-success text-white p-2 mr-1 text-center" onclick="$('#release_hour').val('{{str_replace(':00', '', $list['hour'])}}'); $($('.customizer')).toggleClass('open'); $('#release_date').val($('#receivement_date').val())" style="border-radius: 5px; min-width: 106px">
                                    Livre
                                </div>
                            @endfor
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Hide Scroll To Top Ends-->
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;">
            </div></div><div class="ps__rail-y" style="top: 0px; height: 754px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 590px;">
            </div>
        </div>
    </div>
</div>

<form method="post" action="/logistics/request/cargo/transport/delete/fix/archive" id="form_remove_archive">
    <input type="hidden" name="id" id="remove_id">
    <input type="hidden" name="type" id="remove_type">
    <input type="hidden" name="url" id="remove_url">
</form>    

<script>

    var arr_sector =  {!! json_encode(config('gree.sector')) !!};
    var arr_archives = {!! json_encode($arr_attachs) !!};
    var arr_items = {!! json_encode($arr_items) !!}
    var arr_people = {!! json_encode($arr_people) !!}
    var arr_remove_items = [];
    var arr_remove_people = [];
	
	$('.customizer-toggle').click(function() {
        $($(".customizer")).toggleClass('open');
    });
	
	function receivementReload() {
        $('.listitens').html(`<div class="spinner-grow" role="status">
                    <span class="sr-only">Loading...</span>
            </div>`);
        ajaxSend('/logistics/request/cargo/transport/receivement?receivement_date='+$('#receivement_date').val(), {}, 'GET', '100000').then(function(result){
            $('.listitens').html('');
            if(result.length > 0) {
                var list = '';
                result.forEach(function(val) {
                    list += '<li><div class="d-flex flex-row">';
                    list += '<div class="bg-primary text-white p-2 mr-1 text-center" style="border-radius: 5px;">';
                    list += val.hour;
                    list += '</div>';
                    for (var i=0; i < val.quantity; i++) {
                        list += '<div class="bg-danger text-white p-2 mr-1 text-center" style="border-radius: 5px; min-width: 106px">';
                        list += 'Ocupado';
                        list += '</div>';
                    }

                    var free = 2 - val.quantity;
                    for (var ii=0; ii < free; ii++) {
                        var hr = val.hour;
                        list += `
                            <div class="bg-success text-white p-2 mr-1 text-center"
                                 onclick="$('#release_hour').val('${hr.replace(":00", "")}'); $($('.customizer')).toggleClass('open'); $('#release_date').val($('#receivement_date').val())"
                                 style="border-radius: 5px; min-width: 106px">
                                Livre
                            </div>`;
                    }
                    list += '</div></li>';
                });
                $('.listitens').html(list);
            } else {
                return $error('Aconteceu algo inesperado...');
            }

        }).catch(function(err){
            $('.listitens').html('');
            $error(err.message)
        });
    }

    $(document).ready(function () {
		
		$(".remove-url").click(function() {

            var type = $(this).attr("data-type");
            var url = $(this).attr("data-url");
            var id = $(this).attr("data-id");

            Swal.fire({
                title: 'Remover arquivo',
                text: "Deseja confirmar a remoção do arquivo?",
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
                    $("#remove_type").val(type);
                    $("#remove_url").val(url);
                    $("#remove_id").val(id);
                    $("#form_remove_archive").submit();
                }
            });
        });

        if(arr_items.length > 0) {
            $("#tr_not_item").hide();
            $("#table_itens").append(reloadItems(arr_items, 2));
        }

        if(arr_archives.length > 0) {
            $("#modal_add_archive").modal('hide');
            $('#table_archives').html(reloadArquives(arr_archives, 2));
        }
        
        if(arr_people.length > 0) {
            $("#tr_not_people").hide();
            $("#table_peoples").append(reloadPeople(arr_people));
        }

        $(".select-request").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione",
            language: {
                noResults: function () {
                    return 'Usuário não existe ou está desativado...';
                }
            },
            ajax: {
                url: '/logistics/users/rcode/list',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".select-gate").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione a portaria",
            language: {
                noResults: function () {
                    return 'Portaria não encontrada'; 
                }
            },
            ajax: {
                url: '/logistics/gate/dropdown/list',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".select-wharehouse").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione o galpão",
            language: {
                noResults: function () {
                    return 'Galpão não encontrado'; 
                }
            },
            ajax: {
                url: '/logistics/warehouse/list/dropdown',
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
                url: '/logistics/supplier/list/dropdown',
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
                    var url = "'/logistics/transporter/list/#modal_add_transporter'";
                    return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo transportadora</button>');
                }
            },
            ajax: {
                url: '/logistics/transporter/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".select-driver").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione o motorista",
            language: {
                noResults: function () {
                    return 'Motorista não encontrado'
                }
            },
            ajax: {
                url: '/logistics/transporter/driver/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".select-vehicle").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione pela placa",
            language: {
                noResults: function () {
                    return 'Veículo não encontrado'
                }
            },
            ajax: {
                url: '/logistics/transporter/vehicle/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".select-cart").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione pela placa",
            language: {
                noResults: function () {
                    return 'Carreta não encontrada'
                }
            },
            ajax: {
                url: '/logistics/transporter/cart/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".select-container, .select-container-transhipment").select2({
            maximumSelectionLength: 1,
            placeholder: "pesquise número container",
            language: {
                noResults: function () {
                    return 'Container não encontrado'
                }
            },
            ajax: {
                url: '/logistics/container/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".select-type-content").select2({
            placeholder: "Selecione tipo de conteúdo",
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return 'tipo de conteúdo não encontrado';
                }
            },
            ajax: {
                url: '/logistics/warehouse/type/content/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $('.select-request').on('select2:select', function (e) {
            var data = e.params.data;
            $("#request_sector").val(arr_sector[data.sector]);
            $("#request_phone").val(data.phone);
        }); 

        $('.select-request').on('select2:close', function (e) {
            $("#request_sector, #request_phone").val('');
        });    

        $("#is_transhipment").change(function() {
            if($(this).val() == 1) {
                $('.div-container-transhipment').show();
            } else {
                $('.select-container-transhipment').val(0).trigger('change');
                $('.div-container-transhipment').hide();
            }
        });

        $("#btn_new_item").click(function() {

            $("#tr_not_item").hide();

            var html = `<tr>
                            <td style="display:none;"><input class="input-item" name="items_id[]" value="0"></td>
                            <td style="padding: 5px;"><input class="input-item" name="items_model[]" placeholder="..."></td>
                            <td style="padding: 5px;"><input class="input-item" name="items_description[]" placeholder="..."></td>
                            <td style="padding: 5px;"><input type="number" class="input-item" name="items_quantity[]" placeholder="0"></td>
                            <td style="padding: 5px;"><input class="input-item money" name="items_total[]" placeholder="0,00"></td>
                            <td style="padding: 5px;"><input class="input-item" name="items_unit[]" placeholder="..."></td>
                            <td style="padding: 5px; text-align: center;">
                                <a href="javascript:void(0);" class="remove-item" data-id="0"><i class="bx bx-trash"></i></a>
                            </td>
                        </tr>`;

            $("#table_itens").append(html);
        });

        $(document).on('click', '.remove-item', function (e){

            var id = $(this).attr('data-id');
            if(id != 0) {
                arr_remove_items.push(id);
            }   
            $(this).parent().parent().remove();
        });

        $("#btn_new_people").click(function() {

            $("#tr_not_people").hide();
            var html = `<tr>
                            <td style="display:none;"><input class="input-item" name="people_id[]" value="0"></td>
                            <td style="padding: 5px;"><input class="input-item" name="people_name[]" placeholder="..."></td>
                            <td style="padding: 5px;"><input class="input-item" name="people_identity[]" placeholder="..."></td>
                            <td style="padding: 5px;"><input class="input-item" name="people_reason[]" placeholder="..."></td>
                            <td style="padding: 5px; text-align: center;">
                                <a href="javascript:void(0);" class="remove-people" data-id="0"><i class="bx bx-trash"></i></a>
                            </td>
                        </tr>`;

            $("#table_peoples").append(html);
        });

        $(document).on('click', '.remove-people', function (e){

            var id = $(this).attr('data-id');
            if(id != 0) {
                arr_remove_people.push(id);
            }   
            $(this).parent().parent().remove();
        });
        
        $("#btn_import_items").click(function() {

            if($("#file_items")[0].files.length == 0) {
                return $error('Selecione um arquivo!');
            } else {
                block();
                ajaxSend('/logistics/request/cargo/transport/import/items', $("#form_import_items").serialize(), 'POST', '100000', $("#form_import_items")).then(function(result){

                    if(result.success) {
                        if(result.items.length > 0) {
                            $("#tr_not_item").hide();
                            $("#table_itens").append(reloadItems(result.items, 1));
                            $("#file_items").val('');
                            $(".label-items").text('ESCOLHER ARQUIVO');
                            $("#modal_import_items").modal('hide');
                        } else {
                            return $error('O arquivo está sem dados!');
                        }
                    } 
                    unblock();

                }).catch(function(err){
                    unblock();
                    $error(err.message)
                });
            }
        });

        function reloadItems(items, type) {

            var html = '';
            items.forEach(element => {

                var model = type == 1 ? element[0] : element.code_model;
                var description = type == 1 ? element[1] : element.description;
                var quantity = type == 1 ? element[2] : element.quantity;
                var total = type == 1 ? element[3] : element.total;
                var unit = type == 1 ? element[4] : element.unit;
                var id = type == 1 ? 0 : element.id;

                html += 
                    `<tr>
                        <td style="display:none;"><input class="input-item" name="items_id[]" value="`+id+`"></td>
                        <td style="padding: 5px;"><input class="input-item" name="items_model[]" value="`+model+`" placeholder="..."></td>
                        <td style="padding: 5px;"><input class="input-item" name="items_description[]" value="`+description+`" placeholder="..."></td>
                        <td style="padding: 5px;"><input type="number" class="input-item" name="items_quantity[]" value="`+quantity+`" placeholder="0"></td>
                        <td style="padding: 5px;"><input class="input-item money" name="items_total[]" value="`+total+`" placeholder="0,00"></td>
                        <td style="padding: 5px;"><input class="input-item" name="items_unit[]" value="`+unit+`" placeholder="..."></td>
                        <td style="padding: 5px; text-align: center;">
                            <a href="javascript:void(0);" class="remove-item" data-id="`+id+`"><i class="bx bx-trash"></i></a>
                        </td>
                    </tr>`;
            });
            return html;
        }

        function reloadPeople(items) {

            var html = '';
            items.forEach(element => {

                html += 
                    `<tr>
                        <td style="display:none;"><input class="input-item" name="people_id[]" value="`+element.id+`"></td>
                        <td style="padding: 5px;"><input class="input-item" name="people_name[]" value="`+element.name+`" placeholder="..."></td>
                        <td style="padding: 5px;"><input class="input-item" name=people_identity[]" value="`+element.identity+`" placeholder="..."></td>
                        <td style="padding: 5px;"><input class="input-item" name="people_reason[]" value="`+element.reason+`" placeholder="..."></td>
                        <td style="padding: 5px; text-align: center;">
                            <a href="javascript:void(0);" class="remove-people" data-id="`+element.id+`"><i class="bx bx-trash"></i></a>
                        </td>
                    </tr>`;
            });
            return html;
        }

        $("#btn_upload_archive").click(function() {

            if($("#name_attach").val() == 0) {
                return $error('Nome do arquivo não pode ser vazio!');
            }    
            else if($("#file_archive")[0].files.length == 0) {
                return $error('Selecione um arquivo!');
            } 
            else {

                block();
                ajaxSend('/logistics/request/cargo/transport/upload/archive', $("#form_add_archive").serialize(), 'POST', '100000', $("#form_add_archive")).then(function(result){

                    if(result.success) {

                        $("#tr_not_archive").hide();

                        arr_archives.push({
                            'name_attach' : $("#name_attach").val(),
                            'url_attach': result.url
                        });

                        $("#name_attach, #file_archive").val('');
                        $(".label-archives").text('ESCOLHER ARQUIVO');
                        $("#modal_add_archive").modal('hide');
                        $('#table_archives').html(reloadArquives(arr_archives, 1));
                    } 
                    unblock();
                    
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                });
            }
        });

        function reloadArquives(archives, type) {
            var html = '';

            archives.forEach(function(element, index){

                var id = type == 1 ? 0 : element.id;
                var request_id = type == 1 ? 0 : element.entry_exit_requests_id;

                html += 
                    `<tr>
                        <td style="padding:5px; text-align:center;">`+element.name_attach+`</td>
                        <td style="padding:5px; text-align:center;"><a href="`+element.url_attach+`" target="_blank">Visualizar</a></td>
                        <td style="padding:5px; text-align:center;">
                            <a href="javascript:void(0);" class="remove-archive" 
                                data-url="`+element.url_attach+`" 
                                data-index="`+index+`"
                                data-request-id = "`+request_id+`"
                                data-id = "`+id+`"
                            ><i class="bx bx-trash"></i></a>
                        </td>
                    </tr>`;
            });
            return html;
        }

        $(document).on('click', '.remove-archive', function(e) {

            var $this = $(this);
            var url = $this.attr('data-url');
            var index = $this.attr('data-index');
            var request_id = $this.attr('data-request-id');
            var id = $this.attr('data-id');
            
            if(url != '') {
                block();
                ajaxSend('/logistics/request/cargo/transport/delete/archive', {url: url, id: id, request_id : request_id}, 'GET', 6000).then(function(result) {
                    
                    if(result.success) {

                        arr_archives.splice(index, 1);
                        $('#table_archives').html(reloadArquives(arr_archives, 1));
                        
                        $this.parent().parent().remove();
                        $success(result.message); 
                    }
                    unblock();
                }).catch(function(err){
                    unblock();
                    $error(err.message);
                });
            }
        });
		
		$("#btn_save_approv").click(function() {

            if(verifyForm()) {

                Swal.fire({
                    title: 'Atualizar e Enviar para aprovação',
                    text: "Confirmar a atualização e envio para aprovação?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        $("#send_approv").val(1);
                        sendForm();
                    }
                });
            }
        });

        $("#btn_save_request").click(function() {
            if(verifyForm()) {
                sendForm();
            }
        });
		
		function sendForm() {
            block();
            $("#arr_remove_items").val(JSON.stringify(arr_remove_items));
            $("#arr_archives").val(JSON.stringify(arr_archives));
            $("#arr_remove_people").val(JSON.stringify(arr_remove_people));
            $("#form_request_entry").submit();
        }
		
		function verifyForm() {
            
            if($("#type_reason").val() == "") {
                return $error('Tipo de solicitacão obrigatória');
            }
            else if ($('#release_date').val() == "") {
                return $error('Data de liberação obrigatória');
            }
            else if ($('#release_hour_initial').val() == "") {
                return $error('Horário de liberação inicial obrigatório');
            }
            else if ($('#release_hour').val() == "") {
                return $error('Horário de liberação final obrigatório');
            }
            else if ($('#request_r_code').val() == "") {
                return $error('Solicitante obrigatório');
            }
            else if ($('#request_phone').val() == "") {
                return $error('Telefone solicitante obrigatório');
            }
            else if ($('#entry_exit_gate_id').val() == "") {
                return $error('Portaria obrigatória');
            }
            else if ($('#warehouse_id').val() == "") {
                return $error('Galpão obrigatório');
            }
            else if ($('#reason').val() == "") {
                return $error('Motivo de solicitacão obrigatória');
            }
            else if ($('#is_entry_exit').val() == 2 && $('#transporter_driver_id').val() == "") {
                return $error('Motorista da solicitacão obrigatória');
            }
            /*else if ($('#is_entry_exit').val() == 2 && $('#transporter_vehicle_id').val() == "") {
                return $error('Veículo da solicitacão obrigatória');
            }*/
			/*else if($("#is_content").val() == 1 && $('input[name="items_description[]"]').val() == undefined) {
                return $error('Informe os itens do carregamento');
            }*/
			else if($('input[name="items_description[]"]').val() != undefined && $("#is_content").val() == 0) {
                return $error('Você precisa selecionar SIM para carregado');
            }
            else if($('input[name="items_description[]"]').val() != undefined && $("#warehouse_type_content_id").val() == "") {
                return $error('Selecione o tipo de conteúdo');
            } else {
                return true;
            }
        }

        $('.hour-work-mask').mask('00', {reverse: false});
		$('#receivement_date').mask('00/00/0000', {reverse: false});
        $('.identity-mask').mask('000.000.000-00', {reverse: false});
        
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.phone').mask(SPMaskBehavior, spOptions);

        $(document).on("focus", ".money", function() {
            $(this).mask('000.000,0000', {reverse: true});
        });

        $('.date-mask').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'dd/mm/yyyy',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        });

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExit").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExitRequestCargoTranspList").addClass('active');
        }, 100);

        /*$('.select-container').on('select2:select', function (e) {

            if($('.select-cart').val() == '') {
                $('.select-container').val(0).trigger('change');
                return $error('Você precisa selecionar uma CARRETA PORTA CONTAINER');
            } else {
                var cart = $('.select-cart').select2('data')[0];
                if(cart.type != 3) {
                    $('.select-cart').val(0).trigger('change');
                    return $error('CARRETA SELECIONADA NÃO É UM PORTA CONTAINER');
                }
            }
        });*/
    });
</script>
@endsection