@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/order/list">Home</a></li>
    <li><a href="/commercial/client/list">Clientes</a></li>
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
        Essa página é destinada apenas para aprovação da <b>REVISÃO INTERNA</b> & <b>DIREÇÃO JURIDICA</b> & <b>DIREÇÃO COMERCIAL</b> & <b>DIREÇÃO FINANCEIRA</b>. Outras aprovações abaixo serão feitas no painel do representante.
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
                                <th scope="col" data-rt-column="Nome">Nome</th>
                                <th scope="col" data-rt-column="CNPJ / RG">CNPJ / RG</th>
                                <th scope="col" data-rt-column="Grupo">Grupo</th>
                                <th scope="col" data-rt-column="Status">Status</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($analyze as $key)
                            <tr>
                                <td>{{$key->code}}</td>
                                <td>{{$key->company_name}}</td>
                                <td>{{$key->identity}}</td>
								<td>{{ $key->client_group->first() ? $key->client_group->first()->name : '' }}</td>
                                <td>
                                    @if ($key->client_version->first()->version == 1)
                                    <span class="label label-success">Novo</span>
                                    @else
                                    <span class="label label-warning">Atualização</span>
                                    @endif
                                </td>
                                <td>
                                    <select json-data="{{$key->id}}" onchange="action(this)" class="simpleselect form-control">
                                        <option></option>
                                        <option value="1">Análisar</option>
                                        <option value="2">Impr. Atual</option>
                                        <option value="3">Impr. Alteração</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $analyze->appends(getSessionFilters()[0]->toArray())->links(); ?>
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
                        <div class="col-sm-12">
                            <label for="code">Código</label>
                            <input type="text" id="code" class="form-control" value="{{Session::get('client_code')}}" name="code"/>
                        </div>
                        <div class="col-sm-12">
                            <label for="fantasy_name">Nome fantasia</label>
                            <input type="text" id="fantasy_name" class="form-control" value="{{Session::get('client_fantasy_name')}}" name="fantasy_name"/>
                        </div>
                        <div class="col-sm-12">
                            <label for="salesman">CNPJ / RG</label>
                            <div class="input-group">
                                <input type="text" id="cnpj_rg" placeholder="Pesquisa por CNPJ/CPF..." class="form-control" name="cnpj_rg" value="{{Session::get('client_cnpj_rg')}}">
                                <div class="input-group-btn">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Pesquisar
                                            <span class="caret"></span>
                                        </button>
                                        <ul role="menu" class="dropdown-menu pull-right">
                                            <li>
                                                <a onclick="searchChanger(true)" href="#">CNPJ/CPF</a>
                                            </li>
                                            <li>
                                                <a onclick="searchChanger(false)" href="#">RG</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="group">Grupo</label>
                            <select id="group" class="form-control js-select2" name="group" style="width: 100%" multiple>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('client_status') == 1) selected="selected" @endif>Atualizado</option>
                                <option value="2" @if (Session::get('client_status') == 2) selected="selected" @endif>Novo</option>
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
            window.open('/commercial/client/analyze/'+id, '_self');
        else if ($($this).val() == 2)
            window.open('/commercial/client/print/view/'+id, '_blank');
        else if ($($this).val() == 3)
            window.open('/commercial/client/approv/view/'+id, '_blank');

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

        $(".js-select2").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {

                    return 'Grupo não existe...';
                }
            },
            ajax: {
                url: '/commercial/client/group/dropdown',
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

        $('input[name="cnpj_rg"]').attr('placeholder', 'Pesquisa por CNPJ/CPF...');
        $('input[name="cnpj_rg"]').mask('000.000.000-009', options);

        $("#client").addClass('menu-open');
        $("#clientApprov").addClass('page-arrow active-page');
    });
</script>

@endsection
