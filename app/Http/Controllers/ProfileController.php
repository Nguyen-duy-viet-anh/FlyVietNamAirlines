<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.unique' => 'Email này đã được sử dụng.',
            'new_password.min' => 'Mật khẩu mới phải từ 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'current_password.required_with' => 'Vui lòng nhập mật khẩu hiện tại để đổi mật khẩu mới.'
        ]);

        // Kiểm tra mật khẩu cũ nếu muốn đổi mật khẩu mới
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }
}
