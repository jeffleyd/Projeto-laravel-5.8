@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<style>
    .badge-cost{
        padding: 0.5rem; 
        font-size: 0.7rem; 
        line-height: 0.6;
    }
    .table th, .table td {
        /*padding: 1.15rem 1rem;*/
        padding: 1.10rem 0.8rem;
    }

    .details td {
        padding: 0.50rem 0.2rem;
        border-bottom: 1px solid #eef3f9;
    }
    .details td.td-label {
        width: 36%;
        color: #374d67;
    }

    body.modal-open {
        overflow: auto !important;
    }
    body.modal-open[style] {
        padding-right: 0px !important;
    }
    
</style>    

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Jurídico</h5>
              <div class="breadcrumb-wrapper col-12">
                Processos
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="top d-flex flex-wrap">
                                    <div class="action-filters flex-grow-1">
                                        <div class="dataTables_filter mt-1">
                                            <h5 >Listagem de Processos</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                        </div>
                                        <div class="dropdown invoice-options">
                                            <button type="button" class="btn btn-success shadow mr-0" data-toggle="modal" data-target="#modal_export">Exportar dados</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Processo</th>
                                            <th>Parte</th>
                                            <th>Tipo ação</th>
                                            <th>Seara</th>
                                            <th>Escritório</th>
                                            <th>Ementa</th>
                                            <th>Ajuizamento</th>
                                            <th>Status</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($process as $key) { ?>
                                        <tr>
                                            <td>
                                                <i class="bx bx-show-alt modal-details" data-process-id="{{ $key->id }}" style="cursor: pointer; color:#3568df;" data-toggle="tooltip" data-placement="left" data-original-title="Detalhes"></i>
                                            </td>
                                            <td>
                                                <a href="/juridical/process/info/<?= $key->id ?>" data-toggle="tooltip" data-placement="right" title="<?= $key->process_number ?>" data-original-title=""><?= stringCut($key->process_number, 25) ?> </a><br>
                                            </td>
                                            @if($key->costumer_id != 0)
                                                <td>
                                                    <a href="/sac/client/edit/<?= $key->sac_client->id ?>" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?= $key->sac_client->name ?>"><?= stringCut($key->sac_client->name, 21) ?></a>
                                                </td>
                                            @else
                                                <td><span data-toggle="tooltip" data-placement="right" title="" data-original-title="<?= $key->name_applicant ?>" style="cursor: pointer;"><?= stringCut($key->name_applicant, 15) ?></span></td>
                                            @endif
                                            <td><span data-toggle="tooltip" data-placement="right" title="" data-original-title="<?= $key->juridical_type_action->description ?>" style="cursor: pointer;"><?= stringCut($key->juridical_type_action->description, 21) ?></span></td>
                                            <td>
                                                @if($key->type_process == 1)
                                                    <span>Consumidor</span>
                                                @elseif ($key->type_process == 2) 
                                                    <span>Trabalhista</span>
                                                @elseif ($key->type_process == 3) 
                                                    <span>Cível</span>    
                                                @elseif ($key->type_process == 4) 
                                                    <span>Penal</span>        
                                                @elseif ($key->type_process == 5)
                                                    <span>Tributário</span>        
                                                @elseif ($key->type_process == 6) 
                                                    <span>Adminstrativo</span>            
                                                @endif
                                            </td>
                                            <td>
                                                <a href="/juridical/law/firm/register/<?= $key->law_firm_id ?>" data-toggle="tooltip" data-placement="right" title="<?= $key->juridical_law_firm->name ?>"><?= stringCut($key->juridical_law_firm->name, 15) ?></a>
                                            </td>
                                            <td><span data-toggle="tooltip" data-placement="left" title="" data-original-title="<?= stringCut($key->measures_plea, 200) ?>" style="cursor: pointer;"><?= $key->measures_plea != '' ? stringCut($key->measures_plea, 23) : '_' ?></span></td>
                                            <td><?= $key->date_judgment ? date('d/m/Y', strtotime($key->date_judgment)) : '_' ?></td>
                                            <td>
                                                @if($key->status == 0)
                                                    <span class="badge badge-light-primary badge-cost" data-toggle="tooltip" data-placement="left" title="" data-original-title="Aguardando atualização do histórico">Cadastrado</span>
                                                @elseif ($key->status == 1)    
                                                    <span class="badge badge-light-success badge-cost" data-toggle="tooltip" data-placement="left" title="" data-original-title="Processo tramitando. Curso comum">Em andamento</span>
                                                @elseif ($key->status == 2)    
                                                    <span class="badge badge-light-warning badge-cost" data-toggle="tooltip" data-placement="left" title="" data-original-title="Aguardando decisão judicial para continuidade">Suspenso</span>
                                                @elseif ($key->status == 3)    
                                                    <span class="badge badge-light-danger badge-cost" data-toggle="tooltip" data-placement="left" title="" data-original-title="Processo finalizado">Arquivado</span>    
                                                @elseif ($key->status == 4)    
                                                    <span class="badge badge-light-secondary badge-cost" data-toggle="tooltip" data-placement="left" title="" data-original-title="Transitou em julgado porém ainda não houve quitação. Fase para quitar o que foi decidido">Senteciado</span>    
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/juridical/process/info/<?= $key->id ?>"><i class="bx bx-file mr-1"></i> Acompanhar processo</a>
                                                        <a class="dropdown-item" href="/juridical/process/register/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar processo</a>
                                                    </div>
                                                </div>    
                                            </td>                                          
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $process->appends(getSessionFilters()[2]->toArray()); ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar Processos</span>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="form_modal_filter">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">NÚMERO DO PROCESSO</label>
                                <input type="text" class="form-control" id="process_number" name="process_number" placeholder="">
                            </div>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="type_process">Seara</label>
                                <select class="form-control" id="type_process" name="type_process">
                                    <option value="">Selecione</option>
                                    <option value="1">Consumidor</option>
                                    <option value="2">Trabalhista</option>
                                    <option value="3">Cível</option>
                                    <option value="4">Penal</option>
                                    <option value="5">Tributário</option>
                                    <option value="6">Adminstrativo</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="type_action">Tipo de ação</label>
                                <select class="form-control" id="type_action" name="type_action">
                                    <option value="" disabled selected>Selecione</option>
                                    @foreach ($type_action as $key)
                                        <option value="{{$key->id}}">{{$key->description}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-12 div_costumer_applicant" style="display: none;">
                            <fieldset class="form-group">
                                <label for="type_action">Requerente</label>
                                <select class="form-control js-select2" id="costumer_id" name="costumer_id" style="width: 100%;" multiple></select>
                            </fieldset>
                        </div>
                        <div class="col-12 div_applicant"style="display: none;">
                            <div class="form-group">
                                <label for="first-name-vertical">CPF/CNPJ Requerente</label>
                                <input type="text" class="form-control" id="identity_applicant" name="identity_applicant" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 div_applicant" style="display: none;">
                            <div class="form-group">
                                <label for="first-name-vertical">Nome Requerente</label>
                                <input type="text" class="form-control" id="name_applicant" name="name_applicant" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="law_firm_id">Escritório responsável</label>
                                <select class="form-control js-select24 valid-select2" id="law_firm_id" name="law_firm_id" style="width: 100%;" multiple></select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">STATUS</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="99">Cadastrado</option>
                                    <option value="1">Em Andamento</option>
                                    <option value="2">Suspenso</option>
                                    <option value="3">Encerrado (Arquivo Definitivamente)</option>
                                    <option value="4">Cumprimento De Senteça</option>
                                </select>
                            </fieldset>
                        </div>
						<div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Inicial Criação</label>
                                <input type="text" class="form-control date-format" name="start_date" placeholder="00/00/0000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Final Criação</label>
                                <input type="text" class="form-control date-format" name="end_date" placeholder="00/00/0000">
                            </div>
                        </div>
                    </div>
                </form>    
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_filter_list">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="/juridical/process/export" id="form_modal_export">
                <div class="modal-header bg-primary white">
                    <span class="modal-title">Exportar Processos</span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="type_process">Seara</label>
                                <select class="form-control" id="exp_type_process" name="exp_type_process">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="1">Consumidor</option>
                                    <option value="2">Trabalhista</option>
                                    <option value="3">Cível</option>
                                    <option value="4">Penal</option>
                                    <option value="5">Tributário</option>
                                    <option value="6">Adminstrativo</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="type_action">Tipo de ação</label>
                                <select class="form-control" id="exp_type_action" name="exp_type_action">
                                    <option value="" disabled selected>Selecione</option>
                                    @foreach ($type_action as $key)
                                        <option value="{{$key->id}}">{{$key->description}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-12 div_costumer_applicant" style="display: none;">
                            <fieldset class="form-group">
                                <label for="type_action">Requerente</label>
                                <select class="form-control js-select2" id="exp_costumer_id" name="exp_costumer_id" style="width: 100%;" multiple></select>
                            </fieldset>
                        </div>
                        <div class="col-12 div_applicant"style="display: none;">
                            <div class="form-group">
                                <label for="first-name-vertical">CPF/CNPJ Requerente</label>
                                <input type="text" class="form-control mask-cnpj-cpf" id="exp_identity_applicant" name="exp_identity_applicant" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 div_applicant" style="display: none;">
                            <div class="form-group">
                                <label for="first-name-vertical">Nome Requerente</label>
                                <input type="text" class="form-control" id="exp_name_applicant" name="exp_name_applicant" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="law_firm_id">Escritório responsável</label>
                                <select class="form-control js-select24" id="exp_law_firm_id" name="exp_law_firm_id" style="width: 100%;" multiple></select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">STATUS</label>
                                <select class="form-control" id="exp_status" name="exp_status">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="0">Cadastrado</option>
                                    <option value="1">Em Andamento</option>
                                    <option value="2">Suspenso</option>
                                    <option value="3">Encerrado (Arquivo Definitivamente)</option>
                                    <option value="4">Cumprimento De Senteça</option>
                                </select>
                            </fieldset>
                        </div>
						<div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Inicial Criação</label>
                                <input type="text" class="form-control date-format" name="start_date" placeholder="00/00/0000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Data Final Criação</label>
                                <input type="text" class="form-control date-format" name="end_date" placeholder="00/00/0000">
                            </div>
                        </div>
                        <input type="hidden" name="export" value="1">
                    </div>                    
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1"id="btn_export_process">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Exportar</span>
                    </button>
                </div>
            </form>
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Detalhes do processo</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped details">
                            <tbody id="table_details">
                            </tbody>
                          </table>
                    </div>
                </div>
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

<div class="modal fade" id="modal_measures" tabindex="-1" role="dialog" aria-labelledby="modal_measures" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title modal-title-historic">Ementa / Pleitos</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 modal-subtitle-historic"></div>
                    <div class="col-md-12">
                        <blockquote class="blockquote pl-1 border-left-primary border-left-3">
                            <pre class="text-measures" style="white-space: pre-wrap;color: #445567;font-size: 14px;background-color: #fff;"></pre>
                        </blockquote>
                    </div>    
                </div>    
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


<script>
    $(document).ready(function () {

        $('.modal').on('show.bs.modal', function () {
            var $modal = $(this);
            var baseZIndex = 1050;
            var modalZIndex = baseZIndex + ($('.modal.show').length * 20);
            var backdropZIndex = modalZIndex - 10;
            $modal.css('z-index', modalZIndex).css('overflow', 'auto');
            $('.modal-backdrop.show:last').css('z-index', backdropZIndex);
        });

        $('.modal').on('shown.bs.modal', function () {
            var baseBackdropZIndex = 1040;
            $('.modal-backdrop.show').each(function (i) {
                $(this).css('z-index', baseBackdropZIndex + (i * 20));
            });
        });

        $('.modal').on('hide.bs.modal', function () {
            var $modal = $(this);
            $modal.css('z-index', '');
        });

        $(".js-select2").select2({
            placeholder: "Selecione",
            language: {
                noResults: function () {
                    var url = "'/sac/client/edit/0'";
                    return $('<button type="button" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo cliente</button>');
                }
            },
            ajax: {
                url: '/misc/sac/client/',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(".js-select24").select2({
            placeholder: "Selecione",
            language: {
                noResults: function () {
                    var url = "'/juridical/law/firm/register/0'";
                    return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo escritório</button>');
                }
            },
            ajax: {
                url: '/juridical/law/firm/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#type_process, #exp_type_process").change(function() {

            var type = $(this).val();

            if(type == 1) {
                $(".div_costumer_applicant").show();
                $(".div_applicant").hide();
            } else if (type != 1) {
                $(".div_costumer_applicant").hide();
                $(".div_applicant").show();
            }
        });

        $("#btn_filter_list").click(function (e) {
            $("#modal_filter").modal('toggle');
            block();
            $("#form_modal_filter").submit();
        });

        $("#btn_export_process").click(function (e) {
            $("#modal_export").modal('toggle');
            
            $("#form_modal_export").submit();
        });
		
		$('.date-format').pickadate({
            selectYears: true,
            selectMonths: true,
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

        $('#list-datatable').DataTable({
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

        $(".modal-details").click(function() {

            var process_id = $(this).attr('data-process-id');
            
            block();
            ajaxSend('/juridical/process/details/ajax', {process_id: process_id}, 'GET', '60000', '').then(function(result){

                if(result.success) {

                    console.log(result.details);

                    $('#table_details').html(loadDetails(result.details));
                    $("#modal_details").modal('show');

                    unblock();
                } else {
                    return $error(result.message);
                }
            }).catch(function(err){
                unblock();
                $error(err.message)
            });
        });

        var CpfCnpjMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
        },
        cpfCnpjpOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.mask-cnpj-cpf').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
        

        setInterval(() => {
            $("#mJuridical").addClass('sidebar-group-active active');
            $("#mJuridicalProcess").addClass('sidebar-group-active active');
            $("#mJuridicalProcessList").addClass('active');
        }, 100);
    });

    function loadDetails(details) {

        var html = '';
        html += '<tr><td class="td-label">Seara:</td><td>'+ details.type_process +'</td></tr>';
        html += '<tr><td class="td-label">Recebimento:</td><td>'+ details.date_received +'</td></tr>';

        if(details.costumer_id == 0) {
            html += '<tr><td class="td-label">Requerente:</td><td class="users-view-name">'+ details.name_applicant +'</td></tr>';
        } else {
            html += '<tr><td class="td-label">Requerente:</td><td class="users-view-name"><a target="_blank" href="/sac/client/edit/'+ details.sac_client_id +'">'+ details.name_applicant +'</a></td></tr>';
        }

        html += '<tr><td class="td-label">Requerido:</td><td class="users-view-name">'+ details.name_required +'</td></tr>';
        html += '<tr><td class="td-label">Ação:</td><td class="users-view-email">'+ details.type_action +'</td></tr>';
        html += '<tr><td class="td-label">Comarca:</td><td class="users-view-email">'+ details.district_court +'</td></tr>';
        html += '<tr><td class="td-label">Estado:</td><td>'+ details.state_court +'</td></tr>';
        html += '<tr><td class="td-label">Data Ajuizamento:</td><td>'+ details.date_judgment +'</td></tr>';
        html += '<tr><td class="td-label">Valor da Causa:</td><td>R$ '+ details.value_cause +'</td></tr>';
        html += '<tr><td class="td-label">Responsável:</td><td>'+ details.law_name_resp +'</td></tr>';
        html += '<tr><td class="td-label">Escritório:</td><td><a target="_blank" href="/juridical/law/firm/register/'+ details.law_firm_id +'">'+ details.law_firm_name +'</a></td></tr>';
        html += '<tr><td class="td-label">Ementa / Pleitos:</td><td><a href="javascript:void(0)" onclick="MeasuresModal(this)" data-measures="'+ details.measures_plea +'">'+ details.measures_plea.substring(0, 25) +' <i class="bx bx-link-external" style="top: 3px;position: relative;"></i></a></td></tr>';
        html += '<tr><td class="td-label">Último andamento:</td><td><a href="javascript:void(0)" onclick="lastHistoric(this)" data-historic="'+ details.last_historic.description +'" data-title="'+ details.last_historic.title +'">'+ details.last_historic.title.substring(0, 25) +' <i class="bx bx-link-external" style="top: 3px;position: relative;"></i></a></td></tr>';

        return html;
    }

    function MeasuresModal(el) {

        $(".modal-title-historic").text('Ementa / Pleitos');
        $(".modal-subtitle-historic").html('');

        $(".text-measures").html($(el).attr('data-measures'));
        $("#modal_measures").modal('show');
    }

    function lastHistoric(el) {

        var title = $(el).attr('data-title');

        $(".modal-title-historic").text('Último Andamento');
        $(".modal-subtitle-historic").html('<p style="color:#3568df;"><i class="bx bx-error-circle" style="position: relative; top: 2px;"></i> '+title+'</p>');

        $(".text-measures").html($(el).attr('data-historic'));

        $("#modal_measures").modal('show');
    }

</script>
@endsection