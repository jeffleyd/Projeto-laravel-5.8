<style>
.td-title {
    background-color: #f6f6f6;
}
.td-text-white {
    color: white;
}
.td-color-black {
    background-color: #000;
}
.td-color-red {
    background-color: rgb(145, 0, 0);
}
.td-bold {
    font-weight: 600;
}
.td-text-center {
    text-align: center;
}
.td-font-11 {
    font-size: 6px;
}
.td-font-13 {
    font-size: 8px;
}
.td-font-14 {
    font-size: 8px;
}
.td-font-17 {
    font-size: 13px;
}
.table>tbody>tr>td {
    padding: 4px;
    vertical-align: inherit;
    border: 1px solid #ddd;
}
.table-bordered > tbody > tr > td {
    border: 1px solid #bbb;
}
table {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    border-spacing: 0;
}
.spacer-20 {
    margin-top: 20px;
}
.spacer-30 {
    margin-top: 30px;
}
.spacer-50 {
    margin-top: 50px;
}
.row {
    width: 800px;
    margin: auto;
    zoom: 1.5;
}

.checkbtn {
    position: relative;
    bottom: 4px;
}

.border-alert {
    background: #ffd4d1 !important;
}

@media print {
    .row {
        zoom: 1;
    }
}
</style>
<div class="row" style="width: 800px; margin: auto">
    <div class="col-sm-12">
		@if ($order->is_cancelled == 1)
		<img src="https://gree-app.com.br/gdb_request_negate_cancel.png" style="position: absolute;width: 800px;">
		@elseif ($order->is_reprov == 1)
		<img src="https://gree-app.com.br/gdb_request_negate_reprov.png" style="position: absolute;width: 800px;">
		@endif
		@if ($order->is_approv == 1)
		<div style="width:800px;margin: auto;position: absolute;">
        	<img src="https://gree-app.com.br/commercial/order_approv.png" style="position: absolute;right: 140px;width: 150px;bottom: 45px;">
        <div>
		@endif
        {{-- são 7 linhas --}}
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td rowspan="4" style="text-align: center;">
                        <img src="https://gree-app.com.br/media/logo.png" height="30" alt="" style="padding: 2px;">
                    </td>
                    <td colspan="8" rowspan="3" class="td-text-center td-font-17 td-bold">
                       PEDIDO DE VENDAS
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="td-font-13">
                        <b>ID:</b> {{$header->where('command', 'order_id_rev')->first()->value}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="td-font-13">
                        <b>REV:</b> {{$header->where('command', 'order_date_rev')->first()->value}}
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="td-font-13 td-text-white td-text-center td-color-red td-bold">
                        Em atendimento ao Procedimento PCM-{{$header->where('command', 'order_pcm_rev')->first()->value}}
                    </td>
                    <td class="td-font-13">
                        <b>FL:</b> {{$header->where('command', 'order_qtd_paper')->first()->value}}
                    </td>
                    <td class="td-font-13">
                        <b>Rev:</b> {{$header->where('command', 'order_number_rev')->first()->value}}
                    </td>
                </tr>
                <tr>
                    <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                        DADOS DO CLIENTE
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Tipo do pedido:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        NÃO PROGRAMADO
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Data do Pedido:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        {{date('d/m/Y', strtotime($order->created_at))}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Número do Pedido:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="4">
                        {{$order->code}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Tipo do Cliente:
                    </td>
					 @php 
						$table_obj = commercialTablePriceConvertValue($table);
                     @endphp
                    <td class="td-font-14 td-text-center" colspan="2">
                        {{$table_obj->type_client}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Contrato/VPC:
                    </td>
					<td class="td-font-14 td-text-center" id="vpc_view" data-vpc="@if ($order->vpc_view) {{$order->vpc_view}} @else 0.00 @endif" colspan="3">
                        {{$table_obj->contract_vpc}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Cód. do cliente:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="2">
                        {{$order->code_client}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Razão Social:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="4">
                        {{$order->client_company_name}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Loja:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="3">
                        {{$order->client_shop}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Endereço:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        {{$order->client_address}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Telefone:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        {{$order->client_phone}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Bairro:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="4">
                        {{$order->client_district}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Cidade:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="3">
                        {{$order->client_city}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        UF:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="2">
                        {{$order->client_state}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Regime especial:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="4">
                        {{$order->client_especial_regime_icms_per_st}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        CNPJ:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="3">
                        {{$order->client_identity}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Inscrição Estadual:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="2">
                        {{$order->client_state_registration}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Inscrição Suframa*:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        {{$order->client_suframa_registration}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        E-mail envio NF-e*:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        {{$order->client_email_financy_nfe}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Prazo de pagamento:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="3">
                        {{$order->date_payment}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Tipo de pagamento:
                    </td>
                    <td class="td-font-11 td-text-center" colspan="3">
                        <div class="inline-labels">
                            <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="1" name="type_payment" value="0" @if ($order->type_payment == 1) checked="" @endif><span></span> <span class="td-font-11 checkbtn">DDF</span></label>
                            <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="0" name="type_payment" value="1" @if ($order->type_payment == 2) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">DDE</span></label>
                        </div>
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Forma de pagamento:
                    </td>
                    <td class="td-font-11 td-text-center" colspan="3">
                        <div class="inline-labels">
                            <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="1" name="form_payment" value="0" @if ($order->form_payment == 1) checked="" @endif><span></span> <span class="td-font-11 checkbtn">BOL</span></label>
                            <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="0" name="form_payment" value="1" @if ($order->form_payment == 2) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">DEP</span></label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Telefone:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        {{$order->client_peoples_contact_buyer_phone}}
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Contato:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        {{$order->client_peoples_contact_buyer_contact}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Modalidade Frete:
                    </td>
                    <td class="td-font-11 td-text-center" colspan="5">
                        <div class="inline-labels">
                            <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="1" name="type_shpping" value="0" @if ($order->cif_fob == 0) checked="" @endif><span></span> <span class="td-font-11 checkbtn">CIF</span></label>
                            <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="0" name="type_shpping" value="1" @if ($order->cif_fob != 0) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">FOB</span></label>
                        </div>
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Nome do transportador:
                    </td>
                    <td class="td-font-11 td-text-center" colspan="5">
                        {{$order->name_transport}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Representante:
                    </td>
                    <td class="td-font-14 td-text-center colhigh-8" colspan="5">
                         {{$order->salesman->full_name}}
                    </td>
                    <td class="td-title td-font-14 td-bold colnone">
                        Comissão:
                    </td>
                    <td class="td-font-14 td-text-center @if($order->commission != $order->client->commission) border-alert @endif colnone" colspan="2">
                        {{$order->commission}}%
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Data de Faturamento:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="2">
                        {{date('d/m/Y', strtotime($order->date_invoice))}}
                    </td>
                </tr>
                <tr>
                    <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                        ENDEREÇO PARA ENTREGA (SÓ INSERIR DADOS SE O ENDEREÇO DE ENTREGA FOR DIFERENTE DO DE FATURAMENTO)
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Endereço:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        @if ($order->orderDelivery)
                        {{$order->orderDelivery->address}}
                        @endif
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Telefone:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="5">
                        @if ($order->orderDelivery)
                        {{$order->orderDelivery->phone}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        Bairro:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="4">
                        @if ($order->orderDelivery)
                        {{$order->orderDelivery->district}}
                        @endif
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Cidade:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="3">
                        @if ($order->orderDelivery)
                        {{$order->orderDelivery->city}}
                        @endif
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        UF:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="2">
                        @if ($order->orderDelivery)
                        {{$order->orderDelivery->state}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold">
                        CEP:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="4">
                        @if ($order->orderDelivery)
                        {{$order->orderDelivery->zipcode}}
                        @endif
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        CNPJ:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="3">
                        @if ($order->orderDelivery)
                        {{$order->orderDelivery->identity}}
                        @endif
                    </td>
                    <td class="td-title td-font-14 td-bold">
                        Inscrição Estadual:
                    </td>
                    <td class="td-font-14 td-text-center" colspan="2">
                        @if ($order->orderDelivery)
                        {{$order->orderDelivery->state_registration}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold" style="line-height: 1.5">
                        PREENCHER CASO A MODALIDADE DE FRETE SEJA CIF:
                        <br>PROCEDIMENTO DO CLIENTE - RECEBIMENTO DE MERCADORIAS
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-bold td-text-center" colspan="3">
                        RECEBIMENTO:
                    </td>
                    <td class="td-title td-font-14 td-bold td-text-center" colspan="3">
                        DIAS E HORÁRIOS DE RECEBIMENTO:
                    </td>
                    <td class="td-title td-font-14 td-bold td-text-center" colspan="3">
                        AGENDAR COM:
                    </td>
                    <td class="td-title td-font-14 td-bold td-text-center" colspan="3">
                        DESCARGA:
                    </td>
                </tr>
                <tr>
                    <td class="td-font-14" colspan="3">
                        <label><input type="radio" data-is-checked="1" name="receiver" value="0" @if($order->orderReceiver) @if($order->orderReceiver->type_receiver == 1) checked="" @endif @endif><span></span> <span class="td-font-11 checkbtn">ORDEM DE CHEGADA</span></label>
                        <br><label><input type="radio" data-is-checked="0" name="receiver" value="1" @if($order->orderReceiver) @if($order->orderReceiver->type_receiver == 2) checked="" @endif @endif><span></span>  <span class="td-font-11 checkbtn">AGENDADA</span></label>
                    </td>
                    <td class="td-font-14" colspan="3">
                        <div class="float-left" style="display: inherit;">
                            <label><input type="radio" data-is-checked="1" name="day_receiver" value="0" @if($order->orderReceiver) @if($order->orderReceiver->type_day_receiver == 1) checked="" @endif @endif><span></span> <span class="td-font-11 checkbtn">SEGUNDA A SEXTA</span></label>
                            <br><label><input type="radio" data-is-checked="0" name="day_receiver" value="1" @if($order->orderReceiver) @if($order->orderReceiver->type_day_receiver == 2) checked="" @endif @endif><span></span>  <span class="td-font-11 checkbtn">SEGUNDA A SÁBADO</span></label>
                            <br><label><input type="radio" data-is-checked="0" name="day_receiver" value="1" @if($order->orderReceiver) @if($order->orderReceiver->type_day_receiver == 3) checked="" @endif @endif><span></span>  <span class="td-font-11 checkbtn">24 HORAS</span></label>
                        </div>
                        <div class="float-right" style="width: 110px;display: inherit;text-align: right;line-height: 1.7;">
                            <b>Horários</b>
                            <br>
                            Seg à Sex: @if($order->orderReceiver) @if($order->orderReceiver->type_day_receiver != 3) {{date('H:s', strtotime($order->orderReceiver->monday_friday_hour_start))}} - {{date('H:s', strtotime($order->orderReceiver->monday_friday_hour_end))}} @else N/A @endif @else N/A @endif
                            <br>Sáb: @if($order->orderReceiver) @if($order->orderReceiver->type_day_receiver == 2) {{date('H:s', strtotime($order->orderReceiver->saturday_hour_start))}} - {{date('H:s', strtotime($order->orderReceiver->saturday_hour_end))}} @else N/A @endif @else N/A @endif
                        </div>
                    </td>
                    <td class="td-font-11" colspan="3" style="padding: 0;">
                        <div style="border-bottom: solid 1px #bbb;padding: 5px;">
                            <b>Pessoa:</b> @if($order->orderReceiver) {{$order->orderReceiver->apm_name}} @endif
                        </div>
                        <div style="border-bottom: solid 1px #bbb;padding: 5px;">
                            <b>Telefone:</b> @if($order->orderReceiver) {{$order->orderReceiver->apm_phone}} @endif
                        </div>
                        <div style="padding: 5px;">
                            <b>Email:</b> @if($order->orderReceiver) {{$order->orderReceiver->apm_email}} @endif
                        </div>
                    </td>
                    <td class="td-font-14" colspan="3">
                        <label><input type="radio" data-is-checked="1" name="output" value="0" @if($order->orderReceiver) @if($order->orderReceiver->transport == 1) checked="" @endif @endif><span></span> <span class="td-font-11 checkbtn">TRANSPORTADOR DA GREE</span></label>
                        <br><label><input type="radio" data-is-checked="0" name="output" value="1" @if($order->orderReceiver) @if($order->orderReceiver->transport == 2) checked="" @endif @endif><span></span>  <span class="td-font-11 checkbtn">EQUIPE DO CLIENTE</span></label>
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-text-center" colspan="9" rowspan="2">
                        CASO A DESCARGA SEJA "EQUIPE DO CLIENTE", INFORME O CUSTO POR CADA CARGA.
                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center" colspan="3">
                        CUSTO POR CARGA
                    </td>
                </tr>
                <tr>
                    <td class="td-font-14 td-text-center" colspan="3">
                        @if($order->orderReceiver) R$ {{number_format($order->orderReceiver->total, 2, ',', '.')}} @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                        PEDIDO
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-bold td-font-14 td-text-center">
                        Modelo
                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center">
                        Qtde
                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center" colspan="2">
                        Preço unitário
                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center" colspan="2" style="width: 70px;">
                        Valor Total
                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center">
                        Modelo
                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center">
                        Qtde
                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center" colspan="2">
                        Preço unitário
                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center" colspan="2">
                        Valor Total
                    </td>
                </tr>
                @php
                    $categories = collect(json_decode($order->json_categories_products));
                    $index = 0;
                    $uniq = $order->orderProducts->unique('category_id');
                    $line_loop = ceil($categories->count()/2);
                    $arr_column = [0 => [], 1=> []];
                    $pos = 0;
                    $total = 0.00;
                    $qtd_total = 0;
                @endphp

                @foreach($categories as $indx => $cat)
                    @if($pos == 0)
                        @php
                            $pos = 1;
                            $arr_column[0][] = $cat;
                        @endphp
                    @else
                        @php
                            $pos = 0;
                            $arr_column[1][] = $cat;
                        @endphp
                    @endif
                @endforeach
                @for($i = 0; $i < $line_loop; $i++)
                    @php $high_line_product = 1; @endphp
                    <tr>
                        @foreach($arr_column as $column)
                            <td class="td-title td-bold td-font-14 td-text-center" colspan="6">
                                @if (isset($column[$index]))
                                    {{$column[$index]->name}}
                                    @php
                                        $lprods = collect($column[$index]->set_product_on_group);
                                    @endphp

                                    @if (count($lprods) > $high_line_product)
                                        @php $high_line_product = count($lprods); @endphp
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @php
                        $skip = 0;
                    @endphp
                    @for ($l = 0; $l < $high_line_product; $l++)
                    <tr>
                        @foreach($arr_column as $idx => $column)
                            @if (isset($column[$index]))
                                @php
                                $prods = collect($column[$index]->set_product_on_group);
                                $item = $prods->slice($skip)->first();
                                $qtd = 0;
                                $is_price_custom = 0;
                                $price_unit = 0.00;
                                $totalg = 0.00;
                                $descoint = 0;
                                if ($item)
                                    $orderProductf = $order->orderProducts->where('set_product_id', $item->id)->first();
                                else
                                    $orderProductf = null;

                                @endphp
                                @if ($orderProductf)
                                    @php
                                        $qtd = $orderProductf->quantity;
                                        $is_price_custom = $orderProductf->is_price_custom;
                                        $price_unit = $orderProductf->price_unit;
                                        $totalg = $orderProductf->total;
                                        $descoint = $orderProductf->descoint;
                                    @endphp
                                @endif
                                @if ($item)
                                    <td class="td-font-14 td-text-center">
                                        @if ($item->product_air_evap)
                                            @if (substr($item->product_air_evap->model, -2) == '/I' or substr($item->product_air_evap->model, -2) == '/O')
                                                {{substr($item->product_air_evap->model, 0, -2)}}
                                            @else
                                                {{$item->product_air_evap->model}}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="td-font-14 td-text-center">
                                        {{$qtd}}
                                    </td>
                                    <td class="td-font-14 td-text-center @if($is_price_custom == 1) border-alert @endif" colspan="2">
                                        R$ {{number_format($price_unit, 2, ',', '.')}}
                                    </td>
                                    <td class="td-font-14 td-text-center" colspan="2">
                                        @php $total = $total + (($totalg*$qtd) * (1-($descoint/100))); @endphp
                                        @php $qtd_total = $qtd_total + $qtd; @endphp
                                        R$ {{number_format(($totalg*$qtd) * (1-($descoint/100)), 2, ',', '.')}}
                                    </td>
                                @else
                                    <td class="td-font-14 td-text-center">
                                    </td>
                                    <td class="td-font-14 td-text-center">
                                    </td>
                                    <td class="td-font-14 td-text-center" colspan="2">
                                    </td>
                                    <td class="td-font-14 td-text-center" colspan="2">
                                    </td>
                                @endif
                            @else
                            <td class="td-font-14 td-text-center">
                            </td>
                            <td class="td-font-14 td-text-center">
                            </td>
                            <td class="td-font-14 td-text-center" colspan="2">
                            </td>
                            <td class="td-font-14 td-text-center" colspan="2">
                            </td>
                            @endif
                        @endforeach
                    </tr>
                        @php
                            $skip++;
                        @endphp
                    @endfor
                    @php
                        $index++;
                    @endphp
                @endfor
                <tr>
                    <td class="td-font-14 td-text-center">
                    </td>
                    <td class="td-font-14 td-text-center">

                    </td>
                    <td class="td-font-14 td-text-center" colspan="2">

                    </td>
                    <td class="td-font-14 td-text-center">

                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center" colspan="4">
                        TOTAL GERAL DO PEDIDO
                    </td>
                    <td class="td-bold td-font-14 td-text-center" colspan="3">
                        R$ {{number_format($total, 2, ',', '.')}}
                    </td>
                </tr>
                <tr>
                    <td class="td-font-14 td-text-center">
                    </td>
                    <td class="td-font-14 td-text-center">

                    </td>
                    <td class="td-font-14 td-text-center" colspan="2">

                    </td>
                    <td class="td-font-14 td-text-center">

                    </td>
                    <td class="td-title td-bold td-font-14 td-text-center" colspan="4">
                        QUANTIDADE TOTAL
                    </td>
                    <td class="td-bold td-font-14 td-text-center" colspan="3">
                        {{$qtd_total}}
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-bold td-font-14 td-text-center" colspan="8">
                        Este número/informação deverá constar no corpo da nota fiscal:
                    </td>
                    <td class="td-bold td-font-14 td-text-center" colspan="4">
                        {{$order->control_client}}
                    </td>
                </tr>
				<tr>
                    <td class="td-font-14 " colspan="12">
							@php
							$mng_client = $order->client->client_managers->first();
							@endphp
							Emails para recebimento da NFe: <b>{{$order->salesman->email}}, comercialinterno@gree-am.com.br, 
							@if ($mng_client)
							{{$mng_client->salesman->email}}
							@endif</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                        OBSERVAÇÕES
                    </td>
                </tr>
                <tr>
                    <td colspan="12" class="td-font-14 td-text-center">
                        <div style="height: 50px;display: flex;justify-content: center;flex-direction: column;white-space: pre-line;">
                            {{$order->observation}}
							@if ($order->has_apply_discount)
								@php
									$arr_state = ['RR', 'AC', 'RO', 'AP'];
								@endphp
								@if ($order->state_invoice == 'AM')
									<br>
									@if ($order->has_apply_discount == 1)
										INCLUSO ICMS-ST DE 2,8%.
									@else
										NÃO INCLUSO ICMS-ST DE 2,8%.
									@endif
								@elseif (in_array($order->state_invoice, $arr_state))
									<br>
									@if ($order->has_apply_discount == 1)
										INCLUSO ICMS DE INCENTIVO DA ÁREA DE 12%
									@else
										NÃO INCLUSO ICMS DE INCENTIVO DA ÁREA DE 12%
									@endif
								@endif
							@endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-title td-font-14 td-text-center" colspan="3">
                        Cliente/Representante
                    </td>
                    <td class="td-title td-font-14 td-text-center" colspan="3">
                        Gerente Comercial
                    </td>
                    <td class="td-title td-font-14 td-text-center" colspan="3">
                        Responsável Comercial
                    </td>
                    <td class="td-title td-font-14 td-text-center" colspan="3">
                        Financeiro
                    </td>
                </tr>
                <tr>
                    <td class="td-font-14 td-text-center" colspan="3">
                        <div class="spacer-30"></div>
                    </td>
                    <td class="td-font-14 td-text-center" colspan="3">
                        @if ($order->orderImdAnalyze->count())
                            {{$order->orderImdAnalyze->sortByDesc('created_at')->first()->salesman->short_name}}
							@if ($order->orderImdAnalyze->sortByDesc('created_at')->first()->is_reprov == 1)
							<br><b>(REPROVADO)</b>
							@endif
                        @else
                            <div class="spacer-30"></div>
                        @endif
                    </td>
                    <td class="td-font-14 td-text-center" colspan="3">
                        @if ($order->orderCommercialAnalyze)
                            {{$order->orderCommercialAnalyze->user->short_name}}
							@if ($order->orderCommercialAnalyze->is_reprov == 1)
							<br><b>(REPROVADO)</b>
							@endif
                        @else
                            <div class="spacer-30"></div>
                        @endif
                    </td>
                    <td class="td-font-14 td-text-center" colspan="3">
                        @if ($order->orderFinancyAnalyze)
                            {{$order->orderFinancyAnalyze->user->short_name}}
							@if ($order->orderFinancyAnalyze->is_reprov == 1)
							<br><b>(REPROVADO)</b>
							@endif
                        @else
                            <div class="spacer-30"></div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="td-font-14 td-title" colspan="3">
                        <span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">_____/_____/_____</span>
                    </td>
                    <td class="td-font-14 td-title" colspan="3">
                        @if ($order->orderImdAnalyze->count())
						<span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">{{date('d/m/Y', strtotime($order->orderImdAnalyze->sortByDesc('created_at')->first()->created_at))}}</span>
                        @else
                            <span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">_____/_____/_____</span>
                        @endif
                    </td>
                    <td class="td-font-14 td-title" colspan="3">
                        @if ($order->orderCommercialAnalyze)
						<span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">{{date('d/m/Y', strtotime($order->orderCommercialAnalyze->created_at))}}</span>
                        @else
                            <span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">_____/_____/_____</span>
                        @endif
                    </td>
                    <td class="td-font-14 td-title" colspan="3">
                        @if ($order->orderFinancyAnalyze)
						<span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">{{date('d/m/Y', strtotime($order->orderFinancyAnalyze->created_at))}}</span>
                        @else
                            <span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">_____/_____/_____</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="spacer-50"></div>
    </div>
</div>
<script src="/admin/app-assets/js/jquery-3.4.1.min.js"></script>
<script src="/js/printThis.js"></script>
<script>
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };
    $(document).ready(function () {
        var chide = getUrlParameter('chide');
        if (chide == 'true') {
            $('.colnone').hide();
            $('.colhigh-8').attr('colspan', '8');
			var vpc = $('#vpc_view').attr('data-vpc');
			if (vpc != "0.00") {
				vpc = vpc.replace(/\s/g, '');
				$('#vpc_view').html(vpc+'%');
			}
        }
        $("#client").addClass('page-arrow active-page');

        $('input[type="checkbox"], input[type="radio"]').click(function (e) {

            location.reload();
        });
    });
</script>
