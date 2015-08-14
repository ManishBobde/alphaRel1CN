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
//Web Routes
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');

Route::get('/', function () {
    return view('welcome');
});
/**
 * API Routes defined for each of the incoming requests
 * Each request after login needs to be validated
 */
Route::group(array('prefix' => 'api/v1'), function() {

    Route::post('auth/register', 'UserController@registerUser');

    Route::post('auth/login', 'UserController@loginUser');

    Route::get('user/profile','UserController@getUserProfile');

    Route::get('college/user/details','UserController@getUserDetails');

    Route::get('auth/user/logout', 'UserController@logoutUser');

    Route::get('messages', 'MessageController@retrieveMessages');

    Route::post('compose/message', 'MessageController@submitMessages');

    Route::get('news', 'NewsController@retrieveNews');

    Route::get('events', 'EventsController@retrieveEvents');

    Route::post('create/news', 'NewsController@createNews');

    Route::post('create/event', 'EventsController@createEvents');

    Route::get('user/roles/{id}', 'AdminController@retrieveRoleBasedFeatures');

    Route::get('user/{id}/modules', 'ModuleController@retrieveAccessibleModulesList');

    Route::post('college/user/register', 'CollegeController@registerCollege');




});

//Route::post('auth/register', 'UserController@postRegister');

Route::post('register/college', 'UserController@postRegister');

