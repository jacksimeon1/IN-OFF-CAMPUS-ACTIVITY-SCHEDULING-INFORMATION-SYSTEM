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
                            <!-- School/Unit -->
                            <div class="md:col-span-2">
                                <label for="organization" class="block text-sm font-medium text-gray-700 mb-2">
                                    School/Unit <span class="text-red-500">*</span>
                                </label>
                                <select name="organization" id="organization" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                    <option value="">Select School/Unit</option>
                                    <option value="SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION" {{ old('organization') == 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION' ? 'selected' : '' }}>SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION</option>
                                    <option value="SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT" {{ old('organization') == 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT' ? 'selected' : '' }}>SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT</option>
                                    <option value="SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING" {{ old('organization') == 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING' ? 'selected' : '' }}>SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING</option>
                                    <option value="SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES" {{ old('organization') == 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES' ? 'selected' : '' }}>SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES</option>
                                    <option value="SCHOOL OF MEDICINE" {{ old('organization') == 'SCHOOL OF MEDICINE' ? 'selected' : '' }}>SCHOOL OF MEDICINE</option>
                                </select>
                                @error('organization')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                <select name="type" id="type" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                    <option value="">Select Activity Type</option>
                                    <option value="in-campus" {{ old('type') === 'in-campus' ? 'selected' : '' }}>In Campus</option>
                                    <option value="off-campus" {{ old('type') === 'off-campus' ? 'selected' : '' }}>Off Campus</option>
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
                            <div class="md:col-span-3">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                    Venue <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('location')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Conflict Detection -->
                        <div id="conflict-checker" class="mt-6 hidden">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
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
                                <input type="number" name="expected_participants" id="expected_participants" value="{{ old('expected_participants') }}" min="1" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                                @error('expected_participants')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Program Budget -->
                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">
                                    Program Budget (PHP)
                                </label>
                                <input type="number" name="budget" id="budget" value="{{ old('budget') }}" step="0.01" min="0" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
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
                                <input type="file" name="budget_file" id="budget_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg p-2">
                                <div id="budget_file_progress" class="hidden mt-2">
                                    <div class="bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">Uploading...</p>
                                </div>
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
                                <input type="file" name="permit_file" id="permit_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg p-2">
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
                                <input type="file" name="supporting_documents" id="supporting_documents" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg p-2">
                                @error('supporting_documents')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end items-center pt-6 pb-16" style="gap: 0;">
                        <a href="{{ route('activities.index') }}" class="skew-button cancel-button">
                            <span>Cancel</span>
                        </a>
                        <button type="submit" class="skew-button submit-button">
                            <span>Submit Activity</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.skew-button {
    background: #fff;
    border: none;
    padding: 12px 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 600;
    min-width: 160px;
    text-transform: uppercase;
    cursor: pointer;
    border-radius: 50px;
    position: relative;
    text-decoration: none;
    color: #000;
    overflow: hidden;
    z-index: 1;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    white-space: nowrap;
}

.skew-button span {
    display: inline-block;
    text-align: center;
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
    margin-right: 10px;
}

.cancel-button::before {
    background: #dc2626;
}

.submit-button {
    background: #d1fae5;
    color: #059669;
    margin-left: 10px;
}

.submit-button::before {
    background: #059669;
}

/* Conflict Detection Styling */
#conflict-checker {
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

.pulse-warning {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
</style>
@endpush

@push('scripts')
<script>
    let conflictCheckTimeout;

    // Date validation functionality
    function validateDates() {
        const startDate = document.getElementById('activity_date');
        const endDate = document.getElementById('end_date');

        if (startDate.value && endDate.value) {
            if (new Date(endDate.value) < new Date(startDate.value)) {
                endDate.setCustomValidity('End date cannot be earlier than start date');
                endDate.reportValidity();
            } else {
                endDate.setCustomValidity('');
            }
        }
    }

    // Simple conflict detection
    function checkConflicts() {
        const activityDate = document.getElementById('activity_date').value;
        const endDate = document.getElementById('end_date').value;
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        const location = document.getElementById('location').value;

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

        if (data.canProceed) {
            html = `
                <div class="flex items-center text-green-600">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-medium">${data.message}</span>
                </div>
            `;
        } else {
            html = `
                <div class="flex items-center text-red-600 mb-3">
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
                                <strong>Date:</strong> ${conflict.details.conflicting_date}<br>
                                <strong>Time:</strong> ${conflict.details.conflicting_time}<br>
                                <strong>Location:</strong> ${conflict.details.conflicting_location}<br>
                                <strong>Status:</strong> ${conflict.details.conflicting_status}
                            </div>
                        </div>
                    `;
                });
            }
        }

        document.getElementById('conflict-results').innerHTML = html;
    }

    // Event listeners for date validation
    const startDateField = document.getElementById('activity_date');
    const endDateField = document.getElementById('end_date');

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

    // Event listeners for real-time checking
    document.getElementById('start_time').addEventListener('change', function() {
        clearTimeout(conflictCheckTimeout);
        conflictCheckTimeout = setTimeout(checkConflicts, 500);
    });

    document.getElementById('end_time').addEventListener('change', function() {
        clearTimeout(conflictCheckTimeout);
        conflictCheckTimeout = setTimeout(checkConflicts, 500);
    });

    document.getElementById('location').addEventListener('input', function() {
        clearTimeout(conflictCheckTimeout);
        conflictCheckTimeout = setTimeout(checkConflicts, 1000);
    });



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
            const daysShort = Math.max(0, 5 - days);
            noticeEl.querySelector('div').className = 'bg-red-50 border border-red-200 rounded-lg p-3 flex items-start';
            detailEl.className = 'text-xs text-red-700 mt-1';
            detailEl.innerHTML = `<span class="text-red-800 font-semibold"><i class=\"fas fa-exclamation-triangle mr-1\"></i> Requirement not met.</span> Only ${days} day(s) before the scheduled date (${val}). Submit at least 5 days prior.`;
        }
    }

    const activityDateInput = document.getElementById('activity_date');
    if (activityDateInput) {
        activityDateInput.addEventListener('change', updateDeadlineNotice);
        // Initialize on load if a value exists
        document.addEventListener('DOMContentLoaded', updateDeadlineNotice);
    }

    // Handle file upload feedback
    const fileInputs = ['budget_file', 'permit_file', 'supporting_documents'];

    fileInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB
                    console.log(`Selected file: ${file.name} (${fileSize} MB)`);

                    // Show file size info
                    let infoElement = document.getElementById(inputId + '_info');
                    if (!infoElement) {
                        infoElement = document.createElement('p');
                        infoElement.id = inputId + '_info';
                        infoElement.className = 'text-xs text-gray-600 mt-1';
                        this.parentNode.appendChild(infoElement);
                    }
                    infoElement.textContent = `Selected: ${file.name} (${fileSize} MB)`;
                }
            });
        }
    });

    // Validate end time is after start time
    document.getElementById('end_time').addEventListener('change', function() {
        const startTime = document.getElementById('start_time').value;
        const endTime = this.value;

        if (startTime && endTime && endTime <= startTime) {
            alert('End time must be after start time');
            this.value = '';
            return;
        }
    });
</script>
@endpush
