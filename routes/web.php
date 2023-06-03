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

// Route::get('attendance','AttendanceController@create')->name('create-attendance');

Auth::routes();
Route::post('rates','PayrollController@getRatesStore')->name('store-rates');
Route::group(['middleware' => 'auth'], function()
{

    Route::get('sample','AttendanceController@sample');
    Route::get('/get-history','AttendanceController@get')->name('scum');
    
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/home', 'HomeController@index')->name('home');

    //users
    Route::get('/users','UserController@index')->name('users');
    Route::post('change-pass','UserController@changepass');
    Route::get('/record/{id}','UserController@getrecord')->name('record');

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
    Route::get('store-remove','StoreController@remove')->name('store-remove');

    //generate payroll
    Route::get('generate','PayrollController@index')->name('generate-payroll');
    Route::post('generate','PayrollController@save')->name('save-payroll');
    Route::get('payrolls','PayrollController@payrolls')->name('payrolls');
    Route::get('payroll/{id}','PayrollController@payroll')->name('payroll');
    Route::get('billing/{id}','PayrollController@billing')->name('billing');
    Route::get('test','PayrollController@test')->name('test');
    Route::get('edit-payroll/{id}','PayrollController@editPayroll');

    //Salaries
    Route::get('salaries','SalaryController@index')->name('salary');
    Route::post('new-salary','SalaryController@create')->name('new-salary');

    //Rates
    Route::get('rates/{id}','PayrollController@getRates')->name('rates');
    Route::post('edit-rates','PayrollController@setRates')->name('edit-rates');
    Route::post('edit-store-rates','PayrollController@setStoreRates')->name('edit-store-rates');
});


