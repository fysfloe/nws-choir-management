<?php

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
    Route::get('/projects/{project}/participants', 'ProjectController@participants');
    Route::get('/projects/{project}/other_users', 'ProjectController@otherUsers');
    Route::post('/projects/remove_multi', 'ProjectController@removeMulti');
    Route::get('/projects/{project}/grid', 'ProjectController@grid');
    Route::post('/projects/{project}/set_voice', 'ProjectController@setVoice');
    Route::post('/projects/{project}/add_participants', 'ProjectController@addParticipants');
    Route::delete('/projects/{project}/remove_participants', 'ProjectController@removeParticipants');
    Route::post('/admin/projects/export-participants/{project}', 'ProjectController@exportParticipants')->name('project.exportParticipants');
    Route::resource('projects', 'ProjectController');

    Route::post('/rehearsals/accept/{rehearsal}/{user_id?}', 'RehearsalController@accept');
    Route::post('/rehearsals/decline/{rehearsal}/{user_id?}', 'RehearsalController@decline');
    Route::get('/rehearsals/{rehearsal}/participants', 'RehearsalController@participants');
    Route::get('/rehearsals/{rehearsal}/other_users', 'RehearsalController@otherUsers');
    Route::post('/rehearsals/{rehearsal}/confirm/{user}', 'RehearsalController@confirm');
    Route::post('/rehearsals/{rehearsal}/excuse/{user}', 'RehearsalController@excuse');
    Route::post('/rehearsals/{rehearsal}/set_unexcused/{user}', 'RehearsalController@setUnexcused');
    Route::post('/rehearsals/{rehearsal}/add_participants', 'RehearsalController@addParticipants');
    Route::delete('/rehearsals/{rehearsal}/remove_participants', 'RehearsalController@removeParticipants');
    Route::resource('rehearsals', 'RehearsalController');

    Route::resource('comments', 'CommentController');

    Route::post('/semesters/accept/{semester}/{user_id?}', 'SemesterController@accept');
    Route::post('/semesters/decline/{semester}/{user_id?}', 'SemesterController@decline');
    Route::get('/semesters/options', 'SemesterController@options');
    Route::get('/semesters/{semester}/participants', 'SemesterController@participants');
    Route::get('/semesters/{semester}/other_users', 'SemesterController@otherUsers');
    Route::post('/semesters/{semester}/add_participants', 'SemesterController@addParticipants');
    Route::delete('/semesters/{semester}/remove_participants', 'SemesterController@removeParticipants');
    Route::resource('semesters', 'SemesterController');

    Route::post('/concerts/accept/{concert}/{user_id?}', 'ConcertController@accept');
    Route::post('/concerts/decline/{concert}/{user_id?}', 'ConcertController@decline');
    Route::get('/concerts/options', 'ConcertController@options');
    Route::get('/concerts/{concert}/participants', 'ConcertController@participants');
    Route::get('/concerts/{concert}/other_users', 'ConcertController@otherUsers');
    Route::post('/concerts/{concert}/confirm/{user}', 'ConcertController@confirm');
    Route::post('/concerts/{concert}/excuse/{user}', 'ConcertController@excuse');
    Route::post('/concerts/{concert}/set_unexcused/{user}', 'ConcertController@setUnexcused');
    Route::post('/concerts/{concert}/add_participants', 'ConcertController@addParticipants');
    Route::delete('/concerts/{concert}/remove_participants', 'ConcertController@removeParticipants');
    Route::resource('concerts', 'ConcertController');

    Route::get('/user', 'UserController@getCurrent');
    Route::resource('users', 'UserController', ['middleware' => ['permission:manageUsers'], 'uses' => 'UserController']);

    Route::get('dashboard', 'HomeController@index');

    Route::get('/countries/options', 'CountryController@options');

    Route::get('/voices/options', 'VoiceController@options');
    Route::post('/voices/set_multi', 'VoiceController@setMulti');
});
