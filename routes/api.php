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

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'auth',
    ],
    function ($router) {
        Route::post(
            'registracija',
            'App\Http\Controllers\AuthController@register'
        );
        Route::post('prijava', 'App\Http\Controllers\AuthController@login');
        Route::post('logout', 'App\Http\Controllers\AuthController@logout');
        Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
        Route::post('me', 'App\Http\Controllers\AuthController@me');
    }
);

Route::get('opstina', 'App\Http\Controllers\OpstinaController@index');

// ZAPOSLENI
Route::post(
    'zaposleni/izdvojNedostajuceJmbgove',
    'App\Http\Controllers\ZaposleniController@izdvojNedostajuceJmbgove'
);
Route::post(
    'zaposleni/izdvojNedostajuceSifre',
    'App\Http\Controllers\ZaposleniController@izdvojNedostajuceSifre'
);
Route::get('zaposleni', 'App\Http\Controllers\ZaposleniController@index');
Route::post('zaposleni', 'App\Http\Controllers\ZaposleniController@store');
Route::put('zaposleni/{id}', 'App\Http\Controllers\ZaposleniController@update');
Route::delete(
    'zaposleni/{id}',
    'App\Http\Controllers\ZaposleniController@destroy'
);
Route::post(
    'zaposleni/{jmbg}/azurirajEmail',
    'App\Http\Controllers\ZaposleniController@azurirajEmail'
);
// KORISNIK

Route::get(
    'korisnik/detalji',
    'App\Http\Controllers\KorisnikController@detalji'
);
Route::put(
    'korisnik/detalji',
    'App\Http\Controllers\KorisnikController@azurirajDetalje'
);

//SLANJE MAILA LOG

Route::post(
    'slanjeMailova/log',
    'App\Http\Controllers\SlanjeMailaController@log'
);

Route::get(
    'slanjeMailova/log/{slanjeMailaLog}',
    'App\Http\Controllers\SlanjeMailaController@getLog'
);

Route::get(
    'slanjeMailova/logs',
    'App\Http\Controllers\SlanjeMailaController@getLogs'
);

//NOTIFIKACIJA
Route::get(
    'notifikacija/brojNovih',
    'App\Http\Controllers\KorisnikController@brojNovihNotifikacija'
);

Route::get(
    'notifikacija',
    'App\Http\Controllers\KorisnikController@notifikacije'
);
//////////////////////////////////////////////////////////////
Route::get(
    'employee/{id}/availableRelations',
    'App\Employee\EmployeeController@availableRelations'
);
Route::get('employee/active', 'App\Employee\EmployeeController@getActiveOnes');
Route::post(
    'employee/{id}/attachDefaultRelation',
    'App\Employee\EmployeeController@addDefaultRelation'
);
Route::delete(
    'employee/{id}/removeDefaultRelation/{relationId}',
    'App\Employee\EmployeeController@removeDefaultRelation'
);
Route::post(
    'employee/{jmbg}/updateEmail',
    'App\Employee\EmployeeController@updateEmail'
);
Route::resource('employee', 'App\Employee\EmployeeController')->except([
    'create',
]);

Route::resource(
    'municipality',
    'App\Municipality\MunicipalityController'
)->except(['create']);

Route::resource('relation', 'App\Relation\RelationController')->except([
    'create',
]);

Route::get(
    'travelingExpense/{id}/details',
    'App\TravelingExpense\TravelingExpenseController@details'
);
Route::get(
    'travelingExpense/{id}/availableEmployees',
    'App\TravelingExpense\TravelingExpenseController@availableEmployees'
);
Route::post(
    'travelingExpense/{travelingExpenseId}/addEmployee/{employeeId}',
    'App\TravelingExpense\TravelingExpenseController@addEmployeeToTravelingExpense'
);
Route::delete(
    'travelingExpense/relation/{travelingExpenseRelationId}',
    'App\TravelingExpense\TravelingExpenseController@removeRelation'
);
Route::delete(
    'travelingExpense/employee/{removeEmployeeWithRelations}',
    'App\TravelingExpense\TravelingExpenseController@removeEmployeeWithRelations'
);
Route::get(
    'travelingExpense/travelingExpenseEmployee/{travelingExpenseEmployeeId}/availableRelations',
    'App\TravelingExpense\TravelingExpenseController@availableRelations'
);
Route::post(
    'travelingExpense/employeeRelation/{id}/addDays/{days}',
    'App\TravelingExpense\TravelingExpenseController@addDaysToRelation'
);
Route::post(
    'travelingExpense/employee/{travelingExpenseEmployeeId}/addRelationWithDays/{relationId}/{days}',
    'App\TravelingExpense\TravelingExpenseController@addRelationWithDays'
);

Route::post(
    'travelingExpense/{id}/lock',
    'App\TravelingExpense\TravelingExpenseController@lock'
);
Route::resource(
    'travelingExpense',
    'App\TravelingExpense\TravelingExpenseController'
)->except(['create']);

Route::resource('lokacija', 'App\Lokacija\LokacijaController')->except([
    'create',
]);

Route::get(
    'dobavljac/{id}/details',
    'App\Dobavljac\DobavljacController@details'
);
Route::resource('dobavljac', 'App\Dobavljac\DobavljacController')->except([
    'create',
]);

Route::post('user/getMissingJmbgs', 'App\User\UserController@getMissingJmbgs');
Route::post(
    'user/getMissingEmployeeNumbers',
    'App\User\UserController@getMissingEmployeeNumbers'
);

Route::get('userDetails', 'App\UserDetails\UserDetailsController@index');
Route::put('userDetails', 'App\UserDetails\UserDetailsController@update');
