
<table class="table table-bordered">
    <tr class="table-active">
        <td colspan="2" rowspan="2" style="background: white;border: none; text-align: center">
            Essa tabela é um visão geral referente ao ano atual de @if (Request::get('year')) ({{Request::get('year')}}) @else {{date('Y')}} @endif.
        </td>
        @foreach ($months as $value)
            @php $date = new \Carbon\Carbon($value); @endphp
            <td style="text-align: center; background: #d2ffff;" colspan="1">{{$date->locale('pt_BR')->isoFormat('MMMM')}}</td>
        @endforeach
    </tr>
    <tr class="table-active">
        @foreach ($months as $value)
			<td colspan="1" style="text-align: center; background-color:#f7f7c8;">Qty</td>
        @endforeach
    </tr>

    @php
        $pmacro = \App\Model\Commercial\ProgramationMacro::with('programation');

        if (Request::get('salesman_id'))
            $pmacro->where('salesman_id', Request::get('salesman_id'));

        if (Request::get('year'))
            $pmacro->whereRaw("YEAR(yearmonth) = '".Request::get('year')."'");
        else
            $pmacro->whereRaw("YEAR(yearmonth) = '".date('Y')."'");

        $programation_macro = $pmacro->get();
        $category_uniq = $cat_uniq->whereIn('id', $programation_macro->unique('category_id')->pluck('category_id')->toArray());
    @endphp
    @php
        $line_exists = [];
        $line_jump = false;
    @endphp
    @foreach ($category as $value)
        <tr class="table-primary">
			<td style="text-align: center; background-color: #000000; color:#ffffff;" colspan="2">{{$value['name']}}</td>
            @foreach ($months as $d)
                @php
                    if (Request::get('is_total') == 1)
                        $t_qtd = $programation_macro->where('yearmonth', $d)->where('category_id', $value['id'])->sum('total');
                    else
                        $t_qtd = $programation_macro->where('yearmonth', $d)->where('category_id', $value['id'])->sum('quantity');
                @endphp
				<td colspan="1" style="text-align: center; background-color: #000000; color:#ffffff;">{{$t_qtd}}</td>
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
                                <td style="text-align: center; font-size: 12px;" colspan="1">{{$set['resume']}} - @if($set['capacity'] == 1) <span class="label label-danger" style="float: none;">Alta</span> @else <span class="label label-info" style="float: none;">Baixa</span> @endif</td>
                                <td style="text-align: center; font-size: 12px;" colspan="1">@if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                        {{$set['product_air_evap']['model']}} @endif @endif</td>
                                @foreach ($months as $d)
                                    @php
                                        $rqtd = 0;
                                        $rprice = 0.00;
                                        $product_row = $programation_macro->where('yearmonth', $d)->where('set_product_id', $set['id'])->where('category_id', $value['id']);
                                    @endphp

                                    @if ($product_row)
                                        @php
                                            if (Request::get('is_total') == 1)
                                                $rqtd = $product_row->sum('total');
                                            else
                                                $rqtd = $product_row->sum('quantity');
                                        @endphp
                                    @endif
									<td colspan="1" style="text-align: center">{{$rqtd}}</td>
                                @endforeach
                            </tr>
                        @endif
                    @endif
                @endforeach
            @endforeach
        @endforeach
    @endforeach
</table>

