@extends('gree_sac_authorized.panel.layout')

@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Perguntas frequentes</h3>
    </div>
    <div class="block-content">
        <div id="accordion" role="tablist" aria-multiselectable="true">

            @foreach ($faq_authorized as $index=>$key)
            <div class="block block-bordered block-rounded mb-2">
                <div class="block-header bg-gray-light" role="tab" id="accordion_h{{$index}}">
                    <a class="font-w600 collapsed" data-toggle="collapse" data-parent="#accordion" href="#accordion_q{{$index}}" aria-expanded="false" aria-controls="accordion_q{{$index}}">
                        <i class="si si-question fa-1x"></i> <?= $key->question ?>
                    </a>
                </div>
                <div id="accordion_q{{$index}}" class="collapse" role="tabpanel" aria-labelledby="accordion_h{{$index}}" data-parent="#accordion" style="">
                    <div class="block-content">
                        <?= $key->answer ?>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>



@endsection
