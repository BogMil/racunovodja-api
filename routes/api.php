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


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
});


Route::get('employee/{id}/availableRelations', 'App\Employee\EmployeeController@availableRelations');
Route::get('employee/active', 'App\Employee\EmployeeController@getActiveOnes');
Route::post('employee/{id}/attachDefaultRelation', 'App\Employee\EmployeeController@addDefaultRelation');
Route::delete('employee/{id}/removeDefaultRelation/{relationId}', 'App\Employee\EmployeeController@removeDefaultRelation');
Route::resource('employee', 'App\Employee\EmployeeController')
    ->except(['create']);;

Route::resource('municipality', 'App\Municipality\MunicipalityController')
    ->except(['create']);;

Route::resource('relation', 'App\Relation\RelationController')
    ->except(['create']);

Route::get('travelingExpense/{id}/details', 'App\TravelingExpense\TravelingExpenseController@details');
Route::get('travelingExpense/{id}/availableEmployees', 'App\TravelingExpense\TravelingExpenseController@availableEmployees');
Route::post('travelingExpense/{travelingExpenseId}/addEmployee/{employeeId}', 'App\TravelingExpense\TravelingExpenseController@addEmployeeToTravelingExpense');
Route::delete('travelingExpense/relation/{travelingExpenseRelationId}', 'App\TravelingExpense\TravelingExpenseController@removeRelation');
Route::delete('travelingExpense/employee/{removeEmployeeWithRelations}', 'App\TravelingExpense\TravelingExpenseController@removeEmployeeWithRelations');
Route::get('travelingExpense/travelingExpenseEmployee/{travelingExpenseEmployeeId}/availableRelations', 'App\TravelingExpense\TravelingExpenseController@availableRelations');
Route::post('travelingExpense/employeeRelation/{id}/addDays/{days}', 'App\TravelingExpense\TravelingExpenseController@addDaysToRelation');
Route::post('travelingExpense/employee/{travelingExpenseEmployeeId}/addRelationWithDays/{relationId}/{days}', 'App\TravelingExpense\TravelingExpenseController@addRelationWithDays');
Route::resource('travelingExpense', 'App\TravelingExpense\TravelingExpenseController')
    ->except(['create']);

Route::post('user/getMissingJmbgs', 'App\User\UserController@getMissingJmbgs');


Route::get('otherSettings', 'App\OtherSettings\OtherSettingsController@index');
