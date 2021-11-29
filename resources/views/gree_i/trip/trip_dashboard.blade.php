@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_trip_dashboard') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('layout_i.menu_trip_dashboard_subtitle') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <?php if (Session::get('r_code') == '0004' or Session::get('r_code') == '0005' or Session::get('r_code') == '1842' ) { ?>
        <div class="users-list-filter px-1">
            <form method="get" action="/trip/dashboard">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-6 col-lg-9">
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
                    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('trip_i.td_filter_data') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <?php } ?>
       <div class="row">
        <div class="col-xl-6">
            <div class="card text-center">
                <div class="card-content">
                    <div class="card-body">
                        <p class="text-muted mb-0 line-ellipsis"><?= date('Y') - 1 ?></p>
                        <h2 class="mb-0">R$ <?= number_format($amount_last, 2, '.', '') ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card text-center">
                <div class="card-content">
                    <div class="card-body">
                        <p class="text-muted mb-0 line-ellipsis"><?= date('Y') ?></p>
                        <h2 class="mb-0">R$ <?= number_format($amount_actual, 2, '.', '') ?></h2>
                    </div>
                </div>
            </div>
        </div>
       </div>
       <div class="row widgets-text-chart">
            <div class="col-lg-12">
                <div class="card widget-notification">
                    <div class="card-header border-bottom">
                        <h4 class="card-title d-flex align-items-center">{{ __('trip_i.td_compare_amount_year') }}</h4>
                    </div>
                    <div class="card-content">
                        <div style="height: 340px;">
                            {!! $compareFlightYear->container() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row widgets-text-chart">
            <div class="col-lg-6">
                <div class="card widget-notification">
                    <div class="card-header border-bottom">
                        <h4 class="card-title d-flex align-items-center">{{ __('trip_i.td_compare_amount_finality') }}</h4>
                    </div>
                    <div class="card-content">
                        <div style="height: 340px;">
                            {!! $finalityAmountYear->container() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 p-actual">
                <div class="card widget-notification">
                    <div class="card-header border-bottom">
                        <h4 class="card-title d-flex align-items-center">{{ __('trip_i.td_flight_finality') }} <?= date('Y') ?></h4>
                        <div class="heading-elements">
                            <button type="button" class="btn btn-sm btn-light-primary p-actual-click">{{ __('trip_i.td_change_year') }}</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <div style="height: 340px;">
                            {!! $finalityAmountCount->container() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 p-last" style="display:none">
                <div class="card widget-notification">
                    <div class="card-header border-bottom">
                        <h4 class="card-title d-flex align-items-center">{{ __('trip_i.td_flight_finality') }} <?= date('Y') - 1 ?></h4>
                        <div class="heading-elements">
                            <button type="button" class="btn btn-sm btn-light-primary p-last-click">{{ __('trip_i.td_change_year') }}</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <div style="height: 340px;">
                            {!! $finalityAmountCountL->container() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
{{-- ChartScript --}}
@if($compareFlightYear)
{!! $compareFlightYear->script() !!}
@endif
@if($finalityAmountYear)
{!! $finalityAmountYear->script() !!}
@endif
@if($finalityAmountCount)
{!! $finalityAmountCount->script() !!}
@endif
@if($finalityAmountCountL)
{!! $finalityAmountCountL->script() !!}
@endif
<script>
$(document).ready(function () {
    $(".js-select2").select2({
        maximumSelectionLength: 1,
    });

    <?php if (!empty(Session::get('tripdf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('tripdf_r_code') ?>']).trigger('change');
    <?php } ?>

    $(".p-actual-click").click(function (e) { 
        $(".p-last").show();
        $(".p-actual").hide();
        
    });
    $(".p-last-click").click(function (e) { 
        $(".p-actual").show();
        $(".p-last").hide();
        
    });

    setInterval(() => {
        $("#mAdmin").addClass('sidebar-group-active active');
        $("#mTrip").addClass('sidebar-group-active active');
        $("#mTripDashboard").addClass('active');
    }, 100);
    
});
</script>
@endsection