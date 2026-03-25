<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Preview Enabled
    |--------------------------------------------------------------------------
    |
    | Enable or disable the email preview routes. When disabled, no routes
    | will be registered and the preview tool will be completely unavailable.
    |
    */
    'enabled' => env('EMAIL_PREVIEW_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Allowed Environments
    |--------------------------------------------------------------------------
    |
    | The environments in which email previews are allowed. Routes will only
    | be registered if the current environment is in this list.
    |
    */
    'environments' => ['local', 'testing', 'staging'],

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | The URL prefix for all email preview routes.
    | Example: 'email-preview' creates routes like /email-preview
    |
    */
    'route_prefix' => 'email-preview',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware to apply to all email preview routes.
    | Add authentication middleware here if needed.
    |
    */
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Default Test Recipient
    |--------------------------------------------------------------------------
    |
    | The email address to send test emails to. Required for the send
    | functionality. Set TEST_EMAIL_ADDRESS in your .env file.
    |
    */
    'default_to' => env('TEST_EMAIL_ADDRESS', 'test@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Email Previews
    |--------------------------------------------------------------------------
    |
    | Define your email previews here using preview classes.
    | Each key is the preview slug, and the value is the fully-qualified
    | class name of a class that implements EmailPreview interface.
    |
    | Example:
    |
    | 'previews' => [
    |     'password-reset' => \App\MailPreviews\PasswordResetPreview::class,
    |     'welcome' => \App\MailPreviews\WelcomePreview::class,
    | ],
    |
    | Legacy array format (without closures) is also supported:
    |
    | 'welcome' => [
    |     'label' => 'Welcome Email',
    |     'view' => 'emails.welcome',
    |     'subject' => 'Welcome to Our Platform',
    |     'data' => [
    |         'name' => 'John Doe',
    |     ],
    | ],
    |
    | Note: Config must be serializable. Do NOT use closures.
    |
    */
    'previews' => [
        // Example:
        // 'password-reset' => \App\MailPreviews\PasswordResetPreview::class,
    ],
];
