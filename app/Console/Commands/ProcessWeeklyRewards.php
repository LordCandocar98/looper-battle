<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use App\Models\Coin;
use App\Models\User;
use App\Models\CoinReward;
use App\Models\PlayerScore;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CoinRewardAllocation;
use App\Notifications\RewardCoinNotification;

class ProcessWeeklyRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rewards:process-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process weekly rewards for players';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Obtener todas las recompensas activas
        $activeRewards = CoinReward::where('status', true)->get();

        foreach ($activeRewards as $reward) {
            // Obtener el rango de fechas para esta recompensa
            $startDate = Carbon::parse($reward->start_date)->startOfDay();
            $endDate = Carbon::parse($reward->end_date)->endOfDay();

            // Obtener todos los jugadores que califican para esta recompensa
            $qualifiedPlayers = PlayerScore::selectRaw('player_id, SUM(points) as total_points')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('player_id')
                ->havingRaw('SUM(points) >= ?', [$reward->target_points])
                ->get();

            foreach ($qualifiedPlayers as $player) {
                try {
                    DB::beginTransaction();
                    // Verificar si el jugador ya ha recibido esta recompensa
                    $receivedReward = CoinRewardAllocation::where('player_id', $player->player_id)
                        ->where('coin_reward_id', $reward->id)
                        ->exists();

                    if (!$receivedReward) {
                        // Asignar la recompensa al jugador
                        CoinRewardAllocation::create([
                            'player_id' => $player->player_id,
                            'coin_reward_id' => $reward->id,
                        ]);

                        // Incrementar las monedas del jugador
                        $playerModel = User::find($player->player_id);
                        $coins = $playerModel->coins;
                        if (!$coins) {
                            // Si no existe una fila de coins para este jugador, creamos una nueva
                            $coins = Coin::create([
                                'player_id' => $playerModel->id,
                                'amount' => $reward->coin_amount
                            ]);
                        } else {
                            // Si ya existe una fila de coins, incrementamos el valor existente
                            $coins->increment('amount', $reward->coin_amount);
                        }
                        $playerModel->notify(new RewardCoinNotification($reward->coin_amount, $playerModel));
                    }
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error($e->getMessage() . ' - ' . $e->getLine() . ' - ' . $e->getFile());
                }
            }
            $reward->status = false;
            $reward->save();
        }

        $this->info('Weekly rewards processed successfully.');
    }
}
