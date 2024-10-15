<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function getLogin(){
        return view('auth.login');
    }

    public function postChangePassword(ChangePasswordRequest $request){
        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->back()->with('success', 'Password has been updated');
    }

    public function postLogin(LoginRequest $loginRequest){
        if(auth()->attempt(['email' => $loginRequest->email, 'password' => $loginRequest->password])){
            return redirect()->route('dashboard');
        }else{
            return redirect()->back()->with('error', 'Email or Password is not valid');
        }
    }

    public function logout(){
        auth()->logout();
        return redirect('/login');
    }

    public function changePassword(){
        return view('profile.password');
    }
}
