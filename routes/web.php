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
    Route::get('/rehearsals', 'RehearsalController@index')->name('rehearsals');
    Route::get('/rehearsal/{rehearsal}', 'RehearsalController@show')->name('rehearsal.show');
    Route::get('/rehearsal/{rehearsal}/participants', 'RehearsalController@participants')->name('rehearsal.participants');
    Route::get('/rehearsal/accept/{rehearsal}', 'RehearsalController@accept')->name('rehearsal.accept');
    Route::get('/rehearsal/decline/{rehearsal}', 'RehearsalController@decline')->name('rehearsal.decline');
    Route::get('/voice/showSet/{user}', 'VoiceController@showSet')->name('voice.showSet');
    Route::post('/voice/set/{user}', 'VoiceController@set')->name('voice.set');
    Route::get('/semester/accept/{semester}', 'SemesterController@accept')->name('semester.accept');
    Route::get('/semester/decline/{semester}', 'SemesterController@decline')->name('semester.decline');

    Route::get('/profile/edit/{user}', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update/{user}', 'ProfileController@update')->name('profile.update');
    Route::get('/profile/changePassword', 'ProfileController@changePassword')->name('profile.changePassword');
    Route::post('/profile/updatePassword', 'ProfileController@updatePassword')->name('profile.updatePassword');

    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
        // Route::get('/', 'AdminController@welcome');
        Route::resource('users', 'UserController', ['middleware' => ['permission:manageUsers'], 'uses' => 'UserController']);
        Route::get('/users/export', 'UserController@export')->name('users.export');
        // Concerts
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
        Route::post('/concert/{concert}/setVoice/{user}', 'ConcertController@setUserVoice')->name('concert.setUserVoice');
        Route::get('/concert/{concert}/addUser', 'ConcertController@showAddUser')->name('concert.showAddUser');
        Route::post('/concert/{concert}/addUser', 'ConcertController@addUser')->name('concert.addUser');
        // Rehearsals
        Route::get('/rehearsals/create', 'RehearsalController@create')->name('rehearsal.create');
        Route::post('/rehearsals/store', 'RehearsalController@store')->name('rehearsal.store');
        Route::get('/rehearsal/edit/{rehearsal}', 'RehearsalController@edit')->name('rehearsal.edit');
        Route::post('/rehearsal/update', 'RehearsalController@update')->name('rehearsal.update');
        Route::delete('/rehearsal/delete', 'RehearsalController@destroy')->name('rehearsal.delete');
        Route::get('/rehearsal/{rehearsal}/addUser', 'RehearsalController@showAddUser')->name('rehearsal.showAddUser');
        Route::post('/rehearsal/{rehearsal}/addUser', 'RehearsalController@addUser')->name('rehearsal.addUser');
        // Voices
        Route::resource('/voices', 'VoiceController');
        // Roles
        Route::get('/role/set/{user}', 'RoleController@showSet')->name('role.showSet');
        Route::post('/role/set', 'RoleController@set')->name('role.set');
        // Semesters
        Route::resource('/semesters', 'SemesterController');
        Route::get('/semesters/{semester}/addUser', 'SemesterController@showAddUser')->name('semester.showAddUser');
        Route::post('/semesters/{semester}/addUser', 'SemesterController@addUser')->name('semester.addUser');
        Route::get('/semesters/{semester}/participants', 'SemesterController@participants')->name('semester.participants');
    });
});

Auth::routes();
