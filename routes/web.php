
<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// GREE INTERNAL ROUTES

Route::get('/locale/{locale}', 'MiscController@changeLocale');

Route::get('/', 'UserIController@nothing')->middleware('isLogged');

Route::get('/states', 'UserIController@states')->middleware('isLogged');

Route::get('/logout', 'UserIController@logout');

Route::get('/login', array('as' => 'login', 'uses' => 'UserIController@login'))->middleware('Lang', 'hasLogin');

Route::post('/login/verify', 'UserIController@verifyLogin')->middleware('Lang', 'hasLogin');

Route::post('/optauth/verify', 'UserIController@verifyOtpAuth')->middleware('Lang', 'hasLogin');

Route::get('/register', array('as' => 'register', 'uses' => 'UserIController@userRegister'))->middleware('Lang', 'hasLogin');

Route::post('/register/create', array('as' => 'register/create', 'uses' => 'UserIController@userRegister_do'))->middleware('Lang', 'hasLogin');

Route::get('/main', array('as' => 'main', 'uses' => 'UserIController@main'))->middleware('Lang', 'isLogged');

Route::get('/news', array('as' => 'news', 'uses' => 'UserIController@news'))->middleware('Lang', 'isLogged');

Route::get('/news/postit/edit_do', array('as' => 'news', 'uses' => 'UserIController@newsPostItEdit_do'))->middleware('Lang', 'isLogged');

Route::get('/news/postit/next', array('as' => 'news', 'uses' => 'UserIController@newsPostItNext'))->middleware('Lang', 'isLogged');

Route::get('/news/postit/delete/{id}', array('as' => 'news', 'uses' => 'UserIController@newsPostItDelete'))->middleware('Lang', 'isLogged');

Route::get('/news/postit/edit', array('as' => 'news', 'uses' => 'UserIController@newsPostItEdit'))->middleware('Lang', 'isLogged');

Route::get('/post/single/{id}', array('as' => 'post/single', 'uses' => 'UserIController@postSingle'))->middleware('Lang', 'isLogged');

Route::get('/posts/segment/{type}', array('as' => 'posts/segment', 'uses' => 'UserIController@postsSegment'))->middleware('Lang', 'isLogged');

Route::get('/posts/segment', array('as' => 'posts/segment', 'uses' => 'UserIController@postsSegmentAll'))->middleware('Lang', 'isLogged');

Route::post('/account/email', 'UserIController@updateEmail');

Route::get('/notifications/get', array('as' => 'notifications/get', 'uses' => 'UserIController@notificationsGet'));

Route::post('/notifications/token/{type}', array('as' => 'notifications/token', 'uses' => 'UserIController@notificationsToken'));

Route::any('/2fa/update', array('as' => '2fa/update', 'uses' => 'UserIController@active2FAUser'));

Route::post('/notifications/read', array('as' => 'notifications/read', 'uses' => 'UserIController@notificationsRead'));

Route::post('/notifications/read/only', array('as' => 'notifications/read/only', 'uses' => 'UserIController@notificationsReadOnly'));

Route::get('/notifications/change/status/{id}/{status}', array('as' => 'notifications/change/status', 'uses' => 'UserIController@notificationsChangeStatus'))->middleware('Lang', 'isLogged');

Route::get('/status', 'UserIController@systemStatus');


// USER URLs

Route::get('/user/list', array('as' => 'user/list', 'uses' => 'UserIController@userList'))->middleware('Lang', 'isLogged', 'hasPerm:2,1,0');

Route::get('/user/log', array('as' => 'user/log', 'uses' => 'UserIController@userLog'))->middleware('Lang', 'isLogged', 'hasPerm:2,1,0');

Route::get('/user/notifications', array('as' => 'user/notifications', 'uses' => 'UserIController@userNotify'))->middleware('Lang', 'isLogged');

Route::get('/user/unlock/{id}', 'UserIController@userUnlock')->middleware('isLogged', 'hasPerm:2,1,0');

Route::get('/user/edit/{rcode}', 'UserIController@userEdit')->middleware('Lang', 'isLogged');

Route::get('/user/view/{rcode}', 'UserIController@userView')->middleware('Lang');

Route::post('/user/edit/do', 'UserIController@userEdit_do')->middleware('isLogged');

Route::post('/user/edit/holiday/do', 'UserIController@userHoliday')->middleware('isLogged');

Route::post('/user/ajax/immediate', array('as' => 'user/ajax/immediate', 'uses' => 'UserIController@verifyImd'));

Route::get('/user/reset/auth/{rcode}', 'UserIController@userResetAuth');

// TRIP URLs

