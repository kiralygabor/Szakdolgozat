<!-- User Report Modal -->
<div id="user-report-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[70] hidden transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl relative mx-4 animate-fade-in-up">
        
        <!-- Close Button -->
        <button type="button" onclick="closeUserReportModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10 p-1">
            <i data-feather="x" class="w-6 h-6"></i>
        </button>

        <!-- Modal Content -->
        <div class="pt-8 pb-6 px-8">
            
            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center">
                    <i data-feather="user-minus" class="w-8 h-8 text-red-600"></i>
                </div>
            </div>

            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Report User</h2>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Help us keep Minijobz safe by reporting suspicious or inappropriate behavior.
                </p>
            </div>

            <!-- Report Form -->
            <form id="user-report-form" method="POST" action="{{ route('user-reports.store') }}">
                @csrf
                <input type="hidden" name="reported_account_id" id="user-report-reported-account-id">

                <!-- Description Textarea -->
                <div class="mb-6">
                    <label for="user-report-description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Reason for reporting <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        id="user-report-description" 
                        rows="5" 
                        required
                        minlength="10"
                        maxlength="1000"
                        placeholder="Please provide details about why you're reporting this user..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none resize-none text-sm"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimum 10 characters</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="closeUserReportModal()"
                        class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors"
                    >
                        Submit Report
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function openUserReportModal(reportedAccountId) {
    document.getElementById('user-report-reported-account-id').value = reportedAccountId;
    document.getElementById('user-report-description').value = '';
    document.getElementById('user-report-modal').classList.remove('hidden');
    
    // Refresh feather icons
    if (window.feather && typeof window.feather.replace === 'function') {
        window.feather.replace();
    }
}

function closeUserReportModal() {
    document.getElementById('user-report-modal').classList.add('hidden');
}

// Close on background click
document.getElementById('user-report-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeUserReportModal();
    }
});
</script>
