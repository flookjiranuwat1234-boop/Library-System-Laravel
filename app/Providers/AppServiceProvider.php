<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function (ViewContract $view): void {
            $user = auth()->user();

            $view->with(
                'unreadAdminNotificationsCount',
                $user?->role === 'admin' ? $user->unreadNotifications()->count() : 0,
            );
        });
    }
}
