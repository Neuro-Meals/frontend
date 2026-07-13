<x-mail::message>
# {{ __('Welcome aboard, :name!', ['name' => $fullName]) }}

{{ __('Your email :email has been successfully verified. Your :app account is now fully active.', ['email' => $email, 'app' => $appName]) }}

<x-mail::button :url="$loginUrl">
{{ __('Sign In to Your Account') }}
</x-mail::button>

## {{ __('Quick tips to get started') }}

- **{{ __('Explore your meal plan') }}** — {{ __('Check your dashboard to see today\'s meals and upcoming deliveries.') }}
- **{{ __('Track your nutrition') }}** — {{ __('Log water, steps, and calories from the Nutrition Tracker.') }}
- **{{ __('Update your preferences') }}** — {{ __('Set allergies and goals in your profile.') }}
- **{{ __('Get support anytime') }}** — {{ __('Use the AI assistant on your dashboard for quick help with meals and plans.') }}

## {{ __('Your account details') }}

| | |
|---|---|
| **{{ __('Email') }}** | {{ $email }} |
| **{{ __('Account status') }}** | {{ __('Verified') }} |
| **{{ __('Login URL') }}** | [{{ $loginUrl }}]({{ $loginUrl }}) |

{{ __('If you did not create this account, please contact our support team immediately.') }}

{{ __('Thanks,') }}<br>
{{ $appName }} {{ __('Team') }}
</x-mail::message>
