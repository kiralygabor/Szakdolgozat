    @extends('layout')

       @section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minijobz - Get Things Done</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#6366f1',
                        },
                        secondary: {
                            500: '#6366f1',
                        }
                    }
                }
            }
        }
    </script>
    <style>

  @keyframes scrollUp {
    0% { transform: translateY(0); }
    100% { transform: translateY(-50%); }
  }
  @keyframes scrollDown {
    0% { transform: translateY(-50%); }
    100% { transform: translateY(0); }
  }
 
  .animate-scroll-up .task-column {
    animation: scrollUp 20s linear infinite;
  }
  .animate-scroll-down .task-column {
    animation: scrollDown 20s linear infinite;
  }
 
  /* Smooth looping effect */
  .task-column {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
 
  @keyframes scroll-right {
  0% { transform: translateX(-50%); }
  100% { transform: translateX(0%); }
}
@keyframes scroll-left {
  0% { transform: translateX(0%); }
  100% { transform: translateX(-50%); }
}
.animate-scroll-right {
  animation: scroll-right 25s linear infinite;
}
.animate-scroll-left {
  animation: scroll-left 25s linear infinite;
}

</style>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">
    
 
 
<!-- Hero Section -->
<section class="relative h-[90vh] flex items-center justify-center bg-gray-900 text-white overflow-hidden">
  <!-- Background Image -->
  <img
    src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=2000&am…
    alt="People working together"
    class="absolute inset-0 w-full h-full object-cover opacity-60"
  />
 
  <!-- Overlay -->
  <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-gray-900/50 to-transparent"></div>
 
  <!-- Content -->
  <div class="relative z-10 text-center max-w-3xl px-6">
    <h1 class="text-6xl md:text-7xl font-extrabold mb-6 leading-tight">
      Get Things Done with <span class="text-indigo-400">Minijobz</span>
    </h1>
    <p class="text-lg md:text-xl text-gray-200 mb-8">
      Find local helpers or offer your skills — simple, fast, and secure.  
      Post your first task in seconds and connect with reliable workers near you.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="#post-task" class="px-8 py-4 bg-indigo-500 hover:bg-indigo-600 rounded-full text-lg font-semibold shadow-lg transition">
        Post a Task
      </a>
      <a href="#browse-tasks" class="px-8 py-4 bg-white text-gray-900 hover:bg-gray-100 rounded-full text-lg font-semibold shadow-lg transition">
        Browse Tasks
      </a>
    </div>
  </div>
</section>
 
<!-- How It Works -->
<section class="py-24 px-6 bg-white">
  <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-20 items-center">
    <!-- Left side -->
    <div>
      <h2 class="text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
        Post your first<br>task in seconds
      </h2>
      <p class="text-xl text-gray-700 mb-10 max-w-md">
        Save time and get your everyday tasks done by trusted people near you.
      </p>
 
      <div class="space-y-5 mb-12">
        <div class="flex items-start">
          <div class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-bold mr-4">1</div>
          <p class="text-lg text-gray-800">Describe what you need done</p>
        </div>
        <div class="flex items-start">
          <div class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-bold mr-4">2</div>
          <p class="text-lg text-gray-800">Set your budget</p>
        </div>
        <div class="flex items-start">
          <div class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-bold mr-4">3</div>
          <p class="text-lg text-gray-800">Receive offers and choose your Tasker</p>
        </div>
      </div>
 
      <button class="bg-blue-600 text-white font-semibold py-3.5 px-8 rounded-full shadow hover:bg-blue-700 transition">
        Post your task
      </button>
    </div>
 
    <!-- Right side (Animated Task Cards Showcase) -->
    <div class="bg-blue-50 rounded-2xl p-8 shadow-inner w-full max-w-4xl mx-auto overflow-hidden">
      <div class="grid grid-cols-2 gap-4 relative h-[600px]">
        <!-- Left Column (scrolls up) -->
        <div class="space-y-4 animate-scroll-up">
          <template id="task-cards-left">
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
              <img src="https://via.placeholder.com/400x110" alt="Handyman" class="w-full h-24 object-cover">
              <div class="p-3">
                <h4 class="font-semibold text-gray-900 mb-1 text-sm">Handyman</h4>
                <p class="text-gray-600 text-xs">Fix, assemble, or repair home tasks fast.</p>
              </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
              <img src="https://via.placeholder.com/400x110" alt="Cleaning" class="w-full h-24 object-cover">
              <div class="p-3">
                <h4 class="font-semibold text-gray-900 mb-1 text-sm">Cleaning</h4>
                <p class="text-gray-600 text-xs">Home, office, or deep-clean made easy.</p>
              </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
              <img src="https://via.placeholder.com/400x110" alt="Delivery" class="w-full h-24 object-cover">
              <div class="p-3">
                <h4 class="font-semibold text-gray-900 mb-1 text-sm">Delivery</h4>
                <p class="text-gray-600 text-xs">Fast delivery for groceries or parcels.</p>
              </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
              <img src="https://via.placeholder.com/400x110" alt="Gardening" class="w-full h-24 object-cover">
              <div class="p-3">
                <h4 class="font-semibold text-gray-900 mb-1 text-sm">Gardening</h4>
                <p class="text-gray-600 text-xs">Lawn care and plant maintenance made simple.</p>
              </div>
            </div>
          </template>
          <div class="task-column" id="col-left"></div>
        </div>
 
        <!-- Right Column (scrolls down) -->
        <div class="space-y-4 animate-scroll-down">
          <template id="task-cards-right">
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
              <img src="https://via.placeholder.com/400x110" alt="Painter" class="w-full h-24 object-cover">
              <div class="p-3">
                <h4 class="font-semibold text-gray-900 mb-1 text-sm">Painter</h4>
                <p class="text-gray-600 text-xs">Quick wall refresh or minor touch-ups.</p>
              </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
              <img src="https://via.placeholder.com/400x110" alt="Moving Help" class="w-full h-24 object-cover">
              <div class="p-3">
                <h4 class="font-semibold text-gray-900 mb-1 text-sm">Moving Help</h4>
                <p class="text-gray-600 text-xs">Extra hands for heavy lifting or moving.</p>
              </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
              <img src="https://via.placeholder.com/400x110" alt="Pet Sitting" class="w-full h-24 object-cover">
              <div class="p-3">
                <h4 class="font-semibold text-gray-900 mb-1 text-sm">Pet Sitting</h4>
                <p class="text-gray-600 text-xs">Reliable care for your furry friends.</p>
              </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
              <img src="https://via.placeholder.com/400x110" alt="Tech Support" class="w-full h-24 object-cover">
              <div class="p-3">
                <h4 class="font-semibold text-gray-900 mb-1 text-sm">Tech Support</h4>
                <p class="text-gray-600 text-xs">Fix computer issues or setup devices easily.</p>
              </div>
            </div>
          </template>
          <div class="task-column" id="col-right"></div>
        </div>
      </div>
    </div>
  </div>
</section>
 
<!-- Why Choose Minijobz? - Modern/Dynamic -->
<section class="relative py-24 px-6">
  <!-- Decorative gradients -->
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -top-12 -left-12 w-72 h-72 bg-gradient-to-br from-indigo-400/30 to-cyan-400/30 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-16 -right-16 w-80 h-80 bg-gradient-to-tr from-pink-400/20 to-violet-400/20 rounded-full blur-3xl"></div>
  </div>
 
  <div class="relative max-w-7xl mx-auto">
    <div class="text-center">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
        Why Minijobz
      </span>
      <h2 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight">
        <span class="bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-500 bg-clip-text text-transparent">Reasons you'll love using Minijobz</span>
      </h2>
      <p class="text-gray-600 text-lg mt-4 max-w-2xl mx-auto">
        Fast matches, verified people, and pricing you control — all in one place.
      </p>
    </div>
 
    <!-- Cards -->
    <div class="mt-14 grid md:grid-cols-3 gap-6 md:gap-8">
      <!-- Card 1 -->
      <div class="group relative rounded-2xl">
        <div class="absolute -inset-px rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-cyan-500 opacity-30 blur transition-opacity duration-300 group-hover:opacity-60"></div>
        <div class="relative h-full rounded-2xl bg-white/70 backdrop-blur-xl border border-white/60 shadow-xl overflow-hidden">
          <div class="h-36 w-full overflow-hidden">
            <img src="https://images.unsplash.com/photo-1598970434795-0c54fe7c0642?auto=format&fit=crop&w=1200&am… alt="Local & Fast" class="w-full h-full object-cover scale-100 group-hover:scale-105 transition-transform duration-500">
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="inline-flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600">
                  <i data-feather="map-pin" class="w-5 h-5"></i>
                </span>
                <h3 class="text-xl font-bold">Local & Fast</h3>
              </div>
              <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">Under 24h</span>
            </div>
            <p class="text-gray-600">Find trusted people nearby and get help within hours. No waiting, no hassle.</p>
          </div>
        </div>
      </div>
 
      <!-- Card 2 -->
      <div class="group relative rounded-2xl">
        <div class="absolute -inset-px rounded-2xl bg-gradient-to-br from-green-500 via-emerald-500 to-teal-500 opacity-30 blur transition-opacity duration-300 group-hover:opacity-60"></div>
        <div class="relative h-full rounded-2xl bg-white/70 backdrop-blur-xl border border-white/60 shadow-xl overflow-hidden">
          <div class="h-36 w-full overflow-hidden">
            <img src="https://images.unsplash.com/photo-1556740720-4b2b47e6e5e3?auto=format&fit=crop&w=1200&q… alt="Safe & Secure" class="w-full h-full object-cover scale-100 group-hover:scale-105 transition-transform duration-500">
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="inline-flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600">
                  <i data-feather="shield" class="w-5 h-5"></i>
                </span>
                <h3 class="text-xl font-bold">Safe & Secure</h3>
              </div>
              <span class="text-xs px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100">Verified</span>
            </div>
            <p class="text-gray-600">Built-in reviews, ratings, and verified profiles ensure trustworthy help every time.</p>
          </div>
        </div>
      </div>
 
      <!-- Card 3 -->
      <div class="group relative rounded-2xl">
        <div class="absolute -inset-px rounded-2xl bg-gradient-to-br from-amber-500 via-pink-500 to-violet-500 opacity-30 blur transition-opacity duration-300 group-hover:opacity-60"></div>
        <div class="relative h-full rounded-2xl bg-white/70 backdrop-blur-xl border border-white/60 shadow-xl overflow-hidden">
          <div class="h-36 w-full overflow-hidden">
            <img src="https://images.unsplash.com/photo-1581090700227-2db269f9b0e0?auto=format&fit=crop&w=1200&am… alt="Fair Pricing" class="w-full h-full object-cover scale-100 group-hover:scale-105 transition-transform duration-500">
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="inline-flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-pink-100 text-pink-600">
                  <i data-feather="dollar-sign" class="w-5 h-5"></i>
                </span>
                <h3 class="text-xl font-bold">Fair Pricing</h3>
              </div>
              <span class="text-xs px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 border border-amber-100">You decide</span>
            </div>
            <p class="text-gray-600">Set your budget and choose the best offer for your needs. Transparent and fair for everyone.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


 
<!-- BIG Testimonials Carousel (Fixed Visibility + Auto-Scroll) -->
<section class="py-40 px-8 bg-white">
  <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-24 items-center">
    <!-- Left Column -->
    <div>
      <h2 class="text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
        From our <span class="text-gray-900 font-black">community.</span>
      </h2>
      <p class="text-xl text-gray-600 mb-12 max-w-md">
        Here’s what other subscribers had to say about Minijobz.
      </p>
      <div class="flex space-x-5">
        <button id="prevTestimonial" class="w-14 h-14 flex items-center justify-center border border-gray-300 rounded-full hover:bg-gray-100 transition">
          <i data-feather="arrow-left" class="w-6 h-6"></i>
        </button>
        <button id="nextTestimonial" class="w-14 h-14 flex items-center justify-center border border-gray-300 rounded-full hover:bg-gray-100 transition">
          <i data-feather="arrow-right" class="w-6 h-6"></i>
        </button>
      </div>
    </div>

    <!-- Right Column -->
    <div class="relative" id="testimonialWrapper">
      <div id="testimonialContainer" class="relative min-h-[280px] transition-all duration-700 ease-in-out">
        <!-- Testimonial 1 -->
        <div class="testimonial opacity-100 transition-opacity duration-700 absolute inset-0">
          <p class="text-3xl font-medium text-gray-900 mb-10 leading-snug">
            “Minijobz helped me find reliable helpers in minutes — it’s fast, simple, and affordable.”
          </p>
          <div class="flex items-center space-x-6">
            <img src="https://via.placeholder.com/80" alt="User" class="w-16 h-16 rounded-full object-cover shadow-md">
            <div>
              <h4 class="font-bold text-gray-900 text-lg">Lisa Thompson</h4>
              <p class="text-gray-500 text-base">Homeowner, New York</p>
            </div>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="testimonial opacity-0 transition-opacity duration-700 absolute inset-0">
          <p class="text-3xl font-medium text-gray-900 mb-10 leading-snug">
            “I was able to pick up weekend gigs easily. Great way to earn extra income.”
          </p>
          <div class="flex items-center space-x-6">
            <img src="https://via.placeholder.com/80" alt="User" class="w-16 h-16 rounded-full object-cover shadow-md">
            <div>
              <h4 class="font-bold text-gray-900 text-lg">Mark Rodriguez</h4>
              <p class="text-gray-500 text-base">Freelancer, Los Angeles</p>
            </div>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="testimonial opacity-0 transition-opacity duration-700 absolute inset-0">
          <p class="text-3xl font-medium text-gray-900 mb-10 leading-snug">
            “I found a cleaner the same day I posted! Absolutely love the convenience.”
          </p>
          <div class="flex items-center space-x-6">
            <img src="https://via.placeholder.com/80" alt="User" class="w-16 h-16 rounded-full object-cover shadow-md">
            <div>
              <h4 class="font-bold text-gray-900 text-lg">Sophie Lee</h4>
              <p class="text-gray-500 text-base">Apartment Renter, Chicago</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

 
   <!-- Popular Tasks -->
<section class="py-16 px-4 overflow-hidden">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-center mb-12">Popular Tasks</h2>
 
    <!-- Scrolling container -->
    <div class="space-y-10">
      <!-- Top row (scrolls right) -->
      <div class="flex space-x-6 animate-scroll-right">
        <!-- Duplicate cards twice for smooth loop -->
        <div class="flex space-x-6">
          <!-- Task Cards 1–3 -->
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Moving Help</span>
              <span class="font-bold">$120</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Help moving furniture</h3>
            <p class="text-gray-600 mb-4">Need help moving a couch and dining table to a new apartment 2 blocks away.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>East Village (0.5mi)</span>
            </div>
          </div>
 
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Cleaning</span>
              <span class="font-bold">$75</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Apartment deep cleaning</h3>
            <p class="text-gray-600 mb-4">1 bedroom apartment needs deep cleaning before move-in.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Midtown (1.7mi)</span>
            </div>
          </div>
 
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Delivery</span>
              <span class="font-bold">$35</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Pick up groceries</h3>
            <p class="text-gray-600 mb-4">Need someone to pick up my grocery order from Whole Foods.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Uptown (3.2mi)</span>
            </div>
          </div>
        </div>
 
        <!-- Duplicate for continuous loop -->
        <div class="flex space-x-6">
          <!-- (same 3 cards repeated) -->
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Moving Help</span>
              <span class="font-bold">$120</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Help moving furniture</h3>
            <p class="text-gray-600 mb-4">Need help moving a couch and dining table to a new apartment 2 blocks away.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>East Village (0.5mi)</span>
            </div>
          </div>
 
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Cleaning</span>
              <span class="font-bold">$75</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Apartment deep cleaning</h3>
            <p class="text-gray-600 mb-4">1 bedroom apartment needs deep cleaning before move-in.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Midtown (1.7mi)</span>
            </div>
          </div>
 
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Delivery</span>
              <span class="font-bold">$35</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Pick up groceries</h3>
            <p class="text-gray-600 mb-4">Need someone to pick up my grocery order from Whole Foods.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Uptown (3.2mi)</span>
            </div>
          </div>
        </div>
      </div>
 
      <!-- Bottom row (scrolls left) -->
      <div class="flex space-x-6 animate-scroll-left">
        <div class="flex space-x-6">
          <!-- Task Cards 4–6 -->
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Tech Help</span>
              <span class="font-bold">$50</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Set up home network</h3>
            <p class="text-gray-600 mb-4">Need help setting up a mesh WiFi system in my house.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Riverside (2.1mi)</span>
            </div>
          </div>
 
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Handyman</span>
              <span class="font-bold">$90</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Fix leaky faucet</h3>
            <p class="text-gray-600 mb-4">Kitchen faucet is leaking, needs repair or replacement.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Downtown (0.8mi)</span>
            </div>
          </div>
 
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Tutoring</span>
              <span class="font-bold">$40/hr</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Math tutor for 8th grader</h3>
            <p class="text-gray-600 mb-4">Need help with algebra twice a week after school.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Southside (4.3mi)</span>
            </div>
          </div>
        </div>
 
        <!-- Duplicate for seamless loop -->
        <div class="flex space-x-6">
          <!-- same 4–6 cards repeated -->
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Tech Help</span>
              <span class="font-bold">$50</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Set up home network</h3>
            <p class="text-gray-600 mb-4">Need help setting up a mesh WiFi system in my house.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Riverside (2.1mi)</span>
            </div>
          </div>
 
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Handyman</span>
              <span class="font-bold">$90</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Fix leaky faucet</h3>
            <p class="text-gray-600 mb-4">Kitchen faucet is leaking, needs repair or replacement.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Downtown (0.8mi)</span>
            </div>
          </div>
 
          <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm min-w-[300px]">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Tutoring</span>
              <span class="font-bold">$40/hr</span>
            </div>
            <h3 class="font-bold text-xl mb-2">Math tutor for 8th grader</h3>
            <p class="text-gray-600 mb-4">Need help with algebra twice a week after school.</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <i data-feather="map-pin" class="w-4"></i>
              <span>Southside (4.3mi)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
 
    <div class="text-center mt-10">
      <button class="px-6 py-3 rounded-lg border border-primary-500 text-primary-500 hover:bg-primary-500/10 font-medium">
        View All Tasks
      </button>
    </div>
  </div>
</section>
 
<!-- Take Minijobz Anywhere -->
<section class="py-24 px-6 bg-blue-600 text-white">
  <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
   
    <!-- Left Side: Text & CTA -->
    <div>
      <h2 class="text-5xl font-extrabold mb-6 leading-tight">
        Take Minijobz Anywhere
      </h2>
      <p class="text-xl mb-8 text-gray-100 max-w-lg">
        Post tasks, get offers, and hire trusted people directly from your phone. Everything you need, right at your fingertips.
      </p>
      <div class="flex flex-wrap gap-4">
        <a href="#download" class="bg-white text-blue-600 font-semibold py-3 px-6 rounded-full shadow hover:bg-gray-100 transition">
          Download App
        </a>
        <a href="#signup" class="border border-white text-white font-semibold py-3 px-6 rounded-full hover:bg-white hover:text-blue-600 transition">
          Sign Up Now
        </a>
      </div>
    </div>
 
    <!-- Right Side: Phone Mockup -->
    <div class="flex justify-center md:justify-end">
      <div class="relative w-64 md:w-72 lg:w-96">
        <img src="https://via.placeholder.com/300x600" alt="Mobile App Mockup" class="rounded-3xl shadow-2xl border-4 border-white">
        <!-- Optional: Floating app screen highlights -->
        <div class="absolute top-8 left-6 w-52 h-96 bg-white rounded-2xl opacity-30"></div>
      </div>
    </div>
 
  </div>
</section>
 
 
 
    <!-- Footer -->
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
 
  settingsButton.addEventListener('click', () => {
    const isHidden = settingsMenu.classList.contains('hidden');
    if (isHidden) {
      settingsMenu.classList.remove('hidden');
      setTimeout(() => settingsMenu.classList.add('show'), 10); // animate in
    } else {
      settingsMenu.classList.remove('show');
      setTimeout(() => settingsMenu.classList.add('hidden'), 200); // delay hide for smooth close
    }
  });
 
  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!settingsButton.contains(e.target) && !settingsMenu.contains(e.target)) {
      settingsMenu.classList.remove('show');
      setTimeout(() => settingsMenu.classList.add('hidden'), 200);
    }
  });
    function duplicateCards(templateId, containerId) {
    const template = document.getElementById(templateId);
    const container = document.getElementById(containerId);
    for (let i = 0; i < 3; i++) {
      container.appendChild(template.content.cloneNode(true));
    }
  }
  duplicateCards('task-cards-left', 'col-left');
  duplicateCards('task-cards-right', 'col-right');
