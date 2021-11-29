@extends('gree_sac_authorized.panel.layout')

@section('content')
@if (!$authorized->zipcode)
<div class="alert alert-warning alert-dismissable " role="alert">
    <p class="mb-0">Para podermos enviar o seu pedido corretamente, peço que adicione seu CEP e ajuste seu endereço se necessário. <a href="/autorizada/perfil">CLIQUE AQUI</a></p>
</div>
@endif
<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-12 col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Todas solicitações de remessas de peças</h3>
                <div class="block-options">
                    <a href="/autorizada/remessa/peca">
                        <button type="button" class="btn btn-sm btn-info">Nova solicitação de remessa</button>
                    </a>
                    <button type="button" style="display: none" class="btn-block-option" data-toggle="block-option" data-action="state_toggle">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-center">Código</th>
                            <th>Nota de remessa</th>
							<th>Relatório Técnico</th>
                            <th>Nota de compra</th>
							<th>Foto Etiqueta</th>
                            <th>Rastreio</th>
                            <th>Status</th>
                            <th>Solicitação</th>
                            <th>Atualização</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($remittance) > 0)
                            @foreach ($remittance as $key)
                            <tr>
                                <td class="text-center">{{ $key->code }}</td>
                                <td><a href="{{ $key->remittance_note }}" target="_blank">visualizar</a></td>
								<td>
                                    <a href="{{ $key->diagnostic_file }}" target="_blank">visualizar</a>   
                                </td>
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
                                    @if ($key->track_code)
                                    {{$key->track_code}}
                                    @else
                                    --
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($key->is_paid == 1)
                                        <button type="button" class="btn btn-sm btn-success">Concluído</button>
                                    @else
                                        @if ($key->is_cancelled == 0)
                                            @if ($key->is_payment == 1 && $key->status >= 2)
                                                <button type="button" class="btn btn-sm btn-primary">Aguard. Pagamento</button>
                                            @elseif ($key->status == 1)
                                                <button type="button" class="btn btn-sm btn-warning">Em análise</button>
                                            @elseif ($key->status == 2)
                                                <button type="button" class="btn btn-sm btn-info">Expedição</button>
                                            @elseif ($key->status == 3)
                                                <button type="button" class="btn btn-sm btn-info">Enviado</button>
                                            @elseif ($key->status == 4)
                                                <button type="button" class="btn btn-sm btn-success">Concluído</button>
                                            @endif
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger">Cancelado</button>
                                        @endif
                                    @endif 
                                </td>
                                <td>{{ date('d-m-Y', strtotime($key->created_at)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($key->updated_at)) }}</td>
                                <td class="text-center">
                                    @if($key->is_cancelled == 0)
                                        <button type="button" onclick="menu({{ $key->id }}, {{ $key->is_payment }}, {{$key->status}})" class="btn btn-sm btn-info">Ver mais</button>
                                    @else
                                        -- 
                                    @endif    
                                </td>
                            </tr>   
                            @endforeach         
                        @else    
                            <tr>
                                <td colspan="7" style="text-align: center;">Não há solicitações de remessas!<td>
                            </tr>
                        @endif    
                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <?= $remittance->render(); ?>
                    </ul>
                </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-menu" tabindex="-1" role="dialog" aria-labelledby="modal-menu" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <input type="hidden" id="remittance_id">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">ESCOLHA ABAIXO</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <ul class="list-group push text-center" style="text-transform: uppercase;">
                        <a onclick="requestPartView()" data-dismiss="modal" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="javascript:void(0)">
                            <div style="width: 100%"><b>Ver peças solicitadas</b></div>
                        </a>
                        <a target="_blank" id="linkos" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div style="width: 100%;"><b>Imprimir solicitação de remessa</b></div>
                        </a>
						<a onclick="requestFinish()" data-dismiss="modal" class="list-group-item justify-content-between align-items-center btn-finish-req" href="javascript:void(0)" style="color: #575757;">
                            <div style="width: 100%;"><b>Finalizar solicitação</b></div>
                        </a>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" style="overflow: auto !important;" id="modal-spart" role="dialog" aria-labelledby="modal-spart" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Peças solicitadas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-md-12">
                    <table class="table table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>Modelo</th>
                                <th>Peça</th>
                                <th>Quantidade</th>
                                <th>Motivo de solicitação</th>
                            </tr>
                        </thead>
                        <tbody id="listPartView">          
                        </tbody>
                    </table>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" style="overflow: auto !important;" id="modal-os" role="dialog" aria-labelledby="modal-os" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="margin-bottom: -30px;">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Finalizar solicitação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/autorizada/remessa/finalizar" method="post" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 25px;">
                    <input type="hidden" name="id" id="req_finish_id">
                    <div class="row">
                        <div class="col-md-12 description">
                            <div class="form-group">
                                <label for="description_1">Observação</label>
                                <textarea class="form-control" id="observation" name="observation" rows="5" placeholder="Descreva uma observação desta finalização..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" onclick="backMenu();" data-dismiss="modal">
                        Fechar
                    </button>
                    <button type="submit" class="btn btn-alt-primary">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#navRemittanceNote").addClass('active');
    });
	
	function requestFinish() {

        $("#req_finish_id").val($("#remittance_id").val());
        $('#modal-os').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function menu(id, is_payment, status) {
		
		if(is_payment == 1 || status == 1) {
            $(".btn-finish-req").hide();
        } else if(is_payment == 0 && status >= 2){
            $(".btn-finish-req").show();
        }

        $("#remittance_id").val(id);
        $("#linkos").removeAttr('href');
        $("#linkos").attr('href', '/autorizada/remessa/imprimir/' + id);

        $('#modal-menu').modal({
            backdrop: 'static',
            keyboard: false
        });
    }    

    function requestPartView() {
        $('.btn-block-option').click();
        $.ajax({
            type: "GET",
            url: "/autorizada/remessa/lista/peca/" + $("#remittance_id").val(),
            success: function(response) {

                if (response.success) { 

                    $('.block').removeClass("block-mode-loading");

                    var html = '';
                    for (var i = 0; i < response.parts.length; i++) {

                        var column = response.parts[i];
                        var model = column.product_air != null ? column.product_air.model : column.model;
                        var part = column.parts != null ? column.parts.description : column.part;

                        html += '<tr>';
                        html += '<td>'+ model +'</td>';
                        html += '<td>'+ part +'</td>';
                        html += '<td>'+ column.quantity +'</td>';
                        html += '<td>'+ column.description_order_part +'</td>';
                        html += '</tr>';
                    }

                    $("#listPartView").html(html);

                    $('#modal-spart').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    
                } else {
                    error(response.msg);
                }  
            }
        });  
    }
</script>
@endsection