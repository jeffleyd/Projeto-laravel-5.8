@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/forms/wizard.min.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/shepherd-theme-default.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/tour/tour.min.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_trip_new') }}</h5>
                    <div class="breadcrumb-wrapper col-12">
                        {{ __('layout_i.menu_trip_new_subtitle') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <!-- Form wizard with icon tabs section start -->
        <section id="icon-tabs">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            
                        </div>
                        <div class="card-content mt-2">
                            <div class="card-body">

                                <ul class="nav nav-tabs nav-justified mb-3" id="myTab2" role="tablist">
                                    <li class="nav-item current">
                                      <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab" aria-controls="home-just" aria-selected="true">
                                        {{ __('trip_i.tn_trip_tab_1') }}
                                      </a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link" id="profile-tab-justified" data-toggle="tab" href="#profile-just" role="tab" aria-controls="profile-just" aria-selected="false">
                                        {{ __('trip_i.tn_trip_tab_3') }}
                                      </a>
                                    </li>
                                  </ul>

                                  <div class="tab-content pt-1">
                                    <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
                                        <form action="/trip/new/do" method="POST" class="wizard-horizontal" id="PlanTrip">
                                            <input type="hidden" name="data_input" id="data_input">
                                            <!-- Step 1 -->
                                            <h6>
                                                <i class="step-icon"></i>
                                                <span class="fonticon-wrap">
                                                    <i id="pen" class="livicon-evo" data-options="name:pen.svg; size: 50px; style:lines; strokeColor:#adb5bd;"></i>
                                                </span>
                                            </h6>
                                            <!-- Step 1 end-->
                                            <!-- body content step 1 -->
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="py-50">{{ __('trip_i.tn_tab_info') }}</h6>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="type">{{ __('trip_i.tn_finality') }}</label>
                                                            <select class="form-control" id="type" name="type">
                                                                <option value="0">{{ __('trip_i.select') }}</option>
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
                                                    <div class="col-sm-12">
                                                        <div class="form-group other" style="display:none;">
                                                            <label for="other">{{ __('trip_i.tn_finality_other') }}</label>
                                                            <input type="text" class="form-control" id="other" name="other" placeholder="..." />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="goal">{{ __('trip_i.tn_goal') }}</label>
                                                            <textarea class="form-control" id="goal" name="goal" rows="6" placeholder="..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content step 1 end-->
                                            <!-- Step 2 -->
                                            <h6>
                                                <i class="step-icon"></i>
                                                <span class="fonticon-wrap">
                                                    <i id="plane" class="livicon-evo" data-options="name:paper-plane.svg; size: 50px; style:lines; strokeColor:#adb5bd;"></i>
                                                </span>
                                            </h6>
                                            <!-- Step 2 end-->
                                            <!-- body content of step 2 -->
                                            <fieldset>
                                                <div class="row mb-1">
                                                    <div class="col-6" id="mobile-c6-c12">
                                                        <div class="border-element">
                                                        <h5 class="mt-30 font-w300">{{ __('trip_i.tn_fly_go') }}</i></h5>
                                                        <div class="form-group row">
                                                            <div class="col-6">
                                                                <label for="origin">{{ __('trip_i.tn_fly_date_origin') }}</label>
                                                                <input type="text" class="js-flatpickr form-control bg-white js-flatpickr-enabled flatpickr-input" id="origin" name="origin" placeholder="Y-m-d" readonly="readonly">
                                                            </div>
                                                            
                                                            <div class="col-6">
                                                                <label for="period">{{ __('trip_i.tn_fly_Period_origin') }}</label>
                                                                <select class="form-control" id="period" name="period">
                                                                    <option value="0">{{ __('trip_i.select') }}</option>
                                                                    <option value="1">{{ __('trip_i.period_1') }}</option>
                                                                    <option value="2">{{ __('trip_i.period_2') }}</option>
                                                                    <option value="3">{{ __('trip_i.period_3') }}</option>
                                                                    <option value="4">{{ __('trip_i.period_4') }}</option>
                                                                </select>
                                                            </div>
                                
                                                            <div class="col-6 mt-20">
                                                                <label for="countryOrigin">{{ __('trip_i.tn_fly_country_origin') }}</label>
                                                                <select class="form-control" id="countryOrigin" name="countryOrigin">
                                                                    <option value="0">{{ __('trip_i.select') }}</option>
                                                                    <?php foreach ($country as $key) { ?>
                                                                        <option value="<?= $key->id ?>"><?= $key->name ?></option>
                                                                    <?php } ?> 
                                                                </select>
                                                            </div>
                                
                                                            <div class="col-6 mt-20">
                                                                <label for="stateOrigin">{{ __('trip_i.tn_fly_state_origin') }}</label>
                                                                <select class="form-control" id="stateOrigin" name="stateOrigin">
                                                                    
                                                                </select>
                                                            </div>
                                
                                                            <div class="col-12 mt-20">
                                                                <label for="cityOrigin">{{ __('trip_i.tn_fly_city_origin') }}</label>
                                                                <input type="text" class="form-control" id="cityOrigin" name="cityOrigin" />
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                
                                                    <div class="col-6" id="mobile-c6-c12">
                                                        <div class="border-element">
                                                        <h5 class="mt-30 font-w300">{{ __('trip_i.tn_fly_back') }}</i></h5>
                                                        <div class="form-group row">
                                                            <div class="col-6">
                                                                <label for="destiny">{{ __('trip_i.tn_fly_date_origin') }}</label>
                                                                <input type="text" class="js-flatpickr form-control bg-white js-flatpickr-enabled flatpickr-input" id="destiny" name="destiny" placeholder="Y-m-d" readonly="readonly">
                                                            </div>
                                                            
                                                            <div class="col-6">
                                                                <label for="period">{{ __('trip_i.tn_fly_Period_origin') }}</label>
                                                                <select class="form-control" id="period2" name="period2">
                                                                    <option value="0">{{ __('trip_i.select') }}</option>
                                                                    <option value="1">{{ __('trip_i.period_1') }}</option>
                                                                    <option value="2">{{ __('trip_i.period_2') }}</option>
                                                                    <option value="3">{{ __('trip_i.period_3') }}</option>
                                                                    <option value="4">{{ __('trip_i.period_4') }}</option>
                                                                </select>
                                                            </div>
                                
                                                            <div class="col-6 mt-20">
                                                                <label for="countryDestiny">{{ __('trip_i.tn_fly_country_origin') }}</label>
                                                                <select class="form-control" id="countryDestiny" name="countryDestiny">
                                                                    <option value="0">{{ __('trip_i.select') }}</option>
                                                                    <?php foreach ($country as $key) { ?>
                                                                        <option value="<?= $key->id ?>"><?= $key->name ?></option>
                                                                    <?php } ?> 
                                                                </select>
                                                            </div>
                                
                                                            <div class="col-6 mt-20">
                                                                <label for="stateDestiny">{{ __('trip_i.tn_fly_state_origin') }}</label>
                                                                <select class="form-control" id="stateDestiny" name="stateDestiny">
                                                                    
                                                                </select>
                                                            </div>
                                
                                                            <div class="col-12 mt-20">
                                                                <label for="cityDestiny">{{ __('trip_i.tn_fly_city_origin') }}</label>
                                                                <input type="text" class="form-control" id="cityDestiny" name="cityDestiny" />
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-20">
                                                        <label for="dispatch">{{ __('trip_i.tn_dispatch') }}</label>
                                                        <input type="number" class="form-control" min="0" max="10" id="dispatch" name="dispatch" value="1" />
                                                    </div>
                                                    <div class="col-12 mt-20 dispatch-info animated zoomIn" style="display:none">
                                                        <label for="dispatch-info">{{ __('trip_i.tn_dispatch_add') }}</label>
                                                        <input type="text" class="form-control" id="dispatch-info" name="dispatch-info" />
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content of step 2 end-->
                                            <!-- Step 3 -->
                                            <h6>
                                                <i class="step-icon"></i>
                                                <span class="fonticon-wrap">
                                                    <i id="building" class="livicon-evo" data-options="name:building.svg; size: 50px; style:lines; strokeColor:#adb5bd;"></i>
                                                </span>
                                            </h6>
                                            <!-- Step 3 end-->
                                            <!-- body content of Step 3 -->
                                            <fieldset>
                                                <div class="row mb-1">
                                                    <div class="col-6" id="mobile-c6-c12">
                                                        <div class="border-element">
                                                            <h5 class="mt-30 font-w300">{{ __('trip_i.tn_hotel_enter') }}</h5>
                                                            <div class="form-group row">
                                                                
                                                                <div class="col-6">
                                                                    <label for="origin">{{ __('trip_i.tn_hotel_enter_date') }}</label>
                                                                    <input type="text" class="js-flatpickr form-control bg-white js-flatpickr-enabled flatpickr-input" id="originHotel" name="originHotel" placeholder="Y-m-d" readonly="readonly">
                                                                </div>
                                                                
                                                                <div class="col-6">
                                                                    <label for="checkoutHotel">Checkout</label>
                                                                    <select class="form-control" id="checkoutHotel" name="checkoutHotel">
                                                                        <option value="0">{{ __('trip_i.select') }}</option>
                                                                        <option value="1">Checkout normal</option>
                                                                        <option value="2">Checkout later</option>
                                                                    </select>
                                                                </div>
                                
                                                                <div class="col-6 mt-20">
                                                                    <label for="countryOriginHotel">{{ __('trip_i.tn_hotel_enter_country') }}</label>
                                                                    <select class="form-control" id="countryOriginHotel" name="countryOriginHotel">
                                                                        <option value="0">{{ __('trip_i.select') }}</option>
                                                                        <?php foreach ($country as $key) { ?>
                                                                            <option value="<?= $key->id ?>"><?= $key->name ?></option>
                                                                        <?php } ?> 
                                                                    </select>
                                                                </div>
                                
                                                                <div class="col-6 mt-20">
                                                                    <label for="stateOriginHotel">{{ __('trip_i.tn_hotel_enter_state') }}</label>
                                                                    <select class="form-control" id="stateOriginHotel" name="stateOriginHotel">
                                                                        
                                                                    </select>
                                                                </div>
                                
                                                                <div class="col-12 mt-20">
                                                                    <label for="cityOriginHotel">{{ __('trip_i.tn_hotel_enter_city') }}</label>
                                                                    <input type="text" class="form-control" id="cityOriginHotel" name="cityOriginHotel" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6" id="mobile-c6-c12">
                                                        <div class="border-element">
                                                        <h5 class="mt-30 font-w300">{{ __('trip_i.tn_hotel_exit') }}</i></h5>
                                                            <div class="form-group row">
                                                                <div class="col-12">
                                                                    <label for="back">{{ __('trip_i.tn_hotel_exit_date') }}</label>
                                                                    <input type="text" class="js-flatpickr form-control bg-white js-flatpickr-enabled flatpickr-input" id="backHotel" name="backHotel" placeholder="Y-m-d" readonly="readonly">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-12">
                                                                    <label>{{ __('trip_i.tn_has_hotel') }} <small>({{ __('trip_i.tn_has_hotel_txt') }})</small></label>
                                                                    <br>
                                                                    <div class="custom-control mt-1 custom-switch custom-switch-shadow custom-control-inline mb-1">
                                                                        <input type="checkbox" class="custom-control-input" id="r_hotel">
                                                                        <label class="custom-control-label" for="r_hotel">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                
                                                    <div class="col-12 mt-20">
                                                        <label for="addressHotel">{{ __('trip_i.tn_hotel_address') }}<small>({{ __('trip_i.optional') }})</small></label>
                                                        <input type="text" class="form-control" id="addressHotel" name="addressHotel" />
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content of Step 3 end-->
                                            <!-- Step 4 -->
                                            <h6>
                                                <i class="step-icon"></i>
                                                <span class="fonticon-wrap">
                                                    <i id="user" class="livicon-evo" data-options="name:user.svg; size: 50px; style:lines; strokeColor:#adb5bd;"></i>
                                                </span>
                                            </h6>
                                            <!-- Step 4 end-->
                                            <!-- body content of Step 4 -->
                                            <fieldset>
                                                <div class="row mb-1">
                                                    <p>{{ __('trip_i.tn_trip_ps_people_delete') }}</p>
                                                    <div class="col-12">
                                                        <h6 class="py-50">{{ __('trip_i.tn_fly_optional_peoples') }}</h6>
                                                    </div>
                                                    <div class="col-5" id="mobile-c5-c12">
                                                        <label for="r_code">{{ __('trip_i.tn_fly_add_registration') }}</label>
                                                        <input type="number" class="form-control" id="r_code" name="r_code" placeholder="1000">
                                                    </div>
                        
                                                    <div class="col-5" id="mobile-c5-c12">
                                                        <label for="name_full">{{ __('trip_i.tn_fly_add_name_full') }}</label>
                                                        <input type="text" class="form-control" id="name_full" name="name_full" placeholder="joão Rodrigues da silva">
                                                    </div>
                        
                                                    <div class="col-2">
                                                    <button type="button" class="btn btn-rounded btn-outline-primary addpeople" style="margin-top:25px;">{{ __('trip_i.tn_fly_add_add') }}</button>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-1 mb-1">
                                                    <div class="row" id="peoples">
                                            
                            
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content of Step 4 end-->
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">
                                        <div class="table-responsive">
                                            <table id="list-datatable" class="table table-transparent">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" style="width: 100px;">#</th>
                                                        <th>{{ __('trip_i.tntp_origin') }}</th>
                                                        <th>{{ __('trip_i.tntp_destiny') }}</th>
                                                        <th>{{ __('trip_i.tntp_hotel') }}</th>
                                                        <th>{{ __('trip_i.tntp_dispatch') }}</th>
                                                        <th>{{ __('trip_i.tntp_peoples') }}</th>
                                                        <th class="text-center">{{ __('trip_i.tntp_actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="TablePlan">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                  </div>

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Form wizard with number tabs section end -->

    </div>
</div>

  <button id="submitTripCancel" onclick="cancelPlan();" class="btn btn-danger trip-btn-action glow px-1" style="display:none"><?= __('layout_i.btn_cancel') ?></button>
  <button id="FinishPlan" type="submit" class="btn btn-success trip-btn-action glow px-1" style="display:none"><?= __('trip_i.tn_finish') ?></button>
<script src="/admin/app-assets/vendors/js/extensions/jquery.steps.js"></script>
<div class="customizer d-none d-md-block" id="ActiveTraine">
<a class="customizer-toggle" href="#"><i class="bx bx-question-mark white"></i></a>
</div>

<script src="/admin/app-assets/vendors/js/extensions/shepherd.min.js"></script>
<script src="/js/StepsTour.js"></script>
<script>
// tour initialize
var tour = new Shepherd.Tour({
    classes: 'shadow-md bg-purple-dark',
    scrollTo: true
});

AddSteps(1, '<?= __('training_i.page_10') ?>', '#home-tab-justified bottom');
AddSteps(2, '<?= __('training_i.page_11') ?>', '#profile-tab-justified bottom');

    var l_state_origin, l_state_destiny, l_state_hotel;
    var last_date, last_period, last_country, last_state, last_city;
    var datestart, dateend;
    var ArrayPeoples = new Array();
    var ArrayPlan = new Array();
    var route_id = null;
    function Delete(index) {
        Swal.fire({
                    title: '<?= __('trip_i.tn_fly_toast_title') ?>',
                    text: "<?= __('trip_i.tn_fly_toast_description') ?>",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= __('trip_i.tn_fly_toast_yes') ?>',
                    cancelButtonText: '<?= __('trip_i.tn_fly_toast_no') ?>',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            ArrayPeoples.splice(index, 1);
                            ReloadPeoples();
                        }
                    })

    }

    //function copyPlan(index) {
    //  ArrayPlan.push(ArrayPlan[]);
    //  ReloadTablePlan();
    //  CleanInput();

    // }

    function deletePlan(index) {
        Swal.fire({
                    title: '<?= __('trip_i.tn_fly_toast_title') ?>',
                    text: "<?= __('trip_i.tn_fly_toast_description_plan') ?>",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= __('trip_i.tn_fly_toast_yes') ?>',
                    cancelButtonText: '<?= __('trip_i.tn_fly_toast_no') ?>',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            ArrayPlan.splice(index, 1);
                            ReloadTablePlan();
                            if (ArrayPlan.length > 0) {
                                $("#FinishPlan").show();
                            } else {
                                $("#FinishPlan").hide();
                            }
                        }
                    })

    }

    function editPlan(index) {
      route_id = index;
      $('#home-tab-justified').tab('show');
      var arrayObj = ArrayPlan[index];

      $(".actions [href='#finish']").html('<?= __('trip_i.tn_route_btn_finish_upd') ?>');
      $("#home-tab-justified").html('<?= __('trip_i.tn_trip_tab_2') ?>');
      
      $("#submitTripCancel").show();
      $("#FinishPlan").hide();

      if (arrayObj.has_hotel == 1) {
        $("#r_hotel").attr("checked", "");
      } else {
        $("#r_hotel").removeAttr("checked");
      }
      if (arrayObj.finality == 99) {
          $(".other").show();
      } else {
          $(".other").hide();
      }
      if (arrayObj.finality == 9) {
          $(".goal").hide();
      } else {
          $(".goal").show();
      }
      if (arrayObj.flight.dispatch > 1) {
          $(".dispatch-info").show();
      } else {
          $(".dispatch-info").hide();
      }

      $("#type").val(arrayObj.finality);
      $("#other").val(arrayObj.other);
      $("#goal").val(arrayObj.goal);

      $("#origin").val(arrayObj.flight.origin_date);
      $("#period").val(arrayObj.flight.origin_period);
      $("#countryOrigin").val(arrayObj.flight.origin_country);
      $("#stateOrigin").val(arrayObj.flight.origin_state);
      l_state_origin = arrayObj.flight.origin_state;
      $("#cityOrigin").val(arrayObj.flight.origin_city);

      $("#destiny").val(arrayObj.flight.destiny_date);
      $("#period2").val(arrayObj.flight.destiny_period);
      $("#countryDestiny").val(arrayObj.flight.destiny_country);
      $("#stateDestiny").val(arrayObj.flight.destiny_state);
      l_state_destiny = arrayObj.flight.destiny_state;
      $("#cityDestiny").val(arrayObj.flight.destiny_city);
      $("#dispatch").val(arrayObj.flight.dispatch);
      $("#dispatch-info").val(arrayObj.flight.dispatch_info);

      $("#originHotel").val(arrayObj.hotel.enter_date);
      $("#checkoutHotel").val(arrayObj.hotel.checkout);
      $("#countryOriginHotel").val(arrayObj.hotel.enter_country);
      $("#stateOriginHotel").val(arrayObj.hotel.enter_state);
      l_state_hotel = arrayObj.hotel.enter_state;
      $("#cityOriginHotel").val(arrayObj.hotel.enter_city);
      $("#backHotel").val(arrayObj.hotel.exit_date);
      $("#addressHotel").val(arrayObj.hotel.address);

      ArrayPeoples = arrayObj.peoples;
      ReloadPeoples();
      ReloadTablePlan();
      ReloadStates();
      
      $("html, body").animate({ scrollTop: 0 }, "slow");
    }

    function savePlan(index) {

      var arrayObj = ArrayPlan[index];
      var has_hotel;
      if ($("#r_hotel").prop("checked")) {
        has_hotel = 1;
      } else {
        has_hotel = 0;
        $("#originHotel").val("");
        $("#checkoutHotel").val("");
        $("#countryOriginHotel").val("");
        $("#stateOriginHotel").val("");
        $("#cityOriginHotel").val("");
        $("#backHotel").val("");
        $("#addressHotel").val("");
      };
      if ($("#type").val() == 9) {
        $("#goal").val("");
      }

      arrayObj.finality = $("#type").val();
      arrayObj.other = $("#other").val();
      arrayObj.goal = $("#goal").val();

      arrayObj.flight.origin_date =$("#origin").val();
      arrayObj.flight.origin_period = $("#period").val();
      arrayObj.flight.origin_country = $("#countryOrigin").val();
      arrayObj.flight.origin_state = $("#stateOrigin").val();
      arrayObj.flight.origin_state_name = $("#stateOrigin option:selected").text();
      arrayObj.flight.origin_city = $("#cityOrigin").val();

      arrayObj.flight.destiny_date = $("#destiny").val();
      arrayObj.flight.destiny_period = $("#period2").val();
      arrayObj.flight.destiny_country = $("#countryDestiny").val();
      arrayObj.flight.destiny_state = $("#stateDestiny").val();
      arrayObj.flight.destiny_state_name = $("#stateDestiny option:selected").text();
      arrayObj.flight.destiny_city = $("#cityDestiny").val();
      arrayObj.flight.dispatch = $("#dispatch").val();
      arrayObj.flight.dispatch_info = $("#dispatch-info").val();

      arrayObj.has_hotel = has_hotel;
      arrayObj.hotel.enter_date = $("#originHotel").val();
      arrayObj.hotel.checkout = $("#checkoutHotel").val();
      arrayObj.hotel.enter_country = $("#countryOriginHotel").val();
      arrayObj.hotel.enter_state = $("#stateOriginHotel").val();
      arrayObj.hotel.enter_city = $("#cityOriginHotel").val();
      arrayObj.hotel.exit_date = $("#backHotel").val();
      arrayObj.hotel.address = $("#addressHotel").val();

      arrayObj.peoples = ArrayPeoples;

      $(".actions [href='#finish']").html('Adicionar rota!');
      $("#home-tab-justified").html('NOVA ROTA DE VOO');
      $("#submitTripCancel").hide();
      $("#FinishPlan").show();

      var lastr = index + 1;
      if (ArrayPlan.length == lastr) {
        if (has_hotel == 1 && $("#backHotel").val() != "") {
            last_date = $("#backHotel").val(); 
        } else {
            last_date = $("#destiny").val(); 
        }
        last_period = $("#period2").val();
        last_country = $("#countryDestiny").val();
        last_state = $("#stateDestiny").val();
        last_city = $("#cityDestiny").val();
      }
      
      ArrayPlan;
      ReloadTablePlan();
      CleanInput();

      $("#origin").val(last_date);
      $("#period").val(last_period);
      $("#countryOrigin").val(last_country);

      $("#cityOrigin").val(last_city);

      $("#originHotel").val(last_date);
      $("#countryOriginHotel").val(last_country);
    
      $("#cityOriginHotel").val(last_city);

      getLastStates();
      success('<?= __('trip_i.tn_route_update') ?>');
      route_id = null;      

    }

    function cancelPlan() {
      $(".actions [href='#finish']").html('<?= __('trip_i.tn_route_btn_finish_add') ?>');
      $("#home-tab-justified").html('<?= __('trip_i.tn_trip_tab_1') ?>');
      $("#submitTripCancel").hide();
      $("#FinishPlan").show();
      ReloadTablePlan();
      CleanInput();
      
      $("#origin").val(last_date);
      $("#period").val(last_period);
      $("#countryOrigin").val(last_country);

      $("#cityOrigin").val(last_city);

      $("#originHotel").val(last_date);
      $("#countryOriginHotel").val(last_country);
    
      $("#cityOriginHotel").val(last_city);

      getLastStates();

      route_id = null;

    }

    function CleanInput() {
        $("#PlanTrip").trigger("reset");
        ArrayPeoples = new Array();
        $(".other").hide();
        $(".dispatch-info").hide();
        $("#peoples").html("");
        $('#home-tab-justified').tab('show');
        $("#r_hotel").removeAttr("checked");

        wizardTrip.steps("reset");
        $(".wizard-horizontal").find(".step-icon").removeClass("bx bx-time-five");
        $(".wizard-horizontal").find(".step-icon").removeClass("bx bx-check-circle");
        $(".current").find(".step-icon").addClass("bx bx-time-five");
        $("#plane").updateLiviconEvo({ strokeColor: '#adb5bd' });
        $("#building").updateLiviconEvo({ strokeColor: '#adb5bd' });
        $("#user").updateLiviconEvo({ strokeColor: '#adb5bd' });
        $(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
            strokeColor: '#5A8DEE'
        });
    }

    function ReloadPeoples() {
      var list = "";
        for(var i = 0; i < ArrayPeoples.length; i++) {
                var arrayObj = ArrayPeoples[i];
                list += '<div class="col-2 mt-1" onclick="Delete('+ i +');">';
                list += '<a href="javascript: void(0);">';
                list += '<div class="media">';
                list += '<img src="/media/avatars/avatar10.jpg" class="rounded mr-75" alt="profile image" height="64" width="64">';
                list += '<div class="media-body mt-25">';
                list += '<div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">';
                list += '<h6>'+ arrayObj.name +'</h6>';
                list += '</div>';
                list += '<p class="text-muted"><small>'+ arrayObj.r_code +'</small></p>';
                list += '</div>';
                list += '</div>';
                list += '</a>';
                list += '</div>';
            }
        $("#peoples").html(list);
    }

    function ReloadTablePlan() {
      localStorage.ArrayPlan = JSON.stringify(ArrayPlan);
	  ArrayPlan = JSON.parse(localStorage.ArrayPlan);
	  if (ArrayPlan.length == 0) {

		localStorage.removeItem('ArrayPlan');	
	  }
      var plan = "";
      for(var i = 0; i < ArrayPlan.length; i++) {
          var arrayObj = ArrayPlan[i];
          plan += '<tr>';
          plan += '<td class="text-center">';
          plan += '<i class="bx bx-down-arrow-alt"></i>';
          plan += '</td>';
          plan += '<td>';
          plan += '<small><i>'+ arrayObj.flight.origin_date +'</i></small>';
          plan += '<br><span class="font-w600">UF: '+ arrayObj.flight.origin_state_name +'</span>';
          plan += '<br>'+ arrayObj.flight.origin_city +'';
          plan += '</td>';
          plan += '<td>';
          plan += '<small><i>'+ arrayObj.flight.destiny_date +'</i></small>';
          plan += '<br><span class="font-w600">UF: '+ arrayObj.flight.destiny_state_name +'</span>';
          plan += '<br>'+ arrayObj.flight.destiny_city +'';
          plan += '</td>';
          var hotel = arrayObj.has_hotel == 1 ? "{{ __('trip_i.tpe_yes') }}" : "{{ __('trip_i.tpe_no') }}";
          plan += '<td class="font-w600"> '+ hotel +' </td>';
          plan += '<td class="font-w500"> '+ arrayObj.flight.dispatch +' </td>';
          var total_p = arrayObj.peoples == null ? 1 : arrayObj.peoples.length + 1;
          plan += '<td class="font-w500"> '+ total_p +' </td>';
          plan += '<td class="text-center">';
          plan += '<div class="dropleft">';
          plan += '<span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">';
          plan += '</span>';
          plan += '<div class="dropdown-menu dropdown-menu-right">';
          plan += '<a onclick="editPlan('+ i +')" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i> {{ __('layout_i.op_edit') }}</a>';
          plan += '<a onclick="deletePlan('+ i +')" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-trash mr-1"></i> {{ __('layout_i.op_delete') }}</a>';
          plan += '</div>';
          plan += '</div>';
          plan += '</td>';
          plan += '</tr>';
      }
      $("#TablePlan").html(plan);

      $("#data_input").val(JSON.stringify(ArrayPlan));
    }

    function ReloadStates() {
        $('#stateOrigin').load('/states?country='+$('#countryOrigin').val(), function( response, status, xhr ) {
              if ( status == "error" ) {

                  error('<?= __('trip_i.etn_select_network_error') ?>');

              } else {
                if (l_state_origin) {
                    $('#stateOrigin').val(l_state_origin).change();
                    l_state_origin = "";
                }
              }
          });
        
        $('#stateDestiny').load('/states?country='+$('#countryDestiny').val(), function( response, status, xhr ) {
            if ( status == "error" ) {
                error('<?= __('trip_i.etn_select_network_error') ?>');
            } else {
                if (l_state_destiny) {
                    $('#stateDestiny').val(l_state_destiny).change();
                    l_state_destiny = "";
                }
            }
        });

        if ($("#countryOriginHotel").val() != 0) {
            $('#stateOriginHotel').load('/states?country='+$('#countryOriginHotel').val(), function( response, status, xhr ) {
                if ( status == "error" ) {
                    error('<?= __('trip_i.etn_select_network_error') ?>');
                } else {
                    if (l_state_hotel) {
                        $('#stateOriginHotel').val(l_state_hotel).change();
                        l_state_hotel = "";
                    }
                }
            });
        }
    }

    function getLastStates() {
            $('#stateOrigin').load('/states?country='+ last_country, function( response, status, xhr ) {
                if ( status == "error" ) {

                    error('<?= __('trip_i.etn_select_network_error') ?>');

                } else {
                    $('#stateOrigin').val(last_state).change();
                }
            });
            $('#stateOriginHotel').load('/states?country=' + last_country, function( response, status, xhr ) {
                if ( status == "error" ) {
                    error('<?= __('trip_i.etn_select_network_error') ?>');
                } else {
                    $("#stateOriginHotel").val(last_state).change();
                }
            });
    }

    $(document).ready(function () {
        // function to remove tour on small screen
        window.resizeEvt;
        if ($(window).width() > 576) {
            $('#ActiveTraine').on("click", function () {
                clearTimeout(window.resizeEvt);
                tour.start();
            })
        }
        else {
        $('#ActiveTraine').on("click", function () {
            clearTimeout(window.resizeEvt);
            tour.cancel()
            window.resizeEvt = setTimeout(function () {
            alert("Tour only works for large screens!");
            }, 250);
        })
        }
        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mTrip").addClass('sidebar-group-active active');
            $("#mTripNew").addClass('active');
        }, 100);

        if (localStorage.ArrayPlan != undefined) {
            ArrayPlan = JSON.parse(localStorage.ArrayPlan);
		
            pos = ArrayPlan.length - 1;
            if (ArrayPlan[pos].has_hotel == 1 && ArrayPlan[pos].hotel.exit_date != "") {
                last_date = ArrayPlan[pos].hotel.exit_date; 
            } else {
                last_date =  ArrayPlan[pos].flight.destiny_date;
            }
            last_date =  ArrayPlan[pos].flight.destiny_date; 
            last_period = ArrayPlan[pos].flight.destiny_period;
            last_country = ArrayPlan[pos].flight.destiny_country;
            last_state = ArrayPlan[pos].flight.destiny_state;
            last_city = ArrayPlan[pos].flight.destiny_city;
            
            ReloadTablePlan();

            $("#origin").val(last_date);
            $("#period").val(last_period);
            $("#countryOrigin").val(last_country);
            
            $("#cityOrigin").val(last_city);

            $("#originHotel").val(last_date);
            $("#countryOriginHotel").val(last_country);
            
            $("#cityOriginHotel").val(last_city);
            getLastStates();

            $("#FinishPlan").show();
        } else {
            $("#FinishPlan").hide();
        }
            
            $("#mTrip").addClass("open");
            $("#mTripNew").addClass("active");

            $("#FinishPlan").click(function (e) { 
                if (ArrayPlan != "") {

                    Swal.fire({
                    title: '<?= __('trip_i.tn_finish_plan_title') ?>',
                    text: "<?= __('trip_i.tn_finish_plan_desc') ?>",
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
                            localStorage.removeItem('ArrayPlan');
                            block();
                            $("#PlanTrip").submit();
                        }
                    })

                } else {
                 error('<?= __('trip_i.etn_no_plan') ?>');
                }
                    e.preventDefault();
                
            });

            $('#origin').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                minDate: "<?= date('Y-m-d') ?>",
            });

            $('#destiny').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                minDate: "<?= date('Y-m-d') ?>",
            });

        $('#countryOrigin').change(function(){
            val = $('#stateOrigin').load('/states?country='+$('#countryOrigin').val(), function( response, status, xhr ) {
                    if ( status == "error" ) {

                        error('<?= __('trip_i.etn_select_network_error') ?>');

                    } else {

                    }
                });
        });
        
        $('#countryDestiny').change(function(){
            val = $('#stateDestiny').load('/states?country='+$('#countryDestiny').val(), function( response, status, xhr ) {
                    if ( status == "error" ) {
                        error('<?= __('trip_i.etn_select_network_error') ?>');
                    } else {

                    }
                });
        });

        
        $('#originHotel').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
            minDate: "<?= date('Y-m-d') ?>",
        });

        $('#backHotel').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
            minDate: "<?= date('Y-m-d') ?>",
        });

        $('#countryOriginHotel').change(function(){
            val = $('#stateOriginHotel').load('/states?country='+$('#countryOriginHotel').val(), function( response, status, xhr ) {
                    if ( status == "error" ) {
                        error('<?= __('trip_i.etn_select_network_error') ?>');
                    } else {
                        
                    }
                });
        });
        
        $('#countryBackHotel').change(function(){
            val = $('#stateBackHotel').load('/states?country='+$('#countryBackHotel').val(), function( response, status, xhr ) {
                    if ( status == "error" ) {
                        error('<?= __('trip_i.etn_select_network_error') ?>');
                    } else {

                    }
                });
        });

        $(".addpeople").on('click', function () {
            if ($("#r_code").val() == "") {

                        error('<?= __('trip_i.etn_add_people_registrion_empty') ?>');

            } else if ($("#name_full").val() == "") {

                        error('<?= __('trip_i.etn_add_people_name_empty') ?>');
            } else {
                for(var i = 0; i < ArrayPeoples.length; i++) {
                    var arrayObj = ArrayPeoples[i];
                    if (arrayObj.r_code == $("#r_code").val()) {

                        error('<?= __('trip_i.etn_add_people_already') ?>');
                        return;
                    }
                }

                ArrayPeoples.push({"r_code" : $("#r_code").val(), "name" : $("#name_full").val()});

                $("#r_code").val("");
                $("#name_full").val("");
                ReloadPeoples();
            }
        });
    });
    </script>
