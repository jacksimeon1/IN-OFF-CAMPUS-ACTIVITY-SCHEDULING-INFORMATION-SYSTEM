<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPUP Activity Management') }} - @yield('title', 'Admin Panel')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Styles -->
        <style>
            :root {
                --primary-green: #059669;
                --primary-green-dark: #047857;
                --primary-green-light: #10b981;
                --secondary-green: #d1fae5;
                --accent-green: #34d399;
                --success-green: #22c55e;
                --warning-yellow: #f59e0b;
                --danger-red: #ef4444;
                --gray-50: #f9fafb;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --gray-900: #111827;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%) !important;
                margin: 0 !important;
                padding: 0 !important;
                min-height: 100vh !important;
                height: 100% !important;
            }

            body::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%) !important;
                z-index: -999;
                pointer-events: none;
            }

            .bg-primary-green { background-color: var(--primary-green); }
            .bg-primary-green-dark { background-color: var(--primary-green-dark); }
            .bg-secondary-green { background-color: var(--secondary-green); }
            .text-primary-green { color: var(--primary-green); }
            .border-primary-green { border-color: var(--primary-green); }

            .shadow-green {
                box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.1), 0 2px 4px -1px rgba(5, 150, 105, 0.06);
            }

            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.1), 0 10px 10px -5px rgba(5, 150, 105, 0.04);
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
                border: none;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, var(--primary-green-dark) 0%, #065f46 100%);
                transform: translateY(-1px);
            }

            /* Admin Header */
            .admin-header {
                background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
                color: white;
                padding: 1.5rem 0;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                position: relative;
                overflow: hidden;
            }

            .admin-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            }

            .admin-header-content {
                position: relative;
                z-index: 1;
            }

            .admin-breadcrumb {
                background: rgba(255, 255, 255, 0.1);
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                margin-top: 0.5rem;
            }

            .admin-breadcrumb a {
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .admin-breadcrumb a:hover {
                color: white;
            }

            .admin-breadcrumb .separator {
                margin: 0 0.5rem;
                color: rgba(255, 255, 255, 0.6);
            }

            .admin-content {
                padding: 1rem 0 0.5rem 0;
                margin-top: 60px;
            }

            /* Admin specific form styling */
            .admin-form-container {
                background: white;
                border-radius: 1rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border: 1px solid #e5e7eb;
            }

            .admin-form-header {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                padding: 1.5rem;
                border-bottom: 1px solid #e5e7eb;
                border-radius: 1rem 1rem 0 0;
            }

            .admin-form-body {
                padding: 2rem;
            }

            .admin-table-container {
                background: white;
                border-radius: 1rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border: 1px solid #e5e7eb;
                overflow: hidden;
            }

            .admin-table-header {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                padding: 1.5rem;
                border-bottom: 1px solid #e5e7eb;
            }
        </style>

        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">


            <!-- Flash Messages -->
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none" onclick="this.closest('.bg-green-50').style.display='none'">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none" onclick="this.closest('.bg-red-50').style.display='none'">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="admin-content">
                @yield('content')
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
