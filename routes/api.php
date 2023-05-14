<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

// public routes
Route::post('/auth/login',[AuthController::class,'login'])
    ->name('auth.login');

Route::get('/user', [UserController::class, 'index'])
    ->name('user.index');

Route::get('/user/{id}', [UserController::class, 'show'])
    ->name('user.show');

// protected routes
Route::group(['middleware'=>['auth:sanctum']], function(){

    Route::group(['middleware'=>['can:publish articles']], function(){

    });

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
        ->name('auth.update')
        ->middleware('permission:auth.update');

    Route::delete('/user/{id}', [UserController::class, 'destroy'])
        ->name('user.delete')
        ->middleware('permission:user.delete');

});
