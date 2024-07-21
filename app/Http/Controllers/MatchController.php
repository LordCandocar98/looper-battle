<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\GameMatch;
use Illuminate\Support\Str;
use App\Models\PlayerScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Matches\StoreMatchRequest;
use App\Http\Requests\Matches\UpdateMatchRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MatchController extends Controller
{

    /**
     * Obtiene todas las partidas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $matches = GameMatch::with(['owner'])->get();
        return Response::json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $matches
        ], 200);
    }

    /**
     * Obtiene las últimas partidas del usuario actual
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showLatestMatches()
    {
        $owner_id = auth()->user()->id;
        $matches = GameMatch::where('owner_id', $owner_id)
            ->latest()
            ->take(20)
            ->get();
        return Response::json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $matches
        ], 200);
    }

    /**
     * Almacena una nueva partida.
     *
     * @param  \App\Http\Requests\Matches\StoreMatchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMatchRequest $request)
    {
        try {
            DB::beginTransaction();

            $owner_id = auth()->user()->id;

            $match = GameMatch::create([
                'owner_id' => $owner_id,
                'room_name' => $request->input('room_name'),
                'privacy' => $request->input('privacy'),
                'map' => $request->input('map'),
                'game_mode' => $request->input('game_mode'),
                'max_players' => $request->input('max_players'),
                'room_time_limit' => $request->input('room_time_limit'),
                'game_mode_goal' => $request->input('game_mode_goal'),
                'bots' => $request->input('bots'),
                'pay_tournament' => $request->input('pay_tournament'),
                'payment_code' => $request->input('payment_code'),
            ]);

            DB::commit();

            return response()->json([
                'code' => 201,
                'message' => 'Partida creada exitosamente.',
                'data' => $match,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
            return response()->json([
                'code' => 500,
                'message' => 'Error al crear la partida.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualiza la partida especificada
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMatchRequest $request, int $id)
    {
        try {
            $match = GameMatch::findOrFail($id);
            $match->update($request->all());

            // Respuesta exitosa
            return response()->json([
                'code' => 200,
                'message' => 'La partida se actualizó exitosamente.',
                'data' => $match,
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
     * Elimina la partida especificada.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $match = GameMatch::findOrFail($id);

            $match->delete();

            return response()->json([
                'code' => 200,
                'message' => 'Partida eliminada exitosamente',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'code' => 404,
                'message' => 'Partida no encontrada',
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
            return response()->json([
                'code' => 500,
                'message' => 'Error al eliminar la partida',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Obtiene información de mapas diario, semanal e histórico
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function mapStatistics(Request $request)
    {
        try {
            $dailyStats = GameMatch::select('map', DB::raw('COUNT(players_scores.id) as player_count'))
                ->join('players_scores', 'matches.id', '=', 'players_scores.match_id')
                ->whereDate('matches.created_at', Carbon::today())
                ->groupBy('map')
                ->orderBy('player_count', 'desc')
                ->get();
            $weeklyStats = GameMatch::select('map', DB::raw('COUNT(players_scores.id) as player_count'))
                ->join('players_scores', 'matches.id', '=', 'players_scores.match_id')
                ->whereBetween('matches.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->groupBy('map')
                ->orderBy('player_count', 'desc')
                ->get();
            $historicalStats = GameMatch::select('map', DB::raw('COUNT(players_scores.id) as player_count'))
                ->join('players_scores', 'matches.id', '=', 'players_scores.match_id')
                ->groupBy('map')
                ->orderBy('player_count', 'desc')
                ->get();
            return response()->json([
                'code' => 200,
                'message' => 'Estadisticas de mapa.',
                'data' => [
                    'daily' => $dailyStats,
                    'weekly' => $weeklyStats,
                    'historical' => $historicalStats,
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getLine() . ' - ' . $e->getMessage() . ' - ' . $e->getFile());
            return response()->json([
                'code' => 500,
                'message' => 'Error al crear la partida.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function wonMatches(Request $request)
    {
        $user = auth()->user();

        // Obtener los IDs de partidos donde el jugador participó
        $playerMatchIds = PlayerScore::where('player_id', $user->id)->pluck('match_id')->toArray();

        // Obtener los partidos ganados por el jugador
        $matches = GameMatch::whereIn('id', $playerMatchIds)
            // ->with('playerScores')
            ->get()
            ->filter(function ($match) use ($user) {
                return $this->isPlayerWinner($match, $user->id);
            });

        // Paginar manualmente la colección de partidas ganadas
        $page = $request->input('page', 1);
        $perPage = 2;
        $paginatedMatches = $this->paginate($matches, $perPage, $page);

        return response()->json([
            'code' => 200,
            'message' => 'Matches fetched successfully',
            'data' => $paginatedMatches
        ], 200);
    }

    private function isPlayerWinner($match, $playerId)
    {
        if ($match->game_mode == 'free_for_all') {
            $highestScore = $match->playerScores->max('score');
            $playerScore = $match->playerScores->where('player_id', $playerId)->first();

            return $playerScore->score == $highestScore;
        } else {
            // Asumiendo que los equipos se identifican por team_id (0: N/A, 1: RED, 2: BLUE)
            $teamScores = $match->playerScores->groupBy('team_id')->map(function ($team) {
                return $team->sum('points');
            });

            // Obtener el ID del equipo ganador
            $winningTeamId = $teamScores->sortDesc()->keys()->first();

            // Verificar si el jugador está en el equipo ganador
            $playerTeamId = $match->playerScores->where('player_id', $playerId)->first()->team_id;

            return $playerTeamId == $winningTeamId;
        }
    }

    private function paginate($items, $perPage, $page)
    {
        $offset = ($page - 1) * $perPage;
        $paginatedItems = $items->slice($offset, $perPage)->values();

        return [
            'current_page' => $page,
            'data' => $paginatedItems,
            'per_page' => $perPage,
            'total' => $items->count(),
            'last_page' => ceil($items->count() / $perPage)
        ];
    }

    public function playerMatchHistory(Request $request)
    {
        $perPage = $request->filled('perPage') ? $request->perPage : 5;
        $playerId = auth()->id();
    
        $matchHistory = GameMatch::with(['playerScores' => function ($query) use ($playerId) {
            $query->where('player_id', $playerId)->select('id', 'match_id', 'points', 'kills', 'deaths', 'created_at');
        }])->whereHas('playerScores', function ($query) use ($playerId) {
            $query->where('player_id', $playerId);
        })
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
    
        return response()->json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $matchHistory
        ], 200);
    }
}
