<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialCode extends Model
{
    use HasFactory;
    protected $table = 'special_codes';
    protected $guarded = [];
}
