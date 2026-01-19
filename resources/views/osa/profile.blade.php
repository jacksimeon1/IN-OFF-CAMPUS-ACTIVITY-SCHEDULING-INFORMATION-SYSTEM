@extends('layouts.sidebar')

@section('title', 'OSA Profile')
@section('page-title', 'OSA Profile Settings')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-green-600 to-blue-600 shadow-xl rounded-2xl border-2 border-yellow-500 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-xl font-bold text-white">{{ auth()->user()->name }}</h2>
                        <p class="text-green-100 text-sm mt-1">Office of Student Affairs</p>
                        <p class="text-green-100 text-xs mt-1">Student Services & Activities</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="text-right">
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20 text-white">
                            <i class="fas fa-users-cog mr-2"></i>
                            Student Affairs
                        </div>
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

        <form method="post" action="{{ route('osa.profile.update') }}" class="p-6 space-y-6">
            @csrf
            @method('patch')

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', auth()->user()->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', auth()->user()->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Update Password</h3>
            <p class="text-sm text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure.</p>
        </div>

        <form method="post" action="{{ route('osa.password.update') }}" class="p-6 space-y-6">
            @csrf
            @method('put')

            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>

    <!-- OSA Information -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">OSA Role</h3>
        </div>
        <div class="p-6">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-teal-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h6 class="text-sm font-medium text-gray-900">Office of Student Affairs</h6>
                        <p class="text-xs text-gray-600 mt-1">Student services coordination and activity oversight</p>
                        <p class="text-xs text-teal-600 mt-1 font-medium">Student Support Services</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Verified
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('osa.dashboard') }}" class="flex items-center p-4 bg-teal-50 border border-teal-200 rounded-lg hover:bg-teal-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tachometer-alt text-teal-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-teal-900">Dashboard</div>
                        <div class="text-xs text-teal-700">View overview</div>
                    </div>
                </a>

                <a href="{{ route('osa.activities') }}" class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-list text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-blue-900">All Activities</div>
                        <div class="text-xs text-blue-700">View all activities</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
