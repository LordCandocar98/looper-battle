<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PlayerScoreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['guest']], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['api', 'jwt.verify', 'verified']], function () {
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('me/update', [UserController::class, 'updateUser']);
    Route::controller(MatchController::class)
        // ->prefix('matches')
        ->group(
            function () {
                Route::get('matches', 'index');
                Route::get('matches/latest', 'showLatestMatches');
                Route::post('matches', 'store');
                Route::put('matches/{id}', 'update');
                Route::delete('matches/{id}', 'destroy');
            }
        );
    Route::controller(PlayerScoreController::class)
        // ->prefix('scores')
        ->group(
            function () {
                Route::get('scores', 'index');
                Route::get('scores/{id}', 'show');
                Route::post('scores', 'store');
                Route::put('scores/{id}', 'update');
                Route::delete('scores/{id}', 'destroy');
            }
        );
});
