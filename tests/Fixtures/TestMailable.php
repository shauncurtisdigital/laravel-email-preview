<?php

namespace ShaunCurtis\EmailPreview\Tests\Fixtures;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
    ) {}

    public function build(): self
    {
        return $this->subject('Test Mailable Subject')
            ->view('email-preview::test-mailable', [
                'userName' => $this->userName,
            ]);
    }
}
