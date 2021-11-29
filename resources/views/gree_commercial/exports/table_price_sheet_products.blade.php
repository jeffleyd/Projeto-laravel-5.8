<table>
    <tr>
        <td style="vertical-align: center; text-align: center;"><img height="50" src="{{public_path('media/logo.png')}}"></td>
        <td style="vertical-align: center; text-align: center; font-weight: bold; font-size: 16px" colspan="4">Proposta de Preços</td>
        <td style="vertical-align: center; text-align: center; font-weight: bold; font-size: 12px" colspan="4">Data {{date('d/m/Y')}}</td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    @php
        $style = 'color: black; font-weight: bold; background-color: #DDEBF7; border: 1px solid #000000; text-align: center; vertical-align: center; font-size: 10px';
        $style_month = 'color: black; font-weight: bold; background-color: #92D050; border: 4px solid #000000; text-align: center; vertical-align: center; font-size: 10px';
    @endphp
    <tr>
        <td></td>
        <td colspan="12"></td>
        <td></td>
        <td colspan="{{2*$pattern['months']->count()}}" style="color: black; border: 4px solid #000000; text-align: center; font-size: 10px; vertical-align: center;">@if ($pattern['table']->is_programmed == "Sim") Preços com programação @else Preços sem programação @endif</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" rowspan="2" style="{{$style}}">Descrição do Modelo</td>
        <td colspan="2" rowspan="2" style="{{$style}}">Unid</td>
        <td colspan="2" rowspan="2" style="{{$style}}">Código da unidade</td>
        <td colspan="2" rowspan="2" style="{{$style}}">Dimensões Produto <br> (CxLxA mm)</td>
        <td colspan="2" rowspan="2" style="{{$style}}">Dimensões CAIXA <br> (CxLxA mm)</td>
        <td colspan="2" rowspan="2" style="{{$style}}">NCM</td>
        <td></td>
        @foreach ($pattern['months'] as $d)
            @php $month = new \Carbon\Carbon($d->date); @endphp
        <td colspan="2" rowspan="2" style="{{$style_month}}">{{ucfirst($month->locale('pt_BR')->isoFormat('MMMM')) }}</td>
        @endforeach
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td colspan="12"></td>
        <td></td>
        <td colspan="{{2*$pattern['months']->count()}}"></td>
    </tr>
    @php
        $style_category = 'color: black; font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: center; font-size: 10px';
    @endphp
    @foreach ($pattern['products'] as $index => $category)
        <tr>
            <td></td>
            <td colspan="{{13 + (2*$pattern['months']->count())}}" style="background-color: black; color: white">{{$category->name}}</td>
        </tr>
        @foreach ($category->setProductOnGroup as $set)
            @php
                $style_top = 'border-top: 1px solid black; text-align:center;';
                $style_left = 'border-left: 1px solid black; text-align:center;';
                $style_right = 'border-right: 1px solid black; text-align:center;';
            @endphp
            <tr>
                <td></td>
                <td style="{{$style_top}}{{$style_left}}" colspan="2">
                    @if ($set->productAirEvap)
                    {{$set->productAirEvap->model}}
                    @endif
                </td>
                <td style="{{$style_top}}" colspan="2">
                    EVAP
                </td>
                <td style="{{$style_top}}" colspan="2">
                    @if ($set->productAirEvap)
                        {{$set->productAirEvap->code_unity}}
                    @endif
                </td>
                <td style="{{$style_top}}" colspan="2">
                    @if ($set->productAirEvap)
                        {{$set->productAirEvap->length}}x{{$set->productAirEvap->width}}x{{$set->productAirEvap->height}}
                    @endif
                </td>
                <td style="{{$style_top}}" colspan="2">
                    @if ($set->productAirEvap)
                        {{$set->productAirEvap->length_box}}x{{$set->productAirEvap->width_box}}x{{$set->productAirEvap->height_box}}
                    @endif
                </td>
                <td style="{{$style_top}}" colspan="2">
                    @if ($set->productAirEvap)
                        {{$set->productAirEvap->ncm}}
                    @endif
                </td>
                <td></td>
                @foreach ($pattern['months'] as $index_d => $d)
                    @php
                    if ($pattern['table']->is_programmed == "Sim") {
                        $price = $pattern['applyPrice']->calcPrice($set->price_base, $set, $d->date);
                    } else {
                        $price = $pattern['applyPrice']->calcPrice($set->price_base, $set, $d->date, FALSE);
                    }
                    @endphp
                    <td style="{{$style_top}} {{$style_right}}" colspan="2">
                        R$ {{number_format(($price * 35) / 100, '2', ',', '.')}}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td></td>
                <td style="{{$style_left}}" colspan="2">
                    @if ($set->productAirCond)
                        {{$set->productAirCond->model}}
                    @endif
                </td>
                <td style="text-align:center" colspan="2">
                    COND
                </td>
                <td style="text-align:center" colspan="2">
                    @if ($set->productAirCond)
                        {{$set->productAirCond->code_unity}}
                    @endif
                </td>
                <td style="text-align:center" colspan="2">
                    @if ($set->productAirCond)
                        {{$set->productAirCond->length}}x{{$set->productAirCond->width}}x{{$set->productAirCond->height}}
                    @endif
                </td>
                <td style="text-align:center" colspan="2">
                    @if ($set->productAirCond)
                        {{$set->productAirCond->length_box}}x{{$set->productAirCond->width_box}}x{{$set->productAirCond->height_box}}
                    @endif
                </td>
                <td style="text-align:center" colspan="2">
                    @if ($set->productAirCond)
                        {{$set->productAirCond->ncm}}
                    @endif
                </td>
                <td></td>
                @foreach ($pattern['months'] as $d)
                    @php
                        if ($pattern['table']->is_programmed == "Sim") {
                            $price = $pattern['applyPrice']->calcPrice($set->price_base, $set, $d->date);
                        } else {
                            $price = $pattern['applyPrice']->calcPrice($set->price_base, $set, $d->date, FALSE);
                        }
                    @endphp
                    <td style="{{$style_right}}" colspan="2">
                        R$ {{number_format(($price * 65) / 100, '2', ',', '.')}}
                    </td>
                @endforeach
            </tr>
            <tr>
                @php
                    $style = 'font-weight: bold; background-color: #D9E1F2; border-bottom: 1px solid black; text-align:center;';
                @endphp
                <td></td>
                <td style="{{$style}}{{$style_left}}" colspan="2">
                    {{$set->resume}}
                </td>
                <td style="{{$style}}" colspan="2">
                    CONJ
                </td>
                <td style="{{$style}}" colspan="2"></td>
                <td style="{{$style}}" colspan="2"></td>
                <td style="{{$style}}" colspan="2"></td>
                <td style="{{$style}}" colspan="2"></td>
                <td style="border-bottom: 1px solid black;"></td>
                @foreach ($pattern['months'] as $d)
                    @php
                        if ($pattern['table']->is_programmed == "Sim") {
                            $price = $pattern['applyPrice']->calcPrice($set->price_base, $set, $d->date);
                        } else {
                            $price = $pattern['applyPrice']->calcPrice($set->price_base, $set, $d->date, FALSE);
                        }
                    @endphp
                    <td style="{{$style}} {{$style_right}}" colspan="2">
                        R$ {{number_format($price, '2', ',', '.')}}
                    </td>
                @endforeach
            </tr>
        @endforeach
    @endforeach
</table>
