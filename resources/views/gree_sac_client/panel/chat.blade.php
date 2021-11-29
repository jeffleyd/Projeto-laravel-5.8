@extends('gree_sac_client.panel.layout')

@section('content')
<div class="row gutters-tiny push" >
    <div class="col-12 @if ($protocol->is_cancelled == 0 and $protocol->is_completed == 0) col-md-6 col-xl-6 @else col-md-12 col-xl-12 @endif">
        <a class="block block-rounded block-bordered block-link-shadow text-center" href="/suporte/painel">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-action-undo fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Voltar</p>
            </div>
        </a>
    </div>
    @if ($protocol->is_cancelled == 0 and $protocol->is_completed == 0)
    <div class="col-12 col-md-6 col-xl-6">
        <a class="block block-rounded block-bordered block-link-shadow text-center" onclick="endProtocol();" href="javascript:void(0)">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-close fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Encerrar atendimento</p>
            </div>
        </a>
    </div>
    @endif
</div>
<div class="row">
	<div class="col-md-12 col-lg-12">
		<div class="alert alert-warning d-flex align-items-center" role="alert">
			<p class="mb-0">
				Para enviar os arquivos, aperte sobre o icone "<i class="fa fa-fw fa-paperclip font-size-lg"></i>"!
			</p>
		</div>
	</div>
	
	<div class="col-12 col-md-3 col-lg-3">
		@if ($protocol->type != 3)
<div class="row gutters-tiny push" >
    <div class="col-12 col-md-12 col-xl-12">
        <a class="block block-rounded block-bordered block-link-shadow text-center" target="_blank" href="@if ($protocol->nf_file) {{ $protocol->nf_file }} @else javascript:void(0) @endif">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-doc fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Nota fiscal</p>
				@if (!$protocol->nf_file)
				<button type="button" class="btn btn-rounded btn-noborder btn-danger min-width-125 mb-10">Não validado</button>
				@else
				<button type="button" class="btn btn-rounded btn-noborder btn-success min-width-125 mb-10">Validado</button>
				@endif
            </div>
        </a>
    </div>
    <div class="col-12 col-md-12 col-xl-12">
        <a class="block block-rounded block-bordered block-link-shadow text-center" target="_blank" href="@if ($protocol->c_install_file) {{ $protocol->c_install_file }} @else javascript:void(0) @endif">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-note fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Comprovante de instalação</p>
				@if (!$protocol->c_install_file)
				<button type="button" class="btn btn-rounded btn-noborder btn-danger min-width-125 mb-10">Não validado</button>
				@else
				<button type="button" class="btn btn-rounded btn-noborder btn-success min-width-125 mb-10">Validado</button>
				@endif
            </div>
        </a>
    </div>
    <div class="col-12 col-md-12 col-xl-12">
        <a class="block block-rounded block-bordered block-link-shadow text-center" target="_blank" href="@if ($protocol->tag_file) {{ $protocol->tag_file }} @else javascript:void(0) @endif">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-info fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Foto da etiqueta</p>
				@if (!$protocol->tag_file)
				<button type="button" class="btn btn-rounded btn-noborder btn-danger min-width-125 mb-10">Não validado</button>
				@else
				<button type="button" class="btn btn-rounded btn-noborder btn-success min-width-125 mb-10">Validado</button>
				@endif
            </div>
        </a>
    </div>
