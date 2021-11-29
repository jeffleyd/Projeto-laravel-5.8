@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('trip_i.tpe_export_fly') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('trip_i.tpe_export_fly_ps') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <form action="/trip/export" id="submitFilter" method="get">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (isset($manager)) { ?>
                                <div class="form-group">
                                    <label for="r_code">{{ __('project_i.pd_47') }}</label>
                                    <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" data-placeholder="{{ __('project_i.pd_48') }}" multiple>
                                        <option></option>
                                        <?php if (!empty($colab)) { ?>
                                            <?php foreach ($colab as $key) { ?>
                                                <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="finality">{{ __('trip_i.tpe_finality') }}</label>
                                <select class="form-control" id="finality" name="finality">
                                    <option value="" selected>{{ __('trip_i.tpe_all') }}</option>
                                    <option value="1">{{ __('trip_i.finality_1') }}</option>
                                    <option value="2">{{ __('trip_i.finality_2') }}</option>
                                    <option value="3">{{ __('trip_i.finality_3') }}</option>
                                    <option value="4">{{ __('trip_i.finality_4') }}</option>
                                    <option value="5">{{ __('trip_i.finality_5') }}</option>
                                    <option value="6">{{ __('trip_i.finality_6') }}</option>
                                    <option value="7">{{ __('trip_i.finality_7') }}</option>
                                    <option value="8">{{ __('trip_i.finality_8') }}</option>
                                    <option value="9">{{ __('trip_i.finality_9') }}</option>
                                    <option value="99">{{ __('trip_i.finality_99') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">{{ __('trip_i.tntp_date_begin') }}</label>
                                    <input class="form-control" type="text" name="start_date" id="start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">{{ __('trip_i.tntp_date_end') }}</label>
                                    <input class="form-control" type="text" name="end_date" id="end_date">
                                </div>
                            </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_country">{{ __('trip_i.tpe_c_origin') }}</label>
                                <select class="form-control" id="start_country" name="start_country">
                                    <option value="" selected>{{ __('trip_i.tpe_all') }}</option>
                                    <?php foreach ($country as $key) { ?>
                                        <option value="<?= $key->id ?>"><?= $key->name ?></option>
                                    <?php } ?> 
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="start_state">{{ __('trip_i.tpe_s_origin') }}</label>
                                <select class="form-control" id="start_state" name="start_state">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_country">{{ __('trip_i.tpe_c_destiny') }}</label>
                                <select class="form-control" id="end_country" name="end_country">
                                    <option value="" selected>{{ __('trip_i.tpe_all') }}</option>
                                    <?php foreach ($country as $key) { ?>
                                        <option value="<?= $key->id ?>"><?= $key->name ?></option>
                                    <?php } ?> 
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="end_state">{{ __('trip_i.tpe_s_destiny') }}</label>
                                <select class="form-control" id="end_state" name="end_state">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="hotel">{{ __('trip_i.tpe_has_hotel') }}</label>
                                <select class="form-control" id="hotel" name="hotel">
                                    <option value="" selected>{{ __('trip_i.tpe_any') }}</option>
                                    <option value="2">{{ __('trip_i.tpe_no') }}</option>
                                    <option value="1">{{ __('trip_i.tpe_yes') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status">{{ __('trip_i.tpe_status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="" selected>{{ __('trip_i.tpe_any') }}</option>
                                    <option value="1">{{ __('trip_i.tpe_status_1') }}</option>
                                    <option value="2">{{ __('trip_i.tpe_status_2') }}</option>
                                    <option value="3">{{ __('trip_i.tpe_status_3') }}</option>
                                    <option value="4">{{ __('trip_i.tpe_status_4') }}</option>
                                    <option value="5">{{ __('trip_i.tpe_status_5') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-square btn-outline-secondary" style="width: 100%;">{{ __('trip_i.tpe_send') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

    <script>
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        $('#start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        $("#start_date").val('');
        
        $('#end_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        $("#end_date").val('');

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mTrip").addClass('sidebar-group-active active');
            $("#mTripExport").addClass('active');
        }, 100);

    });
    </script>
@endsection