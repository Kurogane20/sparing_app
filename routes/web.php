<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewDataController;
use App\Http\Controllers\ProfileContoller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UidController;

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
    return view('auth.login');
});

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/sse', [HomeController::class, 'sse'])->name('sse');
Route::get('/api/latestData', [HomeController::class, 'getLatestData'])->name('api.latestData');

Route::resource('users', UserController::class)->middleware(['auth', 'admin']);
Route::resource('uids', UidController::class)->middleware('auth');

Route::get('/view-data', [ViewDataController::class, 'index'])->middleware('auth')->name('view-data.index');
Route::get('/api/data', [ViewDataController::class, 'getData'])->middleware(['auth'])->name('api.data');
Route::get('/api/statdata', [ViewDataController::class, 'getStats'])->middleware(['auth'])->name('statapi.data');
Route::get('/export', [ViewDataController::class, 'export'])->middleware('auth')->name('export');

Route::get('/profile', [ProfileContoller::class, 'index'])->name('profile')->middleware('auth');


