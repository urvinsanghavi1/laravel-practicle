<?php

use App\Http\Controllers\CompanyListController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

// tenant routes
Route::group(['domain' => '{subdomain}.' . config('app.base_domain'), 'middleware' => ['tenant']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/login', [HomeController::class, 'login'])->name('login');
    Route::middleware(['identify'])->group(function () {
        Route::get('/home', [HomeController::class, 'home']);
        Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
        Route::get('/profile', [HomeController::class, 'profileEdit']);
        Route::post('/changeLocation/{name}', [HomeController::class, 'changeLocation'])->name('changeLocation');
        Route::post('/edit-profile', [HomeController::class, 'editProfile'])->name('editProfile');
    });
});

Route::group(['domain' => config('app.base_domain')], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/login', [HomeController::class, 'login'])->name('login');
    Route::get('/register', [HomeController::class, 'register'])->name('register');
    Route::post('/changeLocation/{name}', [HomeController::class, 'changeLocation'])->name('changeLocation');
    Route::post('/register-company', [HomeController::class, 'registerCompany'])->name('register-company');
    Route::middleware(['auth'])->group(function () {
        Route::get('/home', [HomeController::class, 'home']);
        Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
    });
});
