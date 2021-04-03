<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request){

        $credentials= $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)){
            return $this->respondErrorWithMessage('Email or password is wrong');
        }

        $user = User::where('email', $request->get('email'))
            ->first();
        $user->token = $token;

        return $this->respondOkWithData($user);
    }

    public function register(RegisterRequest $request){

        $user = User::create([
           'name' => $request->get('name'),
           'email' => $request->get('email'),
           'password' => Hash::make($request->get('password'))
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)){
            return $this->respondErrorWithMessage('Email or password is wrong');
        }

        $user->token = $token;

        return $this->respondOkWithData($user);
    }

    public function test(){
        return $this->respondOkWithData(Auth::user());
    }
}
