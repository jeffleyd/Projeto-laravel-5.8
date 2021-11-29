@extends('gree_commercial_promoter.layout')
@section('content')
<style>
.cardbox {
    box-shadow: -8px 12px 18px 0 rgba(25,42,70,.13);
    -webkit-transition: all .3s ease-in-out;
    transition: all .3s ease-in-out;
    min-height: 155px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    object-fit: cover;
}
</style>
<link href="/elite/assets/node_modules/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
<link href="/elite/assets/node_modules/cropper/cropper.min.css" rel="stylesheet">
<input type="file" accept="image/*" name="attach_trick" id="attach_trick" style="display:none">
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Roterização</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Roterização</li>
            </ol>
            {{-- <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button> --}}
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    @foreach ($routes as $route)
    <!-- column -->
    <div class="col-lg-3 col-md-6">
        <!-- Card -->
        <div class="card">
            <div class="float-right @if ($route->is_completed == 0 and $route->is_cancelled == 0) bg-warning @elseif ($route->is_completed == 1) bg-success @elseif ($route->is_cancelled == 1) bg-danger @endif" style="position: absolute;color: white;width: 180px;text-align: center;right: 0;line-height: 1.4;">
                @if ($route->is_completed == 0 and $route->is_cancelled == 0)
                Pendente para realizar
                @elseif ($route->is_completed == 1)
                Concluída
                @elseif ($route->is_cancelled == 1)
                Cancelada
                @endif
            </div>
            <a target="_blank" href="https://maps.google.com/?q={{$route->address}}">
            <img class="card-img-top img-responsive" src="https://maps.googleapis.com/maps/api/staticmap?zoom=14&size=353x235&maptype=roadmap&markers=icon:{{Request::root()}}/elite/assets/images/promoter/bluedot.png%7Clabel:A%7C{{$route->latitude}},{{$route->longitude}}&key={{ getConfig("google_key_web") }}" alt="Local do serviço">
            </a>
            <div class="bg-secondary" >
                <div class="float-left" style="border-right: solid 1px;padding: 10px;margin-right: 10px;">
                <i class="ti-calendar" style=""></i> <span style="margin-left: 8px;">{{date('d/m/Y', strtotime($route->date_start))}} - {{date('d/m/Y', strtotime($route->date_end))}}</span>
                </div>
                <div style="padding: 10px;">
                    <i class="ti-list" style="position: relative;top: 1px;"></i> <span style="margin-left: 5px;">{{$route->routeHistory()->count()}} tarefas</span>
                </div>
            </div>
            <div class="card-body text-center">
                <p class="card-text text-left">{{$route->description}}</p>
            </div>
            <div class="row">
                @if ($route->checkin and $route->checkout)
                <div class="col-12">
                    <a json-data="<?= htmlspecialchars(json_encode($route->routeHistory), ENT_QUOTES, 'UTF-8') ?>" onclick="detail(this)" href="javascript:void(0)" class="btn waves-effect waves-light btn-block btn-primary" style="border-radius:0px">Tarefas</a>
                </div>
                @else
                <div class="col-6" style="padding: 0;padding-left: 10px;">
                    @if (!$route->checkin)
                    <button type="button" onclick="updateStatus(this, {{$route->id}}, 1)" class="btn waves-effect waves-light btn-block btn-info" style="border-radius: 0;">Checkin</button>
                    @else
                    <button type="button" onclick="updateStatus(this, {{$route->id}}, 2)" class="btn waves-effect waves-light btn-block btn-success" style="border-radius: 0;">Checkout</button>
                    @endif
                </div>
                <div class="col-6" style="padding: 0;padding-right: 10px;">
                    <a json-data="<?= htmlspecialchars(json_encode($route->routeHistory), ENT_QUOTES, 'UTF-8') ?>" onclick="detail(this)" href="javascript:void(0)" class="btn waves-effect waves-light btn-block btn-primary" style="border-radius:0px">Tarefas</a>
                </div>
                @endif
            </div>
        </div>
        <!-- Card -->
    </div>
    @endforeach
    <div class="col-12" style="display: flex; justify-content:center;">
        <?= $routes->render(); ?>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent" style="border: none;">
            <div class="modal-header" style="border-bottom: none;">
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 loadlist">
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none; display: flex;justify-content: center;">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Fechar tarefas</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="reportModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="reportModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
                <div class="modal-title">RELATÓRIO DE FINALIZAÇÃO</div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea id="reporttext" class="form-control" placeholder="Relate a tarefa" name="reporttext" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row loadimage m-0">
                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" onclick="ConfirmFinalizedTask()" class="btn btn-info waves-effect">Confirmar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@include('gree_commercial_promoter.layout.themeScripts')
<script src="/elite/assets/node_modules/sweetalert/sweetalert.min.js"></script>
<script src="/elite/assets/node_modules/resize/canvasResize.js"></script>
<script src="/elite/assets/node_modules/resize/jquery.canvasResize.js"></script>
<script src="/elite/assets/node_modules/resize/binaryajax.js"></script>
<script src="/elite/assets/node_modules/resize/exif.js"></script>
<script>
var _id;
var lthis;
var arryImg = new Array();
var $inputImage = $('#attach_trick');

function detail(elem) {
    lthis = elem;
    reloadtask(elem, true);
    $("#detailModal").modal();
}

function confirmEndTask(id) {
    _id = id;
    arryImg = new Array();
    $('#reporttext').val('');
    $inputImage.val('');
    $("#task_"+_id).find('#report').val('');
    $("#task_"+_id).find('#images').val('');
    reloadImg();
    $("#reportModal").modal({
        backdrop: 'static',
        keyboard: false
    });
}

