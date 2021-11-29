@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Comercial</h5>
              <div class="breadcrumb-wrapper col-12">
                Solicitações de itens
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
                    <div class="col-12 col-sm-12 col-lg-3">
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
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="situation" name="situation">
                                <option></option>
                                <option @if (Session::get('filter_situation') == 1) selected @endif value="1">Cancelado</option>
                                <option @if (Session::get('filter_situation') == 2) selected @endif value="2">Pendente</option>
                                <option @if (Session::get('filter_situation') == 3) selected @endif value="3">Aguardando envio</option>
                                <option @if (Session::get('filter_situation') == 4) selected @endif value="4">Enviado P/ promotor</option>
                                <option @if (Session::get('filter_situation') == 5) selected @endif value="5">Promotor recebeu</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="start_date">Data inicial (Pedido em)</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control date-mask" value="{{Session::get('filter_start_date')}}" id="start_date" name="start_date">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="end_date">Data final (Pedido em)</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control date-mask" value="{{Session::get('filter_end_date')}}" id="end_date" name="end_date">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-12 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lista de pedidos de item</h4>
                        <a class="heading-elements-toggle">
                          <i class="bx bx-dots-vertical font-medium-3"></i>
                        </a>
                        <div class="heading-elements">
                            <button type="button" onclick="edit();" class="btn btn-outline-secondary glow mb-0">Novo pedido de item</button>
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
                                            <th>Promotor</th>
                                            <th>Nome do item</th>
                                            <th>QTD</th>
                                            <th>Pedido em</th>
                                            <th>Status</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($item as $index => $key) { ?>
                                        <tr class="cursor-pointer showDetails user_<?= $key->id ?>">
                                            <td style="width: 1%">
                                                <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                            </td>
                                            <td class="user"><?= $key->user->name ?></td>
                                            <td class="name"><?= $key->name ?></td>
                                            <td class="quantity"><?= $key->quantity ?></td>
                                            <td class="created_at"><?= date('d-m-Y', strtotime($key->created_at)) ?></td>
                                            <td class="status">
                                                @if ($key->is_cancelled == 1)
                                                <span class="badge badge-light-danger">Cancelado</span>
                                                @elseif ($key->has_accept == 0)
                                                <span class="badge badge-light-warning">Pendente</span>
                                                @elseif ($key->has_accept == 1 and $key->has_send == 0)
                                                <span class="badge badge-light-warning">Aguardando envio</span>
                                                @elseif ($key->has_send == 1 and $key->has_receiver == 0)
                                                <span class="badge badge-light-info">Enviado P/ promotor</span>
                                                @elseif ($key->has_receiver == 1)
                                                <span class="badge badge-light-success">Promotor recebeu</span>
                                                @endif
                                            </td>
                                            <td id="action" class="no-click"><a json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="edit(this)" href="javascript:void(0)"><i class="bx bx-edit-alt"></i></a></td>
                                        </tr>
                                        <tr style="display:none" class="group _user_<?= $key->id ?>">
                                            <td colspan="7">
                                                <div class="card m-0">
                                                    <div class="card-content">
                                                      <div class="card-body">
                                                        <p class="card-text">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Item enviado em:</label>
                                                                        <span class="send_date">@if ($key->send_date != NULL)<?= date('d-m-Y', strtotime($key->send_date)) ?>@endif</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Item recebido em:</label>
                                                                        <span class="receiver_date">@if ($key->receiver_date != NULL)<?= date('d-m-Y', strtotime($key->receiver_date)) ?>@endif</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </p>
                                                      </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $item->appends(getSessionFilters()[0]->toArray())->links(); ?>
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

<div class="modal fade text-left" id="modal-generic" tabindex="-1" role="dialog" aria-labelledby="modal-generic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary">
        <h5 class="modal-title white">EDITANDO ITEM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>
        </div>
        <form action="/commercial/promoter/request/item/edit_do" method="post" id="formDefault">
        <input type="hidden" value="0" id="id" name="id">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="user">Usuário</label>
                        <select class="form-control" id="user" name="user">
                            @foreach ($userall as $key)
                            <option value="{{$key->id}}">{{$key->name}}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="editstatus">Status</label>
                        <select class="form-control" id="editstatus" name="editstatus">
                            <option value="1">Cancelado</option>
                            <option value="2">Pendente</option>
                            <option value="3">Aguardando envio</option>
                            <option value="4">Enviado P/ promotor</option>
                            <option value="5">Promotor recebeu</option>
                        </select>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="quantity">Quantidade</label>
                        <input type="text" class="form-control" id="quantity" name="quantity" />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="send_date">Enviado em</label>
                        <input type="text" class="form-control date-mask" id="send_date" name="send_date" />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="receiver_date">Recebido em</label>
                        <input type="text" class="form-control date-mask" id="receiver_date" name="receiver_date" />
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-light-secondary" data-dismiss="modal">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">FECHAR</span>
            </button>
            <button type="button" onclick="update();" class="btn btn-primary ml-1">
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
    function edit(elem = '') {
        $('#formDefault').each (function(){
            this.reset();
        });
        hasNew = elem == '' ? true : false;
        if (elem != '') {
            $(".modal-title").html('EDITANDO ITEM');
            $(".btnmodal").html('ATUALIZAR');
            let json_row = JSON.parse($(elem).attr("json-data"));
            console.log(json_row);
            $("#id").val(json_row.id);
            $("#user").val(json_row.user.id);
            $("#name").val(json_row.name);
            $("#quantity").val(json_row.quantity);
            if (json_row.is_cancelled == 1) {
                $("#editstatus").val(1);
            } else if (json_row.has_accept == 0) {
                $("#editstatus").val(2);
            } else if (json_row.has_accept == 1 && json_row.has_send == 0) {
                $("#editstatus").val(3);
            } else if (json_row.has_send == 1 && json_row.has_receiver == 0) {
                $("#editstatus").val(4);
            } else if (json_row.has_receiver == 1) {
                $("#editstatus").val(5);
            }

            $("#send_date").val(json_row.send_date);
            $("#receiver_date").val(json_row.receiver_date);
        } else {
            $("#id").val(0);
            $(".modal-title").html('CRIANDO NOVO ITEM');
            $(".btnmodal").html('CRIAR NOVO');
        }

        $("#modal-generic").modal();
    }
    function update() {
        if ($("#name").val() == '') {

            return $error('Preencha o nome');
        } else if ($("#quantity").val() == '') {

            return $error('Preencha a quantidade');
        }
        $("#modal-generic").modal('toggle');
        block();
        if (hasNew) {

            $("#formDefault").submit();
        } else {

            ajaxSend('/commercial/promoter/request/item/edit_do', $("#formDefault").serialize(), 'POST', '5000').then(function(result){
                Swal.fire({
                    type: "success",
                    title: 'Sucesso!',
                    text: 'Usuário foi atualizado com sucesso.',
                    confirmButtonClass: 'btn btn-success',
                });

                $(".user_"+result.item.id).find('.id').html(result.item.id);
                $(".user_"+result.item.id).find('.user').html(result.item.user.name);
                $(".user_"+result.item.id).find('.quantity').html(result.item.quantity);
                $(".user_"+result.item.id).find('.created_at').html(result.item.date_created);

                var html = "";
                if (result.item.is_cancelled == 1) {
                    html = '<span class="badge badge-light-danger">Cancelado</span>'
                } else if (result.item.has_accept == 0) {
                    html = '<span class="badge badge-light-warning">Pendente</span>'
                } else if (result.item.has_accept == 1 && result.item.has_send == 0) {
                    html = '<span class="badge badge-light-warning">Aguardando envio</span>'
                } else if (result.item.has_send == 1 && result.item.has_receiver == 0) {
                    html = '<span class="badge badge-light-info">Enviado P/ promotor</span>'
                } else if (result.item.has_receiver == 1) {
                    html = '<span class="badge badge-light-success">Promotor recebeu</span>'
                }
                $(".user_"+result.item.id).find('.status').html(html);
                $("._user_"+result.item.id).find('.send_date').html(result.item.send_date);
                $("._user_"+result.item.id).find('.receiver_date').html(result.item.receiver_date);
                
                $(".user_"+result.item.id).find('a').attr('json-data', JSON.stringify(result.item));
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

        $('.showDetails td').not('.no-click').click(function (e) { 
            e.preventDefault();
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
            
        });

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
        
        setInterval(() => {
            $("#mCommercial").addClass('sidebar-group-active active');
            $("#mCommercialPromoter").addClass('sidebar-group-active active');
            $("#mCommercialPromoterRequestItem").addClass('active');
        }, 100);

    });
    </script>
@endsection