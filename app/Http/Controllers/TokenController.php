<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Token\CreateTokenRequestWeb;
use App\Models\Token;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TokenController extends Controller
{
    public function index()
    {
        $tokens = Token::latest()->paginate(20);
        return view("tokens.index", ['tokens' => $tokens]);

    }
    
    public function show($id)
    {
        $token = Token::findOrFail($id);
        return view("tokens.show", ['token' => $token]);
    }

    public function create()
    {
        return view("tokens.create");
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
    
            $response = Http::get('https://api.binance.com/api/v3/ticker/price');
            $tokens = collect($response->json())->filter(function($token) use ($query) {
                return stripos($token['symbol'], $query) !== false;
            })->take(10);
    
            return response()->json($tokens);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function store(CreateTokenRequestWeb $request)
    {
        try {
            $token = new Token();
            $token->name = $request->name;
            $token->symbol = $request->symbol;
            if($request->has('tokenObject')){
                $token->tokenObject = json_encode($request->tokenObject);
            }
            $token->save();

            return redirect()->route('tokens.index')->with('success', 'Token has been created');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id){
        $token = Token::findOrFail($id);
        return view('tokens.edit', compact('token'));
    }

    public function destroy($id){
        $token = Token::findOrFail($id);
        $token->delete();
        return redirect()->route('tokens.index')->with('success', 'Token has been deleted');
    }

    public function update(CreateTokenRequestWeb $request, $id)
    {
        try {
            $token = Token::findOrFail($id);
            $token->name = $request->name;
            $token->symbol = $request->symbol;
            if($request->has('tokenObject')){
                $token->tokenObject = json_encode($request->tokenObject);
            }
            $token->save();

            return redirect()->route('tokens.index')->with('success', 'Token has been updated');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}