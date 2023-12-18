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
            ->with([
                'gameMatch',
                'gameMatch.playerScores.player' => function ($query) {
                    $query->select('id', 'nickname', 'profile_icon');
                }
            ])
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

    /**
     * Obtiene el top 10 de jugadores con mayor puntaje
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTopTenPlayers()
    {
        $topPlayers = PlayerScore::select('player_id', DB::raw('SUM(points) as total_score'))
            ->groupBy('player_id')
            ->orderByDesc('total_score')
            ->take(10)
            ->get();

        $topPlayers = $topPlayers->map(function ($item) {
            $item->player = User::select('id', 'name', 'nickname', 'profile_icon')->find($item->player_id);
            return $item;
        });
        return response()->json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $topPlayers
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
