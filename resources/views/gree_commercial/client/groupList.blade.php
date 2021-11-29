@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="/commercial/client/group/list">Clientes</a></li>
    <li class="active">Grupos</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
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
                                <th scope="col" data-rt-column="Nome">Nome</th>
                                <th scope="col" data-rt-column="Status">Status</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($client_group as $key)
                            <tr>
                                <td>{{$key->code}}</td>
                                <td>{{$key->name}}</td>
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
                        <?= $client_group->appends(getSessionFilters()[0]->toArray())->links(); ?>
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
                            <label for="name">Code</label>
                            <input type="text" name="fcode" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="name">Nome</label>
                            <input type="text" name="fname" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="fstatus">
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
                <form id="editForm" method="post" action="/commercial/client/group/edit_do">
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
                            <label>Nome</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="name" id="name" class="form-control" />
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
            $("#code").val('CCG-{{$total}}');
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

            $(".editTitle").html('Editando grupo: '+json.name);
            $("#editModal").modal();
        } else if ($($this).val() == 2) {

            bootbox.dialog({
                message: "Você realmente quer deletar o grupo '"+json.name+"'?",
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
                            window.location.href = '/commercial/client/group/delete/'+json.id;
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

            if ($('input[name="name"]').val() == "") {
                return $error('Informe o nome do grupo.');
            }
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

        $("#client").addClass('menu-open');
        $("#clientGroup").addClass('page-arrow active-page');
    });
</script>

@endsection
