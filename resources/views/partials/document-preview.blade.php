{{-- Document Preview Modal --}}
<div x-data="{ showModal: false, docUrl: '', docType: '' }" 
     x-show="showModal" 
     @keydown.escape.window="showModal = false"
     style="display: none;" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

    <div @click.away="showModal = false" class="bg-white rounded-lg shadow-xl w-11/12 h-5/6 md:w-4/5 md:h-5/6 max-w-6xl max-h-full flex flex-col">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">Document Preview</h3>
            <button @click="showModal = false" class="text-gray-500 hover:text-gray-800">&times;</button>
        </div>
        <div class="p-4 flex-grow">
            <template x-if="docType === 'pdf' || docType === 'jpg' || docType === 'jpeg' || docType === 'png' || docType === 'gif'">
                <iframe :src="docUrl" class="w-full h-full border-0"></iframe>
            </template>
            <template x-if="docType === 'docx'">
                <iframe :src="'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(docUrl)" class="w-full h-full border-0"></iframe>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showDocumentPreview(url, type) {
        // Extract filename from URL
        const filename = url.split('/').pop();
        
        // For DOCX files, use the dedicated viewer
        if (type.toLowerCase() === 'docx') {
            const docxViewerUrl = `/attachments/docx-viewer/${filename}`;
            window.open(docxViewerUrl, '_blank');
            return;
        }
        
        // For other files, use the modal
        const modal = document.querySelector('[x-data]');
        const alpineInstance = modal.__x;

        alpineInstance.data.docUrl = url;
        alpineInstance.data.docType = type.toLowerCase();
        alpineInstance.data.showModal = true;
    }
</script>
@endpush
