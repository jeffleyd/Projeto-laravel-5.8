@extends('gree_i.layout')

@section('content')
<style>
  ul {
    padding: 0px !important;
  }
</style>
{{-- <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/jkanban/jkanban.min.css"> --}}
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">   
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/tree/tree.css">
<div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="card">
        <div class="card-header">
          <a class="heading-elements-toggle">
            <i class="bx bx-dots-vertical font-medium-3"></i>
          </a>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li>
                <a data-action="expand">
                  <i class="bx bx-fullscreen"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
              <div class="table-responsive ps">
                <table class="table table-borderless">
                  <tbody>
                    <tr class="text-center">
                      <td class="pb-0 pl-0"><strong>Data de inicio</strong></td>
                      <td class="pb-0 pl-0"><strong>Data final</strong></td>
                      <td class="pb-0"><strong>Responsáveis</strong></td>
                    </tr>
                    <tr class="text-center">
                      <td class="pl-0">
                        <div class="badge badge-light-primary text-bold-500 py-50">02 Abril 2020</div>
                      </td>
                      <td class="pl-0">
                        <div class="badge badge-light-danger text-bold-500 py-50">06 Maio 2020</div>
                      </td>
                      <td>
                        <ul class="list-unstyled users-list m-0">
                          <li data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Lai Lewandowski" class="avatar pull-up">
                            <img class="media-object rounded-circle" src="/admin/app-assets/images/portrait/small/avatar-s-6.jpg" alt="Avatar" height="30" width="30">
                          </li>
                          <li data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Elicia Rieske" class="avatar pull-up">
                            <img class="media-object rounded-circle" src="/admin/app-assets/images/portrait/small/avatar-s-7.jpg" alt="Avatar" height="30" width="30">
                          </li>
                          <li data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Darcey Nooner" class="avatar pull-up">
                            <img class="media-object rounded-circle" src="/admin/app-assets/images/portrait/small/avatar-s-8.jpg" alt="Avatar" height="30" width="30">
                          </li>
                          <li data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Julee Rossignol" class="avatar pull-up">
                            <img class="media-object rounded-circle" src="/admin/app-assets/images/portrait/small/avatar-s-10.jpg" alt="Avatar" height="30" width="30">
                          </li>
                          <li class="avatar pull-up">
                            <span class="badge badge-pill badge-light-primary badge-round">+7</span>
                          </li>
                        </ul>
                      </td>
                    </tr>
                  </tbody>
                </table>
              <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              <span>Progresso total</span>
              <div class="progress progress-bar-primary progress-sm mb-3">
                <div class="progress-bar progress-label" role="progressbar" aria-valuenow="78" style="width:78%"></div>
              </div>

            <div class="overflow">
              <div>
                  <ul class="tree">
                      <li>
                          <div data-id="1" data-id-last="0" data-is-root="true">Lançamento G-TECH</div>
                      </li>
                  </ul>
              </div>
          </div>
          </div>
        </div>
      </div>
    </div>

    <!-- save template -->
    <div class="modal fade text-left modal-borderless" id="save-template" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Salvando template</h3>
            <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <fieldset>
                  <div class="input-group">
                    <input type="text" class="form-control" id="template_name" placeholder="Digite nome do template" aria-describedby="save_template">
                    <div class="input-group-append">
                      <button class="btn btn-primary" id="save_template" type="button">Salvar</button>
                    </div>
                    <p><small>Para salvar por cima, apenas digite o mesmo nome do template abaixo.</small></p>
                  </div>
                </fieldset>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered mb-0" style="border-bottom: solid 1px #DFE3E7;">
                    <thead>
                      <tr>
                        <th>NOME</th>
                        <th>VERSÃO</th>
                        <th>AÇÕES</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Comercial planejamento</td>
                        <td class="text-bold-500">1</td>
                        <td>
                          <div class="dropleft">
                              <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                  <a href="#" class="dropdown-item"><i class="bx bxs-download mr-1"></i> Usar</a>  
                                  <a href="#" class="dropdown-item"><i class="bx bx-x-circle mr-1"></i> Excluir</a>
                              </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>TI - Gerenciamento de projetos</td>
                        <td class="text-bold-500">3</td>
                        <td>
                          <div class="dropleft">
                            <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item"><i class="bx bxs-download mr-1"></i> Usar</a>  
                                <a href="#" class="dropdown-item"><i class="bx bx-x-circle mr-1"></i> Excluir</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
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
    
    <div class="modal fade text-left" id="kaban-view" tabindex="-1" role="dialog" aria-labelledby="kaban-view" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="kaban-view-title">TI - Acompanhamento #2485</h3>
            <button type="button" class="close rounded-pill close-icon" data-dismiss="modal" aria-label="Close">
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="modal-body">
              <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                  <li class="nav-item current">
                      <a class="nav-link active" id="detail-tab-justified" data-toggle="tab" href="#detail-just" role="tab" aria-controls="home-just" aria-selected="true">
                      Detalhes
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="comment-tab-justified" data-toggle="tab" href="#comment-just" role="tab" aria-controls="profile-just" aria-selected="false">
                      Atividades
                      </a>
                  </li>
              </ul>
              <div class="tab-content pt-1">
                  <div class="tab-pane active" id="detail-just" role="tabpanel" aria-labelledby="detail-tab-justified">
                    <div class="row input_description_edit" style="display:none;">
                      <div class="col-12">
                        <div class="form-group">
                          <label>Editando descrição da tarefa</label>
                          <textarea rows="5" id="input_description_edit" class="form-control edit-kanban-item-title">Preciso fazer com que todas autorizadas se atualizem sozinhas</textarea>
                        </div>
                      </div>
                      <div class="col-12 d-flex justify-content-between p-25">
                        <button class="btn btn-primary text-nowrap px-1" style="width: 100%; margin-left: 12px;margin-right: 12px;margin-bottom: 15px;" id="input_description_save" type="button"> <i class="bx bx-check"></i></button>
                      </div>
                    </div>  
                    <div class="form-group input_description">
                        <label>Descrição da tarefa <small>(Clique em cima para editar)</small></label>
                        <p class="form-control-static bg-secondary bg-light p-2" id="input_description">Preciso fazer com que todas autorizadas se atualizem sozinhas</p>
                    </div>
                      <div class="form-group">
                          <label>Data de vencimento</label>
                          <input type="text" class="form-control edit-kanban-item-date" placeholder="21 August, 2019">
                      </div>
                      <div class="row">
                          <div class="col-6">
                              <div class="form-group">
                              <label>Prioridade</label>
                              <select id="priority" class="form-control text-white">
                                  <option class="bg-info" value="1" selected>Baixa</option>
                                  <option class="bg-warning" value="2">Média</option>
                                  <option class="bg-danger" value="3">Urgente</option>
                              </select>
                              </div>
                          </div>
                          <div class="col-6">
                              <div class="form-group">
                              <label>Responsável pela tarefa</label>
                              <div class="d-flex align-items-center">
                                  <div class="avatar m-0 mr-1">
                                      <a href="/user/view/4447" target="_blank"><img src="https://s3.amazonaws.com/gree-app.com.br/20200508104343.png" height="36" width="36" alt="avtar img holder"></a>
                                  </div>
                                  <div class="badge-circle badge-circle-light-secondary">
                                  <i class="bx bx-plus"></i>
                                  </div>
                              </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="tab-pane" id="comment-just" role="tabpanel" aria-labelledby="comment-tab-justified">
                    <fieldset class="form-group">
                      <label>Mudar status</label>
                      <select class="form-control" id="change_status">
                        <option>A fazer</option>
                        <option>Em andamento</option>
                        <option>Concluído</option>
                      </select>
                    </fieldset>
                    <label>Realizar um comentário</label>
                    <div class="snow-container border rounded p-1">
                        <div class="compose-editor" style="border: 0px !important"></div>
                        <div class="d-flex justify-content-end">
                        <div class="compose-quill-toolbar" style="width: 100%; border: 0px !important">
                            <span class="ql-formats mr-0">
                            <button class="ql-bold"></button>
                            <button class="ql-italic"></button>
                            <button class="ql-underline"></button>
                            <button class="ql-link"></button>
                            <button class="ql-image"></button>
                            <button class="btn btn-sm btn-primary btn-comment ml-25" style="width:120px;">Publicar</button>
                            </span>
                        </div>
                        </div>
                    </div>
                    <div class="media-list mt-1">
                      <div class="media">
                        <a class="pr-1" href="#">
                          <img class="media-object rounded-circle" src="/admin/app-assets/images/portrait/small/avatar-s-10.jpg" alt="Generic placeholder image" height="30" width="30">
                        </a><div class="media-body">
                          <div class="row">
                            <div class="col-6 text-left">
                              <h6 class="media-heading"><b>Priscila C.N</b></h6>
                            </div>
                            <div class="col-6 text-right" style="font-size: 12px">
                              <i>21/07/2020 15:16</i>
                            </div>
                          </div>
                          Oat cake topping oat cake jelly soufflé donut jelly-o tootsie roll. Candy sweet cake. Tiramisu cookie
                          toffee donut. Chocolate pie croissant gummi bears muffin dessert chocolate.
                        </div>
                        
                      </div>
                      <div class="media">
                        <a class="pr-1" href="#">
                          <img class="media-object rounded-circle" src="/admin/app-assets/images/portrait/small/avatar-s-7.jpg" alt="Generic placeholder image" height="30" width="30">
                        </a><div class="media-body">
                          <div class="row">
                            <div class="col-6 text-left">
                              <h6 class="media-heading"><b>Jefferson T.S</b></h6>
                            </div>
                            <div class="col-6 text-right" style="font-size: 12px">
                              <i>21/07/2020 15:16</i>
                            </div>
                          </div>
                          
                          Jelly chocolate cake lemon drops halvah dragée caramels jelly-o biscuit. Fruitcake jelly beans
                          marzipan sesame snaps.Jelly beans cake chocolate cake gummi bears lollipop.
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary closemodal" data-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Fechar</span>
            </button>
            <button type="button" class="btn btn-light-danger ml-1 closemodal delete-kanban-item" data-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Deletar</span>
            </button>
            <button type="button" class="btn btn-success ml-1 closemodal update-kanban-item" data-dismiss="modal">
              <i class="bx bx-check d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Salvar</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <script>
        $(document).ready(function () {
          $('.tree').tree_structure({
                    'add_option': true,
                    'edit_option': true,
                    'delete_option': true,
                    'confirm_before_delete': true,
                    'fullwidth_option': false,
                    'align_option': 'center',
                    'draggable_option': false
                });
            setTimeout(() => {
                $("#mTI").addClass('sidebar-group-active active');
                $("#mTIDeveloper").addClass('sidebar-group-active active');
                $("#mTIDeveloperList").addClass('active');
                $(".kanban-container").attr('style', 'width: 1250px !important;');
            }, 100);
            
        });

        $(".accordion-header").on( "click", function() {
            $(this).toggleClass("active").next(".accordion-content").slideToggle();
        });
        </script>
        
@endsection