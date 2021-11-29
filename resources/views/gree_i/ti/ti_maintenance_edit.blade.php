@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-12">
            <h5 class="content-header-title float-left pr-1 mb-0">TI - Suporte e Manutenção</h5>
            <div class="breadcrumb-wrapper col-12">
                Novo atendimento
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form action="/ti/maintenance/update" id="sendForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="status" value="<?= $status ?>">
						<input type="hidden" name="priority" id="priority">
                        @if (!hasPermManager(4))
                            <input type="hidden" name="request_r_code" value="<?= Session::get('r_code') ?>">
                        @endif
                        @if (hasPermManager(4))
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="request_r_code">Solicitante</label>
                                    <select class="form-control js-select23" id="request_r_code" name="request_r_code" multiple>
                                        @foreach ($users as $key)
                                        <option value="{{ $key->r_code }}" @if ($key->r_code == $request_r_code) selected @endif>{{ $key->first_name }} {{ $key->last_name }} ({{ $key->r_code }})</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                @if ($categories)
								@php
								$cat_group = $categories->groupBy('type');
								@endphp
                                <div class="form-group">
									<label for="category">Tipo de Atendimento</label>
									<select name="category" class="category form-control" multiple="multiple" id="category">
										@foreach($cat_group as $index => $cat)
										<optgroup label="@if($index == 1) Hardware @else Software @endif">
										@foreach ($cat as $key)
                                        <option value="{{ $key->id }}" @if ($category == $key->id) selected @endif priority="{{ $key->priority }}">{{ $key->name }}</option>
										@endforeach
										</optgroup>
                                        @endforeach
									</select>
                                    
                                    
                                </div>
                                @endif
                            </div>   
                        </div>
                        <div class="row" id="row_printer_model" style="display:none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="printer_model">Modelo da Impresora</label>
                                    <select class="form-control" id="printer_model" name="printer_model">
                                        <option value="">Selecione o modelo</option>
                                        <option value="1" @if ($printer_model == 1) selected @endif>HP Laserjet 400 M401dw</option>
                                        <option value="2" @if ($printer_model == 2) selected @endif>HP LASERJET PRO M402dne</option>
                                        <option value="3" @if ($printer_model == 3) selected @endif>HP LaserJet Pro M12w</option>
                                        <option value="4" @if ($printer_model == 4) selected @endif>HP Laserjet Color M251nw</option>
                                        <option value="5" @if ($printer_model == 5) selected @endif>HP Laserjet M127fn</option>
                                        <option value="6" @if ($printer_model == 6) selected @endif>HP Laserjet M225-M226</option>
                                        <option value="7" @if ($printer_model == 7) selected @endif>HP Laserjet M201dw</option>
                                        <option value="8" @if ($printer_model == 8) selected @endif>Samsung ProXpress M4075fr</option>
                                        <option value="9" @if ($printer_model == 9) selected @endif>Brother 16NW</option>
                                        <option value="10" @if ($printer_model == 10) selected @endif>Brother DCP-L5602DN</option>
                                        <option value="11" @if ($printer_model == 11) selected @endif>EPSON 2190</option>
                                        <option value="12" @if ($printer_model == 12) selected @endif>HP Laser 107W</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="row_toner_model" style="display:none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="toner_model">Modelo do Toner</label>
                                    <select class="form-control" id="toner_model" name="toner_model">
                                        <option value="">Selecione o modelo do Toner</option>
                                        <option value="1" @if ($toner_model == 1) selected @endif>26A</option>
                                        <option value="2" @if ($toner_model == 2) selected @endif>78A</option>
                                        <option value="3" @if ($toner_model == 3) selected @endif>79A</option>
                                        <option value="4" @if ($toner_model == 4) selected @endif>80A</option>
                                        <option value="5" @if ($toner_model == 5) selected @endif>83A</option>
                                        <option value="6" @if ($toner_model == 6) selected @endif>D204</option>
                                        <option value="7" @if ($toner_model == 7) selected @endif>105A</option>
                                        <option value="8" @if ($toner_model == 8) selected @endif>BROTER</option>
                                        <option value="9" @if ($toner_model == 9) selected @endif>D204E</option>
                                    </select>
                                </div>    
                            </div>    
                        </div>
                        <div class="row" id="row_reserve" style="display:none;">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="date">Início da reserva</label>
                                    <input type="text" class="form-control date-mask" name="start_reserve" id="start_reserve" value="<?= $start_reserve ?>">
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="date">Final da reserva</label>
                                    <input type="text" class="form-control date-mask" name="final_reserve" id="final_reserve" value="<?= $final_reserve ?>">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit">Unidade</label>
                                    <select class="form-control" id="unit" value="<?= $unit ?>" name="unit">
                                        <option value="1" @if ($unit == 1) selected @endif>ADMINISTRATIVO</option>
                                        <option value="2" @if ($unit == 2) selected @endif>GALPÃO 1</option>
                                        <option value="3" @if ($unit == 3) selected @endif>GALPÃO 2</option>
                                        <option value="4" @if ($unit == 4) selected @endif>GALPÃO 3</option>
                                        <option value="5" @if ($unit == 5) selected @endif>AZALEIA</option>
										<option value="6" @if ($unit == 6) selected @endif>SUZUKI G1</option>
										<option value="7" @if ($unit == 7) selected @endif>SUZUKI G2</option>
                                    </select>
                                </div>
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Setor</label>
                                    <select class="form-control" id="sector" name="sector" required>
										<option value="" selected></option>
                                        <option value="1" @if ($sector == 1) selected @endif>RH</option>
                                        <option value="2" @if ($sector == 2) selected @endif>Almoxarifado A</option>
                                        <option value="3" @if ($sector == 3) selected @endif>Almoxarifado B</option>
                                        <option value="4" @if ($sector == 4) selected @endif>Almoxarifado C</option>
                                        <option value="5" @if ($sector == 5) selected @endif>Almoxaridado NF</option>
                                        <option value="6" @if ($sector == 6) selected @endif>Recebimento</option>
                                        <option value="7" @if ($sector == 7) selected @endif>Produção</option>
                                        <option value="8" @if ($sector == 8) selected @endif>C.Q</option>
                                        <option value="9" @if ($sector == 9) selected @endif>SESMT</option>
                                        <option value="10" @if ($sector == 10) selected @endif>Laboratório</option>
                                        <option value="12" @if ($sector == 12) selected @endif>Enfermaria</option>
                                        <option value="13" @if ($sector == 13) selected @endif>Assistência Técnica</option>
                                        <option value="14" @if ($sector == 14) selected @endif>Expedição</option>
                                        <option value="15" @if ($sector == 15) selected @endif>Comercial</option>
                                        <option value="16" @if ($sector == 16) selected @endif>Importação</option>
                                        <option value="17" @if ($sector == 17) selected @endif>Compras</option>
                                        <option value="18" @if ($sector == 18) selected @endif>Marketing</option>
                                        <option value="19" @if ($sector == 19) selected @endif>Manutenção</option>
                                        <option value="20" @if ($sector == 20) selected @endif>Jurídico</option>
                                        <option value="21" @if ($sector == 21) selected @endif>Financeiro</option>
                                        <option value="22" @if ($sector == 22) selected @endif>Engenharia</option>
                                        <option value="23" @if ($sector == 23) selected @endif>Administração</option>
                                        <option value="24" @if ($sector == 24) selected @endif>S.A.C</option>
                                        <option value="25" @if ($sector == 25) selected @endif>P&D</option>
										<option value="26" @if ($sector == 26) selected @endif>TI</option>
                                    </select>
                                </div>    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ext_phone">Ramal</label>
                                    <input type="number" class="form-control" id="ext_phone" value="<?= $ext_phone ?>" name="ext_phone" required>
                                </div>    
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="access_comp">Login de acesso ao computador</label>
                                    <input type="text" class="form-control" id="access_comp" value="<?= $access_comp ?>" name="access_comp" required>
                                </div>    
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="subject">Título</label>
                                    <input type="text" class="form-control" id="subject" value="<?= $subject ?>" name="subject" placeholder="..." required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="message">Descrição do atendimento</label>
                                    <textarea id="message" rows="10" class="form-control" name="message" required><?= $message ?></textarea>
                                    <div class="form-text text-muted">Informe a descrição completa do seu atendimento.</div>
                                </div>
                                <div class="form-group">
                                    <label for="attach">Anexo</label>
                                    <input type="file" class="form-control" name="attach" id="attach">
                                    <div class="form-text text-muted">Caso precise anexar um arquivo.</div>
                                </div>
                                <?php if (!empty($attach)) { ?>
                                <div class="form-group">
                                    <a href="<?= $attach ?>" target="_blank" class="text-primary font-weight-bold">Anexo</a>
                                </div>
                                <?php } ?>
                            </div>
								
							<div class="col-md-12 warning-priority" style="display:none;"> 
                                <div class="alert border-danger mb-2" role="alert">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bx bx-error"></i>
                                        <span><b class="title-priority"></b></span>
                                    </div>
                                    <span class="ml-2">* Fiquem atentos as atualizações dos chamados.</span><br>
                                    <span class="ml-2">* Solicitações relacionadas a estrutura física e predial, “Câmeras, cabeamento de rede e telefonia,” sujeito a análise.</span><br>
                                    <span class="ml-2">* Após a abertura do chamado Favor respeitar o tempo de atendimento.</span>
                                </div>
                            </div>
							
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%;"><?php if ($id == 0) { ?>Criar Atendimento<?php } else { ?>Atualizar Atendimento<?php } ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>    
    $(document).ready(function () {

        $(".js-select23").select2({
            maximumSelectionLength: 1,
        });
		
		$("#category").select2({
            maximumSelectionLength: 1,
        });

        $("#category").change(function(){
            if($(this).val() == 2) {
                $("#row_toner_model").css('display', 'block');
                $("#toner_model").attr("required", true);

                $("#row_printer_model").css('display', 'block');
                $("#printer_model").attr("required", true);
            }
            else if($(this).val() == 5) {
                $("#row_reserve").css('display', '');
                //$("#start_reserve").attr("required", '');
                //$("#final_reserve").attr("required", '');
            }
			else if($(this).val() == 13) {
                $("#row_printer_model").css('display', 'block');
                $("#printer_model").attr("required", true);
            }
            else {
                $("#row_printer_model").css('display', 'none');
                $("#printer_model").attr("required", false);

                $("#row_toner_model").css('display', 'none');
                $("#toner_model").attr("required", false);

                $("#row_reserve").css('display', 'none');
                $("#start_reserve").attr("required", false);
                $("#final_reserve").attr("required", false);
            }
			
			var priority = $('option:selected',this).attr("priority");
            $("#priority").val(priority);
            if(priority != 0) {
                if(priority == 1) {
                    $(".title-priority").text('Prioridade Baixa: Até 72 horas úteis');
                } 
                else if(priority == 2) {    
                    $(".title-priority").text('Prioridade Média: Até 48 horas úteis');
                }
                else {
                    $(".title-priority").text('Prioridade Alta: Até 24 horas úteis');
                }
                $(".warning-priority").show();
            } else {
                $(".warning-priority").hide();
            }
			
        });

        $("#sendForm").submit(function (e) { 

            if($('#row_reserve').is(':visible') && $("#start_reserve").val() == "") {
                e.preventDefault();
                return $error('Selecione o início da reserva!');
            }
            else if($('#row_reserve').is(':visible') && $("#final_reserve").val() == "") {
                e.preventDefault();
                return $error('Selecione o final da reserva!');
            }
			
			if ($('#subject').val() == "") {
				e.preventDefault();
				
				return $error('Você precisa preencher o assunto!');
			} else if ($('#message').val() == "") {
				e.preventDefault();
				
				return $error('Você precisa preencher a descrição do atendimento!');
				
			} else if ($('#category').val() == "") {

                e.preventDefault();
				return $error('Você precisa preencher selecionar o tipo de atendimento!');
            }
            block();
        });

        $('.date-mask').pickadate({
            //editable: true,
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

        setInterval(() => {
            $("#mTI").addClass('sidebar-group-active active');
            $("#mTIMaintenance").addClass('sidebar-group-active active');
            $("#mTIMaintenanceNew").addClass('active');
        }, 100);
    });
</script>
@endsection
