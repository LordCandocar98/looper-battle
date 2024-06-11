<?php

namespace App\Models\Airdrop;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AirdropReward extends Model
{
    use HasFactory;
    protected $table = 'airdrop_rewards';
    protected $guarded = [];
    public function players()
    {
        return $this->belongsToMany(User::class, 'user_airdrop_rewards')->withTimestamps();
    }
    public function playerAirdropRewards()
    {
        return $this->hasMany(PlayerAirdropReward::class, '', '');
    }
}
