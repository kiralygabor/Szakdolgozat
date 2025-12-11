@extends('layout')

@section('content')
<div class="bg-gray-50 text-gray-800 min-h-screen">
  <!-- Header / Hero (bigger) -->
  <section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900"></div>
    <img src="https://via.placeholder.com/2000x900" alt="Collaboration" class="absolute inset-0 w-full h-full object-cover opacity-20">
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-28 md:py-40">
      <div class="max-w-3xl">
        <h1 class="text-5xl md:text-7xl font-extrabold text-white leading-tight">How Minijobz Works</h1>
        <p class="mt-6 text-xl md:text-2xl text-gray-200">Post a task in minutes, get offers fast, and hire trusted people with confidence.</p>
        <div class="mt-10 flex gap-4">
          <a href="#" class="px-7 py-3.5 rounded-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold">Post a Task</a>
          <a href="#download" class="px-7 py-3.5 rounded-full bg-white/10 border border-white/20 text-white hover:bg-white/20">Get the app</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Steps: Describe, Budget, Pick a Tasker (bigger) -->
  <section id="steps" class="py-28 px-6 bg-white">
    <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-10">
      <!-- Step 1 -->
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative hover:shadow-xl transition">
        <div class="absolute top-4 right-4 bg-indigo-500/10 text-indigo-600 text-xs px-2 py-1 rounded-full border border-indigo-200">Step 1</div>
        <img src="https://via.placeholder.com/1200x400" alt="Describe task" class="w-full h-48 object-cover">
        <div class="p-7">
          <div class="flex items-center gap-3 mb-3">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600"><i data-feather="edit-3" class="w-5 h-5"></i></span>
            <h3 class="text-2xl font-bold">Describe what you need</h3>
          </div>
          <p class="text-gray-600">Tell us what needs doing, where, and when. Clear details help you get better offers.</p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative hover:shadow-xl transition">
        <div class="absolute top-4 right-4 bg-indigo-500/10 text-indigo-600 text-xs px-2 py-1 rounded-full border border-indigo-200">Step 2</div>
        <img src="https://via.placeholder.com/1200x400" alt="Set budget" class="w-full h-48 object-cover">
        <div class="p-7">
          <div class="flex items-center gap-3 mb-3">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600"><i data-feather="dollar-sign" class="w-5 h-5"></i></span>
            <h3 class="text-2xl font-bold">Set your budget</h3>
          </div>
          <p class="text-gray-600">Choose a fixed price or hourly rate. You stay in control with transparent pricing.</p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative hover:shadow-xl transition">
        <div class="absolute top-4 right-4 bg-indigo-500/10 text-indigo-600 text-xs px-2 py-1 rounded-full border border-indigo-200">Step 3</div>
        <img src="https://via.placeholder.com/1200x400" alt="Pick tasker" class="w-full h-48 object-cover">
        <div class="p-7">
          <div class="flex items-center gap-3 mb-3">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-sky-100 text-sky-600"><i data-feather="check-circle" class="w-5 h-5"></i></span>
            <h3 class="text-2xl font-bold">Pick a Tasker</h3>
          </div>
          <p class="text-gray-600">Compare offers, profiles, and completion rates to choose the best person for the job.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Insurance Cover -->
  <section class="py-20 px-6 bg-gray-50">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Insurance that has your back</h2>
        <p class="text-gray-600 text-lg mb-6">Eligible tasks are backed by our partner insurance for extra peace of mind. We take safety seriously so you can hire with confidence.</p>
        <ul class="space-y-3 text-gray-700">
          <li class="flex items-start gap-3"><i data-feather="shield" class="text-indigo-500 w-5 h-5 mt-0.5"></i> Coverage for accidental damage during eligible tasks</li>
          <li class="flex items-start gap-3"><i data-feather="file-text" class="text-indigo-500 w-5 h-5 mt-0.5"></i> Clear policy terms and easy claims process</li>
          <li class="flex items-start gap-3"><i data-feather="lock" class="text-indigo-500 w-5 h-5 mt-0.5"></i> Secure payments held until the job is marked complete</li>
        </ul>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600"><i data-feather="award" class="w-6 h-6"></i></span>
          <div>
            <p class="text-sm text-gray-500">Backed protection</p>
            <p class="text-lg font-semibold">Eligible Task Insurance</p>
          </div>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-4">
          <div class="rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Claim support</p>
            <p class="text-sm font-semibold">48h response</p>
          </div>
          <div class="rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Coverage type</p>
            <p class="text-sm font-semibold">Accidental damage</p>
          </div>
          <div class="rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Availability</p>
            <p class="text-sm font-semibold">Select regions</p>
          </div>
          <div class="rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Cost</p>
            <p class="text-sm font-semibold">Included</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Ratings and Reviews -->
  <section class="py-16 px-6 bg-white">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-extrabold">Ratings & Reviews</h2>
        <p class="text-gray-600 max-w-2xl mx-auto mt-3">Every Tasker builds a track record. See ratings, read reviews, and hire with confidence.</p>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center mb-3">
            <div class="w-12 h-12 rounded-full bg-secondary-500 flex items-center justify-center mr-4"><i data-feather="user" class="text-white"></i></div>
            <div>
              <h3 class="font-bold">Alex P.</h3>
              <div class="flex text-yellow-400"><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i></div>
            </div>
          </div>
          <p class="text-gray-600 italic">“Great communication and got the job done quickly. Would hire again.”</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center mb-3">
            <div class="w-12 h-12 rounded-full bg-secondary-500 flex items-center justify-center mr-4"><i data-feather="user" class="text-white"></i></div>
            <div>
              <h3 class="font-bold">Jamie L.</h3>
              <div class="flex text-yellow-400"><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i></div>
            </div>
          </div>
          <p class="text-gray-600 italic">“Professional and friendly. The reviews helped me choose the right person.”</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center mb-3">
            <div class="w-12 h-12 rounded-full bg-secondary-500 flex items-center justify-center mr-4"><i data-feather="user" class="text-white"></i></div>
            <div>
              <h3 class="font-bold">Rita S.</h3>
              <div class="flex text-yellow-400"><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i><i data-feather="star" class="w-4"></i></div>
            </div>
          </div>
          <p class="text-gray-600 italic">“Five stars! Clear ratings and real feedback made it easy to decide.”</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Stay in contact -->
  <section class="py-20 px-6 bg-gray-50">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div class="order-2 md:order-1">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Stay in contact</h2>
        <p class="text-gray-600 text-lg mb-6">Chat safely in-app, share photos, and get real-time updates. Keep everything in one place until the job’s done.</p>
        <div class="grid sm:grid-cols-2 gap-4">
          <div class="rounded-2xl bg-white border border-gray-200 p-4 flex items-start gap-3">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 text-blue-600"><i data-feather="message-circle" class="w-5 h-5"></i></span>
            <div>
              <p class="font-semibold">In-app messaging</p>
              <p class="text-sm text-gray-600">Keep your details private while coordinating tasks.</p>
            </div>
          </div>
          <div class="rounded-2xl bg-white border border-gray-200 p-4 flex items-start gap-3">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 text-amber-600"><i data-feather="bell" class="w-5 h-5"></i></span>
            <div>
              <p class="font-semibold">Notifications</p>
              <p class="text-sm text-gray-600">Get offer alerts and progress updates instantly.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="order-1 md:order-2">
        <div class="relative w-full max-w-md mx-auto">
          <img src="https://via.placeholder.com/300x600" alt="Messages" class="rounded-3xl shadow-2xl border-4 border-white">
          <div class="absolute top-6 left-6 w-40 h-72 bg-white rounded-2xl opacity-20"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Download the App -->
  <section id="download" class="py-24 px-6 bg-blue-600 text-white">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h2 class="text-5xl font-extrabold mb-6 leading-tight">Take Minijobz Anywhere</h2>
        <p class="text-xl mb-8 text-gray-100 max-w-lg">Post tasks, get offers, and hire trusted people directly from your phone. Everything you need, right at your fingertips.</p>
        <div class="flex flex-wrap gap-4">
          <a href="#" class="bg-white text-blue-600 font-semibold py-3 px-6 rounded-full shadow hover:bg-gray-100 transition">Download App</a>
          <a href="#" class="border border-white text-white font-semibold py-3 px-6 rounded-full hover:bg-white hover:text-blue-600 transition">Sign Up Now</a>
        </div>
      </div>
      <div class="flex justify-center md:justify-end">
        <div class="relative w-64 md:w-72 lg:w-96">
          <img src="https://via.placeholder.com/300x600" alt="Mobile App Mockup" class="rounded-3xl shadow-2xl border-4 border-white">
          <div class="absolute top-8 left-6 w-52 h-96 bg-white rounded-2xl opacity-30"></div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection