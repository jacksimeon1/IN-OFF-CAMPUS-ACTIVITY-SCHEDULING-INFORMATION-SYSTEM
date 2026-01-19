@extends('layouts.sidebar')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Activity Management System')

@section('content')
<div class="pb-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center py-16">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <i class="fas fa-info-circle text-blue-500 text-6xl mb-6"></i>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Welcome to SPUP Activity Management System
                </h2>
                
                <p class="text-lg text-gray-600 mb-6">
                    You are logged in as: <strong>{{ $user->getRoleDisplayName() }}</strong>
                </p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">
                        <i class="fas fa-workflow mr-2"></i>
                        Approval Workflow System
                    </h3>
                    <p class="text-blue-800 mb-4">
                        This system focuses on the 5-step approval workflow for activity management:
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-sm">
                        <div class="bg-white p-3 rounded border-l-4 border-green-500">
                            <div class="font-semibold text-green-800">Step 1</div>
                            <div class="text-green-700">Faculty Adviser</div>
                            <div class="text-xs text-green-600">Initial Review</div>
                        </div>
                        
                        <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                            <div class="font-semibold text-blue-800">Step 2</div>
                            <div class="text-blue-700">Dean/Unit Head</div>
                            <div class="text-xs text-blue-600">Academic Review</div>
                        </div>
                        
                        <div class="bg-white p-3 rounded border-l-4 border-purple-500">
                            <div class="font-semibold text-purple-800">Step 3</div>
                            <div class="text-purple-700">PSG Council Adviser</div>
                            <div class="text-xs text-purple-600">Guidelines Review</div>
                        </div>
                        
                        <div class="bg-white p-3 rounded border-l-4 border-red-500">
                            <div class="font-semibold text-red-800">Step 4</div>
                            <div class="text-red-700">Director of Student Affairs</div>
                            <div class="text-xs text-red-600">Policy Endorsement</div>
                        </div>
                        
                        <div class="bg-white p-3 rounded border-l-4 border-gray-500">
                            <div class="font-semibold text-gray-800">Step 5</div>
                            <div class="text-gray-700">VP for Academics</div>
                            <div class="text-xs text-gray-600">Final Approval</div>
                        </div>
                    </div>
                </div>
                

                
                <div class="text-gray-600">
                    <p class="mb-2">
                        <i class="fas fa-user mr-2"></i>
                        Logged in as: {{ $user->name }} ({{ $user->email }})
                    </p>
                    @if($user->department)
                        <p class="mb-4">
                            <i class="fas fa-building mr-2"></i>
                            Department: {{ $user->department }}
                        </p>
                    @endif
                </div>
                
                <div class="flex justify-center space-x-4">
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-l-4 {
        border-left-width: 4px;
    }
</style>
@endpush