const testimonials = document.querySelectorAll('.testimonial');
  const prevBtn = document.getElementById('prevTestimonial');
  const nextBtn = document.getElementById('nextTestimonial');
  const wrapper = document.getElementById('testimonialWrapper');
  let currentIndex = 0;
  let autoSlide;

  function showTestimonial(index) {
    testimonials.forEach((t, i) => {
      t.style.opacity = i === index ? '1' : '0';
      t.style.zIndex = i === index ? '1' : '0';
    });
  }

  function nextTestimonial() {
    currentIndex = (currentIndex + 1) % testimonials.length;
    showTestimonial(currentIndex);
  }

  function prevTestimonialFunc() {
    currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
    showTestimonial(currentIndex);
  }

  prevBtn.addEventListener('click', prevTestimonialFunc);
  nextBtn.addEventListener('click', nextTestimonial);

  // Auto-scroll every 6 seconds
  function startAutoScroll() {
    autoSlide = setInterval(nextTestimonial, 6000);
  }

  function stopAutoScroll() {
    clearInterval(autoSlide);
  }

  wrapper.addEventListener('mouseenter', stopAutoScroll);
  wrapper.addEventListener('mouseleave', startAutoScroll);

  // Initialize
  showTestimonial(currentIndex);
  startAutoScroll();


  feather.replace();
</script>
</body>
</html>

@endsection