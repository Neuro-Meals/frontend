<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $fullName;
    public string $email;
    public string $verificationUrl;

    public function __construct(string $fullName, string $email, ?string $verificationUrl = null)
    {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->verificationUrl = $verificationUrl ?? route('login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . config('app.name', 'Nutrio Meals') . ' - Your account is ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.welcome',
            with: [
                'fullName' => $this->fullName,
                'email' => $this->email,
                'verificationUrl' => $this->verificationUrl,
                'appName' => config('app.name', 'Nutrio Meals'),
            ],
        );
    }
}
