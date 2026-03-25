# Laravel Email Preview

A Laravel package to preview and send email templates in non-production environments.

## Features
- Preview any Blade email template in the browser
- Send test emails to configurable recipients
- Only enabled in safe environments (local, testing, staging)
- Configurable route prefix and middleware
- Support for dynamic test data via closures

## Installation

Add the following lines to `composer.json`

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

Then run

```bash
composer update shauncurtis/laravel-email-preview
```

Publish the config file (optional):

```bash
php artisan vendor:publish --provider="ShaunCurtis\\EmailPreview\\EmailPreviewServiceProvider" --tag=config
```

## Configuration

Edit `config/email-preview.php` to add your email templates.

### Basic Example (Static Data)

```php
'previews' => [
    'welcome' => [
        'label' => 'Welcome Email',
        'view' => 'emails.welcome',
        'subject' => 'Welcome to Our Platform',
        'data' => [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ],
    ],
    'password-reset' => [
        'label' => 'Password Reset',
        'view' => 'emails.password-reset',
        'subject' => 'Reset Your Password',
        'data' => [
            'name' => 'Jane Smith',
            'reset_url' => 'https://example.com/reset-password/sample-token',
        ],
    ],
],
```

### Dynamic Example (Using Closures)

**Important**: Closures cannot be cached. If you use closures in your config, do not run `php artisan config:cache` or `php artisan optimize`.

```php
'previews' => [
    'welcome' => [
        'label' => 'Welcome Email',
        'view' => 'emails.welcome',
        'subject' => 'Welcome to Our Platform',
        'data' => fn () => [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
        ],
    ],
],
```

### Production-Ready Approach

For applications that use config caching (staging/production), register previews in a service provider instead:

```php
// app/Providers/AppServiceProvider.php
public function boot()
{
    if (app()->environment(['local', 'staging'])) {
        config([
            'email-preview.previews' => [
                'welcome' => [
                    'label' => 'Welcome Email',
                    'view' => 'emails.welcome',
                    'subject' => 'Welcome!',
                    'data' => fn () => [
                        'name' => \App\Models\User::first()?->name ?? 'Test User',
                    ],
                ],
            ],
        ]);
    }
}
```

### Configuration Options

- `enabled` - Enable/disable the package (default: true, via `MAIL_PREVIEW_ENABLED`)
- `environments` - Allowed environments (default: `['local', 'testing', 'staging']`)
- `route_prefix` - URL prefix for routes (default: `'email-preview'`)
- `middleware` - Middleware to apply to routes (default: `['web']`)
- `test_recipient` - Default email address for sending tests (via `TEST_EMAIL_ADDRESS`)
- `previews` - Array of email templates to preview

## Usage

Visit `/email-preview` in your browser (only in allowed environments).

- Click "Preview" to see the rendered email in your browser
- Click "Send" to send the email to the configured test recipient

## Troubleshooting

### 404 Error on Routes

If you get a 404 when visiting `/email-preview`:

1. Ensure the package is in an allowed environment (check `APP_ENV` in `.env`)
2. Verify `MAIL_PREVIEW_ENABLED=true` in your `.env`
3. Clear your config cache: `php artisan config:clear`
4. Check that you've published the config and added at least one preview

### Config Cache Error

If you see "value is non-serializable" when running `php artisan config:cache`:

- You're using closures in your config file
- Either:
  - Use static arrays instead of closures, OR
  - Move preview definitions to a service provider (see "Production-Ready Approach" above), OR
  - Don't cache config in local/staging environments

## Security

This package is designed for non-production environments only:
- Routes are only registered when `enabled` is true AND the current environment is in the allowed list
- Emails can only be sent to the configured `test_recipient` (not arbitrary addresses)
- Consider adding authentication middleware in staging environments:

```php
// config/email-preview.php
'middleware' => ['web', 'auth'],
```

## License
MIT
