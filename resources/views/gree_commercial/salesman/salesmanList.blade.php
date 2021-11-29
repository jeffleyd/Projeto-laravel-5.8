    @extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="/commercial/salesman/list">Representantes</a></li>
    <li class="active">Todos</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<style>
    th {
        text-align: center;
    }
    .table>tbody>tr>td {
        vertical-align: middle;
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
                        <h3>REPRESENTANTES</h3>
                    </header>
                    <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                        <thead>
                            <tr>
                                <th scope="col" data-rt-column="Código">Código</th>
                                <th scope="col" data-rt-column="Nome">Nome</th>
                                <th scope="col" data-rt-column="Telefone">Telefone</th>
                                <th scope="col" data-rt-column="CNPJ/CPF">Email</th>
                                <th scope="col" data-rt-column="Status">Status</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesman as $key)
                            <tr>
                                <td style="text-align:center;">{{$key->code}}</td>
                                <td><b>{{$key->first_name}} {{$key->last_name}}</b> <br> <span style="font-size: 12px;">({{$key->identity}})</span></td>
                                <td>{{$key->phone_1}} <br> {{$key->phone_2}}</td>
                                <td style="overflow-wrap:break-word;">{{$key->email}}</td>
                                <td>
                                    @if ($key->is_active == 1)
                                    <span class="label label-success td-status" style="position: relative;left: 40%;">Ativo</span>
                                    @else
                                    <span class="label label-danger td-status" style="position: relative;left: 32%;">Desativado</span>
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
                        <?= $salesman->appends([
                            'status' => Session::get('sal_status'),
                            'name' => Session::get('sal_name'),
                            'identity' => Session::get('sal_identity'),
                            'code' => Session::get('sal_code'),
                        ])->links(); ?>
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
                            <label for="name">Código</label>
                            <input type="text" name="code" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="name">Nome</label>
                            <input type="text" name="name" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="name">CNPJ / CPF</label>
                            <input type="text" name="identity" id="identity" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('sal_status') == 1) selected="selected" @endif>Ativo</option>
                                <option value="2" @if (Session::get('sal_status') == 2) selected="selected" @endif>Desativado</option>
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
    function action($this = '') {

        if ($this == '') {
            window.location.href = '/commercial/salesman/edit/0';
        }
        var json = JSON.parse($($this).attr('json-data'));
        if ($($this).val() == 1) {

            window.location.href = '/commercial/salesman/edit/'+json.id;
        } else if ($($this).val() == 2) {

            bootbox.dialog({
                message: "Você realmente quer deletar '"+json.full_name+"'?",
                title: "Deletar represetante",
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
                            window.location.href = '/commercial/salesman/delete/'+json.id;
                        }
                    }
                }
            });
        }
        $($this).val('');
    }
    $(document).ready(function () {

        var options = {
            onKeyPress : function(cpfcnpj, e, field, options) {
                var masks = ['000.000.000-009', '00.000.000/0000-00'];
                var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                $('#identity').mask(mask, options);
            }
        };
        $('#identity').mask('000.000.000-009', options);

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

        $("#filterNow").click(function (e) {
            $("#filterModal").modal('toggle');
            block();
            $("#filterData").submit();

        });
        $("#salesman").addClass('menu-open');
        $("#salesmanAll").addClass('page-arrow active-page');
    });
</script>

@endsection
