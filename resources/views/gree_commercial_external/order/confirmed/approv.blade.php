@extends('gree_commercial_external.layout')

@section('page-css')
    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endsection
@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Lista de pedidos para aprovar</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#filterModal"><i class="fa fa-filter"></i> Filtrar</button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <style>
        .alt-question {
            position: relative;
            top: 0px;
            left: 2px;
            font-size: 9px;
        }
    </style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="background-color: #03a9f3;color: #fff;">
                                <th>Código</th>
                                <th>Vendedor</th>
                                <th>Cliente</th>
                                <th>Criado em</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $key)
                                <tr>
                                    <td>{{$key->code}}</td>
                                    <td>
                                         {{$key->salesman->short_name}}
                                    </td>
                                    <td>
                                        <a href="/comercial/operacao/cliente/todos?code={{$key->client->code}}" target="_blank" style="color: #428bca;">
                                            {{$key->client->company_name}}
                                        </a>    
                                    </td>
                                    <td>{{date('d/m/Y', strtotime($key->created_at))}}</td>
                                    <td>
                                        <span class="label label-warning">Em análise</span>
                                    </td>
                                    <td>
                                        <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                            <option></option>
                                            <option value="1">Análisar</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $order->appends(getSessionFilters()[2]->toArray())->links(); ?>
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
                <h4 class="modal-title">Filtrar Pedido</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="filterData">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="code">Código do pedido</label>
                            <input type="text" name="code_order" value="" class="form-control" />
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="subordinates">Subordinados</label>
                            <select name="subordinates" class="form-control">
                                <option value=""></option>
                                @foreach ($subordinates as $key)
                                    <option value="{{$key->id}}">{{$key->short_name}}</option>
                                @endforeach
                            </select>
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
                            <label for="start_date">Data</label>
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

<div id="receiverAssignModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enviar Compravação</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="/comercial/operacao/order/signature" id="formSign">
                    <input type="hidden" name="order_id" id="order_id">
                    <div class="row">
                        <div class="col-sm-12">
                            <p>
                                Para prosseguir com o seu pedido, você precisa comprovar a solicitação, podendo
                                pegar assinatura do cliente, email com cliente confirmando, ordem de serviço do cliente...
                            </p>
                        </div>
                        <div class="col-sm-12 mb-4 mt-2 input-group">
                            <input type="file" name="order_file" value="" class="form-control" />
                            <div class="input-group-append">
                                <button class="btn btn-info" type="button" onclick="sendFileOrder();">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Arquivo</th>
                                        <th>Visualizar</th>
                                        <th class="text-nowrap">Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody class="listfiles">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" onclick="sendAssign()" class="btn btn-success pull-right">Enviar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-scripts')

    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
    var orderid;
        function action($this = '') {

            if ($this == '') {
                window.location.href = '/comercial/operacao/order/new';
            }
            var json = JSON.parse($($this).attr('json-data'));
            if ($($this).val() == 1) {
                window.open('/comercial/operacao/order/confirmed/approv/view/'+json.id);
            }
            $($this).val('');
        }

        function reloadFiles(arr) {
            var list = '';
            arr.forEach(function(item){
                list += '<tr>';
                list += '<td>'+item.name.substring(0,10)+'...</td>';
                list += '<td><a target="_blank" href="'+item.url+'">Clique aqui</a></td>';
                list += '<td><i class="ti ti-trash" style="cursor: pointer" onclick="removeFile('+item.id+')"></i></td>';
                list += '</tr>';
            });

            $('.listfiles').html(list);
        }

        function removeFile(id) {
            block();
            ajaxSend('/comercial/operacao/order/proof/remove', {order_id: orderid, attach_id:id}, 'POST', '10000').then(function(result){
                unblock();
                reloadFiles(result.data);
            }).catch(function(err){
                unblock();
                $error(err.message)
            })
        }

        function sendFileOrder() {
            if($('input[name="order_file"]').val() != '') {
                block();
                ajaxSend('/comercial/operacao/order/proof/upload', {order_id: orderid}, 'POST', '10000', $('#formSign')).then(function(result){
                    unblock();
                    reloadFiles(result.data);
                    $('input[name="order_file"]').val('');
                }).catch(function(err){
                    unblock();
                    $error(err.message)
                })

            } else {
                $error('Você precisa informar um arquivo para continuar.');
            }
        }
        function cancelOrder(id) {
            Swal.fire({
                title: 'Aviso importante',
                text: 'Se você continuar, você irá cancelar seu pedido. Seu pedido sairá do processo de análise e não poderá mais dar continuidade.',
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Pode continuar!',
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonText:
                    'Cancelar',
                showCancelButton: true,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                        if (value) {
                            resolve();
                        }
                    });
                }
            }).then(function (result) {
                if (result.value) {
                    block();
                    window.location.href = '/comercial/operacao/order/cancel/'+id;
                }
            });
        }

        function sendAssign() {
            Swal.fire({
                title: 'Aviso importante',
                text: 'Seu pedido entrará no processo de análise! Deseja continuar?',
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Pode continuar!',
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonText:
                    'Cancelar',
                showCancelButton: true,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                        if (value) {
                            resolve();
                        }
                    });
                }
            }).then(function (result) {
                if (result.value) {
                    block();
                    $('#formSign').submit();
                }
            });
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
