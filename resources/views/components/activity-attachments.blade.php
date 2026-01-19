@props(['activity'])

@if($activity->budget_file || $activity->permit_file || $activity->supporting_documents)
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-paperclip mr-2 text-gray-600"></i>
                Supporting Documents
            </h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @if($activity->budget_file)
                    <x-document-attachment 
                        :file="$activity->budget_file"
                        title="Letter of Request"
                        type="budget"
                        border-color="border-green-200"
                    />
                @endif
                
                @if($activity->permit_file)
                    <x-document-attachment 
                        :file="$activity->permit_file"
                        title="Program of Activities"
                        type="permits"
                        border-color="border-yellow-200"
                    />
                @endif
                
                @if($activity->supporting_documents)
                    <x-document-attachment 
                        :file="$activity->supporting_documents"
                        title="Supporting Documents"
                        type="documents"
                        border-color="border-blue-200"
                    />
                @endif
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script src="{{ asset('js/simple-document-viewer.js') }}"></script>
@endpush