@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Comercial</h5>
              <div class="breadcrumb-wrapper col-12">
                Todos os promotores
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="{{Request::url()}}" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-10">
                        <label for="users-list-verified">Promotor</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="id_user" name="id_user" style="width: 100%;" data-placeholder="Pesquise..." multiple>
                                <option></option>
                                @foreach ($userall as $key)
                                <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lista de promotores</h4>
                        <a class="heading-elements-toggle">
                          <i class="bx bx-dots-vertical font-medium-3"></i>
                        </a>
                        <div class="heading-elements">
                            <button type="button" onclick="editUser();" class="btn btn-outline-secondary glow mb-0">Novo promotor</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Identidade</th>
                                            <th>Status</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $key) { ?>
                                        <tr class="cursor-pointer showDetails user_<?= $key->id ?>">
                                            <td style="width: 1%">
                                                <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                            </td>
                                            <td class="name"><?= $key->name ?></td>
                                            <td class="email"><?= $key->email ?></td>
                                            <td class="identity"><?= $key->identity ?></td>
                                            <td class="status">
                                                <?php if ($key->is_active == 1) { ?>
                                                    <span class="badge badge-light-success">Ativo</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-danger">Desativado</span>
                                                <?php } ?>
                                            </td>
                                            <td id="action" class="no-click"><a json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="editUser(this)" href="javascript:void(0)"><i class="bx bx-edit-alt"></i></a></td>
                                        </tr>
                                        <tr style="display:none" class="group">
                                            <td colspan="7">
                                                @if ($key->routes)
                                                @foreach ($key->routes as $route)
                                                <div class="card">
                                                    <div class="card-content">
                                                      <div class="card-body">
                                                        <p class="card-text">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label>Criado em:</label>
                                                                        <span class="created_at"><?= date('d-m-Y', strtotime($route->created_at)) ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Endereço:</label>
                                                                        <span class="address"><?= $route->address ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label>Complemento:</label>
                                                                        <span class="complement"><?= $route->complement ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label>Status:</label>
                                                                        <span class="badge badge-light-warning">Em andamento</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <div class="form-group">
                                                                                <label>Descrição:</label>
                                                                                <span><?= $route->description ?></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>CheckIn:</label>
                                                                                <span><?= $route->checkin ?></span>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>CheckOut:</label>
                                                                                <span><?= $route->checkout ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </p>
                                                      </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                            </td>
                                            
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $users->appends(getSessionFilters()[0]->toArray())->links(); ?>
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

<div class="modal fade text-left" id="modal-user" tabindex="-1" role="dialog" aria-labelledby="modal-user" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary">
        <h5 class="modal-title white">EDITANDO USUÁRIO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>
        </div>
        <form action="/commercial/promoter/user/edit_do" method="post" id="formDefault">
        <input type="hidden" value="0" id="id" name="id">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <ul class="list-unstyled mb-0">
                        <li class="d-inline-block mr-2 mb-1">
                            <fieldset>
                            <div class="is_active">
                                <input type="radio" value="1" name="is_active" id="active" checked="">
                                <label for="active">Ativo</label>
                            </div>
                            </fieldset>
                        </li>
                        <li class="d-inline-block mr-2 mb-1">
                            <fieldset>
                            <div class="radio">
                                <input type="radio" value="0" name="is_active" id="desactive">
                                <label for="desactive">Desativado</label>
                            </div>
                            </fieldset>
                        </li>
                    </ul>
                    <fieldset class="form-group pic">
                        <img src="" id="pic" height="100" width="100" alt="">
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="picture">Imagem de perfil</label>
                        <br>
                        <input type="file" id="picture" name="picture" />
                        <br><span>É necessário para gerar um icone para o mapa de monitoramento.</span>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="phone_1">Telefone</label>
                        <input type="text" class="form-control" id="phone_1" name="phone_1" />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="phone_2">Telefone</label>
                        <input type="text" class="form-control" id="phone_2" name="phone_2" />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="identity">Identidade</label>
                        <input type="text" class="form-control" id="identity" name="identity" />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="password">Senha</label>
                        <input type="text" class="form-control" id="password" name="password" />
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-light-secondary" data-dismiss="modal">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">FECHAR</span>
            </button>
            <button type="button" onclick="updateUser();" class="btn btn-primary ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block btnmodal">ATUALIZAR</span>
            </button>
        </div>
        </form>
    </div>
    </div>
</div>
<script>
    var hasNew = false;
    function editUser(elem = '') {
        $('#formDefault').each (function(){
            this.reset();
        });
        $("#active").attr('checked', '');
        $("#desactive").removeAttr('checked');
        hasNew = elem == '' ? true : false;
        if (elem != '') {
            $(".modal-title").html('EDITANDO USUÁRIO');
            $(".btnmodal").html('ATUALIZAR');
            let json_row = JSON.parse($(elem).attr("json-data"));
            $("#id").val(json_row.id);
            $("#name").val(json_row.name);
            $("#phone_1").val(json_row.phone_1);
            $("#phone_2").val(json_row.phone_2);
            $(".pic").show();
            if (json_row.picture) {
                $("#pic").attr('src', json_row.picture);
            } else {
                $("#pic").attr('src', '/media/avatars/avatar10.jpg');
            }
            $("#email").val(json_row.email);
            $("#identity").val(json_row.identity);
            if (json_row.is_active == 1) {
                $("#active").attr('checked', '');
                $("#desactive").removeAttr('checked');
            } else {
                $("#desactive").attr('checked', '');
                $("#active").removeAttr('checked');
            }
        } else {
            $(".pic").hide();
            $("#id").val(0);
            $(".modal-title").html('CRIANDO NOVO USUÁRIO');
            $(".btnmodal").html('CRIAR NOVO');
        }

        $("#modal-user").modal();
    }
    function updateUser() {
        if ($("#name").val() == '') {

            return $error('Preencha o nome');
        } else if ($("#email").val() == '') {

            return $error('Preencha o email');
        } else if ($("#identity").val() == '') {

            return $error('Preencha a identidade');
        }
        $("#modal-user").modal('toggle');
        block();
        if (hasNew) {

            $("#formDefault").submit();
        } else {

            ajaxSend('/commercial/promoter/user/edit_do', $("#formDefault").serialize(), 'POST', '60000', $("#formDefault")).then(function(result){
                Swal.fire({
                    type: "success",
                    title: 'Sucesso!',
                    text: 'Usuário foi atualizado com sucesso.',
                    confirmButtonClass: 'btn btn-success',
                });

                $(".user_"+result.user.id).find('.name').html(result.user.name);
                $(".user_"+result.user.id).find('.email').html(result.user.email);
                $(".user_"+result.user.id).find('.identity').html(result.user.identity);

                var html = '';
                if (result.user.is_active == 1) {
                    html = '<span class="badge badge-light-success">Ativo</span>';
                } else {
                    html = '<span class="badge badge-light-danger">Desativado</span>';
                }
                $(".user_"+result.user.id).find('.status').html(html);
                
                $(".user_"+result.user.id).find('a').attr('json-data', JSON.stringify(result.user));
                unblock();
            }).catch(function(err){
                unblock();
                $error(err.message)
            })
        }
    }
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('filter_id_user'))) { ?>
        $('.js-select2').val(['<?= Session::get('filter_id_user') ?>']).trigger('change');
        <?php } ?>

        $('#phone_1').mask('(00) 00000-0000', {reverse: false});
        $('#phone_2').mask('(00) 00000-0000', {reverse: false});

        var options = {
            onKeyPress : function(cpfcnpj, e, field, options) {
                var masks = ['000.000.000-009', '00.000.000/0000-00'];
                var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                $('#identity').mask(mask, options);
            }
        };

        $('#identity').mask('000.000.000-009', options);

        $('.showDetails td').not('.no-click').click(function (e) { 
            e.preventDefault();
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
            
        });
        
        setInterval(() => {
            $("#mCommercial").addClass('sidebar-group-active active');
            $("#mCommercialPromoter").addClass('sidebar-group-active active');
            $("#mCommercialPromoterUsers").addClass('active');
        }, 100);

    });
    </script>
@endsection