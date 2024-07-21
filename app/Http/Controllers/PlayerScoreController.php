<?php

// app/Http/Controllers/PlayerScoreController.php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\PlayerScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PlayerScores\StorePlayerScoreRequest;

class PlayerScoreController extends Controller
{
    public function index()
    {
        $scores = PlayerScore::with(['player', 'gameMatch', 'team'])->get();
        return response()->json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $scores
        ], 200);
    }

    public function show($id)
    {
        $score = PlayerScore::find($id);

        if (!$score) {
            return response()->json([
                'code' => 404,
                'message' => 'Puntuación no encontrada'
            ], 404);
        }

        return response()->json(['data' => $score], 200);
    }

    public function store(StorePlayerScoreRequest $request)
    {
        if (PlayerScore::where(['player_id' => auth()->user()->id, 'match_id' => $request->input('match_id')])->exists()) {
            return response()->json([
                'code' => 409,
                'message' => 'Comprobar información',
                'errors' => [
                    'match_id' => ['Ya existe un registro para este jugador en esta partida.']
                ]
            ], 409);
        }
        $score = PlayerScore::create([
            'player_id' => auth()->user()->id,
            'match_id' => $request->input('match_id'),
            'team_id' => $request->input('team_id') ?? 0,
            'points' => $request->input('points'),
            'kills' => $request->input('kills'),
            'deaths' => $request->input('deaths'),
        ]);

        return response()->json([
            'code' => 201,
            'message' => 'Puntución guardada.',
            'data' => $score
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $score = PlayerScore::find($id);

        if (!$score) {
            return response()->json(['message' => 'Puntuación no encontrada'], 404);
        }
        $score->update([
            'points' => $request->input('points'),
            'kills' => $request->input('kills'),
            'deaths' => $request->input('deaths'),
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Puntución actualizada.',
            'data' => $score
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

    public function topPlayers(Request $request)
    {
        $type = $request->filled('type') ? $request->type : 'historical';
        $perPage = $request->filled('perPage') ? $request->perPage : 5;

        switch ($type) {
            case 'weekly':
                $topPlayers = PlayerScore::with(['player' => function ($query) {
                    $query->select('id', 'nickname', 'profile_icon');
                }])->select('player_id', DB::raw('SUM(points) as total_points'))
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('player_id')
                    ->orderBy('total_points', 'desc')
                    ->paginate($perPage);
                break;

            case 'historical':
                $topPlayers = PlayerScore::with(['player' => function ($query) {
                    $query->select('id', 'nickname', 'profile_icon');
                }])->select('player_id', DB::raw('SUM(points) as total_points'))
                    ->groupBy('player_id')
                    ->orderBy('total_points', 'desc')
                    ->paginate($perPage);
                break;

            case 'daily':
            default:
            $topPlayers = PlayerScore::with(['player' => function ($query) {
                $query->select('id', 'nickname', 'profile_icon');
            }])->select('player_id', DB::raw('SUM(points) as total_points'))
                    ->whereDate('created_at', Carbon::today())
                    ->groupBy('player_id')
                    ->orderBy('total_points', 'desc')
                    ->paginate($perPage);
                break;
        }

        return response()->json([
            'code' => 200,
            'message' => 'Solicitud exitosa.',
            'data' => $topPlayers
        ], 200);
    }
}
