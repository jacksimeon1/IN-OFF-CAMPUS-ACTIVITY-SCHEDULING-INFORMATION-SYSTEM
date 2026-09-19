<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPUP Activity Management') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary-green: #059669;
                --primary-green-dark: #047857;
                --sidebar-bg: #065f46;
                --sidebar-hover: #eab308;
                --sidebar-active: #ca8a04;
                --sidebar-text: #ffffff;
                --sidebar-text-muted: #e2e8f0;
            }

            html {
                font-size: 13px !important; 
            }

            /* Sidebar Styles - Based on Audi Design */
            .sidebar {
                background-color: var(--sidebar-bg);
                width: 280px;
                height: 100vh;
                position: fixed;
                left: 0;
                top: 0;
                z-index: 40;
                transition: transform 0.3s ease-in-out;
                overflow-y: auto;
                scrollbar-width: thin;
                scrollbar-color: var(--sidebar-hover) transparent;
            }

            .sidebar::-webkit-scrollbar {
                width: 6px;
            }

            .sidebar::-webkit-scrollbar-track {
                background: transparent;
            }

            .sidebar::-webkit-scrollbar-thumb {
                background-color: var(--sidebar-hover);
                border-radius: 3px;
            }

            .sidebar-hidden {
                transform: translateX(-100%);
            }

            .sidebar-brand {
                padding: 1.5rem;
                border-bottom: 1px solid var(--sidebar-hover);
                color: var(--sidebar-text);
                font-size: 1.25rem;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .sidebar-nav {
                padding: 1rem 0;
            }

            .nav-section {
                margin-bottom: 2rem;
            }

            .nav-section-title {
                color: var(--sidebar-text-muted);
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                padding: 0 1.5rem 0.5rem;
                margin-bottom: 0.5rem;
            }

            /* Admin-specific navigation styling */
            .admin-nav .nav-section-title {
                color: #059669;
                font-weight: 700;
                font-size: 0.7rem;
                border-bottom: 1px solid #e5e7eb;
                padding-bottom: 0.5rem;
                margin-bottom: 0.75rem;
                background: linear-gradient(135deg, #f0fdf4, #dcfce7);
                padding: 0.5rem 1.5rem;
                border-radius: 6px;
            }

            .admin-nav .nav-item {
                margin-bottom: 0.25rem;
                border-radius: 8px;
                transition: all 0.2s ease;
                font-weight: 500;
            }

            .admin-nav .nav-item:hover {
                background-color: var(--sidebar-hover);
                color: white;
                transform: translateX(4px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .admin-nav .nav-item.active {
                background: linear-gradient(135deg, #059669, #047857);
                color: white;
                box-shadow: 0 4px 6px rgba(5, 150, 105, 0.3);
                font-weight: 600;
            }

            .admin-nav .nav-item.active i {
                color: #dcfce7;
            }

            .admin-nav .nav-item i {
                width: 20px;
                text-align: center;
                margin-right: 12px;
                font-size: 0.9rem;
            }

            .nav-item {
                display: block;
                color: var(--sidebar-text);
                text-decoration: none;
                padding: 0.75rem 1.5rem;
                margin: 0 0.75rem;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
                font-weight: 500;
                position: relative;
            }

            .nav-item:hover {
                background-color: var(--sidebar-hover);
                color: white;
                transform: translateX(4px);
            }

            .nav-item.active {
                background-color: var(--sidebar-active);
                color: white;
            }

            .nav-item.active::before {
                content: '';
                position: absolute;
                left: -0.75rem;
                top: 0;
                bottom: 0;
                width: 4px;
                background-color: #10b981;
                border-radius: 0 2px 2px 0;
            }

            .nav-item i {
                width: 1.25rem;
                margin-right: 0.75rem;
                text-align: center;
            }

            /* Main Content Area */
            .main-content {
                margin-left: 280px;
                min-height: 100vh;
                background-color: #f7fafc;
                transition: margin-left 0.3s ease-in-out;
            }

            .main-content.sidebar-collapsed {
                margin-left: 0;
            }

            /* Top Bar */
            .top-bar {
                background: #eab308 !important;
                border-bottom: 1px solid #e2e8f0;
                padding: 1rem 2rem;
                display: flex;
                justify-content: between;
                align-items: center;
                position: sticky;
                top: 0;
                z-index: 30;
            }





            .sidebar-toggle {
                display: none;
                background: none;
                border: none;
                font-size: 1.25rem;
                color: #4a5568;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 0.375rem;
                transition: background-color 0.2s;
            }

            .sidebar-toggle:hover {
                background-color: #f7fafc;
            }

            /* Tablet Responsive */
            @media (max-width: 1280px) and (min-width: 1025px) {
                .sidebar {
                    width: 240px;
                }

                .main-content {
                    margin-left: 240px;
                }

                .nav-item {
                    padding: 0.625rem 1.25rem;
                    font-size: 0.875rem;
                }

                .sidebar-brand {
                    padding: 1.25rem;
                    font-size: 1.125rem;
                }
            }

            /* Mobile Responsive */
            @media (max-width: 1024px) {
                .sidebar {
                    transform: translateX(-100%);
                }

                .sidebar.mobile-open {
                    transform: translateX(0);
                }

                .main-content {
                    margin-left: 0;
                }

                .sidebar-toggle {
                    display: block;
                }

                .sidebar-overlay {
                    position: fixed;
                    inset: 0;
                    background-color: rgba(0, 0, 0, 0.5);
                    z-index: 35;
                    display: none;
                }

                .sidebar-overlay.active {
                    display: block;
                }
            }

            @media (max-width: 640px) {
                .sidebar {
                    width: 100%;
                }

                .top-bar {
                    padding: 1rem;
                }

                .nav-item {
                    padding: 0.875rem 1rem;
                    font-size: 0.875rem;
                }

                .nav-item i {
                    width: 1.5rem;
                    margin-right: 0.875rem;
                }

                .sidebar-brand {
                    padding: 1rem;
                    font-size: 1.125rem;
                }

                .nav-section-title {
                    padding: 0 1rem 0.5rem;
                    font-size: 0.6875rem;
                }
            }

            /* Additional Comprehensive Responsive Scaling */
            @media (max-width: 768px) {
                body { zoom: 0.6; }

                /* Ensure content scrolls horizontally */
                .main-content,
                .overflow-x-auto,
                .table-responsive {
                    overflow-x: auto !important;
                    -webkit-overflow-scrolling: touch;
                }
            }

            @media (max-width: 576px) {
                body { zoom: 0.55; }
            }

            @media (max-width: 480px) {
                body { zoom: 0.5; }
            }

            @media (max-width: 360px) {
                body { zoom: 0.45; }
            }

            @media (max-width: 320px) {
                body { zoom: 0.4; }
            }

            /* Preserve interactive elements */
            .nav-item,
            .sidebar-toggle,
            button, input, select, textarea {
                min-height: 20px;
                min-width: 20px;
            }

            /* Maintain table layouts */
            table {
                min-width: max-content;
            }

            /* Ensure images scale properly */
            img, video, iframe {
                max-width: 100%;
                height: auto;
            }

            /* User Profile in Sidebar */
            .sidebar-user {
                padding: 1rem 1.5rem;
                border-top: 1px solid var(--sidebar-hover);
                margin-top: auto;
            }

            .user-info {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                color: var(--sidebar-text);
                margin-bottom: 0.75rem;
            }

            .user-avatar {
                width: 2.5rem;
                height: 2.5rem;
                background-color: var(--sidebar-active);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
            }

            .user-details h4 {
                font-weight: 600;
                font-size: 0.875rem;
                margin: 0;
            }

            .user-details p {
                font-size: 0.75rem;
                color: var(--sidebar-text-muted);
                margin: 0;
            }

            .test-mode-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                background: linear-gradient(135deg, #f59e0b, #d97706);
                color: white;
                padding: 0.125rem 0.5rem;
                border-radius: 0.75rem;
                font-size: 0.625rem;
                font-weight: 600;
                margin-top: 0.25rem;
                text-transform: uppercase;
                letter-spacing: 0.025em;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
                animation: pulse 2s infinite;
            }

            .test-mode-badge i {
                font-size: 0.625rem;
            }

            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.8;
                }
            }

            .logout-btn {
                width: 100%;
                background: none;
                border: 1px solid var(--sidebar-hover);
                color: var(--sidebar-text);
                padding: 0.5rem;
                border-radius: 0.375rem;
                font-size: 0.875rem;
                cursor: pointer;
                transition: all 0.2s;
            }

            .logout-btn:hover {
                background-color: #dc2626;
                border-color: #dc2626;
                color: white;
            }
        </style>

        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            <!-- Sidebar -->
            <aside id="sidebar" class="sidebar">
                <!-- Brand -->
                <div class="sidebar-brand">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <img src="{{ asset('images/SPUP-final-logo.png') }}" alt="SPUP Logo" class="w-full h-full object-contain">
                    </div>
                    <span>USER PANEL</span>
                </div>

                <!-- Navigation -->
                <nav class="sidebar-nav {{ auth()->user()->isAdmin() ? 'admin-nav' : '' }}">
                    @if(auth()->user()->isStudent())
                        <!-- User Navigation - Always show full dashboard navigation -->
                        <div class="nav-section">
                            <div class="nav-section-title">Dashboard</div>
                            <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') && !request()->routeIs('profile.edit') ? 'active' : '' }}" id="nav-overview">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Overview</span>
                            </a>
                            <a href="{{ route('student.activities') }}" class="nav-item {{ request()->routeIs('student.activities') ? 'active' : '' }}" id="nav-activities">
                                <i class="fas fa-list-alt"></i>
                                <span>My Activities</span>
                            </a>
                            <a href="{{ route('student.create-activity') }}" class="nav-item {{ request()->routeIs('student.create-activity') ? 'active' : '' }}" id="nav-submit">
                                <i class="fas fa-plus-circle"></i>
                                <span>Submit Activity</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">Tools</div>
                            <a href="{{ route('student.profile') }}" class="nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }}" id="nav-profile">
                                <i class="fas fa-user-cog"></i>
                                <span>Profile Settings</span>
                            </a>
                        </div>

                    @elseif(auth()->user()->isAdviser())
                        <!-- Adviser Navigation -->
                        <div class="nav-section">
                            <div class="nav-section-title">Approval Dashboard</div>
                            <a href="{{ route('adviser.dashboard') }}" class="nav-item {{ request()->routeIs('adviser.dashboard') ? 'active' : '' }}" id="nav-adviser-dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('adviser.pending-approvals') }}" class="nav-item {{ request()->routeIs('adviser.pending-approvals') ? 'active' : '' }}" id="nav-adviser-activities">
                                <i class="fas fa-clipboard-list"></i>
                                <span>Pending Approvals</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">Tools</div>
                            <a href="{{ route('my-approvals.index') }}" class="nav-item {{ request()->routeIs('my-approvals.*') ? 'active' : '' }}" id="nav-adviser-my-approvals">
                                <i class="fas fa-check-double"></i>
                                <span>My Approvals</span>
                            </a>
                            <a href="{{ route('adviser.profile') }}" class="nav-item {{ request()->routeIs('adviser.profile') ? 'active' : '' }}" id="nav-adviser-profile">
                                <i class="fas fa-user-cog"></i>
                                <span>Profile Settings</span>
                            </a>
                        </div>

                    @elseif(auth()->user()->isDean())
                        <!-- Dean Navigation -->
                        <div class="nav-section">
                            <div class="nav-section-title">Dean Dashboard</div>
                            <a href="{{ route('dean.dashboard') }}" class="nav-item {{ request()->routeIs('dean.dashboard') ? 'active' : '' }}" id="nav-dean-dashboard">
                                <i class="fas fa-university"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('dean.pending-reviews') }}" class="nav-item {{ request()->routeIs('dean.pending-reviews') ? 'active' : '' }}" id="nav-dean-activities">
                                <i class="fas fa-clipboard-check"></i>
                                <span>Pending Reviews</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">Tools</div>
                            <a href="{{ route('my-approvals.index') }}" class="nav-item {{ request()->routeIs('my-approvals.*') ? 'active' : '' }}" id="nav-dean-my-approvals">
                                <i class="fas fa-check-double"></i>
                                <span>My Approvals</span>
                            </a>
                            <a href="{{ route('dean.profile') }}" class="nav-item {{ request()->routeIs('dean.profile') ? 'active' : '' }}" id="nav-dean-profile">
                                <i class="fas fa-user-cog"></i>
                                <span>Profile Settings</span>
                            </a>
                        </div>

                    @elseif(auth()->user()->isPsgAdviser())
                        <!-- PSG Council Adviser Navigation -->
                        <div class="nav-section">
                            <div class="nav-section-title">PSG Council</div>
                            <a href="{{ route('psg.dashboard') }}" class="nav-item {{ request()->routeIs('psg.dashboard') ? 'active' : '' }}" id="nav-psg-dashboard">
                                <i class="fas fa-users"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('psg.pending-reviews') }}" class="nav-item {{ request()->routeIs('psg.pending-reviews') ? 'active' : '' }}" id="nav-psg-pending">
                                <i class="fas fa-user-check"></i>
                                <span>Pending Reviews</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">Tools</div>
                            <a href="{{ route('my-approvals.index') }}" class="nav-item {{ request()->routeIs('my-approvals.*') ? 'active' : '' }}" id="nav-psg-my-approvals">
                                <i class="fas fa-check-double"></i>
                                <span>My Approvals</span>
                            </a>
                            <a href="{{ route('psg.profile') }}" class="nav-item {{ request()->routeIs('psg.profile') ? 'active' : '' }}" id="nav-psg-profile">
                                <i class="fas fa-user-cog"></i>
                                <span>Profile Settings</span>
                            </a>
                        </div>

                    @elseif(auth()->user()->isDirector())
                        <!-- Director Navigation -->
                        <div class="nav-section">
                            <div class="nav-section-title">Director Panel</div>
                            <a href="{{ route('director.dashboard') }}" class="nav-item {{ request()->routeIs('director.dashboard') ? 'active' : '' }}" id="nav-director-dashboard">
                                <i class="fas fa-shield-alt"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('director.pending-endorsements') }}" class="nav-item {{ request()->routeIs('director.pending-endorsements') ? 'active' : '' }}" id="nav-director-activities">
                                <i class="fas fa-stamp"></i>
                                <span>Pending Endorsements</span>
                            </a>
                            
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">Tools</div>
                            <a href="{{ route('my-approvals.index') }}" class="nav-item {{ request()->routeIs('my-approvals.*') ? 'active' : '' }}" id="nav-director-my-approvals">
                                <i class="fas fa-check-double"></i>
                                <span>My Approvals</span>
                            </a>
                            <a href="{{ route('director.profile') }}" class="nav-item {{ request()->routeIs('director.profile') ? 'active' : '' }}" id="nav-director-profile">
                                <i class="fas fa-user-cog"></i>
                                <span>Profile Settings</span>
                            </a>
                        </div>

                    @elseif(auth()->user()->isVp())
                        <!-- VP Navigation -->
                        <div class="nav-section">
                            <div class="nav-section-title">VP Panel</div>
                            <a href="{{ route('vp.dashboard') }}" class="nav-item {{ request()->routeIs('vp.dashboard') ? 'active' : '' }}" id="nav-vp-dashboard">
                                <i class="fas fa-crown"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('vp.pending-approvals') }}" class="nav-item {{ request()->routeIs('vp.pending-approvals') ? 'active' : '' }}" id="nav-vp-activities">
                                <i class="fas fa-gavel"></i>
                                <span>Final Approvals</span>
                            </a>
                            <!-- Removed: VP All Activities link (route disabled) -->
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">Tools</div>
                            <a href="{{ route('my-approvals.index') }}" class="nav-item {{ request()->routeIs('my-approvals.*') ? 'active' : '' }}" id="nav-vp-my-approvals">
                                <i class="fas fa-check-double"></i>
                                <span>My Approvals</span>
                            </a>
                            <a href="{{ route('vp.profile') }}" class="nav-item {{ request()->routeIs('vp.profile') ? 'active' : '' }}" id="nav-vp-profile">
                                <i class="fas fa-user-cog"></i>
                                <span>Profile Settings</span>
                            </a>
                        </div>

                    @elseif(auth()->user()->isOsa())
                        <!-- OSA Navigation -->
                        <div class="nav-section">
                            <div class="nav-section-title">OSA Dashboard</div>
                            <a href="{{ route('osa.dashboard') }}" class="nav-item {{ request()->routeIs('osa.dashboard') ? 'active' : '' }}" id="nav-osa-dashboard">
                                <i class="fas fa-building"></i>
                                <span>Dashboard</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">Activities</div>
                            <a href="{{ route('osa.activities') }}" class="nav-item {{ request()->routeIs('osa.activities') ? 'active' : '' }}" id="nav-osa-activities">
                                <i class="fas fa-list"></i>
                                <span>All Activities</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">Tools</div>
                            <a href="{{ route('osa.profile') }}" class="nav-item {{ request()->routeIs('osa.profile') ? 'active' : '' }}" id="nav-osa-profile">
                                <i class="fas fa-user-cog"></i>
                                <span>Profile Settings</span>
                            </a>
                        </div>

                    @elseif(auth()->user()->isAdmin())
                        <!-- Admin Navigation - Unique Admin Structure -->
                        <div class="nav-section">
                            <div class="nav-section-title">CONTROL PANEL</div>
                            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ !request()->get('tab') || request()->get('tab') === 'dashboard' ? 'active' : '' }}" id="nav-admin-dashboard">
                                <i class="fas fa-chart-line"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('admin.dashboard') }}?tab=activities" class="nav-item {{ request()->get('tab') === 'activities' ? 'active' : '' }}" id="nav-admin-activities">
                                <i class="fas fa-tasks"></i>
                                <span>Activity Management</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">USER MANAGEMENT</div>
                            <a href="{{ route('admin.dashboard') }}?tab=accounts" class="nav-item {{ request()->get('tab') === 'accounts' ? 'active' : '' }}" id="nav-admin-accounts">
                                <i class="fas fa-users"></i>
                                <span>User Accounts</span>
                            </a>
                            <a href="{{ route('admin.users.create') }}" class="nav-item {{ request()->routeIs('admin.users.create') ? 'active' : '' }}" id="nav-admin-create-user">
                                <i class="fas fa-user-plus"></i>
                                <span>Add New User</span>
                            </a>
                            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}" id="nav-admin-manage-users">
                                <i class="fas fa-user-edit"></i>
                                <span>Manage Users</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">ANALYTICS & REPORTS</div>
                            <a href="{{ route('admin.dashboard') }}?tab=analytics" class="nav-item {{ request()->get('tab') === 'analytics' ? 'active' : '' }}" id="nav-admin-analytics">
                                <i class="fas fa-chart-pie"></i>
                                <span>Analytics</span>
                            </a>
                            <a href="{{ route('admin.dashboard') }}?tab=reports" class="nav-item {{ request()->get('tab') === 'reports' ? 'active' : '' }}" id="nav-admin-reports">
                                <i class="fas fa-file-chart-line"></i>
                                <span>Reports</span>
                            </a>
                            <a href="{{ route('admin.dashboard') }}?tab=calendar" class="nav-item {{ request()->get('tab') === 'calendar' ? 'active' : '' }}" id="nav-admin-calendar">
                                <i class="fas fa-calendar-check"></i>
                                <span>Activity Calendar</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-section-title">SYSTEM</div>
                            <a href="{{ route('admin.dashboard') }}?tab=settings" class="nav-item {{ request()->get('tab') === 'settings' ? 'active' : '' }}" id="nav-admin-settings">
                                <i class="fas fa-cogs"></i>
                                <span>System Settings</span>
                            </a>
                            <a href="{{ route('admin.profile') }}" class="nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}" id="nav-admin-profile">
                                <i class="fas fa-user-shield"></i>
                                <span>Admin Profile</span>
                            </a>
                        </div>
                    @endif
                </nav>

                <!-- User Profile -->
                <div class="sidebar-user">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <h4>{{ auth()->user()->name }}</h4>
                            <p>{{ auth()->user()->getRoleDisplayName() }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Sidebar Overlay for Mobile -->
            <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

            <!-- Main Content -->
            <div id="main-content" class="main-content">
                <!-- Top Bar -->
                <div class="top-bar">
                    <div class="flex items-center justify-between w-full">
                        <div class="flex items-center gap-4">
                            <button class="sidebar-toggle" onclick="toggleSidebar()">
                                <i class="fas fa-bars"></i>
                            </button>
                            <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="flex items-center gap-4">

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
                                   class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all duration-200">
                                    <i class="fas fa-bell"></i>
                                    <span x-show="unreadCount > 0"
                                          x-text="unreadCount"
                                          class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                    </span>
                                </a>
                            </div>

                            <!-- User Status -->
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    <i class="fas fa-circle text-green-500 mr-1"></i>
                                    {{ auth()->user()->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <main class="p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const mainContent = document.getElementById('main-content');
                
                if (window.innerWidth <= 1024) {
                    // Mobile behavior
                    sidebar.classList.toggle('mobile-open');
                    overlay.classList.toggle('active');
                } else {
                    // Desktop behavior
                    sidebar.classList.toggle('sidebar-hidden');
                    mainContent.classList.toggle('sidebar-collapsed');
                }
            }

            function setActiveNavItem(activeId) {
                // Remove active class from all nav items
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });

                // Add active class to the clicked item
                const activeItem = document.getElementById(activeId);
                if (activeItem) {
                    activeItem.classList.add('active');
                }
            }

            // Make setActiveNavItem globally available
            window.setActiveNavItem = setActiveNavItem;



            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 1024) {
                    const sidebar = document.getElementById('sidebar');
                    const sidebarToggle = document.querySelector('.sidebar-toggle');
                    
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('mobile-open');
                        document.getElementById('sidebar-overlay').classList.remove('active');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const mainContent = document.getElementById('main-content');
                
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('active');
                    if (!sidebar.classList.contains('sidebar-hidden')) {
                        mainContent.classList.remove('sidebar-collapsed');
                    }
                }
            });
        </script>

        @stack('scripts')
    </body>
</html>
