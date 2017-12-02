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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/concerts', 'ConcertController@index')->name('concerts');
Route::get('/concert/{concert}', 'ConcertController@show')->name('concert.show');

Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
    Route::get('/', 'AdminController@welcome');
    Route::resource('users', 'UserController', ['middleware' => ['permission:manage-admins'], 'uses' => 'UserController']);
    Route::get('/concerts/create', 'ConcertController@create')->name('concert.create');
    Route::post('/concerts/store', 'ConcertController@store')->name('concert.store');
});
