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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    Route::post('attendance', 'AttendanceController@create')->name('create-attendance');
    return $request->user();
});

Route::post('attendance', 'AttendanceController@create')->name('create-attendance');
Route::get('get-history', 'AttendanceController@get')->name('get-attendance');
Route::post('save', 'PayrollController@save')->name('save-payroll');
Route::post('schedule', 'ScheduleController@create')->name('create-schedule');
Route::post('rates', 'PayrollController@getRatesStore')->name('rates');
Route::post('additional/{id}', 'PayrollController@additionalIncome')->name('additional-income');
Route::post('additional-remarks/{id}', 'PayrollController@additionalRemarks')->name('additional-remarks');
Route::post('deduction/{id}', 'PayrollController@additionalDeduction')->name('additional-deduction');
Route::post('deduction-remarks/{id}', 'PayrollController@deductionRemarks')->name('deduction-remarks');
Route::get('getPayrollInfo/{id}', 'PayrollController@getPayrollInfo')->name('get-payrollinfo');
Route::get('editcompany', 'PayrollController@updateCompanyAPI')->name('edit-store');

