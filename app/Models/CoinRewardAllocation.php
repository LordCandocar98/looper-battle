<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinRewardAllocation extends Model
{
    use HasFactory;
    protected $table = 'coin_reward_allocations';
    protected $guarded = [];
}
