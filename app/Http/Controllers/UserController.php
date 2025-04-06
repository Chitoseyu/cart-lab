<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //

    public function login_page()
    {
        return view('page.auth.login');
        
    }
    public function register_page()
    {
        return view('page.auth.register');
    }
    public function user_login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();
        $err_type = [];

        if ($user) {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $response = ['type'  => 'success','message' => '登入成功'];
                return redirect()->intended('/')->with($response);
            }
            $err_type = ['password' => '密碼錯誤'];
        } else {
            $err_type = ['email' => '帳號不存在'];
        }
        return back()->withErrors($err_type)->withInput();
        
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
        ]);

        Auth::login($user);

        $response = ['type'  => 'success','message' => '註冊完成並登入成功'];
        return redirect('/')->with($response);
    }
    public function user_logout(Request $request)
    {
        Auth::logout();

        // 讓 session 失效，防止 CSRF token 重複使用
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        $response = ['type'  => 'success','message' => '您已成功登出'];

        return redirect('/')->with($response);
    }
    public function edit_profile()
    {
        $user = Auth::user();
        return view('page.auth.profile', compact('user'));
    }

    public function update_profile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $response = ['type'  => 'success','message' => '資料更新成功'];

        return redirect()->route('shop.profile')->with($response);
    }

}
