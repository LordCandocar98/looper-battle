<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Airdrop\AirdropCodeController;

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



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('/admin/{slug}/export', [ExportController::class, 'export'])->name('voyager.export');

    Route::post('generate-codes', [RewardController::class, 'generateCodes'])->name('special-codes.generate');
    Route::get('/codes', [ItemController::class, 'index'])->name('codes.index');


    Route::post('/airdrop/generate-codes', [AirdropCodeController::class, 'generate'])->name('airdrop-codes.generate');
    Route::get('/airdrop/generate-codes', [AirdropCodeController::class, 'index'])->name('Airdrop-codes.index');
    Route::get('/maps', [MapController::class, 'index'])->name('map.index');
});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/verified-email', function () {
    return view('verified-email');
});

Route::get('/verify/{id}', [AuthController::class, 'verify'])->name('verification.verify');
