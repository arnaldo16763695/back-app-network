<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HeadquarterController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Headquarter;
use App\Models\Location;
use Illuminate\Auth\Middleware\Authorize;
use GuzzleHttp\Psr7\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->auth();
// });

Route::get('/prueba', [PruebaController::class, 'index']);
// public routes
Route::post('/auth/login',[AuthController::class,'login'])
    ->name('auth.login');

Route::get('/user', [UserController::class, 'index'])
    ->name('user.index');

Route::get('/user/{id}', [UserController::class, 'show'])
    ->name('user.show');

Route::get('/roles', [RoleController::class, 'index'])
    ->name('roles.index');

//ruta de headquarters (sedes)
Route::get('/headquarters', [HeadquarterController::class, 'index'])
    ->name('headquarters.index');

Route::get('/headquarters/{id}', [HeadquarterController::class, 'show'])
    ->name('headquarters.show');

Route::post('/headquarters/register', [HeadquarterController::class, 'register'])
    ->name('headquarters.register');

Route::put('/headquarters/{id}', [HeadquarterController::class, 'update'])
    ->name('headquarters.update');

Route::delete('/headquarters/{id}', [HeadquarterController::class, 'destroy'])
    ->name('headquarters.delete');

//rutas de locations
Route::get('/locations', [LocationController::class, 'index'])
    ->name('locations.index');

Route::get('/locations/{id}', [LocationController::class, 'show'])
    ->name('locations.show');

Route::post('/locations/register', [LocationController::class, 'register'])
    ->name('locations.register');

Route::put('/locations/{id}', [LocationController::class, 'update'])
    ->name('locations.update');

Route::delete('/locations/{id}', [LocationController::class, 'destroy'])
    ->name('locations.delete');

//ruta de devices
Route::get('/devices', [DeviceController::class, 'index'])
    ->name('devices.index');

Route::get('/devices/{id}', [DeviceController::class, 'show'])
    ->name('devices.show');

Route::post('/devices/register', [DeviceController::class, 'register'])
    ->name('devices.register');

Route::put('/devices/{id}', [DeviceController::class, 'update'])
    ->name('devices.update');

Route::delete('/devices/{id}', [DeviceController::class, 'destroy'])
    ->name('devices.delete');

//ruta de status
Route::get('/statuses', [StatusController::class, 'index'])
    ->name('statuses.index');

Route::get('/statuses/{id}', [StatusController::class, 'show'])
    ->name('statuses.show');

Route::post('/statuses/register', [StatusController::class, 'register'])
    ->name('statuses.register');

Route::put('/statuses/{id}', [StatusController::class, 'update'])
    ->name('statuses.update');

Route::delete('/statuses/{id}', [StatusController::class, 'destroy'])
    ->name('statuses.delete');

//ruta de tipos de equipos (Type)
Route::get('/types', [TypeController::class, 'index'])
    ->name('types.index');

Route::get('/types/{id}', [TypeController::class, 'show'])
    ->name('types.show');

Route::post('/types/register', [TypeController::class, 'register'])
    ->name('types.register');

Route::put('/types/{id}', [TypeController::class, 'update'])
    ->name('types.update');

Route::delete('/types/{id}', [TypeController::class, 'destroy'])
    ->name('types.delete');

// protected routes
Route::group(['middleware'=>['auth:sanctum']], function(){

    Route::post('/resetPassword', [AuthController::class, 'resetPassword'])
        ->name('resetPassword')
        ->middleware('permission:auth.resetPassword');

    Route::post('/changePassword', [AuthController::class, 'changePassword'])
        ->name('changePassword');

    Route::post('/auth/register',[AuthController::class,'register'])
        ->name('auth.register')
        ->middleware('permission:auth.register');

    Route::post('/auth/roletouser',[AuthController::class,'assignRoleToUser'])
        ->name('auth.roletouser')
        ->middleware('permission:auth.roletouser');

    Route::post('/auth/rmvroletouser',[AuthController::class,'removeRoleToUser'])
        ->name('auth.rmvroletouser')
        ->middleware('permission:auth.rmvroletouser');

    Route::get('/auth/logout',[AuthController::class,'logout'])
        ->name('auth.logout')
        ->middleware('permission:auth.logout');

    Route::put('/user/{id}', [UserController::class, 'update'])
        ->name('user.update')
        ->middleware('permission:user.update');

    Route::delete('/user/{id}', [UserController::class, 'destroy'])
        ->name('user.delete')
        ->middleware('permission:user.delete');

    Route::fallback(function(){
        return response()->json(
            [
                'message' => 'La Página solicitada no existe o no ha sido encontrada.',
                'status' => 404
            ],
            404
        );
    });

});
