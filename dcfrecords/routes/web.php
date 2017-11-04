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

Route::prefix('api')->namespace('Api')->group(function () {
	header('Access-Control-Allow-Origin: *');

	Route::group([], function(){
		\Config::set('user', \App\Models\User::authViaGuid());

		if( empty(\Config::get('user.id_user')) ){
			return ['status' => "failed", 'Message'];
		}
		Route::post('records', 'RecordsController@getRecordsAction');
		Route::post('record', 'RecordsController@getRecordAction');
		Route::post('record/save', 'RecordsController@saveRecordAction');

		Route::post('contacts', 'ContactsController@getContactsAction');
		Route::post('contact', 'ContactsController@getContactAction');
		Route::post('contact/save', 'ContactsController@saveContactAction');
		Route::post('contact/delete', 'ContactsController@deleteContactAction');
		Route::post('contact/update-status', 'ContactsController@updateStatusAction');

		Route::post('event/delete', 'EventsController@deleteEventAction');
	});

	Route::post('user/get', 'UserController@getUserAction');
	Route::post('user/create', 'UserController@createUserAction');
	Route::post('user/save', 'UserController@saveUserAction');
	Route::post('user/login', 'UserController@userLoginAction');
	Route::post('user/username', 'UserController@usernameAction');
	Route::post('user/username-email', 'UserController@usernameEmailAction');
	Route::post('user/username-available', 'UserController@usernameAvailableAction');
	Route::post('user/reset-password', 'UserController@resetPasswordAction');
});

Route::get('/', function () {
    return view('layout');
});
