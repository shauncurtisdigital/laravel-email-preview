<?php

namespace ShaunCurtis\EmailPreview\Contracts;

use ShaunCurtis\EmailPreview\Data\PreviewResult;

interface EmailPreview
{
    /**
     * Get the display label for this email preview.
     */
    public function label(): string;

    /**
     * Build the preview result (view or mailable).
     */
    public function build(): PreviewResult;
}
