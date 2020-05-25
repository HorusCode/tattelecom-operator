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

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login-form');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/inactive', 'StatementController@inactive')->name('inactive');
    Route::get('/active', 'StatementController@active')->name('active');
    Route::get('/ended', 'StatementController@ended')->name('ended');
    Route::get('/statement', 'StatementController@create')->name('statement');
    Route::get('/problems', 'StatementController@problem')->name('problem');
});

