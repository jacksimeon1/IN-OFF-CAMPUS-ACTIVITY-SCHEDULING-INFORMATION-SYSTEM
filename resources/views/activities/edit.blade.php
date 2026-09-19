@extends('layouts.sidebar')

@section('title', 'Edit Activity')
@section('page-title', 'Edit Activity')

@section('content')
<!-- Document Preview Modal -->
<div id="documentPreviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg w-full max-w-6xl">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Document Preview</h3>
                <button type="button" onclick="closeDocumentPreview()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="documentPreviewContent" style="min-height: 640px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-edit text-blue-600 mr-2"></i>
                Edit Activity
            </h3>
            <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Activities
            </a>
        </div>
    </div>
    <div class="p-6">
                    <form method="POST" action="{{ route('activities.update', $activity) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Deadline Requirement Notice -->
                        <div id="deadline-notice" class="mb-6">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start">
                                <i class="fas fa-info-circle text-yellow-600 mr-2 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-yellow-800 font-medium">
                                        Submission deadline: submit at least <strong>5 days</strong> before your activity start date.
                                    </p>
                                    <p id="deadline-detail" class="text-xs text-yellow-700 mt-1">
                                        Start date is pre-filled. The system will check compliance automatically.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <x-input-label for="title" :value="__('Activity Title')" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $activity->title)" required autofocus />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Organization -->
                            <div class="md:col-span-2">
                                <x-input-label for="organization" :value="__('School/Unit')" />
                                @if(auth()->user() && method_exists(auth()->user(), 'isStudent') && auth()->user()->isStudent())
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
                                    <x-text-input id="organization_display" class="block mt-1 w-full bg-gray-100" type="text" :value="$displayDept" disabled />
                                    <input type="hidden" name="organization" value="{{ $displayDept }}" />
                                @else
                                    <select id="organization" name="organization" required
                                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select School/Unit</option>
                                        <option value="SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION" {{ old('organization', $activity->organization) == 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION' ? 'selected' : '' }}>SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION</option>
                                        <option value="SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT" {{ old('organization', $activity->organization) == 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT' ? 'selected' : '' }}>SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT</option>
                                        <option value="SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING" {{ old('organization', $activity->organization) == 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING' ? 'selected' : '' }}>SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING</option>
                                        <option value="SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES" {{ old('organization', $activity->organization) == 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES' ? 'selected' : '' }}>SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES</option>
                                        <option value="SCHOOL OF MEDICINE" {{ old('organization', $activity->organization) == 'SCHOOL OF MEDICINE' ? 'selected' : '' }}>SCHOOL OF MEDICINE</option>
                                    </select>
                                @endif
                                <x-input-error :messages="$errors->get('organization')" class="mt-2" />
                            </div>

                            <!-- Leaders -->
                            <div class="md:col-span-2">
                                <x-input-label for="leaders" :value="__('Leaders/Organizers')" />
                                <textarea id="leaders" name="leaders" rows="3" required
                                          class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('leaders', $activity->leaders) }}</textarea>
                                <x-input-error :messages="$errors->get('leaders')" class="mt-2" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('Activity Type')" />
                                <select id="type" name="type" required class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Type</option>
                                    <option value="in-campus" {{ old('type', $activity->type) === 'in-campus' ? 'selected' : '' }}>In-Campus</option>
                                    <option value="off-campus" {{ old('type', $activity->type) === 'off-campus' ? 'selected' : '' }}>Off-Campus</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- Start Date -->
                            <div>
                                <x-input-label for="activity_date" :value="__('Start Date')" />
                                <x-text-input id="activity_date" class="block mt-1 w-full" type="date" name="activity_date" :value="old('activity_date', $activity->activity_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('activity_date')" class="mt-2" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <x-input-label for="end_date" :value="__('End Date')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $activity->end_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>

                            <!-- Start Time -->
                            <div>
                                <x-input-label for="start_time" :value="__('Start Time')" />
                                <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" :value="old('start_time', $activity->start_time->format('H:i'))" required />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>

                            <!-- End Time -->
                            <div>
                                <x-input-label for="end_time" :value="__('End Time')" />
                                <x-text-input id="end_time" class="block mt-1 w-full" type="time" name="end_time" :value="old('end_time', $activity->end_time->format('H:i'))" required />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>

                            <!-- Location -->
                            <div class="md:col-span-2">
                                <x-input-label for="location" :value="__('Location')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $activity->location)" required />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <!-- Objectives -->
                            <div class="md:col-span-2">
                                <x-input-label for="objective_1" :value="__('Objectives/Purposes')" />
                                <div class="space-y-3 mt-1">
                                    <input id="objective_1" name="objective_1" type="text" required
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           value="{{ old('objective_1', $activity->objective_1) }}" placeholder="Objective 1" />
                                    <input id="objective_2" name="objective_2" type="text"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           value="{{ old('objective_2', $activity->objective_2) }}" placeholder="Objective 2" />
                                    <input id="objective_3" name="objective_3" type="text"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           value="{{ old('objective_3', $activity->objective_3) }}" placeholder="Objective 3" />
                                </div>
                                <x-input-error :messages="$errors->get('objective_1')" class="mt-2" />
                            </div>

                            <!-- Speakers -->
                            <div class="md:col-span-2">
                                <x-input-label for="speakers" :value="__('Speakers (if any)')" />
                                <textarea id="speakers" name="speakers" rows="3"
                                          class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('speakers', $activity->speakers) }}</textarea>
                                <x-input-error :messages="$errors->get('speakers')" class="mt-2" />
                            </div>

                            <!-- Budget -->
                            <div>
                                <x-input-label for="budget" :value="__('Budget (Optional)')" />
                                <x-text-input id="budget" class="block mt-1 w-full" type="number" step="0.01" name="budget" :value="old('budget', $activity->budget)" />
                                <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                            </div>

                            <!-- Expected Participants -->
                            <div>
                                <x-input-label for="expected_participants" :value="__('Expected Participants')" />
                                <x-text-input id="expected_participants" class="block mt-1 w-full" type="number" name="expected_participants" :value="old('expected_participants', $activity->expected_participants)" />
                                <x-input-error :messages="$errors->get('expected_participants')" class="mt-2" />
                            </div>

                                                    </div>

                        <!-- File Uploads -->
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Supporting Documents</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Budget File -->
                                <div>
                                    <x-input-label for="budget_file" value="Letter of request approved by the President/Vice President for Academics/Dean" />
                                    @if($activity->budget_file)
                                        <p class="text-sm text-gray-600 mb-2">
                                            @php
                                                $budgetExt = strtolower(pathinfo($activity->budget_file, PATHINFO_EXTENSION));
                                                $budgetUrl = route('attachments.view', ['filename' => basename($activity->budget_file)]);
                                            @endphp
                                            Current: <a href="#" onclick="showDocumentPreview('{{ $budgetUrl }}', '{{ $budgetExt }}'); return false;" class="text-blue-600 hover:underline">View Document</a>
                                        </p>
                                    @endif
                                    <input id="budget_file" type="file" name="budget_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                                           class="block mt-1 mb-4 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <x-input-error :messages="$errors->get('budget_file')" class="mt-2" />
                                </div>

                                <!-- Permit File -->
                                <div>
                                    <x-input-label for="permit_file" value="Planned program of activities/program flow" />
                                    @if($activity->permit_file)
                                        <p class="text-sm text-gray-600 mb-2">
                                            @php
                                                $permitExt = strtolower(pathinfo($activity->permit_file, PATHINFO_EXTENSION));
                                                $permitUrl = route('attachments.view', ['filename' => basename($activity->permit_file)]);
                                            @endphp
                                            Current: <a href="#" onclick="showDocumentPreview('{{ $permitUrl }}', '{{ $permitExt }}'); return false;" class="text-blue-600 hover:underline">View Document</a>
                                        </p>
                                    @endif
                                    <input id="permit_file" type="file" name="permit_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                                           class="block mt-1 mb-4 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <x-input-error :messages="$errors->get('permit_file')" class="mt-2" />
                                </div>

                                <!-- Supporting Documents -->
                                <div>
                                    <x-input-label for="supporting_documents" :value="__('Supporting Documents')" />
                                    @if($activity->supporting_documents)
                                        <p class="text-sm text-gray-600 mb-2">
                                            @php
                                                $suppExt = strtolower(pathinfo($activity->supporting_documents, PATHINFO_EXTENSION));
                                                $suppUrl = route('attachments.view', ['filename' => basename($activity->supporting_documents)]);
                                            @endphp
                                            Current: <a href="#" onclick="showDocumentPreview('{{ $suppUrl }}', '{{ $suppExt }}'); return false;" class="text-blue-600 hover:underline">View Document</a>
                                            | <a href="{{ $activity->supporting_documents_download_url }}" class="text-green-600 hover:underline">Download</a>
                                        </p>
                                    @endif
                                    <input id="supporting_documents" type="file" name="supporting_documents" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                                           class="block mt-1 mb-4 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <x-input-error :messages="$errors->get('supporting_documents')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end items-center pt-6 pb-16 border-t border-gray-200" style="gap: 0;">
                            <a href="{{ route('activities.index') }}" class="skew-button cancel-button">
                                <span>Cancel</span>
                            </a>
                            <button type="submit" class="skew-button submit-button">
                                <span>Update Activity</span>
                            </button>
                        </div>
                    </form>
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
</style>
@endpush

@push('scripts')
<script>
// Include Mammoth.js for DOCX viewing
const script = document.createElement('script');
script.src = 'https://unpkg.com/mammoth@1.6.0/mammoth.browser.min.js';
document.head.appendChild(script);

// Enhanced document preview function with Mammoth.js support
window.showDocumentPreview = function(url, type) {
    console.log('showDocumentPreview called with:', { url, type });
    
    const modal = document.getElementById('documentPreviewModal');
    const content = document.getElementById('documentPreviewContent');
    
    // Check if it's a DOCX file
    if (type === 'docx' || url.toLowerCase().includes('.docx')) {
        // Extract filename from URL
        const filename = url.split('/').pop();
        
        // Route to our Mammoth.js viewer
        const docxViewerUrl = '/attachments/docx-viewer/' + encodeURIComponent(filename);
        console.log('Opening DOCX viewer:', docxViewerUrl);
        window.open(docxViewerUrl, '_blank');
        return false;
    }
    
    if (type === 'pdf') {
        content.innerHTML = `<iframe src="${url}" frameborder="0" style="width:100%;height:640px;"></iframe>`;
    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(type)) {
        content.innerHTML = `<img src="${url}" style="max-width:100%;height:auto;" alt="Document image">`;
    } else {
        content.innerHTML = `<div style="text-align:center;padding:50px;"><p>Preview not available for this file type.</p><a href="${url}" target="_blank">Download file</a></div>`;
    }
    
    modal.classList.remove('hidden');
    return false; // Prevent default action
};

function closeDocumentPreview() {
    const modal = document.getElementById('documentPreviewModal');
    modal.classList.add('hidden');
    document.getElementById('documentPreviewContent').innerHTML = '';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDocumentPreview();
    }
});

// Make sure our function is loaded after the page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document ready - showDocumentPreview function available:', typeof window.showDocumentPreview);
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

    const startDateField = document.getElementById('activity_date');
    if (startDateField) {
        startDateField.addEventListener('change', updateDeadlineNotice);
        updateDeadlineNotice();
    }
});
</script>
@endpush
