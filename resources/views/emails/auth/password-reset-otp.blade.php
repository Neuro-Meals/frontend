<x-mail::message>
# Password Reset Request

You recently requested to reset your password for your {{ $appName }} account. Use the code below to complete the reset process. This code will expire in {{ $expiresInMinutes }} minutes.

<x-mail::panel>
# {{ $otpCode }}
</x-mail::panel>

If you did not request a password reset, please ignore this email or contact support if you have concerns.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
