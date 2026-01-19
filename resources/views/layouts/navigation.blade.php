<style>
.nav-link {
    @apply px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 flex items-center;
    @apply text-gray-700 hover:text-green-600 hover:bg-green-50;
    border: 1px solid transparent;
}
.nav-link.active {
    @apply bg-green-100 text-green-700 shadow-sm border-green-200;
}
.nav-link:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.admin-nav {
    background: linear-gradient(135deg, #059669, #047857);
    border-bottom: 3px solid #065f46;
}

.user-nav {
    background: linear-gradient(135deg, #0f766e, #0d9488);
    border-bottom: 3px solid #134e4a;
}

.nav-brand {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1.5rem;
    letter-spacing: -0.025em;
}

.nav-brand-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 1.25rem;
    letter-spacing: -0.025em;
}

.nav-brand-subtitle {
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    font-size: 0.875rem;
    letter-spacing: 0.025em;
}

.nav-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1rem;
}

.nav-section {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-divider {
    height: 24px;
    width: 1px;
    background: rgba(255, 255, 255, 0.3);
    margin: 0 1rem;
}

/* Custom Button Styles */
.button-link {
    text-decoration: none;
    display: inline-block;
}

.button {
    position: relative;
    width: 150px;
    height: 40px;
    cursor: pointer;
    display: flex;
    align-items: center;
    border: 1px solid #34974d;
    background-color: #3aa856;
}

.button, .button__icon, .button__text {
    transition: all 0.3s;
}

.button .button__text {
    transform: translateX(30px);
    color: #fff;
    font-weight: 600;
}

.button .button__icon {
    position: absolute;
    transform: translateX(109px);
    height: 100%;
    width: 39px;
    background-color: #34974d;
    display: flex;
    align-items: center;
    justify-content: center;
}

.button .svg {
    width: 30px;
    stroke: #fff;
}

.button:hover {
    background: #34974d;
}

.button:hover .button__text {
    color: transparent;
}

.button:hover .button__icon {
    width: 148px;
    transform: translateX(0);
}

.button:active .button__icon {
    background-color: #2e8644;
}

.button:active {
    border: 1px solid #2e8644;
}

/* Cool Box Boundaries for User Navigation */
.user-nav-link {
    position: relative;
    overflow: hidden;
    border: 2px solid #e5e7eb;
    background-color: #ffffff;
    padding: 12px 20px !important;
    margin: 0 4px;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.user-nav-link:hover {
    background-color: #f0fdf4 !important;
    color: #166534 !important;
    border-color: #059669 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(5, 150, 105, 0.2), 0 4px 8px rgba(5, 150, 105, 0.1);
}

.user-nav-link.active {
    background-color: #dcfce7 !important;
    color: #14532d !important;
    border-color: #047857 !important;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
}

.user-nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 10px;
}

.user-nav-link:hover::before {
    opacity: 1;
}

.user-nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, #047857, #059669);
    transition: all 0.3s ease;
    transform: translateX(-50%);
    border-radius: 2px;
}

.user-nav-link:hover::after {
    width: 80%;
}

/* Mobile responsive styles - ultra compact to fit all elements */
@media (max-width: 640px) {
    /* Navigation container */
    nav {
        padding: 4px 0 !important;
    }

    /* Main navigation bar height */
    .flex.justify-between.h-20 {
        height: 50px !important;
    }

    /* User navigation buttons - compact but keep PC design */
    .user-nav-link {
        /* Compact padding but maintain button shape */
        padding: 4px 8px !important;
        margin: 0 1px;
        /* Small but readable font */
        font-size: 0.6rem !important;
        /* Ensure buttons don't shrink */
        flex-shrink: 0;
        white-space: nowrap;
        /* Keep rounded corners like PC */
        border-radius: 6px !important;
        /* Keep border like PC */
        border-width: 2px !important;
        /* Compact line height */
        line-height: 1.2;
        /* Minimum width to fit text */
        min-width: auto;
        /* Keep same styling as PC */
        font-weight: 600 !important;
    }

    .user-nav-link i {
        /* Icon spacing like PC but smaller */
        margin-right: 4px !important;
        font-size: 0.6rem;
    }

    .user-nav-link span {
        /* Keep text visible and readable */
        font-size: 0.6rem !important;
    }

    /* Logo and brand - smaller */
    .nav-brand {
        font-size: 0.75rem !important;
    }

    /* Navigation container - minimal padding */
    .max-w-7xl.mx-auto.px-4 {
        padding-left: 4px !important;
        padding-right: 4px !important;
    }

    /* User navigation container - compact but readable spacing */
    .flex.items-center.ml-6.space-x-2 {
        margin-left: 4px !important;
        gap: 2px !important;
    }

    /* Make all navigation elements more compact */
    .flex.items-center {
        gap: 2px !important;
    }

    /* Hide user dropdown text on mobile */
    .hidden.sm\\:flex.sm\\:items-center.sm\\:ml-6 {
        display: flex !important;
    }

    /* Make user dropdown smaller */
    .inline-flex.items-center {
        font-size: 0.75rem !important;
        padding: 2px 4px !important;
    }
}

