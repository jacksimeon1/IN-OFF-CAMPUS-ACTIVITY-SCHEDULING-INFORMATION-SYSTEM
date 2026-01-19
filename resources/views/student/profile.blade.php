@extends('layouts.sidebar')

@section('title', 'Student Officer Profile')
@section('page-title', 'Student Officer Profile')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-green-600 to-blue-600 shadow-xl rounded-2xl border-2 border-yellow-500 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-graduate text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-xl font-bold text-white">{{ auth()->user()->name }}</h2>
                        <p class="text-green-100 text-sm mt-1">Student Officer</p>
                        <p class="text-green-100 text-xs mt-1">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information Form -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
            <p class="text-sm text-gray-600 mt-1">Update your account's profile information and email address.</p>
        </div>

        <form method="post" action="{{ route('student.profile.update') }}" class="p-6 space-y-6">
            @csrf
            @method('patch')

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Full Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', auth()->user()->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', auth()->user()->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
            </div>

            <!-- Department (read-only, normalized to 5 canonical schools) -->
            <div class="mt-4">
                <x-input-label for="department_display" :value="__('School/Department')" />
                @php
                    $rawDept = auth()->user()->department;
                    $map = [
                        'Engineering Department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
                    ];
                    $displayDept = $rawDept ? ($map[$rawDept] ?? $rawDept) : null;
                @endphp
                @if($displayDept)
                    <input id="department_display" type="text" class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100" value="{{ $displayDept }}" disabled>
                @else
                    <input id="department_display" type="text" class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100" value="Not assigned" disabled>
                @endif
            </div>

            <!-- Save Button -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-save mr-2"></i>
                        {{ __('Save Changes') }}
                    </button>

                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 3000)"
                            class="text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg px-3 py-2 flex items-center"
                        >
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ __('Profile updated successfully!') }}
                        </p>
                    @endif
                </div>

                <div class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Last updated: {{ auth()->user()->updated_at->format('M d, Y \a\t g:i A') }}
                </div>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-lock text-blue-600 mr-2"></i>
                Update Password
            </h3>
            <p class="text-sm text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure.</p>
        </div>

        <form method="post" action="{{ route('student.password.update') }}" class="p-6 space-y-6">
            @csrf
            @method('put')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" required />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="update_password_password" :value="__('New Password')" />
                        <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" required />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" />
                        <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" required />
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-key mr-2"></i>
                        {{ __('Update Password') }}
                    </button>

                    @if (session('status') === 'password-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 3000)"
                            class="text-sm text-blue-600 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2 flex items-center"
                        >
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ __('Password updated successfully!') }}
                        </p>
                    @endif
                </div>

                <div class="text-xs text-gray-500">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Keep your password secure
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
