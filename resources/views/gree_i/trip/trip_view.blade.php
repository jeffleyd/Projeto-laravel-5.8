@extends('gree_i.layout')

@section('content')
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_trip_view') }}</h5>
                  <div class="breadcrumb-wrapper col-12">
                    {{ __('layout_i.menu_trip_view_subtitle') }}
                  </div>
                </div>
              </div>
            </div>
          </div>
    
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- users list start -->
        <section class="users-list-wrapper">
			<div class="alert alert-danger alert-dismissible mb-2" role="alert">
				<div class="d-flex align-items-center">
					<i class="bx bx-error-circle"></i>
					<span>
						Para viagem com 7 dias ou menos, apenas a presidência poderá aprovar a solicitação. Caso queira prosseguir com aprovação, veja todas as solicitações e depois entre nos detalhes para poder aprovar.
					</span>
				</div>
			</div>
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <form action="/trip/analyze/update" id="rtd_analyze_form" method="POST">
                            <div class="table-responsive">
                                <table id="list-datatable" class="table table-transparent">
                                    <thead>
                                        <tr>
                                            <th class="text-center"></th>
                                            <th>{{ __('trip_i.tntp_id') }}</th>
                                            <th>{{ __('trip_i.tntp_colaborate') }}</th>
                                            <th>{{ __('trip_i.tntp_origin') }}</th>
                                            <th>{{ __('trip_i.tntp_destiny') }}</th>
                                            <th>{{ __('trip_i.tntp_situation') }}</th>
                                            <th>{{ __('trip_i.tmtp_actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trips as $key) { ?>
                                        <tr>
                                            <td class="text-center">
                                                <div class="checkbox"><input type="checkbox" class="checkbox-input" id="check_<?= $key->id ?>" name="check[]" value="<?= $key->id ?>">
                                                    <label for="check_<?= $key->id ?>"></label>
                                                </div>
                                            </td>
                                            <td><?= $key->id ?></td>
                                            <td>
                                                <a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a>
                                            </td>
                                            <td>
                                                <b><?= GetCountryName($key->origin_country) ?>:</b> <?= GetStateName($key->origin_country, $key->origin_state) ?>
                                                <br><b>{{ __('trip_i.tntp_going') }}</b> <?= date('Y-m-d', strtotime($key->origin_date)) ?>
                                            </td>
                                            <td>
                                                <b><?= GetCountryName($key->destiny_country) ?>:</b> <?= GetStateName($key->destiny_country, $key->destiny_state) ?>
                                                <br><b>{{ __('trip_i.tntp_arrived') }}</b> <?= date('Y-m-d', strtotime($key->destiny_date)) ?>
                                            </td>
                                            <td>
                                                <?php if ($key->is_approv == 1) { ?>
                                                    <span class="badge badge-light-success">{{ __('trip_i.tntp_status_1') }}</span>
                                                <?php } else if ($key->is_reprov == 1) { ?>
                                                    <span class="badge badge-light-danger">{{ __('trip_i.tntp_status_2') }}</span>
                                                <?php } else if ($key->has_analyze == 1) { ?>
                                                    <span class="badge badge-light-warning">{{ __('trip_i.tntp_status_3') }}</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-info">{{ __('trip_i.tntp_status_4') }}</span>
                                                <?php } ?>
                                            </td>
                                            <td>

                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/trip/review/<?= $key->id ?>"><i class="bx bx-detail mr-1"></i> {{ __('trip_i.action_details') }}</a>                                                   

                                                        <a onclick="rtd_analyzes(<?= $key->id ?>, 'App\\Model\\TripPlan');" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $trips->render(); ?>
                                    </ul>
                                </nav>
                            </div>
                            @include('gree_i.misc.components.analyze.do_analyze.inputs')
                            </form>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>

    <?php if (count($trips) > 0) { ?>
    <div class="mb-2" style="width: 390px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
            <button type="button" onclick="approval();" class="btn btn-success">
                {{ __('trip_i.tntp_approval_trip') }}
            </button>
    </div>
    <?php } ?>
</div>

@include('gree_i.misc.components.analyze.do_analyze.script')
@include('gree_i.misc.components.analyze.history.view')

<script>
    @include('gree_i.misc.components.analyze.history.script')

    function approval () {
        if ($(':checkbox[name="check[]"]:checked').length == 0) {    
            return $error('Selecione ao menos 1 rota para aprovar!');
        } else {
            analyze(1, 1);
        }
    }

    $(document).ready(function () {
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
            $("#mTrip").addClass('sidebar-group-active active');
            $("#mTripView").addClass('active');
        }, 100);
    });
</script>
@endsection