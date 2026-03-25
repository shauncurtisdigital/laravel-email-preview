<?php

namespace ShaunCurtis\EmailPreview;

use Illuminate\Support\ServiceProvider;

class EmailPreviewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/email-preview.php', 'email-preview');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/email-preview.php' => config_path('email-preview.php'),
            ], 'config');
        }

        $enabled = in_array($this->app->environment(), config('email-preview.enabled_environments', []));
        if ($enabled) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'email-preview');
        }
    }
}
