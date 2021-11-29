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
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Cliente</a></li>
                    <li class="breadcrumb-item active">Solicitações de aprovação</li>
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
                            <span class="hidden-xs-down">Atualização para aprovar</span>
                        </a>
                    </li>
                    @if ($versions->count() > 1)
                        <li class="nav-item">
                            <a class="nav-link tab2" data-toggle="tab" href="#tab2" role="tab">
                                <span class="hidden-xs-down">Atual aprovado</span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link tab3" data-toggle="tab" href="#tab3" role="tab">
                            <span class="hidden-xs-down">Documentos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tab3" data-toggle="tab" href="#tab4" role="tab">
                            <span class="hidden-xs-down">Histórico de aprovações</span>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab5" role="tab">
                            <span class="hidden-xs-down">Itenção de compra</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active p-20" id="tab1" role="tabpanel">
                        <h5 class="card-title ">Versão: <div class="label label-table label-warning">{{$versions->first()->version}}</div></h5>
                        <iframe style="width: 100%; height: 1600px; border:1px;" src="/comercial/operacao/client/approv/view/{{$id}}"></iframe>
                    </div>
                    @if ($versions->count() > 1)
                        <div class="tab-pane  p-20" id="tab2" role="tabpanel">
                            @php ($ver = $versions->splice(1)->first()) @endphp
                            <h5 class="card-title ">Versão: <div class="label label-table label-info">{{$ver->version}}</div></h5>
                            <iframe style="width: 100%; height: 1600px; border:1px;" src="/comercial/operacao/client/print/view/{{$id}}"></iframe>
                        </div>
                    @endif
                    <div class="tab-pane p-20" id="tab3" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" data-rt-breakpoint="600">
                                        <thead>
                                            <tr>
                                                <td colspan="6" style="background-color:#03a9f3;color: #fff;"><b>Documentos</b></td>
                                            </tr>
                                            <tr>
                                                <th scope="col">Descrição</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Contrato Social</td>
                                                <td>
                                                    @if(count($documents->contractSocial) > 0)
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if(count($documents->contractSocial) > 0) <a target="_blank" href="<?= $documents->contractSocial->last()->url ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Cartão CNPJ (Receita Federal)</td>
                                                <td>
                                                    @if($documents->card_cnpj != '')
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if($documents->card_cnpj != '')<a target="_blank" href="<?= $documents->card_cnpj ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Cartão de Inscrição Estadual</td>
                                                <td>
                                                    @if($documents->card_ie != '')
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if($documents->card_ie != '')<a target="_blank" href="<?= $documents->card_ie ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Balanço Patrimonial/DRE e Fluxo de Caixa</td>
                                                <td>
                                                    @if(count($documents->balanceEquity) > 0)
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if(count($documents->balanceEquity) > 0) <a target="_blank" href="<?= $documents->balanceEquity->last()->url ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Procuração dos representantes legais</td>
                                                <td>
                                                    @if($documents->proxy_representation_legal != '')
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if($documents->proxy_representation_legal != '')<a target="_blank" href="<?= $documents->proxy_representation_legal ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Declaração de regime de tributação</td>
                                                <td>
                                                    @if($documents->declaration_regime != '')
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if($documents->declaration_regime != '')<a target="_blank" href="<?= $documents->declaration_regime ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Apresentação comercial ou portfólio próprio da empresa</td>
                                                <td>
                                                    @if($documents->apresentation_commercial != '')
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if($documents->apresentation_commercial != '')<a target="_blank" href="<?= $documents->apresentation_commercial ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
											<tr>
                                                <td>Balanço Patrimonial/DRE e Fluxo de Caixa 2º ano</td>
                                                <td>
                                                    @if(count($documents->balanceEquity2Year) > 0)
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if(count($documents->balanceEquity2Year) > 0) <a target="_blank" href="<?= $documents->balanceEquity2Year->last()->url ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Balanço Patrimonial/DRE e Fluxo de Caixa 3º ano</td>
                                                <td>
                                                    @if(count($documents->balanceEquity3Year) > 0)
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if(count($documents->balanceEquity3Year) > 0) <a target="_blank" href="<?= $documents->balanceEquity3Year->last()->url ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Certidão negativa de debitos - Federal</td>
                                                <td>
                                                    @if($documents->certificate_debt_negative_federal != '')
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if($documents->certificate_debt_negative_federal != '')<a target="_blank" href="<?= $documents->certificate_debt_negative_federal ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Certidão negativa de debitos - Sefaz</td>
                                                <td>
                                                    @if($documents->certificate_debt_negative_sefaz != '')
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if($documents->certificate_debt_negative_sefaz != '')<a target="_blank" href="<?= $documents->certificate_debt_negative_sefaz ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Certidão negativa de debitos - Trabalhistas</td>
                                                <td>
                                                    @if($documents->certificate_debt_negative_labor != '')
                                                        <span class="label label-success">Enviado</span>
                                                    @else
                                                        <span class="label label-danger">Pendente</span>
                                                    @endif
                                                </td>
                                                <td>@if($documents->certificate_debt_negative_labor != '')<a target="_blank" href="<?= $documents->certificate_debt_negative_labor ?>">Visualizar</a>@else - @endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane p-20" id="tab4" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12">
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
                                                <th scope="col" data-rt-column="Status">Versão</th>
                                            </tr>
                                        </thead>
                                        <tbody id="analyzes"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="tab-pane p-20" id="tab5" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Itenção de compra</h4>
                                        <p class="card-text"><?= nl2br($client->buy_intention) ?></p>
                                    </div>
                                </div>
                            </div>    
                        </div>    
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

<form id="analyze-submit" method="post" action="/comercial/operacao/client/analyze_do">
    <div id="analyze-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                        <input type="hidden" name="id" id="id" value="{{$id}}">
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

@endsection

@section('page-scripts')

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

            if ($('#type_analyze').val() == "2" && $('#description').val() == '') {
                return $error('Você precisa informar a observação, sobre sua análise.')
            } else {

                block();
                $('#analyze-modal').modal('hide');
                $('#analyze-submit').submit();
            }
        });

        ajaxSend('/comercial/operacao/cliente/analise/historico/approv',{id: {{$id}}, version_hist: {{$versions->first()->version}}})
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
            }
        );

        $(".tab1, .tab2").click(function() {
            $("#card_approv").show();
        });

        $(".tab3").click(function() {
            $("#card_approv").hide();
        });

        $("#client").addClass('menu-open');
        $("#clientApprov").addClass('page-arrow active-page');
    });
</script>

@endsection
