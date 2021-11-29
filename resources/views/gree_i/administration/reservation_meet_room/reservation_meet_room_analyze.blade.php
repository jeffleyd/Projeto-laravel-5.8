@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Reserva de Reunião</h5>
              <div class="breadcrumb-wrapper col-12">
                Aprove ou reprove solicitações.
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>

    <div class="content-body">
        
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
                                            <th>Colaborador</th>                                            
                                            <th>Departamento</th>                                            
                                            <th>Reservado para o dia</th>
                                            <th>Motivo da Reserva</th>
                                            <th>Analisar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reservationMeetRoomHasAnalyze as $key) { ?>
                                        <tr>                                            
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                            <td>{{config('gree.sector')[$key->users->sector_id] ?? ''}}</td>
                                            <td>{{date('Y/m/d', strtotime($key->start_time)).' ('}} {{date('H:i', strtotime($key->start_time))}} às {{date('H:i', strtotime($key->and_time)).' )'}}</td>
                                            <td>{{$key->reason->reason}}</td>
                                            <td id="action"><a onclick="analyze({{$key->id}}, {{$key->rtd_status['status']['validation']->first()->position}})" href="javascript:void(0)"><i class="bx bx-analyse"></i></a></td>                                            
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $reservationMeetRoomHasAnalyze->render(); ?>
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

@include('gree_i.misc.components.analyze.do_analyze.inputs', ['url' => '/administration/reservation/meetroom/analyze_do']);
@include('gree_i.misc.components.analyze.do_analyze.script');

<script>

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
            $("#mReservate").addClass('sidebar-group-active active');
            $("#rmrAnalyzeView").addClass('active');
        }, 100);

    });
    </script>
@endsection