<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Reward;
use App\Models\CoinReward;
use Illuminate\Http\Request;
use App\Models\CodeAssignment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CodeVerificationRequest;

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

    public function redeemCode(CodeVerificationRequest $request)
    {

        $user = User::find(auth()->user()->id);
        $code = $request->code;

        // Buscar la asignación del código
        $codeAssignment = CodeAssignment::where('code', $code)
            ->where('status', true)
            ->first();

        if (!$codeAssignment) {
            return response()->json(['message' => 'El código no es válido o ya ha sido utilizado.'], 404);
        }

        // Obtener la recompensa asociada al código
        $reward = Reward::find($codeAssignment->reward_id);

        if (!$reward) {
            return response()->json(['message' => 'No se encontró la recompensa asociada al código.'], 404);
        }

        // Otorgar la recompensa al usuario
        $user->grantReward($reward->id);
        $user->purchaseItem($reward->item_id, 1);
        $codeAssignment->update(['status' => false]);

        return response()->json([
            'code' => 200,
            'message' => 'Item obtenido exitosamente.',
            'data' => NULL
        ], 200);
    }
}
