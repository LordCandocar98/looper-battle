<?php

namespace App\Http\Controllers\Airdrop;

use Exception;
use App\Models\User;
use App\Models\Coin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Airdrop\AirdropCode;
use App\Http\Controllers\Controller;
use App\Models\Airdrop\AirdropReward;
use App\Models\Airdrop\AirdropGameMode;
use Illuminate\Support\Facades\Response;
use App\Models\Airdrop\PlayerAirdropReward;
use App\Models\Airdrop\AirdropGameModeScore;
use App\Notifications\RewardCoinNotification;
use App\Http\Requests\Airdrop\AirdropGameRequest;
use App\Http\Requests\Airdrop\AirdropGameScoreRequest;

class AirdropGameController extends Controller
{
    /**
     * Almacena una nueva partida.
     *
     * @param  \App\Http\Requests\Airdrop\AirdropGameRequest $request
     * @return \Illuminate\Http\ResponseJson
     */
    public function createGame(AirdropGameRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $player_id = auth()->user()->id;

            $airdropCode = AirdropCode::where('code', $data['airdrop_code_id'])->first();
            $airdropCode->update([
                'user_id' => $player_id,
                'used' => true
            ]);

            $game = AirdropGameMode::create([
                'player_id' => $player_id,
                'airdrop_code_id' => $airdropCode->id,
                'room_name' => $data['room_name'],
                'map' => $data['map'],
                'room_time_limit' => $data['room_time_limit'],
                'game_mode_goal' => $data['game_mode_goal'],
                'bots' => $data['bots'],
            ]);
            DB::commit();

            return response()->json([
                'code' => 201,
                'message' => 'Partida creada exitosamente.',
                'data' => $game,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
            return response()->json([
                'code' => 500,
                'message' => 'Error al crear la partida.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Almacena puntuación final de la partida
     *
     * @param  \App\Http\Requests\Airdrop\AirdropGameScoreRequest $request
     * @return \Illuminate\Http\ResponseJson
     */
    public function endGame(AirdropGameScoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $player_id = auth()->user()->id;


            // Crear o actualizar las estadísticas del juego
            $airdropGameScore = AirdropGameModeScore::updateOrCreate(
                ['airdrop_game_id' => $data['airdrop_game_id'], 'player_id' => $player_id],
                ['score' => $data['score'], 'kills' => $data['kills']]
            );

            $airdropRewards = AirdropReward::where('target_score', '<=', $data['score'])
                ->where('status', true)
                ->get();

            $playerModel = User::find($player_id);
            foreach ($airdropRewards AS $reward) {
                $amount = $reward->amount;
                $coin = Coin::firstOrCreate([
                    'player_id' => $player_id,
                ], [
                    'amount' => 0,
                    'airdrop' => 0,
                ]);
                $coin->increment('airdrop', $amount);
                $playerModel->grantAirdropReward($reward->id);
                $playerModel->notify(new RewardCoinNotification($reward->amount, $playerModel, 'Airdrop'));
            }
            DB::commit();

            return response()->json([
                'code' => 201,
                'message' => 'Partida creada exitosamente.',
                'data' => $airdropGameScore,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
            return response()->json([
                'code' => 500,
                'message' => 'Error al crear la partida.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
