@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/dashboard">Home</a></li>
    <li><a href="/commercial/order/confirmed/all">Pedido</a></li>
    <li><a href="/commercial/order/confirmed/approv">Solicitações de aprovação</a></li>
    <li class="active">Análisando pedido</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')

    <style>
        .analyze {
            position: fixed;
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
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            box-shadow: 0px 0px 10px rgb(0,0,0,0.1);
        }
    </style>
<div class="window">
    <div class="actionbar">
        <div class="pull-left">
            <a href="#" class="btn small-toggle-btn" data-toggle-sidebar="left"></a>
            <ul class="ext-tabs">
                <li class="active">
                    <a href="#content-tab-1">Pedido para aprovação</a>
                </li>
                <li class="">
                    <a href="#content-tab-2">Histórico de aprovações</a>
                </li>
                <li class="">
                    <a href="#content-tab-3">Comprovações</a>
                </li>
				<li class="">
                    <a href="#content-tab-4">Condição comercial</a>
                </li>
				<li class="">
					<a href="#content-tab-5">Mix de produtos</a>
				</li>
            </ul><!-- End .ext-tabs -->
        </div>
    </div>
    <div class="tab-content">
        <div id="content-tab-1" class="tab-pane active">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset>
                            <iframe style="width: 100%; height: 1600px;" src="/commercial/order/confirmed/print/view/{{$order->id}}"></iframe>
                        </fieldset>
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
                                <h3>ANÁLISES</h3>
                            </header>
                            <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                <thead>
                                <tr>
                                    <th scope="col" data-rt-column="Tipo do usuário">Tipo de usuário</th>
                                    <th scope="col" data-rt-column="Nome">Nome</th>
                                    <th scope="col" data-rt-column="Cargo">Cargo</th>
                                    <th scope="col" data-rt-column="Status">Status</th>
                                    <th scope="col" data-rt-column="Observação">Observação</th>
                                </tr>
                                </thead>
                                <tbody id="analyzes">
                                @foreach($order->orderImdAnalyze as $imdt)
                                    <tr>
                                        <td>
                                            Representante
                                        </td>
                                        <td>
                                            {{$imdt->salesman->short_name}}
                                        </td>
                                        <td>
                                            {{$imdt->office}}
                                        </td>
                                        <td>
                                            @if ($imdt->is_approv == 1)
                                                <span class="label label-success">Aprovado</span>
                                            @else
                                                <span class="label label-danger">Reprovado</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$imdt->description}}
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($order->orderCommercialAnalyze)
                                    <tr>
                                        <td>
                                            Usuário interno
                                        </td>
                                        <td>
                                            {{$order->orderCommercialAnalyze->user->short_name}}
                                        </td>
                                        <td>
                                            Diretor comercial
                                        </td>
                                        <td>
                                            @if ($order->orderCommercialAnalyze->is_approv == 1)
                                                <span class="label label-success">Aprovado</span>
                                            @else
                                                <span class="label label-danger">Reprovado</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$order->orderCommercialAnalyze->description}}
                                        </td>
                                    </tr>
                                @endif
                                
                                </tbody>
                            </table>
                        </div>
                        <div class="spacer-50"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="content-tab-3" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-wrapper">
                            <header>
                                <h3>DOCUMENTOS</h3>
                            </header>
                            <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                <thead>
                                <tr>
                                    <td colspan="2" style="background-color:#03a9f3;color: #fff;"><b>Arquivos para comprovação do pedido</b></td>
                                </tr>
                                <tr>
                                    <th scope="col" data-rt-column="Tipo do usuário">Nome</th>
                                    <th scope="col" data-rt-column="Nome">Visualizar</th>
                                </tr>
                                </thead>
                                <tbody id="analyzes">
                                @foreach($order->orderSalesAttach as $file)
                                    <tr>
                                        <td>
                                            {{$file->name}}
                                        </td>
                                        <td>
                                            <a href="{{$file->url}}" target="_blank">Clique aqui</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="spacer-50"></div>
                    </div>
                </div>
            </div>
        </div>
		<div id="content-tab-4" class="tab-pane">
			<ul class="nav nav-tabs customtab" role="tablist">
				<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#conditions" role="tab" aria-selected="true">Condições aplicadas</a></li>
				<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#adjusts" role="tab" aria-selected="false">Reajustes mensal</a></li>
			</ul>
			<div class="tab-content">
            	<div class="tab-pane active" id="conditions" role="tabpanel">
					<div class="inner-padding">
						<div class="row" style="margin-bottom: 8%;">
							<div class="col-sm-12">
								<div class="table-wrapper">
									<header>
										<h3>CONDIÇÃO COMERCIAL</h3>
									</header>
									<table class="table table-bordered table-striped" data-rt-breakpoint="600">
										<thead>
											<tr>
												<td colspan="2" style="background-color:#03a9f3;color: #fff;"><b>Valores da condição comercial</b></td>
											</tr>
											<tr>
												<th scope="col" data-rt-column="Nome do campo">Nome do campo</th>
												<th scope="col" data-rt-column="Valor">Valor</th>
											</tr>
										</thead>
										@php
										$table = json_decode($order->json_table_price);
										$obj_table = commercialTablePriceConvertValue($table);
										@endphp
										<tbody>
											<tr>
												<td>
													É programado?
												</td>
												<td>
													{{$obj_table->is_programmed}}
												</td>
											</tr>
											<tr>
												<td>
													Tipo de cliente
												</td>
												<td>
													{{$obj_table->type_client}}
												</td>
											</tr>
											<tr>
												<td>
													É suframa?
												</td>
												<td>
													{{$obj_table->is_suframa}}
												</td>
											</tr>
											<tr>
												<td>
													Desconto Extra
												</td>
												<td>
													{{$obj_table->descont_extra}}
												</td>
											</tr>
											<tr>
												<td>
													Carga completo
												</td>
												<td>
													{{$obj_table->charge}}
												</td>
											</tr>
											<tr>
												<td>
													Contrato / VPC
												</td>
												<td>
													{{$obj_table->contract_vpc}}
												</td>
											</tr>
											<tr>
												<td>
													Prazo médio
												</td>
												<td>
													{{$obj_table->average_term}}
												</td>
											</tr>
											<tr>
												<td>
													PIS / Confins
												</td>
												<td>
													{{$obj_table->pis_confis}}
												</td>
											</tr>
											<tr>
												<td>
													Tipo de entrega
												</td>
												<td>
													{{$obj_table->cif_fob}}
												</td>
											</tr>
											<tr>
												<td>
													ICMS
												</td>
												<td>
													{{$obj_table->icms}}
												</td>
											</tr>
											<tr>
												<td>
													Ajuste comercial
												</td>
												<td>
													{{$obj_table->adjust_commercial}}
												</td>
											</tr>
											<tr>
												<td>
													Data da condição
												</td>
												<td>
													{{$obj_table->date_condition}}
												</td>
											</tr>
											<tr>
												<td>
													Observação da condição
												</td>
												<td>
													{{$obj_table->description_condition}}
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="spacer-50"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane p-20" id="adjusts" role="tabpanel">
					<div class="inner-padding">
						<div class="row">
							<div class="col-sm-12">
								<div class="table-wrapper">
									<header>
									</header>
									<table class="table table-bordered table-striped" data-rt-breakpoint="600">
										<thead>
											<tr>
												<th scope="col" data-rt-column="Tipo de aplicação">Tipo de aplicação</th>
												<th scope="col" data-rt-column="Porcentagem">Porcentagem</th>
											</tr>
										</thead>
										@php
										$adjusts = json_decode($order->adjust_month);
										@endphp
										<tbody id="loadadjusts">
											@if ($order->adjust_month)
											@foreach($adjusts as $adj)
											<tr>
												<td>{{typeAdjuste($adj->type_apply)}}</td>
												<td>{{$adj->factor}}%</td>
											</tr>
											@endforeach
											@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
		<div id="content-tab-5" class="tab-pane">
			<div class="inner-padding">
				@include('gree_commercial.components.products_mix', ['order' => $order, 'categories' => json_decode($order->json_categories_products, true)])
			</div>
		</div>
    </div>
