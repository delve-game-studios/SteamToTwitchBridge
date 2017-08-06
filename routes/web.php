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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth', 'prefix' => 'service'], function() {

	/** Auth routes **/

	Route::get('steam/auth', [
		'uses' => 'Services\SteamController@auth',
		'as' => 'services.auth.service-steam'
	]);

	Route::get('twitch/auth', [
		'uses' => 'Services\TwitchController@auth',
		'as' => 'services.auth.service-twitch'
	]);
	
	Route::post('facebook/auth', [
		'uses' => 'Services\FacebookController@auth',
		'as' => 'services.auth.service-facebook'
	]);

	/** Callbacks routes **/

	Route::get('steam/callback', [
		'uses' => 'Services\SteamController@callback',
		'as' => 'services.callback-steam'
	]);

	Route::get('twitch/callback', [
		'uses' => 'Services\TwitchController@callback',
		'as' => 'service.callback-twitch'
	]);

	Route::post('facebook/save', [
		'uses' => 'Services\FacebookController@save',
		'as' => 'services.facebook.save'
	]);

	Route::post('facebook/destroy', [
		'uses' => 'Services\FacebookController@destroy',
		'as' => 'services.facebook.unlink'
	]);

	/** Cron routes **/

	Route::post('service-sequence', [
		'uses' => 'ServicesController@serviceSequence',
		'as' => 'services.sequence'
	]);
});

Route::group(['middleware' => 'auth', 'prefix' => 'user'], function() {
	Route::resource('/', 'UsersController');
	Route::get('show/{id}', [
		'uses' => 'UsersController@show',
		'as' => 'users.show'
	]);
	Route::get('profile', [
		'uses' => 'UsersController@profile',
		'as' => 'users.profile'
	]);
});

Route::group(['middleware' => 'auth', 'prefix' => 'services'], function() {
	Route::resource('/', 'ServicesController');
	
	Route::post('save', [
		'uses' => 'ServicesController@save',
		'as' => 'services.save'
	]);
});

Route::group(['middleware' => 'auth', 'prefix' => ''], function() {
	Route::get('home', 'UsersController@profile');
	Route::get('subscribe', 'SubscriptionsController@index');
	// Route::get('home', [
	// 	'uses' => 'HomeController@index',
	// 	'as' => 'home'
	// ]);
});

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function($router) {
	Route::get('try/loginAs/{id}', 'UsersController@loginAs');
});