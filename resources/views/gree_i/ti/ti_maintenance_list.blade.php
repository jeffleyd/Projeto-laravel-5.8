@extends('gree_i.layout')

@section('content')

<style>
    .div_time {
        position: absolute;
        right: 0;
        top: 0px;
    }
	.table th, .table td {
        padding: 1.10rem 0.5rem;
    }
</style>    
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">TI - Suporte e Manutenção</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de atendimentos
              </div>
              <div class="float-right div_time" style=" @if (!hasPermManager(4)) display:none @endif"><b>Atualizando pagina: </b><span id="timer_update"></span> segundos</div>
            </div>
          </div>
        </div>
      </div>
   
    <div class="content-body">
        
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">

                    <div class="card-content">
                        <div class="card-body">
                                  <!--  botoes------>
                                    <div class="top d-flex flex-wrap">
                                        <div class="action-filters flex-grow-1">
                                            <div class="dataTables_filter mt-1">
                                                <h5>Chamados</h5>
                                            </div>
                                        </div>
                                        <div class="actions action-btns d-flex align-items-center">
                                            <div class="dropdown invoice-options">
                                                <a href="/ti/maintenance/edit/0" type="button" class="btn btn-primary shadow mr-1">
                                                    <i class="bx bx-plus"></i>Novo Chamado
                                                </a>
                                            </div>
                                            <div class="dropdown invoice-filter-action">
                                                <button type="button" class="btn btn-success shadow mr-0" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                <!--fim-->
                                <hr>
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Rastreio Id</th>
                                            <th>Categoria</th>
                                            <th>Solicitante</th>
                                            <th>Assunto</th>
											<th>Unidade</th>
                                            <th>Status</th>
                                            <th>Prioridade</th>
                                            <th>Criado em</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($maintenance as $key)   
                                            <tr>
                                                <td><a href="/ti/maintenance/info/{{$key->id}}">{{ $key->trackid }}</a></td>
												<td>{{ strWordCut($key->category->name, 30, '...') }} </td>
                                                <td>@if ($key->users) {{ $key->users->full_name }} @endif</td>
                                                <td><a href="/ti/maintenance/info/{{$key->id}}"><?= strWordCut($key->subject, 30, "...") ?></a></td>
												<td>
													@if($key->unit == 1)
														ADMINISTRATIVO
													@elseif($key->unit == 2)
														GALPÃO 1
													@elseif($key->unit == 3)
														GALPÃO 2
													@elseif($key->unit == 4)
														GALPÃO 3
													@elseif($key->unit == 5)
														AZALEIA
													@elseif($key->unit == 6)
														SUZUKI G1
													@elseif($key->unit == 7)
														SUZUKI G2
													@endif
												</td>	
                                                <td>
                                                    @if ($key->status == 1)
                                                        <span class="text-success">Novo</span>
                                                    @elseif ($key->status == 2)
                                                        <span class="text-warning">Responder</span>
                                                    @elseif ($key->status == 3)
                                                        <span class="text-primary">Respondido</span>
                                                    @elseif ($key->status == 4)    
                                                        <span class="text-muted">Em Progresso</span>
                                                    @elseif ($key->status == 5)
                                                        <span class="text-info">Em Empera</span>    
													@elseif ($key->status == 7)
                                                        <span class="text-info">Encaminhada para o setor de compras</span> 
													@elseif ($key->status == 8)
                                                        <span class="text-info">Aguardando aprovação</span> 
													@elseif ($key->status == 9)
                                                        <span class="text-info">Aguardando toner para troca</span> 
													@elseif ($key->status == 10)
                                                        <span class="text-info">Agendada com o solicitante</span> 
													@elseif ($key->status == 11)
                                                        <span class="text-info">Reserva em andamento</span> 
													@elseif ($key->status == 12)
                                                        <span class="text-info">Aguardando setor de manutenção</span> 
													@elseif ($key->status == 13)
                                                        <span class="text-info">Enviado para assistência técnica</span> 
                                                    @else    
                                                        <span class="text-danger">Resolvido</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($key->priority == 1)  
                                                        <span class="badge badge-secondary">Baixa</span>
                                                    @elseif ($key->priority == 2)
                                                        <div class="badge badge-success">Média</div>
                                                    @elseif ($key->priority == 3)    
                                                        <div class="badge badge-warning">Alta</div>
                                                    @else
                                                        <div class="badge badge-danger">Crítica</div>
                                                    @endif
                                                </td>    
                                                <td>
                                                    {{ date('d/m/Y', strtotime($key->created_at)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= 
                                            $maintenance->appends([
                                                'category' => Session::get('maint_category'),
                                                'status' => Session::get('maint_status'),
												'r_code' => Session::get('maint_r_code'),
                                            ])->links(); 
                                        ?>
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
                <span class="modal-title">Filtrar</span>
            </div>
            <form action="{{Request::url()}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Rastreio Id</label>
                                <input type="text" class="form-control" id="RastreioId" name="RastreioId" placeholder="Identificador">
                            </div>
                        </div>
                        @if (hasPermManager(4))
                        <div class="col-6">
                            <fieldset class="form-group">
                                <label for="type_action">Data Inicial</label>
                                <input type="text" class="form-control date_mask" name="start_date">
                            </fieldset>
                        </div>

                        <div class="col-6">
                            <fieldset class="form-group">
                                <label for="type_action">Data Final</label>
                                <input type="text" class="form-control date_mask" name="end_date">
                            </fieldset>
                        </div>
                        @endif  

                        <div class="col-12">
							@if ($categories)
								<div class="form-group">
									<label for="category">Categoria</label>
									<select class="form-control" id="category" name="category">
										<option value=""></option>
										@foreach($categories as $key)
											<option value="{{ $key->id }}" @if (Session::get('maint_category') == $key->id) selected @endif>{{ $key->name }}</option>
										@endforeach
									</select>
								</div>
							@endif                               
                        </div>
						
						<div class="col-12 col-sm-12 col-lg-12">
							<div class="form-group">
								<label for="r_code">Solicitante</label>
								<select class="form-control js-select23" style="width:100%" id="r_code" name="r_code" multiple>
									@foreach ($users as $key)
									<option value="{{ $key->r_code }}">{{ $key->first_name }} {{ $key->last_name }} ({{ $key->r_code }})</option>
									@endforeach
								</select>
							</div>
						</div>

                        
						<div class="col-12">
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control select-border" id="status" name="status">
								<option value=""></option>
								<option value="1" @if (Session::get('maint_status') == 1) selected @endif>Novo</option>
								<option value="2" @if (Session::get('maint_status') == 2) selected @endif>Responder</option>
								<option value="3" @if (Session::get('maint_status') == 3) selected @endif>Respondido</option>
								<option value="4" @if (Session::get('maint_status') == 4) selected @endif>Em Progresso</option>
								<option value="5" @if (Session::get('maint_status') == 5) selected @endif>Em Espera</option>
								<option value="6" @if (Session::get('maint_status') == 6) selected @endif>Resolvido</option>
								<option value="7" @if (Session::get('maint_status') == 7) selected @endif>Encaminhada para o Setor de compras</option>
								<option value="8" @if (Session::get('maint_status') == 8) selected @endif>Aguardando Aprovação</option>
								<option value="9" @if (Session::get('maint_status') == 9) selected @endif>Aguardando Toner Para troca.</option>
								<option value="10" @if (Session::get('maint_status') == 10) selected @endif>Agendada com o Solicitante</option>
								<option value="11" @if (Session::get('maint_status') == 11) selected @endif>Reserva em Andamento</option>
								<option value="12" @if (Session::get('maint_status') == 12) selected @endif>Aguardando Setor Manutenção</option>
								<option value="13" @if (Session::get('maint_status') == 13) selected @endif>Enviado para Assistência Técnica</option>
							</select>
						</div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">

                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    @if (hasPermManager(4))                   
                        <button type="submit" name="export" value="1" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block actiontxt">Exportar</span>
                        </button>
                    @endif
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block actiontxt">Filtrar</span>
                    </button>
                </div>
            </form> 
        </div>    
    </div>   
</div>

<script>
    $(document).ready(function () {

        $(".js-select23").select2({
            maximumSelectionLength: 1
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

        $('.date_mask').pickadate({
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

        var time_ = 600;
        setInterval(() => {
            time_--;
            $("#timer_update").text(time_);
        }, 1000);
        setTimeout(() => {
            location.reload();
        }, 600000);

        setInterval(() => {
            $("#mTI").addClass('sidebar-group-active active');
        }, 100);
    });
    </script>
@endsection
