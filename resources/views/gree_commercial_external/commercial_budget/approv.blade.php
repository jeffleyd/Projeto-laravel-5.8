@extends('gree_commercial_external.layout')

@section('page-css')

    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
@endsection

@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Aprovar solicitações de verbas</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#add-contact"><i class="fa fa-filter"></i> Filtrar</button>
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
                                <th>Cliente</th>
								<th>Tipo de verba</th>
                                <th>Criado em</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($verbs as $key)
                                <tr>
                                    <td>{{$key->code}}</td>
                                    <td>{{$key->client_company_name}}</td>
									<td>{{$key->type_documents_name}}</td>
                                    <td>{{date('d/m/Y', strtotime($key->created_at))}}</td>
                                    <td>
                                        @if ($key->is_cancelled)
                                            <span class="label label-danger">Cancelado</span>
                                        @elseif ($key->is_reprov)
                                            <span class="label label-danger">Reprovado</span>
                                        @elseif ($key->is_approv)
                                            <span class="label label-success">Aprovado</span>
                                        @elseif ($key->has_analyze)
                                            <span class="label label-warning">Em análise</span>
                                        @elseif ($key->waiting_assign)
                                            <span class="label label-info">Aguard. Comprovação</span>
                                        @else
                                            <span class="label bg-dark">Aguard. Envio P/ Aprovação</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" data-position="<?= $key->rtd_position ?>"  onchange="action(this)" class="simpleselect form-control">
                                            <option></option>
                                            <option value="1">Análisar</option>
                                            <option value="2">Hist. Análises</option>
											<option value="3">Imprimir</option>
                                            <option value="4">Comprovações</option>
											@if ($key->budget_commercial_attach)
											<option value="10">Documentos</option>
											@endif
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $verbs->appends(getSessionFilters()[0]->toArray())->links(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="verbDocuments" class="modal" tabindex="1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Documentos adicionais</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom: 30px;">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Arquivo</th>
                                        <th>Visualizar</th>
                                    </tr>
                                    </thead>
                                    <tbody class="listfiles">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary"> Fechar</button>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

<div id="add-contact" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Filtrar solicitação</h4>
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
                            <label for="name">Razão Social / Nome</label>
                            <input type="text" name="name" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-12">
                            <label for="name">CNPJ / RG</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <select id="type_people" class="select-group" style="height: 38px;background-color: #eeeeee;border-color: #eeeeee;">
                                        <option value="1">CNPJ</option>
                                        <option value="2">RG</option>
                                    </select>
                                </span>
                                <input type="text" class="form-control" name="identity" id="identity" value="" placeholder="00.000.000/0000-00" required/>
                            </div>
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

@include('gree_commercial_external.components.analyze.history.view')
@include('gree_commercial_external.components.analyze.do_analyze.inputs', ['url' => '/comercial/operacao/verba-comercial/fazer/analise'])
@endsection

@section('page-scripts')

    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>

    @include('gree_commercial_external.components.analyze.history.script')
    @include('gree_commercial_external.components.analyze.do_analyze.script')

    <script type="text/javascript">
        var budgetid;
        function action($this = '') {
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                analyze(json.id, $($this).attr('data-position'));
            } else if ($($this).val() == 2) {
                rtd_analyzes(json.id, "App\\Model\\Commercial\\BudgetCommercial");
            } else if ($($this).val() == 3) {
				window.open('/comercial/operacao/verba-comercial/imprimir/'+json.id, '_blank');
			} else if ($($this).val() == 4) {
                window.open(json.url_view_proof, '_blank');
            } else if ($($this).val() == 10) {
                $('#verbDocuments').modal();
    			reloadFiles(json.budget_commercial_attach);
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
