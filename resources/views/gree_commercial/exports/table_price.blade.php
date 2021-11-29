<table>
    <tr>
       <td style="vertical-align: center; text-align: center;"><img height="50" src="{{public_path('media/logo.png')}}"></td>
    </tr>
    @php $date = new \Carbon\Carbon(date('Y-m-d')); @endphp
    <tr>
        <td style="border: 2px solid #000000; background-color: #429BDB; font-size: 16px; font-weight: bold; vertical-align: center; text-align: left; color: #ffffff" rowspan="2" colspan="10">TABELA DE PREÇOS <?php if ($pattern['table']->is_programmed == 'Sim') { ?> (PROGRAMADO) <?php } else { ?> (NÃO PROGRAMADO) <?php } ?> - {{ ucfirst($date->locale('pt_BR')->isoFormat('MMMM')) }}/{{$date->locale('pt_BR')->isoFormat('YYYY')}}</td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    @php
    $style = 'color: black; font-weight: bold; background-color: #fff000; border: 1px solid #000000; text-align: center;';
    @endphp
    <tr>
        <td>TIPO DE CLIENTE</td><td colspan="4" style="{{$style}}">{{$pattern['table']->type_client}}</td>
    </tr>
    <tr>
        <td>DESCONTO EXTRA</td><td colspan="4" style="{{$style}}">{{$pattern['table']->descont_extra}}</td>
    </tr>
    <tr>
        <td>CARGA</td><td colspan="4" style="{{$style}}">{{$pattern['table']->charge}}</td>
    </tr>
    <tr>
        <td>CONTRATO/VPC</td><td colspan="4" style="{{$style}}">{{$pattern['table']->contract_vpc}}</td>
    </tr>
    <tr>
        <td>PRAZO MÉDIO</td><td colspan="4" style="{{$style}}">{{$pattern['table']->average_term}}</td>
    </tr>
    <tr>
        <td>PIS/COFINS</td><td colspan="4" style="{{$style}}">{{$pattern['table']->pis_confis}}</td>
    </tr>
    <tr>
        <td>ICMS</td><td colspan="4" style="{{$style}}">{{$pattern['table']->icms}}</td>
    </tr>
    <tr>
        <td>AJUSTE COMERCIAL</td><td colspan="4" style="{{$style}}">{{$pattern['table']->adjust_commercial}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td>FRETE</td><td colspan="4" style="{{$style}}">{{$pattern['table']->cif_fob}}</td>
    </tr>
    <tr></tr>
    @foreach ($pattern['products'] as $index => $category)
        @if ($index == 0)
            <tr>
                <td></td>
                <td colspan="4"></td>
                <td></td>
                <td></td>
                <td></td>
                <?php if ($pattern['table']->is_programmed == 'Sim') { ?>
                <td style="text-align: center; border: 1px solid #000000;" colspan="{{2*$pattern['months']->count()}}"><b>PREÇOS DE PEDIDO: PROGRAMADOS</b></td>
                <?php } else { ?>
                <td style="text-align: center; border: 1px solid #000000;" colspan="{{2*$pattern['months']->count()}}"><b>PREÇOS DE PEDIDO: NÃO PROGRAMADOS</b></td>
                <?php } ?>
            </tr>
        @endif
        <tr>
            <td></td>
            <td style="text-align: center;" colspan="4">{{$category->name}}</td>
            <td></td>
            <td></td>
            <td></td>
            @foreach ($pattern['months'] as $d)
                @php $month = new \Carbon\Carbon($d->date); @endphp
                <td colspan="2" @if ($index == 0) style="border: 2px solid #000000; background-color: #008000; text-align: center; font-weight: bold; color: #FFFFFF" @endif>@if ($index == 0)
                    {{ucfirst($month->locale('pt_BR')->isoFormat('MMMM')) }} @endif
                </td>
            @endforeach
        </tr>
        @foreach ($category->setProductOnGroup as $key)
            <tr>
                <td></td>
                <td style="text-align: center; border: 2px solid #000000; background-color: @if ($key->is_qf == 0) #89D8F8 @else #F8C3C3 @endif;" colspan="4">{{$key->resume}}</td>
                <td></td>
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: @if ($key->is_qf == 0) #89D8F8 @else #F8C3C3 @endif;">CONJ</td>
                <td></td>
                @foreach ($pattern['months'] as $d)
                    <?php
                        if ($pattern['table']->is_programmed == 'Sim') {
                            $price = $pattern['applyPrice']->calcPrice($key->price_base, $key, $d->date);
                        } else {
                            $price = $pattern['applyPrice']->calcPrice($key->price_base, $key, $d->date, FALSE);
                        }
                    ?>
                    <td colspan="2" style="border: 2px solid #000000; text-align: center; font-weight: bold; background-color: @if ($key->is_qf == 0) #89D8F8 @else #F8C3C3 @endif;">R$
                        {{number_format($price, '2', ',', '.')}}
                    </td>
                @endforeach
            </tr>
        @endforeach
        <tr></tr>
    @endforeach
</table>
