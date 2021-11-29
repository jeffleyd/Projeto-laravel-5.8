@extends('gree_commercial_promoter.layout')
@section('content')
<link href="/elite/assets/node_modules/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-6 col-md-12">
        <div class="row">
            <div class="col-12 col-md-5 align-self-center">
                <h6 class="text-themecolor">Solicitar material</h6>
            </div>
            <div class="col-12 col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Solicitar material</li>
                    </ol>
                    <button onclick="edit('')" type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Novo pedido</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-12 d-md-none d-flex no-block justify-content-center">
        <button onclick="edit('')" type="button" class="btn btn-info"><i class="fa fa-plus-circle"></i> Novo pedido</button>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Lista de solicitação </h4>
                <h6 class="card-subtitle">Abaixo, você verá todas as solicitações já realizadas</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Qtd</th>
                                <th>Pedido em</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itens as $key)
                            <tr>
                                <td><a href="javascript:void(0)">#{{$key->id}}</a></td>
                                <td>{{$key->name}}</td>
                                <td>{{$key->quantity}}</td>
                                <td><span class="text-muted"><i class="fa fa-clock-o"></i> <?= $key->created_at->locale('pt_BR')->isoFormat('MMM, D YYYY') ?></span> </td>
                                <td>
                                    @if ($key->is_cancelled == 1)
                                    <div class="label label-table label-danger">Cancelado</div>
                                    @elseif ($key->has_accept == 0)
                                    <div class="label label-table label-warning">Pendente</div>
                                    @elseif ($key->has_send == 1 and $key->has_receiver == 0)
                                    <div class="label label-table label-info">Aguardando recebimento</div>
                                    @elseif ($key->has_receiver == 1)
                                    <div class="label label-table label-success">Concluído</div>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    @if ($key->has_accept == 0)
                                    <a json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="edit(this)" href="javascript:void(0)" data-toggle="tooltip" data-original-title="Editar"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                    <a onclick="item_delete({{$key->id}})" href="javascript:void(0)" data-toggle="tooltip" data-original-title="Fechar"> <i class="fa fa-close text-danger"></i> </a>
                                    @endif
                                    @if ($key->has_send == 1 and $key->has_receiver == 0)
                                    <a onclick="receiver({{$key->id}})" href="javascript:void(0)" data-toggle="tooltip" data-original-title="Recebi"> <i class="fa fa-check-circle text-info"></i> </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="float-right">
                        <?= $itens->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<div id="editmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editando item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form method="POST" id="formDefault" action="/promotor/request/item/edit_do">
            <input type="hidden" id="id" name="id" value="0">
            <div class="modal-body">                
                <div class="form-group">
                    <label for="name" class="control-label">Nome:</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="message-text" class="control-label">Quantidade:</label>
                    <input type="number" class="form-control" value="1" id="quantity" name="quantity">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">FECHAR</button>
                <button type="submit" class="btn btn-danger waves-effect waves-light btnmodal">CRIAR NOVO</button>
            </div>
            </form>
        </div>
    </div>
</div>

@include('gree_commercial_promoter.layout.themeScripts')
<script src="/elite/assets/node_modules/sweetalert/sweetalert.min.js"></script>
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
            $("#id").val(json_row.id);
            $("#name").val(json_row.name);
            $("#quantity").val(json_row.quantity);
        } else {
            $("#id").val(0);
            $(".modal-title").html('CRIANDO NOVO ITEM');
            $(".btnmodal").html('CRIAR NOVO');
        }

        $("#editmodal").modal();
    }

    $('formDefault').on('submit', function (e) {
        
        if ($("#name").val() == '') {

            e.preventDefault();
            return $error('Preencha o nome');
        } else if ($("#quantity").val() == '') {

            e.preventDefault();
            return $error('Preencha a quantidade');
        }
        $("#editmodal").modal('toggle');
        block();
    });

    function item_delete(id) {
        swal({   
            title: "Tem certeza disso?",   
            text: "Você deseja realmente excluir essa solicitação de item?",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#3085d6",   
            cancelButtonColor: '#d33',
            confirmButtonText: "Confirmar",   
            cancelButtonText: "Cancelar",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
                if (isConfirm) {    
                    swal.close();
                    block();
                    window.location.href = '/promotor/request/item/delete/' + id;
                } else { 
                    swal.close();
                }
        });
    }
    function receiver(id) {
        swal({   
            title: "Recebimento",   
            text: "Você confirma que recebeu o item da lista?",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#3085d6",   
            cancelButtonColor: '#d33',
            confirmButtonText: "Confirmar",   
            cancelButtonText: "Cancelar",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
                if (isConfirm) {    
                    swal.close();
                    block();
                    window.location.href = '/promotor/request/item/receiver/' + id;
                } else { 
                    swal.close();
                } 
        });
    }
    
    
    </script>
@endsection