@extends('gree_i.layout')

@section('content')
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <style>
        .td-title {
            background-color: #f6f6f6;
            font-weight: normal;
        }
        .td-text-white {
            color: white;
            font-weight: normal;
        }
        .td-color-black {
            background-color: #000;
            font-weight: normal;
        }
        .td-color-red {
            background-color: rgb(145, 0, 0);
        }
        .td-bold {
            font-weight: 600 !important;
        }
        .td-text-center {
            text-align: center;
        }
        .td-font-11 {
            font-size: 6px;
            font-weight: normal;
        }
        .td-font-13 {
            font-size: 8px;
            font-weight: normal;
        }
        .td-font-14 {
            font-size: 12px;
            font-weight: normal;
        }
        .td-font-17 {
            font-size: 13px;
            font-weight: normal;
        }
        .input-item {
            width: 100%;
            font-family: "IBM Plex Sans", Helvetica, Arial, serif;
            color: #475f7b;
            text-align: center;
            border: 0px;
        }
    </style>
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Entrada & Saída</h5>
                        <div class="breadcrumb-wrapper col-12">
                            Solicitações de funcionários
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- users list start -->
            <section class="users-list-wrapper">
                <div class="users-list-table">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="top d-flex flex-wrap">
                                    <div class="action-filters flex-grow-1">
                                        <div class="dataTables_filter mt-1">
                                            <h5>Lista de solicitações</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-secondary shadow mr-1" data-toggle="modal" data-target="#modal_new">Criar solicitação</button>
                                            <button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                            <button type="button" onclick="@if(Request::get('start_date') and Request::get('end_date')) window.open('{{Request::fullUrl()}}&export=1', '_self') @else $error('Você precisa filtrar e informar a data inicial e final.'); @endif" class="btn btn-success shadow mr-1">Exportar</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <!-- datatable start -->
                                <div class="table-responsive">
                                    <table id="list-datatable" class="table">
                                        <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Solicitante</th>
                                            <th>Motivo</th>
                                            <th>Data</th>
                                            <th>Tipo</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($load_requests as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td>{{$key->request_user->short_name}} ({{$key->request_user->r_code}})</td>
                                            <td>{{$key->reason_name}}</td>
                                            <td>{{date('d/m/Y H:i', strtotime($key->date_hour))}}</td>
                                            <td>
                                                @if($key->is_entry_exit == 1)
                                                    <span class="badge badge-light-primary">Entrada</span>
                                                @else
                                                    <span class="badge badge-light-warning">Saída</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($key->deleted_at)
                                                    <span class="badge badge-light-danger">Deletado</span>
                                                @elseif ($key->is_reprov)
                                                    <span class="badge badge-light-danger">Reprovado</span>
                                                @elseif ($key->is_cancelled)
                                                    <span class="badge badge-light-danger">Cancelado</span>
                                                @elseif ($key->is_denied)
                                                    <span class="badge badge-light-danger">Negado</span>
                                                @elseif ($key->is_liberate)
                                                    <span class="badge badge-light-success">Liberado</span>
                                                @elseif ($key->has_analyze)
                                                    <span class="badge badge-light-success">Em análise</span>
                                                @else
                                                    <span class="badge badge-light-warning">Aguard. Portaria</span>
                                                @endif
                                            </td>
                                            <td id="action">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="reloadSingle(this)" href="javascript:void(0)" class="dropdown-item"><i class="bx bx-show mr-1"></i> Visualizar</a>
                                                        @if ($key->has_analyze and !$key->is_cancelled)
                                                            <a onclick="cancelRequest({{$key->id}})" href="javascript:void(0)" class="dropdown-item"><i class="bx bx-x-circle mr-1"></i> Cancelar</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-end">
                                            <?= $load_requests->appends(getSessionFilters()[2]->toArray()); ?>
                                        </ul>
                                    </nav>
                                </div>
                                <!-- datatable ends -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- users list ends -->
        </div>
    </div>

    <form action="/adm/entry-exit/employees/create" method="POST" id="submitEntry" enctype="multipart/form-data">
        <div class="modal fade" id="modal_new" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-secondary white">
                        <span class="modal-title">Nova solicitação</span>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="request-tab-fill" data-toggle="tab" href="#request-fill" role="tab" aria-controls="home-fill" aria-selected="true">
                                    Solicitação
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="charge-tab-fill" data-toggle="tab" href="#charge-fill" role="tab" aria-controls="profile-fill" aria-selected="false">
                                    Carregamento
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content pt-1">
                            <div class="tab-pane active" id="request-fill" role="tabpanel" aria-labelledby="request-tab-fill">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Tipo</label>
                                            <select name="is_entry_exit" class="form-control">
                                                <option value="2">SAÍDA</option>
                                                <option value="1">ENTRADA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Data</label>
                                            <input type="text" placeholder="{{date('d/m/Y')}}" value="{{date('d/m/Y')}}" class="form-control" id="date" name="date">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Hora</label>
                                            <input type="text" placeholder="{{date('H:i')}}" value="{{date('H:i')}}" class="form-control" id="hour" name="hour">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Retorna no mesmo dia?</label>
                                            <select name="return_same_day" id="return_same_day" class="form-control">
                                                <option value="2">Não</option>
                                                <option value="1">Sim</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 rsd_day" style="display: none">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Data de retorno</label>
                                            <input type="text" placeholder="{{date('d/m/Y')}}" value="{{date('d/m/Y')}}" class="form-control" id="rsd_day" name="rsd_day">
                                        </div>
                                    </div>
                                    <div class="col-6 rsd_hour" style="display: none">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Hora de retorno</label>
                                            <input type="text" placeholder="{{date('H:i')}}" value="{{date('H:i')}}" class="form-control" id="rsd_hour" name="rsd_hour">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Portaria</label>
                                            <select name="logistics_entry_exit_gate_id" id="logistics_entry_exit_gate_id" style="width:100%" class="form-control select24" multiple>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Solicitante</label>
                                            <select name="sel_r_code" style="width:100%" class="form-control select2" multiple>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Imediato chefe</label>
                                            <select name="who_analyze_r_code[]" id="who_analyze_r_code" style="width:100%" class="form-control select22" multiple>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 r_code">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Matricula</label>
                                            <input type="text" class="form-control" id="r_code" name="r_code">
                                        </div>
                                    </div>
                                    <div class="col-12 first_name">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Primeiro nome:</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name">
                                        </div>
                                    </div>
                                    <div class="col-12 last_name">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Sobrenome</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name">
                                        </div>
                                    </div>
                                    <div class="col-12 office">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Cargo</label>
                                            <input type="text" class="form-control" id="office" name="office">
                                        </div>
                                    </div>
                                    <div class="col-12 sector_id">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Setor</label>
                                            <select name="sector_id" id="sector_id" class="form-control">
                                                <option value=""></option>
                                                @foreach ($sectors as $index => $key)
                                                    <option value="{{$index}}">{{$key}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Motivo</label>
                                            <select name="reason" class="form-control">
                                                <option value="2" selected="">Particular</option>
                                                <option value="1">Serviço</option>
                                                <option value="3">Almoço</option>
                                                <option value="5">Saúde</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Justifique</label>
                                            <textarea cols="5" name="justify" id="justify" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="charge-fill" role="tabpanel" aria-labelledby="charge-tab-fill">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-vertical">N de referência</label>
                                            <input type="text" placeholder="Para facilitar a busca..." class="form-control" name="number_ref">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-vertical">Arquivo adicional</label>
                                            <input type="file" class="form-control" name="file_ref">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Galpão</label>
                                            <select class="custom-select select-wharehouse" name="warehouse_id" id="warehouse_id" style="width: 100%;" multiple>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right mt-2">
                                        <button type="button" class="btn btn-block btn-primary shadow" data-toggle="modal" data-target="#modal_import_items"><i class="bx bx-import"></i> Importar itens</button>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead style="text-align: center;">
                                                <tr>
                                                    <th>DESCRIÇÃO</th>
                                                    <th>QUANTIDADE</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody id="table_itens">
                                                <tr id="tr_not_item">
                                                    <td colspan="3" style="text-align: center;">Não há itens adicionados</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-outline-primary" id="btn_new_item" style="width:100%;">
                                                <i class="bx bx-plus"></i> Novo item
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Fechar</span>
                        </button>
                        <button type="submit" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block actiontxt">Criar solicitação</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="modal_import_items" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <span class="modal-title">Importar itens</span>
                </div>
                <div class="modal-body">
                    <div class="alert border-primary alert-dismissible mb-2" role="alert">
                        <div class="d-flex align-items-center">
                        <span>
                            <a targe="_blank" href="/excell/model_import_items_visitant.xlsx" >Modelo de importação <i class="bx bxs-download"></i></a>
                        </span>
                        </div>
                    </div>
                    <form method="post" action="#" id="form_import_items">
                        <div class="row">
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="price">Arquivo</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="doc_xlsx" id="doc_xlsx">
                                        <label class="custom-file-label label-items">Escolher arquivo</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <span class="modal-title">Filtrar dados</span>
                </div>
                <form action="{{Request::url()}}" id="form_modal_filter">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="first-name-vertical">Código</label>
                                    <input type="text" class="form-control" name="code">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="first-name-vertical">Número de referência</label>
                                    <input type="text" class="form-control" name="number_ref">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="first-name-vertical">Data inicial</label>
                                    <input name="start_date" class="form-control date-mask">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="first-name-vertical">Data final</label>
                                    <input name="end_date" class="form-control date-mask">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="reason">Motivo</label>
                                    <select name="reason" class="form-control">
                                        <option value=""></option>
                                        <option value="2">Particular</option>
                                        <option value="1">Serviço</option>
                                        <option value="3">Almoço</option>
                                        <option value="4">Esqueceu o crachá</option>
                                        <option value="5">Saúde</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="first-name-vertical">Solicitante</label>
                                    <select name="r_code" style="width:100%" class="form-control select2" multiple>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Fechar</span>
                        </button>
                        <button type="submit" onclick="block();" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block actiontxt">Filtrar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left modal-borderless modal-view" id="requestPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full">
            <div class="modal-content">
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="request-tab-fill-print" data-toggle="tab" href="#request-fill-print" role="tab" aria-selected="true">
                                Solicitação
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="charge-tab-fill-print" data-toggle="tab" href="#charge-fill-print" role="tab" aria-selected="false">
                                Carregamento
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content pt-1">
                        <div class="tab-pane active" id="request-fill-print" role="tabpanel" aria-labelledby="request-tab-fill-print">
                            <div id="request">
                            </div>
                        </div>
                        <div class="tab-pane" id="charge-fill-print" role="tabpanel" aria-labelledby="charge-tab-fill-print">
                            <div id="charge">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#request-tab-fill-print').click(); wdgt_printElem('request')">
                        <span>Imprimir Solicitação</span>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="$('#charge-tab-fill-print').click(); wdgt_printElem('charge')">
                        <span>Imprimir Carregamento</span>
                    </button>
                    <button type="button" class="btn btn-light-primary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar visualização</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
    <script>
        var xlsx_json;

        $('#doc_xlsx').change(function() {
            block();
            wdgt_import_xlsx_parseExcel(this);
            $('#modal_import_items').modal('hide');
        });
        function wdgt_import_xlsx_callback() {
            $('#doc_xlsx').val('');
            var obj = JSON.parse(xlsx_json);
            $("#tr_not_item").hide();
            var html = '';
            obj.forEach(function($val) {
                html += `<tr>
                            <td style="padding: 5px;"><input class="input-item" value="${$val.Descrição}" name="items_description[]" placeholder="..."></td>
                            <td style="padding: 5px;"><input type="number" value="${$val.Quantidade}" class="input-item" name="items_quantity[]" placeholder="0"></td>
                            <td style="padding: 5px; text-align: center;">
                                <a href="javascript:void(0);" class="remove-item" data-id="0"><i class="bx bx-trash"></i></a>
                            </td>
                        </tr>`;
            });
            $("#table_itens").append(html);
        }
        function wdgt_import_xlsx_parseExcel(doc_xlsx) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var data = e.target.result;
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                workbook.SheetNames.forEach(function(sheetName) {
                    // Here is your object
                    var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                    var json_object = JSON.stringify(XL_row_object);
                    xlsx_json = json_object;
                    wdgt_import_xlsx_callback();
                    unblock();
                });

            };

            reader.onerror = function(ex) {
                unblock();
                $error(ex);
            };

            reader.readAsBinaryString(doc_xlsx.files[0]);
        }
    </script>
    @include('gree_i.misc.components.printElem.script')
    <script>
        function cancelRequest(id) {
            Swal.fire({
                title: 'Cancelar solicitação',
                text: "Caso continue, você irá tirar a solicitação da análise, precisará criar outra.",
                type: 'warning',
                input: 'text',
                inputPlaceholder: 'Informe o motivo...',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    window.location.href = '/adm/entry-exit/employees/cancel?id='+id+'&description='+result.value;
                }
            });
        }

        $("#btn_new_item").click(function() {

            $("#tr_not_item").hide();
            var html = `<tr>
                            <td style="padding: 5px;"><input class="input-item" name="items_description[]" placeholder="..."></td>
                            <td style="padding: 5px;"><input type="number" class="input-item" name="items_quantity[]" placeholder="0"></td>
                            <td style="padding: 5px; text-align: center;">
                                <a href="javascript:void(0);" class="remove-item" data-id="0"><i class="bx bx-trash"></i></a>
                            </td>
                        </tr>`;
            $("#table_itens").append(html);
        });

        $(document).on('click', '.remove-item', function (e){
            $(this).parent().parent().remove();
        });
		
		$(document).on('change', '#date', function (e){
			$('#rsd_day').val($(this).val());
        });
		
		$(document).on('change', '#hour', function (e){
			$('#rsd_hour').val($(this).val());
        });

        function reloadSingle(elem) {
            var html = '';
            var charge = '';
            let data = JSON.parse($(elem).attr("json-data"));


            $('#request').html('');
            $('#charge').html('');
            var restriction = '';
            if (data.entry_restriction) {
                restriction = `<tr>
                            <td colspan="12" class="td-font-13 td-text-white td-text-center td-color-red td-bold">
                                RENSTRIÇÃO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-text-center td-font-14 td-bold" colspan="12">
                                ${data.entry_restriction}
                            </td>
                        </tr>`;
            }
            var reason = '';
            if (data.justify) {
                reason = `<tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                JUSTIFICATIVA
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-text-center td-font-14" colspan="12">
                                ${data.justify}
                            </td>
                        </tr>`;
            }
            var situation = {
                status: 'Aguardando liberação',
                reason: '',
                name: '',
                time: '',
            };
            $('.btn-analyze').show();
            $('.btn-del').hide();
            if (data.deleted_at) {
                $('.btn-analyze').hide();
                situation = {
                    status: 'Deletado',
                    reason: data.del_description,
                    name: data.who_excute_action,
                    time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
                };
            } else if (data.is_liberate) {
                situation = {
                    status: 'Liberado',
                    reason: '',
                    name: data.who_excute_action,
                    time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
                };
            } else if (data.is_denied) {
                $('.btn-analyze').hide();
                situation = {
                    status: 'Negado',
                    reason: data.denied_reason,
                    name: data.who_excute_action,
                    time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
                };
            } else if (data.is_cancelled) {
                $('.btn-analyze').hide();
                situation = {
                    status: 'Cancelado',
                    reason: data.cancelled_reason,
                    name: data.who_excute_action,
                    time: getDateFormat(data.request_action_time, true) +' '+getHourFormat(data.request_action_time),
                };
            }
            var return_hour = 'N/A';
            if (data.return_hour) {
                return_hour = `<b>DATA:</b> ${getDateFormat(data.return_hour, true)} <b>HORA:</b> ${getHourFormat(data.return_hour)}`
            }
            html += `<table class="table table-bordered table-view">
                        <tbody>
                        <tr>
                            <td rowspan="4" style="text-align: center;">
                                <img src="https://gree-app.com.br/media/logo.png" height="30" alt="" style="padding: 2px;">
                            </td>
                            <td colspan="8" rowspan="3" class="td-text-center td-font-17 td-bold">
                                CONTROLE DE ${data.is_entry_exit === 1 ? 'ENTRADA' : 'SAÍDA'}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-font-14">
                                <b>TIPO:</b> FUNCIONÁRIOS
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-font-14">
                                <b>DATA:</b> ${getDateFormat(data.date_hour, true)} <b>HORA:</b> ${getHourFormat(data.date_hour)}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9" class="td-font-14 td-text-white td-text-center td-color-red td-bold">

                            </td>
                            <td class="td-font-14">
                                <b>CÓDIGO:</b> ${data.code}
                            </td>
                        </tr>
                        ${restriction}
                        ${reason}
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                SOLICITANTE
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                NOME COMPLETO:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.request_user.full_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                MATRICULA:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.request_user.r_code}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                CARGO:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.request_office}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                SETOR:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.sector_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                MOTIVO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.reason_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                Retorna no mesmo dia?:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${data.return_same_day ? 'Sim' : 'Não'}
                            </td>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                Horário de retorno:
                            </td>
                            <td class="td-font-14" colspan="4">
                                ${return_hour}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                APROVADOR
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                NOME COMPLETO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze ? data.who_analyze.full_name : 'N/A'}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                MATRICULA:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze ? data.who_analyze.r_code : 'N/A'}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                CARGO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze ? data.who_analyze.office : 'N/A'}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                SETOR:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.who_analyze ? data.who_analyze.sector_name : 'N/A'}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                SITUAÇÃO DA SOLICITAÇÃO
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                STATUS:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.status}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                PORTARIA:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${data.logistics_entry_exit_gate ? data.logistics_entry_exit_gate.name : 'N/A'}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                VIGILANTE/COLABORADOR:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.name}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                DATA E HORÁRIO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.time}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="3">
                                MOTIVO DA AÇÃO:
                            </td>
                            <td class="td-font-14" colspan="9">
                                ${situation.reason}
                            </td>
                        </tr>
                        </tbody>
                    </table>`;
            $('#request').html(html);

            var has_doc = '';
            if (data.file_ref)
                has_doc = `<a target="_blank" href="${data.file_ref}">Clique para visualizar</a>`;

            var itens = '';
            if (data.entry_exit_employees_items.length > 0) {
                data.entry_exit_employees_items.forEach(function($val) {
                    itens += `<tr>
                                <td class="td-font-14" colspan="6">
                                    ${$val.description}
                                </td>
                                <td class="td-font-14" colspan="6">
                                    ${$val.quantity}
                                </td>
                            </tr>`;
                });
            }
            charge += `<table class="table table-bordered table-view">
                        <tbody>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                DETALHES
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                Número de referência:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.number_ref ? data.number_ref : 'N/A'}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                GALPÃO:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${data.logistics_warehouse ? data.logistics_warehouse.name : 'N/A'}
                            </td>
                        </tr
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="2">
                                Documento de referência:
                            </td>
                            <td class="td-font-14" colspan="10">
                                ${has_doc}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                                ITENS
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title td-font-14 td-bold" colspan="6">
                                DESCRIÇÃO
                            </td>
                            <td class="td-title td-font-14 td-bold" colspan="6">
                                QUANTIDADE
                            </td>
                        </tr>
                        ${itens}
                        </tbody>
                    </table>`;
            $('#charge').html(charge);
            $('#requestPrint').modal();
        }

        function getDateFormat(value, has_year = false){
            var date = new Date(value);
            var day = date.getDate().toString();
            var dayF = (day.length == 1) ? '0'+day : day;
            var month = (date.getMonth()+1).toString();
            var monthF = (month.length == 1) ? '0'+month : month;

            if (has_year)
                return dayF+'/'+monthF+'/'+date.getFullYear();
            else
                return dayF+'/'+monthF;
        }

        function getHourFormat(value) {
            var date = new Date(value);
            var hour = date.getHours();
            var hourF = hour < 10 ? '0'+hour : hour;
            var minutes = date.getMinutes();
            var minutesF = minutes < 10 ? '0'+minutes : minutes;
            return  hourF+':'+minutesF;
        }

        function showEdit(elem) {

            if (elem) {
                let json_row = JSON.parse($(elem).attr("json-data"));
                $("#id").val(json_row.id);
                $('input[name="registration_plate"]').val(json_row.registration_plate);
                $('input[name="color"]').val(json_row.color);
                $('input[name="km"]').val(json_row.km);
                if (json_row.is_active == 1)
                    $('select[name="is_active"]').val(1);
                else
                    $('select[name="is_active"]').val(2);

                $('.itemTitle').html('Editando veículo');
            } else {
                $("#id").val(0);
                $('input[name="registration_plate"]').val('');
                $('input[name="color"]').val('');
                $('input[name="km"]').val(0);
                $('select[name="is_active"]').val(1);
                $('.itemTitle').html('Novo veículo');
            }

            $("#modal-update").modal();

        }

        $('#return_same_day').change(function () {
            if ($(this).val() == 1) {
                $('.rsd_day').show();
                $('.rsd_hour').show();
            } else {
                $('.rsd_day').hide();
                $('.rsd_hour').hide();
            }
        })

        $('#submitEntry').submit(function(e) {
            if (!$('#logistics_entry_exit_gate_id').val().length) {
                e.preventDefault();
                return $error('Você precisa selecionar a portaria de saída.');
            } else if (!$('#who_analyze_r_code').val().length) {
                e.preventDefault();
                return $error('Você precisa selecionar o imediato chefe.');
            } else if (!$('#r_code').val()) {
                e.preventDefault();
                return $error('Você precisa informar a matricula.');
            } else if (!$('#first_name').val()) {
                e.preventDefault();
                return $error('Você precisa informar o primeiro nome.');
            } else if (!$('#last_name').val()) {
                e.preventDefault();
                return $error('Você precisa informar o sobrenome.');
            } else if (!$('#office').val()) {
                e.preventDefault();
                return $error('Você precisa informar o cargo.');
            } else if (!$('#sector_id').val()) {
                e.preventDefault();
                return $error('Você precisa informar o setor.');
            } else if (!$('#justify').val()) {
                e.preventDefault();
                return $error('Você precisa informar a justificativa.');
            }
            if ($('#return_same_day').val() == 1) {
                if ($('#rsd_day').val() == "" && $('#rsd_hour').val() == "") {
                    e.preventDefault();
                    return $error('Você precisa informar a data e horário de retorno.');
                }
            }
            block();
        });
        $(document).ready(function () {
            $('.date-mask').pickadate({
                formatSubmit: 'yyyy-mm-dd',
                format: 'yyyy-mm-dd',
                today: 'Hoje',
                clear: 'Limpar',
                close: 'Fechar',
                monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
                weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            });
            $(".select2").select2({
                placeholder: 'Nome ou matricula...',
                maximumSelectionLength: 1,
                language: "pt-BR",
                ajax: {
                    url: '/adm/entry-exit/misc/users/general',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $(".select2").on('select2:select', function (e) {
                var data = e.params.data;
                $('#r_code').val(data.r_code);
                $('.r_code').hide();
                $('#first_name').val(data.first_name);
                $('.first_name').hide();
                $('#last_name').val(data.last_name);
                $('.last_name').hide();
                $('#office').val(data.office);
                $('.office').hide();
                $('#sector_id').val(data.sector_id);
                $('.sector_id').hide();
            });

            $(".select2").on('select2:unselect', function (e) {
                var data = e.params.data;
                $('#r_code').val('');
                $('.r_code').show();
                $('#first_name').val('');
                $('.first_name').show();
                $('#last_name').val('');
                $('.last_name').show();
                $('#office').val('');
                $('.office').show();
                $('#sector_id').val('');
                $('.sector_id').show();
            });
            $(".select23").select2({
                placeholder: 'Placa...',
                maximumSelectionLength: 1,
                language: "pt-BR",
                ajax: {
                    url: '/adm/entry-exit/misc/rent/vehicles',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $(".select22").select2({
                placeholder: 'Nome ou matricula...',
                maximumSelectionLength: 3,
                language: "pt-BR",
                ajax: {
                    url: '/adm/entry-exit/misc/users',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $(".select24").select2({
                placeholder: 'Nome...',
                maximumSelectionLength: 1,
                language: "pt-BR",
                ajax: {
                    url: '/adm/entry-exit/misc/gates',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $('[data-toggle="popover"]').popover({
                placement: 'right',
                trigger: 'hover',
            });
            $('#list-datatable').DataTable( {
                searching: false,
                paging: false,
                ordering:false,
                lengthChange: false,
                language: {
                    search: "{{ __('layout_i.dtbl_search') }}",
                    zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                    info: "{{ __('layout_i.dtbl_info') }}",
                    infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
                    infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
                }
            });

            $('#date, #rsd_day').mask('00/00/0000');
            $('#hour, #rsd_hour').mask('00:00');

            $(".select-wharehouse").select2({
                maximumSelectionLength: 1,
                placeholder: "Selecione o galpão",
                language: {
                    noResults: function () {
                        return 'Galpão não encontrado';
                    }
                },
                ajax: {
                    url: '/logistics/warehouse/list/dropdown',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;
                    }
                }
            });

            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#mEntryExit").addClass('sidebar-group-active active');
                $("#mEntryExitEmployees").addClass('active');
            }, 100);

        });
    </script>
@endsection
