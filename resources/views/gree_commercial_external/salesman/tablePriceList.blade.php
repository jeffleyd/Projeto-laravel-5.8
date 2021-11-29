@extends('gree_commercial_external.layout')

@section('page-css')

    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
@endsection

@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Lista de condições</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#add-contact"><i class="fa fa-filter"></i> Filtrar</button>
                <a class="btn btn-info d-none d-lg-block m-l-15" onclick="action()" href="#">
                    <i class="fa fa-plus-circle"></i> Nova condição
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="background-color: #03a9f3;color: #fff;">
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Atualizado em</th>
                                <th>Versão</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesman_table_price as $key)
                                <tr>
                                    <td>{{$key->code}}</td>
                                    <td>{{$key->name}}</td>
                                    <td>{{date('d/m/Y H:i:s', strtotime($key->updated_at))}}</td>
                                    <td>
                                        @if ($key->version < $version)
                                            <b>{{number_format($key->version, 2)}}</b> <i data-toggle="tooltip" data-placement="top" title="" data-original-title="Condição comercial precisa ser editada e atualizada com novo preço!" class="fa fa-info-circle"></i>
                                        @else
                                            <b>{{number_format($key->version, 2)}}</b>
                                        @endif
                                    </td>
                                    <td>
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
            </div>
        </div>
    </div>
</div>

<div id="add-contact" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Filtrar Condições</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="filterData">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="name">Código</label>
                            <input type="text" name="code" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="name">Nome</label>
                            <input type="text" name="name" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="name">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('filter_status') == 1) selected="selected" @endif>Atualizado</option>
                                <option value="2" @if (Session::get('filter_status') == 2) selected="selected" @endif>Desatualizado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="client_id">Cliente</label>
                            <select id="status" class="form-control" name="client_id">
                                <option></option>
                                @foreach ($clients as $client)
                                <option value="{{$client->id}}" @if (Session::get('filter_client_id') == $client->id) selected="selected" @endif>
                                    {{$client->company_name}} ({{$client->identity}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" id="filterNow" class="btn btn-success pull-right">Filtrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-scripts')

    <script src="/js/plugins/mask/jquery.mask.min.js"></script>

    <script type="text/javascript">

        function action($this = '') {

            if ($this == '') {
                window.location.href = '/comercial/operacao/tabela/preco/0';
            }
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                window.location.href = '/comercial/operacao/tabela/preco/'+json.id;
            } else if ($($this).val() == 2) {
                window.location.href = '/comercial/operacao/tabela/deletar/'+json.id;
            }
            $($this).val('');
        }

        $(document).ready(function () {

            $('#identity').mask('00.000.000/0000-00', {reverse: false});
            $("#type_people").change(function () {
                var elem = $('#identity');
                if($(this).val() == 1) {
                    elem.mask('00.000.000/0000-00', {reverse: false});
                    elem.attr("placeholder", "00.000.000/0000-00");
                    elem.val('');
                } else {
                    elem.attr("placeholder", "Informe o RG");
                    elem.unmask();
                    elem.val('');
                }
            });

            $("#filterNow").click(function (e) {
                $("#filterModal").modal('toggle');
                block();
                $("#filterData").submit();
            });
        });

        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    </script>

@endsection
