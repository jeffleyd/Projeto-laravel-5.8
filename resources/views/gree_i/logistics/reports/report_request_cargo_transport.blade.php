<table style="text-align: center;">
    <thead>
        <tr>
            <th style="background-color: #0070C0;color: white; width:15px;">Código</th>
            <th style="background-color: #0070C0;color: white; width:28px;">Solicitante</th>
            <th style="background-color: #0070C0;color: white; width:25px;">Razão</th>
            <th style="background-color: #0070C0;color: white; width:20px;">Motivo</th>
            <th style="background-color: #0070C0;color: white; width:18px;">Data Liber.</th>
            <th style="background-color: #0070C0;color: white; width:10px;">Tipo</th>
            <th style="background-color: #0070C0;color: white; width:20px;">Portaria</th>
            <th style="background-color: #0070C0;color: white; width:20px;">Galpão</th>
            <th style="background-color: #0070C0;color: white; width:20px;">Transportadora</th>
            <th style="background-color: #0070C0;color: white; width:15px;">Placa Veículo</th>
            <th style="background-color: #0070C0;color: white; width:15px;">Placa Carreta</th>
            <th style="background-color: #0070C0;color: white; width:20px;">Motorista</th>
            <th style="background-color: #0070C0;color: white; width:15px;">Motorista Telefone</th>
            <th style="background-color: #0070C0;color: white; width:15px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($entry_exit as $key)
            <tr>
                <td>{{ $key->code }}</td>
                <td><?= $key->request_user->full_name ?></td>
                <td>
                    @if($key->type_reason == 1)
                        Entrega de compra
                    @elseif($key->type_reason == 2)
                        Carregamento 
                    @elseif($key->type_reason == 4)
                        Importação 
                    @elseif($key->type_reason == 5)
                        Transferência 
                    @elseif($key->type_reason == 6)
                        Retirada de venda     
                    @elseif($key->type_reason == 7)
                        Coleta  
                    @else
                        Entrega de avaria
                    @endif
                </td>
                <td><?= $key->reason ?></td>
                <td>{{ date('d/m/Y H:i', strtotime($key->date_hour)) }}</td>
                <td>
                    @if($key->is_entry_exit == 1)
                        Entrada
                    @else    
                        Saída
                    @endif
                </td>
                <td>{{ $key->logistics_entry_exit_gate->name ?? '' }}</td>
                <td>{{ $key->logistics_warehouse->name ?? '' }}</td>
                <td>{{ $key->logistics_transporter->name ?? '' }}</td>
                <td>{{ $key->logistics_transporter_vehicle->registration_plate ?? '' }}</td>
                <td>{{ $key->logistics_transporter_cart->registration_plate ?? '' }}</td>
                <td>{{ $key->logistics_transporter_driver->name ?? '' }}</td>
                <td>{{ $key->logistics_transporter_driver->phone ?? '' }}</td>
                <td>
                    @if ($key->is_reprov)
                        Reprovado
                    @elseif ($key->is_cancelled)
                        Cancelado
                    @elseif ($key->is_denied)
                        Negado
                    @elseif ($key->is_liberate)
                        Liberado
                    @elseif ($key->has_analyze)
                        Em análise
                    @elseif ($key->is_approv)
                        Aguard. Liberação
                    @else
                        Rascunho
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>