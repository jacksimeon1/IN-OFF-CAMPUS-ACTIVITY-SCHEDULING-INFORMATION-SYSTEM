<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SPUP Activity Management') }} - Dashboard</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1000;
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }
        
        .sidebar-logo img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            margin-right: 12px;
        }
        
        .sidebar-logo-text {
            font-size: 1.25rem;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-logo-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
            overflow-y: auto;
            height: calc(100vh - 100px);
        }
        
        .nav-section {
            margin-bottom: 2rem;
        }
        
        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.05em;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .nav-section-title {
            opacity: 0;
        }
        
        .nav-item {
            margin: 0.25rem 1rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .nav-text {
            font-size: 0.875rem;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: white;
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            min-width: 20px;
            text-align: center;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .nav-badge {
            opacity: 0;
        }
        
        .sidebar-toggle {
            position: absolute;
            top: 1.5rem;
            right: -12px;
            width: 24px;
            height: 24px;
            background: #3b82f6;
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }
        
        .sidebar-toggle:hover {
            background: #2563eb;
            transform: scale(1.1);
        }
        
        .main-content {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        
        .sidebar.collapsed + .main-content {
            margin-left: 80px;
        }
        
        .content-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-menu-button {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .user-menu-button:hover {
            background: #e2e8f0;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 0.75rem;
        }
        
        .content-body {
            padding: 2rem;
        }
        
        .mobile-menu-button {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #374151;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                left: -280px;
            }
            
            .sidebar.open {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-button {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-chevron-left" id="toggle-icon"></i>
        </button>
        
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-logo">
                <img src="{{ asset('images/SPUP-final-logo.png') }}" alt="SPUP Logo">
                <span class="sidebar-logo-text">SPUP Activity</span>
            </a>
        </div>
        
        <!-- Sidebar Navigation -->
        <nav class="sidebar-nav">
            <!-- Main Navigation -->
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.*') && !request()->routeIs('activities.create') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="nav-text">Activities</span>
                    </a>
                </div>
                
                @if(Auth::user()->role === 'student')
                <div class="nav-item">
                    <a href="{{ route('activities.create') }}" class="nav-link {{ request()->routeIs('activities.create') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span class="nav-text">Submit Activity</span>
                    </a>
                </div>
                @endif
            </div>
            
            <!-- My Role Dashboard -->
            @if(Auth::user()->role !== 'student')
            <div class="nav-section">
                <div class="nav-section-title">My Role</div>

                @if(Auth::user()->role === 'dean')
                <div class="nav-item">
                    <a href="{{ route('dean.dashboard') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span class="nav-text">Dean Dashboard</span>
                        <span class="nav-badge">•</span>
                    </a>
                </div>
                @endif

                @if(Auth::user()->role === 'psg_adviser')
                <div class="nav-item">
                    <a href="{{ route('psg.dashboard') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">PSG Adviser Dashboard</span>
                        <span class="nav-badge">•</span>
                    </a>
                </div>
                @endif

                @if(Auth::user()->role === 'director_student_affairs')
                <div class="nav-item">
                    <a href="{{ route('director.dashboard') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <span class="nav-text">Director Dashboard</span>
                        <span class="nav-badge">•</span>
                    </a>
                </div>
                @endif

                @if(Auth::user()->role === 'vp_academics')
                <div class="nav-item">
                    <a href="{{ route('vp.dashboard') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <span class="nav-text">VP Academics Dashboard</span>
                        <span class="nav-badge">•</span>
                    </a>
                </div>
                @endif

                @if(Auth::user()->role === 'osa')
                <div class="nav-item">
                    <a href="{{ route('osa.dashboard') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <span class="nav-text">OSA Dashboard</span>
                        <span class="nav-badge">•</span>
                    </a>
                </div>
                @endif

                @if(Auth::user()->role === 'admin')
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span class="nav-text">Admin Panel</span>
                        <span class="nav-badge">•</span>
                    </a>
                </div>
                @endif
            </div>
            @endif
            
            <!-- Account -->
            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                
                <div class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="nav-text">Profile</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-link w-full text-left" style="border: none; background: none; width: 100%;">
                            <div class="nav-icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span class="nav-text">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Content Header -->
        <div class="content-header">
            <button class="mobile-menu-button" onclick="toggleMobileSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="user-menu">
                <div class="user-menu-button">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mx-8 mt-4">
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        @if (session('error'))
            <div class="mx-8 mt-4">
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Content Body -->
        <div class="content-body">
            {{ $slot }}
        </div>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const icon = document.getElementById('toggle-icon');
            
            sidebar.classList.toggle('collapsed');
            
            if (sidebar.classList.contains('collapsed')) {
                icon.className = 'fas fa-chevron-right';
            } else {
                icon.className = 'fas fa-chevron-left';
            }
        }
        
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
        
        // Close mobile sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileButton = document.querySelector('.mobile-menu-button');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileButton.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>
