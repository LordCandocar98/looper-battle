<?php

namespace App\Models;

use TCG\Voyager\Models\Role;
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
        'gender',
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
     * Obtener el monto total de de coins del usuario
     */
    public function coins()
    {
        return $this->hasOne(Coin::class, 'player_id');
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
    public function grantReward($rewardId)
    {
        // Verificar si el jugador ya ha recibido esta recompensa
        if (!$this->hasReceivedReward($rewardId)) {
            // Asignar la recompensa al jugador
            $this->rewards()->attach($rewardId);
        }
    }
    public function hasReceivedReward($rewardId)
    {
        return $this->rewards()->where('reward_id', $rewardId)->exists();
    }

    public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'player_rewards', 'player_id', 'reward_id')
            ->withTimestamps();
    }
    public function purchaseItem($itemId, $purchaseType)
    {
        // Verificar si el jugador ya ha comprado este item
        if (!$this->ownsItem($itemId)) {
            // Comprar el item y registrar la compra
            $this->items()->attach($itemId, ['purchase_type_id' => $purchaseType]);
        }
    }

    public function ownsItem($itemId)
    {
        return $this->items()->where('item_id', $itemId)->exists();
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'purchases', 'player_id', 'item_id')
            ->withTimestamps();
    }
    public function grantAirdropReward($rewardId)
    {
        $this->airdropRewards()->attach($rewardId);
    }

    public function airdropRewards()
    {
        return $this->belongsToMany(Reward::class, 'player_airdrop_rewards', 'player_id', 'airdrop_reward_id')
            ->withTimestamps();
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
