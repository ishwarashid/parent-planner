<style>
    /* Theme color variables */
    :root {
        --theme-bg-navy: #000080;
        --theme-text-light: #E5E7EB;
        /* A light gray for inactive text */
        --theme-text-hover: #FFFFFF;
        --theme-accent-turquoise: #40E0D0;
        --theme-accent-turquoise-rgb: 64, 224, 208;
    }

    /* Main Nav Styling */
    .theme-nav {
        background-color: var(--theme-bg-navy);
        border-bottom-color: #1f2937;
        /* A darker border for separation */
    }

    /* Custom Application Logo color */
    .theme-nav .fill-current {
        /* color: var(--theme-accent-turquoise) !important; */
    }

    /* Base Nav Link Styling */
    .theme-nav-link {
        color: var(--theme-text-light);
        border-bottom-width: 2px;
        border-color: transparent;
    }

    .theme-nav-link:hover {
        color: var(--theme-text-hover);
        border-bottom-color: var(--theme-text-light);
        border-bottom-width: 4px;
    }

    /* Active Nav Link Styling - This style is applied by the component's :active prop */
    .theme-nav-link.active {
        color: var(--theme-text-hover);
        border-bottom-color: var(--theme-accent-turquoise);
        border-bottom-width: 4px;
        font-size: 18px
    }

    /* Dropdown Trigger Button */
    .theme-dropdown-button {
        color: var(--theme-text-light);
    }

    .theme-dropdown-button:hover {
        color: var(--theme-text-hover);
    }

    /* Hamburger Menu Button */
    .theme-hamburger {
        color: var(--theme-text-light);
    }

    .theme-hamburger:hover {
        color: var(--theme-text-hover);
        background-color: rgba(255, 255, 255, 0.1);
    }

    /* Responsive/Mobile Menu Styling */
    .theme-responsive-bg {
        background-color: var(--theme-bg-navy);
        border-top-color: #1f2937;
    }

    .theme-responsive-link {
        color: var(--theme-text-light);
        border-left-width: 2px;
        border-color: transparent;
    }

    .theme-responsive-link.active {
        color: var(--theme-text-hover);
        background-color: rgba(var(--theme-accent-turquoise-rgb), 0.1);
        border-color: var(--theme-accent-turquoise);
    }

    .theme-responsive-user-info .name {
        color: var(--theme-text-hover);
    }

    .theme-responsive-user-info .email {
        color: var(--theme-text-light);
    }
</style>

<nav x-data="{ open: false }" class="theme-nav">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="theme-nav-link">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @can('view-children')
                        <x-nav-link :href="route('children.index')" :active="request()->routeIs('children.*')" class="theme-nav-link">
                            {{ __('Children') }}
                        </x-nav-link>
                    @endcan

                    @can('view-visitations')
                        <x-nav-link :href="route('visitations.index')" :active="request()->routeIs('visitations.*')" class="theme-nav-link">
                            {{ __('Visitations') }}
                        </x-nav-link>
                    @endcan

                    @can('view-events')
                        <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')" class="theme-nav-link">
                            {{ __('Calendar') }}
                        </x-nav-link>
                    @endcan

                    @can('view-expenses')
                        <x-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')" class="theme-nav-link">
                            {{ __('Expenses') }}
                        </x-nav-link>
                    @endcan

                    @can('view-documents')
                        <x-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')" class="theme-nav-link">
                            {{ __('Documents') }}
                        </x-nav-link>
                    @endcan
                    <x-nav-link :href="route('professionals.public.index')" :active="request()->routeIs('professionals.*')" class="theme-nav-link">
                        {{ __('Professionals') }}
                    </x-nav-link>
                    @can('view-invitations')
                        <x-nav-link :href="route('invitations.index')" :active="request()->routeIs('invitations.*')" class="theme-nav-link">
                            {{ __('Invitations') }}
                        </x-nav-link>
                    @endcan

                    @can('view-reports')
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="theme-nav-link">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-transparent focus:outline-none transition ease-in-out duration-150 theme-dropdown-button">
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

                        @if (Auth::user()->isAccountOwner())
                            <x-dropdown-link :href="route('billing')">
                                {{ __('Billing') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
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

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition duration-150 ease-in-out theme-hamburger">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden theme-responsive-bg">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="theme-responsive-link">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @can('view-children')
                <x-responsive-nav-link :href="route('children.index')" :active="request()->routeIs('children.*')" class="theme-responsive-link">
                    {{ __('Children') }}
                </x-responsive-nav-link>
            @endcan
            @can('view-visitations')
                <x-responsive-nav-link :href="route('visitations.index')" :active="request()->routeIs('visitations.*')" class="theme-responsive-link">
                    {{ __('Visitations') }}
                </x-responsive-nav-link>
            @endcan
            @can('view-events')
                <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')" class="theme-responsive-link">
                    {{ __('Calendar') }}
                </x-responsive-nav-link>
            @endcan
            @can('view-expenses')
                <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')" class="theme-responsive-link">
                    {{ __('Expenses') }}
                </x-responsive-nav-link>
            @endcan
            @can('view-documents')
                <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')" class="theme-responsive-link">
                    {{ __('Documents') }}
                </x-responsive-nav-link>
            @endcan
            <x-responsive-nav-link :href="route('professionals.public.index')" :active="request()->routeIs('professionals.public.index')" class="theme-responsive-link">
                {{ __('Professionals') }}
            </x-responsive-nav-link>
            @can('view-invitations')
                <x-responsive-nav-link :href="route('invitations.index')" :active="request()->routeIs('invitations.*')" class="theme-responsive-link">
                    {{ __('Invitations') }}
                </x-responsive-nav-link>
            @endcan
            @can('view-reports')
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="theme-responsive-link">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t theme-responsive-bg">
            <div class="px-4 theme-responsive-user-info">
                <div class="font-medium text-base name">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm email">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="theme-responsive-link">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Auth::user()->isAccountOwner())
                    <x-responsive-nav-link :href="route('billing')" class="theme-responsive-link">
                        {{ __('Billing') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();" class="theme-responsive-link">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
