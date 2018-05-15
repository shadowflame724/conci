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
Route::middleware(['first', 'second'])->group(function () {
    Route::get('/user', 'Api\UserController@find');
    Route::get('/notes/list', 'Api\NoteController@findAll');
    Route::post('/notes/create', 'Api\NoteController@create');
    Route::post('/notes/update/{id}', 'Api\NoteController@update');
});
