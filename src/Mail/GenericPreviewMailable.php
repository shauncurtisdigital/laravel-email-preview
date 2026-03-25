<?php

namespace ShaunCurtis\EmailPreview\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericPreviewMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected string $subjectLine,
        protected string $viewName,
        protected array $viewData = [],
    ) {}

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject($this->subjectLine)
            ->view($this->viewName, $this->viewData);
    }
}
