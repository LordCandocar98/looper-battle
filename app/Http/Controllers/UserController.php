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
            'gameMatch' => function ($query) use ($player_id) {
                $query->with([
                    'playerScores' => function ($query) use ($player_id) {
                        $query->with('player', 'team');
                    }
                ]);
            }
        ])
        ->latest()
        ->take(20)
        ->get();

    // Formatear el resultado
    $formattedMatches = $playedMatches->map(function ($playerScore) {
        $gameMatch = $playerScore->gameMatch;

        // Agrupar puntuaciones por equipo
        $teamScores = $gameMatch->playerScores->groupBy('team_id');

        // Calcular totales por equipo
        $teamTotals = $teamScores->map(function ($teamScores) {
            $team = $teamScores->first()->team;
            $totalPoints = $teamScores->sum('points');
            $totalKills = $teamScores->sum('kills');
            $totalDeaths = $teamScores->sum('deaths');

            return [
                'team_id' => $team->id,
                'team_name' => $team->name,
                'team_color' => $team->color,
                'total_points' => $totalPoints,
                'total_kills' => $totalKills,
                'total_deaths' => $totalDeaths,
                'players' => $teamScores->map(function ($score) {
                    $player = $score->player;
                    return [
                        'player_id' => $player->id,
                        'name' => $player->name,
                        'nickname' => $player->nickname,
                        'profile_icon' => $player->profile_icon,
                        'points' => $score->points,
                        'kills' => $score->kills,
                        'deaths' => $score->deaths,
                    ];
                }),
            ];
        })->values();

        return [
            'game_match' => [
                'id' => $gameMatch->id,
                'owner_id' => $gameMatch->owner_id,
                'room_name' => $gameMatch->room_name,
                'privacy' => $gameMatch->privacy,
                'map' => $gameMatch->map,
                'game_mode' => $gameMatch->game_mode,
                'max_players' => $gameMatch->max_players,
                'room_time_limit' => $gameMatch->room_time_limit,
                'game_mode_goal' => $gameMatch->game_mode_goal,
                'bots' => $gameMatch->bots,
                'pay_tournament' => $gameMatch->pay_tournament,
                'payment_code' => $gameMatch->payment_code,
                'created_at' => $gameMatch->created_at,
                'updated_at' => $gameMatch->updated_at,
                'team_totals' => $teamTotals,
            ],
        ];
    });
        return response()->json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $formattedMatches
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
