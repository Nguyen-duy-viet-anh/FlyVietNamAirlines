<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Refund;

use Illuminate\Contracts\Queue\ShouldQueue;
// Gửi thông báo hoàn tiền --- Cái này user gửi admin nhận
class RefundCompletedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Refund $refund;
    public float $originalAmount;
    public float $refundRate;
    public float $refundAmount;

    /**
     * Create a new message instance.
     */
    public function __construct(Refund $refund, float $originalAmount, float $refundRate)
    {
        $this->refund = $refund;
        $this->originalAmount = $originalAmount;
        $this->refundRate = $refundRate;
        $this->refundAmount = round($originalAmount * $refundRate, 2);
    }

    /**
     * Build the message.
     */
    public function build(): Mailable
    {
        $subject = 'Hoan tien thanh cong: ' . ($this->refund->booking->booking_code ?? '');

        return $this->subject($subject)->view('emails.refund_completed');
    }
}
