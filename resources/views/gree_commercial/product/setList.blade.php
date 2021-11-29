@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/order/list">Home</a></li>
    <li class="active">Produtos</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('version')
<p>Versão: <b>{{number_format($version,2)}}</b></p>
@endsection

@section('content')
<style>
    .block-price {
       padding: 10px;
       margin: 15px;
       border: solid 1px;
       text-align: center;
       max-width: 160px;
    }

    .bfr {
       background: #d9e6f6;
    }

    .bqf {
       background: #f5f5f0;
    }

    .padding-left-block {
       padding: 0px;
       padding-left: 35px;
    }

    .padding-right-block {
       padding: 0px;
       padding-right: 35px;
    }

    @media only screen and (max-width: 600px) {
       .padding-left-block {
           padding-right: 35px;
       }

       .padding-right-block {
           padding-left: 35px;
       }

       .block-price {
           max-width: none;
       }
   }

   .select2-container--default .select2-selection--multiple {
       border-radius: 0px;
   }
   </style>
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-default" href="/commercial/product/set/edit/0">
                    <i class="fa fa-plus"></i>&nbsp; Criar novo
                </a>
				<!--
                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#saveModal">
                    <i class="fa fa-floppy-o" style="color: #ffffff;"></i>&nbsp; Salvar
                </a>
	 			
                <a class="btn btn-warning" href="#" data-toggle="modal" data-target="#priceModal">
                    <i class="fa fa-money" style="color: #ffffff;"></i>&nbsp; Reajuste
                </a>
				-->
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#filterModal">
                    <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                </a>
            </div>
        </div>
    </div><!-- End .inner-padding -->
