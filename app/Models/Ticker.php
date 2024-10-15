<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticker extends Model
{
    use HasFactory, UuidTrait, SoftDeletes;

    public function base_token(){
        return $this->belongsTo(Token::class, 'base_token_id');
    }

    public function target_token(){
        return $this->belongsTo(Token::class, 'target_token_id');
    }

    public function pools()
    {
        return $this->hasMany(Pool::class);
    }
}