<script>
var wizardTrip = $(".wizard-horizontal").steps({
  headerTag: "h6",
  bodyTag: "fieldset",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: 'Submit'
  },
  onFinishing: function (event, currentIndex) {
    if ($("#type").val() == 0) {

    error('<?= __('trip_i.etn_finality_empty') ?>');
    return;
    } else if ($("#type").val() == 99 && $("#other").val() == "") {

    error('<?= __('trip_i.etn_finality_other_empty') ?>');
    return;
    } else if ($("#origin").val() == "") {

    error('<?= __('trip_i.etn_go_date_empty') ?>');
    return;
    } else if ($("#period").val() == 0) {

    error('<?= __('trip_i.etn_go_period_empty') ?>');
    return;
    } else if ($("#countryOrigin").val() == 0) {

    error('<?= __('trip_i.etn_go_country_empty') ?>');
    return;
    } else if ($("#stateOrigin").val() == 0) {

    error('<?= __('trip_i.etn_go_state_empty') ?>');
    return;
    } else if ($("#cityOrigin").val() == "") {

    error('<?= __('trip_i.etn_go_city_empty') ?>');
    return;
    } else if ($("#destiny").val() == "") {

    error('<?= __('trip_i.etn_destiny_date_empty') ?>');
    return;
    } else if ($("#period2").val() == 0) {

    error('<?= __('trip_i.etn_destiny_period_empty') ?>');
    return;
    } else if ($("#countryDestiny").val() == 0) {

    error('<?= __('trip_i.etn_destiny_country_empty') ?>');
    return;
    } else if ($("#stateDestiny").val() == 0) {

    error('<?= __('trip_i.etn_destiny_state_empty') ?>');
    return;
    } else if ($("#cityDestiny").val() == "") {

    error('<?= __('trip_i.etn_destiny_city_empty') ?>');
    return;
    } else if ($("#dispatch").val() > 1 && $("#dispatch-info").val() == "") {

    error('<?= __('trip_i.etn_dispatch_info_empty') ?>');
    return;
    } else if ($("#r_hotel").prop("checked")) {

        if ($("#originHotel").val() == "") {

        error('<?= __('trip_i.etn_hotel_date_empty') ?>');
        return;
        } else if ($("#checkoutHotel").val() == 0) {

        error('<?= __('trip_i.etn_hotel_checkout_empty') ?>');
        return;
        } else if ($("#countryOriginHotel").val() == 0) {

        error('<?= __('trip_i.etn_hotel_country_empty') ?>');
        return;
        } else if ($("#stateOriginHotel").val() == 0) {

        error('<?= __('trip_i.etn_hotel_state_empty') ?>');
        return;
        } else if ($("#cityOriginHotel").val() == 0) {

        error('<?= __('trip_i.etn_hotel_city_empty') ?>');
        return;
        } else if ($("#backHotel").val() == "") {

        error('<?= __('trip_i.etn_hotel_exit_empty') ?>');
        return;
        }
    }

    return true;
  },
  onFinished: function (event, currentIndex) {

        if (route_id == null) {
            var has_hotel;
            if ($("#r_hotel").prop("checked")) {
            has_hotel = 1;
            } else {
            has_hotel = 0;
            $("#originHotel").val("");
            $("#checkoutHotel").val("");
            $("#countryOriginHotel").val("");
            $("#stateOriginHotel").val("");
            $("#cityOriginHotel").val("");
            $("#backHotel").val("");
            $("#addressHotel").val("");
            };
            if ($("#type").val() == 9) {
            $("#goal").val("");
            };

            ArrayPlan.push({
            "finality" : $("#type").val(),
            "other" : $("#type").val() == 99 ? $("#other").val() : '',
            "goal" : $("#goal").val(),
            "flight" : {
                "origin_date": $("#origin").val(),
                "origin_period": $("#period").val(),
                "origin_country": $("#countryOrigin").val(),
                "origin_country_name": $("#countryOrigin option:selected").text(),
                "origin_state": $("#stateOrigin").val(),
                "origin_state_name": $("#stateOrigin option:selected").text(),
                "origin_city": $("#cityOrigin").val(),
                "destiny_date": $("#destiny").val(),
                "destiny_period": $("#period2").val(),
                "destiny_country": $("#countryDestiny").val(),
                "destiny_country_name": $("#countryDestiny option:selected").text(),
                "destiny_state": $("#stateDestiny").val(),
                "destiny_state_name": $("#stateDestiny option:selected").text(),
                "destiny_city": $("#cityDestiny").val(),
                "dispatch": $("#dispatch").val(),
                "dispatch_info": $("#dispatch-info").val(),
            },
            "has_hotel" : has_hotel,
            "hotel" : {
                "enter_date": $("#originHotel").val(),
                "checkout": $("#checkoutHotel").val(),
                "enter_country": $("#countryOriginHotel").val(),
                "enter_country_name": $("#countryOriginHotel option:selected").text(),
                "enter_state": $("#stateOriginHotel").val(),
                "enter_state_name": $("#stateOriginHotel option:selected").text(),
                "enter_city": $("#cityOriginHotel").val(),
                "exit_date": $("#backHotel").val(),
                "address": $("#addressHotel").val(),
            },
            "peoples" : ArrayPeoples,
            });

            if (has_hotel == 1 && $("#backHotel").val() != "") {
                last_date = $("#backHotel").val(); 
            } else {
                last_date = $("#destiny").val(); 
            } 
            last_period = $("#period2").val();
            last_country = $("#countryDestiny").val();
            last_state = $("#stateDestiny").val();
            last_city = $("#cityDestiny").val();
            
            ReloadTablePlan();
            CleanInput();

            $("#origin").val(last_date);
            $("#period").val(last_period);
            $("#countryOrigin").val(last_country);

            $("#cityOrigin").val(last_city);

            $("#originHotel").val(last_date);
            $("#countryOriginHotel").val(last_country);
            
            $("#cityOriginHotel").val(last_city);

            getLastStates();

            success('<?= __('trip_i.tn_route_add') ?>');

            $("#FinishPlan").show();

        } else {
            savePlan(route_id);
        }


    }
});
// live Icon color change on state change
$(document).ready(function () {
	
  $(".current").find(".step-icon").addClass("bx bx-time-five");
  $(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#5A8DEE'
  });

  $('#type').change(function() {
        if ($("#type").val() == 99) {
            $(".other").show();
        } else {
            $(".other").hide();
        }
        if ($("#type").val() == 9) {
            $(".goal").hide();
        } else {
            $(".goal").show();
        }
    });

    $('#dispatch').change(function() {
        if ($("#dispatch").val() > 1) {
            $(".dispatch-info").show();
        } else {
            $(".dispatch-info").hide();
        }
    });

    // RESPONSIVE FIELDS
    $(window).resize(function() {
            var width = $(window).width();
            if (width <= 380){
                $("*#mobile-c6-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-12');
                    
                });
                $("*#mobile-c5-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-12');
                    
                });
            } else {
                $("*#mobile-c6-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-6');
                    
                });
                $("*#mobile-c5-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-5');
                    
                });
            }
        });

        
        if ($(window).width() <= 380) 
        {
            $("*#mobile-c6-c12").each(function (index, element) {
                $(element).removeClass().addClass('col-12');
                
            });
            $("*#mobile-c5-c12").each(function (index, element) {
                $(element).removeClass().addClass('col-12');
                
            });
        } else {
            $("*#mobile-c6-c12").each(function (index, element) {
                $(element).removeClass().addClass('col-6');
                
            });
            $("*#mobile-c5-c12").each(function (index, element) {
                $(element).removeClass().addClass('col-5');
                
            });
        }
});
// Icon change on state
// if click on next button icon change
$(".actions [href='#next']").click(function () {
  $(".done").find(".step-icon").removeClass("bx bx-time-five").addClass("bx bx-check-circle");
  $(".current").find(".step-icon").removeClass("bx bx-check-circle").addClass("bx bx-time-five");
  // live icon color change on next button's on click
  $(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#5A8DEE'
  });
  $(".current").prev("li").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#39DA8A'
  });
});
$(".actions [href='#previous']").click(function () {
  // live icon color change on next button's on click
  $(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#5A8DEE'
  });
  $(".current").next("li").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#adb5bd'
  });
});
// if click on  submit   button icon change
$(".actions [href='#finish']").click(function () {
  $(".done").find(".step-icon").removeClass("bx-time-five").addClass("bx bx-check-circle");
  $(".last.current.done").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#39DA8A'
  });
});

$(".actions [href='#previous']").html('<?= __('layout_i.btn_previous') ?>');
$(".actions [href='#next']").html('<?= __('layout_i.btn_next') ?>');
$(".actions [href='#finish']").html('<?= __('trip_i.tn_route_btn_finish_add') ?>');

// add primary btn class
$('.actions a[role="menuitem"]').addClass("btn btn-primary");
$('.icon-tab [role="menuitem"]').addClass("glow ");
$('.wizard-vertical [role="menuitem"]').removeClass("btn-primary").addClass("btn-light-primary");
</script>
@endsection