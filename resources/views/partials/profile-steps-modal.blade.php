{{-- Account Completion / Profile Steps Modal --}}
<div id="profile-steps-modal" class="fixed inset-0 modal-overlay flex items-center justify-center z-[100] hidden transition-opacity duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-[var(--modal-overlay)] backdrop-blur-sm transition-opacity" onclick="closeProfileStepsModal()"></div>

    <!-- Modal Panel -->
    <div class="bg-[var(--modal-bg)] border border-[var(--modal-border)] w-full max-w-[480px] rounded-2xl shadow-2xl relative mx-4 animate-fade-in-up overflow-hidden pointer-events-auto">
        
        <!-- Close Button -->
        <button type="button" onclick="closeProfileStepsModal()" 
            class="absolute top-4 right-4 text-[var(--nav-muted)] hover:text-[var(--text-primary)] z-10 p-2 hover:bg-[var(--nav-dropdown-hover)] rounded-full transition-colors">
            <i data-feather="x" class="w-6 h-6"></i>
        </button>

        <!-- Modal Content -->
        <div class="pt-8 pb-6 px-8 text-center text-[var(--text-primary)]">
            
            <!-- Trust/Verification Illustration -->
            <div class="flex justify-center mb-6">
                <div class="relative w-20 h-20 bg-[var(--primary-accent)]/10 rounded-full flex items-center justify-center">
                    <!-- Shield Icon -->
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" class="fill-[var(--primary-accent)] stroke-[var(--primary-accent)]" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 12L11 14L15 10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
                    <!-- Star Badge Overlay -->
                    <div class="absolute -top-1 -right-1 bg-[var(--modal-bg)] p-1 rounded-full shadow-sm">
                        <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center text-white">
                            <i data-feather="star" class="w-3.5 h-3.5 fill-current"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-2">{{ __('tasks_page.before_offer_title') }}</h2>
            <p class="text-[var(--nav-muted)] text-[15px] leading-relaxed mb-6">
                {{ __('tasks_page.before_offer_desc') }}
            </p>

            <!-- Steps List -->
            @php
                $missingSteps = auth()->check() ? auth()->user()->getMissingProfileSteps() : [];
            @endphp
            <div class="space-y-2 mb-8 text-left">
                @foreach($missingSteps as $step)
                    <a href="{{ route('profile') }}" class="flex items-center justify-between py-2 group cursor-pointer hover:bg-[var(--nav-dropdown-hover)] rounded-xl px-2 transition-colors no-underline">
                        <div class="flex items-center gap-4">
                            <!-- Left Icon Circle -->
                            <div class="w-10 h-10 bg-[var(--nav-dropdown-hover)] text-[var(--nav-muted)] rounded-xl flex items-center justify-center group-hover:bg-[var(--primary-accent)] group-hover:text-white transition-all">
                                <i data-feather="{{ $step['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <!-- Text -->
                            <span class="text-[var(--text-primary)] font-medium text-[15px]">{{ $step['text'] }}</span>
                        </div>
                        
                        <!-- Right Plus Button -->
                        <div class="w-7 h-7 bg-[var(--modal-bg)] border border-[var(--nav-dropdown-border)] rounded-lg flex items-center justify-center text-[var(--nav-muted)] group-hover:border-[var(--primary-accent)] group-hover:text-[var(--primary-accent)] shadow-sm transition-all">
                            <i data-feather="plus" class="w-4 h-4"></i>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Action Button -->
            <div class="mt-2 text-center">
                <a href="{{ route('profile') }}" class="btn block w-full py-3 bg-[var(--primary-accent)] hover:bg-[var(--primary-hover)] text-white font-bold rounded-full transition-colors text-sm border-2 border-transparent">
                    {{ __('tasks_page.continue') }}
                </a>
            </div>
        </div>
    </div>
</div>

