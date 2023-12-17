<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends \TCG\Voyager\Models\User implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'birthday_date',
        'email',
        'password',
        'profile_icon',
        'default_settings',
        'is_verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Obtener las partidas asociadas al usuario.
     */
    public function ownerMatches()
    {
        return $this->hasMany(GameMatch::class, 'owner_id');
    }

    /**
     * Obtener todos los scores de las partidas
     */
    public function playerScores()
    {
        return $this->hasMany(PlayerScore::class, 'player_id');
    }

    /**
     * Obtener puntaje total del jugador
     */
    public function getTotalScore()
    {
        return $this->playerScores->sum('points');
    }
    public function matches()
    {
        return $this->belongsToMany(GameMatch::class, 'players_scores', 'player_id', 'match_id')
            ->using(PlayerScore::class) // Si estás utilizando un modelo personalizado para el pivote
            ->withPivot(['points', 'kills', 'deaths'])
            ->withTimestamps();
    }
}
