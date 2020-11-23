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
    Route::get('user/get-all-user', 'UserController@getAllUser');
    Route::get('user/change-role/{id}', 'UserController@changeRole');

    //Hometown
    Route::post('hometown/create','HomeTownController@create');
    Route::post('hometown/filter', 'HomeTownController@filter');
    Route::get('hometown/detail/{id}', 'HomeTownController@Detail');
    Route::post('hometown/delete', 'HomeTownController@delete');
    Route::put('hometown/update/{id}', 'HomeTownController@update');

    //Order
    Route::post('order/create','OrderController@create');
    Route::post('order/filter', 'OrderController@filter');
    Route::get('order/detail/{id}', 'OrderController@Detail');
    Route::post('order/delete', 'OrderController@delete');
    Route::put('order/update/{id}', 'OrderController@update');

    //OrderDetail
    Route::post('orderDetail/create','OrderDetailController@create');
    Route::post('orderDetail/filter', 'OrderDetailController@filter');
    Route::get('orderDetail/detail/{id}', 'OrderDetailController@Detail');
    Route::post('orderDetail/delete', 'OrderDetailController@delete');
    Route::put('orderDetail/update/{id}', 'OrderDetailController@update');

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

    //IngredientDishes
    Route::post('ingredient-dish/create','IngredientDishController@create');
    Route::post('ingredient-dish/filter', 'IngredientDishController@filter');
    Route::get('ingredient-dish/detail/{id}', 'IngredientDishController@Detail');
    Route::post('ingredient-dish/delete', 'IngredientDishController@delete');
    Route::put('ingredient-dish/update/{id}', 'IngredientDishController@update');

    Route::post('checkout/filter', 'CheckoutController@filter');
});


Route::post('auth/login', 'UserController@login');
Route::post('auth/register', 'UserController@register');

//User
Route::post('dish-user/filter', 'DishController@filter');

//Checkout
Route::post('checkout/create','CheckoutController@create');
Route::post('checkout/filter', 'CheckoutController@filter');
Route::get('checkout/detail/{id}', 'CheckoutController@Detail');
Route::post('checkout/delete', 'CheckoutController@delete');
Route::put('checkout/update/{id}', 'CheckoutController@update');



