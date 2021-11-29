<div class="table-responsive">
    <table class="table table-bordered">
        <tr class="table-active">
            <td colspan="4" rowspan="2" style="background: white;border: none; text-align: center">
                Os valores da coluna quantidade são reduzidos de acordo com a confirmação do pedido. @if (Request::get('year')) ({{Request::get('year')}}) @else {{date('Y')}} @endif
            </td>
            @foreach ($months as $value)
                @php $date = new \Carbon\Carbon($value); @endphp
                <td style="text-align: center; cursor: pointer;" colspan="1">{{$date->locale('pt_BR')->isoFormat('MMMM')}}</td>
            @endforeach
        </tr>
        <tr class="table-active">
            @foreach ($months as $value)
                <td style="text-align: center" colspan="1">Qty</td>
            @endforeach
        </tr>

        <tr style="background: black;color: white;">
            @php
                $colspan = 4;

                if(!Request::get('client_id')) {
                    $client_id = $clients[0]->id;
                } else {
                    $client_id = Request::get('client_id');
                }
                $consumer = \App\Model\Commercial\Client::find($client_id);

                $programation_macro = \App\Model\Commercial\ProgramationMacro::with('programation')->whereHas('programation', function ($q) use ($client_id){
                   $q->where('client_id', $client_id);
                });

                if (Request::get('year'))
                    $programation_macro->whereRaw("YEAR(yearmonth) = '".Request::get('year')."'");
                else
                    $programation_macro->whereRaw("YEAR(yearmonth) = '".date('Y')."'");

                $programation_macro = $programation_macro->get();

                $category_uniq = $cat_uniq->whereIn('id', $programation_macro->unique('category_id')->pluck('category_id')->toArray());
            @endphp
            @foreach ($months as $value)
                @php
                    $colspan = $colspan + 1;
                @endphp
            @endforeach
            <td style="text-align: center" colspan="{{$colspan}}">{{$consumer->company_name}} - CPF/CNPJ: {{$consumer->identity}}</td>
        </tr>
        @php
            $line_exists = [];
            $line_jump = false;
        @endphp
        @foreach ($category as $value)
            <tr class="table-primary">
                <td style="text-align: center" colspan="4">{{$value['name']}}</td>
                @foreach ($months as $d)
                    @php
                        if (Request::get('is_total') == 1)
                            $t_qtd = $programation_macro->where('yearmonth', $d)->where('category_id', $value['id'])->sum('total');
                        else
                            $t_qtd = $programation_macro->where('yearmonth', $d)->where('category_id', $value['id'])->sum('quantity');
                    @endphp
                    <td style="text-align: center" colspan="1">{{$t_qtd}}</td>
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
								@php
									$is_visible = $set['is_visible']?? true;
								@endphp
								@if($is_visible)
                                <tr>
                                    <td style="text-align: center" colspan="2">{{$set['resume']}} @if($set['capacity'] == 1) <span class="label label-danger" style="float: none;">Alta</span> @else <span class="label label-info" style="float: none;">Baixa</span> @endif</td>
                                    <td style="text-align: center" colspan="2">@if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                            {{$set['product_air_evap']['model']}} @endif @endif</td>
                                    @foreach ($months as $d)
                                        @php
                                            $rqtd = 0;
                                            $rqtd_total = $programation_macro->where('yearmonth', $d)->where('set_product_id', $set['id'])->sum('total');
                                            $rqtd_quantity = $programation_macro->where('yearmonth', $d)->where('set_product_id', $set['id'])->sum('quantity');
                                        @endphp
                                        @php
                                            if (Request::get('is_total') == 1)
                                                $rqtd = $rqtd_total;
                                            else
                                                $rqtd = $rqtd_quantity;
                                        @endphp
                                        <td style="text-align: center" colspan="1">{{$rqtd}}</td>
                                    @endforeach
                                </tr>
								@endif
                            @endif
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    </table>
</div>
