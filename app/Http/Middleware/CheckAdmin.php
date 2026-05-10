<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoles = ['admin', 'admin_booking', 'admin_airport', 'super_admin'];

        // Kiểm tra xem user đã đăng nhập chưa và có role thuộc nhóm admin không
        if (Auth::check() && in_array(strtolower(Auth::user()->role), $allowedRoles, true)) {
            return $next($request);
        }

        // Nếu không phải admin, báo lỗi 403 Forbidden
        abort(403, 'Bạn không có quyền truy cập vào khu vực này. Vui lòng kiểm tra lại quyền hạn của tài khoản.');
    }
}