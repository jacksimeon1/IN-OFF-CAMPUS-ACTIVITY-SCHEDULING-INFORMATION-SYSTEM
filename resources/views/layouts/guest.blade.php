<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPUP Activity Management') }}</title>

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
                min-height: 100vh;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <div class="w-12 h-12 mx-auto rounded-full bg-emerald-600 flex items-center justify-center mb-4">
                    <i class="fas fa-university text-white text-lg"></i>
                </div>
                <h1 class="text-xl font-semibold text-gray-800 mb-1">In/Off Campus Activity Scheduling Information System</h1>
                <p class="text-sm text-gray-600">Saint Paul University Philippines</p>
            </div>

            <!-- Auth Card -->
            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-sm rounded-lg border border-gray-200">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} Saint Paul University Philippines</p>
            </div>
        </div>


    </body>
</html>
