<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Refund;

use Illuminate\Contracts\Queue\ShouldQueue;

// Gửi thông báo hoàn tiền -- admin gửi yêu cầu hoàn tiền cho user
class RefundRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Refund $refund;

    /**
     * Create a new message instance.
     */
    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

    /**
     * Build the message.
     */
    public function build(): Mailable
    {
        $subject = 'Yêu cầu hoàn tiền: ' . ($this->refund->booking->booking_code ?? '');

        $m = $this->subject($subject);

        // trả lời về email người yêu cầu nếu có
        $replyEmail = $this->refund->requester->email ?? $this->refund->booking->passenger_email ?? null;
        $replyName = $this->refund->requester->name ?? $this->refund->booking->passenger_name ?? null;

        if ($replyEmail) {
            $m->replyTo($replyEmail, $replyName);
        }

        return $m->view('emails.refund_request');
    }
}
