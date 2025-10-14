    @extends('layout')

       @section('content')
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
 
<!-- Why Choose Minijobz? - Clean, Icon Left -->
<section class="relative py-56 px-6 md:px-12 bg-gradient-to-r from-blue-50 to-white">
  <!-- Background image with subtle overlay -->
  <div class="absolute inset-0">
    <img src="https://images.unsplash.com/photo-1581092795360-fd1ca04f0952?auto=format&fit=crop&w=2000&am… alt="Teamwork Background" class="w-full h-full object-cover opacity-20">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-100/70 to-white/80"></div>
  </div>
  <div class="relative max-w-7xl mx-auto">
    <div class="text-center">
      <h2 class="text-5xl md:text-6xl font-extrabold tracking-tight text-gray-900">
        Reasons you'll love using Minijobz
      </h2>
      <p class="text-gray-700 text-lg mt-4 max-w-2xl mx-auto">
        Fast matches, verified people, and pricing you control — all in one place.
      </p>
    </div>
 
    <!-- Cards - smaller but balanced -->
    <div class="mt-16 grid md:grid-cols-3 gap-6 md:gap-8">
      <!-- Card 1 -->
      <div class="rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md transition p-6 flex items-center gap-6">
        <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex-shrink-0">
          <i data-feather="map-pin" class="w-9 h-9"></i>
        </span>
        <div>
          <h3 class="text-xl font-bold text-gray-900 mb-1">Local & Fast</h3>
          <p class="text-gray-600 text-base leading-relaxed">
            Find trusted people nearby and get help within hours — no waiting, no hassle.
          </p>
        </div>
      </div>
 
      <!-- Card 2 -->
      <div class="rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md transition p-6 flex items-center gap-6">
        <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex-shrink-0">
          <i data-feather="shield" class="w-9 h-9"></i>
        </span>
        <div>
          <h3 class="text-xl font-bold text-gray-900 mb-1">Safe & Secure</h3>
          <p class="text-gray-600 text-base leading-relaxed">
            Verified profiles, reviews, and ratings make every job safe and transparent.
          </p>
        </div>
      </div>
 
      <!-- Card 3 -->
      <div class="rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md transition p-6 flex items-center gap-6">
        <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex-shrink-0">
          <i data-feather="dollar-sign" class="w-9 h-9"></i>
        </span>
        <div>
          <h3 class="text-xl font-bold text-gray-900 mb-1">Fair Pricing</h3>
          <p class="text-gray-600 text-base leading-relaxed">
            Set your budget and choose the best offer for your needs — transparent and fair.
          </p>
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
<section class="py-20 px-4 overflow-hidden">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-20">See what others are getting done</h2>
 
    <!-- Scrolling container -->
    <div class="space-y-10">
      <!-- Top row (scrolls right) -->
      <div class="flex space-x-6 animate-scroll-right">
        <!-- Duplicate cards twice for smooth loop -->
        <div class="flex space-x-6">
           <!-- Card 1 -->
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Delivery</span>
                   <h3 class="text-xl font-bold text-gray-900">King mattress pick and delivery</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$85</span>
             </div>
           </div>
 
           <!-- Card 2 -->
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Delivery</span>
                   <h3 class="text-xl font-bold text-gray-900">Sofa delivery</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$95</span>
             </div>
           </div>
 
           <!-- Card 3 -->
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Cleaning</span>
                   <h3 class="text-xl font-bold text-gray-900">End of lease clean</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$450</span>
             </div>
           </div>
        </div>
 
        <!-- Duplicate for continuous loop -->
        <div class="flex space-x-6">
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Delivery</span>
                   <h3 class="text-xl font-bold text-gray-900">King mattress pick and delivery</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$85</span>
             </div>
           </div>
 
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Delivery</span>
                   <h3 class="text-xl font-bold text-gray-900">Sofa delivery</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$95</span>
             </div>
           </div>
 
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Cleaning</span>
                   <h3 class="text-xl font-bold text-gray-900">End of lease clean</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$450</span>
             </div>
           </div>
        </div>
      </div>
 
      <!-- Bottom row (scrolls left) -->
      <div class="flex space-x-6 animate-scroll-left">
        <div class="flex space-x-6">
           <!-- Card 4 -->
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Removals</span>
                   <h3 class="text-xl font-bold text-gray-900">Couch moved 1km down the road</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$60</span>
             </div>
           </div>
 
           <!-- Card 5 -->
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Removals</span>
                   <h3 class="text-xl font-bold text-gray-900">Removalist TODAY</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$506</span>
             </div>
           </div>
 
           <!-- Card 6 -->
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Removals</span>
                   <h3 class="text-xl font-bold text-gray-900">Urgent removalist</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$450</span>
             </div>
           </div>
        </div>
 
        <!-- Duplicate for seamless loop -->
        <div class="flex space-x-6">
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Removals</span>
                   <h3 class="text-xl font-bold text-gray-900">Couch moved 1km down the road</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$60</span>
             </div>
           </div>
 
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Removals</span>
                   <h3 class="text-xl font-bold text-gray-900">Removalist TODAY</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$506</span>
             </div>
           </div>
 
           <div class="min-w-[360px] bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
             <div class="flex items-start mb-4">
               <div class="flex items-center gap-3">
                 <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="avatar">
                 <div>
                   <span class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Removals</span>
                   <h3 class="text-xl font-bold text-gray-900">Urgent removalist</h3>
                 </div>
               </div>
             </div>
             <div class="flex items-center justify-between text-sm">
               <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white text-blue-700 border border-blue-100"><i data-feather="star" class="w-4 h-4 text-amber-400"></i> 5 Stars</span>
               <span class="text-lg font-bold text-gray-900">$450</span>
             </div>
           </div>
        </div>
      </div>
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
    <img  
      src="assets/img/phone_14_01.webp" 
      alt="Mobile App Mockup" 
    >

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

@endsection