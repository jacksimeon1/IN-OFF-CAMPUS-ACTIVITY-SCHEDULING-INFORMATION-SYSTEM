@extends('layouts.admin')

@section('title', 'Create User')
@section('page-title', 'Create New User')
@section('page-subtitle', 'Add a new user to the activity management system')

@push('styles')
<style>
/* Hide sidebar and hamburger menu on create user page */
#adminSidebar,
#adminToggleBtn,
#adminSidebarOverlay,
.admin-sidebar,
.admin-toggle-btn,
.admin-sidebar-overlay {
    display: none !important;
    visibility: hidden !important;
}
</style>
@endpush


@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.users') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to User Management
        </a>
    </div>

    <!-- Create User Form -->
    <div class="admin-form-container">
        <div class="admin-form-header">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-user-plus mr-3 text-green-600"></i>
                        Create New User Account
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Add a new user to the activity management system</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="admin-form-body" style="padding: 2rem; padding-bottom: 3rem;">
                    <form method="POST" action="{{ route('admin.users.store') }}" style="margin-bottom: 2rem;">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Username -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Username')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- User ID -->
                        <div class="mb-4">
                            <x-input-label for="student_id" :value="__('User ID (Optional)')" />
                            <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id')" />
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Role</option>
                                <option value="dean" {{ old('role') === 'dean' ? 'selected' : '' }}>Dean</option>
                                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student Officer</option>
                                <option value="director" {{ old('role') === 'director' ? 'selected' : '' }}>Director</option>
                                <option value="adviser" {{ old('role') === 'adviser' ? 'selected' : '' }}>Adviser</option>
                                <option value="psg_adviser" {{ old('role') === 'psg_adviser' ? 'selected' : '' }}>PSG Council Adviser</option>
                                <option value="vp" {{ old('role') === 'vp' ? 'selected' : '' }}>VP Acads</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Department -->
                        <div class="mb-4" id="department-field">
                            <x-input-label for="department" :value="__('Department/School Assignment')" />
                            <select id="department" name="department" class="block mt-1 w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                                <option value="">Select Department/School</option>
                                <option value="SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION" {{ old('department') === 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION' ? 'selected' : '' }}>SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION</option>
                                <option value="SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT" {{ old('department') === 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT' ? 'selected' : '' }}>SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT</option>
                                <option value="SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING" {{ old('department') === 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING' ? 'selected' : '' }}>SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING</option>
                                <option value="SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES" {{ old('department') === 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES' ? 'selected' : '' }}>SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES</option>
                                <option value="SCHOOL OF MEDICINE" {{ old('department') === 'SCHOOL OF MEDICINE' ? 'selected' : '' }}>SCHOOL OF MEDICINE</option>
                            </select>
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500" id="department-help">
                                <strong>For Deans:</strong> Select the specific SPUP School you will oversee. Only activities from that school will be routed to you.
                            </p>
                        </div>

                        <!-- Course -->
                        <div class="mb-4" id="course-field" style="display: none;">
                            <x-input-label for="course" :value="__('Course (Optional)')" />
                            <x-text-input id="course" class="block mt-1 w-full" type="text" name="course" :value="old('course')" />
                            <x-input-error :messages="$errors->get('course')" class="mt-2" />
                        </div>

                        <!-- Year Level -->
                        <div class="mb-4" id="year-field" style="display: none;">
                            <x-input-label for="year_level" :value="__('Year Level (Optional)')" />
                            <select id="year_level" name="year_level" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Year Level</option>
                                <option value="1st Year" {{ old('year_level') === '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ old('year_level') === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ old('year_level') === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ old('year_level') === '4th Year' ? 'selected' : '' }}>4th Year</option>
                                <option value="5th Year" {{ old('year_level') === '5th Year' ? 'selected' : '' }}>5th Year</option>
                            </select>
                            <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                            <a href="{{ route('admin.users') }}" class="skew-button cancel-button">
                                <span>Cancel</span>
                            </a>
                            <button type="submit" class="skew-button create-button">
                                <span>Create User</span>
                            </button>
                        </div>

                        <style>
                        .skew-button {
                            background: #fff;
                            border: none;
                            padding: 10px 20px;
                            display: inline-block;
                            font-size: 15px;
                            font-weight: 600;
                            width: 140px;
                            text-transform: uppercase;
                            cursor: pointer;
                            transform: skew(-21deg);
                            position: relative;
                            text-decoration: none;
                            color: #000;
                            overflow: hidden;
                            z-index: 1;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        }

                        .skew-button span {
                            display: inline-block;
                            transform: skew(21deg);
                        }

                        .skew-button::before {
                            content: '';
                            position: absolute;
                            top: 0;
                            bottom: 0;
                            right: 100%;
                            left: 0;
                            background: rgb(20, 20, 20);
                            opacity: 0;
                            z-index: -1;
                            transition: all 0.5s;
                        }

                        .skew-button:hover {
                            color: #fff;
                        }

                        .skew-button:hover::before {
                            left: 0;
                            right: 0;
                            opacity: 1;
                        }

                        .cancel-button {
                            background: #fee2e2;
                            color: #dc2626;
                            margin-right: 20px;
                        }

                        .cancel-button::before {
                            background: #dc2626;
                        }

                        .create-button {
                            background: #d1fae5;
                            color: #059669;
                            margin-left: 20px;
                        }

                        .create-button::before {
                            background: #059669;
                        }
                        </style>
                    </form>
        </div>
    </div>
</div>

    @push('scripts')
    <script>
        document.getElementById('role').addEventListener('change', function() {
            const role = this.value;
            const courseField = document.getElementById('course-field');
            const yearField = document.getElementById('year-field');
            const departmentField = document.getElementById('department-field');
            const departmentHelp = document.getElementById('department-help');
            const departmentSelect = document.getElementById('department');
            
            // Always clear department field when role changes
            departmentSelect.value = '';
            
            // Set required attribute based on role
            if (role === 'dean' || role === 'student' || role === 'adviser') {
                departmentSelect.required = true;
            } else {
                departmentSelect.required = false;
            }
            
            // Show/hide fields based on role
            if (role === 'student') {
                courseField.style.display = 'block';
                yearField.style.display = 'block';
                departmentField.style.display = 'block';
                departmentHelp.innerHTML = '<strong>For Student Officers:</strong> Select the department you belong to.';
            } else if (role === 'dean') {
                courseField.style.display = 'none';
                yearField.style.display = 'none';
                departmentField.style.display = 'block';
                departmentHelp.innerHTML = '<strong>For Deans:</strong> Select the specific SPUP School you will oversee.';
            } else if (role === 'director') {
                courseField.style.display = 'none';
                yearField.style.display = 'none';
                departmentField.style.display = 'none';
                departmentHelp.innerHTML = '<strong>For Directors:</strong> Department is not required.';
            } else if (role === 'adviser') {
                courseField.style.display = 'none';
                yearField.style.display = 'none';
                departmentField.style.display = 'block';
                departmentHelp.innerHTML = '<strong>For Advisers:</strong> Select the department you will be assigned to for noting activities.';
            } else if (role === 'psg_adviser') {
                courseField.style.display = 'none';
                yearField.style.display = 'none';
                departmentField.style.display = 'none';
                departmentHelp.innerHTML = '<strong>For PSG Council Advisers:</strong> Department is not required.';
            } else if (role === 'vp') {
                courseField.style.display = 'none';
                yearField.style.display = 'none';
                departmentField.style.display = 'none';
                departmentHelp.innerHTML = '<strong>For VP Acads:</strong> Department is not required.';
            } else {
                // No role selected
                courseField.style.display = 'none';
                yearField.style.display = 'none';
                departmentField.style.display = 'none';
                departmentHelp.innerHTML = 'Select a role to see relevant options.';
            }
        });

        // Trigger on page load if role is already selected
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            if (roleSelect.value) {
                roleSelect.dispatchEvent(new Event('change'));
            } else {
                // Hide all fields initially
                document.getElementById('course-field').style.display = 'none';
                document.getElementById('year-field').style.display = 'none';
                document.getElementById('department-field').style.display = 'none';
                document.getElementById('department-help').innerHTML = 'Select a role to see relevant options.';
            }
        });

        // Add form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const role = document.getElementById('role').value;
            const department = document.getElementById('department').value;
            
            if ((role === 'dean' || role === 'student' || role === 'adviser') && !department) {
                e.preventDefault();
                alert('Please select a department for the ' + role.replace('_', ' ').toUpperCase() + ' role.');
                document.getElementById('department').focus();
            }
        });
    </script>
    @endpush
</div>
@endsection
