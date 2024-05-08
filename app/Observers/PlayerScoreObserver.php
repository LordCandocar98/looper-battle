<?php

namespace App\Observers;

use Exception;
use App\Models\User;
use App\Models\Reward;
use App\Models\PlayerScore;
use App\Models\SpecialCode;
use App\Models\CodeAssignment;
use Illuminate\Support\Facades\Log;
use App\Notifications\RewardNotification;

class PlayerScoreObserver
{
    /**
     * Handle the PlayerScore "created" event.
     *
     * @param  \App\Models\PlayerScore  $playerScore
     * @return void
     */
    public function created(PlayerScore $playerScore)
    {
        $this->assignmentCode($playerScore);
    }

    /**
     * Handle the PlayerScore "updated" event.
     *
     * @param  \App\Models\PlayerScore  $playerScore
     * @return void
     */
    public function updated(PlayerScore $playerScore)
    {
    }

    /**
     * Handle the PlayerScore "deleted" event.
     *
     * @param  \App\Models\PlayerScore  $playerScore
     * @return void
     */
    public function deleted(PlayerScore $playerScore)
    {
        //
    }

    /**
     * Handle the PlayerScore "restored" event.
     *
     * @param  \App\Models\PlayerScore  $playerScore
     * @return void
     */
    public function restored(PlayerScore $playerScore)
    {
        //
    }

    /**
     * Handle the PlayerScore "force deleted" event.
     *
     * @param  \App\Models\PlayerScore  $playerScore
     * @return void
     */
    public function forceDeleted(PlayerScore $playerScore)
    {
        //
    }

    public function assignmentCode(PlayerScore $playerScore)
    {
        try {

            $totalPoints = PlayerScore::where('player_id', $playerScore->player_id)
                ->sum('points');

            // Obtener las recompensas que el jugador ha alcanzado con su puntaje total
            $rewards = Reward::with(['item'])->where('target_score', '<=', $totalPoints)->where('status', true)->get();
            $user = User::find($playerScore->player_id);
            foreach ($rewards as $reward) {
                $existingAssignment = CodeAssignment::where('player_id', $user->id)
                    ->where('reward_id', $reward->id)
                    ->exists();

                if (!$existingAssignment) {
                    $specialCode = SpecialCode::whereDoesntHave('assignment')->where('value', 0)->first();
                    if ($specialCode) {
                        $assignment = CodeAssignment::create([
                            'code' => $specialCode->code,
                            'player_id' => $user->id,
                            'reward_id' => $reward->id,
                        ]);
                        // Eliminar el código especial de la tabla de códigos especiales
                        $specialCode->delete();
                        $user->notify(new RewardNotification($specialCode->code, $user, $reward->item));
                    } else {
                        Log::info('No hay código disponible para la recompensa "' . $reward->title . '" asignada a: ' . $user->nickname);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
        }
    }
}
