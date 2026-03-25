<?php

namespace ShaunCurtis\EmailPreview\Data;

use Illuminate\Mail\Mailable;

class PreviewResult
{
    public function __construct(
        public string $type,
        public ?string $view = null,
        public array $data = [],
        public ?string $subject = null,
        public ?Mailable $mailable = null,
    ) {}

    /**
     * Create a preview result from a Blade view.
     */
    public static function fromView(string $view, array $data = [], ?string $subject = null): self
    {
        return new self(
            type: 'view',
            view: $view,
            data: $data,
            subject: $subject,
        );
    }

    /**
     * Create a preview result from a Mailable instance.
     */
    public static function fromMailable(Mailable $mailable): self
    {
        return new self(
            type: 'mailable',
            mailable: $mailable,
        );
    }

    /**
     * Check if this is a view-based preview.
     */
    public function isView(): bool
    {
        return $this->type === 'view';
    }

    /**
     * Check if this is a mailable-based preview.
     */
    public function isMailable(): bool
    {
        return $this->type === 'mailable';
    }
}
