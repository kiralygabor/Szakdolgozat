<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How It Works - Minijobz</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 500: '#6366f1' },
                        secondary: { 500: '#6366f1' }
                    }
                }
            }
        }
    </script>
    <style>
      /* Smooth dropdown open animation */
      #settings-menu.show { opacity: 1 !important; transform: translateY(0) !important; }
    </style>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">
    <!-- Navigation (same as index) -->
    <nav class="bg-white border-b border-gray-200 shadow-sm">
      <div class="max-w-6xl mx-auto flex justify-between items-center px-6 py-3">
        <!-- Left side -->
        <div class="flex items-center space-x-5">
          <div class="flex items-center space-x-2">
            <i data-feather="zap" class="text-secondary-500"></i>
            <span class="text-xl font-bold text-gray-900">Minijobz</span>
          </div>
          <a href="#" class="px-4 py-2 rounded-lg bg-secondary-500 text-white hover:bg-secondary-600 font-semibold">Post a Task</a>
          <a href="#" class="text-gray-600 hover:text-secondary-500">Categories</a>
          <a href="#" class="text-gray-600 hover:text-secondary-500">Browse Tasks</a>
          <a href="#" class="text-gray-600 hover:text-secondary-500">How It Works</a>
        </div>
 
        <!-- Right side -->
        <div class="flex items-center space-x-3 relative">
          <button class="px-4 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white">Login</button>
          <button class="px-4 py-2 rounded-lg border border-primary-500 text-primary-500 hover:bg-primary-500/10">Sign Up</button>
 
          <!-- Settings dropdown -->
          <div class="relative">
            <button id="settings-button" class="p-2 rounded-full hover:bg-gray-200 transition">
              <i data-feather="settings"></i>
            </button>
 
            <!-- Dropdown -->
            <div id="settings-menu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-10 opacity-0 translate-y-2 transition-all duration-200 ease-out">
              <!-- Main items -->
              <div class="flex flex-col">
                <div class="group relative">
                  <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex justify-between items-center">Theme <i data-feather="chevron-right" class="w-4 h-4"></i></div>
                </div>
                <div class="group relative">
                  <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex justify-between items-center">Language <i data-feather="chevron-right" class="w-4 h-4"></i></div>
                </div>
                <div class="group relative">
                  <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex justify-between items-center">Extras <i data-feather="chevron-right" class="w-4 h-4"></i></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
 
    <!-- Header / Hero -->
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900"></div>
      <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=2000&am… alt="Collaboration" class="absolute inset-0 w-full h-full object-cover opacity-20">
      <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 md:py-28">
        <div class="max-w-3xl">
          <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight">How Minijobz Works</h1>
          <p class="mt-5 text-lg md:text-xl text-gray-200">Post a task in minutes, get offers fast, and hire trusted people with confidence.</p>
          <div class="mt-8 flex gap-4">
            <a href="#steps" class="px-6 py-3 rounded-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold">See the steps</a>
            <a href="#download" class="px-6 py-3 rounded-full bg-white/10 border border-white/20 text-white hover:bg-white/20">Get the app</a>
          </div>
        </div>
      </div>
    </section>
 
    <!-- Steps: Describe, Budget, Pick a Tasker -->
    <section id="steps" class="py-20 px-6 bg-white">
      <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
        <!-- Step 1 -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative hover:shadow-xl transition">
          <div class="absolute top-4 right-4 bg-indigo-500/10 text-indigo-600 text-xs px-2 py-1 rounded-full border border-indigo-200">Step 1</div>
          <img src="https://images.unsplash.com/photo-1529336953121-ad5a0d43d0f5?auto=format&fit=crop&w=1200&am… alt="Describe task" class="w-full h-40 object-cover">
          <div class="p-6">
            <div class="flex items-center gap-3 mb-3">
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600"><i data-feather="edit-3" class="w-5 h-5"></i></span>
              <h3 class="text-xl font-bold">Describe what you need</h3>
            </div>
            <p class="text-gray-600">Tell us what needs doing, where, and when. Clear details help you get better offers.</p>
          </div>
        </div>
 
        <!-- Step 2 -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative hover:shadow-xl transition">
          <div class="absolute top-4 right-4 bg-indigo-500/10 text-indigo-600 text-xs px-2 py-1 rounded-full border border-indigo-200">Step 2</div>
          <img src="https://images.unsplash.com/photo-1553729459-efe14ef6055d?auto=format&fit=crop&w=1200&q… alt="Set budget" class="w-full h-40 object-cover">
          <div class="p-6">
            <div class="flex items-center gap-3 mb-3">
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600"><i data-feather="dollar-sign" class="w-5 h-5"></i></span>
              <h3 class="text-xl font-bold">Set your budget</h3>
            </div>
            <p class="text-gray-600">Choose a fixed price or hourly rate. You stay in control with transparent pricing.</p>
          </div>
        </div>
 
        <!-- Step 3 -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative hover:shadow-xl transition">
          <div class="absolute top-4 right-4 bg-indigo-500/10 text-indigo-600 text-xs px-2 py-1 rounded-full border border-indigo-200">Step 3</div>
          <img src="https://images.unsplash.com/photo-1544006659-f0b21884ce1d?auto=format&fit=crop&w=1200&q… alt="Pick tasker" class="w-full h-40 object-cover">
          <div class="p-6">
            <div class="flex items-center gap-3 mb-3">
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-sky-100 text-sky-600"><i data-feather="check-circle" class="w-5 h-5"></i></span>
              <h3 class="text-xl font-bold">Pick a Tasker</h3>
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
 
    <!-- Download the App (reuse style) -->
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
 
    <!-- Footer (same as index) -->
    <footer class="bg-white border-t border-gray-200 py-12 px-4 shadow-sm">
      <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
        <div>
          <div class="flex items-center space-x-2 mb-4">
            <i data-feather="zap" class="text-secondary-500"></i>
            <span class="text-xl font-bold text-gray-900">Minijobz</span>
          </div>
          <p class="text-gray-600">Connecting people to get things done since 2025.</p>
          <div class="flex space-x-4 mt-4">
            <a href="#" class="text-gray-400 hover:text-white"><i data-feather="facebook"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i data-feather="twitter"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i data-feather="instagram"></i></a>
          </div>
        </div>
        <div>
          <h3 class="font-bold text-gray-900 mb-4">For Taskers</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Browse Tasks</a></li>
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">How It Works</a></li>
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Safety Tips</a></li>
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Tasker Resources</a></li>
          </ul>
        </div>
        <div>
          <h3 class="font-bold text-gray-900 mb-4">For Posters</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Post a Task</a></li>
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Pricing</a></li>
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Safety Tips</a></li>
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Help Center</a></li>
          </ul>
        </div>
        <div>
          <h3 class="font-bold text-gray-900 mb-4">Company</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">About Us</a></li>
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Careers</a></li>
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Press</a></li>
            <li><a href="#" class="text-gray-600 hover:text-secondary-500">Contact</a></li>
          </ul>
        </div>
      </div>
      <div class="max-w-7xl mx-auto border-t border-gray-200 mt-12 pt-8 text-center text-gray-500">
        <p>© 2025 Minijobz. All rights reserved.</p>
      </div>
    </footer>
 
    <script>
      const settingsButton = document.getElementById('settings-button');
      const settingsMenu = document.getElementById('settings-menu');
 
      if (settingsButton && settingsMenu) {
        settingsButton.addEventListener('click', () => {
          const isHidden = settingsMenu.classList.contains('hidden');
          if (isHidden) {
            settingsMenu.classList.remove('hidden');
            setTimeout(() => settingsMenu.classList.add('show'), 10);
          } else {
            settingsMenu.classList.remove('show');
            setTimeout(() => settingsMenu.classList.add('hidden'), 200);
          }
        });
 
        document.addEventListener('click', (e) => {
          if (!settingsButton.contains(e.target) && !settingsMenu.contains(e.target)) {
            settingsMenu.classList.remove('show');
            setTimeout(() => settingsMenu.classList.add('hidden'), 200);
          }
        });
      }
 
      feather.replace();
    </script>
</body>
</html>