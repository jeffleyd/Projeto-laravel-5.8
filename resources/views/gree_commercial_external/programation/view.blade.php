@extends('gree_commercial_external.layout')
@section('page-css')
    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
@endsection
@section('page-breadcrumb')
<style>
    .this-lock {
        position: absolute;
        color: white;
        font-size: 25px;
        margin: -5px -55px;
    }
    .bg-lock {
        background: #00000045 !important;
    }
    .qtd-td {
        border: none;
        width: 40px;
        text-align: center;
        border: 1px solid #d2d2d2;
    }
    .swal2-popup .swal2-select {
        display: flex;
        border-radius: 5px;
        border: solid 3px #d6d6d6;
        width: 100% !important;
    }
    .analyze {
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        width: 345px;
        height: 149px;
        z-index: 2;
        display: flex;
        justify-content: center;
        background: white;
        box-shadow: 0px 0px 10px rgb(0 0 0 / 38%);
    }
    .tagst {
        font-size: 10px;
        float: right;
        margin-top: 3px;
    }
</style>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Código da programação: {{$programation->code}}</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                @if ($in_analyze == 1)
                    <a class="btn btn-success d-none d-lg-block m-l-15" href="javascript:void(0)">
                        <i class="fa fa-check-circle"></i> Realizando análise
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><b>Data de criação:</b> {{date('d/m/Y', strtotime($programation->created_at))}}</td>
                            </tr>
                            <tr>
                                <td><b>Representante:</b> {{$programation->salesman->short_name}}</td>
                            </tr>
                            <tr>
                                <td><b>Gestor:</b> <span id="manager">@if (isset($client->client_managers[0]->salesman)) {{$client->client_managers[0]->salesman->short_name}} @endif</span></td>
                            </tr>
                            <tr>
                                <td><b>Cliente:</b> <span id="client">{{$client->company_name}}</span></td>
                            </tr>
                            <tr>
                                <td><b>Tipo do cliente:</b> <span id="type_client">{{$client->type_client_name}}</span>
								<br><small>Originado da base do cadastro</small></td>
                            </tr>
                            <tr>
                                <td><b>Regime de tributação:</b> <span id="type_client">{{$client->tax_regime_name}}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th colspan="3" class="text-center">Registro de alterações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $trow = 0;
                        $arr_row = $programation->programationVersionAll->toArray();
                        $html = '';
                        ?>
                        @foreach ($arr_row as $index => $val)
                            @if ($trow == 0)
                                <?php $html .= '<tr>'; ?>
                            @endif
                            @php $status_row = ""; @endphp
                            @if($val['is_approv'] == 1)
                                @php $status_row = 'Aprovado'; @endphp
                            @elseif ($val['is_reprov'] == 1)
                                @php $status_row = 'Reprovado'; @endphp
                            @else
                                @php $status_row = 'Em análise'; @endphp
                            @endif
                            @if ($val['version'] == $version->version)
                               <?php $html .= '<td style="cursor: pointer" onclick="viewVersion('.$val['version'].')" class="text-center bg-primary"><a style="color: white !important" href="javascript:void(0)">Ver: 00'. $val['version'].' <span style="font-size: 10px">'.$val['created'].'</span></a><span class="tagst" style="color:white"> '. $status_row .' </span></td>'; ?>
                            @else
                                <?php $html .= '<td style="cursor: pointer" onclick="viewVersion('.$val['version'].')" class="text-center"><a href="javascript:void(0)">Ver: 00'. $val['version'].' <span style="font-size: 10px">'.$val['created'].'</span></a><span class="tagst" style="color:black"> '. $status_row .' </span></td>'; ?>
                            @endif
                            <?php $trow++; ?>
                            @if ($trow == 3)
                                <?php $html .= '</tr>'; ?>
                                <?php $trow = 0; ?>
                            @elseif (($index+1) == count($arr_row))
                                <?php $rest = 3 - $trow; ?>
                                @for ($i = 0; $i < $rest; $i++)
                                    <?php $html .= '<td></td>'; ?>
                                @endfor
                                <?php $html .= '</tr>'; ?>
                            @endif
                        @endforeach
                        <?= $html ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Programação
                <div class="card-actions">
                    <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered">
                    <tr class="table-active">
                        <td colspan="2" rowspan="2" style="background: white;border: none; text-align: center">
                            Clique sobre o mês para ver as <br>condições comerciais.
                        </td>
                        @foreach ($months as $value)
                            @if (isset($version_arr[date('Y-m', strtotime($value['date']))]))
                            @php $date = new \Carbon\Carbon($value['date']); @endphp
                            @php $table = $tables->where('yearmonth', date('Y-m-01 00:00:00', strtotime($value['date'])))->first(); @endphp
                            @php $table_json = $table->json_table_price; @endphp
                            @php $table = json_decode($table->json_table_price, true); @endphp
                            <td onclick="viewTablePrice(this)" data-json="<?= htmlspecialchars($table_json, ENT_QUOTES, 'UTF-8') ?>" data-date="{{date('Y-m', strtotime($value['date']))}}" style="text-align: center; cursor: pointer;" colspan="3">{{$date->locale('pt_BR')->isoFormat('MMMM')}} {{$date->locale('pt_BR')->isoFormat('YYYY')}} <span class="label label-info float-right">{{$table['code']}}</span></td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="table-active">
                        @foreach ($months as $value)
                            @if (isset($version_arr[date('Y-m', strtotime($value['date']))]))
                            <td style="text-align: center">Qty</td>
                            <td style="text-align: center">Preço</td>
                            <td style="text-align: center">MIX%</td>
                            @endif
                        @endforeach
                    </tr>

                    @foreach ($category->where('is_conf_cap', 1) as $value)
                        <tr class="table-primary">
                            <td style="text-align: center" colspan="2">{{$value['name']}}</td>
                            @foreach ($months as $d)
                                @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                                <td style="text-align: center" id="category-qtd-total-{{$value['id']}}-{{date('Y-m', strtotime($d['date']))}}">0</td>
                                <td style="text-align: center" id="category-price-total-{{$value['id']}}-{{date('Y-m', strtotime($d['date']))}}">R$ 0,00</td>
                                <td style="text-align: center" id="category-mix-total-{{$value['id']}}-{{date('Y-m', strtotime($d['date']))}}">0.0%</td>
                                @endif
                            @endforeach
                        </tr>
                        @php
                            $line_exists = [];
                        @endphp
                        @foreach ($months as $d)
                            @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                                @foreach ($version_arr[date('Y-m', strtotime($d['date']))]['category'] as $catfill)
                                    @if ($catfill['is_hlcap'] == 1)
                                        @foreach ($value['set_product_on_group'] as $set)
                                            @foreach ($catfill['products'] as $prodfill)
                                                @if ($prodfill['id'] == $set['id'])
                                                    @php
                                                        $line_jump = true;
                                                    @endphp
                                                    @if (isset($line_exists[$catfill['id']]))
                                                        @if (!in_array($prodfill['id'], $line_exists[$catfill['id']]))
                                                            @php
                                                            array_push($line_exists[$catfill['id']], $prodfill['id']);
                                                            $line_jump = false;
                                                            @endphp
                                                        @endif
                                                    @else
                                                        @php
                                                            $line_exists[$catfill['id']] = [];
                                                            array_push($line_exists[$catfill['id']], $prodfill['id']);
                                                            $line_jump = false;
                                                        @endphp
                                                    @endif
                                                    @if (!$line_jump)
                                                    <tr>
                                                        <td style="text-align: center">{{$set['resume']}} @if($set['capacity'] == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
                                                        <td style="text-align: center">@if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                                            {{$set['product_air_evap']['model']}} @endif @endif</td>
                                                        @foreach ($months as $d)
                                                            @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                                                                @php
                                                                    $dline = $version_arr[date('Y-m', strtotime($d['date']))];
                                                                    $qtdline = 0;
                                                                    $priceline = 0.00;
                                                                @endphp
                                                                @foreach($dline['category'] as $catline)
                                                                    @if ($catline['is_hlcap'] == 1)
                                                                        @foreach ($catline['products'] as $prodline)
                                                                            @if ($prodline['id'] == $set['id'])
                                                                                @php
                                                                                    $qtdline = $prodline['qtd'];
                                                                                    $priceline = $prodline['price'];
                                                                                @endphp
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                                <td style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-qtd"><input disabled data-cat="{{$value['id']}}" data-product="{{$set['id']}}" data-date="{{date('Y-m', strtotime($d['date']))}}" onkeyup="reCalcColumn(this)" class="qtd-td" type="text" value="{{$qtdline}}"></td>
                                                                <td style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-price">R$ {{number_format($priceline, 2, ',', '.')}}</td>
                                                                <td style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-mix">0.0%</td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                    <tr class="table-success">
                        <td class="hl-cap" style="text-align: center" colspan="2">BAIXA CAPACIDADE</td>
                        @foreach ($months as $d)
                        @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                        <td class="hl-cap" style="text-align: center" colspan="3" id="low-cap-total-{{date('Y-m', strtotime($d['date']))}}">0.0%</td>
                        @endif
                        @endforeach
                    </tr>
                    <tr class="table-success">
                        <td class="hl-cap" style="text-align: center" colspan="2">ALTA CAPACIDADE</td>
                        @foreach ($months as $d)
                        @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                        <td class="hl-cap" style="text-align: center" colspan="3" id="high-cap-total-{{date('Y-m', strtotime($d['date']))}}">0.0%</td>
                        @endif
                        @endforeach
                    </tr>
                    @foreach ($category->where('is_conf_cap', 0) as $value)
                        <tr class="table-primary">
                            <td style="text-align: center" colspan="2">{{$value['name']}}</td>
                            @foreach ($months as $d)
                                @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                                <td style="text-align: center" id="category-qtd-total-{{$value['id']}}-{{date('Y-m', strtotime($d['date']))}}">0</td>
                                <td colspan="2" style="text-align: center" id="category-price-total-{{$value['id']}}-{{date('Y-m', strtotime($d['date']))}}">R$ 0,00</td>
                                @endif
                            @endforeach
                        </tr>
                        @foreach ($months as $d)
                            @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                                @foreach ($version_arr[date('Y-m', strtotime($d['date']))]['category'] as $catfill)
                                    @if ($catfill['is_hlcap'] == 0)
                                        @foreach ($value['set_product_on_group'] as $set)
                                            @foreach ($catfill['products'] as $prodfill)
                                                @if ($prodfill['id'] == $set['id'])
                                                    @php
                                                        $line_jump = true;
                                                    @endphp
                                                    @if (isset($line_exists[$catfill['id']]))
                                                        @if (!in_array($prodfill['id'], $line_exists[$catfill['id']]))
                                                            @php
                                                            array_push($line_exists[$catfill['id']], $prodfill['id']);
                                                            $line_jump = false;
                                                            @endphp
                                                        @endif
                                                    @else
                                                        @php
                                                            $line_exists[$catfill['id']] = [];
                                                            array_push($line_exists[$catfill['id']], $prodfill['id']);
                                                            $line_jump = false;
                                                        @endphp
                                                    @endif
                                                    @if (!$line_jump)
                                                    <tr>
                                                        <td style="text-align: center">{{$set['resume']}} @if($set['capacity'] == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
                                                        <td style="text-align: center">@if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                                            {{$set['product_air_evap']['model']}} @endif @endif</td>
                                                        @foreach ($months as $d)
                                                            @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                                                                @php
                                                                    $dline = $version_arr[date('Y-m', strtotime($d['date']))];
                                                                    $qtdline = 0;
                                                                    $priceline = 0.00;
                                                                @endphp
                                                                @foreach($dline['category'] as $catline)
                                                                    @if ($catline['is_hlcap'] == 0)
                                                                        @foreach ($catline['products'] as $prodline)
                                                                            @if ($prodline['id'] == $set['id'])
                                                                                @php
                                                                                    $qtdline = $prodline['qtd'];
                                                                                    $priceline = $prodline['price'];
                                                                                @endphp
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                                <td style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-qtd"><input disabled data-cat="{{$value['id']}}" data-product="{{$set['id']}}" data-date="{{date('Y-m', strtotime($d['date']))}}" onkeyup="reCalcColumn(this)" class="qtd-td" type="text" value="{{$qtdline}}"></td>
                                                                <td colspan="2" style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-price">R$ {{number_format($priceline, 2, ',', '.')}}</td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                    <tr style="background-color: #d9dada">
                        <td style="text-align: center" colspan="2">TOTAL/MÊS</td>
                        @foreach ($months as $d)
                            @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                            <td style="text-align: center" colspan="3" id="month-total-{{date('Y-m', strtotime($d['date']))}}">0</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td style="text-align: center; background-color: #d9dada" colspan="2">TIPO DE FRETE</td>
                        @foreach ($months as $d)
                            @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                                @php
                                    $arr = ['CIF', 'FOB'];
                                    $fob = [
                                        26 => 'Manaus',
                                        27 => 'RR/AC/RO/AP/PA',
                                        28 => 'NORDESTE',
                                        29 => 'SUDESTE',
                                        30 => 'CENTROESTE',
                                        31 => 'SUL',
                                    ];
                                    $ciffob = '';
                                @endphp
                                @if ($version_arr[date('Y-m', strtotime($d['date']))]['cif_fob'] == 0)
                                    @php $ciffob = 'CIF'; @endphp
                                @else
                                    @php $ciffob = 'FOB ('.$fob[$version_arr[date('Y-m', strtotime($d['date']))]['cif_fob']].')'; @endphp
                                @endif
                            <td style="text-align: center; background-color: #FFFFFF" colspan="3" id="shipping-type-{{date('Y-m', strtotime($d['date']))}}">{{$ciffob}}</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td style="text-align: center; background-color: #d9dada" colspan="2">VERBA/VPC/CONTRATO</td>
                        @foreach ($months as $d)
                            @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                            <td style="text-align: center; background-color: #FFFFFF" colspan="3" id="vpc-{{date('Y-m', strtotime($d['date']))}}">{{number_format($version_arr[date('Y-m', strtotime($d['date']))]['contract_vpc'], 2, '.', '')}}%</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td style="text-align: center; background-color: #d9dada" colspan="2">PRAZO MÉDIO DE PAGAMENTO</td>
                        @foreach ($months as $d)
                            @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                            <td style="text-align: center; background-color: #FFFFFF" colspan="3" id="payment-{{date('Y-m', strtotime($d['date']))}}">{{$version_arr[date('Y-m', strtotime($d['date']))]['average_term']}} Dias</td>
                            @endif
                        @endforeach
                    </tr>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-6">
        <div class="alert alert-warning">
            <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Atenção</h3>
            <?= $alert ?>
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="alert alert-info">
            <h3 class="text-info">Observação adicional</h3>
            <?= $programation->description ?>
        </div>
    </div>
</div>
@if ($version->is_approv == 1 and $version->description)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-rounded">
                <b>Motivo da análise:</b> {{$version->description}}
            </div>
        </div>
    </div>
@endif
@if ($version->is_reprov == 1 and $version->description)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-rounded">
                <b>Motivo da análise:</b> {{$version->description}}
            </div>
        </div>
    </div>
@endif
@if ($in_analyze == 1)
<div class="card text-center analyze" style="position: fixed;border-radius: 5px;" id="card_approv">
    <div class="card-body">
        <h4 class="card-title"></h4>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon33"><i class="ti-lock"></i></span>
            </div>
            <input type="password" class="form-control" id="pass_type" placeholder="Digite sua senha" aria-label="Username" aria-describedby="basic-addon11">
        </div>
        <div class="row button-group">
            <div class="col-lg-6 col-md-6">
                <button type="button" onclick="approv()" class="btn waves-effect waves-light btn-block btn-info">Aprovar</button>
            </div>
            <div class="col-lg-6 col-md-6">
                <button type="button" onclick="reprov()" class="btn waves-effect waves-light btn-block btn-danger">Reprovar</button>
            </div>
        </div>
    </div>
</div>

<form id="analyze-submit" method="post" action="/comercial/operacao/programation/approv_do">
    <div id="analyze-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="programation_id" value="<?= $programation->id ?>">
                    <input type="hidden" name="type_analyze" id="type_analyze">
                    <input type="hidden" name="password" id="pass">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Observação</label>
                            <textarea name="description" id="description" rows="4" class="form-control noresizing"></textarea>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" id="analyze-btn" class="btn btn-success pull-right">Aprovar</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endif
	
<div id="ModalTablePrice" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">CONDIÇÃO COMERCIAL</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
                <div class="table-wrapper">
                            <header>
                            </header>
                            <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                <thead>
                                <tr>
                                    <th scope="col" data-rt-column="Nome">Nome</th>
                                    <th scope="col" data-rt-column="Valor">Valor</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        É programado?
                                    </td>
                                    <td id="t_is_programmed">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Tipo de cliente
                                    </td>
                                    <td id="t_type_client">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        É suframa?
                                    </td>
                                    <td id="t_is_suframa">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Desconto Extra
                                    </td>
                                    <td id="t_descont_extra">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Carga completo
                                    </td>
                                    <td id="t_charge">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Contrato / VPC
                                    </td>
                                    <td id="t_contract_vpc">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Prazo médio
                                    </td>
                                    <td id="t_average_term">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        PIS / Confins
                                    </td>
                                    <td id="t_pis_confis">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Tipo de entrega
                                    </td>
                                    <td id="t_cif_fob">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ICMS
                                    </td>
                                    <td id="t_icms">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Ajuste comercial
                                    </td>
                                    <td id="t_adjust_commercial">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
					<div class="clear"></div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	 </div>
	
@endsection

@section('page-scripts')
	<script src="/commercial/salesmanTablePrice.js"></script>
    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        var yearmonth, yearmonthelem;
        var client_id = 0;
        var months = {!! json_encode($months_arr) !!}
        var programation = {!! $version->json_programation !!};

        function approv() {
            if ($('#pass_type').val() == '')
                return $error('você precisa preencher a senha para aprovar!');

            $('.modal-title').html('APROVAR PROGRAMAÇÃO');
            $('#analyze-btn').html('Aprovar');
            $('#analyze-btn').removeClass('btn-danger');
            $('#analyze-btn').addClass('btn-success');
            $('#type_analyze').val(1);
            $('#pass').val($('#pass_type').val());
            $('#analyze-modal').modal('show');
        }

        function reprov() {
            if ($('#pass_type').val() == '')
                return $error('você precisa preencher a senha para aprovar!');

            $('.modal-title').html('REPROVAR PROGRAMAÇÃO');
            $('#analyze-btn').html('Reprovar');
            $('#analyze-btn').removeClass('btn-success');
            $('#analyze-btn').addClass('btn-danger');
            $('#type_analyze').val(2);
            $('#pass').val($('#pass_type').val());
            $('#analyze-modal').modal('show');
        }

        $('#analyze-btn').click(function () {

            if ($('#type_analyze').val() == "2" && $('#description').val() == '') {
                return $error('Você precisa informar a observação, sobre sua análise.')
            } else {

                block();
                $('#analyze-modal').modal('hide');
                $('#analyze-submit').submit();
            }
        });

        function viewVersion(v) {
            @if (Request::get('order_id'))
            window.open('?version='+v+'&order_id={{Request::get('order_id')}}','_blank');
            @else
            window.open('?version='+v,'_blank');
            @endif
        }
		
        function viewTablePrice($this) {
			
            var obj = JSON.parse($($this).attr('data-json'));
			$('.modal-title').html('Condição comercial ('+obj.code+')');
			var table = commercialTablePriceConvertValue(obj);
			$('#t_adjust_commercial').html(table.adjust_commercial);
			$('#t_average_term').html(table.average_term);
			$('#t_charge').html(table.charge);
			$('#t_cif_fob').html(table.cif_fob);
			$('#t_contract_vpc').html(table.contract_vpc);
			$('#t_descont_extra').html(table.descont_extra);
			$('#t_icms').html(table.icms);
			$('#t_is_programmed').html(table.is_programmed);
			$('#t_is_suframa').html(table.is_suframa);
			$('#t_pis_confis').html(table.pis_confis);
			$('#t_type_client').html(table.type_client);

			$('#ModalTablePrice').modal();
			
        }
			
        function reloadValues() {
            months.forEach(function (mval) {
                var cat = programation[mval]['category'];
                cat.forEach(function (val) {
                    reCalcColumn(mval, val.id);
                });
            });
        }
        function reCalcColumn(date, category_id) {
            var total_cat = 0;
            var total_price = 0;
            var total_mix = 0;
            var total_hlcap = 0;
            var total_month = 0;
            var total_hlcap_cat = 0;
            var row_products;
            var cat = programation[date]['category'];
            cat.forEach(function (val) {
                if (val.id == category_id) {
                    var prod = val['products'];
                    row_products = prod;
                    prod.forEach(function (valp) {
                        total_cat = total_cat + parseInt(valp.qtd);
                        total_price = total_price + (valp.price * valp.qtd);

                    });


                    // Soma total da categoria
                    $('#category-qtd-total-'+val.id+'-'+date).html(total_cat);
                    // Soma total do preço da categoria
                    $('#category-price-total-'+val.id+'-'+date).html(total_price.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));

                    if (val.is_hlcap == 1) {
                        // porcentagem total do Mix da categoria
                        total_mix = isNaN(total_mix) ? '0.0' : total_mix;
                        $('#category-mix-total-'+val.id+'-'+date).html(total_mix+'%');
                        total_hlcap_cat = total_hlcap_cat + total_cat;
                    }
                }


                // Soma total de alta e baixa capacidade
                if (val.is_hlcap == 1) {
                    var prod = val['products'];
                    prod.forEach(function (valp) {
                        total_hlcap = total_hlcap + valp.qtd;
                    });
                }

                // Soma total do mês
                var prod = val['products'];
                prod.forEach(function (valp) {
                    total_month = total_month + valp.qtd;
                });

            });

            $('#month-total-'+date).html(total_month);

            updateColumnMix(date,total_hlcap);
        }

        function changeTablePrice($this) {
            yearmonth = $($this).attr('data-date');
            yearmonthelem = $this;
            if (programation[yearmonth]['table'] != 0) {
                Swal.fire({
                    title: 'Escolha uma opção!',
                    input: 'select',
                    inputOptions: {
                        '1': 'Escolher outra tabela',
                        '2': 'Cancelar o mês',
                    },
                    showCancelButton: true,
                    inputValidator: function (value) {
                        return new Promise(function (resolve, reject) {
                            if (value !== '') {
                                resolve();
                            } else {
                                resolve('Você precisa escolhar uma ação!');
                            }
                        });
                    }
                }).then(function (result) {
                    if (result.value) {
                        if (result.value == 1) {
                            $('#modal-tableprice').modal();
                        } else {
                            $(yearmonthelem).addClass('bg-lock');
                            $(yearmonthelem)[0].lastChild.remove();
                            $(yearmonthelem).append('<i class="ti-lock this-lock"></i>');
                            cleanColumn();
                        }

                    }
                });
            } else {
                $('#modal-tableprice').modal();
            }

        }

        function updateColumnMix(date, total) {
            var cat = programation[date]['category'];
            var total_high_cap = 0;
            var total_low_cap = 0;
            cat.forEach(function (val) {
                var prod = val['products'];
                var total_cat = 0;
                prod.forEach(function (valp) {
                    total_cat = total_cat + valp.qtd;
                    var rowmix = ((valp.qtd/total) * 100).toFixed(1);
                    rowmix = isNaN(rowmix) ? '0.0' : rowmix;
                    $('#cat-'+val.id+'-product-'+valp.id+'-date-'+date+'-mix').html(rowmix+'%');

                    if (val.is_hlcap == 1) {
                        if (valp.hlcap == 1)
                            total_high_cap = total_high_cap + valp.qtd;
                        else
                            total_low_cap = total_low_cap + valp.qtd;
                    }

                });

                if (val.is_hlcap == 1) {
                    var mixtotalcat = ((total_cat/total) * 100).toFixed(1);
                    mixtotalcat = isNaN(mixtotalcat) ? '0.0' : mixtotalcat;
                    $('#category-mix-total-'+val.id+'-'+date).html(mixtotalcat+'%');
                }
            });

            var lowcap = ((total_low_cap/total) * 100).toFixed(1);
            lowcap = isNaN(lowcap) ? '0.0' : lowcap;
            $('#low-cap-total-'+date).html(lowcap+'%');
            var highcap = ((total_high_cap/total) * 100).toFixed(1);
            highcap = isNaN(highcap) ? '0.0' : highcap;
            $('#high-cap-total-'+date).html(highcap+'%');
        }
        function updateColumns() {

            var cat = programation[yearmonth]['category'];
            cat.forEach(function (val){
                var prod = val['products'];
                prod.forEach(function (valp) {
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-qtd > input').removeAttr('disabled');
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-price').html(valp.price.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
                });
            });

            $('#vpc-'+yearmonth).html(programation[yearmonth]['contract_vpc']+'%');
            $('#payment-'+yearmonth).html(programation[yearmonth]['average_term']);
            var cif_fob = {0: 'CIF', 1: 'FOB'};
            $('#shipping-type-'+yearmonth).html(cif_fob[programation[yearmonth]['cif_fob']]);
        }
        function cleanColumn() {

            var cat = programation[yearmonth]['category'];
            programation[yearmonth]['average_term'] = 0;
            programation[yearmonth]['cif_fob'] = 0;
            programation[yearmonth]['contract_vpc'] = 0;
            programation[yearmonth]['table'] = 0;
            cat.forEach(function (val){
                var prod = val['products'];
                prod.forEach(function (valp) {
                    valp.qtd = 0;
                    valp.price = 0;
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-qtd > input').attr('disabled', '');
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-qtd > input').val(0);
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-price').html('R$ 0,00');
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-mix').html('0.0%');
                });

                $('#category-mix-total-'+val['id']+'-'+yearmonth).html('0.0%');
                $('#category-price-total-'+val['id']+'-'+yearmonth).html('R$ 0,00');
                $('#category-qtd-total-'+val['id']+'-'+yearmonth).html('0');

            });

            $('#low-cap-total-'+yearmonth).html('0');
            $('#high-cap-total-'+yearmonth).html('0');

            $('#month-total-'+yearmonth).html('0');
            $('#vpc-'+yearmonth).html('0%');
            $('#payment-'+yearmonth).html('0');
            $('#shipping-type-'+yearmonth).html('');
        }

        function action($this = '') {

            if ($this == '') {
                window.location.href = '/comercial/operacao/tabela/preco/0';
            }
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                window.location.href = '/comercial/operacao/tabela/preco/'+json.id;
            } else if ($($this).val() == 2) {
                block();
                ajaxSend('/comercial/operacao/change/table/month', {date: yearmonth, id: json.id}).then(function (response) {
                    unblock();
                    programation[yearmonth]['category'] = response.result;
                    programation[yearmonth]['contract_vpc'] = response.contract_vpc;
                    programation[yearmonth]['average_term'] = response.average_term;
                    programation[yearmonth]['cif_fob'] = response.cif_fob;
                    programation[yearmonth]['table'] = response.table_id;
                    $('#modal-tableprice').modal('toggle');
                    $(yearmonthelem).removeClass('bg-lock');
                    $(yearmonthelem)[0].lastChild.remove();
                    $(yearmonthelem).append('<span class="label label-info float-right">'+response.code+'</span>');
                    updateColumns();
                }).catch(function (error) {
                    unblock();
                    $error('Aconteceu um erro inesperado, atualize a página!');
                })
            }
            $($this).val('');
        }

        function selectClient() {
            $('#modal-client').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }

        function saveProgramation() {
            if ($('#json_programation').val() == '') {
                $error('Você precisa escolher a tabela de condição comercial clicando sobre o mês e adicionar os produtos que deseja fazer o pedido.');
            } else {
                Swal.fire({
                    title: 'Aviso importante',
                    text: 'Você está prestes a criar uma programação, confirmando, o gestor regional será notificado para aprovar a solicitação! Deseja continuar?',
                    confirmButtonText:
                        '<i class="fa fa-thumbs-up"></i> Pode continuar!',
                    confirmButtonAriaLabel: 'Thumbs up, great!',
                    cancelButtonText:
                        'Cancelar',
                    showCancelButton: true,
                    inputValidator: function (value) {
                        return new Promise(function (resolve, reject) {
                            if (value) {
                                resolve();
                            }
                        });
                    }
                }).then(function (result) {
                    if (result.value) {
                        block();
                        $('#submitProgramation').submit();
                    }
                });

            }
        }

        $('.js-select2').change(function() {
            if ($(this).val() != '') {
                client_id = $(".js-select2 option:selected").val();
                $('#client_id').val($(".js-select2 option:selected").val());
                $('#client').html($(".js-select2 option:selected").text());
                $('#type_client').html($(".js-select2 option:selected").attr('data-type-client'));
                $('#manager').html($(".js-select2 option:selected").attr('data-manager'));
                $('#modal-client').modal('toggle');
            } else {
                client_id = 0;
                $('#client').html('');
                $('#type_client').html('');
                $('#manager').html('');
            }
        })

        $(document).ready(function () {

            reloadValues();
            $('.qtd-td').mask('0000', {reverse: false});
        });

    </script>

@endsection
