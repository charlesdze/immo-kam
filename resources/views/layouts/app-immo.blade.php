<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Immo-Kam | Excellence Immobilière')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* --- DESIGN DU HEADER --- */
        .custom-header {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item {
            position: relative;
            padding: 0.5rem 0;
            color: #475569;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: color 0.3s ease;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #2563eb;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-item:hover { color: #2563eb; }
        .nav-item:hover::before { width: 100%; }

        .btn-publish {
            background: #0f172a;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-publish:hover {
            background: #2563eb;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.3);
        }

        .logo-box {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .footer-dark { background-color: #020617; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 antialiased flex flex-col min-h-screen">

    {{-- HEADER --}}
    <header class="custom-header sticky top-0 z-50">
        <div class="container mx-auto px-6 h-20 flex items-center justify-between">
            
            {{-- LOGO SECTION --}}
            <a href="{{ route('listings.index') }}" class="flex items-center gap-3 group">
                <div class="logo-box w-11 h-11 rounded-2xl flex items-center justify-center text-white font-black text-xl transition-transform group-hover:rotate-12">
                    IK
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black tracking-tighter text-slate-900 leading-none">
                        IMMO<span class="text-blue-600">KAM</span>
                    </span>
                    <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-[0.3em] mt-1">Immobilier 2.0</span>
                </div>
            </a>
            
            {{-- NAV LINKS CENTER (Uniquement Explorer) --}}
            <div class="hidden lg:flex items-center gap-10">
                <a href="{{ route('listings.index') }}" class="nav-item">Explorer</a>
            </div>

            {{-- USER ACTIONS --}}
            <div class="flex items-center gap-5">
                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 p-1 pr-3 rounded-full bg-slate-100 hover:bg-white border border-transparent hover:border-slate-200 transition-all">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-md">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-xs font-bold text-slate-700">{{ Auth::user()->name }}</span>
                        </button>
                        {{-- Dropdown --}}
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-4 w-48 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-xs font-bold text-slate-600 hover:bg-blue-50">Mon Compte</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-50">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition uppercase tracking-widest">Connexion</a>
                @endauth

                <a href="{{ route('listings.create') }}" class="btn-publish">
                    Publier une offre
                </a>
            </div>
        </div>
    </header>

    {{-- MAIN --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer-dark text-slate-400 pt-20 pb-10">
        <div class="container mx-auto px-6 text-center md:text-left">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div>
                    <p class="text-xl font-black text-white mb-6 tracking-tighter">IMMO<span class="text-blue-500">KAM</span></p>
                    <p class="text-sm leading-relaxed">Le futur de l'immobilier au Cameroun commence ici. Trouvez, achetez ou vendez en toute confiance.</p>
                </div>
                <div>
                    <h4 class="text-white text-xs font-bold uppercase tracking-widest mb-6">Liens Utiles</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-blue-400">Confidentialité</a></li>
                        <li><a href="#" class="hover:text-blue-400">Termes & Conditions</a></li>
                        <li><a href="#" class="hover:text-blue-400">Aide & Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-xs font-bold uppercase tracking-widest mb-6">Villes</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-blue-400">Douala</a></li>
                        <li><a href="#" class="hover:text-blue-400">Yaoundé</a></li>
                        <li><a href="#" class="hover:text-blue-400">Kribi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-xs font-bold uppercase tracking-widest mb-6">Suivez-nous</h4>
                    <div class="flex gap-4 justify-center md:justify-start">
                        <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center hover:bg-blue-600 cursor-pointer transition">FB</div>
                        <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center hover:bg-blue-600 cursor-pointer transition">IG</div>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-900 pt-10 text-[10px] font-bold uppercase tracking-[0.4em] text-slate-600">
                &copy; 2026 Immo-Kam Node. Excellence in Engineering.
            </div>
        </div>
    </footer>

</body>
</html>