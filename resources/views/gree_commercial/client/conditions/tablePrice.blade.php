@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/order/list">Home</a></li>
    <li><a href="#">Clientes</a></li>
    <li><a href="#">Condições comerciais</a></li>
    <li class="active">Tabela de preço</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-default" href="/commercial/client/conditions/table/edit/0">
                    <i class="fa fa-plus"></i>&nbsp; Criar novo
                </a>
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#filterModal">
                    <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                </a>
            </div>
        </div>
    </div><!-- End .inner-padding -->
</header>
<div class="window">
    <div class="inner-padding">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-wrapper">
                    <header>
                        <h3>TABELAS DE PREÇOS</h3>
                    </header>
                    <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                        <thead>
                            <tr>
                                <th scope="col" data-rt-column="Código">Código</th>
                                <th scope="col" data-rt-column="Nome">Nome</th>
                                <th scope="col" data-rt-column="Representante">Representante</th>
                                <th scope="col" data-rt-column="Atualizado em">Atualizado em</th>
                                <th scope="col" data-rt-column="Versão">Versão</th>
                                <th scope="col" data-rt-column="Status">Status</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesman_table_price as $key)
                            <tr> <?php $style = 'vertical-align: middle !important;text-align: center;'; ?>
                                <td style="{{$style}}">{{$key->code}}</td>
                                <td style="{{$style}}">{{$key->name}}</td>
                                <td>
                                    @if ($key->manual_table_price == 0)
										@if ($key->salesman)
										<a class="text-primary" href="/commercial/salesman/edit/{{$key->salesman->id}}">{{Str::limit($key->salesman->first_name .' '. $key->salesman->last_name, 18)}}</a>
										<br><b>{{$key->salesman->identity}}</b>
										@endif
                                    @else
                                      Criada internamente
                                    @endif
                                </td>
                                <td style="{{$style}}">{{date('d/m/Y H:i:s', strtotime($key->updated_at))}}</td>
                                <td style="{{$style}}">
                                    @if ($key->version < $version)
                                    <b>{{number_format($key->version, 2)}}</b>
                                    <div class="table-tooltip" title="" data-original-title="Tabela precisa ser editada e atualizada com novo preço!">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    @else
                                    <b>{{number_format($key->version, 2)}}</b>
                                    @endif
                                </td>
                                <td style="{{$style}}">
                                    @if ($key->version >= $version)
                                    <span class="label label-success">Atualizada</span>
                                    @else
                                    <span class="label label-warning">Desatualizada</span>
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
                        <?= $salesman_table_price->appends(getSessionFilters()[0]->toArray())->links(); ?>
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
                            <input type="text" id="code" class="form-control" placeholder="CTP-2" name="code" value="{{Session::get('filter_code')}}">
                        </div>
                        <div class="col-sm-12">
                            <label for="mtp">Tipo de tabela</label>
                            <select id="mtp" class="form-control" name="mtp">
                                <option></option>
                                <option value="1" @if (Session::get('filter_mtp') == 1) selected="selected" @endif>Criada internamente</option>
                                <option value="2" @if (Session::get('filter_mtp') == 2) selected="selected" @endif>Pelo representante</option>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('filter_status') == 1) selected="selected" @endif>Atualizado</option>
                                <option value="2" @if (Session::get('filter_status') == 2) selected="selected" @endif>Desatualizado</option>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="salesman">Representante</label>
                            <div class="input-group">
                                <input type="text" id="salesman" placeholder="Pesquisa por nome..." class="form-control" name="salesman" value="{{Session::get('filter_salesman')}}">
                                <div class="input-group-btn">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Pesquisar
                                            <span class="caret"></span>
                                        </button>
                                        <ul role="menu" class="dropdown-menu pull-right">
                                            <li>
                                                <a onclick="searchChanger(1, true)" href="#">Nome</a>
                                            </li>
                                            <li>
                                                <a onclick="searchChanger(1, false)" href="#">CNPJ/CPF</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="client">Cliente</label>
                            <div class="input-group">
                                <input type="text" id="client" placeholder="Pesquisa por nome..." class="form-control" name="client" value="{{Session::get('filter_client')}}">
                                <div class="input-group-btn">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Pesquisar
                                            <span class="caret"></span>
                                        </button>
                                        <ul role="menu" class="dropdown-menu pull-right">
                                            <li>
                                                <a onclick="searchChanger(2, true)" href="#">Nome</a>
                                            </li>
                                            <li>
                                                <a onclick="searchChanger(2, false)" href="#">CNPJ/CPF</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
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
    function searchChanger($field, $bool) {

        if ($field == 1) {

            if (!$bool) {
                var options = {
                    onKeyPress : function(cpfcnpj, e, field, options) {
                        var masks = ['000.000.000-009', '00.000.000/0000-00'];
                        var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                        $('input[name="salesman"]').mask(mask, options);
                    }
                };

                $('input[name="salesman"]').attr('placeholder', 'Pesquisa por CNPJ/CPF...');
                $('input[name="salesman"]').mask('000.000.000-009', options);
            } else {
                $('input[name="salesman"]').attr('placeholder', 'Pesquisa por nome...');
                $('input[name="salesman"]').unmask();
            }

            // Close dropdown;
            $('input[name="salesman"]').click();
        } else {

            if (!$bool) {
                var options = {
                    onKeyPress : function(cpfcnpj, e, field, options) {
                        var masks = ['000.000.000-009', '00.000.000/0000-00'];
                        var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                        $('input[name="client"]').mask(mask, options);
                    }
                };

                $('input[name="client"]').attr('placeholder', 'Pesquisa por CNPJ/CPF...');
                $('input[name="client"]').mask('000.000.000-009', options);
            } else {
                $('input[name="client"]').attr('placeholder', 'Pesquisa por nome...');
                $('input[name="client"]').unmask();
            }

            // Close dropdown;
            $('input[name="client"]').click();
        }
    }

    function action($this = '') {

        var json = JSON.parse($($this).attr('json-data'));

        if ($($this).val() == 1) {

            window.location.href = '/commercial/client/conditions/table/edit/'+ json.id;
        } else if ($($this).val() == 2) {

            bootbox.dialog({
                message: "Você realmente quer deletar a tabela '"+json.code+"'?",
                title: "Deletar grupo",
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
                            window.location.href = '/commercial/client/conditions/table/delete/'+json.id;
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
        $("#editSave").click(function (e) {
            $("#editModal").modal('toggle');
            block();
            $("#editForm").submit();

        });
        $("#client").addClass('menu-open');
        $("#clientConditions").addClass('menu-open');
        $("#clientConditionsTable").addClass('page-arrow active-page');
    });
</script>

@endsection
