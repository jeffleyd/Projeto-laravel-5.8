@extends('gree_i.layout')

@section('content')
    <!-- END: Page CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/pages/app-chat.css">
    <div class="newVersion" style="display:none; margin-top: 110px; margin-bottom: -173px; padding: 35px;">
        <a href="/chat/main">
            <div class="alert alert-primary mb-1" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle"></i>
                    <span>
          Nova versão disponível, atualize a página ou clique aqui, por favor.
        </span>
                </div>
            </div>
        </a>
    </div>
    <div class="content-area-wrapper">

        <div class="sidebar-left">
            <div class="sidebar">
                <!-- app chat user profile left sidebar start -->
                <div class="chat-user-profile">
                    <header class="chat-user-profile-header text-center border-bottom">
                    <span class="chat-profile-close">
                        <i class="bx bx-x"></i>
                    </span>
                        <div class="my-2">
                            <div class="avatar">
                                <img src="@if (empty(Session::get('picture'))) /media/avatars/avatar10.jpg @else {{ Session::get('picture') }} @endif" alt="user_avatar" height="100" width="100">
                            </div>
                            <h5 class="mb-0"><?= Session::get('first_name') ?></h5>
                            <span><?= sectorName(Session::get('sector')) ?></span>
                        </div>
                    </header>
                </div>
                <!-- app chat user profile left sidebar ends -->
                <!-- app chat sidebar start -->
                <div class="chat-sidebar card">
                <span class="chat-sidebar-close">
                    <i class="bx bx-x"></i>
                </span>
                    <div class="chat-sidebar-search">
                        <div class="d-flex align-items-center">
                            <div class="chat-sidebar-profile-toggle">
                                <div class="avatar">
                                    <img src="@if (empty(Session::get('picture'))) /media/avatars/avatar10.jpg @else {{ Session::get('picture') }} @endif" alt="user_avatar" height="36" width="36">
                                </div>
                            </div>
                            <fieldset class="form-group position-relative has-icon-left mx-75 mb-0">
                                <input type="text" class="form-control round" id="chat-search" placeholder="Pesquisar">
                                <div class="form-control-position">
                                    <i class="bx bx-search-alt text-dark"></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="chat-sidebar-list-wrapper pt-2">
                        <h6 class="px-2 pb-25 mb-0">GRUPOS</h6>
                        <ul class="chat-sidebar-list" id="groups">
                            @foreach ($group as $key)
                                <li>
                                    <input type="hidden" id="type" value="group">
                                    <input type="hidden" id="g_id" value="<?= $key->id ?>">
                                    <input type="hidden" id="name" value="<?= $key->name ?>">
                                    <h6 class="mb-0"># <?= $key->name ?></h6>
                                </li>
                            @endforeach
                        </ul>
                        <h6 class="px-2 pt-2 pb-25 mb-0">CHATS</h6>
                        <ul class="chat-sidebar-list" id="contacts">
                            @foreach ($chats as $key)
                                <li class="id-<?= $key->r_code ?>">
                                    <input type="hidden" id="type" value="single">
                                    <input type="hidden" id="r_code" value="<?= $key->r_code ?>">
                                    <input type="hidden" id="name" value="<?= getENameF($key->r_code) ?>">
                                    <input type="hidden" id="sector" value="<?= sectorName($key->sector_id) ?>">
                                    <input type="hidden" id="picture" value="<?php if (empty($key->picture)) { ?>/media/avatars/avatar10.jpg<?php } else { ?>{{ $key->picture }}<?php } ?>">
                                    <input type="hidden" id="status" value="<?= $key->status ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar m-0 mr-50"><img src="<?php if (empty($key->picture)) { ?>/media/avatars/avatar10.jpg<?php } else { ?>{{ $key->picture }}<?php } ?>" height="36" width="36" alt="sidebar user image">
                                            @if ($key->status == 0)
                                                <span class="avatar-status-busy user-list-status"></span>
                                            @else
                                                <span class="avatar-status-online user-list-status"></span>
                                            @endif
                                        </div>
                                        <div class="chat-sidebar-name">
                                            <h6 class="mb-0"><?= getENameF($key->r_code) ?></h6><span class="text-muted"> <?= sectorName($key->sector_id) ?></span>
                                        </div>
                                        <?php $total = $key->total ?>

                                        <span class="badge badge-warning user-list-notify" style="margin-left: auto;padding: 6px 9px;border-radius: 33px; @if ($total == 0) display:none;@endif">{{ $total }}</span>

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <h6 class="px-2 pt-2 pb-25 mb-0">CONTATOS</h6>
                        <ul class="chat-sidebar-list">
                            @foreach ($users as $key)
                                <li class="id-<?= $key->r_code ?>">
                                    <input type="hidden" id="type" value="single">
                                    <input type="hidden" id="r_code" value="<?= $key->r_code ?>">
                                    <input type="hidden" id="name" value="<?= getENameF($key->r_code) ?>">
                                    <input type="hidden" id="sector" value="<?= sectorName($key->sector_id) ?>">
                                    <input type="hidden" id="picture" value="<?php if (empty($key->picture)) { ?>/media/avatars/avatar10.jpg<?php } else { ?>{{ $key->picture }}<?php } ?>">
                                    <input type="hidden" id="status" value="<?= $key->status ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar m-0 mr-50"><img src="<?php if (empty($key->picture)) { ?>/media/avatars/avatar10.jpg<?php } else { ?>{{ $key->picture }}<?php } ?>" height="36" width="36" alt="sidebar user image">
                                            @if ($key->status == 0)
                                                <span class="avatar-status-busy user-list-status"></span>
                                            @else
                                                <span class="avatar-status-online user-list-status"></span>
                                            @endif
                                        </div>
                                        <div class="chat-sidebar-name">
                                            <h6 class="mb-0"><?= getENameF($key->r_code) ?></h6><span class="text-muted"> <?= sectorName($key->sector_id) ?></span>
                                        </div>

                                        <span class="badge badge-warning user-list-notify" style="margin-left: auto;padding: 6px 9px;border-radius: 33px; display:none;"></span>

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- app chat sidebar ends -->
            </div>
        </div>
        <div class="content-right">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <!-- app chat overlay -->
                    <div class="chat-overlay"></div>
                    <!-- app chat window start -->
                    <section class="chat-window-wrapper">
                        <div class="chat-start">
                            <span class="bx bx-message chat-sidebar-toggle chat-start-icon font-large-3 p-3 mb-1"></span>
                            <h4 class="d-none d-lg-block py-50 text-bold-500">Escolha o grupo ao lado.</h4>
                            <button class="btn btn-light-primary chat-start-text chat-sidebar-toggle d-block d-lg-none py-50 px-1">Começar a conversar</button>
                        </div>
                        <div class="chat-area d-none">
                            <div class="chat-header">
                                <header class="d-flex justify-content-between align-items-center border-bottom px-1 py-75">
                                    <div class="d-flex align-items-center">
                                        <div class="chat-sidebar-toggle d-block d-lg-none mr-1"><i class="bx bx-menu font-large-1 cursor-pointer"></i>
                                        </div>
                                        <div class="avatar chat-profile-toggle m-0 mr-1 user-select" style="display:none">
                                            <img class="user-select-pic" src="" alt="avatar" height="36" width="36">
                                        </div>
                                        <h6 class="mb-0 user-select-name" style="display:none">Jefferson</h6>
                                        <h6 class="mb-0 group-select" style="text-transform: uppercase; height:36px;position: relative;top: 9px;">HOME OFFICE</h6>
                                    </div>
                                </header>
                                <div id="loadingChat" style="position: absolute;left: 0;right: 0;top: 0;bottom: 0;margin: 12px;">
                                    <div class="spinner-border float-right" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <!-- chat card start -->
                            <div class="card chat-wrapper shadow-none">
                                <div class="card-content">
                                    <div class="card-body chat-container">
                                        <div class="chat-content">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer chat-footer border-top px-2 pt-1 pb-0 mb-1">
                                    <form class="d-flex align-items-center" onsubmit="chatMessagesSend();" id="chatForm" action="javascript:void(0);">


                                        <div class="btn-group dropup">
                                            <i class="bx bx-face cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" onclick="emojiChat('👍');" href="#">👍 legal</a>
                                                <a class="dropdown-item" onclick="emojiChat('🤩');" href="#">🤩 Incrível</a>
                                                <a class="dropdown-item" onclick="emojiChat('😎');" href="#">😎 8)</a>
                                                <a class="dropdown-item" onclick="emojiChat('😆');" href="#">😆 XD</a>
                                                <a class="dropdown-item" onclick="emojiChat('😄');" href="#">😄 Sorrindo</a>
                                                <a class="dropdown-item" onclick="emojiChat('🤝');" href="#">🤝 Fechado</a>
                                            </div>
                                        </div>
                                        <i class="bx bx-paperclip ml-1 cursor-pointer" id="clickAttach"></i>
                                        <input type="text" name="msg" id="msg_send" class="form-control chat-message-send mx-1" placeholder="Digite sua mensagem...">
                                        <input type="hidden" name="receiver">
                                        <input type="hidden" name="group">
                                        <input type="hidden" name="pm_id">
                                        <input type="file" name="attach" id="attach" style="display:none">
                                        <button type="submit" class="btn btn-primary glow send d-lg-flex"><i class="bx bx-paper-plane"></i>
                                            <span class="d-none d-lg-block ml-1">Enviar</span></button>
                                    </form>
                                </div>
                            </div>
                            <!-- chat card ends -->
                        </div>
                    </section>
                    <!-- app chat window ends -->
                    <!-- app chat profile right sidebar ends -->
                    <section class="chat-profile">
                        <header class="chat-profile-header text-center border-bottom">
                      <span class="chat-profile-close">
                        <i class="bx bx-x"></i>
                      </span>
                            <div class="my-2">
                                <div class="avatar">
                                    <img src="/media/avatars/avatar10.jpg" class="user-click-pic" alt="chat avatar" height="100" width="100">
                                </div>
                                <h5 class="app-chat-user-name mb-0 user-click-name">Jhon Doe</h5>
                                <span class="user-click-sector">Comercial</span>
                            </div>
                        </header>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="attachUpload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title attach-title" id="myModalLabel1">ARQUIVO</h3>
                </div>
                <div class="modal-body text-center">
                    <div id="attachShow" class="text-center mb-2">
                        <i class="bx bxs-file-image text-primary" style="font-size:70"></i>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" onclick="chatCleanAttach();" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Cancelar</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" onclick="chatMessagesSend();" data-dismiss="modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Enviar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#attachImg').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        function chatCleanAttach() {
            $('#attach').val("");
        }
        function getFormatLink(url) {
            if (url != null && url != "") {
                URL = url.split("/");
                return URL[URL.length-1].split('.')[1];
            } else {
                return "";
            }
        }
        var chatSidebarListWrapper = $(".chat-sidebar-list-wrapper"),
            chatOverlay = $(".chat-overlay"),
            chatContainer = $(".chat-container"),
            chatSidebarProfileToggle = $(".chat-sidebar-profile-toggle"),
            chatProfileToggle = $(".chat-profile-toggle"),
            chatSidebarClose = $(".chat-sidebar-close"),
            chatProfile = $(".chat-profile"),
            chatUserProfile = $(".chat-user-profile"),
            chatProfileClose = $(".chat-profile-close"),
            chatSidebar = $(".chat-sidebar"),
            chatArea = $(".chat-area"),
            chatStart = $(".chat-start"),
            chatSidebarToggle = $(".chat-sidebar-toggle"),
            chatMessageSend = $(".chat-message-send");
        var pm_id = 0;
        var r_r_code = '0';
        var group = 0;
        var intervalMsg;
        var version= 2;
        var audioElement = document.createElement('audio');
        var mygroups = {!! $group->pluck('id')->toJson() !!};
        audioElement.setAttribute('src', '/music/new_message.mp3');

        function emojiChat(em) {
            var oldval = $(".chat-message-send").val();
            $(".chat-message-send").val(oldval +' ' + em);
            $(".chat-message-send").focus();
        }
        $(document).ready(function () {
            "use strict";

            // Socket
            socket.on('new version', function(data){
                if (version < data.version) {
                    $(".newVersion").show();
                }
            });

            socket.on('user status', function(data){
                $(".chat-sidebar-list-wrapper ul .id-"+ data.id +"").find('.user-list-status').removeClass('avatar-status-online');
                $(".chat-sidebar-list-wrapper ul .id-"+ data.id +"").find('.user-list-status').removeClass('avatar-status-busy');
                if (data.status == 1) {
                    $(".chat-sidebar-list-wrapper ul .id-"+ data.id +"").find('.user-list-status').addClass('avatar-status-online');
                    $(".chat-sidebar-list-wrapper ul .id-"+ data.id +"").find("#status").val(1);


                } else {
                    $(".chat-sidebar-list-wrapper ul .id-"+ data.id +"").find('.user-list-status').addClass('avatar-status-busy');
                    $(".chat-sidebar-list-wrapper ul .id-"+ data.id +"").find("#status").val(0);

                }
            });
            socket.on('chat message', function(data){
                var hasgroup = mygroups.find(x => x == group);

                if (pm_id == data.pm_id && pm_id != 0) {
                    audioElement.play(); audioElement.currentTime = 0;

                    var html = '';
                    if (data.id != '<?= Session::get('r_code') ?>') {
                        html += '<div class="chat chat-left">';
                    } else {
                        html += '<div class="chat">';
                    }
                    html += '<div class="chat-avatar">';
                    html += '<div class="avatar m-0">';
                    html += '<input type="hidden" id="pic" value="'+ data.picture +'">';
                    html += '<input type="hidden" id="name" value="'+ data.name +'">';
                    html += '<input type="hidden" id="sector" value="'+ data.sector +'">';
                    if (data.id != '<?= Session::get('r_code') ?>') {
                        html += '<img src="'+ data.picture +'" alt="avatar" height="36" width="36" />';
                    } else {
                        html += '<img src="@if (empty(Session::get('picture'))) /media/avatars/avatar10.jpg @else {{ Session::get('picture') }} @endif" alt="avatar" height="36" width="36" />';
                    }
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="chat-body">';
                    html += '<div class="chat-message">';
                    if (data.attach != "" && data.attach != null) {
                        if (getFormatLink(data.attach) == 'jpg' || getFormatLink(data.attach) == 'png' || getFormatLink(data.attach) == 'gif' || getFormatLink(data.attach) == 'jpeg') {
                            html += '<small><b>'+ data.name +'</b></small><p><a target="_blank" target="_blank" href="'+ data.attach +'"><img heigth="200" width="260" src="'+ data.attach +'"></a></p><p>'+ data.msg +'</p>';
                        } else if (getFormatLink(data.attach) == 'pdf') {

                            html += '<small><b>'+ data.name +'</b></small><p><a target="_blank" href="'+ data.attach +'"><i class="bx bxs-file-pdf text-danger" style="font-size:70px"></i></a></p><p>'+ data.msg +'</p>';
                        } else if (getFormatLink(data.attach) == 'xlsx') {
                            html += '<small><b>'+ data.name +'</b></small><p><a target="_blank" href="'+ data.attach +'"><i class="bx bxs-file text-success" style="font-size:70px"></i></a></p><p>'+ data.msg +'</p>';
                        } else {

                            html += '<small><b>'+ data.name +'</b></small><p><a target="_blank" href="'+ data.attach +'"><i class="bx bxs-file-blank text-secondary" style="font-size:70px"></i></a></p><p>'+ data.msg +'</p>';
                        }
                    } else {
                        html += '<small><b>'+ data.name +'</b></small><p>'+ data.msg +'</p>';
                    }
                    html += '<span class="chat-time">'+ data.time +'</span>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    $(".chat-content").append(html);
                    chatContainer.scrollTop($(".chat-container > .chat-content").height());

                } else if (pm_id == 0 && typeof hasgroup != 'undefined') {
                    audioElement.play(); audioElement.currentTime = 0;

                    var html = '';
                    if (data.id != '<?= Session::get('r_code') ?>') {
                        html += '<div class="chat chat-left">';
                    } else {
                        html += '<div class="chat">';
                    }
                    html += '<div class="chat-avatar">';
                    html += '<div class="avatar m-0">';
                    html += '<input type="hidden" id="pic" value="'+ data.picture +'">';
                    html += '<input type="hidden" id="name" value="'+ data.name +'">';
                    html += '<input type="hidden" id="sector" value="'+ data.sector +'">';
                    if (data.id != '<?= Session::get('r_code') ?>') {
                        html += '<img src="'+ data.picture +'" alt="avatar" height="36" width="36" />';
                    } else {
                        html += '<img src="@if (empty(Session::get('picture'))) /media/avatars/avatar10.jpg @else {{ Session::get('picture') }} @endif" alt="avatar" height="36" width="36" />';
                    }
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="chat-body">';
                    html += '<div class="chat-message">';
                    if (data.attach != "" && data.attach != null) {
                        if (getFormatLink(data.attach) == 'jpg' || getFormatLink(data.attach) == 'png' || getFormatLink(data.attach) == 'gif' || getFormatLink(data.attach) == 'jpeg') {
                            html += '<small><b>'+ data.name +'</b></small><p><a target="_blank" href="'+ data.attach +'"><img heigth="200" width="260" src="'+ data.attach +'"></a></p><p>'+ data.msg +'</p>';
                        } else if (getFormatLink(data.attach) == 'pdf') {

                            html += '<small><b>'+ data.name +'</b></small><p><a target="_blank" href="'+ data.attach +'"><i class="bx bxs-file-pdf text-danger" style="font-size:70px"></i></a></p><p>'+ data.msg +'</p>';
                        } else if (getFormatLink(data.attach) == 'xlsx') {
                            html += '<small><b>'+ data.name +'</b></small><p><a target="_blank" href="'+ data.attach +'"><i class="bx bxs-file text-success" style="font-size:70px"></i></a></p><p>'+ data.msg +'</p>';
                        } else {

                            html += '<small><b>'+ data.name +'</b></small><p><a target="_blank" href="'+ data.attach +'"><i class="bx bxs-file-blank text-secondary" style="font-size:70px"></i></a></p><p>'+ data.msg +'</p>';
                        }
                    } else {
                        html += '<small><b>'+ data.name +'</b></small><p>'+ data.msg +'</p>';
                    }
                    html += '<span class="chat-time">'+ data.time +'</span>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    $(".chat-content").append(html);
                    chatContainer.scrollTop($(".chat-container > .chat-content").height());

                }

                if (pm_id != data.pm_id && data.receiver == '<?= Session::get('r_code') ?>') {

                    if (data.msg_total > 0) {

                        $(".chat-sidebar-list-wrapper ul .id-"+ data.id +"").find('.user-list-notify').show().html(data.msg_total);
                        $(".chat-sidebar-list-wrapper ul .id-"+ data.id +"").prependTo("#contacts");
                    } else {
                        $(".chat-sidebar-list-wrapper ul .id-"+ data.id +"").find('.user-list-notify').hide();
                    }

                }
            });

            // Socket End

            $("#clickAttach").click(function (e) {
                $('#attach').trigger('click');
            });

            $("#attach").change(function(){
                $(".attach-title").html($(this)[0].files[0].name);

                if ($(this)[0].files[0].type == "image/png" || $(this)[0].files[0].type == "image/jpg" || $(this)[0].files[0].type == "image/gif") {

                    $("#attachShow").html('<img height="200" width="260" id="attachImg" src="" />');
                    readURL(this);
                } else if ($(this)[0].files[0].type == "application/pdf") {
                    $("#attachShow").html('<i class="bx bxs-file-pdf text-danger" style="font-size:70px"></i>');
                } else if ($(this)[0].files[0].type == "vnd.ms-excel") {
                    $("#attachShow").html('<i class="bx bxs-file text-success" style="font-size:70px"></i>');
                } else {
                    $("#attachShow").html('<i class="bx bxs-file-blank text-secondary" style="font-size:70px"></i>');
                }

                $('#attachUpload').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });

            // menu user list perfect scrollbar initialization
            if (chatSidebarListWrapper.length > 0) {
                var menu_user_list = new PerfectScrollbar(".chat-sidebar-list-wrapper");
            }
            // user profile sidebar perfect scrollbar initialization
            if ($(".chat-user-profile-scroll").length > 0) {
                var profile_sidebar_scroll = new PerfectScrollbar(".chat-user-profile-scroll");
            }
            // chat area perfect scrollbar initialization
            if (chatContainer.length > 0) {
                var chat_user_user = new PerfectScrollbar(".chat-container");
            }
            if ($(".chat-profile-content").length > 0) {
                var chat_profile_content = new PerfectScrollbar(".chat-profile-content");
            }
            // user profile sidebar toggle
            chatSidebarProfileToggle.on("click", function () {
                chatUserProfile.addClass("show");
                chatOverlay.addClass("show");
            });
            // user profile sidebar toggle
            chatProfileToggle.on("click", function () {
                chatProfile.addClass("show");
                chatOverlay.addClass("show");
            });
            // on profile close icon click
            chatProfileClose.on("click", function () {
                chatUserProfile.removeClass("show");
                chatProfile.removeClass("show");
                if (!chatSidebar.hasClass("show")) {
                    chatOverlay.removeClass("show");
                }
            });
            // On chat menu sidebar close icon click
            chatSidebarClose.on("click", function () {
                chatSidebar.removeClass("show");
                chatOverlay.removeClass("show");
            });
            // on overlay click
            chatOverlay.on("click", function () {
                chatSidebar.removeClass("show");
                chatOverlay.removeClass("show");
                chatUserProfile.removeClass("show");
                chatProfile.removeClass("show");
            });
            // Add class active on click of Chat users list
            $("body").addClass('chat-application');
            $(".chat-demo-button").hide();
            $(".chat-sidebar-list-wrapper ul li").on("click", function () {
                $(".chat-content").html("");
                if ($(".chat-sidebar-list-wrapper ul li").hasClass("active")) {
                    $(".chat-sidebar-list-wrapper ul li").removeClass("active");
                }
                var type = $(this).find("#type").val();

                if (type == 'single') {
                    clearInterval(intervalMsg);

                    group = 0;
                    r_r_code = r_code;

                    var r_code = $(this).find("#r_code").val();
                    r_r_code = r_code;
                    var name = $(this).find("#name").val();
                    var picture = $(this).find("#picture").val();
                    var status = $(this).find("#status").val();
                    var sector = $(this).find("#sector").val();

                    $(".user-click-pic").attr('src', picture);
                    $(".user-click-name").html(name);
                    $(".user-click-sector").html(sector);
                    $(this).find('.user-list-notify').hide();

                    $("#loadingChat").show();
                    $.ajax({
                        type: "get",
                        url: "/chat/messages",
                        data: {r_code: r_code, type: type},
                        success: function (response) {
                            $("#loadingChat").hide();
                            var msg = "";
                            pm_id = response.pm_id;
                            for (let index = 0; index < response.message.length; index++) {
                                const obj = response.message[index];
                                if (obj.s_r_code == '<?= Session::get('r_code') ?>') {
                                    msg += '<div class="chat">';
                                } else {
                                    msg += '<div class="chat chat-left">';
                                }
                                msg += '<div class="chat-avatar">';
                                msg += '<div class="avatar m-0">';
                                msg += '<input type="hidden" id="pic" value="'+ obj.picture_s +'">';
                                msg += '<input type="hidden" id="name" value="'+ obj.name_s +'">';
                                msg += '<input type="hidden" id="sector" value="'+ obj.sector_s +'">';
                                msg += '<img src="'+ obj.picture_s +'" alt="avatar" height="36" width="36" />';
                                msg += '</div>';
                                msg += '</div>';
                                msg += '<div class="chat-body">';
                                msg += '<div class="chat-message">';
                                if (obj.attach != "" && obj.attach != null) {
                                    if (getFormatLink(obj.attach) == 'jpg' || getFormatLink(obj.attach) == 'png' || getFormatLink(obj.attach) == 'gif' || getFormatLink(obj.attach) == 'jpeg') {
                                        msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><img heigth="200" width="260" src="'+ obj.attach +'"></a></p><p>'+ obj.msg +'</p>';
                                    } else if (getFormatLink(obj.attach) == 'pdf') {

                                        msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><i class="bx bxs-file-pdf text-danger" style="font-size:70px"></i></a></p><p>'+ obj.msg +'</p>';
                                    } else if (getFormatLink(obj.attach) == 'xlsx') {
                                        msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><i class="bx bxs-file text-success" style="font-size:70px"></i></a></p><p>'+ obj.msg +'</p>';
                                    } else {

                                        msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><i class="bx bxs-file-blank text-secondary" style="font-size:70px"></i></a></p><p>'+ obj.msg +'</p>';
                                    }
                                } else {
                                    msg += '<small><b>'+ obj.name_s +'</b></small><p>'+ obj.msg +'</p>';
                                }
                                msg += '<span class="chat-time">'+ obj.time +'</span>';
                                msg += '</div>';
                                msg += '</div>';
                                msg += '</div>';


                            }
                            $(".chat-content").html(msg);
                            intervalMsg = setInterval(() => {
                                chatContainer.scrollTop($(".chat-container > .chat-content").height());
                                clearInterval(intervalMsg);
                            }, 300);
                        }
                    });

                    $(".user-select-pic").attr('src', picture);
                    $(".user-select-name").html(name);

                    $(".user-select").show();
                    $(".user-select-name").show();
                    $(".group-select").hide();

                } else {
                    clearInterval(intervalMsg);

                    pm_id = 0;
                    r_r_code = '0';

                    var name = $(this).find("#name").val();
                    var g_id = $(this).find("#g_id").val();
                    group = g_id;

                    $("#loadingChat").show();
                    $.ajax({
                        type: "get",
                        url: "/chat/messages",
                        data: {g_id: g_id, type: type},
                        success: function (response) {
                            $("#loadingChat").hide();
                            var msg = "";
                            for (let index = 0; index < response.message.length; index++) {
                                const obj = response.message[index];
                                if (obj.s_r_code != '<?= Session::get('r_code') ?>') {
                                    msg += '<div class="chat chat-left">';
                                } else {
                                    msg += '<div class="chat">';
                                }
                                msg += '<div class="chat-avatar">';
                                msg += '<div class="avatar m-0">';
                                msg += '<input type="hidden" id="pic" value="'+ obj.picture +'">';
                                msg += '<input type="hidden" id="name" value="'+ obj.name_s +'">';
                                msg += '<input type="hidden" id="sector" value="'+ obj.sector_s +'">';
                                if (obj.s_r_code != '<?= Session::get('r_code') ?>') {
                                    msg += '<img src="'+ obj.picture_s +'" alt="avatar" height="36" width="36" />';
                                } else {
                                    msg += '<img src="@if (empty(Session::get('picture'))) /media/avatars/avatar10.jpg @else {{ Session::get('picture') }} @endif" alt="avatar" height="36" width="36" />';
                                }
                                msg += '</div>';
                                msg += '</div>';
                                msg += '<div class="chat-body">';
                                msg += '<div class="chat-message">';
                                if (obj.attach != "" && obj.attach != null) {
                                    if (getFormatLink(obj.attach) == 'jpg' || getFormatLink(obj.attach) == 'png' || getFormatLink(obj.attach) == 'gif' || getFormatLink(obj.attach) == 'jpeg') {
                                        msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><img heigth="200" width="260" src="'+ obj.attach +'"></a></p><p>'+ obj.msg +'</p>';
                                    } else if (getFormatLink(obj.attach) == 'pdf') {

                                        msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><i class="bx bxs-file-pdf text-danger" style="font-size:70px"></i></a></p><p>'+ obj.msg +'</p>';
                                    } else if (getFormatLink(obj.attach) == 'xlsx') {
                                        msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><i class="bx bxs-file text-success" style="font-size:70px"></i></a></p><p>'+ obj.msg +'</p>';
                                    } else {

                                        msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><i class="bx bxs-file-blank text-secondary" style="font-size:70px"></i></a></p><p>'+ obj.msg +'</p>';
                                    }
                                } else {
                                    msg += '<small><b>'+ obj.name_s +'</b></small><p>'+ obj.msg +'</p>';
                                }
                                msg += '<span class="chat-time">'+ obj.time +'</span>';
                                msg += '</div>';
                                msg += '</div>';
                                msg += '</div>';

                            }
                            $(".chat-content").html(msg);
                            intervalMsg = setInterval(() => {
                                chatContainer.scrollTop($(".chat-container > .chat-content").height());
                                clearInterval(intervalMsg);
                            }, 300);
                        }
                    });
                    $(".group-select").html(name);
                    $(".user-select").hide();
                    $(".user-select-name").hide();
                    $(".group-select").show();
                }

                $(this).addClass("active");
                if ($(".chat-sidebar-list-wrapper ul li").hasClass("active")) {
                    chatStart.addClass("d-none");
                    chatArea.removeClass("d-none");
                }
                else {
                    chatStart.removeClass("d-none");
                    chatArea.addClass("d-none");
                }
            });
            // app chat favorite star click
            $(".chat-icon-favorite i").on("click", function (e) {
                $(this).parent(".chat-icon-favorite").toggleClass("warning");
                $(this).toggleClass("bxs-star bx-star");
                e.stopPropagation();
            });
            // menu toggle till medium screen
            if ($(window).width() < 992) {
                chatSidebarToggle.on("click", function () {
                    chatSidebar.addClass("show");
                    chatOverlay.addClass("show");
                });
            }
            // autoscroll to bottom of Chat area
            $(".chat-sidebar-list li").on("click", function () {
                chatContainer.animate({ scrollTop: chatContainer[0].scrollHeight }, 400)
            });

            // click on main menu toggle will remove sidebars & overlays
            $(".menu-toggle").click(function () {
                chatSidebar.removeClass("show");
                chatOverlay.removeClass("show");
                chatUserProfile.removeClass("show");
                chatProfile.removeClass("show");
            });

            // chat search filter
            $("#chat-search").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                if (value != "") {
                    $(".chat-sidebar-list-wrapper .chat-sidebar-list li").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }
                else {
                    // if search filter box is empty
                    $(".chat-sidebar-list-wrapper .chat-sidebar-list li").show();
                }
            });
            // window resize
            $(window).on("resize", function () {
                // remove show classes from overlay when resize, if size is > 992
                if ($(window).width() > 992) {
                    if (chatOverlay.hasClass("show")) {
                        chatOverlay.removeClass("show");
                    }
                }
                // menu toggle on resize till medium screen
                if ($(window).width() < 992) {
                    chatSidebarToggle.on("click", function () {
                        chatSidebar.addClass("show");
                        chatOverlay.addClass("show");
                    });
                }
                // disable on click overlay when resize from medium to large
                if ($(window).width() > 992) {
                    chatSidebarToggle.on("click", function () {
                        chatOverlay.removeClass("show");
                    });
                }
            });
        });
        // Add message to chat
        function chatMessagesSend() {
            var message;
            if ($('#attach')[0].files.length > 0) {
                $("input[name=msg]").val($("#attachMsg").val())
                message = $("#attachMsg").val();
            } else {
                message = $("input[name=msg]").val();
            }
            if (message != "") {
                $( "input[name=receiver]").val(r_r_code);
                $( "input[name=group]").val(group);
                $( "input[name=pm_id]").val(pm_id);
                if (group == 0) {
                    $(".chat-sidebar-list-wrapper ul .id-"+ r_r_code +"").prependTo("#contacts");
                }
                // Get form
                var form = $('#chatForm')[0];
                // Create an FormData object
                var data = new FormData(form);

                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: "/chat/new/message",
                    processData: false, // impedir que o jQuery tranforma a "data" em querystring
                    contentType: false, // desabilitar o cabeçalho "Content-Type"
                    data: data,
                    success: function (response) {
                        $('#attach').val("");
                        $("#attachMsg").val("");
                        if (pm_id == 0 && group == 0) {
                            pm_id = response.pm_id;
                            $("#loadingChat").show();
                            $.ajax({
                                type: "get",
                                url: "/chat/messages",
                                data: {r_code: r_r_code, type: 'single'},
                                success: function (response) {
                                    $("#loadingChat").hide();
                                    var msg = "";
                                    pm_id = response.pm_id;
                                    for (let index = 0; index < response.message.length; index++) {
                                        const obj = response.message[index];
                                        if (obj.s_r_code == '<?= Session::get('r_code') ?>') {
                                            msg += '<div class="chat">';
                                        } else {
                                            msg += '<div class="chat chat-left">';
                                        }
                                        msg += '<div class="chat-avatar">';
                                        msg += '<div class="avatar m-0">';
                                        msg += '<input type="hidden" id="pic" value="'+ obj.picture_s +'">';
                                        msg += '<input type="hidden" id="name" value="'+ obj.name_s +'">';
                                        msg += '<input type="hidden" id="sector" value="'+ obj.sector_s +'">';
                                        msg += '<img src="'+ obj.picture_s +'" alt="avatar" height="36" width="36" />';
                                        msg += '</div>';
                                        msg += '</div>';
                                        msg += '<div class="chat-body">';
                                        msg += '<div class="chat-message">';
                                        if (obj.attach != "" && obj.attach != null) {
                                            if (getFormatLink(obj.attach) == 'jpg' || getFormatLink(obj.attach) == 'png' || getFormatLink(obj.attach) == 'gif' || getFormatLink(obj.attach) == 'jpeg') {
                                                msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><img heigth="200" width="260" src="'+ obj.attach +'"></a></p><p>'+ obj.msg +'</p>';
                                            } else if (getFormatLink(obj.attach) == 'pdf') {

                                                msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><i class="bx bxs-file-pdf text-danger" style="font-size:70px"></i></a></p><p>'+ obj.msg +'</p>';
                                            } else if (getFormatLink(obj.attach) == 'xlsx') {
                                                msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><i class="bx bxs-file text-success" style="font-size:70px"></i></a></p><p>'+ obj.msg +'</p>';
                                            } else {

                                                msg += '<small><b>'+ obj.name_s +'</b></small><p><a target="_blank" href="'+ obj.attach +'"><i class="bx bxs-file-blank text-secondary" style="font-size:70px"></i></a></p><p>'+ obj.msg +'</p>';
                                            }
                                        } else {
                                            msg += '<small><b>'+ obj.name_s +'</b></small><p>'+ obj.msg +'</p>';
                                        }
                                        msg += '<span class="chat-time">'+ obj.time +'</span>';
                                        msg += '</div>';
                                        msg += '</div>';
                                        msg += '</div>';


                                    }
                                    $(".chat-content").html(msg);
                                    chatContainer.scrollTop($(".chat-container > .chat-content").height());
                                }
                            });
                        } else {
                            pm_id = response.pm_id;
                        }

                    }
                });
                chatMessageSend.val("");
            }
        }
    </script>
@endsection
