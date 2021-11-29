<table>
    <thead>
        <tr>
            <td style="background-color: #0070C0;color: white; width:15px;">Código</td>
            <td style="background-color: #0070C0;color: white; width:30px;">Solicitante</td>
            <td style="background-color: #0070C0;color: white; width:30px;">Razão</td>
            <td style="background-color: #0070C0;color: white; width:30px;">Visitante</td>
            <td style="background-color: #0070C0;color: white; width:30px;">Empresa</td>
            <td style="background-color: #0070C0;color: white; width:10px;">Motivo</td>
            <td style="background-color: #0070C0;color: white; width:10px;">Status</td>
        </tr> 
    </thead>    
    <tbody>
        @foreach ($entry_exit as $key)
            <tr>
                <td style="border-bottom: 1px solid black; border-top: 1px solid black; background-color: #C5D9F1;">{{ $key->code }}</td>
                <td style="border-bottom: 1px solid black; border-top: 1px solid black; background-color: #C5D9F1;"><?= $key->request_user->full_name ?></td>
                <td style="border-bottom: 1px solid black; border-top: 1px solid black; background-color: #C5D9F1;">
                    @if($key->type_reason == 3)
                        Visita
                    @elseif($key->type_reason == 9)
                        Prestador de serviço
                    @elseif($key->type_reason == 10)
                        Seleção p/ contratação
                    @endif
                </td>
                <td style="border-bottom: 1px solid black; border-top: 1px solid black; background-color: #C5D9F1">{{ $key->logistics_entry_exit_visit->name }}</td>
                <td style="border-bottom: 1px solid black; border-top: 1px solid black; background-color: #C5D9F1">{{ $key->logistics_entry_exit_visit->company_name }}</td>
                <td style="border-bottom: 1px solid black; border-top: 1px solid black; background-color: #C5D9F1"><?= $key->reason ?></td>
                <td style="border-bottom: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; background-color: #C5D9F1;">
                    @if ($key->is_reprov)
                        Reprovado
                    @elseif ($key->is_cancelled)
                        Cancelado
                    @elseif ($key->has_analyze)
                        Em análise
                    @elseif ($key->is_approv)
                        Aprovado
                    @else
                        Rascunho
                    @endif
                </td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid black; font-weight: bold; background-color: #F2F2F2;">Tipo</th>
                <th style="border-bottom: 1px solid black; font-weight: bold; background-color: #F2F2F2;">Data Liberação</th>
                <th style="border-bottom: 1px solid black; font-weight: bold; background-color: #F2F2F2;">Restrição</th>
                <th style="border-bottom: 1px solid black; font-weight: bold; background-color: #F2F2F2;">Encaminhamento</th>
                <th style="border-bottom: 1px solid black; font-weight: bold; border-right: 1px solid black; background-color: #F2F2F2;" colspan="3">Status</th>
            </tr>
            @php $last = $key->logistics_entry_exit_requests_schedule->count() - 1 @endphp
            @foreach ($key->logistics_entry_exit_requests_schedule as $i => $schedule)
            <tr>
                <td style="@if($last == $i)border-bottom: 1px solid black; @endif background-color: #F2F2F2;">
                    @if($schedule->is_entry_exit == 1)
                        Entrada
                    @else    
                        Saída
                    @endif
                </td>
                <td style="@if($last == $i) border-bottom: 1px solid black; @endif background-color: #F2F2F2;">{{ date('d/m/Y H:i', strtotime($schedule->date_hour)) }}</td>
                <td style="@if($last == $i) border-bottom: 1px solid black; @endif background-color: #F2F2F2;">{{ $schedule->entry_restriction }}</td>
                <td style="@if($last == $i) border-bottom: 1px solid black; @endif background-color: #F2F2F2;">{{ $schedule->request_forwarding }}</td>
                <td style="@if($last == $i) border-bottom: 1px solid black; @endif border-right: 1px solid black; background-color: #F2F2F2;" colspan="3">
                    @if($schedule->is_liberate == 1)
                        Liberado
                    @elseif($schedule->is_denied == 1)    
                        Negado
                    @else     
                        Aguard. Liberação
                    @endif
                </td>
            </tr>    
            @endforeach
            <tr>
                <td colspan="7"></td>
            </tr>    
        @endforeach
    </tbody>    
</table>
