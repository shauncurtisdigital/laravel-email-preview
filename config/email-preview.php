<?php

return [
    'enabled' => env('MAIL_PREVIEW_ENABLED', true),
    'environments' => ['local', 'testing', 'staging'],
    'route_prefix' => 'email-preview',
    'middleware' => ['web'],
    'test_recipient' => env('TEST_EMAIL_ADDRESS'),

    'previews' => [
        'password-reset' => [
            'label' => 'Password Reset',
            'view' => 'emails.password-reset',
            'subject' => 'Reset Your Password',
            'data' => fn () => [
                'name' => 'John Doe',
                'email' => 'test@example.com',
                'reset_url' => url('/reset-password/sample-token?email=test@example.com'),
                'support_email' => config('mail.from.address'),
            ],
        ],
        'welcome' => [
            'label' => 'Welcome Email',
            'view' => 'emails.welcome',
            'subject' => 'Welcome to the platform',
            'data' => fn () => [
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
            ],
        ],
    ],
];
