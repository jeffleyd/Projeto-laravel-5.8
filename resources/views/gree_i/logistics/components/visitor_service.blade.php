<script>

//Visualização solicitação Visitante/P.Serviço 
function reloadSingle(elem) {

    let data = JSON.parse($(elem).attr("json-data"));
    var requestsData = data;

    position = $(elem).attr("data-position");

    sel_id = data.id;
    $("#rtd_analyze_id").val(sel_id);

    var html = '';
    var charge = '';
	
	$('#request').html('');
    $('#charging').html('');
    
    var situation = {
        status: 'Aguardando liberação',
        reason: '',
        name: '',
        time: '',
    };

    var reason = '';
    if (requestsData.reason) {
        reason = 
        `<tr>
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

    var schedule = `
        <tr class="td-text-center" style="background-color: #ff6767;color:#fff;">
            <td colspan="3" class="td-bold">Tipo</td>
            <td colspan="3" class="td-bold">Data Liberação</td>
            <td colspan="3" class="td-bold">Restrição</td>
            <td colspan="3" class="td-bold">Encaminhamento</td>
        </tr>
    `;

    var entry_exit = {1 : 'ENTRADA', 2 : 'SAÍDA'};

    data.logistics_entry_exit_requests_schedule.forEach(function(item, index) {

        schedule += `
            <tr class="td-text-center">
                <td colspan="3">`+ entry_exit[item.is_entry_exit] +`</td>
                <td colspan="3">`+ moment(item.date_hour).format('DD/MM/YYYY - HH:mm') +`</td>
                <td colspan="3">`+ item.entry_restriction +`</td>
                <td colspan="3">`+ item.request_forwarding +`</td>
            </tr>
        `;
    });
    
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
                    <td class="td-title td-font-14 td-bold td-text-white td-color-red td-text-center" colspan="12">
                        AGENDAMENTOS
                    </td>
                </tr>
                ${schedule}
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

    var items = '';
	if (requestsData.logistics_entry_exit_requests_items.length) {
        requestsData.logistics_entry_exit_requests_items.forEach(function ($var) {
            items += `<tr>
                <td class="td-font-17" colspan="6" style="font-size: 16px;">
                    ${$var.description}
                </td>
                <td class="td-font-17" colspan="6" style="font-size: 16px;">
                    ${$var.quantity}
                </td>
            </tr>`
        });
    }
	
    charge = `<table class="table table-bordered table-view">
                <tbody>
                <tr>
                    <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                        CARREGAMENTO
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

        $('#request').html(html);
		$('#charging').html(charge);
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