</div>

<div class="analyze">
    <div class="row" style="width: 100%;">
        <div class="col-sm-12" style="margin-top: 25px; margin-bottom: 20px;">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input class="form-control" id="pass_type"  type="password" placeholder="Digite sua senha...">
            </div>
        </div>
        <div class="col-6">
            <button onclick="approv()" class="btn btn-success" style="width: 50%;margin: 0;border-radius: 0;height: 45px; font-weight: bold">Aprovar</button>
        </div>
        <div class="col-6">
            <button onclick="reprov()" class="btn btn-danger" style="width: 50%;margin: 0;border-radius: 0;height: 45px;font-weight: bold">Reprovar</button>
        </div>
    </div>

</div>

    <form id="analyze-submit" method="post" action="/commercial/order/analyze_do">
        <div id="analyze-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id" value="{{$order->id}}">
                        <input type="hidden" name="is_programmed" id="is_programmed" value="0">
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
                        <button type="submit" id="analyze-btn" class="btn btn-success pull-right">Aprovar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

<script>
    $('#analyze-btn').click(function () {
        if ($('#type_analyze').val() == "2" && $('#description').val() == '')
            return $error('Você precisa informar a observação, sobre sua análise.');

        $('#analyze-modal').modal('hide');
        block();

    });

    function approv() {
        if ($('#pass_type').val() == '')
            return $error('você precisa preencher a senha para aprovar!');

        $('.modal-title').html('APROVAR PEDIDO');
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

        $('.modal-title').html('REPROVAR PEDIDO');
        $('#analyze-btn').html('Reprovar');
        $('#analyze-btn').removeClass('btn-success');
        $('#analyze-btn').addClass('btn-danger');
        $('#type_analyze').val(2);
        $('#pass').val($('#pass_type').val());
        $('#analyze-modal').modal('show');
    }

    $(document).ready(function () {

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
        $("#orderConfirmed").addClass('menu-open');
        $("#orderConfirmedApprov").addClass('page-arrow active-page');
    });
</script>

@endsection
