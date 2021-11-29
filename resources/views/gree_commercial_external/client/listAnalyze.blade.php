@extends('gree_commercial_external.layout')

@section('page-css')

    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="/js/plugins/datatables/responsive.dataTables.min.css" rel="stylesheet" type="text/css">
@endsection

@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-12 align-self-center text-right">
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
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Solicitações de aprovação</h4><br>

                <!--<h6 class="card-subtitle">Create responsive tables by wrapping any <code>.table</code> in <code>.table-responsive </code></h6>-->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>CNPJ / RG</th>
                                <th>Grupo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($analyze as $key)
                                <tr>
                                    <td>{{$key->code}}</td>
                                    <td>{{$key->company_name}}</td>
                                    <td>{{$key->identity}}</td>
                                    <td>@if(count($key->client_group) > 0) {{$key->client_group->first()->name}} @else - @endif</td>
                                    <td>
                                        @if ($key->client_version->first()->version == 1)
                                        <span class="label label-info">Novo</span>
                                        @else
                                        <span class="label label-warning">Atualização</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select json-data="{{$key->id}}" onchange="action(this)" class="simpleselect form-control">
                                            <option></option>
                                            <option value="1">Análisar</option>
                                            <option value="2">Imprimir Atual</option>
                                            <option value="3">Imprimir Alteração</option>
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')

    <script>
        function action($this = '') {

            var id = $($this).attr('json-data');

            if ($($this).val() == 1)
                window.open('/comercial/operacao/cliente/analise/'+id, '_self');
            else if ($($this).val() == 2)
                window.open('/comercial/operacao/client/print/view/'+id, '_blank');
            else if ($($this).val() == 3)
                window.open('/comercial/operacao/client/approv/view/'+id, '_blank');

            $($this).val('');

        }
    </script>

@endsection
