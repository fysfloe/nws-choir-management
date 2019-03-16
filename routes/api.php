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

Route::group(['api', 'middleware' => 'auth:api'], function () {
    Route::post('/projects/accept/{project}/{user_id?}', 'ProjectController@accept');
    Route::post('/projects/decline/{project}/{user_id?}', 'ProjectController@decline');
    Route::get('/projects/options', 'ProjectController@options');
    Route::get('/projects/participants/{project}', 'ProjectController@participants');
    Route::resource('projects', 'ProjectController');

    Route::post('/rehearsals/accept/{rehearsal}/{user_id?}', 'RehearsalController@accept');
    Route::post('/rehearsals/decline/{rehearsal}/{user_id?}', 'RehearsalController@decline');
    Route::resource('rehearsals', 'RehearsalController');

    Route::resource('comments', 'CommentController');

    Route::post('/semesters/accept/{semester}/{user_id?}', 'SemesterController@accept');
    Route::post('/semesters/decline/{semester}/{user_id?}', 'SemesterController@decline');
    Route::get('/semesters/options', 'SemesterController@options');
    Route::resource('semesters', 'SemesterController');
    Route::get('/semesters/load_participants/{semester}', 'SemesterController@loadParticipants');

    Route::post('/concerts/accept/{concert}/{user_id?}', 'ConcertController@accept');
    Route::post('/concerts/decline/{concert}/{user_id?}', 'ConcertController@decline');
    Route::resource('concerts', 'ConcertController');
    Route::get('/concerts/load_participants/{concert}', 'ConcertController@loadParticipants');

    Route::resource('users', 'UserController', ['middleware' => ['permission:manageUsers'], 'uses' => 'UserController']);
    Route::get('/user', 'UserController@getCurrent');

    Route::get('dashboard', 'HomeController@index');

    Route::get('/countries/options', 'CountryController@options');
    Route::get('/voices/options', 'VoiceController@options');
});
