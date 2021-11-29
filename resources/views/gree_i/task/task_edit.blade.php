@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" href="/js/plugins/jquery-tags-input/jquery.tagsinput.min.css">
<style>
div.tagsinput span.tag {
    border: 1px solid #82300f !important;
    background: #e65012 !important;
    color: #f6f6f6 !important;
    padding: 2px !important;
    font-weight: 100;
}

div.tagsinput span.tag a {
    color: #ffffff !important;
}
</style>
<div class="content-overlay"></div>
<form action="/task_do" method="post" enctype="multipart/form-data">
<input type="hidden" name="data_res" id="data_res">
<input type="hidden" name="data_copy" id="data_copy">
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('project_i.pd_title') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('project_i.pd_subtitle') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="rcodes">{{ __('project_i.pd_47') }}</label>
                                <select class="js-select2 form-control" id="rcodes" name="rcodes" style="width: 100%;" data-placeholder="{{ __('project_i.pd_48') }}" multiple>
                                    <option></option>
                                    <?php if (!empty($colab)) { ?>
                                        <?php foreach ($colab as $key) { ?>
                                            <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <div class="form-text text-muted">{{ __('project_i.pd_49') }}</div>
                            </div>
                            <label for="ecopy">{{ __('project_i.pd_50') }}</label>
                            <input type="text" class="js-tags-input form-control" data-height="34px" id="ecopy" name="ecopy">
                            <div class="form-text text-muted mb-1">{{ __('project_i.pd_51') }}</div>
                            <div class="form-group">
                                <label for="start_date">{{ __('project_i.pd_2') }}</label>
                            <input type="text" class="form-control" id="start_date" value="<?php if (!empty($start_date)) { ?><?= date('Y-m-d', strtotime($start_date)) ?><?php } ?>" name="start_date">
                                <div class="form-text text-muted">{{ __('project_i.pd_3') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="end_date">{{ __('project_i.pd_4') }}</label>
                                <input type="text" class="form-control" id="end_date" value="<?php if (!empty($end_date)) { ?><?= date('Y-m-d', strtotime($end_date)) ?><?php } ?>" name="end_date">
                                <div class="form-text text-muted">{{ __('project_i.pd_5') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="title">{{ __('project_i.pd_7') }}</label>
                                <input type="text" class="form-control" id="title" value="<?= $title ?>" name="title" placeholder="...">
                                <div class="form-text text-muted">{{ __('project_i.pd_8') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="description">{{ __('project_i.pd_9') }}</label>
                                <textarea id="description" name="description"><?= $description ?></textarea>
                                <div class="form-text text-muted">{{ __('project_i.pd_10') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="attach">{{ __('project_i.pd_12') }}</label>
                                <input type="file" class="form-control" name="attach" id="attach">
                                <div class="form-text text-muted">{{ __('project_i.pd_13') }}</div>
                            </div>
                            <?php if (!empty($attach)) { ?>
                            <div class="form-group">
                                <a href="<?= $attach ?>" target="_blank" class="text-primary font-weight-bold">{{ __('project_i.pd_14') }}</a>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" id="sendProject" class="btn btn-primary" style="width:100%;"><?php if ($id == 0) { ?>{{ __('project_i.pd_27') }}<?php } else { ?>{{ __('project_i.pd_28') }}<?php } ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

    
    <script>
    var arrayCopy = new Array();
    function onChangeTags() {
        arrayCopy = new Array();
        var data = $('.js-tags-input').tagsInput()[0]['value'];
        var str_array = data.split(',');

        for(var i = 0; i < str_array.length; i++) {
            // Trim the excess whitespace.
            str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
            // Add additional code here, such as:
            arrayCopy.push({
                'email': str_array[i],
            });
        }

        $("#data_copy").val(JSON.stringify(arrayCopy));
    }
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        $('.js-tags-input').tagsInput({
            'height':'34px',
            'width':'100%',
            'defaultText':'',
            'onChange' : onChangeTags
        });
        CKEDITOR.replace( 'description' );
        <?php if (!empty($res)) { ?>
            $('.js-select2').val([
            <?php foreach ($res as $key) { ?>
                '<?= $key->r_code ?>',
            <?php } ?>
            ]).trigger('change');
        <?php } ?>

        <?php if (!empty($copy)) { ?>
            <?php foreach ($copy as $key) { ?>
                $('.js-tags-input').addTag('<?= $key->email ?>');
            <?php } ?>
        <?php } ?>
        
        $("#data_copy").val(JSON.stringify(arrayCopy));
        $("#sendProject").click(function (e) { 
            $("#data_res").val(JSON.stringify($(".js-select2").select2('data')));
            if ($(".js-select2").select2('data').length == 0) {

                error('Você precisa escolher pelo menos 1 responsável.');
                e.preventDefault();
                return;
            } else if ($("#start_date").val() == "") {

                error('<?= __('project_i.pd_36') ?>');
                e.preventDefault();
                return;
            } else if ($("#end_date").val() == "") {

                error('<?= __('project_i.pd_37') ?>');
                e.preventDefault();
                return;
            } else if ($("#title").val() == "") {

                error('<?= __('project_i.pd_39') ?>');
                e.preventDefault();
                return;
            } else if (CKEDITOR.instances.description.getData() == "") {

                error('<?= __('project_i.pd_40') ?>');
                e.preventDefault();
                return;
            }

            
        });

        <?php if ($id == 0) { ?>

            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#mTask").addClass('sidebar-group-active active');
                $("#mTaskNew").addClass('active');
            }, 100);
            
        <?php } else { ?>

            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#mTask").addClass('sidebar-group-active active');
            }, 100);

        <?php } ?>

        $('#start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        
        $('#end_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

    });
    </script>
@endsection