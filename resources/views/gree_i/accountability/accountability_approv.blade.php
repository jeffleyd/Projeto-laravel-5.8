@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Reembolso</h5>
              <div class="breadcrumb-wrapper col-12">
                Aprovar reembolsos
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="alert alert-primary alert-dismissible mb-2" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bxs-info-circle"></i>
            <span>
            Você precisará entrar nos detalhes do reembolso para poder aprovar, sua senha de acesso será necessário no processo!
            </span>
        </div>
    </div>
      
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/financy/refund/all" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-8">
                        <label for="users-list-verified">{{ __('trip_i.tntp_collaborator') }}</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                <option></option>
                                <?php foreach ($userall as $key) { ?>
                                    <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                <?php } ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">{{ __('trip_i.tntp_id') }}</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" id="id" name="id" value="<?= Session::get('refundf_id') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- users list start -->
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>Solicitante</th>
                                            <th>Total</th>
                                            <th>Empréstimo</th>
                                            <th>Status</th>
                                            <th>Criado em</th>
                                            <th>Atualizado em</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($refund as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td><a target="_blank" href="/user/view/<?= $key->request_r_code ?>"><?= getENameF($key->request_r_code); ?></a></td>
                                            <td>R$ <?= number_format($key->total, 2, ".", "") ?></td>
                                            <td>R$ <?= number_format($key->lending, 2, ".", "") ?></td>
                                            <td>
                                                <?php if ($key->has_suspended == 1) { ?>
                                                    <span class="badge badge-light-warning">SUSPENSO</span>
                                                <?php } else if ($key->mng_reprov == 0 and $key->mng_approv == 0) { ?>
                                                    <span class="badge badge-light-info">{{ __('lending_i.lt_13') }}</span>
                                                <?php } else if ($key->financy_reprov == 0 and $key->financy_approv == 0) { ?>
                                                    @if (!$key->financy_supervisor)
                                                    <span class="badge badge-light-info">físcal análisando</span>
                                                    @else
                                                    <span class="badge badge-light-info">Gerente financeiro análisando</span>
                                                    @endif
                                                <?php } else if ($key->pres_reprov == 0 and $key->pres_approv == 0) { ?>
                                                    <span class="badge badge-light-info">{{ __('lending_i.lt_15') }}</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= date('Y-m-d', strtotime($key->created_at)) ?></td>
                                            <td><?= date('Y-m-d', strtotime($key->updated_at)) ?></td>
                                            <td class="text-center">
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="/financy/refund/edit/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-show-alt mr-1"></i> Análisar</a>
                                                        <a onclick="seeAnalyzes(<?= $key->id ?>);" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $refund->appends(['r_code' => Session::get('refundf_r_code'), 'id' => Session::get('refundf_id')])->links(); ?>
                                    </ul>
                                </nav>
                            </div>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="card border-info text-center bg-transparent">
                <div class="card-content">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12 col-sm-12 d-flex justify-content-center">
                        <img src="/admin/app-assets/images/backgrounds/process_approv.png" alt="element 04" class="float-left mt-1 img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </section>
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
            url: "/misc/modeule/timeline/2/" + id,
            success: function (response) {
                unblock();
                if (response.success) {

                    if (response.history.length > 0) {
                        $(".histId").html(response.code);
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
    $(document).ready(function () {

        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('refundf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('refundf_r_code') ?>']).trigger('change');
        <?php } ?>
        
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });

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

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mFinancyRefund").addClass('sidebar-group-active active');
            $("#mFinancyRefundApprov").addClass('active');
        }, 100);

    });
    </script>
@endsection