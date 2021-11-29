@extends('gree_i.layout')

@section('content')

<style>
    pre {
        background-color: #ffffff;
        font-size: 1em;
        color: #26282a;
        font-family: "IBM Plex Sans", Helvetica, Arial, serif;
    }
    .select-border {
        border: 1px solid #5a8dee;
    }

    table.details-table td {
        padding-right: 10px;
        padding-top: 10px; 
        padding-bottom:10px
    }
</style>

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">TI - Suporte e Manutenção</h5>
                    <div class="breadcrumb-wrapper col-12">
                        ID Rastreio: {{ $trackid }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section>
            <div class="row">
                <div class="col-lg-8 col-md-8 col-12 order-2 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-inline-block">
                                <h6 class="text-bold-0 font-small-3"><span class="text-muted">Solicitidado por</span> {{ $first_name }} {{ $last_name }} <span class="text-muted"><small>{{ $created_at }}</small></span></h6>
                            </div>
                            @if (hasPermManager(4))
                            <div class="dropleft float-right">
                                <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="/ti/maintenance/edit/<?= $maintenance_id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                </div>
                            </div>
                            @endif
                            <br><br>
                            <h4 class="card-title">
                                {{ $subject }}
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <blockquote class="blockquote pl-1 border-left-primary border-left-3">
                                    <pre style="white-space: pre-wrap;">{{ $message }}</pre>
                                    <br>
                                    <?php if (!empty($attach)) { ?>
                                        <a href="<?= $attach ?>" target="_blank" class="text-primary font-weight-bold">Anexo</a>
                                    <?php } ?>
                                </blockquote>
                                <br>
                                @if (hasPermManager(4))
                                @foreach($maintenance->notes()->get() as $note)
                                    <div class="alert bg-rgba-warning alert-dismissible mb-2" role="alert">
                                        <button type="button" class="close note_delete" aria-label="Close" value="<?= $note->id?>">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <p class="mb-0"><span class="text-muted">Nota de<span> <span style="color:#26282a;">{{ $note->users()->first_name }} {{ $note->users()->last_name }}</span> as <span class="text-muted">{{ $note->created_at }}</span></p><br>
                                        <p class="mb-0" style="color:#26282a;"> {{ $note->message }} </p>
                                        <?php if (!empty($note->attach)) { ?>
                                            <a href="<?= $note->attach ?>" target="_blank" class="text-primary font-weight-bold">Anexo</a>
                                        <?php } ?>
                                    </div>
                                @endforeach
                                <br>
                                <button type="button" id="add_note" class="btn btn-outline-primary mr-1 mb-1">
                                    <i class="bx bx-note"></i><span class="align-middle ml-25">Adicionar nota</span>
                                </button>
                                @endif
                                <form action="/ti/maintenance/note" id="form_note" method="post" enctype="multipart/form-data" style="display:none;">
                                    <input type="hidden" name="maintenance_id" id="maintenance_id" value="<?= $maintenance_id ?>">
                                    <input type="hidden" name="id" value="0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <small>Só usuários de TI poderão ver a nota!</small>
                                                <textarea id="message" rows="4" class="form-control" name="message" required></textarea>
                                            </div>
                                            <fieldset class="form-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="attach" name="attach">
                                                    <label class="custom-file-label" for="attach">Escolha arquivo</label>
                                                </div>
                                            </fieldset>
                                            <button type="submit" class="btn btn-primary">Criar Nota</button>
                                        </div>    
                                    </div>    
                                </form>    
                            </div>
                        </div>
                    </div>
                    @foreach($maintenance->replies()->get() as $replie)
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header user-profile-header" style="padding-bottom: 0;">
                                <div class="d-inline-block">
                                    <h6 class="text-bold-200"><span class="text-muted">Respondido por</span> {{ $replie->users()->first_name }} {{ $replie->users()->last_name }}</h6>
                                    <p class="text-muted"><small>{{ $replie->created_at }}</small></p>
                                </div>
                            </div>
                            <blockquote class="blockquote pl-1 border-left-primary border-left-3">
                                <pre style="white-space: pre-wrap;"><?= $replie->message ?></pre>
                                <?php if (!empty($replie->attach)) { ?>
                                    <a href="<?= $replie->attach ?>" target="_blank" class="text-primary font-weight-bold">Anexo</a>
                                <?php } ?>
                            </blockquote>
                        </div>
                    </div> 
                    @endforeach
                    @if (hasPermManager(4) || $request_r_code == Session::get('r_code'))
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <form action="/ti/maintenance/replie" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="maintenance_id" id="maintenance_id" value="<?= $maintenance_id ?>">
                                    <input type="hidden" name="id" value="0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="message">Envie uma resposta</label>
                                                <textarea id="message" rows="4" class="form-control" name="message" placeholder="Digite uma mensagem" required></textarea>
                                            </div>
                                            <fieldset class="form-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="attach" name="attach">
                                                    <label class="custom-file-label" for="attach">Escolha arquivo</label>
                                                </div>
                                            </fieldset>
                                            <button type="submit" class="btn btn-primary">Enviar resposta</button>
                                        </div>
                                    </div>    
                                </form>     
                            </div>    
                        </div>    
                    </div>
                    @endif
                    
                    <div class="card text-center" style="width: 395px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; opacity: 0.9;z-index: 99; @if (!hasPermManager(4)) display:none @endif">
                        <div class="card-header">
                          <h4 class="card-title">Cronômetro</h4>
                        </div>
                        <div class="card-content">
                          <div class="card-body">
                            <div style="width: 100%;">
                                <div id="end_job_div" style="display:none;" class="mr-30"><span id="end_time_text">{{ __('homeoffice_i.ws_06') }}</span><h4 id="end_job_time">00:00:00</h4></div>
                            </div>
                            <div class="spinner-grow loading-task" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-12 order-1 order-md-2">
                    <div class="card">
                        <div class="card-body">
                            <table class="details-table">
                                <tbody>
                                    <tr>
                                        <td>Status:</td>
                                        <td>
                                            <select class="form-control select-border" @if (!hasPermManager(4)) disabled @endif id="status" name="status">
                                                <option value="1" @if($status == 1) selected @endif>Novo</option>
                                                <option value="2" @if($status == 2) selected @endif>Responder</option>
                                                <option value="3" @if($status == 3) selected @endif>Respondido</option>
                                                <option value="4" @if($status == 4) selected @endif>Em Progresso</option>
                                                <option value="5" @if($status == 5) selected @endif>Em Espera</option>
                                                <option value="6" @if($status == 6) selected @endif>Resolvido</option>
                                                <option value="7" @if($status == 7) selected @endif>Encaminhada para o Setor de compras</option>
                                                <option value="8" @if($status == 8) selected @endif>Aguardando Aprovação</option>
                                                <option value="9" @if($status == 9) selected @endif>Aguardando Toner Para troca.</option>
                                                <option value="10" @if($status == 10) selected @endif>Agendada com o Solicitante</option>
                                                <option value="11" @if($status == 11) selected @endif>Reserva em Andamento</option>
                                                <option value="12" @if($status == 12) selected @endif>Aguardando Setor Manutenção</option>
                                                <option value="13" @if($status == 13) selected @endif>Enviado para Assistência Técnica</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Categoria:</td>
                                        <td>
                                            <select class="form-control select-border" @if (!hasPermManager(4)) disabled @endif id="category" name="category_id">
                                                @foreach($categories as $key)
                                                    <option value="{{ $key->id }}" @if ($category == $key->id) selected @endif>{{ $key->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Prioridade:</td>
                                        <td>
                                            <select class="form-control select-border" @if (!hasPermManager(4)) disabled @endif id="priority" name="priority">
                                                <option value="1" @if($priority == 1) selected @endif>Baixa</option>
                                                <option value="2" @if($priority == 2) selected @endif>média</option>
                                                <option value="3" @if($priority == 3) selected @endif>Alta</option>
                                                <option value="4" @if($priority == 4) selected @endif>Crítica</option>
                                            </select>    
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr/>
                            <label>Atribuído a</label>
                            <select class="form-control" @if (!hasPermManager(4)) disabled @endif id="assigned" name="assigned" multiple>
                                @foreach ($users as $key)
                                        <option value="{{ $key->r_code }}" @foreach ($maintenance->assigns()->get() as $assig) @if ($key->r_code == $assig->r_code) selected @endif @endforeach>{{ $key->first_name }} {{ $key->last_name }} ({{ $key->r_code }})</option>
                                @endforeach
                            </select><br><br>
                            <button type="button" @if (!hasPermManager(4)) style="display:none;" @endif class="btn btn-primary" id="btn_assigned">Atribuir</button>
                        </div>
                    </div>   
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Detalhes</h5>
                            <table class="details-table">
                                <tbody>
                                    <tr>
                                        <td>Id rastreio:</td><td>{{ $trackid }}</td>
                                    </tr>
                                    <tr>
                                        <td>Unidade:</td>
                                        <td>
                                            @if ($unit == 1)  
                                                <span>ADMINISTRATIVO</span>
                                            @elseif ($unit == 2)
                                                <span>GALPÃO 1</span>
                                            @elseif ($unit == 3)    
                                                <span>GALPÃO 2</span>
                                            @elseif ($unit == 4)    
                                                <span>GALPÃO 3</span>    
											@elseif ($unit == 5)    
                                                <span>AZALEIA</span>
                                            @elseif ($unit == 6)    
                                                <span>SUZUKI G1</span>
                                            @elseif ($unit == 7)    
                                                <span>SUZUKI G2</span>
                                            @endif
                                            
                                        </td>
                                    </tr>
                                    <tr><td>Setor:</td><td>{{ $sector }}</td></tr>
                                    <tr><td>Ramal:</td><td>{{ $ext_phone }}</td></tr>
                                    @if ($printer_model != null)
                                        <tr><td>Modelo da Impresora:</td><td>{{ $printer_model }}</td></tr>
                                    @endif
                                    @if($toner_model != null)
                                        <tr><td>Modelo do Toner:</td><td>{{ $toner_model }}</td></tr>
                                    @endif
                                    @if ($start_reserve != null && $final_reserve != null)
                                        <tr><td>Início da reserva:</td><td>{{ date('Y-m-d', strtotime($start_reserve)) }}</td></tr>
                                        <tr><td>Final da reserva:</td><td>{{ date('Y-m-d', strtotime($final_reserve)) }}</td></tr>
                                    @endif
                                    <tr><td>Acesso Computador:</td><td>{{ $access_comp }}</td></tr>
                                    <tr><td>Criado em:</td><td>{{ $created_at }}</td></tr>
                                    <tr><td>Atualizado:</td><td>{{ $created_at }}</td></tr>
                                </tbody>    
                            </table>    
                        </div>    
                    </div>    
                </div>
            </div>
        </section>
    </div>
</div>
<script>

    var time_start, time_end;
    var interval;
    
    
    function setTime(status, _time) {
        clearInterval(interval);
        if(status == 4) {
            $("#end_time_text").text('Em progresso');
        } else if(status == 5) {
            $("#end_time_text").text('Em Empera');
        } else if(status == 6) {
            $("#end_time_text").text('Resolvido');
        }else if(status == 7) {
            $("#end_time_text").text('Encaminhada para o Setor de compras');
        }
        else {
            $("#end_time_text").text('Em Empera');
        }

        var seconds = _time;
        if(status == 4) {
            // Update the count down every 1 second
            interval = setInterval(function() {
                
                var days        = Math.floor(seconds/24/60/60);
                var hoursLeft   = Math.floor((seconds) - (days*86400));
                var hours       = Math.floor(hoursLeft/3600);
                var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
                var minutes     = Math.floor(minutesLeft/60);
                var remainingSeconds = seconds % 60;
                function pad(n) {
                    return (n < 10 ? "0" + n : n);
                }
                document.getElementById("end_job_time").innerHTML = pad(hours) + ":" + pad(minutes) + ":" + pad(remainingSeconds);
                seconds++;
                

            }, 1000);
        } else {
            var days        = Math.floor(seconds/24/60/60);
            var hoursLeft   = Math.floor((seconds) - (days*86400));
            var hours       = Math.floor(hoursLeft/3600);
            var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
            var minutes     = Math.floor(minutesLeft/60);
            var remainingSeconds = seconds % 60;
            function pad(n) {
                return (n < 10 ? "0" + n : n);
            }
            document.getElementById("end_job_time").innerHTML = pad(hours) + ":" + pad(minutes) + ":" + pad(remainingSeconds);
        }

        $(".loading-task").hide();
        $("#end_job_div").show();
        
    } 

    $(document).ready(function () {

        @if ($status == 4)
        setTime({{$status}}, {{$start_time}});
        @elseif ($status == 5 || $status == 7)
        setTime({{$status}}, {{$stop_time}});
        @elseif ($status == 6)
        setTime({{$status}}, {{$end_time}});
        @else
        $(".loading-task").hide();
        $("#end_job_div").show();
        document.getElementById("end_job_time").innerHTML = "00:00:00";
        $("#end_time_text").text('Não iniciado');
        @endif

        $("#assigned").select2({
            placeholder: "Atendimento não atribuído"
        });

        $("#add_note").click(function(){
            if($("#form_note").css('display') == 'block') {
                $("#form_note").css('display', 'none');
            }
            else {
                $("#form_note").css('display', 'block');
            }
        });

        $("#btn_assigned").click(function(){
            block();
            ajaxSend('/ti/maintenance/info/ajax', {field: $("#assigned").attr("name"), value: $("#assigned").val(), maintenance_id: $("#maintenance_id").val()}, 'POST', 3000).then(function(result) {
                unblock();
                $success(result.message);
                if (result.time != null)
                console.log(result);
                setTime(result.status, result.time);
            }).catch(function(err){
                unblock();
                $error(err.message);
            });
        });

        $("#status, #category, #priority").change(function(){
            block();
            ajaxSend('/ti/maintenance/info/ajax', {field: $(this).attr("name"), value: $(this).val(), maintenance_id: $("#maintenance_id").val()}, 'POST', 3000).then(function(result) {
                unblock();
                $success(result.message);
                if (result.time != null)
                console.log(result);
                setTime(result.status, result.time);
            }).catch(function(err){
                unblock();
                $error(err.message);
                $("#status").val(1);
            });
        });    

        $(".note_delete").click(function(){

            var  value = $(this).val();
            Swal.fire({
                title: '<?= __('news_i.la_11') ?>',
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
                        window.location.href = "/ti/maintenance/note/delete/"+ value;
                    }
                })
        });

        setInterval(() => {
            $("#mTI").addClass('sidebar-group-active active');
            $("#mTIMaintenance").addClass('sidebar-group-active active');
            $("#mTIMaintenanceList").addClass('active');
        }, 100);
    });
    </script>
@endsection