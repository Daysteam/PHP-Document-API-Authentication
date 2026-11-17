<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!$token = auth('api')->attempt($credentials)){
            return response()->json([
                'success' => false,
                'code' => 401,
                'email' => 'email atau password salah'
            ],401);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ],200);
    }

    public function register(Request $request){
        $validated = $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'success' => true,
            'code' => 201,
            'message' => 'Akun berhasil dibuat',
            'data' => $user
        ],201);
    }

    public function logout(){
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Berhasil logout'
        ],200);
    }

    public function profile(){
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Berhasil mengambil profile',
            'data' => auth('api')->user()
        ],200);
    }

    public function refresh(){
        $token = auth('api')->refresh();

        return response()->json([
            'success' => true,
            'code' => 200,
            'access_token' => $token,
            'token_type' =>  'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ],200);
    }
}
