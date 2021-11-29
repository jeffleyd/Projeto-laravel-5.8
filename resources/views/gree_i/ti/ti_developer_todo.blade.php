@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/pages/app-todo.css">
<div class="content-area-wrapper">
    <div class="sidebar-left">
        <div class="sidebar">
            <div class="todo-sidebar d-flex">
                <span class="sidebar-close-icon">
                    <i class="bx bx-x"></i>
                </span>
                <!-- todo app menu -->
                <div class="todo-app-menu">
                    <div class="form-group text-center add-task">
                        <button type="button" class="btn btn-primary add-task-btn btn-block my-1">
                            <i class="bx bx-plus"></i>
                            <span>NOVO PROJETO</span>
                        </button>
                    </div>
                    <!-- sidebar list start -->
                    <div class="sidebar-menu-list">
                        <div class="list-group">
                            <a href="#" class="list-group-item border-0 active">
                                <span class="fonticon-wrap mr-50">
                                    <i class="livicon-evo" data-options="name: list.svg; size: 24px; style: lines; strokeColor:#5A8DEE; eventOn:grandparent;"></i>
                                </span>
                                <span> Todos</span>
                            </a>
                        </div>
                        <label class="filter-label mt-2 mb-1 pt-25">Filtros</label>
                        <div class="list-group">
                            <a href="#" class="list-group-item border-0">
                                <span class="fonticon-wrap mr-50">
                                    <i class="livicon-evo" data-options="name: check.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent;"></i>
                                </span>
                                <span>Concluídos</span>
                            </a>
                            <a href="#" class="list-group-item border-0">
                                <span class="fonticon-wrap mr-50">
                                    <i class="livicon-evo" data-options="name: trash.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent;"></i>
                                </span>
                                <span>Cancelados</span>
                            </a>
                        </div>
                        <label class="filter-label mt-2 mb-1 pt-25">Setores</label>
                        <select class="form-control" id="sector" name="sector">
                            <option value=""></option>
                            <option value="1">Comercial</option>
                            <option value="2">Industrial</option>
                            <option value="3">Financeiro</option>
                            <option value="4">Expedição & Recebimento</option>
                            <option value="5">Importação</option>
                            <option value="6">Administração</option>
                            <option value="7">Recursos humanos</option>
                            <option value="8">Compras</option>
                            <option value="9">TI</option>
                            <option value="10">Manutenção</option>
                        </select>
                    </div>
                    <!-- sidebar list end -->
                </div>
            </div>

        </div>
    </div>
    <div class="content-right">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="app-content-overlay"></div>
                <div class="todo-app-area">
                    <div class="todo-app-list-wrapper">
                        <div class="todo-app-list">
                            <div class="todo-fixed-search d-flex justify-content-between align-items-center">
                                <div class="sidebar-toggle d-block d-lg-none">
                                    <i class="bx bx-menu"></i>
                                </div>
                                <fieldset class="form-group position-relative has-icon-left m-0 flex-grow-1">
                                    <input type="text" class="form-control todo-search" id="todo-search" placeholder="Buscar projeto...">
                                    <div class="form-control-position">
                                        <i class="bx bx-search"></i>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="todo-task-list list-group">
                                <!-- task list start -->
                                <ul class="todo-task-list-wrapper list-unstyled" id="todo-task-list-drag">
                                    <li class="todo-item completed" data-name="David Smith">
                                        <div class="todo-title-wrapper d-flex justify-content-sm-between justify-content-end align-items-center">
                                            <div class="todo-title-area d-flex">
                                                <i class="bx bx-check mr-1"></i>
                                                07-07-2020 | <p class="todo-title mx-50 m-0 truncate">Novo sistema automatizado para o SAC</p>
                                            </div>
                                            <div class="todo-item-action d-flex align-items-center">
                                                <div class="todo-badge-wrapper d-flex">
                                                    <span class="badge badge-primary badge-pill ml-50">Comercial</span>
                                                </div>
                                                <div class="avatar ml-1">
                                                    <img src="/admin/app-assets/images/portrait/small/avatar-s-1.jpg" alt="avatar" height="30" width="30">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="todo-item completed" data-name="David Smith">
                                      <div class="todo-title-wrapper d-flex justify-content-sm-between justify-content-end align-items-center">
                                          <div class="todo-title-area d-flex">
                                            <i class="bx bx-x mr-1"></i>
                                            06-07-2020 | <p class="todo-title mx-50 m-0 truncate">Criar lista de backlog</p>
                                          </div>
                                          <div class="todo-item-action d-flex align-items-center">
                                              <div class="todo-badge-wrapper d-flex">
                                                  <span class="badge badge-primary badge-pill ml-50">TI</span>
                                              </div>
                                              <div class="avatar ml-1">
                                                  <img src="/admin/app-assets/images/portrait/small/avatar-s-2.jpg" alt="avatar" height="30" width="30">
                                              </div>
                                          </div>
                                      </div>
                                    </li>
                                    <li class="todo-item" data-name="David Smith">
                                      <div class="todo-title-wrapper d-flex justify-content-sm-between justify-content-end align-items-center">
                                          <div class="todo-title-area d-flex">
                                            <i class="bx bx-dots-horizontal-rounded mr-1"></i>
                                            05-07-2020 | <p class="todo-title mx-50 m-0 truncate">Lista de pesquisa para saber o nível de satisfação dos funcionários</p>
                                          </div>
                                          <div class="todo-item-action d-flex align-items-center">
                                              <div class="todo-badge-wrapper d-flex">
                                                  <span class="badge badge-primary badge-pill ml-50">Administração</span>
                                              </div>
                                              <div class="avatar ml-1">
                                                  <img src="/admin/app-assets/images/portrait/small/avatar-s-3.jpg" alt="avatar" height="30" width="30">
                                              </div>
                                          </div>
                                      </div>
                                    </li>
                                </ul>
                                <!-- task list end -->
                                <div class="no-results">
                                    <h5>Nenhum projeto foi encontrado</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $("body").addClass('horizontal-layout horizontal-menu navbar-sticky content-left-sidebar todo-application footer-static  menu-expanded pace-done');
    $('#list-datatable').DataTable( {
        searching: false,
        paging: false,
        ordering:false,
        lengthChange: false,
        language: {
            search: "{{ __('layout_i.dtbl_search') }}",
            zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
            info: "{{ __('layout_i.dtbl_info') }}",
            infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
            infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
        }
    });

    $("#sector").change(function (e) { 

      $('.content-right').block({
            message: '<span class="spinner-border text-primary spinner-border-sm" role="status" aria-hidden="true"></span> <span style="position: relative;top: 1px;left: 5px;">Por favor, aguarde...</span>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });

      setTimeout(() => {
        $('.content-right').unblock();
      }, 1000);
      
    });

});
</script>
<script>
// Todo App variables
var todoNewTasksidebar = $(".todo-new-task-sidebar"),
  appContentOverlay = $(".app-content-overlay"),
  sideBarLeft = $(".sidebar-left"),
  todoTaskListWrapper = $(".todo-task-list-wrapper"),
  todoItem = $(".todo-item"),
  selectAssignLable = $(".select2-assign-label"),
  selectUsersName = $(".select2-users-name"),
  avatarUserImage = $(".avatar-user-image"),
  updateTodo = $(".update-todo"),
  addTodo = $(".add-todo"),
  markCompleteBtn = $(".mark-complete-btn"),
  newTaskTitle = $(".new-task-title"),
  taskTitle = $(".task-title"),
  noResults = $(".no-results"),
  assignedAvatarContent = $(".assigned .avatar .avatar-content"),
  todoAppMenu = $(".todo-app-menu");

