<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HeadquarterController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

// login
Route::post('/auth/login',[AuthController::class,'login'])
    ->name('auth.login');

// rutas protegidas
Route::group(['middleware'=>['auth:sanctum']], function(){

    // rutas de usuarios & acceso
    Route::get('/user', [UserController::class, 'index'])
        ->name('user.index')
        ->middleware('permission:user.index');

    Route::get('/user/{id}', [UserController::class, 'show'])
        ->name('user.show')
        ->middleware('permission:user.show');

    Route::put('/user/{id}', [UserController::class, 'update'])
        ->name('user.update')
        ->middleware('permission:user.update');

    Route::delete('/user/{id}', [UserController::class, 'destroy'])
        ->name('user.delete')
        ->middleware('permission:user.delete');

    Route::post('/auth/register',[AuthController::class,'register'])
        ->name('auth.register')
        ->middleware('permission:auth.register');

    Route::post('/auth/resetPassword', [AuthController::class, 'resetPassword'])
        ->name('auth.resetPassword')
        ->middleware('permission:auth.resetPassword');

    Route::post('/auth/changePassword', [AuthController::class, 'changePassword'])
        ->name('changePassword')
        ->middleware('permission:auth.changePassword');

    Route::get('/auth/logout',[AuthController::class,'logout'])
        ->name('auth.logout')
        ->middleware('permission:auth.logout');

    Route::post('/auth/roletouser',[AuthController::class,'assignRoleToUser'])
        ->name('auth.roletouser')
        ->middleware('permission:auth.roletouser');

    Route::post('/auth/rmvroletouser',[AuthController::class,'removeRoleToUser'])
        ->name('auth.rmvroletouser')
        ->middleware('permission:auth.rmvroletouser');

    // rutas de roles
    Route::get('/auth/roles', [RoleController::class, 'index'])
        ->name('roles.index')
        ->middleware('permission:roles.index');

    // rutas de equipos (devices)
    Route::get('/devices', [DeviceController::class, 'index'])
        ->name('devices.index')
        ->middleware('permission:devices.index');

    Route::post('/devices/register', [DeviceController::class, 'register'])
        ->name('devices.register')
        ->middleware('permission:devices.register');

    Route::get('/devices/{id}', [DeviceController::class, 'show'])
        ->name('devices.show')
        ->middleware('permission:devices.show');

    Route::put('/devices/{id}', [DeviceController::class, 'update'])
        ->name('devices.update')
        ->middleware('permission:devices.update');

    Route::delete('/devices/{id}', [DeviceController::class, 'destroy'])
        ->name('devices.delete')
        ->middleware('permission:devices.delete');

    // rutas de estado (status) de equipos (devices)
    Route::get('/status', [StatusController::class, 'index'])
        ->name('status.index')
        ->middleware('permission:status.index');

    Route::post('/status/register', [StatusController::class, 'register'])
        ->name('status.register')
        ->middleware('permission:status.register');

    Route::get('/status/{id}', [StatusController::class, 'show'])
        ->name('status.show')
        ->middleware('permission:status.show');

    Route::put('/status/{id}', [StatusController::class, 'update'])
        ->name('status.update')
        ->middleware('permission:status.update');

    Route::delete('/status/{id}', [StatusController::class, 'destroy'])
        ->name('status.delete')
        ->middleware('permission:status.delete');

    // rutas de tipos (Type) de equipos (device)
    Route::get('/types', [TypeController::class, 'index'])
        ->name('types.index')
        ->middleware('permission:types.index');

    Route::post('/types/register', [TypeController::class, 'register'])
        ->name('types.register')
        ->middleware('permission:types.register');

    Route::get('/types/{id}', [TypeController::class, 'show'])
        ->name('types.show')
        ->middleware('permission:types.show');

    Route::put('/types/{id}', [TypeController::class, 'update'])
        ->name('types.update')
        ->middleware('permission:types.update');

    Route::delete('/types/{id}', [TypeController::class, 'destroy'])
        ->name('types.delete')
        ->middleware('permission:types.delete');

    //ruta de headquarters (sedes)
    Route::get('/headquarters', [HeadquarterController::class, 'index'])
        ->name('headquarters.index')
        ->middleware('permission:headquarters.index');

    Route::post('/headquarters/register', [HeadquarterController::class, 'register'])
        ->name('headquarters.register')
        ->middleware('permission:headquarters.register');

    Route::get('/headquarters/{id}', [HeadquarterController::class, 'show'])
        ->name('headquarters.show')
        ->middleware('permission:headquarters.show');

    Route::put('/headquarters/{id}', [HeadquarterController::class, 'update'])
        ->name('headquarters.update')
        ->middleware('permission:headquarters.update');

    Route::delete('/headquarters/{id}', [HeadquarterController::class, 'destroy'])
        ->name('headquarters.delete')
        ->middleware('permission:headquarters.delete');

    //rutas de locaciones (locations)
    Route::get('/locations', [LocationController::class, 'index'])
        ->name('locations.index')
        ->middleware('permission:locations.index');

    Route::post('/locations/register', [LocationController::class, 'register'])
        ->name('locations.register')
        ->middleware('permission:locations.register');

    Route::get('/locations/{id}', [LocationController::class, 'show'])
        ->name('locations.show')
        ->middleware('permission:locations.show');

    Route::put('/locations/{id}', [LocationController::class, 'update'])
        ->name('locations.update')
        ->middleware('permission:locations.update');

    Route::delete('/locations/{id}', [LocationController::class, 'destroy'])
        ->name('locations.delete')
        ->middleware('permission:locations.delete');

    Route::get('/locHead/{id}', [LocationController::class, 'locHead'])
        ->name('locations.locHead')
        ->middleware('permission:locations.locHead');

    // respuestas para rutas no definidas en la API
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
