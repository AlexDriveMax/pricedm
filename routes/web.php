<?php

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


use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;

Route::get('/', [LoginController::class, 'page'])->name('loginPage');
Route::post('/sendform', [LoginController::class, 'sendForm']);

Route::get('/dashboard', [DashboardController::class, 'main'])->name('dashboard');

Route::get('/dashboard/history/{date}', [DashboardController::class, 'main']);

Route::get('/dashboard/history', [HistoryController::class, 'main'])->name('history');

Route::post('/ajaxCars', [DashboardController::class, 'ajaxCars'])->name('ajaxCars');

Route::post('/ajaxCharts', [DashboardController::class, 'ajaxCharts'])->name('ajaxCharts');

Route::post('/dashboard/clearPrices', [DashboardController::class, 'clearPrices']);

Route::post('/dashboard/savePriceAjax', [DashboardController::class, 'savePrice']);

Route::post('/dashboard/saveNotesAjax', [DashboardController::class, 'saveNotes']);

Route::post('/dashboard/priceHistoryAjax', [DashboardController::class, 'priceHistoryAjax']);

Route::post('/dashboard/pricesLeadsAjax', [DashboardController::class, 'pricesLeadsAjax']);

Route::get('/testtt', [DashboardController::class, 'test']);

Route::get('/dashboard/exit', [DashboardController::class, 'exit'])->name('dashboard.exit');




//может быть последняя, может быть из истории (с датой) .Не путать с логикой алиаса истории. там сложнее.
Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('export');
Route::get('/dashboard/export/{date}', [DashboardController::class, 'export'])->name('export');

Route::get('/dashboard/cache/', [DashboardController::class, 'cacheCars']);
Route::get('/dashboard/cache/{date}', [DashboardController::class, 'cacheCars']);







//Route::get('/', 'App\Http\Controllers\LoginController@page');
