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

Auth::routes();

Route::get('/projects/{project}/grid', 'ProjectController@grid');


Route::group(['middleware' => 'auth'], function() {

 /*   Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('/current_user', 'UserController@getCurrent');
    Route::get('/dashboard', 'HomeController@index');

    Route::get('/concerts', 'ConcertController@index')->name('concerts');
    Route::get('/concerts/{concert}/voices', 'ConcertController@voices')->name('concert.voices');
    Route::get('/concerts/accept/{concert}', 'ConcertController@accept')->name('concert.accept');
    Route::get('/concerts/decline/{concert}', 'ConcertController@decline')->name('concert.decline');
    Route::post('concerts/{concert}/createComment', 'ConcertController@createComment')->name('concert.createComment');

    //Route::get('/projects', 'ProjectController@index')->name('projects');
    Route::get('/projects/loadItems', 'ProjectController@loadItems')->name('project.loadItems');
    Route::get('/project/{project}', 'ProjectController@show')->name('project.show');
    Route::post('/project/{project}/removeComment/{comment}', 'ProjectController@removeComment')->name('project.removeComment');
    Route::get('/projects/accept/{project}', 'ProjectController@accept')->name('project.accept');
    Route::get('/projects/decline/{project}', 'ProjectController@decline')->name('project.decline');

    Route::get('/rehearsals', 'RehearsalController@index')->name('rehearsals');

    Route::post('rehearsal/{rehearsal}/createComment', 'RehearsalController@createComment')->name('rehearsal.createComment');

    Route::get('/voice/showSet/{user?}', 'VoiceController@showSet')->name('voice.showSet');
    Route::post('/voice/set/{user?}', 'VoiceController@set')->name('voice.set');
    Route::get('/semesters/accept/{semester}', 'SemesterController@accept')->name('semester.accept');
    Route::get('/semesters/decline/{semester}', 'SemesterController@decline')->name('semester.decline');

    Route::get('/profile/edit/{user?}', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update/{user}', 'ProfileController@update')->name('profile.update');
    Route::get('/profile/changePassword', 'ProfileController@changePassword')->name('profile.changePassword');
    Route::post('/profile/updatePassword', 'ProfileController@updatePassword')->name('profile.updatePassword');
    Route::post('/profile/uploadProfilePicture', 'ProfileController@uploadProfilePicture')->name('profile.uploadProfilePicture');

    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
        // Route::get('/', 'AdminController@welcome');
        Route::get('/users/export', 'UserController@export')->name('users.export');
        Route::post('/users/multi-archive', 'UserController@multiArchive')->name('users.multiArchive');
        Route::resource('users', 'UserController', ['middleware' => ['permission:manageUsers'], 'uses' => 'UserController']);
        Route::get('/load-users', 'UserController@loadUsers')->name('users.load');
        // Concerts
        Route::get('/concert/export-participants/{concert}', 'ConcertController@exportParticipants')->name('concert.exportParticipants');
        Route::get('/concert/create', 'ConcertController@create')->name('concert.create');
        Route::post('/concert/store', 'ConcertController@store')->name('concert.store');
        Route::get('/concert/edit/{concert}', 'ConcertController@edit')->name('concert.edit');
        Route::put('/concert/update/{concert}', 'ConcertController@update')->name('concert.update');
        Route::delete('/concert/delete/{concert}', 'ConcertController@destroy')->name('concert.delete');
        Route::get('/concert/{concert}/addDate', 'ConcertController@addDate')->name('concert.addDate');
        Route::post('/concert/{concert}/addDate', 'ConcertController@saveDate')->name('concert.saveDate');
        Route::post('/concert/{concert}/saveVoices', 'ConcertController@saveVoices')->name('concert.saveVoices');
        Route::get('/concert/{concert}/editVoices', 'ConcertController@editVoices')->name('concert.editVoices');
        Route::get('/concert/{concert}/removeVoice/{voice}', 'ConcertController@removeVoice')->name('concert.removeVoice');
        Route::get('/concert/{concert}/setVoice/{user?}', 'ConcertController@showSetUserVoice')->name('concert.showSetUserVoice');
        Route::get('/concert/{concert}/setVoice', 'ConcertController@showSetUserVoices')->name('concert.showSetUserVoices');
        Route::post('/concert/{concert}/setVoice/{user?}', 'ConcertController@setUserVoice')->name('concert.setUserVoice');
        Route::post('/concert/{concert}/setVoice', 'ConcertController@setUserVoices')->name('concert.setUserVoices');
        Route::get('/concert/{concert}/addUser', 'ConcertController@showAddUser')->name('concert.showAddUser');
        Route::post('/concert/{concert}/addUser', 'ConcertController@addUser')->name('concert.addUser');
        Route::get('/concert/load-participants/{concert}', 'ConcertController@loadParticipants')->name('concert.loadParticipants');
        Route::post('/concert/{concert}/removeParticipants', 'ConcertController@removeParticipants')->name('concert.removeParticipants');
        Route::delete('/concert/{concert}/removeParticipant', 'ConcertController@removeParticipant')->name('concert.removeParticipant');
        Route::get('/concert/{concert}/confirm/{user?}', 'ConcertController@confirm')->name('concert.confirm');
        Route::get('/concert/{concert}/excuse/{user?}', 'ConcertController@excuse')->name('concert.excuse');
        Route::get('/concert/{concert}/setUnexcused/{user?}', 'ConcertController@setUnexcused')->name('concert.setUnexcused');
        // Projects
        Route::get('/project/export-participants/{project}', 'ProjectController@exportParticipants')->name('project.exportParticipants');
        Route::get('/projects/create', 'ProjectController@create')->name('project.create');
        Route::post('/projects/store', 'ProjectController@store')->name('project.store');
        Route::get('/project/edit/{project}', 'ProjectController@edit')->name('project.edit');
        Route::put('/project/update/{project}', 'ProjectController@update')->name('project.update');
        Route::delete('/project/delete/{project}', 'ProjectController@destroy')->name('project.delete');
        Route::post('projects/multiDelete', 'ProjectController@multiDelete')->name('project.multiDelete');
        Route::post('/project/{project}/saveVoices', 'ProjectController@saveVoices')->name('project.saveVoices');
        Route::get('/project/{project}/editVoices', 'ProjectController@editVoices')->name('project.editVoices');
        Route::get('/project/{project}/removeVoice/{voice}', 'ProjectController@removeVoice')->name('project.removeVoice');
        Route::get('/project/{project}/setVoice/{user?}', 'ProjectController@showSetUserVoice')->name('project.showSetUserVoice');
        Route::get('/project/{project}/setVoice', 'ProjectController@showSetUserVoices')->name('project.showSetUserVoices');
        Route::post('/project/{project}/setVoice/{user?}', 'ProjectController@setUserVoice')->name('project.setUserVoice');
        Route::post('/project/{project}/setVoice', 'ProjectController@setUserVoices')->name('project.setUserVoices');
        Route::get('/project/{project}/addUser', 'ProjectController@showAddUser')->name('project.showAddUser');
        Route::post('/project/{project}/addUser', 'ProjectController@addUser')->name('project.addUser');
        Route::post('/project/{project}/removeParticipants', 'ProjectController@removeParticipants')->name('project.removeParticipants');
        Route::delete('/project/{project}/removeParticipant', 'ProjectController@removeParticipant')->name('project.removeParticipant');
        // Rehearsals
        Route::get('/rehearsal/create', 'RehearsalController@create')->name('rehearsal.create');
        Route::post('/rehearsal/store', 'RehearsalController@store')->name('rehearsal.store');
        Route::get('/rehearsal/edit/{rehearsal}', 'RehearsalController@edit')->name('rehearsal.edit');
        Route::post('/rehearsal/update/{rehearsal}', 'RehearsalController@update')->name('rehearsal.update');
        Route::delete('/rehearsal/delete/{rehearsal}', 'RehearsalController@destroy')->name('rehearsal.delete');
        Route::get('/rehearsal/{rehearsal}/addUser', 'RehearsalController@showAddUser')->name('rehearsal.showAddUser');
        Route::post('/rehearsal/{rehearsal}/addUser', 'RehearsalController@addUser')->name('rehearsal.addUser');
        Route::get('/rehearsal/{rehearsal}/ajaxConfirm/{user?}', 'RehearsalController@ajaxConfirm')->name('rehearsal.confirm');
        Route::get('/rehearsal/{rehearsal}/ajaxExcuse/{user?}', 'RehearsalController@ajaxExcuse')->name('rehearsal.excuse');
        Route::get('/rehearsal/{rehearsal}/ajaxSetUnexcused/{user?}', 'RehearsalController@ajaxSetUnexcused')->name('rehearsal.setUnexcused');
        Route::get('/rehearsal/load-participants/{rehearsal}', 'RehearsalController@loadParticipants')->name('rehearsal.loadParticipants');
        Route::get('/rehearsal/{rehearsal}/participants', 'RehearsalController@participants')->name('rehearsal.participants');
        Route::get('/rehearsal/{rehearsal}/addProjectParticipants', 'RehearsalController@addProjectParticipants')->name('rehearsal.addProjectParticipants');
        Route::post('/rehearsal/{rehearsal}/removeParticipants', 'RehearsalController@removeParticipants')->name('rehearsal.removeParticipants');
        Route::delete('/rehearsal/{rehearsal}/removeParticipant', 'RehearsalController@removeParticipant')->name('rehearsal.removeParticipant');
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
        Route::post('/semester/{semester}/removeParticipants', 'SemesterController@removeParticipants')->name('semester.removeParticipants');
        Route::delete('/semesters/{semester}/removeParticipant', 'SemesterController@removeParticipant')->name('semester.removeParticipant');
        Route::get('/semesters/{semester}/participants', 'SemesterController@participants')->name('semester.participants');
        Route::get('/semesters/load-participants/{semester}', 'SemesterController@loadParticipants')->name('semester.loadParticipants');
    });*/
});

Route::get('/{vue_capture?}', function () {
    return view('index');
})->where('vue_capture', '[\/\w\.-]*');