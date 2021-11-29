@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('project_i.ep_1') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('project_i.ep_2') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <form action="/task/download/export" id="submitFilter" method="get">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (isset($manager)) { ?>
                                <div class="form-group">
                                    <label for="r_code">{{ __('project_i.pd_47') }}</label>
                                    <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" data-placeholder="Pesquise o nome ou matricula..." multiple>
                                        <option></option>
                                        <?php if (!empty($colab)) { ?>
                                            <?php foreach ($colab as $key) { ?>
                                                <?php $name = App\Model\Users::where('r_code', $key->user_r_code)->first(); ?>
                                                @if ($name)
                                                <option value="<?= $key->user_r_code ?>"><?= $name->first_name ." ". $name->last_name ?> (<?= $key->user_r_code ?>)</option>
                                                @endif
                                            <?php } ?>
                                        <?php } ?>    
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status">{{ __('trip_i.tpe_status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value=""></option>
                                    <option value="1">{{ __('project_i.ee_11') }}</option>
                                    <option value="2">{{ __('project_i.ee_14') }}</option>
                                    <option value="3">{{ __('project_i.ee_13') }}</option>
                                    <option value="4">{{ __('project_i.ee_15') }}</option>
                                    <option value="5">{{ __('project_i.ee_16') }}</option>
                                    <option value="6">{{ __('project_i.ee_18') }}</option>
                                    <option value="7">{{ __('project_i.ee_17') }}</option>
                                    <option value="8">{{ __('project_i.ee_12') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-square btn-outline-secondary" style="width: 100%;">{{ __('project_i.ep_14') }}</button>
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

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mTask").addClass('sidebar-group-active active');
            $("#mTaskExport").addClass('active');
        }, 100);

    });
    </script>
@endsection