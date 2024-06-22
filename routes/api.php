<?php

use App\Http\Controllers\Airdrop\AirdropGameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\RewardController;
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
    Route::get('items/list', [ItemController::class, 'list'])->name('items.list');
});

Route::group(['middleware' => ['api', 'jwt.verify', 'verified']], function () {
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('me', [AuthController::class, 'me']);
    Route::controller(UserController::class)
        ->prefix('me')
        ->group(
            function () {
                Route::post('/update', 'updateUser');
                Route::get('/show-latest-matches', 'showLatestMatchesDetail');
                Route::get('/show-top-ten-players', 'showTopTenPlayers');
            }
        );
    Route::controller(MatchController::class)
        // ->prefix('matches')
        ->group(
            function () {
                Route::get('matches', 'index');
                Route::get('matches/latest', 'showLatestMatches');
                Route::post('matches', 'store');
                Route::put('matches/{id}', 'update');
                Route::delete('matches/{id}', 'destroy');
                Route::get('matches/map-statistics', 'mapStatistics');
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
                Route::post('/scores/top-players', 'topPlayers');
            }
        );
    Route::controller(RewardController::class)
        ->prefix('rewards')
        ->group(
            function () {
                Route::get('coins', 'coinReward');
                Route::post('redeem-code', 'redeemCode');
                // Route::post('generate-codes', 'generateCodes')->name('special-codes.generate');
            }
        );
    Route::controller(AirdropGameController::class)
        ->prefix('airdrops')
        ->group(
            function () {
                Route::post('/create-game', 'createGame');
                Route::post('/end-game', 'endGame');
                // Route::post('generate-codes', 'generateCodes')->name('special-codes.generate');
            }
        );
});
