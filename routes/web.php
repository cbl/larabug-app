<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\ExceptionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\ProjectController;

Route::permanentRedirect('terms', 'terms-of-service');
Route::permanentRedirect('privacy', 'privacy-policy');
Route::permanentRedirect('dashboard', 'panel');
Route::permanentRedirect('what-is-larabug', 'features');

Route::redirect('discord', 'https://discord.gg/AWrdVpc');

Route::get('/', [PageController::class, 'home'])->name('home');

Route::group([], function () {
    // Route::get('requirements', [PageController::class, 'requirements'])->name('page.requirements');
    Route::get('features', [PageController::class, 'features'])->name('features');
    Route::get('pricing', [PageController::class, 'pricing'])->name('pricing');
    Route::get('branding', [PageController::class, 'branding'])->name('branding');
    Route::get('larabug-is-free', [PageController::class, 'larabugIsFree'])->name('larabug-is-free');
    // Route::get('what-is-larabug', [PageController::class, 'explanation'])->name('page.explanation');

    Route::get('terms-of-service', [PageController::class, 'terms'])->name('terms');
    Route::get('privacy-policy', [PageController::class, 'policy'])->name('privacy');

    // Route::get('contact', [ContactController::class, 'contact')->name('contact');
    // Route::post('contact', [ContactController::class, 'send')->name('contact.send');

    Route::get('docs', [DocumentationController::class, 'index'])->name('docs.index');
    Route::get('docs/{category}/{item}', [DocumentationController::class, 'show'])->name('docs.show');
});

Route::get('exception/{exception:publish_hash}', [PageController::class, 'exception'])->name('public.exception');

Auth::routes();

Route::get('login/{provider}', [LoginController::class, 'redirectToProvider'])->name('socialite.login');
Route::get('login/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('socialite.callback');

Route::get('scripts/feedback', [FeedbackController::class, 'script'])->name('feedback.script');

Route::middleware('auth')->prefix('panel')->name('panel.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('introduction', [HomeController::class, 'introduction'])->name('introduction');

    Route::resource('projects', ProjectController::class);
    Route::get('projects/{id}/installation', [ProjectController::class, 'installation'])->name('projects.installation');
    Route::get('projects/{id}/feedback-installation', [ProjectController::class, 'feedbackInstallation'])->name('projects.feedback-installation');
    Route::post('projects/{id}/test-webhook', [ProjectController::class, 'testWebhook'])->name('projects.test.webhook');
    Route::post('projects/{id}/remove-image', [ProjectController::class, 'removeImage'])->name('projects.remove.image');

    Route::resource('groups', GroupController::class);

    Route::delete('projects/{id}/exceptions/delete-all', [ExceptionController::class, 'deleteAll'])->name('exceptions.delete-all');
    Route::post('projects/{id}/exceptions/delete-selected', [ExceptionController::class, 'deleteSelected'])->name('exceptions.delete-selected');
    Route::resource('projects/{id}/exceptions', ExceptionController::class);
    Route::post('projects/{id}/exceptions/{exception}/fixed', [ExceptionController::class, 'fixed'])->name('exceptions.fixed');
    Route::post('projects/{id}/exceptions/{exception}/toggle-public', [ExceptionController::class, 'togglePublic'])->name('exceptions.toggle-public');
    Route::post('projects/{id}/exceptions/mark-as', [ExceptionController::class, 'markAs'])->name('exceptions.mark-as');
    Route::post('projects/{id}/exceptions/mark-all-fixed', [ExceptionController::class, 'markAllAsFixed'])->name('exceptions.mark-all-fixed');
    Route::post('projects/{id}/exceptions/mark-all-read', [ExceptionController::class, 'markAllAsRead'])->name('exceptions.mark-all-read');

    Route::get('feedback', [FeedbackController::class, 'index'])
        ->middleware('has.feature:feedback')
        ->name('feedback.index');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::patch('profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
});
