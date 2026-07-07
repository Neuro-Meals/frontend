<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifiedWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $fullName;
    public string $email;
    public string $loginUrl;

    public function __construct(string $fullName, string $email)
    {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->loginUrl = route('login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Welcome to :app — Your email is verified!', ['app' => config('app.name', 'Nutrio Meals')]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.welcome-verified',
            with: [
                'fullName' => $this->fullName,
                'email' => $this->email,
                'loginUrl' => $this->loginUrl,
                'appName' => config('app.name', 'Nutrio Meals'),
            ],
        );
    }
}
