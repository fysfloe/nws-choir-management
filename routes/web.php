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
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');

    Route::get('/concerts', 'ConcertController@index')->name('concerts');
    Route::get('/concert/{concert}', 'ConcertController@show')->name('concert.show');
    Route::get('/rehearsals', 'RehearsalController@index')->name('rehearsals');
    Route::get('/rehearsal/{rehearsal}', 'RehearsalController@show')->name('rehearsal.show');
    Route::get('/rehearsal/accept/{rehearsal}', 'RehearsalController@accept')->name('rehearsal.accept');
    Route::get('/rehearsal/decline/{rehearsal}', 'RehearsalController@decline')->name('rehearsal.decline');
    Route::get('/voice/showSet/{user}', 'VoiceController@showSet')->name('voice.showSet');
    Route::post('/voice/set', 'VoiceController@set')->name('voice.set');

    Route::get('/profile/edit/{id}', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update/{id}', 'ProfileController@update')->name('profile.update');
    Route::get('/profile/changePassword', 'ProfileController@changePassword')->name('profile.changePassword');
    Route::post('/profile/updatePassword', 'ProfileController@updatePassword')->name('profile.updatePassword');

    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
        // Route::get('/', 'AdminController@welcome');
        Route::resource('users', 'UserController', ['middleware' => ['permission:manageUsers'], 'uses' => 'UserController']);
        // Concerts
        Route::get('/concerts/create', 'ConcertController@create')->name('concert.create');
        Route::post('/concerts/store', 'ConcertController@store')->name('concert.store');
        Route::get('/concert/edit/{concert}', 'ConcertController@edit')->name('concert.edit');
        Route::post('/concert/update', 'ConcertController@update')->name('concert.update');
        Route::delete('/concert/delete/{concert}', 'ConcertController@destroy')->name('concert.delete');
        Route::get('/concert/addDate/{concert}', 'ConcertController@addDate')->name('concert.addDate');
        Route::post('/concert/addDate', 'ConcertController@saveDate')->name('concert.saveDate');
        // Rehearsals
        Route::get('/rehearsals/create', 'RehearsalController@create')->name('rehearsal.create');
        Route::post('/rehearsals/store', 'RehearsalController@store')->name('rehearsal.store');
        Route::get('/rehearsal/edit/{rehearsal}', 'RehearsalController@edit')->name('rehearsal.edit');
        Route::post('/rehearsal/update', 'RehearsalController@update')->name('rehearsal.update');
        Route::delete('/rehearsal/delete', 'RehearsalController@destroy')->name('rehearsal.delete');
        // Voices
        Route::resource('/voices', 'VoiceController');
    });
});

Auth::routes();
