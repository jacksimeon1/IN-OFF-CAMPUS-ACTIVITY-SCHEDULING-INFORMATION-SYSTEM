<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPUP Activity Management') }} - @yield('title', 'Dashboard')</title>

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

            html {
                font-size: 13px !important; 
            }

            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
                min-height: 100vh;
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

            /* Comprehensive Responsive System - Preserve Layout */

            /* Base viewport and scaling setup */
            * {
                box-sizing: border-box;
            }

            body {
                min-width: 320px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Responsive scaling with zoom and font-size combination */
            @media (max-width: 1400px) {
                html { font-size: 15px; }
                body { zoom: 0.75; }
            }

            @media (max-width: 1200px) {
                html { font-size: 14px; }
                body { zoom: 0.7; }
            }

            @media (max-width: 992px) {
                html { font-size: 13px; }
                body { zoom: 0.65; }
            }

            @media (max-width: 768px) {
                html { font-size: 12px; }
                body { zoom: 0.6; }

                /* Ensure horizontal scrolling works */
                .overflow-x-auto, .table-responsive {
                    overflow-x: auto !important;
                    -webkit-overflow-scrolling: touch;
                }
            }

            @media (max-width: 576px) {
                html { font-size: 11px; }
                body { zoom: 0.55; }
            }

            @media (max-width: 480px) {
                html { font-size: 10px; }
                body { zoom: 0.5; }
            }

            @media (max-width: 360px) {
                html { font-size: 9px; }
                body { zoom: 0.45; }
            }

            @media (max-width: 320px) {
                html { font-size: 8px; }
                body { zoom: 0.4; }
            }

            /* Preserve interactive element accessibility */
            button, input, select, textarea, a {
                min-height: 20px;
                min-width: 20px;
            }

            /* Maintain table layouts */
            table {
                min-width: max-content;
            }

            /* Preserve image aspect ratios */
            img, video, iframe {
                max-width: 100%;
                height: auto;
            }
        </style>

        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @hasSection('header')
                <header class="bg-yellow-500 shadow-green border-b border-gray-200" style="background-color: #eab308 !important;">
                    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif

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
            <main>
                @yield('content')
            </main>
        </div>

        @include('partials.document-preview')

        @stack('scripts')
    </body>
</html>
