@extends('gree_i.layout')

@section('content')
<style>
    .table th, .table td {
        padding: 1.10rem 0.5rem;
    }
</style>  
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Assitência Técnica</h5>
              <div class="breadcrumb-wrapper col-12">
                Remessa de peças
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
                            <div class="top d-flex flex-wrap">
                                <div class="action-filters flex-grow-1">
                                    <div class="dataTables_filter mt-1">
                                        <h5 >Solicitações de remessas</h5>
                                    </div>
                                </div>
                                <div class="actions action-btns d-flex align-items-center">
                                    <div class="dropdown invoice-filter-action">
                                        <button type="button" class="btn btn-primary shadow mr-1" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                    </div>
                                    <div class="dropdown invoice-options">
                                        <button type="button" class="btn btn-success shadow mr-0" data-toggle="modal" data-target="#modal_export">Exportar</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table id="list-datatable" class="table" style="text-align: center;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Autorizada</th>
                                            <th>Nota remessa</th>
											<th>Relatório técnico</th>
                                            <th>Nota compra</th>
											<th>Foto Etiqueta</th>
                                            <th>Status</th>
                                            <th>Rastreio</th>
                                            <th>Solicitado em</th>
                                            <th>Atualizado em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($remittance as $key)
                                        <tr>
                                            <td>{{ $key->code }}</td>
                                            <td>
                                                <a href="/sac/authorized/edit/<?= $key->authorized_id ?>" data-toggle="tooltip" data-placement="right" title="<?= $key->sac_authorized->name ?>" target="_blank"><?= stringCut($key->sac_authorized->name, 15) ?></a>
                                            </td>
                                            <td><a href="{{ $key->remittance_note }}" target="_blank">visualizar</a></td>
											<td><a href="{{ $key->diagnostic_file }}" target="_blank">visualizar</a></td>
                                            <td>
                                                @if($key->purchase_origin_note != null)
                                                    <a href="{{ $key->purchase_origin_note }}" target="_blank">visualizar</a>
                                                @else 
                                                --
                                                @endif    
                                            </td>
											<td>
                                                @if($key->photo_tag != null)
                                                    <a href="{{ $key->photo_tag }}" target="_blank">visualizar</a>
                                                @else 
                                                --
                                                @endif    
                                            </td>
                                            <td>
                                                 @if($key->is_paid == 1)
                                                     <span class="badge badge-light-success">Pago</span>
                                                @else
                                                    @if ($key->is_cancelled == 0)
														@if ($key->is_payment)
															<span class="badge badge-light-info">Aguard. Pagamento
                                                                @if($key->is_payment_authorized)
                                                                    <i class="bx bx-info-circle cursor-pointer" style="color: #3568df; position: relative; top: 1px; font-size: 0.9rem; left:1px;"data-toggle="tooltip" data-placement="bottom" data-original-title="Obs. Autorizado: <?= $key->is_payment_observation ?>"></i>
                                                                @endif    
                                                            </span>
                                                        @elseif ($key->status == 1)
                                                            <span class="badge badge-light-warning">Em análise</span>
                                                        @elseif ($key->status == 2)
                                                            <span class="badge badge-light-primary">Expedição</span>
                                                        @elseif ($key->status == 3)
                                                            <span class="badge badge-light-info">Enviado</span>
                                                        @elseif ($key->status == 4)
                                                            <span class="badge badge-light-success">Concluído</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-light-danger">Cancelado</span>
                                                    @endif
                                                @endif
                                            </td>
                                            @if ($key->track_code)
                                                <td>{{$key->track_code}}</td>
                                            @else
                                                <td class="text-center">--</td>
                                            @endif
                                            <td>{{ date('d/m/Y', strtotime($key->created_at)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($key->updated_at)) }}</td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" href="javascript:void(0)" onclick="viewAnalyze(<?= $key->id ?>)"><i class="bx bx-file mr-1"></i> Visualizar Análises</a>
                                                        @if($key->is_cancelled != 1)
                                                            <a class="dropdown-item" href="/sac/assistance/remittance/approv/<?= $key->id ?>"><i class="bx bxs-package mr-1"></i> @if($key->status == 1)Aprovar remessa @else Visualizar aprovados @endif</a>
                                                        @endif
                                                        @if($key->status == 1)
                                                            <a class="dropdown-item" href="/sac/assistance/remittance/edit/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar peças</a>
                                                        @endif
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="edit(<?= $key->id ?>, <?= $key->is_cancelled == 0 ?  $key->status : 99 ?>, '<?= $key->track_code ?>', '<?= $key->shipping_cost ?>', '<?= $key->total ?>' )"><i class="bx bx-edit mr-1"></i> Atualizar remessa</a>
                                                        <a class="dropdown-item" href="/sac/assistance/remittance/print/<?= $key->id ?>" target="_blank"><i class="bx bx-receipt mr-1"></i> Impr. remessa</a>
														@if($key->status > 1 && $key->status < 99 && $key->is_payment == 0)
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="RequestPaid(<?= $key->id ?>)"><i class="bx bx-edit mr-1"></i> Solicitar pagamento</a>
                                                        @endif
														@if($key->status < 4 && $key->is_cancelled == 0 && $key->is_paid == 0)
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="changeStatus(<?= $key->id ?>)"><i class="bx bx-rotate-right mr-1"></i> Alterar Status</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>   
                                        @endforeach   
                                    </tbody>
                                </table>
                                <nav>
                                    <ul class="pagination justify-content-end">
                                        <?= $remittance->appends([
                                            'remittance_code' => Session::get('remitList_remittance_code'),
                                            'authorized_id' => Session::get('remitList_authorized_id'),
                                            'status' => Session::get('remitList_status')
                                        ])->links(); ?>
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

