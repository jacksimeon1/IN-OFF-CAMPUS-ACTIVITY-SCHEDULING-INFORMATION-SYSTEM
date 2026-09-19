<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Admin Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    @if(request()->routeIs('admin.reports.*'))
    <style>
        /* Hide sidebar/nav sections on reports pages */
        .admin-sidebar,
        .admin-nav-section,
        .admin-nav-section-title,
        .admin-toggle-btn,
        #adminSidebar,
        #adminSidebarOverlay,
        #adminToggleBtn { display: none !important; }

        /* Ensure main content uses full width */
        .admin-main, .admin-content { margin-left: 0 !important; width: 100% !important; max-width: 100% !important; }
    </style>
    @endif

    <style>
        :root {
            --admin-primary: #059669;
            --admin-primary-dark: #047857;
            --admin-secondary: #10b981;
            --admin-bg: #f8fafc;
            --admin-sidebar-bg: #065f46;
            --admin-sidebar-text: #ffffff;
            --admin-sidebar-active: #eab308;
            --admin-sidebar-hover: #eab308;
        }

        html {
            font-size: 13px !important; 
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--admin-bg);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            border: none;
        }

        .admin-layout {
            background-color: var(--admin-bg);
            min-height: 100vh;
            border: none;
        }

        .admin-top-nav {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white;
            box-shadow: none;
            border-bottom: none !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            outline: none !important;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0.15rem 1.5rem;
            height: 50px;
        }

        .admin-nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 100%;
            gap: 2rem;
            height: 50px;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .admin-brand-text {
            display: flex;
            flex-direction: column;
        }

        .admin-brand-title {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .admin-brand-subtitle {
            font-size: 0.625rem;
            color: #dcfce7;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-nav-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 0 1rem;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            color: white;
            text-decoration: none;
            transition: all 0.2s ease;
            border-radius: 6px;
            font-weight: 500;
            white-space: nowrap;
            border: 1px solid transparent;
            font-size: 0.8rem;
            position: relative;
            overflow: hidden;
        }

        .admin-nav-item:hover {
            background-color: var(--admin-sidebar-hover);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .admin-nav-item.active {
            background-color: var(--admin-sidebar-active);
            color: white;
            font-weight: 600;
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 2px rgba(234, 179, 8, 0.3);
        }

        .admin-nav-item i {
            width: 16px;
            text-align: center;
            margin-right: 6px;
            font-size: 0.875rem;
        }

        .admin-nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }

        .admin-user-profile {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            background: #fbbf24 !important;
            padding: 0.125rem 0.5rem;
            border-radius: 0.25rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        /* Force remove any backgrounds from user profile elements */
        .admin-user-profile *,
        .user-avatar,
        .user-info,
        .user-name,
        .user-role {
            background: transparent !important;
            background-color: transparent !important;
        }

        .user-avatar {
            width: 24px;
            height: 24px;
            background: #f59e0b !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.7rem;
            color: white;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            background: transparent;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1;
            color: #1f2937 !important;
        }

        .user-role {
            font-size: 0.625rem;
            color: #374151 !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-decoration: none;
            border: none;
            box-shadow: none;
            outline: none;
        }

        .admin-logout {
            margin: 0;
        }

        .logout-btn {
            color: white;
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--admin-sidebar-active);
        }

        .admin-main {
            padding-top: 25px;
            padding-left: 0;
            transition: padding-left 0.3s ease;
            width: 100%;
            background-color: var(--admin-bg);
            border-top: none !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            outline: none !important;
        }

        .admin-content {
            background: var(--admin-bg);
            margin-top: 0;
            padding-top: 0;
            padding-bottom: 0;
            height: auto;
            min-height: auto;
            border-top: none !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            outline: none !important;
        }

        /* Additional rule to remove any top border from content */
        .admin-content::before,
        .admin-content::after {
            content: none !important;
            border: none !important;
            outline: none !important;
        }

        /* Global rule to remove any unwanted borders or lines that create the black line effect */
        .admin-content > *:first-child,
        .admin-content > div:first-child,
        .admin-content > section:first-child {
            border-top: none !important;
            border-bottom: none !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* Remove any top borders from content containers */
        .admin-content .bg-white,
        .admin-content .bg-gray-50,
        .admin-content .bg-gray-100 {
            border-top: none !important;
            box-shadow: none !important;
        }

        /* Remove any borders from the first element in content */
        .admin-content .max-w-4xl:first-child,
        .admin-content .max-w-7xl:first-child,
        .admin-content .max-w-6xl:first-child {
            border-top: none !important;
            box-shadow: none !important;
        }

        /* Additional rules to remove any potential black lines */
        .admin-content .rounded-2xl,
        .admin-content .rounded-lg,
        .admin-content .rounded-xl {
            border-top: none !important;
            box-shadow: none !important;
        }

        /* Remove any borders from gradient backgrounds */
        .admin-content .bg-gradient-to-r {
            border-top: none !important;
            box-shadow: none !important;
        }

        /* Force remove any top borders from all content elements */
        .admin-content * {
            border-top: none !important;
        }

        @media (max-width: 1024px) {
            .admin-top-nav {
                padding: 0.1rem 0.75rem;
                height: 45px;
            }

            .admin-nav-container {
                gap: 0.75rem;
            }

            .user-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .admin-nav-links {
                padding: 0;
                gap: 0.25rem;
            }

            .admin-nav-item span {
                display: none;
            }

            .admin-nav-item {
                padding: 0.375rem;
                min-width: 36px;
                justify-content: center;
            }

            .admin-nav-item i {
                margin-right: 0;
                font-size: 1rem;
            }

            .admin-brand-text {
                display: none;
            }

            .admin-content {
                padding: 0.5rem 1rem 0.25rem 1rem;
            }
        }

        @media (max-width: 640px) {
            .admin-nav-container {
                gap: 0.5rem;
            }

            .admin-nav-actions {
                gap: 0.5rem;
            }

            .admin-nav-links {
                gap: 0.125rem;
            }

            .admin-nav-item {
                padding: 0.375rem;
                min-width: 36px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <!-- Top Navigation Bar -->
        <nav class="admin-top-nav">
            <div class="admin-nav-container">
                <!-- Brand Section -->
                <div class="admin-brand">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <img src="{{ asset('images/SPUP-final-logo.png') }}" alt="SPUP Logo" class="w-full h-full object-contain">
                    </div>
                    <div class="admin-brand-text">
                        <div class="admin-brand-title">SPUP Activity</div>
                        <div class="admin-brand-subtitle">Admin Panel</div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="admin-nav-links">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') && (!request()->get('tab') || request()->get('tab') === 'dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}?tab=activities" class="admin-nav-item {{ request()->routeIs('admin.dashboard') && request()->get('tab') === 'activities' ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Activities</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}?tab=calendar" class="admin-nav-item {{ request()->routeIs('admin.dashboard') && request()->get('tab') === 'calendar' ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Calendar</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}?tab=accounts" class="admin-nav-item {{ request()->routeIs('admin.dashboard') && request()->get('tab') === 'accounts' ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Accounts</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}?tab=analytics" class="admin-nav-item {{ request()->routeIs('admin.dashboard') && request()->get('tab') === 'analytics' ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Analytics</span>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="admin-nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i>
                        <span>Profile</span>
                    </a>
                </div>

                <!-- Right Side Actions -->
                <div class="admin-nav-actions">
                    <!-- User Profile -->
                    <div class="admin-user-profile">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="admin-logout">
                        @csrf
                        <button type="submit" class="logout-btn" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('js/docx-viewer.js') }}"></script>
    @stack('scripts')
</body>
</html>