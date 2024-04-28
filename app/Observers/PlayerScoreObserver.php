<?php

namespace App\Observers;

use App\Models\PlayerScore;
use App\Models\Reward;
use App\Models\User;
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
        $totalPoints = PlayerScore::where('player_id', $playerScore->player_id)
            ->sum('points');

        // Obtener las recompensas que el jugador ha alcanzado con su puntaje total
        $rewards = Reward::where('target_score', '<=', $totalPoints)->where('status', true)->get();
        foreach ($rewards as $reward) {
            $user = User::find($playerScore->player_id);
            $user->grantReward($reward->id);
            $user->purchaseItem($reward->item_id, 1);
        }
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
}
