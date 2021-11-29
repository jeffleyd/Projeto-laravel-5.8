<script>

function reloadSingle(elem) {

    let data = JSON.parse($(elem).attr("json-data"));
	
	console.log(data);
	
	$("#rtd_analyze_id").val($(elem).attr("data-id"));
    position = $(elem).attr("data-position");

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
                        MOTORISTA / PEDESTRE
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
                FROTA:
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
                        <b>DATA:</b> ${getDateFormat(data.date_hour, true)} &nbsp;&nbsp;&nbsp;&nbsp;<b>HORA INICIAL:</b> ${data.date_hour_initial ? getHourFormat(data.date_hour_initial) : '-'}  &nbsp;&nbsp;&nbsp;&nbsp;<b>HORA FINAL:</b> ${getHourFormat(data.date_hour)}
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
        content = data.logistics_warehouse_type_content ? data.logistics_warehouse_type_content.description : '-';
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
                    <td class="td-font-14 td-text-center" colspan="2">
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
                    <td class="td-font-14 td-text-center" colspan="2">
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
                    <td class="td-font-14 td-text-center" colspan="2">
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
                    <td class="td-font-14 td-text-center" colspan="2">
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
    $('#requestPrint').modal();
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

</script>