<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinReward extends Model
{
    use HasFactory;
    protected $table = 'coin_rewards';
    protected $guarded = [];
}
