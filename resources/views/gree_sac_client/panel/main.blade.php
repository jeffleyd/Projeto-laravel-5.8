@extends('gree_sac_client.panel.layout')

@section('content')
    <div class="row gutters-tiny push" >
        <div class="col-12 col-md-12 col-xl-12" onclick="document.location.href='/suporte/novo/atendimento';">
            <a class="block block-rounded block-bordered block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content">
                    <p class="mt-5">
                        <i class="si si-plus fa-3x text-muted"></i>
                    </p>
                    <p class="font-w600">Novo atendimento</p>
                </div>
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="block block-rounded block-bordered">
                <div class="block-header">
                    <h3 class="block-title text-uppercase">Lista de atendimentos</h3>
                </div>
                <div class="block-content p-5">
                    <div class="table-responsive">
                    <table class="table table-borderless table-striped mb-0">
                        <thead>
                            <tr style="font-size: 14px;">
                                <th>#Protocolo</th>
                                <th>Assunto</th>
                                <th>Modelo(s)</th>
                                <th>Status</th>
                                <th class="text-center">Criando em</th>
                                <th class="d-none d-sm-table-cell text-center">Avaliação</th>
                                <th class="d-none d-sm-table-cell text-center">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($protocol as $key)
                            <tr style="font-size: 12px;">
                                <td>
                                    <a class="font-w600" href="javascript:void(0)">{{ $key->code }}</a>
                                </td>
                                <td>
                                    @if ($key->type == 1)
                                    Reclamação
                                    @elseif ($key->type == 2)
                                    Atend. Garantia
                                    @else
                                    Dúvida técnica
                                    @endif
                                </td>
                                <td class="text-black">
                                    <?php $models = \App\Model\SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')->select('product_air.model')->where('sac_protocol_id', $key->id)->get(); ?>
                                    @foreach ($models as $item)
                                        {{ $item->model .' ' }}
                                    @endforeach
                                </td>
                                <td>
                                    @if ($key->is_denied == 1)
                                    <span class="badge badge-danger">Finalização negada</span>
                                    @elseif ($key->is_cancelled == 1)
                                    <span class="badge badge-danger">Cancelado</span>
                                    @elseif ($key->pending_completed == 1)
                                    <span class="badge badge-warning">Pendente p/ completar</span>
                                    @elseif ($key->is_completed == 1)
                                    <span class="badge badge-success">Concluído</span>
                                    @elseif ($key->in_progress == 1)
                                    <span class="badge badge-warning">Em andamento</span>
                                    @elseif ($key->in_wait == 1)
                                    <span class="badge badge-info">Aguardando</span>
                                    @endif
                                </td>
                                <td class="text-black text-center">
                                    {{ date('d/m/Y', strtotime($key->created_at)) }}
                                </td>
                                <td class="d-none d-sm-table-cell text-center">
                                    <div>
                                        <i class="fa fa-star @if ($key->rate > 0) text-warning @elseif ($key->rate < 1) text-muted @endif"></i>
                                        <i class="fa fa-star @if ($key->rate > 1) text-warning @elseif ($key->rate < 2) text-muted @endif"></i>
                                        <i class="fa fa-star @if ($key->rate > 2) text-warning @elseif ($key->rate < 3) text-muted @endif"></i>
                                        <i class="fa fa-star @if ($key->rate > 3) text-warning @elseif ($key->rate < 4) text-muted @endif"></i>
                                        <i class="fa fa-star @if ($key->rate > 4) text-warning @elseif ($key->rate < 5) text-muted @endif"></i>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" onclick="document.location.href='/suporte/interacao/atendimento/{{ $key->id }}';" class="btn btn-sm btn-outline-primary mb-10">Interagir</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Orders navigation">
                        <ul class="pagination justify-content-end">
                            <?= $protocol->render(); ?>
                        </ul>
                    </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    @endsection