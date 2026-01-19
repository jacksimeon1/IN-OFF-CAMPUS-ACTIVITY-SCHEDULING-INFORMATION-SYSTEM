<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Login - In/Off Campus Activity Scheduling Information System ({{ now()->timestamp }})</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
    /* Minimal User Login Design */
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

    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 32px;
        width: 100%;
        max-width: 400px;
        border: 2px solid #059669;
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

    .login-title {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .login-subtitle {
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

    .role-select {
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
    }

    .role-select option {
        padding: 8px 12px;
        font-size: 14px;
    }

    .role-description {
        margin-top: 8px;
        padding: 8px 12px;
        background: #f0f9ff;
        border: 1px solid #e0f2fe;
        border-radius: 6px;
        min-height: 40px;
        transition: all 0.3s ease;
    }

    .role-description.active {
        background: #ecfdf5;
        border-color: #d1fae5;
    }

    .role-description p {
        margin: 0;
        font-size: 13px;
        line-height: 1.4;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        background: #059669;
        color: white;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        margin-top: 4px;
    }

    .required-field {
        color: #dc2626;
        font-weight: 600;
    }

    .form-input.error {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-input.error:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }






    .login-btn {
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
    }

    .login-btn:hover {
        background: #047857;
    }



    @media (max-width: 480px) {
        .login-card {
            padding: 24px;
            margin: 16px;
        }
    }


    </style>

    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ asset('images/SPUP-final-logo.png') }}" alt="SPUP Logo">
                </div>
                <h1 class="login-title">User Login</h1>
                <p class="login-subtitle">In/Off Campus Activity Scheduling Information System</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

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
                        autofocus
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
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <!-- Detected Role (shown only when credentials are valid) -->
                    <div id="detected-role" class="mt-2 text-sm" style="display:none;color:#059669;font-weight:600;"></div>
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-btn">
                    Sign In
                </button>


            </form>
        </div>
    </div>

    <script>
        // Debug: Page loaded
        console.log('Login page script loaded');

        // Secure role detection (no disclosure on failure)
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const detectedRole = document.getElementById('detected-role');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!emailInput || !passwordInput || !detectedRole) return;

            let timer;
            const debounce = (fn, delay = 400) => {
                clearTimeout(timer);
                timer = setTimeout(fn, delay);
            };

            async function checkRole() {
                const email = emailInput.value.trim();
                const password = passwordInput.value;
                if (!email || !password) {
                    detectedRole.style.display = 'none';
                    detectedRole.textContent = '';
                    return;
                }
                try {
                    const res = await fetch("{{ route('login.checkRole') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email, password })
                    });
                    if (!res.ok) throw new Error('Network');
                    const data = await res.json();
                    if (data && data.ok && data.role) {
                        detectedRole.textContent = `Detected role: ${data.role.replace('_', ' ')}`;
                        detectedRole.style.display = 'block';
                    } else {
                        // Hide silently to avoid revealing which field is wrong
                        detectedRole.style.display = 'none';
                        detectedRole.textContent = '';
                    }
                } catch (e) {
                    detectedRole.style.display = 'none';
                    detectedRole.textContent = '';
                }
            }

            emailInput.addEventListener('input', () => debounce(checkRole));
            passwordInput.addEventListener('input', () => debounce(checkRole));
            passwordInput.addEventListener('blur', () => debounce(checkRole, 0));
        });


        // Initialize page
        console.log('Login page loaded successfully!');

        // No client-side role selection validation needed
    </script>

</body>
</html>
