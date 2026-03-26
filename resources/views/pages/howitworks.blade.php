@extends('layout')
 
@section('content')
<style>
    /* Dark Mode Overrides */
    html.dark body { background-color: #0f172a !important; }
    html.dark div.bg-white, 
    html.dark section.bg-white { background-color: #0f172a !important; color: #f8fafc !important; }
    
    html.dark #howitworks-hero,
    html.dark .bg-slate-50,
    html.dark section[class*="bg-[#f8fafc]"],
    html.dark section[class*="bg-slate-50"] { background-color: #1e293b !important; }

    html.dark .text-slate-900,
    html.dark h1, html.dark h2, html.dark h3 { color: #f1f5f9 !important; }
    html.dark .text-slate-600 { color: #cbd5e1 !important; }
    html.dark .text-slate-700 { color: #e2e8f0 !important; }
    html.dark .text-slate-500 { color: #94a3b8 !important; }
    
    /* Target the very dark blue text */
    html.dark .text-\[\#000033\],
    html.dark [class*="text-[#000033]"] { color: #f1f5f9 !important; }

    /* Indigo Text Contrast */
    html.dark .text-indigo-600 { color: #818cf8 !important; }
    html.dark .text-indigo-100 { color: #e0e7ff !important; }

    html.dark .border-slate-100,
    html.dark .border-white { border-color: #334155 !important; }
    
    /* Decorative Elements */
    html.dark .howitworks-circle { background-color: rgba(99, 102, 241, 0.15) !important; mix-blend-mode: normal !important; }
    html.dark .bg-indigo-50\/40 { background-color: rgba(99, 102, 241, 0.1) !important; }
    html.dark .bg-white\\/90 { background-color: rgba(30, 41, 59, 0.9) !important; color: #f8fafc !important; }

    /* Fix image backgrounds and borders */
    html.dark .bg-slate-200 { background-color: #334155 !important; }
    html.dark .ring-white { --tw-ring-color: #334155 !important; }

    /* High Contrast Rules for How It Works */
    .high-contrast #howitworks-hero {
        background-color: #ffffff !important;
    }
    .high-contrast #howitworks-title,
    .high-contrast #howitworks-title span {
        color: #000000 !important;
    }
    .high-contrast .howitworks-circle {
        background-color: #000000 !important;
        mix-blend-mode: normal !important;
        opacity: 1 !important;
    }
    .high-contrast .get-started-btn {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
    }

    /* Exceptions for Mobile App Mockups */
    html.dark .qr-code-wrapper { background-color: #ffffff !important; }
    html.dark .qr-code-wrapper img { mix-blend-mode: normal !important; opacity: 1 !important; }
    html.dark .mockup-notification text,
    html.dark .mockup-notification p,
    html.dark .mockup-notification span { color: #0f172a !important; }
    html.dark .mockup-notification .text-gray-900 { color: #0f172a !important; }
    html.dark .mockup-notification .text-gray-500 { color: #64748b !important; }
</style>
<div class="bg-white text-slate-900 font-sans antialiased">
   
    <!-- Hero Section (Text Left, Image Right) -->
    <section id="howitworks-hero" class="relative bg-[#f8fafc] py-20 md:py-28 overflow-hidden border-b border-slate-100">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-indigo-50/40 hidden lg:block -skew-x-12 translate-x-24"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Text -->
                <div class="max-w-xl">
                    <h1 id="howitworks-title" class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                        Post your first task <br>
                        <span class="text-indigo-600">in minutes</span>
                    </h1>
                    <p class="mt-6 text-lg md:text-xl text-slate-600 leading-relaxed">
                        Whether you need a hand around the house or a specialized professional, Minijobz is the easiest way to get things done.
                    </p>
                    <div class="mt-10">
                        <a href="{{ route('post-task') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                            Post a task
                        </a>
                    </div>
                </div>
                <!-- Image -->
                <div class="relative">
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=1000&q=80" alt="Professional tasker" class="w-full h-auto object-contain">
                    </div>
                    <div class="howitworks-circle absolute -top-6 -right-6 w-32 h-32 bg-indigo-100 rounded-full mix-blend-multiply opacity-60"></div>
                </div>
            </div>
        </div>
    </section>
 
    <!-- SECTION 1 (Image LEFT, Text RIGHT) -->
    <section class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Image FIRST (Left) -->
                <div class="relative lg:order-1">
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="{{ asset('assets/img/Basics.jpg') }}" alt="Describe what you need" class="w-full h-auto object-contain">
                    </div>
                    <div class="howitworks-circle absolute -bottom-6 -left-6 w-40 h-40 bg-indigo-50 rounded-full"></div>
                </div>
                <!-- Text SECOND (Right) -->
                <div class="lg:order-2">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Describe what you need</h2>
                    <p class="text-lg md:text-xl text-slate-600 leading-relaxed mb-8">
                        It’s free to post. Simply tell us what you need done, when and where you need it. You can even upload photos to help Taskers understand the job.
                    </p>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium"><i data-feather="check" class="text-emerald-500 w-6 h-6"></i> Set a realistic budget</li>
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium"><i data-feather="check" class="text-emerald-500 w-6 h-6"></i> Choose a date and time</li>
                    </ul>
                    <a href="{{ route('post-task') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                        Post a task
                    </a>
                </div>
            </div>
        </div>
    </section>
 
    <!-- SECTION 2 (Text LEFT, Image RIGHT) -->
    <section class="py-20 md:py-28 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Text FIRST (Left) -->
                <div>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Set your budget</h2>
                    <p class="text-lg md:text-xl text-slate-600 leading-relaxed mb-8">
                        You're in control of your budget. Set the price you're willing to pay, or let Taskers suggest their own rates. Compare offers and choose the one that fits your needs and budget best.
                    </p>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium"><i data-feather="check" class="text-emerald-500 w-6 h-6"></i> Set your own price or receive quotes</li>
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium"><i data-feather="check" class="text-emerald-500 w-6 h-6"></i> Compare offers from multiple Taskers</li>
                    </ul>
                    <a href="{{ route('post-task') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                        Post a task
                    </a>
                </div>
                <!-- Image SECOND (Right) -->
                <div class="relative">
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="{{ asset('assets/img/Budget.jpg') }}" alt="Set your budget" class="w-full h-auto object-contain">
                    </div>
                    <div class="howitworks-circle absolute -top-6 -right-6 w-40 h-40 bg-indigo-100/50 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>
 
    <!-- SECTION 3 (Image LEFT, Text RIGHT) -->
    <section class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Image FIRST (Left) -->
                <div class="relative lg:order-1">
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="{{ asset('assets/img/Offer.jpg') }}" alt="Review & Choose" class="w-full h-auto object-contain">
                    </div>
                    <div class="howitworks-circle absolute -bottom-6 -left-6 w-40 h-40 bg-indigo-100/50 rounded-full"></div>
                </div>
                <!-- Text SECOND (Right) -->
                <div class="lg:order-2">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Review & Choose</h2>
                    <p class="text-lg md:text-xl text-slate-600 leading-relaxed mb-8">
                        Receive quotes from available Taskers. Review their profiles, ratings, and completion rates to find the perfect match for your task.
                    </p>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium">
                            <i data-feather="user-check" class="text-emerald-500 w-6 h-6"></i>
                            View Tasker profiles and reviews
                        </li>
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium">
                            <i data-feather="message-square" class="text-emerald-500 w-6 h-6"></i>
                            Chat directly with Taskers
                        </li>
                    </ul>
                    <a href="{{ route('post-task') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                        Post a task
                    </a>
                </div>
            </div>
        </div>
    </section>
 
    <!-- SECTION 4 (Text LEFT, Image RIGHT) -->
    <section class="py-20 md:py-28 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Text FIRST (Left) -->
                <div>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Get it done</h2>
                    <p class="text-lg md:text-xl text-slate-600 leading-relaxed mb-8">
                        When the task is complete, simply release the payment. It's that easy! Your payment is held securely by Minijobz Pay until the job is finished.
                    </p>
                    <div class="flex items-center gap-6 mb-10">
                        <div class="flex -space-x-4">
                            <img class="h-14 w-14 rounded-full ring-4 ring-white" src="https://i.pravatar.cc/100?u=11" alt="">
                            <img class="h-14 w-14 rounded-full ring-4 ring-white" src="https://i.pravatar.cc/100?u=12" alt="">
                            <img class="h-14 w-14 rounded-full ring-4 ring-white" src="https://i.pravatar.cc/100?u=13" alt="">
                        </div>
                        <p class="text-lg font-bold text-slate-500">20,000+ happy users</p>
                    </div>
                    <a href="{{ route('post-task') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                        Post a task
                    </a>
                </div>
                <!-- Image SECOND (Right) -->
                <div class="relative">
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="{{ asset('assets/img/Task_Complete.jpg') }}" alt="Get it done" class="w-full h-auto object-contain">
                    </div>
                    <div class="howitworks-circle absolute -top-6 -right-6 w-40 h-40 bg-indigo-100/50 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>
 
<!-- REDESIGNED Safety Section: We've got you covered -->
    <section class="py-20 md:py-28 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Main Heading -->
                <div>
                    <h2 class="text-5xl md:text-6xl font-black text-[#000033] tracking-tighter leading-none mb-8">
                        We've got you covered
                    </h2>
                    <p class="text-xl md:text-2xl text-[#000033] leading-snug mb-10 max-w-md">
                        Whether you’re a posting a task or completing a task, you can do both with the peace of mind that Minijobz is there to support.
                    </p>
                </div>
 
                <!-- Right: Two Column Features -->
                <div class="grid sm:grid-cols-2 gap-12">
                    <div>
                        <div class="text-[#000033] mb-4">
                            <i data-feather="user" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#000033] mb-4">Public liability insurance</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Minijobz Insurance covers you for any accidental injury to the customer or property damage whilst performing certain task activities.
                        </p>
                    </div>
                    <div>
                        <div class="text-[#000033] mb-4">
                            <i data-feather="star" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#000033] mb-4">Top rated insurance</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Minijobz Insurance is provided by world-class partners, ensuring some of the world’s most reputable, stable and innovative insurance support.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
 
    <!-- NEW Section: Ratings & Reviews -->
    <section class="py-20 md:py-28 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Image with Decorative Circle -->
                <div class="relative">
                    <!-- Main Image -->
                    <div class="relative z-10 rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white bg-slate-200">
                        <img src="{{ asset('assets/img/Review.jpg') }}" alt="Tasker" class="w-full h-auto object-contain">
                    </div>
 
                    <!-- Decorative Circle -->
                    <div class="howitworks-circle absolute -bottom-6 -left-6 w-40 h-40 bg-indigo-100/50 rounded-full"></div>
                </div>
 
                <!-- Right: Text Content -->
                <div class="lg:pl-12">
                    <h2 class="text-5xl md:text-6xl font-black text-[#000033] tracking-tighter leading-none mb-8">
                        Ratings & reviews
                    </h2>
                    <p class="text-lg md:text-xl text-[#000033] leading-relaxed mb-10">
                        Review Tasker's portfolios, skills, badges on their profile, and see their transaction verified ratings, reviews & completion rating (to see their reliability) on tasks they’ve previously completed on Minijobz. This empowers you to make sure you’re choosing the right person for your task.
                    </p>
                    <a href="{{ route('post-task') }}" class="get-started-btn inline-block bg-blue-50 text-blue-600 font-bold py-4 px-10 rounded-full hover:bg-blue-100 transition-colors">
                        Get started for free
                    </a>
                </div>
            </div>
        </div>
    </section>
 
    <!-- NEW Section: Communication -->
    <section class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Image Container -->
                <div class="relative">
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="{{ asset('assets/img/Messages.jpg') }}" alt="Communication" class="w-full h-auto object-contain">
                    </div>
                    <div class="howitworks-circle absolute -bottom-6 -right-6 w-40 h-40 bg-indigo-50 rounded-full -z-0"></div>
                </div>
 
                <!-- Right: Text Content -->
                <div class="lg:pl-12">
                    <h2 class="text-5xl md:text-6xl font-black text-[#000033] tracking-tighter leading-none mb-8">
                        Communication
                    </h2>
                    <p class="text-lg md:text-xl text-[#000033] leading-relaxed mb-10">
                        Use Minijobz to stay in contact from the moment your task is posted until it’s completed. Accept an offer and you can privately message the Tasker to discuss final details, and get your task completed.
                    </p>
                    <a href="{{ route('post-task') }}" class="get-started-btn inline-block bg-blue-50 text-blue-600 font-bold py-4 px-10 rounded-full hover:bg-blue-100 transition-colors">
                        Get started for free
                    </a>
                </div>
            </div>
        </div>
    </section>
 
    <!-- Take Minijobz Anywhere - Modern App Card -->
    <section class="py-24 px-4 md:px-6 bg-white transition-colors duration-300">
     
      <!-- Main Gradient Card -->
      <div class="max-w-7xl mx-auto bg-gradient-to-br from-indigo-600 to-violet-700 rounded-[2.5rem] shadow-2xl overflow-hidden relative">
       
        <!-- Decorative Background Glows -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-white opacity-10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-400 opacity-20 blur-[100px] rounded-full pointer-events-none translate-y-1/3 -translate-x-1/3"></div>
 
        <div class="grid lg:grid-cols-12 gap-12 items-center relative z-10 p-8 md:p-16">
         
          <!-- Left Content (Text + Buttons) -->
          <div class="lg:col-span-7 flex flex-col justify-center text-center lg:text-left">
            <div class="inline-flex items-center justify-center lg:justify-start gap-2 mb-6">
              <span class="px-3 py-1 rounded-full bg-indigo-500/30 border border-indigo-400/30 text-indigo-100 text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
                {{ __('index.mobile_app_badge') }}
              </span>
            </div>
           
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight">
              @lang('index.mobile_app_title')
            </h2>
           
            <p class="text-lg text-indigo-100 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
              {{ __('index.mobile_app_desc') }}
            </p>
 
            <!-- Buttons & QR Row -->
            <div class="flex flex-col sm:flex-row items-center gap-8 justify-center lg:justify-start">
             
              <!-- Store Buttons (Updated to White) -->
                <div class="flex gap-4">
                  <a href="#">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-12 cursor-pointer transition-transform hover:scale-105">
                  </a>
                  <a href="#">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-12 cursor-pointer transition-transform hover:scale-105">
                  </a>
                </div>
 
              <!-- Divider for Mobile -->
              <div class="hidden sm:block w-px h-24 bg-indigo-400/30"></div>
 
              <!-- QR Code Block -->
              <div class="hidden sm:flex flex-col items-center gap-3">
                <div class="qr-code-wrapper p-2 bg-white rounded-xl shadow-inner">
                  <!-- Placeholder QR Code -->
                  <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=MinijobzAppDownload" alt="Scan to download" class="w-24 h-24 mix-blend-multiply opacity-90">
                </div>
                <span class="text-xs font-medium text-indigo-200 tracking-wide uppercase">{{ __('index.scan_to_install') }}</span>
              </div>
           
            </div>
          </div>
 
          <!-- Right Image (Phone Mockup) -->
          <div class="lg:col-span-5 relative flex justify-center lg:justify-end h-full min-h-[300px] lg:min-h-auto mt-8 lg:mt-0">
            <!-- The Image Container - Tilted Effect -->
            <div class="relative w-64 md:w-80 lg:w-[22rem] transition-transform duration-500 hover:scale-[1.02] hover:-rotate-1">
              <!-- Phone Shadow/Glow -->
              <div class="absolute inset-4 bg-indigo-900 rounded-[3rem] blur-2xl opacity-60"></div>
             
              <!-- Actual Image -->
              <img
                src="https://assets.codepen.io/7729268/iphone-mockup-minijobz.png"
                onerror="this.src='assets/img/phone_14_01.webp'"
                alt="Minijobz App Interface"
                class="relative z-10 drop-shadow-2xl transform lg:translate-y-12"
              >
             
              <!-- Floating Elements (Decoration) -->
              <div class="mockup-notification absolute -left-8 top-1/4 z-20 bg-white/90 backdrop-blur-md p-3 rounded-2xl shadow-xl animate-bounce" style="animation-duration: 3s;">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                    <i data-feather="check" class="w-4 h-4"></i>
                  </div>
                  <div>
                    <p class="text-xs font-bold text-gray-900">Task Completed!</p>
                    <p class="text-[10px] text-gray-500">Payment Released</p>
                  </div>
                </div>
              </div>
 
              <!-- Notification Card -->
              <div class="mockup-notification absolute -right-4 bottom-8 z-20 bg-white/90 backdrop-blur-md p-3 rounded-2xl shadow-xl animate-bounce" style="animation-duration: 4s; animation-delay: 1s;">
                 <div class="flex items-center gap-3">
                  <img src="https://i.pravatar.cc/150?img=12" class="w-8 h-8 rounded-full border border-gray-200">
                  <div>
                    <p class="text-xs font-bold text-gray-900">New Offer: $45</p>
                    <p class="text-[10px] text-gray-500">James W. is interested</p>
                  </div>
                </div>
              </div>
 
            </div>
          </div>
 
        </div>
      </div>
    </section>
 
</div>
 
<script>
    feather.replace();
</script>
@endsection