<div class="modal fade text-left" id="update-modal" tabindex="-1" role="dialog" aria-labelledby="update-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ATUALIZAR INFORMAÇÕES</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form action="/sac/assistance/remittance/update" id="sendform" method="post">
                <input type="hidden" value="0" id="id" name="id">
                <div class="modal-body">
                    <fieldset class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="99">Cancelado</option>
                        </select>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="track_code">Código de rastreio</label>
                        <input type="text" class="form-control" name="track_code" id="track_code">
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="shipping_cost">Valor do frete</label>
                        <input type="text" style="text-transform: uppercase" class="form-control" name="shipping_cost" id="shipping_cost" value="0.00">
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-sm-block d-none">Fechar</span>
                    </button>
                    <button type="button" onclick="sendInfo()" class="btn btn-primary ml-1 btn-sm">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-sm-block d-none">Concluir</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar Remessas</span>
            </div>
            <form action="{{Request::url()}}" id="form_modal_filter">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Código remessa</label>
                                <input type="text" class="form-control" name="remittance_code" placeholder="Exem. R18739">
                            </div>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="type_action">Autorizada</label>
                                <select class="form-control js-select22" name="authorized_id" style="width: 100%;" multiple></select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">STATUS</label>
                                <select class="form-control" name="status">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="99">Cancelado</option>
                                    <option value="1">Em análise</option>
                                    <option value="2">Expedição</option>
                                    <option value="3">Enviado</option>
                                    <option value="4">Concluído</option>
                                </select>
                            </fieldset>
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
                        <span class="d-none d-sm-block actiontxt">Filtrar</span>
                    </button>
                </div>
            </form> 
        </div>    
    </div>   
</div>

<div class="modal fade" id="modal_export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Exportar Remessas</span>
            </div>
            <form action="{{Request::url()}}" id="form_modal_filter">
                <input type="hidden" name="export" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label>Data inicial</label>
                                <input type="text" name="start_date" id="start_date" class="form-control">
                            </fieldset>
                        </div>    
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label>Data Final</label>
                                <input type="text" name="end_date" id="end_date" class="form-control">
                            </fieldset>
                        </div>    
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Código remessa</label>
                                <input type="text" class="form-control" name="remittance_code" placeholder="Exem. R18739">
                            </div>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="type_action">Autorizada</label>
                                <select class="form-control js-select22" name="authorized_id" style="width: 100%;" multiple></select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">STATUS</label>
                                <select class="form-control" name="status">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="99">Cancelado</option>
                                    <option value="1">Em análise</option>
                                    <option value="2">Expedição</option>
                                    <option value="3">Enviado</option>
                                    <option value="4">Concluído</option>
                                </select>
                            </fieldset>
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
                        <span class="d-none d-sm-block actiontxt">Exportar</span>
                    </button>
                </div>
            </form> 
        </div>    
    </div>   
</div>


