@extends('gree_i.layout')

@section('content')
<style>
    .handle {
        cursor: move;
    }
    .row.vertical-divider [class*='col-']:not(:last-child)::after {
        background: #e0e0e0;
        width: 1px;
        content: "";
        display:block;
        position: absolute;
        top:0;
        bottom: 0;
        right: 0;
        min-height: 70px;
    }
</style>
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Assistência técnica</h5>
              <div class="breadcrumb-wrapper col-12">
                    Modelos da O.S: {{ $code_os }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row"></div>
    <div class="content-body">
        <section>
            <div class="row vertical-divider">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Modelos OS
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <p>Inclua o modelo na ordem de serviço arrastando para bloco de <code>Modelos na Ordem de Serviço</code></p>
                                <div class="row">
                                    <input type="hidden" id="os_id" value="<?= $id ?>">
                                    <input type="hidden" id="authorized_id" value="<?= $authorized_id ?>">
                                    <input type="hidden" id="sac_protocol_id" value="<?= $sac_protocol_id ?>">
                                    <div class="col-sm-6">
                                        <h4 class="my-1">Modelos no Protocolo</h4>
                                        <ul class="list-group" id="handle-list-1" style="min-height: 5.714rem;">
                                            @foreach ($sac_protocol_os as $model_prot)
                                                <li data-id="{{ $model_prot->id }}" class="list-group-item handle"><?=  $model_prot->sacProductAir()->first()->model ?? 'Sem modelo' ?>
                                                    <button type="button" class="close model_delete btn_model_del" aria-label="Close" value="{{ $model_prot->id }}" style="display: none;">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-sm-6">
                                        <h4 class="my-1">Modelos na Ordem de Serviço</h4>
                                        <ul class="list-group" id="handle-list-2" style="min-height: 5.714rem;">
                                            @foreach ($models_os->get() as $model_os)
                                                <li data-id="{{ $model_os->sac_model_protocol_id }}" class="list-group-item handle"><?=  $model_os->sacProductAir()->first()->model ?? 'Sem modelo' ?>
                                                    <button type="button" class="close model_delete" aria-label="Close" value="{{ $model_os->sac_model_protocol_id }}">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>    
                </div>    
            </div>    
        </section>    
    </div>
</div>
<script>
    $(document).ready(function(){
        var drake = dragula([document.getElementById("handle-list-1"),document.getElementById("handle-list-2")],{
            moves:function(el, container, handler){
                return handler.classList.contains("handle");
            },
            accepts: function (el, target) {
                return target !== document.getElementById("handle-list-1");
            }
        });
        drake.on('drop', function (el, target) {
            block();
            ajaxSend('/sac/warranty/os/model/ajax', {model_id: el.getAttribute('data-id'), os_id: $("#os_id").val(), authorized_id: $("#authorized_id").val(), sac_protocol_id: $("#sac_protocol_id").val()}, 'POST', 3000).then(function(result) {
                unblock();
                $('#handle-list-2').find('.btn_model_del').css('display', '');
                $success(result.message);
            }).catch(function(err){
                unblock();
                $error(err.message);
            });
        });
        $(".model_delete").click(function(){
            var os_id = $("#os_id").val();
            var model_id = $(this).val();
            Swal.fire({
                title: 'Deletar Modelo da OS',
                text: "<?= __('news_i.la_12') ?>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= __('trip_i.tn_fly_toast_yes') ?>',
                cancelButtonText: '<?= __('trip_i.tn_fly_toast_no') ?>',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        block();
                        window.location.href = "/sac/warranty/os/model/delete/"+ os_id +"/"+model_id;
                    }
                })
            });
        });
</script>    

@endsection