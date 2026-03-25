<?php

namespace ShaunCurtis\EmailPreview\Tests\Fixtures;

use ShaunCurtis\EmailPreview\Contracts\EmailPreview;
use ShaunCurtis\EmailPreview\Data\PreviewResult;

class TestViewPreview implements EmailPreview
{
    public function label(): string
    {
        return 'Test View Email';
    }

    public function build(): PreviewResult
    {
        return PreviewResult::fromView(
            view: 'email-preview::test-view',
            data: ['name' => 'Test User'],
            subject: 'Test Subject'
        );
    }
}
