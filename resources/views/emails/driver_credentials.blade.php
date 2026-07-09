<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Your Driver Account') }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f6f7f9; font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .container { max-width: 520px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #173327 0%, #6E7A25 100%); padding: 40px 32px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.8); margin: 8px 0 0; font-size: 14px; }
        .body { padding: 32px; color: #333; }
        .greeting { font-size: 18px; font-weight: 700; margin-bottom: 16px; color: #173327; }
        .message { font-size: 15px; line-height: 1.6; color: #555; margin-bottom: 24px; }
        .credentials { background: #f6f3e9; border-left: 4px solid #6E7A25; border-radius: 10px; padding: 20px; margin-bottom: 24px; }
        .credential-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid rgba(110,122,37,0.15); }
        .credential-row:last-child { border-bottom: none; }
        .credential-label { font-size: 13px; color: #6c757d; font-weight: 600; }
        .credential-value { font-size: 14px; color: #173327; font-weight: 700; font-family: monospace; }
        .btn { display: inline-block; background: linear-gradient(135deg, #173327 0%, #6E7A25 100%); color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 15px; margin-top: 8px; }
        .footer { padding: 24px 32px; background: #f8f9fa; text-align: center; font-size: 12px; color: #999; }
        .footer a { color: #6E7A25; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('Welcome to the Team!') }}</h1>
            <p>{{ __('Your driver account is ready.') }}</p>
        </div>
        <div class="body">
            <p class="greeting">{{ __('Hi :name,', ['name' => $firstName]) }}</p>
            <p class="message">
                {{ __('An admin has created a driver account for you on the Nutrio Meals delivery platform. You can log in using the credentials below. Please change your password after your first login.') }}
            </p>

            <div class="credentials">
                <div class="credential-row">
                    <span class="credential-label">{{ __('Email') }}</span>
                    <span class="credential-value">{{ $email }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">{{ __('Password') }}</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <a href="{{ $loginUrl }}" class="btn">{{ __('Login to Driver Dashboard') }}</a>

            <p style="margin-top: 24px; font-size: 13px; color: #888;">
                {{ __('If the button above does not work, copy and paste this link into your browser:') }}
                <br>
                <a href="{{ $loginUrl }}" style="color: #6E7A25;">{{ $loginUrl }}</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
        </div>
    </div>
</body>
</html>
