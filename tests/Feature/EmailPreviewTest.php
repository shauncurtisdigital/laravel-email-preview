<?php

namespace ShaunCurtis\EmailPreview\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use ShaunCurtis\EmailPreview\Http\Controllers\EmailPreviewController;
use ShaunCurtis\EmailPreview\Tests\TestCase;

class EmailPreviewTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Config::set('email-preview.enabled', true);
        Config::set('email-preview.environments', ['testing']);
        Config::set('email-preview.test_recipient', 'test@example.com');
        Config::set('email-preview.previews', [
            'test-email' => [
                'label' => 'Test Email',
                'view' => 'emails.test',
                'subject' => 'Test Subject',
                'data' => fn () => ['foo' => 'bar'],
            ],
        ]);
        Route::middleware('web')->group(function () {
            Route::get('/email-preview', [EmailPreviewController::class, 'index'])->name('email-preview.index');
            Route::get('/email-preview/{type}', [EmailPreviewController::class, 'show'])->name('email-preview.show');
            Route::post('/email-preview/{type}/send', [EmailPreviewController::class, 'send'])->name('email-preview.send');
        });
    }

    public function test_index_route_lists_previews()
    {
        $response = $this->get('/email-preview');
        $response->assertStatus(200);
        $response->assertSee('Test Email');
    }

    public function test_show_route_renders_email()
    {
        view()->addNamespace('emails', __DIR__ . '/../../resources/views');
        $response = $this->get('/email-preview/test-email');
        $response->assertStatus(200);
    }

    public function test_send_route_sends_email()
    {
        Mail::fake();
        $response = $this->post('/email-preview/test-email/send');
        $response->assertRedirect('/email-preview');
        $response->assertSessionHas('status', 'Email sent to test@example.com!');
    }
}
