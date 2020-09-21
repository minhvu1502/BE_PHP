<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'jwt.auth'], function () {
    //User
    Route::get('user-info', 'UserController@getUserInfo');
    Route::get('auth/logout', 'UserController@logout');

    //Hometown
    Route::post('hometown/create','HomeTownController@create');
    Route::get('hometown/filter', 'HomeTownController@filter');
    Route::get('hometown/detail/{id}', 'HomeTownController@Detail');
    Route::post('hometown/delete', 'HomeTownController@delete');
    Route::put('hometown/update/{id}', 'HomeTownController@update');
});


Route::post('auth/login', 'UserController@login');
Route::post('auth/register', 'UserController@register');



