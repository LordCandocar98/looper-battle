<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;
    protected $table = 'coins';
    protected $guarded = [];
    public function player()
    {
        return $this->belongsTo(User::class, 'player_id');
    }
}
