<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/setting', [SettingController::class, 'edit'])->name('setting.edit');
    Route::patch('/setting', [SettingController::class, 'update'])->name('setting.update');

    Route::get('/reservation/{id}', [ReservationController::class, 'get'])->name('reservation.get');
    Route::patch('/reservation', [ReservationController::class, 'update'])->name('reservation.update');
    Route::delete('/reservation', [ReservationController::class, 'delete'])->name('reservation.delete');
});

require __DIR__ . '/auth.php';
