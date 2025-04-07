<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

        // 一般用戶
        $role_id = 2;
        // 普通會員
        $membership_level_id  = 1;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role_id,
            'membership_level_id' => $membership_level_id,
        ]);

        Auth::login($user);

        $response = ['type'  => 'success','message' => '註冊完成! 已替您登入網站'];
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

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'birthday' => 'nullable|date',
            'zip_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:50',
            'district' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'remove_avatar' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        try {
            $fillableFields = [
                'name', 'email', 'phone', 'gender', 'birthday', 
                'zip_code', 'city', 'district', 'address'
            ];
            // 更新基本資料
            foreach (array_intersect_key($data, array_flip($fillableFields)) as $key => $value) {
                $user->$key = $value;
            }

            // 處理密碼
            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            // 處理頭像上傳
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = $user->id . '.' . $avatar->getClientOriginalExtension();
                if ($avatar->storeAs('images/avatars', $filename, 'public')) {
                    $user->avatar = $filename;
                } else {
                    return back()->withErrors(['avatar' => '頭像上傳失敗'])->withInput();
                }
            }

            // 處理頭像移除
            if ($request->input('remove_avatar') == '1') {
                if ($user->avatar) {
                    Storage::delete('public/images/avatars/' . $user->avatar);
                }
                $user->avatar = null;
            }

            $user->save();
            $response = ['type' => 'success', 'message' => '資料更新成功'];
            return redirect()->route('shop.profile')->with($response);

        } catch (\Exception $e) {
            // 更新失敗
            return back()->withErrors(['error' => '資料更新失敗，請稍後再試'])->withInput();
        }
    }

}
