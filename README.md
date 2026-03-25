# Laravel Email Preview

A Laravel package for previewing and testing emails in development environments. Supports both **Blade view** emails and **Laravel Mailable** emails.

## Features

- 📧 Preview emails in your browser before sending
- 🔒 Safe by default - only works in configured environments
- 🎨 Supports both Blade views and Laravel Mailables
- 📤 Send test emails to configured recipients
- 🔧 Serializable config (compatible with Laravel config caching)
- 🎯 Simple class-based preview definitions

## Installation

Add the following lines to `composer.json`:

```bash
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/shauncurtisdigital/laravel-email-preview.git"
        }
    ],
    "require-dev": {
        "shauncurtis/laravel-email-preview": "dev-main"
    },
}
```

Then run:

```bash
composer update shauncurtis/laravel-email-preview
```

Publish the config file:

```bash
php artisan vendor:publish --provider="ShaunCurtis\\EmailPreview\\EmailPreviewServiceProvider" --tag=config
```

## Configuration

Update `config/email-preview.php`:

```php
return [
    'enabled' => env('EMAIL_PREVIEW_ENABLED', true),
    
    'environments' => ['local', 'testing', 'staging'],
    
    'route_prefix' => 'email-preview',
    
    'middleware' => ['web'],
    
    'default_to' => env('TEST_EMAIL_ADDRESS', 'test@example.com'),
    
    'previews' => [
        'password-reset' => \App\MailPreviews\PasswordResetPreview::class,
        'welcome' => \App\MailPreviews\WelcomePreview::class,
    ],
];
```

Set your test email address in `.env`:

```env
EMAIL_PREVIEW_ENABLED=true
TEST_EMAIL_ADDRESS=your-email@example.com
```

## Creating Preview Classes

### 1. View-Based Preview (Plain Blade Templates)

For simple Blade HTML email templates:

```php
<?php

namespace App\MailPreviews;

use ShaunCurtis\EmailPreview\Contracts\EmailPreview;
use ShaunCurtis\EmailPreview\Data\PreviewResult;

class PasswordResetPreview implements EmailPreview
{
    public function label(): string
    {
        return 'Password Reset Email';
    }

    public function build(): PreviewResult
    {
        return PreviewResult::fromView(
            view: 'emails.password-reset',
            data: [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'reset_url' => url('/reset-password/sample-token'),
            ],
            subject: 'Reset Your Password'
        );
    }
}
```

### 2. Mailable-Based Preview (Laravel Mailables)

For projects using Laravel Mailables and Markdown mail:

```php
<?php

namespace App\MailPreviews;

use App\Mail\WelcomeMail;
use ShaunCurtis\EmailPreview\Contracts\EmailPreview;
use ShaunCurtis\EmailPreview\Data\PreviewResult;

class WelcomePreview implements EmailPreview
{
    public function label(): string
    {
        return 'Welcome Email';
    }

    public function build(): PreviewResult
    {
        $mailable = new WelcomeMail(
            userName: 'Jane Smith',
            loginUrl: url('/login'),
        );

        return PreviewResult::fromMailable($mailable);
    }
}
```

## Usage

Once configured, visit:

```
http://your-app.test/email-preview
```

You'll see:
- A list of all configured email previews
- **Preview** button - opens the email in a new tab
- **Send** button - sends the email to your test address

## Routes

The package registers these routes:

- `GET /email-preview` - List all previews
- `GET /email-preview/{preview}` - Preview specific email
- `POST /email-preview/{preview}/send` - Send test email

## Security

The package is safe by default:

✅ Only enabled in configured environments  
✅ Routes not registered in production  
✅ Test emails only sent to configured address  
✅ No config caching issues (serializable config)

For additional security in staging, add authentication:

```php
// config/email-preview.php
'middleware' => ['web', 'auth'],
```

## Legacy Array Format (Backward Compatible)

For simple cases, you can still use array configuration:

```php
'previews' => [
    'welcome' => [
        'label' => 'Welcome Email',
        'view' => 'emails.welcome',
        'subject' => 'Welcome!',
        'data' => [
            'name' => 'John Doe',
        ],
    ],
],
```

⚠️ **Note:** Closures are NOT supported in config (breaks config caching). Use preview classes for dynamic data.

## Troubleshooting

### 404 Error on Routes

If you get a 404 when visiting `/email-preview`:

1. Ensure the package is in an allowed environment (check `APP_ENV` in `.env`)
2. Verify `EMAIL_PREVIEW_ENABLED=true` in your `.env`
3. Clear your config cache: `php artisan config:clear`
4. Check that you've added at least one preview

### Config Cache Error

The new class-based approach is fully compatible with `php artisan config:cache`. Legacy array format (without closures) is also compatible.

If you see serialization errors, ensure you're not using closures in your config file.

## Requirements

- PHP 8.1+
- Laravel 10.x or 11.x

## License

MIT
