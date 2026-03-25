<?php

namespace ShaunCurtis\EmailPreview\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use ShaunCurtis\EmailPreview\Contracts\EmailPreview;
use ShaunCurtis\EmailPreview\Data\PreviewResult;
use ShaunCurtis\EmailPreview\Mail\GenericPreviewMailable;

class EmailPreviewController extends Controller
{
    public function index()
    {
        $previews = config('email-preview.previews', []);
        
        // Build preview list with labels
        $previewList = [];
        foreach ($previews as $key => $className) {
            if (is_string($className)) {
                // New class-based format
                $previewList[$key] = [
                    'label' => $this->resolvePreviewLabel($key, $className),
                    'key' => $key,
                ];
            } elseif (is_array($className)) {
                // Legacy array format (backward compatibility)
                $previewList[$key] = [
                    'label' => $className['label'] ?? $key,
                    'key' => $key,
                ];
            }
        }
        
        return view('email-preview::index', ['previews' => $previewList]);
    }

    public function show(string $preview)
    {
        $result = $this->buildPreview($preview);
        
        if ($result->isView()) {
            return view($result->view, $result->data);
        }
        
        if ($result->isMailable()) {
            $html = $result->mailable->render();
            return response($html);
        }
        
        abort(500, 'Unsupported preview type: ' . $result->type);
    }

    public function send(Request $request, string $preview)
    {
        $result = $this->buildPreview($preview);
        
        $to = config('email-preview.default_to');
        if (!$to) {
            return redirect()->route('email-preview.index')
                ->withErrors(['to' => 'No test recipient configured. Set TEST_EMAIL_ADDRESS in your .env file.']);
        }
        
        if ($result->isView()) {
            $mailable = new GenericPreviewMailable(
                subjectLine: $result->subject ?? 'Email Preview',
                viewName: $result->view,
                viewData: $result->data,
            );
            Mail::to($to)->send($mailable);
        } elseif ($result->isMailable()) {
            Mail::to($to)->send($result->mailable);
        } else {
            abort(500, 'Unsupported preview type: ' . $result->type);
        }
        
        return redirect()->route('email-preview.index')
            ->with('status', "Email sent to {$to}!");
    }

    /**
     * Build the preview result for a given preview key.
     */
    protected function buildPreview(string $key): PreviewResult
    {
        $previews = config('email-preview.previews', []);
        
        if (!isset($previews[$key])) {
            abort(404, "Preview '{$key}' not found.");
        }
        
        $config = $previews[$key];
        
        // Handle class-based preview (new format)
        if (is_string($config)) {
            return $this->buildFromClass($key, $config);
        }
        
        // Handle legacy array format (backward compatibility)
        if (is_array($config)) {
            return $this->buildFromLegacyArray($config);
        }
        
        abort(500, "Invalid preview configuration for '{$key}'.");
    }

    /**
     * Build preview from a preview class.
     */
    protected function buildFromClass(string $key, string $className): PreviewResult
    {
        if (!class_exists($className)) {
            abort(500, "Preview class '{$className}' does not exist.");
        }
        
        $instance = app($className);
        
        if (!$instance instanceof EmailPreview) {
            abort(500, "Preview class '{$className}' must implement " . EmailPreview::class);
        }
        
        return $instance->build();
    }

    /**
     * Build preview from legacy array format.
     */
    protected function buildFromLegacyArray(array $config): PreviewResult
    {
        // Don't allow closures in data
        $data = $config['data'] ?? [];
        if (is_callable($data)) {
            abort(500, 'Closures are not allowed in preview data. Use preview classes instead.');
        }
        
        return PreviewResult::fromView(
            view: $config['view'],
            data: $data,
            subject: $config['subject'] ?? null,
        );
    }

    /**
     * Resolve the label for a preview class.
     */
    protected function resolvePreviewLabel(string $key, string $className): string
    {
        try {
            if (class_exists($className)) {
                $instance = app($className);
                if ($instance instanceof EmailPreview) {
                    return $instance->label();
                }
            }
        } catch (\Exception $e) {
            // Fall back to key if class can't be instantiated
        }
        
        return $key;
    }
}
