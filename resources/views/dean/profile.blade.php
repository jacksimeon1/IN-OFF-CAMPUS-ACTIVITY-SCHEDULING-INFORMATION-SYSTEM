@extends('layouts.sidebar')

@section('title', 'Dean Profile')
@section('page-title', 'Dean Profile Settings')

@section('content')
@php
    // Define school-specific colors
    $schoolColors = [
        'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING' => [
            'gradient' => 'from-purple-800 to-purple-900',
            'border' => 'border-purple-300',
            'icon_bg' => 'bg-purple-300',
            'text_accent' => 'text-purple-200'
        ],
        'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES' => [
            'gradient' => 'from-red-800 to-red-900',
            'border' => 'border-red-300',
            'icon_bg' => 'bg-red-300',
            'text_accent' => 'text-red-200'
        ],
        'SCHOOL OF MEDICINE' => [
            'gradient' => 'from-gray-700 to-gray-800',
            'border' => 'border-gray-300',
            'icon_bg' => 'bg-gray-300',
            'text_accent' => 'text-gray-200'
        ],
        'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT' => [
            'gradient' => 'from-green-800 to-green-900',
            'border' => 'border-green-300',
            'icon_bg' => 'bg-green-300',
            'text_accent' => 'text-green-200'
        ],
        'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION' => [
            'gradient' => 'from-blue-800 to-blue-900',
            'border' => 'border-blue-300',
            'icon_bg' => 'bg-blue-300',
            'text_accent' => 'text-blue-200'
        ]
    ];

    $userDepartment = auth()->user()->department;
    $colors = $schoolColors[$userDepartment] ?? [
        'gradient' => 'from-gray-700 to-gray-800',
        'border' => 'border-gray-300',
        'icon_bg' => 'bg-gray-300',
        'text_accent' => 'text-gray-200'
    ];
@endphp

<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r {{ $colors['gradient'] }} shadow-xl rounded-2xl border-2 {{ $colors['border'] }} overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 {{ $colors['icon_bg'] }} bg-opacity-90 rounded-full flex items-center justify-center">
                            <i class="fas fa-university text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-xl font-bold text-white">{{ auth()->user()->name }}</h2>
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

        <form method="post" action="{{ route('dean.profile.update') }}" class="p-6 space-y-6">
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

            <!-- School/Department (read-only for deans) -->
            <div>
                <x-input-label for="department_display" :value="__('School/Department')" />
                @if(auth()->user()->department)
                    <input id="department_display" type="text" class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100" value="{{ auth()->user()->department }}" disabled>
                    <!-- Preserve current department on submit, but don't allow changes -->
                    <input type="hidden" name="department" value="{{ auth()->user()->department }}">
                @else
                    <input id="department_display" type="text" class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100" value="Not assigned" disabled>
                @endif
                <x-input-error class="mt-2" :messages="$errors->get('department')" />
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

        <form method="post" action="{{ route('dean.password.update') }}" class="p-6 space-y-6">
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

    

    
</div>
@endsection
