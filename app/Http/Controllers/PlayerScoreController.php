<?php

// app/Http/Controllers/PlayerScoreController.php

namespace App\Http\Controllers;

use App\Models\PlayerScore;
use Illuminate\Http\Request;
use App\Http\Requests\PlayerScores\StorePlayerScoreRequest;

class PlayerScoreController extends Controller
{
    public function index()
    {
        $scores = PlayerScore::with(['player', 'gameMatch'])->get();
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
}
