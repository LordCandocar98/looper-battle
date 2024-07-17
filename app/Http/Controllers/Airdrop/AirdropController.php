<?php

namespace App\Http\Controllers\Airdrop;

use App\Http\Controllers\Controller;
use App\Models\Airdrop\PlayerAirdropReward;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AirdropController extends Controller
{
    public function airdropRewardsHistory(Request $request)
    {
        $type = $request->filled('type') ? $request->type : 'historical';
        $perPage = $request->filled('perPage') ? $request->perPage : 5;

        switch ($type) {
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;

            case 'historical':
                $startDate = Carbon::createFromTimestamp(0);
                $endDate = Carbon::now();
                break;
            case 'daily':
            default:
                $startDate = Carbon::today();
                $endDate = Carbon::tomorrow();
                break;
        }

        $airdropRewards = PlayerAirdropReward::with(['player' => function ($query) {
            $query->select('id', 'nickname', 'profile_icon');
        }, 'airdropReward'])->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $airdropRewards
        ], 200);
    }
}
