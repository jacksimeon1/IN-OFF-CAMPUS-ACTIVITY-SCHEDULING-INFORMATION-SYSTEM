@extends('layouts.sidebar')

@section('title', 'Submit New Activity')
@section('page-title', 'Submit New Activity')
@section('page-subtitle', 'Create and submit a new activity for approval')

@section('content')
<div class="pb-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
            <div class="p-4">
                <form method="POST" action="{{ route('activities.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Basic Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            Basic Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- School/Unit (locked to student's department, normalized) -->
                            <div class="md:col-span-2">
                                <label for="organization_display" class="block text-sm font-medium text-gray-700 mb-2">
                                    School/Unit <span class="text-red-500">*</span>
                                </label>
                                @php
                                    $rawDept = auth()->user()->department;
                                    $map = [
                                        'Engineering Department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
                                        'IT Department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
                                        'Business Department' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
                                        'Nursing Department' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
                                        'Medicine Department' => 'SCHOOL OF MEDICINE',
                                    ];
                                    $displayDept = $rawDept ? ($map[$rawDept] ?? $rawDept) : null;
                                @endphp
                                <input type="text" id="organization_display" class="block w-full rounded-lg border-gray-300 bg-gray-100 shadow-sm" value="{{ $displayDept ?: 'Not assigned' }}" disabled>
                                <input type="hidden" name="organization" value="{{ $displayDept }}">
                                @error('organization')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if(!$displayDept)
                                    <p class="mt-2 text-sm text-red-600">You are not assigned to a department. Please contact the administrator.</p>
                                @endif
                            </div>

                            <!-- Name of Activity -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Name of the Activity <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Activity Type -->
                            <div class="md:col-span-2">
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Activity Type <span class="text-red-500">*</span>
                                </label>
                                <select name="type" id="type" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                    <option value="">Select Activity Type</option>
                                    <option value="in-campus" {{ old('type') == 'in-campus' ? 'selected' : '' }}>In-Campus</option>
                                    <option value="off-campus" {{ old('type') == 'off-campus' ? 'selected' : '' }}>Off-Campus</option>
                                </select>
                                @error('type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Leaders/Organizers -->
                            <div class="md:col-span-2">
                                <label for="leaders" class="block text-sm font-medium text-gray-700 mb-2">
                                    Leaders/Organizers of the Activity <span class="text-red-500">*</span>
                                </label>
                                <textarea name="leaders" id="leaders" rows="3" required
                                          class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">{{ old('leaders') }}</textarea>
                                @error('leaders')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Objectives/Purposes -->
                            <div class="md:col-span-2">
                                <label for="objectives" class="block text-sm font-medium text-gray-700 mb-2">
                                    Objective(s)/Purpose(s) of the Activity <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-3">
                                    <div>
                                        <input type="text" name="objective_1" id="objective_1" value="{{ old('objective_1') }}" required
                                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                               placeholder="Objective 1">
                                    </div>
                                    <div>
                                        <input type="text" name="objective_2" id="objective_2" value="{{ old('objective_2') }}"
                                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                               placeholder="Objective 2">
                                    </div>
                                    <div>
                                        <input type="text" name="objective_3" id="objective_3" value="{{ old('objective_3') }}"
                                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                               placeholder="Objective 3">
                                    </div>
                                </div>
                                @error('objective_1')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Schedule and Location -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Schedule and Location
                        </h3>

                        <!-- Deadline Requirement Notice -->
                        <div id="deadline-notice" class="mb-4">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start">
                                <i class="fas fa-info-circle text-yellow-600 mr-2 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-yellow-800 font-medium">
                                        Submission deadline: submit at least <strong>5 days</strong> before your activity start date.
                                    </p>
                                    <p id="deadline-detail" class="text-xs text-yellow-700 mt-1">
                                        Select a start date to check if the requirement is met.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <label for="activity_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="activity_date" id="activity_date" value="{{ old('activity_date') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('activity_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('end_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Time <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('start_time')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Time <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('end_time')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Venue -->
                            <div class="md:col-span-2">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                    Venue <span class="text-red-500">*</span>
                                </label>
                                <p id="venue-help" class="text-xs text-gray-500 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Please select an activity type first to choose the venue
                                </p>
                                <!-- In-Campus dropdown (shown when type = in-campus) -->
                                <select id="location_select" name="location" class="hidden block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                    <option value="">Select Venue</option>
                                    <option value="Student Center">Student Center</option>
                                    <option value="BEU">BEU</option>
                                    <option value="Global">Global</option>
                                    <option value="MM Hall">MM Hall</option>
                                </select>

                                <!-- Off-Campus free text (default) -->
                                <input type="text" id="location_input" value="{{ old('location') }}" required
                                       placeholder="Enter off-campus venue location"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('location')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Conflict Detection -->
                        <div id="conflict-checker" class="mt-6 hidden">
                            <div id="conflict-panel" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div id="conflict-results">
                                    <div class="flex items-center text-blue-600">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Checking for conflicts and analyzing schedule...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Display server-side conflicts if any -->
                        @if(session('conflicts'))
                            <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <h4 class="text-lg font-semibold text-red-900">Scheduling Conflicts Detected</h4>
                                </div>
                                @foreach(session('conflicts') as $conflict)
                                    <div class="mb-2 p-3 bg-red-100 rounded border-l-4 border-red-500">
                                        <p class="text-red-800 font-medium">{{ $conflict['message'] }}</p>
                                        <div class="text-sm text-red-600 mt-1">
                                            <strong>Conflicting Activity:</strong> {{ $conflict['details']['conflicting_activity'] }}<br>
                                            <strong>Time:</strong> {{ $conflict['details']['conflicting_time'] }}<br>
                                            <strong>Location:</strong> {{ $conflict['details']['conflicting_location'] }}<br>
                                            <strong>Status:</strong> {{ ucfirst($conflict['details']['conflicting_status']) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @error('conflict')
                            <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <p class="text-red-800">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror
                    </div>

                    <!-- Activity Details -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-users text-green-600 mr-2"></i>
                            Additional Details
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Number of Participants -->
                            <div>
                                <label for="expected_participants" class="block text-sm font-medium text-gray-700 mb-2">
                                    Number of Participants <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="expected_participants" id="expected_participants" value="{{ old('expected_participants') }}" min="1" required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('expected_participants')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Program Budget -->
                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">
                                    Program Budget (PHP)
                                </label>
                                <input type="number" name="budget" id="budget" value="{{ old('budget') }}" step="0.01" min="0"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('budget')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Speakers -->
                            <div class="md:col-span-2">
                                <label for="speakers" class="block text-sm font-medium text-gray-700 mb-2">
                                    Speakers (if any)
                                </label>
                                <textarea name="speakers" id="speakers" rows="3"
                                          class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">{{ old('speakers') }}</textarea>
                                @error('speakers')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Required Attachments -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-paperclip text-green-600 mr-2"></i>
                            Required Attachments
                        </h3>
                        <div class="space-y-6">
                            <!-- Attachment 1: Letter of Request -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <label for="budget_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded mr-2">Required</span>
                                        Attachment 1: Letter of request approved by the President/Vice President for Academics/Dean
                                    </span>
                                </label>
                                <input type="file" name="budget_file" id="budget_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg p-2">
                                @error('budget_file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Attachment 2: Program of Activities -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <label for="permit_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded mr-2">Required</span>
                                        Attachment 2: Planned program of activities/program flow
                                    </span>
                                </label>
                                <input type="file" name="permit_file" id="permit_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg p-2">
                                @error('permit_file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Attachment 3: Letter for Attire/Costumes -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <label for="supporting_documents" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded mr-2">Optional</span>
                                        Attachment 3: Letter for Attire/Costumes
                                    </span>
                                </label>
                                <input type="file" name="supporting_documents" id="supporting_documents" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg p-2">
                                @error('supporting_documents')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons - Updated with new design -->
                    <div class="flex justify-end items-center pt-6 pb-16 space-x-3">
                        <a href="{{ route('student.activities') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to List
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Apply Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Updated button styles to match admin design */
.inline-flex {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}

.py-2 {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}

.bg-gray-600 {
    background-color: #4b5563;
}

.bg-green-600 {
    background-color: #059669;
}

.text-white {
    color: #ffffff;
}

.text-xs {
    font-size: 0.75rem;
}

.font-semibold {
    font-weight: 600;
}

.uppercase {
    text-transform: uppercase;
}

.tracking-widest {
    letter-spacing: 0.1em;
}

.rounded-md {
    border-radius: 0.375rem;
}

.border-transparent {
    border-color: transparent;
}

.hover\:bg-gray-700:hover {
    background-color: #374151;
}

.hover\:bg-green-700:hover {
    background-color: #047857;
}

.focus\:outline-none:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}

.focus\:ring-2:focus {
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #6b7280;
}

    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.suggestion-button {
    background: #059669;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.suggestion-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.conflict-item {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<script>
    let conflictCheckTimeout;

    // Date validation functionality
    function validateDates() {
        const startDate = document.getElementById('activity_date');
        const endDate = document.getElementById('end_date');

        if (startDate.value && endDate.value) {
            if (new Date(endDate.value) < new Date(startDate.value)) {
                endDate.setCustomValidity('End date must be after start date');
            } else {
                endDate.setCustomValidity('');
            }
        }
    }

    // Helper: returns current venue value depending on activity type
    function getLocationValue() {
        const type = document.getElementById('type')?.value;
        const selectEl = document.getElementById('location_select');
        const inputEl = document.getElementById('location_input');
        if (type === 'in-campus' && selectEl) return selectEl.value || '';
        if (inputEl) return inputEl.value || '';
        return '';
    }

    // Simple conflict detection
    function checkConflicts() {
        const activityDate = document.getElementById('activity_date').value;
        const endDate = document.getElementById('end_date').value;
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        const location = getLocationValue();

        // Only check if we have the required fields
        if (!activityDate || !endDate || !startTime || !endTime || !location) {
            document.getElementById('conflict-checker').classList.add('hidden');
            return;
        }

        // Show the conflict checker
        document.getElementById('conflict-checker').classList.remove('hidden');
        document.getElementById('conflict-results').innerHTML = `
            <div class="flex items-center text-blue-600">
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Checking for conflicts...
            </div>
        `;

        // Make AJAX request
        fetch('{{ route("activities.check-conflicts") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                activity_date: activityDate,
                end_date: endDate,
                start_time: startTime,
                end_time: endTime,
                location: location
            })
        })
        .then(response => response.json())
        .then(data => {
            showResults(data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('conflict-results').innerHTML = `
                <div class="text-red-600">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Error checking conflicts. Please try again.
                </div>
            `;
        });
    }

    function showResults(data) {
        let html = '';
        const panel = document.getElementById('conflict-panel');

        if (data.hasConflicts) {
            // Style panel red for conflicts
            if (panel) {
                panel.className = 'bg-red-50 border border-red-200 rounded-lg p-4';
            }
            html = `
                <div class="text-red-600 mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-medium">${data.message}</span>
                </div>
            `;

            // Show conflict details
            if (data.conflicts && data.conflicts.length > 0) {
                data.conflicts.forEach(conflict => {
                    html += `
                        <div class="mb-2 p-3 bg-red-100 rounded border-l-4 border-red-500">
                            <p class="text-red-800 font-medium">${conflict.message}</p>
                            <div class="text-sm text-red-600 mt-1">
                                <strong>Activity:</strong> ${conflict.details.conflicting_activity}<br>
                                ${conflict.details.conflicting_department ? `<strong>Department:</strong> ${conflict.details.conflicting_department}<br>` : ''}
                                <strong>Date:</strong> ${conflict.details.conflicting_date}<br>
                                <strong>Time:</strong> ${conflict.details.conflicting_time}<br>
                                <strong>Location:</strong> ${conflict.details.conflicting_location}<br>
                                <strong>Status:</strong> ${conflict.details.conflicting_status}
                            </div>
                        </div>
                    `;
                });
            }

            // Suggestions UI
            if (Array.isArray(data.suggestions) && data.suggestions.length > 0) {
                const timeSuggestions = data.suggestions.filter(s => s.type === 'time');
                const venueSuggestions = data.suggestions.filter(s => s.type === 'venue');
                html += `
                    <div class="mt-4 p-3 bg-white rounded border border-yellow-300">
                        <div class="flex items-center text-yellow-700 mb-2">
                            <i class="fas fa-lightbulb mr-2"></i>
                            <span class="font-semibold">Suggested alternatives</span>
                        </div>
                        ${timeSuggestions.length ? `
                            <div class="mb-3">
                                <div class="text-sm font-medium text-gray-700 mb-1">Time options:</div>
                                <div class="flex flex-wrap gap-2">
                                    ${timeSuggestions.map(s => `
                                        <button type="button" class="suggestion-button apply-suggestion" data-s-type="time"
                                            data-activity_date="${s.activity_date}" data-end_date="${s.end_date}"
                                            data-start_time="${s.start_time}" data-end_time="${s.end_time}">
                                            ${s.label}
                                        </button>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                        ${venueSuggestions.length ? `
                            <div>
                                <div class="text-sm font-medium text-gray-700 mb-1">Venue options:</div>
                                <div class="flex flex-wrap gap-2">
                                    ${venueSuggestions.map(s => `
                                        <button type="button" class="suggestion-button apply-suggestion" data-s-type="venue"
                                            data-location="${s.location}">
                                            ${s.label}
                                        </button>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }
        } else {
            // Style panel green when clear
            if (panel) {
                panel.className = 'bg-green-50 border border-green-200 rounded-lg p-4';
            }
            html = `
                <div class="text-green-600">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-medium">${data.message}</span>
                </div>
            `;
        }

        const resultsEl = document.getElementById('conflict-results');
        resultsEl.innerHTML = html;

        // Attach handlers for Apply Suggestion buttons
        resultsEl.querySelectorAll('.apply-suggestion').forEach(btn => {
            btn.addEventListener('click', function() {
                const sType = this.getAttribute('data-s-type');
                if (sType === 'time') {
                    const aDate = this.getAttribute('data-activity_date');
                    const eDate = this.getAttribute('data-end_date');
                    const sTime = this.getAttribute('data-start_time');
                    const eTime = this.getAttribute('data-end_time');
                    const startDateField = document.getElementById('activity_date');
                    const endDateField = document.getElementById('end_date');
                    const startTimeField = document.getElementById('start_time');
                    const endTimeField = document.getElementById('end_time');
                    if (startDateField) startDateField.value = aDate;
                    if (endDateField) endDateField.value = eDate;
                    if (startTimeField) startTimeField.value = sTime.substring(0,5);
                    if (endTimeField) endTimeField.value = eTime.substring(0,5);
                } else if (sType === 'venue') {
                    const loc = this.getAttribute('data-location');
                    const typeEl = document.getElementById('type');
                    const selectEl = document.getElementById('location_select');
                    const inputEl = document.getElementById('location_input');
                    // Prefer dropdown if currently in-campus
                    if (typeEl && typeEl.value === 'in-campus' && selectEl) {
                        // If option exists, select it; else set value directly
                        const opt = Array.from(selectEl.options).find(o => o.value === loc);
                        if (opt) selectEl.value = loc; else selectEl.value = '';
                    } else if (inputEl) {
                        inputEl.value = loc;
                    }
                }
                clearTimeout(conflictCheckTimeout);
                conflictCheckTimeout = setTimeout(checkConflicts, 200);
            });
        });
    }

    // Toggle venue field based on activity type
    function updateLocationField() {
        const typeEl = document.getElementById('type');
        const selectEl = document.getElementById('location_select');
        const inputEl = document.getElementById('location_input');
        const helpEl = document.getElementById('venue-help');
        if (!typeEl || !selectEl || !inputEl || !helpEl) return;

        if (typeEl.value === 'in-campus') {
            // show dropdown, hide input
            selectEl.classList.remove('hidden');
            inputEl.classList.add('hidden');
            // move name attribute to the active control
            selectEl.setAttribute('name', 'location');
            inputEl.removeAttribute('name');
            // Ensure required on the active one only
            selectEl.setAttribute('required', 'required');
            inputEl.removeAttribute('required');
            // Enable the dropdown
            selectEl.disabled = false;
            // Update help message
            helpEl.innerHTML = '<i class="fas fa-check-circle mr-1 text-green-600"></i>Select an in-campus venue from the dropdown';
            helpEl.className = 'text-xs text-green-600 mb-2';
        } else if (typeEl.value === 'off-campus') {
            // show input, hide dropdown
            inputEl.classList.remove('hidden');
            selectEl.classList.add('hidden');
            inputEl.setAttribute('name', 'location');
            selectEl.removeAttribute('name');
            inputEl.setAttribute('required', 'required');
            selectEl.removeAttribute('required');
            // Enable the input
            inputEl.disabled = false;
            // Update help message
            helpEl.innerHTML = '<i class="fas fa-map-marker-alt mr-1 text-blue-600"></i>Enter the off-campus venue location';
            helpEl.className = 'text-xs text-blue-600 mb-2';
        } else {
            // No type selected - hide both and disable
            selectEl.classList.add('hidden');
            inputEl.classList.add('hidden');
            selectEl.removeAttribute('name');
            inputEl.removeAttribute('name');
            selectEl.removeAttribute('required');
            inputEl.removeAttribute('required');
            // Disable both fields
            selectEl.disabled = true;
            inputEl.disabled = true;
            // Clear values
            selectEl.value = '';
            inputEl.value = '';
            // Update help message
            helpEl.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Please select an activity type first to choose the venue';
            helpEl.className = 'text-xs text-gray-500 mb-2';
        }
    }

    // Event listeners for date validation
    document.addEventListener('DOMContentLoaded', function() {
        const startDateField = document.getElementById('activity_date');
        const endDateField = document.getElementById('end_date');
        const typeField = document.getElementById('type');
        const locationInput = document.getElementById('location_input');
        const locationSelect = document.getElementById('location_select');

        if (startDateField && endDateField) {
            startDateField.addEventListener('change', function() {
                endDateField.min = this.value;
                validateDates();
                clearTimeout(conflictCheckTimeout);
                conflictCheckTimeout = setTimeout(checkConflicts, 500);
            });

            endDateField.addEventListener('change', function() {
                validateDates();
                clearTimeout(conflictCheckTimeout);
                conflictCheckTimeout = setTimeout(checkConflicts, 500);
            });
        }

        // Initialize venue control based on existing selection (including old('type'))
        updateLocationField();

        // Event listeners for real-time checking
        document.getElementById('start_time').addEventListener('change', function() {
            clearTimeout(conflictCheckTimeout);
            conflictCheckTimeout = setTimeout(checkConflicts, 500);
        });

        document.getElementById('end_time').addEventListener('change', function() {
            clearTimeout(conflictCheckTimeout);
            conflictCheckTimeout = setTimeout(checkConflicts, 500);
        });

        if (locationInput) {
            locationInput.addEventListener('input', function() {
                clearTimeout(conflictCheckTimeout);
                conflictCheckTimeout = setTimeout(checkConflicts, 1000);
            });
        }
        if (locationSelect) {
            locationSelect.addEventListener('change', function() {
                clearTimeout(conflictCheckTimeout);
                conflictCheckTimeout = setTimeout(checkConflicts, 500);
            });
        }

        if (typeField) {
            typeField.addEventListener('change', function() {
                updateLocationField();
                clearTimeout(conflictCheckTimeout);
                conflictCheckTimeout = setTimeout(checkConflicts, 500);
            });
        }

        // Validate end time is after start time
        document.getElementById('end_time').addEventListener('change', function() {
            const startTime = document.getElementById('start_time').value;
            const endTime = this.value;

            if (startTime && endTime && endTime <= startTime) {
                alert('End time must be after start time');
                this.value = '';
                return;
            }

            // Trigger conflict check
            clearTimeout(conflictCheckTimeout);
            conflictCheckTimeout = setTimeout(checkConflicts, 500);
        });

        // Deadline Notice Logic
        function updateDeadlineNotice() {
            const startInput = document.getElementById('activity_date');
            const detailEl = document.getElementById('deadline-detail');
            const noticeEl = document.getElementById('deadline-notice');

            if (!startInput || !detailEl || !noticeEl) return;

            const val = startInput.value;
            if (!val) {
                noticeEl.querySelector('div').className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start';
                detailEl.className = 'text-xs text-yellow-700 mt-1';
                detailEl.innerText = 'Select a start date to check if the requirement is met.';
                return;
            }

            const today = new Date();
            today.setHours(0,0,0,0);
            const start = new Date(val);
            start.setHours(0,0,0,0);
            const diffMs = start.getTime() - today.getTime();
            const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));

            if (days >= 5) {
                noticeEl.querySelector('div').className = 'bg-green-50 border border-green-200 rounded-lg p-3 flex items-start';
                detailEl.className = 'text-xs text-green-700 mt-1';
                detailEl.innerHTML = `<span class="text-green-800 font-semibold"><i class="fas fa-check-circle mr-1"></i> Requirement met.</span> ${days} day(s) before the scheduled date (${val}).`;
            } else {
                noticeEl.querySelector('div').className = 'bg-red-50 border border-red-200 rounded-lg p-3 flex items-start';
                detailEl.className = 'text-xs text-red-700 mt-1';
                detailEl.innerHTML = `<span class=\"text-red-800 font-semibold\"><i class=\"fas fa-exclamation-triangle mr-1\"></i> Requirement not met.</span> Only ${days} day(s) before the scheduled date (${val}). Submit at least 5 days prior.`;
            }
        }

        if (startDateField) {
            startDateField.addEventListener('change', updateDeadlineNotice);
            updateDeadlineNotice();
        }
    });
</script>
@endsection
