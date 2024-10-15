<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pool extends Model
{
    use HasFactory, UuidTrait, SoftDeletes;

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    protected $dates = ['closed_at', 'settled_at', 'fetch_init_price_at', 'fetch_final_price_at'];

    // protected $dates = [
    //     'closed_at',
    // ];

    // protected $casts = [
    //     'closed_at' => 'datetime:Y-m-d\TH:i:s.u\Z',
    // ];
}
