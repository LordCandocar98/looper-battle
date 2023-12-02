<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameMatch extends Model
{
    use HasFactory;
    protected $table = 'matches';
    protected $guarded = [];

    /**
     * Obtiene al propietario de la partida.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
