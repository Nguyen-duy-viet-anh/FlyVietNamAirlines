<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class AdminNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $messageText;

    public function __construct($subject, $message)
    {
        $this->subjectText = $subject;
        $this->messageText = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin_notification',
        );
    }
}
