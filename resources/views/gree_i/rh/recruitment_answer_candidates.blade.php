
@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Candidatos</h5>
                    <div class="breadcrumb-wrapper col-12">
                        Provas realizadas
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
                                        <h5>Prova realizada por candidato</h5>
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
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Prova</th>
                                            <th>Realizada</th>
                                            <th>Acertou</th>
                                            <th>Status</th>
                                            <th>Repostas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($candidates as $key) { ?>
                                            <tr>
                                                <td><?= $key->name ?></td>
                                                <td><?= $key->email ?></td>
                                                <td><span data-toggle="tooltip" data-placement="left" title="" data-original-title="<?= $key->recruitment_test->title ?>" style="cursor: pointer;"><?= stringCut($key->recruitment_test->title, 24) ?></span></td>
                                                <td><?= $key->date_concluded ? date('d/m/Y', strtotime($key->date_concluded)) : '--' ?></td>
                                                <td><?= $key->questions_correct ? ''.$key->questions_correct.'/'.$key->recruitment_test->total_questions.'': 0 ?></td>
                                                <td>
                                                    @if($key->is_concluded == 1 && $key->is_approved == 1 && $key->is_timeout == 0)
                                                        <span class="badge badge-light-success">Aprovado</span>
                                                    @elseif($key->is_concluded == 1 && $key->is_approved == 0 && $key->is_timeout == 0)
                                                        <span class="badge badge-light-danger">Reprovado</span>
                                                    @elseif($key->is_concluded == 1 && $key->is_timeout == 1) 
                                                        <span class="badge badge-light-danger">Reprovado <br>Não concluiu no tempo</span>   
                                                    @elseif($key->is_concluded == 0)
                                                        <span class="badge badge-light-warning">Em andamento</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($key->is_concluded == 1)
                                                        <a href="/recruitment/answer/candidates/response/<?= $key->id ?>" target="_blank">Visualizar</a>
                                                    @else
                                                        --
                                                    @endif    
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav>
                                    <ul class="pagination justify-content-end">
                                        <?= $candidates->appends([
                                            'name' => Session::get('cand_name'),
                                            'email' => Session::get('cand_email'),
                                            'is_approved' => Session::get('cand_is_approved')
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
                <span class="modal-title">Filtrar Candidatos</span>
            </div>
            <form action="{{Request::url()}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Nome</label>
                                <input type="text" class="form-control" name="name" placeholder="Por nome">
                            </div>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="type_action">Email</label>
                                <input type="text" class="form-control" name="email" placeholder="Por email">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">STATUS</label>
                                <select class="form-control" name="is_approved">
                                    <option></option>
                                    <option value="1">Aprovado</option>
                                    <option value="99">Reprovado</option>
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
                <span class="modal-title">Exportar Candidatos</span>
            </div>
            <form action="{{Request::url()}}">
                <input type="hidden" name="export" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible mb-2" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-error"></i>
                                    <span>
                                        Para exportar todos os candidatos, não é necessário selecionar os campos!
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">STATUS</label>
                                <select class="form-control" name="is_approved">
                                    <option></option>
                                    <option value="99">Reprovado</option>
                                    <option value="1">Aprovado</option>
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

<script>
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

        setInterval(() => {
            $("#mRH").addClass('sidebar-group-active active');
            $("#mQuestion").addClass('sidebar-group-active active');
            $("#mQuestionAll").addClass('active');
        }, 100);
    });
    </script>
@endsection