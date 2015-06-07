<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function(){return view('home');});
Route::get('/app', ['middleware'=>'auth', function(){return view('app');}]);
Route::get('/login', 'AuthController@loginWithGoogle');
Route::get('/logout', function(){
	Auth::logout();
	return redirect('/');
});

//GET ALL FOR THE VARIOUS CONTROLLERS
Route::get('api/years', 'YearController@getYears');
Route::get('api/days', 'DayController@getDays');
Route::get('api/speakers', 'SpeakerController@getSpeakers');
Route::get('api/exhibitors', 'ExhibitorController@getExhibitors');

Route::group(['prefix'=>'api', 'middleware'=>'auth'], function(){

	//YEARS CONTROLLER
	Route::post('/years', 'YearController@store');
	Route::put('/years/{id}/activate', 'YearController@activateYear');
	Route::put('/years/{id}', 'YearController@update');
	Route::delete('/years/{id}', 'YearController@destroy');

	//DAYS CONTROLLER
	Route::post('/days', 'DayController@storeDay');
	Route::post('/days/{id}/sessions', 'DayController@storeSession');
	Route::post('/days/{dayId}/sessions/{sessionId}/events', 'DayController@storeEvent');

	Route::put('/days/{id}', 'DayController@updateDay');
	Route::put('/days/{id}/sessions/{sessionId}', 'DayController@updateSession');
	Route::put('/days/{id}/sessions/{sessionId}/events/{eventId}', 'DayController@updateEvent');

	Route::delete('/days/{id}', 'DayController@destroyDay');
	Route::delete('/days/{dayId}/sessions/{sessionId}', 'DayController@destroySession');
	Route::delete('/days/{dayId}/sessions/{sessionId}/events/{eventId}', 'DayController@destroyEvent');

	//SPEAKERS CONTROLLER
	Route::Post('/speakers', 'SpeakerController@store');
	Route::Put('/speakers/{id}', 'SpeakerController@update');
	Route::Delete('/speakers/{id}', 'SpeakerController@destroy');

	//EXHIBITORS CONTROLLER
	Route::Post('/exhibitors', 'ExhibitorController@store');
	Route::Put('/exhibitors/{id}', 'ExhibitorController@update');
	Route::Delete('/exhibitors/{id}', 'ExhibitorController@destroy');
});

