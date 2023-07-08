<?php

use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Guests\PageController as GuestsPageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [GuestsPageController::class, 'home'])->name('guests.home');

Route::middleware(['auth', 'verified'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [AdminPageController::class, 'dashboard'])->name('dashboard');
        Route::resource('posts', PostController::class);
    });

Route::middleware('auth')
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

require __DIR__.'/auth.php';
