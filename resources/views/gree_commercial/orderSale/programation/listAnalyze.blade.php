@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/dashboard">Home</a></li>
    <li><a href="/commercial/order/all">Pedido programado</a></li>
    <li class="active">Solicitações de aprovação</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#filterModal">
                    <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                </a>
            </div>
        </div>
    </div><!-- End .inner-padding -->
</header>
<div class="window">
    <div class="alert alert-block alert-inline-top alert-dismissable">
        <h4>AVISO!</h4>
        Essa página é destinada apenas para aprovação da <b>DIREÇÃO COMERCIAL</b> & <b>DIREÇÃO FINANCEIRA</b>. Outras aprovações abaixo serão feitas no painel do representante.
    </div>
    <div class="inner-padding">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-wrapper">
                    <header>
                        <h3>SOLICITAÇÕES</h3>
                    </header>
                    <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                        <thead>
                            <tr>
                                <th scope="col" data-rt-column="Código">Código</th>
                                <th scope="col" data-rt-column="Programação">Programação</th>
                                <th scope="col" data-rt-column="Vendendor">Vendendor</th>
                                <th scope="col" data-rt-column="Cliente">Cliente</th>
                                <th scope="col" data-rt-column="Criado em">Criado em</th>
                                <th scope="col" data-rt-column="Status">Status</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $key)
                            <tr>
                                <td>{{$key->code}}</td>
                                <td>
                                    @if ($key->programationMonth)
                                        <b>Código:</b> {{$key->programationMonth->programation->code}}
                                        <br>{{$key->programationMonth->programation->months}}
                                    @endif
                                </td>
                                <td>
                                    @if ($key->programationMonth)
										<a href="/commercial/salesman/list?code={{$key->programationMonth->programation->salesman->code}}" target="_blank" style="color: #428bca;">
                                        	{{$key->programationMonth->programation->salesman->short_name}}
										</a>	
                                    @endif
                                </td>
                                <td>
                                    @if ($key->programationMonth)
										<a href="/commercial/client/list?code={{$key->programationMonth->programation->client->code}}" target="_blank" style="color: #428bca;">
                                        	{{$key->programationMonth->programation->client->company_name}}
										</a>	
                                    @endif
                                </td>
                                <td>{{date('d/m/Y', strtotime($key->created_at))}}</td>
                                <td>
                                    <span class="label label-warning">Em análise</span>
                                </td>
                                <td>
                                    <select json-data="{{$key->id}}" onchange="action(this)" class="simpleselect form-control">
                                        <option></option>
                                        <option value="1">Análisar</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $order->appends(getSessionFilters()[2]->toArray())->links(); ?>
                    </ul>
                </div>
                <div class="spacer-50"></div>
            </div>
        </div>

    </div>
    <!-- End .inner-padding -->
</div>

<div id="filterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Filtrar dados</h4>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="filterData">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="code">Código do pedido</label>
                            <input type="text" name="code_order" value="" class="form-control" />
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="code">Código da programação</label>
                            <input type="text" name="code_programation" value="" class="form-control" />
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="subordinates">Vendedores</label>
                            <select name="subordinates" class="form-control">
                                <option value=""></option>
                                @foreach ($subordinates as $key)
                                    <option value="{{$key->id}}">{{$key->short_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="client">Cliente</label>
                            <select name="client" class="form-control">
                                <option value=""></option>
                                @foreach ($clients as $key)
                                    <option value="{{$key->id}}">{{$key->company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="start_date">Data</label>
                            <input type="text" name="start_date" value="" class="form-control myear" />
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
            </div>
            <div class="modal-footer" style="padding: 0;height: 76px;">
                <div data-dismiss="modal" style="float: left;width: 170px;text-align: center;position: relative;top: 0px;font-weight: bold;color: #ff0000;height: 76px;cursor: pointer; font-size: 16px;">
                    <span style="position: relative;top: 25px;">Fechar</span>
                </div>
            <div style="position: absolute;height: 76px;border-right: solid 1px #bbb;left: 170px;right: 0;width: 1px;"></div>
                <div  id="filterNow" style="float: right;width: 178px;text-align: center;position: relative;height:76px;font-weight: bold;color: #18b202;cursor: pointer;font-size: 16px;">
                    <span style="position: relative;top: 25px;">Filtrar</span>
                </div>
            </div>
        </div>
    </div>
 </div>

<script>
    function searchChanger($bool) {

        if ($bool) {
            var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('input[name="cnpj_rg"]').mask(mask, options);
                }
            };

            $('input[name="cnpj_rg"]').attr('placeholder', 'Pesquisa por CNPJ/CPF...');
            $('input[name="cnpj_rg"]').mask('000.000.000-009', options);
        } else {
            $('input[name="cnpj_rg"]').attr('placeholder', 'Pesquisa por RG...');
            $('input[name="cnpj_rg"]').unmask();
        }

        // Close dropdown;
        $('input[name="cnpj_rg"]').click();
    }

    function action($this = '') {

        var id = $($this).attr('json-data');

        if ($($this).val() == 1)
            window.open('/commercial/order/approv/view/'+id, '_self');

        $($this).val('');

    }
    $(document).ready(function () {
        $("#filterNow").click(function (e) {
            $("#filterModal").modal('toggle');
            block();
            $("#filterData").submit();

        });

        var options = {
            onKeyPress : function(cpfcnpj, e, field, options) {
                var masks = ['000.000.000-009', '00.000.000/0000-00'];
                var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                $('input[name="cnpj_rg"]').mask(mask, options);
            }
        };

        $('table').responsiveTables({
            columnManage: false,
            exclude: '.table-collapsible, .table-collapsible-open',
            menuIcon: '<i class="fa fa-bars"></i>',
            startBreakpoint: function(ui){
                //ui.item(element)
                ui.item.find('label').parents('.rt-responsive-row').hide();
            },
            endBreakpoint: function(ui){
                //ui.item(element)
                ui.item.find('label').parents('.rt-responsive-row').show();
            },
            onColumnManage: function(){}
        });

        $("#orderSale").addClass('menu-open');
        $("#order").addClass('menu-open');
        $("#orderApprov").addClass('page-arrow active-page');
    });
</script>

@endsection
