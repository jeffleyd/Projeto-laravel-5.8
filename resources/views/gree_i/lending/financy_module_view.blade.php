
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<meta name="robots" content="noindex, nofollow">
	
    <title>Gree - System Internal</title>
    <link rel="apple-touch-icon" href="/admin/app-assets/images/ico/favicon-192x192.png">
    <link rel="shortcut icon" type="image/x-icon" href="/admin/app-assets/images/ico/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/dragula.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/pages/dashboard-analytics.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/extensions/toastr.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/all.css">
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css">

</head>
<!-- END: Head-->
<?php if ($perm == 1) { ?>
    
            <h3 class="block-title">{{ __('trip_i.tmp_identification') }} #<?= $planid ?></h3>
            <table id="list-datatable" class="table">
                <thead>
                    <tr>
                        <th class="text-center"></th>
                        <th>{{ __('trip_i.tmptp_reason') }}</th>
                        <th>{{ __('trip_i.tmptp_orin') }}</th>
                        <th>{{ __('trip_i.tmptp_destiny') }}</th>
                        <th>{{ __('trip_i.tmptp_hotel') }}</th>
                        <th>{{ __('trip_i.tmptp_dispatch') }}</th>
                        <th>{{ __('trip_i.tmptp_peoples') }}</th>
                        <th>{{ __('trip_i.tmptp_situation') }}</th>
                        <th>{{ __('trip_i.action_details') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trips as $key) { ?>
                    <tr>
                        <td class="text-center"><i class="bx bx-down-arrow-alt"></i></td>
                        <td><?= $key->goal ?></td>
                        <td>
                            <small><i><?= date('Y-m-d', strtotime($key->origin_date)) ?></i></small>
                            <br><span class="font-w600">UF: <?= GetStateName($key->origin_country, $key->origin_state) ?></span>
                            <div class="text-muted"><?= $key->origin_city ?></div>
                        </td>
                        <td>
                            <small><i><?= date('Y-m-d', strtotime($key->destiny_date)) ?></i></small>
                            <br><span class="font-w600">UF: <?= GetStateName($key->destiny_country, $key->destiny_state) ?></span>
                            <div class="text-muted"><?= $key->destiny_city ?></div>
                        </td>
                        <td><?php if ($key->has_hotel == 1) { echo __('trip_i.tmp_yes'); } else { echo __('trip_i.tmp_no'); } ?></td>
                        <td><?= $key->dispatch ?></td>
                        <td><?= $key->peoples + 1 ?></td>
                        <td>
                            <?php if ($key->is_completed == 1) { ?>
                                <span class="badge badge-light-info">{{ __('trip_i.tmptp_status_0') }}</span></td>
                            <?php } else if ($key->is_cancelled == 1) { ?>
                                <span class="badge badge-light-danger">{{ __('trip_i.tntp_status_6') }}</span></td>
                            <?php } else if ($key->is_approv == 1) { ?>
                                <span class="badge badge-light-success">{{ __('trip_i.tmptp_status_1') }}</span></td>
                            <?php } else if ($key->is_reprov == 1) { ?>
                                <span class="badge badge-light-danger">{{ __('trip_i.tmptp_status_2') }}</span></td>
                            <?php } else if ($key->has_analyze == 1) { ?>
                                <span class="badge badge-light-warning">{{ __('trip_i.tmptp_status_3') }}</span></td>
                            <?php } else { ?>
                                <span class="badge badge-light-secondary">{{ __('trip_i.tmptp_status_4') }}</span></td>
                            <?php } ?>
                        <td>
                            <a href="/trip/review/<?= $key->id ?>" href="javascript:void(0)"><i class="bx bx-detail mr-1"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        
<?php } ?>