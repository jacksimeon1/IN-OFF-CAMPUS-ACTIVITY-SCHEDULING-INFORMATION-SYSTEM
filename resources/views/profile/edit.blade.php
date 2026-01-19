<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile Settings - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Enhanced Statistics Cards with Animations */
        .animated-stat-card {
            position: relative;
            opacity: 0;
            transform: translateY(30px) scale(0.95);
            animation: slideInUp 0.8s ease-out forwards;
            animation-delay: var(--delay);
            perspective: 1000px;
        }

        .animated-stat-card[data-delay="0"] { --delay: 0s; }
        .animated-stat-card[data-delay="100"] { --delay: 0.1s; }
        .animated-stat-card[data-delay="200"] { --delay: 0.2s; }
        .animated-stat-card[data-delay="300"] { --delay: 0.3s; }

        @keyframes slideInUp {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            50% {
                opacity: 0.7;
                transform: translateY(-5px) scale(1.02);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .card-inner {
            position: relative;
            background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
            border-radius: 20px;
            padding: 24px;
            height: 140px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow:
                0 10px 30px rgba(5, 150, 105, 0.3),
                0 5px 15px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .animated-stat-card:hover .card-inner {
            transform: translateY(-8px) rotateX(5deg);
            box-shadow:
                0 20px 40px rgba(5, 150, 105, 0.4),
                0 15px 25px rgba(5, 150, 105, 0.3),
                0 10px 15px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%);
            opacity: 0;
            transition: all 0.6s ease;
            animation: rotateGlow 4s linear infinite;
        }

        .animated-stat-card:hover .card-glow {
            opacity: 1;
            animation-duration: 2s;
        }

        @keyframes rotateGlow {
            0% { transform: rotate(0deg) scale(0.8); }
            50% { transform: rotate(180deg) scale(1.2); }
            100% { transform: rotate(360deg) scale(0.8); }
        }

        .card-content {
            position: relative;
            z-index: 2;
            height: 100%;
        }

        .stat-info {
            color: white;
            flex: 1;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .animated-stat-card:hover .stat-icon-wrapper {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            font-size: 20px;
            color: white;
            transition: all 0.3s ease;
        }

        .animated-stat-card:hover .stat-icon {
            color: #d1fae5;
            transform: scale(1.1);
        }

        .stat-title {
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 4px;
            transition: all 0.3s ease;
        }

        .animated-stat-card:hover .stat-title {
            color: #d1fae5;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: white;
            line-height: 1;
            margin-bottom: 4px;
            transition: all 0.3s ease;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .animated-stat-card:hover .stat-number {
            color: #d1fae5;
            transform: scale(1.05);
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        }

        .stat-subtitle {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .animated-stat-card:hover .stat-subtitle {
            color: rgba(209, 250, 229, 0.9);
        }

        .stat-visual {
            position: relative;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pulse-ring {
            position: absolute;
            width: 40px;
            height: 40px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        .pulse-ring-2 {
            position: absolute;
            width: 60px;
            height: 60px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite 0.5s;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.5;
            }
            100% {
                transform: scale(0.8);
                opacity: 1;
            }
        }

        .animated-stat-card:hover .pulse-ring {
            animation-duration: 1s;
            border-color: rgba(209, 250, 229, 0.6);
        }

        .animated-stat-card:hover .pulse-ring-2 {
            animation-duration: 1s;
            border-color: rgba(209, 250, 229, 0.4);
        }

        /* Floating animation for cards */
        .animated-stat-card {
            animation: slideInUp 0.8s ease-out forwards, float 6s ease-in-out infinite;
            animation-delay: var(--delay), calc(var(--delay) + 1s);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .profile-card {
            background-color: #059669 !important;
            background: #059669 !important;
            transition: all 0.3s ease;
        }
        .profile-card:hover {
            background-color: #059669 !important;
            background: #059669 !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3), 0 10px 10px -5px rgba(5, 150, 105, 0.2);
        }
        .profile-card .icon-container {
            background-color: rgba(255, 255, 255, 0.2) !important;
            background: rgba(255, 255, 255, 0.2) !important;
        }
        .profile-card .icon-white {
            color: #ffffff !important;
        }
        .profile-card .text-white {
            color: #ffffff !important;
        }


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
    </style>

        /* Hide any sidebar elements that might appear */
        .sidebar, .sidebar-overlay, .hamburger-menu, .mobile-menu-toggle {
            display: none !important;
        }
        
        /* Ensure full width layout */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--admin-bg) !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden;
        }

        /* Top Navigation Bar */
        .admin-top-nav {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        /* Brand Section */
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

        /* Navigation Links */
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
            margin-right: 0.5rem;
            font-size: 0.875rem;
        }

        /* Right Side Actions */
        .admin-nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }

        .admin-user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--admin-sidebar-active);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
            color: white;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1;
        }

        .user-role {
            font-size: 0.625rem;
            color: #dcfce7;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-decoration: none !important;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--admin-sidebar-active);
        }

        /* Main Content */
        .admin-main {
            background: var(--admin-bg) !important;
            margin-top: 70px !important;
            padding-top: 15px !important;
        }
    </style>
</head>
<body>
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
                <a href="/admin/dashboard" class="admin-nav-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/dashboard?tab=activities" class="admin-nav-item">
                    <i class="fas fa-tasks"></i>
                    <span>Activities</span>
                </a>
                <a href="/admin/dashboard?tab=calendar" class="admin-nav-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Calendar</span>
                </a>
                <a href="/admin/reports" class="admin-nav-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
                <a href="/admin/dashboard?tab=accounts" class="admin-nav-item">
                    <i class="fas fa-users"></i>
                    <span>Accounts</span>
                </a>
                <a href="/admin/dashboard?tab=analytics" class="admin-nav-item">
                    <i class="fas fa-chart-pie"></i>
                    <span>Analytics</span>
                </a>
                <a href="/admin/dashboard?tab=settings" class="admin-nav-item">
                    <i class="fas fa-cogs"></i>
                    <span>Settings</span>
                </a>
                <a href="/profile" class="admin-nav-item active">
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

<!-- Enhanced Statistics Cards with Animations -->
<div class="mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="profileStatsContainer">
        <!-- Total Activities Card -->
        <div class="animated-stat-card group" data-delay="0">
            <div class="card-inner">
                <div class="card-glow"></div>
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="stat-info">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-calendar-alt stat-icon"></i>
                            </div>
                            <h3 class="stat-title">Total Activities</h3>
                            <p class="stat-number">{{ auth()->user()->activities()->count() }}</p>
                            <p class="stat-subtitle">All submissions</p>
                        </div>
                        <div class="stat-visual">
                            <div class="pulse-ring"></div>
                            <div class="pulse-ring-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Activities Card -->
        <div class="animated-stat-card group" data-delay="100">
            <div class="card-inner">
                <div class="card-glow"></div>
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="stat-info">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-clock stat-icon"></i>
                            </div>
                            <h3 class="stat-title">Pending</h3>
                            <p class="stat-number">{{ auth()->user()->activities()->where('status', 'pending')->count() }}</p>
                            <p class="stat-subtitle">Awaiting review</p>
                        </div>
                        <div class="stat-visual">
                            <div class="pulse-ring"></div>
                            <div class="pulse-ring-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Activities Card -->
        <div class="animated-stat-card group" data-delay="200">
            <div class="card-inner">
                <div class="card-glow"></div>
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="stat-info">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-check-circle stat-icon"></i>
                            </div>
                            <h3 class="stat-title">Approved</h3>
                            <p class="stat-number">{{ auth()->user()->activities()->where('status', 'approved')->count() }}</p>
                            <p class="stat-subtitle">Ready to proceed</p>
                        </div>
                        <div class="stat-visual">
                            <div class="pulse-ring"></div>
                            <div class="pulse-ring-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Activities Card -->
        <div class="animated-stat-card group" data-delay="300">
            <div class="card-inner">
                <div class="card-glow"></div>
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="stat-info">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-times-circle stat-icon"></i>
                            </div>
                            <h3 class="stat-title">Rejected</h3>
                            <p class="stat-number">{{ auth()->user()->activities()->where('status', 'rejected')->count() }}</p>
                            <p class="stat-subtitle">Need revision</p>
                        </div>
                        <div class="stat-visual">
                            <div class="pulse-ring"></div>
                            <div class="pulse-ring-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- School Information Banner -->
@if(auth()->user()->department)
<div class="max-w-4xl mx-auto mb-6">
    <div class="bg-gradient-to-r from-green-600 to-blue-600 shadow-xl rounded-2xl border-2 border-yellow-500 overflow-hidden" style="border: 3px solid #eab308 !important;">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-university text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-xl font-bold text-white">{{ auth()->user()->department }}</h2>
                        <p class="text-green-100 text-sm mt-1">
                            @if(auth()->user()->role === 'student')
                                Student - {{ auth()->user()->course ?? 'Course not specified' }}
                                @if(auth()->user()->year_level)
                                    | {{ auth()->user()->year_level }}
                                @endif
                            @elseif(auth()->user()->role === 'adviser')
                                Faculty Adviser
                            @elseif(auth()->user()->role === 'dean')
                                Dean/Unit Head
                            @else
                                {{ ucfirst(auth()->user()->role) }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="text-right">
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20 text-white">
                            <i class="fas fa-id-badge mr-2"></i>
                            @if(auth()->user()->student_id)
                                ID: {{ auth()->user()->student_id }}
                            @else
                                {{ ucfirst(auth()->user()->role) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Main Content - Single Unified Box -->
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-2xl rounded-3xl border-2 border-yellow-500 overflow-hidden" style="border: 3px solid #eab308 !important;">
        <div class="p-10 space-y-12">
            <!-- Profile Information Section -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-user-edit text-green-600 mr-3"></i>
                    Profile Information
                </h3>
                <div class="bg-gray-50 rounded-lg p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Update Section -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-lock text-blue-600 mr-3"></i>
                    Update Password
                </h3>
                <div class="bg-gray-50 rounded-lg p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Account Deletion Section -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-trash text-red-600 mr-3"></i>
                    Delete Account
                </h3>
                <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
    </main>
</body>
</html>
