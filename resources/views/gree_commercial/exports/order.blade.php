<table class="table table-bordered">
    <tr>
        <td style="font-weight: bold">Data do evento</td>
		<td style="font-weight: bold">Data de faturamento</td>	
        <td style="font-weight: bold">Código Parceiro</td>
        <td style="font-weight: bold">Cliente</td>
		<td style="font-weight: bold">Loja</td>
        <td style="font-weight: bold">CNPJ / CPF</td>
        <td style="font-weight: bold">Cidade</td>
        <td style="font-weight: bold">Estado</td>
        <td style="font-weight: bold">Pedido</td>
        <td style="font-weight: bold">OC</td>
        <td style="font-weight: bold">Sigla</td>
        <td style="font-weight: bold">Modelo</td>
        <td style="font-weight: bold">R$ Unit</td>
        <td style="font-weight: bold">QTD Pedido</td>
        <td style="font-weight: bold">Cubagem m³</td>
        <td style="font-weight: bold">QTD Faturado</td>
        <td style="font-weight: bold">Mês</td>
        <td style="font-weight: bold">Ano</td>
        <td style="font-weight: bold">Frete</td>
        <td style="font-weight: bold">VPC</td>
        <td style="font-weight: bold">Prazo de pagamento</td>
        <td style="font-weight: bold">Representante</td>
        <td style="font-weight: bold">Gestor</td>
        <td style="font-weight: bold">Comissão</td>
        <td style="font-weight: bold">Status pedido</td>
    </tr>
    @foreach($orders as $order)
        @php
            $table = json_decode($order->json_table_price);
        @endphp
        @foreach($order->orderProducts as $prod)
            <tr>
            <td style="text-align: center">{{date('d/m/Y', strtotime($order->created_at))}}</td>    
			<td style="text-align: center">{{date('d/m/Y', strtotime($order->date_invoice))}}</td>    
            <td style="text-align: center">{{$order->client->code}}</td>
            <td style="text-align: center">{{$order->client->company_name}}</td>
			<td style="text-align: center">{{$order->client_shop}}</td>
            <td style="text-align: center">{{$order->client->identity}}</td>
            <td style="text-align: center">{{$order->client->city}}</td>
            <td style="text-align: center">{{$order->client->state}}</td>
            <td style="text-align: center">{{$order->code}}</td>
            <td style="text-align: center">{{$order->control_client}}</td>
            <td style="text-align: center">
                @if ($prod->setProduct)
                    {{$prod->setProduct->resume}}
                @endif
            </td>
            <td style="text-align: center">
                @if ($prod->setProduct)
                    @if ($prod->setProduct->productAirEvap)
                        @if (substr($prod->setProduct->productAirEvap->model, -2) == '/I' or substr($prod->setProduct->productAirEvap->model, -2) == '/O') {{substr($prod->setProduct->productAirEvap->model, 0, -2)}}
                        @else
                            {{$prod->setProduct->productAirEvap->model}}
                        @endif
                    @endif
                    {{$prod->setProduct->resume}}
                @endif
            </td>
            <td style="text-align: center">R$ {{number_format($prod->price_unit, 2, ',', '.')}}</td>
            <td style="text-align: center">{{$prod->quantity}}</td>
            <td style="text-align: center">
                @if ($prod->setProduct) 
                    @php $total_cubage = $prod->quantity * $prod->setProduct->calc_cubage; @endphp
                    {{ number_format($total_cubage, 2, ',', '.')}}
                @endif
            </td>
            
            <td style="text-align: center">0</td>
            @php $date = new \Carbon\Carbon($order->yearmonth); @endphp
            <td style="text-align: center">{{$date->locale('pt_BR')->isoFormat('MMMM')}}</td>
            <td style="text-align: center">{{$date->locale('pt_BR')->isoFormat('YYYY')}}</td>
            <td style="text-align: center">
                @php
                    $fob = [
                            26 => 'Manaus',
                            27 => 'RR/AC/RO/AP/PA',
                            28 => 'NORDESTE',
                            29 => 'SUDESTE',
                            30 => 'CENTROESTE',
                            31 => 'SUL',
                        ];
                @endphp
                @if ($order->cif_fob == 0)
                    CIF
                @else
                    FOB ({{$fob[$order->cif_fob]}})
                @endif
            </td>

            <td style="text-align: center">{{$order->contract_vpc}}%</td>
            <td style="text-align: center">{{$order->date_payment}}</td>
            <td style="text-align: center">
                @if ($order->client->salesman)
                {{$order->client->salesman->full_name}}
                @endif
            </td>
            <td style="text-align: center">
                @if ($order->client->client_managers->count() > 0)
                    {{$order->client->client_managers->first()->salesman->full_name}}
                @endif
            </td>
            <td style="text-align: center">{{$order->commission}}%</td>
            <td style="text-align: center">
                @if ($order->is_cancelled == 1)
                    Cancelado
                @elseif ($order->salesman_imdt_approv == 1 and $order->commercial_is_approv == 1 and $order->financy_approv == 1 and $order->is_invoice == 0)
                    Aprovado
                @elseif ($order->is_invoice == 1)
                    Faturado
                @elseif ($order->salesman_imdt_reprov == 1)
                    Reprovado 
                @elseif ($order->commercial_is_reprov == 1)
                    Reprovado
                @elseif ($order->financy_reprov == 1)
                    Reprovado
                @elseif ($order->waiting_assign == 1)
                    Aguardando Comprovação
                @elseif ($order->has_analyze == 1)
                    Em análise
                @else
                    Não enviado
                @endif    
            </td>
        </tr>
        @endforeach
    @endforeach
</table>
