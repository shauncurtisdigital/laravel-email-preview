<?php

namespace ShaunCurtis\EmailPreview\Tests\Fixtures;

use ShaunCurtis\EmailPreview\Contracts\EmailPreview;
use ShaunCurtis\EmailPreview\Data\PreviewResult;

class TestMailablePreview implements EmailPreview
{
    public function label(): string
    {
        return 'Test Mailable Email';
    }

    public function build(): PreviewResult
    {
        $mailable = new TestMailable(userName: 'Test User');
        return PreviewResult::fromMailable($mailable);
    }
}
