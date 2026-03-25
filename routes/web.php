<?php

use Illuminate\Support\Facades\Route;
use ShaunCurtis\EmailPreview\Http\Controllers\EmailPreviewController;

Route::group([
    'prefix' => 'email-preview',
    'middleware' => config('email-preview.middleware', ['web']),
], function () {
    Route::get('/', [EmailPreviewController::class, 'index'])->name('email-preview.index');
    Route::get('/{type}', [EmailPreviewController::class, 'show'])->name('email-preview.show');
    Route::post('/{type}/send', [EmailPreviewController::class, 'send'])->name('email-preview.send');
});
