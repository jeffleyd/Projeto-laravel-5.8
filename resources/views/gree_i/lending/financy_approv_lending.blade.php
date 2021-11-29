@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_lending') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('layout_i.menu_lending_approv') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/financy/lending/approv" method="GET">
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
                            <input type="text" class="form-control" id="id" name="id" value="<?= Session::get('lendingf_id') ?>">
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
                                            <th>{{ __('lending_i.lt_1') }}</th>
                                            <th>{{ __('lending_i.lt_2') }}</th>
                                            <th>{{ __('lending_i.lt_3') }}</th>
                                            <th>{{ __('lending_i.lt_4') }}</th>
                                            <th>{{ __('lending_i.lt_5') }}</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lending as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                            <td><span data-toggle="popover" data-content="<?= $key->description ?>"><?= strWordCut($key->description, 25, "...") ?></span></td>
                                            <td>R$ <?= number_format($key->amount, 2, ".", "") ?></td>
                                            <td><?= date('Y-m-d', strtotime($key->created_at)) ?></td>
                                            <td>
                                                @if ($key->is_reprov == 1)
                                                    <span class="badge badge-light-danger">REPROVADO</span>
                                                @elseif ($key->is_paid == 1)
                                                    <span class="badge badge-light-success">PAGO</span>
                                                @elseif ($key->is_approv == 1)
                                                    <span class="badge badge-light-success">APROVADO</span>
                                                @elseif ($key->has_analyze == 1)
                                                    <span class="badge badge-light-info">EM ANÁLISE</span>
                                                @else
                                                    <span class="badge badge-light-secondary">NÃO ENVIADO</span>
                                                @endif
                                            </td>
                                            <td>
                                                <?php $attach = App\Model\FinancyLendingAttach::where('financy_lending_id', $key->id)->first(); ?>
                                                <?php if ($attach) { ?>
                                                    <?php if ($attach->is_module) { ?>
                                                        <a target="_blank" href="/module-view/<?= $attach->id_module ?>/1" href="javascript:void(0)"><i class="bx bxs-data mr-1"></i></a>
                                                    <?php } else { ?>
                                                        <a target="_blank" href="<?= $attach->url_file ?>" href="javascript:void(0)"><i class="bx bxs-file mr-1"></i></a>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="/financy/lending/approv/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-show-alt mr-1"></i> Análisar</a>
                                                        <a onclick="rtd_analyzes(<?= $key->id ?>, 'App\\Model\\FinancyLending');" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $lending->appends(['r_code' => Session::get('lendingf_r_code'), 'id' => Session::get('lendingf_id')])->links(); ?>
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

<div class="modal fade text-left" id="modal-bank" tabindex="-1" role="dialog" aria-labelledby="modal-bank" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modal-bank">{{ __('lending_i.lrn_27') }}</h3>
          <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body text-center">
            <div class="font-w600 mb-1"><span class="favo"></span></div>
            <div class="font-size-sm text-muted"><b>{{ __('lending_i.lrn_22') }}</b> <span class="agen"></span> </div>
            <div class="font-size-sm text-muted"><b>{{ __('lending_i.lrn_23') }}</b> <span class="acco"></span> </div>
            <div class="font-size-sm text-muted"><b>{{ __('lending_i.lrn_24') }}</b> <span class="bank"></span> </div>
            <div class="font-size-sm text-muted"><b>{{ __('lending_i.lrn_25') }}</b> <span class="cpf"></span> </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('lending_i.lrn_32') }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade text-left" id="modal-reprov" tabindex="-1" role="dialog" aria-labelledby="modal-reprov" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header bg-danger">
        <h5 class="modal-title white" id="modal-reprov"><?= __('lending_i.lt_24') ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>
        </div>
        <div class="modal-body">
            <div class="row">

                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="desc-reprov">{{ __('trip_i.trc_17') }}</label>
                        <textarea class="form-control" id="desc-reprov" name="desc-reprov" rows="6" placeholder="..."></textarea>
                    </fieldset>
                </div>

            </div>
        </div>
        <div class="modal-footer">
        <button type="button" id="submit-reprov" class="btn btn-danger ml-1" data-dismiss="modal">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">{{ __('trip_i.trc_8') }}</span>
        </button>
        </div>
    </div>
    </div>
</div>

<div class="customizer d-md-block text-center"><a class="customizer-close" href="#"><i class="bx bx-x"></i></a><div class="customizer-content p-2 ps ps--active-y">
    <h4 class="text-uppercase mb-0 histId"></h4>
    <small>Veja todo histórico de análises</small>
    <hr>
    <div class="theme-layouts">
        <div class="d-flex justify-content-start text-left p-1">
            <ul class="widget-timeline listitens" style="width: 100%;"></ul>
        </div>
    </div>
    <!-- Hide Scroll To Top Ends-->
  <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 754px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 590px;"></div></div></div>
</div>

@include('gree_i.misc.components.analyze.history.view')

<script>

    @include('gree_i.misc.components.analyze.history.script')

    function seeAnalyzes(id) {
        block();
        $.ajax({
            type: "GET",
            url: "/misc/modeule/timeline/1/" + id,
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
    function account(agency, account, bank, identity, name) {
        $(".favo").html(name);
        $(".agen").html(agency);
        $(".acco").html(account);
        $(".bank").html(bank);
        $(".cpf").html(identity);
        $("#modal-bank").modal();
    }
    function Approv(index) {
            id = index;
            Swal.fire({
                title: '<?= __('lending_i.lt_20') ?>',
                text: "<?= __('lending_i.lt_22') ?>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
                cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        block();
                        window.location.href = "/financy/lending/analyze/"+ index +"/1";
                    }
                })
    }
    function Repprov(index) {
        id = index;
        Swal.fire({
                title: '<?= __('lending_i.lt_21') ?>',
                text: "<?= __('lending_i.lt_22') ?>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
                cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        $('#modal-reprov').modal();
                    }
                })
    }
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        <?php if (!empty(Session::get('lendingf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('lendingf_r_code') ?>']).trigger('change');
        <?php } ?>

        $("#submit-reprov").click(function (e) { 
            if ($('#desc-reprov').val() != "") {
                $('#modal-reprov').modal('toggle');
                block();
                window.location.href = "/financy/lending/analyze/"+ id +"/2?description=" + $('#desc-reprov').val();
            } else {
                error('<?= __('lending_i.lt_23') ?>');
            }
            
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
            $("#mFinancyLending").addClass('sidebar-group-active active');
            $("#mFinancyLendingApprov").addClass('active');
        }, 100);

    });
    </script>
@endsection