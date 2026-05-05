<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Refund;

use Illuminate\Contracts\Queue\ShouldQueue;

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

        // Set reply-to to the requester if present, otherwise to passenger email so admin can reply directly
        $replyEmail = $this->refund->requester->email ?? $this->refund->booking->passenger_email ?? null;
        $replyName = $this->refund->requester->name ?? $this->refund->booking->passenger_name ?? null;

        if ($replyEmail) {
            $m->replyTo($replyEmail, $replyName);
        }

        return $m->view('emails.refund_request');
    }
}
