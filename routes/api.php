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
    Route::post('hometown/filter', 'HomeTownController@filter');
    Route::get('hometown/detail/{id}', 'HomeTownController@Detail');
    Route::post('hometown/delete', 'HomeTownController@delete');
    Route::put('hometown/update/{id}', 'HomeTownController@update');

    //Employee
    Route::post('employee/create','EmployeeController@create');
    Route::post('employee/filter', 'EmployeeController@filter');
    Route::get('employee/detail/{id}', 'EmployeeController@Detail');
    Route::post('employee/delete', 'EmployeeController@delete');
    Route::put('employee/update/{id}', 'EmployeeController@update');

    //TableTypes
    Route::post('tableType/create','TableTypeController@create');
    Route::post('tableType/filter', 'TableTypeController@filter');
    Route::get('tableType/detail/{id}', 'TableTypeController@Detail');
    Route::post('tableType/delete', 'TableTypeController@delete');
    Route::put('tableType/update/{id}', 'TableTypeController@update');

    //Table
    Route::post('table/create','TableController@create');
    Route::post('table/filter', 'TableController@filter');
    Route::get('table/detail/{id}', 'TableController@Detail');
    Route::post('table/delete', 'TableController@delete');
    Route::put('table/update/{id}', 'TableController@update');
});


Route::post('auth/login', 'UserController@login');
Route::post('auth/register', 'UserController@register');



