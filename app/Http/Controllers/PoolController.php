<?php

namespace App\Http\Controllers;

use App\Models\Pool;

class PoolController extends Controller
{
    public function index()
    {
        $pools = Pool::latest()->paginate(20);
        return view("pools.index", ['pools' => $pools]);

    }
    
    public function show($id)
    {
        $pool = Pool::findOrFail($id);
        return view("pools.show", ['pool' => $pool]);
    }
}