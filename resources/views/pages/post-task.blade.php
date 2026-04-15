@extends('layout')
@section('title', __('post-task.title'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/post-task.css') }}">
@endpush

@section('content')
<div class="post-task-container details-main-bg min-h-screen">
  <div class="w-full max-w-7xl flex flex-col md:flex-row px-6 py-10 mx-auto">
    <!-- Sidebar -->
    <aside class="md:w-1/5 mb-8 md:mb-0 md:border-r details-border-color md:pr-6">
      <h2 class="text-lg font-bold mb-6 details-text-main mt-2 uppercase tracking-widest text-[11px]">
        {{ __('post-task.sidebar.post_task') }}
      </h2>
      <ul class="space-y-4 details-text-muted text-sm font-medium" id="sidebarSteps">
        <li class="font-bold details-text-main border-l-4 border-[var(--primary-accent)] pl-3 -ml-4">
          {{ __('post-task.sidebar.category_date') }}
        </li>
        <li class="pl-3">{{ __('post-task.sidebar.location') }}</li>
        <li class="pl-3">{{ __('post-task.sidebar.details') }}</li>
        <li class="pl-3">{{ __('post-task.sidebar.budget') }}</li>
      </ul>
    </aside>

    <!-- Main -->
    <section class="md:w-4/5 md:pl-10">
      <form id="postTaskForm" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="post-task-form">
        @csrf
        <input type="hidden" name="task_type" id="input_task_type" value="{{ old('task_type', 'in-person') }}" />
        <input type="hidden" name="is_date_flexible" id="input_is_date_flexible" value="{{ old('is_date_flexible', '0') }}" />

        @if ($errors->any())
          <div class="details-alert-error p-4 mb-10">
            <div class="font-bold mb-2 flex items-center gap-2">
              <i data-feather="alert-circle" class="w-4 h-4"></i>
              {{ __('post-task.error_header') }}
            </div>
            <ul class="list-disc pl-8 space-y-1 text-sm font-medium">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(isset($targetUser))
          <input type="hidden" name="employee_id" value="{{ $targetUser->id }}">
          <div class="mb-8 details-info-box p-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
              <img src="{{ $targetUser->avatar_url }}" alt="{{ $targetUser->first_name }}" 
                   class="w-12 h-12 rounded-full object-cover shadow-sm bg-white border details-border-color">
              <div>
                <h3 class="font-bold details-text-main text-sm md:text-base">
                  {{ __('post-task.requesting_quote_from') ?? 'Requesting a quote from' }} {{ $targetUser->first_name }}
                </h3>
                <p class="text-xs md:text-sm details-text-muted">
                  {{ __('post-task.requesting_quote_desc') ?? 'This task will be sent specifically to them.' }}
                </p>
              </div>
            </div>
          </div>
        @endif

        <!-- STEP 1: Category & Date -->
        <div id="step-1" class="step-pane">
          <h1 class="text-3xl font-extrabold details-text-main mb-8">{{ __('post-task.step1.title') }}</h1>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label for="categorySelect" class="block text-sm font-bold details-text-main mb-2">
                {{ __('post-task.step1.category_label') }}
              </label>
              <select id="categorySelect" name="categories_id" class="w-full rounded-lg p-3 outline-none transition">
                <option value="">{{ __('post-task.step1.category_placeholder') }}</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}">{{ __('categories.' . $category->name) }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label for="jobSelect" class="block text-sm font-bold details-text-main mb-2">
                {{ __('post-task.step1.service_label') }}
              </label>
              <select id="jobSelect" name="jobs_id" class="w-full rounded-lg p-3 outline-none transition">
                <option value="">{{ __('post-task.step1.service_placeholder_select') }}</option>
              </select>
            </div>
          </div>

          <div class="mb-8">
            <label for="taskDescription" class="block text-sm font-bold details-text-main mb-2">
              {{ __('post-task.step1.task_title_label') }}
            </label>
            <input id="taskDescription" name="title" type="text" placeholder="{{ __('post-task.step1.task_title_placeholder') }}" 
                   value="{{ old('title') }}" class="w-full rounded-lg p-3 outline-none transition" />
          </div>

          <div class="mt-6">
            <label class="block text-sm font-bold details-text-main mb-4">{{ __('post-task.step1.date_label') }}</label>
            <div class="flex flex-wrap gap-4">
              <div class="date-dropdown flex-1 min-w-[200px]">
                <button type="button" class="date-dropdown-btn" id="beforeDateBtn">
                  <span id="beforeDateLabel">{{ __('post-task.step1.before_date') }}</span>
                  <i data-feather="calendar" class="w-5 h-5"></i>
                </button>
                <div class="date-dropdown-calendar" id="beforeDateCalendar">
                  <input type="date" name="required_before_date" id="beforeDateValue" value="{{ old('required_before_date') }}" />
                </div>
              </div>
              <div class="date-dropdown flex-1 min-w-[200px]">
                <button type="button" class="date-dropdown-btn" id="onDateBtn">
                  <span id="onDateLabel">{{ __('post-task.step1.on_date') }}</span>
                  <i data-feather="calendar" class="w-5 h-5"></i>
                </button>
                <div class="date-dropdown-calendar" id="onDateCalendar">
                  <input type="date" name="required_date" id="onDateValue" value="{{ old('required_date') }}" />
                </div>

              </div>
              <button type="button" class="pill-btn shrink-0" data-option="flexible">{{ __('post-task.step1.flexible') }}</button>
            </div>
            <p id="clientDateError" class="text-sm details-text-danger mt-2 hidden"></p>
          </div>

          <div class="mt-8">
            <label class="flex items-center gap-2 text-sm font-bold details-text-main mb-4 cursor-pointer">
              <input type="checkbox" id="needTimeCheckbox" class="w-5 h-5 rounded border-gray-300 text-[var(--primary-accent)] focus:ring-[var(--primary-accent)]" />
              <span>{{ __('post-task.step1.certain_time') }}</span>
            </label>
            <div id="timeOfDayOptions" class="grid grid-cols-2 md:grid-cols-4 gap-4 hidden">
              @foreach(['morning', 'midday', 'afternoon', 'evening'] as $time)
                <label class="time-option group" data-time="{{ $time }}" tabindex="0">
                  <input type="checkbox" name="preferred_time[]" value="{{ $time }}" class="hidden">
                  <div class="icon">
                    <i data-feather="{{ $time === 'morning' ? 'sunrise' : ($time === 'midday' ? 'sun' : ($time === 'afternoon' ? 'sunset' : 'moon')) }}" class="w-full h-full"></i>
                  </div>
                  <span class="font-bold details-text-main text-sm">{{ __('post-task.step1.' . $time) }}</span>
                  <span class="text-xs details-text-muted">{{ __('post-task.step1.' . $time . '_range') }}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>

        <!-- STEP 2: Location -->
        <div id="step-2" class="step-pane hidden">
          <h1 class="text-3xl font-extrabold details-text-main mb-8">{{ __('post-task.step2.title') }}</h1>
          <div class="space-y-8">
            <div>
              <p class="text-sm font-bold details-text-main mb-6">{{ __('post-task.step2.question') }}</p>
              <div class="flex flex-col sm:flex-row gap-4">
                <div class="location-option" id="inPersonOption" tabindex="0" role="radio">
                  <div class="icon"><i data-feather="map-pin" class="w-full h-full"></i></div>
                  <div class="title">{{ __('post-task.step2.in_person') }}</div>
                  <div class="description">{{ __('post-task.step2.in_person_desc') }}</div>
                </div>
                <div class="location-option" id="onlineOption" tabindex="0" role="radio">
                  <div class="icon"><i data-feather="monitor" class="w-full h-full"></i></div>
                  <div class="title">{{ __('post-task.step2.online') }}</div>
                  <div class="description">{{ __('post-task.step2.online_desc') }}</div>
                </div>
              </div>
            </div>
            <div id="locationInputs" class="space-y-6">
              <div>
                <label class="block text-sm font-bold details-text-main mb-2">{{ __('post-task.step2.location_label') }}</label>
                <div class="relative">
                  <input type="text" id="pickupSuburb" name="location" class="w-full rounded-lg p-3" 
                         placeholder="{{ __('post-task.step2.location_placeholder') }}" value="{{ old('location') }}" autocomplete="off" />
                  <div id="pickupSuburbDropdown" class="location-autocomplete-dropdown absolute left-0 right-0 mt-1 max-h-48 overflow-y-auto rounded-lg hidden"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 3: Details & Photos -->
        <div id="step-3" class="step-pane hidden">
          <h1 class="text-3xl font-extrabold details-text-main mb-8">{{ __('post-task.step3.title') }}</h1>
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-bold details-text-main mb-2">{{ __('post-task.step3.details_label') }}</label>
              <textarea id="taskDetails" name="description" rows="6" class="w-full rounded-lg p-3" 
                        placeholder="{{ __('post-task.step3.details_placeholder') }}">{{ old('description') }}</textarea>
            </div>
            <div>
              <label class="block text-sm font-bold details-text-main mb-2">
                {{ __('post-task.step3.photos_label') }} <span class="details-text-muted font-normal">{{ __('post-task.step3.photos_optional') }}</span>
              </label>
              <div class="photo-upload-plus" id="photoUploadPlus" tabindex="0" role="button">
                <i data-feather="plus-circle" class="plus-icon"></i>
                <div class="text">{{ __('post-task.step3.add_photos') }}</div>
                <div class="subtext text-xs">{{ __('post-task.step3.add_photos_desc') }}</div>
              </div>
              <input type="file" id="photoSelectorInput" multiple accept="image/*" class="hidden">
              <input type="file" id="photoSubmissionInput" name="photos[]" multiple class="hidden">
              <div class="photo-preview-container" id="photoPreviewContainer"></div>
            </div>
          </div>
        </div>

        <!-- STEP 4: Budget -->
        <div id="step-4" class="step-pane hidden">
          <h1 class="text-3xl font-extrabold details-text-main mb-8">{{ __('post-task.step4.title') }}</h1>
          <div class="bg-gray-50 dark:bg-slate-800/50 rounded-2xl p-6 mb-8">
            <p class="text-lg font-bold details-text-main mb-1">{{ __('post-task.step4.budget_question') }}</p>
            <p class="text-sm details-text-muted mb-6">{{ __('post-task.step4.negotiable') }}</p>
            <div class="flex items-center rounded-xl overflow-hidden border details-border-color" id="budgetWrapper">
              <span class="px-5 h-14 flex items-center bg-gray-100 dark:bg-slate-800 border-r details-border-color font-bold details-text-muted">€</span>
              <input id="budgetInput" name="price" type="number" min="5" max="5000" 
                     class="flex-1 h-14 px-4 outline-none text-xl font-bold details-main-bg details-text-main" 
                     placeholder="0.00" value="{{ old('price') }}">
            </div>
            <div id="budgetError" class="invalid-feedback-custom hidden">
              {{ __('post-task.step4.budget_error') }}
            </div>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center justify-between mt-12 pt-8 border-t details-border-color">
          <button type="button" id="backBtn" class="px-8 py-3 rounded-full font-bold step-nav-btn-back disabled:opacity-30" disabled>
            {{ __('post-task.nav.back') }}
          </button>
          <div class="flex gap-4">
            <button type="button" id="nextBtn" class="px-10 py-3 rounded-full font-bold step-nav-btn-next disabled:opacity-50" disabled>
              {{ __('post-task.nav.next') }}
            </button>
            <button type="submit" id="submitBtn" class="px-10 py-3 rounded-full font-bold step-nav-btn-next hidden disabled:opacity-50">
              {{ __('post-task.nav.get_quotes') }}
            </button>
          </div>
        </div>
      </form>
    </section>
  </div>
</div>

<script type="module">
  import { PostTaskManager } from "{{ asset('js/pages/post-task.js') }}";
  
  document.addEventListener('DOMContentLoaded', () => {
    // Process Categories for JS
    @php
      $categoriesJson = $categories->map(function($cat) {
          return [
              'id' => $cat->id,
              'name' => __('categories.' . $cat->name),
              'jobs' => $cat->jobs->map(function($job) {
                  return [
                      'id' => $job->id,
                      'name' => __('jobs.' . $job->name)
                  ];
              })
          ];
      });
    @endphp
    const categoriesData = @json($categoriesJson);

    new PostTaskManager({
        locale: "{{ app()->getLocale() == 'hu' ? 'hu-HU' : 'en-US' }}",
        categories: categoriesData,
        oldTimes: @json(old('preferred_time', [])),
        preCat: "{{ request('category', '') }}",
        preJob: "{{ request('job', request('service', '')) }}",
        i18n: {
            onDate: "{{ __('post-task.step1.on_date') }}",
            beforeDate: "{{ __('post-task.step1.before_date') }}",
            servicePlaceholder: "{{ __('post-task.step1.service_placeholder_select') }}",
            submitting: "{{ __('post-task.nav.submitting') ?? 'Submitting...' }}",
            dateError: "{{ __('validation.after_or_equal', ['attribute' => 'date', 'date' => 'today']) }}"
        }
    });

    // State restoration is handled inside PostTaskManager constructor
  });
</script>
@endsection