<?php

namespace App\MailPreviews;

use App\Mail\WelcomeMail;
use ShaunCurtis\EmailPreview\Contracts\EmailPreview;
use ShaunCurtis\EmailPreview\Data\PreviewResult;

/**
 * Example: Mailable-based email preview
 * 
 * Use this approach for Laravel Mailables, including Markdown mail.
 * This properly renders @component('mail::message') and other mail components.
 */
class WelcomeMailablePreview implements EmailPreview
{
    public function label(): string
    {
        return 'Welcome Email (Mailable)';
    }

    public function build(): PreviewResult
    {
        // Create your Mailable with sample data
        $mailable = new WelcomeMail(
            userName: 'Jane Smith',
            loginUrl: url('/login'),
            verificationUrl: url('/verify-email/sample-token'),
        );

        return PreviewResult::fromMailable($mailable);
    }
}
