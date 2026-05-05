<?php

namespace App\Jobs;

use App\Models\AppBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingConfirmedNotification;

class SendBookingConfirmationEmail implements ShouldQueue
{
    /**
     * Job gửi email xác nhận đặt vé khi thanh toán thành công.
     * Job này implement `ShouldQueue` nên được xử lý bởi queue worker.
     * Gọi Notification::route('mail', ...) để gửi notification dạng mail.
     */
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking;

    /**
     * Create a new job instance.
     *
     * @param  AppBooking  $booking
     * @return void
     */
    public function __construct(AppBooking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Thực tế gửi notification; lỗi sẽ được catch và log để không làm crash queue worker.
        try {
            Notification::route('mail', $this->booking->passenger_email)
                ->notify(new BookingConfirmedNotification($this->booking));
        } catch (Exception $e) {
            Log::error('Lỗi gửi thông báo xác nhận đặt vé qua queue: ' . $e->getMessage());
        }
    }
}
