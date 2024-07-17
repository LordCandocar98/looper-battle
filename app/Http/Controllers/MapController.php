<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index()
    {
        // Estadísticas diarias
        $dailyMapStats = DB::table('matches')
            ->select('map', DB::raw('COUNT(*) as match_count'), DB::raw('SUM(players_scores.player_id) as player_count'))
            ->join('players_scores', 'matches.id', '=', 'players_scores.match_id')
            ->whereDate('matches.created_at', Carbon::today())
            ->groupBy('map')
            ->orderBy('match_count', 'desc')
            ->get();

        // Estadísticas semanales
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyMapStats = DB::table('matches')
            ->select('map', DB::raw('COUNT(*) as match_count'), DB::raw('SUM(players_scores.player_id) as player_count'))
            ->join('players_scores', 'matches.id', '=', 'players_scores.match_id')
            ->whereBetween('matches.created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('map')
            ->orderBy('match_count', 'desc')
            ->get();

        // Estadísticas históricas
        $historicalMapStats = DB::table('matches')
            ->select('map', DB::raw('COUNT(*) as match_count'), DB::raw('SUM(players_scores.player_id) as player_count'))
            ->join('players_scores', 'matches.id', '=', 'players_scores.match_id')
            ->groupBy('map')
            ->orderBy('match_count', 'desc')
            ->get();

        return view('maps.map', compact('dailyMapStats', 'weeklyMapStats', 'historicalMapStats', 'startOfWeek', 'endOfWeek'));
    }
}
