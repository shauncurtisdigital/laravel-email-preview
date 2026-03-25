<?php

return [
    'enabled' => env('MAIL_PREVIEW_ENABLED', true),
    'environments' => ['local', 'testing', 'staging'],
    'route_prefix' => 'email-preview',
    'middleware' => ['web'],
    'test_recipient' => env('TEST_EMAIL_ADDRESS'),

    /*
    |--------------------------------------------------------------------------
    | Email Previews
    |--------------------------------------------------------------------------
    |
    | Define your email templates here. Each preview should have:
    | - label: Display name in the UI
    | - view: Path to the Blade template
    | - subject: Email subject line
    | - data: Array or closure returning data for the template
    |
    | Note: If using closures for 'data', config caching will be disabled.
    | For production-ready configs, use arrays or service providers instead.
    |
    */

    'previews' => [
        // Example:
        // 'welcome' => [
        //     'label' => 'Welcome Email',
        //     'view' => 'emails.welcome',
        //     'subject' => 'Welcome to Our Platform',
        //     'data' => [
        //         'name' => 'John Doe',
        //         'email' => 'test@example.com',
        //     ],
        // ],
    ],
];
