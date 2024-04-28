<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CoinReward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    /**
     * Obtiene todas las partidas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function coinReward()
    {
        $today = Carbon::now()->toDateString();
        $rewards = CoinReward::where('status', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();
        return response()->json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $rewards
        ], 200);
    }
}
