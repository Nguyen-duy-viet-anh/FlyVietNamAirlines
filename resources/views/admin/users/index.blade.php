@extends('layouts.admin')
@section('title', 'Quản lý Tài khoản')

@section('content')
<div class="card">
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" placeholder="Tìm kiếm tên, email, SĐT..." value="{{ request('search') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 300px;">
            <button type="submit" class="btn btn-primary" style="background: #003580; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Tìm kiếm</button>
        </form>
        <button onclick="openNotifyModal('all_users', 'Tất cả người dùng')" style="background: #f05a28; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Gửi mail cho tất cả Người dùng
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: left;">STT</th>
                <th style="width: 20%; text-align: left;">Họ tên</th>
                <th style="width: 25%; text-align: left;">Email</th>
                <th style="width: 15%; text-align: left;">Số điện thoại</th>
                <th style="width: 10%; text-align: center;">Vai trò</th>
                <th style="width: 15%; text-align: center;">Ngày đăng ký</th>
                <th style="width: 15%; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                <td>
                    <strong>{{ $user->name }}</strong>
                </td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? 'N/A' }}</td>
                <td style="text-align: center;">
                    @if($user->role === 'admin')
                        <span style="background: #e1001a; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Admin</span>
                    @else
                        <span style="background: #28a745; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Khách</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    {{ $user->created_at->format('d/m/Y') }}
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.users.show', $user->id) }}" style="text-decoration: none; color: #003580; font-weight: 600; font-size: 13px; margin-right: 10px;">
                        Chi tiết
                    </a>
                    <button onclick="openNotifyModal('{{ $user->email }}', '{{ $user->email }}')" style="background: none; border: none; color: #28a745; font-weight: 600; font-size: 13px; cursor: pointer; padding: 0;">
                        Gửi mail
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">Không tìm thấy tài khoản nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="mt-20">
        {{ $users->onEachSide(1)->links('pagination.admin') ?? $users->links() }}
    </div>
    </div>

    <!-- Newsletter Subscribers Section -->
    <div class="card" style="margin-top: 40px; border-top: 4px solid #f05a28;">
        <div class="card-header" style="background: #fff; padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #333; font-size: 18px;">
                Danh sách đăng ký nhận tin (Footer)
            </h3>
            <button onclick="openNotifyModal('all_newsletters', 'Tất cả người đăng ký nhận tin')" style="background: #003580; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold;">
                Gửi mail cho tất cả
            </button>
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 12px 20px; text-align: left; border-bottom: 2px solid #dee2e6;">STT</th>
                        <th style="padding: 12px 20px; text-align: left; border-bottom: 2px solid #dee2e6;">Email</th>
                        <th style="padding: 12px 20px; text-align: left; border-bottom: 2px solid #dee2e6;">Ngày đăng ký</th>
                        <th style="padding: 12px 20px; text-align: left; border-bottom: 2px solid #dee2e6;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newsletters as $index => $item)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px 20px;">{{ $index + 1 }}</td>
                            <td style="padding: 12px 20px; font-weight: 500; color: #0056b3;">{{ $item->email }}</td>
                            <td style="padding: 12px 20px; color: #666;">{{ $item->created_at->format('H:i - d/m/Y') }}</td>
                            <td style="padding: 12px 20px;">
                                <button onclick="openNotifyModal('{{ $item->email }}', '{{ $item->email }}')" style="padding: 5px 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Gửi thông báo</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 30px; text-align: center; color: #999;">Chưa có ai đăng ký nhận tin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- NOTIFICATION MODAL -->
    <div id="notifyModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
        <div style="background: white; width: 500px; margin: 100px auto; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            <div style="background: #003580; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0;">Gửi thông báo Email</h4>
                <span onclick="closeNotifyModal()" style="cursor: pointer; font-size: 24px;">&times;</span>
            </div>
            <form action="{{ route('admin.users.send_notification') }}" method="POST" style="padding: 20px;">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Tới Email:</label>
                    <input type="text" name="email" id="notifyEmail" readonly style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Tiêu đề:</label>
                    <input type="text" name="subject" required placeholder="Nhập tiêu đề..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nội dung:</label>
                    <textarea name="message" rows="5" required placeholder="Nhập nội dung thông báo..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeNotifyModal()" style="padding: 10px 20px; background: #eee; border: none; border-radius: 4px; cursor: pointer;">Hủy</button>
                    <button type="submit" style="padding: 10px 25px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Gửi ngay</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNotifyModal(email) {
            document.getElementById('notifyEmail').value = email;
            document.getElementById('notifyModal').style.display = 'block';
        }
        function closeNotifyModal() {
            document.getElementById('notifyModal').style.display = 'none';
        }
        // Đóng khi click ra ngoài
        window.onclick = function(event) {
            let modal = document.getElementById('notifyModal');
            if (event.target == modal) {
                closeNotifyModal();
            }
        }
    </script>
@endsection