// badge colors object define here for badge color
var badgeColors = {
  "Frontend": "badge-light-primary",
  "Backend": "badge-light-success",
  "Issue": "badge-light-danger",
  "Design": "badge-light-warning",
  "Wireframe": "badge-light-info",
}

$(function () {
  "use strict";


  // dragable list
  dragula([document.getElementById("todo-task-list-drag")], {
    moves: function (el, container, handle) {
      return handle.classList.contains("handle");
    }
  });

  // On sidebar close click hide sidebarleft and overlay
  $(".todo-application .sidebar-close-icon").on('click', function () {
    sideBarLeft.removeClass('show');
    appContentOverlay.removeClass('show');
  });


  // Sidebar scrollbar
  if ($('.todo-application .sidebar-menu-list').length > 0) {
    var sidebarMenuList = new PerfectScrollbar('.sidebar-menu-list', {
      theme: "dark",
      wheelPropagation: false
    });
  }

  //  New task scrollbar
  if (todoNewTasksidebar.length > 0) {
    var todo_new_task_sidebar = new PerfectScrollbar('.todo-new-task-sidebar', {
      theme: "dark",
      wheelPropagation: false
    });
  }

  // Task list scrollbar
  if ($('.todo-application .todo-task-list').length > 0) {
    var sidebar_todo = new PerfectScrollbar('.todo-task-list', {
      theme: "dark",
      wheelPropagation: false
    });
  }

  // Add class active on click of sidebar menu's filters
  todoAppMenu.find(".list-group a").on('click', function () {
    var $this = $(this);
    todoAppMenu.find(".active").removeClass('active');
    $this.addClass("active")
    // if active class available icon color primary blue else gray
    if ($this.hasClass('active')) {
      $this.find(".livicon-evo").updateLiviconEvo({
        strokeColor: '#5A8DEE'
      });
      todoAppMenu.find(".list-group a").not(".active").find(".livicon-evo").updateLiviconEvo({
        strokeColor: '#475f7b'
      });
    }
  });

  // Todo sidebar toggle
  $('.sidebar-toggle').on('click', function (e) {
    e.stopPropagation();
    sideBarLeft.toggleClass('show');
    appContentOverlay.addClass('show');
  });

  //On compose btn click of compose mail visible and sidebar left hide
  $('.add-task-btn').on('click', function () {
    alert('Nova tarefa.');
  });


  // ************Rightside content************//
  // -----------------------------------------

  // Search filter for task list
  $(document).on("keyup", ".todo-search", function () {
    todoItem = $(".todo-item");
    $('.todo-item').css('animation', 'none')
    var value = $(this).val().toLowerCase();
    if (value != "") {
      todoItem.filter(function () {
        $(this).toggle($(this).text().toLowerCase().includes(value));
      });
      var tbl_row = $(".todo-item:visible").length; //here tbl_test is table name

      //Check if table has row or not
      if (tbl_row == 0) {
        if (!noResults.hasClass('show')) {
          noResults.addClass('show');
        }
      }
      else {
        noResults.removeClass('show');
      }
    }
    else {
      // If filter box is empty
      todoItem.show();
      if (noResults.hasClass('show')) {
        noResults.removeClass('show');
      }
    }
  });
  // on Todo Item click show data in sidebar
  var globalThis = ""; // Global variable use in multiple function
  todoTaskListWrapper.on('click', '.todo-item', function (e) {
    alert('Ver detalhes');

  }).on('click', '.todo-item-favorite', function (e) {
    e.stopPropagation();
    $(this).toggleClass("warning");
    $(this).find("i").toggleClass("bxs-star");
  }).on('click', '.todo-item-delete', function (e) {
    e.stopPropagation();
    $(this).closest('.todo-item').remove();
  }).on('click', '.checkbox', function (e) {
    e.stopPropagation();
  });

  // Complete task strike through
  todoTaskListWrapper.on('click', ".todo-item .checkbox-input", function (e) {
    $(this).closest('.todo-item').toggleClass("completed");
  });

  // sorting task list item
  $(".ascending").on("click", function () {
    todoItem = $(".todo-item");
    $('.todo-item').css('animation', 'none')
    todoItem.sort(sort_li).appendTo(todoTaskListWrapper);
    function sort_li(a, b) {
      return ($(b).find('.todo-title').text().toLowerCase()) < ($(a).find('.todo-title').text().toLowerCase()) ? 1 : -1;
    }
  });

  // descending sorting
  $(".descending").on("click", function () {
    todoItem = $(".todo-item");
    $('.todo-item').css('animation', 'none')
    todoItem.sort(sort_li).appendTo(todoTaskListWrapper);
    function sort_li(a, b) {
      return ($(b).find('.todo-title').text().toLowerCase()) > ($(a).find('.todo-title').text().toLowerCase()) ? 1 : -1;
    }
  });
});

$(window).on("resize", function () {
  // remove show classes from sidebar and overlay if size is > 992
  if ($(window).width() > 992) {
    if (appContentOverlay.hasClass('show')) {
      sideBarLeft.removeClass('show');
      appContentOverlay.removeClass('show');
      todoNewTasksidebar.removeClass("show");
    }
  }
});
</script>
@endsection