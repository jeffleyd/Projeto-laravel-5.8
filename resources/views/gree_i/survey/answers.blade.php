@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Pesquisas</h5>
              <div class="breadcrumb-wrapper col-12">
                Respostas dos colaboradores
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/survey/answers" id="searchTrip" method="GET">

                
                <div class="row border rounded py-2 mb-2">
                    
                    <div class="col-12 col-sm-6 col-lg-6">
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
                    <div class="col-12 col-sm-6 col-lg-6">
                        <label for="users-list-verified">Pesquisa</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="survey_id" name="survey_id" style="width: 100%;" data-placeholder="Pesquisa" multiple>
                                <option></option>
                                <?php foreach ($surveys as $key) { ?>
                                    <option value="<?= $key->id ?>"><?= $key->name?></option>
                                <?php } ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label for="users-list-verified">Data inicial</label>
                        <fieldset class="form-group">
                            <input type="text" class="js-flatpickr form-control bg-white js-flatpickr-enabled flatpickr-input date_pick" name="start_date" placeholder="d-m-Y" >
                        </fieldset>
                    </div>
					<div class="col-12 col-lg-4">
                        <label for="users-list-verified">Data final</label>
                        <fieldset class="form-group">
                            <input type="text" class="js-flatpickr form-control bg-white js-flatpickr-enabled flatpickr-input date_pick" name="end_date" placeholder="d-m-Y" >
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
					<div class="col-12 col-sm-2 d-flex align-items-center">
                        <button type="submit" name="export" value="1" class="btn btn-success btn-block glow users-list-clear mb-0">Exportar</button>
                    </div>
                </div>
                
            </form>
        </div>
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
                                            <th>Usuário</th>
                                            <th>Pesquisa</th>
                                            <th>Respondido em</th>
                                            <th>Ver resposta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($answer as $key) { ?>
                                        <tr>
                                            <td>
												@if ($key->users)
												<a target="_blank" href="/user/view/<?= $key->users->r_code ?>"><?= $key->users->short_name; ?></a>
												@else
												Anônimo
												@endif
											</td>
                                            <td>
                                                <?= strip_tags($key->survey->name) ?>
                                            </td>
                                            <td><?= date('d-m-Y H:i', strtotime($key->created_at)) ?></td>
                                            <td><a target="_blank" href="/survey/answers/view/<?= $key->id ?>" href="javascript:void(0)"><i class="bx bx-show-alt mr-1"></i></a></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $answer->appends([
											'r_code' => Session::get('surveyf_r_code'),
											'survey_id' => Session::get('surveyf_survey_id'),
											'start_date' => Session::get('surveyf_start_date'),
											'end_date' => Session::get('surveyf_end_date')
											])->links(); 
										?>
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

    <script>
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        
       
        $('.date_pick').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY',
                 cancelLabel: 'Clear'
            },
            maxDate: "<?= date('d-m-Y') ?>",
        });
        


        <?php if (!empty(Session::get('surveyf_r_code'))) { ?>
        $('#r_code').val(['<?= Session::get('surveyf_r_code') ?>']).trigger('change');
        <?php } ?>
        
        <?php if (!empty(Session::get('surveyf_survey_id'))) { ?>
        $('#survey_id').val(['<?= Session::get('surveyf_survey_id') ?>']).trigger('change');
        <?php } ?>
        
        $('.date_pick').val(null).trigger('change');

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
            $("#msurvey").addClass('sidebar-group-active active');
            $("#msurveyAnswer").addClass('active');
        }, 100);

    });
    </script>
@endsection