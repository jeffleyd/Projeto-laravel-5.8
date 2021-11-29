@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('trip_i.trc_title') }}: #<?= $planid ?></h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('trip_i.trc_title_sub') }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php if ($trip->is_cancelled == 1) { ?>
        <div class="alert alert-danger alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-x"></i>
                <span>
                    Rota foi cancelada pela administração, entre em contato para mais detalhes.
                </span>
            </div>
        </div>
      <?php } else if ($trip->is_approv == 1 and $trip->is_completed == 1) { ?>
        <div class="alert alert-success alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-check-circle"></i>
                <span>
                    {{ __('trip_i.trc_status_completed') }}
                </span>
            </div>
        </div>
    <?php } else if ($trip->is_approv == 1) { ?>
        <div class="alert alert-info alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error-circle"></i>
                <span>
                    {{ __('trip_i.trc_status_approv') }}
                </span>
            </div>
        </div>
    <?php } else if ($trip->is_reprov == 1) { ?>
        <div class="alert alert-danger alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error"></i>
                <span>
                    {{ __('trip_i.trc_status_reprov') }}
                </span>
            </div>
        </div>
    <?php } else if ($trip->has_analyze == 1) { ?>
        <div class="alert alert-warning alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error-circle"></i>
                <span>
                    {{ __('trip_i.trc_wait_approv') }}
                </span>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-secondary alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error-circle"></i>
                <span>
                    {{ __('trip_i.trc_not_sent') }}
                </span>
            </div>
        </div>
    <?php } ?>
        
        <section id="nav-tabs-centered">
          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header">
                </div>
                <div class="card-content">
                  <div class="card-body">
                    <ul class="nav nav-tabs justify-content-center nav-fill" role="tablist">
                        <li class="nav-item current">
                          <a class="nav-link active" id="request-tab-center" data-toggle="tab" href="#request-center" aria-controls="request-center" role="tab" aria-selected="true">
                              <i class="bx bxs-plane-alt align-middle"></i>
                              <span class="align-middle"><?= __('trip_i.trc_38') ?></span>
                          </a>
                        </li>

                        <?php if (isset($grade)) { ?>
                        <li class="nav-item">
                          <a class="nav-link" id="agency-tab-center" data-toggle="tab" href="#agency-center" aria-controls="agency-center" role="tab" aria-selected="false">
                              <i class="bx bx-briefcase-alt-2 align-middle"></i>
                              <span class="align-middle"><?= __('trip_i.trc_40') ?></span>
                          </a>
                        </li>
                        <?php } ?>
                        <?php if (isset($budget)) { ?>
                        <li class="nav-item">
                          <a class="nav-link" id="interation-tab-center" data-toggle="tab" href="#interation-center" aria-controls="interation-center" role="tab" aria-selected="false">
                              <i class="bx bx-message align-middle"></i>
                              <span class="align-middle"><?= __('trip_i.trc_41') ?></span>
                          </a>
                        </li>
                        <?php } ?>
                      </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="request-center" aria-labelledby="request-tab-center" role="tabpanel">
                        <div class="row bg-lighten-5 rounded mb-2 mx-25 text-center text-lg-left">
                            <div class="col-12 col-sm-12 p-2">
                                <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                      <tr>
                                        <td><b><?= __('trip_i.trc_35') ?></b></td>
                                        <td>
                                            <?= $user->first_name ?> <?= $user->last_name ?>
                                            <?php if (count($peoples) > 0) { ?>
                                                @foreach ($peoples as $key)
                                                    --- <?= $key->name ?>
                                                @endforeach
                                            <?php }  ?>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td><b>{{ __('trip_i.trc_finality') }}</b></td>
                                        <td>
                                            <?php if ($trip->finality == 99) { ?>
                                                <?= $trip->other ?><br>
                                            <?php } else { ?>
                                                <?= finalityName($trip->finality) ?><br>
                                            <?php }  ?>    
                                        </td>
                                      </tr>
                                      <tr>
                                        <td><b>{{ __('trip_i.trc_goal_trip') }}</b></td>
                                        <td><?= $trip->goal ?></td>
                                      </tr>
                                      <tr>
                                        <td><b>{{ __('trip_i.trc_destiny_dispatch') }}</b></td>
                                        <td>
                                            <?= $trip->dispatch ?>
                                        </td>
                                      </tr>
                                      <?php if ($trip->dispatch > 1) { ?>
                                      <tr>
                                        <td colspan="2">
                                            <?= $trip->dispatch_reason ?>
                                        </td>
                                      </tr>
                                      <?php } ?>
                        
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 p-2">
                                <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                      <tr>
                                        <td>{{ __('trip_i.trc_orign') }} <i class="bx bx-right-arrow-alt"></i></td>
                                        <td>{{ __('trip_i.trc_destiny') }}</td>
                                        <?php if ($trip->has_hotel == 1) { ?><td colspan="2">{{ __('trip_i.trc_hotel') }}</td><?php } ?>
                                      </tr>
                                      <tr>
                                        <td style="line-height: 1.8;">
                                            <b>{{ __('trip_i.trc_origin_country') }}</b> <?= GetCountryName($trip->origin_country) ?><br>
                                            <b>{{ __('trip_i.trc_origin_state') }}</b> <?= GetStateName($trip->origin_country, $trip->origin_state) ?><br>
                                            <b>{{ __('trip_i.trc_origin_city') }}</b> <?= $trip->origin_city ?><br>
                                            <b>{{ __('trip_i.trc_origin_period') }}</b> <?= periodName($trip->origin_period) ?><br>
                                            <b>{{ __('trip_i.trc_origin_date') }}</b> <?= date('Y-m-d', strtotime($trip->origin_date)) ?>
                                        </td>
                                        <td style="line-height: 1.8;">
                                            <b>{{ __('trip_i.trc_destiny_country') }}</b> <?= GetCountryName($trip->destiny_country) ?><br>
                                            <b>{{ __('trip_i.trc_destiny_state') }}</b> <?= GetStateName($trip->destiny_country, $trip->destiny_state) ?><br>
                                            <b>{{ __('trip_i.trc_destiny_city') }}</b> <?= $trip->destiny_city ?><br>
                                            <b>{{ __('trip_i.trc_destiny_period') }}</b> <?= periodName($trip->destiny_period) ?><br>
                                            <b>{{ __('trip_i.trc_destiny_date') }}</b> <?= date('Y-m-d', strtotime($trip->destiny_date)) ?>
                                        </td>
                                        <?php if ($trip->has_hotel == 1) { ?>
                                        <td style="line-height: 1.8;">
                                            ({{ __('trip_i.trc_hotel_enter') }})<br>
                                            <b>{{ __('trip_i.trc_hotel_country') }}</b> <?= GetCountryName($trip->hotel_country) ?><br>
                                            <b>{{ __('trip_i.trc_hotel_state') }}</b> <?= GetStateName($trip->hotel_country, $trip->hotel_state) ?><br>
                                            <b>{{ __('trip_i.trc_hotel_city') }}</b> <?= $trip->hotel_city ?><br>
                                            <b>{{ __('trip_i.trc_hotel_checkout') }}</b> 
                                            <?php if ($trip->hotel_checkout == 1) { ?>
                                                {{ __('trip_i.trc_hotel_normal') }}
                                            <?php } else if ($trip->hotel_checkout == 2) { ?>
                                                {{ __('trip_i.trc_hotel_later') }}
                                            <?php } ?><br>
                                            <b>{{ __('trip_i.trc_hotel_date') }}</b> <?= date('Y-m-d', strtotime($trip->hotel_date)) ?>
											<br><b>Endereço do hotel:</b> <?= $trip->hotel_address ?>
                                        </td>
                                        <td style="line-height: 1.8;">
                                            ({{ __('trip_i.trc_hotel_exit') }})<br>
                                            <b>{{ __('trip_i.trc_hotel_exit_date') }}</b> <?= date('Y-m-d', strtotime($trip->hotel_exit)) ?><br>
                                        </td>
                                        <?php } ?>
                                      </tr>
                        
                                    </tbody>
                                  </table>
                                </div>
                            </div>

                        </div>
                      </div>
                      <?php if (isset($grade)) { ?>
                      <div class="tab-pane" id="agency-center" aria-labelledby="agency-tab-center" role="tabpanel">
                        <div class="row bg-lighten-5 rounded mb-2 mx-25 text-center text-lg-left">
                            <?php if (count($peoples) > 0) { ?>
                            <div class="col-12 col-sm-12 p-2">
                                <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('trip_i.trc_people_identity') }}</th>
                                            <th>{{ __('trip_i.trc_people_name') }}</th>
                                            <th>{{ __('trip_i.trc_people_file') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach ($peoples as $key) { ?>
                                      <tr>
                                        <td><?= $key->identity ?></td>
                                        <td><?= $key->name ?></td>
                                        <td>
                                            <?php if (!empty($key->ticket_url)) { ?>
                                                <a target="_blank" href="<?= $key->ticket_url ?>">
                                                    <button type="button" class="btn btn-sm btn-outline-info mb-10">{{ __('trip_i.trc_people_ticket') }}</button>
                                                </a>
                                            <?php } ?>
                                        </td>
                                      </tr>
                                    <?php } ?>
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="col-12 col-sm-12 p-2">
                                <div class="table-responsive">

                                    <table id="table-extended-chechbox" class="table table-transparent">
                                        <thead>
                                            <tr>
                                                <th class="text-center"></th>
                                                <th>{{ __('trip_i.trc_agency_name') }}</th>
                                                <th>{{ __('trip_i.trc_agency_budget') }}</th>
                                                <th>{{ __('trip_i.trc_agency_actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <form id="agencyBudgets" action="/trip/send/budget" method="post">
                                                    <input type="hidden" name="id" value="<?= $planid ?>">
                                                    <?php foreach ($agency as $key) { ?>
                                                    <?php $select_agency = App\Model\TripAgencyBudget::where('trip_plan_id', $planid)->where('agency_id', $key->id)->first(); ?>
                                                    <tr class="">
                                                        <td class="text-center">
                                                            <div class="checkbox"><input type="checkbox" class="checkbox-input" value="<?= $key->id ?>" <?php if (isset($select_agency)) { ?>checked="checked" disabled=""<?php } else { ?> id="agency_<?= $key->id ?>" name="agency[<?= $key->id ?>]" <?php } ?>>
                                                                <label for="agency_<?= $key->id ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?= $key->name ?>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($select_agency)) { ?>
                                                                <?php if (!empty($select_agency->budget_url)) { ?>
                                                                    <?php if (isset($select_agency->budget_url)) { ?>
                                                                    <a target="_blank" href="<?= $select_agency->budget_url ?>" data-toggle="lightbox" data-gallery="budget-<?= $key->id ?>-1" data-width="800">
                                                                        <button type="button" class="btn btn-sm btn-outline-info">{{ __('trip_i.trc_agency_see_budget') }}</button>
                                                                    </a>
                                                                    <?php } ?>
                                                                    <?php if (isset($select_agency->budget_hotel)) { ?>
                                                                    <a target="_blank" href="<?= $select_agency->budget_hotel ?>" data-toggle="lightbox" data-gallery="budget-<?= $key->id ?>-2" data-width="800">
                                                                        <button type="button" class="btn btn-sm btn-outline-primary">{{ __('trip_i.trc_agency_see_hotel') }}</button>
                                                                    </a>
                                                                    <?php } ?>
                                                                    <button type="button" onclick="sDesc('<?= $select_agency->description; ?>')" class="btn btn-sm btn-outline-danger">{{ __('trip_i.trc_agency_observation') }}</button>
                                                                <?php } ?>
                                                                <?php if (!empty($select_agency->ticket_url)) { ?>
                                                                    <a target="_blank" href="<?= $select_agency->ticket_url ?>">
                                                                        <button type="button" class="btn btn-sm btn-outline-info">{{ __('trip_i.trc_agency_down_ticket') }}</button>
                                                                    </a>
                                                                <?php } ?>
                                                                <?php if (!empty($select_agency->ticket_hotel)) { ?>
                                                                    <a target="_blank" href="<?= $select_agency->ticket_hotel ?>">
                                                                        <button type="button" class="btn btn-sm btn-outline-info">{{ __('trip_i.trc_agency_down_hotel') }}</button>
                                                                    </a>
                                                                <?php } ?>
                                                                <?php $oldfiles = App\Model\TripBudgetFiles::where('trip_plan_id', $trip->id)->where('agency_id', $key->id)->orderBy('id', 'DESC')->get(); ?>
                                                                <!-- Agency old files -->
                                                                <div class="row" style="display:none;">
                                                                <?php foreach ($oldfiles as $file) { ?>
                                                                    <?php if (!empty($file->old_file)) { ?>
                                                                    <a href="<?= $file->old_file ?>" data-toggle="lightbox" data-gallery="budget-<?= $key->id ?>-1"></a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                </div>
                                                                <div class="row" style="display:none;">
                                                                <?php foreach ($oldfiles as $file) { ?>
                                                                    <?php if (!empty($file->old_file_hotel)) { ?>
                                                                    <a href="<?= $file->old_file_hotel ?>" data-toggle="lightbox" data-gallery="budget-<?= $key->id ?>-2"></a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                </div>
                                                                <!-- Agency old files end -->
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($select_agency)) { ?>
                                                            <?php if (isset($budget)) { ?>
                                                                <?php if ($budget->agency_id == $key->id) {  $has_approv_agency = 1;?>
                                                                    <?php $agency_name = $key->name; ?>
                                                                    <button type="button" class="btn btn-sm btn-success">{{ __('trip_i.trc_agency_approv') }}</button>
                                                                <?php } ?>
                                                            <?php } else if ($has_approv_agency == 0) { ?>
                                                                <button type="button" onclick="approvAgency(<?= $key->id ?>)" class="btn btn-sm btn-info">{{ __('trip_i.trc_agency_approv_budget') }}</button>
                                                            <?php } else { ?>   
                                                                
                                                            <?php } ?> 
                                                            <?php } ?> 
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                    </form>
                                            </tr>
                                        </tbody>
                                    </table>

                                    
                                </div>
                            </div>

                            <div class="col-12 col-sm-12 p-2">
                                <?php if (isset($grade)) { ?>
                                    <?php if ($grade->grade == 99 and !$budget and $trip->is_approv == 1) { ?>
                                        <button type="button" id="sendBudget" class="btn btn-info" style="width: 100%">{{ __('trip_i.trc_send_budget') }}</button>
                                    <?php } ?>
                                <?php } ?>
                            </div>

                        </div>
                      </div>
                      <?php } ?>
                      <?php if (isset($budget)) { ?>
                      <div class="tab-pane" id="interation-center" aria-labelledby="interation-tab-center" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 widget-chat-card">
                                <div class="widget-chat widget-chat-messages">
                                  <div class="card">
                                    <div class="card-header border-bottom p-0">
                                      <div class="media m-75">
                                        <a class="media-left" href="JavaScript:void(0);">
                                            <div class="avatar mr-1 bg-success" style="width: 36px; height: 36px;">
                                                <span style="margin: 2px;" class="avatar-content">AG</span>
                                            </div>
                                        </a>
                                        <div class="media-body">
                                          <h6 class="media-heading mb-0 pt-25"><a href="javaScript:void(0);"><?= $agency_name ?></a>
                                          </h6>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card-body widget-chat-container widget-chat-scroll ps ps--active-y">
                                      <div class="chat-content">
                                        <?php if (!empty($budget->description)) { ?>
                                            <div class="badge badge-pill badge-light-secondary my-1"><?= $budget->description ?></div>
                                        <?php } ?>
                                        <?php foreach ($msgs as $key) { ?>
                                            <?php if ($key->type == 1) { ?>
                                        <div class="chat">
                                            <div class="chat-body">
                                                <div class="chat-message">
                                                    <p><?= $key->message ?></p>
                                                    <span class="chat-time"><?= date('d-m-Y H:i', strtotime($key->created_at)) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                            <div class="chat chat-left">
                                                <div class="chat-body">
                                                    <div class="chat-message">
                                                        <p><?= $key->message ?></p>
                                                        <span class="chat-time"><?= date('d-m-Y H:i', strtotime($key->created_at)) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 420px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 156px;"></div></div></div>
                                    <?php if (isset($grade)) { ?>
                                    <?php if ($grade->grade == 99 and isset($budget) and $trip->is_completed == 0) { ?>
                                    <div class="card-footer border-top p-1">
                                        <form class="d-flex align-items-center" action="/trip/agency/msg" id="sendMsg" method="post">
                                        <input type="hidden" name="type" value="1">
                                        <input type="hidden" name="trip_id" value="<?= $planid ?>">
                                        <input type="hidden" name="gen" value="<?= $budget->budget_gen ?>">

                                        <input type="text" name="msg" class="form-control widget-chat-message mx-75" placeholder="{{ __('trip_i.trc_42') }}">
                                        <button type="submit" class="btn btn-primary glow"><i class="bx bx-paper-plane"></i></button>
                                      </form>
                                    </div>
                                    <?php } ?>
                                    <?php } ?>

                                  </div>
                                </div>
                              </div>
                        </div>

                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        
        <?php if (isset($grade)) { ?>
            <div class="mb-2" style="width: 390px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99;">
            <?php if ($grade->grade == 99 and isset($budget) and $trip->is_completed == 0) { ?>
                
                    <button type="button" id="completedPlan" class="btn btn-danger mb-1 mr-2">{{ __('trip_i.trc_completed_trip') }}</button> <button type="button" id="rollbackPlan" class="btn btn-warning mb-1 min-width-125">{{ __('trip_i.trc_reopen_budget') }}</button>
                    <div class="text-center">
                        <div class="checkbox"><input type="checkbox" class="checkbox-input" value="1" id="iscredit" name="iscredit">
                            <label for="iscredit">{{ __('trip_i.trc_14') }}</label>
                        </div>
                    </div>
                
            <?php } ?>
            <?php if ($grade->grade == 99 and $trip->is_completed == 1) { ?>
                    <button type="button" id="reopenPlan" class="btn btn-info">
                        {{ __('trip_i.trc_31') }}
                    </button>
            <?php } ?>
            </div>
        <?php } ?>
      
        </div>

    <?php if (isset($grade)) { ?>
        <?php if ($trip->is_approv == 1 and $grade->grade == 99) { ?>
            <div class="modal fade text-left" id="modal-agency" tabindex="-1" role="dialog" aria-labelledby="modal-agency" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title white" id="modal-agency">{{ __('trip_i.trc_32') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label for="info_a">{{ __('trip_i.trc_33') }}</label>
                                    <textarea class="form-control" id="info_a" name="info_a" rows="6" placeholder="..."></textarea>
                                </fieldset>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="approvBudget();" class="btn btn-success ml-1" data-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('trip_i.trc_34') }}</span>
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>


<?php if (isset($grade)) { ?>
    <?php if ($grade->grade != 99 and $grade->can_approv == 1 or $user_grade->grade == 99 and $grade->can_approv == 1 and $t_user->r_code != Session::get('r_code')) { ?>
        <?php if ($trip->has_analyze == 1) { ?>
            <div class="mb-2 cursor-pointer" id="showAnalyze" style="position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
                <i class="bx bx-up-arrow-alt"></i>
                <br>Mostrar análise
            </div>

            <div class="card text-center" id="Analyze" style="width: 395px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; opacity: 0.9;z-index: 99;">
                <div class="card-content">
                    <button type="button" id="HAnalyze" class="close HideAnalyze" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                    <div class="card-body">    
                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-center">
                                <button type="button" class="btn btn-success" onclick="analyze(<?= $trip->id ?>, <?= $trip->position_analyze ?>)">Realizar análise</button> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade text-left" id="modal-reprov" tabindex="-1" role="dialog" aria-labelledby="modal-reprov" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                    <h5 class="modal-title white" id="modal-reprov">{{ __('trip_i.trc_15') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label for="r_val">{{ __('trip_i.trc_17') }}</label>
                                    <textarea class="form-control" id="r_val" name="r_val" rows="6" placeholder="..."></textarea>
                                </fieldset>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" id="reprov" class="btn btn-danger ml-1" data-dismiss="modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('trip_i.trc_8') }}</span>
                    </button>
                    </div>
                </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>

        <div class="modal fade text-left" id="budget_total" tabindex="-1" role="dialog" aria-labelledby="budget_total" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="budget_total">{{ __('trip_i.trc_45') }}</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-12">
                            <fieldset class="form-group">
                                <label for="total">{{ __('trip_i.trc_13') }}</label>
                                <input class="form-control" id="total" name="total" rows="6" placeholder="0.00">
                            </fieldset>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary ml-1 btn-sm" id="completed">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-sm-block d-none">{{ __('trip_i.trc_completed_trip') }}</span>
                  </button>
                </div>
            </div>
        </div>
    </div>

    <div class="customizer d-md-block text-center">
        <a onclick="rtd_analyzes(<?= $id ?>, 'App\\Model\\TripPlan');" style="writing-mode: vertical-lr;height: 200px;font-weight: bold;top: 40%;" class="customizer-toggle btn-historic-approv" href="javascript:void(0);">
            Histórico de aprovação
        </a>
    </div>

    @include('gree_i.misc.components.analyze.do_analyze.inputs')
    @include('gree_i.misc.components.analyze.do_analyze.script')
    @include('gree_i.misc.components.analyze.history.view')

<script>

    @include('gree_i.misc.components.analyze.history.script')

    var idagency = 0;
    var credit = 0;
    function approvAgency(id) {
        idagency = id;
        $("#modal-agency").modal();
    }

    function approvBudget() {
        Swal.fire({
            title: '<?= __('trip_i.trc_18') ?>',
            text: "<?= __('trip_i.trc_19') ?>",
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
                window.location.href = "/trip/approv/budget/"+ idagency +"/<?= $planid ?>?chat="+ $("#info_a").val();
            }
        });
    }

$(document).ready(function () {

//$(document).on('click', '[data-toggle="lightbox"]', function(event) {
//    event.preventDefault();
//    $(this).ekkoLightbox();
//});

// $(".chat").animate({ scrollTop: $(document).height() }, 1000);

$("#sendMsg").submit(function (e) { 
    
    if ($("input[name='msg']").val() == "") {
        return e.preventDefault();
    } else {
        block();
    }
    
});

$('#total').mask('00000.00', {reverse: true});

$("#sendBudget").click(function (e) { 
    Swal.fire({
        title: '<?= __('trip_i.trc_36') ?>',
        text: "<?= __('trip_i.trc_37') ?>",
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
                $("#agencyBudgets").submit();
            }
        })
    
    
    
});

$("#reopenPlan").click(function (e) { 
    Swal.fire({
        title: '<?= __('trip_i.trc_43') ?>',
        text: "<?= __('trip_i.trc_44') ?>",
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
                window.location.href = "/trip/plan/back-status/<?= $trip->id ?>";
            }
        })
    
});
$("#completed").click(function (e) { 
    $('#budget_total').modal('toggle');
    block(); 
    if ($("#iscredit").prop("checked")) {
        credit = 1;
    } else {
        credit = 0;
    }
    window.location.href = "/trip/plan/complete/<?= $planid ?>?total=" + $("#total").val() + "&iscredit=" + credit;
    
    
});
$("#completedPlan").click(function (e) { 
    Swal.fire({
        title: '<?= __('trip_i.trc_20') ?>',
        text: "<?= __('trip_i.trc_21') ?>",
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
                $('#budget_total').modal();
            }
        })
    
});

$("#rollbackPlan").click(function (e) { 
    Swal.fire({
        title: '<?= __('trip_i.trc_29') ?>',
        text: "<?= __('trip_i.trc_30') ?>",
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
                window.location.href = "/trip/plan/reopen/<?= $planid ?>";
            }
        })
    
});

    $("#reprov").click(function (e) { 
        $("#reason").val($("#r_val").val());
        $("#is_approv").val(0);
        if ($("#reason").val() == "") {

            error('<?= __('trip_i.trc_22') ?>');

            return;
        } else if ($("#password").val() == "") {
            
            error('<?= __('trip_i.trc_23') ?>');

            return;
        }

        $("#modal-reprov").modal('toggle');
        Swal.fire({
                title: '<?= __('trip_i.trc_24') ?>',
                text: "<?= __('trip_i.trc_25') ?>",
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
                        $("#AnalyzeForm").submit();
                    }
                })
    });

    $("#approv").click(function (e) {
        $("#is_approv").val(1);
        if ($("#password").val() == "") {
            
            error('<?= __('trip_i.trc_26') ?>');

            return;
        }
        
        $("#reason").val("");
        Swal.fire({
                title: '<?= __('trip_i.trc_27') ?>',
                text: "<?= __('trip_i.trc_28') ?>",
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
                        $("#AnalyzeForm").submit();
                    }
                })
        
    });

        // Perfect Scrollbar
        //------------------
        // Widget - User Details -Perfect Scrollbar X
        if ($('.widget-user-details .table-responsive').length > 0) {
            var user_details = new PerfectScrollbar('.widget-user-details .table-responsive');
        }

        // Widget - Card Overlay - Perfect Scrollbar X - on initial level
        if ($('.widget-overlay-content .table-responsive').length > 0) {
            var card_overlay = new PerfectScrollbar('.widget-overlay-content .tab-pane.active .table-responsive');
        }

        // Widget - Card Overlay - Perfect Scrollbar X - on active tab-pane
        $('.widget-overlay-content a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var card_overlay = new PerfectScrollbar('.widget-overlay-content .tab-pane.active .table-responsive');
        })

        // Widget - timeline perfect scrollbar initialization
        if ($(".widget-timeline").length > 0) {
            var widget_chat_scroll = new PerfectScrollbar(".widget-timeline", { wheelPropagation: false });
        }
        // Widget - chat area perfect scrollbar initialization
        if ($(".widget-chat-scroll").length > 0) {
            var widget_chat_scroll = new PerfectScrollbar(".widget-chat-scroll", { wheelPropagation: false });
        }
        // Widget - earnings perfect scrollbar initialization
        if ($(".widget-earnings-scroll").length > 0) {
            var widget_earnings = new PerfectScrollbar(".widget-earnings-scroll",
            // horizontal scroll with mouse wheel
            {
                suppressScrollY: true,
                useBothWheelAxes: true
            });
        }
        // Widget - chat autoscroll to bottom of Chat area on page initialization
        $(".widget-chat-scroll").animate({ scrollTop: $(".widget-chat-scroll")[0].scrollHeight }, 800);

    $("#HAnalyze").click(function (e) { 
        $("#Analyze").hide();
        
    });

    $("#showAnalyze").click(function (e) { 
        $("#Analyze").show();
        
    });

    setInterval(() => {
        $("#mAdmin").addClass('sidebar-group-active active');
        $("#mTrip").addClass('sidebar-group-active active');
    }, 100);
});
</script>
@endsection