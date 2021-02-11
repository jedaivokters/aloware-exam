<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'blog'], function () {
    Route::get('list', 'BlogController@blogs');
    Route::post('store', 'BlogController@blogStore');
    Route::get('comments', 'BlogController@comments');
    Route::post('comment-store', 'BlogController@commentStore');
});