<?php

namespace ShaunCurtis\EmailPreview\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmailPreviewController extends Controller
{

    public function index()
    {
        // List available previews from config
        $previews = config('email-preview.previews', []);
        return view('email-preview::index', compact('previews'));
    }

    public function show($type)
    {
        // Show preview for a specific email template
        $previews = config('email-preview.previews', []);
        if (!isset($previews[$type])) {
            abort(404);
        }
        $preview = $previews[$type];
        $data = is_callable($preview['data']) ? call_user_func($preview['data']) : ($preview['data'] ?? []);
        return view($preview['view'], $data);
    }

    public function send(Request $request, $type)
    {
        $previews = config('email-preview.previews', []);
        if (!isset($previews[$type])) {
            abort(404);
        }
        $preview = $previews[$type];
        $data = is_callable($preview['data']) ? call_user_func($preview['data']) : ($preview['data'] ?? []);
        
        // Only send to the configured test recipient for safety
        $to = config('email-preview.test_recipient');
        if (!$to) {
            return redirect()->route('email-preview.index')->withErrors(['to' => 'No test recipient configured. Set TEST_EMAIL_ADDRESS in your .env file.']);
        }
        
        \Mail::send($preview['view'], $data, function ($message) use ($to, $preview) {
            $message->to($to)
                ->subject($preview['subject'] ?? 'Email Preview');
        });
        
        return redirect()->route('email-preview.index')->with('status', "Email sent to {$to}!");
    }
}
