@extends('gree_i.layout')

@section('content')
<style>
    tr:hover {
        background-color: #F2F4F4;
    }

	#calendarEvents .picker {
        height: 420px;
        width: 100%;
        position: relative;
        z-index: 9;
    }

    .picker__holder {
        max-width: 380px;
        width: 100% !important;
    }
    #calendarEvents .picker.picker--opened:before {
        display: none;
    }

    #calendarEvents .picker__day {
        padding: 7px;
        height: 50px;
        width: 50px;
    }

    #calendarEvents .picker__footer {
        display: none;
    }

    #calendarEvents .picker__holder {
        max-height: 100%;
        max-width: 100%;
        border-top-width: 1px;
        border-bottom-width: 1px;
        display: block;
    }
    .wdgtCalendarEventsText {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* number of lines to show */
        -webkit-box-orient: vertical;
    }
	
    .carousel-thumb{
        list-style: none;
        margin-right: 0%;
        margin-left: 0%;
        margin-bottom: 0%;
        left: auto;
    }
    .carousel-thumb li, .carousel-thumb li.active{
        width: 40px;
        height: 40px;
        background-color: #fff;
        position: relative;
        margin: 0px;           
    }
    .carousel-thumb img{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;            
    }

    .div-sector {
        height: 54px;
        width:3px;
        float: left;
    }

    .span-sector {
        position: relative;
        top: 16px;
        left: 10px;
        font-weight: 600;
    }
    .dropdown-menu::before {
        display: none;
    }
    .remove-menu {
        margin: 0.1rem 0 0;
        width: 100%;
    }
    .nav-tabs .nav-link, .nav-pills .nav-link {
        background-color: #e6f1f1;
    }
    .card-postit:hover{
        cursor: pointer;
        box-shadow: 0px 6px 15px 4px rgb(25 42 70 / 48%);
    }
    .chevron-icon {
        position: absolute;
        z-index:90;
        margin-top:40px;
        font-size: 1.8rem;
        cursor:pointer;
    }
    .chevron-icon:hover {
        color: #4079ff;
    }
    .delete-postit:hover, .edit-postit:hover {
        color: #3a352e;
        cursor:pointer;
    }
    pre {
        background-color: #ffffff;
        font-size: 1em;
        color: #6b7279;
        font-family: "IBM Plex Sans", Helvetica, Arial, serif;
    }
