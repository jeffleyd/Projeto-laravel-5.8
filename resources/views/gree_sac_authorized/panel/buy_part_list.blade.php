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
                <h3 class="block-title">Todos pedidos de compra</h3>
                <div class="block-options">
                    <a href="/autorizada/comprar/peca">
                        <button type="button" class="btn btn-sm btn-info">Novo pedido de compra</button>
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
                            <th>Status</th>
                            <th>Rastreio</th>
                            <th>Custo do frete</th>
                            <th>Total</th>
                            <th>Feito em</th>
                            <th>Última atualização</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ob as $key)
                        <tr>
                            <td class="text-center">{{ $key->code }}</td>
                            <td class="text-center">
                                @if ($key->is_cancelled == 0)
                                    @if ($key->status == 1)
                                    <button type="button" class="btn btn-sm btn-warning">Em análise</button>
                                    @elseif ($key->status == 6)
                                    <button type="button" class="btn btn-sm btn-info">Imprimido</button>
                                    @elseif ($key->status == 2)
                                    <button type="button" class="btn btn-sm btn-info">Aguardando pagamento</button>
                                    @elseif ($key->status == 3)
                                    <button type="button" class="btn btn-sm btn-info">Enviado</button>
                                    @elseif ($key->status == 4)
                                    <button type="button" class="btn btn-sm btn-success">Concluído</button>
                                    @endif
                                @else
                                <button type="button" class="btn btn-sm btn-danger">Cancelado</button>
                                @endif
                            </td>
                            <td>
                                @if ($key->track_code)
                                {{$key->track_code}}
                                @else
                                --
                                @endif
                            </td>
                            <td>{{number_format($key->shipping_cost, 2, '.', '')}}</td>
                            <td>{{number_format($key->total, 2, '.', '')}}</td>
                            <td>{{ date('d-m-Y', strtotime($key->created_at)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($key->updated_at)) }}</td>
                            <td class="text-center">
                                <a href="/autorizada/print/ob/{{ $key->id }}" target="_blank"><button type="button" class="btn btn-sm btn-secondary">Ver mais</button></a>
                            </td>
                        </tr>   
                        @endforeach         
                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <?= $ob->render(); ?>
                    </ul>
                </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $("#navBuyPart").addClass('active');
    });
</script>
@endsection