Route::get('/trip/dashboard', array('as' => 'trip/dashboard', 'uses' => 'UserIController@tripDashboard'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/view', array('as' => 'trip/view', 'uses' => 'UserIController@tripView'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/view/all', array('as' => 'trip/view/all', 'uses' => 'UserIController@tripViewApprov'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,1');

Route::get('/trip/new', array('as' => 'trip/new', 'uses' => 'UserIController@tripNew'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::post('/trip/new/do', 'UserIController@tripNew_do')->middleware('isLogged', 'hasPerm:1,0,0');

Route::get('/trip/my', array('as' => 'trip/my', 'uses' => 'UserIController@tripMy'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/all', array('as' => 'trip/all', 'uses' => 'UserIController@tripAll'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,1');

Route::get('/trip/detail/{id}', array('as' => 'trip/detail', 'uses' => 'UserIController@tripDetail'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/review/{id}', array('as' => 'trip/review', 'uses' => 'UserIController@tripReview'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/plan/back-status/{id}', array('as' => 'trip/plan/back-status', 'uses' => 'UserIController@tripPlanBackStatus'))->middleware('isLogged', 'hasPerm:1,1,0');

Route::get('/trip/plan/complete/{id}', array('as' => 'trip/plan/complete', 'uses' => 'UserIController@tripPlanComplete'))->middleware('isLogged', 'hasPerm:1,1,0');

Route::get('/trip/plan/reopen/{id}', array('as' => 'trip/plan/reopen', 'uses' => 'UserIController@tripPlanReopen'))->middleware('isLogged', 'hasPerm:1,1,0');

Route::post('/trip/send/budget', array('as' => 'trip/send/budget', 'uses' => 'UserIController@tripSendBudget'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,0');

Route::get('/trip/approv/budget/{id}/{plan}', array('as' => 'trip/approv/budget', 'uses' => 'UserIController@tripApprovBudget'));

Route::get('/trip/agency/budget/{gen}/{id}', array('as' => 'trip/agency/budget', 'uses' => 'UserIController@tripBudget'));

Route::post('/trip/agency/msg', array('as' => 'trip/agency/msg', 'uses' => 'UserIController@tripSendMsg'));

Route::get('/trip/credits', array('as' => 'trip/credits', 'uses' => 'UserIController@tripCredits'));

Route::get('/trip/credits_do/{id}', array('as' => 'trip/credits_do', 'uses' => 'UserIController@tripCredits_do'));

Route::post('/trip/agency/budget/do/{gen}/{id}', array('as' => 'trip/agency/budget/do', 'uses' => 'UserIController@tripAgencyBudget_do'));

Route::post('/trip/agency/people/do/{gen}/{id}', array('as' => 'trip/agency/people/do', 'uses' => 'UserIController@tripABPeoples'));

Route::get('/trip/agency', array('as' => 'trip/agency', 'uses' => 'UserIController@tripAgencyList'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,0');

Route::get('/trip/agency/delete/{id}', array('as' => 'trip/agency/delete', 'uses' => 'UserIController@tripAgencyDelete'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,0');

Route::post('/trip/agency/update', array('as' => 'trip/agency/update', 'uses' => 'UserIController@tripAgencyUpdate'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,0');

Route::get('/trip/analyze/{rcode}/{idplan}', array('as' => 'trip/analyze', 'uses' => 'UserIController@tripAnalyze'))->middleware('Lang');

Route::post('/trip/analyze/update', array('as' => 'trip/analyze/update', 'uses' => 'UserIController@tripAnalyze_do'))->middleware('Lang');

Route::post('/trip/analyze/update/single', array('as' => 'trip/analyze/update/single', 'uses' => 'UserIController@tripAnalyzeSingle_do'))->middleware('Lang');

Route::post('/trip/analyze-in/update', array('as' => 'trip/analyze-in/update', 'uses' => 'UserIController@tripAnalyzeInternal_do'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,1');

Route::get('/trip/request/approv/{id}/{plan}', array('as' => 'trip/request/approv', 'uses' => 'UserIController@tripRequestApprov'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/delete/{id}', array('as' => 'trip/delete', 'uses' => 'UserIController@tripDelete'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/edit/{id}', array('as' => 'trip/edit', 'uses' => 'UserIController@tripEdit'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/edit/route/{id}', array('as' => 'trip/edit/route', 'uses' => 'UserIController@tripEditManager'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,0');

Route::get('/trip/cancel/route/{id}', array('as' => 'trip/cancel/route', 'uses' => 'UserIController@tripCancelManager'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,0');

Route::post('/trip/edit/route_do/{id}', array('as' => 'trip/edit/route_do', 'uses' => 'UserIController@tripEditManager_do'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,0');

Route::post('/trip/edit/route/peoples_do/{id}', array('as' => 'trip/edit/route/peoples_do', 'uses' => 'UserIController@tripMngPeoples'))->middleware('Lang', 'isLogged', 'hasPerm:1,1,0');

Route::post('/trip/update/{id}', array('as' => 'trip/update', 'uses' => 'UserIController@tripUpdate'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/export/view', array('as' => 'trip/export/view', 'uses' => 'UserIController@tripExportView'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

Route::get('/trip/export', array('as' => 'trip/export', 'uses' => 'UserIController@tripExport'))->middleware('Lang', 'isLogged', 'hasPerm:1,0,0');

// TASKS

Route::get('/task/{id}', array('as' => 'task', 'uses' => 'UserIController@taskEdit'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,1');

Route::get('/task/change/{id}', array('as' => 'task/change', 'uses' => 'UserIController@taskStatus'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,1');

Route::post('/task_do', array('as' => 'task_do', 'uses' => 'UserIController@taskEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::get('/task/view/my', array('as' => 'task/view/my', 'uses' => 'UserIController@taskMy'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::get('/task/view/history/{id}', array('as' => 'task/view/history', 'uses' => 'UserIController@taskListHistory'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::get('/task/accept/{id}', array('as' => 'task/accept', 'uses' => 'UserIController@taskAnalyze'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::get('/task/resend/{id}', array('as' => 'task/resend/', 'uses' => 'UserIController@taskReSend'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::post('/task/update/history/{id}', array('as' => 'task/update/history', 'uses' => 'UserIController@taskUpdateHistory'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::get('/task/view/export', array('as' => 'task/view/export', 'uses' => 'UserIController@taskExportView'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::get('/task/download/export', array('as' => 'task/download/export', 'uses' => 'UserIController@taskExport'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::get('/task/completed/{id}/{type}', array('as' => 'task/completed/', 'uses' => 'UserIController@taskCompleted'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::post('/task/update/subtask', array('as' => 'task/update/subtask', 'uses' => 'UserIController@taskUpdateSubTask'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

Route::get('/task/delete/subtask/{id}', array('as' => 'task/delete/subtask', 'uses' => 'UserIController@taskDeleteSubTask'))->middleware('Lang', 'isLogged', 'hasPerm:3,0,0');

// TI

Route::get('/ti/edit/{id}', array('as' => 'ti/edit', 'uses' => 'UserIController@tiEdit'))->middleware('Lang', 'isLogged', 'hasPerm:4,0,0');

Route::post('/ti/edit_do', array('as' => 'ti/edit/do', 'uses' => 'UserIController@tiEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:4,0,0');

Route::get('/ti/version/edit/{id}', array('as' => 'ti/version/edit', 'uses' => 'UserIController@tiVersionEdit'))->middleware('Lang', 'isLogged', 'hasPerm:4,0,0');

Route::post('/ti/version/edit_do', array('as' => 'ti/version/edit/do', 'uses' => 'UserIController@tiVersionEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:4,0,0');

Route::get('/ti/developer/edit/{id}', array('as' => '/ti/developer/edit', 'uses' => 'UserIController@tiDevEdit'))->middleware('Lang', 'isLogged');

Route::get('/ti/developer/all', array('as' => '/ti/developer/all', 'uses' => 'UserIController@tiDevAll'))->middleware('Lang', 'isLogged');

Route::post('/ti/developer/update', array('as' => 'ti/developer/update', 'uses' => 'UserIController@tiDevUpdate'))->middleware('Lang', 'isLogged');

Route::get('/ti/developer/track/{id}', array('as' => '/ti/developer/track', 'uses' => 'UserIController@tiDevTrack'))->middleware('Lang', 'isLogged');

Route::post('/ti/developer/send/history', array('as' => 'ti/developer/send/history', 'uses' => 'UserIController@tiDevSendHistory'))->middleware('Lang', 'isLogged', 'hasPerm:4,1,1');

Route::get('/ti/developer/monitor', array('as' => 'ti/developer/monitor', 'uses' => 'UserIController@tiDevMonitor'))->middleware('Lang', 'isLogged', 'hasPerm:4,0,1');

Route::get('/ti/developer/analyze/{id}/{type}', array('as' => 'ti/developer/analyze', 'uses' => 'UserIController@tiDevAnalyze'))->middleware('Lang', 'isLogged', 'hasPerm:4,0,1');

Route::get('/ti/developer/approv', array('as' => 'ti/developer/approv', 'uses' => 'UserIController@tiDevApprov'))->middleware('Lang', 'isLogged', 'hasPerm:4,0,1');

Route::get('/ti/support', array('as' => '/support/ti', 'uses' => 'TiMaintenanceController@MaintenanceList'))->middleware('Lang', 'isLogged');

Route::get('/ti/maintenance/all', array('as' => '/ti/maintenance/all', 'uses' => 'TiMaintenanceController@MaintenanceList'))->middleware('Lang', 'isLogged');

Route::get('/ti/maintenance/edit/{id}', array('as' => '/ti/maintenance/edit', 'uses' => 'TiMaintenanceController@MaintenanceEdit'))->middleware('Lang', 'isLogged');

Route::post('/ti/maintenance/update', array('as' => '/ti/maintenance/update', 'uses' => 'TiMaintenanceController@MaintenanceEdit_do'))->middleware('Lang', 'isLogged');

Route::get('/ti/maintenance/info/{id}', array('as' => '/ti/maintenance/info', 'uses' => 'TiMaintenanceController@MaintenanceInfo'))->middleware('Lang', 'isLogged');

Route::post('/ti/maintenance/note', array('as' => '/ti/maintenance/note', 'uses' => 'TiMaintenanceController@MaintenanceNote_do'))->middleware('Lang', 'isLogged');

Route::post('/ti/maintenance/replie', array('as' => '/ti/maintenance/replie', 'uses' => 'TiMaintenanceController@MaintenanceReplies_do'))->middleware('Lang', 'isLogged');

Route::post('/ti/maintenance/info/ajax', array('as' => '/ti/maintenance/info/ajax', 'uses' => 'TiMaintenanceController@MaintenanceInfo_ajax'))->middleware('Lang', 'isLogged');

Route::get('/ti/maintenance/note/delete/{id}', array('as' => '/ti/maintenance/note/delete', 'uses' => 'TiMaintenanceController@MaintenanceNoteDelete'))->middleware('Lang', 'isLogged');


// BLOG

Route::get('/blog/edit/{id}', array('as' => 'blog', 'uses' => 'UserIController@blogEdit'))->middleware('Lang', 'isLogged', 'hasPerm:5,1,0');

Route::get('/blog/author/all', array('as' => 'blog/author/all', 'uses' => 'UserIController@authorList'))->middleware('Lang', 'isLogged', 'hasPerm:5,0,1');

Route::get('/blog/author/delete/{id}', array('as' => 'blog/author/delete', 'uses' => 'UserIController@authorDelete'))->middleware('Lang', 'isLogged', 'hasPerm:5,0,1');

Route::post('/blog/author/update', array('as' => 'blog/author/update', 'uses' => 'UserIController@authorEdit'))->middleware('Lang', 'isLogged', 'hasPerm:5,0,1');

Route::post('/blog/update/do', array('as' => 'blog/update/do', 'uses' => 'UserIController@blogUpdate'))->middleware('Lang', 'isLogged', 'hasPerm:5,1,0');

Route::get('/blog/view/all', array('as' => 'blog/view/all', 'uses' => 'UserIController@blogAll'))->middleware('Lang', 'isLogged', 'hasPerm:5,1,0');

Route::get('/blog/delete/{id}', array('as' => 'blog/delete', 'uses' => 'UserIController@blogDelete'))->middleware('Lang', 'isLogged', 'hasPerm:5,1,0');

Route::get('/blog/view/categories', array('as' => '/blog/view/categories', 'uses' => 'UserIController@blogCategoryAll'))->middleware('Lang', 'isLogged', 'hasPerm:5,1,0');

Route::post('/blog/category/do', array('as' => 'blog/category/do', 'uses' => 'UserIController@blogCategoryUpdate'))->middleware('Lang', 'isLogged', 'hasPerm:5,1,0');

Route::get('/blog/transmission', array('as' => 'blog/transmission', 'uses' => 'UserIController@transmissionList'))->middleware('Lang', 'isLogged', 'hasPerm:5,0,1');

Route::get('/blog/transmission/delete/{id}', array('as' => 'blog/transmission/delete', 'uses' => 'UserIController@transmissionDelete'))->middleware('Lang', 'isLogged', 'hasPerm:5,0,1');

Route::post('/blog/transmission/update', array('as' => 'blog/transmission/update', 'uses' => 'UserIController@transmissionEdit'))->middleware('Lang', 'isLogged', 'hasPerm:5,0,1');

// SAC

Route::get('/sac/monitor', array('as' => 'sac/monitor', 'uses' => 'UserIController@sacMonitor'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/authorized/monitor', 'UserIController@sacAuthorizedMonitor')->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/monitor/ajax', array('as' => 'sac/monitor/ajax', 'uses' => 'UserIController@sacMonitor_ajax'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/monitor/filter/ajax', array('as' => 'sac/monitor/filter/ajax', 'uses' => 'UserIController@sacMonitorFilter_ajax'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/comunication/authorized/all', array('as' => 'sac/comunication/authorized/all', 'uses' => 'UserIController@sacComunicationAuthorizedAll'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/comunication/authorized/edit/{id}', array('as' => 'sac/comunication/authorized/edit', 'uses' => 'UserIController@sacComunicationAuthorizedEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::post('/sac/comunication/authorized/update', array('as' => 'sac/comunication/authorized/update', 'uses' => 'UserIController@sacComunicationAuthorizedEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/comunication/authorized/delete/{id}', array('as' => 'sac/comunication/authorized/delete', 'uses' => 'UserIController@sacComunicationAuthorizedDelete'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/faq/all', array('as' => 'sac/faq/all', 'uses' => 'UserIController@sacFaqAll'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/faq/edit/{id}', array('as' => 'sac/faq/edit', 'uses' => 'UserIController@sacFaqEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::post('/sac/faq/update', array('as' => 'sac/faq/update', 'uses' => 'UserIController@sacFaqEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/faq/delete/{id}', array('as' => 'sac/faq/delete', 'uses' => 'UserIController@sacFaqDelete'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/expedition/pending', array('as' => 'sac/expedition/pending', 'uses' => 'UserIController@sacExpeditionPending'))->middleware('Lang', 'isLogged', 'hasPerm:17,0,0');

Route::post('/sac/expedition/pending_do', array('as' => 'sac/expedition/pending_do', 'uses' => 'UserIController@sacExpeditionPending_do'))->middleware('Lang', 'isLogged', 'hasPerm:17,0,0');

Route::get('/sac/expedition/track', array('as' => 'sac/expedition/track', 'uses' => 'UserIController@sacExpeditionTrack'))->middleware('Lang', 'isLogged', 'hasPerm:17,0,0');

Route::get('/sac/expedition/track_do', array('as' => 'sac/expedition/track_do', 'uses' => 'UserIController@sacExpeditionTrack_do'))->middleware('Lang', 'isLogged', 'hasPerm:17,0,0');

Route::get('/sac/expedition/track_part/{id}/{is_expedition}', array('as' => 'sac/expedition/track_part', 'uses' => 'UserIController@sacExpeditionTrackParts'))->middleware('Lang', 'isLogged', 'hasPerm:17,0,0');

Route::get('/sac/config', array('as' => 'sac/config', 'uses' => 'UserIController@sacConfig'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/config_do', array('as' => 'sac/config_do', 'uses' => 'UserIController@sacConfig_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/edit/{id}', array('as' => 'sac/warranty/edit', 'uses' => 'UserIController@sacWarrantyEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/edit_do', array('as' => 'sac/warranty/edit_do', 'uses' => 'UserIController@sacWarrantyEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/notify/assist/{id}', array('as' => 'sac/warranty/notify/assist', 'uses' => 'UserIController@sacWarrantyNotifyAssist'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/all', array('as' => 'sac/warranty/all', 'uses' => 'UserIController@sacWarrantyAll'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/attachmentAll', array('as' => 'sac/warranty/attachmentAll', 'uses' => 'UserIController@sacAttachmentAll'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/os/all', array('as' => 'sac/warranty/os/all', 'uses' => 'UserIController@sacWarrantyOsAll'))->middleware('Lang', 'isLogged', 'hasPerm:16,0,0');

Route::get('/sac/warranty/os/monitor', array('as' => 'sac/warranty/os/monitor', 'uses' => 'UserIController@sacMonitorOS'))->middleware('Lang', 'isLogged', 'hasPerm:16,0,0');

Route::get('/sac/warranty/os/monitor/filter', array('as' => 'sac/warranty/os/monitor/filter', 'uses' => 'UserIController@sacMonitorFilterOs_ajax'))->middleware('Lang', 'isLogged', 'hasPerm:16,0,0');

Route::get('/sac/warranty/os/all/ajax', array('as' => 'sac/warranty/os/all/ajax', 'uses' => 'UserIController@sacWarrantyOsAllAjax'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/os/all/model/ajax', array('as' => 'sac/warranty/os/all/model/ajax', 'uses' => 'UserIController@sacWarrantyOsAllModelAjax'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/os/analyze', array('as' => 'sac/warranty/os/analyze', 'uses' => 'UserIController@sacWarrantyOsAnalyze'))->middleware('Lang', 'isLogged', 'hasPerm:16,0,0');

Route::post('/sac/warranty/os/analyzeTechnique', array('as' => '/sac/warranty/os/analyzeTechnique', 'uses' => 'UserIController@sacOsAnalyzeanalyzeTechnique'))->middleware('Lang', 'isLogged', 'hasPerm:16,0,0');

Route::post('/sac/warranty/os/split', array('as' => 'sac/warranty/os/split', 'uses' => 'UserIController@sacWarrantyOsSplit'))->middleware('Lang', 'isLogged', 'hasPerm:16,0,0');

Route::post('/sac/warranty/os/export/split', array('as' => 'sac/warranty/os/export/split', 'uses' => 'UserIController@sacWarrantyOsSplitExport'))->middleware('Lang', 'isLogged', 'hasPerm:16,0,0');

Route::get('/sac/warranty/os/status/{status}/{id}', array('as' => 'sac/warranty/os/status', 'uses' => 'UserIController@sacWarrantyOsStatus'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/os/print/{id}', array('as' => 'sac/warranty/os/print', 'uses' => 'UserIController@sacWarrantyOsPrint'))->middleware('Lang', 'isLogged');

Route::post('/sac/warranty/os/payment', array('as' => 'sac/warranty/os/payment', 'uses' => 'UserIController@sacWarrantyOsPayment'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::post('/sac/warranty/os/payment/status', array('as' => 'sac/warranty/os/payment/status', 'uses' => 'UserIController@sacWarrantyOsPaymentStatus'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/warranty/os/request/nf/{id}', array('as' => 'sac/warranty/os/request/nf', 'uses' => 'UserIController@sacWarrantyOsRequestNf'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/warranty/os/paid', array('as' => 'sac/warranty/os/paid', 'uses' => 'UserIController@sacWarrantyOsPaid'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/warranty/os/model/{id}', array('as' => 'sac/warranty/os/model', 'uses' => 'UserIController@sacWarrantyOsModel'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/os/model/ajax', array('as' => 'sac/warranty/os/model/ajax', 'uses' => 'UserIController@sacWarrantyOsModelAjax'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/os/model/delete/{os_id}/{model_id}', array('as' => '/sac/warranty/os/model/delete', 'uses' => 'UserIController@sacWarrantyOsModelDelete'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/os/interactive/{id}', array('as' => 'sac/warranty/os/interactive', 'uses' => 'UserIController@sacWarrantyOSInteractive'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/os/interactive/edit', array('as' => 'sac/warranty/os/interactive/edit', 'uses' => 'UserIController@sacWarrantyOSInteractiveEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/os/msg', array('as' => 'sac/warranty/msg/os', 'uses' => 'UserIController@sacWarrantySendOsMsg'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/ob', array('as' => 'sac/warranty/ob', 'uses' => 'UserIController@sacWarrantyOb'))->middleware('Lang', 'isLogged', 'hasPerm:6,1,0');

Route::post('/sac/warranty/ob_do', array('as' => 'sac/warranty/ob_do', 'uses' => 'UserIController@sacWarrantyOb_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,1,0');

Route::post('/sac/warranty/ob/send/print', array('as' => 'sac/warranty/ob/send/print', 'uses' => 'UserIController@sacWarrantyObSendPrint'))->middleware('Lang', 'isLogged', 'hasPerm:6,1,0');

Route::get('/sac/warranty/parts/ob/{id}', array('as' => 'sac/warranty/parts/ob', 'uses' => 'UserIController@sacWarrantyPartsOb'))->middleware('Lang', 'isLogged', 'hasPerm:6,1,0');

Route::post('/sac/warranty/parts/ob_do', array('as' => 'sac/warranty/parts/ob_do', 'uses' => 'UserIController@sacWarrantyPartsOb_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,1,0');

Route::post('/warrany/parts/ob/delete/{id}', array('as' => 'warrany/parts/ob/delete', 'uses' => 'UserIController@sacWarrantyPartsObDelete'))->middleware('Lang', 'isLogged', 'hasPerm:6,1,0');

Route::get('/sac/warranty/print/ob/{id}', array('as' => 'sac/warranty/print/ob', 'uses' => 'UserIController@sacWarrantyPrintOb'))->middleware('Lang', 'isLogged', 'hasPerm:6,1,0');

Route::get('/sac/warranty/approv', array('as' => 'sac/warranty/approv', 'uses' => 'UserIController@sacWarrantyApprov'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/interactive/{id}', array('as' => 'sac/warranty/interactive', 'uses' => 'UserIController@sacWarrantyInteractive'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/interactive/edit', array('as' => 'sac/warranty/interactive/edit', 'uses' => 'UserIController@sacWarrantyInteractiveEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/msg', array('as' => 'sac/warranty/msg', 'uses' => 'UserIController@sacWarrantySendMsg'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/update/{id}', array('as' => 'sac/warranty/update', 'uses' => 'UserIController@sacWarrantyInteractiveUpd'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/parts/{id}', array('as' => 'sac/warranty/parts', 'uses' => 'UserIController@sacWarrantyPart'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/parts_do', array('as' => 'sac/warranty/parts_do', 'uses' => 'UserIController@sacWarrantyPart_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/warrany/part/delete/{id}', array('as' => 'warrany/part/delete', 'uses' => 'UserIController@sacWarrantyPartDelete'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/warrany/model/delete/{id}', array('as' => 'warrany/model/delete', 'uses' => 'UserIController@sacWarrantyModelDelete'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/get/authorizeds', array('as' => 'sac/warranty/get/authorizeds', 'uses' => 'UserIController@sacWarrantyAuthorizeds'));

Route::get('/sac/map/global', array('as' => 'sac/map/global', 'uses' => 'UserIController@sacMap'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/warranty/get/authorizeds/all', array('as' => 'sac/warranty/get/authorizeds/all', 'uses' => 'UserIController@sacWarrantyAuthorizedsAll'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/client/edit/{id}', array('as' => 'sac/client/edit', 'uses' => 'UserIController@sacClientEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/client/edit_do', array('as' => 'sac/client/edit_do', 'uses' => 'UserIController@sacClientEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/client/all', array('as' => 'sac/client/all', 'uses' => 'UserIController@sacClientAll'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/problemcategory', array('as' => 'sac/sacProblemcategory', 'uses' => 'UserIController@sacProblemcategory'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/problemcategory/excluir', array('as' => 'sac/sacProblemcategory/excluir', 'uses' => 'UserIController@sacProblemcategoryExcluir'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/problemcategory/update', array('as' => '/sac/problemcategory/update', 'uses' => 'UserIController@sacProblemacategoryEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/register/shop/all', 'UserIController@sacShopAll')->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/register/shop/edit/{id}', 'UserIController@sacShopEdit')->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/register/shop/edit_do', 'UserIController@sacShopEdit_do')->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/register/shop-parts/all', 'UserIController@sacShopPartsAll')->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/register/shop-parts/edit/{id}', 'UserIController@sacShopPartsEdit')->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/register/shop-parts/edit_do', 'UserIController@sacShopPartsEdit_do')->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/register/salesman/all', 'UserIController@sacRepresentationAll')->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/register/salesman/edit/{id}', 'UserIController@sacRepresentationEdit')->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/register/salesman/edit_do', 'UserIController@sacRepresentationEdit_do')->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/authorized/edit/{id}', array('as' => 'sac/authorized/edit', 'uses' => 'UserIController@sacAuthorizedEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/authorized/view/{id}', array('as' => 'sac/authorized/view', 'uses' => 'UserIController@sacAuthorizedView'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/authorized/edit_do', array('as' => 'sac/authorized/edit_do', 'uses' => 'UserIController@sacAuthorizedEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/authorized/all', array('as' => 'sac/authorized/all', 'uses' => 'UserIController@sacAuthorizedAll'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/authorized/historic', array('as' => 'sac/authorized/historic', 'uses' => 'UserIController@sacAuthorizedHistoric'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::post('/sac/authorized/historic_do', array('as' => 'sac/authorized/historic_do', 'uses' => 'UserIController@sacAuthorizedHistoric_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

Route::get('/sac/authorized/historic/delete/{id}', array('as' => '/sac/authorized/historic/delete', 'uses' => 'UserIController@sacAuthorizedHistoricDelete'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,1');

 // SAC REMESSA DE PEÇA

Route::get('/sac/assistance/remittance/all', array('as' => 'sac/assistance/remittance/all', 'uses' => 'TechnicalAssistanceController@remittanceList'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/assistance/remittance/change/status/{id}/{status}', array('as' => 'sac/assistance/remittance/change/status', 'uses' => 'TechnicalAssistanceController@remittanceChangeStatus'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/assistance/remittance/approv/{id}', array('as' => 'sac/assistance/remittance/approv', 'uses' => 'TechnicalAssistanceController@remittanceApprov'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/assistance/remittance/approv_do', array('as' => 'sac/assistance/remittance/approv_do', 'uses' => 'TechnicalAssistanceController@remittanceApprov_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/assistance/remittance/edit/{id}', array('as' => 'sac/assistance/remittance/edit', 'uses' => 'TechnicalAssistanceController@remittanceEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/assistance/remittance/edit_do', array('as' => 'sac/assistance/remittance/edit_do', 'uses' => 'TechnicalAssistanceController@remittanceEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/assistance/remittance/part/edit', array('as' => 'sac/assistance/remittance/part/edit', 'uses' => 'TechnicalAssistanceController@remittancePartEdit'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/assistance/remittance/update', array('as' => 'sac/assistance/remittance/update', 'uses' => 'TechnicalAssistanceController@remittanceUpdate'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/assistance/remittance/print/{id}', array('as' => 'sac/assistance/remittance/print', 'uses' => 'TechnicalAssistanceController@remittancePrint'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/assistance/remittance/part/ajax', array('as' => 'sac/assistance/remittance/part/ajax', 'uses' => 'TechnicalAssistanceController@remittancePartAjax'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/assistance/remittance/request/payment/{id}', array('as' => 'sac/assistance/remittance/request/payment', 'uses' => 'TechnicalAssistanceController@remittanceRequestPayment'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/assistance/remittance/request/payment/export', array('as' => 'sac/assistance/remittance/request/payment/export', 'uses' => 'TechnicalAssistanceController@remittanceListPaymentExport'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/warranty/os/paid/export', array('as' => 'sac/warranty/os/paid/export', 'uses' => 'TechnicalAssistanceController@sacWarrantyOsPaidExport'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/assistance/remittance/list/payment', array('as' => 'sac/assistance/remittance/list/payment', 'uses' => 'TechnicalAssistanceController@remittanceListPayment'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/assistance/remittance/payment', array('as' => 'sac/assistance/remittance/payment', 'uses' => 'TechnicalAssistanceController@remittancePayment'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/assistance/remittance/payment/status', array('as' => 'sac/assistance/remittance/payment/status', 'uses' => 'TechnicalAssistanceController@remittancePaymentStatus'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::post('/sac/assistance/remittance/analyze', array('as' => 'sac/assistance/remittance/analyze', 'uses' => 'TechnicalAssistanceController@remittanceAnalyze'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/assistance/remittance/analyze/list', array('as' => 'sac/assistance/remittance/analyze/list', 'uses' => 'TechnicalAssistanceController@remittanceAnalyzeList'))->middleware('Lang', 'isLogged', 'hasPerm:6,0,0');

Route::get('/sac/assistance/warranty/os/costs/all', array('as' => '/sac/assistance/warranty/os/costs/all', 'uses' => 'TechnicalAssistanceController@warrantyOsCostsAll'))->middleware('Lang', 'isLogged', 'hasPerm:16,1,1');

Route::post('/sac/assistance/warranty/os/costs/edit_do', array('as' => '/sac/assistance/warranty/os/costs/edit_do', 'uses' => 'TechnicalAssistanceController@warrantyOsCostsEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:16,1,1');

Route::get('/sac/assistance/warranty/remittance/costs/all', array('as' => '/sac/assistance/warranty/remittance/costs/all', 'uses' => 'TechnicalAssistanceController@warrantyRemittanceCostsAll'))->middleware('Lang', 'isLogged', 'hasPerm:16,1,1');
Route::post('/sac/assistance/warranty/remittance/costs/edit_do', array('as' => '/sac/assistance/warranty/remittance/costs/edit_do', 'uses' => 'TechnicalAssistanceController@warrantyRemittanceCostsEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:16,1,1');

Route::get('/sac/assistance/warranty/os/part/invoice/{id}', array('as' => '/sac/assistance/warranty/os/part/invoice', 'uses' => 'TechnicalAssistanceController@warrantyOsPartInvoice'))->middleware('Lang', 'isLogged', 'hasPerm:16,1,1');

Route::get('/sac/assistance/warranty/remittance/part/invoice/{id}', array('as' => '/sac/assistance/warranty/remittance/part/invoice', 'uses' => 'TechnicalAssistanceController@warrantyRemittancePartInvoice'))->middleware('Lang', 'isLogged', 'hasPerm:16,1,1');

Route::get('/autorizada/remessa/lista', array('as' => 'autorizada/remessa/lista', 'uses' => 'SacAuthorizedController@remittanceList'))->middleware('sacAuthorizedHasLogin');

Route::get('/autorizada/remessa/peca', array('as' => 'autorizada/remessa/peca', 'uses' => 'SacAuthorizedController@remittancePart'))->middleware('sacAuthorizedHasLogin');

Route::post('/autorizada/remessa/peca_do', array('as' => 'autorizada/remessa/peca_do', 'uses' => 'SacAuthorizedController@remittancePart_do'))->middleware('sacAuthorizedHasLogin');

Route::get('/autorizada/remessa/lista/peca/{id}', array('as' => 'autorizada/remessa/lista/peca', 'uses' => 'SacAuthorizedController@remittanceListPart'))->middleware('sacAuthorizedHasLogin');

Route::get('/autorizada/remessa/imprimir/{id}', array('as' => 'autorizada/remessa/imprimir', 'uses' => 'SacAuthorizedController@remittancePrint'))->middleware('sacAuthorizedHasLogin');

Route::post('/autorizada/remessa/finalizar', array('as' => 'autorizada/remessa/finalizar', 'uses' => 'SacAuthorizedController@comfirmRemittancePartFinish'))->middleware('sacAuthorizedHasLogin');

 // SAC CLIENT EXTERNAL
 //Route::get('/suporte', array('as' => 'suporte', 'uses' => 'SacClientController@login'));

 Route::get('/suporte', array('as' => 'suporte', 'uses' => 'SacClientController@faq'));

 Route::get('/representantes', array('as' => 'suporte', 'uses' => 'SacClientController@representation'));

 Route::get('/loja-de-pecas', array('as' => 'suporte', 'uses' => 'SacClientController@shopParts'));

Route::get('/lojas', array('as' => 'suporte', 'uses' => 'SacClientController@shops'));

 Route::post('/suporte/criar/protocolo', array('as' => 'suporte/criar/protocolo', 'uses' => 'SacClientController@faq_do'));

 Route::post('/suporte/enviar/email', array('as' => 'suporte/enviar/email', 'uses' => 'SacClientController@faq_email_do'));

 Route::post('/suporte/buscar/protocolo', array('as' => 'suporte/buscar/protocolo', 'uses' => 'SacClientController@findProtocol'));

 Route::post('/suporte/esqueci', array('as' => 'suporte/recuperar', 'uses' => 'SacClientController@forgotten'));

 Route::get('/suporte/recuperar/{code}', array('as' => 'suporte/recuperar', 'uses' => 'SacClientController@passwordRecovery'));

 Route::post('/suporte/recuperar_do', array('as' => 'suporte/recuperar_do', 'uses' => 'SacClientController@passwordRecovery_do'));

 Route::post('/support/verify/client', array('as' => 'support/verify/client', 'uses' => 'SacClientController@verifyLoginData'));
 
 Route::post('/support/verify/login', array('as' => 'support/verify/login', 'uses' => 'SacClientController@verifyLogin'));

 Route::get('/suporte/painel', array('as' => 'suporte/painel', 'uses' => 'SacClientController@panel'))->middleware('sacClientHasLogin');

 Route::get('/suporte/novo/atendimento', array('as' => 'suporte/novo/atendimento', 'uses' => 'SacClientController@newProtocol'))->middleware('sacClientHasLogin');

 Route::get('/suporte/encerrar/atendimento', array('as' => 'suporte/encerrar/atendimento', 'uses' => 'SacClientController@endProtocol'))->middleware('sacClientHasLogin');

 Route::post('/suporte/novo/atendimento_do', array('as' => 'suporte/novo/atendimento_do', 'uses' => 'SacClientController@newProtocol_do'))->middleware('sacClientHasLogin');

 Route::get('/suporte/products', array('as' => 'suporte/products', 'uses' => 'MiscController@sacProductList'));
  
 Route::get('/suporte/products/protocol', array('as' => 'suporte/products', 'uses' => 'MiscController@sacProductListProtocol'));

 Route::get('/suporte/parts', array('as' => 'suporte/parts', 'uses' => 'MiscController@sacPartsList'));

 Route::get('/suporte/interacao/atendimento/{id}', array('as' => 'suporte/interacao/atendimento', 'uses' => 'SacClientController@interactionProtocol'))->middleware('sacClientHasLogin');

 Route::post('/suporte/nova/mensagem', array('as' => 'suporte/nova/mensagem', 'uses' => 'SacClientController@sendMsgProtocol'))->middleware('sacClientHasLogin');

 Route::post('/suporte/avaliar/atendimento', array('as' => 'suporte/avaliar/atendimento', 'uses' => 'SacClientController@ratingProtocol'))->middleware('sacClientHasLogin');

 // SAC AUTHORIZED EXTERNAL
 Route::get('/autorizada', array('as' => 'autorizada', 'uses' => 'SacAuthorizedController@login'))->middleware('sacAuthorizedHasLogged');

 Route::get('/autorizada/suporte', array('as' => 'autorizada/suporte', 'uses' => 'SacAuthorizedController@support'))->middleware('sacAuthorizedHasLogin');

 Route::post('/autorizada/acessar', array('as' => 'autorizada/acessar', 'uses' => 'SacAuthorizedController@verifyLogin'))->middleware('sacAuthorizedHasLogged');

 Route::get('/autorizada/sair', 'SacAuthorizedController@logout');

 Route::get('/autorizada/gerar/certificado', 'MiscController@authorizedCertifiedGen')->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/imprimir/os/{id}', array('as' => 'autorizada/imprimir/os', 'uses' => 'SacAuthorizedController@OsPrint'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/comunicado/todos', 'SacAuthorizedController@comunicationList')->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/comunicado/ver/{id}', 'SacAuthorizedController@comunicationView')->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/carregar/protocolos', array('as' => 'autorizada/carregar/protocolos', 'uses' => 'SacAuthorizedController@loadList'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/aceitar/{code}', array('as' => 'autorizada/aceitar', 'uses' => 'SacAuthorizedController@protocolAcceptLink'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/atendimento', array('as' => 'autorizado/atendimento', 'uses' => 'SacAuthorizedController@newProtocol'))->middleware('sacAuthorizedHasLogin');

 Route::post('/autorizada/atendimento_do', array('as' => 'autorizado/atendimento_do', 'uses' => 'SacAuthorizedController@newProtocol_do'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/painel', array('as' => 'autorizada/painel', 'uses' => 'SacAuthorizedController@panel'))->middleware('sacAuthorizedHasLogin');

 Route::post('/autorizada/confirmar/atendimento', array('as' => 'autorizada/confirmar/atendimento', 'uses' => 'SacAuthorizedController@protocolAccept'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/buscar/atendimento/{id}', array('as' => 'autorizada/buscar/atendimento', 'uses' => 'SacAuthorizedController@protocolGet'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/buscar/os/{id}', array('as' => 'autorizada/buscar/os', 'uses' => 'SacAuthorizedController@protocolOSGet'))->middleware('sacAuthorizedHasLogin');

 Route::post('/autorizada/agendar/atendimento', array('as' => 'autorizada/agendar/atendimento', 'uses' => 'SacAuthorizedController@protocolSchedule'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/os', array('as' => 'autorizada/os', 'uses' => 'SacAuthorizedController@myOs'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/os/interacao/{id}', array('as' => 'autorizada/os/interacao', 'uses' => 'SacAuthorizedController@interactionOs'))->middleware('sacAuthorizedHasLogin');

 Route::post('/autorizada/os/interacao/mensagem', array('as' => 'autorizada/os/interacao/mensagem', 'uses' => 'SacAuthorizedController@sendMsgOs'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/area-tecnica', array('as' => 'autorizada/area-tecnica', 'uses' => 'SacAuthorizedController@technicalArea'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/lista/ob', array('as' => 'autorizada/lista/ob', 'uses' => 'SacAuthorizedController@buyPartList'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/print/ob/{id}', array('as' => 'autorizada/print/ob', 'uses' => 'SacAuthorizedController@buyPartPrint'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/comprar/peca', array('as' => 'autorizada/comprar/peca', 'uses' => 'SacAuthorizedController@buyPart'))->middleware('sacAuthorizedHasLogin');

 Route::post('/autorizada/comprar/peca_do', array('as' => 'autorizada/comprar/peca_do', 'uses' => 'SacAuthorizedController@buyPart_do'))->middleware('sacAuthorizedHasLogin');

 Route::post('/autorizada/atualizar/atendimento', array('as' => 'autorizada/atualizar/atendimento', 'uses' => 'SacAuthorizedController@protocolOsUpdate'))->middleware('sacAuthorizedHasLogin');

 Route::post('/autorizada/pecas/atendimento', array('as' => 'autorizada/pecas/atendimento', 'uses' => 'SacAuthorizedController@protocolsendPart'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/perfil', array('as' => 'autorizada/perfil', 'uses' => 'SacAuthorizedController@profile'))->middleware('sacAuthorizedHasLogin');

 Route::post('/autorizada/perfil_do', array('as' => 'autorizada/perfil_do', 'uses' => 'SacAuthorizedController@profile_do'))->middleware('sacAuthorizedHasLogin');

 Route::get('/autorizada/esqueci', array('as' => 'autorizada/esqueci', 'uses' => 'SacAuthorizedController@forgotten'))->middleware('sacAuthorizedHasLogged');

 Route::post('/autorizada/esqueci_do', array('as' => 'autorizada/esqueci_do', 'uses' => 'SacAuthorizedController@forgotten_do'))->middleware('sacAuthorizedHasLogged');

 Route::get('/autorizada/recuperar/{code}', array('as' => 'autorizada/recuperar', 'uses' => 'SacAuthorizedController@passwordRecovery'))->middleware('sacAuthorizedHasLogged');

 Route::post('/autorizada/recuperar_do', array('as' => 'autorizada/recuperar_do', 'uses' => 'SacAuthorizedController@passwordRecovery_do'))->middleware('sacAuthorizedHasLogged');

// SERVERs CRON
Route::group(['middleware' => ['cronValidation'] ], function() {

	 Route::get('/server/activity/protocol', 'CronJobController@sacAbsenceInteraction');

	 Route::get('/server/appointment/protocol', 'CronJobController@sacAuthorizedApm');

	 Route::get('/server/expedition/track/{action}', 'CronJobController@sacTrackCorreios');

	 Route::get('/server/reset/counts', 'CronJobController@resetCountFirstDay');

	 Route::get('/server/commercial/adjust/year', 'CronJobController@adjustYearPriceCommercial');
	
	Route::get('/server/user/holiday/validation', 'CronJobController@hasHolidayActive');
	
	Route::get('/server/juridicalmonth/report', 'CronJobController@JuridicalReportMonth');
	
	Route::get('/server/entryexit/employees', 'CronJobController@entryExitEmployeesDailyStatus');
	
	Route::get('/server/entryexit/visite', 'CronJobController@entryExitVisiteDailyStatus');
	
	Route::get('/server/entryexit/transport', 'CronJobController@entryExitTransportDailyStatus');
	
	Route::get('/server/commercial/operation/order/invoice/email', 'CronJobController@operationalOrderInvoiceEmail');	
});	

// LENDING

Route::get('/financy/lending/new', array('as' => 'financy/lending/new', 'uses' => 'UserIController@financyLendingEdit'))->middleware('Lang', 'isLogged', 'hasPerm:9,0,0');

Route::post('/financy/lending/new_do', array('as' => 'financy/lending/new_do', 'uses' => 'UserIController@financyLendingEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:9,0,0');

Route::post('/financy/lending/bank_upd', array('as' => 'financy/lending/bank_upd', 'uses' => 'UserIController@financyLendingBank'))->middleware('Lang', 'isLogged');

Route::get('/module-view/{id}/{perm}', array('as' => 'module-view', 'uses' => 'UserIController@financyModuleView'))->middleware('Lang');

Route::get('/financy/lending/my', array('as' => 'financy/lending/my', 'uses' => 'UserIController@financyLendingMy'))->middleware('Lang', 'isLogged', 'hasPerm:9,0,0');

Route::get('/financy/lending/dashboard', array('as' => 'financy/lending/dashboard', 'uses' => 'UserIController@financyLendingDashboard'))->middleware('Lang', 'isLogged', 'hasPerm:9,0,0');

Route::get('/financy/lending/approv', array('as' => 'financy/lending/approv', 'uses' => 'UserIController@financyLendingApprov'))->middleware('Lang', 'isLogged');

Route::get('/financy/lending/approv/{id}', array('as' => 'financy/lending/approv', 'uses' => 'UserIController@financyLendingApprovView'))->middleware('Lang', 'isLogged');

Route::any('/financy/lending/analyze/{id}/{type}', array('as' => 'financy/lending/analyze', 'uses' => 'UserIController@financyLendingAnalyze'))->middleware('Lang', 'isLogged');

Route::get('/financy/lending/all', array('as' => 'financy/lending/all', 'uses' => 'UserIController@financyLendingAll'))->middleware('Lang', 'isLogged', 'hasPerm:9,0,0');

Route::get('/financy/lending/export', array('as' => 'financy/lending/export', 'uses' => 'UserIController@financyLendingExportView'))->middleware('Lang', 'isLogged', 'hasPerm:9,0,0');

Route::get('/financy/download/export', array('as' => 'financy/download/export', 'uses' => 'UserIController@financyLendingExport'))->middleware('Lang', 'isLogged', 'hasPerm:9,0,0');

Route::get('/financy/lending/receiver', array('as' => 'financy/lending/receiver', 'uses' => 'UserIController@financyLendingReceiver'))->middleware('Lang', 'isLogged', 'hasPerm:9,1,0');

Route::post('/financy/lending/receiver_do', array('as' => 'financy/lending/receiver_do', 'uses' => 'UserIController@financyLendingReceiver_do'))->middleware('Lang', 'isLogged', 'hasPerm:9,1,0');

Route::get('/financy/lending/limit', array('as' => 'financy/lending/limit', 'uses' => 'UserIController@financyLendingLimit'))->middleware('Lang', 'isLogged', 'hasPerm:9,0,1');

Route::post('/financy/lending/limit_do', array('as' => 'financy/lending/limit_do', 'uses' => 'UserIController@financyLendingLimit_do'))->middleware('Lang', 'isLogged', 'hasPerm:9,0,1');

// PAYMENT

Route::get('/financy/payment/edit/{id}', array('as' => 'financy/payment/edit', 'uses' => 'UserIController@financyPaymentEdit'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,0');

Route::post('/financy/payment/edit_do', array('as' => 'financy/payment/edit_do', 'uses' => 'UserIController@financyPaymentEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,0');

Route::get('/financy/payment/my', array('as' => 'financy/payment/my', 'uses' => 'UserIController@financyPaymentMy'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,0');

Route::get('/financy/payment/transfer', array('as' => 'financy/payment/transfer', 'uses' => 'UserIController@financyPaymentTransfer'))->middleware('Lang', 'isLogged', 'hasPerm:11,1,0');

Route::post('/financy/payment/transfer_do', array('as' => 'financy/payment/transfer_do', 'uses' => 'UserIController@financyPaymentTransfer_do'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,0');

Route::get('/financy/payment/all', array('as' => 'financy/payment/all', 'uses' => 'UserIController@financyPaymentAll'))->middleware('Lang', 'isLogged', 'hasPerm:11,1,1');

Route::get('/financy/payment/export', array('as' => 'financy/payment/export', 'uses' => 'UserIController@financyPaymentExportView'))->middleware('Lang', 'isLogged', 'hasPerm:11,1,1');

Route::get('/payment/download/export', array('as' => 'payment/download/export', 'uses' => 'UserIController@financyPaymentExport'))->middleware('Lang', 'isLogged', 'hasPerm:11,1,1');

Route::get('/financy/payment/approv', array('as' => 'financy/payment/approv', 'uses' => 'UserIController@financyPaymentApprov'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,1');

Route::get('/financy/payment/supervisor/approv', array('as' => 'financy/payment/supervisor/approv', 'uses' => 'UserIController@financyPaymentSupervisoList'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,1');

Route::get('/financy/payment/supervisor/edit/{id}', array('as' => 'financy/payment/supervisor/edit', 'uses' => 'UserIController@financyPaymentSupervisoEdit'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,1');

Route::get('/financy/payment/supervisor/delete', array('as' => 'financy/payment/supervisor/delete', 'uses' => 'UserIController@financyPaymentSupervisoDelete'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,1');

Route::post('/financy/payment/supervisor/update', array('as' => 'financy/payment/supervisor/update', 'uses' => 'UserIController@financyPaymentSupervisoUpdate'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,1');

Route::get('/financy/payment/request/print/{id}', array('as' => 'financy/payment/request/print', 'uses' => 'UserIController@financyPaymentPrint'))->middleware('Lang', 'isLogged');

Route::get('/financy/payment/request/approv/{id}', array('as' => 'financy/payment/request/approv', 'uses' => 'UserIController@financyPaymentApprovView'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,1');

Route::get('/financy/payment/analyze/{id}/{type}', array('as' => 'financy/payment/analyze', 'uses' => 'UserIController@financyPaymentAnalyze'))->middleware('Lang', 'isLogged', 'hasPerm:11,0,1');

// REFUND

Route::get('/financy/refund/edit/{id}', array('as' => 'financy/refund/edit', 'uses' => 'UserIController@financyRefundEdit'))->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');

Route::get('/financy/refund/track/{id}', array('as' => 'financy/track', 'uses' => 'UserIController@financyRefundTrack'))->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');

Route::post('/financy/refund/edit_do', array('as' => 'financy/refund/edit_do', 'uses' => 'UserIController@financyRefundEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');

Route::post('/financy/refund/item/delete', array('as' => 'financy/refund/item/delete', 'uses' => 'UserIController@financyRefundItemDelete'))->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');

Route::get('/financy/refund/my', array('as' => 'financy/refund/my', 'uses' => 'UserIController@financyRefundMy'))->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');

Route::get('/financy/refund/all', array('as' => 'financy/refund/all', 'uses' => 'UserIController@financyRefundAll'))->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');

Route::get('/financy/refund/approv', array('as' => 'financy/refund/approv', 'uses' => 'UserIController@financyRefundApprov'))->middleware('Lang', 'isLogged');

Route::get('/financy/refund/send/analyze/{id}', array('as' => 'financy/refund/send/analyze', 'uses' => 'UserIController@financyRefundSendAnalyze'))->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');

Route::get('/financy/refund/correction/{id}', array('as' => 'financy/refund/correction', 'uses' => 'UserIController@financyRefundCorrection'))->middleware('Lang', 'isLogged', 'hasPerm:12,0,1');

Route::post('/financy/refund/analyze/{id}', array('as' => 'financy/refund/analyze', 'uses' => 'UserIController@financyRefundAnalyze'))->middleware('Lang', 'isLogged');

Route::post('/financy/refund/lending_do', array('as' => 'financy/refund/lending_do', 'uses' => 'UserIController@financyRefundLending_do'))->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');

// FINANCY MANAGER USERS VIEW PAGE

Route::get('/financy/permission/module', array('as' => 'financy/permission/module', 'uses' => 'UserIController@financyPermModule'))->middleware('Lang', 'isLogged', 'hasPerm:18,0,1');

Route::post('/financy/permission/module_do', array('as' => 'financy/permission/module_do', 'uses' => 'UserIController@financyPermModule_do'))->middleware('Lang', 'isLogged', 'hasPerm:18,0,1');

// HOME OFFICE

Route::get('/home-office', array('as' => 'home-office', 'uses' => 'UserIController@homeOffice'))->middleware('Lang', 'isLogged', 'hasPerm:10,0,0');

Route::get('/home-office/my', array('as' => 'home-office/my', 'uses' => 'UserIController@homeOfficeMy'))->middleware('Lang', 'isLogged', 'hasPerm:10,0,0');

Route::get('/home-office/data', array('as' => 'home-office/data', 'uses' => 'UserIController@homeOfficeData'))->middleware('Lang', 'isLogged', 'hasPerm:10,1,1');

Route::get('/home-office/online', array('as' => 'home-office/online', 'uses' => 'UserIController@homeOfficeOnline'))->middleware('Lang', 'isLogged', 'hasPerm:10,1,1');

Route::get('/home-office/approv', array('as' => 'home-office/approv', 'uses' => 'UserIController@homeOfficeApprov'))->middleware('Lang', 'isLogged', 'hasPerm:10,0,1');

Route::get('/home-office/approv_do/{id}', array('as' => 'home-office/approv_do', 'uses' => 'UserIController@homeOfficeApprov_do'))->middleware('Lang', 'isLogged', 'hasPerm:10,0,1');

Route::get('/misc/homeoffice/report/users', array('as' => 'misc/homeoffice/report/users', 'uses' => 'MiscController@homeOfficeExcelReport'))->middleware('Lang', 'isLogged');

// HOUR EXTRA

Route::get('/hour-extra/my', array('as' => 'hour-extra/my', 'uses' => 'UserIController@hourExtraMy'))->middleware('Lang', 'isLogged');

Route::get('/hour-extra/new', array('as' => 'hour-extra/new', 'uses' => 'UserIController@hourExtraNew'))->middleware('Lang', 'isLogged');

Route::post('/hour-extra/new_do', array('as' => 'hour-extra/new_do', 'uses' => 'UserIController@hourExtraNew_do'))->middleware('Lang', 'isLogged');

Route::get('/hour-extra/all', array('as' => 'hour-extra/all', 'uses' => 'UserIController@hourExtraAll'))->middleware('Lang', 'isLogged', 'hasPerm:24,1,0');

Route::get('/hour-extra/cancel/{id}', array('as' => 'hour-extra/cancel', 'uses' => 'UserIController@hourExtraCancel'))->middleware('Lang', 'isLogged', 'hasPerm:24,0,0');

Route::get('/hour-extra/approv', array('as' => 'hour-extra/approv', 'uses' => 'UserIController@hourExtraApprov'))->middleware('Lang', 'isLogged', 'hasPerm:24,0,1');

Route::get('/hour-extra/approv_do/{id}', array('as' => 'hour-extra/approv_do', 'uses' => 'UserIController@hourExtraApprov_do'))->middleware('Lang', 'isLogged', 'hasPerm:24,0,1');

Route::get('/misc/hourextra/report/users', array('as' => 'misc/hourextra/report/users', 'uses' => 'MiscController@hourExtraExcelReport'))->middleware('Lang', 'isLogged');

// INDUSTRIAL

Route::get('/engineering/product/edit/{id}', array('as' => 'engineering/product/edit', 'uses' => 'UserIController@idlEngEditProduct'))->middleware('Lang', 'isLogged', 'hasPerm:15,1,1');

Route::get('/engineering/product/all', array('as' => 'engineering/product/all', 'uses' => 'UserIController@idlEngAllProduct'))->middleware('Lang', 'isLogged', 'hasPerm:15,0,0');

Route::post('/engineering/product/edit_do', array('as' => 'engineering/product/edit_do', 'uses' => 'UserIController@idlEngEditProduct_do'))->middleware('Lang', 'isLogged', 'hasPerm:15,0,0');

Route::get('/engineering/product/status', array('as' => 'engineering/product/status', 'uses' => 'UserIController@idlEngEditProduct_status'))->middleware('Lang', 'isLogged', 'hasPerm:15,0,0');

Route::get('/engineering/part/edit/{id}', array('as' => 'engineering/part/edit', 'uses' => 'UserIController@idlEngEditPart'))->middleware('Lang', 'isLogged', 'hasPerm:15,1,1');

Route::get('/engineering/part/all', array('as' => 'engineering/part/all', 'uses' => 'UserIController@idlEngAllPart'))->middleware('Lang', 'isLogged', 'hasPerm:15,0,0');

Route::post('/engineering/part/edit_do', array('as' => 'engineering/part/edit_do', 'uses' => 'UserIController@idlEngEditPart_do'))->middleware('Lang', 'isLogged', 'hasPerm:15,0,0');

Route::get('/engineering/part/status', array('as' => 'engineering/part/status', 'uses' => 'UserIController@idlEngEditPart_status'))->middleware('Lang', 'isLogged', 'hasPerm:15,0,0');

Route::get('/engineering/part/import', array('as' => 'engineering/part/import', 'uses' => 'UserIController@idlEngImportPart'))->middleware('Lang', 'isLogged', 'hasPerm:15,1,1');

Route::post('/engineering/part/import_do', array('as' => 'engineering/part/import_do', 'uses' => 'UserIController@idlEngImportPart_do'))->middleware('Lang', 'isLogged', 'hasPerm:15,1,1');

Route::get('/engineering/type', array('as' => 'engineering/type', 'uses' => 'UserIController@idlEngAllType'))->middleware('Lang', 'isLogged', 'hasPerm:15,0,0');

Route::post('/engineering/type/update', array('as' => 'engineering/type/update', 'uses' => 'UserIController@idlEngEditType'))->middleware('Lang', 'isLogged', 'hasPerm:15,1,1');

Route::get('/engineering/type/delete/{id}', array('as' => 'engineering/type/delete', 'uses' => 'UserIController@idlEngDeleteType'))->middleware('Lang', 'isLogged', 'hasPerm:15,1,1');

// FILTERS
Route::get('/filter/task/users', array('as' => 'filter/task/user', 'uses' => 'FilterIController@filterTaskUsers'))->middleware('Lang', 'isLogged');

// CRON JOBS

Route::get('/server/task/later', array('as' => 'server/task/later', 'uses' => 'CronJobController@verifyTaskLater'));

Route::get('/server/trip/later', array('as' => 'server/trip/later', 'uses' => 'CronJobController@verifyTripRest7'));

Route::get('/server/trip/report', array('as' => 'server/trip/report', 'uses' => 'CronJobController@sendReportTrip'));

Route::get('/server/taskday/report', array('as' => 'server/taskday/report', 'uses' => 'CronJobController@sendReportTaskDay'));

Route::get('/server/surveyday/report', array('as' => 'server/surveyday/report', 'uses' => 'CronJobController@SurveyReportDaily'));

Route::get('/server/protocolmonth/report', array('as' => 'server/protocolmonth/report', 'uses' => 'CronJobController@ProtocolReportMonth'));

Route::get('/server/hour-extra/rh/report', array('as' => 'server/hour-extra/rh/report', 'uses' => 'CronJobController@hourExtraReportRH'));

// MISC

Route::get('/misc/chatjs/bar/trip/{month}', array('as' => 'misc/chatjs/bar/trip', 'uses' => 'MiscController@chartjsBarTrip'));

Route::post('/misc/base64/', array('as' => 'misc/base64', 'uses' => 'MiscController@saveBase64Img'));

Route::get('/misc/currency-to-words', array('as' => 'misc/currency-to-words', 'uses' => 'MiscController@CurrencyToWords'));

Route::get('/misc/user/bank', array('as' => 'misc/user/bank', 'uses' => 'MiscController@userBank'));

Route::get('/user/permissions/update', array('as' => 'user/permissions/update', 'uses' => 'MiscController@userUpdatePermissions'))->middleware('Lang', 'isLogged');

Route::post('/misc/task/start', array('as' => 'misc/task/start', 'uses' => 'UserIController@miscTaskDayStart'))->middleware('Lang', 'isLogged');

Route::post('/misc/task/report', array('as' => 'misc/task/report', 'uses' => 'UserIController@miscTaskDayReport'))->middleware('Lang', 'isLogged');

Route::get('/misc/user/sectors/{nivel}/{id}', array('as' => 'misc/user/sectors', 'uses' => 'MiscController@getSubSectors'));

Route::get('/misc/product/category/{nivel}/{id}', array('as' => 'misc/product/category', 'uses' => 'MiscController@getSubProducts'));

Route::get('/misc/product/part/{id}', array('as' => 'misc/product/part', 'uses' => 'MiscController@getPartProducts'));

Route::get('/misc/part/list/{id}', array('as' => 'misc/part/list', 'uses' => 'MiscController@getPartsList'));

Route::post('/misc/survey/user', array('as' => 'misc/survey/user', 'uses' => 'SurveyController@userSurveyAswer'))->middleware('Lang', 'isLogged');

Route::post('/misc/user/picture', array('as' => 'misc/user/picture', 'uses' => 'UserIController@userPicture'))->middleware('Lang', 'isLogged');

Route::get('/misc/modeule/timeline/{type}/{id}', array('as' => 'misc/module/timeline', 'uses' => 'UserIController@ProcessAnalyzeTrack'))->middleware('Lang', 'isLogged');

Route::any('/misc/suspended/request/{type}/{id}', array('as' => 'misc/suspended/request', 'uses' => 'UserIController@suspendedRequest'))->middleware('Lang', 'isLogged');

Route::any('/misc/retroc/request/{type}/{id}', array('as' => 'misc/retroc/request', 'uses' => 'UserIController@retrocRequest'))->middleware('Lang', 'isLogged');

Route::get('/misc/sac/client', array('as' => 'misc/sac/client', 'uses' => 'MiscController@sacClientList'))->middleware('Lang', 'isLogged');

Route::get('/misc/sac/registers', array('as' => 'misc/sac/registers', 'uses' => 'MiscController@sacRegisterList'))->middleware('Lang', 'isLogged');

Route::get('/misc/sac/authorized', array('as' => 'misc/sac/authorized', 'uses' => 'MiscController@sacAuthorizedList'))->middleware('Lang', 'isLogged');

Route::get('/misc/sac/product', array('as' => 'misc/sac/product', 'uses' => 'MiscController@sacProductList'))->middleware('Lang', 'isLogged');

Route::get('/misc/sac/product/protocol', array('as' => 'misc/sac/product/protocol', 'uses' => 'MiscController@sacProductListProtocol'))->middleware('Lang', 'isLogged');

Route::get('/misc/sac/protocol', array('as' => 'misc/sac/protocol', 'uses' => 'MiscController@sacProtocolList'))->middleware('Lang', 'isLogged');

Route::get('/misc/import/csv', array('as' => 'misc/import/csv', 'uses' => 'UserIController@importCsv'))->middleware('Lang', 'isLogged');

Route::post('/misc/import/authorized', array('as' => 'misc/import/authorized', 'uses' => 'MiscController@authorizedImport'))->middleware('Lang', 'isLogged');

Route::post('/misc/import/shop', array('as' => 'misc/import/shop', 'uses' => 'MiscController@shopImport'))->middleware('Lang', 'isLogged');

Route::post('/misc/import/shop-parts', array('as' => 'misc/import/shop-parts', 'uses' => 'MiscController@shopPartsImport'))->middleware('Lang', 'isLogged');

Route::post('/misc/import/client', array('as' => 'misc/import/client', 'uses' => 'MiscController@clientImport'))->middleware('Lang', 'isLogged');

Route::post('/misc/import/product', array('as' => 'misc/import/product', 'uses' => 'MiscController@ProductImport'))->middleware('Lang', 'isLogged');

Route::post('/misc/import/parts', array('as' => 'misc/import/parts', 'uses' => 'MiscController@PartsImport'))->middleware('Lang', 'isLogged');

Route::post('/misc/import/users', array('as' => 'misc/import/users', 'uses' => 'MiscController@wdgt_createUsers'))->middleware('Lang', 'isLogged');

//Route::any('/misc/1', array('as' => 'misc/teste', 'uses' => 'MiscController@migrateRefundApprov'))->middleware('Lang', 'isLogged');
//Route::any('/misc/2', array('as' => 'misc/teste', 'uses' => 'MiscController@migrateRefundAnalyze'))->middleware('Lang', 'isLogged');
//Route::any('/misc/3', array('as' => 'misc/teste', 'uses' => 'MiscController@migrateLendingApprov'))->middleware('Lang', 'isLogged');
//Route::any('/misc/4', array('as' => 'misc/teste', 'uses' => 'MiscController@migrateLendingAnalyze'))->middleware('Lang', 'isLogged');
//Route::any('/misc/5', array('as' => 'misc/teste', 'uses' => 'MiscController@migrateAccountbilityApprov'))->middleware('Lang', 'isLogged');
//Route::any('/misc/6', array('as' => 'misc/teste', 'uses' => 'MiscController@migrateAccountbilityAnalyze'))->middleware('Lang', 'isLogged');
Route::any('/misc/7', array('as' => 'misc/7', 'uses' => 'MiscController@migrateAtualizaca'))->middleware('Lang', 'isLogged');

Route::any('/misc/teste', array('as' => 'misc/teste', 'uses' => 'MiscController@teste'))->middleware('Lang', 'isLogged');
Route::any('/misc/teste/list', array('as' => 'misc/teste/list', 'uses' => 'MiscController@projectlist'))->middleware('Lang', 'isLogged');

Route::get('/misc/unlink/aws', 'MiscController@unLinkAWS')->middleware('Lang', 'isLogged');

Route::get('/misc/components/analyze/', 'MiscController@processAnalyzeDepartaments');

Route::get('/misc/components/analyze/create/approvers', 'MiscController@componentsAnalyzeCreateApprovers')->middleware('Lang', 'isLogged');

Route::post('/misc/components/analyze/create/approvers_do', 'MiscController@componentsAnalyzeCreateApprovers_do')->middleware('Lang', 'isLogged');

Route::post('/financy/accountability/send_analyze_do/{id}', 'FinancyAccountabilityController@financyAccountabilitySendAnalyze_do')->middleware('Lang', 'isLogged');

Route::post('/financy/accountability/analyze_do', 'FinancyAccountabilityController@financyAccountabilityAnalyze_do')->middleware('Lang', 'isLogged');

Route::post('/financy/lending/analyze_do', array('as' => 'financy/lending/analyze_do', 'uses' => 'UserIController@financyLendingAnalyze_do'))->middleware('Lang', 'isLogged');

Route::any('/visualizar/pedidos', 'MiscController@expeditionViewOrders')->middleware('Lang', 'isLogged');

// CHAT

Route::get('/chat/main', array('as' => 'chat/main', 'uses' => 'UserIController@chatMain'))->middleware('Lang', 'isLogged');

Route::post('/chat/new/message', array('as' => 'chat/new/message', 'uses' => 'UserIController@chatNewMessage'))->middleware('Lang', 'isLogged');

Route::get('/chat/new/version', array('as' => 'chat/new/version', 'uses' => 'UserIController@chatNewVersion'))->middleware('Lang', 'isLogged');

Route::get('/chat/messages', array('as' => 'chat/messages', 'uses' => 'UserIController@chatMessages'))->middleware('Lang', 'isLogged');

Route::post('/chat/status/{r_code}/{status}', array('as' => 'chat/status', 'uses' => 'UserIController@chatStatus'));

// SURVEY
Route::group(['middleware' => ['Lang','isLogged'] ], function() {
    Route::group(['middleware' => ['hasPerm:13,0,0'] ], function() {
        Route::get('/survey/all', array('as' => 'survey/all', 'uses' => 'SurveyController@SurveyList'));
        Route::get('/survey/edit/{id}', array('as' => 'survey/edit', 'uses' => 'SurveyController@SurveyEdit'));
        Route::post('/survey/edit_do', array('as' => 'survey/edit_do', 'uses' => 'SurveyController@SurveyEdit_do'));
        Route::post('/survey/question/delete/{id}', array('as' => 'survey/question/delete', 'uses' => 'SurveyController@SurveyDelete'));
        Route::get('/survey/answers', array('as' => 'survey/answers', 'uses' => 'SurveyController@SurveyAswers'));
        Route::get('/survey/answers/view/{id}', array('as' => 'survey/answers/view', 'uses' => 'SurveyController@SurveyAswersView'));
    });
});

Route::get('/pesquisa/{id}/resposta', 'SurveyController@SurveyAswersEdit')->name('pesquisa.anonima');
Route::post('/surveys/anonymous/answers', 'SurveyController@anonymousSurveyAswer');

// Route::get('/survey/all', array('as' => 'survey/all', 'uses' => 'UserIController@SurveyList'))->middleware('Lang', 'isLogged', 'hasPerm:13,0,0');
// Route::get('/survey/edit/{id}', array('as' => 'survey/edit', 'uses' => 'UserIController@SurveyEdit'))->middleware('Lang', 'isLogged', 'hasPerm:13,0,0');
// Route::post('/survey/edit_do', array('as' => 'survey/edit_do', 'uses' => 'UserIController@SurveyEdit_do'))->middleware('Lang', 'isLogged', 'hasPerm:13,0,0');
// Route::post('/survey/question/delete/{id}', array('as' => 'survey/question/delete', 'uses' => 'UserIController@SurveyDelete'))->middleware('Lang', 'isLogged', 'hasPerm:13,0,0');
// Route::get('/survey/answers', array('as' => 'survey/answers', 'uses' => 'UserIController@SurveyAswers'))->middleware('Lang', 'isLogged', 'hasPerm:13,0,0');
// Route::get('/survey/answers/view/{id}', array('as' => 'survey/answers/view', 'uses' => 'UserIController@SurveyAswersView'))->middleware('Lang', 'isLogged', 'hasPerm:13,0,0');
Route::get('/survey/export', array('as' => 'survey/export', 'uses' => 'UserIController@SurveyExport'));


Route::get('/tratativa/interna', array('as' => 'tratativa/interna', 'uses' => 'UserIController@greeInterno'));

Route::post('/tratativa/interna/latlong', array('as' => 'tratativa/interna', 'uses' => 'UserIController@greeInternoLatLong'));


// API GREE

Route::any('/api/v1/sac/endprotocol', array('as' => 'api/v1/sac/endprotocol', 'uses' => 'ApiController@sacEndProtocol'));

Route::any('/api/v1/sac/rateprotocol', array('as' => 'api/v1/sac/rateprotocol', 'uses' => 'ApiController@sacRateProtocol'));

Route::any('/api/v1/sac/endcall', 'ApiController@endCall');

Route::any('/api/v1/sac/ura', 'ApiController@jsonURADinamic');

Route::any('/api/v1/emails/responses/sns', 'ApiController@awsResponseSNS');

// LOG VIEW

Route::get('/ti/developer/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('Lang', 'isLogged', 'hasPerm:4,0,1');

//QR CODE
Route::group(['middleware' => ['Lang','isLogged'] ], function() {
    
    //Pode Aprovar
    Route::group(['middleware' => ['hasPerm:19,0,1'] ], function() {
        Route::post('/qr_code/edit_do', 'QrCodeController@QrCodeEdit_do');
        Route::post('/qr_code/analyze', 'QrCodeController@QrCodeAnalyze');
        Route::get('/qr_code/list/all', 'QrCodeController@QrCodeAll');
        Route::get('/qr_code/list/approv', 'QrCodeController@QrCodeApprov');
    });

    //Gestor da Permissão
    // Route::group(['middleware' => ['hasPerm:19,1,0'] ], function() {

    // }

    //Pode Acessar esse modulo
    // Route::group(['middleware' => ['hasPerm:19,0,0'] ], function() {

    // }

    //Somente Gestor ou Quem Aprova pode acessar esse modulo
    // Route::group(['middleware' => ['hasPerm:19,1,1'] ], function() {

    // }

});
Route::post('/qrcode/cadastrar', 'QrCodeController@QrCodeNewRequest');

// COMMERCIAL
Route::group(['middleware' => ['Lang','isLogged'] ], function() {
    
    //Pode Aprovar
    Route::group(['middleware' => ['hasPerm:20,0,0'] ], function() {
        Route::get('/commercial/promoter/user/all', 'CommercialController@admPromoterUserAll');
        Route::post('/commercial/promoter/user/edit_do', 'CommercialController@admPromoterUserEdit_do');
        Route::get('/commercial/promoter/request/item/all', 'CommercialController@admPromoterRequestItemAll');
        Route::post('/commercial/promoter/request/item/edit_do', 'CommercialController@admPromoterRequestItemEdit_do');
        Route::get('/commercial/promoter/routes/all', 'CommercialController@admPromoterRouteAll');
        Route::post('/commercial/promoter/routes/cancel', 'CommercialController@admPromoterRouteCancel');
        Route::post('/commercial/promoter/routes/edit_do', 'CommercialController@admPromoterRouteEdit_do');
        Route::get('/commercial/promoter/calendar', 'CommercialController@admPromoterCalendarGet');
        Route::get('/commercial/promoter/monitor', 'CommercialController@admPromoterMonitor');
        Route::get('/commercial/promoter/monitor/filter', 'CommercialController@admPromoterMonitorFilter');
    });

});

// PROMOTER USER
Route::group(['middleware' => ['commercialPromoterLogin'] ], function() {

    Route::get('/promoter/send/position', 'CommercialController@promoterNewPosition');
    Route::get('/promotor/logout', 'CommercialController@promoterLogout');
    Route::get('/promotor/dashboard', 'CommercialController@promoterDashboard');
    Route::get('/promotor/request/item', 'CommercialController@promoterRequestItem');
    Route::post('/promotor/route/update', 'CommercialController@promoteRouteUpdate');
    Route::post('/promotor/task/completed', 'CommercialController@promoterTaskCompleted');
    Route::post('promotor/request/item/edit_do', 'CommercialController@PromoterRequestItemEdit_do');
    Route::get('/promotor/request/item/delete/{id}', 'CommercialController@promoterRequestItemDelete');
    Route::get('/promotor/request/item/receiver/{id}', 'CommercialController@promoterRequestItemReceiver');
});   

Route::group(['middleware' => ['commercialPromoterHasLogged'] ], function() {

    Route::get('/promotor/login', 'CommercialController@promoterLogin');
    Route::post('/promotor/login/verify', 'CommercialController@promoterLoginVerify');
    Route::post('/promotor/forgotten/password', 'CommercialController@promoterLoginForgotten');
    Route::get('/promotor', function (){
        return redirect('/promotor/login');
    });

}); 


// ACCOUNTABILITY
Route::group(['middleware' => ['Lang','isLogged'] ], function() {
    
    //Pode Aprovar
    // Route::group(['middleware' => ['hasPerm:22,0,1'] ], function() {
        
    // });

    //Gestor da Permissão
    // Route::group(['middleware' => ['hasPerm:22,1,0'] ], function() {

    // });

    Route::get('/financy/accountability/my', 'FinancyAccountabilityController@financyAccountabilityMy');
        Route::get('/financy/accountability/edit/{id}', 'FinancyAccountabilityController@editPrestacaoContas')->middleware('Lang', 'isLogged');
        Route::post('/financy/accountability/edit_do', 'FinancyAccountabilityController@savePrestacaoContas')->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');
        Route::post('/financy/accountability/change/lending', 'FinancyAccountabilityController@trocaEmprestimoPrestacaoContas')->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');
        Route::post('/financy/accountability/item/delete', 'FinancyAccountabilityController@financyAccountabilityItemDelete')->middleware('Lang', 'isLogged', 'hasPerm:12,0,0');
        Route::post('/financy/accountability/send_analyze/{id}', 'FinancyAccountabilityController@financyAccountabilitySendAnalyze')->middleware('Lang', 'isLogged');

        Route::get('/financy/accountability/ajax/lending', 'FinancyAccountabilityController@ajaxViewEmprestimosPendentes')->middleware('Lang', 'isLogged');
        Route::get('/financy/accountability/ajax/lending/history/{id}', 'FinancyAccountabilityController@ajaxViewHistoricoEmprestimo')->name('ajaxViewHistoricoEmprestimo')->middleware('Lang', 'isLogged');
        Route::get('/financy/accountability/ajax/lendings/history/user/{r_code}', 'FinancyAccountabilityController@ajaxViewHistoricoEmprestimosUsuario')->name('ajaxViewDebitos')->middleware('Lang', 'isLogged');

    //Somente Gestor ou Quem Aprova pode acessar esse modulo
    Route::group(['middleware' => ['hasPerm:22,1,1'] ], function() {
        Route::get('/financy/list/debtors', 'FinancyAccountabilityController@listAllDevedores');
        Route::get('/financy/accountability/all', 'FinancyAccountabilityController@financyAccountabilityAll');
		
        Route::post('/financy/accountability/manual_entry/edit_do', 'FinancyAccountabilityController@savePrestacaoContasManual');
        Route::post('/financy/accountability/manual_entry/item/delete', 'FinancyAccountabilityController@financyAccountabilityManualEntryItemDelete');
    });
	
	Route::get('/financy/accountability/approv', 'FinancyAccountabilityController@financyAccountabilityApprov');

});

Route::group(['middleware' => ['Lang','isLogged'] ], function() {
    
    Route::group(['middleware' => ['hasPerm:23,0,0'] ], function() {

        Route::get('/juridical/process/register/{id}', 'JuridicalController@processEdit');
        Route::post('/juridical/process/edit_do', 'JuridicalController@processEdit_do');
        Route::get('/juridical/process/list', 'JuridicalController@processList');
        Route::get('/juridical/process/export', 'JuridicalController@processExport');
        Route::get('/juridical/process/monitor', 'JuridicalController@processMonitor');
        Route::get('/juridical/process/list/dropdown', 'MiscController@juridicalProcessList');
        Route::get('/juridical/process/monitor/filter/ajax', 'JuridicalController@processMonitorFilterAjax');
        Route::post('/juridical/process/payment/attach', 'JuridicalController@processPaymentAttach');

        Route::get('/juridical/process/info/{id}', 'JuridicalController@processInfo');
        Route::post('/juridical/process/info/historic_do', 'JuridicalController@processHistoric_do');
        Route::post('/juridical/process/info/document/ajax', 'JuridicalController@processDocumentsAjax');
        Route::post('/juridical/process/info/document/delete/ajax', 'JuridicalController@processDocumentsDeleteAjax');
        Route::post('/juridical/process/info/update/status/ajax', 'JuridicalController@processInfoUpdateStatusAjax');
        Route::get('/juridical/process/info/historic/ajax', 'JuridicalController@processHistoricAjax');
        Route::get('/juridical/process/info/cost/ajax', 'JuridicalController@processCostAjax');
        Route::get('/juridical/process/info/historic/notification', 'JuridicalController@processHistoricNotification');

        Route::post('/juridical/process/cost/attach/ajax', 'JuridicalController@processCostAttchAjax');
        Route::post('/juridical/process/cost/attach/delete/ajax', 'JuridicalController@processCostAttchDeleteAjax');
        Route::post('/juridical/process/cost_do', 'JuridicalController@processCost_do');
        Route::get('/juridical/process/cost/receipt/ajax', 'JuridicalController@processCostReceiptAjax');
        Route::get('/juridical/process/cost/list', 'JuridicalController@processCostList');   
		Route::get('/juridical/process/details/ajax', 'JuridicalController@processDetails');

        Route::get('/juridical/law/firm/register/{id}', 'JuridicalController@lawFirmEdit');
        Route::post('/juridical/law/firm/edit_do', 'JuridicalController@lawFirmEdit_do');
        Route::get('/juridical/law/firm/list', 'JuridicalController@lawFirmList');
        Route::get('/juridical/law/firm/list/dropdown', 'MiscController@juridicalLawFirmList');
        Route::get('/juridical/law/firm/cost/{id}', 'JuridicalController@lawFirmCostList');
        Route::post('/juridical/law/firm/cost/edit_do', 'JuridicalController@lawFirmCostEdit_do');
        Route::get('/juridical/law/firm/type/cost/dropdown', 'MiscController@juridicalTypeCostList');
        Route::get('/juridical/law/firm/cost/receipt/ajax', 'JuridicalController@lawFirmCostReceiptAjax');
        Route::get('/juridical/law/firm/cost/info/ajax', 'JuridicalController@lawFirmCostInfoAjax');
        Route::get('/juridical/law/firm/cost/receipt/ajax', 'JuridicalController@lawFirmCostReceiptAjax');
        Route::post('/juridical/law/firm/payment/attach', 'JuridicalController@lawFirmPaymentAttach');
        Route::get('/juridical/law/cost/list', 'JuridicalController@lawCostList');

        Route::get('/juridical/type/action/list', 'JuridicalController@processTypeActionList');
        Route::post('/juridical/type/action/edit_do', 'JuridicalController@processTypeActionEdit_do');
        Route::get('/juridical/type/documents/list', 'JuridicalController@processTypeDocumentsList');
        Route::post('/juridical/type/documents/edit_do', 'JuridicalController@processTypeDocumentsEdit_do');
        Route::get('/juridical/type/cost/list', 'JuridicalController@processTypeCostList');
        Route::post('/juridical/type/cost/edit_do', 'JuridicalController@processTypeCostEdit_do');        
        Route::post('/juridical/type/cost/edit/ajax', 'JuridicalController@processTypeCostEditAjax');     
        
        Route::get('/juridical/process/import', 'JuridicalController@processImport');
        Route::post('/juridical/process/import_do', 'JuridicalController@processImport_do');
    });  
});    

Route::group(['middleware' => ['Lang','isLogged'] ], function() {
    Route::group(['middleware' => ['hasPerm:25,0,0'] ], function() {

        Route::get('/recruitment/question/all', array('as' => 'recruitment/question/all', 'uses' => 'HumanResourcesController@recruitmentQuestionAll'));
        Route::get('/recruitment/question/new/{id}', array('as' => 'recruitment/question/new', 'uses' => 'HumanResourcesController@recruitmentQuestionNew'));
        Route::get('/recruitment/question/edit/{id}', array('as' => 'recruitment/question/edit', 'uses' => 'HumanResourcesController@recruitmentQuestionEdit'));
        Route::post('/recruitment/question/edit_do', array('as' => 'recruitment/question/edit_do', 'uses' => 'HumanResourcesController@recruitmentQuestionEdit_do'));
        Route::get('/recruitment/answer/candidates/{id}', array('as' => 'recruitment/answer/candidates', 'uses' => 'HumanResourcesController@recruitmentAnswerCandidates'));		
        Route::get('/recruitment/answer/candidates/response/{id}', array('as' => 'recruitment/answer/candidates/response', 'uses' => 'HumanResourcesController@recruitmentAnswerCandidatesResponse'));	
		Route::get('/recruitment/duplicate/test/{id}', 'HumanResourcesController@recruitmentTestDuplicate');
        Route::post('/recruitment/question/image/upload', array('as' => 'recruitment/question/image/upload', 'uses' => 'HumanResourcesController@recruitmentQuestionImageUpload'));

        Route::get('/recruitment/question/ajax/edit', 'HumanResourcesController@recruitmentQuestionAjax');
        Route::get('/recruitment/answer/delete/ajax', 'HumanResourcesController@recruitmentAnswerDeleteAJax');
        Route::post('/recruitment/answer/update/ajax', 'HumanResourcesController@recruitmentAnswerUpdateAJax');
        Route::get('/recruitment/answer/edit/ajax', 'HumanResourcesController@recruitmentAnswerEditAJax');

        Route::post('/recruitment/question/update/ajax', 'HumanResourcesController@recruitmentQuestionUpdateAJax');
        Route::get('/recruitment/question/delete/ajax/{id}', 'HumanResourcesController@recruitmentQuestionDeleteAJax');
        Route::get('/recruitment/question/return/ajax', 'HumanResourcesController@recruitmentQuestionEditAJax');
        Route::get('/recruitment/answer/edit/new/ajax', 'HumanResourcesController@recruitmentAnswerEditNewAjax');
    });    
});   

Route::group(['middleware' => ['Lang','RecruitmentTestHasLogged'] ], function() {
    Route::get('/recrutamento/prova/{code}', 'HumanResourcesController@recruitmentAnswerLogin');
    Route::post('/recruitment/answer/login', 'HumanResourcesController@recruitmentAnswerLoginVerify');
});

Route::group(['middleware' => ['Lang','RecruitmentTestHasLogin'] ], function() {    

    Route::get('/recrutamento/prova/resolver/{code}', 'HumanResourcesController@recruitmentAnswerEdit'); 
    Route::get('/recruitment/question/ajax', 'HumanResourcesController@recruitmentQuestionAjax'); 
    Route::post('/recruitment/question/response', 'HumanResourcesController@recruitmentQuestionResponse'); 
    Route::post('/recruitment/question/response/timeout', 'HumanResourcesController@recruitmentQuestionResponseTimeout');
    Route::post('/recruitment/logout', 'HumanResourcesController@logout');
});

// Modulo comercial em construção
Route::group(['middleware' => ['isLogged', 'hasPerm:20,1,1'] ], function() {

    Route::group(['middleware' => ['hasSchemePerm:pedidos_programados,view'] ], function() {
        Route::get('/commercial/order/all', 'CommercialController@orderList');
    });

    Route::get('/commercial/order/approv', 'CommercialController@orderApprovList');
    Route::get('/commercial/order/approv/view/{id}', 'CommercialController@orderAnalyze');
    Route::post('/commercial/order/analyze_do', 'CommercialController@orderAnalyze_do');
    Route::get('/commercial/order/print/view/{id}', 'CommercialController@orderPrintView');

    Route::group(['middleware' => ['hasSchemePerm:pedidos_nao_programados,view'] ], function() {
        Route::get('/commercial/order/confirmed/all', 'CommercialController@orderConfirmedList');
    });

    Route::group(['middleware' => ['hasSchemePerm:pedidos_nao_programados,edit'] ], function() {
		Route::any('/commercial/order/import', 'CommercialController@orderImport');
        Route::get('/commercial/order/confirmed/new', 'CommercialController@orderConfirmedNew');
        Route::post('/commercial/order/confirmed/save', 'CommercialController@orderConfirmedSaveNew');
        Route::post('/commercial/order/proof', 'CommercialController@orderProof');
        Route::post('/commercial/order/proof/upload', 'CommercialController@orderProofUpload');
        Route::post('/commercial/order/proof/remove', 'CommercialController@orderProofRemove');
        Route::get('/commercial/order/cancel/{id}', 'CommercialController@orderCancel');
    });

    Route::get('/commercial/order/confirmed/approv', 'CommercialController@orderConfirmedApprovList');
    Route::get('/commercial/order/confirmed/approv/view/{id}', 'CommercialController@orderConfirmedAnalyze');
    Route::get('/commercial/order/confirmed/print/view/{id}', 'CommercialController@orderConfirmedPrintView');

    Route::get('/commercial/user/dropdown', 'MiscController@comercialUsersList');
    Route::get('/commercial/client/timeline/{id}', 'CommercialController@clientTimelineAnalyze');
    Route::get('/commercial/order/timeline/{id}', 'CommercialController@orderTimelineAnalyze');
    Route::get('/commercial/select/group/client', 'CommercialController@listClientSameGroup');
    Route::get('/commercial/programation/export', 'CommercialController@programationExport');
    Route::get('/commercial/order/export', 'CommercialController@orderExport');

    Route::group(['middleware' => ['hasSchemePerm:programacoes,view'] ], function() {
		Route::get('/commercial/programation/timeline/{id}', 'CommercialController@programationTimelineAnalyze');
        Route::get('/commercial/programation/all', 'CommercialController@programationList');
        Route::get('/commercial/programation/view/{id}', 'CommercialController@programationView');
		Route::get('/commercial/programation/macro', 'CommercialController@programationMacro');
		Route::get('/commercial/programation/macro/clients/ajax', 'CommercialController@programationMacroClientsAjax');
    });
    Route::group(['middleware' => ['hasSchemePerm:programacoes,edit'] ], function() {
        Route::get('/commercial/programation/status/{id}', 'CommercialController@programationStatus');
    });

    Route::group(['middleware' => ['hasSchemePerm:reajuste_comercial,view'] ], function() {
        Route::get('/commercial/order/programation/month/list', 'CommercialController@orderAvaibleMonth');
    });

    Route::group(['middleware' => ['hasSchemePerm:reajuste_comercial,edit'] ], function() {
        Route::get('/commercial/order/programation/month_do', 'CommercialController@orderAvaibleMonth_do');
        Route::get('/commercial/order/programation/month/delete/{id}', 'CommercialController@orderAvaibleMonthDelete');
    });

    Route::group(['middleware' => ['hasSchemePerm:representantes,view'] ], function() {
        Route::get('/commercial/salesman/list', 'CommercialController@salesmanList');
    });
    Route::group(['middleware' => ['hasSchemePerm:representantes,edit'] ], function() {
        Route::get('/commercial/salesman/edit/{id}', 'CommercialController@salesmanEdit');
        Route::post('/commercial/salesman/edit_do', 'CommercialController@salesmanEdit_do');
        Route::get('/commercial/salesman/delete/{id}', 'CommercialController@salesmanDelete');
        Route::get('/commercial/salesman/verify/identity/ajax', 'CommercialController@salesmanVerifyIdentityAjax');
        Route::get('/commercial/salesman/reset/auth/{id}', 'CommercialController@salesmanResetAuth');
		Route::get('/commercial/salesman/view/{id}', 'CommercialController@salesmanView');
    });
    Route::get('/commercial/salesman/dropdown', 'MiscController@commercialSalesmanList');
	Route::get('/commercial/tableprice/dropdown', 'MiscController@commercialTablePriceList');

    Route::group(['middleware' => ['hasSchemePerm:grupos_clientes,view'] ], function() {
        Route::get('/commercial/client/group/list', 'CommercialController@clientGroupList');
    });
    Route::group(['middleware' => ['hasSchemePerm:grupos_clientes,edit'] ], function() {
        Route::post('/commercial/client/group/edit_do', 'CommercialController@clientGroupEdit_do');
        Route::get('/commercial/client/group/delete/{id}', 'CommercialController@clientGroupDelete');
    });

    Route::group(['middleware' => ['hasSchemePerm:clientes,view'] ], function() {
        Route::get('/commercial/client/list', 'CommercialController@clientList');
    });
    Route::group(['middleware' => ['hasSchemePerm:clientes,edit'] ], function() {
        Route::get('/commercial/client/edit/{id}', 'CommercialController@clientEdit');
        Route::post('/commercial/client/edit_do', 'CommercialController@clientEdit_do');
        Route::post('/commercial/client/edit_analyze', 'CommercialController@clientEditAnalyze');
		Route::get('/commercial/client/import', 'CommercialController@clientImport');
    	Route::post('/commercial/client/import_do', 'CommercialController@clientImport_do');
    });

    Route::any('/commercial/client/documents/ajax', 'CommercialController@clientDocumentsAjax');
    Route::post('/commercial/client/document/delete/ajax', 'CommercialController@clientDocumentDeleteAjax');
    Route::get('/commercial/client/dropdown', 'MiscController@commercialClientList');
    Route::get('/commercial/client/group/dropdown', 'MiscController@commercialClientGroupList');
    Route::get('/commercial/client/print/view/{id}', 'CommercialController@clientPrintView');
    Route::get('/commercial/client/print/versions/view/{id}/{ver}', 'CommercialController@clientPrintVersionView');

    Route::group(['middleware' => ['isLogged', 'hasPerm:20,0,1'] ], function() {
        Route::get('/commercial/client/list/analyze', 'CommercialController@clientAnalyzeList');
        Route::get('/commercial/client/analyze/{id}', 'CommercialController@clientAnalyze');
        Route::get('/commercial/client/analyze/history/approv', 'CommercialController@clientAnalyzeHistoryApprov');
        Route::get('/commercial/client/approv/view/{id}', 'CommercialController@clientPrintAnalyze');
        Route::post('/commercial/client/analyze_do', 'CommercialController@clientAnalyze_do');
    });

    Route::group(['middleware' => ['hasSchemePerm:grupos_produtos,view'] ], function() {
        Route::get('/commercial/product/group/list', 'CommercialController@productGroupList');
    });
    Route::group(['middleware' => ['hasSchemePerm:grupos_produtos,edit'] ], function() {
        Route::post('/commercial/product/group/edit_do', 'CommercialController@productGroupEdit_do');
        Route::get('/commercial/product/group/delete/{id}', 'CommercialController@productGroupEditDelete');
    });

    Route::get('/commercial/product/group/dropdown', 'MiscController@commercialProductGroupList');

    Route::group(['middleware' => ['hasSchemePerm:produtos,view'] ], function() {
        Route::get('/commercial/product/set/list', 'CommercialController@productSetList');
    });
    Route::group(['middleware' => ['hasSchemePerm:produtos,edit'] ], function() {
        Route::get('/commercial/product/set/edit/{id}', 'CommercialController@productSetEdit');
        Route::post('/commercial/product/set/edit_do', 'CommercialController@productSetEdit_do');
        Route::get('/commercial/product/set/delete/{id}', 'CommercialController@productSetEditDelete');
        Route::post('/commercial/product/adjust', 'CommercialController@productSetAdjust');
        Route::post('/commercial/product/save', 'CommercialController@productCreateCopy');
        Route::get('/commercial/product/save/delete/{id}', 'CommercialController@productSaveDelete');
    });
    Route::get('/commercial/product/save/dropdown', 'MiscController@commercialProductSaveList');

    Route::group(['middleware' => ['hasSchemePerm:condicoes_tabela_preco,view'] ], function() {
        Route::get('/commercial/client/conditions/table', 'CommercialController@clientConditionTablePrice');
    });
    Route::group(['middleware' => ['hasSchemePerm:condicoes_tabela_preco,edit'] ], function() {
        Route::get('/commercial/client/conditions/table/delete/{id}', 'CommercialController@clientConditionTablePriceEditDelete');
        Route::get('/commercial/client/conditions/table/edit/{id}', 'CommercialController@clientConditionTablePriceEdit');
        Route::get('/commercial/client/conditions/table/export/{id}', 'CommercialController@clientConditionTablePriceExport');
        Route::post('/commercial/client/conditions/table/edit_do', 'CommercialController@clientConditionTablePriceEdit_do');
    });

    Route::group(['middleware' => ['hasSchemePerm:condicoes_regra_preco,view'] ], function() {
        Route::get('/commercial/client/conditions/rules', 'CommercialController@clientConditionTablePriceRules');
    });
    Route::group(['middleware' => ['hasSchemePerm:condicoes_regra_preco,edit'] ], function() {
        Route::post('/commercial/client/conditions/table/rules_do', 'CommercialController@clientConditionTablePriceRules_do');
        Route::get('/commercial/client/conditions/table/rules/delete/{id}', 'CommercialController@clientConditionTablePriceRulesDelete');
    });

    Route::post('/commercial/client/conditions/table/template/{action}', 'CommercialController@clientConditionTablePriceTemplace');
    Route::get('/commercial/client/conditions/template/dropdown', 'MiscController@commercialProductSaveList');

    Route::get('/commercial/order/print/view/{id}', 'CommercialController@orderPrintView');


    Route::group(['middleware' => ['hasSchemePerm:configuracoes,view'] ], function() {
        Route::get('/commercial/settings', 'CommercialController@settings');
    });
    Route::group(['middleware' => ['hasSchemePerm:configuracoes,edit'] ], function() {
        Route::post('/commercial/settings_do', 'CommercialController@settings_do');
        Route::post('/commercial/permissions_do', 'CommercialController@permissions_do');
        Route::get('/commercial/user/remover/permissions/{id}', 'CommercialController@permissions_delete');
    });
    
	Route::get('/commercial/operation/dashboard/general', 'CommercialController@operationDashboardGeneral');
	
	Route::get('/commercial/operation/order/invoice', 'CommercialController@operationalOrderInvoice');
	Route::post('/commercial/operation/order/invoice/import/refund', 'CommercialController@operationalOrderInvoiceImportRefund');
    Route::get('/commercial/operation/order/invoice/refund/delete/{id}', 'CommercialController@operationalOrderInvoiceRefundDelete');
    Route::post('/commercial/operation/order/invoice/resend/xml', 'CommercialController@operationOrderInvoiceResendXml');	
	Route::get('/commercial/operation/order/invoice/confirm/{id}', 'CommercialController@operationalOrderInvoiceConfirm');

    Route::get('/commercial/operation/report/invoice/print/{id}', 'CommercialController@operationalReportInvoicePrint');
    Route::get('/commercial/operation/report/invoice', 'CommercialController@operationalReportInvoice');
    Route::get('/commercial/operation/report/invoice/delete/{id}', 'CommercialController@operationalReportInvoiceDelete');
    Route::post('/commercial/operation/report/invoice/new', 'CommercialController@operationalReportInvoiceNew');
    Route::post('/commercial/operation/report/invoice/update', 'CommercialController@operationalReportInvoiceUpdate');

    Route::get('/commercial/operation/nfs/pendings/import', 'CommercialController@operationalNFsPendingImport');
    Route::post('/commercial/operation/nfs/pendings/import_do', 'CommercialController@operationalNFsPendingImport_do');
	
	Route::group(['middleware' => ['hasSchemePerm:apuracao_vendas,edit'] ], function() {
		Route::get('/commercial/export/report/sale/client/response/list', 'CommercialController@exportReportSaleClientResponseList');
        Route::get('/commercial/operation/sale/verification', 'CommercialController@operationSaleVerification');
		Route::get('/commercial/export/report/sale/client/response/errors/list/{id}', 'CommercialController@exportReportSaleClientResponseErrorsList');
		Route::get('/commercial/sale/client/verification/errors/list/dropdown/{id}', 'CommercialController@saleClientVerificationErrorsListDropdown');
		
		Route::get('/commercial/sales/budget/list', 'CommercialController@commercialBudgetList');
		Route::get('/commercial/sales/budget/list/analyze', 'CommercialController@commercialBudgetListAnalyze');
		Route::post('/commercial/sales/budget/analyze_do', 'CommercialController@commercialBudgetAnalyze_do');
		Route::post('/commercial/sales/budget/cancel', 'CommercialController@commercialBudgetCancel');
		
		Route::get('/commercial/sales/budget/print/{id}', 'CommercialController@commercialBudgetPrint');
		Route::get('/commercial/sales/budget/credit/print/{id}', 'CommercialController@commercialBudgetCreditPrint');
		Route::get('/commercial/sales/budget/payment/print/{id}', 'CommercialController@commercialBudgetPaymentPrint');
		
    });	
	
	Route::group(['middleware' => ['hasSchemePerm:pedidos_faturados,view'] ], function() {
		Route::get('/commercial/operation/order/invoice', 'CommercialController@operationalOrderInvoice');
	});
	
	Route::group(['middleware' => ['hasSchemePerm:pedidos_faturados,edit'] ], function() {
		Route::post('/commercial/operation/order/invoice/import/refund', 'CommercialController@operationalOrderInvoiceImportRefund');
		Route::get('/commercial/operation/order/invoice/refund/delete/{id}', 'CommercialController@operationalOrderInvoiceRefundDelete');
		Route::post('/commercial/operation/order/invoice/resend/xml', 'CommercialController@operationOrderInvoiceResendXml');	
		Route::get('/commercial/operation/order/invoice/confirm/{id}', 'CommercialController@operationalOrderInvoiceConfirm');
		Route::get('/commercial/operation/order/invoice/nfe/delete/{order_id}/{code_nfe}', 'CommercialController@operationalOrderInvoiceNfeDelete');
	});
});

Route::group(['middleware' => ['commercialSalesmanIsLoggedDashboard'] ], function() {

    Route::get('/comercial/operacao/dashboard', 'CommercialSalesmanController@salesmanDashboard');
	Route::get('/comercial/operacao/dashboard/programation', 'CommercialSalesmanController@salesmanDashboardProgramation');
    Route::any('/comercial/operacao/dashboard/2fa/update', 'CommercialSalesmanController@active2FAUser');
    Route::post('/optauth/verify/dashboard', 'CommercialSalesmanController@verifyOtpAuthDashboard');

});    

Route::get('/comercial/operacao/logout', 'CommercialSalesmanController@salesmanLogout');

Route::group(['middleware' => ['commercialSalesmanIsLogged'] ], function() {


    Route::get('/comercial/operacao/dashboard/export', 'CommercialSalesmanController@salesmanDashboardExport');
    Route::get('/comercial/operacao/meu_perfil', 'CommercialSalesmanController@viewProfile');
    Route::get('/comercial/operacao/tabela/preco/lista', 'CommercialSalesmanController@tabelaPrecoLista');
    Route::get('/comercial/operacao/tabela/preco/{id}', 'CommercialSalesmanController@tabelaPreco');
    Route::get('/comercial/operacao/tabela/deletar/{id}', 'CommercialSalesmanController@tabelaPrecoDeletar');
    Route::post('/comercial/operacao/tabela/preco_do', 'CommercialSalesmanController@tabelaPreco_do');
    Route::get('/comercial/operacao/tabela/exporta/{id}', 'CommercialSalesmanController@clientConditionTablePriceExport');
    Route::get('/comercial/operacao/configuracoes', 'CommercialSalesmanController@viewProfile');
    Route::get('/comercial/operacao/equipe', 'CommercialSalesmanController@viewProfile');
    Route::post('/comercial/operacao/perfil/save', 'CommercialSalesmanController@saveProfile');
	
	Route::post('/comercial/operacao/2fa/update', 'CommercialSalesmanController@active2FAUser');

    Route::get('/comercial/operacao/cliente/todos', 'CommercialSalesmanController@clientList');
	Route::get('/comercial/operacao/cliente/group/dropdown', 'MiscController@commercialSalesmanClientGroupList');
    Route::get('/comercial/operacao/cliente/todos/analise', 'CommercialSalesmanController@clientAnalyzeList');
    Route::get('/comercial/operacao/cliente/analise/{id}', 'CommercialSalesmanController@clientAnalyze');
    Route::post('/comercial/operacao/client/analyze_do', 'CommercialSalesmanController@clientAnalyze_do');
    Route::get('/comercial/operacao/client/print/view/{id}', 'CommercialSalesmanController@clientPrintView');
    Route::get('/comercial/operacao/client/approv/view/{id}', 'CommercialSalesmanController@clientPrintAnalyze');
	Route::get('/comercial/operacao/cliente/timeline/{id}', 'CommercialSalesmanController@clientTimelineAnalyze');

    Route::any('/comercial/operacao/cliente/documento/ajax', 'CommercialSalesmanController@clientDocumentsAjax');
    Route::post('/comercial/operacao/cliente/documento/delete/ajax', 'CommercialSalesmanController@clientDocumentDeleteAjax');

    Route::get('/comercial/operacao/cliente/cadastro/{id}', 'CommercialSalesmanController@clientEdit');
    Route::post('/comercial/operacao/cliente/edit_analise', 'CommercialSalesmanController@clientEditAnalyze');
    Route::post('/comercial/operacao/cliente/edit_do', 'CommercialSalesmanController@clientEdit_do');

    Route::get('/comercial/operacao/cliente/print/versions/view/{id}/{ver}', 'CommercialSalesmanController@clientPrintVersionView');
    Route::get('/comercial/operacao/cliente/analise/historico/approv', 'CommercialSalesmanController@clientAnalyzeHistoryApprov');

	Route::get('/comercial/operacao/programation/timeline/{id}', 'CommercialSalesmanController@programationTimelineAnalyze');
    Route::get('/comercial/operacao/programation/all', 'CommercialSalesmanController@programationList');
    Route::get('/comercial/operacao/programation/new', 'CommercialSalesmanController@programationNew');
    Route::get('/comercial/operacao/programation/view/{id}', 'CommercialSalesmanController@programationView');
    Route::get('/comercial/operacao/programation/edit/{id}', 'CommercialSalesmanController@programationEdit');
    Route::post('/comercial/operacao/programation/new/save', 'CommercialSalesmanController@programationSaveNew');
    Route::post('/comercial/operacao/programation/edit/save', 'CommercialSalesmanController@programationSaveEdit');
    Route::get('/comercial/operacao/change/table/month', 'CommercialSalesmanController@programationChangerTablePrice');
    Route::get('/comercial/operacao/programation/approv', 'CommercialSalesmanController@programationApprovList');
    Route::post('/comercial/operacao/programation/approv_do', 'CommercialSalesmanController@programationApprov_do');
	Route::get('/comercial/operacao/programation/export', 'CommercialSalesmanController@programationExport');
	Route::get('/comercial/operacao/programation/macro/clients/ajax', 'CommercialSalesmanController@programationMacroClientsAjax');

    Route::get('/comercial/operacao/order/new', 'CommercialSalesmanController@orderNew');
    Route::post('/comercial/operacao/order/save', 'CommercialSalesmanController@orderSaveNew');
    Route::get('/comercial/operacao/order/print/view/{id}', 'CommercialSalesmanController@orderPrintView');
    Route::get('/comercial/operacao/order/select/programations', 'CommercialSalesmanController@listDropDownProgramations');
    Route::get('/comercial/operacao/order/select/months', 'CommercialSalesmanController@listProgramationMonth');
    Route::get('/comercial/operacao/select/group/client', 'CommercialSalesmanController@listClientSameGroup');
    Route::get('/comercial/operacao/order/all', 'CommercialSalesmanController@orderList');
    Route::get('/comercial/operacao/order/cancel/{id}', 'CommercialSalesmanController@orderCancel');
    Route::post('/comercial/operacao/order/proof', 'CommercialSalesmanController@orderProof');
    Route::post('/comercial/operacao/order/proof/upload', 'CommercialSalesmanController@orderProofUpload');
    Route::post('/comercial/operacao/order/proof/remove', 'CommercialSalesmanController@orderProofRemove');
    Route::get('/comercial/operacao/order/approv', 'CommercialSalesmanController@orderApprovList');
    Route::get('/comercial/operacao/order/approv/view/{id}', 'CommercialSalesmanController@orderAnalyze');
	Route::post('/comercial/operacao/order/analyze_do', 'CommercialSalesmanController@orderAnalyze_do');
	Route::get('/comercial/operacao/order/timeline/{id}', 'CommercialSalesmanController@orderTimelineAnalyze');
	
	Route::get('/comercial/operacao/order/confirmed/new', 'CommercialSalesmanController@orderConfirmedNew');
    Route::post('/comercial/operacao/order/confirmed/save', 'CommercialSalesmanController@orderConfirmedSaveNew');
    Route::get('/comercial/operacao/order/confirmed/all', 'CommercialSalesmanController@orderConfirmedList');
    Route::get('/comercial/operacao/order/confirmed/approv', 'CommercialSalesmanController@orderConfirmedApprovList');
    Route::get('/comercial/operacao/order/confirmed/approv/view/{id}', 'CommercialSalesmanController@orderConfirmedAnalyze');
    Route::get('/comercial/operacao/order/confirmed/print/view/{id}', 'CommercialSalesmanController@orderConfirmedPrintView');
	Route::get('/comercial/operacao/client/dropdown', 'MiscController@commercialSalesmanClientList');
	Route::get('/comercial/operacao/order/export', 'CommercialSalesmanController@orderExport');
	
	Route::get('/comercial/operacao/verba-comercial/todos', 'CommercialSalesmanController@commercialBudgetList');
	Route::get('/comercial/operacao/verba-comercial/analise', 'CommercialSalesmanController@commercialBudgetListApprov');
	Route::get('/comercial/operacao/verba-comercial/analise/{id}', 'CommercialSalesmanController@commercialBudgetListApprovView');

	Route::get('/comercial/operacao/verba-comercial/novo', 'CommercialSalesmanController@commercialBudgetNew');
	Route::post('/comercial/operacao/verba-comercial/salvar', 'CommercialSalesmanController@commercialBudgetSaveNew');

	Route::get('/comercial/operacao/verba-comercial/editar/{id}', 'CommercialSalesmanController@commercialBudgetEdit');
	Route::post('/comercial/operacao/verba-comercial/editar/salvar', 'CommercialSalesmanController@commercialBudgetSaveEdit');

	Route::post('/comercial/operacao/verba-comercial/cancelar', 'CommercialSalesmanController@commercialBudgetCancel');

	Route::get('/comercial/operacao/verba-comercial/enviar/analise/{id}', 'CommercialSalesmanController@commercialBudgetSendAnalyze');
	Route::post('/comercial/operacao/verba-comercial/fazer/analise', 'CommercialSalesmanController@commercialBudgetDoAnalyze');
	Route::post('/comercial/operacao/verba-comercial/comprovacao/validar', 'CommercialSalesmanController@commmercialBudgetProofConfirm');

	Route::get('/comercial/operacao/verba-comercial/imprimir/{id}', 'CommercialSalesmanController@commercialBudgetPrint');
	Route::get('/comercial/operacao/verba-comercial/credit/imprimir/{id}', 'CommercialSalesmanController@commercialBudgetCreditPrint');
	Route::get('/comercial/operacao/verba-comercial/payment/imprimir/{id}', 'CommercialSalesmanController@commercialBudgetPaymentPrint');

	Route::post('/comercial/operacao/verba-comercial/comprovacao/remover', 'CommercialSalesmanController@commercialBudgetProofRemove');
	Route::post('/comercial/operacao/verba-comercial/comprovacao/adicionar', 'CommercialSalesmanController@commercialBudgetProofUpload');
	
});

Route::get('/server/commercial/order/print/view/{id}', 'CommercialController@orderPrintViewServer');
	Route::get('/server/commercial/order/confirmed/print/view/{id}','CommercialController@orderConfirmedPrintViewServer');

Route::group(['middleware' => ['commercialSalesmanHasLogged'] ], function() {

    Route::get('/comercial/operacao/login', 'CommercialSalesmanController@salesmanLogin');
    Route::post('/comercial/operacao/login/verify', 'CommercialSalesmanController@salesmanLoginVerify');
    Route::post('/comercial/operacao/login/optauth/verify', 'CommercialSalesmanController@verifyOtpAuth');
    Route::post('/comercial/operacao/forgotten/password', 'CommercialSalesmanController@salesmanLoginForgotten');

    Route::get('/comercial/operacao', function (){
        return redirect('/comercial/operacao/login');
    });
});

Route::get('/administration/generic/request/list', 'UserIController@admGenericRequest')->middleware('Lang', 'isLogged');
Route::get('/administration/generic/request/observer', 'UserIController@admGenericRequestObserver')->middleware('Lang', 'isLogged');
Route::get('/administration/generic/request/approv', 'UserIController@admGenericRequestApprov')->middleware('Lang', 'isLogged');
Route::any('/administration/generic/request/view', 'UserIController@admGenericRequestView')->middleware('Lang', 'isLogged');
Route::get('/administration/generic/request/cancelled', 'UserIController@admGenericRequestCancel')->middleware('Lang', 'isLogged');
Route::post('/administration/generic/request/view_do', 'UserIController@admGenericRequestView_do')->middleware('Lang', 'isLogged');
Route::get('/administration/generic/request/base64', 'UserIController@admGenericRequestBase64')->middleware('Lang', 'isLogged');
Route::post('/administration/generic/request/analyze_do', 'UserIController@admGenericRequestAnalyze')->middleware('Lang', 'isLogged');

Route::get('/users/dropdown', 'MiscController@usersList')->middleware('Lang', 'isLogged');

Route::group(['middleware' => ['Lang','isLogged'] ], function() {
    Route::group(['middleware' => ['hasPerm:24,0,0'] ], function() {
        Route::get('/notify/collaborator/new', 'HumanResourcesController@notifyCollaboratorNew');
        Route::get('/notify/collaborator/msg/ajax', 'HumanResourcesController@notifyCollaboratorMsgAjax');
        Route::post('/notify/collaborator/notify/send', 'HumanResourcesController@notifyCollaboratorNotifySend');
        Route::get('/notify/collaborator/list/ajax', 'HumanResourcesController@notifyCollaboratorListAjax');

        Route::post('/notify/collaborator/msg/delete', 'HumanResourcesController@notifyCollaboratorMsgDelete');
    });
    Route::group(['middleware' => ['hasPerm:24,1,0'] ], function() {
        Route::get('/notify/collaborator/liberate', 'HumanResourcesController@notifyCollaboratorLiberate');
        Route::get('/notify/collaborator/liberate_do/{id}/{type}', 'HumanResourcesController@notifyCollaboratorLiberate_do');
    });
});

// WIDGET HOME
Route::get('/widgets/calendar-inline/json/events', array('as' => 'news', 'uses' => 'UserIController@wdgtCalendarEventsJsonEvents'))->middleware('Lang', 'isLogged');

Route::group(['middleware' => ['SecurityGuardHasLogin'] ], function() {
    Route::get('/controle/portaria', 'SecurityGateController@login');
    Route::post('/controle/portaria/validar', 'SecurityGateController@loginVerify');
});

Route::group(['middleware' => ['SecurityGuardIsLogged'] ], function() {
    Route::get('/controle/portaria/sair', 'SecurityGateController@logout');
    Route::get('/controle/portaria/principal', 'SecurityGateController@main');

    Route::get('/controle/portaria/paginas/visita', 'SecurityGateController@pagesVisite');
    Route::get('/controle/portaria/paginas/visita/visualizar', 'SecurityGateController@pagesVisitSingle');
    Route::get('/controle/portaria/paginas/visita/listar', 'SecurityGateController@pagesVisitList');

    Route::get('/controle/portaria/paginas/transporte-de-carga', 'SecurityGateController@pagesTransportCharge');
    Route::get('/controle/portaria/paginas/transporte-de-carga/visualizar', 'SecurityGateController@pagesTransportChargeSingle');
    Route::get('/controle/portaria/paginas/transporte-de-carga/listar', 'SecurityGateController@pagesTransportChargeList');
    Route::post('/controle/portaria/paginas/transporte-de-carga/cadastrar/motorista', 'SecurityGateController@pagesTransportChargeRegisterDriver');

    Route::get('/controle/portaria/paginas/transporte-de-carga/transportes', 'SecurityGateController@listDropDownLogisticsTransport');

    Route::post('/controle/portaria/transporte-de-carga/visita/aprovar', 'SecurityGateController@pagesRequestsEntryExitApprov');
    Route::post('/controle/portaria/transporte-de-carga/visita/negar', 'SecurityGateController@pagesRequestsEntryExitDenied');

    Route::get('/controle/portaria/paginas/funcionarios', 'SecurityGateController@pagesEmployees');
    Route::get('/controle/portaria/paginas/funcionarios/visualizar', 'SecurityGateController@pagesEmployeesSingle');
    Route::get('/controle/portaria/paginas/funcionarios/listar', 'SecurityGateController@pagesEmployeesList');
    Route::post('/controle/portaria/paginas/funcionarios/analisar', 'SecurityGateController@pagesEmployeesApprovOrReprov');
    Route::post('/controle/portaria/paginas/funcionarios/criar', 'SecurityGateController@pagesEmployeesCreateEntry');
    Route::post('/controle/portaria/paginas/funcionarios/deletar', 'SecurityGateController@pagesEmployeesDeleteEntry');

    Route::get('/controle/portaria/paginas/veiculos', 'SecurityGateController@pagesVehicle');
    Route::get('/controle/portaria/paginas/veiculos/visualizar', 'SecurityGateController@pagesVehicleSingle');
    Route::get('/controle/portaria/paginas/veiculos/listar', 'SecurityGateController@pagesVehicleList');
    Route::post('/controle/portaria/paginas/veiculos/analisar', 'SecurityGateController@pagesVehicleApprovOrReprov');
    Route::post('/controle/portaria/paginas/veiculos/criar', 'SecurityGateController@pagesVehicleCreateEntry');
    Route::post('/controle/portaria/paginas/veiculos/deletar', 'SecurityGateController@pagesVehicleDeleteEntry');

    Route::get('/controle/portaria/misc/users/general', 'SecurityGateController@listDropDownUsersGeneral');
    Route::get('/controle/portaria/misc/users', 'SecurityGateController@listDropDownUsers');
    Route::get('/controle/portaria/misc/rent/vehicles', 'SecurityGateController@listDropDownRentVehicles');
	
	Route::get('/controle/portaria/misc/driver/list/dropdown', 'SecurityGateController@transporterDriverListDropdown');
	Route::get('/controle/portaria/misc/vehicle/list/dropdown', 'SecurityGateController@transporterVehicleListDropdown');
	Route::get('/controle/portaria/misc/cart/list/dropdown', 'SecurityGateController@transporterCartListDropdown');
	Route::get('/controle/portaria/misc/transporter/list/dropdown', 'SecurityGateController@transporterListDropdown');
	Route::get('/controle/portaria/misc/supplier/list/dropdown', 'SecurityGateController@supplierListDropdown');
	
});

Route::group(['middleware' => ['Lang','isLogged'] ], function() {
    // Gestor na permissão administrativo
    Route::group(['middleware' => ['hasPerm:27,1,0']], function() {

    });

    Route::get('/adm/entry-exit/visitant/list', 'AdministrationController@notifyCollaboratorNew');
    Route::get('/adm/entry-exit/charge/list', 'AdministrationController@notifyCollaboratorNew');
    Route::get('/adm/entry-exit/employees/list', 'AdministrationController@requestEmployees');
    Route::post('/adm/entry-exit/employees/create', 'AdministrationController@requestEmployeesNew');
    Route::get('/adm/entry-exit/employees/cancel', 'AdministrationController@requestEmployeesCancel');

    Route::get('/adm/entry-exit/vehicles/list', 'AdministrationController@requestVehicles');
    Route::get('/adm/entry-exit/vehicles/edit', 'AdministrationController@requestVehiclesEdit');

    Route::get('/adm/entry-exit/approv/employees/list', 'AdministrationController@requestEmployeesApprov');
    Route::any('/adm/entry-exit/approv/employees_do', 'AdministrationController@requestEmployeesApprov_do');

    Route::get('/adm/entry-exit/rent/vehicles/list', 'AdministrationController@rentVehicles');
    Route::post('/adm/entry-exit/rent/vehicles/edit', 'AdministrationController@rentVehiclesEdit');

    Route::get('/adm/entry-exit/misc/users/general', 'AdministrationController@listDropDownUsersGeneral');
    Route::get('/adm/entry-exit/misc/users', 'AdministrationController@listDropDownUsers');
    Route::get('/adm/entry-exit/misc/gates', 'AdministrationController@listDropDownGates');
    Route::get('/adm/entry-exit/misc/rent/vehicles', 'AdministrationController@listDropDownRentVehicles');
	
	Route::get('/receivement/monitor', 'AdministrationController@receivementMonitor');
    Route::get('/receivement/monitor/ajax', 'AdministrationController@receivementMonitorAjax');
	Route::get('/receivement/confirm/received/{id}', 'AdministrationController@receivementMonitorConfirm');

});

Route::group(['middleware' => ['Lang','isLogged'] ], function() {
    Route::group(['middleware' => ['hasPerm:26,0,0'] ], function() {

        Route::get('/logistics/transporter/list', 'LogisticsController@transporterList');
        Route::post('/logistics/transporter/edit_do', 'LogisticsController@transporterEdit_do');
        Route::get('/logistics/transporter/change/status/{id}/{status}', 'LogisticsController@transporterChangeStatus');
        Route::get('/logistics/transporter/list/dropdown', 'LogisticsController@transporterListDropdown');

        Route::get('/logistics/transporter/driver/list', 'LogisticsController@transporterDriverList');
        Route::post('/logistics/transporter/driver/edit_do', 'LogisticsController@transporterDriverEdit_do');
        Route::get('/logistics/transporter/driver/remove/file/ajax', 'LogisticsController@transporterDriverRemoveFileAjax');
        Route::get('/logistics/transporter/driver/list/dropdown', 'LogisticsController@transporterDriverListDropdown');

        Route::get('/logistics/transporter/vehicle/list', 'LogisticsController@transporterVehicleList');
        Route::post('/logistics/transporter/vehicle/edit_do', 'LogisticsController@transporterVehicleEdit_do');
        Route::get('/logistics/transporter/vehicle/list/dropdown', 'LogisticsController@transporterVehicleListDropdown');

        Route::get('/logistics/transporter/cart/list', 'LogisticsController@transporterCartList');
        Route::post('/logistics/transporter/cart/edit_do', 'LogisticsController@transporterCartEdit_do');
        Route::get('/logistics/transporter/cart/list/dropdown', 'LogisticsController@transporterCartListDropdown');

        Route::get('/logistics/warehouse/list', 'LogisticsController@warehouseList');
        Route::get('/logistics/warehouse/edit/{id}', 'LogisticsController@warehouseEdit');
        Route::post('/logistics/warehouse/edit_do', 'LogisticsController@warehouseEdit_do');

        Route::post('/logistics/warehouse/type/content/edit/ajax', 'LogisticsController@warehouseTypeContentEditAjax');
        Route::get('/logistics/warehouse/type/content/dropdown', 'LogisticsController@warehouseTypeContentDropdown');
        Route::get('/logistics/warehouse/type/content/delete', 'LogisticsController@warehouseTypeContentDelete');

        Route::post('/logistics/gate/edit/ajax', 'LogisticsController@gateEditAjax');
        Route::get('/logistics/gate/dropdown/list', 'LogisticsController@gateDropdownList');
        Route::get('/logistics/gate/delete', 'LogisticsController@gateDelete');
        
        Route::get('/logistics/container/list', 'LogisticsController@containerList');
        Route::get('/logistics/container/edit/{id}', 'LogisticsController@containerEdit');
        Route::post('/logistics/container/edit_do', 'LogisticsController@containerEdit_do');
        Route::get('/logistics/container/list/dropdown', 'LogisticsController@containerListDropdown');

        Route::get('/logistics/security/guard/list', 'LogisticsController@securityGuardList');
        Route::get('/logistics/security/guard/edit/{id}', 'LogisticsController@securityGuardEdit');
        Route::post('/logistics/security/guard/edit_do', 'LogisticsController@securityGuardEdit_do');

        Route::get('/logistics/request/cargo/transport/list', 'LogisticsController@requestCargoTransportList');
        Route::get('/logistics/request/cargo/transport/edit/{id}', 'LogisticsController@requestCargoTransportEdit');
        Route::post('/logistics/request/cargo/transport/edit_do', 'LogisticsController@requestCargoTransportEdit_do');
        Route::post('/logistics/request/cargo/transport/import/items', 'LogisticsController@requestCargoTransportImportItems');
        Route::post('/logistics/request/cargo/transport/upload/archive', 'LogisticsController@requestCargoTransportUploadArchive');
        Route::get('/logistics/request/cargo/transport/delete/archive', 'LogisticsController@requestCargoTransportDeleteArchive');
        Route::get('/logistics/request/cargo/transport/analyze/{id}', 'LogisticsController@requestCargoTransportStartAnalyze');
		Route::post('/logistics/request/cargo/transport/delete/fix/archive', 'LogisticsController@requestCargoTransportDeleteFixArchive');

        
		Route::get('/logistics/request/cargo/transport/duplicate/{id}', 'LogisticsController@requestCargoTransportDuplicate');
		Route::post('/logistics/request/cargo/transport/approv/now', 'LogisticsController@requestCargoTransportApprovNow');
		
		Route::get('/logistics/supplier/list', 'LogisticsController@supplierList');
        Route::post('/logistics/supplier/edit_do', 'LogisticsController@supplierEdit_do');
        Route::post('/logistics/supplier/import', 'LogisticsController@supplierImport');
        Route::get('/logistics/supplier/list/dropdown', 'LogisticsController@supplierListDropdown');
        Route::post('/logistics/driver/import', 'LogisticsController@transportDriverImport');
        Route::post('/logistics/vehicle/import', 'LogisticsController@transportVehicleImport');
		Route::get('/logistics/warehouse/entry/exit/items/list', 'LogisticsController@warehouseEntryExitItemsList');
			
		Route::get('/logistics/request/visitor/cargo/monitor', 'LogisticsController@RequestVisitorCargoMonitor');
        Route::get('/logistics/request/visitor/cargo/monitor/ajax', 'LogisticsController@RequestVisitorCargoMonitorAjax');
    });
	
	Route::post('/logistics/request/cargo/visitant/cancel', 'LogisticsController@requestCargoVisitantCancel');
	Route::post('/logistics/request/visitant/service/approv/now', 'LogisticsController@requestVisitantServiceApprovNow');
	Route::get('/logistics/request/visitant/service/duplicate/{id}', 'LogisticsController@requestVisitantServiceDuplicate');
	Route::get('/logistics/request/visitor/service/approvers', 'LogisticsController@requestVisitorServiceApprovers');
    Route::post('/logistics/request/visitor/service/approv/edit_do', 'LogisticsController@requestVisitorServiceApprovEdit_do');
	Route::get('/logistics/request/visitor/service/analyze/{id}', 'LogisticsController@requestVisitorServiceStartAnalyze');
	Route::get('/logistics/users/rcode/list', 'LogisticsController@usersRcodeList');
	
	Route::get('/logistics/warehouse/list/dropdown', 'LogisticsController@warehouseListDropdown');
	Route::get('/logistics/request/cargo/transport/receivement', 'LogisticsController@verifyHoursReceivement');

	Route::get('/logistics/request/visitor/service/list', 'LogisticsController@requestVisitorServiceList');
	Route::get('/logistics/request/visitor/service/edit/{id}', 'LogisticsController@requestVisitorServiceEdit');
	Route::post('/logistics/request/visitor/service/edit_do', 'LogisticsController@requestVisitorServiceEdit_do');
	
	Route::get('/logistics/request/cargo/transport/approv/list', 'LogisticsController@requestCargoTransportApprovList');
	Route::post('/logistics/request/cargo/transport/analyze', 'LogisticsController@requestCargoTransportAnalyze_do');
	Route::get('/logistics/request/visitor/service/list/approv', 'LogisticsController@requestVisitorServiceListApprov');
	Route::post('/logistics/request/visitor/service/analyze', 'LogisticsController@requestVisitorServiceAnalyze_do');
	
	Route::get('/administration/reservation/meetroom', 'AdministrationController@reservationMeetRoomShow');
    Route::get('/getReservationMeetRoom', 'AdministrationController@getReservationMeetroom');
    Route::post('/administration/reservation/meetroom/insert', 'AdministrationController@reservationMeetRoomInsert');
    Route::post('/administration/reservation/meetroom/edit', 'AdministrationController@reservationMeetRoomEdit');
    Route::post('/administration/reservation/meetroom/validateRCode', 'AdministrationController@reservationMeetRoomValidateRCode');
    Route::post('/administration/reservation/meetroom/remove', 'AdministrationController@reservationMeetRoomRemove');
    Route::get('/administration/reservation/meetroom/analyze', 'AdministrationController@reservationMeetRoomAnalyze');
    Route::post('/administration/reservation/meetroom/analyze_do', 'AdministrationController@reservationMeetRoomAnalyze_do');
    Route::post('/administration/reservation/meetroom/reason/insert', 'AdministrationController@reservationMeetRoomReasonIsert');
});    

	Route::get('/administration/marketing/training/generate/certificate', 'TrainingController@generateCertificate');
	Route::post('/administration/marketing/training/certificate', 'TrainingController@certificate');
	