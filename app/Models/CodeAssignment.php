<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeAssignment extends Model
{
    use HasFactory;
    protected $table = 'code_assignments';
    protected $guarded = [];
    public function player()
    {
        return $this->belongsTo(User::class);
    }
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
