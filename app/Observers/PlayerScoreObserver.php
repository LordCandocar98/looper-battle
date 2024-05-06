<?php

namespace App\Observers;

use Exception;
use App\Models\User;
use App\Models\Reward;
use App\Models\PlayerScore;
use App\Models\SpecialCode;
use App\Models\CodeAssignment;
use App\Notifications\RewardNotification;
use Illuminate\Support\Facades\Log;

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
            $rewards = Reward::where('target_score', '<=', $totalPoints)->where('status', true)->get();
            foreach ($rewards as $reward) {
                $specialCode = SpecialCode::whereDoesntHave('assignment')->where('value', 0)->first();
                if ($specialCode) {
                    $user = User::find($playerScore->player_id);
                    $assignment = CodeAssignment::create([
                        'code' => $specialCode->code,
                        'player_id' => $user->id,
                        'reward_id' => $reward->id,
                    ]);

                    // Eliminar el código especial de la tabla de códigos especiales
                    $specialCode->delete();
                    $user->notify(new RewardNotification($specialCode->code, $user));

                    // Antigua lógica para asignar a un usuario recompensas

                    // $user->grantReward($reward->id);
                    // $user->purchaseItem($reward->item_id, 1);
                } else {
                    Log::info('Códigos no disponibles para asignar al jugador');
                }
                // $user->grantReward($reward->id);
                // $user->purchaseItem($reward->item_id, 1);
            }
        } catch (Exception $e) {
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
        }
    }
}
