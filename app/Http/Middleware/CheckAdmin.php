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
        // Kiểm tra xem user đã đăng nhập chưa và có role là 'admin' không (không phân biệt hoa thường)
        if (Auth::check() && strtolower(Auth::user()->role) === 'admin') {
            return $next($request);
        }

        // Nếu không phải admin, báo lỗi 403 Forbidden
        abort(403, 'Bạn không có quyền truy cập vào khu vực này. Vui lòng kiểm tra lại quyền hạn của tài khoản.');
    }
}