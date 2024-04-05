<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\MoviesController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\DirectorsController;

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

Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register'])->name('register');



Route ::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'roles' => \App\Http\Controllers\API\RolesController::class,
        'users' => \App\Http\Controllers\API\UsersController::class,
        'movies' => \App\Http\Controllers\API\MoviesController::class,
        'directors' => \App\Http\Controllers\API\DirectorsController::class
    ]);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



