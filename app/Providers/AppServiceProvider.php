use Illuminate\Support\Facades\URL; // Assure-toi que cette ligne est bien présente tout en haut du fichier
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message; // Ou le chemin correct vers ton modèle Message

public function boot(): void
{
    // 1. Force le protocole HTTPS en production pour corriger l'alerte de sécurité du formulaire
    if (config('app.env') === 'production' || config('app.env') === 'staging') {
        URL::forceScheme('https');
    }

    // 2. Partage le compte des messages non lus avec toutes les vues (Ton code existant)
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
        }
    });
}