<?php

namespace ShaunCurtis\EmailPreview\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use ShaunCurtis\EmailPreview\Http\Controllers\EmailPreviewController;
use ShaunCurtis\EmailPreview\Tests\Fixtures\TestViewPreview;
use ShaunCurtis\EmailPreview\Tests\Fixtures\TestMailablePreview;
use ShaunCurtis\EmailPreview\Tests\TestCase;

class EmailPreviewTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Config::set('email-preview.enabled', true);
        Config::set('email-preview.environments', ['testing']);
        
        // Register routes for testing
        Route::middleware('web')->group(function () {
            Route::get('/email-preview', [EmailPreviewController::class, 'index'])->name('email-preview.index');
            Route::get('/email-preview/{preview}', [EmailPreviewController::class, 'show'])->name('email-preview.show');
            Route::post('/email-preview/{preview}/send', [EmailPreviewController::class, 'send'])->name('email-preview.send');
        });
    }

    public function test_index_route_lists_class_based_previews()
    {
        Config::set('email-preview.default_to', 'test@example.com');
        Config::set('email-preview.previews', [
            'test-view' => TestViewPreview::class,
            'test-mailable' => TestMailablePreview::class,
        ]);

        $response = $this->get('/email-preview');
        
        $response->assertStatus(200);
        $response->assertSee('Test View Email');
        $response->assertSee('Test Mailable Email');
    }

    public function test_index_route_lists_legacy_array_previews()
    {
        Config::set('email-preview.default_to', 'test@example.com');
        Config::set('email-preview.previews', [
            'legacy-email' => [
                'label' => 'Legacy Email',
                'view' => 'email-preview::test-view',
                'subject' => 'Legacy Subject',
                'data' => ['name' => 'Test'],
            ],
        ]);

        $response = $this->get('/email-preview');
        
        $response->assertStatus(200);
        $response->assertSee('Legacy Email');
    }

    public function test_show_route_renders_view_based_preview()
    {
        Config::set('email-preview.previews', [
            'test-view' => TestViewPreview::class,
        ]);

        $response = $this->get('/email-preview/test-view');
        
        $response->assertStatus(200);
        $response->assertSee('Test View Email');
        $response->assertSee('Hello Test User!');
    }

    public function test_show_route_renders_mailable_based_preview()
    {
        Config::set('email-preview.previews', [
            'test-mailable' => TestMailablePreview::class,
        ]);

        $response = $this->get('/email-preview/test-mailable');
        
        $response->assertStatus(200);
        $response->assertSee('Test Mailable Email');
        $response->assertSee('Hello Test User!');
    }

    public function test_show_route_renders_legacy_array_preview()
    {
        Config::set('email-preview.previews', [
            'legacy' => [
                'label' => 'Legacy Email',
                'view' => 'email-preview::test-view',
                'subject' => 'Legacy Subject',
                'data' => ['name' => 'Legacy User'],
            ],
        ]);

        $response = $this->get('/email-preview/legacy');
        
        $response->assertStatus(200);
        $response->assertSee('Hello Legacy User!');
    }

    public function test_send_route_sends_view_based_email()
    {
        Mail::fake();
        
        Config::set('email-preview.default_to', 'test@example.com');
        Config::set('email-preview.previews', [
            'test-view' => TestViewPreview::class,
        ]);

        $response = $this->post('/email-preview/test-view/send');
        
        $response->assertRedirect('/email-preview');
        $response->assertSessionHas('status', 'Email sent to test@example.com!');
        
        Mail::assertSent(\ShaunCurtis\EmailPreview\Mail\GenericPreviewMailable::class);
    }

    public function test_send_route_sends_mailable_based_email()
    {
        Mail::fake();
        
        Config::set('email-preview.default_to', 'test@example.com');
        Config::set('email-preview.previews', [
            'test-mailable' => TestMailablePreview::class,
        ]);

        $response = $this->post('/email-preview/test-mailable/send');
        
        $response->assertRedirect('/email-preview');
        $response->assertSessionHas('status', 'Email sent to test@example.com!');
        
        Mail::assertSent(\ShaunCurtis\EmailPreview\Tests\Fixtures\TestMailable::class);
    }

    public function test_send_route_requires_test_recipient()
    {
        Config::set('email-preview.default_to', null);
        Config::set('email-preview.previews', [
            'test-view' => TestViewPreview::class,
        ]);

        $response = $this->post('/email-preview/test-view/send');
        
        $response->assertRedirect('/email-preview');
        $response->assertSessionHasErrors(['to']);
    }

    public function test_show_route_returns_404_for_missing_preview()
    {
        Config::set('email-preview.previews', []);

        $response = $this->get('/email-preview/non-existent');
        
        $response->assertStatus(404);
    }

    public function test_preview_class_must_implement_interface()
    {
        Config::set('email-preview.previews', [
            'invalid' => \stdClass::class,
        ]);

        $response = $this->get('/email-preview/invalid');
        
        $response->assertStatus(500);
    }
}

