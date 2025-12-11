@extends('layout')

@section('title', 'Post a Task')

@section('content')
<style>
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
    z-index: 10;
    opacity: 0;
    pointer-events: none;
    width: 0;
    height: 0;
    overflow: hidden;
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
  }
</style>


<div class="min-h-screen flex flex-col items-center bg-white">
  <div class="w-full max-w-5xl flex flex-col md:flex-row px-6 py-10">

    <!-- Sidebar -->
    <aside class="md:w-1/4 mb-8 md:mb-0">
      <h2 class="text-lg font-semibold mb-6 text-gray-800">Post a task</h2>
      <ul class="space-y-4 text-gray-500" id="sidebarSteps">
        <li class="font-semibold text-blue-800">Category & Date</li>
        <li>Location</li>
        <li>Details</li>
        <li>Budget</li>
      </ul>
    </aside>

    <!-- Main -->
    <section class="md:w-3/4 md:pl-10">
      <form id="postTaskForm" class="space-y-10" action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="task_type" id="input_task_type" value="{{ old('task_type', 'in-person') }}" />
        <input type="hidden" name="is_date_flexible" id="input_is_date_flexible" value="{{ old('is_date_flexible', '0') }}" />
        
        @if ($errors->any())
        <div class="rounded-md border border-red-200 bg-red-50 p-4 text-red-700">
          <div class="font-semibold mb-2">Please fix the following and try again:</div>
          <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <!-- STEP 1 -->
        <div id="step-1" class="step-pane">
          <h1 class="text-3xl font-bold text-blue-900 mb-8">Let's start with the basics</h1>
          
          <!-- Category and Job Selection -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                  <label for="categorySelect" class="block text-lg font-medium text-gray-800 mb-2">Category</label>
                  <select id="categorySelect" name="categories_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-600 outline-none transition">
                      <option value="">Select a Category</option>
                      @foreach($categories as $category)
                          <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
                  </select>
                  @error('categories_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                  @enderror
              </div>
              <div>
                  <label for="jobSelect" class="block text-lg font-medium text-gray-800 mb-2">Service (Job)</label>
                  <select id="jobSelect" name="jobs_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-600 outline-none transition" disabled>
                      <option value="">Select a Category first</option>
                  </select>
                  @error('jobs_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                  @enderror
              </div>
          </div>

          <div>
            <label for="taskDescription" class="block text-lg font-medium text-gray-800 mb-2">Task Title</label>
            <input id="taskDescription" name="title" type="text" placeholder="e.g. Help move my sofa" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-600 outline-none transition" />
            @error('title')
              <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mt-6">
            <label class="block text-lg font-medium text-gray-800 mb-4">When do you need this done?</label>
            <div class="flex flex-wrap gap-4">
              <div class="date-dropdown flex-1 min-w-[200px]">
                <button type="button" class="date-dropdown-btn" id="beforeDateBtn">
                  <span id="beforeDateLabel">Before date</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </button>
                <div class="date-dropdown-calendar" id="beforeDateCalendar">
                  <input type="date" name="required_before_date" class="w-full border-0 rounded-lg p-2" id="beforeDateValue" value="{{ old('required_before_date') }}" />
                </div>
              </div>
              <div class="date-dropdown flex-1 min-w-[200px]">
                <button type="button" class="date-dropdown-btn" id="onDateBtn">
                  <span id="onDateLabel">On date</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </button>
                <div class="date-dropdown-calendar" id="onDateCalendar">
                  <input type="date" name="required_date" class="w-full border-0 rounded-lg p-2" id="onDateValue" value="{{ old('required_date') }}" />
                </div>
              </div>
              <button type="button" class="pill-btn" data-option="flexible">I'm flexible</button>
            </div>
            @error('required_date')
              <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
            @error('required_before_date')
              <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
          </div>

          <div class="mt-8">
            <label class="flex items-center gap-2 text-lg font-medium text-gray-800 mb-4">
              <input type="checkbox" id="needTimeCheckbox" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
              <span>I need a certain time of day</span>
            </label>
            <!-- Note: name="preferred_time[]" allows multiple values to be sent as an array -->
            <div id="timeOfDayOptions" class="grid grid-cols-2 md:grid-cols-4 gap-4 hidden">
              <label class="time-option" data-time="morning">
                <input type="checkbox" name="preferred_time[]" value="morning" class="hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="2" x2="12" y2="9"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line><polyline points="8 6 12 2 16 6"></polyline></svg>
                <span class="font-semibold text-gray-800">Morning</span>
                <span class="text-sm text-gray-600">6am - 12pm</span>
              </label>
              <label class="time-option" data-time="midday">
                <input type="checkbox" name="preferred_time[]" value="midday" class="hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <span class="font-semibold text-gray-800">Midday</span>
                <span class="text-sm text-gray-600">12pm - 3pm</span>
              </label>
              <label class="time-option" data-time="afternoon">
                <input type="checkbox" name="preferred_time[]" value="afternoon" class="hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="9" x2="12" y2="2"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line><polyline points="16 5 12 9 8 5"></polyline></svg>
                <span class="font-semibold text-gray-800">Afternoon</span>
                <span class="text-sm text-gray-600">3pm - 6pm</span>
              </label>
              <label class="time-option" data-time="evening">
                <input type="checkbox" name="preferred_time[]" value="evening" class="hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                <span class="font-semibold text-gray-800">Evening</span>
                <span class="text-sm text-gray-600">6pm - 9pm</span>
              </label>
            </div>
          </div>
        </div>

      <!-- STEP 2 -->
      <div id="step-2" class="step-pane hidden">
        <h1 class="text-3xl font-bold text-blue-900 mb-8">Tell us where</h1>
        <div class="space-y-8">
          <div>
            <p class="text-lg font-medium text-gray-800 mb-6">How would you like this task to be done?</p>
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="location-option selected" id="inPersonOption">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <div class="title">In-person</div>
                <div class="description">Tasker comes to your location</div>
              </div>
              <div class="location-option" id="onlineOption">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                  <line x1="8" y1="21" x2="16" y2="21"></line>
                  <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <div class="title">Online</div>
                <div class="description">Task can be done remotely</div>
              </div>
            </div>
          </div>
        
          <div id="locationInputs" class="space-y-6">
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Where do you need this done?</label>
              <div class="relative">
                <input type="text" id="pickupSuburb" name="location" class="w-full border border-gray-300 rounded-lg p-3" placeholder="Enter a suburb" value="{{ old('location') }}" autocomplete="off" />
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
      <h1 class="text-3xl font-bold text-blue-900 mb-8">Add more details</h1>
      <div class="space-y-6">
          <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Task details</label>
              <textarea id="taskDetails" name="description" rows="6" class="w-full border border-gray-300 rounded-lg p-3" placeholder="Provide more information so Taskers can give accurate quotes">{{ old('description') }}</textarea>
              @error('description')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
              @enderror
          </div>
          <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Photos <span class="text-gray-500">(optional)</span></label>
              <div class="photo-upload-plus" id="photoUploadPlus">
                  <svg xmlns="http://www.w3.org/2000/svg" class="plus-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                  <div class="text">Add photos</div>
                  <div class="subtext">Click to upload images of your task</div>
              </div>
              <input type="file" id="photoInput" name="photos[]" multiple accept="image/*" class="hidden">
              <div class="photo-preview-container" id="photoPreviewContainer"></div>
          </div>
      </div>
  </div>

      <!-- STEP 4 -->
      <div id="step-4" class="step-pane hidden">
      <h1 class="text-3xl font-bold text-blue-900 mb-8">Suggest your budget</h1>
      <p class="text-lg font-medium text-gray-800">What is your budget?</p>
      <p class="text-gray-600 mb-4">You can always negotiate the final price.</p>
      <div class="flex items-stretch rounded-lg overflow-hidden border" id="budgetWrapper">
          <span class="px-4 flex items-center bg-gray-50 border-r text-gray-600">$</span>
          <input id="budgetInput" name="price" type="number" min="10" max="9999" class="flex-1 p-3 outline-none" placeholder="Enter budget" value="{{ old('price') }}">
      </div>
      <p id="budgetError" class="text-sm text-orange-600 mt-2 hidden">The price must be between $10 and $9999</p>
      @error('price')
        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
      @enderror
      </div>

      <!-- Nav Buttons -->
      <div class="flex items-center justify-between mt-10">
      <button type="button" id="backBtn" class="w-40 bg-blue-50 text-blue-700 font-semibold py-3 rounded-full disabled:opacity-50" disabled>Back</button>
      <div class="flex gap-3">
          <button type="button" id="nextBtn" class="w-40 bg-blue-600 text-white font-semibold py-3 rounded-full">Next</button>
          <button type="submit" id="submitBtn" class="w-40 bg-blue-600 text-white font-semibold py-3 rounded-full opacity-60 cursor-not-allowed hidden" disabled>Get quotes</button>
      </div>
  </div>
      </form>
    </section>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Categories Data
  const categoriesData = @json($categories);

  
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
  let photoInput = document.getElementById('photoInput');
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
    if (stepIndex === 0) {
      // Must have Task title AND Category AND Job
      ok = taskInput.value.trim().length > 0 && categorySelect.value !== "" && jobSelect.value !== "";
    } else if (stepIndex === 1) {
      const isInPerson = inPersonOption.classList.contains('selected');
      ok = !isInPerson || (isInPerson && pickupSuburb.value.trim().length > 0);
    } else if (stepIndex === 2) {
      ok = document.getElementById('taskDetails').value.trim().length > 0;
    } else if (stepIndex === 3) {
      const v = Number(budgetInput.value);
      ok = v >= 10 && v <= 9999;
      budgetError.classList.toggle('hidden', ok);
    }
    nextBtn.disabled = !ok;
    submitBtn.disabled = !ok;
    submitBtn.classList.toggle('opacity-60', !ok);
    submitBtn.classList.toggle('cursor-not-allowed', !ok);
  }

  taskInput.addEventListener('input', validateCurrent);
  categorySelect.addEventListener('change', validateCurrent);
  jobSelect.addEventListener('change', validateCurrent);
  pickupSuburb && pickupSuburb.addEventListener('input', validateCurrent);
  document.getElementById('taskDetails')?.addEventListener('input', validateCurrent);
  budgetInput && budgetInput.addEventListener('input', validateCurrent);

  backBtn.addEventListener('click', function(){
    if (stepIndex > 0) { stepIndex -= 1; showStep(stepIndex); }
  });
  nextBtn.addEventListener('click', function(){
    if (stepIndex < panes.length - 1) { stepIndex += 1; showStep(stepIndex); }
  });
  
  // --- JOB POPULATION LOGIC ---
  function populateJobs(catId, selectedJobId = null) {
      jobSelect.innerHTML = '<option value="">Select a Service</option>';
      jobSelect.disabled = true;
      
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

          jobSelect.disabled = false;
      }
      validateCurrent();
  }

  categorySelect.addEventListener('change', function() {
      populateJobs(this.value);
  });

  // --- PRE-SELECTION FROM URL ---
  const urlParams = new URLSearchParams(window.location.search);
  const preCat = urlParams.get('category'); // e.g. ?category=2
  const preService = urlParams.get('job') || urlParams.get('service'); // e.g. &job=5 or &service=5
  
  if (preCat) {
      categorySelect.value = preCat;
      // We pass the job ID to select it automatically
      populateJobs(preCat, preService);
  }

  // Form Submission
  const form = document.getElementById('postTaskForm');
  form.addEventListener('submit', function(e){
      // Update hidden fields
      const isOnline = onlineOption.classList.contains('selected');
      document.getElementById('input_task_type').value = isOnline ? 'online' : 'in-person';
      
      const flexibleActive = flexibleBtn && flexibleBtn.getAttribute('data-active') === 'true';
      document.getElementById('input_is_date_flexible').value = flexibleActive ? '1' : '0';
      
      if (submitBtn.disabled) {
          e.preventDefault();
          alert('Please complete all required fields correctly.');
      }
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
    onDateLabel.textContent = 'On date';
    beforeDateLabel.textContent = 'Before date';
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
      onDateLabel.textContent = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      onDateBtn.classList.add('active');
    }
  });
  
  onDateValue.addEventListener('click', function(e) { e.stopPropagation(); });

  beforeDateValue.addEventListener('change', function() {
    if (this.value) {
      const date = new Date(this.value + 'T00:00:00');
      beforeDateLabel.textContent = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      beforeDateBtn.classList.add('active');
    }
  });
  
  beforeDateValue.addEventListener('click', function(e) { e.stopPropagation(); });

  if (flexibleBtn) {
    flexibleBtn.addEventListener('click', function() {
      resetDateOptions();
      this.setAttribute('data-active','true');
    });
  }

  // Time of day checkbox and options
  const needTimeCheckbox = document.getElementById('needTimeCheckbox');
  const timeOfDayOptions = document.getElementById('timeOfDayOptions');
  const timeOptions = document.querySelectorAll('.time-option');

  needTimeCheckbox.addEventListener('change', function() {
    timeOfDayOptions.classList.toggle('hidden', !this.checked);
    if (!this.checked) {
        // Uncheck all hidden checkboxes and remove visual selection
        document.querySelectorAll('input[name="preferred_time[]"]').forEach(cb => cb.checked = false);
        timeOptions.forEach(opt => opt.classList.remove('selected'));
    }
  });

  // Multiple time selections
  timeOptions.forEach(option => {
    option.addEventListener('click', function(e) {
      const checkbox = this.querySelector('input[type="checkbox"]');
      checkbox.checked = !checkbox.checked;
      this.classList.toggle('selected', checkbox.checked);
    });
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
    validateCurrent();
  }

  inPersonOption.addEventListener('click', function(){
    setActive(inPersonOption, true);
    setActive(onlineOption, false);
    toggleWorkType();
  });

  onlineOption.addEventListener('click', function(){
    setActive(inPersonOption, false);
    setActive(onlineOption, true);
    toggleWorkType();
  });

  // Photo upload functionality
  function attachPhotoInputListener(inputEl) {
    inputEl.addEventListener('change', function(e) {
      const files = e.target.files;
      if (!files || files.length === 0) return;

      // Show preview container when at least one file is selected
      photoPreviewContainer.style.display = 'block';

      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.type && file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function(ev) {
            const photoPreview = document.createElement('div');
            photoPreview.className = 'photo-preview';
            photoPreview.innerHTML = `
              <img src="${ev.target.result}" alt="Preview">
              <div class="remove-photo" onclick="this.parentElement.remove()">×</div>
            `;
            photoPreviewContainer.appendChild(photoPreview);
          };
          reader.readAsDataURL(file);
        }
      }

      // After one selection, keep this input (with its FileList) for form submission
      // and create a fresh empty input for any further selections.
      const newInput = document.createElement('input');
      newInput.type = 'file';
      newInput.name = 'photos[]';
      newInput.multiple = true;
      newInput.accept = 'image/*';
      newInput.className = 'hidden';

      // Replace the reference so the plus button opens the newest empty input
      inputEl.parentNode.insertBefore(newInput, inputEl.nextSibling);
      attachPhotoInputListener(newInput);
      photoInput = newInput;
    });
  }

  // Initial wiring
  attachPhotoInputListener(photoInput);

  photoUploadPlus.addEventListener('click', function() {
    if (photoInput) {
      photoInput.click();
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