<div class="modal fade text-left" id="analyse-modal" tabindex="-1" role="dialog" aria-labelledby="update-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">REALIZAR ANÁLISE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab_historic_analyse" data-toggle="tab" href="#historic_analyse" role="tab" aria-controls="home-just" aria-selected="false">
                            Histórico de Análises
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab_new_analyse" data-toggle="tab" href="#new_analyse" role="tab" aria-controls="profile-just" aria-selected="true">
                            Nova análise técnica
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-1">
                    <div class="tab-pane active" id="historic_analyse" role="tabpanel" aria-labelledby="home-tab-justified">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody id="table_analyze"></tbody>        
                            </table>    
                        </div>    
                    </div>
                    <div class="tab-pane" id="new_analyse" role="tabpanel" aria-labelledby="profile-tab-justified">
                        <form action="/sac/assistance/remittance/analyze" id="form_modal_analyze" method="post">
                            <input type="hidden" name="remittance_id" id="analyze_id">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label>INFORME A ANÁLISE</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="..."></textarea>
                                    </fieldset>
                                </div> 
                            </div>    
                        </form>    
                    </div>
                </div>
            </div>  
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_add_analyze" style="display:none;">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Enviar análise</span>
                </button>
            </div>  
        </div>
    </div>
</div>

<script>
		
	function changeStatus(id) {

        var status = null;
        Swal.fire({
            title: 'Alterar Status',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            html: `Utilize para editar a remessa, não esqueça de voltar para status anterior 
                   <select class="swal2-input" id="swal_status" style="width: 100%;">
                        <option value="" selected disabled>Selecione o status</option>
                        <option value="1">Em análise</option>
                        <option value="2">Expedição</option>
                        <option value="3">Enviado</option>
                        <option value="4">Concluído</option>
                   </select>`,
            preConfirm: () => {
                status = $("#swal_status").val();
                if(status == null) {
                    swal.showValidationError('Selecione o status');
                    return false;
                }   
            }
        }).then(function (result) { ''
            if (result.value) {
                block();
                window.location.href = "/sac/assistance/remittance/change/status/"+ id+"/"+status;
            }
        });

    }
	
    function edit(id, status, track, shipping, total) {
        $("#id").val(id);
        $("#status").val(status);
        $("#track_code").val(track);
        $("#shipping_cost").val(shipping);
        $("#total").val(total);
        $("#update-modal").modal();
    }
	
	function RequestPaid(id) {
        Swal.fire({
            title: 'Solicitação de pagamento',
            text: "Deseja confirmar esta solicitação de pagamento?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                block();
                window.location.href = "/sac/assistance/remittance/request/payment/"+ id;
            }
        });
    }

    function viewAnalyze(id) {
        $("#analyze_id").val(id);

        block();
        ajaxSend('/sac/assistance/remittance/analyze/list', {remittance_id: id}, 'GET', 3000).then(function(result) {

            if(result.success) {

                $('#table_analyze').html(reloadAnalyze(result.analyze));
                $("#analyse-modal").modal('show');
            }
            unblock();
        }).catch(function(err){

            $("#analyse-modal").modal('hide');
            unblock();
            $error(err.message);
        });    

        $("#analyse-modal").modal();
    }

    function reloadAnalyze(object) {
        var html = '';

        if(object.length != 0) {
            for (var i = 0; i < object.length; i++) {
                var column = object[i];

                var date = new Date(column.created_at);

                html += '<tr>';
                html += '<td>'+ column.users.short_name +'</td>';
                html += '<td>'+ column.description +'</td>';    
                html += '<td>'+ date.toLocaleString() +'</td>';
                html += '</tr>';
            }
        } else {
            html += '<tr>';
            html += '<td colspan="3">Não há análises cadastradas!</td>';
            html += '</tr>';
        }
        
        return html;
    }
	
    function sendInfo() {
        $("#update-modal").modal('toggle');
        block();
        $("#sendform").submit();
        
    }
    $(document).ready(function () {

        $("#exportdata").submit(function (e) {
            Swal.fire({
                type: "success",
                title: 'Exportando...',
                text: 'Aguarde nessa tela, enquanto estamos criando o arquivo para você :)',
                confirmButtonClass: 'btn btn-success',
            });
            
        });
		
		$("#tab_historic_analyse").click(function() {
            $("#btn_add_analyze").css('display', 'none');
        });

        $("#tab_new_analyse").click(function() {
            $("#btn_add_analyze").css('display', '');
        });

        $("#btn_add_analyze").click(function(e) {

            if ($("#description").val() == '') {
                e.preventDefault();
                return $error('Obrigatório informar campo análise!');
            } else {
                $("#form_modal_analyze").unbind().submit();
            }    
        });

        $(".js-select22").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {

                    var url = "'/sac/authorized/edit/0'";
                    return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Nova Autorizada</button>');
                }
            },
            ajax: {
                url: '/misc/sac/authorized/',
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

        $('#start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

        $('#end_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

        $('#shipping_cost').mask('000.00', {reverse: true});

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

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mTAssist").addClass('sidebar-group-active active');
            $("#mTAssistRemittance").addClass('active');
        }, 100);

    });
    </script>
@endsection