</header>
<div class="window">
    <div class="actionbar">
        <div class="pull-left">
            <ul class="ext-tabs">
                <li class="active">
                    <a href="#content-tab-1" tab-position="1" class="tab_item">Lista de conjuntos</a>
                </li>
				<!--
                <li>
                    <a href="#content-tab-2" tab-position="2" class="tab_item">Histórico de ajustes</a>
                </li>
                <li>
                    <a href="#content-tab-3" tab-position="3" class="tab_item">Histórico de preços</a>
                </li>-->
            </ul><!-- End .ext-tabs -->
        </div>
    </div>
    <div class="tab-content">
        <div id="content-tab-1" class="tab-pane active">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-wrapper">
                            <header>
                                <h3 style="font-size: 17px;font-weight: 100;">Lista de conjuntos cadastrados</h3>
                            </header>
                            <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                                <thead>
                                    <tr>
                                        <th scope="col" data-rt-column="Código">Código</th>
                                        <th scope="col" data-rt-column="Grupo">Grupo</th>
                                        <th scope="col" data-rt-column="Evap">Evap</th>
                                        <th scope="col" data-rt-column="Cond">Cond</th>
                                        <th scope="col" data-rt-column="Resumo">Resumo</th>
                                        <th scope="col" data-rt-column="Status">Status</th>
                                        <th scope="col" data-rt-column="Ações">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($set_product as $key)
                                    <tr> <?php $style = 'vertical-align: middle !important;text-align: center;'; ?>
                                        <td style="{{$style}}">CP-{{$key->id}}</td>
                                        <td style="{{$style}}">@if ($key->setProductOnGroup->first()) {{$key->setProductOnGroup->first()->name}} @endif</td>
                                        <td>
                                            @if ($key->productAirEvap)
                                            {{$key->productAirEvap->model}}
                                            @endif
                                            <br><b>Preço:</b> {{number_format($key->evap_product_price, 2, ',', '.')}}
                                        </td>
                                        <td>
                                            @if ($key->productAirCond)
                                            {{$key->productAirCond->model}}
                                            @endif
                                            <br><b>Preço:</b> {{number_format($key->cond_product_price, 2, ',', '.')}}
                                        </td>
                                        <td style="{{$style}}">{{$key->resume}}</td>
                                        <td style="{{$style}}">
                                            @if ($key->is_active)
                                            <span class="label label-success">Ativo</span>
                                            @else
                                            <span class="label label-danger">Desativado</span>
                                            @endif
                                            @if ($key->is_qf == 1)
                                            <span class="label label-warning">Quente/Frio</span>
                                            @else
                                            <span class="label label-info">Frio</span>
                                            @endif
                                            @if ($key->capacity == 1)
                                                <span class="label label-danger">Alta</span>
                                            @else
                                                <span class="label label-info">Baixa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                                <option></option>
                                                <option value="1">Editar</option>
                                                <option value="2">Deletar</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-right" style="margin-top: 20px;">
                            <ul class="pagination">
                                <?= $set_product->appends(getSessionFilters()[0]->toArray())->links(); ?>
                            </ul>
                        </div>
                        <div class="spacer-50"></div>
                    </div>
                </div>

            </div>
            <!-- End .inner-padding -->
        </div>
        <div id="content-tab-2" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-wrapper">
                            <header>
                                <h3>HISTÓRICO DE AJUSTES</h3>
                            </header>
                            <table class="table table-bordered table-striped" id="tb2" data-rt-breakpoint="600">
                                <thead>
                                    <tr>
                                        <th scope="col" data-rt-column="ID">ID</th>
                                        <th scope="col" data-rt-column="Responsável">Responsável</th>
                                        <th scope="col" data-rt-column="Tipo">Tipo</th>
                                        <th scope="col" data-rt-column="Fator">Fator</th>
                                        <th scope="col" data-rt-column="Operação">Operação</th>
                                        <th scope="col" data-rt-column="Novo valor">Novo valor</th>
                                        <th scope="col" data-rt-column="Antigo valor">Antigo valor</th>
                                        <th scope="col" data-rt-column="Criado em">Criado em</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($set_product_adjust as $key)
                                    <tr> <?php $style = 'vertical-align: middle !important;text-align: center;'; ?>
                                        <td style="{{$style}}">{{$key->id}}</td>
                                        <td style="{{$style}}"><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                        <td style="{{$style}}">
                                            @if ($key->type == 1)
                                            <span class="label label-primary">Grupo</span>
                                            @elseif ($key->type == 2)
                                            <span class="label label-info">Frio</span>
                                            @elseif ($key->type == 3)
                                            <span class="label label-warning">Quente/Frio</span>
                                            @else
                                            <span class="label label-default">Todos</span>
                                            @endif
                                        </td>
                                        <td style="{{$style}}">{{$key->factor}}%</td>
                                        <td style="{{$style}}">
                                            @if ($key->is_sum == 1)
                                            <span class="label label-success">Acrescentou</span>
                                            @else
                                            <span class="label label-danger">Deduziu</span>
                                            @endif
                                        </td>
                                        <td style="{{$style}}">R$ {{number_format($key->new_amount, 2, ',', '.')}}</td>
                                        <td style="{{$style}}">R$ {{number_format($key->old_amount, 2, ',', '.')}}</td>
                                        <td style="{{$style}}">{{date('d/m/Y H:i:s', strtotime($key->created_at))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-right" style="margin-top: 20px;">
                            <ul class="pagination">
                                <?= $set_product_adjust->appends([
                                    'tab' => 2,
                                ])->links() ?>
                            </ul>
                        </div>
                        <div class="spacer-50"></div>
                    </div>
                </div>

            </div>
            <!-- End .inner-padding -->
        </div>
        <div id="content-tab-3" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12" style="text-align: center;margin-bottom: 15px;">
                        <label for="table_id">Buscar tabela</label>
                        <form action="{{Request::url()}}" method="get">
                        <div class="input-group">
                            <select id="table_id" class="form-control js-select23" name="table_id" style="width:100%" multiple>
                            </select>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit" style="width: 150px;height: 33px;">Buscar!</button>
                            </span>
                        </div>
                        </form>
                    </div>
                    <div class="col-sm-12">
                        <fieldset>
                            @if ($table)
                            <legend><span style="position: relative;top: 7px;">{{$table->name}}</span> <a onclick="DeleteTableProduct({{$table->id}}, '{{$table->name}}')" href="#" class="btn btn-danger btn-circle"><i style="color:white" class="fa fa-times"></i></a></legend>
                            @endif
                            <div class="row">
                                @if ($table)
                                <?php $order_total = 0; ?>
                                @foreach ($table->collect as $item)
                                <div class="col-sm-12">
                                    <fieldset style="text-align: center;padding: 5px;margin: 15px;background: #f6ebd5;">
                                       <b>{{$item->name}}</b>
                                    </fieldset>
                                </div>
                                <div class="col-sm-6 padding-left-block">
                                    <fieldset>
                                        <div class="row">
                                        @foreach ($item->set_product_on_group as $set)
                                            @if ($set->is_qf == 0)
                                            <?php $order_total = $order_total + ($set->evap_product_price + $set->cond_product_price) ?>
                                            <div class="col-sm-4 block-price bfr">
                                                <div class="title">
                                                    <b>{{$set->resume}}</b>
                                                </div>
                                                <div data-price="{{number_format($set->evap_product_price + $set->cond_product_price, 2, '.', '')}}" class="price_fr">
                                                    R$ {{number_format($set->evap_product_price + $set->cond_product_price, 2, '.', '')}}
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-sm-6 padding-right-block">
                                    <fieldset>
                                        <div class="row">
                                            @foreach ($item->set_product_on_group as $set)
                                                @if ($set->is_qf == 1)
                                                <?php $order_total = $order_total + ($set->evap_product_price + $set->cond_product_price) ?>
                                                <div class="col-sm-4 block-price bqf">
                                                    <div class="title">
                                                        <b>{{$set->resume}}</b>
                                                    </div>
                                                    <div data-price="{{number_format($set->evap_product_price + $set->cond_product_price, 2, '.', '')}}" class="price_qf">
                                                        R$ {{number_format($set->evap_product_price + $set->cond_product_price, 2, '.', '')}}
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </fieldset>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <!-- End .inner-padding -->
            <div class="spacer-10"></div>
            @if ($table)
            <div class="row">
                <div class="col-sm-12 pull-right" style="text-align: center;font-size: 20px;">
                    TOTAL DO PREÇO BASE: <b>R$ <span>{{number_format($order_total, 2, ',', '.')}}</span></b>
                </div>
            </div>
            @endif

        </div>

        <div class="spacer-50"></div>
    </div>

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
                        <div class="col-sm-12">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('filter_status') == 1) selected="selected" @endif>Ativo</option>
                                <option value="2" @if (Session::get('filter_status') == 2) selected="selected" @endif>Desativado</option>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="group">Grupo</label>
                            <select id="group" class="form-control js-select2" name="group" style="width:100%" multiple>
                            </select>
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

 <div id="priceModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">REAJUSTE DE PREÇOS</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="/commercial/product/adjust" id="priceData">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="type">Tipo do reajuste</label>
                            <select id="type" class="form-control" name="type">
                                <option></option>
                                <option value="1">GRUPO</option>
                                <option value="2">FRIO</option>
                                <option value="3">QUENTE FRIO</option>
                                <option value="4">TODOS</option>
                            </select>
                        </div>
                        <div class="col-sm-12 price_group" style="display:none">
                            <label for="price_group">Escolha uma opção</label>
                            <select id="price_group" class="form-control js-select21" name="price_group[]" style="width:100%" multiple>
                            </select>
                        </div>
                        <div class="col-sm-8" style="padding-right: 10px;">
                            <label for="amount">Porcentagem</label>
                            <input type="text" id="amount" class="form-control" name="amount" placeholder="0.00">
                        </div>
                        <div class="col-sm-4" style="padding-left: 10px;">
                            <label for="is_sum">Tipo</label>
                            <select id="is_sum" class="form-control" name="is_sum">
                                <option value="1">Mais</option>
                                <option value="2">Menos</option>
                            </select>
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
                <div  id="priceNow" style="float: right;width: 178px;text-align: center;position: relative;height:76px;font-weight: bold;color: #18b202;cursor: pointer;font-size: 16px;">
                    <span style="position: relative;top: 25px;">Ajustar</span>
                </div>
            </div>
        </div>
    </div>
 </div>

 <div id="saveModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">SALVAR TABELA DE PREÇOS</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="/commercial/product/save" id="saveData">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="table_name">Nome da tabela</label>
                            <input type="text" id="table_name" class="form-control" name="table_name">
                            <p>Informe um nome para sua tabela de preço.</p>
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
                <div  id="saveNow" style="float: right;width: 178px;text-align: center;position: relative;height:76px;font-weight: bold;color: #18b202;cursor: pointer;font-size: 16px;">
                    <span style="position: relative;top: 25px;">Salvar</span>
                </div>
            </div>
        </div>
    </div>
 </div>
<script>
    function DeleteTableProduct(id, name) {
        bootbox.dialog({
                message: "Você realmente quer deletar a tabela: '"+name+"'?",
                title: "Deletar tabela",
                buttons: {
                    danger: {
                        label: "Cancelar",
                        className: "btn-default",
                        callback: function(){}
                    },
                    main: {
                        label: "Confirmar",
                        className: "btn-primary",
                        callback: function() {
                            block();
                            window.location.href = '/commercial/product/save/delete/'+id;
                        }
                    }
                }
            });
    }
    function action($this = '') {

        var json = JSON.parse($($this).attr('json-data'));

        if ($($this).val() == 1) {

            window.location.href = '/commercial/product/set/edit/'+json.id;
        } else if ($($this).val() == 2) {

            bootbox.dialog({
                message: "Você realmente quer deletar '"+json.resume+"'? O conjunto sairá da criação do pedido.",
                title: "Deletar conjunto",
                buttons: {
                    danger: {
                        label: "Cancelar",
                        className: "btn-default",
                        callback: function(){}
                    },
                    main: {
                        label: "Confirmar",
                        className: "btn-primary",
                        callback: function() {
                            block();
                            window.location.href = '/commercial/product/set/delete/'+json.id;
                        }
                    }
                }
            });
        }

        $($this).val('');

    }
    $(document).ready(function () {
        $("#filterNow").click(function (e) {
            $("#filterModal").modal('toggle');
            block();
            $("#filterData").submit();

        });

        $("#priceNow").click(function (e) {
            if ($("#type").val() == "") {

                return $error('Você precisa informar o tipo de reajuste.');

            } else if ($("#type").val() == 1 && $("#price_group").val() == null) {

                return $error('Você precisa escolhar um grupo para aplicar a regra.');
            } else if ($("#amount").val() == "") {

                return $error('Você precisa informar a porcentagem de reajuste.');
            }
            bootbox.dialog({
                message: "Você realmente quer realizar o reajuste dos preços? Certifique-se que já salvou!",
                title: "Confirmar reajuste",
                buttons: {
                    danger: {
                        label: "Cancelar",
                        className: "btn-default",
                        callback: function(){}
                    },
                    main: {
                        label: "Confirmar",
                        className: "btn-primary",
                        callback: function() {
                            $("#priceModal").modal('toggle');
                            block();
                            $("#priceData").submit();
                        }
                    }
                }
            });


        });

        $("#saveNow").click(function (e) {
            if ($("#table_name").val() == "") {

                return $error('Você precisa informar um nome para sua tabela.');
            }
            bootbox.dialog({
                message: "Você está salvando sua tabela atual de preços, deseja continuar?",
                title: "Salvar tabela de preços",
                buttons: {
                    danger: {
                        label: "Cancelar",
                        className: "btn-default",
                        callback: function(){}
                    },
                    main: {
                        label: "Continuar",
                        className: "btn-primary",
                        callback: function() {
                            $("#saveModal").modal('toggle');
                            block();
                            $("#saveData").submit();
                        }
                    }
                }
            });


        });

        $('#tb2').responsiveTables({
            columnManage: true,
            exclude:'',
            menuIcon: '<i class="fa fa-bars"></i>',
            startBreakpoint: function(ui){
                //ui.item(element)
            },
            endBreakpoint: function(ui){
                //ui.item(element)
            },
            onColumnManage: function(){}
        });

        $("#type").change(function (e) {
            $("#price_group").val(0).trigger("change");
            if ($("#type").val() == 1) {

                $(".price_group").show();
            } else {

                $(".price_group").hide();
            }

        });

        $(".js-select2").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {

                    return 'Grupo não existe...';
                }
            },
            ajax: {
                url: '/commercial/product/group/dropdown',
                data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                }

                // Query parameters will be ?search=[term]&page=[page]
                return query;
                }
            }
        });

        $(".js-select23").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {

                    return 'Tabela salva não existe...';
                }
            },
            ajax: {
                url: '/commercial/product/save/dropdown',
                data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                }

                // Query parameters will be ?search=[term]&page=[page]
                return query;
                }
            }
        });

        $(".js-select21").select2({
            language: {
                noResults: function () {

                    return 'Grupo não existe...';
                }
            },
            ajax: {
                url: '/commercial/product/group/dropdown',
                data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                }

                // Query parameters will be ?search=[term]&page=[page]
                return query;
                }
            }
        });

        $(".tab_item").click(function (e) {
            var currentUrl = '{{Request::url()}}';
            var url = new URL(currentUrl);
            url.searchParams.set("tab", $(this).attr('tab-position')); // setting your param
            var newUrl = url.href;
            history.pushState('', '', newUrl);
        });

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

        // set tab
        @if (Session::has('tab'))
        $('#content > div.window > div.actionbar > div > ul > li:nth-child({{Session::get('tab')}}) > a').tab('show');
        @endif

        $('input[name="amount"]').mask('000.00', {reverse: true});
        $("#product").addClass('menu-open');
        $("#productSet").addClass('menu-open');
        $("#productSetAll").addClass('page-arrow active-page');
    });
</script>

@endsection
