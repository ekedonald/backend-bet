<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bet extends Model
{
    use HasFactory, SoftDeletes, UuidTrait;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function pool(){
        return $this->belongsTo(Pool::class);
    }

    public function ticker(){
        return $this->belongsTo(Ticker::class);
    }
}
