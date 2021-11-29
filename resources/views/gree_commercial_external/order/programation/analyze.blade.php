@extends('gree_commercial_external.layout')

@section('page-css')

    <style>
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
        .badge-custom {
            font-size: 10px !important;
            padding: 2px 10px !important;
            position: relative;
            bottom: 2px;
            left: 5px;
        }

    </style>
@endsection

@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Análise de aprovação</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Pedidos</a></li>
                    <li class="breadcrumb-item active">Programados</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active tab1" data-toggle="tab" href="#tab1" role="tab">
                                <span class="hidden-sm-up"><i class="ti-shopping-cart"></i></span>
                                <span class="hidden-xs-down">Pedido programado para aprovação</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tab3" data-toggle="tab" href="#tab4" role="tab">
                                <span class="hidden-sm-up"><i class="ti-check-box"></i></span>
                                <span class="hidden-xs-down">Histórico de aprovações</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tab3" data-toggle="tab" href="#tab5" role="tab">
                                <span class="hidden-sm-up"><i class="ti-bolt-alt"></i></span>
                                <span class="hidden-xs-down">Processo de análise</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tab3" data-toggle="tab" href="#tab6" role="tab">
                                <span class="hidden-sm-up"><i class="ti-briefcase"></i></span>
                                <span class="hidden-xs-down">Comprovações</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tab3" data-toggle="tab" href="#tab7" role="tab">
                                <span class="hidden-sm-up"><i class="ti-dollar"></i></span>
                                <span class="hidden-xs-down">Condição comercial</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tab3" href="/comercial/operacao/programation/view/{{$order->programationMonth->programation_id}}?order_id={{$order->id}}" target="_blank">
                                <span class="hidden-sm-up"><i class="ti-calendar"></i></span>
                                <span class="hidden-xs-down">Ver programação</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tab3" data-toggle="tab" href="#tab8" role="tab">
                                <span class="hidden-sm-up"><i class="ti-package"></i></span>
                                <span class="hidden-xs-down">Mix de produtos</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active p-20" id="tab1" role="tabpanel">
                            <iframe style="width: 100%; height: 1600px; border:1px;" src="/comercial/operacao/order/print/view/{{$order->id}}"></iframe>
                        </div>
                        <div class="tab-pane p-20" id="tab4" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <div class="table-wrapper">
                                            <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                                <thead>
                                                <tr>
                                                    <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Análises</b></td>
                                                </tr>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-20" id="tab5" role="tabpanel">
                            <div class="profiletimeline">
                                @foreach ($arr_imdt as $key)
                                    <div class="sl-item">
                                        <div class="sl-left"> <img src="{{$key->picture}}" alt="user" class="img-circle"> </div>
                                        <div class="sl-right">
                                            <div><a href="javascript:void(0)" class="link">{{$key->full_name}}</a>
                                                @if ($order->orderImdAnalyze->where('salesman_id', $key->id)->first())
                                                    @if ($order->orderImdAnalyze->where('salesman_id', $key->id)->first()->is_approv == 1)
                                                        <span class="label label-success badge-custom">Aprovado</span>
                                                    @elseif ($order->orderImdAnalyze->where('salesman_id', $key->id)->first()->is_reprov == 1)
                                                        <span class="label label-danger badge-custom">Reprovado</span>
                                                    @else
                                                        <span class="label label-warning badge-custom">Aguardando</span>
                                                    @endif
                                                @else
                                                    <span class="label label-warning badge-custom">Aguardando</span>
                                                @endif
                                                <blockquote class="m-t-10">
                                                    {{$key->office}}
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if ($dir_commecrial)
                                    <div class="sl-item">
                                        <div class="sl-left"> <img src="{{$dir_commecrial->user->picture}}" alt="user" class="img-circle"> </div>
                                        <div class="sl-right">
                                            <div><a href="javascript:void(0)" class="link">{{$dir_commecrial->user->full_name}}</a>
                                                @if ($order->orderCommercialAnalyze)
                                                    @if ($order->orderCommercialAnalyze->is_approv == 1)
                                                        <span class="label label-success badge-custom">Aprovado</span>
                                                    @elseif ($order->orderCommercialAnalyze->is_reprov == 1)
                                                        <span class="label label-danger badge-custom">Reprovado</span>
                                                    @else
                                                        <span class="label label-warning badge-custom">Aguardando</span>
                                                    @endif
                                                @else
                                                    <span class="label label-warning badge-custom">Aguardando</span>
                                                @endif
                                                <blockquote class="m-t-10">
                                                    {{$dir_commecrial->user->office}}
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="tab-pane p-20" id="tab6" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <div class="table-wrapper">
                                            <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                                <thead>
                                                <tr>
                                                    <td colspan="2" style="background-color:#03a9f3;color: #fff;"><b>Arquivos para comprovação do pedido programado</b></td>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-20" id="tab7" role="tabpanel">
                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#conditions" role="tab" aria-selected="true">Condições aplicadas</a></li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#adjusts" role="tab" aria-selected="false">Reajustes mensal</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="conditions" role="tabpanel">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <div class="table-wrapper">
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
                                                            $table = json_decode($order->programationMonth->json_table_price);
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-20" id="adjusts" role="tabpanel">
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
                                                        $adjusts = json_decode($order->programationMonth->adjust_month);
                                                    @endphp
                                                    <tbody id="loadadjusts">
														@if ($order->programationMonth->adjust_month)
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
                        <div class="tab-pane p-20" id="tab8" role="tabpanel">
							@include('gree_commercial_external.components.products_mix', [
				'order' => $order, 
				'categories' => json_decode($order->programationMonth->programation->json_categories_products, true)])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <form id="analyze-submit" method="post" action="/comercial/operacao/order/analyze_do">
        <div id="analyze-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id" value="{{$order->id}}">
                        <input type="hidden" name="is_programmed" id="is_programmed" value="1">
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

@endsection

@section('page-scripts')

    <script>

        $('#analyze-btn').click(function () {
            if ($('#type_analyze').val() == "2" && $('#description').val() == '')
                return $error('Você precisa informar a observação, sobre sua análise.');

            $('#analyze-modal').modal('hide');
            block();

        });

        function seeProgramation(id_prog, id_order) {

            window.open('/commercial/programation/view/'+id_prog+'?order_id='+id_order, '_blank');
        }

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


            $("#client").addClass('menu-open');
            $("#clientApprov").addClass('page-arrow active-page');
        });
    </script>

@endsection
