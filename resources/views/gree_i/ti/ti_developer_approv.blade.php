@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">TI - Criação de software</h5>
                <div class="breadcrumb-wrapper col-12">
                    Aprovação de solicitações
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="content-body">
            <section class="users-list-wrapper">
                <div class="users-list-table">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="list-datatable" class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($backlog as $index => $key) { ?>
                                            <tr>
                                                <td>
                                                    <i class="bx bx-plus-circle cursor-pointer" id="seq_p{{$index+1}}" onclick="showDetails(this, {{$index+1}})"></i>
                                                    <i class="bx bx-minus-circle cursor-pointer" style="display: none" id="seq_m{{$index+1}}" onclick="hideDetails(this, {{$index+1}})"></i>
                                                </td>
                                                <td colspan="5" @if ($key->is_completed == 1) style="text-decoration: line-through;" @endif><?= $key->subject ?></td>
                                                <td>
                                                    <div class="dropleft text-center">
                                                      <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                      <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/ti/developer/edit/<?= $key->id ?>"><i class="bx bx-show-alt mr-1"></i> Análisar</a>
                                                        <a onclick="seeAnalyzes(<?= $key->id ?>);" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
                                                      </div>
                                                    </div>
                                                </td>                                          
                                            </tr>
                                            <tr style="display:none" class="seq_{{$index+1}} group">
                                                <td colspan="7">
                                                    <table class="table table-striped mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 14%"><b>ID:</b></td><td><?= $key->id ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 14%"><b>Setor:</b></td><td><?= sectors($key->sector_id) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 14%"><b>Solicitante:</b></td><td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 14%"><b>Criado em:</b></td><td><?= date('d-m-Y', strtotime($key->created_at)) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 14%"><b>Status:</b></td>
                                                                <td>
                                                                    @if ($key->is_completed == 1)
                                                                    <span class="badge badge-light-success">Concluído</span>
                                                                    @elseif ($key->is_cancelled == 1)
                                                                    <span class="badge badge-light-danger">Cancelado</span>
                                                                    @elseif ($key->has_suspended == 1)
                                                                    <span class="badge badge-light-warning">Suspenso</span>
                                                                    @elseif ($key->is_approv == 1)
                                                                    <span class="badge badge-light-success">Aprovado</span>
                                                                    @elseif ($key->is_repprov == 1)
                                                                    <span class="badge badge-light-danger">Reprovado</span>
                                                                    @else
                                                                    <span class="badge badge-light-info">Em análise</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>                                   
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-end">
                                            <?= $backlog->appends([
                                                'code' => Session::get('sacf_code'),
                                                'os' => Session::get('sacf_os'),
                                                'status' => Session::get('sacf_status'),
                                                ])->links(); ?>
                                        </ul>
                                    </nav>
                                </div>
                                <!-- datatable ends -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- users list ends -->
        </div>
    </div>
    <div class="customizer d-md-block text-center"><a class="customizer-close" href="#"><i class="bx bx-x"></i></a><div class="customizer-content p-2 ps ps--active-y">
        <h4 class="text-uppercase mb-0 histId"></h4>
        <small>Veja todo histórico de análises</small>
        <hr>
        <div class="theme-layouts">
          <div class="d-flex justify-content-start text-left p-1">
                <ul class="widget-timeline listitens" style="width: 100%;">
                </ul>
          </div>
        </div>
        <!-- Hide Scroll To Top Ends-->
      <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 754px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 590px;"></div></div></div>
    </div>
    <script>
        function seeAnalyzes(id) {
            block();
            $.ajax({
                type: "GET",
                url: "/misc/modeule/timeline/4/" + id,
                success: function (response) {
                    unblock();
                    if (response.success) {

                        if (response.history.length > 0) {
                            $(".histId").html('Histórico: #' + response.code);
                            var list = '';
                            console.log(response.history);
                            
                            for (let i = 0; i < response.history.length; i++) {
                                var obj = response.history[i];
                                var status;
                                if (obj.type == 1) {
                                    status = 'Aprovado';
                                } else if (obj.type == 2) {
                                    status = 'Reprovado';
                                } else if (obj.type == 3) {
                                    status = 'Suspenso';
                                } else if (obj.type == 4) {
                                    status = 'Aguardando aprovação';
                                }

                                list += '<li class="timeline-items timeline-icon-'+ obj.status +' active">';
                                if (obj.type != 4) {
                                    list += '<div class="timeline-time">'+ obj.created_at +'</div>';
                                } else {
                                    list += '<div class="timeline-time">--</div>';
                                }
                                

                                for (let index = 0; index < obj.users.length; index++) {
                                    var obj_users = obj.users[index];

                                    list += '<h6 class="timeline-title"><a target="_blank" href="/user/view/'+ obj_users.r_code +'">'+ obj_users.name +'</a></h6>';
                                    
                                }
                                
                                list += '<p class="timeline-text">'+ obj.sector +': <b>'+ status +'</b></p>';
                                if (obj.type != 4 && obj.message != null && obj.message != "") {
                                    list += '<div class="timeline-content">'+ obj.message +'</div>';
                                }
                                list += '</li>';
                                
                            }

                            $(".listitens").html(list);
                            $($(".customizer")).toggleClass('open');

                        } else {
                            error('Ainda não foi enviado para análise.');
                        }

                    } else {
                        error(response.msg);
                    }
                }
            });
        }
        function showDetails(element, index) {
            $(element).hide();
            $('#seq_m' + index).show();
            $('.seq_' + index).show();
        }
        function hideDetails(element, index) {
            $(element).hide();
            $('#seq_p' + index).show();
            $('.seq_' + index).hide();
        }
        $(document).ready(function () {
    
            setInterval(() => {
                $("#mTI").addClass('sidebar-group-active active');
                $("#mTIDeveloper").addClass('sidebar-group-active active');
                $("#mTIDeveloperApprov").addClass('active');
            }, 100);
            
        });
        </script>
@endsection