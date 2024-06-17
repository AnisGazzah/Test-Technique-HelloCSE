<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login ', 'login')->name('auth.login');
    });
});

Route::controller(ProfileController::class)->group(function () {
    Route::group(['prefix' => 'profiles'], function () {
        Route::get('/', 'index')->name('profiles.index');
    });
    Route::group(['prefix' => 'profiles', 'middleware' => 'auth:sanctum'], function () {
        Route::post('/', 'store')->name('profiles.store');
        Route::put('/{profile}	', 'update')->name('profiles.update');
        Route::delete('/{profile}', 'destroy')->name('profiles.destroy');
    });
});
