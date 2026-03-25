# Laravel Email Preview

A Laravel package to preview and send emails in non-production environments.

## Features
- Preview any configured Mailable in the browser
- Send test emails to any address
- Only enabled in safe environments (local, testing, staging)
- Easily extendable/configurable

## Installation

```bash
composer require shauncurtis/laravel-email-preview --dev
```

Publish the config file (optional):

```bash
php artisan vendor:publish --provider="ShaunCurtis\\EmailPreview\\EmailPreviewServiceProvider" --tag=config
```

## Configuration
Edit `config/email-preview.php` to add your emails:

```php
'emails' => [
    'welcome' => [
        'mailable' => App\Mail\WelcomeMail::class,
        'test_data' => ['user' => 1],
    ],
],
```

## Usage
Visit `/email-preview` in your browser (only in allowed environments).

- Click "Preview" to see the rendered email
- Use the form to send a test email

## Extending
- Add more emails to the config
- Customize test data as needed
- Optionally override the mail driver for previews

## License
MIT
