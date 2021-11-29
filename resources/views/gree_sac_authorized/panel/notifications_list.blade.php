@extends('gree_sac_authorized.panel.layout')

@section('content')
<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-12 col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Todos notificações</h3>
                <div class="block-options">
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
                            <th>Assunto</th>
                            <th>Criado em</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comunication as $key)
                        <tr>
                            <td>{{ $key->subject }}</td>
                            <td>{{ date('d-m-Y', strtotime($key->created_at)) }}</td>
                            <td class="text-center">
                                @if ($key->link_external)
                                <a href="{{ $key->link_external }}" target="_blank"><button type="button" class="btn btn-sm btn-primary">Ver mais</button></a>
                                @else
                                <a href="/autorizada/comunicado/ver/<?= $key->id ?>"><button type="button" class="btn btn-sm btn-primary">Ver mais</button></a>
                                @endif
                            </td>
                        </tr>   
                        @endforeach         
                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <?= $comunication->render(); ?>
                    </ul>
                </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
	$("#navComunic").addClass('active');
    });
</script>
@endsection