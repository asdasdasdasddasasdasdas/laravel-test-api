<?php

namespace App\Http\Controllers\api;



use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    function register(Request $request)
    {

        $data = $request->validate([
            'login' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = User::create($data);

        if (!Auth::attempt($data)) {
            return response()->json(['error' => 'Unable to authenticate user'], 500);
        }

        $token = Auth::user()->createToken('auth-token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }


    function login(Request $request)
    {

        $data = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);


        if (!Auth::attempt($data)) {
            return response()->json(['error' => 'Invalid login or password'], 401);
        }


        $token = Auth::user()->createToken('auth-token')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
