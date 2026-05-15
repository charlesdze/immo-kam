<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider; // <--- C'EST CETTE LIGNE QUI MANQUAIT
use Illuminate\Support\Facades\View;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

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
        // On partage le compte des messages non lus avec toutes les vues
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // On essaie de compter les messages
                // Si la colonne 'is_read' n'existe pas encore, utilise juste count()
                try {
                    $unreadMessagesCount = Message::where('receiver_id', Auth::id())
                        ->where('is_read', false)
                        ->count();
                    $view->with('unreadMessagesCount', $unreadMessagesCount);
                } catch (\Exception $e) {
                    // Si la colonne is_read n'existe pas, on ne bloque pas le site
                    $view->with('unreadMessagesCount', 0);
                }
            }
        });
    }
}