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

	Route::get('twitch/getStreamData', 'Services\TwitchController@getStreamData');
	
	Route::post('twitch/setGameAndStatus', 'Services\TwitchController@setGameAndStatus');

	Route::get('steam/getProfileData', 'Services\SteamController@getProfileData');

	/** Auth routes **/

	Route::get('steam/auth', [
		'uses' => 'Services\SteamController@auth',
		'as' => 'services.auth.service-steam'
	]);

	Route::get('twitch/auth', [
		'uses' => 'Services\TwitchController@auth',
		'as' => 'services.auth.service-twitch'
	]);
	
	Route::get('youtube/auth', [
		'uses' => 'Services\TwitchController@auth',
		'as' => 'services.auth.service-youtube'
	]);
	
	Route::get('facebook/auth', [
		'uses' => 'Services\TwitchController@auth',
		'as' => 'services.auth.service-facebook'
	]);
	
	Route::get('obs/auth', [
		'uses' => 'Services\TwitchController@auth',
		'as' => 'services.auth.service-obs'
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
	
	Route::get('youtube/callback', [
		'uses' => 'Services\YoutubeController@callback',
		'as' => 'services.callback-youtube'
	]);
	
	Route::get('facebook/callback', [
		'uses' => 'Services\FacebookController@callback',
		'as' => 'services.callback-facebook'
	]);
	
	Route::get('obs/callback', [
		'uses' => 'Services\ObsController@callback',
		'as' => 'services.callback-obs'
	]);

	Route::get('twitch/update', function() {
		$SteamController = app()->make('App\Http\Controllers\Services\SteamController');
		$TwitchController = app()->make('App\Http\Controllers\Services\TwitchController');
		$steamData = $SteamController->callAction('getProfileData', []);
		$updateUserGames = false;

		if(!empty($steamData['gameextrainfo'])) {
			if(!$game = \App\UserGame::byAppid($steamData['gameid'])) { // returning Object || null
				$game = (object)['title' => $steamData['gameextrainfo']]; // if null return object
				$updateUserGames = true;
			}
			$TwitchController->callAction('setGameAndStatus', [['game' => $game->title]]);
		}

		if($updateUserGames) $SteamController->callAction('updateUserGames', []);

		return redirect('/user/profile')->send();
	});

	Route::get('steam/updateUserGames', [
		'uses' => 'Services\SteamController@updateUserGames',
		'as' => 'services.steam.update-games'
	]);

	Route::get('checkStream', 'ServicesController@updateGameForAllUsers');
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
	// Route::get('home', [
	// 	'uses' => 'HomeController@index',
	// 	'as' => 'home'
	// ]);
});

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function($router) {
	Route::get('try/loginAs/{id}', 'UsersController@loginAs');
});