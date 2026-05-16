<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm relative z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- LOGO & IDENTITÉ SYSTEM --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md">
                        <x-application-logo class="block h-9 w-auto fill-current text-indigo-600" />
                        <span class="font-black text-xl tracking-tighter text-gray-900 uppercase">Sentinel</span>
                    </a>
                </div>

                {{-- NAVIGATION PRINCIPALE (DESKTOP) --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Liens d'administration Sentinel --}}
                    @if(Auth::user()->is_admin)
                        <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                            <span class="text-red-600 font-extrabold tracking-wide uppercase text-xs">Admin Node</span>
                        </x-nav-link>
                        
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                            <span class="text-red-600/90 font-medium text-xs uppercase tracking-wider">Directory</span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            {{-- BLOC DROITE : DROPDOWN UTILISATEUR (DESKTOP) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-semibold rounded-xl text-gray-600 bg-white hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:border-gray-300 transition ease-in-out duration-150 cursor-pointer shadow-sm">
                            <div class="flex items-center gap-2">
                                {{-- Indicateur visuel d'état Admin --}}
                                @if(Auth::user()->is_admin)
                                    <span class="h-2 w-2 bg-red-500 rounded-full animate-pulse" aria-hidden="true"></span>
                                @endif
                                <div class="text-gray-800">{{ Auth::user()->name }}</div>
                            </div>

                            <div class="ms-2 text-gray-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 font-bold hover:bg-red-50">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- DÉCLENCHEUR MENU BURGER (MOBILE) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" 
                        :aria-expanded="open.toString()"
                        class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-800 transition duration-150 ease-in-out">
                    <span class="sr-only">Ouvrir le menu principal</span>
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- INTERFACE DE NAVIGATION MOBILE DISCORDANCE --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-50 border-t border-gray-200 animate-fadeIn">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->is_admin)
                <div class="border-t border-gray-200/60 my-1"></div>
                <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')" class="text-red-600 font-bold bg-red-50/40">
                    <span class="flex items-center gap-2">
                        <span class="h-1.5 w-1.5 bg-red-500 rounded-full"></span>
                        Admin Node
                    </span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')" class="text-red-600/90 pl-7">
                    Directory
                </x-responsive-nav-link>
            @endif
        </div>

        {{-- PIED DE MENU MOBILE : COMPTE DE L'OPÉRATEUR --}}
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-black text-base text-gray-900 tracking-tight">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500 italic max-w-[220px] truncate">{{ Auth::user()->email }}</div>
                </div>
                @if(Auth::user()->is_admin)
                    <span class="text-[10px] bg-red-100 text-red-800 px-2.5 py-1 rounded-md font-black tracking-wider uppercase border border-red-200">Admin</span>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 font-bold bg-red-50/20">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>