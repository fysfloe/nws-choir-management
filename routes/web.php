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

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('/dashboard', 'HomeController@index');

    Route::get('/concerts', 'ConcertController@index')->name('concerts');
    Route::get('/concert/{concert}', 'ConcertController@show')->name('concert.show');
    Route::get('/concert/{concert}/participants', 'ConcertController@participants')->name('concert.participants');
    Route::get('/concert/{concert}/voices', 'ConcertController@voices')->name('concert.voices');
    Route::get('/concert/accept/{concert}', 'ConcertController@accept')->name('concert.accept');
    Route::get('/concert/decline/{concert}', 'ConcertController@decline')->name('concert.decline');

    Route::get('/projects', 'ProjectController@index')->name('projects');
    Route::get('/project/{project}', 'ProjectController@show')->name('project.show');
    Route::get('/project/{project}/participants', 'ProjectController@participants')->name('project.participants');
    Route::get('/project/{project}/voices', 'ProjectController@voices')->name('project.voices');
    Route::get('/project/accept/{project}', 'ProjectController@accept')->name('project.accept');
    Route::get('/project/decline/{project}', 'ProjectController@decline')->name('project.decline');

    Route::get('/rehearsals', 'RehearsalController@index')->name('rehearsals');
    Route::get('/rehearsal/{rehearsal}', 'RehearsalController@show')->name('rehearsal.show');
    Route::get('/rehearsal/{rehearsal}/participants', 'RehearsalController@participants')->name('rehearsal.participants');
    Route::get('/rehearsal/accept/{rehearsal}', 'RehearsalController@accept')->name('rehearsal.accept');
    Route::get('/rehearsal/decline/{rehearsal}', 'RehearsalController@decline')->name('rehearsal.decline');
    Route::get('/voice/showSet/{user}', 'VoiceController@showSet')->name('voice.showSet');
    Route::post('/voice/set/{user}', 'VoiceController@set')->name('voice.set');
    Route::get('/semester/accept/{semester}', 'SemesterController@accept')->name('semester.accept');
    Route::get('/semester/decline/{semester}', 'SemesterController@decline')->name('semester.decline');

    Route::get('/profile/edit/{user?}', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update/{user}', 'ProfileController@update')->name('profile.update');
    Route::get('/profile/changePassword', 'ProfileController@changePassword')->name('profile.changePassword');
    Route::post('/profile/updatePassword', 'ProfileController@updatePassword')->name('profile.updatePassword');
    Route::post('/profile/uploadProfilePicture', 'ProfileController@uploadProfilePicture')->name('profile.uploadProfilePicture');

    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
        // Route::get('/', 'AdminController@welcome');
        Route::get('/users/export', 'UserController@export')->name('users.export');
        Route::get('/users/multi-archive', 'UserController@multiArchive')->name('users.multiArchive');
        Route::resource('users', 'UserController', ['middleware' => ['permission:manageUsers'], 'uses' => 'UserController']);
        Route::get('/load-users', 'UserController@loadUsers')->name('users.load');
        // Concerts
        Route::get('/concert/export-participants/{concert}', 'ConcertController@exportParticipants')->name('concert.exportParticipants');
        Route::get('/concerts/create', 'ConcertController@create')->name('concert.create');
        Route::post('/concerts/store', 'ConcertController@store')->name('concert.store');
        Route::get('/concert/edit/{concert}', 'ConcertController@edit')->name('concert.edit');
        Route::put('/concert/update/{concert}', 'ConcertController@update')->name('concert.update');
        Route::delete('/concert/delete/{concert}', 'ConcertController@destroy')->name('concert.delete');
        Route::get('/concert/{concert}/addDate', 'ConcertController@addDate')->name('concert.addDate');
        Route::post('/concert/{concert}/addDate', 'ConcertController@saveDate')->name('concert.saveDate');
        Route::post('/concert/{concert}/saveVoices', 'ConcertController@saveVoices')->name('concert.saveVoices');
        Route::get('/concert/{concert}/editVoices', 'ConcertController@editVoices')->name('concert.editVoices');
        Route::get('/concert/{concert}/removeVoice/{voice}', 'ConcertController@removeVoice')->name('concert.removeVoice');
        Route::get('/concert/{concert}/setVoice/{user}', 'ConcertController@showSetUserVoice')->name('concert.showSetUserVoice');
        Route::get('/concert/{concert}/setVoice', 'ConcertController@showSetUserVoices')->name('concert.showSetUserVoices');
        Route::post('/concert/{concert}/setVoice/{user}', 'ConcertController@setUserVoice')->name('concert.setUserVoice');
        Route::post('/concert/{concert}/setVoice', 'ConcertController@setUserVoices')->name('concert.setUserVoices');
        Route::get('/concert/{concert}/addUser', 'ConcertController@showAddUser')->name('concert.showAddUser');
        Route::post('/concert/{concert}/addUser', 'ConcertController@addUser')->name('concert.addUser');
        Route::get('/concert/load-participants/{concert}', 'ConcertController@loadParticipants')->name('concert.loadParticipants');
        // Projects
        Route::get('/project/export-participants/{project}', 'ProjectController@exportParticipants')->name('project.exportParticipants');
        Route::get('/projects/create', 'ProjectController@create')->name('project.create');
        Route::post('/projects/store', 'ProjectController@store')->name('project.store');
        Route::get('/project/edit/{project}', 'ProjectController@edit')->name('project.edit');
        Route::put('/project/update/{project}', 'ProjectController@update')->name('project.update');
        Route::delete('/project/delete/{project}', 'ProjectController@destroy')->name('project.delete');
        Route::post('/project/{project}/saveVoices', 'ProjectController@saveVoices')->name('project.saveVoices');
        Route::get('/project/{project}/editVoices', 'ProjectController@editVoices')->name('project.editVoices');
        Route::get('/project/{project}/removeVoice/{voice}', 'ProjectController@removeVoice')->name('project.removeVoice');
        Route::get('/project/{project}/setVoice/{user}', 'ProjectController@showSetUserVoice')->name('project.showSetUserVoice');
        Route::get('/project/{project}/setVoice', 'ProjectController@showSetUserVoices')->name('project.showSetUserVoices');
        Route::post('/project/{project}/setVoice/{user}', 'ProjectController@setUserVoice')->name('project.setUserVoice');
        Route::post('/project/{project}/setVoice', 'ProjectController@setUserVoices')->name('project.setUserVoices');
        Route::get('/project/{project}/addUser', 'ProjectController@showAddUser')->name('project.showAddUser');
        Route::post('/project/{project}/addUser', 'ProjectController@addUser')->name('project.addUser');
        Route::get('/project/load-participants/{project}', 'ProjectController@loadParticipants')->name('project.loadParticipants');
        // Rehearsals
        Route::get('/rehearsals/create', 'RehearsalController@create')->name('rehearsal.create');
        Route::post('/rehearsals/store', 'RehearsalController@store')->name('rehearsal.store');
        Route::get('/rehearsal/edit/{rehearsal}', 'RehearsalController@edit')->name('rehearsal.edit');
        Route::post('/rehearsal/update/{rehearsal}', 'RehearsalController@update')->name('rehearsal.update');
        Route::delete('/rehearsal/delete', 'RehearsalController@destroy')->name('rehearsal.delete');
        Route::get('/rehearsal/{rehearsal}/addUser', 'RehearsalController@showAddUser')->name('rehearsal.showAddUser');
        Route::post('/rehearsal/{rehearsal}/addUser', 'RehearsalController@addUser')->name('rehearsal.addUser');
        Route::get('/rehearsal/{rehearsal}/ajaxConfirm/{user}', 'RehearsalController@ajaxConfirm')->name('rehearsal.confirm');
        Route::get('/rehearsal/{rehearsal}/ajaxExcuse/{user}', 'RehearsalController@ajaxExcuse')->name('rehearsal.excuse');
        Route::get('/rehearsal/{rehearsal}/ajaxSetUnexcused/{user}', 'RehearsalController@ajaxSetUnexcused')->name('rehearsal.setUnexcused');
        // Voices
        Route::resource('/voices', 'VoiceController');
        Route::get('/voice/set', 'VoiceController@showSetMulti')->name('voice.showSetMulti');
        Route::post('/voice/set', 'VoiceController@setMulti')->name('voice.setMulti');
        // Roles
        Route::get('/role/set/{user}', 'RoleController@showSet')->name('role.showSet');
        Route::post('/role/set/{user}', 'RoleController@set')->name('role.set');
        Route::get('/role/set', 'RoleController@showSetMulti')->name('role.showSetMulti');
        Route::post('/role/set', 'RoleController@setMulti')->name('role.setMulti');
        // Semesters
        Route::resource('/semesters', 'SemesterController');
        Route::get('/semesters/{semester}/addUser', 'SemesterController@showAddUser')->name('semester.showAddUser');
        Route::post('/semesters/{semester}/addUser', 'SemesterController@addUser')->name('semester.addUser');
        Route::get('/semesters/{semester}/participants', 'SemesterController@participants')->name('semester.participants');
    });
});

Auth::routes();
