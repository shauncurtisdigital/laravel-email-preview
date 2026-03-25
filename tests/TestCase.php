<?php

namespace ShaunCurtis\EmailPreview\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    // You can add package-specific setup here if needed
    protected function setUp(): void
    {
        parent::setUp();
        $this->app['view']->addNamespace('email-preview', dirname(__DIR__) . '/resources/views');
        // Add test email views path
        $this->app['view']->addLocation(dirname(__DIR__) . '/resources/views');
    }
    protected function getPackageProviders($app)
    {
        return [
            \ShaunCurtis\EmailPreview\EmailPreviewServiceProvider::class,
        ];
    }
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
    protected function getPackageRootPath(): string
    {
        return dirname(__DIR__);
    }
}
