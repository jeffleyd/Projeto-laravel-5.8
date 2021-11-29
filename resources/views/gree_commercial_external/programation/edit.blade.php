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
    .tagst {
        font-size: 10px;
        float: right;
        margin-top: 3px;
    }
</style>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Editando programação: {{$programation->code}}</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <a class="btn btn-success d-none d-lg-block m-l-15" onclick="saveProgramation()" href="#">
                    <i class="fa fa-check-circle"></i> Atualizar programação
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form id="submitProgramation" action="/comercial/operacao/programation/edit/save" method="POST">
    <input type="hidden" id="json_programation" name="json_programation" value="">
    <input type="hidden" id="programation_id" name="programation_id" value="{{$programation->id}}">
    <input type="hidden" id="programation_desc" name="programation_desc" value="{{$programation->description}}">
</form>
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
            <textarea class="form-control" rows="4" placeholder="Digite aqui..." id="descript_add">{{$programation->description}}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger">
            A condição comercial vinculada no mês, só será atualizada se você trocar a mesma por outra condição comercial!
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
                            Clique em cima do mês para <br>trocar a condição comercial.
                        </td>
                        @foreach ($months as $value)
                            @if (isset($version_arr[date('Y-m', strtotime($value['date']))]))
                            @php $date = new \Carbon\Carbon($value['date']); @endphp
                            @php $table = $tables->where('yearmonth', date('Y-m-01 00:00:00', strtotime($value['date'])))->first(); @endphp
                            @php $table = json_decode($table->json_table_price, true); @endphp
                            <td onclick="changeTablePrice(this)" data-date="{{date('Y-m', strtotime($value['date']))}}" style="text-align: center; cursor: pointer;" colspan="3">{{$date->locale('pt_BR')->isoFormat('MMMM')}} {{$date->locale('pt_BR')->isoFormat('YYYY')}} <i class="ti-pencil-alt"></i> <span class="label label-info float-right">{{$table['code']}}</span></td>
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
                        @foreach ($value['set_product_on_group'] as $set)

                            <tr>
                                <td style="text-align: center">{{$set['resume']}} @if($set['capacity'] == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
                                <td style="text-align: center">@if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                    {{$set['product_air_evap']['model']}} @endif @endif</td>
                                @foreach ($months as $d)
                                    @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                                        @php
                                            $rqtd = 0;
                                            $rprice = 0.00;
                                        @endphp

                                        @foreach ($version_arr[date('Y-m', strtotime($d['date']))]['category'] as $catfill)
                                            @if ($catfill['id'] == $value['id'])
                                                @foreach ($catfill['products'] as $prodfill)
                                                    @if ($prodfill['id'] == $set['id'])
                                                        @php
                                                            $rqtd = $prodfill['qtd'];
                                                            $rprice = $prodfill['price'];
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach

                                        @php $is_block = 0; @endphp
                                        @if (date('Y-m', strtotime($d['date'])) < date('Y-m'))
                                            @php $is_block = 1; @endphp
                                        @endif
                                        <td style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-qtd"><input @if ($is_block == 1) disabled @endif data-cat="{{$value['id']}}" data-product="{{$set['id']}}" data-date="{{date('Y-m', strtotime($d['date']))}}" onkeyup="reCalcColumn(this)" class="qtd-td" type="text" value="{{$rqtd}}"></td>
                                        <td style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-price">R$ {{number_format($rprice, 2, ',', '.')}}</td>
                                        <td style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-mix">0.0%</td>
                                    @endif
                                @endforeach
                            </tr>
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
                        @foreach ($value['set_product_on_group'] as $set)

                            <tr>
                                <td style="text-align: center">{{$set['resume']}} @if($set['capacity'] == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
                                <td style="text-align: center">@if ($set['product_air_evap']) @if (substr($set['product_air_evap']['model'], -2) == '/I' or substr($set['product_air_evap']['model'], -2) == '/O') {{substr($set['product_air_evap']['model'], 0, -2)}} @else
                                    {{$set['product_air_evap']['model']}} @endif @endif</td>
                                @foreach ($months as $d)
                                    @if (isset($version_arr[date('Y-m', strtotime($d['date']))]))
                                        @php
                                            $rqtd = 0;
                                            $rprice = 0.00;
                                        @endphp

                                        @foreach ($version_arr[date('Y-m', strtotime($d['date']))]['category'] as $catfill)
                                            @if ($catfill['id'] == $value['id'])
                                                @foreach ($catfill['products'] as $prodfill)
                                                    @if ($prodfill['id'] == $set['id'])
                                                        @php
                                                            $rqtd = $prodfill['qtd'];
                                                            $rprice = $prodfill['price'];
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                        @php $is_block = 0; @endphp
                                        @if (date('Y-m', strtotime($d['date'])) < date('Y-m'))
                                            @php $is_block = 1; @endphp
                                        @endif
                                        <td style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-qtd"><input @if ($is_block == 1) disabled @endif data-cat="{{$value['id']}}" data-product="{{$set['id']}}" data-date="{{date('Y-m', strtotime($d['date']))}}" onkeyup="reCalcColumn(this)" class="qtd-td" type="text" value="{{$rqtd}}"></td>
                                        <td colspan="2" style="text-align: center" id="cat-{{$value['id']}}-product-{{$set['id']}}-date-{{date('Y-m', strtotime($d['date']))}}-price">R$ {{number_format($rprice, 2, ',', '.')}}</td>
                                    @endif
                                @endforeach
                            </tr>
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
                            <td style="text-align: center; background-color: #FFFFFF" colspan="3" id="vpc-{{date('Y-m', strtotime($d['date']))}}">{{$version_arr[date('Y-m', strtotime($d['date']))]['contract_vpc']}}%</td>
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

<div id="modal-tableprice" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="vcenter">Condições comerciais</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <p>Caso não tenha nehuma <b>"condição comercial"</b> para ser escolhida, você precisará criar uma <b>"condição comercial"</b> antes de criar sua programação.</p>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody id="progTable">
                        @foreach ($salesman_table_price as $key)
                            <tr>
                                <td>{{$key->code}}</td>
                                <td>{{$key->name}}</td>
                                <td>
                                    @if ($key->version >= $version_actual)
                                        <span class="label label-success">Atualizada</span>
                                    @else
                                        <span class="label label-warning">Desatualizada </span> <i data-toggle="tooltip" data-placement="top" title="" data-original-title="Condição precisa ser editada e atualizada com novo preço!" class="fa fa-info-circle"></i>
                                    @endif
                                </td>
                                <td>
                                    <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                        <option></option>
                                        <option value="1">Editar</option>
                                        <option value="2">Escolher</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="modal-client" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label>Escolha o cliente</label>
                    <select name="client" class="form-control js-select2">
                        <option value=""></option>
                        @foreach ($clients as $client)
                            <option data-type-client="{{$client->type_client_name}}" data-manager="{{$client->manager_region[0]->short_name}}" value="{{$client->id}}">{{$client->company_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('page-scripts')

    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        var yearmonth, yearmonthelem;
        var client_id = 0;
        var months = {!! json_encode($months_arr) !!}
        var programation_old = {!! json_encode($version_arr) !!};
        var programation = {!! json_encode($version_arr) !!};

        function viewVersion(v) {
            window.open('/comercial/operacao/programation/view/{{$programation->id}}?version='+v,'_blank');
        }

        function reloadValues() {
            months.forEach(function (mval) {
                var cat = programation[mval]['category'];
                cat.forEach(function (val) {
                    reLoadCalcColumn(mval, val.id);
                });
            });
        }

        function reLoadCalcColumn(date, category_id) {
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

        function reCalcColumn($this) {
            var total_cat = 0;
            var total_price = 0;
            var total_mix = 0;
            var total_hlcap = 0;
            var total_month = 0;
            var total_hlcap_cat = 0;
            var row_products;
            var cat = programation[$($this).attr('data-date')]['category'];
            cat.forEach(function (val) {
                if (val.id == $($this).attr('data-cat')) {
                    var prod = val['products'];
                    row_products = prod;
                    prod.forEach(function (valp) {
                        if (valp.id == $($this).attr('data-product')) {
                            if ($($this).val() > 0) {
                                valp.qtd = parseInt($($this).val());
                                total_cat = total_cat + parseInt($($this).val());
                            } else {
                                valp.qtd = 0;
                            }
                        } else {
                            total_cat = total_cat + valp.qtd;
                        }

                        if (valp.id == $($this).attr('data-product')) {
                            total_price = total_price + (valp.price * valp.qtd);
                        } else if (valp.qtd > 0) {
                            total_price = total_price + (valp.price * valp.qtd);
                        }

                    });

                    // Soma total da categoria
                    $('#category-qtd-total-'+val.id+'-'+$($this).attr('data-date')).html(total_cat);
                    // Soma total do preço da categoria
                    $('#category-price-total-'+val.id+'-'+$($this).attr('data-date')).html(total_price.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));

                    if (val.is_hlcap == 1) {
                        // porcentagem total do Mix da categoria
                        total_mix = isNaN(total_mix) ? '0.0' : total_mix;
                        $('#category-mix-total-'+val.id+'-'+$($this).attr('data-date')).html(total_mix+'%');
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

            $('#month-total-'+$($this).attr('data-date')).html(total_month);
            updateColumnMix($($this).attr('data-date'),total_hlcap);
            $('#json_programation').val(JSON.stringify(programation));
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
                    confirmButtonText:
                        '<i class="fa fa-thumbs-up"></i> Pode continuar!',
                    cancelButtonText:
                        'Cancelar',
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
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-qtd > input').removeAttr('disabled').val(0);
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-price').html(valp.price.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-mix').html('0.0%');
                });

                $('#category-mix-total-'+val['id']+'-'+yearmonth).html('0.0%');
                $('#category-price-total-'+val['id']+'-'+yearmonth).html('R$ 0,00');
                $('#category-qtd-total-'+val['id']+'-'+yearmonth).html('0');
            });

            $('#low-cap-total-'+yearmonth).html('0');
            $('#high-cap-total-'+yearmonth).html('0');
            $('#month-total-'+yearmonth).html('0');

            $('#vpc-'+yearmonth).html(programation[yearmonth]['contract_vpc']+'%');
            $('#payment-'+yearmonth).html(programation[yearmonth]['average_term']);

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
                    $('#cat-'+val['id']+'-product-'+valp.id+'-date-'+yearmonth+'-qtd > input').attr('disabled', '').val(0);
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

            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                window.open('/comercial/operacao/tabela/preco/'+json.id, '_blank');
            } else if ($($this).val() == 2) {
                block();
                ajaxSend('/comercial/operacao/change/table/month', {date: yearmonth, id: json.id}).then(function (response) {
                    unblock();
                    programation[yearmonth]['category'] = response.result;
                    programation[yearmonth]['contract_vpc'] = response.contract_vpc;
                    programation[yearmonth]['average_term'] = response.average_term;
                    programation[yearmonth]['cif_fob'] = response.cif_fob;
					$('#shipping-type-'+yearmonth).html(response.cif_fob_name);
                    if (programation_old[yearmonth]['table'] != response.table_id)
                        programation[yearmonth]['table_is_change'] = 1;
                    else
                        programation[yearmonth]['table_is_change'] = 0;

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
                    text: 'Você está prestes atualizar a programação, confirmando, o gestor regional será notificado para aprovar a sua atualização! Deseja continuar?',
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

            $('#descript_add').keyup(function () {
               $('#programation_desc').val($('#descript_add').val());
            });
            $('#json_programation').val(JSON.stringify(programation));
            reloadValues();
            $('.qtd-td').mask('0000', {reverse: false});
        });

    </script>

@endsection
