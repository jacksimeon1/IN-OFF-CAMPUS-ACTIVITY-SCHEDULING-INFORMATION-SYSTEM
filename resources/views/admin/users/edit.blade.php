@extends('layouts.admin')

@section('title', 'Edit User')

@push('styles')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }





        /* FontAwesome icons */
        .fas, .far, .fab, .fal, .fad {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 5 Free", "Font Awesome 5 Pro" !important;
            font-weight: 900 !important;
        }

        /* Skewed Button Design */
        .skewed-btn {
            position: relative;
            display: inline-block;
            padding: 12px 24px;
            background: white;
            border: none;
            transform: skew(-21deg);
            transition: all 0.3s ease;
            cursor: pointer;
            margin: 0 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .skewed-btn span {
            display: block;
            transform: skew(21deg);
            transition: all 0.3s ease;
        }

        .skewed-btn.cancel {
            background: #ef4444;
            color: white;
        }

        .skewed-btn.cancel:hover {
            background: #dc2626;
        }

        .skewed-btn.cancel:hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s ease;
        }

        .skewed-btn.create {
            background: #22c55e;
            color: white;
        }

        .skewed-btn.create:hover {
            background: #16a34a;
        }

        .skewed-btn.create:hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s ease;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 16px;
            margin-top: 24px;
        }
    </style>
@endpush

@section('content')

<div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Username -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Username')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- User ID -->
                        <div class="mb-4">
                            <x-input-label for="student_id" :value="__('User ID (Optional)')" />
                            <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id', $user->student_id)" />
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Role</option>
                                <option value="dean" {{ old('role', $user->role) === 'dean' ? 'selected' : '' }}>Dean</option>
                                <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student Officer</option>
                                <option value="director" {{ old('role', $user->role) === 'director' ? 'selected' : '' }}>Director</option>
                                <option value="adviser" {{ old('role', $user->role) === 'adviser' ? 'selected' : '' }}>Adviser</option>
                                <option value="psg_adviser" {{ old('role', $user->role) === 'psg_adviser' ? 'selected' : '' }}>PSG Council Adviser</option>
                                <option value="vp" {{ old('role', $user->role) === 'vp' ? 'selected' : '' }}>VP Acads</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Department -->
                        <div class="mb-4">
                            <x-input-label for="department" :value="__('Department/School Assignment')" />
                            <select id="department" name="department" class="block mt-1 w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                                <option value="">Select Department/School</option>
                                <optgroup label="SPUP Schools (for Deans)">
                                    <option value="SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION" {{ old('department', $user->department) === 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION' ? 'selected' : '' }}>SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION</option>
                                    <option value="SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT" {{ old('department', $user->department) === 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT' ? 'selected' : '' }}>SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT</option>
                                    <option value="SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING" {{ old('department', $user->department) === 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING' ? 'selected' : '' }}>SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING</option>
                                    <option value="SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES" {{ old('department', $user->department) === 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES' ? 'selected' : '' }}>SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES</option>
                                    <option value="SCHOOL OF MEDICINE" {{ old('department', $user->department) === 'SCHOOL OF MEDICINE' ? 'selected' : '' }}>SCHOOL OF MEDICINE</option>
                                </optgroup>
                                <optgroup label="Administrative Departments">
                                    <option value="Office of Student Affairs" {{ old('department', $user->department) === 'Office of Student Affairs' ? 'selected' : '' }}>Office of Student Affairs</option>
                                    <option value="Student Officer Affairs" {{ old('department', $user->department) === 'Student Officer Affairs' ? 'selected' : '' }}>Student Officer Affairs</option>
                                    <option value="IT Department" {{ old('department', $user->department) === 'IT Department' ? 'selected' : '' }}>IT Department</option>
                                    <option value="Other" {{ old('department', $user->department) === 'Other' ? 'selected' : '' }}>Other</option>
                                </optgroup>
                            </select>
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500">
                                <strong>For Deans:</strong> Select the specific SPUP School you will oversee. Only activities from that school will be routed to you.
                            </p>
                        </div>

                        <!-- Course -->
                        <div class="mb-4" id="course-field" style="display: {{ old('role', $user->role) === 'student' ? 'block' : 'none' }};">
                            <x-input-label for="course" :value="__('Course (Optional)')" />
                            <x-text-input id="course" class="block mt-1 w-full" type="text" name="course" :value="old('course', $user->course)" />
                            <x-input-error :messages="$errors->get('course')" class="mt-2" />
                        </div>

                        <!-- Year Level -->
                        <div class="mb-4" id="year-field" style="display: {{ old('role', $user->role) === 'student' ? 'block' : 'none' }};">
                            <x-input-label for="year_level" :value="__('Year Level (Optional)')" />
                            <select id="year_level" name="year_level" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Year Level</option>
                                <option value="1st Year" {{ old('year_level', $user->year_level) === '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ old('year_level', $user->year_level) === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ old('year_level', $user->year_level) === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ old('year_level', $user->year_level) === '4th Year' ? 'selected' : '' }}>4th Year</option>
                                <option value="5th Year" {{ old('year_level', $user->year_level) === '5th Year' ? 'selected' : '' }}>5th Year</option>
                            </select>
                            <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
                        </div>

                        <!-- Active Status -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input id="is_active" type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <x-input-label for="is_active" :value="__('Active User')" class="ml-2" />
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <!-- Password (Optional) -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('New Password (Leave blank to keep current)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="button-container">
                            <a href="{{ route('admin.users') }}" class="skewed-btn cancel">
                                <span>Cancel</span>
                            </a>
                            <button type="submit" class="skewed-btn create">
                                <span>Update User</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Role change functionality
    document.getElementById('role').addEventListener('change', function() {
        const role = this.value;
        const courseField = document.getElementById('course-field');
        const yearField = document.getElementById('year-field');

        if (role === 'student') {
            courseField.style.display = 'block';
            yearField.style.display = 'block';
        } else {
            courseField.style.display = 'none';
            yearField.style.display = 'none';
        }
    });
</script>
@endpush
