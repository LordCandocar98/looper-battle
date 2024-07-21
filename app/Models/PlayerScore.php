<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayerScore extends Model
{
    use HasFactory;
    protected $table = 'players_scores';
    protected $guarded = [];

    /**
     * Obtiene al jugador de la partida
     */
    public function player()
    {
        return $this->belongsTo(User::class, 'player_id');
    }
    /**
     * Obtiene la partida jugada
     */
    public function gameMatch()
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
