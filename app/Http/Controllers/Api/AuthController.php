<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function user_login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => '帳號或密碼錯誤'], 401);
        }
        $user = auth()->user();


        return response()->json([
            'message' => '登入成功',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
        ]);
    }

    public function user_register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 2,
            'membership_level_id' => 1,
        ]);

        $token = JWTAuth::fromUser($user);

        $user = auth()->user();

        return response()->json([
            'message' => '註冊成功',
            'token' => $token,
           'user' => [
            'id' => $user->id,
            'name' => $user->name,
            ],
        ]);
    }

    public function user_logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => '登出成功']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token 無效或登出失敗'], 400);
        }
    }

}

