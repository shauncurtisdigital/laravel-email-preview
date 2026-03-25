# Laravel Email Preview

A Laravel package to preview and send email templates in non-production environments.

## Features
- Preview any Blade email template in the browser
- Send test emails to configurable recipients
- Only enabled in safe environments (local, testing, staging)
- Configurable route prefix and middleware
- Support for dynamic test data via closures

## Installation

```bash
composer require shauncurtis/laravel-email-preview --dev
```

Publish the config file (optional):

```bash
php artisan vendor:publish --provider="ShaunCurtis\\EmailPreview\\EmailPreviewServiceProvider" --tag=config
```

## Configuration

Edit `config/email-preview.php` to add your email templates:

```php
'previews' => [
    'welcome' => [
        'label' => 'Welcome Email',
        'view' => 'emails.welcome',
        'subject' => 'Welcome to Our Platform',
        'data' => fn () => [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ],
    ],
    'password-reset' => [
        'label' => 'Password Reset',
        'view' => 'emails.password-reset',
        'subject' => 'Reset Your Password',
        'data' => fn () => [
            'name' => fake()->name(),
            'reset_url' => url('/reset-password/sample-token'),
        ],
    ],
],
```

### Configuration Options

- `enabled` - Enable/disable the package (default: true)
- `environments` - Allowed environments (default: ['local', 'testing', 'staging'])
- `route_prefix` - URL prefix for routes (default: 'email-preview')
- `middleware` - Middleware to apply to routes (default: ['web'])
- `test_recipient` - Default email address for sending tests (uses env: TEST_EMAIL_ADDRESS)
- `previews` - Array of email templates to preview

### Environment Variables

Add to your `.env` file:

```env
MAIL_PREVIEW_ENABLED=true
TEST_EMAIL_ADDRESS=test@example.com
```

## Usage

Visit `/email-preview` in your browser (only in allowed environments).

- Click "Preview" to see the rendered email in your browser
- Click "Send" to send the email to the configured test recipient

## Security

This package is designed for non-production environments only:
- Routes are only registered when `enabled` is true AND the current environment is in the allowed list
- Consider adding authentication middleware in staging environments:

```php
// config/email-preview.php
'middleware' => ['web', 'auth'],
```

## License
MIT