function updateStatus($this, id, type) {
    swal({   
        title: "Tem certeza disso?",   
        text: type == 1 ? "Você confirma que acabou de chegar na rota?" : "Você confirma que está saindo da rota?",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: '#d33',
        confirmButtonText: "Confirmar",   
        cancelButtonText: "Cancelar",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm){   
        if (isConfirm) {    
            swal.close();
            block();
            ajaxSend('/promotor/route/update', {id:id, latitude:$('#latitude').val(), longitude:$('#longitude').val()}, 'POST', '10000').then(function(result){
                unblock();
                if (type == 1) {
                    $($this).html('Checkout');
                    $($this).removeAttr('onclick').attr('onclick', 'updateStatus(this, '+id+', 2)');
                    $($this).removeClass('btn-info').addClass('btn-success');
                    swal("Chegada confirmada", "Checkin realizado com sucesso!", "success"); 
                } else {
                    swal("Saída confirmada", "Checkout realizado com sucesso!", "success");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }).catch(function(err){
                unblock();
                $error(err.message)
            }) 
        } else { 
            swal.close();
        }
    });
}

function ConfirmFinalizedTask() {
    if ($("#reporttext").val() == '') {

        return $error('Relate sua tarefa para sabermos o que foi realizado.');
    } else if (arryImg.length == 0) {

        return $error('Você precisa enviar ao menos 1 imagem.');
    } else if (arryImg.length > 5) {

        return $error('Você pode enviar apenas 5 imagens.');
    }
    $("#task_"+_id).find('#report').val($("#reporttext").val());
    $("#task_"+_id).find('#images').val(JSON.stringify(arryImg));
    
    swal({   
        title: "Tem certeza disso?",   
        text: "Você irá finalizar sua tarefa.",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: '#d33',
        confirmButtonText: "Confirmar",   
        cancelButtonText: "Cancelar",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm){   
        if (isConfirm) {    
            swal.close();
            $("#imageModal").modal('toggle');
            block();
            ajaxSend('/promotor/task/completed', $("#task_"+_id).serialize(), 'POST', '30000', $("#task_"+_id)).then(function(result){
                unblock();
                location.reload();
            }).catch(function(err){
                unblock();
                $error(err.message)
            }) 
        } else { 
            swal.close();
        }
    });
}

function reloadtask(object, isjson = false) {
    var arr;

    if (isjson)
    arr = JSON.parse($(object).attr("json-data"));
    else
    arr = object;

    var list = '';

    for (let index = 0; index < arr.length; index++) {
        const row = arr[index];

        if (!row.attach) {
        list += '<div class="card text-white bg-info">';
        } else {
            list += '<div class="card text-white bg-success"> ';
        }

        list += '<div class="card-header">';
        list += '<h4 class="m-b-0 text-white">Tarefa #'+row.id+'</h4></div>';
        list += '<div class="card-body">';
        if (!row.attach) {
            list += '<form action="#" id="task_'+row.id+'">';
            list += '<input type="hidden" id="report" name="report" value="">';
            list += '<input type="hidden" id="images" name="images" value="">';
            list += '<input type="hidden" id="task_id" name="task_id" value="'+row.id+'">';
            list += '</form>';
            list += '<p class="card-text">'+row.description+'</p>';
            list += '<a onclick="confirmEndTask('+row.id+')" href="javascript:void(0)" class="btn btn-block btn-dark">Concluir</a>';
        } else {
            list += '<p class="card-text">'+row.description+'</p>';
            list += '<br><b>Terminou em:</b> '+row.job_done+'';
            list += '<br><b>Arquivo:</b> <a style="color: white;" target="_blank" href="'+row.attach+'">Clique aqui para ver</a>';
        }
                
        list += '</div>';
        list += '</div>';
        
    }

    $(".loadlist").html(list);

}

function arrdel(index) {
    swal({   
        title: "Tem certeza disso?",   
        text: "Você irá remover a imagem em anexo.",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: '#d33',
        confirmButtonText: "Confirmar",   
        cancelButtonText: "Cancelar",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm){   
        if (isConfirm) {    
            swal.close();
            arryImg.splice(index, 1);
            $success('Imagem excluída com sucesso!');
            reloadImg();
        } else { 
            swal.close();
        }
    });
    
}

function reloadImg() {

    var list = '';
    for (let index = 0; index < arryImg.length; index++) {
        const img = arryImg[index];

        list += '<div class="col-md-4 cardbox cursor-pointer" onclick="arrdel('+index+')">';
        list += '<img id="image" height="100" class="img-fluid" src="'+img+'" alt="">';
        list += '</div>';
        
    }

    list += '<div class="col-md-4 cardbox cursor-pointer" onclick="trickImage()">';
    list += 'Adicionar imagem';
    list += '</div>';

    $(".loadimage").html(list);

}

function trickImage() {
    $inputImage.trigger('click');
}

function readURL(input) {
    var files = input.files;
    var file;

    if (files && files.length) {
        file = files[0];

        canvasResize(file, {
            width: 0,
            height: 800,
            crop: false,
            quality: 100,
            //rotate: 90,
            callback: function(data, width, height) {
                arryImg.push(data);
                reloadImg();
            }
        });
    }

    $inputImage.val('');
    
}

function ConfirmImage() {

}

$(document).ready(function () {
    $inputImage.on('change', function () {
        readURL(this);
    });
});
</script>
@endsection