<?php

namespace App\Models\Airdrop;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
