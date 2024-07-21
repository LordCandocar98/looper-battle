<?php

namespace App\Observers;

use Exception;
use App\Models\User;
use App\Models\Reward;
use App\Models\SpecialCode;
use App\Models\PlayerScore;
use App\Models\CodeAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\RewardNotification;

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
                    $user = User::find($player->player_id);
                    $existingAssignment = CodeAssignment::where('player_id', $user->id)
                        ->where('item_id', $reward->item_id)
                        ->exists();
                    if (!$existingAssignment) {
                        $specialCode = SpecialCode::whereDoesntHave('assignment', function ($query) {
                            $query->where('used', false);
                        })->where('item_id', $reward->item_id)
                            ->where('purchase_type_id', 1)->first();
                        if ($specialCode) {
                            $assignment = CodeAssignment::create([
                                'code' => $specialCode->code,
                                'player_id' => $user->id,
                                'item_id' => $reward->item_id,
                                'purchase_type_id' => $specialCode->purchase_type_id,
                            ]);
                            // Eliminar el código especial de la tabla de códigos especiales
                            $specialCode->delete();
                            $user->notify(new RewardNotification($specialCode->code, $user, $reward->item));
                        } else {
                            Log::info('Sin código disponible para la recompensa "' . $reward->title . '" para el Jugador: ' . $user->nickname);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
        }
    }
}
