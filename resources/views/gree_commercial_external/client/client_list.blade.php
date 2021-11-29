@extends('gree_commercial_external.layout')

@section('page-css')

    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
@endsection

@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Lista de Clientes</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#add-contact"><i class="fa fa-filter"></i> Filtrar</button>
                <a class="btn btn-info d-none d-lg-block m-l-15" onclick="action()" href="#">
                    <i class="fa fa-plus-circle"></i> Novo Cliente
                </a>
				<a class="btn btn-success d-none d-lg-block m-l-15" href="#" data-toggle="modal" data-target="#exportModal"><i class="fa fa-download"></i> Exportar</a>
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
								<th>Crédito aprovado</th>
                                <th>CNPJ / RG</th>
                                <th>Grupo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($client as $key)
                                <tr>
                                    <td>{{$key->code}}</td>
                                    <td>{{$key->company_name}}</td>
									<td>R$ {{$key->financy_credit}}</td>
                                    <td>{{$key->identity}}</td>
                                    <td>{{$key->group->name}}</td>
                                    <td>
                                        @if ($key->is_active == 0)
                                        <span class="label label-danger">Desativado</span>
                                        @elseif ($key->salesman_imdt_reprov == 1 or $key->revision_is_reprov == 1 or $key->judicial_is_reprov == 1 or $key->commercial_is_reprov == 1 or $key->financy_reprov == 1)
                                            <span class="label label-danger">Reprovado</span>
                                        @elseif ($key->salesman_imdt_approv == 1 and $key->revision_is_approv == 1 and $key->judicial_is_approv == 1 and $key->commercial_is_approv == 1 and $key->financy_approv == 1)
                                            <span class="label label-success">Aprovado</span>
											@if ($key->financy_status == 1)
												<br><span class="label label-danger">Reprovado pelo financeiro</span>
											@elseif ($key->financy_status == 2)
												<br><span class="label label-warning">Liberado antecipado</span>
											@elseif ($key->financy_status == 3)
												<br><span class="label label-success">Liberado antecipado & parcelado</span>
											@endif
                                        @elseif($key->has_analyze == 0)
                                            <span class="label label-info">Cadastrado</span>
											@if ($key->financy_status == 1)
												<br><span class="label label-danger">Reprovado pelo financeiro</span>
											@elseif ($key->financy_status == 2)
												<br><span class="label label-warning">Liberado antecipado</span>
											@elseif ($key->financy_status == 3)
												<br><span class="label label-success">Liberado antecipado & parcelado</span>
											@endif
                                        @else
                                            <span class="label label-warning">Em análise</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                            <option></option>
                                            <option value="1">Editar</option>
                                            <option value="2">Hist. Análises</option>
											<option value="3">Imprimir</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $client->appends([
                            'code' => Session::get('client_code'),
                            'name' => Session::get('client_name'),
                            'identity' => Session::get('client_identity'),
                            'status' => Session::get('client_status'),
                            'is_analyze' => Session::get('client_is_analyze'),
                            'region' => Session::get('client_region'),
                            'status_chart' => Session::get('client_status_chart'),
                        ])->links(); ?>
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
                <h4 class="modal-title">Filtrar Clientes</h4>
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
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1" @if (Session::get('client_status') == 1) selected="selected" @endif>Ativo</option>
                                <option value="2" @if (Session::get('client_status') == 2) selected="selected" @endif>Desativado</option>
								<option value="3" @if (Session::get('client_status') == 3) selected="selected" @endif>Reprovado</option>
                                <option value="4" @if (Session::get('client_status') == 4) selected="selected" @endif>Aprovado / Reprovado pelo financeiro</option>
                                <option value="5" @if (Session::get('client_status') == 5) selected="selected" @endif>Aprovado / Liberado antecipado</option>
                                <option value="6" @if (Session::get('client_status') == 6) selected="selected" @endif>Aprovado / Liberado antecipado & parcelado</option>
                                <option value="7" @if (Session::get('client_status') == 7) selected="selected" @endif>Cadastrado / Reprovado pelo financeiro</option>
                                <option value="8" @if (Session::get('client_status') == 8) selected="selected" @endif>Cadastrado / Liberado antecipado</option>
                                <option value="9" @if (Session::get('client_status') == 9) selected="selected" @endif>Cadastrado / Liberado antecipado & parcelado</option>
                                <option value="10" @if (Session::get('client_status') == 10) selected="selected" @endif>Em análise</option>
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

<div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Exportar Clientes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="form_client_export">
                    <input type="hidden" name="export" value="1">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="year">Ano</label>
                            <input name="year" placeholder="2021" value="2021" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option></option>
                                <option value="1">Ativo</option>
                                <option value="2">Desativado</option>
								<option value="3">Reprovado</option>
                                <option value="4">Aprovado / Reprovado pelo financeiro</option>
                                <option value="5">Aprovado / Liberado antecipado</option>
                                <option value="6">Aprovado / Liberado antecipado & parcelado</option>
                                <option value="7">Cadastrado / Reprovado pelo financeiro</option>
                                <option value="8">Cadastrado / Liberado antecipado</option>
                                <option value="9">Cadastrado / Liberado antecipado & parcelado</option>
                                <option value="10">Em análise</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" id="exportNow" class="btn btn-success pull-right">Exportar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-scripts')

    <script src="/js/plugins/mask/jquery.mask.min.js"></script>

    @include('gree_commercial_external.components.timeline_analyze', ['url' => '/comercial/operacao/cliente/timeline/'])

    <script type="text/javascript">
        function action($this = '') {

            if ($this == '') {
                window.location.href = '/comercial/operacao/cliente/cadastro/0';
            }
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                window.location.href = '/comercial/operacao/cliente/cadastro/'+json.id;
            } else if ($($this).val() == 2) {
                analyzeTimeline(json.id);
            } else if ($($this).val() == 3) {
				window.open('/comercial/operacao/client/print/view/'+json.id, '_blank');	
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
			
			$("#exportNow").click(function() {
                $("#form_client_export").submit();
            });
        });

        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    </script>

@endsection
