
@extends('gree_i.layout')

@section('content')

<style>
    .table th, .table td {
        padding: 1.60rem 0.4rem;
    }
</style>
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Comunicados e notificações</h5>
                    <div class="breadcrumb-wrapper col-12">
                        Permissões
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
                                        <h5>Permitir acesso: comunicados e notificações</h5>
                                    </div>
                                </div>
                                <div class="actions action-btns d-flex align-items-center">
                                    <div class="dropdown invoice-filter-action">
                                        <button type="button" class="btn btn-primary shadow mr-0" data-toggle="modal" data-target="#modal_filter">Filtrar</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Matrícula</th>
                                            <th>Nome</th>
                                            <th>email</th>
                                            <th>Telefone</th>
                                            <th>Setor</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users_notify as $key) { ?>
                                        <tr>
                                            <td><?= $key->r_code ?></td>
                                            <td><?= $key->name ?></td>
                                            <td><?= $key->email ?></td>
                                            <td><?= $key->phone ?></td>
                                            <td><?= config('gree.sector')[$key->sector] ?></td>
                                            <td>
                                                <?php if ($key->status == 2) { ?>
                                                    <span class="badge badge-light-primary">Novo Cadastro</span>
                                                <?php } else if($key->status == 1) { ?>
                                                    <span class="badge badge-light-success">Permitido</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-danger">Não permitido</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if($key->status != 1)
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="liberateUser(<?= $key->id ?>, 1)"><i class="bx bx-lock-open-alt mr-1"></i> Permitir acesso</a>
                                                        @endif
                                                        @if($key->status == 1)
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="liberateUser(<?= $key->id ?>, 0)"><i class="bx bx-block mr-1"></i> Remover acesso</a>
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
                                        <?= $users_notify->appends([
                                            'status' => Session::get('notif_status'),
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
                <span class="modal-title">Filtrar colaborador</span>
            </div>
            <form action="{{Request::url()}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Matrícula</label>
                                <input type="text" class="form-control" name="r_code" placeholder="Informe a matrícula">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Nome</label>
                                <input type="text" class="form-control" name="name" placeholder="Informe o nome">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">STATUS</label>
                                <select class="form-control" name="status">
                                    <option></option>
                                    <option value="2">Novo cadastro</option>
                                    <option value="1">Permitido</option>
                                    <option value="99">Não permitido</option>
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
            $("#mNotifyCollaborator").addClass('sidebar-group-active active');
            $("#mLiberateCollaborator").addClass('active');
        }, 100);
    });

    function liberateUser(id, type) {

        console.log('id: '+id+ ', type: '+type );

        var msg_title = type == 1 ? 'Permitir acesso' : 'Remover acesso';
        var msg_text = type == 1 ? 'Deseja confirmar a permissão de acesso a este colaborador?' : 'Confirmar remoção de acesso a este coloborador?';

        Swal.fire({
            title: msg_title,
            text: msg_text,
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
                window.location.href = "/notify/collaborator/liberate_do/"+ id +"/"+type;
            }
        });
    }

</script>
@endsection