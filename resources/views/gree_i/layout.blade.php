<?php

$changelogs = App\Model\ChangeLogs::orderBy('created_at', 'DESC')->first();

?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
    <!-- BEGIN: Head-->
    @include('gree_i.layout.head')
    <!-- END: Head-->

    <!-- BEGIN: Body-->

    <body class="horizontal-layout horizontal-menu navbar-sticky 2-columns   footer-static  " data-open="hover" data-menu="horizontal-menu" data-col="2-columns">
        <div class="toast toast-light" role="alert" aria-live="assertive" aria-atomic="true" data-delay="10000">
            <div class="toast-header">
                <i class="bx bx-bell"></i>
                <span class="mr-auto toast-title fcm-title"></span>
                <small class="d-sm-block d-none">Agora</small>
                <button type="button" class=" close" data-dismiss="toast" aria-label="Close">
                <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="toast-body fcm-body">
            </div>
        </div>
        <div class="toast-bs-container">
            <div class="toast-position"></div>
        </div>
        <!-- BEGIN: Header-->
        @include('gree_i.layout.nav-header')
        <!-- END: Header-->


        <!-- BEGIN: Main Menu-->
        @include('gree_i.layout.menu.main-menu')
        <!-- END: Main Menu-->

        <!-- BEGIN: Content-->
        <div class="app-content content">
            @yield('content')
			@if (Session::get('is_holiday'))
			<div style="background-color:red; color:white; width:100%; padding: 7px; text-align:center; position: fixed;z-index: 9999;font-size: 12px;bottom: 0;">Você está no <b>modo férias</b></div>
			@endif
        </div>
        <!-- END: Content-->
		
        <!-- Version Modal -->
        <div class="modal fade text-left" id="modal-version" tabindex="-1" role="dialog" aria-labelledby="modal-version" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title white">{{ __('layout_i.vlm_version') }} <small><?= getConfig("version_name") ?></small></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (Session::get('lang') == 'pt-br') { ?>
                    <?= $changelogs->text_pt ?>
                    <?php } else { ?>
                    <?= $changelogs->text_en ?>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">{{ __('layout_i.vlm_close') }}</span>
                    </button>
                </div>
                </div>
            </div>
        </div>
        <!-- END Version Modal -->
        
        @if (!Session::get('picture'))
        <!-- Picture Modal -->
        <div class="modal fade text-left" id="modal-pic" tabindex="-1" role="dialog" aria-labelledby="modal-pic" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title white">FOTO DE PERFIL É OBRIGATÓRIO!</h5>
                </div>
                <form action="/misc/user/picture" id="picsend" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p>Para dar andamento no uso do sistema é preciso enviar uma foto sua de perfil no sistema para completar o cadastro.</p>
                    <p>
                        <b>Tamanho recomendado:</b>
                        <br>320 pixel de largura
                        <br>320 pixel de altura
                        <br>Máximo de 500kb
                    </p>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <fieldset class="form-group">
                                <label for="picture">Imagem</label>
                                <input type="file" class="form-control-file" name="picture" id="picture">
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Atualizar imagem</span>
                    </button>
                </div>
                </form>
                </div>
            </div>
        </div>
        <!-- END Picture Modal -->
        @endif
		
		<div class="modal fade text-left" id="admRequestSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Buscar código</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <fieldset>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="bx bxs-barcode"></i></span>
                                </div>
                                <input type="text" class="form-control" id="admrequestcode" placeholder="1020-A247E668">
                              </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-sm-block d-none">Fechar</span>
                  </button>
                  <button type="button" class="btn btn-primary ml-1 btn-sm" onclick="admSearchCode()">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-sm-block d-none">Buscar</span>
                  </button>
                </div>
              </div>
            </div>
        </div>
		
		<div class="modal fade text-left" id="admRequestEntryExit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tipo de solicitação</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="is_entry_exit" id="modal_is_entry_exit" class="form-control">
                                        <option value="3">Funcionários</option>
                                        <option value="2">Transportes de cargas</option>
                                        <option value="1">Visitas & Tercerizados</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-sm-block d-none">Fechar</span>
                        </button>
                        <button type="button" class="btn btn-primary ml-1 btn-sm" onclick="admEntryExitApprov()">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-sm-block d-none">Acessar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('gree_i.layout.survey')

        @yield('survey-modal')

        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>

        <a href="/chat/main">
            <button class="btn btn-primary chat-demo-button glow px-1"><i class="livicon-evo"
                data-options="name: comments.svg; style: lines; size: 24px; strokeColor: #fff; autoPlay: true; repeat: loop;"></i></button>
        </a>

        <!-- BEGIN: Footer-->
        @include('gree_i.layout.footer')
        <!-- END: Footer-->

        <!-- BEGIN: JS Vendor Scripts -->
        @include('gree_i.layout.js-vendor-scripts')
        <!-- END: JS Vendor Scripts-->
        
        <!-- BEGIN: JS Dev Scripts -->
        @include('gree_i.layout.js-user-scripts')
        <!-- END: JS Dev Scripts-->
        
        @yield('json-data')

    </body>
    <!-- END: Body-->

</html>