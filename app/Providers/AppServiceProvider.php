<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

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
        // 1. Force le protocole HTTPS en production
        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            URL::forceScheme('https');
        }

        // 2. Partage le compte des messages non lus de maniere securisee
        View::composer('*', function ($view) {
            if (Auth::check()) {
                try {
                    $unreadMessagesCount = Message::where('receiver_id', Auth::id())
                        ->where('is_read', false)
                        ->count();
                    $view->with('unreadMessagesCount', $unreadMessagesCount);
                } catch (\Exception $e) {
                    $view->with('unreadMessagesCount', 0);
                }
            } else {
                $view->with('unreadMessagesCount', 0);
            }
        });
    }
}