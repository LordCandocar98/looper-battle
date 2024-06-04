<?php

namespace App\Models\Airdrop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayerAirdropReward extends Model
{
    use HasFactory;
    protected $table = 'player_airdrop_rewards';
    protected $guarded = [];
}
