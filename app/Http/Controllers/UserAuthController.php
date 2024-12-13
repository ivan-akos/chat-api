<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserAuthController extends Controller
{
    public function register(Request $request){
        $registerUserData = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|string|email|unique:users',
            'password'  => 'required|string|min:8'
        ]);

        $user = User::create([
            'name'      => $registerUserData['name'],
            'email'     => $registerUserData['email'],
            'password'  => Hash::make($registerUserData['password']),
        ])->sendEmailVerificationNotification();

        event(new Registered($user));

        return response()->json([
            'message'       => 'User registered',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     => 'required|string|email',
            'password'  => 'required|string|min:8'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user   = User::where('email', $request->email)->firstOrFail();
        $token  = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'       => 'User logged in',
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]); 
        
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'User logged out'
        ]);
    }
}
