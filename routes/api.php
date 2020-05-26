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
Route::middleware('auth:api')->group(function() {
    Route::get('/users/search/service', 'UserController@searchServiceUser');
    Route::get('/users/search/client', 'UserController@searchClient');
    Route::get('/problems/search', 'Api\ProblemController@searchProblem');
    Route::post('/works', 'WorkController@store');
    Route::post('/works/start', 'WorkController@start');
    Route::post('/works/stop', 'WorkController@stop');
    Route::apiResource('/statements', 'Api\StatementController');
    Route::apiResource('/problems', 'Api\ProblemController');
});





