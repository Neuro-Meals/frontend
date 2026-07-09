<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DriverCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $firstName;
    public string $email;
    public string $password;
    public string $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(string $firstName, string $email, string $password, string $loginUrl)
    {
        $this->firstName = $firstName;
        $this->email = $email;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Your Nitrio Meals Driver Account'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.driver_credentials',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
