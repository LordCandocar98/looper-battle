<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialCode extends Model
{
    use HasFactory;
    protected $table = 'special_codes';
    protected $guarded = [];
    public function assignment()
    {
        return $this->hasOne(CodeAssignment::class, 'code', 'code');
    }
    public function purchaseType()
    {
        return $this->belongsTo(PurchaseType::class, 'purchase_type_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
