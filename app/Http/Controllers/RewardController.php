<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Item;
use App\Models\Reward;
use App\Models\CoinReward;
use Illuminate\Support\Str;
use App\Models\SpecialCode;
use Illuminate\Http\Request;
use App\Models\CodeAssignment;
use Illuminate\Http\JsonResponse;
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

    /**
     * Redeem the code.
     *
     * @param CodeVerificationRequest $request
     * @return JsonResponse
     */
    public function redeemCode(CodeVerificationRequest $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $code = $request->input('code');
            $itemCode = $request->input('item_id');

            // Obtener el item basado en el código
            $item = Item::where('code', $itemCode)->first();
            // Buscar la asignación del código
            $codeAssignment = CodeAssignment::where('code', $code)
                ->where('user', $user->id)
                ->where('item_id', $item->id)
                ->where('used', false)
                ->first();
            if (!$codeAssignment) {
                $specialCode = SpecialCode::where('code', $code)
                    ->where('item_id', $item->id)
                    ->where('purchase_type_id', 2)
                    ->first();

                $codeAssignment = CodeAssignment::create([
                    'code' => $code,
                    'user' => $user->id,
                    'item_id' => $item->id,
                    'purchase_type_id' => $specialCode->purchase_type_id,
                    'used' => false,
                    'paid' => true
                ]);
            }

            // Obtener la recompensa asociada al código
            $reward = Reward::where('item_id', $codeAssignment->item_id)->first();
            // Otorgar la recompensa al usuario si no fue obtenida por pago
            if (!$codeAssignment->paid) {
                $user->grantReward($reward->id);
            }
            $user->purchaseItem($reward->item_id, $codeAssignment->purchase_type_id);
            $codeAssignment->update(['used' => true]);

            return response()->json([
                'code' => 200,
                'message' => 'Código redimido exitosamente',
                'data' => NULL
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al procesar la solicitud.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function generateCodes(Request $request)
    {
        $quantity = $request->input('quantity', 1);
        $value = $request->input('value', 5);
        $type = $request->input('type', 1);
        $item = $request->input('item_id', 1);

        $codes = [];

        for ($i = 0; $i < $quantity; $i++) {
            $code = $this->generateUniqueCode($type, $item, $value);

            // SpecialCode::create([
            //     'value' => $value,
            //     'code' => $code,
            //     'purchase_type_id' => $type,
            // ]);

            $codes[] = $code;
        }

        return redirect('/admin/special-codes')->with('success', 'Códigos generados exitosamente.');
    }
    private function generateUniqueCode($type, $item, $value)
    {
        do {
            $code = $this->createCode();
        } while ($this->codeExists($code, $type, $item, $value));

        SpecialCode::create([
            'code' => $code,
            'item_id' => $item,
            'purchase_type_id' => $type,
            'value' => $value,
        ]);

        return $code;
    }

    private function createCode($length = 6)
    {
        return Str::upper(Str::random($length));
    }

    private function codeExists($code, $type, $item, $value)
    {
        return SpecialCode::where('code', $code)
            ->where('purchase_type_id', $type)
            ->where('item_id', $item)
            ->where('value', $value)
            ->exists();
    }
}
