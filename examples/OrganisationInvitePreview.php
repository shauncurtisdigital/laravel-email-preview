<?php

namespace App\MailPreviews;

use App\Mail\OrganisationInviteMail;
use ShaunCurtis\EmailPreview\Contracts\EmailPreview;
use ShaunCurtis\EmailPreview\Data\PreviewResult;

/**
 * Example: Mailable with dynamic data
 * 
 * Shows how to use models or services to build preview data.
 */
class OrganisationInvitePreview implements EmailPreview
{
    public function label(): string
    {
        return 'Organisation Invite';
    }

    public function build(): PreviewResult
    {
        // You can use models, services, or any PHP code here
        $mailable = new OrganisationInviteMail(
            organisationName: 'Acme Corp',
            inviterName: 'Bob Johnson',
            invitationUrl: url('/invite/sample-token'),
            expirationDays: 7,
        );

        return PreviewResult::fromMailable($mailable);
    }
}