</div>
@endif
	</div>
    <div class="col-12 col-md-9 col-lg-9 bg-white d-flex flex-column">
        <div class="js-chat-active-user p-15 d-flex align-items-center justify-content-between bg-white">
            <div class="d-flex align-items-center">
                <a class="img-link img-status" href="javascript:void(0)">
                    <img class="img-avatar img-avatar32" src="/admin/app-assets/images/ico/favicon-192x192.png" alt="Avatar">
                    <div class="img-status-indicator bg-success"></div>
                </a>
                <div class="ml-10">
                    <a class="font-w600" href="javascript:void(0)">GREE ELECTRIC APPLIANCES DO BRASIL LTDA</a>
                    <div class="font-size-sm text-muted">Tratativa interna</div>
                </div>
            </div>
        </div>
        <div class="js-chat-window p-15 bg-light flex-grow-1 text-wrap-break-word overflow-y-auto" style="height: 400.8px;">
            <div class="d-flex flex-row-reverse mb-20">
                <div class="mx-10 text-right">
                    <div>
                        <p class="bg-primary-lighter text-primary-darker rounded px-15 py-10 mb-5 d-inline-block">
                            {{ $protocol->description }}
                        </p>
                    </div>
                    <div class="text-right text-muted font-size-xs font-italic">Você ({{ date('d/m/Y', strtotime($protocol->created_at)) }})</div>
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
        @if ($protocol->is_completed == 0 and $protocol->is_cancelled == 0)
        <div class="js-chat-message p-10 mt-auto">
            <form action="/suporte/nova/mensagem" method="POST" id="sendmsg" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ $protocol->id }}">
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
        @elseif ($protocol->is_completed == 1) 
        <div class="js-chat-message p-10 mt-auto bg-success text-center text-white">
            ATENDIMENTO FOI FINALIZADO
        </div>
        @elseif ($protocol->is_cancelled == 1)
        <div class="js-chat-message p-10 mt-auto bg-danger text-center text-white">
            ATENDIMENTO FOI CANCELADO
        </div>
        @endif

    </div>
</div>

  <div class="modal fade" id="ratingProtocol" tabindex="-1" role="dialog" aria-labelledby="ratingProtocol" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">AVALIAR ATENDIMENTO</h3>
                    <div class="block-options">
                    </div>
                </div>
                <div class="block-content">
                      <div class="row">
                        <div class="col-md-12">
                            <p>Para mantermos a qualidade do atendimento, clique sobre a estrela.</p>
                        </div>
                        <div class="col-md-12 text-center p-20 mb-20">
                            <div class="js-rating" data-score="1" data-star-on="fa fa-fw fa-2x fa-star text-warning" data-star-off="fa fa-fw fa-2x fa-star text-muted"></div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="rating();" class="btn btn-alt-success"  data-dismiss="modal">
                    <i class="fa fa-check"></i> CONFIRMAR
                </button>
            </div>
        </div>
    </div>
</div>

  <div class="modal fade" id="attachUpload" tabindex="-1" role="dialog" aria-labelledby="attachUpload" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">ARQUIVO</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div id="attachShow" class="text-center mb-2">
                        <i class="fa fa-file-image-o text-primary" style="font-size:70px"></i>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <fieldset class="form-group">
                              <label for="attachMsg">Observação sobre o arquivo</label>
                              <input type="text" class="form-control" id="attachMsg" placeholder="....">
                          </fieldset>
                        </div>
                      </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" onclick="chatCleanAttach();" data-dismiss="modal">Fechar</button>
                <button type="button" onclick="chatMessagesSend();" class="btn btn-alt-success"  data-dismiss="modal">
                    <i class="fa fa-check"></i> Enviar
                </button>
            </div>
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
    function rating() {
        $.ajax({
            type: "POST",
            url: "/suporte/avaliar/atendimento",
            data: {rate: $('.js-rating').raty('score'), id: <?= $protocol->id ?>},
            success: function (response) {
                if (response.success) {
                    success('Atendimento foi avaliado com sucesso!');
                } else {
                    error(response.msg);
                }
                
            }
        });
    }
    function endProtocol() {
        Swal.fire({
            title: 'Concluir atendimento',
            text: "Ao confirmar você estará concluíndo o atendimento.",
            type: 'warning',
            input: 'text',
            inputPlaceholder: 'Digite o motivo...',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar!',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
            if ($('.swal2-input').val() != "") {

                document.location.href = "/suporte/encerrar/atendimento?id=<?= $protocol->id ?>&description=" + result.value;
                Codebase.loader('show', 'bg-gd-sea'); 
            } else {
                error('Você precisa dizer o motivo da conclusão.');
            }
        })
    }
    $(document).ready(function () {
        @if ($protocol->is_completed == 1 and $protocol->rate == 0 and $protocol->type != 3)
        setTimeout(() => {
            $('#ratingProtocol').modal({
                backdrop: 'static',
                keyboard: false
            });
        }, 500);
        @endif

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