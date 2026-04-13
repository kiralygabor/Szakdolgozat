@extends('layout')
@section('title', __('post-task.title'))
@section('content')
<style>
  /* Remove number input arrows (spinners) */
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    margin: 0 !important;
  }
  input[type=number] {
    -moz-appearance: textfield !important;
  }

  .pill-btn {
    border: 1px solid #1e3a8a;
    color: #1e3a8a;
    border-radius: 9999px;
    padding: 0.5rem 1.25rem;
    transition: background-color .2s, color .2s, border-color .2s;
  }
  .pill-btn:hover, .pill-btn[data-active="true"] {
    background-color: #1e3a8a; color: #fff; border-color: #1e3a8a;
  }
  .date-dropdown {
    position: relative;
    display: inline-block;
  }
  .date-dropdown-btn {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    background: white;
    cursor: pointer;
    transition: all .2s;
    width: 100%;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .date-dropdown-btn:hover {
    border-color: #1e3a8a;
  }
  .date-dropdown-btn.active {
    background-color: #1e3a8a;
    color: #fff;
    border-color: #1e3a8a;
  }
  .date-dropdown-btn.active svg {
    stroke: #fff;
  }
  .date-dropdown-calendar {
    position: absolute;
    top: 100%;
    left: 0;
    margin-top: 0.5rem;
    z-index: 50;
    opacity: 0;
    pointer-events: none;
    width: 0;
    height: 0;
    overflow: hidden;
    transition: opacity 0.2s;
  }
  .date-dropdown-calendar.show {
    opacity: 1;
    pointer-events: auto;
    width: 100%;
    height: auto;
  }
  .time-option {
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1rem;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.5rem;
    position: relative;
  }
  .time-option:hover {
    border-color: #93c5fd;
    background-color: #eff6ff;
  }
  .time-option.selected {
    border-color: #1e3a8a;
    background-color: #dbeafe;
  }
  .time-option .icon {
    width: 2.5rem;
    height: 2.5rem;
    color: #1e3a8a;
  }
  .location-option {
    border: 2px solid #e5e7eb;
    border-radius: 1rem;
    padding: 2rem 1.5rem;
    cursor: pointer;
    transition: all .3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 1rem;
    flex: 1;
    min-width: 160px;
  }
  .location-option:hover {
    border-color: #93c5fd;
    background-color: #eff6ff;
    transform: translateY(-2px);
  }
  .location-option.selected {
    border-color: #1e3a8a;
    background-color: #dbeafe;
  }
  .location-option:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.4);
    border-color: #1e3a8a;
  }
  html.dark .location-option:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.4);
  }
  .location-option .icon {
    width: 3rem;
    height: 3rem;
    color: #1e3a8a;
  }
  .location-option .title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
  }
  .location-option .description {
    font-size: 0.875rem;
    color: #6b7280;
  }
  .photo-upload-plus {
    border: 2px dashed #d1d5db;
    border-radius: 1rem;
    padding: 3rem 2rem;
    cursor: pointer;
    transition: all .3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 1rem;
    background-color: #f9fafb;
  }
  .photo-upload-plus:hover {
    border-color: #1e3a8a;
    background-color: #eff6ff;
  }
  .photo-upload-plus:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.4);
    border-color: #1e3a8a;
  }
  html.dark .photo-upload-plus:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.4);
  }
  .photo-upload-plus .plus-icon {
    width: 4rem;
    height: 4rem;
    color: #6b7280;
    transition: all .3s;
  }
  .photo-upload-plus:hover .plus-icon {
    color: #1e3a8a;
    transform: scale(1.1);
  }
  .photo-upload-plus .text {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
  }
  .photo-upload-plus .subtext {
    font-size: 0.875rem;
    color: #6b7280;
  }
  .photo-preview-container {
    display: none;
    margin-top: 1rem;
  }
  .photo-preview {
    display: inline-block;
    position: relative;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
  }
  .photo-preview img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
  }
  .remove-photo {
    position: absolute;
    top: -0.5rem;
    right: -0.5rem;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 1.5rem;
    height: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.875rem;
    border: 2px solid white;
    transition: transform 0.2s ease;
  }
  .remove-photo:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.5);
    transform: scale(1.1);
  }
  .is-invalid {
    border-color: #dc3545 !important;
    animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both;
  }
  @keyframes shake {
    10%, 90% { transform: translate3d(-1px, 0, 0); }
    20%, 80% { transform: translate3d(2px, 0, 0); }
    30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
    40%, 60% { transform: translate3d(4px, 0, 0); }
  }
  .invalid-feedback-custom {
  text-align: left;
  color: #dc3545;
  font-size: 0.85rem;
  margin-top: 6px;
  margin-left: 4px;
  font-weight: 500;
}
 
  /* Dark Mode Overrides */
  html.dark .min-h-screen.bg-white { background-color: #0f172a !important; }
  html.dark .step-pane h1,
  html.dark aside h2,
  html.dark #sidebarSteps li,
  html.dark .block.text-lg.font-medium,
  html.dark .text-lg.font-medium.text-gray-800,
  html.dark .text-gray-600,
  html.dark p.text-lg.font-medium { color: #f8fafc !important; }
 
  html.dark .date-dropdown-btn { background: #1e293b !important; border-color: #334155 !important; color: #f8fafc !important; }
  html.dark .date-dropdown-btn.active { background-color: #6366f1 !important; border-color: #6366f1 !important; }
  html.dark .time-option { border-color: #334155 !important; background-color: #1e293b !important; }
  html.dark .time-option:hover { background-color: #334155 !important; border-color: #475569 !important; }
  html.dark .time-option.selected { border-color: #6366f1 !important; background-color: rgba(99,102,241,0.15) !important; }
  html.dark .location-option { border-color: #334155 !important; background-color: #1e293b !important; }
  html.dark .location-option:hover { background-color: #334155 !important; border-color: #475569 !important; }
  html.dark .location-option.selected { border-color: #6366f1 !important; background-color: rgba(99,102,241,0.15) !important; }
  html.dark .location-option .title { color: #f8fafc !important; }
  html.dark .location-option .description { color: #94a3b8 !important; }
  html.dark .photo-upload-plus { border-color: #334155 !important; background-color: #1e293b !important; }
  html.dark .photo-upload-plus:hover { background-color: #334155 !important; border-color: #475569 !important; }
  html.dark .photo-upload-plus .plus-icon { color: #94a3b8 !important; }
  html.dark .photo-upload-plus .text { color: #f8fafc !important; }
  html.dark .photo-upload-plus .subtext { color: #64748b !important; }
 
  /* Form Fields Dark Mode */
  html.dark #taskDescription,
  html.dark #categorySelect,
  html.dark #jobSelect,
  html.dark #pickupSuburb,
  html.dark #taskDetails,
  html.dark #budgetInput {
    background-color: #1e293b !important;
    border-color: #475569 !important;
    color: #f8fafc !important;
  }
  html.dark #budgetWrapper { border-color: #475569 !important; background-color: #1e293b !important; }
  html.dark #budgetWrapper span {
    background-color: #1e293b !important;
    border-color: #475569 !important;
    color: #94a3b8 !important;
  }
  html.dark #taskDetails::placeholder,
  html.dark #taskDescription::placeholder,
  html.dark #budgetInput::placeholder {
    color: #64748b !important;
  }
  html.dark #backBtn { background-color: #1e293b !important; color: #f8fafc !important; border: 1px solid #334155 !important; }
  html.dark .time-option .font-semibold.text-gray-800 { color: #f8fafc !important; }
  
  html.dark #pickupSuburbDropdown { background-color: #1e293b !important; border-color: #334155 !important; }
  html.dark #pickupSuburbDropdown > div { color: #f8fafc !important; border-color: #334155 !important; }
  html.dark #pickupSuburbDropdown > div:hover { background-color: #334155 !important; color: #ffffff !important; }
  html.dark .photo-upload-plus .subtext { color: #64748b !important; }
 
 
/* High Contrast mode overrides for Post a Task */
.high-contrast .step-pane h1,
.high-contrast aside h2,
.high-contrast #sidebarSteps li {
    color: #000000 !important;
}
 
.high-contrast .date-dropdown-btn {
    border: 2px solid #000000 !important;
    background-color: #ffffff !important;
    color: #000000 !important;
}
 
.high-contrast .date-dropdown-btn:hover,
.high-contrast .date-dropdown-btn.active {
    background-color: #000000 !important;
    color: #ffffff !important;
}
 
.high-contrast .date-dropdown-btn:hover *,
.high-contrast .date-dropdown-btn.active * {
    color: #ffffff !important;
}
 
.high-contrast .date-dropdown-btn.active svg {
    stroke: #ffffff !important;
}
 
.high-contrast .pill-btn {
    border: 2px solid #000000 !important;
    background-color: #ffffff !important;
    color: #000000 !important;
}
 
.high-contrast .pill-btn:hover,
.high-contrast .pill-btn[data-active="true"] {
    background-color: #000000 !important;
    color: #ffffff !important;
}
 
.high-contrast .pill-btn:hover *,
.high-contrast .pill-btn[data-active="true"] * {
    color: #ffffff !important;
}
 
.high-contrast .time-option,
.high-contrast .location-option {
    border: 2px solid #000000 !important;
    background-color: #ffffff !important;
}
 
.high-contrast .time-option:hover,
.high-contrast .time-option.selected,
.high-contrast .location-option:hover,
.high-contrast .location-option.selected {
    background-color: #000000 !important;
}
 
.high-contrast .time-option:hover *,
.high-contrast .time-option.selected *,
.high-contrast .location-option:hover *,
.high-contrast .location-option.selected * {
    color: #ffffff !important;
}
 
.high-contrast .time-option .icon,
.high-contrast .location-option .icon {
    color: #000000 !important;
}
 
.high-contrast .photo-upload-plus {
    border: 3px dashed #000000 !important;
    background-color: #ffffff !important;
}
 
.high-contrast .photo-upload-plus:hover {
    background-color: #000000 !important;
}
 
.high-contrast .photo-upload-plus:hover *,
.high-contrast .photo-upload-plus:hover .plus-icon {
    color: #ffffff !important;
}
 
.high-contrast .photo-upload-plus .plus-icon {
    color: #000000 !important;
}
 
.high-contrast #backBtn {
    background-color: #ffffff !important;
    color: #000000 !important;
    border: 2px solid #000000 !important;
}
 
.high-contrast #backBtn:hover {
    background-color: #000000 !important;
    color: #ffffff !important;
}
 
.high-contrast #nextBtn,
.high-contrast #submitBtn {
    background-color: #000000 !important;
    color: #ffffff !important;
    border: 2px solid #000000 !important;
}
 
.high-contrast #nextBtn:hover,
.high-contrast #submitBtn:hover {
    background-color: #ffffff !important;
    color: #000000 !important;
}
 
.high-contrast input:focus,
.high-contrast select:focus,
.high-contrast textarea:focus {
    border-color: #000000 !important;
    box-shadow: 0 0 0 3px #000000 !important;
    outline: 2px solid #000000 !important;
}
 
.high-contrast input[type="checkbox"] {
    accent-color: #000000 !important;
    border: 2px solid #000000 !important;
}

/* Fix information/alert boxes in High Contrast Mode */
.high-contrast .bg-blue-50,
.high-contrast .bg-red-50,
.high-contrast .bg-green-50,
.high-contrast .bg-amber-50 {
    background-color: #000000 !important;
    border: 4px solid #000000 !important;
    box-shadow: 0 0 0 2px #ffffff !important;
    color: #ffffff !important;
}

.high-contrast .bg-blue-50 *,
.high-contrast .bg-red-50 *,
.high-contrast .bg-green-50 *,
.high-contrast .bg-amber-50 * {
    color: #ffffff !important;
}
</style>
 
<div class="min-h-screen flex flex-col items-center bg-white">
<div class="w-full max-w-7xl flex flex-col md:flex-row px-6 py-10">
<!-- Sidebar -->
<aside class="md:w-1/5 mb-8 md:mb-0 md:border-r border-gray-200 md:pr-6">
<h2 class="text-lg font-semibold mb-6 text-gray-800 mt-2">{{ __('post-task.sidebar.post_task') }}</h2>
<ul class="space-y-4 text-gray-500" id="sidebarSteps">
<li class="font-semibold text-blue-800">{{ __('post-task.sidebar.category_date') }}</li>
<li>{{ __('post-task.sidebar.location') }}</li>
<li>{{ __('post-task.sidebar.details') }}</li>
<li>{{ __('post-task.sidebar.budget') }}</li>
</ul>
</aside>
<!-- Main -->
<section class="md:w-4/5 md:pl-10">
<form id="postTaskForm" action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
<input type="hidden" name="task_type" id="input_task_type" value="{{ old('task_type', 'in-person') }}" />
<input type="hidden" name="is_date_flexible" id="input_is_date_flexible" value="{{ old('is_date_flexible', '0') }}" />
        @if ($errors->any())
<div class="rounded-md border border-red-200 bg-red-50 p-4 text-red-700 mb-10">
<div class="font-semibold mb-2">{{ __('post-task.error_header') }}</div>
<ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
<li>{{ $error }}</li>
            @endforeach
</ul>
</div>
        @endif
       
        @if(isset($targetUser))
            <input type="hidden" name="employee_id" value="{{ $targetUser->id }}">
            <div class="mb-8 bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <img src="{{ $targetUser->avatar_url }}" alt="{{ $targetUser->first_name }}" class="w-12 h-12 rounded-full object-cover shadow-sm bg-white border border-blue-200">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm md:text-base">{{ __('post-task.requesting_quote_from') ?? 'Requesting a quote from' }} {{ $targetUser->first_name }}</h3>
                        <p class="text-xs md:text-sm text-gray-500">{{ __('post-task.requesting_quote_desc') ?? 'This task will be sent specifically to them.' }}</p>
                    </div>
                </div>
            </div>
        @endif
<!-- STEP 1 -->
<div id="step-1" class="step-pane">
<h1 class="text-3xl font-bold text-blue-900 mb-8">{{ __('post-task.step1.title') }}</h1>
<!-- Category and Job Selection -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
<div>
<label for="categorySelect" class="block text-lg font-medium text-gray-800 mb-2">{{ __('post-task.step1.category_label') }}</label>
<select id="categorySelect" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-600 outline-none transition">
<option value="">{{ __('post-task.step1.category_placeholder') }}</option>
                      @foreach($categories as $category)
<option value="{{ $category->id }}">{{ __('categories.' . $category->name) }}</option>
                      @endforeach
</select>
                  @error('categories_id')
<p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                  @enderror
</div>
<div>
<label for="jobSelect" class="block text-lg font-medium text-gray-800 mb-2">{{ __('post-task.step1.service_label') }}</label>
<select id="jobSelect" name="jobs_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-600 outline-none transition">
<option value="">{{ __('post-task.step1.service_placeholder_select') }}</option>
</select>
                  @error('jobs_id')
<p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                  @enderror
</div>
</div>
<div>
<label for="taskDescription" class="block text-lg font-medium text-gray-800 mb-2">{{ __('post-task.step1.task_title_label') }}</label>
<input id="taskDescription" name="title" type="text" placeholder="{{ __('post-task.step1.task_title_placeholder') }}" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-600 outline-none transition" />
            @error('title')
<p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
</div>
<div class="mt-6">
<label class="block text-lg font-medium text-gray-800 mb-4">{{ __('post-task.step1.date_label') }}</label>
<div class="flex flex-wrap gap-4">
<div class="date-dropdown flex-1 min-w-[200px]">
<button type="button" class="date-dropdown-btn" id="beforeDateBtn">
<span id="beforeDateLabel">{{ __('post-task.step1.before_date') }}</span>
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
</button>
<div class="date-dropdown-calendar" id="beforeDateCalendar">
<input type="date" name="required_before_date" class="w-full border-0 rounded-lg p-2" id="beforeDateValue" value="{{ old('required_before_date') }}" tabindex="-1" />
</div>
</div>
<div class="date-dropdown flex-1 min-w-[200px]">
<button type="button" class="date-dropdown-btn" id="onDateBtn">
<span id="onDateLabel">{{ __('post-task.step1.on_date') }}</span>
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
</button>
<div class="date-dropdown-calendar" id="onDateCalendar">
<input type="date" name="required_date" class="w-full border-0 rounded-lg p-2" id="onDateValue" value="{{ old('required_date') }}" tabindex="-1" />
</div>
</div>
<button type="button" class="pill-btn" data-option="flexible">{{ __('post-task.step1.flexible') }}</button>
</div>
            <p id="clientDateError" class="text-sm text-red-600 mt-2 hidden"></p>
            @error('required_date')
<p class="text-sm text-red-600 mt-2 server-date-error">{{ $message }}</p>
            @enderror
            @error('required_before_date')
<p class="text-sm text-red-600 mt-2 server-date-error">{{ $message }}</p>
            @enderror
</div>
<div class="mt-8">
<label class="flex items-center gap-2 text-lg font-medium text-gray-800 mb-4">
<input type="checkbox" id="needTimeCheckbox" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
<span>{{ __('post-task.step1.certain_time') }}</span>
</label>
<!-- Note: name="preferred_time[]" allows multiple values to be sent as an array -->
<div id="timeOfDayOptions" class="grid grid-cols-2 md:grid-cols-4 gap-4 hidden">
<label class="time-option" data-time="morning" tabindex="0">
<input type="checkbox" name="preferred_time[]" value="morning" class="hidden">
<svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="2" x2="12" y2="9"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line><polyline points="8 6 12 2 16 6"></polyline></svg>
<span class="font-semibold text-gray-800">{{ __('post-task.step1.morning') }}</span>
<span class="text-sm text-gray-600">{{ __('post-task.step1.morning_range') }}</span>
</label>
<label class="time-option" data-time="midday" tabindex="0">
<input type="checkbox" name="preferred_time[]" value="midday" class="hidden">
<svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
<span class="font-semibold text-gray-800">{{ __('post-task.step1.midday') }}</span>
<span class="text-sm text-gray-600">{{ __('post-task.step1.midday_range') }}</span>
</label>
<label class="time-option" data-time="afternoon" tabindex="0">
<input type="checkbox" name="preferred_time[]" value="afternoon" class="hidden">
<svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="9" x2="12" y2="2"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line><polyline points="16 5 12 9 8 5"></polyline></svg>
<span class="font-semibold text-gray-800">{{ __('post-task.step1.afternoon') }}</span>
<span class="text-sm text-gray-600">{{ __('post-task.step1.afternoon_range') }}</span>
</label>
<label class="time-option" data-time="evening" tabindex="0">
<input type="checkbox" name="preferred_time[]" value="evening" class="hidden">
<svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
<span class="font-semibold text-gray-800">{{ __('post-task.step1.evening') }}</span>
<span class="text-sm text-gray-600">{{ __('post-task.step1.evening_range') }}</span>
</label>
</div>
</div>
</div>
<!-- STEP 2 -->
<div id="step-2" class="step-pane hidden">
<h1 class="text-3xl font-bold text-blue-900 mb-8">{{ __('post-task.step2.title') }}</h1>
<div class="space-y-8">
<div>
<p class="text-lg font-medium text-gray-800 mb-6">{{ __('post-task.step2.question') }}</p>
<div class="flex flex-col sm:flex-row gap-4">
<div class="location-option selected" id="inPersonOption" tabindex="0" role="radio" aria-checked="true">
<svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
<circle cx="12" cy="10" r="3"></circle>
</svg>
<div class="title">{{ __('post-task.step2.in_person') }}</div>
<div class="description">{{ __('post-task.step2.in_person_desc') }}</div>
</div>
<div class="location-option" id="onlineOption" tabindex="0" role="radio" aria-checked="false">
<svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
<rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
<line x1="8" y1="21" x2="16" y2="21"></line>
<line x1="12" y1="17" x2="12" y2="21"></line>
</svg>
<div class="title">{{ __('post-task.step2.online') }}</div>
<div class="description">{{ __('post-task.step2.online_desc') }}</div>
</div>
</div>
</div>
<div id="locationInputs" class="space-y-6">
<div>
<label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('post-task.step2.location_label') }}</label>
<div class="relative">
<input type="text" id="pickupSuburb" name="location" class="w-full border border-gray-300 rounded-lg p-3" placeholder="{{ __('post-task.step2.location_placeholder') }}" value="{{ old('location') }}" autocomplete="off" />
<div id="pickupSuburbDropdown" class="absolute left-0 right-0 mt-1 max-h-40 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10"></div>
</div>
              @error('location')
<p class="text-sm text-red-600 mt-1">{{ $message }}</p>
              @enderror
</div>
</div>
</div>
</div>
<!-- STEP 3 -->
<div id="step-3" class="step-pane hidden">
<h1 class="text-3xl font-bold text-blue-900 mb-8">{{ __('post-task.step3.title') }}</h1>
<div class="space-y-6">
<div>
<label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('post-task.step3.details_label') }}</label>
<textarea id="taskDetails" name="description" rows="6" class="w-full border border-gray-300 rounded-lg p-3" placeholder="{{ __('post-task.step3.details_placeholder') }}">{{ old('description') }}</textarea>
              @error('description')
<p class="text-sm text-red-600 mt-1">{{ $message }}</p>
              @enderror
</div>
<div>
<label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('post-task.step3.photos_label') }} <span class="text-gray-500">{{ __('post-task.step3.photos_optional') }}</span></label>
<div class="photo-upload-plus" id="photoUploadPlus" tabindex="0" role="button" aria-label="{{ __('post-task.step3.add_photos') }}">
<svg xmlns="http://www.w3.org/2000/svg" class="plus-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
<line x1="12" y1="5" x2="12" y2="19"></line>
<line x1="5" y1="12" x2="19" y2="12"></line>
</svg>
<div class="text">{{ __('post-task.step3.add_photos') }}</div>
<div class="subtext">{{ __('post-task.step3.add_photos_desc') }}</div>
</div>
<input type="file" id="photoSelectorInput" multiple accept="image/*" class="hidden">
<input type="file" id="photoSubmissionInput" name="photos[]" multiple class="hidden">
<div class="photo-preview-container" id="photoPreviewContainer"></div>
</div>
</div>
</div>
<!-- STEP 4 -->
<div id="step-4" class="step-pane hidden">
<h1 class="text-3xl font-bold text-blue-900 mb-8">{{ __('post-task.step4.title') }}</h1>
<p class="text-lg font-medium text-gray-800">{{ __('post-task.step4.budget_question') }}</p>
<p class="text-gray-600 mb-4">{{ __('post-task.step4.negotiable') }}</p>
<div class="flex items-stretch rounded-lg overflow-hidden border @error('price') is-invalid @enderror" id="budgetWrapper">
<span class="px-4 flex items-center bg-gray-50 border-r text-gray-600">€</span>
<input id="budgetInput" name="price" type="number" min="5" max="5000" class="flex-1 p-3 outline-none" placeholder="{{ __('post-task.step4.budget_placeholder') }}" value="{{ old('price') }}">
</div>
<div id="budgetError" class="invalid-feedback-custom hidden">
        {{ __('post-task.step4.budget_error') }}
</div>
      @error('price')
<div class="invalid-feedback-custom server-error">
          {{ $message }}
</div>
      @enderror
</div>
<!-- Nav Buttons -->
<div class="flex items-center justify-between mt-10">
<button type="button" id="backBtn" class="w-40 bg-blue-50 text-blue-700 font-semibold py-3 rounded-full disabled:opacity-50" disabled>{{ __('post-task.nav.back') }}</button>
<div class="flex gap-3">
<button type="button" id="nextBtn" class="w-40 bg-blue-600 text-white font-semibold py-3 rounded-full opacity-60 cursor-not-allowed" disabled>{{ __('post-task.nav.next') }}</button>
<button type="submit" id="submitBtn" class="w-40 bg-blue-600 text-white font-semibold py-3 rounded-full opacity-60 cursor-not-allowed hidden" disabled>{{ __('post-task.nav.get_quotes') }}</button>
</div>
</div>
</form>
</section>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Categories Data
  const categoriesData = @json($categories->map(function($cat) {
      $cat->name = __('categories.' . $cat->name);
      if ($cat->jobs) {
          $cat->jobs = $cat->jobs->map(function($job) {
              $job->name = __('jobs.' . $job->name);
              return $job;
          });
      }
      return $cat;
  })); 
  // Step handling
  const panes = [
    document.getElementById('step-1'),
    document.getElementById('step-2'),
    document.getElementById('step-3'),
    document.getElementById('step-4'),
  ];
  const backBtn = document.getElementById('backBtn');
  const nextBtn = document.getElementById('nextBtn');
  const submitBtn = document.getElementById('submitBtn');
  let stepIndex = 0;
  let isLocationSelected = false;
  // Inputs
  const categorySelect = document.getElementById('categorySelect');
  const jobSelect = document.getElementById('jobSelect');
  const taskInput = document.getElementById('taskDescription');
  const dateOptions = document.querySelectorAll('button.pill-btn[data-option]');
  const inPersonOption = document.getElementById('inPersonOption');
  const onlineOption = document.getElementById('onlineOption');
  const pickupSuburb = document.getElementById('pickupSuburb');
  const pickupSuburbDropdown = document.getElementById('pickupSuburbDropdown');
  const budgetInput = document.getElementById('budgetInput');
  const budgetError = document.getElementById('budgetError');
  // Photo upload elements
  const photoUploadPlus = document.getElementById('photoUploadPlus');
  const photoPreviewContainer = document.getElementById('photoPreviewContainer');
  function setActive(el, active) {
    if (!el) return;
    el.classList.toggle('selected', active);
  }
  function showStep(i){
    panes.forEach((p, idx) => p.classList.toggle('hidden', idx !== i));
    backBtn.disabled = i === 0;
    nextBtn.classList.toggle('hidden', i === panes.length - 1);
    submitBtn.classList.toggle('hidden', i !== panes.length - 1);
    updateSidebar(i);
    validateCurrent();
    if (window.feather && typeof window.feather.replace === 'function') feather.replace();
  }
  function updateSidebar(i){
    const items = document.querySelectorAll('#sidebarSteps li');
    items.forEach((li, idx) => {
      const isActive = idx === i;
      li.classList.toggle('font-semibold', isActive);
      li.classList.toggle('text-blue-800', isActive);
      li.classList.toggle('text-gray-500', !isActive);
    });
  }
  function validateCurrent(){
    let ok = true;
    let budgetErrorTimeout;
    if (stepIndex === 0) {
      const titleOk = taskInput.value.trim().length > 0;
      const categoryOk = categorySelect.value !== "";
      const jobOk = jobSelect.value !== "";
     
      const onDate = document.getElementById('onDateValue').value;
      const beforeDate = document.getElementById('beforeDateValue').value;
      const isFlexible = flexibleBtn && flexibleBtn.getAttribute('data-active') === 'true';
      const dateOk = onDate !== "" || beforeDate !== "" || isFlexible;
 
      ok = titleOk && categoryOk && jobOk && dateOk;
    } else if (stepIndex === 1) {
      const isInPerson = inPersonOption.classList.contains('selected');
      ok = !isInPerson || (isInPerson && isLocationSelected);
    } else if (stepIndex === 2) {
      ok = document.getElementById('taskDetails').value.trim().length > 0;
    } else if (stepIndex === 3) {
  const val = budgetInput.value.trim();
  const budgetWrapper = document.getElementById('budgetWrapper');
  const serverError = budgetWrapper.parentElement.querySelector('.server-error');
  // 1. Always clear the timer immediately on every keystroke
  clearTimeout(budgetErrorTimeout);
  // 2. Hide server error if user starts typing
  if (serverError) serverError.classList.add('hidden');
  if (val === "") {
    ok = false;
    budgetError.classList.add('hidden');
    budgetWrapper.classList.remove('is-invalid');
  } else {
    const n = Number(val);
    const isValidRange = (n >= 5 && n <= 5000);
    if (isValidRange) {
      // INSTANT: If valid, hide error immediately and enable button
      ok = true;
      budgetError.classList.add('hidden');
      budgetWrapper.classList.remove('is-invalid');
    } else {
      // INSTANT: Disable button so they can't submit a bad value
      ok = false;
      // DELAYED: Wait to show the red error message
      budgetErrorTimeout = setTimeout(() => {
        // RE-VERIFY: Check the value again AFTER the delay
        const currentVal = budgetInput.value.trim();
        const currentN = Number(currentVal);
        const stillInvalid = currentVal !== "" && (currentN < 5 || currentN > 5000);
        if (stillInvalid) {
          budgetError.classList.remove('hidden');
          budgetWrapper.classList.add('is-invalid');
        }
      }, 800);
    }
  }
}
    nextBtn.disabled = !ok;
    nextBtn.classList.toggle('opacity-60', !ok);
    nextBtn.classList.toggle('cursor-not-allowed', !ok);
   
    submitBtn.disabled = !ok;
    submitBtn.classList.toggle('opacity-60', !ok);
    submitBtn.classList.toggle('cursor-not-allowed', !ok);
  }
  taskInput.addEventListener('input', validateCurrent);
  categorySelect.addEventListener('change', validateCurrent);
  jobSelect.addEventListener('change', validateCurrent);
  pickupSuburb && pickupSuburb.addEventListener('input', () => {
    isLocationSelected = false;
    validateCurrent();
  });
  document.getElementById('taskDetails')?.addEventListener('input', validateCurrent);
  budgetInput && budgetInput.addEventListener('input', validateCurrent);
  backBtn.addEventListener('click', function(){
    if (stepIndex > 0) { stepIndex -= 1; showStep(stepIndex); }
  });
  function hideDateError() {
    const err = document.getElementById('clientDateError');
    if (err) err.classList.add('hidden');
    document.querySelectorAll('.server-date-error').forEach(e => e.classList.add('hidden'));
  }
  function showDateError(msg) {
    const err = document.getElementById('clientDateError');
    if (err) {
      err.textContent = msg;
      err.classList.remove('hidden');
    }
  }

  nextBtn.addEventListener('click', function(e){
    if (stepIndex === 0) {
      const onDate = onDateValue.value;
      const beforeDate = beforeDateValue.value;
      const isFlexible = flexibleBtn && flexibleBtn.getAttribute('data-active') === 'true';

      if (!isFlexible) {
        const dStr = onDate || beforeDate;
        if (dStr) {
          const d = new Date(dStr + 'T00:00:00');
          const today = new Date();
          today.setHours(0,0,0,0);
          if (d < today) {
            showDateError("{{ __('validation.after_or_equal', ['attribute' => 'date', 'date' => 'today']) }}");
            return;
          }
        }
      }
      hideDateError();
    }
    if (stepIndex < panes.length - 1) { stepIndex += 1; showStep(stepIndex); }
  });
  // --- JOB POPULATION LOGIC ---
  function populateJobs(catId, selectedJobId = null) {
      jobSelect.innerHTML = '<option value="">{{ __('post-task.step1.service_placeholder_select') }}</option>';
      if (!catId) return;
      const category = categoriesData.find(c => c.id == catId);
      const uniqueJobs = new Map(); // Using Map to track unique jobs by ID
      if (category && category.jobs && category.jobs.length > 0) {
          // Collect unique jobs
          category.jobs.forEach(job => {
              if (!uniqueJobs.has(job.id)) {
                  uniqueJobs.set(job.id, job);
              }
          });
          // Add unique jobs to dropdown
          uniqueJobs.forEach(job => {
              const option = document.createElement('option');
              option.value = job.id;
              option.textContent = job.name;
              if (selectedJobId && job.id == selectedJobId) {
                  option.selected = true;
              }
              jobSelect.appendChild(option);
          });
      }
      validateCurrent();
  }
  categorySelect.addEventListener('change', function() {
      populateJobs(this.value);
  });
  // --- RESTORE OLD SELECTION OR PRE-SELECTION FROM URL ---
  @php
      $oldJobId = old('jobs_id');
      $oldCategoryId = '';
      if ($oldJobId) {
          foreach($categories as $cat) {
              foreach($cat->jobs as $job) {
                  if ($job->id == $oldJobId) {
                      $oldCategoryId = $cat->id;
                      break 2;
                  }
              }
          }
      }
  @endphp
 
  const oldCatId = "{{ $oldCategoryId }}";
  const oldJobId = "{{ $oldJobId }}";
 
  const urlParams = new URLSearchParams(window.location.search);
  const preCat = urlParams.get('category'); // e.g. ?category=2
  const preService = urlParams.get('job') || urlParams.get('service'); // e.g. &job=5 or &service=5
 
  if (oldCatId) {
      categorySelect.value = oldCatId;
      populateJobs(oldCatId, oldJobId);
  } else if (preCat) {
      categorySelect.value = preCat;
      // We pass the job ID to select it automatically
      populateJobs(preCat, preService);
  }
  // Form Submission
  const form = document.getElementById('postTaskForm');
  form.addEventListener('submit', function(e){
      if (submitBtn.disabled) {
          e.preventDefault();
          return;
      }
      // Update hidden fields
      const isOnline = onlineOption.classList.contains('selected');
      document.getElementById('input_task_type').value = isOnline ? 'online' : 'in-person';
      const flexibleActive = flexibleBtn && flexibleBtn.getAttribute('data-active') === 'true';
      document.getElementById('input_is_date_flexible').value = flexibleActive ? '1' : '0';
 
      // Disable button to prevent double-click
      submitBtn.disabled = true;
      submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
      submitBtn.innerHTML = '<i data-feather="loader" class="animate-spin w-4 h-4 inline mr-2"></i> {{ __('post-task.nav.submitting') ?? 'Submitting...' }}';
      if (window.feather) feather.replace();
  });
  // Date dropdown functionality
  const onDateBtn = document.getElementById('onDateBtn');
  const onDateCalendar = document.getElementById('onDateCalendar');
  const onDateValue = document.getElementById('onDateValue');
  const onDateLabel = document.getElementById('onDateLabel');
  const beforeDateBtn = document.getElementById('beforeDateBtn');
  const beforeDateCalendar = document.getElementById('beforeDateCalendar');
  const beforeDateValue = document.getElementById('beforeDateValue');
  const beforeDateLabel = document.getElementById('beforeDateLabel');
  const flexibleBtn = document.querySelector('[data-option="flexible"]');
  function resetDateOptions() {
    onDateBtn.classList.remove('active');
    beforeDateBtn.classList.remove('active');
    if (flexibleBtn) {
      flexibleBtn.setAttribute('data-active', 'false');
    }
    onDateValue.value = '';
    beforeDateValue.value = '';
    onDateLabel.textContent = "{{ __('post-task.step1.on_date') }}";
    beforeDateLabel.textContent = "{{ __('post-task.step1.before_date') }}";
    hideDateError();
    validateCurrent();
  }
  onDateBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    resetDateOptions();
    onDateBtn.classList.add('active');
    setTimeout(() => { onDateValue.showPicker(); }, 50);
  });
  beforeDateBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    resetDateOptions();
    beforeDateBtn.classList.add('active');
    setTimeout(() => { beforeDateValue.showPicker(); }, 50);
  });
  onDateValue.addEventListener('change', function() {
    if (this.value) {
      const date = new Date(this.value + 'T00:00:00');
      onDateLabel.textContent = date.toLocaleDateString('{{ app()->getLocale() == 'hu' ? 'hu-HU' : 'en-US' }}', { month: 'short', day: 'numeric', year: 'numeric' });
      onDateBtn.classList.add('active');
      validateCurrent();
    }
  });
  onDateValue.addEventListener('click', function(e) { e.stopPropagation(); });
  beforeDateValue.addEventListener('change', function() {
    if (this.value) {
      const date = new Date(this.value + 'T00:00:00');
      beforeDateLabel.textContent = date.toLocaleDateString('{{ app()->getLocale() == 'hu' ? 'hu-HU' : 'en-US' }}', { month: 'short', day: 'numeric', year: 'numeric' });
      beforeDateBtn.classList.add('active');
      validateCurrent();
    }
  });
  beforeDateValue.addEventListener('click', function(e) { e.stopPropagation(); });
  if (flexibleBtn) {
    flexibleBtn.addEventListener('click', function() {
      resetDateOptions();
      this.setAttribute('data-active','true');
      validateCurrent();
    });
  }
  // Time of day checkbox and options
  const needTimeCheckbox = document.getElementById('needTimeCheckbox');
  const timeOfDayOptions = document.getElementById('timeOfDayOptions');
  const timeOptions = document.querySelectorAll('.time-option');
  needTimeCheckbox.addEventListener('click', function() {
    timeOfDayOptions.classList.toggle('hidden', !this.checked);
    if (!this.checked) {
        // Uncheck all hidden checkboxes and remove visual selection
        document.querySelectorAll('input[name="preferred_time[]"]').forEach(cb => cb.checked = false);
        timeOptions.forEach(opt => opt.classList.remove('selected'));
    }
  });
 
  // Enable Enter key for toggling the checkbox (standard is Space)
  needTimeCheckbox.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault(); // Stop form submission
      this.click();       // Trigger the toggle logic
    }
  });
  // Multiple time selections
  timeOptions.forEach(option => {
    const checkbox = option.querySelector('input[type="checkbox"]');
   
    // Listen for change on the input itself (better for accessibility)
    checkbox.addEventListener('change', function() {
      option.classList.toggle('selected', this.checked);
    });
 
    // Toggle on Enter/Space if focused
    option.addEventListener('keydown', function(e) {
      if (e.key === ' ' || e.key === 'Enter') {
        e.preventDefault();
        checkbox.checked = !checkbox.checked;
        checkbox.dispatchEvent(new Event('change'));
      }
    });
 
    // Clicking the label already triggers the checkbox, so we just let it happen naturally
    // BUT we need to make sure we don't have a manual toggle here that doubles it up.
    // The previous code had a manual toggle on the 'option' (label) click.
  });
  // Suburb / city autocomplete (uses /api/cities like tasks search)
  let suburbSearchTimeout;
  if (pickupSuburb && pickupSuburbDropdown) {
    pickupSuburb.addEventListener('input', (e) => {
      clearTimeout(suburbSearchTimeout);
      const q = e.target.value.trim();
      if (q.length < 2) {
        pickupSuburbDropdown.classList.add('hidden');
        pickupSuburbDropdown.innerHTML = '';
        return;
      }
      suburbSearchTimeout = setTimeout(async () => {
        try {
          const res = await fetch(`/api/cities?q=${encodeURIComponent(q)}`);
          const cities = await res.json();
          pickupSuburbDropdown.innerHTML = '';
          if (!cities || !cities.length) {
            pickupSuburbDropdown.classList.add('hidden');
            return;
          }
          cities.slice(0, 8).forEach((c) => {
            const div = document.createElement('div');
            div.className = 'px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer text-gray-700';
            div.textContent = c.name;
            div.onclick = () => {
              pickupSuburb.value = c.name;
              isLocationSelected = true;
              pickupSuburbDropdown.classList.add('hidden');
              pickupSuburbDropdown.innerHTML = '';
              validateCurrent();
            };
            pickupSuburbDropdown.appendChild(div);
          });
          pickupSuburbDropdown.classList.remove('hidden');
        } catch (err) {
          pickupSuburbDropdown.classList.add('hidden');
        }
      }, 300);
    });
    // Hide dropdown when clicking outside
    document.addEventListener('click', (event) => {
      if (!pickupSuburbDropdown.contains(event.target) && event.target !== pickupSuburb) {
        pickupSuburbDropdown.classList.add('hidden');
      }
    });
  }
  // Location type selection
  function toggleWorkType(){
    const isOnline = onlineOption.classList.contains('selected');
    document.getElementById('locationInputs').classList.toggle('hidden', isOnline);
    
    // Update ARIA
    inPersonOption.setAttribute('aria-checked', !isOnline);
    onlineOption.setAttribute('aria-checked', isOnline);
    
    validateCurrent();
  }
  
  function handleLocationSelect(isOnline) {
    setActive(inPersonOption, !isOnline);
    setActive(onlineOption, isOnline);
    toggleWorkType();
  }

  inPersonOption.addEventListener('click', () => handleLocationSelect(false));
  onlineOption.addEventListener('click', () => handleLocationSelect(true));

  // Keydown handlers
  [inPersonOption, onlineOption].forEach(el => {
    el.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            handleLocationSelect(el.id === 'onlineOption');
        }
    });
  });
  // Photo upload functionality - FIXED
  const photoSelectorInput = document.getElementById('photoSelectorInput');
  const photoSubmissionInput = document.getElementById('photoSubmissionInput');
  let allPhotos = [];
  function updateSubmissionFiles() {
      const dt = new DataTransfer();
      allPhotos.forEach(item => dt.items.add(item.file));
      photoSubmissionInput.files = dt.files;
  }
  function renderPreviews() {
      photoPreviewContainer.innerHTML = '';
      if (allPhotos.length === 0) {
          photoPreviewContainer.style.display = 'none';
      } else {
          photoPreviewContainer.style.display = 'block';
      }
      allPhotos.forEach((item, index) => {
          const div = document.createElement('div');
          div.className = 'photo-preview';
          div.innerHTML = `
<img src="${item.url}" alt="Preview">
<div class="remove-photo" tabindex="0" role="button" aria-label="Remove photo">×</div>
          `;
          const remover = div.querySelector('.remove-photo');
          const removeAction = (e) => {
              e.stopPropagation();
              // Remove item
              allPhotos.splice(index, 1);
              // Update state
              updateSubmissionFiles();
              renderPreviews();
          };
          remover.addEventListener('click', removeAction);
          remover.addEventListener('keydown', (e) => {
              if (e.key === 'Enter' || e.key === ' ') {
                  e.preventDefault();
                  removeAction(e);
              }
          });
          photoPreviewContainer.appendChild(div);
      });
  }
  if (photoSelectorInput) {
      photoSelectorInput.addEventListener('change', function(e) {
          const files = Array.from(e.target.files || []);
          if (files.length === 0) return;
          files.forEach(file => {
              if (file.type && file.type.startsWith('image/')) {
                  allPhotos.push({
                      file: file,
                      url: URL.createObjectURL(file)
                  });
              }
          });
          updateSubmissionFiles();
          renderPreviews();
          // Clear selector so same file can be selected again if needed
          this.value = '';
      });
  }
  photoUploadPlus.addEventListener('click', function() {
      if (photoSelectorInput) {
        photoSelectorInput.click();
      }
  });
  photoUploadPlus.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.click();
      }
  });
  // Initialize initial state from old() values
  (function initializeFromOldValues(){
    const initialTaskType = document.getElementById('input_task_type').value;
    if (initialTaskType === 'online') {
      setActive(inPersonOption, false);
      setActive(onlineOption, true);
    } else {
      setActive(inPersonOption, true);
      setActive(onlineOption, false);
    }
    toggleWorkType();
    if (pickupSuburb && pickupSuburb.value.trim() !== '') {
        isLocationSelected = true;
    }
    const oldCat = "{{ old('categories_id') }}";
    const oldJob = "{{ old('jobs_id') }}";
    if (oldCat) {
        categorySelect.value = oldCat;
        populateJobs(oldCat, oldJob);
    }
    const isFlexible = document.getElementById('input_is_date_flexible').value === '1';
    if (isFlexible && flexibleBtn) {
      flexibleBtn.setAttribute('data-active','true');
    }
    if (onDateValue.value) {
      const d = new Date(onDateValue.value + 'T00:00:00');
      onDateLabel.textContent = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      onDateBtn.classList.add('active');
    }
    if (beforeDateValue.value) {
      const d2 = new Date(beforeDateValue.value + 'T00:00:00');
      beforeDateLabel.textContent = d2.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      beforeDateBtn.classList.add('active');
    }
    // Restore preferred times
    const oldPreferredTime = @json(old('preferred_time', []));
    if (oldPreferredTime && oldPreferredTime.length > 0) {
        needTimeCheckbox.checked = true;
        timeOfDayOptions.classList.remove('hidden');
        oldPreferredTime.forEach(time => {
            const el = document.querySelector(`.time-option[data-time="${time}"]`);
            if (el) {
                el.classList.add('selected');
                const cb = el.querySelector('input[type="checkbox"]');
                if (cb) cb.checked = true;
            }
        });
    }
  })();
  if (typeof feather !== 'undefined') feather.replace();
  showStep(stepIndex);
});
</script>
@endsection