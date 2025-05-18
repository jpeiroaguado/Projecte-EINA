<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left: Logo and Navigation Links -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @if (auth()->user()->rol === 'professor')
                        <a href="{{ route('configuracio.index') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @else
                        <a href="{{ route('alumne.chat') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @endif
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if (auth()->user()->rol === 'professor')
                        <x-nav-link :href="route('configuracio.index')" :active="request()->routeIs('configuracio.index')">
                            🬝 Panell del professor
                        </x-nav-link>
                        <x-nav-link :href="route('configuracio.edit', 0)" :active="request()->routeIs('configuracio.edit')">
                            ⚙️ Configuració contextos
                        </x-nav-link>
                    @elseif (auth()->user()->rol === 'alumne')
                        <x-nav-link :href="route('alumne.chat')" :active="request()->routeIs('alumne.chat')">
                            💬 Tornar al xat
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right: Dark Mode Toggle and User Dropdown -->
            <div class="flex items-center gap-3 ms-auto">
                <button onclick="toggleDarkMode()" id="theme-toggle"
                    class="px-2 py-1 text-sm rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                    <span id="theme-icon">🌞</span>
                </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if (auth()->user()->rol === 'professor')
                <x-responsive-nav-link :href="route('configuracio.index')" :active="request()->routeIs('configuracio.index')">
                    🬝 Panell del professor
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('configuracio.edit', 0)" :active="request()->routeIs('configuracio.edit')">
                    ⚙️ Configuració contextos
                </x-responsive-nav-link>
            @elseif (auth()->user()->rol === 'alumne')
                <x-responsive-nav-link :href="route('alumne.chat')" :active="request()->routeIs('alumne.chat')">
                    💬 Tornar al xat
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
