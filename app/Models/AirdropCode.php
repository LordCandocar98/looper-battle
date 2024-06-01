<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirdropCode extends Model
{
    use HasFactory;
    protected $table = 'airdrop_codes';
    protected $guarded = [];
    public function player()
    {
        return $this->belongsTo(User::class);
    }
}
