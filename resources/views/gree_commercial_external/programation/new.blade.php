@extends('gree_commercial_external.layout')

@section('page-css')
    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="/elite/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
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
    </style>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Nova programação</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <a class="btn btn-warning d-none d-lg-block m-l-15" onclick="selectClient()" href="#">
                    <i class="fa fa-edit"></i> Alterar cliente
                </a>
                <a class="btn btn-success d-none d-lg-block m-l-15" onclick="saveProgramation()" href="#">
                    <i class="fa fa-check-circle"></i> Salvar programação
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="submitProgramation" action="/comercial/operacao/programation/new/save" method="POST">
        <input type="hidden" id="json_programation" name="json_programation" value="">
        <input type="hidden" id="client_id" name="client_id" value="">
        <input type="hidden" id="programation_desc" name="programation_desc" value="">
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
                <textarea class="form-control" rows="4" placeholder="Digite aqui..." id="descript_add"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td><b>Data de criação:</b> {{date('d/m/Y')}}</td>
                            </tr>
                            <tr>
                                <td><b>Representante:</b> {{Session::get('salesman_data')->short_name}}</td>
                            </tr>
                            <tr>
                                <td><b>Gestor:</b> <span id="manager"></span></td>
                            </tr>
                            <tr>
                                <td><b>Cliente:</b> <span id="client"></span></td>
                            </tr>
                            <tr>
                                <td><b>Tipo do cliente:</b> <span id="type_client"></span>
                                    <br><small>Originado da base do cadastro</small></td>
                            </tr>
                            <tr>
                                <td><b>Regime de tributação:</b> <span id="tax_regime"></span></td>
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
                            <tr>
                                <td><div style="margin-top:20px"></div></td>
                                <td></div></td>
                    <td></div></td>
                </tr>
                <tr>
                    <td><div style="margin-top:20px"></div></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><div style="margin-top:20px"></div></td>
                    <td></td>
                    <td></td>
                </tr>
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
                                    Clique no cadeado "<i class="ti-lock"></i>"<br> para liberar
                                </td>
                                @foreach ($months as $value)
                                    @php $date = new \Carbon\Carbon($value->date); @endphp
                                    <td onclick="changeTablePrice(this)" data-date="{{date('Y-m', strtotime($value->date))}}" style="text-align: center; cursor: pointer;" class="bg-lock" colspan="3">{{$date->locale('pt_BR')->isoFormat('MMMM')}} <small>{{$date->locale('pt_BR')->isoFormat('YYYY')}}</small> <i class="ti-pencil-alt"></i> <i class="ti-lock this-lock"></i></td>
                                @endforeach
                            </tr>
                            <tr class="table-active">
                                @foreach ($months as $value)
                                    <td style="text-align: center">Qty</td>
                                    <td style="text-align: center">Preço</td>
                                    <td style="text-align: center">MIX%</td>
                                @endforeach
                            </tr>
                            @foreach ($category->where('is_conf_cap', 1) as $value)
                                <tr class="table-primary">
                                    <td style="text-align: center" colspan="2">{{$value->name}}</td>
                                    @foreach ($months as $d)
                                        <td style="text-align: center" id="category-qtd-total-{{$value->id}}-{{date('Y-m', strtotime($d->date))}}">0</td>
                                        <td style="text-align: center" id="category-price-total-{{$value->id}}-{{date('Y-m', strtotime($d->date))}}">R$ 0,00</td>
                                        <td style="text-align: center" id="category-mix-total-{{$value->id}}-{{date('Y-m', strtotime($d->date))}}">0.0%</td>
                                    @endforeach
                                </tr>
                                @foreach ($value->setProductOnGroup as $set)
                                    @if ($set->is_visible)
                                    <tr>
                                        <td style="text-align: center">{{$set->resume}} @if($set->capacity == 1) <span class="label label-danger">Alta</span> @else <span class="label label-info">Baixa</span> @endif</td>
                                        <td style="text-align: center">@if ($set->productAirEvap) @if (substr($set->productAirEvap->model, -2) == '/I' or substr($set->productAirEvap->model, -2) == '/O') {{substr($set->productAirEvap->model, 0, -2)}} @else
                                                {{$set->productAirEvap->model}} @endif @endif</td>
                                        @foreach ($months as $d)
                                            <td style="text-align: center" id="cat-{{$value->id}}-product-{{$set->id}}-date-{{date('Y-m', strtotime($d->date))}}-qtd"><input disabled data-cat="{{$value->id}}" data-product="{{$set->id}}" data-date="{{date('Y-m', strtotime($d->date))}}" onkeyup="reCalcColumn(this)" class="qtd-td" type="text" value="0"></td>
                                            <td style="text-align: center" id="cat-{{$value->id}}-product-{{$set->id}}-date-{{date('Y-m', strtotime($d->date))}}-price">R$ 0,00</td>
                                            <td style="text-align: center" id="cat-{{$value->id}}-product-{{$set->id}}-date-{{date('Y-m', strtotime($d->date))}}-mix">0.0%</td>
                                        @endforeach
                                    </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            <tr class="table-success">
                                <td class="hl-cap" style="text-align: center" colspan="2">BAIXA CAPACIDADE</td>
                                @foreach ($months as $d)
                                    <td class="hl-cap" style="text-align: center" colspan="3" id="low-cap-total-{{date('Y-m', strtotime($d->date))}}">0.0%</td>
                                @endforeach
                            </tr>
                            <tr class="table-success">
                                <td class="hl-cap" style="text-align: center" colspan="2">ALTA CAPACIDADE</td>
                                @foreach ($months as $d)
                                    <td class="hl-cap" style="text-align: center" colspan="3" id="high-cap-total-{{date('Y-m', strtotime($d->date))}}">0.0%</td>
                                @endforeach
                            </tr>
                            @foreach ($category->where('is_conf_cap', 0) as $value)
                                <tr class="table-primary">
                                    <td style="text-align: center" colspan="2">{{$value->name}}</td>
                                    @foreach ($months as $d)
                                        <td style="text-align: center" id="category-qtd-total-{{$value->id}}-{{date('Y-m', strtotime($d->date))}}">0</td>
                                        <td colspan="2" style="text-align: center" id="category-price-total-{{$value->id}}-{{date('Y-m', strtotime($d->date))}}">R$ 0,00</td>
                                    @endforeach
                                </tr>
                                @foreach ($value->setProductOnGroup as $set)
                                    @if ($set->is_visible)
                                    <tr>
                                        <td style="text-align: center">{{$set->resume}}</td>
                                        <td style="text-align: center">@if ($set->productAirEvap) @if (substr($set->productAirEvap->model, -2) == '/I' or substr($set->productAirEvap->model, -2) == '/O') {{substr($set->productAirEvap->model, 0, -2)}} @else
                                                {{$set->productAirEvap->model}} @endif @endif</td>
                                        @foreach ($months as $d)
                                            <td style="text-align: center" id="cat-{{$value->id}}-product-{{$set->id}}-date-{{date('Y-m', strtotime($d->date))}}-qtd"><input disabled data-cat="{{$value->id}}" data-product="{{$set->id}}" data-date="{{date('Y-m', strtotime($d->date))}}" onkeyup="reCalcColumn(this)" class="qtd-td" type="text" value="0"></td>
                                            <td colspan="2" style="text-align: center" id="cat-{{$value->id}}-product-{{$set->id}}-date-{{date('Y-m', strtotime($d->date))}}-price">R$ 0,00</td>
                                        @endforeach
                                    </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            <tr style="background-color: #d9dada">
                                <td style="text-align: center" colspan="2">TOTAL/MÊS</td>
                                @foreach ($months as $d)
                                    <td style="text-align: center" colspan="3" id="month-total-{{date('Y-m', strtotime($d->date))}}">0</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: center; background-color: #d9dada" colspan="2">TIPO DE FRETE</td>
                                @foreach ($months as $d)
                                    <td style="text-align: center; background-color: #FFFFFF" colspan="3" id="shipping-type-{{date('Y-m', strtotime($d->date))}}"></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: center; background-color: #d9dada" colspan="2">VERBA/VPC/CONTRATO</td>
                                @foreach ($months as $d)
                                    <td style="text-align: center; background-color: #FFFFFF" colspan="3" id="vpc-{{date('Y-m', strtotime($d->date))}}">0%</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: center; background-color: #d9dada" colspan="2">PRAZO MÉDIO DE PAGAMENTO</td>
                                @foreach ($months as $d)
                                    <td style="text-align: center; background-color: #FFFFFF" colspan="3" id="payment-{{date('Y-m', strtotime($d->date))}}">0</td>
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
                                <tr class="tab-client-{{$key->client_id}} line-table">
                                    <td>{{$key->code}}</td>
                                    <td>{{$key->name}}</td>
                                    <td>
                                        @if ($key->version >= $version)
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
        <div class="modal-dialog modal-dialog-centered" style="max-width: 800px; !important">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Escolha o cliente</label>
                        <select name="client" class="form-control js-select2" style="width:100%" multiple>
                            @foreach ($clients as $client)
                                <option data-type-client="{{$client->type_client_name}}" data-tax-regime="{{$client->tax_regime_name}}" data-manager="@if($client->client_managers->count()) {{$client->client_managers[0]->salesman->short_name}} @endif" value="{{$client->id}}">{{$client->company_name}} ({{$client->code}}) {{$client->identity}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="/comercial/operacao/programation/all"><button type="button" class="btn btn-dark"> Sair</button></a>
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
    <script src="/elite/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        var yearmonth, yearmonthelem;
        var client_id = 0;
        var programation = {!! json_encode($programation) !!};

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
                    programation[yearmonth]['table'] = response.table_id;
                    $('#shipping-type-'+yearmonth).html(response.cif_fob_name);
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
            var validProgramation = Object.entries(programation);
            var hasProgramation = false;

            validProgramation.forEach(function(val) {
                if (val[1]['table'] > 0)
                    hasProgramation = true;
            });

            if (!hasProgramation) {
                return $error('Você precisa ao menos ter 1 mês escolhido para poder gerar a programação.');
            }
                @foreach ($months as $d)
            else if ($('#month-total-{{date('Y-m', strtotime($d->date))}}').html() == '0' && programation['{{date('Y-m', strtotime($d->date))}}']['table'] != '0') {

                return $error('Você precisa informar ao menos 1 produto no mês habilitado.');
            }
            @endforeach
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

        $(".js-select2").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return "Sem resultados...";
                },
                searching: function () {
                    return "Buscando resultados...";
                },
                loadingMore: function () {
                    return 'Carregando mais resultados...';
                },
                maximumSelected: function (args) {
                    return 'Você já selecionou o mês da programação';
                },
            }
        });

        $('.js-select2').change(function() {
            if ($(this).val() != '') {
                client_id = $(".js-select2 option:selected").val();
                $('#client_id').val($(".js-select2 option:selected").val());
                $('#client').html($(".js-select2 option:selected").text());
                $('#type_client').html($(".js-select2 option:selected").attr('data-type-client'));
                $('#tax_regime').html($(".js-select2 option:selected").attr('data-tax-regime'));
                $('#manager').html($(".js-select2 option:selected").attr('data-manager'));
                $('#modal-client').modal('toggle');

                //$('.line-table').hide();
                //$('.tab-client-'+client_id).show();
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
            $('.qtd-td').mask('0000', {reverse: false});
        });

        selectClient();
    </script>

@endsection
