<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the simple pagination links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    'email_update_error' => 'Email already used! Try again.',

    // SIDE TOP LEFT
    'registration' => 'Registration:',

    // SIDE TOP RIGHT
    'srt_notifications' => 'Notifications',
    'srt_see_all' => 'See all notifications',
    'srt_exit' => 'EXIT',
    'srt_new_notify' => 'New notifications',
    'srt_empty_notify' => 'No new notifications',
    'srt_mark_all' => 'Mark all as read',

        // NOTIFICATIONS

        // TRIP
        'n_trip_001_title' => 'Approved trip',
        'n_trip_001' => 'Your trip has been approved, check your travel list.',
        'n_trip_002_title' => 'Trip disapproved',
        'n_trip_002' => 'Unfortunately your travel request was not approved, check your travel list.',
        'n_trip_003_title' => 'Updated quote',
        'n_trip_003' => 'The agency :Agency updated its quote for trip :id please check the details.',
        'n_trip_004_title' => 'Travel ticket',
        'n_trip_004' => 'Your ticket is now available to download, check the details.',
        'n_trip_005_title' => 'Make the quotation',
        'n_trip_005' => 'New trip to make a quote, access the details and make the quote.',
        'n_trip_006_title' => 'Request for approval',
        'n_trip_006' => 'New application for travel approval, access details and perform analysis.',
        'n_trip_007_title' => 'Agency responded',
        'n_trip_007' => 'Agency :Agency sent a new message for trip :id see travel details.',

        // PROJECT
        'n_proj_001_title' => 'New task',
        'n_proj_001' => 'Check your to-do list and dont forget to accept or decline.',
        'n_proj_002_title' => 'Your task has been accepted',
        'n_proj_002' => 'The collaborator accepted his task, check his task list.',
        'n_proj_003_title' => 'Task was denied',
        'n_proj_003' => 'The employee denied his task, access the details and perform analysis.',
        'n_proj_004_title' => 'Task completion',
        'n_proj_004' => 'The employee wants to complete the task, access his list of tasks.',
        'n_proj_005_title' => 'Updated task',
        'n_proj_005' => 'Task history has been updated, go and check the changes.',
        'n_proj_006_title' => 'Task finished',
        'n_proj_006' => 'Your task has been completed, access the details to see more information.',
        'n_proj_007_title' => 'Finalization refused',
        'n_proj_007' => 'The completion of your task was refused, see more information in the details.',

        // CRON TASK
        'n_cron_001_title' => 'Delayed task',
        'n_cron_001' => 'The task :id of collaborator :Name is late, check the details of it.',

        // LENDING
        'n_lending_001_title' => 'Request lending',
        'n_lending_001' => 'New request lending of R$ :amount of the collaborator :Name, check the approval list.',
        'n_lending_002_title' => 'Lending approved',
        'n_lending_002' => 'Your lending #:id in amount of the R$ :amount was approved! Check your list.',
        'n_lending_003_title' => 'Lending disapproved',
        'n_lending_003' => 'Your lending #:id in amount of the R$ :amount was disapproved! Check your list.',
        'n_lending_004_title' => 'Lending transferred',
        'n_lending_004' => 'The lending #:id in amount of the R$ :amount was approved! Check your list.',
        'n_lending_005_title' => 'Lending transferred',
        'n_lending_005' => 'The lending #:id in amount of the R$ :amount was transfer! Check your list.',

        // REFUND
        'n_refund_001_title' => 'Request refund',
        'n_refund_001' => 'New request refund from :Name, check your list.',
        'n_refund_002_title' => 'Refund approved',
        'n_refund_002' => 'Your refund #:id was approved! Check your list.',
        'n_refund_003_title' => 'Refund disapproved',
        'n_refund_003' => 'Your refund #:id was disapproved! Check your list.',
        'n_refund_004_title' => 'Refund transferred',
        'n_refund_004' => 'The refund #:id was approved! Check your list of the transfer.',
        'n_refund_005_title' => 'Refund correction',
        'n_refund_005' => 'The value of some items in your refund has been changed. Check your list.',

        // PAYMENT
        'n_payment_001_title' => 'Request payment',
        'n_payment_001' => 'New payment of R$ :amount of the collaborator :Name, check the approval list.',
        'n_payment_002_title' => 'Payment approved',
        'n_payment_002' => 'Your payment #:id in amount of the R$ :amount was approved! Check your list.',
        'n_payment_003_title' => 'Payment disapproved',
        'n_payment_003' => 'Your payment #:id in amount of the R$ :amount was disapproved! Check your list.',
        'n_payment_004_title' => 'Payment transferred',
        'n_payment_004' => 'The payment #:id in amount of the R$ :amount was approved! Check your list.',
        'n_payment_005_title' => 'Payment transferred',
        'n_payment_005' => 'The payment #:id in amount of the R$ :amount was transfer! Check your list.',

        // Accountability
        'n_accountability_001_title' => 'Accountability Request',
        'n_accountability_001' => 'New Accountability request from :Name, check your list.',
        'n_accountability_002_title' => 'Accountability approved',
        'n_accountability_002' => 'Your Accountability #:id was approved! Check your list.',
        'n_accountability_003_title' => 'Accountability disapproved',
        'n_accountability_003' => 'Your Accountability #:id was disapproved! Check your list.',
        'n_accountability_004_title' => 'Accountability Reimbursement',
        'n_accountability_004' => 'Your Accountability #:id has been refunded! Check your list.',
        'n_accountability_005_title' => 'Accountability correction',
        'n_accountability_005' => 'The value of some items in your accountability has changed. Check your list.',

    // MISC
    'btn_next' => 'Next',
    'btn_previous' => 'Previous',
    'btn_confirm' => 'Confirm',
    'btn_cancel' => 'Cancel',
    'op_edit' => 'Edit',
    'op_delete' => 'Delete',
    'op_see_routes' => 'View routes',
    'op_ticket' => 'Ticket',
    'op_hotel' => 'Hotel',
    'op_history' => 'View History',
    'op_inactive' => 'desactive',
    'op_active' => 'Active',

    // FOOTER
    'footer_description' => 'Internal access system.',

    // MENU SIDE LEFT
    'menu_news' => 'News & Alerts',
    'menu_news_subtitle' => 'Everything that goes through Gree',
    'menu_news_post' => 'New post',
    'menu_news_posts' => 'All posts',
    'menu_news_publish' => 'Publications',
    'menu_news_author' => 'Authors',
    'menu_news_list_transmission' => 'Transmission list',
    'menu_news_categories' => 'Categories',
    'menu_admin' => 'Administration',
    'menu_my_profile' => 'My profile',
    'menu_my_task' => 'Tasks',
    'menu_users' => 'Users',
    'menu_users_new' => 'New user',
    'menu_users_list' => 'List user',
    'menu_users_log' => 'Log users',
    'menu_trip' => 'Trip',
    'menu_trip_view' => 'Approve requests',
    'menu_trip_view_subtitle' => 'Approve or disapprove requests',
    'menu_trip_view_approv' => 'All requests',
    'menu_trip_view_approv_subtitle' => 'Flights available for budget',
    'menu_trip_agency' => 'See agencies',
    'menu_trip_agency_subtitle' => 'Create or edit!',
    'menu_trip_credit' => 'Credited trips',
    'menu_trip_credit_subtitle' => 'Buy other tickets with these trips.',
    'menu_trip_my' => 'My plans',
    'menu_trip_my_subtitle' => 'Submit your routes for approval',
    'menu_trip_all' => 'All plans',
    'menu_trip_all_subtitle' => 'Verify all plans',
    'menu_trip_dashboard' => 'Report',
    'menu_trip_dashboard_subtitle' => 'Flight information already done the budget.',
    'menu_trip_new' => 'New planning',
    'menu_trip_new_subtitle' => 'Create your entire planning for the month.',
    'menu_trip_export' => 'Export data',
    'menu_trip_export_subtile' => 'Download excel data from trips made.',
    'menu_lending' => 'Lending',
    'menu_lending_report' => 'Report',
    'menu_lending_new' => 'New lending',
    'menu_lending_my' => 'My lending',
    'menu_lending_approv' => 'Approv lending',
    'menu_lending_all' => 'All lending',
    'menu_lending_transfer' => 'Transfer lending',
    'menu_lending_export' => 'Export data',
    'menu_project' => 'Task',
    'menu_project_new' => 'New Task',
    'menu_project_my' => 'My Tasks',
    'menu_project_approv' => 'Approve Tasks',
    'menu_project_view' => 'View Tasks',
    'menu_project_export' => 'Export data',
    'menu_commercial' => 'Commercial',
    'menu_homeoffice' => 'Home office',
    'menu_homeoffice_cron' => 'Work stopwatch',
    'menu_homeoffice_report' => 'My reports',
    'menu_homeoffice_data' => 'Bank of hours',
    'menu_homeoffice_online' => 'Employees working',


    // DATA TABLE LANG
    'dtbl_search' => 'Search:',
    'dtbl_zero_records' => 'No search-based results',
    'dtbl_info' => 'showing _START_ of _END_ of the total: _TOTAL_',
    'dtbl_info_empty' => 'showing 0 of 0 ',
    'dtbl_info_filtred' => '(Total filter: _MAX_)',
    
    // MODAL INPUT EMAIL
    'mie_finalized' => 'Completed',
    'mie_input_email' => 'Your email',
    'mie_description' => 'First access!',
    'mie_ps' => 'Enter your registration email, no need to type @ and the like.',
    'mie_title' => 'UPDATE ACCOUNT',


    // VERSION LANG MODAL
    'vlm_version' => 'Version',
    'vlm_close' => 'CLOSE',
    'vlm_updated' => 'UPDATES',
    'vlm_version_title_1' => 'News',
    'vlm_version_body_1' => 'Check out our news portal about what happens inside and outside of gree, enjoy and interact with us about what you think about certain news articles published by us.',
    'vlm_version_title_2' => 'Trip',
    'vlm_version_body_2' => 'Need to schedule your trip out or even to another state, you can now place the order here on the platform and await approval dynamically.',

    // DROPDOWN SECTOR AND SUB SECTOR
    'sct_name_1' => 'Commercial (CRAC)',
    'sct_name_2' => 'Industrial',
    'sct_name_3' => 'Financial',
    'sct_name_4' => 'Shipping & Receiving',
    'sct_name_5' => 'Import',
    'sct_name_6' => 'Administration',
    'sct_name_7' => 'Human Resources',
    'sct_name_8' => 'Shopping',
    'sct_name_9' => 'TI',
    'sct_name_10' => 'Maintenance',
    'sct_name_11' => 'Reception',
    'sct_name_12' => 'Commercial (CAC)',
    'sct_name_13' => 'Commercial International',
    'sct_name_14' => 'Production',
    'sct_name_15' => 'Engineering',
    'sct_name_16' => 'After sales',
    'sct_name_17' => 'Technical assistance',
    'sct_name_18' => 'SAC',
    'sct_name_19' => 'P&D',
    'sct_name_20' => 'Certification',
    'sct_name_21' => 'Training',
    'sct_name_22' => 'legal department',
    'sct_name_23' => 'Quality',
    'sct_name_24' => 'Logistics',
    'sct_name_25' => 'Trade',
    'sct_name_99' => 'General',
    'sct_name_100' => 'Marketing Internal',

    'sct_2_name_1' => 'Imported products',
    'sct_2_name_2' => 'Marketing and training',
    'sct_2_name_3' => 'Industrial products',
    'sct_2_name_4' => 'Technical assistance',
    'sct_2_name_5' => 'Engineering',
    'sct_2_name_6' => 'Production',
    'sct_2_name_7' => 'CQ',

    'sct_3_name_1' => 'Product certification',
    'sct_3_name_2' => 'Assist. Tec. of residential and commercial products',
    'sct_3_name_3' => 'SAC',

    // NOT PERMISSIONS
    'not_permissions' => 'You do not have permission to access this page.',

];