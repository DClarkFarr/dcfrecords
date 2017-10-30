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

Route::get('/', function () {
    return view('layout');
});
