@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/order/list">Home</a></li>
    <li><a href="/commercial/client/list">Clientes</a></li>
    <li><a href="/commercial/client/list/analyze">Solicitações de aprovação</a></li>
    <li class="active">Análisando cliente</li>
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
                    <a href="#content-tab-1">Atualização para aprovar</a>
                </li>
                @if ($versions->count() > 1)
                <li class="">
                    <a href="#content-tab-2">Atual aprovado</a>
                </li>
                @endif
                <li class="">
                    <a href="#content-tab-3">Histórico de aprovações</a>
                </li>
                <li class="">
                    <a href="#content-tab-4">Documentos</a>
                </li>
				<li class="">
                    <a href="#content-tab-5">Intenção de compra</a>
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
                            <legend>Versão: {{$versions->first()->version}}</legend>
                            <iframe style="width: 100%; height: 1600px;" src="/commercial/client/approv/view/{{$id}}"></iframe>
                        </fieldset>
                        <div class="spacer-50"></div>
                    </div>
                </div>
            </div>
            <!-- End .inner-padding -->
        </div>
        @if ($versions->count() > 1)
        <div id="content-tab-2" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        @php ($ver = $versions->splice(1)->first()) @endphp
                        <fieldset>
                            <legend>Versão: {{$ver->version}}</legend>
                            <iframe style="width: 100%; height: 1600px;" src="/commercial/client/print/view/{{$id}}"></iframe>
                        </fieldset>
                        <div class="spacer-50"></div>
                    </div>
                </div>
            </div>
            <!-- End .inner-padding -->
        </div>
        @endif
        <div id="content-tab-3" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Escolha a versão para ver o histórico</label>
                        <select id="version_hist" class="form-control" style="width:100%">
                            <option value=""></option>
                            @foreach ($versions as $key)
                                <option value="{{$key->version}}">Versão: {{$key->version}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="spacer-10"></div>
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
                                    <th scope="col" data-rt-column="Status">Versão</th>
                                </tr>
                                </thead>
                                <tbody id="analyzes">
                                </tbody>
                            </table>
                        </div>
                        <div class="spacer-50"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="content-tab-4" class="tab-pane">
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
                                    <th scope="col" data-rt-column="Tipo do documento">Tipo do documento</th>
                                    <th scope="col" data-rt-column="URL">URL</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Contrato social</td>
                                    <td>
                                        @if ($documents->contractSocial->count() > 0)
                                            <b><a href="{{$documents->contractSocial->first()->url}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Balanço Patrimonial</td>
                                    <td>
                                        @if ($documents->balanceEquity->count() > 0)
                                            <b><a href="{{$documents->balanceEquity->first()->url}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Declaração de Regime</td>
                                    <td>
                                        @if ($documents->declaration_regime)
                                            <b><a href="{{$documents->declaration_regime}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cartão CNPJ</td>
                                    <td>
                                        @if ($documents->card_cnpj)
                                            <b><a href="{{$documents->card_cnpj}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cartão Inscrição Estadual</td>
                                    <td>
                                        @if ($documents->card_ie)
                                            <b><a href="{{$documents->card_ie}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Apresentação Comercial</td>
                                    <td>
                                        @if ($documents->apresentation_commercial)
                                            <b><a href="{{$documents->apresentation_commercial}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Procuração de representação legal</td>
                                    <td>
                                        @if ($documents->proxy_representation_legal)
                                            <b><a href="{{$documents->proxy_representation_legal}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
								<tr>
                                    <td>Balanço Patrimonial/DRE e Fluxo de Caixa 2º ano</td>
                                    <td>
                                        @if ($documents->balanceEquity2Year->count() > 0)
                                            <b><a href="{{$documents->balanceEquity2Year->first()->url}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Balanço Patrimonial/DRE e Fluxo de Caixa 3º ano</td>
                                    <td>
                                        @if ($documents->balanceEquity3Year->count() > 0)
                                            <b><a href="{{$documents->balanceEquity3Year->first()->url}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Certidão negativa de debitos - Federal</td>
                                    <td>
                                        @if ($documents->certificate_debt_negative_federal)
                                            <b><a href="{{$documents->certificate_debt_negative_federal}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Certidão negativa de debitos - Sefaz</td>
                                    <td>
                                        @if ($documents->certificate_debt_negative_sefaz)
                                            <b><a href="{{$documents->certificate_debt_negative_sefaz}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Certidão negativa de debitos - Trabalhistas</td>
                                    <td>
                                        @if ($documents->certificate_debt_negative_labor)
                                            <b><a href="{{$documents->certificate_debt_negative_labor}}" target="_blank">Visualizar</a></b>
                                        @else
                                            Não enviado
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="spacer-50"></div>
						<div class="spacer-50"></div>
                        <div class="spacer-50"></div>
                    </div>
                </div>
            </div>
        </div>
		<div id="content-tab-5" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset>
                            <div class="widget">
                                <header>
                                    <h2>Intenção de compra</h2>
                                </header>
                                <div>
                                   <div class="row ext-raster">
                                        <div class="col-sm-12">  
                                            <div class="inner-padding"> 
                                                <?= nl2br($client->buy_intention) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>    
                    </div>    
                </div>    
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

<form id="analyze-submit" method="post" action="/commercial/client/analyze_do">
<div id="analyze-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>

            <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="{{$id}}">
                    <input type="hidden" name="type_analyze" id="type_analyze">
                    <input type="hidden" name="password" id="pass">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Observação</label>
                            <textarea name="description" id="description" class="form-control noresizing"></textarea>
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
    function approv() {
        if ($('#pass_type').val() == '')
            return $error('você precisa preencher a senha para aprovar!');

        $('.modal-title').html('APROVAR CLIENTE');
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

        $('.modal-title').html('REPROVAR CLIENTE');
        $('#analyze-btn').html('Reprovar');
        $('#analyze-btn').removeClass('btn-success');
        $('#analyze-btn').addClass('btn-danger');
        $('#type_analyze').val(2);
        $('#pass').val($('#pass_type').val());
        $('#analyze-modal').modal('show');
    }
    function genHTML(object) {
        var html = '';
        for (let index = 0; index < object.length; index++) {
            const column = object[index];

            html += '<tr>';
            html += '<td>'+column.type_user+'</td>';
            html += '<td>'+column.name+'</td>';
            html += '<td>'+column.office+'</td>';
            html += '<td>';

            if (column.status == 1)
                html += '<span class="label label-success">Aprovado</span>';
            else
                html += '<span class="label label-danger">Reprovado</span>';

            html += '</td>';
            html += '<td>'+column.description+'</td>';
            html += '<td>'+column.version+'</td>';
            html += '</tr>';

        }

        return html;
    }
    function realodAnalyzes(object) {

        var html = '';
        html += genHTML(object.imdt);
		html += genHTML(object.revision);
		html += genHTML(object.judicial);
        html += genHTML(object.commercial);
        //html += genHTML(object.financy);

        $('#analyzes').html(html);
    }
    $(document).ready(function () {

        $('#analyze-btn').click(function () {
           if ($('#type_analyze').val() == "2" && $('#description').val() == '')
               return $error('Você precisa informar a observação, sobre sua análise.');

           $('#analyze-modal').modal('hide');
           block();

        });

        $('#version_hist').change(function () {
            if($('#version_hist').val() == '') {
                $('#analyzes').html('');
            } else {
                block();
                ajaxSend(`/commercial/client/analyze/history/approv`,{id: {{$id}}, version_hist: $('#version_hist').val()})
                    .then((response) => {
                        unblock();
                        if (response.data.length != 0)
                            realodAnalyzes(response.data);
                        else
                            $('#analyzes').html('');
                    })
                    .catch((error) => {
                        $error(error.message);
                        unblock();
                    });
            }
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

        $("#client").addClass('menu-open');
        $("#clientApprov").addClass('page-arrow active-page');
    });
</script>

@endsection
