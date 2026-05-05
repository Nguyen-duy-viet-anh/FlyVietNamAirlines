<?php

namespace App\Notifications;

use App\Models\AppBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    /**
     * Notification gửi email xác nhận đặt vé (sử dụng Markdown mail)
     * Implemented ShouldQueue để gửi async.
     */
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     *
     * @param  AppBooking  $booking
     * @return void
     */
    public function __construct(AppBooking $booking)
    {
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
        // Tạo MailMessage sử dụng Markdown template `emails.bookings.confirmed`
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
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'amount' => $this->booking->total_amount,
        ];
    }
}
