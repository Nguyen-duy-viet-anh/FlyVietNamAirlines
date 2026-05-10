<?php

namespace App\Notifications;

use App\Models\AppBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification gửi email xác nhận đặt vé (sử dụng Markdown mail).
 *
 * - Implements ShouldQueue để gửi bất đồng bộ (queue).
 * - `toMail()` trả về một MailMessage sử dụng markdown template.
 */
class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    /**
     * Trait Queueable cung cấp helper để push notification vào queue.
     */
    use Queueable;

    /**
     * @var AppBooking Instance của AppBooking chứa dữ liệu đặt vé.
     * Dùng để truyền vào view/email và để lưu vào mảng `toArray()`.
     */
    protected $booking;

    /**
     * Create a new notification instance.
     *
     * @param  AppBooking  $booking
     * @return void
     */
    public function __construct(AppBooking $booking)
    {
        // Lưu model AppBooking để sử dụng trong `toMail()` và `toArray()`
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Trả về kênh gửi notification: 'mail'.
        // Có thể thêm 'database' hoặc kênh khác nếu cần.
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Tạo MailMessage sử dụng markdown template `emails.bookings.confirmed`.
        // Subject hiển thị mã đặt vé; template nhận biến 'booking'.
        return (new MailMessage)
            ->subject('Xác nhận đặt vé thành công - ' . $this->booking->booking_code)
            ->markdown('emails.bookings.confirmed', ['booking' => $this->booking]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // Mảng dữ liệu đại diện cho notification (ví dụ khi dùng 'database' channel).
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'amount' => $this->booking->total_amount,
        ];
    }
}
