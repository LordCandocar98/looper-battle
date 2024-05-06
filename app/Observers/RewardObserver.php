<?php

namespace App\Observers;

use Exception;
use App\Models\User;
use App\Models\Reward;
use App\Models\SpecialCode;
use App\Models\PlayerScore;
use App\Models\CodeAssignment;
use App\Notifications\RewardNotification;
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
        $this->assignmentCode($reward);
    }

    /**
     * Handle the Reward "updated" event.
     *
     * @param  \App\Models\Reward  $reward
     * @return void
     */
    public function updated(Reward $reward)
    {
        $this->assignmentCode($reward);
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

    public function assignmentCode(Reward $reward)
    {
        try {
            if ($reward->status == true) {
                $qualifiedPlayers = PlayerScore::selectRaw('player_id, SUM(points) as total_points')
                    ->groupBy('player_id')
                    ->havingRaw('SUM(points) >= ?', [$reward->target_score])
                    ->get();
                foreach ($qualifiedPlayers as $player) {
                    $specialCode = SpecialCode::whereDoesntHave('assignment')->where('value', 0)->first();
                    if ($specialCode) {
                        $user = User::find($player->player_id);
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
                        Log::info('Sin códigos disponibles para recompensa automatica.');
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
        }
    }
}
