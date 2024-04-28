<?php

namespace App\Observers;

use App\Models\PlayerScore;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RewardObserver
{
    /**
     * Handle the Reward "created" event.
     *
     * @param  \App\Models\Reward  $reward
     * @return void
     */
    public function created(Reward $reward)
    {
        if ($reward->status == true) {
            $qualifiedPlayers = PlayerScore::selectRaw('player_id, SUM(points) as total_points')
                ->groupBy('player_id')
                ->havingRaw('SUM(points) >= ?', [$reward->target_score])
                ->get();
            foreach ($qualifiedPlayers as $player) {
                $user = User::find($player->player_id);
                $user->grantReward($reward->id);
                $user->purchaseItem($reward->item_id, 1);
            }
        }
    }

    /**
     * Handle the Reward "updated" event.
     *
     * @param  \App\Models\Reward  $reward
     * @return void
     */
    public function updated(Reward $reward)
    {
        if ($reward->status == true) {
            $qualifiedPlayers = PlayerScore::selectRaw('player_id, SUM(points) as total_points')
                ->groupBy('player_id')
                ->havingRaw('SUM(points) >= ?', [$reward->target_score])
                ->get();
            foreach ($qualifiedPlayers as $player) {
                $user = User::find($player->player_id);
                $user->grantReward($reward->id);
                $user->purchaseItem($reward->item_id, 1);
            }
        }
    }

    /**
     * Handle the Reward "deleted" event.
     *
     * @param  \App\Models\Reward  $reward
     * @return void
     */
    public function deleted(Reward $reward)
    {
        //
    }

    /**
     * Handle the Reward "restored" event.
     *
     * @param  \App\Models\Reward  $reward
     * @return void
     */
    public function restored(Reward $reward)
    {
        //
    }

    /**
     * Handle the Reward "force deleted" event.
     *
     * @param  \App\Models\Reward  $reward
     * @return void
     */
    public function forceDeleted(Reward $reward)
    {
        //
    }
}
