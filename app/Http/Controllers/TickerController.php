<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ticker\CreateTickerRequestWeb;
use App\Models\Ticker;
use App\Models\Token;
use Exception;

class TickerController extends Controller
{
    public function index()
    {
        $tickers = Ticker::latest()->paginate(20);

        return view("tickers.index", ['tickers' => $tickers]);

    }
    
    public function show($id)
    {
        $ticker = Ticker::findOrFail($id);
        return view("tickers.show", ['ticker' => $ticker]);
    }

    public function create()
    {
        $tokens = Token::all();
        return view("tickers.create", ['tokens' => $tokens]);
    }

    public function store(CreateTickerRequestWeb $request)
    {
        try {
            $base = Token::where('id', $request->base_token_id)->first();
            if(!$base){
                return redirect()->back()->with('error', 'base token is not valid');
            }
            $target = Token::where('id', $request->target_token_id)->first();
            if(!$target){
                return redirect()->back()->with('error', 'target token is not valid');
            }

            $check = Ticker::where('base_token_id', $base->id)
                ->where('target_token_id', $target->id)
                ->first();
            if($check){
                return redirect()->back()->with('error', 'ticker for this pair is already created, try another pair');
            }
            $ticker = new Ticker();
            $ticker->base_token_id = $base->id;
            $ticker->target_token_id = $target->id;
            $ticker->name = $base->name.'/'.$target->name;
            $ticker->save();

            return redirect()->route('tickers.index')->with('success', 'Ticker has been created');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id){
        $ticker = Ticker::findOrFail($id);
        $tokens = Token::all();
        return view('tickers.edit', compact('ticker', 'tokens'));
    }

    public function destroy($id){
        $ticker = Ticker::findOrFail($id);
        $ticker->delete();
        return redirect()->route('tickers.index')->with('success', 'Ticker has been deleted');
    }

    public function update(CreateTickerRequestWeb $request, $id)
    {
        try {
            $base = Token::where('id', $request->base_token_id)->first();
            if(!$base){
                return redirect()->back()->with('error', 'base token is not valid');
            }
            $target = Token::where('id', $request->target_token_id)->first();
            if(!$target){
                return redirect()->back()->with('error', 'target token is not valid');
            }
            $ticker = Ticker::findOrFail($id);
            $ticker->base_token_id = $base->id;
            $ticker->target_token_id = $target->id;
            $ticker->name = $base->name.'/'.$target->name;
            $ticker->save();

            return redirect()->route('tickers.index')->with('success', 'Ticker has been updated');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}