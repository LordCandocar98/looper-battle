<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerReward extends Model
{
    use HasFactory;
    protected $table = 'payer_rewards';
    protected $guarded = [];
}
