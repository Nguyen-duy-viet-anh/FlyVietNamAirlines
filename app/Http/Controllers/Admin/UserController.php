<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNotification;

class UserController extends Controller
{
    public function sendNotification(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $emails = [];
        if ($request->email === 'all_users') {
            $emails = User::pluck('email')->toArray();
        } elseif ($request->email === 'all_newsletters') {
            $emails = \App\Models\Newsletter::pluck('email')->toArray();
        } else {
            $emails = [$request->email];
        }

        if (empty($emails)) {
            return back()->with('error', 'Không tìm thấy địa chỉ email nào để gửi.');
        }

        try {
            // Gửi từng mail (Trong thực tế nên dùng Queue nếu danh sách quá lớn)
            foreach ($emails as $email) {
                Mail::to($email)->send(new AdminNotification($request->subject, $request->message));
            }
            return back()->with('success', 'Đã gửi thông báo thành công tới ' . count($emails) . ' địa chỉ.');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi gửi mail: ' . $e->getMessage());
        }
    }
    /**
     * Hiển thị danh sách tài khoản.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        $users = $query->orderBy('id', 'desc')->paginate(15);
        $newsletters = \App\Models\Newsletter::latest()->get();
        return view('admin.users.index', compact('users', 'newsletters'));
    }

    /**
     * Hiển thị chi tiết tài khoản và thông tin khách hàng.
     */
    public function show($id)
    {
        $user = User::with(['bookings' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

}
