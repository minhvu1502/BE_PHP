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
    Route::get('user/user-info', 'UserController@getUserInfo');
    Route::get('auth/logout', 'UserController@logout');
    Route::put('user/update/{id}', 'UserController@update');

    //Hometown
    Route::post('hometown/create','HomeTownController@create');
    Route::post('hometown/filter', 'HomeTownController@filter');
    Route::get('hometown/detail/{id}', 'HomeTownController@Detail');
    Route::post('hometown/delete', 'HomeTownController@delete');
    Route::put('hometown/update/{id}', 'HomeTownController@update');

    //DishTypes
    Route::post('dishTypes/create','DishTypeController@create');
    Route::post('dishTypes/filter', 'DishTypeController@filter');
    Route::get('dishTypes/detail/{id}', 'DishTypeController@Detail');
    Route::post('dishTypes/delete', 'DishTypeController@delete');
    Route::put('dishTypes/update/{id}', 'DishTypeController@update');

    //Uses
    Route::post('uses/create','UseController@create');
    Route::post('uses/filter', 'UseController@filter');
    Route::get('uses/detail/{id}', 'UseController@Detail');
    Route::post('uses/delete', 'UseController@delete');
    Route::put('uses/update/{id}', 'UseController@update');

    //Dishes
    Route::post('dishes/create','DishController@create');
    Route::post('dishes/filter', 'DishController@filter');
    Route::get('dishes/detail/{id}', 'DishController@Detail');
    Route::post('dishes/delete', 'DishController@delete');
    Route::put('dishes/update/{id}', 'DishController@update');

    //Ingredient
    Route::post('ingredient/create','IngredientController@create');
    Route::post('ingredient/filter', 'IngredientController@filter');
    Route::get('ingredient/detail/{id}', 'IngredientController@Detail');
    Route::post('ingredient/delete', 'IngredientController@delete');
    Route::put('ingredient/update/{id}', 'IngredientController@update');

    //Provider
    Route::post('provider/create','ProviderController@create');
    Route::post('provider/filter', 'ProviderController@filter');
    Route::get('provider/detail/{id}', 'ProviderController@Detail');
    Route::post('provider/delete', 'ProviderController@delete');
    Route::put('provider/update/{id}', 'ProviderController@update');

    //Employee
    Route::post('employee/create','EmployeeController@create');
    Route::post('employee/filter', 'EmployeeController@filter');
    Route::get('employee/detail/{id}', 'EmployeeController@Detail');
    Route::post('employee/delete', 'EmployeeController@delete');
    Route::put('employee/update/{id}', 'EmployeeController@update');

    //Invoices
    Route::post('invoices/create','InvoiceController@create');
    Route::post('invoices/filter', 'InvoiceController@filter');
    Route::get('invoices/detail/{id}', 'InvoiceController@Detail');
    Route::post('invoices/delete', 'InvoiceController@delete');
    Route::put('invoices/update/{id}', 'InvoiceController@update');

    //InvoiceDetail
    Route::post('invoiceDetails/create','InvoiceDetailController@create');
    Route::post('invoiceDetails/filter', 'InvoiceDetailController@filter');
    Route::get('invoiceDetails/detail/{id}', 'InvoiceDetailController@Detail');
    Route::post('invoiceDetails/delete', 'InvoiceDetailController@delete');
    Route::put('invoiceDetails/update/{id}', 'InvoiceDetailController@update');

    //Customer
    Route::post('customer/create','CustomerController@create');
    Route::post('customer/filter', 'CustomerController@filter');
    Route::get('customer/detail/{id}', 'CustomerController@Detail');
    Route::post('customer/delete', 'CustomerController@delete');
    Route::put('customer/update/{id}', 'CustomerController@update');

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



