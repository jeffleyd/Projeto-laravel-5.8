<table class="table table-bordered">
    @foreach($clients as $client)
    @php
        $colspan = 2;


        $consumer = \App\Model\Commercial\Client::find($client->id);

        $programation_macro = \App\Model\Commercial\ProgramationMacro::with('programation.client.manager_region')->whereHas('programation', function ($q) use ($client){
           $q->where('client_id', $client->id);
        });

        if (Request::get('year'))
            $programation_macro->whereRaw("YEAR(yearmonth) = '".Request::get('year')."'");
        else
            $programation_macro->whereRaw("YEAR(yearmonth) = '".date('Y')."'");

        $programation_macro = $programation_macro->get();

        $months = $programation_macro->unique('yearmonth')->pluck('yearmonth');

        $category_uniq = $cat_uniq->whereIn('id', $programation_macro->unique('category_id')->pluck('category_id')->toArray());
    @endphp

    @foreach($programation_macro as $pmacros)
    <tr>
        <td>{{$consumer->company_name}} - CPF/CNPJ: {{$consumer->identity}}</td>
        @php
        $product = $category->setProductOnGroup->where('id', $pmacros->set_product_id)->first();
        @endphp
        <td>
            @if ($product)
                @if ($product->productAirEvap)
                    @if (substr($product->productAirEvap->model, -2) == '/I' or substr($product->productAirEvap->model, -2) == '/O') {{substr($product->productAirEvap->model, 0, -2)}}
                    @else
                    {{$set['product_air_evap']['model']}}
                    @endif
                @endif
            @endif
        </td>
        <td>
            @if (Request::get('is_total') == 1)
                {{$pmacros->total}}
            @else
                {{$pmacros->quantity}}
            @endif
        </td>
        <td>R$ {{number_format($pmacros->price, 2, ',', '.')}}</td>
        @php $date = new \Carbon\Carbon($pmacros->yearmonth); @endphp
        <td>{{$date->locale('pt_BR')->isoFormat('MMMM')}} {{$date->locale('pt_BR')->isoFormat('YYYY')}}</td>
        <td>{{$pmacros->programation->client->manager_region->full_name}}</td>
    </tr>
    @endforeach

    <tr class="table-active">
        <td colspan="2" rowspan="2" style="background: white;border: none; text-align: center">
        </td>
        @foreach ($months as $value)

        @endforeach
    </tr>
    <tr class="table-active">
        @foreach ($months as $value)
            <td style="text-align: center; font-weight: bold">Qty</td>
            <td colspan="2" style="text-align: center; font-weight: bold">Preço</td>
        @endforeach
    </tr>

    <tr style="background: black;color: white;">
        @foreach ($months as $value)
            @php
                $colspan = $colspan + 3;
            @endphp
        @endforeach
        <td style="text-align: center; background-color: black; color: white;" colspan="{{$colspan}}">{{$consumer->company_name}} - CPF/CNPJ: {{$consumer->identity}}</td>
    </tr>
    @php
        $line_exists = [];
        $line_jump = false;
    @endphp
    @foreach ($category as $value)
        <tr class="table-primary">
            <td style="text-align: center; background-color: indianred; color: white" colspan="2">{{$value['name']}}</td>
            @foreach ($months as $d)
                @php
                    $t_qtd = $programation_macro->where('yearmonth', $d)->where('category_id', $value['id'])->sum('quantity');
                    $t_price = $programation_macro->where('yearmonth', $d)->where('category_id', $value['id'])->sum('price');
                @endphp
                <td style="text-align: center; background-color: indianred; color: white">{{$t_qtd}}</td>
                <td colspan="2" style="text-align: center; background-color: indianred; color: white">R$ {{number_format($t_price, 2, ',', '.')}}</td>
            @endforeach
        </tr>
        @foreach ($category_uniq as $catfill)
            @foreach ($value['set_product_on_group'] as $set)
                @foreach ($catfill->setProductOnGroup->whereIn('id', $programation_macro->where('category_id', $catfill->id)->unique('set_product_id')->pluck('set_product_id')->toArray())->toArray() as $prodfill)
                    @if ($prodfill['id'] == $set['id'])
                        @if (isset($line_exists[$catfill['id']]))
                            @foreach($line_exists[$catfill['id']] as $prodts)
                                @if ($prodts == $prodfill['id'])
                                    @php $line_jump = true; @endphp
                                    @break
                                @else
                                    @php
                                        $line_exists[$catfill['id']] = [];
                                        array_push($line_exists[$catfill['id']], $prodfill['id']);
                                        $line_jump = false;
                                    @endphp
                                    @break
                                @endif
                            @endforeach
                        @else
                            @php
                                $line_exists[$catfill['id']] = [];
                                array_push($line_exists[$catfill['id']], $prodfill['id']);
                                $line_jump = false;
                            @endphp
                        @endif
                        @if (!$line_jump)
                            <tr>
                                <td style="text-align: center">{{$set['resume']}}: @if($set['capacity'] == 1) Alta @else Baixa @endif</td>
                                <td style="text-align: center">@if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                        {{$set['product_air_evap']['model']}} @endif @endif</td>
                                @foreach ($months as $d)
                                    @php
                                        $rqtd = 0;
                                        $rprice = 0.00;
                                        $product_row = $programation_macro->where('yearmonth', $d)->where('set_product_id', $set['id'])->first();
                                    @endphp

                                    @if ($product_row)
                                        @php
                                            if (Request::get('is_total') == 1)
                                                $rqtd = $product_row->total;
                                            else
                                                $rqtd = $product_row->quantity;

                                            $rprice = $product_row->price;
                                        @endphp
                                    @endif
                                    <td style="text-align: center">{{$rqtd}}</td>
                                    <td colspan="2" style="text-align: center">R$ {{number_format($rprice, 2, ',', '.')}}</td>
                                @endforeach
                            </tr>
                        @endif
                    @endif
                @endforeach
            @endforeach
        @endforeach
    @endforeach
    <tr>
    <td style="text-align: center" colspan="{{$colspan}}"></td>
    </tr>
    @endforeach
</table>
