@extends('gree_commercial_external.layout')

@section('page-css')
    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endsection

@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Lista de programações para aprovar</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#filterModal"><i class="fa fa-filter"></i> Filtrar</button>
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
                                <th>Gestor</th>
                                <th>Cliente</th>
                                <th>Programado para</th>
                                <th>Versão</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programations as $key)
                                <tr>
                                    <td>{{$key->code}}</td>
                                    <td>{{$key->client->client_managers[0]->salesman->short_name}} <br>{{$key->client->client_managers[0]->salesman->office}}</td>
                                    <td>
										<a href="/comercial/operacao/cliente/todos?code={{$key->client->code}}" target="_blank" style="color: #428bca;">
                                            {{$key->client->company_name}}
                                        </a>    
									</td>
                                    <td>{{$key->months}}</td>
                                    <td>{{number_format($key->programationVersion->version, 2)}}</td>
                                    <td>
                                        @if ($key->is_cancelled == 1)
                                            <span class="label label-danger">Cancelado</span>
                                        @elseif ($key->salesman_mng_approv == 1)
                                            <span class="label label-success">Aprovado</span>
                                        @elseif ($key->salesman_mng_reprov == 1)
                                            <span class="label label-danger">Reprovado</span>
                                        @elseif ($key->has_analyze == 1)
                                            <span class="label label-warning">Em análise</span>
                                        @else
                                            <span class="label label-secondary">Não enviado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                            <option></option>
                                            @if ($key->has_analyze == 0 and $key->is_cancelled == 0)
                                            <option value="1">Editar</option>
                                            @endif
                                            <option value="2">Visualizar</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $programations->appends(getSessionFilters()[0]->toArray())->links(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="filterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Filtrar Programações</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="filterData">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="code">Código</label>
                            <input type="text" name="code" value="" class="form-control" />
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="client">Cliente</label>
                            <select name="client" class="form-control">
                                <option value=""></option>
                                @foreach ($clients as $key)
                                    <option value="{{$key->id}}">{{$key->company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="start_date">Data da programação</label>
                            <input type="text" name="start_date" value="" class="form-control myear" />
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
    <script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">

        function action($this = '') {

            if ($this == '') {
                window.location.href = '/comercial/operacao/programation/new';
            }
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                window.location.href = '/comercial/operacao/programation/edit/'+json.id;
            } else if ($($this).val() == 2) {
                window.location.href = '/comercial/operacao/programation/view/'+json.id;
            }
            $($this).val('');
        }

        function acPicker() {
            $.fn.datepicker.dates['en'] = {
                days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                today: "Hoje",
                clear: "Limpar",
                format: "mm/dd/yyyy",
                titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
                weekStart: 0
            };

            $(".myear").datepicker( {
                format: "yyyy-mm",
                viewMode: "months",
                minViewMode: "months"
            });
        }

        $(document).ready(function () {

            acPicker();
            $("#filterNow").click(function (e) {
                $("#filterModal").modal('toggle');
                block();
                $("#filterData").submit();
            });
        });

        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    </script>

@endsection
