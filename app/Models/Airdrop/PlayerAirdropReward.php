<?php

namespace App\Models\Airdrop;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayerAirdropReward extends Model
{
    use HasFactory;
    protected $table = 'player_airdrop_rewards';
    protected $guarded = [];

    public function player()
    {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function airdropReward()
    {
        return $this->belongsTo(AirdropReward::class, 'airdrop_reward_id');
    }
}