</style>
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/shepherd-theme-default.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/tour/tour.min.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-body">
        <section>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="">Lembretes / Avisos</h5>
                </div>    
                <div class="col-md-6">    
                    @if($postits->count() > 0)
                    <button type="button" class="btn btn-primary btn-sm mb-1 float-right" id="new_postit" style="margin-bottom: 1px;">
                        <i class="bx bx-add-to-queue mr-50 font-size-small"></i> Novo lembrete
                    </button>
                    @endif
                </div>
            </div>

            <div class="row list-postit">
                <div>
                    <i class="bx bx-chevron-left chevron-icon" style="left: 5px;"></i>
                </div>    
                @if($postits->count() > 0)
                    @foreach ($postits as $postit)
                    <div class="col-md-3">
                        <div style="height: 106px;" class="card text-white card-postit 
                            @if($postit->priority == 1)
                                bg-primary
                            @elseif($postit->priority == 2)    
                                bg-success
                            @elseif($postit->priority == 3) 
                                bg-warning         
                            @else    
                                bg-danger
                            @endif">
                            <i class="bx bx-edit edit-postit" data-id="{{$postit->id}}" style="position: absolute;right: 22px;top: 2px; z-index:90;"></i>
                            <i class="bx bx-trash delete-postit" data-postit="{{$postit->id}}" style="position: absolute;right: 2px;top: 2px; z-index:90;"></i>
                            <div class="row no-gutters div-description" data-description='<?= htmlspecialchars($postit->description, ENT_QUOTES, "UTF-8") ?>'>
                                <div class="col-lg-12 col-12">
                                    <div class="card-body" style="padding: 1.2rem;">
                                        <p class="card-text text-ellipsis" style="margin-bottom: 5px;">
                                            <?= nl2br($postit->description); ?>
                                        </p>
                                        <small>
                                            <span>
                                                <i class="bx bx-time-five font-size-small"></i>
                                                <span class="font-size-small">
                                                    @php $date = new \Carbon\Carbon($postit->created_at); @endphp
                                                    {{date('d', strtotime($postit->created_at))}}
                                                    {{$date->locale('pt_BR')->isoFormat('MMMM')}}
                                                    {{$date->locale('pt_BR')->isoFormat('YYYY')}}
                                                </span>
                                            </span>    
                                            @if($postit->is_notify == 1)
                                            <span style="margin-left: 10px;">
                                                <i class="bx bxs-bell-ring font-size-small"></i>
                                                <span class="font-size-small">
                                                    @php $date_notify = new \Carbon\Carbon($postit->date_notify); @endphp
                                                    {{date('d', strtotime($postit->date_notify))}}
                                                    {{$date_notify->locale('pt_BR')->isoFormat('MMMM')}}
                                                    {{$date->locale('pt_BR')->isoFormat('YYYY')}}
                                                </span>
                                            </span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else 
                <div class="col-md-3">
                    <div style="height: 135px;background-color: #e7edf34a;display: flex;justify-content: center;" class="card card-postit text-center" data-toggle="modal" data-target="#modal_notification">
                        <div class="row no-gutters">
                            <div class="col-lg-12 col-12">
                                <div class="card-body">
                                    <i class="bx bx-duplicate font-medium-5"></i>
                                    <h5 style="font-size: 12px;margin-top: 13px;" class="mb-0">Adicionar lembrete</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div>
                    <i class="bx bx-chevron-right chevron-icon" style="right: 5px;"></i>
                </div>    
            </div>
        </section>
		<section>
            <div class="row">
                @foreach($wdget_approv as $wdget_approv_key)
                <div class="col-md-3">
                    <div style="height: 106px;cursor: pointer;" class="card text-white bg-primary" onclick="block(); window.open('{{$wdget_approv_key->url}}', '_self')">
                        <div class="row no-gutters">
                            <div class="col-lg-12 col-12">
                                <div class="card-body" style="padding: 1.2rem;">
                                    <p class="card-text text-ellipsis" style="margin-bottom: 5px;text-align: center;line-height: 1.2;"><b>{{$wdget_approv_key->name}}</b>
                                        <br><small>Para aprovar em aberto</small>
                                    </p>
                                    <div style="font-size: 28px;text-align: center;font-weight: bold;">{{$wdget_approv_key->qtd}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        <section>
            <div class="row match-height">
                <div class="col-md-4 col-12">
                    <div style="margin-top: -10px;">
                    <p id="calendarEvents" onclick="wdgtCalendarEventsShowEventsDay()"></p>
                    <input class="calendarEvents d-none" name="calendarEvents" type="hidden">
                    </div>
                    <div class="card widget-notification">
                        <div class="card-header border-bottom">
                            <h4 class="card-title d-flex align-items-center">
                                <i class="bx bx-bell font-medium-4 mr-1"></i>Lembretes: (<span id="wdgtCalendarEventsTitle">--/--/----</span>)</h4>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush" id="loadEventsDay">
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title float-left pr-1 mb-0">{{ __('layout_i.menu_news') }} |</h5>
                            <div class="breadcrumb-wrapper d-none d-md-block col-12">
                                {{ __('layout_i.menu_news_subtitle') }}
                            </div>
                            <div class="heading-elements">
                                <a class="btn btn-primary btn-sm shadow"href="/posts/segment">Todas notícias</a>
                              </div>
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                    @foreach ($posts as $post)    
                                        <tr class="cursor-pointer">
                                            @if($post->category_id == 7 || $post->category_id == 6)
                                                <td onclick="location.href = '/posts/segment/1'"  class="font-small-3 p-0"><div class="div-sector bg-<?php if ($post->caution == 1) { echo "primary"; } else if ($post->caution == 2) { echo "warning"; } else if ($post->caution == 3) { echo "danger"; } ?>"></div><span class="span-sector">{{ __('news_i.resources_human') }}</span></td>
                                            @elseif ($post->category_id == 100)    
                                                <td onclick="location.href = '/posts/segment/2'" class="font-small-3 p-0"><div class="div-sector bg-<?php if ($post->caution == 1) { echo "primary"; } else if ($post->caution == 2) { echo "warning"; } else if ($post->caution == 3) { echo "danger"; } ?>"></div><span class="span-sector">{{ __('news_i.marketing_internal') }}</span></td>
                                            @elseif ($post->category_id == 2)
                                                <td onclick="location.href = '/posts/segment/3'" class="font-small-3 p-0"><div class="div-sector bg-<?php if ($post->caution == 1) { echo "primary"; } else if ($post->caution == 2) { echo "warning"; } else if ($post->caution == 3) { echo "danger"; } ?>"></div><span class="span-sector">{{ __('news_i.enginer_iso') }}</span></td>
                                            @elseif ($post->category_id == 1)
                                                <td onclick="location.href = '/posts/segment/4'" class="font-small-3 p-0"><div class="div-sector bg-<?php if ($post->caution == 1) { echo "primary"; } else if ($post->caution == 2) { echo "warning"; } else if ($post->caution == 3) { echo "danger"; } ?>"></div><span class="span-sector">{{ __('news_i.pv_pd') }}</span></td>
                                            @elseif ($post->category_id == 3)
                                                <td onclick="location.href = '/posts/segment/5'" class="font-small-3 p-0"><div class="div-sector bg-<?php if ($post->caution == 1) { echo "primary"; } else if ($post->caution == 2) { echo "warning"; } else if ($post->caution == 3) { echo "danger"; } ?>"></div><span class="span-sector">{{ __('news_i.financy') }}</span></td>
                                            @else    
                                                <td onclick="location.href = '/posts/segment/6'" class="font-small-3 p-0"><div class="div-sector bg-<?php if ($post->caution == 1) { echo "primary"; } else if ($post->caution == 2) { echo "warning"; } else if ($post->caution == 3) { echo "danger"; } ?>"></div><span class="span-sector">{{ __('news_i.other_sectors') }}</span></td>
                                            @endif

                                            <td onclick="location.href = '/post/single/<?= $post->id ?>';" class="font-small-3"><?php if (Session::get('lang') == 'en') { echo $post->title_en; } else { echo $post->title_pt; } ?></td>
                                            <td onclick="location.href = '/post/single/<?= $post->id ?>';" class="font-small-3"><?= date('Y-m-d', strtotime($post->created_at)) ?></td>
                                        </tr>
                                    @endforeach    
                                  </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="card bg-transparent" style="margin-bottom:240px;">
                <div class="card-content">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-justified" role="tablist">
                            <li class="nav-item current">
                                <a class="nav-link active btn-block" id="geral-tab" data-toggle="tab" href="#geral" aria-controls="geral" role="tab" aria-selected="true">
                                    <span class="align-middle">Geral</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-block" id="noticias-tab" data-toggle="tab" href="#noticias" aria-controls="noticias" role="tab" aria-selected="false">
                                    <span class="align-middle">Notícias & Avisos</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-block" id="adm-tab" data-toggle="tab" href="#adm" aria-controls="adm" role="tab" aria-selected="false">
                                    <span class="align-middle">Administração</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-block" id="pos-venda-tab" data-toggle="tab" href="#pos-venda" aria-controls="pos-venda" role="tab" aria-selected="false">
                                    <span class="align-middle">Pós-venda</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-block" id="industrial-tab" data-toggle="tab" href="#industrial" aria-controls="industrial" role="tab" aria-selected="false">
                                    <span class="align-middle">Industrial</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-block" id="ti-tab" data-toggle="tab" href="#ti" aria-controls="ti" role="tab" aria-selected="false">
                                    <span class="align-middle">TI</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="geral" aria-labelledby="geral-tab" role="tabpanel">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" href="/trip/new">Solicitar viagem</a>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" href="/financy/lending/new">Solicitar empréstimo</a>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" href="/financy/refund/edit/0">Solicitar reembolso</a>
                                        </div>
                                    </div><br>    
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" target="_blank" href="http://172.61.0.6/comp/index.asp">Sistema Cadastra compressor</a>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" target="_blank" href="http://172.61.0.6/novafabrica2/">Sistema Nova Fabrica</a>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" target="_blank" href="http://172.61.0.6/zktm/">Sistema SQ SCANNER</a>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" target="_blank" href="http://172.61.0.6/novoarmazem/">Sistema Novo armazém</a>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" target="_blank" href="http://172.61.0.6/suzuki/">Sistema SUZUKI</a>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" target="_blank" href="http://172.61.0.6/vpc/">Sistema VPC</a>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" target="_blank" href="https://gree-app.com.br/ti/maintenance/edit/0">Suporte de TI</a>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" target="_blank" href="https://backup.gree.com.br/sistema">Sistema antigo SAC</a>
                                        </div>
                                    </div><br>
                                </div>
                            </div>
                            <div class="tab-pane" id="noticias" aria-labelledby="noticias-tab" role="tabpanel">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Publicações</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/blog/edit/0">Nova publicação</a>
                                                    <a class="dropdown-item" href="/blog/view/all">Todas publicações</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" href="/blog/edit/0">Autores</a>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" href="/blog/author/all">Lista de transmissão</a>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="tab-pane" id="adm" aria-labelledby="adm-tab" role="tabpanel">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Usuários</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/user/edit/0">Novo usuário</a>
                                                    <a class="dropdown-item" href="/user/list">Listar usuários</a>
                                                    <a class="dropdown-item" href="/user/log">Log de usuários</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Viagem</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/trip/dashboard">Relatório</a>
                                                    <a class="dropdown-item" href="/trip/new">Nova programação</a>
                                                    <a class="dropdown-item" href="/trip/my">Meus planejamentos</a>
                                                    <a class="dropdown-item" href="/trip/all">Todos planejamentos</a>
                                                    <a class="dropdown-item" href="/trip/view">Aprovar solicitações</a>
                                                    <a class="dropdown-item" href="/trip/view/all">Todas solicitações</a>
                                                    <a class="dropdown-item" href="/trip/agency">Ver agências</a>
                                                    <a class="dropdown-item" href="/trip/credits">Viagens creditadas</a>
                                                    <a class="dropdown-item" href="/trip/export/view">Exportar dados</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Empréstimo</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/financy/lending/dashboard">Relatório</a>
                                                    <a class="dropdown-item" href="/financy/lending/new">Novo empréstimo</a>
                                                    <a class="dropdown-item" href="/financy/lending/my">Meus empréstimos</a>
                                                    <a class="dropdown-item" href="/financy/lending/approv">Aprovar empréstimo</a>
                                                    <a class="dropdown-item" href="/financy/lending/all">Todos empréstimos</a>
                                                    <a class="dropdown-item" href="/financy/permission/module?mdl=1">Permitir usuários</a>
                                                    <a class="dropdown-item" href="/financy/lending/export">Exportar dados</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reembolso</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/financy/refund/edit/0">Novo reembolso</a>
                                                    <a class="dropdown-item" href="/financy/refund/my">Meus reembolsos</a>
                                                    <a class="dropdown-item" href="/financy/refund/approv">Aprovar reembolso</a>
                                                    <a class="dropdown-item" href="/financy/refund/all">Todos reembolsos</a>
                                                    <a class="dropdown-item" href="/financy/permission/module?mdl=2">Permitir usuários</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pagamento</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/financy/payment/edit/0">Nova solicitação</a>
                                                    <a class="dropdown-item" href="/financy/payment/my">Minhas solicitações</a>
                                                    <a class="dropdown-item" href="/financy/payment/approv">Aprovar pagamentos</a>
                                                    <a class="dropdown-item" href="/financy/payment/supervisor/approv">Antecipar Análise Fiscal</a>
                                                    <a class="dropdown-item" href="/financy/payment/transfer">Transferir pagamento</a>
                                                    <a class="dropdown-item" href="/financy/payment/all">Todos pagamentos</a>
                                                    <a class="dropdown-item" href="/financy/permission/module?mdl=3">Permitir usuários</a>
                                                    <a class="dropdown-item" href="/financy/payment/export">Exportar pagamentos</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tarefas</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/task/0">Nova tarefa</a>
                                                    <a class="dropdown-item" href="/task/view/my">Minhas tarefas</a>
                                                    <a class="dropdown-item" href="/task/view/export">Exportar tarefas</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Home office</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/home-office" data-toggle="dropdown">Cronômetro de trabalho</a>
                                                    <a class="dropdown-item" href="/home-office/my" data-toggle="dropdown">Meus relatórios</a>
                                                    <a class="dropdown-item" href="/home-office/data" data-toggle="dropdown">Banco de horas</a>
                                                    <a class="dropdown-item" href="/home-office/online" data-toggle="dropdown">Colaboradores ativos</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pesquisas (Survey)</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/survey/edit/0" data-toggle="dropdown">Nova pesquisa</a>
                                                    <a class="dropdown-item" href="/survey/all" data-toggle="dropdown">Todas pesquisas</a>
                                                    <a class="dropdown-item" href="/survey/answers" data-toggle="dropdown">Respostas</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Responder Pesquisas</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item align-items-center nav-link-button" id="2"
                                                        href="javascript:void(0);"
                                                        onclick="showModalSurvey(2); return false;"
                                                        data-toggle="dropdown"><i class="bx bxs-edit"></i>FICHA DE SUGESTÃO E RECLAMAÇÕES - REFEIÇÕES
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="tab-pane" id="pos-venda" aria-labelledby="pos-venda-tab" role="tabpanel">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">SAC</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/sac/monitor">Monitoramento</a>
                                                    <a class="dropdown-item" href="/sac/warranty/edit/0">Novo protocolo</a>
                                                    <a class="dropdown-item" href="/sac/warranty/all">Lista de protocolos</a>
                                                    <a class="dropdown-item" href="/sac/client/edit/0">Novo cliente</a>
                                                    <a class="dropdown-item" href="/sac/client/all">Todos clientes</a>
                                                    <a class="dropdown-item" href="/sac/authorized/edit/0">Nova autorizada</a>
                                                    <a class="dropdown-item" href="/sac/authorized/all">Todas autorizadas</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Assist. Técnica</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/sac/warranty/approv">Aprovar envio de peças</a>
                                                    <a class="dropdown-item" href="/sac/warranty/os/all">Todas O.S</a>
                                                    <a class="dropdown-item" href="/sac/warranty/os/paid">Pagar O.S</a>
                                                    <a class="dropdown-item" href="/sac/warranty/ob">Ordem de compra</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Expedição</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/sac/expedition/pending">Peças para envio</a>
                                                    <a class="dropdown-item" href="/sac/expedition/track">Peças que chegaram ou a caminho </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" href="/sac/map/global">Mapa global</a>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Comunicação</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/sac/comunication/authorized/all">Notificar autorizadas</a>
                                                    <a class="dropdown-item" href="/sac/faq/all">Perguntas frequentes</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Site cadastros</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/sac/register/shop/all">Lojas</a>
                                                    <a class="dropdown-item" href="/sac/register/shop-parts/all">Lojas de peças</a>
                                                    <a class="dropdown-item" href="/sac/register/salesman/all">Representante</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <a class="btn shadow btn-block rounded-0 text-left bg-white" href="/sac/config">Configurações</a>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="tab-pane" id="industrial" aria-labelledby="industrial-tab" role="tabpanel">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Engenharia</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/engineering/product/edit/0">Novo produto</a>
                                                    <a class="dropdown-item" href="/engineering/product/all">Todos produtos</a>
                                                    <a class="dropdown-item" href="/engineering/part/edit/0">Nova peça</a>
                                                    <a class="dropdown-item" href="/engineering/part/all">Todos peças</a>
                                                    <a class="dropdown-item" href="/engineering/type">Tipos de linhas</a>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="tab-pane" id="ti" aria-labelledby="ti-tab" role="tabpanel">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="dropdown">
                                                <button class="btn shadow btn-block dropdown-toggle mr-1 rounded-0 text-left bg-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Criação de software</button>
                                                <div class="dropdown-menu remove-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/ti/developer/monitor">Monitoramento</a>
                                                    <a class="dropdown-item" href="/ti/developer/edit/0">Nova tarefa</a>
                                                    <a class="dropdown-item" href="/ti/developer/approv">Aprovar tarefa</a>
                                                    <a class="dropdown-item" href="/ti/developer/all">Todas tarefas</a>
                                                </div>
                                            </div>
                                        </div>
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


<div class="modal fade" id="modal_notification" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/news/postit/edit_do" id="form_postit">
                <input type="hidden" name="postit_id" id="postit_id" value="0">
                <input type="hidden" name="job_id" id="job_id">

                <div class="modal-header bg-primary">
                    <h5 class="modal-title white title-edit-postit">Novo lembrete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="serie">Descrição</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Informe a descrição deste lembrete!"></textarea>
                            </fieldset>
                        </div>

                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="serie">Prioridade</label>
                                <select class="form-control" name="priority" id="priority">
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                    <option value="4">Crítica</option>
                                </select>    
                            </fieldset>    
                        </div>

                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="serie">Deseja ser lembrado sobre este aviso?</label>
                                <select class="form-control" name="is_notify" id="is_notify">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>    
                            </fieldset>    
                        </div>

                        <div class="col-md-12 div-date-notify" style="display: none;">
                            <fieldset class="form-group">
                                <label for="price">data do lembrete</label>
                                <input type="text"class="form-control date-mask" id="date_notify" name="date_notify" placeholder="__/__/____">
                            </fieldset>
                        </div>
						
						<div class="col-md-12 div-url-notify" style="display: none;">
                            <fieldset class="form-group">
                                <label for="price">URL para visualizar</label>
                                <input type="text"class="form-control" id="notify_url" name="notify_url" placeholder="https:://gree-app.com.br/">
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                        <span class="d-none d-sm-block">Fechar</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" id="btn_send_postit" >
                        <span class="d-none d-sm-block actiontxt">Salvar</span>
                    </button>
                </div>
            </form>    
        </div>
    </div>
</div>

<div class="modal text-left modal-borderless" id="modal_description" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" >
                <pre style="white-space: pre-wrap;" id="modal_postit_desc"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="/admin/app-assets/vendors/js/extensions/shepherd.min.js"></script>
<script src="/js/StepsTour.js"></script>
<script src="/admin/app-assets/js/moment.min.js"></script>

<script>
    var page = 1; 
	var wdgtCalendarEventsFirstload = false;
    var wdgtCalendarEventsJson = null;

    // tour initialize
    var tour = new Shepherd.Tour({
        classes: 'shadow-md bg-purple-dark',
        scrollTo: true
    });

    AddSteps(1, '<?= __('training_i.page_1') ?>', '#Userinfo left');
    AddSteps(2, '<?= __('training_i.page_2') ?>', '#Usernotify left');
    AddSteps(3, '<?= __('training_i.page_3') ?>', '#UserFull bottom');
    AddSteps(4, '<?= __('training_i.page_4') ?>', '#Userlang bottom');
    AddSteps(5, '<?= __('training_i.page_5') ?>', '.main-menu-content bottom');
    AddSteps(0, '<?= __('training_i.page_6') ?>', '.kb-categories top');

	function wdgtCalendarEvents($this) {
        var content = $($this).find('.wdgtCalendarEventsText').html();
        $("#modal_postit_desc").html(content);
        $("#modal_description").modal('show');
    }


    function wdgtCalendarEventsLoad(data) {

        $('#loadEventsDay').html('');
        var load = '';
        for (i = 0; i < data.length; i++) {

            var $val = data[i];
            var arr = [{
                    'name': 'Baixo',
                    'color': 'primary'
                },
                {
                    'name': 'Médio',
                    'color': 'success'
                },
                {
                    'name': 'Alto',
                    'color': 'Warning'
                },
                {
                    'name': 'Critico',
                    'color': 'danger'
                },
            ];
            load += '<li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between" style="cursor: pointer;" onclick="wdgtCalendarEvents(this)">';
            load += '<div class="list-left d-flex">';
            load += '<div class="list-content" style="display: flex;flex-direction: row;flex-wrap: nowrap;align-items: center;">';
            load += '<div style="float: left;height: 40px;">';
            load += '<span style="font-weight: bold;position: relative;top: 9px; margin-right: 12px;">'+arr[$val.priority-1].name+'</span>';
            load += '</div>';
            load += '<div style="float: left;height: 40px;">';
            load += '<i class="bullet bullet-xs bullet-'+arr[$val.priority-1].color+'" style="height: 12px;width: 12px;position: relative;top: 10px;margin-right: 15px;"></i>';
            load += '</div>';
            load += '<span class="list-title wdgtCalendarEventsText">'+$val.description+'</span>';
            load += '</div>';
            load += '</div>';
            load += '</li>';
        }

        $('#loadEventsDay').html(load);
    }

    async function wdgtCalendarEventsRender() {
        $('.calendarEvents').pickadate({
            container : '#calendarEvents',
            formatSubmit: 'yyyy-mm-dd',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            format: 'yyyy-mm-dd',
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            onRender: function() {
                wdgtCalendarEventsShowEventsDay();
            },
            onSet: function() {
                if (typeof wdgtCalendarEventsJson[$('input[name="calendarEvents"]').val()] != 'undefined') {
                    wdgtCalendarEventsLoad(wdgtCalendarEventsJson[$('input[name="calendarEvents"]').val()]);
                    $('#wdgtCalendarEventsTitle').html(moment($('input[name="calendarEvents"]').val()).format('DD/MM/YYYY'));
                } else {
                    $('#loadEventsDay').html('');
                    $('#wdgtCalendarEventsTitle').html('--/--/----');
                }
            }
        });
    }

    function wdgtCalendarEventsShowEventsDay() {

        var id = $('.calendarEvents').attr('id');
        var first_date = $('#'+id+'_table > tbody > tr:nth-child(1) > td:nth-child(1)').find('.picker__day').attr('aria-label');
        var last_date = $('#'+id+'_table > tbody > tr:nth-child(6) > td:nth-child(7)').find('.picker__day').attr('aria-label');

        ajaxSend(
            '/widgets/calendar-inline/json/events',
            {
                start_date: first_date,
                end_date: last_date
            },
            'GET', 3000
        ).then(function(result) {
            if(result) {
                wdgtCalendarEventsJson = result;
                var now = new Date(last_date);
                for (var d = new Date(first_date); d <= now; d.setDate(d.getDate() + 1)) {
                    if (typeof wdgtCalendarEventsJson[moment(d).format("YYYY-MM-DD")] != 'undefined') {
                        $('div[aria-label="'+moment(d).format("YYYY-MM-DD")+'"]')
                            .html(moment(d).format("D"))
                            .append('<div style="position:relative; top: -4px"><i style="font-size: 13px;" class="bx bxs-bell-ring"></i></div>');
                    }
                }

                if (typeof wdgtCalendarEventsJson[moment(new Date()).format('YYYY-MM-DD')] != 'undefined' && !wdgtCalendarEventsFirstload) {
                    wdgtCalendarEventsLoad(wdgtCalendarEventsJson[moment(new Date()).format('YYYY-MM-DD')]);
                    $('#wdgtCalendarEventsTitle').html(moment(new Date()).format('DD/MM/YYYY'));
                    wdgtCalendarEventsFirstload = true;
                }
            }
        }).catch((error) => {
            $error(error.message);
        });

    }
	
    $(document).ready(function () { 
		
		// montar widgets
        setTimeout(() => {
            wdgtCalendarEventsRender();
        }, 300);

        $("#new_postit").click(function() {
            $("#postit_id").val('');
            $("#description").val('');
            $("#priority").val(1);
            $("#is_notify").val(0);
            $("#job_id").val('');
            $("#date_notify").val('');
            $(".title-edit-postit").text('Novo lembrete');
            $("#modal_notification").modal('show');
        });

        $("#btn_send_postit").click(function() {

            if($("#description").val() == '') {
                $error('Informe a descrição!');
            }
            else if($("#is_notify").val() == 1 && $("#date_notify").val() == '') {
                $error('Informe a data do lembrete!');
            } 
            else {
                $("#form_postit").unbind().submit();
            }
        })

        $("#is_notify").change(function() {

            if($(this).val() == 1) {
                $('.div-date-notify, .div-url-notify').show();
            } else {
                $('.div-date-notify, .div-url-notify').hide();
                $("#date_notify").val('');
            }
        });

        $(document).on("click",".bx-chevron-right",function() {
            page++;
            ajaxSend('/news/postit/next?page='+page+'', {page: page}, 'GET', 3000).then(function(result) {
                if(result.postit.data.length > 0) {
                    //$(".list-postit").animate({left: '+=100%',opacity: '1'}, 1000);
                    $(".list-postit").html(loadPostit(result.postit.data));
                }
            })
            .catch((error) => {
                $error(error.message);
                unblock();
            });    
        });

        $(document).on("click",".bx-chevron-left",function() {
            page--;
            if(page < 0) {
                page = 1;
            }
            ajaxSend('/news/postit/next?page='+page+'', {page: page}, 'GET', 3000).then(function(result) {
                if(result.postit.data.length > 0) {
                    $(".list-postit").html(loadPostit(result.postit.data));
                }
            })
            .catch((error) => {
                $error(error.message);
                unblock();
            });  
        });

        $(document).on("click",".delete-postit",function() {
        
            var id_postit = $(this).attr("data-postit");
            
            Swal.fire({
                title: 'Excluir Lembrete?',
                text: "Você deseja excluir este lembrete",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    block();
                    window.location.href = "/news/postit/delete/"+id_postit;
                }
            });
        });

        $(document).on("click",".edit-postit",function() {

            var id = $(this).attr("data-id");

            block();
            ajaxSend('/news/postit/edit', {id: id}, 'GET', 3000).then(function(result) {

                if(result.success) {
                    unblock();

                    $("#postit_id").val(result.postit.id);
                    $("#description").val(result.postit.description);
                    $("#priority").val(result.postit.priority);
                    $("#is_notify").val(result.postit.is_notify);
                    $("#job_id").val(result.postit.job_id);

                    if(result.postit.is_notify == 1) {
                        $("#date_notify").val(moment(result.postit.date_notify).format("DD/MM/YYYY"));
						$("#notify_url").val(result.postit.url_notify);
                        $('.div-date-notify, .div-url-notify').show();
                    } else {
                        $('.div-date-notify, .div-url-notify').hide();
                        $("#date_notify").val('');
						$("#notify_url").val('');
                    }

                    $(".title-edit-postit").text('Editar lembrete');
                    $("#modal_notification").modal('show');
                }
            })
            .catch((error) => {
                $error(error.message);
                unblock();
            });
        });  
        

        $(document).on("click",".div-description",function() {

            $("#modal_postit_desc").html($(this).attr("data-description"));
            $("#modal_description").modal('show');
        });    
        

        $('.date-mask').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'dd/mm/yyyy',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        });

        // function to remove tour on small screen
        window.resizeEvt;
        if ($(window).width() > 576) {
            $('#ActiveTraine').on("click", function () {
                clearTimeout(window.resizeEvt);
                tour.start();
            })
        }
        else {
        $('#ActiveTraine').on("click", function () {
            clearTimeout(window.resizeEvt);
            tour.cancel()
            window.resizeEvt = setTimeout(function () {
            alert("Tour only works for large screens!");
            }, 250);
        })
        }       
        localStorage.rcode = '<?= Session::get('r_code') ?>';
        <?php if (!empty(Session::get('picture'))) { ?>
        localStorage.picture = '<?= Session::get('picture') ?>';
        <?php } else { ?>
        localStorage.picture = '/media/avatars/avatar10.jpg';
        <?php } ?>

        $('table').DataTable( {
            searching: false,
            ordering:false,
            lengthChange: false,
            pagingType: "numbers",
            pageLength: 3,
            fnInitComplete: function() { 
                $(".pagination").addClass('pagination-sm');
                },
            drawCallback: function () {
                $('.dataTables_paginate > .pagination').addClass('pagination-sm');
            },
            language: {
                search: "",
                zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                info: "",
                infoEmpty: "",
                infoFiltered: "",
            }
        });

        $.fn.dataTable.ext.classes.sPageButton = 'pagination pagination-sm';

        setInterval(() => {
            $("#mNews").addClass('active');
        }, 100);

    });

    function loadPostit(postits) {

        var html = '';
        html += '<div>';
        html += '    <i class="bx bx-chevron-left chevron-icon" style="left: 5px;"></i>';
        html += '</div>';
        
        for (var i = 0; i < postits.length; i++) {
            var column = postits[i];

            html += '<div class="col-md-3">';
            html += '   <div style="height: 106px;" class="card text-white card-postit ';
                            if(column.priority == 1) {
            html += '            bg-primary';
                            } else if(column.priority == 2) {
            html += '            bg-success';
                            } else if(column.priority == 3) {
            html += '            bg-warning';
                            } else {
            html += '            bg-danger';
                            }
            html += '   " div>';                
            html += '        <i class="bx bx-edit edit-postit" data-id="'+column.id+'" style="position: absolute;right: 22px;top: 2px; z-index:90;"></i>';
            html += '        <i class="bx bx-trash delete-postit" data-postit="'+column.id+'" style="position: absolute;right: 2px;top: 2px; z-index:90;"></i>';
            html += '        <div class="row no-gutters div-description" data-description="'+ column.description +'">';
            html += '            <div class="col-lg-12 col-12">';
            html += '                <div class="card-body" style="padding: 1.2rem;">';
            html += '                    <p class="card-text text-ellipsis" style="margin-bottom: 5px;">';
            html += '                        '+ nl2br(column.description) +'';
            html += '                    </p>';
            html += '                    <small>';
            html += '                        <span>';
            html += '                            <i class="bx bx-time-five font-size-small"></i>';
            html += '                            <span class="font-size-small">';
            html +=                                 moment(column.created_at).format("D MMMM YYYY");
            html += '                            </span>';
            html += '                        </span>    ';
                                            if(column.is_notify == 1) {
            html += '                        <span style="margin-left: 10px;">';
            html += '                            <i class="bx bxs-bell-ring font-size-small"></i>';
            html += '                            <span class="font-size-small">';
            html +=                                 moment(column.date_notify).format("D MMMM YYYY");
            html += '                            </span>';
            html += '                        </span>';
                                             }
            html += '                    </small>';
            html += '                </div>';
            html += '            </div>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';
        }
        html += '<div>';
        html += '    <i class="bx bx-chevron-right chevron-icon" style="right: 5px;"></i>';
        html += '</div>';

        return html;
    }

    function nl2br (str, replaceMode, isXhtml) {

        var breakTag = (isXhtml) ? '<br />' : '<br>';
        var replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
    }

</script>
@endsection