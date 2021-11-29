@php
$order_products = $order->orderProducts;
$total = $order_products->sum('quantity');
@endphp
<style>
	.qtd-td {
		border: none;
		width: 40px;
		text-align: center;
		border: 1px solid #d2d2d2;
	}
	.tagst {
        font-size: 12px;
        margin-top: 3px;
    }
    .portlet-placeholder, .table tbody tr:hover td, .planning-timeline-timeframe > span.current-date {
        background: #0000000f;
    }
</style>
<div class="row" style="margin-bottom: 150px">
    <div class="col-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody><tr class="table-active">
                            <td colspan="2" rowspan="2" style="background: white;border: none; text-align: center"></td>
                            <td style="text-align: center; cursor: pointer;background-color: #c2fdfdb0;" colspan="3"></td>
                        </tr>
                        <tr class="table-active" style="background-color:#ffc;">
                            <td style="text-align: center">Qty</td>
                            <td style="text-align: center">Preço</td>
                            <td style="text-align: center">MIX%</td>
                        </tr>
						@php
							$total_hlcap = 0;
							$total_high_cap = 0;
							$total_low_cap = 0;
						@endphp
						@foreach ($categories as $cat)
							@if ($cat['is_conf_cap'])
								@if ($order_products->where('category_id', $cat['id'])->first())
									@php
										$total_hlcap += $order_products->where('category_id', $cat['id'])->sum('quantity');
									@endphp
								@endif
							@endif
						@endforeach
						@foreach ($categories as $cat)
							@if ($cat['is_conf_cap'])
							<tr class="table-primary tr-model" style="background: black; color: #ffffff;">
								<td style="text-align: center" colspan="2">{{$cat['name']}}</td>
								@php
									$total_cat_line = 0.00;
									$has_cat = $order_products->where('category_id', $cat['id'])->first();
									$cat_line_quantity = $order_products->where('category_id', $cat['id'])->sum('quantity');
								@endphp
								@if ($has_cat)
								<td style="text-align: center">{{$cat_line_quantity}}</td>
								@foreach($order_products->where('category_id', $cat['id']) as $product_line)
									@php
										$total_cat_line += $product_line->quantity * $product_line->total;
									@endphp
								@endforeach
								<td style="text-align: center">R$ {{number_format($total_cat_line, 2, ',', '.')}}</td>
								<td style="text-align: center">{{round(($cat_line_quantity / $total_hlcap) * 100, 1)}}%</td>
								@else
								<td style="text-align: center">0</td>
								<td style="text-align: center">R$ 0,00</td>
								<td style="text-align: center">0.0%</td>
								@endif
							</tr>
							@foreach($cat['set_product_on_group'] as $set)
							<tr>
								@php
									$line_prod = $order_products->where('category_id', $cat['id'])->where('set_product_id', $set['id'])->first();
								@endphp
								@if ($line_prod)
								@if($set['capacity'] == 1)
									@php
										$total_high_cap += $line_prod->quantity;
									@endphp
								@else
									@php
										$total_low_cap += $line_prod->quantity;
									@endphp
								@endif
								<td style="text-align: center">{{$set['resume']}} @if($set['capacity'] == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
								<td style="text-align: center"> @if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                                                    {{$set['product_air_evap']['model']}} @endif @endif</td>
								<td style="text-align: center"><input disabled="" class="qtd-td" value="{{$line_prod->quantity}}" type="text" value="0" maxlength="4"></td>
								<td style="text-align: center">R$ {{number_format($line_prod->total, 2, ',', '.')}}</td>
								<td style="text-align: center">{{round(($line_prod->quantity/$cat_line_quantity) * 100,1)}}%</td>
								@else
								<td style="text-align: center">{{$set['resume']}} @if($set['capacity'] == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
								<td style="text-align: center"> @if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                                                    {{$set['product_air_evap']['model']}} @endif @endif</td>
								<td style="text-align: center"><input disabled="" class="qtd-td" value="0" type="text" value="0" maxlength="4"></td>
								<td style="text-align: center">R$ 0,00</td>
								<td style="text-align: center">0.0%</td>
								@endif
							</tr>
							@endforeach
							@endif
						@endforeach
                        <tr class="table-success" style="text-align: center; cursor: pointer;background-color: #c2fdfdb0;">
                            <td class="hl-cap" style="text-align: center" colspan="2">BAIXA CAPACIDADE</td>
							@if ($total_low_cap > 0)
                            <td class="hl-cap" style="text-align: center" colspan="3">{{round(($total_low_cap / $total_hlcap) * 100, 1)}}%</td>
							@else
							<td class="hl-cap" style="text-align: center" colspan="3">0.0%</td>
							@endif

                        </tr>
                        <tr class="table-success" style="text-align: center; cursor: pointer;background-color: #c2fdfdb0;">
                            <td class="hl-cap" style="text-align: center" colspan="2">ALTA CAPACIDADE</td>
							@if ($total_high_cap > 0)
                            <td class="hl-cap" style="text-align: center" colspan="3">{{round(($total_high_cap / $total_hlcap) * 100, 1)}}%</td>
							@else
							<td class="hl-cap" style="text-align: center" colspan="3">0.0%</td>
							@endif
                        </tr>
						@foreach ($categories as $cat)
							@if (!$cat['is_conf_cap'])
							<tr class="table-primary tr-model" style="background: black; color: #ffffff;">
								<td style="text-align: center" colspan="2">{{$cat['name']}}</td>
								@php
									$total_cat_line = 0.00;
									$has_cat = $order_products->where('category_id', $cat['id'])->first();
									$cat_line_quantity = $order_products->where('category_id', $cat['id'])->sum('quantity');
								@endphp
								@if ($has_cat)
								<td style="text-align: center">{{$cat_line_quantity}}</td>
								@foreach($order_products->where('category_id', $cat['id']) as $product_line)
									@php
										$total_cat_line += $product_line->quantity * $product_line->total;
									@endphp
								@endforeach
								<td style="text-align: center" colspan="2">R$ {{number_format($total_cat_line, 2, ',', '.')}}</td>
								@else
								<td style="text-align: center">0</td>
								<td style="text-align: center" colspan="2">R$ 0,00</td>
								@endif
							</tr>
							@foreach($cat['set_product_on_group'] as $set)
							<tr>
								@php
									$line_prod = $order_products->where('category_id', $cat['id'])->where('set_product_id', $set['id'])->first();
								@endphp
								@if ($line_prod)
								<td style="text-align: center">{{$set['resume']}} @if($set['capacity'] == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
								<td style="text-align: center"> @if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                                                    {{$set['product_air_evap']['model']}} @endif @endif</td>
								<td style="text-align: center"><input disabled="" class="qtd-td" value="{{$line_prod->quantity}}" type="text" value="0" maxlength="4"></td>
								<td style="text-align: center" colspan="2">R$ {{number_format($line_prod->total, 2, ',', '.')}}</td>
								@else
								<td style="text-align: center">{{$set['resume']}} @if($set['capacity'] == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
								<td style="text-align: center"> @if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                                                    {{$set['product_air_evap']['model']}} @endif @endif</td>
								<td style="text-align: center"><input disabled="" class="qtd-td" value="0" type="text" value="0" maxlength="4"></td>
								<td style="text-align: center" colspan="2">R$ 0,00</td>
								@endif
							</tr>
							@endforeach
							@endif
						@endforeach
                        <tr style="background-color: #d9dada">
                            <td style="text-align: center" colspan="2">TOTAL</td>
                            <td style="text-align: center" colspan="3">{{$total}}</td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
