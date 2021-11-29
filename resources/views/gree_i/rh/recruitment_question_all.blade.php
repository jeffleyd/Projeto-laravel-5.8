
@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Recrutamento</h5>
                    <div class="breadcrumb-wrapper col-12">
                        Todas as provas
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
                                        <h5>Listagem de provas</h5>
                                    </div>
                                </div>
                                <div class="actions action-btns d-flex align-items-center">
                                    <div class="dropdown invoice-options">
                                        <a href="/recruitment/question/new/0" type="button" class="btn btn-primary shadow mr-1">
                                            <i class="bx bx-plus"></i>Nova prova
                                        </a>
                                    </div>
                                    <div class="dropdown invoice-filter-action">
                                        <button type="button" class="btn btn-success shadow mr-0" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>Prova</th>
                                            <th>Descrição</th>
                                            <th>Tempo prova</th>
                                            <th>Criado em</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recruitment_test as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td><span data-toggle="tooltip" data-placement="left" title="" data-original-title="<?= $key->title ?>" style="cursor: pointer;"><?= stringCut($key->title, 80) ?></span></td>
                                            <td><span data-toggle="tooltip" data-placement="left" title="" data-original-title="<?= $key->description ?>" style="cursor: pointer;"><?= stringCut($key->description, 35) ?></span></td>
                                            <td><?= $key->test_time ?></td>
                                            <td><?= date('d/m/Y', strtotime($key->created_at)) ?></td>
                                            <td>
                                                <?php if ($key->is_progress == 0) { ?>
                                                    <span class="badge badge-light-warning">Não iniciada</span>
                                                <?php } else if($key->is_progress == 1) { ?>
                                                    <span class="badge badge-light-success">Em progresso</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-danger">Encerrada</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/recruitment/question/edit/<?= $key->id ?>"><i class="bx bx-file mr-1"></i> @if($key->is_progress == 0)Editar @else Visualizar @endif</a>
														<a class="dropdown-item" onclick="duplicateTest(<?= $key->id ?>)" href="javascript:void(0)"><i class="bx 
bxs-copy mr-1"></i> Duplicar Prova</a>
                                                        <a class="dropdown-item" href="/recruitment/answer/candidates/<?= $key->id ?>" target="_blank"><i class="bx bx-group mr-1"></i> Candidatos</a>
                                                    </div>                                                   
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $recruitment_test->appends([
                                            'title' => Session::get('rec_title'),
                                            'date_create' => Session::get('rec_date_create'),
                                            'is_active' => Session::get('rec_is_active')
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

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar Provas</span>
            </div>
            <form action="{{Request::url()}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Título da prova</label>
                                <input type="text" class="form-control" name="title">
                            </div>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="type_action">Criado em</label>
                                <input type="text" name="date_create" class="form-control date-pick" placeholder="dd/mm/aaaa">
                            </fieldset>
                        </div>
						<div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="sector">SETOR</label>
                                <select class="form-control" name="sector">
                                    <option value=""></option>
									<?php foreach ($sectors as $key) { ?>
									<option value="<?= $key->id ?>"><?= __('layout_i.'. $key->name .'') ?></option>
									<?php } ?>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">STATUS</label>
                                <select class="form-control" name="is_progress">
                                    <option></option>
                                    <option value="1">Em progresso</option>
                                    <option value="2">Encerrada</option>
                                    <option value="99">Não iniciada</option>
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

<script>
	function duplicateTest(id) {
		
		 Swal.fire({
                    title: 'Tem certeza disso?',
                    text: "Você irá duplicar a prova, essa prova não terá candidados em anexo.",
                    type: 'warning',
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
						block();
						window.location.href = "/recruitment/duplicate/test/"+id;
                    }
            })	
	}
    $(document).ready(function () {
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
		
		$('.date-pick').pickadate({
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
		

        setInterval(() => {
            $("#mRH").addClass('sidebar-group-active active');
            $("#mQuestion").addClass('sidebar-group-active active');
            $("#mQuestionAll").addClass('active');
        }, 100);
    });
    </script>
@endsection