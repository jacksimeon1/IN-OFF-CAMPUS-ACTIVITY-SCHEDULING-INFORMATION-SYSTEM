<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - In/Off Campus Activity Scheduling Information System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <style>
    /* Minimal Register Design */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        background: #f8fafc;
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
    }

    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .register-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 32px;
        width: 100%;
        max-width: 450px;
        border: 1px solid #e2e8f0;
    }

    .logo-container {
        text-align: center;
        margin-bottom: 32px;
    }

    .logo {
        width: 48px;
        height: 48px;
        margin: 0 auto 16px;
        border-radius: 50%;
        overflow: hidden;
    }

    .logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .register-title {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .register-subtitle {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }

    .register-btn {
        width: 100%;
        padding: 12px;
        background: #059669;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        margin-top: 8px;
    }

    .register-btn:hover {
        background: #047857;
    }

    .register-links {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        text-align: center;
    }

    .register-link {
        color: #6b7280;
        font-size: 14px;
    }

    .register-link a {
        color: #059669;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .register-card {
            padding: 30px 25px;
            margin: 10px;
        }

        .register-title {
            font-size: 24px;
        }

        .logo {
            width: 70px;
            height: 70px;
        }
    }

    </style>

    <div class="register-container">
        <div class="register-card">
            <!-- Logo Section -->
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ asset('images/SPUP-final-logo.png') }}" alt="SPUP Logo">
                </div>
                <h1 class="register-title">Create Account</h1>
                <p class="register-subtitle">In/Off Campus Activity Scheduling Information System</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name Field -->
                <div class="form-group">
                    <label class="form-label" for="name">Full Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        class="form-input"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Enter your full name"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label" for="email">Username</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        class="form-input"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        placeholder="Enter your username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-input"
                        required
                        autocomplete="new-password"
                        placeholder="Enter your password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="form-input"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Register Button -->
                <button type="submit" class="register-btn">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Account
                </button>

                <!-- Links -->
                <div class="register-links">
                    <div class="register-link">
                        Already have an account? <a href="{{ route('login') }}">Sign In</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
