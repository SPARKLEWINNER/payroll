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
Route::group(['middleware' => 'auth'], function()
{


    
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/home', 'HomeController@index')->name('home');

    //users
    Route::get('users','UserController@index')->name('users');

    //groups
    Route::get('groups','GroupController@index')->name('groups');
    Route::post('new-group','GroupController@new')->name('new-group');

    //holidays
    Route::get('holidays','HolidayController@index')->name('holidays');
    Route::post('new-holiday','HolidayController@create')->name('holidays');
    Route::get('delete-holiday/{id}', 'HolidayController@delete_holiday');
    Route::post('edit-holiday/{id}', 'HolidayController@edit_holiday');


    //Stores
    Route::get('stores','StoreController@index')->name('store');

    //generate payroll
    Route::get('generate','PayrollController@index')->name('generate-payroll');
    Route::get('payrolls','PayrollController@payrolls')->name('payroll');
});


