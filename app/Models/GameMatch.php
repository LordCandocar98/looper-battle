<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameMatch extends Model
{
    use HasFactory;
    protected $table = 'matches';
    protected $guarded = [];

    /**
     * Obtiene al propietario de la partida.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Obtiene al propietario de la partida.
     */
    public function playerScores()
    {
        return $this->hasMany(PlayerScore::class, 'match_id','id');
    }
}
