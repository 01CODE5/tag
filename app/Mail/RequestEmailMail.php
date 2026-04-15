<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->payload['subject'] ?? 'Barangay Request Update',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request-update',
            with: [
                'payload' => $this->payload,
            ],
        );
    }
}
