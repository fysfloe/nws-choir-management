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
    Route::resource('projects', 'ProjectController');
    Route::get('/projects/load_participants/{project}', 'ProjectController@loadParticipants');

    Route::resource('rehearsals', 'RehearsalController');

    Route::resource('comments', 'CommentController');

    Route::get('/semesters/load_options', 'SemesterController@loadOptions');
    Route::resource('semesters', 'SemesterController');
    Route::get('/semesters/load_participants/{semester}', 'SemesterController@loadParticipants');

    Route::resource('concerts', 'ConcertController');
    Route::get('/concerts/load_participants/{concert}', 'ConcertController@loadParticipants');

    Route::resource('users', 'UserController', ['middleware' => ['permission:manageUsers'], 'uses' => 'UserController']);
    Route::get('/user', 'UserController@getCurrent');

    Route::get('dashboard', 'HomeController@index');
});
