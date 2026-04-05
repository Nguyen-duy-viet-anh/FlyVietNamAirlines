# TODO: Sửa nút swap sân bay không hoạt động trên home.blade.php

## Phân tích lỗi
- Hàm `swapAirports()` có nhưng thứ tự logic sai: update options trước khi swap values.
- Sửa: Swap values trước, update options sau.

## Các bước:
- [ ] Bước 1: Sửa `public/js/flightHelper.js` - sửa hàm swapAirports()
- [ ] Bước 2: Reload trang home, test click nút swap (kiểm tra values swap, options update, console log)
- [ ] Bước 3: ✅ Hoàn thành task (update TODO này)

**Lưu ý**: Mở F12 Console để xem log 'swapAirports:' khi test. Nếu lỗi, paste error.

