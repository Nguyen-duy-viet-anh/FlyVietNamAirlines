<?php

namespace App\Mail;

use App\Models\AppBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Contracts\Queue\ShouldQueue;

class BookingConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new message instance.
     *
     * @param  AppBooking  $booking
     * @return void
     */
    public function __construct(AppBooking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Xác nhận đặt vé thành công - ' . $this->booking->booking_code)
                    ->markdown('emails.bookings.confirmed');
    }
}
