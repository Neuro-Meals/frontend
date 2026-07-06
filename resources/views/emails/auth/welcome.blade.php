<x-mail::message>
# Welcome to {{ $appName }}, {{ $fullName }}!

Your account has been created successfully. We're excited to have you on board and help you enjoy chef-crafted, nutritionist-approved meals delivered to your door.

<x-mail::button :url="$verificationUrl">
Log In to Your Account
</x-mail::button>

If you did not create this account, please ignore this email or contact our support team.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
