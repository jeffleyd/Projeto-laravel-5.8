@extends('gree_sac_authorized.panel.layout')

@section('content')
<div class="row gutters-tiny push" >
    <div class="col-12 col-md-4 col-xl-4">
        <a class="block block-rounded block-bordered block-link-shadow text-center" href="/autorizada/os">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-action-undo fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Voltar</p>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-4 col-xl-4">
        <a class="block block-rounded block-bordered block-link-shadow text-center" @if ($os->diagnostic_test != '') target="_blank" @endif href="@if ($os->diagnostic_test != '') {{ $os->diagnostic_test }} @else javascript:void(0) @endif">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-doc fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Relatório técnico</p>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-12 col-xl-4">
        <a class="block block-rounded block-bordered block-link-shadow text-center" @if ($os->os_signature != '') target="_blank" @endif href="@if ($os->os_signature != '') {{ $os->os_signature }} @else javascript:void(0) @endif">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-note fa-3x text-muted"></i>
                </p>
                <p class="font-w600">OS assinada</p>
            </div>
        </a>
    </div>
</div>
<div class="row gutters-tiny push">
    <div class="col-md-12 col-lg-12 bg-white d-flex flex-column">
        <div class="js-chat-active-user p-15 d-flex align-items-center justify-content-between bg-white">
            <div class="d-flex align-items-center">
                <a class="img-link img-status" href="javascript:void(0)">
                    <img class="img-avatar img-avatar32" src="/admin/app-assets/images/ico/favicon-192x192.png" alt="Avatar">
                    <div class="img-status-indicator bg-success"></div>
                </a>
                <div class="ml-10">
                    <a class="font-w600" href="javascript:void(0)">INTERAÇÃO COM ASSISTÊNCIA - 
                        @if($os->code != null)
                            OS: {{$os->code}}
                        @else
                            Protocolo: {{ $os->sacProtocol()->first()->code }}
                        @endif
                    </a>
                </div>
            </div>
        </div>
        <div class="js-chat-window p-15 bg-light flex-grow-1 text-wrap-break-word overflow-y-auto" style="height: 400.8px;">
            <div class="d-flex flex-row-reverse mb-20">
                <div class="mx-10 text-right">
                    <div>
                        <p class="bg-primary-lighter text-primary-darker rounded px-15 py-10 mb-5 d-inline-block">
                            {{ $os->sacProtocol()->first()->description }}
                        </p>
                    </div>
                    <div class="text-right text-muted font-size-xs font-italic">Você ({{ date('d/m/Y', strtotime($os->created_at)) }})</div>
                </div>
            </div>
            @if (count($messages) > 0)
            @foreach($messages as $key)
            @if ($key->r_code != null)
            <div class="d-flex mb-20">
                <div class="mx-10">
                    <div>
                        <p class="bg-white text-dark rounded px-15 py-10 mb-5">
                            @if ($key->file)
                                <?php $path_info = pathinfo($key->file); ?>
                                @if (isset($path_info['extension']))
                                    @if ($path_info['extension'] == "jpg" or $path_info['extension'] == "jpeg" or $path_info['extension'] == "png" or $path_info['extension'] == "gif")
                                    <a href="{{ $key->file }}" target="_blank"><img class="img-fluid" height="200" width="260" src="{{ $key->file }}" alt=""></a>
                                    @else
                                    <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer">
                                        <i class="fa fa-file" style="font-size: 90px;"></i>
                                    </a>
                                    @endif
                                @else
                                <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer">
                                    <i class="fa fa-file" style="font-size: 90px;"></i>
                                </a>
                                @endif
                            @endif
                            
                            <br>
                            <?= $key->message ?>
                        </p>
                    </div>
                    <div class="text-muted font-size-xs font-italic">GREE ({{ date('d/m/Y', strtotime($key->created_at)) }})</div>
                </div>
            </div>
            @elseif ($key->authorized_id)
            <div class="d-flex mb-20">
                <div class="mx-10">
                    <div>
                        <p class="bg-warning text-dark rounded px-15 py-10 mb-5">
                            <?= $key->message ?>
                        </p>
                    </div>
                    <div class="text-muted font-size-xs font-italic">{{ getNameAuthorizedFull($key->authorized_id) }} ({{ date('d/m/Y', strtotime($key->created_at)) }})</div>
                </div>
            </div>
            @elseif ($key->is_system == 1)
            <div class="d-flex justify-content-center mb-20">
                <div class="mx-10">
                    <div>
                        <p class="bg-body-dark text-dark rounded px-15 py-10 mb-5 text-center">
                            <?= $key->message ?>
                        </p>
                    </div>
                    <div class="text-muted font-size-xs font-italic"></div>
                </div>
            </div>
            @else
            <div class="d-flex flex-row-reverse mb-20">
                <div class="mx-10 text-right">
                    <div>
                        <p class="bg-primary-lighter text-primary-darker rounded px-15 py-10 mb-5 d-inline-block">

                            @if ($key->file)
                            <?php $path_info = pathinfo($key->file); ?>
                            @if (isset($path_info['extension']))
                            @if ($path_info['extension'] == "jpg" or $path_info['extension'] == "jpeg" or $path_info['extension'] == "png" or $path_info['extension'] == "gif")
                            <a href="{{ $key->file }}" target="_blank"><img class="img-fluid" height="200" width="260" src="{{ $key->file }}" alt=""></a>
                            @else
                            <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer">
                                <i class="fa fa-file" style="font-size: 90px;"></i>
                            </a>
                            @endif
                            @else
                            <a href="{{ $key->file }}" target="_blank" rel="noopener noreferrer">
                                <i class="fa fa-file" style="font-size: 90px;"></i>
                            </a>
                            @endif
                            
                            <br>
                            @endif
                            <?= $key->message ?>
                        </p>
                    </div>
                    <div class="text-right text-muted font-size-xs font-italic">Você ({{ date('d/m/Y', strtotime($key->created_at)) }})</div>
                </div>
            </div>
            @endif
            @endforeach
            @endif 
        </div>
        <div class="js-chat-message p-10 mt-auto">
            <form action="/autorizada/os/interacao/mensagem" method="POST" id="sendmsg" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ $id }}">
                <input type="hidden" name="r_code" value="{{ $os->sacProtocol()->first()->r_code }}">
                <input type="hidden" name="os_code" id="os_code" value="{{ $os->code }}">
                <input type="hidden" name="protocol_code" id="protocol_code" value="{{ $os->sacProtocol()->first()->code }}">
                <div class="d-flex align-items-center">
                    <button type="button" id="clickAttach" class="btn btn-alt-secondary btn-circle mr-5">
                        <i class="fa fa-fw fa-paperclip font-size-lg"></i>
                    </button>
                    <input type="file" id="attach" name="attach" style="display: none;">
                    <input type="text" name="msg" id="msg" class="form-control flex-grow mr-5" placeholder="Digite sua mensagem...">
                    <button type="submit" class="btn btn-circle btn-alt-primary">
                        <i class="fa fa-send"></i>
                    </button>    
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/pages/be_comp_rating.min.js"></script>
<script>
    function chatCleanAttach() {
        $('#attach').val("");
    }
    function chatMessagesSend() {
        $("#msg").val($("#attachMsg").val());
        $("#sendmsg").submit();
    }
    
    $(document).ready(function () {

        $(".js-chat-window").animate({ scrollTop: $(document).height() }, 1000);
        $("#sendmsg").submit(function (e) {
            if ($("#attach").val() == "") {
                if ($("#msg").val() == "") {
                    
                    return e.preventDefault();
                }
            }
            Codebase.loader('show', 'bg-gd-sea'); 
        });

        $("#clickAttach").click(function (e) { 
            $('#attach').trigger('click');
        });

        $("#attach").change(function(){
            $('#attachUpload').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
    });
</script>
@endsection