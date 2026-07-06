<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'default=' . config('mail.default') . PHP_EOL;
echo 'sendmail_path=' . config('mail.mailers.sendmail.path') . PHP_EOL;
echo 'from_address=' . config('mail.from.address') . PHP_EOL;
echo 'from_name=' . config('mail.from.name') . PHP_EOL;
echo 'transport=' . get_class(app('mailer')->getSymfonyTransport()) . PHP_EOL;

new App\Mail\RegistrationWelcomeMail('John Doe', 'john@example.com', route('login'));
new App\Mail\PasswordResetOtpMail('john@example.com', '123456');
echo 'mailables_ok' . PHP_EOL;
