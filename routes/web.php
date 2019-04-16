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
    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
        Route::get('/project/export-participants/{project}', 'ProjectController@exportParticipants')->name('project.exportParticipants');
    });
});

Route::get('/{vue_capture?}', function () {
    return view('index');
})->where('vue_capture', '[\/\w\.-]*');