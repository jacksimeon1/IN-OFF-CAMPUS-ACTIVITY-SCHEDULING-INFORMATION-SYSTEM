<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - In/Off Campus Activity Scheduling Information System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
    /* Minimal Admin Login Design */
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

    .remember-me {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
        font-size: 14px;
        color: #6b7280;
    }

    .remember-me input[type="checkbox"] {
        margin-right: 8px;
        accent-color: #059669;
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

    .login-links {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        text-align: center;
    }

    .login-link {
        color: #6b7280;
        font-size: 14px;
    }

    .login-link a {
        color: #059669;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
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
                <h1 class="login-title">Admin Login</h1>
                <p class="login-subtitle">In/Off Campus Activity Scheduling Information System</p>
            </div>



            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login') }}">
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
                        placeholder="Enter admin username"
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
                        placeholder="Enter admin password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Role Selection -->
                <div class="form-group">
                    <label class="form-label" for="test_role">Login as <span style="color: #dc2626;">*</span></label>
                    <select id="test_role" name="test_role" class="form-input role-select" required>
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('test_role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="student" {{ old('test_role') == 'student' ? 'selected' : '' }}>Student Officer</option>
                        <option value="adviser" {{ old('test_role') == 'adviser' ? 'selected' : '' }}>Adviser</option>
                        <option value="dean" {{ old('test_role') == 'dean' ? 'selected' : '' }}>Dean/Unit Head</option>
                        <option value="psg_adviser" {{ old('test_role') == 'psg_adviser' ? 'selected' : '' }}>PSG Council Adviser</option>
                        <option value="director" {{ old('test_role') == 'director' ? 'selected' : '' }}>Director of Student Officer Affairs</option>
                        <option value="vp" {{ old('test_role') == 'vp' ? 'selected' : '' }}>Vice President for Academics</option>
                    </select>
                    <x-input-error :messages="$errors->get('test_role')" class="mt-2" />

                    <!-- Role Description -->
                    <div id="role-description" class="role-description" style="display: none;">
                        <p id="role-text"></p>
                        <span id="role-badge" class="role-badge" style="display: none;">
                            <i class="fas fa-user"></i>
                            <span id="badge-text"></span>
                        </span>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">{{ __('Remember me') }}</label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-btn">
                    Sign In
                </button>

                <!-- Links -->
                <div class="login-links">
                    <div class="login-link">
                        User access? <a href="{{ route('login') }}">User Portal →</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Role descriptions and example credentials
        const roleData = {
            admin: {
                description: "Full system access, user management, comprehensive reporting and analytics.",
                email: "admin@spup.edu.ph",
                badge: "System Administrator"
            },
            student: {
                description: "Create and submit activity requests, track approval status, receive notifications.",
                email: "student1@spup.edu.ph",
                badge: "Student Officer"
            },
            adviser: {
                description: "Review and approve student officer activity requests, first level of approval workflow.",
                email: "adviser1@spup.edu.ph",
                badge: "Faculty Adviser"
            },
            dean: {
                description: "Review adviser-approved requests for academic alignment and departmental objectives.",
                email: "dean.aste@spup.edu.ph",
                badge: "Dean/Unit Head"
            },
            psg_adviser: {
                description: "Review activities for student officer council guidelines compliance and development.",
                email: "psg.adviser1@spup.edu.ph",
                badge: "PSG Council Adviser"
            },
            director: {
                description: "Review institutional policy compliance and student officer welfare, provide endorsement.",
                email: "director.sa@spup.edu.ph",
                badge: "Director of Student Officer Affairs"
            },
            vp: {
                description: "Final approval authority ensuring academic quality and institutional alignment.",
                email: "vp.academics@spup.edu.ph",
                badge: "Vice President for Academics"
            }
        };

        // Handle role selection
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('test_role');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const roleDescription = document.getElementById('role-description');
            const roleText = document.getElementById('role-text');
            const roleBadge = document.getElementById('role-badge');
            const badgeText = document.getElementById('badge-text');

            if (!roleSelect || !emailInput || !passwordInput) {
                console.error('Required elements not found');
                return;
            }

            roleSelect.addEventListener('change', function() {
                const selectedRole = this.value;
                console.log('Role changed to:', selectedRole);

                if (selectedRole && roleData[selectedRole]) {
                    // Show role description only - no auto-fill
                    if (roleText) roleText.textContent = roleData[selectedRole].description;
                    if (badgeText) badgeText.textContent = roleData[selectedRole].badge;
                    if (roleDescription) {
                        roleDescription.style.display = 'block';
                        roleDescription.classList.add('active');
                    }
                    if (roleBadge) roleBadge.style.display = 'inline-flex';

                } else {
                    // Hide role description
                    if (roleDescription) {
                        roleDescription.style.display = 'none';
                        roleDescription.classList.remove('active');
                    }
                    if (roleBadge) roleBadge.style.display = 'none';
                }
            });
        });

        // Initialize page
        console.log('Admin login page loaded successfully!');

        // Form validation
        const form = document.querySelector('form');
        const roleSelect = document.getElementById('test_role');

        form.addEventListener('submit', function(e) {
            if (!roleSelect.value) {
                e.preventDefault();
                roleSelect.classList.add('error');
                roleSelect.focus();
                
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'mt-2 text-sm text-red-600';
                errorDiv.textContent = 'Please select your role before logging in.';
                
                // Remove existing error message if any
                const existingError = roleSelect.parentNode.querySelector('.text-red-600');
                if (existingError) {
                    existingError.remove();
                }
                
                roleSelect.parentNode.appendChild(errorDiv);
            } else {
                roleSelect.classList.remove('error');
                const existingError = roleSelect.parentNode.querySelector('.text-red-600');
                if (existingError) {
                    existingError.remove();
                }
            }
        });

        // Remove error styling when role is selected
        roleSelect.addEventListener('change', function() {
            this.classList.remove('error');
            const existingError = this.parentNode.querySelector('.text-red-600');
            if (existingError) {
                existingError.remove();
            }
        });
    </script>
</body>
</html>
