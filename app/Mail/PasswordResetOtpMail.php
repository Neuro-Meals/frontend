<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $email;
    public string $otpCode;
    public string $expiresInMinutes;

    public function __construct(string $email, string $otpCode, int $expiresInMinutes = 15)
    {
        $this->email = $email;
        $this->otpCode = $otpCode;
        $this->expiresInMinutes = (string) $expiresInMinutes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your password reset code for ' . config('app.name', 'Nutrio Meals'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.password-reset-otp',
            with: [
                'email' => $this->email,
                'otpCode' => $this->otpCode,
                'expiresInMinutes' => $this->expiresInMinutes,
                'appName' => config('app.name', 'Nutrio Meals'),
            ],
        );
    }
}
