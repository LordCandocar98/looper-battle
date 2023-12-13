<?php

// app/Http/Controllers/PlayerScoreController.php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\PlayerScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function updateUser(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $user->update($request->all());

            // Respuesta exitosa
            return response()->json([
                'code' => 200,
                'message' => 'El usuario se actualizó exitosamente.',
                'data' => $user,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'code' => 404,
                'message' => 'Partida no encontrada.',
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
            return response()->json([
                'code' => 500,
                'message' => 'Error al actualizar la partida.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Obtiene las últimas partidas del usuario actual
     * con el detalle de los puntos y jugadores
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showLatestMatchesDetail()
    {
        $player_id = auth()->user()->id;
        $playedMatches = PlayerScore::where('player_id', $player_id)
            ->with(['gameMatch.owner', 'gameMatch.playerScores.player'])
            ->latest()
            ->take(20)
            ->get()
            ->pluck('gameMatch')
            ->unique('id');
        return response()->json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $playedMatches
        ], 200);
    }
    public function destroy($id)
    {
        $score = PlayerScore::find($id);

        if (!$score) {
            return response()->json([
                'code' => 404,
                'message' => 'Puntuación no encontrada'
            ], 404);
        }

        $score->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Puntuación eliminada'
        ], 200);
    }
}
