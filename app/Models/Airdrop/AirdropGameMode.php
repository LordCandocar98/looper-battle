<?php

namespace App\Models\Airdrop;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AirdropGameMode extends Model
{
    use HasFactory;
    protected $table = 'airdrop_game_mode';
    protected $guarded = [];
    public function player()
    {
        return $this->belongsTo(User::class);
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
