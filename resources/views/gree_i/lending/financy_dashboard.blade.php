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
                {{ __('layout_i.menu_lending_report') }}
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
            <form method="get" action="/financy/lending/dashboard">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-6 col-lg-9">
                        <label for="users-list-verified">{{ __('trip_i.tntp_collaborator') }}</label>
                        <fieldset class="form-group">
                            <select class="form-control js-select2" id="r_code" name="r_code" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                <option value=""></option>
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
                        <p class="text-muted mb-0 line-ellipsis">{{ __('lending_i.lr_1') }}</p>
                        <h2 class="mb-0">R$ <?= number_format($used_credit, 2, '.', '') ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card text-center">
                <div class="card-content">
                    <div class="card-body">
                        <p class="text-muted mb-0 line-ellipsis">{{ __('lending_i.lr_2') }} (<?= date('Y') ?>)</p>
                        <h2 class="mb-0">R$ <?= number_format($lending, 2, '.', '') ?></h2>
                    </div>
                </div>
            </div>
        </div>
       </div>
       <div class="row widgets-text-chart">
            <div class="col-lg-12">
                <div class="card widget-notification">
                    <div class="card-header border-bottom">
                        <h4 class="card-title d-flex align-items-center">{{ __('lending_i.lr_3') }}</h4>
                    </div>
                    <div class="card-content">
                        <div style="height: 340px;">
                            {!! $compareLendingYear->container() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
{{-- ChartScript --}}
@if($compareLendingYear)
{!! $compareLendingYear->script() !!}
@endif
<script>
$(document).ready(function () {
    $(".js-select2").select2({
        maximumSelectionLength: 1,
    });

    <?php if (!empty(Session::get('lendingdf_r_code'))) { ?>
        $('.js-select2').val(['<?= Session::get('lendingdf_r_code') ?>']).trigger('change');
    <?php } ?>

    setInterval(() => {
        $("#mAdmin").addClass('sidebar-group-active active');
        $("#mFinancyLending").addClass('sidebar-group-active active');
        $("#mFinancyLendingReport").addClass('active');
    }, 100);
    
});
</script>
@endsection