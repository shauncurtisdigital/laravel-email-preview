<?php

namespace App\MailPreviews;

use ShaunCurtis\EmailPreview\Contracts\EmailPreview;
use ShaunCurtis\EmailPreview\Data\PreviewResult;

/**
 * Example: View-based email preview
 * 
 * Use this approach for simple Blade HTML email templates.
 */
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
                'support_email' => 'support@example.com',
                'reset_url' => url('/reset-password/sample-token?email=john@example.com'),
            ],
            subject: 'Reset Your Password'
        );
    }
}
