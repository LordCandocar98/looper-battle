<?php

namespace App\Models\Airdrop;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AirdropGameModeScore extends Model
{
    use HasFactory;
    protected $table = 'airdrop_game_mode_scores';
    protected $guarded = [];
    public function player()
    {
        return $this->belongsTo(User::class);
    }
    public function airdropGame()
    {
        return $this->belongsTo(AirdropGameMode::class);
    }
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
