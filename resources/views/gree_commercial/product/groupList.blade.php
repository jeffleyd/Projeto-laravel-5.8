@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/order/list">Home</a></li>
    <li><a href="/commercial/product/set/list">Produtos</a></li>
    <li class="active">Grupos</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
    <style>
        .tooltip {
            z-index: 6000 !important;
        }
    </style>
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-default" onclick="action()" href="#">
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
                        <h3>GRUPOS</h3>
                    </header>
                    <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                        <thead>
                            <tr>
                                <th scope="col" data-rt-column="Código">Código</th>
                                <th scope="col" data-rt-column="Posição">Posição</th>
                                <th scope="col" data-rt-column="Presente no MIX">Presente no MIX</th>
                                <th scope="col" data-rt-column="Nome">Nome</th>
                                <th scope="col" data-rt-column="Qtd Conjuntos">Qtd Conjuntos</th>
                                <th scope="col" data-rt-column="Status">Status</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($set_product_group as $key)
                            <tr>
                                <td>CPG-{{$key->id}}</td>
                                <td>{{$key->position}}</td>
                                <td>
                                    @if ($key->is_conf_cap)
                                        <span class="label label-success">Presente</span>
                                    @else
                                        <span class="label label-warning">Não</span>
                                    @endif
                                </td>
                                <td>{{$key->name}}</td>
                                <td><a class="text-primary" href="/commercial/product/set/list?group={{$key->id}}">{{$key->setProductOnGroup->count()}}</a></td>
                                <td>
                                    @if ($key->is_active)
                                    <span class="label label-success">Ativo</span>
                                    @else
                                    <span class="label label-danger">Desativado</span>
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
                        <?= $set_product_group->appends(getSessionFilters()[0]->toArray())->links(); ?>
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
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('filter_status') == 1) selected="selected" @endif>Ativo</option>
                                <option value="2" @if (Session::get('filter_status') == 2) selected="selected" @endif>Desativado</option>
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

 <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title editTitle">Novo grupo</h4>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post" action="/commercial/product/group/edit_do">
                    <input type="hidden" id="id" name="id" value="0">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Código</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="code" name="code" readonly="readonly" value="CPG-3" class="form-control" />
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Posição</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="number" min="1" name="position" id="position" class="form-control" />
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Nome</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="name" id="name" class="form-control" />
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Presente no MIX? <div class="table-tooltip" title="" data-original-title="Caso seja 'SIM', no momento da criação da programação, essa categoria estará presente no calculo do MIX.">
                                                        <i class="fa fa-info-circle"></i>
                                                    </div>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <select class="form-control" id="is_conf_cap" name="is_conf_cap">
                                <option value="0">Não</option>
                                <option value="1">Sim</option>
                            </select>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Status</label>
                        </div>
                        <div class="col-sm-8">
                            <select class="form-control" id="is_active" name="is_active">
                                <option value="1">Ativo</option>
                                <option value="0">Desativado</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button class="btn btn-primary pull-right" id="editSave">Salvar</button>
            </div>
        </div>
    </div>
 </div>
<script>
    function action($this = '') {
        $('#editForm').each (function(){
            this.reset();
        });

        if ($this == '') {
            $("#editModal").modal();
            $("#id").val(0);
            $("#code").attr('readonly', 'readonly');
            $("#code").val('CPG-{{$last_id}}');
            $(".editTitle").html('Novo grupo');
            return
        }

        var json = JSON.parse($($this).attr('json-data'));

        if ($($this).val() == 1) {

            $("#id").val(json.id);
            $("#code").val(json.code);
            $("#position").val(json.position);
            $("#name").val(json.name);
            $("#is_active").val(json.is_active);
            $("#is_conf_cap").val(json.is_conf_cap);

            $(".editTitle").html('Editando grupo: '+json.name);
            $("#editModal").modal();
        } else if ($($this).val() == 2) {

            bootbox.dialog({
                message: "Você realmente quer deletar '"+json.name+"'? Todos os conjuntos vinculados a ele, sairam da criação do pedido.",
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
                            window.location.href = '/commercial/product/group/delete/'+json.id;
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

        $("#product").addClass('menu-open');
        $("#productGroup").addClass('page-arrow active-page');
    });
</script>

@endsection
