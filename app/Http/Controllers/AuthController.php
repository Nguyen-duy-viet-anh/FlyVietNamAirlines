<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý logic đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Kiểm tra email và mật khẩu
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Nếu là Admin thì đẩy thẳng vào trang Quản lý đơn vé
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.bookings.index');
            }
            
            // Khách hàng bình thường thì về trang chủ
            return redirect()->intended('/');
        }

        // Đăng nhập sai
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ]);
    }

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}