/* Comprehensive Navigation Responsive System */

/* Base navigation setup */
nav {
    min-width: 320px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.nav-container {
    min-width: max-content;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Navigation responsive scaling */
@media (max-width: 1400px) {
    nav { font-size: 0.95rem; }
}

@media (max-width: 1200px) {
    nav { font-size: 0.9rem; }
}

@media (max-width: 992px) {
    nav { font-size: 0.85rem; }
}

@media (max-width: 768px) {
    nav { font-size: 0.8rem; }

    /* Ensure navigation scrolls horizontally on smaller screens */
    .nav-container {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        padding: 0 0.5rem;
    }

    /* Maintain navigation item spacing */
    .nav-link {
        white-space: nowrap;
        flex-shrink: 0;
    }
}

@media (max-width: 576px) {
    nav { font-size: 0.75rem; }

    .nav-container {
        padding: 0 0.25rem;
    }
}

@media (max-width: 480px) {
    nav { font-size: 0.7rem; }
}

@media (max-width: 360px) {
    nav { font-size: 0.65rem; }
}

@media (max-width: 320px) {
    nav { font-size: 0.6rem; }
}

/* Preserve navigation functionality */
.nav-link, .nav-button {
    min-height: 20px;
    min-width: 20px;
    flex-shrink: 0;
}

/* Ensure dropdown menus work on all screen sizes */
.dropdown-menu {
    min-width: 150px;
    max-width: 90vw;
}
</style>

<nav x-data="{ open: false }" class="bg-white shadow-lg border-b-4 border-black sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="@if(auth()->user()->isStudent()){{ route('student.dashboard') }}@elseif(auth()->user()->isAdviser()){{ route('adviser.dashboard') }}@elseif(auth()->user()->isOsa()){{ route('osa.dashboard') }}@else{{ route('dashboard') }}@endif" class="flex items-center space-x-3 group">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105 overflow-hidden">
                            <img src="{{ asset('images/SPUP-final-logo.png') }}" alt="SPUP Logo" class="w-full h-full object-contain">
                        </div>
                        <div class="hidden sm:block">
                            <h1 class="nav-brand-title text-gray-800 group-hover:text-green-600 transition-colors">SPUP Activity</h1>
                            <p class="nav-brand-subtitle text-gray-600">In/Off Campus Activity Scheduling Information System</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:ml-10 sm:flex">
                    @if(auth()->user()->isStudent())
                        <!-- User navigation moved to dashboard tabs -->

                    @elseif(auth()->user()->isAdviser())
                        <a href="{{ route('adviser.dashboard') }}" class="nav-link {{ request()->routeIs('adviser.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                        </a>
                    @elseif(auth()->user()->isOsa())
                        <a href="{{ route('osa.dashboard') }}" class="nav-link {{ request()->routeIs('osa.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                        </a>
                    @endif

                    @if(auth()->user()->isAdviser())
                        <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list mr-2"></i> {{ __('Review Activities') }}
                        </a>
                    @endif

                    @if(auth()->user()->isOsa())
                        <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                            <i class="fas fa-check-circle mr-2"></i> {{ __('Approve Activities') }}
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog mr-2"></i> {{ __('Admin Panel') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- User Dashboard Navigation removed - using sidebar navigation only -->

            <!-- Notifications and Settings -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                <!-- Notifications -->
                <div class="relative" x-data="{ notifications: [], unreadCount: {{ auth()->user()->notifications()->unread()->count() }} }" x-init="
                    // Auto-refresh notification count every 30 seconds
                    setInterval(() => {
                        fetch('{{ route('notifications.unread-count') }}')
                            .then(response => response.json())
                            .then(data => {
                                unreadCount = data.unread_count;
                            })
                            .catch(error => console.error('Error updating notification count:', error));
                    }, 30000);
                ">
                    <a href="{{ route('notifications.index') }}"
                       class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200 relative">
                        <i class="fas fa-bell text-lg"></i>
                        <span x-show="unreadCount > 0"
                              x-text="unreadCount"
                              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                        </span>
                    </a>
                </div>

                <!-- User Role Badge -->
                <span class="bg-gradient-to-r from-green-500 to-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                    {{ auth()->user()->role === 'student' ? 'User' : ucfirst(auth()->user()->role) }}
                </span>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-green-50 hover:text-green-600 hover:border-green-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div>{{ Auth::user()->name }}</div>
                            </div>

                            <div class="ml-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fas fa-user mr-2"></i> {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>


        </div>
    </div>

    <!-- Responsive Navigation Menu - Hidden since we show buttons directly -->
    <div class="hidden">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @if(auth()->user()->isStudent())
                <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('student.dashboard') ? 'text-green-700 bg-green-100' : 'text-gray-700 hover:text-green-600 hover:bg-green-50' }} transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                </a>

                <!-- Mobile navigation uses sidebar only -->
                @if(!request()->routeIs('student.dashboard') && !request()->routeIs('dashboard') && !str_contains(request()->url(), 'dashboard'))
                    <a href="{{ route('activities.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('activities.*') && !request()->routeIs('activities.create') ? 'text-green-700 bg-green-100' : 'text-gray-700 hover:text-green-600 hover:bg-green-50' }} transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i> {{ __('My Activities') }}
                    </a>
                    <a href="{{ route('activities.create') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('activities.create') ? 'text-white bg-green-600' : 'text-white bg-green-500 hover:bg-green-600' }} transition-colors">
                        <i class="fas fa-plus mr-2"></i> {{ __('Submit Activity') }}
                    </a>
                @endif
            @elseif(auth()->user()->isAdviser())
                <a href="{{ route('adviser.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('adviser.dashboard') ? 'text-green-700 bg-green-100' : 'text-gray-700 hover:text-green-600 hover:bg-green-50' }} transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                </a>
            @elseif(auth()->user()->isOsa())
                <a href="{{ route('osa.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('osa.dashboard') ? 'text-green-700 bg-green-100' : 'text-gray-700 hover:text-green-600 hover:bg-green-50' }} transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'text-green-700 bg-green-100' : 'text-gray-700 hover:text-green-600 hover:bg-green-50' }} transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                </a>
            @endif

            @if(auth()->user()->isAdviser())
                <a href="{{ route('activities.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('activities.*') ? 'text-green-700 bg-green-100' : 'text-gray-700 hover:text-green-600 hover:bg-green-50' }} transition-colors">
                    <i class="fas fa-clipboard-list mr-2"></i> {{ __('Review Activities') }}
                </a>
            @endif

            @if(auth()->user()->isOsa())
                <a href="{{ route('activities.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('activities.*') ? 'text-green-700 bg-green-100' : 'text-gray-700 hover:text-green-600 hover:bg-green-50' }} transition-colors">
                    <i class="fas fa-check-circle mr-2"></i> {{ __('Approve Activities') }}
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.*') ? 'text-green-700 bg-green-100' : 'text-gray-700 hover:text-green-600 hover:bg-green-50' }} transition-colors">
                    <i class="fas fa-users-cog mr-2"></i> {{ __('Admin Panel') }}
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 py-2">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium mt-1">
                            {{ auth()->user()->role === 'student' ? 'User' : ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-4">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors">
                    <i class="fas fa-user mr-2"></i> {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>


