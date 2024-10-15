<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, UuidTrait, SoftDeletes;

    public function getPaymentObjectAttribute($payment){
        return json_decode($payment);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
