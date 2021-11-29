@extends('gree_sac_authorized.panel.layout')

@section('content')
<div class="col-md-12">
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ $comunication->subject }}</h3>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-md-12 mb-20">
                    @if ($comunication->attach_1)
                    <a href="{{ $comunication->attach_1 }}" target="_blank"><button class="btn btn-primary" type="button">Anexo_1</button></a> 
                    @endif
                    @if ($comunication->attach_2)
                    <a href="{{ $comunication->attach_2 }}" target="_blank"><button class="btn btn-primary" type="button">Anexo_2</button></a>
                    @endif
                    @if ($comunication->attach_3)
                    <a href="{{ $comunication->attach_3 }}" target="_blank"><button class="btn btn-primary" type="button">Anexo_3</button></a>
                    @endif
                </div>
                <div class="col-md-12">
                    <?= $comunication->description ?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection