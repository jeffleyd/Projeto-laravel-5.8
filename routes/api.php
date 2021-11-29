<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/news/login', 'ApiNewsController@login');
Route::post('/news/password/new', 'ApiNewsController@passwordNew');
Route::post('/news/user/new', 'ApiNewsController@userNew');

Route::group(['middleware' => ['auth:api']], function ($router) {
	
    Route::post('/news/logout', 'ApiNewsController@logout');
    Route::post('/news/refresh', 'ApiNewsController@refresh');
    Route::post('/news/me', 'ApiNewsController@me');

    Route::post('/news/logout', 'ApiNewsController@logout');
    Route::post('/news/notification/token', 'ApiNewsController@newsNotificationToken');
    
	Route::get('/news/posts', 'ApiNewsController@newsPosts');    
    Route::get('/news/post/single', 'ApiNewsController@newsPostSingle');
    Route::get('/news/notice', 'ApiNewsController@newsNotice');
});

